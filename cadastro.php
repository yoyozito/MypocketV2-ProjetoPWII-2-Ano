<?php

session_start();

require 'config.php';

require_once 'classes/Usuario.php';

require_once 'classes/Carteira.php';


$usuarioClasse = new Usuario($pdo);

$carteiraClasse = new Carteira($pdo);


$erro = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome =
        trim($_POST['nome']);


    $email =
        trim($_POST['email']);


    $senha =
        $_POST['senha'];


    if (
        $nome == '' ||
        $email == '' ||
        $senha == ''
    ) {

        $erro =
            'Preencha todos os campos.';
    } else {

        try {


            $idUsuario =
                $usuarioClasse->cadastrar(
                    $nome,
                    $email,
                    $senha
                );

            $carteiraClasse->criarCarteira(
                $idUsuario
            );


            header(
                'Location: login.php?cadastro=1'
            );

            exit;
        } catch (PDOException $e) {

            $erro =
                'Não foi possível cadastrar.';
        }
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>
        Cadastro - MyPocket
    </title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


    <h1>
        MyPocket
    </h1>


    <h3>
        Criar Conta
    </h3>


    <?php if ($erro != '') { ?>

        <p>

            <?= $erro ?>

        </p>

    <?php } ?>


    <form method="post">


        <label>
            Nome
        </label>

        <br>

        <input
            type="text"
            name="nome"
            required>


        <br><br>


        <label>
            E-mail
        </label>

        <br>

        <input
            type="email"
            name="email"
            required>


        <br><br>


        <label>
            Senha
        </label>

        <br>

        <input
            type="password"
            name="senha"
            required>


        <br><br>


        <button type="submit">

            Cadastrar

        </button>


    </form>


    <br>


    <a href="login.php">

        Já tenho uma conta

    </a>


</body>

</html>