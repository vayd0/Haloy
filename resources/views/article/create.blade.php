@extends("layout.app")

@section('contenu')
    <section class="create-article-container pt-[15vh]">
        <h1 class="ml-8 mb-3 title font-[2rem]">Créer un article</h1>

        <article class="p-8 glass-morph">

            @if ($errors->any())
                <div class="errors">
                    <h3>Erreurs:</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group flex flex-col mb-4">
                    <label for="titre" class="mb-2 ml-6">Titre *</label>
                    <input class="py-2 px-6 glass-morph" type="text" id="titre" name="titre" value="{{ old('titre') }}" placeholder="Quel est le titre de votre article ?" required>
                </div>
                <div class="form-group flex flex-col mb-4">
                    <label for="resume" class="mb-2 ml-6">Résumé *</label>
                    <textarea class="p-6 glass-morph" id="resume" name="resume" rows="3" placeholder="En quelques mots, c'est quoi votre article ?" required>{{ old('resume') }}</textarea>
                </div>

                <div class="form-group flex flex-col">
                    <label for="texte" class="mb-2 ml-6">Texte de l'article * <span
                            style="font-weight: normal; font-size: 0.9em; color: #666;">(Format Markdown
                            supporté)</span></label>

                    <textarea id="texte" name="texte" rows="12" required class="p-6 glass-morph" 
                        placeholder="# Votre Titre ici&#10;&#10;Un paragraphe d'introduction...&#10;&#10;## Un sous-titre&#10;- Une liste à puces&#10;- Un autre élément&#10;&#10;Un texte en **gras** et un [lien](https://google.com).">{{ old('texte') }}</textarea>

                    <div
                        style="margin-top: 8px; font-size: 0.85em; color: #555; background: #fff; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <strong>Aide Markdown :</strong>
                        <ul style="padding-left: 20px; margin: 5px 0; list-style-type: disc;">
                            <li><code># Titre</code> pour un grand titre</li>
                            <li><code>## Titre</code> pour un sous-titre</li>
                            <li><code>**texte**</code> pour mettre en <strong>gras</strong></li>
                            <li><code>*texte*</code> pour mettre en <em>italique</em></li>
                            <li><code>- item</code> pour une liste à puces</li>
                            <li><code>[Texte](url)</code> pour créer un lien</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Photo d'accroche *</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="media">Média audio *</label>
                    <input type="file" id="media" name="media" accept=".mp3,.wav" required>
                </div>

                <div class="form-group">
                    <label for="rythme_id">Rythme *</label>
                    <select id="rythme_id" name="rythme_id" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($rythmes as $rythme)
                            <option value="{{ $rythme->id }}" {{ old('rythme_id') == $rythme->id ? 'selected' : '' }}>
                                {{ $rythme->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="accessibilite_id">Accessibilité *</label>
                    <select id="accessibilite_id" name="accessibilite_id" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($accessibilites as $accessibilite)
                            <option value="{{ $accessibilite->id }}" {{ old('accessibilite_id') == $accessibilite->id ? 'selected' : '' }}>
                                {{ $accessibilite->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="conclusion_id">Conclusion *</label>
                    <select id="conclusion_id" name="conclusion_id" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($conclusions as $conclusion)
                            <option value="{{ $conclusion->id }}" {{ old('conclusion_id') == $conclusion->id ? 'selected' : '' }}>
                                {{ $conclusion->texte }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="flex items-center">
                        <input type="hidden" name="en_ligne" value="0">
                        <input type="checkbox" name="en_ligne" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('en_ligne') ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 font-bold">Mettre l'article en ligne (Activer)</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Créer l'article</button>
                    <a href="{{ route('accueil') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </article>
    </section>
@endsection