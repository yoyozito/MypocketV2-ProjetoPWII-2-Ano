<?php

declare(strict_types=1);

require_once __DIR__ . '/config/conexao.php';

try {

    $id = (int) (
        $_POST['id'] ?? 0
    );

    $tipo = $_POST['tipo'] ?? '';

    $valor = (float) (
        $_POST['valor'] ?? 0
    );

    $descricao = trim(
        $_POST['descricao'] ?? ''
    );

    $data = $_POST['data'] ?? '';

    if ($id <= 0) {

        throw new Exception(
            'ID da transação inválido.'
        );
    }

    if ($valor <= 0) {

        throw new Exception(
            'O valor deve ser maior que zero.'
        );
    }

    if ($descricao === '') {

        throw new Exception(
            'A descrição é obrigatória.'
        );
    }

    if ($data === '') {

        throw new Exception(
            'A data é obrigatória.'
        );
    }

    if (
        $tipo !== 'receita' &&
        $tipo !== 'despesa'
    ) {

        throw new Exception(
            'Tipo de transação inválido.'
        );
    }

    if ($tipo === 'despesa') {

        $stmtSaldo = $pdo->prepare(
            "SELECT COALESCE(
                SUM(
                    CASE
                        WHEN tipo = 'receita'
                        THEN valor
                        ELSE -valor
                    END
                ),
                0
            )
            FROM transacoes
            WHERE id <> :id"
        );

        $stmtSaldo->execute([
            ':id' => $id
        ]);

        $saldoDisponivel =
            (float) $stmtSaldo->fetchColumn();

        if (
            $valor > $saldoDisponivel
        ) {

            throw new Exception(
                'Saldo insuficiente para realizar esta despesa.'
            );
        }
    }

    $stmt = $pdo->prepare(
        "UPDATE transacoes

         SET tipo = :tipo,
             valor = :valor,
             descricao = :descricao,
             data = :data

         WHERE id = :id"
    );

    $stmt->execute([
        ':tipo' => $tipo,
        ':valor' => $valor,
        ':descricao' => $descricao,
        ':data' => $data,
        ':id' => $id
    ]);

    header(
        'Location: index.php?mensagem=' .
            urlencode(
                'Transação atualizada com sucesso!'
            )
    );

    exit;
} catch (Exception $e) {

    header(
        'Location: index.php?erro=' .
            urlencode($e->getMessage())
    );

    exit;
}
