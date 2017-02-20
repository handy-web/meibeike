

<?php


$key = $_GET["key"];

if($key == "")

{ $key = "所有"; }


$from = $_GET["from"];

if($from == "")

{ $from = "direct"; }


echo $key.$from;


?>
