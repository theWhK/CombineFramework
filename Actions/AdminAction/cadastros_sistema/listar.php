<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// RESPONSE OBJECTS: lista hierarquizada
$itens = $FirstMoldHierarchyModule->readNested();

// RESPONSE: /admin/cadastros_sistema/listar
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;