<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $patient_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $service
 * @property string|null $gender
 * @property string|null $message
 * @property string $status
 * @property int|null $assigned_to
 * @property string|null $staff_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Patient|null $patient
 * @property-read User|null $assignee
 */
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
