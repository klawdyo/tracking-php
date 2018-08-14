<?php
  header('Content-Type: text/html; charset=utf-8');

  require './TrackingValidation.php';

  class Tracking {
    public $trackingNumber = '';
    
    public $html = '';

    public $array = [];

    public $error = [];
    
    public function __construct ( $trackingNumber ) {
      if ( !Tracking::isValid( $trackingNumber ) ) {
        $this->error = [
          'lastStatus' => 'Código de Objeto Inválido',
          'status' => 'invalidTrackingNumber',
          'isDelivered' => 0,
          'events' => []
        ];
      } else {
        $this->trackingNumber = $trackingNumber;
      }
    }
    
    /**
     * Verifica se um número passado é válido
     */
    public static function isValid ( $trackingNumber ) {
      return TrackingValidation::isValid( $trackingNumber );
    }

    /**
     * Devolve os resultados como array.
     * É a base do retorno. Os métodos asJson e asXml usam asArray
     * 
     * @return {Array} array formatado contendo os resultados da busca
     */
    public function asArray () {
      // Se existe erro
      if ( !empty( $this->error ) ) {
        return $this->error;
      }

      // Faz o parseamento do HTML da página
      $array = $this->request()->parse( $this->html );

      // Se o array estiver vazio, não encontrou nada
      if ( empty( $array ) ) {
        return [
          'lastStatus' => 'Objeto não encontrado',
          'status' => 'notFound',
          'isDelivered' => 0,
          'events' => []
        ];
      }

      // Array vazio dos resultados
      $result = [];

      foreach ( $array as $row ) {
        // Primeira expressão regular para pegar data, hora e a cidade
        $rgx1 = '/(?<date>[0-9]{2}\/[0-9]{2}\/[0-9]{4})\s*(?<time>[0-9]{2}\:[0-9]{2})\s*(?<city>.*)/im';
        preg_match( $rgx1, $row['detail'],$datimeDetails );


        // Segunda expressão regular regularizar a descrição
        $rgx2 = '/(\s{2,}|\n})/im';
        $descrition = preg_replace( $rgx2, ' ', $row['description'] );

        // Alimenta o array
        $result[] = [
          'date' => $datimeDetails[ 'date' ],
          'time' => $datimeDetails[ 'time' ],
          'unit' => trim( $datimeDetails['city' ] ),
          'observation' => trim( $descrition ),
        ];
      }
      
      // Exibe o último histórico
      $last = count( $result ) > 0 ? $result[ 0 ][ 'observation' ] : 'Objeto não movimentado';

      // Pega o status do último evento
      $status = $this->status( $result[ 0 ][ 'observation' ] );

      // Retorna
      return [
        'status' => $status,
        'lastStatus' => $last,
        'lastUpdate' => "{$result[ 0 ][ 'date' ]} {$result[ 0 ][ 'time' ]}",
        'isDelivered' => $this->isDelivered( $result ),
        'events' => $result,
      ];
    }

    /**
     * Retorna o resultado como JSON
     * 
     * @return {String} Retorna uma string formatada como JSON
     */
    public function asJson () {
      return json_encode( $this->asArray() );
    }

    /**
     * @todo
     * Retorna o resultado como XML
     * 
     * @return {String} Retorna um string formatada como XML
     */
    public function asXml () {
      //to do
    }

    /***************************************************************************************
     * 
     *    MÉTODOS PRIVADOS 
     * 
     */


    /**
     * Passa o array inteiro de resultados para que ele possa avaliar somente o último evento
     * retorna true ou falso
     * 
     * @param {Array} $result Array com os resultados da busca
     * @return {Boolean} Retorna true or false, dependendo se o pacote foi ou não entregue
     */
    private function isDelivered ( $result ) {
      if ( isset( $result[0] ) ) {
        return in_array( $result[0]['observation'], [ 'delivered', 'returned' ] );
      }

      return false;
    }

    /**
     * Faz a requisição para a página dos correios que contém o resultado da página
     */
    private function request () {
      
      $post = ['objetos' => $this->trackingNumber, 'btnPesq' => 'Buscar'];
      $ch = curl_init('http://www2.correios.com.br/sistemas/rastreamento/resultado.cfm?');
      
      curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
              'Referer: http://www2.correios.com.br/sistemas/rastreamento/',
              'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
          ],
          CURLOPT_POSTFIELDS => $post,
          CURLOPT_ENCODING => '',
          CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS
      ]);
      
      $this->html = curl_exec($ch);
      return $this;
    }
    
    /**
     * Recebe o html da página e devolve só as linhas da tabela que realmente interessam
     * para a continuidade da requisição
     */
    private function parse( $html ) {
      $dom = new DOMDocument();
      @$dom->loadHTML($html);
      $result = [];
    
      foreach($dom->getElementsByTagName('tr') as $i => $row) {
        foreach( $row->getElementsByTagName('td') as $j => $cell ) {
          $result[$i][($j === 0 ? 'detail' : 'description' )] = $cell->textContent;
        }
      }
      
      return $result;
    }

    /**
     * Avalia o comentário para retornar um possível agrupamento de mensagem
     * O retornos possívels são: delivered, returned, forwarded, outForDelivery, deliveryError, notFound
     */
    private function status( $phrase ) {
      $rgx = '/((?<delivered>entregue)|(?<returned>devolvido)|(?<forwarded>encaminhado)|(?<outForDelivery>saiu para entrega)|(?<deliveryError>A entrega não pode ser efetuada))/im';
    
      preg_match( $rgx, $phrase, $matches );
    
      $result = null;
    
      foreach( $matches as $key => $response ) {
        if ( $response !== '' && !is_numeric( $key ) ) {
          return $key;
        }
      }
    
      return 'other';
    }
  } 