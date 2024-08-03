<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("mod_filtro")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['mod-filtro-titulo'];?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['mod-filtro-titulo'];?></title>
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
					<?php if (PHBPermissoes::Check("mod_filtro_adicionar")) { ?>
						<!-- Row -->
						<div class="row row-sm mt-xl-4">
							<div class="col-lg-12 col-md-12">
								<div class="card custom-card">
									<div class="card-body">
										<div>
											<h6 class="main-content-label mb-1"><?= $lingua['mod-filtro-texto1'];?></h6>
											<p class="text-muted card-sub-title"><?= $lingua['mod-filtro-texto2'];?></p>
										</div>
										<?php
										if (isset($_POST['proibir']) && PHBPermissoes::Check("mod_filtro_adicionar")) {
											if (isset($_POST['key']) && isset($_POST['replacement']) && isset($_POST['mute']) && isset($_POST['report']) && $_POST['key'] != ""  && $_POST['key'] != ""  && $_POST['mute'] != ""  && $_POST['report'] != "") {

												$addTexts = $db->prepare("INSERT INTO `wordfilter` (`key`, `replacement`, `report`, `mute`) VALUES (:key, :replace, :report, :mute);");
												$addTexts->bindValue(':key', $_POST['key']);
												$addTexts->bindValue(':replace', $_POST['replacement']);
												$addTexts->bindValue(':report', $_POST['report']);
												$addTexts->bindValue(':mute', $_POST['mute']);
												$addTexts->execute();
												PHBLogger::AddLog($lingua['mod-filtro-log']." \"" . $_POST['key'] . "\"");
												echo '<div class="alert alert-solid-success" role="alert">
														<button aria-label="Close" class="close" data-dismiss="alert" type="button">
														<span aria-hidden="true">&times;</span></button>
														<strong>'.$lingua['generico-sucesso'].'</strong> '.$lingua['mod-filtro-palavra-adicionada'] .'</div>';
												PHBSocket::SendMessage(json_encode(['key' => 'updatewordfilter', 'data' => ['' => '']]));
											} else {
												echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>'.$lingua['generico-erro'].'</strong> '.$lingua['mod-filtro-erro-preencha'].'</div>';
											}
										}
										?>
										<form method="post">
											<div class="row row-sm">

												<div class="col-md-3">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['mod-filtro-palavra'];?></p>
														<input type="text" class="form-control" name="key" placeholder="<?= $lingua['mod-filtro-palavra-placeholder'];?>">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['mod-filtro-substituicao'];?></p>
														<input type="text" class="form-control" name="replacement" placeholder="<?= $lingua['mod-filtro-substituicao-placeholder'];?>">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['mod-filtro-muta'];?></p>
														<input type="number" class="form-control" name="mute" placeholder="<?= $lingua['mod-filtro-muta-placeholder'];?>">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['mod-filtro-reporta'];?></p>
														<select class="form-control" name="report">
															<option value="1"><?= $lingua['generico-sim'];?></option>
															<option value="0"><?= $lingua['generico-nao'];?></option>
														</select>
													</div>
												</div>
											</div>
											<button type="submit" class="btn ripple btn-primary" name="proibir" style="float: right;margin-top: 15px;"><?= $lingua['mod-filtro-salvar'];?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->
					<?php } ?>
					<!-- Row -->
					<div class="row row-sm">
						<div class="col-lg-12 mt-xl-4">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div>
										<h6 class="main-content-label mb-1"><?= $lingua['mod-filtro-texto3'];?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['mod-filtro-texto4'];?></p>
									</div>
									<div class="table-responsive">
										<?php
										if (isset($_POST['palavra']) && PHBPermissoes::Check("mod_filtro_apagar")) {
											$apagaTexto = $db->prepare("DELETE FROM `wordfilter` WHERE `key`= :key");
											$apagaTexto->bindValue(':key', $_POST['palavra']);
											$apagaTexto->execute();
											PHBLogger::AddLog($lingua['mod-filtro-apagar-log']." \"" . $_POST['palavra'] . "\" do filtro.");
											PHBSocket::SendMessage(json_encode(['key' => 'updatewordfilter', 'data' => ['' => '']]));
											echo '<div class="alert alert-solid-success" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>'.$lingua['generico-sucesso'].'</strong> '.$lingua['mod-filtro-apagada'].'</div>';
										}
										?>
										<table class="table" id="example1">
											<thead>
												<tr>
													<th class="wd-25p"><?= $lingua['mod-filtro-palavra'];?></th>
													<th class="wd-25p"><?= $lingua['mod-filtro-substituicao'];?></th>
													<th class="wd-15p"><?= $lingua['mod-filtro-muta'];?></th>
													<th class="wd-20p"><?= $lingua['mod-filtro-reporta'];?></th>
													<?php if (PHBPermissoes::Check("mod_filtro_apagar")) { ?><th class="wd-20p"></th><?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php
												$obtemLogs = $db->prepare("SELECT * FROM wordfilter;");
												$obtemLogs->execute();
												while ($log = $obtemLogs->fetch()) {
													if ($log['report'] == "0")
														$log['report'] = $lingua['generico-nao'];
													else
														$log['report'] = $lingua['generico-sim'];

													if ($log['mute'] == "0")
														$log['mute'] = $lingua['generico-nao'];
													else
														$log['mute'] = $log['mute'] . " ".$lingua['generico-segundos'];
												?>
													<tr>
														<td class="text-primary"><?= $log['key']; ?></td>
														<td class="text-primary"><?= $log['replacement']; ?></td>
														<td class="text-primary"><?= $log['mute']; ?></td>
														<td class="text-primary"><?= $log['report']; ?></td>
														<?php if (PHBPermissoes::Check("mod_filtro_apagar")) { ?><td class="text-primary">
																<form method="post"><input type="hidden" name="palavra" value="<?= $log['key']; ?>"><input type="image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAZUlEQVQ4jWP0is/8zzCkAeMw9cK2hdMZqWbDfwaG//8bGzEs+d/Y+P8/AwPh8IMpRDYEWQxnLCB7439j43+G+noGhsZGiACUzVhfT7xX4baiuYaJaBNwANp7gVAgEjaAQDRSnJAALgpiF4bpNiMAAAAASUVORK5CYII=" onclick="return confirm('<?= $lingua['generico-excluir-confirmacao'];?>')"></form>
															</td>
														<?php } ?>
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