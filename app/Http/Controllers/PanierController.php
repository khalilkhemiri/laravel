<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CommandeProduit;
use Illuminate\Http\Request;
use App\Models\Produit;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PanierController extends Controller
{
    public function ajouter($id)
    {
        $produit = Produit::find($id);
        
        if (!$produit) {
            return redirect()->back()->with('error', 'Produit non trouvé.');
        }

        // Get the current cart from the session or initialize a new array
        $panier = session()->get('panier', []);

        // Check if the product is already in the cart
        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            // Add the product to the cart
            $panier[$id] = [
                'nom' => $produit->nom,
                'quantite' => 1,
                'prix' => $produit->prix,
                'image' => $produit->image ?? 'default-image-url', // Use default if no image exists
                'categorie' => $produit->categorie->nom ?? 'Inconnu', // Assuming there's a relationship with categories
            ];
        }

        session()->put('panier', $panier);
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }

    public function afficher()
    {
        $panier = session()->get('panier', []);

        // Calculate the total price of all items
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        return view('panier.afficher', compact('panier', 'total'));
    }

    public function supprimer($id)
    {
        $panier = session()->get('panier', []);

        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        session()->put('panier', $panier);
        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    public function passerCommande(Request $request)
    {
        $panier = session()->get('panier', []);

        if (count($panier) == 0) {
            return redirect()->route('panier.afficher')->with('error', 'Votre panier est vide.');
        }

        // Créer une nouvelle commande
        $commande = new Commande();
        $commande->user_id = auth()->id();
        $commande->total = $request->input('total'); // Assurez-vous d'avoir une colonne 'total' dans votre table Commande
        $commande->save();

        foreach ($panier as $id => $produit) {
            CommandeProduit::create([
                'commande_id' => $commande->id,
                'produit_id' => $id,
                'quantite' => $produit['quantite'],
                'prix' => $produit['prix'],
            ]);
        }

        session()->forget('panier');

        // Renvoyer un message de succès
        return response()->json(['message' => 'Commande passée avec succès.']);
    }

  
public function checkout()
{
    return view('checkout');
}

public function session(Request $request)

{
    $panier = session()->get('panier', []);

    if (count($panier) == 0) {
        return redirect()->route('panier.afficher')->with('error', 'Votre panier est vide.');
    }
    $commande = new Commande();
        $commande->user_id = auth()->id();
        $commande->total = $request->input('total'); 
        $commande->save();

        foreach ($panier as $id => $produit) {
            CommandeProduit::create([
                'commande_id' => $commande->id,
                'produit_id' => $id,
                'quantite' => $produit['quantite'],
                'prix' => $produit['prix']*$produit['quantite'],
            ]);
        }

        session()->forget('panier');

        // Renvoyer un message de succès
    \Stripe\Stripe::setApiKey('sk_test_51P88OSP7whfjob9cFTeFZowSAubEgI0RCcKmVBXRBR47qTc5FpCiWyEEmkYeWZMd0I4COkP0TRd73jExA0D6ugNR004UJqZkWX');

    $productname = $request->get('productname');
    
    $totalPanier = $commande->total * 100; // Convertir en centimes

    $session = \Stripe\Checkout\Session::create([
        'line_items'  => [
            [
                'price_data' => [
                    'currency'     => 'USD',
                    'product_data' => [
                        'name' => 'Commande n°' . $commande->id, // Nom du produit ou référence de la commande
                    ],
                    'unit_amount'  =>$totalPanier,
                ],
                'quantity'   => 1,
            ],
             
        ],
        'mode'        => 'payment',
        'success_url' => route('success'),
        'cancel_url'  => route('checkout'),
    ]);

    return redirect()->away($session->url);
}

public function success()
{
    return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
}
}