<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Administrator, UserRole::Manager]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || in_array($user->role, [UserRole::Administrator, UserRole::Manager]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?UserRole $targetRole = null): bool
    {        
        // Admin can create users of any role
        if ($user->role === UserRole::Administrator) {
            return true;
        }

        // Manager can only create regular users
        if ($user->role === UserRole::Manager) {
            return $targetRole === UserRole::User;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return match($user->role) {
            UserRole::Administrator => true,
            UserRole::Manager       => $user->id === $model->id
                                       || $model->role === UserRole::User,
            UserRole::User          => $user->id === $model->id,
        };
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return match($user->role) {
            UserRole::Administrator => true,
            UserRole::Manager       => $user->id === $model->id
                                       || $model->role === UserRole::User,
            UserRole::User          => false,
        };
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
