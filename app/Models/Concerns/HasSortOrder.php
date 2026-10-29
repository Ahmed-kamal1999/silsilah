<?php

namespace App\Models\Concerns;

trait HasSortOrder
{
    protected static function bootHasSortOrder(): void
    {
        static::creating(function ($model) {
            if (empty($model->sort)) {
                $model->sort = ((int) static::max('sort')) + 1;
            }
        });
    }
}
