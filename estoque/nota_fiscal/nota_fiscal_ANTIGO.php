<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'nota_fiscal';
$titulo = 'Estoque - Nota Fiscal de Entrada';
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
<body onload="javascript:foco('busca');">


<?php

// ==================================================================================================================	

$filial = $filial_usuario;

$numero_romaneio = $_POST["numero_romaneio"];
$num_romaneio_aux = $_POST["num_romaneio_aux"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$botao_relatorio = $_POST["botao_relatorio"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];
$codigo_nf = $_POST["codigo_nf"];

$codigo_fornecedor = $_POST["representante"];
$data_emissao = Helpers::ConverteData($_POST["data_emissao"]);
$numero_nf = $_POST["numero_nf"];
$unidade = $_POST["unidade"];
$quantidade = $_POST["quantidade"];
$valor_unitario = Helpers::ConverteValor($_POST["valor_unitario"]);
$observacao = $_POST["observacao"];

$valor_total = ($quantidade * $valor_unitario);



$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());


// =============================================================================================================
// =============================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor_aux = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],2,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,2,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],2,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],2,",",".");
$quantidade_aux = $aux_romaneio[10];
$quantidade_aux_print = number_format($aux_romaneio[10],2,",",".");
$unidade_x = $aux_romaneio[11];
$tipo_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao_aux = $aux_romaneio[18];
$usuario_cadastro_x = $aux_romaneio[19];
$hora_cadastro_x = $aux_romaneio[20];
$data_cadastro_x = $aux_romaneio[21];
$usuario_alteracao_x = $aux_romaneio[22];
$hora_alteracao_x = $aux_romaneio[23];
$data_alteracao_x = $aux_romaneio[24];
$filial_x = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];


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
	if ($unidade_x == "SC")
	{$unidade_print = "Sc";}
	elseif ($unidade_x == "KG")
	{$unidade_print = "Kg";}
	elseif ($unidade_x == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade_x == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_romaneio == "PRE_ROMANEIO")
	{$situacao_print = "Pr&eacute;-Romaneio";}
	elseif ($situacao_romaneio == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_romaneio == "FECHADO")
	{$situacao_print = "Fechado";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_aux' ORDER BY nome");
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



// ACHA FAVORECIDO  ==========================================================================================
$acha_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$codigo_fornecedor' ORDER BY nome");
$linha_acha_favorecido = mysqli_num_rows ($acha_favorecido);




// SOMA PAGAMENTOS  ==========================================================================================
//$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_romaneio' AND estado_registro='ATIVO'"));
//$saldo_pagamento = $valor_total - $soma_pagamentos[0];



// =================================================================================================================

if ($botao == "incluir")
{
	if ($codigo_fornecedor == '')
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_1.php");
	}

	elseif ($linha_acha_favorecido == 0)
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_7.php");
	}
	
	elseif ($data_emissao == '')
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_2.php");
	}

	elseif ($numero_nf == '')
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_6.php");
	}

	elseif ($unidade == '')
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_4.php");
	}

	elseif ($quantidade == '' or $quantidade <= 0)
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_5.php");
	}

	elseif (!is_numeric($quantidade))
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_8.php");
	}

	elseif ($valor_unitario == '' or $valor_unitario <= 0)
	{
	header ("Location: $servidor/$diretorio_servidor/estoque/nota_fiscal/aviso_3.php");
	}
	
	else
	{
	$inserir = mysqli_query ($conexao, "INSERT INTO nota_fiscal_entrada (codigo, codigo_romaneio, codigo_fornecedor, numero_nf, data_emissao, valor_unitario, unidade, quantidade, 	valor_total, observacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro) VALUES (NULL, '$numero_romaneio', '$codigo_fornecedor', '$numero_nf',	'$data_emissao', '$valor_unitario', '$unidade', '$quantidade', '$valor_total', '$observacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO')");
	}
}

elseif ($botao == "excluir")
{
$delete = mysqli_query ($conexao, "DELETE FROM nota_fiscal_entrada WHERE codigo='$codigo_nf'");
}

else
{}


// SOMA PAGAMENTOS  ==========================================================================================
$soma_pagamentos_2 = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_romaneio' AND estado_registro='ATIVO'"));
$saldo_pagamento_2 = $valor_total - $soma_pagamentos_2[0];
$saldo_pagamento_print = number_format($saldo_pagamento_2,2,",",".");


// =============================================================================================================
// =============================================================================================================
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




<div id="centro" style="height:400px; width:1080px; border:0px solid #0000FF; margin:auto">

<div id="espaco_2" style="width:1050px"></div>

<div id="centro" style="height:15px; width:1050px; border:0px solid #000; color:#003466; font-size:12px"></div>

