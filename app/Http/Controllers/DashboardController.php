<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'communities' => DB::table('literacy_communities')->count(),
            'posts' => DB::table('literacy_posts')->count(),
            'active_programs' => DB::table('literacy_programs')->whereIn('status', ['planned', 'active'])->count(),
            'members' => DB::table('community_members')->count(),
        ];

        $activities = DB::table('activity_reports')
            ->join('literacy_communities', 'activity_reports.literacy_community_id', '=', 'literacy_communities.id')
            ->select('activity_reports.title', 'activity_reports.activity_date', 'activity_reports.status', 'literacy_communities.name as community_name')
            ->latest('activity_reports.created_at')
            ->limit(3)
            ->get();

        return view('dashboard', compact('stats', 'activities'));
    }
}
