<?php

namespace InfraEstrutura;

/**
 * Description of Log
 *
 * @author dilsonrabelo.unasus@gmail.com
 */
class Log {    
    private static $logger;
    private static $nameLog = NULL;       
    
    private static function createLogger() {
        self::$logger = new \Zend\Log\Logger();
    }
    
    private static function destroyLogger() {
        self::$logger = NULL;
    }
    
    public static function setNameLog($name) {
        self::$nameLog = $name;
    }
    
    public static function getNameLog() {
        return self::$nameLog;
    }
    
    public static function err($pathLog, $message) {
        self::createLogger();
        self::$logger->addWriter(new \Zend\Log\Writer\Stream($pathLog . DIRECTORY_SEPARATOR . (self::$nameLog != NULL ? self::$nameLog : "err.log")));
        self::$logger->err($message);
        self::destroyLogger();
        return TRUE;
    }

    public static function info($pathLog, $message) {
        self::createLogger();
        self::$logger->addWriter(new \Zend\Log\Writer\Stream($pathLog . DIRECTORY_SEPARATOR . (self::$nameLog != NULL ? self::$nameLog : "info.log")));
        self::$logger->info($message);
        self::destroyLogger();
        return TRUE;
    }

    public static function notice($pathLog, $message) {
        self::createLogger();
        self::$logger->addWriter(new \Zend\Log\Writer\Stream($pathLog . DIRECTORY_SEPARATOR . (self::$nameLog != NULL ? self::$nameLog : "notice.log")));
        self::$logger->notice($message);
        self::destroyLogger();
        return TRUE;
    }

    public static function warn($pathLog, $message) {
        self::createLogger();
        self::$logger->addWriter(new \Zend\Log\Writer\Stream($pathLog . DIRECTORY_SEPARATOR . (self::$nameLog != NULL ? self::$nameLog : "warn.log")));
        self::$logger->warn($message);
        self::destroyLogger();
        return TRUE;
    }

}
