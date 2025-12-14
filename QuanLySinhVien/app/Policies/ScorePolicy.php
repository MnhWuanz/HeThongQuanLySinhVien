<?php

namespace App\Policies;

use App\Models\Score;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Score $score): bool
    {
        return true; // Everyone can view details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Teacher KHÔNG được tạo điểm mới
        return !$user->hasRole('Teacher');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Score $score): bool
    {
        // Teacher chỉ được cập nhật điểm
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Score $score): bool
    {
        // Teacher KHÔNG được xóa điểm
        return !$user->hasRole('Teacher');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Score $score): bool
    {
        return !$user->hasRole('Teacher');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Score $score): bool
    {
        return !$user->hasRole('Teacher');
    }
}
