<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Superadmin BentoCat',
            'email' => 'admin@bentocat.com',
            'password' => Hash::make('Bismillah@bentocat'),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Editor BentoCat',
            'email' => 'editor@bentocat.com',
            'password' => Hash::make('Bismillah@bentocat'),
            'role' => 'editor'
        ]);

        User::create([
            'name' => 'Contributor BentoCat',
            'email' => 'contributor@bentocat.com',
            'password' => Hash::make('Bismillah@bentocat'),
            'role' => 'contributor'
        ]);
    }
}
