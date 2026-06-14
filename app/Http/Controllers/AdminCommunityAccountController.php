<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminCommunityAccountController extends Controller
{
    public function index(): View
    {
        $communities = DB::table('community_account_requests')
            ->latest()
            ->paginate(10);

        return view('admin.communities.index', compact('communities'));
    }
}
