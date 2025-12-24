<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'MAJAP POLINDRA', 'type' => 'text', 'group' => 'general'],
            
            // Hero Section
            ['key' => 'hero_text', 'value' => 'Mahasiswa Jabodetabek Politeknik Indramayu', 'type' => 'text', 'group' => 'hero'],
            ['key' => 'hero_slides', 'value' => json_encode([
                ['image' => 'placeholder.png', 'title' => 'Selamat Datang di MAJAP', 'subtitle' => 'Bersama Membangun Masa Depan'],
                ['image' => 'placeholder.png', 'title' => 'Organisasi Mahasiswa', 'subtitle' => 'Wadah Berkembang & Berkarya'],
            ]), 'type' => 'json', 'group' => 'hero'],
            
            // Stats (for homepage)
            ['key' => 'active_members', 'value' => '150+', 'type' => 'text', 'group' => 'stats'],
            ['key' => 'established_year', 'value' => '2015', 'type' => 'text', 'group' => 'stats'],
            
            // About
            ['key' => 'vision', 'value' => 'Menjadi organisasi mahasiswa yang unggul, inovatif, dan berdaya saing tinggi dalam mengembangkan potensi mahasiswa regional Jabodetabek di Politeknik Negeri Indramayu.', 'type' => 'textarea', 'group' => 'about'],
            ['key' => 'mission', 'value' => json_encode([
                'Membangun solidaritas dan kekeluargaan antar mahasiswa Jabodetabek',
                'Mengembangkan potensi akademik dan non-akademik anggota',
                'Menjalin kerjasama dengan berbagai pihak untuk kemajuan bersama',
                'Berkontribusi aktif dalam kegiatan kampus dan masyarakat',
            ]), 'type' => 'json', 'group' => 'about'],
            ['key' => 'history', 'value' => 'MAJAP (Mahasiswa Jabodetabek Politeknik Indramayu) didirikan sebagai wadah bagi mahasiswa yang berasal dari wilayah Jakarta, Bogor, Depok, Tangerang, dan Bekasi untuk saling mengenal, bertukar informasi, dan mengembangkan diri bersama selama menempuh pendidikan di Politeknik Negeri Indramayu.', 'type' => 'textarea', 'group' => 'about'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'majap@polindra.ac.id', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+62 812 3456 7890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address', 'value' => 'Politeknik Negeri Indramayu, Jl. Lohbener Lama No.08, Legok, Kec. Lohbener, Kabupaten Indramayu, Jawa Barat 45252', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'map_embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.0!2d107.9!3d-6.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTgnMDAuMCJTIDEwN8KwNTQnMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890', 'type' => 'text', 'group' => 'contact'],
            
            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/majap_polindra', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => '', 'type' => 'text', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
