<?php
     
     session_start();

     if(!isset($_SESSION['user'])){
        include 'cabecalho.php';
        echo '
        <div class="erro">
        Não tem permissão para aceder ao conteudo no site.<br><br>
        <a href="index.php">Voltar á Página Incial</a>
        </div>
        ';
        include 'rodape.php';
        exit();
     }




?>