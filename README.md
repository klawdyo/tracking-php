# tracking-php

## Classe de rastreamento de objetos registrados dos Correios com cURL, sem o uso do webservice.

### Classe para fins de estudo. Não substitui o uso do webservice oficial. Use por sua conta e risco.

## Importando o o arquivo

```php
require './Tracking.php';
```

## Definindo o objeto passando o número de registro no construtor da classe

```php
$track = new Tracking( 'DY277347772BR' );
```

## Resultado como JSON

```php
echo $track->asJson();
```


## Resultado como Array

```php
echo $track->asArray();
```

## Resultado como XML

```php
echo $track->asXml();
```

## Pesquisando um objeto recebido via POST

```php
$track = new Tracking( $_POST[ 'tracking_number' ] );
echo $track->asJson();
```

## Pesquisando um objeto recebido via GET

```
GET seusite.com/tracking?tracking_number=AB123456789BR
```

```php
$track = new Tracking( $_GET[ 'tracking_number' ] );
echo $track->asJson();
```

## Validação

### Tracking faz a validação do número de rastreamento antes da consulta, então, caso ele seja inválido, um erro será retornado imediatamente, evitando a consulta

Mas caso você deseje consultar se um objeto é válido:

```php
Tracking::isValid('AB123456789BR');
```


