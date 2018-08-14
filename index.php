<?php
require './Tracking.php';

$track = new Tracking( 'DY277347772BR' );
echo $track->asJson();