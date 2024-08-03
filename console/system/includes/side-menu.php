		<?php if (!defined("PHB_HK")) die("Arquivo bloqueado."); ?>
		<!-- Sidemenu -->
		<div class="main-sidebar main-sidebar-sticky side-menu">
			<div class="sidemenu-logo">
				<a class="main-logo" href="/principal">
					<img src="/assets/img/brand/logo-light.png" class="header-brand-img desktop-logo" alt="logo">
					<img src="/assets/img/brand/icon-light.png" class="header-brand-img icon-logo" alt="logo">
					<img src="/assets/img/brand/logo.png" class="header-brand-img desktop-logo theme-logo" alt="logo">
					<img src="/assets/img/brand/icon.png" class="header-brand-img icon-logo theme-logo" alt="logo">
				</a>
			</div>
			<div class="main-sidebar-body">
				<ul class="nav">
					<li class="nav-header"><span class="nav-label"><?= $lingua['menu-titulo']; ?></span></li>
					<li class="nav-item">
						<a class="nav-link" href="/principal"><span class="shape1"></span><span class="shape2"></span><i class="ti-home sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-principal']; ?></span></a>
					</li>
					<?php if (PHBPermissoes::Categoria("noticia")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="mdi mdi-newspaper sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-noticias']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("noticia_escrever")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/noticia-escrever"><?= $lingua['menu-noticias-escrever']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("noticia_historico")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/noticia-historico"><?= $lingua['menu-noticias-historico']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("logs")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fas fa-clipboard-list sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-logs']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("logs_conversas")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-conversas"><?= $lingua['menu-logs-conversasdequarto']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_console")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-console"><?= $lingua['menu-logs-conversasprivadas']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_staffs")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-staffs"><?= $lingua['menu-logs-comandosstaffs']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_trocas")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-trocas"><?= $lingua['menu-logs-trocas']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_raros")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-raros"><?= $lingua['menu-logs-comprasderaros']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_hk")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-hk"><?= $lingua['menu-logs-paineldecontrole']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_marketplace")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-marketplace"><?= $lingua['menu-logs-feiralivre']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_escolhermobis")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-escolher"><?= $lingua['menu-logs-escolhermobis']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_trocadenome")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-trocasdenome"><?= $lingua['menu-logs-trocasdenome']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_comprasdequarto")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-comprasdequarto"><?= $lingua['menu-logs-comprasdequarto']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_pay")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-pay"><?= $lingua['menu-logs-pagamentos']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_eventos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-eventos"><?= $lingua['menu-logs-eventosrealizados']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_login")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-login"><?= $lingua['menu-logs-login']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("logs_redeem")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/logs-redeem"><?= $lingua['menu-logs-redeem']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("emblema")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fa fa-certificate sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-emblemas']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("emblema_hospedar")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/emblema-hospedar"><?= $lingua['menu-emblemas-hospedar']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("emblema_hospedados")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/emblema-hospedados"><?= $lingua['menu-emblemas-hospedados']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("mod")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fas fa-ban sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-moderacao']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("mod_filtro")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/mod-filtro"><?= $lingua['menu-moderacao-filtro']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("mod_bans")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/mod-bans"><?= $lingua['menu-moderacao-banidos']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("mod_ip")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/mod-ip"><?= $lingua['menu-moderacao-contasip']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("usuarios")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fas fa-users sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-usuarios']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("usuarios_darcreditos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/usuarios-darcreditos"><?= $lingua['menu-usuarios-darcreditos']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("usuarios_online")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/usuarios-online"><?= $lingua['menu-usuarios-usuarios-onlines']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("usuarios_staffs")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/usuarios-staffs"><?= $lingua['menu-usuarios-staffs-onlines']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("usuarios_fakes")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/usuarios-fakes"><?= $lingua['menu-usuarios-contas-fakes']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("gerenciamento")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fas fa-cogs sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-gerenciamento']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("gerenciamento_hospedarimagem")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-hospedarimagem"><?= $lingua['menu-gerenciamento-hospedar-imagem']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_arquivos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-arquivos"><?= $lingua['menu-gerenciamento-gerenciar-arquivos']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_emulador")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-emulador"><?= $lingua['menu-gerenciamento-gerenciar-emulador']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_bancodedados")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-bancodedados"><?= $lingua['menu-gerenciamento-gerenciar-bancodedados']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_permissoes")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-permissoes"><?= $lingua['menu-gerenciamento-gerenciar-permissoes']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_permissoespainel")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-permissoespainel"><?= $lingua['menu-gerenciamento-gerenciar-permissoes-painel']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_textos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-textos"><?= $lingua['menu-gerenciamento-gerenciar-textos']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_catalogo_paginas")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-catalogo-paginas"><?= $lingua['menu-gerenciamento-gerenciar-catalogo']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_enviaralertastaff")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-enviaralertastaff"><?= $lingua['menu-gerenciamento-alerta-staff']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_addmobis")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-addmobis"><?= $lingua['menu-gerenciamento-adicionar-mobis']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_configuracoespainel")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-configuracoespainel"><?= $lingua['menu-gerenciamento-config-painel']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("gerenciamento_configuracoesemulador")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/gerenciamento-configuracoesemulador"><?= $lingua['menu-gerenciamento-config-emulador']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("raros")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fa fa-shopping-cart sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-raros']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("raros_adicionar")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/raros-adicionar"><?= $lingua['menu-raros-adicionar']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("raros_adicionados")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/raros-adicionados"><?= $lingua['menu-raros-adicionados']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("relatorio")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fa fa-line-chart sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-relatorio']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("relatorio_onlines")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/relatorio-onlines"><?= $lingua['menu-relatorio-usuarios-online']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("relatorio_eventos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/relatorio-eventos"><?= $lingua['menu-relatorio-eventos-realizados']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("relatorio_eventos")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/relatorio-eventosusuarios"><?= $lingua['menu-relatorio-eventos-realizados-usuario']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (PHBPermissoes::Categoria("vips")) { ?>
						<li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="fa fa-star sidemenu-icon"></i><span class="sidemenu-label"><?= $lingua['menu-vips']; ?></span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<?php if (PHBPermissoes::Check("vips_adicionar")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/vips-adicionar"><?= $lingua['menu-vips-adicionar']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("vips_usuarios")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/vips-usuarios"><?= $lingua['menu-vips-usuarios']; ?></a>
									</li>
								<?php } ?>
								<?php if (PHBPermissoes::Check("vips_logs")) { ?>
									<li class="nav-sub-item">
										<a class="nav-sub-link" href="/vips-logs"><?= $lingua['menu-vips-logs']; ?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<!-- End Sidemenu -->