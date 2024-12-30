<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'relatorio_fornecedor_seleciona';
$titulo = 'Relat&oacute;rios de Contratos Futuros';
$modulo = 'compras';
$menu = 'contratos';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$nome_form = $_POST["nome_form"];
// ========================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($nome_form != "")
{
$busca_pessoa_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome_form%' ORDER BY nome");
$linha_pessoa_geral = mysqli_num_rows ($busca_pessoa_geral);
}
else
{
$busca_pessoa_geral = 0;
$linha_pessoa_geral = 0;
}
// ========================================================================================================


// ========================================================================================================
include ('../../includes/head.php'); 
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
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>
<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:auto; margin:auto; background-color:#FFF; border-radius:10px; border:1px solid #999">
<div style="width:1250px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1100px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:0px">
    Contratos Futuros
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:0px">
    	<div id="menu_atalho_3" style="margin-top:10px">
    	<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorios.php' >
        &#8226; Outros relat&oacute;rios de contratos futuros</a>
        </div>
    </div>
</div>

<div style="width:1250px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1100px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:0px; font-size:14px">
	Selecione um fornecedor
    </div>
</div>

<div style="width:1100px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<!--
<div style="width:1250px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    <?php // echo "$msg"; ?>
    </div>
</div>

<div style="width:1080px; height:5px; border:0px solid #000; margin:auto"></div>
-->
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:36px; width:1250px; border:1px solid #999; margin:auto; background-color:#EEE">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_fornecedor_seleciona.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR_PESSOA' />

	<div id="centro" style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div id="centro" style="height:20px; width:50px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:left; margin-top:11px">
	<i>Nome:</i>
    </div>

	<div id="centro" style="height:20px; width:400px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="nome_form" id="ok" maxlength="50" style="height:16px; width:395px; color:#0000FF; font-size:12px" value="<?php echo"$nome_form"; ?>" />
	</div>


	<div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:20px; margin-top:8px">
    <input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
    </form>
	</div>
	
</div>

<div id="centro" style="height:15px; width:1250px; border:0px solid #000; margin:auto"></div>


<div id="centro" style="height:26px; width:1250px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:400px; float:left; height:26px; margin-left:10px; border:0px solid #999">
	</div>
	
	<div id="centro" style="width:400px; float:left; height:26px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:400px; float:right; height:26px; border:0px solid #999; font-size:12px; color:#003466; text-align:right; margin-right:10px">
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:10px; width:1250px; border:0px solid #000; margin:auto; border-radius:0px"></div>



<!-- ====================================================================================== -->
<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<?php
// ======================================================================================================
if ($linha_pessoa_geral == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:5px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_pessoa_geral == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:11px'>
<tr>
<td width='450px' height='24px' align='center' bgcolor='#006699'>Nome</td>
<td width='200px' align='center' bgcolor='#006699'>CPF/CNPJ</td>
<td width='200px' align='center' bgcolor='#006699'>Telefone</td>
<td width='300px' align='center' bgcolor='#006699'>Cidade/UF</td>
</tr>
</table>";}

echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pessoa_geral ; $x++)
{
$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

// ====== DADOS DO CONTRATO ============================================================================
$fornecedor_x = $aux_pessoa_geral[0];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_x' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print_x = $aux_pessoa[1];
$codigo_pessoa_x = $aux_pessoa[35];
$cidade_fornecedor_x = $aux_pessoa[10];
$estado_fornecedor_x = $aux_pessoa[12];
$telefone_fornecedor_x = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj_x = $aux_pessoa[3];}
else
{$cpf_cnpj_x = $aux_pessoa[4];}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	echo "
	<tr style='color:#00F'>
	<td width='450px' height='24px' align='left'>
		<div style='margin-left:10px'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/relatorio_fornecedor.php' method='post'>
		<input type='hidden' name='fornecedor_form' value='$aux_pessoa[0]'>
		<input type='submit' style='width:430px; height:22px; text-align:left; border:0px solid #000; color:#00F; background-color:transparent' value='$fornecedor_print_x'>
		</form>
		</div>
	</td>
	<td width='200px' align='center'>$cpf_cnpj_x</td>
	<td width='200px' align='center'>$telefone_fornecedor_x</td>
	<td width='300px' align='center'>$cidade_fornecedor_x/$estado_fornecedor_x</td>
	</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_pessoa_geral == 0 and ($nome_form != '' or $cpf != ''))
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum cadastro encontrado.</i></div>";}
else
{}
// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================
?>


<!-- ====================================================================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<!-- FIM CENTRO GERAL (depois do menu geral) -->
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>