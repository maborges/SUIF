<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorio_consolidado_geral';
	$titulo = 'Relat&oacute;rio Consolidado';
	$modulo = 'compras';
	$menu = 'relatorio';

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
// ============================================== CONVERTE DATA ====================================================	
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql

function ConverteData($data){

	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
//echo ConverteData($data_emissao);
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){

	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================




// =================================================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$filial = $filial_usuario;
$botao = $_POST["botao"];
$data_inicial_aux = $data_hoje_aux;
$data_inicial = $data_hoje;


// SOMAS COMPRAS GERAL  ==========================================================================================
	$soma_compra = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA'"));
	if ($soma_compra[0] <= 0)
		{$soma_print = "-";}
	else
		{$soma_print = "R$ " . number_format($soma_compra[0],2,",",".");}

// CAFE -------------------------------------------------------------------------------------------------------------
	$soma_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE'"));
	if ($soma_compra_cafe[0] <= 0)
		{$soma_cafe_print = "-";}
	else
		{$soma_cafe_print = "R$ " . number_format($soma_compra_cafe[0],2,",",".");}
	
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE'"));
	if ($soma_quant_cafe[0] <= 0)
		{$quant_cafe_print = "-";
		$media_cafe_print = "-";}
	else
		{$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".") . " Sc";
		$media_cafe = ($soma_compra_cafe[0] / $soma_quant_cafe[0]);
		$media_cafe_print = "R$ " . number_format($media_cafe,2,",",".");}

// PIMENTA -------------------------------------------------------------------------------------------------------------
	$soma_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA'"));
	if ($soma_compra_pimenta[0] <= 0)
		{$soma_pimenta_print = "-";}
	else
		{$soma_pimenta_print = "R$ " . number_format($soma_compra_pimenta[0],2,",",".");}

	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA'"));
	if ($soma_quant_pimenta[0] <= 0)
		{$quant_pimenta_print = "-";
		$media_pimenta_print = "-";}
	else
		{$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".") . " Kg";
		$media_pimenta = ($soma_compra_pimenta[0] / $soma_quant_pimenta[0]);
		$media_pimenta_print = "R$ " . number_format($media_pimenta,2,",",".");}

// CACAU -------------------------------------------------------------------------------------------------------------
	$soma_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CACAU'"));
	if ($soma_compra_cacau[0] <= 0)
		{$soma_cacau_print = "-";}
	else
		{$soma_cacau_print = "R$ " . number_format($soma_compra_cacau[0],2,",",".");}

	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CACAU'"));
	if ($soma_quant_cacau[0] <= 0)
		{$quant_cacau_print = "-";
		$media_cacau_print = "-";}
	else
		{$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".") . " Kg";
		$media_cacau = ($soma_compra_cacau[0] / $soma_quant_cacau[0]);
		$media_cacau_print = "R$ " . number_format($media_cacau,2,",",".");}
// ==================================================================================================================
	
	
	
	
// SOMAS COMPRAS LINHARES  ==========================================================================================
// CAFE -------------------------------------------------------------------------------------------------------------
	$soma_l_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='LINHARES'"));
	if ($soma_l_compra_cafe[0] <= 0)
		{$soma_l_cafe_print = "-";}
	else
		{$soma_l_cafe_print = "R$ " . number_format($soma_l_compra_cafe[0],2,",",".");}
	
	$soma_l_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='LINHARES'"));
	if ($soma_l_quant_cafe[0] <= 0)
		{$quant_l_cafe_print = "-";
		$media_l_cafe_print = "-";}
	else
		{$quant_l_cafe_print = number_format($soma_l_quant_cafe[0],2,",",".") . " Sc";
		$media_l_cafe = ($soma_l_compra_cafe[0] / $soma_l_quant_cafe[0]);
		$media_l_cafe_print = "R$ " . number_format($media_l_cafe,2,",",".");}

// PIMENTA -------------------------------------------------------------------------------------------------------------
	$soma_l_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='LINHARES'"));
	if ($soma_l_compra_pimenta[0] <= 0)
		{$soma_l_pimenta_print = "-";}
	else
		{$soma_l_pimenta_print = "R$ " . number_format($soma_l_compra_pimenta[0],2,",",".");}

	$soma_l_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='LINHARES'"));
	if ($soma_l_quant_pimenta[0] <= 0)
		{$quant_l_pimenta_print = "-";
		$media_l_pimenta_print = "-";}
	else
		{$quant_l_pimenta_print = number_format($soma_l_quant_pimenta[0],2,",",".") . " Kg";
		$media_l_pimenta = ($soma_l_compra_pimenta[0] / $soma_l_quant_pimenta[0]);
		$media_l_pimenta_print = "R$ " . number_format($media_l_pimenta,2,",",".");}

