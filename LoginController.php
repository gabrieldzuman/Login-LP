require 'LoginModel.php';

class LoginController {
    public function index() {
        include 'index.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $model = new LoginModel();
            if ($model->checkCredentials($email, $password)) {
                header('Location: welcome.php');
            } else {
                include 'index.php';
            }
        }
    }
}

$controller = new LoginController();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $controller->login();
} else {
    $controller->index();
}
