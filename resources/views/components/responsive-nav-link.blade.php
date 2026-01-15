@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-blue-600 px-4 py-2 text-left text-sm font-semibold text-white'
            : 'block w-full rounded-xl px-4 py-2 text-left text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
