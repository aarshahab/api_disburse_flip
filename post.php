<?php 
  // Headers
  	header('Access-Control-Allow-Origin: *');
  	header('Content-Type: application/x-www-form-urlencoded');
  	include_once '../config/database.php';
  	include_once '../controller/disburse.php';
 
  	$database = new Database();
 	$db = $database->connect();
 	$disburse = new Disburse($db);
  
  	$data = file_get_contents("php://input");
	
	$stmt = $disburse->insertData($data);
?>