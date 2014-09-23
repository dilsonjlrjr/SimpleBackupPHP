<?php

namespace InfraEstrutura\File;

/**
 * Description of Directory
 *
 * @author nme
 */
class Directory {

    public function copyDirectory($source, $destiny) {
        // If source is not a directory stop processing
        if (!is_dir($source)) {
            throw new \Exception("O caminho informado não é um diretório ou não existe!");
        }

        // If the destination directory does not exist create it
        if (!is_dir($destiny)) {
            if (!mkdir($destiny, 0777, TRUE)) {
                throw new \Exception("Ocorreu um erro ao criar o diretório de destino.[$destiny]");
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($source);
        foreach ($i as $f) {
            if ($f->isFile()) {
                copy($f->getRealPath(), $destiny . DIRECTORY_SEPARATOR . $f->getFilename());
            } else if (!$f->isDot() && $f->isDir()) {
                $this->copyDirectory($f->getRealPath(), $destiny . DIRECTORY_SEPARATOR . $f);
            }
        }

        return TRUE;
    }

    public function excludeDirectory($directory) {
        $arrayArquivos = array_diff(scandir($directory), array('.', '..'));

        foreach ($arrayArquivos as $arquivo) {
            $arquivo = $directory . DIRECTORY_SEPARATOR . $arquivo;
            (is_dir($arquivo)) ? $this->excludeDirectory($arquivo) : unlink($arquivo);
        }

        return rmdir($directory);
    }

}
