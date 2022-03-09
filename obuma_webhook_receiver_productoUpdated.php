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
			
		// Actualizar el producto
		$producto_id = $data["producto_id"];
		$producto_nombre = $data["producto_nombre"];
		$producto_precio = $data["producto_precio_clp_total"];
		$producto_meta_keywords = $data["producto_metakeywords"];
		$producto_codigo_comercial = $data["producto_codigo_comercial"];
		$producto_categoria = $data["producto_categoria"];

		if(isset($producto_codigo_comercial ) && !empty(trim($producto_codigo_comercial)) && isset($producto_nombre) &&  !empty(trim($producto_nombre)) && $producto_categoria > 0){

			$pro = verificar_producto($producto_codigo_comercial);
			$categoria_vinculada = verificar_categoria_vinculada($producto_categoria);
		
			try {
				$product = new Product((int)$pro[0]["id_product"]);  
				$product->name = [$default_lang => $producto_nombre];
				$product->link_rewrite = [$default_lang => Tools::str2url($producto_nombre)];
				$product->price = 0;
				$product->active = 1;
				$product->quantity = 0;
				$product->show_price = 1;
				$product->meta_keywords = [$default_lang => $producto_meta_keywords];
				if($categoria_vinculada != false){
					$product->id_category_default = (int)$categoria_vinculada[0]["id_category"];
					$product->category = [(int)$categoria_vinculada[0]["id_category"]];
					$product->updateCategories($product->category,true);
				}  
						 
				if($product->update()){
					update_id_producto_obuma($pro[0]["id_product"],$producto_id);	
				}
			} catch (Exception $e) {
					print_r($e->getMessage());
			}
		}
	}

?>