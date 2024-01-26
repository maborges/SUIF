<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "entrada_cadastro";
$titulo = "Entrada de Mercadoria";
$modulo = "compras";
$menu = "ficha_produtor";
// ================================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==================================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$numero_romaneio = $_POST["numero_romaneio"];
$filial = $filial_usuario;
// ================================================================================================================


// ====== BUSCA ROMANEIO ==============================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' AND movimentacao='ENTRADA' ORDER BY codigo");
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$fornecedor = $aux_romaneio[2];
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$peso_final = $aux_romaneio[7];
$desconto_sacaria = $aux_romaneio[8];
$desconto = $aux_romaneio[9];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$unidade = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$movimentacao = $aux_romaneio[13];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao = $aux_romaneio[18];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$desconto_previsto_print = number_format($aux_romaneio[36],2,",",".");
$filial_romaneio = $aux_romaneio[25];
$filial_origem = $aux_romaneio[34];
// ================================================================================================================


// ====== BUSCA SACARIA ==============================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ===================================================================================
if ($cod_produto == "2" or $cod_produto == "10")
{$quantidade_real = ($quantidade / 60);}
else
{$quantidade_real = $quantidade;}

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
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA NUMERO DE ROMANEIO =================================================================================
$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$numero_romaneio'");
$aux_nr = mysqli_fetch_row($busca_num_romaneio);
$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);

$achou_num_registro = $aux_nr[1];
$data_registro = date('d/m/Y', strtotime($aux_nr[4]));
// =================================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO" or $situacao_romaneio == "EM_ABERTO")
{$erro = 1;
$msg_erro = "<div style='float:left; margin-top:3px; margin-right:10px'>
* Este romaneio n&atilde;o est&aacute; fechado. N&atilde;o &eacute; poss&iacute;vel dar entrada sem o peso l&iacute;quido da mercadoria.
</div>";}

elseif ($achou_num_romaneio >= 1)
{$erro = 2;
$msg_erro = "<div style='float:left; margin-top:3px; margin-right:10px'>
* J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio. (N&ordm; Registro: $achou_num_registro - Data: $data_registro)
</div>";}

elseif ($linha_romaneio == 0)
{$erro = 3;
$msg_erro = "<div style='float:left; margin-top:3px; margin-right:10px'>
* Romaneio n&atilde;o localizado.</div>";}

// Bloqueio qualidade só para Jaguaré
elseif ($classificacao != "SIM" and $filial_romaneio == "LINHARES" and $filial_origem == "JAGUARE")
{$erro = 4;
$msg_erro = "<div style='float:left; margin-top:3px; margin-right:10px'>
* Este romaneio ainda n&atilde;o foi aprovado pela classifica&ccedil;&atilde;o de qualidade.</div>";}

else
{$erro = 0;
$msg_erro = "";}
// =================================================================================================================


// =================================================================================================================
include ("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
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
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:950px; border:0px solid #000; margin:auto">

<div style="width:950px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:950px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:140px">
    Entrada de Romaneio
    </div>
</div>

<div style="width:950px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:950px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:140px">
    <?php echo "$msg_erro";
	
	if ($erro == 3)
	{}
	else
	{echo "
	<div style='float:left'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' title='Ficha do Produtor' />
	</form></div>";}
	?>
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ====================================================================================== -->
<div style="width:140px; height:235px; border:0px solid #000; float:left">
<!-- IMAGEM --></div>

<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero do Romaneio:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Produto:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:350px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:345px; height:5px; border:0px solid #000"></div>Fornecedor:</div>




<!-- =========================================  CODIGO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<?php
if ($linha_romaneio == 0)
{echo "<input type='text' name='numero_romaneio_aux' maxlength='30' onkeydown='if (getKey(event) == 13) return false;' style='color:#FF0000; font-size:11px; width:145px' value='$numero_romaneio' disabled='disabled' />";}
else
{echo "<input type='text' name='numero_romaneio_aux' maxlength='30' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:145px' value='$numero_romaneio' disabled='disabled' />";}
?>


<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_enviar.php" method="post" />

<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />
<input type="hidden" name="numero_compra" value="<?php echo "$numero_compra"; ?>" />
<input type="hidden" name="quantidade" value="<?php echo "$quantidade"; ?>" />
<input type="hidden" name="produto" value="<?php echo "$produto"; ?>" />
<input type="hidden" name="cod_produto" value="<?php echo "$cod_produto"; ?>" />
<input type="hidden" name="fornecedor" value="<?php echo "$fornecedor"; ?>" />

</div>


<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="produto_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px" value="<?php echo "$produto_print_2"; ?>" disabled="disabled" />
<!-- ========================================================================================================== -->
<!-- ========================================================================================================== -->
</div>


<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:350px; border:0px solid #000">
<input type="text" name="fornecedor_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:345px; font-size:12px" value="<?php echo "$fornecedor_print"; ?>" disabled="disabled" />
<!-- ========================================================================================================== -->
<!-- ========================================================================================================== -->
</div>




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Peso L&iacute;quido (Kg):</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Tipo Sacaria:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Placa do Ve&iacute;culo:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Motorista:</div>

<!-- =========================================  PESO LIQUIDO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="peso_liquido" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$quantidade_print"; ?>" disabled="disabled" /></div>

<!-- =========================================  TIPO SACARIA ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="tipo_sacaria_2" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" value="<?php echo "$tipo_sacaria"; ?>" disabled="disabled" />
</div>

<!-- =========================================  PLACA DO VEICULO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="placa_veiculo" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" value="<?php echo "$placa_veiculo"; ?>" disabled="disabled" />
</div>

<!-- ========================================= MOTORISTA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<input type="text" name="motorista" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" value="<?php echo "$motorista"; ?>" disabled="disabled" />
</div>





<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
<?php
if ($unidade_print == "SC")
{echo"Quantidade (Sacas):";}
else
{echo"Quantidade (Kg):";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
<?php
if ($unidade_print == "SC")
{echo"Desconto (Sacas):";}
else
{echo"Desconto (Kg):";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Tipo</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div><!-- Tipo: --></div>

<!-- =========================================  QUANTIDADE  ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="quantidade" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$quantidade_real_print"; ?>" disabled="disabled" /></div>

<!-- =========================================  DESCONTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="desconto" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="0" /></div>

<!-- ========================================= TIPO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
{
$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	

	if ($aux_tipo_produto[0] == $cod_tipo)
	{echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
	else
	{echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
}
?>
</select>
</div>


<!-- ========================================= NUMERO ROMANEIO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<!-- <input type="text" name="numero_romaneio" maxlength="15" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" /> -->
</div>



<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<!-- =========================================  OBSERVAÇÃO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:689px" /></div>

<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000; color:#228B22">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Desconto previsto a ser aplicado (Qualidade): <?php echo "<b>$desconto_previsto_print Kg</b>"; ?></div>

<!-- =========================================  Desconto Previsto ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
</div>




<!-- =========================================  MENSAGEM ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000; font-size:8px; color:#CCC6C6">
</div>



<!-- =============================================================================================== -->
<div id="espaco_2" style="width:925px"></div>




<div id="tabela_2" style="width:930px; text-align:center; border:0px solid #000">
<?php
if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4)
{}

else
{echo "<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Confirmar</button></form>";}
?>

<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_seleciona.php">
<button type='submit' class='botao_2' style='margin-left:50px; width:120px'>Cancelar</button></a>
</div>










</div>
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>