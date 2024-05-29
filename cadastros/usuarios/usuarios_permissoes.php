<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "usuarios_permissoes";
$titulo = "Permiss&otilde;es de Usu&aacute;rios";	
$modulo = "cadastros";
$menu = "cadastro_usuarios";
// ================================================================================================================


// ====== RECEBENDO POST ==========================================================================================
$username = $_POST["username"] ?? '';
$botao = $_POST["botao"] ?? '';
$cod_usuario = $_POST["cod_usuario"] ?? '';

$filial = $filial_usuario;

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());
// ================================================================================================================


// ====== BUSCA USUÁRIO ===========================================================================================
$busca_usuario = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE username='$cod_usuario'");
$aux_usuario = mysqli_fetch_row($busca_usuario);
$linhas_usuario = mysqli_num_rows ($busca_usuario);

$nome_usuário = $aux_usuario[3];
$filial_usuário = $aux_usuario[12];
// ================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_usuario_x = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
$linha_usuario_x = mysqli_num_rows ($busca_usuario_x);
// ==================================================================================================================


// ====== EDITA CADASTRO ==========================================================================================
if ($botao == "ATUALIZA" and $permissao[56] == "S")
{
$cod_usuario = $_POST["cod_usuario"];

if ($_POST["aux_1"] == "S") {$aux_1 = "S";} else {$aux_1 = "N";} // [1] modulo_cadastros
if ($_POST["aux_2"] == "S") {$aux_2 = "S";} else {$aux_2 = "N";} // [2] modulo_compras
if ($_POST["aux_3"] == "S") {$aux_3 = "S";} else {$aux_3 = "N";} // [3] modulo_estoque
if ($_POST["aux_4"] == "S") {$aux_4 = "S";} else {$aux_4 = "N";} // [4] modulo_diversos
if ($_POST["aux_5"] == "S") {$aux_5 = "S";} else {$aux_5 = "N";} // [5] cadastro_pessoa_fisica
if ($_POST["aux_6"] == "S") {$aux_6 = "S";} else {$aux_6 = "N";} // [6] cadastro_favorecidos
if ($_POST["aux_7"] == "S") {$aux_7 = "S";} else {$aux_7 = "N";} // [7] cadastro_produtos
if ($_POST["aux_8"] == "S") {$aux_8 = "S";} else {$aux_8 = "N";} // [8] cadastro_usuarios
if ($_POST["aux_9"] == "S") {$aux_9 = "S";} else {$aux_9 = "N";} // [9] produtos
if ($_POST["aux_10"] == "S") {$aux_10 = "S";} else {$aux_10 = "N";} // [10] ficha_produtor
if ($_POST["aux_11"] == "S") {$aux_11 = "S";} else {$aux_11 = "N";} // [11] menu_contrato_futuro
if ($_POST["aux_12"] == "S") {$aux_12 = "S";} else {$aux_12 = "N";} // [12] estoque_movimentacao
if ($_POST["aux_13"] == "S") {$aux_13 = "S";} else {$aux_13 = "N";} // [13] armazens
if ($_POST["aux_14"] == "S") {$aux_14 = "S";} else {$aux_14 = "N";} // [14] exportacao
if ($_POST["aux_15"] == "S") {$aux_15 = "S";} else {$aux_15 = "N";} // [15] impressoes
if ($_POST["aux_16"] == "S") {$aux_16 = "S";} else {$aux_16 = "N";} // [16] romaneio_editar
if ($_POST["aux_17"] == "S") {$aux_17 = "S";} else {$aux_17 = "N";} // [17] romaneio_novo
if ($_POST["aux_18"] == "S") {$aux_18 = "S";} else {$aux_18 = "N";} // [18] estoque_mov_entrada
if ($_POST["aux_19"] == "S") {$aux_19 = "S";} else {$aux_19 = "N";} // [19] estoque_mov_entrada_r
if ($_POST["aux_20"] == "S") {$aux_20 = "S";} else {$aux_20 = "N";} // [20] estoque_mov_saida
if ($_POST["aux_21"] == "S") {$aux_21 = "S";} else {$aux_21 = "N";} // [21] estoque_mov_saida_r
if ($_POST["aux_22"] == "S") {$aux_22 = "S";} else {$aux_22 = "N";} // [22] modulo_financeiro
if ($_POST["aux_23"] == "S") {$aux_23 = "S";} else {$aux_23 = "N";} // [23] financeiro_contas_pagar
if ($_POST["aux_24"] == "S") {$aux_24 = "S";} else {$aux_24 = "N";} // [24] financeiro_contas_receber
if ($_POST["aux_25"] == "S") {$aux_25 = "S";} else {$aux_25 = "N";} // [25] financeiro_caixa
if ($_POST["aux_26"] == "S") {$aux_26 = "S";} else {$aux_26 = "N";} // [26] financeiro_banco
if ($_POST["aux_27"] == "S") {$aux_27 = "S";} else {$aux_27 = "N";} // [27] financeiro_gerencial
if ($_POST["aux_28"] == "S") {$aux_28 = "S";} else {$aux_28 = "N";} // [28] financeiro_pgtos_relatorios
if ($_POST["aux_29"] == "S") {$aux_29 = "S";} else {$aux_29 = "N";} // [29] financeiro_aux_2
if ($_POST["aux_30"] == "S") {$aux_30 = "S";} else {$aux_30 = "N";} // [30] excluir_compra
if ($_POST["aux_31"] == "S") {$aux_31 = "S";} else {$aux_31 = "N";} // [31] ficha_produtor_entrada
if ($_POST["aux_32"] == "S") {$aux_32 = "S";} else {$aux_32 = "N";} // [32] ficha_produtor_entrada_2
if ($_POST["aux_33"] == "S") {$aux_33 = "S";} else {$aux_33 = "N";} // [33] ficha_produtor_transferencia
if ($_POST["aux_34"] == "S") {$aux_34 = "S";} else {$aux_34 = "N";} // [34] ficha_produtor_movimentacao
if ($_POST["aux_35"] == "S") {$aux_35 = "S";} else {$aux_35 = "N";} // [35] favorecido_editar
if ($_POST["aux_36"] == "S") {$aux_36 = "S";} else {$aux_36 = "N";} // [36]	favorecido_excluir
if ($_POST["aux_37"] == "S") {$aux_37 = "S";} else {$aux_37 = "N";} // [37] compra
if ($_POST["aux_38"] == "S") {$aux_38 = "S";} else {$aux_38 = "N";} // [38] compra_relatorio
if ($_POST["aux_39"] == "S") {$aux_39 = "S";} else {$aux_39 = "N";} // [39] registro_excluir
if ($_POST["aux_40"] == "S") {$aux_40 = "S";} else {$aux_40 = "N";} // [40] preco_compra_maximo
if ($_POST["aux_41"] == "S") {$aux_41 = "S";} else {$aux_41 = "N";} // [41] permite_compra_maximo
if ($_POST["aux_42"] == "S") {$aux_42 = "S";} else {$aux_42 = "N";} // [42] relatorio_talao
if ($_POST["aux_43"] == "S") {$aux_43 = "S";} else {$aux_43 = "N";} // [43] baixar_talao
if ($_POST["aux_44"] == "S") {$aux_44 = "S";} else {$aux_44 = "N";} // [44] imprime_talao
if ($_POST["aux_45"] == "S") {$aux_45 = "S";} else {$aux_45 = "N";} // [45] compra_talao
if ($_POST["aux_46"] == "S") {$aux_46 = "S";} else {$aux_46 = "N";} // [46] contrato_futuro_relatorio
if ($_POST["aux_47"] == "S") {$aux_47 = "S";} else {$aux_47 = "N";} // [47] contrato_futuro_emitir
if ($_POST["aux_48"] == "S") {$aux_48 = "S";} else {$aux_48 = "N";} // [48] contrato_futuro_baixar
if ($_POST["aux_49"] == "S") {$aux_49 = "S";} else {$aux_49 = "N";} // [49] contrato_futuro_estornar
if ($_POST["aux_50"] == "S") {$aux_50 = "S";} else {$aux_50 = "N";} // [50] contrato_futuro_excluir
if ($_POST["aux_51"] == "S") {$aux_51 = "S";} else {$aux_51 = "N";} // [51] acerto_quantidade_compra
if ($_POST["aux_52"] == "S") {$aux_52 = "S";} else {$aux_52 = "N";} // [52] index_gerencial
if ($_POST["aux_53"] == "S") {$aux_53 = "S";} else {$aux_53 = "N";} // [53] registro_excluir_trans
if ($_POST["aux_54"] == "S") {$aux_54 = "S";} else {$aux_54 = "N";} // [54] romaneio_pendente
if ($_POST["aux_55"] == "S") {$aux_55 = "S";} else {$aux_55 = "N";} // [55] usuarios_novo_cadastro
if ($_POST["aux_56"] == "S") {$aux_56 = "S";} else {$aux_56 = "N";} // [56] usuarios_permissoes
if ($_POST["aux_57"] == "S") {$aux_57 = "S";} else {$aux_57 = "N";} // [57] usuarios_desbloqueio
if ($_POST["aux_58"] == "S") {$aux_58 = "S";} else {$aux_58 = "N";} // [58] usuarios_editar
if ($_POST["aux_59"] == "S") {$aux_59 = "S";} else {$aux_59 = "N";} // [59] usuarios_relatorio
if ($_POST["aux_60"] == "S") {$aux_60 = "S";} else {$aux_60 = "N";} // [60] classificacao_qualidade
if ($_POST["aux_61"] == "S") {$aux_61 = "S";} else {$aux_61 = "N";} // [61] atualiza_saldo_relatorio
if ($_POST["aux_62"] == "S") {$aux_62 = "S";} else {$aux_62 = "N";} // [62] ficha_produtor_entrada_3
if ($_POST["aux_63"] == "S") {$aux_63 = "S";} else {$aux_63 = "N";} // [63] ficha_produtor_saida_3
if ($_POST["aux_64"] == "S") {$aux_64 = "S";} else {$aux_64 = "N";} // [64] romaneio_excluir
if ($_POST["aux_65"] == "S") {$aux_65 = "S";} else {$aux_65 = "N";} // [65] editar_compra
if ($_POST["aux_66"] == "S") {$aux_66 = "S";} else {$aux_66 = "N";} // [66] cadastro_pessoa_juridica
if ($_POST["aux_67"] == "S") {$aux_67 = "S";} else {$aux_67 = "N";} // [67] menu_pessoas
if ($_POST["aux_68"] == "S") {$aux_68 = "S";} else {$aux_68 = "N";} // [68] menu_favorecidos
if ($_POST["aux_69"] == "S") {$aux_69 = "S";} else {$aux_69 = "N";} // [69] editar_pf
if ($_POST["aux_70"] == "S") {$aux_70 = "S";} else {$aux_70 = "N";} // [70] editar_pj (pessoas_excluir)
if ($_POST["aux_71"] == "S") {$aux_71 = "S";} else {$aux_71 = "N";} // [71] pessoas_pesquisar
if ($_POST["aux_72"] == "S") {$aux_72 = "S";} else {$aux_72 = "N";} // [72] pessoas_relatorio (impressao)
if ($_POST["aux_73"] == "S") {$aux_73 = "S";} else {$aux_73 = "N";} // [73] pessoas_classificacao
if ($_POST["aux_74"] == "S") {$aux_74 = "S";} else {$aux_74 = "N";} // [74] registro_excluir_antigo
if ($_POST["aux_75"] == "S") {$aux_75 = "S";} else {$aux_75 = "N";} // [75] favorecido_pesquisar
if ($_POST["aux_76"] == "S") {$aux_76 = "S";} else {$aux_76 = "N";} // [76] menu_usuarios
if ($_POST["aux_77"] == "S") {$aux_77 = "S";} else {$aux_77 = "N";} // [77] romaneio_finalizar
if ($_POST["aux_78"] == "S") {$aux_78 = "S";} else {$aux_78 = "N";} // [78] romaneio_visualizar
if ($_POST["aux_79"] == "S") {$aux_79 = "S";} else {$aux_79 = "N";} // [79] romaneio_imprimir
if ($_POST["aux_80"] == "S") {$aux_80 = "S";} else {$aux_80 = "N";} // [80] romaneio_excluir_antigo
if ($_POST["aux_81"] == "S") {$aux_81 = "S";} else {$aux_81 = "N";} // [81] romaneio_editar_antigo
if ($_POST["aux_82"] == "S") {$aux_82 = "S";} else {$aux_82 = "N";} // [82] romaneio_nf_entrada
if ($_POST["aux_83"] == "S") {$aux_83 = "S";} else {$aux_83 = "N";} // [83] romaneio_nf_saida
if ($_POST["aux_84"] == "S") {$aux_84 = "S";} else {$aux_84 = "N";} // [84] compra_nova
if ($_POST["aux_85"] == "S") {$aux_85 = "S";} else {$aux_85 = "N";} // [85] menu_contrato_tratado
if ($_POST["aux_86"] == "S") {$aux_86 = "S";} else {$aux_86 = "N";} // [86] contrato_tratado_relatorio
if ($_POST["aux_87"] == "S") {$aux_87 = "S";} else {$aux_87 = "N";} // [87] contrato_tratado_emitir
if ($_POST["aux_88"] == "S") {$aux_88 = "S";} else {$aux_88 = "N";} // [88] contrato_tratado_baixar
if ($_POST["aux_89"] == "S") {$aux_89 = "S";} else {$aux_89 = "N";} // [89] contrato_tratado_estornar
if ($_POST["aux_90"] == "S") {$aux_90 = "S";} else {$aux_90 = "N";} // [90] contrato_tratado_excluir
if ($_POST["aux_91"] == "S") {$aux_91 = "S";} else {$aux_91 = "N";} // [91] lote_cadastro
if ($_POST["aux_92"] == "S") {$aux_92 = "S";} else {$aux_92 = "N";} // [92] lote_editar
if ($_POST["aux_93"] == "S") {$aux_93 = "S";} else {$aux_93 = "N";} // [93] lote_excluir
if ($_POST["aux_94"] == "S") {$aux_94 = "S";} else {$aux_94 = "N";} // [94] armazem_cadastro
if ($_POST["aux_95"] == "S") {$aux_95 = "S";} else {$aux_95 = "N";} // [95] armazem_editar
if ($_POST["aux_96"] == "S") {$aux_96 = "S";} else {$aux_96 = "N";} // [96] armazem_excluir
if ($_POST["aux_97"] == "S") {$aux_97 = "S";} else {$aux_97 = "N";} // [97] sacaria_cadastro
if ($_POST["aux_98"] == "S") {$aux_98 = "S";} else {$aux_98 = "N";} // [98] sacaria_editar
if ($_POST["aux_99"] == "S") {$aux_99 = "S";} else {$aux_99 = "N";} // [99] sacaria_excluir
if ($_POST["aux_100"] == "S") {$aux_100 = "S";} else {$aux_100 = "N";} // [100] servico_producao_cadastro
if ($_POST["aux_101"] == "S") {$aux_101 = "S";} else {$aux_101 = "N";} // [101] servico_producao_editar
if ($_POST["aux_102"] == "S") {$aux_102 = "S";} else {$aux_102 = "N";} // [102] servico_producao_excluir
if ($_POST["aux_103"] == "S") {$aux_103 = "S";} else {$aux_103 = "N";} // [103] tipo_producao_cadastro
if ($_POST["aux_104"] == "S") {$aux_104 = "S";} else {$aux_104 = "N";} // [104] tipo_producao_editar
if ($_POST["aux_105"] == "S") {$aux_105 = "S";} else {$aux_105 = "N";} // [105] tipo_producao_excluir
if ($_POST["aux_106"] == "S") {$aux_106 = "S";} else {$aux_106 = "N";} // [106] ordem_producao_cadastro
if ($_POST["aux_107"] == "S") {$aux_107 = "S";} else {$aux_107 = "N";} // [107] ordem_producao_editar
if ($_POST["aux_108"] == "S") {$aux_108 = "S";} else {$aux_108 = "N";} // [108] ordem_producao_editar_antigo
if ($_POST["aux_109"] == "S") {$aux_109 = "S";} else {$aux_109 = "N";} // [109] ordem_producao_excluir
if ($_POST["aux_110"] == "S") {$aux_110 = "S";} else {$aux_110 = "N";} // [110] ordem_producao_excluir_antigo
if ($_POST["aux_111"] == "S") {$aux_111 = "S";} else {$aux_111 = "N";} // [111] ordem_producao_cadastros
if ($_POST["aux_112"] == "S") {$aux_112 = "S";} else {$aux_112 = "N";} // [112] ordem_producao_mov_int
if ($_POST["aux_113"] == "S") {$aux_113 = "S";} else {$aux_113 = "N";} // [113] romaneio_editar_peso
if ($_POST["aux_114"] == "S") {$aux_114 = "S";} else {$aux_114 = "N";} // [114] vendas_modulo
if ($_POST["aux_115"] == "S") {$aux_115 = "S";} else {$aux_115 = "N";} // [115] vendas_menu
if ($_POST["aux_116"] == "S") {$aux_116 = "S";} else {$aux_116 = "N";} // [116] vendas_cadastrar
if ($_POST["aux_117"] == "S") {$aux_117 = "S";} else {$aux_117 = "N";} // [117] vendas_editar
if ($_POST["aux_118"] == "S") {$aux_118 = "S";} else {$aux_118 = "N";} // [118] vendas_editar_tudo
if ($_POST["aux_119"] == "S") {$aux_119 = "S";} else {$aux_119 = "N";} // [119] vendas_editar_antigo
if ($_POST["aux_120"] == "S") {$aux_120 = "S";} else {$aux_120 = "N";} // [120] vendas_excluir
if ($_POST["aux_121"] == "S") {$aux_121 = "S";} else {$aux_121 = "N";} // [121] vendas_excluir_antigo
if ($_POST["aux_122"] == "S") {$aux_122 = "S";} else {$aux_122 = "N";} // [122] vendas_imprimir
if ($_POST["aux_123"] == "S") {$aux_123 = "S";} else {$aux_123 = "N";} // [123] vendas_visualizar
if ($_POST["aux_124"] == "S") {$aux_124 = "S";} else {$aux_124 = "N";} // [124] vendas_nota_fiscal
if ($_POST["aux_125"] == "S") {$aux_125 = "S";} else {$aux_125 = "N";} // [125] vendas_recebimento
if ($_POST["aux_126"] == "S") {$aux_126 = "S";} else {$aux_126 = "N";} // [126] vendas_relatorios
if ($_POST["aux_127"] == "S") {$aux_127 = "S";} else {$aux_127 = "N";} // [127] vendas_entrega
if ($_POST["aux_128"] == "S") {$aux_128 = "S";} else {$aux_128 = "N";} // [128] usuarios_excluir
if ($_POST["aux_129"] == "S") {$aux_129 = "S";} else {$aux_129 = "N";} // [129] usuarios_resetar_senha
if ($_POST["aux_130"] == "S") {$aux_130 = "S";} else {$aux_130 = "N";} // [130] config_menu
if ($_POST["aux_131"] == "S") {$aux_131 = "S";} else {$aux_131 = "N";} // [131] config_1 (Configurações Gerais)
if ($_POST["aux_132"] == "S") {$aux_132 = "S";} else {$aux_132 = "N";} // [132] config_2 (Cadastro forma de entrega)
if ($_POST["aux_133"] == "S") {$aux_133 = "S";} else {$aux_133 = "N";} // [133] config_3 (Cadastro unidade de medida)
if ($_POST["aux_134"] == "S") {$aux_134 = "S";} else {$aux_134 = "N";} // [134] config_4
if ($_POST["aux_135"] == "S") {$aux_135 = "S";} else {$aux_135 = "N";} // [135] config_5
if ($_POST["aux_136"] == "S") {$aux_136 = "S";} else {$aux_136 = "N";} // [136] config_6
if ($_POST["aux_137"] == "S") {$aux_137 = "S";} else {$aux_137 = "N";} // [137] config_7
if ($_POST["aux_138"] == "S") {$aux_138 = "S";} else {$aux_138 = "N";} // [138] config_8
if ($_POST["aux_139"] == "S") {$aux_139 = "S";} else {$aux_139 = "N";} // [139] config_9
if ($_POST["aux_140"] == "S") {$aux_140 = "S";} else {$aux_140 = "N";} // [140] config_10
if ($_POST["aux_141"] == "S") {$aux_141 = "S";} else {$aux_141 = "N";} // [141] contrato_adto_emitir
if ($_POST["aux_142"] == "S") {$aux_142 = "S";} else {$aux_142 = "N";} // [142] contrato_adto_baixar
if ($_POST["aux_143"] == "S") {$aux_143 = "S";} else {$aux_143 = "N";} // [143] contrato_adto_estornar
if ($_POST["aux_144"] == "S") {$aux_144 = "S";} else {$aux_144 = "N";} // [144] contrato_adto_excluir
if ($_POST["aux_145"] == "S") {$aux_145 = "S";} else {$aux_145 = "N";} // [145] contrato_adto_relatorio
if ($_POST["aux_147"] == "S") {$aux_147 = "S";} else {$aux_147 = "N";} // [145] contrato_adto_relatorio

$editar = mysqli_query ($conexao, "UPDATE usuarios_permissoes SET modulo_cadastros='$aux_1', modulo_compras='$aux_2', modulo_estoque='$aux_3', modulo_diversos='$aux_4', cadastro_pessoa_fisica='$aux_5', cadastro_favorecidos='$aux_6', cadastro_produtos='$aux_7', cadastro_usuarios='$aux_8', produtos='$aux_9', ficha_produtor='$aux_10', menu_contrato_futuro='$aux_11', estoque_movimentacao='$aux_12', armazens='$aux_13', exportacao='$aux_14', impressoes='$aux_15', romaneio_editar='$aux_16', romaneio_novo='$aux_17', estoque_mov_entrada='$aux_18', estoque_mov_entrada_r='$aux_19', estoque_mov_saida='$aux_20', estoque_mov_saida_r='$aux_21', modulo_financeiro='$aux_22', financeiro_contas_pagar='$aux_23', financeiro_contas_receber='$aux_24', financeiro_caixa='$aux_25', financeiro_banco='$aux_26', financeiro_gerencial='$aux_27', financeiro_pgtos_relatorios='$aux_28', financeiro_aux_2='$aux_29', excluir_compra='$aux_30', ficha_produtor_entrada='$aux_31', ficha_produtor_entrada_2='$aux_32', ficha_produtor_transferencia='$aux_33', ficha_produtor_movimentacao='$aux_34', favorecido_editar='$aux_35', favorecido_excluir='$aux_36', compra='$aux_37', compra_relatorio='$aux_38', registro_excluir='$aux_39', preco_compra_maximo='$aux_40', permite_compra_maximo='$aux_41', relatorio_talao='$aux_42', baixar_talao='$aux_43', imprime_talao='$aux_44', compra_talao='$aux_45', contrato_futuro_relatorio='$aux_46', contrato_futuro_emitir='$aux_47', contrato_futuro_baixar='$aux_48', contrato_futuro_estornar='$aux_49', contrato_futuro_excluir='$aux_50', acerto_quantidade_compra='$aux_51', index_gerencial='$aux_52', registro_excluir_trans='$aux_53', romaneio_pendente='$aux_54', usuarios_novo_cadastro='$aux_55', usuarios_permissoes='$aux_56', usuarios_desbloqueio='$aux_57', usuarios_editar='$aux_58', usuarios_relatorio='$aux_59', classificacao_qualidade='$aux_60', atualiza_saldo_relatorio='$aux_61', ficha_produtor_entrada_3='$aux_62', ficha_produtor_saida_3='$aux_63', romaneio_excluir='$aux_64', editar_compra='$aux_65', cadastro_pessoa_juridica='$aux_66', menu_pessoas='$aux_67', menu_favorecidos='$aux_68', editar_pf='$aux_69', editar_pj='$aux_70', pessoas_pesquisar='$aux_71', pessoas_relatorio='$aux_72', pessoas_classificacao='$aux_73', registro_excluir_antigo='$aux_74', favorecido_pesquisar='$aux_75', menu_usuarios='$aux_76', romaneio_finalizar='$aux_77', romaneio_visualizar='$aux_78', romaneio_imprimir='$aux_79', romaneio_excluir_antigo='$aux_80', romaneio_editar_antigo='$aux_81', romaneio_nf_entrada='$aux_82', romaneio_nf_saida='$aux_83', compra_nova='$aux_84', menu_contrato_tratado='$aux_85', contrato_tratado_relatorio='$aux_86', contrato_tratado_emitir='$aux_87', contrato_tratado_baixar='$aux_88', contrato_tratado_estornar='$aux_89', contrato_tratado_excluir='$aux_90', lote_cadastro='$aux_91', lote_editar='$aux_92', lote_excluir='$aux_93', armazem_cadastro='$aux_94', armazem_editar='$aux_95', armazem_excluir='$aux_96', sacaria_cadastro='$aux_97', sacaria_editar='$aux_98', sacaria_excluir='$aux_99', servico_producao_cadastro='$aux_100', servico_producao_editar='$aux_101', servico_producao_excluir='$aux_102', tipo_producao_cadastro='$aux_103', tipo_producao_editar='$aux_104', tipo_producao_excluir='$aux_105', ordem_producao_cadastro='$aux_106', ordem_producao_editar='$aux_107', ordem_producao_editar_antigo='$aux_108', ordem_producao_excluir='$aux_109', ordem_producao_excluir_antigo='$aux_110', ordem_producao_cadastros='$aux_111', ordem_producao_mov_int='$aux_112', romaneio_editar_peso='$aux_113', vendas_modulo='$aux_114', vendas_menu='$aux_115', vendas_cadastrar='$aux_116', vendas_editar='$aux_117', vendas_editar_tudo='$aux_118', vendas_editar_antigo='$aux_119', vendas_excluir='$aux_120', vendas_excluir_antigo='$aux_121', vendas_imprimir='$aux_122', vendas_visualizar='$aux_123', vendas_nota_fiscal='$aux_124', vendas_recebimento='$aux_125', vendas_relatorios='$aux_126', vendas_entrega='$aux_127', usuarios_excluir='$aux_128', usuarios_resetar_senha='$aux_129', config_menu='$aux_130', config_1='$aux_131', config_2='$aux_132', config_3='$aux_133', config_4='$aux_134', config_5='$aux_135', config_6='$aux_136', config_7='$aux_137', config_8='$aux_138', config_9='$aux_139', config_10='$aux_140', contrato_adto_emitir='$aux_141', contrato_adto_baixar='$aux_142', contrato_adto_estornar='$aux_143', contrato_adto_excluir='$aux_144', contrato_adto_relatorio='$aux_145', situacao_compra = '$aux_147' WHERE username='$cod_usuario'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Permiss&otilde;es de usu&aacute;rio editadas com sucesso!</div>";
}

elseif ($botao == "ATUALIZA" and $permissao[56] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar permiss&otilde;es</div>";
}

else
{
// MONTA MENSAGEM
$msg = "<div>Selecione abaixo o usu&aacute;rio e todas as atribuições e acessos que serão permitidos. Ap&oacute;s alterar clique em salvar.</div>";
}
// ================================================================================================================


// ====== BUSCA PERMISSÕES DE USUÁRIO =============================================================================
$busca_p_usuario = mysqli_query ($conexao, "SELECT * FROM usuarios_permissoes WHERE username='$cod_usuario'");
$x = mysqli_fetch_row($busca_p_usuario);
$linhas_p_usuario = mysqli_num_rows ($busca_p_usuario);
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
// ================================================================================================================
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

</script>

</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_usuarios.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_usuario_x == 1)
    {echo"<i>$linha_usuario_x usu&aacute;rio cadastrado</i>";}
    elseif ($linha_usuario_x == 0)
    {echo"";}
    else
    {echo"<i>$linha_usuario_x usu&aacute;rios cadastrados</i>";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa" style="height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form name="usuario" action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/usuarios/usuarios_permissoes.php" method="post" />
<input type="hidden" name="botao" value="BUSCA" />
</div>
<!-- ================================================================================================================ -->


 <!-- ======= USUÁRIO =============================================================================================== -->
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
    Usu&aacute;rio:
    </div>
    
    <div style="width:218px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_usuario" class="form_select" onchange="document.usuario.submit()" style="width:200px" />
    <option></option>
    <?php
        $busca_usuario_list = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
        $linhas_usuario_list = mysqli_num_rows ($busca_usuario_list);
    
        for ($u=1 ; $u<=$linhas_usuario_list ; $u++)
        {
            $aux_usuario_list = mysqli_fetch_row ($busca_usuario_list);	
            if ($aux_usuario_list[0] == $cod_usuario)
            {
            echo "<option selected='selected' value='$aux_usuario_list[0]'>$aux_usuario_list[0]</option>";
            }
            else
            {
            echo "<option value='$aux_usuario_list[0]'>$aux_usuario_list[0]</option>";
            }
        }
    ?>
    </select>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="ct_relatorio">



<div style="height:10px; width:1060px; border:0px solid #000; margin:auto"></div>
<form name="form1" action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/usuarios/usuarios_permissoes.php" method="post" />
<input type="hidden" name="botao" value="ATUALIZA" />
<input type="hidden" name="cod_usuario" value="<?php echo"$cod_usuario"; ?>" />
<script>
function verificaStatus(nome){
	if(nome.form.tudo.checked == 0)
		{
			nome.form.tudo.checked = 1;
			marcarTodos(nome);
		}
	else
		{
			nome.form.tudo.checked = 0;
			desmarcarTodos(nome);
		}
}
 
function marcarTodos(nome){
   for (i=0;i<nome.form.elements.length;i++)
	  if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=1
}
 
function desmarcarTodos(nome){
   for (i=0;i<nome.form.elements.length;i++)
	  if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=0
}
</script>



<div style='height:30px; width:1200px; border:0px solid #0099CC; background-color:#FFF; margin:auto; margin-top:6px'>
<div style='height:auto; width:200px; border:0px solid #000; float:left; color:#FFFFFF; margin-top:0px; margin-left:30px'>
<button type='submit' class='botao_1'>Salvar</button>
</div>
<div style='height:auto; width:120px; border:0px solid #000; float:right; color:#003466; font-size:12px; margin-top:3px; margin-right:30px; text-align:right'>
<input type='checkbox' name='tudo' onclick='verificaStatus(this)' />Marcar tudo
<input type='checkbox' name='tudo' onclick='verificaStatus(this)' hidden="hidden" />
</div>

</div>

<div style='height:34px; width:1200px; border:0px solid #0099CC; background-color:#006699; font-size:16px; margin:auto; margin-top:6px'>
	<div style='height:auto; width:300px; border:0px solid #000; float:left; color:#FFFF00; margin-top:7px; margin-left:30px'>
    <?php
	if ($x[1] == "S")
	{echo "<input type='checkbox' name='aux_1' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_1' value='S'>";}
	?>
	M&oacute;dulo: <b>Cadastros</b>
    </div>
</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[67] == "S")
        {echo "<input type='checkbox' name='aux_67' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_67' value='S'>";}
        ?>
		Menu: <b>Pessoas</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[5] == "S")
	{echo "<input type='checkbox' name='aux_5' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_5' value='S'>";}
	?>
	Cadastrar Pessoa</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[69] == "S")
	{echo "<input type='checkbox' name='aux_69' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_69' value='S'>";}
	?>
	Editar Pessoa</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[70] == "S")
	{echo "<input type='checkbox' name='aux_70' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_70' value='S'>";}
	?>
	Excluir Pessoa</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[71] == "S")
	{echo "<input type='checkbox' name='aux_71' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_71' value='S'>";}
	?>
	Pesquisar Pessoa</div>
 
	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[72] == "S")
	{echo "<input type='checkbox' name='aux_72' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_72' value='S'>";}
	?>
	Relatório de Pessoas</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[73] == "S")
	{echo "<input type='checkbox' name='aux_73' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_73' value='S'>";}
	?>
	Classifica&ccedil;&atilde;o/Fun&ccedil;&atilde;o</div>

