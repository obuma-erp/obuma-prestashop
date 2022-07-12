<br>
<div class="panel panel-info">

	<div class="panel-heading bg-white">

		ACERCA DEL M&Oacute;DULO

	</div>

	<div class='panel-body'>
		
		<p><strong>Informacion importante sobre el funcionamiento del m&oacute;dulo</strong></p>
		
		<div class="wrap">

			<ul>
				<li>Versi&oacute;n actual del m&oacute;dulo : <code>1.0.1</code></li>
				<li>Requisitos m&iacute;nimos  : <code>Prestashop 1.7.7.0</code>  &oacute; superior , <code>PHP 5.6</code> &oacute; superior</li>

			</ul>



		</div>

	</div>

</div>



<div class="panel panel-info">

	<div class="panel-heading bg-white">

		ACERCA DE LOS WEBHOOKS

	</div>

	<div class='panel-body'>
		
		<p><strong>Estas son los webhooks del m&oacute;dulo para vincular con OBUMA</strong></p>
		
		<div class="wrap">

			<code>Crear producto</code>

			<pre><input type="text" id="webhook_obuma_crear_producto" class="input_copy_link"  readonly="readonly" style="width: 90%;" name="" value="{$url_plugin}obuma_webhook_receiver_productoCreated.php"> <button class="btn btn-primary" onclick="copiarAlPortapapeles(this,'webhook_obuma_crear_producto')">Copiar</button></pre>

			<code>Actualizar producto</code>

			<pre><input type="text" id="webhook_obuma_actualizar_producto"  class="input_copy_link"  readonly="readonly" name="" style="width: 90% !important;" value="{$url_plugin}obuma_webhook_receiver_productoUpdated.php"> <button class="btn btn-primary button_copy" onclick="copiarAlPortapapeles(this,'webhook_obuma_actualizar_producto')">Copiar</button></pre>


			<code>Actualizar precio</code>

			<pre><input type="text" id="webhook_obuma_actualizar_precio"  class="input_copy_link"  readonly="readonly" name="" style="width: 90%;" value="{$url_plugin}obuma_webhook_receiver_precios.php"> <button class="btn btn-primary" onclick="copiarAlPortapapeles(this,'webhook_obuma_actualizar_precio')">Copiar</button></pre>

			<code>Actualizar stock</code>

			<pre><input type="text" id="webhook_obuma_actualizar_stock"  class="input_copy_link"  readonly="readonly" name="" style="width: 90%;" value="{$url_plugin}obuma_webhook_receiver_productoStockCreated.php"> <button class="btn btn-primary" onclick="copiarAlPortapapeles(this,'webhook_obuma_actualizar_stock')">Copiar</button></pre>


			<code>Crear cliente</code>

			<pre><input type="text" id="webhook_obuma_crear_cliente"  class="input_copy_link"  readonly="readonly" name="" style="width: 90%;" value="{$url_plugin}obuma_webhook_receiver_clienteCreated.php"> <button class="btn btn-primary" onclick="copiarAlPortapapeles(this,'webhook_obuma_crear_cliente')">Copiar</button></pre>
			

			<code>Actualizar cliente</code>

			<pre><input type="text" id="webhook_obuma_actualizar_cliente"  class="input_copy_link"  readonly="readonly" name="" style="width: 90%;" value="{$url_plugin}obuma_webhook_receiver_clienteUpdated.php"> <button class="btn btn-primary" onclick="copiarAlPortapapeles(this,'webhook_obuma_actualizar_cliente')">Copiar</button></pre>





			
			<?php// echo __FILE__; ?>

		</div>

	</div>

</div>


<script type="text/javascript">
	
function copiarAlPortapapeles(element,id_elemento) {

  element.classList.add("button_black");
  // Crea un campo de texto "oculto"
  var aux = document.createElement("input");

  // Asigna el contenido del elemento especificado al valor del campo
  aux.setAttribute("value", document.getElementById(id_elemento).value);

  // Añade el campo a la página
  document.body.appendChild(aux);

  // Selecciona el contenido del campo
  aux.select();

  // Copia el texto seleccionado
  document.execCommand("copy");

  // Elimina el campo de la página
  document.body.removeChild(aux);

  setInterval(function(){
  	element.classList.remove("button_black");
  },1000)

}

</script>