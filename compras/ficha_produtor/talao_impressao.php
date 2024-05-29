<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'talao_impressao';
$titulo = 'Tal&atilde;o Saldo de Produtor';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ========================================================================================================


// ====== RECEBE POST ==============================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$observacao = $_POST["observacao"];
$numero_compra = $_POST["numero_compra"];
$quant_via = $_POST["quant_via"];
$filial = $filial_usuario;

$usuario_impressao = $nome_usuario_print;
$hora_impressao = date('G:i:s', time());
$data_impressao = date('Y/m/d', time());
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== SALDO PRODUTOR =================================================================================
// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SA�DA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0],2,",",".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto,2,",",".");
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

//$un_descricao = $aux_un_med[1];
//$unidade_print = $aux_un_med[2];

if ($produto_apelido == "CAFE" or $produto_apelido == "CAFE_ARABICA")
{	
	if ($saldo_geral_produto <= 1)
	{$unidade_print = "Saca";
	$unidade = "SC";}
	else	
	{$unidade_print = "Sacas";
	$unidade = "SC";}
}
else
{
	$unidade_print = "Kg";
	$unidade = "KG";
}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA CONTROLE DE TALAO ========================================================================
$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE codigo_talao='$numero_compra' ORDER BY codigo");
$linha_talao = mysqli_num_rows ($busca_talao);
// ======================================================================================================


// ========================================================================================================
include ('../../includes/head_impressao.php');
?>

<!-- ==================================   T � T U L O   D A   P � G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N � C I O   =============================================== -->
<body onLoad="imprimir()">

<?php

/*
// CALCULO SALDO  ==========================================================================================
	$soma_entrada = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND produto='$produto_list' AND fornecedor='$produtor_ficha' AND filial='$filial'"));

	$soma_saida = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND produto='$produto_list' AND fornecedor='$produtor_ficha' AND filial='$filial'"));

	$saldo = ($soma_entrada[0] - $soma_saida[0]);
	$saldo_print = number_format($saldo,2,",",".");



// UNIDADE PRINT  ==========================================================================================


// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$produtor_ficha' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($y=1 ; $y<=$linha_pessoa ; $y++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
$cod_fornecedor_print = $aux_pessoa[0];
	if ($aux_pessoa[2] == "pf")
	{$cpf_cnpj = $aux_pessoa[3];}
	else
	{$cpf_cnpj = $aux_pessoa[4];}
}
// =================================================================================================================



// BUSCA TALAO  ==========================================================================================
$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE codigo_talao='$numero_compra' ORDER BY codigo");
$linha_talao = mysqli_num_rows ($busca_talao);
*/

if ($linha_talao == 0)
{
// INSERIR TABELA TALAO_CONTROLE  ==========================================================================================
$inserir = mysqli_query($conexao, "INSERT INTO talao_controle (codigo, codigo_talao, codigo_pessoa, produto, quantidade, unidade, data_impressao, hora_impressao, usuario_impressao, devolvido, observacao, estado_registro, filial, fornecedor_print, cod_produto) VALUES (NULL, '$numero_compra', '$fornecedor', '$produto_apelido', '$saldo_geral_produto', '$unidade', '$data_impressao', '$hora_impressao', '$usuario_impressao', 'N', '$observacao', 'ATIVO', '$filial', '$fornecedor_print', '$cod_produto')");
}

else
{

}


// =============================================================================================================
// =============================================================================================================
?>





<!-- =============== INICIO DA 1a VIA ==================== -->
<div id='centro' style='width:780px; height:auto; border:0px solid #000; float:left'>



<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'></div>
<div id='centro' style='width:720px; height:60px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='left'>
<img src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca.png' border='0' />
</div>

<div id='centro' style='width:720px; height:50px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:10px; float:left' align='left'></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:13px; float:left' align='center'>
    <b>TAL&Atilde;O SALDO DE PRODUTOR <?php // echo"$linha_talao"; ?></b></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:9px; float:left' align='right'></div>
	
	

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:12px; float:left' align='left'>N&ordm; <?php echo"$numero_compra"; ?></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:13px; float:left' align='center'>
    <?php echo"<b>$produto_print</b>"; ?></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:12px; float:left' align='right'>
    <?php
	$hoje = date('d/m/Y', time());
	echo "$hoje";
	/*
    $data_atual = date('d/m/Y', time());
    $hora_atual = date('G:i:s', time());
    echo'$data_atual $hora_atual'*/
	?>
    </div>



</div>


<!-- ======================================================================================================================================= -->

