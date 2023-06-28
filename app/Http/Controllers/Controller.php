<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Karyawan;
use App\Models\Komponen;
use App\Models\Absensi;
use App\Models\Gaji;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function res($code = 200, $msg = 'Berhasil', $data = [])
    {
        return response()->json([
            'message' => $msg,
            'data' => $data
        ], $code);
    }

    protected function errorFk()
    {
        return $this->res(422, 'Gagal', 'Tidak dapat menghapus, data ini digunakan oleh data lain');
    }

    protected function rupiah($angka)
    {
        $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }

    function tgl_indo($tanggal, $cetak_hari = false) {
        $hari = array ( 
            1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array (
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        
        $split 	  = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        
        // variabel pecahkan 0 = tahun
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tanggal
    
        return $tgl_indo;
    }

    protected function dateTimeToSecond($start, $end)
    {
        $diff = $end->diff($start, true);

        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;
        return $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
    }

    protected function secondToHMS($seconds)
    {
        $getHours = floor($seconds / 3600);
        $getMins = floor(($seconds - ($getHours*3600)) / 60);
        // $getSecs = floor($seconds % 60);
        return $getHours.' Jam '.$getMins.' Menit';
    }

    protected function gajiSebulan($id, $id_gaji)
    {
        $data = Gaji::findOrFail($id_gaji);
        $karyawan = Karyawan::findOrFail($id);
        $komponen = Komponen::where('jabatan_id', $karyawan->jabatan_id)->first();
        $startDate = date("Y-m-d 23:59:59", strtotime($data->tanggal_awal));
        $lastDate = date("Y-m-d 00:00:00", strtotime($data->tanggal_akhir));

        $tgl = $this->tgl_indo(date('Y-m-d', strtotime($startDate))) .' s.d '. $this->tgl_indo(date('Y-m-d', strtotime($lastDate)));

        $table = '';
        $gaji_pokok = 0;
        $total_absensi = 0;
        $total_lembur = 0;
        $total_pph = 0;

        // GAJI POKOK
        $gaji_pokok = $komponen->gaji_pokok;
        $table .= '
            <tr>
                <td class="td-1-width">+ Gaji pokok</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($gaji_pokok) .'</td>
            </tr>';
        
        // HITUNG ABSENSI
        $absensi = Absensi::where('karyawan_id', $id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $total_absensi = ($komponen->gaji_absen / 3600) * intval($absensi);
        $table .= '
            <tr>
                <td class="td-1-width">+ Kehadiran</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_absensi) . ' / ' . $this->secondToHMS(intval($absensi)) .'</td>
            </tr>';

        // HITUNG LEMBUR
        $lembur = Absensi::where('karyawan_id', $id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $total_lembur = ($komponen->gaji_lembur / 3600) * intval($lembur);
        $table .= '
            <tr>
                <td class="td-1-width">+ Lembur</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_lembur) . ' / ' . $this->secondToHMS(intval($lembur)) .'</td>
            </tr>';

        // HITUNG PPH
        $jenis = '';
        if(!is_null($karyawan->npwp)) {
            $a = $gaji_pokok * 0.5;
            $b = $a * 0.05;

            $total_pph = $b;
            $jenis = '(NPWP)';
        }else{
            $a = $gaji_pokok * 0.5;
            $b = $a * 0.05 * 1.2;

            $total_pph = $b;
            $jenis = '(Non NPWP)';
        }

        $table .= '
            <tr>
                <td class="td-1-width">- PPH '. $jenis .'</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_pph) .'</td>
            </tr>';


        $gaji_bersih = $gaji_pokok + $total_absensi + $total_lembur - $total_pph;
        $table .= '
            <tr>
                <td class="td-1-width"><b>Penghasilan Bersih</b></td>
                <td class="td-2-width">:</td>
                <td><b>'. $this->rupiah($gaji_bersih) .'</b></td>
            </tr>';

        $res = [
            'tgl' => $tgl,
            'tgl_terbaru' => date('d-m-Y', strtotime($lastDate)),
            'table' => $table,
            'gaji_bersih' => $gaji_bersih
        ];

        return $res;
    }

    protected function gajiSebulanPrint($id, $id_gaji)
    {
        $data = Gaji::findOrFail($id_gaji);
        $karyawan = Karyawan::findOrFail($id);
        $komponen = Komponen::where('jabatan_id', $karyawan->jabatan_id)->first();
        $startDate = date("Y-m-d 23:59:59", strtotime($data->tanggal_awal));
        $lastDate = date("Y-m-d 00:00:00", strtotime($data->tanggal_akhir));

        $tgl = $this->tgl_indo(date('Y-m-d', strtotime($startDate))) .' s.d '. $this->tgl_indo(date('Y-m-d', strtotime($lastDate)));

        $tablePenghasilan = '';
        $tablePotongan = '';
        $subTotalHasil = 0;
        $gaji_pokok = 0;
        $total_absensi = 0;
        $total_lembur = 0;
        $total_pph = 0;

        // GAJI POKOK
        $gaji_pokok = $komponen->gaji_pokok;
        $tablePenghasilan .= '
            <tr>
                <td class="td-1-width">+ Gaji pokok</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($gaji_pokok) .'</td>
            </tr>';
        
        // HITUNG ABSENSI
        $absensi = Absensi::where('karyawan_id', $id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $total_absensi = ($komponen->gaji_absen / 3600) * intval($absensi);
        $tablePenghasilan .= '
            <tr>
                <td class="td-1-width">+ Kehadiran</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_absensi) . ' / ' . $this->secondToHMS(intval($absensi)) .'</td>
            </tr>';

        // HITUNG LEMBUR
        $lembur = Absensi::where('karyawan_id', $id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $total_lembur = ($komponen->gaji_lembur / 3600) * intval($lembur);
        $tablePenghasilan .= '
            <tr>
                <td class="td-1-width">+ Lembur</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_lembur) . ' / ' . $this->secondToHMS(intval($lembur)) .'</td>
            </tr>';


        // HITUNG PPH
        $jenis = '';
        if(!is_null($karyawan->npwp)) {
            $a = $gaji_pokok * 0.5;
            $b = $a * 0.05;

            $total_pph = $b;
            $jenis = '(NPWP)';
        }else{
            $a = $gaji_pokok * 0.5;
            $b = $a * 0.05 * 1.2;

            $total_pph = $b;
            $jenis = '(Non NPWP)';
        }

        $tablePotongan .= '
            <tr>
                <td class="td-1-width">- PPH '. $jenis .'</td>
                <td class="td-2-width">:</td>
                <td>'. $this->rupiah($total_pph) .'</td>
            </tr>';

        $gaji_bersih = $gaji_pokok + $total_absensi + $total_lembur - $total_pph;
        $subTotalHasil = $gaji_pokok + $total_absensi + $total_lembur;

        $res = [
            'tgl' => $tgl,
            'table_penghasilan' => $tablePenghasilan,
            'sub_total_hasil' => $subTotalHasil,
            'table_potongan' => $tablePotongan,
            'gaji_bersih' => $gaji_bersih
        ];

        return $res;
    }

    protected function printGaji($id, $id_gaji)
    {
        $data = Gaji::findOrFail($id_gaji);
        $karyawan = Karyawan::with(['jabatan'])->findOrFail($id);

        $tanggal_print = $this->tgl_indo(date('Y-m-d'));
        $gaji = $this->gajiSebulanPrint($id, $id_gaji);

        $pdf = PDF::loadView('shared.gajiPrint', compact('data', 'karyawan', 'tanggal_print', 'gaji'));

        return $pdf->download($karyawan->nama . '-' . date('d-m-Y') . '.pdf');
    }
}
