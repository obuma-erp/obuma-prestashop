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
	//print_r($data['items']);
	// Desde data se debe sacar los datos del producto
	//echo '<hr>';

	$items = $data['items'];

	

		






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
			
		// Actualizar el Stock del producto

		foreach ($items as $item) {
			$rel_producto_id = $item["rel_producto_id"];
			$rel_bodega_id 	 = $item["rel_bodega_id"];
			$producto_codigo_comercial = $item["producto_codigo_comercial"];
			$pi_saldo = $item["pi_saldo"];
			if ($rel_bodega_id == Configuration::get("id_bodega")) {
			$pro = verificar_producto($producto_codigo_comercial);

			if($pro != false){
				try {

					if(StockAvailable::updateQuantity((int)$pro[0]['id_product'], 0, $pi_saldo)){
						print_r("Stock Actualizado a :" . $pi_saldo);
					}
				} catch (Exception $e) {
					print_r($e->_toString());
					

				}
			}
		}
		}
	}
?>