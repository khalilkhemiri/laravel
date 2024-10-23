@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar - Filters -->
        <div class="col-lg-3 mb-4">
            <h4 class="mb-4 text-success"><i class="fas fa-filter"></i> Filtres</h4>
            <form action="{{ route('shop.index') }}" method="GET">
                <!-- Search -->
                <div class="form-group mb-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request()->query('search') }}">
                </div>

                <!-- Category Filter -->
                <div class="form-group mb-4">
                    <label for="categorie" class="form-label"><i class="fas fa-th-list"></i> Catégorie</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ request()->query('categorie') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }} <!-- Assurez-vous que c'est le bon attribut à afficher -->
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="form-group mb-4">
                    <label for="prix_min" class="form-label"><i class="fas fa-dollar-sign"></i> Prix Minimum</label>
                    <input type="number" name="prix_min" id="prix_min" class="form-control" value="{{ request()->query('prix_min') }}" placeholder="Min">
                </div>

                <div class="form-group mb-4">
                    <label for="prix_max" class="form-label"><i class="fas fa-dollar-sign"></i> Prix Maximum</label>
                    <input type="number" name="prix_max" id="prix_max" class="form-control" value="{{ request()->query('prix_max') }}" placeholder="Max">
                </div>

                <button type="submit" class="btn btn-outline-success btn-block">
                    <i class="fas fa-check"></i> Appliquer les filtres
                </button>
            </form>
        </div>

        <!-- Product Display -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-success"><i class="fas fa-box"></i> Nos Produits</h2>
                <a href="/panier" class="btn btn-outline-success">
                    <i class="fas fa-shopping-cart"></i> Panier
                </a>
                <form action="{{ route('shop.index') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="{{ request()->query('search') }}">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="row">
                @forelse($produits as $produit)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm ">
                            <img src="{{ asset($produit->image) ?? 'https://via.placeholder.com/700x700' }}" class="card-img-top" alt="{{ $produit->nom }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $produit->nom }}</h5>
                                <p class="card-text">{{ Str::limit($produit->description, 100) }}</p>
                                <p class="card-text"><strong>Prix: </strong>{{ number_format($produit->prix, 2) }} €</p>
                                <p class="card-text"><strong>Catégorie: </strong>{{ $produit->categorie->nom ?? 'Non spécifiée' }}</p> <!-- Afficher la catégorie -->
                                <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn w-100">
                                        <i class="fas fa-shopping-cart"></i> 
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Aucun produit trouvé.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $produits->links() }}
            </div>
        </div>
    </div>
</div>

<!-- CSS personnalisé pour améliorer l'affichage -->
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: scale(1.05);
    }
    .btn-outline-success {
        border-color: #28a745; /* Vert */
        color: #28a745; /* Vert */
    }
    .btn {
        border-color: #28a745; /* Vert */
        color: #28a745; /* Vert */
    }
    .btn:hover {
        background-color: #28a745; /* Vert */
        color: white;
    }
    .btn-outline-success:hover {
        background-color: #28a745; /* Vert */
        color: white;
    }
</style>

<!-- Ajouter Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
