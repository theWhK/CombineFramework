<?php
/*
 * by r0ds - 2018
 */

namespace Combine\Modules\Images;

include_once(PATH_ABS."/vendor/verot/class.upload.php/src/class.upload.php");

use upload as ImageUpload;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}


/**
 * Funções para tratamento, redimensionamento  e preparação de imagens.
 * 
 * @author Rodrigo Espinosa <rodrigop.espinosa@gmail.com>
 */

class ImagesModule
{
    /**
     * Responsável por armazenar o endereço da instância da classe ImageUpload
     */
    private $imagem;

    /**
     * Armazena o tipo da imagem que está sendo enviada
     */
    private $tipoImagem;

    /**
     * Armazena o tamanho em bytes da imagem
     */
    private $tamanhoImagem;
    
    /**
     * Determinará o nome da imagem temporariamente
     */
    private $nomeImagem;
    
    /**
     * Receberá o nome temporário da imagem
     */
    private $nomeImagemTemp;

    /**
     * diretório a ser utilizado temporariamente para tratamento da imagem
     */
    private $diretorioImagem;

    /**
     * Trata das mensagens de erro que podem retornar
     */
    private $retorno;

    /**
     * Inicia o módulo 
     * 
     * @param array $imageFile $_FILES da imagem.
     * 
     * @return void
     */
    public function __construct($imageFile){
        // Definição de atributos
        $this->nomeImagem = $imageFile["name"];
        $this->nomeImagemTemp = $imageFile["tmp_name"];
        $this->tipoImagem = $imageFile["type"];
        $this->tamanhoImagem = $imageFile["size"];
        $this->diretorioImagem = PATH_ABS."/tempFiles";
        $this->retorno = array();

        // Instância do upload
        $this->imagem = new ImageUpload($imageFile,'pt_BR');
    }

    /**
     * 
     * Trata do redimensionamento da imagem.
     * 
     * @param int $width tamanho em pixels da nova largura da imagem.
     * @param int $height tamanho em pixels da nova altura da imagem.
     * @param boolean $keepRatio TRUE para manter a proporção da imagem e FALSE para não manter.
     * 
     * @return void
     */
    public function setResize($width,$height,$keepRatio = true){
        //redimensiona os tamanhos da imagem
        if(!empty($width) || !empty($height)){
            //lê a largura e altura da imagem enviada
            list($originalWidth,$originalHeight) = getimagesize($this->nomeImagemTemp);
            
            //caso tenha informado uma largura no parâmetro
            if(!empty($width)){
                //caso for maior que a largura original, calcula a proporção e redefine a largura e altura
                if($originalWidth > $width){
                    $ratio          = $originalHeight / $originalWidth;
                    $originalWidth  = $width;
                    $originalHeight = $originalWidth * $ratio;
                }
            }
            //caso tenha informado uma altura no parâmetro
            if(!empty($height)){
                //caso for maior que a altura original, calcula a proporção e redefine a largura e altura
                if($originalHeight > $height){
                    $ratio          = $originalHeight / $originalWidth;
                    $originalHeight = $height;
                    $originalWidth  = $originalHeight / $ratio;
                }
            }

            $width  = $originalWidth;
            $height = $originalHeight;


            // caso seja TRUE, mantém as proporções da imagem
            if($keepRatio === true){
                $this->imagem->image_ratio = true;
            }else{
                $this->imagem->image_ratio = false;
            }

            // verifica se a largura passada por parametro está correta
            if(!empty($width) && is_numeric($width)){
                // permite que a imagem seja redimensionada
                $this->imagem->image_resize = true;
                $this->imagem->image_x = $width;
            }
            // verifica se a altura passada por parametro está correta
            if(!empty($height) && is_numeric($height)){
                // permite que a imagem seja redimensionada
                $this->imagem->image_resize = true;
                $this->imagem->image_y = $height;
            }
        }else{
            // imagem não será redimensionada
            $this->imagem->image_resize = false;
        }
    }

    /**
     * Altera a qualidade da imagem.
     * 
     * @param int $quality valor de 1 a 100 da qualidade da imagem.
     * 
     * @return void
     */
    public function setQuality($quality){
        // verifica se o parametro passado está de acordo
        if(!empty($quality) && is_numeric($quality) && $quality > 0 && $quality <= 100){
            $this->imagem->jpeg_quality = $quality;
        }else{
            $this->imagem->jpeg_quality = 100;
        }
    }
    
    /**
     * Retorna o tamanho em bytes da imagem.
     * 
     * @return int
     */
    public function getSize(){
        // inicialmente o $this->tamanhoImagem está em bytes
        return $this->tamanhoImagem;
    }
    
    /**
     * Retorna o tipo mime da imagem.
     * 
     * @return string
     */
    public function getType(){
        // verifica se o parametro passado está de acordo
        return $this->tipoImagem;
    }

    /**
     * Realiza os procedimentos para finalização da edição da imagem.
     * 
     * @return array
     */
    public function get(){
        //garante que o nome da imagem no diretório não conflite
        $this->imagem->file_safe_name = true;
        $this->imagem->file_new_name_ext = true;
        //define o novo nome que a imagem terá
        $this->imagem->file_new_name_body = $this->nomeImagem;

        //checa se o upload da imagem foi efetuado corretamente
        if($this->imagem->uploaded){ 
            
            //define permissão completa ao diretório
            chmod($this->diretorioImagem,"0777");

            //processa o upload, copiando a imagem do diretorio temporario para o local definido nos parametros
            $this->imagem->Process($this->diretorioImagem);

            //checa se o processamento foi efetuado corretamente
            if($this->imagem->processed){
                //associa o status e dado de retorno
                $this->retorno["status"] = true;
                $this->retorno["data"] = "data:".$this->tipoImagem.";base64,".base64_encode(file_get_contents($this->diretorioImagem."/".$this->imagem->file_dst_name));
            }else{
                //associa o status e dado de retorno
                $this->retorno["status"] = false;
                $this->retorno["data"] = $this->imagem->error;
            }

            //define permissão completa ao diretório
            chmod($this->diretorioImagem,"0755");

            // deleta o arquivo de imagem criado no servidor, na pasta temporária.
            unlink($this->diretorioImagem."/".$this->imagem->file_dst_name);
        }else{
            //associa o status e dado de retorno
            $this->retorno["status"] = false;
            $this->retorno["data"] = "Erro: ".$this->imagem->error;
        }
        
        //limpa as informações 
        $this->imagem->clean();

        return $this->retorno;
    }
}