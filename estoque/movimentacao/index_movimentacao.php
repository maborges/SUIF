<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'relatorio_consolidado_estoque';
$titulo = 'Relat&oacute;rio Consolidado de Estoque';
$modulo = 'estoque';
$menu = 'movimentacao';


// ===== LINHARES ===========================================================
$soma_entrada_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='LINHARES'"));
$soma_entrada_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='LINHARES'"));
$soma_entrada_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='LINHARES'"));
$soma_entrada_cravo_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='LINHARES'"));
$soma_entrada_cafe_arabica_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='LINHARES'"));
$soma_entrada_residuo_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='LINHARES'"));
$soma_entrada_residuo_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='11' AND filial='LINHARES'"));
$soma_entrada_residuo_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='12' AND filial='LINHARES'"));


$soma_saida_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='LINHARES'"));
$soma_saida_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='LINHARES'"));
$soma_saida_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='LINHARES'"));
$soma_saida_cravo_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='LINHARES'"));
$soma_saida_cafe_arabica_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='LINHARES'"));
$soma_saida_residuo_cacau_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='LINHARES'"));
$soma_saida_residuo_pimenta_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='11' AND filial='LINHARES'"));
$soma_saida_residuo_cafe_linhares = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='12' AND filial='LINHARES'"));



$quant_cafe_linhares = ($soma_entrada_cafe_linhares[0] - $soma_saida_cafe_linhares[0]);
$quant_cafe_linhares_convert = ($quant_cafe_linhares / 60);
$quant_cafe_linhares_print = number_format($quant_cafe_linhares_convert,2,",",".");
$quant_pimenta_linhares = ($soma_entrada_pimenta_linhares[0] - $soma_saida_pimenta_linhares[0]);
$quant_pimenta_linhares_print = number_format($quant_pimenta_linhares,2,",",".");
$quant_cacau_linhares = ($soma_entrada_cacau_linhares[0] - $soma_saida_cacau_linhares[0]);
$quant_cacau_linhares_print = number_format($quant_cacau_linhares,2,",",".");
$quant_cravo_linhares = ($soma_entrada_cravo_linhares[0] - $soma_saida_cravo_linhares[0]);
$quant_cravo_linhares_print = number_format($quant_cravo_linhares,2,",",".");
$quant_cafe_arabica_linhares = ($soma_entrada_cafe_arabica_linhares[0] - $soma_saida_cafe_arabica_linhares[0]);
$quant_cafe_arabica_linhares_convert = ($quant_cafe_arabica_linhares / 60);
$quant_cafe_arabica_linhares_print = number_format($quant_cafe_arabica_linhares_convert,2,",",".");
$quant_res_cacau_linhares = ($soma_entrada_residuo_cacau_linhares[0] - $soma_saida_residuo_cacau_linhares[0]);
$quant_res_cacau_linhares_print = number_format($quant_res_cacau_linhares,2,",",".");
$quant_res_pimenta_linhares = ($soma_entrada_residuo_pimenta_linhares[0] - $soma_saida_residuo_pimenta_linhares[0]);
$quant_res_pimenta_linhares_print = number_format($quant_res_pimenta_linhares,2,",",".");
$quant_res_cafe_linhares = ($soma_entrada_residuo_cafe_linhares[0] - $soma_saida_residuo_cafe_linhares[0]);
$quant_res_cafe_linhares_convert = ($quant_res_cafe_linhares / 60);
$quant_res_cafe_linhares_print = number_format($quant_res_cafe_linhares_convert,2,",",".");


// ===== JAGUARE ===========================================================
$soma_entrada_cafe_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='JAGUARE'"));
$soma_entrada_pimenta_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='JAGUARE'"));
$soma_entrada_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='JAGUARE'"));
$soma_entrada_cravo_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='JAGUARE'"));
$soma_entrada_cafe_arabica_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='JAGUARE'"));
$soma_entrada_residuo_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='JAGUARE'"));


