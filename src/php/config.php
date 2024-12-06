<?php
$host = 'localhost'; // ou o IP do seu servidor de banco de dados
$user = 'root'; // seu usuário do banco de dados
$pass = ''; // sua senha do banco de dados
$dbname = 'loja'; // nome do banco de dados

// Criação da conexão
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}
