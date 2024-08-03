<?php

session_set_cookie_params('604800', '/', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')? true : false, true);
session_start();

define('PHB_HK', 1);

require_once $_SERVER['DOCUMENT_ROOT'].'/system/config.php';

/// Cria uma conexão com o banco de dados
try {
	$db = new PDO('mysql:host='.$database['host'].':'.$database['porta'].';dbname='.$database['db'].';charset=utf8', $database['usuario'], $database['senha']);
} catch (PDOException $e){
    die("Erro ao obter uma conexão com o servidor MySQL<br>".$e);
}

/// Obtém configurações extras do banco de dados na variável config
$getConfigs = $db->prepare("SELECT * FROM phb_hk_settings");
$getConfigs->execute();
while($dbConfig = $getConfigs->fetch()){
    $config[$dbConfig['chave']] = $dbConfig['valor'];
}

/// Obtem dados do usuário na variável myUser
if(isset($_SESSION['id_painel'])){
    $getUser = $db->prepare("SELECT users.*, permissions.rank_name FROM users,permissions WHERE users.id = :id AND permissions.id = users.rank");
    $getUser->bindValue(":id", $_SESSION['id_painel']);
    $getUser->execute();
    $myUser = $getUser->fetch();
}

/// Carrega arquivo de linguagem
if($config['IdiomaHK'] == null || $config['IdiomaHK'] == "") $config['IdiomaHK'] = "pt";
require_once $_SERVER['DOCUMENT_ROOT'].'/system/linguas/'.$config['IdiomaHK'].'.php';

/// Carrega todas as classes
foreach (glob($_SERVER['DOCUMENT_ROOT']."/system/classes/*.php") as $arquivo) {
    require_once $_SERVER['DOCUMENT_ROOT']."/system/classes/".basename($arquivo);
}

/// Database
PHBDatabase::checkDB();

/// Verificação de VIP's vencidos
PHBVIP::VerificarVipsVencidos();
