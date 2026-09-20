<?php

declare(strict_types=1);

require_once __DIR__ . '/config/conexao.php';

$id = (int) (
    $_GET['id'] ?? 0
);

if ($id <= 0) {

    header(
        'Location: index.php'
    );

    exit;
}

$stmt = $pdo->prepare(
    "SELECT *
     FROM transacoes
     WHERE id = :id"
);

$stmt->execute([
    ':id' => $id
]);

$transacao = $stmt->fetch(
    PDO::FETCH_ASSOC
);

if (!$transacao) {

    header(
        'Location: index.php'
    );

    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Editar Transação - MyPocket</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow-sm">

            <div class="card-header">

                <h3>Editar Transação</h3>

            </div>

            <div class="card-body">

                <form
                    action="atualizar.php"
                    method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $transacao['id']; ?>">

                    <div class="mb-3">

                        <label class="form-label">
                            Tipo
                        </label>

                        <select
                            name="tipo"
                            class="form-select"
                            required>

                            <option
                                value="receita"
                                <?= $transacao['tipo'] === 'receita'
                                    ? 'selected'
                                    : ''; ?>>
                                Receita
                            </option>

                            <option
                                value="despesa"
                                <?= $transacao['tipo'] === 'despesa'
                                    ? 'selected'
                                    : ''; ?>>
                                Despesa
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Descrição
                        </label>

                        <input
                            type="text"
                            name="descricao"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                        $transacao['descricao']
                                    ); ?>"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Valor
                        </label>

                        <input
                            type="number"
                            name="valor"
                            step="0.01"
                            min="0.01"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                        (string) $transacao['valor']
                                    ); ?>"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Data
                        </label>

                        <input
                            type="date"
                            name="data"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                        $transacao['data']
                                    ); ?>"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Salvar Alterações
                    </button>

                    <a
                        href="index.php"
                        class="btn btn-secondary">
                        Cancelar
                    </a>

                </form>

            </div>

        </div>

    </div>

</body>

</html>