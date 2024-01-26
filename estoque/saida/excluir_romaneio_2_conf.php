<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_romaneio_2_conf';
$titulo = 'Excluir Romaneio';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_inicial = $_POST["data_inicia_buscal"];
$data_final = $_POST["data_final_busca"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$num_romaneio_form = $_POST["num_romaneio_form"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE numero_romaneio='$num_romaneio_form' AND movimentacao='SAIDA' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data_emissao = $aux_romaneio[3];
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
$sit_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],0,",",".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];

$usuario_cadastro = $aux_romaneio[19];
if ($usuario_cadastro == "")
{$dados_cadastro = "";}
else
{
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
}

$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}

$usuario_exclusao = $aux_romaneio[40];
if ($usuario_exclusao == "")
{
$dados_exclusao = "";
$motivo_exclusao = $aux_romaneio[43];
$data_exclusao = "";
$hora_exclusao = "";
$dados_exclusao = "Exclu&iacute;do por:";
}
else
{
$usuario_exclusao = $aux_romaneio[40];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$hora_exclusao = $aux_romaneio[41];
$motivo_exclusao = $aux_romaneio[43];
$dados_exclusao = "exclu&iacute;do por: $usuario_exclusao $data_exclusao $hora_exclusao";
}
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
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$nome_imagem_produto = $aux_bp[28];
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
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

if ($linha_romaneio == 0 or $num_romaneio_form == "")
{$endereco_fornecedor = "";}
else
{$endereco_fornecedor = $cidade_fornecedor . "/" . $estado_fornecedor;}
// ======================================================================================================


// ====== SITUAÇÃO PRINT ===================================================================================
if ($sit_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($sit_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($sit_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== BUSCA ENTRADA =================================================================================
$busca_saida = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$num_romaneio_form' ORDER BY codigo");
$aux_busca_saida = mysqli_fetch_row($busca_saida);
$linha_saida = mysqli_num_rows ($busca_saida);

if ($linha_saida == 0)
{$num_registro_saida = "(Romaneio ainda n&atilde;o vinculado a ficha)";}
else
{$num_registro_saida = $aux_busca_saida[1];}
// ======================================================================================================


// ====== SOMA NOTAS FISCAIS ======================================================================
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$num_romaneio_form'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0],2,",",".");
// ======================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ============================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_emissao);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[23] or $permissao[80] == "S")
{$pode_excluir = "S";}
else
{$pode_excluir = "N";}

if ($permissao[64] == "S" and $linha_saida == 0 and $pode_excluir == "S" and $estado_registro == "ATIVO")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_romaneio == 0 or $num_romaneio_form == "")
{$erro = 1;
$msg = "Romaneio n&atilde;o localizado";}

elseif ($pode_excluir == "N")
{$erro = 2;
$msg = "Aten&ccedil;&atilde;o: O prazo para exclus&atilde;o deste romaneio expirou";}

elseif ($permissao[64] != "S")
{$erro = 3;
$msg = "Usu&aacute;rio n&atilde;o autorizado para exclus&atilde;o deste romaneio";}

elseif ($linha_saida >= 1)
{$erro = 4;
$msg = "Aten&ccedil;&atilde;o: Existe uma entrada vinculada a este romaneio, por isso n&atilde;o pode ser exclu&iacute;do";}

elseif ($estado_registro == "EXCLUIDO")
{$erro = 5;
$msg = "Romaneio j&aacute; $dados_exclusao";}

else
{$erro = 0;
$msg = "Deseja realmente excluir este romaneio?";}
// ==================================================================================================================


// ================================================================================================================
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
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:500px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Excluir Romaneio
    </div>

	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    N&ordm; <?php echo "$num_romaneio_form"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:745px; float:left; text-align:left; color:#FF0000">
	<?php echo "$msg"; ?>
	</div>

	<div class="ct_subtitulo_1" style="width:345px; float:right; text-align:right; font-style:normal">
	<?php
	if ($linha_romaneio == 0 or $num_romaneio_form == "")
	{}
	else
	{echo "$data_print";}
	?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:230px; width:1080px; border:0px solid #0000FF; margin:auto">


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
    <div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
    Cliente:
    </div>
</div>

<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>
    
	<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div>
	</div>
    
	<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div>
	</div>

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

    <div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
        <div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cidade_uf_fornecedor</b>" ?></div>
    </div>
    
    <div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
		<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div>
	</div>

</div>
<!-- ======================================================================================================================= -->


<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
    Produto:
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
    <!-- xxxxxxxxxxxxxx: -->
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
	<!-- xxxxxxxxxxxxxx: -->
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>
-->
<!-- ======================================================================================================================= -->


<!-- =============================================== DADOS DO ROMANEIO ============================================================================= -->
		<div id="centro" style="width:1055px; height:270px; border:0px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

			<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:0px">
			<div class="form_rotulo" style="margin-top:5px">Peso Inicial:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Peso Final:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Peso Bruto:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Desconto Sacaria:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Outros Descontos:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:20px">
			<div class="form_rotulo" style="margin-top:5px">Peso L&iacute;quido:</div></div>

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"<b>$quantidade_print</b> Kg" ?></div></div>


		</div>
<!-- ============================================================================================================== -->





</div>
<!-- ============================================================================================================== -->


<!-- ============================================================================================================= -->
<div class="form" style="height:17px; border:1px solid transparent">
<?php
if ($erro == 0)
{
	echo "
	<div class='form_rotulo' style='width:115px; height:15px; border:1px solid transparent'></div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'>Motivo da exclus&atilde;o:</div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'><!-- xxxxxxxx --></div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'><!-- xxxxxxxx --></div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'><!-- xxxxxxxx --></div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'><!-- xxxxxxxx --></div>
    <div class='form_rotulo' style='width:174px; height:15px; border:1px solid transparent'><!-- xxxxxxxx --></div>";
}
else
{}
?>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">
<?php
if ($erro == 0)
{
    echo "
	<div class='form_rotulo' style='width:115px; height:26px; border:1px solid transparent'></div>

	<div style='width:875px; height:auto; float:left; border:1px solid transparent'>
    <form action='$servidor/$diretorio_servidor/estoque/saida/excluir_romaneio_3_enviar.php' method='post'>
	<input type='hidden' name='num_romaneio_form' value='$num_romaneio_form'>
	<input type='hidden' name='botao' value='EXCLUIR'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>

    <input type='text' class='form_input' name='motivo_exclusao' id='ok' maxlength='150' 
    onkeydown='if (getKey(event) == 13) return false;' style='width:851px; text-align:left' value='$motivo_exclusao' />
    </div>

	<div style='width:174px; height:auto; float:left; border:1px solid transparent'>
    <button type='submit' class='botao_1' style='width:152px'>Excluir</button>
    </form>
    </div>";
}
else
{}
?>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_25"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:50px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	<?php

		echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/saida/excluir_romaneio_1.php' method='post'>
			<input type='hidden' name='num_romaneio_form' value='$num_romaneio_form'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
			</form>
			</div>";

	
	
	
	?>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="width:1050px; height:25px; border:0px solid #999; color:#003466; border-radius:0px; overflow:hidden; margin:auto; background-color:#EEE">
    <div style="margin-left:20px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo"<i>$dados_cadastro</i>" ?></div>
    <div style="margin-left:40px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo"<i>$dados_alteracao</i>" ?></div>
</div>
<!-- ============================================================================================================= -->





</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>