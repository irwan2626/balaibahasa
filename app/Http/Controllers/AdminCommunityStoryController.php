<?php

namespace App\Http\Controllers;

use App\Models\CommunityStory;
use App\Models\CommunityStoryPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCommunityStoryController extends Controller
{
    public function index(): View
    {
        $stories = CommunityStory::query()
            ->with(['account', 'photos'])
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
        $story->load(['account', 'photos']);

        return view('admin.stories.show', compact('story'));
    }

    public function edit(CommunityStory $story): View
    {
        $story->load(['account', 'photos']);

        return view('admin.stories.edit', compact('story'));
    }

    public function update(Request $request, CommunityStory $story): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'story' => ['required', 'string', 'min:30'],
        ], [
            'title.required' => 'Judul cerita wajib diisi.',
            'story.required' => 'Isi cerita wajib diisi.',
            'story.min' => 'Cerita minimal 30 karakter.',
        ]);

        $story->update($validated);

        return redirect()
            ->route('admin.stories.show', $story)
            ->with('status', 'Teks cerita berhasil diperbarui.');
    }

    public function storePhotos(Request $request, CommunityStory $story): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'photos.required' => 'Pilih minimal satu foto untuk ditambahkan.',
            'photos.*.image' => 'Setiap file harus berupa gambar.',
            'photos.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 2 MB.',
        ]);

        $nextOrder = (int) $story->photos()->max('sort_order') + 1;

        foreach ($request->file('photos', []) as $index => $photo) {
            $story->photos()->create([
                'photo_path' => $photo->store('community-stories', 'public'),
                'sort_order' => $nextOrder + $index,
            ]);
        }

        if (! $story->photo_path) {
            $story->update([
                'photo_path' => $story->photos()->oldest()->value('photo_path'),
            ]);
        }

        return back()->with('status', 'Foto cerita berhasil ditambahkan.');
    }

    public function destroyPhoto(CommunityStory $story, CommunityStoryPhoto $photo): RedirectResponse
    {
        abort_unless($photo->community_story_id === $story->id, 404);

        $path = $photo->photo_path;
        $photo->delete();
        Storage::disk('public')->delete($path);

        if ($story->photo_path === $path) {
            $story->update([
                'photo_path' => $story->photos()->oldest()->value('photo_path'),
            ]);
        }

        return back()->with('status', 'Foto cerita berhasil dihapus.');
    }

    public function destroy(CommunityStory $story): RedirectResponse
    {
        $story->load('photos');
        $photoPaths = $story->photos->pluck('photo_path');

        $story->delete();

        Storage::disk('public')->delete($photoPaths->all());

        if ($story->photo_path && ! $photoPaths->contains($story->photo_path)) {
            Storage::disk('public')->delete($story->photo_path);
        }

        return redirect()
            ->route('admin.stories.index')
            ->with('status', 'Cerita berhasil dihapus dari daftar cerita masuk.');
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
