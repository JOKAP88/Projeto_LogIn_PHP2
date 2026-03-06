<?php
// criar a base de dados que interliga com  o site

include 'config.php';

$ligacao = new PDO("mysql:host=$host", $user, $db_password);
$motor = $ligacao->prepare("CREATE DATABASE IF NOT EXISTS $base_dados default charset=utf8mb4");
$motor->execute();


$ligacao = null;

echo '<p>Base de dados criada com sucesso</p>';

$ligacao = new PDO("mysql:dbname=$base_dados;host=$host;charset=utf8mb4", $user, $db_password);

// optional: create a basic users table for foreign key references
$sql = "CREATE TABLE IF NOT EXISTS Users(
    ID CHAR(36) PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$motor = $ligacao->prepare($sql);
$motor->execute();
echo '<p> Tabela Users criada com sucesso</p>';

//criacao da tabela dos users
  
$sql = "CREATE TABLE IF NOT EXISTS PerfisUtilizadores(
    id_Utilizador    CHAR(36) PRIMARY KEY,
    primeiro_nome    VARCHAR(30) NOT NULL,
    ultimo_nome      VARCHAR(30) NOT NULL,
    nome_completo    VARCHAR(100) NOT NULL UNIQUE,
    pass             VARCHAR(255) NOT NULL,
    Avatar           VARCHAR(250) DEFAULT NULL,
    Bio              TEXT,
    Tel              TEXT,
    Cidade           TEXT,
    Data_Nascimento  DATE NOT NULL,
    Genero           ENUM('Masculino','Feminino'),
    Data_Criacao     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpDated_At       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela PerfisUtilizadores criada com sucesso</P>'; //mensagem de sucesso


