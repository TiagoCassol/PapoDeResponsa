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
            <h1>Bem vindo,
                    ao painel do site!
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>
    
    <main>
        
                  <!-- Formulário para Cadastrar novo Multiplicador -->
        <form id="signup-form" action="" method="post">
            <h2>Esqueceu sua senha</h2>
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
                <label for="senha_multiplicador">Sua nova senha:</label>
                <input type="password" id="senha_multiplicador" name="senha_multiplicador" required>
            </div>
            <div class="form-group">
                <label for="repete_senha">Repita a senha:</label>
                <input type="password" id="repete_senha" name="repete_senha" required>
            </div>

            </div>
            <button type="submit" name="cadastrar" value="Cadastrar">Cadastrar</button>
        </form>
        <a href="logout.php">Sair</a>
    </main>

    <footer>
    
    </footer>
</body>
</html>
