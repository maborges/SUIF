<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_impressao_saida';
$titulo = 'Estoque - Romaneio de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'movimentacao';

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

<div id="centro" style="width:745px; border:0px solid #000; float:left">

<?php

$numero_romaneio = $_POST["numero_romaneio"];

// =============================================================================================================
// =============================================================================================================
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
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],0,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],0,",",".");
$peso_bruto = ($peso_final - $peso_inicial);
$peso_bruto_print = number_format($peso_bruto,0,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],0,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],0,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],0,",",".");
$unidade = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$confirmacao_negocio = $aux_romaneio[32];
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
	$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
	$linha_sacaria = mysqli_num_rows ($busca_sacaria);
	
	for ($s=1 ; $s<=$linha_sacaria ; $s++)
	{
	$aux_sacaria = mysqli_fetch_row($busca_sacaria);
	}
	$tipo_sacaria = $aux_sacaria[1];



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

// SITUA��O PRINT  ==========================================================================================
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
<div id="centro" style="width:720px; height:8px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>

<div id="centro" style="width:720px; height:60px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
    <div id="centro" style="width:300px; height:58px; border:0px solid #000; font-size:12px; float:left" align="left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_grancafe_pb.png" alt="Grancafe" border="0" />	
	</div>
	
    <div id="centro" style="width:100px; height:58px; border:0px solid #000; font-size:12px; float:left" align="left">
	</div>

    <div id="centro" style="width:300px; height:58px; border:0px solid #000; font-size:16px; float:right" align="right">
	<div style="margin-top:24px"><b>PESAGEM - ROMANEIO DE SA&Iacute;DA</b></div>
	</div>


</div>

<div id="centro" style="width:720px; height:18px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:200px; height:17px; border:0px solid #000; font-size:12px; float:left" align="left">
	<?php // echo"$data_print"; ?>
	</div>

    <div id="centro" style="width:314px; height:17px; border:0px solid #000; font-size:12px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?>
	</div>

    <div id="centro" style="width:200px; height:17px; border:0px solid #000; font-size:12px; float:left" align="right">
	N&ordm; <?php echo"$numero_romaneio"; ?>
	</div>

</div>

<div id="centro" style="width:720px; height:17px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:200px; height:16px; border:0px solid #000; font-size:12px; float:left" align="left">
	<?php // echo"$data_print"; ?>
	</div>

    <div id="centro" style="width:314px; height:16px; border:0px solid #000; font-size:12px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?>
	</div>

    <div id="centro" style="width:200px; height:16px; border:0px solid #000; font-size:12px; float:left" align="right">
	<?php echo"$data_print $hora_alteracao"; ?>
	</div>

</div>



<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:0px; margin-left:20px; float:left">

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:20px; border:0px solid #000; font-size:10px">
	<div style="margin-top:3px; margin-left:5px">Dados do Cliente:</div></div>
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

<!-- ========================================================== DADOS DA PESAGEM ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:9px; margin-top:5px">
	<div style="margin-top:3px; margin-left:5px"><!-- Dados da Compra: --></div></div>
		<div id="tabela_2" style="width:730px; height:230px; border:0px solid #999; color:#000; border-radius:5px; overflow:hidden">

			
			<div style="width:320px; height:15px; border:0px solid #000; float:left; font-size:13px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Safra: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$safra</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Tipo: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$tipo</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Umidade: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$umidade</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Umidade:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$broca</b>" ?></div></div>
			
			<div style="width:720px; height:18px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:6px">
			<div style="margin-top:5px">Peso Inicial</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Final</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso L&iacute;quido</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Desconto Sacaria</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Outros Descontos</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Desc. Sacaria</div></div>

			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:6px">
			<div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"<b>$quantidade_print</b> Kg" ?></div></div>


			<div style="width:720px; height:20px; border:0px solid #000; float:left; font-size:12px"></div>


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade real em sacas:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_real_print Sacas</b>" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$tipo_sacaria" ?></div></div>



			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista_cpf" ?></div></div>
			


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$placa_veiculo" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Confirma&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$confirmacao_negocio" ?></div></div>
		


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:690px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left; overflow:hidden"><?php echo"$observacao" ?></div></div>




		</div>
		</div>















<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:55px; border:0px solid #000; margin-left:10px; margin-top:1px; font-size:17px; float:left" align="center">

<div id="centro" style="width:710px; height:20px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">__________________________</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">__________________________</div>

<div id="centro" style="width:710px; height:10px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Balan&ccedil;a</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">Fiscal</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">Armaz&eacute;m</div>

</div>


		
<div id="centro" style="width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:18px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy; SUIF | GRANCAF&Eacute;
</div>
<div id="centro" style="width:250px; height:18px; border:0px solid #000; font-size:9px; float:left" align="center">
<?php echo"$usuario_print" ?> <?php echo"($filial_print)" ?>
</div>
<div id="centro" style="width:230px; height:18px; border:0px solid #000; font-size:9px; float:left" align="right">
1&ordf; VIA
</div>
</div>
<!-- =============================================================================================== -->





