<?php

Class EmpresaController
{
    /**
     * Serviço responsável por inserir uma empresa
     * 
     * @param $cnpj string contendo cnpj da empresa a ser inserida
     */
    public function inserirEmpresa($cnpj)
    {
        $mensagemErro = [
            'erro' => [
                'cnpj' => $cnpj,
                'messagem' => 'CNPJ Inválido'
            ]
        ];

        // Elimina possivel mascara
        $numeroCnpj = preg_replace("/[^0-9]/", "", $cnpj);
        
        if(mb_strlen($numeroCnpj) == 14){
            $service = new EmpresaService();
            $result = $service->consultarCnpj($numeroCnpj);

            if($result['cnpj_raiz']){
                $empresa = $service->inserirEmpresa($result);
                
                if($empresa){
                    $destinatario = 'exemplo@envio.com.br';
                    $assunto = "Nova empresa cadastrada {$empresa->getRazaoSocial()}";

                    $email = $service->enviarEmail(['destinatario' => $destinatario, 'assunto' => $assunto, 'mensagem' => '']);

                    return json_encode([
                        'data' => [
                            'cnpj' => $cnpj,
                            'mensagem' => 'CNPJ Cadastrado com sucesso',
                            'email_enviado' => $email
                        ]
                    ]);
                } 
                $mensagemErro['erro']['messagem'] = 'Não foi possível inserir o registro';

            } else {
                $mensagemErro['erro']['messagem'] = $result['detalhes'];
            }
        }

        return json_encode($mensagemErro);
    }
}

Class EmpresaService
{
    /**
     * Serviço responsável por consulta um CNPJ na API do cnjp.ws
     * 
     * @param $cnpj string contendo o cnpj
     */
    public function consultarCnpj($cnpj)
    {
        $url = "https://comercial.cnpj.ws/cnpj/$cnpj";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Serviço responsável por inserir uma empresa
     * 
     * @param $dadosEmpresa objeto contendo os dados da empresa
     */
    public function inserirEmpresa($dadosEmpresa): Empresa|bool
    {
        $estabelecimento = $dadosEmpresa['estabelecimento'];
        $endereco = "$dadosEmpresa[tipo_logradouro] $dadosEmpresa[logradouro] n° $dadosEmpresa[numero],
            $dadosEmpresa[bairro] - $dadosEmpresa[cidade][nome], $dadosEmpresa[estado][sigla]"
        ;

        $empresa = new Empresa();
        $empresa->setCnpj($estabelecimento['cnpj']);
        $empresa->setRazaoSocial($dadosEmpresa['razao_social']);
        $empresa->setCapitalSocial($dadosEmpresa['capital_social']);
        $empresa->setPorte($dadosEmpresa['porte']['descricao']);
        $empresa->setEndereco($endereco);

        $repository = new EmpresaRepository();
        return $repository->inserirEmpresa($empresa);
    }

    /**
     * Serviço responsável por enviar email
     * 
     * @param $dadosEmail array associativo contendo as strings $destinatario, $assunto e $mensagem
     */
    public function enviarEmail($dadosEmail): bool
    {
        ['destinatario' => $destinatario, 'assunto' => $assunto, 'mensagem' => $mensagem] = $dadosEmail;

        return mail($destinatario, $assunto, $mensagem);
    }
}

Class EmpresaRepository
{
    public function inserirEmpresa(Empresa $empresa)
    {
        $conn = mysqli_connect(/** Dados para conexão com Banco */);

        $sql = "INSERT INTO Empresa
        (
            COD_CNPJ,
            STR_RAZAO_SOCIAL,
            STR_CAPITAL_SOCIAL,
            STR_PORTE,
            STR_ENDERECO
        ) VALUES (
            '{$empresa->getCnpj()}',
            '{$empresa->getRazaoSocial()}',
            '{$empresa->getCapitalSocial()}',
            '{$empresa->getPorte()}',
            '{$empresa->getEndereco()}'
        )";

        return mysqli_query($conn, $sql); 
    }

}

Class Empresa
{
    protected $cnpj;
    protected $razaoSocial;
    protected $capitalSocial;
    protected $porte;
    protected $endereco;

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function setCnpj($cnpj)
    {
        return $this->cnpj = $cnpj;
    }

    public function getRazaoSocial()
    {
        return $this->razaoSocial;
    }

    public function setRazaoSocial($razaoSocial)
    {
        return $this->razaoSocial = $razaoSocial;
    }

    public function getCapitalSocial()
    {
        return $this->capitalSocial;
    }

    public function setCapitalSocial($capitalSocial)
    {
        return $this->capitalSocial = $capitalSocial;
    }

    public function getPorte()
    {
        return $this->porte;
    }

    public function setPorte($porte)
    {
        return $this->porte = $porte;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        return $this->endereco = $endereco;
    }
}

