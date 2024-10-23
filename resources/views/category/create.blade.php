@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-success">Ajouter une Catégorie</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label for="nom" class="form-label">Nom de la Catégorie</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-outline-success">Ajouter la Catégorie</button>
    </form>
</div>
@endsection
