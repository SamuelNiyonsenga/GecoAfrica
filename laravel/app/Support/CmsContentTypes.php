<?php

namespace App\Support;

class CmsContentTypes
{
    public static function all(): array
    {
        $types = [
            'services' => ['type' => 'service', 'label' => 'Services', 'singular' => 'service', 'fields' => [
                'number' => ['label' => 'Display number', 'type' => 'text'],
                'title' => ['label' => 'Service name', 'type' => 'text', 'required' => true],
                'summary' => ['label' => 'Short description', 'type' => 'textarea'],
                'intro' => ['label' => 'Introduction', 'type' => 'textarea'],
                'image' => ['label' => 'Photo', 'type' => 'media'],
                'imageAlt' => ['label' => 'Photo description', 'type' => 'text'],
                'imageCaption' => ['label' => 'Photo caption', 'type' => 'text'],
                'tags' => ['label' => 'Tags (one per line)', 'type' => 'list'],
                'items' => ['label' => 'What this service includes (one per line)', 'type' => 'list'],
                'priceList' => ['label' => 'Downloadable document', 'type' => 'media'],
            ]],
            'project-groups' => ['type' => 'project_group', 'label' => 'Project groups', 'singular' => 'project group', 'fields' => [
                'number' => ['label' => 'Display number', 'type' => 'text'],
                'title' => ['label' => 'Group title', 'type' => 'text', 'required' => true],
                'intro' => ['label' => 'Introduction', 'type' => 'textarea'],
            ]],
            'projects' => ['type' => 'project_reference', 'label' => 'Project references', 'singular' => 'project reference', 'parent_type' => 'project_group', 'fields' => [
                'title' => ['label' => 'Project title', 'type' => 'text', 'required' => true],
                'location' => ['label' => 'Location', 'type' => 'text'],
                'client' => ['label' => 'Client', 'type' => 'text'],
            ]],
            'featured-projects' => ['type' => 'project_feature', 'label' => 'Featured projects', 'singular' => 'featured project', 'fields' => [
                'number' => ['label' => 'Display number', 'type' => 'text'],
                'title' => ['label' => 'Project title', 'type' => 'text', 'required' => true],
                'location' => ['label' => 'Location', 'type' => 'text'],
                'category' => ['label' => 'Category', 'type' => 'text'],
                'summary' => ['label' => 'Summary', 'type' => 'textarea'],
                'image' => ['label' => 'Photo', 'type' => 'media'],
                'imageAlt' => ['label' => 'Photo description', 'type' => 'text'],
                'areaNote' => ['label' => 'Additional note', 'type' => 'textarea'],
            ]],
            'team' => ['type' => 'team_member', 'label' => 'Team', 'singular' => 'team member', 'group_options' => ['core' => 'Core team', 'supporting' => 'Supporting team'], 'fields' => [
                'name' => ['label' => 'Full name', 'type' => 'text', 'required' => true],
                'shortName' => ['label' => 'Display name', 'type' => 'text'],
                'role' => ['label' => 'Role', 'type' => 'text'],
                'image' => ['label' => 'Portrait', 'type' => 'media'],
                'cv' => ['label' => 'CV document', 'type' => 'media'],
                'alt' => ['label' => 'Portrait description', 'type' => 'text'],
                'bio' => ['label' => 'Biography', 'type' => 'textarea'],
                'profile' => ['label' => 'Profile note', 'type' => 'textarea'],
                'focus' => ['label' => 'Professional focus', 'type' => 'textarea'],
                'expertise' => ['label' => 'Expertise (one per line)', 'type' => 'list'],
                'qualification' => ['label' => 'Qualification', 'type' => 'text'],
                'experience' => ['label' => 'Experience', 'type' => 'textarea'],
                'initials' => ['label' => 'Initials', 'type' => 'text'],
            ]],
            'partners' => ['type' => 'partner', 'label' => 'Partners', 'singular' => 'partner', 'fields' => [
                'name' => ['label' => 'Partner name', 'type' => 'text', 'required' => true],
                'shortName' => ['label' => 'Short name', 'type' => 'text'],
                'category' => ['label' => 'Category', 'type' => 'text'],
                'image' => ['label' => 'Logo or photo', 'type' => 'media'],
                'alt' => ['label' => 'Image description', 'type' => 'text'],
                'website' => ['label' => 'Website address', 'type' => 'url'],
                'websiteLabel' => ['label' => 'Website label', 'type' => 'text'],
                'summary' => ['label' => 'Description', 'type' => 'textarea'],
            ]],
            'gallery' => ['type' => 'gallery_item', 'label' => 'Gallery', 'singular' => 'gallery item', 'fields' => [
                'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
                'category' => ['label' => 'Category', 'type' => 'category', 'source' => 'gallery_category', 'hint' => 'Which gallery filter this photo appears under. Manage the list in Gallery categories.'],
                'image' => ['label' => 'Photo or video', 'type' => 'media'],
                'alt' => ['label' => 'Image description', 'type' => 'text'],
                'caption' => ['label' => 'Caption', 'type' => 'textarea', 'hint' => 'Shown under the photo when a visitor opens it full size.'],
            ]],
            'blog' => ['type' => 'blog_post', 'label' => 'Blog and insights', 'singular' => 'article', 'fields' => [
                'title' => ['label' => 'Article title', 'type' => 'text', 'required' => true],
                'categorySlug' => ['label' => 'Category', 'type' => 'category', 'source' => 'blog_category', 'hint' => 'Which Insights filter this article appears under. Manage the list in Blog categories.'],
                'eyebrow' => ['label' => 'Short heading', 'type' => 'text'],
                'summary' => ['label' => 'Summary', 'type' => 'textarea'],
                'image' => ['label' => 'Cover image', 'type' => 'media'],
                'imageAlt' => ['label' => 'Cover image description', 'type' => 'text'],
                'readTime' => ['label' => 'Reading time', 'type' => 'text'],
                'serviceAnchor' => ['label' => 'Related service', 'type' => 'category', 'source' => 'service', 'hint' => 'The service linked in the "Related service" box beside the article.'],
                'sections' => ['label' => 'Article sections', 'type' => 'json', 'template' => [['heading' => '', 'paragraphs' => ['']]]],
            ]],
            'gallery-categories' => ['type' => 'gallery_category', 'label' => 'Gallery categories', 'singular' => 'gallery category', 'fields' => [
                'name' => ['label' => 'Category name', 'type' => 'text', 'required' => true],
            ]],
            'blog-categories' => ['type' => 'blog_category', 'label' => 'Blog categories', 'singular' => 'blog category', 'fields' => [
                'name' => ['label' => 'Category name', 'type' => 'text', 'required' => true],
            ]],
            'activities' => ['type' => 'activity', 'label' => 'Activities and events', 'singular' => 'activity', 'fields' => [
                'title' => ['label' => 'Activity title', 'type' => 'text', 'required' => true],
                'client' => ['label' => 'Client or organizer', 'type' => 'text'],
                'date' => ['label' => 'Date', 'type' => 'text'],
                'duration' => ['label' => 'Duration', 'type' => 'text'],
                'category' => ['label' => 'Category', 'type' => 'text'],
                'location' => ['label' => 'Location', 'type' => 'text'],
            ]],
        ];

        $hints = [
            'number' => 'Small marker shown before this item in homepage and service sections.',
            'title' => 'Main heading visitors see for this item.',
            'name' => 'Name visitors see in cards and detail pages.',
            'shortName' => 'Compact name used where a full name would not fit.',
            'role' => 'Role displayed below the team member name.',
            'initials' => 'Letters used in the portrait placeholder when no photo is set.',
            'profile' => 'Short profile note displayed in the team listing.',
            'focus' => 'Professional focus shown in the team profile.',
            'experience' => 'Experience detail shown in the team profile.',
            'image' => 'Photo or logo displayed on the public website.',
            'imageAlt' => 'Accessible description read by screen readers for the image.',
            'alt' => 'Accessible description read by screen readers for the image.',
            'summary' => 'Short description shown in listings and search previews.',
            'intro' => 'Introductory copy shown at the top of this page or detail.',
            'items' => 'The service inclusions listed on its public detail page.',
            'tags' => 'Small service labels shown on the card and detail page.',
            'website' => 'External website visitors can open from this partner profile.',
            'sections' => 'Article headings and paragraphs shown in the article body.',
            'category' => 'Short label shown above the name on its card, e.g. Water & field services.',
            'location' => 'Where the work took place, e.g. Nyanza District.',
            'client' => 'The client or organisation the work was for.',
            'date' => 'When it happened, as visitors should read it, e.g. Jun 2021.',
            'duration' => 'How long it ran, e.g. June to July 2021.',
            'websiteLabel' => 'Short web address shown as the link text, e.g. afridrillers.com.',
            'eyebrow' => 'Small label in capitals shown above the article title.',
            'readTime' => 'Shown next to the category, e.g. 5 min read.',
            'qualification' => 'Degree or professional title shown in the team profile.',
            'cv' => 'PDF visitors can open from the "View CV" button.',
            'priceList' => 'File visitors can download from this service, e.g. a price list PDF.',
            'areaNote' => 'Extra detail visitors can expand under the project summary.',
            'imageCaption' => 'Caption shown on the photo.',
            'bio' => 'Main biography paragraph on the team page.',
            'expertise' => 'One area per line. Shown as small tags under the profile.',
        ];
        foreach ($types as &$type) {
            foreach ($type['fields'] as $key => &$field) {
                $field['hint'] ??= $hints[$key] ?? 'Shown with this item on the public website.';
            }
            unset($field);
        }
        unset($type);
        return $types;
    }

