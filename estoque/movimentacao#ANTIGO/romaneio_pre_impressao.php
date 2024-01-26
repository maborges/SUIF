<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_pre_impressao';
$titulo = 'Pr&eacute;-Romaneio de Entrada';
$modulo = 'estoque';
$menu = 'movimentacao';

include ('../../includes/head_impressao.php');
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
<body onLoad="imprimir()">

<div id="centro" style="width:745px; border:0px solid #000; float:left">

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
// ==================================================================================================================	

$numero_romaneio = $_POST["numero_romaneio"];
$numero_compra = $_POST["numero_compra"];

//  BUSCA ROMANEIO ==================================================================================================
// ==================================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],3,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],3,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,3,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],3,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],3,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],3,",",".");
$unidade = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao = $aux_romaneio[18];
$usuario_cadastro = $aux_romaneio[19];
$hora_cadastro = $aux_romaneio[20];
$data_cadastro = $aux_romaneio[21];
$usuario_alteracao = $aux_romaneio[22];
$hora_alteracao = $aux_romaneio[23];
$data_alteracao = $aux_romaneio[24];
$filial_print = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];

$usuario_print = $aux_romaneio[19];
$filial_print = $aux_romaneio[25];


// BUSCA SACARIA ==============================================================================================
// =============================================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

for ($s=1 ; $s<=$linha_sacaria ; $s++)
{
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
}
$tipo_sacaria = $aux_sacaria[1];



//  BUSCA COMPRA ==================================================================================================
// ==================================================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$quantidade_compra = $aux_compra[5];
$quantidade_compra_print = number_format($aux_compra[5],2,",",".");
$unidade_compra = $aux_compra[8];
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$tipo_secagem = $aux_compra[27];
$observacao_compra = $aux_compra[13];







// CALCULO QUANTIDADE REAL  ==========================================================================================
	if ($produto == "CAFE")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "PIMENTA")
	{$quantidade_real = ($quantidade / 50);}
	elseif ($produto == "CACAU")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "CRAVO")
	{$quantidade_real = ($quantidade / 60);}
	else
	{$quantidade_real = "";}

	$quantidade_real_print = number_format($quantidade_real,2,",",".");

// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{$unidade_print = "Sc";}
	elseif ($unidade == "KG")
	{$unidade_print = "Kg";}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_romaneio == "PRE_ROMANEIO")
	{$situacao_print = "Pr&eacute;-Romaneio";}
	elseif ($situacao_romaneio == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_romaneio == "FECHADO")
	{$situacao_print = "Fechado";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "pf")
		{$cpf_cnpj = $aux_pessoa[3];}
		else
		{$cpf_cnpj = $aux_pessoa[4];}
	}
// =================================================================================================================



// =============================================================================================================
// =============================================================================================================
?>
<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:35px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_grancafe_pb.png" alt="Grancafe" border="0" height="30px" />
</div>

<div id="centro" style="width:720px; height:40px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="left"></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:16px; float:left" align="center">
    <b>PR&Eacute;-ROMANEIO DE ENTRADA</b></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="right"></div>
	
	

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:12px; float:left" align="left"><?php echo"$data_print"; ?></div>

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:13px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?></div>

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:12px; float:left" align="right">N&ordm; <?php echo"$numero_romaneio"; ?></div>

</div>


<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:10px; margin-left:20px; float:left">

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:20px; border:0px solid #000; font-size:10px">
	<div style="margin-top:3px; margin-left:5px">Dados do Fornecedor:</div></div>
		<div id="tabela_2" style="width:730px; height:70px; border:1px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:530px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b> ($aux_pessoa[0])" ?></div></div>
			<div style="width:195px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:530px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[10] - $aux_pessoa[12]</b>" ?></div></div>
			<div style="width:195px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[14]</b>" ?></div></div>

		</div>

