<?php
$server = "localhost";
$userDb = "root";
$passDb = "12345";
$nameDb = "papo_de_responsa";
$port = 3306;

// Conexão com o Banco de Dados
$connect = mysqli_connect($server, $userDb, $passDb, $nameDb, $port);

// Verificar conexão
if (!$connect) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Função para realizar o login
function login($connect)
{
    if (isset($_POST['acessar'])) {
        $check_email = filter_input(INPUT_POST, 'email_multiplicador', FILTER_VALIDATE_EMAIL);
        if ($check_email === false) {
            echo '<label style="color: red; font-size: 2rem;">E-mail inválido!</label>';
            return;
        }
        $email = mysqli_real_escape_string($connect, $check_email);
        $senha = mysqli_real_escape_string($connect, $_POST['senha_multiplicador']);

        if (!empty($email) && !empty($senha)) {
            $query = "SELECT * FROM multiplicador WHERE email_multiplicador = '$email' AND senha_multiplicador = '$senha'";
            $executar = mysqli_query($connect, $query);

            if (!$executar) {
                // Erro ao executar a consulta
                echo '<label style="color: red; font-size: 2rem;">Erro ao executar a consulta: ' . mysqli_error($connect) . '</label>';
                return;
            }

            $verifica = mysqli_num_rows($executar);
            $usuario = mysqli_fetch_assoc($executar);

            if ($verifica > 0) {
				// Verifica se a conta está ativa
				if ($usuario['status_multiplicador'] == 'I') {
					echo '<script>alert("Sua conta está inativa. Entre em contato com o suporte.");</script>';
					return;
				}
				
                // Inicia uma sessão (login)
                session_start();
                $_SESSION['email_multiplicador'] = $usuario['email_multiplicador'];
                $_SESSION['nome_multiplicador'] = $usuario['nome_multiplicador'];
                $_SESSION['id_multiplicador'] = $usuario['id_multiplicador'];
				$_SESSION['nivel_hierarquia'] = $usuario['nivel_hierarquia']; 
                $_SESSION['ativa'] = true;
                header("location: indexMultiplicador.php"); // Redireciona para a página de administração
                exit;
            } else {
                echo '<label style="color: red; font-size: 2rem;">E-mail ou senha não encontrados!</label>';
            }
        } else {
            echo '<label style="color: red; font-size: 2rem;">E-mail ou senha incorretos!</label>';
        }
    }
}
function loginSolicitante($connect)
{
    if (isset($_POST['acessar_solicitante'])) {
        $check_email = filter_input(INPUT_POST, 'email_solicitante', FILTER_VALIDATE_EMAIL);
        if ($check_email === false) {
            echo '<label style="color: red; font-size: 2rem;">E-mail inválido!</label>';
            return;
        }
        $email = mysqli_real_escape_string($connect, $check_email);
        ########### verificar ###################################
        $senha = mysqli_real_escape_string($connect, $_POST['senha_solicitante']);

        if (!empty($email) && !empty($senha)) {
            $query = "SELECT * FROM solicitante WHERE email_solicitante = '$email' AND senha_solicitante = '$senha'";
            $executar = mysqli_query($connect, $query);

            if (!$executar) {
                echo '<label style="color: red; font-size: 2rem;"Erro ao executar a consulta: ' . mysqli_error($connect) . '</label>';
                return;
            }

            $verifica = mysqli_num_rows($executar);
            $usuario = mysqli_fetch_assoc($executar);

            if ($verifica > 0) {
				if ($usuario['status_solicitante'] == 'I') {
					echo '<script>alert("Sua conta está inativa. Entre em contato com o suporte.");</script>';
					return;
				}
				
                session_start();
                $_SESSION['email_solicitante'] = $usuario['email_solicitante'];
                $_SESSION['responsavel'] = $usuario['responsavel'];
                $_SESSION['id_solicitante'] = $usuario['id_solicitante'];
                $_SESSION['ativa'] = true;
                header("location: solicitante.php"); // Redireciona para a página de administração do solicitante
                exit;
            } else {
                echo '<label style="color: red; font-size: 2rem;">E-mail ou senha não encontrados!</label>';
                }
        } else {
                echo  '<label style="color: red; font-size: 2rem;">E-mail ou senhaaaaa incorretos!</label>';
        }
    }
}




