<?php

date_default_timezone_set('America/Sao_Paulo');

/**
 * Arquivo de iniciação para frameworks
 * 
 * @author Dilson José <dilsonrabelo.unasus@gmail.com>
 * @version 1.0
 */
define("CAMINHO_APLICACAO", __DIR__);
define("CAMINHO_DIR_TEMPORARIO", CAMINHO_APLICACAO . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR);
define("CAMINHO_LOG_SISTEMA", CAMINHO_APLICACAO . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR);


/*
 * Modulos requidos para desenvolvimento da Aplicação.
 */
$arrayModulesRequired = array(
    "zip" => "O módulo zip não foi instalado.",
    "json" => "O módulo json não foi instalado.",
    "ftp" => "O módulo ftp não foi instalado.",
    "curl" => "O módulo curl não foi instalado.",
);
define("MODULE_REQUIRED", serialize($arrayModulesRequired));

/**
 * Auto loading namespace
 */
require_once 'Autoload/autoload.php';

/**
 * Zend Framework
 *  Módulos:
 *      Zend Filter: 2.*
 *      Zend StdLib: 2.*
 */
require_once 'vendor/autoload.php';

/**
 * Reportar somente Erros (Exception)
 */
error_reporting(E_ERROR);