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
            <h2>Editando o Solicitante <?php echo $usuario['Nome_Instituicao']; ?> </h2>
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
                    <input value="<?php echo $usuario['cnpj']; ?>" type="text" name="cnpj" placeholder="CNPJ">
                </div>
                <div class="form-group">
                    <label for="endereco_solicitante">Endereço:</label>
                    <input value="<?php echo $usuario['endereco_solicitante']; ?>" type="text" name="endereco_solicitante" placeholder="Endereço">
                </div>

                <div class="form-group">
                            <!-- Campo para status -->
                    <label for="tipo_escola">Tipo de Escola:</label>
                    <select id="tipo_escola" name="tipo_escola">
                        <option value="publica" <?php echo ($usuario['tipo_escola'] == 'publica') ? 'selected' : ''; ?>>Publica</option>
                        <option value="privada" <?php echo ($usuario['tipo_escola'] == 'privada') ? 'selected' : ''; ?>>Privada</option>
                    </select>
                </div>

                <div class="form-group">
                            <!-- Campo para status -->
                    <label for="esfera">Esfera:</label>
                    <select id="esfera" name="esfera">
                        <option value="municipal" <?php echo ($usuario['esfera'] == 'municipal') ? 'selected' : ''; ?>>Municipal</option>
                        <option value="federal" <?php echo ($usuario['esfera'] == 'federal') ? 'selected' : ''; ?>>Federal</option>
                        <option value="estadual" <?php echo ($usuario['esfera'] == 'estadual') ? 'selected' : ''; ?>>Estadual</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="senha_solicitante">Senha:</label>
                    <input type="password" name="senha_solicitante" placeholder="senha">
                </div>
                <div class="form-group">
                    <label for="repete_senha">Repita a Senha:</label>
                    <input type="password" name="repetesenha" placeholder="Confirme sua senha">
                </div>
                <div class="form-group">
                            <!-- Campo para status -->
                    <label for="status_solicitante">Status do Solicitante:</label>
                    <select id="status_solicitante" name="status_solicitante">
                        <option value="A" <?php echo ($usuario['status_solicitante'] == 'A') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="I" <?php echo ($usuario['status_solicitante'] == 'I') ? 'selected' : ''; ?>>Inativo</option>
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