</div>



<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[68] == "S")
        {echo "<input type='checkbox' name='aux_68' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_68' value='S'>";}
        ?>
		Menu: <b>Favorecidos</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[6] == "S")
	{echo "<input type='checkbox' name='aux_6' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_6' value='S'>";}
	?>
	Cadastrar Favorecido</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[35] == "S")
	{echo "<input type='checkbox' name='aux_35' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_35' value='S'>";}
	?>
	Editar Favorecido</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[36] == "S")
	{echo "<input type='checkbox' name='aux_36' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_36' value='S'>";}
	?>
	Excluir Favorecido</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[75] == "S")
	{echo "<input type='checkbox' name='aux_75' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_75' value='S'>";}
	?>
	Pesquisar Favorecido</div>

    

</div>




<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[76] == "S")
        {echo "<input type='checkbox' name='aux_76' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_76' value='S'>";}
        ?>
		Menu: <b>Usu&aacute;rios</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[55] == "S")
	{echo "<input type='checkbox' name='aux_55' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_55' value='S'>";}
	?>
	Cadastrar Usu&aacute;rio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[58] == "S")
	{echo "<input type='checkbox' name='aux_58' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_58' value='S'>";}
	?>
	Editar Usu&aacute;rio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[128] == "S")
	{echo "<input type='checkbox' name='aux_128' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_128' value='S'>";}
	?>
	Excluir Usu&aacute;rio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[59] == "S")
	{echo "<input type='checkbox' name='aux_59' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_59' value='S'>";}
	?>
	Pesquisar Usu&aacute;rio</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[57] == "S")
	{echo "<input type='checkbox' name='aux_57' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_57' value='S'>";}
	?>
	Bloquear/Desblo. Usu&aacute;rio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[129] == "S")
	{echo "<input type='checkbox' name='aux_129' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_129' value='S'>";}
	?>
	Resetar Senha de Usu&aacute;rio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px; color:#FF0000' title='Esta p&aacute;gina'>
    <?php
	if ($x[56] == "S")
	{echo "<input type='checkbox' name='aux_56' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_56' value='S'>";}
	?>
	Permiss&otilde;es de Usu&aacute;rios</div>

