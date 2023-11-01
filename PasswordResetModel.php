class PasswordResetModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function requestReset($email) {
        // Gera um token único e insire no banco de dados com o email do usuário
        $token = bin2hex(random_bytes(32));
        $query = "INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }

    public function validateToken($email, $token) {
        // Verifica se o token é válido para o email fornecido
        $query = "SELECT * FROM password_resets WHERE email = :email AND token = :token AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($email, $password) {
        // Atualiza a senha do usuário com base no email
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Após a redefinição de senha, exclui o registro da tabela de redefinição de senha
        $query = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
}
