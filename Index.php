<?php

include_once("all.php");

Session::init();

$server = new HttpRestServer();
$server->Handle();

//echo date("d/m/Y",(1438121467.461));

