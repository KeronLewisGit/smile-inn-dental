<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'tagline', 'intro', 'body', 'icon', 'image', 'treatments', 'faqs', 'featured', 'active', 'sort'])]
class Service extends Model
{
    protected function casts(): array
    {
        return ['treatments' => 'array', 'faqs' => 'array', 'featured' => 'boolean', 'active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(fn (Service $s) => $s->slug = $s->slug ?: Str::slug($s->name));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true)->orderBy('sort');
    }
}
