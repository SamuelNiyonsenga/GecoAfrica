<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\CmsContentEntry;
use App\Models\ContactMessage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $drafts = CmsContentEntry::query()->where('status', 'draft')->with('translations')->latest('updated_at')->limit(8)->get();
        $enquiries = ContactMessage::query()->where('status', 'new')->latest()->limit(5)->get();
        $recentChanges = CmsAuditLog::query()->with('user')->latest('created_at')->limit(8)->get();

        return view('admin.dashboard', [
            'draftCount' => CmsContentEntry::query()->where('status', 'draft')->count(),
            'newEnquiries' => ContactMessage::query()->where('status', 'new')->count(),
            'enquiries' => $enquiries,
            'drafts' => $drafts,
            'recentChanges' => $recentChanges,
        ]);
    }
}
