<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_impressao';
$titulo = 'Estoque - Romaneio de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'saida';

// ====== RECEBE POST =============================================================================================
$numero_romaneio = $_POST["numero_romaneio"];
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
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
$cod_produto = $aux_romaneio[44];
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
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],0,",",".");
$numero_compra = $aux_romaneio[29];
$confirmacao_negocio = $aux_romaneio[32];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$usuario_cadastro = $aux_romaneio[19];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}
$usuario_print = $aux_romaneio[19];
$filial_print = $aux_romaneio[25];
// ================================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descrisao_sacaria = "(Sem sacaria)";}
else
{$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
if ($produto == "CAFE")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CAFE_ARABICA")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "PIMENTA")
{$quantidade_real = ($quantidade / 50);}
elseif ($produto == "CACAU")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CRAVO")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "RESIDUO_CACAU")
{$quantidade_real = ($quantidade / 60);}
else
{$quantidade_real = 0;}

$quantidade_real_print = number_format($quantidade_real,2,",",".");
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================


// ====== SITUA��O PRINT ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($situacao_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($situacao_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


// ====== BUSCA VENDA =================================================================================
/*
$busca_saida = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$aux_busca_saida = mysqli_fetch_row($busca_saida);
$linha_saida = mysqli_num_rows ($busca_saida);

if ($linha_saida == 0)
{$num_registro_saida = "(Romaneio n&atilde;o vinculado a ficha)";}
else
{$num_registro_saida = $aux_busca_saida[1];}
*/
// ======================================================================================================


// ====== SOMA NOTAS FISCAIS ======================================================================
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0],2,",",".");
// ======================================================================================================


// ================================================================================================================
include ('../../includes/head_impressao.php');
?>

<!-- ====== T�TULO DA P�GINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== IN�CIO ================================================================================================ -->
<body onLoad="imprimir()">

<div id="centro" style="width:745px; border:0px solid #000; float:left">

<div id="centro" style="width:730px; height:40px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>

<div id="centro" style="width:730px; height:80px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
	<div id="centro" style="width:355px; height:80px; border:0px solid #000; font-size:17px; float:left" align="left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" border="0" />
    </div>
	<div id="centro" style="width:355px; height:50px; border:0px solid #000; font-size:26px; margin-top:20px; float:right" align="right">
	Romaneio de Sa&iacute;da
    </div>
</div>

<div id="centro" style="width:730px; height:50px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:235px; height:20px; border:0px solid #000; font-size:18px; float:left" align="left"><?php echo"$data_print"; ?></div>
    <div id="centro" style="width:235px; height:20px; border:0px solid #000; font-size:18px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?></div>
    <div id="centro" style="width:235px; height:40px; border:0px solid #000; font-size:18px; float:right" align="right">N&ordm; <?php echo"$numero_romaneio"; ?></div>

    <div id="centro" style="width:235px; height:20px; border:0px solid #000; font-size:14px; float:left" align="left"><?php // echo"$hora_cadastro"; ?></div>
    <div id="centro" style="width:235px; height:20px; border:0px solid #000; font-size:14px; float:left" align="center">
    <?php // echo"<b>$produto_print</b>"; ?></div>


</div>


<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:5px; margin-left:20px; float:left">

<!-- ================================================ FORNECEDOR ============================================================================= -->
<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left">
<div style="margin-top:0px; margin-left:25px">Cliente:</div></div>

<div id="tabela_2" style="width:730px; height:50px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left">

    <div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
    
    <div style="width:500px; height:15px; border:0px solid #000; float:left; font-size:12px">
    <div style="margin-top:3px; margin-left:25px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div></div>
    <div style="width:225px; height:15px; border:0px solid #000; float:left; font-size:12px">
    <div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

    <div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

    <div style="width:500px; height:15px; border:0px solid #000; float:left; font-size:12px">
    <div style="margin-top:3px; margin-left:25px; float:left">Cidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cidade_fornecedor - $estado_fornecedor</b>" ?></div></div>
    <div style="width:225px; height:15px; border:0px solid #000; float:left; font-size:12px">
    <div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div></div>

</div>


<!-- ================================================ PRODUTO ============================================================================= -->
<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left"></div>


<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left">
<div style="margin-top:0px; margin-left:25px">Produto:</div></div>

<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php echo"<b>$produto_print</b>" ?></div>
    </div>
</div>
<!--
<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" ?></div>
    </div>
</div>

<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" ?></div>
    </div>
</div>

<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" ?></div>
    </div>
</div>
-->

<!-- ====================================== DADOS DA PESAGEM ============================================================================= -->
<div id="tabela_2" style="width:730px; height:330px; border:0px solid #000; color:#000; overflow:hidden; float:left">

    <div style="width:725px; height:15px; border:0px solid #000; float:left; font-size:12px"></div>

    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:0px">
    <div style="margin-top:5px">Peso Inicial</div></div>
    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Peso Final</div></div>
    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Peso L&iacute;quido</div></div>
    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Desconto Sacaria</div></div>
    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Outros Descontos</div></div>
    <div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Peso Desc. Sacaria</div></div>

    <div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px">
    <div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
    <div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px"><?php echo"<b>$quantidade_print Kg</b>" ?></div></div>




