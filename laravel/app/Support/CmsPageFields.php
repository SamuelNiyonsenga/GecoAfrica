<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Plain-English labels, hints and groups for page fields in the page editor.
 */
class CmsPageFields
{
    /** Generated or technical values: never shown in the editor, always kept as they are on save. */
    public const PRESERVED = ['canonicalUrl', 'preloadImage'];

    /** Managed elsewhere (section manager / generated from Site Settings). */
    public const NOT_EDITABLE = ['homeSections', 'structuredData'];

    public const SEO_GROUP = 'Search & sharing';

    private const GROUP_PREFIXES = [
        'hero' => 'Hero carousel',
        'facts' => 'At a glance',
        'about' => 'About GECO',
        'principles' => 'Our principles',
        'companyPrinciples' => 'Our principles',
        'lab' => 'Laboratory',
        'laboratory' => 'Laboratory',
        'experience' => 'Experience',
        'contact' => 'Contact callout',
        'selectedExperience' => 'Selected experience',
        'projects' => 'Project highlights',
    ];

    private const EXACT = [
        'pageTitle' => ['Browser tab and Google title', 'Shown in the browser tab and as the blue headline in Google results. Keep it under about 60 characters.'],
        'pageDescription' => ['Google description', 'The short summary under the title in Google results. Aim for about 150 characters.'],
        'ogImage' => ['Social sharing image', 'Picture shown when this page is shared on WhatsApp, LinkedIn or Facebook. Use a full image address.'],
        'ogImageAlt' => ['Social image description', 'Describes the sharing image for people using screen readers.'],
        'heroSlides' => ['Slides', 'Each slide in the homepage carousel. Use Add and Remove to change them.'],
        'factsItems' => ['Facts', 'The figures in the strip under the carousel, e.g. 2007 · Engineering practice founded in Rwanda.'],
        'companyPrinciples' => ['Principles', 'The vision, objective and motto cards.'],
        'laboratoryCapabilities' => ['Laboratory capabilities', 'Capabilities listed in the laboratory section.'],
        'labTests' => ['Test types', 'Small tags listing laboratory tests, e.g. Consolidation, Permeability.'],
        'experience' => ['Experience list', 'The ticked list of experience areas.'],
        'experienceAccentPrefix' => ['Words before the highlight', 'Plain words shown just before the highlighted words in the heading.'],
        'experienceImageCaption' => ['Photo caption', 'Caption shown on the experience photo.'],
        'experienceImageDetail' => ['Photo caption detail', 'Small line under the photo caption.'],
        'labVisualLabel' => ['Photo label', 'Small label on the laboratory photo.'],
        'labVisualHeading' => ['Photo caption', 'Large caption on the laboratory photo.'],
        'labPriceListLabel' => ['Price list button text', 'Text on the laboratory price list button.'],
        'labPriceListUrl' => ['Price list link', 'Link to the price list file or page.'],
        'labEquipmentLabel' => ['Equipment list button text', 'Text on the equipment list link.'],
        'labEquipmentUrl' => ['Equipment list link', 'Link to the equipment list file or page.'],
        'projects' => ['Project highlights', 'Projects highlighted on this page.'],
        'selectedExperience' => ['Selected experience', 'Major projects listed on this page.'],
    ];

    private const SUFFIXES = [
        'AccentHeading' => ['Highlighted words', 'The second part of the heading, shown in coloured italic.'],
        'Heading' => ['Heading', 'Main heading of this section.'],
        'Eyebrow' => ['Small label above the heading', 'Short label in capitals shown above the heading.'],
        'Lede' => ['Lead sentence', 'Larger opening sentence under the heading.'],
        'Intro' => ['Introduction', 'Short paragraph introducing this section.'],
        'Body' => ['Main paragraph', 'Main text of this section.'],
        'Text' => ['Text', 'Main text of this section.'],
        'LinkLabel' => ['Button text', 'Text on this section\'s button or link.'],
        'LinkUrl' => ['Button link', 'Where the button goes: a page on this site such as /contact, or a full web address.'],
        'SignatureAccent' => ['Sign-off highlight', 'Second line of the sign-off, shown in italic.'],
        'Signature' => ['Sign-off line', 'Short closing line shown under the text.'],
    ];

    /** @return array{label: string, hint: string, group: string} */
    public static function meta(string $key): array
    {
        $group = self::group($key);
        if (isset(self::EXACT[$key])) {
            [$label, $hint] = self::EXACT[$key];
        } else {
            [$label, $hint] = self::fromSuffix($key);
        }

        return ['label' => $label, 'hint' => $hint, 'group' => $group];
    }

    /** @param array<string, mixed> $fields  @return array<string, array<string, mixed>> group => [key => value] in display order */
    public static function grouped(array $fields): array
    {
        $groups = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, self::PRESERVED, true) || in_array($key, self::NOT_EDITABLE, true)) {
                continue;
            }
            $groups[self::group($key)][$key] = $value;
        }
        // Search & sharing always last.
        if (isset($groups[self::SEO_GROUP])) {
            $seo = $groups[self::SEO_GROUP];
            unset($groups[self::SEO_GROUP]);
            $groups[self::SEO_GROUP] = $seo;
        }
        return $groups;
    }

    private static function group(string $key): string
    {
        if (in_array($key, ['pageTitle', 'pageDescription', 'ogImage', 'ogImageAlt'], true)) {
            return self::SEO_GROUP;
        }
        foreach (self::GROUP_PREFIXES as $prefix => $group) {
            if ($key === $prefix || (str_starts_with($key, $prefix) && ctype_upper($key[strlen($prefix)] ?? 'x'))) {
                return $group;
            }
        }
        return 'Page content';
    }

    /** @return array{0: string, 1: string} */
    private static function fromSuffix(string $key): array
    {
        foreach (self::SUFFIXES as $suffix => $meta) {
            if (str_ends_with($key, $suffix)) {
                return $meta;
            }
        }
        return [Str::of($key)->headline()->lower()->ucfirst()->toString(), 'Shown in this section of the page.'];
    }
}