    public static function get(string $section): ?array
    {
        return self::all()[$section] ?? null;
    }

    public static function forType(string $type): ?array
    {
        foreach (self::all() as $definition) {
            if ($definition['type'] === $type) {
                return $definition;
            }
        }
        return null;
    }

    /**
     * Empty values for every field a content type defines, so public views never
     * hit an undefined key when the client leaves optional fields blank.
     */
    public static function defaultsForType(string $type): array
    {
        $defaults = [];
        foreach (self::forType($type)['fields'] ?? [] as $key => $spec) {
            $defaults[$key] = in_array($spec['type'], ['list', 'json'], true) ? [] : '';
        }
        return $defaults;
    }

    /** Public page where an item of this type appears, with its anchor when the page has one. */
    public static function publicUrl(string $type, ?string $slug = null): ?string
    {
        return match ($type) {
            'blog_post' => $slug ? route('blog.show', $slug) : route('blog'),
            'blog_category' => route('blog'),
            'service' => route('services').($slug ? '#'.$slug : ''),
            'team_member' => route('team').($slug ? '#'.$slug : ''),
            'partner' => route('partners'),
            'gallery_item', 'gallery_category' => route('gallery'),
            'project_group', 'project_reference', 'project_feature' => route('projects'),
            'activity' => route('activities'),
            default => null,
        };
    }
}