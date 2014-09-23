<?php

namespace SimpleBackupPHP\Adapters\Adapter;

/**
 * Description of Config
 *
 * @author dilsonrabelo.unasus@gmail.com
 */

use SimpleBackupPHP\Adapters\Adapter\Type\Plugin;


class Adapter {
    private $configuracoes;
    private $directories;
    private $path_save_backup;
    private $name;

    function __construct(Plugin $configuracoes, array $directories, $path_save_backup, $name) {
        $this->configuracoes = $configuracoes;
        $this->directories = $directories;
        $this->path_save_backup = $path_save_backup;
        $this->name = $name;
    }


    public function getConfiguracoes() {
        return $this->configuracoes;
    }

    public function getDirectories() {
        return $this->directories;
    }

    public function setConfiguracoes(Plugin $configuracoes) {
        $this->configuracoes = $configuracoes;
    }

    public function setDirectories(array $directories) {
        $this->directories = $directories;
    }

    public function getPath_save_backup() {
        return $this->path_save_backup;
    }

    public function setPath_save_backup($path_save_backup) {
        $this->path_save_backup = $path_save_backup;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }


    
}
