<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen;
use App\Models\Jabatan;
use DataTables;

class AKomponenController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Komponen::with(['jabatan'])->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-warning edit" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-edit">Edit</a>' .
                        '<a href="#" class="btn btn-sm btn-danger mt-2 mt-lg-0 mb-2 mb-lg-0 delete" data-id="'. $data->id .'">Delete</a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $jabatan = Jabatan::latest()->get();

        return view('admin.komponen.komponen', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required|max:10',
            'gaji_absen' => 'numeric|digits_between:1,11',
            'gaji_lembur' => 'numeric|digits_between:1,11',
            'gaji_pokok' => 'numeric|digits_between:1,11',
        ]);

        $check = Komponen::where('jabatan_id', $request->jabatan_id)->first();
        if(!is_null($check)) {
            return $this->res(422, 'Gagal', 'Komponen jabatan ini sudah dibuat sebelumnya');
        }

        try {
            $penghasilan = '';
            if(!is_null($request->penghasilan)) {
                foreach ($request->penghasilan as $value) {
                    if($value['nama'] != '' && $value['nominal'] != '') {
                        $penghasilan .= $value['nama'] .'_'. $value['nominal'] .',';
                    }else{
                        return $this->res(422, 'Gagal', "Pasangan nama dan nominal penghasilan harus diisi");
                    }
                }
            }

            $potongan = '';
            if(!is_null($request->potongan)) {
                foreach ($request->potongan as $value) {
                    if($value['nama'] != '' && $value['nominal'] != '') {
                        $potongan .= $value['nama'] .'_'. $value['nominal'] .',';
                    }else{
                        return $this->res(422, 'Gagal', "Pasangan nama dan nominal potongan harus diisi");
                    }
                }
            }

            $data = Komponen::create([
                'jabatan_id' => $request->jabatan_id,
                'gaji_absen' => $request->gaji_absen,
                'gaji_lembur' => $request->gaji_lembur,
                'gaji_pokok' => $request->gaji_pokok,
                'penghasilan' => $penghasilan,
                'potongan' => $potongan
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = Komponen::findOrFail($id);

        $penghasilan = [];
        if(!is_null($data->penghasilan) && $data->penghasilan != '') {
            $pecah = explode(',', $data->penghasilan);
            foreach($pecah as $key => $row) {
                $txt = explode('_', $row);
                if($txt[0] != '' && $txt[1] != '') {
                    $penghasilan[$key]['nama'] = $txt[0];
                    $penghasilan[$key]['nominal'] = $txt[1];
                }
            }
        }

        $potongan = [];
        if(!is_null($data->potongan) && $data->potongan != '') {
            $pecah = explode(',', $data->potongan);
            foreach($pecah as $key => $row) {
                $txt = explode('_', $row);
                if($txt[0] != '' && $txt[1] != '') {
                    $potongan[$key]['nama'] = $txt[0];
                    $potongan[$key]['nominal'] = $txt[1];
                }
            }
        }
        
        $data['penghasilan'] = $penghasilan;
        $data['potongan'] = $potongan;

        return $this->res(200, 'Berhasil', $data);
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required|max:10',
            'gaji_absen' => 'numeric|digits_between:1,11',
            'gaji_lembur' => 'numeric|digits_between:1,11',
            'gaji_pokok' => 'numeric|digits_between:1,11',
        ]);

        $check = Komponen::where('jabatan_id', $request->jabatan_id)->where('id', '!=', $id)->first();
        if(!is_null($check)) {
            return $this->res(422, 'Gagal', 'Komponen gaji ini sudah dibuat sebelumnya');
        }

        $data = Komponen::findOrFail($id);
        try {
            $penghasilan = '';
            if(!is_null($request->penghasilan)) {
                foreach ($request->penghasilan as $value) {
                    if($value['nama'] != '' && $value['nominal'] != '') {
                        $penghasilan .= $value['nama'] .'_'. $value['nominal'] .',';
                    }else{
                        return $this->res(422, 'Gagal', "Pasangan nama dan nominal penghasilan harus diisi");
                    }
                }
            }

            $potongan = '';
            if(!is_null($request->potongan)) {
                foreach ($request->potongan as $value) {
                    if($value['nama'] != '' && $value['nominal'] != '') {
                        $potongan .= $value['nama'] .'_'. $value['nominal'] .',';
                    }else{
                        return $this->res(422, 'Gagal', "Pasangan nama dan nominal potongan harus diisi");
                    }
                }
            }

            $data->update([
                'jabatan_id' => $request->jabatan_id,
                'gaji_absen' => $request->gaji_absen,
                'gaji_lembur' => $request->gaji_lembur,
                'gaji_pokok' => $request->gaji_pokok,
                'penghasilan' => $penghasilan,
                'potongan' => $potongan
            ]);

            return $this->res(200, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $data = Komponen::findOrFail($id);
        try {
            $data->delete();

            return $this->res(200, 'Berhasil', $data);
        } catch (\Illuminate\Database\QueryException $ex) {
            if($ex->getCode() === '23000') 
                return $this->errorFk();
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }
}
