<?php

namespace SimpleBackupPHP\Adapters\Adapter\Type;

/**
 * Description of NFS
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use SimpleBackupPHP\Adapters\Adapter\Type\Plugin;
use InfraEstrutura\Log;

use InfraEstrutura\NFS\NFS as NFSServer;
use InfraEstrutura\Zip\Zip;

class NFS extends Plugin {
    private $nfs_server;
    private $nfs_dir_share;
    private $nfs_dir_point_mount;
    
    function __construct($nfs_server = NULL, $nfs_dir_share = NULL, $nfs_dir_point_mount = NULL) {
        $this->nfs_server = $nfs_server;
        $this->nfs_dir_share = $nfs_dir_share;
        $this->nfs_dir_point_mount = $nfs_dir_point_mount;
    }
    
    public function getNfs_server() {
        return $this->nfs_server;
    }

    public function getNfs_dir_share() {
        return $this->nfs_dir_share;
    }

    public function getNfs_dir_point_mount() {
        return $this->nfs_dir_point_mount;
    }

    public function setNfs_server($nfs_server) {
        $this->nfs_server = $nfs_server;
    }

    public function setNfs_dir_share($nfs_dir_share) {
        $this->nfs_dir_share = $nfs_dir_share;
    }

    public function setNfs_dir_point_mount($nfs_dir_point_mount) {
        $this->nfs_dir_point_mount = $nfs_dir_point_mount;
    }
        
    public function backup($diretorioBackup, $destinoDownloadArquivo, $pathArquivoCompactado, $nomeArquivoCompactado, $pathLog) {
        $serverNFS = new NFSServer($this->nfs_server, $this->nfs_dir_share, $this->nfs_dir_point_mount);
        try {
            chdir($destinoDownloadArquivo);            
            $serverNFS->connect();
            Log::info($pathLog, "Iniciando download do diretorio via o servidor NFS ($this->nfs_dir_point_mount" . DIRECTORY_SEPARATOR . $diretorioBackup . ").");
            $serverNFS->copiaDiretorio($this->nfs_dir_point_mount . DIRECTORY_SEPARATOR . $diretorioBackup, $destinoDownloadArquivo . DIRECTORY_SEPARATOR . $diretorioBackup);
            $serverNFS->disconnect();            
            Log::info($pathLog, "Download efetuado com sucesso ([$this->nfs_server] $this->nfs_dir_point_mount" . DIRECTORY_SEPARATOR . $diretorioBackup . ").");
            
            $zip = new Zip;
            Log::info($pathLog, "Iniciando compactacao do diretorio $diretorioBackup");
            $zip->compactarDiretorio($nomeArquivoCompactado, $diretorioBackup, $pathArquivoCompactado);
            Log::info($pathLog, "Fim compactacao do diretorio $diretorioBackup");
            
        } catch (\Exception $ex) {
            $serverNFS->disconnect();
            Log::err($pathLog, "Ocorreu um erro ao efetuar o download do diretorio $diretorioBackup. Causa:" . $ex->getMessage());
            throw new \Exception("Ocorreu um erro ao efetuar o download do diretorio $diretorioBackup. Causa:" . $ex->getMessage());
        }        
        return TRUE;        
    }

    public function configuraObjeto(array $arrayConfiguration) {
        $this->nfs_server = $arrayConfiguration["server"];
        $this->nfs_dir_share = $arrayConfiguration["dir_share"];
        $this->nfs_dir_point_mount = $arrayConfiguration["dir_point_mount"];
        
        return TRUE;
    }

}
