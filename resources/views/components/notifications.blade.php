<div class="relative">
    <button type="button" class="h-10 w-10 glass-morph flex items-center justify-center relative transition-all duration-300 hover:rotate-[10deg] hover:scale-105" id="notif-dropdown-toggle">
        <i class="fa-solid fa-bell text-white"></i> {{-- J'ai ajouté une couleur à l'icône --}}

        {{-- CORRECTION 1 : On vérifie l'auth et on compte directement --}}
        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div id="notif-dropdown-menu"
         class="absolute right-0 mt-2 w-96 bg-white border border-gray-200 shadow-xl rounded-lg py-4 opacity-0 pointer-events-none transition-all z-50 origin-top-right">

        {{-- CORRECTION 2 : On utilise auth()->user() --}}
        @if(auth()->check() && auth()->user()->unreadNotifications->isNotEmpty())
            <div class="flex justify-between items-center px-6 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Nouveautés</h2>
                {{-- Lien pour tout marquer comme lu --}}
                <small class="text-blue-500 cursor-pointer text-xs" id="mark-all-read">Tout lu</small>
            </div>

            <div class="space-y-4 px-6 max-h-96 overflow-y-auto">
                {{-- CORRECTION 3 : La boucle sur les notifs de l'user --}}
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-start gap-3 hover:bg-blue-100 transition">

                        {{-- Gestion de l'image (si elle existe dans les data) --}}
                        @if(isset($notification->data['article_image']))
                            <img src="{{ asset('storage/' . $notification->data['article_image']) }}"
                                 alt="Img" class="w-16 h-16 object-cover rounded shadow-sm">
                        @else
                            {{-- Image par défaut si pas d'image --}}
                            <div class="w-16 h-16 bg-blue-200 rounded flex items-center justify-center text-blue-400">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0"> {{-- min-w-0 aide pour le text truncate --}}
                            <p class="text-xs text-gray-500 mb-1">
                                <span class="font-bold text-gray-700">{{ $notification->data['auteur_name'] ?? 'Inconnu' }}</span> a publié :
                            </p>

                            <a href="{{ route('article.show', $notification->data['article_id']) }}"
                               class="block text-sm font-bold text-blue-600 hover:underline leading-tight mb-1">
                                {{ $notification->data['article_titre'] }}
                            </a>

                            {{-- Résumé coupé pour pas casser le design --}}
                            <p class="text-xs text-gray-600 line-clamp-2">
                                {{ Str::limit($notification->data['article_resume'] ?? '', 60) }}
                            </p>

                            <p class="text-[10px] text-gray-400 mt-2 text-right">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-2xl mb-2 text-gray-300"></i>
                <p class="text-sm">Aucune nouvelle notification.</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Ton script était bon, je l'ai gardé tel quel
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('notif-dropdown-toggle');
        const menu = document.getElementById('notif-dropdown-menu');
        const markAllReadBtn = document.getElementById('mark-all-read');

        if(toggle && menu) { // Petite sécurité s'ils n'existent pas sur la page
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('opacity-0');
                menu.classList.toggle('pointer-events-none');
                // Petit effet de transformation pour faire joli
                menu.classList.toggle('scale-95');
                menu.classList.toggle('scale-100');
            });

            document.addEventListener('click', function () {
                menu.classList.add('opacity-0');
                menu.classList.add('pointer-events-none');
                menu.classList.add('scale-95');
                menu.classList.remove('scale-100');
            });

            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Gestion du clic sur "Tout lu"
        if(markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Créer une requête AJAX pour marquer les notifications comme lues
                fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if(response.ok) {
                        // Rafraîchir la page ou mettre à jour l'interface
                        location.reload();
                    }
                })
                .catch(error => console.error('Erreur:', error));
            });
        }
    });
</script>