<?php 

function eliminar_simbolos($string){
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','Ã'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    $string = str_replace(
        array("\\", "¨", "º","°", "-","_", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "<","=" ,";", ",", ":","©","³",
             ".", " "),
        ' ',
        $string
    );
return $string;
} 

function copyImg($id_entity, $url, $id_image = null, $entity = 'products'){
    
    // Añadimos la imagen que nos envía el código desde WS al Prestashop
    $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
    $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
    $image_obj = new Image($id_image);
    $path = $image_obj->getPathForCreation();
    $url = str_replace(' ', '%20', trim($url));
    
    if (!ImageManager::checkImageMemoryLimit($url)) {
        return false;
    }

    if (@copy($url, $tmpfile)) {
        ImageManager::resize($tmpfile, $path . '.jpg');
        $images_types = ImageType::getImagesTypes($entity);
        
        foreach ($images_types as $image_type) {
            ImageManager::resize(
                $tmpfile,
                $path . '-' . stripslashes($image_type['name']) . '.jpg',
                $image_type['width'],
                $image_type['height']
            );

            if (in_array($image_type['id_image_type'], $watermark_types)) {
                Hook::exec('actionWatermark', [
                    'id_image' => $id_image,
                    'id_product' => $id_entity
                ]);
            }
        }
    } else {
        unlink($tmpfile);
        return false;
    }

    unlink($tmpfile);
    return true;
}

function set_url(){
    $result = "";
    $url_obuma = Configuration::get('api_url');
    $url_obuma_array = str_split($url_obuma);
    $ultimo_caracter = end($url_obuma_array);
    
    if($ultimo_caracter == "/"){
        $result = $url_obuma;
    }else{
        $result = $url_obuma . "/";
    }
    return $result;
}

    
function is_valid_email($str)
{
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}


function total_paginas_first($file,$pagina){
    global $cantidad_paginas;
    $json = ObumaConector::get(set_url().$file,Configuration::get("api_key"));
    $json = json_encode($json, true);
    $json = json_decode($json, true);
    $cantidad_paginas = $json["data-total-pages"];
    echo json_encode(array("total" => $cantidad_paginas, "first" => true,"pagina" => $pagina));
}


function is_image($image){
    $result = false;
    $permit = ["jpg","jpeg","png"];
    $string = explode(".",$image);
    $extension = end($string);
    if (in_array($extension, $permit)) {
        $result = true;
    }
    return $result;
}


function verificar_categorias_seleccionadas($url,$categoria_seleccionada,$nombre,$bodega = null){
    global $pagina;
    $url_final = "";
    if(!isset($categoria_seleccionada)){ $categoria_seleccionada = "all"; }
    $url_final .= $url;
    if ($categoria_seleccionada == "all") {   
        if (trim($nombre) == "stock") {
            $url_final .= "?codigo_bodega=". $bodega . "&page=" . $pagina;
        }else{
            $url_final .= "?page=" . $pagina;
        }
    }else{
        if (trim($nombre) == "stock") {
            $url_final .= "?codigo_bodega=" . $bodega . "&categoria=" . $categoria_seleccionada . "&page=" . $pagina;
        }else{
            $url_final .= "?categoria=" . $categoria_seleccionada . "&page=" . $pagina;
        }          
    }
    $json = ObumaConector::get($url_final,Configuration::get("api_key"));   
    return $json;
}

function obtener_numero_pagina($pagina){
    $pag = 1;
    if (isset($pagina)) {
        $pag = (int)$pagina;   
    }
    return $pag;
}

    function esRut($r = false){
        if((!$r) or (is_array($r)))
            return false; /* Hace falta el rut */
     
        if(!$r = preg_replace('|[^0-9kK]|i', '', $r))
            return false; /* Era código basura */
     
        if(!((strlen($r) == 8) or (strlen($r) == 9)))
            return false; /* La cantidad de carácteres no es válida. */
     
        $v = strtoupper(substr($r, -1));
        if(!$r = substr($r, 0, -1))
            return false;
     
        if(!((int)$r > 0))
            return false; /* No es un valor numérico */
     
        $x = 2; $s = 0;
        for($i = (strlen($r) - 1); $i >= 0; $i--){
            if($x > 7)
                $x = 2;
            $s += ($r[$i] * $x);
            $x++;
        }
        $dv=11-($s % 11);
        if($dv == 10)
            $dv = 'K';
        if($dv == 11)
            $dv = '0';
        if($dv == $v)
            return number_format($r, 0, '', '.').'-'.$v; /* Formatea el RUT */
    return false;
    }

