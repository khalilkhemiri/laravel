@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Payer votre commande</h1>
    <h3>Total : €{{ $total }}</h3>

    <form id="payment-form">
        <div id="card-element"><!-- A Stripe Element will be inserted here. --></div>
        <button id="submit">Payer</button>
        <div id="card-errors" role="alert"></div>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('pk_test_51P88OSP7whfjob9c7Jwc06cN4c3Xqvn3zaKa8PtDSYxHTZJ47uPoV4T2SVtFWOgJkjnlxBIage2odpR4iFybPesS00TEVEqbeY'); // Votre clé publique Stripe
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        // Gérer le formulaire de soumission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.confirmCardPayment('{{ $clientSecret }}', {
                payment_method: {
                    card: cardElement,
                }
            }).then(function(result) {
                if (result.error) {
                    // Afficher l'erreur à l'utilisateur
                    document.getElementById('card-errors').textContent = result.error.message;
                } else {
                    // Le paiement a réussi, vous pouvez rediriger ou faire autre chose
                    if (result.paymentIntent.status === 'succeeded') {
                        // Rediriger ou afficher un message de succès
                        alert('Paiement réussi !');
                    }
                }
            });
        });
    </script>
</div>
@endsection
