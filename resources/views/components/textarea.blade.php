@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'rows' => 4,
    'hint' => null,
])

@php
    $hasError = $name && $errors->has($name);
    $inputId = $id ?? $name;
    $inputClasses = 'w-full rounded-xl border px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100';
    $inputClasses .= $hasError ? ' border-rose-400 focus:border-rose-500 focus:ring-rose-500' : ' border-slate-200';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label class="text-sm font-semibold text-slate-700" @if($inputId) for="{{ $inputId }}" @endif>
            {{ $label }}
        </label>
    @endif

    <textarea
        @if($name) name="{{ $name }}" @endif
        @if($inputId) id="{{ $inputId }}" @endif
        rows="{{ $rows }}"
        {{ $attributes->except('class')->merge(['class' => $inputClasses]) }}
    >{{ old($name, $slot) }}</textarea>

    @if($hint)
        <p class="text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
