<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TenantScoped
{
    protected static function bootTenantScoped()
    {
        
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user() instanceof \App\Models\HouseOwner) {
                $builder->where('house_owner_id', Auth::user()->id);
            }
        });

        
        static::creating(function (Model $model) {
            if (Auth::check() && Auth::user() instanceof \App\Models\HouseOwner) {
                if (!$model->house_owner_id) {
                    $model->house_owner_id = Auth::user()->id;
                }
            }
        });
    }
}