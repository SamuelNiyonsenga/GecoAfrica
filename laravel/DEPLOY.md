# Deploying the GECO Africa website

This guide takes the Laravel site in this folder (`laravel/`) to production at
`https://gecoafrica.com`. The old PHP site at the repository root is **not** deployed.

> The steps work on both cPanel shared hosting and a VPS. Where they differ, both are given.

---

## 0. Before you start — security (do this first)

The old PHP site in this repository contains real credentials in plain text, and the
repository was public:

| File | What is exposed |
|---|---|
| `geco_db/PdoConnect.php`, `ProfileDropzone/config.php` | Database host, user and password |
| `geco_email/sendrequest.php` | SMTP email login and password |
| `sendquery.php` | SMS API call (check for an API key in the URL) |
| `database_Backup/cspchukc_gecoafrica.sql.gz` | Full database dump, including user tables |

1. Make the GitHub repository **private**.
2. **Change** the database password, the email account password and the SMS API key at the
   hosting provider. Removing the files does not remove them from git history.
3. After the new site is live (step 9), remove the old site from the repository — see step 10.

---

## 1. Server requirements

- PHP **8.2 or newer** with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`,
  `xml`, `ctype`, `json`, `fileinfo`, `gd` (image sizes in the media library), `intl`.
- MySQL 8 / MariaDB 10.4 or newer.
- Composer 2.
- **Upload limits** — the media library accepts files up to 50 MB. In `php.ini` (cPanel:
  *MultiPHP INI Editor*) set:
  ```ini
  upload_max_filesize = 50M
  post_max_size = 60M
  max_file_uploads = 20
  ```
  With the common 2 MB default, uploads of larger photos or PDFs will fail.

No queue worker, cron job, Node.js build or Redis is needed: CSS and JS are plain files in
`public/`, and contact emails are sent immediately.

---

## 2. Get the code onto the server

**VPS:** `git clone` the repository (private, deploy key) into e.g. `/var/www/gecoafrica`.

**cPanel:** upload the `laravel/` folder **outside** `public_html`, e.g. to
`/home/USER/gecoafrica/` (Git Version Control in cPanel also works).

The web server must serve **`laravel/public`** and nothing else — never the repository root
or the `laravel/` folder itself (that would expose `.env`).

- **VPS (Nginx):** `root /var/www/gecoafrica/laravel/public;` with the standard Laravel
  `try_files $uri $uri/ /index.php?$query_string;` location block.
- **cPanel:** set the domain's *Document Root* to `gecoafrica/laravel/public`
  (Domains → gecoafrica.com → Manage). If the host does not allow that, replace the contents
  of `public_html` with the contents of `laravel/public` and edit the two paths in
  `public_html/index.php` to point at `../gecoafrica/laravel/vendor/autoload.php` and
  `../gecoafrica/laravel/bootstrap/app.php`.

---

## 3. Install dependencies

From `laravel/`:

```bash
composer install --no-dev --optimize-autoloader
```

---

## 4. Create the database

Create an empty MySQL database and a user with full rights on it only
(cPanel: *MySQL Databases*). Note the name, user and password.

---

## 5. Configure `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env`:

```dotenv
APP_NAME="GECO Africa"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gecoafrica.com        # exact, no trailing slash — canonical URLs and the sitemap use it

LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=...                       # from step 4
DB_USERNAME=...
DB_PASSWORD=...

SESSION_SECURE_COOKIE=true            # site runs on https

# Contact form emails — TODO(client): a real mailbox and its SMTP details
MAIL_MAILER=smtp
MAIL_HOST=mail.gecoafrica.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=no-reply@gecoafrica.com
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS="no-reply@gecoafrica.com"
MAIL_FROM_NAME="GECO Africa website"
CONTACT_RECIPIENT=info@gecoafrica.com # fallback; the CMS "Notification email" setting wins
```

Use a **new** mail password, not the one exposed in the old site.

---

## 6. Database, content and storage

```bash
php artisan migrate --force
php artisan db:seed --force             # first deploy only: loads all current site content
php artisan storage:link                # makes uploaded media visible
```

On cPanel without SSH access to `storage:link`, create a symlink
`public/storage → ../storage/app/public` with the File Manager, or ask the host.

Folder permissions: the web server user must be able to write to `storage/` and
`bootstrap/cache/` (`chmod -R 775 storage bootstrap/cache` and the right owner on a VPS).

---

## 7. Create the first CMS administrator

```bash
php artisan cms:user:create
```

It asks for name, email and password (hidden). That person can add or deactivate other
users from **Admin → Users**.

---

## 8. Production caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Run these again after every update (step 11).

---

## 9. Go-live checks

- [ ] `https://gecoafrica.com` loads over HTTPS (cPanel: *SSL/TLS Status* → AutoSSL; VPS: Certbot).
- [ ] `http://` and `www.` redirect to `https://gecoafrica.com`.
- [ ] `https://gecoafrica.com/admin/login` — sign in with the account from step 7.
- [ ] Send a test message from the Contact page. It appears in **Admin → Enquiries** and
      the email arrives at the notification address.
- [ ] Upload an image in **Admin → Media library** and see it in the picker.
- [ ] Old links redirect: `/about.php`, `/project.php`, `/events.php?id=1`, `/team1.php`.
- [ ] `https://gecoafrica.com/sitemap.xml` lists the pages; submit it in Google Search Console.
- [ ] `https://gecoafrica.com/.env` returns 404 (proves the document root is `public/`).

---

## 10. Remove the old site from the repository

Once the new site is live and checked, the old PHP files are no longer needed. From the
repository root:

```bash
git rm -r --cached database_Backup geco_db geco_email geco_cv Login-Portal ProfileDropzone
# …and the remaining old root files (*.php, css/, js/, images/ …) if you no longer want them
git commit -m "Remove old PHP site"
```

To also purge the secrets from history, use `git filter-repo` or BFG Repo-Cleaner — but
changing the passwords (step 0) is what actually closes the leak.

---

## 11. Updating the site later

```bash
git pull                                 # or upload the changed files
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cms:sync-page-fields         # adds any new editable page fields, never overwrites edits
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Do **not** run `db:seed` again on a live site with client edits — use
`cms:sync-page-fields` instead.

---

## Backups

Back up two things regularly: the MySQL database and `storage/app/public` (uploaded media).
cPanel's *Backup* covers both; on a VPS, a nightly `mysqldump` plus a copy of that folder.
