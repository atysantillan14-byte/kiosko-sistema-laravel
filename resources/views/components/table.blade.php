@props([
    'striped' => true,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="sticky top-0 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                {{ $head ?? '' }}
            </thead>
            <tbody class="divide-y divide-slate-100 {{ $striped ? '[&>tr:nth-child(even)]:bg-slate-50/50' : '' }}">
                {{ $body ?? '' }}
            </tbody>
        </table>
    </div>
</div>
