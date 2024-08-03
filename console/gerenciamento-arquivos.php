<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_arquivos")) {
	header("Location: /index");
	die();
}

$_SESSION['gerenciamento_arquivos'] = PHBPermissoes::Check("gerenciamento_arquivos");

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-arquivos-titulo']; ?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-arquivos-titulo']; ?></title>
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
	<link rel="stylesheet" href="/assets/filemanager/jquery/jquery-ui-1.12.0.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/commands.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/common.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/contextmenu.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/cwd.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/dialog.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/fonts.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/navbar.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/places.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/quicklook.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/statusbar.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/theme.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/toast.css" type="text/css">
	<link rel="stylesheet" href="/assets/filemanager/css/toolbar.css" type="text/css">
	<script src="/assets/filemanager/jquery/jquery-1.12.4.js" type="text/javascript" charset="utf-8"></script>
	<script src="/assets/filemanager/jquery/jquery-ui-1.12.0.js" type="text/javascript" charset="utf-8"></script>
	<script src="/assets/filemanager/js/elFinder.js"></script>
	<script src="/assets/filemanager/js/elFinder.version.js"></script>
	<script src="/assets/filemanager/js/jquery.elfinder.js"></script>
	<script src="/assets/filemanager/js/elFinder.mimetypes.js"></script>
	<script src="/assets/filemanager/js/elFinder.options.js"></script>
	<script src="/assets/filemanager/js/elFinder.options.netmount.js"></script>
	<script src="/assets/filemanager/js/elFinder.history.js"></script>
	<script src="/assets/filemanager/js/elFinder.command.js"></script>
	<script src="/assets/filemanager/js/elFinder.resources.js"></script>
	<script src="/assets/filemanager/js/jquery.dialogelfinder.js"></script>
	<script src="/assets/filemanager/js/i18n/elfinder.en.js"></script>
	<script src="/assets/filemanager/js/ui/button.js"></script>
	<script src="/assets/filemanager/js/ui/contextmenu.js"></script>
	<script src="/assets/filemanager/js/ui/cwd.js"></script>
	<script src="/assets/filemanager/js/ui/dialog.js"></script>
	<script src="/assets/filemanager/js/ui/fullscreenbutton.js"></script>
	<script src="/assets/filemanager/js/ui/navbar.js"></script>
	<script src="/assets/filemanager/js/ui/navdock.js"></script>
	<script src="/assets/filemanager/js/ui/overlay.js"></script>
	<script src="/assets/filemanager/js/ui/panel.js"></script>
	<script src="/assets/filemanager/js/ui/path.js"></script>
	<script src="/assets/filemanager/js/ui/places.js"></script>
	<script src="/assets/filemanager/js/ui/searchbutton.js"></script>
	<script src="/assets/filemanager/js/ui/sortbutton.js"></script>
	<script src="/assets/filemanager/js/ui/stat.js"></script>
	<script src="/assets/filemanager/js/ui/toast.js"></script>
	<script src="/assets/filemanager/js/ui/toolbar.js"></script>
	<script src="/assets/filemanager/js/ui/tree.js"></script>
	<script src="/assets/filemanager/js/ui/uploadButton.js"></script>
	<script src="/assets/filemanager/js/ui/viewbutton.js"></script>
	<script src="/assets/filemanager/js/ui/workzone.js"></script>
	<script src="/assets/filemanager/js/commands/archive.js"></script>
	<script src="/assets/filemanager/js/commands/back.js"></script>
	<script src="/assets/filemanager/js/commands/copy.js"></script>
	<script src="/assets/filemanager/js/commands/cut.js"></script>
	<script src="/assets/filemanager/js/commands/chmod.js"></script>
	<script src="/assets/filemanager/js/commands/colwidth.js"></script>
	<script src="/assets/filemanager/js/commands/download.js"></script>
	<script src="/assets/filemanager/js/commands/duplicate.js"></script>
	<script src="/assets/filemanager/js/commands/edit.js"></script>
	<script src="/assets/filemanager/js/commands/empty.js"></script>
	<script src="/assets/filemanager/js/commands/extract.js"></script>
	<script src="/assets/filemanager/js/commands/forward.js"></script>
	<script src="/assets/filemanager/js/commands/fullscreen.js"></script>
	<script src="/assets/filemanager/js/commands/getfile.js"></script>
	<script src="/assets/filemanager/js/commands/help.js"></script>
	<script src="/assets/filemanager/js/commands/hidden.js"></script>
	<script src="/assets/filemanager/js/commands/home.js"></script>
	<script src="/assets/filemanager/js/commands/info.js"></script>
	<script src="/assets/filemanager/js/commands/mkdir.js"></script>
	<script src="/assets/filemanager/js/commands/mkfile.js"></script>
	<script src="/assets/filemanager/js/commands/netmount.js"></script>
	<script src="/assets/filemanager/js/commands/open.js"></script>
	<script src="/assets/filemanager/js/commands/opendir.js"></script>
	<script src="/assets/filemanager/js/commands/paste.js"></script>
	<script src="/assets/filemanager/js/commands/places.js"></script>
	<script src="/assets/filemanager/js/commands/quicklook.js"></script>
	<script src="/assets/filemanager/js/commands/quicklook.plugins.js"></script>
	<script src="/assets/filemanager/js/commands/reload.js"></script>
	<script src="/assets/filemanager/js/commands/rename.js"></script>
	<script src="/assets/filemanager/js/commands/resize.js"></script>
	<script src="/assets/filemanager/js/commands/restore.js"></script>
	<script src="/assets/filemanager/js/commands/rm.js"></script>
	<script src="/assets/filemanager/js/commands/search.js"></script>
	<script src="/assets/filemanager/js/commands/selectall.js"></script>
	<script src="/assets/filemanager/js/commands/selectinvert.js"></script>
	<script src="/assets/filemanager/js/commands/selectnone.js"></script>
	<script src="/assets/filemanager/js/commands/sort.js"></script>
	<script src="/assets/filemanager/js/commands/undo.js"></script>
	<script src="/assets/filemanager/js/commands/up.js"></script>
	<script src="/assets/filemanager/js/commands/upload.js"></script>
	<script src="/assets/filemanager/js/commands/view.js"></script>
	<script src="/assets/filemanager/js/proxy/elFinderSupportVer1.js"></script>
	<script src="/assets/filemanager/js/extras/editors.default.js"></script>
	<script src="/assets/filemanager/js/extras/quicklook.googledocs.js"></script>
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
					<?php if (PHBPermissoes::Check("gerenciamento_arquivos")) { ?><script>
							$(function() {
								$('#elfinder').elfinder({
										cssAutoLoad: false,
										baseUrl: './',
										url: '/assets/filemanager/php/connector.minimal.php',
										getFileCallback: function(file) {},
									},
									function(fm, extraObj) {
										fm.bind('init', function() {
											delete fm.options.rawStringDecoder;
											if (fm.lang === 'ja') {
												fm.loadScript(
													[fm.baseUrl + '/assets/filemanager/js/extras/encoding-japanese.min.js'],
													function() {
														if (window.Encoding && Encoding.convert) {
															fm.options.rawStringDecoder = function(s) {
																return Encoding.convert(s, {
																	to: 'UNICODE',
																	type: 'string'
																});
															};
														}
													}, {
														loadType: 'tag'
													}
												);
											}
										});
										var title = document.title;
										fm.bind('open', function() {
											var path = '',
												cwd = fm.cwd();
											if (cwd) {
												path = fm.path(cwd.hash) || null;
											}
											document.title = path ? path + ':' + title : title;
										}).bind('destroy', function() {
											document.title = title;
										});
									}
								);
							});
						</script>
					<?php } ?>
					</head>
					<div class="row row-sm">
						<div class="col-lg-12 mt-xl-4">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div>
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-arquivos-texto1']; ?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-arquivos-texto2']; ?></p>
									</div>
									<div id="elfinder"></div>
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
</body>

</html>