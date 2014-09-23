<?php

/**
 * Description of TestSizeFile
 *
 * @author NME
 */
use InfraEstrutura\System\DiskStatus;
use SimpleBackupPHP\Adapters\WrapConfig;
use SimpleBackupPHP\Application\GarbageCollectorBackup;

class TestSizeDisk extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function deveApresentarInformacoesSobreEspaco() {
        $disk = new DiskStatus("/Users/NME/Desktop");

        echo "Free Disk: " . $disk->freeSpace(FALSE) . "<br />";
        echo "Total Space: " . $disk->totalSpace(FALSE) . "<br />";
        echo "Used Space: " . $disk->usedSpace(FALSE) . "<br />";
        echo "% Space: " . $disk->usedSpacePercent(5) . "<br />";

//        $iterator = new \DirectoryIterator("/Users/NME/www/SigU/backup");
//        
//        foreach ($iterator as $file) {
//            print_r($file->isDir() . "<br/>");
//            $date = new \DateTime(date('Y-m-d H:m:s', $file->getMTime()));
//            print_r($date->format('Y-m-d H:m:s') . "<br/>");
//            print_r($file->getPathname() . "<br/>");
//        }
//
//        $date = new \DateTime('2014-8-18');
//        $date2 = new \DateTime('2014-7-17');
//
//        $intervalo = $date->diff($date2);
//
//        print_r($intervalo->format("%a"));
    }

    /**
     * 
     */
    public function deveExcluirBackupAntigos() {
        $wrapConfig = new WrapConfig();
        $application = $wrapConfig->capture();

        try {
            $lixeiroBackup = new GarbageCollectorBackup();
            $lixeiroBackup->efetuarLimpeza($application);
        } catch (Exception $exc) {
            $this->Fail($exc->getMessage());
        }
    }

}
