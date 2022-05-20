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

$url = set_url()."productosCategorias.list.json";
$json = verificar_categorias_seleccionadas($url,$_POST["categorias_seleccionadas"],"productos");
$json = json_encode($json, true);
$json = json_decode($json, true);
$data_categorias = $json["data"];
$cantidad_paginas = $json["data-total-pages"];

$default_lang = Configuration::get('PS_LANG_DEFAULT');


//Variables log de sincronizacion:

$log_synchronization_type = "Categories";
$log_synchronization_option = "All categories";


if($cantidad_paginas > 0){
	foreach ($data_categorias as $key => $data) {
		$producto_categoria_id = $data["producto_categoria_id"];
		$producto_categoria_nombre = $data["producto_categoria_nombre"];
		$producto_categoria_descripcion = $data["producto_categoria_descripcion"];
		$producto_categoria_metatitle = $data["producto_categoria_metatitle"];
		$producto_categoria_metadescription = $data["producto_categoria_metadescription"];
		$producto_categoria_metakeywords = $data["producto_categoria_metakeywords"];

		if(isset($producto_categoria_nombre) && !empty(trim($producto_categoria_nombre)) && isset($producto_categoria_id) && !empty(trim($producto_categoria_id))){

			$categoria = verificar_categoria($producto_categoria_id,$producto_categoria_nombre);


			if ($categoria == false) {

				try {
					$category = new Category();
			        $category->description = [ $default_lang => $producto_categoria_descripcion ] ;
			        $category->id_parent = Configuration::get('PS_HOME_CATEGORY');
			        $category->is_root_category = false;
			        $category->link_rewrite = [ $default_lang => Tools::str2url($producto_categoria_nombre) ];
			        $category->meta_description = [ $default_lang => $producto_categoria_metadescription ] ;
			        $category->meta_keywords = [ $default_lang => $producto_categoria_metakeywords ] ;
			        $category->meta_title = [ $default_lang => $producto_categoria_metatitle ] ;
			        $category->name = [ $default_lang => $producto_categoria_nombre ] ;
			       	if($category->add()){
						if(update_id_categoria_obuma($category->id,$producto_categoria_id)){
								$resumen["resumen"][$indice]["name"] = $producto_categoria_nombre;
								$resumen["resumen"][$indice]["action"] = "agregado";
								$indice++;
						}
					}
				} catch (Exception $e) {
					$error[$producto_categoria_id]["message"] =  $e->__toString();
					$error[$producto_categoria_id]["fields"]["name"] =  $producto_categoria_nombre;
					$error[$producto_categoria_id]["fields"]["meta_description"] =  $producto_categoria_descripcion;
					$error[$producto_categoria_id]["fields"]["link_rewrite"] =  Tools::str2url($producto_categoria_nombre);
					$error[$producto_categoria_id]["fields"]["meta_description"] =  $producto_categoria_metadescription;
					$error[$producto_categoria_id]["fields"]["meta_keywords"] =  $producto_categoria_metakeywords;

				}
				

			}else{

				try {
					$category = new Category($categoria[0]["id_category"]);
			        $category->description = [ $default_lang => $producto_categoria_descripcion ] ;
			        $category->id_parent = Configuration::get('PS_HOME_CATEGORY');
			        $category->is_root_category = false;
			        $category->link_rewrite = [ $default_lang => Tools::str2url($producto_categoria_nombre) ];
			        $category->meta_description = [ $default_lang => $producto_categoria_metadescription ] ;
			        $category->meta_keywords = [ $default_lang => $producto_categoria_metakeywords ] ;
			        $category->meta_title = [ $default_lang => $producto_categoria_metatitle ] ;
			        $category->name = [ $default_lang => $producto_categoria_nombre ] ;
			       if($category->update()){
			       		if(update_id_categoria_obuma($categoria[0]["id_category"],$producto_categoria_id)){
			       			$resumen["resumen"][$indice]["name"] = $producto_categoria_nombre;
							$resumen["resumen"][$indice]["action"] = "actualizado";
							$indice++;
			       		}
				       	
			       }
				} catch (Exception $e) {
					$error[$producto_categoria_id]["message"] =  $e->__toString();
					$error[$producto_categoria_id]["fields"]["name"] =  $producto_categoria_nombre;
					$error[$producto_categoria_id]["fields"]["meta_description"] =  $producto_categoria_descripcion;
					$error[$producto_categoria_id]["fields"]["link_rewrite"] =  Tools::str2url($producto_categoria_nombre);
					$error[$producto_categoria_id]["fields"]["meta_description"] =  $producto_categoria_metadescription;
					$error[$producto_categoria_id]["fields"]["meta_keywords"] =  $producto_categoria_metakeywords;
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