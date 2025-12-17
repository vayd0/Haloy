@extends("layout.app")

@section('contenu')
<div class="create-article-container">
    <h1>Créer un article</h1>


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

        <!-- Titre -->
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required>
        </div>

        <!-- Résumé -->
        <div class="form-group">
            <label for="resume">Résumé *</label>
            <textarea id="resume" name="resume" rows="3" required>{{ old('resume') }}</textarea>
        </div>

        <!-- Texte -->
        <div class="form-group">
            <label for="texte">Texte de l'article *</label>
            <textarea id="texte" name="texte" rows="8" required>{{ old('texte') }}</textarea>
        </div>

        <!-- Image -->
        <div class="form-group">
            <label for="image">Photo d'accroche *</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <!-- Média audio -->
        <div class="form-group">
            <label for="media">Média audio *</label>
            <input type="file" id="media" name="media" accept=".mp3,.wav" required>
        </div>

        <!-- Rythme -->
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

        <!-- Accessibilité -->
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

        <!-- Conclusion -->
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

        <!-- Boutons -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">Créer l'article</button>
            <a href="{{ route('accueil') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection
