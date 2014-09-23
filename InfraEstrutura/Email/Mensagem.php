<?php

namespace InfraEstrutura\Email;

class Mensagem {
    private $email;
    private $assunto;
    private $corpo;
    private $anexo;    
    
    function __construct($email, $assunto, $corpo, array $anexo = array()) {
        $this->email = $email;
        $this->assunto = $assunto;
        $this->corpo = $corpo;
        $this->anexo = $anexo;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function getAssunto() {
        return $this->assunto;
    }

    public function getCorpo() {
        return $this->corpo;
    }

    public function getAnexo() {
        return $this->anexo;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAssunto($assunto) {
        $this->assunto = $assunto;
    }

    public function setCorpo($corpo) {
        $this->corpo = $corpo;
    }

    public function setAnexo($anexo) {
        $this->anexo = $anexo;
    }


}
