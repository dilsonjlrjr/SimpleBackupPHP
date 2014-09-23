<?php

use SimpleBackupPHP\Application\UnidadeBackup;

class TestUnidadeBackup extends PHPUnit_Framework_TestCase {
    
    /**
     * @test
     */
    public function deveLimparTemporario() {
        if (!is_dir(CAMINHO_DIR_TEMPORARIO)) {
            throw new \Exception("Diretório não existe.");
        }
        
        chdir(CAMINHO_DIR_TEMPORARIO);
        touch("arquivo.txt");
        
        $unidadeBackup = new UnidadeBackup();
        $unidadeBackup->limpaDiretorioTemporario();
        
        if (!is_dir(CAMINHO_DIR_TEMPORARIO)) {
            $this->Fail("Diretório não existe.");
        }
        
        $diretorio = scandir(CAMINHO_DIR_TEMPORARIO);
        $this->assertTrue((count($diretorio) == 2)); //Somente os "." e ".."
    }
}
