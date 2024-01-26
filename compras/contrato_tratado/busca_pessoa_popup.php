<?php
	include ('../../includes/config.php'); 
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'busca_pessoa_popup';
	$titulo = 'Pesquisa Pessoa';	
	$menu = 'produtos';
	$modulo = 'compras';
	
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
<div id="centro" style="width:670px; height:auto; border:1px solid #999; margin:auto; margin-top:15px; border-radius:20px; background-color:#FFF">
<div id="centro" style="width:660px; border:0px solid #000; margin:auto">

<div id="espaco_2" style="width:650px"></div>

<div id="centro" style="height:30px; width:650px; border:0px solid #000; color:#003466; font-size:12px">
<!-- &#160;&#160;&#8226; <b>Pesquisa - Pessoa F&iacute;sica</b> -->
</div>

<div id='centro' style='width:650px; height:auto; border:0px solid #999; margin:auto'>
<div id='centro' style='width:640px; height:auto; border:1px solid #999; border-radius:10px; margin:auto'>







<?php
$nome = $_POST["nome"];
$cpf = $_POST["cpf"];
$cnpj = $_POST["cnpj"];
$tipo_pessoa = $_POST["tipo_pessoa"];
$mostra_inativo = $_POST["mostra_inativo"];
?>

<div id="centro" style="width:660px; height:35px; border:0px solid #999; color:#666; font-size:12px">
<div id="espaco_1" style="width:650px; float:left; border:0px solid #999"></div>
<div id="espaco_1" style="width:650px; float:left; border:0px solid #999"></div>
<div id="centro" style="width:20px; float:left; height:20px; border:0px solid #999"></div>

<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/busca_pessoa_popup.php" method="post" />
<input type='hidden' name='tipo_pessoa' value='pf' />
<i>Nome:</i><input type="text" name="nome" id="ok" size="45" maxlength="50" style="color:#0000FF" value="<?php echo"$nome"; ?>" />
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="18px" align="absmiddle" border="0" />

</div>
</div>
</div>



<div id="centro" style="height:5px; width:650px; border:0px solid #000; color:#666; font-size:12px">
	<div id="centro" style="width:200px; float:left; height:5px; border:0px solid #999">
    </form>
	</div>
   
   	<div id="centro" style="width:300px; float:right; height:5px; border:0px solid #999; text-align:right">
    <?php
	if ($nome != '')
	{
	$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome%' ORDER BY nome");
	$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	}
	else
	{
	$busca_pessoa = 0;
	$linha_pessoa = 0;
	}

	?>
 	</div>
</div>

<?php
if ($nome == '' and $cpf == '' and $cnpj == '')
{echo "<div id='centro' style='height:145px; font-style:normal; width:660px; margin:auto; border:0px solid #999'>
<div id='centro' style='height:auto; font-style:normal; width:650px; margin:auto; border:0px solid #999; border-radius:10px'>";}
else
{echo "<div id='centro' style='height:auto; font-style:normal; width:660px; margin:auto; border:0px solid #999'>
<div id='centro' style='height:auto; font-style:normal; width:650px; margin:auto; border:0px solid #999; border-radius:10px'>";}
?>




<div id="centro" style="height:2px; width:650px; border:0px solid #000; color:#666; font-size:14px">
<!-- &#160;&#160;&#8226; --> <i><!-- Resultado da Busca --></i>
</div>


<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:10px">

<script>
	function retorna(retorno)
	{
	window.opener.document.popup.fornecedor.value = retorno;
	window.self.close();
	}
</script>


<?php
//	<a href=javascript:retorna('$aux_pessoa[0]'); style='text-decoration:none; color:#00F'>&#160;&#160;$aux_pessoa[0] - $aux_pessoa[1]</a>
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
		
	echo "<tr style='color:#00F'>
	<td width='410px' align='left' style='text-transform:uppercase'>
	<a href=javascript:retorna('$aux_pessoa[0]'); style='text-decoration:none; color:#00F'>&#160;&#160;$aux_pessoa[0] - $aux_pessoa[1]</a>

	
	
	</td>";
	
	if ($aux_pessoa[2] == 'pf')
	{echo "<td width='185px' align='center'>&#160;$aux_pessoa[3]</td>";}
	else
	{echo "<td width='185px' align='center'>&#160;$aux_pessoa[4]</td></tr>";}

}

if ($linha_pessoa == 0 and ($nome != '' or $cpf != ''))
{echo "<tr style='color:#F00; font-size:11px'>
<td width='829px' align='left'>&#160;&#160;<i>Nenhum cadastro encontrado.</i></td></tr>";}




?>


</table>
<div id="centro" style="width:650px; height:20px; border:0px solid #039"></div>
</div>
<div id="centro" style="width:650px; height:95px; border:0px solid #039; font-size:11px; color:#999">
<div id="centro" style="width:650px; height:20px; border:0px solid #039; font-size:11px; color:#999"></div>
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