<div id='centro' style='width:735px; height:auto; border:0px solid #000; margin-top:2px; margin-left:20px; float:left'>

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id='tabela_2' style='width:730px; height:5px; border:0px solid #000; font-size:9px'>
	<div style='margin-top:3px; margin-left:5px'><!-- Dados do Produtor: --></div></div>
		<div id='tabela_2' style='width:730px; height:50px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:11px'></div>
			
			<div style='width:540px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Nome:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<?php echo"<b>$fornecedor_print</b> ($fornecedor)" ?>
			</div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>CPF/CNPJ:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<?php echo"<b>$cpf_cnpj</b>" ?>
			</div></div>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:11px'></div>

			<div style='width:540px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Cidade:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<?php echo"<b>$cidade_fornecedor - $estado_fornecedor</b>" ?>
			</div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Telefone:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<?php echo"<b>$telefone_fornecedor</b>" ?>
			</div></div>

		</div>

<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
	<div id='tabela_2' style='width:730px; height:15px; border:0px solid #000; font-size:12px; margin-top:10px'>
	<div style='margin-top:3px; margin-left:5px'><!--Dados da Compra:--></div></div>
		<div id='tabela_2' style='width:730px; height:50px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:705px; height:15px; border:0px solid #000; float:left; font-size:12px'></div>
			
			<div style='width:530px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:40px; float:left'>Saldo atual de <?php echo"$produto_print" ?> armazenado. <?php //echo"com $nome_fantasia"; ?></div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Quantidade: <?php echo"<b>$saldo_geral_produto_print</b> $unidade_print" ?></div></div>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:12px'></div>

		</div>

	<div id='tabela_2' style='width:730px; height:5px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden; float:left'>
	</div>


		<!-- ===================================================================================== -->
<?php
$busca_tipo = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' ORDER BY codigo");
$linha_tipo = mysqli_num_rows ($busca_tipo);

for ($t=1 ; $t<=$linha_tipo ; $t++)
{
$aux_tipo = mysqli_fetch_row($busca_tipo);


$soma_tipo_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));
	
$soma_tipo_saida = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));

$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
$saldo_tipo_print = number_format($saldo_tipo,2,",",".");
		
// ===================================================================================================================================
if ($saldo_tipo == 0)
{echo "";}
else
{echo "
<div id='tabela_2' style='width:730px; height:20px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden; float:left'>
	<div style='width:100px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	</div>

	<div style='width:450px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	Tipo: $aux_tipo[1]
	</div>
	
	<div style='width:150px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	Saldo: $saldo_tipo_print $un_print
	</div>
</div>	
";}
	
		
}

?>
<!-- ===================================================================================== -->
		
		




		<div id='tabela_2' style='width:730px; height:25px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:100px; height:18px; border:0px solid #000; float:left; font-size:12px; text-align:right; margin-top:5px'>
			Observa&ccedil;&atilde;o:
			</div>
			
			<div style='width:600px; height:18px; border:0px solid #000; float:left; font-size:12px; margin-left:5px; margin-top:5px'>
			<?php echo"$observacao" ?>
			</div>

		</div>
	

<!-- ======================================================================================================================================================= -->



<div id='centro' style='width:710px; height:60px; border:0px solid #000; font-size:17px; float:left' align='center'></div>

	<div id='centro' style='width:700px; height:10px; border:0px solid #000; font-size:11px; float:left' align='center'>______________________________</div>

<div id='centro' style='width:710px; height:10px; border:0px solid #000; font-size:17px; float:left' align='center'></div>

	<div id='centro' style='width:700px; height:10px; border:0px solid #000; font-size:11px; float:left' align='center'><?php echo"$nome_fantasia"; ?></div>






</div>


		
<div id='centro' style='width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>
<div id='centro' style='width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'>
<hr style='border:1px solid #999' />
</div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'>
<div id='centro' style='width:350px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>
<?php echo "$rodape_print | $nome_fantasia_m | $telefone_filial_1 | $telefone_filial_2"; ?>
</div>
<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'>
<?php echo"FILIAL: $filial" ?>
</div>
<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
1&ordf; Via
</div>
</div>
<!-- =============================================================================================== -->

<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id='centro' style='width:720px; height:30px; border:0px solid #000; margin-left:5px; font-size:17px; float:left' align='center'>

</div>

<div id='centro' style='width:720px; height:30px; border:0px solid #000; margin-left:5px; font-size:17px; float:left' align='center'>
<hr style='border:1px dashed #999' />
</div>

</div>
<!-- =============== FIM DA 1a VIA ==================== -->