// Função para deslogar
function logout()
{
	session_start();
	session_unset();
	session_destroy();
	header("location: index.php"); // Redireciona para a página inicial
}

// Função para buscar um usuário específico
function buscaUnica($connect, $tabela, $id) {
    // Monta a consulta SQL
    $query = "SELECT * FROM $tabela WHERE id_Multiplicador = " . (int)$id;

    // Executa a consulta
    $execute = mysqli_query($connect, $query);

    // Verifica se a consulta foi bem-sucedida
    if ($execute === false) {
        die("Erro na consulta: " . mysqli_error($connect));
    }

    // Verifica se há resultados
    if (mysqli_num_rows($execute) > 0) {
        // Retorna o resultado como um array associativo
        return mysqli_fetch_assoc($execute);
    } else {
        // Retorna null se não houver resultados
        return null;
    }
}


// Função para buscar todos os usuários
function buscar($connect, $tabela, $where = 1, $order = "")
{
	if (!empty($order)) {
		$order = "ORDER BY $order";
	}
	$query = "SELECT * FROM multiplicador where $where $order";
	$execute = mysqli_query($connect, $query);
	$results = mysqli_fetch_all($execute, MYSQLI_ASSOC);
	return $results;
}

function inserirMultiplicador($connect)
{
	if (isset($_POST['cadastrar']) and !empty($_POST['email_multiplicador']) and !empty($_POST['senha_multiplicador'])) {
		$erros = array();
		$email = filter_input(INPUT_POST, 'email_multiplicador', FILTER_VALIDATE_EMAIL);
		$nome = mysqli_real_escape_string($connect, $_POST['nome_multiplicador']);
		$matricula = mysqli_real_escape_string($connect, $_POST['matricula']);
		$cpf = mysqli_real_escape_string($connect, $_POST['cpf_multiplicador']);
		$endereco = mysqli_real_escape_string($connect, $_POST['endereco_multiplicador']);
		$senha = ($_POST['senha_multiplicador']);
		$nivel_hierarquia = mysqli_real_escape_string($connect, $_POST['nivel_hierarquia']);


		if ($_POST['senha_multiplicador'] != $_POST['repete_senha']) {
			$erros[] = "Senhas não conferem";
		}
		$queryEmail = "SELECT email_multiplicador FROM multiplicador WHERE email_multiplicador = '$email' ";
		$buscaEmail = mysqli_query($connect, $queryEmail);
		$verifica = mysqli_num_rows($buscaEmail); # traz número de linhas
		if (!empty($verifica)) {
			$erros[] = "E-mail já cadastrado!";
		}

		$queryMatricula = "SELECT matricula FROM multiplicador WHERE matricula = '$matricula' ";
		$buscaMatricula = mysqli_query($connect, $queryMatricula);
		$verificaMatricula = mysqli_num_rows($buscaMatricula);
		if ($verificaMatricula > 0) {
			$erros[] = "Matrícula já cadastrada!";
		}

    if (strlen($matricula) < 7) {
        $erros[] = "Tamanho da matrícula inferior a 7 caracteres";
    }
    if (strlen($matricula) > 7) {
        $erros[] = "Tamanho da matrícula deve ser no máximo de 7 caracteres";
    }

    if (strlen($cpf) != 11) {
        $erros[] = "Tamanho do CPF inválido";
    }

    if (strlen($senha) < 8) {
        $erros[] = "Tamanho da senha deve ser de no mínimo 8 caracteres";
    }
			
		$queryCpf = "SELECT cpf_multiplicador FROM multiplicador WHERE cpf_multiplicador = '$cpf' ";
		$buscaCpf = mysqli_query($connect, $queryCpf);
		$verificaCpf = mysqli_num_rows($buscaCpf);
		if ($verificaCpf > 0) {
			$erros[] = "CPF já cadastrado!";
		}

		if (empty($erros)) {
			$query = "INSERT INTO multiplicador (nome_multiplicador,email_multiplicador,senha_multiplicador,matricula,cpf_multiplicador,endereco_multiplicador,nivel_hierarquia) 
			values ('$nome','$email','$senha','$matricula','$cpf','$endereco', '$nivel_hierarquia')";

			if (mysqli_query($connect, $query)) {
				// Verifica o nível de hierarquia do usuário logado
				if ($_SESSION['nivel_hierarquia'] == 'administrador') {
					// Administrador
					echo "<script>
							alert('Novo multiplicador cadastrado com sucesso.');
							window.location.href = 'multiplicadores.php'; // Redireciona para o painel do administrador
						</script>";
				} else {
					// Outros multiplicadores
					echo "<script>
							alert('Seu pedido foi enviado para revisão.');
							window.location.href = 'index.php'; // Redireciona para o index
						</script>";
				}
			} else {
				echo "Erro ao cadastrar usuário: " . mysqli_error($connect);
			}

		} else {
			foreach ($erros as $erro) {
				echo "<p>$erro</p>";
			}
		}
	}
}

