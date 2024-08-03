<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBSocket
{

    public static function SendMessage($in)
    {
        global $config;

        if (!function_exists('socket_create')) {
            echo "A função socket não está ativa em seu php";
            return false;
        }

        $service_port = $config['ArcturusRCONPort'];
        $address = $config['ArcturusIP'];
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            //return "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
            return false;
        }
        $result = @socket_connect($socket, $address, $service_port);
        if ($result === false) {
            //return "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
            return false;
        }
        if (@socket_write($socket, $in, strlen($in)) === false) {
            //return socket_strerror(socket_last_error($socket));
            return false;
        }
        $out = @socket_read($socket, 2048);
        return $out;
    }
}