<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:5px; font-size:17px; float:left" align="center">
<hr style="border:1px dashed #999" />
</div>






<div id="centro" style="width:720px; height:8px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>

<div id="centro" style="width:720px; height:60px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
    <div id="centro" style="width:300px; height:58px; border:0px solid #000; font-size:12px; float:left" align="left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_grancafe_pb.png" alt="Grancafe" border="0" />	
	</div>
	
    <div id="centro" style="width:100px; height:58px; border:0px solid #000; font-size:12px; float:left" align="left">
	</div>

    <div id="centro" style="width:300px; height:58px; border:0px solid #000; font-size:16px; float:right" align="right">
	<div style="margin-top:24px"><b>PESAGEM - ROMANEIO DE SA&Iacute;DA</b></div>
	</div>


</div>

<div id="centro" style="width:720px; height:18px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:200px; height:17px; border:0px solid #000; font-size:12px; float:left" align="left">
	<?php // echo"$data_print"; ?>
	</div>

    <div id="centro" style="width:314px; height:17px; border:0px solid #000; font-size:12px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?>
	</div>

    <div id="centro" style="width:200px; height:17px; border:0px solid #000; font-size:12px; float:left" align="right">
	N&ordm; <?php echo"$numero_romaneio"; ?>
	</div>

</div>

<div id="centro" style="width:720px; height:17px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:200px; height:16px; border:0px solid #000; font-size:12px; float:left" align="left">
	<?php // echo"$data_print"; ?>
	</div>

    <div id="centro" style="width:314px; height:16px; border:0px solid #000; font-size:12px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?>
	</div>

    <div id="centro" style="width:200px; height:16px; border:0px solid #000; font-size:12px; float:left" align="right">
	<?php echo"$data_print $hora_alteracao"; ?>
	</div>

</div>



<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:0px; margin-left:20px; float:left">

<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:20px; border:0px solid #000; font-size:10px">
	<div style="margin-top:3px; margin-left:5px">Dados do Cliente:</div></div>
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

<!-- ========================================================== DADOS DA PESAGEM ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:15px; border:0px solid #000; font-size:9px; margin-top:5px">
	<div style="margin-top:3px; margin-left:5px"><!-- Dados da Compra: --></div></div>
		<div id="tabela_2" style="width:730px; height:230px; border:0px solid #999; color:#000; border-radius:5px; overflow:hidden">

			
			<div style="width:320px; height:15px; border:0px solid #000; float:left; font-size:13px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Safra: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$safra</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Tipo: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$tipo</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!-- Umidade: --></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$umidade</b>" ?></div></div>
			<div style="width:100px; height:15px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Umidade:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$broca</b>" ?></div></div>
			
			<div style="width:720px; height:18px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:6px">
			<div style="margin-top:5px">Peso Inicial</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Final</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Bruto</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Desconto Sacaria</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Outros Descontos</div></div>
			<div style="width:100px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso L&iacute;quido</div></div>

			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:6px">
			<div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
			<div style="width:100px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"<b>$quantidade_print</b> Kg" ?></div></div>


			<div style="width:720px; height:20px; border:0px solid #000; float:left; font-size:12px"></div>


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade real em sacas:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_real_print Sacas</b>" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$tipo_sacaria" ?></div></div>



			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista_cpf" ?></div></div>
			


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$placa_veiculo" ?></div></div>

			<div style="width:335px; height:20px; border:0px solid #000; float:left; font-size:12px">
			<div style="margin-top:3px; margin-left:5px; float:left">Confirma&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$confirmacao_negocio" ?></div></div>
		


			<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:690px; height:20px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div><div style="margin-top:3px; margin-left:5px; float:left; overflow:hidden"><?php echo"$observacao" ?></div></div>




		</div>
		</div>















<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:55px; border:0px solid #000; margin-left:10px; margin-top:1px; font-size:17px; float:left" align="center">

<div id="centro" style="width:710px; height:20px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">__________________________</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">__________________________</div>

<div id="centro" style="width:710px; height:10px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Balan&ccedil;a</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">Fiscal</div>
	<div id="centro" style="width:230px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:0px" align="center">Armaz&eacute;m</div>

</div>


		
<div id="centro" style="width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:18px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy; SUIF | GRANCAF&Eacute;
</div>
<div id="centro" style="width:250px; height:18px; border:0px solid #000; font-size:9px; float:left" align="center">
<?php echo"$usuario_print" ?> <?php echo"($filial_print)" ?>
</div>
<div id="centro" style="width:230px; height:18px; border:0px solid #000; font-size:9px; float:left" align="right">
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