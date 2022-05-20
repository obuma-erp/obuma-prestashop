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
		$cliente_rut = trim($data["cliente_rut"]);
		$cliente_razon_social = trim($data["cliente_razon_social"]);
		$cliente_email = trim($data["cliente_email"]);
		$cliente_clave = $data["cliente_clave"];
		$rel_empresa_id = $data["rel_empresa_id"];

		$result = [];


		$validar_cliente_por = Configuration::get("sincronizar_cliente_por");
		$validar_cliente_por_value = "";

		if(esRut($cliente_rut) == false || !is_valid_email($cliente_email)){

			$data_log = array("tipo" => "Actualizar cliente","peticion" => json_encode($requestBody, JSON_PRETTY_PRINT), "resultado" => "Error : El rut o el email del cliente no es valido");
	
			create_log_obuma($data_log,"webhook");
			exit();
		}


		if($validar_cliente_por == 0){

			$validar_cliente_por_value = $cliente_rut;

		}else{

			$validar_cliente_por_value = $cliente_email;

		}


		if (isset($cliente_razon_social) && !empty($cliente_razon_social)) {
			$cl = verificar_cliente($cliente_id,$validar_cliente_por,$validar_cliente_por_value);
			if ($cl != false) {
				try {

						$customer = new Customer((int)$cl[0]["id_customer"]);

						if($validar_cliente_por == 0){
							$customer->email = $cliente_email;
						}
						
						$customer->firstname = $cliente_razon_social;
						$customer->lastname = $cliente_razon_social;
						
						if($customer->update()){

							if($validar_cliente_por == 1){
								update_rut_id_obuma((int)$cl[0]["id_customer"],$cliente_rut,$cliente_id);
							}
							
							$resumen["resumen"][$indice]["clave"] = $cliente_clave;
							$resumen["resumen"][$indice]["name"] = $cliente_razon_social;
							$resumen["resumen"][$indice]["action"] = "actualizado";
							$indice++;
							
						}
					}
					catch (Exception $e) {
						 $error[$cliente_id]["message"] =  $e->__toString();
						 $error[$cliente_id]["fields"]["firstname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["lastname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["email"] =  $cliente_email;
						
					}
			}else{

					$data_log = array("tipo" => "Actualizar cliente","peticion" => json_encode($requestBody, JSON_PRETTY_PRINT), "resultado" => "Error : El cliente no fue encontrado en Prestashop, revise el RUT o el Email");
	
					create_log_obuma($data_log,"webhook");

					exit();
			}
		}else{

			$data_log = array("tipo" => "Actualizar cliente","peticion" => json_encode($requestBody, JSON_PRETTY_PRINT), "resultado" => "Error : El cliente debe tener una razon social valida");
	
			create_log_obuma($data_log,"webhook");

			exit();
		}
	}


	$data_log = array("tipo" => "Actualizar cliente","peticion" => json_encode($requestBody, JSON_PRETTY_PRINT), "resultado" => json_encode($result, JSON_PRETTY_PRINT));
	
	create_log_obuma($data_log,"webhook");
?>