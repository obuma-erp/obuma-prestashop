<?php 

class ObumaEjemploModuleFrontController extends ModuleFrontController
{
	
	public function __construct(){
		
		parent::__construct();
	}

	public function init(){
		parent::init();
	}
	public function initContent(){
		parent::initContent();
		$this->context->smarty->assign(array(
			"categorias" => Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."category_lang"),
			"cantidad_categorias" => Db::getInstance()->getValue("SELECT count(name) FROM "._DB_PREFIX_."category_lang"),
			"modulo" => Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."module ORDER BY name DESC")
		));
		$this->setTemplate("module:obuma/views/templates/front/ejemplo.tpl");

	}
}


?>