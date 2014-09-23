<?php

namespace InfraEstrutura\Email;

/**
 *
 * @author dilsonrabelo.unasus@gmail.com
 */
interface IDespachante {
    function enviar(Mensagem $mensagem);
    function fabricaObjetoDespachante();
}
