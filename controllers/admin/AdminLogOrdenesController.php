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

		$this->context->smarty->assign("check_version",$this->check_version_module_obuma());
		$this->context->smarty->assign("log_ordenes",$log_ordenes);
		$this->setTemplate('log_ordenes.tpl');
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