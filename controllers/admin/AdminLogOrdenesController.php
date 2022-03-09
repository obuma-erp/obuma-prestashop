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

		$sql = "SELECT * FROM ". _DB_PREFIX_."order_obuma_log";

		$log_ordenes = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("log_ordenes",$log_ordenes);
		$this->setTemplate('log_ordenes.tpl');
	}
}


?>