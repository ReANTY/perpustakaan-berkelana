<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Sample Member
        $admin = User::updateOrCreate(
            ['email' => 'admin@berkelana.id'],
            [
                'name' => 'Admin Perpustakaan Berkelana',
                'password' => Hash::make('password'),
                'phone' => '085726029832',
                'address' => 'Sumber, Kabupaten Cirebon',
                'role' => 'admin',
                'is_verified' => true,
                'is_suspended' => false,
            ]
        );

        $member = User::updateOrCreate(
            ['email' => 'anggota@berkelana.id'],
            [
                'name' => 'Raka Pratama',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Dewi Sartika, Sumber, Kab. Cirebon',
                'role' => 'user',
                'is_verified' => true,
                'is_suspended' => false,
                'notes' => 'Anggota aktif lapak akhir pekan.',
            ]
        );

        // 2. 8 Indonesian Literature & Non-Fiction Books
        $booksData = [
            [
                'code' => 'PB-001',
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 2005,
                'category' => 'Sastra',
                'synopsis' => 'Roman sejarah pergerakan nasional Indonesia pada awal abad ke-20 yang mengikuti kisah Minke, seorang pribumi terpelajar yang berjuang melawan ketidakadilan kolonial.',
                'status' => 'available',
                'is_featured' => true,
                'lapak_request_count' => 14,
            ],
            [
                'code' => 'PB-002',
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2017,
                'category' => 'Fiksi',
                'synopsis' => 'Kisah tentang persahabatan, cinta, keluarga, dan kehilangan para aktivis mahasiswa menjelang reformasi 1998.',
                'status' => 'borrowed',
                'is_featured' => true,
                'lapak_request_count' => 19,
            ],
            [
                'code' => 'PB-003',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Kompas',
                'publication_year' => 2018,
                'category' => 'Filsafat',
                'synopsis' => 'Pengantar praktis filsafat Stoisisme Yunani-Romawi kuno untuk menghadapi kecemasan, overthinking, dan dinamika kehidupan modern.',
                'status' => 'borrowed',
                'is_featured' => true,
                'lapak_request_count' => 11,
            ],
            [
                'code' => 'PB-004',
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2002,
                'category' => 'Sastra',
                'synopsis' => 'Epik realisme magis berlatar kota pesisir Halimunda dari masa kolonial hingga pasca-kemerdekaan melalui kisah Dewi Ayu dan keempat putrinya.',
                'status' => 'available',
                'is_featured' => false,
                'lapak_request_count' => 8,
            ],
            [
                'code' => 'PB-005',
                'title' => 'Sejarah Cirebon: Dari Kerajaan hingga Kabupaten',
                'author' => 'Sulendraningrat',
                'publisher' => 'Balai Pustaka',
                'publication_year' => 1985,
                'category' => 'Sejarah',
                'synopsis' => 'Menelusuri jejak sejarah Kesultanan Cirebon, akulturasi budaya pesisir Jawa-Sunda, serta peran Sunan Gunung Jati dalam peradaban Cirebon.',
                'status' => 'booked',
                'is_featured' => true,
                'lapak_request_count' => 9,
            ],
            [
                'code' => 'PB-006',
                'title' => 'Atomic Habits (Edisi Bahasa Indonesia)',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2019,
                'category' => 'Pengembangan Diri',
                'synopsis' => 'Panduan membangun kebiasaan baik dan menghilangkan kebiasaan buruk melalui perubahan kecil sebesar 1% setiap hari.',
                'status' => 'available',
                'is_featured' => false,
                'lapak_request_count' => 7,
            ],
            [
                'code' => 'PB-007',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'category' => 'Anak & Remaja',
                'synopsis' => 'Kisah inspiratif sepuluh anak di Pulau Belitung yang bersekolah di SD Muhammadiyah penuh keterbatasan namun kaya akan mimpi.',
                'status' => 'available',
                'is_featured' => false,
                'lapak_request_count' => 5,
            ],
            [
                'code' => 'PB-008',
                'title' => 'Madilog: Materialisme, Dialektika, dan Logika',
                'author' => 'Tan Malaka',
                'publisher' => 'Narasi',
                'publication_year' => 1943,
                'category' => 'Filsafat',
                'synopsis' => 'Karya pemikiran filosofis Tan Malaka tentang cara berpikir ilmiah dan rasional untuk membebaskan bangsa dari belenggu tahayul dan penjajahan.',
                'status' => 'available',
                'is_featured' => false,
                'lapak_request_count' => 6,
            ],
        ];

        $books = [];
        foreach ($booksData as $data) {
            $books[$data['code']] = Book::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        // 3. Activities (1 Pinned Upcoming Lapak, 2 Event Reports, 1 Threads Highlight)
        Activity::updateOrCreate(
            ['slug' => 'lapak-baca-gratis-alun-alun-pataraksa-pekan-ini'],
            [
                'title' => 'Lapak Baca Buku Gratis — Edisi Akhir Pekan di Alun-Alun Pataraksa',
                'type' => 'upcoming_lapak',
                'event_date' => now()->next('Sunday')->setTime(15, 30),
                'location' => 'Alun-Alun Pataraksa, Sumber, Kabupaten Cirebon',
                'content' => "Mari merapat ke gelar tikar baca gratis sore ini di sisi timur Alun-Alun Pataraksa!\n\nKami membawa 60+ koleksi buku sastra, sejarah Cirebon, filsafat populer, hingga bacaan anak. Gratis untuk dibaca di tempat, dan anggota terdaftar bisa langsung meminjam buku untuk dibawa pulang selama 7 hari.",
                'is_pinned_banner' => true,
                'is_published' => true,
            ]
        );

        Activity::updateOrCreate(
            ['slug' => 'catatan-lapak-baca-senja-dan-diskusi-sastra-sumber'],
            [
                'title' => 'Catatan Lapak Baca Senja & Diskusi Sastra Lokal Sumber',
                'type' => 'event_report',
                'event_date' => now()->subDays(7)->setTime(16, 0),
                'location' => 'Alun-Alun Pataraksa, Sumber, Cirebon',
                'content' => "Minggu lalu lebih dari 35 pembaca mampir ke tikar baca kami di Pataraksa. Obrolan hangat mengalir dari buku-buku Pramoedya hingga sejarah lokal Cirebon. Terima kasih untuk teman-teman yang sudah ikut menyumbangkan buku bacaan anak!",
                'is_pinned_banner' => false,
                'is_published' => true,
            ]
        );

        Activity::updateOrCreate(
            ['slug' => 'bedah-buku-santai-filosofi-teras-bersama-komunitas'],
            [
                'title' => 'Bedah Buku Santai: Filosofi Teras di Bawah Pohon Pataraksa',
                'type' => 'event_report',
                'event_date' => now()->subDays(14)->setTime(16, 0),
                'location' => 'Alun-Alun Pataraksa, Sumber, Cirebon',
                'content' => "Diskusi santai sore hari membahas konsep dikotomi kendali dari buku Filosofi Teras. Banyak pembaca muda dari Sumber dan sekitarnya berbagi pengalaman mengelola stres kuliah dan pekerjaan.",
                'is_pinned_banner' => false,
                'is_published' => true,
            ]
        );

        Activity::updateOrCreate(
            ['slug' => 'sorotan-threads-kenapa-lapak-baca-jalanan-tetap-relevan'],
            [
                'title' => 'Sorotan Threads: Mengapa Lapak Baca Ruang Terbuka Tetap Relevan',
                'type' => 'threads_highlight',
                'event_date' => now()->subDays(3),
                'location' => 'Threads (@perpustakaan.berkelana)',
                'content' => 'Obrolan menarik di Threads tentang bagaimana lapak buku gratis di ruang publik mempertemukan pembaca lintas usia tanpa sekat.',
                'threads_url' => 'https://www.threads.net/@threads/post/C8xYzExample',
                'is_pinned_banner' => false,
                'is_published' => true,
            ]
        );

        // 4. 2 Sample Loans (1 active, 1 overdue by 2 days -> Rp 4.000)
        Loan::updateOrCreate(
            ['loan_code' => 'LN-20261120-001'],
            [
                'user_id' => $member->id,
                'book_id' => $books['PB-002']->id,
                'duration_days' => 7,
                'pickup_date' => now()->subDays(5)->toDateString(),
                'collateral_type' => 'KTP',
                'status' => 'active',
                'borrowed_at' => now()->subDays(5),
                'due_date' => now()->addDays(2)->toDateString(),
                'fine_amount' => 0,
                'is_fine_paid' => false,
            ]
        );

        Loan::updateOrCreate(
            ['loan_code' => 'LN-20261116-002'],
            [
                'user_id' => $member->id,
                'book_id' => $books['PB-003']->id,
                'duration_days' => 7,
                'pickup_date' => now()->subDays(9)->toDateString(),
                'collateral_type' => 'KTM',
                'status' => 'overdue',
                'borrowed_at' => now()->subDays(9),
                'due_date' => now()->subDays(2)->toDateString(),
                'fine_amount' => 4000,
                'is_fine_paid' => false,
                'admin_notes' => 'Terlambat 2 hari, sudah dijadwalkan pengembalian via WA.',
            ]
        );
    }
}
