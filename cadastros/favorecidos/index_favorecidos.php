<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "index_favorecidos";
$titulo = "Cadastro de Favorecidos";	
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido ORDER BY codigo DESC LIMIT 10");
$linha_registro = mysqli_num_rows ($busca_registro);
// ================================================================================================================



// ================================================================================================================
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
<body>


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_favorecidos.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php
	echo"
	<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/cadastro_1_selec_fornecedor.php' method='post' />
	<button type='submit' class='botao_1' style='float:right'>Novo Favorecido</button>
	</form>";
	?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	&Uacute;ltimos cadastros
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->





<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php
if ($linha_registro == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='340px'>Nome</td>
<td width='160px'>CPF/CNPJ</td>
<td width='140px'>Banco</td>
<td width='80px'>Ag&ecirc;ncia</td>
<td width='125px'>N&ordm; da Conta</td>
<td width='95px'>Tipo de Conta</td>
<td width='130px'>Status Cadastro</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_registro[0];
$codigo_pessoa_w = $aux_registro[1];
$banco_w = $aux_registro[2];
$agencia_w = $aux_registro[3];
$conta_w = $aux_registro[4];
$tipo_conta_w = $aux_registro[5];
$observacao_w = $aux_registro[12];
$estado_registro_w = $aux_registro[13];
$nome_w = $aux_registro[14];
$conta_conjunta_w = $aux_registro[15];

if ($conta_conjunta_w == "SIM" and $agencia_w!="")
{$conta_conjunta_print = "SIM";}
elseif ($conta_conjunta_w != "SIM" and $agencia_w!="")
{$conta_conjunta_print = "N&Atilde;O";}
else
{$conta_conjunta_print = "";}

$usuario_cadastro_w = $aux_registro[6];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[8]));
$hora_cadastro_w = $aux_registro[7];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_registro[9];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[11]));
$hora_alteracao_w = $aux_registro[10];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_registro[16];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_registro[17]));
$hora_exclusao_w = $aux_registro[18];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$codigo_pessoa_w'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linha_pessoa = mysqli_num_rows ($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];

if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{$cpf_cnpj = $cpf_pessoa;}
else
{$cpf_cnpj = $cnpj_pessoa;}

if ($linha_pessoa == 0)
{$cidade_uf = "";}
else
{$cidade_uf = "$cidade_pessoa/$estado_pessoa";}
// ======================================================================================================


// ====== BUSCA BANCO ===================================================================================
$busca_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_w'");
$aux_banco = mysqli_fetch_row($busca_banco);

$apelido_banco = $aux_banco[3];
$logomarca_banco = $aux_banco[4];

if (empty($logomarca_banco))
{$logo_banco = "$apelido_banco";}
else
{$logo_banco = "<img src='$servidor/$diretorio_servidor/imagens/$logomarca_banco' style='height:22px' />";}
// ======================================================================================================


// ====== TIPO DE CONTA =================================================================================
if ($tipo_conta_w == "corrente")
{$tipo_conta_print = "Corrente";}
elseif ($tipo_conta_w == "poupanca")
{$tipo_conta_print = "Poupan&ccedil;a";}
elseif ($tipo_conta_w == "salario")
{$tipo_conta_print = "Sal&aacute;rio";}
elseif ($tipo_conta_w == "aplicacao")
{$tipo_conta_print = "Aplica&ccedil;&atilde;o";}
else
{$tipo_conta_print = "";}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "INATIVO")
{echo "<tr class='tabela_4' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
elseif ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_5' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
else
{echo "<tr class='tabela_1' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

echo "
<td width='340px' align='left' style='height:28px'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_pessoa</div></td>
<td width='160px' align='center' style='height:28px'>$cpf_cnpj</td>
<td width='140px' align='center' style='height:28px'>$logo_banco</td>
<td width='80px' align='center' style='height:28px'>$agencia_w</td>
<td width='125px' align='center' style='height:28px'>$conta_w</td>
<td width='95px' align='center' style='height:28px'>$tipo_conta_print</td>
<td width='130px' align='center'>$estado_registro_w</td>";







}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_registro == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro de favorecido encontrado.</i></div>";}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>