<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('groups.viewAny');
    }

    public function view(User $user, Group $group): bool
    {
        return $user->can('groups.viewOwn') && $this->isOwner($user, $group);
    }

    public function create(User $user): bool
    {
        return $user->can('groups.create');
    }

    public function update(User $user, Group $group): bool
    {
        return $user->can('groups.editOwn') && $this->isOwner($user, $group);
    }

    public function delete(User $user, Group $group): bool
    {
        return $user->can('groups.deleteOwn') && $this->isOwner($user, $group);
    }

    public function restore(User $user, Group $group): bool
    {
        return false;
    }

    public function forceDelete(User $user, Group $group): bool
    {
        return false;
    }

    protected function isOwner(User $user, Group $group): bool
    {
        return $user->curator &&
            $group
                ->courses()
                ->where('curator_id', $user->curator->id)
                ->exists();
    }
}
