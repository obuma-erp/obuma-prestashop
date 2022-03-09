<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";
require_once "obuma_conector.php";
require_once "functions.php";



$result = array();	
$cantidad_paginas = 0;

$resumen = array();
$resumen["resumen"] = []; 

$error = array();

$log = array();
$indice_log = 0;
$indice = 0;

$pagina = obtener_numero_pagina($_POST["pagina"]);

$url = "http://api.obuma.cl/v1.0/productosStock.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"stock",Configuration::get("bodega"));
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_stock = $json["data"];
$cantidad_paginas = $json["data-total-pages"];


if ($cantidad_paginas > 0) {
	foreach ($data_stock as $data) {
		$producto_id = $data["producto_id"];
		$producto_nombre = $data["producto_nombre"];
		$producto_codigo_comercial = eliminar_simbolos($data["producto_codigo_comercial"]);
		$producto_stock_minimo = $data["producto_stock_minimo"];
		$producto_stock_ideal = $data["producto_stock_ideal"];
		$producto_stock_actual = $data["stock_actual"];

		if(isset($producto_codigo_comercial ) && !empty(trim($producto_codigo_comercial)) && isset($producto_nombre) &&  !empty(trim($producto_nombre))){

		$pro = verificar_producto($producto_codigo_comercial);
		if($pro != false){
			try {
				//StockAvailable::setQuantity((int)$pro[0]['id_product'], 0, $quantity);
				//StockAvailable::updateQuantity((int)$pro[0]['id_product'], 0, $quantity);
				//actualizar_stock((int)$pro[0]['id_product'],$producto_stock_actual);
				if(StockAvailable::updateQuantity((int)$pro[0]['id_product'], 0, $producto_stock_actual)){
					$resumen["resumen"][$indice]["name"] = $producto_nombre;
					$resumen["resumen"][$indice]["action"] = "actualizado";
					$indice++;
				}
			} catch (Exception $e) {
					$error[$producto_id]["message"] =  $e->__toString();
					$error[$producto_id]["fields"]["name"] =  $producto_nombre;
					$error[$producto_id]["fields"]["reference"] =  $producto_codigo_comercial;
					$error[$producto_id]["fields"]["quantity"] =  $producto_stock_actual;

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