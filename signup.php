<?php

session_start();

include 'cabecalho.php';

//verificar se foram inseridos dadso de utilizador

if(isset($_POST['btn_submit'])){
    RegistarUtilizador();
}
else{
    ApresentarFormulario();
}

include 'rodape.php';

//construcao de funcoes.

//funcao ApresentarFormulario
function ApresentarFormulario(){
echo'
    <form class="form_signup" method="POST" action="signup.php" enctype="multipart/form-data">
    <h3>Signup</h3>
    <hr>

    <label>E-Mail:</label>
    <input type="email" id="text_utilizador" name="text_utilizador" required>

    <label>Password:</label>
    <input type="password" name="text_password_1" required>

    <label>Re-escrever Password:</label>
    <input type="password" id="text_password "name="text_password_2" required>

    <label>Avatar (opcional):</label>
    <input type="file" name="imagem_avatar" accept=".jpg,.jpeg,.png"><br><br>
    <small>(Imagem JPG, máximo 50kb)</small><br><br>

    <button type="submit" name="btn_submit">Registar</button><br><br>
    <a href="index.php">Voltar</a>

    </form>
';
}

function RegistarUtilizador()
{
    $utilizador = trim($_POST['text_utilizador']);
    $password_1 = $_POST['text_password_1'];
    $password_2 = $_POST['text_password_2'];
    $avatar = $_FILES['imagem_avatar'];

    $erro = false;
    $maxSize = 50000;
    $nomeAvatar = null;

    //Validacoes 

    if($utilizador == "" || $password_1== "" || $password_2 ==""){
        echo'<div class="erro">Não foram prenshidos todos os campos.</div>';
        $erro = true;
    }
    else if($password_1 != $password_2){
        echo'<div class="erro">As passwords não coincidem.</div>';
        $erro = true;
    }

    //validar o avatar

    if($avatar['name'] !=""){
        $ext = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
        
        if($ext != "jpg" && $ext != "jpeg" && $ext != "png"){
            echo'<div class="erro">Formato de imagem inválido. Use apenas JPG, JPEG ou PNG.</div>';
            $erro = true;
        }
        else if($avatar['size'] > $maxSize){
            echo'<div class="erro">O Avatar excede os 50kb.</div>';
            $erro = true;
        }
    }

    if($erro){
        ApresentarFormulario();
        return;
    }

    //ligacao á BD

    include 'config.php';
    $ligacao = new PDO(
        "mysql:dbname=$base_dados;host=$host;charset=utf8",
            $user,
            $db_password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    //upload dos perfis
    $motor = $ligacao->prepare("SELECT id_Utilizador FROM PerfisUtilizadores WHERE Nome_Completo = ?");
    $motor->execute([$utilizador]);
    
    if($motor->rowcount() > 0){
        echo'
            <div class="erro">
            Usuário já Existente
            </div>
            ';
        ApresentarFormulario();
    return;
    }

    if($avatar['name'] != "")
        {
            $nomeAvatar = uniqid() . '.jpg';
            move_uploaded_file($avatar['tmp_name'], "img/avatar/".$nomeAvatar);
        }

        $passwordEncriptada = password_hash($password_1, PASSWORD_DEFAULT);

        // MySQL: Usar UUID() automático, implementar ainda os campos de not null
        $sql = "INSERT INTO PerfisUtilizadores(id_Utilizador, Nome_Completo, pass, Avatar) 
                VALUES(UUID(), :user, :pass, :avatar)";

        $motor = $ligacao->prepare($sql);
        $motor->bindParam(':user', $utilizador);
        $motor->bindParam(':pass', $passwordEncriptada);
        $motor->bindParam(':avatar', $nomeAvatar);
        $motor->execute();

        $ligacao = null;

        echo '
        <div class="novo_registo_sucesso">
        Bem-Vindo ao Site, <strong>'.$utilizador.'</strong><br><br>
        Já pode fazer Login e ver tudo o que se passa á volta do  MUAI THAI.<br><br>
        <a href="index.php">Ir para o login</a>
        </div>
        ';

}


?>