$soma_saida_cafe_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='JAGUARE'"));
$soma_saida_pimenta_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='JAGUARE'"));
$soma_saida_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='JAGUARE'"));
$soma_saida_cravo_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='JAGUARE'"));
$soma_saida_cafe_arabica_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='JAGUARE'"));
$soma_saida_residuo_cacau_jaguare = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='JAGUARE'"));


$quant_cafe_jaguare = ($soma_entrada_cafe_jaguare[0] - $soma_saida_cafe_jaguare[0]);
$quant_cafe_jaguare_convert = ($quant_cafe_jaguare / 60);
$quant_cafe_jaguare_print = number_format($quant_cafe_jaguare_convert,2,",",".");
$quant_pimenta_jaguare = ($soma_entrada_pimenta_jaguare[0] - $soma_saida_pimenta_jaguare[0]);
$quant_pimenta_jaguare_print = number_format($quant_pimenta_jaguare,2,",",".");
$quant_cacau_jaguare = ($soma_entrada_cacau_jaguare[0] - $soma_saida_cacau_jaguare[0]);
$quant_cacau_jaguare_print = number_format($quant_cacau_jaguare,2,",",".");
$quant_cravo_jaguare = ($soma_entrada_cravo_jaguare[0] - $soma_saida_cravo_jaguare[0]);
$quant_cravo_jaguare_print = number_format($quant_cravo_jaguare,2,",",".");
$quant_cafe_arabica_jaguare = ($soma_entrada_cafe_arabica_jaguare[0] - $soma_saida_cafe_arabica_jaguare[0]);
$quant_cafe_arabica_jaguare_convert = ($quant_cafe_arabica_jaguare / 60);
$quant_cafe_arabica_jaguare_print = number_format($quant_cafe_arabica_jaguare_convert,2,",",".");


// ===== CASTANHAL ===========================================================
$soma_entrada_cafe_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='CASTANHAL'"));
$soma_entrada_pimenta_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='CASTANHAL'"));
$soma_entrada_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='CASTANHAL'"));
$soma_entrada_cravo_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='CASTANHAL'"));
$soma_entrada_cafe_arabica_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='CASTANHAL'"));
$soma_entrada_residuo_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='CASTANHAL'"));


$soma_saida_cafe_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='CASTANHAL'"));
$soma_saida_pimenta_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='CASTANHAL'"));
$soma_saida_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='CASTANHAL'"));
$soma_saida_cravo_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='CASTANHAL'"));
$soma_saida_cafe_arabica_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='CASTANHAL'"));
$soma_saida_residuo_cacau_castanhal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='CASTANHAL'"));


$quant_cafe_castanhal = ($soma_entrada_cafe_castanhal[0] - $soma_saida_cafe_castanhal[0]);
$quant_cafe_castanhal_convert = ($quant_cafe_castanhal / 60);
$quant_cafe_castanhal_print = number_format($quant_cafe_castanhal_convert,2,",",".");
$quant_pimenta_castanhal = ($soma_entrada_pimenta_castanhal[0] - $soma_saida_pimenta_castanhal[0]);
$quant_pimenta_castanhal_print = number_format($quant_pimenta_castanhal,2,",",".");
$quant_cacau_castanhal = ($soma_entrada_cacau_castanhal[0] - $soma_saida_cacau_castanhal[0]);
$quant_cacau_castanhal_print = number_format($quant_cacau_castanhal,2,",",".");
$quant_cravo_castanhal = ($soma_entrada_cravo_castanhal[0] - $soma_saida_cravo_castanhal[0]);
$quant_cravo_castanhal_print = number_format($quant_cravo_castanhal,2,",",".");
$quant_cafe_arabica_castanhal = ($soma_entrada_cafe_arabica_castanhal[0] - $soma_saida_cafe_arabica_castanhal[0]);
$quant_cafe_arabica_castanhal_convert = ($quant_cafe_arabica_castanhal / 60);
$quant_cafe_arabica_castanhal_print = number_format($quant_cafe_arabica_castanhal_convert,2,",",".");



