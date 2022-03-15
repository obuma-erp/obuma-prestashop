<style type="text/css">
	form table tr th{
		
		padding:10px;
	}
</style>



<div class="panel panel-info">
<div class="panel-heading bg-white">
	{l s='VARIABLES DE CONFIGURACION DE OBUMA' mod='obuma'}
</div>
<div class='panel-body'>
	<p>Establece tus variables de Configuracion,para conectar con OBUMA</p>


{if $save}
    <div class="bootstrap">
        <div class="module_confirmation conf confirm alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            Se ha guardado correctamente
        </div>
    </div>



{/if}

<form action="" method="post">
 
 <table class="tabla">
     <tr>
         <th><label for="exampleUrl">RUT EMPRESA</label></th>
         <th><input type="text" name="rut_empresa" id="rut_empresa" value="{$rut_empresa}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce RUT EMPRESA" required>
            <small id="urlHelp" class="form-text text-muted"></small>
         </th>
     </tr>


     <tr>
         <th><label for="exampleUrl">API KEY</label></th>
         <th><input type="password" name="api_key" id="api_key" value="{$api_key}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce API KEY" required>
         <small id="urlHelp" class="form-text text-muted"></small>
     </th>
     </tr>

    <tr>
         <th><label for="exampleUrl">API URL</label></th>
         <th><input type="text" name="api_url" id="api_url" value="{$api_url}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce API KEY" required>
         <small id="urlHelp" class="form-text text-muted">  URL para conectarse a la API de Obuma - ej. https://api.obuma.cl/v1.0 </small>
     </th>
     </tr>

     <tr>
          <th><label for="exampleUrl">SUCURSAL</label></th>
         <th> <input type="text" name="sucursal" id="sucursal" value="{$sucursal}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce sucursal a usar">
         <small id="urlHelp" class="form-text text-muted">Código sucursal desea vincular a las ventas</small>
     </th>
     </tr>

     <tr>
          <th><label for="exampleUrl">BODEGA</label></th>
         <th><input type="text" name="bodega" id="bodega" value="{$bodega}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce Bodega a usar">
            <small id="urlHelp" class="form-text text-muted">Código bodega desea vincular a las ventas</small>
         </th>
         <th><input type="text" name="id_bodega" id="id_bodega" value="{$id_bodega}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce Id de la bodega">
             <small id="urlHelp" class="form-text text-muted">ID de la bodega</small>
         </th>
     </tr>

     <tr>
          <th><label for="exampleUrl">VENDEDOR</label></th>
         <th> <input type="text" name="vendedor" id="vendedor" value="{$vendedor}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce vendedor">
            <small id="urlHelp" class="form-text text-muted">Código del vendedor desea vincular a las ventas</small>
         </th>
     </tr>

     <tr>
          <th><label for="exampleUrl">USUARIO</label></th>
         <th><input type="text" name="usuario" id="usuario" value="{$usuario}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce usuario">
        <small id="urlHelp" class="form-text text-muted">Usuario desea vincular a las ventas</small>
         </th>
     </tr>


      <tr>
         <th><label for="exampleUrl">CANAL VENTA</label></th>
         <th> <input type="text" name="canal_venta" id="canal_venta" value="{$canal_venta}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce el canal de venta">
            <small id="urlHelp" class="form-text text-muted">Código del canal de venta que desea vincular a las ventas</small>
         </th>
     </tr>


     <tr>
         <th><label for="exampleUrl">LISTA PRECIO</label></th>
         <th> <input type="text" name="lista_precio" id="lista_precio" value="{$lista_precio}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce lista precio">
            <small id="urlHelp" class="form-text text-muted">Código lista de precio desea vincular a las ventas</small>
         </th>
     </tr>

      <tr>
         <th><label for="exampleUrl">CÓDIGO FORMA DE PAGO</label></th>
         <th><input type="text" name="codigo_forma_pago" id="codigo_forma_pago" value="{$codigo_forma_pago}" class="form-control"  aria-describedby="urlHelp" placeholder="Introduce código forma pago">
            <small id="urlHelp" class="form-text text-muted">Código forma de pago desea vincular a las ventas</small>
         </th>
     </tr>

      <tr>
         <th><label>REBAJAR STOCK</label></th>
         <th><input type="radio" {if $rebajar_stock == 0} checked {/if} value="0" name="rebajar_stock"  id="rebajar_stock"> No 
                <input type="radio" {if $rebajar_stock == 1} checked {/if} name="rebajar_stock"  id="rebajar_stock" value="1"> Si 
                 <br>
                <small id="urlHelp" class="form-text text-muted">&nbsp;</small>
            </th>
     </tr>

      <tr>
         <th><label>ACTUALIZAR DATOS DEL CLIENTE</label></th>
         <th><input type="radio" value="0" {if $cliente_actualizar_datos == 0} checked {/if} name="cliente_actualizar_datos"  id="cliente_actualizar_datos"> No 
                <input type="radio" {if $cliente_actualizar_datos == 1} checked {/if}  name="cliente_actualizar_datos"  id="cliente_actualizar_datos" value="1"> Si 
                <br>
                <small id="urlHelp" class="form-text text-muted">&nbsp;</small>
            </th>
     </tr>

      <tr>
         <th><label>REGISTRAR CONTABILIDAD</label></th>
         <th><input type="radio" value="0" {if $registrar_contabilidad == 0} checked {/if}  name="registrar_contabilidad"  id="registrar_contabilidad"> No 
                <input type="radio" {if $registrar_contabilidad == 1} checked {/if}  name="registrar_contabilidad"  id="registrar_contabilidad" value="1"> Si 
                 <br>
                <small id="urlHelp" class="form-text text-muted">&nbsp;</small>
            </th>
     </tr>

      <tr>
         <th><label>ENVIAR EMAIL AL CLIENTE</label></th>
         <th><input type="radio" value="0"  {if $enviar_email_cliente == 0} checked {/if}  name="enviar_email_cliente"  id="enviar_email_cliente"> No 
                <input type="radio"  {if $enviar_email_cliente == 1} checked {/if}  name="enviar_email_cliente"  id="enviar_email_cliente" value="1"> Si 
                <br>
                <small id="urlHelp" class="form-text text-muted">Permite enviar email con boleta/factura al cliente</small>
            </th>
     </tr>

     <tr>
         <th><label>REGISTRAR COBRO</label></th>
         <th><input type="radio" value="0" name="registrar_cobro"  {if $registrar_cobro == 0} checked {/if}   id="registrar_cobro"> No 
                <input type="radio"  {if $registrar_cobro == 1} checked {/if}  name="registrar_cobro"  id="registrar_cobro" value="1"> Si <br>
                <small id="urlHelp" class="form-text text-muted">Registra en obuma el cobro</small>
            </th>
     </tr>

          <tr>
         <th><label>TIPO DE DOCUMENTO</label></th>
         <th><input type="checkbox" {if in_array(39,json_decode($tipo_documento))} checked {/if} multiple value="39" name="tipo_documento[]"  id="tipo_documento" required> Boleta 
                <input type="checkbox" {if in_array(33,json_decode($tipo_documento))} checked {/if} multiple name="tipo_documento[]"  id="tipo_documento" value="33" required> Factura 
                <br>
                <small id="urlHelp" class="form-text text-muted">&nbsp;</small>
            </th>
     </tr>


      <tr>
         <th><label>EMISION DE NOTA DE VENTA EN SEGUNDO PLANO</label></th>
         <th>
         <input type="radio" required value="0" name="nota_venta_segundo_plano"  {if $nota_venta_segundo_plano == 0}  checked {/if}> No
                <input type="radio" required  name="nota_venta_segundo_plano"   value="1" {if $nota_venta_segundo_plano == 1}  checked {/if}> Si

                <input type="radio" required  name="nota_venta_segundo_plano"  {if $nota_venta_segundo_plano == 2}  checked {/if}> Solo si es Factura
                <br>
            </th>
     </tr>

      <tr>
         <th><label>ENVIAR VENTAS A OBUMA</label></th>
         <th>
            <input type="radio" value="0" name="enviar_ventas_obuma"  id="enviar_ventas_obuma" {if $enviar_ventas_obuma == 0}  checked {/if}> No 
                <input type="radio" name="enviar_ventas_obuma" id="enviar_ventas_obuma" value="1" {if $enviar_ventas_obuma == 1}  checked {/if}> Si 
                <br>
                <small id="urlHelp" class="form-text text-muted">Permite enviar a OBUMA las ordenes que fueron completadas</small>
        </th>
     </tr>

     <tr>
         <th><label>ENVIAR A OBUMA AUTOMÁTICAMENTE</label></th>
         <th>
            <input type="radio" value="0" name="cambiar_a_completado" id="cambiar_a_completado" {if $cambiar_a_completado == 0}  checked {/if}> No 
                <input type="radio" name="cambiar_a_completado" id="cambiar_a_completado" value="1" {if $cambiar_a_completado == 1}  checked {/if}> Si 
                <br>
                <small id="urlHelp" class="form-text text-muted">Permite cambiar el estado del pedido a "COMPLETADO" despu&eacute;s de  realizar un pago, para ser enviado automaticamente a OBUMA</small>
        </th>
     </tr>

     <tr>
         <th><label>PRECIO A COPIAR DESDE OBUMA</label></th>
         <th>
            <input type="radio" value="0" name="sincronizar_precio" id="sincronizar_precio" {if $sincronizar_precio == 0}  checked {/if}> No 
                <input type="radio" name="sincronizar_precio" id="sincronizar_precio" value="1" {if $sincronizar_precio == 1}  checked {/if}> Si 
                <br>
                <small id="urlHelp" class="form-text text-muted">Permite seleccionar si se trae el precio bruto o el precio neto de los productos de OBUMA</small>
        </th>
     </tr>


     <tr>
         <th><label>LIMPIAR REGISTROS ANTIGUOS</label></th>
         <th>
                <button type="button" id="limpiar_registros" class="btn btn-info">Iniciar limpieza</button>
                <span id="update_limpiar_registros_message">
                    {if $update_limpiar_registros_date != ""}
                     Ultima Limpieza
                  
                    {else}

                    {/if}
                    
                </span> 


                
                   <strong  id="update_limpiar_registros"> {$update_limpiar_registros_date}</strong>

                   <br>
                <small id="urlHelp" class="form-text text-muted">Permite limpiar los registros antiguos generados por el plugin Obuma Sync</small>
                
        </th>
     </tr>
 </table>
        
              
     <button type="submit" name="enviar_datos" id="enviar_datos" class="btn btn-primary">Guardar </button>
</form>
</div>

</div>

    <script>

  var requiredCheckboxes = jQuery(':checkbox[required]');
  if(requiredCheckboxes.is(':checked')) {
    requiredCheckboxes.removeAttr('required');
  }else{
     requiredCheckboxes.attr('required','required');
  }
    requiredCheckboxes.change(function(){

        if(requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        }

        else {
            requiredCheckboxes.attr('required','required');
        }
    });
    </script>