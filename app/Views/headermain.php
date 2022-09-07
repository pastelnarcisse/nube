<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=empty($module->name) ? 'NARCISSE' : $module->name?></title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/fontawesome-free/css/all.min.css')?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')?>">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/jqvmap/jqvmap.min.css')?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url('public/dist/css/adminlte.css')?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/daterangepicker/daterangepicker.css')?>">
	<!-- summernote -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/summernote/summernote-bs4.min.css')?>">
	<!-- multiple select -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/multiple-select-1.5.2/dist/multiple-select.css')?>">
	<!-- datatables -->
	<link rel="stylesheet" href="<?=base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
	<!-- spinner -->
	<link rel="stylesheet" href="<?=base_url('public/assets/css/spinner.css')?>">
	<!-- GLOBAL JS -->
	<script type="text/javascript">
		var base_url = '<?=base_url();?>';
	</script>

	<!-- jQuery -->
	<script src="<?=base_url('public/plugins/jquery/jquery.min.js')?>"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?=base_url('public/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button)
	</script>

	<!-- Bootstrap 4 -->
	<script src="<?=base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
	
	<!-- ChartJS -->
	<script src="<?=base_url('public/plugins/chart.js/Chart.min.js')?>"></script>
	<!-- Sparkline -->
	<script src="<?=base_url('public/plugins/sparklines/sparkline.js')?>"></script>
	<!-- JQVMap -->
	<script src="<?=base_url('public/plugins/jqvmap/jquery.vmap.min.js')?>"></script>
	<script src="<?=base_url('public/plugins/jqvmap/maps/jquery.vmap.usa.js')?>"></script>
	<!-- jQuery Knob Chart -->
	<script src="<?=base_url('public/plugins/jquery-knob/jquery.knob.min.js')?>"></script>
	<!-- daterangepicker -->
	<script src="<?=base_url('public/plugins/moment/moment-with-locales.min.js')?>"></script>
	<script src="<?=base_url('public/plugins/daterangepicker/daterangepicker.js')?>"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?=base_url('public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js?v=1.3')?>"></script>
	<!-- Summernote -->
	<script src="<?=base_url('public/plugins/summernote/summernote-bs4.min.js')?>"></script>
	<!-- overlayScrollbars -->
	<script src="<?=base_url('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')?>"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url('public/dist/js/adminlte.js')?>"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?=base_url('public/dist/js/demo.js')?>"></script> 
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url('public/dist/js/pages/dashboard.js?v=1.1')?>"></script>
	<!-- multiple select -->
	<script src="<?=base_url('public/plugins/multiple-select-1.5.2/dist/multiple-select-es.js')?>"></script>
	<!-- datatables -->
	<script src="<?=base_url('public/plugins/datatables/jquery.dataTables.min.js')?>"></script>
	<script src="<?=base_url('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>

	<script src="<?=base_url('public/assets/js/app.min.js')?>"></script>

	<script src="<?=base_url('public/assets/js/callpost.js?v=1.01')?>"></script>
	

</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

	<!-- Preloader -->
	<div class="preloader flex-column justify-content-center align-items-center">
		<img class="animation__shake" src="<?=base_url('public/assets/images/login/logo.png')?>" alt="NarcisseLogo" height="100" width="150">
	</div>

	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="<?=base_url()?>">NARCISSE</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">

				<?php foreach ($menus as $menu): ?>

					<li class="nav-item">
						<a class="nav-link" href="<?=base_url($menu->menuLink)?>"> <?=$menu->menuName?> <span class="sr-only">(current)</span></a>
					</li>
					
				<?php endforeach ?>

					<li class="nav-item">
						<a class="nav-link" href="<?=base_url('auth/logout')?>"> Cerra Sesión <span class="sr-only">(current)</span></a>
					</li>

			</ul>

		</div>
	</nav>
