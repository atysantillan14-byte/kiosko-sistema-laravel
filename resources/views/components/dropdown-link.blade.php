<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white']) }}>
    {{ $slot }}
</a>
