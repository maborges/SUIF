<?php
// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================

// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace("R$ ", "", $valor_x); //tira o símbolo
	$valor_2 = str_replace(".", "", $valor_1); //tira o ponto
	$valor_3 = str_replace(",", ".", $valor_2); //troca vírgula por ponto
	return $valor_3;
}
// ================================================================================================================

// ====== CONVERTE PESO ==========================================================================================
if ($config[30] == "troca(this)")
{
	function ConvertePeso($peso_x){
	$peso_1 = str_replace(",", ".", $peso_x);
	return $peso_1;}
}
else
{
	function ConvertePeso($peso_x){
	$peso_1 = str_replace(".", "", $peso_x);
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;}
}
// ================================================================================================================
?>