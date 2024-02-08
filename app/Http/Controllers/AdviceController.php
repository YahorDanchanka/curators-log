<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdviceRequest;
use App\Models\Advice;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Carbon;

class AdviceController extends Controller
{
    public function index(Group $group)
    {
        $group->load(['advice' => fn(HasMany $query) => $query->orderBy('date')]);
        return Inertia::render('advice/IndexPage', [...compact('group'), 'printing' => true]);
    }

    public function create(Group $group)
    {
        return Inertia::render('advice/CreatePage', compact('group'));
    }

    public function store(AdviceRequest $request, Group $group)
    {
        $group->advice()->create($request->validated());
        return to_route('groups.advice.index', ['group' => $group->id]);
    }

    public function edit(Group $group, Advice $advice)
    {
        return Inertia::render('advice/EditPage', compact('group', 'advice'));
    }

    public function update(AdviceRequest $request, Group $group, Advice $advice)
    {
        $advice->update($request->validated());
        return to_route('groups.advice.index', ['group' => $group->id]);
    }

    public function destroy(Group $group, Advice $advice)
    {
        $advice->delete();
        return to_route('groups.advice.index', ['group' => $group->id]);
    }

    public function print(Group $group)
    {
        $templateProcessor = new TemplateProcessor(resource_path('documents/advice.docx'));

        $templateProcessor->cloneRowAndSetValues(
            'date',
            $group
                ->advice()
                ->orderBy('date')
                ->get()
                ->map(
                    fn(Advice $advice) => [
                        ...$advice->getAttributes(),
                        'date' => Carbon::parse($advice->date)->format('d.m.Y'),
                    ]
                )
                ->values()
                ->toArray()
        );

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            'Замечания и предложения по организации идеологической и воспитательной работы.docx'
        );
    }
}
