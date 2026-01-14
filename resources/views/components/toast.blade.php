@php
    $success = session('success') ?? session('status');
    $error = session('error');
    $warning = session('warning');
@endphp

@if($success || $error || $warning)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-cloak
        class="fixed right-6 top-6 z-50 space-y-2"
        role="status"
        aria-live="polite"
    >
        @if($success)
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-soft dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ $success }}</span>
                <button type="button" class="ml-auto text-emerald-600" @click="show = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
        @if($warning)
            <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 shadow-soft dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ $warning }}</span>
                <button type="button" class="ml-auto text-amber-600" @click="show = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
        @if($error)
            <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-soft dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $error }}</span>
                <button type="button" class="ml-auto text-rose-600" @click="show = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>
@endif
