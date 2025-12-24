<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\OrganizationStructure;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $batch = Batch::orderBy('year', 'desc')->first();
        
        if (!$batch) {
            return;
        }

        // Structure matching the hierarchy chart (positions only, no names)
        // Level 0: KETUA UMUM
        // Level 1: SEKRETARIS UMUM, BENDAHARA UMUM
        // Level 2: SEKRETARIS 1, BENDAHARA 1
        // Level 3: PSDM, HUMAS, DANUS, INFOKOM
        
        $members = [
            // Level 0 - Top
            ['position' => 'KETUA UMUM', 'level' => 0, 'order' => 1, 'description' => 'Pemimpin tertinggi organisasi MAJAP'],
            
            // Level 1 - Sekretaris & Bendahara Umum
            ['position' => 'SEKRETARIS UMUM', 'level' => 1, 'order' => 1, 'description' => 'Mengelola administrasi dan dokumentasi organisasi'],
            ['position' => 'BENDAHARA UMUM', 'level' => 1, 'order' => 2, 'description' => 'Mengelola keuangan organisasi'],
            
            // Level 2 - Sub Sekretaris & Bendahara
            ['position' => 'SEKRETARIS 1', 'level' => 2, 'order' => 1, 'description' => 'Asisten sekretaris umum'],
            ['position' => 'BENDAHARA 1', 'level' => 2, 'order' => 2, 'description' => 'Asisten bendahara umum'],
            
            // Level 3 - Divisions
            ['position' => 'PSDM', 'level' => 3, 'order' => 1, 'description' => 'Pengembangan Sumber Daya Manusia'],
            ['position' => 'HUMAS', 'level' => 3, 'order' => 2, 'description' => 'Hubungan Masyarakat'],
            ['position' => 'DANUS', 'level' => 3, 'order' => 3, 'description' => 'Dana dan Usaha'],
            ['position' => 'INFOKOM', 'level' => 3, 'order' => 4, 'description' => 'Informasi dan Komunikasi'],
        ];

        foreach ($members as $member) {
            OrganizationStructure::create([
                'batch_id' => $batch->id,
                'name' => '',
                'photo' => 'placeholder.png',
                ...$member,
            ]);
        }
    }
}
