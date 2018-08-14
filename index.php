<?php
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

  echo $output = curl_exec($ch);
