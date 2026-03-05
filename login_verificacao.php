<?php

session_start();

if (isset($_SESSION['user'])) {
    echo '
    <div class="mensagem">
    Já se encontra logado no site!<br><br>
    <a href="forum.php">Avançar<a/>
    </div>';

    include 'rodape.php';
    exit;
}

include 'cabecalho.php';

// verifica se o usario tem posts

if (empty($_POST['text_utilizador']) || empty($_POST['text_password'])) {

    echo '<div class="Erro">
        Deve Prensher todos os campos.<br><br>
        <a href="index.php">Voltar</a>
        </div>';
    include 'rodape.php';
    exit;
}

$utilizador  = trim($_POST['text_utilizador']);
$password_utilizador = $_POST['text_password'];


//ligacao á Base De Dados 

include 'config.php';

try {

    $ligacao = new PDO(
        "mysql:host=$host;dbname=$base_dados;charset=utf8",
        $user,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $motor = $ligacao->prepare("SELECT id_Utilizador, Nome_Completo, pass, Avatar from PerfisUtilizadores where Nome_Completo = ?");
    $motor->execute([$utilizador]);
    $dados_user = $motor->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    echo '
      <div class="erro">
        Erro de ligacao á base de dados.
        </div>
        ';
    include 'rodape.php';
    exit;
        }

if (!$dados_user || !password_verify($password_utilizador, $dados_user['pass'])) {
    echo '<div class="erro">
                Dados de login inválidos.<br><br>
                <a href="index.php>Tente Novamente</a>
                </div>
                ';
    include 'rodape.php';
    exit;
}

//login Valido
$_SESSION['id_user'] = $dados_user['id_Utilizador'];
$_SESSION['user'] = $dados_user['Nome_Completo'];
$_SESSION['avatar'] = $dados_user['Avatar'];

echo '
        <div class="login_sucesso">
        Bem-Vindo, <strong>' . $dados_user['Nome_Completo'] . '</strong><br><br>
        <a href="forum.php">Continuar</a>
        </div>
        ';

include 'rodape.php';
