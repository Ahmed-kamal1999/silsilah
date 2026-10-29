@props(['size' => 44])
<svg {{ $attributes }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 64 64" role="img" aria-label="{{ __('site.brand.name') }}">
    <circle cx="32" cy="32" r="30" fill="#0D1A4A" stroke="#B8913F" stroke-width="1.5"/>
    <circle cx="32" cy="32" r="25.5" fill="none" stroke="#B8913F" stroke-opacity=".35"/>
    <rect x="17" y="38" width="5" height="9" rx="1" fill="#B8913F"/>
    <rect x="25" y="32" width="5" height="15" rx="1" fill="#E9C46A"/>
    <rect x="33" y="26" width="5" height="21" rx="1" fill="#E9C46A"/>
    <rect x="41" y="20" width="5" height="27" rx="1" fill="#F4D58D"/>
    <path d="M13 36c7-9 11 5 18-3s12-12 20-8" fill="none" stroke="#F4D58D" stroke-width="2.4" stroke-linecap="round"/>
</svg>
