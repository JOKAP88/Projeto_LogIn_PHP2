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

    <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Primeiro Nome:</label>
            <input type="text" name="primeiro_nome" required>
        </div>
        <div style="flex: 1;">
            <label>Último Nome:</label>
            <input type="text" name="ultimo_nome" required>
        </div>
    </div>

    <label>E-Mail:</label>
    <input type="email" id="text_utilizador" name="text_utilizador" required>

    <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Password:</label>
            <input type="password" name="text_password_1" required>
        </div>
        <div style="flex: 1;">
            <label>Re-escrever Password:</label>
            <input type="password" id="text_password" name="text_password_2" required>
        </div>
    </div>

    <label>Bio:</label>
    <textarea name="bio"></textarea>

    <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Telefone:</label>
            <input type="tel" name="tel">
        </div>
        <div style="flex: 1;">
            <label>Cidade:</label>
            <input type="text" name="cidade">
        </div>
    </div>

    <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required>
        </div>
        <div style="flex: 1;">
            <label>Género:</label>
            <select name="genero" required>
                <option value="">Selecione</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
            </select>
        </div>
    </div>

    <label>Avatar (opcional):</label>
    <input type="file" name="imagem_avatar" accept=".jpg,.jpeg,.png"><br><br>
    <small>(Imagem JPG, máximo 50kb)</small><br><br>

    <button type="submit" name="btn_submit">Registar</button><br><br>
    <a href="index.php">Voltar</a>

   

    </form>
';
}
/*  id_Utilizador    CHAR(36) PRIMARY KEY,
    Nome_Completo   VARCHAR(30) NOT NULL,
    pass            VARCHAR(255) NOT NULL,
    Avatar          VARCHAR(250) DEFAULT NULL,
    Bio             TEXT,
    Tel             TEXT,
    Cidade          TEXT,
    Data_Nascimento DATE NOT NULL,
    Genero         */
function RegistarUtilizador()
{
    $primeiro_nome = trim($_POST['primeiro_nome']);
    $ultimo_nome = trim($_POST['ultimo_nome']);
    $utilizador = trim($_POST['text_utilizador']);
    $password_1 = $_POST['text_password_1'];
    $password_2 = $_POST['text_password_2'];
    $bio = trim($_POST['bio']);
    $tel = trim($_POST['tel']);
    $cidade = trim($_POST['cidade']);
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $avatar = $_FILES['imagem_avatar'];

    $erro = false;
    $maxSize = 50000;
    $nomeAvatar = null;

    //Validacoes 

    if($primeiro_nome == "" || $ultimo_nome == "" || $utilizador == "" || $password_1== "" || $password_2 =="" || $data_nascimento == "" || $genero == ""){
        echo'<div class="erro">Não foram preenchidos todos os campos obrigatórios.</div>';
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
        "mysql:dbname=$base_dados;host=$host;charset=utf8mb4",
            $user,
            $db_password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    //upload dos perfis
    $motor = $ligacao->prepare("SELECT id_Utilizador FROM PerfisUtilizadores WHERE nome_completo = ?");
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
        $sql = "INSERT INTO PerfisUtilizadores(id_Utilizador, primeiro_nome, ultimo_nome, nome_completo, pass, Avatar, Bio, Tel, Cidade, Data_Nascimento, Genero) 
                VALUES(UUID(), :primeiro, :ultimo, :user, :pass, :avatar, :bio, :tel, :cidade, :data_nasc, :genero)";

        $motor = $ligacao->prepare($sql);
        $motor->bindParam(':primeiro', $primeiro_nome);
        $motor->bindParam(':ultimo', $ultimo_nome);
        $motor->bindParam(':user', $utilizador);
        $motor->bindParam(':pass', $passwordEncriptada);
        $motor->bindParam(':avatar', $nomeAvatar);
        $motor->bindParam(':bio', $bio);
        $motor->bindParam(':tel', $tel);
        $motor->bindParam(':cidade', $cidade);
        $motor->bindParam(':data_nasc', $data_nascimento);
        $motor->bindParam(':genero', $genero);
        $motor->execute();

        $ligacao = null;

        echo '
        <div class="novo_registo_sucesso">
        Bem-Vindo ao Site, <strong>'.$primeiro_nome.' '.$ultimo_nome.'</strong><br><br>
        Já pode fazer Login e ver tudo o que se passa á volta do  MUAI THAI.<br><br>
        <a href="index.php">Ir para o login</a>
        </div>
        ';

}


?>