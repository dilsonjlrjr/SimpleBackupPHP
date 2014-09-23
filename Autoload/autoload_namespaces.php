<?php

$dominioDir = dirname(dirname(__FILE__));

return array(
    'InfraEstrutura\\' => array($dominioDir),
    'SimpleBackupPHP\\' => array($dominioDir . DIRECTORY_SEPARATOR . 'Dominio'),
    'Test\\' => array($dominioDir . DIRECTORY_SEPARATOR . 'test'),
);
