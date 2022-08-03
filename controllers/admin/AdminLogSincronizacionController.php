<?php 

class AdminLogSincronizacionController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();

		$where = "";

		if(isset($_POST["btn_search"])){

			$search_value = $_POST['search'];

			$where = "WHERE fecha LIKE '%{$search_value}%' OR tipo LIKE '%{$search_value}%' OR opcion LIKE '%{$search_value}%'  OR resultado LIKE '%{$search_value}%'";

		}

		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_log_synchronization {$where} ORDER BY id DESC";

		$log_sincronizacion = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("check_version",check_version_module_obuma());
		$this->context->smarty->assign("log_sincronizacion",$log_sincronizacion);
		$this->setTemplate('log_sincronizacion.tpl');
	}
}


?>