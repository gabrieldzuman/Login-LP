class LoginModel {
    private $db; // Conexão com o banco de dados

    public function __construct($db) {
        $this->db = $db;
    }

    public function checkCredentials($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado e se a senha está correta
        if ($user && password_verify($password, $user['password'])) {
            return true; // Credenciais válidas
        }

        return false; // Credenciais inválidas
    }
}
