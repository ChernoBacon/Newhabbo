<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("logs_trocas")) {
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
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['logs-trocas-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['logs-trocas-titulo']; ?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['logs-trocas-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['logs-trocas-texto2']; ?></p>
									</div>
									<div class="table-responsive">
										<table class="table" id="example1">
											<thead>
												<tr>
													<th>ID</th>
													<th><?= $lingua['logs-trocas-usuario1']; ?></th>
													<th><?= $lingua['logs-trocas-usuario1-deu']; ?></th>
													<th><?= $lingua['logs-trocas-usuario2']; ?></th>
													<th><?= $lingua['logs-trocas-usuario2-deu']; ?></th>
													<th><?= $lingua['logs-trocas-data']; ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$getLogs = $db->prepare("SELECT room_trade_log.id, room_trade_log.timestamp, room_trade_log.user_one_id, u1.username AS usuario1, room_trade_log.user_two_id, u2.username AS usuario2 FROM room_trade_log, users u1, users u2 WHERE u1.id = room_trade_log.user_one_id AND u2.id = room_trade_log.user_two_id ORDER BY room_trade_log.id DESC LIMIT 1000");
												$getLogs->execute();
												while ($log = $getLogs->fetch()) {
													echo '<tr>
													<td>' . $log["id"] . '</td>
													<td>' . $log["usuario1"] . '</td><td>';
													$getItems1 = $db->prepare("SELECT * FROM room_trade_log_items WHERE id = :id and user_id = :user");
													$getItems1->bindValue(":id", $log["id"]);
													$getItems1->bindValue(":user", $log["user_one_id"]);
													$getItems1->execute();
													while ($items1 = $getItems1->fetch()) {
														$getitemdetail1 = $db->prepare("SELECT item_id, limited_data FROM items WHERE id = :id");
														$getitemdetail1->bindValue(":id", $items1['item_id']);
														$getitemdetail1->execute();
														if ($getitemdetail1->rowCount() >= 1) {
															$itemdetail1 = $getitemdetail1->fetch();
															$getItemName = $db->prepare("SELECT item_name, id FROM items_base WHERE id = :id");
															$getItemName->bindValue(":id", $itemdetail1['item_id']);
															$getItemName->execute();
															$itemname = $getItemName->fetch();
															if ($itemdetail1['limited_data'] != "0:0")
																echo $itemname['item_name'] . " (Lote: " . explode(":", $itemdetail1['limited_data'])[1] . " de " . explode(":", $itemdetail1['limited_data'])[0] . " / Item: " . $items1['item_id'] . " / Base Item: ".$itemname['id'].")<br>";
															else
																echo $itemname['item_name'] . " (" . $items1['item_id'] . ")<br>";
														} else {
															$getReedemItemName = $db->prepare("SELECT items_base.item_name, items_base.id FROM phbplugin_redeem_logs,items_base WHERE phbplugin_redeem_logs.item_id = 155666 AND phbplugin_redeem_logs.base_item_id = items_base.id;");
															$getReedemItemName->bindValue(":id", $items1['item_id']);
															$getReedemItemName->execute();
															if ($getReedemItemName->rowCount() == 0)
																echo $lingua['logs-trocas-naoexiste'] . " " . $items1['item_id'] . "<br>";
															else {
																$redeemItem = $getReedemItemName->fetch();
																echo $redeemItem['item_name'] . " (Gerado / Item: " . $items1['item_id'] . " / Base Item: " . $redeemItem['id'] . ")<br>";
															}
														}
													}

													echo '</td><td>' . $log["usuario2"] . '</td><td>';
													$getItems2 = $db->prepare("SELECT * FROM room_trade_log_items WHERE id = :id and user_id = :user");
													$getItems2->bindValue(":id", $log["id"]);
													$getItems2->bindValue(":user", $log["user_two_id"]);
													$getItems2->execute();
													while ($items2 = $getItems2->fetch()) {
														$getitemdetail2 = $db->prepare("SELECT item_id, limited_data FROM items WHERE id = :id");
														$getitemdetail2->bindValue(":id", $items2['item_id']);
														$getitemdetail2->execute();
														if ($getitemdetail2->rowCount() >= 1) {
															$itemdetail2 = $getitemdetail2->fetch();
															$getItemName2 = $db->prepare("SELECT item_name, id FROM items_base WHERE id = :id");
															$getItemName2->bindValue(":id", $itemdetail2['item_id']);
															$getItemName2->execute();
															$itemname2 = $getItemName2->fetch();
															if ($itemdetail2['limited_data'] != "0:0")
																echo $itemname2['item_name'] . " (Lote: " . explode(":", $itemdetail2['limited_data'])[1] . " de " . explode(":", $itemdetail2['limited_data'])[0] . " / Item: " . $items2['item_id'] . " / Base Item: ".$itemname2['id'].")<br>";
															else
																echo $itemname2['item_name'] . " (" . $items2['item_id'] . ")<br>";
														} else {

															$getReedemItemName2 = $db->prepare("SELECT items_base.item_name, items_base.id FROM phbplugin_redeem_logs,items_base WHERE phbplugin_redeem_logs.item_id = :id AND phbplugin_redeem_logs.base_item_id = items_base.id;");
															$getReedemItemName2->bindValue(":id", $items2['item_id']);
															$getReedemItemName2->execute();
															if ($getReedemItemName2->rowCount() == 0)
																echo $lingua['logs-trocas-naoexiste'] . " " . $items2['item_id'] . "<br>";
															else {
																$redeemItem2 = $getReedemItemName2->fetch();
																echo $redeemItem2['item_name'] . " (Gerado / Item: " . $items2['item_id'] . " / Base Item: " . $redeemItem2['id'] . ")<br>";
															}
														}
													}
													echo '<td>' . date('d/m/y h:i:s', $log["timestamp"]) . '</td></td>';
												}
												?>
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