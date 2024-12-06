<?php
include("config.php");
// Tratando o formulário de cadastro:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obter os dados do formulário
  $usuario = $_POST['usuario'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Verificar se o e-mail já está cadastrado
  $sql = "SELECT * FROM usuarios WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<script>
            alert('E-mail já cadastrado. Tente outro.');
            window.location.href = 'loginCadastro.html';
          </script>";
  } else {
    $sql = "INSERT INTO usuarios (usuario, senha, email) VALUES ('$usuario', '$senha', '$email')";

    if ($conn->query($sql) === TRUE) {
      echo "<script>
              alert('Cadastro realizado com sucesso!');
              window.location.href = 'loginCadastro.html';
            </script>";
    } else echo "<script> 
                  alert('Erro ao cadastrar: " . $conn->error . "');
                  window.location.href = 'loginCadastro.html';
                </script>";
  }
}

$conn->close();
