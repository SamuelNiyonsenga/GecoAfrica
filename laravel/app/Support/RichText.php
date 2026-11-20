<?php

namespace App\Support;

use Illuminate\Support\Str;

/** Helpers for showing rich text from the CMS on the public website. */
class RichText
{
    /**
     * Adds an id to every h2 so the "In this guide" box can link to it.
     *
     * @return array{html: string, toc: array<int, array{id: string, heading: string}>}
     */
    public static function withHeadingAnchors(string $html, string $prefix = 'section'): array
    {
        // Site-relative links and images ("/storage/…") follow APP_URL, so they also work in a subfolder install.
        $base = rtrim(asset(''), '/');
        $html = preg_replace('#\b(src|href)="/(?!/)#', '$1="'.$base.'/', $html) ?? $html;
        $toc = [];
        $used = [];
        $html = preg_replace_callback('#<h2>(.*?)</h2>#su', function (array $match) use (&$toc, &$used, $prefix): string {
            $text = trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $id = $prefix.'-'.(Str::slug($text) ?: (count($toc) + 1));
            while (isset($used[$id])) {
                $id .= '-'.(count($toc) + 1);
            }
            $used[$id] = true;
            $toc[] = ['id' => $id, 'heading' => $text];

            return '<h2 id="'.e($id).'">'.$match[1].'</h2>';
        }, $html) ?? $html;

        return ['html' => $html, 'toc' => $toc];
    }

    /** Converts the older "sections" format (heading + paragraphs) into rich text. */
    public static function fromSections(array $sections): string
    {
        $html = '';
        foreach ($sections as $section) {
            if (!is_array($section)) {
                continue;
            }
            if (trim((string) ($section['heading'] ?? '')) !== '') {
                $html .= '<h2>'.e($section['heading']).'</h2>';
            }
            foreach ((array) ($section['paragraphs'] ?? []) as $paragraph) {
                if (trim((string) $paragraph) !== '') {
                    $html .= '<p>'.e($paragraph).'</p>';
                }
            }
        }

        return $html;
    }

    public static function plainText(string $html): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '</h2>', '</h3>', '</li>', '<br>'], ' ', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    }

    /** "4 min read" from the words in the body (about 200 words a minute). */
    public static function readTime(string $html): string
    {
        $words = str_word_count(self::plainText($html));

        return max(1, (int) ceil($words / 200)).' min read';
    }

    /** Images used inside rich text, as site-relative paths. */
    public static function imagePaths(string $html): array
    {
        preg_match_all('#<img[^>]+src="([^"]+)"#i', $html, $matches);

        return array_map(static fn (string $src): string => ltrim((string) parse_url($src, PHP_URL_PATH), '/'), $matches[1] ?? []);
    }
}
