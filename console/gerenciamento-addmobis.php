<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/system/global.php';
if (!isset($_SESSION['id_painel'])) {
	header("Location: /index");
	die();
}
if (!PHBPermissoes::Check("gerenciamento_addmobis")) {
	header("Location: /index");
	die();
}

set_time_limit(0);

$arrRequestHeaders = array(
	'http' => array(
		'method'        => 'GET',
		'protocol_version'    => 1.1,
		'follow_location'    => 1,
		'header' =>    "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
	)
);

if (isset($_POST['baixar-mobis']) && PHBPermissoes::Check("gerenciamento_addmobis")) {
	$xml = $_POST['xml'];
	$xmlwall = $_POST['xml_wall'];
	$pasta = $_POST['catalog_name'] . "-" . time();
	$pagina = $_POST['catalog_name'];
	$page_parent_id = $_POST['parent_id'];
	$path_sql = "system/downloads/" . $pasta . "/sql.sql";
	$path_xml = "system/downloads/" . $pasta . "/xml.txt";
	$path_xml_original = "system/downloads/" . $pasta . "/xml_original.txt";
	$path_pasta = "system/downloads/" . $pasta . "/mobis/";
	$path_icones = "system/downloads/" . $pasta . "/icones/";
	$i = 0;
	$xml_escreve = "";
	$sql_escreve = "";
	$zip_file = "system/zip/" . $pasta . ".zip";
	$zip_path = "system/downloads/" . $pasta . "/";
	$_POST['download'] = '1';

	$furnidataRoom = simplexml_load_string("<phb>" . $xml . "</phb>");
	$furnidataWall = simplexml_load_string("<phb>" . $xmlwall . "</phb>");

	@mkdir("system/downloads/");
	@mkdir("system/zip/");
	@mkdir("system/downloads/" . $pasta);
	@mkdir($path_pasta);
	@mkdir($path_icones);

	if(empty($_POST['catalog_icon']) || $_POST['catalog_icon'] < 1)
		$_POST['catalog_icon'] = 0;

	$inserePaginaCatalogo = $db->prepare("INSERT INTO `catalog_pages` (`parent_id`, `caption`, `icon_image`) VALUES (:parent, :caption, :icon);");
	$inserePaginaCatalogo->bindParam(":parent", $page_parent_id);
	$inserePaginaCatalogo->bindParam(":caption", $pagina);
	$inserePaginaCatalogo->bindParam(":icon", $_POST['catalog_icon']);
	$inserePaginaCatalogo->execute();
	$itemPageId = $db->lastInsertId();

	$sql_escreve .= "INSERT INTO `catalog_pages` (`id`, `parent_id`, `caption`, `icon_image`) VALUES ('" . $itemPageId . "', '" . $page_parent_id . "', '" . $pagina . "', '" . $_POST['catalog_icon'] . "');" . "\n";

	/// Faz backup da furnidata
	$nameXmlFile = $zip_path . "furnidata_backup_" . time() . ".xml";

	PHBTools::copy($config['SwfFurnidataUrl'], $nameXmlFile, stream_context_create($arrRequestHeaders)); /// baixa furnidata.xml

	if ($_POST['download'] == '0') {
		PHBTools::downloadFileFromUrl($config['SwfFurnidataUrl'], $nameXmlFile); /// baixa a furnidata sozinha se não tiver o zip.
	}

	$furnidataXml = simplexml_load_file($nameXmlFile); /// Abre a furnidata copiada para edição

	/// ROOM
	$xml_escreve .= "<roomitemtypes>" . "\n";
	foreach ($furnidataRoom->furnitype as $mobi) {

		$i++;

		if($mobi->xdim == null || $mobi->xdim < 1)
			$mobi->xdim = 1;
		
		if($mobi->ydim == null || $mobi->ydim < 1)
			$mobi->ydim = 1;
		
		if($mobi->cansiton == null || $mobi->cansiton < 1)
			$mobi->cansiton = 0;
		
		if($mobi->name == null || $mobi->name < 1)
			$mobi->name = $mobi['classname'];
		
		/// Insere items_base
		$insereItem = $db->prepare("INSERT INTO `items_base` (`sprite_id`, `public_name`, `item_name`, `type`, `width`, `length`, `stack_height`, `allow_sit`, `interaction_type`) VALUES (:sprite_id, :public_name, :item_name, 's', :width, :length, :stack_height, :allow_sit, :interaction_type);");
		$insereItem->bindValue(":sprite_id", "0");
		$insereItem->bindParam(":public_name", $mobi->name);
		$insereItem->bindParam(":item_name", $mobi['classname']);
		$insereItem->bindParam(":width", $mobi->xdim);
		$insereItem->bindParam(":length", $mobi->ydim);
		$insereItem->bindValue(":stack_height", "1");
		$insereItem->bindValue(":allow_sit", str_replace(".", "", $mobi->cansiton));
		$insereItem->bindValue(":interaction_type", "default");
		$insereItem->execute();
		$itemBaseId = $db->lastInsertId();

		/// Update Sprite ID
		$updateSpriteId = $db->prepare("UPDATE `items_base` SET `sprite_id` = :sprite_id WHERE id = :id;");
		$updateSpriteId->bindParam(":sprite_id", $itemBaseId);
		$updateSpriteId->bindParam(":id", $itemBaseId);
		$updateSpriteId->execute();

		/// Insere Catalog Items
		$insereCatalog = $db->prepare("INSERT INTO `catalog_items` (`item_ids`, `page_id`, `catalog_name`, `offer_id`) VALUES (:item_id, :page_id, :catalog_name, :offer_id);");
		$insereCatalog->bindParam(":item_id", $itemBaseId);
		$insereCatalog->bindParam(":page_id", $itemPageId);
		$insereCatalog->bindParam(":catalog_name", $mobi->name);
		$insereCatalog->bindParam(":offer_id", $itemBaseId);
		$insereCatalog->execute();
		$itemCatalog = $db->lastInsertId();

		$sql_escreve .= "INSERT INTO `items_base` (`id`, `sprite_id`, `public_name`, `item_name`, `type`, `width`, `length`, `stack_height`, `allow_sit`, `interaction_type`) VALUES ('" . $itemBaseId . "', '" . $itemBaseId . "', '" . $mobi->name . "', '" . $mobi['classname'] . "', 's', '" . $mobi->xdim . "', '" . $mobi->ydim . "', '1', '".$mobi->cansiton."', 'default');" . "\n";
		$sql_escreve .= "INSERT INTO `catalog_items` (`id`, `item_ids`, `page_id`, `catalog_name`, `offer_id`) VALUES ('" . $itemCatalog . "', '" . $itemBaseId . "', '" . $itemPageId . "', '" . $mobi->name . "', '" . $itemBaseId . "');" . "\n";;

		/// Gera arquivo XML
		$xml_escreve .= '<furnitype id="' . $itemBaseId . '" classname="' . $mobi['classname'] . '">' . "\n";
		$xml_escreve .= '<partcolors>';
		foreach ($mobi->partcolors->color as $cor) {
			$xml_escreve .= "<color>" . $cor . "</color>";
		}
		$xml_escreve .= '</partcolors>' . "\n";
		$xml_escreve .= '<name>' . $mobi->name . '</name>' . "\n";
		$xml_escreve .= '<description>' . $mobi->description . '</description>' . "\n";
		$xml_escreve .= '<adurl/>' . "\n";
		$xml_escreve .= '<offerid>' . $itemBaseId . '</offerid><rentofferid>-1</rentofferid><rentbuyout>0</rentbuyout>' . "\n";
		$xml_escreve .= '</furnitype>' . "\n";

		/// Adiciona na XML
		$furniType = $furnidataXml->roomitemtypes->addChild("furnitype");
		$furniType->addAttribute("id", $itemBaseId);
		$furniType->addAttribute("classname", $mobi['classname']);
		$partcolors = $furniType->addChild("partcolors");
		foreach ($mobi->partcolors->color as $cor) {
			$partcolors->addChild("color", $cor);
		}
		$furniType->addChild("name", $mobi->name);
		$furniType->addChild("description", $mobi->description);
		$furniType->addChild("adurl");
		$furniType->addChild("offerid", $itemBaseId);
		$furniType->addChild("rentofferid", '-1');
		$furniType->addChild("rentbuyout", '0');

		/// Download do mobi
		if (mb_strpos($mobi['classname'], '*') !== false) {
			$icone = str_replace("*", "_", $mobi['classname']);
			$mobi['classname'] = explode("*", $mobi['classname'])[0];
		} else {
			$icone = $mobi['classname'];
		}

		if ($_POST['hotel'] == "habbocity") {
			PHBTools::copy("https://swf.habbocity.me/dcr/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://swf.habbocity.me/dcr/hof_furni/icons2/" . $icone . "_icon.png", $path_icones . $icone . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "space") {
			PHBTools::copy("https://images.spacehotel.co/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://images.spacehotel.co/hof_furni/icon/view.php?name=" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "iron") {
			PHBTools::copy("https://cdn.ironhotel.biz/static_global/furniture/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://cdn.ironhotel.biz/static_global/furniture/icons/" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "age") {
			PHBTools::copy("https://agehotel.info/game/dcr/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://agehotel.info/game/dcr/hof_furni/images/" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		}  else {
			PHBTools::copy("https://images.habbogroup.com/dcr/hof_furni/" . $mobi->revision . "/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://images.habbogroup.com/dcr/hof_furni/" . $mobi->revision . "/" . $icone . "_icon.png", $path_icones . $icone . "_icon.png", stream_context_create($arrRequestHeaders));
		}

		/// Move arquivo baixado para a SWF
		PHBTools::CopiarArquivo($path_pasta . $mobi['classname'] . ".swf", $config['SWFMobisPath'] . $mobi['classname'] . ".swf"); // swf
		PHBTools::CopiarArquivo($path_icones . $icone . "_icon.png", $config['SWFMobisIconPath'] . $mobi['classname'] . "_icon.png"); // icon

	}
	$xml_escreve .= "</roomitemtypes>" . "\n";

	/// WALL
	$xml_escreve .= "<wallitemtypes>" . "\n";
	foreach ($furnidataWall->furnitype as $mobi) {

		$i++;

		if($mobi->xdim == null || $mobi->xdim < 1)
			$mobi->xdim = 1;
		
		if($mobi->ydim == null || $mobi->ydim < 1)
			$mobi->ydim = 1;
		
		if($mobi->cansiton == null || $mobi->cansiton < 1)
			$mobi->cansiton = 0;
		
		if($mobi->name == null || $mobi->name < 1)
			$mobi->name = $mobi['classname'];
		
		/// Insere items_base
		$insereItem = $db->prepare("INSERT INTO `items_base` (`sprite_id`, `public_name`, `item_name`, `type`, `width`, `length`, `stack_height`, `allow_sit`, `interaction_type`) VALUES (:sprite_id, :public_name, :item_name, 'i', :width, :length, :stack_height, :allow_sit, :interaction_type);");
		$insereItem->bindValue(":sprite_id", "0");
		$insereItem->bindParam(":public_name", $mobi->name);
		$insereItem->bindParam(":item_name", $mobi['classname']);
		$insereItem->bindParam(":width", $mobi->xdim);
		$insereItem->bindParam(":length", $mobi->ydim);
		$insereItem->bindValue(":stack_height", "1");
		$insereItem->bindValue(":allow_sit", str_replace(".", "", $mobi->cansiton));
		$insereItem->bindValue(":interaction_type", "default");
		$insereItem->execute();
		$itemBaseId = $db->lastInsertId();

		/// Update Sprite ID
		$updateSpriteId = $db->prepare("UPDATE `items_base` SET `sprite_id` = :sprite_id WHERE id = :id;");
		$updateSpriteId->bindParam(":sprite_id", $itemBaseId);
		$updateSpriteId->bindParam(":id", $itemBaseId);
		$updateSpriteId->execute();

		/// Insere Catalog Items
		$insereCatalog = $db->prepare("INSERT INTO `catalog_items` (`item_ids`, `page_id`, `catalog_name`, `offer_id`) VALUES (:item_id, :page_id, :catalog_name, :offer_id);");
		$insereCatalog->bindParam(":item_id", $itemBaseId);
		$insereCatalog->bindParam(":page_id", $itemPageId);
		$insereCatalog->bindParam(":catalog_name", $mobi->name);
		$insereCatalog->bindParam(":offer_id", $itemBaseId);
		$insereCatalog->execute();
		$itemCatalog = $db->lastInsertId();

		$sql_escreve .= "INSERT INTO `items_base` (`id`, `sprite_id`, `public_name`, `item_name`, `type`, `width`, `length`, `stack_height`, `allow_sit`, `interaction_type`) VALUES ('" . $itemBaseId . "', '" . $itemBaseId . "', '" . $mobi->name . "', '" . $mobi['classname'] . "', 'i', '" . $mobi->xdim . "', '" . $mobi->ydim . "', '1', '".$mobi->cansiton."', 'default');" . "\n";
		$sql_escreve .= "INSERT INTO `catalog_items` (`id`, `item_ids`, `page_id`, `catalog_name`, `offer_id`) VALUES ('" . $itemCatalog . "', '" . $itemBaseId . "', '" . $itemPageId . "', '" . $mobi->name . "', '" . $itemBaseId . "');" . "\n";;

		/// Gera arquivo XML
		$xml_escreve .= '<furnitype id="' . $itemBaseId . '" classname="' . $mobi['classname'] . '">' . "\n";
		$xml_escreve .= '<name>' . $mobi->name . '</name>' . "\n";
		$xml_escreve .= '<description>' . $mobi->description . '</description>' . "\n";
		$xml_escreve .= '<adurl/>' . "\n";
		$xml_escreve .= '<offerid>' . $itemBaseId . '</offerid><rentofferid>-1</rentofferid><rentbuyout>0</rentbuyout>' . "\n";
		$xml_escreve .= '</furnitype>' . "\n";

		/// Adiciona na XML
		$furniType = $furnidataXml->wallitemtypes->addChild("furnitype");
		$furniType->addAttribute("id", $itemBaseId);
		$furniType->addAttribute("classname", $mobi['classname']);
		$furniType->addChild("name", $mobi->name);
		$furniType->addChild("description", $mobi->description);
		$furniType->addChild("adurl");
		$furniType->addChild("offerid", $itemBaseId);
		$furniType->addChild("rentofferid", '-1');
		$furniType->addChild("rentbuyout", '0');

		/// Download do mobi
		if (mb_strpos($mobi['classname'], '*') !== false) {
			$icone = str_replace("*", "_", $mobi['classname']);
			$mobi['classname'] = explode("*", $mobi['classname'])[0];
		} else {
			$icone = $mobi['classname'];
		}

		if ($_POST['hotel'] == "habbocity") {
			PHBTools::copy("https://swf.habbocity.me/dcr/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://swf.habbocity.me/dcr/hof_furni/icons2/" . $icone . "_icon.png", $path_icones . $icone . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "space") {
			PHBTools::copy("https://images.spacehotel.co/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://images.spacehotel.co/hof_furni/icon/view.php?name=" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "iron") {
			PHBTools::copy("https://cdn.ironhotel.biz/static_global/furniture/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://cdn.ironhotel.biz/static_global/furniture/icons/" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		} else if ($_POST['hotel'] == "age") {
			PHBTools::copy("https://agehotel.info/game/dcr/hof_furni/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://agehotel.info/game/dcr/hof_furni/images/" . $mobi['classname'] . "_icon.png", $path_icones . $mobi['classname'] . "_icon.png", stream_context_create($arrRequestHeaders));
		} else {
			PHBTools::copy("https://images.habbogroup.com/dcr/hof_furni/" . $mobi->revision . "/" . $mobi['classname'] . ".swf", $path_pasta . $mobi['classname'] . ".swf", stream_context_create($arrRequestHeaders));
			PHBTools::copy("https://images.habbogroup.com/dcr/hof_furni/" . $mobi->revision . "/" . $icone . "_icon.png", $path_icones . $icone . "_icon.png", stream_context_create($arrRequestHeaders));
		}

		/// Move arquivo baixado para a SWF
		PHBTools::CopiarArquivo($path_pasta . $mobi['classname'] . ".swf", $config['SWFMobisPath'] . $mobi['classname'] . ".swf"); // swf
		PHBTools::CopiarArquivo($path_icones . $icone . "_icon.png", $config['SWFMobisIconPath'] . $mobi['classname'] . "_icon.png"); // icon

	}
	$xml_escreve .= "</wallitemtypes>" . "\n";

	/// Salva a furnidata
	$furnidataXml->saveXML($zip_path . "furnidata_atualizada.xml");

	/// Move a furnidata para a swf.
	PHBTools::removerArquivo($config['SWFGamedataFurnidataPath']); // remove furnidata antiga
	PHBTools::CopiarArquivo($zip_path . "furnidata_atualizada.xml", $config['SWFGamedataFurnidataPath']); // bota a furnidata nova

	/// Salva xml e sql.
	fwrite(fopen($path_sql, "w"), $sql_escreve);
	fwrite(fopen($path_xml, "w"), $xml_escreve);
	fwrite(fopen($path_xml_original, "w"), "<roomitemtypes>" . $xml . "</roomitemtypes><wallitemtypes>" . $xmlwall . "</wallitemtypes>");

	PHBLogger::AddLog(str_replace("%count%", $i, str_replace("%pagina%", $pagina . " (" . $itemPageId . ")", $lingua['gerenciamento-addmobis-log'])));

	/// Gera arquivo ZIP e realiza o download.
	$zipDownload = new PHBZip($zip_path, $zip_file);
}


?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-addmobis-titulo'];?></title>
	<meta name="author" content="PHB">
	<meta name="keywords" content="<?= $config['WebsiteKeywords']; ?> ">
	<!-- Favicon -->
	<link rel="icon" href="<?= $config['Favicon']; ?>" type="image/x-icon" />
	<!-- Title -->
	<title><?= $config['NomeHotel']; ?> - <?= $lingua['gerenciamento-addmobis-titulo'];?></title>
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
										<h6 class="main-content-label mb-1"><?= $lingua['gerenciamento-addmobis-texto1'];?></h6>
										<p class="text-muted card-sub-title"><?= $lingua['gerenciamento-addmobis-texto2'];?></p>
									</div>
									<div class="alert alert-solid-success" role="alert" id="mensagem" style="display:none"> 
										<button aria-label="Close" class="close" data-dismiss="alert" type="button">
											<span aria-hidden="true">&times;</span></button>
										<strong><?= $lingua['generico-sucesso'];?></strong> <?= $lingua['gerenciamento-addmobis-sucesso'];?>
									</div>
									<form method="post">
										<div class="row row-sm">
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-nome'];?></p>
													<input type="text" class="form-control" name="catalog_name" placeholder="<?= $lingua['gerenciamento-addmobis-nome-placeholder'];?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-paginapai'];?></p>
													<select class="form-control" name="parent_id">
														<?php
														$getCatalogPages = $db->prepare("SELECT id,caption FROM catalog_pages ORDER BY id");
														$getCatalogPages->execute();
														while ($page = $getCatalogPages->fetch()) {
														?>
															<option value="<?= $page['id'] ?>"><?= $page['caption']; ?> | <?= $page['id'] ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-iconepagina'];?></p>
													<input type="number" class="form-control" name="catalog_icon" placeholder="<?= $lingua['gerenciamento-addmobis-iconepagina-placeholder'];?>">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-hotel'];?></p>
													<select class="form-control" name="hotel">
														<option value="habbo">habbo.com</option>
														<option value="habbocity">habbocity.me</option>
														<option value="space">spacehotel.co</option>
														<option value="iron">ironhotel.biz</option>
														<option value="age">agehotel.info</option>
													</select>
												</div>
											</div>
											<div class="col-md-12 ">
												<div class="form-group mb-0">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-xmlroom'];?></p>
													<textarea class="form-control" name="xml" rows="4" id="ckeditor"></textarea>
												</div>
											</div>

											<div class="col-md-12" style="margin-top:10px">
												<div class="form-group mb-0">
													<p class="mg-b-10"><?= $lingua['gerenciamento-addmobis-xmlwall'];?></p>
													<textarea class="form-control" name="xml_wall" rows="4" id="ckeditor"></textarea>
												</div>
											</div>

										</div>
										<button type="submit" class="btn ripple btn-primary" name="baixar-mobis" style="float: right;margin-top: 15px;"><?= $lingua['gerenciamento-addmobis-botao'];?></button>
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
	<!-- cookie -->
	<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
	<script>
		setInterval(function() {
			if (Cookies.get('phb')) {
				Cookies.remove('phb')
				document.getElementById("mensagem").style.display = "";
			}
		}, 1000);
	</script>
</body>

</html>