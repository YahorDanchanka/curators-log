<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCuratorRequest;
use App\Http\Requests\UpdateCuratorRequest;
use App\Models\Curator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CuratorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Curator::class, 'curator');
    }

    public function index()
    {
        $query = Curator::with('user');
        $curators = auth()->user()->is_admin ? $query->get() : $query->where('user_id', auth()->id())->get();
        $curators->each->append('can');
        return Inertia::render('curator/IndexPage', compact('curators'));
    }

    public function create()
    {
        return Inertia::render('curator/CreatePage');
    }

    public function store(StoreCuratorRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([...$validated, 'password' => Hash::make($validated['password'])]);
            $user->curator()->create($validated);
            $user->assignRole('curator');
        });

        return to_route('curators.index');
    }

    public function edit(Curator $curator)
    {
        $curator->load('user');
        return Inertia::render('curator/EditPage', compact('curator'));
    }

    public function update(UpdateCuratorRequest $request, Curator $curator)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($curator, $validated) {
            if (isset($validated['password']) && $validated['password']) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $curator->user->update($validated);
            $curator->update($validated);
        });

        return to_route('curators.index');
    }

    public function destroy(Curator $curator)
    {
        try {
            DB::transaction(function () use ($curator) {
                $user = $curator->user;
                $curator->delete();

                if (auth()->id() !== $user->id) {
                    $user->delete();
                }
            });
        } catch (\Exception) {
            throw ValidationException::withMessages(['Куратор связан с другими записями.']);
        }

        return to_route('curators.index');
    }
}
