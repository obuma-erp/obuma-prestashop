<?php
if(!defined('_PS_VERSION_')){
    exit();
}

require_once "obuma_conector.php";
require_once "functions.php";


class Obuma extends Module{

    public function __construct(){


        $this->name = "obuma";
        $this->tab = "front_office_features";
        $this->version = "1.0.0"; 
        $this->author = "Obuma";
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array("min" => '1.6' , "max" => _PS_VERSION_ );
        parent::__construct();
        $this->displayName = $this->l("Integracion API OBUMA");
        $this->description = $this->l("Este modulo permite obtener informacion de API OBUMA");
        $this->confirmUninstall = $this->l('Deseas Desinstalar el Modulo?');
        
    }
    
    public function install(){
        include_once($this->local_path."sql/install.php");

        if(!parent::install() ||
            !Configuration::updateValue("rut_empresa","") || 
            !Configuration::updateValue("bodega","") || 
            !Configuration::updateValue("id_bodega","") || 
            !Configuration::updateValue("api_key","") || 
            !Configuration::updateValue("sucursal","") || 
            !Configuration::updateValue("vendedor","") || 
            !Configuration::updateValue("usuario","") || 
            !Configuration::updateValue("lista_precio","") || 
            !Configuration::updateValue("codigo_forma_pago","") || 
            !Configuration::updateValue("rebajar_stock",0) || 
            !Configuration::updateValue("cliente_actualizar_datos",0) || 
            !Configuration::updateValue("registrar_contabilidad",0) || 
            !Configuration::updateValue("enviar_email_cliente",0) || 
            !Configuration::updateValue("registrar_cobro",0) || 
            !Configuration::updateValue("tipo_documento","[]") || 

            !$this->registerHook("DisplayBackOfficeHeader") || 
            !$this->registerHook("Header") ||
            !$this->registerHook("actionOrderStatusPostUpdate") || 
            !$this->registerHook("actionPaymentConfirmation") || 
            !$this->registerHook("AdditionalCustomerFormFields") || 
            !$this->registerHook("ValidateCustomerFormFields") || 
            !$this->registerHook("actionCustomerAccountUpdate") || 
            !$this->registerHook("actionCustomerAccountAdd") || 
            !$this->registerHook("actionValidateOrder") || 
            
            !$this->createTabLink()){

            return false;

        }else{
            
            return true;

        }
    }
    
    public function uninstall(){
        include_once($this->local_path."sql/uninstall.php");
        if(!parent::uninstall() ||
           !Configuration::deleteByName("rut_empresa") || 
           !Configuration::deleteByName("bodega") ||
           !Configuration::deleteByName("id_bodega") ||
           !Configuration::deleteByName("api_key") ||
           !Configuration::deleteByName("sucursal") || 
           !Configuration::deleteByName("vendedor") ||
           !Configuration::deleteByName("usuario") || 
           !Configuration::deleteByName("lista_precio") || 
           !Configuration::deleteByName("codigo_forma_pago") || 
           !Configuration::deleteByName("rebajar_stock") || 
           !Configuration::deleteByName("cliente_actualizar_datos") || 
           !Configuration::deleteByName("registrar_contabilidad") || 
           !Configuration::deleteByName("enviar_email_cliente") || 
           !Configuration::deleteByName("registrar_cobro") ||
           !Configuration::deleteByName("tipo_documento") ||  
           !$this->deleteTabLink()){
            return false;
        }else{
            return true;
        }
    }
     public function hookDisplayBackOfficeHeader($params) {
        if (Tools::getValue("controller") === "AdminSincronizacion" || Tools::getValue("controller") === "AdminConfiguracion" || Tools::getValue("controller") === "AdminLogOrdenes" || Tools::getValue("controller") === "AdminVincularCategorias") {
            
            $this->context->controller->addCSS($this->local_path."views/css/back_obuma.css");
            $this->context->controller->addJS(__PS_BASE_URI__."/js/jquery/jquery-1.11.0.min.js");
            $this->context->controller->addJS($this->local_path."views/js/back_obuma.js");
            $this->context->controller->addCSS("http://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css");
            $this->context->controller->addJS("http://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js");

        }
    }

    public function hookHeader(){
        $this->context->controller->addJS($this->local_path."views/js/app.js");
    }

