@props(['compact' => false])

@php
    $user = auth()->user();
    $canSwitchLocale = ! $user || $user->isSuperAdmin();
    $currentLocale = app()->getLocale();
    $languages = [
        'fr' => $compact ? __('ui.locale.fr_short') : __('ui.locale.french'),
        'ar' => $compact ? __('ui.locale.ar_short') : __('ui.locale.arabic'),
    ];
@endphp

@if ($canSwitchLocale)
    <div @class(['locale-switcher', 'locale-switcher-compact' => $compact]) role="group" aria-label="{{ __('ui.locale.switcher_label') }}">
        @foreach ($languages as $locale => $label)
            <a
                href="{{ route('locale.switch', ['locale' => $locale]) }}"
                @class(['locale-switcher-link', 'locale-switcher-link-active' => $currentLocale === $locale])
                aria-current="{{ $currentLocale === $locale ? 'true' : 'false' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>
@endif
