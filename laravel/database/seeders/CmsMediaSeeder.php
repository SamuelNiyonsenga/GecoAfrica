<?php

namespace Database\Seeders;

use App\Models\CmsContentEntry;
use App\Models\CmsMediaAsset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CmsMediaSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [];
        foreach (CmsContentEntry::query()->with('translations')->get() as $entry) {
            foreach ($entry->translations as $translation) {
                $this->collectPaths($translation->published_fields, $paths);
            }
        }
        foreach (['home', 'about', 'contact'] as $key) {
            $page = \App\Models\CmsPage::query()->where('page_key', $key)->first();
            if ($page) {
                $page->loadMissing('translations');
                $this->collectPaths($page->publishedFields('en'), $paths);
            }
        }

        foreach (array_keys($paths) as $relativePath) {
            $absolutePath = public_path($relativePath);
            if (!File::isFile($absolutePath)) {
                continue;
            }
            CmsMediaAsset::query()->firstOrCreate(
                ['path' => $relativePath],
                [
                    'disk' => 'legacy-public',
                    'original_name' => basename($relativePath),
                    'mime_type' => File::mimeType($absolutePath) ?: 'application/octet-stream',
                    'size_bytes' => File::size($absolutePath),
                ],
            );
        }
    }

    private function collectPaths(mixed $value, array &$paths): void
    {
        if (is_array($value)) {
            foreach ($value as $child) {
                $this->collectPaths($child, $paths);
            }
            return;
        }
        if (is_string($value) && preg_match('#^(images|assets)/[^?]+\.(jpg|jpeg|png|webp|svg|pdf|mp4|webm|mov)$#i', $value)) {
            $paths[$value] = true;
        }
    }
}