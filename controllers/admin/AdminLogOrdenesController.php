<?php 

class AdminLogOrdenesController extends ModuleAdminController{

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

			$where = "WHERE order_id LIKE '%{$search_value}%' OR fecha LIKE '%{$search_value}%' OR respuesta LIKE '%{$search_value}%' OR estado LIKE '%{$search_value}%'";

		}

		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_log_order {$where} ORDER BY id DESC";

		

		$log_ordenes = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("check_version",check_version_module_obuma());
		$this->context->smarty->assign("log_ordenes",$log_ordenes);
		$this->setTemplate('log_ordenes.tpl');
	}
}


?>