<div id="centro" style="height:120px; width:1050px; border:0px solid #000; color:#003466; font-size:12px">

		<div id='centro' style='float:left; height:120px; width:1050px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:115px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:690px; color:#000066; text-align:left; border:1px solid #999; font-size:10px; line-height:15px'>
				<div style="margin-left:10px; margin-top:5px; margin-bottom:5px">
				<?php echo"
				N&ordm; do Romaneio: $numero_romaneio</br>
				Data: $data_print</br>
				Fornecedor: $fornecedor_print</br>
				Produto: $produto_print</br>
				Peso L&iacute;quido: $quantidade_aux_print Kg</br>
				Situa&ccedil;&atilde;o Romaneio: $situacao_print</br>
				"; ?>
				</div>
			</div>
		</div>
</div>
<div id="centro" style="height:10px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:200px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:690px; border:0px solid #000; color:#003466; font-size:14px; float:left">
<b>&#160;&#160;&#8226; Nota Fiscal de Entrada:</b>
</div>

<div id="centro" style="height:5px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<form name="compra_cafe" action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal/nota_fiscal.php" method="post">
<input type="hidden" name="numero_romaneio" value="<?php echo"$numero_romaneio"; ?>" />
<input type="hidden" name="botao" value="incluir" />
<input type="hidden" name="botao_relatorio" value="<?php echo"$botao_relatorio"; ?>" />
<input type='hidden' name='pagina_mae' value='<?php echo"$pagina_mae"; ?>'>
<input type='hidden' name='pagina_filha' value='<?php echo"$pagina_filha"; ?>'>
<input type='hidden' name='data_inicial' value='<?php echo"$data_inicial"; ?>'>
<input type='hidden' name='data_final' value='<?php echo"$data_final"; ?>'>
<input type='hidden' name='produto_list' value='<?php echo"$produto_list"; ?>'>
<input type='hidden' name='produtor_ficha' value='<?php echo"$produtor_ficha"; ?>'>
<input type='hidden' name='monstra_situacao' value='<?php echo"$monstra_situacao"; ?>'>




<div style="width:200px; height:200px; border:0px solid #000; float:left"></div>




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:845px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:795px; height:5px; border:0px solid #000"></div>Fornecedor / Produtor:</div>


<!-- =========================================  FAVORECIDO ====================================== -->
<div id="tabela_2" style="width:845px; border:0px solid #000">
<!-- <input type="text" name="descricao" id="ok" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:500px; font-size:12px" />-->
<!-- ========================================================================================================== -->
<script type="text/javascript">
function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=270,width=700');
	}

</script>
<script type="text/javascript" src="representante_funcao.js"></script>

<!-- ========================================================================================================== -->
<div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar produtor" />
<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/index_pessoas.php" target="_blank">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_editar.png" border="0" height="18px" title="Cadastrar novo produtor" /></a>
</div>

<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">
&#160;
<!-- ========================================================================================================== -->
<script type="text/javascript">
document.onkeyup=function(e)
	{
		if(e.which == 113)
		{
			//Pressionou F2, aqui vai a função para esta tecla.
			//alert(tecla F2);
			var aux_f2 = document.compra_cafe.representante.value;
			javascript:foco('busca');
			javascript:abrir('busca_pessoa_popup.php');
			//javascript:buscarNoticias(aux_f2);
		}
	}
</script>
<!-- ========================================================================================================== -->
<input id="busca" type="text" name="representante" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; width:50px; font-size:12px" />&#160;</div>
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="resultado" style="width:510px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


</div>


<!-- ========================================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Data de Emiss&atilde;o:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero da NF:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Unidade:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:300px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:295px; height:5px; border:0px solid #000"></div>Produto:</div>


<!-- =================================  DATA EMISSAO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="data_emissao" maxlength="10" onkeypress="mascara(this,data)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo date('d/m/Y')?>" id="calendario" />
</div>

<!-- =========================================  NUMERO N F ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="numero_nf" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>

<!-- =========================================  UNIDADE ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="unidade" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:154px; height:20px; font-size:12px" />
<option></option>
<option value="SC">Sc</option>
<option value="KG">Kg</option>
<option value="CX">Cx</option>
<option value="UN">Un</option>
</select>
</div>

<!-- =========================================  PRODUTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:300px; border:0px solid #000">
<input type="text" name="produto_disabled" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" value="<?php echo"$produto_print"; ?>" disabled="disabled" />
</div>


<!-- ========================================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Quantidade:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Valor Unit&aacute;rio:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!-- Unidade:--></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div><!-- Produto: --></div>


<!-- =================================  QUANTIDADE ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="quantidade" maxlength="15" onkeypress="troca(this)" onBlur="mascara(this,numero_real)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" />
</div>