function updateMultiplicador($connect)
{
	if (isset($_POST['atualizar']) and !empty($_POST['email_multiplicador'])) {
		$erros = array();
		$id_multiplicador = filter_input(INPUT_POST, "id_multiplicador", FILTER_VALIDATE_INT);
		$email_multiplicador = filter_input(INPUT_POST, 'email_multiplicador', FILTER_VALIDATE_EMAIL);
		$nome = mysqli_real_escape_string($connect, $_POST['nome_multiplicador']);
		$matricula = mysqli_real_escape_string($connect, $_POST['matricula']);
		$cpf= mysqli_real_escape_string($connect, $_POST['cpf_multiplicador']);
		$endereco= mysqli_real_escape_string($connect, $_POST['endereco_multiplicador']);
		$nivel_hierarquia = mysqli_real_escape_string($connect, $_POST['nivel_hierarquia']);
		$status = mysqli_real_escape_string($connect, $_POST['status_multiplicador']);
		$senha = "";

		if (strlen($nome) < 3) {
			$erros[] = "Nome muito curto";
		}

		if (empty($email_multiplicador)) {
			$erros[] = "Preencha seu e-mail corretamente";
		}

		if (!empty($_POST['senha_multiplicador'])) {
			if ($_POST['senha_multiplicador'] == $_POST['repetesenha']) {
				$senha = ($_POST['senha_multiplicador']);
			} else {
				$erros[] = "Senhas não conferem";
			}
		}

		// Verificar se não mudou o email
		$queryEmailAtual = "SELECT email_multiplicador FROM multiplicador where id_multiplicador = $id_multiplicador ";
		$buscaEmailAtual = mysqli_query($connect, $queryEmailAtual);
		$retornoEmail = mysqli_fetch_assoc($buscaEmailAtual);
		$queryEmail = "SELECT email_multiplicador FROM multiplicador WHERE email_multiplicador = '$email_multiplicador' AND  email_multiplicador <> '" . $retornoEmail['email_multiplicador'] . "'";
		$buscaEmail = mysqli_query($connect, $queryEmail);
		$verifica = mysqli_num_rows($buscaEmail); # traz número de linhas
		if (!empty($verifica)) {
			$erros[] = "E-mail já cadastrado!";
		}

		if (empty($erros)) {
			if (!empty($senha)) {
				$query = "UPDATE multiplicador SET 
                            nome_multiplicador='$nome', 
                            email_multiplicador='$email_multiplicador', 
                            matricula='$matricula',
							cpf_multiplicador='$cpf', 
               				endereco_multiplicador='$endereco',
                            senha_multiplicador='$senha', 
                            nivel_hierarquia='$nivel_hierarquia', 
                            status_multiplicador='$status' 
                          WHERE id_multiplicador='$id_multiplicador'";
			} else {
				$query = "UPDATE multiplicador SET 
                            nome_multiplicador='$nome', 
                            email_multiplicador='$email_multiplicador', 
                            matricula='$matricula', 
							cpf_multiplicador='$cpf', 
               				endereco_multiplicador='$endereco', 
                            nivel_hierarquia='$nivel_hierarquia', 
                            status_multiplicador='$status' 
                          WHERE id_multiplicador='$id_multiplicador'";
			}

			$executar = mysqli_query($connect, $query);
			if ($executar) {
			    "Usuário atualizado com sucesso!";
                header("Location: multiplicadores.php");
                exit(); // Certifique-se de que o script pare de ser executado após o redirecionamento
			} else {
				echo "Erro ao atualizar usuário: " . mysqli_error($connect);
			}
		} else {
			foreach ($erros as $erro) {
				echo "<p>$erro</p>";
			}
		}
	}
}



