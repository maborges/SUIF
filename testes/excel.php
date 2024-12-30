<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
    border-color: #D3D3D3;
  }

  label {
    background-color: #8B4513;
    color: #F0F8FF;
    
  }

  .texto-centralizado {
    text-align: center;
  }

  .texto-direita {
    text-align: right;
  }

  .texto-esquerda {
    text-align: left;
  }
</style>

<?php

include_once("../includes/config.php");
include_once("../includes/conecta_bd.php");
include_once("../includes/valida_cookies.php");
include_once("../sankhya/Sankhya.php");
include_once("../helpers.php");


$dadosXls = "";

$titulo  = 'Planilha de Averbação';
$empresa = 'GRANCAFÉ COM. IMP. E EXP. DE CAFÉ LTDA';

//declaramos uma variavel para monstarmos a tabela 
$dadosXls .= "  <table>
                  <tr>
                    <td class='texto-centralizado' colspan='14'><h3>$titulo</h3></td>
                  </tr>

                  <tr>
                    <th class='label texto-direita'>Nome do Segurado:</th>
                    <td class='label texto-esquerda' colspan='13'><strong>$empresa</strong></td>
                  </tr>  

                  <tr>
                    <th class='label texto-direita'>CNPJ:</th>
                    <td class='label texto-esquerda' colspan='13'><strong>02.239.346/0001-72</strong></td>
                  </tr>
                </table>
                <br>";



//declaramos uma variavel para monstarmos a tabela 
$dadosXls .= " <table class='table table-hover table-striped table-sm'>";
$dadosXls .= " <thead>";
$dadosXls .= " <tr>";
$dadosXls .= " <th>Apólice</th>";
$dadosXls .= " <th>SubGrupo</th>";
$dadosXls .= " <th>Série</th>";
$dadosXls .= " <th>Documento</th>";
$dadosXls .= " <th>Data de Saída</th>";
$dadosXls .= " <th>Veículo</th>";
$dadosXls .= " <th>Meio de Transporte</th>";
$dadosXls .= " <th>Estado de Origem</th>";
$dadosXls .= " <th>Estado de Destino</th>";
$dadosXls .= " <th>Percurso Urbano</th>";
$dadosXls .= " <th>Valor Segurado (R$)</th>";
$dadosXls .= " <th>Mercadoria</th>";
$dadosXls .= " <th>Despesa</th>";
$dadosXls .= " <th>Frete</th>";
$dadosXls .= " </tr>";
$dadosXls .= " </thead> ";

// $sql = "SET CHARACTER SET 'UTF-8'";
// $result = Sankhya::queryExecuteDB($sql);

$sql =  "select a.numero_apolice 'Apolice', 
                '' SubGrupo,
                b.serie_nf Serie,
                b.numero_nf Documento,
                DATE_FORMAT(c.data,'%d/%m/%Y') 'DataSaida',
                c.placa_veiculo 'Veiculo',
                'TERRESTRE' as 'MeioTransporte',
                d.estado as 'EstadoOrigem',
                a.uf as 'EstadoDestino',
                '' as 'PercursoUrano',
                b.valor_total 'ValorSegurado',
                c.produto 'Mercadoria',
                '' Despesas,
                '' Frete
           from configuracoes x,
                filiais a,
                nota_fiscal_entrada b,
                estoque c,
                cadastro_pessoa d ,
                filial_veiculo e,
                averbacao_vigencia f
          where a.codigo = 1
            and b.estado_registro = 'ATIVO'
            and c.numero_romaneio = b.codigo_romaneio  
            and c.estado_registro != 'EXCLUIDO'
            and c.data between '2024-04-01' 
                           and '2024-04-30'
            and d.codigo_pessoa = c.cod_produto 
            and e.filial = a.codigo 
            and e.placa  = replace(c.placa_veiculo,' ', '')
            and e.situacao = 'ATV'
            and f.filial_veiculo = e.codigo
            and f.situacao = 'ATV'
            and c.data >= f.data_inicio 
            and c.data <= f.data_fim";

$result = Sankhya::queryExecuteDB($sql);

// varremos o array com o foreach para pegar os dados 
foreach ($result['rows'] as $res) {
  $produto = $res[11];
  $dadosXls .= " <tr>";
  $dadosXls .= " <td>{$res[0]}</td>";
  $dadosXls .= " <td>{$res[1]}</td>";
  $dadosXls .= " <td>{$res[2]}</td>";
  $dadosXls .= " <td>{$res[3]}</td>";
  $dadosXls .= " <td>{$res[4]}</td>";
  $dadosXls .= " <td>{$res[5]}</td>";
  $dadosXls .= " <td>{$res[6]}</td>";
  $dadosXls .= " <td>{$res[7]}</td>";
  $dadosXls .= " <td>{$res[8]}</td>";
  $dadosXls .= " <td>{$res[9]}</td>";
  $dadosXls .= " <td>" . number_format($res[10], 2, ',', '.') . "</td>";
  $dadosXls .= " <td>$produto</td>";
  $dadosXls .= " <td>{$res[12]}</td>";
  $dadosXls .= " <td>{$res[13]}</td>";
  $dadosXls .= " </tr>";
}

$dadosXls .= " </table>";

// echo $dadosXls;

// Definimos o nome do arquivo que será exportado 
$arquivo = "Seguradora.xls";

// Configurações header para forçar o download 
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="' . $arquivo . '"');
header('Cache-Control: max-age=0');

// Se for o IE9, isso talvez seja necessário 
header('Cache-Control: max-age=1');
// Envia o conteúdo do arquivo 

echo $dadosXls;
//exit; 
?>