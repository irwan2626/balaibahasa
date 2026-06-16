<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use App\Models\CommunityStory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'communities' => CommunityAccountRequest::query()->count(),
            'pending_communities' => CommunityAccountRequest::query()->where('status', 'pending')->count(),
            'published_stories' => CommunityStory::query()->where('status', 'published')->count(),
            'submitted_stories' => CommunityStory::query()->where('status', 'submitted')->count(),
        ];

        $activities = CommunityStory::query()
            ->with('account')
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard', compact('stats', 'activities'));
    }
}
