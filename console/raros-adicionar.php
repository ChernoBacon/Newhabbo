<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("raros_adicionar")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['raros-adicionar-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['raros-adicionar-titulo']; ?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['raros-adicionar-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['raros-adicionar-texto2']; ?></p>
									</div>
									<?php
									if (isset($_POST['add-raro']) && PHBPermissoes::Check("raros_adicionar")) {

										if (isset($_POST['catalog_name']) && $_POST['catalog_name'] != "" &&  isset($_POST['item_id']) && $_POST['item_id'] != "" && isset($_POST['estoque']) && $_POST['estoque'] != "" && isset($_POST['page_id']) && $_POST['page_id'] != "") {

											$itemids = $_POST['item_id'];

											$checkItemId = $db->prepare("SELECT id FROM items_base WHERE id = :id;");
											$checkItemId->bindValue(':id', $_POST['item_id']);
											$checkItemId->execute();
											$countItemId = $checkItemId->rowCount();

											$checkPageId = $db->prepare("SELECT id FROM catalog_pages WHERE id = :id;");
											$checkPageId->bindValue(':id', $_POST['page_id']);
											$checkPageId->execute();
											$countPageId = $checkPageId->rowCount();

											if ($countItemId == 0) {
												echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['raros-adicionar-erro1'] . '</div>';
											} else if ($countPageId == 0) {
												echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['raros-adicionar-erro2'] . '</div>';
											} else {

												if ($_POST['emblema'] != "") {
													$adicionabadge = $db->prepare("INSERT INTO `items_base` (`public_name`, `item_name`, `type`, `interaction_type`) VALUES (:emb1, :emb2, 'b', 'badge');");
													$adicionabadge->bindValue(':emb1', $_POST['emblema']);
													$adicionabadge->bindValue(':emb2', $_POST['emblema']);
													$adicionabadge->bindValue(':emb3', $_POST['emblema']);
													$adicionabadge->execute();
													$badge_item_id = $db->lastInsertId();
													$itemids = $itemids . ";" . $badge_item_id;
												}

												$adicionanadb = $db->prepare("INSERT INTO `catalog_items` (`page_id`, `item_ids`, `catalog_name`, `cost_credits`, `cost_points`, `points_type`, `limited_stack`) VALUES (:pageid, :furni, :nome, :moedas, :pontos, :tipopontos, :estoque);");
												$adicionanadb->bindValue(':pageid', $_POST['page_id']);
												$adicionanadb->bindValue(':furni', $itemids);
												$adicionanadb->bindValue(':nome', $_POST["catalog_name"]);
												$adicionanadb->bindValue(':moedas', $_POST['credits']);
												$adicionanadb->bindValue(':pontos', $_POST['points']);
												$adicionanadb->bindValue(':tipopontos', $_POST['points_type']);
												$adicionanadb->bindValue(':estoque', $_POST['estoque']);
												if ($adicionanadb->execute()) {
													PHBLogger::AddLog($lingua['raros-adicionar-log']);
													echo '<div class="alert alert-solid-success" role="alert">
													<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span></button>
													<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['raros-adicionar-sucesso'] . '</div>';

													if (isset($_POST['atualizarcatalogo'])) {
														PHBSocket::SendMessage(json_encode(['key' => 'updatecatalog', 'data' => ['' => '']]));
													}
												} else {
													echo '<div class="alert alert-solid-danger" role="alert">
													<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span></button>
													<strong>' . $lingua['generico-erro'] . '</strong>' . $lingua['raros-adicionar-erro3'] . '</div>';
												}
											}
										} else {
											echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['raros-adicionar-erro4'] . '</div>';
										}
									}
									?>
									<form method="post" enctype="multipart/form-data">
										<div class="row row-sm">

											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-nome']; ?></p>
													<input type="text" class="form-control" name="catalog_name" placeholder="<?= $lingua['raros-adicionar-nome-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-moedas']; ?></p>
													<input type="number" class="form-control" name="credits" placeholder="<?= $lingua['raros-adicionar-moedas-placeholder']; ?>" value="0">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-pontos']; ?></p>
													<input type="number" class="form-control" name="points" placeholder="<?= $lingua['raros-adicionar-pontos-placeholder']; ?>" value="0">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-tipoponto']; ?></p>
													<select class="form-control" name="points_type">
														<option value="0"><?= $config['NomeCurrency.0']; ?></option>
														<option value="5"><?= $config['NomeCurrency.5']; ?></option>
														<option value="<?= $config['SeasonalID']; ?>"><?= $config['NomeCurrency.' . $config['SeasonalID']]; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-furnitureid']; ?></p>
													<input type="number" class="form-control" name="item_id" placeholder="<?= $lingua['raros-adicionar-furnitureid-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-pagina']; ?></p>
													<select class="form-control" name="page_id">
														<?php
														$getCatalogPages = $db->prepare("SELECT id,caption FROM catalog_pages WHERE page_layout != \"info_duckets\" and page_layout != \"marketplace_own_items\" 
													and page_layout != \"marketplace\" and page_layout != \"badge_display\" and page_layout != \"bots\"  and page_layout != \"soundmachine\" and page_layout != \"trophies\" and page_layout != \"roomads\" and page_layout != \"single_bundle\" and page_layout != \"recycler\"  and page_layout != \"pets3\" and page_layout != \"pets\" and enabled = \"1\" and visible = \"1\"  ORDER BY id");
														$getCatalogPages->execute();
														while ($page = $getCatalogPages->fetch()) {
														?>
															<option value="<?= $page['id'] ?>"><?= $page['caption']; ?> - <?= $page['id'] ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-estoque']; ?></p>
													<input type="number" class="form-control" name="estoque" placeholder="<?= $lingua['raros-adicionar-estoque-placeholder']; ?>" value="10">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-emblema']; ?></p>
													<input type="text" class="form-control" name="emblema" placeholder="<?= $lingua['raros-adicionar-emblema-placeholder']; ?>">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['raros-adicionar-atualizar']; ?></p>
													<label class="custom-switch">
														<input type="checkbox" name="atualizarcatalogo" class="custom-switch-input">
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="add-raro" style="float: right;margin-top: 15px;"><?= $lingua['raros-adicionar-add']; ?></button>
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