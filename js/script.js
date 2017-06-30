var categorias= new Array();
var limit_plan_general_consulta=50;
$(document).ready(function() {

  
  $('.table_general').on('click','.liquidacion',function(){
 // $('.liquidacion').click(function(){
    $('.textLiquidacionSemanal').addClass('hideLiquidacion');
    $('.textLiquidacion').addClass('hideLiquidacion');
    $(this).children('.textLiquidacion').removeClass('hideLiquidacion');
    /*var semana=$(this).attr('data-semana');
    console.log(semana);
    var capitulos=$('.s_'+semana).attr('data-c');
     //console.log(capitulos);
     $(this).children('.textLiquidacion').html(capitulos);*/
  });

  $('.liquidacionSemana').click(function(){
    $('.textLiquidacionSemanal').addClass('hideLiquidacion');
    $('.textLiquidacion').addClass('hideLiquidacion');
    $(this).children('.textLiquidacionSemanal').removeClass('hideLiquidacion');
  });
  //FUNCION PARA LINKEAR PRODUCCIONES
  $('.production_div').on('click', function(){
      location.href = $(this).attr('data-href');
  });
  /*FUNCION CARGA DE CAMPOS SEGUN NUMERO DE UNIDADES PLAN PRODUCCION*/
  $('#unidades').change(function(){
    var i = $('#unidades').val();
    var cont=1;
    while(i>=cont){
      var id=".field_form"+cont;
      $(id).fadeIn(500);
      cont++;
    }
    while(cont<=5){
      var id=".field_form"+cont;
      var id2="#director_"+cont;
      $(id).fadeOut(500); 
      cont++;
    }
    if(i==1){
      $('#first_unity').hide();
    }else{
      $('#first_unity').show();
    }
  });
  /*FIN FUNCION CARGA DE CAMPOS SEGUN NUMERO DE UNIDADES PLAN PRODUCCION*/

  /*FUNCION CARGA DE USUARIOS SEGUN ROL*/
  $('#tipo_user').change(function(){
    var id_tipo=$('#tipo_user').val();
    var id_produccion=$('#id_produccion').val();
    var datos={id_tipo:id_tipo,idproduccion:id_produccion}
    var user_final='';
    $.ajax({
      type: "POST",
      url: site_url+"/plan_produccion/carga_usuarios",
      data: datos,
      dataType: "json",
      success:function(data){
        if(data.user){
          $.each(data.user, function(i,user){
          user_final = user_final+'<input type="hidden" name="id_produccion" id="id_produccion" value="'+id_produccion+'"> <tr class="sort_left"><input type="hidden" name="id_user" id="id_user" value="'+user.id_user+'"><input type="hidden" name="rol" id="rol" value="'+user.id_rol+'"><td>'+user.nombre+' '+user.apellido+'</td><td>'+user.descripcion+'</td><td class="role_hiden" style="text-align:center;"><input type="checkbox" id=""></td><td class="actions_hiden" style="text-align: center;"> <a class="delete" >'+eliminar+'</a> <a class="add" >'+agregar+'</a> </td></tr>';
          });
        }else{
          user_final='<tr class="sort_left"><td colspan="4">'+no_hay_usuario_en_esta_categoria+'</td></tr>'
        }
       $("#table_1").html(user_final);
      }
    });
  });
  /*FIN FUNCION CARGA DE USUARIOS SEGUN ROL*/ 
  
  /*FUNCION AGREGAR USUARIO A PRODUCCION Y A TABLA*/    
  $('#table_1').on('click', '.add', function(){
    $('#wrapper_loader').fadeIn();
    var elem = $(this).parent().parent();
    var id_user=$(this).parent().parent().find('#id_user').val();
    var rol=$(this).parent().parent().find('#rol').val();
    var id_produccion=$('#id_produccion').val();
    var datos={id_user:id_user,rol:rol,id_produccion:id_produccion};
    $.ajax({
    type: "POST",
    url: site_url+"/plan_produccion/agregar_usuario",
    data: datos,
    dataType: "json",
      success:function(data){
       $("#table_2").append(elem);
      }
    }); 
    $('#table_2 .hidden_row').css('display','none');
    $('#table_1 .hidden_row').css('display','block');
    $('#wrapper_loader').fadeOut();
    return false;
  });
  /*FIN FUNCION AGREGAR USUARIO A PRODUCCION Y A TABLA*/   

  /*FUNCION ELIMINAR USUARIO DE PRODUCCION Y TABLA*/
  $('#table_2').on('click', '.delete', function(){
    $('#wrapper_loader').fadeIn();
    var elem = $(this).parent().parent();
    var id_user=$(this).parent().parent().find('#id_user').val();
    var id_produccion=$('#id_produccion').val();
    var datos={id_user:id_user,id_produccion:id_produccion};
    $.ajax({
    type: "POST",
    url: site_url+"/plan_produccion/borrar_usuario",
    data: datos,
    dataType: "json",
      success:function(data){
       $("#table_1").append(elem);
       $(this).parent().parent().remove();
      }
    }); 
    $('#table_1 .hidden_row').css('display','none');
    $('#table_2 .hidden_row').css('display','block');
    $('#wrapper_loader').fadeOut();
    return false;
  });
  /*FIN FUNCION ELIMINAR USUARIO DE PRODUCCION Y TABLA*/

  /*CONEXION DE TABLAS DE USUARIOS*/
  $( "#table_1, #table_2" ).sortable({
    connectWith: ".connectedSortable",
    change: function( event, ui ) {
    }
  }).disableSelection(); 
  /*FIN CONEXION DE TABLAS DE USUARIOS*/

  /*SORTABLE TABLAS USUARIOS*/
  $( "#table_2" ).sortable({
    connectWith: ".connectedSortable",
    receive: function( event, ui ) {
      var id_user= $(ui.item.context).find('#id_user').val();
      var rol= $(ui.item.context).find('#rol').val();
      var id_produccion=$('#id_produccion').val();
      guardar_user(id_user,rol,id_produccion);
      $('#table_2 .hidden_row').css('display','none');
    }
  }).disableSelection();

  $( "#table_1" ).sortable({
    connectWith: ".connectedSortable",
    receive: function( event, ui ) {
      var id_user= $(ui.item.context).find('#id_user').val();
      var id_produccion=$('#id_produccion').val();
     delete_user(id_user,id_produccion);
     $('#table_1 .hidden_row').css('display','none');
    }
  }).disableSelection();
  /*FIN SORTABLE TABLAS USUARIOS*/
  
  /*FUNCION AGREGAR USUARIO PRODUCCION*/ 
  function guardar_user(id_user,rol,id_produccion){
    var datos={id_user:id_user,rol:rol,id_produccion:id_produccion};
    $.ajax({
    type: "POST",
    url: site_url+"/plan_produccion/agregar_usuario",
    data: datos,
    dataType: "json",
      success:function(data){
       return false;
      }
    });  
  }
  /*FIN FUNCION AGREGAR USUARIO PRODUCCION*/ 

  /*FUNCION BORRAR USUARIO PRODUCCION*/ 
  function delete_user(id_user,id_produccion){
    var datos={id_user:id_user,id_produccion:id_produccion};
        $.ajax({
          type: "POST",
          url: site_url+"/plan_produccion/borrar_usuario",
          data: datos,
          dataType: "json",
          success:function(data){
           $("#table_1").append(elem);
          }
        }); 
  }
  /*FIN FUNCION BORRAR USUARIO PRODUCCION*/ 
  
  /*FUNCION EDICION DATOS CAPITULO*/
  $('.main').on('click','.save_icon',function(){
    var escenas_escritas=$(this).attr('escenasDesglosadas');
    var idproduccion = $(this).attr('idproduccion');
    var idcapitulo = $(this).attr('idcapitulo');
    var opcion = $(this).attr('opcion');
    var valor = $('#'+opcion+"_"+idproduccion+'_'+idcapitulo+'').val();
    datos= {idproduccion: idproduccion, idcapitulo: idcapitulo,opcion:opcion,valor:valor}
    $.ajax({
      type: "POST",
      url: site_url+"/libretos/editar_capitulo",
      async: true,
      data: datos,
      dataType: "json",
      success:function(data){
        if(opcion!="fecha_aire" && opcion!="numero_escenas" && opcion!="paginasPorLibretos"){
          $('.hide_box').stop().fadeOut();
          $('.table_general table table.secondary tr td').css({'background':'transparent','font-weight':'normal'});
          $('.open_box').css({'color':'#0098d2'});
          document.getElementById(opcion+"_text"+idproduccion+'_'+idcapitulo).innerHTML = valor.substring(0,15)+"";
        }else{
            var limite_inf = $('#limit_inf').val();
            var limite_sup = $('#limit_sup').val();
            if(data.validacion){
              window.location.href = site_url+'/libretos/filtro_libretos/'+idproduccion+'/'+limite_inf+'/'+limite_sup; 
            }else{
              alert(no_se_puede_cambiar_la_cantidad_de_escenas);
            } 
        }
      }
    });
  });
  /*FUNCION EDICION DATOS CAPITULO*/


  /*BOTON CANCELAR CAPITULO*/
  $('#wrapper').on('click','.cancel_cap',function(){
    var idproduccion = $(this).attr('idproduccion');
    var idcapitulo = $(this).attr('idcapitulo');
    var confirmacion = desea_cancelar_el_libreto_y_eliminar+" ("+$(this).attr('data-noproducudos')+") "+escenas_no_producidas;
    if($(this).attr('planes-producidos') !=""){
      confirmacion+= y_cancelar+"+ ("+$(this).attr('planes-producidos')+") "+escenas_pro;
    }
    if($(this).attr('planes-asignados')!=""){
      confirmacion += y_las_asignadas_el+ $(this).attr('planes-asignados');
    }
    confirmacion+=" ?";
    if(confirm(confirmacion)){
      datos= {idproduccion: idproduccion, idcapitulo: idcapitulo}
      $.ajax({
        type: "POST",
        url: site_url+"/libretos/cancelar_capitulo",
        data: datos,
        dataType: "json",
        success:function(data){
         window.location.href = site_url+'/libretos/index/'+idproduccion;
        }
      });
    }
  });
  /*FIN BOTON CANCELAR CAPITULO*/

  /*BOTON PARA LIMINAR CAPITULO*/
  $('#wrapper').on('click','.delete_cap',function(){
    var idproduccion = $(this).attr('idproduccion');
    var idcapitulo = $(this).attr('idcapitulo');
    var confirmacion = esta_seguro_de_que_desea_eliminar;
    if($(this).attr('planes-asignados')!=""){
      confirmacion = esta_seguro_de_que_desea_eliminar_el_libreto + $(this).attr('planes-asignados') + " )?";
    }
    if(confirm(confirmacion)){
      datos= {idproduccion: idproduccion, idcapitulo: idcapitulo}
      $.ajax({
        type: "POST",
        url: site_url+"/libretos/eliminar_capitulo",
        data: datos,
        dataType: "json",
        success:function(data){
          window.location.href = site_url+'/libretos/index/'+idproduccion;
        }
      });
    }
  });
  /*FIN BOTON PARA ELIMINAR CAPITULO*/


   

  /*FUNCION PARA ACCIONES DE ESCENA*/
  $('.action_escena').click(function() {
    var idproduccion = $(this).attr('idproduccion');
    var idcapitulo = $(this).attr('idcapitulo');
    var idescena = $(this).attr('idescena');
    var opcion = $(this).attr('opcion');
    var numero_escena = $('#numero_escena').val();
   
    if(opcion!="duplicar"){
      datos= {idproduccion: idproduccion, idcapitulo: idcapitulo,opcion:opcion,idescena:idescena,numero_escena:numero_escena}
      var confirmacion = esta_seguro_que_desea_+" "+opcion+"?";
      if(opcion=="eliminar" && $(this).attr('asignacion')!='-'){
        confirmacion = esta_seguro_que_desea_desasignar_la_escena+" "+$(this).attr('asignacion')+" "+y_eliminarla;
      }
      if(confirm(confirmacion)){
        $('#wrapper_loader').fadeIn();
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/acciones_escenas",
          async: true,
          data: datos,
          dataType: "json",
          success:function(data){
            window.location.href = site_url+'/escenas/index/'+idproduccion+'/'+idcapitulo;
          }
        });
      }
    }else{
      idcapitulo = $('#cap').val();
      datos2= {idcapitulo: idcapitulo,numero_escena:numero_escena}
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/validacion_numero",
          data: datos2,
          dataType: "json",
          success:function(data){
            if(data.existe==true){
                datos= {idproduccion: idproduccion, idcapitulo: idcapitulo,opcion:opcion,idescena:idescena,numero_escena:numero_escena}
                if(confirm(esta_seguro_que_desea_+opcion+"?")){
                  $('#wrapper_loader').fadeIn();
                  $.ajax({
                    type: "POST",
                    url: site_url+"/escenas/acciones_escenas",
                    async: true,
                    data: datos,
                    dataType: "json",
                    success:function(data){
                      if($('#cap').val()==$('#list_cap').val()){
                        window.location.href = site_url+'/escenas/index/'+idproduccion+'/'+idcapitulo;
                      }else
                      $('#duplicate_escene').fadeOut();
                      $('#wrapper_loader').fadeOut();
                      $('#wrappOverlay').fadeOut();
                    }
                  });

                }
            }else{
                $('.label-error-hiden').remove();

               $('#numero_escena').addClass('error');
                html1 = '<label class="error label-error-hiden">'+esta_escena_ya_existe+'.</label>';
                $('#numero_escena').parent().append(html1);
            }
          }
        });
    }

  });
  /*FIN FUNCIONES PARA ACCIONES DE ESCENA*/

  /*FUNCION APRETURA PANTALLA DE DUPLICCION ESCENA*/
  $('.duplicate_escena').click(function(){
    $(this).parent().parent().click();
    $('#wrappOverlay').fadeIn();
    $('#duplicate_escene').fadeIn();  
    $('#duplicate_button').attr('idescena',$(this).attr('idescena'));
    $('#duplicate_button').attr('idcapitulo',$(this).attr('idcapitulo'));
    $('#duplicate_button').attr('idproduccion',$(this).attr('idproduccion'));
    $('#cap').change();
  });
  /*FIN FUNCION APRETURA PANTALLA DE DUPLICCION ESCENA*/

  $('#botton_yes').click(function(){
    $('#user_box').css('display','block')
    $('#confirm').css('display','none')
  });

  /*FUNCION PARA CAMPOS DE FECHA*/
  $(".datepicker").datepicker({
      dateFormat: 'dd-M-yy',
      showOn: "button",
      buttonImage: site_url2+"/images/calendar_white.png",
      buttonImageOnly: true
  });


  /*FIN FUNCION PARA CAMPOS DE FECHA*/

  /*FUNCION MUESTRA SECCION PARA FILTRO ESCENAS */
  $('.icon_filter').click(function(){
    $('#content_filter').fadeIn();
  });
  /*FIN FUNCION MUESTRA SECCION PARA FILTRO ESCENAS */ 

  /*SEGUNDO BOTON DE FILTRO PLAN GENERAL*/
  var sumatoria = 0;
  $('#filter_button2').click(function(){
    if(sumatoria%2!=0){
      $('.filter_button').click();
    }
    sumatoria++;
  });
  /*FIN SEGUNDO BOTON DE FILTRO PLAN GENERAL*/



  /*BOTON DE LIMPIAR SELECCION EN PLAN GENERAL*/
  $('#limpiar_seleccion').click(function(){
    $(".tabla_info input").each(function() {
      if($(this).attr('type')=="text"){
        $(this).val("");
      }
      if($(this).attr('type')=="radio" || $(this).attr('type')=="checkbox"){
        $(this).prop('checked', false);
      }
    });

    $(".tabla_info select").each(function() {
      $("option", this).eq(0).prop('selected', true);
      $(this).change();
    });

    $("#tabla2 .eliminar_elemento").each(function() {
      $(this).click();
    });

    $('#elemento_id').change();

    return false;
  });
  /*FIN BOTON DE LIMPIAR SELECCION EN PLAN GENERAL*/

  /*FUNCION CERRAR PANEL DE FILTRO PLAN GENERAL*/
  $('#cancel_filter').click(function(){
    $('#content_filter').fadeOut();
    return false;
  });
  /*FIN FUNCION CERRAR PANEL DE FILTRO PLAN GENERAL*/
  

  $('.id_elemento').change();
  
  /*FUNCION CARGA SETS SEGUN LOCACION ESCENAS*/
  /*$('#location').change( function(){

       $('#location').removeClass('error');
       $('#set').removeClass('error');
          
    $("#set").css('background','lightblue');
    $("#set").html('<option>'+cargando+'...</option>');
    var id_locacion= $('#location').val();
    var sets='';
     datos= {id_locacion: id_locacion}
    $.ajax({
      type: "POST",
      url: site_url+"/escenas/set",
      data: datos,
      dataType: "json",
      success:function(data){
          var pathname = window.location.pathname;  
          if(pathname.search("plan_general/")>0 || pathname.search("escenas/index/")>0 || pathname.search("escenas/filtro_escenas")>0 || pathname.search("escenas/buscar_escenas")>0){
            sets = sets+"<option value=''>Todos</option>";
          }
          if(data.set){
            if(data.set.length>1 && (pathname.search("escenas/crear_escenas")>0 || pathname.search("escenas/editar_escena/")>0)){
              sets=sets+"<option value=''>Seleccione un set</option>";
            }
            $.each(data.set, function(i,set){
              sets=sets+"<option value='"+set.id+"'";
              if(pathname.search("escenas/filtro_escenas")>0){
                sets+= " selected ";
              } 
              sets+=" >"+set.nombre+"</option>";
            });
          } else{
            if(pathname.search("plan_general/")>0){
              var idproduccion = $('#idproduccion').val();
              datos= {idproduccion: idproduccion}
              $.ajax({
                type: "POST",
                url: site_url+"/plan_general/sets_produccion",
                data: datos,
                dataType: "json",
                success:function(data){
                  $.each(data.set, function(i,set){
                    sets+="<option value='"+set.id+"'>"+set.nombre+"</option>";
                  });
                  $("#set").html(sets);
                }
              });
            }else{
              sets = sets+"<option value=''>"+no_hay_set_para_esta_locacion+"</option>";
            }
          }
          $("#set").css('background','');
         $("#set").html(sets);
      }
    });
  });*/

