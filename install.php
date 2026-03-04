<?php
// criar a base de dados que interliga com  o site

include 'config.php';

$ligacao = new PDO("mysql:host=$host", $user, $db_password);
$motor = $ligacao->prepare("CREATE DATABASE IF NOT EXISTS $base_dados default charset=utf8");
$motor->execute();


$ligacao = null;

echo '<p>Base de dados criada com sucesso</p>';

$ligacao = new PDO("mysql:dbname=$base_dados;host=$host;charset=utf8", $user, $db_password);

// optional: create a basic users table for foreign key references
$sql = "CREATE TABLE IF NOT EXISTS Users(
    ID CHAR(36) PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$motor = $ligacao->prepare($sql);
$motor->execute();
echo '<p> Tabela Users criada com sucesso</p>';

//criacao da tabela dos users
  
$sql = "CREATE TABLE IF NOT EXISTS PerfisUtilizadores(
    id_Utilizador    CHAR(36) PRIMARY KEY,
    Nome_Completo   VARCHAR(30) NOT NULL,
    Avatar          VARCHAR(250) DEFAULT NULL,
    Bio             TEXT,
    Tel             TEXT,
    Cidade          TEXT,
    Data_Nascimento DATE NOT NULL,
    Genero          ENUM('Masculino','Feminino'),
    Data_Criacao    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpDated_At      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela PerfisUtilizadores criada com sucesso</P>'; //mensagem de sucesso


// criacao da tabela que define o papel de cada utilizador nao podendo repetir 
$sql = "CREATE TABLE IF NOT EXISTS PapeisUtilizadores(
    id               CHAR(36) PRIMARY KEY,
    id_Utilizador    CHAR(36) NOT NULL,
    papel            ENUM('Admin', 'Treinador', 'Atleta', 'Utilizador') NOT NULL,
    UNIQUE KEY (id_Utilizador, papel),
    FOREIGN KEY (id_Utilizador) REFERENCES Users(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela PapeisUtilizadores criada com sucesso</P>'; //mensagem de sucesso

//criacao da tabela ginasios 

$sql = "CREATE TABLE IF NOT EXISTS Equipas(
   id   CHAR(36) PRIMARY KEY,
   id_Propriatario  CHAR(36) NOT NULL,
   Nome        TEXT NOT NULL,
   slug         VARCHAR(255) UNIQUE NOT NULL,
   Descricao    TEXT,
   Logo_Equipa  VARCHAR(250),
   Morada       TEXT NOT NULL,
   Cidade       TEXT NOT NULL,
   Destrito     TEXT NOT NULL,
   CP           TEXT NOT NULL,
   TEL          INT NOT NULL,
   Email        TEXT NOT NULL,
   WebSite      TEXT,
   Insta        TEXT,
   Face         TEXT,
   Horarios     JSON,
   Modalidades  ENUM('MuaiThai', 'KickBoxing','K1'),
   Mensalidade  DECIMAL(8,2) NULL,
   Mensalidade_Info TEXT,
   Verificado   BOOLEAN NOT NULL DEFAULT FALSE,
   Criado_Em    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (id_Propriatario) REFERENCES Users(ID)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela Equipas criada com sucesso</P>'; //mensagem de sucesso


$sql = "CREATE TABLE IF NOT EXISTS Lutadores(
    id              CHAR(36) PRIMARY KEY,
    Utilizador_id   CHAR(36),
    id_Ginasio      CHAR(36),
    Nome            TEXT NOT NULL,
    Slug            VARCHAR(255) NOT NULL UNIQUE,
    Avatar          VARCHAR(250),
    Data_Nascimento DATE,
    Peso            DECIMAL(5,2),
    Categoria_Peso  TEXT,
    Altura          INT,
    Envergadura     INT,
    Guarda          TEXT,
    Vitorias        INT NOT NULL DEFAULT 0,
    Derrotas        INT NOT NULL DEFAULT 0,
    Empates         INT NOT NULL DEFAULT 0,
    Vitorias_Ko     INT NOT NULL DEFAULT 0,
    Vitorias_Decisao INT NOT NULL DEFAULT 0,
    Biografia       TEXT,
    Titulos         TEXT,
    Cidade          TEXT,
    Nacionalidade   TEXT,
    Ativo           BOOLEAN NOT NULL DEFAULT TRUE,
    Criado_em       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Utilizador_id) REFERENCES Users(ID),
    FOREIGN KEY (id_Ginasio) REFERENCES Equipas(id)
)
";


$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela Lutadores criada com sucesso</P>'; //mensagem de sucesso


$ligacao = null; // fechar a ligacao á base de dados

?> // fim do ficheiro

