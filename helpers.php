<?php



/**
 * Classe Helper - Classe auxiliar responsável por prover funcionalidades comuns
 *
 * @author Borgus
 * @copyright Copyright (c) 2024, BORGUS Software
 */
class Helpers
{

    private const COM_ACENTOS = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú','�');

    private const  SEM_ACENTOS = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U','?');

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

    // ====== CONVERTE PESO ==========================================================================================
    public static function ConvertePeso($peso_x)
    {
        if ($GLOBALS["estoqueMascara"] == "troca(this)") {
            return str_replace(",", ".", $peso_x);
        } else {
            return str_replace(",", "", str_replace(".", "", $peso_x));
        }
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

    public static function consoleLog($value) {
        $valuePress =  date("H:i:s") . '-' . $value;
        echo "<script>console.log('$valuePress')</script>";
    }
}
