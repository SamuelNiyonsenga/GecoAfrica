# GECO Africa website

The Laravel application lives in this directory so the existing PHP site at the repository root remains available during the rebuild.

## Run locally

```powershell
php artisan serve
```

The homepage is served at `http://127.0.0.1:8000/`. Its Blade view uses the styles and small navigation script in `public/`; no frontend build step is needed for the current page.

## Current page structure

- `app/Http/Controllers/HomeController.php` supplies homepage content.
- `resources/views/layouts/site.blade.php` provides shared navigation, footer and search metadata.
- `resources/views/home.blade.php` contains the homepage sections.
- `public/css/site.css` and `public/js/site.js` hold the responsive presentation and menu behavior.

The homepage content is currently seeded in the controller. As the CMS and database are built, that content can move to managed records without changing the page structure.
