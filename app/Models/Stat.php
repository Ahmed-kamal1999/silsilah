<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Stat extends Model
{
    use HasSortOrder, HasTranslations;

    public array $translatable = ['label'];

    protected $guarded = [];
}
