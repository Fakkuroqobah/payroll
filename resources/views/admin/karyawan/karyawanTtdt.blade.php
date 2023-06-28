@extends('layouts.admin.index')

@section('title', 'Ttdt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('a_karyawan_index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active" aria-current="page">TTDT</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.daterange {
    width: 215px;
}

.td-1-width {
    width: 240px;
}
.td-2-width {
    text-align: center;
    width: 40px;
}
#table-detail tr td {
    vertical-align: top;
}
</style>
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
        <a class="nav-link active" href="{{ route('a_karyawan_ttdt', $id) }}">TTDT</a>
    </li>
</ul>

<div class="d-flex justify-content-between align-items-center mb-2">
    <input type="text" class="form-control daterange" name="daterange" value="{{ $startDate }} - {{ $lastDate }}" />
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal Absen</th>
                        <th>Tanggal Buat TTDT</th>
                        <th>Perubahan terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- modal --}}
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Ttdt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="text-center mb-0">Loading</h4>

                <p id="detail-tgl"></p>
                <p><b>Judul</b></p>
                <p id="detail-judul"></p>

                <p><b>Isi</b></p>
                <p id="detail-isi"></p>
            </div>
            <div class="modal-footer">
                <button type="button" name="close-modal" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        processing: true,
        ajax: '{{ route("a_karyawan_ttdt", $id) }}' + '?startdate={{ Request::input("startdate") }}&lastdate={{ Request::input("lastdate") }}',
        columns: [
            { data: 'DT_RowIndex', name:'DT_RowIndex', searchable: false },
            { data: 'judul', name: 'judul' },
            { data: 'tanggal_absen', name: 'tanggal_absen' },
            { data: 'tanggal_buat_ttdt', name: 'tanggal_buat_ttdt' },
            { data: 'perubahan_terakhir', name: 'perubahan_terakhir' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 5] },
            { "width": "5%", "targets": 0 },
            { "width": "20%", "targets": 5 },
        ]
    });

    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end, label) {
        window.location = '{{ route("a_karyawan_ttdt", $id) }}' + '?startdate=' + start.format('DD-MM-YYYY') + '&lastdate=' + end.format('DD-MM-YYYY');
    });

    $('#table').on('click', '.detail', function(e) {
        e.preventDefault();

        var url = "{{ route('a_karyawan_ttdtDetail', ['id' => ':id', 'id_ttdt' => ':id_ttdt']) }}";
        url = url.replace(':id', '{{ $id }}');
        url = url.replace(':id_ttdt', $(this).data('id'));
        
        $('#detail-tgl').css('display', 'none');
        $('#detail-judul').css('display', 'none');
        $('#detail-isi').css('display', 'none');
        $('#modal-detail h4').css('display', 'block');

        $.ajax({
            url: url,
            method: "GET",
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            $('#detail-tgl').empty().append(msg.data.tgl);
            $('#detail-judul').empty().append(msg.data.judul);
            $('#detail-isi').empty().append(msg.data.isi);
            $('#detail-tgl').css('display', 'block');
            $('#detail-judul').css('display', 'block');
            $('#detail-isi').css('display', 'block');
            $('#modal-detail h4').css('display', 'none');
        }).fail(function(err) {
            alert("Terjadi kesalahan pada server");
        });
    });
});
</script>
@endpush