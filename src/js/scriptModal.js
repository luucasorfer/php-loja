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
  document.getElementById("imagem").value = "";
  openForm();
}
