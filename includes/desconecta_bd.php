<?php
if (isset($conexao) && $conexao->ping()) {
    mysqli_close($conexao);
}

/*
<?php
mysqli_close($conexao);
?>
*/


