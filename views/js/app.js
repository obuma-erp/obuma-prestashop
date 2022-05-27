/*
if (document.getElementsByName('obuma_tipo_documento')[0]) {
	var tipo_documento_e = document.getElementsByName('obuma_tipo_documento')[0];
	var giro_comercial_e = document.getElementsByName('order_obuma_giro_comercial')[0];
	tipo_documento_e.addEventListener("change",function(){
		let tipo_documento = this.value;
		if (tipo_documento == "39") {
			giro_comercial_e.style.display = "none";
			giro_comercial_e.removeAttribute("required");
			giro_comercial_e.value = " ";
		}else{
			giro_comercial_e.style.display = "block";
			giro_comercial_e.setAttribute("required","required");
			giro_comercial_e.value = "";
		}
	
	});


	if (tipo_documento_e.value == "39") {
			giro_comercial_e.style.display = "none";
			giro_comercial_e.removeAttribute("required");
			giro_comercial_e.value = " ";
		}else{
			giro_comercial_e.style.display = "block";
			giro_comercial_e.setAttribute("required","required");
			if(giro_comercial_e.value == " "){
				giro_comercial_e.value = "";
			}
			
		}

	
}





	function change_select(element){

		let value = element.value;
		let html = "";

		let detalles_facturacion = document.createElement("div");

		if(value == 33){

			html += `<label>Rut</label><input placeholder="Ingresa el Rut" id="field-razon_social" class="form-control" name="phone" required type="tel" value="" maxlength="32">`
			html += `<label>Razon social</label><input placeholder="Ingresa la razon social " id="field-razon_social" class="form-control" required name="phone" type="tel" value="" maxlength="32">`
			html += `<label>Giro comercial</label><input placeholder="Ingresa el giro comercial" id="field-razon_social" class="form-control" required name="phone" type="tel" value="" maxlength="32">`
			
			document.getElementById("detalles_facturacion").innerHTML = html


		}else{

			document.getElementById("detalles_facturacion").innerHTML = ""
		}

		
	}

*/





/*
		if(document.getElementsByName("confirm-addresses").length){
			let add_address = document.getElementsByName("confirm-addresses")[0]
			let p = document.createElement("p");

			p.innerHTML = `<br><strong>Seleccione el tipo de documento</strong><br><div class="col-md-12 form-control-valign">

      
        
                      <label class="radio-inline" for="field-obuma_tipo_documento-39">
              <span class="custom-radio">
                <input name="tipo_documento" onclick='select_tipo_documento(39)' id="field-obuma_tipo_documento-39" type="radio" value="39" checked>
                <span></span>
              </span>
              Boleta
            </label>
                      <label class="radio-inline" for="field-obuma_tipo_documento-33">
              <span class="custom-radio">
                <input name="tipo_documento"  onclick="" id="field-obuma_tipo_documento-33" type="radio" value="33">
                <span></span>
              </span>
              Factura
            </label><p id="detalles_facturacion"></p></div><br>`
			add_address.insertAdjacentElement('beforebegin',p);
	}

*/


	if(document.querySelector("a[data-link-action='different-invoice-address']")){
		document.querySelector("a[data-link-action='different-invoice-address']").style.display = "none"
	}


	

	function select_tipo_documento(tipo){

		document.getElementById("tipo_documento").value = tipo
	}

	//document.getElementById("content").insertAdjacentHTML("afterbegin","<input type='hidden' name='tipo_documento' id='tipo_documento'>")



	if(document.querySelector("input[name='saveAddress']")){



	if(document.querySelector("input[name='saveAddress']").value == "invoice"){

		if(document.querySelector("label[for='field-firstname']")){
			document.querySelector("label[for='field-firstname']").innerText = "Razon social"
		}
		
		if(document.querySelector("label[for='field-lastname']")){
			document.querySelector("label[for='field-lastname']").innerText = "Giro comercial"
		}


		if(document.querySelector("label[for='field-dni']")){
			document.querySelector("label[for='field-dni']").innerText = "RUT"
		}
		
		
	}else{


		if(document.querySelector("label[for='field-dni']")){
			document.querySelector("label[for='field-dni']").innerText = "RUT de comprador"
		}
		
	}

}
