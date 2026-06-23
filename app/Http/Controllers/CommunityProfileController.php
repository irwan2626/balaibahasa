<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
            'logo_data' => ['nullable', 'string'],
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

        // Prioritaskan hasil crop (logo_data) jika ada
        if ($request->filled('logo_data')) {
            $data = $request->input('logo_data');
            // data:image/png;base64,...
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
                $type = strtolower($matches[1]);
                $data = substr($data, strpos($data, ',') + 1);
            } else {
                // default to png
                $type = 'png';
            }

            $decoded = base64_decode($data);
            if ($decoded !== false) {
                $ext = in_array($type, ['jpg','jpeg','png','webp']) ? $type : 'png';
                $fileName = 'community-logos/' . uniqid('logo_') . '.' . $ext;
                Storage::disk('public')->put($fileName, $decoded);

                // hapus logo lama jika ada
                if ($account->logo_path) {
                    Storage::disk('public')->delete($account->logo_path);
                }

                $validated['logo_path'] = $fileName;
            }
        } elseif ($request->hasFile('logo')) {
            // fallback jika ada file upload biasa
            $filePath = $request->file('logo')->store('community-logos', 'public');
            if ($filePath) {
                if ($account->logo_path) {
                    Storage::disk('public')->delete($account->logo_path);
                }
                $validated['logo_path'] = $filePath;
            }
        }

        $account->update($validated);

        $freshAccount = $account->fresh();

        $request->session()->put('account_name', $freshAccount->name);
        $request->session()->put('account_logo', $freshAccount->logo_path);

        return back()->with('status', 'Informasi akun komunitas berhasil diperbarui.');
    }

    public function home(Request $request)
    {
        $account = $this->accountFromSession($request);

        if (! $account) {
            return redirect()->route('community-login.create');
        }

        $stories = $account->stories()->where('status', 'published')->latest()->paginate(6);
        $drafts = $account->stories()->where('status', 'submitted')->latest()->get();

        return view('account.home', compact('account', 'stories', 'drafts'));
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
