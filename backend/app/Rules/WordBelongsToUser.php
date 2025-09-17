<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;

class WordBelongsToUser implements Rule
{
    public function passes($attribute, $value)
    {
        if (!Auth::check()) {
            return false;
        }

        return Word::where('id', $value)
                  ->whereHas('subject', function($query) {
                      $query->where('user_id', Auth::id());
                  })
                  ->exists();
    }

    public function message()
    {
        return 'The word does not exist or you do not have access to it.';
    }
}
