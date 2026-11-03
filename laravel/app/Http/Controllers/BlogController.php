<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
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
            $categoryCounts[$article['categorySlug']]++;
        }

        $itemList = array_map(static fn (array $article, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'BlogPosting',
                'headline' => $article['title'],
                'url' => 'https://gecoafrica.com/blog/'.$article['slug'].'/',
                'image' => 'https://gecoafrica.com/'.$article['image'],
                'author' => ['@id' => 'https://gecoafrica.com/#organization'],
            ],
        ], $articles, array_keys($articles));

        return view('blog', [
            'pageTitle' => 'Engineering Insights & Field Guides | GECO Africa',
            'pageDescription' => 'Practical insights on geotechnical investigations, road engineering and building project planning from GECO Africa in Rwanda.',
            'canonicalUrl' => 'https://gecoafrica.com/blog/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'Geotechnical field investigation by GECO Africa',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Engineering Insights | GECO Africa',
                'url' => 'https://gecoafrica.com/blog/',
                'description' => 'Practical engineering guides from GECO Africa.',
                'publisher' => ['@id' => 'https://gecoafrica.com/#organization'],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => count($articles),
                    'itemListElement' => $itemList,
                ],
            ],
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

        $related = array_values(array_filter(
            CmsContentRepository::blog(),
            static fn (array $item): bool => $item['slug'] !== $article['slug'],
        ));

        return view('blog-article', [
            'article' => $article,
            'relatedArticles' => array_slice($related, 0, 2),
            'pageTitle' => $article['title'].' | GECO Africa Insights',
            'pageDescription' => $article['summary'],
            'canonicalUrl' => 'https://gecoafrica.com/blog/'.$article['slug'].'/',
            'ogImage' => 'https://gecoafrica.com/'.$article['image'],
            'ogImageAlt' => $article['imageAlt'],
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $article['title'],
                'description' => $article['summary'],
                'image' => 'https://gecoafrica.com/'.$article['image'],
                'mainEntityOfPage' => 'https://gecoafrica.com/blog/'.$article['slug'].'/',
                'author' => ['@id' => 'https://gecoafrica.com/#organization'],
                'publisher' => ['@id' => 'https://gecoafrica.com/#organization'],
                'articleSection' => $article['category'],
            ],
        ]);
    }
}
