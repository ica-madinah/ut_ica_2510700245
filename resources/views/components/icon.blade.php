@props(['name' => 'arrow'])
@php
$paths = [
 'arrow' => 'M5 12h14m-5-5 5 5-5 5',
 'wrench' => 'm14 6 4 4M5 19l7-7M16 3a5 5 0 0 0-5 7L3 18a2 2 0 0 0 3 3l8-8a5 5 0 0 0 7-5l-4 3-4-4 3-4Z',
 'check' => 'm5 12 4 4L19 6',
 'clock' => 'M12 8v4l3 2M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z',
 'pin' => 'M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0ZM15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
 'shield' => 'M12 3 3 7v6c0 5 9 9 9 9s9-4 9-9V7l-9-4Zm-4 9 3 3 5-6',
 'car' => 'm5 7 2-4h10l2 4 2 3v9h-3v-3H6v3H3v-9l2-3Zm0 0h14M6 12h2m8 0h2',
 'grid' => 'M3 3h7v7H3Zm11 0h7v7h-7ZM3 14h7v7H3Zm11 0h7v7h-7Z',
 'calendar' => 'M7 3v4m10-4v4M3 10h18M4 5h16v16H4Z',
 'logout' => 'M10 3H3v18h7m-1-9h12m-4-4 4 4-4 4',
 'plus' => 'M12 5v14M5 12h14',
 'mail' => 'M3 5h18v14H3ZM3 5l9 8 9-8',
];
@endphp
<svg {{ $attributes->merge(['class' => 'icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['wrench'] }}"/></svg>
