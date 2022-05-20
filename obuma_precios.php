<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";
require_once "obuma_conector.php";
require_once "functions.php";


$cantidad_paginas = 0;
$result = array();

$resumen = array();
$resumen["resumen"] = []; 

$error = array();

$log = array();
$indice_log = 0;
$indice = 0;

$pagina = obtener_numero_pagina($_POST["pagina"]);



$url = set_url()."productosConsultaPrecios.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"precios");
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_precios = $json["data"];
$cantidad_paginas = $json["data-total-pages"];


//Variables log de sincronizacion:

$log_synchronization_type = "Product price";
$log_synchronization_option = "All categories";
if(isset($_POST["categorias_seleccionadas"])){
	$log_synchronization_option = $_POST['categorias_seleccionadas'] == "all" ? "All categories" : $_POST['categorias_seleccionadas'];
}


if ($cantidad_paginas > 0) {
	foreach ($data_precios as $key => $data) {
		$producto_id = $data["producto_id"];
		$producto_nombre = $data["producto_nombre"];
		$producto_codigo_comercial = $data["producto_codigo_comercial"];
		$producto_precio_clp_neto = $data["producto_precio_clp_neto"];
		$producto_precio_clp_iva = $data["producto_precio_clp_iva"];
		$producto_precio_clp_total = $data["producto_precio_clp_total"];

		if(isset($producto_codigo_comercial ) && !empty(trim($producto_codigo_comercial)) && isset($producto_nombre) &&  !empty(trim($producto_nombre))){
			$pro = verificar_producto($producto_codigo_comercial);
			if($pro != false){

				$precio_aplicar = $producto_precio_clp_total;

				if(Configuration::get("sincronizar_precio") == 1){
					$precio_aplicar = $producto_precio_clp_neto;
				}

				try {

					


					$product = new Product((int)$pro[0]['id_product']);
					$product->price = $precio_aplicar;
					//actualizar_precio($producto_codigo_comercial,$producto_precio_clp_neto);
					if($product->update()){
						$resumen["resumen"][$indice]["name"] = $producto_nombre;
						$resumen["resumen"][$indice]["action"] = "actualizado";
						$indice++;
					}
				} catch (Exception $e) {
					$error[$producto_id]["message"] =  $e->__toString();
					$error[$producto_id]["fields"]["name"] =  $producto_categoria_nombre;
					$error[$producto_id]["fields"]["reference"] =  $producto_codigo_comercial;
					$error[$producto_id]["fields"]["price"] =  $precio_aplicar;

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