    public function hookActionOrderStatusPostUpdate($params){
         $id = $params["id_order"];

        if (strtolower($params["newOrderStatus"]->name) == "entregado" || strtolower($params["newOrderStatus"]->name) == "delivered") {
        $data = array();
       
        $order_obuma_existe = Db::getInstance()->executeS("SELECT * FROM ". _DB_PREFIX_."order_obuma WHERE order_id='".$id."'");

            if (count($order_obuma_existe) == 0) {
                $order = new Order($id);
                $productos_orden = $order->getProductsDetail();
                $data["orden_id"] = $id;

                $customer = new Customer($order->id_customer);

                $datos_obuma = Db::getInstance()->executeS("SELECT * FROM ". _DB_PREFIX_."order_obuma_customer WHERE id_customer='".$order->id_customer."'");


                $data["giro_comercial"] = $datos_obuma[0]["giro_comercial"];
                $data["rut"] = $datos_obuma[0]["rut"];
                $data["tipo_documento"] = $datos_obuma[0]["tipo_documento"];
                $data["email"] = $customer->email;
                $data["telefono"] = $order->delivery_number;


                $address = new Address($order->id_address_delivery);


                $data["razon_social"] = $address->company;
                $data["direccion"] = $order->id_address_delivery;

                $data["sucursal"] = Configuration::get("sucursal");
                $data["bodega"] = Configuration::get("bodega");
                $data["usuario"] = Configuration::get("usuario");
                $data["vendedor"] = Configuration::get("vendedor");
                $data["lista_precio"] = Configuration::get("lista_precio");
                $data["rebajar_stock"] = Configuration::get("rebajar_stock");
                $data["registrar_contabilidad"] = Configuration::get("registrar_contabilidad");
                $data["enviar_email_cliente"] = Configuration::get("enviar_email_cliente");
                $data["registrar_cobro"] = Configuration::get("registrar_cobro");
                
                if(Configuration::get("registrar_cobro") == 1){
                    $data["total_pagado"] = $order->total_paid_tax_incl;
                    $data["total_por_pagar"] = 0;
                }else{
                    $data["total_pagado"] = 0;
                    $data["total_por_pagar"] = $order->total_paid_tax_incl;
                }

                $data["cliente_actualizar_datos"] = Configuration::get("cliente_actualizar_datos");
                $payment_method = $order->payment;
                $forma_pago_ = explode('#', $payment_method);
                if (isset($forma_pago_[1])) {
                    $venta_forma_pago = $forma_pago_[1];
                }else{
                     $venta_forma_pago = Configuration::get("codigo_forma_pago");
                }
                
                $data["forma_pago"] = $venta_forma_pago;
                $data["total_neto"] = ($order->total_paid_tax_incl / 1.19);
                $data["subtotal"] = $order->total_paid_tax_incl;
                $data["total_envio"] = $order->total_shipping;
                $data["total"] = $order->total_paid_tax_incl;


                $indice = 0;
                
                foreach ($productos_orden as $po) {
                    $data["orden_detalle"][$indice]["codigo_comercial"] = $po["reference"];
                    $data["orden_detalle"][$indice]["nombre"] = $po["product_name"];
                    $data["orden_detalle"][$indice]["cantidad"] = $po["product_quantity"];
                    $data["orden_detalle"][$indice]["precio"] = $po["product_price"];
                    $data["orden_detalle"][$indice]["subtotal"] = $po["total_price_tax_excl"];
                    $indice++;
                }
                
                
                $carrier = new Carrier($order->id_carrier);

                $data["orden_detalle"][$indice]["codigo_comercial"] = 'envio';
                $data["orden_detalle"][$indice]["nombre"] = $carrier->name;
                $data["orden_detalle"][$indice]["cantidad"] = 1;
                $data["orden_detalle"][$indice]["precio"] = $order->total_shipping;
                $data["orden_detalle"][$indice]["subtotal"] = $order->total_shipping;

        
                $response = $this->enviar_orden_venta($data);

                $datos_log  = array('data' => $response["peticion"], "response" => $response["respuesta"],"estado" => strtolower($params["newOrderStatus"]->name));
                $this->insert_order_obuma_log($datos_log,$id);

       
                if (isset($response["respuesta"]["result"]["result_dte"][0]["dte_result"]) && $response["respuesta"]["result"]["result_dte"][0]["dte_result"] == "OK") {

                    $result_dte = $response["respuesta"]["result"]["result_dte"][0];
                    $this->insert_order_obuma(
                        array('order_id' => $id,
                          'dte_id' => $result_dte["dte_id"],
                          'dte_tipo' => $result_dte["dte_tipo"],
                          'dte_folio' => $result_dte["dte_folio"],
                          'dte_result' => $result_dte["dte_result"],
                          'dte_xml' => $result_dte["dte_xml"],
                          'dte_pdf' => $result_dte["dte_pdf"]
                            )
                    );
                }


            }else{
                $datos_log  = array('data' => [], "response" => [],"estado" => strtolower($params["newOrderStatus"]->name));
                $this->insert_order_obuma_log($datos_log,$id);
            }


        }else{

            $datos_log  = array('data' => [], "response" => [],"estado" => strtolower($params["newOrderStatus"]->name));
            $this->insert_order_obuma_log($datos_log,$id);
        
        }


    }



