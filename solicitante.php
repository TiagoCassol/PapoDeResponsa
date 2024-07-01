<?php 
session_start();
$seguranca = isset($_SESSION['ativa']) ? TRUE : header("location:index.php");

require_once "functions.php";

inserirSolicitacao($connect);
$solicitacoes = buscarSolicitacoesUsuarioLogado($connect, $_SESSION['id_solicitante']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Solicitante</title>
    <link rel="stylesheet" href="style/solicitante.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>  
    <?php 
    if ($seguranca) { 
    ?>
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>Bem vindo,
                    <?php echo $_SESSION['responsavel']; ?> ao painel do site!
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
        <?php 
        include "layout/menuSolicitante.php"; 
        ?>
        
        <h2>Caso queira solicitar uma palestra, digite abaixo.</h2>
        <div class="form-container">
            <form action="" method="POST">
                <input type="text" name="pedido" placeholder="Descreva o tema da palestra que voce deseja solicitar" required>
                <button type="submit" name="cadastrar" value="Enviar">Solicitar</button>
            </form>
        </div>
        <div class="solicitacoes-container">
            <h2>Suas Solicitações:</h2>
            <?php 
            if (empty($solicitacoes)) {
                echo "<p>Você ainda não fez nenhuma solicitação.</p>";
            } else {
                foreach ($solicitacoes as $solicitacao) {
                    echo "<p>Descrição: {$solicitacao['descricao']}</p>";
                    echo "<p>Status: " . traduzirStatus($solicitacao['status_solicitacao']) . "</p>";
                    if ($solicitacao['status_solicitacao'] == 'E') {
                        echo "<form action='' method='POST' class='transparent-form'>
                                <input type='hidden' name='id_solicitacao' value='{$solicitacao['id_solicitacao']}'>
                                <button id='cancelar-btn-{$solicitacao['id_solicitacao']}' type='submit' name='cancelar_solicitacao' class='cancelar-btn'>Cancelar Solicitação</button>
                              </form>";
                    }
                    echo "<hr>";
                }
            }
            ?>
        </div>
    <?php 
    }  
    ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancelar_solicitacao'])) {
        $id_solicitacao = $_POST['id_solicitacao'];
        $query = "DELETE FROM solicitacao WHERE id_solicitacao = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("i", $id_solicitacao);

        if ($stmt->execute()) {
            echo "Solicitação {$id_solicitacao} cancelada com sucesso.";
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Erro ao cancelar a solicitação: " . $stmt->error;
        }

        $stmt->close();
    }

    ?>

</body>
</html>
