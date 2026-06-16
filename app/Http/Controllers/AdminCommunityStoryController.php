<?php

namespace App\Http\Controllers;

use App\Models\CommunityStory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCommunityStoryController extends Controller
{
    public function index(): View
    {
        $stories = CommunityStory::query()
            ->with('account')
            ->latest()
            ->paginate(10);

        $summary = [
            'submitted' => CommunityStory::query()->where('status', 'submitted')->count(),
            'published' => CommunityStory::query()->where('status', 'published')->count(),
            'rejected' => CommunityStory::query()->where('status', 'rejected')->count(),
        ];

        return view('admin.stories.index', compact('stories', 'summary'));
    }

    public function show(CommunityStory $story): View
    {
        $story->load('account');

        return view('admin.stories.show', compact('story'));
    }

    public function approve(Request $request, int $story): RedirectResponse
    {
        $validated = $request->validate([
            'review_comment' => ['nullable', 'string', 'max:2000'],
        ]);

        CommunityStory::query()->whereKey($story)->update([
            'status' => 'published',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_comment' => $validated['review_comment'] ?? null,
        ]);

        return back()->with('status', 'Cerita berhasil disetujui dan tampil di halaman utama.');
    }

    public function reject(Request $request, int $story): RedirectResponse
    {
        $validated = $request->validate([
            'review_comment' => ['required', 'string', 'max:2000'],
        ], [
            'review_comment.required' => 'Komentar perbaikan wajib diisi saat menolak cerita.',
        ]);

        CommunityStory::query()->whereKey($story)->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_comment' => $validated['review_comment'],
        ]);

        return back()->with('status', 'Cerita sudah ditolak.');
    }

    public function comment(Request $request, int $story): RedirectResponse
    {
        $validated = $request->validate([
            'review_comment' => ['required', 'string', 'max:2000'],
        ], [
            'review_comment.required' => 'Komentar review wajib diisi.',
        ]);

        CommunityStory::query()->whereKey($story)->update([
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_comment' => $validated['review_comment'],
        ]);

        return back()->with('status', 'Komentar review berhasil disimpan.');
    }
}