// ===== ALTAMIRA ===========================================================
$soma_entrada_cafe_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='ALTAMIRA'"));
$soma_entrada_pimenta_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='ALTAMIRA'"));
$soma_entrada_cacau_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='ALTAMIRA'"));
$soma_entrada_cravo_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='ALTAMIRA'"));
$soma_entrada_cafe_arabica_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='ALTAMIRA'"));
$soma_entrada_residuo_cacau_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='ALTAMIRA'"));


$soma_saida_cafe_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='ALTAMIRA'"));
$soma_saida_pimenta_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='ALTAMIRA'"));
$soma_saida_cacau_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='ALTAMIRA'"));
$soma_saida_cravo_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='ALTAMIRA'"));
$soma_saida_cafe_arabica_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='ALTAMIRA'"));
$soma_saida_residuo_cacau_altamira = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='ALTAMIRA'"));


$quant_cafe_altamira = ($soma_entrada_cafe_altamira[0] - $soma_saida_cafe_altamira[0]);
$quant_cafe_altamira_convert = ($quant_cafe_altamira / 60);
$quant_cafe_altamira_print = number_format($quant_cafe_altamira_convert,2,",",".");
$quant_pimenta_altamira = ($soma_entrada_pimenta_altamira[0] - $soma_saida_pimenta_altamira[0]);
$quant_pimenta_altamira_print = number_format($quant_pimenta_altamira,2,",",".");
$quant_cacau_altamira = ($soma_entrada_cacau_altamira[0] - $soma_saida_cacau_altamira[0]);
$quant_cacau_altamira_print = number_format($quant_cacau_altamira,2,",",".");
$quant_cravo_altamira = ($soma_entrada_cravo_altamira[0] - $soma_saida_cravo_altamira[0]);
$quant_cravo_altamira_print = number_format($quant_cravo_altamira,2,",",".");
$quant_cafe_arabica_altamira = ($soma_entrada_cafe_arabica_altamira[0] - $soma_saida_cafe_arabica_altamira[0]);
$quant_cafe_arabica_altamira_convert = ($quant_cafe_arabica_altamira / 60);
$quant_cafe_arabica_altamira_print = number_format($quant_cafe_arabica_altamira_convert,2,",",".");



// ===== ITAMARAJU ===========================================================
$soma_entrada_cafe_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='ITAMARAJU'"));
$soma_entrada_pimenta_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='ITAMARAJU'"));
$soma_entrada_cacau_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='ITAMARAJU'"));
$soma_entrada_cravo_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='ITAMARAJU'"));
$soma_entrada_cafe_arabica_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='ITAMARAJU'"));
$soma_entrada_residuo_cacau_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='ITAMARAJU'"));


$soma_saida_cafe_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='ITAMARAJU'"));
$soma_saida_pimenta_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='ITAMARAJU'"));
$soma_saida_cacau_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='ITAMARAJU'"));
$soma_saida_cravo_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='ITAMARAJU'"));
$soma_saida_cafe_arabica_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='ITAMARAJU'"));
$soma_saida_residuo_cacau_itamaraju = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='ITAMARAJU'"));


$quant_cafe_itamaraju = ($soma_entrada_cafe_itamaraju[0] - $soma_saida_cafe_itamaraju[0]);
$quant_cafe_itamaraju_convert = ($quant_cafe_itamaraju / 60);
$quant_cafe_itamaraju_print = number_format($quant_cafe_itamaraju_convert,2,",",".");
$quant_pimenta_itamaraju = ($soma_entrada_pimenta_itamaraju[0] - $soma_saida_pimenta_itamaraju[0]);
$quant_pimenta_itamaraju_print = number_format($quant_pimenta_itamaraju,2,",",".");
$quant_cacau_itamaraju = ($soma_entrada_cacau_itamaraju[0] - $soma_saida_cacau_itamaraju[0]);
$quant_cacau_itamaraju_print = number_format($quant_cacau_itamaraju,2,",",".");
$quant_cravo_itamaraju = ($soma_entrada_cravo_itamaraju[0] - $soma_saida_cravo_itamaraju[0]);
$quant_cravo_itamaraju_print = number_format($quant_cravo_itamaraju,2,",",".");
$quant_cafe_arabica_itamaraju = ($soma_entrada_cafe_arabica_itamaraju[0] - $soma_saida_cafe_arabica_itamaraju[0]);
$quant_cafe_arabica_itamaraju_convert = ($quant_cafe_arabica_itamaraju / 60);
$quant_cafe_arabica_itamaraju_print = number_format($quant_cafe_arabica_itamaraju_convert,2,",",".");


