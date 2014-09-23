<?php

/**
 * Description of TestWrapConfig
 *
 * @author nme
 */

use SimpleBackupPHP\Adapters\WrapConfig;
use SimpleBackupPHP\Adapters\Application;

class TestWrapConfig extends PHPUnit_Framework_TestCase {
    
    /**
     * @test
     */
    public function deveRetornarArrayConfiguracao() {
        $wrapConfig = new WrapConfig();        
        $arrayConfig = $wrapConfig->getArrayConfig();
        
        $this->assertInternalType("array", $arrayConfig);
    }
    
    /**
     * @test
     */
    public function deveRetornarUmObjetoTipoApplication() {
        $wrapConfig = new WrapConfig();
        
        $arrayConfig = $wrapConfig->getArrayConfig();
        
        $application = new Application();
        $application = $wrapConfig->capture();
        
        $this->assertInstanceOf("SigUBackup\Adapters\Application", $application);
        
        $this->assertContains("dilsonrabelo.unasus@gmail.com", $application->getEmailAdministrador());
        
        foreach ($application->getArrayAdapter() as $adapter) {
            $this->assertInstanceOf("SigUBackup\Adapters\Adapter\Adapter", $adapter);
            
            $this->assertEquals($adapter->getPath_save_backup(), "/Users/NME/backup");
            
            $this->assertInstanceOf("SigUBackup\Adapters\Adapter\Type\Ftp", $adapter->getConfiguracoes());
            
            $this->assertEquals($adapter->getConfiguracoes()->getUsername(), "---");
            $this->assertEquals($adapter->getConfiguracoes()->getPort(), "21");
            $this->assertEquals($adapter->getConfiguracoes()->getPassword(), "---");            
        }       
        
        $arrayAdapter = $application->getArrayAdapter();
        
        $this->assertTrue(count($arrayAdapter) > 0);
    }    
}
