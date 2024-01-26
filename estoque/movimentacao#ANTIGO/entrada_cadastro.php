<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_cadastro';
$titulo = 'Estoque - Entrada';
$modulo = 'estoque';
$menu = 'movimentacao';
// ======================================================================================================


// ====== CONTADOR NÚMERO ROMANEIO ==========================================================================
$busca_numero_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnr = mysqli_fetch_row($busca_numero_romaneio);
$numero_romaneio = $aux_bnr[8];

$contador_num_romaneio = $numero_romaneio + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_num_romaneio'");
// ================================================================================================================


// ======================================================================================================
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


<form name="compra_cafe" action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_enviar_inicial.php" method="post">
<input type="hidden" name="unidade" value="KG" />

<!-- ====================================================================================== -->
<div style="width:190px; height:235px; border:0px solid #000; float:left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/estoque_entrada.jpg" border="0" alt="entrada" title="Estoque - Entrada" style="margin-top:20px" />
</div>

<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero do Romaneio:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:600px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:595px; height:5px; border:0px solid #000"></div>Fornecedor:</div>





<!-- =========================================  CODIGO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="numero_romaneio_aux" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$numero_romaneio"; ?>" disabled="disabled" />
<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />
<input type="hidden" name="pagina_aux" value="<?php echo "$pagina"; ?>" />

</div>

<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:570px; border:0px solid #000">
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
<div id="resultado" style="width:325px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


</div>




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Peso Inicial (Kg):</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Produto:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Tipo Sacaria:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Motorista:</div>

<!-- =========================================  PESO INICIAL ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="peso_inicial" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" /></div>

<!-- =========================================  PRODUTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="produto_list" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row($busca_produto_list);	
		if ($aux_produto_list[20] == $produto_list)
		{
		echo "<option selected='selected' value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
		}
		else
		{
		echo "<option value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
		}
	}
?>
</select>
</div>

<!-- ========================================= TIPO SACARIA  ====================================== -->
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


<!-- ========================================= MOTORISTA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<input type="text" name="motorista" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>



<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>CPF Motorista:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Placa do Ve&iacute;culo:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&ordm; Romaneio Manual:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Filial Origem:</div>





<!-- =========================================  CPF MOTORISTA ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="motorista_cpf" maxlength="14" onkeypress="mascara(this,num_cpf)" onBlur="mascara(this,num_cpf)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>

<!-- =========================================  PLACA DO VEICULO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="placa_veiculo" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" />
</div>

<!-- ========================================= NUMERO ROMANEIO MANUAL  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="num_romaneio_manual" maxlength="20" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px" />
</div>



<!-- ========================================= FILIAL ORIGEM  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="filial_origem" style="color:#0000FF; width:145px; font-size:12px" />
<option></option>
<?php
	$busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
	$linhas_filial_origem = mysqli_num_rows ($busca_filial_origem);

for ($f=1 ; $f<=$linhas_filial_origem ; $f++)
{
$aux_filial_origem = mysqli_fetch_row($busca_filial_origem);	
	echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";
}
?>
</select>

</div>



<!-- 
====================================================================================== 
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Data de Pagamento:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Forma de Pagamento:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:430px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:425px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>


 =========================================  DATA DE PAGAMENTO ====================================== 
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="data_pagamento" value='<?php // $hoje = date ("d/m/Y", time()); echo "$hoje"; ?>' size='14' maxlength='10' onkeypress='mascara(this,data)' onkeydown='if (getKey(event) == 13) return false;' style="color:#0000FF; width:145px; font-size:12px; text-align:center" /></div>

 =========================================  FORMA DE PAGAMENTO ====================================== 
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="forma_pagamento" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px" />
<option></option>
<option value="DINHEIRO">DINHEIRO</option>
<option value="CHEQUE">CHEQUE</option>
<option value="TED">TRANSFER&Ecirc;NCIA BANC&Aacute;RIA</option>
</select>
</div>



 ========================================= OBSERVAÇÃO  ====================================== 
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:420px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:325px" />
</div>
-->

<!--						SACARIA
============================================================================================
<input type="radio" name="sacaria" value="DEVOLVIDA" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px" />DEVOLVIDA
&#160;&#160;&#160;
<input type="radio" name="sacaria" value="NAO_DEVOLVIDA" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px" />N&Atilde;O DEVOLVIDA
&#160;
<input type="radio" name="sacaria" value="BAG" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px" />BAG
</div>

-->

<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<!-- =========================================  OBSERVAÇÃO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:689px" /></div>





<!-- =============================================================================================== -->
<div id="espaco_2" style="width:925px"></div>

<div id="tabela_2" style="width:930px; text-align:center; border:0px solid #000">
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/botao_confirmar_2.png" border="0" />
<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_periodo.php"><img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/cancelar.png" border="0" alt="cancelar" /></a>
</div>



</form>






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