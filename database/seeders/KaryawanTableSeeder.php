<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanTableSeeder extends Seeder
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
                'nama' => 'Canggih Puspo Wibowo',
                'pendidikan' => 'S2 Teknik Informatika, King Mongkut Univ. Thailand',
                'jk' => 'laki-laki',
                'no_hp' => '089610868608',
                'alamat' => 'Kalasan, Sleman, Yogyakarta',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat rencana tahunan yang dievalusi setiap 3 bulan untuk pengembangan produk; Menentukan teknologi yang digunakan oleh perusahaan dalam pengembangan produk; Mengembangan produk yang sesuai dengan pasar dan menjadi yang terbaik dibandingkan kompetitor; Melakukan riset produk dan teknologi yang digunakan oleh kompetitor yang diupdate setiap 3 bulan; Mewakili perusahaan untuk melakukan presentasi terkait dengan keunggulan produk dan teknologi yang digunakan oleh perushaan terhadap potential client; Berkordinasi dengan COO dan CMO untuk menentukan target produk dan sasaran pasar; Membuat laporan bulanan tertulis yang disampaikan pada BOD',
                'npwp' => '86.124.922.5-542.000',
                'bank' => 'BCA',
                'no_rek' => '4451820235',
                'awal_kontrak' => '2021-01-01',
                'akhir_kontrak' => '2021-12-31',
                'foto' => null,
                'email' => 'canggih@mail.com',
                'telegram_id' => '49413251',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 2,
                'level_id' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Ujang Fahmi',
                'pendidikan' => 'S2-MKP, Universitas Gadjah Mada',
                'jk' => 'laki-laki',
                'no_hp' => '081226622504',
                'alamat' => 'Gg. Anggur No. 88, Krodan, Maguwoharjo, Sleman, Yogyakarta',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat rencana tahunan; Menentukan strategi market tahunan; Menetapkan target tahunan yang ingin dicapai perusahaan; Membuat keputusan alokasi resource perusahaan; Melakukan presentasi untuk mengenalkan produk; Memastikan perusahaan tetap berjalan dan berkembang',
                'npwp' => '86.124.922.5-542.000',
                'bank' => 'MANDIRI',
                'no_rek' => '9000010211358',
                'awal_kontrak' => '2022-01-10',
                'akhir_kontrak' => '2023-01-09',
                'foto' => null,
                'email' => 'ujangfahmi@kedata.online',
                'telegram_id' => '175197155',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 1,
                'level_id' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Rika Anggoro Prasetiya',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '082134148481',
                'alamat' => 'Sumberharjo, Prambanan, Sleman.',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat rencana marketing tahunan; Menentukan strategi pemasaran; Mengeksekusi rencana marketing tahunan dan strategi pemasaran; Membuat laporan bulanan tertulis yang disampaikan pada BOD; Berkordinasi dengan COO untuk membuat initiative-initiative baru pemasaran; Berkordinasi dengan tim produk setiap 3 bulan untuk memberikan insight produk; Berkordinasi dengan COO dan CTO untuk presentasi produk tahap lanjutan',
                'npwp' => '76.978.633.6-542.000',
                'bank' => 'BCA',
                'no_rek' => '4451828554',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'anggoro@kedata.online',
                'telegram_id' => '734923133',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 3,
                'level_id' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Zulfakhri Auzar',
                'pendidikan' => 'S1 Akuntansi, S2 Perencanaan Pembangunan dan Keuangan Daerah',
                'jk' => 'laki-laki',
                'no_hp' => '082226193393',
                'alamat' => 'Perum SPA, Blok A, No. 27, Selo Martani, Kledokan, Kalasan, Sleman, DIY',
                'jam_kerja' => 'full time',
                'tugas' => 'Menerjemahkan rencana tahunan CEO menjadi rencana strategis dengan seluruh tim yang terlibat; Mengeksekusi strategi yang dibuat oleh top management; Memastikan perusahaan berjalan sesuai dengan peraturan yang berlaku; Berkordinasi dengan tim developer untuk memastikan produk perusahaan sesuai dengan target; Berkordinasi dengan CMO dan marekting untuk melihat market vit produk dan peluang pasar; Membuat standar adminstrasi perusahaan dan memastikan administrasi perusahaan sesuai dengan standar yang ditetapkan; Menyelenggarakan rapat rutin bulanan untuk mendapatkan laporan dari tiap divisi; Membuat laporan bulanan tertulis yang disampaikan pada BOD',
                'npwp' => null,
                'bank' => 'BCA',
                'no_rek' => '4472456324',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'zulfakhriauzar@gmail.com',
                'telegram_id' => '812221726',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 4,
                'level_id' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Ridwan Adjie S',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '081393946042',
                'alamat' => 'Boyolali, Jawa Tengah.',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat riset produk untuk mendesain produk yang dikembangkan/dikerjakan; Mempresentasikan desain produk pada bod/perwakilan cto dan produk manager; Membuat template desain publikasi media sosial yang mudah digunakan oleh TIM Marketing; Membuat template desain presentasi yang mudah digunakan oleh tim lain',
                'npwp' => '246412351684632000',
                'bank' => 'MANDIRI',
                'no_rek' => '1430298271',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'rassantoso@gmail.com',
                'telegram_id' => '316397893',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 5,
                'level_id' => 2,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Friandy Dwi Noviandha',
                'pendidikan' => 'S2',
                'jk' => 'laki-laki',
                'no_hp' => '085753079372',
                'alamat' => '',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat frontend untuk produk/proyek yang dibuat perusahaan; Membuat dokumentasi frontend produk/proyek yang dikerjakan; Membuat laporan bulanan pada produk manager terkait dengan pembangunan produk secara tertulis yang memuat progress, kendala, dan solusi yang telah diterapkan',
                'npwp' => '82.287.952.4-722.000',
                'bank' => 'MANDIRI',
                'no_rek' => '1480013943868',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'freyandhy@gmail.com',
                'telegram_id' => '316397893',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 6,
                'level_id' => 3,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Rudi Sarwiyana',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '895358428890',
                'alamat' => 'Bantul, Yogyakarta.',
                'jam_kerja' => 'full time',
                'tugas' => 'Mengeksekusi pembuatan produk/pekerjaan sesuai dengan rencana yang ditetapkan; Memastikan pekerjaan sesuai dengan timeline yang ditentukan; Berkordinasi dengan CMO dan COO untuk memberikan laporan pekerjaan pada klien; Membuat laporan bulanan tertulis yang disampaikan pada BOD',
                'npwp' => '96.168.814.0-543.000',
                'bank' => 'BNI',
                'no_rek' => '847011838',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'rudiwowot@gmail.com',
                'telegram_id' => '517055276',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 7,
                'level_id' => 2,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Faturahman Yudanto',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '082323579174',
                'alamat' => 'Kulonprogo, Yogyakarta.',
                'jam_kerja' => 'full time',
                'tugas' => 'Membantu tim developer jika diperlukan; Membantu CDAO untuk pembuatan blue print; Membuat riset dan model-model machine learning; Melakukan pengambilan data dan analisis yang dibutuhkan untuk klien',
                'npwp' => null,
                'bank' => 'BNI',
                'no_rek' => '501008626252427',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'rudiwowot@gmail.com',
                'telegram_id' => '764447531',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 8,
                'level_id' => 2,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Dedi Irawan',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '085728767573',
                'alamat' => 'Pilangsari Rt 24 Rw 11 Gondang Kebonarum Klaten',
                'jam_kerja' => 'part time',
                'tugas' => 'Membuat frontend untuk produk/proyek yang dibuat perusahaan; Membuat dokumentasi frontend produk/proyek yang dikerjakan; Membuat laporan bulanan pada produk manager terkait dengan pembangunan produk secara tertulis yang memuat progress, kendala, dan solusi yang telah diterapkan',
                'npwp' => null,
                'bank' => 'MANDIRI',
                'no_rek' => '1370016039204',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'irawand07@gmail.com',
                'telegram_id' => null,
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 9,
                'level_id' => 3,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Arief Rahman Wijaya',
                'pendidikan' => 'S1',
                'jk' => 'laki-laki',
                'no_hp' => '089633690101',
                'alamat' => 'JL.M.ALI GG.SAUDARA, NO.103, RT 003 RW 007, PERAWANG BARAT, TUALANG, SIAK, RIAU',
                'jam_kerja' => 'part time',
                'tugas' => 'Membuat backend untuk produk yang dibuat; Membuat dokumentasi backend produk/proyek yang dikerjakan; Membuat laporan bulanan pada produk manager terkait dengan pembangunan produk secara tertulis yang memuat progress, kendala, dan solusi yang telah diterapkan',
                'npwp' => null,
                'bank' => 'MANDIRI SYARIAH',
                'no_rek' => '7114354842',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'rivujr@gmail.com',
                'telegram_id' => null,
                'tingkat' => 'junior',
                'status' => 1,
                'jabatan_id' => 10,
                'level_id' => 3,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Jaza Anil Husna',
                'pendidikan' => 'S1-Akuntansi',
                'jk' => 'laki-laki',
                'no_hp' => '085726084127',
                'alamat' => 'Maguwoharjo, Sleman. DIY',
                'jam_kerja' => 'full time',
                'tugas' => 'Mengurus semua administrasi perusahaan (penggajian, persuratan, dan perpajakan); Mengurus keuangan perusahaan',
                'npwp' => null,
                'bank' => 'BRI',
                'no_rek' => '585201025414533',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'jaza.anil21@gmail.com',
                'telegram_id' => '1778487492',
                'tingkat' => 'senior',
                'status' => 1,
                'jabatan_id' => 11,
                'level_id' => 3,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Alfian Andi',
                'pendidikan' => 'S1 Informatika Universitas Teknologi Yogyakarta',
                'jk' => 'laki-laki',
                'no_hp' => '081314581924',
                'alamat' => 'Kos Gala RW3 RT4, Gedongan, Kec. Mlati, Kab. Sleman, DIY',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat Frontend Kalkula Integrator',
                'npwp' => null,
                'bank' => 'BNI',
                'no_rek' => '849129112',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'alfian.23andi@gmail.com',
                'telegram_id' => '1778487492',
                'tingkat' => 'junior',
                'status' => 1,
                'jabatan_id' => 9,
                'level_id' => 4,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Agung Maulana',
                'pendidikan' => 'S1 Informatika Universitas Teknologi Yogyakarta',
                'jk' => 'laki-laki',
                'no_hp' => '085156188699',
                'alamat' => 'Kos Pak Parno, Kec. Mlati, Kab. Sleman, DIY',
                'jam_kerja' => 'full time',
                'tugas' => 'Membuat Backend untuk Kerjabaik Gudang',
                'npwp' => null,
                'bank' => 'BRI',
                'no_rek' => '1829918212',
                'awal_kontrak' => '2022-01-09',
                'akhir_kontrak' => '2023-01-10',
                'foto' => null,
                'email' => 'maulanaagung169@gmail.com',
                'telegram_id' => '172881728',
                'tingkat' => 'junior',
                'status' => 1,
                'jabatan_id' => 10,
                'level_id' => 4,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $row) {
            Karyawan::create($row);
        }
    }
}
