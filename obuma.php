<?php
if(!defined('_PS_VERSION_')){
    exit();
}



require_once dirname(__FILE__) . "/obuma_conector.php";
require_once dirname(__FILE__) . "/functions.php";



class Obuma extends Module{

    public function __construct(){

        $this->name = "obuma";
        $this->tab = "front_office_features";
        $this->version = "1.0.1"; 
        $this->author = "Obuma";
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array("min" => '1.7' , "max" => _PS_VERSION_ );
        parent::__construct();
        $this->displayName = $this->l("Integracion API OBUMA");
        $this->description = $this->l("Este modulo permite integrar Prestashop con OBUMA");
        $this->confirmUninstall = $this->l('Deseas Desinstalar el Modulo Obuma Sync?');
        
    }
    
    public function install(){

        include_once(dirname(__FILE__)."/sql/install.php");

        if(!parent::install() ||
            !Configuration::updateValue("rut_empresa","") || 
            !Configuration::updateValue("bodega","") || 
            !Configuration::updateValue("id_bodega","") || 
            !Configuration::updateValue("api_key","") || 
            !Configuration::updateValue("api_url","") || 
            !Configuration::updateValue("sucursal","") || 
            !Configuration::updateValue("vendedor","") || 
            !Configuration::updateValue("usuario","") || 
            !Configuration::updateValue("canal_venta","") || 
            !Configuration::updateValue("lista_precio","") || 
            !Configuration::updateValue("codigo_forma_pago","") || 
            !Configuration::updateValue("rebajar_stock",0) || 
            !Configuration::updateValue("cliente_actualizar_datos",0) || 
            !Configuration::updateValue("registrar_contabilidad",0) || 
            !Configuration::updateValue("enviar_email_cliente",0) || 
            !Configuration::updateValue("registrar_cobro",0) || 
            !Configuration::updateValue("registrar_producto",0) ||
            !Configuration::updateValue("tipo_documento","[]") ||
            !Configuration::updateValue("nota_venta_segundo_plano",0) || 
            !Configuration::updateValue("enviar_ventas_obuma",0) || 
            !Configuration::updateValue("cambiar_a_completado",0) || 
            !Configuration::updateValue("sincronizar_precio",0) || 
            !Configuration::updateValue("sincronizar_cliente_por",0) || 
            !Configuration::updateValue("update_limpiar_registros_date","") || 
            !Configuration::updateValue("estado_enviar_obuma",0) || 
            !Configuration::updateValue("proveedores_actualizar_stock","") || 
            !Configuration::updateValue("obuma_module_version","1.0.1") || 
            !$this->registerHook("DisplayBackOfficeHeader") || 
            !$this->registerHook("Header") ||
            !$this->registerHook("actionCartSave") ||
            !$this->registerHook("actionValidateOrder") ||
            !$this->registerHook("actionOrderStatusPostUpdate") || 
            !$this->registerHook("actionValidateCustomerAddressForm") || 
            !$this->registerHook("AdditionalCustomerAddressFields") || 
            !$this->registerHook('displayCustomerAccountForm') ||
            !$this->registerHook('actionCustomerAccountAdd') || 
            !$this->registerHook('actionCustomerAccountUpdate') || 
            !$this->registerHook('displayCheckoutSummaryTop') ||
            !$this->registerHook('displayCheckoutBeforeConfirmation') ||
            
            !$this->createTabLink()){

            return false;

        }else{
            
            return true;

        }

    }
    
    public function uninstall(){

        include_once(dirname(__FILE__)."/sql/uninstall.php");

        if(!parent::uninstall() ||
           !Configuration::deleteByName("rut_empresa") || 
           !Configuration::deleteByName("bodega") ||
           !Configuration::deleteByName("id_bodega") ||
           !Configuration::deleteByName("api_key") ||
           !Configuration::deleteByName("api_url") ||
           !Configuration::deleteByName("sucursal") || 
           !Configuration::deleteByName("vendedor") ||
           !Configuration::deleteByName("usuario") || 
           !Configuration::deleteByName("canal_venta") || 
           !Configuration::deleteByName("lista_precio") || 
           !Configuration::deleteByName("codigo_forma_pago") || 
           !Configuration::deleteByName("rebajar_stock") || 
           !Configuration::deleteByName("cliente_actualizar_datos") || 
           !Configuration::deleteByName("registrar_contabilidad") || 
           !Configuration::deleteByName("enviar_email_cliente") || 
           !Configuration::deleteByName("registrar_cobro") ||
           !Configuration::deleteByName("registrar_producto") ||
           !Configuration::deleteByName("tipo_documento") ||  
           !Configuration::deleteByName("nota_venta_segundo_plano") || 
           !Configuration::deleteByName("enviar_ventas_obuma") || 
           !Configuration::deleteByName("cambiar_a_completado") || 
           !Configuration::deleteByName("sincronizar_precio") || 
           !Configuration::deleteByName("sincronizar_cliente_por") || 
           !Configuration::deleteByName("update_limpiar_registros_date") || 
           !Configuration::deleteByName("estado_enviar_obuma") || 
           !Configuration::deleteByName("proveedores_actualizar_stock") || 
           !Configuration::deleteByName("obuma_module_version") || 
           !$this->deleteTabLink()){
            return false;
        }else{
            return true;
        }

    }


