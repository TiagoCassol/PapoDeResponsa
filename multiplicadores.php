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
    <title>Painel admin - multiplicadores</title>
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
            <h1>Bem vindo,
                    <?php echo $_SESSION['nome_multiplicador']; ?> ao painel do site!
                </h1>
            </nav>
            
            <nav class="nav-right">
                <img src="style/papoLogo2.png" alt="Logo Papo de Responsa" class="logo">
            </nav>
        </header>


        <?php include "layout/menuMultiplicador.php"; ?>

        <?php
         // Define a tabela e a ordem para a consulta
            $tabela = "usuarios";
            $order = "";
            // Busca os usuários no banco de dados
            $usuarios = buscar($connect, $tabela, $where = 1, $order);
            // Insere um novo multiplicador
            inserirMultiplicador($connect);
           // Verifica se um ID de multiplicador foi fornecido via GET
           if (isset($_GET['id_multiplicador']) && $_SESSION['nivel_hierarquia'] == 'administrador'){ ?>
                <h2>Tem certeza que deseja deletar o Multiplicador 
                <?php echo $_GET['nome_multiplicador'];?></h2>
                <form action="" method="post">
                    <input type="hidden" name="id_multiplicador" value="<?php echo $_GET['id_multiplicador']?>">
                    <button type="submit" name="deletar" value="Deletar">Deletar</button>
                </form>
            <?php } ?>
            <?php 
                if(isset($_POST['deletar']) && $_SESSION['nivel_hierarquia'] == 'administrador'){
                    if ($_SESSION['id_multiplicador'] != $_POST['id_multiplicador']){
                        deletar($connect, "multiplicador",$_POST['id_multiplicador']);
                    } else{
                        echo "Você não pode deletar seu próprio usuário!";
                    }
                }
            
            ?>
            
        <!-- Tabela de usuários -->
        <div class="container">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Matricula</th>
                        <th>CPF</th>
                        <th>Endereço</th>
                        <th>Status</th>
                        <th>Cargo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td><?php echo $usuario['id_multiplicador']; ?></td>
                            <td data-tooltip="<?php echo $usuario['nome_multiplicador']; ?>"><?php echo $usuario['nome_multiplicador']; ?></td>
                            <td><?php echo $usuario['email_multiplicador']; ?></td>
                            <td><?php echo $usuario['matricula']; ?></td>
                            <td><?php echo formatarCPF($usuario['cpf_multiplicador']); ?></td>
                            <td data-tooltip="<?php echo $usuario['endereco_multiplicador']; ?>"><?php echo $usuario['endereco_multiplicador']; ?></td>
                            <td>
                                <?php 
                                if ($usuario['status_multiplicador'] == 'A') {
                                    echo 'Ativo';
                                } elseif ($usuario['status_multiplicador'] == 'I') {
                                    echo 'Inativo';
                                } elseif ($usuario['status_multiplicador'] == 'E') {
                                    echo 'Em aberto';
                                } else {
                                    echo 'Status desconhecido';
                                }
                                ?>
                            </td>

                            <td><?php echo $usuario['nivel_hierarquia']; ?></td>
                            <td>
                                <?php if ($_SESSION['nivel_hierarquia'] == 'administrador') : ?>
                                        <a href="multiplicadores.php?id_multiplicador=<?php echo $usuario['id_multiplicador']; ?>&nome_multiplicador=<?php echo $usuario['nome_multiplicador']; ?>">Excluir</a>
                                        |
                                        <a href="editarMultiplicador.php?id_multiplicador=<?php echo $usuario['id_multiplicador']; ?>&nome_multiplicador=<?php echo $usuario['nome_multiplicador']; ?>">Atualizar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php }  ?>

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

</body>
</html>