<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SistemaCuerpoSeeder::class,
            OrganoSeeder::class,
            EnfermedadSintomaSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
