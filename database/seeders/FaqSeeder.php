<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apa itu MAJAP?',
                'answer' => '<p>MAJAP adalah singkatan dari <strong>Mahasiswa Jabodetabek Politeknik Negeri Indramayu</strong>. Sebuah organisasi daerah yang mewadahi mahasiswa asal Jakarta, Bogor, Depok, Tangerang, dan Bekasi yang menempuh pendidikan di Politeknik Negeri Indramayu. MAJAP didirikan pada <strong>tahun 2018</strong> dan memiliki motto <em>"Kagak Ngaruh Tapi Berpengaruh"</em>.</p>',
                'order' => 1,
            ],
            [
                'question' => 'Kapan MAJAP berdiri?',
                'answer' => '<p>MAJAP resmi berdiri pada <strong>tahun 2018</strong>. Hingga saat ini MAJAP telah memiliki lebih dari <strong>110 anggota</strong> dari berbagai angkatan dan program studi di Politeknik Negeri Indramayu.</p>',
                'order' => 2,
            ],
            [
                'question' => 'Siapa saja yang bisa bergabung dengan MAJAP?',
                'answer' => '<p>Seluruh <strong>mahasiswa aktif Politeknik Negeri Indramayu</strong> yang berasal dari wilayah <strong>Jabodetabek</strong> (Jakarta, Bogor, Depok, Tangerang, dan Bekasi) berhak bergabung menjadi anggota MAJAP. Tidak ada batasan angkatan — baik mahasiswa baru maupun tingkat akhir, semuanya dipersilakan untuk bergabung.</p>',
                'order' => 3,
            ],
            [
                'question' => 'Apa saja kegiatan MAJAP?',
                'answer' => '<p>MAJAP memiliki berbagai kegiatan, di antaranya:</p><ul><li><strong>First Meet MAJAP</strong> — pertemuan perdana mahasiswa baru untuk saling mengenal.</li><li><strong>Prawisuda MAJAP</strong> — pelepasan anggota yang akan wisuda.</li><li><strong>FAMGETH MAJAP</strong> — gathering keluarga besar MAJAP.</li><li><strong>MGTS (MAJAP Goes To School)</strong> — sosialisasi ke sekolah-sekolah Jabodetabek.</li><li><strong>Dies Natalis MAJAP</strong> — peringatan hari lahir MAJAP.</li></ul><p>Dokumentasi kegiatan bisa dilihat di halaman <a href="/kegiatan">Kegiatan</a> dan <a href="/galeri">Galeri</a>.</p>',
                'order' => 4,
            ],
            [
                'question' => 'Apa visi dan misi MAJAP?',
                'answer' => '<p><strong>Visi:</strong> Terciptanya tali persaudaraan sesama Mahasiswa JABODETABEK POLINDRA yang berintegritas, berkualitas, dan berdedikasi tinggi bagi nusa dan bangsa.</p><p><strong>Misi:</strong></p><ul><li>Meningkatkan kebersamaan dan kekeluargaan antara sesama Mahasiswa JABODETABEK POLINDRA.</li><li>Mengembangkan potensi kreatif dan inovatif keilmuan anggota dan masyarakat daerah.</li><li>Berperan aktif dalam lingkungan masyarakat dengan mewujudkan nilai-nilai agama dan sosial.</li></ul>',
                'order' => 5,
            ],
            [
                'question' => 'Bagaimana struktur organisasi MAJAP?',
                'answer' => '<p>Struktur organisasi MAJAP terdiri dari:</p><ul><li><strong>Ketua Umum</strong> — pemimpin tertinggi organisasi.</li><li><strong>Sekretaris Umum</strong> — administrasi dan dokumentasi.</li><li><strong>Bendahara Umum</strong> — keuangan organisasi.</li><li><strong>Sekretaris 1</strong> — asisten sekretaris umum.</li><li><strong>Bendahara 1</strong> — asisten bendahara umum.</li><li><strong>PSDM</strong> — Pengembangan Sumber Daya Manusia.</li><li><strong>HUMAS</strong> — Hubungan Masyarakat.</li><li><strong>DANUS</strong> — Dana dan Usaha.</li><li><strong>INFOKOM</strong> — Informasi dan Komunikasi.</li></ul>',
                'order' => 6,
            ],
            [
                'question' => 'Bagaimana cara menghubungi MAJAP?',
                'answer' => '<p>Kamu bisa menghubungi MAJAP melalui:</p><ul><li><strong>Email:</strong> <a href="mailto:majapolindra@gmail.com">majapolindra@gmail.com</a></li><li><strong>Instagram:</strong> <a href="https://instagram.com/majap_polindra" target="_blank">@majap_polindra</a></li><li><strong>Alamat:</strong> Jl. Raya Lohbener Lama No.08, Lohbener, Indramayu, Jawa Barat 45252</li><li><strong>Form Kontak:</strong> Kunjungi halaman <a href="/kontak">Hubungi Kami</a></li><li><strong>Saran & Masukan:</strong> <a href="/saran">Sampaikan saran anonim</a> melalui website</li></ul>',
                'order' => 7,
            ],
            [
                'question' => 'Apa motto MAJAP?',
                'answer' => '<p>Motto MAJAP adalah <strong>"Kagak Ngaruh Tapi Berpengaruh"</strong>. Motto ini mencerminkan semangat mahasiswa Jabodetabek yang meskipun berasal dari luar Indramayu, namun tetap memberikan pengaruh dan kontribusi positif bagi kampus dan masyarakat.</p>',
                'order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('8 FAQ seeded!');
    }
}
