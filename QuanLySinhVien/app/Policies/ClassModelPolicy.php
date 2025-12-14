<?php

namespace App\Policies;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassModelPolicy
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
    public function view(User $user, ClassModel $classModel): bool
    {
        return true; // Everyone can view details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Teacher có thể tạo lớp
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassModel $classModel): bool
    {
        // Teacher có thể cập nhật lớp
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassModel $classModel): bool
    {
        // Teacher KHÔNG được xóa lớp
        return !$user->hasRole('Teacher');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassModel $classModel): bool
    {
        return !$user->hasRole('Teacher');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassModel $classModel): bool
    {
        return !$user->hasRole('Teacher');
    }
}
