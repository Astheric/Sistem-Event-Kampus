<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Checkin;
use App\Models\Event;
use App\Models\EventMember;
use App\Models\Notification;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Users & Roles
        $admin = User::create([
            'name' => 'Administrator Kampus',
            'email' => 'admin@eventhub.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'bio' => 'Super Administrator Sistem Event Kampus EventHub.',
        ]);

        $organizerUser = User::create([
            'name' => 'BEM Fasilkom',
            'email' => 'organizer@eventhub.test',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'phone' => '082198765432',
            'bio' => 'Badan Eksekutif Mahasiswa Fakultas Ilmu Komputer.',
        ]);

        $panitiaUser = User::create([
            'name' => 'Panitia Lapangan',
            'email' => 'panitia@eventhub.test',
            'password' => Hash::make('password'),
            'role' => 'participant', // Participant delegated as event committee
            'phone' => '083811223344',
            'bio' => 'Koordinator Divisi Registrasi & Check-in Lapangan.',
        ]);

        $participantUser = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'participant@eventhub.test',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'phone' => '085712345678',
            'bio' => 'Mahasiswa Teknik Informatika Angkatan 2023.',
        ]);

        $participant2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@eventhub.test',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'phone' => '087812349988',
            'bio' => 'Mahasiswi Sistem Informasi Angkatan 2024.',
        ]);

        // 2. Organizations
        $org = Organization::create([
            'user_id' => $organizerUser->id,
            'name' => 'BEM Fakultas Ilmu Komputer',
            'description' => 'Organisasi kemahasiswaan tingkat fakultas yang menaungi seluruh kegiatan akademik, teknologi, dan minat bakat.',
            'logo' => null,
            'contact' => 'bem.fasilkom@kampus.ac.id / 082198765432',
        ]);

        // 3. Master Categories
        $catSeminar = Category::create(['name' => 'Seminar Nasional', 'slug' => 'seminar-nasional', 'icon' => '🎤']);
        $catWorkshop = Category::create(['name' => 'Workshop & Bootcamp', 'slug' => 'workshop-bootcamp', 'icon' => '💻']);
        $catFestival = Category::create(['name' => 'Festival & Seni', 'slug' => 'festival-seni', 'icon' => '🎨']);
        $catKompetisi = Category::create(['name' => 'Kompetisi Mahasiswa', 'slug' => 'kompetisi-mahasiswa', 'icon' => '🏆']);
        $catExpo = Category::create(['name' => 'Expo Karir & Industri', 'slug' => 'expo-karir', 'icon' => '💼']);
        $catWebinar = Category::create(['name' => 'Webinar Terbuka', 'slug' => 'webinar-terbuka', 'icon' => '🌐']);

        // 4. Venues
        $venue1 = Venue::create([
            'name' => 'Auditorium Graha Sasana Kampus',
            'address' => 'Gedung Rektorat Lt. 3, Jl. Pendidikan No. 45',
            'city' => 'Bandung',
            'maps_url' => 'https://maps.google.com/?q=Bandung',
        ]);

        $venue2 = Venue::create([
            'name' => 'Creative Hub & Multimedia Lab',
            'address' => 'Gedung Perpustakaan Pusat Lt. 2, Sayap Timur',
            'city' => 'Bandung',
            'maps_url' => 'https://maps.google.com/?q=Bandung',
        ]);

        $venue3 = Venue::create([
            'name' => 'Lapangan Terbuka Plaza Budaya',
            'address' => 'Area Student Center Kampus Terpadu',
            'city' => 'Bandung',
            'maps_url' => 'https://maps.google.com/?q=Bandung',
        ]);

        // 5. Events

        // Event 1: PUBLISHED - Tech Summit (Paid & Multi-tier)
        $event1 = Event::create([
            'organization_id' => $org->id,
            'category_id' => $catSeminar->id,
            'venue_id' => $venue1->id,
            'title' => 'National Tech Summit 2026: AI & Cyber Security Horizons',
            'slug' => 'national-tech-summit-2026',
            'banner' => null,
            'description' => 'Konferensi teknologi tahunan terbesar di kampus yang mempertemukan pakar industri terkemuka, peneliti AI, dan praktisi keamanan siber. Pelajari tren masa depan, arsitektur AI agentic, dan pertahanan siber modern!',
            'start_time' => $now->copy()->addDays(14)->setTime(9, 0),
            'end_time' => $now->copy()->addDays(14)->setTime(16, 30),
            'capacity' => 300,
            'status' => 'PUBLISHED',
        ]);

        $t1Early = Ticket::create([
            'event_id' => $event1->id,
            'name' => 'Early Bird',
            'price' => 25000,
            'quota' => 50,
            'available_quota' => 48,
            'start_sale' => $now->copy()->subDays(5),
            'end_sale' => $now->copy()->addDays(5),
        ]);

        $t1Regular = Ticket::create([
            'event_id' => $event1->id,
            'name' => 'Regular',
            'price' => 50000,
            'quota' => 200,
            'available_quota' => 199,
            'start_sale' => $now->copy()->subDays(2),
            'end_sale' => $now->copy()->addDays(13),
        ]);

        $t1VIP = Ticket::create([
            'event_id' => $event1->id,
            'name' => 'VIP Backstage & Networking Lunch',
            'price' => 120000,
            'quota' => 50,
            'available_quota' => 49,
            'start_sale' => $now->copy()->subDays(5),
            'end_sale' => $now->copy()->addDays(13),
        ]);

        // Committee delegation for Event 1
        EventMember::create([
            'event_id' => $event1->id,
            'user_id' => $panitiaUser->id,
            'committee_role' => 'Registration',
        ]);

        // Event 2: PUBLISHED - Free Workshop
        $event2 = Event::create([
            'organization_id' => $org->id,
            'category_id' => $catWorkshop->id,
            'venue_id' => $venue2->id,
            'title' => 'Hands-on Workshop: Neo-Brutalism Web Design & Modern Tailwind',
            'slug' => 'hands-on-workshop-neo-brutalism',
            'banner' => null,
            'description' => 'Bedah tuntas implementasi estetika Neo-Brutalism pada antarmuka web modern. Pelajari kombinasi border tebal, warna kontras tinggi, solid shadows, dan micro-interaction yang tactile!',
            'start_time' => $now->copy()->addDays(7)->setTime(13, 0),
            'end_time' => $now->copy()->addDays(7)->setTime(17, 0),
            'capacity' => 60,
            'status' => 'PUBLISHED',
        ]);

        $t2Free = Ticket::create([
            'event_id' => $event2->id,
            'name' => 'Peserta Workshop (Gratis)',
            'price' => 0,
            'quota' => 60,
            'available_quota' => 58,
            'start_sale' => $now->copy()->subDays(3),
            'end_sale' => $now->copy()->addDays(6),
        ]);

        // Event 3: SUBMITTED - Awaiting Admin Approval
        $event3 = Event::create([
            'organization_id' => $org->id,
            'category_id' => $catKompetisi->id,
            'venue_id' => $venue1->id,
            'title' => 'Hackathon Mahasiswa Nasional: Inovasi Kampus Digital 2026',
            'slug' => 'hackathon-mahasiswa-nasional-2026',
            'banner' => null,
            'description' => 'Ajang kompetisi coding dan pembuatan prototipe solusi berbasis AI dan Web selama 36 jam nonstop. Total hadiah Rp 35.000.000 untuk 3 tim terbaik!',
            'start_time' => $now->copy()->addDays(28)->setTime(8, 0),
            'end_time' => $now->copy()->addDays(30)->setTime(18, 0),
            'capacity' => 100,
            'status' => 'SUBMITTED', // Needs admin moderation approval!
        ]);

        Ticket::create([
            'event_id' => $event3->id,
            'name' => 'Biaya Registrasi Tim (3 Org)',
            'price' => 75000,
            'quota' => 30,
            'available_quota' => 30,
            'start_sale' => $now->copy()->addDays(1),
            'end_sale' => $now->copy()->addDays(20),
        ]);

        // Event 4: COMPLETED - Past Event with Attended Attendees & Certificates
        $event4 = Event::create([
            'organization_id' => $org->id,
            'category_id' => $catSeminar->id,
            'venue_id' => $venue1->id,
            'title' => 'Seminar Kepemimpinan & Etika Digital Organisasi Mahasiswa',
            'slug' => 'seminar-kepemimpinan-etika-digital',
            'banner' => null,
            'description' => 'Seminar penguatan kapasitas fungsionaris BEM, DPM, dan UKM se-lingkungan kampus tentang tata kelola organisasi yang transparan, etis, dan berbasis kolaborasi digital.',
            'start_time' => $now->copy()->subDays(10)->setTime(8, 30),
            'end_time' => $now->copy()->subDays(10)->setTime(12, 30),
            'capacity' => 150,
            'status' => 'COMPLETED',
        ]);

        $t4Regular = Ticket::create([
            'event_id' => $event4->id,
            'name' => 'Tiket Delegasi',
            'price' => 15000,
            'quota' => 150,
            'available_quota' => 148,
            'start_sale' => $now->copy()->subDays(25),
            'end_sale' => $now->copy()->subDays(11),
        ]);

        // Seed Registrations:
        // Ahmad Fauzi (participant) on Event 1 (CONFIRMED paid ticket)
        $reg1 = Registration::create([
            'ticket_id' => $t1Early->id,
            'user_id' => $participantUser->id,
            'event_id' => $event1->id,
            'registration_code' => 'EVT-2026-TK8812',
            'status' => 'CONFIRMED',
            'notes' => 'Pendaftaran Early Bird terkonfirmasi',
        ]);

        Payment::create([
            'registration_id' => $reg1->id,
            'payment_method' => 'Bank Transfer (BCA)',
            'amount' => 25000,
            'proof_file' => null,
            'status' => 'VERIFIED',
            'verified_at' => $now->copy()->subDays(1),
            'verified_by' => $organizerUser->id,
            'notes' => 'Bukti transfer valid dan nominal sesuai.',
        ]);

        // Ahmad Fauzi on Event 2 (Free ticket CONFIRMED)
        $reg2 = Registration::create([
            'ticket_id' => $t2Free->id,
            'user_id' => $participantUser->id,
            'event_id' => $event2->id,
            'registration_code' => 'EVT-2026-WK5501',
            'status' => 'CONFIRMED',
            'notes' => 'Tiket gratis workshop',
        ]);

        // Ahmad Fauzi on Event 4 (ATTENDED - with checkin & issued certificate & review!)
        $reg3 = Registration::create([
            'ticket_id' => $t4Regular->id,
            'user_id' => $participantUser->id,
            'event_id' => $event4->id,
            'registration_code' => 'EVT-2026-PA1001',
            'status' => 'ATTENDED',
            'notes' => 'Hadir penuh di lokasi seminar',
        ]);

        Payment::create([
            'registration_id' => $reg3->id,
            'payment_method' => 'E-Wallet (GoPay)',
            'amount' => 15000,
            'proof_file' => null,
            'status' => 'VERIFIED',
            'verified_at' => $now->copy()->subDays(12),
            'verified_by' => $organizerUser->id,
        ]);

        Checkin::create([
            'registration_id' => $reg3->id,
            'scanned_by' => $panitiaUser->id,
            'checked_in_at' => $now->copy()->subDays(10)->setTime(8, 45),
        ]);

        $cert3 = Certificate::create([
            'registration_id' => $reg3->id,
            'certificate_number' => 'CERT-2026-908123',
            'pdf_path' => null,
            'issued_at' => $now->copy()->subDays(9)->setTime(15, 0),
        ]);

        Review::create([
            'event_id' => $event4->id,
            'user_id' => $participantUser->id,
            'rating' => 5,
            'comment' => 'Materinya sangat insightful dan pembawaannya santai tapi sarat ilmu. Penggunaan QR check-in sangat cepat dan sertifikat langsung bisa diunduh!',
        ]);

        // Siti Nurhaliza on Event 4 (ATTENDED with certificate)
        $reg4 = Registration::create([
            'ticket_id' => $t4Regular->id,
            'user_id' => $participant2->id,
            'event_id' => $event4->id,
            'registration_code' => 'EVT-2026-PA1002',
            'status' => 'ATTENDED',
        ]);

        Payment::create([
            'registration_id' => $reg4->id,
            'payment_method' => 'Bank Transfer (Mandiri)',
            'amount' => 15000,
            'status' => 'VERIFIED',
            'verified_at' => $now->copy()->subDays(12),
            'verified_by' => $organizerUser->id,
        ]);

        Checkin::create([
            'registration_id' => $reg4->id,
            'scanned_by' => $panitiaUser->id,
            'checked_in_at' => $now->copy()->subDays(10)->setTime(8, 50),
        ]);

        Certificate::create([
            'registration_id' => $reg4->id,
            'certificate_number' => 'CERT-2026-908124',
            'pdf_path' => null,
            'issued_at' => $now->copy()->subDays(9)->setTime(15, 0),
        ]);

        Review::create([
            'event_id' => $event4->id,
            'user_id' => $participant2->id,
            'rating' => 5,
            'comment' => 'Keren banget acaranya! Semoga sering diadakan seminar kepemimpinan seperti ini.',
        ]);

        // Notifications
        Notification::create([
            'user_id' => $participantUser->id,
            'title' => 'Tiket Terbit: National Tech Summit 2026',
            'message' => 'Tiket Anda dengan kode EVT-2026-TK8812 telah aktif.',
            'link' => '/participant/tickets',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $participantUser->id,
            'title' => 'Sertifikat Siap Diunduh!',
            'message' => 'Sertifikat Seminar Kepemimpinan (CERT-2026-908123) telah terbit.',
            'link' => '/participant/certificates',
            'is_read' => true,
        ]);

        // Audit Logs
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'category.created',
            'entity_type' => 'Category',
            'entity_id' => $catSeminar->id,
            'ip_address' => '127.0.0.1',
            'details' => ['name' => 'Seminar Nasional'],
        ]);

        AuditLog::create([
            'user_id' => $organizerUser->id,
            'action' => 'event.created',
            'entity_type' => 'Event',
            'entity_id' => $event1->id,
            'ip_address' => '127.0.0.1',
            'details' => ['title' => $event1->title, 'status' => 'PUBLISHED'],
        ]);

        AuditLog::create([
            'user_id' => $panitiaUser->id,
            'action' => 'checkin.verified',
            'entity_type' => 'Registration',
            'entity_id' => $reg3->id,
            'ip_address' => '127.0.0.1',
            'details' => ['code' => 'EVT-2026-PA1001', 'attendee' => 'Ahmad Fauzi'],
        ]);
    }
}