    public function hookDisplayCheckoutBeforeConfirmation($params)
    {
        $selected_option = Tools::getValue('invoice_type', 'boleta'); // Por defecto 'boleta'
        
        $this->context->smarty->assign(array(
            'selected_option' => $selected_option,
            'invoice_options' => array(
                'boleta' => 'Boleta',
                'factura' => 'Factura'
            ),
        ));
        
        return $this->display(__FILE__, 'views/templates/hook/invoice_selector.tpl');
    }


    public function hookDisplayCheckoutSummaryTop($params)
{


    /*
    $selected_option = Tools::getValue('invoice_type', 'boleta'); // Por defecto 'boleta'
    
    $this->context->smarty->assign(array(
        'selected_option' => $selected_option,
        'invoice_options' => array(
            'boleta' => 'Boleta',
            'factura' => 'Factura'
        ),
    ));
    
    return $this->display(__FILE__, 'views/templates/hook/invoice_selector.tpl');

    */
}




    public function hookActionCustomerAccountUpdate($params){

        // Obtiene el ID del cliente desde el objeto customer
        $id_customer = (int) $params['customer']->id;

        // Obtiene el valor de obuma_rut desde el formulario
        $rut = Tools::getValue('obuma_rut');

        // Si el campo obuma_rut tiene un valor, realiza la actualización
        if ($id_customer && $rut) {
            Db::getInstance()->update(
                'customer', // Tabla sin prefijo
                array('obuma_rut' => pSQL($rut)), // Datos a actualizar
                'id_customer = ' . $id_customer // Condición
            );


        }

    }


    public function hookDisplayCustomerAccountForm($params){      

        $id_customer = (int) $this->context->customer->id;

        // Si no se encuentra, intenta obtenerlo de la solicitud
        if (!$id_customer) {
            $id_customer = (int) Tools::getValue('id_customer');
        }

        $rut = Tools::getValue('obuma_rut', ''); // Prioriza el valor enviado por POST
        
        
        // Recupera de la base de datos solo si no hay un valor en POST
        if (!$rut && $id_customer) {
            $rut = Db::getInstance()->getValue('
                SELECT obuma_rut 
                FROM '._DB_PREFIX_.'customer 
                WHERE id_customer = '.(int)$id_customer
            );
        }
    
        // Asigna valores a Smarty para usar en la plantilla
        $this->context->smarty->assign('obuma_rut_label', 'R.U.T');
        $this->context->smarty->assign('obuma_rut', $rut);
    
        // Devuelve el contenido del formulario
        return $this->display(__FILE__, 'views/templates/hook/customer_form.tpl');

    }

    public function hookActionCustomerAccountAdd($params)
    {
        
        $rut = Tools::getValue('obuma_rut');

        if ($rut) {
            
            $id_customer = (int) $params['newCustomer']->id;

            
            Db::getInstance()->update(
                'customer', 
                array('obuma_rut' => pSQL($rut)),
                'id_customer = ' . $id_customer
            );

        }

    }





