<?php
include 'config.php';

// Armazenando a pasta onde será salva a imagem
$pastaImagem = 'src/imageProd/';

function incluirProdutos($nome, $preco, $quantidade, $imagem)
{
  global $conn;
  global $pastaImagem;

  // Verificar se a pasta existe, se não, cria
  if (!is_dir($pastaImagem)) {
    mkdir($pastaImagem, 0777, true); // Cria a pasta com permissão total
  }

  // Verifica se o arquivo foi enviado corretamente
  if ($imagem['error'] == 0) {
    $imagemNome = uniqid() . '-' . $imagem['name'];
    move_uploaded_file($imagem['tmp_name'], $pastaImagem . $imagemNome);
  } else {
    $imagemNome = ''; // Caso não tenha imagem
  }

  // SQL para inserir produto no banco
  $sql = "INSERT INTO produtos (nome, preco, quantidade, imagem)
            VALUES ('$nome', '$preco', '$quantidade', '$imagemNome')";

  if ($conn->query($sql) === TRUE) {
    return ["status" => "success", "message" => "Produto incluído com sucesso!"];
  } else {
    return ["status" => "error", "message" => "Erro ao incluir produto: " . $conn->error];
  }
}


function listarProdutos()
{
  global $conn;

  $sql = "SELECT * FROM produtos";
  $result = $conn->query($sql);
  return $result;
}

function editarProdutos($id, $nome, $preco, $quantidade, $imagem)
{
  global $conn;
  global $pastaImagem;

  // Buscar o nome da imagem anterior para não apagar sem querer
  $sql = "SELECT imagem FROM produtos WHERE id = $id";
  $result = $conn->query($sql);
  $produto = $result->fetch_assoc();
  $imagemAtual = $produto['imagem'];

  // Verificar se a imagem foi enviada
  if ($imagem['error'] == 0) {
    $imagemNome = uniqid() . '-' . $imagem['name'];
    move_uploaded_file($imagem['tmp_name'], $pastaImagem . $imagemNome);
  } else {
    $imagemNome = $imagemAtual; // Mantém a imagem antiga se nenhuma nova for enviada
  }

  // SQL para atualizar os dados do produto
  $sql = "UPDATE produtos SET nome = '$nome', preco = '$preco', quantidade = '$quantidade', imagem = '$imagemNome' WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    return ["status" => "success", "message" => "Produto editado com sucesso!"];
  } else {
    return ["status" => "error", "message" => "Erro ao editar produto: " . $conn->error];
  }
}

function excluirProdutos($id)
{
  global $conn;
  global $pastaImagem;

  // Buscar o nome da imagem associada ao produto para excluí-la
  $sql = "SELECT imagem FROM produtos WHERE id = $id";
  $result = $conn->query($sql);
  $produto = $result->fetch_assoc();
  $imagem = $produto['imagem'];

  // Verificar se existe uma imagem e excluir da pasta
  if ($imagem && file_exists($pastaImagem . $imagem)) {
    unlink($pastaImagem . $imagem); // Exclui a imagem da pasta
  }

  // SQL para excluir o produto do banco
  $sql = "DELETE FROM produtos WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    return ["status" => "success", "message" => "Produto excluído com sucesso!"];
  } else {
    return ["status" => "error", "message" => "Erro ao excluir produto: " . $conn->error];
  }
}
