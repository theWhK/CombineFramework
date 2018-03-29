<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Caso o usuário não for superusuário, lista todos os usuários
// menos os superusuários
if (!$NivelPoderModule->isSuperUser($LoginModule->userId())) {
    $where = "WHERE `nivelUso` != 2";
}

// RESPONSE OBJECTS: dados dos usuários
$usuarios = $UsuariosModule->read($where);

// RESPONSE: /admin/usuarios/listar
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;