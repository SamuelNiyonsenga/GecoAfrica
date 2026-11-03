<?php

namespace Database\Seeders;

use App\Data\ActivitiesCatalog;
use App\Data\GalleryCatalog;
use App\Data\InsightsCatalog;
use App\Data\PartnersCatalog;
use App\Data\ProjectsCatalog;
use App\Data\ServicesCatalog;
use App\Data\TeamCatalog;
use App\Models\CmsContentEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ServicesCatalog::all() as $index => $service) {
            $this->entry('service', $service['slug'], $service, $index);
        }

        foreach (ProjectsCatalog::groups() as $groupIndex => $group) {
            $groupEntry = $this->entry('project_group', (string) $group['id'], $group, $groupIndex);
            foreach ($group['projects'] as $projectIndex => $project) {
                $slug = Str::slug('project-'.$group['id'].'-'.$projectIndex.'-'.$project['title']);
                $this->entry('project_reference', $slug, $project, $projectIndex, [
                    'parent_id' => $groupEntry->id,
                    'group_key' => (string) $group['id'],
                ]);
            }
        }

        foreach (ProjectsCatalog::featured() as $index => $project) {
            $this->entry('project_feature', Str::slug('featured-'.$index.'-'.$project['title']), $project, $index, ['is_featured' => true]);
        }

        foreach (TeamCatalog::core() as $index => $member) {
            $this->entry('team_member', $member['slug'], $member, $index, ['group_key' => 'core']);
        }
        foreach (TeamCatalog::supporting() as $index => $member) {
            $this->entry('team_member', Str::slug($member['name']), $member, $index, ['group_key' => 'supporting']);
        }

        foreach (PartnersCatalog::all() as $index => $partner) {
            $this->entry('partner', Str::slug($partner['name']), $partner, $index);
        }
        foreach (GalleryCatalog::all() as $index => $photo) {
            $slug = Str::slug('gallery-'.$index.'-'.$photo['file']);
            $this->entry('gallery_item', $slug, $photo, $index, ['group_key' => $photo['category'] ?? null]);
        }
        foreach (GalleryCatalog::categories() as $index => $category) {
            if (($category['id'] ?? 'all') === 'all') {
                continue;
            }
            $this->entry('gallery_category', $category['id'], $category, $index);
        }
        foreach (InsightsCatalog::all() as $index => $article) {
            $this->entry('blog_post', $article['slug'], $article, $index, ['group_key' => $article['categorySlug'] ?? null]);
        }
        foreach (InsightsCatalog::categories() as $slug => $label) {
            $this->entry('blog_category', (string) $slug, ['slug' => $slug, 'name' => $label], 0);
        }
        foreach (ActivitiesCatalog::all() as $index => $activity) {
            $this->entry('activity', 'activity-'.$activity['id'], $activity, $index, ['group_key' => Str::slug($activity['category'] ?? '')]);
        }
    }

    private function entry(string $type, string $slug, array $fields, int $order, array $meta = []): CmsContentEntry
    {
        unset($fields['slug']);
        $entry = CmsContentEntry::withTrashed()->firstOrCreate(
            ['type' => $type, 'slug' => Str::limit($slug, 175, '')],
            array_merge([
                'status' => 'published',
                'sort_order' => $order,
                'published_at' => now(),
            ], $meta),
        );

        if (!$entry->translations()->where('locale_code', 'en')->exists()) {
            $entry->translations()->create(['locale_code' => 'en', 'published_fields' => $fields, 'draft_fields' => $fields]);
        }

        return $entry;
    }
}