$('#location_crear_escena').change( function(){

       $('#location_crear_escena').removeClass('error');
       $('#set').removeClass('error');
          
    $("#set").css('background','lightblue');
    $("#set").html('<option>'+cargando+'...</option>');
    var id_locacion= $('#location_crear_escena').val();
    var sets='';
     datos= {id_locacion: id_locacion}
    $.ajax({
      type: "POST",
      url: site_url+"/escenas/set",
      data: datos,
      dataType: "json",
      success:function(data){
          var pathname = window.location.pathname;  
          if(pathname.search("plan_general/")>0 || pathname.search("escenas/index/")>0 || pathname.search("escenas/filtro_escenas")>0 || pathname.search("escenas/buscar_escenas")>0){
            sets = sets+"<option value=''>Todos</option>";
          }
          if(data.set){
            if(data.set.length>1 && (pathname.search("escenas/crear_escenas")>0 || pathname.search("escenas/editar_escena/")>0)){
              sets=sets+"<option value=''>Seleccione un set</option>";
            }
            $.each(data.set, function(i,set){
              sets=sets+"<option value='"+set.id+"'";
              if(pathname.search("escenas/filtro_escenas")>0){
                sets+= " selected ";
              } 
              sets+=" >"+set.nombre+"</option>";
            });
          } else{
            if(pathname.search("plan_general/")>0){
              var idproduccion = $('#idproduccion').val();
              datos= {idproduccion: idproduccion}
              $.ajax({
                type: "POST",
                url: site_url+"/plan_general/sets_produccion",
                data: datos,
                dataType: "json",
                success:function(data){
                  $.each(data.set, function(i,set){
                    sets+="<option value='"+set.id+"'>"+set.nombre+"</option>";
                  });
                  $("#set").html(sets);
                }
              });
            }else{
              sets = sets+"<option value=''>"+no_hay_set_para_esta_locacion+"</option>";
            }
          }
          $("#set").css('background','');
         $("#set").html(sets);
      }
    });
  });

      $('body').on("click",'.remove_set', function(){
        $('.remove_').attr('id','');        
        $(this).attr('id','set');
          if ($(this).checked = true) {
              $('.remove_').attr("disabled", true);
              $(this).removeAttr("disabled");
          }else{
              $('.remove_').removeAttr("disabled", true);
          }
          if (!this.checked) {
            $('.remove_').removeAttr("disabled", true);
          }
                    
      })

      $('#location').change( function(){
        var id_locacion= $('#location').val();
        var sets='';
         datos= {id_locacion: id_locacion}
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/set",
          data: datos,
          dataType: "json",
          success:function(data){

                  var html='';
                  $.each(data.set, function(i,set){
                        html+='<label>'; 
                        html+='<input name="set" data-name="'+set.nombre+'" class="remove_set remove_" type="checkbox" value="'+set.id+'">'+set.nombre;
                        html+='</label>';
                  });
                  
                  $(".sets_locaciones_a_cambio").html(html); 
                  console.log(html)
                  $("#set").html(html);  
                }
            });            
      })  

      $('.location').click( function(){

      $(".location").prop("checked",false);
      $(this).prop("checked",true);
      // var id_locaciones =$("#locacion_select_herramientas option:selected").val();
      var id_locaciones=$(this).val()
        var sets='';
         datos= {id_locacion: id_locaciones}
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/set",
          data: datos,
          dataType: "json",
          success:function(data){

                  var html='';
                  $.each(data.set, function(i,set){
                        html+='<label>'; 
                        html+='<input name="set" data-name="'+set.nombre+'" class="remove_set remove_" type="checkbox" value="'+set.id+'">'+set.nombre;
                        html+='</label>';
                  });
                  
                  $(".sets_locaciones_a_cambio").html(html); 
                  $("#set").html(html);  
                }
            });            
      })  


    $('.locacion_select').click( function(){
       var roles_personajes = document.getElementsByName('locacion[]');
       var id_locaciones='';
        $.each(roles_personajes, function(i,rol){
              if (rol.checked) {
                id_locaciones+=rol.value+',';
              }

        });
        if(id_locaciones){
           datos= {locaciones: id_locaciones}
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/set_multiple",
            data: datos,
            dataType: "json",
            success:function(data){
              var html='';
              $.each(data.set, function(i,set){
                    html+='<label>'; 
                    html+='<input name="sets[]" type="checkbox" value="'+set.id+'">'+set.nombre;
                    html+='</label>';
                  });
              $(".sets_locaciones").html(html);
            }
          }); 
        }   
    })

    $('.locacion_select_herramientas').change( function(){
      $(".locacion_select_herramientas").prop("checked",false);
      $(this).prop("checked",true);
      // var id_locaciones =$("#locacion_select_herramientas option:selected").val();
      var id_locaciones=$(this).val()
        if(id_locaciones){
           datos= {locaciones: id_locaciones}
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/set_multiple",
            data: datos,
            dataType: "json",
            success:function(data){
              var html='';
              $.each(data.set, function(i,set){
                    html+='<label>'; 
                    html+='<input name="sets[]" type="checkbox" value="'+set.id+'">'+set.nombre;
                    html+='</label>';
                  });
              
                $(".sets_locaciones").html(html);  
            }
          }); 
        }   
    })

    $('.locacion_select_cambio').click( function(){
       //var id_locaciones =$("#locacion_select_cambio option:selected").val();
       $(".locacion_select_cambio").prop("checked",false);
          $(this).prop("checked",true);
          var id_locaciones=$(this).val()
        if(id_locaciones){
           datos= {locaciones: id_locaciones}
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/set_multiple",
            data: datos,
            dataType: "json",
            success:function(data){
              var html='';
              $.each(data.set, function(i,set){
                    html+='<label>'; 
                    html+='<input name="sets_actual[]" class="sets_actual" type="checkbox" value="'+set.id+'">'+set.nombre;
                    html+='</label>';
                  });
              
                $(".sets_locaciones_cambio").html(html);  
            }
          }); 
        }   
    })

  /*FUNCION CARGA SETS SEGUN LOCACION ESCENAS*/

  /*FUNCION CREAR LOCACAION CON SET*/
  var counter_add_item_location = '';
  $('#add_item_location').click(function(){
    var required1 = $('#new_location').val();
    var required2 = $('#new_set').val();
    var respuesta = true;
    if(required1 == '' || required2 == '' ){
        if (counter_add_item_location != 1) {
          counter_add_item_location = 1;
          $('#new_location').addClass('error');
          html1 = '<label class="error label-error-hiden">'+campo_requerido+'.</label>';
          $('#new_location').parent().append(html1);

          $('#new_set').addClass('error');
          html2 = '<label class="error label-error-hiden">'+campo_requerido+'.</label>';
          $('#new_set').parent().append(html2);
        }
      }else{
      $('.label-error-hiden').hide();
      $('#new_set').css('display','none');
      $('#add_item_set').css('display','none');
      $('#cancel_item_set').css('display','none');
      $(this).css('display','none');
      $('#new_item_set').show();
      $('#new_item_location').show();
      $('#set').css('display','block');
      $('#location').css('display','block');
      var firstOption = $('#location option:last');
      firstOption.prop('selected', true);
      $('#cancel_item_location').css('display','none');
      $('#new_location').css('display','none');
      var nombre_locacion=$('#new_location').val();
      var nombre_set=$('#new_set').val();
      var idproduccion = $('#idproduccion').val();
      var opciones='';
      datos= {nombre_locacion: nombre_locacion, nombre_set: nombre_set, idproduccion:idproduccion}
      $.ajax({
        type: "POST",
        url: site_url+"/escenas/nombre_locacion",
        data: datos,
        dataType: "json",
        success:function(data){
        if(!data.respuesta){
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/crear_locacion",
            data: datos,
            dataType: "json",
            success:function(data){
               if(data.locacion){
                  var pathname = window.location.pathname;  
                  if(pathname.indexOf('elementos/index')>0){
                    window.location.reload();
                  }
                  $.each(data.locacion, function(i,locacion){
                    if(locacion.id == data.id_locacion){
                      opciones=opciones+"<option selected value='"+locacion.id+"'>"+locacion.nombre+"</option>";
                    }else{
                      opciones=opciones+"<option value='"+locacion.id+"'>"+locacion.nombre+"</option>";
                    }
                  });
                } else{
                  opciones = opciones+"<option value='null'>"+no_hay_set_para_esta_locacion+"</option>";
                }
                  var set="<option value=''>"+no_hay_set_para_esta_locacion+"</option>";
                  $('#add_item_location').fadeOut();
                  $('#new_location').fadeOut();
                  $('#new_location').val('');
                  $('#new_item_location').fadeIn();
                  $("#location_crear_escena").html(opciones).fadeIn();
                  $("#set").html(set);
                  $('#location_crear_escena').change().focus();
                  $('#cancel_item_location').click();
                  return false;
                  
                }

            });
        }else{
          var temp = $('#new_set').val();
          $('#new_item_location').click();
          $('#new_set').val(temp);
          alert(ya_existe_una_locacion_con_este_nombre);
        };
        }
      });
      }
  });
  /*FIN FUNCION CREAR LOCACAION CON SET*/

  /*CANCELAR LA CREACION DE LOCACION*/
  $('#cancel_item_location').click(function(){
    $('#location_crear_escena').show();
    $('#new_location').val('');
    $('#new_set').val('');
    $('#location').change().focus();
  });
  /*FIN CANCELAR LA CREACION DE LOCACION*/

  /*CANCELAR LA CREACION DE SET*/
  $('#cancel_item_set').click(function(){
    $('#new_location').val('');
    $('#new_set').val('');
  });
  /*FIN CANCELAR LA CREACION DE SET*/
  
  /*FOCUS A TEXT BOX AL CREAR LOCACION O SET*/
  $('#new_item_location').click(function(){
    $('#location_crear_escena').hide();
    $('#new_location').focus();
  });

  $('#new_item_set').click(function(){
    $('#new_set').focus();
  });
  /*FIN FOCUS A TEXT BOX AL CREAR LOCACIÓN O SET*/

  var counter_add_item_set = '';
  /*FUNCION CREACION SET*/
   $('#add_item_set').click(function(){
      var required = $('#new_set').val();
      if(required == ''){
          if (counter_add_item_set != 1) {
            counter_add_item_set = 1;
            $('#new_set').addClass('error');
            html1 = '<label class="error label-error-hiden">'+campo_requerido+'.</label>';
            $('#new_set').parent().append(html1);
          };
        }else{
          $('.label-error-hiden').hide();
          var nombre_set=$('#new_set').val();
          var id_locacion=$('#location_crear_escena').val();
          var opciones='';
          datos= {nombre_set: nombre_set,id_locacion:id_locacion}

        $.ajax({
          type: "POST",
          url: site_url+"/escenas/nombre_set",
          data: datos,
          dataType: "json",
          success:function(data){
          if(!data.respuesta){
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/crear_set",
            data: datos,
            dataType: "json",
            success:function(data){
              if(data.set){
                var pathname = window.location.pathname;  
                if(pathname.indexOf('elementos/index')>0){
                  window.location.reload();
                }
                $.each(data.set, function(i,set){
                  if(data.id_set == set.id){
                    opciones=opciones+"<option selected value='"+set.id+"'>"+set.nombre+"</option>";
                  }else{
                    opciones=opciones+"<option value='"+set.id+"'>"+set.nombre+"</option>"; 
                  }
                });
              } else{
                opciones = opciones+"<option value='null'>"+no_hay_set_para_esta_locacion+"</option>";
              }
              $('#add_item_set').fadeOut();
              $('#new_set').fadeOut();
              $('#new_set').val('');
              $('#new_item_set').fadeIn();
              $('#new_item_set').val('');
              $("#set").fadeIn();
              $("#cancel_item_set").fadeOut();
              $("#set").html(opciones).focus();
              //$('#cancel_item_location').click();
              var pathname = window.location.pathname;  
              if(pathname.indexOf('elementos/index')>0){
                alert();
                window.location.reload();
              }
            }
            
          });
          }else{
          var temp = $('#new_set').val();
          $('#new_set').val(temp);
          alert(ya_existe_un_set_con_este_nombre_para_esta_locacion);
        };
        }
      });
    }
  });
  /*FIN FUNCION CREACION SET*/

  /*SORTEABLE TABLAS ELEMETOS EN ESCENA*/
  $(function() {
    $( "#sortable1, #sortable2" ).sortable({
      items: "tr:not(.ui-drag-disabled)",
      connectWith: ".connectedSortable"
    }).disableSelection();

  });

   $(function() {
    $( "#tabla1, #tabla2" ).sortable({
      items: "tr:not(.ui-drag-disabled)",
      connectWith: ".connectedSortable"
    }).disableSelection();

  });
  /*FIN SORTEABLE TABLAS ELEMETOS EN ESCENA*/

  /*FUNCION ASIGNAR ELEMENTOS A TABLA DE ELEMENTOS ESCENA*/
  $("#tabla2").sortable({
   receive: function( event, ui ) {
      $('tr.ui-drag-disabled').hide();
      var pathname = window.location.pathname;
      var id_elemento= $(ui.item.context).find('#id_elemento_escenas').val();
      var escenanombre = $(ui.item.context).find('#nombre').val()+',';
      var opciones="<input type='checkbox' name='elemento["+id_elemento+"]' value="+id_elemento+" style='display:none;' checked>";
      var rol = $(ui.item.context).find('#rol_elemento').val();
      if(rol.indexOf("Extra")>=0 && pathname.search("escena")>0 ){ 
        $( ui.item.context).find('.rol_cell').append('<input type="text" onkeypress="return soloLetras(event)" class="extras_field" name="hidden'+id_elemento+'" value="1">');
      }
      $("#tabla2 .sort_left").append(opciones);
      $('#con_hidden').val($('#con_hidden').val()+escenanombre);
      $('#elements_ids').val($('#elements_ids').val()+id_elemento+',');
    },
    remove:function( event, ui ) {
      var id_elemento = $(this).parent().parent().find('#id_elemento_escenas').val();

      $('#elements_ids').val($('#elements_ids').val().replace(id_elemento + ',',""));
    }
  }).disableSelection(); 
  /*FIN FUNCION ASIGNAR ELEMENTOS A TABLA DE ELEMENTOS ESCENA*/

  /*ASIGNAR ELMENTOS A TABLA INCIAL DE ELEMENTOS*/
  $("#tabla1").sortable({
   receive: function( event, ui ) {
    $(ui.item.context).find('.extras_field').remove();
     var id_elemento= $(ui.item.context).find('#id_elemento_escenas').val();
     var escenanombre = $(ui.item.context).find('#nombre').val()+',';
     $('#sin_hidden').val($('#sin_hidden').val().replace(escenanombre,''));
     $('#con_hidden').val($('#con_hidden').val().replace(escenanombre,''));
     $('#elements_ids').val($('#elements_ids').val().replace(id_elemento + ',',""));
    }
  }).disableSelection(); 
  /*FIN ASIGNAR ELMENTOS A TABLA INCIAL DE ELEMENTOS*/

  $("#sortable2").sortable({
   receive: function( event, ui ) {
        var id_elemento= $(ui.item.context).find('#id_elemento_escenas').val();
        var opciones="<input type='checkbox name='elemento"+id_elemento+" value="+id_elemento+">";
      }
  }).disableSelection(); 


  $(".planDiarioTable>tbody").sortable({
    items: ".order_row",
    cancel: "tr.disabledItem"
  }).disableSelection();

  $( ".planDiarioTable>tbody" ).on( "sortstop", function( event, ui ) {
    var orden_filas = "";
    $(".planDiarioTable tr.order_row").each(function() {
      var idescena = $(this).attr('data-idescena');
      orden_filas+=idescena+',';
    });
    $('#orden_filas').val(orden_filas);
    $('#order_button').fadeIn(); 
  });


  // $('#no_save_order').click(function(){
  //   $.ajax({
  //     type: "POST",
  //     async:false,
  //     url: site_url+"/plan_general/reorder_fields",
  //     dataType: "json",
  //     success:function(data){
  //       $('#order_fields').html(data.cadena_campos);
  //       $( "#itemsEnable, #itemsDisable" ).sortable({
  //         connectWith: ".connectedSortable",
  //       }).disableSelection();

  //       $("#itemsDisable").sortable({
  //         receive: function( event, ui ) {
  //         var newItem= $(ui.item.context).attr("class");
  //            if(newItem == "horizontal_sort"){
  //             $(ui.sender).sortable('cancel');
  //             return false;
  //            }
  //         }
  //       });
  //     }
  //   });
  // });

  $('#save_fields').click(function(){
    var campos="";
    $("#itemsEnable li").each(function() {
      if(!$(this).is(':checked')){
       campos+= $(this).html()+',';
      }
    });
    $('#campos_select').val(campos);
    $('#arderFormPLanDiario').submit(); 
  })

  $('#id_elemento').change(function(){
       var id_elemento=$('#id_elemento').val();
      if(id_elemento==1){
        $('#rolPersonajes').fadeIn();
      }else{
        $('#rolPersonajes').fadeOut();
      }
  });

  /*FUNCION CREAR ELEMENTO*/
  $('#create_element_button').click(function(){
    var validacion = false;
    datos = {nombre: $('#element_name').val(),categoria:$('.id_elemento').val()}
    $.ajax({
      type: "POST",
      async: false,
      url: site_url+"/elementos/validacionElementosAjax",
      data: datos,
      dataType: "json",
      success:function(data){
        if(!data.respuesta){
          $('#element_name').parent().find('label.error').html(este_elemento_ya_existe_en_esta_categoria);
        }
        validacion = data.respuesta;
      }
    });
    if(validacion){
      $('#create_element').submit();
    }
  });
  /*FIN FUNCION CREAR ELEMENTO*/
                               

  var counter_crear_elemento = '';
   $('#crear_elemento').click(function(){
    var required = $('#name_elemento').val();
    if (confirm(desea_crear_elementos +required)) { 

                var required = $('#name_elemento').val();
                var tipo= $(this).parent().parent().find('#tipo').val();
                if(required == ''){
                  if (counter_crear_elemento != 1) {
                    counter_crear_elemento = 1;
                    $('#name_elemento').addClass('error');
                    html = '<label class="error label-error-hiden">'+campo_requerido+'.</label>';
                    $('#name_elemento').parent().append(html);
                  };
                }else{
                  $('.label-error-hiden').hide();
                  $('#name_elemento').removeClass('error');
                  var nombre_elemento=$('#name_elemento').val();
                  var id_elemento=$('#id_elemento2').val();
                  var rol=$('#rol_personaje').val();
                  $('#wrapper_loader').fadeIn();
                  datos= {nombre_elemento: nombre_elemento,id_elemento:id_elemento,rol:rol}
                  $.ajax({
                    type: "POST",
                    async: true,
                    url: site_url+"/escenas/crear_elemento",
                    data: datos,
                    dataType: "json",
                    success:function(data){
                      if(data.respuesta==1){
                        alert(este_elemento_ya_existe_en_esta_categoria);
                        return false;
                      }else{
                        $('#name_elemento').parent().find('.alert-box').remove();
                        $('#name_elemento').parent().append('<div class="alert-box success">'+elemento_creado_con_exito+'<a href="" class="close" tabindex="-1">×</a></div>');
                        $('#name_elemento').val("");
                        /*AGREGAR A LA TABLA 2*/
                        $('.sort_right').hide();
                        var opciones=opciones+'<tr class="sort_left">';
                        opciones=opciones+'<td width="30%"><input type="checkbox" name="elemento['+data.elemento.idelemento+']" value='+data.elemento.idelemento+' style="display:none;" checked><input type="hidden" value='+data.elemento.idelemento+' id="id_elemento_escenas"><input type="hidden" value='+data.elemento.rol_final+' id="tipo"><input type="hidden" value="'+data.elemento.nombre+'" id="nombre"><span>'+data.elemento.tipo+'</span></td>';
                        opciones=opciones+'<td width="50%">'+data.elemento.nombre
                        if(data.elemento.rol_final!=null){
                          opciones=opciones+ '( '+data.elemento.rol_final+' )'
                        }
                        var tempe = data.elemento.rol_final;
                        if(tempe){
                          if(tempe.toUpperCase().indexOf('EXTRA')>=0){
                            opciones= opciones+ '<input type="text" onkeypress="return soloLetras(event)" class="extras_field" name="hidden'+data.elemento.idelemento+'" value="1">';
                          }
                        }
                        if(data.elemento.tipo=='Vehiculo'){
                          opciones= opciones+ '<input type="text" onkeypress="return soloLetras(event)" class="vehiBack_field" name="hidden'+data.elemento.idelemento+'vehiculo" value="1">';
                        }
                        opciones=opciones+'</td>';
                        opciones=opciones+'<td width="20%"><a class="eliminar_elemento" >'+eliminar+'</a> <a class="agregar_elemento" >'+agregar+'</a></td>';
                        opciones=opciones+'</tr>';
                        console.log(opciones);
                        $("#tabla2").append(opciones)
                       /*FIN AGREGAR A LA TABLA 2*/
                      }
                    }
                  });    
                  $('#wrapper_loader').fadeOut();   
                }
          }else{
              return false;
          }      
              return false;
   });

  /*FUNCION INICIAL DE CARGA DE ELEMENTOS EN TABLA*/
  $('#elemento_id').change(function(){
    var elemento_id= $('#elemento_id').val();
    var idproduccion= $(this).attr('idproduccion');
    var idescena = $(this).attr('idescena');
    var opciones='';
    var rol="";
    var elements_ids = $('#elements_ids').val();
       
    datos= {elemento_id: elemento_id, idescena: idescena, idproduccion:idproduccion, elements_ids:elements_ids}
    $.ajax({
      type: "POST",
      url: site_url+"/escenas/buscar_elemento",
      data: datos,
      dataType: "json",
      success:function(data){
        if(data.elementos){
          //alert(data.elementos);
         $.each(data.elementos, function(i,elementos){
            if(elementos.rol!=null){
                rol= ' ( '+elementos.rol+' ) ';
            }else{
              rol="";
            }
            opciones=opciones+'<tr class="sort_left">';
            opciones=opciones+'<td width="30%"><input type="hidden" value="'+rol+'" id="rol_elemento"><input type="hidden" value='+elementos.id_elemento+' id="id_elemento_escenas"><input type="hidden" value="'+elementos.tipo+'" id="tipo"><input type="hidden" value="'+elementos.nombre+'" id="nombre"><span>'+elementos.tipo+'</span></td>';
            opciones=opciones+'<td class="rol_cell" width="50%">'+elementos.nombre;'</td> ';
            opciones+=rol;
            opciones+='<td width="20%" style="text-align:center;">';
            var pathname = window.location.pathname;
            if(pathname.search("plan_general/")>0){
              opciones+='<div class="filter_action"><input type="checkbox" class="sin_checkbox left" escenanombre="'+elementos.nombre+'" ><label class="left">Sin&nbsp&nbsp</label></div>';
            }
            opciones+='<a class="eliminar_elemento">'+eliminar+'</a> <a class="agregar_elemento">'+agregar+'</a></td></tr>';
          });
        } else{
          opciones=opciones+"<tr class='ui-drag-disabled'> class='sort_left'><td>"+no_hay_elementos_para_esta_categoria+"</td></tr>";
        }
        $("#tabla1").html(opciones);
      }
    });
  });
  /*FUNCION INICIAL DE CARGA DE ELEMENTOS EN TABLA*/

  /*FUNCION PARA NO HACER SUBMIT AL PRECIONAR ENTER EN CREACION Y EDICION*/
  $('#buscar_elemento').keypress(function(){
    return !(window.event && window.event.keyCode == 13);
  });
  /*FUNCION PARA NO HACER SUBMIT AL PRECIONAR ENTER EN CREACION Y EDICION*/

    /*FUNCION PARA BUSCAR ELEMENTOS POR NOMBRE*/
    $('#buscar_elemento').keyup(function(e){
      if (e.keyCode == 13 || $('#buscar_elemento').val()=="") {
        $('#loadElements').fadeIn();      
        var palabra=$('#buscar_elemento').val();
        var opciones='';
        var elements_ids = $('#elements_ids').val();
        var categoria_id = $('#elemento_id').val();
        var idproduccion= $(this).attr('idproduccion');
        var rol ="";
        if(palabra){
          datos= {palabra: palabra, idproduccion:idproduccion, elements_ids:elements_ids, categoria_id:categoria_id}
          $.ajax({
            type: "POST",
            url: site_url+"/escenas/buscar_elemento_palabra",
            data: datos,
            dataType: "json",
            success:function(data){
              if(data.elementos){
                $.each(data.elementos, function(i,elementos){
                  if(elementos.rol_elemento!=null){
                    rol = ' ( '+elementos.rol_elemento+' ) ';
                  }

                  opciones=opciones+'<tr class="sort_left">';
                  opciones=opciones+'<td width="30%"><input type="hidden" value='+elementos.id_elemento_es+' id="id_elemento_escenas"><input type="hidden" value="'+elementos.tipo+'" id="tipo"><input type="hidden" value="'+elementos.nombre+'"" id="nombre"><input type="hidden" value="'+rol+'" id="rol_elemento"><span>'+elementos.tipo+'</span></td>';
                  opciones=opciones+'<td width="50%">'+elementos.nombre;
                  opciones+= rol;
                  opciones=opciones+'</td> ';
                  opciones+='<td width="20%" style="text-align:center;"><a class="eliminar_elemento" escenanombre="'+elementos.nombre+'">'+eliminar+'</a> <a class="agregar_elemento" >'+agregar+'</a></td></tr>';
                });
              } else{
                opciones=opciones+"<tr class='ui-drag-disabled'> class='sort_left'><td>"+no_hay_elementos_para_la_busqueda+"</td></tr>";
              }
              $("#tabla1").html(opciones);
            }
          })
        }else{
        $('#elemento_id').change();
        }
        $('#loadElements').fadeOut();
      }
   });
   /*FIN FUNCION PARA BUSCAR ELEMENTOS POR NOMBRE*/



  /*AGREGAR ELEMENTO A TABLA 2 DE ELEMENTOS ESCENA POR MEDIO DE BOTON*/
  $('#tabla1').on('click', '.agregar_elemento', function(){
    $('#wrapper_loader').fadeIn();
    var pathname = window.location.pathname;
    $(this).parent().parent('.sort_left').remove();
    $('tr.ui-drag-disabled').hide();
    var id_elemento= $(this).parent().parent().find('#id_elemento_escenas').val();
    var tipo= $(this).parent().parent().find('#tipo').val();
    var nombre= $(this).parent().parent().find('#nombre').val();
    var rol = $(this).parent().parent().find('#rol_elemento').val();
    var opciones= '';
    var opciones=opciones+'<tr class="sort_left">';
    opciones=opciones+'<td width="30%"><input type="checkbox" name="elemento['+id_elemento+']" value='+id_elemento+' style="display:none;" checked><input type="hidden" value="'+rol+'" id="rol_elemento"><input type="hidden" value='+id_elemento+' id="id_elemento_escenas"><input type="hidden" value="'+tipo+'" id="tipo"><input type="hidden" value="'+nombre+'" id="nombre"><span>'+tipo+'</span></td>';
    opciones=opciones+'<td width="50%">'+nombre+' '+rol;
        if(rol.indexOf('( Extra )') >0 && pathname.search("escena")>0 ){ 
          opciones+='<input type="text" onkeypress="return soloLetras(event)" class="extras_field" name="hidden'+id_elemento+'" value="1">';
        }
         
        /*if(tipo.search('background')>0 && pathname.search("escena")>0 ){ 
          opciones+='<input type="text" onkeypress="return soloLetras(event)" class="vehiBack_field" name="hidden'+id_elemento+'" value="1">';
        }*/
        
       /* if(tipo.search('Vehiculo')>0 && pathname.search("escena")>0 ){ 
          console.log(tipo)
          opciones+='<input type="text" onkeypress="return soloLetras(event)" class="vehiBack_field" name="hidden'+id_elemento+'vehiculo" value="1">';
        }*/
       
        if(tipo=='Vehiculo' || tipo=='Vehiculos background'){
          opciones+='<input type="text" onkeypress="return soloLetras(event)" class="vehiBack_field" ';
          if(tipo=='Vehiculo'){
            opciones+='name="hidden';
          }else{
            opciones+='name="hiddenBack';
          }
          opciones+=id_elemento+'vehiculo" value="1">';
        }

        opciones+='</td>';
        opciones=opciones+'<td width="20%" style="text-align:center;">';
        if(pathname.search("plan_general/")>0){
          opciones+='<div class="filter_action"><input type="checkbox" class="sin_checkbox left" escenaid="'+id_elemento+'" escenanombre="'+nombre+'" ><label class="left">Sin&nbsp&nbsp</label></div>';
          $('#con_hidden').val($('#con_hidden').val()+id_elemento+',');
        }
        opciones+='<a  escenanombre="'+nombre+'"  class="eliminar_elemento">'+eliminar+'</a> <a class="agregar_elemento" >'+agregar+'</a></td>';
        opciones=opciones+'</tr>';
    $('#elements_ids').val($('#elements_ids').val()+id_elemento+',');
    $("#tabla2").append(opciones);
    $('#wrapper_loader').fadeOut();
  });
  /*FIN AGREGAR ELEMENTO A TABLA 2 DE ELEMENTOS ESCENA POR MEDIO DE BOTON*/



  /*SELECCIONAR ELEMENTO SIN EN FILTRO DE PLAN GENERAL*/
  $('#tabla2').on('click', '.sin_checkbox', function(){
    var escenanombre = $(this).attr('escenanombre');
    var id = $(this).attr('escenaid');
    if($(this).is(':checked')) {
      $('#con_hidden').val($('#con_hidden').val().replace(id + ',',""));
      $('#sin_hidden').val($('#sin_hidden').val()+id + ',');
    }else{  
       $('#con_hidden').val($('#con_hidden').val()+id + ',');
       $('#sin_hidden').val($('#sin_hidden').val().replace(id + ',',""));
    }
  });
  /*FIN SELECCIONAR ELEMENTO SIN EN FILTRO DE PLAN GENERAL*/

  /*VALIDACION PARA QUE AL MENOS EXIST UN EXTRA ASIGNADO A LA ESCENA*/
  $('#tabla2').on('keyup', '.extras_field', function(){
    var value = parseInt($(this).val());
    if(value<1){
      $(this).val("1");
    }
  }); 
  /*FIN VALIDACION PARA QUE AL MENOS EXIST UN EXTRA ASIGNADO A LA ESCENA*/
