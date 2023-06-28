@extends('layouts.admin.index')

@section('title', 'Komponen')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Komponen</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<a href="#" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#modal-add">Tambah</a>
<div class="clearfix"></div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jabatan</th>
                        <th>Gaji Absen</th>
                        <th>Gaji Lembur</th>
                        <th>Gaji Pokok</th>
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
                <h5 class="modal-title">Tambah komponen</h5>
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
                        <select name="jabatan_id" class="form-control">
                            <option value="">--Masukan jabatan--</option>
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_absen" class="form-control" placeholder="Masukan gaji absensi">
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_lembur" class="form-control" placeholder="Masukan gaji lembur">
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_pokok" class="form-control" placeholder="Masukan gaji pokok">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="close-modal" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit komponen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-edit">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    <div class="form-group">
                        <select name="jabatan_id" class="form-control">
                            <option value="">--Masukan jabatan (optional)--</option>
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_absen" class="form-control" placeholder="Masukan gaji absensi">
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_lembur" class="form-control" placeholder="Masukan gaji lembur">
                    </div>

                    <div class="form-group">
                        <input type="number" name="gaji_pokok" class="form-control" placeholder="Masukan gaji pokok">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="close-modal" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" data-id="" name="submit" class="btn btn-warning">Edit Data</button>
                </div>
            </form>
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
        ajax: '{{ route("a_komponen_index") }}',
        columns: [
            { data: 'DT_RowIndex', name:'DT_RowIndex', searchable: false },
            { data: 'jabatan.nama', name: 'jabatan_nama' },
            { data: 'gaji_absen', name: 'gaji_absen', render: $.fn.dataTable.render.number( '.', ',', 0 ) },
            { data: 'gaji_lembur', name: 'gaji_lembur', render: $.fn.dataTable.render.number( '.', ',', 0 ) },
            { data: 'gaji_pokok', name: 'gaji_pokok', render: $.fn.dataTable.render.number( '.', ',', 0 ) },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 1, 2, 3, 4, 5] },
            { "width": "5%", "targets": 0 },
        ]
    });
    

    $('#modal-add').on('shown.bs.modal', function() {
        $('#modal-add input[name="nama"]').trigger('focus');
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

        var opt = {
            type: 'aKomponen',
            method: 'POST',
            aksi: 'tambah',
            url: '{{ route("a_komponen_store") }}',
            table: table,
            element: add
        };

        var txt = {
            btnText: 'Tambah Data',
            msgAlert: 'Data berhasil ditambahkan',
            msgText: 'ditambah'
        };

        requestAjaxPost(opt, form, txt);
    });


    var modalEditInput = $('#modal-edit input');
    var modalEditSelect = $('#modal-edit select');
    var modalEditGajiAbsen = $('#modal-edit input[name="gaji_absen"]');
    var modalEditGajiLembur = $('#modal-edit input[name="gaji_lembur"]');
    var modalEditGajiPokok = $('#modal-edit input[name="gaji_pokok"]');
    var modalEditJabatanId = $('#modal-edit select[name="jabatan_id"]');
    $('#modal-edit').on('hidden.bs.modal', function () {
        modalEditInput.val("");
        modalEditSelect.val("");
    });
    $('#table').on('click', '.edit', function(e) {
        e.preventDefault()

        var url = "{{ route('a_komponen_show', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        $('#modal-edit button[name="submit"]').attr('data-id', $(this).data('id'));
        modalEditInput.attr("disabled", true);
        modalEditSelect.attr("disabled", true);

        $('#modal-edit .wrap-penghasilan').empty();
        $('#modal-edit .wrap-potongan').empty();
        
        $.ajax({
            url: url,
            method: "GET",
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            modalEditInput.attr("disabled", false);
            modalEditSelect.attr("disabled", false);
            
            modalEditJabatanId.trigger('focus');

            modalEditGajiAbsen.val(msg.data.gaji_absen);
            modalEditGajiLembur.val(msg.data.gaji_lembur);
            modalEditGajiPokok.val(msg.data.gaji_pokok);
            modalEditJabatanId.val(msg.data.jabatan_id);
        }).fail(function(err) {
            alert("Terjadi kesalahan pada server");
            modalEditInput.attr("disabled", false);
            modalEditSelect.attr("disabled", false);
        });
    });


    var edit = $("#modal-edit button[name='submit']");
    edit.click(function(e) {
        e.preventDefault();

        edit.attr("disabled", true);
        edit.text('Loading');

        $('.error-message').addClass('d-none');
        $('.error-message ul').empty();

        var form = new FormData($('#form-edit')[0]);
        form.append('aksi', 'edit');
        form.append('_method', 'PATCH');

        var url = "{{ route('a_komponen_edit', ':id') }}";
        url = url.replace(':id', $(this).attr('data-id'));

        var opt = {
            type: 'aKomponen',
            method: 'POST',
            aksi: 'edit',
            url: url,
            table: table,
            element: edit
        };

        var txt = {
            btnText: 'Edit Data',
            msgAlert: 'Data berhasil diedit',
            msgText: 'diedit'
        };

        requestAjaxPost(opt, form, txt);
    });


    $('#table').on('click', '.delete', function(event) {
        var url = "{{ route('a_komponen_delete', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        var opt = {
            url: url,
            type: 'aKomponen',
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