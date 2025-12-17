@extends('layout.app')

@section('contenu')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Modifier l'article</h1>

        <form action="{{ route('article.update', $article) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="titre">
                    Titre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="titre" type="text" name="titre" value="{{ old('titre', $article->titre) }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="resume">
                    Résumé
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="resume" name="resume" rows="3" required>{{ old('resume', $article->resume) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="texte">
                    Contenu
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="texte" name="texte" rows="10" required>{{ old('texte', $article->texte) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Image (laisser vide pour conserver l'actuelle)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" type="file" name="image" accept="image/*">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media">
                    Média audio (laisser vide pour conserver l'actuel)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="media" type="file" name="media" accept="audio/*">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="rythme_id">
                        Rythme
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rythme_id" name="rythme_id" required>
                        @foreach($rythmes as $rythme)
                            <option value="{{ $rythme->id }}" {{ old('rythme_id', $article->rythme_id) == $rythme->id ? 'selected' : '' }}>{{ $rythme->texte }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="accessibilite_id">
                        Accessibilité
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="accessibilite_id" name="accessibilite_id" required>
                        @foreach($accessibilites as $accessibilite)
                            <option value="{{ $accessibilite->id }}" {{ old('accessibilite_id', $article->accessibilite_id) == $accessibilite->id ? 'selected' : '' }}>{{ $accessibilite->texte }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="conclusion_id">
                        Conclusion
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="conclusion_id" name="conclusion_id" required>
                        @foreach($conclusions as $conclusion)
                            <option value="{{ $conclusion->id }}" {{ old('conclusion_id', $article->conclusion_id) == $conclusion->id ? 'selected' : '' }}>{{ $conclusion->texte }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="hidden" name="en_ligne" value="0">
                    <input type="checkbox" name="en_ligne" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('en_ligne', $article->en_ligne) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700 font-bold">Mettre l'article en ligne (Activer)</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Mettre à jour
                </button>
                <a href="{{ route('article.show', $article) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection

