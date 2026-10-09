@props(['status'])
@php($tone = match ($status) { 'confirmed', 'booked', 'completed' => 'tone-green', 'pending', 'new' => 'tone-amber', 'contacted' => 'tone-blue', 'cancelled', 'no_show' => 'tone-red', default => 'tone-slate' })
<span {{ $attributes->merge(['class' => 'chip '.$tone]) }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
