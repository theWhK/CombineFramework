<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Action;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Action base.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
abstract class Action
{
    /**
     * Core.
     */
    public $core;

    public function __construct($core)
    {
        // Armazena o Core num atributo
        $this->core = $core;
    }
}