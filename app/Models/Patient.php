<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'source', 'notes', 'marketing_opt_in'])]
class Patient extends Model
{
    protected function casts(): array
    {
        return ['date_of_birth' => 'date', 'marketing_opt_in' => 'boolean'];
    }

    /** @return HasMany<Appointment, $this> */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->orderByDesc('starts_at');
    }

    /** @return HasMany<Inquiry, $this> */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class)->latest();
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    /** Match an existing patient by email, then phone, so repeat bookers are not duplicated. */
    public static function findOrCreateFrom(array $data): self
    {
        $existing = null;
        if (! empty($data['email'])) {
            $existing = static::whereRaw('lower(email) = ?', [strtolower($data['email'])])->first();
        }
        if (! $existing && ! empty($data['phone'])) {
            $existing = static::where('phone', static::normalisePhone($data['phone']))->first();
        }
        $attributes = ['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'] ?? null, 'phone' => static::normalisePhone($data['phone'] ?? null)];
        if ($existing) {
            $existing->fill(array_filter($attributes))->save();

            return $existing;
        }

        return static::create($attributes + ['source' => $data['source'] ?? 'website', 'gender' => $data['gender'] ?? null, 'marketing_opt_in' => (bool) ($data['marketing_opt_in'] ?? false)]);
    }

    public static function normalisePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $phone);
        // Trinidad numbers: 7 local digits or 10 with the 868 area code; store in E.164 (+1868...).
        if (strlen($digits) === 7) {
            $digits = '1868'.$digits;
        } elseif (strlen($digits) === 10) {
            $digits = '1'.$digits;
        }

        return $digits ? '+'.$digits : null;
    }
}
