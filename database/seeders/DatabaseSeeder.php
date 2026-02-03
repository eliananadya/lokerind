<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all the seeders in the correct order

        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(IndustriesSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CandidatesSeeder::class);
        $this->call(TypeJobsSeeder::class);
        $this->call(PrefferedTypeJobsSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(PrefferedCitiesSeeder::class);
        $this->call(SubscribesSeeder::class);
        $this->call(JobPostingsSeeder::class);
        $this->call(SkillsSeeder::class);
        $this->call(CandidatesSkillsSeeder::class);
        $this->call(BenefitSeeder::class);
        $this->call(JobPostingBenefitSeeder::class);
        $this->call(DaysSeeder::class);
        $this->call(JobDatesSeeder::class);
        $this->call(PrefferedDaysSeeder::class);
        $this->call(PrefferedIndustriesSeeder::class);
        $this->call(SaveJobsSeeder::class);
        $this->call(PortofoliosSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(FeedbackApllicationSeeder::class);
        // 
        $this->call(ReportsSeeder::class);
        $this->call(BlacklistSeeder::class);
        $this->call(JobPostingSkillsSeeder::class);
        $this->call(HistoryPointSeeder::class);
    }
}
