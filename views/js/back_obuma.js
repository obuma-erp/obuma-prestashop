

$(document).ready(function(){

  /*
  
  VARIABLES GLOBALES, USADAS EN TODO EL PROCESO DE SINCRONIZACION 
  */

  var resumen = "";
  var log = [];
  var agregados = 0;
  var actualizados = 0;
  var cabecera = "<div class='panel panel-info' style='margin-top:15px;'><div class='panel-heading'>Resumen de sincronización</div><div class='panel-body'><table class='table table-bordered table-striped table-condensed'>";
  var pie = "</table></div></div>";
  var categorias_seleccionadas = "all";
  var url = "";
  var before = {};

  /*
  AL INGRESAR A LA PAGINA DE SINCRONIZACION SE CARGA LA ETIQUETA SECCION DE RESULTADOS CON SU ICONO
  
  */

  $("#cargar_vistas").html('<p class="centrar marTop">Sección de Resultados</p><br><i style="font-size:2.8em;" class="large material-icons">equalizer</i>')
  

  /*
  
  DAMOS CLICK EN EL PANEL DE SINCRONIZACION, SI HAY LA POSIBILIDAD DE SELECCIONAR CATEGORIAS, APARECERÁ EL SELECT, DE LO CONTRARIO EMPEZARÁ A SINCRONIZAR 
  
  */

  $('#myList a').on('click',function(e){
  e.preventDefault();
  removerClaseListas();
  resetear();

  $(this).addClass('active');
  url = $(this).attr("href");
  var pagina = $(this).attr("data-pagina");
  if (pagina == "productos_imagenes" || pagina == "precios" || pagina == "productos"  || pagina == "stock") {
    $.ajax({
    method : "POST",
    url : baseDir+"modules/obuma-prestashop-main/categorias.php",
    data : {
      obtener : true
    },
    beforeSend:function(response){
      $("#cargar_vistas").html('<p class="centrar marTop" id="m">Sincronizando con la API <br>Por favor, espere ..</p><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');

    },
    complete:function(response){
        var result = JSON.parse(response.responseText);
        var html = "";
        html += '<div class="row" style="margin-top:20px;">'

        html += '<div class="col-lg-4">'
        html += "</div>"

        html += '<div class="col-lg-4">'
        html += '<div class="row">'
        html += '<div class="col-lg-12">'
        html += "<p>Elija una opcion para SINCRONIZAR</p>"
        html += '<div class="form-group">'
        html += '<select id="combo" class="form-control centrar_select" style="width:100%;max-width: 100%">'
        html += '<option value="all">TODAS LAS CATEGORIAS</option>'

        for (var i = 0; i < result.length; i++) {
            html += '<option value="'+result[i].producto_categoria_id+'">'+result[i].producto_categoria_nombre+'</option>'
        }

       html += "</select>"
       html += '</div>'
       html += "</div>"
       html += "</div>"

       html += '<div class="row">'
       html += '<div class="col-lg-12">'
       html += '<div class="form-group"><button class="btn btn-primary form-control" data-pagina="'+pagina+'" id="sincronizar_productos"><span class="glyphicon glyphicon-refresh"  aria-hidden="true"></span> SINCRONIZAR AHORA</button></div>'
       html += "</div>"
       html += "</div>"
       html += "</div>"

       html += '<div class="col-lg-4">'
       html += "</div>"

       html += '</div>'


       //html += '</div>'
       //html += '</div>'
       html += '<div class="row">'
       html += '<div id="loader_producto">'
       html += '</div>'
       html += '<div id="completado_producto">'
       html += '</div>'
       html += '</div>'
      $("#cargar_vistas").html(html)
    }
     });

   
    
   
  
  }else{
    var html = "";
        html += '<div class="row" style="margin-top:20px;">'

        html += '<div class="col-lg-4">'
        html += "</div>"

        html += '<div class="col-lg-4">'
       
       html += '<div class="row">'
       html += '<div class="col-lg-12">'
       html += "<p>Presione para SINCRONIZAR</p>"
       html += '<div class="form-group"><button class="btn btn-primary form-control" data-pagina="'+pagina+'" id="sincronizar_productos"><span class="glyphicon glyphicon-refresh"  aria-hidden="true"></span> SINCRONIZAR AHORA</button></div>'
       html += "</div>"
       html += "</div>"
       html += "</div>"

       html += '<div class="col-lg-4">'
       html += "</div>"

       html += '</div>'


       //html += '</div>'
       //html += '</div>'
       html += '<div class="row">'
       html += '<div id="loader_producto">'
       html += '</div>'
       html += '<div id="completado_producto">'
       html += '</div>'
       html += '</div>'
      

    before = {
      id : "#cargar_vistas",
      content : '<p class="centrar marTop" id="m">Sincronizando con la API <br>Por favor, espere ..</p><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
    };

     $(before.id).html(before.content)
    setTimeout(function(){
       $(before.id).html(html)
     },1000);

  }

});

  /*
  
  CONSULTA AJAX AL BACKEND
  
  */


  function consultar(url,numero_pagina,before){
    var pagina = parseInt(numero_pagina) + 1;
    $.ajax({
      method : "POST",
      url : baseDir+"modules/obuma-prestashop-main/" + url,
      data : { 
        pagina : pagina,
        categorias_seleccionadas : categorias_seleccionadas
      },
      beforeSend:function(){
        if (pagina == 1) {
          $(before.id).html(before.content)
        }   
      },
      success:function(response){
          
          try {
            comprobarRespuesta(response);
          } catch (e) {
            let array = [];
            let error = {
              page : pagina,
              url : url,
              response_api : response,
              error : ""
            };

            array.push(error);
            acumularLog(array);

            
          }

      },
      complete:function(response){
        console.log("completed");

      },
      error:function(error){
        console.log(error)
        alert("Hubo un error al realizar la Sincronización")
      }
    });
  }

  /*
  
  DAMOS CLICK EN EL BOTÓN SINCRONIZAR PRODUCTOS, ESTE BOTON SOLO APARECE CUANDO HAY LA POSIBILIDAD DE SELECCIONAR CATEGORIAS 
  
  */

  $(document).on("click","#sincronizar_productos",function(){
    resetear();
    var pagina = $(this).attr("data-pagina");
  if (pagina == "productos_imagenes" || pagina == "precios" || pagina == "productos"  || pagina == "stock") {
    categorias_seleccionadas = $("#combo").val();
  }else{
    categorias_seleccionadas = "all";
  }
    
    before = {
      id : '#loader_producto',
      content :'<p class="centrar marTop" id="m">Sincronizando con la API <br>Por favor, espere ..</p><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div> <div id="ver" style="margin-top:8px;"><input type="checkbox" class="" id="ver_resumen" style="margin:0px;"> <label style="margin:0px;" id="texto-resumen"> Ver resumen de Sincronización</label><div id="ver_logg" style="margin-top:8px;"> <input type="checkbox" class="" id="ver_log" style="margin:0px;"> <label style="margin:0px;" id="texto-log"> Ver log de Sincronización</label></div></div>'
    };
    console.log(categorias_seleccionadas)
    consultar(url,0,before);
  });
  

  /*
  
    COMPROBAMOS LA RESPUESTA DESDE EL BACKEND
  
  */

  function comprobarRespuesta(response){
      var result = JSON.parse(response);
      console.log(result);
      if (result.completado == result.total) {
          $(".lds-spinner").html("<img style='width:100%;margin-bottom:5px;' src='../modules/obuma-prestashop-main/views/img/notification_done.png'>")
          finalizar();
          $("#texto-resumen").css("color","#337ab7")
          $("#texto-log").css("color","#337ab7")
          acumularResumen(result.resumen.resumen);
          acumularLog(result.log);
          if (comprobarCheckBox()) {
              mostrarResumen();
          }
          if (comprobarCheckBoxLog()) {
            mostrarLog();
          }       
      }else if(result.completado < result.total){
        acumularResumen(result.resumen.resumen);
        acumularLog(result.log);
        if (comprobarCheckBox()) {
            mostrarResumen();
        }

        if (comprobarCheckBoxLog()) {
            mostrarLog();
        }

        consultar(url,result.completado,before);
      }else{
        alert("Hubo un error , el numero de página actual es mayor al total de paǵinas");
      }
  }

 /*
  
  ACUMULAMOS EL LOG EN UN ARRAY DE OBJETOS PARA LUEGO MOSTRARLO EN FORMATO JSON  
  
  */

  function acumularLog(object){
      var tamanio = object.length;
      if (tamanio > 0) {
        for (var i = 0; i < object.length; i++) {

            var dl = {
              page : object[i].page,
              url : object[i].url,
              response_api : object[i].response,
              error : object[i].error
            };

            log.push(dl)
                   
        }
      }  
  }

  /*
  
  ACUMULAMOS EL RESUMEN EN UNA CADENA PARA LUEGO LISTARLO EN UNA TABLA 
  
  */

  function acumularResumen(object){
    var tamanio = object.length;
      if (tamanio > 0) {
        for (var i = 0; i < object.length; i++) {
          if(object[i].action == "agregado"){
            resumen += "<tr><td>Agregado</td><td>"+object[i].name+"</td></tr>"
            agregados++;
          }
          if(object[i].action == "actualizado"){
            resumen += "<tr><td>Actualizado</td><td>"+object[i].name+"</td></tr>"
            actualizados++;
          }
              
        }
      }       
  }

  /*
  
  MOSTRAR RESUMEN EN EL SINCRONIZADOR 
  
  */

  function mostrarResumen(){
    if(agregados == 0 && actualizados == 0){
      $("#completado").html("<p>No hay cambios</p>");
    }else{
      $("#completado").html(cabecera+resumen+pie);
    }
  }

  /*
  
  MOSTRAR LOG EN EL SINCRONIZADOR 
  
  */


  function mostrarLog(){
    $("#completado").html("<pre>" +  JSON.stringify(log,undefined, 2) + "</pre>");
    
  }

  /*
  
  REMOVER LA CLASE ACTIVE DEL PANEL DE SINCRONIZACIÓN 
  
  */

  function removerClaseListas(){
    var opciones = $("#myList a");
    for (var i = 0; i < opciones.length; i++) {
      $(opciones[i]).removeClass("active");
    }
  }

  /*
  
  RESETEAR LOS CONTADORES , LAS VARIABLES DE RESUMEN,LOG Y LAS ETIQUETAS DE COMPLETED 
  
  */

  function resetear(){
    agregados = 0;
    actualizados = 0;
    resumen = "";
    log = [];
    $("#completado").html("");
    $("#completado_producto").html("");
  }


  /*
  
  IMPRIMIR MENSAJE AL FINALIZAR LA SINCRONIZACION 
  
  */

  function  finalizar(){
    $("#m").text("SINCRONIZACIÓN COMPLETADA !");
    
  }

  /*
  
  CHECKBOX DE RESUMEN Y LOG 
  
  */

  //ESCUCHAMOS CAMBIOS EN EL CHECKBOX DE RESUMEN
  
  $(document).on('change',"#ver_resumen", function() {
    if(comprobarCheckBox()) {
      mostrarResumen();
       $("#ver_log").prop("checked", false);
    }else{
      $("#completado").html("");
    }
  });

  //ESCUCHAMOS CAMBIOS EN EL CHECKBOX DE LOG
  
  $(document).on('change',"#ver_log", function() {
    if(comprobarCheckBoxLog()) {
      mostrarLog();
      $("#ver_resumen").prop("checked", false);
    }else{
      $("#completado").html("");
    }
  });



  //Comprobar si el checkbox de resumen está seleccionado

  function comprobarCheckBox(){
    var test = false;
    if($("#ver_resumen").is(':checked')){
      test = true;
    }
    return test;
  }

  //Comprobar si el checkbox de log está seleccionado

  function comprobarCheckBoxLog(){
    var test = false;
    if($("#ver_log").is(':checked')){
      test = true;
    }
    return test;
  }


  function comprobar_json($string){
       try {
           JSON.parse(string);
       } catch (e) {
           return false;
       }
       return true;

  }
});
