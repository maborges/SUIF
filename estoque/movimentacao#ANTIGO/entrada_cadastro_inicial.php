<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');

$pagina = 'entrada_cadastro_inicial';
$titulo = 'Estoque - Entrada';
$modulo = 'estoque';
$menu = 'movimentacao';
	
include ('../../includes/head.php'); 
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<?php


// =================================================================================================================
$numero_romaneio = $_POST["numero_romaneio"];
$pagina_mae = $_POST["pagina_mae"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$monstra_situacao = $_POST["monstra_situacao"];
$botao = $_POST["botao"];


// =============================================================================================================
// =============================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_final = $aux_romaneio[7];
$desconto_sacaria = $aux_romaneio[8];
$desconto = $aux_romaneio[9];
$quantidade = $aux_romaneio[10];
$unidade = $aux_romaneio[11];
$tipo_sacaria = $aux_romaneio[12];
$movimentacao = $aux_romaneio[13];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$quantidade_prevista = $aux_romaneio[27];
$quantidade_prevista_saca = ($aux_romaneio[27] / 60);
$quantidade_prevista_print = number_format($aux_romaneio[27],2,",",".");

// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{$unidade_print = "Sc";}
	elseif ($unidade == "KG")
	{$unidade_print = "Kg";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	else
	{$unidade_print = "";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao == "POSTO")
	{$situacao_print = "POSTO";}
	elseif ($situacao == "A_RETIRAR")
	{$situacao_print = "A RETIRAR";}
	elseif ($situacao == "ARMAZENADO")
	{$situacao_print = "ARMAZENADO";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($y=1 ; $y<=$linha_pessoa ; $y++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
	if ($aux_pessoa[2] == "pf")
	{$cpf_cnpj = $aux_pessoa[3];}
	else
	{$cpf_cnpj = $aux_pessoa[4];}
}


// =========================================================================================
if ($numero_romaneio == '')
{
header ("Location: $servidor/$diretorio_servidor/estoque/movimentacao/aviso_2.php");
}
elseif ($situacao_romaneio != 'PRE_ROMANEIO')
{
header ("Location: $servidor/$diretorio_servidor/estoque/movimentacao/aviso_1.php");
}
else
{}
// =========================================================================================


?>



<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>

<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:400px; width:1000px; border:0px solid #000; margin:auto">

<div id="espaco_2" style="width:995px"></div>

<div id="centro" style="height:50px; width:995px; border:0px solid #000; color:#003466; font-size:12px">
<div id="centro" style="height:30px; width:500px; border:0px solid #000; color:#003466; font-size:14px; margin-left:180px; margin-top:18px"><b>&bull; Entrada - Peso Inicial</b></div>
</div>

<form name="entrada_inicial" action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_enviar_inicial.php" method="post">


<!-- ====================================================================================== -->
<div style="width:190px; height:235px; border:0px solid #000; float:left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/estoque_entrada.jpg" border="0" alt="entrada" title="Estoque - Entrada" style="margin-top:20px" />
</div>

<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero do Romaneio:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Produto:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:350px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:345px; height:5px; border:0px solid #000"></div>Fornecedor:</div>




<!-- =========================================  CODIGO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="numero_romaneio_aux" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$numero_romaneio"; ?>" disabled="disabled" />
<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />
<input type="hidden" name="representante" value="<?php echo "$fornecedor"; ?>" />
<input type="hidden" name="produto_list" value="<?php echo "$produto"; ?>" />
<input type="hidden" name="pagina" value="<?php echo "$pagina"; ?>" />
<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
<input type="hidden" name="data_inicial" value="<?php echo "$data_inicial"; ?>" />
<input type="hidden" name="data_final" value="<?php echo "$data_final"; ?>" />
<input type="hidden" name="monstra_situacao" value="<?php echo "$monstra_situacao"; ?>" />
<input type="hidden" name="botao" value="<?php echo "$botao"; ?>" />
</div>




<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="produto_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px" value="<?php echo "$produto_print"; ?>" disabled="disabled" />
<!-- ========================================================================================================== -->
<!-- ========================================================================================================== -->
</div>


<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:350px; border:0px solid #000">
<input type="text" name="fornecedor_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:345px; font-size:12px" value="<?php echo "$fornecedor_print"; ?>" disabled="disabled" />
<!-- ========================================================================================================== -->
<!-- ========================================================================================================== -->
</div>






<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Peso Inicial (Kg):</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Tipo Sacaria:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Placa do Ve&iacute;culo:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Motorista:</div>

<!-- =========================================  PESO INICIAL ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="peso_inicial" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" id"ok" /></div>

<!-- =========================================  TIPO SACARIA ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="tipo_sacaria" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<?php

	$busca_tipo_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE movimentacao='ENTRADA' ORDER BY codigo");
	$linhas_tipo_sacaria = mysqli_num_rows ($busca_tipo_sacaria);

for ($t=1 ; $t<=$linhas_tipo_sacaria ; $t++)
{
$aux_tipo_sacaria = mysqli_fetch_row($busca_tipo_sacaria);	

	if ($aux_tipo_sacaria[1] == "13%")
	{echo "<option selected='selected' value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
	else
	{echo "<option value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
}
?>
</select>
</div>

<!-- =========================================  PLACA DO VEICULO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="placa_veiculo" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>


<!-- ========================================= MOTORISTA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<input type="text" name="motorista" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>




<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<!-- =========================================  OBSERVAÇÃO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:689px" /></div>



<!-- =========================================  QUANTIDADE PREVISTA ====================================== -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Quantidade Prevista:</div>

<div id="tabela_2" style="width:730px; border:0px solid #000; color:#0000FF; font-size:12px;">
<input type="text" name="quantidade_prevista_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:200px" value="
<?php
if ($produto == "CAFE")
{echo"$quantidade_prevista_print Kg ($quantidade_prevista_saca Sacas)";}
else
{echo"$quantidade_prevista_print Kg";}
?>" disabled="disabled" />


</div>



<!-- =============================================================================================== -->
<div id="espaco_2" style="width:925px"></div>

<div id="tabela_2" style="width:400px; text-align:center; border:0px solid #000"></div>
<div id="tabela_2" style="width:250px; text-align:center; border:0px solid #000">
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/botao_confirmar_2.png" border="0" style="float:left" />
</form>

<?php
if ($botao == "RELATORIO")
{
echo "
<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
<input type='hidden' name='data_inicial' value='$data_inicial'>
<input type='hidden' name='data_final' value='$data_final'>
<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/cancelar.png' border='0' />
</form>
";
}
else
{echo "<a href='$servidor/$diretorio_servidor/estoque/movimentacao/entrada_seleciona.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/cancelar.png' border='0' alt='cancelar' /></a>";}
?>
</div>










</div>
</div>




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>