<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['patient_id', 'appointment_type_id', 'team_member_id', 'starts_at', 'ends_at', 'status', 'patient_notes', 'staff_notes', 'source', 'confirmed_at', 'cancelled_at', 'cancellation_reason', 'created_by'])]
class Appointment extends Model
{
    public const STATUSES = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No show'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'confirmed_at' => 'datetime', 'cancelled_at' => 'datetime', 'reminded_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (Appointment $a) {
            $a->reference ??= 'SI-'.now()->format('ym').'-'.strtoupper(Str::random(4));
            $a->manage_token ??= Str::random(48);
        });
    }

    /** @return BelongsTo<Patient, $this> */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /** @return BelongsTo<AppointmentType, $this> */
    public function type(): BelongsTo
    {
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    /** @return BelongsTo<TeamMember, $this> */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->whereIn('status', ['pending', 'confirmed'])->where('starts_at', '>=', now())->orderBy('starts_at');
    }

    public function scopeOnDate(Builder $q, \DateTimeInterface $date): Builder
    {
        return $q->whereDate('starts_at', $date->format('Y-m-d'));
    }

    public function scopeBlocking(Builder $q): Builder
    {
        return $q->whereIn('status', ['pending', 'confirmed']);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function statusName(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function manageUrl(): string
    {
        return route('book.manage', $this->manage_token);
    }
}
