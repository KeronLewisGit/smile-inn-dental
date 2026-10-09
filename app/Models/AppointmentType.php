<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $duration_minutes
 * @property bool $is_free
 * @property bool $is_virtual
 * @property int|null $team_member_id
 * @property bool $active
 * @property int $sort
 * @property-read TeamMember|null $provider
 */
#[Fillable(['name', 'slug', 'description', 'duration_minutes', 'is_free', 'is_virtual', 'team_member_id', 'active', 'sort'])]
class AppointmentType extends Model
{
    protected function casts(): array
    {
        return ['is_free' => 'boolean', 'is_virtual' => 'boolean', 'active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(fn (AppointmentType $t) => $t->slug = $t->slug ?: Str::slug($t->name));
    }

    /** @return BelongsTo<TeamMember, $this> */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    /**
     * @param  Builder<AppointmentType>  $q
     * @return Builder<AppointmentType>
     */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true)->orderBy('sort');
    }
}
