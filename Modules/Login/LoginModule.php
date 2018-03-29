<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\Login;

use Combine\Classes\Bcrypt\Bcrypt;
use Combine\Modules\BD\BDModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida dos login em área restrita.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 * @abstract baseado no tutorial em 
 * https://pt.wikihow.com/Criar-um-Script-de-Login-Seguro-em-PHP-e-MySQL.
 * Acesso em 20-Fev-2018, 15h10.
 */
class LoginModule
{  
    /**
     * Conexão ao banco de dados.
     */
    private $conn;

    /**
     * Prefixo.
     */
    public $prefix;

    /**
     * Caminho dos cookies.
     */
    public $cookiePath;

    /**
     * Opções.
     * 
     * @abstract Campos disponíveis:
     * 'TABELA_USUARIOS': (usuarios)                                nome da tabela com os usuários.
     * 'LOGAR_VIA': (usuario)                                       escolhe o que será usado para identificar o login. Pode ser 'usuario', 'email' ou 'ambos'
     * 'CHAVE_USUARIO': (username)                                  chave do nome de usuário na tabela.
     * 'CHAVE_SENHA': (password)                                    chave da senha na tabela.
     * 'CHAVE_EMAIL': (email)                                       chave do email na tabela.
     * 'CHAVE_PODER_DE_ACESSO': (nivelUso)                          chave do nível de poder na tabela.
     * 'TABELA_TENTATIVAS': (::prefix . _usuarios_login_tentativas) nome da tabela com o número de tentativas de login.
     * 'TABELA_TOKENS_RECUPERACAO': 
     * (::prefix . _usuarios_tokens_recuperacao)                    nome da tabela com tokens de recuperação de senha.
     * 'PRAZO_RECUPERAR': (1)                                       prazo máximo para a recuperação de senha, em dias.
     * 'TEMPO_EXPIRACAO_TENTATIVAS': (2 * 60 * 60)                  tempo para expiração das tentativas de login.
     * 'QTD_MAXIMA_TENTATIVAS' (5)                                  quantidade máxima de tentativas de login no intervalo de tempo especificado.
     * 'SESSAO_PREFIXO' (::prefix . _auth)                          prefixo usado para a sessão do login.
     * 'TEMPO_LOGADO' (30 * 60 * 60)                                intervalo de tempo para manter o usuário logado.
     */
    public $options;

    /**
     * Inicializa o banco de dados.
     * 
     * @param string $prefix prefixo para isolar a estrutura de login.
     * @param array $dataStructure estrutura dos rótulos
     */
    public function __construct($conn, $prefix, $options = array()) 
    {
        // PDO Connect
        $this->conn = $conn;

        // Definição do prefixo no qual o objeto estará trabalhando
        $this->prefix = $prefix;

        // Armazena as opções e as processa juntamente às opções padrão
        $options_default = array(
            'TABELA_USUARIOS'               =>      'usuarios',
            'LOGAR_VIA'                     =>      'usuario',
            'CHAVE_USUARIO'                 =>      'username',
            'CHAVE_SENHA'                   =>      'password',
            'CHAVE_EMAIL'                   =>      'email',
            'CHAVE_PODER_DE_ACESSO'         =>      'nivelUso',
            'TABELA_TENTATIVAS'             =>      $this->prefix.'_usuarios_login_tentativas',
            'TABELA_TOKENS_RECUPERACAO'     =>      $this->prefix.'_usuarios_tokens_recuperacao',
            'PRAZO_RECUPERAR'               =>      1,
            'TEMPO_EXPIRACAO_TENTATIVAS'    =>      (2 * 60 * 60), // 2 horas
            'QTD_MAXIMA_TENTATIVAS'         =>      5,
            'SESSAO_PREFIXO'                =>      $this->prefix.'_auth',
            'TEMPO_LOGADO'                  =>      (30 * 24 * 60 * 60) // 30 dias
        );
        $this->options = array_replace($options_default, $options);

        // Define o caminho do cookie
        $this->cookiePath = "/";
    }

