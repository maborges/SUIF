<?php 
  $localComplementoRodape = $complemento_rodape ?? '';
?>
<div style="width:auto; height:20px; border:0px solid #000; margin-top:20px; text-align:center">
<a href="javascript:abrir('<?php echo "$servidor/$diretorio_servidor"; ?>/troca_filial_2.php')">
<?php echo "&copy; $ano_atual_rodape $rodape_slogan - $nome_fantasia $localComplementoRodape"; ?></a>
</div>