// Função para deletar um usuário
function deletar($connect, $usuario, $id_multiplicador)
{
	if (!empty($id_multiplicador)) {
		$query = "DELETE FROM $usuario WHERE id_multiplicador =" . (int) $id_multiplicador;
		$execute = mysqli_query($connect, $query);
		if ($execute) {
			echo "<script>
							alert('Multiplicador deletado com sucesso.');
							window.location.href = 'multiplicadores.php';
				</script>";
		} else {
			echo "Erro ao deletar!";
		}
	}
}


// // Buscar solicitações disponíveis
// function buscarSolicitacoesDisponiveis($connect) {
//     $query = "SELECT s.*, sl.endereco_solicitante, sl.email_solicitante, sl.responsavel FROM solicitacao s
//               INNER JOIN solicitante sl ON s.id_solicitante = sl.id_solicitante
//               WHERE s.id_multiplicador IS NULL ";
//     $result = mysqli_query($connect, $query);
//     $solicitacoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     return $solicitacoes;
// }

// // Buscar solicitações aceitas
// function buscarSolicitacoesAceitas($connect, $id_multiplicador) {
//     $query = "SELECT s.*, so.endereco_solicitante, so.responsavel, so.email_solicitante FROM solicitacao s
//               INNER JOIN solicitante so ON s.id_solicitante = so.id_solicitante
//               WHERE s.id_multiplicador = $id_multiplicador AND s.status_solicitacao = 'A'";
//     $result = mysqli_query($connect, $query);
//     $solicitacoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     return $solicitacoes;
// }

// // Aceitar uma solicitação
// function aceitarSolicitacao($connect, $id_solicitacao, $id_multiplicador) {
//     $query = "UPDATE solicitacao SET id_multiplicador = $id_multiplicador, status_solicitacao = 'A' WHERE id_solicitacao = $id_solicitacao";
//     $result = mysqli_query($connect, $query);
//     if ($result) {
//         header("Location: indexMultiplicador.php");
//         exit;
//     } else {
//         echo "Erro ao aceitar solicitação.";
//     }
// }

// // Desistir de uma solicitação
// function desistirSolicitacao($connect, $id_solicitacao, $id_multiplicador) {
//     $query = "UPDATE solicitacao SET id_multiplicador = NULL, status_solicitacao = 'E' WHERE id_solicitacao = $id_solicitacao";
//     $result = mysqli_query($connect, $query);
//     if ($result) {
//         header("Location: indexMultiplicador.php");
//         exit;
//     } else {
//         echo "Erro ao desistir da solicitação.";
//     }
// }
// function concluirSolicitacao($connect, $id_solicitacao) {
//     $query = "UPDATE solicitacao SET status_solicitacao = 'C' WHERE id_solicitacao = $id_solicitacao";
//     $result = mysqli_query($connect, $query);
//     if ($result) {
//         header("Location: indexMultiplicador.php");
//         exit;
//     } else {
//         echo "Erro ao concluir a solicitação: ";
//     }
// }

