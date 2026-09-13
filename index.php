<?php

session_start();


if (!isset($_SESSION['id_usuario'])) {

    header('Location: login.php');

    exit;
}


require 'config.php';

require_once 'classes/Carteira.php';

require_once 'classes/Transacao.php';


$carteiraClasse =
    new Carteira($pdo);


$transacaoClasse =
    new Transacao($pdo);


$idUsuario =
    $_SESSION['id_usuario'];


$carteira =
    $carteiraClasse->buscarCarteira(
        $idUsuario
    );


$idCarteira =
    $carteira['id_carteira'];


if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['acao'])
    &&
    $_POST['acao'] === 'salvar'
) {

    $idCategoria =
        $_POST['id_categoria'] ?? '';


    $descricao =
        trim(
            $_POST['descricao'] ?? ''
        );


    $valor =
        $_POST['valor'] ?? 0;


    $data =
        $_POST['data'] ?? '';


    if (

        $idCategoria != ''

        &&

        $descricao != ''

        &&

        $valor > 0

        &&

        $data != ''

    ) {


        $transacaoClasse->salvar(

            $idCarteira,

            $idCategoria,

            $data,

            $valor,

            $descricao

        );


        $carteiraClasse->atualizarSaldo(
            $idCarteira
        );


        $mes =
            date(
                'n',
                strtotime($data)
            );


        $ano =
            date(
                'Y',
                strtotime($data)
            );


        $carteiraClasse
            ->atualizarResumoMensal(

                $idCarteira,

                $mes,

                $ano

            );


        header(
            'Location: index.php?ok=1'
        );

        exit;
    } else {

        header(
            'Location: index.php?erro=1'
        );

        exit;
    }
}


if (
    isset($_GET['acao'])
    &&
    $_GET['acao'] === 'excluir'
    &&
    isset($_GET['id'])
) {

    $idTransacao =
        $_GET['id'];


    $dados =
        $transacaoClasse->excluir(

            $idTransacao,

            $idUsuario

        );


    if ($dados) {


        $carteiraClasse->atualizarSaldo(

            $dados['id_carteira']

        );


        $mes =
            date(

                'n',

                strtotime(
                    $dados['data']
                )

            );


        $ano =
            date(

                'Y',

                strtotime(
                    $dados['data']
                )

            );


        $carteiraClasse
            ->atualizarResumoMensal(

                $dados['id_carteira'],

                $mes,

                $ano

            );
    }


    header(
        'Location: index.php'
    );

    exit;
}


$categorias =
    $pdo

    ->query("

        SELECT *

        FROM categoria

        ORDER BY tipo, nome

    ")

    ->fetchAll(
        PDO::FETCH_ASSOC
    );


$sql = "

    SELECT

        t.*,

        c.nome AS categoria,

        c.tipo

    FROM transacao t

    INNER JOIN categoria c

    ON t.id_categoria =
    c.id_categoria

    WHERE t.id_carteira = ?

    ORDER BY

        t.data DESC,

        t.id_transacao DESC

";


$stmt =
    $pdo->prepare($sql);


$stmt->execute([

    $idCarteira

]);


$lista =
    $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );


$entradas = 0;

$saidas = 0;


foreach ($lista as $item) {

    if (
        $item['tipo'] === 'Receita'
    ) {

        $entradas +=
            $item['valor'];
    } else {

        $saidas +=
            $item['valor'];
    }
}


$saldo =
    $entradas - $saidas;

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        MyPocket
    </title>


    <link rel="stylesheet" href="style.css">

</head>


