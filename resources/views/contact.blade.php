@extends('layout.app')

@section('contenu')
<section class="contact-section">
    <div class="contact-container">
        <div class="contact-wrapper">
            <h1 class="contact-title">Nous contacter</h1>
            <p class="contact-subtitle">Envoyez-nous un message et nous vous répondrons au plus vite.</p>

            @if ($errors->any())
                <div class="contact-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="contact-success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                @csrf

                <div class="form-group">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                        class="form-input"
                        placeholder="Votre nom" required>
                    @error('nom')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="form-input"
                        placeholder="votre.email@exemple.com" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" rows="6"
                        class="form-input form-textarea"
                        placeholder="Votre message..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="submit-button">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

