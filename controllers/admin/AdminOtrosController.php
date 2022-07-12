<?php 

class AdminOtrosController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();

		//$url = _PS_BASE_URL_.__PS_BASE_URI__."modules/obuma/";
		
		$url = Tools::getHttpHost(true).__PS_BASE_URI__."modules/obuma/";
		
		$this->context->smarty->assign("url_plugin",$url);
		$this->setTemplate('otros.tpl');
	}
}


?>