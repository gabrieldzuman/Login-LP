<?php

require 'PasswordResetModel.php';

class PasswordResetController {
    /**
     * Trata a solicitação de redefinição de senha.
     */
    public function requestReset() {
        if (!empty($_POST['email'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            $model = new PasswordResetModel();
            $user = $model->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $model->insertToken($email, $token);

                $resetLink = 'http://seusite.com/reset_password.php?email=' . urlencode($email) . '&token=' . urlencode($token);

                $this->sendResetEmail($email, $resetLink);
            } else {
                $this->displayError('O e-mail fornecido não foi encontrado.');
            }
        } else {
            $this->displayError('Por favor, forneça um e-mail válido.');
        }
    }

    /**
     * Valida o token de redefinição de senha.
     */
    public function validateToken($email, $token) {
        if ($email && $token) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $token = filter_var($token, FILTER_SANITIZE_STRING);

            $model = new PasswordResetModel();
            $tokenData = $model->validateToken($email, $token);

            if ($tokenData) {
                include 'reset_password.php'; 
            } else {
                $this->displayError('Token inválido ou expirado.');
            }
        } else {
            $this->displayError('Parâmetros de e-mail ou token ausentes.');
        }
    }

    /**
     * Redefine a senha do usuário.
     */
    public function resetPassword() {
        if (!empty($_POST['email']) && !empty($_POST['token']) && !empty($_POST['password'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
            $password = $_POST['password']; 

            $model = new PasswordResetModel();
            $tokenData = $model->validateToken($email, $token);

            if ($tokenData) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $model->resetPassword($email, $hashedPassword);

                header('Location: login.php');
                exit;
            } else {
                $this->displayError('Token inválido ou expirado.');
            }
        } else {
            $this->displayError('Todos os campos são obrigatórios.');
        }
    }

    /**
     * Exibe mensagens de erro de maneira segura.
     */
    private function displayError($message) {
        echo '<p style="color: red;">' . htmlspecialchars($message) . '</p>';
    }

    /**
     * Envia o email de redefinição de senha.
     */
    private function sendResetEmail($email, $resetLink) {
        $subject = 'Redefinição de Senha';
        $message = "Olá,\n\nClique no link abaixo para redefinir sua senha:\n\n$resetLink\n\nSe você não solicitou essa alteração, ignore este e-mail.";
        $headers = "From: no-reply@seusite.com";

        if (!mail($email, $subject, $message, $headers)) {
            $this->displayError('Erro ao enviar o e-mail. Por favor, tente novamente mais tarde.');
        }
    }
}

$controller = new PasswordResetController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['token'])) {
        $controller->requestReset();
    } elseif (isset($_POST['token']) && isset($_POST['password'])) {
        $controller->resetPassword();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email'], $_GET['token'])) {
    $controller->validateToken($_GET['email'], $_GET['token']);
} else {
    header('Location: request_reset.php');
    exit;
}
