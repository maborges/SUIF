<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'index_movimentacao';
$titulo = 'Estoque - Movimenta&ccedil;&atilde;o';
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
<div id="centro" style="height:410px; width:1080px; border:0px solid #000; margin:auto">
<?php

// ===== LINHARES ===========================================================
$soma_entrada_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='LINHARES'"));
$soma_entrada_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='LINHARES'"));
$soma_entrada_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='LINHARES'"));
$soma_entrada_cravo_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='LINHARES'"));
$soma_entrada_cafe_arabica_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='LINHARES'"));
$soma_entrada_residuo_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='LINHARES'"));


$soma_saida_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='LINHARES'"));
$soma_saida_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='LINHARES'"));
$soma_saida_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='LINHARES'"));
$soma_saida_cravo_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='LINHARES'"));
$soma_saida_cafe_arabica_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='LINHARES'"));
$soma_saida_residuo_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='LINHARES'"));



$quant_cafe_linhares = ($soma_entrada_cafe_linhares[0] - $soma_saida_cafe_linhares[0]);
$quant_cafe_linhares_convert = ($quant_cafe_linhares / 60);
$quant_cafe_linhares_print = number_format($quant_cafe_linhares_convert,2,",",".");
$quant_pimenta_linhares = ($soma_entrada_pimenta_linhares[0] - $soma_saida_pimenta_linhares[0]);
$quant_pimenta_linhares_print = number_format($quant_pimenta_linhares,2,",",".");
$quant_cacau_linhares = ($soma_entrada_cacau_linhares[0] - $soma_saida_cacau_linhares[0]);
$quant_cacau_linhares_print = number_format($quant_cacau_linhares,2,",",".");
$quant_cravo_linhares = ($soma_entrada_cravo_linhares[0] - $soma_saida_cravo_linhares[0]);
$quant_cravo_linhares_print = number_format($quant_cravo_linhares,2,",",".");
$quant_cafe_arabica_linhares = ($soma_entrada_cafe_arabica_linhares[0] - $soma_saida_cafe_arabica_linhares[0]);
$quant_cafe_arabica_linhares_convert = ($quant_cafe_arabica_linhares / 60);
$quant_cafe_arabica_linhares_print = number_format($quant_cafe_arabica_linhares_convert,2,",",".");


// ===== JAGUARE ===========================================================
$soma_entrada_cafe_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='JAGUARE'"));
$soma_entrada_pimenta_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='JAGUARE'"));
$soma_entrada_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='JAGUARE'"));
$soma_entrada_cravo_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='JAGUARE'"));
$soma_entrada_cafe_arabica_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='JAGUARE'"));
$soma_entrada_residuo_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='JAGUARE'"));


$soma_saida_cafe_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='JAGUARE'"));
$soma_saida_pimenta_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='JAGUARE'"));
$soma_saida_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='JAGUARE'"));
$soma_saida_cravo_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='JAGUARE'"));
$soma_saida_cafe_arabica_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='JAGUARE'"));
$soma_saida_residuo_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='JAGUARE'"));


$quant_cafe_jaguare = ($soma_entrada_cafe_jaguare[0] - $soma_saida_cafe_jaguare[0]);
$quant_cafe_jaguare_convert = ($quant_cafe_jaguare / 60);
$quant_cafe_jaguare_print = number_format($quant_cafe_jaguare_convert,2,",",".");
$quant_pimenta_jaguare = ($soma_entrada_pimenta_jaguare[0] - $soma_saida_pimenta_jaguare[0]);
$quant_pimenta_jaguare_print = number_format($quant_pimenta_jaguare,2,",",".");
$quant_cacau_jaguare = ($soma_entrada_cacau_jaguare[0] - $soma_saida_cacau_jaguare[0]);
$quant_cacau_jaguare_print = number_format($quant_cacau_jaguare,2,",",".");
$quant_cravo_jaguare = ($soma_entrada_cravo_jaguare[0] - $soma_saida_cravo_jaguare[0]);
$quant_cravo_jaguare_print = number_format($quant_cravo_jaguare,2,",",".");
$quant_cafe_arabica_jaguare = ($soma_entrada_cafe_arabica_jaguare[0] - $soma_saida_cafe_arabica_jaguare[0]);
$quant_cafe_arabica_jaguare_convert = ($quant_cafe_arabica_jaguare / 60);
$quant_cafe_arabica_jaguare_print = number_format($quant_cafe_arabica_jaguare_convert,2,",",".");


// ===== CASTANHAL ===========================================================
$soma_entrada_cafe_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='CASTANHAL'"));
$soma_entrada_pimenta_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='CASTANHAL'"));
$soma_entrada_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='CASTANHAL'"));
$soma_entrada_cravo_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='CASTANHAL'"));
$soma_entrada_cafe_arabica_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='CASTANHAL'"));
$soma_entrada_residuo_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='CASTANHAL'"));


$soma_saida_cafe_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='CASTANHAL'"));
$soma_saida_pimenta_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='CASTANHAL'"));
$soma_saida_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='CASTANHAL'"));
$soma_saida_cravo_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='CASTANHAL'"));
$soma_saida_cafe_arabica_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='CASTANHAL'"));
$soma_saida_residuo_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='CASTANHAL'"));


