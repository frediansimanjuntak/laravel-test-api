<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Notifications\NewUserAdminNotification;
use App\Notifications\NewUserWelcomeNotification;
use App\DTOs\UserData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepository $repo) {}

    public function getAll(array $filters): LengthAwarePaginator
    {
        return $this->repo->allActiveOnly($filters);
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }
    
    public function create(array $data)
    {
        $dto = UserData::fromArray($data);
        $user = $this->repo->create($dto);
        
        $this->notifyAdministrators($user);
        $user->notify(new NewUserWelcomeNotification());

        return $user;
    }

    public function update(User $actor, User $target, array $data)
    {        
        $permittedData = $this->filterToPermittedFields($actor, $data);
       
        if (!empty($permittedData['password'])) {
            $permittedData['password'] = Hash::make($permittedData['password']);
        }        
        $user = $this->repo->update($target->id, $permittedData);
        
        return $user->fresh(); // Return the updated user instance
    }

    public function delete(User $user)
    {
        $this->repo->delete($user);
        return true;
    }
    
    private function filterToPermittedFields(User $actor, array $data): array
    {
        $permitted = $actor->role->editableFields();

        return array_intersect_key($data, array_flip($permitted));
    }

    private function notifyAdministrators($user)
    {
        $administrators = $this->repo->all()->filter(fn($u) => $u->role === 'administrator');
        foreach ($administrators as $admin) {
            $admin->notify(new NewUserAdminNotification($user));
        }
    }
}