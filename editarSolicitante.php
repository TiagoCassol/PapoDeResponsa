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
    <title>Painel admin - Editar Solicitante</title>
    <link rel="stylesheet" href="style/adm.css">
</head>
<body>
    <?php if (isset($_SESSION['ativa'])) { ?>
        <!-- Se a sessão estiver ativa, exibe o conteúdo -->
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>
                    <?php echo $_SESSION['nome_multiplicador']; ?>: Editando Solicitante
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
        <!-- Inclui o menu -->
        <?php include "layout/menuMultiplicador.php"; ?>
        <?php
        // Busca usuários no banco de dados
        $tabela = "solicitante";
        $usuarios = buscar($connect, $tabela, $where = 1, $order = "");
        ?>
        <?php if (isset($_GET['id_solicitante'])) {
            // Se um ID de usuário é fornecido, busca o usuário específico
            $id = $_GET['id_solicitante'];
            $usuario = buscaUnicaSolicitante($connect, $tabela, $id);
            updateSolicitante($connect);
            ?>
            <!-- Título para indicar que está editando um usuário específico -->
            <h2>Editando o Solicitante <?php echo $_GET['Nome_Instituicao']; ?> </h2>
        <?php } ?>

        <!-- Formulário para editar um usuário existente ou adicionar um novo usuário -->
        <form id="signup-form" action="" method="post">
         
                    <input value="<?php echo $usuario['id_solicitante']; ?>" type="hidden" name="id_solicitante">
                <div class="form-group">
                <label for="name">Nome_Instituicao:</label>
                    <input value="<?php echo $usuario['Nome_Instituicao']; ?>" id="name" type="text" name="Nome_Instituicao" placeholder="nome">
                </div>
                <div class="form-group">
                    <label for="email">E-mail solicitante:</label>
                    <input value="<?php echo $usuario['email_solicitante']; ?>" type="email" name="email_solicitante" placeholder="email">
                </div>
                <div class="form-group">
                    <label for="responsavel">responsavel:</label>
                    <input value="<?php echo $usuario['responsavel']; ?>" type="text" name="responsavel" placeholder="matricula">
                </div>
                <div class="form-group">
                    <label for="cpf_multiplicador">CNPJ:</label>
                    <input value="<?php echo $usuario['CNPJ']; ?>" type="text" name="CNPJ" placeholder="CNPJ">
                </div>
                <div class="form-group">
                    <label for="endereco_multiplicador">Endereco:</label>
                    <input value="<?php echo $usuario['endereco_multiplicador']; ?>" type="text" name="endereco_multiplicador" placeholder="Endereço">
                </div>
                <div class="form-group">
                    <label for="senha_multiplicador">Senha:</label>
                    <input type="password" name="senha_multiplicador" placeholder="senha">
                </div>
                <div class="form-group">
                    <label for="repete_senha">Repita a Senha:</label>
                    <input type="password" name="repetesenha" placeholder="Confirme sua senha">
                </div>
                <div class="form-group">
                    <label for="nivel_hierarquia">Nível de Hierarquia:</label>
                    <select id="nivel_hierarquia" name="nivel_hierarquia">
                        <option value="padrao" <?php echo ($usuario['nivel_hierarquia'] == 'padrao') ? 'selected' : ''; ?>>Padrão</option>
                        <option value="administrador" <?php echo ($usuario['nivel_hierarquia'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="trainee" <?php echo ($usuario['nivel_hierarquia'] == 'trainee') ? 'selected' : ''; ?>>Trainee</option>
                    </select>
                </div>
                <div class="form-group">
                            <!-- Campo para status -->
                    <label for="status_multiplicador">Status do Multiplicador:</label>
                    <select id="status_multiplicador" name="status_multiplicador">
                        <option value="A" <?php echo ($usuario['status_multiplicador'] == 'A') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="I" <?php echo ($usuario['status_multiplicador'] == 'I') ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>
                    <input type="submit" name="atualizar" value="Atualizar">

 
        </form>

        <a href="sair.php">Sair</a>

    <?php } else {
        // Se a sessão não estiver ativa, exibe uma mensagem de acesso negado
        echo "Você não tem acesso a esta página!";
    } ?>
</body>

</html>