</div>


<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[9] == "S")
        {echo "<input type='checkbox' name='aux_9' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_9' value='S'>";}
        ?>
		Menu: <b>Produtos</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[7] == "S")
	{echo "<input type='checkbox' name='aux_7' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_7' value='S'>";}
	?>
	Cadastrar Produtos</div>
    
</div>












<div style='height:34px; width:1200px; border:0px solid #0099CC; background-color:#006699; font-size:16px; margin:auto; margin-top:30px'>
	<div style='height:auto; width:300px; border:0px solid #000; float:left; color:#FFFF00; margin-top:7px; margin-left:30px'>
    <?php
	if ($x[2] == "S")
	{echo "<input type='checkbox' name='aux_2' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_2' value='S'>";}
	?>
	M&oacute;dulo: <b>Compras</b>
    </div>
</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[37] == "S")
        {echo "<input type='checkbox' name='aux_37' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_37' value='S'>";}
        ?>
		Menu: <b>Compras</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[84] == "S")
	{echo "<input type='checkbox' name='aux_84' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_84' value='S'>";}
	?>
	Nova Compra</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[65] == "S")
	{echo "<input type='checkbox' name='aux_65' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_65' value='S'>";}
	?>
	Editar Compra</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[40] == "S")
	{echo "<input type='checkbox' name='aux_40' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_40' value='S'>";}
	?>
	Alt. Pre&ccedil;o M&aacute;x. de Compra</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[41] == "S")
	{echo "<input type='checkbox' name='aux_41' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_41' value='S'>";}
	?>
	Compras acima do pre&ccedil;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[30] == "S")
	{echo "<input type='checkbox' name='aux_30' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_30' value='S'>";}
	?>
	Excluir Compra</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[39] == "S")
	{echo "<input type='checkbox' name='aux_39' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_39' value='S'>";}
	?>
	Excluir Registro</div>
 
	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[74] == "S")
	{echo "<input type='checkbox' name='aux_74' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_74' value='S'>";}
	?>
	Excluir Registro Antigo</div>
  
  	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[53] == "S")
	{echo "<input type='checkbox' name='aux_53' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_53' value='S'>";}
	?>
	Excluir Transfer&ecirc;ncia</div>
 
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[51] == "S")
	{echo "<input type='checkbox' name='aux_51' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_51' value='S'>";}
	?>
	Acerto de Quantidade</div>

	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[147] == "S")
	{echo "<input type='checkbox' name='aux_147' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_147' value='S'>";}
	?>
	Situação Compra</div>
 
