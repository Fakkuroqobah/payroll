<?php

namespace Database\Seeders;

use App\Models\Komponen;
use Illuminate\Database\Seeder;

class KomponenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'gaji_absen' => 25000,
                'gaji_lembur' => 30000,
                'gaji_pokok' => 5000000,
                'jabatan_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $row) {
            Komponen::create($row);
        }
    }
}
