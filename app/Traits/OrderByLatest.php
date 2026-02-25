<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait OrderByLatest
{
    protected static function bootOrderByLatest()
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $table = (new static)->getTable();

            if (Schema::hasColumn($table, 'created_at')) {
                $builder->orderBy('created_at', 'desc');
            } elseif (Schema::hasColumn($table, 'id')) {
                $builder->orderBy('id', 'desc');
            }
        });
    }
}
