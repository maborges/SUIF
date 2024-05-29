<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "copia_cheque";
$titulo = "Impress&atilde;o de C&oacute;pia de Cheque";
$menu = "contas_pagar";
$modulo = "financeiro";

include ("../../includes/head_impressao.php");
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

<div style='border:1px solid #FFF'>
<div id="centro" style="width:745px; border:0px solid #000; float:left; page-break-after:always">

<?php


// ==================================================================================================================
    function valorPorExtenso($valor=0) {
    $singular = array("CENTAVO", "REAL", "MIL", "MILH&Atilde;O", "BILH&Atilde;O", "TRILH&Atilde;O", "QUATRILH&Atilde;O");
    $plural = array("CENTAVOS", "REAIS", "MIL", "MILH&Otilde;ES", "BILH&Otilde;ES", "TRILH&Otilde;ES","QUATRILH&Otilde;ES");
     
    $c = array("", "CEM", "DUZENTOS", "TREZENTOS", "QUATROCENTOS","QUINHENTOS", "SEISCENTOS", "SETECENTOS", "OITOCENTOS", "NOVECENTOS");
    $d = array("", "DEZ", "VINTE", "TRINTA", "QUARENTA", "CINQUENTA","SESSENTA", "SETENTA", "OITENTA", "NOVENTA");
    $d10 = array("DEZ", "ONZE", "DOZE", "TREZE", "QUATORZE", "QUINZE","DEZESSEIS", "DEZESETE", "DEZOITO", "DEZENOVE");
    $u = array("", "UM", "DOIS", "TR&Ecirc;S", "QUATRO", "CINCO", "SEIS","SETE", "OITO", "NOVE");
     
    $z=0;
     
    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for($i=0;$i<count($inteiro);$i++)
    for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
    $inteiro[$i] = "0".$inteiro[$i];
     
    // $fim identifica onde que deve se dar jun��o de centenas por "e" ou por "," ;)
    $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
    for ($i=0;$i<count($inteiro);$i++) {
    $valor = $inteiro[$i];
    $rc = (($valor > 100) && ($valor < 200)) ? "CENTO" : $c[$valor[0]];
    $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
    $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
    $r = $rc.(($rc && ($rd || $ru)) ? " E " : "").$rd.(($rd && $ru) ? " E " : "").$ru;
    $t = count($inteiro)-1-$i;
    $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
    if ($valor == "000")$z++; elseif ($z > 0) $z--;
    if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " DE " : "").$plural[$t];
    if ($r) $rt = ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " E ") : " ") . $r;
    }
     
    return($rt ? $rt : "zero");
    }
	
// ==================================================================================================================================================	


// ==================================================================================================================	


// =================================================================================================================



// =============================================================================================================
// =============================================================================================================
?>


<!-- ======================================================================================================================================= -->


<?php
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$num_cheque = $_POST["num_cheque"];
$filial_print = $filial_usuario;


$busca_favorecidos_ch = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
$linha_favorecidos_ch = mysqli_num_rows ($busca_favorecidos_ch);

// SOMAS CHEQUES  ==========================================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");





for ($c=1 ; $c<=$linha_favorecidos_ch ; $c++)
{
	$aux_cheque = mysqli_fetch_row($busca_favorecidos_ch);

// DADOS DO FAVORECIDO CHEQUE =========================
	$data_cheque = date('Y-m-d', strtotime($aux_cheque[4]));
	$data_cheque_print = date('d/m/Y', strtotime($aux_cheque[4]));
	$obs_pgto = $aux_cheque[7];
	$banco_cheque = $aux_cheque[6];
	$valor_cheque = $aux_cheque[5];
	$valor_cheque_print = number_format($aux_cheque[5],2,",",".");
	$codigo_compra = $aux_cheque[1];
	$cod_compra = $cod_compra . " " . $codigo_compra;


	$busca_favorecido_ch = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_cheque[2]' ORDER BY nome");
	$aux_fch = mysqli_fetch_row($busca_favorecido_ch);
	$codigo_pessoa_ch = $aux_fch[1];
	
	$busca_pessoa_ch = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_ch' ORDER BY nome");
	$aux_pessoa_ch = mysqli_fetch_row($busca_pessoa_ch);
	$nome_favorecido_ch = $aux_pessoa_ch[1];
	$tipo_pessoa_ch = $aux_pessoa_ch[2];
		if ($tipo_pessoa_ch == "PF" or $tipo_pessoa_ch == "pf")
		{$cpf_cnpj_ch = $aux_pessoa_ch[3];}
		else
		{$cpf_cnpj_ch = $aux_pessoa_ch[4];}
		
// DADOS BANCARIOS =========================
	if ($banco_cheque == "BANCO DO BRASIL")
	{$banco_ch = "Banco do Brasil (001)"; $ag_ch = "3431-2"; $conta_ch = "34.419-2"; $img_ch = "b_brasil";}
	elseif ($banco_cheque == "BANESTES")
	{
		if ($filial_print == "LINHARES")
		{$banco_ch = "Banestes (021)"; $ag_ch = "0124"; $conta_ch = "11.910.387"; $img_ch = "b_banestes";}
		else
		{$banco_ch = "Banestes (021)"; $ag_ch = "0176"; $conta_ch = "19.830.348"; $img_ch = "b_banestes";}
	}
	elseif ($banco_cheque == "SICOOB")
	{
		if ($filial_print == "LINHARES")
		{$banco_ch = "Sicoob (756)"; $ag_ch = "3007"; $conta_ch = "106.997-7"; $img_ch = "b_sicoob";}
		else
		{$banco_ch = "Sicoob (756)"; $ag_ch = "3007"; $conta_ch = "39.872-1"; $img_ch = "b_sicoob";}
	}
	else
	{$banco_ch = ""; $ag_ch = ""; $conta_ch = "";}

// RELATORIO COPIA DE CHEQUE =========================


}



	echo "
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!--                                      COPIA DE CHEQUE                                            -->

