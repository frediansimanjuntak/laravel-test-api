<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Enums\UserRole;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index(IndexUserRequest $request)
    {
        $req = $request->validated();
        Gate::authorize('viewAny', User::class);

        $users = $this->service->getAll($req)
                                ->appends($request->query());

        return ApiResponse::success(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $req = $request->validated();
        $dataRole = UserRole::from($req['role'] ?? 'user');
        Gate::authorize('create', [User::class, $dataRole]);

        $user = $this->service->create($req);

        return ApiResponse::created(new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->service->getById($id);
        Gate::authorize('view', $user);

        return ApiResponse::success(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $user = $this->service->update($request->user(), $user, $request->validated());
        return ApiResponse::success(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $this->service->delete($user);
        return ApiResponse::success([]);
    }
}
