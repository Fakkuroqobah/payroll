<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\Gaji;
use App\Models\Lembur;
use App\Models\Todo;
use App\Models\Absensi;
use DataTables;
use File;
use Str;

class AKaryawanController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Karyawan::with(['jabatan', 'gajiTerbaru'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($data) {
                    return '<a href="'. route('a_karyawan_gaji', $data->id) .'" class="">'. $data->nama .'</a>';
                })
                ->addColumn('tanggal', function($data) {
                    if(isset($data->gajiTerbaru->tanggal_awal) && isset($data->gajiTerbaru->tanggal_akhir)) {
                        return $this->tgl_indo(date("Y-m-d", strtotime($data->gajiTerbaru->tanggal_awal))) .' s.d '. $this->tgl_indo(date("Y-m-d", strtotime($data->gajiTerbaru->tanggal_akhir)));
                    }
                })
                ->addColumn('status', function($data) {
                    if($data->status == 1) {
                        return '<span class="badge badge-success">Aktif</span>';
                    }else if($data->status == 0) {
                        return '<span class="badge badge-secondary">Nonaktif</span>';
                    }
                })
                ->addColumn('aksi', function($data) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-warning edit" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-edit">Edit</a>' .
                        '<a href="#" class="btn btn-sm btn-danger mt-2 mt-lg-0 mb-2 mb-lg-0 delete" data-id="'. $data->id .'">Delete</a>';
                })
                ->rawColumns(['nama', 'tanggal', 'status', 'aksi'])
                ->make(true);
        }

        $jabatan = Jabatan::latest()->get();
        $level = Level::latest()->get();

        return view('admin.karyawan.karyawan', compact('jabatan', 'level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'pendidikan' => 'required',
            'jk' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required|max:15',
            'alamat' => 'required',
            'jam_kerja' => 'required|in:full time,part time',
            'tugas' => 'required',
            'npwp' => 'required|max:30',
            'bank' => 'required|max:30',
            'no_rek' => 'required|max:30',
            'awal_kontrak' => 'required',
            'foto' => 'nullable|mimes:jpeg,jpg,bmp,png|max:4096',
            'email' => 'required',
            'tingkat' => 'required|in:senior,junior',
            'jabatan_id' => 'required',
            'level_id' => 'required',
            'password' => 'required'
        ]);

        try {
            $data = new Karyawan();
            $data->nama = $request->nama;
            $data->pendidikan = $request->pendidikan;
            $data->jk = $request->jk;
            $data->no_hp = $request->no_hp;
            $data->alamat = $request->alamat;
            $data->jam_kerja = $request->jam_kerja;
            $data->tugas = $request->tugas;
            $data->npwp = $request->npwp;
            $data->bank = $request->bank;
            $data->no_rek = $request->no_rek;
            $data->awal_kontrak = $request->awal_kontrak;
            $data->akhir_kontrak = $request->akhir_kontrak;
            $data->email = $request->email;
            $data->telegram_id = $request->telegram_id;
            $data->tingkat = $request->tingkat;
            $data->jabatan_id = $request->jabatan_id;
            $data->level_id = $request->level_id;
            $data->password = bcrypt($request->password);

            if($request->file('foto') != null) {
                $foto = $request->file('foto');
                $fotoNama = Str::random(5) . ucfirst($request->nama) . '.' . $foto->clientExtension();

                $data->foto = 'karyawan/' . $fotoNama;
                $request->foto->move(storage_path('app/public/karyawan'), $fotoNama);
            }

            $data->save();

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = Karyawan::findOrFail($id);

        return $this->res(200, 'Berhasil', $data);
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'pendidikan' => 'required',
            'jk' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required|max:15',
            'alamat' => 'required',
            'jam_kerja' => 'required|in:full time,part time',
            'tugas' => 'required',
            'npwp' => 'required|max:30',
            'bank' => 'required|max:30',
            'no_rek' => 'required|max:30',
            'awal_kontrak' => 'required',
            'foto' => 'nullable|mimes:jpeg,jpg,bmp,png|max:4096',
            'email' => 'required',
            'tingkat' => 'required|in:senior,junior',
            'jabatan_id' => 'required',
            'level_id' => 'required',
        ]);

        $data = Karyawan::findOrFail($id);
        $fotoLama = $data->foto;

        try {
            $status = 0;
            if(!is_null($request->status)) {
                $status = 1;
            }

            $data->nama = $request->nama;
            $data->pendidikan = $request->pendidikan;
            $data->jk = $request->jk;
            $data->no_hp = $request->no_hp;
            $data->alamat = $request->alamat;
            $data->jam_kerja = $request->jam_kerja;
            $data->tugas = $request->tugas;
            $data->npwp = $request->npwp;
            $data->bank = $request->bank;
            $data->no_rek = $request->no_rek;
            $data->awal_kontrak = $request->awal_kontrak;
            $data->akhir_kontrak = $request->akhir_kontrak;
            $data->email = $request->email;
            $data->telegram_id = $request->telegram_id;
            $data->tingkat = $request->tingkat;
            $data->status = $status;
            $data->jabatan_id = $request->jabatan_id;
            $data->level_id = $request->level_id;

            if(!is_null($request->password)) {
                $data->password = bcrypt($request->password);
            }

            if($request->file('foto') != null) {
                $foto = $request->file('foto');
                $fotoNama = Str::random(5) . ucfirst($request->nama) . '.' . $foto->clientExtension();

                $data->foto = 'karyawan/' . $fotoNama;
                $request->foto->move(storage_path('app/public/karyawan'), $fotoNama);

                File::delete('storage/' . $fotoLama);
            }

            $data->save();

            return $this->res(200, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $data = Karyawan::findOrFail($id);
        $fotoLama = $data->foto;
        try {
            $data->delete();
            File::delete('storage/' . $fotoLama);

            return $this->res(200, 'Berhasil', $data);
        } catch (\Illuminate\Database\QueryException $ex) {
            if($ex->getCode() === '23000') 
                return $this->errorFk();
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    // GAJI
    public function gaji(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $data = Gaji::where('karyawan_id', $id)->latest();

        if($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function($data) use($id) {
                    if(isset($data->tanggal_awal) && isset($data->tanggal_akhir)) {
                        return '<a href="'. route('a_karyawan_gajiPrint', ['id' => $id, 'id_gaji' => $data->id]) .'">'. $this->tgl_indo(date("Y-m-d", strtotime($data->tanggal_awal))) .' s.d '. $this->tgl_indo(date("Y-m-d", strtotime($data->tanggal_akhir))) .'</a>';
                    }
                })
                ->addColumn('status', function($data) {
                    if($data->status == 'l') {
                        return '<span class="badge badge-success">Lunas</span>';
                    }else if($data->status == 'bl') {
                        return '<span class="badge badge-secondary">Belum Lunas</span>';
                    }
                })
                ->addColumn('aksi', function($data) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-info detail" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-detail">Detail</a>' .
                        '<a href="#" class="btn btn-sm mr-2 mt-2 mt-lg-0 btn-warning edit" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-edit">Edit</a>' .
                        '<a href="#" class="btn btn-sm btn-danger mt-2 mt-lg-0 mb-2 mb-lg-0 delete" data-id="'. $data->id .'">Delete</a>';
                })
                ->rawColumns(['tanggal', 'status', 'aksi'])
                ->make(true);
        }

        return view('admin.karyawan.karyawanGaji', compact('data', 'id', 'karyawan'));
    }

    public function gajiShow($id, $id_gaji)
    {
        $data = Gaji::findOrFail($id_gaji);

        return $this->res(200, 'Berhasil', $data);
    }

    public function gajiDetail($id, $id_gaji)
    {
        return $this->res(200, 'Berhasil', $this->gajiSebulan($id, $id_gaji));
    }

    public function gajiTambah(Request $request)
    {
        $request->validate([
            'no' => 'required|max:255',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        $check = Gaji::where('no', $request->no)->first();
        if(!is_null($check)) {
            return $this->res(422, 'Gagal', 'No gaji sudah dibuat sebelumnya');
        }

        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        $komponen = Komponen::where('jabatan_id', $karyawan->jabatan_id)->first();
        if(is_null($komponen)) {
            return $this->res(422, 'Gagal', 'Data komponen jabatan ini belum dibuat');
        }

        try {
            $data = new Gaji();
            $data->no = $request->no;
            $data->tanggal_awal = $request->tanggal_awal;
            $data->tanggal_akhir = $request->tanggal_akhir;
            $data->keterangan = $request->keterangan;
            $data->deskripsi = $request->deskripsi;
            $data->karyawan_id = $request->karyawan_id;
            $data->save();

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function gajiEdit(Request $request, $id, $id_gaji)
    {
        $request->validate([
            'no' => 'required|max:255',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        $check = Gaji::where('no', $request->no)->where('id', '!=', $id_gaji)->first();
        if(!is_null($check)) {
            return $this->res(422, 'Gagal', 'No gaji sudah dibuat sebelumnya');
        }

        $data = Gaji::findOrFail($id_gaji);
        try {
            $status = 'bl';
            if(!is_null($request->status)) {
                $status = 'l';
                $nominal = $this->gajiSebulan($id, $id_gaji);
                $data->nominal = $nominal['gaji_bersih'];
            }else{
                $data->nominal = null;
            }

            $data->no = $request->no;
            $data->tanggal_awal = $request->tanggal_awal;
            $data->tanggal_akhir = $request->tanggal_akhir;
            $data->keterangan = $request->keterangan;
            $data->deskripsi = $request->deskripsi;
            $data->status = $status;
            $data->karyawan_id = $request->karyawan_id;
            $data->save();

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    public function gajiHapus($id, $id_gaji)
    {
        $data = Gaji::findOrFail($id_gaji);
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

    public function gajiPrint($id, $id_gaji)
    {
        // return view('shared.gajiPrint', compact('id', 'id_gaji'));
        return $this->printGaji($id, $id_gaji);
    }

    public function absen(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $gaji = Gaji::where('karyawan_id', $id)->orderBy('id', 'DESC')->first();
        if(is_null($gaji)) return view('admin.gajiKosong', compact('id'));

        $startDate = date("Y-m-d 23:59:59", strtotime($gaji->tanggal_awal));
        $lastDate = date("Y-m-d 00:00:00", strtotime($gaji->tanggal_akhir));

        $kstartDate = $request->input('startdate');
        $klastDate = $request->input('lastdate');

        if(!is_null($kstartDate) && !is_null($klastDate)) {
            $akstartDate = $kstartDate;
			$aklastDate = $klastDate;
		}else{
            $akstartDate = date("d/m/Y", strtotime($gaji->tanggal_awal));
            $aklastDate = date("d/m/Y", strtotime($gaji->tanggal_akhir));
		}

        if ((!is_null($kstartDate) && $kstartDate != '') && (!is_null($klastDate) && $klastDate != '')) {
            $kstartDateFormat = date('Y-m-d 00:00:00', strtotime($kstartDate));
            $klastDateFormat = date('Y-m-d 23:59:59', strtotime($klastDate));

            $kehadiran = Absensi::where('karyawan_id', $id)->whereBetween('tanggal', [$kstartDateFormat, $klastDateFormat])->get();
		}else{
            $kehadiran = Absensi::where('karyawan_id', $id)->whereBetween('tanggal', [date('Y-m-d 00:00:00', strtotime($gaji->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($gaji->tanggal_akhir))])->get();
		}

        $data = Absensi::where('karyawan_id', $id)->where('jenis', 'h')->where('tanggal', date('Y-m-d'))->first();
        $absensi = Absensi::with(['todo'])->where('karyawan_id', $id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->get();
        
        $absensiTerbaru = Absensi::where('karyawan_id', $id)->where('jenis', 'h')->orderBy('id', 'DESC')->first();
        if(!is_null($absensiTerbaru)) { // start = false; stop = true;
            if(is_null($absensiTerbaru->total_jam_hadir)) {
                $checkAbsensi = true;
            }else{
                $checkAbsensi = false;
            }
        }else{
            $checkAbsensi = false;
        }

        $totalAbsensi = Absensi::where('karyawan_id', $id)->where('jenis', 'h')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $totalAbsensi = (intval($totalAbsensi) != 0) ? $this->secondToHMS(intval($totalAbsensi)) : '-';


        $lembur = Absensi::where('karyawan_id', $id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->get();
        $lemburTerbaru = Absensi::where('karyawan_id', $id)->where('jenis', 'l')->orderBy('id', 'DESC')->first();
        if(!is_null($lemburTerbaru)) { // start = false; stop = true;
            if(is_null($lemburTerbaru->total_jam_hadir)) {
                $checkLembur = true;
            }else{
                $checkLembur = false;
            }
        }else{
            $checkLembur = false;
        }

        $totalLembur = Absensi::where('karyawan_id', $id)->where('jenis', 'l')->whereBetween('tanggal', [$startDate, $lastDate])->sum('total_jam_hadir');
        $totalLembur = (intval($totalLembur) != 0) ? $this->secondToHMS(intval($totalLembur)) : '-';

        return view('admin.karyawan.karyawanAbsen', compact('id', 'data', 'absensi', 'lembur', 'absensiTerbaru', 'lemburTerbaru', 'checkAbsensi', 'totalAbsensi', 'checkLembur', 'totalLembur', 'kehadiran', 'akstartDate', 'aklastDate'));
    }

    public function ttdt(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $gaji = Gaji::where('karyawan_id', $id)->orderBy('id', 'DESC')->first();
        if(is_null($gaji)) return view('admin.gajiKosong', compact('id'));

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
    
                $data = Todo::with(['absensiAdmin'])->whereHas('absensiAdmin', function($q) use($id) {
                    $q->where('karyawan_id', $id);
                })->whereBetween('tanggal', [$startDateFormat, $lastDateFormat])->latest()->get();
            } else {
                $data = Todo::with(['absensiAdmin'])->whereHas('absensiAdmin', function($q) use($id) {
                    $q->where('karyawan_id', $id);
                })->whereBetween('tanggal', [date('Y-m-d 00:00:00', strtotime($gaji->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($gaji->tanggal_akhir))])->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('judul', function($data) {
                    return $data->judul;
                })
                ->addColumn('tanggal_absen', function($data) {
                    return date("l, d-m-Y", strtotime($data->absensiAdmin->tanggal));
                })
                ->addColumn('tanggal_buat_ttdt', function($data) {
                    return date("l, d-m-Y", strtotime($data->tanggal));
                })
                ->addColumn('perubahan_terakhir', function($data) {
                    return date("l, d-m-Y", strtotime($data->updated_at));
                })
                ->addColumn('aksi', function($data) use($id) {
                    return '<a href="#" class="btn btn-sm mr-2 btn-info detail" data-id="'. $data->id .'" data-toggle="modal" data-target="#modal-detail">Detail</a>';
                })
                ->rawColumns(['judul', 'tanggal_absen', 'tanggal_buat_ttdt', 'perubahan_terakhir', 'aksi'])
                ->make(true);
        }

        return view('admin.karyawan.karyawanTtdt', compact('id', 'startDate', 'lastDate'));
    }

    public function ttdtDetail($id, $id_ttdt)
    {
        $data = Todo::findOrFail($id_ttdt);

        return $this->res(200, 'Berhasil', $data);
    }
}
