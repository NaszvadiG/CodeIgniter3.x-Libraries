# CodeIgniter3.x-Libraries
Contém bibliotecas para CodeIgniter v3.x

## Google reCAPTCHA
Integra o CodeIgniter ao Google reCAPTCHA, você deve baixa o arquivo **Google_recaptcha.php** e colar em **application\libraries**.

#### No *Controller*
Eu não recomendo que você configure a biblioteca para carregar automáticamente em **autoload.php**, mas que você a carregue somente no Controller que você vai usa-la.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index() {
        // carrega os recursos necessários
        $this->load->library('google_recaptcha');
        
        // configura o Google reCAPTCHA
        $this->google_recaptcha->setSiteKey("**************************");
        $this->google_recaptcha->setSecretKey("**************************");
        
        // verifica o Google reCAPTCHA
        if ($this->input->post('g-recaptcha-response') != false) {
            $this->google_recaptcha->setResponse($this->input->post('g-recaptcha-response'));
            
            if ($this->google_recaptcha->validateResponse()) {
                // está válido
            } else {
                // inválido
            }
        }
        
        $this->load->view('template_login');
    }

}
```

#### Na *View*
Na sua View você tem que somente inserir o JavaScript do Google usando o método **getJavaScript()** no cabeçalho.

```php
<head>
    <title>Página de Login</title>
    ...
    <!-- reCaptcha -->
    <?= $this->google_recaptcha->getJavaScript() ?>
</head>
```

E onde você quer que o widget do reCAPTCHA apareça use o método **getWidget()**.

```php
<div class="col-md-3 col-lg-6 col-xs-12">
    <?= $this->google_recaptcha->getWidget() ?>
</div>
```

## ViaCEP
Integra o CodeIgniter com o webservice do ViaCEP, também cria uma tabela *CEP* (pode ser alterado) em sua base de dados MySQL para armazenar os CEP's consultados, eles por padrão expiram em seis meses (também pode ser alterado), para usar você deve baixa o arquivo **Viacep.php** e colar em **application\libraries**.

Observação: A biblioteca foi projetada para funcionar com MySQL, mas pode ser facilmente portada para outras bases de dados como PostgreSQL por exemplo, mas é necessário ter uma base de dados configurada em **database.php**. Não é necessário carregar automáticamente a biblioteca '*database*' em *autoload.php* caso a sua aplicação somente a use conforme a necessidade.

#### No Controller
Quando você for integrar a biblioteca a sua aplicação você vai perceber que não tem segredo, você somente precisa usar **$this->load->library('viacep');** para carregar a biblioteca e depois usar o método **consultar()** para saber se foi encontrado o CEP.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        // carrega a biblioteca
        $this->load->library('viacep');

        $this->load->view('welcome_message');
    }

}
```

#### Na View
Para testar ele você pode usar esse teste:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Welcome to CodeIgniter</title>

        <style type="text/css">

            ::selection { background-color: #E13300; color: white; }
            ::-moz-selection { background-color: #E13300; color: white; }

            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }

            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }

            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }

            #body {
                margin: 0 15px 0 15px;
            }

            p.footer {
                text-align: right;
                font-size: 11px;
                border-top: 1px solid #D0D0D0;
                line-height: 32px;
                padding: 0 10px 0 10px;
                margin: 20px 0 0 0;
            }

            #container {
                margin: 10px;
                border: 1px solid #D0D0D0;
                box-shadow: 0 0 8px #D0D0D0;
            }
        </style>
    </head>
    <body>

        <div id="container">
            <h1>Welcome to CodeIgniter!</h1>

            <div id="body">
                <?php
                function exibe_cep($cep, $t) {
                    if ($t->viacep->consultar($cep)) {
                        ?>
                        <strong>CEP: </strong><?= $t->viacep->getCEP() ?><br/>
                        <strong>Logradouro: </strong><?= $t->viacep->getLogradouro() ?><br/>
                        <strong>Complemento: </strong><?= $t->viacep->getComplemento() ?><br/>
                        <strong>Bairro: </strong><?= $t->viacep->getBairro() ?><br/>
                        <strong>Localidade: </strong><?= $t->viacep->getLocalidade() ?><br/>
                        <strong>UF: </strong><?= $t->viacep->getUF() ?><br/>
                        <strong>IBGE: </strong><?= $t->viacep->getIBGE() ?><br/>
                        <strong>GIA: </strong><?= $t->viacep->getGIA() ?><br/>
                        <?php
                    } else {
                        echo $t->viacep->getUltimoErro();
                    }

                    echo '<br/><br/>';
                }

                // testa
                exibe_cep('01001-000', $this);

                exibe_cep('01310-909', $this);

                exibe_cep('13465-970', $this);
                ?>
            </div>

            <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
        </div>

    </body>
</html>
```
