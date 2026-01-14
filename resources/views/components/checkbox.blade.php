@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'checked' => false,
])

@php
    $inputId = $id ?? $name;
@endphp

<label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
    <input
        type="checkbox"
        @if($name) name="{{ $name }}" @endif
        @if($inputId) id="{{ $inputId }}" @endif
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'rounded border-slate-300 text-brand-600 shadow-sm focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-900']) }}
    />
    <span>{{ $label ?? $slot }}</span>
</label>
