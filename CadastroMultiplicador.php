<?php
session_start();

require_once "functions.php";

// Chama a função de inserção de multiplicador
inserirMultiplicador($connect);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/adm.css">

</head>
<body>
        <header>
            <nav class="nav-left">
            
            <img src="style/policiaCivil2.png" alt="Logo Papo de Responsa" class="">
            </nav>
            <nav class="nav-center">
            <h1>
                    
                </h1>
            </nav>
            
            <nav class="nav-right">
            <a href="index.php">  <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo"></a>
            </nav>
        </header>
    
    <main>

                  <!-- Formulário para Cadastrar novo Multiplicador -->
        <form id="signup-form" action="" method="post">
            <h2>Cadastro do Multiplicador</h2>
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="nome_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula:</label>
                <input type="text" id="matricula" name="matricula" required>
            </div>
            <div class="form-group">
                <label for="cpf_multiplicador">CPF:</label>
                <input type="text" id="cpf_multiplicador" name="cpf_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="endereco_multiplicador">Endereco:</label>
                <input type="text" id="endereco_multiplicador" name="endereco_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="senha_multiplicador">Senha:</label>
                <input type="password" id="senha_multiplicador" name="senha_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="repete_senha">Repita a Senha:</label>
                <input type="password" id="repete_senha" name="repete_senha" required>
            </div>
            <div class="form-group">
            <label for="nivel_hierarquia">Nível de Hierarquia:</label>
            <select id="nivel_hierarquia" name="nivel_hierarquia" required>
                <option value="padrao">Padrão</option>
                <option value="trainee">Trainee</option>
                <option value="administrador">Administrador</option>
            </select>
            </div>
            <button type="submit" name="cadastrar" value="Cadastrar">Cadastrar</button>
        </form>
        <a href="logout.php">Sair</a>
    </main>

    <footer>
    
    </footer>
</body>
</html>
