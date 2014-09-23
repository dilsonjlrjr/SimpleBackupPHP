<?php

namespace InfraEstrutura\NFS;

/**
 * Description of NFS
 *
 * @author nme
 * 
 * 
 * df -H | grep zeus | cut -f 1 -d" "
 * sudo mount -t nfs zeus:/var/www/unasus /media/nme/cdrom/
 */

use InfraEstrutura\File\Directory as Diretorio;
use InfraEstrutura\System\OS as SistemaOperacional;

class NFS {
    private $nfs_server;
    private $nfs_dir_share;
    private $nfs_dir_point_mount;
    
    function __construct($nfs_server, $nfs_dir_share, $nfs_dir_point_mount) {
        $this->nfs_server = $nfs_server;
        $this->nfs_dir_share = $nfs_dir_share;
        $this->nfs_dir_point_mount = $nfs_dir_point_mount;
    }
    
    private function isConnect() {
        $this->avaliaAtributosdeConexao();
        
        $conexao = shell_exec('sudo df -H | grep ' . $this->getServerNFS() . ' | cut -f 1 -d" "');        
        if (trim($conexao) != $this->getServerNFS()) {
            return FALSE;
        }
        
        return TRUE;
    }

    public function connect() {
        $this->avaliaAtributosdeConexao();
    
        $conexao = shell_exec("sudo mount -t nfs " . $this->getServerNFS() . " " . $this->getPointMount());
        
        if (!$this->isConnect()) {
            throw new \Exception("Ocorreu um erro ao conectar com o servidor NFS. O servidor não foi encontrado ou já existe conexão aberta para o ponto de montagem.");
        }
        
        return TRUE;
    }
    
    public function disconnect() {
        $this->avaliaAtributosdeConexao();
        
        if (!$this->isConnect()) {
            throw new \Exception("Não existe conexão aberta com o servidor " . $this->nfs_server);
        }
        
        $conexao = shell_exec("sudo umount " . $this->getPointMount());
        
        if (trim($conexao) != "")
            throw new \Exception("Ocorreu um erro ao desconectar com o servidor NFS.");
        
        return TRUE;
    }
    
    public function copiaDiretorio($origem, $destino) {
        $directory = new Diretorio();
        return $directory->copyDirectory($origem, $destino);
    }
    
    private function checkOS() {
        if (SistemaOperacional::getSistemaOperacional() != SistemaOperacional::__Linux) {
            throw new \Exception("Funcinalidade disponível somente pra Sistema Operacional Linux.");
        }
        
        return TRUE;        
    }
    
    private function getServerNFS() {
        return $this->nfs_server . ":" . $this->nfs_dir_share;
    }
    
    private function getPointMount() { 
        return $this->nfs_dir_point_mount;
    }
    
    private function avaliaAtributosdeConexao() {
        if ($this->nfs_server == NULL) {
            throw new \Exception("Parâmetro Server não preenchido.");
        }
        
        if ($this->nfs_dir_share == NULL) {
            throw new \Exception("Parâmetro Diretório Compartilhamento não preenchido.");
        }
        
        if ($this->nfs_dir_point_mount == NULL) {
            throw new \Exception("Parâmetro Ponto de Montagem não preenchido.");
        }
        
        if (!is_dir($this->nfs_dir_point_mount)) {
            throw new \Exception("O ponto de montagem não é um diretório.");
        }
        
        $this->checkOS();
    }
    
}
