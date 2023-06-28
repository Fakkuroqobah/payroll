@extends('layouts.admin.index')

@section('title', 'Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('a_karyawan_index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gaji</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<style>
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
        <a class="nav-link active" href="{{ route('a_karyawan_gaji', $id) }}">Gaji</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_absen', $id) }}">Absen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('a_karyawan_ttdt', $id) }}">TTDT</a>
    </li>
</ul>

<a href="#" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#modal-add">Tambah</a>
<div class="clearfix"></div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
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

<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-add">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    @include('admin.karyawan.modalKaryawanGaji', ['jenis' => 'tambah'])
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
                <h5 class="modal-title">Edit gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-edit">
                <div class="modal-body">
                    <div class="alert alert-danger error-message d-none" role="alert">
                        <ul class="m-0"></ul>
                    </div>

                    @include('admin.karyawan.modalKaryawanGaji', ['jenis' => 'edit'])
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
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        processing: true,
        ajax: '{{ route("a_karyawan_gaji", $id) }}',
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

    var optionCk = {
        extraPlugins: 'autogrow',
        autoGrow_minHeight: 200,
        autoGrow_maxHeight: 400,
        autoGrow_bottomSpace: 0,
        resize_enabled : false,
        height: 200,
        removePlugins: 'elementspath',
        filebrowserBrowseUrl: '{{ env("APP_URL") }}' + '/karyawan/storage?type=Files',
        filebrowserUploadUrl: '{{ env("APP_URL") }}' + '/karyawan/storage/upload?type=Files&_token=',
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'doctools', 'document' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            '/',
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        removeButtons: 'Source,Templates,Save,NewPage,ExportPdf,Preview,Print,Cut,Copy,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,Outdent,Indent,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Table,HorizontalRule,Smiley,PageBreak,Iframe,Styles,Format,Font,FontSize,ShowBlocks,About'
    };

    CKEDITOR.plugins.addExternal('autogrow', '{{ asset("vendor/autogrow/plugin.js") }}');
    CKEDITOR.replace('deskripsi-tambah', optionCk);
    CKEDITOR.replace('deskripsi-edit', optionCk);


    $('#modal-add').on('shown.bs.modal', function() {
        $('#modal-add input[name="no"]').trigger('focus');
        $('#modal-add input[name="karyawan_id"]').val('{{ $id }}');
    });
    var add = $("#modal-add button[name='submit']");
    add.click(function(e) {
        e.preventDefault();

        add.attr("disabled", true);
        add.text('Loading');

        $('.error-message').addClass('d-none');
        $('.error-message ul').empty();

        var form = new FormData($('#form-add')[0]);
        form.append('deskripsi', CKEDITOR.instances['deskripsi-tambah'].getData());
        form.append('aksi', 'tambah');

        var opt = {
            type: 'aGaji',
            method: 'POST',
            aksi: 'tambah',
            url: '{{ route("a_karyawan_gajiTambah", $id) }}',
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


    $('#table').on('click', '.detail', function(e) {
        e.preventDefault();

        var url = "{{ route('a_karyawan_gajiDetail', ['id' => ':id', 'id_gaji' => ':id_gaji']) }}";
        url = url.replace(':id', '{{ $id }}');
        url = url.replace(':id_gaji', $(this).data('id'));
        
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

    var modalEditInput = $('#modal-edit input');
    var modalEditTextArea = $('#modal-edit textarea');
    var modalEditSelect = $('#modal-edit select');
    var modalEditNo = $('#modal-edit input[name="no"]');
    var modalEditTanggalAwal = $('#modal-edit input[name="tanggal_awal"]');
    var modalEditTanggalAkhir = $('#modal-edit input[name="tanggal_akhir"]');
    var modalEditKeterangan = $('#modal-edit input[name="keterangan"]');
    var modalEditStatus = $('#modal-edit #status');
    $('#modal-edit').on('hidden.bs.modal', function () {
        modalEditInput.val("");
        modalEditTextArea.val("");
        $('#modal-edit input[name="karyawan_id"]').val('{{ $id }}');
        $('#modal-edit input[name="status"]').removeAttr('value');
    });
    $('#table').on('click', '.edit', function(e) {
        e.preventDefault();

        var url = "{{ route('a_karyawan_gajiShow', ['id' => ':id', 'id_gaji' => ':id_gaji']) }}";
        url = url.replace(':id', '{{ $id }}');
        url = url.replace(':id_gaji', $(this).data('id'));

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
            
            modalEditNo.trigger('focus');

            modalEditNo.val(msg.data.no);
            modalEditTanggalAwal.val(msg.data.tanggal_awal);
            modalEditTanggalAkhir.val(msg.data.tanggal_akhir);
            modalEditKeterangan.val(msg.data.keterangan);
            
            if(msg.data.status == 'l') {
                modalEditStatus.prop("checked", true);
            }else{
                modalEditStatus.prop("checked", false);
            }

            CKEDITOR.instances['deskripsi-edit'].setData(msg.data.deskripsi);
        }).fail(function(err) {
            alert("Terjadi kesalahan pada server");
            modalEditInput.attr("disabled", false);
            modalEditTextArea.attr("disabled", false);
            modalEditSelect.attr("disabled", false);
            modalEditStatus.attr("disabled", false);
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
        form.append('deskripsi', CKEDITOR.instances['deskripsi-edit'].getData());
        form.append('aksi', 'edit');
        form.append('_method', 'PATCH');

        var url = "{{ route('a_karyawan_gajiEdit', ['id' => ':id', 'id_gaji' => ':id_gaji']) }}";
        url = url.replace(':id', '{{ $id }}');
        url = url.replace(':id_gaji', $(this).data('id'));

        var opt = {
            type: 'aGaji',
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
        var url = "{{ route('a_karyawan_gajiHapus', ['id' => ':id', 'id_gaji' => ':id_gaji']) }}";
        url = url.replace(':id', '{{ $id }}');
        url = url.replace(':id_gaji', $(this).data('id'));

        var opt = {
            url: url,
            type: 'aGaji',
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