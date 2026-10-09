<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property Carbon|null $unsubscribed_at
 * @property Carbon|null $created_at
 */
#[Fillable(['email', 'name', 'unsubscribed_at'])]
class Subscriber extends Model
{
    protected function casts(): array
    {
        return ['unsubscribed_at' => 'datetime'];
    }
}
