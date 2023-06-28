<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nama' => 'Chief Executive Officer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Chief Technology Officer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Chief Marketing Officer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Chief Operating Officer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'UI/UX Designer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Head of Egineering', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Product Manager', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Full Stack', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Frontend Developer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Backend Engineer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Admin and Accounting', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($data as $row) {
            Jabatan::create($row);
        }
    }
}
