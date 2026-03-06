<?php

session_start();

if(isset($_SESSION['user'])){
    echo '<!DOCTYPE html>
    <html lang="pt-pt">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        
        <title>MUAI THAI PT</title>
    </head>
    <body>
        <div class="container_logado">
            <div class="mensagem">
            <strong>'.$_SESSION['user'].'</strong> Já se encontra logado no site.<br><br>
            <a href="forum.php">Avançar para o Fórum</a>
            </div>
        </div>
        <footer class="rodape">WebMaster: Claudio José Mourão &copy; 2026</footer>
    </body>
    </html>';
    exit();
}

include 'login.php';
include 'rodape.php';


?>