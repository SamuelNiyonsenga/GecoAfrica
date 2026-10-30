# GECO Africa website and CMS

The Laravel website and its English CMS live in this folder. The earlier PHP site at the repository root remains separate. CMS content is stored in the `geco_cms` MySQL/MariaDB database and is seeded from Laravel's existing content catalogs; public URLs stay unchanged.

## Run locally with XAMPP

1. Start Apache and MySQL from the XAMPP control panel.
2. Make sure the `geco_cms` database exists in MySQL. For a fresh local setup, copy `.env.example` to `.env`, set the local database credentials, then run `php artisan key:generate` once.
3. From this folder, prepare and seed the CMS:

   ```powershell
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

4. Create the first CMS administrator. The command asks for the person's name, email, and password without displaying the password:

   ```powershell
   php artisan cms:user:create
   ```

   The first administrator can add or deactivate other users from **Admin → Users**. All active CMS users have the same access.

5. Start the Laravel site:

   ```powershell
   php artisan serve
   ```

   Website: `http://127.0.0.1:8000/`  
   CMS: `http://127.0.0.1:8000/admin/login`

## What the CMS manages

- English page content and search metadata for all current public pages; future translations have locale-specific storage.
- Services, project groups and references, featured projects, team, partners, gallery items and categories, blog posts and categories, and activities.
- Homepage section visibility and display order.
- A reusable media library, site contact settings, contact enquiries, user accounts, audit history, version restore, and recoverable Trash.

Content changes are saved as drafts and become public only when published. Uploaded files use Laravel's public storage disk; keep the `public/storage` link in place so their URLs work.