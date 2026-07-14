<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Support\Str;

class WebsiteContentService
{
    public static function sections(): array
    {
        return [
            'global' => [
                'label' => 'Identitas Website',
                'description' => 'Logo, nama website, navigasi, dan informasi footer.',
            ],
            'home_hero' => [
                'label' => 'Beranda - Hero',
                'description' => 'Tampilan utama paling atas pada halaman Beranda.',
            ],
            'home_tour' => [
                'label' => 'Beranda - Promosi Virtual Tour',
                'description' => 'Judul, gambar, deskripsi, dan poin keunggulan virtual tour.',
            ],
            'home_facilities' => [
                'label' => 'Beranda - Fasilitas',
                'description' => 'Judul dan pengantar daftar fasilitas pada Beranda.',
            ],
            'campus_location' => [
                'label' => 'Lokasi Kampus',
                'description' => 'Gambar, keterangan lokasi, dan URL Google Maps.',
            ],
            'about' => [
                'label' => 'Tentang Program Studi',
                'description' => 'Gambar, visi, penjelasan, dan poin komitmen program studi.',
            ],
            'contact' => [
                'label' => 'Kontak & Informasi',
                'description' => 'Alamat, telepon, email, jam operasional, dan teks formulir.',
            ],
            'virtual_tour' => [
                'label' => 'Halaman Virtual Tour',
                'description' => 'Hero dan pengantar daftar virtual tour umum.',
            ],
            'facilities_page' => [
                'label' => 'Halaman Fasilitas',
                'description' => 'Badge, judul, dan deskripsi halaman virtual tour fasilitas.',
            ],
        ];
    }

