<?php

class Tracking {
  public $trackingNumber = '';
   public functin __constructor ( $trackingNumber ) {
     $this->$trackingNumber = $trackingNumber;
   }
  
  public function request () {
    $post = ['objetos' => 'DY277947771BR', 'btnPesq' => 'Buscar'];
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
  
  public function track() {

    //  preg_match();
    # Create a DOM parser object
    $dom = new DOMDocument();
    # Parse the HTML from Google.
    # The @ before the method call suppresses any warnings that
    # loadHTML might throw because of invalid HTML in the page.
    @$dom->loadHTML($html);
    $result = [];
    $i = 0;
    # Iterate over all the <a> tags
    foreach($dom->getElementsByTagName('tr') as $row) {
      //echo 'nova linha:   ';

            # Show the <a href>
    //         echo $link->getAttribute('href');
    //     pr($row);
      $j = 0;
      foreach( $row->getElementsByTagName('td') as $cell ) {
        $result[$i][($j === 0 ? 'detail' : 'description' )] = $cell->textContent;
        $j++;
        //echo 'celula  ' . $cell->textContent . '';
      }//foreach
      $i++;

    }//foreach
  } //track
} // class

$track = new Tracking( '3' );
$track->request()->track();

// pr($result);
function pr($e){
echo '<pre>', print_r($e, true), '</pre>';
}
