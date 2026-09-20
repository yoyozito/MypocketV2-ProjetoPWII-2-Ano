<?php

declare(strict_types=1);

require_once __DIR__ . '/config/conexao.php';

try {

    $id = (int) (
        $_GET['id'] ?? 0
    );

    if ($id <= 0) {

        throw new Exception(
            'ID da transação inválido.'
        );
    }

    $stmt = $pdo->prepare(
        "DELETE FROM transacoes
         WHERE id = :id"
    );

    $stmt->execute([
        ':id' => $id
    ]);

    header(
        'Location: index.php?mensagem=' .
            urlencode(
                'Transação excluída com sucesso!'
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
