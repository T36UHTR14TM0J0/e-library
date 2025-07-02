<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    public function update(User $user, Review $review)
    {
        return $user->id === $review->user_id
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengedit komentar ini');
    }

    public function delete(User $user, Review $review)
    {
        return $user->id === $review->user_id || $user->isAdmin()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk menghapus komentar ini');
    }
}