</div>




<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[10] == "S")
        {echo "<input type='checkbox' name='aux_10' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_10' value='S'>";}
        ?>
		Menu: <b>Ficha Produtor</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' title='Acesso a ficha do produtor'>
    <?php
	if ($x[34] == "S")
	{echo "<input type='checkbox' name='aux_34' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_34' value='S'>";}
	?>
	Movimenta&ccedil;&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' 
    title='Depende de um romaneio criado na balan&ccedil;a'>
    <?php
	if ($x[31] == "S")
	{echo "<input type='checkbox' name='aux_31' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_31' value='S'>";}
	?>
	Entrada Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' 
    title='Cria romaneio autom&aacute;tico (Movimenta Ficha e Estoque)'>
    <?php
	if ($x[32] == "S")
	{echo "<input type='checkbox' name='aux_32' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_32' value='S'>";}
	?>
	Entrada Direta</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' 
    title='Cria romaneio de entrada se for selecionado'>
    <?php
	if ($x[62] == "S")
	{echo "<input type='checkbox' name='aux_62' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_62' value='S'>";}
	?>
	Entrada</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' 
    title='Cria romaneio de sa&iacute;da se for selecionado'>
    <?php
	if ($x[63] == "S")
	{echo "<input type='checkbox' name='aux_63' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_63' value='S'>";}
	?>
	Sa&iacute;da</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px' 
    title='Transfer&ecirc;ncias entre produtores'>
    <?php
	if ($x[33] == "S")
	{echo "<input type='checkbox' name='aux_33' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_33' value='S'>";}
	?>
	Transfer&ecirc;ncias</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[42] == "S")
	{echo "<input type='checkbox' name='aux_42' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_42' value='S'>";}
	?>
	Tal&otilde;es</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[43] == "S")
	{echo "<input type='checkbox' name='aux_43' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_43' value='S'>";}
	?>
	Baixar Tal&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[44] == "S")
	{echo "<input type='checkbox' name='aux_44' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_44' value='S'>";}
	?>
	Imprimir Tal&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[45] == "S")
	{echo "<input type='checkbox' name='aux_45' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_45' value='S'>";}
	?>
	Compras c/ mais de 2 Tal&otilde;es</div>
	
