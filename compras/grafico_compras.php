<?php
// ================================================================================================================
include ("../includes/config.php");
include ("../includes/conecta_bd.php");
include ("../includes/valida_cookies.php");
// ================================================================================================================


// ================================================================================================================
if ($filial_config[30] == "S")
{$mysql_filial = "filial IS NOT NULL";}
else	
{$mysql_filial = "filial='$filial_usuario'";}
// ================================================================================================================


// ================================================================================================================
$ano_atual = date("Y", time());
// ================================================================================================================


// BUSCA DADOS ================================================================================================
$b_cafe = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='2'");
$achou_cafe = mysqli_num_rows ($b_cafe);

$b_pimenta = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
$achou_pimenta = mysqli_num_rows ($b_pimenta);

$b_cacau = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='4'");
$achou_cacau = mysqli_num_rows ($b_cacau);
// ================================================================================================================


if ($achou_cafe == 1)
{
// CAFÉ CONILON ================================================================================================
$cafe_jan = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='1' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_jan = ceil($cafe_jan[0] * 60);
$cafe_fev = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='2' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_fev = ceil($cafe_fev[0] * 60);
$cafe_mar = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='3' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_mar = ceil($cafe_mar[0] * 60);
$cafe_abr = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='4' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_abr = ceil($cafe_abr[0] * 60);
$cafe_mai = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='5' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_mai = ceil($cafe_mai[0] * 60);
$cafe_jun = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='6' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_jun = ceil($cafe_jun[0] * 60);
$cafe_jul = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='7' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_jul = ceil($cafe_jul[0] * 60);
$cafe_ago = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='8' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_ago = ceil($cafe_ago[0] * 60);
$cafe_set = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='9' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_set = ceil($cafe_set[0] * 60);
$cafe_out = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='10' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_out = ceil($cafe_out[0] * 60);
$cafe_nov = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='11' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_nov = ceil($cafe_nov[0] * 60);
$cafe_dez = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='12' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
$cafe_dez = ceil($cafe_dez[0] * 60);
// ================================================================================================================
}


if ($achou_pimenta == 1)
{
// PIMENTA =====================================================================================================
$pimenta_jan = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='1' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_jan = ceil($pimenta_jan[0]);
$pimenta_fev = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='2' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_fev = ceil($pimenta_fev[0]);
$pimenta_mar = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='3' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_mar = ceil($pimenta_mar[0]);
$pimenta_abr = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='4' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_abr = ceil($pimenta_abr[0]);
$pimenta_mai = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='5' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_mai = ceil($pimenta_mai[0]);
$pimenta_jun = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='6' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_jun = ceil($pimenta_jun[0]);
$pimenta_jul = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='7' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_jul = ceil($pimenta_jul[0]);
$pimenta_ago = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='8' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_ago = ceil($pimenta_ago[0]);
$pimenta_set = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='9' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_set = ceil($pimenta_set[0]);
$pimenta_out = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='10' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_out = ceil($pimenta_out[0]);
$pimenta_nov = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='11' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_nov = ceil($pimenta_nov[0]);
$pimenta_dez = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='12' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND (cod_produto='3' OR cod_produto='13')"));
$pimenta_dez = ceil($pimenta_dez[0]);
// ================================================================================================================
}


if ($achou_cacau == 1)
{
// CACAU ==========================================================================================================
$cacau_jan = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='1' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_jan = ceil($cacau_jan[0]);
$cacau_fev = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='2' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_fev = ceil($cacau_fev[0]);
$cacau_mar = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='3' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_mar = ceil($cacau_mar[0]);
$cacau_abr = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='4' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_abr = ceil($cacau_abr[0]);
$cacau_mai = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='5' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_mai = ceil($cacau_mai[0]);
$cacau_jun = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='6' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_jun = ceil($cacau_jun[0]);
$cacau_jul = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='7' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_jul = ceil($cacau_jul[0]);
$cacau_ago = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='8' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_ago = ceil($cacau_ago[0]);
$cacau_set = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='9' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_set = ceil($cacau_set[0]);
$cacau_out = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='10' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_out = ceil($cacau_out[0]);
$cacau_nov = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='11' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_nov = ceil($cacau_nov[0]);
$cacau_dez = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='12' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
$cacau_dez = ceil($cacau_dez[0]);
// ================================================================================================================
}




// GERA GRAFICO ================================================================================================
header("content-type: image/png");

$prod_cafe = "Cafe Conilon";
$prod_pimenta = "Pimenta do Reino";
$prod_cacau = "Cacau";

// TABELA DE CORES
// http://www.erikasarti.com/html/tabela-cores/
// https://www.peko-step.com/pt/tool/tfcolor.html


// configurações do gráfico
$titulo = "GRAFICO";
$largura = 950;
$altura = 220;
$largura_eixo_x = 750;
$largura_eixo_y = 180;
$inicio_grafico_x = 60;
$inicio_grafico_y = 190;

// configurações da legenda
$exibir_legenda = "sim";
$fonte = 3;
$largura_fonte = 8; // largura em pixels (2=6, 3=8, 4=10)
$altura_fonte = 10; // altura em pixels (2=8, 3=10, 4=12)
$espaco_entre_linhas = 10;
$margem_vertical = 5;

