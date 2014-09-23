<?php

namespace SimpleBackupPHP\Adapters;

/**
 * Description of Application
 *
 * @author dilsonrabelo.unasus@gmail.com
 */
use SimpleBackupPHP\Adapters\Adapter\Adapter;

class Application {
    private $arrayAdapter;
    private $emailAdministrador;
    private $dayExcludeBackup;
    private $unitDiskDefault;
    
    function __construct() {
        $this->arrayAdapter = array();
    }

    public function getArrayAdapter() {
        return $this->arrayAdapter;
    }

    public function addAdapter(Adapter $adapter) {
        $this->arrayAdapter[] = $adapter;
    }
    
    public function clearArrayAdapter() {
        $this->arrayAdapter = array();
    }
    
    public function getEmailAdministrador() {
        return $this->emailAdministrador;
    }

    public function setEmailAdministrador($emailAdministrador) {
        $this->emailAdministrador = $emailAdministrador;
    }

    public function getDayExcludeBackup() {
        return $this->dayExcludeBackup;
    }

    public function setDayExcludeBackup($dayExcludeBackup) {
        $this->dayExcludeBackup = $dayExcludeBackup;
    }
    
    public function getUnitDiskDefault() {
        return $this->unitDiskDefault;
    }

    public function setUnitDiskDefault($unitDiskDefault) {
        $this->unitDiskDefault = $unitDiskDefault;
    }

}
