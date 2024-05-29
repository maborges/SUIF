<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "acerto_alterar";
$titulo = "Acerto de Quantidade (Quebra / Des&aacute;gio)";
$modulo = "compras";
$menu = "compras";

// ======== DADOS PARA BUSCA =========================================================================================
$filial = $filial_usuario;
$numero_compra = $_POST["numero_compra"];

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];
$num_compra_aux = $_POST["num_compra_aux"];
$movimentacao = $_POST["movimentacao"];
$quantidade_desconto = $_POST["quantidade_desconto"];
$quant_entregar_aux = $_POST["quant_entregar_aux"];

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());
// =================================================================================================================


// ========  BUSCA COMRPA  =========================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' AND movimentacao='COMPRA' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
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
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:550px; width:930px; border:0px solid #000; margin:auto">

<?php
// =================================================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $cod_tipo[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$observacao = $aux_compra[13];

$total_pago_w = $aux_compra[50];
$saldo_pagar_w = $aux_compra[51];
// ========================================================================================================


// CALCULO NOVA QUANTIDADE  ===============================================================================
$nova_quantidade = $quantidade - $quantidade_desconto;
$nova_quantidade_print = number_format($nova_quantidade,2,",",".");

$novo_valor = $nova_quantidade * $preco_unitario;
$novo_valor_print = number_format($novo_valor,2,",",".");

$valor_descontado = $quantidade_desconto * $preco_unitario;
$novo_saldo_pagar = $saldo_pagar_w - $valor_descontado;
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
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


// =================================================================================================================
if (!is_numeric($quantidade_desconto))
{
		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Quantidade inv&aacute;lida.</div>";
}

elseif ($quantidade_desconto > $quant_entregar_aux)
{
		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Quantidade de desconto n&atilde;o pode ser maior do que o saldo atual da compra.</div>";
}

elseif ($quantidade_desconto <= 0)
{
		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Quantidade de desconto n&atilde;o pode ser menor ou igual a 0.</div>";
}

elseif ($quantidade_desconto == $quantidade)
{
		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Quantidade de desconto n&atilde;o pode ser igual a quantidade original.</div>";
}


else
{
		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Deseja realmente alterar a quantidade da compra?</div>
		
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:150px; color:#00F; text-align:left; border:0px solid #000; font-size:11px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:11px; line-height:18px'>
			N&ordm; da compra: $numero_compra</br>
			Produtor: $fornecedor_print</br>
			Produto: $produto_print</br>
			Quantidade original: $quantidade $unidade_print</br>
			<font style='color:#0000FF'>Nova quantidade: $nova_quantidade_print $unidade_print</font></br>
			<font style='color:#0000FF'>Novo valor: R$ $novo_valor_print</font></br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:70px; width:980px; color:#000066; text-align:center; border:0px solid #000; font-size:11px'> 
		<div style='float:left; margin-top:2px; margin-left:80px; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/acerto_alterar_enviar.php' method='post'>
		Motivo da altera&ccedil;&atilde;o: <input type='text' name='motivo_alteracao' maxlength='200' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:500px' />
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<input type='hidden' name='nova_quantidade' value='$nova_quantidade'>
		<input type='hidden' name='quantidade_original' value='$quantidade'>
		<input type='hidden' name='valor_original' value='$valor_total'>
		<input type='hidden' name='novo_valor' value='$novo_valor'>
		<input type='hidden' name='novo_saldo_pagar' value='$novo_saldo_pagar'>
		<input type='hidden' name='quantidade_desconto' value='$quantidade_desconto'>
		</div>
		<div style='float:left; margin-top:2px; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Salvar</button>
		</div>
		</form>
		</div>		
		
		<div id='centro' style='float:left; height:70px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";
}

	
		echo "
		<div id='centro' style='float:left; height:70px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
		</form>
		</div>";

		


?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>