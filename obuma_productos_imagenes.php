<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";
require_once "obuma_conector.php";
require_once "functions.php";

$cantidad_paginas = 0;
$pro = array();

$url_imagenes_producto = set_url()."productosImagenes.findByProductoId.json";

$pagina = obtener_numero_pagina($_POST["pagina"]);


	if ($_POST["categorias_seleccionadas"] == "all") {
		$inicio = $pagina * 100 - 100;
		$total = Db::getInstance()->executeS("SELECT id_product,obuma_id_product FROM "._DB_PREFIX_."product");

		$pro = Db::getInstance()->executeS("SELECT id_product,obuma_id_product FROM "._DB_PREFIX_."product LIMIT $inicio,100 ");
		$cantidad_paginas = count($total);
			$cantidad_paginas = ceil($cantidad_paginas/100);
	}else{
		$inicio = $pagina * 100 - 100;
		$id_categoria = Db::getInstance()->executeS("SELECT id_category FROM "._DB_PREFIX_."category WHERE obuma_id_category='".$_POST["categorias_seleccionadas"]."' LIMIT 1");
		$id_categoria = $id_categoria[0]["id_category"];

		$pro = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."category_product cp INNER JOIN "._DB_PREFIX_."product p  ON cp.id_product=p.id_product WHERE cp.id_category='".$id_categoria."' LIMIT $inicio,100");
			$cantidad_paginas = count($pro);
			$cantidad_paginas = ceil($cantidad_paginas/100);


		
	}


$resumen = array();
$resumen["resumen"] = []; 

$error = [];

$log = array();
$indice_log = 0;
$indice = 0;

$result = array();

$json2 = [];


//Variables log de sincronizacion:

$log_synchronization_type = "Product images";
$log_synchronization_option = "All categories";
if(isset($_POST["categorias_seleccionadas"])){
	$log_synchronization_option = $_POST['categorias_seleccionadas'] == "all" ? "All categories" : $_POST['categorias_seleccionadas'];
}



		if($cantidad_paginas > 0){
			foreach ($pro as $key => $data) {
			try {
				$json2 = ObumaConector::get($url_imagenes_producto.'/'.$data['obuma_id_product'],Configuration::get("api_key"));
				   	 $json2 = json_encode($json2, true);
				     $json2 = json_decode($json2, true);
				     if(isset($json2["data"])){
				     	foreach ($json2["data"] as $r2) {

				     	$imagen_url = $r2['producto_imagen_url'];
				     	
				     	$imagen_explode = explode("/", $imagen_url);

					    $imagen_product = end($imagen_explode);

					    $imagen_a_copiar = $imagen_url;

				     	if(isset($imagen_url) AND !empty($imagen_url)){

				     		if (is_image($imagen_url)) {
					    	
					    	
					    		$shops = Shop::getShops(true, null, true);    
								$image = new Image();
								$image->id_product = (int) $data["id_product"];
								$image->position = Image::getHighestPosition($data["id_product"]) + 1;
								$image->cover =  true;
								$image->legend  = '';

								if (($image->validateFields(false, true)) === true && ($image->validateFieldsLang(false, true)) === true && $image->add()){
    								$image->associateTo($shops);
    								if (!copyImg($data["id_product"], $imagen_a_copiar , $image->id,  'products')){
										$image->delete();
				                   
				        			}else{
				        				$resumen["resumen"][$indice]["name"] = $imagen_url;
										$resumen["resumen"][$indice]["action"] = "actualizado";
										$indice++;


										$log[$indice_log]["url"]["first"] = $url_imagenes_producto;
										$log[$indice_log]["page"] = $pagina;
										$log[$indice_log]["response"] = $json2; 
										
										$indice_log++;

				        				
				        			}
				     			}
				


				     }
				     	}
				     }
				     }
				       
						} catch (Exception $e) {
					   
					    		$error[] = $e->__toString();
					    	}
                 
				     	

				     }
	                
            	
				 }else{
				 	$pagina = 0;
				 	$cantidad_paginas = 0;
				 }
		
		


$result = array("completado" => $pagina,"total" => $cantidad_paginas,"resumen" => $resumen,"log" => $log);

if($cantidad_paginas > 0 && $pagina == $cantidad_paginas){

	$log_synchronization_result = "Completed";

	$data_log = array("tipo" => $log_synchronization_type,"opcion" => $log_synchronization_option,"resultado" => $log_synchronization_result);
	create_log_obuma($data_log,"synchronization");

}

echo json_encode($result);