<?php

declare(strict_types=1);

require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/classes/Carteira.php';
require_once __DIR__ . '/classes/Custo.php';

$carteira = new Carteira($pdo);
$custo = new Custo($pdo);

$mensagem = $_GET['mensagem'] ?? '';
$erro = $_GET['erro'] ?? '';

$mesAtual = (int) date('m');
$anoAtual = (int) date('Y');
$dataAtual = date('Y-m-d');

$saldo = $carteira->getSaldo();

$historico =
    $carteira->getHistorico();

$custoDiario =
    $custo->getCustoDiario(
        $dataAtual
    );

$custoMensal =
    $custo->getCustoMensal(
        $mesAtual,
        $anoAtual
    );

$entradasMensais =
    $custo->getEntradasMensais(
        $mesAtual,
        $anoAtual
    );

$saldoMensal =
    $custo->getSaldoMensal(
        $mesAtual,
        $anoAtual
    );

$mediaCustoDiario =
    $custo->getMediaCustoDiario(
        $mesAtual,
        $anoAtual
    );

$custosPorDia =
    $custo->getCustosPorDia(
        $mesAtual,
        $anoAtual
    );

$saldoAcumulado = 0;

foreach ($custosPorDia as &$linha) {

    $saldoAcumulado +=
        $linha['diario'];

    $linha['saldo'] =
        $saldoAcumulado;
}

unset($linha);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        MyPocket - Controle Financeiro
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            border: 0;
        }

        .valor {
            font-size: 1.7rem;
            font-weight: 700;
        }

        .positivo {
            color: #198754;
        }

        .negativo {
            color: #dc3545;
        }
    </style>

</head>

<body>

    <div class="container py-4">

        <div class="mb-4">

            <h1 class="mb-1">
                MyPocket
            </h1>

            <p class="text-muted">
                Controle financeiro pessoal
            </p>

        </div>

        <?php if ($mensagem): ?>

            <div class="alert alert-success">

                <?= htmlspecialchars($mensagem); ?>

            </div>

        <?php endif; ?>

        <?php if ($erro): ?>

            <div class="alert alert-danger">

                <?= htmlspecialchars($erro); ?>

            </div>

        <?php endif; ?>


        <div class="row g-3 mb-4">

            <div class="col-md-6 col-lg-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Saldo atual
                        </div>

                        <div
                            class="valor
    <?= $saldo >= 0
        ? 'positivo'
        : 'negativo'; ?>">

                            R$

                            <?= number_format(
                                $saldo,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Entradas do mês
                        </div>

                        <div class="valor positivo">

                            R$

                            <?= number_format(
                                $entradasMensais,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Gastos do mês
                        </div>

                        <div class="valor negativo">

                            R$

                            <?= number_format(
                                $custoMensal,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Gasto de hoje
                        </div>

                        <div class="valor negativo">

                            R$

                            <?= number_format(
                                $custoDiario,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h5>
                            Saldo do mês
                        </h5>

                        <div
                            class="valor
    <?= $saldoMensal >= 0
        ? 'positivo'
        : 'negativo'; ?>">

                            R$

                            <?= number_format(
                                $saldoMensal,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h5>
                            Média de gasto por dia
                        </h5>

                        <div class="valor">

                            R$

                            <?= number_format(
                                $mediaCustoDiario,
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h4>
                    Nova transação
                </h4>

            </div>

            <div class="card-body">

                <form
                    action="processa.php"
                    method="POST">

                    <div class="row g-3">

                        <div class="col-md-3">

                            <label class="form-label">
                                Tipo
                            </label>

                            <select
                                name="tipo"
                                class="form-select"
                                required>

                                <option value="">
                                    Selecione
                                </option>

                                <option value="receita">
                                    Receita
                                </option>

                                <option value="despesa">
                                    Despesa
                                </option>

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Valor
                            </label>

                            <input
                                type="number"
                                name="valor"
                                step="0.01"
                                min="0.01"
                                class="form-control"
                                required>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Descrição
                            </label>

                            <input
                                type="text"
                                name="descricao"
                                class="form-control"
                                required>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Data
                            </label>

                            <input
                                type="date"
                                name="data"
                                class="form-control"
                                value="<?= $dataAtual; ?>"
                                required>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-success mt-3">
                        Cadastrar transação
                    </button>

                </form>

            </div>

        </div>


        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h4>
                    Resumo diário -
                    <?= date('m/Y'); ?>
                </h4>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Data
                                </th>

                                <th>
                                    Entrada
                                </th>

                                <th>
                                    Saída
                                </th>

                                <th>
                                    Diário
                                </th>

                                <th>
                                    Saldo
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (!$custosPorDia): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center py-4">

                                        Nenhuma movimentação
                                        neste mês.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $custosPorDia
                                    as $linha
                                ): ?>

                                    <tr>

                                        <td>

                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $linha['data']
                                                )
                                            ); ?>

                                        </td>


                                        <td class="positivo">

                                            R$

                                            <?= number_format(
                                                $linha['entrada'],
                                                2,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <td class="negativo">

                                            R$

                                            <?= number_format(
                                                $linha['saida'],
                                                2,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <td
                                            class="<?= $linha['diario'] >= 0
                                                        ? 'positivo'
                                                        : 'negativo'; ?>">

                                            R$

                                            <?= number_format(
                                                $linha['diario'],
                                                2,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <td
                                            class="<?= $linha['saldo'] >= 0
                                                        ? 'positivo'
                                                        : 'negativo'; ?>">

                                            R$

                                            <?= number_format(
                                                $linha['saldo'],
                                                2,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="card shadow-sm">

            <div class="card-header">

                <h4>
                    Histórico de transações
                </h4>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Data
                                </th>

                                <th>
                                    Tipo
                                </th>

                                <th>
                                    Descrição
                                </th>

                                <th>
                                    Valor
                                </th>

                                <th>
                                    Ações
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (!$historico): ?>

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-4">

                                        Nenhuma transação
                                        cadastrada.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $historico as $t
                                ): ?>

                                    <tr>

                                        <td>
                                            <?= $t['id']; ?>
                                        </td>

                                        <td>

                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $t['data']
                                                )
                                            ); ?>

                                        </td>

                                        <td>

                                            <?php if (
                                                $t['tipo'] === 'receita'
                                            ): ?>

                                                <span
                                                    class="badge text-bg-success">
                                                    Receita
                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="badge text-bg-danger">
                                                    Despesa
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $t['descricao']
                                            ); ?>

                                        </td>


                                        <td
                                            class="<?= $t['tipo'] === 'receita'
                                                        ? 'positivo'
                                                        : 'negativo'; ?>">

                                            <?= $t['tipo'] === 'receita'
                                                ? '+'
                                                : '-'; ?>

                                            R$

                                            <?= number_format(
                                                $t['valor'],
                                                2,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <td>

                                            <a
                                                href="editar.php?id=<?= $t['id']; ?>"
                                                class="btn btn-sm btn-primary">
                                                Editar
                                            </a>

                                            <a
                                                href="excluir.php?id=<?= $t['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm(
        'Tem certeza que deseja excluir esta transação?'
    );">
                                                Excluir
                                            </a>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>