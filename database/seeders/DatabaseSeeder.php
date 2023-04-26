<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()
            ->state([
                'name' => 'Administrador',
                'email' => 'administrador@laraveleando.com'
            ])
            ->administrator()
            ->create();

        User::factory()
            ->state([
                'name' => 'Empleado 1',
                'email' => 'empleado1@laraveleando.com'
            ])
            ->employee()
            ->create();

        User::factory()
            ->state([
                'name' => 'Empleado 2',
                'email' => 'empleado2@laraveleando.com'
            ])
            ->employee()
            ->create();
    }
}