</div>




<div style='height:100px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[11] == "S")
        {echo "<input type='checkbox' name='aux_11' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_11' value='S'>";}
        ?>
		Menu: <b>Contrato Futuro</b>
        </div>
	</div>
    
    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[47] == "S")
	{echo "<input type='checkbox' name='aux_47' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_47' value='S'>";}
	?>
	Novo Contrato Futuro</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[48] == "S")
	{echo "<input type='checkbox' name='aux_48' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_48' value='S'>";}
	?>
	Baixar Contrato Futuro</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[49] == "S")
	{echo "<input type='checkbox' name='aux_49' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_49' value='S'>";}
	?>
	Estornar Contrato Futuro</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[50] == "S")
	{echo "<input type='checkbox' name='aux_50' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_50' value='S'>";}
	?>
	Excluir Contrato Futuro</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[46] == "S")
	{echo "<input type='checkbox' name='aux_46' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_46' value='S'>";}
	?>
	Relat&oacute;rios Contratos Futuros</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[87] == "S")
	{echo "<input type='checkbox' name='aux_87' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_87' value='S'>";}
	?>
	Novo Contrato Tratado</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[88] == "S")
	{echo "<input type='checkbox' name='aux_88' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_88' value='S'>";}
	?>
	Baixar Contrato Tratado</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[89] == "S")
	{echo "<input type='checkbox' name='aux_89' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_89' value='S'>";}
	?>
	Estornar Contrato Tratado</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[90] == "S")
	{echo "<input type='checkbox' name='aux_90' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_90' value='S'>";}
	?>
	Excluir Contrato Tratado</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[86] == "S")
	{echo "<input type='checkbox' name='aux_86' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_86' value='S'>";}
	?>
	Relat&oacute;rios Contratos Tratados</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[141] == "S")
	{echo "<input type='checkbox' name='aux_141' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_141' value='S'>";}
	?>
	Novo Contrato Adiantamento</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[142] == "S")
	{echo "<input type='checkbox' name='aux_142' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_142' value='S'>";}
	?>
	Baixar Contrato Adto</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[143] == "S")
	{echo "<input type='checkbox' name='aux_143' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_143' value='S'>";}
	?>
	Estornar Contrato Adto</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[144] == "S")
	{echo "<input type='checkbox' name='aux_144' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_144' value='S'>";}
	?>
	Excluir Contrato Adto</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[145] == "S")
	{echo "<input type='checkbox' name='aux_145' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_145' value='S'>";}
	?>
	Relat&oacute;rios Contratos Adto</div>