function verificar_categoria_vinculada($id_categoria_obuma){
    $result = false;
    $categoria_vinculada = Db::getInstance()->executeS("SELECT c.id_category,vco.obuma_id_category FROM "._DB_PREFIX_."category c INNER JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category INNER JOIN "._DB_PREFIX_."obuma_vincular_categorias vco ON c.id_category=vco.id_category WHERE vco.obuma_id_category='".$id_categoria_obuma."' AND vco.obuma_id_category > 0 LIMIT 1");

    if(count($categoria_vinculada) == 1){
        $result = $categoria_vinculada;
    }
    return $result;
}

function verificar_producto($sku){
    $result = false;
    $existe_producto = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."product p INNER JOIN " ._DB_PREFIX_. "product_lang pl ON p.id_product=pl.id_product WHERE p.reference='".$sku."' LIMIT 1");

    if(count($existe_producto) == 1){
        $result = $existe_producto;
    }
    return $result;
}

function update_id_producto_obuma($id_producto,$id_producto_obuma){
    $update_id_obuma = "UPDATE "._DB_PREFIX_."product SET obuma_id_product='".$id_producto_obuma."' WHERE id_product='".$id_producto."'";
    
    return Db::getInstance()->execute($update_id_obuma);

}

function update_id_categoria_obuma($id_categoria,$id_categoria_obuma){
    $update_id_obuma = "UPDATE "._DB_PREFIX_."category SET obuma_id_category='".$id_categoria_obuma."' WHERE id_category='".$id_categoria."'";
    
    return Db::getInstance()->execute($update_id_obuma);

}


function verificar_categoria($id_categoria_obuma,$categoria_nombre){
    $result = false;
    $existe_categoria = Db::getInstance()->executeS("SELECT c.id_category FROM "._DB_PREFIX_."category c INNER JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category WHERE c.obuma_id_category='".$id_categoria_obuma."' OR cl.name='".$categoria_nombre."'");

    if(count($existe_categoria) == 1){
        $result = $existe_categoria;
    }
    return $result;
}


function actualizar_precio($sku,$nuevo_precio){
    $actualizar_precio = "UPDATE "._DB_PREFIX_."product_shop ps INNER JOIN "._DB_PREFIX_."product p ON ps.id_product=p.id_product SET ps.price='".$nuevo_precio."',p.price='".$nuevo_precio."' WHERE ps.id_shop=1 AND p.reference='".$sku."'";

    return Db::getInstance()->execute($actualizar_precio);
}


function actualizar_stock($id_product,$nuevo_stock){
    $actualizar_stock = "UPDATE "._DB_PREFIX_."stock_available SET quantity='".$nuevo_stock."' WHERE id_product='".$id_product."' AND id_product_attribute=0";

    return Db::getInstance()->execute($actualizar_stock);
}

function verificar_cliente($id_cliente_obuma,$sincronizar_por,$sincronizar_value){
    $result = false;
    $conditions = [];
    $where = "";
    
    if($sincronizar_por == 0){
        $conditions[] = "obuma_rut='".$sincronizar_value."'";

    }else{
        $conditions[] = "email='".$sincronizar_value."'";
    }

    //$conditions[] = "obuma_id_customer='".$id_cliente_obuma."'";

    if(count($conditions) > 0 ){
         $where = " WHERE " .implode(" AND ",$conditions);
    }
   
    
    $existe_cliente = Db::getInstance()->executeS("SELECT id_customer,obuma_id_customer FROM "._DB_PREFIX_."customer {$where} LIMIT 1");

    if(count($existe_cliente) == 1){
        $result = $existe_cliente;
    }
    return $result;
}

