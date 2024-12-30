<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");

$userid = $_POST['userid'];

$sql = "select codigo, fornecedor, situacao_compra, username, data_cadastro, descricao
          from situacao_compra_historico 
         where fornecedor=" . $userid .
    " order by data_cadastro desc";

$result  = mysqli_query($conexao, $sql);
$records = mysqli_num_rows($result);

echo "<div>
      <h5>Histórico de Alterações</h5>
      <hr>";

if ($records > 0) {
    $situacao = '';
    $color_bg = '';
    $msgHistorico =  '';

    while ($row = mysqli_fetch_array($result)) {
        switch ($row['situacao_compra']) {
            case 0:
                $situacao = 'LIBERADA';
                $color_bg = '#7FFF00';
                break;
            case 1:
                $situacao = 'ANALISE';
                $color_bg = '#FFFF00';
                break;
            case 2:
                $situacao = 'BLOQUEADA';
                $color_bg = '#FF0000';
                break;
            default:
                $situacao = 'Indefinido';
                $color_bg = '#000000';
        }

        $msgHistorico = $row['descricao'];

        if ($row['descricao'] == '') {
            $msgHistorico = 'Motivo não informado';
        } 

?>
        <p style='font-size:12px; margin-bottom:1px;'>
            <?php echo '<b>Em: </b>' . date('d/m/Y H:i', strtotime($row['data_cadastro'])) .
                '          <b>Por: </b>' . $row['username'] .
                '          <b>Alterado para: </b>' .  $situacao; ?>
        </p>
        <p style='font-size:12px; margin-top:2px;'><?php echo $msgHistorico; ?></p>

<?php }
} else {
    echo "<p style='font-size:12px; margin-bottom:1px;'>Nenhum histórico de alteração encontrado</p>";
}
echo "<hr></div>";
?>