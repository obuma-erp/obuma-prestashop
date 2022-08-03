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

		$where = "";

		if(isset($_POST["btn_search"])){

			$search_value = $_POST['search'];

			$where = "WHERE fecha LIKE '%{$search_value}%' OR tipo LIKE '%{$search_value}%' OR peticion LIKE '%{$search_value}%'  OR resultado LIKE '%{$search_value}%'";

		}

		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_log_webhook {$where} ORDER BY id DESC";

		$log_webhook = Db::getInstance()->executeS($sql);

		$this->context->smarty->assign("check_version",check_version_module_obuma());
		$this->context->smarty->assign("log_webhook",$log_webhook);
		$this->setTemplate('log_webhook.tpl');
	}
}


?>