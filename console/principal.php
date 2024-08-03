<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['principal-titulo']; ?></title>
	<meta name="author" content="DARK">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['principal-titulo']; ?></title>
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
</head>

<body class="main-body leftmenu <?= $config['TemaHK']; ?>-theme">
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/loader.php'; ?>
	<!-- Page -->
	<div class="page">
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/side-menu.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/main-header.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/includes/mobile-header.php'; ?>
		<!-- Main Content-->
		<?php
		$menor_timestamp_hoje = strtotime(date('Y-m-d 00:00:00'));
		$maior_timestamp_hoje = strtotime(date('Y-m-d 23:59:00'));
		$menor_timestamp_ontem = strtotime(date('Y-m-d 00:00:00')) - 86400;
		$maior_timestamp_ontem = strtotime(date('Y-m-d 23:59:00')) - 86400;
		$maxOnlinesHoje = 0;
		$maxOnlinesOntem = 0;
		$maxEventosHoje = 0;
		$maxEventosOntem = 0;

		/// Obtém o máximo de onlines de hoje.
		$obtemOnlinesHoje = $db->prepare("SELECT onlines FROM graficos_phbplugin WHERE TIMESTAMP >= :menor AND TIMESTAMP <= :maior ORDER BY onlines DESC LIMIT 1");
		$obtemOnlinesHoje->bindParam(":maior", $maior_timestamp_hoje);
		$obtemOnlinesHoje->bindParam(":menor", $menor_timestamp_hoje);
		$obtemOnlinesHoje->execute();
		if ($fetch = $obtemOnlinesHoje->fetch())
			$maxOnlinesHoje = $fetch['onlines'];
		else
			$maxOnlinesHoje = 0;

		/// Obtém o máximo de onlines de ontem.
		$obtemOnlinesOntem = $db->prepare("SELECT onlines FROM graficos_phbplugin WHERE TIMESTAMP >= :menor AND TIMESTAMP <= :maior ORDER BY onlines DESC LIMIT 1");
		$obtemOnlinesOntem->bindParam(":maior", $maior_timestamp_ontem);
		$obtemOnlinesOntem->bindParam(":menor", $menor_timestamp_ontem);
		$obtemOnlinesOntem->execute();

		if ($fetch = $obtemOnlinesOntem->fetch())
			$maxOnlinesOntem = $fetch['onlines'];
		else
			$maxOnlinesOntem = 0;

		/// Obtém o máximo de eventos de ontem.
		$obtemEventosOntem = $db->prepare("SELECT phbcommandlogs.timestamp FROM phbcommandlogs,commandlogs WHERE commandlogs.timestamp = phbcommandlogs.timestamp AND commandlogs.user_id = phbcommandlogs.user_id AND commandlogs.command = :command AND commandlogs.succes = 'yes' AND DATE_FORMAT(FROM_UNIXTIME(phbcommandlogs.timestamp), '%Y-%m-%d') = :data");
		$obtemEventosOntem->bindValue(":data", date("Y-m-d", strtotime('- 1 day')));
		$obtemEventosOntem->bindParam(":command", $config['ClasseComandoEventAlert']);
		$obtemEventosOntem->execute();
		$maxEventosOntem = $obtemEventosOntem->rowCount();

		/// Obtém o máximo de eventos de hoje.
		$obtemEventosHoje = $db->prepare("SELECT phbcommandlogs.timestamp FROM phbcommandlogs,commandlogs WHERE commandlogs.timestamp = phbcommandlogs.timestamp AND commandlogs.user_id = phbcommandlogs.user_id AND commandlogs.command = :command AND commandlogs.succes = 'yes' AND DATE_FORMAT(FROM_UNIXTIME(phbcommandlogs.timestamp), '%Y-%m-%d') = :data");
		$obtemEventosHoje->bindValue(":data", date("Y-m-d"));
		$obtemEventosHoje->bindParam(":command", $config['ClasseComandoEventAlert']);
		$obtemEventosHoje->execute();
		$maxEventosHoje = $obtemEventosHoje->rowCount();

		?>
		<div class="main-content side-content pt-0">
			<div class="container-fluid">
				<div class="inner-body">
					<!--Row-->
					<div class="row row-sm">
						<div class="col-sm-12 col-lg-12 col-xl-8 mt-xl-4">
							<!--Row-->
							<div class="row row-sm">
								<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
									<div class="card custom-card">
										<div class="card-body">
											<div class="card-item">
												<div class="card-item-icon card-icon">
													<i class="fas fa-users" style="color: #9b92ff;"></i>
												</div>
												<div class="card-item-title mb-2">
													<label class="main-content-label tx-13 font-weight-bold mb-1"><?= $lingua['principal-pico-usuarios']; ?></label>
													<span class="d-block tx-12 mb-0 text-muted"><?= $lingua['generico-hoje-vs-ontem']; ?></span>
												</div>
												<div class="card-item-body">
													<div class="card-item-stat">
														<?php
														if ($maxOnlinesOntem > $maxOnlinesHoje) {
															$porcentagem = $lingua['principal-pico-usuarios-queda'] . " <b class=\"text-danger\">";
															$time = @floor(((($maxOnlinesOntem - $maxOnlinesHoje) * 100) / $maxOnlinesOntem));
															if ($time > 0)
																$porcentagem .= $time;
															else
																$porcentagem .= '0';
															$porcentagem .= "%</b>";
														} else {
															$porcentagem = $lingua['principal-pico-usuarios-aumento'] . " <b class=\"text-success\">";
															if($maxOnlinesOntem>0)
															$time = @floor((($maxOnlinesHoje * 100) / $maxOnlinesOntem) - 100);
															else $time = 0;
															if ($time > 0)
																$porcentagem .= $time;
															else
																$porcentagem .= '0';
															$porcentagem .= "%</b>";
														}
														
														?>
														<h4 class="font-weight-bold"><?= $maxOnlinesHoje; ?></h4>
														<small><?= $porcentagem; ?> - <?= $lingua['generico-ontem']; ?>: <?= $maxOnlinesOntem; ?></small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
									<div class="card custom-card">
										<div class="card-body">
											<div class="card-item">
												<div class="card-item-icon card-icon">
													<i class="fa fa-bed" style="color: #9b92ff;"></i>
												</div>
												<div class="card-item-title mb-2">
													<label class="main-content-label tx-13 font-weight-bold mb-1"><?= $lingua['principal-usuarios-online']; ?></label>
													<span class="d-block tx-12 mb-0 text-muted"><?= $lingua['principal-usuarios-online-tempo-real']; ?></span>
												</div>
												<div class="card-item-body">
													<div class="card-item-stat">
														<h4 class="font-weight-bold"><?= PHBTools::UsuariosOnlines(); ?></h4>
														<small><?= str_replace("%quartos%", PHBTools::QuartosAtivos(), $lingua['principal-usuarios-online-quartos']) ?></small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
									<div class="card custom-card">
										<div class="card-body">
											<div class="card-item">
												<div class="card-item-icon card-icon">
													<i class="fa fa-smile-o" style="color: #9b92ff;"></i>
												</div>
												<div class="card-item-title  mb-2">
													<label class="main-content-label tx-13 font-weight-bold mb-1"><?= $lingua['principal-eventos-realizados']; ?></label>
													<span class="d-block tx-12 mb-0 text-muted"><?= $lingua['generico-hoje-vs-ontem']; ?></span>
												</div>
												<div class="card-item-body">
													<div class="card-item-stat">
														<?php
														if ($maxEventosOntem > $maxEventosHoje) {
															$porcentagem = $lingua['principal-eventos-queda'] . " <b class=\"text-danger\">";
															$time = @floor(((($maxEventosOntem - $maxEventosHoje) * 100) / $maxEventosOntem));
															if ($time > 0)
																$porcentagem .= $time;
															else
																$porcentagem .= '0';
															$porcentagem .= "%</b>";
														} else {
															$porcentagem = $lingua['principal-eventos-aumento'] . " <b class=\"text-success\">";
															if($maxOnlinesOntem>0)
															$time = @floor((($maxEventosHoje * 100) / $maxEventosOntem) - 100);
															else $time = 0;
															if ($time > 0)
																$porcentagem .= $time;
															else
																$porcentagem .= '0';
															$porcentagem .= "%</b>";
														}
														?>
														<h4 class="font-weight-bold"><?= $maxEventosHoje; ?></h4>
														<small><?= $porcentagem; ?> - <?= $lingua['generico-ontem']; ?>: <?= $maxEventosOntem; ?></small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--End row-->
							<!--row-->
							<div class="row row-sm">
								<div class="col-sm-12 col-lg-12 col-xl-12">
									<div class="card custom-card overflow-hidden">
										<div class="card-header border-bottom-0">
											<div>
												<label class="main-content-label mb-2"><?= $lingua['principal-grafico-de-onlines']; ?></label><span class="d-block tx-12 mb-0 text-muted"><?= $lingua['principal-grafico-de-onlines-desc']; ?></span>
											</div>
										</div>
										<div class="card-body pl-0">
											<div class>
												<div class="container">
													<canvas id="chartLine" class="chart-dropshadow2 ht-250"></canvas>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- -->
								<div class="col-lg-12">
									<div class="card custom-card mg-b-20">
										<div class="card-body">
											<div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
												<div>
													<label class="main-content-label mb-2"><?= $lingua['principal-banimentos']; ?></label> <span class="d-block tx-12 mb-3 text-muted"><?= $lingua['principal-banimentos-desc']; ?></span>
												</div>
											</div>
											<div class="table-responsive tasks">
												<table class="table card-table table-vcenter text-nowrap mb-0  border">
													<thead>
														<tr>
															<th class="wd-lg-10p"><?= $lingua['principal-banimentos-tipo']; ?></th>
															<th class="wd-lg-20p"><?= $lingua['principal-banimentos-data']; ?></th>
															<th class="wd-lg-20p"><?= $lingua['principal-banimentos-expiracao']; ?></th>
															<th class="wd-lg-20p"><?= $lingua['principal-banimentos-razao']; ?></th>
															<th class="wd-lg-20p"><?= $lingua['principal-banimentos-staff']; ?></th>
															<th class="wd-lg-20p"><?= $lingua['principal-banimentos-valor']; ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$getBans = $db->prepare("SELECT bans.timestamp, bans.ban_expire, bans.ip, bans.machine_id, bans.ban_reason, bans.type, users1.username AS banido, users2.username AS staff FROM bans, users users1, users users2 WHERE users1.id = bans.user_id AND users2.id = bans.user_staff_id GROUP BY bans.user_id ORDER BY bans.id DESC LIMIT 10;");
														$getBans->execute();
														while ($bans = $getBans->fetch()) {

															switch ($bans['type']) {
																case "ip":
																	$tipo = $lingua['principal-banimentos-tipo-ip'];
																	$valor = $bans['ip'];
																	break;
																case "account":
																	$tipo = $lingua['principal-banimentos-tipo-conta'];
																	$valor = $bans['banido'];
																	break;
																case "machine":
																	$tipo = $lingua['principal-banimentos-tipo-maquina'];
																	$valor = $bans['machine_id'];
																	break;
																case "super":
																	$tipo = $lingua['principal-banimentos-tipo-super'];
																	$valor = $bans['banido'];
																	break;
																default:
																	$tipo = "?";
																	$valor = $bans['banido'];
																	break;
															}

														?>
															<tr>
																<td><span class="badge badge-pill badge-primary-light"><?= $tipo; ?></span></td>
																<td class="text-primary"><?= date("d/m/y H:i:s", $bans['timestamp']); ?></td>
																<td class="text-primary"><?= date("d/m/y H:i:s", $bans['ban_expire']); ?></td>
																<td class="text-primary"><?= $bans['ban_reason']; ?></td>
																<td class="text-primary"><?= $bans['staff']; ?></td>
																<td class="text-primary"><?= $valor; ?></td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div><!-- col end -->
							</div><!-- Row end -->
						</div><!-- col end -->
						<div class="col-sm-12 col-lg-12 col-xl-4 mt-xl-4">
							<div class="card custom-card card-dashboard-calendar pb-0">
								<label class="main-content-label mb-2 pt-1"><?= $lingua['principal-conversas']; ?></label>
								<span class="d-block tx-12 mb-2 text-muted"><?= $lingua['principal-conversas-desc']; ?></span>
								<table class="table table-hover m-b-0 transcations mt-2">
									<tbody>
										<?php
										$pegarChatlog = $db->prepare("SELECT chatlogs_room.message, chatlogs_room.timestamp, chatlogs_room.room_id, users.username, users.look FROM chatlogs_room, users WHERE chatlogs_room.message != '' AND users.id = chatlogs_room.user_from_id ORDER BY chatlogs_room.timestamp DESC LIMIT 16");
										$pegarChatlog->execute();
										while ($msg = $pegarChatlog->fetch()) {
										?>
											<tr>
												<td class="wd-5p">
													<div class="main-img-user avatar-md">
														<img style="margin-top: -16px;height: 179%;" alt="avatar" class="rounded-circle mr-3" src="<?= $config['AvatarImageURL']; ?>?figure=<?= $msg['look']; ?>&action=std&gesture=std&direction=2&head_direction=2&size=&headonly=1&img_format=png">
													</div>
												</td>
												<td>
													<div class="d-flex align-middle ml-3">
														<div class="d-inline-block">
															<h6 class="mb-1"><?= $msg['username']; ?></h6>
															<p class="mb-0 tx-13 text-muted"><?= mb_strimwidth((strip_tags($msg['message'])), 0, 25, "..."); ?></p>
														</div>
													</div>
												</td>
												<td class="text-right">
													<div class="d-inline-block">
														<h6 class="mb-2 tx-15 font-weight-semibold"><a target="_blank" href="<?= str_replace("%id%", $msg['room_id'], $config['HotelRoomURL']); ?>"><?= $lingua['principal-conversas-iraoquarto']; ?></a></h6>
														<p class="mb-0 tx-11 text-muted"><?= date("d/m/y \á\s H:i:s", $msg['timestamp']); ?></p>
													</div>
												</td>
											</tr>
										<?php } ?>

									</tbody>
								</table>
							</div>
						</div><!-- col end -->
					</div><!-- Row end -->
				</div>
			</div>
		</div>
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
	<!-- Circle Progress js-->
	<script src="/assets/js/circle-progress.min.js"></script>
	<script src="/assets/js/chart-circle.js"></script>
	<!-- Internal Dashboard js-->
	<script src="/assets/js/index.js"></script>
</body>

</html>