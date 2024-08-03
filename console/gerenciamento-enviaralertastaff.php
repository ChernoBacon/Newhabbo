<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_enviaralertastaff")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-alertastaff-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-alertastaff-titulo']; ?></title>
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
	<link href="/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
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
					<div class="row row-sm mt-xl-4">
						<div class="col-lg-12 col-md-12">
							<div class="card custom-card">
								<div class="card-body">
									<div>
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-alertastaff-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-alertastaff-texto2']; ?></p>
									</div>
									<?php
									if (isset($_POST['enviar-alerta']) && PHBPermissoes::Check("gerenciamento_enviaralertastaff")) {
										if (isset($_POST['conteudo']) && $_POST['conteudo'] != "") {
											$result = PHBSocket::SendMessage(json_encode(['key' => 'staffalert', 'data' => ['message' => $_POST['conteudo']]]));
											if ($json = json_decode($result)) {
												if ($json->status == "0") {
													echo '<div class="alert alert-solid-success" role="alert">
													<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span></button>
													<strong>'.$lingua['generico-sucesso'].'</strong> ' . $lingua['gerenciamento-alertastaff-sucesso'] . '</div>';
													PHBLogger::AddLog($lingua['gerenciamento-alertastaff-log']);
												} else {
													echo '<div class="alert alert-solid-danger" role="alert">
													<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span></button>
													<strong>'.$lingua['generico-erro'].'</strong> 	' . $lingua['gerenciamento-alertastaff-erro'] . ' (' . $json->status . ').</div>';
												}
											} else {
												echo '<div class="alert alert-solid-danger" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>'.$lingua['generico-erro'].'</strong> ' . $result . '.</div>';
											}
										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>'.$lingua['generico-erro'].'</strong> ' . $lingua['gerenciamento-alertastaff-erro2'] . '</div>';
										}
									}
									?>
									<form method="post" enctype="multipart/form-data">
										<div class="row row-sm">
											<div class="col-md-12">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-alertastaff-conteudo']; ?></p>
													<input type="text" class="form-control" name="conteudo" placeholder="<?= $lingua['gerenciamento-alertastaff-conteudo-placeholder']; ?>">
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="enviar-alerta" style="float: right;margin-top: 15px;"><?= $lingua['gerenciamento-alertastaff-botao']; ?></button>
									</form>
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
	<!-- Internal Dashboard js-->
	<script src="/assets/js/index.js"></script>
	<!-- Internal Fileuploads js-->
	<script src="/assets/plugins/fileuploads/js/hospedaremblema.js"></script>
	<script src="/assets/plugins/fileuploads/js/file-upload.js"></script>
</body>

</html>