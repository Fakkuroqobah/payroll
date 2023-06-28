<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Gaji;
use App\Models\Todo;
use DataTables;
use Auth;

class KTodoController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = Karyawan::findOrFail(Auth::user()->id);
        $gaji = Gaji::where('karyawan_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        if(is_null($gaji)) return view('karyawan.gajiKosong');

        $startDate = $request->input('startdate');
		$lastDate = $request->input('lastdate');
        
		if(!is_null($startDate) && !is_null($lastDate)) {
            $startDate = $startDate;
			$lastDate = $lastDate;
		}else{
            $startDate = date("d/m/Y", strtotime($gaji->tanggal_awal));
            $lastDate = date("d/m/Y", strtotime($gaji->tanggal_akhir));
		}
        
        if($request->ajax()) {
            $startDate = $request->input('startdate');
		    $lastDate = $request->input('lastdate');

            if ((!is_null($startDate) && $startDate != '') && (!is_null($lastDate) && $lastDate != '')) {
                $startDateFormat = date('Y-m-d 00:00:00', strtotime($startDate));
                $lastDateFormat = date('Y-m-d 23:59:59', strtotime($lastDate));
    
                $data = Todo::with(['absensi'])->whereHas('absensi')->whereBetween('tanggal', [$startDateFormat, $lastDateFormat])->latest()->get();
            } else {
                $data = Todo::with(['absensi'])->whereHas('absensi')->whereBetween('tanggal', [date('Y-m-d 00:00:00', strtotime($gaji->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($gaji->tanggal_akhir))])->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('judul', function($data) {
                    return '<a href="'. route('k_ttdt_show', $data->id) .'">'. $data->judul .'</a>';
                })
                ->addColumn('tanggal_absen', function($data) {
                    return date("l, d-m-Y", strtotime($data->absensi->tanggal));
                })
                ->addColumn('tanggal_buat_ttdt', function($data) {
                    return date("l, d-m-Y", strtotime($data->tanggal));
                })
                ->addColumn('perubahan_terakhir', function($data) {
                    return date("l, d-m-Y", strtotime($data->updated_at));
                })
                ->addColumn('aksi', function($data) {
                    return '<a href="'. route('k_ttdt_edit', $data->id) .'" class="btn btn-sm mr-2 btn-warning edit">Edit</a>' .
                        '<a href="#" class="btn btn-sm btn-danger mt-2 mt-lg-0 mb-2 mb-lg-0 delete" data-id="'. $data->id .'">Delete</a>';
                })
                ->rawColumns(['judul', 'tanggal_absen', 'tanggal_buat_ttdt', 'perubahan_terakhir', 'aksi'])
                ->make(true);
        }

        return view('karyawan.todo.todo', compact('startDate', 'lastDate'));
    }

    public function show($id)
    {
        $data = Todo::with(['absensi'])->whereHas('absensi')->findOrFail($id);
        return view('karyawan.todo.todoDetail', compact('data'));
    }

    public function check(Request $request)
    {
        $data = Absensi::where('tanggal', $request->tanggal)->firstOrFail();
        return $this->res(201, 'Berhasil', $data);
    }

    public function create($id)
    {
        $data = Absensi::where('karyawan_id', Auth::user()->id)->findOrFail($id);
        return view('karyawan.todo.todoTambah', compact('id', 'data'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
        ]);
        
        try {
            $data = Todo::create([
                'tanggal' => now(),
                'judul' => $request->judul,
                'isi' => $request->isi,
                'absensi_id' => $id,
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = Todo::whereHas('absensi')->findOrFail($id);
        return view('karyawan.todo.todoEdit', compact('id', 'data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
        ]);
        
        $data = Todo::whereHas('absensi')->findOrFail($id);
        try {
            $data->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
            ]);

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $data = Todo::whereHas('absensi')->findOrFail($id);
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
