<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoOffensiveContent implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $words = config('offensive_words.words', []);
        $normalized = mb_strtolower((string) $value);

        foreach ($words as $word) {
            if (str_contains($normalized, mb_strtolower($word))) {
                $fail('Tu comentario incumple con nuestras políticas');
                return;
            }
        }
    }
}