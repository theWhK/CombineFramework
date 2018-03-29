<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// RESPONSE OBJECTS: departamentos
$departamentos = $CargosModule->read('WHERE id_depart = "0"');    

// RESPONSE OBJECTS: cargos
$cargos = $CargosModule->read('WHERE id_depart != "0"');

// RESPONSE: /admin/cargos/listar
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;