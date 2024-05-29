<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
require("../../sankhya/Sankhya.php");

$pagina = "compra_editar_enviar";
$titulo = "Editar Compra";
$modulo = "compras";
$menu = "compras";


// ======= RECEBENDO POST =================================================================================
$numero_compra = $_POST["numero_compra"];
$numero_compra_aux = $_POST["numero_compra_aux"];
$pedidoSankhya = $_POST["pedidoSankhya"];
$pedidoSankhyaConfirmado = $_POST["pedidoSankhyaConfirmado"];
$botao = $_POST["botao"];
$safra = $_POST["safra"];
$cod_tipo = $_POST["cod_tipo"];
$cod_tipo_anterior = $_POST["cod_tipo_anterior"];
$umidade = $_POST["umidade"];
$broca = $_POST["broca"];
$impureza = $_POST["impureza"];
$observacao = $_POST["observacao"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"] ?? '';
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$quantidade = $_POST["quantidade"];
$fornecedor = $_POST["fornecedor"];
$fornecedor_print = $_POST["fornecedor_print"];
$cod_produto = $_POST["cod_produto"];
$produto_print = $_POST["produto_print"];
$unidade_print = $_POST["unidade_print"];

$filial = $filial_usuario;
$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());
// ========================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ===========================================================================================================
include("../../includes/head.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body onload="javascript:foco('ok');">


	<!-- ====== TOPO ================================================================================================== -->
	<div class="topo">
		<?php include("../../includes/topo.php"); ?>
	</div>


	<!-- ====== MENU ================================================================================================== -->
	<div class="menu">
		<?php include("../../includes/menu_compras.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_compras_compras.php"); ?>
	</div>




	<!-- =============================================   C E N T R O   =============================================== -->
	<div id="centro_geral">
		<div id="centro" style="height:410px; width:1080px; border:0px solid #000; margin:auto">

			<?php
			if ($cod_tipo == '') {
				echo "<div id='centro' style='float:left; height:40px; width:1050px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					<b>Tipo</b> &eacute; obrigat&oacute;rio para o cadastro da compra.</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#F00; text-align:center; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>";

				echo "
					<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
					<input type='hidden' name='botao' value='erro_enviar' />
					<input type='hidden' name='numero_compra' value='$numero_compra' />
					<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
					<input type='hidden' name='pagina_mae' value='$pagina_mae' />
					<input type='hidden' name='data_inicial' value='$data_inicial'>
					<input type='hidden' name='data_final' value='$data_final'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='cod_tipo' value='$cod_tipo'>
					<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
					</form>";

				echo "</div>";
			} else {
				$editar = mysqli_query($conexao, "UPDATE compras SET safra='$safra', tipo='$tipo_print', broca='$broca', umidade='$umidade', observacao='$observacao', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', cod_tipo='$cod_tipo', impureza='$impureza' WHERE numero_compra='$numero_compra'");

				// Trata gravação do pedido no SANKHYA
				if (!$pedidoSankhya) {
					$resultPedido = Sankhya::insertPedidoCompra($numero_compra);

					if ($resultPedido['errorCode']) {
						$pedidoSankhya = '';
						$errorSankhya = $resultPedido['errorCode'];
						$msgSankhya   = $resultPedido['errorMessage'];
					} else {
						$pedidoSankhya = $resultPedido['rows']['NUNOTA']['$'];

						// Faz a confirmação do pedido
						$confirmaPedido = Sankhya::confirmaPedidoCompra($pedidoSankhya);
						$pedidoConfirmado = $confirmaPedido['errorCode'] ? 'N' : 'S';

						// Atualiza o número do pedido Sankhya na compra do SUIF
						$sql = "update compras 
								   set id_pedido_sankhya         = $pedidoSankhya, 
								       pedido_confirmado_sankhya = '$pedidoConfirmado' 
							 	 where numero_compra = $numero_compra";
						$resultUpdateCompra = Sankhya::queryExecuteDB($sql);
						$errorSankhya   = 0;
						$msgSankhya = "Pedido Sankhya: $pedidoSankhya criado com sucesso.";
					}
				} else {

					if ($pedidoSankhyaConfirmado === 'N') {
						// Faz a confirmação do pedido
						$confirmaPedido = Sankhya::confirmaPedidoCompra($pedidoSankhya);
						$pedidoConfirmado = $confirmaPedido['errorCode'] ? 'N' : 'S';

						// Atualiza o número do pedido Sankhya na compra do SUIF
						if ($pedidoConfirmado === 'S') { 
							$sql = "update compras 
									set pedido_confirmado_sankhya = '$pedidoConfirmado' 
									where numero_compra = $numero_compra";
							$resultUpdateCompra = Sankhya::queryExecuteDB($sql);
							$errorSankhya   = 0;
						}
					}

					$msgSankhya = "Pedido Sankhya: $pedidoSankhya";
				}

				echo "<div id='centro' style='float:left; height:20px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
					<div id='centro' style='float:left; height:40px; width:1080px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
					Registro editado com sucesso!</div>
					<div id='centro' style='float:left; height:40px; width:1080px; color:#000066; text-align:center; border:0px solid #000; font-size:12px'>
					Pedido SUIF: $numero_compra <br>
					$msgSankhya</div>		
					
					<div id='centro' style='float:left; height:90px; width:447px; color:#00F; text-align:center; border:0px solid #000'>
					</div>

					<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>";

				if ($pagina_mae == "movimentacao_produtor") {
					echo "
					<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
					<input type='hidden' name='data_inicial' value='$data_inicial'>
					<input type='hidden' name='data_final' value='$data_final'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='cod_tipo' value='$cod_tipo'>
					<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
					</form>";
				} else {
					echo "
					<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
					<input type='hidden' name='numero_compra' value='$numero_compra'>
					<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
					<input type='hidden' name='data_inicial' value='$data_inicial'>
					<input type='hidden' name='data_final' value='$data_final'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='cod_tipo' value='$cod_tipo'>
					<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
					</form>";
				}

				echo "</div>";
			}



			?>




		</div>
	</div><!-- FIM DIV CENTRO GERAL -->




	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>