// CACAU -------------------------------------------------------------------------------------------------------------
	$soma_l_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CACAU' AND filial='LINHARES'"));
	if ($soma_l_compra_cacau[0] <= 0)
		{$soma_l_cacau_print = "-";}
	else
		{$soma_l_cacau_print = "R$ " . number_format($soma_l_compra_cacau[0],2,",",".");}

	$soma_l_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CACAU' AND filial='LINHARES'"));
	if ($soma_l_quant_cacau[0] <= 0)
		{$quant_l_cacau_print = "-";
		$media_l_cacau_print = "-";}
	else
		{$quant_l_cacau_print = number_format($soma_l_quant_cacau[0],2,",",".") . " Kg";
		$media_l_cacau = ($soma_l_compra_cacau[0] / $soma_l_quant_cacau[0]);
		$media_l_cacau_print = "R$ " . number_format($media_l_cacau,2,",",".");}
// ==================================================================================================================




// SOMAS COMPRAS JAGUARE  ==========================================================================================
// CAFE -------------------------------------------------------------------------------------------------------------
	$soma_j_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='JAGUARE'"));
	if ($soma_j_compra_cafe[0] <= 0)
		{$soma_j_cafe_print = "-";}
	else
		{$soma_j_cafe_print = "R$ " . number_format($soma_j_compra_cafe[0],2,",",".");}
	
	$soma_j_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='JAGUARE'"));
	if ($soma_j_quant_cafe[0] <= 0)
		{$quant_j_cafe_print = "-";
		$media_j_cafe_print = "-";}
	else
		{$quant_j_cafe_print = number_format($soma_j_quant_cafe[0],2,",",".") . " Sc";
		$media_j_cafe = ($soma_j_compra_cafe[0] / $soma_j_quant_cafe[0]);
		$media_j_cafe_print = "R$ " . number_format($media_j_cafe,2,",",".");}

// PIMENTA -------------------------------------------------------------------------------------------------------------
	$soma_j_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='JAGUARE'"));
	if ($soma_j_compra_pimenta[0] <= 0)
		{$soma_j_pimenta_print = "-";}
	else
		{$soma_j_pimenta_print = "R$ " . number_format($soma_j_compra_pimenta[0],2,",",".");}

	$soma_j_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='JAGUARE'"));
	if ($soma_j_quant_pimenta[0] <= 0)
		{$quant_j_pimenta_print = "-";
		$media_j_pimenta_print = "-";}
	else
		{$quant_j_pimenta_print = number_format($soma_j_quant_pimenta[0],2,",",".") . " Kg";
		$media_j_pimenta = ($soma_j_compra_pimenta[0] / $soma_j_quant_pimenta[0]);
		$media_j_pimenta_print = "R$ " . number_format($media_j_pimenta,2,",",".");}

// ==================================================================================================================




// SOMAS COMPRAS CASTANHAL  ==========================================================================================
// PIMENTA -------------------------------------------------------------------------------------------------------------
	$soma_c_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='CASTANHAL'"));
	if ($soma_c_compra_pimenta[0] <= 0)
		{$soma_c_pimenta_print = "-";}
	else
		{$soma_c_pimenta_print = "R$ " . number_format($soma_c_compra_pimenta[0],2,",",".");}

	$soma_c_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='CASTANHAL'"));
	if ($soma_c_quant_pimenta[0] <= 0)
		{$quant_c_pimenta_print = "-";
		$media_c_pimenta_print = "-";}
	else
		{$quant_c_pimenta_print = number_format($soma_c_quant_pimenta[0],2,",",".") . " Kg";
		$media_c_pimenta = ($soma_c_compra_pimenta[0] / $soma_c_quant_pimenta[0]);
		$media_c_pimenta_print = "R$ " . number_format($media_c_pimenta,2,",",".");}

// ==================================================================================================================



// SOMAS COMPRAS SAO MATEUS  ==========================================================================================
// CAFE -------------------------------------------------------------------------------------------------------------
	$soma_sm_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='SAO_MATEUS'"));
	if ($soma_sm_compra_cafe[0] <= 0)
		{$soma_sm_cafe_print = "-";}
	else
		{$soma_sm_cafe_print = "R$ " . number_format($soma_sm_compra_cafe[0],2,",",".");}
	
	$soma_sm_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='SAO_MATEUS'"));
	if ($soma_sm_quant_cafe[0] <= 0)
		{$quant_sm_cafe_print = "-";
		$media_sm_cafe_print = "-";}
	else
		{$quant_sm_cafe_print = number_format($soma_sm_quant_cafe[0],2,",",".") . " Sc";
		$media_sm_cafe = ($soma_sm_compra_cafe[0] / $soma_sm_quant_cafe[0]);
		$media_sm_cafe_print = "R$ " . number_format($media_sm_cafe,2,",",".");}

