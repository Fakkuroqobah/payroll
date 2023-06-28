@extends('layouts.admin.index')

@section('title', 'Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<style>
img.foto-karyawan-edit {
    width: 100px;
    height: 100px;
}
</style>
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
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Tanggal gaji</th>
                        <th>Status</th>
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
                <h5 class="modal-title">Tambah karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-add">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    @include('admin.karyawan.modalKaryawan', ['jenis' => 'tambah'])
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
                <h5 class="modal-title">Edit karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-edit">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    @include('admin.karyawan.modalKaryawan', ['jenis' => 'edit'])
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
var fotoKaryawanAdd = $("#modal-add .foto-karyawan-edit");
var fotoKaryawanEdit = $("#modal-edit .foto-karyawan-edit");
function loadImg() {
    var fileSrc = URL.createObjectURL(event.target.files[0]);
    fotoKaryawanAdd.attr('src', fileSrc);
    fotoKaryawanEdit.attr('src', fileSrc);
}

function loadImgDefault() {
    var fileSrc = "{{ asset('img/img-empty.png') }}";
    fotoKaryawanAdd.attr('src', fileSrc);
    fotoKaryawanEdit.attr('src', fileSrc);
}

$(document).ready(function() {
    var table = $('#table').DataTable({
        processing: true,
        ajax: '{{ route("a_karyawan_index") }}',
        columns: [
            { data: 'DT_RowIndex', name:'DT_RowIndex', searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'jabatan.nama', name: 'jabatan' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'status', name: 'status' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 3, 4, 5] },
            { "width": "5%", "targets": 0 },
        ]
    });


    $('#modal-add').on('shown.bs.modal', function() {
        $('#modal-add input[name="nama"]').trigger('focus');
        loadImgDefault();
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
            type: 'aKaryawan',
            method: 'POST',
            aksi: 'tambah',
            url: '{{ route("a_karyawan_store") }}',
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
    var modalEditTextArea = $('#modal-edit textarea');
    var modalEditSelect = $('#modal-edit select');
    var modalEditStatus = $('#modal-edit #status');
    var modalEditNama = $('#modal-edit input[name="nama"]');
    var modalEditPendidikan = $('#modal-edit input[name="pendidikan"]');
    var modalEditJk = $('#modal-edit input[name="jk"]');
    var modalEditNoHp = $('#modal-edit input[name="no_hp"]');
    var modalEditAlamat = $('#modal-edit textarea[name="alamat"]');
    var modalEditJamKerja = $('#modal-edit select[name="jam_kerja"]');
    var modalEditTugas = $('#modal-edit textarea[name="tugas"]');
    var modalEditNpwp = $('#modal-edit input[name="npwp"]');
    var modalEditBank = $('#modal-edit select[name="bank"]');
    var modalEditNoRek = $('#modal-edit input[name="no_rek"]');
    var modalEditAwalKontrak = $('#modal-edit input[name="awal_kontrak"]');
    var modalEditAkhirKontrak = $('#modal-edit input[name="akhir_kontrak"]');
    var modalEditFoto = $('#modal-edit input[name="foto"]');
    var modalEditEmail = $('#modal-edit input[name="email"]');
    var modalEditTelegramId = $('#modal-edit input[name="telegram_id"]');
    var modalEditTingkat = $('#modal-edit select[name="tingkat"]');
    var modalEditJabatanId = $('#modal-edit select[name="jabatan_id"]');
    var modalEditLevel = $('#modal-edit select[name="level_id"]');
    var modalEditPassword = $('#modal-edit input[name="password"]');
    var modalRadioLk = $("#modal-edit input[name=jk].radio-lk");
    var modalRadioPr = $("#modal-edit input[name=jk].radio-pr");
    $('#modal-edit').on('hidden.bs.modal', function () {
        modalEditInput.val("");
        modalEditTextArea.val("");
        modalEditSelect.val("");

        modalRadioLk.prop("checked", false);
        modalRadioPr.prop("checked", false);
        modalRadioLk.val("laki-laki");
        modalRadioPr.val("perempuan");

        $('#modal-edit input[name="status"]').removeAttr('value');
    });
    $('#table').on('click', '.edit', function(e) {
        e.preventDefault();
        loadImgDefault();

        var url = "{{ route('a_karyawan_show', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        $('#modal-edit button[name="submit"]').attr('data-id', $(this).data('id'));
        modalEditInput.attr("disabled", true);
        modalEditTextArea.attr("disabled", true);
        modalEditSelect.attr("disabled", true);
        modalEditStatus.attr("disabled", true);
        
        $.ajax({
            url: url,
            method: "GET",
            cache: false,
            processData: false,
            contentType: false
        }).done(function(msg) {
            modalEditInput.attr("disabled", false);
            modalEditTextArea.attr("disabled", false);
            modalEditSelect.attr("disabled", false);
            modalEditStatus.attr("disabled", false);
            
            modalEditNama.trigger('focus');

            modalEditNama.val(msg.data.nama);
            modalEditPendidikan.val(msg.data.pendidikan);
            modalEditNoHp.val(msg.data.no_hp);
            modalEditAlamat.val(msg.data.alamat);
            modalEditJamKerja.val(msg.data.jam_kerja);
            modalEditTugas.val(msg.data.tugas);
            modalEditNpwp.val(msg.data.npwp);
            modalEditBank.val(msg.data.bank);
            modalEditNoRek.val(msg.data.no_rek);
            modalEditAwalKontrak.val(msg.data.awal_kontrak);
            modalEditAkhirKontrak.val(msg.data.akhir_kontrak);
            modalEditEmail.val(msg.data.email);
            modalEditTelegramId.val(msg.data.telegram_id);
            modalEditTingkat.val(msg.data.tingkat);
            modalEditJabatanId.val(msg.data.jabatan_id);
            modalEditLevel.val(msg.data.level_id);

            if(msg.data.status == 1) {
                modalEditStatus.prop("checked", true);
            }else{
                modalEditStatus.prop("checked", false);
            }

            if(msg.data.jk == 'laki-laki') {
                modalRadioLk.prop("checked", true);
            }else{
                modalRadioPr.prop("checked", true);
            }

            if(msg.data.foto != null) {
                fotoKaryawanEdit.attr("src", "{{ asset('storage') }}" + '/' + msg.data.foto);
            }else{
                fotoKaryawanEdit.attr("src", "{{ asset('img/img-empty.png') }}");
            }
        }).fail(function(err) {
            alert("Terjadi kesalahan pada server");
            modalEditInput.attr("disabled", false);
            modalEditTextArea.attr("disabled", false);
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

        var url = "{{ route('a_karyawan_edit', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        var opt = {
            type: 'aKaryawan',
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
        var url = "{{ route('a_karyawan_delete', ':id') }}";
        url = url.replace(':id', $(this).data('id'));

        var opt = {
            url: url,
            type: 'aKaryawan',
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