// criacao da tabela que define o papel de cada utilizador nao podendo repetir (Adicionar no campo paple o promotor)
$sql = "CREATE TABLE IF NOT EXISTS PapeisUtilizadores(
    id               CHAR(36) PRIMARY KEY,
    id_Utilizador    CHAR(36) NOT NULL,
    papel            ENUM('Admin', 'Treinador', 'Atleta', 'Utilizador') NOT NULL, 
    UNIQUE KEY (id_Utilizador, papel),
    FOREIGN KEY (id_Utilizador) REFERENCES Users(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela PapeisUtilizadores criada com sucesso</P>'; //mensagem de sucesso

// tabela ginasios  

$sql = "CREATE TABLE IF NOT EXISTS Equipas(
   id   CHAR(36) PRIMARY KEY,
   id_Propriatario  CHAR(36) NOT NULL,
   Nome        TEXT NOT NULL,
   slug         VARCHAR(255) UNIQUE NOT NULL,
   slug_hash    VARCHAR(64) UNIQUE,
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
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela Equipas criada com sucesso</P>'; //mensagem de sucesso

// TABELA LUTADORES

$sql = "CREATE TABLE IF NOT EXISTS Lutadores(
    id              CHAR(36) PRIMARY KEY,
    Utilizador_id   CHAR(36),
    id_Ginasio      CHAR(36),
    Nome            TEXT NOT NULL,
    Slug            VARCHAR(255) NOT NULL UNIQUE,
    slug_hash       VARCHAR(64) UNIQUE,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";



$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela Lutadores criada com sucesso</P>'; //mensagem de sucesso

//TABELA EVENTOS

$sql = "CREATE TABLE IF NOT EXISTS eventos (
    id CHAR(36) PRIMARY KEY,
    organizador_id CHAR(36) NOT NULL,
    titulo TEXT NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    slug_hash VARCHAR(64) UNIQUE,
    descricao TEXT,
    capa_url TEXT,
    data_evento DATETIME NOT NULL,
    data_pesagem DATETIME NULL,
    data_fim DATETIME,
    local TEXT NOT NULL,
    morada_local TEXT,
    cidade TEXT NOT NULL,
    distrito TEXT,
    tipo ENUM('gala','campeonato','seminario','treino_aberto','outro') NOT NULL,
    max_participantes INT,
    preco_inscricao DECIMAL(8,2),
    preco_bilhete DECIMAL(8,2),
    link_bilhetes TEXT,
    prazo_inscricao DATETIME,
    regulamento_url TEXT,
    publicado BOOLEAN NOT NULL DEFAULT FALSE,
    destaque BOOLEAN NOT NULL DEFAULT FALSE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizador_id) REFERENCES Users(ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela eventos criada com sucesso</P>'; //mensagem de sucesso

//TABELA DE INSCRICOES PARA OS EVENTES REGISTADOS NO SITE(FEATURE A IMPLEMENTAR ACESSO POR PROMOTORAS COM POSSIBILIDADE DE ANUNCIAR EVENTOS E GERIR EVENTOS )

$sql = "CREATE TABLE IF NOT EXISTS inscricoes_eventos (
    id CHAR(36) PRIMARY KEY,
    evento_id CHAR(36) NOT NULL,
    utilizador_id CHAR(36) NOT NULL,
    lutador_id CHAR(36),
    categoria_peso TEXT,
    tipo_inscricao ENUM('competidor','participante','espectador') NOT NULL DEFAULT 'participante',
    estado ENUM('pendente','confirmada','cancelada') NOT NULL DEFAULT 'pendente',
    notas TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(evento_id, utilizador_id),
    FOREIGN KEY (evento_id) REFERENCES eventos(id),
    FOREIGN KEY (utilizador_id) REFERENCES Users(ID),
    FOREIGN KEY (lutador_id) REFERENCES Lutadores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela inscricoes_eventos criada com sucesso</P>'; //mensagem de sucesso

//TABELA AVALIACOES DE GINASIOS(SITE STATUS: PENDING) POSSIVEL NAO SER NECESSARIO AVALICAO REQUESITADA ("APAGAR COMENTARIO QUANDO RESOLVIDO")
$sql = "CREATE TABLE IF NOT EXISTS avaliacoes (
    id CHAR(36) PRIMARY KEY,
    utilizador_id CHAR(36) NOT NULL,
    ginasio_id CHAR(36) NOT NULL,
    classificacao INT NOT NULL,
    titulo TEXT,
    comentario TEXT,
    resposta_proprietario TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(utilizador_id, ginasio_id),

    FOREIGN KEY (utilizador_id) REFERENCES Users(ID),
    FOREIGN KEY (ginasio_id) REFERENCES Equipas(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela inscricoes_eventos criada com sucesso</P>'; //mensagem de sucesso

//TABELA FAVORITOS (FEATURE A IMPLEMENTAR SISTEMA DE NOTIFICAOES OU ADDICIONAR AO CALENDARIO PESSOAL)

$sql = "CREATE TABLE IF NOT EXISTS favoritos (
    id CHAR(36) PRIMARY KEY,
    utilizador_id CHAR(36) NOT NULL,
    tipo_item ENUM('ginasio','evento','lutador') NOT NULL,
    item_id CHAR(36) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(utilizador_id, tipo_item, item_id),

    FOREIGN KEY (utilizador_id) REFERENCES Users(ID)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela favoritos criada com sucesso</P>'; //mensagem de sucesso

// TABELA ARTIGOS E NOTICIAS REFERENTES A EVENTOS, LUTADORES, ACONTECIMENTOS E NOTICIAS
$sql = "CREATE TABLE IF NOT EXISTS artigos (
    id CHAR(36) PRIMARY KEY,
    autor_id CHAR(36) NOT NULL,
    titulo TEXT NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    slug_hash VARCHAR(64) UNIQUE,
    resumo TEXT,
    conteudo LONGTEXT NOT NULL,
    capa_url TEXT,
    categoria VARCHAR(100),
    etiquetas TEXT,
    publicado BOOLEAN NOT NULL DEFAULT FALSE,
    data_publicacao DATETIME,
    visualizacoes INT NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (autor_id) REFERENCES Users(ID)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

";

$motor = $ligacao->prepare($sql); //prepara o comando sql
$motor->execute();                 //executa o comando

echo '<p> Tabela Artigos criada com sucesso</P>'; //mensagem de sucesso




$ligacao = null; // fechar a ligacao á base de dados

?> // fim do ficheiro

