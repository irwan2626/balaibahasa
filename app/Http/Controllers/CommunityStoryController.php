<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use App\Models\CommunityStory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityStoryController extends Controller
{
    public function create(Request $request): RedirectResponse|View
    {
        if (! $request->session()->get('account_created')) {
            return redirect()
                ->route('community-account.create')
                ->with('status', 'Silakan buat akun komunitas terlebih dahulu sebelum menambahkan cerita.');
        }

        return view('stories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->session()->get('account_created')) {
            return redirect()
                ->route('community-account.create')
                ->with('status', 'Silakan buat akun komunitas terlebih dahulu sebelum menambahkan cerita.');
        }

        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'title' => ['required', 'string', 'max:255'],
            'story' => ['required', 'string', 'min:30'],
        ], [
            'photos.required' => 'Foto cerita wajib diunggah.',
            'photos.array' => 'Foto cerita harus berupa daftar file gambar.',
            'photos.min' => 'Minimal unggah satu foto kegiatan.',
            'photos.*.required' => 'Setiap foto wajib dipilih.',
            'photos.*.image' => 'Setiap file harus berupa gambar.',
            'photos.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 2 MB.',
            'title.required' => 'Judul cerita wajib diisi.',
            'story.required' => 'Isi cerita wajib diisi.',
            'story.min' => 'Cerita minimal 30 karakter.',
        ]);

        $account = CommunityAccountRequest::query()
            ->where('email', $request->session()->get('account_email'))
            ->latest()
            ->first();

        $photoPaths = collect($request->file('photos', []))
            ->map(fn ($photo) => $photo->store('community-stories', 'public'))
            ->values();

        $story = CommunityStory::query()->create([
            'community_account_request_id' => $account?->id,
            'author_name' => $request->session()->get('account_name', 'Pengelola Komunitas'),
            'author_email' => $request->session()->get('account_email', $account?->email ?? ''),
            'title' => $validated['title'],
            'story' => $validated['story'],
            'photo_path' => $photoPaths->first(),
            'status' => 'submitted',
        ]);

        $photoPaths->each(function (string $path, int $index) use ($story) {
            $story->photos()->create([
                'photo_path' => $path,
                'sort_order' => $index,
            ]);
        });

        return back()->with('status', 'Cerita berhasil dikirim dan sedang menunggu kurasi tim SILERA.');
    }
}