    public function hookActionPaymentConfirmation($params){
        
        $history = new OrderHistory();
        $history->changeIdOrderState(5, (int)$params["id_order"]);
        $history->add(true);
    }


    public function hookAdditionalCustomerFormFields($params){

        $extra_fields = array();

        $tipo_documento = Configuration::get("tipo_documento");
        $tipo_documento = json_decode($tipo_documento);

        $data_tipo_documento = array();
        //$data_tipo_documento[""] = 'Selecciona tipo de documento';

        if (in_array("39",$tipo_documento)) {
            $data_tipo_documento["39"] = "Boleta";
        }

        if (in_array("33",$tipo_documento)) {
            $data_tipo_documento["33"] = "Factura";
        }

         $datos_obuma = Db::getInstance()->executeS("SELECT * FROM ". _DB_PREFIX_."order_obuma_customer WHERE id_customer='".$params["cookie"]->id_customer."'");
        
        if (count($datos_obuma) > 0) {
            $rut_customer = $datos_obuma[0]["rut"];
            $giro_comercial_customer = $datos_obuma[0]["giro_comercial"];
            $tipo_documento_customer = $datos_obuma[0]["tipo_documento"];
        }else{
            $rut_customer = '';
            $giro_comercial_customer = '';
            //$tipo_documento_customer = $datos_obuma[0]["tipo_documento"];
        }


        $extra_fields['obuma_tipo_documento'] = (new FormField)->setName('obuma_tipo_documento')->setLabel('Tipo de documento', [], 'Shop.Forms.Labels')->setType("select")->setAvailableValues($data_tipo_documento)->setRequired(true);

        $extra_fields['obuma_rut'] = (new FormField)->setName('obuma_rut')->setLabel('Rut', [], 'Shop.Forms.Labels')->setValue($rut_customer)->setRequired(true);


        $extra_fields['order_obuma_giro_comercial'] = (new FormField)->setName('order_obuma_giro_comercial')->setLabel('Giro comercial', [], 'Shop.Forms.Labels')->setValue($giro_comercial_customer)->setRequired(true);

        return $extra_fields;


    }


    public function hookValidateCustomerFormFields($params){
        
        $module_fields = $params['fields'];

        foreach ($module_fields as $field) {
            if ($field->getName() == 'obuma_rut') {
                if(esRut($field->getValue()) == false){ 
                    $module_fields[1]->addError(
                    $this->l('Rut Incorrecto')
                    );
                }
            }
        }
        
        return array($module_fields);

    
    }

