<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Payroll">
    <meta name="author" content="Agung Maulana">

    <title>Payroll | @yield('title')</title>

    {{-- Favicon --}}
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }} ">

    {{-- Custom fonts for this template --}}
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    {{-- Custom styles for this template --}}
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('styles')

    <style>
        .text-responsive { font-size: calc(100% + .5vw); }
        .swal2-top-end .swal2-title { color: white !important; }
    </style>
</head>
<body id="page-top">
@php
    function tgl_indo($tanggal, $cetak_hari = false) {
        $hari = array ( 
            1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array (
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        
        $split 	  = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        
        // variabel pecahkan 0 = tahun
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tanggal
    
        return $tgl_indo;
    }
@endphp
<div id="wrapper">
    {{-- Sidebar --}}
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-money-bill"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Payroll</div>
        </a>

        <hr class="sidebar-divider my-0">
        <li class="nav-item {{ Request::segment(2) === 'karyawan' ? 'active' : null }}">
            <a class="nav-link" href="{{ route('a_karyawan_index') }}">
                <i class="fas fa-users"></i>
                <span>Karyawan</span>
            </a>
        </li>
        <li class="nav-item {{ Request::segment(2) === 'level' ? 'active' : null }}">
            <a class="nav-link" href="{{ route('a_level_index') }}">
                <i class="fas fa-id-badge"></i>
                <span>Level</span>
            </a>
        </li>
        <li class="nav-item {{ Request::segment(2) === 'jabatan' ? 'active' : null }}">
            <a class="nav-link" href="{{ route('a_jabatan_index') }}">
                <i class="fas fa-user-tag"></i>
                <span>Jabatan</span>
            </a>
        </li>
        <li class="nav-item {{ Request::segment(2) === 'komponen' ? 'active' : null }}">
            <a class="nav-link" href="{{ route('a_komponen_index') }}">
                <i class="fas fa-address-card"></i>
                <span>Komponen</span>
            </a>
        </li>
        
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    {{-- End of Sidebar --}}

    {{-- Content Wrapper --}}
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                {{-- Sidebar Toggle (Topbar) --}}
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
                    {{ tgl_indo(date('Y-m-d'), true) }}
                </div>

                {{-- Topbar Navbar --}}
                <ul class="navbar-nav ml-auto">
                    {{-- Nav Item - User Information --}}
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-lg-inline text-gray-600 small">{{ ucfirst(Auth::guard('admin')->user()->username) }}</span>
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('a_logout') }}">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            {{-- End of Topbar --}}

            <div class="container-fluid">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>

                {{-- Content Row --}}
                @yield('content')
            </div>
        </div>
        {{-- End of Main Content --}}

        {{-- Footer --}}
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© {{ date('Y') }} PT KEDATA INDONESIA Powered By Agung Maulana</span>
                </div>
            </div>
        </footer>
        {{-- End of Footer --}}
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.modal').on('hidden.bs.modal', function() {
        $('.error-message').addClass('d-none');
        $('.error-message ul').empty();
    });
});
</script>
@include('shared.ajax')
@stack('scripts')
</body>
</html>