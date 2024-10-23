@extends('backoffice.back')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Créer une Plante</h5>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('plants.store.admin') }}" method="POST" class="mt-4">
                        @csrf

                        <div class="mb-3">
                            <label for="common_name" class="form-label">Nom Commun <span class="text-danger">*</span></label>
                            <input type="text" name="common_name" id="common_name" class="form-control" required>
                            <small class="form-text text-muted">Doit contenir au moins 3 caractères.</small>
                        </div>

                        <div class="mb-3">
                            <label for="scientific_name" class="form-label">Nom Scientifique</label>
                            <input type="text" name="scientific_name" id="scientific_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="origin" class="form-label">Origine <span class="text-danger">*</span></label>
                            <input type="text" name="origin" id="origin" class="form-control" required>
                            <small class="form-text text-muted">Doit contenir au moins 3 caractères.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Choisir une Catégorie <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Créer</button>
                        <a href="{{ route('plants.index.admin') }}" class="btn btn-secondary">Retour à la Liste</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection