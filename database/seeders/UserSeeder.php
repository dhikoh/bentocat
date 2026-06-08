<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bentocat.id'],
            [
                'name' => 'Superadmin BentoCat',
                'password' => Hash::make('Bismillah@bentocat'),
                'role' => 'superadmin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@bentocat.id'],
            [
                'name' => 'Editor BentoCat',
                'password' => Hash::make('Bismillah@bentocat'),
                'role' => 'editor'
            ]
        );

        User::updateOrCreate(
            ['email' => 'contributor@bentocat.id'],
            [
                'name' => 'Contributor BentoCat',
                'password' => Hash::make('Bismillah@bentocat'),
                'role' => 'contributor'
            ]
        );

        User::updateOrCreate(
            ['email' => 'marketing@bentocat.id'],
            [
                'name' => 'Marketing BentoCat',
                'password' => Hash::make('Bismillah@bentocat'),
                'role' => 'marketing'
            ]
        );
    }
}
