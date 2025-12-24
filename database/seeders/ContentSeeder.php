<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Album;
use App\Models\Batch;
use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Create Batches
        $batches = [
            ['name' => 'Angkatan 1', 'year' => 2020],
            ['name' => 'Angkatan 2', 'year' => 2021],
            ['name' => 'Angkatan 3', 'year' => 2022],
            ['name' => 'Angkatan 4', 'year' => 2023],
        ];

        foreach ($batches as $batchData) {
            Batch::firstOrCreate(
                ['year' => $batchData['year']],
                ['name' => $batchData['name'], 'is_active' => true]
            );
        }

        $latestBatch = Batch::orderBy('year', 'desc')->first();

        // Create Activities
        $activities = [
            [
                'title' => 'Makrab MAJAP 2023',
                'description' => 'Malam keakraban untuk menyambut anggota baru MAJAP Polindra.',
                'date' => Carbon::now()->subMonths(2),
            ],
            [
                'title' => 'Bakti Sosial Indramayu',
                'description' => 'Kegiatan berbagi dengan masyarakat sekitar kampus.',
                'date' => Carbon::now()->subMonths(5),
            ],
            [
                'title' => 'Workshop Web Development',
                'description' => 'Pelatihan dasar pembuatan website untuk anggota.',
                'date' => Carbon::now()->subMonths(1),
            ],
        ];

        foreach ($activities as $activityData) {
            $activity = Activity::create([
                'batch_id' => $latestBatch->id,
                'title' => $activityData['title'],
                'slug' => Str::slug($activityData['title']),
                'short_description' => $activityData['description'],
                'content' => $activityData['description'] . ' Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'cover_image' => 'placeholder.png',
                'event_date' => $activityData['date'],
                'location' => 'Polindra',
                'is_featured' => true,
                'is_published' => true,
            ]);

            // Create Album for Activity
            $album = Album::create([
                'batch_id' => $latestBatch->id,
                'activity_id' => $activity->id,
                'title' => 'Dokumentasi ' . $activityData['title'],
                'slug' => Str::slug('Dokumentasi ' . $activityData['title']),
                'description' => 'Foto-foto kegiatan ' . $activityData['title'],
                'cover_image' => 'placeholder.png',
                'is_published' => true,
            ]);

            // Add Photos to Album
            for ($i = 1; $i <= 5; $i++) {
                Photo::create([
                    'album_id' => $album->id,
                    'image_path' => 'placeholder.png',
                    'caption' => 'Foto Kegiatan ' . $i,
                    'order' => $i,
                ]);
            }
        }
    }
}
