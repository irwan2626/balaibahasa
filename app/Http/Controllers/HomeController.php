<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use App\Models\CommunityStory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $publishedStories = CommunityStory::query()
            ->where('status', 'published')
            ->with('account')
            ->latest('reviewed_at')
            ->latest()
            ->limit(3)
            ->get();

        $registeredCommunities = CommunityAccountRequest::query()
            ->latest()
            ->limit(4)
            ->get();

        return view('welcome', compact('publishedStories', 'registeredCommunities'));
    }

    public function showStory(CommunityStory $story): View
    {
        abort_unless($story->status === 'published', 404);

        $story->load('account');

        $relatedStories = CommunityStory::query()
            ->where('status', 'published')
            ->whereKeyNot($story->id)
            ->latest('reviewed_at')
            ->latest()
            ->limit(3)
            ->get();

        return view('stories.show', compact('story', 'relatedStories'));
    }

    public function communities(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $communities = CommunityAccountRequest::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('community_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('communities.index', compact('communities', 'search'));
    }

    public function showCommunity(CommunityAccountRequest $community): View
    {
        // pastikan hanya akun yang disetujui yang dapat dilihat publik
        abort_unless($community->status === 'approved', 404);

        return view('communities.show', compact('community'));
    }

    public function articles(): View
    {
        $articles = CommunityStory::query()
            ->where('status', 'published')
            ->with('account')
            ->latest('reviewed_at')
            ->latest()
            ->paginate(9);

        return view('articles.index', compact('articles'));
    }
}
