<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBDatabase
{
    public static function CheckDB()
    {

        //// Cria tabelas
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_permissoes` (`id` INT(11) NULL DEFAULT NULL, `login` ENUM('0','1') NULL DEFAULT '1' COLLATE 'latin1_swedish_ci') COLLATE='latin1_swedish_ci' ENGINE=InnoDB;"); // Permissões
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_settings` (`chave` VARCHAR(50) NOT NULL DEFAULT 'Habbo' COLLATE 'latin1_swedish_ci',`valor` VARCHAR(1000) NULL DEFAULT 'Habbo' COLLATE 'latin1_swedish_ci',`descricao` VARCHAR(500) NULL DEFAULT 'Habbo' COLLATE 'latin1_swedish_ci',UNIQUE INDEX `chave` (`chave`) USING BTREE)COLLATE='latin1_swedish_ci'ENGINE=InnoDB;"); // Configuraçoes
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_logs` (`id` INT(11) NOT NULL AUTO_INCREMENT,`usuario` INT(11) NULL DEFAULT NULL,`log` VARCHAR(200) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',`data` INT(100) NULL DEFAULT NULL,PRIMARY KEY (`id`) USING BTREE) COLLATE='latin1_swedish_ci' ENGINE=InnoDB ROW_FORMAT=COMPACT AUTO_INCREMENT=1;"); // Logs
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_news` (`id` INT(11) NOT NULL AUTO_INCREMENT,`title` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',`image` VARCHAR(100) NOT NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',`shortstory` TEXT(65535) NOT NULL COLLATE 'latin1_swedish_ci',`longstory` TEXT(65535) NOT NULL COLLATE 'latin1_swedish_ci',`author` VARCHAR(100) NOT NULL DEFAULT 'Tom' COLLATE 'latin1_swedish_ci',`date` INT(11) NOT NULL DEFAULT '0',`curtidas` VARCHAR(100) NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',`visitas` INT(11) NULL DEFAULT '0',`tipo` ENUM('noticia','promocao') NULL DEFAULT 'noticia' COLLATE 'latin1_swedish_ci',`apagada` ENUM('0','1') NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',PRIMARY KEY (`id`) USING BTREE) COLLATE='latin1_swedish_ci' ENGINE=InnoDB ROW_FORMAT=COMPACT;"); // Noticias
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_emblemas` (`id` INT(11) NOT NULL AUTO_INCREMENT,`user_id` INT(11) NOT NULL,`codigo` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',`nome` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',`descricao` VARCHAR(120) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',PRIMARY KEY (`id`) USING BTREE,UNIQUE INDEX `codigo` (`codigo`) USING BTREE) COLLATE='utf8_bin' ENGINE=InnoDB ROW_FORMAT=COMPACT;"); // Emblemas
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_vips` (`id` INT(11) NOT NULL AUTO_INCREMENT,`user_id` INT(11) NULL DEFAULT NULL,`user_rank_antigo` INT(11) NULL DEFAULT NULL,`expira_time` INT(11) NULL DEFAULT NULL,`adicionado_time` INT(11) NULL DEFAULT NULL,`emblema` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',`observacoes` VARCHAR(500) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',`desativar_automaticamente` ENUM('0','1') NULL DEFAULT '1' COLLATE 'latin1_swedish_ci',PRIMARY KEY (`id`) USING BTREE,UNIQUE INDEX `user_id` (`user_id`) USING BTREE) COLLATE='latin1_swedish_ci' ENGINE=InnoDB AUTO_INCREMENT=1;"); // VIP's
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_vips_logs` (`id` INT(11) NOT NULL AUTO_INCREMENT,`staff_id` INT(11) NULL DEFAULT NULL,`user_id` INT(11) NULL DEFAULT NULL,`log` VARCHAR(1000) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',`time` INT(11) NULL DEFAULT NULL,PRIMARY KEY (`id`) USING BTREE) COLLATE='latin1_swedish_ci'ENGINE=InnoDB AUTO_INCREMENT=15;"); // VIP's Log's
        PHBDatabase::criarTabela("CREATE TABLE IF NOT EXISTS `phb_hk_promocoes` (`news_id` INT(11) NULL DEFAULT NULL,`timestamp_inicio` INT(11) NULL DEFAULT NULL,`timestamp_fim` INT(11) NULL DEFAULT NULL,UNIQUE INDEX `news_id` (`news_id`) USING BTREE) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;"); /// Promoções

        /* Insere dados de permissões */

        /// Notícias
        PHBDatabase::addPermissao("noticia_escrever", 0);
        PHBDatabase::addPermissao("noticia_historico", 0);
        PHBDatabase::addPermissao("noticia_apagar", 0);
        PHBDatabase::addPermissao("noticia_editar", 0);

        /// Log's
        PHBDatabase::addPermissao("logs_conversas", 0);
        PHBDatabase::addPermissao("logs_console", 0);
        PHBDatabase::addPermissao("logs_login", 0);
        PHBDatabase::addPermissao("logs_comprasdequarto", 0);
        PHBDatabase::addPermissao("logs_pay", 0);
        PHBDatabase::addPermissao("logs_staffs", 0);
        PHBDatabase::addPermissao("logs_trocas", 0);
        PHBDatabase::addPermissao("logs_raros", 0);
        PHBDatabase::addPermissao("logs_hk", 0);
        PHBDatabase::addPermissao("logs_marketplace", 0);
        PHBDatabase::addPermissao("logs_escolhermobis", 0);
        PHBDatabase::addPermissao("logs_trocadenome", 0);
        PHBDatabase::addPermissao("logs_eventos", 0);
        PHBDatabase::addPermissao("logs_redeem", 1);

        /// Gerenciamento
        PHBDatabase::addPermissao("gerenciamento_catalogo_paginas", 0);
        PHBDatabase::addPermissao("gerenciamento_catalogo_items", 0);
        PHBDatabase::addPermissao("gerenciamento_catalogo_paginas_apagar", 0);
        PHBDatabase::addPermissao("gerenciamento_hospedarimagem", 0);
        PHBDatabase::addPermissao("gerenciamento_catalogo_paginas_editar", 0);
        PHBDatabase::addPermissao("gerenciamento_catalogo_item_editar", 0);
        PHBDatabase::addPermissao("gerenciamento_catalogo_item_apagar", 0);
        PHBDatabase::addPermissao("gerenciamento_enviaralertastaff", 0);
        PHBDatabase::addPermissao("gerenciamento_textos", 0);
        PHBDatabase::addPermissao("gerenciamento_textos_editar", 0);
        PHBDatabase::addPermissao("gerenciamento_addmobis", 0);
        PHBDatabase::addPermissao("gerenciamento_emulador", 0);
        PHBDatabase::addPermissao("gerenciamento_arquivos", 0);
        PHBDatabase::addPermissao("gerenciamento_bancodedados", 0);
        PHBDatabase::addPermissao("gerenciamento_permissoes", 0);
        PHBDatabase::addPermissao("gerenciamento_permissoes_editar", 0);
        PHBDatabase::addPermissao("gerenciamento_permissoespainel", 0);
        PHBDatabase::addPermissao("gerenciamento_permissoespainel_editar", 0);
        PHBDatabase::addPermissao("gerenciamento_configuracoespainel", 0);
        PHBDatabase::addPermissao("gerenciamento_configuracoesemulador", 0);

        /// Emblema
        PHBDatabase::addPermissao("emblema_hospedar", 0);
        PHBDatabase::addPermissao("emblema_hospedados", 0);
        PHBDatabase::addPermissao("emblema_editar", 0);
        PHBDatabase::addPermissao("emblema_apagar", 0);

        /// Filtro
        PHBDatabase::addPermissao("mod_filtro", 0);
        PHBDatabase::addPermissao("mod_filtro_adicionar", 0);
        PHBDatabase::addPermissao("mod_filtro_apagar", 0);
        PHBDatabase::addPermissao("mod_bans", 0);
        PHBDatabase::addPermissao("mod_bans_apagar", 0);
        PHBDatabase::addPermissao("mod_ip", 0);
        PHBDatabase::addPermissao("mod_contasip", 0);

        /// Usuários
        PHBDatabase::addPermissao("usuarios_buscar", 0);
        PHBDatabase::addPermissao("usuarios_emblemas", 0);
        PHBDatabase::addPermissao("usuarios_emblemas_apagar", 0);
        PHBDatabase::addPermissao("usuarios_quartos", 0);
        PHBDatabase::addPermissao("usuarios_editar", 0);
        PHBDatabase::addPermissao("usuarios_informacoes", 0);
        PHBDatabase::addPermissao("usuarios_editar_rank", 0);
        PHBDatabase::addPermissao("usuarios_editar_creditos", 0);
        PHBDatabase::addPermissao("usuarios_online", 0);
        PHBDatabase::addPermissao("usuarios_staffs", 0);
        PHBDatabase::addPermissao("usuarios_inventario", 0);
        PHBDatabase::addPermissao("usuarios_items", 0);
        PHBDatabase::addPermissao("usuarios_grupos", 0);
        PHBDatabase::addPermissao("usuarios_ltds", 0);
        PHBDatabase::addPermissao("usuarios_fakes", 0);
        PHBDatabase::addPermissao("usuarios_darcreditos", 0);

        /// Raros
        PHBDatabase::addPermissao("raros_adicionar", 0);
        PHBDatabase::addPermissao("raros_adicionados", 0);
        PHBDatabase::addPermissao("raros_adicionados_apagar", 0);
        PHBDatabase::addPermissao("raros_editar", 0);
        PHBDatabase::addPermissao("raros_apagar", 0);

        /// Relatórios
        PHBDatabase::addPermissao("relatorio_onlines", 0);
        PHBDatabase::addPermissao("relatorio_eventos", 0);

        /// VIP's
        PHBDatabase::addPermissao("vips_adicionar", 0);
        PHBDatabase::addPermissao("vips_usuarios", 0);
        PHBDatabase::addPermissao("vips_usuarios_editar", 0);
        PHBDatabase::addPermissao("vips_logs", 0);
        PHBDatabase::addPermissao("vips_usuarios_remover", 0);


       /* Insere dados de configurações */
       PHBDatabase::addConfig("AcessarContaUsuarioUrl", "https://hablun.online/conta-usuario/%id%", "Link que você acessa para iniciar uma sessão com a conta do usuário, variável %id%.");
       PHBDatabase::addConfig("ArcturusExePath", "C:\Users\Administrator\Desktop\EMULADOR", "Local onde o Arcturus.exe está localizado, usado para ligar o emulador, deixe em branco para desativar.");
       PHBDatabase::addConfig("ArcturusIP", "127.0.0.1", "IP do emulador");
       PHBDatabase::addConfig("ArcturusRCONPort", "3001", "Porta rcon do emulador");
       PHBDatabase::addConfig("AvatarImageURL", "https://avatar.grafx.me/habbo-imaging/avatarimage", "Link de imagens de avatar..");
       PHBDatabase::addConfig("CaminhoUploadEmblema", "C:\inetpub\wwwroot\Cosmic\public\ms-swf\c_images\album1584\\", "Caminho onde o emblema hospedado será movido. ATENÇÃO: COM SLASH (/) NO FINAL.");
       PHBDatabase::addConfig("CaminhoUploadImagem", "C:\inetpub\wwwroot\Cosmic\public\ms-swf\c_images\mpu\\", "Caminho onde a imagem hospedada será movida. ATENÇÃO: COM SLASH (/) NO FINAL.");
       PHBDatabase::addConfig("CatalogPageLayouts", "'default_3x3','club_buy','club_gift','frontpage','spaces','recycler','recycler_info','recycler_prizes','trophies','plasto','marketplace','marketplace_own_items','spaces_new','soundmachine','guilds','guild_furni','info_duckets','info_rentables','info_pets','roomads','single_bundle','sold_ltd_items','badge_display','bots','pets','pets2','pets3','productpage1','room_bundle','recent_purchases','default_3x3_color_grouping','guild_forum','vip_buy','info_loyalty','loyalty_vip_buy','collectibles','petcustomization','frontpage_featured'", "Lista de layouts do catálogo separado por vírgula.");
       PHBDatabase::addConfig("ClasseComandoEventAlert", "EventAlertSocketCommand", "Classe do comando de event alert, usado para calcular a quantidade no painel.");
       PHBDatabase::addConfig("DiscordWebHookHKLog", "https://discord.com/api/webhooks/1025516487848509450/_dr_F4_Gry_zhNGVcsezcjgKWO1IQn8WYrwVj_5AP2oFrNZV-YtwhZuvbuo8QMZm2e4Q", "Link do Discord Webhook do canal de logs do painel, deixe em branco para desativado.");
       PHBDatabase::addConfig("DiscordWebHookNews", "https://discord.com/api/webhooks/1025516592701902868/fPY7n26D2_IoaiN36QaAxfkhKlPlhTUxaIFtmZepIzZAVlSW44XPn1K9AuK5hvG5aRkc", "Link do Discord Webhook do canal de notificação de novas notícias, deixe em branco para desativado.");
       PHBDatabase::addConfig("Favicon", "https://painel.hablun.online/assets/images/favicon.png", "Link para o favicon do painel.");
       PHBDatabase::addConfig("FTP_IP", "localhost", "IP do servidor FTP.");
       PHBDatabase::addConfig("FTP_PASS", "123", "Senha do servidor FTP.");
       PHBDatabase::addConfig("FTP_USER", "phb", "Usuário do servidor FTP.");
       PHBDatabase::addConfig("GroupBadgeURL", "https://swf.grafx.me/habbo-imaging/badge/%imagerdata%", "Link para emblema do grupo, variável %imagerdata%.");
       PHBDatabase::addConfig("HotelNewsUrl", "https://habbo.com.br/news/%id%", "Link da página de notícias do seu hotel, variável: %id%.");
       PHBDatabase::addConfig("HotelRoomURL", "https://habbo.com.br/room/%id%", "Link de redirecionamento para quarto, variável: %id%.");
       PHBDatabase::addConfig("LinkEmblema", "https://hablun.online/ms-swf/c_images/album1584/%codigo%.gif", "Link de onde o painel puxará os emblemas, variável %codigo%.");
       PHBDatabase::addConfig("LinkHotel", "https://hablun.online", "Link do seu hotel, com https://, exemplo: https://habbo.com.br.");
       PHBDatabase::addConfig("LinkImagemHospedada", "https://localhost/images/%nome%", "URL da imagem que o painel hospeda.");
       PHBDatabase::addConfig("MetodoTransferenciaArquivos", "muf", "Método que o painel irá usar para mover os arquivos de emblemas, mobis e imagens. ftp ou muf (move_uploaded_files).");
       PHBDatabase::addConfig("NomeCurrency.-1", "Créditos", "Nome da Moeda.");
       PHBDatabase::addConfig("NomeCurrency.0", "Duckets", "Nome da Moeda.");
       PHBDatabase::addConfig("NomeCurrency.104", "Nuvens", "Nome da Moeda.");
       PHBDatabase::addConfig("NomeCurrency.5", "Diamantes", "Nome da Moeda.");
       PHBDatabase::addConfig("NomeHotel", "Hablun", "Nome do seu hotel, exemplo: Habbo.");
       PHBDatabase::addConfig("PasswordHash", "phphash", "Método de hash da senha para o login do painel, md5 ou phphash.");
       PHBDatabase::addConfig("PathIconesCatalogo", "https://hablun.online/ms-swf/c_images/catalogue/icon_%id%.png", "Link para icone do catalogo, variável %id%.");
       PHBDatabase::addConfig("PathIconesMobis", "https://hablun.online/ms-swf/dcr/hof_furni/%classname%_icon.png", "Link para o caminho dos ícones das mobílias, variável %classname%.");
       PHBDatabase::addConfig("RanksVIP", "1,2,3,4", "Ranks que poderão ser dados ao adicionar um usuário vip, separados por vírgula.");
       PHBDatabase::addConfig("SeasonalID", "104", "ID do seasonal usado em seu hotel.");
       PHBDatabase::addConfig("SwfFurnidataUrl", "https://hablun.online/ms-swf/gamedata/furnidata.xml", "Link da furnidata.xml de seu hotel, ela é usada na função add mobis.");
       PHBDatabase::addConfig("SWFGamedataFurnidataPath", "C:\inetpub\wwwroot\Cosmic\public\ms-swf\gamedata\furnidata.xml", "Caminho da furnidata.xml, ela é usada na função add mobis.");
       PHBDatabase::addConfig("SWFMobisIconPath", "C:\inetpub\wwwroot\Cosmic\public\ms-swf\dcr\hof_furni\icons\\", "Local onde o painel colocará ícone de mobis baixados. COM SLASH /");     
       PHBDatabase::addConfig("SWFMobisPath", "C:\inetpub\wwwroot\Cosmic\public\ms-swf\dcr\hof_furni", "Local onde o painel colocará mobis baixados.  COM SLASH /");
       PHBDatabase::addConfig("TemaHK", "dark", "Tema do painel, escolha entre light e dark.");
       PHBDatabase::addConfig("WebsiteKeywords", "habbo painel, habbo admin panel, phb hk, phb plugin", "Keywords para o head da página.");
       PHBDatabase::addConfig("IdiomaHK", "pt", "Nome do arquivo de linguagem sem .php");
       PHBDatabase::addConfig("TipoLogin", "email", "Login por email ou usuario.");
  
    }

    private static function addPermissao($nome, $padrao)
    {
        global $db;
        $insere = $db->prepare("ALTER TABLE `phb_hk_permissoes` ADD COLUMN IF NOT EXISTS " . $nome . " ENUM('0','1') NULL DEFAULT '" . $padrao . "'");;
        @$insere->execute();
    }

    private static function addConfig($chave, $valor, $descricao)
    {
        global $db;
        $insere = $db->prepare("INSERT IGNORE INTO `phb_hk_settings` (`chave`, `valor`, `descricao`) VALUES (:chave, :valor, :descricao);");;
        $insere->bindParam(":chave", $chave);
        $insere->bindParam(":valor", $valor);
        $insere->bindParam(":descricao", $descricao);
        @$insere->execute();
    }

    private static function criarTabela($sql)
    {
        global $db;
        $insere = $db->prepare($sql);
        @$insere->execute();
    }
}
