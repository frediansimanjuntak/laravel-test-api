<?php

namespace App\Repositories;

use App\Models\User;
use App\Enums\UserRole;
use App\DTOs\UserData;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    private const ALLOWED_SORT_COLUMNS = ['name', 'email', 'created_at'];
    private const DEFAULT_PER_PAGE     = 15;
    private const MAX_PER_PAGE         = 100;

    public function all()
    {
        return User::all();
    }

    public function allActiveOnly(array $filters): LengthAwarePaginator
    {
        $query = User::query()->where('is_active', true)->withCount('orders');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortBy  = in_array($filters['sort_by'] ?? null, self::ALLOWED_SORT_COLUMNS, true)
                   ? $filters['sort_by']
                   : 'created_at';

        $sortDir = ($filters['sort_dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $perPage = min(
            (int) ($filters['per_page'] ?? self::DEFAULT_PER_PAGE),
            self::MAX_PER_PAGE
        );

        $res = $query->orderBy($sortBy, $sortDir)->paginate($perPage);
        return $res;
    }

    public function find($id)
    {
        $user = User::findOrFail($id);
        $user->loadCount('orders');
        return $user;
    }

    public function create(UserData $data)
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
            'role' => $data->role ?? UserRole::User->value,
            'is_active' => $data->is_active,
        ]);
    }

    public function update($id, UserData $data)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
            'role' => $data->role,
            'is_active' => $data->is_active,
        ]);
        $user->loadCount('orders');
        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}