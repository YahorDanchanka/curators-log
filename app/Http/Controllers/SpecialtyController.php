<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Specialty::class, 'specialty');
    }

    public function index()
    {
        $specialties = Specialty::all();
        $specialties->each->append('can');
        return Inertia::render('specialty/IndexPage', compact('specialties'));
    }

    public function create()
    {
        return Inertia::render('specialty/CreatePage');
    }

    public function store(SpecialtyRequest $request)
    {
        Specialty::create($request->validated());
        return to_route('specialties.index');
    }

    public function edit(Specialty $specialty)
    {
        return Inertia::render('specialty/EditPage', compact('specialty'));
    }

    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->validated());
        return to_route('specialties.index');
    }

    public function destroy(Specialty $specialty)
    {
        if ($specialty->groups()->exists()) {
            throw ValidationException::withMessages(['Специальность связана с группами.']);
        }

        $specialty->delete();
        return to_route('specialties.index');
    }
}
