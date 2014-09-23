<?php

namespace InfraEstrutura\FTP;

/**
 * Description of FTP
 *
 * @author nme
 */
use InfraEstrutura\Log;

class FTP {

    private $ftpserver;
    private $port;
    private $timeout;
    private $username;
    private $password;
    private $last_directory;
    private $objetoFTPConnection;
    private $objetoLoginConnection;
    private $pathLogApp;

    function __construct($ftpserver, $port, $username, $password, $timeout = 180, $pathLogApp = NULL) {
        $this->ftpserver = $ftpserver;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->username = $username;
        $this->password = $password;

        $this->last_directory = ".";

        if ($pathLogApp == NULL)
            $this->pathLogApp = CAMINHO_LOG_SISTEMA;
        else
            $this->pathLogApp = $pathLogApp;
    }

    public function connect() {
        $this->objetoFTPConnection = NULL;
        $this->objetoLoginConnection = FALSE;
        $iLimiteTentativas = 0;

        while ((!$this->objetoLoginConnection) || ($iLimiteTentativas >= 10)) {
            try {
                $this->objetoFTPConnection = ftp_connect($this->ftpserver, $this->port, $this->timeout);
                $this->objetoLoginConnection = ftp_login($this->objetoFTPConnection, $this->username, $this->password);
                ftp_pasv($this->objetoFTPConnection, TRUE);
            } catch (\Exception $exc) {
                throw new \Exception("Ocorreu um erro ao conectar com o servidor FTP, causa: " . $exc->getMessage());
            }

            $iLimiteTentativas++;
        }

        if ($iLimiteTentativas >= 10) {
            Log::err(CAMINHO_LOG_SISTEMA, "Ocorreu um erro ao conectar com o servidor FTP, Causa: Limites de tentativas 10 de 10.");
            throw new \Exception("Ocorreu um erro ao conectar com o servidor FTP, Causa: Limites de tentativas 10 de 10.");
        }

        return TRUE;
    }

    public function disconnect() {
        ftp_close($this->objetoFTPConnection);
    }

    public function isConnected($bReconectar = FALSE) {
        $retorno = ftp_pwd($this->objetoFTPConnection);

        if (!$retorno) {
            Log::err(CAMINHO_LOG_SISTEMA, "Conexão com FTP perdida. Tentando Reconectar.");
            if ($bReconectar) {
                $this->connect();
                $this->chDir($this->last_directory);
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function chDir($diretorio) {
        $this->isConnected(TRUE);

        return ftp_chdir($this->objetoFTPConnection, $diretorio);
    }

    public function pwd() {
        $this->isConnected(TRUE);

        return ftp_pwd($this->objetoFTPConnection);
    }

    public function getFile($arquivo) {
        $this->isConnected(TRUE);
        $iLimitesTentativas = 0;
        $this->last_directory = $this->pwd();

        while (true) {
            if ($iLimitesTentativas == 10) {
                throw new \Exception("Limite na tentativa de obter o arquivo($arquivo).");
            }

            $retorno = ftp_get($this->objetoFTPConnection, $arquivo, $arquivo, FTP_BINARY);

            if (!$retorno) {
                Log::err($this->pathLogApp, "Erro a baixar o arquivo ($arquivo). Tentando novamente.");
                $iLimitesTentativas++;
                $this->isConnected(TRUE);

                continue;
            }
            break;
        }

        return TRUE;
    }

    public function getListStructureArray($diretorio) {
        $this->isConnected(TRUE);

        return ftp_nlist($this->objetoFTPConnection, $diretorio);
    }

    public function getDirectory($arqRemote) {

        if ($arqRemote != ".") {
            if ($this->chDir($arqRemote) == FALSE) {
                throw new \Exception("Falha ao mudar de diretorio.");
            }
            if (!(is_dir($arqRemote)))
                mkdir($arqRemote);
            chdir($arqRemote);
        }

        $contents = $this->getListStructureArray(".");
        foreach ($contents as $file) {

            if ($file == '.' || $file == '..')
                continue;

            if (@$this->chDir($file)) {
                $this->chDir("..");
                Log::notice($this->pathLogApp, "Baixando diretório ($file).");
                $this->getDirectory($file);
            } else {
                Log::notice($this->pathLogApp, "Baixando arquivo ($file).");
                $this->getFile($file);
            }
        }
        $this->chDir("..");
        chdir("..");
    }

    public function get($arqRemote) {
        if (is_dir($arqRemote)) {
            $this->getDirectory($arqRemote);
        } else {
            $this->getFile($arqRemote);
        }

        return FALSE;
    }

}
