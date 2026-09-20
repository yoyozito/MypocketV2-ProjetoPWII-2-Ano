<?php

declare(strict_types=1);

require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/classes/Carteira.php';
require_once __DIR__ . '/classes/Receita.php';
require_once __DIR__ . '/classes/Despesa.php';

$carteira = new Carteira($pdo);

try {

    $tipo = $_POST['tipo'] ?? '';

    $valor = (float) (
        $_POST['valor'] ?? 0
    );

    $descricao = trim(
        $_POST['descricao'] ?? ''
    );

    $data = $_POST['data'] ?? '';

    if ($valor <= 0) {

        throw new Exception(
            'O valor da transação deve ser maior que zero.'
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

    if ($tipo === 'receita') {

        $transacao = new Receita(
            $valor,
            $descricao,
            $data
        );
    } elseif ($tipo === 'despesa') {

        $transacao = new Despesa(
            $valor,
            $descricao,
            $data
        );
    } else {

        throw new Exception(
            'Tipo de transação inválido.'
        );
    }

    $carteira->adicionarTransacao(
        $transacao
    );

    header(
        'Location: index.php?mensagem=' .
            urlencode(
                'Transação cadastrada com sucesso!'
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
