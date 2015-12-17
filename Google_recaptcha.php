<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe para integrar ao Google reCAPTCHA
 *
 * @author Pablo Alexander da Rocha Gonçalves
 */
class Google_recaptcha {

    private $site_key;
    private $secret_key;
    private $response;
    private $remote_ip;

    /**
     * Método construtor
     */
    public function __construct() {
        $this->site_key = null;
        $this->secret_key = null;
        $this->response = null;
        $this->remote_ip = null;
    }

    /**
     * Define a chave do site
     * @param string $key
     */
    public function setSiteKey($key) {
        $this->site_key = $key;
    }

    /**
     * Obtem a chave do site
     * @return string
     */
    public function getSiteKey() {
        return $this->site_key;
    }

    /**
     * Define a chave do segredo
     * @param string $key
     */
    public function setSecretKey($key) {
        $this->secret_key = $key;
    }

    /**
     * Obtem a chave do segredo
     * @return string
     */
    public function getSecretKey() {
        return $this->secret_key;
    }
    
    /**
     * Define a resposta do reCAPTCHA
     * @param string $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }
    
    /**
     * Retorna a resposta do reCAPTCHA
     * @return string
     */
    public function getResponse() {
        return $this->response;
    }
    
    /**
     * Define o ip remoto
     * @param string $ip
     */
    public function setRemoteIP($ip) {
        $this->remote_ip = $ip;
    }
    
    /**
     * Retorna o ip remoto
     * @return string
     */
    public function getRemoteIP() {
        return $this->remote_ip;
    }

    /**
     * Retorna o Widget do reCAPTCHA
     * @return string
     */
    public function getWidget() {
        return "<div class=\"g-recaptcha\" data-sitekey=\"{$this->site_key}\"></div>";
    }

    /**
     * Retorna a tag script necessária para integrar o reCAPTCHA
     * é necessário inserir antes de </head>.
     * @return string
     */
    public function getJavaScript() {
        return '<script src="https://www.google.com/recaptcha/api.js"></script>';
    }
    
    /**
     * Valida a resposta do usuário
     * @return boolean
     */
    public function validateResponse() {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        
        // define os paramentros padrões
        $campos = [
            'secret' => urlencode($this->secret_key),
            'response' => urlencode($this->response)
        ];
        
        // verifica se foi definido o ip remoto
        if ($this->remote_ip != null && is_string($this->remote_ip)) {
            $campos['remoteip'] = urlencode($this->remote_ip);
        }
        
        // dados formatados
        $data = '';
        
        // define os dados formatados
        foreach ($campos as $k => $v) {
            $data .= "$k=$v&";
        }
        
        // remove o ultimo &
        rtrim($data, '&');
        
        // abre a conexão
        $ch = curl_init();
        
        // define url, número de variaveis e os dados
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($campos));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // executa o post
        $json = curl_exec($ch);
        $resultado = json_decode($json);
        
        if ($resultado->success == true) {
            return true;
        }
        
        // fecha a conexão
        curl_close($ch);
        
        return false;
    }

}
