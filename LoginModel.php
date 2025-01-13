<?php

class LoginModel {
    private $db; 

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Verifica as credenciais do usuário.
     * 
     * @param string $email O e-mail do usuário.
     * @param string $password A senha do usuário.
     * @return bool Retorna true se as credenciais forem válidas, false caso contrário.
     * @throws Exception Caso ocorra algum erro de banco de dados.
     */
    public function checkCredentials($email, $password) {
        try {
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return true; 
            }
        } catch (PDOException $e) {
            throw new Exception('Erro ao verificar credenciais: ' . $e->getMessage());
        }

        return false; 
    }
}
