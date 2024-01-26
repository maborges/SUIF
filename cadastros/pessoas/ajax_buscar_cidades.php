<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');

$estado = $_GET['estado'];

$res = mysqli_query ($conexao, "SELECT * FROM cad_cidades WHERE est_id=$estado ORDER BY cid_nome");
$num = mysqli_num_rows($res);
for ($i = 0; $i < $num; $i++) {
	$dados = mysqli_fetch_array($res);
	$arrCidades[$dados['cid_id']] = $dados['cid_nome'];
	//$arrCidades[$dados['cid_id']] = utf8_encode($dados['cid_nome']);
}
?>

<select name="cidade" id="cidade" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px">
<option></option>
  <?php foreach($arrCidades as $value => $nome){
    echo "<option value='{$value}'>{$nome}</option>";
  }
?>
</select>