@extends('layouts.admin.index')

@section('title', 'Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('a_karyawan_index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Absen</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.daterange {
    width: 215px;
}
</style>
@endpush

@section('content')
@php
function secondToHMS($seconds) {
    $getHours = floor($seconds / 3600);
    $getMins = floor(($seconds - ($getHours*3600)) / 60);
    return $getHours.' Jam '.$getMins.' Menit';
}
@endphp
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_gaji', $id) }}">Gaji</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('a_karyawan_absen', $id) }}">Absen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_ttdt', $id) }}">TTDT</a>
    </li>
</ul>

<div class="d-flex justify-content-between align-items-center mb-2">
    <input type="text" class="form-control daterange" name="daterange" value="{{ $akstartDate }} - {{ $aklastDate }}" />
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <table>
            <tr>
                <td>Hadir</td>
                <td class="text-center" style="width: 20px">:</td>
                <td>{{ $totalAbsensi }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td class="text-center" style="width: 20px">:</td>
                <td>{{ $totalLembur }}</td>
            </tr>
        </table>

        <div class="table-responsive mt-2">
            <table class="table table-bordered table-hover" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mulai Hadir</th>
                        <th>Selesai Hadir</th>
                        <th>Jenis</th>
                        <th>Total Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kehadiran as $item)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($item->mulai_hadir)) }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($item->selesai_hadir)) }}</td>
                            <td>
                                @if ($item->jenis == 'l')
                                    Lembur
                                @else
                                    Hadir
                                @endif
                            </td>
                            <td>{{ secondToHMS($item->total_jam_hadir) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end, label) {
        window.location = '{{ route("a_karyawan_absen", $id) }}' + '?startdate=' + start.format('DD-MM-YYYY') + '&lastdate=' + end.format('DD-MM-YYYY');
    });
});
</script>
@endpush