//canto superior direito da legenda
$lx = 970;
$ly = 10;



// cria imagem e define as cores
$imagem = imagecreate($largura, $altura);
$fundo = imagecolorallocate($imagem, 255, 255, 255);
$preto = imagecolorallocate($imagem, 0, 0, 0);
$azul = imagecolorallocate($imagem, 0, 0, 255);
$azul_escuro = imagecolorallocate($imagem, 0, 52, 102);
$verde = imagecolorallocate($imagem, 0, 255, 0);
$vermelho = imagecolorallocate($imagem, 255, 0, 0);
$amarelo = imagecolorallocate($imagem, 255, 255, 0);
$cinza_claro = imagecolorallocate($imagem, 207, 207, 207);
$cinza_escuro = imagecolorallocate($imagem, 105, 105, 105);
$cinza_preto = imagecolorallocate($imagem, 68, 68, 68);

$cor_cafe = imagecolorallocate($imagem, 0, 153, 0);
$cor_pimenta = imagecolorallocate($imagem, 128, 0, 0);
$cor_cacau = imagecolorallocate($imagem, 70, 130, 180);

// Define os dados
// Linhas representam os valores, colunas representam os intervalos

$texto_coluna = array ("Janeiro", "Fevereiro", "Marco", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$formatos = array ("quadrado", "quadrado", "quadrado", "quadrado");
$cores_linha = array ($cor_cafe, $cor_pimenta, $cor_cacau);

if ($achou_cafe == 1 and $achou_pimenta == 1 and $achou_cacau == 1)
{$texto_linha = array ($prod_cafe, $prod_pimenta, $prod_cacau);}
else
{$texto_linha = array ($prod_cafe, $prod_pimenta);}

if ($achou_cafe == 1 and $achou_pimenta == 1 and $achou_cacau == 1)
{
$valores = array ($cafe_jan, $cafe_fev, $cafe_mar, $cafe_abr, $cafe_mai, $cafe_jun, $cafe_jul, $cafe_ago, $cafe_set, $cafe_out, $cafe_nov, $cafe_dez, $pimenta_jan, $pimenta_fev, $pimenta_mar, $pimenta_abr, $pimenta_mai, $pimenta_jun, $pimenta_jul, $pimenta_ago, $pimenta_set, $pimenta_out, $pimenta_nov, $pimenta_dez, $cacau_jan, $cacau_fev, $cacau_mar, $cacau_abr, $cacau_mai, $cacau_jun, $cacau_jul, $cacau_ago, $cacau_set, $cacau_out, $cacau_nov, $cacau_dez);
}
else
{
$valores = array ($cafe_jan, $cafe_fev, $cafe_mar, $cafe_abr, $cafe_mai, $cafe_jun, $cafe_jul, $cafe_ago, $cafe_set, $cafe_out, $cafe_nov, $cafe_dez, $pimenta_jan, $pimenta_fev, $pimenta_mar, $pimenta_abr, $pimenta_mai, $pimenta_jun, $pimenta_jul, $pimenta_ago, $pimenta_set, $pimenta_out, $pimenta_nov, $pimenta_dez);
}

$numero_linhas = sizeof($texto_linha);
$numero_colunas = sizeof($texto_coluna);
$numero_valores = sizeof($valores);

// Obtem os valores minimo e maximo de Y
$y_maximo = 0;
for ($i = 0; $i < $numero_valores; $i++)
	if ($valores[$i] > $y_maximo)
	$y_maximo = $valores[$i];

// calcula o intervalo de variação entre os pontos de y
$fator = pow (10, strlen(intval($y_maximo)) - 1);
if ($y_maximo < 1)
	$variacao = 0.1;
elseif ($y_maximo < 10)
	$variacao = 1;
elseif ($y_maximo < 2*$fator)
	$variacao = $fator / 5;
elseif ($y_maximo < 5*$fator)
	$variacao = $fator / 2;
elseif ($y_maximo < 10*$fator)
	$variacao = $fator;

// calcula o numero de pontos no eixo Y
$num_pontos_eixo_y = 0;
$valor = 0;
while ($y_maximo >= $valor)
{
	$valor += $variacao;
	$num_pontos_eixo_y++;
}
$valor_topo = $valor;
$dist_entre_pontos = $largura_eixo_y / $num_pontos_eixo_y;

// titulo
//imagestring($imagem, 3, 3, 3, $titulo, $preto);

// eixos X e Y
imageline($imagem, $inicio_grafico_x, $inicio_grafico_y, $inicio_grafico_x+$largura_eixo_x, $inicio_grafico_y, $azul_escuro);
imageline($imagem, $inicio_grafico_x, $inicio_grafico_y, $inicio_grafico_x, $inicio_grafico_y - $largura_eixo_y, $azul_escuro);

// pontos no eixo Y
$posy = $inicio_grafico_y;
$valor = 0;

for ($i = 0; $i <= $num_pontos_eixo_y; $i++)
{
	$posx = $inicio_grafico_x - (strlen($valor)+2)*6; // 6 da largura da fonte + 2 espaços
	
	imagestring($imagem, 2, $posx, $posy-7, $valor, $azul_escuro);
	imageline($imagem, $inicio_grafico_x-6, $posy, $inicio_grafico_x+$largura_eixo_x, $posy, $cinza_claro);
	$valor += $variacao;
	$posy -= $dist_entre_pontos;
}

// imprime os labels
$largura_coluna = floor($largura_eixo_x / $numero_colunas);
$posx = $inicio_grafico_x;

for ($i = 0; $i < $numero_colunas; $i++)
{
	$pos_label_x = $posx + ($largura_coluna/2) - (strlen($texto_coluna[$i]) * 6 /2);
	$pos_label_y = $inicio_grafico_y + 10;
	imagestring($imagem, 2, $pos_label_x, $pos_label_y, $texto_coluna[$i], $azul_escuro);
	$posx += $largura_coluna;
}

// desenha linhas
for ($i = 0; $i < $numero_linhas; $i++)
{
	$posx = $inicio_grafico_x + $largura_coluna/2;
	$primeiro_ponto = true;
	$indice_j = $i * $numero_colunas;
	
	for ($j = $indice_j; $j < $indice_j+$numero_colunas; $j++)
	{
		if ($j > $indice_j)
			$primeiro_ponto = false;
		$posy = $valores[$j]/$valor_topo * $largura_eixo_y;
		$posy = $inicio_grafico_y - $posy;
		if (!$primeiro_ponto)
			if ($valores[$j] > 0) // Se o total do mês for ZERO, não dezenha linha
			{
			imageline($imagem, $ponto_inicio_x, $ponto_inicio_y, $posx, $posy, $cores_linha[$i]);
			}
			
		// desenha o ponto (de acordo com o formato definido)
		if ($formatos[$i] == "losango")
		{
			$pontos = array($posx-4, $posy, $posx, $posy-4, $posx+4, $posy, $posx, $posy+4);
			if ($valores[$j] > 0) // Se o total do mês for ZERO, não dezenha ponto
			{
			imagefilledpolygon($imagem, $pontos, 4, $cores_linha[$i]);
			imagestring($imagem, 4, $posx-4, $posy+6, $valores[$j], $cinza_preto);
			}
		}
		elseif ($formatos[$i] == "quadrado")
		{
			if ($valores[$j] > 0) // Se o total do mês for ZERO, não dezenha ponto
			{
			imagefilledrectangle($imagem, $posx-4, $posy-4, $posx+4, $posy+4, $cores_linha[$i]);
			imagestring($imagem, 4, $posx-4, $posy+6, $valores[$j], $cinza_preto);
//			imagestring($imagem, 4, $posx-4, $posy+6, $cinza_preto);
			}
		}
		elseif ($formatos[$i] == "triangulo")
		{
			$pontos = array($posx-4, $posy+4, $posx, $posy-4, $posx+4, $posy+4);
			if ($valores[$j] > 0) // Se o total do mês for ZERO, não dezenha ponto
			{
			imagefilledpolygon($imagem, $pontos, 3, $cores_linha[$i]);
			imagestring($imagem, 4, $posx-4, $posy+6, $valores[$j], $cinza_preto);
			}
		}
		$ponto_inicio_x = $posx;
		$ponto_inicio_y = $posy;
		
		$posx += $largura_coluna;
	}
}



// criação da legenda

if ($exibir_legenda == "sim")
{
	// acha a maior string
	$maior_tamanho = 0;
	for ($i = 0; $i < $numero_linhas; $i++)
		if (strlen($texto_linha[$i]) > $maior_tamanho)
			$maior_tamanho = strlen($texto_linha[$i]);
		
	// calcula os pontos de inicio e fim do quadrado
	$x_inicio_legenda = $lx - $largura_fonte * ($maior_tamanho+4);
	$y_inicio_legenda = $ly;
	$x_fim_legenda = $lx;
	$y_fim_legenda = $ly + $numero_linhas * ($largura_fonte + $espaco_entre_linhas) + 2*$margem_vertical;
//	imagerectangle($imagem, $x_inicio_legenda, $y_inicio_leganda, $x_fim_legenda, $y_fim_legenda, $cinza_claro);
	
	// começa a desenhar os dados
	for ($i = 0; $i < $numero_linhas; $i++)
	{
		$x_pos = $x_inicio_legenda + $largura_fonte*3;
		$y_pos = $y_inicio_legenda + $i * ($altura_fonte + $espaco_entre_linhas) + $margem_vertical;
		imagestring($imagem, $fonte, $x_pos, $y_pos, $texto_linha[$i], $cores_linha[$i]);
		imagefilledrectangle ($imagem, $x_pos-2*$largura_fonte, $y_pos, $x_pos-$largura_fonte, $y_pos+$altura_fonte, $cores_linha[$i]);
		imagerectangle ($imagem, $x_pos-2*$largura_fonte, $y_pos, $x_pos-$largura_fonte, $y_pos+$altura_fonte, $cores_linha[$i]);
	}
}



imagepng($imagem);
imagedestroy($imagem);







?>
