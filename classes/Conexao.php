<?php

class Conexao
{
    private $host = 'localhost';
    private $db = 'mypocket';
    private $user = 'root';
    private $pass = '';

    public function conectar()
    {
        try {

            $pdo = new PDO(
                "mysql:host=" . $this->host .
                    ";dbname=" . $this->db .
                    ";charset=utf8mb4",
                $this->user,
                $this->pass
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $pdo;
        } catch (PDOException $e) {

            die('Erro ao conectar com o banco: ' .
                $e->getMessage());
        }
    }
}
