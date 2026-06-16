<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityProfileController extends Controller
{
    public function show(Request $request): RedirectResponse|View
    {
        $account = $this->accountFromSession($request);

        if (! $account) {
            return redirect()
                ->route('community-login.create')
                ->withErrors(['email' => 'Silakan masuk terlebih dahulu untuk melihat profil akun.']);
        }

        $storyStats = [
            'submitted' => $account->stories()->where('status', 'submitted')->count(),
            'published' => $account->stories()->where('status', 'published')->count(),
            'rejected' => $account->stories()->where('status', 'rejected')->count(),
        ];

        $stories = $account->stories()
            ->latest()
            ->limit(5)
            ->get();

        return view('account.profile', compact('account', 'storyStats', 'stories'));
    }

    public function update(Request $request): RedirectResponse
    {
        $account = $this->accountFromSession($request);

        if (! $account) {
            return redirect()->route('community-login.create');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'community_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:120'],
            'vision' => ['required', 'string', 'max:3000'],
            'mission' => ['required', 'string', 'max:3000'],
            'background' => ['required', 'string', 'max:3000'],
            'phone' => ['required', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama pengelola wajib diisi.',
            'community_name.required' => 'Nama komunitas wajib diisi.',
            'position.required' => 'Jabatan wajib diisi.',
            'vision.required' => 'Visi komunitas wajib diisi.',
            'vision.max' => 'Visi komunitas maksimal 3000 karakter.',
            'mission.required' => 'Misi komunitas wajib diisi.',
            'mission.max' => 'Misi komunitas maksimal 3000 karakter.',
            'background.required' => 'Latar belakang komunitas wajib diisi.',
            'background.max' => 'Latar belakang komunitas maksimal 3000 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('community-logos', 'public');
        }

        $account->update($validated);

        $freshAccount = $account->fresh();

        $request->session()->put('account_name', $freshAccount->name);
        $request->session()->put('account_logo', $freshAccount->logo_path);

        return back()->with('status', 'Informasi akun komunitas berhasil diperbarui.');
    }

    private function accountFromSession(Request $request): ?CommunityAccountRequest
    {
        if (! $request->session()->get('account_created')) {
            return null;
        }

        return CommunityAccountRequest::query()
            ->where('email', $request->session()->get('account_email'))
            ->latest()
            ->first();
    }
}
