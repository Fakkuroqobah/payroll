<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Gaji;
use Auth;
use DateTime;

class KAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = Karyawan::findOrFail(Auth::user()->id);
        $gaji = Gaji::where('karyawan_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        if(is_null($gaji)) return view('karyawan.gajiKosong');

        $startDate = date("Y-m-d 23:59:59", strtotime($gaji->tanggal_awal));
        $lastDate = date("Y-m-d 00:00:00", strtotime($gaji->tanggal_akhir));

        $data = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->where('tanggal', date('Y-m-d'))->first();
        $absensi = Absensi::with(['todo'])->where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->get();
        
        $absensiTerbaru = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->orderBy('id', 'DESC')->first();
        if(!is_null($absensiTerbaru)) { // start = false; stop = true;
            if(is_null($absensiTerbaru->total_jam_hadir)) {
                $checkAbsensi = true;
            }else{
                $checkAbsensi = false;
            }
        }else{
            $checkAbsensi = false;
        }

        $totalAbsensi = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $totalAbsensi = (intval($totalAbsensi) != 0) ? $this->secondToHMS(intval($totalAbsensi)) : '-';


        $lembur = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->get();
        $lemburTerbaru = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'l')->orderBy('id', 'DESC')->first();
        if(!is_null($lemburTerbaru)) { // start = false; stop = true;
            if(is_null($lemburTerbaru->total_jam_hadir)) {
                $checkLembur = true;
            }else{
                $checkLembur = false;
            }
        }else{
            $checkLembur = false;
        }

        $totalLembur = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $totalLembur = (intval($totalLembur) != 0) ? $this->secondToHMS(intval($totalLembur)) : '-';

        return view('karyawan.absensi.absensi', compact('data', 'absensi', 'lembur', 'absensiTerbaru', 'lemburTerbaru', 'checkAbsensi', 'totalAbsensi', 'checkLembur', 'totalLembur'));
    }

    public function start(Request $request)
    {   
        $now = new DateTime();
        $formatStartTime = $now->format('H:i:s');
        if($formatStartTime < '08:00:00' || $formatStartTime > '17:00:00') {
            return $this->res(422, 'Gagal', "Bukan waktu absen");
        }

        if(date('N') > 5) { // 6 = Sabtu; 7 = Minggu;
            return $this->res(422, 'Gagal', 'Anda tidak bisa absen di hari sabtu dan minggu');
        }

        $check = Absensi::where('jenis', 'h')->where('tanggal', date('Y-m-d'))->first();
        if(!is_null($check)) {
            return $this->res(422, 'Gagal', 'Anda sudah absen sebelumnya');
        }

        try {
            $data = Absensi::create([
                'tanggal' => now(),
                'mulai_hadir' => now(),
                'jenis' => 'h',
                'karyawan_id' => Auth::user()->id,
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function stop(Request $request)
    {
        $data = Absensi::where('karyawan_id', Auth::user()->id)->where('jenis', 'h')->orderBy('id', 'DESC')->firstOrFail();
        try {
            $start = new DateTime($data->mulai_hadir);
            $startModify1 = new DateTime($data->mulai_hadir);
            $startModify2 = new DateTime($data->mulai_hadir);
            $end = new DateTime();

            // $start = new DateTime('2022-04-01 15:00:00');
            // $startModify1 = new DateTime('2022-04-01 15:00:00');
            // $startModify2 = new DateTime('2022-04-01 15:00:00');
            // $end = new DateTime('2022-04-03 12:00:00');

            $formatStartDay = $start->format('Y-m-d');
            $formatEndDay = $end->format('Y-m-d');
            $formatStartTime = $start->format('H:i:s');
            $formatEndTime = $end->format('H:i:s');
            $increaseStartOneDay = $startModify1->modify('+1 day')->format('Y-m-d');

            // Testing
            // 1. Mulai = 08:00:00; Selesai = 13:00:00; Hasil = 5 Jam
            // 2. Mulai = 08:00:00; Selesai = 17:30:00; Hasil = 9 Jam
            
            // 3. Mulai = 2022-04-01 15:00:00; Selesai = 2022-04-02 12:00:00; Hasil = 2 Jam

            $detik = $this->dateTimeToSecond($start, $end);

            if($formatEndTime >= '17:00:00' || $formatEndTime < '08:00:00') {
                if($formatStartTime >= '08:00:00' && $formatStartTime <= '17:00:00') $limit = new DateTime($formatStartDay . '17:00:00');
                else $limit = new DateTime($increaseStartOneDay . ' 17:00:00');

                $detik = $this->dateTimeToSecond($start, $limit);
            }

            if($formatEndDay >= $increaseStartOneDay) {
                if($formatStartTime >= '08:00:00' && $formatStartTime <= '17:00:00') $limit = new DateTime($formatStartDay . '17:00:00');
                else $limit = new DateTime($increaseStartOneDay . ' 17:00:00');
                
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
