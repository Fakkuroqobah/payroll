<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Payroll">
    <meta name="author" content="Agung Maulana">

    <title>Payroll | Login</title>

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
	<style>
		.logo {
			width: 60%;
		}
    </style>
</head>
<body class="bg-gradient-primary-">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-xl-6 col-lg-6 col-md-8">
			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<div class="p-5">
						<div class="text-center">
							<img src="{{ asset('img/logo.png') }}" class="logo mb-4">
							<br />
							<h1 class="h4 text-gray-900 mb-4">Payroll Kedata</h1>
						</div>
						
						@if (session('danger'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ Session::get('danger') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif

						<form action="{{ route('k_login') }}" method="POST" class="user">
							@csrf

							<div class="form-group">
								<input type="email" name="email" class="form-control form-control-user" placeholder="Masukan Email" autofocus required>
							</div>
							
							<div class="form-group">
								<input type="password" name="password" class="form-control form-control-user" placeholder="Masukan Kata Sandi" required>
							</div>

							<button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- Footer --}}
<footer class="sticky-footer bg-white">
	<div class="container my-auto">
		<div class="copyright text-center my-auto">
			<span>© {{ date('Y') }} PT KEDATA INDONESIA Powered By Agung Maulana</span>
		</div>
	</div>
</footer>
{{-- End of Footer --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>