<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(JabatanTableSeeder::class);
        $this->call(KomponenTableSeeder::class);
        $this->call(LevelTableSeeder::class);
        $this->call(KaryawanTableSeeder::class);
    }
}
