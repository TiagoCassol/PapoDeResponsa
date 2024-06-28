<?php
session_start();

require_once "functions.php";

// Chama a função de inserção de multiplicador
inserirSolicitante($connect);
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
        <form id="signup-form" action="" method="post">
            <h2>Cadastro do Solicitante</h2>
            <div class="form-group">
                <label for="institution-name">Nome da Instituição:</label>
                <input type="text" id="institution-name" name="Nome_Instituicao" required>
            </div>
            <div class="form-group">
                <label for="institution-email">Email da Instituição:</label>
                <input type="text" id="institution-email" name="email_solicitante" required>
            </div>
            <div class="form-group">
                <label for="institution-name">Responsavel:</label>
                <input type="text" id="institution-name" name="responsavel" required>
            </div>
            <div class="form-group">
                <label for="institution-cnpj">CNPJ da Instituição:</label>
                <input type="text" id="institution-cnpj" name="cnpj" required>
            </div>
            <div class="form-group">
                <label for="institution-type">A instituição é:</label>
                <select id="institution-type" name="tipo_escola" required>
                    <option value="publica">Pública</option>
                    <option value="privada">Privada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="institution-sphere">Esfera:</label>
                <select id="institution-sphere" name="esfera" required>
                    <option value="federal">Federal</option>
                    <option value="state">Estadual</option>
                    <option value="municipal">Municipal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Endereço:</label>
                <input type="text" id="address" name="endereco_solicitante" required>
            </div>
            <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="senha_solicitante" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmar Senha:</label>
                <input type="password" id="confirm-password" name="repete_senha" required>
            </div>

            <button type="submit" name="cadastrar" value="Cadastrar">Cadastrar</button>
        </form>
        <a href="logout.php">Sair</a>
    </main>

    <footer>
    
    </footer>
</body>
</html>

