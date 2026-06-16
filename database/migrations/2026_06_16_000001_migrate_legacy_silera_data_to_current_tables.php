<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('literacy_communities') || ! Schema::hasTable('community_account_requests')) {
            return;
        }

        $adminId = DB::table('users')->value('id');
        $communityIdMap = [];

        DB::transaction(function () use (&$communityIdMap, $adminId) {
            $legacyCommunities = DB::table('literacy_communities')->get();

            foreach ($legacyCommunities as $community) {
                $email = $community->email ?: Str::slug($community->name, '').'@silera.test';
                $status = match ($community->status) {
                    'verified' => 'approved',
                    'inactive' => 'rejected',
                    default => 'pending',
                };

                DB::table('community_account_requests')->updateOrInsert([
                    'email' => $email,
                ], [
                    'name' => $community->contact_person ?: 'Pengelola '.$community->name,
                    'community_name' => $community->name,
                    'logo_path' => $community->logo_path,
                    'position' => 'Koordinator',
                    'vision' => 'Menjadi komunitas literasi yang aktif dan berdampak di wilayah '.$community->city.'.',
                    'mission' => 'Mengembangkan budaya baca, memperluas akses bacaan, dan membangun kolaborasi literasi.',
                    'vision_mission' => $community->description,
                    'background' => $community->description ?: 'Komunitas literasi yang bergerak di wilayah '.$community->city.', Riau.',
                    'phone' => $community->phone ?: '081234567890',
                    'password' => Hash::make('password'),
                    'status' => $status,
                    'terms_accepted_at' => now(),
                    'created_at' => $community->created_at ?? now(),
                    'updated_at' => now(),
                ]);

                $communityIdMap[$community->id] = DB::table('community_account_requests')
                    ->where('email', $email)
                    ->value('id');
            }

            if (Schema::hasTable('literacy_posts')) {
                $legacyPosts = DB::table('literacy_posts')
                    ->leftJoin('literacy_communities', 'literacy_posts.literacy_community_id', '=', 'literacy_communities.id')
                    ->select('literacy_posts.*', 'literacy_communities.email as community_email', 'literacy_communities.contact_person as contact_person')
                    ->get();

                foreach ($legacyPosts as $post) {
                    $accountId = $post->literacy_community_id ? ($communityIdMap[$post->literacy_community_id] ?? null) : null;
                    $authorEmail = $post->community_email ?: 'admin@silera.test';
                    $status = $post->status === 'published' ? 'published' : 'submitted';

                    DB::table('community_stories')->updateOrInsert([
                        'title' => $post->title,
                        'author_email' => $authorEmail,
                    ], [
                        'community_account_request_id' => $accountId,
                        'author_name' => $post->contact_person ?: 'Admin SILERA',
                        'story' => $post->content,
                        'photo_path' => $post->thumbnail_path,
                        'status' => $status,
                        'reviewed_by' => $status === 'published' ? $adminId : null,
                        'reviewed_at' => $status === 'published' ? ($post->published_at ?? now()) : null,
                        'review_comment' => null,
                        'created_at' => $post->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (Schema::hasTable('activity_reports')) {
                $legacyReports = DB::table('activity_reports')
                    ->leftJoin('literacy_communities', 'activity_reports.literacy_community_id', '=', 'literacy_communities.id')
                    ->select('activity_reports.*', 'literacy_communities.email as community_email', 'literacy_communities.contact_person as contact_person')
                    ->get();

                foreach ($legacyReports as $report) {
                    $accountId = $communityIdMap[$report->literacy_community_id] ?? null;
                    $authorEmail = $report->community_email ?: 'admin@silera.test';
                    $story = trim(($report->summary ?? '')."\n\n".($report->outcome ?? ''));

                    DB::table('community_stories')->updateOrInsert([
                        'title' => $report->title,
                        'author_email' => $authorEmail,
                    ], [
                        'community_account_request_id' => $accountId,
                        'author_name' => $report->contact_person ?: 'Admin SILERA',
                        'story' => $story !== '' ? $story : 'Laporan kegiatan komunitas literasi.',
                        'photo_path' => $report->attachment_path,
                        'status' => $report->status === 'approved' ? 'published' : 'submitted',
                        'reviewed_by' => $report->status === 'approved' ? $adminId : null,
                        'reviewed_at' => $report->status === 'approved' ? now() : null,
                        'review_comment' => null,
                        'created_at' => $report->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            foreach ([
                'collaboration_requests',
                'activity_reports',
                'literacy_programs',
                'literacy_posts',
                'community_members',
                'community_category',
                'literacy_communities',
                'literacy_categories',
            ] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->delete();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data lama tidak direkonstruksi otomatis karena sudah disalin ke tabel aktif.
    }
};
