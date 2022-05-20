<?php 
require_once "../../config/config.inc.php";
require_once "../../init.php";

$date_now = date('Y-m-d');

$date_future = strtotime('-60 day', strtotime($date_now));

$date_future = date('Y-m-d', $date_future);

$sql = [];

$sql[] = "DELETE FROM "._DB_PREFIX_."obuma_log_order WHERE fecha < '".$date_future."'";

$sql[] = "DELETE FROM "._DB_PREFIX_."obuma_log_webhook WHERE fecha < '".$date_future."'";

$sql[] = "DELETE FROM "._DB_PREFIX_."obuma_log_synchronization WHERE fecha < '".$date_future."'";

foreach ($sql as $key => $value) {

	Db::getInstance()->execute($value);

}

Configuration::updateValue("update_limpiar_registros_date",date("Y-m-d H:i:s"));

echo json_encode(array("result" => "true","date" => Configuration::get('update_limpiar_registros_date')));
