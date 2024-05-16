<?php

namespace App\Http\Controllers;

use App\Http\Requests\InteractionWithParentRequest;
use App\Models\Group;
use App\Models\InteractionWithParent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;

class InteractionWithParentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Group::class, 'group');
    }

    public function index(Group $group)
    {
        $group->append('name');
        $group->load(['interactionWithParents' => fn(HasMany $query) => $query->orderBy('date')]);
        return Inertia::render('interaction-with-parent/IndexPage', [...compact('group'), 'printing' => true]);
    }

    public function create(Group $group)
    {
        $group->load('courses');
        $group->append(['first_course', 'last_course', 'name']);
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
        $group->append(['first_course', 'last_course', 'name']);
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

    public function print(Group $group)
    {
        Gate::authorize('view', $group);
        $templateProcessor = new TemplateProcessor(resource_path('documents/interaction-with-parents.docx'));

        $templateProcessor->cloneRowAndSetValues(
            'date',
            $group
                ->interactionWithParents()
                ->orderBy('date')
                ->get()
                ->map(
                    fn(InteractionWithParent $interactionWithParent) => [
                        ...$interactionWithParent->getAttributes(),
                        'date' => Carbon::parse($interactionWithParent->date)->format('d.m.Y'),
                    ]
                )
                ->values()
                ->toArray()
        );

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            'Содержание взаимодействия с родителями.docx'
        );
    }
}
