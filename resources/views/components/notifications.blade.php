<div class="relative">
    <button type="button" class="h-10 w-10 glass-morph flex items-center justify-center relative transition-all duration-300 hover:rotate-[10deg] hover:scale-105" id="notif-dropdown-toggle">
        <i class="fa-solid fa-bell"></i>
        @if(isset($notifications) && $notifications->whereNull('read_at')->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ $notifications->whereNull('read_at')->count() }}
            </span>
        @endif
    </button>
    <div id="notif-dropdown-menu"
        class="absolute right-[-100%] mt-2 w-96 w-1/3 bg-blue-50 shadow-md rounded-lg py-4 opacity-0 pointer-events-none transition-all z-50"
        style="border-radius:10px;">
        @if(isset($notifications) && $notifications->isNotEmpty())
            <h2 class="text-xl font-semibold mb-4 px-6">Nouveaux articles de vos abonnements</h2>
            <div class="space-y-4 px-6 max-h-96 overflow-y-auto">
                @foreach($notifications as $notification)
                    <div class="bg-white border border-blue-200 rounded-lg p-4 flex items-start gap-4">
                        @if($notification->data['article_image'])
                            <img src="{{ asset('storage/' . $notification->data['article_image']) }}"
                                alt="{{ $notification->data['article_titre'] }}" class="w-20 h-20 object-cover rounded">
                        @endif
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1">
                                <strong>{{ $notification->data['auteur_name'] }}</strong> a publié un nouvel article
                            </p>
                            <a href="{{ route('article.show', $notification->data['article_id']) }}"
                                class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                {{ $notification->data['article_titre'] }}
                            </a>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->data['article_resume'] }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-4 text-gray-500">Aucune notification.</div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('notif-dropdown-toggle');
        const menu = document.getElementById('notif-dropdown-menu');
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('pointer-events-none');
        });
        document.addEventListener('click', function () {
            menu.classList.add('opacity-0');
            menu.classList.add('pointer-events-none');
        });
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>