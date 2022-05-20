{if $check_version}
	<div class='alert alert-warning' >
	Hay una nueva versi&oacute;n disponible del modulo Obuma Sync !  <a target='__blank'  style='background-color:#bb4827;padding:5px;color:white;text-decoration:none;'href='https://github.com/obuma-erp/obuma-prestashop'>Obtener la nueva versi&oacute;n</a>
	</div>
{/if}


{if isset($save)}
{if $save}
    <div class="bootstrap">
        <div class="module_confirmation conf confirm alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            Se ha guardado correctamente
        </div>
    </div>



{/if}
{/if}

<p style="color:#0073aa;">Categorías PRESTASHOP : <b>{$cantidad_categorias} </b> - Categorías vinculadas : <b>{$categorias_vinculadas}</b></p>
<form action="" method="post">
	<table class="table">
	<thead>
		<tr>
			<th>ID PRESTASHOP</th>
			<th>Nombre Categoria</th>
			<th>ID OBUMA</th>
			
		</tr>
	</thead>

	<tbody>

		{foreach from=$categories item=category}
			<tr>
				<td><input type="hidden" name="id_category[]" value="{$category.id_category}"> {$category.id_category}</td>
				<td><input type="hidden" name="name_category[]" value="{$category.name}"> {$category.name}</td>
				<td><input type="text" name="obuma_id_category[]" value="{$category.obuma_id_category}" placeholder="Ingresa ID OBUMA"></td>
			
			</tr>
		{/foreach}
		
	</tbody>
</table>
<button type="submit" name="vincular_categorias" class="btn btn-primary">Guardar</button>
</form>