<?php
if ($quant_via == 2)
{

echo "
<!-- =============== INICIO DA 2a VIA ==================== -->
<div id='centro' style='width:780px; height:auto; border:0px solid #000; float:left'>



<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'></div>
<div id='centro' style='width:720px; height:60px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='left'>
<img src='$servidor/$diretorio_servidor/imagens/logomarca.png' border='0' />
</div>

<div id='centro' style='width:720px; height:50px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:10px; float:left' align='left'></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:13px; float:left' align='center'>
    <b>TAL&Atilde;O SALDO DE PRODUTOR</b></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:9px; float:left' align='right'></div>
	
	

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:12px; float:left' align='left'>N&ordm; $numero_compra</div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:13px; float:left' align='center'>
    <b>$produto_print</b></div>

    <div id='centro' style='width:238px; height:24px; border:0px solid #000; font-size:12px; float:left' align='right'>";

	echo "$hoje";
	echo "
    </div>



</div>


<!-- ======================================================================================================================================= -->

<div id='centro' style='width:735px; height:auto; border:0px solid #000; margin-top:2px; margin-left:20px; float:left'>

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id='tabela_2' style='width:730px; height:5px; border:0px solid #000; font-size:9px'>
	<div style='margin-top:3px; margin-left:5px'><!-- Dados do Produtor: --></div></div>
		<div id='tabela_2' style='width:730px; height:50px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:11px'></div>
			
			<div style='width:540px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Nome:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<b>$fornecedor_print</b> ($fornecedor)
			</div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>CPF/CNPJ:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<b>$cpf_cnpj</b>
			</div></div>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:11px'></div>

			<div style='width:540px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Cidade:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<b>$cidade_fornecedor - $estado_fornecedor</b>
			</div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Telefone:</div><div style='margin-top:3px; margin-left:5px; float:left'>
			<b>$telefone_fornecedor</b>
			</div></div>

		</div>

<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
	<div id='tabela_2' style='width:730px; height:15px; border:0px solid #000; font-size:12px; margin-top:10px'>
	<div style='margin-top:3px; margin-left:5px'><!--Dados da Compra:--></div></div>
		<div id='tabela_2' style='width:730px; height:50px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:705px; height:15px; border:0px solid #000; float:left; font-size:12px'></div>
			
			<div style='width:530px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:40px; float:left'>Saldo atual de $produto_print armazenado.</div></div>
			
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:12px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Quantidade: <b>$saldo_geral_produto_print</b> $unidade_print</div></div>

			<div style='width:705px; height:5px; border:0px solid #000; float:left; font-size:12px'></div>

		</div>

	<div id='tabela_2' style='width:730px; height:5px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden; float:left'>
	</div>


		<!-- ===================================================================================== -->";

$busca_tipo = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' ORDER BY codigo");
$linha_tipo = mysqli_num_rows ($busca_tipo);

for ($t=1 ; $t<=$linha_tipo ; $t++)
{
$aux_tipo = mysqli_fetch_row($busca_tipo);


$soma_tipo_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));
	
$soma_tipo_saida = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));

$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
$saldo_tipo_print = number_format($saldo_tipo,2,",",".");
		
// ===================================================================================================================================
if ($saldo_tipo == 0)
{echo "";}
else
{echo "
<div id='tabela_2' style='width:730px; height:20px; border:0px solid #000; color:#000; border-radius:5px; overflow:hidden; float:left'>
	<div style='width:100px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	</div>

	<div style='width:450px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	Tipo: $aux_tipo[1]
	</div>
	
	<div style='width:150px; height:18px; border:0px solid #000; float:left; font-size:10px'>
	Saldo: $saldo_tipo_print $un_print
	</div>
</div>	
";}
	
		
}








		
		
echo "
	<!-- ===================================================================================== -->


		<div id='tabela_2' style='width:730px; height:25px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden'>

			<div style='width:100px; height:18px; border:0px solid #000; float:left; font-size:12px; text-align:right; margin-top:5px'>
			Observa&ccedil;&atilde;o:
			</div>
			
			<div style='width:600px; height:18px; border:0px solid #000; float:left; font-size:12px; margin-left:5px; margin-top:5px'>
			$observacao
			</div>

		</div>
	

<!-- ======================================================================================================================================================= -->



<div id='centro' style='width:710px; height:60px; border:0px solid #000; font-size:17px; float:left' align='center'></div>

	<div id='centro' style='width:700px; height:10px; border:0px solid #000; font-size:11px; float:left' align='center'>______________________________</div>

<div id='centro' style='width:710px; height:10px; border:0px solid #000; font-size:17px; float:left' align='center'></div>

	<div id='centro' style='width:700px; height:10px; border:0px solid #000; font-size:11px; float:left' align='center'>$nome_fantasia</div>






</div>


		
<div id='centro' style='width:720px; height:50px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>
<div id='centro' style='width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'>
<hr style='border:1px solid #999' />
</div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'>
<div id='centro' style='width:350px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>
$rodape_print | $nome_fantasia_m | $telefone_filial_1 | $telefone_filial_2
</div>
<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'>
FILIAL: $filial
</div>
<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
2&ordf; Via
</div>
</div>
<!-- =============================================================================================== -->

<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id='centro' style='width:720px; height:25px; border:0px solid #000; margin-left:5px; font-size:17px; float:left' align='center'>
<!-- <hr style='border:1px dashed #999' /> -->
</div>


</div>
<!-- =============== FIM DA 2a VIA ==================== -->

";
}

else

{}


?>



</body>
</html>
<!-- ==================================================   FIM   ================================================= -->