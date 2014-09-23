<?php

namespace SimpleBackupPHP\Application;

/**
 * Description of UnidadeDeTrabalho
 *
 * @author dilsonrabelo.unasus@gmail.com
 */
use SimpleBackupPHP\Application\UnidadeBackup;
use SimpleBackupPHP\Adapters\WrapConfig;
use SimpleBackupPHP\Adapters\Application;
use SimpleBackupPHP\Adapters\Adapter\Adapter;
use SimpleBackupPHP\Application\GarbageCollectorBackup;

use InfraEstrutura\Log;
use InfraEstrutura\Utils\DateUtils;
use InfraEstrutura\Email\SigUDespachante;
use InfraEstrutura\Email\Mensagem;
use InfraEstrutura\System\DiskStatus;


class UnidadeDeTrabalho {

    public function iniciaBackup() {       
        //Obtendo configuracao Adapters
        $application = new Application(NULL, NULL);
        $application = $this->capturaConfiguracao();
        
        $this->iniciarPoliticaInfraEstrutura($application);        
        
        Log::setNameLog("logbackup_" . DateUtils::hoje("Ymd") . ".log");
        Log::info(CAMINHO_LOG_SISTEMA, "Backup Iniciado");                

        //Prepara o backup percorrendo nos adaptadores do 
        //disponiveis nas configurações.
        try {
            foreach ($application->getArrayAdapter() as $adapter) {
                //Cria Unidade para o backup
                $unidadeBackup = new UnidadeBackup();
                $unidadeBackup->limpaDiretorioTemporario();
                $unidadeBackup->crieUnidadeBackup();

                $this->efetuaBackup($adapter, $unidadeBackup);

                $unidadeBackup->excluiTemporarioUnidadeBackup();
                Log::info(CAMINHO_LOG_SISTEMA, "Armazenando backup [" . $adapter->getName() . ']');
                $unidadeBackup->copiaUnidadeBackup(CAMINHO_DIR_TEMPORARIO, $adapter->getPath_save_backup());
                chdir(CAMINHO_DIR_TEMPORARIO);
                $unidadeBackup->excluiUnidadeBackup();
            }
        } catch (\Exception $ex) {
            Log::err(CAMINHO_LOG_SISTEMA, "Um erro ocorreu no backup, causa: " . $ex->getMessage());
            if ($unidadeBackup->getPathUnidadeBackup() != NULL) {
                $unidadeBackup->excluiUnidadeBackup();
            }
            throw new \Exception("Um erro ocorreu no backup, causa: " . $ex->getMessage());
        }

        Log::info(CAMINHO_LOG_SISTEMA, "Backup Finalizado com sucesso");

        //Encaminhar Email para Administrador
        foreach ($application->getEmailAdministrador() as $email) {
            $despachante = new SigUDespachante();
            $mensagem = new Mensagem($email, "Feedback Backup", "Backup Finalizado com Sucesso", $despachante->criaArrayAnexo(CAMINHO_LOG_SISTEMA . DIRECTORY_SEPARATOR . Log::getNameLog(), "application/txt", ".log"));

            $despachante->enviar($mensagem);
        }

        return TRUE;
    }

    private function efetuaBackup(Adapter $adapter, UnidadeBackup $unidadeBackup) {
        //Efetua o backup e compacta os arquivos 
        foreach ($adapter->getDirectories() as $diretorioBackup => $nomeArquivoCompactado) {
            try {
                //backup
                $adapter->getConfiguracoes()->backup($diretorioBackup, $unidadeBackup->getPathUnidadeBackup() . DIRECTORY_SEPARATOR . "tmp", $unidadeBackup->getPathUnidadeBackup(), $nomeArquivoCompactado, $unidadeBackup->getPathUnidadeBackup() . DIRECTORY_SEPARATOR . "log");
            } catch (\Exception $ex) {
                Log::err(CAMINHO_LOG_SISTEMA, "Um erro ocorreu no backup, causa: " . $ex->getMessage());
            }
        }

        return TRUE;
    }

    private function capturaConfiguracao() {
        $wrap = new WrapConfig();
        return $wrap->capture();
    }
    
    public function iniciarPoliticaInfraEstrutura(Application $application) {
        $this->prepararAmbienteConfiguracoesGerais();                
        $this->iniciarPoliticaArmazenamentoDisco($application);
    }
    
    public function iniciarPoliticaArmazenamentoDisco(Application $application) {
        /*
         * Quando o disco chegar em 70% de espaço alocado 
         * A ferramenta irá excluir os backups antigos
         * conforme na configuracao.
         */
        $diskUsage = new DiskStatus($application->getUnitDiskDefault());
        
        $percentualEspacoUtilizado = $diskUsage->usedSpacePercent();
        
        if ($percentualEspacoUtilizado >= 70) {
            try {
                $lixeiroBackup = new GarbageCollectorBackup();
                $lixeiroBackup->efetuarLimpeza($application);   
            } catch (Exception $exc) {
                throw new Exception("Ocorreu um erro ao executar a política de limpeza do backup.");
            }
        }
        
        return TRUE;
    }
    
    public function executarGarbageCollectorBackup() {
        //Obtendo configuracao Adapters
        $application = new Application(NULL, NULL);
        $application = $this->capturaConfiguracao();
        
        $this->iniciarPoliticaArmazenamentoDisco($application);
        
        return TRUE;
    }

    public function prepararAmbienteConfiguracoesGerais() {
        //Verifica se o diretorio TEMP existe
        if (!is_dir(CAMINHO_DIR_TEMPORARIO)) {
            chdir(CAMINHO_APLICACAO);
            $retorno = mkdir(CAMINHO_DIR_TEMPORARIO);
            if (!$retorno) {
                throw new Exception("Não foi possível criar o diretório \"tmp\".");
            }
        }
        //Verifica se o diretorio LOG existe
        if (!is_dir(CAMINHO_LOG_SISTEMA)) {
            chdir(CAMINHO_APLICACAO);
            $retorno = mkdir(CAMINHO_LOG_SISTEMA);
            if (!$retorno) {
                throw new Exception("Não foi possível criar o diretório \"log\".");
            }
        }
        //Verifica os Modulos do PHP
        $arrayModulesRequired = unserialize(MODULE_REQUIRED);
        foreach ($arrayModulesRequired as $modulo => $mensagem) {
            if (!extension_loaded($modulo)) {
                throw new Exception($mensagem);
            }
        }                       
        return TRUE;
    }

}