/*aquiaqui*/
  /*FUNCION PARA ELIMINAR ELEMENTO DE TABLA DE ELEMENTOS DE ESCENA CON BOTON*/
  $('#tabla2').on('click', '.eliminar_elemento', function(){
    var pathname = window.location.pathname;
    if(pathname.search("plan_general/")>0){
      var escenanombre = $(this).attr('escenanombre') + ',';
      var text = $('#con_hidden').val();
      $('#sin_hidden').val(text.replace(escenanombre,''));
      $('#con_hidden').val(text.replace(escenanombre,''));
    }
    $(this).parent().parent().find('.elemento_escena').remove();
    $(this).parent().parent().find('.extras_field').remove();
    $(this).parent().parent().find('.vehiBack_field').remove();
    $('#tabla1').append('<tr class="sort_left">'+$(this).parent().parent('.sort_left').html()+'</tr>');
    var id_elemento = $(this).parent().parent().find('#id_elemento_escenas').val();
    $('#elements_ids').val($('#elements_ids').val().replace(id_elemento + ',',""));
    $(this).parent().parent('.sort_left').remove();
  });
  /*FIN FUNCION PARA ELIMINAR ELEMENTO DE TABLA DE ELEMENTOS DE ESCENA CON BOTON*/

  /*VALIDACIONES PARA MOSTRAR CAMPO DE "PRODUCIDA" CREACION Y EDICION DE ESCENAS */
  $('.location_tipo').click(function(){
    var tipo= $(this).val();
    if(tipo==3){
      if($(this).is(':checked')) {  
        $('.escena_producida').fadeIn();
      } else {  
        $('#escena_producida').val('');
        $('.escena_producida').fadeOut();
      }
    } 

    if(tipo==1 || tipo==2){
      $('#toma').fadeIn();
    }else{
      $('#toma').fadeOut();
    }  
  });

  $('.flashback').click(function(){
    var tipo= $(this).val();
    if(tipo==1 || tipo==3 || tipo==2){
      $('.escena_producida').fadeIn();
    }else{
      $('#escena_producida').val('');
      $('.escena_producida').fadeOut();
    }  
  });
  /*VALIDACIONES PARA MOSTRAR CAMPO DE "PRODUCIDA" CREACION Y EDICION DE ESCENAS */

  /*ELIMINAR CLASE DE ERROR AL SECCIONAR UNA IMGEN CREAR PRODUCCION*/
  $('#image_production').click(function(){
    $('#image_production').removeClass('error');
    $('#image_production').parent('div').find('label.error2').css('display','none');
      return true;
  });
  /*FIN ELIMINAR CLASE DE ERROR AL SECCIONAR UNA IMGEN CREAR PRODUCCION*/

  /*CARGAR EL NUMERO DE ESCENA SEGUN EL LIBRETO */
  $('#cap').change(function(){
    var id_capitulo=$('#cap').val();
    if(id_capitulo) {
       datos= {id_capitulo: id_capitulo}
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/numero_escena",
          data: datos,
          dataType: "json",
        success:function(data){
          $('#numero_escena').val(data.numero_escena);
        }
       }); 
      }  
  });
  /*FIN CARGAR EL NUMERO DE ESCENA SEGUN EL LIBRETO */

  $('input.categoria').click(function(){
    $(this).addClass('active');
    var id_categoria=$(this).attr('id');
    $('.categoriatext').fadeOut();
    $('.btnGuardar').fadeOut();
    $('#categoria'+id_categoria).fadeIn();
  });

  $('.icon_categories').click(function(){
     $('.categorias_elemento').slideToggle();
     $('.crear_elemento').slideUp(); 
     return false;
  });
  $('.new_elemento').click(function(){
    $('.categorias_elemento').slideUp(); 
    $('.crear_elemento').slideToggle(); 
    return false;
  });

  $('.new_locacion_set').click(function(){
    $('.crear_locacion_set').slideToggle(); 
    return false;
  });

  /*FUNCION ELIMINAR CATEGORIA*/
  $(".eliminarCAtegoria").click(function(){
    if(confirm(quiere_eliminar_esta_categoria)) {
      var id_categoria=$(this).attr('id');
      var id_produccion=$('.id_produccion').val();
      datos= {id_categoria: id_categoria}
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/eliminar_categoria",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data.eliminar==1){
            window.location.href=site_url+'/elementos/index/'+id_produccion;
          }else{
            alert(esta_categoria_no_se_puede_eliminar_ya_que);
          }
        }
      }); 
    }
  });
  /*FUNCION ELIMINAR CATEGORIA*/

  /*FUNCION CAMBIAR ESTADO A PRODUCCION*/
  $("#produccion_estado").click(function(){
    if(confirm(quieres_cambiar_el_estado_de_esta_produccion)) {
      var id_produccion=$('#id_produccion_estado').val();
      datos= {id_produccion: id_produccion}
      $.ajax({
        type: "POST",
        url: site_url+"/produccion/cambiar_estado",
        data: datos,
          dataType: "json",
          success:function(data){
              window.location.href=site_url+'/plan_produccion/index/'+id_produccion;
          }
      }); 
    }
  });
  /*FIN FUNCION CAMBIAR ESTADO A PRODUCCION*/

  /*FUNCIONES MUESTRA CAMPO ROL SI ES PERSONAJE*/
  $('.id_elemento').change(function(){
      var id_elemento=$('.id_elemento').val();
      var clase='elemento_'+id_elemento;
      var elemento=$('.'+clase).attr('id');
      if(elemento!='Personaje'){
         $('.rol_personaje').fadeOut(); 
      }else{
        $('.rol_personaje').fadeIn();
      }
  });
 
  $('.id_elemento').change();

  $('#id_elemento2').change(function(){
      var id_elemento=$('#id_elemento2').val();
      var clase='elemento_'+id_elemento;
      var elemento = $("#id_elemento2 option:selected").html();
      if(elemento != "Personaje"){
        $('#rolPersonajes').fadeOut(); 
      }else{
        $('#rolPersonajes').fadeIn(500);
      }
  });
  /*FIN FUNCIONES MUESTRA CAMPO ROL SI ES PERSONAJE*/

  /*FUNCION MUESTRA SECCION EDICION ELEMENTO*/
  $('#usersTable').on('click', '.editar_elemento',function(){
    var elemento=$(this).attr('id');
    var elemento='form_elemento_'+elemento;
    $('.'+elemento).slideToggle();
  });
  /*FIN FUNCION MUESTRA SECCION EDICION ELEMENTO*/

  /*FUNCION OCULTA SECCION EDICION ELEMENTO*/
  $('#usersTable').on('click','.cancel_edit_element',function(){
    var elemento=$(this).attr('id');
    var elemento='form_elemento_'+elemento;
    $('.'+elemento).slideToggle();
  });
  /*FIN FUNCION OCULTA SECCION EDICION ELEMENTO*/

  /*FUNCION ELIMINAR ELEMENTO*/
  $('#usersTable').on('click','.eliminar_elemento',function(){
    var idelemento=$(this).attr('id');
    if(confirm(esta_seguro_de_que_desea_eliminar_este_elemento)){
      datos= {idelemento: idelemento}
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/eliminar_elemento",
        data: datos,
          dataType: "json",
          success:function(data){
            $('.element_'+idelemento).remove();
          }
      }); 
    }
  });
  /*FIN FUNCION ELIMINAR ELEMENTO*/

  /*FUNCION ACTUALIZAR LIBRETOS A ESCRIBIR*/
  $('.cap_pro_sem').click(function(){
   var id=$(this).attr('id')
   var id_semana=$(".cap_semana_"+id).val();
   var valor=$(".val_cap_sema_"+id).val();
   if(valor==""){
    valor=0;
   }
   var tipo=$('.tipo_cap_sema_'+id).val();
     datos= {id_semana: id_semana,valor:valor,tipo:tipo}
      $.ajax({
        type: "POST",
        url: site_url+"/plan_produccion/update_escritura_cap",
        data: datos,
          dataType: "json",
          success:function(data){
            $('.hide_box').stop().fadeOut();
            $('.table_general table table.secondary tr td').css({'background':'transparent','font-weight':'normal'});
            $('.open_box').css({'color':'#0098d2'});
            document.getElementById('valor_'+id_semana).innerHTML = valor;
          }
      }); 
  });
  /*FIN FUNCION ACTUALIZAR LIBRETOS A ESCRIBIR*/

});

$(document).ready(function() {
  $( "#start_plan" ).datepicker( "option", "maxDate", $('#recording_end').val());
  $( "#start_plan" ).datepicker( "option", "minDate", $('#min_date').val());
  $( "#end_plan" ).datepicker( "option", "maxDate", $('#recording_end').val());
  var pathname = window.location.pathname;
  if(pathname.search("plan_produccion/index")>0){
    $( ".start_unity").datepicker( "option", "minDate", new Date(document.getElementById('record_begin').innerHTML));
    $("#end_recording").datepicker("option", "minDate", new Date($('#record_begin').html()));
    $('#date_online').datepicker("option", "minDate", new Date($('#record_begin').html()));
    $('#tipo_user').change();
  }
});

function edit(idescena,idproduccion, idcapitulo){
    window.location.href = site_url+'/escenas/editar_escena/'+idescena+'/'+idproduccion+'/'+idcapitulo ;
}

  /*FUNCION PARA VALIDAR LA EXTENSION DE LA IMAGEN (CREAR PRODUCCION)*/

  function validation_2(){
      datos= {name_production: $('#name_production').val()}
      $.ajax({
        type: "POST",
        url: site_url+"/produccion/validate_production_name",
        data: datos,
          dataType: "json",
          success:function(data){
            if(data.response=="1"){
              addclass_error();
              $('#name_production').addClass('error');
              $('#validation_name').val('2');
            }else{
              $('#validation_name').val('')
            }
          }
      }); 
  }

  $(".full").click(function(){
    $('#start_pre').focus();
  });

  function comprueba_extension() { 
    $("#inner_content").focus();
    extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".bmp"); 
    var pathname = window.location.pathname;
    var archivo = document.getElementById('image_production').value;
    var response = false;
    if (archivo!=""){
      extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
      permitida = false; 
      for (var i = 0; i < extensiones_permitidas.length; i++) { 
        if (extensiones_permitidas[i] == extension) { 
          permitida = true; 
          break; 
        } 
      }
      if (!permitida) {
        $('#image_production').focus();  
        $('#image_production').addClass('error');  
        $('#image_production').parent('div').append('<label class="error2">'+el_formato_del_archivo_no_es_valido+'</label>');
        $('#image_production').parent('div').find('label.error2').css('color','#c60f13');
        response = false;
      }else{
        $('#image_production').parent('div').find('label.error2').css('display','none');
        response = true;
      }
    }else{
      response = true;
    }
    

    if(response){
      if($('#validation_name').val()==""){
        response = true;
      }else{
        $('#name_production').addClass('error');
        response = false;
      }
    }

    if(response){
      if(parseInt($('#segundos_capitulo').val())>59){
        response = false;
        $('#segundos_capitulo').removeClass('error');
        $('#segundos_capitulo').parent('div').find('.error2').remove();
        $('#segundos_capitulo').addClass('error');
        $('#segundos_capitulo').parent('div').append('<label class="error2">'+el_valor_debe_ser_menor_a+'</label>');
        $('#segundos_capitulo').parent('div').find('label.error2').css('color','#c60f13');
      }else{
        resṕonse = true;
        $('#segundos_capitulo').removeClass('error');
        $('#segundos_capitulo').parent('div').find('.error2').remove();
      }
    }

    if(response){
      if(parseInt($('#seg_proy_sem').val())>59){
        response = false;
        $('#seg_proy_sem').removeClass('error');
        $('#seg_proy_sem').parent('div').find('.error2').remove();
        $('#seg_proy_sem').addClass('error');
        $('#seg_proy_sem').parent('div').append('<label class="error2">'+el_valor_debe_ser_menor_a+'</label>');
        $('#seg_proy_sem').parent('div').find('label.error2').css('color','#c60f13');
      }else{
        resṕonse = true;
        $('#seg_proy_sem').removeClass('error');
        $('#seg_proy_sem').parent('div').find('.error2').remove();
      }
    }

    if(response){
        $(".maxnumber").each(function(){
            var valor = $(this).val();
            if(parseInt(valor)>999){
              response = false;
              $(this).addClass('error');
              $(this).parent('div').append('<label class="error2">'+el_valor_maximo_es+'</label>');
              $(this).parent('div').find('label.error2').css('color','#c60f13');
            }
        });
    }

    if(response && pathname.indexOf('produccion/index')>0){
      if($('#date_online').val()!=""){
        if($('#lunes').is(':checked') || $('#martes').is(':checked') || $('#miercoles').is(':checked')  || $('#jueves').is(':checked') || $('#viernes').is(':checked') || $('#sabado').is(':checked') || $('#domingo').is(':checked')){
          response = true;
        }else{
          $('#online_days').css({border: "1px solid #c60f13" });
          $('#online_days').append('<label class="error2">'+seleccione_al_menos_un_dia+'</label>');
          $('#online_days').find('label.error2').css('color','#c60f13');
          response = false;
        }
      }else{
        response = true;
      }
    }

    
    return response;

    
  }

  $('#online_days').click(function(){
    $('#online_days').css({border: "none" });
    $('#online_days').find('label.error2').remove();
  });

   /*FIN FUNCION PARA VALIDAR LA EXTENSION DE LA IMAGEN (CREAR PRODUCCION)*/

 
    /*FUNCION PARA VALIDAR SOLO CARACTERES NUMERICOS EN Y PUNTO*/
    function soloLetras(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = " 1234567890";
      especiales = [8, 37, 39, 46];

      tecla_especial = false
      for(var i in especiales) {
          if(key == especiales[i]) {
              tecla_especial = true;
              break;
          }
      }
      if(letras.indexOf(tecla) == -1 && !tecla_especial)
          return false;
    }
    /*FIN FUNCION PARA VALIDAR SOLO CARACTERES NUMERICOS EN Y PUNTO*/

    /*FUNCION PARA VALIDAR SOLO CARACTERES NUMERICOS EN*/
    function onlyNumbers(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = " 1234567890";
      especiales = [8, 37, 39];

      tecla_especial = false
      for(var i in especiales) {
          if(key == especiales[i]) {
              tecla_especial = true;
              break;
          }
      }

      if(letras.indexOf(tecla) == -1 && !tecla_especial)
          return false;
    }
    /*FIN FUNCION PARA VALIDAR SOLO CARACTERES NUMERICOS EN*/


  $(document).ready(function() {
     $('.table_general').on('click','.save_script',function(){
        var idcapitulo = $(this).attr('idcapitulo');
        var archivo = $('#libreto'+idcapitulo).val();
        extensiones_permitidas = new Array(".pdf", ".doc", ".docx"); 
        if (archivo!=""){
          extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
          permitida = false; 
          for (var i = 0; i < extensiones_permitidas.length; i++) { 
            if (extensiones_permitidas[i] == extension) { 
              permitida = true; 
              break; 
            } 
          }
          if (!permitida) {
            $('#libreto'+idcapitulo).focus();  
            $('#libreto'+idcapitulo).addClass('error');  
            $('#libreto'+idcapitulo).parent('div').append('<label class="error2">'+el_formato_del_archivo_no_es_valido+'</label>');
            $('#libreto'+idcapitulo).parent('div').find('label.error2').css('color','#c60f13');
            return false;
          }else{
            $('#libreto'+idcapitulo).parent('div').find('label.error2').css('display','none');
            $('#subirLibreto'+idcapitulo).submit();
           
          }
        }else{
          return false;
        }
});
  var contador_caps=1;
  var pathname = window.location.pathname;

    $('#load').click(function(){
      var idproduccion = $(this).attr('data-idprod');
      var limite_inf = parseInt($('#limit_inf').val());
      var limite_sup = $('#limit_sup').val();
      var limite_tot = $('#limit_tot').val();
      var row="";
      load_capitules(parseInt(limite_inf)+30,parseInt(limite_sup)+30,idproduccion,1);
      if(limite_sup>=limite_tot){
        $('#load').fadeOut();
      }
    });


  $('.capitule_state').click(function(){
      var idproduccion = $('#idproduccion').val();
      var limite_inf = 0;
      var limite_sup = $('#limit_sup').val();
      var limite_tot = $('#limit_tot').val();
      var row="";
      load_capitules(limite_inf,limite_sup,idproduccion,0);
  });

  $('.roles_personajes').click(function(){
   /* palabra = $(this).val();
    temporal = $(this);
    roles_filter(palabra,temporal);

    var cadena ="";

    $('.roles_personajes').each(function(){
      if(!$(this).is(':checked')){
        cadena += $(this).val()+',';
      }
    });

    var id_produccion = $('.id_produccion').val();
    var desde = $('#capitulos_herramientas_from').val();
    var hasta = $('#capitulos_herramientas_to').val();
    datos= {cadena: cadena,id_produccion:id_produccion, desde:desde, hasta:hasta}
    $('#wrapper_loader').fadeIn();
    $('#filtro_elementos').submit();
    /*$.ajax({
      type: "POST",
      url: site_url+"/elementos/contar_elementos_roles",
      data: datos,
      dataType: "json",
      success:function(data){
        if(data.cantidad){
          $('.escenasNoProducidas').html('Total Personaje:'+data.cantidad);
        }
      }
    });*/
  });

  function roles_filter(palabra,temporal){
   
    $("#usersTable .rol_name").each(function() {
      if($(this).html().toLowerCase().indexOf(palabra.toLowerCase(),0)>=0){
        if(!temporal.is(':checked')){
          $(this).parent().hide();
        }else{
          $(this).parent().show();
        }
      }
    });
  }

    // //if (e.keyCode == 13) {
  //  var palabra = $(this).val();
  
  //  $("#usersTable .element_name").each(function() {
  //   console.log($(this).html());
  //   console.log($(this).html().indexOf(palabra.toLowerCase()));
  //   console.log(palabra.toLowerCase());
  //     if($(this).html().toLowerCase().indexOf(palabra.toLowerCase(),0)<0){
  //       $(this).parent().hide();
  //     }else{
  //       $(this).parent().show();
  //     }
  //   });
  // //}

  $('#principal_filter').click(function(){
      var idproduccion = $('#idproduccion').val();
      var limite_inf = $('#from_select').val();
      var limite_sup = $('#to_select').val();
      var limite_tot = $('#limit_tot').val();
      if(limite_sup>limite_inf){
        load_capitules(limite_inf,limite_sup,idproduccion,3);
      }else{
        $('#from_select').addClass('error');
        $('#to_select').addClass('error');
      }
      if(limite_sup>=limite_tot){
        $('#load').fadeOut();
      } 
  });

  function load_capitules(limite_inf,limite_sup,idproduccion,val){
    $('#wrapper_loader').fadeIn();
    var estados="";
    var contador = 0;
      $(".capitule_state").each(function() {
        if(!$(this).is(':checked')){
          estados += $(this).attr('data-idestado')+',';
        }
      });

    datos= {limite_inf: limite_inf,limite_sup:limite_sup, idproduccion:idproduccion, estados:estados,val:val}
      $.ajax({
        type: "POST",
        url: site_url+"/libretos/load_capitules",
        data: datos,
          dataType: "json",
          success:function(data){
            if(data.cadena!=false && data.cadena!=null){
              $('#wrapper_loader').fadeOut();
              if(val=="0" || val=="3"){
                 $('#main').html(data.cadena+'<script type="text/javascript"> $("#side_menu ul li").click(function(){ $(this).find("ul.submenu").stop().slideToggle("slow"); $(this).parent().find("ul.submenu").not($(this).find("ul.submenu")).stop().slideUp("slow");return true;});</script>');  
              }else{
                $('#main').append(data.cadena+'<script type="text/javascript"> $("#side_menu ul li").click(function(){ $(this).find("ul.submenu").stop().slideToggle("slow"); $(this).parent().find("ul.submenu").not($(this).find("ul.submenu")).stop().slideUp("slow");return true;});</script>');
              }
              
              //$('#limit_sup').val(limite_sup);
              $.ajaxSetup({ cache: true });
                $.getScript(site_url2+"/js/app.js", function(){
                $.ajaxSetup({ cache: false });
              });
                if(data.total>30){
                  $('#load').fadeIn();
                  if(val=='0'){
                    $('#limit_inf').val(0);
                    $('#limit_sup').val(30);
                  }else{
                    $('#limit_inf').val(parseInt(data.limite_inf));
                    $('#limit_sup').val(parseInt(data.limite_sup));
                  }

                  if(val=="3"){
                    $('#limit_inf').val(0);
                    $('#limit_sup').val(30);
                    $('#load').fadeOut();
                  }
                
                  if(parseInt(data.total)<parseInt(data.limite_sup)){
                     $('#load').fadeOut();
                  }
                   
                }else{
                  $('#load').fadeOut();
                }
             /* $.ajaxSetup({ cache: true });
               $.getScript(site_url+"/js/script.js", function(){
                $.ajaxSetup({ cache: false });
              });*/
              
            }
          }
      });
  }
  });






/*FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/
$('#difference_capitules_hidden').ready(function(){
  $('#difference_capitules').text($('#difference_capitules_hidden').val());
  $('#difference_capitules').css("display", "inline");
  if($('#difference_capitules_hidden').attr('data-last-week')<0){
    $("#capitules_green_box").removeClass("green_box").addClass("red_box");
  }else{
    $("#capitules_green_box").removeClass("red_box").addClass("yellow_box");
  }

  if($('#difference_capitules_hidden').val()>=0){
    $("#capitules_green_box").removeClass("red_box").removeClass('yellow_box').addClass("green_box");
  }

  if($('#difference_capitules_hidden').val()===undefined || $('#difference_capitules_hidden').val()==""){
    $("#capitules_green_box").removeClass("red_box").removeClass('yellow_box').addClass("green_box");
  }
});
/* FIN FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/

/*FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/
$('#difference_minutes_hidden').ready(function(){
  var pathname = window.location.pathname;
  if(pathname.search("plan_produccion/")>0 ){

    if($('#difference_minutes_hidden').attr('data-last-week')!="" && $('#difference_minutes_hidden').attr('data-last-week')!==undefined){
        var temporal = $('#difference_minutes_hidden').attr('data-last-week').split(':');
        $('#difference_minutes').text($('#difference_minutes_hidden').val());
        $('#difference_minutes').css("display", "inline");
      if(temporal[0]<0){
        $("#minutes_green_box").removeClass("green_box").addClass("red_box");
      }else{
        $("#minutes_green_box").removeClass("red_box").addClass("yellow_box");
      }
    }else{
    }

    if($('#difference_minutes_hidden').val()!="" && $('#difference_minutes_hidden').val()!==undefined){
      var temporal = $('#difference_minutes_hidden').val().split(':');
      $('#difference_minutes').text($('#difference_minutes_hidden').val());
      $('#difference_minutes').css("display", "inline");
      if(temporal[0]>0){
        $("#minutes_green_box").removeClass("red_box").addClass("green_box");
      }
    }else{
      $("#minutes_green_box").removeClass("red_box").addClass("green_box");
    }
  }
});
/* FIN FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/



/*FUNCION PARA CARGAR AUTOMATICAMENTE PERSONAJES EN CREAR ESCENA*/
$(document).ready(function() {
  var pathname = window.location.pathname;
  if(pathname.search("escenas/crear_escenas")>0  ){
    $('#elemento_id').change();
    $('#cap').change(); 
  }
   if(pathname.search("escenas/editar_escena")>0 ){
    $('#elemento_id').change();
   }
});
/*FIN FUNCION PARA CARGAR AUTOMATICAMENTE PERSONAJES EN CREAR ESCENA*/

