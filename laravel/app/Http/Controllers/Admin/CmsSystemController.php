<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsAuditLog;
use App\Models\CmsMediaAsset;
use App\Models\CmsSiteSetting;
use App\Models\ContactMessage;
use App\Models\CmsContentEntry;
use App\Support\MediaUsage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsSystemController
{
    public function media(Request $request): View
    {
        $query = CmsMediaAsset::query()->orderByDesc('created_at');
        if ($request->filled('q')) $query->where('original_name', 'like', '%'.$request->string('q').'%');
        if (in_array($request->input('type'), ['images', 'documents', 'video'], true)) {
            $mime = ['images' => 'image/', 'documents' => 'application/pdf', 'video' => 'video/'][$request->input('type')];
            str_ends_with($mime, '/') ? $query->where('mime_type', 'like', $mime.'%') : $query->where('mime_type', $mime);
        }
        $assets = $query->paginate(36)->withQueryString();
        $assets->getCollection()->transform(function (CmsMediaAsset $asset): CmsMediaAsset {
            $asset->setAttribute('usage_count', MediaUsage::count($asset));
            return $asset;
        });
        return view('admin.system.media', compact('assets'));
    }
    public function uploadMedia(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate(['files' => ['required', 'array', 'max:20'], 'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,pdf,mp4,webm,mov', 'max:51200']]);
        $uploaded = [];
        foreach ($data['files'] as $file) {
            $path = $file->store('cms/'.now()->format('Y/m'), 'public');
            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
            $asset = CmsMediaAsset::query()->create(['disk' => 'public', 'path' => $path, 'original_name' => $file->getClientOriginalName(), 'mime_type' => $file->getMimeType() ?: 'application/octet-stream', 'size_bytes' => $file->getSize(), 'width' => $width, 'height' => $height, 'uploaded_by' => $request->user()->id]);
            $uploaded[] = ['id' => $asset->id, 'name' => $asset->original_name, 'mime' => $asset->mime_type, 'url' => $asset->publicUrl(), 'value' => 'storage/'.$asset->path, 'alt_text' => null];
        }
        $this->audit($request, 'media.upload', 'media', (string) count($data['files']));
        if ($request->expectsJson()) return response()->json(['items' => $uploaded]);
        return back()->with('success', 'Media uploaded. Select it in a content item to use it on the website.');
    }
    public function deleteMedia(Request $request, CmsMediaAsset $asset): RedirectResponse
    {
        $usageCount = MediaUsage::count($asset);
        if ($usageCount > 0 && ! $request->boolean('confirm_used')) {
            return back()->with('media_confirmation', ['id' => $asset->id, 'count' => $usageCount]);
        }
        $asset->delete();
        $this->audit($request, 'media.trash', 'media', (string) $asset->id);
        return back()->with('success', 'Media moved to Trash. The stored file was kept.');
    }

    public function mediaPicker(Request $request): JsonResponse
    {
        $query = CmsMediaAsset::query()->orderByDesc('created_at');
        if ($request->filled('q')) $query->where('original_name', 'like', '%'.$request->string('q').'%');
        if (in_array($request->input('type'), ['images', 'documents', 'video'], true)) {
            $mime = ['images' => 'image/', 'documents' => 'application/pdf', 'video' => 'video/'][$request->input('type')];
            str_ends_with($mime, '/') ? $query->where('mime_type', 'like', $mime.'%') : $query->where('mime_type', $mime);
        }
        $items = $query->paginate(24);
        return response()->json(['items' => $items->getCollection()->map(fn (CmsMediaAsset $asset) => ['id' => $asset->id, 'name' => $asset->original_name, 'mime' => $asset->mime_type, 'url' => $asset->publicUrl(), 'value' => $asset->disk === 'legacy-public' ? $asset->path : 'storage/'.$asset->path, 'alt_text' => $asset->alt_text]), 'next_page_url' => $items->nextPageUrl()]);
    }

    public function updateMediaAlt(Request $request, CmsMediaAsset $asset): RedirectResponse
    {
        $data = $request->validate(['alt_text' => ['nullable', 'string', 'max:1000']]);
        $asset->update(['alt_text' => trim($data['alt_text'] ?? '') ?: null]);
        return back()->with('success', 'Image description saved.');
    }
    public function settings(): View
    {
        $settings = CmsSiteSetting::query()->find(1);
        $fields = $settings?->fields ?? [];
        $social = is_array($fields['social_links'] ?? null) ? $fields['social_links'] : [];
        return view('admin.system.settings', [
            'settings' => $settings,
            'fields' => $fields,
            'phoneNumbersText' => implode("\n", is_array($fields['phone_numbers'] ?? null) ? $fields['phone_numbers'] : []),
            'openingHoursText' => implode("\n", is_array($fields['opening_hours'] ?? null) ? $fields['opening_hours'] : []),
            'socialLinks' => $social,
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'public_email' => ['required', 'email', 'max:255'],
            'notification_email' => ['nullable', 'email', 'max:255'],
            'phone_numbers_text' => ['required', 'string', 'max:1000'],
            'whatsapp_number' => ['nullable', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'po_box' => ['nullable', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:120'],
            'country' => ['required', 'string', 'max:120'],
            'opening_hours_text' => ['nullable', 'string', 'max:2000'],
            'social_links' => ['nullable', 'array'],
            'social_links.facebook' => ['nullable', 'url', 'max:500'],
            'social_links.instagram' => ['nullable', 'url', 'max:500'],
            'social_links.linkedin' => ['nullable', 'url', 'max:500'],
            'social_links.youtube' => ['nullable', 'url', 'max:500'],
            'social_links.x' => ['nullable', 'url', 'max:500'],
        ]);
        $fields = CmsSiteSetting::query()->find(1)?->fields ?? [];
        $fields = array_merge($fields, [
            'business_name' => trim($data['business_name']),
            'tagline' => trim($data['tagline'] ?? ''),
            'public_email' => mb_strtolower(trim($data['public_email'])),
            'notification_email' => mb_strtolower(trim($data['notification_email'] ?? $data['public_email'])),
            'phone_numbers' => array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['phone_numbers_text'])))),
            'whatsapp_number' => trim($data['whatsapp_number'] ?? ''),
            'address' => trim($data['address']),
            'po_box' => trim($data['po_box'] ?? ''),
            'city' => trim($data['city']),
            'country' => trim($data['country']),
            'opening_hours' => array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['opening_hours_text'] ?? '')))),
            'social_links' => array_filter(array_map('trim', $data['social_links'] ?? [])),
            'default_locale' => 'en',
            'enabled_locales' => ['en'],
        ]);
        CmsSiteSetting::query()->updateOrCreate(['id' => 1], ['fields' => $fields, 'updated_by' => $request->user()->id]);
        $this->audit($request, 'settings.update', 'settings', '1');
        return back()->with('success', 'Site settings saved.');
    }
    public function enquiries(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = ContactMessage::query()->orderByRaw("FIELD(status, 'new', 'contacted', 'closed')")->latest();
        if (in_array($status, ['new', 'contacted', 'closed'], true)) $query->where('status', $status);
        return view('admin.system.enquiries', ['enquiries' => $query->paginate(25)->withQueryString(), 'status' => $status]);
    }

    public function updateEnquiry(Request $request, ContactMessage $enquiry): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:new,contacted,closed'], 'admin_note' => ['nullable', 'string', 'max:5000']]);
        $enquiry->status = $data['status'];
        $enquiry->admin_note = $data['admin_note'] ?? null;
        $enquiry->contacted_at = $data['status'] === 'contacted' ? ($enquiry->contacted_at ?? now()) : $enquiry->contacted_at;
        $enquiry->closed_at = $data['status'] === 'closed' ? now() : null;
        $enquiry->save();
        $this->audit($request, 'enquiry.update', 'enquiry', (string) $enquiry->id);
        return back()->with('success', 'Enquiry updated.');
    }

    public function activity(): View
    {
        return view('admin.system.activity', ['logs' => CmsAuditLog::query()->with('user')->latest('created_at')->paginate(50)]);
    }

    public function trash(): View
    {
        return view('admin.system.trash', ['entries' => CmsContentEntry::onlyTrashed()->with('translations')->latest('deleted_at')->paginate(30), 'assets' => CmsMediaAsset::onlyTrashed()->latest('deleted_at')->limit(100)->get()]);
    }

    public function restoreContent(Request $request, int $entry): RedirectResponse
    {
        $content = CmsContentEntry::onlyTrashed()->findOrFail($entry);
        $content->restore();
        $this->audit($request, 'content.restore', $content->type, (string) $content->id);
        return back()->with('success', 'Content restored. Review it and publish it if needed.');
    }

    public function restoreMedia(Request $request, int $asset): RedirectResponse
    {
        $media = CmsMediaAsset::onlyTrashed()->findOrFail($asset);
        $media->restore();
        $this->audit($request, 'media.restore', 'media', (string) $media->id);
        return back()->with('success', 'Media restored.');
    }

    private function audit(Request $request, string $action, string $type, string $key): void
    {
        CmsAuditLog::query()->create(['user_id' => $request->user()->id, 'action' => $action, 'entity_type' => $type, 'entity_key' => $key, 'created_at' => now()]);
    }
}