    public function hookactionCustomerAccountAdd($params){
        $id_customer = (int)$params["newCustomer"]->id;
        
        $obuma_tipo_documento = Tools::getValue("obuma_tipo_documento");
        $obuma_rut = Tools::getValue("obuma_rut");
        $obuma_giro_comercial = trim(Tools::getValue("order_obuma_giro_comercial"));
        
        //var_dump($params);exit();
        $order_obuma_customer = Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_."order_obuma_customer (id_customer,tipo_documento,rut,giro_comercial) VALUES ('".$id_customer."','".$obuma_tipo_documento."','".$obuma_rut."','".$obuma_giro_comercial."')");

    }

    public function hookactionCustomerAccountUpdate($params){
        $id_customer = (int)$params["customer"]->id;

        //var_dump($params);exit();
        
        $obuma_tipo_documento = Tools::getValue("obuma_tipo_documento");
        $obuma_rut = Tools::getValue("obuma_rut");
        $obuma_giro_comercial = trim(Tools::getValue("order_obuma_giro_comercial"));

            $order_obuma_customer = Db::getInstance()->execute("UPDATE ". _DB_PREFIX_."order_obuma_customer SET tipo_documento='".$obuma_tipo_documento."',rut='".$obuma_rut."',giro_comercial='".$obuma_giro_comercial."'  WHERE id_customer='".$id_customer."'");        
    } 

    public function hookactionValidateOrder($params){
        $cart = $params["cart"];
        $order = $params["order"];
        $customer = $params["customer"];
        $currency = $params["currency"];
        $orderStatus = $params["orderStatus"];

        $order_obuma_customer = Db::getInstance()->execute("UPDATE ". _DB_PREFIX_."order_obuma_customer SET id_order='".$order->id."' WHERE id_customer='".$customer->id."'");


    }

    public function createTabLink(){
        

//Main Parent menu

      $parentTab = new Tab();
      $parentTab->active = 1;
      $parentTab->name = array();
      $parentTab->class_name = "AdminObuma";
      foreach (Language::getLanguages() as $language) {
          $parentTab->name[$language['id_lang']] = 'Obuma Sync';
      }
      $parentTab->id_parent = (int) Tab::getIdFromClassName('DEFAULT');;
      $parentTab->module = '';
      $parentTab->add();



//Sub menu code

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminConfiguracion";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Configurar');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();



//Sub menu code

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminSincronizacion";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Sincronizar');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();


//Sub menu code

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminLogOrdenes";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Log de ordenes');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();


//Sub menu code

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminVincularCategorias";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Vincular categorias');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();

        return true;
    }


     private function deleteTabLink(){
        $tabId = (int) Tab::getIdFromClassName('AdminObuma');
        if (!$tabId) {
            return true;
        }
        $tab = new Tab($tabId);
        return $tab->delete();
    }




    private function enviar_orden_venta($data){

    $data_enviar = array(
    'venta_tipo_dcto'       => $data["tipo_documento"], // el codigo del tipo documento segun normas del SII
    'venta_nro_dcto'            => "", // irrelevante, obuma maneja el folio
    'venta_fecha'           => date("Y-m-d"),
    'venta_sucursal'            => $data["sucursal"], // codigo creado en obuma
    'venta_bodega'          => $data["bodega"], // codigo creado en obuma
    'venta_lista_precio'        => $data["lista_precio"], // codigo creado en obuma
    'venta_usuario'             => $data["usuario"], // codigo creado en obuma
    'venta_vendedor'            => $data["vendedor"], // codigo creado en obuma
    'venta_subtotal'            => number_format($data["subtotal"],0,'.',''),
    'venta_rebajar_stock'   => $data["rebajar_stock"],
    'venta_registrar_contabilidad'   => $data["registrar_contabilidad"],
    'venta_enviar_email_cliente'   => $data["enviar_email_cliente"],
    'venta_registrar_cobro'   => $data["registrar_cobro"],
    'venta_forma_pago'   => $data["forma_pago"],
    'venta_exento'          => 0,
    'venta_neto'                => number_format($data["total_neto"],0,'.',''), 
    'venta_iva'                 => number_format(($data["total"] - $data["total_neto"]),0,'.',''),
    'venta_total'           => number_format($data["total"],0,'.',''),
    'venta_total_pagado'    => number_format($data["total_pagado"],0,'.',''),
    'venta_total_por_pagar' => number_format($data["total_por_pagar"],0,'.',''),
    'cliente_actualizar_datos' => $data["cliente_actualizar_datos"],
    'cliente_rut'           => $data["rut"], 
    'cliente_razon_social'  => $data["razon_social"],
    'cliente_direccion'     => $data["direccion"],
    'cliente_comuna'        => '',
    'cliente_region'        => '',
    'cliente_giro'          => $data["giro_comercial"],
    'cliente_email'         => $data["email"],
    'cliente_telefono'      => $data["telefono"]

    );



    foreach ($data["orden_detalle"] as $key => $producto) {
        
        $data_enviar["venta_detalle"][$key]  = array(
                                    'codigo_comercial' => $producto["codigo_comercial"], // codigo sku
                                    'producto_nombre'           => $producto["nombre"],
                                    'mostrar_descripcion'       => '',
                                    'producto_descripcion'      => '',
                                    'cantidad'                  => $producto["cantidad"],  
                                    'producto_precio'           => number_format($producto["precio"],0,'.',''),
                                    'descuento'                 => 0,
                                    'descuento_monto'           => 0,
                                    'subtotal'                  => number_format($producto["subtotal"],0,'.',''),
                                    'producto_exento'           => "", // si el producto es exento indicar 1
                                    'unidad_medida'             => ""
        );
    }


    $data_enviar["venta_referencias"][0] = array(
                                    'tipo_dcto_ref' => '802', 
                                    'folio_dcto_ref' => $data["orden_id"],
                                    'fecha_dcto_ref' => date("Y-m-d"),
                                    'razon_ref' => 'Prestashop');
             

$data_enviar =  array('docs' => [$data_enviar]); 

$json = ObumaConector::post("http://api.obuma.cl/v1.0/ventasIntegracionExternas.create.json",$data_enviar,Configuration::get("api_key"));
                
    $json = json_encode($json, true);
    $json = json_decode($json, true);

    
    $return = array('peticion' => $data_enviar , "respuesta" => $json);

    return $return;
}



 private function insert_order_obuma_log($data,$order_id){
   
    Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_. "order_obuma_log(order_id,fecha,hora,peticion,respuesta,estado) VALUES " . "('".$order_id."','".date('Y-m-d')."','".date("H:i:s")."','".json_encode($data["data"])."','".print_r($data["response"],true)."','".$data["estado"]."')");

}


private function insert_order_obuma($data){

   Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_."order_obuma(order_id,dte_id,dte_tipo,dte_folio,dte_result,dte_xml,dte_pdf,fecha,hora) VALUES " . "('".$data["order_id"]."','".$data['dte_id']."','".$data['dte_tipo']."','".$data['dte_folio']."','".$data['dte_result']."','".$data["dte_xml"]."','".$data["dte_pdf"]."','".date('Y-m-d')."','".date("H:i:s")."')");

}

}
