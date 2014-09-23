<?php

namespace SimpleBackupPHP\Adapters\Adapter\Type;

/**
 *
 * @author nme
 */
abstract class Plugin {
    abstract function configuraObjeto(array $arrayConfiguration);
    abstract function backup($diretorioBackup, $destinoDownloadArquivo, $pathArquivoCompactado, $nomeArquivoCompactado, $pathLog);
}
