<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Gaji;
use DataTables;
use DateTime;
use Auth;

class KGajiController extends Controller
{
    public function index(Request $request)
    {
        $data = Gaji::where('karyawan_id', Auth::user()->id)->get();

        if($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function($data) {
                    if(isset($data->tanggal_awal) && isset($data->tanggal_akhir)) {
                        return '<a href="'. route('k_gajiPrint', $data->id) .'">'. $this->tgl_indo(date("Y-m-d", strtotime($data->tanggal_awal))) .' s.d '. $this->tgl_indo(date("Y-m-d", strtotime($data->tanggal_akhir))) .'</a>';
                    }
                })
                ->addColumn('aksi', function($data) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-info detail" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-detail">Detail</a>';
                })
                ->addColumn('status', function($data) {
                    if($data->status == 'l') {
                        return '<span class="badge badge-success">Lunas</span>';
                    }else if($data->status == 'bl') {
                        return '<span class="badge badge-secondary">Belum Lunas</span>';
                    }
                })
                ->rawColumns(['tanggal', 'status', 'aksi'])
                ->make(true);
        }

        $gaji_terbaru = Gaji::where('karyawan_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        $gaji = [];
        if(!is_null($gaji_terbaru)) {
            $gaji = $this->gajiSebulan(Auth::user()->id, $gaji_terbaru->id);
        }

        return view('karyawan.gaji.gaji', compact('data', 'gaji'));
    }

    public function detail($id)
    {
        return $this->res(200, 'Berhasil', $this->gajiSebulan(Auth::user()->id, $id));
    }

    public function gajiPrint($id)
    {
        return $this->printGaji(Auth::user()->id, $id);
    }
}