// PIMENTA -------------------------------------------------------------------------------------------------------------
	$soma_sm_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='SAO_MATEUS'"));
	if ($soma_sm_compra_pimenta[0] <= 0)
		{$soma_sm_pimenta_print = "-";}
	else
		{$soma_sm_pimenta_print = "R$ " . number_format($soma_sm_compra_pimenta[0],2,",",".");}

	$soma_sm_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra='$data_inicial' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='SAO_MATEUS'"));
	if ($soma_sm_quant_pimenta[0] <= 0)
		{$quant_sm_pimenta_print = "-";
		$media_sm_pimenta_print = "-";}
	else
		{$quant_sm_pimenta_print = number_format($soma_sm_quant_pimenta[0],2,",",".") . " Kg";
		$media_sm_pimenta = ($soma_sm_compra_pimenta[0] / $soma_sm_quant_pimenta[0]);
		$media_sm_pimenta_print = "R$ " . number_format($media_sm_pimenta,2,",",".");}

// ==================================================================================================================



?>

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_relatorio.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral_relatorio" style="width:1200px; height:auto; margin:auto; background-color:#FFF; border-radius:20px; border:1px solid #999">
<!-- <div id="centro_geral"> -->
	<div id="centro" style="height:1500px; width:1180px; border:0px solid #000; margin:auto">




<!-- =========================================================================================================== -->
	    <div id='centro' style='height:430px; width:1170px; border:1px solid #BCD2EE; margin-top:15px; margin-left:10px; float:left' align='center'>
 <!-- =========================================================================================================== -->
 <!-- =========================================================================================================== -->
          	<div id='centro' style='height:30px; width:1150px; border:0px solid #000; margin-top:10px; background-color:#003466; color:#FFF; 
            font-size:12px; float:left; margin-left:10px' align='center'>
            <div style='width:217px; float:left; margin-top:0px; text-align:left'>
            <img src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/rel_cons_compras.png' border='0' /></div>
            <div style='width:702px; float:left; margin-top:7px'><b>Relat&oacute;rio Consolidado de Compras</b></div>
            <div style='width:217px; float:left; margin-top:7px'><b><!-- XXX --></b></div>
            </div>



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Janeiro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Fevereiro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Mar&ccedil;o</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Abril</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Maio</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Junho</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Julho</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Agosto</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Setembro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Outubro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Novembro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Dezembro</i></div>
				</div>
            </div>





 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Caf&eacute; Conilon</b></div>
				</div>
			</div>

    
 



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Jaguar&eacute;</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>








 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>






 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Pimenta do Reino</b></div>
				</div>
			</div>

    
 


 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Jaguar&eacute;</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>

 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Castanhal</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>





 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Cacau</b></div>
				</div>
			</div>

    
 


 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>









<!-- =========================================================================================================== -->
<!-- =========================================================================================================== -->
        </div>
<!-- =========================================================================================================== -->







<!-- =========================================================================================================== -->
	    <div id='centro' style='height:180px; width:530px; border:1px solid #BCD2EE; margin-top:15px; margin-left:10px; float:left' align='center'>
 <!-- =========================================================================================================== -->
 <!-- =========================================================================================================== -->
          	<div id='centro' style='height:30px; width:510px; border:0px solid #000; margin-top:10px; background-color:#003466; color:#FFF; 
            font-size:12px; float:left; margin-left:10px' align='center'>
            <div style='width:100px; float:left; margin-top:0px; text-align:left'>
            <img src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/rel_cons_compras.png' border='0' /></div>
            <div style='width:308px; float:left; margin-top:7px'><b>Compras dos &uacute;ltimos 3 anos</b></div>
            <div style='width:100px; float:left; margin-top:7px'><b><!-- XXX --></b></div>
            </div>



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:458px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>2015</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>2016</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>2017</i></div>
				</div>
            </div>






 



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:458px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#009900; text-align:left'><b>Caf&eacute; Conilon</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#009900; text-align:left'><b>Pimenta do Reino</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>








 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#009900; text-align:left'><b>Cacau</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#666; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>










<!-- =========================================================================================================== -->
<!-- =========================================================================================================== -->
        </div>
