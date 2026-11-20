<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Cleans HTML from the CMS rich text editor down to a small, safe set of tags.
 * Anything pasted from Word or a web page loses its styles, scripts and classes.
 */
class HtmlSanitizer
{
    private const ALLOWED = [
        'p' => [], 'br' => [], 'h2' => [], 'h3' => [], 'h4' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [],
        'a' => ['href', 'title'], 'img' => ['src', 'alt', 'width', 'height'],
        'figure' => [], 'figcaption' => [], 'hr' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => [], 'td' => [],
    ];

    /** Tags removed together with everything inside them. */
    private const DROP_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea', 'noscript', 'template', 'svg', 'math', 'head', 'title', 'meta', 'link'];

    private const RENAME = ['b' => 'strong', 'i' => 'em', 'h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4', 'div' => 'p'];

    public static function clean(string $html): string
    {
        $html = trim($html);
        if ($html === '' || trim(strip_tags($html, '<img>')) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="cms-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('cms-root');
        if (!$root) {
            return e(strip_tags($html));
        }
        self::cleanChildren($root, $document);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }
        // Collapse empty paragraphs the editor leaves behind.
        $output = preg_replace('#<p>(\s|&nbsp;|<br>)*</p>#u', '', $output) ?? $output;

        return trim($output);
    }

    private static function cleanChildren(DOMNode $parent, DOMDocument $document): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node->nodeType === XML_COMMENT_NODE || $node->nodeType === XML_PI_NODE) {
                $parent->removeChild($node);
                continue;
            }
            if (!$node instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($node->tagName);
            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $parent->removeChild($node);
                continue;
            }
            if (isset(self::RENAME[$tag])) {
                $node = self::rename($node, self::RENAME[$tag], $document);
                $tag = self::RENAME[$tag];
            }
            if (!array_key_exists($tag, self::ALLOWED)) {
                // Unknown wrapper (span, font, section…): keep its text, drop the tag.
                self::cleanChildren($node, $document);
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);
                continue;
            }
            foreach (iterator_to_array($node->attributes) as $attribute) {
                if (!in_array(strtolower($attribute->name), self::ALLOWED[$tag], true)) {
                    $node->removeAttribute($attribute->name);
                }
            }
            if ($tag === 'a') {
                $href = trim($node->getAttribute('href'));
                if (!self::safeUrl($href)) {
                    $node->removeAttribute('href');
                } elseif (preg_match('#^https?://#i', $href) && !self::isOwnSite($href)) {
                    $node->setAttribute('rel', 'noopener');
                    $node->setAttribute('target', '_blank');
                }
            }
            if ($tag === 'img') {
                $src = trim($node->getAttribute('src'));
                if (!self::safeUrl($src) || str_starts_with(strtolower($src), 'mailto:') || str_starts_with(strtolower($src), 'tel:')) {
                    $parent->removeChild($node);
                    continue;
                }
                $node->setAttribute('loading', 'lazy');
                $node->setAttribute('decoding', 'async');
            }
            self::cleanChildren($node, $document);
        }
    }

    private static function rename(DOMElement $node, string $tag, DOMDocument $document): DOMElement
    {
        $replacement = $document->createElement($tag);
        while ($node->firstChild) {
            $replacement->appendChild($node->firstChild);
        }
        $node->parentNode->replaceChild($replacement, $node);

        return $replacement;
    }

    private static function safeUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return $scheme === '' || in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
    }

    private static function isOwnSite(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $own = strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST));

        return $host !== '' && ($host === $own || preg_replace('/^www\./', '', $host) === preg_replace('/^www\./', '', $own));
    }
}
