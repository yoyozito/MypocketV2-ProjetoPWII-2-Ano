<?php

declare(strict_types=1);

class Custo
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCustoDiario(
        string $data
    ): float {

        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(valor), 0)
             FROM transacoes
             WHERE tipo = 'despesa'
             AND data = :data"
        );

        $stmt->execute([
            ':data' => $data
        ]);

        return (float) $stmt->fetchColumn();
    }

    public function getCustoMensal(
        int $mes,
        int $ano
    ): float {

        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(valor), 0)
             FROM transacoes
             WHERE tipo = 'despesa'
             AND MONTH(data) = :mes
             AND YEAR(data) = :ano"
        );

        $stmt->execute([
            ':mes' => $mes,
            ':ano' => $ano
        ]);

        return (float) $stmt->fetchColumn();
    }

    public function getEntradasMensais(
        int $mes,
        int $ano
    ): float {

        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(valor), 0)
             FROM transacoes
             WHERE tipo = 'receita'
             AND MONTH(data) = :mes
             AND YEAR(data) = :ano"
        );

        $stmt->execute([
            ':mes' => $mes,
            ':ano' => $ano
        ]);

        return (float) $stmt->fetchColumn();
    }

    public function getSaldoMensal(
        int $mes,
        int $ano
    ): float {

        return $this->getEntradasMensais(
            $mes,
            $ano
        ) - $this->getCustoMensal(
            $mes,
            $ano
        );
    }

    public function getMediaCustoDiario(
        int $mes,
        int $ano
    ): float {

        $stmt = $this->pdo->prepare(
            "SELECT
                COALESCE(SUM(valor), 0)
                / NULLIF(COUNT(DISTINCT data), 0)
             FROM transacoes
             WHERE tipo = 'despesa'
             AND MONTH(data) = :mes
             AND YEAR(data) = :ano"
        );

        $stmt->execute([
            ':mes' => $mes,
            ':ano' => $ano
        ]);

        return (float) $stmt->fetchColumn();
    }

    public function getCustosPorDia(
        int $mes,
        int $ano
    ): array {

        $stmt = $this->pdo->prepare(
            "SELECT
                data,

                COALESCE(
                    SUM(
                        CASE
                            WHEN tipo = 'receita'
                            THEN valor
                            ELSE 0
                        END
                    ),
                    0
                ) AS entrada,

                COALESCE(
                    SUM(
                        CASE
                            WHEN tipo = 'despesa'
                            THEN valor
                            ELSE 0
                        END
                    ),
                    0
                ) AS saida,

                COALESCE(
                    SUM(
                        CASE
                            WHEN tipo = 'receita'
                            THEN valor
                            ELSE -valor
                        END
                    ),
                    0
                ) AS diario

             FROM transacoes

             WHERE MONTH(data) = :mes
             AND YEAR(data) = :ano

             GROUP BY data

             ORDER BY data ASC"
        );

        $stmt->execute([
            ':mes' => $mes,
            ':ano' => $ano
        ]);

        $dados = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

        foreach ($dados as &$linha) {

            $linha['entrada'] =
                (float) $linha['entrada'];

            $linha['saida'] =
                (float) $linha['saida'];

            $linha['diario'] =
                (float) $linha['diario'];
        }

        unset($linha);

        return $dados;
    }
}