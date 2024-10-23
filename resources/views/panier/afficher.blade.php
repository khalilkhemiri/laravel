@extends('layouts.app')

@section('content')
<section class="h-100 h-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h1 class="fw-bold mb-0">Votre Panier</h1>
                                        <h6 class="mb-0 text-muted">{{ count($panier) }} items</h6>
                                    </div>
                                    <hr class="my-4">

                                    @foreach ($panier as $id => $produit)
                                    <div class="row mb-4 d-flex justify-content-between align-items-center">
                                        <div class="col-md-2 col-lg-2 col-xl-2">
                                            <img src="{{ asset($produit['image']) ?? 'default-image-url' }}" class="img-fluid rounded-3" alt="{{ $produit['nom'] }}">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-3">
                                            <h6 class="text-muted">{{ $produit['categorie'] ?? 'Categorie' }}</h6>
                                            <h6 class="mb-0">{{ $produit['nom'] }}</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                            <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <input id="form1" min="0" name="quantite" value="{{ $produit['quantite'] }}" type="number" class="form-control form-control-sm" />

                                            <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                            <h6 class="mb-0">{{ $produit['prix'] }} €</h6>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                            <a href="{{ route('panier.supprimer', $id) }}" class="text-muted"><i class="fas fa-times"></i></a>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    @endforeach

                                    <div class="pt-5">
                                        <h6 class="mb-0"><a href="{{ route('shop.index') }}" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Retour à la boutique</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 bg-body-tertiary">
                                <div class="p-5">
                                    <h3 class="fw-bold mb-5 mt-2 pt-1">Résumé</h3>
                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-4">
                                        <h5 class="text-uppercase">Items {{ count($panier) }}</h5>
                                        <h5>€ {{ $total }}</h5>
                                    </div>

                                    <h5 class="text-uppercase mb-3">Livraison</h5>

                                    <div class="mb-4 pb-2">
                                        <select class="form-select">
                                            <option value="1">Standard - €5.00</option>
                                            <option value="2">Express - €10.00</option>
                                        </select>
                                    </div>

                                    <h5 class="text-uppercase mb-3">Code Promo</h5>

                                    <div class="mb-5">
                                        <div class="form-outline">
                                            <input type="text" id="promoCode" class="form-control form-control-lg" />
                                            <label class="form-label" for="promoCode">Entrez votre code</label>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-5">
                                        <h5 class="text-uppercase">Prix Total</h5>
                                        <h5>€ {{ $total + 5.00 }}</h5> <!-- Assuming standard delivery cost included -->
                                    </div>
                                    <form action="/session" method="POST">

                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="total" value="{{ $total + 5.00 }}"> <!-- Passer le total à Stripe -->

                                    <button class="btn btn-success" type="submit" id="checkout-live-button"><i class="fa fa-money"></i> Passer Commande</button>

                                    </form>

                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
