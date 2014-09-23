<?php

namespace SimpleBackupPHP\Adapters;

/**
 * Description of ControleConfig
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use SimpleBackupPHP\Adapters\Application;
use SimpleBackupPHP\Adapters\Adapter\Adapter;
use SimpleBackupPHP\Adapters\Adapter\Type\FTP;
use SimpleBackupPHP\Adapters\Adapter\Type\NFS;
use SimpleBackupPHP\Adapters\Adapter\Type\Mysql;

class WrapConfig {
    private $arrayConfig;
    
    
    public function capture() {
        $this->arrayConfig = $this->getArrayConfig();        
        return $this->montaApplication($this->arrayConfig);
    }
    
    public function getArrayConfig() {
        return require CAMINHO_APLICACAO . DIRECTORY_SEPARATOR . 'config.application.php';
    }
    
    private function montaApplication($arrayApplication) {
        if ($arrayApplication == NULL) {
            throw new \Exception("O arquivo de configuração não possui uma aplicação válida");
        }
        
        $application = new Application();
        
        $application->setEmailAdministrador($this->retornaEmailAdministrador($arrayApplication['config']));
        $application->setDayExcludeBackup($this->retornExcludeOldBackup($arrayApplication['config']));
        $application->setUnitDiskDefault($this->retornUnitDiskDefault($arrayApplication['config']));
        
        foreach ($arrayApplication['adapters'] as $nameAdaptador => $elementArrayApplication) {
            $application->addAdapter($this->montaAdaptador($elementArrayApplication, $nameAdaptador));
        }                              
        
        return $application;
    }
    
    private function montaAdaptador($arrayAdaptador, $nameAdaptador) {        
        if ($arrayAdaptador == NULL) {
            throw new \Exception("O arquivo de configuracão não possui um adaptador válida.");
        }
        
        return new Adapter( $this->fabricaConfigurador($arrayAdaptador['CONFIG']), $arrayAdaptador['DIRETORIOS'], 
                            $arrayAdaptador['PATH_SAVE_BACKUP_DIRECTORY'], $nameAdaptador);
    }
    
    private function fabricaConfigurador($configuracaoAdaptador) {
        switch (strtoupper($configuracaoAdaptador['type'])) {
            case "FTP":
                $configurador = new FTP();
                break;
            case "NFS":
                $configurador = new NFS();
                break;
            case "MYSQL":
                $configurador = new Mysql();
                break;
            default:
                throw new \Exception("Não foi possível identificar o adaptador");
                break;
        }
        
        $configurador->configuraObjeto($configuracaoAdaptador['config']);
        return $configurador;
    }
    
    private function retornaEmailAdministrador(array $arrayApplicationConfig) {
        return $arrayApplicationConfig['email_administrador'];
    }
    
    private function retornExcludeOldBackup(array $arrayApplicationConfig) {
        return $arrayApplicationConfig['exclude_old_backup'];
    }
    
    private function retornUnitDiskDefault(array $arrayApplicationConfig) {
        return $arrayApplicationConfig['unit_disk_default'];
    }
}
