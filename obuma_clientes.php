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

$url = set_url()."clientes.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"clientes");
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_clientes = $json["data"];
$cantidad_paginas = $json["data-total-pages"];


$log_synchronization_type = "Customers";
$log_synchronization_option = "";


	if($cantidad_paginas > 0){
		foreach ($data_clientes as $key => $data) {

			$cliente_id = $data["cliente_id"];
			$cliente_rut = trim($data["cliente_rut"]);
			$cliente_razon_social = trim($data["cliente_razon_social"]);
			$cliente_email = trim($data["cliente_email"]);
			$cliente_clave = $data["cliente_clave"];
			$rel_empresa_id = $data["rel_empresa_id"];
			$validar_cliente_por = Configuration::get("sincronizar_cliente_por");
			$validar_cliente_por_value = "";

			if(esRut($cliente_rut) == false || !is_valid_email($cliente_email)){
					continue;
			}


			if($validar_cliente_por == 0){

				$validar_cliente_por_value = $cliente_rut;

			}else{

				$validar_cliente_por_value = $cliente_email;

			}

			if (isset($cliente_razon_social) && !empty($cliente_razon_social)) {

				$cl = verificar_cliente($cliente_id,$validar_cliente_por,$validar_cliente_por_value);

				//var_dump($cl);exit();
				if ($cl == false) {

					try {

						$customer = new Customer();
						$customer->email = $cliente_email;
						$customer->firstname = $cliente_razon_social;
						$customer->lastname = $cliente_razon_social;
						$customer->passwd = Tools::encrypt("Presto_i".$cliente_id."_i".$rel_empresa_id);
						$customer->is_guest = 0;
						
						if($customer->add()){

							update_rut_id_obuma($customer->id,$cliente_rut,$cliente_id);
							
							$resumen["resumen"][$indice]["name"] = $cliente_razon_social;
							$resumen["resumen"][$indice]["action"] = "agregado";
							$indice++;	

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

if($cantidad_paginas > 0 && $pagina == $cantidad_paginas){

	$log_synchronization_result = "Completed";

	$data_log = array("tipo" => $log_synchronization_type,"opcion" => $log_synchronization_option,"resultado" => $log_synchronization_result);

	create_log_obuma($data_log,"synchronization");
	

}

echo json_encode($result);

?>