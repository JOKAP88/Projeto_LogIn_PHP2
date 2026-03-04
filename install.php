<?php
// criar a base de dados que interliga com  o site

include 'config.php';

$con = new PDO("mysql:host=$host", $user, $db_password);




?>