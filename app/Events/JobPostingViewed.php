<?php

namespace App\Events;

use App\Models\JobPostings;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobPostingViewed
{
    use Dispatchable, SerializesModels;

    public $jobPosting;

    public function __construct(JobPostings $jobPosting)
    {
        $this->jobPosting = $jobPosting;
    }
}
