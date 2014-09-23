<?php

/**
 * Description of TestUnidadeTrabalho
 *
 * @author nme
 */
use SimpleBackupPHP\Application\UnidadeDeTrabalho;

class TestUnidadeTrabalho extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function deveIniciarBackup() {
        $unidadeTrabalho = new UnidadeDeTrabalho();
        $this->assertTrue($unidadeTrabalho->iniciaBackup());
    }
}
