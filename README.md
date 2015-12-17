# CodeIgniter3.x-Libraries
Contém bibliotecas para CodeIgniter v3.x

## Google reCAPTCHA
Integra o CodeIgniter ao Google reCAPTCHA, você deve baixa o arquivo **Google_recaptcha.php** e colar em **application\libraries**.

### No *Controller*
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

### Na *View*
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
