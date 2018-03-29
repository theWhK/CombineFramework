<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Action;

use Combine\Action\AbstractFirstMoldAction;
use Combine\Modules\FirstMold\FirstMoldHierarchyModule;
use Combine\Modules\FirstMold\FirstMoldPermissionsModule;
use Combine\Modules\FirstMold\FirstMoldMenuModule;
use Combine\Modules\Cargos\CargosModule;
use Combine\Modules\Login\LoginModule;
use Combine\Modules\Notificacoes\NotificacoesModule;
use Combine\Modules\Sessao\SessaoModule;
use Combine\Modules\Usuarios\UsuariosModule;
use Combine\Modules\NivelPoder\NivelPoderModule;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Action de Admin.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class AdminAction extends AbstractFirstMoldAction
{
    public function __construct($core)
    {
        $this->dbPrefix = "admin";
        $this->className = "AdminAction";
        parent::__construct($core);
    }

    /**
     * Comando inicial.
     */
    public function index() 
    {
        //var_dump_pre($_COOKIE, true);
        
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';

        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // RESPONSE: /admin/index/main.php
        require PATH_ABS . '/Response/admin/index/main.php';

        return;
    }

    /**
     * Comando de Usuários.
     */
    public function usuarios() 
    {
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoComando.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoMetodo.php';

        // Instanciação dos módulos necessários
        $CargosModule = new CargosModule($this->conn);
        $NotificacoesModule = new NotificacoesModule();
        $SessaoModule = new SessaoModule($this->commandArchive);
        
        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // Define o Método padrão
        if (empty($this->method)) {
            $this->methodArchive = "listar";
        };

        switch ($this->methodArchive) {
            case "criar":              
            case "listar":
            case "editar":
            case "apagar":
                // Rotina do Método armazenado externamente
                require PATH_ABS . '/Actions/'.$this->className.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';
            break;
        }

        return;
    }

    /**
     * Comando de Cargos.
     */
    public function cargos()
    {
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoComando.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoMetodo.php';
        
        // Instanciação dos módulos necessários
        $CargosModule = new CargosModule($this->conn);
        $SessaoModule = new SessaoModule($this->commandArchive);
        
        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // Define o Método padrão
        if (empty($this->method)) {
            $this->methodArchive = "listar";
        };

        switch ($this->methodArchive) {
            case "criar":              
            case "listar":
            case "editar":
            case "apagar":
                // Rotina do Método armazenado externamente
                require PATH_ABS . '/Actions/'.$this->className.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';
            break;
        }

        return;
    }

    /**
     * Comando de Cadastros do Sistema.
     * Meta-comando: cuida das hierarquias postas nesta Action, usando a First Mold.
     */
    public function cadastros_sistema()
    {
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoComando.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoMetodo.php';
        
        // Instanciação dos módulos necessários
        $FirstMoldHierarchyModule = new FirstMoldHierarchyModule($this->conn, $this->dbPrefix);
        $SessaoModule = new SessaoModule($this->commandArchive);
        
        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // Define o Método padrão
        if (empty($this->method)) {
            $this->methodArchive = "listar";
        };

        switch ($this->methodArchive) {
            case "comando-criar":
            case "metodo-criar":
            case "listar":
            case "comando-editar":
            case "metodo-editar":
            case "comando-apagar":
            case "metodo-apagar":
            case "ajax_resgatarRegistrosAtrelados":
                // Rotina do Método armazenado externamente
                require PATH_ABS . '/Actions/'.$this->className.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';
            break;
        }

        return;
    }

    /**
     * Comando de Permissões dos Cadastros.
     * Meta-comando: cuida das hierarquias postas nesta Action, usando a First Mold.
     */
    public function permissoes()
    {
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoComando.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoMetodo.php';
        
        // Instanciação dos módulos necessários
        $FirstMoldHierarchyModule = new FirstMoldHierarchyModule($this->conn, $this->dbPrefix);
        $SessaoModule = new SessaoModule($this->commandArchive);
        
        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // Define o Método padrão
        if (empty($this->method)) {
            $this->methodArchive = "listar";
        };

        switch ($this->methodArchive) {
            case "criar":
            case "listar":
            case "editar":
            case "apagar":
            case "ajax_resgatarPermissoesCargo":
                // Rotina do Método armazenado externamente
                require PATH_ABS . '/Actions/'.$this->className.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';
            break;
        }

        return;
    }

    /**
     * Comando de Login.
     */
    public function login()
    {
        // Instanciação dos Modules necessários
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NotificacoesModule = new NotificacoesModule();
        $SessaoModule = new SessaoModule($this->commandArchive);
        $NivelPoderModule = new NivelPoderModule($this->conn);
        $UsuariosModule = new UsuariosModule($this->conn);

        // Define o Método padrão
        if (empty($this->method)) {
            $this->methodArchive = "login";
        };

        switch ($this->methodArchive) {
            case "login":
            case "logout":
            case "recuperar-senha":
            case "recuperar-senha-nova":
                // Rotina do Método armazenado externamente
                require PATH_ABS . '/Actions/'.$this->className.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';
            break;
        }

        return;
    }

    /**
     * Comando de Perfil.
     */
    public function perfil()
    {
        // Módulos iniciais
        $LoginModule = new LoginModule(
            $this->conn, 
            $this->dbPrefix, 
            ['CHAVE_USUARIO'         =>      'nickname']);
        $NivelPoderModule = new NivelPoderModule(
            $this->conn);
        $FirstMoldPermissionsModule = new FirstMoldPermissionsModule(
            $this->conn, 
            $this->dbPrefix);
        $FirstMoldMenuModule = new FirstMoldMenuModule(
            $this->conn, 
            $this->dbPrefix, 
            $FirstMoldPermissionsModule->list($LoginModule->userId()),
            $NivelPoderModule->isSuperUser($LoginModule->userId()));
        $NotificacoesModule = new NotificacoesModule();
        $UsuariosModule = new UsuariosModule($this->conn);

        // Bloco padrão para verificar acesso
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarLogin.php';
        require PATH_ABS.'/Actions/'.$this->className.'/_common/verificarPermissaoComando.php';
        
        // Bloco padrão para itens de menu no header
        require PATH_ABS.'/Actions/'.$this->className.'/_common/menuHeader.php';

        // Bloco padrão para dados do usuário logado
        require PATH_ABS.'/Actions/'.$this->className.'/_common/usuarioLogado.php';

        // Rotina da Action armazenado externamente
        require PATH_ABS.'/Actions/'.$this->className.'/'.$this->commandArchive.'/main.php';

        return;
    }
}