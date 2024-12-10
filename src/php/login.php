<?php
include("config.php");

// Começa a sessão
session_start();

// Função para apresentar a mensagem e redirecionar para a pagina desejada
function redirectWithAlert($message, $url)
{
  echo "<script>
          alert('$message');
          window.location.href = '$url';
        </script>";
  exit();  // Garantir que o script pare após o redirecionamento
}

// Tratando o formulário de login:
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
  // Obter os dados do formulário
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Verificar se o e-mail existe no banco de dados
  $sql = "SELECT * FROM usuarios WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Usuário encontrado, verificar a senha
    $row = $result->fetch_assoc();

    // Comparação da senha digitada com a do banco
    if ($senha === $row['senha']) {
      // Armazena o e-mail e o nome do usuário na sessão
      $_SESSION['email'] = $row['email'];
      $_SESSION['usuario'] = $row['usuario'];

      if ($email === 'admin@teste.com')
        redirectWithAlert('Login realizado com sucesso! Bem-vindo, ' . $_SESSION['usuario'] . '!', '../../gerenciamento.php');
      else redirectWithAlert('Login realizado com sucesso! Bem-vindo, ' . $_SESSION['usuario'] . '!', '../../index.html');
    } else
      // Senha incorreta
      redirectWithAlert('Senha incorreta!', '../../loginCadastro.html');
  } else
    // E-mail não encontrado
    redirectWithAlert('E-mail não encontrado.', '../../loginCadastro.html');
}

$conn->close();
