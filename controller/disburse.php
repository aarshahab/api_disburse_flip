<?php
class disburse
{
    private $conn;
    private $table_name = "disburse";
    public $id;
	public $bank_code;
	public $amount;
	public $status;
	public $create_by;
	public $success_by;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
	function detailData($transaction_id){
		$url = 'https://nextar.flip.id/disburse/'.$transaction_id;
		$ch = curl_init();
		
		$secret_key = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");
		curl_setopt($ch, CURLOPT_URL, $url);
		
		
		$response = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($response);
			$status = $data->status;
			if($status === "SUCCESS"){
				$receipt = $data->receipt;
				$time_received = $data->time_served;
				$query = "UPDATE " . $this->table_name . " SET status='$status', receipt='$receipt', time_received='$time_received' WHERE id=$transaction_id";
				$stmt = $this->conn->prepare($query);
        		
        		$stmt->execute();
			}
			
		$query = "SELECT *
            		FROM
                " . $this->table_name . " WHERE id=$transaction_id";
        $stmt = $this->conn->prepare($query);
		
        $stmt->execute();
		return $stmt;
	}
	
	function insertData($data){
		$url = 'https://nextar.flip.id/disburse';
		$ch = curl_init();
		$secret_key = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded",));
		curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		curl_close($ch);
	
		$data = json_decode($response);
	
		$id = $data->id; 
		$bank_code = $data->bank_code;
		$amount = $data->amount;
		$status = $data->status;
		$receipt = $data->receipt;
		$create_by = $data->timestamp; 
	
		$query = "INSERT INTO " . $this->table_name . " (id,bank_code,amount,status,receipt,create_by) VALUES ($id,'$bank_code',$amount,'$status','$receipt','$create_by')";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		$disburse_data=array(
            "id" => $data->id,
            "bank_code" => $data->bank_code,
            "amount" => $data->amount,
            "status" => $data->status,
			"receipt" => $data->receipt,
            "create_by" => $data->timestamp
        );
        echo json_encode($disburse_data);

	}
	
}
?>
