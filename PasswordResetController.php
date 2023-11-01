require 'PasswordResetModel.php';

class PasswordResetController {
    public function requestReset() {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Verifica se o email existe no banco de dados
        $model = new PasswordResetModel();
        $user = $model->getUserByEmail($email);

        if ($user) {
            // Gera um token único
            $token = bin2hex(random_bytes(32));

            // Insire o token no banco de dados
            $model->insertToken($email, $token);

            // Envia um email com o link de redefinição de senha
            $resetLink = 'http://seusite.com/reset_password.php?email=' . $email . '&token=' . $token;
            // Envia o link de redefinição de senha por email
            // Você pode usar uma biblioteca de envio de email, como PHPMailer, para fazer isso
        } else {
            // O email não foi encontrado no banco de dados, talvez você queira exibir uma mensagem de erro
        }
    }
}


    public function validateToken($email, $token) {
    if ($email && $token) {
        // Verifica se o token é válido no banco de dados
        $model = new PasswordResetModel();
        $tokenData = $model->validateToken($email, $token);

        if ($tokenData) {
            // Se o token é válido, mostre a página de redefinição de senha
            include 'reset_password.php';
        } else {
            // Se o token não é válido, exiba uma mensagem de erro
        }
    } else {
        // Se os parâmetros de email e token não foram fornecidos, redirecione para a página de solicitação de redefinição de senha
    }
}


    public function resetPassword() {
    if (isset($_POST['email']) && isset($_POST['token']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $token = $_POST['token'];
        $password = $_POST['password'];

        // Verifica se o token é válido no banco de dados
        $model = new PasswordResetModel();
        $tokenData = $model->validateToken($email, $token);

        if ($tokenData) {
            // Se o token é válido, redefina a senha
            $model->resetPassword($email, $password);
            // Redirecione o usuário para a página de login ou outra página apropriada
            header('Location: login.php');
        } else {
            // Se o token não é válido, exiba uma mensagem de erro
        }
    }
}

}

$controller = new PasswordResetController();

if (isset($_POST['email'])) {
    $controller->requestReset();
} elseif (isset($_POST['token']) && isset($_POST['password'])) {
    $controller->resetPassword();
} else {
    $controller->validateToken($_GET['email'], $_GET['token']);
}
