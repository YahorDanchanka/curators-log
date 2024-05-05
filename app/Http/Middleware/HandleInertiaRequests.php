<?php

namespace App\Http\Middleware;

use App\Enums\CharacteristicId;
use App\Models\AdministrativeDivision;
use App\Models\Curator;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Illuminate\Support\Facades\Gate;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $administrativeDivisions = Cache::rememberForever(
            'administrativeDivisions',
            fn() => AdministrativeDivision::all()
        );

        return array_merge(parent::share($request), [
            'enums' => [
                'CharacteristicId' => CharacteristicId::array(),
            ],
            'administrativeDivisions' => $administrativeDivisions,
            'auth.user' => $request->user(),
            'auth.permissions.specialties.viewAny' => Gate::allows('viewAny', Specialty::class),
            'auth.permissions.specialties.create' => Gate::allows('create', Specialty::class),
            'auth.permissions.curators.viewAny' => Gate::allows('viewAny', Curator::class),
            'auth.permissions.curators.create' => Gate::allows('create', Curator::class),
        ]);
    }
}
