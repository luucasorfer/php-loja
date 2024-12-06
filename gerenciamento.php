<?php
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
}

// Verificar se foi pedido para excluir um produto
if (isset($_GET['excluir'])) {
  $id = $_GET['excluir'];
  $statusMessage = excluirProdutos($id);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro de Produtos</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link rel="stylesheet" href="./src/style/styleCrud.css" />
</head>

<body>

  <?php if ($statusMessage): ?>
    <script>
      alert("<?php echo $statusMessage['message']; ?>");
    </script>
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
    <img class="user-img" />
    <p class="user">Bem-vindo, Admin!</p>
    <form method="GET" action="">
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
                                <td><img src='./src/image/{$produto['imagem']}' alt='Imagem do produto' /></td>
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

  <script>
    const mask = document.getElementById("mask");
    const overlay = document.getElementById("overlay");

    function openForm() {
      // Exibir o modal
      mask.style.display = "block";
      overlay.style.display = "flex";
    }

    function closeForm() {
      // Limpar os dados do formulário
      document.getElementById("produtoForm").reset();
      // Esconder o modal
      mask.style.display = "none";
      overlay.style.display = "none";
    }

    function editarProduto(id, nome, preco, quantidade, imagem) {
      document.getElementById("productId").value = id;
      document.getElementById("nome").value = nome;
      document.getElementById("preco").value = preco;
      document.getElementById("quantidade").value = quantidade;
      // Limpa o campo de imagem
      document.getElementById("imagem").value = '';
      openForm();
    }
  </script>
</body>

</html>