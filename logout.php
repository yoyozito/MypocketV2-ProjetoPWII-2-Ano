<?php

session_start();

require 'config.php';

require_once 'classes/Usuario.php';


$usuarioClasse =
    new Usuario($pdo);


$usuarioClasse->logout();


header(
    'Location: login.php'
);

exit;

?>