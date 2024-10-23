<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Afficher le formulaire d'ajout de catégorie
    public function create()
    {
        return view('category.create');
    }

    // Stocker la nouvelle catégorie
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categorie', // Validation
        ]);

        Categorie::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('categories.create')->with('success', 'Catégorie ajoutée avec succès.');
    }
    public function index(Request $request)
    {
        $query = Categorie::query();

        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $categories = $query->get();

        return view('category.index', compact('categories'));
    }

    // Affiche le formulaire de création d'une nouvelle catégorie
   

    // Stocke une nouvelle catégorie

    // Met à jour une catégorie
    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    // Supprime une catégorie
    public function destroy(Categorie $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}

