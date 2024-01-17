<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Course;
use App\Models\Curator;
use App\Models\Group;
use App\Models\Specialty;
use App\Services\GroupService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['courses' => fn(HasMany $query) => $query->with('curator')])
            ->latest()
            ->get();

        $groups->each(function (Group $group) {
            $group->append('current_course', 'name');
            $group->courses->each(fn(Course $course) => $course->curator->append('full_name'));
        });

        return Inertia::render('group/IndexPage', compact('groups'));
    }

    public function create()
    {
        extract($this->getFormData());
        return Inertia::render('group/CreatePage', compact('specialties', 'curators'));
    }

    public function store(GroupRequest $request, GroupService $groupService)
    {
        $groupService->create($request);
        return to_route('groups.index');
    }

    public function edit(Group $group)
    {
        $group->load('courses');
        $group->append('name');
        extract($this->getFormData());
        return Inertia::render('group/EditPage', compact('group', 'specialties', 'curators'));
    }

    public function update(GroupRequest $request, Group $group, GroupService $groupService)
    {
        $groupService->update($group, $request);
        return to_route('groups.index');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return to_route('groups.index');
    }

    protected function getFormData(): array
    {
        $specialties = Specialty::select(['id', 'name'])->get();
        $curators = Curator::select(['id', 'surname', 'name', 'patronymic'])
            ->orderBy('surname')
            ->orderBy('name')
            ->orderBy('patronymic')
            ->get();
        $curators->each(fn(Curator $curator) => $curator->append('full_name'));
        return compact('specialties', 'curators');
    }
}
