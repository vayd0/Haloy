@props(['user'])

@php
    $initial = strtoupper(substr($user, 0, 1));
@endphp
<div class="flex items-center justify-center w-6 h-6 rounded-full bg-redc/20 text-white font-medium text-md select-none">
    {{ $initial }}
</div>