@props([
    'title' => 'No hay datos',
    'description' => 'Todavía no se registraron elementos en esta sección.',
    'icon' => 'fa-regular fa-folder-open',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center dark:border-slate-700 dark:bg-slate-800']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-500 shadow-soft dark:bg-slate-900 dark:text-slate-300">
        <i class="{{ $icon }}"></i>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-slate-900">{{ $title }}</h3>
        <p class="mt-1 text-xs text-slate-500">{{ $description }}</p>
    </div>
    @if(trim($slot))
        <div class="mt-2">{{ $slot }}</div>
    @endif
</div>