    /**
     * Faz o login do usuário no sistema através do nome de usuário.
     * 
     * @param string $username Nome de usuário.
     * @param string $password Senha
     * @param int $cookieTime timestamp UNIX para o tempo que manter-se-á o usuário logado.
     * Caso 0, será o tempo de sessão; caso 1, será o tempo estipulado nas opções do objeto para o prazo longo.
     */
    public function loginWithUsername($username, $password, $cookieTime = 0)
    {
        // Define o tempo de sessão
        switch ($cookieTime) {
            case 0:
                $cookieTime = 0;
                break;
            case 1:
                $cookieTime = time() + $this->options['TEMPO_LOGADO'];
                break;
            default:
                $cookieTime += time();
                break;
        }

        // Usando definições pré-estabelecidas significa que a injeção de SQL (um tipo de ataque) não é possível. 
        if ($stmt = $this->conn->PDO->prepare(
            "SELECT 
                id, 
                {$this->options['CHAVE_USUARIO']}, 
                {$this->options['CHAVE_SENHA']} 
             FROM {$this->options['TABELA_USUARIOS']}
             WHERE {$this->options['CHAVE_USUARIO']} = ?
             LIMIT 1")) {
            $stmt->execute([$username]); // Executa a tarefa estabelecida.
            $result = $stmt->fetch();
    
            // obtém variáveis a partir dos resultados.
            $user_id = $result['id'];
            $user_login = $result[$this->options['CHAVE_USUARIO']];
            $user_pwd = $result[$this->options['CHAVE_SENHA']];

            if ($stmt->rowCount() == 1) {
                // Caso o usuário exista, conferimos se a conta está bloqueada
                // devido ao limite de tentativas de login ter sido ultrapassado     
                if ($this->checkBrute($user_id) == true) {
                    // A conta está bloqueada 
                    // Envia um email ao usuário informando que a conta está bloqueada 
                    return false;
                } else {
                    // Verifica se a senha confere com o que consta no banco de dados
                    // a senha do usuário é enviada.
                    if (Bcrypt::check($password, $user_pwd)) {
                        // A senha está correta!
                        // Obtém o string usuário-agente do usuário. 
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        // proteção XSS conforme imprimimos este valor
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        setcookie(
                            "{$this->options['SESSAO_PREFIXO']}_user_id",
                            $user_id,
                            $cookieTime,
                            $this->cookiePath,
                            "",
                            false,
                            true);
                        // proteção XSS conforme imprimimos este valor 
                        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                    "", 
                                                                    $username);
                        setcookie(
                            "{$this->options['SESSAO_PREFIXO']}_username",
                            $username,
                            $cookieTime,
                            $this->cookiePath,
                            "",
                            false,
                            true);
                        setcookie(
                            "{$this->options['SESSAO_PREFIXO']}_login_string",
                            hash('sha512', $user_pwd . $user_browser),
                            $cookieTime,
                            $this->cookiePath,
                            "",
                            false,
                            true);
                        // Login concluído com sucesso.
                        return true;
                    } else {
                        // A senha não está correta
                        // Registramos essa tentativa no banco de dados
                        $now = time();
                        $this->conn->PDO->query(
                        "INSERT INTO ".$this->options['TABELA_TENTATIVAS']."
                        (
                            id_usuario,
                            data
                        ) VALUES (
                            '$user_id',
                            '$now'
                        )");
                        return false;
                    }
                }
            } else {
                // Tal usuário não existe.
                return false;
            }
        }
    }

    /**
     * Checa se a conta em questão está bloqueada pela proteção
     * contra invasão via bruteforce.
     * 
     * @param string $user_id ID do usuário para checagem.
     */
    public function checkBrute($user_id)
    {
        // Registra a hora atual 
        $now = time();
    
        // Todas as tentativas de login são contadas dentro do intervalo das últimas 2 horas. 
        $valid_attempts = $now - $this->options['TEMPO_EXPIRACAO_TENTATIVAS'];
    
        if ($stmt = $this->conn->PDO->prepare("SELECT data 
                                FROM ".$this->options['TABELA_TENTATIVAS']."
                                WHERE id_usuario = ? 
                                AND data > '$valid_attempts'")) {
    
            // Executa a tarefa pré-estabelecida. 
            $stmt->execute(array(
                $user_id
            ));
    
            // Se houve mais do que 5 tentativas fracassadas de login 
            if ($stmt->rowCount() > $this->options['QTD_MAXIMA_TENTATIVAS']) {
                return true;
            } else {
                return false;
            }
        }

        // Não houve conexão ao banco; encerra o app.
        statusCode(500);
    }

    /**
     * Checa se o login existe e é válido.
     * 
     * @return bool
     */
    public function loginCheck()
    {
        // Verifica se todas as variáveis das sessões foram definidas 
        if (isset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"], 
                  $_COOKIE["{$this->options['SESSAO_PREFIXO']}_username"], 
                  $_COOKIE["{$this->options['SESSAO_PREFIXO']}_login_string"])) {
    
            $user_id      = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"];
            $login_string = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_login_string"];
            $username     = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_username"];
    
            // Pega a string do usuário.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];
    
            if ($stmt = $this->conn->PDO->prepare("SELECT ".$this->options['CHAVE_SENHA']." 
                                        FROM ".$this->options['TABELA_USUARIOS']." 
                                        WHERE id = ? LIMIT 1")) {
                // Executa a query
                $stmt->execute([$user_id]);

                if ($stmt->rowCount() == 1) {
                    // Caso o usuário exista, pega variáveis a partir do resultado.
                    $password = $stmt->fetch()[$this->options['CHAVE_SENHA']];
                    $login_check = hash('sha512', $password . $user_browser);

                    if ($login_check == $login_string) {
                        // Logado!
                        return true;
                    } else {
                        // Não foi logado 
                        return false;
                    }
                } else {
                    // Não foi logado 
                    return false;
                }
            } else {
                // Não foi logado 
                return false;
            }
        } else {
            // Não foi logado 
            return false;
        }
    }

    /**
     * Executa o logout.
     */
    public function logout()
    {
        // Verifica se todas as variáveis das sessões foram definidas 
        if (isset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"], 
                  $_COOKIE["{$this->options['SESSAO_PREFIXO']}_username"], 
                  $_COOKIE["{$this->options['SESSAO_PREFIXO']}_login_string"])) {
    
            $user_id      = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"];
            $username     = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_username"];
            $login_string = $_COOKIE["{$this->options['SESSAO_PREFIXO']}_login_string"];

            // Desfaz todos os valores dos cookies
            unset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"]);
            unset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_username"]);
            unset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_login_string"]);

            // Envia cookies no passado
            setcookie(
                "{$this->options['SESSAO_PREFIXO']}_user_id",
                $user_id,
                1,
                $this->cookiePath,
                "",
                false,
                true);
            setcookie(
                "{$this->options['SESSAO_PREFIXO']}_username",
                $username,
                1,
                $this->cookiePath,
                "",
                false,
                true);
            setcookie(
                "{$this->options['SESSAO_PREFIXO']}_login_string",
                $login_string,
                1,
                $this->cookiePath,
                "",
                false,
                true);
        }
        
        return true;
    }

    /**
     * Gera um token para recuperação de senha, vincula-o ao
     * determinado usuário e retorna o token.
     * 
     * @param string $email email do usuário
     * 
     * @return string
     */
    public function startRecovery($email)
    {
        // Gera um token
        $token = rand(999, 99999);
        $token = md5($token);

        // Verifica se há o email no cadastro
        $stmt = $this->conn->PDO->prepare(
            "SELECT 1
             FROM `usuarios`
             WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            // Guarda na tabela de tokens
            $stmt = $this->conn->PDO->prepare(
                "INSERT INTO {$this->options['TABELA_TOKENS_RECUPERACAO']}
                (email, token, dataExpiracao)
                VALUES
                (?, ?, ADDTIME(NOW(), '{$this->options['PRAZO_RECUPERAR']} 0:0:0'))");
            $stmt->execute([$email, $token]);

            return $token;
        }
        
        return false;
    }

    /**
     * Verifica se existe o processo de recuperação de senha
     * solicitado, e retorna o ID deste registro.
     * 
     * @param string $email Email do usuário vinculado
     * @param string $token Token do processo
     * 
     * @return int/bool ID do registro, ou false caso não haja
     */
    public function requestRecoveryProcess($email, $token)
    {
        // Limpa a tabela apagando registros que tenham passado do prazo
        $this->conn->PDO->query(
            "DELETE 
             FROM {$this->options['TABELA_TOKENS_RECUPERACAO']} 
             WHERE dataExpiracao < NOW()");

        // Busca o registro na tabela
        $stmt = $this->conn->PDO->prepare(
            "SELECT id 
             FROM {$this->options['TABELA_TOKENS_RECUPERACAO']}
             WHERE email = ?
             AND token = ?");
        $stmt->execute([$email, $token]);

        // Verifica se há o registro
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch()['id'];
        } else {
            return false;
        }
    }

    /**
     * Apaga o token de recuperação de senha indicado.
     * 
     * @param int $id ID do token;
     * 
     * @return bool
     */
    public function deleteRecoveryProcess($id)
    {
        //  Apaga o registro
        $stmt = $this->conn->PDO->prepare(
            "DELETE 
             FROM {$this->options['TABELA_TOKENS_RECUPERACAO']}
             WHERE id = ?");
        $stmt->execute([$id]);

        return true;
    }

    /**
     * Requer o login para acesso; caso não, executa o logout
     * e redireciona para uma página aberta especificada.
     * 
     * @param string $page página para redirecionamento, caso não esteja logado.
     * 
     * @return void
     */
    public function requireLogin($page = URL_BASE)
    {
        if (!$this->loginCheck()) {
            $this->logout();
            redirectTo($page);
        }
    }

    /**
     * Retorna o ID do usuário.
     * 
     * @return int/bool
     */
    public function userId()
    {
        if (isset($_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"]))
            return $_COOKIE["{$this->options['SESSAO_PREFIXO']}_user_id"]; return false;
    }
}