@extends("layout.app")

@section('contenu')
    <section class="relative flex flex-col items-center p-5 pt-[20vh] min-h-[100vh]">
        <form id="articleForm" action="{{ route('article.update', $article) }}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-4xl mx-auto flex-1 flex flex-col">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <h3 class="font-bold mb-2">Erreurs :</h3>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 glass-morph p-4 flex-1">
                <div class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1">
                    <label for="titre" class="text-sm text-gray-200 mb-1">Titre *</label>
                    <input class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none" type="text" id="titre"
                        name="titre" value="{{ old('titre', $article->titre) }}" placeholder="Titre de l'article" required>
                </div>
                <div
                    class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1 sm:col-span-2 md:col-span-2">
                    <label for="resume" class="text-sm text-gray-200 mb-1">Résumé *</label>
                    <textarea class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none" id="resume"
                        name="resume" rows="3" placeholder="Résumé" required>{{ old('resume', $article->resume) }}</textarea>
                </div>
                <div
                    class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1 sm:col-span-2 md:col-span-3">
                    <label for="texte" class="text-sm text-gray-200 mb-1">Texte *</label>
                    <textarea id="texte" name="texte" rows="8" required
                        class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none"
                        placeholder="Tout commence par quelques mots...">{{ old('texte', $article->texte) }}</textarea>
                </div>
                <div class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1">
                    <label for="rythme_id" class="text-sm text-gray-200">Rythme *</label>
                    <select id="rythme_id" name="rythme_id" required
                        class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-redc">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($rythmes as $rythme)
                            <option value="{{ $rythme->id }}" {{ old('rythme_id', $article->rythme_id) == $rythme->id ? 'selected' : '' }}>
                                {{ $rythme->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1">
                    <label for="accessibilite_id" class="text-sm text-gray-200">Accessibilité *</label>
                    <select id="accessibilite_id" name="accessibilite_id" required
                        class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-redc">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($accessibilites as $accessibilite)
                            <option value="{{ $accessibilite->id }}" {{ old('accessibilite_id', $article->accessibilite_id) == $accessibilite->id ? 'selected' : '' }}>
                                {{ $accessibilite->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="glass-morph rounded-xl p-6 flex flex-col gap-2 shadow-lg col-span-1">
                    <label for="conclusion_id" class="text-sm text-gray-200">Conclusion *</label>
                    <select id="conclusion_id" name="conclusion_id" required
                        class="p-3 rounded-lg bg-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-redc">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($conclusions as $conclusion)
                            <option value="{{ $conclusion->id }}" {{ old('conclusion_id', $article->conclusion_id) == $conclusion->id ? 'selected' : '' }}>
                                {{ $conclusion->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="rounded-xl flex flex-col gap-2">
                    <label for="image" class="text-sm text-gray-200 mb-1">Photo d'accroche</label>
                    @if($article->image)
                        <div class="mb-2">
                            <span class="text-xs text-gray-400">Image actuelle :</span><br>
                            <img src="{{ asset('storage/' . $article->image) }}" alt="Image actuelle" class="max-h-32 rounded-lg mt-1">
                        </div>
                    @endif
                    <label for="image"
                        class="flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-lg p-6 cursor-pointer bg-white/10 hover:bg-white/20 transition">
                        <i class="fa-solid fa-image text-[2rem] text-redc/50"></i>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden">
                        <span class="text-xs text-gray-400 mt-2">Laisser vide pour conserver l'actuelle</span>
                    </label>
                </div>
                <div class="rounded-xl flex flex-col gap-2">
                    <label for="media" class="text-sm text-gray-200 mb-1">Média audio</label>
                    @if($article->media)
                        <div class="mb-2">
                            <span class="text-xs text-gray-400">Audio actuel :</span><br>
                            <audio controls class="mt-1">
                                <source src="{{ asset('storage/' . $article->media) }}">
                                Votre navigateur ne supporte pas l'audio.
                            </audio>
                        </div>
                    @endif
                    <label for="media"
                        class="flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-lg p-6 cursor-pointer bg-white/10 hover:bg-white/20 transition">
                        <i class="fa-solid fa-music text-[2rem] text-redc/50"></i>
                        <input type="file" id="media" name="media" accept=".mp3,.wav" class="hidden">
                        <span class="text-xs text-gray-400 mt-2">Laisser vide pour conserver l'actuel</span>
                    </label>
                </div>
                <div class="rounded-xl flex items-center gap-2 shadow-lg">
                    <div class="hidden">
                        <input type="hidden" name="en_ligne" value="0">
                        <input type="checkbox" id="en_ligne" name="en_ligne" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('en_ligne', $article->en_ligne) ? 'checked' : '' }}>
                        <span class="text-gray-200 text-sm">Mettre l'article en ligne (Activer)</span>
                    </div>
                    <div class="w-full flex flex-row gap-4 justify-center items-end shadow-none">
                        <button type="submit"
                            class="h-12 w-12 glass-morph flex justify-center items-center rounded-full bg-redc/60 text-white font-bold cursor-pointer hover:bg-redc transition"
                            title="Valider">
                            <i class="fa-solid fa-check"></i>
                        </button>
                        <a href="{{ route('article.show', $article) }}"
                            class="h-12 w-12 glass-morph flex justify-center items-center rounded-full bg-gray-500/60 text-white font-bold cursor-pointer hover:bg-gray-700 transition text-center"
                            title="Annuler">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('articleForm');
            const enLigneCheckbox = document.getElementById('en_ligne');
            form.addEventListener('submit', function (e) {
                if (!enLigneCheckbox.checked) {
                    e.preventDefault();
                    if (confirm("Voulez-vous mettre l'article en ligne ?")) {
                        enLigneCheckbox.checked = true;
                    }
                    form.submit();
                }
            });
        });
    </script>
@endsection

