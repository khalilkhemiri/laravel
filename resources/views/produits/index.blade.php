@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4 text-green-600">Liste des Produits</h1>

<div class="container mb-4 d-flex justify-content-between align-items-center">
   <form action="{{ route('produits.index') }}" method="GET" class="w-50">
       <div class="input-group">
           <input type="text" name="search" class="form-control rounded-pill" placeholder="Rechercher un produit" value="{{ request('search') }}">
           <button type="submit" class="btn btn-primary rounded-pill"><i class="material-icons">search</i></button> <!-- Utilisation de Material Icons pour la recherche -->
       </div>
   </form>
   <a href="{{ route('produits.create') }}" class="btn btn-success rounded-pill d-flex align-items-center">
       <i class="material-icons">add</i> <!-- Icône Material Icons pour l'ajout -->
   </a>
</div>

<div class="container mt-4">
    <table class="table align-middle mb-0 bg-white shadow-sm">
        <thead class="bg-light">
            <tr>
                <th>Nom du Produit</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img
                                src="{{ asset($produit->image) ?? 'https://via.placeholder.com/45' }}" 
                                alt="{{ $produit->nom }}"
                                style="width: 45px; height: 45px;"
                                class="rounded-circle shadow-sm"
                            />
                            <div class="ms-3">
                                <p class="fw-bold mb-1 text-truncate" style="max-width: 150px;">{{ $produit->nom }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="fw-normal mb-1 text-truncate" style="max-width: 250px;">{{ $produit->description }}</p>
                    </td>
                    <td>
                        <span class="badge bg-success rounded-pill">{{ number_format($produit->prix, 2) }} DT</span>
                    </td>
                    <td>{{ $produit->quantite }}</td>
                    <td>{{ $produit->categorie->nom ?? 'N/A' }}</td>                    <td>
                        <!-- Bouton pour afficher le modal -->
                        <button type="button" class="btn btn-link btn-sm btn-rounded" title="Modifier" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $produit->id }}">
                            <i class="material-icons">edit</i> <!-- Utilisation de Material Icons pour modifier -->
                        </button>
                        <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm btn-rounded text-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                <i class="material-icons">delete</i> <!-- Utilisation de Material Icons pour supprimer -->
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal pour modifier le produit -->
                <div class="modal fade" id="editProductModal{{ $produit->id }}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProductModalLabel">Modifier le Produit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom du Produit</label>
                                        <input type="text" class="form-control" id="nom" name="nom" value="{{ $produit->nom }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $produit->description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prix" class="form-label">Prix</label>
                                        <input type="number" class="form-control" id="prix" name="prix" value="{{ $produit->prix }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantite" class="form-label">Quantité</label>
                                        <input type="number" class="form-control" id="quantite" name="quantite" value="{{ $produit->quantite }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="categorie" class="form-label">Catégorie</label>
                                        <input type="text" class="form-control" id="categorie" name="categorie" value="{{ $produit->categorie->nom }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image (facultatif)</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- CSS personnalisé pour améliorer l'affichage -->
<style>
    .table {
        border-collapse: collapse;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.9rem;
    }
    .btn-link {
        color: #007bff;
    }
    .btn-link:hover {
        color: #0056b3;
    }
    .text-truncate {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

@endsection
