<?php
// Incluir aquivo de conex�o
include ('../../includes/conecta_bd.php');
 
// Recebe a id enviada no m�todo GET
$id = $_GET['id'];
 
// Seleciona a noticia que tem essa ID
$sql = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo = '".$id."'");
 
// Pega os dados e armazena em uma vari�vel
$noticia = mysqli_fetch_object($sql);
 
// Exibe o conte�do da notica
echo $noticia->conteudo;
 
// Acentua��o
header("Content-Type: text/html; charset=ISO-8859-1",true);
?>