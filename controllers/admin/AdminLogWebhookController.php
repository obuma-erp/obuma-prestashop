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

		$this->context->smarty->assign("check_version",$this->check_version_module_obuma());
		$this->context->smarty->assign("log_webhook",$log_webhook);
		$this->setTemplate('log_webhook.tpl');
	}




	private function check_version_module_obuma(){


        $response = file_get_contents("https://obuma-cl.s3.us-east-2.amazonaws.com/cdn-utiles/versions_module_prestashop.json");

        $response_decode = json_decode($response,true);

        $result = false;
        $html = "";
        foreach ($response_decode as $key => $version) {
            if($version["version"] > Configuration::get("obuma_module_version")){
                $result = true;
                break;
            }
        }


        return $result;
        
    }
    
}


?>