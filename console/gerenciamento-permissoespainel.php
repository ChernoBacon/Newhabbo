<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_permissoespainel")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-permissoespainel-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-permissoespainel-titulo']; ?></title>
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
						<div class="col-lg-12 mt-xl-4">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div>
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-permissoespainel-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-permissoespainel-texto2']; ?></p>
									</div>
									<div class="table-responsive">
										<table class="table" id="example1">
											<thead>
												<tr>
													<th><?= $lingua['gerenciamento-permissoespainel-id']; ?></th>
													<th><?= $lingua['gerenciamento-permissoespainel-nome']; ?></th>
													<?php if (PHBPermissoes::Check("gerenciamento_permissoespainel_editar")) { ?><th></th><?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php
												$obtemPermissoes = $db->prepare("SELECT phb_hk_permissoes.id, permissions.rank_name FROM phb_hk_permissoes, permissions WHERE permissions.id = phb_hk_permissoes.id;");
												$obtemPermissoes->execute();
												while ($permissao = $obtemPermissoes->fetch()) {
												?>
													<tr>
														<td class="text-primary"><?= $permissao['id']; ?></td>
														<td class="text-primary"><?= $permissao['rank_name']; ?></td>
														<?php if (PHBPermissoes::Check("gerenciamento_permissoespainel_editar")) { ?><td class="text-primary"><a href="/gerenciamento-editarpermissaopainel/<?= $permissao['id']; ?>"><img draggable="false" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAoElEQVQ4jcWTOw4DIQxEh2jPRG13PhU1vhEV4mbeCuQQVssmRVwivZnxh4CbIiKb31pr4Y4bcK3Vaq1mZpZztpXgT/CxI6aqKKW8Re9ir604i3QpJWwLxBgH6OFHCVJKEBF0mJmvBYjIROQyCTNDVddOfvo557EFX34jxwz7/rzjXB/H5J2969bh/A/GtIXeKzNvf5gh8A0MAGGO+gQGgBNnXrPwXhYvmQAAAABJRU5ErkJggg=="></a></td><?php } ?>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
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