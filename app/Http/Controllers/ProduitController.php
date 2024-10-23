<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // Afficher le formulaire d'ajout de produit
    public function create()
    {
        $categories = Categorie::all(); // Récupérer toutes les catégories
        return view('produits.create', compact('categories'));
    }

    // Stocker le produit dans la base de données
    public function store(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'prix' => 'required|numeric|min:0.01',
            'quantite' => 'required|integer|min:1',
            'categorie_id' => 'required|exists:categorie,id', // Changez ici
            'image' => 'nullable|image|max:2048',
        ]);

        // Affichez les données validées pour débogage
     \Log::info('Données validées : ', $validated);

        // Vérifier si une image est téléchargée
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        } else {
            $imagePath = null; // Pas d'image uploadée
        }

        // Créer un nouveau produit
        $product = new Produit();
        $product->nom = $request->nom;
        $product->prix = $request->prix;
        $product->quantite = $request->quantite;
        $product->categorie_id = $request->categorie_id; // Changez ici
        $product->description = $request->description;
        $product->image = $imagePath; // Enregistrer le chemin de l'image ou null si aucune image
        $product->save();

        // Rediriger vers la page des produits avec un message de succès
        return redirect()->route('shop.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $produits = Produit::with('categorie') // Chargement de la relation
            ->where('nom', 'like', "%{$search}%")
            ->orWhereHas('categorie', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%");
            })
            ->get();

        return view('produits.index', compact('produits'));
    }

    public function destroy(Produit $produit)
    {
        // Supprimer le produit
        $produit->delete();

        // Rediriger avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function update(Request $request, Produit $produit)
    {
        // Valider les données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'prix' => 'required|numeric|min:0.01',
            'quantite' => 'required|integer|min:1',
            'categorie_id' => 'required|exists:categorie,id', // Changez ici
            'image' => 'nullable|image|max:2048',
        ]);

        // Mettre à jour les informations du produit
        $produit->nom = $request->nom;
        $produit->description = $request->description;
        $produit->prix = $request->prix;
        $produit->quantite = $request->quantite;
        $produit->categorie_id = $request->categorie_id; // Changez ici

        // Gérer l'image si elle a été téléchargée
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $produit->image = 'images/' . $imageName;
        }

        $produit->save();

        // Rediriger avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }
}
