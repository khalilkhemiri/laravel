@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4 text-green-600">Liste des Catégories</h1>

<div class="container mb-4 d-flex justify-content-between align-items-center">
    <form action="{{ route('categories.index') }}" method="GET" class="w-50">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Rechercher une catégorie" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary rounded-pill"><i class="material-icons">search</i></button>
        </div>
    </form>
    <a href="{{ route('categories.create') }}" class="btn btn-success rounded-pill d-flex align-items-center">
        <i class="material-icons">add</i> Ajouter une catégorie
    </a>
</div>

<div class="container mt-4">
    <table class="table align-middle mb-0 bg-white shadow-sm">
        <thead class="bg-light">
            <tr>
                <th>Nom de la Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $categorie)
                <tr>
                    <td>{{ $categorie->nom }}</td>
                    <td>
                        <button type="button" class="btn btn-link btn-sm btn-rounded" title="Modifier" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $categorie->id }}">
                            <i class="material-icons">edit</i>
                        </button>
                        <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm btn-rounded text-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                <i class="material-icons">delete</i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal pour modifier la catégorie -->
                <div class="modal fade" id="editCategoryModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategoryModalLabel">Modifier la Catégorie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('categories.update', $categorie->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom de la Catégorie</label>
                                        <input type="text" class="form-control" id="nom" name="nom" value="{{ $categorie->nom }}" required>
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
    .btn-link {
        color: #007bff;
    }
    .btn-link:hover {
        color: #0056b3;
    }
</style>

@endsection
