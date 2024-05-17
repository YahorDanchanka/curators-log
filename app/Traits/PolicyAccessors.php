<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

trait PolicyAccessors
{
    protected function can(): Attribute
    {
        return Attribute::make(
            get: fn() => ['view' => $this->can_view, 'edit' => $this->can_edit, 'delete' => $this->can_delete]
        );
    }

    protected function canView(): Attribute
    {
        return Attribute::make(get: fn() => Gate::allows('view', $this));
    }

    protected function canEdit(): Attribute
    {
        return Attribute::make(get: fn() => Gate::allows('update', $this));
    }

    protected function canDelete(): Attribute
    {
        return Attribute::make(get: fn() => Gate::allows('delete', $this));
    }
}
