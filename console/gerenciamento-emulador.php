<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_emulador")) {
	header("Location: /index");
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-emulador-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-emulador-titulo']; ?></title>
	<!-- Bootstrap css-->
	<link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<!-- Icons css-->
	<link href="/assets/plugins/web-fonts/icons.css" rel="stylesheet" />
	<link href="/assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet" />
	<link href="/assets/plugins/web-fonts/plugin.css" rel="stylesheet" />
	<!-- Style css-->
	<link href="/assets/css/style.css" rel="stylesheet" />
	<link href="/assets/css/skins.css" rel="stylesheet" />
	<link href="/assets/css/dark-style.css" rel="stylesheet" />
	<link href="/assets/css/colors/default.css" rel="stylesheet" />
	<!-- Color css-->
	<link id="theme" rel="stylesheet" type="text/css" media="all" href="/assets/css/colors/color.css" />
	<!-- Select2 css-->
	<link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
	<!-- Sidemenu css-->
	<link href="/assets/css/sidemenu/sidemenu.css" rel="stylesheet">
	<!-- Switcher css-->
	<link href="/assets/switcher/css/switcher.css" rel="stylesheet">
	<link href="/assets/switcher/demo.css" rel="stylesheet">
	<!-- Mutipleselect css-->
	<link rel="stylesheet" href="/assets/plugins/multipleselect/multiple-select.css">
	<!-- Internal DataTables css-->
	<link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
</head>

<body class="main-body leftmenu <?= $config['TemaHK']; ?>-theme">
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/loader.php'; ?>
	<!-- Page -->
	<div class="page">
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/side-menu.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/main-header.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/mobile-header.php'; ?>
		<!-- Main Content-->
		<div class="main-content side-content pt-0">
			<div class="container-fluid">
				<div class="inner-body">
					<!-- Row -->
					<div class="row row-sm">
						<div class="col-lg-12">

							<?php
							$socketJson = PHBSocket::SendMessage(json_encode(['key' => 'serverinfo', 'data' => ['' => '']]));
							if ($socketJson != null) {
								$socketJson = json_decode($socketJson);
								$infoJson = $socketJson->message;
								$infoJson = json_decode($socketJson->message);

								$versao_emulador = $infoJson->versaoEmu;
								$versao_plugin = $infoJson->versaoPlugin;
								$uptime = $infoJson->uptime;

							?>

								<div class="row row-sm mt-lg-4">
									<div class="col-sm-12 col-lg-12 col-xl-12">
										<div class="card bg-primary custom-card card-box">
											<div class="card-body p-4">
												<div class="row align-items-center">
													<div class="offset-xl-3 offset-sm-6 col-xl-8 col-sm-6 col-12 img-bg ">

														<h4 class="d-flex  mb-3">
															<span class="font-weight-bold text-white"><?= $versao_emulador; ?> & PHBPlugin v<?= $versao_plugin; ?></span>
														</h4>
														<p class="tx-white-7 mb-1"><?= $lingua['gerenciamento-emulador-uptime']; ?> <?= $uptime; ?></p>
													</div>
													<img src="https://codeigniter.spruko.com/spruha/spruha-ltr/public/assets/img/pngs/work3.png" alt="user-img" class="wd-200">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card custom-card overflow-hidden">
								<?php } else echo "<div class=\"card custom-card overflow-hidden mt-lg-4\">"; ?>
								<div class="card-body">
									<div>
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-emulador-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-emulador-texto2']; ?></p>
									</div>
									<?php
									if (isset($_POST['desligar']) && PHBPermissoes::Check("gerenciamento_emulador")) {
										if (PHBSocket::SendMessage(json_encode(['key' => 'desligar', 'data' => ['' => '']]))) {
											PHBLogger::AddLog($lingua['gerenciamento-emulador-desligar-log']);
											echo '<div class="alert alert-solid-success" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['gerenciamento-emulador-desligar-sucesso'] . '</div>';
										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['gerenciamento-emulador-desligar-erro'] . '</div>';
										}
									} else if (isset($_POST['reiniciar']) && PHBPermissoes::Check("gerenciamento_emulador")) {
										if (PHBSocket::SendMessage(json_encode(['key' => 'reiniciar', 'data' => ['' => '']]))) {
											PHBLogger::AddLog($lingua['gerenciamento-emulador-reiniciar-log']);
											echo '<div class="alert alert-solid-success" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['gerenciamento-emulador-reiniciar-sucesso'] . '</div>';
										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['gerenciamento-emulador-reiniciar-erro'] . '</div>';
										}
									} else if (isset($_POST['ligar']) && PHBPermissoes::Check("gerenciamento_emulador") && $config['ArcturusExePath'] != "") {

										$taskName = "emu".time();
										shell_exec('SCHTASKS /F /Create /TN '.$taskName.' /TR "' . $config['ArcturusExePath'] . '/run.bat' . '"" /SC DAILY /RU INTERACTIVE');
										shell_exec('SCHTASKS /RUN /TN "'.$taskName.'');
										shell_exec('SCHTASKS /DELETE /TN "'.$taskName.'" /F');
										
										PHBLogger::AddLog($lingua['gerenciamento-emulador-ligar-log']);

										echo '<div class="alert alert-solid-success" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['gerenciamento-emulador-ligar-sucesso'] . '</div>';
									}
									?>
									<center>
										<form method="post">
											<div class="btn btn-list">
												<?php if ($config['ArcturusExePath'] != "") { ?><button name="ligar" class="btn ripple btn-success"><?= $lingua['gerenciamento-emulador-ligar']; ?></button><?php } ?>
												<button name="desligar" class="btn ripple btn-danger"><?= $lingua['gerenciamento-emulador-desligar']; ?></button>
												<button name="reiniciar" class="btn ripple btn-info"><?= $lingua['gerenciamento-emulador-reiniciar']; ?></button>
											</div>
										</form>
									</center>
								</div>
								</div>
						</div>
					</div>
					<!-- End Row -->
				</div>
			</div>
		</div>
		<!-- End Main Content-->
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/footer.php'; ?>
	</div>
	<!-- Back-to-top -->
	<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>
	<!-- Jquery js-->
	<script src="/assets/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap js-->
	<script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- Perfect-scrollbar js -->
	<script src="/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<!-- Sidemenu js -->
	<script src="/assets/plugins/sidemenu/sidemenu.js"></script>
	<!-- Sidebar js -->
	<script src="/assets/plugins/sidebar/sidebar.js"></script>
	<!-- Select2 js-->
	<script src="/assets/plugins/select2/js/select2.min.js"></script>
	<!-- Sticky js -->
	<script src="/assets/js/sticky.js"></script>
	<!-- Custom js -->
	<script src="/assets/js/custom.js"></script>
	<!-- Switcher js -->
	<script src="/assets/switcher/js/switcher.js"></script>
	<!-- Internal Chart.Bundle js-->
	<script src="/assets/plugins/chart.js/Chart.bundle.min.js"></script>
	<!-- Peity js-->
	<script src="/assets/plugins/peity/jquery.peity.min.js"></script>
	<!-- Internal Morris js -->
	<script src="/assets/plugins/raphael/raphael.min.js"></script>
	<script src="/assets/plugins/morris.js/morris.min.js"></script>
	<!-- Internal Data Table js -->
	<script src="/assets/plugins/datatable/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatable/fileexport/dataTables.buttons.min.js"></script>
	<script src="/assets/js/table-data.js"></script>
</body>

</html>