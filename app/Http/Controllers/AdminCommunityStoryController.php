<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminCommunityStoryController extends Controller
{
    public function index(): View
    {
        $stories = DB::table('community_stories')
            ->leftJoin('community_account_requests', 'community_stories.community_account_request_id', '=', 'community_account_requests.id')
            ->select(
                'community_stories.*',
                'community_account_requests.community_name',
                'community_account_requests.phone'
            )
            ->latest('community_stories.created_at')
            ->paginate(10);

        $summary = [
            'submitted' => DB::table('community_stories')->where('status', 'submitted')->count(),
            'published' => DB::table('community_stories')->where('status', 'published')->count(),
            'rejected' => DB::table('community_stories')->where('status', 'rejected')->count(),
        ];

        return view('admin.stories.index', compact('stories', 'summary'));
    }

    public function approve(int $story): RedirectResponse
    {
        DB::table('community_stories')
            ->where('id', $story)
            ->update([
                'status' => 'published',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('status', 'Cerita berhasil disetujui dan tampil di halaman utama.');
    }

    public function reject(int $story): RedirectResponse
    {
        DB::table('community_stories')
            ->where('id', $story)
            ->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('status', 'Cerita sudah ditolak.');
    }
}
