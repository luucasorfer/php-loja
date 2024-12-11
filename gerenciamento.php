<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
  // Se não estiver logado, redirecionar para a página de login
  header('Location: loginCadastro.html');
  exit(); // Certifique-se de que o script não continue após o redirecionamento
} else {

  include 'src/php/crud.php'; // Incluir as funções CRUD

  $statusMessage = null;

  // Verificar se o formulário foi enviado para incluir ou editar produtos
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $imagem = $_FILES['imagem'];

    if ($id) {
      // Editar produto
      $statusMessage = editarProdutos($id, $nome, $preco, $quantidade, $imagem);
    } else {
      // Incluir produto
      $statusMessage = incluirProdutos($nome, $preco, $quantidade, $imagem);
    }
    // Armazena a mensagem de status na sessão
    $_SESSION['statusMessage'] = $statusMessage;
    // Redireciona após excluir
    header('Location: gerenciamento.php');
    exit(); // Certifique-se de que o script não continue após o redirecionamento
  }

  // Verificar se foi pedido para excluir um produto
  if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $statusMessage = excluirProdutos($id);

    // Armazena a mensagem de status na sessão
    $_SESSION['statusMessage'] = $statusMessage;
    // Redireciona para a página de login
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit(); // Garantir que o script pare após o redirecionamento
  }
}

// Verificar se foi solicitado logout
if (isset($_GET['logout'])) {
  // Destroi a sessão
  session_destroy();

  // Redireciona para a página de login
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro de Produtos</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link rel="stylesheet" href="./src/style/gerenciamento.css" />
</head>

<body>

  <?php if (isset($_SESSION['statusMessage'])): ?>
    <script>
      alert("<?php echo $_SESSION['statusMessage']['message']; ?>");
    </script>
    <?php unset($_SESSION['statusMessage']); // Limpa a mensagem da sessão 
    ?>
  <?php endif; ?>

  <div class="mask" id="mask"></div>
  <div class="overlay" id="overlay">
    <button class="close" onclick="closeForm()">
      <span class="material-symbols-outlined">close</span>
    </button>
    <div class="modal">
      <!-- Formulário para adicionar/editar produtos -->
      <form id="produtoForm" class="produtoForm" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
        <input type="hidden" name="id" id="productId" />
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required />

        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" id="preco" required />

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" required />

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" />

        <button type="submit">Salvar</button>
      </form>
    </div>
  </div>

  <!-- Barra lateral -->
  <nav class="menu-lateral">
    <img class="user-img" src="./src/image/user-img.svg" />
    <p class="user">Bem-vindo,
      <span class="nome-usuario"><?php echo $_SESSION['usuario']; ?> !</span>
    </p>
    <form method="GET" action="gerenciamento.php">
      <button class="logoff" name="logout">Sair</button>
    </form>

  </nav>

  <div class="content">
    <h1>Produtos Cadastrados</h1>

    <!-- Botão para exibir o formulário -->
    <button class="btn-add" onclick="openForm()">Novo Produto</button>

    <!-- Tabela de exibição dos produtos -->
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Preço</th>
          <th>Quantidade</th>
          <th>Imagem</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $produtos = listarProdutos();
        if ($produtos->num_rows > 0) {
          while ($produto = $produtos->fetch_assoc()) {
            echo "<tr>
                                <td>{$produto['nome']}</td>
                                <td>R$ {$produto['preco']}</td>
                                <td>{$produto['quantidade']}</td>
                                <td><img src='{$pastaImagem}{$produto['imagem']}' alt='Imagem do produto' /></td>
                                <td class='actions'>
                                    <a class='editar' onclick='editarProduto({$produto['id']}, \"{$produto['nome']}\", {$produto['preco']}, {$produto['quantidade']}, \"{$produto['imagem']}\")'>Editar</a>
                                    <a class='excluir' href='?excluir={$produto['id']}' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                                </td>
                              </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>Nenhum produto cadastrado.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script src="./src/js/scriptModal.js"></script>
</body>

</html>