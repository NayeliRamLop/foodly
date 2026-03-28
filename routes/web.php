    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Auth\{
        ForgotPasswordController,
        ResetPasswordController,
        AuthenticatedSessionController
    };
use App\Http\Controllers\{
    AdminAnalyticsController,
    UserController,
    GuestRecipeAuthController,
    ProfileController,
    HomeController,
    CategoriesController,
    AssetController,
    GeneradorController,
    NotificationController,
    RecipeController,
    FavoriteController,
    SearchController
};

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return app(\App\Http\Controllers\HomeController::class)->landing();
});

Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');
Route::get('/recipes/suggest', [RecipeController::class, 'suggest'])->name('recipes.suggest');
Route::post('/recipes/{recipe}/rate', [RecipeController::class, 'rate'])->middleware('auth')->name('recipes.rate');
Route::post('/recipes/{recipe}/comments', [RecipeController::class, 'addComment'])->middleware('auth')->name('recipes.comments');
Route::get('/perfil/{user}', [ProfileController::class, 'showPublic'])->name('profile.public');
Route::post('/perfil/{user}/follow', [ProfileController::class, 'toggleFollow'])->middleware('auth')->name('profile.follow');

    // Rutas de autenticación personalizadas
    Route::middleware('guest')->group(function () {
        // Login personalizado
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);

        // Restablecimiento de contraseña
        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
             ->name('password.request');

        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
             ->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
             ->name('password.reset');

       Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


        // Registro sin login
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('user', [UserController::class, 'store'])->name('user.store');
        Route::post('recipe-auth/register', [GuestRecipeAuthController::class, 'register'])->name('guest.recipe-auth.register');
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
         ->middleware('auth')
         ->name('logout');

    // Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [SearchController::class, 'index'])->name('search.global');
    Route::middleware('can:user')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });

        // Gestión de usuarios
        Route::prefix('users')->group(function () {
            Route::resource('', UserController::class)->except(['create', 'store'])
                ->parameters(['' => 'user'])
                ->names('user');

            // Rutas para el avatar
            Route::put('/{user}/avatar', [UserController::class, 'updateAvatar'])
                ->name('user.avatar.update');
            Route::delete('/{user}/avatar', [UserController::class, 'deleteAvatar'])
                ->name('user.avatar.delete');
        });

    // Ruta del perfil
    Route::get('/user/perfil', [ProfileController::class, 'show'])
        ->name('user.perfil');

        // Otras rutas de recursos
        Route::resource('categories', CategoriesController::class);
        //cambio de contraseña
        Route::get('/profile/change-password', function () {
            return view('profile.change-password');
        })->name('profile.change-password');

        Route::post('/profile/change-password', function (Request $request) {
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) {
                    if (!\Illuminate\Support\Facades\Hash::check($value, auth()->user()->password)) {
                        $fail('La contraseña actual es incorrecta');
                    }
                }],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            $user = auth()->user();
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();

            return back()->with('status', 'Contraseña cambiada correctamente');
        })->name('profile.change-password.update');

       // Rutas para recetas
    Route::prefix('recipes')->name('recipes.')->group(function () {
        // Rutas básicas CRUD
        Route::get('/para-ti', [RecipeController::class, 'paraTi'])->name('para-ti');
        Route::get('/category/{category_id}', [RecipeController::class, 'porCategoria'])->name('por-categoria');
        Route::get('/subcategory/{subcategory_id}', [RecipeController::class, 'porSubcategoria'])->name('por-subcategory');
        Route::get('/mis-recetas', [RecipeController::class, 'misRecetas'])->name('mis-recetas');

        // Rutas principales
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
        Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');

        // Nuevas rutas para funcionalidades adicionales
        Route::get('/{recipe}/download', [RecipeController::class, 'download'])->name('download');
        Route::post('/{recipe}/upload-image', [RecipeController::class, 'uploadImage'])->name('upload-image');
        Route::post('/{recipe}/upload-video', [RecipeController::class, 'uploadVideo'])->name('upload-video');
        Route::delete('/{recipe}/remove-image', [RecipeController::class, 'removeImage'])->name('remove-image');
        Route::delete('/{recipe}/remove-video', [RecipeController::class, 'removeVideo'])->name('remove-video');

        // Rutas para favoritos (si están relacionadas con recetas)
        Route::post('/{recipe}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('toggle-favorite');
    });

        Route::resource('assets', AssetController::class);


    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/{recipe}', [FavoriteController::class, 'store'])->name('store');
        Route::delete('/{recipe}', [FavoriteController::class, 'destroy'])->name('destroy');
        Route::post('/toggle/{recipe}', [FavoriteController::class, 'toggle'])->name('toggle');
    });



        Route::prefix('files')->group(function () {
            Route::get('/imprimir', [GeneradorController::class, 'imprimir'])->name('print.general');
            Route::get('/userpdf', [GeneradorController::class, 'imprimirusuarios'])->name('print.users');
            Route::get('/video/{filename}', [AssetController::class, 'getVideo'])->name('assets.video');
            Route::get('/miniatura/{filename}', [AssetController::class, 'getImage'])->name('assets.thumbnail');
        });

        // Rutas de administración (solo para admin)
    // Rutas de administración
    Route::prefix('admin')->middleware(['auth', 'can:admin'])->group(function () {
        // Usuarios
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'adminIndex'])
             ->name('admin.users.index');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'deleteUser'])
             ->name('admin.users.delete');
             Route::get('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
             ->name('admin.users.toggle-status');
             Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
         ->name('user.toggle-status');

             Route::get('/recipes/{recipe}/toggle-status', [RecipeController::class, 'toggleStatus'])
         ->name('admin.recipes.toggle-status');
         Route::post('/recipes/{recipe}/toggle-status', [RecipeController::class, 'toggleStatus'])
        ->name('recipes.toggle-status');
        // Recetas
        Route::get('/recipes', [\App\Http\Controllers\RecipeController::class, 'adminIndex'])
             ->name('admin.recipes.index');
        Route::delete('/recipes/{recipe}', [\App\Http\Controllers\RecipeController::class, 'adminDelete'])
             ->name('admin.recipes.delete');
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])
             ->name('admin.analytics.index');
    });
    });
