<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBVIP
{

    public static function VerificarVipsVencidos()
    {

        global $db;

        $obtemVipsVencidos = $db->prepare("SELECT * FROM phb_hk_vips WHERE expira_time <= :t AND desativar_automaticamente = '1';");
        $obtemVipsVencidos->bindValue(':t', time());
        $obtemVipsVencidos->execute();
        $obtemVipsVencidos->execute();
        while ($vipVencido = $obtemVipsVencidos->fetch()) {
            PHBVIP::removerVip($vipVencido['user_id'], true);
        }
    }

    public static function removerVip($user_id, $automatico = false)
    {
        global $_SESSION;
        global $db;
        global $lingua;

        $obtemVip = $db->prepare("SELECT phb_hk_vips.*, users.username FROM phb_hk_vips,users WHERE users.id = phb_hk_vips.user_id AND phb_hk_vips.user_id = :id");
        $obtemVip->bindParam(':id', $user_id);
        $obtemVip->execute();
        if ($obtemVip->rowCount() == 1) {

            $vip = $obtemVip->fetch();

            /// Bota o Rank antigo
            $editarUsuarioRank = $db->prepare("UPDATE `users` SET `rank`= :rank WHERE `id`= :id");
            $editarUsuarioRank->bindValue(':rank', $vip['user_rank_antigo']);
            $editarUsuarioRank->bindValue(':id', $vip['user_id']);
            if (!$editarUsuarioRank->execute())
                return $lingua['removervip-errorank'];

            @PHBSocket::SendMessage(json_encode(['key' => 'setrank', 'data' => ['user_id' => $vip['user_id'], 'rank' => $vip['user_rank_antigo']]]));

            /// Remove o Emblema
            $removerEmblema = $db->prepare("DELETE FROM users_badges WHERE user_id = :user AND badge_code = :badge");
            $removerEmblema->bindValue(':user', $vip['user_id']);
            $removerEmblema->bindValue(':badge', $vip['emblema']);
            if (!$removerEmblema->execute())
                return $lingua['removervip-erroemblema'];

            /// Remove da tabela de vip
            $removerTabela = $db->prepare("DELETE FROM phb_hk_vips WHERE user_id = :user");
            $removerTabela->bindValue(':user', $vip['user_id']);
            if (!$removerTabela->execute())
                return $lingua['removervip-errotabela'];

            if (!$automatico) {
                PHBLogger::AddLog($lingua['removervip-log'] . " " . $vip['username'] . " (" . $vip['user_id'] . ")");
                PHBLogger::AddVipLog(str_replace("%usuario%",  $vip['username'] . " (" . $vip['user_id'] . ")", str_replace("%staff%", PHBTools::GetUsernameById($_SESSION['id_painel']), $lingua['removervip-logvip'])), $vip['user_id']);
            } else {
                PHBLogger::AddVipLog(str_replace("%usuario%",  $vip['username'] . " (" . $vip['user_id'] . ")", $lingua['removervip-logvip-automatico']), $vip['user_id']);
            }

            return "ok";
        } else {
            return $lingua['removervip-nao'];
        }
    }
}
