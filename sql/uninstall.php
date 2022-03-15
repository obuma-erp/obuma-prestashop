<?php 


$sql = array();

$sql[] = "ALTER TABLE ". _DB_PREFIX_."customer DROP obuma_rut";
$sql[] = "ALTER TABLE ". _DB_PREFIX_."customer DROP obuma_id_customer";
$sql[] = "ALTER TABLE ". _DB_PREFIX_."product DROP obuma_id_product";
$sql[] = "ALTER TABLE ". _DB_PREFIX_."category DROP obuma_id_category";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."order_obuma";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_order";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_synchronization";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."obuma_log_webhook";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."vincular_categorias_obuma";

$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."order_obuma_customer";

foreach ($sql as $s) {
		
		if(!Db::getInstance()->execute($s)){
			return false;
		}
}

?>