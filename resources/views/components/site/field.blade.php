@props(['label', 'name', 'hint' => null, 'required' => false])
<div {{ $attributes }}>
    <label for="{{ $name }}" class="label">{{ $label }}@if ($required)<span class="text-rose"> *</span>@endif</label>
    {{ $slot }}
    @if ($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="error" role="alert">{{ $message }}</p>@enderror
</div>
