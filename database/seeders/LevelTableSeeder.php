<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nama' => 'C-Level', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Manajer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Karyawan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Magang', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($data as $row) {
            Level::create($row);
        }
    }
}
