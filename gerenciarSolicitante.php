<?php session_start(); 
// Verifica se a sessão está ativa. 
// Se 'ativa' está definida na sessão, $seguranca é TRUE, caso contrário, redireciona para 'index.php'
$seguranca = isset($_SESSION['ativa']) ? TRUE : header("location:index.php");
require_once "functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel admin - Solicitante</title>
    <link rel="stylesheet" href="style/adm.css">
</head>
<body>
    <!-- Se $seguranca é TRUE, exibe o conteúdo protegido -->
    <?php if ($seguranca) { ?>
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>
                    <?php echo $_SESSION['nome_multiplicador']; ?>: Painel dos Solicitantes
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
        <?php include "layout/menuMultiplicador.php"; ?>

        <?php
         // Define a tabela e a ordem para a consulta
            $tabela = "solicitante";
            $order = "";
            // Busca os usuários no banco de dados
            $usuarios = buscarSolicitante($connect, $tabela, $where = 1, $order);
            // Insere um novo solicitante
            inserirSolicitante($connect);
           // Verifica se um ID de solicitante foi fornecido via GET
           if (isset($_GET['id_solicitante']) && $_SESSION['nivel_hierarquia'] == 'administrador'){ ?>
                <h2>Tem certeza que deseja deletar o Solicitante
                <?php echo $_GET['responsavel'];?></h2>
                <form action="" method="post">
                    <input type="hidden" name="id_solicitante" value="<?php echo $_GET['id_solicitante']?>">
                    <button type="submit" name="deletar" value="Deletar">Deletar</button>
                </form>
            <?php } ?>
            <?php 
                if(isset($_POST['deletar']) && $_SESSION['nivel_hierarquia'] == 'administrador'){
                        deletarSolicitante($connect, "solicitante",$_POST['id_solicitante']);
                }
            ?>

            <!-- Tabela de usuários -->
        <div class="container">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Responsavel</th>
                        <th>Email</th>
                        <th>Instituicão</th>
                        <th>CNPJ</th>
                        <th>Tipo</th>
                        <th>Esfera</th>
                        <th>Endereço</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td><?php echo $usuario['id_solicitante']; ?></td>
                            <td data-tooltip="<?php echo $usuario['responsavel']; ?>"><?php echo $usuario['responsavel']; ?></td>
                            <td><?php echo $usuario['email_solicitante']; ?></td>
                            <td data-tooltip="<?php echo $usuario['nome_instituicao']; ?>"><?php echo $usuario['nome_instituicao']; ?></td>
                            <td><?php echo formatarCNPJ($usuario['cnpj']); ?></td>
                            <td><?php echo $usuario['tipo_escola']; ?></td>
                            <td><?php echo $usuario['esfera']; ?></td>
                            <td data-tooltip="<?php echo $usuario['endereco_solicitante']; ?>"><?php echo $usuario['endereco_solicitante']; ?></td>
                            <td>
                                <?php 
                                if ($usuario['status_solicitante'] == 'A') {
                                    echo 'Ativo';
                                } elseif ($usuario['status_solicitante'] == 'I') {
                                    echo 'Inativo';
                                } elseif ($usuario['status_solicitante'] == 'E') {
                                    echo 'Em aberto';
                                } else {
                                    echo 'Status desconhecido';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($_SESSION['nivel_hierarquia'] == 'administrador') : ?>
                                        <a href="gerenciarSolicitante.php?id_solicitante=<?php echo $usuario['id_solicitante']; ?>&responsavel=<?php echo $usuario['responsavel']; ?>">Excluir</a>
                                        |<!-- CRIAR GERENCIARSOLICITANTE.PHP-->
                                        <a href="editarSolicitante.php?id_solicitante=<?php echo $usuario['id_solicitante']; ?>&responsavel=<?php echo $usuario['responsavel']; ?>">Atualizar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php }  ?>



</body>
</html>