<?php
// definição desta variável é por conta de ela estar nos inputs 
// mas não foi definida e estava dando warning
$filial_busca = $_POST['filial_busca'] ?? '';


if ($linha_romaneio == 0) {
    echo "
        <div class='espacamento' style='height:400px'>
        <div class='espacamento' style='height:30px'></div>";
} else {
    echo "
        <div class='ct_relatorio'>

        <table class='tabela_cabecalho'>
        <tr>
        <td width='60px'>Visualizar</td>
        <td width='60px'>NF</td>
        <td width='60px'>Imprimir</td>
        <td width='100px'>Data</td>
        <td width='360px'>Fornecedor</td>
        <td width='100px'>N&ordm;</td>
        <td width='160px'>Produto</td>
        <td width='120px'>Peso Inicial</td>
        <td width='120px'>Peso Final</td>
        <td width='100px'>Desc. Sacaria</td>
        <td width='140px'>Peso L&iacute;quido</td>
        </tr>
        </table>";
}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x = 1; $x <= $linha_romaneio; $x++) {
    $aux_romaneio = mysqli_fetch_row($busca_romaneio);

    // ====== DADOS DO CADASTRO ============================================================================
    $id_w = $aux_romaneio[0];
    $numero_romaneio_w = $aux_romaneio[1];
    $fornecedor_w = $aux_romaneio[2];
    $data_w = $aux_romaneio[3];
    $produto_w = $aux_romaneio[4];
    $peso_inicial_w = $aux_romaneio[5];
    $peso_final_w = $aux_romaneio[6];
    $desconto_sacaria_w = $aux_romaneio[7];
    $desconto_w = $aux_romaneio[8];
    $quantidade_w = $aux_romaneio[9];
    $unidade_w = $aux_romaneio[10];
    $tipo_sacaria_w = $aux_romaneio[11];
    $movimentacao_w = $aux_romaneio[12];
    $placa_veiculo_w = $aux_romaneio[13];
    $motorista_w = $aux_romaneio[14];
    $observacao_w = $aux_romaneio[15];
    $usuario_cadastro_w = $aux_romaneio[16];
    $hora_cadastro_w = $aux_romaneio[17];
    $data_cadastro_w = $aux_romaneio[18];
    $usuario_alteracao_w = $aux_romaneio[19];
    $hora_alteracao_w = $aux_romaneio[20];
    $data_alteracao_w = $aux_romaneio[21];
    $filial_w = $aux_romaneio[22];
    $estado_registro_w = $aux_romaneio[23];
    $quantidade_prevista_w = $aux_romaneio[24];
    $quantidade_sacaria_w = $aux_romaneio[25];
    $numero_compra_w = $aux_romaneio[26];
    $motorista_cpf_w = $aux_romaneio[27];
    $num_romaneio_manual_w = $aux_romaneio[28];
    $filial_origem_w = $aux_romaneio[29];
    $quant_volume_sacas_w = $aux_romaneio[30];
    $cod_produto_w = $aux_romaneio[31];
    $usuario_exclusao_w = $aux_romaneio[32];
    $hora_exclusao_w = $aux_romaneio[33];
    $data_exclusao_w = $aux_romaneio[34];
    $fornecedor_print_w = $aux_romaneio[35];
    $nome_sacaria_w = $aux_romaneio[36];
    $peso_sacaria_w = $aux_romaneio[37];

    $peso_bruto = ($peso_inicial_w - $peso_final_w);

    $data_print = date('d/m/Y', strtotime($data_w));
    $peso_inicial_print = number_format($peso_inicial_w, 0, ",", ".") . " " . $unidade_w;
    $peso_final_print = number_format($peso_final_w, 0, ",", ".") . " " . $unidade_w;
    $peso_bruto_print = number_format($peso_bruto, 0, ",", ".") . " " . $unidade_w;
    $desconto_sacaria_print = number_format($desconto_sacaria_w, 0, ",", ".") . " " . $unidade_w;
    $desconto_print = number_format($desconto_w, 0, ",", ".") . " " . $unidade_w;
    $quantidade_print = "<b>" . number_format($quantidade_w, 0, ",", ".") . "</b> " . $unidade_w;
    $quantidade_sacaria_print = number_format($quantidade_sacaria_w, 0, ",", ".");


    if (!empty($usuario_cadastro_w)) {
        $dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;
    }

    if (!empty($usuario_alteracao_w)) {
        $dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;
    } else {
        $dados_alteracao_w = '';
    }

    if (!empty($usuario_exclusao_w)) {
        $dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;
    } else {
        $dados_exclusao_w = '';
    }
    // ======================================================================================================


    // ====== RELATORIO =======================================================================================
    if ($estado_registro_w == "EXCLUIDO") {
        echo "<tr class='tabela_4' title=' ID: $id_w &#13; Peso Bruto: $peso_bruto_print &#13; Tipo Sacaria: $nome_sacaria_w &#13; Quant. Sacaria: $quantidade_sacaria_print &#13; Outros Descontos: $desconto_print &#13; Motorista: $motorista_w &#13; Placa Ve&iacute;culo: $placa_veiculo_w &#13; Filial Origem: $filial_origem_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Entrada Ficha Produtor: $numero_compra_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
    } else {
        echo "<tr class='tabela_1' title=' ID: $id_w &#13; Peso Bruto: $peso_bruto_print &#13; Tipo Sacaria: $nome_sacaria_w &#13; Quant. Sacaria: $quantidade_sacaria_print &#13; Outros Descontos: $desconto_print &#13; Motorista: $motorista_w &#13; Placa Ve&iacute;culo: $placa_veiculo_w &#13; Filial Origem: $filial_origem_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Entrada Ficha Produtor: $numero_compra_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
    }


    // ====== BOTAO VISUALIZAR =========================================================================================
    echo "
        <td width='60px' align='center'>
        <div style='height:24px; margin-top:0px; border:0px solid #000'>
        <form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_visualizar.php' method='post' />
        <input type='hidden' name='modulo_mae' value='$modulo'>
        <input type='hidden' name='menu_mae' value='$menu'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='VISUALIZAR'>
        <input type='hidden' name='id_w' value='$id_w'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
        <input type='hidden' name='data_final_busca' value='$data_final_br'>
        <input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
        <input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
        <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
        <input type='hidden' name='filial_busca' value='$filial_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
        </form>
        </div>
        </td>";
    // =================================================================================================================


    // ====== BOTAO NOTA FISCAL ========================================================================================
    echo "
        <td width='60px' align='center'>
        <div style='height:24px; margin-top:0px; border:0px solid #000'>
        <form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_entrada/nota_fiscal.php' method='post' />
        <input type='hidden' name='modulo_mae' value='$modulo'>
        <input type='hidden' name='menu_mae' value='$menu'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='NOTA_FISCAL'>
        <input type='hidden' name='id_w' value='$id_w'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
        <input type='hidden' name='data_final_busca' value='$data_final_br'>
        <input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
        <input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
        <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
        <input type='hidden' name='filial_busca' value='$filial_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/doc_1.png' height='18px' style='margin-top:3px' />
        </form>
        </div>
        </td>";
    // =================================================================================================================


    // ====== BOTAO IMPRIMIR ===========================================================================================
    echo "
        <td width='60px' align='center'>
        <div style='height:24px; margin-top:0px; border:0px solid #000'>
        <form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_impressao.php' method='post' target='_blank' />
        <input type='hidden' name='modulo_mae' value='$modulo'>
        <input type='hidden' name='menu_mae' value='$menu'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='IMPRIMIR'>
        <input type='hidden' name='id_w' value='$id_w'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
        <input type='hidden' name='data_final_busca' value='$data_final_br'>
        <input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
        <input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
        <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
        <input type='hidden' name='filial_busca' value='$filial_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='18px' style='margin-top:3px' />
        </form>
        </div>
        </td>";
    // =================================================================================================================


    // =================================================================================================================
    echo "
        <td width='100px' align='center'>$data_print</td>
        <td width='360px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
        <td width='100px' align='center'>$numero_romaneio_w</td>
        <td width='160px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_w</div></td>
        <td width='120px' align='right'><div style='height:14px; margin-right:10px; overflow:hidden'>$peso_inicial_print</div></td>
        <td width='120px' align='right'><div style='height:14px; margin-right:10px'>$peso_final_print</div></td>
        <td width='100px' align='right'><div style='height:14px; margin-right:10px'>$desconto_sacaria_print</div></td>
        <td width='140px' align='right'><div style='height:14px; margin-right:15px'>$quantidade_print</div></td>";
    // =================================================================================================================

    echo "</tr>";
}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_romaneio == 0 and $botao == "BUSCAR") {
    echo "
        <div class='espacamento' style='height:30px'></div>
        <div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
        <i>Nenhum romaneio encontrado.</i></div>";
}
// =================================================================================================================
