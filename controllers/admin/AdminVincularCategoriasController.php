<?php 

class AdminVincularCategoriasController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();
		
		//$categories = Category::getCategories();
		$this->agregar_categoria_vinculada();
		$this->obtenerCategorias();
		
		$this->context->smarty->assign("check_version",check_version_module_obuma());
		$this->setTemplate('vincular_categorias.tpl');
	}

	public function obtenerCategorias(){
		$data = array();
		$categories = Category::getAllCategoriesName();

		foreach ($categories as $key => $cat) {
			$data[$key]["id_category"] = $cat["id_category"];
			$data[$key]["name"] =  $cat["name"];
			$category = $this->verificar_id_categoria_vinculada($cat["id_category"]);
 				if(count($category) == 1){
 					$data[$key]["obuma_id_category"] = $category[0]["obuma_id_category"];
 				}else{
 					$data[$key]["obuma_id_category"] = 0;
 				}
			
		}
		$this->context->smarty->assign("categorias_vinculadas",count($this->obtener_categorias_vinculadas()));
		$this->context->smarty->assign("cantidad_categorias",count($data));
		$this->context->smarty->assign("categories",$data);

	}
	public function agregar_categoria_vinculada(){
		if(Tools::isSubmit("vincular_categorias")){
			$sql = "";
            $obuma_id_category = Tools::getValue("obuma_id_category");
            $id_category = Tools::getValue("id_category");
            $name_category = Tools::getValue("name_category");

            for ($i=0; $i < count($id_category) ; $i++) { 
 				$category = $this->verificar_id_categoria_vinculada($id_category[$i]);
 				if(count($category) == 1){
 					$sql = "UPDATE " . _DB_PREFIX_."obuma_vincular_categorias SET name_category='".$name_category[$i]."',obuma_id_category='".$obuma_id_category[$i]."' WHERE id_category='".$category[0]['id_category']."'";

 				}else{
 					$sql = "INSERT INTO " . _DB_PREFIX_."obuma_vincular_categorias (id_category,name_category,obuma_id_category) VALUES ('".$id_category[$i]."','".$name_category[$i]."','".$obuma_id_category[$i]."')";

					
 				}
            		
				Db::getInstance()->execute($sql);	
            }

            $this->context->smarty->assign("save",true);
            

        }
		
	}

	public function verificar_id_categoria_vinculada($id_category){
		$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_vincular_categorias WHERE id_category='".$id_category."'";

		$result = Db::getInstance()->executeS($sql);

		return $result;	

	}


	public function obtener_categorias_vinculadas(){
		$data = array();
		$categories = Category::getAllCategoriesName();

		
		foreach ($categories as $key => $cat) {
			$sql = "SELECT * FROM ". _DB_PREFIX_."obuma_vincular_categorias WHERE id_category='".$cat["id_category"]."' AND obuma_id_category > 0";

			$result = Db::getInstance()->executeS($sql);
			if (count($result) == 1) {
				$data[$key]["id_category"] = $cat["id_category"];
				$data[$key]["name_category"] = $cat["name"];
				$data[$key]["obuma_id_category"] = $result[0]["obuma_id_category"];
			}
		}

		return $data;

	}
}


?>