<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include("../../helpers.php");
$pagina = "relatorio_consolidado";
$titulo = "Relat&oacute;rio Consolidado de Compras";
$modulo = "compras";
$menu = "relatorios";


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$filial = $filial_usuario;
$botao = $_POST["botao"];

if ($botao == "interno")
{
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
}
elseif ($botao == "externo")
{
$data_inicial_aux = $data_hoje_aux;
$data_inicial = $data_hoje;
$data_final_aux = $data_hoje_aux;
$data_final = $data_hoje;
}
else
{
$data_inicial_aux = $data_hoje_aux;
$data_inicial = $data_hoje;
$data_final_aux = $data_hoje_aux;
$data_final = $data_hoje;
}
// =======================================================================================================

$quant_dias = (strtotime($data_final) - strtotime($data_inicial))/86400;;
if ($quant_dias > 370)
{

}

else
{
// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================


// ====== BUSCA POR FILIAS GERAL  =======================================================================
$busca_filial_geral = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
$linhas_bf_geral = mysqli_num_rows ($busca_filial_geral);
// =======================================================================================================
}

// ===========================================================================================================
include ("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
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
<?php include ("../../includes/submenu_compras_relatorios.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
	<div id="centro" style="height:1000px; width:1060px; border:0px solid #000; margin:auto">




<!-- =========================================================================================================== -->
	    <div id='centro' style='height:950px; width:1040px; border:1px solid #BCD2EE; margin-top:15px; margin-left:10px; float:left' align='center'>
 <!-- =========================================================================================================== -->
 <!-- =========================================================================================================== -->
          	<div id='centro' style='height:30px; width:1020px; border:0px solid #000; margin-top:10px; background-color:#003466; color:#FFF; 
            font-size:12px; float:left; margin-left:10px; text-align:center'>
				<div style='margin-top:7px'><b>Relat&oacute;rio Consolidado de Compras</b></div>
            </div>

 <!-- =========================================================================================================== -->
			<div id='centro' style='height:35px; width:1020px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:200px; float:left; height:33px; margin-left:10px; border:0px solid #999'>
            		<div style='float:left; margin-top:4px; font-size:11px; color:#003466; text-align:center'>
                    <!-- XXXXXXXXXXXXXXXXXXXX --></div>
				</div>

            	<div id='centro' style='width:580px; float:left; height:30px; margin-left:10px; border:1px solid #999; border-radius:5px'>
                    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_consolidado.php" method="post" />
                    <input type='hidden' name='botao' value='interno' />
            		<div style='margin-top:7px; margin-left:25px; font-size:11px; color:#666; float:left'>
                    <i>Data inicial:&#160;</i>
                    </div>
                    <div style='margin-top:3px; margin-left:10px; font-size:11px; color:#666; float:left'>
                    <input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" 
                    style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>"  />
                    </div>
            		<div style='margin-top:7px; margin-left:25px; font-size:11px; color:#666; float:left'>
                    <i>Data final:&#160;</i>
                    </div>
                    <div style='margin-top:3px; margin-left:10px; font-size:11px; color:#666; float:left'>
                    <input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" 
                    style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>"  />
                    </div>
                    <div style='margin-top:3px; margin-left:30px; font-size:11px; color:#666; float:left'>
                    <input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" 
                    height="20px" style="float:left" />
                    </form>
                    </div>
				</div>

            	<div id='centro' style='width:200px; float:right; height:22px; margin-right:10px; border:0px solid #999'>
            		<div style='float:right; margin-top:4px; font-size:11px; color:#003466; text-align:right'>
                    <!-- XXXXXXXXXXXXXXXXXXXX --></div>
				</div>
            
            </div>

 <!-- =========================================================================================================== -->
<?php
$quant_dias = (strtotime($data_final) - strtotime($data_inicial))/86400;;
if ($quant_dias > 370)
{
echo "<div id='centro' style='height:22px; width:1020px; border:0px solid #000; font-size:11px; margin-top:10px; float:left; margin-left:10px' align='center'>
<div id='oculta' style='color:#FF0000; margin-top:4px'>
<i>Intervalo entre datas n&atilde;o pode ser maior que um ano.</i></div></div>";
}

else
{
echo "
			<div id='centro' style='height:22px; width:1020px; border:0px solid #000; margin-top:10px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
                     <!--&#160;&#160;&#8226; <b><i>xxxxxxxxxxxxx</i></b>--></div>
				</div>

            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
					<i>Quantidade comprada:</i></div>
				</div>

            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
					<i>Valor total:</i></div>
				</div>


            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
                    <i>Pre&ccedil;o M&eacute;dio:</i></div>
				</div>
            
            </div>";
}




//==============================================================================================================
for ($x=1 ; $x<=$linhas_bf_geral ; $x++)
{
$aux_filial = mysqli_fetch_row($busca_filial_geral);

echo "
<div id='centro' style='height:22px; width:1020px; border:0px solid #000; margin-top:4px; float:left; margin-left:10px' align='center'>
<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
<div style='float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
&#160;&#160;&#8226; <b><i>$aux_filial[2]</i></b></div>
</div>
<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'></div>
<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'></div>
<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'></div>
</div>";

// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
	
	$w = 0;

	//==============================================================================================================  
	for ($y=1 ; $y<=$linhas_bp_geral ; $y++)
	{
	$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	// ====== BUSCA E SOMA COMPRAS POR PRODUTO =========================================================================
	$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' 
	AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$aux_filial[1]'"));
	$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
	$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' 
	AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$aux_filial[1]'"));
	$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
	if ($soma_quant_produto[0] <= 0)
	{$media_produto_print = "0,00";}
	else
	{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
	$media_produto_print = number_format($media_produto,2,",",".");}
	// =======================================================================================================
	if ($soma_compra_produto[0] == 0)
		{echo "";
		$w = $w;}
	else
		{echo "
		<div id='centro' style='height:22px; width:1020px; border:0px solid #999; border-radius:2px; background-color:#EEE; 
		margin-top:4px; float:left; margin-left:10px' align='center'>
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='float:left; margin-top:4px; font-size:11px; color:#009900; text-align:center'>
		<b>$aux_bp_geral[22]</b></div>
		</div>
		
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		$quant_produto_print $aux_bp_geral[26]</div>
		</div>
		
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		R$ $soma_cp_print</div>
		</div>
		
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		R$ $media_produto_print / $aux_bp_geral[26]</div>
		</div>
		</div>";
		$w = $w + 1;}
	}
if ($w >= 1)
	{echo "<div style='height:22px; width:1020px; border:0px solid #999; float:left; margin-top:0px; margin-left:10px'></div>";}
else
	{echo "<div style='height:22px; width:1020px; border:0px solid #999; float:left; margin-top:0px; margin-left:10px; font-size:9px; 
	color:#999; text-align:left'><i>(N&atilde;o houve compras neste per&iacute;odo)</i></div>";}

	

}
//==============================================================================================================  
?>

 <!-- =========================================================================================================== -->


 <!-- =========================================================================================================== -->

<?php

$quant_dias = (strtotime($data_final) - strtotime($data_inicial))/86400;;
if ($quant_dias > 370)
{

}

else
{
echo "
 <!-- =========================================================================================================== -->
			<div id='centro' style='height:22px; width:1020px; border:0px solid #000; margin-top:40px; float:left; margin-left:10px' align='center'>
            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
                     &#160;&#160;&#8226; <b><i>TOTAL GERAL</i></b></div>
				</div>

            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
					<i>Quantidade comprada:</i></div>
				</div>

            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
					<i>Valor total:</i></div>
				</div>


            	<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
            		<div style='margin-top:4px; font-size:11px; color:#666; text-align:center'>
                    <i>Pre&ccedil;o M&eacute;dio:</i></div>
				</div>
            
            </div>";




// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================

for ($z=1 ; $z<=$linhas_bp_geral ; $z++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

// ====== BUSCA E SOMA COMPRAS POR PRODUTO =========================================================================
$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' 
AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' 
AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}
// =======================================================================================================
	if ($soma_compra_produto[0] == 0)
		{}
	else
		{echo "
		<div id='centro' style='height:22px; width:1020px; border:1px solid #999; border-radius:2px; background-color:#98FB98; 
		margin-top:2px; float:left; margin-left:10px' align='center'>
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='float:left; margin-top:4px; font-size:11px; color:#00F; text-align:center'>
		<b>$aux_bp_geral[22]</b></div>
		</div>
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		<b>$quant_produto_print $aux_bp_geral[26]</b></div>
		</div>
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		<b>R$ $soma_cp_print</b></div>
		</div>
		<div id='centro' style='width:240px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
		<div style='margin-top:4px; font-size:11px; color:#003466; text-align:center'>
		<b>R$ $media_produto_print / $aux_bp_geral[26]</b></div>
		</div>
		</div>";}
}
//==============================================================================================================  


}

?>


 <!-- =========================================================================================================== -->











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