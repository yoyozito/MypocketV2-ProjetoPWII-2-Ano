<?php

session_start();

require 'config.php';

require_once 'classes/Usuario.php';


$usuarioClasse =
    new Usuario($pdo);


$erro = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email =
        trim($_POST['email']);


    $senha =
        $_POST['senha'];


    $login =
        $usuarioClasse->login(
            $email,
            $senha
        );


    if ($login) {

        header(
            'Location: index.php'
        );

        exit;
    } else {

        $erro =
            'E-mail ou senha incorretos.';
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>
        Login - MyPocket
    </title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


    <h1>
        MyPocket
    </h1>


    <h3>
        Controle Financeiro
    </h3>


    <?php if (isset($_GET['cadastro'])) { ?>

        <p>

            Cadastro realizado com sucesso.
            Faça login.

        </p>

    <?php } ?>


    <?php if ($erro != '') { ?>

        <p>

            <?= $erro ?>

        </p>

    <?php } ?>


    <form method="post">


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

            Entrar

        </button>


    </form>


    <br>


    <a href="cadastro.php">

        Criar uma conta

    </a>


</body>

</html>