<?php

$uri = $_SERVER["REQUEST_URI"];
$cleans = explode('/',$uri);

$path = "";
for($i = 2; $i < count($cleans); $i++) {
    $path = $path.'/';
    $path = $path.$cleans[$i];
}

header("Location: /".$cleans[1]."/public".$path);

?>