$(document).ready(function() {
    $('.no_scene').click(function(){
      alert(aun_no_hay_ingresado_el_numero_de_escenas_para_el_libreto + $(this).attr('data-numero'));
    });
    /*SELECCIONAR EL PRIMER ITEM DEL FORMULARIO*/
    $('form:first *:input[type!=hidden]input[type!=seelect]:first').focus();
    /*FIN SELECCIONAR EL PRIMER ITEM DEL FORMULARIO*/

    /*OCULTAR EL CAMPO DE ERROR AL PRESIONAR TECLA*/
    $('#duration_minutes').keypress(function(){
      $('label.error2').fadeOut();
    });

    $('#duration_seconds').keypress(function(){
      $('label.error2').fadeOut();
    });
    /*FIN OCULTAR EL CAMPO DE ERROR AL PRESIONAR TECLA*/

    /*VALIDAR SI SE ELIGE CONTINUAR EN ESCENAS*/
    $('#continue_button').click(function(){
      $("#tabla2 input.extras_field").each(function() {
        var campo = $(this);
        if($(this).val()=="") {
          $(this).val('1');
        }
      });
      $('#validator_field').val("1");
      $('#wrapper_loader').fadeOut();
    });
    /*FIN VALIDAR SI SE ELIGE CONTINUAR EN ESCENAS*/


    $('#elemento_id').change();

    var contador_escenas=0;
    var counterSelected=0;
    var estado = true;
    /* FUNCION PARA SELECCIONAR CELDAS TABLA 1 */
    $('#table_general').on("click",'tr.actionAsing', function(){
      $(this).toggleClass('ui-selected');
      var itemsSelected = $('#table_general tr.ui-selected').size();
      if (itemsSelected == 0) {
        ocultarBoton();
      }else{
        mostrarBoton();
      };
    });

    /* FUNCION PARA SELECCIONAR MULTIPLES CELDAS de la tabla 1 */
    $( "#table_general tbody" ).selectable({
      distance: 1,
      selected: mostrarBoton
    });

    $( "#table_asign tbody" ).selectable({
      distance: 1,
      selected: mostrarBoton2
    });

    function mostrarBoton(){
      $('.asignarEscenasSeleccinadas').fadeIn();
      var itemsSelected = $('#table_general tr.ui-selected').size();
      if(itemsSelected==0){
         ocultarBoton();
      }
    }

    $('.borrarSeleccionadas').click( function(){
      if(confirm(esta_seguro_de_que_desea_eliminar_estas_escenas)){
        $("#planDiarioTableSorter tr.ui-selected").each(function() {
          if($(this).attr('class').search('td_yellow')<0) {
            var idescena = $(this).attr('data-idescena');
            var idplan= $("#idplan").val();
            var idproduccion = $("#id_produccion_estado").val();
            var idunidad = $("#id_unidad_actual").val();
            $(this).remove();
            var datos={idescena:idescena,idplan:idplan,idproduccion:idproduccion,idunidad:idunidad}
            $.ajax({
              type: "POST",
              url: site_url+"/plan_diario/eliminar_escena_plan",
              data: datos,
                dataType: "json",
                success:function(data){
                  
                }
            });
          }
        });
        alert(escenas_eliminadas_exitosamente);
        //window.location.reload();
      }
    });
    function ocultarBoton(){
      $('.asignarEscenasSeleccinadas').stop().fadeOut();
    }
    function mostrarBoton2(){
      $('#desasignarEscenasSeleccinadas').fadeIn();
      var itemsSelected2 = $('#table_asign tr.ui-selected').size();
      if(itemsSelected2==0){
         ocultarBoton();
      }
    }
    function ocultarBoton2(){
      $('#desasignarEscenasSeleccinadas').stop().fadeOut();
    }

    $('.asignarEscenasSeleccinadas').click(function(){
        $("#table_general tr.ui-selected").each(function() {
            var idescena = $(this);
            if($(this).attr('class').search('oldPlan')<0) {
              add_row(idescena.attr("data-idescena"));
            }
        });
        ocultarBoton();
        time_acumulated();
    });



    $('#desasignarEscenasSeleccinadas').click(function(){
      $("#table_asign tr.ui-selected").each(function() {
            var idescena = $(this);
            remove_row(idescena.attr("data-idescena"));
        });
      ocultarBoton();
      time_acumulated();
    });

    $('#cancel_asing').click(function(){
      $("tr.actionDesasing").each(function() {
          var idescena = $(this);
          remove_row(idescena.attr("data-idescena"));
      });
    });

    function time_acumulated(){
      var valor = $('#idescenas').val();
      var datos={idescenas:valor}
      $.ajax({
        type: "POST",
        url: site_url+"/escenas/suma_tiempos_escenas",
        data: datos,
          dataType: "json",
          success:function(data){
            $('#time_accumulated').html(data.tiempo);
          }
      });
    }

    $('#asing_button').click(function(){
      $('#idescenas').val("");
      $("td.actionDesasing").each(function() {
            var idescena = $(this);
            $('#idescenas').val(idescena.attr("data-idescena")+',');
      });
    });
  
    /* FUNCION PARA SELECCIONAR CELDAS TABLA 2 */
    $('#table_asign').on("click",'tr.actionDesasing', function(){
      $(this).toggleClass('ui-selected');
      var itemsSelected2 = $('#table_asign tr.ui-selected').size();
      if (itemsSelected2 == 0) {
        ocultarBoton2();
      }else{
        mostrarBoton2();
      };
    });
    /* FUNCION PARA SELECCIONAR MULTIPLES CELDAS de la tabla 2 */
    $( "#table_asign tbody" ).selectable({
      distance: 1
    });

    /* FUNCION PARA CARGAR  A LA TABLA 2 EN PLAN GENERAL */
    $('#table_general').on("dblclick",'tr.actionAsing', function(){
      var idescena = $(this).data('idescena');
      add_row(idescena);
      time_acumulated();
    });


    function add_row(idescena){
      $('#table_general').trigger('update');
      $('#table_asign').fadeIn();
      $('#table_asign').append('<tr class="actionDesasing" id="row2_'+idescena+'" idescena="'+idescena+'" data-idescena="'+idescena+'" data-plan="'+$('#row_'+idescena).attr('data-plan')+'" data-unidad="'+$('#row_'+idescena).attr('data-unidad')+'" data-libreto="'+$('#row_'+idescena).attr('data-libreto')+'" data-numero="'+$('#row_'+idescena).attr('data-numero')+'">'+$('#row_'+idescena).html().replace(/asing_tabla2/, "asing_tabla1").replace(/row_/,'row_2')+'</tr>');
      $('#idescenas').val($('#idescenas').val()+idescena+',');
      /*ACUMULADO DE ESCENAS*/
      var valor = $('#idescenas').val();
      if(valor!=""){
        $('#asing_container').fadeIn();
        ++contador_escenas;
        $('#selected_escenes').html(contador_escenas);
      }
      
      /*FIN ACUMULADO ESCENAS*/
      $('#row_'+idescena).remove();
    }
     /* FIN FUNCION PARA CARGAR  A LA TABLA 2 EN PLAN GENERAL*/

    /* FUNCION PARA CARGAR  A LA TABLA 1 EN PLAN GENERAL */
    $('#table_asign').on("dblclick",'tr.actionDesasing', function(){
      var idescena = $(this).data('idescena');
      remove_row(idescena);
      time_acumulated();
      if(contador_escenas<=0){
        ocultarBoton(); 
      }
    });

    function remove_row(idescena){
      $('#table_general').trigger('update');
      $('.asing_tabla2').attr('checked', false);
      $('#table_general').append('<tr class="actionAsing" id="row_'+idescena+'" idescena="'+idescena+'" data-idescena="'+idescena+'">'+$('#row2_'+idescena).html().replace(/asing_tabla1/, "asing_tabla2").replace(/row_2/,'row_')+'</tr>');
      $('#row2_'+idescena).remove();
      $('#idescenas').val($('#idescenas').val().replace(idescena+',',''));
      /*ACUMULADO DE ESCENAS*/
      --contador_escenas;
      $('#selected_escenes').html(contador_escenas);
      ocultarBoton2();
      if($('#idescenas').val()==""){
        $('#asing_container').fadeOut();
      }
      /*FIN ACUMULADO ESCENAS*/
    }
     /* FIN FUNCION PARA CARGAR  A LA TABLA 1 EN PLAN GENERAL*/

/////crear categoria elemento//////////
 $('#crear_categoria').click(function(){
    var required = $('#categoria_nombre').val();
    if(required == ''){
       $('#categoria_nombre').addClass('error');
       html1 = '<label class="error label-error-hiden">'+campo_requerido+'.</label>';
       $('#categoria_nombre').html(html1);
    }
    return false;
 });
/////fin crear categoria elementos/////

 $('#enviar_form_mover_plan').click(function(){
     var fecha=$('#fecha_unidad_mover').val();
    if(!fecha){
      $('#wrapper_loader').hide();
      $('#fecha_unidad_mover').parent('div').append('<label class="error" style="color:red!important;">'+debe_ingresar_fecha+'</label>');
      return false;
    }else{
       var unidad=$('#unidad_plan_mover option:selected').val();
       if(!unidad){
           $('#wrapper_loader').hide();
          $('#unidad_plan_mover').parent('div').append('<label class="error error_unidad" style="color:red!important;">'+debe_seleccionar_una_unidad+'</label>');
          return false;
       }else{
           $('#form_mover_plan').submit();
       }
    }
 })

 $('#unidad_plan_mover').change(function(){
     $('.error_unidad').remove();
 })

});


/*FUNCION PARA VALIDAR LOS MINUTOS Y SEGUNDOS ESTIMADOS EN LA ESCENA SOLO 1 CAMPO PUEDE SER 00 O VACIO*/
function validation_duration(){
  var pathname = window.location.pathname;
  if(confirm(esta_seguro_que_quiere_guardar_la_escena)){
        var duration_minutes = $('#duration_minutes').val();
        var duration_seconds = $('#duration_seconds').val();
        var retorno =true;
        $('label.error2').css('display','none');
        if((duration_minutes=="" || duration_minutes=="00" || duration_minutes=="0")  && (duration_seconds=="" || duration_seconds=="00" || duration_seconds=="0")
          || (duration_seconds=="" && duration_minutes=="00" && duration_minutes=="0") || (duration_minutes=="" && duration_seconds=="00" && duration_seconds=="00")){
          $('#duration_minutes').addClass('error'); 
          $('#duration_seconds').addClass('error'); 
          $('#duration_minutes').parent('div').append('<label class="error2">'+debe_ingresar_los_minutos_o_segundos+'</label>');
          $('#duration_minutes').parent('div').find('label.error2').css('color','#c60f13');
          retorno= false;
        }else{
          if(duration_seconds<60){
            if(duration_minutes==""){
              $('#duration_minutes').val('00'); 
            }
            if(duration_seconds==""){
              $('#duration_seconds').val('00'); 
            }
            retorno= true;
            $('#wrapper_loader').fadeIn();
          }else{
            $('#duration_seconds').addClass('error'); 
            $('#duration_seconds').parent('div').append('<label class="error2">'+los_minutos_deben_ser_menores_a+'</label>');
            $('#duration_seconds').parent('div').find('label.error2').css('color','#c60f13');
            retorno= false;
          } 
            
        };

        var tempo = $('#numero_escena').parent();
          if(retorno && pathname.indexOf('editar_escena')<0){
          idcapitulo = $('#cap').val();
          numero_escena =  $('#numero_escena').val();
            datos2= {idcapitulo: idcapitulo,numero_escena:numero_escena}
              $.ajax({
                type: "POST",
                url: site_url+"/escenas/validacion_numero",
                data: datos2,
                async:false,
                dataType: "json",
                success:function(data){
                  if(!data.existe){
                    tempo.append('<label class="error2" style="font-size: 11px; text-transform: inherit; margin-bottom: 10px; font-weight: normal; clear: both;color: #c60f13;">'+ya_existe_una_escena_con_este_numero+'</label>');
                    $('#numero_escena').focus();
                    retorno = false;
                  }else{
                    retorno = true;
                    $('#wrapper_loader').fadeIn();
                    tempo.find('.error2').remove();
                  }
                }
              }); 
         }
       
        
    }else{
      retorno= false;
    } 
     
     var form = $( "#crear_escena" );
    if(form.valid()==false){
      $('#wrapper_loader').fadeOut();
    }
  return retorno;
}
/*FIN FUNCION PARA VALIDAR LOS MINUTOS Y SEGUNDOS ESTIMADOS EN LA ESCENA SOLO 1 CAMPO PUEDE SER 00 O VACIO*/

function validation_fecha(){
  var fecha=$('#fecha_unidad_plan').val();
  if(!fecha){
    $('#wrapper_loader').hide();
    $('#fecha_unidad_plan').parent('div').append('<label class="error" style="color:red!important;">'+debe_ingresar_fecha+'</label>');
    return false;
  }
}

