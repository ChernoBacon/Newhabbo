<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_catalogo_paginas_editar")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-editarpagina-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-editarpagina-titulo']; ?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-editarpagina-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-editarpagina-texto2']; ?></p>
									</div>
									<?php
									if (isset($_POST['salvar']) && PHBPermissoes::Check("gerenciamento_catalogo_item_editar")) {
										$update = $db->prepare("UPDATE catalog_pages SET parent_id = :parent_id, caption_save = :caption_save, caption = :caption, page_layout = :page_layout, icon_image = :icon_image, min_rank = :min_rank,  order_num = :order_num, visible = :visible, enabled = :enabled, club_only = :club_only, vip_only = :vip_only, page_headline = :page_headline, page_teaser = :page_teaser, room_id = :room_id, page_special = :page_special, page_text_details = :page_text_details, page_text_teaser = :page_text_teaser, includes = :includes, page_text1 = :page_text1, page_text2 = :page_text2 WHERE id = :id;");
										$update->bindValue(":parent_id", $_POST['parent_id']);
										$update->bindValue(":caption_save", $_POST['caption_save']);
										$update->bindValue(":caption", $_POST['caption']);
										$update->bindValue(":page_layout", $_POST['page_layout']);
										$update->bindValue(":icon_image", $_POST['icon_image']);
										$update->bindValue(":min_rank", $_POST['min_rank']);
										$update->bindValue(":order_num", $_POST['order_num']);
										$update->bindValue(":visible", $_POST['visible']);
										$update->bindValue(":enabled", $_POST['enabled']);
										$update->bindValue(":club_only", $_POST['club_only']);
										$update->bindValue(":vip_only", $_POST['vip_only']);
										$update->bindValue(":page_headline", $_POST['page_headline']);
										$update->bindValue(":page_teaser", $_POST['page_teaser']);
										$update->bindValue(":room_id", $_POST['room_id']);
										$update->bindValue(":page_special", $_POST['page_special']);
										$update->bindValue(":page_text_details", $_POST['page_text_details']);
										$update->bindValue(":page_text_teaser", $_POST['page_text_teaser']);
										$update->bindValue(":includes", $_POST['includes']);
										$update->bindValue(":page_text1", $_POST['page_text1']);
										$update->bindValue(":page_text2", $_POST['page_text2']);
										$update->bindValue(":id", $_GET['id']);
										if (!$update->execute()) {
											echo '<div class="alert alert-solid-danger" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-erro'] . '</strong> ' . $update->errorInfo()[2] . '.</div>';
										} else {
											PHBLogger::AddLog($lingua['gerenciamento-editarpagina-log'] . " \"" . $_POST['caption'] . "\" (" . $_GET['id'] . ").");
											echo '<div class="alert alert-solid-success" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['gerenciamento-editarpagina-sucesso'] . '</div>';
										}
									}

									$obtemItems = $db->prepare("SELECT * FROM catalog_pages WHERE id = :id;");
									$obtemItems->bindValue(":id", $_GET['id']);
									$obtemItems->execute();
									$item = $obtemItems->fetch();

									?>
									<form method="post" enctype="multipart/form-data">
										<div class="row row-sm">

											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-paginapai']; ?></p>
													<select class="form-control" name="parent_id">
														<option value="-1" <?php if ($item['parent_id'] == "-1") echo "selected"; ?>><?= $lingua['gerenciamento-editarpagina-paginapai-aba']; ?></option>
														<?php
														$getCatalogPages = $db->prepare("SELECT id,caption FROM catalog_pages ORDER BY id");
														$getCatalogPages->execute();
														while ($page = $getCatalogPages->fetch()) {
														?>
															<option value="<?= $page['id'] ?>" <?php if ($page['id'] == $item['parent_id']) echo "selected"; ?>><?= $page['caption']; ?> | <?= $page['id'] ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-captionsave']; ?></p>
													<input type="text" class="form-control" name="caption_save" value="<?= $item['caption_save'] ?>" required>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-nome']; ?></p>
													<input type="text" class="form-control" name="caption" value="<?= $item['caption'] ?>" required>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-layout']; ?></p>
													<select class="form-control" name="page_layout">
														<?php
														$pageLayouts = str_replace("'", "", $config['CatalogPageLayouts']);
														$pageLayouts = explode(",", $pageLayouts);
														foreach ($pageLayouts as $layout) { ?>
															<option value="<?= $layout; ?>" <?php if ($item['page_layout'] ==  $layout) echo "selected"; ?>><?= $layout; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-icone']; ?></p>
													<input type="number" class="form-control" name="icon_image" value="<?= $item['icon_image'] ?>" required>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-rank']; ?></p>
													<select class="form-control" name="min_rank">
														<?php
														$getRanks = $db->prepare("SELECT id, rank_name FROM permissions ORDER BY id");
														$getRanks->execute();
														while ($rank = $getRanks->fetch()) {
														?>
															<option value="<?= $rank['id'] ?>" <?php if ($rank['id'] == $item['min_rank']) echo "selected"; ?>><?= $rank['id'] ?> - <?= $rank['rank_name'] ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-ordem']; ?></p>
													<input type="number" class="form-control" name="order_num" value="<?= $item['order_num'] ?>" required>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-visivel']; ?></p>
													<select class="form-control" name="visible">
														<option value="1" <?php if ($item['visible'] == "1") echo "selected"; ?>><?= $lingua['generico-sim']; ?></option>
														<option value="0" <?php if ($item['visible'] == "0") echo "selected"; ?>><?= $lingua['generico-nao']; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-ativa']; ?></p>
													<select class="form-control" name="enabled">
														<option value="1" <?php if ($item['enabled'] == "1") echo "selected"; ?>><?= $lingua['generico-sim']; ?></option>
														<option value="0" <?php if ($item['enabled'] == "0") echo "selected"; ?>><?= $lingua['generico-nao']; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-apenasclub']; ?></p>
													<select class="form-control" name="club_only">
														<option value="1" <?php if ($item['club_only'] == "1") echo "selected"; ?>><?= $lingua['generico-sim']; ?></option>
														<option value="0" <?php if ($item['club_only'] == "0") echo "selected"; ?>><?= $lingua['generico-nao']; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-apenasvip']; ?></p>
													<select class="form-control" name="vip_only">
														<option value="1" <?php if ($item['vip_only'] == "1") echo "selected"; ?>><?= $lingua['generico-sim']; ?></option>
														<option value="0" <?php if ($item['vip_only'] == "0") echo "selected"; ?>><?= $lingua['generico-nao']; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-headline']; ?></p>
													<input type="text" class="form-control" name="page_headline" value="<?= $item['page_headline'] ?>">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-teaser']; ?></p>
													<input type="text" class="form-control" name="page_teaser" value="<?= $item['page_teaser'] ?>">
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-idquarto']; ?></p>
													<input type="number" class="form-control" name="room_id" value="<?= $item['room_id'] ?>">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-special']; ?></p>
													<input type="text" class="form-control" name="page_special" value="<?= $item['page_special'] ?>">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-detalhestexto']; ?></p>
													<input type="text" class="form-control" name="page_text_details" value="<?= $item['page_text_details'] ?>">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-teasertexto']; ?></p>
													<input type="text" class="form-control" name="page_text_teaser" value="<?= $item['page_text_teaser'] ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-includes']; ?></p>
													<input type="text" class="form-control" name="includes" value="<?= $item['includes'] ?>">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-texto12']; ?></p>
													<input type="text" class="form-control" name="page_text1" value="<?= $item['page_text1'] ?>">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-editarpagina-texto22']; ?></p>
													<input type="text" class="form-control" name="page_text2" value="<?= $item['page_text2'] ?>">
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="salvar" style="float: right;margin-top: 15px;"><?= $lingua['gerenciamento-editarpagina-botao']; ?></button>
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