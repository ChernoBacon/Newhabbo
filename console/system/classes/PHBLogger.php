<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBLogger
{
    private static function processDiscord($dataPacket, $link_webhook)
    {
        global $lingua;
        $dataString = json_encode($dataPacket);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link_webhook);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        $output = curl_exec($curl);
        $output = json_decode($output, true);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            echo '<div class="alert alert-solid-danger" role="alert">
				<button aria-label="Close" class="close" data-dismiss="alert" type="button">
				<span aria-hidden="true">&times;</span></button>
				<strong>' . $lingua['generico-erro'] . '</strong> ' . $lingua['discord-webhook-error'] . ' ' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . '</div>';
        }
        curl_close($curl);
    }

    public static function NotificarNoticia($idnoticia)
    {
        global $db;
        global $config;
        global $lingua;

        if ($config['DiscordWebHookNews'] != "") {
            $getNoticia = $db->prepare("SELECT website_news.slug, website_news.id, website_news.title, website_news.header, users.username FROM website_news, users WHERE users.id = website_news.author AND website_news.id = :id");
            $getNoticia->bindParam(':id', $idnoticia);
            $getNoticia->execute();
            $noticia = $getNoticia->fetch();
            $titulo = $noticia["title"];
            $dataPacket = array(
                'content' => str_replace("%link%", $lingua['discord-webhook-news-content'], str_replace("%id%", $idnoticia, $config['HotelNewsUrl'])),
                'username' => $config['NomeHotel'],
                'embeds' => array(
                    array(
                        'title' => $lingua['discord-webhook-postadapor'] . " " . $noticia['username'],
                        'url' => str_replace("%id%", $idnoticia, $config['HotelNewsUrl']),
                        'timestamp' => date(DateTime::ISO8601),
                        'description' => '',
                        'color' => '5653183',
                        'author' => array(
                            'name' => "$titulo"
                        ),
                        'image' => array('url' => $noticia["header"]),
                    )
                )
            );
            PHBLogger::processDiscord($dataPacket, $config['DiscordWebHookNews']);
        }
    }

    public static function AddLog($mensagem)
    {
        global $db;
        global $config;
        global $_SESSION;
        global $lingua;
        
        $id = $_SESSION['id_painel'];

        if ($config['DiscordWebHookHKLog'] != "") {
            $stmt = $db->prepare("SELECT username, rank FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch();
            $dataPacket = array(
                'content' => "",
                'username' => $config['NomeHotel'],
                'embeds' => array(
                    array(
                        'title' => "",
                        'url' => $config['LinkHotel'],
                        'timestamp' => date(DateTime::ISO8601),
                        'description' => '',
                        'color' => '5653183',
                        'author' => array(
                            'name' => $mensagem
                        ),
                        'fields' => array(
                            array(
                                'name' => $lingua['discord-webhook-usuario'],
                                'value' => $user['username'],
                                'inline' => true
                            ),
                            array(
                                'name' => $lingua['discord-webhook-rank'],
                                'value' => $user['rank'],
                                'inline' => true
                            ),
                            array(
                                'name' => $lingua['discord-webhook-data'],
                                'value' => date('d/m/y H:i'),
                                'inline' => true
                            )
                        )
                    )
                )
            );
            PHBLogger::processDiscord($dataPacket, $config['DiscordWebHookHKLog']);
        }
        $data = time();
        $stmt = $db->prepare("INSERT INTO `phb_hk_logs` (`usuario`, `log`, `data`) VALUES (:usuario, :log, :data);");
        $stmt->bindParam(':usuario', $id);
        $stmt->bindParam(':log', $mensagem);
        $stmt->bindParam(':data', $data);
        $stmt->execute();
    }

    public static function AddVipLog($mensagem, $usuario)
    {
        global $_SESSION;
        global $db;
        $data = time();
        $stmt = $db->prepare("INSERT INTO `phb_hk_vips_logs` (`staff_id`, `user_id`, `log`, `time`) VALUES (:staff, :user, :logs, :tim);");
        $stmt->bindParam(':staff', $_SESSION['id_painel']);
        $stmt->bindParam(':user', $usuario);
        $stmt->bindParam(':logs', $mensagem);
        $stmt->bindParam(':tim', $data);
        $stmt->execute();
    }
}
