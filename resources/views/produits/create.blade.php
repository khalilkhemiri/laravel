@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4 text-green-600">Ajouter un Produit</h1>

    <!-- Formulaire d'ajout de produit -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <!-- Affichage des erreurs de validation uniquement après une tentative de soumission -->
                    @if ($errors->any() && session('submitted'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-floating mb-4">
                            <input type="text" name="nom" id="nom" class="form-control shadow-sm" placeholder="Nom du produit" value="{{ old('nom') }}" required>
                            <label for="nom">Nom du produit</label>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea name="description" id="description" class="form-control shadow-sm" placeholder="Description du produit" rows="5" required>{{ old('description') }}</textarea>
                            <label for="description">Description</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="number" name="prix" id="prix" class="form-control shadow-sm" placeholder="Prix" value="{{ old('prix') }}" step="0.01" required>
                            <label for="prix">Prix (€)</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="number" name="quantite" id="quantite" class="form-control shadow-sm" placeholder="Quantité" value="{{ old('quantite') }}" required>
                            <label for="quantite">Quantité</label>
                        </div>

                        <div class="mb-3">
    <label for="categorie_id" class="form-label">Catégorie</label>
    <select class="form-select" id="categorie_id" name="categorie_id" required>
        @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
</div>


                        <div class="mb-4">
                            <label for="image" class="form-label">Image du produit</label>
                            <input type="file" name="image" id="image" class="form-control shadow-sm" required>
                        </div>

                        <!-- Bouton stylisé -->
                        <button type="submit" class="btn btn-primary w-100 shadow-sm py-3">
                            <i class="fas fa-plus-circle"></i> Ajouter le produit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personnalisé -->
<style>
    .form-floating > label {
        padding-left: 15px;
    }
    .btn-primary {
        background-color: green;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .card {
        border-radius: 10px;
    }
</style>

<script>
    // Marquer la soumission du formulaire
    document.querySelector('form').addEventListener('submit', function() {
        sessionStorage.setItem('submitted', 'true');
    });
</script>
@endsection