<!-- =============================================================================================== -->
<div id='centro' style='width:745px; height:540px; border:1px solid #FFF; float:left'> <!-- INICIO DIV 587 -->
<!-- =============================================================================================== -->

<div id='centro' style='width:720px; height:30px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:720px; border:1px solid #000; margin-left:25px; border-radius:20px; float:left'>

<div id='centro' style='width:700px; height:50px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:700px; height:30px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'>
	<div id='centro' style='width:340px; height:30px; border:0px solid #000; font-size:18px; float:left' align='left'>
	<b>C&oacute;pia de Cheque</b>
	</div>

	<div id='centro' style='width:300px; height:30px; border:0px solid #000; font-size:18px; float:right; margin-right:20px' align='right'>
	 N&ordm; $aux_cheque[18]
	</div>
</div>

<div id='centro' style='width:700px; height:50px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'></div>


<div id='centro' style='width:700px; border:1px solid #000; margin-left:10px; float:left'> <!-- INICIO DIV 600 -->

<div id='centro' style='width:690px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:30px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:180px; height:30px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Banco: $banco_ch
	</div>

	<div id='centro' style='width:100px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	 Ag&ecirc;ncia: $ag_ch
	</div>

	<div id='centro' style='width:120px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	Conta: $conta_ch
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	N&ordm; do cheque: $aux_cheque[18]
	</div>
	
	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:right; margin-right:10px' align='right'>
	<b>Valor: R$ $soma_pagamentos_print</b>
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:42px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:40px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Valor:
	</div>

	<div id='centro' style='width:15px; height:40px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:600px; height:40px; border:0px solid #000; font-size:14px; float:left' align='left'>";
	echo valorPorExtenso($soma_pagamentos[0]);
	
	echo "
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:18px; border:0px solid #000; font-size:11px; float:left; margin-top:3px' align='left'>
	Favorecido:
	</div>

	<div id='centro' style='width:15px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:590px; height:18px; border:0px solid #000; font-size:14px; float:left' align='left'>
	$nome_favorecido_ch
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:18px; border:0px solid #000; font-size:11px; float:left; margin-top:3px' align='left'>
	CPF/CNPJ:
	</div>

	<div id='centro' style='width:15px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:590px; height:18px; border:0px solid #000; font-size:14px; float:left' align='left'>
	$cpf_cnpj_ch
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:110px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:190px; height:108px; border:0px solid #000; font-size:9px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/$img_ch.png' height='50' />
	</div>

	<div id='centro' style='width:115px; height:108px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:380px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>";

	$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	$data_aux = explode("-", $data_cheque);	
	$dia = $data_aux[2];
	$mes = $data_aux[1];
	$ano = $data_aux[0];

	echo "Linhares, " . $dia . " de " . $meses [$mes-1] . " de " . $ano;
	
	echo "
	</div>

	<div id='centro' style='width:380px; height:35px; border:0px solid #000; font-size:11px; float:left' align='center'>

	</div>

	<div id='centro' style='width:380px; height:20px; border:0px solid #000; font-size:11px; float:left' align='center'>
	___________________________________________
	</div>

	<div id='centro' style='width:380px; height:15px; border:0px solid #000; font-size:11px; float:left' align='center'>
	GRANCAF&Eacute; COM. IMP. E EXP. DE CAF&Eacute; LTDA
	</div>
	<div id='centro' style='width:380px; height:15px; border:0px solid #000; font-size:11px; float:left' align='center'>
	CNPJ: 02.239.346/0001-72
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

</div> <!-- FIM DIV 600 -->

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:700px; height:30px; border:0px solid #000; margin-left:20px; float:left' align='center'>
	<div id='centro' style='width:240px; height:30px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Confirma&ccedil;&atilde;o de Compra: $cod_compra
	</div>

	<div id='centro' style='width:40px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>
	
	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:14px; float:right' align='right'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>
</div>
</div>
<!-- =============================================================================================== -->
</div> <!-- FIM DIV 587 -->
<!-- =============================================================================================== -->


<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
";




?>






</div>
</body>
</html>
<!-- ==================================================   FIM   ================================================= -->