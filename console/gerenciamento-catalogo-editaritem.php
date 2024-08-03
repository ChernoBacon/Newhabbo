<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_catalogo_item_editar")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-editaritem-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-editaritem-titulo']; ?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-editaritem-titulo']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-editaritem-texto2']; ?></p>
									</div>
									<?php
									if (isset($_POST['salvar']) && PHBPermissoes::Check("gerenciamento_catalogo_item_editar")) {
										if (isset($_POST['catalog_name']) && isset($_POST['item_ids']) && isset($_POST['cost_credits']) && isset($_POST['cost_points']) && isset($_POST['amount']) && isset($_POST['limited_stack']) && isset($_POST['order_number'])) {
											$update = $db->prepare("UPDATE catalog_items SET catalog_name = :catalog_name, item_ids = :item_ids, page_id = :page_id, cost_credits = :cost_credits, cost_points = :cost_points, points_type = :points_type, amount = :amount, limited_stack = :limited_stack, order_number = :order_number, extradata = :extradata WHERE id = :id");
											$update->bindValue(":catalog_name", $_POST['catalog_name']);
											$update->bindValue(":item_ids", $_POST['item_ids']);
											$update->bindValue(":page_id", $_POST['page_id']);
											$update->bindValue(":cost_credits", $_POST['cost_credits']);
											$update->bindValue(":cost_points", $_POST['cost_points']);
											$update->bindValue(":points_type", $_POST['points_type']);
											$update->bindValue(":amount", $_POST['amount']);
											$update->bindValue(":limited_stack", $_POST['limited_stack']);
											$update->bindValue(":order_number", $_POST['order_number']);
											$update->bindValue(":extradata", $_POST['extradata']);
											$update->bindValue(":id", $_GET['id']);
											if ($update->execute()) {
												PHBLogger::AddLog($lingua['gerenciamento-editaritem-log'] . " \"" . $_POST['catalog_name'] . "\" (" . $_GET['id'] . ").");
												echo '<div class="alert alert-solid-success" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['gerenciamento-editaritem-sucesso'] . '</div>';
											} else {
												echo '<div class="alert alert-solid-danger" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['gerenciamento-editaritem-errosql'] . '</div>';
											}
										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span></button>
										<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['gerenciamento-editaritem-erro'] . '</div>';
										}
									}

									$obtemItems = $db->prepare("SELECT * FROM catalog_items WHERE id = :id;");
									$obtemItems->bindValue(":id", $_GET['id']);
									$obtemItems->execute();
									$item = $obtemItems->fetch();

									?>
									<form method="post" enctype="multipart/form-data">
										<div class="row row-sm">

											<div class="col-md-4">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-nome']; ?></p>
													<input type="text" class="form-control" name="catalog_name" placeholder="<?= $lingua['gerenciamento-editaritem-nome-placeholder']; ?>" value="<?= $item['catalog_name'] ?>" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-itemids']; ?></p>
													<input type="text" class="form-control" name="item_ids" placeholder="<?= $lingua['gerenciamento-editaritem-itemids-placeholder']; ?>" value="<?= $item['item_ids'] ?>" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-pagina']; ?></p>
													<select class="form-control" name="page_id">
														<?php
														$getCatalogPages = $db->prepare("SELECT id,caption FROM catalog_pages WHERE page_layout != \"info_duckets\" and page_layout != \"marketplace_own_items\" 
													and page_layout != \"marketplace\" and page_layout != \"badge_display\" and page_layout != \"bots\"  and page_layout != \"soundmachine\" and page_layout != \"trophies\" and page_layout != \"roomads\" and page_layout != \"single_bundle\" and page_layout != \"recycler\"  and page_layout != \"pets3\" and page_layout != \"pets\" and enabled = \"1\" and visible = \"1\"  ORDER BY id");
														$getCatalogPages->execute();
														while ($page = $getCatalogPages->fetch()) {
														?>
															<option value="<?= $page['id'] ?>" <?php if ($page['id'] == $item['page_id']) echo "selected"; ?>><?= $page['caption']; ?> - <?= $page['id'] ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-creditos']; ?></p>
													<input type="number" class="form-control" name="cost_credits" value="<?= $item['cost_credits'] ?>" required>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-pontos']; ?></p>
													<input type="number" class="form-control" name="cost_points" value="<?= $item['cost_points'] ?>" required>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-tipo-pontos']; ?></p>
													<select class="form-control" name="points_type">
														<option value="0" <?php if ($item['points_type'] == "0") echo "selected"; ?>><?= $config['NomeCurrency.0']; ?></option>
														<option value="5" <?php if ($item['points_type'] == "5") echo "selected"; ?>><?= $config['NomeCurrency.5']; ?></option>
														<option value="<?= $config['SeasonalID']; ?>" <?php if ($item['points_type'] == $config['SeasonalID']) echo "selected"; ?>><?= $config['NomeCurrency.' . $config['SeasonalID']]; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-quantidade']; ?></p>
													<input type="number" class="form-control" name="amount" value="<?= $item['amount'] ?>" required>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-estoque']; ?></p>
													<input type="number" class="form-control" name="limited_stack" value="<?= $item['limited_stack'] ?>" required>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-ordem']; ?></p>
													<input type="number" class="form-control" name="order_number" value="<?= $item['order_number'] ?>" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editaritem-extradata'] ?></p>
													<input type="text" class="form-control" name="extradata" value="<?= $item['extradata'] ?>">
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="salvar" style="float: right;margin-top: 15px;"><?= $lingua['gerenciamento-editaritem-salvar']; ?></button>
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