$quant_cafe_castanhal = ($soma_entrada_cafe_castanhal[0] - $soma_saida_cafe_castanhal[0]);
$quant_cafe_castanhal_convert = ($quant_cafe_castanhal / 60);
$quant_cafe_castanhal_print = number_format($quant_cafe_castanhal_convert,2,",",".");
$quant_pimenta_castanhal = ($soma_entrada_pimenta_castanhal[0] - $soma_saida_pimenta_castanhal[0]);
$quant_pimenta_castanhal_print = number_format($quant_pimenta_castanhal,2,",",".");
$quant_cacau_castanhal = ($soma_entrada_cacau_castanhal[0] - $soma_saida_cacau_castanhal[0]);
$quant_cacau_castanhal_print = number_format($quant_cacau_castanhal,2,",",".");
$quant_cravo_castanhal = ($soma_entrada_cravo_castanhal[0] - $soma_saida_cravo_castanhal[0]);
$quant_cravo_castanhal_print = number_format($quant_cravo_castanhal,2,",",".");
$quant_cafe_arabica_castanhal = ($soma_entrada_cafe_arabica_castanhal[0] - $soma_saida_cafe_arabica_castanhal[0]);
$quant_cafe_arabica_castanhal_convert = ($quant_cafe_arabica_castanhal / 60);
$quant_cafe_arabica_castanhal_print = number_format($quant_cafe_arabica_castanhal_convert,2,",",".");



// ===== SAO MATEUS ===========================================================
$soma_entrada_cafe_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='SAO_MATEUS'"));
$soma_entrada_pimenta_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='SAO_MATEUS'"));
$soma_entrada_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='SAO_MATEUS'"));
$soma_entrada_cravo_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='SAO_MATEUS'"));
$soma_entrada_cafe_arabica_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='SAO_MATEUS'"));
$soma_entrada_residuo_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='SAO_MATEUS'"));


$soma_saida_cafe_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='SAO_MATEUS'"));
$soma_saida_pimenta_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='SAO_MATEUS'"));
$soma_saida_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='SAO_MATEUS'"));
$soma_saida_cravo_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='SAO_MATEUS'"));
$soma_saida_cafe_arabica_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='SAO_MATEUS'"));
$soma_saida_residuo_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='SAO_MATEUS'"));


$quant_cafe_sao_mateus = ($soma_entrada_cafe_sao_mateus[0] - $soma_saida_cafe_sao_mateus[0]);
$quant_cafe_sao_mateus_convert = ($quant_cafe_sao_mateus / 60);
$quant_cafe_sao_mateus_print = number_format($quant_cafe_sao_mateus_convert,2,",",".");
$quant_pimenta_sao_mateus = ($soma_entrada_pimenta_sao_mateus[0] - $soma_saida_pimenta_sao_mateus[0]);
$quant_pimenta_sao_mateus_print = number_format($quant_pimenta_sao_mateus,2,",",".");
$quant_cacau_sao_mateus = ($soma_entrada_cacau_sao_mateus[0] - $soma_saida_cacau_sao_mateus[0]);
$quant_cacau_sao_mateus_print = number_format($quant_cacau_sao_mateus,2,",",".");
$quant_cravo_sao_mateus = ($soma_entrada_cravo_sao_mateus[0] - $soma_saida_cravo_sao_mateus[0]);
$quant_cravo_sao_mateus_print = number_format($quant_cravo_sao_mateus,2,",",".");
$quant_cafe_arabica_sao_mateus = ($soma_entrada_cafe_arabica_sao_mateus[0] - $soma_saida_cafe_arabica_sao_mateus[0]);
$quant_cafe_arabica_sao_mateus_convert = ($quant_cafe_arabica_sao_mateus / 60);
$quant_cafe_arabica_sao_mateus_print = number_format($quant_cafe_arabica_sao_mateus_convert,2,",",".");




// ===== VARGINHA ===========================================================
$soma_entrada_cafe_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='VARGINHA'"));
$soma_entrada_pimenta_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='VARGINHA'"));
$soma_entrada_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='VARGINHA'"));
$soma_entrada_cravo_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='VARGINHA'"));
$soma_entrada_cafe_arabica_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='VARGINHA'"));
$soma_entrada_residuo_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='VARGINHA'"));


$soma_saida_cafe_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='VARGINHA'"));
$soma_saida_pimenta_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='VARGINHA'"));
$soma_saida_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='VARGINHA'"));
$soma_saida_cravo_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='VARGINHA'"));
$soma_saida_cafe_arabica_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='VARGINHA'"));
$soma_saida_residuo_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='VARGINHA'"));


