<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Feedback...');

        // ✅ SOLUSI 1: Disable foreign key checks, truncate, enable kembali
        Schema::disableForeignKeyConstraints();
        DB::table('feedback')->truncate();
        Schema::enableForeignKeyConstraints();

        // ✅ ATAU SOLUSI 2: Delete all records (uncomment jika solusi 1 tidak work)
        // DB::table('feedback')->delete();

        $this->command->info('✅ Old feedback data cleared');

        // ========================================
        // FEEDBACK UNTUK CANDIDATE
        // ========================================
        $candidateFeedbacks = [
            // ✅ Soft Skills
            [
                'name' => 'Komunikasi Lancar',
                'description' => 'Kemampuan komunikasi verbal dan non-verbal sangat baik, mudah dipahami',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Attitude Positif',
                'description' => 'Sikap dan perilaku sangat baik, ramah, dan sopan',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Penampilan Profesional',
                'description' => 'Penampilan rapi, bersih, dan profesional',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Tepat Waktu',
                'description' => 'Selalu datang tepat waktu, disiplin tinggi',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Bertanggung Jawab',
                'description' => 'Bertanggung jawab penuh terhadap tugas yang diberikan',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Proaktif',
                'description' => 'Inisiatif tinggi, tidak perlu menunggu perintah',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Teamwork Bagus',
                'description' => 'Kerjasama tim sangat baik, mudah beradaptasi',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Cepat Belajar',
                'description' => 'Mudah menerima instruksi dan cepat memahami tugas baru',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Problem Solving',
                'description' => 'Mampu menyelesaikan masalah dengan baik dan kreatif',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Adaptif',
                'description' => 'Mudah beradaptasi dengan lingkungan kerja baru',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],

            // ✅ Hard Skills (Sales/Marketing)
            [
                'name' => 'Selling Bagus',
                'description' => 'Kemampuan menjual produk/jasa sangat baik, persuasif',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Closing Rate Tinggi',
                'description' => 'Tingkat keberhasilan closing deal sangat tinggi',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Presentasi Menarik',
                'description' => 'Kemampuan presentasi produk sangat menarik dan persuasif',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Negosiasi Handal',
                'description' => 'Kemampuan negosiasi dengan customer sangat baik',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Product Knowledge Baik',
                'description' => 'Pengetahuan produk sangat mendalam dan detail',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],

            // ✅ Work Ethics
            [
                'name' => 'Jujur',
                'description' => 'Sangat jujur dalam bekerja, dapat dipercaya',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Teliti',
                'description' => 'Sangat teliti dalam mengerjakan tugas, minim kesalahan',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Energik',
                'description' => 'Penuh energi dan semangat dalam bekerja',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Kreatif',
                'description' => 'Memiliki ide-ide kreatif dan inovatif',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Fokus',
                'description' => 'Sangat fokus pada target dan tujuan kerja',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],

            // ✅ Customer Service
            [
                'name' => 'Customer Service Excellent',
                'description' => 'Pelayanan kepada customer sangat memuaskan',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Sabar Menghadapi Customer',
                'description' => 'Sangat sabar dalam menghadapi customer yang sulit',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Ramah',
                'description' => 'Sangat ramah dan menyenangkan dalam berinteraksi',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Responsif',
                'description' => 'Cepat merespon pertanyaan dan keluhan customer',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Empati Tinggi',
                'description' => 'Memiliki empati tinggi terhadap kebutuhan customer',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],

            // ✅ Technical Skills
            [
                'name' => 'Menguasai Teknologi',
                'description' => 'Cepat menguasai tools dan teknologi baru',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Data Entry Cepat',
                'description' => 'Kecepatan input data sangat baik dan akurat',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Reporting Rapi',
                'description' => 'Laporan kerja sangat rapi dan terstruktur',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Multitasking',
                'description' => 'Mampu mengerjakan banyak tugas sekaligus dengan baik',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
            [
                'name' => 'Analitis',
                'description' => 'Kemampuan analisis data dan situasi sangat baik',
                'for' => Feedback::FOR_CANDIDATE,
                'is_active' => true,
            ],
        ];

        // ========================================
        // FEEDBACK UNTUK COMPANY
        // ========================================
        $companyFeedbacks = [
            // ✅ Komunikasi & Manajemen
            [
                'name' => 'Komunikasi Jelas',
                'description' => 'Komunikasi perusahaan sangat jelas dan transparan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Manajemen Profesional',
                'description' => 'Manajemen perusahaan sangat profesional dan terorganisir',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Instruksi Jelas',
                'description' => 'Instruksi kerja sangat jelas dan mudah dipahami',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Responsif',
                'description' => 'Perusahaan cepat merespon pertanyaan dan keluhan karyawan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Supportive',
                'description' => 'Perusahaan sangat mendukung karyawan dalam bekerja',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],

            // ✅ Pekerjaan & Deskripsi
            [
                'name' => 'Sesuai Job Desc',
                'description' => 'Pekerjaan sesuai dengan deskripsi yang dijanjikan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Target Realistis',
                'description' => 'Target kerja yang diberikan realistis dan achievable',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Workload Seimbang',
                'description' => 'Beban kerja seimbang dan tidak berlebihan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'SOP Jelas',
                'description' => 'Standard Operating Procedure sangat jelas',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Tools Lengkap',
                'description' => 'Tools dan peralatan kerja lengkap dan memadai',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],

            // ✅ Kompensasi & Benefit
            [
                'name' => 'Gaji Tepat Waktu',
                'description' => 'Pembayaran gaji selalu tepat waktu',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Gaji Sesuai',
                'description' => 'Gaji sesuai dengan yang dijanjikan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Benefit Lengkap',
                'description' => 'Benefit karyawan sangat lengkap (BPJS, asuransi, dll)',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Bonus Menarik',
                'description' => 'Sistem bonus dan insentif sangat menarik',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Komisi Fair',
                'description' => 'Sistem komisi adil dan transparan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],

            // ✅ Lingkungan Kerja
            [
                'name' => 'Lingkungan Nyaman',
                'description' => 'Lingkungan kerja sangat nyaman dan kondusif',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Fasilitas Lengkap',
                'description' => 'Fasilitas kantor lengkap dan modern',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Lokasi Strategis',
                'description' => 'Lokasi kantor strategis dan mudah dijangkau',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Suasana Positif',
                'description' => 'Suasana kerja positif dan menyenangkan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Tim Solid',
                'description' => 'Tim kerja solid dan saling mendukung',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],

            // ✅ Pengembangan Karir
            [
                'name' => 'Training Memadai',
                'description' => 'Pelatihan yang diberikan sangat memadai',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Career Path Jelas',
                'description' => 'Jenjang karir sangat jelas dan terstruktur',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Kesempatan Berkembang',
                'description' => 'Banyak kesempatan untuk berkembang dan belajar',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Mentoring Baik',
                'description' => 'Sistem mentoring dan coaching sangat baik',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Promosi Fair',
                'description' => 'Sistem promosi adil berdasarkan performa',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],

            // ✅ Work-Life Balance
            [
                'name' => 'Work-Life Balance',
                'description' => 'Keseimbangan antara pekerjaan dan kehidupan pribadi sangat baik',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Jam Kerja Fleksibel',
                'description' => 'Jam kerja fleksibel dan tidak kaku',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Cuti Memadai',
                'description' => 'Jatah cuti memadai dan mudah diajukan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Tidak Lembur Berlebihan',
                'description' => 'Tidak ada lembur yang berlebihan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
            [
                'name' => 'Respect Work Hours',
                'description' => 'Perusahaan menghargai jam kerja karyawan',
                'for' => Feedback::FOR_COMPANY,
                'is_active' => true,
            ],
        ];

        // ========================================
        // INSERT DATA
        // ========================================

        try {
            // Insert candidate feedbacks
            foreach ($candidateFeedbacks as $feedback) {
                Feedback::create($feedback);
            }

            $this->command->info('✅ Candidate feedbacks created: ' . count($candidateFeedbacks));

            // Insert company feedbacks
            foreach ($companyFeedbacks as $feedback) {
                Feedback::create($feedback);
            }

            $this->command->info('✅ Company feedbacks created: ' . count($companyFeedbacks));
        } catch (\Exception $e) {
            $this->command->error('❌ Error creating feedbacks: ' . $e->getMessage());
            return;
        }

        // ========================================
        // SUMMARY
        // ========================================
        $total = count($candidateFeedbacks) + count($companyFeedbacks);

        $this->command->info('');
        $this->command->info('📊 FEEDBACK SEEDING SUMMARY:');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("   Total Feedbacks: {$total}");
        $this->command->info("   - For Candidate: " . count($candidateFeedbacks));
        $this->command->info("   - For Company: " . count($companyFeedbacks));
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Show statistics
        try {
            $stats = Feedback::getStatistics();
            $this->command->info('');
            $this->command->info('📈 DATABASE STATISTICS:');
            $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->command->info("   Total in DB: {$stats['total']}");
            $this->command->info("   Active: {$stats['active']}");
            $this->command->info("   Inactive: {$stats['inactive']}");
            $this->command->info("   Candidate (Active): {$stats['active_candidate']}");
            $this->command->info("   Company (Active): {$stats['active_company']}");
            $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        } catch (\Exception $e) {
            $this->command->warn('⚠️  Could not fetch statistics: ' . $e->getMessage());
        }

        $this->command->info('');
        $this->command->info('🎉 Feedback seeding completed successfully!');
    }
}
