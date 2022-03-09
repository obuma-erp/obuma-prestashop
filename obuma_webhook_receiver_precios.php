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


	$data = $decodedBody['eventData'];
	$data = stripslashes($data);
	$data = json_decode($data, true);
	//print_r($data);
	// Desde data se debe sacar los datos del producto



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
		
		$producto_id = $data["producto_id"];
		$producto_nombre = $data["producto_nombre"];
		$producto_codigo_comercial = $data["producto_codigo_comercial"];
		$producto_precio_clp_neto = $data["producto_precio_clp_neto"];
		$producto_precio_clp_iva = $data["producto_precio_clp_iva"];
		$producto_precio_clp_total = $data["producto_precio_clp_total"];


		if(isset($producto_codigo_comercial ) && !empty(trim($producto_codigo_comercial)) && isset($producto_nombre) &&  !empty(trim($producto_nombre))){
			$pro = verificar_producto($producto_codigo_comercial);
			if($pro != false){
				try {
					// Actualizar el precio del Producto
					$product = new Product((int)$pro[0]['id_product']);
					$product->price = $producto_precio_clp_neto;
					//actualizar_precio($producto_codigo_comercial,$producto_precio_clp_total);
					$product->update();

				}catch(Exception $e){
					print_r($e->getMessage());
				}
			}
		}
	}
?>