@extends('layouts.admin.index')

@section('title', 'Gaji')

@section('breadcrumb')
@endsection

@push('styles')
@endpush

@section('content')
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_gaji', $id) }}">Gaji</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_absen', $id) }}">Absen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_ttdt', $id) }}">TTDT</a>
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