<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate([
            'email' => 'admin@silera.test',
        ], [
            'name' => 'Admin SILERA',
            'password' => Hash::make('password'),
        ]);

        $categories = collect([
            ['name' => 'Taman Bacaan', 'color' => '#00236f'],
            ['name' => 'Forum Literasi', 'color' => '#006591'],
            ['name' => 'Perpustakaan Komunitas', 'color' => '#735c00'],
            ['name' => 'Workshop', 'color' => '#39b8fd'],
        ])->mapWithKeys(function (array $category) {
            $id = DB::table('literacy_categories')->updateOrInsert([
                'slug' => Str::slug($category['name']),
            ], [
                'name' => $category['name'],
                'color' => $category['color'],
                'description' => 'Kategori '.$category['name'].' dalam ekosistem literasi Riau.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [$category['name'] => DB::table('literacy_categories')->where('slug', Str::slug($category['name']))->value('id')];
        });

        $communities = collect([
            [
                'name' => 'TBM Hamfara',
                'type' => 'Taman Bacaan',
                'city' => 'Pekanbaru',
                'district' => 'Tampan',
                'category' => 'Taman Bacaan',
            ],
            [
                'name' => 'Rumpus Bintang',
                'type' => 'Komunitas',
                'city' => 'Dumai',
                'district' => 'Dumai Timur',
                'category' => 'Forum Literasi',
            ],
            [
                'name' => 'Forum Lingkar Pena Riau',
                'type' => 'Forum',
                'city' => 'Kampar',
                'district' => 'Bangkinang',
                'category' => 'Forum Literasi',
            ],
            [
                'name' => 'TBM Kandas Library',
                'type' => 'Perpustakaan',
                'city' => 'Siak',
                'district' => 'Mempura',
                'category' => 'Perpustakaan Komunitas',
            ],
        ])->map(function (array $community) use ($admin, $categories) {
            DB::table('literacy_communities')->updateOrInsert([
                'slug' => Str::slug($community['name']),
            ], [
                'user_id' => $admin->id,
                'name' => $community['name'],
                'type' => $community['type'],
                'description' => 'Komunitas literasi aktif yang mendukung pendataan dan kolaborasi literasi di wilayah '.$community['city'].'.',
                'address' => 'Jalan Literasi No. 1',
                'district' => $community['district'],
                'city' => $community['city'],
                'province' => 'Riau',
                'contact_person' => 'Koordinator '.$community['name'],
                'phone' => '081234567890',
                'email' => Str::slug($community['name'], '').'@silera.test',
                'status' => 'verified',
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $communityId = DB::table('literacy_communities')->where('slug', Str::slug($community['name']))->value('id');

            DB::table('community_category')->updateOrInsert([
                'literacy_community_id' => $communityId,
                'literacy_category_id' => $categories[$community['category']],
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('community_members')->updateOrInsert([
                'literacy_community_id' => $communityId,
                'email' => 'koordinator+'.Str::slug($community['name'], '').'@silera.test',
            ], [
                'name' => 'Koordinator '.$community['name'],
                'phone' => '081234567890',
                'role' => 'coordinator',
                'status' => 'active',
                'joined_at' => now()->subMonths(6)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $communityId;
        });

        $posts = [
            'Lapak Baca Pinggir Sungai Siak: Menghidupkan Budaya Baca',
            'Gerakan Satu Dusun Satu Pojok Baca Capai Target',
            'Menyongsong Festival Literasi Riau 2024',
        ];

        foreach ($posts as $index => $title) {
            DB::table('literacy_posts')->updateOrInsert([
                'slug' => Str::slug($title),
            ], [
                'user_id' => $admin->id,
                'literacy_community_id' => $communities[$index] ?? $communities->first(),
                'title' => $title,
                'type' => 'news',
                'excerpt' => 'Info terkini gerakan literasi Riau dari komunitas dan jejaring Balai Bahasa.',
                'content' => 'Konten berita '.$title.' disiapkan sebagai data awal untuk sistem SILERA.',
                'status' => 'published',
                'published_at' => now()->subDays(7 - $index),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('literacy_programs')->updateOrInsert([
            'title' => 'Festival Literasi Riau',
        ], [
            'literacy_community_id' => $communities->first(),
            'description' => 'Program kolaborasi tahunan bagi komunitas literasi Riau.',
            'location' => 'Pekanbaru',
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonth()->addDays(2)->toDateString(),
            'target_participants' => 250,
            'actual_participants' => 0,
            'status' => 'planned',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('activity_reports')->updateOrInsert([
            'literacy_community_id' => $communities->first(),
            'title' => 'Kelas Baca Akhir Pekan',
        ], [
            'user_id' => $admin->id,
            'activity_date' => now()->subWeek()->toDateString(),
            'participants_count' => 42,
            'summary' => 'Kegiatan baca bersama untuk anak dan remaja.',
            'outcome' => 'Peserta menyelesaikan sesi membaca terpandu dan diskusi singkat.',
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('collaboration_requests')->updateOrInsert([
            'requester_community_id' => $communities->first(),
            'title' => 'Pertukaran Relawan Literasi',
        ], [
            'partner_community_id' => $communities->last(),
            'description' => 'Kolaborasi relawan untuk memperluas layanan baca komunitas.',
            'proposed_date' => now()->addWeeks(3)->toDateString(),
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
