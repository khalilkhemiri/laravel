<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class MagasinController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Produit::query();

        // Recherche par nom
        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Filtrer par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('categorie_id', $request->categorie); // Assurez-vous d'utiliser l'ID de la catégorie
        }

        // Filtrer par prix
        if ($request->has('prix_min') && $request->prix_min != '') {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->has('prix_max') && $request->prix_max != '') {
            $query->where('prix', '<=', $request->prix_max);
        }

        $produits = $query->paginate(9); // Pagination à 9 produits par page

        // Obtenir toutes les catégories pour le filtre
        $categories = Categorie::all(); // Récupérer toutes les catégories

        return view('magasin.index', compact('produits', 'categories'));
    }

}
