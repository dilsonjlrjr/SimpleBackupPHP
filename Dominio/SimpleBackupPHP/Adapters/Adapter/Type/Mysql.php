<?php

namespace SimpleBackupPHP\Adapters\Adapter\Type;

/**
 * Description of Mysql
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use SimpleBackupPHP\Adapters\Adapter\Type\Plugin;
use InfraEstrutura\Log;
use InfraEstrutura\System\OS as SistemaOperacional;

class Mysql extends Plugin {
    private $mysql_ip_server;
    private $mysql_user;
    private $mysql_password;
    
    function __construct($mysql_ip_server = NULL, $mysql_user = NULL, $mysql_password = NULL) {
        $this->mysql_ip_server = $mysql_ip_server;
        $this->mysql_user = $mysql_user;
        $this->mysql_password = $mysql_password;
    }

    public function getMysql_ip_server() {
        return $this->mysql_ip_server;
    }

    public function getMysql_user() {
        return $this->mysql_user;
    }

    public function getMysql_password() {
        return $this->mysql_password;
    }

    public function setMysql_ip_server($mysql_ip_server) {
        $this->mysql_ip_server = $mysql_ip_server;
    }

    public function setMysql_user($mysql_user) {
        $this->mysql_user = $mysql_user;
    }

    public function setMysql_password($mysql_password) {
        $this->mysql_password = $mysql_password;
    }
        
    public function backup($diretorioBackup, $destinoDownloadArquivo, $pathArquivoCompactado, $nomeArquivoCompactado, $pathLog) {
        try {
            chdir($destinoDownloadArquivo);
            Log::info($pathLog, "Iniciando backup do banco de dados MYSQL (Server: $this->mysql_ip_server. Banco: $diretorioBackup).");
            $this->dumpMysql($diretorioBackup, $pathArquivoCompactado . DIRECTORY_SEPARATOR . $nomeArquivoCompactado);
            Log::info($pathLog, "Backup do banco de dados MYSQL efetuado com sucesso. (Server: $this->mysql_ip_server. Banco: $diretorioBackup).");            
        } catch (\Exception $ex) {
            Log::err($pathLog, "Ocorreu um erro ao efetuar o backup do banco de dados MYSQL (Server: $this->mysql_ip_server. Banco: $diretorioBackup). Causa:" . $ex->getMessage());
            throw new \Exception("Ocorreu um erro ao efetuar o backup do banco de dados MYSQL (Server: $this->mysql_ip_server. Banco: $diretorioBackup). Causa:" . $ex->getMessage());
        }        
        return TRUE;        
    }       

    public function configuraObjeto(array $arrayConfiguration) {
        $this->mysql_ip_server = $arrayConfiguration['server'];
        $this->mysql_password = $arrayConfiguration['password'];
        $this->mysql_user = $arrayConfiguration['user'];
        
        return TRUE;
    }
    
    private function checkOS() {
        if (SistemaOperacional::getSistemaOperacional() != SistemaOperacional::__Linux) {
            throw new \Exception("Funcionalidade disponível somente pra Sistema Operacional Linux.");
        }

        return TRUE;        
    }
    
    private function dumpMysql($nome_banco, $file_path) {
        $this->checkOS();
        
        system("mysqldump -u $this->mysql_user -h $this->mysql_ip_server -p$this->mysql_password $nome_banco > $file_path", $result_shell_exec);
        
        if ($result_shell_exec != 0) {
            throw new \Exception ("Ocorreu um erro ao executar o DUMP do banco. Exit Code: $result_shell_exec");
        }
            
        return TRUE;
        
    }
}
