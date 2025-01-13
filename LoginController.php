<?php

require 'LoginModel.php';

class LoginController {
    public function index($error = '') {
        include 'index.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                return $this->index('Por favor, preencha todos os campos.');
            }

            $model = new LoginModel();

            try {
                if ($model->checkCredentials($email, $password)) {
                    header('Location: welcome.php');
                    exit; 
                } else {
                    return $this->index('E-mail ou senha incorretos.');
                }
            } catch (Exception $e) {
                return $this->index('Ocorreu um erro interno. Tente novamente mais tarde.');
            }
        }
        
        $this->index();
    }
}

$controller = new LoginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->index();
}
?>
