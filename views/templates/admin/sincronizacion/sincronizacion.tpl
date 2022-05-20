{if $check_version}
	<div class='alert alert-warning' >
	Hay una nueva versi&oacute;n disponible del modulo Obuma Sync !  <a target='__blank'  style='background-color:#bb4827;padding:5px;color:white;text-decoration:none;'href='https://github.com/obuma-erp/obuma-prestashop'>Obtener la nueva versi&oacute;n</a>
	</div>
{/if}


<div class="panel panel-info">
<div class="panel-heading bg-white">
	{l s='Panel de Sinronización con la API OBUMA' mod='obuma'}
</div>
<div class='panel-body'>


{if isset($response_connect_success->data[0]->empresa_id) && $response_connect_success->data[0]->empresa_id > 0 }
	<div class='alert alert-success'>
	<strong>Se conect&oacute; correctamente  con la API de Obuma</strong><br>
	Id de la empresa : {$response_connect_success->data[0]->empresa_id}<br>
	Rut de la empresa : {$response_connect_success->data[0]->empresa_rut}<br>
	Razón social : {$response_connect_success->data[0]->empresa_razon_social}<br>
	Nombre de fantasia : {$response_connect_success->data[0]->empresa_nombre_fantasia}<br>
	</div>
	{else}
	<div class='alert alert-danger'>
	<strong>Hubo un error  al conectar con la API de Obuma,verifique el API KEY registrado en la configuraci&oacute;n del plugin . <a class='btn btn-primary' href='?controller=AdminConfiguracion&token={$token_configuracion}'>Ir a la configuraci&oacute;n</a></strong><br>

	</div>
{/if}


	<p>{l s='Pulse en una de las opciones para sincronizar con la API de OBUMA' mod='obuma'}</p>
<div class="row">

<div class="col-lg-3">
	<!-- List group -->

<div class="list-group" id="myList" role="tablist">
  <a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_clientes.php" data-pagina="clientes"  role="tab"><i class="material-icons">
accessibility
</i> Clientes</a>
  <a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_productos.php" data-pagina="productos" role="tab"><i class="material-icons">
redeem
</i> Productos</a>
<a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_categorias_productos.php"  data-pagina="categorias_productos"  role="tab"><i class="material-icons">
redeem
</i>Categorias de  Productos</a>
  <a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_precios.php" data-pagina="precios" role="tab"><i class="material-icons">
attach_money
</i> Precios</a>
  <a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_stock.php" data-pagina="stock" role="tab"><i class="material-icons">
exposure
</i> Stock</a>

  <a class="list-group-item list-group-item-action" data-toggle="list" href="obuma_productos_imagenes.php" data-pagina="productos_imagenes" role="tab"><i class="material-icons">
burst_mode
</i> Imagenes de Productos</a>
</div>

</div>
<div class="col-lg-9 centrar" style='background-color:white;'>
<div  id="cargar_vistas">

</div>

<div id="completado"  style="overflow-y: scroll;height: 350px;">

</div>
</div>
</div>

</div>

</div>