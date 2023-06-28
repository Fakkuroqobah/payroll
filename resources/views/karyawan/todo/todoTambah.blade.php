@extends('layouts.karyawan.index')

@section('title', 'Ttdt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('k_ttdt_index') }}">Things to do today</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@push('styles')

@endpush

@section('content')
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="POST" id="form-add">
            <h3>Tambah Things To Do Today</h3>
            <p>Tanggal : {{ date('l, d F Y', strtotime($data->tanggal)) }}</p>

            <div class="alert alert-danger error-message d-none" role="alert">
                <ul class="m-0"></ul>
            </div>
            
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukan Judul">
            </div>
            <div class="form-group">
                <label for="isi">Isi</label>
                <textarea name="isi" id="isi" cols="30" rows="10"></textarea>
            </div>

            <a href="{{ route('k_ttdt_index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" name="submit" class="btn btn-primary float-right">Simpan</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
$(document).ready(function() {
    CKEDITOR.plugins.addExternal('autogrow', '{{ asset("vendor/autogrow/plugin.js") }}');
    CKEDITOR.replace('isi', {
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
    });

    
    var add = $("button[name='submit']");
    add.click(function(e) {
        e.preventDefault();

        add.attr("disabled", true);
        add.text('Loading');

        $('.error-message').addClass('d-none');
        $('.error-message ul').empty();

        var form = new FormData($('#form-add')[0]);
        form.append('isi', CKEDITOR.instances['isi'].getData());
        form.append('aksi', 'tambah');

        var opt = {
            type: 'kTtdt',
            method: 'POST',
            aksi: 'tambah',
            url: '{{ route("k_ttdt_store", $id) }}',
            element: add
        };

        var txt = {
            btnText: 'Tambah Data',
            msgAlert: 'Data berhasil ditambahkan',
            msgText: 'ditambah'
        };

        requestAjaxPost(opt, form, txt);
    });
});
</script>
@endpush