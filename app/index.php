<?php
require_once "functions.php";

$cnpjRaw = $_POST['cnpj'];
$cnpj = preg_replace('/[^0-9]/', '', (string)$cnpjRaw);

if (validatesCnpj($cnpj)) {
    $result = queryCnpj($cnpj);
    if (empty($result['detalhes'])) {
        $addressRaw = "{$result['estabelecimento']['tipo_logradouro']} {$result['estabelecimento']['logradouro']}, {$result['estabelecimento']['numero']}, {$result['estabelecimento']['complemento']}, {$result['estabelecimento']['bairro']}, {$result['estabelecimento']['cidade']['nome']}, {$result['estabelecimento']['estado']['sigla']}";
        $address = preg_replace('/[ ]{2,}/', ' ', (string)$addressRaw);

        $data = [
            'cnpj' => $cnpjRaw,
            'razao_social' => $result['razao_social'],
            'capital_social' => $result['capital_social'],
            'porte' => $result['porte']['descricao'],
            'endereco' => "$address",
        ];

        if (newEnterprise($data)) {
            if (sendEmail($data)) {
                echo json_encode([
                    'data' => [
                        'cnpj' => $cnpj,
                        'message' => 'CNPJ cadastrado com sucesso',
                        'email_enviado' => true
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'error' => [
                        'cnpj' => $cnpj,
                        'message' => 'Erro ao enviar e-mail'
                    ]
                ], JSON_UNESCAPED_UNICODE);

            }
        } else {
            echo json_encode([
                'error' => [
                    'cnpj' => $cnpj,
                    'message' => 'Erro ao cadastrar empresa no banco de dados'
                ]
            ], JSON_UNESCAPED_UNICODE);

        }
    } else {
        echo json_encode([
            'error' => [
                'cnpj' => $cnpj,
                'message' => 'CNPJ não cadastrado no cnpj.ws'
            ]
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        'error' => [
            'cnpj' => $cnpj,
            'message' => 'CNPJ inválido'
        ]
    ], JSON_UNESCAPED_UNICODE);
}
