<?php
/**
 * Convert an object to an array
 *
 * @param $data
 * @return array
 */
function convertObjectToArray($data)
{

    if (is_object($data)) {
        $data = get_object_vars($data);
    }
    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    } else {
        return $data;
    }
}

/**
 * Validates CNPJ
 *
 * @param $cnpj
 * @return bool
 */
function validatesCnpj($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);

    if (strlen($cnpj) != 14) {
        return false;
    }

    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    // Validates first check digit
    for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
        $sum += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $rest = $sum % 11;

    if ($cnpj[12] != ($rest < 2 ? 0 : 11 - $rest)) {
        return false;
    }

    // Validates second check digit
    for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
        $sum += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $rest = $sum % 11;

    return $cnpj[13] == ($rest < 2 ? 0 : 11 - $rest);
}


/**
 * Query a CNPJ in an open api to check if it's a valid or invalid document
 *
 * @param string $cnpj
 * @return array
 */
function queryCnpj(string $cnpj): array
{
    $url = "https://publica.cnpj.ws/cnpj/{$cnpj}";
    $consult = curl_init($url);
    curl_setopt($consult, CURLOPT_URL, $url);
    curl_setopt($consult, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($consult));
    curl_close($consult);
    return convertObjectToArray($result);
}

/**
 * Send an e-mail message
 * @param $data
 * @return bool
 */
function sendEmail($data): bool
{
    return true;
}

/**
 * Save the data as new Enterprise in the database
 * @param $data
 * @return bool
 */
function newEnterprise($data): bool
{
    return true;
}
