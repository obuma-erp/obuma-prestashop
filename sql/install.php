<?php 

$sql = array();


 $table_obuma_log_order = _DB_PREFIX_ . 'obuma_log_order';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_obuma_log_order (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `order_id` int(11)  NOT NULL,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
      `peticion` text  NOT NULL,
      `respuesta` text  NOT NULL,
      `estado` text  NOT NULL,
       PRIMARY KEY (`id`))";
    


    $sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'cart` 
            ADD `invoice_type` VARCHAR(20) NULL';


     $table_obuma_log_synchronization = _DB_PREFIX_ . 'obuma_log_synchronization';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_obuma_log_synchronization (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
      `tipo` text  NOT NULL,
      `opcion` text  NOT NULL,
      `resultado` text  NOT NULL,
       PRIMARY KEY (`id`))";

    $table_obuma_log_webhook = _DB_PREFIX_ . 'obuma_log_webhook';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_obuma_log_webhook (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
      `tipo` text  NOT NULL,
      `peticion` text  NOT NULL,
      `resultado` text  NOT NULL,
       PRIMARY KEY (`id`))";


    $table_obuma_order = _DB_PREFIX_ . 'obuma_order';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_obuma_order (
      `id` int(11) NOT NULL AUTO_INCREMENT,
       `order_id` int(11)  NOT NULL,
       `invoice_type` varchar(40)  NOT NULL,
      `dte_id` int(11)  NOT NULL,
       `dte_tipo` int(11)  NOT NULL,
        `dte_folio` int(11)  NOT NULL,
      `dte_result` text  NOT NULL,
      `dte_xml` text  NOT NULL,
      `dte_pdf` text  NOT NULL,
      `fecha_registro` datetime  NOT NULL,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
       PRIMARY KEY (`id`))";
    


    $table_obuma_vincular_categorias = _DB_PREFIX_ . 'obuma_vincular_categorias';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_obuma_vincular_categorias (
      `id` int(11) NOT NULL AUTO_INCREMENT,
       `id_category` int(11)  NOT NULL,
      `name_category` text  NOT NULL,
       `obuma_id_category` int(11)  NOT NULL,
       PRIMARY KEY (`id`))";






$insert_new_order_state = Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_."order_state(module_name,color) VALUES ('obuma','#fa2ca9')");

$id_new_order_state =Db::getInstance()->Insert_ID();

$id_lang = Configuration::get('PS_LANG_DEFAULT');

$insert_new_order_state_lang = Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_."order_state_lang(id_order_state,id_lang,name,template) VALUES ('".$id_new_order_state."','".$id_lang."','Error al enviar a OBUMA','')");






$check_exists_obuma_rut = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."customer WHERE Field = 'obuma_rut'");
if(!isset($check_exists_obuma_rut[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_. "customer ADD obuma_rut varchar(15) not null";
}

$check_exists_obuma_id_customer = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."customer WHERE Field = 'obuma_id_customer'");
if(!isset($check_exists_obuma_id_customer[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_. "customer ADD obuma_id_customer int(11) not null";
}

$check_exists_obuma_id_product = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."product WHERE Field = 'obuma_id_product'");
if(!isset($check_exists_obuma_id_product[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_. "product ADD obuma_id_product int(11) not null";
}
$check_exists_obuma_id_category = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."category WHERE Field = 'obuma_id_category'");
if(!isset($check_exists_obuma_id_category[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_. "category ADD obuma_id_category int(11) not null";
}



foreach ($sql as $s) {
        
        if(!Db::getInstance()->execute($s)){
            return false;
        }
}


?>