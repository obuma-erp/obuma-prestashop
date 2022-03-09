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

