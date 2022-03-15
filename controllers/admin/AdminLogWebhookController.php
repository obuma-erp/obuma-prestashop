<?php 

class AdminLogWebhookController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();

		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_log_webhook";

		$log_webhook = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("log_webhook",$log_webhook);
		$this->setTemplate('log_webhook.tpl');
	}
}


?>