/*VALIDACAION DE FECHAS PLAN GENERAL*/
var validation = 0 ;
$(document).ready(function() {

  var pathname = window.location.pathname;

  if(pathname.search("plan_general/index")>0 || pathname.search("plan_general/filtro")>0){
    $( "#start_plan" ).datepicker( "option", "maxDate", $('#recording_end').val());
    $( "#end_plan" ).datepicker( "option", "maxDate", $('#recording_end').val());

    if($('#unidad_selector').val()==""){
        $('#insert_asing_button').hide();
        $("#unidad_selector").parent().remove('.error');    
    }

    $('#wrapper').append("<style> .tooltip.tip-centered-top{ background-color: #adadad!important;}.tooltip.tip-centered-top>.nub{border-top-color:#adadad!important;}</style>");

    $('#unidad_selector').change(function(){
      $('label.error').remove();
      var minDate = $("#date_unity" + $(this).val()).html();
      var html2 = '<label class="error label-error-hiden">'+esta_unidad_no_tiene_fecha_asignada+'</label>';
      if(minDate!=""){
        $('#insert_asing_button').fadeIn();
        $( "#start_plan" ).datepicker( "option", "minDate", minDate);
        $('#unidad_selector').removeClass('error');
        $('label.error').remove(); 
      }else{
        $('label.error').remove();
        $('#insert_asing_button').fadeOut();
          $('#unidad_selector').addClass('error');
          $('#unidad_selector').parent().append(html2);    
      }
      $('#start_plan').val('');
    });


    $('.unselected').click(function(){  
      var checked = $(this).attr('checked');
      if(checked){ 
        $(this).attr('checked', false);
      }else{ 
        $(this).attr('checked', true);
      }
    });
  }


    var temporal="";
    /*FUNCION PARA ASIGNAR ESCENAS A UN PLAN DIARIO*/
    $('#insert_asing_button').click(function(){
      var unidad_selected = "";
      if(pathname.search("plan_diario/")>0){
        $('#start_plan').val($('#start_plan_2').val());
        unidad_selected = $("#unidad_plan option:selected" ).text();
        
      }
      validacion = true;
      if(pathname.search("plan_general/")>0){
        unidad_selected = $( "#unidad_selector option:selected" ).text();
        if( (Date.parse($('#start_plan').val())/ 1000) < (Date.parse($('#date_unity'+$('#unidad_selector').val()).html())/ 1000) ){
          var html = '<label class="error label-error-hiden">'+fecha_invalida+'</label>';
          $('#start_plan').addClass('error');
          $('#start_plan').parent().append(html);
          validacion = false;
        }
      }



      if(validacion){
        if($('#start_plan').val()!="" && $('#unidad_selector').val()!=""){
          $('#wrapper_loader').fadeIn();
          var idescenas = $('#idescenas').val();
          var ids = idescenas.split(',');
          var comparacion="";
          var confirmacion="";
          var estado=false;
          var datos2={idunidad:$('#unidad_selector').val(),fecha:$('#start_plan').val()};
          var valida=0;
          $.ajax({
            type: "POST",
            async:false,
            url: site_url+"/plan_general/buscar_estado_plan",
            data: datos2,
            dataType: "json",
              success:function(data){
                estado = data.estado;
              }
          });

          if(estado){
             $.each(ids, function(j,ids){             
                if(ids!="" && ids){ 
                if($('#row2_'+ids).attr('data-plan') != $('#start_plan').val() || 
                $('#row2_'+ids).attr('data-unidad') != $('#unidad_selector').val()){
                  valida++;
                  var datos={idescena:ids,idunidad:$('#unidad_selector').val(),fecha:$('#start_plan').val()};
                  $.ajax({
                  type: "POST",
                  async:false,
                  url: site_url+"/plan_general/buscar_asignadas",
                  data: datos,
                  dataType: "json",
                    success:function(data){
                      if(comparacion.search(ids+',')<0){
                        if(data.resultado){
                          $.each(data.plan, function(i,plan2){
                            if(plan2.id_escena ==ids ){
                              console.log(plan2);
                              confirmacion += la_escena+plan2.numero_capitulo+'/'+ plan2.numero_escena + que_se_a_asignado_al_plan_del_dia +plan2.fecha_inicio_f+' U'+ plan2.numero_unidad +', ';
                              comparacion += plan2.id_escena + ',';
                              if($('#idplanes').val().indexOf(plan2.id_plan_diario+',')<0){
                                $('#idplanes').val($('#idplanes').val()+plan2.id_plan_diario+',');
                              }
                            }
                          });
                        }else{
                          if(pathname.search("plan_diario/")>=0){
                            confirmacion += la_escena + $( "#capitulos option:selected" ).text() + '/' + $( "#escenas option:selected" ).text()+', ';
                          }else{
                            confirmacion += la_escena + $('#row2_'+ids).attr('data-libreto') + '/' +$('#row2_'+ids).attr('data-numero')+', ';
                          }
                          
                        }
                      } 
                      comparacion+=ids+',';
                    }
                  });
                }else{
                  alert(la_escena+ $('#row2_'+ids).attr('data-libreto')+ '/' +$('#row2_'+ids).attr('data-numero')+ya_pertenede_a_un_plan);
                }
                }  
              });
            
            if(pathname.search("plan_diario/")>=0){

              if(!confirm(desea_asignar_estas_escenas_al_plan_diario_del_dia +$('#fecha_unidad_plan').val()+de_la_unidad+unidad_selected.replace("unidad","")+' ?')){
                
                var temp_e = comparacion.split(',');
                for (var i = 0; i < temp_e.length; i++) {
                   $('#idescenas').val($('#idescenas').val().replace(temp_e[i]+',', ""));
                };
                $('#idplanes').val("");
              }
            }else{

              if(!confirm(confirmacion+desea_asignar_estas_escenas_al_plan_diario_del_dia+$('#start_plan').val()+de_la_unidad+unidad_selected.replace("unidad","")+' ?')){
                var temp_e = comparacion.split(',');
                for (var i = 0; i < temp_e.length; i++) {
                   $('#idescenas').val($('#idescenas').val().replace(temp_e[i]+',', ""));
                };
                $('#idplanes').val("");
                console.log($('#idescenas').val()+'------'+comparacion);
                $('#wrapper_loader').fadeOut();
              }
            }
            if(valida>0){
             $('#Asing_plan').submit();
            }
          }else{
            $('#wrapper_loader').fadeOut();
            alert(este_plan_esta_cerrado);
          }
        }else{
          var html = '<label class="error label-error-hiden">'+campo_requerido+'</label>';
          $('#start_plan').parent().find('label.error').remove();
          $('#unidad_selector').parent().find('label.error').remove();
          if($('#start_plan').val()==""){
            $('#start_plan').addClass('error');
            $('#start_plan').parent().append(html);
          }

          if($('#unidad_selector').val()==""){
            $('#unidad_selector').addClass('error');
            $('#unidad_selector').parent().append(html);
          }
        }
      }
    });
    /*FUNCION PARA ASIGNAR ESCENAS A UN PLAN DIARIO*/

  $('#guardar').click(function(){
     $('#Asing_plan').submit();
  });

  $('#start_plan').change(function(){
    $('#start_plan').removeClass('error');
    $('.error').remove();
  });

 $('#fecha_unidad_plan').click(function(){
    $('#fecha_unidad_plan').removeClass('error');
 });

 /*VALIDACIONES FECHAS PLAN DIARIO*/
 $('#fecha_unidad_plan').datepicker( "option", "maxDate", $('#end_recording_production').val());
 $('#fecha_unidad_plan').datepicker( "option", "minDate", $('#start_recording_production').val());

  $('#fecha_unidad_mover').datepicker( "option", "maxDate", $('#end_recording_production').val());
 $('#fecha_unidad_mover').datepicker( "option", "minDate", $('#fecha_actual').val());


  /*FIN VALIDACIONES FECHAS PLAN DIARIO*/
  $('#cargar_mas').click(function(event){
    var busqueda_general=$('.busqueda_general').val();
      if(busqueda_general=="0"){
          var limit=parseInt($('.limit_plan_general').val());
          var limit=limit+50;
          // if(limit>=$('#total_escenas').val())
          $('.limit_plan_general').val(limit);
          $('#asignarEscenasSeleccinadas').fadeOut();
          $('#validate_click').val('1');
          $('.filter_button').click();
      }else{
        $('#validate_click').val('1');
        var e = jQuery.Event("keyup");
        e.which = 13; //choose the one you want
        e.keyCode = 13;
        $("#buscar_escena").trigger(e);
   
      }
        
  });

  $('#cargar_todos').click(function(){
     var busqueda_general=$('.busqueda_general').val();
      if(busqueda_general=="0"){
            var limit=parseInt($('.limit_plan_general').val());
            var limit=limit+50;
            // if(limit>=$('#total_escenas').val())
            $('.limit_plan_general').val(limit);
            $('#asignarEscenasSeleccinadas').fadeOut();
            $('#validate_click').val('1');
            limit_plan_general_consulta=3000;
            $('#cargar_mas').hide();
            $('#cargar_todos').hide();
            $('.filter_button').click();
      }else{
        $('#validate_click').val('1');
        $('.limit_plan_general_general').val(3000);
        var e = jQuery.Event("keyup");
        e.which = 13; //choose the one you want
        e.keyCode = 13;
        $("#buscar_escena").trigger(e);
   
      }      
  });

var idconsulta = 0;
/*FUNCION PARA EL FILTRO EN PLAN GENERAL*/
 $('.filter_button').click(function(){
    var validate_click = $('#validate_click').val();
    $('.busqueda_general').val('0');
    $('#validate_click').val('0');
    $('#wrapper_loader').fadeIn();
    $('#content_filter').hide();
    var vehiculo="";
    var dia_noche="";
    var int_ext="";
    var toma_ubicacion="";
    var flashback="";
    var foto_realizacion="";
    var imagenes_archivo="";
    var tipo_locacion1="";
    var tipo_locacion2="";
    var idescenas = "";
    var limit = parseInt($('.limit_plan_general').val());

    
    if($('#idescenas').val()){
      idescenas = $('#idescenas').val();
    }
    if($('#vehiculo_background').val()){
      vehiculo = $('#vehiculo_background').val();
    }
    $("input.dia_noche").each(function() {
      if($(this).is(':checked')){
       dia_noche = $(this).val();
      }
    });

    $("input.int_ext").each(function() {
      if($(this).is(':checked')){
       int_ext = $(this).val();
      }
    });

    if($('#toma_ubicacion').is(':checked')){
       toma_ubicacion = $('#toma_ubicacion').val();
    }

    if($('#flashback').is(':checked')){
       flashback = $('#flashback').val();
    }

    if($('#foto_realizacion').is(':checked')){
       foto_realizacion = $('#foto_realizacion').val();
    }

    if($('#imagenes_archivo').is(':checked')){
       imagenes_archivo = $('#imagenes_archivo').val();
    }

    if($('#tipo_locacion1').is(':checked')){
       tipo_locacion1 = $('#tipo_locacion1').val();
    }

    if($('#tipo_locacion2').is(':checked')){
       tipo_locacion2 = $('#tipo_locacion2').val();
    }
    var continuidad=$('#dia_continuidad').val();
    var dia_cont='';
    if(continuidad){
       dia_cont=continuidad.split("-");
  
    }

     var locaciones = document.getElementsByName('locacion[]');
     var locacion='';
      $.each(locaciones, function(i,l){
            if (l.checked) {
              locacion+=l.value+',';
            }

      });

    var sets = document.getElementsByName('sets[]');
     var set='';
      $.each(sets, function(i,s){
            if (s.checked) {
              set+=s.value+',';
            }

      });

    var datos={     
    limite1_esc:      $('#limite1_esc').val()   ,
    limite2_esc:      $('#limite2_esc').val()   ,
    limite1_cap:      $('#limite1_cap').val()   ,
    limite2_cap:      $('#limite2_cap').val()   ,
    //locacion:         $('#location').val()  ,
    locacion:       locacion ,
    limite1_fec:      $('#start_recording').val()   ,
    limite2_fec :      $('#end_recording').val()   ,
    //set:      $('#set').val()   ,
    vehiculo_background:vehiculo,
    set:      set,
    con:     $('#con_hidden').val()  ,
    sin:     $('#sin_hidden').val()  ,
    idproduccion:     $('#idproduccion').val()  ,
    unidad:     $('#unidad').val()  ,
    esc_estado:     $('#esc_estado').val()  ,
    toma_ubicacion:     toma_ubicacion  ,
    flashback:      flashback   ,
    foto_realizacion: foto_realizacion,
    imagenes_archivo: imagenes_archivo,
    dia_noche:      dia_noche   ,
    int_ext:      int_ext,
    tipo_locacion2:tipo_locacion2,
    tipo_locacion1:tipo_locacion1,
    idescenas: idescenas,
    idconsulta:idconsulta,
    limit:limit ,
    limit_plan_general_consulta:limit_plan_general_consulta,
    magnitud:$('#magnitud').val(),
    order_by:$('#order_by').val(),
    dia_cont:dia_cont,
    validate_click: validate_click };
    var cadena_tabla = "";
    var class_tr="";
    var fecha =new Date();
    var classe = "";
      $.ajax({
        type: "POST",
        url: site_url+"/plan_general/filtro",
        data: datos,
        dataType: "json",
        success:function(data){         
          if(data.msg != "1"){
            $('#consultaImpresion').val(data.consultaImpresion);
            $('#consultaImpresion_excel').val(data.consultaImpresion_excel);
            $('#consultaExcel').val(data.consultaImpresion);
            $('.top_page_plangeneral').show();
            $('#table_general').show();
            $('#message').hide();
            $('#alert_filter').fadeOut();
            var k=0;
            $.each(data.escenas, function(i,escena){
              var m=0;

                if(escena.estado!=3 && data.usuario_permisos=="write"){
                              class_tr = "actionAsing";
                          }else{
                              class_tr="";
                          }
                          fecha_inicio_escena = escena.fecha_inicio;

                          if(escena.estado == 1 && escena.unidad_numero==0 && escena.fecha_inicio==""){
                              class_tr = "actionAsing";
                          }
                          if(escena.fecha_inicio!=""){
                              if((Date.parse(escena.fecha_inicio)/ 1000) <= (Date.parse(fecha) / 1000)){
                                  class_tr = "oldPlan";
                              }
                          }
                          if(escena.planes_abiertos!="" && escena.planes_abiertos!=null){
                            temp_ex = escena.planes_abiertos.split('_');
                            if(temp_ex[2]!=null){
                              class_tr = "oldPlan";
                              fecha_inicio_escena = temp_ex[0];
                            }
                          }
                          cadena_tabla+= '<tr class="'+class_tr+'" data-plan="'+fecha_inicio_escena+'" id="row_'+escena.id+'" data-idescena="'+escena.id+'" data-libreto="'+escena.capitulo+'"'+'" data-numero="'+escena.numero_escena+'"';
                          if(k%2!=0){
                            cadena_tabla+=' style=""';
                          }
                          ++k;
                          cadena_tabla+= '>';
                          switch (parseInt(escena.estado)) {
                              case 1:
                                  classe = "td_yellow";
                                  break;
                              case 2:
                                  classe = "td_retoma";
                                  break;
                              case 3:
                                  classe = "td_black";
                                  break;
                              case 4:
                                  classe = "td_cian";
                                  break;
                              case 5:
                                  classe = "td_cian_light";
                                  break;
                              case 6:
                                  classe = "td_green";
                                  break;
                              case 7:
                                  classe = "td_green_light";
                                  break;
                              case 8:
                                  classe = "td_pink";
                                  break;
                              case 9:
                                  classe = "td_pink_light";
                                  break;
                              case 10:
                                  classe = "td_orange";
                                  break;
                              case 11:
                                  classe = "td_orange_light";
                                  break;
                              case 12:
                                  classe = "td_yellow";
                                  break;
                              case 14:
                                  classe = "td_retoma";
                                  break;
                              default:
                                  classe = "td_brown_light";
                                  break;
                          }

                          cadena_tabla+='<td class="align_center '+classe+'">';

                          
                          if((escena.estado == 1 &&  escena.unidad_produccion!=null) || (escena.estado == 2 &&  escena.unidad_produccion!="") ){
                            cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.unidad_produccion+'/'+escena.fecha_produccion+ '">'+escena.unidad_produccion_numero+'</a>';  
                          }else{
                              if (escena.unidad_numero != 0) {
                                  cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.idunidad+'/'+escena.fecha_inicio+'">'+escena.unidad_numero+'</a>';
                              } else {
                                  cadena_tabla+= '-';
                              }
                          } 

                          cadena_tabla+= '</td>'; 
                          cadena_tabla+='<td class="align_center '+classe+'">';

                          var meses = new Array ("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");

                          if ((escena.estado == 1 &&  escena.fecha_produccion!=null &&  escena.fecha_produccion!="0000-00-00") || (escena.estado == 2 &&  escena.fecha_produccion!=null &&  escena.fecha_produccion!="0000-00-00") ) {
                            var fecha=escena.fecha_produccion_2.split('-');

                              var mes=meses[parseInt(fecha[1])];
                                 
                              var str__ =escena.fecha_produccion_2;
                              var res__ = str__.replace("-","");
                              res__ = res__.replace("-","");
                              cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.unidad_produccion+'/'+escena.fecha_produccion+'"><span style="display:none" >'+res__+'</span>'+fecha[2]+'-'+mes+'-'+fecha[0]+'</a>';
                          } else {
                            if (escena.fecha_inicio != null && escena.fecha_inicio != "0000-00-00") {
                              var str__ =escena.fecha_inicio;
                              var res__ = str__.replace("-","");
                              res__ = res__.replace("-","");
                              var fecha=escena.fecha_inicio.split('-');
                              var mes=meses[parseInt(fecha[1])]
                              cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.idunidad+'/'+escena.fecha_inicio+'"> <span style="display:none" >'+res__+'</span>'+fecha[2]+'-'+mes+'-'+fecha[0]+'</a>';
                              
                            } else {
                              cadena_tabla+= '-';
                            }
                          }

                          cadena_tabla+='</td>';
                          
                          while ( m < data.campos_usuario.length-1) {
                            if(data.campos_usuario[m]=="libreto"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.capitulo+'</td>';
                            }
                            if(data.campos_usuario[m]=="escena"){ ++m; 
                            cadena_tabla+='<td class="align_center">'+escena.numero_escena+'</td>';
                            }
                            if(data.campos_usuario[m]=="página"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.libreto+'</td>';
                            }
                            if(data.campos_usuario[m]=="día"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.dias_continuidad+'</td>';
                            }
                            if(data.campos_usuario[m]=="locación"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">'+escena.locacion+'</td>';
                            }
                            if(data.campos_usuario[m]=="set"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">'+escena.setnombre+'</td>';
                            }
                            if(data.campos_usuario[m]=="int/ext"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.ubicacion+'</td>';
                            }
                            if(data.campos_usuario[m]=="d/n"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.tiempo+'</td>';
                            }
                            if(data.campos_usuario[m]=="loc/ext"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.tipo+'</td>';
                            }
                            if(data.campos_usuario[m]=="tiempo estimado"){ ++m;
                              cadena_tabla+='<td class="align_center">';
                              if (escena.duracion_estimada_minutos.length < 2) {
                                  cadena_tabla+= '0'+escena.duracion_estimada_minutos+':';
                              } else {
                                  cadena_tabla+= escena.duracion_estimada_minutos+':';
                              }

                              if ((escena.duracion_estimada_segundos).length < 2) {
                                  cadena_tabla+= '0'+escena.duracion_estimada_segundos;
                              } else {
                                  cadena_tabla+= escena.duracion_estimada_segundos;
                              }
                              cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="tiempo real"){ ++m;
                            cadena_tabla+='<td class="align_center">';

                            if ((escena.duracion_real_minutos).length < 2) {
                                cadena_tabla+= '0'+escena.duracion_real_minutos+':';
                            } else {
                                cadena_tabla+= escena.duracion_real_minutos+':';
                            }

                            if ((escena.duracion_real_segundos).length < 2) {
                                cadena_tabla+= '0'+escena.duracion_real_segundos;
                            } else {
                                cadena_tabla+= escena.duracion_real_segundos;
                            }
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="tiempo post"){ ++m;
                            cadena_tabla+='<td class="align_center">';
                                  /*if (escena.tiempo_post_minutos) {
                                    if ((escena.tiempo_post_minutos).length < 2) {
                                        cadena_tabla+= '0'+escena.tiempo_post_minutos+':';
                                    } else {
                                        cadena_tabla+= escena.tiempo_post_minutos+':';
                                    }
                                  }else{
                                    cadena_tabla+="00:";
                                  }  

                            
                            if (escena.tiempo_post_segundos) {
                              if ((escena.tiempo_post_segundos).length < 2) {
                                  cadena_tabla+= '0'+escena.tiempo_post_segundos;
                              } else {
                                  cadena_tabla+= escena.tiempo_post_segundos;
                              }
                            }else{
                              cadena_tabla+="00";
                            }  */
                            if(escena.tiempo_post_minutos && escena.tiempo_post_segundos){
                               cadena_tabla+=calculo_tiempo_post(escena.tiempo_post_minutos,escena.tiempo_post_segundos,escena.tiempo_post_cuadros); 
                            }else{
                             cadena_tabla+='00:00';
                            }
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="personajes principales"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.personajes_principales+'">';

                            if(escena.personajes_principales!=null){ 
                              cadena_tabla+= escena.personajes_principales.substring(0,35);
                              if (escena.personajes_principales.length >= 35) {
                                  cadena_tabla+= '...';
                              }
                            }
                            cadena_tabla+='</span>';
                            cadena_tabla+='</td>';
                            }

                            if(data.campos_usuario[m]=="personajes secundarios"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.personajes_secundarios+'">';

                            
                            if(escena.personajes_secundarios!=null){ 
                              cadena_tabla+= escena.personajes_secundarios.substring(0,35);
                              if (escena.personajes_secundarios.length >= 35) {
                                  cadena_tabla+= '...';
                              }
                            }

                            cadena_tabla+='</span>';
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="descripción"){ ++m;
                            cadena_tabla+='<td width="5%">';
                            cadena_tabla+='<div class="descriptionText">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.descripcion+'">';

                            /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES descripcion */
                            
                            if(escena.descripcion!=null){ 
                              cadena_tabla+= escena.descripcion.substring(0,40);
                              if ((escena.descripcion).length >= 40) {
                                  cadena_tabla+= '...';
                              }
                            }
                            cadena_tabla+='      </span>';
                            cadena_tabla+='    </div>';
                            cadena_tabla+='</td>';
                          }
                          if(data.campos_usuario[m]=="elementos"){ ++m;
                            cadena_tabla+='<td>';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.elementos+'">';
                            /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES ELEMENTOS */
                            //
                          if(escena.elementos!=null){ 
                            cadena_tabla+= escena.elementos.substring(0,30);
                            if ((escena.elementos).length >= 30) {
                                cadena_tabla+= '...';
                            }
                          }
                            cadena_tabla+= '    </span>';
                            cadena_tabla+= '</td>';
                          }
                          if(data.campos_usuario[m]=="magnitud"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.magnitud_nombre+'</td>';
                          }
                          if(data.campos_usuario[m]=="vehículo background"){ 
                            ++m;
                            if(escena.vehiculo_background!=null && escena.vehiculo_background!='0'){
                              cadena_tabla+='<td class="align_center">'+escena.vehiculo_background+'</td>';
                            }else{
                              cadena_tabla+='<td class="align_center">-</td>';
                            }
                          }
                          }
                          
                          cadena_tabla+= '</tr>';


            });

            if(data.usuario_permisos=="write"){
              console.log(validate_click)
              if(validate_click=='0'){
                $('.tabla_filtro_general').html(data.header+cadena_tabla+"</tbody><script type='text/javascript'> $(document).ready(function() {$('.tabla_filtro_general tbody').selectable({ distance: 1, selected: mostrarBoton }); });   function mostrarBoton(){  $('.asignarEscenasSeleccinadas').fadeIn();       var itemsSelected = $('.tabla_filtro_general tr.ui-selected').size();       if(itemsSelected==0){          ocultarBoton();       }     }  </script>");
                $(".tabla_filtro_general").tablesorter(); 
                 
              }else{
                 console.log(cadena_tabla)
                $('.tabla_filtro_general').append(cadena_tabla+"<script type='text/javascript'> $(document).ready(function() {$('.tabla_filtro_general tbody').selectable({ distance: 1, selected: mostrarBoton }); });   function mostrarBoton(){  $('.asignarEscenasSeleccinadas').fadeIn();       var itemsSelected = $('.tabla_filtro_general tr.ui-selected').size();       if(itemsSelected==0){          ocultarBoton();       }     }  </script>");
                $(".tabla_filtro_general").tablesorter(); 
                 
              }
            }else{
              if(validate_click=='0'){
                $('.tabla_filtro_general').html(data.header+cadena_tabla+"</tbody>");
                $(".tabla_filtro_general").tablesorter(); 
              }else{
                $('.tabla_filtro_general').append(cadena_tabla);
              }
            }



            $(".tabla_filtro_general").tablesorter();
           /* $('.headerSortDown').click();
            $('.headerSortDown').click();
            $('.headerSortUp').click();
            $('.headerSortUp').click();*/
            var total=parseInt(data.total)
            if(limit>=total){
              $('#cargar_mas').fadeOut();
              $('#cargar_todos').fadeOut();
            }else{
              if(limit_plan_general_consulta==3000){
              }else{
                $('.limit_pdf_excel').fadeIn();
                $('#cargar_mas').fadeIn();
                $('#cargar_todos').fadeIn();
              }
             $('.limit_plan_general').val(limit);  
            }
            
            
            $('#save_filter').fadeIn();
            $('#consulta').val(data.msg);
            $('#selected_scenes').html('Escenas: ' + data.cantidad);
            $('.total_escenas').val(data.cantidad);
            $('#selected_scenes').parent().fadeIn();
            $('#selected_time').html('Tiempo Estimado: '+data.tiempo);
            $('#selected_time').parent().fadeIn();
            $('#time_prod').html('Tiempo producido: '+data.tiempo_producido);
            $('#time_prod').parent().fadeIn();
            $('#resume').html('');

            if(data.resumen!=""){
              $('#resume').append('<label> Filtrado por:'+data.resumen+'</label>');

            }

            if($('#validator').val()==""){
               if(data.resumen!=""){
                 $('.go_back').fadeIn();
              }else{
                 $('.go_back').fadeOut();
              }
              $('#validator').val('1');
            }
          }else{
            $('#save_filter').fadeOut();
            $('#consulta').val('');
            $('#alert_filter').html("No se encontraron coincidencias," + data.resumen + " <a href='' class='close'>×</a>");
            $('#alert_filter').fadeIn();

            $('#consultaImpresion').val('1');
            $('#consultaExcel').val('1');
          }
          $('footer').append("<style> .tooltip { word-break: break-all; }</style><script>  $('.oldPlan').on('dblclick',function(){alert(esta_escena_esta_al_plan_del+$(this).attr('data-plan')+', '+que_aun_esta_abierto);});</script>");
           /*$.getScript(site_url+"/js/app.js");
           $.getScript(site_url+"/js/script.js");*/
          $('#wrapper_loader').fadeOut();
           $(".tabla_filtro_general").tablesorter(); 
        }
        
      });

 $(".tabla_filtro_general").tablesorter(); 

    if($('#validator').val()!=""){
      $('#validator').val("");
    }
    sumatoria=0;
    idconsulta=0;
 });
/*FIN FUNCION PARA EL FILTRO EN PLAN GENERAL*/

/*FUNCION PARA DESELECCIONAR RADIOBUTTON*/
$('.unchecked').dblclick(function(){
  if($(this).is(':checked')) {  
    $(this).prop('checked', false);
  }
});
/*FIN FUNCION PARA DESELECCIONAR RADIOBUTTON*/

$('#cancel_consult_button').click(function(){
  $('#save_filter .closeIcon').click();
});

$('#limite1_cap').keypress(function(){
  return numbersonly(event);
});

$('#limite2_cap').keypress(function(){
  return numbersonly(event);
});

/*FUNCION PARA LIMPIAR FILTROS PLAN DIARIO*/
$('.go_back').click(function(){
  /*  $('#sin_hidden').val("");
    $('#con_hidden').val("");
    $("#tabla2 .sort_left").each(function() {
      $(this).find('.eliminar_elemento').click();
    });
    $('#asignarEscenasSeleccinadas').fadeOut();
    $('#elemento_id').change();
    $('.limit_plan_general').val(50);
    $('#cargar_mas').fadeIn();
    $('#cargar_todos').fadeIn();
    document.getElementById("filtro").reset();
    $('.filter_button').click();
    $('#validator').val('1');
    $('.go_back').hide();
    $('#consultaImpresion').val('');
    $('#consultaExcel').val('');*/
    $('#wrapper_loader').fadeIn();
    location.reload(true);
})
/*FIN FUNCION PARA LIMPIAR FILTROS PLAN DIARIO*/

/*FUNCION PARA GUARDAR FILTROS PLAN DIARIO*/
$('#save_consult_button').click(function(){
  var idproduccion = $('#idproduccion').val();
  var nombre = $('#nombre_consulta').val();
  if($('#nombre_consulta').val()!=""){
    var datos={idproduccion:idproduccion,nombre:nombre};
      $.ajax({
      type: "POST",
      url: site_url+"/plan_general/guardar_consulta",
      data: datos,
      dataType: "json",
      success:function(data){ 
        idconsulta = data.idconsulta;
        $('.filter_button').click();
        $('#wrappOverlay').fadeOut();
        $('.consult_list').append('<a class="nameConsulta" href="'+site_url+'/plan_general/ejecutar_consulta/'+data.idconsulta+'/'+idproduccion+'">'+nombre+' </a><a class="delete_consult" data-idconsulta="'+data.idconsulta+'" data-idproduccion="'+idproduccion+'">'+eliminar+'</a>,');
        return false;
      }
    });
  }else{
    $('#nombre_consulta').addClass('error');
  }
});
/*FIN FUNCION PARA GUARDAR FILTROS PLAN DIARIO*/

/*FIN FUNCION FILTRO PLAN GENERAL*/
var identifier=0;

/*FUNCION BUSQUEDA GENERAL PLAN GENERAL*/
$('#buscar_escena').keyup(function(e){
  var general=$('.busqueda_general').val();
  if (e.keyCode == 13 ) {
  $('#wrapper_loader').fadeIn();
  var validate_click = $('#validate_click').val();
  var palabra = $(this).val();
  $('.busqueda_general').val('1');
  var idproduccion = $('#idproduccion').val();
  var limit_plan_general=$('.limit_plan_general_general').val();
  var datos={idproduccion:idproduccion, palabra:palabra ,idescenas: $('#idescenas').val(),limit_plan_general:limit_plan_general };
  var cadena_tabla = "";
  var class_tr="";
  var fecha =new Date();
  var classe = "";
  $.ajax({
    type: "POST",
    url: site_url+"/plan_general/find_scenes",
    data: datos,
    dataType: "json",
    success:function(data){ 
      if(data.msg != "1"){
        $('#alert_filter').fadeOut();
                    var k=0;
            $.each(data.escenas, function(i,escena){
              var m=0;
              $('#consultaImpresion').val(data.consultaImpresion);
            $('#consultaImpresion_excel').val(data.consultaImpresion_excel);
            $('#consultaExcel').val(data.consultaImpresion);
                if(escena.estado!=3 && data.usuario_permisos=="write"){
                              class_tr = "actionAsing";
                          }else{
                              class_tr="";
                          }

                          if(escena.estado == 1 && escena.unidad_numero==0 && escena.fecha_inicio==""){
                              class_tr = "actionAsing";
                          }
                          if(escena.fecha_inicio!=""){
                              if((Date.parse(escena.fecha_inicio)/ 1000) <= (Date.parse(fecha) / 1000)){
                                  class_tr = "oldPlan";
                              }
                          }

                          cadena_tabla+= '<tr class="'+class_tr+'" data-plan="'+escena.fecha_inicio+'" id="row_'+escena.id+'" data-idescena="'+escena.id+'" data-libreto="'+escena.capitulo+'"';
                          if(k%2!=0){
                            cadena_tabla+=' style=""';
                          }
                          ++k;
                          cadena_tabla+= '>';
                          switch (parseInt(escena.estado)) {
                              case 1:
                                  classe = "td_yellow";
                                  break;
                              case 2:
                                  classe = "td_retoma";
                                  break;
                              case 3:
                                  classe = "td_black";
                                  break;
                              case 4:
                                  classe = "td_cian";
                                  break;
                              case 5:
                                  classe = "td_cian_light";
                                  break;
                              case 6:
                                  classe = "td_green";
                                  break;
                              case 7:
                                  classe = "td_green_light";
                                  break;
                              case 8:
                                  classe = "td_pink";
                                  break;
                              case 9:
                                  classe = "td_pink_light";
                                  break;
                              case 10:
                                  classe = "td_orange";
                                  break;
                              case 11:
                                  classe = "td_orange_light";
                                  break;
                              default:
                                  classe = "td_brown_light";
                                  break;
                          }

                          cadena_tabla+='<td class="align_center '+classe+'">';

                          
                          if(escena.estado == 1  &&  escena.unidad_produccion!=""){
                            cadena_tabla+= '<a href="'+site_url+'plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.unidad_produccion+'/'+escena.fecha_produccion+ '">'+escena.unidad_produccion_numero+'</a>';  
                          }else{
                              if (escena.unidad_numero != 0) {
                                  cadena_tabla+= '<a href="'+site_url+'plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.idunidad+'/'+escena.fecha_inicio+'">'+escena.unidad_numero+'</a>';
                              } else {
                                  cadena_tabla+= '-';
                              }
                          } 

                          cadena_tabla+= '</td>'; 
                          cadena_tabla+='<td class="align_center '+classe+'">';


                           var meses = new Array ("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");

                          if ((escena.estado == 1 &&  escena.fecha_produccion!=null &&  escena.fecha_produccion!="0000-00-00") || (escena.estado == 2 &&  escena.fecha_produccion!=null &&  escena.fecha_produccion!="0000-00-00") ) {
                            var fecha=escena.fecha_produccion_2.split('-');

                              var mes=meses[parseInt(fecha[1])];
                                 
                              var str__ =escena.fecha_produccion_2;
                              var res__ = str__.replace("-","");
                              res__ = res__.replace("-","");
                              cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.unidad_produccion+'/'+escena.fecha_produccion+'"><span style="display:none" >'+res__+'</span>'+fecha[2]+'-'+mes+'-'+fecha[0]+'</a>';
                          } else {
                            if (escena.fecha_inicio != null && escena.fecha_inicio != "0000-00-00") {
                              var str__ =escena.fecha_inicio;
                              var res__ = str__.replace("-","");
                              res__ = res__.replace("-","");
                              var fecha=escena.fecha_inicio.split('-');
                              var mes=meses[parseInt(fecha[1])]
                              cadena_tabla+= '<a href="'+site_url+'/plan_diario/index/'+data.produccion[0].id_produccion+'/'+escena.idunidad+'/'+escena.fecha_inicio+'"> <span style="display:none" >'+res__+'</span>'+fecha[2]+'-'+mes+'-'+fecha[0]+'</a>';
                              
                            } else {
                              cadena_tabla+= '-';
                            }
                          }


                          cadena_tabla+='</td>';
                          
                          while ( m < data.campos_usuario.length-1) {
                            if(data.campos_usuario[m]=="libreto"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.capitulo+'</td>';
                            }
                            if(data.campos_usuario[m]=="escena"){ ++m; 
                            cadena_tabla+='<td class="align_center">'+escena.numero_escena+'</td>';
                            }
                            if(data.campos_usuario[m]=="página"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.libreto+'</td>';
                            }
                            if(data.campos_usuario[m]=="día"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.dias_continuidad+'</td>';
                            }
                            if(data.campos_usuario[m]=="locación"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">'+escena.locacion+'</td>';
                            }
                            if(data.campos_usuario[m]=="set"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">'+escena.setnombre+'</td>';
                            }
                            if(data.campos_usuario[m]=="int/ext"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.ubicacion+'</td>';
                            }
                            if(data.campos_usuario[m]=="d/n"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.tiempo+'</td>';
                            }
                            if(data.campos_usuario[m]=="loc/ext"){ ++m;
                            cadena_tabla+='<td class="align_center">'+escena.tipo+'</td>';
                            }
                            if(data.campos_usuario[m]=="tiempo estimado"){ ++m;
                              cadena_tabla+='<td class="align_center">';
                              if (escena.duracion_estimada_minutos.length < 2) {
                                  cadena_tabla+= '0'+escena.duracion_estimada_minutos+':';
                              } else {
                                  cadena_tabla+= escena.duracion_estimada_minutos+':';
                              }

                              if ((escena.duracion_estimada_segundos).length < 2) {
                                  cadena_tabla+= '0'+escena.duracion_estimada_segundos;
                              } else {
                                  cadena_tabla+= escena.duracion_estimada_segundos;
                              }
                              cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="tiempo real"){ ++m;
                            cadena_tabla+='<td class="align_center">';

                            if ((escena.duracion_real_minutos).length < 2) {
                                cadena_tabla+= '0'+escena.duracion_real_minutos+':';
                            } else {
                                cadena_tabla+= escena.duracion_real_minutos+':';
                            }

                            if ((escena.duracion_real_segundos).length < 2) {
                                cadena_tabla+= '0'+escena.duracion_real_segundos;
                            } else {
                                cadena_tabla+= escena.duracion_real_segundos;
                            }
                            cadena_tabla+='</td>';
                            }

                            if(data.campos_usuario[m]=="tiempo post"){ ++m;
                                cadena_tabla+='<td class="align_center">';

                               /* if ((escena.tiempo_post_minutos).length < 2) {
                                    cadena_tabla+= '0'+escena.tiempo_post_minutos+':';
                                } else {
                                    cadena_tabla+= escena.tiempo_post_minutos+':';
                                }

                                  if ((escena.tiempo_post_segundos).length < 2) {
                                      cadena_tabla+= '0'+escena.tiempo_post_segundos;
                                  } else {
                                      cadena_tabla+= escena.tiempo_post_segundos;
                                  }*/
                            if(escena.tiempo_post_minutos && escena.tiempo_post_segundos){
                               cadena_tabla+=calculo_tiempo_post(escena.tiempo_post_minutos,escena.tiempo_post_segundos,escena.tiempo_post_cuadros); 
                            }else{
                             cadena_tabla+='00:00';
                            }
                                /*cadena_tabla+=calculo_tiempo_post(escena.tiempo_post_minutos,escena.tiempo_post_segundos,escena.tiempo_post_cuadros);
                                console.log(escena.tiempo_post_minutos,escena.tiempo_post_segundos,escena.tiempo_post_cuadros)*/
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="personajes principales"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.personajes_principales+'">';

                            

                            if(escena.personajes_principales!=null){ 
                              cadena_tabla+= escena.personajes_principales.substring(0,30);
                              if (escena.personajes_principales.length >= 30) {
                                  cadena_tabla+= '...';
                              }
                            }
                            cadena_tabla+='</span>';
                            cadena_tabla+='</td>';
                            }

                            if(data.campos_usuario[m]=="personajes secundarios"){ ++m;
                            cadena_tabla+='<td class="cell_align_left">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.personajes_secundarios+'">';

                            
                            if(escena.personajes_secundarios!=null){ 
                              cadena_tabla+= escena.personajes_secundarios.substring(0,30);
                              if (escena.personajes_secundarios.length >= 30) {
                                  cadena_tabla+= '...';
                              }
                            }

                            cadena_tabla+='</span>';
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="descripción"){ ++m;
                            cadena_tabla+='<td width="5%">';
                            cadena_tabla+='<div class="descriptionText">';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.descripcion+'">';

                            /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES descripcion */
                            
                            if(escena.descripcion!=null){ 
                              cadena_tabla+= escena.descripcion.substring(0,40);
                              if ((escena.descripcion).length >= 40) {
                                  cadena_tabla+= '...';
                              }
                            }
                            cadena_tabla+='      </span>';
                            cadena_tabla+='    </div>';
                            cadena_tabla+='</td>';
                            }
                            if(data.campos_usuario[m]=="elementos"){ ++m;
                            cadena_tabla+='<td>';
                            cadena_tabla+='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="'+escena.elementos+'">';
                            /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES ELEMENTOS */
                            //
                              if(escena.elementos!=null){ 
                                cadena_tabla+= escena.elementos.substring(0,30);
                                  if ((escena.elementos).length >= 30) {
                                      cadena_tabla+= '...';
                                  }
                              }
                            cadena_tabla+= '    </span>';
                            cadena_tabla+= '</td>';
                            }
                            if(data.campos_usuario[m]=="magnitud"){ ++m;
                              cadena_tabla+='<td class="align_center">'+escena.magnitud_nombre+'</td>';
                            }
                            if(data.campos_usuario[m]=="vehículo background"){ 
                              ++m;
                              if(escena.vehiculo_background!=null && escena.vehiculo_background!='0'){
                                cadena_tabla+='<td class="align_center">'+escena.vehiculo_background+'</td>';
                              }else{
                                cadena_tabla+='<td class="align_center">-</td>';
                              }
                            }
                          }
                          cadena_tabla+= '</tr>';


            });
        
       /* $('.tabla_filtro_general').html(data.header+cadena_tabla+"</tbody>");
        $(".tabla_filtro_general").tablesorter({});*/ 

        if(data.usuario_permisos=="write"){
          if(validate_click=='0'){
                $('.tabla_filtro_general').html(data.header+cadena_tabla+"</tbody><script type='text/javascript'> $(document).ready(function() {$('.tabla_filtro_general tbody').selectable({ distance: 1, selected: mostrarBoton }); });   function mostrarBoton(){  $('.asignarEscenasSeleccinadas').fadeIn();       var itemsSelected = $('.tabla_filtro_general tr.ui-selected').size();       if(itemsSelected==0){          ocultarBoton();       }     }  </script>");
                $(".tabla_filtro_general").tablesorter(); 
              }else{
                $('.tabla_filtro_general').append(cadena_tabla+"<script type='text/javascript'> $(document).ready(function() {$('.tabla_filtro_general tbody').selectable({ distance: 1, selected: mostrarBoton }); });   function mostrarBoton(){  $('.asignarEscenasSeleccinadas').fadeIn();       var itemsSelected = $('.tabla_filtro_general tr.ui-selected').size();       if(itemsSelected==0){          ocultarBoton();       }     }  </script>");
                console.log(cadena_tabla)
              }
            }else{
              if(validate_click=='0'){
                $('.tabla_filtro_general').html(data.header+cadena_tabla+"</tbody>");
                $(".tabla_filtro_general").tablesorter(); 
              }else{
                $('.tabla_filtro_general').append(cadena_tabla);
              }
            }

        $(".tabla_filtro_general").trigger("update");
        $('.headerSortDown').click();
        $('.headerSortDown').click();
        $('.headerSortUp').click();
        $('.headerSortUp').click();
        var total=parseInt(data.total)

        if(parseInt(data.limit_plan_general)>=total){
          $('#cargar_mas').fadeOut();
          $('#cargar_todos').fadeOut();
        }else{
          if(limit_plan_general_consulta==3000){
          }else{
            $('#cargar_mas').fadeIn();
            $('#cargar_todos').fadeIn();
          }
         
        }
        console.log(data.limit_plan_general)
        $('.limit_plan_general_general').val(data.limit_plan_general);  
        


        $('#selected_scenes').html('Escenas: ' + data.total);
        $('.total_escenas').val(data.total);
        $('#selected_time').html('Tiempo Estimado: '+data.tiempo);
        

      }else{
        $('#alert_filter').fadeIn();
        $('#table_general').html('');
      }
      $('#wrapper_loader').fadeOut();
      $(".tabla_filtro_general").tablesorter(); 
    }


  });

  }
 
});

/*FIN FUNCION BUSQUEDA GENERAL PLAN GENERAL*/

/*FUNCION PARA FECHAS PLAN PRODUCCION*/
  $( "#end_recording" ).datepicker({
    dateFormat: 'dd-M-yy',
    changeMonth: true,
    numberOfMonths: 2,
    showOn: "button",
    buttonImage: site_url2+"/images/calendar.gif",
    buttonImageOnly: true,
    onClose: function( selectedDate ) {
      $( "#start_recording" ).datepicker( "option", "maxDate", selectedDate );
    },
    onSelect: function(){
      $(this).focus();
    }
  });
/*FIN FUNCION PARA FECHAS PLAN PRODUCCION*/

    $('#name_production').click(function(){
      removeclass_error();  
    });

    $('#name_production').focusout(function(){
      validation_production_name();
    });

    function addclass_error(){
      $('#name_production').addClass('error');
      +$('#name_production').parent('div').find('.error2').remove();
      $('#name_production').parent('div').append('<label class="error2">'+nombre_de_la_produccion_ya_asignado+'</label>');
      $('#name_production').parent('div').find('label.error2').css('color','#c60f13');
      $('#validation_name').val('2');
    }

    function removeclass_error(){
      $('#name_production').removeClass('error');
      $('#name_production').parent('div').find('.error2').remove();
      $('#validation_name').val('');
    }

    $('.onlynumbers').keypress(function(){
      return numbersonly(event);
    });
    
    /*VALIDACION SEGUNDOS PLAN PRODUCCION*/
    $('#segundos_capitulo').keyup(function(){
      if(parseInt($('#segundos_capitulo').val())>59){
        $(this).removeClass('error');
        $(this).parent('div').find('.error2').remove();
        $('#segundos_capitulo').addClass('error');
        $('#segundos_capitulo').parent('div').append('<label class="error2">'+el_valor_debe_ser_menor_a+'</label>');
        $('#segundos_capitulo').parent('div').find('label.error2').css('color','#c60f13');
      }else{
        $('#segundos_capitulo').removeClass('error');
        $('#segundos_capitulo').parent('div').find('.error2').remove();
      }
    });
    /*FIN VALIDACION SEGUNDOS PLAN PRODUCCION*/

    /*VALIDACION SEGUNDOS PLAN PRODUCCION*/
    $('#seg_proy_sem').keyup(function(){
      if(parseInt($('#seg_proy_sem').val())>59){
        $(this).removeClass('error');
        $(this).parent('div').find('.error2').remove();
        $('#seg_proy_sem').addClass('error');
        $('#seg_proy_sem').parent('div').append('<label class="error2">'+el_valor_debe_ser_menor_a+'</label>');
        $('#seg_proy_sem').parent('div').find('label.error2').css('color','#c60f13');
      }else{
        $('#seg_proy_sem').removeClass('error');
        $('#seg_proy_sem').parent('div').find('.error2').remove();
      }
    });
    /*FIN VALIDACION SEGUNDOS PLAN PRODUCCION*/

    /*VALIDACION NUMERO MAXIMO PLAN PRODUCCION*/
    $('.maxnumber').keyup(function(){
      if(parseInt($(this).val())>999){
        $(this).removeClass('error');
        $(this).parent('div').find('.error2').remove();
        $(this).addClass('error');
        $(this).parent('div').append('<label class="error2">'+el_valor_maximo_es+'</label>');
        $(this).parent('div').find('label.error2').css('color','#c60f13');
      }else{
        $(this).removeClass('error');
        $(this).parent('div').find('.error2').remove();
      }
    });
    /*FIN VALIDACION NUMERO MAXIMO PLAN PRODUCCION*/

    /*VALIDACION NOMBRE DE PLA PRODUCCION*/
    function validation_production_name(){
    datos= {name_production: $('#name_production').val()}
      $.ajax({
        type: "POST",
        url: site_url+"/produccion/validate_production_name",
        data: datos,
          dataType: "json",
          success:function(data){
            if(data.response=='1'){
              if($('#id_produccion').val()!=""){
                datos2= {name_production: $('#name_production').val(), idproduccion:$('#id_produccion').val()}
                  $.ajax({
                  type: "POST",
                  url: site_url+"/produccion/validate_production_name_produccion",
                  data: datos2,
                    dataType: "json",
                    success:function(data){
                      if(data.response=='1'){
                        addclass_error();
                      }else{
                        removeclass_error();
                      }
                    }
                  }); 
              }else{
                addclass_error();  
              }
            }
          }
      }); 
    }
    /*FIN VALIDACION NOMBRE DE PLA PRODUCCION*/


    $( ".online_cap" ).datepicker( "option", "minDate", new Date($('#min_date_cap').val()));
    $( ".online_cap2" ).datepicker( "option", "minDate", new Date($('#min_date_cap').val()));

    /*FUNCION ELIMINAR UNIDAD PLAN PRODUCCION*/
    $(".delete_unity").click(function(){
      var idunidad = $(this).attr('data-idunidad');
      datos= {idunidad: idunidad}
      $.ajax({
        type: "POST",
        url: site_url+"/produccion/buscar_planes_unidad",
        data: datos,
        dataType: "json",
          success:function(data){
            if(data.asignados == '1'){
              alert(esta_unidada_no_puede_eliminarce);
            }else{
              if(confirm(esta_seguro_de_que_desea_eliminar_esta_unidad)){
                datos2= {idunidad: idunidad, idproduccion: $('#id_produccion').val()}
                $.ajax({
                  type: "POST",
                  url: site_url+"/produccion/eliminar_unidad",
                  data: datos2,
                  dataType: "json",
                    success:function(data){
                      if(data.respuesta=='1'){
                        location.reload(true);
                      }
                    }
                }); 
              }
            }
          }
        }); 
    });
    /*FIN FUNCION ELIMINAR UNIDAD PLAN PRODUCCION*/

  $('#cancel_duplicate').click(function(){
    $('#duplicate_escene').fadeOut();
    $('#wrappOverlay').fadeOut();
  });

  $('#duplicate_escene span.closeIcon').click(function(){
   $('#cancel_duplicate').click();
  });

  /*FUNCION BUSCAR USUARIOS*/
  $('#buscar_usuarios').keyup(function(){
    var apellido = $('#buscar_usuarios').val();
    datos= {apellido: apellido}
    $.ajax({
      type: "POST",
      url: site_url+"/produccion/buscar_usuarios",
      data: datos,
      dataType: "json",
        success:function(data){
          $("#usersTable").html("");
          $("#usersTable").html(data.cadena_tabla);
        }
    }); 
  });
  /*FIN FUNCION BUSCAR USUARIOS*/

  /*FUNCION ELIMINAR ESCENA DE UN PLAN DIARIO*/
  $('.delete_scene_plan').click(function(){
    var idplan = $(this).attr('data-idplan');
    var idescena = $(this).attr('data-idescena');
    var idproduccion = $('#idproduccion').val();
    var idunidad = $('#unidad_plan').val();
    if(confirm(eliminar_escena + $(this).attr('data-numerolibreto') + "/"+ $(this).attr('data-numeroescena') +de_este_plan)){
      $('#wrapper_loader').fadeIn();
      datos= {idplan: idplan,idescena:idescena,idproduccion:idproduccion,idunidad:idunidad}
      $.ajax({
        type: "POST",
        url: site_url+"/plan_diario/eliminar_escena_plan",
        data: datos,
        dataType: "json",
          success:function(data){
            if(data.resultado=="1"){
              $('.row_'+idescena).remove();
            }
            if(parseInt(data.cantidad) <=0){
              $('.estado_plan').html('No iniciado');
            }
            location.reload(true);
          }
      });  
    }
  });
  /*FIN FUNCION ELIMINAR ESCENA DE UN PLAN DIARIO*/


  $('.load_page').click(function(){
    $('#wrapper_loader').fadeIn();
  })

  /*FUNCION CARGAR SELECT DE ESCENAS PLAN DIARIO*/
  $('#capitulos').change(function(){
    var cadena_escenas="";
    datos= {idcapitulo: $('#capitulos').val(), idplan: $('#idplan').val()}
    $.ajax({
      type: "POST",
      url: site_url+"/plan_diario/buscar_escenas_capitulo",
      data: datos,
      dataType: "json",
        success:function(data){
          if(data.escenas!=""){
            $('#insert_asing_button').fadeIn();
            //$.each(data.escenas, function(i,escenas){
             cadena_escenas = data.cadena_escenas;
            //});
            $('#insert_asing_button').show();  
          }else{
            $('#insert_asing_button').fadeOut();
          }
          $('#escenas').html(cadena_escenas);
          $('#idescenas').val($('#escenas').val()+',');
        }
    });  
  });
  /*FIN FUNCION CARGAR SELECT DE ESCENAS PLAN DIARIO*/
  
  $('#escenas').change(function(){
    $('#idescenas').val($('#escenas').val()+',');
  });

  var pathname = window.location.pathname;  
  if(pathname.search("plan_diario/index")>0){
    $('#unidad_plan').change();
    $('#capitulos').change();
  }



 $('#find_plan').click(function(){
  $('#wrapper_loader').fadeIn();
 });

  /*FUNCION VALIDAR LA FECHA DE INICIO UNIDAD*/
  $('#unidad_plan').change(function(){
    if($('#unidad_plan').val()!=""){
      $('#find_plan').fadeIn();
      $('label.error').remove();
      var minDate = $("#date_unity" + $(this).val()).html();
      var html2 = '<label style="color: #c60f13!important;" class="error label-error-hiden">'+esta_unidad_no_tiene_fecha_asignada+'</label>';
      if(minDate!=""){
        $("#fecha_unidad_plan" ).datepicker( "option", "minDate", minDate);
        $('#unidad_plan').removeClass('error');
        $('label.error').remove(); 
        $('#find_plan').show();
      }else{
          $('#unidad_plan').addClass('error');
          $('#unidad_plan').parent().append(html2);
          $('#find_plan').hide();      
      }
      $('#fecha_unidad_plan').val('');
    }else{
      $('#find_plan').fadeOut();
    }
  });
  /*FUNCION VALIDAR LA FECHA DE INICIO UNIDAD*/

  $('a.confirm_link').click(function(){
      return confirm_link();
  });

  $('.submenu li a').click(function(){
    return confirm_link();
  });

  $('#edit_plan_diario').click(function(){
    var validation = true;
    var horas_wrap_time = $('#horas_wrap_time').val();
    var minutos_wrap_time = $('#minutos_wrap_time').val();
    var am_pm_wrap_time = $('#am_pm_wrap_time').val();
    var horas_llamado = $('#horas_llamado').val();
    var minutos_llamado = $('#minutos_llamado').val();
    var am_pm_llamado = $('#am_pm_llamado').val();

    var values_wrap_time = new Array($('#horas_wrap_time'),$('#minutos_wrap_time'),$('#am_pm_wrap_time'));  
    var values_llamado = new Array($('#horas_llamado'),$('#minutos_llamado'),$('#am_pm_llamado'));  
    

    //WRAP TIME//
    if(horas_wrap_time!="" && minutos_wrap_time!="" && am_pm_wrap_time!=""){
      validation=true;
    }else{
      for (var i = 0; i < values_wrap_time.length; i++) {
        if(values_wrap_time[i].val()!=""){
          validation=false;
          for (var j = 0; j < values_wrap_time.length; j++) {
            values_wrap_time[j].addClass('error');
          };
        }
      };
    }

    //LLAMADO//
    if(horas_llamado!="" && minutos_llamado!="" && am_pm_llamado!=""){
      if(validation){
        validation=true;
      }
    }else{
      for (var i = 0; i < values_llamado.length; i++) {
        if(values_llamado[i].val()!=""){
          validation=false;
          for (var j = 0; j < values_llamado.length; j++) {
            values_llamado[j].addClass('error');
          };
        }
      };
    }
    if(validation){
      $('#form_edit_plan_diario').submit();
    }
  });

  /*CONFIRMACION SALIR DE CREAR O EDITAR*/
  function confirm_link(){
    if(pathname.search("crear_escena")>0 || pathname.search("editar_escena")>0 || pathname.search("/produccion/index")>0){
      if(confirm(esta_seguro_que_desea_salir)){
        return true;
      }else{
        return false;
      }
    }  
  }
  /*FIN CONFIRMACION SALIR DE CREAR O EDITAR*/

  $('.oldPlan').on("dblclick",function(){
    alert(esta_escena_esta_al_plan_del+$(this).attr('data-plan')+', '+que_aun_esta_abierto);
  });

  $('.olderPlan').on("click",function(){
    alert(esta_escena_esta_al_plan_del+$(this).attr('data-plan')+', '+que_aun_esta_abierto);
    $('#insert_asing_button').hide();
  });
  $('.olderPlan').focus(function(){
    alert();
  })

  $('.noPlan').on("click",function(){
    $('#insert_asing_button').show();
  });

  $('#buscar_usuarios_tabla').keyup(function(e){
    if (e.keyCode == 13 || $('#buscar_elemento').val()=="") {
      $('#loadElements').fadeIn();    
      var palabra=$('#buscar_usuarios_tabla').val();
      var opciones='';
      var id_users = $('#id_users_table').val();
      var idproduccion= $(this).attr('idproduccion');
      var rol ="";
        if(palabra){
          datos= {palabra: palabra, idproduccion:idproduccion, id_users:id_users}
          $.ajax({
          type: "POST",
          url: site_url+"/plan_produccion/buscar_usuarios_palabra",
          data: datos,
          dataType: "json",
          success:function(data){
          if(data.usuarios){
          $.each(data.usuarios, function(i,usuarios){
            opciones+='<tr class="sort_left"><input type="hidden" name="id_user" id="id_user" value="'+usuarios.id_user+'">';
            opciones+='<input type="hidden" name="rol" id="rol" value="'+usuarios.id_rol+'">';
            opciones+='<td>'+usuarios.nombre+'</td><td>'+usuarios.descripcion+'</td>';
            opciones+='<td class="role_hiden" style="text-align:center;"><input type="checkbox" id=""></td>';
            opciones+='<td class="actions_hiden" style="text-align: center;"> <a class="delete">'+eliminar+'</a> <a class="add">'+agregar+'</a> </td>';
            opciones+='</tr>'
          });
          } else{
            opciones=opciones+"<tr class='ui-drag-disabled'> class='sort_left'><td>"+no_hay_usuario_para_la_busqueda+"</td></tr>";
          }
            $("#table_1").html(opciones);
          }
          });
        }else{
          $('#tipo_user').change();
        }
        $('#loadElements').fadeOut();
    }
  });
  
$('#crear_libreto').click(function(){
  $('.number').focus();
});

$('.open_box_plan').on('click',function(){
  $(this).next().fadeIn();
  $(this).next().find('.hide_box').fadeIn();
});


$('.locaciones').click(function(){
 $('.ver_locaciones').show();
});

/*FILTRO DE USUARIOS POR PRODUCCION*/
$('#productions_list').change(function(){
  var idproduccion = $(this).val();
  var estado_user=$('#estado_user').val();
  datos= {idproduccion: idproduccion,estado_user:estado_user}
    $.ajax({
      type: "POST",
      url: site_url+"/admin_user/buscar_usuarios_produccion",
      data: datos,
      dataType: "json",
        success:function(data){
          $("#usersTable").html("");
          $("#usersTable").html(data.cadena_tabla);
        }
    }); 
});
/*FIN FILTRO DE USUARIOS POR PRODUCCION*/
/*FILTRO DE USUARIOS POR ESTADO*/
$('#estado_user').change(function(){
  var idproduccion = $('#productions_list').val();
  var estado_user=$('#estado_user').val();
  datos= {idproduccion: idproduccion,estado_user:estado_user}
    $.ajax({
      type: "POST",
      url: site_url+"/admin_user/buscar_usuarios_produccion",
      data: datos,
      dataType: "json",
        success:function(data){
          $("#usersTable").html("");
          $("#usersTable").html(data.cadena_tabla);
        }
    });
  /*var estado = $(this).val();
  datos= {estado: estado}
    $.ajax({
      type: "POST",
      url: site_url+"/admin_user/buscar_usuarios_estado",
      data: datos,
      dataType: "json",
        success:function(data){
          $("#usersTable").html("");
          $("#usersTable").html(data.cadena_tabla);
        }
    }); */
});
/*FIN FILTRO DE USUARIOS POR ESTADO*/

$('#print_pdf').click(function(){
  $('#blue_box').fadeIn();
});

/*FUNCION CREACION PDF PLAN DIARIO DETALLATDO Y GENERAL */
$('#create_pdf').click(function(){
  var option_pdf = "";
  $('.option_pdf').each(function() {
    if($(this).is(':checked')){
      option_pdf = $(this).val();
    }
  });
  if(options_pdf!=""){
    window.open(site_url+'/pdf2/index/'+$(this).attr('data-idproduccion')+'/'+$(this).attr('data-idunidad')+'/'+$(this).attr('data-fechaunidad')+'/' + option_pdf);
  }
});
/*FIN FUNCION CREACION PDF PLAN DIARIO DETALLATDO Y GENERAL  */

$('#open_box_semanal').click(function(){
  $('#blue_box_semanal').fadeIn();
});

/*FUNCION REPORTE SEMANAL UNIDADES*/
$('#semanal_pdf').click(function(){
  var fecha = $('#date_semanal_pdf').val();
  var checked = false;
  var unidades = "";
  if(fecha!=""){
    $("#semanal_report .box_unity").each(function() {
      if($(this).is(':checked')){
       checked = true;
       unidades += $(this).val()+'.';
      }
    });
    if(checked){
      window.open(site_url+'/pdf2/pdf_reporte_semanal/'+$(this).attr('data-idproduccion')+'/'+fecha + '/' + unidades);
    }else{
      $("#semanal_pdf").parent().append("<label class='error'>"+selecciona_una_unidad+"</label>");
    }
  }
});
/*FIN FUNCION REPORTE SEMANAL UNIDADES*/

/*FUNCION REPORTE SEMANAL ELEMENTOS Y PERSONAJES*/
$('#semanal_element_pdf').click(function(){
 
  var fecha = $('#date_semanal_element_pdf').val();
  var checked = false;
  var opcion="";
  var unidades = "";
  $('#semanal_report').find('label.error').remove();
  $("#semanal_report .semanal_option").each(function() {
      if($(this).is(':checked')){
       checked = true;
       opcion += $(this).val();
      }
  });
  if(checked){
    //checked = false;
    $("#semanal_reporte_elements .box_unity_elements").each(function() {
        if($(this).is(':checked')){
         checked = true;
         unidades += $(this).val()+'.';
        }
    });
  }

  if(fecha!="" && unidades!="" && opcion!=""){
     console.log('dasda');
     if(opcion=='0'){
      window.open(site_url+'/pdf2/pdf_reporte_semanal/'+$(this).attr('data-idproduccion')+'/'+fecha + '/' + unidades);
    }else{
      window.open(site_url+'/pdf2/pdf_personajes_elementos_semanal/'+$(this).attr('data-idproduccion')+'/'+fecha+'/'+opcion+'/'+unidades);  
    }
    
  }else{
    $('#semanal_elementos').append('<label class="error">'+seleccione_una_opcion_de_reporte+'</label>');
  }
});
/*FIN FUNCION REPORTE SEMANAL ELEMENTOS Y PERSONAJES*/

/*FUNCION REPORTE NOMINA PERSONAJES*/
$('#nomina_pdf').click(function(){
  var fecha1 = $('#date_nomina_from_pdf').val();
  var fecha2 = $('#date_nomina_to_pdf').val();
  var html= "<label class='error'>"+rango_de_fechas_invalidas+"</label>"
  $('#date_nomina_from_pdf').parent().parent().find("label.error").remove();
  if((Date.parse(fecha1)/ 1000) <= (Date.parse(fecha2)/ 1000)){
    window.open(site_url+'/pdf2/pdf_nomina/'+$(this).attr('data-idproduccion')+'/'+fecha1+'/'+fecha2);
  }else{
    $('#date_nomina_from_pdf').parent().parent().append(html);
  }
});
/*FIN FUNCION REPORTE NOMINA PERSONAJES*/


/*NOMINA PERSONAJES MENSUALES*/
$('#month_selector').change(function(){
  if($(this).val()!=""){
    $('#mes_liquidacion').val($(this).val());
    datos ={ id_produccion:$('#the_produccion').val(), mes: $('#month_selector').val() }
    $.ajax({
      type: "POST",
      url: site_url+"/herramientas/actores_mensuales",
      data: datos,
      dataType: "json",
        success:function(data){
          if(data.resultado){
            $('#nomina_mensual_pdf').fadeOut();
            $('#discounts_actors').fadeIn();
            if(data.cadena_liquidacion!=""){
              $('#discounts_actors_section').html(data.cadena_liquidacion);
            }else{
              $('#discounts_actors_section').html("<label>"+no_hay_personajes_mensuales_asignados+"</label>");
            }
          }else{
            $('#discounts_actors').hide();
            $('#nomina_mensual_pdf').fadeIn();
          }
        }
    }); 
  }else{
    $('#nomina_mensual_pdf').fadeIn();
  }
});
/*FIN  NOMINA DE PERSONAJES MENSUALES*/


/*FUNCION REPORTE NOMINA PERSONAJES MENSUALES*/
$('#nomina_mensual_pdf').click(function(){
  var fecha1 = $('#month_selector').val()+'-01';
  var fecha2 = $('#month_selector').val()+'-30';
  var html= "<label class='error'>"+rango_de_fechas_invalidas+"</label>"
  $('#date_nomina_from_pdf').parent().parent().find("label.error").remove();
  window.open(site_url+'/pdf2/pdf_nomina_mensual/'+$(this).attr('data-idproduccion')+'/'+fecha1+'/'+fecha2);
});
/*FIN FUNCION REPORTE NOMINA PERSONAJES MENSUALES*/


/*FUNCION CARGA ESCENAS SELECT DESDE CAPITULO (HERRAMIENTAS)*/
$('#capitulos_herramientas_from').change(function(){
  $('#capitulos_for').val($(this).val());
  var cadena_escenas="";
  $("#escenas_herramientas_from").css('background','lightblue');
  $("#escenas_herramientas_from").html('<option>'+cargando+'...</option>');
  
  datos= {idcapitulo: $('option:selected', this).attr('data-idcapitulo')}
  $.ajax({
    type: "POST",
    url: site_url+"/herramientas/buscar_escenas_capitulo",
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.escenas!=""){
          $.each(data.escenas, function(i,escenas){
           cadena_escenas += "<option data-numero_capitulo='"+escenas.numero_capitulo+"' data-numero_escena='"+escenas.numero_escena+"' data-locacion='"+escenas.locacion_nombre+"' data-set='"+escenas.set_nombre+"' data-dia='"+escenas.dias_continuidad+"' value='"+escenas.id_escena+"'>"+escenas.numero_escena+"</option>";
          });
        }
        $("#escenas_herramientas_from").css('background','');  
        $('#escenas_herramientas_from').html(cadena_escenas);
      }
  });  
});
/*FIN FUNCION CARGA ESCENAS SELECT DESDE CAPITULO (HERRAMIENTAS)*/

