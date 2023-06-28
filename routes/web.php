<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AAdminController;
use App\Http\Controllers\Admin\AKaryawanController;
use App\Http\Controllers\Admin\ALevelController;
use App\Http\Controllers\Admin\AJabatanController;
use App\Http\Controllers\Admin\AKomponenController;

use App\Http\Controllers\Karyawan\KKaryawanController;
use App\Http\Controllers\Karyawan\KAbsensiController;
use App\Http\Controllers\Karyawan\KLemburController;
use App\Http\Controllers\Karyawan\KTodoController;
use App\Http\Controllers\Karyawan\KGajiController;

Route::get('/', function () {
    if(Auth::guard('admin')->guest() && Auth::guest()) return view('karyawan.login');
    else return redirect()->back();
})->name('home');

Route::middleware(['guest'])->group(function () {  
    Route::prefix('admin')->group(function () {  
        Route::get('/login', [AAdminController::class, 'viewLogin'])->name('a_viewLogin');
        Route::post('/login', [AAdminController::class, 'login'])->name('a_login');
    });

    Route::post('/', [KKaryawanController::class, 'login'])->name('k_login');
});


// Admin
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('/logout', [AAdminController::class, 'logout'])->name('a_logout');

    // Karyawan
    Route::get('/karyawan', [AKaryawanController::class, 'index'])->name('a_karyawan_index');
    Route::post('/karyawan', [AKaryawanController::class, 'store'])->name('a_karyawan_store');
    Route::get('/karyawan/{id}', [AKaryawanController::class, 'show'])->name('a_karyawan_show');
    Route::patch('/karyawan/{id}', [AKaryawanController::class, 'edit'])->name('a_karyawan_edit');
    Route::delete('/karyawan/{id}', [AKaryawanController::class, 'delete'])->name('a_karyawan_delete');
    
    Route::get('/karyawan/gaji/{id}', [AKaryawanController::class, 'gaji'])->name('a_karyawan_gaji');
    Route::get('/karyawan/gaji/{id}/show/{id_gaji}', [AKaryawanController::class, 'gajiShow'])->name('a_karyawan_gajiShow');
    Route::get('/karyawan/gaji/{id}/detail/{id_gaji}', [AKaryawanController::class, 'gajiDetail'])->name('a_karyawan_gajiDetail');
    Route::post('/karyawan/gaji/{id}/tambah', [AKaryawanController::class, 'gajiTambah'])->name('a_karyawan_gajiTambah');
    Route::patch('/karyawan/gaji/{id}/edit/{id_gaji}', [AKaryawanController::class, 'gajiEdit'])->name('a_karyawan_gajiEdit');
    Route::delete('/karyawan/gaji/{id}/hapus/{id_gaji}', [AKaryawanController::class, 'gajiHapus'])->name('a_karyawan_gajiHapus');
    Route::get('/karyawan/gaji/{id}/print/{id_gaji}', [AKaryawanController::class, 'gajiPrint'])->name('a_karyawan_gajiPrint');

    Route::get('/karyawan/absen/{id}', [AKaryawanController::class, 'absen'])->name('a_karyawan_absen');
    Route::get('/karyawan/ttdt/{id}', [AKaryawanController::class, 'ttdt'])->name('a_karyawan_ttdt');
    Route::get('/karyawan/ttdt/{id}/detail/{id_ttdt}', [AKaryawanController::class, 'ttdtDetail'])->name('a_karyawan_ttdtDetail');

    // Level
    Route::get('/level', [ALevelController::class, 'index'])->name('a_level_index');
    Route::post('/level', [ALevelController::class, 'store'])->name('a_level_store');
    Route::get('/level/{id}', [ALevelController::class, 'show'])->name('a_level_show');
    Route::patch('/level/{id}', [ALevelController::class, 'edit'])->name('a_level_edit');
    Route::delete('/level/{id}', [ALevelController::class, 'delete'])->name('a_level_delete');

    // Jabatan
    Route::get('/jabatan', [AJabatanController::class, 'index'])->name('a_jabatan_index');
    Route::post('/jabatan', [AJabatanController::class, 'store'])->name('a_jabatan_store');
    Route::get('/jabatan/{id}', [AJabatanController::class, 'show'])->name('a_jabatan_show');
    Route::patch('/jabatan/{id}', [AJabatanController::class, 'edit'])->name('a_jabatan_edit');
    Route::delete('/jabatan/{id}', [AJabatanController::class, 'delete'])->name('a_jabatan_delete');

    // Komponen
    Route::get('/komponen', [AKomponenController::class, 'index'])->name('a_komponen_index');
    Route::post('/komponen', [AKomponenController::class, 'store'])->name('a_komponen_store');
    Route::get('/komponen/{id}', [AKomponenController::class, 'show'])->name('a_komponen_show');
    Route::patch('/komponen/{id}', [AKomponenController::class, 'edit'])->name('a_komponen_edit');
    Route::delete('/komponen/{id}', [AKomponenController::class, 'delete'])->name('a_komponen_delete');
});


// Karyawan
Route::prefix('karyawan')->middleware(['karyawan'])->group(function () {
    Route::get('/logout', [KKaryawanController::class, 'logout'])->name('k_logout');

    Route::group(['prefix' => 'storage'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    // Absensi
    Route::get('/absensi', [KAbsensiController::class, 'index'])->name('k_absensi_index');
    Route::post('/absensi/start', [KAbsensiController::class, 'start'])->name('k_absensi_start');
    Route::post('/absensi/stop', [KAbsensiController::class, 'stop'])->name('k_absensi_stop');
    
    // Lembur
    Route::post('/lembur/start', [KLemburController::class, 'start'])->name('k_lembur_start');
    Route::post('/lembur/stop', [KLemburController::class, 'stop'])->name('k_lembur_stop');
    
    // Ttdt
    Route::get('/ttdt', [KTodoController::class, 'index'])->name('k_ttdt_index');
    Route::get('/ttdt/{id}', [KTodoController::class, 'show'])->name('k_ttdt_show');
    Route::post('/ttdt/cek', [KTodoController::class, 'check'])->name('k_ttdt_check');
    Route::get('/ttdt/tambah/{id}', [KTodoController::class, 'create'])->name('k_ttdt_create');
    Route::post('/ttdt/tambah/{id}', [KTodoController::class, 'store'])->name('k_ttdt_store');
    Route::get('/ttdt/edit/{id}', [KTodoController::class, 'edit'])->name('k_ttdt_edit');
    Route::patch('/ttdt/edit/{id}', [KTodoController::class, 'update'])->name('k_ttdt_update');
    Route::delete('/ttdt/{id}', [KTodoController::class, 'delete'])->name('k_ttdt_delete');

    // Gaji
    Route::get('/gaji', [KGajiController::class, 'index'])->name('k_gaji_index');
    Route::get('/gaji/{id}', [KGajiController::class, 'detail'])->name('k_gaji_detail');
    Route::get('/gaji/print/{id}', [KGajiController::class, 'gajiPrint'])->name('k_gajiPrint');
});