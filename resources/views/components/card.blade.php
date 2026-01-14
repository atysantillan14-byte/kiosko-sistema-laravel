@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'surface-card p-5']) }}>
    @if($title)
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">{{ $title }}</h3>
                @if($description)
                    <p class="text-xs text-slate-500">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div>{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ $title ? 'mt-4' : '' }}">
        {{ $slot }}
    </div>
</div>
