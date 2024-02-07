<?php

namespace App\Http\Controllers;

use App\Http\Requests\InteractionWithParentRequest;
use App\Models\Group;
use App\Models\InteractionWithParent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class InteractionWithParentController extends Controller
{
    public function index(Group $group)
    {
        $group->load(['interactionWithParents' => fn(HasMany $query) => $query->orderBy('date')]);
        return Inertia::render('interaction-with-parent/IndexPage', compact('group'));
    }

    public function create(Group $group)
    {
        $group->load('courses');
        $group->append(['first_course', 'last_course']);
        return Inertia::render('interaction-with-parent/CreatePage', compact('group'));
    }

    public function store(InteractionWithParentRequest $request, Group $group)
    {
        $group->interactionWithParents()->create($request->validated());
        return to_route('groups.interaction-with-parents.index', ['group' => $group->id]);
    }

    public function edit(Group $group, InteractionWithParent $interactionWithParent)
    {
        $group->load('courses');
        $group->append(['first_course', 'last_course']);
        return Inertia::render('interaction-with-parent/EditPage', compact('group', 'interactionWithParent'));
    }

    public function update(
        InteractionWithParentRequest $request,
        Group $group,
        InteractionWithParent $interactionWithParent
    ) {
        $interactionWithParent->update($request->validated());
        return to_route('groups.interaction-with-parents.index', ['group' => $group->id]);
    }

    public function destroy(Group $group, InteractionWithParent $interactionWithParent)
    {
        $interactionWithParent->delete();
        return to_route('groups.interaction-with-parents.index', ['group' => $group->id]);
    }
}
