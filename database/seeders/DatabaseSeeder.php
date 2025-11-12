<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NewsCategory;
use App\Models\ForumCategory;
use App\Models\ShopCategory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Test User
        User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // News Categories
        NewsCategory::create(['name' => 'Allgemein', 'slug' => 'allgemein', 'color' => '#00ff00']);
        NewsCategory::create(['name' => 'Updates', 'slug' => 'updates', 'color' => '#00ffff']);
        NewsCategory::create(['name' => 'Events', 'slug' => 'events', 'color' => '#ff00ff']);

        // Forum Categories
        ForumCategory::create(['name' => 'Allgemein', 'description' => 'Allgemeine Diskussionen', 'color' => '#00ff00', 'order' => 1]);
        ForumCategory::create(['name' => 'Support', 'description' => 'Hilfe und Support', 'color' => '#00ffff', 'order' => 2]);
        ForumCategory::create(['name' => 'Off-Topic', 'description' => 'Alles andere', 'color' => '#ff00ff', 'order' => 3]);

        // Shop Categories
        ShopCategory::create(['name' => 'Ränge', 'slug' => 'raenge', 'order' => 1]);
        ShopCategory::create(['name' => 'Items', 'slug' => 'items', 'order' => 2]);
        ShopCategory::create(['name' => 'Pakete', 'slug' => 'pakete', 'order' => 3]);
    }
}

