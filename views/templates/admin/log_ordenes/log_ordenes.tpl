{if $check_version}
	<div class='alert alert-warning' >
	Hay una nueva versi&oacute;n disponible del modulo Obuma Sync !  <a target='__blank'  style='background-color:#bb4827;padding:5px;color:white;text-decoration:none;'href='https://github.com/obuma-erp/obuma-prestashop'>Obtener la nueva versi&oacute;n</a>
	</div>
{/if}


<div>

<form method="post">
	
	<p style="text-align:right;">

	<input type="text" name="search" id="search" placeholder="Buscar.." style="width:200px;display:inline-block;">
	<button type="submit" name="btn_search" class="btn btn-primary">Buscar</button>

	</p>
	
</form>

</div>

<table id="log_ordenes" class="table table-bordered table-striped" style="width:100%;">
	<thead>
		<tr>
			<th>ID ORDEN</th>
			<th>FECHA</th>
			<th>HORA</th>
			<th with="30%">PETICION</th>
			<th>RESPUESTA</th>
			<th>ESTADO</th>
			
		</tr>
	</thead>

	<tbody>

		{foreach from=$log_ordenes item=log}
			<tr>
				<td>{$log.order_id}</td>
				<td>{$log.fecha}</td>
				<td>{$log.hora}</td>
				<td><pre>{json_encode(json_decode($log.peticion,true),JSON_PRETTY_PRINT)}</pre></td>
				<td><pre>{json_encode(json_decode($log.respuesta,true),JSON_PRETTY_PRINT)}</pre></td>
				<td>{$log.estado}</td>
			</tr>
		{/foreach}

	</tbody>

</table>


<script type="text/javascript">
	$(document).ready( function () {
    $('#log_ordenes').DataTable();
} );
</script>