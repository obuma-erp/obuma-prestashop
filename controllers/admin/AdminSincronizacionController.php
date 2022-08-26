<?php 

class AdminSincronizacionController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();

		$json = ObumaConector::get(set_url()."empresa.findByAPIKey.json/".Configuration::get("api_key"),Configuration::get("api_key"));

		$cookie = new Cookie("psAdmin");
		$id_employee = $cookie->__get("id_employee");
		$controller = "AdminConfiguracion";
		$id_class = Tab::getIdFromClassName($controller);

		$token_configuracion = Tools::getAdminToken($controller.$id_class.$id_employee);


		
		$this->context->smarty->assign("check_version",$this->check_version_module_obuma());
		$this->context->smarty->assign("response_connect_success",$json);
		$this->context->smarty->assign("token_configuracion",$token_configuracion);
		$this->setTemplate('sincronizacion.tpl');
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