<?php

namespace SimpleBackupPHP\Application;

/**
 * Description of Directory
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use InfraEstrutura\Utils\DateUtils;
use InfraEstrutura\Log;
use InfraEstrutura\File\Directory as Diretorio;

class UnidadeBackup {
    private $pathUnidadeBackup;
    const MODE_ACCESS = 0777;
     
    public function crieUnidadeBackup() { 
        if (defined(CAMINHO_DIR_TEMPORARIO)) {
            throw new \Exception("Constante CAMINHO_DIR_TEMPORARIO não definida.");
        }

        try {
            $hoje = DateUtils::hoje("YmdHms");
            chdir(CAMINHO_DIR_TEMPORARIO);
            mkdir($hoje, self::MODE_ACCESS);
            $this->pathUnidadeBackup = CAMINHO_DIR_TEMPORARIO . DIRECTORY_SEPARATOR . $hoje;

            chdir($this->pathUnidadeBackup);
            mkdir("log", self::MODE_ACCESS);
            mkdir("tmp", self::MODE_ACCESS);
            Log::info($this->pathUnidadeBackup . DIRECTORY_SEPARATOR . "log", "Criação da unidade de backup.");
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao criar Unidade Backup, causa: " . $ex->getMessage());
        }
        return TRUE;
    }
    
    public function copiaUnidadeBackup($caminhoOrigem, $caminhoDestino) {
        $directory = new Diretorio();
        return $directory->copyDirectory($caminhoOrigem, $caminhoDestino);
    }

    public function excluiTemporarioUnidadeBackup() {
        if (!is_dir($this->pathUnidadeBackup)) {
            throw new \Exception("Unidade de Backup não encontrada.");
        }

        $this->excluiDiretorio($this->pathUnidadeBackup . DIRECTORY_SEPARATOR . "tmp");
    }
    
    public function excluiUnidadeBackup() {
        if (!is_dir($this->pathUnidadeBackup)) {
            throw new \Exception("Unidade de Backup não encontrada.");
        }

        $this->excluiDiretorio($this->pathUnidadeBackup);
    }
    
    private function excluiDiretorio($diretorio) {
        $directory = new Diretorio();        
        $directory->excludeDirectory($diretorio);
    }
    
    public function getPathUnidadeBackup() {
        return $this->pathUnidadeBackup;
    }
    
    public function limpaDiretorioTemporario() {
        $this->excluiDiretorio(CAMINHO_DIR_TEMPORARIO);
        chdir(CAMINHO_APLICACAO);
        mkdir("tmp");
    }
}
