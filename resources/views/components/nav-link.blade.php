@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm'
            : 'inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