/*FUNCION CARGA ESCENAS SELECT HASTA CAPITULO (HERRAMIENTAS)*/
$('#capitulos_herramientas_to').change(function(){
  $('#capitulos_to').val($(this).val());
  var cadena_escenas="";
  $("#escenas_herramientas_to").css('background','lightblue');
  $("#escenas_herramientas_to").html('<option>'+cargando+'...</option>');
  datos= {idcapitulo: $('option:selected', this).attr('data-idcapitulo')}
  $.ajax({
    type: "POST",
    url: site_url+"/herramientas/buscar_escenas_capitulo",
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.escenas!=""){
          $.each(data.escenas, function(i,escenas){
           cadena_escenas += "<option data-numero_capitulo='"+escenas.numero_capitulo+"' data-numero_escena='"+escenas.numero_escena+"' data-locacion='"+escenas.locacion_nombre+"' data-set='"+escenas.set_nombre+"' data-dia='"+escenas.dias_continuidad+"' value='"+escenas.id_escena+"'>"+escenas.numero_escena+"</option>";
          });
        }
        $("#escenas_herramientas_to").css('background','');  
        $('#escenas_herramientas_to').html(cadena_escenas);
      }
  });  
});
/*FIN FUNCION CARGA ESCENAS SELECT HASTA CAPITULO (HERRAMIENTAS)*/

