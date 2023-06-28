@if ($jenis == 'edit')
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="status" name="status">
        <label class="custom-control-label" for="status">Status karyawan</label>
    </div>
</div>
@endif
<div class="form-group">
    <label for="nama">Nama</label>
    <input type="text" name="nama" class="form-control" placeholder="Masukan nama" autofocus autocomplete="off">
</div>
<div class="form-group">
    <label for="pendidikan">Pendidikan</label>
    <input type="text" name="pendidikan" class="form-control" placeholder="Masukan pendidikan">
</div>
<div class="form-group">
    <label for="jk">Jenis Kelamin</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input radio-lk" type="radio" name="jk" value="laki-laki">
        <label class="form-check-label" for="laki-laki">Laki-laki</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input radio-pr" type="radio" name="jk" value="perempuan">
        <label class="form-check-label" for="perempuan">Perempuan</label>
    </div>
</div>
<div class="form-group">
    <label for="no_hp">Nomor HP</label>
    <input type="number" name="no_hp" class="form-control" placeholder="Masukan nomor hp">
</div>
<div class="form-group">
    <label for="alamat">Alamat</label>
    <textarea name="alamat" class="form-control" cols="5" rows="2" placeholder="Masukan alamat"></textarea>
</div>
<div class="form-group">
    <label for="jam_kerja">Jam kerja</label>
    <select name="jam_kerja" class="form-control">
        <option value="">--Masukan jenis jam kerja--</option>
        <option value="full time">Full Time</option>
        <option value="part time">Part Time</option>
    </select>
</div>
<div class="form-group">
    <label for="tugas">Tugas</label>
    <textarea name="tugas" class="form-control" cols="5" rows="2" placeholder="Masukan tugas"></textarea>
</div>
<div class="form-group">
    <label for="npwp">NPWP (optional)</label>
    <input type="text" name="npwp" class="form-control" placeholder="Masukan npwp (optional)">
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="bank">Bank</label>
            <select name="bank" class="form-control">
                <option value="">--Masukan nama bank--</option>
                <option value="BNI">BNI</option>
                <option value="BRI">BRI</option>
                <option value="BCA">BCA</option>
                <option value="MANDIRI">MANDIRI</option>
                <option value="MANDIRI SYARIAH">MANDIRI SYARIAH</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="no_rek">Nomor rekening</label>
            <input type="text" name="no_rek" class="form-control" placeholder="Masukan nomor rekening">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="awal_kontrak">Awal kontrak</label>
            <input type="date" name="awal_kontrak" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="akhir_kontrak">Akhir kontrak (optional)</label>
            <input type="date" name="akhir_kontrak" class="form-control">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="foto">Foto (optional)</label>
            <input type="file" name="foto" class="form-control" onchange="loadImg()" accept="image/*">
        </div>
    </div>
    <div class="col-md-6">
        <img src="{{ asset('img/img-empty.png') }}" class="foto-karyawan-edit mb-2">
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukan Email">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="telegram_id">ID Telegram</label>
            <input type="text" name="telegram_id" class="form-control" placeholder="Masukan ID Telegram">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="tingkat">Tingkat</label>
    <select name="tingkat" class="form-control">
        <option value="">--Masukan jenis tingkat--</option>
        <option value="senior">Senior</option>
        <option value="junior">Junior</option>
    </select>
</div>
<div class="form-group">
    <label for="jabatan_id">Jabatan</label>
    <select name="jabatan_id" class="form-control">
        <option value="">--Masukan jabatan--</option>
        @foreach ($jabatan as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="level">Level</label>
    <select name="level_id" class="form-control">
        <option value="">--Masukan level--</option>
        @foreach ($level as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Masukan password">
</div>