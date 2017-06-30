$(document).ready(function() {
	var pathname = window.location.pathname; 

	$('#honorarios').autoNumeric('init');

	/*SOLO MES Y AÑO EN DATEPICKER*/
		$("#start_month").datepicker({ 
	        dateFormat: 'M-yy',
	        changeMonth: true,
	        changeYear: true,
	        showOn: "button",
	        showButtonPanel: true,
	 		buttonImage: site_url+"/images/calendar_white.png",
	        buttonImageOnly: true,
	        onClose: function(dateText, inst) {  
	            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
	            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
	            $(this).val($.datepicker.formatDate('M-yy', new Date(year, month, 1)));
	        }
	    });

		
	    
	 
	    $("#start_month").focus(function () {
	        $(".ui-datepicker-calendar").hide();
	        $("#ui-datepicker-div").position({
	            my: "center top",
	            at: "center bottom",
	            of: $(this)
	        });    
	    });
    /* FIN SOLO MES Y AÑO EN DATEPICKER*/

	$('.fancybox').fancybox();
	var item_menu = $('.nav_casting li').length;

	$('.nav_casting li').css('cssText', 'width:'+100 / item_menu + '% !important');

	$('#crear_actor').validate();
	$('#crear_solicitud').validate();
	$('#guardar_comentario_solicitud').validate();
	$('#agregar_contrato').validate();
	

	$('.tabla_detalle_actor').tablesorter({cssInfoBlock : "tableSolicitudes"});

	$('.tableSolicitudes').tablesorter({
		headers: {
	      '.tabla_detalle_actor' : {
	        sorter: false
	      }
	    }
	});

	/*Botones Solicitud*/
	var width_button = parseFloat(100 / $(".btn_solicitud .button").length);
	$(".btn_solicitud .button").css('width', width_button + '%');


 	$('.pais_selector').change(function(){
 		var temporal = $(this);
 		var idpais = $(this).val();
 		var opciones="";
 		var select = $(this);
 		datos= {idpais: idpais}
		$.ajax({
			type: "POST",
			url: site_url+"casting/ciudades_pais",
			data: datos,
			dataType: "json",
			success:function(data){
				if(data.ciudades){
					$.each(data.ciudades, function(i,ciudad){
						opciones+="<option value='"+ciudad.id+"' ";
						if(pathname.indexOf('editar_actor')>0 || pathname.indexOf('anadir_actor')>0){
							if($('#id_ciudad_sociedad_hidden').val()!="" && $('#id_ciudad_sociedad_hidden').val()==ciudad.id && select.attr('id')!="pais"){
								opciones+= " selected ";
							}

							if (select.attr('id')=="pais") {
								if($('#ciudad_hidden').val()!="" && $('#ciudad_hidden').val()==ciudad.id){
									opciones+= " selected ";
								}
							};
						}

						opciones+="' >"+ciudad.nombre+"</option>";
					});
				}
				temporal.parent().parent().find('.ciudad_selector').html(opciones);
			}
		});
 	});

 	$('.society_validation').click(function(){
 		if($(this).val()=="1"){
 			$('#society_section').fadeIn();
 		}else{
 			$('#society_section').fadeOut();
 		}
 	});

 	$('.manager_validation').click(function(){
 		if($(this).val()=="1"){	
 			$('#contactar .disabled_item').each(function(){
 				$(this).removeAttr('disabled');
 			});
 			$('#manager_section').fadeIn();
 		}else{

 			$('#contactar .disabled_item').each(function(){
 				$(this).attr('disabled','disabled');
 			});
 			$("#contactar option").eq(1).prop("selected", true);
 			$('#manager_section').fadeOut();
 		}
 	});

 	$('.add_production').click(function(){
 		var html = $('#base_production').html();
 		var validacion =0;
 		$('#old_producitions input').each(function() {
			if($(this).val()==""){
				$(this).addClass('error');
				validacion=1;
			}else{
				$(this).removeClass('error');
			}
	    });

	    $('#old_producitions select').each(function() {
			if($(this).val()==""){
				$(this).addClass('error');
				validacion=1;
			}else{
				$(this).removeClass('error');
			}
	    });
	    if(validacion==0){
 			$(this).parent().parent().append(html);
 		}
 	});

 	$('#birthday_actor').datepicker({
      dateFormat: 'dd-M-yy',
      showOn: "button",
      buttonImage: site_url+"/images/calendar.gif",
      buttonImageOnly: true,
      changeMonth: true,
      changeYear: true,
      yearRange: '1830:2014',
      onSelect: function(){
        $(this).focus();
        calculoEdad();
      }
    });

    function calculoEdad () {
    	var fecha = $('#birthday_actor').val();
    	if (fecha!="") {
    		$.ajax({
				type: "POST",
				url: site_url+"casting/calculo_edad",
				data: {fecha:fecha},
				dataType: "json",
				success:function(data){
					if (data<18) {
						$('.society_validation_no').prop( "checked", true );
						$('.society_validation_si').prop( "checked", false );
						$('.society_validation').attr("disabled", true);
					}else{
						$('.society_validation').removeAttr("disabled");
					};
				}
			});
    	};
    }

    $('#inicio_personaje').datepicker({
		dateFormat: 'dd-M-yy',
	    showOn: "button",
	    buttonImage: site_url+"/images/calendar.gif",
	    buttonImageOnly: true,
	});

    $('#fin_personaje').datepicker({
		dateFormat: 'dd-M-yy',
	    showOn: "button",
	    buttonImage: site_url+"/images/calendar.gif",
	    buttonImageOnly: true,
	});


    $('.foto_button').click(function(){
    	$(this).parent().find('.foto_hidden').click();
    });

    $('.languaje').click(function(){
    	if($(this).val()=="9" && $(this).is(':checked')){
    		$('#other_languaje').fadeIn();
    	}else{
    		$('#other_languaje').fadeOut();
    		$('#other_languaje').val("");
    	}
    });

    // $('.skills').click(function(){
    // 	if($(this).val()=="3" && $(this).is(':checked')){
    // 		$('#other_skill').fadeIn();
    // 	}else{
    // 		$('#other_skill').fadeOut();
    // 		$('#other_skill').val("");
    // 	}
    // });


    /*FUNCION PARA VALIDAR DOCUMENTO ACTOR*/
    $('#documento_actor').focusout(function(){
    	$(this).parent().find('label.error').remove();
    	var documento = $('#documento_actor').val();
 		var tipo_documento = $('#tipo_documento').val();
 		var opciones="";
 		var id_actor = "";
 		if ($('#id_actor').length>0) {
 			var id_actor=$('#id_actor').val();
 		};
 		datos= {documento: documento, tipo_documento:tipo_documento, id_actor: id_actor }
		$.ajax({
			type: "POST",
			url: site_url+"casting/validar_documento",
			data: datos,
			dataType: "json",
			success:function(data){
				if(!data){
					$('#documento_actor').addClass('error');
					$('#documento_actor').parent().append('<label class="error">Ya existe un actor con este documento</label>');
				}
			}
		});
    });
    /*FIN FUNCION PARA VALIDAR DOCUMENTO ACTOR*/

    /*FUNCION VALIDAR EL DOCUMENTO AL HACER SUBMIT*/
    $('#actorInsert').click(function(){
    	$('#wrapper_loader').show();
    	$('#documento_actor').parent().find('label.error').remove();
    	var documento = $('#documento_actor').val();
 		var tipo_documento = $('#tipo_documento').val();
 		var opciones="";
 		var validacion=false;
 		var id_actor = "";
 		if ($('#id_actor').length>0) {
 			var id_actor=$('#id_actor').val();
 		};
 		datos= {documento: documento, tipo_documento:tipo_documento, id_actor: id_actor};
 		if ($('#crear_actor').valid()) {
			$.ajax({
				type: "POST",
				url: site_url+"casting/validar_documento",
				data: datos,
				dataType: "json",
				success:function(data){
					if(!data){
						$('#documento_actor').addClass('error');
						$('#documento_actor').parent().append('<label class="error">Ya existe un actor con este documento</label>');
						$('#documento_actor').focus();
						validacion=false;	
						$('#wrapper_loader').hide();
					}else{
						$('#crear_actor').submit();
						validacion=true;
					};
					return validacion;
				}
			});
		}else{
			$('#wrapper_loader').hide();
		}
		
    });
    /*FIN FUNCION VALIDAR EL DOCUMENTO AL HACER SUBMIT*/

    /*FUNCION FILTRAR ACTORES POR NOMBRE*/
    $('#filtro_general').click(function(){
    	var palabra = $('#palabra').val();
    	var cadena_html="";
    	var incremento=0;
    	if(palabra!=""){
    		datos= {palabra: palabra};
			$.ajax({
				type: "POST",
				url: site_url+"casting/filtro_actores_nombre",
				data: datos,
				dataType: "json",
				success:function(data){
					if (data.actores!="") {
						cadena_html+='<div id="grid-gallery" class="grid-gallery">';
						cadena_html+='<section class="grid-wrap"><ul class="grid"><li class="grid-sizer"></li>';
						$.each(data.actores, function(i,actor){
							cadena_html+= "<li><figure>";
							if(actor.ruta_foto!="" && actor.ruta_foto!=null){
								cadena_html+= "<div><img src='"+site_url+"/"+actor.ruta_foto+"'></div>";
							}else{
								cadena_html+= "<div><img src='"+site_url+"/imagescasting/default.jpg'></div>";
							}
							cadena_html+= "<figcaption>";
							cadena_html+= "<p>"+actor.nombre+" "+actor.apellido;
							cadena_html+= " "+actor.descripcion+"</p>";
							cadena_html+= "</figcaption>";
							cadena_html+= "</figure>";
							cadena_html+= "<span class='btn_eye'><a href='"+site_url+"casting/detalle_actor/"+actor.id+"'>Eye</a></span>";
							cadena_html+= "<span class='btn_balance' data-idactor='"+actor.id+"'><a href='#'>Balance</a></span>";
							cadena_html+='</li>';
						});
						cadena_html+= "</ul></section></div>";

						$('#resultado_actores').html(cadena_html);
						new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
					}else{
						$('#alert_filter').fadeIn();
					};
				}
			});
    	}
    });
    /*FIN FUNCION FILTRAR ACTORES POR NOMBRE*/

    /*FUNCION FILTRO ACTORES PRINCIPAL*/
    $('#filtro_principal').click(function(){
    	$('#alert_filter').fadeOut();
    	$('#resultado_actores').html("");
    	var cadena_html="";
    	var incremento = 0;
    	var nacionalidades = "";
    	var disponible = "";
    	var fotos = "";
    	var extranjero ="";
    	$('#nacionalidades_section .nacionalidad').each(function(){
    		if($(this).is(':checked')){
    			nacionalidades+=$(this).val()+',';
    		}
    	});

    	$('#disponible_section .disponible').each(function(){
    		if($(this).is(':checked')){
    			disponible=$(this).val();
    		}
    	});

    	$('#fotos_section .fotos').each(function(){
    		if($(this).is(':checked')){
    			fotos=$(this).val();
    		}
    	});

    	$('#extranjero_section .extranjero').each(function(){
    		if($(this).is(':checked')){
    			extranjero=$(this).val();
    		}
    	});

    	datos= {
    		nacionalidades: nacionalidades,
			tipo_documento: $('#tipo_documento').val(),
			documento: $('#documento').val(),
			nombre: $('#nombre').val(),
			apellido: $('#apellido').val(),
			manager: $('#manager').val(),
			genero: $('#genero').val(),
			altura_desde: $('#altura_desde').val(),
			altura_hasta: $('#altura_hasta').val(),
			peso_desde: $('#peso_desde').val(),
			peso_hasta: $('#peso_hasta').val(),
			edad_desde: $('#edad_desde').val(),
			edad_hasta: $('#edad_hasta').val(),
			color_tez: $('#color_tez').val(),
			color_ojos: $('#color_ojos').val(),
			idioma: $('#idioma').val(),
			proyectos_desempenados: $('#proyectos_desempenados').val(),
			personajes_desempenados: $('#personajes_desempenados').val(),
			rol: $('#rol').val(),
			disponible:disponible,
			fotos:fotos,
			extranjero:extranjero,
			ano_actor:$('#ano_actor').val()
    	}
    	$.ajax({
			type: "POST",
			url: site_url+"casting/filtro_actores",
			data: datos,
			dataType: "json",
			success:function(data){
				if (data.actores!="") {
					cadena_html+='<div id="grid-gallery" class="grid-gallery">';
					cadena_html+='<section class="grid-wrap"><ul class="grid"><li class="grid-sizer"></li>';
					$.each(data.actores, function(i,actor){
						cadena_html+= "<li><figure>";
						if(actor.ruta_foto!="" && actor.ruta_foto!=null){
							cadena_html+= "<div><img src='"+site_url+"/"+actor.ruta_foto+"'></div>";
						}else{
							cadena_html+= "<div><img src='"+site_url+"/imagescasting/default.jpg'></div>";
						}
						cadena_html+= "<figcaption>";
						cadena_html+= "<p>"+actor.nombre+" "+actor.apellido;
						cadena_html+= " "+actor.descripcion+"</p>";
						cadena_html+= "</figcaption>";
						cadena_html+= "</figure>";
						cadena_html+= "<span class='btn_eye'><a href='"+site_url+"casting/detalle_actor/"+actor.id+"'>Eye</a></span>";
						cadena_html+= "<span class='btn_balance' data-idactor='"+actor.id+"'><a href='#'>Balance</a></span>";
						cadena_html+='</li>';
					});
					cadena_html+= "</ul></section></div>";

					$('#resultado_actores').html(cadena_html);
					new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
					
				}else{
					$('#alert_filter').fadeIn();
				};
				$('#resumen_busqueda').html(data.resumen_busqueda);
				if(data.resumen_busqueda!="" && data.resumen_busqueda!='<div class="clr"></div>'){
					$('#limpiar_filtro_casting').fadeIn();
				}
				$('#resumen_busqueda').fadeIn();
				$('#filtro_general').fadeOut();
				$('#filtro_actores_section').slideToggle();
			}
		});
    });
    /* FIN FUNCION FILTRO ACTORES PRINCIPAL*/

    $('#special_contidions').click(function(){
    	if($(this).is(':checked')){
    		$('#conditions_field').fadeIn();
    	}else{
    		$('#conditions_field').fadeOut();
    	}
    });

    $('.resultado_actores').on('click','.btn_balance',function(){
    	var cont = 0;
    	$('.resultado_actores .compare').each(function(){
			cont++
		});

    	if($(this).attr('class').indexOf('compare')>0){
			$(this).removeClass('compare');
    	}else{
    		//if(cont<3){
    			$(this).addClass('compare');
    		//}
		}
		
		$('.resultado_actores .compare').each(function(){
			cont++
		});
		
		if(cont>1){
			$('#div_comparar_archivos').fadeIn();
		}else{
			$('#div_comparar_archivos').fadeOut();
		}
		return false;
    });


    $('.sugestions_contratation').change(function(){
    	if($(this).val()==1){
    		$('#sugestions_field').fadeIn();
    	}
    	else{
    		$('#sugestions_field').fadeOut();
    	}
    });

    $('#filtro_actores').click(function(){
    	$('#filtro_general').slideToggle();
    	$('#filtro_actores_section').slideToggle();
    	$(this).toggleClass('active_gray');
    });

    $('#cancelar_filtro').click(function(){
    	$('#filtro_general').slideToggle();
    	$('#filtro_actores_section').slideToggle();
    	$(this).toggleClass('active_gray');
    });

    $('#filtro_personajes').click(function(){
    	$('#filtro_presonajes_section').slideToggle();
    });



    $('.disponible').click(function(){
    	if($(this).val()==2){
    		$('#terminacion_proyecto_section').fadeIn();
    	}else{
    		$('#terminacion_proyecto_section').fadeOut();
    	}
    });

    // FUNCION CAMBIA DE IMAGEN ACTOR
    $('.g_scale').click(function(){
		$('#foto_principal').attr("src", $(this).attr("src"));    	
    });
    // FIN FUNCION CAMBIA DE IMAGEN ACTOR

    $('#id_manager').change(function(){
    	if ($(this).val()=="0") {
    		$(this).hide();
    		$('#nombre_manager').show();
    		$('.cancelar_manager').show();
    		$('#agregar_manager input').removeAttr('readonly');
    		$('#agregar_manager input').val('');
    	}else if($(this).val()!=""){
    		datos= { idmanager: $(this).val() };
    		$.ajax({
				type: "POST",
				url: site_url+"casting/buscar_manager",
				data: datos,
				dataType: "json",
				success:function(data){
					if (data.manager) {
						console.log(data.manager);
			    		$('#telefono_fijo_manager').val(data.manager[0].telefono_fijo);
			    		$('#telefono_movil_manager').val(data.manager[0].telefono_movil);
			    		$('#email_manager').val(data.manager[0].email);
			    	}
			    }
			});
    	}
    });

    $('#cancelar_manager').click(function(e){
    	e.preventDefault();
    	$('#id_manager').show();
		$('#nombre_manager').hide();
		$('.cancelar_manager').hide();
		$('#agregar_manager input').attr('readonly','');
		$("option", $('#id_manager')).eq(0).prop('selected', true);	
    });

    $('#production_selector').change(function(){
	    if($(this).val()!="" && $('#rol_selector').val()!=""){
	      cargar_actores();
	    }
	    $('.new_rol').change();
  	});

  	$('#rol_selector').change(function(){
	    if($(this).val()!="" && $('#production_selector').val()!=""){
	      cargar_actores();
	    }
  	});



  	$('#personajes_section').on('change', '.new_rol' ,function(){
	    if($(this).val()!="" && $('#production_selector').val()!=""){
	    	$('#wrapper_loader').show();
		    var idproduccion = $('#production_selector').val();
			var idrol = $(this).val();
			var html="";
			datos = {idproduccion:idproduccion,idrol:idrol };
			var now = $(this);
	  	    $.ajax({
		      type: "POST",
		      async:false,
		      url: site_url+"casting/buscar_personajes",
		      data: datos,
		      dataType: "json",
		      success:function(data){
		        if(data.personajes){
		        	html+="<option value=''>Seleccione una opción</option>";
		        	$.each(data.personajes, function(i,personaje){
		        		html+="<option value='"+personaje.id+"'";
		        		if (personaje.id == $('#id_elemento_hidden').val()) {
		        			html+=" selected ";
		        		};
		        		html+=">"+personaje.nombre+"</option>";
					});
		        	now.parent().parent().find('.new_personaje').html(html);
		        }else{
		        	html+="<option value=''>No hay actores</option>";
		        	now.parent().parent().find('.new_personaje').html(html);
		        };
		        $('#wrapper_loader').hide();
		      }
		    });
	    }
  	});

  	// FUNCION CARGAR ACTORES DE PRODUCCION Y ROL
  	function cargar_actores(){
  		$('#wrapper_loader').show();
	    var idproduccion = $('#production_selector').val();
	    var idrol = $('#rol_selector').val();
	    var html="";
	    datos = {idproduccion:idproduccion,idrol:idrol };
	    $.ajax({
	      type: "POST",
	      async:false,
	      url: site_url+"casting/buscar_personajes",
	      data: datos,
	      dataType: "json",
	      success:function(data){
	        if(data.personajes){
	        	html+="<option value=''>Seleccione una opción</option>";
	        	if (pathname.indexOf('editar_solicitud')>0) {
	        			html+="<option value='"+$('#id_elemento_hidden').val()+"' selected>"+$('#id_elemento_hidden').attr('data-nombre-elemento')+"</option>";
	        	};
	        	$.each(data.personajes, function(i,personaje){
	        		html+="<option value='"+personaje.id+"'";
	        		if (personaje.id == $('#id_elemento_hidden').val()) {
	        			html+=" selected ";
	        		}
	        		html+=">"+personaje.nombre+"</option>";
				});
	        	$('#personaje_selector').html(html);
	        }else{
	        	html+="<option value=''>No hay actores</option>";
	        	$('#personaje_selector').html(html);
	        };
	        $('#wrapper_loader').hide();
	      }
	    });

  	}


  	$('#crear_solicitud').on('change', '#personaje_selector', function(){
  		if($(this).val()!=""){
  			var element= $(this);
  			datos = {idpersonaje:$(this).val()};
  			$.ajax({
		      type: "POST",
		      async:false,
		      url: site_url+"casting/buscar_libretos_personaje",
		      data: datos,
		      dataType: "json",
		      success:function(data){
		      	if(data.libretos=="null" ||  data.libretos==null){
		      		data.libretos = "-";
		      		data.cantidad_libretos = 0;
		      	}
		      	element.parent().parent().find('.num_libretos').val(data.cantidad_libretos + ' ( Desglosados: ' +data.libretos + ' )');
		      	if (data.fecha_inicio) {
		      		$('#fecha_inicio').val(data.fecha_inicio[0].fecha_inicio);
		      	};
		      	//element.parent().parent().find('.list_libretos').val(data.libretos);
		      }
	    	});
	  	}
  	});

  	$('#crear_solicitud').on('change', '.new_personaje', function(){
  		if($(this).val()!="Seleccione una opción" && $(this).val()!=""){
  			var element= $(this);
  			datos = {idpersonaje:$(this).val()};
  			$.ajax({
		      type: "POST",
		      async:false,
		      url: site_url+"casting/buscar_libretos_personaje",
		      data: datos,
		      dataType: "json",
		      success:function(data){
		      	if(data.libretos=="null" ||  data.libretos==null){
		      		data.libretos = "-";
		      		data.cantidad_libretos = 0;
		      	}
		      	element.parent().parent().find('.num_libretos').val(data.cantidad_libretos + ' ( Desglosados: ' + data.libretos + ' )');
		      	element.parent().parent().find('.list_libretos').val(data.libretos);
		      }
	    	});
	  	}
  	});



  	

  	$('#generar_solicitud').click(function(){
  		if($('#fecha_inicio').val()!="" && $('#fecha_final').val()!=""){
	  		var d1 = $('#fecha_inicio').val().split("-");
			var dat1 = new Date($('#fecha_inicio').val());
			var dat2 = new Date($('#fecha_final').val());
			var fin = dat2.getTime() - dat1.getTime();
			var dias = Math.floor(fin / (1000 * 60 * 60 * 24))+1;
			var alerta = "";

			if(dias>15){
				// $('#eps_nombre').addClass('required');
				// $('#eps_activo').addClass('required');
				// $('#eps_clase').addClass('required');
				// if(($('#eps_documento_hidden').val()==""
				//    && $('#eps_documento').val()=="") 
				//    && (pathname.indexOf('crear_solicitud')>0
				//    || pathname.indexOf('editar_solicitud')>0)){
				// 	$('#eps_documento').addClass('required');
				// 	alerta+=" Pendiente Documento EPS \n";
				// }
				// $('#pensiones_nombre').addClass('required');
				// $('#pensiones_activo').addClass('required');
				// $('#pensiones_clase').addClass('required');
				// if(($('#pensiones_documento_hidden').val()==""
				// 	&& $('#pensiones_documento').val()=="")  
				// 	&& (pathname.indexOf('crear_solicitud')>0
				// 	|| pathname.indexOf('editar_solicitud')>0)){
				// 	$('#pensiones_documento').addClass('required');
				// 	alerta+=" Pendiente Documento Fondo pensiones \n";
				// }

				// $('#arl_nombre').addClass('required');
				// $('#arl_activo').addClass('required');
				// $('#arl_clase').addClass('required');
				// if(($('#arl_documento_hidden').val()=="" 
				// 	&& $('#arl_documento').val()=="" )
				// 	&& (pathname.indexOf('crear_solicitud')>0
				// 	|| pathname.indexOf('editar_solicitud')>0)){
				// 	$('#arl_documento').addClass('required');
				// 	alerta+=" Pendiente Documento ARL ";
				// }

			} else {
				$('#eps_nombre').removeClass('required');
				$('#eps_activo').removeClass('required');
				$('#eps_clase').removeClass('required');
				$('#eps_documento').removeClass('required');

				$('#pensiones_nombre').removeClass('required');
				$('#pensiones_activo').removeClass('required');
				$('#pensiones_clase').removeClass('required');
				$('#pensiones_documento').removeClass('required');

				$('#arl_nombre').removeClass('required');
				$('#arl_activo').removeClass('required');
				$('#arl_clase').removeClass('required');
				$('#arl_documento').removeClass('required');
			}; 
  		}
  		if($('#crear_solicitud').valid() && alerta==""){
  			$('#valida_completo').val('1');
  			$('#wrapper_loader').show();
  		}else{
  			return false;
  		}
  	});

	$('#guardar_solicitud').click(function(){
		$('#wrapper_loader').show();
		$("#crear_solicitud").validate().cancelSubmit = true;
	    $("#crear_solicitud").submit();
	    return false;
	});


	//VERIDFICACION DE DIAS CONTRATO
	$('.fecha_inicio_casting').datepicker( {
		dateFormat: 'dd-M-yy',
	    showOn: "button",
	    buttonImage: site_url+"/images/calendar_white.png",
	    buttonImageOnly: true,
        onSelect: function(date) {
            valida_dias();
        }
    });

    $('.fecha_final_casting').datepicker( {
		dateFormat: 'dd-M-yy',
	    showOn: "button",
	    buttonImage: site_url+"/images/calendar_white.png",
	    buttonImageOnly: true,
        onSelect: function(date) {
            valida_dias();
        }
    });

    function valida_dias(){
    	if($('.fecha_inicio_casting').val()!="" && $('.fecha_final_casting').val()!=""){
	    	if(getNumeroDeDias()>15){
	    		$('#load_documents_solicitud').fadeIn();
    		}else{
    			$('#load_documents_solicitud').fadeOut();
    		}
    	}
    }

    /*CARGA DOCUMENTOS ACTOR EN SOLICITUD*/
    if(pathname.indexOf('editar_solicitud')>0){
    	valida_dias();
    }
    /*CARGA DOCUMENTOS ACTOR EN SOLICITUD*/	
    
  	
  	function getNumeroDeDias(){
		var d1 = $('#fecha_inicio').val().split("-");
		var dat1 = new Date($('#fecha_inicio').val());
		var dat2 = new Date($('#fecha_final').val());
		var fin = dat2.getTime() - dat1.getTime();
		var dias = Math.floor(fin / (1000 * 60 * 60 * 24))+1;
		return dias;
	}

	$('#add_personaje').click(function(){
		var html="";
		html = $('#initial_personaje').html();
		html = html.replace(/name-temp/gi,"name");
		html = html.replace(/class-temp/gi,"class");

		$('#personajes_section').append('<div class="new_personaje">'+html+'</div>');
	});

	$('#personajes_section').on('click', '.eliminar_personaje', function(){
		$(this).parent().remove();
	});

	$('#aprobar_solicitud').click(function(){
		var estado_actual = $('#estado_solicitud_span').html().toUpperCase();
		var estado_cambio = "SOLICITUD COMPLETA";
		var caso_especial = parseInt($('#caso_especial').val());
		var otro_si = "";
		if ($('#solicitud_tipo').val()==2) {
			otro_si = " / OTRO SI";
		};
		console.log(estado_actual.trim() +'--'+ ("SOLICITUD COMPLETA"+otro_si));

		if (estado_actual.trim() == ("GENERANDO SOLICITUD"+otro_si)) {
			estado_cambio = "SOLICITUD COMPLETA";
		};

		if (estado_actual.trim() == ("SOLICITUD COMPLETA"+otro_si)) {
			estado_cambio = "APROBADA - PRODUCCIÓN";
		};

		if (estado_actual.trim() == "APROBACIÓN EXTRANJERO"+otro_si) {
			estado_cambio = "SOLICITUD COMPLETA";
		};
		if (estado_actual.trim() == "APROBADA - PRODUCCIÓN"+otro_si) {
			if (caso_especial==0) {

				estado_cambio = "GENERANDO CONTRATO";
			}else{
				if (caso_especial==1) {
					estado_cambio = "VERIFICANDO SOLICITUD JURIDICA Y FINANZAS";
				};
				if (caso_especial==2) {
					estado_cambio = "APROBADA JURIDICA";
				};
				if (caso_especial==3) {
					estado_cambio = "APROBADA FINANZAS";
				};
			};

		};

		if (estado_actual.trim() == "GENERANDO CONTRATO"+otro_si) {
			estado_cambio = "PROCESO DE FIRMA - RECOLECCIÓN DE DOCUMENTOS";
		};
		if (estado_actual.trim() == "PROCESO DE FIRMA"+otro_si) {
			estado_cambio = "RECOLECCIÓN DE DOCUMENTOS";
		};
		if(estado_actual.trim() == "RECOLECCIÓN DE DOCUMENTOS"+otro_si){
			estado_cambio = "SOLICITUD TERMINADA";
		};

		if ( (estado_actual.trim() == "RECHAZADA - SOLICITUD INCOMPLETA"+otro_si
			|| estado_actual.trim() == "RECHAZADA - ESCALAR A JURIDICA"+otro_si
			|| estado_actual.trim() =="RECHAZADA - OTROS"+otro_si) && $('#situacion_especial').html().toUpperCase().trim()=="EXTRANJERO") {
			estado_cambio = "APROBACION EXTRANJERO";
		};

		if(caso_especial!=4){
			var comfirmacion = "Esta seguro que desea aprobar la solicitud "+ $('#id_solicitud_span').html().trim() + " del estado " + estado_actual.trim() + ' a ' + estado_cambio.trim();
			if (confirm(comfirmacion)) {
				$('#wrapper_loader').show();
			    datos = {idsolicitud:$(this).attr('data-solicitud'), validacion: 0 };
			    $.ajax({
					type: "POST",
					async:false,
					url: site_url+"casting/aprobar_solicitud",
					data: datos,
					dataType: "json",
					success:function(data){
						//window.location.reload();
					}
				});
			};
		}else{
			$('#ordenarWrap').fadeIn();
		}
	});
	

	$("#aprobar_admin").click(function(){
		var valor = "";
		$('#wrapper_loader').show();
		$('#ordenarWrap .estado_admin').each(function(){
			if ($(this).is(':checked')) {
				valor = $(this).val();
			}
		});
		if(valor!=""){
			datos = {idsolicitud:$(this).attr('data-solicitud'), validacion:valor };
		    $.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/aprobar_solicitud",
				data: datos,
				dataType: "json",
				success:function(data){
					$('#wrapper_loader').hide();
					window.location.reload();
				}
			});
		}
	});

	$('.aprobar_solicitud').click(function(){
		var estado_actual = $(this).parent().parent().find('.estado_solicitud_cell').html().toUpperCase();
		var idsolicitud = $(this).attr('data-solicitud');
		var number_solicitud = $(this).parent().parent().find('.span_solicitud').html().toUpperCase();
		var otro_si = "";
		if ($('#solicitud_tipo').val()==2) {
			otro_si = " / OTRO SI";
		};

	    if (estado_actual == "GENERANDO SOLICITUD"+otro_si) {
			estado_cambio = "SOLICITUD COMPLETA";
		};
		if (estado_actual == "SOLICITUD COMPLETA"+otro_si) {
			estado_cambio = "APROBADA - PRODUCCIÓN";
		};

		if (estado_actual == "APROBACIÓN EXTRANJERO"+otro_si) {
			estado_cambio = "SOLICITUD COMPLETA";
		};
		if (estado_actual.trim() == "APROBADA - PRODUCCIÓN"+otro_si) {
			estado_cambio = "GENERANDO CONTRATO";
		};

		if (estado_actual == "GENERANDO CONTRATO"+otro_si) {
			estado_cambio = "PROCESO DE FIRMA";
		};
		if (estado_actual == "PROCESO DE FIRMA"+otro_si) {
			estado_cambio = "RECOLECCIÓN DE DOCUMENTOS";
		};
		if(estado_actual == "RECOLECCIÓN DE DOCUMENTOS"+otro_si){
			estado_cambio = "SOLICITUD TERMINADA";
		};

		if ( (estado_actual == "RECHAZADA - SOLICITUD INCOMPLETA"+otro_si
			|| estado_actual == "RECHAZADA - ESCALAR A JURIDICA"+otro_si
			|| estado_actual =="RECHAZADA - OTROS"+otro_si) && $('#situacion_especial').html().toUpperCase().trim()=="EXTRANJERO") {
			estado_cambio = "APROBACION EXTRANJERO"+otro_si ;
		};

		var comfirmacion = "Esta seguro que desea aprobar la solicitud "+ number_solicitud + " del estado " + estado_actual + ' a ' + estado_cambio;
		if (confirm(comfirmacion)) {
			$('#wrapper_loader').show();
		    datos = {idsolicitud:idsolicitud, validacion: 0 };
		    $.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/aprobar_solicitud",
				data: datos,
				dataType: "json",
				success:function(data){
					window.location.reload();
				}
			});
		}else{
			return false;
		};
	});

	$('.rechazar_solicitud_button_up').click(function () {
		$('#rechazar_section').show();
		$('html, body').animate({
	        scrollTop: $("#rechazar_section").offset().top
	    }, 1000);
	});

	$('.rechazar_solicitud_button').click(function(){
		$('#rechazar_section').fadeIn();
	});

	$('.rechazar_solicitud').click(function(){
		if($('#rechazo_razon').val()==""){
			$('#rechazo_razon').addClass('error');
		}else{
			var id_estado = "";
			$('#rechazar_section .estado_cambio_solicitud').each(function(){
				if($(this).is(':checked')){
					id_estado = $(this).val();
				}
			});

			var estado_actual = $('#estado_solicitud_span').html().toUpperCase();
			var estado_cambio = "";

			if(estado_actual == "SOLICITUD COMPLETA"){
				estado_cambio = "RECHAZADA PRODUCCIÓN";
			};

			//if(estado_actual == "GENERANDO CONTRATO" ){
				if(id_estado==8){
					estado_cambio = "RECHAZADA - ESCALAR A JURÍDICA";
				}
				if(id_estado==7){
					estado_cambio = "RECHAZADA - SOLICITUD INCOMPLETA";
				}
				if(id_estado==9){
					estado_cambio = "RECHAZADA - OTROS";
				}

				if (id_estado==5) {
					estado_cambio = "GENERANDO CONTRATO";
				};
			//};

			if (estado_actual.indexOf('-')>0) {
				estado_actual = estado_actual.split('-');
				estado_actual = estado_actual[0].trim()+' - '+estado_actual[1].trim();
			};


			var confirmacion = "Esta seguro que desea rechazar la solicitud " + $('#id_solicitud_span').html().trim() + " del estado " + estado_actual.trim() + " al estado " + estado_cambio.trim();

			if(id_estado!=""){
				if(confirm(confirmacion)){
					$('#wrapper_loader').show();
			    	datos = {idsolicitud:$(this).attr('data-solicitud'), idestado: id_estado, razon: $('#rechazo_razon').val() };
				    $.ajax({
						type: "POST",
						async:false,
						url: site_url+"casting/rechazar_solicitud",
						data: datos,
						dataType: "json",
						success:function(data){
							window.location.reload();
						}
					});
				}
			}else{
				alert("Seleccione una opción");
			}
		}
	});

	$('#more_link').click(function(e){
		e.preventDefault();
		var value_text = $(this).prev('.link_video').val();
		$(this).prev('.link_video').val(null);
		var html = '<div class="content-link-video"><label for="">videos:</label><input type="text" name="links_videos[]" class="link_video" value="'+value_text+'"><a href="#" id="" class="button btn_orange two delete_link">ELIMINAR</a></div>';
		
		$('#links_videos').append(html);
	});

	$('body').on('click','.delete_link', function(e){
		e.preventDefault();
		$(this).parent().remove();
	});
	$('#produccion_iestado').change(function(){
		if ($(this).val('2')) {
			$('.inactive').show();
		}else if ($(this).val('1')){
			$('.inactive').hide();
		};
	});

	$('#no_activas').change(function(){
		if ($('.inactive').length>0) {
			if ($(this).val('2')) {
				$('.inactive').show();
			}else if ($(this).val('1')){
				$('.inactive').hide();
			};
		};
	});

	$('.estado_personaje_fill').click(function(){
		$('.checksFilter .estado_personaje').each(function(){
			if ($(this).is(':checked')) {
				$('.'+$(this).attr('data-clase')).parent().show();
			}else{
				$('.'+$(this).attr('data-clase')).parent().hide();
			};
		});
	});

	$('#buscar_personaje').keyup(function(e){
	  	if (e.keyCode == 13) {
	  		var palabra = $(this).val();
	  		$('#tbody_personajes tr').each(function(){

	  			var nombre_p = $(this).find('.nombre_personaje');
	  			valida = false;
	  			console.log(nombre_p.html());
	  			if(nombre_p.html().toUpperCase().indexOf(palabra.toUpperCase())>=0){
	  				valida = true;
	  			}

	  			var nombre_a =  $(this).find('.nombre_actor');
	  			console.log(nombre_a);
	  			if((nombre_a.html() === undefined) == false){
	  				if(nombre_a.html().toUpperCase().indexOf(palabra.toUpperCase())>=0){
	  					valida = true;
	  				}
	  			}

	  			var nombre_s = $(this).find('.solicitud_personaje');
	  			if((nombre_s.html() === undefined) == false){
	  				if(nombre_s.html().toUpperCase().indexOf(palabra.toUpperCase())>=0){
	  					valida = true;
	  				}
	  			}

	  			if(valida){
	  				$(this).show();
	  			}else{
	  				$(this).hide();
	  			}
	  			
	  		});
	  	}
	});


	$('#filtro_personajes_button').click(function(){
		var idproduccion = $('#the_produccion2').val();
		if(idproduccion==""){
			idproduccion = null;
		};
		var rol = $('#rol').val();
		if (rol=="") {
			rol = null;
		};
		var date_from = $('#inicio_personaje').val();
		if(date_from==""){
			date_from= null;
		};
		var date_to = $('#fin_personaje').val();
		if(date_to==""){
			date_to = null;
		}

		window.location.href=site_url+'casting/personajes/'+idproduccion+'/'+rol+'/'+date_from+'/'+date_to;
	});

	$('.row_personaje').click(function(){
		var idpersonaje = $(this).attr('data-idpersonaje');
		$('#bitacora_personaje_'+idpersonaje).slideToggle();
	});


	$('.select_actor').change(function(){
		if($(this).val()!=""){
	    	datos = {idactor:$(this).val()};
			$.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/busqueda_actor",
				data: datos,
				dataType: "json",
				success:function(data){
					$('#nacionalidad_hidden').val(data[0].nacionalidad);
					$('#identificacion_hidden').val(data[0].documento);
					$('#direccion_hidden').val(data[0].direccion);
					$('#ciudad_hidden').val(data[0].nombre_ciudad);
					$('#pais_hidden').val(data[0].nombre_pais);
					$('#telefono_fijo_hidden').val(data[0].telefono_fijo);
					$('#telefono_movil_hidden').val(data[0].telefono_movil);
					if(data[0].nit_sociedad!="" && data[0].nit_sociedad!="null" && data[0].nit_sociedad!=null){
						$('#nit_hidden').val(data[0].nit_sociedad);
						$('#razon_hidden').val(data[0].razon_social_sociedad);
						$('#telefono_fijo_sociedad_hidden').val(data[0].telefono_fijo_sociedad);
						$('#telefono_movil_sociedad_hidden').val(data[0].telefono_movil_sociedad);
						$('#direccion_sociedad_hidden').val(data[0].direccion_sociedad);
						$('.datos_sociedad').fadeIn();
					}else{
						$('.datos_sociedad').hide();
					}
					$('.datos_actor').fadeIn();
					$('#load_documents_solicitud').html(data[0].cadena_documentos);
					if (data[0].edad<18) {
						$('.clausula').attr('disabled','disabled');
					}else{
						$('.clausula').removeAttr('disabled');
					};
				}
			});
			
		}else{
			$('.datos_actor').hide();
		}
	});

	$('#convenciones_solicitud').click(function(){
		$('#convenciones_solicitud_section').slideToggle();
	});

	$('#rechazo_boton').click(function(){
		$('#rechazo_section').slideToggle();
	});

	//RECHAZAR SOLICITUD EN AGREGAR CONTRATO
	$('.rechazar_solicitud_boton').click(function(){
		if($('#rechazo_razon').val()==""){
			$('#rechazo_razon').addClass('error');
		}else{
			var estado_solcitud = "";
			$('#rechazo_section .estado_solcitud').each(function(){
				if($(this).is(':checked')){
					estado_solcitud = $(this).val();
				}
			});

			if(confirm("Esta seguro que desea rechazar la solicitud")){
		    	datos = {idsolicitud:$('#id_solcitud').val(), idestado:estado_solcitud, razon: $('#rechazo_razon').val() };
			    $.ajax({
					type: "POST",
					async:false,
					url: site_url+"casting/rechazar_solicitud",
					data: datos,
					dataType: "json",
					success:function(data){
						window.location.href = site_url + "casting/solicitudes" ;
					}
				});
			}
		}
	});


	//FILTRO GEGENRAL DE SOLICITUDES
	$('#nombre_solicitud').keyup(function(e){
		if (e.keyCode == 13) {
	  		var palabra = $(this).val();
	  		$('#tbody_solicitudes tr.row_solicitud').each(function(){
	  			var nombre_p = $(this).find('.nombre_elemento');
	  			valida = false;
	  			console.log(nombre_p.html());

	  			if (nombre_p.html()!==undefined) {
		  			if(nombre_p.html().toUpperCase().indexOf(palabra.toUpperCase())>=0){
		  				valida = true;
		  			}
	  			};

	  			var nombre_a =  $(this).find('.nombre_actor');
	  			if (nombre_a.html()!==undefined) {
		  			if(nombre_a.html().toUpperCase().indexOf(palabra.toUpperCase())>=0){
		  				valida = true;
		  			}
	  			};

	  			if(valida){
	  				$(this).show();
	  			}else{
	  				$(this).hide();
	  			}
	  		});
	  	}
	});

	$('#filtro_solicitudes').click(function(){
		$('#filtro_solicitudes_section').slideToggle();
	});

	$('.cambiar_foto_actor').click(function(e){

		e.preventDefault();
		console.log($(this).parent().parent().find('.foto_remplazo'));
		$(this).parent().parent().find('.foto_remplazo').click();
	});

	$('.eliminar_foto_actor').click(function(){
		$(this).parent().remove();
	});

	// $('body').on('click', '.visa_vigencia', function () {
	// 	//$(this).datepicker("show"); 
	// 	$(this).datepicker({
	//       dateFormat: 'dd-M-yy',
	//       showOn: "button",
	//       buttonImage: site_url+"/images/calendar.gif",
	//       //buttonImageOnly: true,
	//       changeMonth: true,
	//       changeYear: true,
	//       yearRange: '1830:2014',
	//       onSelect: function(){
	//         $(this).focus();
	//       }
	//     });
	// });

	$('#documentos_section').on('click','.agregar_documento',function(){
		var botones = $('.load_content .agregar_documento');
		var target = $('.clone_row');
		var clone = target.clone();
		target.removeClass('clone_row');
		clone.find('.load_content').empty();
		/*DESABILITA TIPO ANTERIOR */
		var indice = $(this).parent().parent().parent().find('#documentos_select')[0].selectedIndex;
		if(indice!=6 && indice!=7 && indice!=5){
			clone.find('#documentos_select option:eq('+indice+')').attr('disabled', 'disabled');
		}
		/*FIN DESABILITA TIPO ANTERIOR*/
		$('#documentos_section').append(clone);
		$('#documentos_section').append('<div class="clr"></div>');
	});	



	var indice_doc = 0;
	$('#documentos_section').on('click','.agregar_documento_solicitud',function(){
		++indice_doc;
		var clone = $('.load_content_base').clone();
		console.log(clone);
		var boton = clone.find('.agregar_documento_solicitud');
		boton.addClass('eliminar_documento_solicitud btn_orange');
		boton.removeClass('agregar_documento_solicitud');
		boton.text('eliminar');
		$('#row_documents').append('<div>'+clone.html().replace(/_base/gi, "").replace(/_indice/gi, "_"+indice_doc)+'<div>');
	});

	$('#documentos_section').on('click','.eliminar_documento_solicitud',function(){
		$(this).parent().parent().remove();
	}); 

	

	$('#documentos_section').on('click','.eliminar_documento',function(){
		$(this).parent().parent().parent().remove();
	});

	$('#documentos_section').on('click','.eliminar_documento_cargado',function(){
		$(this).parent().parent().remove();
	});



	
	$('#documentos_section').on('change','#documentos_select',function(){
		var parent = $(this).parent().parent().find('.load_content');

		$('.load_content button').removeClass('agregar_documento');
		$('.load_content button').addClass('btn_orange');
		$('.load_content button').addClass('eliminar_documento');
		$('.load_content button').html('eliminar');
		var html_tmp = $('#document_'+$(this).val()).html().replace(/_base/g,'');
		parent.html(html_tmp);
		$("#documentos_section").find(".visa_vigencia").each(function () {
            bindDatePicker(this);
        });
	});

	function bindDatePicker (element) {
	    $(element).datepicker({
	      dateFormat: 'dd-M-yy',
	      showOn: "button",
	      buttonImage: site_url+"/images/calendar.gif",
	      buttonImageOnly: true,
	      onSelect: function(){
	        $(this).focus();
	      }
	    });
	};

	$('.visa_vigencia').datepicker({
	      dateFormat: 'dd-M-yy',
	      showOn: "button",
	      buttonImage: site_url+"/images/calendar.gif",
	      buttonImageOnly: true,
	      onSelect: function(){
	        $(this).focus();
	      }
	    });



	$('.foto_remplazo').change(function(evt){
		console.log(evt.target.files);
		var imagen = $(this).parent().find('img');

		var files = evt.target.files;
		if (files[0]['type']=="image/jpeg" || files[0]['type']=="image/jpg" || files[0]['type']=="image/png"){

            var reader = new FileReader();
            var status = document.getElementById('status');
           
           
            reader.onload = (function(theFile) {
                return function(e) {
                	console.log(e.target.result);
                 	imagen.attr( "src", e.target.result);
                };
            })(files[0]);
		 	reader.readAsDataURL(files[0]);
        }else{
	       	alert('El formato del archivo no es permitido');
		}	
	});

	document.addEventListener('change', function(e){
		if(pathname.indexOf('casting/')>0){
    		archivo_casting(e.target.files,e.target.id);
    	}
    });

	

    function archivo_casting(evt,id) {
    	cont_tomar=0;
    	if(id=="fotos_actor"){
    		$('#loader_gif').fadeIn();
    	    $('#list_fotos_casting').append('<li>Cargando...</li>');
            var files = evt;
            console.log('ID...');
            console.log(id);
	        var id2=id;
	        if(files){
	        	id = id.split("_");
	        	imagenes_elminadas = new Array();
	        	if (files['0']['type']=="image/jpeg" || files['0']['type']=="image/jpg" || files['0']['type']=="image/png"){
		        	$('#list_fotos_casting').html('');
		        	var cont =0;
			        for (var i = 0, f; f = files[i]; i++) {
			        	if(i>3){
			        		break;
			        	}
			            if (!f.type.match('image.*')) {
			                continue;
			            }
			            var reader = new FileReader();
			            var status = document.getElementById('status');
			           
			           
			            reader.onload = (function(theFile) {
			                return function(e) {
                                //var html='<li class="imagen_set_'+cont+'"><div class="div_thumb"><img class="thumb" id="imagen64"  width="50" src="'+e.target.result+'" title="'+escape(theFile.name)+'"/><div class="eliminar_foto_actor btn_naranja" data-foto="'+cont+'" data-nameimagen="'+escape(theFile.name)+'">eliminar</div><label><input type="radio" name="portada">Portada</label></div></li>';
                                cont++;
                                $('#'+id).parent().find('.foto > img').attr('src',e.target.result).attr('title',escape(theFile.name));
                                $('#loader_gif').fadeOut();
			                };
			            })(f);
						reader.readAsDataURL(f);
		        	}
		        }else{
	                $('#btn_subir_foto_'+id['3']).val('');
		        	alert('El formato del archivo no es permitido');
		        }	
		     } 
    	}else{
    	    var files = evt;
	        var id2=id;
	        if(files){
	        	$('#loader_gif').fadeIn();
    	   		 //$('.list_fotos_sets').append('<li>Cargando...</li>');
	        	id = id.split("_");
	        	if (files['0']['type']=="image/jpeg" || files['0']['type']=="image/jpg" || files['0']['type']=="image/png"){
		        	//$('.list_fotos_sets').html('');
			        for (var i = 0, f; f = files[i]; i++) {
			            if (!f.type.match('image.*')) {
			                continue;
			            }
			            var reader = new FileReader();
			            reader.onload = (function(theFile) {
			                return function(e) {
			                    /*document.getElementById('uploading_'+id['3']).innerHTML = ['<img class="thumb" id="imagen64" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
			                    $('.imagen').val(e.target.result);*/
			                     var html='<div class="div_thumb numero_foto_'+cont_tomar+'"><img class="thumb" id="imagen64"  width="50" src="'+e.target.result+'" title="'+escape(theFile.name)+'"/><div class="eliminar_foto_camara btn_naranja" data-foto="'+cont_tomar+'" data-nameimagen="'+escape(theFile.name)+'">eliminar</div></div>';
                                cont_tomar++;
                                $('.cantidad_'+id).append(html);
                                $('#loader_gif').fadeOut();
			                };
			            })(f);
			            reader.readAsDataURL(f);

		        	}
		        }	
		     } 
		 }      
    }

    $('.select_actor').change();
    $('#production_selector').change();
    $('.pais_selector').change();
 	$('#personaje_selector').change();
  	$('.new_personaje').change();
  	$('#id_manager').change();

   $('#contrato_select').change(function(){
   	if($(this).val()!="" && $(this).val()!="Seleccione una opción"){
   		datos = {id_contrato:$(this).val(), id_solicitud:$('#idsolicitud').val()};
   		$.ajax({
			type: "POST",
			async:false,
			url: site_url+"casting/carga_contrato",
			data: datos,
			dataType: "json",
			success:function(data){
				$('#contrato_section').html(data);
			}
		});
   	}else{
   		$('#contrato_section').html("");
   	}
   	var label = $("#contrato_select option:selected").html().toUpperCase();
   	
   	if (label.indexOf('JCA')>=0) {
   		$('#rcn_represent').show();
   	}else{
   		$('#rcn_represent').hide();
   	};
   });

	$('#contrato_select').change();

    $('#ordenarCasting').click(function(){
        $('#ordenarWrap').fadeIn();
        return false;
    });

    $('.detalle_open_fancybox tr').click(function(){
    	$('#open_fancy').click();
    });


    $('#subir_contrato_firmado').click(function(){
    	$('#contrato_firmado_file').trigger('click');
    });

    $('#contrato_personalizado').change(function(){
    	if($(this).val()!=""){
    		$('#subir_contrato_personalizado').fadeIn();
    	}else{
    		$('#subir_contrato_personalizado').hide();
    	}
    });

    /*APERTURA FILA HISTORIAL SOLICITUD EN SOLICITUDES */
    $('.row_solicitud').click(function(){
    	$(this).next('.info_solicitud').slideToggle()
    });
    /*APERTURA FILA HISTORIAL SOLICITUD EN SOLICITUDES */

    /*Button File Chose*/

    $('#documentos_section').on('click','.button-file',function(e){
    	e.preventDefault();
    	var input_file = '';
    	if($(this).attr('data-input') == ''){
    		input_file = $(this).next('input[type=file]');
    	} else {
    		if ($(this).attr('data-input') =="visa_documento" || $(this).attr('data-input') =="otro_documento" || $(this).attr('data-input') =="pasaporte") {
    			input_file = $(this).next('input[type=file]');
    		}else{
    			input_file = $('#'+$(this).attr('data-input'));
    		}
    	}
    	input_file.trigger('click');
    });

    $('#form_subir_contrato_personalizado').on('click','.modificar', function(e){
    	input_file = $(this).next('input[type=file]');
    	input_file.trigger('click');
    	return false;
    });

    $('#form_contrato_firmado').on('click','.modificar', function(e){
    	input_file = $(this).next('input[type=file]');
    	input_file.trigger('click');
    	return false;
    });


    

    $('#adjuntar_archivo_contrato').click(function(){
    	$('#contrato_personalizado').click();
    });

    $('.grupo-datos').on('click','.modificar', function(e){
    	var iddocumento = $(this).attr('data-id');
    	$('#documento_solicitud_'+iddocumento).trigger('click');
    	return false;
    });

    $('.guardar_documento_actor_form').on('click','.modificar', function(e){
    	var documento = $(this).attr('data-documento');
    	$('#'+documento).trigger('click');
    	return false;
    });


    $('.field_document_actor').keypress(function(){
    	show_button_save($(this));
    });

    $('.field_document_actor').change(function(){
    	show_button_save($(this));
    });

    function show_button_save (element) {
    	button = element.parent().parent().find('.guardar_documento_actor');
    	console.log(button.html());
    	if(element.val()!=""){
    		button.fadeIn();
    	}else{
    		button.fadeOut();
    	}
    }
    

    /*ACTULIZAR DOCUEMNTOS EN DETALLE DE LA SOLICITUD*/
    $('.change_documento_solicitud').change(function(){
    	var iddocumento = $(this).attr('data-id');
    	if($(this).val()!=""){
    		$('#button_'+iddocumento).fadeIn();	
    	}
    });

    $('.change_numero_documento').keypress(function(e){
    	var iddocumento = $(this).attr('data-id');
    	console.log(iddocumento)
    	$('#button_'+iddocumento).fadeIn();
    });

    $('.guardar_documento_solicitud').click(function(){
    	var iddocumento = $(this).attr('data-id');
		if(confirm("¿Está seguro que desea actualizar este documento?")){
			$('#actualizar_documento_'+iddocumento).submit();
		}
    });
    /* FIN ACTULIZAR DOCUEMNTOS EN DETALLE DE LA SOLICITUD*/

    $('#guardar_documento_solicitud').on('click','.button-file',function(e){
    	e.preventDefault();
    	var input_file = '';
    	var input_text = $(this).parent().find('input[type=text]');
    	if(input_text.val()==""){
    		input_text.addClass('error');
    	}else{
    		input_file = $(this).next('input[type=file]');
			input_file.trigger('click');
    	}
    });


    $('.with-label').change(function(){
    	var path = this.value.split('\\');
    	var file = path[path.length-1];
    	var label = $('.label-file[data-input="'+$(this).attr('id')+'"]');
    	label.html(file);
    });

     $('#documentos_section').on('change','.with-label',function(){
    	var path = this.value.split('\\');
    	var file = path[path.length-1];
    	var label = $(this).next('.label-file');
    	label.html(file);
    });

    $('.autosave').change(function(){
    	$(this).closest('form').submit();
    	$('#wrapper_loader').show();la
    });

    $('#exportar_contrato').click(function () {
    	var idcontrato = $('#contrato_select').val();
    	var idsolicitud = $('#idsolicitud').val();

    	if (idcontrato!="") {
    		window.open(site_url + 'casting/exportar_contrato/' + idsolicitud + '/' + idcontrato);
    	};

    });

    $('#anular_solicitud').click(function(e){
    	var idsolicitud = $(this).attr('data-idsolicitud'); 
    	if (confirm("¿Esta seguro que desea anular la solicitud?")) {
    		$('#wrapper_loader').show();
    		window.location.href 	= site_url + 'casting/anular_solicitud/' + idsolicitud;
    	}else{
    		return false;
    	}
    	
    });

    $('.old_field').change(function(){
    	$('#start_month_section').fadeIn();
    });

    $('.old_field').keypress(function(){
    	$('#start_month_section').fadeIn();
    });

    $('.guardar_representantes').click(function(){
    	$('#alerta_representantes').hide();
    	var representante = $(this).attr('data-representante');
    	if($('#representante_'+representante).val()!="" &&  $('#documento_'+representante).val()!=""){
    		$('#wrapper_loader').show();
    		datos = {
    			nombre: $('#representante_'+representante).val(), 
    			documento: $('#documento_'+representante).val(),
    			tipo:representante
    		};
    		$.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/guardar_representantes",
				data: datos,
				dataType: "json",
				success:function(data){
					if(data){
						$('#alerta_representantes').fadeIn();
						$('#wrapper_loader').hide();
					}
					$('#contrato_select').change();
				}
			});
    	}
    });

    $('.guardar_sociedad').click(function(){
    	$('#alerta_representantes').hide();
    	var id_sociedad = $('#id_sociedad').val();
    	if($('#representante_legal').val()!="" &&  $('#representante_documento').val()!=""){
    		$('#wrapper_loader').show();
    		datos = {
    			representante_legal: $('#representante_legal').val(), 
    			representante_documento: $('#representante_documento').val(),
    			id_sociedad:id_sociedad
    		};
    		$.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/guardar_sociedad",
				data: datos,
				dataType: "json",
				success:function(data){
					if(data){
						$('#alerta_representantes').fadeIn();
						$('#wrapper_loader').hide();
					}
					$('#contrato_select').change();
				}
			});
    	}
    });

    //orden de columnas
    $('#save_fields_casting').click(function(){
	    var campos="";
	    $("#itemsEnable li").each(function() {
	      if(!$(this).is(':checked')){
	       campos+= $(this).html()+',';
	      }
	    });
	    $('#campos_select').val(campos);
	    $('#orderCasting').submit(); 
	});


	$('#comparar_actores').click(function () {
	  var actores = "";
	  var nombre_produccion = $('#nombre_produccion').val();
	  var nombre_personaje = $('#nombre_personaje').val();
	  $('#resultado_actores .compare').each(function () {
	    actores+=$(this).attr('data-idactor')+'.';
	  });
	  if(nombre_produccion == ""){
	  	nombre_produccion = '-'
	  } 
	  if(nombre_personaje == ""){
	  	nombre_personaje = '-'
	  }
	  window.open(site_url + "casting/pdf_comparacion/" + actores + '/' + encodeURI(nombre_produccion) + '/' + encodeURI(nombre_personaje));
	});

	$('.save_button').click(function(){
		$('#wrapper_loader').fadeIn();
	});

	/*BOTON DE LIMPIAR SELECCION EN CASTING*/
  	$('#limpiar_filtro_casting').click(function(){
	    $("#filtro_actores_section input").each(function() {
	      if($(this).attr('type')=="text"){
	        $(this).val("");
	      }
	      if($(this).attr('type')=="radio" || $(this).attr('type')=="checkbox"){
	        $(this).prop('checked', false);
	      }
	    });

	    $("#filtro_actores_section select").each(function() {
	      $("option", this).eq(0).prop('selected', true);
	      $(this).change();
	    });

	    $(this).fadeOut();
	    $('#resultado_actores').html('');
	    $('#resumen_busqueda').html('');
	    return false;
  	});
    /*FIN BOTON DE LIMPIAR SELECCION EN CASTING*/

    $('#the_produccion').change(function(){
    	if($(this).val()!="" && $(this).val()!="Seleccione una opción"){
    		$('#wrapper_loader').show();
    		window.location.href 	= site_url + 'casting/nomina_personajes/' + $(this).val();
    	};
    });

    $('#razon_social').change(function(){
    	if ($(this).val()!="" && $(this).val()!="Seccione una opción" && $(this).val()!=0) {
    		var id_sociedad = $(this).val();
    		$.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/buscar_sociedad",
				data: {id_sociedad : id_sociedad},
				dataType: "json",
				success:function(data){
					if(data){
						$('#nit_sociedad').val(data.sociedad[0].nit);
						$('#telefono_fijo_sociedad').val(data.sociedad[0].telefono_fijo);
						$('#telefono_movil_sociedad').val(data.sociedad[0].telefono_movil);
						$('#direccion_sociedad').val(data.sociedad[0].direccion);
						$('#email_sociedad').val(data.sociedad[0].email);
						$('#id_ciudad_sociedad_hidden').val(data.sociedad[0].ciudad);
						$('#sociedad_pais_'+data.sociedad[0].pais).attr('selected','selected');
						$('#representante_legal').val(data.sociedad[0].representante_legal);
						$('#documento_representante').val(data.sociedad[0].documento_representante);
						$('.pais_selector').change();
					}
				}
			});
    	};
    });

    $('#razon_social').change(function () {
    	if ($(this).val()==0) {
    		$(this).hide();
    		$('#nombre_sociedad').show();
    		$('.cancelar_sociedad').show();
    		$('#society_section input').val('');
    	};
    });

    $('#cancelar_sociedad').click(function(event){
    	event.preventDefault();
    	$('#razon_social').show();
		$('#nombre_sociedad').hide();
		$('.cancelar_sociedad').hide();
		$('#society_section input').val('');
		$("option", $('#razon_social')).eq(0).prop('selected', true);	
    });

    

    //BOTON SUPERIOR DE APROBACION
    $('.up_button').click(function(e){
    	e.preventDefault();
    	var id = $(this).attr('id');
    	id = id.replace('_up','');
    	$('#'+id).click();
    });

    $('#aprobar_solicitud_firma').click(function () {
		$('#ordenarWrap2').fadeIn(); 
    });

    $('#ordenarWrap2 .closeIcon').click(function () {
    	$('#ordenarWrap2').hide(); 
    });

    $('#aprobar_admin_firma').click(function () {
    	var id_solicitud = $(this).attr('data-solicitud');
    	$('#ordenarWrap2 .estado_admin').each(function(){
			if ($(this).is(':checked')) {
				valor = $(this).val();
			}
		});
		if(valor!=""){
			datos = {idsolicitud:id_solicitud, validacion:valor };
		    $.ajax({
				type: "POST",
				async:false,
				url: site_url+"casting/aprobar_solicitud",
				data: datos,
				dataType: "json",
				success:function(data){
					window.location.reload();
				}
			});
		}
    });

	/*Botones Listado de Documentos*/
	$('.typeAprobacionDetalleProduct').ready(function(){
		$('.content-doc').each(function(){
			var length_button = ($(this).find('.button-file').length > 1) ? 2:1 ;
			$(this).find('.button-file').css('width', 97/length_button+'%');
		});
	});



});