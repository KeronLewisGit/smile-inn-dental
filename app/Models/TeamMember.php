<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string|null $bio
 * @property string|null $photo
 * @property string|null $instagram
 * @property bool $is_dentist
 * @property bool $accepts_bookings
 * @property bool $active
 * @property int $sort
 * @property-read Collection<int, Appointment> $appointments
 */
#[Fillable(['name', 'slug', 'title', 'bio', 'photo', 'instagram', 'is_dentist', 'accepts_bookings', 'active', 'sort'])]
class TeamMember extends Model
{
    protected function casts(): array
    {
        return ['is_dentist' => 'boolean', 'accepts_bookings' => 'boolean', 'active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(fn (TeamMember $m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return HasMany<Appointment, $this> */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * @param  Builder<TeamMember>  $q
     * @return Builder<TeamMember>
     */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true)->orderBy('sort');
    }

    public function firstName(): string
    {
        return Str::of($this->name)->replace('Dr. ', '')->before(' ')->toString();
    }

    public function photoUrl(): string
    {
        return $this->photo ? asset($this->photo) : asset('images/logo-gold.png');
    }
}
