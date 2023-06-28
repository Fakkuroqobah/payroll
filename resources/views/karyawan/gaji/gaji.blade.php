@extends('layouts.karyawan.index')

@section('title', 'Gaji')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Gaji</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<style>
.gaji-wrapper {
    border: 1px solid #E1E3E7;
    border-radius: 5px;
    padding: 20px;
}
.gaji-wrapper table tr td:nth-child(1) {
    width: 55%;
}
.gaji-desk {
    color: #787C83;
    font-weight: 400;
}
.gaji-total {
    color: #48BF53;
    font-weight: 700;
    font-size: 40px;
}

.td-1-width {
    width: 260px;
}
.td-2-width {
    text-align: center;
    width: 40px;
}
</style>
@endpush

@section('content')
@php
function rupiah($angka) {
    $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
    return $hasil_rupiah;
}
@endphp

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        @if (!empty($gaji))
            <div class="gaji-wrapper mb-4">
                <table>
                    <tr>
                        <td><h5>Perolehan gaji sementara</h5></td>
                        <td rowspan="2"><div class="text-right gaji-total">{{ rupiah($gaji['gaji_bersih']) }}</div></td>
                    </tr>
                    <tr>
                        <td>
                            <i class="gaji-desk">
                                Dihitung dari berbagai komponen gaji yang terlibat. Mulai dari gaji pokok, performa, dan pajak. Detail lebih lanjut klik link berikut  
                                <a class="" data-toggle="collapse" href="#detail-gaji" role="button" aria-expanded="false" aria-controls="detail-gaji">detail</a>.
                            </i>
                        </td>
                    </tr>
                </table>
            </div>
        
            <div class="collapse mb-3" id="detail-gaji">
                <div class="card card-body">
                    <b><u>Dasar perhitungan</u></b>
                    <table>
                        {!! $gaji['table'] !!}
                    </table>
                </div>
            </div>
        @endif


        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No gaji</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Status</th>
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
                <h5 class="modal-title">Detail gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="text-center mb-0">Loading</h4>
                <p id="table-detail-tgl"></p>
                <table id="table-detail">
                    
                </table>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        processing: true,
        ajax: '{{ route("k_gaji_index") }}',
        columns: [
            { data: 'DT_RowIndex', name:'DT_RowIndex', searchable: false },
            { data: 'no', name: 'no' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'nominal', name: 'nominal', render: $.fn.dataTable.render.number( '.', ',', 0 ) },
            { data: 'status', name: 'status' },
            { data: 'aksi', name: 'aksi' },
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 1, 2, 3, 4, 5] },
            { "width": "5%", "targets": 0 },
        ]
    });

    $('#table').on('click', '.detail', function(e) {
        e.preventDefault();

        var url = "{{ route('k_gaji_detail', ':id') }}";
        url = url.replace(':id', $(this).data('id'));
        
        $('#table-detail-tgl').css('display', 'none');
        $('#table-detail').css('display', 'none');
        $('#modal-detail h4').css('display', 'block');

        $.ajax({
            url: url,
            method: "GET",
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            $('#table-detail-tgl').empty().append(msg.data.tgl);
            $('#table-detail').empty().append(msg.data.table);
            $('#table-detail-tgl').css('display', 'block');
            $('#table-detail').css('display', 'block');
            $('#modal-detail h4').css('display', 'none');
        }).fail(function(err) {
            alert("Terjadi kesalahan pada server");
        });
    });
});
</script>
@endpush