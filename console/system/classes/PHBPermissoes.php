<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBPermissoes
{
    public static function Check($permissao)
    {
        global $db;
        global $_SESSION;
        $stmt = $db->prepare("SELECT phb_hk_permissoes." . $permissao . " FROM phb_hk_permissoes, users WHERE phb_hk_permissoes.id = users.rank AND users.id = :userid");
        $stmt->bindValue(':userid', $_SESSION['id_painel']);
        $stmt->execute();
        $result = $stmt->fetch()[$permissao];
        if ($result == '1')
            return true;
        else
            return false;
    }

    public static function Categoria($nome)
    {
        global $db;
        global $database;
        global $_SESSION;
        $stmt = $db->prepare("SELECT COLUMN_NAME FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='".$database['db']."' AND TABLE_NAME='phb_hk_permissoes' AND COLUMN_NAME LIKE \"".$nome."%\" ORDER BY ORDINAL_POSITION ;");
        $stmt->bindValue(':userid', $_SESSION['id_painel']);
        $stmt->execute();
        while($result = $stmt->fetch()){
            if(PHBPermissoes::Check($result['COLUMN_NAME']))
                return true;
        }
        return false;
    }
}
