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

$cantidad_paginas = 0;

$url = set_url()."productos.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"productos");
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_productos = $json["data"];
$cantidad_paginas = $json["data-total-pages"];

$default_lang = Configuration::get('PS_LANG_DEFAULT');


//Variables log de sincronizacion:

$log_synchronization_type = "Products";
$log_synchronization_option = "All categories";
if(isset($_POST["categorias_seleccionadas"])){
	$log_synchronization_option = $_POST['categorias_seleccionadas'] == "all" ? "All categories" : $_POST['categorias_seleccionadas'];
}


if($cantidad_paginas > 0){
	foreach ($data_productos as $key => $data) {
		$producto_id = $data["producto_id"];
		$producto_nombre = $data["producto_nombre"];
		$producto_precio = $data["producto_precio_clp_total"];
		$producto_meta_keywords = $data["producto_metakeywords"];
		$producto_codigo_comercial = $data["producto_codigo_comercial"];
		$producto_categoria = $data["producto_categoria"];

		if(isset($producto_codigo_comercial ) && !empty(trim($producto_codigo_comercial)) && isset($producto_nombre) &&  !empty(trim($producto_nombre)) && $producto_categoria > 0){

			$pro = verificar_producto($producto_codigo_comercial);

			$categoria_vinculada = verificar_categoria_vinculada($producto_categoria);

			if ($pro == false) {


				try {
					$product = new Product();  
					$product->name = [$default_lang => $producto_nombre];
					$product->reference = $producto_codigo_comercial;
					$product->link_rewrite = [$default_lang => Tools::str2url($producto_nombre)];
					$product->price = 0;
					$product->active = 1;
					$product->quantity = 0;
					$product->show_price = 1;
					$product->meta_keywords = [$default_lang => $producto_meta_keywords];

					if($categoria_vinculada != false){
						$product->id_category_default = (int)$categoria_vinculada[0]["id_category"]; 
						$product->category = [(int)$categoria_vinculada[0]["id_category"]];
					}
							  
					if($product->add()){

							if(update_id_producto_obuma($product->id,$producto_id)){
								
								if($categoria_vinculada != false){
									$product->addToCategories([(int)$categoria_vinculada[0]["id_category"]]);
								}

								StockAvailable::updateQuantity((int)$pro[0]['id_product'], 0, 0);
								$resumen["resumen"][$indice]["name"] = $producto_nombre;
								$resumen["resumen"][$indice]["action"] = "agregado";
								$indice++;
							}
									
					}
				} catch (Exception $e) {
					
					$error[$producto_id]["message"] =  $e->__toString();
					$error[$producto_id]["fields"]["name"] =  $producto_nombre;
					$error[$producto_id]["fields"]["reference"] =  $producto_codigo_comercial;
					
				}


			}else{
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
						if(update_id_producto_obuma($pro[0]["id_product"],$producto_id)){
							$resumen["resumen"][$indice]["name"] = $producto_nombre;
							$resumen["resumen"][$indice]["action"] = "actualizado";
							$indice++;
						}
						
					}
				} catch (Exception $e) {
					$error[$producto_id]["message"] =  $e->__toString();
					$error[$producto_id]["fields"]["name"] =  $producto_nombre;
					$error[$producto_id]["fields"]["reference"] =  $producto_codigo_comercial;
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
			