// ===== SAO MATEUS ===========================================================
$soma_entrada_cafe_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='SAO_MATEUS'"));
$soma_entrada_pimenta_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='SAO_MATEUS'"));
$soma_entrada_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='SAO_MATEUS'"));
$soma_entrada_cravo_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='SAO_MATEUS'"));
$soma_entrada_cafe_arabica_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='SAO_MATEUS'"));
$soma_entrada_residuo_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='SAO_MATEUS'"));


$soma_saida_cafe_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='SAO_MATEUS'"));
$soma_saida_pimenta_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='SAO_MATEUS'"));
$soma_saida_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='SAO_MATEUS'"));
$soma_saida_cravo_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='SAO_MATEUS'"));
$soma_saida_cafe_arabica_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='SAO_MATEUS'"));
$soma_saida_residuo_cacau_sao_mateus = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='SAO_MATEUS'"));


$quant_cafe_sao_mateus = ($soma_entrada_cafe_sao_mateus[0] - $soma_saida_cafe_sao_mateus[0]);
$quant_cafe_sao_mateus_convert = ($quant_cafe_sao_mateus / 60);
$quant_cafe_sao_mateus_print = number_format($quant_cafe_sao_mateus_convert,2,",",".");
$quant_pimenta_sao_mateus = ($soma_entrada_pimenta_sao_mateus[0] - $soma_saida_pimenta_sao_mateus[0]);
$quant_pimenta_sao_mateus_print = number_format($quant_pimenta_sao_mateus,2,",",".");
$quant_cacau_sao_mateus = ($soma_entrada_cacau_sao_mateus[0] - $soma_saida_cacau_sao_mateus[0]);
$quant_cacau_sao_mateus_print = number_format($quant_cacau_sao_mateus,2,",",".");
$quant_cravo_sao_mateus = ($soma_entrada_cravo_sao_mateus[0] - $soma_saida_cravo_sao_mateus[0]);
$quant_cravo_sao_mateus_print = number_format($quant_cravo_sao_mateus,2,",",".");
$quant_cafe_arabica_sao_mateus = ($soma_entrada_cafe_arabica_sao_mateus[0] - $soma_saida_cafe_arabica_sao_mateus[0]);
$quant_cafe_arabica_sao_mateus_convert = ($quant_cafe_arabica_sao_mateus / 60);
$quant_cafe_arabica_sao_mateus_print = number_format($quant_cafe_arabica_sao_mateus_convert,2,",",".");




// ===== VARGINHA ===========================================================
$soma_entrada_cafe_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='VARGINHA'"));
$soma_entrada_pimenta_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='VARGINHA'"));
$soma_entrada_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='VARGINHA'"));
$soma_entrada_cravo_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='VARGINHA'"));
$soma_entrada_cafe_arabica_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='VARGINHA'"));
$soma_entrada_residuo_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='VARGINHA'"));


$soma_saida_cafe_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='VARGINHA'"));
$soma_saida_pimenta_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='VARGINHA'"));
$soma_saida_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='VARGINHA'"));
$soma_saida_cravo_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='VARGINHA'"));
$soma_saida_cafe_arabica_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='VARGINHA'"));
$soma_saida_residuo_cacau_varginha = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='VARGINHA'"));


