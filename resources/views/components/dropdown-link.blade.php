@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-slate-100 px-4 py-2 text-left text-sm font-semibold text-slate-900'
            : 'block w-full rounded-xl px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
