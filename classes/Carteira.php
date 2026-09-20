<?php

declare(strict_types=1);

require_once __DIR__ . '/Receita.php';
require_once __DIR__ . '/Despesa.php';

class Carteira
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function adicionarTransacao(Transacao $transacao): void
    {
        if (
            $transacao instanceof Despesa &&
            $transacao->getValor() > $this->getSaldo()
        ) {
            throw new Exception(
                'Saldo insuficiente para realizar esta despesa.'
            );
        }

        $tipo = $transacao instanceof Receita
            ? 'receita'
            : 'despesa';

        $stmt = $this->pdo->prepare(
            "INSERT INTO transacoes
            (tipo, valor, descricao, data)
            VALUES
            (:tipo, :valor, :descricao, :data)"
        );

        $stmt->execute([
            ':tipo' => $tipo,
            ':valor' => $transacao->getValor(),
            ':descricao' => $transacao->getDescricao(),
            ':data' => $transacao->getData()
        ]);
    }

    public function getSaldo(): float
    {
        $sql = "SELECT COALESCE(
                    SUM(
                        CASE
                            WHEN tipo = 'receita'
                            THEN valor
                            ELSE -valor
                        END
                    ),
                    0
                )
                FROM transacoes";

        return (float) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    public function getHistorico(): array
    {
        $stmt = $this->pdo->query(
            "SELECT *
             FROM transacoes
             ORDER BY data DESC, id DESC"
        );

        $transacoes = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

        foreach ($transacoes as &$transacao) {

            $transacao['id'] =
                (int) $transacao['id'];

            $transacao['valor'] =
                (float) $transacao['valor'];
        }

        unset($transacao);

        return $transacoes;
    }
}
