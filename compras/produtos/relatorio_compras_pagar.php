<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include("../../helpers.php");
$pagina = "relatorio_compras_pagar";
$titulo = "Saldo Financeiro a Pagar";
$modulo = "compras";
$menu = "relatorios";
	
// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$cod_produto = $_POST["cod_produto"];
$fornecedor = $_POST["fornecedor"];
$botao = $_POST["botao"];
// =================================================================================================================


// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

// SOMAS COMPRAS  ==========================================================================================
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =================================================================================================================


// =================================================================================================================
include ("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_relatorios.php"); ?>
</div>



<!-- =============================================   C E N T R O   =============================================== -->


<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- INÍCIO CENTRO GERAL -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Saldo de Compras a Pagar
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px">
	Por Fornecedor
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:10px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1060px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:left; margin-left:10px">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<form name="popup" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_compras_pagar.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Fornecedor:&#160;</i></div>

		<div id="centro" style="width:525px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:520px; height:3px; float:left; border:0px solid #999"></div>

			<!-- ========================================================================================================== -->
			<script type="text/javascript">
			function abrir(programa,janela)
				{
					if(janela=="") janela = "janela";
					window.open(programa,janela,'height=270,width=700');
				}
			
			</script>
			<script type="text/javascript" src="fornecedor_funcao.js"></script>
			
			<!-- ========================================================================================================== -->
			<div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
			<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar produtor" />
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_pessoa_fisica.php" target="_blank">
			<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/editar.png" border="0" height="18px" title="Cadastrar novo fornecedor" /></a>
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
						var aux_f2 = document.popup.fornecedor.value;
						javascript:foco('busca');
						javascript:abrir('busca_pessoa_popup.php');
						//javascript:buscarNoticias(aux_f2);
					}
				}
			</script>

			<!-- ========================================================================================================== -->
			<input id="busca" type="text" name="fornecedor" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " 
			style="color:#0000FF; width:50px; font-size:12px" value="<?php echo"$fornecedor"; ?>" />&#160;</div>
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="resultado" style="width:330px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:10px; font-style:normal; padding-top:3px; padding-left:5px"></div>


			</div>


		
			<div id="centro" style="width:60px; float:left; height:22px; border:0px solid #999; text-align:left">
			<div id="geral" style="width:55px; height:3px; float:left; border:0px solid #999"></div>
			<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
			</form>
			</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:50px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
	</div>
	
	<div id="centro" style="width:400px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
    <?php
	// BUSCA PESSOA  ==========================================================================================
	$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
	$linha_fornecedor = mysqli_num_rows ($busca_fornecedor);
		for ($f=1 ; $f<=$linha_fornecedor ; $f++)
		{
		$aux_fornecedor = mysqli_fetch_row($busca_fornecedor);
		$forn_print = $aux_fornecedor[1];
		}

	echo"<b>$forn_print</b>";
	?>
	</div>
	
	<div id="centro" style="width:250px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
	</div>
	
	<div id="centro" style="width:30px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
	</div>

</div>
<!-- ====================================================================================== -->







<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_compra == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_compra == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='80px' align='center' bgcolor='#006699'>Data</td>
<td width='350px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='85px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='85px' align='center' bgcolor='#006699'>Pre&ccedil;o Un</td>
<td width='95px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='95px' align='center' bgcolor='#006699'>Saldo a Pagar</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$num_compra_print = $aux_compra[1];
$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $aux_compra[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$usuario_alteracao = $aux_compra[21];
if ($aux_compra[23] == "")
{$data_alteracao = "";}
else
{$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));}
$hora_alteracao = $aux_compra[22];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf" or $aux_pessoa[2] == "PF")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


// ====== BUSCA PAGAMENTO  ==========================================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
// ======================================================================================================


// ====== CALCULO SALDO A PAGAR  ==========================================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");
// ======================================================================================================


// ====== CALCULO QUANTIDADE A ENTREGAR  ==========================================================================================
$quant_entregar = $saldo_a_pagar_aux / $preco_unitario;
$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
$quant_entregar_print = number_format($quant_entregar_aux,2,",",".");
// ======================================================================================================


// ====== RELATORIO  ==========================================================================================
	if ($soma_pagamentos[0] < $valor_total)
	{
	echo "<tr style='color:#006600' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";
	echo "
	<td width='80px' align='left'><div style='margin-left:5px'>$data_compra_print</div></td>
	<td width='350px' align='left'><div style='margin-left:5px'>$fornecedor_print</div></td>
	<td width='60px' align='center'>$num_compra_print</td>
	<td width='100px' align='center'>$produto_print_2</td>
	<td width='85px' align='center'>$quantidade_print $unidade_print</td>
	<td width='85px' align='right'><div style='margin-right:5px'>$preco_unitario_print</div></td>
	<td width='95px' align='right'><div style='margin-right:5px'>$valor_total_print</div></td>
	<td width='95px' align='right'><div style='margin-right:5px; color:#000099'>$saldo_a_pagar</div></td>
	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_visualizar.php' method='post'>
	<input type='hidden' name='numero_compra' value='$num_compra_print'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
	</td>
	
	</tr>";
	}
	
	else
	{}

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_compra == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma compra encontrada.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->



<!-- =========================================================================================================== -->
<div id="centro" style="height:8px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	</div>
	
	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	if ($linha_compra >= 1)
	{echo"SALDO TOTAL A PAGAR: <b>R$ $saldo_pagar_total_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- =========================================================================================================== -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>