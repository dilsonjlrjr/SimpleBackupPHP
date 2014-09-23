<?php

namespace SimpleBackupPHP\Adapters\Adapter\Type;

/**
 * Description of FTP
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use SimpleBackupPHP\Adapters\Adapter\Type\Plugin;
use InfraEstrutura\Log;

use InfraEstrutura\FTP\FTP as ServerFTP;
use InfraEstrutura\Zip\Zip;

class FTP extends Plugin {
    private $server;
    private $username;
    private $password;
    private $port;
    private $timeout;
    
    function __construct($server = NULL, $username = NULL, $password = NULL, $port = NULL, $timeout = NULL) {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->timeout = $timeout;
    }
    
    public function getServer() {
        return $this->server;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPort() {
        return $this->port;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function setServer($server) {
        $this->server = $server;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function configuraObjeto(array $arrayConfiguration) {
        $this->server = $arrayConfiguration['server'];
        $this->username = $arrayConfiguration['username'];
        $this->password = $arrayConfiguration['password'];
        $this->port = $arrayConfiguration['port'];
        $this->timeout = $arrayConfiguration['timeout'];
        
        return TRUE;
    }
    
    public function backup($diretorioBackup, $destinoDownloadArquivo, $pathArquivoCompactado, $nomeArquivoCompactado, $pathLog) {
        try {
            chdir($destinoDownloadArquivo);
            $serverFtp = new ServerFTP($this->server, $this->port, $this->username, $this->password, $this->timeout, $pathLog);
            $serverFtp->connect();
            Log::info($pathLog, "Iniciando download do diretorio via o servidor FTP ($this->server" . DIRECTORY_SEPARATOR . $diretorioBackup . ").");
            $serverFtp->get($diretorioBackup);
            Log::info($pathLog, "Download efetuado com sucesso ($this->server" . DIRECTORY_SEPARATOR . $diretorioBackup . ").");
            
            $zip = new Zip;
            Log::info($pathLog, "Iniciando compactacao do diretorio $diretorioBackup");
            $zip->compactarDiretorio($nomeArquivoCompactado, $diretorioBackup, $pathArquivoCompactado);
            Log::info($pathLog, "Fim compactacao do diretorio $diretorioBackup");
            
        } catch (\Exception $ex) {
            Log::err($pathLog, "Ocorreu um erro ao efetuar o download do diretorio $diretorioBackup. Causa:" . $ex->getMessage());
            throw new \Exception("Ocorreu um erro ao efetuar o download do diretorio $diretorioBackup. Causa:" . $ex->getMessage());
        }        
        return TRUE;        
    }
}
