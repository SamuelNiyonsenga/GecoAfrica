# GECO Africa — premium polish delivery

Applies on top of branch `codex/premium-polish` at commit `1db6b12` (the WIP commit you pushed).
Unzip at the **repository root** (`C:\xampp\htdocs\GecoAfrica\GecoAfrica`) and let it overwrite.
No files were deleted, so nothing needs removing.

## After unzipping (from the `laravel` folder)

```powershell
composer install
php artisan migrate
php artisan cms:sync-page-fields
php artisan view:clear
php artisan test        # expect: 31 passed
```

Then commit and push:

```powershell
cd ..
git add -A laravel
git commit -m "Premium polish 4/5 + 5/5, review fixes, deploy docs"
git push
```

(Or, instead of the zip: `git am geco-premium.patch` from the repository root applies the same
changes as 4 separate commits with full messages, then `git push`.)

---

## Files (35)

### Docs
| File | Status | What changed |
|---|---|---|
| `laravel/DEPLOY.md` | NEW | Production deploy checklist (cPanel or VPS), credential rotation, upload limits, mail, caches, go-live checks, update routine. |
| `laravel/docs/CMS-GUIDE.md` | NEW | Plain-language guide for GECO's staff on using the CMS. |
| `laravel/README.md` | EDITED | Links to the deploy and CMS guides; "updating an existing install" steps. |

### Database
| File | Status | What changed |
|---|---|---|
| `laravel/database/migrations/2026_11_03_000002_make_cms_drafts_saveable.php` | NEW | Fixes the crash when creating any draft (published version column now allows empty) and guarantees the English locale exists. |

### Application code
| File | Status | What changed |
|---|---|---|
| `laravel/app/Support/CmsContentRepository.php` | EDITED | Safe defaults and fallbacks for blank CMS fields (no more page crashes), auto display numbers, placeholder image, category and related-service names derived from dropdown choices. |
| `laravel/app/Support/CmsContentTypes.php` | EDITED | Gallery/blog categories and blog "related service" become dropdowns; removed the "Original title/category" migration fields; specific hints; public-URL helper. |
| `laravel/app/Support/CmsPageFields.php` | NEW | Plain-English labels, hints and section groups for every page-editor field. |
| `laravel/app/Support/CmsSlug.php` | NEW | Shared web-address generator ("d’Ingénierie" becomes "d-ingenierie"), matching the editor. |
| `laravel/app/Support/Canonical.php` | NEW | Canonical URL built from APP_URL and the real page path (no redirecting trailing slash). |
| `laravel/app/Support/StructuredData.php` | EDITED | Page schema URL uses the new canonical helper. |
| `laravel/app/Http/Controllers/Admin/CmsContentController.php` | EDITED | Category dropdown options and validation; uses the shared slug generator. |
| `laravel/app/Http/Controllers/Admin/CmsPageController.php` | EDITED | Saving a page no longer wipes its canonical URL and preload image; passes grouped fields to the editor. |
| `laravel/app/Http/Controllers/BlogController.php` | EDITED | Category counts no longer crash on an article without a known category. |
| `laravel/app/Http/Controllers/ActivitiesController.php` | EDITED | Activities added in the CMS (no legacy id) no longer crash the page. |
| `laravel/app/Http/Controllers/SitemapController.php` | NEW | `/sitemap.xml` listing all pages and published articles. |
| `laravel/routes/web.php` | EDITED | Sitemap route; old `.php` URLs redirect straight to final URLs; redirects for the remaining old pages. |

### Admin (CMS) screens
| File | Status | What changed |
|---|---|---|
| `laravel/resources/views/admin/content/form.blade.php` | EDITED | Category dropdowns, locked web-address field with real public URL, media thumbnails, Remove only when a file is chosen. |
| `laravel/resources/views/admin/pages/edit.blade.php` | EDITED | Rewritten page editor: fields grouped by page section, plain labels and hints, Search & sharing collapsed, styled section order list. |
| `laravel/public/js/admin-structured.js` | EDITED | Web address keeps following the title; unsaved-changes guard on all editors; media thumbnails; friendly labels inside list editors. |
| `laravel/public/css/admin.css` | EDITED | Editor side panel no longer overflows; page editor, section manager and media field styles. |

### Public site
| File | Status | What changed |
|---|---|---|
| `laravel/public/css/site.css` | EDITED | Removed stray shadows inside cards, fixed full-screen mobile menu, footer alignment, mobile hero/facts, contact hero photo, WCAG AA contrast fixes, small layout fixes. |
| `laravel/resources/views/layouts/site.blade.php` | EDITED | Canonical/og:url from the real address, preloads use local files, opening hours in the footer. |
| `laravel/resources/views/contact.blade.php` | EDITED | Real GECO office photo in the hero (marked TODO for a higher-resolution version). |
| `laravel/resources/views/projects.blade.php` | EDITED | "Area figures" note only where one exists; empty Location/Client rows hidden. |
| `laravel/resources/views/about.blade.php` | EDITED | Profile note only shown when filled in. |
| `laravel/resources/views/partners.blade.php` | EDITED | No crash when a partner has no previous name. |
| `laravel/resources/views/activities.blade.php` | EDITED | No crash for activities added in the CMS. |
| `laravel/resources/views/blog-article.blade.php` | EDITED | Related-service box hidden when none chosen; tolerant of incomplete article sections. |
| `laravel/resources/views/gallery.blade.php` | EDITED | Removed the empty lightbox image source. |
| `laravel/public/images/placeholder.svg` | NEW | Branded placeholder shown when an item has no photo. |
| `laravel/public/robots.txt` | EDITED | Keeps search engines out of /admin and points to the sitemap. |

### Tests
| File | Status | What changed |
|---|---|---|
| `laravel/tests/Feature/CmsMinimalContentTest.php` | NEW | Every public page must work when each content type has an item with only its required field. |
| `laravel/tests/Feature/CmsPageEditorTest.php` | NEW | Page publish keeps hidden SEO fields; editor shows plain labels and hides technical fields. |
| `laravel/tests/Feature/SitemapTest.php` | NEW | Sitemap is valid XML with pages and articles, and no admin URLs. |
| `laravel/tests/Feature/CmsSlugConflictTest.php` | EDITED | Tolerates the English locale the new migration creates. |

---

## TODO(client) — things only the client can provide

- **SVG logo** (full colour + white version): the current header logo's tagline is unreadable at small sizes.
- **Higher-resolution photos**, especially the office entrance (Contact page) and the large "Who we are" homepage photo.
- **A real mailbox and SMTP details** for contact-form emails (see DEPLOY.md, step 5).
- **Support contact** at the end of docs/CMS-GUIDE.md.
