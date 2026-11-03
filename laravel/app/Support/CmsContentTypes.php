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
                'category' => ['label' => 'Category', 'type' => 'text'],
                'image' => ['label' => 'Photo or video', 'type' => 'media'],
                'alt' => ['label' => 'Image description', 'type' => 'text'],
                'caption' => ['label' => 'Caption', 'type' => 'textarea'],
                'legacyTitle' => ['label' => 'Original title', 'type' => 'text'],
                'legacyCategory' => ['label' => 'Original category', 'type' => 'text'],
            ]],
            'blog' => ['type' => 'blog_post', 'label' => 'Blog and insights', 'singular' => 'article', 'fields' => [
                'title' => ['label' => 'Article title', 'type' => 'text', 'required' => true],
                'category' => ['label' => 'Category name', 'type' => 'text'],
                'categorySlug' => ['label' => 'Category key', 'type' => 'text'],
                'eyebrow' => ['label' => 'Short heading', 'type' => 'text'],
                'summary' => ['label' => 'Summary', 'type' => 'textarea'],
                'image' => ['label' => 'Cover image', 'type' => 'media'],
                'imageAlt' => ['label' => 'Cover image description', 'type' => 'text'],
                'readTime' => ['label' => 'Reading time', 'type' => 'text'],
                'serviceAnchor' => ['label' => 'Related service key', 'type' => 'text'],
                'serviceLabel' => ['label' => 'Related service name', 'type' => 'text'],
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
}