// function buscarEnderecoMultiplicador($connect, $id_multiplicador) {
//     $query = "SELECT endereco_multiplicador FROM multiplicador
//               WHERE id_multiplicador = $id_multiplicador";
//     $result = mysqli_query($connect, $query);
    
//     // Verifique se a consulta retornou algum resultado
//     if ($result && mysqli_num_rows($result) > 0) {
//         // Obtenha a primeira linha do resultado
//         $row = mysqli_fetch_assoc($result);
//         return $row['endereco_multiplicador'];
//     } else {
//         // Retorne null ou um valor padrão se nenhum resultado for encontrado
//         return null;
//     }
// }


function deletarSolicitacao($connect, $usuario, $id_solicitacao)
{
	if (!empty($id_solicitacao)) {
		$query = "DELETE FROM $usuario WHERE id_solicitacao =" . (int) $id_solicitacao;
		$execute = mysqli_query($connect, $query);
		if ($execute) {
			echo "<script>
							alert('Solicitacao deletado com sucesso.');
							window.location.href = 'gerenciarSolicitacoes.php';
				</script>";
		} else {
			echo "Erro ao deletar!";
		}
	}
}

function buscarSolicitacao($connect, $tabela, $where = 1, $order = "")
{
	if (!empty($order)) {
		$order = "ORDER BY $order";
	}
	$query = "
	SELECT s.id_solicitacao, s.id_solicitante, so.nome_instituicao, s.id_multiplicador, m.nome_multiplicador, 
		   s.data_criacao, s.descricao, s.status_solicitacao
	FROM solicitacao s
    JOIN solicitante so ON s.id_solicitante = so.id_solicitante
    LEFT JOIN multiplicador m ON s.id_multiplicador = m.id_multiplicador
	WHERE $where $order
	";
	$execute = mysqli_query($connect, $query);
	$results = mysqli_fetch_all($execute, MYSQLI_ASSOC);
	return $results;
}

// Função para buscar um usuário específico (solicitante)
function buscaUnicaSolicitante($connect, $tabela, $id) {
    // Monta a consulta SQL
    $query = "SELECT * FROM $tabela WHERE id_solicitante = " . (int)$id;
    // Executa a consulta
    $execute = mysqli_query($connect, $query);
    if ($execute === false) {
        die("Erro na consulta: " . mysqli_error($connect));
    }


    // Verifica se há resultados
    if (mysqli_num_rows($execute) > 0) {
        return mysqli_fetch_assoc($execute);
    } else {
        return null;
    }
}

function buscarSolicitante($connect, $tabela, $where = 1, $order = "")
{
    if (!empty($order)) {
        $order = "ORDER BY $order";
    }
    $query = "SELECT * FROM solicitante where $where $order";
    $execute = mysqli_query($connect, $query);
    $results = mysqli_fetch_all($execute, MYSQLI_ASSOC);
    return $results;
}

