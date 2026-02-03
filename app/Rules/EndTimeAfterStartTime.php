<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EndTimeAfterStartTime implements ValidationRule
{
    protected $startTime;

    public function __construct($startTime)
    {
        $this->startTime = $startTime;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtotime($value) <= strtotime($this->startTime)) {
            $fail('Jam selesai harus lebih besar dari jam mulai.');
        }
    }
}
