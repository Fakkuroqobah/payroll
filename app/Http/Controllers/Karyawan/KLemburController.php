<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use DateTime;
use Auth;

class KLemburController extends Controller
{
    public function start(Request $request)
    {
        $now = new DateTime();
        $formatStartTime = $now->format('H:i:s');
        if($formatStartTime >= '08:00:00' && $formatStartTime <= '17:00:00') {
            return $this->res(422, 'Gagal', "Bukan waktu lembur");
        }

        $lemburTerbaru = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->orderBy('id', 'DESC')->first();
        if(!is_null($lemburTerbaru)) { // start = false; stop = true;
            if(is_null($lemburTerbaru->total_jam_hadir)) {
                return $this->res(422, 'Gagal', "Anda tidak bisa lembur sebelum waktu kerja di stop");
            }
        }

        try {
            $data = Absensi::create([
                'tanggal' => now(),
                'mulai_hadir' => now(),
                'jenis' => 'l',
                'karyawan_id' => Auth::user()->id,
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function stop(Request $request)
    {
        $data = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'l')->orderBy('id', 'DESC')->firstOrFail();
        try {
            $start = new DateTime($data->mulai_hadir);
            $startModify1 = new DateTime($data->mulai_hadir);
            $startModify2 = new DateTime($data->mulai_hadir);
            $end = new DateTime();

            // $start = new DateTime('2022-04-02 21:00:00');
            // $startModify1 = new DateTime('2022-04-02 21:00:00');
            // $startModify2 = new DateTime('2022-04-02 21:00:00');
            // $end = new DateTime('2022-04-04 01:00:00');

            $formatStartDay = $start->format('Y-m-d');
            $formatEndDay = $end->format('Y-m-d');
            $formatStartTime = $start->format('H:i:s');
            $formatEndTime = $end->format('H:i:s');
            $increaseStartOneDay = $startModify1->modify('+1 day')->format('Y-m-d');
            $increaseStartTwoDay = $startModify2->modify('+2 day')->format('Y-m-d');

            // Testing
            // 1. Mulai = 19:00:00; Selesai = 02:00:00; Hasil = 7 Jam
            // 2. Mulai = 00:00:00; Selesai = 04:00:00; Hasil = 4 Jam
            
            // 3. Mulai = 2022-04-01 19:00:00; Selesai = 2022-04-02 12:00:00; Hasil = 13 Jam
            // 4. Mulai = 2022-04-02 01:00:00; Selesai = 2022-04-02 12:00:00; Hasil = 7 Jam
            
            // 5. Mulai = 2022-04-02 21:00:00; Selesai = 2022-04-04 01:00:00; Hasil = 11 Jam
            // 6. Mulai = 2022-04-02 21:00:00; Selesai = 2022-04-04 09:00:00; Hasil = 11 Jam
            
            // 7. Mulai = 2022-04-02 01:00:00; Selesai = 2022-04-04 21:00:00; Hasil = 7 Jam
            // 8. Mulai = 2022-04-02 01:00:00; Selesai = 2022-04-04 09:00:00; Hasil = 7 Jam

            $detik = $this->dateTimeToSecond($start, $end);

            if($formatEndTime >= '08:00:00' && $formatEndTime <= '17:00:00') {
                if($formatStartTime >= '00:00:00' && $formatStartTime < '08:00:00') $limit = new DateTime($formatStartDay . ' 08:00:00');
                else $limit = new DateTime($increaseStartOneDay . ' 08:00:00');

                $detik = $this->dateTimeToSecond($start, $limit);
            }

            if($formatEndDay >= $increaseStartTwoDay) {
                if($formatStartTime >= '00:00:00' && $formatStartTime < '08:00:00') $limit = new DateTime($formatStartDay . ' 08:00:00');
                else $limit = new DateTime($increaseStartOneDay . ' 08:00:00');
                
                $detik = $this->dateTimeToSecond($start, $limit);
            }

            $data->selesai_hadir = $end;
            $data->total_jam_hadir = $detik;
            $data->save();

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }
}
