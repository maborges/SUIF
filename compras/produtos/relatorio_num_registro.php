<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "relatorio_num_registro";
$titulo = "Excluir Registro";
$modulo = "compras";
$menu = "compras";

// ====== LIMITE DE DIAS PARA EXCLUSAO ==========================================================================
$busca_limite_exclusao = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_limite_exclusao = mysqli_fetch_row($busca_limite_exclusao);
$limite_exclusao = $aux_limite_exclusao[20];
// ========================================================================================================


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$numero_compra = $_POST["numero_compra"];

$botao = $_POST["botao"];
$monstra_situacao = "todas";
//$monstra_situacao = $_POST["monstra_situacao"];
// =======================================================================================================


// ====== BUSCA REGISTROS =================================================================================
if ($monstra_situacao == "todas")
{	
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra' AND (movimentacao='ENTRADA' OR movimentacao='COMPRA' OR movimentacao='SAIDA') AND filial='$filial' ORDER BY codigo");
//$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra' AND (movimentacao='ENTRADA' OR movimentacao='COMPRA' OR movimentacao='SAIDA' OR movimentacao='ENTRADA_FUTURO' OR movimentacao='SAIDA_FUTURO') AND filial='$filial' ORDER BY codigo");
//	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
}

else
{
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro='ATIVO' AND numero_compra='$numero_compra' AND (movimentacao='ENTRADA' OR movimentacao='COMPRA' OR movimentacao='SAIDA') AND filial='$filial' ORDER BY codigo");
//$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro='ATIVO' AND numero_compra='$numero_compra' AND (movimentacao='ENTRADA' OR movimentacao='COMPRA' OR movimentacao='SAIDA' OR movimentacao='ENTRADA_FUTURO' OR movimentacao='SAIDA_FUTURO') AND filial='$filial' ORDER BY codigo");
//	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro='ATIVO' AND numero_compra='$numero_compra' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
}
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ===========================================================================================================
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
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:460px; width:1080px; border:0px solid #000; margin:auto">

<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Excluir Registro
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    	<div id="menu_atalho_3" style="margin-top:7px">
		<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_registros_excluidos.php' >
        &#8226; Relat&oacute;rio de registros exclu&iacute;dos</a>
        </div>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    Compras, Entradas e Sa&iacute;das
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div style="height:30px; width:940px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left; margin-left:70px">
	<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_num_registro.php" method="post" />
	<input type='hidden' name='botao' value='1' />
		<div style="width:160px; float:left; border:0px solid #999; color:#666; text-align:right; margin-top:7px">
		<i>N&uacute;mero do registro:</i>
        </div>

		<div style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:4px; margin-left:20px">
		<input type="text" name="numero_compra" maxlength="15" id="ok" style="color:#0000FF; width:90px" value="<?php echo"$numero_compra"; ?>" />
		</div>

<!--		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todas</i>";}
			*/?>
		</div>
		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			*/?>
		</div>
		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "pagas")
			{echo "<input type='radio' name='monstra_situacao' value='pagas' checked='checked' /><i>Pagas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagas' /><i>Pagas</i>";}
			*/?>
		</div>
-->
		<div style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>











<div id='centro' style='float:left; height:25px; width:1045px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
</div>

<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$numero_compra = $aux_compra[1];
$produto = $aux_compra[3];
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
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$estado_registro = $aux_compra[24];
$movimentacao = $aux_compra[16];
$usuario_cadastro = $aux_compra[18];
$hora_cadastro = $aux_compra[19];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$usuario_exclusao = $aux_compra[31];
$hora_exclusao = $aux_compra[33];
$data_exclusao = date('d/m/Y', strtotime($aux_compra[32]));
$motivo_exclusao = $aux_compra[34];
$cod_produto = $aux_compra[39];


// ====== CALCULA LIMITE DIAS P/ EXCLUSAO ===============================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_compra);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias > $limite_exclusao)
{$pode_excluir = "N";}
else
{$pode_excluir = "S";}
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
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


// ====== BUSCA PAGAMENTO  ===============================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");

}

if ($botao != 1)
{
		echo "
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			
			<div style='width:654px; height:15px; border:1px solid #FFF; float:left; color:#F00; text-align:center; font-size:12px; margin-left:0px'>
			</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>
		</div>";
}


elseif ($linha_compra == 0 and $botao == 1)
{
		echo "
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			
			<div style='width:654px; height:15px; border:1px solid #FFF; float:left; color:#F00; text-align:center; font-size:12px; margin-left:0px'>
			<i>Nenhum registro encontrado.</i></div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>
		</div>";
}

