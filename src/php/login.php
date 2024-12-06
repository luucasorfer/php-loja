<?php
include("config.php");

// Começa a sessão
session_start();

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

      if ($email === 'admin@teste.com') {
        echo "<script>
                    alert('Login realizado com sucesso! Bem-vindo, " . $row['usuario'] . "!');
                    window.location.href = '../../gerenciamento.php';
                    </script>";
      } else {
        echo "<script>
                    alert('Login realizado com sucesso! Bem-vindo, " . $row['usuario'] . "!');
                    window.location.href = '../../index.html';
                    </script>";
      }
    } else {
      // Senha incorreta
      echo "<script> 
                alert('Senha incorreta.'); 
                window.location.href = '../../loginCadastro.html';
            </script>";
    }
  } else {
    // E-mail não encontrado
    echo "<script> 
            alert('E-mail não encontrado.'); 
            window.location.href = '../../loginCadastro.html';
        </script>";
  }
}

// Tratando logout
if (isset($_GET['logout'])) {
  // Destroi a sessão
  session_destroy();
  // Redireciona para a página de login
  echo "<script> window.location.href = '../../loginCadastro.html'; </script>";
}

$conn->close();
