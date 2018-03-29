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
        // Caso string, faz a sanitização com função interna do PHP
        if (is_string($data)) $data = filter_var($data, FILTER_SANITIZE_STRING);

        return $data;
    }
}