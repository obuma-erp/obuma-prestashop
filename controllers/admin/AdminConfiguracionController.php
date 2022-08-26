<?php 


class AdminConfiguracionController extends ModuleAdminController{

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();
		$this->bootstrap = true;
	}

	public function initContent(){
		parent::initContent();

		$this->getContent();
        $this->context->smarty->assign("check_version",$this->check_version_module_obuma());
		$this->setTemplate('configuracion.tpl');
	}

	    public function getContent(){
        $this->context->smarty->assign("save",false);
        if(Tools::isSubmit("enviar_datos")){

            $rut_empresa = trim(Tools::getValue("rut_empresa"));
            $bodega = trim(Tools::getValue("bodega"));
            $id_bodega = trim(Tools::getValue("id_bodega"));
            $api_key = trim(Tools::getValue("api_key"));
            $api_url = trim(Tools::getValue("api_url"));
            $sucursal = trim(Tools::getValue("sucursal"));
            $vendedor = trim(Tools::getValue("vendedor"));
            $usuario = trim(Tools::getValue("usuario"));
            $canal_venta = trim(Tools::getValue("canal_venta"));
            $lista_precio = trim(Tools::getValue("lista_precio"));
            $codigo_forma_pago = trim(Tools::getValue("codigo_forma_pago"));
            $rebajar_stock = Tools::getValue("rebajar_stock");
            $cliente_actualizar_datos = Tools::getValue("cliente_actualizar_datos");
            $registrar_contabilidad = Tools::getValue("registrar_contabilidad");
            $enviar_email_cliente = Tools::getValue("enviar_email_cliente");
            $registrar_cobro = Tools::getValue("registrar_cobro");

            $tipo_documento = is_array(Tools::getValue("tipo_documento")) ?  json_encode(Tools::getValue("tipo_documento")) : "[]";
            $nota_venta_segundo_plano = Tools::getValue("nota_venta_segundo_plano");
            $enviar_ventas_obuma = Tools::getValue("enviar_ventas_obuma");
            $cambiar_a_completado = Tools::getValue("cambiar_a_completado");
            $sincronizar_precio = Tools::getValue("sincronizar_precio");
            $sincronizar_cliente_por = Tools::getValue("sincronizar_cliente_por");
            $update_limpiar_registros_date = Tools::getValue("update_limpiar_registros_date");
            $estado_enviar_obuma = Tools::getValue("estado_enviar_obuma");

            $proveedores_actualizar_stock = trim(Tools::getValue("proveedores_actualizar_stock"));

            Configuration::updateValue("rut_empresa",$rut_empresa);
            Configuration::updateValue("bodega",$bodega);
            Configuration::updateValue("id_bodega",$id_bodega);
            Configuration::updateValue("api_key",$api_key);
            Configuration::updateValue("api_url",$api_url);
            Configuration::updateValue("sucursal",$sucursal);
            Configuration::updateValue("vendedor",$vendedor);
            Configuration::updateValue("usuario",$usuario);
            Configuration::updateValue("canal_venta",$canal_venta);
            Configuration::updateValue("lista_precio",$lista_precio);
            Configuration::updateValue("codigo_forma_pago",$codigo_forma_pago);
            Configuration::updateValue("rebajar_stock",$rebajar_stock);
            Configuration::updateValue("cliente_actualizar_datos",$cliente_actualizar_datos);
            Configuration::updateValue("registrar_contabilidad",$registrar_contabilidad);
            Configuration::updateValue("enviar_email_cliente",$enviar_email_cliente);
            Configuration::updateValue("registrar_cobro",$registrar_cobro);
            Configuration::updateValue("tipo_documento",$tipo_documento);
            Configuration::updateValue("nota_venta_segundo_plano",$nota_venta_segundo_plano);
            Configuration::updateValue("enviar_ventas_obuma",$enviar_ventas_obuma);
            Configuration::updateValue("cambiar_a_completado",$cambiar_a_completado); 
            Configuration::updateValue("sincronizar_precio",$sincronizar_precio);
            Configuration::updateValue("sincronizar_cliente_por",$sincronizar_cliente_por);
            Configuration::updateValue("update_limpiar_registros_date",$update_limpiar_registros_date);
            Configuration::updateValue("estado_enviar_obuma",$estado_enviar_obuma);
            Configuration::updateValue("proveedores_actualizar_stock",$proveedores_actualizar_stock);

            $this->context->smarty->assign("save",true);         


        }
        
        
        $rut_empresa_text = Configuration::get("rut_empresa");
        $bodega_text = Configuration::get("bodega");
        $id_bodega_text = Configuration::get("id_bodega");
        $api_key_text = Configuration::get("api_key");
        $api_url_text = Configuration::get("api_url");
        $sucursal_text = Configuration::get("sucursal");
        $vendedor_text = Configuration::get("vendedor");
        $usuario_text = Configuration::get("usuario");
        $canal_venta_text = Configuration::get("canal_venta");
        $lista_precio_text = Configuration::get("lista_precio");
        $codigo_forma_pago_text = Configuration::get("codigo_forma_pago");
        $rebajar_stock_text = Configuration::get("rebajar_stock");
        $cliente_actualizar_datos_text = Configuration::get("cliente_actualizar_datos");
        $registrar_contabilidad_text = Configuration::get("registrar_contabilidad");
        $enviar_email_cliente_text = Configuration::get("enviar_email_cliente");
        $registrar_cobro_text = Configuration::get("registrar_cobro");
        $tipo_documento_text = Configuration::get("tipo_documento");
        $nota_venta_segundo_plano_text = Configuration::get("nota_venta_segundo_plano");
        $enviar_ventas_obuma_text = Configuration::get("enviar_ventas_obuma");
        $cambiar_a_completado_text = Configuration::get("cambiar_a_completado");
        $sincronizar_precio_text = Configuration::get("sincronizar_precio");
        $sincronizar_cliente_por_text = Configuration::get("sincronizar_cliente_por");
        $update_limpiar_registros_date_text = Configuration::get("update_limpiar_registros_date");
        $estado_enviar_obuma_text = Configuration::get("estado_enviar_obuma");
        $proveedores_actualizar_stock_text = Configuration::get("proveedores_actualizar_stock");



        $this->context->smarty->assign("rut_empresa",$rut_empresa_text);
        $this->context->smarty->assign("bodega",$bodega_text);
        $this->context->smarty->assign("id_bodega",$id_bodega_text);
        $this->context->smarty->assign("api_key",$api_key_text);
        $this->context->smarty->assign("api_url",$api_url_text);
        $this->context->smarty->assign("sucursal",$sucursal_text);
        $this->context->smarty->assign("vendedor",$vendedor_text);
        $this->context->smarty->assign("usuario",$usuario_text);
        $this->context->smarty->assign("canal_venta",$canal_venta_text);
        $this->context->smarty->assign("lista_precio",$lista_precio_text);
        $this->context->smarty->assign("codigo_forma_pago",$codigo_forma_pago_text);
        $this->context->smarty->assign("rebajar_stock",$rebajar_stock_text);
        $this->context->smarty->assign("cliente_actualizar_datos",$cliente_actualizar_datos_text);
        $this->context->smarty->assign("registrar_contabilidad",$registrar_contabilidad_text);
        $this->context->smarty->assign("enviar_email_cliente",$enviar_email_cliente_text);
        $this->context->smarty->assign("registrar_cobro",$registrar_cobro_text);
        $this->context->smarty->assign("tipo_documento",$tipo_documento_text);
        $this->context->smarty->assign("nota_venta_segundo_plano",$nota_venta_segundo_plano_text);
        $this->context->smarty->assign("enviar_ventas_obuma",$enviar_ventas_obuma_text);
        $this->context->smarty->assign("cambiar_a_completado",$cambiar_a_completado_text);
        $this->context->smarty->assign("sincronizar_precio",$sincronizar_precio_text);
        $this->context->smarty->assign("sincronizar_cliente_por",$sincronizar_cliente_por_text);
        $this->context->smarty->assign("update_limpiar_registros_date",$update_limpiar_registros_date_text);

        $this->context->smarty->assign("estado_enviar_obuma",$estado_enviar_obuma_text);
        $this->context->smarty->assign("proveedores_actualizar_stock",$proveedores_actualizar_stock_text);
        $status = new OrderStateCore();
        $estados = $status::getOrderStates(1);


        $this->context->smarty->assign("estados",$estados);
        //return $this->display(__FILE__,"views/templates/admin/configuracion/configuracion.tpl");
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