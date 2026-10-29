<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PostCategory extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $guarded = [];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
