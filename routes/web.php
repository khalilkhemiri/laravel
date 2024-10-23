<?php 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JardinController;
use App\Http\Controllers\EvenementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MagasinController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Events\UserActivity;


Route::resource('jardins', JardinController::class);
Route::resource('evenements', EvenementController::class);

Route::get('/', function () {
    return view('home'); 
});


Route::get('/evenements/{id}/details', [EvenementController::class, 'frontDetaille'])->name('evenements.frontDetaille');


Route::get('/admin', [AdminController::class, 'index'])->name('back.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/jardins', [JardinController::class, 'index'])->name('gardens.index');

Route::get('/jardins/create', [JardinController::class, 'create'])->name('gardens.create');

Route::post('/jardins', [JardinController::class, 'store'])->name('gardens.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'track.user.activity'])->group(function () {
Route::get('/plants', [PlantController::class, 'index'])->name('plants.index');
Route::get('/plants/details/{id}', [PlantController::class, 'show'])->name('plants.details');
});

Route::post('/broadcasting/auth', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    // Use the Broadcast facade to authorize the user
    return Broadcast::auth($request);
})->middleware('auth');



Route::middleware('auth')->group(function () {
    Route::post('/advice', [AdviceController::class, 'store'])->name('advice.store');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
});


Route::middleware('auth')->group(function () {
    Route::resource('admin/advices', AdviceController::class)->except(['create', 'show', 'store']); // Exclude non-admin actions
    Route::get('/admin/advices', [AdviceController::class, 'index'])->name('admin.advices.index'); // List all advices
    Route::get('/admin/advices/{id}/edit', [AdviceController::class, 'edit'])->name('admin.advices.edit'); // Edit specific advice
    Route::patch('/admin/advices/{id}', [AdviceController::class, 'update'])->name('admin.advices.update'); // Update specific advice
    Route::delete('/admin/advices/{id}', [AdviceController::class, 'destroy'])->name('admin.advices.destroy'); // Delete specific advice

    Route::resource('admin/ratings', RatingController::class)->except(['create', 'show', 'store']); // Exclude non-admin actions
    Route::get('/admin/ratings', [RatingController::class, 'index'])->name('admin.ratings.index'); // List all ratings
    Route::get('/admin/ratings/{id}/edit', [RatingController::class, 'edit'])->name('admin.ratings.edit'); // Edit specific rating
    Route::patch('/admin/ratings/{id}', [RatingController::class, 'update'])->name('admin.ratings.update'); // Update specific rating
    Route::delete('/admin/ratings/{id}', [RatingController::class, 'destroy'])->name('admin.ratings.destroy'); // Delete specific rating
});





Route::get('/test-broadcast', function () {
    $user = auth()->user(); // or any user object you want to pass
    broadcast(new UserActivity($user)); // Pass the user as an argument
    return 'Broadcasting event!';
});

Route::post('/update-activity', [AuthenticatedSessionController::class, 'updateActivity'])->name('update-activity');



// Routes pour les jardins

Route::resource('admin/plants', PlantController::class)->except(['show']); // Exclude public viewing
Route::get('/plants', [PlantController::class, 'index'])->name('plants.index'); // List all plants
Route::get('/plants/create', [PlantController::class, 'create'])->name('plants.create'); // Form to create a plant
Route::post('/plants', [PlantController::class, 'store'])->name('plants.store'); // Store a new plant
Route::get('/plants/{plant}/edit', [PlantController::class, 'edit'])->name('plants.edit'); // Edit specific plant
Route::patch('/plants/{plant}', [PlantController::class, 'update'])->name('plants.update'); // Update specific plant
Route::delete('/plants/{plant}', [PlantController::class, 'destroy'])->name('plants.destroy'); // Delete specific plant


