<table id="log_webhook" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>FECHA</th>
			<th>HORA</th>
			<th>TIPO</th>
			<th>PETICION</th>
			<th>RESULTADO</th>
			
		</tr>
	</thead>

	<tbody>

		{foreach from=$log_webhook item=log}
			<tr>
				<td>{$log.id}</td>
				<td>{$log.fecha}</td>
				<td>{$log.hora}</td>
				<td>{$log.tipo}</td>
				<td>{$log.peticion}</td>
				<td>{$log.resultado}</td>
			</tr>
		{/foreach}

	</tbody>

</table>


<script type="text/javascript">
	$(document).ready( function () {
    $('#log_webhook').DataTable();
} );
</script>