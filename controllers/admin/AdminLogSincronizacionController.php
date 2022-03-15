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

		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_log_synchronization";

		$log_sincronizacion = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("log_sincronizacion",$log_sincronizacion);
		$this->setTemplate('log_sincronizacion.tpl');
	}
}


?>