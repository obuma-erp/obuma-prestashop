<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";
require_once "obuma_conector.php";
require_once "functions.php";


// guarda en archivo
//$file_ = date('Y').'.txt';
//file_put_contents($file_, file_get_contents('php://input'));




// Get contents of webhook request
	$requestBody = file_get_contents('php://input');

	$client_secret = Configuration::get("api_key");
	echo '<br>$client_secret : '.$client_secret;



// Parse webhook data
	$decodedBody = json_decode($requestBody, true);

	$eventId = $decodedBody['eventId'];
	$eventType = $decodedBody['eventType'];
	$eventDate = $decodedBody['eventDate'];

	// se almacena la data y se asigna a un array
	$data = $decodedBody['eventData'];
	$data = stripslashes($data);
	$data = json_decode($data, true);
	//print_r($data);
	// Desde data se debe sacar los datos del Cliente



	echo '<br>eventId : '.$eventId;
	echo '<br>eventType : '.$eventType;




// Filter out the events we're not interested in
	/*
	if ($eventType !== 'cliente.updated') {
	    echo 'Error... tipo evento invalido.';
	    exit;
	}
	*/



//***************************************************************************************************
// Signature
//***************************************************************************************************

	// Save the signature sended
	$headerSignature = $_SERVER['HTTP_OBUMA_WEBHOOK_SIGNATURE'];
	echo '<br>signature received: '.$headerSignature;


	// generate the signature
	$signature = $eventDate.$eventId;
	$hmac_result = hash_hmac("sha256", $signature, $client_secret, true);
	$generatedSignature = base64_encode($hmac_result);
	echo '<br>signature generated: '.$generatedSignature;

	// verificate the signature
	if ($generatedSignature !== $headerSignature) {
		echo '<br>Error... signature verification failed';
		// Reply with 401->verification failed
		
		exit;
	} else {
		echo '<br>Signature ok...';
		// Reply with 200->OK
		$cliente_id = $data["cliente_id"];
		$cliente_rut = $data["cliente_rut"];
		$cliente_razon_social = $data["cliente_razon_social"];
		$cliente_email = $data["cliente_email"];
		$cliente_clave = $data["cliente_clave"];

		if (isset($cliente_razon_social) && !empty(trim($cliente_razon_social)) && is_valid_email(trim($cliente_email))) {
			$cl = verificar_cliente($cliente_id);
			if ($cl != false) {
				try {
					$customer = new Customer((int)$cl[0]["id_customer"]);
					$customer->email = $cliente_email;
					$customer->firstname = $cliente_razon_social;
					$customer->lastname = $cliente_razon_social;
					$customer->passwd = md5($cliente_clave);
					$customer->is_guest = 1;
					if($customer->update()){
						update_id_cliente_obuma((int)$cl[0]["id_customer"],$cliente_id,$cliente_rut);
						}
				}catch (Exception $e) {
					print_r($e->getMessage());
				}
			}
		}
	}
?>