function update_rut_id_obuma($id_cliente,$obuma_rut,$id_cliente_obuma){
    $update_id_obuma = "UPDATE "._DB_PREFIX_."customer SET obuma_rut='{$obuma_rut}',obuma_id_customer='{$id_cliente_obuma}' WHERE id_customer='".$id_cliente."'";
    
    return Db::getInstance()->execute($update_id_obuma);

}

function create_log_obuma($data,$type){

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    if($type == "synchronization"){
        $query = "INSERT INTO "._DB_PREFIX_."obuma_log_synchronization SET fecha='".$fecha."',hora='".$hora."',tipo='".$data["tipo"]."',opcion='".$data["opcion"]."',resultado='".$data["resultado"]."'";
    }
    
    if($type == "webhook"){
        $query = "INSERT INTO "._DB_PREFIX_."obuma_log_webhook SET fecha='".$fecha."',hora='".$hora."',tipo='".$data["tipo"]."',peticion='".json_encode($data["peticion"])."',resultado='".$data["resultado"]."'";
    }


    if($type == "order"){
        $query = "INSERT INTO "._DB_PREFIX_."obuma_log_order SET fecha='".$fecha."',hora='".$hora."',order_id='".$data["order_id"]."',peticion='".json_encode($data["peticion"])."',respuesta='".json_encode($data["respuesta"])."',estado='".$data["estado"]."'";
    }
    
    
    return Db::getInstance()->execute($query);

}

function obtener_id_product($sku){
    $result = false;
    $obtener_id_product = Db::getInstance()->executeS("SELECT id_product FROM "._DB_PREFIX_."product WHERE reference='".$sku."'");
    if(count($obtener_id_product) == 1){
        $result = $obtener_id_product;
    }
    return $result;
}

function eliminar_producto($id_product){
    $sql = [];
    $sql[] = "DELETE FROM "._DB_PREFIX_."product WHERE id_product='".$id_product."'";
    $sql[] = "DELETE FROM "._DB_PREFIX_."product_lang WHERE id_product='".$id_product."'";
    $sql[] = "DELETE FROM "._DB_PREFIX_."product_shop WHERE id_product='".$id_product."'";

    foreach ($sql as $key => $query) {
        Db::getInstance()->execute($query);
    }
    

}


function validar_proveedor($sku){

    $result = true;

    $proveedores_actualizar_stock = trim(Configuration::get("proveedores_actualizar_stock"));
    
    if(!empty($proveedores_actualizar_stock)){

        $explode_proveedores = explode(",",$proveedores_actualizar_stock);

        $proveedores_valid = [];

        foreach($explode_proveedores as $p){

            if(is_numeric($p)){

                $proveedores_valid[] = $p;

            }

        }  

        if(count($proveedores_valid) > 0){

            $proveedores_actualizar_stock_valid = implode(",", $proveedores_valid);

            $obtener_id_product = Db::getInstance()->executeS("SELECT id_supplier FROM "._DB_PREFIX_."product WHERE reference='".$sku."' AND id_supplier IN ({$proveedores_actualizar_stock_valid})");

            if(count($obtener_id_product) > 0){
                $result = true;
            }else{
                $result = false;
            }

        }

    }

    return $result;
 
}


function updateOrderStatusObuma($order_id,$new_order_state){

    $ctx = Context::getContext();
    $emp_id = (int)$ctx->employee->id; 

    $fecha = date('Y-m-d H:i:s');

    $update_order_state = Db::getInstance()->execute("UPDATE  "._DB_PREFIX_."orders SET current_state={$new_order_state} WHERE id_order={$order_id}");

    $insert_order_history = Db::getInstance()->execute("INSERT INTO "._DB_PREFIX_."order_history(id_employee,id_order,id_order_state,date_add) VALUES ({$emp_id},{$order_id},{$new_order_state},'{$fecha}')");



}
function getIdOrderStatus(){


    $obtener_id_order_state = Db::getInstance()->executeS("SELECT id_order_state FROM "._DB_PREFIX_."order_state WHERE module_name='obuma' LIMIT 1");

            if(count($obtener_id_order_state) > 0){
                return $obtener_id_order_state[0]["id_order_state"];
            }else{
                return false;
            }
}

function soloLetras($in){
    if(preg_match('/[^a-zA-Z\s]/',$in)){ 
        return false;
    }else{
     return true;

    }
}