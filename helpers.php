<?php

include "includes/valida_cookies.php";

/**
 * Classe Helper - Classe auxiliar responsável por prover funcionalidades comuns
 *
 * @author Borgus
 * @copyright Copyright (c) 2024, BORGUS Software
 */
class Helpers
{

    private const COM_ACENTOS = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú', '�');
    private const SEM_ACENTOS = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', '?');

    /*
        Verifica se o separador da data é "/" e troca por "-"
    */
    public static function ConverteData($data)
    {
        if (strstr($data, "/")) { //verifica se tem a barra
            $d = explode("/", $data); //tira a barra
            $rstData = "$d[2]-$d[1]-$d[0]"; //separa as datas $d[2] = ano $d[1] = mes etc...

            return $rstData;
        }
    }


    // ====== CONVERTE VALOR ==========================================================================================
    public static function ConverteValor($valor_x)
    {
        $valor_1 = str_replace("R$ ", "", $valor_x); //tira o símbolo
        $valor_2 = str_replace(".", "", $valor_1); //tira o ponto
        $valor_3 = str_replace(",", ".", $valor_2); //troca vírgula por ponto
        return $valor_3;
    }

    /*
     *  Altera caracteres da variável do valor do peso conforme parâmetros
     *  
     *  @param string @value O valor a ser alterado
     *  @param string @troca Valor da variável global @global[30] que contém a string
     *  "troca(this)"
     *  Quando não informado, a função não considerará a veriável global $config[30]
     *  @param array $dePor Array multi-dimencional contendo os caracteres a serem trocados.
     *  @return string Retorna a string alterada ou o valor passado quando alterações não forem encontradas.
     * 
    */

    // ====== CONVERTE PESO ==========================================================================================
    public static function ConvertePeso($value, $troca = '', $dePor = [])
    {
        global $config;
        $result = $value;

        if ($troca) {
            if ($troca == $config[30]) {
                $result = str_replace(",", ".", $result);
            } else {
                $result = str_replace(".", "", $result);
                $result = str_replace(",", "", $result);
            }
        } else if ($dePor == []) {
            $result = str_replace(".", "", $result);
            $result = str_replace(",", ".", $result);
        } else if (count($dePor) > 0) {
            foreach ($dePor as $changeFor) {
                $result = str_replace($changeFor[0], $changeFor[1], $result);
            }
        } 
        return $result;
    }
    
    // ========== ELIMINA MÁSCARAS CPF E CNPJ ================================================================
    public static function limpa_cpf_cnpj($limpa)
    {
        $limpa = trim($limpa);
        $limpa = str_replace(".", "", $limpa);
        $limpa = str_replace(",", "", $limpa);
        $limpa = str_replace("-", "", $limpa);
        $limpa = str_replace("/", "", $limpa);
        return $limpa;
    }

    // Retorna texto sem acentos
    public static function TiraAcentos(string $Texto): string
    {
        return str_replace(self::COM_ACENTOS, self::SEM_ACENTOS, $Texto);
    }

    public static function consoleLog($value)
    {
        $valuePress =  date("H:i:s") . '-' . $value;
        echo "<script>console.log('$valuePress')</script>";
    }

    public static function valorPorExtenso($value, $uppercase = 1)
    {
        if (strpos($value, ",") > 0) {
            $value = str_replace(".", "", $value);
            $value = str_replace(",", ".", $value);
        }

        $singular = ["centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"];
        $plural = ["centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"];

        $c = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
        $d = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
        $d10 = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"];
        $u = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];

        $z = 0;

        $value = number_format($value, 2, ".", ".");
        $integer = explode(".", $value);
        $cont = count($integer);

        for ($i = 0; $i < $cont; $i++)
            for ($ii = strlen($integer[$i]); $ii < 3; $ii++)
                $integer[$i] = "0" . $integer[$i];

        $fim = $cont - ($integer[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
            $value = $integer[$i];
            $rc = (($value > 100) && ($value < 200)) ? "cento" : $c[$value[0]];
            $rd = ($value[1] < 2) ? "" : $d[$value[1]];
            $ru = ($value > 0) ? (($value[1] == 1) ? $d10[$value[2]] : $u[$value[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                $ru) ? " e " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($value > 1 ? $plural[$t] : $singular[$t]) : "";
            if (
                $value == "000"
            )
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($integer[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                    ($integer[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if ($uppercase) {
            return strtoupper(trim($rt ? $rt : "zero"));
        } else {
            return trim($rt ? $rt : "zero");
        }
    }

    /* 
        @Trunca um valor decimal sem fazer arredondamento
        @value = Valor decimal a ser arredondado
        #decimals = Quantidade de casas decimais desejada
    */
    public static function SimpleTrunc($value, $decimals)
    {
        $base =  intval('1' . str_repeat("0", $decimals));
        $result = intval($value * $base) / $base;
        return $result;
    }

    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;

        /* TIPO DE MASCARA
            $cnpj = '11222333000199';
            $cpf = '00100200300';
            $cep = '08665110';
            $data = '10102010';
            $hora = '021050';

            echo mask($cnpj, '##.###.###/####-##').'<br>';
            echo mask($cpf, '###.###.###-##').'<br>';
            echo mask($cep, '#####-###').'<br>';
            echo mask($data, '##/##/####').'<br>';
            echo mask($data, '##/##/####').'<br>';
            echo mask($data, '[##][##][####]').'<br>';
            echo mask($data, '(##)(##)(####)').'<br>';
            echo mask($hora, 'Agora são ## horas ## minutos e ## segundos').'<br>';
            echo mask($hora, '##:##:##');
        */
    }


    
}