</div>






<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[38] == "S")
        {echo "<input type='checkbox' name='aux_38' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_38' value='S'>";}
        ?>
		Menu: <b>Relat&oacute;rios de Compras</b>
        </div>
	</div>
    

</div>












<div style='height:34px; width:1200px; border:0px solid #0099CC; background-color:#006699; font-size:16px; margin:auto; margin-top:30px'>
	<div style='height:auto; width:300px; border:0px solid #000; float:left; color:#FFFF00; margin-top:7px; margin-left:30px'>
    <?php
	if ($x[3] == "S")
	{echo "<input type='checkbox' name='aux_3' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_3' value='S'>";}
	?>
	M&oacute;dulo: <b>Estoque</b>
    </div>
</div>



<div style='height:130px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[12] == "S")
        {echo "<input type='checkbox' name='aux_12' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_12' value='S'>";}
        ?>
		Menu: <b>Produ&ccedil;&atilde;o</b>
        </div>
	</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[106] == "S")
	{echo "<input type='checkbox' name='aux_106' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_106' value='S'>";}
	?>
	Nova Ordem de Produ&ccedil;&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[107] == "S")
	{echo "<input type='checkbox' name='aux_107' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_107' value='S'>";}
	?>
	Editar Ordem de Produ&ccedil;&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[108] == "S")
	{echo "<input type='checkbox' name='aux_108' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_108' value='S'>";}
	?>
	Editar Ordem de Prod. Antiga</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[109] == "S")
	{echo "<input type='checkbox' name='aux_109' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_109' value='S'>";}
	?>
	Excluir Ordem de Produ&ccedil;&atilde;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[110] == "S")
	{echo "<input type='checkbox' name='aux_110' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_110' value='S'>";}
	?>
	Excluir Ordem de Prod. Antiga</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[112] == "S")
	{echo "<input type='checkbox' name='aux_112' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_112' value='S'>";}
	?>
	Movimenta&ccedil;&atilde;o Interna</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[111] == "S")
	{echo "<input type='checkbox' name='aux_111' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_111' value='S'>";}
	?>
	Cadastros (Produ&ccedil;&atilde;o)</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[54] == "S")
	{echo "<input type='checkbox' name='aux_54' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_54' value='S'>";}
	?>
	Romaneios Pendentes</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[60] == "S")
	{echo "<input type='checkbox' name='aux_60' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_60' value='S'>";}
	?>
	Classifica&ccedil;&atilde;o de Qualidade</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[91] == "S")
	{echo "<input type='checkbox' name='aux_91' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_91' value='S'>";}
	?>
	Cadastrar Lote</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[92] == "S")
	{echo "<input type='checkbox' name='aux_92' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_92' value='S'>";}
	?>
	Editar Lote</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[93] == "S")
	{echo "<input type='checkbox' name='aux_93' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_93' value='S'>";}
	?>
	Excluir Lote</div>





    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[94] == "S")
	{echo "<input type='checkbox' name='aux_94' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_94' value='S'>";}
	?>
	Cadastrar Armaz&eacute;m</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[95] == "S")
	{echo "<input type='checkbox' name='aux_95' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_95' value='S'>";}
	?>
	Editar Armaz&eacute;m</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[96] == "S")
	{echo "<input type='checkbox' name='aux_96' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_96' value='S'>";}
	?>
	Excluir Armaz&eacute;m</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[97] == "S")
	{echo "<input type='checkbox' name='aux_97' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_97' value='S'>";}
	?>
	Cadastrar Sacaria</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[98] == "S")
	{echo "<input type='checkbox' name='aux_98' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_98' value='S'>";}
	?>
	Editar Sacaria</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[99] == "S")
	{echo "<input type='checkbox' name='aux_99' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_99' value='S'>";}
	?>
	Excluir Sacaria</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[100] == "S")
	{echo "<input type='checkbox' name='aux_100' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_100' value='S'>";}
	?>
	Cadastrar Servi&ccedil;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[101] == "S")
	{echo "<input type='checkbox' name='aux_101' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_101' value='S'>";}
	?>
	Editar Servi&ccedil;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[102] == "S")
	{echo "<input type='checkbox' name='aux_102' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_102' value='S'>";}
	?>
	Excluir Servi&ccedil;o</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[103] == "S")
	{echo "<input type='checkbox' name='aux_103' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_103' value='S'>";}
	?>
	Cadastrar Tipo (Produ&ccedil;&atilde;o)</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[104] == "S")
	{echo "<input type='checkbox' name='aux_104' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_104' value='S'>";}
	?>
	Editar Tipo (Produ&ccedil;&atilde;o)</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[105] == "S")
	{echo "<input type='checkbox' name='aux_105' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_105' value='S'>";}
	?>
	Excluir Tipo (Produ&ccedil;&atilde;o)</div>

