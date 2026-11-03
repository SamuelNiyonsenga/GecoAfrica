<?php

namespace App\Console\Commands;

use App\Models\CmsPage;
use App\Support\CmsPageDefaults;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCmsPageFields extends Command
{
    protected $signature = 'cms:sync-page-fields';

    protected $description = 'Add new default fields to English CMS page drafts and published versions without replacing existing content';

    public function handle(): int
    {
        $defaults = app(CmsPageDefaults::class)->all();

        foreach ($defaults as $pageKey => $defaultFields) {
            unset($defaultFields['structuredData']);
            $addedDraft = 0;
            $addedPublished = 0;

            DB::transaction(function () use ($pageKey, $defaultFields, &$addedDraft, &$addedPublished): void {
                $page = CmsPage::query()->firstOrCreate(['page_key' => $pageKey]);
                $translation = $page->translations()->firstOrNew(['locale_code' => 'en']);
                $draft = $translation->draft_fields ?? [];
                $published = $translation->published_fields ?? [];

                unset($draft['structuredData'], $published['structuredData']);
                foreach ($defaultFields as $key => $value) {
                    if (! array_key_exists($key, $draft)) {
                        $draft[$key] = $value;
                        $addedDraft++;
                    }
                    if (! array_key_exists($key, $published)) {
                        $published[$key] = $value;
                        $addedPublished++;
                    }
                }

                $translation->fill(['draft_fields' => $draft, 'published_fields' => $published])->save();
            });

            $this->line("{$pageKey}: {$addedDraft} draft field(s), {$addedPublished} published field(s) added.");
        }

        return self::SUCCESS;
    }
}