function inserirSolicitante($connect)
{
	if (isset($_POST['cadastrar']) and !empty($_POST['email_solicitante']) and !empty($_POST['senha_solicitante'])) {
		$erros = array();
		$email = filter_input(INPUT_POST, 'email_solicitante', FILTER_VALIDATE_EMAIL);
		$responsavel = mysqli_real_escape_string($connect, $_POST['responsavel']);
		$nome_instituicao = mysqli_real_escape_string($connect, $_POST['Nome_Instituicao']);
		$cnpj = mysqli_real_escape_string($connect, $_POST['cnpj']);
		$tipo_escola = mysqli_real_escape_string($connect, $_POST['tipo_escola']);
		$esfera = mysqli_real_escape_string($connect, $_POST['esfera']);
		$endereco = mysqli_real_escape_string($connect, $_POST['endereco_solicitante']);
		$senha = ($_POST['senha_solicitante']);
		

		if ($_POST['senha_solicitante'] != $_POST['repete_senha']) {
			$erros[] = "Senhas não conferem";
		}
		$queryEmail = "SELECT email_solicitante FROM solicitante WHERE email_solicitante = '$email' ";
		$buscaEmail = mysqli_query($connect, $queryEmail);
		$verifica = mysqli_num_rows($buscaEmail); # traz número de linhas
		if (!empty($verifica)) {
			$erros[] = "E-mail já cadastrado!";
		}

		$queryCnpj = "SELECT cnpj FROM solicitante WHERE cnpj = '$cnpj' ";
		$buscaCnpj = mysqli_query($connect, $queryCnpj);
		$verificaCnpj = mysqli_num_rows($buscaCnpj);
		if ($verificaCnpj > 0) {
			$erros[] = "CNPJ já cadastrado!";
		}

    if (strlen($cnpj) != 14) {
        $erros[] = "Tamanho do CNPJ inválido";
    }

    if (strlen($senha) < 8) {
        $erros[] = "Tamanho da senha deve ser de no mínimo 8 caracteres";
    }
			
		
		if (empty($erros)) {
			$query = "INSERT INTO solicitante (email_solicitante, senha_solicitante, responsavel,Nome_Instituicao, cnpj, tipo_escola, esfera, endereco_solicitante) 
			values ('$email','$senha','$responsavel','$nome_instituicao','$cnpj','$tipo_escola','$esfera','$endereco')";

			if (mysqli_query($connect, $query)) {
				// Verifica o nível de hierarquia do usuário logado
				if ($_SESSION['nivel_hierarquia'] == 'administrador') {
					// Administrador
					echo "<script>
							alert('Novo solicitante cadastrado com sucesso.');
							window.location.href = 'solicitante.php'; // Redireciona para o painel do administrador
						</script>";
				} else {
					// Outros multiplicadores
					echo "<script>
							alert('Seu pedido foi enviado para revisão.');
							window.location.href = 'index.php'; // Redireciona para o index
						</script>";
				}
			} else {
				echo "Erro ao cadastrar usuário: " . mysqli_error($connect);
			}

		} else {
			foreach ($erros as $erro) {
				echo "<p>$erro</p>";
			}
		}
	}
}

function updateSolicitante($connect)
{
	if (isset($_POST['atualizar']) and !empty($_POST['email_solicitante'])) {
		$erros = array();
		$id_solicitante = filter_input(INPUT_POST, "id_solicitante", FILTER_VALIDATE_INT);
		$email_solicitante = filter_input(INPUT_POST, 'email_solicitante', FILTER_VALIDATE_EMAIL);
		$responsavel = mysqli_real_escape_string($connect, $_POST['responsavel']);
		$nome_instituicao = mysqli_real_escape_string($connect, $_POST['Nome_Instituicao']);
		$cnpj= mysqli_real_escape_string($connect, $_POST['cnpj']);
		$tipo_escola= mysqli_real_escape_string($connect, $_POST['tipo_escola']);
		$esfera = mysqli_real_escape_string($connect, $_POST['esfera']);
		$endereco= mysqli_real_escape_string($connect, $_POST['endereco_solicitante']);
		$status = mysqli_real_escape_string($connect, $_POST['status_solicitante']);
		$senha = "";

		if (strlen($responsavel) < 3) {
			$erros[] = "Nome muito curto";
		}

		if (empty($email_solicitante)) {
			$erros[] = "Preencha seu e-mail corretamente";
		}

		if (!empty($_POST['senha_solicitante'])) {
			if ($_POST['senha_solicitante'] == $_POST['repetesenha']) {
				$senha = ($_POST['senha_solicitante']);
			} else {
				$erros[] = "Senhas não conferem";
			}
		}

		// Verificar se não mudou o email
		$queryEmailAtual = "SELECT email_solicitante FROM solicitante where id_solicitante = $id_solicitante ";
		$buscaEmailAtual = mysqli_query($connect, $queryEmailAtual);
		$retornoEmail = mysqli_fetch_assoc($buscaEmailAtual);
		$queryEmail = "SELECT email_solicitante FROM solicitante WHERE email_solicitante = '$email_solicitante' AND  email_solicitante <> '" . $retornoEmail['email_solicitante'] . "'";
		$buscaEmail = mysqli_query($connect, $queryEmail);
		$verifica = mysqli_num_rows($buscaEmail); # traz número de linhas
		if (!empty($verifica)) {
			$erros[] = "E-mail já cadastrado!";
		}

		if (empty($erros)) {
			if (!empty($senha)) {
				$query = "UPDATE solicitante SET 
                            responsavel='$responsavel', 
                            email_solicitante='$email_solicitante',
							Nome_Instituicao='$nome_instituicao', 
							cnpj='$cnpj', 
							tipo_escola='$tipo_escola',
							esfera='$esfera',
               				endereco_solicitante='$endereco',
                            senha_solicitante='$senha',  
                            status_solicitante='$status' 
                          WHERE id_solicitante='$id_solicitante'";
			} else {
				$query =  "UPDATE solicitante SET 
                            responsavel='$responsavel', 
                            email_solicitante='$email_solicitante', 
							Nome_Instituicao='$nome_instituicao',
							cnpj='$cnpj', 
							tipo_escola= '$tipo_escola',
							esfera='$esfera',
               				endereco_solicitante='$endereco',
                            status_solicitante='$status' 
                          WHERE id_solicitante='$id_solicitante'";
			}

			$executar = mysqli_query($connect, $query);
			if ($executar) {
			    "Usuário atualizado com sucesso!";
                header("Location: gerenciarSolicitante.php");
                exit(); // Certifique-se de que o script pare de ser executado após o redirecionamento
			} else {
				echo "Erro ao atualizar usuário: " . mysqli_error($connect);
			}
		} else {
			foreach ($erros as $erro) {
				echo "<p>$erro</p>";
			}
		}
	}
}

