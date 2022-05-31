	if(document.getElementsByName("confirm-addresses").length){
			let add_address = document.getElementsByName("confirm-addresses")[0]
			let p = document.createElement("p");

			let exists = !document.querySelector("input[name='saveAddress']") ? "window.location.href='pedido?use_same_address=0'" : ""
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
                <input name="tipo_documento"  onclick="${exists}" id="field-obuma_tipo_documento-33" type="radio" value="33">
                <span></span>
              </span>
              Factura
            </label><p id="detalles_facturacion"></p></div><br>`
			add_address.insertAdjacentElement('beforebegin',p);
	}




	if(document.querySelector("input[name='saveAddress']")){

	let input_text = document.querySelectorAll("input[type='text']");
	let label = document.querySelectorAll("label");

	if(document.querySelector("input[name='saveAddress']").value == "invoice"){

				for (var i = 0; i < label.length; i++) {
					if(label[i].getAttribute("for")){
						let label_for = label[i].getAttribute("for");

						if(label_for.includes("dni")){
							label[i].innerText = "RUT"
						}

						if(label_for.includes("firstname")){
							label[i].innerText = "Razon social"
						}


						if(label_for.includes("lastname")){
							label[i].innerText = "Giro comercial"
						}
					}


				}


			}else{


				for (var i = 0; i < label.length; i++) {
					if(label[i].getAttribute("for")){
						let label_for = label[i].getAttribute("for");

						if(label_for.includes("dni")){
							label[i].innerText = "RUT del comprador"
						}
					}


				}

				
			}

}

