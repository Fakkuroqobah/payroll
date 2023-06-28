<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use DataTables;

class ALevelController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Level::latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-warning edit" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-edit">Edit</a>' .
                        '<a href="#" class="btn btn-sm btn-danger mt-2 mt-lg-0 mb-2 mb-lg-0 delete" data-id="'. $data->id .'">Delete</a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('admin.level.level');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255'
        ]);

        try {
            $data = Level::create([
                'nama' => ucfirst($request->nama),
                'deskripsi' => $request->deskripsi
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = Level::findOrFail($id);

        return $this->res(200, 'Berhasil', $data);
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255'
        ]);

        $data = Level::findOrFail($id);
        try {
            $data->update([
                'nama' => ucfirst($request->nama),
                'deskripsi' => $request->deskripsi
            ]);

            return $this->res(200, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $data = Level::findOrFail($id);
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
