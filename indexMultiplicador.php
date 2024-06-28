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

// Buscar solicitações disponíveis
$solicitacoes_disponiveis = buscarSolicitacoesDisponiveis($connect);

// Buscar solicitações aceitas
$solicitacoes_aceitas = buscarSolicitacoesAceitas($connect, $_SESSION['id_multiplicador']);

// Buscar endereço e coordenadas do multiplicador
$endereco_multiplicador = buscarEnderecoMultiplicador($connect, $_SESSION['id_multiplicador']);
list($latitude_multiplicador, $longitude_multiplicador) = getCoordenada($endereco_multiplicador);

// Buscar coordenadas das solicitações disponíveis
$solicitacoes_coordenadas = [];
foreach ($solicitacoes_disponiveis as $solicitacao) {
    list($lat, $lng) = getCoordenada($solicitacao['endereco_solicitante']);
    $solicitacoes_coordenadas[] = [
        'descricao' => $solicitacao['descricao'],
        'lat' => $lat,
        'lng' => $lng
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/adm.css">

    <style type="text/css">
        #map {
            height: 400px;
            width: 400px;
        }
    </style>
</head>
<body>
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>
                    <?php echo $_SESSION['nome_multiplicador']; ?>: Solicitações
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
    <?php include "layout/menuMultiplicador.php"; ?>

    <h2>Solicitações Disponíveis</h2>
    <ul class="solicitacoes-disponiveis">
        <?php foreach ($solicitacoes_disponiveis as $solicitacao) : ?>
            <li>
                <?php 
                $distancia = getDistance($solicitacao['endereco_solicitante'], $endereco_multiplicador, "k");
                echo $solicitacao['descricao']; ?> - Solicitante: <?php echo $solicitacao['endereco_solicitante']; ?> - Distância: <?php echo $distancia; ?>
                <a href="?aceitar=<?php echo $solicitacao['id_solicitacao']; ?>">Aceitar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Solicitações Aceitas por Você</h2>
    <ul class="solicitacoes-aceitas">
        <?php foreach ($solicitacoes_aceitas as $solicitacao) : ?>
            <li>
                <?php echo $solicitacao['descricao']; ?> - Endereço: <?php echo $solicitacao['endereco_solicitante']; ?>
                <a href="?desistir=<?php echo $solicitacao['id_solicitacao']; ?>">Desistir</a>
            </li>
        <?php endforeach; ?>
    </ul>



    <div id="map"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm6ymiUpqDNNI8clPp2Qi2F-HSWsSLyVk&callback=initMap&libraries=&v=weekly" async></script>
    <script>
        // Inicialização do mapa
        function initMap() {
            const enderecoMultiplicador = {
                lat: <?php echo json_encode((float)$latitude_multiplicador); ?>,
                lng: <?php echo json_encode((float)$longitude_multiplicador); ?>
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 17.56,
                center: enderecoMultiplicador
            });

            // Adiciona marcador para o multiplicador
            new google.maps.Marker({
                position: enderecoMultiplicador,
                map: map,
                title: 'Multiplicador'
            });

            // Adiciona marcadores para as solicitações disponíveis
            const solicitacoes = <?php echo json_encode($solicitacoes_coordenadas); ?>;
            solicitacoes.forEach(function(solicitacao) {
                new google.maps.Marker({
                    position: { lat: parseFloat(solicitacao.lat), lng: parseFloat(solicitacao.lng) },
                    map: map,
                    title: solicitacao.descricao
                });
            });
        }
    </script>
</body>
</html>


<?php
// Buscar solicitações disponíveis
function buscarSolicitacoesDisponiveis($connect) {
    $query = "SELECT s.*, sl.endereco_solicitante FROM solicitacao s
              INNER JOIN solicitante sl ON s.id_solicitante = sl.id_solicitante
              WHERE s.id_multiplicador IS NULL AND s.status_solicitacao = 'A'";
    $result = mysqli_query($connect, $query);
    $solicitacoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $solicitacoes;
}

// Buscar solicitações aceitas
function buscarSolicitacoesAceitas($connect, $id_multiplicador) {
    $query = "SELECT s.*, sl.endereco_solicitante FROM solicitacao s
              INNER JOIN solicitante sl ON s.id_solicitante = sl.id_solicitante
              WHERE s.id_multiplicador = $id_multiplicador AND s.status_solicitacao = 'A'";
    $result = mysqli_query($connect, $query);
    $solicitacoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $solicitacoes;
}

// Aceitar uma solicitação
function aceitarSolicitacao($connect, $id_solicitacao, $id_multiplicador) {
    $query = "UPDATE solicitacao SET id_multiplicador = $id_multiplicador WHERE id_solicitacao = $id_solicitacao";
    $result = mysqli_query($connect, $query);
    if ($result) {
        header("Location: indexMultiplicador.php");
        exit;
    } else {
        echo "Erro ao aceitar solicitação.";
    }
}

// Desistir de uma solicitação
function desistirSolicitacao($connect, $id_solicitacao, $id_multiplicador) {
    $query = "UPDATE solicitacao SET id_multiplicador = NULL WHERE id_solicitacao = $id_solicitacao AND id_multiplicador = $id_multiplicador";
    $result = mysqli_query($connect, $query);
    if ($result) {
        header("Location: indexMultiplicador.php");
        exit;
    } else {
        echo "Erro ao desistir da solicitação.";
    }
}

function buscarEnderecoMultiplicador($connect, $id_multiplicador) {
    $query = "SELECT endereco_multiplicador FROM multiplicador
              WHERE id_multiplicador = $id_multiplicador";
    $result = mysqli_query($connect, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['endereco_multiplicador'];
    } else {
        return null;
    }
}
?>