/*ALERTA DE ESCENAS REPORTE PLAN GENERAL*/
$('#pdf_plan_general').click(function(){
  var total_escenas=parseInt($('.total_escenas').val());
  if(total_escenas>300){
    alert(se_informa_un_maximo_de_300_escenas_en_este_informe);  
  }
  
});
/*FIN ALERTA DE ESCENAS REPORTE PLAN GENERAL*/

/*ELIMINAR ESCENAS SELECCIONAS PLAN GENERAL*/
$('#selected_escenes').on('click', '.delete_escene',function(){
  $(this).parent().remove();
});
/*FIN ELIMINAR ESCENAS SELECCIONAS PLAN GENERAL*/

/*FUNCION EDICION DE ESCENAS HERRAMIENTAS*/
$('.save_tools').click(function(){
  var tipo = $(this).attr('data-tipo');
  var valida = true;
  var html= "<label class='error'>"+campo_requerido+"</label>"
  var alerta="";
  $('#tipo').val(tipo);
  var from = $("#capitulos_herramientas_from option:selected").html() +'/'+  $("#escenas_herramientas_from option:selected").html();
  var to = $("#capitulos_herramientas_to option:selected").html() +'/'+  $("#escenas_herramientas_to option:selected").html();
  //var set_busqueda = $("#set_busqueda option:selected").val();
  var set_busqueda = document.getElementsByName('sets[]');
  var id_set_busqueda='';
  $.each(set_busqueda, function(i,set){
        if (set.checked) {
          id_set_busqueda+=set.value+',';
        }

  });

  if(tipo=='1'){
    // CAMBIAR DIA DE CONTINUIDAD
    $('#herramientas_form').validate();
    $('#dia_continuidad').parent().find('label.error').remove();
    $('#dia_continuidad_actual').parent().find('label.error').remove();
    var dia_continuidad = $('#dia_continuidad').val();
    var dia_continuidad_actual = $('#dia_continuidad_actual').val();
    if(dia_continuidad=="" || dia_continuidad_actual==""){
      valida= false;
      $('#dia_continuidad').addClass('error');
      $('#dia_continuidad').parent().append(html);
      $('#dia_continuidad_actual').addClass('error');
      $('#dia_continuidad_actual').parent().append(html);
    }else{
      if(confirm(esta_seguro_que_desea_cambiar_la_continuidad_de_las +from+hasta+ to)){
        valida= true;
        alerta = el_dia_de_continuidad + $('#dia_continuidad').val();
      }else{
        valida= false;
      }
    }
  }else if(tipo=='2'){
    // CAMBIAR LOCACION Y SET
    var location = $('.location:checked').html();
    var set = $('#set').val();
    var location_actual = $('.locacion_select_cambio:checked').html();
    var set_actual = $('#set_actual').val();


    var sets_actual = document.getElementsByName('sets_actual[]');
      var id_set_actual='';
      $.each(sets_actual, function(i,set){
            if (set.checked) {
              id_set_actual+=set.value+',';
            }
      });


    //$('#sets_actual').parent().find('label.error').remove();
    $('#sets_locaciones_cambio').parent().find('label.error').remove();
    $('#set').parent().find('label.error').remove();
    $('.location').parent().find('label.error').remove();
    if(set == null || set == "" || location == "Seleccione una locación"){

      valida= false;
      $('.location').addClass('error');
      $('.location').parent().append(html);
      $('#set').addClass('error');
      $('#set').parent().append(html);
    } else if(id_set_actual == null || id_set_actual == "" || location_actual == "Seleccione una locación"){
      valida= false;
      console.log(valida)
      $('.locacion_select_cambio').removeClass('error');
      //$('#locacion_select_cambio').parent().remove();
      $('.locacion_select_cambio').addClass('error');
      $('.locacion_select_cambio').parent().append(html);
      /*$('#set_actual').addClass('error');
      $('#set_actual').parent().append(html);*/
      $('#sets_actual').addClass('error');
      $('#sets_actual').parent().append(html);
    }else{
      if(confirm(esta_seguro_que_desa_cambiar_la_locacion+from+hasta+ to)){
        valida= true;

        alerta = location + $(".location:checked").attr('data-name').toUpperCase() + y_el_set + $("#set").attr('data-name').toUpperCase();

      }else{
        valida= false;
      }
    }

  }else if(tipo=='3'){
    // CAMBIAR LOCACION / Estudio
    var locacion_tipo = $('.locacion_tipo:checked').val();
    if(locacion_tipo=="1"){
     var locacion_tipo_cambiar=2; 
    }else{
     var locacion_tipo_cambiar=1;
    }
    //var locacion_tipo_cambiar = $('.locacion_tipo_cambiar').val();
    $('.loc_est_actual').find('label.error').remove();
    $('.loc_est_cambiar').find('label.error').remove();

    if($('.locacion_tipo:checked').size()==0){
      valida= false;
      $('.loc_est_actual').append(html);
    /*}else if($('.locacion_tipo_cambiar:checked').size()==0){
      valida= false;
      $('.loc_est_cambiar').append(html);*/
    }else{
      if(confirm(esta_seguro_que_desa_cambiar_la_locacion_estudio+from+hasta+ to)){
        valida= true;
        if(locacion_tipo_cambiar=='1'){
          alerta = locacion;
        }else{
          alerta = estudio;
        }
        
      }else{
        valida= false;
      }
    }
  }else if(tipo=='4'){
    // CAMBIAR LOCACION Y SET
    var dia_noche = $('.dia_noche:checked').val();
 
    if(dia_noche=='1'){
     var dia_noche_cambiar=2; 
  
    }else{
     var dia_noche_cambiar=1;
    }
    console.log(dia_noche_cambiar);

   // var dia_noche_cambiar = $('.dia_noche_cambiar:checked').val();
    $('.d_n_actual').find('label.error').remove();
    $('.d_n_cambiar').find('label.error').remove();
    if($('.dia_noche:checked').size()==0){
      valida= false;
      $('.d_n_actual').append(html);
    /*}else if($('.dia_noche_cambiar:checked').size()==0){
      valida= false;
      $('.d_n_cambiar').append(html);*/
    }else{
      if(confirm(esta_seguro_que_desea_cambiar_dia_noche_de_las+from+" "+hasta+" "+ to)){
        valida= true;
        if(dia_noche_cambiar=='1'){
          alerta = dia;
        }else{
          alerta = noche;
        }
      }else{
        valida= false;
      }
    }

  }
  else if(tipo=='5'){
    // CAMBIAR LOCACION Y SET
    var int_ext = $('.int_ext:checked').val();
    if(int_ext=="1"){
     var int_ext_cambiar=2; 
    }else{
     var int_ext_cambiar=1;
    }
    //var int_ext_cambiar = $('.int_ext_cambiar:checked').val();
    $('.i_e_actual').find('label.error').remove();
    $('.i_e_cambiar').find('label.error').remove();
    if($('.int_ext:checked').size()==0){
      valida= false;
      $('.i_e_actual').append(html);
    /*}else if($('.int_ext_cambiar:checked').size()==0){
      valida= false;
      $('.i_e_cambiar').append(html);*/
    }else{
      if(confirm(esta_seguro_que_desa_cambiar_interior_exterior+from+hasta+ to)){
        valida= true;
        if(int_ext_cambiar=='1'){
          alerta = interior;
        }else{
          alerta = exterior;
        }
      }else{
        valida= false;
      }
    }
  }

  if(valida){
    if($('#herramientas_form').valid()){
      $('#wrapper_loader').fadeIn();
      datos= {
        id_produccion: $('.idproduccion').val(),
        capitulos_herramientas_from: $('#capitulos_herramientas_from').val(),
        capitulos_herramientas_to: $('#capitulos_herramientas_to').val(),
        escenas_herramientas_from: $('#escenas_herramientas_from option:selected').attr('data-numero_escena'),
        escenas_herramientas_to: $('#escenas_herramientas_to option:selected').attr('data-numero_escena'),
        set_busqueda:id_set_busqueda,
        dia_continuidad_actual:$('#dia_continuidad_actual').val(),
        dia_continuidad:$('#dia_continuidad').val(),
        location_actual:$('.locacion_select_cambio:checked').val(),
        set_actual:id_set_actual,
        location_cambio:$('.location:checked').val(),
        set_cambio:$('#set').val(),
        locacion_tipo:$('.locacion_tipo:checked').val(),
        locacion_tipo_cambiar:locacion_tipo_cambiar,
        dia_noche:$('.dia_noche:checked').val(),
        dia_noche_cambiar:dia_noche_cambiar,
        int_ext:$('.int_ext:checked').val(),
        int_ext_cambiar:int_ext_cambiar,
        tipo:tipo
      }
      $.ajax({
        type: "POST",
        url: site_url+"/herramientas/actualizar_escenas",
        data: datos,
        dataType: "json",
          success:function(data){
            var html = " Las escenas " + from + " al " + to + " fueron actualizadas por " + alerta + ", Total = " + data.cantidad ;
            $('#alertSection').fadeIn();
            $('#alertSection').append('<div class="alert-box success"><span id="alertText">'+html+'</span><a href="" class="close">&times;</a></div>')
           // $('#wrapper_loader').fadeOut(); 
          }
      });
      $('#wrapper_loader').fadeOut(); 
    }
  }
});


$('.personaje2').click(function(){
      var personaje2=$('.personaje2:checked').prop("checked",false);
    $(this).prop("checked",true);
});

$('.unificacion_personaje').click(function(){
  $('#alertUnificacion').fadeOut();
  $('#alertUnificacion').html('');
  var personaje1=$('.personaje1').val();
  var personaje_cambio = document.getElementsByName('personaje1[]');
  var personaje1='';
  $.each(personaje_cambio, function(i,set){
        if (set.checked) {
          personaje1+=set.value+',';
        }

  });
  //var personaje1=$('.personaje1').val();


  var personaje2=$('.personaje2:checked').val();

  if(personaje1.search(personaje2)<0){

    if(confirm(esta_seguro_de_unificar_el_actor+personaje1.toUpperCase()+al_actor+personaje2.toUpperCase()+esto_eliminara_al_Actor+personaje1.toUpperCase()+"?")){
                $('#wrapper_loader').fadeIn();
                      var html= "<label </label>";
                      var validar1=0;var validar2=0;
                      if(personaje1 == "" || personaje1==null){
                        $('.label1').fadeIn();
                        validar1=1;
                      }else{
                        $('.label1').fadeOut();
                      }
                      if(personaje2 == "" || personaje2==null){
                        $('.label2').fadeIn();
                        validar2=1;
                      }else{
                        $('.label2').fadeOut();
                      }

                      if(validar1==0 && validar2==0){
                        datos= {personaje1:personaje1,personaje2:personaje2,id_produccion: $('.idproduccion').val()}  
                        $.ajax({
                            type: "POST",
                            url: site_url+"/herramientas/unificar_personaje",
                            data: datos,
                            dataType: "json",
                              success:function(data){
                                if(data.validar1=='1' || data.validar2=='1' || data.validar3=='1'){
                                    if(data.validar1=='1'){
                                       $('.label1').html('Este personaje no existe');
                                       $('.label1').fadeIn();
                                    }
                                    if(data.validar2=='1'){
                                      $('.label2').html('Este personaje no existe');
                                       $('.label2').fadeIn();
                                    }
                                    if(data.validar3=='1'){
                                      var html='Estos elementos no tienen el mismo actor ';
                                      $('#alertUnificacion').fadeIn();
                                      $('#alertUnificacion').html('<div class="alert-box alert"><span id="alertText">'+html+'</span><a href="" class="close">&times;</a></div>')
                                    }
                                   $('#wrapper_loader').fadeOut();

                                      
                                }else{
                                  var html='La cantidad de escenas actualizadas es '+data.cantidad_escenas+', continuidad relacionada al personaje: '+data.cantidad_continuidad;
                                  $('#alertUnificacion').fadeIn();
                                  $('#alertUnificacion').html('<div class="alert-box success"><span id="alertText">'+html+'</span><a href="" class="close">&times;</a></div>')
                                  $('#wrapper_loader').fadeOut();

                                }
                              }  
                          }); 
                      //window.location.reload();
                      }else{
                        $('#wrapper_loader').fadeOut();
                      }
      }
  }else{
    alert(los_personajes_deben_ser_diferentes);
  }
  

})
/*FIN FUNCION EDICION DE ESCENAS HERRAMIENTAS*/

