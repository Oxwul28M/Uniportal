@props(['icon', 'label', 'route'])

@php
    $isActive = $route !== '#' && request()->routeIs($route);
@endphp

<a href="{{ $route !== '#' ? route($route) : '#' }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group ' . ($isActive ? 'nav-link-active' : 'nav-link')]) }}>
    <i data-lucide="{{ $icon }}"
        class="w-5 h-5 {{ $isActive ? 'text-brand-800' : 'text-slate-400 group-hover:text-brand-600' }} transition-colors"></i>
    <span>{{ $label }}</span>
</a>