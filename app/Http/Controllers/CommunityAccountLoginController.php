<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CommunityAccountLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.community-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $account = CommunityAccountRequest::query()
            ->where('email', $validated['email'])
            ->first();

        if (! $account || ! $account->password || ! Hash::check($validated['password'], $account->password)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('account_created', true);
        $request->session()->put('account_id', $account->id);
        $request->session()->put('account_name', $account->name);
        $request->session()->put('account_email', $account->email);
        $request->session()->put('account_logo', $account->logo_path);

        return redirect()->intended(route('community-stories.create'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['account_created', 'account_id', 'account_name', 'account_email', 'account_logo']);

        return redirect()->route('home');
    }
}