<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:10px; margin-top:20px">
	<div style="margin-top:3px; margin-left:5px">Dados da Compra:</div></div>
		<div id="tabela_2" style="width:730px; height:70px; border:1px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:160px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:120px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Safra:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$safra</b>" ?></div></div>
			<div style="width:160px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$tipo</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Umidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$umidade</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			
			<?php
			if ($produto == "CAFE")
			{$escreve_1 = "Broca:";}
			elseif ($produto == "PIMENTA")
			{$escreve_1 = "Impureza:";}
			else
			{$escreve_1 = "";}
			?>
	
			<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$escreve_1"; ?></div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$broca</b>" ?></div></div>

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


			<div style="width:540px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Observa&ccedil;&atilde;o:--></div><div style="margin-top:3px; margin-left:5px; width:400px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php // echo"$observacao" ?></div>
			</div>
			<?php
			if ($produto == "PIMENTA")
			{echo "
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Tipo Secagem:</div><div style='margin-top:3px; margin-left:5px; float:left'>$tipo_secagem</div></div>";}
			else
			{echo "
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'><!-- Tipo Secagem: --></div><div style='margin-top:3px; margin-left:5px; float:left'></div></div>";}
			?>

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


			<div style="width:270px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_compra_print</b> $unidade_compra" ?></div></div>
			<div style="width:270px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Pre&ccedil;o Unit&aacute;rio:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $preco_unitario_print</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Valor Total:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $valor_total_print</b>" ?></div></div>


		</div>



<!-- ========================================================== DADOS DA PESAGEM ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:9px; margin-top:5px">
	<div style="margin-top:3px; margin-left:5px"><!-- Dados da Compra: --></div></div>
		<div id="tabela_2" style="width:730px; height:60px; border:0px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">N&ordm; da compra:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$numero_compra" ?></div></div>
			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$observacao_compra" ?></div></div>
			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Loca de entrega da mercadoria: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"$safra" ?></div></div>
			

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left"></div>
			<div style="margin-top:3px; margin-left:5px; width:720px; height:14px; float:left; border:0px solid #000; overflow:hidden"></div></div>







		</div>
		</div>















<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:75px; border:0px solid #000; margin-left:10px; margin-top:5px; font-size:17px; float:left" align="center">

<div id="centro" style="width:710px; height:43px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>

<div id="centro" style="width:710px; height:10px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Balan&ccedil;a</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Armaz&eacute;m</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Compras</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Fornecedor / Produtor</div>

</div>


		
<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy; SUIF | GRANCAF&Eacute;
</div>
<div id="centro" style="width:250px; height:25px; border:0px solid #000; font-size:9px; float:left" align="center">
<?php echo"$usuario_print" ?> ( <?php echo"$filial_print" ?> )
</div>
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="right">
1&ordf; VIA
</div>
</div>
<!-- =============================================================================================== -->

<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:5px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:5px; font-size:17px; float:left" align="center">
<hr style="border:1px dashed #999" />
</div>

<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:35px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_grancafe_pb.png" alt="Grancafe" border="0" height="30px" />
</div>

<div id="centro" style="width:720px; height:40px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="left"></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:16px; float:left" align="center">
    <b>PR&Eacute;-ROMANEIO DE ENTRADA</b></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="right"></div>
	
	

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:12px; float:left" align="left"><?php echo"$data_print"; ?></div>

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:13px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?></div>

    <div id="centro" style="width:238px; height:18px; border:0px solid #000; font-size:12px; float:left" align="right">N&ordm; <?php echo"$numero_romaneio"; ?></div>

</div>


<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:10px; margin-left:20px; float:left">

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:20px; border:0px solid #000; font-size:10px">
	<div style="margin-top:3px; margin-left:5px">Dados do Fornecedor:</div></div>
		<div id="tabela_2" style="width:730px; height:70px; border:1px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:530px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b> ($aux_pessoa[0])" ?></div></div>
			<div style="width:195px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:530px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[10] - $aux_pessoa[12]</b>" ?></div></div>
			<div style="width:195px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[14]</b>" ?></div></div>

		</div>

<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:10px; margin-top:20px">
	<div style="margin-top:3px; margin-left:5px">Dados da Compra:</div></div>
		<div id="tabela_2" style="width:730px; height:70px; border:1px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:160px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:120px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Safra:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$safra</b>" ?></div></div>
			<div style="width:160px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$tipo</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Umidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$umidade</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			
			<?php
			if ($produto == "CAFE")
			{$escreve_1 = "Broca:";}
			elseif ($produto == "PIMENTA")
			{$escreve_1 = "Impureza:";}
			else
			{$escreve_1 = "";}
			?>
	
			<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$escreve_1"; ?></div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$broca</b>" ?></div></div>

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


			<div style="width:540px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Observa&ccedil;&atilde;o:--></div><div style="margin-top:3px; margin-left:5px; width:400px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php // echo"$observacao" ?></div>
			</div>
			<?php
			if ($produto == "PIMENTA")
			{echo "
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Tipo Secagem:</div><div style='margin-top:3px; margin-left:5px; float:left'>$tipo_secagem</div></div>";}
			else
			{echo "
			<div style='width:180px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'><!-- Tipo Secagem: --></div><div style='margin-top:3px; margin-left:5px; float:left'></div></div>";}
			?>

			<div style="width:705px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


			<div style="width:270px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_compra_print</b> $unidade_compra" ?></div></div>
			<div style="width:270px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Pre&ccedil;o Unit&aacute;rio:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $preco_unitario_print</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Valor Total:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $valor_total_print</b>" ?></div></div>


		</div>



<!-- ========================================================== DADOS DA PESAGEM ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:9px; margin-top:5px">
	<div style="margin-top:3px; margin-left:5px"><!-- Dados da Compra: --></div></div>
		<div id="tabela_2" style="width:730px; height:60px; border:0px solid #999; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">N&ordm; da compra:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$numero_compra" ?></div></div>
			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$observacao_compra" ?></div></div>
			<div style="width:715px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Loca de entrega da mercadoria: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"$safra" ?></div></div>

			

			<div style="width:720px; height:10px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left"></div>
			<div style="margin-top:3px; margin-left:5px; width:720px; height:14px; float:left; border:0px solid #000; overflow:hidden"></div></div>







		</div>
		</div>















<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:75px; border:0px solid #000; margin-left:10px; margin-top:5px; font-size:17px; float:left" align="center">

<div id="centro" style="width:710px; height:43px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>

<div id="centro" style="width:710px; height:10px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Balan&ccedil;a</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Armaz&eacute;m</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Compras</div>
	<div id="centro" style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Fornecedor / Produtor</div>

</div>


		
<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy; SUIF | GRANCAF&Eacute;
</div>
<div id="centro" style="width:250px; height:25px; border:0px solid #000; font-size:9px; float:left" align="center">
<?php echo"$usuario_print" ?> ( <?php echo"$filial_print" ?> )
</div>
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="right">
2&ordf; VIA
</div>
</div>
<!-- =============================================================================================== -->

<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->



</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->