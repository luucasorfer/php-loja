<?php
include 'config.php';

function incluirProdutos($nome, $preco, $quantidade, $imagem)
{
  global $conn;

  if ($imagem['error'] == 0) {
    $imagemNome = uniqid() . '-' . $imagem['name'];
    move_uploaded_file($imagem['tmp_name'], 'src/imageProd/' . $imagemNome);
  } else {
    $imagemNome = ''; // Caso não tenha imagem
  }

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

  if ($imagem['error'] == 0) {
    $imagemNome = uniqid() . '-' . $imagem['name'];
    move_uploaded_file($imagem['tmp_name'], 'src/image/' . $imagemNome);
  } else {
    $imagemNome = ''; // Caso não tenha imagem
  }

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

  $sql = "DELETE FROM produtos WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    return ["status" => "success", "message" => "Produto excluído com sucesso!"];
  } else {
    return ["status" => "error", "message" => "Erro ao excluir produto: " . $conn->error];
  }
}