<body>


    <header class="topo">

        <div class="topo-conteudo">


            <div class="logo">

                MyPocket

            </div>


            <div class="usuario">

                Olá,

                <?= htmlspecialchars(
                    $_SESSION['nome']
                ) ?>


                <a href="logout.php">

                    Sair

                </a>

            </div>


        </div>

    </header>



    <main class="conteudo">


        <h1>

            MyPocket

        </h1>


        <div class="saldo">


            Saldo Atual:


            <span>

                <?= $carteiraClasse->moeda(
                    $saldo
                ) ?>

            </span>


        </div>


        <div class="resumos">


            <div class="card">

                Entradas


                <strong class="receita">

                    <?= $carteiraClasse->moeda(
                        $entradas
                    ) ?>

                </strong>

            </div>



            <div class="card">

                Saídas


                <strong class="despesa">

                    <?= $carteiraClasse->moeda(
                        $saidas
                    ) ?>

                </strong>

            </div>



            <div class="card">

                Transações


                <strong>

                    <?= count($lista) ?>

                </strong>

            </div>



            <div class="card">

                Saldo


                <strong>

                    <?= $carteiraClasse->moeda(
                        $saldo
                    ) ?>

                </strong>

            </div>


        </div>

        <?php if (isset($_GET['ok'])) { ?>


            <div class="mensagem sucesso">

                Transação cadastrada com sucesso.

            </div>


        <?php } ?>



        <?php if (isset($_GET['erro'])) { ?>


            <div class="mensagem erro">

                Preencha os dados corretamente.

            </div>


        <?php } ?>

        <div class="caixa">


            <div class="titulo-caixa">

                Nova Transação

            </div>


            <form method="post">


                <input
                    type="hidden"
                    name="acao"
                    value="salvar">



                <label>

                    Categoria

                </label>


                <select
                    name="id_categoria"
                    required>


                    <option value="">

                        Selecione

                    </option>


                    <?php foreach ($categorias as $categoria) { ?>


                        <option
                            value="<?= $categoria['id_categoria'] ?>">


                            <?= htmlspecialchars(
                                $categoria['nome']
                            ) ?>


                            -

                            <?= $categoria['tipo'] ?>


                        </option>


                    <?php } ?>


                </select>



                <label>

                    Descrição

                </label>


                <input
                    type="text"
                    name="descricao"
                    maxlength="150"
                    required>



                <label>

                    Valor

                </label>


                <input
                    type="number"
                    name="valor"
                    step="0.01"
                    min="0.01"
                    required>



                <label>

                    Data

                </label>


                <input
                    type="date"
                    name="data"
                    value="<?= date('Y-m-d') ?>"
                    required>



                <button type="submit">

                    Cadastrar

                </button>


            </form>


        </div>


        <div class="caixa">


            <div class="titulo-caixa">

                Extrato

            </div>


            <div class="tabela-area">


                <table>


                    <thead>


                        <tr>

                            <th>Data</th>

                            <th>Descrição</th>

                            <th>Categoria</th>

                            <th>Tipo</th>

                            <th>Valor</th>

                            <th>Ação</th>

                        </tr>


                    </thead>



                    <tbody>


                        <?php if (!$lista) { ?>


                            <tr>


                                <td colspan="6">

                                    Nenhuma transação cadastrada.

                                </td>


                            </tr>


                        <?php } ?>



                        <?php foreach ($lista as $item) { ?>


                            <tr>


                                <td>

                                    <?= date(

                                        'd/m/Y',

                                        strtotime(
                                            $item['data']
                                        )

                                    ) ?>

                                </td>



                                <td>

                                    <?= htmlspecialchars(

                                        $item['descricao']

                                    ) ?>

                                </td>



                                <td>

                                    <?= htmlspecialchars(

                                        $item['categoria']

                                    ) ?>

                                </td>



                                <td

                                    class="<?= strtolower(

                                                $item['tipo']

                                            ) ?>">


                                    <?= $item['tipo'] ?>


                                </td>



                                <td

                                    class="<?= strtolower(

                                                $item['tipo']

                                            ) ?>">


                                    <?= $carteiraClasse->moeda(

                                        $item['valor']

                                    ) ?>


                                </td>



                                <td class="acoes">


                                    <a

                                        href="index.php?acao=excluir&id=<?= $item['id_transacao'] ?>"

                                        onclick="return confirm('Deseja excluir esta transação?')">

                                        Excluir

                                    </a>


                                </td>


                            </tr>


                        <?php } ?>


                    </tbody>


                </table>


            </div>


        </div>


    </main>


</body>

</html>