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