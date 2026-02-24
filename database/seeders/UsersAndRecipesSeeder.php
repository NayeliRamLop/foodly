<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Categories;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersAndRecipesSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunas categorías y subcategorías existentes
        $categories = Categories::all();
        $subcategories = Subcategory::all();

        // Usuarios a crear (incluyendo Jessica y Fernando)
        $users = [
            [
                'name' => 'Jessica',
                'last_name' => 'Gómez',
                'email' => 'jessica@mail.com',
                'gender' => 'female',
                'phone' => '5551234567',
                'country' => 'México'
            ],
            [
                'name' => 'Fernando',
                'last_name' => 'Hernández',
                'email' => 'fernando@mail.com',
                'gender' => 'male',
                'phone' => '5557654321',
                'country' => 'España'
            ],
            [
                'name' => 'María',
                'last_name' => 'López',
                'email' => 'maria@mail.com',
                'gender' => 'female',
                'phone' => '5559876543',
                'country' => 'Argentina'
            ],
            [
                'name' => 'Carlos',
                'last_name' => 'Martínez',
                'email' => 'carlos@mail.com',
                'gender' => 'male',
                'phone' => '5554567890',
                'country' => 'Colombia'
            ],
            [
                'name' => 'Laura',
                'last_name' => 'Rodríguez',
                'email' => 'laura@mail.com',
                'gender' => 'female',
                'phone' => '5556789012',
                'country' => 'Chile'
            ]
        ];

        foreach ($users as $userData) {
            // Crear usuario
            $user = User::create([
                'name' => $userData['name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'), // Todos con contraseña 'password'
                'gender' => $userData['gender'],
                'phone' => $userData['phone'],
                'country' => $userData['country'],
                'registration_date' => Carbon::now(),
                'email_verified_at' => Carbon::now()
            ]);

            // Crear 5 recetas para cada usuario
            for ($i = 1; $i <= 5; $i++) {
                $category = $categories->random();
                $subcategory = $subcategories->where('category_id', $category->id)->random();

                Recipe::create([
                    'recipe_title' => "Receta {$i} de {$userData['name']}",
                    'recipe_description' => "Esta es la descripción de la receta {$i} creada por {$userData['name']}. Una deliciosa opción para cualquier ocasión.",
                    'ingredients' => "Ingrediente 1\nIngrediente 2\nIngrediente 3\nIngrediente 4",
                    'instructions' => "Paso 1: Hacer esto\nPaso 2: Hacer aquello\nPaso 3: Finalizar",
                    'preparation_time' => rand(15, 120),
                    'cooking_timer' => rand(10, 60),
                    'difficulty' => ['Fácil', 'Media', 'Difícil'][rand(0, 2)],
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'status' => 1
                ]);
            }
        }

        $this->command->info('5 usuarios con 5 recetas cada uno creados exitosamente!');
    }
}