		<?php if (!defined("PHB_HK")) die("Arquivo bloqueado."); ?>
		<!-- Mobile-header -->
		<div class="mobile-main-header">
			<div class="mb-1 navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
				<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
					<div class="d-flex order-lg-2 ml-auto">
						<div class="dropdown header-search">
							<a class="nav-link icon header-search">
								<i class="fe fe-search header-icons"></i>
							</a>
							<div class="dropdown-menu">
								<div class="main-form-search p-2">
									<div class="input-group">
										<div class="input-group-btn search-panel">
											<select class="form-control select2-no-search">
												<option value="username">
													Nome de usuário
												</option>
												<option value="email">
													Email de usuário
												</option>
												<option value="ip">
													Endereço de IP
												</option>>
												<option value="id_user">
													ID User
												</option>
											</select>
										</div>
										<input type="search" class="form-control" placeholder="Pesquise por alguma coisa...">
										<button class="btn search-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
												<circle cx="11" cy="11" r="8"></circle>
												<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
											</svg></button>
									</div>
								</div>
							</div>
						</div>
						<div class="dropdown full-screen-link">
							<a class="nav-link icon ">
								<i class="fe fe-maximize fullscreen-button fullscreen header-icons"></i>
								<i class="fe fe-minimize fullscreen-button exit-fullscreen header-icons"></i>
							</a>
						</div>
						<div class="dropdown main-profile-menu">
							<a class="d-flex" href="#">
								<span class="main-img-user"><img alt="avatar" src="<?= $config['AvatarImageURL']; ?>?figure=<?= $myUser['look']; ?>&action=std&gesture=std&direction=2&head_direction=2&size=&headonly=1&img_format=png"></span>
							</a>
							<div class="dropdown-menu">
								<div class="header-navheading">
									<h6 class="main-notification-title"><?= $myUser['username']; ?></h6>
									<p class="main-notification-text"><?= $myUser['rank_name']; ?></p>
								</div>
								<a class="dropdown-item" href="/sair">
									<i class="fe fe-power"></i> Sign Out
								</a>
							</div>
						</div>
						<div class="dropdown  header-settings">
							<a href="#" class="nav-link icon" data-toggle="sidebar-right" data-target=".sidebar-right">
								<i class="fe fe-align-right header-icons"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Mobile-header closed -->