</div>



<div style='height:90px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:215px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[18] == "S")
        {echo "<input type='checkbox' name='aux_18' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_18' value='S'>";}
        ?>
		Menu: <b>Entrada de Estoque</b>
        </div>

        <div style='height:auto; width:215px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[20] == "S")
        {echo "<input type='checkbox' name='aux_20' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_20' value='S'>";}
        ?>
		Menu: <b>Sa&iacute;da de Estoque</b>
        </div>
	</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[17] == "S")
	{echo "<input type='checkbox' name='aux_17' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_17' value='S'>";}
	?>
	Novo Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[78] == "S")
	{echo "<input type='checkbox' name='aux_78' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_78' value='S'>";}
	?>
	Buscar/Visualizar Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[64] == "S")
	{echo "<input type='checkbox' name='aux_64' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_64' value='S'>";}
	?>
	Excluir Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[80] == "S")
	{echo "<input type='checkbox' name='aux_80' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_80' value='S'>";}
	?>
	Excluir Romaneio Antigo</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[16] == "S")
	{echo "<input type='checkbox' name='aux_16' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_16' value='S'>";}
	?>
	Editar Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[81] == "S")
	{echo "<input type='checkbox' name='aux_81' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_81' value='S'>";}
	?>
	Editar Romaneio Antigo</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[77] == "S")
	{echo "<input type='checkbox' name='aux_77' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_77' value='S'>";}
	?>
	Finalizar Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[79] == "S")
	{echo "<input type='checkbox' name='aux_79' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_79' value='S'>";}
	?>
	Imprimir Romaneio</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[82] == "S")
	{echo "<input type='checkbox' name='aux_82' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_82' value='S'>";}
	?>
	Nota Fiscal de Entrada</div>

    <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[83] == "S")
	{echo "<input type='checkbox' name='aux_83' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_83' value='S'>";}
	?>
	Nota Fiscal de Sa&iacute;da</div>