elseif ($numero_compra == 0 or $numero_compra == "" or $numero_compra == " ")
{
		echo "
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			
			<div style='width:654px; height:15px; border:1px solid #FFF; float:left; color:#F00; text-align:center; font-size:12px; margin-left:0px'>
			<i>Nenhum registro encontrado.</i></div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>
		</div>";
}


else
{

		echo "
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
	
			<!-- =========  MOVIMENTACAO E ESTADO REGISTRO ============================================================= -->
			<div style='width:327px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Tipo de registro:</div>
			<div style='width:327px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Situa&ccedil;&atilde;o do Registro:</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:327px; height:20px; border:1px solid #999; float:left; color:#009900; text-align:left; font-size:14px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'><b>$movimentacao</b></div></div>";
			
		if ($estado_registro == "EXCLUIDO")
		{echo "
			<div style='width:327px; height:20px; border:1px solid #999; float:left; color:#F00; text-align:left; font-size:14px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'><b>$estado_registro</b></div></div>";}
		else
		{echo "
			<div style='width:327px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:14px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'><b>$estado_registro</b></div></div>";}
			
		echo"
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>


			
			<!-- =========  NUMERO DA COMPRA E FORNECEDOR ============================================================================= -->
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			N&uacute;mero do Registro:</div>
			<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Fornecedor:</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$numero_compra</div></div>
			<div style='width:504px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$fornecedor_print</div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			
			<!-- =========  PRODUTO, QUANTIDADE, USUARIO, DATA ============================================================================= -->
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Produto:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Quantidade:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Usu&aacute;rio Cadastro:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Data Cadastro:</div>
			<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$produto_print</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$quantidade $unidade_print</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$usuario_cadastro</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$data_cadastro</div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			<!-- =========  MOTIVO EXCLUSAO ============================================================================= -->
			<div style='width:680px; height:4px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			</div>
			<div style='width:120px; height:4px; border:1px solid #FFF; float:left; margin-left:25px'></div>";

		if ($estado_registro == "EXCLUIDO")
		{echo "
			<div style='width:681px; height:30px; border:1px solid #999; float:left; color:#666; text-align:left; font-size:9px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-right:10px; margin-top:3px'>
			Exclu&iacute;do por: $usuario_exclusao $data_exclusao $hora_exclusao 
			Motivo: $motivo_exclusao | Observa&ccedil;&atilde;o: $observacao
			</div></div>";}
		else
		{echo "
			<div style='width:681px; height:30px; border:1px solid #999; float:left; color:#666; text-align:left; font-size:9px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-right:10px; margin-top:3px'>
			Observa&ccedil;&atilde;o: $observacao
			</div></div>";}
			
		echo "	
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

		</div>
		
		<div id='centro' style='float:left; height:45px; width:168px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:45px; width:278px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:45px; width:185px; color:#F00; text-align:center; border:0px solid #000; font-size:10px'>";
		
		if ($linha_acha_pagamento == 0 and $permissao[39] == 'S' and $estado_registro != 'EXCLUIDO' and ($pode_excluir == 'S' or $permissao[74] == 'S'))
		{		
		echo "
		<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='quantidade' value='$quantidade'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='produto_print' value='$produto_print'>
		<input type='hidden' name='unidade_print' value='$unidade_print'>
		<input type='hidden' name='tipo' value='$tipo'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Excluir</button>
		</form>";
		}
		
		elseif ($linha_acha_pagamento >= 1)
		{echo "Aten&ccedil;&atilde;o: Existe pagamento vinculado a esta compra, por isso n&atilde;o pode ser exclu&iacute;da.";}

		elseif ($pode_excluir == "N")
		{echo "Aten&ccedil;&atilde;o: O prazo para exclus&atilde;o deste registro expirou.";}

		else
		{}

		
		echo "
		</div>

		<div id='centro' style='float:left; height:45px; width:185px; color:#00F; text-align:center; border:0px solid #000'>";

		echo "
		</div>

		<div id='centro' style='float:left; height:45px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";
}


?>

<div style='float:left; height:30px; width:1070px; color:#00F; text-align:center; border:0px solid #000; font-size:12px' align="center">
</div>

<div style='float:left; height:30px; width:200px; color:#00F; text-align:center; border:0px solid #000; font-size:12px; margin-left:440px'>
</div>


</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>