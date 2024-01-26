<?php
// Incluir aquivo de conexão
include ('../../includes/conecta_bd.php');
 
// Recebe o valor enviado
$valor = $_GET['valor'];
 
// Procura titulos no banco relacionados ao valor
$sql = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='".$valor."'");

 
// Exibe todos os valores encontrados
while ($noticias = mysqli_fetch_object($sql)) {
	echo "" . $noticias->nome . "";
//	echo "<a href=\"javascript:func()\" onclick=\"exibirConteudo('".$noticias->codigo."')\">" . $noticias->nome . "</a><br />";
}
 
// Acentuação
//header("Content-Type: text/html; charset=ISO-8859-1",true);
?>