    public static function definitions(): array
    {
        return [
            'site_logo' => self::field('global', 'Logo Website', 'image', 'asset/logo-unpam-300x291.png'),
            'navbar_title' => self::field('global', 'Nama Universitas', 'text', 'UNIVERSITAS PAMULANG'),
            'navbar_subtitle' => self::field('global', 'Nama Program Studi', 'text', 'Prodi Sistem Informasi'),
            'footer_description' => self::field('global', 'Deskripsi Footer', 'text', 'Universitas Pamulang memberikan pendidikan berkualitas untuk masa depan yang lebih baik.'),
            'footer_text' => self::field('global', 'Teks Hak Cipta', 'text', 'Universitas Pamulang'),

            'hero_title' => self::field('home_hero', 'Judul Utama', 'text', 'SELAMAT DATANG DI'),
            'hero_subtitle' => self::field('home_hero', 'Subjudul Utama', 'text', 'VIRTUAL TOUR PRODI SISTEM INFORMASI'),
            'hero_description' => self::field('home_hero', 'Deskripsi Hero', 'text', 'Jelajahi fasilitas dan lingkungan kampus Universitas Pamulang secara virtual'),
            'hero_background_image' => self::field('home_hero', 'Gambar Latar Hero', 'image', 'https://static.republika.co.id/uploads/member/images/news/2x4cu8nrv8.jpg'),

            'home_tour_title' => self::field('home_tour', 'Judul Bagian', 'text', 'VIRTUAL TOUR 360°'),
            'home_tour_image' => self::field('home_tour', 'Gambar Pratinjau', 'image', 'https://static.republika.co.id/uploads/member/images/news/2x4cu8nrv8.jpg'),
            'home_tour_heading' => self::field('home_tour', 'Judul Penjelasan', 'text', 'Jelajahi Kampus Secara Virtual'),
            'home_tour_description' => self::field('home_tour', 'Deskripsi', 'text', 'Dengan teknologi virtual tour 360°, Anda dapat menjelajahi berbagai fasilitas Prodi Sistem Informasi Universitas Pamulang dari mana saja dan kapan saja.'),
            'home_tour_feature_1' => self::field('home_tour', 'Keunggulan 1', 'text', 'Pandangan 360° berbagai ruangan penting'),
            'home_tour_feature_2' => self::field('home_tour', 'Keunggulan 2', 'text', 'Navigasi intuitif dengan menu interaktif'),
            'home_tour_feature_3' => self::field('home_tour', 'Keunggulan 3', 'text', 'Informasi detail setiap fasilitas'),
            'home_tour_button_text' => self::field('home_tour', 'Teks Tombol', 'text', 'Mulai Virtual Tour'),

            'facilities_title' => self::field('home_facilities', 'Judul Fasilitas', 'text', 'FASILITAS UNPAM'),
            'facilities_description' => self::field('home_facilities', 'Deskripsi Fasilitas', 'text', 'Temukan berbagai fasilitas modern yang mendukung proses belajar mengajar di Program Studi Sistem Informasi'),

            'campus_location_title' => self::field('campus_location', 'Judul Bagian Lokasi', 'text', 'Lokasi Kampus UNPAM Viktor'),
            'campus_map_title' => self::field('campus_location', 'Judul Lokasi Kampus', 'text', 'Kampus UNPAM Viktor'),
            'campus_map_image' => self::field('campus_location', 'Gambar Lokasi Kampus', 'image', 'asset/kampus2.B0WqicWG.jpg'),
            'campus_map_description' => self::field('campus_location', 'Deskripsi Lokasi Kampus', 'text', 'Lihat lokasi kampus untuk orientasi lebih lanjut.'),
            'google_maps_title' => self::field('campus_location', 'Judul Google Maps', 'text', 'Lokasi di Google Maps'),
            'google_maps_embed_url' => self::field('campus_location', 'URL Embed Google Maps', 'url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.155041658!2d106.6889577!3d-6.3462879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e5a6e26dc3cd%3A0xccd6344b8021119d!2sUniversitas%20Pamulang%20Kampus%202%20(UNPAM%20Viktor)!5e0!3m2!1sid!2sid!4v1723900000000!5m2!1sid!2sid'),
            'google_maps_description' => self::field('campus_location', 'Deskripsi Google Maps', 'text', 'Temukan lokasi kami dengan mudah melalui Google Maps.'),

            'about_title' => self::field('about', 'Judul Tentang', 'text', 'TENTANG PRODI SISTEM INFORMASI'),
            'about_image' => self::field('about', 'Gambar Tentang', 'image', 'asset/Landscape HMSI.jpeg'),
            'about_subtitle' => self::field('about', 'Subjudul Tentang', 'text', 'Visi & Misi'),
            'about_description' => self::field('about', 'Deskripsi Utama', 'text', 'Menjadi program studi unggulan dalam bidang Sistem Informasi yang berdaya saing di tingkat nasional pada tahun 2030.'),
            'about_commitment_text' => self::field('about', 'Pengantar Komitmen', 'text', 'Untuk mencapai visi ini, kami berkomitmen untuk:'),
            'about_point_1' => self::field('about', 'Poin Komitmen 1', 'text', 'Menyelenggarakan pendidikan berbasis kompetensi'),
            'about_point_2' => self::field('about', 'Poin Komitmen 2', 'text', 'Mengembangkan penelitian inovatif'),
            'about_point_3' => self::field('about', 'Poin Komitmen 3', 'text', 'Melaksanakan pengabdian kepada masyarakat'),
            'about_point_4' => self::field('about', 'Poin Komitmen 4', 'text', 'Menjalin kerjasama dengan berbagai pihak'),
            'about_button_text' => self::field('about', 'Teks Tombol Selengkapnya', 'text', 'Selengkapnya'),

            'contact_title' => self::field('contact', 'Judul Kontak', 'text', 'HUBUNGI KAMI'),
            'contact_description' => self::field('contact', 'Deskripsi Kontak', 'text', 'Dapatkan informasi lebih lanjut tentang program studi dan fasilitas kampus.'),
            'contact_information_title' => self::field('contact', 'Judul Informasi', 'text', 'INFORMASI'),
            'contact_address' => self::field('contact', 'Alamat', 'text', 'Jl. Raya Puspitek, Buaran, Kec. Pamulang, Kota Tangerang Selatan, Banten 15310'),
            'contact_phone' => self::field('contact', 'Telepon', 'text', '021 7412 566 - Ext. 123 (Prodi Sistem Informasi)'),
            'contact_email' => self::field('contact', 'Email', 'text', 'humas@unpam.ac.id'),
            'contact_hours_title' => self::field('contact', 'Judul Jam Operasional', 'text', 'Jam Operasional'),
            'contact_weekday_hours' => self::field('contact', 'Jam Senin - Jumat', 'text', '08:00 - 16:00'),
            'contact_saturday_hours' => self::field('contact', 'Jam Sabtu', 'text', '08:00 - 14:00'),
            'contact_sunday_hours' => self::field('contact', 'Jam Minggu', 'text', 'Tutup'),
            'feedback_title' => self::field('contact', 'Judul Kritik & Saran', 'text', 'KRITIK & SARAN'),
            'feedback_submit_text' => self::field('contact', 'Teks Tombol Kirim', 'text', 'Kirim Pesan'),

            'vr_title' => self::field('virtual_tour', 'Judul Hero', 'text', 'SELAMAT DATANG DI'),
            'vr_subtitle' => self::field('virtual_tour', 'Subjudul Hero', 'text', 'VIRTUAL TOUR PRODI SISTEM INFORMASI'),
            'vr_description' => self::field('virtual_tour', 'Deskripsi Hero', 'text', 'Jelajahi fasilitas dan lingkungan kampus Universitas Pamulang secara virtual'),
            'vr_background_image' => self::field('virtual_tour', 'Gambar Latar Hero', 'image', 'https://static.republika.co.id/uploads/member/images/news/2x4cu8nrv8.jpg'),
            'vr_section_title' => self::field('virtual_tour', 'Judul Daftar Tour', 'text', 'VIRTUAL TOUR 360°'),
            'vr_section_description' => self::field('virtual_tour', 'Deskripsi Daftar Tour', 'text', 'Pilih lokasi di bawah untuk memulai tur virtual interaktif. Gunakan mouse atau sentuh layar untuk melihat ke segala arah.'),

            'facility_page_badge' => self::field('facilities_page', 'Teks Badge', 'text', 'Virtual Tour Khusus Fasilitas'),
            'facility_page_title' => self::field('facilities_page', 'Judul Halaman', 'text', 'FASILITAS UNPAM VIKTOR'),
            'facility_page_description' => self::field('facilities_page', 'Deskripsi Halaman', 'text', 'Pilih fasilitas untuk melihat informasi dan menjelajahi ruangannya melalui virtual tour 360°.'),
        ];
    }

    public function all(): array
    {
        $definitions = self::definitions();
        $rows = Content::whereNotIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->get()
            ->keyBy('content_key');
        $content = [];

        foreach ($definitions as $key => $definition) {
            $row = $rows->get($key);
            $content[$key] = $row?->content_value ?? $definition['value'];

            $type = $row?->content_type ?? $definition['type'];
            if ($type === 'image') {
                $content[$key.'_url'] = $this->resolveImageUrl($content[$key]);
            }
        }

        foreach ($rows as $key => $row) {
            if (! array_key_exists($key, $content)) {
                $content[$key] = $row->content_value;
                if ($row->content_type === 'image') {
                    $content[$key.'_url'] = $this->resolveImageUrl($row->content_value);
                }
            }
        }

        return $content;
    }

    private static function field(string $section, string $label, string $type, string $value): array
    {
        return compact('section', 'label', 'type', 'value');
    }

    private function resolveImageUrl(string $value): string
    {
        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:'])) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }
}
