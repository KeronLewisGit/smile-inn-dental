<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'quote', 'treatment', 'rating', 'source', 'video_url', 'featured', 'active', 'sort'])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return ['featured' => 'boolean', 'active' => 'boolean'];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true)->orderBy('sort');
    }
}