</div>




<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[19] == "S")
        {echo "<input type='checkbox' name='aux_19' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_19' value='S'>";}
        ?>
		Menu: <b>Relat&oacute;rios de Estoque</b>
        </div>
	</div>
    

</div>


<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[13] == "S")
        {echo "<input type='checkbox' name='aux_13' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_13' value='S'>";}
        ?>
		Menu: <b>Armaz&eacute;ns</b>
        </div>
	</div>
    

</div>


<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[14] == "S")
        {echo "<input type='checkbox' name='aux_14' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_14' value='S'>";}
        ?>
		Menu: <b>Exporta&ccedil;&atilde;o</b>
        </div>
	</div>
    

</div>











<div style='height:34px; width:1200px; border:0px solid #0099CC; background-color:#006699; font-size:16px; margin:auto; margin-top:30px'>
	<div style='height:auto; width:300px; border:0px solid #000; float:left; color:#FFFF00; margin-top:7px; margin-left:30px'>
    <?php
	if ($x[22] == "S")
	{echo "<input type='checkbox' name='aux_22' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_22' value='S'>";}
	?>
	M&oacute;dulo: <b>Financeiro</b>
    </div>
</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[23] == "S")
        {echo "<input type='checkbox' name='aux_23' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_23' value='S'>";}
        ?>
		Menu: <b>Contas a Pagar</b>
        </div>
	</div>
 
     <div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
    <?php
	if ($x[28] == "S")
	{echo "<input type='checkbox' name='aux_28' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_28' value='S'>";}
	?>
	Relat&oacute;rios de Contas a Pagar</div>

</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[24] == "S")
        {echo "<input type='checkbox' name='aux_24' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_24' value='S'>";}
        ?>
		Menu: <b>Contas a Receber</b>
        </div>
	</div>
    

</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[25] == "S")
        {echo "<input type='checkbox' name='aux_25' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_25' value='S'>";}
        ?>
		Menu: <b>Caixa</b>
        </div>
	</div>
    

</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[26] == "S")
        {echo "<input type='checkbox' name='aux_26' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_26' value='S'>";}
        ?>
		Menu: <b>Banco</b>
        </div>
	</div>
    

</div>


<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>
	<div style='height:24px; width:1200px; border:0px solid #0099CC; background-color:#DCDCDC; font-size:12px; margin:auto'>
        <div style='height:auto; width:300px; border:0px solid #000; color:#003466; float:left; margin-top:0px; margin-left:23px'>
		<?php
        if ($x[27] == "S")
        {echo "<input type='checkbox' name='aux_27' value='S' checked='checked'>";}
        else
        {echo "<input type='checkbox' name='aux_27' value='S'>";}
        ?>
		Menu: <b>Gerencial</b>
        </div>
	</div>
    

</div>








<div style='height:34px; width:1200px; border:0px solid #0099CC; background-color:#006699; font-size:16px; margin:auto; margin-top:30px'>
	<div style='height:auto; width:300px; border:0px solid #000; float:left; color:#FFFF00; margin-top:7px; margin-left:30px'>
    <?php
	if ($x[4] == "S")
	{echo "<input type='checkbox' name='aux_4' value='S' checked='checked'>";}
	else
	{echo "<input type='checkbox' name='aux_4' value='S'>";}
	?>
	<b>Diversos</b>
    </div>
</div>

<div style='height:70px; width:1200px; border:1px solid #DCDCDC; color:#003466; font-size:12px; margin:auto; margin-top:20px'>

	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
	<?php
    if ($x[15] == "S")
    {echo "<input type='checkbox' name='aux_15' value='S' checked='checked'>";}
    else
    {echo "<input type='checkbox' name='aux_15' value='S'>";}
    ?>
	Menu Impress&otilde;es</div>

	<div style='height:18px; width:215px; border:0px solid #000; float:left; margin-top:2px; margin-left:23px'>
	<?php
    if ($x[52] == "S")
    {echo "<input type='checkbox' name='aux_52' value='S' checked='checked'>";}
    else
    {echo "<input type='checkbox' name='aux_52' value='S'>";}
    ?>
	Index Gerencial</div>


</div>







</form>


<div class="espacamento_30"></div>


</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>



</div>
<!-- ====== FIM DIV CT ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>