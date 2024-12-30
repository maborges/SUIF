<div style="width:1280px; height:7px"></div>

<div style="width:auto; height:35px">
	<a href=<?="$servidor/$diretorio_servidor/sankhya/index_cadastro.php"?>>
		<button type='submit' style='margin-top:0px; margin-left:15px'
			<?php if ($menu == 'cadastro_sankhya') : ?>
			class='botao_menu_on'><b>Cadastros</b>
		<?php else : ?>
			class='botao_menu_off'>Cadastros
		<?php endif; ?>
		</button>
	</a>

	<a href=<?="$servidor/$diretorio_servidor/sankhya/index_integracao.php"?>>
		<button type='submit' style='margin-top:0px; margin-left:15px'
			<?php if ($menu == 'integracao_sankhya') : ?>
			class='botao_menu_on'><b>Integração</b>
		<?php else : ?>
			class='botao_menu_off'>Integração
		<?php endif; ?>
		</button>
	</a>


</div>