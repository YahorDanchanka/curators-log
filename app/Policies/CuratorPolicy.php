<?php

namespace App\Policies;

use App\Models\Curator;
use App\Models\User;

class CuratorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('curators.viewAny');
    }

    public function view(User $user, Curator $curator): bool
    {
        return $user->can('curators.viewOwn') && $curator->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('curators.create');
    }

    public function update(User $user, Curator $curator): bool
    {
        return $user->can('curators.editOwn') && $curator->user_id === $user->id;
    }

    public function delete(User $user, Curator $curator): bool
    {
        return $user->can('curators.deleteOwn') && $curator->user_id === $user->id;
    }

    public function restore(User $user, Curator $curator): bool
    {
        return false;
    }

    public function forceDelete(User $user, Curator $curator): bool
    {
        return false;
    }
}
