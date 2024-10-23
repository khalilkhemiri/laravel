<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',   // Ajoutez 'commande_id' ici
        'produit_id',
        'quantite',
        'prix',
    ];
}
