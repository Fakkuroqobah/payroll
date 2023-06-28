@extends('layouts.karyawan.index')

@section('title', 'Ttdt')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Things to do today</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.daterange {
    width: 215px;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <input type="text" class="form-control daterange" name="daterange" value="{{ $startDate }} - {{ $lastDate }}" />
    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Tambah</a>
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
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah TTDT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-add">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    <div class="form-group">
                        <label>Pilih tanggal absensi</label>
                        <input type="date" name="tanggal" class="form-control" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="close-modal" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="submit" class="btn btn-primary">Buat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        processing: true,
        ajax: '{{ route("k_ttdt_index") }}' + '?startdate={{ Request::input("startdate") }}&lastdate={{ Request::input("lastdate") }}',
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
        window.location = '{{ route("k_ttdt_index") }}' + '?startdate=' + start.format('DD-MM-YYYY') + '&lastdate=' + end.format('DD-MM-YYYY');
    });
    

    $('#modal-add').on('shown.bs.modal', function() {
        $('#modal-add input[name="tanggal"]').trigger('focus');
    });
    var add = $("#modal-add button[name='submit']");
    add.click(function(e) {
        e.preventDefault();

        add.attr("disabled", true);
        add.text('Loading');

        $('.error-message').addClass('d-none');
        $('.error-message ul').empty();

        var form = new FormData($('#form-add')[0]);
        form.append('aksi', 'tambah');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            url: '{{ route("k_ttdt_check") }}',
            method: "POST",
            dataType: 'json',
            data: form,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            add.attr("disabled", false);
            add.text('Buat');
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Data absensi berhasil ditemukan',
                showConfirmButton: false,
                timer: 4000,
                background: '#059669',
            });

            var url = "{{ route('k_ttdt_create', ':id') }}";
            url = url.replace(':id', msg.data.id);

            window.location.href = url;
        }).fail(function(err) {
            add.attr("disabled", false);
            add.text('Buat');

            if(err.status == 404) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Data absensi tidak ditemukan',
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


    $('#table').on('click', '.delete', function(event) {
        var url = "{{ route('k_ttdt_delete', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        var opt = {
            url: url,
            type: 'kTtdt',
            method: 'DELETE',
            aksi: 'hapus',
            table: table
        };
        
        var txt = {
            msgAlert: "Data akan dihapus!",
            msgText: "hapus",
            msgTitle: 'Data berhasil dihapus'
        };

        requestAjaxDelete(opt, txt);
    });
});
</script>
@endpush