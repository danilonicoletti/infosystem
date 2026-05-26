<?php

function formata_cpf($cpf_cnpj)
{

    $bloco_1 = substr($cpf_cnpj, 0, 3);
    $bloco_2 = substr($cpf_cnpj, 3, 3);
    $bloco_3 = substr($cpf_cnpj, 6, 3);
    $dig_verificador = substr($cpf_cnpj, -2);
    $cpf_cnpj_formatado = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;

    return $cpf_cnpj_formatado;
}
function limita_caracteres($texto, $limite, $quebra = true)
{
    $tamanho = strlen($texto);
    if ($tamanho <= $limite) { //Verifica se o tamanho do texto é menor ou igual ao limite
        $novo_texto = $texto;
    } else { // Se o tamanho do texto for maior que o limite
        if ($quebra == true) { // Verifica a opção de quebrar o texto
            $novo_texto = trim(substr($texto, 0, $limite)) . "...";
        } else { // Se não, corta $texto na última palavra antes do limite
            $ultimo_espaco = strrpos(substr($texto, 0, $limite), " "); // Localiza o último espaço antes de $limite
            $novo_texto = trim(substr($texto, 0, $ultimo_espaco)) . "..."; // Corta o $texto até a posição localizada
        }
    }
    return $novo_texto; // Retorna o valor formatado
}
function my_Sql_regcase($str)
{
    $res = "";
    $chars = str_split($str);
    foreach ($chars as $char) {
        if (preg_match("/[A-Za-z]/", $char))
            $res .= "[" . mb_strtoupper($char, 'UTF-8') . mb_strtolower($char, 'UTF-8') . "]";
        else
            $res .= $char;
    }
    return $res;
}
function anti_injection($sql)
{
    $sql = preg_replace(my_Sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $sql);
    $sql = trim($sql); //limpa espaços vazio
    $sql = strip_tags($sql); //tira tags html e php
    $sql = addslashes($sql); //Adiciona barras invertidas a uma string
    return $sql;
}
function limpaCar($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    $valor = str_replace("!", "", $valor);
    $valor = str_replace("?", "", $valor);
    $valor = str_replace(" ", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    return $valor;
}
function limpaCarSpace($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("/", " ", $valor);
    $valor = str_replace("!", "", $valor);
    $valor = str_replace("?", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    return $valor;
}
function tirarAcentos($string)
{
    return limpaCar(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string));
}
function sql_debug($sql_string, array $params = null)
{
    if (!empty($params)) {
        $indexed = $params == array_values($params);
        foreach ($params as $k => $v) {
            if (is_object($v)) {
                if ($v instanceof \DateTime) $v = $v->format('Y-m-d H:i:s');
                else continue;
            } elseif (is_string($v)) $v = "'$v'";
            elseif ($v === null) $v = 'NULL';
            elseif (is_array($v)) $v = implode(',', $v);

            if ($indexed) {
                $sql_string = preg_replace('/\?/', $v, $sql_string, 1);
            } else {
                if ($k[0] != ':') $k = ':' . $k; //add leading colon if it was left out
                $sql_string = str_replace($k, $v, $sql_string);
            }
        }
    }
    return $sql_string;
}
function pdo_debugStrParams($stmt)
{
    ob_start();
    $stmt->debugDumpParams();
    $r = ob_get_contents();
    ob_end_clean();
    return $r;
}
function debugPDO($raw_sql, $parameters)
{
    $keys = array();
    $values = $parameters;

    foreach ($parameters as $key => $value) {

        // check if named parameters (':param') or anonymous parameters ('?') are used
        if (is_string($key)) {
            $keys[] = '/' . $key . '/';
        } else {
            $keys[] = '/[?]/';
        }

        // bring parameter into human-readable format
        if (is_string($value)) {
            $values[$key] = "'" . $value . "'";
        } elseif (is_array($value)) {
            $values[$key] = implode(',', $value);
        } elseif (is_null($value)) {
            $values[$key] = 'NULL';
        }
    }

    $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

    return $raw_sql;
}
function round_up($value, $places)
{
    $mult = pow(10, abs($places));
    return $places < 0 ?
        ceil($value / $mult) * $mult :
        ceil($value * $mult) / $mult;
}
