<?php

namespace App\Http\Controllers;

use App\Models\CommunityAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCommunityAccountController extends Controller
{
    public function index(): View
    {
        $communities = CommunityAccountRequest::query()
            ->latest()
            ->paginate(10);

        $summary = [
            'pending' => CommunityAccountRequest::query()->where('status', 'pending')->count(),
            'approved' => CommunityAccountRequest::query()->where('status', 'approved')->count(),
            'rejected' => CommunityAccountRequest::query()->where('status', 'rejected')->count(),
        ];

        return view('admin.communities.index', compact('communities', 'summary'));
    }

    public function approve(CommunityAccountRequest $community): RedirectResponse
    {
        $community->update([
            'status' => 'approved',
        ]);

        return back()->with('status', 'Akun komunitas berhasil disetujui.');
    }

    public function reject(CommunityAccountRequest $community): RedirectResponse
    {
        $community->update([
            'status' => 'rejected',
        ]);

        return back()->with('status', 'Akun komunitas berhasil ditolak.');
    }
}
