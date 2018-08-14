<?php


function pr($e){
  echo '<pre>', print_r($e, true), '</pre>';
}

function dump($e){
  echo '<pre>';
  var_dump($e);
  echo '</pre>';
}