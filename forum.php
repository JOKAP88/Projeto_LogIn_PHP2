<?php
     
     session_start();

     if(!isset($_SESSION['user'])){
        echo '<!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="css/main.css">
            <title>MUAI THAI PT - Erro</title>
        </head>
        <body>
            <div class="container-logado">
                <div class="erro">
                Não tem permissão para aceder ao conteudo no site.<br><br>
                <a href="index.php">Voltar á Página Inicial</a>
                </div>
            </div>
            <footer class="rodape">WebMaster: Claudio José Mourão &copy; 2026</footer>
        </body>
        </html>';
        exit();
     }




?>