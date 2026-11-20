<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use App\Support\RichText;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $category = (string) $request->query('category', 'all');
        $categories = CmsContentRepository::blogCategories();

        if (! array_key_exists($category, $categories)) {
            $category = 'all';
        }

        $articles = array_values(array_filter(
            CmsContentRepository::blog(),
            static fn (array $article): bool => $category === 'all' || $article['categorySlug'] === $category,
        ));
        $categoryCounts = array_fill_keys(array_keys($categories), 0);
        $categoryCounts['all'] = count(CmsContentRepository::blog());
        foreach (CmsContentRepository::blog() as $article) {
            if (isset($categoryCounts[$article['categorySlug'] ?? ''])) {
                $categoryCounts[$article['categorySlug']]++;
            }
        }

        $itemList = array_map(static fn (array $article, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'BlogPosting',
                'headline' => $article['title'],
                'url' => route('blog.show', $article['slug']),
                'image' => Seo::absoluteUrl($article['image']),
                'author' => ['@id' => 'https://gecoafrica.com/#organization'],
            ],
        ], $articles, array_keys($articles));

        return view('blog', [
            'pageTitle' => 'Engineering Insights & Field Guides | GECO Africa',
            'pageDescription' => 'Practical insights on geotechnical investigations, road engineering and building project planning from GECO Africa in Rwanda.',
            'canonicalUrl' => 'https://gecoafrica.com/blog/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'Geotechnical field investigation by GECO Africa',
            'articles' => $articles,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'activeCategory' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $article = CmsContentRepository::findBlog($slug);

        abort_if($article === null, 404);

        $others = array_values(array_filter(
            CmsContentRepository::blog(),
            static fn (array $item): bool => $item['slug'] !== $article['slug'],
        ));
        // Same-category articles first, then the rest.
        usort($others, static fn (array $a, array $b): int => (int) (($b['categorySlug'] ?? '') === $article['categorySlug']) <=> (int) (($a['categorySlug'] ?? '') === $article['categorySlug']));
        $body = RichText::withHeadingAnchors($article['body'], 'guide-section');
        $url = route('blog.show', $article['slug']);
        $seo = Seo::forItem($article);
        $modified = $article['updatedAt'] ?? null;

        return view('blog-article', $seo + [
            'cmsPageKey' => 'article',
            'article' => $article,
            'body' => $body['html'],
            'toc' => $body['toc'],
            'relatedArticles' => array_slice($others, 0, 2),
            'nextArticle' => $others[0] ?? null,
            'ogType' => 'article',
            'articlePublishedTime' => $article['publishedAt']?->toAtomString(),
            'articleModifiedTime' => $modified?->toAtomString(),
            'schemaExtra' => [
                array_filter([
                    '@type' => 'BlogPosting',
                    'headline' => $article['title'],
                    'description' => $seo['pageDescription'],
                    'image' => $seo['ogImage'],
                    'url' => $url,
                    'mainEntityOfPage' => $url,
                    'datePublished' => $article['publishedAt']?->toAtomString(),
                    'dateModified' => ($modified ?? $article['publishedAt'])?->toAtomString(),
                    'author' => $article['author'] === 'GECO Africa'
                        ? ['@id' => rtrim((string) config('app.url'), '/').'/#organization']
                        : ['@type' => 'Person', 'name' => $article['author']],
                    'publisher' => ['@id' => rtrim((string) config('app.url'), '/').'/#organization'],
                    'articleSection' => $article['category'] ?: null,
                    'wordCount' => str_word_count(RichText::plainText($article['body'])),
                ]),
                Seo::breadcrumbs([['Home', route('home')], ['Insights', route('blog')], [$article['title'], $url]]),
            ],
        ]);
    }
}
