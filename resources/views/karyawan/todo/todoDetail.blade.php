@extends('layouts.karyawan.index')

@section('title', 'Ttdt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('k_ttdt_index') }}">Things to do today</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@push('styles')
<style>
.td-width {
    width: 25px;
    text-align: center
}
</style>
@endpush

@section('content')
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h3>{{ $data->judul }}</h3>
        <table>
            <tr>
                <td>Tanggal absensi</td>
                <td class="td-width">:</td>
                <td>{{ date('l, d F Y', strtotime($data->absensi->tanggal)) }}</td>
            </tr>
            <tr>
                <td>Tanggal buat</td>
                <td class="td-width">:</td>
                <td>{{ date('l, d F Y', strtotime($data->tanggal)) }}</td>
            </tr>
            <tr>
                <td>Perubahan terakhir</td>
                <td class="td-width">:</td>
                <td>{{ date("l, d-m-Y", strtotime($data->updated_at)) }}</td>
            </tr>
        </table>
        <p>{!! $data->isi !!}</p>
    </div>
</div>
@endsection

@push('scripts')

@endpush