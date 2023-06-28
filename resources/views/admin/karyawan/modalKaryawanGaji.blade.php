<input type="hidden" name="karyawan_id" value="{{ $id }}">

@if ($jenis == 'edit')
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="status" name="status">
        <label class="custom-control-label" for="status">Status gaji</label>
    </div>
</div>
@endif

<div class="form-group">
    <label>Nomor</label>
    <input type="text" name="no" class="form-control" placeholder="Masukan nomor gaji" autofocus autocomplete="off">
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal awal gaji</label>
            <input type="date" name="tanggal_awal" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal akhir gaji</label>
            <input type="date" name="tanggal_akhir" class="form-control">
        </div>
    </div>
</div>
<div class="form-group">
    <label>Untuk pembayaran</label>
    <input type="text" name="keterangan" class="form-control" placeholder="Masukan keterangan">
</div>
<div class="form-group">
    <label>Deskripsi (optional)</label>
    <textarea name="deskripsi" id="deskripsi-{{ $jenis }}" cols="30" rows="10"></textarea>
</div>