# Builds laravel\deploy\geco-laravel-upload.zip: a production copy of the site, ready to
# upload to cPanel and extract. Your own working folder is left untouched.
#
# Run from PowerShell:   cd C:\xampp\htdocs\GecoAfrica\GecoAfrica\laravel
#                        powershell -ExecutionPolicy Bypass -File deploy\build-upload.ps1

$ErrorActionPreference = 'Stop'
$root  = Split-Path -Parent $PSScriptRoot          # ...\laravel
$build = Join-Path $PSScriptRoot 'build'
$zip   = Join-Path $PSScriptRoot 'geco-laravel-upload.zip'

Write-Host '1/5 Preparing a clean build folder...'
if (Test-Path $build) { Remove-Item $build -Recurse -Force }
New-Item $build -ItemType Directory | Out-Null

Write-Host '2/5 Copying the site (without .env, vendor, tests, caches, logs)...'
$skip = @('.env', '.env.backup', '.env.production', 'vendor', 'node_modules', 'tests', 'deploy', 'storage', '.phpunit.result.cache', '.phpunit.cache', '.git')
Get-ChildItem $root -Force | Where-Object { $skip -notcontains $_.Name } |
    ForEach-Object { Copy-Item $_.FullName -Destination $build -Recurse -Force }

# Empty storage skeleton the app needs on the server.
foreach ($dir in 'app\public', 'framework\cache\data', 'framework\sessions', 'framework\views', 'logs') {
    New-Item (Join-Path $build "storage\$dir") -ItemType Directory -Force | Out-Null
}
# Never ship local caches (they contain C:\ paths) or local databases.
Get-ChildItem (Join-Path $build 'bootstrap\cache') -Filter *.php -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem (Join-Path $build 'database') -Filter *.sqlite -ErrorAction SilentlyContinue | Remove-Item -Force
$publicStorage = Join-Path $build 'public\storage'
if (Test-Path $publicStorage) { Remove-Item $publicStorage -Recurse -Force }

Write-Host '3/5 Installing production packages (composer install --no-dev)...'
Push-Location $build
composer install --no-dev --optimize-autoloader --no-interaction --no-progress
if ($LASTEXITCODE -ne 0) { Pop-Location; throw 'composer install failed' }
Pop-Location

Write-Host '4/5 Creating the zip...'
if (Test-Path $zip) { Remove-Item $zip -Force }
# Windows' built-in tar writes Linux-friendly paths (Compress-Archive does not).
tar -a -c -f $zip -C $build .
if ($LASTEXITCODE -ne 0) { throw 'creating the zip failed' }

Write-Host '5/5 Cleaning up...'
Remove-Item $build -Recurse -Force

$sizeMb = [math]::Round((Get-Item $zip).Length / 1MB, 1)
Write-Host ""
Write-Host "Done: $zip ($sizeMb MB)"
Write-Host 'Upload it to the subdomain folder in cPanel File Manager and Extract it there.'
