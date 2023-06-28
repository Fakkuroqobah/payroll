@extends('layouts.karyawan.index')

@section('title', 'Absensi')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Absensi</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/event-calendar-evo/css/evo-calendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/event-calendar-evo/css/evo-calendar.royal-navy.min.css') }}">
<style>
.royal-navy .event-container:hover a.link-ttdt {
    color: #164255 !important;
}
span.event-indicator {
    top: 115%;
    right: 25%;
    margin-left: -15px;
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
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="text-center">
                    <small>Jam</small>
                    <h1 class="text-center clock">{{ date('H:i') }}</h1>
                </div>

                <hr>
                <div class="text-center">
                    <small>Absensi hari ini</small>

                    <form method="POST">
                        @if ($checkAbsensi)
                            <button type="submit" class="mt-2 mb-2 btn btn-secondary btn-block btn-stop-kerja">Stop kerja</button>
                        @else
                            <button type="submit" class="mt-2 mb-2 btn btn-primary btn-block btn-mulai-kerja">Mulai kerja</button>
                        @endif
                    </form>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <small>Waktu mulai</small>
                            <h3 class="text-center">{{ (isset($absensiTerbaru->mulai_hadir) && !is_null($absensiTerbaru->mulai_hadir)) ? date('H:i', strtotime($absensiTerbaru->mulai_hadir)) : '-' }}</h3>
                        </div>
                        <div class="col-md-6">
                            <small>Waktu selesai</small>
                            <h3 class="text-center">{{ (isset($absensiTerbaru->selesai_hadir) && !is_null($absensiTerbaru->selesai_hadir)) ? date('H:i', strtotime($absensiTerbaru->selesai_hadir)) : '-' }}</h3>
                        </div>
                    </div>
                
                    <small>Total jam kerja (bulan)</small>
                    <h4 class="text-center">{{ $totalAbsensi }}</h4>
                </div>

                <hr>
                <div class="text-center">
                    <small>Kerja lembur (hanya tersedia di luar jam kerja)</small>
                    <form method="POST">
                        @if ($checkLembur)
                            <button type="submit" class="mt-2 mb-2 btn btn-secondary btn-block btn-stop-lembur">Stop lembur</button>
                        @else
                            <button type="submit" class="mt-2 mb-2 btn btn-success btn-block btn-mulai-lembur">Mulai lembur</button>
                        @endif
                    </form>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <small>Waktu mulai</small>
                            <h3 class="text-center">{{ (isset($lemburTerbaru->mulai_lembur) && !is_null($lemburTerbaru->mulai_lembur)) ? date('H:i', strtotime($lemburTerbaru->mulai_lembur)) : '-' }}</h3>
                        </div>
                        <div class="col-md-6">
                            <small>Waktu selesai</small>
                            <h3 class="text-center">{{ (isset($lemburTerbaru->selesai_lembur) && !is_null($lemburTerbaru->selesai_lembur)) ? date('H:i', strtotime($lemburTerbaru->selesai_lembur)) : '-' }}</h3>
                        </div>
                    </div>
                
                    <small>Total jam lembur (bulan)</small>
                    <h4 class="text-center">{{ $totalLembur }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/event-calendar-evo/js/evo-calendar.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    $("#calendar").evoCalendar({
        theme: 'Royal Navy',
        sidebarDisplayDefault: false,
        eventDisplayDefault: false,
        todayHighlight: true,
        calendarEvents: [
            @foreach($absensi as $row)
                {
                    id: '{{ $row->id }}',
                    name: 
                        @if($row->jenis == 'h')
                            'Hadir'
                        @elseif($row->jenis == 'l')
                            'Lembur'
                        @endif,
                    badge: '{{ date("H:i", strtotime($row->mulai_hadir)) }}',
                    date: '{{ $row->tanggal }}',
                    type: 'event',
                    color: 
                        @if($row->jenis == 'h')
                            '#007BFF'
                        @elseif($row->jenis == 'l')
                            '#FFC107'
                        @endif,
                },
                @if(isset($row->todo))
                    @foreach($row->todo as $todo)
                        {
                            id: '{{ $todo->id }}',
                            name: 'TTDT',
                            description: '<a href="{{ route("k_ttdt_show", $todo->id) }}" class="link-ttdt text-white" target="__BLANK">{{ $todo->judul }}</a>',
                            badge: '{{ date("l, d F Y", strtotime($todo->tanggal)) }}',
                            date: '{{ $todo->tanggal }}',
                            type: 'holiday',
                            color: '#00E0F2',
                        },
                    @endforeach
                @endif
            @endforeach
            @foreach($lembur as $row)
                {
                    id: '{{ $row->id }}',
                    name: 'Lembur',
                    badge: '{{ secondToHMS($row->total_jam_hadir) }}',
                    date: '{{ $row->tanggal }}',
                    type: 'birthday',
                    color: '#28A745',
                },
            @endforeach
        ]
    });
    
    clockUpdate();
    setInterval(clockUpdate, 1000);

    var mulai_kerja = $('button.btn-mulai-kerja');
    mulai_kerja.click(function(e) {
        e.preventDefault();

        var _this = $(this);
        var txt = $(this).text();
        
        mulai_kerja.attr("disabled", true);
        _this.text('Loading');

        var form = new FormData();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '{{ route("k_absensi_start") }}',
            method: "POST",
            dataType: 'json',
            data: form,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            mulai_kerja.attr("disabled", false);
            _this.text(txt);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Jam kerja berhasil dimulai',
                showConfirmButton: false,
                timer: 4000,
                background: '#059669',
            });

            location.reload();
        }).fail(function(err) {
            mulai_kerja.attr("disabled", false);
            _this.text(txt);

            if(err.status == 422) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: err.responseJSON.data,
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }else{
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Terjadi kesalahan pada server',
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }
        });
    });

    var stop_kerja = $('button.btn-stop-kerja');
    stop_kerja.click(function(e) {
        e.preventDefault();

        var _this = $(this);
        var txt = $(this).text();
        
        stop_kerja.attr("disabled", true);
        _this.text('Loading');

        var form = new FormData();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '{{ route("k_absensi_stop") }}',
            method: "POST",
            dataType: 'json',
            data: form,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            stop_kerja.attr("disabled", false);
            _this.text(txt);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Jam kerja berhasil distop',
                showConfirmButton: false,
                timer: 4000,
                background: '#059669',
            });

            location.reload();
        }).fail(function(err) {
            stop_kerja.attr("disabled", false);
            _this.text(txt);

            if(err.status == 422) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: err.responseJSON.data,
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }else{
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Terjadi kesalahan pada server',
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }
        });
    });


    var mulai_lembur = $('button.btn-mulai-lembur');
    mulai_lembur.click(function(e) {
        e.preventDefault();

        var _this = $(this);
        var txt = $(this).text();
        
        mulai_lembur.attr("disabled", true);
        _this.text('Loading');

        var form = new FormData();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '{{ route("k_lembur_start") }}',
            method: "POST",
            dataType: 'json',
            data: form,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            mulai_lembur.attr("disabled", false);
            _this.text(txt);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Jam lembur berhasil dimulai',
                showConfirmButton: false,
                timer: 4000,
                background: '#059669',
            });

            location.reload();
        }).fail(function(err) {
            mulai_lembur.attr("disabled", false);
            _this.text(txt);

            if(err.status == 422) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: err.responseJSON.data,
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }else{
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Terjadi kesalahan pada server',
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }
        });
    });


    var stop_lembur = $('button.btn-stop-lembur');
    stop_lembur.click(function(e) {
        e.preventDefault();

        var _this = $(this);
        var txt = $(this).text();
        
        stop_lembur.attr("disabled", true);
        _this.text('Loading');

        var form = new FormData();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '{{ route("k_lembur_stop") }}',
            method: "POST",
            dataType: 'json',
            data: form,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            stop_lembur.attr("disabled", false);
            _this.text(txt);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Jam lembur berhasil distop',
                showConfirmButton: false,
                timer: 4000,
                background: '#059669',
            });

            location.reload();
        }).fail(function(err) {
            stop_lembur.attr("disabled", false);
            _this.text(txt);

            if(err.status == 422) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: err.responseJSON.data,
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }else{
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Terjadi kesalahan pada server',
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }
        });
    });
});

function clockUpdate() {
    var date = new Date();
    
    function addZero(x) {
        if (x < 10) {
            return x = '0' + x;
        } else {
            return x;
        }
    }

    function twelveHour(x) {
        if (x > 12) {
            return x = x - 12;
        } else if (x == 0) {
            return x = 12;
        } else {
            return x;
        }
    }

    var h = addZero(twelveHour(date.getHours()));
    var m = addZero(date.getMinutes());
    var s = addZero(date.getSeconds());

    $('.clock').text(h + ':' + m + ':' + s)
}
</script>
@endpush