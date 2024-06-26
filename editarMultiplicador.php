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
    <title>Painel admin - Editar Multiplicador</title>
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
                    <?php echo $_SESSION['nome_multiplicador']; ?>: Editando Multiplicador
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
        $tabela = "multiplicador";
        $usuarios = buscar($connect, $tabela, $where = 1, $order = "");
        ?>
        <?php if (isset($_GET['id_multiplicador'])) {
            // Se um ID de usuário é fornecido, busca o usuário específico
            $id = $_GET['id_multiplicador'];
            $usuario = buscaUnica($connect, $tabela, $id);
            updateMultiplicador($connect);
            ?>
            <!-- Título para indicar que está editando um usuário específico -->
            <h2>Editando o usuário <?php echo $_GET['nome_multiplicador']; ?> </h2>
        <?php } ?>

        <!-- Formulário para editar um usuário existente ou adicionar um novo usuário -->
        <form id="signup-form" action="" method="post">
         
                    <input value="<?php echo $usuario['id_multiplicador']; ?>" type="hidden" name="id_multiplicador">
                <div class="form-group">
                <label for="name">Nome:</label>
                    <input value="<?php echo $usuario['nome_multiplicador']; ?>" id="name" type="text" name="nome_multiplicador" placeholder="nome">
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input value="<?php echo $usuario['email_multiplicador']; ?>" type="email" name="email_multiplicador" placeholder="email">
                </div>
                <div class="form-group">
                    <label for="matricula">Matrícula:</label>
                    <input value="<?php echo $usuario['matricula']; ?>" type="text" name="matricula" placeholder="matricula">
                </div>
                <div class="form-group">
                    <label for="cpf_multiplicador">CPF:</label>
                    <input value="<?php echo $usuario['cpf_multiplicador']; ?>" type="text" name="cpf_multiplicador" placeholder="CPF">
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