<!-- ======= DADOS DO ROMANEIO ====================================================================================================== -->
    <div style="width:725px; height:20px; border:0px solid #000; float:left; font-size:12px"></div>
    
    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quant. Real em Sacas:</div>
    	<div style="margin-top:7px; margin-left:0px; float:left"><?php echo"<b>$quantidade_real_print Sacas</b>" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Situa&ccedil;&atilde;o Romaneio:</div>
        <div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$situacao_print" ?></div>
	</div>



    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quant. Volume Sacas:</div>
    	<div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$quant_volume" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div>
        <div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$num_romaneio_manual" ?></div>
	</div>



    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Tipo Sacaria:</div>
        <div style="width:185px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
		<?php echo"$descrisao_sacaria" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Filial Origem:</div>
        <div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$filial_origem" ?></div>
	</div>



    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quantidade Sacaria:</div>
    	<div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$quant_sacaria" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Motorista:</div>
        <div style="width:190px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
		<?php echo"$motorista" ?></div>
	</div>



    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:155px; margin-top:7px; margin-left:5px; float:left">Desc. Previsto (Qualidade):</div>
    	<div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$desconto_previsto Kg" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">CPF Motorista:</div>
        <div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$motorista_cpf" ?></div>
	</div>



    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:135px; margin-top:7px; margin-left:5px; float:left">N&ordm; Confirma&ccedil;&atilde;o:</div>
    	<div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$confirmacao_negocio" ?></div>
	</div>

    <div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
    	<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div>
        <div style="margin-top:7px; margin-left:0px; float:left"><?php echo"$placa_veiculo" ?></div>
	</div>



    <div style="width:682px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
    	<div style="width:80px; margin-top:7px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
        <div style="width:590px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
		<?php echo"$observacao" ?></div>
	</div>


</div>
</div>

<!-- ========= NOTAS FISCAIS ================================================================================================================== -->
<div style="width:730px; height:25px; border:0px solid #000; font-size:12px; margin-top:10px; float:left">
	<div style="margin-left:35px; width:450px; height:20px; border:0px solid #000; float:left">Notas Fiscais de Sa&iacute;da:</div>
	<div style="width:220px; height:20px; border:0px solid #000; float:right; text-align:right">Total: <?php echo"R$ $soma_nota_fiscal_print" ?></div>
</div>
<!-- ========================================================================================================================================== -->


<!-- ================== INICIO DO RELATORIO ================= -->
<div id="tabela_2" style="width:730px; height:210px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; margin-left:20px">

<div id="centro" style="height:5px; width:725px; border:0px solid #999; margin:auto"></div>
<?php
$busca_nota_fiscal = mysqli_query ($conexao, "SELECT * FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio' ORDER BY data_emissao");
$linha_nota_fiscal = mysqli_num_rows ($busca_nota_fiscal);


if ($linha_nota_fiscal == 0)
{echo "<div id='centro' style='height:30px; width:725px; border:0px solid #999; font-size:12px; color:#000; margin-left:30px'><!-- <i>N&atilde;o existem notas fiscais para este romaneio.</i> --></div>";}
else
{echo "
<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>
	<div style='width:70px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:8px; text-align:center'>Data Emiss&atilde;o</div>
	<div style='width:260px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>Cliente</div>
	<div style='width:80px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>N&ordm; Nota Fiscal</div>
	<div style='width:100px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>Quantidade</div>
	<div style='width:80px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>Valor Unit&aacute;rio</div>
	<div style='width:80px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>Valor Total</div>
</div>
<div id='centro' style='height:5px; width:725px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>";

for ($w=1 ; $w<=$linha_nota_fiscal ; $w++)
{
	$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

// DADOS DO FAVORECIDO =========================
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
	$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
	$codigo_pessoa_2 = $aux_busca_favorecido_2[35];
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_busca_pessoa_2[1];
	$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
		if ($tipo_pessoa_2 == "pf")
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[3];}
		else
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[4];}

	$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));		
	$numero_nf_print_2 = $aux_nota_fiscal[3];
	$valor_unitario_print_2 = number_format($aux_nota_fiscal[5],2,",",".");
	$valor_total_print_2 = number_format($aux_nota_fiscal[8],2,",",".");
	$unidade_print_2 = $aux_nota_fiscal[6];
	$quantidade_print_2 = $aux_nota_fiscal[7];
	$observacao_print_2 = $aux_nota_fiscal[8];

// RELATORIO =========================
	echo "
	<div style='width:70px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:8px; text-align:center'>
	<div style='margin-top:2px; margin-left:0px'>$data_nf_print_2</div></div>
	<div style='width:260px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; overflow:hidden'>
	<div style='margin-top:2px; margin-left:5px; float:left'>$nome_favorecido_2</div></div>
	<div style='width:80px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$numero_nf_print_2</div></div>
	<div style='width:100px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$quantidade_print_2 $unidade_print_2</div></div>
	<div style='width:80px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; text-align:right'>
	<div style='margin-top:2px; margin-right:5px'>$valor_unitario_print_2</div></div>
	<div style='width:80px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; text-align:right'>
	<div style='margin-top:2px; margin-right:5px'>$valor_total_print_2</div></div>
	
	<div id='centro' style='height:2px; width:725px; border:0px solid #999; margin:auto; float:left'></div>";
}
echo "
</div>
";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->














<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->

<div id="centro" style="width:720px; height:75px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center">

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


		
<div id="centro" style="width:750px; height:10px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:750px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:740px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:280px; height:25px; border:0px solid #000; font-size:10px; float:left" align="left">
<?php echo "&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m"; ?>
</div>

<div id="centro" style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:left" align="center">
<?php echo "$usuario_print" ?>
</div>

<div id="centro" style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:left" align="center">
<?php echo "$data_print $hora_cadastro" ?>
</div>

<div id="centro" style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:right" align="right">
<?php echo"FILIAL: $filial_print" ?>
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