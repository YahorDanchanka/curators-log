<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('students.viewAny');
    }

    public function view(User $user, Student $student): bool
    {
        return $user->can('students.viewOwn');
    }

    public function create(User $user): bool
    {
        return $user->can('students.create');
    }

    public function update(User $user, Student $student): bool
    {
        return Gate::allows('update', $student->group);
    }

    public function delete(User $user, Student $student): bool
    {
        return Gate::allows('update', $student->group);
    }

    public function achievements(User $user): bool
    {
        return $user->can('students.achievements');
    }

    public function asocialBehaviors(User $user): bool
    {
        return $user->can('students.asocialBehaviors');
    }

    public function expertAdvice(User $user): bool
    {
        return $user->can('students.expertAdvice');
    }

    public function individualWorks(User $user): bool
    {
        return $user->can('students.individualWorks');
    }

    public function restore(User $user, Student $student): bool
    {
        return false;
    }

    public function forceDelete(User $user, Student $student): bool
    {
        return false;
    }
}
