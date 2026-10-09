<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $quote
 * @property string|null $treatment
 * @property int $rating
 * @property string|null $source
 * @property string|null $video_url
 * @property bool $featured
 * @property bool $active
 * @property int $sort
 */
#[Fillable(['name', 'quote', 'treatment', 'rating', 'source', 'video_url', 'featured', 'active', 'sort'])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return ['featured' => 'boolean', 'active' => 'boolean'];
    }

    /**
     * @param  Builder<Testimonial>  $q
     * @return Builder<Testimonial>
     */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true)->orderBy('sort');
    }
}
