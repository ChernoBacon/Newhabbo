<?php

if (!defined("PHB_HK")) die("Arquivo bloqueado.");

class PHBZip extends ZipArchive
{
    protected $dir;
    protected $archive;
    protected $pathsArray;


    public function __construct($dir, $name)
    {
        $this->dir = $dir;
        $this->archive = $name;
        $this->open($this->archive, PHBZip::CREATE);
        $this->myScanDir($this->dir);
        $this->addZip();
        ob_clean();
        ob_end_flush();
        $this->getZip();
    }

    protected function myScanDir($dir)
    {
        $files = scandir($dir);
        unset($files[0], $files[1]);
        foreach ($files as $file) {
            if (is_dir($dir . '/' . $file)) {
                $this->myScanDir($dir . '/' . $file);
            } else {
                $this->pathsArray[] = array('oldpath' => $dir . '/' . $file, 'newpath' => (($this->dir == $dir) ? $file : str_replace($this->dir . '/', '', $dir) . '/' . $file));
            }
        }
    }

    protected function addZip()
    {
        foreach ($this->pathsArray as $path) {
            $this->addFile($path['oldpath'], $path['newpath']);
        }
    }

    public function getZip()
    {
        $this->close();
        if (headers_sent()) {
            echo 'HTTP header already sent';
        } else {
            if (!is_file($this->archive)) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                echo 'File not found';
            } else if (!is_readable($this->archive)) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
                echo 'File not readable';
            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($this->archive));
                header("Content-Disposition: attachment; filename=\"" . basename($this->archive) . "\"");
                header('Set-Cookie: phb=true'); 
                readfile($this->archive);
                unlink($this->archive);
                PHBTools::removerPastaseArquivosLocal($this->dir);
                exit;
            }
        }
    }
}
