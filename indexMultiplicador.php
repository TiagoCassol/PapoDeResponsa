
<?php
session_start();
$seguranca = isset($_SESSION['ativa']) ? TRUE : header("location:index.php");

include "functions.php";
require "calculadorGoogleMaps.php";

if (isset($_GET['aceitar'])) {
    $id_solicitacao = $_GET['aceitar'];
    aceitarSolicitacao($connect, $id_solicitacao, $_SESSION['id_multiplicador']);
}

if (isset($_GET['desistir'])) {
    $id_solicitacao = $_GET['desistir'];
    desistirSolicitacao($connect, $id_solicitacao, $_SESSION['id_multiplicador']);
}

if (isset($_GET['concluir'])) {
    $id_solicitacao = $_GET['concluir'];
    concluirSolicitacao($connect, $id_solicitacao);
}

// Buscar solicitações disponíveis
$solicitacoes_disponiveis = buscarSolicitacoesDisponiveis($connect);

// Buscar solicitações aceitas
$solicitacoes_aceitas = buscarSolicitacoesAceitas($connect, $_SESSION['id_multiplicador']);
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel admin</title>
    <link rel="stylesheet" href="style/adm.css">
</head>
<body>
    <?php if ($seguranca) { ?>
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>Bem vindo,
                    <?php echo $_SESSION['nome_multiplicador']; ?> ao painel do site!
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
        <?php include "layout/menuMultiplicador.php"; ?>

        <h2>Solicitações Disponíveis</h2>
        <ul>
            <?php
            $endereco_multiplicador = buscarEnderecoMultiplicador($connect, $_SESSION['id_multiplicador']);

            foreach ($solicitacoes_disponiveis as $solicitacao) : ?>
                <li>
                    <?php $distancia = getDistance($addressFrom = $solicitacao['endereco_solicitante'], $addressTo = $endereco_multiplicador, $unit = "k"); ;
                    echo $solicitacao['descricao']; ?> - Solicitante: <?php echo $solicitacao['endereco_solicitante']; ?> - Distância: <?php echo $distancia;?>
                    <a href="?aceitar=<?php echo $solicitacao['id_solicitacao']; ?>">Aceitar</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2>Solicitações Aceitas por Você</h2>
        <ul>
            <?php foreach ($solicitacoes_aceitas as $solicitacao) : ?>
                <li>
                    <?php echo $solicitacao['descricao']; ?> - Endereço: <?php echo $solicitacao['endereco_solicitante']; ?>
                    <a href="?desistir=<?php echo $solicitacao['id_solicitacao']; ?>">Desistir</a>
                    <a href="?concluir=<?php echo $solicitacao['id_solicitacao']; ?>">Visita realizada</a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php }  ?>
</body>
</html>
