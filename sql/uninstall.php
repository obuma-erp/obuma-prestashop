<?php

$sql = array();


$check_exists_invoice_type = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."cart WHERE Field = 'invoice_type'");
if(isset($check_exists_invoice_type[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_."customer DROP invoice_type";
}



$check_exists_obuma_rut = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."customer WHERE Field = 'obuma_rut'");
if(isset($check_exists_obuma_rut[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_."customer DROP obuma_rut";
}

$check_exists_obuma_id_customer = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."customer WHERE Field = 'obuma_id_customer'");
if(isset($check_exists_obuma_id_customer[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_."customer DROP obuma_id_customer";
}

$check_exists_obuma_id_product = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."product WHERE Field = 'obuma_id_product'");
if(isset($check_exists_obuma_id_product[0]["Field"])){
   $sql[] = "ALTER TABLE ". _DB_PREFIX_."product DROP obuma_id_product";
}
$check_exists_obuma_id_category = Db::getInstance()->executeS("SHOW COLUMNS FROM ". _DB_PREFIX_."category WHERE Field = 'obuma_id_category'");
if(isset($check_exists_obuma_id_category[0]["Field"])){
    $sql[] = "ALTER TABLE ". _DB_PREFIX_."category DROP obuma_id_category";
}



$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_order";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_order";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_synchronization";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_webhook";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_vincular_categorias";



$sql[] = "DELETE os,osl FROM "._DB_PREFIX_."order_state os INNER JOIN "._DB_PREFIX_."order_state_lang osl ON os.id_order_state=osl.id_order_state WHERE os.module_name='obuma'";

foreach ($sql as $s) {
		
		if(!Db::getInstance()->execute($s)){
			return false;
		}
}

?>