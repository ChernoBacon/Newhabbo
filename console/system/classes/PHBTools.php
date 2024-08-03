<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBTools
{

    public static function GetUsernameById($id)
    {
        global $db;
        $stmt = $db->prepare("SELECT username FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            return $stmt->fetch()['username'];
        else
            return '?';
    }

    public static function UsuariosOnlines()
    {
        global $db;
        $stmt = $db->prepare("SELECT id FROM users WHERE online = '1'");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public static function QuartosAtivos()
    {
        global $db;
        $stmt = $db->prepare("SELECT id FROM rooms WHERE users > 0");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public static function mover_arquivo_upado($arquivo, $path)
    {
        global $config;

        if ($config['MetodoTransferenciaArquivos'] == "ftp") {
            $conexao_ftp = ftp_connect($config['FTP_IP']);
            ftp_login($conexao_ftp, $config['FTP_USER'], $config['FTP_PASS']);
            ftp_pasv($conexao_ftp, true);
            return ftp_put($conexao_ftp, $path, $arquivo, FTP_BINARY);
        } else {
            return move_uploaded_file($arquivo, $path);
        }
    }

    public static function CopiarArquivo($arquivo, $path)
    {
        global $config;

        if ($config['MetodoTransferenciaArquivos'] == "ftp") {
            $conexao_ftp = ftp_connect($config['FTP_IP']);
            ftp_login($conexao_ftp, $config['FTP_USER'], $config['FTP_PASS']);
            ftp_pasv($conexao_ftp, true);
            return ftp_put($conexao_ftp, $path, $arquivo, FTP_BINARY);
        } else {
            return copy($arquivo, $path);
        }
    }

    public static function removerArquivo($path)
    {
        global $config;

        if ($config['MetodoTransferenciaArquivos'] == "ftp") {
            $conexao_ftp = ftp_connect($config['FTP_IP']);
            ftp_login($conexao_ftp, $config['FTP_USER'], $config['FTP_PASS']);
            return ftp_delete($conexao_ftp, $path);
        } else {
            return unlink($path);
        }
    }

    public static function check_pass($pass, $dbpass)
    {
        global $config;

        if ($config['PasswordHash'] == "md5") {

            if (sha1(md5(md5($pass))) == $dbpass)
                return true;
            else
                return false;
        } else {
            return password_verify($pass, $dbpass);
        }
    }

    public static function copy($source, $dest, $context)
    {
        copy($source, $dest, $context);
    }

    public static function downloadFileFromUrl($url, $filename)
    {
        copy($url, $filename);

        if (headers_sent()) {
            echo 'HTTP header already sent';
        } else {
            if (!is_file($filename)) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                echo 'File not found';
            } else if (!is_readable($filename)) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
                echo 'File not readable';
            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($filename));
                header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
                readfile($filename);
                unlink($filename);
                exit;
            }
        }
    }

    public static function removerPastaseArquivosLocal($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK);
            foreach ($files as $file) {
                PHBTools::removerPastaseArquivosLocal($file);
            }
            rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }
}
