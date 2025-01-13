<?php

class PasswordResetModel {
    private $db;

    /**
     * Construtor da classe.
     *
     * @param PDO $db Instância do banco de dados.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Solicita a redefinição de senha.
     *
     * @param string $email Email do usuário.
     * @return string Retorna o token gerado.
     * @throws Exception Se ocorrer um erro ao gerar o token ou ao executar a query.
     */
    public function requestReset($email) {
        $token = bin2hex(random_bytes(32)); 
        $query = "
            INSERT INTO password_resets (email, token, created_at)
            VALUES (:email, :token, NOW())
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $token;
        } else {
            throw new Exception('Erro ao solicitar a redefinição de senha.');
        }
    }

    /**
     * Valida um token de redefinição de senha.
     *
     * @param string $email Email do usuário.
     * @param string $token Token de redefinição.
     * @return array|false Dados do token se válido, ou false caso contrário.
     */
    public function validateToken($email, $token) {
        $query = "
            SELECT * FROM password_resets
            WHERE email = :email
              AND token = :token
              AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Redefine a senha do usuário.
     *
     * @param string $email Email do usuário.
     * @param string $password Nova senha do usuário.
     * @throws Exception Se ocorrer um erro ao redefinir a senha.
     */
    public function resetPassword($email, $password) {
        try {
            $this->db->beginTransaction();

            $query = "
                UPDATE users
                SET password = :password
                WHERE email = :email
            ";
            $stmt = $this->db->prepare($query);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            $query = "
                DELETE FROM password_resets
                WHERE email = :email
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Erro ao redefinir a senha: ' . $e->getMessage());
        }
    }
}
