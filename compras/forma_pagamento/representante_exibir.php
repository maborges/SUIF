<?php
// Incluir aquivo de conexуo
include ('../../includes/conecta_bd.php');
 
// Recebe a id enviada no mщtodo GET
$id = $_GET['id'];
 
// Seleciona a noticia que tem essa ID
$sql = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo = '".$id."'");
 
// Pega os dados e armazena em uma variсvel
$noticia = mysqli_fetch_object($sql);
 
// Exibe o conteњdo da notica
echo $noticia->conteudo;
 
// Acentuaчуo
header("Content-Type: text/html; charset=ISO-8859-1",true);
?>