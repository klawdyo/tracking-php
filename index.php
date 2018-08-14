<?php

class Tracking {
  public $trackingNumber = '';
	
  public $html = '';
	
  public function __construct ( $trackingNumber ) {
//     pr('dentro do construcot');
//     pr($trackingNumber);
    $this->trackingNumber = $trackingNumber;
  }
  
  public function request () {
//     pr( $this->trackingNumber );
//     pr('Dentro do request()');
    $post = ['objetos' => $this->trackingNumber, 'btnPesq' => 'Buscar'];
//     pr($post);
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
//     pr( $this->html );
    return $this;
  }
	
  public function parse() {
      pr('dentro do parse()');
//       pr( $this->html );

      $dom = new DOMDocument();
      @$dom->loadHTML($html);
      $result = [];
      $i = 0;

      foreach($dom->getElementsByTagName('tr') as $row) {

        $j = 0;
        foreach( $row->getElementsByTagName('td') as $cell ) {
          $result[$i][($j === 0 ? 'detail' : 'description' )] = $cell->textContent;
          $j++;
        }//foreach

        $i++;
     }//foreach   
	  pr($result);
  } // track		
} // class

$track = new Tracking( 'DY277947771BR' );
$track->request()->parse();

function pr($e){
  echo '<pre>', print_r($e, true), '</pre>';
}
