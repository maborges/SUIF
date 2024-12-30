<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'busca_pessoa_popup';
$titulo = 'Pesquisa Pessoa';	
$modulo = 'estoque';
$menu = 'saida';

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



<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro" style="width:850px; height:auto; border:1px solid #999; margin:auto; margin-top:15px; margin-bottom:15px; border-radius:5px; background-color:#FFF">

<?php
$nome_form = $_POST["nome_form"];
/*
$cpf = $_POST["cpf"];
$cnpj = $_POST["cnpj"];
$tipo_pessoa = $_POST["tipo_pessoa"];
$mostra_inativo = $_POST["mostra_inativo"];
*/

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
?>

<script>
	function retorna(retorno)
	{
	window.opener.document.popup.fornecedor.value = retorno;
	window.self.close();
	}
</script>


<div style="width:840px; height:20px; margin:auto"></div>


<div id="centro" style="height:36px; width:810px; border:1px solid #999; margin:auto; background-color:#EEE">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_saida/busca_pessoa_popup.php" method="post" />

	<div id="centro" style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div id="centro" style="height:20px; width:50px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:left; margin-top:11px">
	<i>Nome:</i>
    </div>

	<div id="centro" style="height:20px; width:400px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="nome_form" id="ok" maxlength="50" style="height:16px; width:395px; color:#0000FF; font-size:12px" value="<?php echo"$nome"; ?>" />
	</div>


	<div id="centro" style="height:auto; width:auto; border:0px solid #999; float:left; margin-left:20px; margin-top:4px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>

<!--
	<div id="centro" style="height:auto; width:auto; border:0px solid #999; float:left; margin-left:20px; margin-top:4px">
    <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/index_pessoas.php"><button type='submit' class='botao_1'>Cadastrar</button></a>
	</div>
-->	
</div>





<div id="centro" style="width:840px; border:0px solid #000; margin:auto">


<div style="width:840px; height:20px; margin:auto"></div>


<?php
if ($nome_form == "")
{echo "<div id='centro' style='height:250px; font-style:normal; width:840px; margin:auto; border:0px solid #999'>
<div id='centro' style='height:auto; font-style:normal; width:820px; margin:auto; border:0px solid #999; border-radius:10px'>";}
else
{echo "<div id='centro' style='height:auto; font-style:normal; width:840px; margin:auto; border:0px solid #999'>
<div id='centro' style='height:auto; font-style:normal; width:820px; margin:auto; border:0px solid #999; border-radius:10px'>";}


if ($linha_pessoa_geral == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='360px' height='24px' align='center' bgcolor='#006699'>Nome</td>
<td width='130px' align='center' bgcolor='#006699'>CPF/CNPJ</td>
<td width='100px' align='center' bgcolor='#006699'>Telefone</td>
<td width='210px' align='center' bgcolor='#006699'>Cidade/UF</td>
</tr>
</table>";}



echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:11px'>";


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
	<td width='360px' height='24px' align='left'>
		<div style='height:16px; margin-left:7px; overflow:hidden'>
		<a href=javascript:retorna('$aux_pessoa[0]'); style='text-decoration:none; color:#00F'>$fornecedor_print_x</a>
		</div>
	</td>
	<td width='130px' align='center'><div style='height:13px; overflow:hidden'>$cpf_cnpj_x</div></td>
	<td width='100px' align='center'><div style='height:13px; overflow:hidden'>$telefone_fornecedor_x</div></td>
	<td width='210px' align='center'><div style='height:13px; overflow:hidden'>$cidade_fornecedor_x/$estado_fornecedor_x</div></td>
	</tr>";

}

if ($linha_pessoa_geral == 0 and ($nome_form != ""))
{echo "<div id='centro' style='height:80px; width:700px; border:0px solid #000; color:#F00; font-size:11px; margin:auto; text-align:center'><i>Nenhum cadastro encontrado.</i></div>";}


echo "</table>";
// =================================================================================================================
?>








</div>
<div id="centro" style="width:840px; height:95px; border:0px solid #039; font-size:11px; color:#999">
<div id="centro" style="width:840px; height:20px; border:0px solid #039; font-size:11px; color:#999"></div>
</div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->









<!-- ======================================================================================================== -->
</div>
</div>


<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>