$quant_cafe_varginha = ($soma_entrada_cafe_varginha[0] - $soma_saida_cafe_varginha[0]);
$quant_cafe_varginha_convert = ($quant_cafe_varginha / 60);
$quant_cafe_varginha_print = number_format($quant_cafe_varginha_convert,2,",",".");
$quant_pimenta_varginha = ($soma_entrada_pimenta_varginha[0] - $soma_saida_pimenta_varginha[0]);
$quant_pimenta_varginha_print = number_format($quant_pimenta_varginha,2,",",".");
$quant_cacau_varginha = ($soma_entrada_cacau_varginha[0] - $soma_saida_cacau_varginha[0]);
$quant_cacau_varginha_print = number_format($quant_cacau_varginha,2,",",".");
$quant_cravo_varginha = ($soma_entrada_cravo_varginha[0] - $soma_saida_cravo_varginha[0]);
$quant_cravo_varginha_print = number_format($quant_cravo_varginha,2,",",".");
$quant_cafe_arabica_varginha = ($soma_entrada_cafe_arabica_varginha[0] - $soma_saida_cafe_arabica_varginha[0]);
$quant_cafe_arabica_varginha_convert = ($quant_cafe_arabica_varginha / 60);
$quant_cafe_arabica_varginha_print = number_format($quant_cafe_arabica_varginha_convert,2,",",".");


// ===== LINHARES - POLO ===========================================================
$soma_entrada_cafe_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='2' AND filial='LINHARES_POLO'"));
$soma_entrada_pimenta_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='3' AND filial='LINHARES_POLO'"));
$soma_entrada_cacau_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='4' AND filial='LINHARES_POLO'"));
$soma_entrada_cravo_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='5' AND filial='LINHARES_POLO'"));
$soma_entrada_cafe_arabica_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='10' AND filial='LINHARES_POLO'"));
$soma_entrada_residuo_cacau_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND cod_produto='9' AND filial='LINHARES_POLO'"));


$soma_saida_cafe_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='2' AND filial='LINHARES_POLO'"));
$soma_saida_pimenta_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='3' AND filial='LINHARES_POLO'"));
$soma_saida_cacau_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='4' AND filial='LINHARES_POLO'"));
$soma_saida_cravo_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='5' AND filial='LINHARES_POLO'"));
$soma_saida_cafe_arabica_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='10' AND filial='LINHARES_POLO'"));
$soma_saida_residuo_cacau_linhares_polo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' AND cod_produto='9' AND filial='LINHARES_POLO'"));


$quant_cafe_linhares_polo = ($soma_entrada_cafe_linhares_polo[0] - $soma_saida_cafe_linhares_polo[0]);
$quant_cafe_linhares_polo_convert = ($quant_cafe_linhares_polo / 60);
$quant_cafe_linhares_polo_print = number_format($quant_cafe_linhares_polo_convert,2,",",".");
$quant_pimenta_linhares_polo = ($soma_entrada_pimenta_linhares_polo[0] - $soma_saida_pimenta_linhares_polo[0]);
$quant_pimenta_linhares_polo_print = number_format($quant_pimenta_linhares_polo,2,",",".");
$quant_cacau_linhares_polo = ($soma_entrada_cacau_linhares_polo[0] - $soma_saida_cacau_linhares_polo[0]);
$quant_cacau_linhares_polo_print = number_format($quant_cacau_linhares_polo,2,",",".");
$quant_cravo_linhares_polo = ($soma_entrada_cravo_linhares_polo[0] - $soma_saida_cravo_linhares_polo[0]);
$quant_cravo_linhares_polo_print = number_format($quant_cravo_linhares_polo,2,",",".");
$quant_cafe_arabica_linhares_polo = ($soma_entrada_cafe_arabica_linhares_polo[0] - $soma_saida_cafe_arabica_linhares_polo[0]);
$quant_cafe_arabica_linhares_polo_convert = ($quant_cafe_arabica_linhares_polo / 60);
$quant_cafe_arabica_linhares_polo_print = number_format($quant_cafe_arabica_linhares_polo_convert,2,",",".");




