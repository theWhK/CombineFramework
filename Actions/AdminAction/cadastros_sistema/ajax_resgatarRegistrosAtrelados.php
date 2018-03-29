<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Armazena o ID a ser apagado
$tipoResgate = $this->parameters[0];

// Caso o ID seja nulo, retorna à home da sub-ação
if (!isset($tipoResgate)) {
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
}

// Escolhe entre os dados pedidos e faz o resgate
switch ($tipoResgate) {
    case "command":
        // Resgata a lista de comandos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
             FROM ".$this->dbPrefix."_commands");
        $dataRegistrosCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataRegistrosCanvas as $item) {
            if ($item['id_comando_pai'] == 0) {
                $dadosResgatados[$item['id']] = $item;
            }
        }

        foreach ($dataRegistrosCanvas as $item) {
            if ($item['id_comando_pai'] != 0) {
                $dadosResgatados[$item['id_comando_pai']]['listaComandosFilho'][] = $item;
            }
        }
    break;

    case "method":
        // Resgata a lista de métodos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
                FROM ".$this->dbPrefix."_methods");
        $dataRegistrosCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Resgata a lista de comandos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
                FROM ".$this->dbPrefix."_commands");
        $dataComandosPaiCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insere os métodos nos comandos
        foreach ($dataComandosPaiCanvas as $keyPai => $valuePai) {
            foreach ($dataRegistrosCanvas as $key => $value) {
                if ($valuePai['id'] == $value['id_comando_pai']) {
                    $dataComandosPaiCanvas[$keyPai]['listaMetodos'][] = $value;
                    unset($dataRegistrosCanvas[$key]);
                }
            }
        }

        // Organiza os comandos-pai
        foreach ($dataComandosPaiCanvas as $item) {
            if ($item['id_comando_pai'] == 0) {
                $dadosResgatados[$item['id']] = $item;
            }
        }

        // Organiza os comandos-filho
        foreach ($dataComandosPaiCanvas as $item) {
            if ($item['id_comando_pai'] != 0) {
                $dadosResgatados[$item['id_comando_pai']]['listaComandosFilho'][] = $item;
            }
        }

        // Organiza os métodos soltos
        if (!empty($dataRegistrosCanvas)) {
            $dadosResgatados[] = array(
                'rotulo' => 'Métodos Soltos',
                'listaMetodos' => $dataRegistrosCanvas);
        }
    break;
}

// RESPONSE: dados pedidos em formato JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($dadosResgatados);

return;