/*FUNCION CARGA SETS LOCACION ACTUAL HERRAMIENTAS*/
$('#location_actual').change( function(){
  var id_locacion= $('#location_actual').val();
  var sets='';
  $("#set_actual").css('background','lightblue');
  $("#set_actual").html('<option>'+cargando+'...</option>');
   datos= {id_locacion: id_locacion}
  $.ajax({
    type: "POST",
    url: site_url+"/escenas/set",
    data: datos,
    dataType: "json",
    success:function(data){
      var pathname = window.location.pathname;  
      if(pathname.search("plan_general/")>0 || pathname.search("escenas/index/")>0 || pathname.search("escenas/filtro_escenas")>0 || pathname.search("escenas/buscar_escenas")>0){
        sets = sets+"<option value=''>"+todos+"</option>";
      }
      if(data.set){
        if(data.set.length>1 && (pathname.search("escenas/crear_escenas")>0 || pathname.search("escenas/editar_escena/")>0)){
          sets=sets+"<option value=''>"+seleccione_un_set+"</option>";
        }
        $.each(data.set, function(i,set){
          sets=sets+"<option value='"+set.id+"'>"+set.nombre+"</option>";
        });
      } else{
        if(pathname.search("plan_general/")>0){
          var idproduccion = $('#idproduccion').val();
          datos= {idproduccion: idproduccion}
          $.ajax({
            type: "POST",
            url: site_url+"/plan_general/sets_produccion",
            data: datos,
            dataType: "json",
            success:function(data){
              $.each(data.set, function(i,set){
                sets+="<option value='"+set.id+"'>"+set.nombre+"</option>";
              });
              $("#set_actual").html(sets);
            }
          });
        }else{
          sets = sets+"<option value=''>"+no_hay_set_para_esta_locacion+"</option>";
        }
      }
      $("#set_actual").html(sets);
      $("#set_actual").css('background','');
    }
  });
});

$('#location_busqueda').change( function(){
  var id_locacion= $('#location_busqueda').val();
  var sets='';
 // $("#set_busqueda").css('background','lightblue');
  $("#set_busqueda").html('<option>'+cargando+'...</option>');
   datos= {id_locacion: id_locacion}
  $.ajax({
    type: "POST",
    url: site_url+"/escenas/set",
    data: datos,
    dataType: "json",
    success:function(data){
      var pathname = window.location.pathname;  
      if(pathname.search("plan_general/")>0 || pathname.search("escenas/index/")>0 || pathname.search("escenas/filtro_escenas")>0 || pathname.search("escenas/buscar_escenas")>0){
        sets = sets+"<option value=''>Todos</option>";
      }
      if(data.set){
        if(data.set.length>1 && (pathname.search("escenas/crear_escenas")>0 || pathname.search("escenas/editar_escena/")>0)){
          sets=sets+"<option value=''>"+seleccione_un_set+"</option>";
        }
        $.each(data.set, function(i,set){
          sets=sets+"<option value='"+set.id+"'>"+set.nombre+"</option>";
        });
      } else{
        if(pathname.search("plan_general/")>0){
          var idproduccion = $('#idproduccion').val();
          datos= {idproduccion: idproduccion}
          $.ajax({
            type: "POST",
            url: site_url+"/plan_general/sets_produccion",
            data: datos,
            dataType: "json",
            success:function(data){
              $.each(data.set, function(i,set){
                sets+="<option value='"+set.id+"'>"+set.nombre+"</option>";
              });
              $("#set_busqueda").html(sets);
            }
          });
        }else{
          sets = sets+"<option value=''>"+no_hay_set_para_esta_locacion+"</option>";
        }
      }
      $("#set_busqueda").html(sets);
      //$("#set_busqueda").css('background','');
    }
  });
});
/*FIN FUNCION CARGA SETS LOCACION ACTUAL HERRAMIENTAS*/

/*FUNCION IMPRESION PDF PLAN GENERAL*/
$('#pdf_plan_general').click(function(){
  $('#pdfForm').submit();
});
/*FIN FUNCION IMPRESION PDF PLAN GENERAL*/

/*FUNCION IMPRESION EXCEL PLAN GENERAL*/
$('#excel_plan_general').click(function(){
  $('#excelForm').submit();
});
/*FIN FUNCION IMPRESION EXCEL PLAN GENERAL*/

// FUNCION PDF PLAN DIARIO
$('#create_pdf').click(function(){
  window.open(site_url+'/pdf2/index/'+$(this).attr('data-idproduccion')+'/'+$(this).attr('data-idunidad')+'/'+$(this).attr('data-fechaunidad')+'/' + option_pdf);
});
// FIN FUNCION PDF PLAN DIARIO
  
// FUNCION AUTOFORMATO CIFRAS MONETARIAS ELEMENTOS
  $('.monto_personaje').autoNumeric('init'); 
// FIN FUNCION AUTOFORMATO CIFRAS MONETARIAS ELEMENTOS

// FUNCION AUTOFORMATO CIFRAS MONETARIAS PLAN PRODUCCION
 $('#presupuesto_principales').autoNumeric('init');
 $('#monto_figurante_extra').autoNumeric('init');
 $('#presupuesto_secundarios').autoNumeric('init');
 $('#presupuesto_figurante').autoNumeric('init');  
// FIN FUNCION AUTOFORMATO CIFRAS MONETARIAS PLAN PRODUCCION

  /*ALERTA GENERACION REPORTE DE ELEMENTOS*/
  $('.pdf_elementos').click(function(){
    alert(la_duracion_del_proceso_depende_de_la_cantidad);
    var carga = $(this).attr("data-carga");
    var categoria = $(this).attr("data-categoria");
    var idproduccion = $(this).attr("data-idproduccion");
    var limite_inferior = $('#capitulos_herramientas_from').val();
    var limite_superior = $('#capitulos_herramientas_to').val();
    var roles_personajes=$('.roles_personajes:checked').val();
    var roles_personajes = document.getElementsByName('roles_personajes[]');
    var roles='';
    $.each(roles_personajes, function(i,rol){
      if (rol.checked) {
        roles+=rol.value+'-';
      }

    });
    window.open(site_url+'/pdf2/pdf_elementos/'+idproduccion+'/'+carga+'/'+categoria+'/' + limite_inferior + '/' + limite_superior+'/'+roles);
  });
  $('.excel_elementos').click(function(){
    //alert("La duración del proceso depende de la cantidad de libretos x elementos");
    var carga = $(this).attr("data-carga");
    var categoria = $(this).attr("data-categoria");
    var idproduccion = $(this).attr("data-idproduccion");
    var limite_inferior = $('#capitulos_herramientas_from').val();
    var limite_superior = $('#capitulos_herramientas_to').val();
     var roles_personajes=$('.roles_personajes:checked').val();
    var roles_personajes = document.getElementsByName('roles_personajes[]');
    var roles='';
    $.each(roles_personajes, function(i,rol){
      if (rol.checked) {
        roles+=rol.value+'-';
      }

    });
    window.open(site_url+'/excel/excel_elementos/'+idproduccion+'/'+carga+'/'+categoria+'/' + limite_inferior + '/' + limite_superior+'/'+roles);
  }); 
  /*ALERTA GENERACION REPORTE DE ELEMENTOS*/ 
  // base_url('pdf2/pdf_elementos/').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria

  $('#search_sets').click(function(){
    $('#capitulos_for').val($('#capitulos_herramientas_from').val());
    $('#capitulos_to').val($('#capitulos_herramientas_to').val());
  });

  /*CARGA INICIAL DE ESCENAS (HERRAMIENTAS)*/
    $('#capitulos_herramientas_from').change();
    $('#capitulos_herramientas_to').change();
  /*FIN CARGA INICIAL DE ESCENAS (HERRAMIENTAS)*/ 

$('.print_elements_button').click(function(){
  $('#print_elements_section').show();
});


$('#seach_tableElements').keypress(function(){
    return !(window.event && window.event.keyCode == 13);
});

$('#seach_tableLocations').keypress(function(){
    return !(window.event && window.event.keyCode == 13);
});

$('#seach_tableSets').keypress(function(){
    return !(window.event && window.event.keyCode == 13);
});

 $('#mover_plan').click(function(){
      $('#plan_mover').fadeIn();
  });

 $('#cancel_mover_plan').click(function(){
      $('#plan_mover').fadeOut();
  });

  

$('#seach_tableElements').keyup(function(e){
  if (e.keyCode == 13) {
    var id_categoria = $('#id_categoria').val();
    var id_produccion = $('.id_produccion').val();
    var palabra = $(this).val();
    if(palabra!=""){
      $('#wrapper_loader').fadeIn();
         var desde = $('#desde_hidden').val();
        var hasta = $('#hasta_hidden').val();
        var id_locacion=$('#id_locacion_hidden').val()
        var categoria_valid = $('#id_categoria option:selected').attr('id');
        if(categoria_valid=="Personaje"){ 
          var cadena='';
          $('.roles_personajes').each(function(){
              if($(this).is(':checked')){
                cadena += $(this).val()+',';
              }
          });

          datos= {id_categoria: id_categoria,id_produccion:id_produccion,palabra:palabra,desde:desde,hasta:hasta,cadena:cadena,tipo:1,id_locacion:id_locacion}
        }else{
          datos= {id_categoria: id_categoria,id_produccion:id_produccion,palabra:palabra,desde:desde,hasta:hasta,tipo:0,id_locacion:id_locacion}   
        }
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/carga_tabla_nombre",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data!=""){
            $('#tabla_elementos').html(data);
            sortables_init();
            $('.alert-box').fadeOut();
            $(".datepicker").datepicker({
                dateFormat: 'dd-M-yy',
                showOn: "button",
                buttonImage: site_url2+"/images/calendar_white.png",
                buttonImageOnly: true
            });
          }else{
            $('.alert-box').fadeIn();
          }
          $('#wrapper_loader').fadeOut();
        }
      });
    }else{
      var tamano_inicial = parseInt($('#limite_actual').val()) ;
      $('#limite_actual').val(tamano_inicial-30);
      $('#cargar_elementos').click();
    }
  }

  // //if (e.keyCode == 13) {
  //  var palabra = $(this).val();
  
  //  $("#usersTable .element_name").each(function() {
  //   console.log($(this).html());
  //   console.log($(this).html().indexOf(palabra.toLowerCase()));
  //   console.log(palabra.toLowerCase());
  //     if($(this).html().toLowerCase().indexOf(palabra.toLowerCase(),0)<0){
  //       $(this).parent().hide();
  //     }else{
  //       $(this).parent().show();
  //     }
  //   });
  // //}
});


$('#cargar_elementos').click(function(){
  $('#wrapper_loader').fadeIn();
  var id_categoria = $('#id_categoria').val();
  var id_produccion = $('.id_produccion').val();
  var desde = $('#desde_hidden').val();
  var hasta = $('#hasta_hidden').val();
  var id_locacion=$('#id_locacion_hidden').val()
 
  
  var limite_escena=$('#limite_escena').val();
  var tamano_inicial = parseInt($('#limite_actual').val()) ;
  $('#limite_actual').val(tamano_inicial+30);
  $('#limite_escena').val(parseInt(limite_escena)+30);
  if(parseInt($('#limite_actual').val())>=parseInt($('#limite_elementos').val())){
    $('#cargar_elementos').hide();
    var tamano_final= $('#limite_elementos').val();
  }else{
    var tamano_final= parseInt($('#limite_actual').val())
  }
  //$("#usersTable").trigger("updateAll",true);
  //
  
  //
  var categoria_valid = $('#id_categoria option:selected').attr('id');
  
  if(categoria_valid=="Personaje"){ 
    var cadena='';
    $('.roles_personajes').each(function(){
        if($(this).is(':checked')){
          cadena += $(this).val()+',';
        }
    });

    datos= {id_categoria: id_categoria,id_produccion:id_produccion,desde:desde,hasta:hasta,tamano_inicial:tamano_inicial,tamano_final:tamano_final,limite_escena:limite_escena,cadena:cadena,tipo:1,id_locacion:id_locacion}
  }else{
    datos= {id_categoria: id_categoria,id_produccion:id_produccion,desde:desde,hasta:hasta,tamano_inicial:tamano_inicial,tamano_final:tamano_final,tipo:0,limite_escena:limite_escena,id_locacion:id_locacion}   
  }

  
  $.ajax({
    type: "POST",
    url: site_url+"/elementos/carga_tabla_ajax",
    data: datos,
    dataType: "json",
    success:function(data){
      if(data!=""){
        $('#tabla_elementos').append(data);
        sortables_init();
        $("#usersTable").trigger("update");
        $('.monto_personaje').autoNumeric('init'); 
        $('.alert-box').fadeOut();
      }else{
        $('.alert-box').fadeIn();
      }
      $('#wrapper_loader').fadeOut();

      $('.roles_personajes').each(function(){
        //roles_filter($(this).val(),$(this));
      });

    }
  });

 
});

function roles_filter(palabra,temporal){
  var cadena ="";
  $("#usersTable .rol_name").each(function() {
    if($(this).html().toLowerCase().indexOf(palabra.toLowerCase(),0)>=0){
      if(!temporal.is(':checked')){
        $(this).parent().hide();
        cadena += $(this).val()+',';
      }else{
        $(this).parent().show();
      }
    }
  });

  $('.roles_personajes').each(function(){
      if(!$(this).is(':checked')){
        cadena += $(this).val()+',';
      }
  });

  var id_produccion = $('.id_produccion').val();
  var desde = $('#capitulos_herramientas_from').val();
  var hasta = $('#capitulos_herramientas_to').val();
  datos= {cadena: cadena,id_produccion:id_produccion, desde:desde, hasta:hasta}
  $.ajax({
    type: "POST",
    url: site_url+"/elementos/contar_elementos_roles",
    data: datos,
    dataType: "json",
    success:function(data){
      if(data.cantidad){
        $('.escenasNoProducidas').html('Total Personaje:'+data.cantidad);
      }
    }
  });

}

$('#cargar_locaciones').click(function(){
  $('#wrapper_loader').fadeIn();
  var id_categoria = $('#id_categoria').val();
  var id_produccion = $('.id_produccion').val();
  var desde = $('#desde_hidden').val();
  var hasta = $('#hasta_hidden').val();
  var limite_escena=$('#limite_escena').val();
  var tamano_inicial = parseInt($('#limite_actual').val()) ;
  $('#limite_actual').val(tamano_inicial+30);
  $('#limite_escena').val(parseInt(limite_escena)+30);
  console.log($('#limite_elementos').val());
  if(parseInt($('#limite_actual').val())>=parseInt($('#limite_elementos').val())){
    $('#cargar_locaciones').hide();
    var tamano_final= $('#limite_elementos').val();
  }else{
    var tamano_final= parseInt($('#limite_actual').val())
  }

  datos= {id_categoria: id_categoria,id_produccion:id_produccion,desde:desde,hasta:hasta,tamano_inicial:tamano_inicial,tamano_final:tamano_final,limite_escena:limite_escena}
  $.ajax({
    type: "POST",
    url: site_url+"/elementos/carga_tabla_ajax_locaciones",
    data: datos,
    dataType: "json",
    success:function(data){
      if(data!=""){
        $('#tabla_elementos').append(data);
        $("#usersTable").trigger("update");
      }
      $('#wrapper_loader').fadeOut();
    }
  });
});

$('#cargar_sets').click(function(){
  $('#wrapper_loader').fadeIn();
  var id_categoria = $('#id_categoria').val();
  var id_produccion = $('.id_produccion').val();
  var desde = $('#desde_hidden').val();
  var hasta = $('#hasta_hidden').val();
  var tamano_inicial = parseInt($('#limite_actual').val()) ;
  $('#limite_actual').val(tamano_inicial+30);
  if(parseInt($('#limite_actual').val())>=parseInt($('#limite_elementos').val())){
    $('#cargar_locaciones').hide();
    var tamano_final= $('#limite_elementos').val();
  }else{
    var tamano_final= parseInt($('#limite_actual').val())
  }

  datos= {id_categoria: id_categoria,id_produccion:id_produccion,desde:desde,hasta:hasta,tamano_inicial:tamano_inicial,tamano_final:tamano_final}
  $.ajax({
    type: "POST",
    url: site_url+"/elementos/carga_tabla_ajax_sets",
    data: datos,
    dataType: "json",
    success:function(data){
      if(data!=""){
        $('#tabla_elementos').append(data);
        $("#usersTable").trigger("update");
      }
      $('#wrapper_loader').fadeOut();
    }
  });
});

$('#seach_tableLocations').keyup(function(e){

  if (e.keyCode == 13) {
    var id_produccion = $('.id_produccion').val();
    var palabra = $(this).val();
    if(palabra!=""){
      $('#wrapper_loader').fadeIn();
      datos= {id_produccion:id_produccion,palabra:palabra}
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/carga_tabla_nombre_locacion",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data!=""){
            $('#tabla_elementos').html(data);
            sortables_init();
            $('#wrapper_loader').fadeOut();
          }
        }
      });
    }else{
      var tamano_inicial = parseInt($('#limite_actual').val()) ;
      $('#limite_actual').val(tamano_inicial-30);
      $('#cargar_locaciones').click();
    }
  }
});

$('#seach_tableSets').keyup(function(e){
  if (e.keyCode == 13) {
   var palabra = $(this).val();
  
   $("#usersTable .element_name").each(function() {
    console.log($(this).html());
    console.log($(this).html().indexOf(palabra.toLowerCase()));
    console.log(palabra.toLowerCase());
      if($(this).html().toLowerCase().indexOf(palabra.toLowerCase(),0)<0){
        $(this).parent().hide();
      }else{
        $(this).parent().show();
      }
    });
    
  }
});

$('#imprimir_seleccion').click(function(){
  var idescenas=$('#idescenas').val();
  var id_produccion=$('#id_produccion_estado').val();
  var escenas = idescenas.split(",");
  
  var e="";
  for (var i = 0; i < escenas.length; i++) {
    e=e+escenas[i]+'-';
  };

  
  window.open(site_url+'/excel/excel_escenas_seleccionadas/'+id_produccion+'/'+e);

})

$('.numero_escena_crear').change(function(){
  console.log('dasd')
  var tempo = $('#numero_escena').parent();
  
    idcapitulo = $('#cap').val();
    numero_escena =  $('#numero_escena').val();
      datos2= {idcapitulo: idcapitulo,numero_escena:numero_escena}
        $.ajax({
          type: "POST",
          url: site_url+"/escenas/validacion_numero",
          data: datos2,
          async:false,
          dataType: "json",
          success:function(data){
            if(!data.existe){
              tempo.find('.error2').remove();
              tempo.append('<label class="error2" style="font-size: 11px; text-transform: inherit; margin-bottom: 10px; font-weight: normal; clear: both;color: #c60f13;">'+ya_existe_una_ecena_con_este_numero+'</label>');
              $('#numero_escena').focus();
              $('#numero_escena').val('');
            }else{
              tempo.find('.error2').remove();
            }
          }
        }); 
   
})

$('#usersTable').on('click','.user_produccion',function(){
  var id_user=$(this).attr('data-id')
  $('.producciones_usuario').hide()
   datos= {id_user: id_user}
    $.ajax({
      type: "POST",
      url: site_url+"/admin_user/user_produccion",
      data: datos,
      dataType: "json",
      success:function(data){
        $('#user_'+id_user).html(data.cadena_tabla);
      }
    });
  $('#user_'+id_user).show();

});


$('#usersTable').on('click','.cambiar_user',function(){
  var id_user=$(this).attr('data-iduser');
  var estado=$(this).attr('data-estado');
  var produccion=$('#productions_list').val();
  var estado_selec=$('#estado_user').val();
  if(produccion=='Todos'){
    produccion=0;
  }
  window.location.href=site_url+"/admin_user/cambiar_estado_user/"+id_user+"/"+estado+'/'+produccion+'/'+estado_selec;

});


  /*CARACTERES VALIDOS PARA IMPRESION < Y >*/

  $('.validcharprint').on('keypress', function(e){
    var key = e.keyCode || e.which;
    var char_key = String.fromCharCode(key);
    var regex = /[<>]/g;
    console.log(char_key);
    if(char_key.match(regex)){
      return false;
    }
  });

  $('#usersTable').on('change','.rol_personaje',function(){
     var val=$(this).attr('data-rol');
     if(val=="Extra"){
      alert(si_este_personaje_tiene_escenas_con_multiples_estra);
     }
  });



   $('.closeIcon').click(function(){
     $('.label-error-hiden').remove();
     $('#numero_escena').removeClass('error');
   });


  $('#wrapper').on('change','.tamsa_cambio',function(){
         var valor=parseInt($(this).val());
         var id_elemento=$(this).attr('data-elemento');
         var moton=parseInt($(this).attr('data-monto'));
         if(isNaN(valor)){
            alert(se_debe_ingresar_un_valor_valido);
            $(this).val('')
         }else{
              valor=valor*moton;
              datos ={valor:valor , mes: $('#month_selector').val(),id_elemento:id_elemento }
              $.ajax({
                type: "POST",
                url: site_url+"/herramientas/liquidacion_monto",
                data: datos,
                dataType: "json",
                  success:function(data){
                     $('.liquidacion_'+id_elemento).html(data.liquidacion)
                     $('.liquidacion_'+id_elemento).attr('data-valorliquidado',data.liquidacion_s) ;
                     var total=0
                     $(".total_liquidado").each(function() {
                      if($(this).attr('data-valorliquidado')){
                         total=parseInt($(this).attr('data-valorliquidado'))+total;
                         console.log(parseInt($(this).attr('data-valorliquidado')))
                      }
                         
                      });
                      
                      console.log(total)
                     var total_formant=format(total);
                     $('.acum_liquidacion').html(total_formant)
                  }
              }); 
         }
  });


   $('.eliminar_fotos').click(function(){

       if(confirm(esta_seguro_de_eliminar_todas_las_fotos)){
         $('#wrapper_loader').fadeIn();
           var idproduccio=$(this).attr('data-idproduccion');
           window.location.href=site_url+"/herramientas/eliminar_fotos_produccion/"+idproduccio;
       }

   });

});

/*FUNCION RECARGA AUTOMATICA PLAN PRODUCCION*/
idleTime = 0;
$(document).ready(function () {
  var pathname = window.location.pathname;
  if(pathname.search("/plan_produccion/index")>0){
    var idleInterval = setInterval("timerIncrement()", 1000); // 1 minute
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
  }
})
function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 60) { // 20 minutes
        window.location.reload();
    }
}
/*FIN FUNCION RECARGA AUTOMAYICA PLAN PRODUCCION*/

/*FUNCION VALIDAR EL NOMBRE DE UN CATEGORIA*/
function validar_categoria(){
  var palabra=$('#name_categoria').val();
  var tam=categorias.length;
  var cont=0;
  var i=0;
  while (cont<tam){
      var p = palabra.toLowerCase();
      var c =categorias[cont].toLowerCase();
       if(p==c){
         i++;
       }
      cont++;
  }
    if(i>0){
      alert(ya_existe_una_categoria_con_este_nombre);
      return false;
    }else{
      return true
    }
}


function calculo_tiempo_post(minutos1,segundos1,cuadros1){
      var cuadros = parseInt(0);
      var segundos = parseInt(0);
      var minutos = parseInt(0);
      var horas = parseInt(0);

      var minutos1=parseInt(minutos1);
      var segundos1=parseInt(segundos1);
      var cuadros1=parseInt(cuadros1);

      segundos = segundos1;

      cuadros = cuadros1;
     
     while(cuadros>=30){
          segundos+=1;
          cuadros= cuadros-30;
      }
      while(segundos>=60){
          minutos+=1;
          segundos= segundos-60;
      }

      minutos2 = minutos1 + minutos;
      
      if(cuadros>15){
        segundos=segundos+1;
      }

      if(minutos2<10){
        minutos2 = '0'+minutos2;
      }
      if(segundos<10){
        segundos = '0'+segundos;
      }
    
      if(minutos2==0){
        minutos2='00';
      }
      if(segundos==0){
        segundos='00';
      }
      if(cuadros==0){
        cuadros='00';
      }

      var tiempo = minutos2+":"+segundos;

      return tiempo;
    }

     function validation_confirmacion(){
        if(confirm(esta_seguro_que_desea_mover_este_plan_diario)){
          return true;
        }else{
          return false;
        }
    }

    function format(valor){
            var num = valor;
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/,'');
            return num;
          
}
/*FIN FUNCION VALIDAR EL NOMBRE DE UN CATEGORIA*/
// Inicio liquidacion