// ===== TOTAL ===========================================================
$quant_cafe_total = ($quant_cafe_linhares + $quant_cafe_jaguare + $quant_cafe_castanhal + $quant_cafe_altamira + $quant_cafe_itamaraju + $quant_cafe_sao_mateus + $quant_cafe_varginha + $quant_cafe_linhares_polo);
$quant_cafe_total_convert = ($quant_cafe_total / 60);
$quant_cafe_total_print = number_format($quant_cafe_total_convert,2,",",".");
$quant_pimenta_total = ($quant_pimenta_linhares + $quant_pimenta_jaguare + $quant_pimenta_castanhal + $quant_pimenta_altamira + $quant_pimenta_itamaraju + $quant_pimenta_sao_mateus + $quant_pimenta_varginha + $quant_pimenta_linhares_polo);
$quant_pimenta_total_print = number_format($quant_pimenta_total,2,",",".");
$quant_cacau_total = ($quant_cacau_linhares + $quant_cacau_jaguare + $quant_cacau_castanhal + $quant_cacau_altamira + $quant_cacau_itamaraju + $quant_cacau_sao_mateus + $quant_cacau_varginha + $quant_cacau_linhares_polo);
$quant_cacau_total_print = number_format($quant_cacau_total,2,",",".");
$quant_cravo_total = ($quant_cravo_linhares + $quant_cravo_jaguare + $quant_cravo_castanhal + $quant_cravo_altamira + $quant_cravo_itamaraju + $quant_cravo_sao_mateus + $quant_cravo_varginha + $quant_cravo_linhares_polo);
$quant_cravo_total_print = number_format($quant_cravo_total,2,",",".");
$quant_cafe_arabica_total = ($quant_cafe_arabica_linhares + $quant_cafe_arabica_jaguare + $quant_cafe_arabica_castanhal + $quant_cafe_arabica_altamira + $quant_cafe_arabica_itamaraju + $quant_cafe_arabica_sao_mateus + $quant_cafe_arabica_varginha + $quant_cafe_arabica_linhares_polo);
$quant_cafe_arabica_total_convert = ($quant_cafe_arabica_total / 60);
$quant_cafe_arabica_total_print = number_format($quant_cafe_arabica_total_convert,2,",",".");
// =======================================================================



include ('../../includes/head.php'); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>






<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="ct_1" style="height:500px">
<div style="width:1270px; height:15px; border:0px solid #000; margin:auto"></div>



<!-- =========================================================================================================== -->
<div id='centro' style='height:400px; width:1240px; border:0px solid #BCD2EE; margin-top:15px; margin-left:18px; float:left' align='center'>



<div id='centro' style='height:40px; width:1220px; border:0px solid #000; margin-top:10px; background-color:#003466; color:#FFF; 
font-size:16px; float:left; margin-left:10px; text-align:center'>
    <div style='margin-top:10px'><b>Relat&oacute;rio Consolidado de Estoque</b></div>
</div>


<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i></i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Linhares</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Jaguar&eacute;</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Castanhal</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Altamira</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Itamaraj&uacute;</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>S&atilde;o Mateus</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Varginha</i></b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <b><i>Linhares/Polo</i></b></div>
    </div>
</div>


<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Caf&eacute; Conilon</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_linhares_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_jaguare_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_castanhal_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_altamira_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_itamaraju_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_sao_mateus_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_varginha_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_linhares_polo_print Sc"; ?></div>
    </div>
</div>



<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Res&iacute;duo de Caf&eacute;</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_res_cafe_linhares_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Sc"; ?></div>
    </div>
</div>




<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Pimenta do Reino</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_linhares_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_jaguare_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_castanhal_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_altamira_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_itamaraju_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_sao_mateus_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_varginha_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_pimenta_linhares_polo_print Kg"; ?></div>
    </div>
</div>




<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Res&iacute;duo Pimenta</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_res_pimenta_linhares_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>
</div>




<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Cacau</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_linhares_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_jaguare_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_castanhal_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_altamira_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_itamaraju_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_sao_mateus_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_varginha_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cacau_linhares_polo_print Kg"; ?></div>
    </div>
</div>




<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Res&iacute;duo de Cacau</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_res_cacau_linhares_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "0,00 Kg"; ?></div>
    </div>
</div>





<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Cravo da &Iacute;ndia</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_linhares_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_jaguare_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_castanhal_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_altamira_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_itamaraju_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_sao_mateus_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_varginha_print Kg"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cravo_linhares_polo_print Kg"; ?></div>
    </div>
</div>




