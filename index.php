<?php

 include 'core.php';
 
 $db = simplexml_load_file("mlDB.xml");

 $method = $_SERVER['REQUEST_METHOD'];
 
 if($method != 'GET'){
	initialize();
 }
 if (!isset($_GET['id'])){
	die("nocode");
 }

 if (!isset($_GET['lang'])){
	view_paste($_GET['id']);
 } else if (isset($_GET['lang'])){
	syntax($_GET['id'], $_GET['lang']);
 } 

?>

