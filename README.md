# tracking-php


Carregando

```
<?php
require './Tracking.php';

Definindo o objeto e recendo o resultado como JSON

```
$track = new Tracking( 'DY277347772BR' );
echo $track->asJson();

Resultado como Array

```
echo $track->asArray();

Resultado como XML

```
echo $track->asXml();

Pesquisando um objeto recebido via POST

```
echo (new Tracking( $_POST[ 'tracking_number' ] ))->asJson();


Pesquisando um objeto recebido via GET

```
echo (new Tracking( $_GET[ 'tracking_number' ] ))->asJson();


O método Tracking faz a validação do número de rastreamento antes da consulta, então, caso ele seja inválido, um erro será retornado imediatamente, evitando a consulta


