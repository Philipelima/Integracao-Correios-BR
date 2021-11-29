<?php

namespace App\Services;

class Correios
{
    /**
     * modalidade de Servicos dos Correios
     * 
     * @var string 
     */
    const SEDEX = "04014";
    const PAC = "04510";
    const SEDEX_12 = "04782";
    const SEDEX_10 = "04790";
    const SEDEX_HOJE = "04804";

    /**
     * 
     * Tipos de Pacotes 
     * @var int 
     */
    const CAIXA_PACOTE = 1;
    const ROLO_PRISMA = 2;
    const ENVELOPE = 3;



    const WS_URL = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";

    /**
     * Credenciais para empresas com contrato
     * @var string
     */
    private $nCdEmpresa;
    private $sDsSenha;

    /**
     * Dados enviados via Requisição GET para o WebService dos correios
     * @var array
     */
    private $dadosObrigatorios = [];

    /**
     * tipo de retorno da consulta
     * @var string
     */
    private $retorno;


    public function __construct($nCdEmpresa = null, $sDsSenha = null)
    {
        if ($nCdEmpresa && $sDsSenha) {
            $this->setCredenciais($nCdEmpresa, $sDsSenha);
        }
    }

    /**
     * Função publica responsavel pela consulta do frete
     * @param array $dados
     * @param string $retorno
     */
    public function consultarFrete(array $dados = [], $retorno = "xml")
    {
        $this->setDadosObrigatorios($dados);
        $this->setRetorno($retorno);
        return $this->consultar();
    }


    /**
     * Função responsavel por realizar a consulta na Url completa
     * @return SimpleXMLElement Object
     */
    private function consultar()
    {
        $url = $this->getCompleteUrl();
        $response = $this->curlRequest($url);

        return $this->responseTratada($response)->cServico;
    }

    /**
     * 
     * função responsavel por definir as credenciais da empresa, caso ela tenha contrato com os Correios
     * @param string $nCdEmpresa 
     * @param string $sDsSenha
     * 
     */
    private function setCredenciais($nCdEmpresa, $sDsSenha)
    {
        $this->nCdEmpresa = $nCdEmpresa;
        $this->sDsSenha = $sDsSenha;
    }


    /**
     * Configura o tipo de retorno que deve ser dado pela consulta
     * @param string $tipoRetorno (XML ou JSON)
     */
    private function setRetorno($tipoRetorno)
    {
        $this->retorno = $tipoRetorno;
    }


    /**
     * Seta todos os dados que são obrigatorios para que a requisição funcione
     * @param array $dados 
     */

    private function setDadosObrigatorios($dados)
    {

        $this->dadosObrigatorios['nCdEmpresa']            =       $this->nCdEmpresa ? $this->nCdEmpresa  :   "";
        $this->dadosObrigatorios['sDsSenha']              =       $this->sDsSenha   ? $this->sDsSenha    :   '';
        $this->dadosObrigatorios['nCdServico']            =       $dados['servico'];
        $this->dadosObrigatorios['sCepOrigem']            =       $dados['cepOrigem'];
        $this->dadosObrigatorios['sCepDestino']           =       $dados['cepDestino'];
        $this->dadosObrigatorios['nVlPeso']               =       $dados['pesoEncomenda'];
        $this->dadosObrigatorios['nCdFormato']            =       $dados['formatoEncomenda'];
        $this->dadosObrigatorios['nVlComprimento']        =       $dados['comprimentoEncomenda'];
        $this->dadosObrigatorios['nVlAltura']             =       $dados['alturaEncomenda'];
        $this->dadosObrigatorios['nVlLargura']            =       $dados['larguraEncomenda'];
        $this->dadosObrigatorios['nVlDiametro']           =       $dados['diametroEncomenda'];
        $this->dadosObrigatorios['sCdMaoPropria']         =       $dados['maoPropria']        ?  'S'  :  'N';
        $this->dadosObrigatorios['nVlValorDeclarado']     =       $dados['valorDeclarado'];
        $this->dadosObrigatorios['sCdAvisoRecebimento']   =       $dados['avisoRecebimento']  ?  'S'  :  'N';
        $this->dadosObrigatorios['StrRetorno']            =       'xml';
    }


    /**
     * Retorna quais dados são obrigatorios para a Requisição da API dos correios
     * @return array
     */

    private function getDadosObrigatorios()
    {
        return $this->dadosObrigatorios;
    }


    /**
     * Formata a url pela qual a Classe executa a requisição a API dos Correios
     * @return string
     */
    private function getCompleteUrl()
    {
        $uri    =   self::WS_URL;
        $parametros = $this->getDadosObrigatorios();
        $query  =   http_build_query($parametros);

        return $uri.$query;
    }


    /**
     * Executa a requisição na api dos Correios via CURL
     * @return string
     */
    private function curlRequest($url)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'

        ]);


        $response = curl_exec($curl);
        curl_close($curl);

        return strlen($response) ? $response : null;
    }


    /**
     * Trata a resposta da requisição de string para Objeto
     * @return object
     */
    private function responseTratada($response)
    {
        switch ($this->retorno) {
            case 'xml':
                return  simplexml_load_string($response);

            default:
                return simplexml_load_string($response);
                break;
        }
    }
}
