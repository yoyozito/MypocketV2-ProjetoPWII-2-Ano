<?php

class Transacao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function salvar(
        $idCarteira,
        $idCategoria,
        $data,
        $valor,
        $descricao
    ) {
        $sql = "

            INSERT INTO transacao
            (
                id_carteira,
                id_categoria,
                data,
                valor,
                descricao
            )

            VALUES (?, ?, ?, ?, ?)

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $idCarteira,

            $idCategoria,

            $data,

            $valor,

            $descricao

        ]);
    }

    public function excluir(
        $idTransacao,
        $idUsuario
    ) {

        $sql = "

            SELECT

                c.id_carteira,

                t.data

            FROM transacao t

            INNER JOIN carteira c
            ON t.id_carteira = c.id_carteira

            WHERE

                t.id_transacao = ?

                AND c.id_usuario = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $idTransacao,

            $idUsuario

        ]);

        $dados = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        if ($dados) {

            $sql = "

                DELETE FROM transacao

                WHERE id_transacao = ?

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                $idTransacao
            ]);

            return $dados;
        }


        return false;
    }
}
