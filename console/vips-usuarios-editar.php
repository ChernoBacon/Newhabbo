<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("vips_usuarios_editar")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['vips-usuarios-editar-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['vips-usuarios-editar-titulo']; ?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['vips-usuarios-editar-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['vips-usuarios-editar-texto2']; ?></p>
									</div>
									<?php

									if (isset($_POST['add-vip']) && PHBPermissoes::Check("vips_usuarios_editar") && isset($_POST['expira']) && $_POST['expira'] != "") {

										$expira = date_format(date_create_from_format('d/m/Y H:i:s', $_POST['expira']), 'U');

										$attDb = $db->prepare("UPDATE `phb_hk_vips` SET `expira_time`= :expira, `observacoes`= :ob, `desativar_automaticamente`= :desativa WHERE user_id =:id;");
										$attDb->bindValue(":expira", $expira);
										$attDb->bindValue(":ob", $_POST['observacoes']);
										$attDb->bindValue(":desativa", $_POST['desativar']);
										$attDb->bindValue(":id", $_GET['id']);
										$attDb->execute();

										PHBLogger::AddLog($lingua['vips-usuarios-editar-log'] . " " . PHBTools::GetUsernameById($_GET['id']) . " (" . $_GET['id'] . ")");
										PHBLogger::AddVipLog(str_replace("%usuario%", PHBTools::GetUsernameById($_GET['id']) . " (" . $_GET['id'] . ")", str_replace("%staff%", PHBTools::GetUsernameById($_SESSION['id_painel']), $lingua['vips-usuarios-editar-viplog'])), $_GET['id']);

										echo '<div class="alert alert-solid-success" role="alert">
														<button aria-label="Close" class="close" data-dismiss="alert" type="button">
														<span aria-hidden="true">&times;</span></button>
														<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['vips-usuarios-editar-sucesso'] . '</div>';
									}

									$getVip = $db->prepare("SELECT phb_hk_vips.*, users.username, users.rank FROM phb_hk_vips, users WHERE users.id = phb_hk_vips.user_id AND phb_hk_vips.user_id = :id");
									$getVip->bindValue(":id", $_GET['id']);
									$getVip->execute();
									$vip = $getVip->fetch();

									?>
									<form method="post">
										<div class="row row-sm">

											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-usuario']; ?></p>
													<input type="text" class="form-control" placeholder="<?= $lingua['vips-usuarios-usuario-placeholder']; ?>" disabled value="<?= $vip['username']; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-expiracao']; ?></p>
													<input type="text" class="form-control" name="expira" placeholder="<?= $lingua['vips-usuarios-expiracao-placeholder']; ?>" id="datetimepicker" value="<?= date('d/m/Y H:i:s', $vip['expira_time']); ?>">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-rank']; ?></p>
													<select class="form-control" disabled>
														<?php
														$pegaRank = $db->prepare("SELECT id, rank_name FROM permissions WHERE id IN (" . $config['RanksVIP'] . ")");
														$pegaRank->execute();
														while ($rank = $pegaRank->fetch()) {
														?>
															<option value="<?= $rank['id']; ?>" <?php if ($vip['rank'] == $rank['id']) echo "selected"; ?>><?= $rank['id']; ?> - <?= $rank['rank_name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-desativar']; ?></p>
													<select class="form-control" name="desativar">
														<option value="1" <?php if ($vip['desativar_automaticamente'] == '1') echo "selected"; ?>><?= $lingua['generico-sim']; ?></option>
														<option value="0" <?php if ($vip['desativar_automaticamente'] == '0') echo "selected"; ?>><?= $lingua['generico-nao']; ?></option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-emblema']; ?></p>
													<input type="text" class="form-control" placeholder="<?= $lingua['vips-usuarios-emblema-placeholder']; ?>" disabled value="<?= $vip['emblema']; ?>">
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['vips-usuarios-observacoes']; ?></p>
													<input type="text" class="form-control" name="observacoes" placeholder="<?= $lingua['vips-usuarios-observacoes-placeholder']; ?>" value="<?= $vip['observacoes']; ?>">
												</div>
											</div>
										</div>
										<button type="submit" class="btn ripple btn-primary" name="add-vip" style="float: right;margin-top: 15px;"><?= $lingua['vips-usuarios-attvip']; ?></button>
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
		$(function() {
			$('#datetimepicker').datetimepicker({
				format: 'dd/mm/yyyy hh:ii:ss',
				autoclose: true
			});
		});
	</script>
</body>

</html>