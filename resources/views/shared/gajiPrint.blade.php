<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; }
        hr { border: 1px solid #E2E5E9; }
        h1 { color: #1089FF; font-size: 24px; margin-bottom: 0; }

        .float-left { float: left; }
        .float-right { float: right; }
        .clear { clear: both; }
        .text-center { text-align: center; }

        .logo {
            width: 140px;
            margin-top: 60px;
        }
        .polygon {
            width: 300px;
            margin-top: -45px;
            margin-right: -45px;
        }
        .blue { color: #1089FF; }

        table tr td {
            color: #414A58;
            vertical-align: middle;
            height: 25px;
        }
        table tr td:nth-child(1) {
            width: 220px;
        }
        table tr td:nth-child(2) {
            width: 20px;
            text-align: center;
        }

        .catatan {
            width: 430px;
            padding: 10px;
            background-color: #FAFAFA;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        function rupiah($angka) {
            $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
            return $hasil_rupiah;
        }
    @endphp
    <div>
        <img src="{{ public_path('img/logo.png') }}" class="logo float-left">
        <img src="{{ public_path('img/polygon.svg') }}" class="polygon float-right">
        <div class="clear"></div>
    </div>
    
    <h1 class="text-center">SLIP GAJI PEGAWAI</h1>

    <hr>
    <table>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $tanggal_print }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>{{ $gaji['tgl'] }}</td>
        </tr>
    </table>

    <hr>
    <table>
        <tr>
            <td>ID Pegawai</td>
            <td>:</td>
            <td>{{ $karyawan->id }}</td>
        </tr>
        <tr>
            <td>Nama Pegawai</td>
            <td>:</td>
            <td>{{ $karyawan->nama }}</td>
        </tr>
        <tr>
            <td>Posisi/Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan->nama }}</td>
        </tr>
    </table>

    <hr>
    <b style="margin-left: 4px">Penghasilan</b>
    <table>
        {!! $gaji['table_penghasilan'] !!}
    </table>
    <hr>
    <table>
        <tr>
            <td><b class="blue">Sub Total</b></td>
            <td></td>
            <td><b class="blue">{{ rupiah($gaji['sub_total_hasil']) }}</b></td>
        </tr>
    </table>
    <hr>

    <b style="margin-left: 4px">Potongan</b>
    <table>
        {!! $gaji['table_potongan'] !!}
    </table>
    <hr>
    <table>
        <tr>
            <td><b class="blue">Total Penghasilan Bersih</b></td>
            <td></td>
            <td><b class="blue">{{ rupiah($gaji['gaji_bersih']) }}</b></td>
        </tr>
    </table>
    <hr>

    <div>
        <div class="catatan float-left">
            <span>Catatan: {!! $data->deskripsi ?? '<p>Pembayaran gaji dilakukan dengan sistem transfer</p>' !!}</span>
        </div>
        <div class="text-center float-right" style="margin-right: 20px">
            <p>Hormat Kami</p>
            <br><br>
            <p>
                <b class="blue">Ujang Fahmi</b><br>
                Direktur Utama
            </p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>