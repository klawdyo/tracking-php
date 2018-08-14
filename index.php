<?php

/**
 * Este arquivo é responsável por pegar as informações da url e realizar a consulta
 */

// Importa o arquivo
require './Tracking.php';

// Se o número de rastreamento foi informado na url
if ( isset( $_GET['tracking_number'] ) ) {
  // Pega o número do get
  $trackingNumber = $_GET['tracking_number'];
  // Gera o resultado
  $track = new Tracking( $trackingNumber );
  // Imprime na tela
  echo $track->asJson();
} else {
  echo json_encode( [
    'lastStatus' => 'Número do Objeto não Informado',
    'status' => 'invalidTrackingNumber',
    'isDelivered' => 0,
    'events' => []
  ] );
}