<!-- =========================================  VALOR UNITARIO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="valor_unitario" maxlength="15" onkeypress="mascara(this,mvalor)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" />
</div>

<!-- =========================================  ############# ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<!-- <select name="unidade" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:154px; height:20px; font-size:12px" />
<option></option>
<option value="SC">Sc</option>
<option value="KG">Kg</option>
<option value="CX">Cx</option>
<option value="UN">Un</option>
</select> -->
</div>

<!-- =========================================  ############## ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<!-- <input type="text" name="numero_nf" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" value="<?php // echo"$produto_print"; ?>" disabled="disabled" /> -->
</div>


<!-- ========================================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!-- Valor Unit&aacute;rio: --></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!-- Unidade:--></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div><!-- Produto: --></div>


<!-- =================================  OBSERVACAO ====================================== -->
<div id="tabela_2" style="width:520px; border:0px solid #000">
<input type="text" name="observacao" maxlength="100" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:515px; font-size:12px; text-align:left" />
</div>


<!-- =========================================  ############## ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/botao_confirmar.png" border="0" style="margin-left:15px" />
</div>






</form>






</div>





<!-- ================== INICIO DO RELATORIO ================= -->
<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:5px;">

<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
<?php
$busca_nota_fiscal = mysqli_query ($conexao, "SELECT * FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio' ORDER BY data_emissao");
$linha_nota_fiscal = mysqli_num_rows ($busca_nota_fiscal);


if ($linha_nota_fiscal == 0)
{echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem notas fiscais para este romaneio.</i></div>";}
else
{echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='90px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='380px' align='center' bgcolor='#006699'>Fornecedor / Produtor</td>
<td width='120px' align='center' bgcolor='#006699'>N&ordm; Nota Fiscal</td>
<td width='122px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='60px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>
</div>
<div id='centro' style='height:10px; width:1030px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";

for ($w=1 ; $w<=$linha_nota_fiscal ; $w++)
{
	$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

// DADOS DO FAVORECIDO =========================
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
	$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
	$codigo_pessoa_2 = $aux_busca_favorecido_2[35];
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_busca_pessoa_2[1];
	$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
		if ($tipo_pessoa_2 == "pf")
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[3];}
		else
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[4];}

	$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));		
	$numero_nf_print_2 = $aux_nota_fiscal[3];
	$valor_unitario_print_2 = number_format($aux_nota_fiscal[5],2,",",".");
	$valor_total_print_2 = number_format($aux_nota_fiscal[8],2,",",".");
	$unidade_print_2 = $aux_nota_fiscal[6];
	$quantidade_print_2 = $aux_nota_fiscal[7];
	$observacao_print_2 = $aux_nota_fiscal[9];
	

// RELATORIO =========================
	echo "
	<tr style='color:#00F' title=' CPF/CNPJ: $cpf_cnpj_2 &#13; Observa&ccedil;&atilde;o: $observacao_print_2'>
	<td width='90px' align='left'>&#160;&#160;$data_nf_print_2</td>
	<td width='380px' align='left'>&#160;&#160;$nome_favorecido_2</td>
	<td width='120px' align='center'>$numero_nf_print_2</td>
	<td width='122px' align='center'>$quantidade_print_2 $unidade_print_2</td>
	<td width='122px' align='right'>$valor_unitario_print_2&#160;&#160;</td>
	<td width='122px' align='right'>&#160;&#160;$valor_total_print_2&#160;&#160;</td>
	<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal/nota_fiscal.php' method='post'>
		<input type='hidden' name='botao' value='excluir' />
		<input type='hidden' name='codigo_nf' value='$aux_nota_fiscal[0]' />
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='botao_relatorio' value='$botao_relatorio' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' border='0' /></form>
	</td>						
	</tr>";
}
echo "
</table>
</div>
<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->


<div id="centro" style="height:15px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center"></div>
<div id="centro" style="height:60px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center">
<div id='centro' style='float:left; height:55px; width:330px; color:#00F; text-align:center; border:0px solid #000'></div>
<?php
	if ($botao_relatorio == "relatorio")
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";
	}
	elseif ($pagina_filha == "entrada_enviar")
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";
	}
	else
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<!-- <a href='$servidor/$diretorio_servidor/compras/produtos/compra_selecionar.php' id='ok'>
		<img src='$servidor/$diretorio_servidor/imagens/botoes/nova_compra.jpg' border='0' /></a> -->
		</div>";
	}
	
		
		
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_romaneio.jpg' border='0' /></form>
		</div>";
?>
</div>

</div> <!-- ================================== FIM DA DIV CENTRO GERAL ======================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>