<?php

namespace InfraEstrutura\Zip;

/**
 * Description of Zip
 *
 * @author Dilson
 */
class Zip {
    private $zipArchive;
    
    function __construct() {
        $this->zipArchive = new \Zend\Filter\Compress();
    }
    
    function compactarDiretorio($nomeArquivoZip, $diretorio, $destino) {
        $nomeArquivoZip = $destino . DIRECTORY_SEPARATOR . $nomeArquivoZip;
        
        $this->zipArchive->setAdapter("zip");
        $this->zipArchive->setOptions(array("options" => array("archive" => $nomeArquivoZip)));
        
        $this->zipArchive->filter($diretorio);
        
        return $nomeArquivoZip;
    }

}
