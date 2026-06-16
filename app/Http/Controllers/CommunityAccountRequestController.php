<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityAccountRequestController extends Controller
{
    public function create(): View
    {
        return view('auth.community-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'community_name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'position' => ['required', 'string', 'max:120'],
            'vision' => ['required', 'string', 'max:3000'],
            'mission' => ['required', 'string', 'max:3000'],
            'background' => ['required', 'string', 'max:3000'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:community_account_requests,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'community_name.required' => 'Nama komunitas literasi wajib diisi.',
            'logo.required' => 'Foto logo komunitas wajib diunggah.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
            'position.required' => 'Jabatan wajib diisi.',
            'vision.required' => 'Visi komunitas wajib diisi.',
            'vision.max' => 'Visi komunitas maksimal 3000 karakter.',
            'mission.required' => 'Misi komunitas wajib diisi.',
            'mission.max' => 'Misi komunitas maksimal 3000 karakter.',
            'background.required' => 'Latar belakang komunitas wajib diisi.',
            'background.max' => 'Latar belakang komunitas maksimal 3000 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Pos-el wajib diisi.',
            'email.email' => 'Format pos-el belum benar.',
            'email.unique' => 'Pos-el ini sudah pernah didaftarkan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Persetujuan syarat dan kebijakan privasi wajib dicentang.',
        ]);

        $logoPath = $request->file('logo')->store('community-logos', 'public');

        $account = CommunityAccountRequest::query()->create([
            'name' => $validated['name'],
            'community_name' => $validated['community_name'],
            'logo_path' => $logoPath,
            'position' => $validated['position'],
            'vision' => $validated['vision'],
            'mission' => $validated['mission'],
            'background' => $validated['background'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => 'pending',
            'terms_accepted_at' => now(),
        ]);

        $request->session()->put('account_created', true);
        $request->session()->put('account_id', $account->id);
        $request->session()->put('account_name', $validated['name']);
        $request->session()->put('account_email', $validated['email']);
        $request->session()->put('account_logo', $account->logo_path);

        return back()->with('status', 'Permohonan akun komunitas berhasil dikirim. Tim SILERA akan meninjau data Anda.');
    }
}
