<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class JobCandidateMatch extends Model
{
    use HasFactory;

    protected $table = 'job_candidate_matches';

    /**
     * ✅ FILLABLE: 6 kriteria + match_percentage
     */
    protected $fillable = [
        'candidates_id',
        'job_posting_id',
        'city_match',
        'type_job_match',
        'industry_match',
        'salary_match',
        'skill_match',
        'day_match',
        'match_percentage',
    ];

    /**
     * ✅ CASTS: Convert ke tipe data yang sesuai
     */
    protected $casts = [
        'city_match' => 'boolean',
        'type_job_match' => 'boolean',
        'industry_match' => 'boolean',
        'salary_match' => 'boolean',
        'skill_match' => 'boolean',
        'day_match' => 'boolean',
        'match_percentage' => 'decimal:2',
    ];

    /**
     * ✅ RELASI: Ke Candidates
     */
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }

    /**
     * ✅ RELASI: Ke JobPostings
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id');
    }

    /**
     * ✅ CALCULATE MATCH - 6 KRITERIA BINARY
     *
     * Menghitung kecocokan kandidat dengan job posting berdasarkan:
     * 1. City Match (Kota)
     * 2. Type Job Match (Tipe Pekerjaan)
     * 3. Industry Match (Industri)
     * 4. Salary Match (Gaji)
     * 5. Skill Match (Keahlian)
     * 6. Day Match (Hari Kerja)
     *
     * @param Candidates $candidate
     * @param JobPostings $job
     * @return array
     */

    public static function calculateMatch(Candidates $candidate, JobPostings $job): array
    {
        // ========================================
        // PHASE 1: FILTER/CLEANING - DAY MATCH WAJIB
        // ========================================
        $jobDays = $job->jobDatess->pluck('days_id')->filter()->unique()->toArray();
        $candidateDays = $candidate->preferredDays->pluck('id')->toArray();

        // ✅ JIKA JOB PUNYA JADWAL HARI
        if (!empty($jobDays)) {
            $commonDays = array_intersect($candidateDays, $jobDays);

            // ❌ TIDAK LOLOS FILTER - TIDAK ADA IRISAN HARI SAMA SEKALI
            if (empty($commonDays)) {
                Log::info('Candidate filtered out - No day match', [
                    'candidate_id' => $candidate->id,
                    'job_id' => $job->id,
                    'candidate_days' => $candidateDays,
                    'job_days' => $jobDays
                ]);

                // Return semua 0 karena tidak lolos filter
                return [
                    'city_match' => 0,
                    'type_job_match' => 0,
                    'industry_match' => 0,
                    'salary_match' => 0,
                    'skill_match' => 0,
                    'day_match' => 0,
                    'match_percentage' => 0,
                ];
            }

            Log::info('Candidate passed day filter', [
                'candidate_id' => $candidate->id,
                'job_id' => $job->id,
                'common_days' => $commonDays
            ]);
        }

        // ========================================
        // PHASE 2: CALCULATION - HITUNG 5 KRITERIA (JACCARD)
        // ========================================
        $totalRequirements = 0; // |L|
        $matchedRequirements = 0; // |K ∩ L|

        // Tracking per kriteria untuk fillable
        $cityMatch = 0;
        $typeJobMatch = 0;
        $industryMatch = 0;
        $salaryMatch = 0;
        $skillMatch = 0;
        $dayMatch = 0; // Tidak digunakan dalam perhitungan, hanya untuk record

        // ========================================
        // 1. CITY MATCH (1 requirement)
        // ========================================
        try {
            $totalRequirements++; // Job butuh 1 kota

            $candidateCities = $candidate->preferredCities->pluck('id')->toArray();
            if (in_array($job->cities_id, $candidateCities)) {
                $matchedRequirements++;
                $cityMatch = 1;
            }

            Log::debug('City Match Calculation', [
                'candidate_cities' => $candidateCities,
                'job_city' => $job->cities_id,
                'match' => $cityMatch,
                'total_req' => $totalRequirements,
                'matched_req' => $matchedRequirements
            ]);
        } catch (\Exception $e) {
            Log::error('Error in City Match: ' . $e->getMessage());
        }

        // ========================================
        // 2. TYPE JOB MATCH (1 requirement)
        // ========================================
        try {
            $totalRequirements++; // Job butuh 1 tipe pekerjaan

            $candidateTypeJobs = $candidate->preferredTypeJobs->pluck('id')->toArray();
            if (in_array($job->type_jobs_id, $candidateTypeJobs)) {
                $matchedRequirements++;
                $typeJobMatch = 1;
            }

            Log::debug('Type Job Match Calculation', [
                'candidate_type_jobs' => $candidateTypeJobs,
                'job_type_job' => $job->type_jobs_id,
                'match' => $typeJobMatch,
                'total_req' => $totalRequirements,
                'matched_req' => $matchedRequirements
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Type Job Match: ' . $e->getMessage());
        }

        // ========================================
        // 3. INDUSTRY MATCH (1 requirement)
        // ========================================
        try {
            $totalRequirements++; // Job butuh 1 industri

            $candidateIndustries = $candidate->preferredIndustries->pluck('id')->toArray();
            if (in_array($job->industries_id, $candidateIndustries)) {
                $matchedRequirements++;
                $industryMatch = 1;
            }

            Log::debug('Industry Match Calculation', [
                'candidate_industries' => $candidateIndustries,
                'job_industry' => $job->industries_id,
                'match' => $industryMatch,
                'total_req' => $totalRequirements,
                'matched_req' => $matchedRequirements
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Industry Match: ' . $e->getMessage());
        }

        // ========================================
        // 4. SALARY MATCH (1 requirement)
        // ========================================
        try {
            if ($job->salary) {
                $totalRequirements++; // Job punya requirement gaji

                if ($candidate->min_salary && $job->salary >= $candidate->min_salary) {
                    $matchedRequirements++;
                    $salaryMatch = 1;
                }

                Log::debug('Salary Match Calculation', [
                    'candidate_min_salary' => $candidate->min_salary,
                    'job_salary' => $job->salary,
                    'match' => $salaryMatch,
                    'total_req' => $totalRequirements,
                    'matched_req' => $matchedRequirements
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in Salary Match: ' . $e->getMessage());
        }

        // ========================================
        // 5. SKILL MATCH (N requirements - setiap skill dihitung)
        // ========================================
        try {
            $jobSkills = $job->skills->pluck('id')->toArray();
            $candidateSkills = $candidate->skills->pluck('id')->toArray();

            if (!empty($jobSkills)) {
                $totalRequirements += count($jobSkills); // Job butuh N skills

                $commonSkills = array_intersect($candidateSkills, $jobSkills);
                $matchedRequirements += count($commonSkills); // Berapa yang cocok

                // Untuk fillable: binary (ada irisan atau tidak)
                $skillMatch = count($commonSkills) > 0 ? 1 : 0;

                Log::debug('Skill Match Calculation', [
                    'candidate_skills' => $candidateSkills,
                    'job_skills' => $jobSkills,
                    'common_skills' => $commonSkills,
                    'job_skill_count' => count($jobSkills),
                    'matched_skill_count' => count($commonSkills),
                    'match' => $skillMatch,
                    'total_req' => $totalRequirements,
                    'matched_req' => $matchedRequirements
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in Skill Match: ' . $e->getMessage());
        }

        // ========================================
        // HITUNG PERSENTASE KECOCOKAN (JACCARD - 5 KRITERIA)
        // ========================================
        $matchPercentage = 0;
        if ($totalRequirements > 0) {
            $matchPercentage = ($matchedRequirements / $totalRequirements) * 100;
        }

        Log::info('Match Calculation Complete (With Day Filter)', [
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'passed_day_filter' => true,
            'total_requirements' => $totalRequirements, // |L| (5 kriteria)
            'matched_requirements' => $matchedRequirements, // |K ∩ L|
            'match_percentage' => round($matchPercentage, 2)
        ]);

        return [
            'city_match' => $cityMatch,
            'type_job_match' => $typeJobMatch,
            'industry_match' => $industryMatch,
            'salary_match' => $salaryMatch,
            'skill_match' => $skillMatch,
            'day_match' => 1, // Selalu 1 karena sudah lolos filter
            'match_percentage' => round($matchPercentage, 2),
        ];
    }

    /**
     * ✅ SIMPAN ATAU UPDATE MATCH KE DATABASE
     *
     * @param Candidates $candidate
     * @param JobPostings $job
     * @return self
     */
    public static function storeMatch(Candidates $candidate, JobPostings $job): self
    {
        $matchData = self::calculateMatch($candidate, $job);

        $match = self::updateOrCreate(
            [
                'candidates_id' => $candidate->id,
                'job_posting_id' => $job->id,
            ],
            $matchData
        );

        Log::info('Match Stored/Updated', [
            'match_id' => $match->id,
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'match_percentage' => $match->match_percentage
        ]);

        return $match;
    }

    /**
     * ✅ GET MATCH DATA TANPA SIMPAN
     * Digunakan untuk preview atau search tanpa menyimpan ke database
     *
     * @param Candidates $candidate
     * @param JobPostings $job
     * @return array
     */
    public static function getMatchData(Candidates $candidate, JobPostings $job): array
    {
        return self::calculateMatch($candidate, $job);
    }

    /**
     * ✅ GET EXISTING MATCH ATAU HITUNG BARU
     *
     * @param int $candidateId
     * @param int $jobPostingId
     * @return self|null
     */
    public static function getOrCalculate(int $candidateId, int $jobPostingId): ?self
    {
        // Cek apakah sudah ada match di database
        $match = self::where('candidates_id', $candidateId)
            ->where('job_posting_id', $jobPostingId)
            ->first();

        // Jika belum ada, hitung dan simpan
        if (!$match) {
            $candidate = Candidates::with([
                'skills',
                'preferredCities',
                'preferredTypeJobs',
                'preferredIndustries',
                'preferredDays'
            ])->find($candidateId);

            $job = JobPostings::with(['skills', 'jobDatess'])->find($jobPostingId);

            if ($candidate && $job) {
                $match = self::storeMatch($candidate, $job);
            }
        }

        return $match;
    }

    /**
     * ✅ HAPUS MATCH LAMA (CLEANUP)
     * Untuk maintenance, hapus match yang sudah lama tidak diupdate
     *
     * @param int $days Jumlah hari
     * @return int Jumlah record yang dihapus
     */
    public static function deleteOldMatches(int $days = 30): int
    {
        $deleted = self::where('updated_at', '<', now()->subDays($days))->delete();

        Log::info('Old Matches Deleted', [
            'days' => $days,
            'deleted_count' => $deleted
        ]);

        return $deleted;
    }

    /**
     * ✅ RECALCULATE SEMUA MATCH UNTUK KANDIDAT TERTENTU
     * Berguna saat kandidat update preferensi
     *
     * @param int $candidateId
     * @return int Jumlah match yang diupdate
     */
    public static function recalculateForCandidate(int $candidateId): int
    {
        $candidate = Candidates::with([
            'skills',
            'preferredCities',
            'preferredTypeJobs',
            'preferredIndustries',
            'preferredDays'
        ])->find($candidateId);

        if (!$candidate) {
            Log::warning('Candidate not found for recalculation', ['candidate_id' => $candidateId]);
            return 0;
        }

        // Ambil semua job yang pernah di-match
        $existingMatches = self::where('candidates_id', $candidateId)->get();
        $updated = 0;

        foreach ($existingMatches as $match) {
            $job = JobPostings::with(['skills', 'jobDatess'])->find($match->job_posting_id);
            if ($job) {
                self::storeMatch($candidate, $job);
                $updated++;
            }
        }

        Log::info('Matches Recalculated for Candidate', [
            'candidate_id' => $candidateId,
            'updated_count' => $updated
        ]);

        return $updated;
    }

    /**
     * ✅ RECALCULATE SEMUA MATCH UNTUK JOB TERTENTU
     * Berguna saat job posting diupdate
     *
     * @param int $jobPostingId
     * @return int Jumlah match yang diupdate
     */
    public static function recalculateForJob(int $jobPostingId): int
    {
        $job = JobPostings::with(['skills', 'jobDatess'])->find($jobPostingId);

        if (!$job) {
            Log::warning('Job not found for recalculation', ['job_id' => $jobPostingId]);
            return 0;
        }

        // Ambil semua kandidat yang pernah di-match
        $existingMatches = self::where('job_posting_id', $jobPostingId)->get();
        $updated = 0;

        foreach ($existingMatches as $match) {
            $candidate = Candidates::with([
                'skills',
                'preferredCities',
                'preferredTypeJobs',
                'preferredIndustries',
                'preferredDays'
            ])->find($match->candidates_id);

            if ($candidate) {
                self::storeMatch($candidate, $job);
                $updated++;
            }
        }

        Log::info('Matches Recalculated for Job', [
            'job_id' => $jobPostingId,
            'updated_count' => $updated
        ]);

        return $updated;
    }

    /**
     * ✅ GET TOP MATCHES UNTUK KANDIDAT
     *
     * @param int $candidateId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopMatchesForCandidate(int $candidateId, int $limit = 10)
    {
        return self::with(['jobPosting.company', 'jobPosting.city', 'jobPosting.typeJobs'])
            ->where('candidates_id', $candidateId)
            ->orderBy('match_percentage', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * ✅ GET TOP CANDIDATES UNTUK JOB
     *
     * @param int $jobPostingId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopCandidatesForJob(int $jobPostingId, int $limit = 10)
    {
        return self::with(['candidate.user'])
            ->where('job_posting_id', $jobPostingId)
            ->orderBy('match_percentage', 'desc')
            ->limit($limit)
            ->get();
    }
}
