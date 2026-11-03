<?php

namespace App\Support;

class StructuredData
{
    public static function forPage(array $page, array $settings, string $pageKey): array
    {
        $phones = is_array($settings['phone_numbers'] ?? null) ? array_values(array_filter($settings['phone_numbers'], 'is_string')) : [];
        $socialLinks = is_array($settings['social_links'] ?? null) ? array_values(array_filter($settings['social_links'], static fn ($url): bool => is_string($url) && filter_var($url, FILTER_VALIDATE_URL))) : [];
        $siteUrl = rtrim((string) config('app.url', 'https://gecoafrica.com'), '/');
        $address = [
            '@type' => 'PostalAddress',
            'streetAddress' => $settings['address'] ?? 'Avenue des Poids Lourds, Source Oil Petrol Station building',
            'postOfficeBoxNumber' => $settings['po_box'] ?? '3711',
            'addressLocality' => $settings['city'] ?? 'Kigali',
            'addressCountry' => $settings['country'] ?? 'Rwanda',
        ];
        $organization = array_filter([
            '@type' => 'ProfessionalService',
            '@id' => $siteUrl.'/#organization',
            'name' => $settings['business_name'] ?? 'GECO Africa Ltd',
            'url' => $siteUrl.'/',
            'telephone' => $phones ?: ['+250 788 842 990', '+250 788 572 346'],
            'email' => $settings['public_email'] ?? 'info@gecoafrica.com',
            'address' => $address,
            'areaServed' => $settings['country'] ?? 'Rwanda',
            'sameAs' => $socialLinks ?: null,
        ], static fn ($value): bool => $value !== null && $value !== '');

        $pageSchema = [
            '@type' => $pageKey === 'contact' ? 'ContactPage' : ($pageKey === 'home' ? 'WebSite' : 'WebPage'),
            'name' => $page['pageTitle'] ?? ($settings['business_name'] ?? 'GECO Africa Ltd'),
            'description' => $page['pageDescription'] ?? '',
            'url' => $page['canonicalUrl'] ?? $siteUrl.'/',
            'isPartOf' => ['@id' => $siteUrl.'/#website'],
            'publisher' => ['@id' => $siteUrl.'/#organization'],
        ];
        if (! empty($page['ogImage'])) {
            $pageSchema['image'] = $page['ogImage'];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $organization,
                [
                    '@type' => 'WebSite',
                    '@id' => $siteUrl.'/#website',
                    'url' => $siteUrl.'/',
                    'name' => $settings['business_name'] ?? 'GECO Africa Ltd',
                    'publisher' => ['@id' => $siteUrl.'/#organization'],
                ],
                $pageSchema,
            ],
        ];
    }
}
