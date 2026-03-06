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
            <div class="container_logado">
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

     // If logged in, show the forum
     echo '<!DOCTYPE html>
     <html lang="pt-br">
     <head>
         <meta charset="UTF-8">
         <link rel="stylesheet" type="text/css" href="css/main.css">
         <link href="https://fonts.googleapis.com/css2?family=Gravitas+One&display=swap" rel="stylesheet">
         <title>MUAI THAI PT - Fórum</title>
     </head>
     <body>
     ';

     echo '
     <nav class="navbar">
         <div class="logo">MUAY THAI</div>
         <ul class="nav_links">
             <li><a href="#home">Início</a></li>
             <li><a href="#eventos">Eventos</a></li>
             <li><a href="#lutadores">Lutadores</a></li>
             <li><a href="#ginasios">Ginásios</a></li>
             <li><a href="#equipas">Equipas</a></li>
             <li><a href="#noticias">Notícias</a></li>
         </ul>
     </nav>

     <header id="home" class="lutador_lp">
         <div class="lutador_lp_content">
             <h1>MUAY THAI</h1>
             <p>A Arte das Oito Armas</p>

             <div class="lutador_lp_btns">
                 <a href="#eventos" class="btn btn_primary">Ver Eventos</a>
                 <a href="#lutadores" class="btn btn_secondary">Ver Lutadores</a>
             </div>
         </div>
     </header>

     <section id="eventos" class="section">
         <h2>Próximos Eventos</h2>
         <div class="grid_container" id="container_eventos">
             </div>
     </section>

     <section id="lutadores" class="section bg_dark">
         <h2>Ranking de Lutadores</h2>
         <div class="table_container">
             <table>
                 <thead>
                     <tr>
                         <th>Ranking</th>
                         <th>Nome</th>
                         <th>Peso</th>
                         <th>V/D/E</th>
                         <th>Equipa</th>
                     </tr>
                 </thead>
                 <tbody id="lista_lutadores">
                     </tbody>
             </table>
         </div>
     </section>

     <section id="ginasios" class="section">
         <h2>Ginásios</h2>
         <div class="grid_container" id="container_ginasios">
             </div>
     </section>

     <section id="equipas" class="section bg_dark">
         <h2>Equipas</h2>
         <div class="grid_container" id="container_equipas">
             </div>
     </section>

     <section id="noticias" class="section">
         <h2>Últimas Notícias</h2>
         <div class="grid_container" id="container_noticias">
             </div>
     </section>

     <footer class="rodape">WebMaster: Claudio José Mourão &copy; 2026</footer>
     </body>
     </html>
     ';

?>