<!-- =========================================================================================================== -->








<!-- =========================================================================================================== -->
	    <div id='centro' style='height:180px; width:530px; border:1px solid #BCD2EE; margin-top:15px; margin-right:0px; float:right' align='center'>
 <!-- =========================================================================================================== -->
 <!-- =========================================================================================================== -->
          	<div id='centro' style='height:30px; width:510px; border:0px solid #000; margin-top:10px; background-color:#003466; color:#FFF; 
            font-size:12px; float:left; margin-left:10px' align='center'>
            <div style='width:100px; float:left; margin-top:0px; text-align:left'>
            <img src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/rel_cons_compras.png' border='0' /></div>
            <div style='width:308px; float:left; margin-top:7px'><b>Contratos Futuros</b></div>
            <div style='width:100px; float:left; margin-top:7px'><b><!-- XXX --></b></div>
            </div>


 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#009900; text-align:left'><b>Caf&eacute; Conilon</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Linhares</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Jaguar&eacute;</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>TOTAL</i></div>
				</div>
            </div>
 



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Vencidos</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><b><?php echo "8.000.000 Sc"; ?></b></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>A Vencer</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><b><?php echo "8.000.000 Sc"; ?></b></div>
				</div>
            </div>





 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#009900; text-align:left'><b>Pimenta do Reino</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Linhares</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Jaguar&eacute;</i></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>TOTAL</i></div>
				</div>
            </div>
 



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Vencidos</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#FF0000; text-align:right'><b><?php echo "8.000.000 Sc"; ?></b></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:18px; width:458px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:105px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>A Vencer</b></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:108px; float:left; height:14px; margin-left:4px; border:0px solid #999'>
            		<div style='margin-top:0px; margin-right:5px; font-size:9px; color:#0000FF; text-align:right'><b><?php echo "8.000.000 Sc"; ?></b></div>
				</div>
            </div>










<!-- =========================================================================================================== -->
<!-- =========================================================================================================== -->
        </div>
<!-- =========================================================================================================== -->















<!-- =========================================================================================================== -->
	    <div id='centro' style='height:430px; width:1170px; border:1px solid #BCD2EE; margin-top:15px; margin-left:10px; float:left' align='center'>
 <!-- =========================================================================================================== -->
 <!-- =========================================================================================================== -->
          	<div id='centro' style='height:30px; width:1150px; border:0px solid #000; margin-top:10px; background-color:#006400; color:#FFF; 
            font-size:12px; float:left; margin-left:10px' align='center'>
            <div style='width:217px; float:left; margin-top:0px; text-align:left'>
            <img src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/rel_cons_compras.png' border='0' /></div>
            <div style='width:702px; float:left; margin-top:7px'><b>Estoque F&iacute;sico Atual</b></div>
            <div style='width:217px; float:left; margin-top:7px'><b><!-- XXX --></b></div>
            </div>



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Janeiro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Fevereiro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Mar&ccedil;o</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Abril</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Maio</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Junho</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Julho</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Agosto</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Setembro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Outubro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Novembro</i></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999; background-color:#E0EEEE'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><i>Dezembro</i></div>
				</div>
            </div>





 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Caf&eacute; Conilon</b></div>
				</div>
			</div>

    
 



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Jaguar&eacute;</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>








 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>






 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Pimenta do Reino</b></div>
				</div>
			</div>

    
 


 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Jaguar&eacute;</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>

 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Castanhal</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>



 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>





 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
            	<div id='centro' style='width:10px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:9px; color:#666; text-align:center'><i><!-- xxxx --></i></div>
				</div>
            	<div id='centro' style='width:200px; float:left; height:14px; margin-left:4px; margin-top:4px; border:0px solid #999'>
            		<div style='margin-top:0px; font-size:11px; color:#009900; text-align:left'><b>Cacau</b></div>
				</div>
			</div>

    
 


 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>Linhares</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>




 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1150px; border:0px solid #000; margin-top:2px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:65px; float:left; height:14px; margin-left:4px; border:1px solid #FFF'>
            		<div style='margin-top:0px; font-size:9px; color:#003466; text-align:left'><b>S&atilde;o Mateus</b></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            	<div id='centro' style='width:83px; float:left; height:14px; margin-left:4px; border:1px solid #999'>
            		<div style='margin-top:0px; font-size:9px; color:#666; text-align:center'><?php echo "8.000.000 Sc"; ?></div>
				</div>
            </div>









<!-- =========================================================================================================== -->
<!-- =========================================================================================================== -->
        </div>
<!-- =========================================================================================================== -->

















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