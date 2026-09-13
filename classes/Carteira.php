<?php

class Carteira
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }



    public function criarCarteira($idUsuario)
    {
        $sql = "
            INSERT INTO carteira
            (
                id_usuario,
                saldo
            )
            VALUES (?, 0)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idUsuario
        ]);
    }

    public function buscarCarteira($idUsuario)
    {
        $sql = "
            SELECT *
            FROM carteira
            WHERE id_usuario = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idUsuario
        ]);

        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }

    public function atualizarSaldo($idCarteira)
    {
        $sql = "

            SELECT

                COALESCE(
                    SUM(
                        CASE

                            WHEN c.tipo = 'Receita'
                            THEN t.valor

                            ELSE -t.valor

                        END
                    ),
                    0
                ) AS saldo

            FROM transacao t

            INNER JOIN categoria c
            ON t.id_categoria = c.id_categoria

            WHERE t.id_carteira = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idCarteira
        ]);

        $saldo = $stmt->fetchColumn();


        $sql = "
            UPDATE carteira
            SET saldo = ?
            WHERE id_carteira = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $saldo,
            $idCarteira
        ]);
    }


    public function atualizarResumoMensal(
        $idCarteira,
        $mes,
        $ano
    ) {
        $sql = "

            SELECT

                COALESCE(
                    SUM(
                        CASE

                            WHEN c.tipo = 'Receita'
                            THEN t.valor

                            ELSE 0

                        END
                    ),
                    0
                ) AS entradas,


                COALESCE(
                    SUM(
                        CASE

                            WHEN c.tipo = 'Despesa'
                            THEN t.valor

                            ELSE 0

                        END
                    ),
                    0
                ) AS saidas


            FROM transacao t

            INNER JOIN categoria c
            ON t.id_categoria = c.id_categoria

            WHERE t.id_carteira = ?

            AND MONTH(t.data) = ?

            AND YEAR(t.data) = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idCarteira,
            $mes,
            $ano
        ]);

        $dados = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        $saldoFinal =
            $dados['entradas'] -
            $dados['saidas'];


        $desempenho = 0;


        if ($dados['entradas'] > 0) {

            $desempenho =
                (
                    $saldoFinal /
                    $dados['entradas']
                ) * 100;
        }

        $sql = "

            SELECT id_resumo

            FROM resumo_mensal

            WHERE id_carteira = ?

            AND mes = ?

            AND ano = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idCarteira,
            $mes,
            $ano
        ]);

        $idResumo =
            $stmt->fetchColumn();


        // ATUALIZAR

        if ($idResumo) {

            $sql = "

                UPDATE resumo_mensal

                SET

                    total_entradas = ?,

                    total_saidas = ?,

                    saldo_final = ?,

                    desempenho = ?

                WHERE id_resumo = ?

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([

                $dados['entradas'],

                $dados['saidas'],

                $saldoFinal,

                $desempenho,

                $idResumo

            ]);
        }

        // CRIAR

        else {

            $sql = "

                INSERT INTO resumo_mensal
                (
                    id_carteira,
                    mes,
                    ano,
                    total_entradas,
                    total_saidas,
                    saldo_final,
                    desempenho
                )

                VALUES (?, ?, ?, ?, ?, ?, ?)

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([

                $idCarteira,

                $mes,

                $ano,

                $dados['entradas'],

                $dados['saidas'],

                $saldoFinal,

                $desempenho

            ]);
        }
    }

    public function moeda($valor)
    {
        return 'R$ ' .
            number_format(
                (float)$valor,
                2,
                ',',
                '.'
            );
    }
}
