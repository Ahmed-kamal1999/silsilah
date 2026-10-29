@props(['type' => 'shield'])
<svg viewBox="0 0 400 300" class="w-full h-auto" fill="none" role="img" aria-hidden="true">
    <rect x="1" y="1" width="398" height="298" rx="18" fill="#0D1A4A" stroke="#B8913F" stroke-opacity=".5"/>
    <g stroke="#B8913F" stroke-opacity=".12">
        @for ($i = -300; $i < 400; $i += 24)<line x1="{{ $i }}" y1="300" x2="{{ $i + 300 }}" y2="0"/>@endfor
    </g>
    @if ($type === 'shield')
        <path d="M200 50l86 32v66c0 52-36 86-86 104-50-18-86-52-86-104V82z" fill="#122A6E" stroke="#E9C46A" stroke-width="3"/>
        <path d="M160 152l30 30 52-60" stroke="#F4D58D" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($type === 'chart')
        <g fill="#E9C46A"><rect x="90" y="170" width="38" height="80" rx="4" fill="#B8913F"/><rect x="146" y="130" width="38" height="120" rx="4"/><rect x="202" y="100" width="38" height="150" rx="4"/><rect x="258" y="62" width="38" height="188" rx="4" fill="#F4D58D"/></g>
        <path d="M80 140c50-60 90 20 140-30s80-40 110-50" stroke="#F4D58D" stroke-width="4" stroke-linecap="round"/>
    @else
        <g stroke="#E9C46A" stroke-width="3"><path d="M120 100L200 150 280 100M120 200L200 150 280 200M200 70v80M200 150v80"/></g>
        <g fill="#122A6E" stroke="#F4D58D" stroke-width="3"><circle cx="200" cy="150" r="22"/><circle cx="120" cy="100" r="14"/><circle cx="280" cy="100" r="14"/><circle cx="120" cy="200" r="14"/><circle cx="280" cy="200" r="14"/><circle cx="200" cy="70" r="14"/><circle cx="200" cy="230" r="14"/></g>
    @endif
</svg>
