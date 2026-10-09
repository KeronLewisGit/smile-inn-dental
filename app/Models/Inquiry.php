<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['patient_id', 'name', 'email', 'phone', 'service', 'gender', 'message', 'status', 'assigned_to', 'staff_notes'])]
class Inquiry extends Model
{
    public const STATUSES = ['new' => 'New', 'contacted' => 'Contacted', 'booked' => 'Booked', 'closed' => 'Closed'];

    /** @return BelongsTo<Patient, $this> */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /** @return BelongsTo<User, $this> */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusName(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
