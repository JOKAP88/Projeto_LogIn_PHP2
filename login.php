<?php

include 'cabecalho.php';

echo ' 
        <form class="form_login" method="POST" action="login_verificacao.php">
        <h3>MUAI THAI PT</h3>

        <p> Entra Na Tua Conta Para Continuar</p><br>

<label for="text_utilizador">UserName:</label><br>
<input type="email" id="text_utilizador" name="text_utilizador" placeholder="Insere o teu email" required><br><br>

<label for="text_password">Password:</label><br>
<input type="password" id="text_password" name="text_password" placeholder="Insere a tua password" required><br><br>

<input type="submit" name="btn_submit" value="Entrar"><br><br>
<hr>

<p>ou</p>

<a href="signup.php">Criar uma nova conta de utilizador</a><br><br>

</form>

';

include 'rodape.php';
?>
