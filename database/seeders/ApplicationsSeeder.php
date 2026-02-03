<?php

namespace Database\Seeders;

use App\Models\Applications;
use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\JobPostings;
use App\Models\Companies;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ✅ Cek apakah data master sudah ada
        if (Candidates::count() === 0) {
            $this->command->warn('⚠️ Tidak ada data candidates. Silakan jalankan CandidateSeeder terlebih dahulu.');
            return;
        }

        if (JobPostings::count() === 0) {
            $this->command->warn('⚠️ Tidak ada data job postings. Silakan jalankan JobPostingsSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('🚀 Membuat 200 data applications...');

        $candidates = Candidates::all();
        $jobPostings = JobPostings::with('company')->where('status', 'Open')->get();

        if ($jobPostings->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada job posting dengan status "Open".');
            return;
        }

        $statuses = [
            'Applied' => 40,      // 40%
            'Reviewed' => 20,     // 20%
            'Interview' => 15,    // 15%
            'Accepted' => 10,     // 10%
            'Rejected' => 10,     // 10%
            'Withdrawn' => 3,     // 3%
            'Finished' => 2,      // 2%
        ];

        $createdCount = 0;

        foreach (range(1, 100) as $index) {
            $candidate = $candidates->random();
            $jobPosting = $jobPostings->random();
            $company = $jobPosting->company;

            // ✅ Cek duplikasi (1 kandidat tidak bisa melamar 2x ke job yang sama)
            $exists = Applications::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $jobPosting->id)
                ->exists();

            if ($exists) {
                continue; // Skip jika sudah ada
            }

            // ✅ Pilih status berdasarkan probabilitas
            $status = $this->getWeightedRandomStatus($statuses);

            // ✅ Generate tanggal applied
            $appliedAt = $faker->dateTimeBetween('-60 days', 'now');

            // ✅ Data untuk invited (jika status invited)
            $invitedByCompany = null;
            $invitedAt = null;

            if ($faker->boolean(30)) { // 30% chance diundang oleh company
                $invitedByCompany = $company->id;
                $invitedAt = $faker->dateTimeBetween('-90 days', $appliedAt);
            }

            // ✅ Rating dan review hanya jika status Finished atau Rejected
            $ratingCandidate = null;
            $ratingCompany = null;
            $reviewCandidate = null;
            $reviewCompany = null;

            if (in_array($status, ['Finished', 'Rejected'])) {
                $ratingCandidate = $faker->numberBetween(1, 5);
                $ratingCompany = $faker->numberBetween(1, 5);
                $reviewCandidate = $faker->optional(0.7)->paragraph(); // 70% ada review
                $reviewCompany = $faker->optional(0.7)->paragraph();
            }

            // ✅ Message (cover letter)
            $message = $faker->optional(0.8)->paragraphs(2, true); // 80% ada message

            Applications::create([
                'candidates_id' => $candidate->id,
                'job_posting_id' => $jobPosting->id,
                'status' => $status,
                'message' => $message,
                'applied_at' => $appliedAt,
                'rating_candidates' => $ratingCandidate,
                'rating_company' => $ratingCompany,
                'review_candidate' => $reviewCandidate,
                'review_company' => $reviewCompany,
                'invited_by_company' => $invitedByCompany,
                'invited_at' => $invitedAt,
                'created_at' => $appliedAt,
                'updated_at' => $appliedAt,
            ]);

            $createdCount++;

            if ($createdCount % 50 === 0) {
                $this->command->info("✅ {$createdCount}/200 applications berhasil dibuat.");
            }
        }

        $this->command->info("✅ Total {$createdCount} applications berhasil dibuat!");
    }

    /**
     * Get weighted random status based on probability
     */
    private function getWeightedRandomStatus(array $statuses): string
    {
        $rand = mt_rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'Applied'; // Default fallback
    }
}
