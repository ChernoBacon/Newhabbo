<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("usuarios_editar") && !PHBPermissoes::Check("usuarios_informacoes")) {
	header("Location: /index");
	die();
}
if (!isset($_GET['username'])) {
	header("Location: /index");
	die();
}

/// Obtem dados do usuário
$pegaUser = $db->prepare("SELECT * FROM users WHERE username = :username");
$pegaUser->bindValue(":username", $_GET['username']);
$pegaUser->execute();
$user = $pegaUser->fetch();

if ($pegaUser->rowCount() != 1) {
	header("Location: /index");
	return;
}

$pegaUsersSettings = $db->prepare("SELECT * FROM users_settings WHERE user_id = :user_id");
$pegaUsersSettings->bindValue(":user_id", $user['id']);
$pegaUsersSettings->execute();
$userSettings = $pegaUsersSettings->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $config['NomeHotel']; ?> - <?= str_replace("%usuario%", $user['username'], $lingua['usuarios-editar-titulo']); ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= str_replace("%usuario%", $user['username'], $lingua['usuarios-editar-titulo']); ?></title>
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
		<div class="main-content side-content pt-0">
			<div class="container-fluid">
				<div class="inner-body">
					<?php if (PHBPermissoes::Check("usuarios_informacoes")) { ?>
						<!-- Row -->
						<div class="row row-sm mt-xl-4">
							<div class="col-lg-12 col-md-12">
								<div class="card custom-card">
									<div class="card-body">
										<div>
											<h6 class="main-content-label mb-1"><?= $lingua['usuarios-editar-texto1']; ?></h6>
											<p class="text-muted card-sub-title"><?= $lingua['usuarios-editar-texto2']; ?></p>
										</div>
										<?php

										$apiIpCurrent = json_decode(file_get_contents("http://ip-api.com/json/" . $user['ip_current'] . "?fields=status,message,continent,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,proxy,hosting,query"), true);

										$pegaUsersBadges = $db->prepare("SELECT id FROM users_badges WHERE user_id = :user");
										$pegaUsersBadges->bindValue(":user", $user['id']);
										$pegaUsersBadges->execute();
										$UsersBadges = $pegaUsersBadges->rowCount();

										$pegaRooms = $db->prepare("SELECT id FROM rooms WHERE owner_id = :user");
										$pegaRooms->bindValue(":user", $user['id']);
										$pegaRooms->execute();
										$rooms = $pegaRooms->rowCount();

										$pegaContasNoIP = $db->prepare("SELECT id FROM users WHERE ip_current = :ip OR ip_register = :ip");
										$pegaContasNoIP->bindValue(":ip", $user['ip_current']);
										$pegaContasNoIP->execute();
										$contasNoIP = $pegaContasNoIP->rowCount();

										$pegaGrupos = $db->prepare("SELECT id FROM guilds WHERE user_id = :id");
										$pegaGrupos->bindValue(":id", $user['id']);
										$pegaGrupos->execute();
										$grupos = $pegaGrupos->rowCount();

										$pegaRaros = $db->prepare("SELECT id FROM items WHERE limited_data != \"0:0\" AND limited_data != \"\" AND user_id = :id;");
										$pegaRaros->bindValue(":id", $user['id']);
										$pegaRaros->execute();
										$raros = $pegaRaros->rowCount();

										$pegaItems = $db->prepare("SELECT id FROM items WHERE user_id = :id;");
										$pegaItems->bindValue(":id", $user['id']);
										$pegaItems->execute();
										$items = $pegaItems->rowCount();

										?>
										<div class="row row-sm">

											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['usuarios-editar-ultimoip']; ?></p>
													<input type="text" class="form-control" value="<?= $user['ip_current']; ?>" disabled>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['usuarios-editar-ipregistro']; ?></p>
													<input type="text" class="form-control" value="<?= $user['ip_register']; ?>" disabled>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['usuarios-editar-machineid']; ?></p>
													<input type="text" class="form-control" value="<?= $user['machine_id']; ?>" disabled>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10">&nbsp;</p>
													<a href="<?= str_replace("%id%", $user['id'], $config['AcessarContaUsuarioUrl']); ?>" class="btn ripple btn-light"><?= $lingua['usuarios-editar-entrarnaconta']; ?></a>
												</div>
											</div>

											<?php if ($apiIpCurrent['status'] == "success") { ?>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-provedor']; ?></p>
														<input type="text" class="form-control" value="<?= $apiIpCurrent['org']; ?>" disabled>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-pais']; ?></p>
														<input type="text" class="form-control" value="<?= $apiIpCurrent['country']; ?> (<?= $apiIpCurrent['continent']; ?>)" disabled>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-cidade']; ?></p>
														<input type="text" class="form-control" value="<?= $apiIpCurrent['city']; ?>" disabled>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-estado']; ?></p>
														<input type="text" class="form-control" value="<?= $apiIpCurrent['regionName']; ?>" disabled>
													</div>
												</div>

												<div class="col-md-1">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-vpn']; ?></p>
														<input type="text" class="form-control" value="<?php if ($apiIpCurrent['proxy'] == "true") echo "Sim";
																										else echo "Não"; ?>" disabled>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10">&nbsp;</p>
														<a href="/usuarios-inventario/<?= $user['id']; ?>" class="btn ripple btn-light"><?= $lingua['usuarios-editar-verinventario']; ?></a>
													</div>
												</div>

											<?php } ?>
										</div>
										<div class="row row-sm">
											<div class="col-md-1" onclick="location.href='/usuarios-emblemas/<?= $user['id']; ?>'">
												<div class="form-group">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-emblemas']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $UsersBadges; ?>" disabled>
												</div>
											</div>
											<div class="col-md-1" onclick="location.href='/usuarios-quartos/<?= $user['id']; ?>'">
												<div class="form-group">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-quartos']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $rooms; ?>" disabled>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group" onclick="location.href='/usuarios-items/<?= $user['id']; ?>'">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-items']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $items; ?>" disabled>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group" onclick="location.href='/usuarios-grupos/<?= $user['id']; ?>'">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-grupos']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $grupos; ?>" disabled>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group" onclick="location.href='/usuarios-ltds/<?= $user['id']; ?>'">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-rarosltd']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $raros; ?>" disabled>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group" onclick="location.href='/mod-contasip/<?= $user['ip_current']; ?>'">
													<p class="mg-b-10" style="cursor: pointer;"><?= $lingua['usuarios-editar-contasip']; ?></p>
													<input type="text" style="cursor: pointer;" class="form-control" value="<?= $contasNoIP; ?>" disabled>
												</div>
											</div>
											<?php if ($apiIpCurrent['status'] != "success") { ?>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10">&nbsp;</p>
														<a href="/usuarios-inventario/<?= $user['id']; ?>" class="btn ripple btn-light"><?= $lingua['usuarios-editar-verinventario']; ?></a>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->
					<?php }
					if (PHBPermissoes::Check("usuarios_editar")) { ?>
						<!-- Row -->
						<div class="row row-sm mt-xl-2">
							<div class="col-lg-12 col-md-12">
								<div class="card custom-card">
									<div class="card-body">
										<div>
											<h6 class="main-content-label mb-1"><?= $lingua['usuarios-editar-texto3']; ?></h6>
											<p class="text-muted card-sub-title"><?= $lingua['usuarios-editar-texto4']; ?></p>
										</div>
										<?php
										if (isset($_POST['editar-usuario']) && PHBPermissoes::Check("usuarios_editar")) {

											if (isset($_POST['motto']) && isset($_POST['mail']) && isset($_POST['prefixo']) && isset($_POST['game_level']) && isset($_POST['game_total_level']) && isset($_POST['promo_level']) && isset($_POST['respects_received']) && isset($_POST['achievement_score']) &&  $_POST['mail'] != "" && $_POST['game_level'] != "" && $_POST['game_total_level'] != "" && $_POST['promo_level'] != "" && $_POST['respects_received'] != "" && $_POST['achievement_score'] != "") {

												$getUserId = $db->prepare("SELECT id FROM users WHERE `username`= :username");
												$getUserId->bindValue(':username', $_GET['username']);
												$getUserId->execute();
												$userId = $getUserId->fetch()['id'];

												if ($userId == $_SESSION['id_painel']) {
													echo '<div class="alert alert-solid-danger" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>'.$lingua['generico-erro'].'</strong> ' . $lingua['usuarios-editar-erro-simesmo'] . '</div>';
												} else {

													$editarUsuario = $db->prepare("UPDATE `users` SET `motto`= :motto, `mail`= :mail, `game_level`= :game_level, `game_total_level`= :game_total_level, `prefixo`= :prefixo, `promo_level`= :promo_level WHERE `username`= :username");
													$editarUsuario->bindValue(':motto', $_POST['motto']);
													$editarUsuario->bindValue(':mail', $_POST['mail']);
													$editarUsuario->bindValue(':game_level', $_POST['game_level']);
													$editarUsuario->bindValue(':game_total_level', $_POST['game_total_level']);
													$editarUsuario->bindValue(':prefixo', $_POST['prefixo']);
													$editarUsuario->bindValue(':promo_level', $_POST['promo_level']);
													$editarUsuario->bindValue(':username', $_GET['username']);
													$editarUsuario->execute();
													PHBSocket::SendMessage(json_encode(['key' => 'setmotto', 'data' => ['user_id' => $userId, 'motto' => $_POST['motto']]]));

													if (isset($_POST['rank']) && PHBPermissoes::Check("usuarios_editar_rank") && $_POST['rank'] != "") {
														if ($user['rank'] != $_POST['rank']) {
															$editarUsuarioRank = $db->prepare("UPDATE `users` SET `rank`= :rank WHERE `username`= :username");
															$editarUsuarioRank->bindValue(':rank', $_POST['rank']);
															$editarUsuarioRank->bindValue(':username', $_GET['username']);
															$editarUsuarioRank->execute();
															PHBSocket::SendMessage(json_encode(['key' => 'setrank', 'data' => ['user_id' => $userId, 'rank' => $_POST['rank']]]));
														}
													}

													$diferencaConquistas = $_POST['achievement_score'] - $userSettings['achievement_score'];
													$updateConquistas = json_decode(PHBSocket::SendMessage(json_encode(['key' => 'updateuser', 'data' => ['user_id' => $userId, 'achievement_score' => $diferencaConquistas]])));
													if ($updateConquistas->status == "0") {
														$editarUsuarioSettings = $db->prepare("UPDATE users_settings SET respects_received = :respects_received WHERE user_id = :userid");
														$editarUsuarioSettings->bindValue(':respects_received', $_POST['respects_received'], PDO::PARAM_INT);
														$editarUsuarioSettings->bindValue(':userid', $userId);
														$editarUsuarioSettings->execute();
													} else {
														$editarUsuarioSettings = $db->prepare("UPDATE users_settings SET respects_received = :respects_received, achievement_score = :achievement_score WHERE user_id = :userid");
														$editarUsuarioSettings->bindValue(':respects_received', $_POST['respects_received'], PDO::PARAM_INT);
														$editarUsuarioSettings->bindValue(':achievement_score', $_POST['achievement_score'], PDO::PARAM_INT);
														$editarUsuarioSettings->bindValue(':userid', $userId);
														$editarUsuarioSettings->execute();
													}

													PHBLogger::AddLog($lingua['usuarios-editar-log'] . " \"" . $_GET['username'] . "\".");

													echo '<div class="alert alert-solid-success" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['usuarios-editar-sucesso'] . '</div>';
												}
											} else {
												echo '<div class="alert alert-solid-danger" role="alert">
											<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
											<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['usuarios-editar-erro'] . '</div>';
											}
										}

										/// Obtem dados do usuário
										$pegaUser = $db->prepare("SELECT * FROM users WHERE username = :username");
										$pegaUser->bindValue(":username", $_GET['username']);
										$pegaUser->execute();
										$user = $pegaUser->fetch();

										$pegaUsersSettings = $db->prepare("SELECT * FROM users_settings WHERE user_id = :user_id");
										$pegaUsersSettings->bindValue(":user_id", $user['id']);
										$pegaUsersSettings->execute();
										$userSettings = $pegaUsersSettings->fetch();

										?>
										<form method="post">
											<div class="row row-sm">

												<div class="col-md-6">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-nick']; ?></p>
														<input type="text" class="form-control" name="username" value="<?= $user['username']; ?>" disabled>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-missao']; ?></p>
														<input type="text" class="form-control" name="motto" value="<?= strip_tags($user['motto']); ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-email']; ?></p>
														<input type="text" class="form-control" name="mail" value="<?= strip_tags($user['mail']); ?>">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-rank']; ?></p>
														<select class="form-control" name="rank" <?php if (!PHBPermissoes::Check("usuarios_editar_rank")) echo "disabled"; ?>>
															<?php
															$pegaRank = $db->prepare("SELECT id, rank_name FROM permissions");
															$pegaRank->execute();
															while ($rank = $pegaRank->fetch()) {
															?>
																<option value="<?= $rank['id']; ?>" <?php if ($user['rank'] == $rank['id']) echo "selected"; ?>><?= $rank['id']; ?> - <?= $rank['rank_name']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-prefixo']; ?></p>
														<input type="text" class="form-control" name="prefixo" value="<?= $user['prefixo']; ?>">
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-pontoseventotemporada']; ?></p>
														<input type="number" class="form-control" name="game_level" value="<?= $user['game_level']; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-pontoseventototal']; ?></p>
														<input type="number" class="form-control" name="game_total_level" value="<?= $user['game_total_level']; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-pontospromocao']; ?></p>
														<input type="number" class="form-control" name="promo_level" value="<?= $user['promo_level']; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-respeitos-recebidos']; ?></p>
														<input type="number" class="form-control" name="respects_received" value="<?= $userSettings['respects_received']; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-conquistas']; ?></p>
														<input type="number" class="form-control" name="achievement_score" value="<?= $userSettings['achievement_score']; ?>">
													</div>
												</div>
											</div>
											<button type="submit" class="btn ripple btn-primary" name="editar-usuario" style="float: right;margin-top: 15px;"><?= $lingua['usuarios-editar-salvar']; ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->
					<?php }
					if (PHBPermissoes::Check("usuarios_editar_creditos")) { ?>
						<div class="row row-sm mt-xl-2">
							<div class="col-lg-12 col-md-12">
								<div class="card custom-card">
									<div class="card-body">
										<div>
											<h6 class="main-content-label mb-1"><?= $lingua['usuarios-editar-texto5']; ?></h6>
											<p class="text-muted card-sub-title"><?= $lingua['usuarios-editar-texto6']; ?></p>
										</div>
										<?php
										if (isset($_POST['atualizar-creditos'])) {

											$erro = false;
											$diferencaCreditos = $_POST['credits'] - $user['credits'];

											if (!PHBSocket::SendMessage(json_encode(['key' => 'givecredits', 'data' => ['user_id' => $user['id'], 'credits' => $diferencaCreditos]])))
												$erro = true;

											foreach ($_POST as $field => $value) {
												if (substr($field, 0, 9) === "currency_") {

													$currencyId = str_replace("currency_", "", $field);

													$obtemCurrency = $db->prepare("SELECT amount FROM users_currency WHERE user_id = :user AND type = :tipo");
													$obtemCurrency->bindValue(":user", $user['id']);
													$obtemCurrency->bindValue(":tipo", $currencyId);
													$obtemCurrency->execute();
													$currency = $obtemCurrency->fetch();

													$diferencaCurrency = $value - $currency['amount'];
													if (!PHBSocket::SendMessage(json_encode(['key' => 'givepoints', 'data' => ['user_id' => $user['id'], 'points' => $diferencaCurrency, 'type' => $currencyId]])))
														$erro = true;
												}
											}

											if ($erro) {
												echo '<div class="alert alert-solid-danger" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['usuarios-editar-creditos-erro'] . '</div>';
											} else {
												$user['credits'] = $_POST['credits'];
												PHBLogger::AddLog($lingua['usuarios-editar-creditos-log'] . " " . $user['username'] . " (" . $user['id'] . ")");
												echo '<div class="alert alert-solid-success" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
												<span aria-hidden="true">&times;</span></button>
												<strong>' . $lingua['generico-sucesso'] . '</strong> ' . $lingua['usuarios-editar-creditos-sucesso'] . '</div>';
											}
										}
										?>
										<form method="post">
											<div class="row row-sm">
												<div class="col-md-2">
													<div class="form-group">
														<p class="mg-b-10"><?= $lingua['usuarios-editar-creditos-creditos']; ?></p>
														<input type="text" class="form-control" name="credits" value="<?= $user['credits']; ?>">
													</div>
												</div>
												<?php
												$obtemCurrency = $db->prepare("SELECT * FROM users_currency WHERE user_id = :user");
												$obtemCurrency->bindValue(":user", $user['id']);
												$obtemCurrency->execute();
												while ($currency = $obtemCurrency->fetch()) {
												?>
													<div class="col-md-2">
														<div class="form-group">
															<p class="mg-b-10"><?= $config['NomeCurrency.' . $currency['type']]; ?></p>
															<input type="text" class="form-control" name="currency_<?= $currency['type']; ?>" value="<?= $currency['amount']; ?>">
														</div>
													</div>
												<?php } ?>
											</div>
											<button type="submit" class="btn ripple btn-primary" name="atualizar-creditos" style="float: right;margin-top: 15px;"><?= $lingua['usuarios-editar-creditos-salvar']; ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->
					<?php } ?>

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
</body>

</html>