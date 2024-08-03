		<?php if (!defined("PHB_HK")) die("Arquivo bloqueado."); ?>

		<!-- Main Header-->
		<div class="main-header side-header sticky">
			<div class="container-fluid">
				<div class="main-header-left">
					<a class="main-header-menu-icon" href="#" id="mainSidebarToggle"><span></span></a>
				</div>
				<div class="main-header-center">
					<div class="responsive-logo">
						<a href="index.html"><img src="/assets/img/brand/logo.png" class="mobile-logo" alt="logo"></a>
						<a href="index.html"><img src="/assets/img/brand/logo-light.png" class="mobile-logo-dark" alt="logo"></a>
					</div>
					<?php if (PHBPermissoes::Check("usuarios_buscar")) { ?>
						<?php
						if (isset($_POST['search'])) {
							switch ($_POST['search']) {
								case "username":
									$pesquisaUser = $db->prepare("SELECT username FROM users WHERE username = :username");
									$pesquisaUser->bindValue(":username", $_POST['valor']);
									$pesquisaUser->execute();
									if ($pesquisaUser->rowCount() > 0) {
										echo '<meta http-equiv="refresh" content="0;url=/usuarios-editar/' . $pesquisaUser->fetch()['username'] . '">';
									} else {
										echo $lingua['search-erro-nome'];
									}
									break;
								case "email":
									$pesquisaUser = $db->prepare("SELECT username FROM users WHERE mail = :mail");
									$pesquisaUser->bindValue(":mail", $_POST['valor']);
									$pesquisaUser->execute();
									if ($pesquisaUser->rowCount() > 0) {
										echo '<meta http-equiv="refresh" content="0;url=/usuarios-editar/' . $pesquisaUser->fetch()['username'] . '">';
									} else {
										echo $lingua['search-erro-email'];
									}
									break;
								case "id_user":
									$pesquisaUser = $db->prepare("SELECT username FROM users WHERE id = :id");
									$pesquisaUser->bindValue(":id", $_POST['valor']);
									$pesquisaUser->execute();
									if ($pesquisaUser->rowCount() > 0) {
										echo '<meta http-equiv="refresh" content="0;url=/usuarios-editar/' . $pesquisaUser->fetch()['username'] . '">';
									} else {
										echo $lingua['search-erro-id'];
									}
									break;
								case "ip":
									$pesquisaUser = $db->prepare("SELECT username FROM users WHERE ip_current = :ip OR ip_register = :ip LIMIT 1");
									$pesquisaUser->bindValue(":ip", $_POST['valor']);
									$pesquisaUser->execute();
									if ($pesquisaUser->rowCount() > 0) {
										echo '<meta http-equiv="refresh" content="0;url=/mod-contasip/' . $_POST['valor'] . '">';
									} else {
										echo $lingua['search-erro-ip'];
									}
									break;

							}
						}
						?>
						<form method="post">
							<div class="input-group">
								<div class="input-group-btn search-panel">
									<select class="form-control select2-no-search select2-hidden-accessible" name="search" data-select2-id="1" tabindex="-1" aria-hidden="true">
										<option value="username">
											<?= $lingua['search-nome'];?>
										</option>
										<option value="id_user">
											<?= $lingua['search-id'];?>
										</option>
										<option value="email">
											<?= $lingua['search-email'];?>
										</option>
										<option value="ip">
											<?= $lingua['search-ip'];?>
										</option>>
									</select>
								</div>
								<input type="search" class="form-control rounded-0" placeholder="<?= $lingua['search-placeholder'];?>" name="valor">
								<button class="btn search-btn"><i class="fe fe-search"></i></button>
							</div>
						</form>
					<?php } ?>
				</div>
				<div class="main-header-right">
					<div class="dropdown d-md-flex full-screen-link">
						<a class="nav-link icon " href="#">
							<i class="fe fe-maximize fullscreen-button fullscreen header-icons"></i>
							<i class="fe fe-minimize fullscreen-button exit-fullscreen header-icons"></i>
						</a>
					</div>
					<div class="dropdown main-profile-menu">
						<a class="d-flex" href="#">
							<span class="main-img-user"><img alt="avatar" style="height: 65px;margin-top: -14px;" src="<?= $config['AvatarImageURL']; ?>?figure=<?= $myUser['look']; ?>&action=std&gesture=std&direction=2&head_direction=2&size=&headonly=1&img_format=png"></span>
						</a>
						<div class="dropdown-menu">
							<div class="header-navheading">
								<h6 class="main-notification-title"><?= $myUser['username']; ?></h6>
								<p class="main-notification-text"><?= $myUser['rank_name']; ?></p>
							</div>
							<a class="dropdown-item" href="/sair">
								<i class="fe fe-power"></i> <?= $lingua['sign-out'];?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Main Header-->