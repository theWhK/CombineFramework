<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Armazena o ID a ser apagado
$cargoAResgatar = $this->parameters[0];

// Caso o ID seja nulo, retorna à home da sub-ação
if (!isset($cargoAResgatar)) {
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
}

// Resgata os dados e os processa
$dadosResgatados = $FirstMoldPermissionsModule->getPredef("cargos", $cargoAResgatar);

// RESPONSE: dados pedidos em formato JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($dadosResgatados);

return;