function deletarSolicitante($connect, $usuario, $id_solicitante)
{
	if (!empty($id_solicitante)) {
		$query = "DELETE FROM $usuario WHERE id_solicitante =" . (int) $id_solicitante;
		$execute = mysqli_query($connect, $query);
		if ($execute) {
			echo "<script>
							alert('Solicitante deletado com sucesso.');
							window.location.href = 'gerenciarSolicitante.php';
				</script>;";
		} else {
			echo "Erro ao deletar!";
		}
	}
}

function formatarCPF($cpf) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
}

function formatarCNPJ($cnpj) {
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj);
}


function inserirSolicitacao($connect)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['pedido']) && !empty($_POST['pedido'])) {
            $descricao = htmlspecialchars($_POST['pedido']);
            $id_solicitante = $_SESSION['id_solicitante'];
            if (strlen($descricao) < 4) {
                $erros[] = "A descrição do pedido deve conter pelo menos 4 caracteres.";
            }
            if (empty($erros)) {
                $query = "INSERT INTO solicitacao (id_solicitante, descricao) VALUES (?, ?)";
                $stmt = $connect->prepare($query);
                $stmt->bind_param("is", $id_solicitante, $descricao);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Pedido enviado com sucesso.');
                          </script>";
					$stmt->close(); 
					header('Location: ' . $_SERVER['PHP_SELF']);
					exit; 
                } else {
                    echo "Erro ao registrar o pedido: " . $stmt->error;
                }
	
                $stmt->close();
            } else {
                foreach ($erros as $erro) {
                    echo "<p>$erro</p>";
                }
            }

        } else {
            echo "Erro: Por favor, preencha o campo de pedido.";
        }
    }
}

function buscarSolicitacoesUsuarioLogado($connect, $id_solicitante) {
    $solicitacoes = array();

    $query = "SELECT id_solicitacao, descricao, status_solicitacao FROM solicitacao WHERE id_solicitante = ? ORDER BY data_criacao DESC";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $id_solicitante);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $solicitacoes[] = $row;
    }

    $stmt->close();
    return $solicitacoes;
}

function traduzirStatus($codigo) {
	switch ($codigo) {
		case 'A':
			return 'Aceita';
		case 'E':
			return 'Em aberto';
		case 'C':
			return 'Visita concluída';
		default:
			return 'Desconhecido';
	}
}
?>