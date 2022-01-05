# Integração Correios BR

Esse é um exemplo de integração com a API dos Correios

A classe desenvolvida aqui  pode facilmente ser integrada ao seu código e facilitar suas consultas de Frete no serviço dos Correios:

```
https://github.com/Philipelima/Integracao-Correios-BR.git
```

A ideia é tornar a consulta mais clara e fácil de ser executada.

### Requisitos 

- Php 7.4 ou superior
- php-curl 
- php7.4-simplexml


### Resolvendo Possiveis Problemas:


1) **call to Undefined curl_init()** 

esse erro geralmenta ocorre quando a biblioteca php-curl está desinstalada ou dasabilitada.

Existem duas formas possiveis de resolver esse problema, a primeira é indo até o arquivo **php.ini**


procure pela seguinte linha:
```ini
;extension=php_curl.dll
```
remova o **;** do inicio.

```ini
extension=php_curl.dll
```

reinicie o seu servidor apache, se tudo der certo, agora o curl_init() está funcionando.

A segunda é caso você esteja no Ubuntu:

em seu terminal rode o seguinte comando: 

```
	sudo apt-get install php-curl
```
reinicie o seu servidor apache, se tudo der certo, agora o curl_init() está funcionando.

2) **call to Undefined simplexml_load_string()**
 
 esse erro geralmente ocorre pelo mesmo motivo da curl_init().

então para resolvermos podemos primeiramente tentar  o arquivo **php.ini**

descomentando a seguinte linha:

```ini
;extension=php_xmlrpc.dll
```
```ini
extension=php_xmlrpc.dll
```

reinicie seu servidor apache, se tudo der certo, agora a função está funcionando.

A segunda forma é pelo terminal:

```
sudo apt-get install php7.*-simplexml
```


## Descrição de Parâmetros


| Parâmetro       | Descrição   |
| ------------- |:-------------:| 
| servico  (string)    | constanste da modalidade de serviço | 
| cepOrigem (string)      | Cep da onde sairá a encomenda (sem pontuação)     | 
| cepDestino (string) | Cep do destino para onde a encomenda será enviada    |
| pesoEncomenda (string)|  peso da encomenda |
| formatoEncomenda (int) | constante do formato da Encomenda |
| comprimentoEncomenda (decimal) | comprimento da encomenda |
|alturaEncomenda (decimal)| altura da encomenda |
|larguraEncomenda (decimal)| largura da encomenda |
|diametroEncomenda (decimal)| diametro da encomenda|
|maoPropria (bool)| encomenda será entregue com o serviço  
adicional mão própria.  (true ou false)|
|valorDeclarado (decimal)| valor declarado da encomenda (se não houver envie 0)|
|avisoRecebimento  (bool)| se a encomenda será entregue com o serviço  
adicional aviso de recebimento (true ou false)|


### Descrição de Serviços dos Correios

| Serviço       | Constante   | 
| ------------- |:-------------:| 
| Sedex | Correios::SEDEX |
| PAC |Correios::PAC|
| Sedex 12|Correios::SEDEX_12|
| Sedex 10| Correios::SEDEX_10|
|Sedex hoje| Correios::SEDEX_HOJE|


### Descrição de Formatos de Encomendas dos Correios
| Formato      | Constante   | 
| ------------- |:-------------:| 
|formato Caixa/Pacote| Correios::CAIXA_PACOTE|
|formato Rolo/Prisma | Correios::ROLO_PRISMA|
|formato Envelope| Correios::ENVELOPE|


## Usando a Classe no Código


```php
require_once("./vendor/autoload.php");

use App\Services\Correios;

/*
 Alimentando Array com informações sobre o Envio
*/
$dadosConsultaFrete = [

	'servico' => Correios::SEDEX,
	'cepOrigem' => '47813282',
	'cepDestino' => '05861900',
	'pesoEncomenda' => 1,
	'formatoEncomenda' => Correios::CAIXA_PACOTE,
	'comprimentoEncomenda' => 15,
	'alturaEncomenda' => 15,
	'larguraEncomenda' => 15,
	'diametroEncomenda' => 41,
	'maoPropria' => true,
	'valorDeclarado' => 0,
	'avisoRecebimento' => false
	
];

  
//instanciando a classe e consultando o frete e taxas.
$calculoFrete = (new  Correios)->consultarFrete($dadosConsultaFrete);
	
```

Caso a empresa tenha contrato com os Correios, deverá ser informado mais dois paramêtros na instancia da classe Correios():

o nCdEmpresa - Seu código administrativo junto à ECT

a sDsSenha - sua senha para acesso ao serviço  (A senha inicial corresponde aos  
8 primeiros dígitos do CNPJ informado no contrato.)

```php
$nCdEmpresa = "";
$sDsSenha = "";

$calculoFrete = (new  Correios($nCdEmpresa, $sDsSenha))->consultarFrete($dadosConsultaFrete);
```

### Retorno da Consulta

O retorno Obtido pela consulta é um XML, no entanto, como ele já é tratado dentro da Classe Correios, o resultado final é um objeto da classe SimpleXMLElement:

```php
SimpleXMLElement Object(
	
    [Codigo] => 04510
    [Valor] => 87,00
    [PrazoEntrega] => 10
    [ValorSemAdicionais] => 79,50
    [ValorMaoPropria] => 7,50
    [ValorAvisoRecebimento] => 0,00
    [ValorValorDeclarado] => 0,00
    [EntregaDomiciliar] => S
    [EntregaSabado] => N
    [obsFim] => SimpleXMLElement Object
        (
        )

    [Erro] => 0
    [MsgErro] => SimpleXMLElement Object
        (
        )
)
```

### TODO List

estarei listando aqui coisas que pretendo melhorar em breve:

1) fornecer dados em json;
2) pesquisa de cep integrada;


#### Referências:

Documentação da Api dos Correios:

https://www.correios.com.br/atendimento/ferramentas/sistemas/arquivos/manual-de-implementacao-do-calculo-remoto-de-precos-e-prazos/view

Video Aula WDEV - Integração com a API dos Correios:

https://www.youtube.com/watch?v=TDk9PWA1RoQ
