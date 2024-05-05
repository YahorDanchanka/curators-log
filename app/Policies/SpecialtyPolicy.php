<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;

class SpecialtyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('specialties.viewAny');
    }

    public function view(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.view');
    }

    public function create(User $user): bool
    {
        return $user->can('specialties.create');
    }

    public function update(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.edit');
    }

    public function delete(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.delete');
    }

    public function restore(User $user, Specialty $specialty): bool
    {
        return false;
    }

    public function forceDelete(User $user, Specialty $specialty): bool
    {
        return false;
    }
}
