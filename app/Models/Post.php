<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'category', 'excerpt', 'body', 'cover_image', 'published_at', 'author_id', 'featured'])]
class Post extends Model
{
    public const CATEGORIES = ['Education', 'Invisalign', 'Cosmetic', 'Launches'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'featured' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(fn (Post $p) => $p->slug = $p->slug ?: Str::slug($p->title));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now())->orderByDesc('published_at');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }

    public function coverUrl(): string
    {
        return $this->cover_image ? asset($this->cover_image) : asset('images/clinic/lounge.webp');
    }
}