// Routes pour le tableau de bord admin pour les jardins
Route::get('/admin/jardins', [JardinController::class, 'indexAdmin'])->name('gardens.index.admin');
Route::get('/admin/jardins/create', [JardinController::class, 'createAdmin'])->name('gardens.create.admin');
Route::post('/admin/jardins', [JardinController::class, 'storeAdmin'])->name('gardens.store.admin');
Route::get('/admin/jardins/{jardin}/edit', [JardinController::class, 'editAdmin'])->name('gardens.edit.admin');
Route::put('/admin/jardins/{jardin}', [JardinController::class, 'updateAdmin'])->name('gardens.update.admin');
Route::delete('/admin/jardins/{jardin}', [JardinController::class, 'destroyAdmin'])->name('gardens.destroy.admin');
// Routes pour le tableau de bord admin pour les événements
Route::get('/admin/evenements', [EvenementController::class, 'indexAdmin'])->name('evenements.index.admin');
Route::get('/admin/evenements/create', [EvenementController::class, 'createAdmin'])->name('evenements.create.admin');
Route::post('/admin/evenements', [EvenementController::class, 'storeAdmin'])->name('evenements.store.admin');
Route::get('/admin/evenements/{evenement}/edit', [EvenementController::class, 'editAdmin'])->name('evenements.edit.admin');
Route::put('/admin/evenements/{evenement}', [EvenementController::class, 'updateAdmin'])->name('evenements.update.admin');
Route::delete('/admin/evenements/{evenement}', [EvenementController::class, 'destroyAdmin'])->name('evenements.destroy.admin');
Route::get('/admin/calendrier', [EvenementController::class, 'calendrierAdmin'])->name('evenements.calendrier.admin');
Route::get('/admin/evenements/{id}', [EvenementController::class, 'show'])->name('evenements.show');




// Routes for plants
Route::middleware('auth')->group(function () {
    Route::get('/admin/plants', [PlantController::class, 'indexAdmin'])->name('plants.index.admin');
    Route::get('/admin/plants/create', [PlantController::class, 'createAdmin'])->name('plants.create.admin');
    Route::post('/admin/plants', [PlantController::class, 'storeAdmin'])->name('plants.store.admin');
    Route::get('/admin/plants/{plant}/edit', [PlantController::class, 'editAdmin'])->name('plants.edit.admin');
    Route::put('/admin/plants/{plant}', [PlantController::class, 'updateAdmin'])->name('plants.update.admin');
    Route::delete('/admin/plants/{plant}', [PlantController::class, 'destroyAdmin'])->name('plants.destroy.admin');
});




// Routes for categories
Route::middleware('auth')->group(function () {
    // Routes for categories
    Route::resource('categories', CategorieController::class); // Resource routes for categories
});



// route khalil

Route::get('/magasin', [MagasinController::class, 'index'])->name('shop.index');
Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::get('/panier', [PanierController::class, 'afficher'])->name('panier.afficher');
Route::get('/panier/supprimer/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::get('/commande/passer', [PanierController::class, 'passerCommande'])->name('commande.passer');
Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
Route::get('/produit', [ProduitController::class, 'index'])->name('produits.index');
Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');
Route::post('/create-payment-intent', [PanierController::class, 'createPaymentIntent'])->name('payment.intent');
Route::get('/panier', [PanierController::class, 'afficher'])->name('panier.afficher');
Route::post('/panier/passer-commande', [PanierController::class, 'passerCommande'])->name('panier.passerCommande');
Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
Route::resource('produits', ProduitController::class);
Route::resource('categories', CategoryController::class);

Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/checkout', 'App\Http\Controllers\PanierController@checkout')->name('checkout');
Route::post('/session', 'App\Http\Controllers\PanierController@session')->name('session');
Route::get('/success', 'App\Http\Controllers\PanierController@success')->name('success');










// Admin routes for categories
Route::middleware('auth')->group(function () {
    // Admin routes for categories
    Route::get('/admin/categories', [CategorieController::class, 'indexAdmin'])->name('categories.index.admin');
    Route::get('/admin/categories/create', [CategorieController::class, 'createAdmin'])->name('categories.create.admin');
    Route::post('/admin/categories', [CategorieController::class, 'storeAdmin'])->name('categories.store.admin');
    Route::get('/admin/categories/{category}/edit', [CategorieController::class, 'editAdmin'])->name('categories.edit.admin');
    Route::put('/admin/categories/{category}', [CategorieController::class, 'updateAdmin'])->name('categories.update.admin');
    Route::delete('/admin/categories/{category}', [CategorieController::class, 'destroyAdmin'])->name('categories.destroy.admin');
});


require __DIR__.'/auth.php';
