@extends('layouts.admin.index')

@section('title', 'Gaji')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('a_karyawan_index') }}">Karyawan</a></li>
    @if(Route::current()->getName() == 'a_karyawan_gaji')
        <li class="breadcrumb-item active" aria-current="page">Gaji</li>
    @elseif(Route::current()->getName() == 'a_karyawan_absen')
        <li class="breadcrumb-item active" aria-current="page">Absen</li>
    @elseif(Route::current()->getName() == 'a_karyawan_ttdt')
        <li class="breadcrumb-item active" aria-current="page">TTDT</li>
    @endif
@endsection

@push('styles')
@endpush

@section('content')
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link @if(Route::current()->getName() == 'a_karyawan_gaji') active @endif" href="{{ route('a_karyawan_gaji', $id) }}">Gaji</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::current()->getName() == 'a_karyawan_absen') active @endif" href="{{ route('a_karyawan_absen', $id) }}">Absen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Route::current()->getName() == 'a_karyawan_ttdt') active @endif" href="{{ route('a_karyawan_ttdt', $id) }}">TTDT</a>
    </li>
</ul>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h3 class="text-center mb-0">Data gaji masih kosong</h3>
    </div>
</div>
@endsection

@push('scripts')
@endpush