<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\ProtectExternalData;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Coletânea de recursos para tratamento de dados externos.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class ProtectExternalDataModule
{
    /**
     * Faz a limpeza dos dados enviados e os retorna limpos.
     * 
     * @param X $data dados a serem processados.
     * 
     * @return X/null
     */
    public static function protect($data)
    {
        // Caso array, itera entre os itens e os processa um a um
        if (is_array($data)) {
            foreach($data as $key => $child) {
                $data[$key] = parent::protect($child);
            }
        }

        // Caso string, faz a sanitização com função interna do PHP
        if (is_string($data)) $data = filter_var($data, FILTER_SANITIZE_STRING);

        // Caso outros, faz a sanitização com função interna do PHP
        if (!is_string($data) && !is_array($data)) $data = filter_var($data);

        return $data;
    }
}