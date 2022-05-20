{if $check_version}
	<div class='alert alert-warning' >
	Hay una nueva versi&oacute;n disponible del modulo Obuma Sync !  <a target='__blank'  style='background-color:#bb4827;padding:5px;color:white;text-decoration:none;'href='https://github.com/obuma-erp/obuma-prestashop'>Obtener la nueva versi&oacute;n</a>
	</div>
{/if}



<form method="post">
	
	<p style="text-align:right;">

	<input type="text" name="search" id="search" placeholder="Buscar.." style="width:200px;display:inline-block;">
	<button type="submit" name="btn_search" class="btn btn-primary">Buscar</button>

	</p>
	
</form>

<table id="log_sincronizacion" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>FECHA</th>
			<th>HORA</th>
			<th>TIPO</th>
			<th>OPCION</th>
			<th>RESULTADO</th>
			
		</tr>
	</thead>

	<tbody>

		{foreach from=$log_sincronizacion item=log}
			<tr>
				<td>{$log.id}</td>
				<td>{$log.fecha}</td>
				<td>{$log.hora}</td>
				<td>{$log.tipo}</td>
				<td>{$log.opcion}</td>
				<td>{$log.resultado}</td>
			</tr>
		{/foreach}

	</tbody>

</table>


<script type="text/javascript">
	$(document).ready( function () {
    $('#log_sincronizacion').DataTable();
} );
</script>