     public function hookDisplayBackOfficeHeader($params) {

        if (Tools::getValue("controller") === "AdminSincronizacion" || Tools::getValue("controller") === "AdminConfiguracion" || Tools::getValue("controller") === "AdminLogOrdenes" || Tools::getValue("controller") === "AdminVincularCategorias"  || Tools::getValue("controller") === "AdminOtros") {
            
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


    public function hookActionCartSave($params){

        $invoiceType = Tools::getValue('invoice_type_value');



        if ($invoiceType) {
            $this->context->cart->invoice_type = $invoiceType;
            $this->context->cart->update(); // Guarda los cambios
        }
}


    public function hookActionValidateOrder($params){

        var_dump($_GET);exit();
        /*
        $order = $params['order'];

        //$invoiceType = Tools::getValue('invoice_type_value');

        
        $invoiceType = $params['cart']->invoice_type;


        if ($invoiceType) {
            Db::getInstance()->insert('obuma_order', [
                'order_id' => (int) $order->id,
                'invoice_type' => pSQL($invoiceType),
                'fecha_registro' => date("Y-m-d H:i:s")
            ]);
        }

        */

    }

    public function hookActionOrderStatusPostUpdate($params){

         $id = $params["id_order"];

         $invoiceType = Tools::getValue('invoice_type_value');

         

         $enviar_ventas_obuma = trim(Configuration::get("enviar_ventas_obuma"));
         $estado_enviar_obuma = trim(Configuration::get("estado_enviar_obuma"));

         if($enviar_ventas_obuma == 1 && !empty($estado_enviar_obuma) && $estado_enviar_obuma > 0){

            if ($params["newOrderStatus"]->id == $estado_enviar_obuma) {

            $data = array();
       
            $order_obuma_existe = Db::getInstance()->executeS("SELECT * FROM ". _DB_PREFIX_."obuma_order WHERE order_id='".$id."'");

            if (count($order_obuma_existe) == 0) {

                $order = new Order($id);

                $productos_orden = $order->getProductsDetail();

                $data["orden_id"] = $id;

                $customer = new Customer($order->id_customer);

                $id_address_invoice = $order->id_address_invoice;

                $address = new Address($id_address_invoice);

                $nota_venta_segundo_plano = Configuration::get("nota_venta_segundo_plano");

                if ($nota_venta_segundo_plano == 0) {

                    $data["tipo_documento"] = ($address->other != 33 && $address->other != 39) ? 39 : $address->other;

                }elseif($nota_venta_segundo_plano == 1){

                    $data["tipo_documento"] = 4;

                }else{

                    if($address->other == 33){

                          $data["tipo_documento"] = 4;

                    }else{

                         $data["tipo_documento"] = ($address->other != 33 && $address->other != 39) ? 39 : $address->other;
                         
                    }
                }
                

                if($data["tipo_documento"] == 33){

                    $data["razon_social"] = $address->firstname;
                    $data["giro_comercial"] = $address->lastname;

                }else{

                    $data["razon_social"] = $address->firstname . " " . $address->lastname;
                    $data["giro_comercial"] = "";

                }

                $data["rut"] = $address->dni;
                
                $data["email"] = $customer->email;

                $data["telefono"] = $address->phone;

                $data["direccion"] = $address->address1;

                $data["sucursal"] = Configuration::get("sucursal");
                $data["bodega"] = Configuration::get("bodega");
                $data["usuario"] = Configuration::get("usuario");
                $data["vendedor"] = Configuration::get("vendedor");
                $data["lista_precio"] = Configuration::get("lista_precio");
                $data["rebajar_stock"] = Configuration::get("rebajar_stock");
                $data["registrar_contabilidad"] = Configuration::get("registrar_contabilidad");
                $data["enviar_email_cliente"] = Configuration::get("enviar_email_cliente");
                $data["registrar_cobro"] = Configuration::get("registrar_cobro");
                $data["registrar_producto"] = Configuration::get("registrar_producto");
                    
                if(Configuration::get("registrar_cobro") == 1){

                    $data["total_pagado"] = $order->total_paid_tax_incl;
                    $data["total_por_pagar"] = 0;

                }else{

                    $data["total_pagado"] = 0;
                    $data["total_por_pagar"] = $order->total_paid_tax_incl;

                }

                if($data["tipo_documento"] != 39){

                    $data["subtotal"] = ($order->total_paid_tax_incl / 1.19);

                }else{
                    $data["subtotal"] = $order->total_paid_tax_incl;

                }

                $data["cliente_actualizar_datos"] = Configuration::get("cliente_actualizar_datos");
                $payment_method = $order->payment;
                $forma_pago_ = explode('#', $payment_method);

                if (isset($forma_pago_[1])){
                    $venta_forma_pago = $forma_pago_[1];
                }else{
                     $venta_forma_pago = Configuration::get("codigo_forma_pago");
                }
                
                $data["forma_pago"] = $venta_forma_pago;
                $data["total_neto"] = ($order->total_paid_tax_incl / 1.19);
                
                $data["total_envio"] = $order->total_shipping;
                $data["total"] = $order->total_paid_tax_incl;


                $indice = 0;
                
                foreach ($productos_orden as $po) {

                    if ($data["tipo_documento"] != "39") {

                        $data["orden_detalle"][$indice]["precio"] = ($po["product_price"] / 1.19);
                        $data["orden_detalle"][$indice]["subtotal"] = ($po["total_price_tax_excl"] / 1.19);

                    }else{
                        $data["orden_detalle"][$indice]["precio"] = $po["product_price"];
                        $data["orden_detalle"][$indice]["subtotal"] = $po["total_price_tax_excl"];

                    }

                    if(!validar_proveedor($po["reference"])){
                        $data["orden_detalle"][$indice]["inventariable"] = 0;
                    }

                    $data["orden_detalle"][$indice]["codigo_comercial"] = $po["reference"];
                    $data["orden_detalle"][$indice]["nombre"] = $po["product_name"];
                    $data["orden_detalle"][$indice]["cantidad"] = $po["product_quantity"];
                    
                    $indice++;
                }
                
                
                $carrier = new Carrier($order->id_carrier);

                $data["orden_detalle"][$indice]["codigo_comercial"] = 'envio';
                $data["orden_detalle"][$indice]["nombre"] = $carrier->name;
                $data["orden_detalle"][$indice]["cantidad"] = 1;


                if ($data["tipo_documento"] != "39") {
                    $data["orden_detalle"][$indice]["precio"] = ($order->total_shipping  / 1.19);
                    $data["orden_detalle"][$indice]["subtotal"] = ($order->total_shipping / 1.19);
                }else{
                    $data["orden_detalle"][$indice]["precio"] = $order->total_shipping;
                    $data["orden_detalle"][$indice]["subtotal"] = $order->total_shipping;
                }

                $response = $this->enviar_orden_venta($data);

                $data_log  = array('order_id' => $id,'peticion' => $response["peticion"], "respuesta" => $response["respuesta"],"estado" => strtolower($params["newOrderStatus"]->name));

                create_log_obuma($data_log,"order");
       
                if (isset($response["respuesta"]["result"]["result_dte"][0]["dte_id"])) {

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
                }else{

                    if(isset($response["respuesta"]["errors"])){

                        $objOrder = new Order($id);
                        $history = new OrderHistory();
                        $history->id_order = (int)$objOrder->id;

                        $ctx = Context::getContext();
                        $emp_id = (int)$ctx->employee->id;

                        if(getIdOrderStatus() != false){
                            $objOrder->setCurrentState(getIdOrderStatus(),$emp_id);  
                            //$history->changeIdOrderState(getIdOrderStatus(), (int)($objOrder->id));

                        }
                        

                    }
                }


            }else{
                $data_log  = array('order_id' => '' , 'peticion' => [], "respuesta" => [],"estado" => strtolower($params["newOrderStatus"]->name));

                create_log_obuma($data_log,"order");
                
            }


        }else{

            $data_log  = array('order_id' => '' , 'peticion' => [], "respuesta" => [],"estado" => strtolower($params["newOrderStatus"]->name));
            create_log_obuma($data_log,"order");
        
        }


         }


    }


    public function hookActionValidateCustomerAddressForm($form){

       $context = Context::getContext();
        
       $id_customer = $context->customer->id;

       $id_address = Tools::getValue("id_address");


            if($id_address == 0){

                $lastname = Tools::getValue("lastname");
                $company = (Tools::getValue("company") == null || empty(Tools::getValue("company"))) ? "" : Tools::getValue("company");
                $dni = Tools::getValue("dni");
                $address1 = Tools::getValue("address1");
                $address2 = Tools::getValue("address2") == null ? "" : Tools::getValue("address2");
                $city = Tools::getValue("city");
                $id_country = Tools::getValue("id_country");
                $id_state = Tools::getValue("id_state") == null ? 0 : Tools::getValue("id_state");
                $postcode = Tools::getValue("postcode") == null ? "" : Tools::getValue("postcode");
                $phone = Tools::getValue("phone");
                $saveAddress = Tools::getValue("saveAddress");

                $tipo_documento_registrar = ($saveAddress == "delivery") ? 39 : 33;

                $tipo_documento_seleccionado = !isset($_POST["tipo_documento"]) ? $tipo_documento_registrar : $_POST["tipo_documento"];
                
                 //Db::getInstance()->execute("INSERT INTO ". _DB_PREFIX_."address(id_country,id_customer,alias,company,lastname,firstname,address1,address2,postcode,city,other,phone,dni,date_add,date_upd) VALUES " . "('".$id_country."','".$id_customer."','".$alias."','".$company."','".$lastname."','".$firstname."','".$address1."','".$address2."','".$postcode."','".$city."','".$tipo_documento."','".$phone."','".$dni."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");


                //$id =Db::getInstance()->Insert_ID();
                


                if(!soloLetras($firstname)){
                    if($tipo_documento_registrar == 33){
                        $this->context->controller->errors[] = $this->l('La razon social solo permite letras y espacios en blanco');
                    }else{
                         $this->context->controller->errors[] = $this->l('El nombre solo permite letras y espacios en blanco');
                    }
                    
                    return false;
                }
                
                if(!soloLetras($lastname)){
                    if($tipo_documento_registrar == 33){
                        $this->context->controller->errors[] = $this->l('El giro comercial solo permite letras y espacios en blanco');
                    }else{
                         $this->context->controller->errors[] = $this->l('Los apellidos solo permite letras y espacios en blanco');
                    }
                    return false;
                }



                $address = new Address();
                $address->id_customer = $id_customer; 
                $address->firstname = pSQL($firstname);
                $address->lastname = pSQL($lastname);
                $address->address1 = pSQL($address1);
                $address->address2 = pSQL($address2);
                $address->company = pSQL($company);
                $address->postcode = pSQL($postcode);
                $address->city = pSQL($city);
                $address->id_country = (int)$id_country; 
                $address->alias = pSQL($alias);
                $address->phone = pSQL($phone);
                $address->dni = pSQL($dni);
                $address->other = pSQL($tipo_documento_registrar);
                $address->add();
                    
                //$this->context->cookie->__set('id_address_delivery', $address->id);
                
                
                if($tipo_documento_registrar == 39){
                    
                    $this->context->cart->updateAddressId(
                    $this->context->cart->id_address_delivery,
                    $address->id
                    );

                    $this->context->cart->id_address_delivery =  $address->id;
                   

                }else{
                    
                    $this->context->cart->updateAddressId(
                    $this->context->cart->id_address_invoice,
                    $address->id
                    ); 
                    
                    $this->context->cart->id_address_invoice =  $address->id;

                }
                



                if($tipo_documento_seleccionado == 33){
                    echo "<script>window.location.href='pedido?newAddress=invoice';</script>";
                    exit();
                }else{
                    if($saveAddress == "invoice"){
                         echo "<script>window.location.href='pedido?use_same_address=0';</script>";
                       
                    }else{
                         echo "<script>window.location.href='pedido';</script>";
                    }
                    exit();
                }




            }   
    
        
    }


    public function hookAdditionalCustomerAddressFields($params){


        $enviar_ventas_obuma = Configuration::get("enviar_ventas_obuma");

        if($enviar_ventas_obuma == 1){

            $extra_fields = array();

            $data_add = [];

            $tipo_documento = json_decode(Configuration::get("tipo_documento"),true);

            if(in_array(39, $tipo_documento)){
                $data_add["39"] = "Boleta"; 
            }

            if(in_array(33, $tipo_documento)){
                $data_add["33"] = "Factura";
            }

            $extra_fields['other'] = (new FormField)->setName('other')->setLabel('Tipo de documento')->setType("select")->setAvailableValues($data_add)->setRequired(true);

            //return $extra_fields;

        }



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
      $tab->class_name = "AdminVincularCategorias";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Vincular categorias');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();


      //Sub menu code

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminLogSincronizacion";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Log de sincronizacion');
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
      $tab->class_name = "AdminLogWebhook";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Log de webhook');
      }
      $tab->id_parent = $parentTab->id;
      $tab->module = $this->name;
      $tab->add();

      $parentTabID = Tab::getIdFromClassName('AdminObuma');
      $parentTab = new Tab($parentTabID);

      $tab = new Tab();
      $tab->active = 1;
      $tab->class_name = "AdminOtros";
      $tab->name = array();
      foreach (Language::getLanguages() as $language) {
          $tab->name[$language['id_lang']] = $this->l('Otros');
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
        'venta_registrar_producto'   => $data["registrar_producto"],
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

    $json = ObumaConector::post(set_url()."ventasIntegracionExternas.create.json",$data_enviar,Configuration::get("api_key"));
                    
    $json = json_encode($json, true);
    $json = json_decode($json, true);

        
    $return = array('peticion' => $data_enviar , "respuesta" => $json);

    return $return;

}



private function insert_order_obuma($data){

    $result = Db::getInstance()->execute("
    UPDATE " . _DB_PREFIX_ . "obuma_order
    SET 
        dte_id = '" . pSQL($data['dte_id']) . "',
        dte_tipo = '" . pSQL($data['dte_tipo']) . "',
        dte_folio = '" . pSQL($data['dte_folio']) . "',
        dte_result = '" . pSQL($data['dte_result']) . "',
        dte_xml = '" . pSQL($data['dte_xml']) . "',
        dte_pdf = '" . pSQL($data['dte_pdf']) . "',
        fecha = '" . pSQL(date('Y-m-d')) . "',
        hora = '" . pSQL(date('H:i:s')) . "'
    WHERE order_id = '" . (int)$data['order_id'] . "'
    ");


}

}
