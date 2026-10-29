<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ServiceCategory extends Model
{
    use HasSortOrder, HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id')->orderBy('sort');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort');
    }
}
