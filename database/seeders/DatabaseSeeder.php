<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $categoriesData = [
            'Aperitivos y Entradas' => [
                'Tapas españolas', 'Antipastos italianos', 'Canapés', 'Bruschettas', 
                'Dips y salsas', 'Enrollados', 'Tartaletas', 'Empanadillas','Otro'
            ],
            'Sopas y Cremas' => [
                'Sopas de verduras', 'Cremas calientes', 'Gazpachos', 'Sopas de pescado',
                'Sopas asiáticas', 'Consomés', 'Sopas de legumbres', 'Sopas de pasta','Otro'
            ],
            'Ensaladas' => [
                'Ensaladas verdes', 'Ensaladas de pasta', 'Ensaladas de legumbres',
                'Ensaladas de frutas', 'Ensaladas compuestas', 'Ensaladas templadas',
                'Ensaladas gourmet', 'Ensaladas proteicas','Otro'
            ],
            'Carnes' => [
                'Carnes rojas', 'Aves', 'Cerdo', 'Cordero', 'Caza', 
                'Hamburguesas', 'Albóndigas', 'Estofados','Otro'
            ],
            'Pescados y Mariscos' => [
                'Pescados blancos', 'Pescados azules', 'Mariscos', 'Moluscos',
                'Crustáceos', 'Sushi', 'Pescados al horno', 'Pescados a la parrilla','Otro'
            ],
            'Vegetarianos' => [
                'Legumbres', 'Tofu y tempeh', 'Seitán', 'Verduras al horno',
                'Quinoa', 'Hamburguesas veganas', 'Woks vegetales', 'Currys vegetales','Otro'
            ],
            'Pastas y Arroces' => [
                'Pastas italianas', 'Pastas rellenas', 'Risottos', 'Paellas',
                'Arroces caldosos', 'Pastas al horno', 'Fideos asiáticos', 'Arroces integrales','Otro'
            ],
            'Postres' => [
                'Tartas clásicas', 'Postres de chocolate', 'Helados artesanales',
                'Postres fríos', 'Flanes y cremas', 'Frutas caramelizadas',
                'Postres light', 'Postres veganos','Otro'
            ],
            'Panadería' => [
                'Panes artesanales', 'Bollos', 'Baguettes', 'Panecillos',
                'Pan integral', 'Pan de molde', 'Panecillos dulces', 'Pan rústico','Otro'
            ],
            'Bebidas' => [
                'Cócteles clásicos', 'Cócteles sin alcohol', 'Batidos', 
                'Smoothies', 'Jugos naturales', 'Infusiones', 'Licuados',
                'Bebidas energéticas naturales','Otro'
            ],
            'Cocina Internacional' => [
                'Mexicana', 'Italiana', 'Japonesa', 'China',
                'India', 'Mediterránea', 'Árabe', 'Peruana','Otro'
            ],
            'Repostería' => [
                'Galletas', 'Magdalenas', 'Cupcakes', 'Brownies',
                'Cheesecakes', 'Postres individuales', 'Tartaletas dulces', 'Bombones','Otro'
            ]
        ];

        foreach ($categoriesData as $categoryName => $subcategories) {
            // Evita crear duplicados
            $category = Categories::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );

            foreach ($subcategories as $subcategoryName) {
                Subcategory::updateOrCreate(
                    ['slug' => Str::slug($subcategoryName), 'category_id' => $category->id],
                    ['name' => $subcategoryName]
                );
            }
        }
        $this->call([
        DatabaseSeeder::class, // Tu seeder original de categorías
        UsersAndRecipesSeeder::class // El nuevo seeder
    ]);
    }
}
