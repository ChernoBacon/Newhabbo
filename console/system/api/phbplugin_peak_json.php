<?php

define('PHB_HK', 1);

include $_SERVER['DOCUMENT_ROOT'].'/system/config.php';

/// Cria uma conexão com o banco de dados
try {
	$db = new PDO('mysql:host='.$database['host'].':'.$database['porta'].';dbname='.$database['db'].';charset=utf8', $database['usuario'], $database['senha']);
} catch (PDOException $e){
    die("Erro ao obter uma conexão com o servidor MySQL<br>".$e);
}

/// Obtém json dos dados
$getJson = $db->prepare("SELECT JSON_OBJECT('phbplugin_peak', JSON_ARRAYAGG(JSON_OBJECT('timestamp', timestamp, 'onlines', onlines) ORDER BY timestamp DESC LIMIT 500)) AS json from graficos_phbplugin;");
if($getJson->execute()){
    die($getJson->fetch()['json']);
} else {
    die("{'error': 'MySQL Fetch.'}");
}

?>