<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:8px; float:left; margin-left:10px; border-radius:2px; background-color:#EEE' align='center'>
    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#009900; text-align:left'>
    <b>Caf&eacute; Ar&aacute;bica</b></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_linhares_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_jaguare_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_castanhal_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_altamira_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_itamaraju_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_sao_mateus_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_varginha_print Sc"; ?></div>
    </div>

    <div id='centro' style='width:122px; float:left; height:22px; margin-left:10px; border:0px solid #999'>
    <div style='width:122px; float:left; margin-top:4px; font-size:12px; color:#003466; text-align:center'>
    <?php echo "$quant_cafe_arabica_linhares_polo_print Sc"; ?></div>
    </div>
</div>






<div id='centro' style='height:22px; width:1220px; border:0px solid #000; margin-top:25px; float:left; margin-left:10px' align='center'>
    <div id='centro' style='width:1220px; float:left; height:22px; margin-left:0px; border:0px solid #999'>
    <div style='width:1220px; float:left; margin-top:4px; font-size:16px; color:#003466; text-align:center'>
    <b><i>TOTAL GERAL</i></b></div>
    </div>
</div>



<div id='centro' style='height:44px; width:1220px; border:0px solid #000; margin-top:20px; float:left; margin-left:10px; border-radius:2px' align='center'>

    <div id='centro' style='width:200px; float:left; height:44px; margin-left:10px; border:0px solid #999'>
    </div>

    <div id='centro' style='width:140px; float:left; height:44px; margin-left:10px; border:1px solid #999; border-radius:2px'>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#009900; text-align:center; border:0px solid #999'>
        	<div style="margin-top:4px"><b>Caf&eacute; Conilon</b></div>
        </div>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#003466; text-align:center; border:0px solid #999; 
        border-radius:2px; background-color:#EEE'>
        	<div style="margin-top:4px"><b><?php echo "$quant_cafe_total_print Sc"; ?></b></div>
        </div>
    </div>


    <div id='centro' style='width:140px; float:left; height:44px; margin-left:20px; border:1px solid #999; border-radius:2px'>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#009900; text-align:center; border:0px solid #999'>
        	<div style="margin-top:4px"><b>Pimenta do Reino</b></div>
        </div>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#003466; text-align:center; border:0px solid #999; 
        border-radius:2px; background-color:#EEE'>
        	<div style="margin-top:4px"><b><?php echo "$quant_pimenta_total_print Kg"; ?></b></div>
        </div>
    </div>

    <div id='centro' style='width:140px; float:left; height:44px; margin-left:20px; border:1px solid #999; border-radius:2px'>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#009900; text-align:center; border:0px solid #999'>
        	<div style="margin-top:4px"><b>Cacau</b></div>
        </div>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#003466; text-align:center; border:0px solid #999; 
        border-radius:2px; background-color:#EEE'>
        	<div style="margin-top:4px"><b><?php echo "$quant_cacau_total_print Kg"; ?></b></div>
        </div>
    </div>

    <div id='centro' style='width:140px; float:left; height:44px; margin-left:20px; border:1px solid #999; border-radius:2px'>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#009900; text-align:center; border:0px solid #999'>
        	<div style="margin-top:4px"><b>Cravo da &Iacute;ndia</b></div>
        </div>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#003466; text-align:center; border:0px solid #999; 
        border-radius:2px; background-color:#EEE'>
        	<div style="margin-top:4px"><b><?php echo "$quant_cravo_total_print Kg"; ?></b></div>
        </div>
    </div>

    <div id='centro' style='width:140px; float:left; height:44px; margin-left:20px; border:1px solid #999; border-radius:2px'>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#009900; text-align:center; border:0px solid #999'>
        	<div style="margin-top:4px"><b>Caf&eacute; Ar&aacute;bica</b></div>
        </div>
        <div style='width:140px; height:22px; float:left; font-size:12px; color:#003466; text-align:center; border:0px solid #999; 
        border-radius:2px; background-color:#EEE'>
        	<div style="margin-top:4px"><b><?php echo "$quant_cafe_arabica_total_print Sc"; ?></b></div>
        </div>
    </div>



</div>








</div>
<!-- =========================================================================================================== -->


</div>


<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>