<?php 

$sql = array();
/*$sql[] = "CREATE TABLE IF NOT EXISTS ". _DB_PREFIX_."customer_obuma(
		id int(11) PRIMARY KEY AUTO_INCREMENT,
		id_customer_obuma int(11) not null,
		rut_obuma varchar(15) not null,
		razon_social_obuma varchar(100) not null,
		id_customer int(11) not null) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET=UTF8";
*/


 $table_order_obuma_log = _DB_PREFIX_ . 'order_obuma_log';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_order_obuma_log (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `order_id` int(11)  NOT NULL,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
      `peticion` text  NOT NULL,
      `respuesta` text  NOT NULL,
      `estado` text  NOT NULL,
       PRIMARY KEY (`id`))";
    

    $table_order_obuma = _DB_PREFIX_ . 'order_obuma';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_order_obuma (
      `id` int(11) NOT NULL AUTO_INCREMENT,
       `order_id` int(11)  NOT NULL,
      `dte_id` int(11)  NOT NULL,
       `dte_tipo` int(11)  NOT NULL,
        `dte_folio` int(11)  NOT NULL,
      `dte_result` text  NOT NULL,
      `dte_xml` text  NOT NULL,
      `dte_pdf` text  NOT NULL,
      `fecha` date  NOT NULL,
      `hora` time  NOT NULL,
       PRIMARY KEY (`id`))";
    


    $table_categorias_obuma = _DB_PREFIX_ . 'vincular_categorias_obuma';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_categorias_obuma (
      `id` int(11) NOT NULL AUTO_INCREMENT,
       `id_category` int(11)  NOT NULL,
      `name_category` text  NOT NULL,
       `obuma_id_category` int(11)  NOT NULL,
       PRIMARY KEY (`id`))";

      $table_order_obuma_customer = _DB_PREFIX_ . 'order_obuma_customer';
     $sql[] = "CREATE TABLE IF NOT EXISTS $table_order_obuma_customer (
      `id` int(11) NOT NULL AUTO_INCREMENT,
       `id_order` int(11) NOT NULL,
      `id_customer` int(11) NOT NULL,
       `tipo_documento` int(11) NOT NULL,
       `rut` text  NOT NULL,
       `giro_comercial` text NOT NULL,
       PRIMARY KEY (`id`))";

$sql[] = "ALTER TABLE ". _DB_PREFIX_. "customer ADD obuma_rut varchar(15) not null";
$sql[] = "ALTER TABLE ". _DB_PREFIX_. "customer ADD obuma_id_customer int(11) not null";
$sql[] = "ALTER TABLE ". _DB_PREFIX_. "product ADD obuma_id_product int(11) not null";
$sql[] = "ALTER TABLE ". _DB_PREFIX_. "category ADD obuma_id_category int(11) not null";

foreach ($sql as $s) {
		
		if(!Db::getInstance()->execute($s)){
			return false;
		}
}

?>