<?php

namespace SimpleBackupPHP\Application;

use SimpleBackupPHP\Adapters\Application;
use InfraEstrutura\Utils\DateUtils;
use InfraEstrutura\File\Directory as Diretorio;
/**
 * Description of GarbageCollectorBackup
 *
 * @author dilsonrabelo.unasus@gmail.com
 */
class GarbageCollectorBackup {
    public function efetuarLimpeza(Application $application) { 
        $dataHoje = new \DateTime(date(DateUtils::__FORMAT_DATETIME_DATABASE));
        
        foreach ($application->getArrayAdapter() as $adapter) {
            $iterator = new \DirectoryIterator($adapter->getPath_save_backup());            
            
            foreach ($iterator as $diretorios) {
                //Somente diretório serão avaliados.
                if ($diretorios->isDir() && $diretorios->getFilename() != "." && $diretorios->getFilename() != "..") {
                    $dataUltimaModificacaoDiretorio = new \DateTime(date(DateUtils::__FORMAT_DATETIME_DATABASE, $diretorios->getMTime()));
                    
                    $intervalo = $dataUltimaModificacaoDiretorio->diff($dataHoje);
                    $intervaloInt = (int) $intervalo->format("%a");
                    
                    if ($intervaloInt >= (int) $application->getDayExcludeBackup()) {
                        print_r("Excluindo diretorio: " . $diretorios->getPathname());
                        $excludeDirectory = new Diretorio();
                        $excludeDirectory->excludeDirectory($diretorios->getPathname());
                        $excludeDirectory = NULL;
                    }
                }
            }
            
            $iterator = NULL;
        }
        
    }
}
