<?php
/*
 * by r0ds - 2018
 */

namespace Combine\Modules\FirstMold;

use PDO;
use Combine\Modules\CRUD\CRUDModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida da montagem automática do menu do header.
 * 
 * @author Rodrigo Espinosa <rodrigop.espinosa@gmail.com>
 */

class FirstMoldMenuModule
{
    /**
     * Conexão ao banco.
     */
    private $conn;

    /**
     * Módulo de CRUD.
     */
    public $CRUD;
    
    /**
     * Prefixo das tabelas
     */
    public $prefix;

    /**
     * ID do usuário logado.
     */
    private $userId;

    /**
     * Permissões de usuário.
     */
    private $userPerms;

    /**
     * É superusuário?
     */
    private $isSuperUser;
    
    /**
    * Inicializa o banco de dados.
    */
    public function __construct($conn, $prefix, $userPerms, $isSuperUser) 
    {
        // Determina o prefixo das tabelas
        $this->prefix = $prefix;

        // Armazenamento da conexão ao banco
        $this->conn = $conn;

        // Armazenamento das permissões do usuário
        $this->userPerms = $userPerms;

        // Armazenamento do estado de superusuário
        $this->isSuperUser = $isSuperUser;

        // Módulo de CRUD
        $this->CRUD = new CRUDModule(
                            [$this->prefix.'_menu'],
                            $this->conn);
        
    }
    
