<table id="log_ordenes" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID ORDEN</th>
			<th>FECHA</th>
			<th>HORA</th>
			<th>PETICION</th>
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
				<td>{$log.peticion}</td>
				<td>{$log.respuesta}</td>
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