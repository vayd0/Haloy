{{-- Composant blade pour le badge de stat --}}
@props(['label', 'value', 'icon' => null, 'link' => null])

@php
    use Illuminate\Support\Str;

    $displayValue = is_numeric($value) ? number_format($value) : $value;

    // Autoriser uniquement http(s) et chemins relatifs commençant par "/".
    $safeLink = null;
    if ($link) {
        if (Str::startsWith($link, ['http://', 'https://', '/'])) {
            $safeLink = $link;
        }
    }

    // Détecter si le lien est externe pour ajouter rel/target si besoin
    $external = $safeLink && Str::startsWith($safeLink, ['http://', 'https://']) && !Str::startsWith($safeLink, [url('/')]);

    // Autoriser le rendu brut seulement si l'icône commence par <svg ou <i>
    $iconIsSafeHtml = false;
    $safeIconHtml = null;
    if ($icon && is_string($icon)) {
        $trimmed = ltrim($icon);
        if (preg_match('/^<\s*(svg|i)\b/i', $trimmed)) {
            $iconIsSafeHtml = true;
            $safeIconHtml = $trimmed;
        }
    }
@endphp

@if($safeLink)
    <a href="{{ $safeLink }}" class="stat-badge inline-flex items-center p-2 rounded-md hover:bg-gray-100 transition" @if($external) target="_blank" rel="noopener noreferrer" @endif>
        @else
            <div class="relative stat-badge inline-flex items-center p-4 rounded-md glass-morph">
                @endif

                {{-- Icon: si fourni en slot nommé, l'utiliser ; sinon afficher l'icône prop --}}
                @hasSection('icon')
                    <span class="absolute top-[-1rem] right-[-1rem] stat-icon mr-2 text-[1rem] p-2 glass-morph">@yield('icon')</span>
                @elseif($iconIsSafeHtml)
                    <span class="absolute top-[-1rem] right-[-1rem] stat-icon mr-2 text-[1rem] p-2 glass-morph">{!! $safeIconHtml !!}</span>
                @elseif($icon !== null)
                    <span class="absolute top-[-1rem] right-[-1rem] stat-icon mr-2 text-[1rem] p-2 glass-morph">{{ $icon }}</span>
                @endif

                <div class="stat-content text-left flex justify-between gap-3 items-center">
                    <div class="stat-value text-lg font-semibold">{{ $displayValue }}</div>
                    <div class="stat-label text-sm text-white">{{ $label }}</div>
                </div>

            @if($safeLink)
    </a>
    @else
        </div>
@endif