    /**
     * Retorna um array com os itens de menu que podem ser mostrados
     * ao usuário atual.
     * 
     * @param string $where comando SQL caso a leitura seja específica. 
     * Lembre-se de colocar o verbo WHERE no comando.
     * 
     * @return array multi-array com dados dos menus
     */
    public function displayedItems()
    {
        // Resgata os itens-pai
        $itens = $this->CRUD->query(
            "SELECT
            menu.`id`,
            menu.`visibilidade`,
            permissao.`id` AS idPermissao,
            CASE
                WHEN (menu.`rotulo` = '')
                THEN
                CASE
                WHEN (menu.`tipo` = 'command')
                THEN command.`rotulo`
                WHEN (menu.`tipo` = 'method')
                THEN method.`rotulo`
                END
                ELSE menu.`rotulo`
            END AS rotulo,
            CASE
                WHEN (menu.`urlAmigavel` = '')
                THEN
                CASE
                WHEN (menu.`tipo` = 'command')
                THEN command.`urlAmigavel`
                WHEN (menu.`tipo` = 'method')
                THEN method.`urlAmigavel`
                END
                ELSE menu.`urlAmigavel`
            END AS urlAmigavel,
            CASE
                WHEN (menu.`classeIcone` = '')
                THEN
                CASE
                WHEN (menu.`tipo` = 'command')
                THEN command.`classeIcone`
                WHEN (menu.`tipo` = 'method')
                THEN method.`classeIcone`
                END
                ELSE menu.`classeIcone`
            END AS classeIcone
            FROM
            {$this->prefix}_menu menu
            LEFT OUTER JOIN {$this->prefix}_commands command
                ON menu.`idRegistroAtrelado` = command.`id`
                AND menu.`tipo` = 'command'
            LEFT OUTER JOIN {$this->prefix}_methods method
                ON menu.`idRegistroAtrelado` = method.`id`
                AND menu.`tipo` = 'method'
            LEFT OUTER JOIN {$this->prefix}_permissoes_lista permissao
                ON menu.`idRegistroAtrelado` = permissao.`idRegistroAtrelado`
                AND menu.`tipo` = permissao.`tipo` 
                AND menu.`tipo` != 'custom'
            WHERE menu.`idMenuPai` = 0
            AND menu.`status` = 1
            ORDER BY menu.`ordem` ASC"
        );
        $itens = $itens->fetchAll(PDO::FETCH_ASSOC);

        // Varre os itens-pai para remover os quais não há permissão de acesso
        if (!$this->isSuperUser) {
            foreach ($itens as $key => $value) {
                switch ($value['visibilidade']) {
                    case "escondido":
                        if ($value['idPermissao'] != null && !in_array($value['idPermissao'], $this->userPerms))
                            unset($itens[$key]);
                        break;
                    case "translucido":
                        if ($value['idPermissao'] != null && in_array($value['idPermissao'], $this->userPerms))
                            unset($itens[$key]['visibilidade']);
                        break;
                    case "superuser":
                        unset($itens[$key]);
                        break;
                }
            }
        }

        // Varre os itens-pai e emenda seus filhos na chave "listaSubitens"
        foreach ($itens as $key => $value) {
            // Resgata os filhos
            $itensFilho = $this->CRUD->query(
                "SELECT
                menu.`id`,
                menu.`visibilidade`,
                permissao.`id` AS idPermissao,
                CASE
                    WHEN (menu.`rotulo` = '')
                    THEN
                    CASE
                    WHEN (menu.`tipo` = 'command')
                    THEN command.`rotulo`
                    WHEN (menu.`tipo` = 'method')
                    THEN method.`rotulo`
                    END
                    ELSE menu.`rotulo`
                END AS rotulo,
                CASE
                    WHEN (menu.`urlAmigavel` = '')
                    THEN
                    CASE
                    WHEN (menu.`tipo` = 'command')
                    THEN command.`urlAmigavel`
                    WHEN (menu.`tipo` = 'method')
                    THEN method.`urlAmigavel`
                    END
                    ELSE menu.`urlAmigavel`
                END AS urlAmigavel,
                CASE
                    WHEN (menu.`classeIcone` = '')
                    THEN
                    CASE
                    WHEN (menu.`tipo` = 'command')
                    THEN command.`classeIcone`
                    WHEN (menu.`tipo` = 'method')
                    THEN method.`classeIcone`
                    END
                    ELSE menu.`classeIcone`
                END AS classeIcone
                FROM
                {$this->prefix}_menu menu
                LEFT OUTER JOIN {$this->prefix}_commands command
                    ON menu.`idRegistroAtrelado` = command.`id`
                    AND menu.`tipo` = 'command'
                LEFT OUTER JOIN {$this->prefix}_methods method
                    ON menu.`idRegistroAtrelado` = method.`id`
                    AND menu.`tipo` = 'method'
                LEFT OUTER JOIN {$this->prefix}_permissoes_lista permissao
                    ON menu.`idRegistroAtrelado` = permissao.`idRegistroAtrelado`
                    AND menu.`tipo` = permissao.`tipo`
                    AND menu.`tipo` != 'custom'
                WHERE menu.`idMenuPai` = ?
                AND menu.`status` = 1
                ORDER BY menu.`ordem` ASC",
                [$value['id']]);
            
            // Emenda os filhos
            if ($itensFilho->rowCount() > 0) {
                $itens[$key]["listaSubitens"] = $itensFilho->fetchAll(PDO::FETCH_ASSOC);
                $itensFilhoResult = &$itens[$key]["listaSubitens"];
                
                // Varre os itens-filho para remover os quais não há permissão de acesso
                if (!$this->isSuperUser) {
                    foreach ($itensFilhoResult as $key => $value) {
                        switch ($value['visibilidade']) {
                            case "escondido":
                                if ($value['idPermissao'] != null && !in_array($value['idPermissao'], $this->userPerms))
                                    unset($itensFilhoResult[$key]);
                                break;
                            case "translucido":
                                if ($value['idPermissao'] != null && in_array($value['idPermissao'], $this->userPerms))
                                    unset($itensFilhoResult[$key]['visibilidade']);
                                break;
                            case "superuser":
                                unset($itensFilhoResult[$key]);
                                break;
                        }
                    }
                }
            }
        }

        // Retorna os itens
        return $itens;
    }
}