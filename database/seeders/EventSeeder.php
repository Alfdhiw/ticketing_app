<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'user_id' => 1,
                'judul' => 'Konser Musik Rock',
                'deskripsi' => 'Nikmati malam penuh energi dengan band rock terkenal.',
                'tanggal_waktu' => '2026-08-15 19:00:00',
                'lokasi' => 'Stadion Utama',
                'kategori_id' => 1,
                'gambar' => 'konser_rock.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Pameran Seni Kontemporer',
                'deskripsi' => 'Jelajahi karya seni modern dari seniman lokal dan internasional.',
                'tanggal_waktu' => '2026-09-10 10:00:00',
                'lokasi' => 'Galeri Seni Kota',
                'kategori_id' => 2,
                'gambar' => 'pameran_seni.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Festival Makanan Internasional',
                'deskripsi' => 'Cicipi berbagai hidangan lezat dari seluruh dunia.',
                'tanggal_waktu' => '2026-10-05 12:00:00',
                'lokasi' => 'Taman Kota',
                'kategori_id' => 3,
                'gambar' => 'festival_makanan.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Seminar Digital Marketing',
                'deskripsi' => 'Belajar strategi pemasaran digital untuk bisnis masa kini.',
                'tanggal_waktu' => '2026-07-25 09:00:00',
                'lokasi' => 'Aula Nusantara',
                'kategori_id' => 2,
                'gambar' => 'seminar_marketing.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Workshop UI/UX Design',
                'deskripsi' => 'Praktik membuat desain antarmuka yang nyaman dan efektif.',
                'tanggal_waktu' => '2026-08-02 13:00:00',
                'lokasi' => 'Lab Kreatif',
                'kategori_id' => 3,
                'gambar' => 'workshop_uiux.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Live Acoustic Night',
                'deskripsi' => 'Menghadirkan penampilan solo akustik dengan nuansa santai.',
                'tanggal_waktu' => '2026-08-20 20:00:00',
                'lokasi' => 'Rooftop Cafe',
                'kategori_id' => 1,
                'gambar' => 'acoustic_night.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Expo Teknologi 2026',
                'deskripsi' => 'Lihat inovasi teknologi terbaru dari berbagai startup dan brand.',
                'tanggal_waktu' => '2026-09-15 10:00:00',
                'lokasi' => 'Hall Teknologi',
                'kategori_id' => 2,
                'gambar' => 'expo_teknologi.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Bazar Kuliner Malam Hari',
                'deskripsi' => 'Bergabunglah dalam bazar kuliner dengan berbagai makanan khas.',
                'tanggal_waktu' => '2026-10-12 18:00:00',
                'lokasi' => 'Lapangan Merdeka',
                'kategori_id' => 3,
                'gambar' => 'bazar_kuliner.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Talkshow Entrepreneur',
                'deskripsi' => 'Sesi inspiratif dengan para founder dan bisnis digital.',
                'tanggal_waktu' => '2026-11-03 14:00:00',
                'lokasi' => 'Gedung Serbaguna',
                'kategori_id' => 2,
                'gambar' => 'talkshow_entrepreneur.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Workshop Fotografi Pemula',
                'deskripsi' => 'Pelatihan dasar fotografi untuk meningkatkan kemampuan visual.',
                'tanggal_waktu' => '2026-07-30 11:00:00',
                'lokasi' => 'Studio Foto',
                'kategori_id' => 3,
                'gambar' => 'workshop_fotografi.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Konser Jazz Kota',
                'deskripsi' => 'Pengalaman musik jazz dengan para musisi handal.',
                'tanggal_waktu' => '2026-08-28 19:30:00',
                'lokasi' => 'Teater Kota',
                'kategori_id' => 1,
                'gambar' => 'konser_jazz.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Lomba Karya Tulis Ilmiah',
                'deskripsi' => 'Ajang kompetisi menulis karya ilmiah untuk pelajar dan mahasiswa.',
                'tanggal_waktu' => '2026-09-25 08:30:00',
                'lokasi' => 'Auditorium Kampus',
                'kategori_id' => 2,
                'gambar' => 'lomba_karya_tulis.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Festival Film Pendek',
                'deskripsi' => 'Menampilkan film pendek karya sineas muda dari berbagai daerah.',
                'tanggal_waktu' => '2026-10-18 16:00:00',
                'lokasi' => 'Cinema Plaza',
                'kategori_id' => 3,
                'gambar' => 'festival_film.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Seminar Kesehatan Mental',
                'deskripsi' => 'Diskusi penting mengenai kesehatan mental dan kesejahteraan.',
                'tanggal_waktu' => '2026-11-12 09:30:00',
                'lokasi' => 'Center Hall',
                'kategori_id' => 2,
                'gambar' => 'seminar_kesehatan.jpg',
            ],
            [
                'user_id' => 1,
                'judul' => 'Music Festival Summer',
                'deskripsi' => 'Festival musik multi-genre dengan lineup nasional dan lokal.',
                'tanggal_waktu' => '2026-12-05 18:00:00',
                'lokasi' => 'Taman Hiburan',
                'kategori_id' => 1,
                'gambar' => 'music_festival.jpg',
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['judul' => $event['judul']],
                $event
            );
        }
    }
}
