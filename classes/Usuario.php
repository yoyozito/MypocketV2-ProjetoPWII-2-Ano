<?php

class Usuario
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar($nome, $email, $senha)
    {
        $hash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        $sql = "
            INSERT INTO usuario
            (
                nome,
                email,
                senha
            )
            VALUES (?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $nome,
            $email,
            $hash
        ]);

        return $this->pdo->lastInsertId();
    }

    public function login($email, $senha)
    {
        $sql = "
            SELECT *
            FROM usuario
            WHERE email = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $email
        ]);

        $usuario = $stmt->fetch(
            PDO::FETCH_ASSOC
        );

        if (
            $usuario &&
            password_verify(
                $senha,
                $usuario['senha']
            )
        ) {

            $_SESSION['id_usuario'] =
                $usuario['id_usuario'];

            $_SESSION['nome'] =
                $usuario['nome'];

            return true;
        }

        return false;
    }

    public function logout()
    {
        session_unset();

        session_destroy();
    }
}
