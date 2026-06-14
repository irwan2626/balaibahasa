<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'position' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:community_account_requests,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'community_name.required' => 'Nama komunitas literasi wajib diisi.',
            'position.required' => 'Jabatan wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Pos-el wajib diisi.',
            'email.email' => 'Format pos-el belum benar.',
            'email.unique' => 'Pos-el ini sudah pernah didaftarkan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Persetujuan syarat dan kebijakan privasi wajib dicentang.',
        ]);

        DB::table('community_account_requests')->insert([
            'name' => $validated['name'],
            'community_name' => $validated['community_name'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
            'terms_accepted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request->session()->put('account_created', true);
        $request->session()->put('account_name', $validated['name']);
        $request->session()->put('account_email', $validated['email']);

        return back()->with('status', 'Permohonan akun komunitas berhasil dikirim. Tim SILERA akan meninjau data Anda.');
    }
}
