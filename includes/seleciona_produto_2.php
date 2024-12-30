<?php
// ======= RECEBENDO POST ===================================================================================
$numero_romaneio = $_POST["numero_romaneio"];
$numero_compra = $_POST["numero_compra"];
$numero_venda = $_POST["numero_venda"];
$botao = $_POST["botao"];

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$pagina_destino = "cadastro_2_selec_fornecedor";
// ==========================================================================================================


// ====== MOSTRA PREÇO ======================================================================================
if ($filial_config[5] == "S" and $mostra_preco != "NAO")
{$mostra_preco = "SIM";}
else
{$mostra_preco = "NAO";}
// ==========================================================================================================



// ====== BUSCA PREÇO CAFE ===================================================================================
$busca_cafe = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='2'");
$aux_cafe = mysqli_fetch_row($busca_cafe);

$cod_cafe = $aux_cafe[0];
$cafe_print = $aux_cafe[22];
$preco_max_cafe = $aux_cafe[21];
if ($mostra_preco == "SIM" and $modulo == "compras")
{$preco_max_cafe_print = "R$ " . number_format($aux_cafe[21],2,",",".");}
else
{$preco_max_cafe_print = "";}
// ======================================================================================================


// ====== BUSCA PREÇO PIMENTA ===================================================================================
$busca_pimenta = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='3'");
$aux_pimenta = mysqli_fetch_row($busca_pimenta);

$cod_pimenta = $aux_pimenta[0];
$pimenta_print = $aux_pimenta[22];
$preco_max_pimenta = $aux_pimenta[21];
if ($mostra_preco == "SIM" and $modulo == "compras")
{$preco_max_pimenta_print = "R$ " . number_format($aux_pimenta[21],2,",",".");}
else
{$preco_max_pimenta_print = "";}
// ======================================================================================================


// ====== BUSCA PREÇO CACAU ===================================================================================
$busca_cacau = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='4'");
$aux_cacau = mysqli_fetch_row($busca_cacau);

$cod_cacau = $aux_cacau[0];
$cacau_print = $aux_cacau[22];
$preco_max_cacau = $aux_cacau[21];
if ($mostra_preco == "SIM" and $modulo == "compras")
{$preco_max_cacau_print = "R$ " . number_format($aux_cacau[21],2,",",".");}
else
{$preco_max_cacau_print = "";}
// ======================================================================================================



// ======================================================================================================
echo "<div style='height:80px; border:0px solid #0000FF; margin:auto'>";




// ====== CAFE ==========================================================================================
if ($filial_config[35] == "S")
{echo "
<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
<form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cafe' />
<input type='hidden' name='cod_produto_form' value='$cod_cafe' />
<input type='hidden' name='cod_seleciona_produto' value='$cod_cafe' />
<input type='hidden' name='numero_compra' value='$numero_compra'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
<input type='hidden' name='botao' value='$botao'>
<input type='hidden' name='pagina_mae' value='$pagina_mae'>
<input type='hidden' name='pagina_filha' value='$pagina_filha'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
<input type='hidden' name='data_final_busca' value='$data_final_busca'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
</form>
<a href='javascript:enviar_cafe()'>
<div class='produto_2' style='height:60px; width:300px; float:left; margin-left:40px; margin-top:0px'>
	<div style='height:45px; width:105px; float:left; margin-left:5px; margin-top:8px'>
	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' class='preto_branco' style='width:100px'>
	</div>
	<div style='height:25px; width:185px; float:left; margin-left:0px; margin-top:20px; font-size:16px; text-align:center'>
	<b>$cafe_print</b>
	</div>
</div>
</a>
";}
// ======================================================================================================


// ====== PIMENTA =======================================================================================
if ($filial_config[36] == "S")
{echo "
<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
<form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_pimenta' />
<input type='hidden' name='cod_produto_form' value='$cod_pimenta' />
<input type='hidden' name='cod_seleciona_produto' value='$cod_pimenta' />
<input type='hidden' name='numero_compra' value='$numero_compra'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
<input type='hidden' name='botao' value='$botao'>
<input type='hidden' name='pagina_mae' value='$pagina_mae'>
<input type='hidden' name='pagina_filha' value='$pagina_filha'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
<input type='hidden' name='data_final_busca' value='$data_final_busca'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
</form>
<a href='javascript:enviar_pimenta()'>
<div class='produto_2' style='height:60px; width:300px; float:left; margin-left:40px; margin-top:0px'>
	<div style='height:45px; width:105px; float:left; margin-left:5px; margin-top:8px'>
	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' class='preto_branco' style='width:100px'>
	</div>
	<div style='height:25px; width:185px; float:left; margin-left:0px; margin-top:20px; font-size:16px; text-align:center'>
	<b>$pimenta_print</b>
	</div>
</div>
</a>
";}
// ======================================================================================================


// ====== CACAU =========================================================================================
if ($filial_config[37] == "S")
{echo "
<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
<form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cacau' />
<input type='hidden' name='cod_produto_form' value='$cod_cacau' />
<input type='hidden' name='cod_seleciona_produto' value='$cod_cacau' />
<input type='hidden' name='numero_compra' value='$numero_compra'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
<input type='hidden' name='botao' value='$botao'>
<input type='hidden' name='pagina_mae' value='$pagina_mae'>
<input type='hidden' name='pagina_filha' value='$pagina_filha'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
<input type='hidden' name='data_final_busca' value='$data_final_busca'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
</form>
<a href='javascript:enviar_cacau()'>
<div class='produto_2' style='height:60px; width:300px; float:left; margin-left:40px; margin-top:0px'>
	<div style='height:45px; width:105px; float:left; margin-left:5px; margin-top:8px'>
	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' class='preto_branco' style='width:100px'>
	</div>
	<div style='height:25px; width:185px; float:left; margin-left:0px; margin-top:20px; font-size:16px; text-align:center'>
	<b>$cacau_print</b>
	</div>
</div>
</a>
";}
// ======================================================================================================



// ====== OUTROS PRODUTOS ===============================================================================
if ($filial_config[6] == "S")
{echo "
<div style='margin-top:0px; margin-left:40px; float:left'>
<form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produtos' />
<input type='hidden' name='numero_compra' value='$numero_compra'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
<input type='hidden' name='botao' value='$botao'>
<input type='hidden' name='pagina_mae' value='$pagina_mae'>
<input type='hidden' name='pagina_filha' value='$pagina_filha'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
<input type='hidden' name='data_final_busca' value='$data_final_busca'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>

<select name='cod_seleciona_produto' class='produto_2' onchange='document.produtos.submit()' 
onkeydown='if (getKey(event) == 13) return false;' style='width:300px; height:60px; font-size:16px; text-align:center' />
<option>(Outros Produtos)</option>";

$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

for ($j=1 ; $j<=$linhas_produto_list ; $j++)
{
	$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
	if ($aux_produto_list[0] == $cod_produto_form)
	{
	echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[22]</option>";
	}
	else
	{
	echo "<option value='$aux_produto_list[0]'>$aux_produto_list[22]</option>";
	}
}

echo "
</select>
</form>
</div>";
}
// ======================================================================================================





echo "</div>";
// ======================================================================================================
?>