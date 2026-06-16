<?php

namespace Database\Seeders;

use App\Models\CommunityAccountRequest;
use App\Models\CommunityStory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        $communities = collect([
            [
                'name' => 'Koordinator TBM Hamfara',
                'community_name' => 'TBM Hamfara',
                'email' => 'tbmhamfara@silera.test',
                'position' => 'Koordinator',
            ],
            [
                'name' => 'Koordinator Rumpus Bintang',
                'community_name' => 'Rumpus Bintang',
                'email' => 'rumpusbintang@silera.test',
                'position' => 'Ketua',
            ],
            [
                'name' => 'Koordinator Forum Lingkar Pena Riau',
                'community_name' => 'Forum Lingkar Pena Riau',
                'email' => 'forumlingkarpenariau@silera.test',
                'position' => 'Koordinator',
            ],
            [
                'name' => 'Koordinator TBM Kandas Library',
                'community_name' => 'TBM Kandas Library',
                'email' => 'tbmkandaslibrary@silera.test',
                'position' => 'Ketua',
            ],
        ])->mapWithKeys(function (array $community) {
            $account = CommunityAccountRequest::query()->updateOrCreate([
                'email' => $community['email'],
            ], [
                'name' => $community['name'],
                'community_name' => $community['community_name'],
                'logo_path' => null,
                'position' => $community['position'],
                'vision' => 'Menjadi komunitas literasi yang aktif, terbuka, dan berdampak bagi masyarakat Riau.',
                'mission' => 'Mengadakan kegiatan membaca, memperluas akses bahan bacaan, dan membangun kolaborasi literasi.',
                'vision_mission' => 'Menjadi komunitas literasi yang aktif, terbuka, dan berdampak bagi masyarakat Riau.',
                'background' => 'Komunitas ini hadir untuk menguatkan budaya baca dan ruang belajar masyarakat.',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'status' => 'approved',
                'terms_accepted_at' => now(),
            ]);

            return [$community['community_name'] => $account];
        });

        $stories = [
            [
                'community' => 'TBM Hamfara',
                'title' => 'Lapak Baca Pinggir Sungai Siak: Menghidupkan Budaya Baca',
                'story' => 'Gerakan literasi masyarakat di tepian Sungai Siak semakin bergairah dengan hadirnya lapak baca komunitas. Anak-anak dan remaja berkumpul untuk membaca, berdiskusi, dan mengenal cerita lokal Riau.',
            ],
            [
                'community' => 'Rumpus Bintang',
                'title' => 'Gerakan Satu Dusun Satu Pojok Baca Capai Target',
                'story' => 'Upaya pemerataan akses bacaan terus dilakukan melalui pojok baca di tingkat dusun. Kegiatan ini mempertemukan relawan, warga, dan pelajar dalam ruang belajar yang sederhana namun hidup.',
            ],
            [
                'community' => 'Forum Lingkar Pena Riau',
                'title' => 'Menyongsong Festival Literasi Riau 2024',
                'story' => 'Persiapan festival literasi dilakukan melalui kurasi kegiatan, pelibatan komunitas, dan penyusunan agenda yang mendorong kreativitas membaca serta menulis.',
            ],
        ];

        foreach ($stories as $story) {
            $account = $communities[$story['community']];

            CommunityStory::query()->updateOrCreate([
                'title' => $story['title'],
                'author_email' => $account->email,
            ], [
                'community_account_request_id' => $account->id,
                'author_name' => $account->name,
                'story' => $story['story'],
                'photo_path' => null,
                'status' => 'published',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'review_comment' => null,
            ]);
        }
    }
}
