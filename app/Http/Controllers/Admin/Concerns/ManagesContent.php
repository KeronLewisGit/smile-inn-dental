<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;

trait ManagesContent
{
    /** Store an uploaded image in public/uploads and return its relative path. */
    protected function storeImage(Request $request, string $field, string $folder, ?string $current = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $current;
        }
        $file = $request->file($field);
        $name = now()->format('Ymd-His').'-'.preg_replace('/[^a-z0-9.]+/i', '-', strtolower($file->getClientOriginalName()));
        $file->move(public_path('uploads/'.$folder), $name);

        return 'uploads/'.$folder.'/'.$name;
    }

    /**
     * "Name | description" per line into [{name, description}].
     *
     * @return array<int, array<string, string>>
     */
    protected function linesToPairs(?string $text, string $a = 'name', string $b = 'description'): array
    {
        return collect(preg_split('/\r?\n/', (string) $text))->map(fn ($l) => trim($l))->filter()->map(function ($line) use ($a, $b) {
            [$x, $y] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');

            return [$a => $x, $b => $y];
        })->values()->all();
    }

    /** @param array<int, array<string, string>>|null $pairs */
    protected function pairsToLines(?array $pairs, string $a = 'name', string $b = 'description'): string
    {
        return collect($pairs ?? [])->map(fn ($p) => ($p[$a] ?? '').' | '.($p[$b] ?? ''))->implode("\n");
    }
}
