<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'relatorio_pgtos_impressao';
$titulo = 'Relat&oacute;rio de Pagamentos';
$modulo = 'financeiro';
$menu = 'contas_pagar';

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

<div id="centro" style="width:745px; border:0px solid #F00">

<?php

// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$codigo_pagamento = $_POST["codigo_pagamento"];
$botao = $_POST["botao"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$forma_pagamento = $_POST["forma_pagamento"];		
$status_pagamento = $_POST["status_pagamento"];


// ==================================================================================================================================================
if ($status_pagamento == "EM_ABERTO")
{

	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='EM_ABERTO' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='EM_ABERTO' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}

// ==================================================================================================================================================
elseif ($status_pagamento == "PAGO")
{
	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='PAGO' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='PAGO' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}

// ==================================================================================================================================================
else
{
	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND filial='$filial' ORDER BY banco_ted");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}
// ==================================================================================================================================================



echo "<div id='centro' style='width:740px; height:1065px; border:0px solid #000; page-break-after:always'>";
	




echo "
<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RELAT&Oacute;RIO DE PAGAMENTOS<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$filial_print</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:680px; border:0px solid #000; margin-top:0px; margin-left:15px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	if ($status_pagamento == "")
	{echo "<i>Per&iacute;odo: <b>GERAL</b></i>";}
	else
	{echo "<i>Per&iacute;odo: <b>$data_inicial_aux</b> at&eacute; <b>$data_final_aux</b></i>";}
	
	echo "
	
	</div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_pagamento == 1)
	{echo"<i><b>$linha_pagamento</b> Pagamento</i>";}
	elseif ($linha_pagamento == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_pagamento</b> Pagamentos</i>";}
	echo "</div>";


echo "
<div id='centro' style='height:auto; width:770px; border:0px solid #999; margin:auto'>

	<div style='width:56px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Data Pgto:</i></div></div>
	<div style='width:190px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Favorecido:</i></div></div>
	<div style='width:97px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>CPF/CNPJ:</i></div></div>
	<div style='width:42px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Compra:</i></div></div>
	<div style='width:60px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Forma Pgto:</i></div></div>
	<div style='width:190px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Dados Banc&aacute;rios:</i></div></div>
	<div style='width:65px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Valor:</i></div></div>
	<div style='width:17px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>PG</i></div></div>

</div>

<div id='centro' style='height:10px; width:770px; border:0px solid #999; margin:auto'></div>";



echo "
<div id='centro' style='height:auto; width:770px; border:0px solid #999; margin:auto'>";


for ($x=1 ; $x<=$linha_pagamento ; $x++)
{
	$aux_favorecido = mysqli_fetch_row($busca_pagamento);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);
	$num_compra = ($aux_favorecido[1]);
	$banco_cheque = ($aux_favorecido[6]);
	$num_cheque = ($aux_favorecido[18]);
	$filial_print = $aux_favorecido[16];

	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
	$aux_f2 = mysqli_fetch_row($busca_favorecido_2);
	
	$codigo_pessoa_2 = $aux_f2[1];
	$banco_2 = $aux_f2[2];
	$agencia_2 = $aux_f2[3];
	$conta_2 = $aux_f2[4];
	$tipo_conta_2 = $aux_f2[5];
	$conta_conjunta = $aux_f2[15];
	
	$busca_banco_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
	$aux_b2 = mysqli_fetch_row($busca_banco_2);
	$banco_print_2 = $aux_b2[3];
	
	if ($tipo_conta_2 == "corrente")
	{$tipo_conta_print_2 = "C/C";}
	elseif ($tipo_conta_2 == "poupanca")
	{$tipo_conta_print_2 = "C/P";}
	else
	{$tipo_conta_print_2 = "C.";}
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_p2[1];
	$tipo_pessoa_2 = $aux_p2[2];
		if ($tipo_pessoa_2 == "pf" or $tipo_pessoa_2 == "PF")
		{$cpf_cnpj_2 = $aux_p2[3];}
		else
		{$cpf_cnpj_2 = $aux_p2[4];}
		
	$valor_pagamento_print_2 = number_format($aux_favorecido[5],2,",",".");

// FORMA DE PAGAMENTO =========================
	if ($aux_favorecido[3] == "DINHEIRO")
	{$forma_pagamento_2 = "Dinheiro";}
	elseif ($aux_favorecido[3] == "CHEQUE")
	{$forma_pagamento_2 = "Cheque";}
	elseif ($aux_favorecido[3] == "TED")
	{$forma_pagamento_2 = "Transfer.";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$forma_pagamento_2 = "Outra";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$forma_pagamento_2 = "(PREVIS&Atilde;O)";}
	else
	{$forma_pagamento_2 = "-";}
	
// DADOS BANCARIOS =========================
	if ($aux_favorecido[3] == "CHEQUE")
	{$dados_bancarios_2 = " $aux_favorecido[6] ( N&ordm; cheque: $aux_favorecido[18] )";}
	elseif ($aux_favorecido[3] == "TED")
	{$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";}
	elseif ($aux_favorecido[3] == "DINHEIRO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$dados_bancarios_2 = "$obs_pgto";}
	else
	{$dados_bancarios_2 = "-";}

// RELATORIO =========================
	echo "
	<div id='centro' style='height:5px; width:770px; border:0px solid #999; margin:auto; float:left'></div>
	
	<div style='width:56px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-left:4px; float:left'>$data_pagamento_print_2</div></div>
	<div style='width:190px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px; overflow:hidden'>";

	if ($conta_conjunta == "SIM")
	{echo "<div style='margin-top:2px; margin-left:4px; float:left'>$nome_favorecido_2 (*)</div></div>";}
	else
	{echo "<div style='margin-top:2px; margin-left:4px; float:left'>$nome_favorecido_2</div></div>";}
	
	echo "
	<div style='width:97px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-left:4px; float:left'>$cpf_cnpj_2</div></div>
	<div style='width:42px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-left:4px; float:left'>$num_compra</div></div>
	<div style='width:60px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-left:4px; float:left'>$forma_pagamento_2</div></div>
	<div style='width:190px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-left:4px; float:left'>$dados_bancarios_2</div></div>
	<div style='width:65px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-right:4px; float:right'>$valor_pagamento_print_2</div></div>";
	
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "
	<div style='width:17px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-right:4px; float:right'></div></div>";}
	else
	{echo "
	<div style='width:17px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-right:4px; float:right'><b>&#10004;</b></div></div>";}
}


echo "
	<div id='centro' style='height:5px; width:770px; border:0px solid #999; margin:auto; float:left'></div>";

echo "</div>";





if ($linha_pagamento == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='785px' height='15px' align='left'>&#160;&#160;<i>Nenhum pagamento encontrado.</i></td></tr>";}






echo "


</div>

<div id='centro' style='width:760px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:100px; border:0px solid #000; margin-left:10px; float:left; border-radius:7px;' align='center'>

	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:170px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->	
		</div>
		<div id='centro' style='height:15px; width:200px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:right; font-size:11px; color:#000000'>
		<b>TOTAL DE PAGAMENTOS:</b>	
		</div>
		<div id='centro' style='height:15px; width:130px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:right; font-size:11px; color:#000000'>
		<b>R$ $soma_pagamentos_print</b>
		</div>
	</div>";


echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		<!-- xxxxxxxxxxxxxxxxxxxx -->
		</div>
	</div>";
	
// ===================================================================
// ============== TOTAL BANCOS =======================================
echo "<div id='centro' style='width:710px; height:54px; border:0px solid #000; margin-left:10px; float:left; font-size:10px;' align='right'>";

	$busca_bco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco ORDER BY numero");
	$linha_bco = mysqli_num_rows ($busca_bco);

for ($b=1 ; $b<=$linha_bco ; $b++)
{
	$aux_bco = mysqli_fetch_row($busca_bco);
	$bco_ted = $aux_bco[2];
	$nome_bco = $aux_bco[3];

	if ($status_pagamento == "EM_ABERTO")
	{	
	$soma_bco = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' 
	AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='TED' AND filial='$filial' 
	AND banco_ted='$bco_ted' AND situacao_pagamento='EM_ABERTO'"));
	$soma_bco_print = number_format($soma_bco[0],2,",",".");
	}
	
	elseif ($status_pagamento == "PAGO")
	{
	$soma_bco = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' 
	AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='TED' AND filial='$filial' 
	AND banco_ted='$bco_ted' AND situacao_pagamento='PAGO'"));
	$soma_bco_print = number_format($soma_bco[0],2,",",".");
	}
	
	else
	{
	$soma_bco = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' 
	AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='TED' AND filial='$filial' AND banco_ted='$bco_ted'"));
	$soma_bco_print = number_format($soma_bco[0],2,",",".");
	}

	if ($soma_bco[0] == 0 or $forma_pagamento!="TED")
	{}

	else
	{echo "
	<div id='centro' style='height:17px; width:160px; margin-left:12px; margin-top:3px; border:1px solid #000; float:left; text-align:left; font-size:9px; color:#000000'>
	<div style='margin-top:2px; margin-left:5px'>$nome_bco &#160;&#160;&#160; R$ $soma_bco_print</div>
	</div>";}

}

echo "</div>";

// ===================================================================
// ===================================================================



echo "
</div>


<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #f85; float:left; margin-left:10px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date('Y');
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia";

	echo"
	</div>
	<div id='centro' style='width:430px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'></div>

	<div id='centro' style='width:100px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
	FILIAL: $filial
	</div>
</div>
<!-- =============================================================================================== -->

<!-- ####################################################################### -->";

echo "</div>"; // quebra de p�gina



?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->