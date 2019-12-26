<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/database.php';
include_once '../controller/disburse.php';
 
$database = new Database();
$db = $database->connect();
 
$disburse = new Disburse($db);

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id = $url[count($url) - 1];
$num = 0;
if(is_numeric($id) === true){
	$stmt = $disburse->detailData($id);
	$num = $stmt->rowCount();
}
 
if($num==1){
 
    $disburses_arr=array();
    $disburses_arr["records"]=array();
	
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $disburse_item=array(
            "id" => $id,
            "bank_code" => $bank_code,
            "amount" => $amount,
            "status" => $status,
			"receipt" => $receipt,
            "create_by" => $create_by,
            "time_received" => $time_received
        );
    }
 
    http_response_code(200);
 
    echo json_encode($disburse_item);
}else{ 
 
    http_response_code(404);
    echo json_encode(
        array("message" => "No disburse found.")
    );
}
?>