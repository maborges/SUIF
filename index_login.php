<?php
include("includes/config.php");
$pagina = "index_login";
$titulo = "Autentica&ccedil;&atilde;o de acesso";
$modulo = "";
$menu = "";

$botao        = '';
$suif_usuario = '';
$suif_senha   = '';
$erro         = 0;
// ========================================================================================================

// ======= RECEBENDO POST =================================================================================

if (!empty($_POST)) {
	$botao = $_POST["botao"];
	$suif_usuario = $_POST["suif_u"];
	$suif_senha = $_POST["suif_s"];

	// ========================================================================================================

	// ======= LOGIN ==========================================================================================
	if ($botao == "login") {
		include("includes/conecta_bd.php");
		$resultado = mysqli_query($conexao, "SELECT username, senha, primeiro_nome, contador_bloqueio, filial, nome_filial FROM usuarios WHERE username='$suif_usuario' AND estado_registro='ATIVO'");
		include("includes/desconecta_bd.php");

		$linhas = mysqli_num_rows($resultado);
		$suif_aux = mysqli_fetch_row($resultado);

		$usuario = $suif_aux[0];
		$senha = $suif_aux[1];
		$nome = $suif_aux[2];
		$contador_bloqueio = $suif_aux[3];
		$filial = $suif_aux[4];
		$nome_filial = $suif_aux[5];


		// ------ USUÁRIO INCORRETO ---------------------------------------------------------------------------
		if ($linhas == 0) {
			$erro = 1;
			$msg_erro = "Usu&aacute;rio incorreto.";
		}

		// ------ USUÁRIO BLOQUEADO ---------------------------------------------------------------------------
		elseif ($contador_bloqueio >= 4) {
			$erro = 2;
			$msg_erro = "Por motivo de seguran&ccedil;a este usu&aacute;rio foi bloqueado para acesso ao sistema.";
		}

		// ------ SENHA INCORRETA -----------------------------------------------------------------------------	
		elseif (md5($suif_senha) != $senha) {
			$cont_aux = $contador_bloqueio;
			$cont = $cont_aux + 1;

			include("includes/conecta_bd.php");
			$atualiza_contador = mysqli_query($conexao, "UPDATE usuarios SET contador_bloqueio='$cont' WHERE username='$usuario'");
			include("includes/desconecta_bd.php");

			$tentativa = 4 - $cont;
			if ($tentativa > 1) {
				$tentativa_palavra = "tentativas";
			} else {
				$tentativa_palavra = "tentativa";
			}

			$erro = 3;

			if ($tentativa <= 0) {
				include("includes/conecta_bd.php");
				$bloqueia_usuario = mysqli_query($conexao, "UPDATE usuarios SET estado_registro='BLOQUEADO' WHERE username='$usuario'");
				include("includes/desconecta_bd.php");

				$msg_erro = "Por motivo de seguran&ccedil;a este usu&aacute;rio foi bloqueado para acesso ao sistema.";
			} else {
				$msg_erro = "A senha est&aacute; incorreta. Voc&ecirc; ainda tem <b>$tentativa</b> $tentativa_palavra";
			}
		}

		// ------ CRIA SESSÃO -----------------------------------------------------------------------------------	
		else {
			// ------ LOGIN POR COOKIE --------------- 
			// 21600 = expira em 6h
			// 43200 = expira em 12h
			// 86400 = expira em 1 dia
			// 604800 = expira em 1 semana
			setcookie("u_suif", $usuario, time() + 43200);
			setcookie("s_suif", $senha, time() + 43200);
			setcookie("n_suif", $nome, time() + 43200);
			setcookie("filial_suif", $filial, time() + 43200);
			setcookie("nome_filial", $nome_filial, time() + 43200);
			// ---------------------------------------

			$cont = 0;

			include("includes/conecta_bd.php");
			$atualiza_contador = mysqli_query($conexao, "UPDATE usuarios SET contador_bloqueio='$cont' WHERE username='$usuario'");
			include("includes/desconecta_bd.php");

			header("Location: $servidor/$diretorio_servidor/index.php");
			exit;
		}
	}
}
// ========================================================================================================


// ========================================================================================================
include("includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body onload="javascript:foco('ok');">


	<!-- ====== TOPO ================================================================================================== -->
	<div class="topo">
		<?php include("includes/topo.php"); ?>
	</div>


	<!-- ====== MENU ================================================================================================== -->
	<div class="menu">
	</div>

	<div class="submenu">
	</div>


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_fixo">

		<div style="width:1060px; height:180px; border:0px solid #000; margin:auto; text-align:center">
			<img src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/logomarca.png" height="170px" />
		</div>


		<div style="width:1060px; height:40px; border:0px solid #000; margin:auto; text-align:center; margin-top:15px">
			<?php
			if ($erro == 1 or $erro == 2 or $erro == 3) {
				echo "
	<div style='height:32px; width:513px; border:2px solid #FFFFFF; margin:auto; border-radius:10px; background-color: rgba(255, 255, 255, 0.7)'>
	<div style='height:auto; width:513px; border:0px solid #000; font-size:13px; color:#F00; float:left; text-align:center; margin-top:8px'>
	$msg_erro
	</div>
	</div>";
			}
			?>
		</div>


		<div style="width:1060px; height:40px; border:0px solid #000; margin:auto; text-align:center; margin-top:15px">
			<div style="height:32px; width:240px; border:1px solid #003466; margin:auto; border-radius:5px; background-color: rgba(255, 255, 255, 0.7)">
				<div style="height:30px; width:70px; border:0px solid #000; font-size:14px; color:#444; float:left; text-align:right">
					<div style="height:8px; width:65px; border:0px solid #000"></div>
					<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/index_login.php" method="post" />
					<input type="hidden" name="botao" value="login" />
					Usu&aacute;rio:
				</div>

				<div style="height:30px; width:160px; border:0px solid #000; margin:auto; float:left">
					<div style="height:6px; width:155px; border:0px solid #000"></div>
					&#160;<input type="text" name="suif_u" maxlength="30" style="font-size:14px; color:#003466; width:145px; 
        background-color:transparent; border:0px solid #FFFFFF" id="ok" />
				</div>



			</div>
		</div>

		<div style="width:1060px; height:40px; border:0px solid #000; margin:auto; text-align:center; margin-top:15px">
			<div style="height:32px; width:240px; border:1px solid #003466; margin:auto; border-radius:5px; background-color: rgba(255, 255, 255, 0.7)">
				<div style="height:30px; width:70px; border:0px solid #000; font-size:14px; color:#444; float:left; text-align:right">
					<div style="height:8px; width:65px; border:0px solid #000"></div>
					Senha:
				</div>

				<div style="height:30px; width:160px; border:0px solid #000; margin:auto; float:left">
					<div style="height:6px; width:155px; border:0px solid #000"></div>
					&#160;<input type="password" name="suif_s" maxlength="30" style="font-size:14px; color:#003466; width:145px; background-color:transparent; border:0px solid #FFFFFF" />
				</div>
			</div>
		</div>


		<div style="width:1060px; height:40px; border:0px solid #000; margin:auto; text-align:center; margin-top:15px">
			<button type='submit' class='botao_3' style='width:120px; height:32px'>Entrar</button>
			</form>
		</div>



	</div>
	<!-- ====== FIM DIV CT =========================================================================================== -->

	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
	</div>
</body>

</html>