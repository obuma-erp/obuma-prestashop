<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";
require_once "obuma_conector.php";
require_once "functions.php";

$result = array();

$resumen = array();
$resumen["resumen"] = []; 

$error = array();
$log = array();
$indice_log = 0;
$indice = 0;


$pagina = obtener_numero_pagina($_POST["pagina"]);

$url = "http://api.obuma.cl/v1.0/clientes.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"clientes");
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_clientes = $json["data"];
$cantidad_paginas = $json["data-total-pages"];


	if($cantidad_paginas > 0){
		foreach ($data_clientes as $key => $data) {
			$cliente_id = $data["cliente_id"];
			$cliente_rut = $data["cliente_rut"];
			$cliente_razon_social = $data["cliente_razon_social"];
			$cliente_email = $data["cliente_email"];
			$cliente_clave = $data["cliente_clave"];

			if (isset($cliente_razon_social) && !empty(trim($cliente_razon_social)) && is_valid_email(trim($cliente_email))) {
				$cl = verificar_cliente($cliente_id);

				if ($cl == false) {

					try {
						$customer = new Customer();
						$customer->email = $cliente_email;
						$customer->firstname = $cliente_razon_social;
						$customer->lastname = $cliente_razon_social;
						$customer->passwd = md5($cliente_clave);
						$customer->is_guest = 1;
						if($customer->add()){
							if (update_id_cliente_obuma($customer->id,$cliente_id,$cliente_rut)) {
									$resumen["resumen"][$indice]["name"] = $cliente_razon_social;
									$resumen["resumen"][$indice]["action"] = "agregado";
									$indice++;	
							}
						}
					} catch (Exception $e) {
						 $error[$cliente_id]["message"] =  $e->__toString();
						 $error[$cliente_id]["fields"]["firstname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["lastname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["email"] =  $cliente_email;
					}
					

				}else{
					try {
						$customer = new Customer((int)$cl[0]["id_customer"]);
						$customer->email = $cliente_email;
						$customer->firstname = $cliente_razon_social;
						$customer->lastname = $cliente_razon_social;
						$customer->passwd = md5($cliente_clave);
						$customer->is_guest = 1;
						if($customer->update()){
							if (update_id_cliente_obuma((int)$cl[0]["id_customer"],$cliente_id,$cliente_rut)){
							$resumen["resumen"][$indice]["name"] = $cliente_razon_social;
							$resumen["resumen"][$indice]["action"] = "actualizado";
							$indice++;
							}
						}
					}
					catch (Exception $e) {
						 $error[$cliente_id]["message"] =  $e->__toString();
						 $error[$cliente_id]["fields"]["firstname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["lastname"] =  $cliente_razon_social;
						 $error[$cliente_id]["fields"]["email"] =  $cliente_email;
						
					}
					

				}
			}		
		}

}else{
	$cantidad_paginas = 0;
	$pagina = 0;
}

$log[$indice_log]["url"] = $url;
$log[$indice_log]["page"] = $pagina;
$log[$indice_log]["response"] = $json;
$log[$indice_log]["error"] = $error; 
$indice_log++;

$result = array("completado" => $pagina,"total" => $cantidad_paginas,"resumen" => $resumen,"log" => $log);
echo json_encode($result);

?>