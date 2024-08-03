<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("noticia_escrever")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['noticia-escrever-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['noticia-escrever-titulo']; ?></title>
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
	<!-- Internal Datetimepicker-slider css -->
	<link href="/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css" rel="stylesheet">
	<?php
	if ($config['TemaHK'] == "dark") {
		$skin_ck = "moono-dark";
		echo "<style>.datetimepicker .datetimepicker-days table thead tr:last-child th {color: #3c4858;}.dark-theme .datetimepicker table th.prev, .dark-theme .datetimepicker table th.next, .dark-theme .datetimepicker table th.switch {background-color: #0e0e23;color: #334151;}.datetimepicker table td {background: #0e0e23;}.datetimepicker table th.dow {background: #0e0e23;}</style>";
	} else {
		$skin_ck = "moono-lisa";
	}
	?>
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
										<h6 class="main-content-label mb-1"><?= $lingua['noticia-escrever-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['noticia-escrever-texto2']; ?></p>
									</div>
									<?php
									error_reporting(E_ALL);
									ini_set('display_errors', 1);
									
									if (isset($_POST['postar-noticia']) && PHBPermissoes::Check("noticia_escrever")) {

										if (isset($_POST['titulo']) && isset($_POST['subtitulo']) && isset($_POST['link_imagem']) && isset($_POST['conteudo']) && $_POST['titulo'] != "" && $_POST['subtitulo'] != "" && $_POST['link_imagem'] != "" && $_POST['conteudo'] != "" ) {
											
										try{
											$postarNoticia = $db->prepare("INSERT INTO `website_news` (`slug`, `title`, `short_story`, `full_story`, `images`, `author`, `header`, `category`, `form`, `timestamp`) VALUES (?, ?, ?, ?, '', ?, ?, ?, ?, ?);");
											$postarNoticia->bindValue(1, strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['titulo']))));
											$postarNoticia->bindValue(2, $_POST['titulo']);
											$postarNoticia->bindValue(3, $_POST['subtitulo']);
											$postarNoticia->bindValue(4, $_POST['conteudo']);
											$postarNoticia->bindValue(5, $_SESSION['id_painel']);
											$postarNoticia->bindValue(6, $_POST['link_imagem']);
											$postarNoticia->bindValue(7, '1');
											$postarNoticia->bindValue(8, 'none');
											$postarNoticia->bindValue(9, time());
											$postarNoticia->execute();
											$idNoticia = $db->lastInsertId();
										}
										catch(Exception $e){
											echo $e;
										}
										
										PHBLogger::AddLog($lingua['noticia-log'] . "\"" . $_POST['titulo'] . "\" (" . $idNoticia . ").");
										PHBLogger::NotificarNoticia($idNoticia);

											echo '<div class="alert alert-solid-success" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-sucesso'] . '!</strong> ' . $lingua['noticia-sucesso'] . '</div>';

										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-erro'] . '!</strong> ' . $lingua['noticia-erro'] . '</div>';
										}
									}
									?>
									<form method="post">
										<div class="row row-sm">

											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['noticia-titulo']; ?></p>
													<input type="text" class="form-control" name="titulo" placeholder="<?= $lingua['noticia-titulo-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['noticia-subtitulo']; ?></p>
													<input type="text" class="form-control" name="subtitulo" placeholder="<?= $lingua['noticia-subtitulo-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['noticia-link-imagem']; ?></p>
													<input type="text" class="form-control" name="link_imagem" placeholder="<?= $lingua['noticia-link-imagem-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-12 ">
												<div class="form-group mb-0">
													<p class="mg-b-10"><?= $lingua['noticia-conteudo']; ?></p>
													<textarea class="form-control" name="conteudo" rows="4" id="ckeditor"></textarea>
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="postar-noticia" style="float: right;margin-top: 15px;"><?= $lingua['noticia-postar']; ?></button>
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
	<!-- Internal jquery-simple-datetimepicker js -->
	<script src="/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
	<!-- CK Editor -->
	<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('ckeditor');
		CKEDITOR.config.skin = "<?= $skin_ck; ?>";
	</script>
</body>

</html>