<?php
require_once "../../config/config.inc.php";
require_once "../../init.php";


	function obtener_categorias(){
		if(isset($_POST["obtener"])) {
			global $wpdb;
			$indice = 0;
			$categorias_vinculadas = [];
			$categorias = Db::getInstance()->executeS("SELECT id_category,obuma_id_category  as producto_categoria_id,name_category as producto_categoria_nombre FROM "._DB_PREFIX_."vincular_categorias_obuma WHERE obuma_id_category > 0 ORDER BY name_category ASC");

			foreach ($categorias as $cat) {
				$categorias_taxonomy = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."category c INNER JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category WHERE c.id_category='".$cat["id_category"]."'");

				if (count($categorias_taxonomy) > 0) {
					$categorias_vinculadas[$indice]["producto_categoria_id"] = $cat["producto_categoria_id"];
					$categorias_vinculadas[$indice]["producto_categoria_nombre"] = $cat["producto_categoria_nombre"];
					$indice++;
				}
			}
			echo json_encode($categorias_vinculadas);

			//get_terms("product_cat");
		}
	}


	obtener_categorias();
