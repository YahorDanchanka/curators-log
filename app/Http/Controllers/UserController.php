<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        $users = User::with('roles')->get();

        $users->each(
            fn(User $user) => ($user->can = [
                'view' => Gate::allows('view', $user),
                'update' => Gate::allows('update', $user),
                'delete' => Gate::allows('delete', $user),
            ])
        );

        return Inertia::render('user/IndexPage', compact('users'));
    }

    public function create()
    {
        return Inertia::render('user/CreatePage');
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([...$validated, 'password' => Hash::make($validated['password'])]);
            $user->syncRoles([$validated['role']]);
        });

        return to_route('users.index');
    }

    public function edit(User $user)
    {
        $user->load('roles');
        return Inertia::render('user/EditPage', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated) {
            if (isset($validated['password']) && $validated['password']) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);
            $user->syncRoles([$validated['role']]);
        });

        return to_route('users.index');
    }

    public function destroy(User $user)
    {
        if ($user->curator()->exists()) {
            throw ValidationException::withMessages(['Удалите куратора через соответствующую форму.']);
        }

        $adminCount = User::with('roles')
            ->get()
            ->filter(fn($user) => $user->roles->where('name', 'admin')->toArray())
            ->count();

        if ($user->hasRole('admin') && $adminCount <= 1) {
            throw ValidationException::withMessages(['Невозможно удалить последнего администратора.']);
        }

        $user->delete();
        return to_route('users.index');
    }
}
