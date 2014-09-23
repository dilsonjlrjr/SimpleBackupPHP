<?php

require_once 'bootstrap.php';

use SigUBackup\Application\UnidadeDeTrabalho;

$iniciarGarbageCollector = in_array("garbage", $argv);
try {
    $unidadeTrabalho = new UnidadeDeTrabalho();
    if ($iniciarGarbageCollector) {
        echo "Iniciando a GarbageCollector\n";
        $unidadeTrabalho->executarGarbageCollectorBackup();
        echo "Limpeza efetuada com sucesso\n";
    } else {
        echo "Iniciando a Backup\n";
        $unidadeTrabalho->iniciaBackup();
        echo "Backup finalizado com sucesso\n";
    }
} catch (\Exception $ex) {
    echo $ex->getMessage();
}