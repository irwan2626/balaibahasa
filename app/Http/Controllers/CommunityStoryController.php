<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'title' => ['required', 'string', 'max:255'],
            'story' => ['required', 'string', 'min:30'],
        ], [
            'photo.required' => 'Foto cerita wajib diunggah.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'title.required' => 'Judul cerita wajib diisi.',
            'story.required' => 'Isi cerita wajib diisi.',
            'story.min' => 'Cerita minimal 30 karakter.',
        ]);

        $account = DB::table('community_account_requests')
            ->where('email', $request->session()->get('account_email'))
            ->latest()
            ->first();

        $photoPath = $request->file('photo')->store('community-stories', 'public');

        DB::table('community_stories')->insert([
            'community_account_request_id' => $account?->id,
            'author_name' => $request->session()->get('account_name', 'Pengelola Komunitas'),
            'author_email' => $request->session()->get('account_email', $account?->email ?? ''),
            'title' => $validated['title'],
            'story' => $validated['story'],
            'photo_path' => $photoPath,
            'status' => 'submitted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Cerita berhasil dikirim dan sedang menunggu kurasi tim SILERA.');
    }
}
