<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Permissões do comando
$permissoes_comando_stmt = $FirstMoldPermissionsModule->query(
    'SELECT
    permissoes.id,
    permissoes.nome,
    permissoes.descricao,
    permissoes.tipo,
    permissoes.idRegistroAtrelado,
    comandos.rotulo,
    comandos.id_comando_pai
    FROM
    '.$this->dbPrefix.'_permissoes_lista permissoes
    LEFT JOIN '.$this->dbPrefix.'_commands comandos
        ON permissoes.idRegistroAtrelado = comandos.id
    WHERE permissoes.tipo = "command"
    ORDER BY permissoes.id');
$permissoes_comando = $permissoes_comando_stmt->fetchAll(PDO::FETCH_ASSOC);

// Permissões do método
$permissoes_metodo_stmt = $FirstMoldPermissionsModule->query(
    'SELECT
    permissoes.id,
    permissoes.nome,
    permissoes.descricao,
    permissoes.tipo,
    permissoes.idRegistroAtrelado,
    metodos.rotulo,
    metodos.id_comando_pai
    FROM
    '.$this->dbPrefix.'_permissoes_lista permissoes
    LEFT JOIN '.$this->dbPrefix.'_methods metodos
        ON permissoes.idRegistroAtrelado = metodos.id
    WHERE permissoes.tipo = "method"
    ORDER BY permissoes.id');
$permissoes_metodo = $permissoes_metodo_stmt->fetchAll(PDO::FETCH_ASSOC);

// Permissões de comandos e métodos estruturadas hierarquicamente
    // Hierarquia das permissões de métodos
    foreach ($permissoes_comando as $key => $comando) {
        // Reseta a chave
        $permissoes_hierarquia_canvas[$comando['idRegistroAtrelado']] = $permissoes_comando[$key];

        // Varre os métodos
        foreach ($permissoes_metodo as $key_sec => $metodo) {
            // Se o método pertencer ao comando em voga, insere-o no conjunto
            if ($comando['idRegistroAtrelado'] == $metodo['id_comando_pai']) {
                $permissoes_hierarquia_canvas[$comando['idRegistroAtrelado']]['listaMetodos'][] = $metodo;
                unset($permissoes_metodo[$key_sec]);
            }
        }
    }

    // Hierarquia entre permissões de comandos
    foreach ($permissoes_hierarquia_canvas as $key => $comando) {
        // Hierarquiza os comandos
        if ($comando['id_comando_pai'] == 0) {
            $permissoes_hierarquia[$comando['idRegistroAtrelado']] = $comando;
        }
    }
    foreach ($permissoes_hierarquia_canvas as $key => $comando) {
        // Hierarquiza os comandos
        if ($comando['id_comando_pai'] != 0) {
            $permissoes_hierarquia[$comando['id_comando_pai']]['listaComandosFilho'][] = $comando;
        }
    }

// RESPONSE OBJECTS: permissões hierarquizadas
$permissoes_hierarquia;

// RESPONSE OBJECTS: permissões customizadas
$permissoes_custom = $FirstMoldPermissionsModule->read("WHERE tipo = 'custom'");

// Executa trocas de informação nos objetos da Response
if (is_array($permissoes_todos)) {
    foreach ($permissoes_todos as $key => $value) {
        switch ($value['tipo']) {
            case "command":
                $permissoes_todos[$key]['tipo'] = "Comando";
            break;
            case "method":
                $permissoes_todos[$key]['tipo'] = "Método";
            break;
            case "custom":
                $permissoes_todos[$key]['tipo'] = "Personalizado";
            break;
        }
    }
}

// RESPONSE: /admin/cadastros_sistema/listar
require PATH_ABS . '/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;