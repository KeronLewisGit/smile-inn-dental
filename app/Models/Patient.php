<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string $source
 * @property string|null $notes
 * @property bool $marketing_opt_in
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Appointment> $appointments
 * @property-read Collection<int, Inquiry> $inquiries
 */
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

    /**
     * Match an existing patient so repeat bookers are not duplicated: by email first, then by phone when the
     * name matches too (a household often shares one number). Existing details are never overwritten by a
     * web form; only blanks are filled in.
     *
     * @param  array<string, mixed>  $data
     */
    public static function findOrCreateFrom(array $data): self
    {
        $email = filled($data['email'] ?? null) ? strtolower(trim((string) $data['email'])) : null;
        $phone = static::normalisePhone($data['phone'] ?? null);
        $first = trim((string) ($data['first_name'] ?? ''));
        $last = trim((string) ($data['last_name'] ?? ''));

        $existing = $email ? static::whereRaw('lower(email) = ?', [$email])->first() : null;
        if (! $existing && $phone) {
            $existing = static::where('phone', $phone)->get()
                ->first(fn (Patient $p) => strcasecmp(trim($p->first_name), $first) === 0 && strcasecmp(trim($p->last_name), $last) === 0);
        }

        if ($existing) {
            $existing->fill(array_filter([
                'email' => $existing->email ? null : $email,
                'phone' => $existing->phone ? null : $phone,
                'gender' => $existing->gender ? null : ($data['gender'] ?? null),
                'marketing_opt_in' => ! $existing->marketing_opt_in && (bool) ($data['marketing_opt_in'] ?? false) ? true : null,
            ]))->save();

            return $existing;
        }

        return static::create([
            'first_name' => $first, 'last_name' => $last, 'email' => $email, 'phone' => $phone,
            'source' => $data['source'] ?? 'website', 'gender' => $data['gender'] ?? null, 'marketing_opt_in' => (bool) ($data['marketing_opt_in'] ?? false),
        ]);
    }

    public static function normalisePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        // Trinidad numbers: 7 local digits or 10 with the 868 area code; store in E.164 (+1868...).
        if (strlen($digits) === 7) {
            $digits = '1868'.$digits;
        } elseif (strlen($digits) === 10) {
            $digits = '1'.$digits;
        }

        return $digits !== '' ? '+'.$digits : null;
    }
}
