<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPostings;
use App\Models\Companies;
use App\Models\Industries;
use App\Models\TypeJobs;
use App\Models\Cities;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobPostingsSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ✅ Validasi data master
        if (Companies::count() === 0) {
            $this->command->error('⚠️ Tidak ada data companies. Jalankan CompanySeeder terlebih dahulu.');
            return;
        }

        if (Industries::count() === 0) {
            $this->command->error('⚠️ Tidak ada data industries. Jalankan IndustrySeeder terlebih dahulu.');
            return;
        }

        if (TypeJobs::count() === 0) {
            $this->command->error('⚠️ Tidak ada data type jobs. Jalankan TypeJobSeeder terlebih dahulu.');
            return;
        }

        if (Cities::count() === 0) {
            $this->command->error('⚠️ Tidak ada data cities. Jalankan CitySeeder terlebih dahulu.');
            return;
        }

        $this->command->info('🚀 Membuat 10,000 job postings...');

        // ✅ Ambil ID sekali saja (performa lebih baik)
        $companyIds = Companies::pluck('id')->toArray();
        $industryIds = Industries::pluck('id')->toArray();
        $typeJobIds = TypeJobs::pluck('id')->toArray();
        $cityIds = Cities::pluck('id')->toArray();

        // ✅ Daftar job title realistis
        $jobTitles = [
            'Backend Developer',
            'Frontend Developer',
            'Full Stack Developer',
            'Mobile Developer',
            'Data Analyst',
            'Data Scientist',
            'Machine Learning Engineer',
            'DevOps Engineer',
            'Cloud Engineer',
            'Product Manager',
            'Project Manager',
            'Scrum Master',
            'UI/UX Designer',
            'Graphic Designer',
            'Content Writer',
            'Digital Marketing Specialist',
            'SEO Specialist',
            'Social Media Manager',
            'Customer Service Representative',
            'Sales Executive',
            'Business Analyst',
            'HR Manager',
            'Recruiter',
            'Admin',
            'Accounting Staff',
            'Finance Manager',
            'IT Support',
            'Network Administrator',
            'Quality Assurance Engineer',
            'Security Analyst',
            'System Administrator',
            'Warehouse Supervisor',
            'Logistics Coordinator',
            'Driver',
            'Receptionist',
            'Chef',
            'Waiter',
            'Cashier',
            'Barista',
            'Pelaut',
            'Teknisi',
            'Montir',
            'Tukang Jahit',
            'Operator Produksi'
        ];

        // ✅ Bulk insert dengan chunking (500 records per batch)
        $chunkSize = 50;
        $totalRecords = 100;

        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $jobPostings = [];
            $limit = min($chunkSize, $totalRecords - $i);

            for ($j = 0; $j < $limit; $j++) {
                $openRecruitment = $faker->dateTimeBetween('-30 days', '+30 days');
                $closeRecruitment = (clone $openRecruitment)->modify('+' . $faker->numberBetween(7, 60) . ' days');

                $minAge = $faker->numberBetween(18, 30);
                $maxAge = $faker->numberBetween($minAge + 5, 50);

                // ✅ Generate deskripsi yang tidak terlalu panjang (2-3 paragraf cukup)
                $description = $faker->paragraph(2) . "\n\n" . $faker->paragraph(2);

                $jobPostings[] = [
                    'title' => $faker->randomElement($jobTitles),
                    'description' => $description,
                    'salary' => $faker->numberBetween(3000000, 25000000),
                    'address' => $faker->address,
                    'min_age' => $minAge,
                    'max_age' => $maxAge,
                    'min_height' => $faker->numberBetween(150, 180),
                    'min_weight' => $faker->numberBetween(40, 90),
                    'verification_status' => $faker->randomElement(['Approved', 'Pending', 'Rejected']),
                    'status' => $faker->randomElement(['Open', 'Closed', 'Draft']),
                    'gender' => $faker->randomElement(['Male', 'Female', 'Both']),
                    'open_recruitment' => $openRecruitment->format('Y-m-d'),
                    'close_recruitment' => $closeRecruitment->format('Y-m-d'),
                    'slot' => $faker->numberBetween(1, 20),
                    'level_english' => $faker->randomElement(['beginner', 'intermediate', 'expert']),
                    'level_mandarin' => $faker->randomElement(['beginner', 'intermediate', 'expert']),
                    'has_interview' => $faker->boolean(70), // 70% ada interview
                    'industries_id' => $faker->randomElement($industryIds),
                    'companies_id' => $faker->randomElement($companyIds),
                    'type_jobs_id' => $faker->randomElement($typeJobIds),
                    'cities_id' => $faker->randomElement($cityIds),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // ✅ Insert 500 records sekaligus
            DB::table('job_postings')->insert($jobPostings);

            $progress = $i + $limit;
            $this->command->info("✅ Progress: {$progress} / {$totalRecords} job postings berhasil dibuat.");
        }

        $this->command->info('🎉 Seeder job postings selesai!  Total: ' . number_format($totalRecords) . ' records');
    }
}
