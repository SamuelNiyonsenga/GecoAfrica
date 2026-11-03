<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContentEntry;
use App\Models\ContactMessage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'draftCount' => CmsContentEntry::query()->where('status', 'draft')->count(),
            'publishedCount' => CmsContentEntry::query()->published()->count(),
            'newEnquiries' => ContactMessage::query()->where('status', 'new')->count(),
            'contentCount' => CmsContentEntry::query()->count(),
        ]);
    }
}