$quant_cafe_varginha = ($soma_entrada_cafe_varginha[0] - $soma_saida_cafe_varginha[0]);
$quant_cafe_varginha_convert = ($quant_cafe_varginha / 60);
$quant_cafe_varginha_print = number_format($quant_cafe_varginha_convert,2,",",".");
$quant_pimenta_varginha = ($soma_entrada_pimenta_varginha[0] - $soma_saida_pimenta_varginha[0]);
$quant_pimenta_varginha_print = number_format($quant_pimenta_varginha,2,",",".");
$quant_cacau_varginha = ($soma_entrada_cacau_varginha[0] - $soma_saida_cacau_varginha[0]);
$quant_cacau_varginha_print = number_format($quant_cacau_varginha,2,",",".");
$quant_cravo_varginha = ($soma_entrada_cravo_varginha[0] - $soma_saida_cravo_varginha[0]);
$quant_cravo_varginha_print = number_format($quant_cravo_varginha,2,",",".");
$quant_cafe_arabica_varginha = ($soma_entrada_cafe_arabica_varginha[0] - $soma_saida_cafe_arabica_varginha[0]);
$quant_cafe_arabica_varginha_convert = ($quant_cafe_arabica_varginha / 60);
$quant_cafe_arabica_varginha_print = number_format($quant_cafe_arabica_varginha_convert,2,",",".");



// ===== TOTAL ===========================================================
$quant_cafe_total = ($quant_cafe_linhares + $quant_cafe_jaguare + $quant_cafe_castanhal + $quant_cafe_sao_mateus + $quant_cafe_varginha);
$quant_cafe_total_convert = ($quant_cafe_total / 60);
$quant_cafe_total_print = number_format($quant_cafe_total_convert,2,",",".");
$quant_pimenta_total = ($quant_pimenta_linhares + $quant_pimenta_jaguare + $quant_pimenta_castanhal + $quant_pimenta_sao_mateus + $quant_pimenta_varginha);
$quant_pimenta_total_print = number_format($quant_pimenta_total,2,",",".");
$quant_cacau_total = ($quant_cacau_linhares + $quant_cacau_jaguare + $quant_cacau_castanhal + $quant_cacau_sao_mateus + $quant_cacau_varginha);
$quant_cacau_total_print = number_format($quant_cacau_total,2,",",".");
$quant_cravo_total = ($quant_cravo_linhares + $quant_cravo_jaguare + $quant_cravo_castanhal + $quant_cravo_sao_mateus + $quant_cravo_varginha);
$quant_cravo_total_print = number_format($quant_cravo_total,2,",",".");
$quant_cafe_arabica_total = ($quant_cafe_arabica_linhares + $quant_cafe_arabica_jaguare + $quant_cafe_arabica_castanhal + $quant_cafe_arabica_sao_mateus + $quant_cafe_arabica_varginha);
$quant_cafe_arabica_total_convert = ($quant_cafe_arabica_total / 60);
$quant_cafe_arabica_total_print = number_format($quant_cafe_arabica_total_convert,2,",",".");


// =======================================================================
?>



<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:300px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:295px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Resumo Geral de Estoque</b>
	</div>
	
	<div id="centro" style="height:25px; width:22px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
	</div>
</div>



<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto">
</div>


<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:20px; width:80px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:920px; border:1px solid #006600; color:#006600; font-size:12px; float:left; text-align:center; border-radius:2px">
	<div style="margin-top:4px"><b>Estoque F&iacute;sico Atual</b></div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto">
</div>

<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left">
	<!-- <b>Produtos</b> -->
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>Linhares</b>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>Jaguar&eacute;</b>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>Castanhal</b>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>S&atilde;o Mateus</b>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>Varginha</b>
	</div>


	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#0000FF; font-size:11px; float:left; text-align:center">
	<b>TOTAL</b>
	</div>

</div>



<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:left">
	Caf&eacute; Conilon
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_linhares_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_jaguare_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_castanhal_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_sao_mateus_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_varginha_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<b><?php echo "$quant_cafe_total_print Sc"; ?></b>
	</div>
</div>


<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:left">
	Pimenta do Reino
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_pimenta_linhares_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_pimenta_jaguare_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_pimenta_castanhal_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_pimenta_sao_mateus_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_pimenta_varginha_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<b><?php echo "$quant_pimenta_total_print Kg"; ?></b>
	</div>
</div>



<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:left">
	Cacau
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cacau_linhares_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cacau_jaguare_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cacau_castanhal_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cacau_sao_mateus_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cacau_varginha_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<b><?php echo "$quant_cacau_total_print Kg"; ?></b>
	</div>
</div>




<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:left">
	Cravo da &Iacute;ndia
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cravo_linhares_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cravo_jaguare_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cravo_castanhal_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cravo_sao_mateus_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cravo_varginha_print Kg"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<b><?php echo "$quant_cravo_total_print Kg"; ?></b>
	</div>
</div>


<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; float:left">
	</div>
	
	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:left">
	Caf&eacute; Ar&aacute;bica
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_arabica_linhares_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_arabica_jaguare_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_arabica_castanhal_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_arabica_sao_mateus_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<?php echo "$quant_cafe_arabica_varginha_print Sc"; ?>
	</div>

	<div id="centro" style="height:20px; width:120px; border:0px solid #000; color:#003466; font-size:11px; float:left; text-align:center">
	<b><?php echo "$quant_cafe_arabica_total_print Sc"; ?></b>
	</div>
</div>












<!-- ============================================================================================================ -->
<!-- ============================================================================================================ -->
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