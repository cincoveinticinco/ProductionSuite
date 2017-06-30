$(document).ready(function() {

	$('a').each(function(){
		$(this).attr('tabindex','-1');
	});
   
   $('td.estatus').each(function(){
		if($(this).find('div').length > 1){
			$(this).find('div').css({'width':'50%', 'font-size':'10px'});
		}
	});
	

	$('.crear_capitulo').click(function(){
		var numero_capitulo=$('.numero_capitulo').val()
		var id_produccion=$('.id_produccion').val();
		datos= {numero_capitulo: numero_capitulo,id_produccion:id_produccion}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/crear_capitulo",
			data: datos,
			dataType: "json",
			success:function(data){
                   if(data.existe=='1'){
                       alert(capitulo_ya_existe)
                   }else{
                      alert(capitulo_creado)
                      window.location.reload();
                   }
			}
		}); 
	})


	/*SELECCION FIRLTRO ESTADO CAPITULOS*/
	$('.capitule_state_post').click(function(){
      var idproduccion = $('#idproduccion').val();
      var limite = $('#limite_capitulos').val();
      cargar_capitulos(limite,idproduccion);
      $('td.estatus').each(function(){
		if($(this).find('div').length > 1){
			$(this).find('div').css({'width':'50%', 'font-size':'10px'});
		}
	 });

  	});
	/*SELECCION FIRLTRO ESTADO CAPITULOS*/

    var limit_cap=40;
	$('#load_capitulos').click(function(){
	    limit_cap=parseInt(limit_cap)+40;
	    $('#limite_capitulos').val(limit_cap);
        var id_produccion=$(this).attr('data-idprod');
        
        cargar_capitulos(limit_cap,id_produccion);
	});

	function cargar_capitulos(limite,id_produccion,validator){
		var estados="";
		$("input.capitule_state_post").each(function() {
	        if(!$(this).is(':checked')){
	          estados += $(this).attr('data-idestado')+',';
	        }
	    });
		datos= {id_produccion: id_produccion,limit:limit_cap,estados:estados}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/cargar_capitulos",
			data: datos,
			dataType: "json",
			success:function(data){
                   if(data.existe=='1'){
                       //alert('No hay mas capitulos para cargar');
                       $(".tr_capitulos" ).html('');
                       $('#load_capitulos').hide();
                   }else{
						$(".tr_capitulos" ).html(data.html);
						if(data.total_capitulos<=data.cantidad){
							$('#load_capitulos').hide();
							
						}
						$('td.estatus').each(function(){
						   if($(this).find('div').length > 1){
							 $(this).find('div').css({'width':'50%', 'font-size':'10px'});
							}
					   });
                   }
			}
		});
	}

	var limit_esc=0;
	$('#load_escenas').click(function(){
	 limit_esc=parseInt(limit_esc)+40;
      var id_capitulo=$(this).attr('data-idcaptulo');
      var id_produccion=$(this).attr('data-produccion');
      var permitir=$(this).attr('data-permitir');
      datos= {id_capitulo: id_capitulo,limit:limit_esc,id_produccion:id_produccion,permitir:permitir}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/cargar_escenas",
			data: datos,
			dataType: "json",
			success:function(data){
                   if(data.existe=='1'){
                       alert(no_mas_escenas_para_cargar);
                       $('#load_escenas').remove();
                   }else{
                   	$(".tr_escenas" ).append(data.html);
                   	if(data.total_escena<=limit_esc+40){
                   		$('#load_escenas').remove();
                   	}
                  
                   }
			}
		}); 

	});

	$('.add_lib').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
		$(this).addClass('nav_post_active');
		$('.content_acction_menu > div:not(.agregar_escena)').css('display', 'none');
		$('.agregar_escena').fadeIn();
		$('.content_acction_menu').slideDown();
		
	});
	  $('.btn_escena_cancelar').click(function(){
	  	$('.nav_post li a').removeClass('nav_post_active');
	  	$(this).addClass('nav_post_active');
		$('.content_acction_menu > div:not(.agregar_tiempo_extra)').css('display', 'none');
		$('.agregar_esc:nth-child(1)').addClass('ultimo');
		$('.agregar_esc:nth-child(n+2)').remove();
		$('.content_acction_menu').slideUp();
		$('.agregar_tiempo_extra').fadeOut();
    });
	$('.add_tiempo_extra').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
		$(this).addClass('nav_post_active');
		$('.content_acction_menu > div:not(.agregar_tiempo_extra)').css('display', 'none');
		$('.agregar_tiempo_extra').fadeIn();
		$('.content_acction_menu').slideDown();
		
	});

	$('.cancel_tiempo_extra').click(function(){
		$('.content_acction_menu').slideUp();
		$('.agregar_tiempo_extra').fadeOut();
    });

    $('.ver_convensiones a').click(function(){
    	$('.nav_post li a').removeClass('nav_post_active');
    	$(this).addClass('nav_post_active');
    	$('.content_acction_menu > div:not(.cuadro_convensiones)').css('display', 'none');
		$('.cuadro_convensiones').fadeIn();
		$('.content_acction_menu').slideDown();
	});

	$('.cerrar_convensiones').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
    	$('.content_acction_menu').slideUp();
		$('.cuadro_convensiones').fadeOut();
	});

    $('.nuevo_capitulo').click(function(){
    	$('.nav_post li a').removeClass('nav_post_active');
    	$(this).addClass('nav_post_active');
		$('.content_acction_menu > div:not(.add_capitulo)').css('display', 'none');
		$('.add_capitulo').fadeIn();
		$('.content_acction_menu').slideDown();
	});

	$('.cancelar_crear_capitulo').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
		$('.content_acction_menu').slideUp();
		$('.add_capitulo').fadeOut();
	});

	$('.filtar_capitulo').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
		$(this).addClass('nav_post_active');
		$('.content_acction_menu > div:not(.filtros_capitulo)').css('display', 'none');
		$('.filtros_capitulo').fadeIn();
		$('.content_acction_menu').slideDown();
	});

	$('.cancelar_filtro_capitulo').click(function(){
		$('.nav_post li a').removeClass('nav_post_active');
		$('.content_acction_menu').slideUp();
		$('.filtros_capitulo').fadeOut();
	});

	$('.add_tiempo_mult').click(function(){
		$('.tiempos_multiples').fadeIn();
	});
	$('.agregarMultiplesbox .closeIcon').click(function(){
		$('.tiempos_multiples').fadeOut();
	});

	
    $('.asignar_escenas').on("change",".libretos_cap",function(){
    	var cantidad_escenas=$('.cantidad_escenas').val();
        var libreto=$(this).val();
        var valor=$(this).attr('data-valor');
        var id_produccion=$('.id_produccion').val();
        var id_capitulo=$('.id_capitulo').val();
      	datos= {libreto: libreto,id_capitulo:id_capitulo}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/cargar_escena",
			data: datos,
			dataType: "json",
			success:function(data){
                   if(data.escenas){
                   	var html='<label>'+escenas+'</label>';
                   	    var cont=cantidad_escenas;
                   	    var con2=0;
						$.each(data.escenas, function(i,e){
							if(e.capitulo==0){
								 if(e.estado=='1' || e.estado=='2' || e.estado=='12' || e.estado=='14'){
								 	html = html+'<div class="sel_escena"><input class="selectedId_'+valor+'" type="checkbox" name="escenas['+cont+']" value="'+e['id']+'">'+e['numero_escena']+'</div>';
									cont++;
									con2++;
								 }else{
								 	html = html+'<div class="sel_escena no_producido">'+e['numero_escena']+'</div>';
								 }	
							}else{
								html = html+'<div class="sel_escena cap_asginado">'+e['numero_escena']+'</div>';
							}
						});
						if(con2>0){
							html=html+'<input type="hidden" name="id_produccion" value="'+id_produccion+'"><input type="hidden" name="id_capitulo" class="id_capitulo" value="'+id_capitulo+'"><div class="sel_escena" style="width:auto;"><input type="checkbox" class="selectall" data-valor="'+valor+'">'+seleccionar_todo+'</input></div>';
						}	
						$('.cantidad_escenas').val(cont)
					$('.valores_'+valor).html(html);	
                   }
			}
		}); 
	})

	$('.tr_escenas').on("click",".add_tiempo_post",function(){
		$(this).next('.hide_box').stop().fadeIn();
        $(this).parent().find('.hide_box.capitulo_box').css("opacity", "");
        $(this).parent('td').css({'background':'#2aabe2','font-weight':'bolder'});
        $(this).css({'color':'white'});
        $('.hide_box').not($(this).next('.hide_box')).fadeOut();
        $('.add_tiempo_post').not($(this)).css({'color':'#0098d2'});
        $('.table_general .tr_escenas tr td').not($(this).parent('td')).css({'background':'transparent','font-weight':'normal'});
        $('.value').focus();
        $('.hide_box textarea').focus();
        $('.hide_box input').focus();
		$('#inner_content').css('margin-bottom','120px');
	    return false;
	});

	$('#main_container').on('click','span.close_box', function(){
		console.log('Click');
		 hide_box_blue();
        $('#inner_content').css('margin-bottom','50px');
        
     });

	$('.tr_capitulos').on("click",".chage_date",function(){
		$(this).next('.hide_box').stop().fadeIn();
	})
	$('.close_box').on('click',function(){
        hide_box_blue();
        return false;
    });
    $('.table_general').on('click','.save_tiempo_post',function(){
    	var escena=$(this).attr('data-idescena');
    	var m=$('.m_'+escena).val();
    	var s=$('.s_'+escena).val();
    	
    	m=parseInt(m);
    	s=parseInt(s);
    	console.log(m);
    	console.log(s);
    	if(isNaN(m)){
          alert(se_debe_ingresar_los_minutos_y_segundos);
    	  return false;	
    	}else{
    		if(isNaN(s)){
    		  alert(se_debe_ingresar_los_minutos_y_segundos);
    	  	  return false;		
    		}else{
    	       $(this).parent().submit();
          	   return false;				
    		}
    	  
    	}
        //
	});
    
    function hide_box_blue(){
	    $('.hide_box').stop().fadeOut();
	    $('.table_general .tr_escenas tr td').css({'background':'transparent','font-weight':'normal', 'color':'rgb(0, 152, 210)'});
	    $('.add_tiempo_post').css({'color':'#0098d2'});
    }

	$('.tr_capitulos').on("click",".confirmar_capitulo",function(){
		var estado_actual='';
		var estado_new='';
		var idestado=$(this).attr('data-idestado');
		switch(idestado){
          case '2':
            estado_actual=ingestando;
            estado_new=pre_editando;
          break;
          case '3':
            estado_actual=pre_editando;
            estado_new=editando;
          break;
          case '4':
            estado_actual=editando;
            estado_new=editando;
          break;
          case '5':
            estado_actual=editando;
            estado_new=codificando_video;
          break;
          case '6':
            estado_actual=codificando_video;
            estado_new=qc_productor;
          break;
          case '7':
            estado_actual=qc_tecnico;
            estado_new=montnado_lto;
          break;
          case '8':
            estado_actual=qc_productor;
            estado_new=montnado_lto;
          break;
          case '9':
            estado_actual=montnado_lto;
            estado_new=codificando_cliente;
          break;
          case '10':
            estado_actual=codificando_cliente;
            estado_new=enviando_cliente;
          break;
          case '11':
            estado_actual=enviando_cliente;
            estado_new=qc_cliente;
          break;
          case '12':
            estado_actual=qc_cliente;
            estado_new=sesion_de_protools_montado_del_ps;
          break;
          case '13':
            estado_actual=sesion_protools;
            estado_new=capitulo_entregado;
          break;
          case '14':
            estado_actual=montando_ps;
            estado_new=capitulo_entregado;
          break;
          case '15':
            estado_actual=montando_ps;
            estado_new="";
          break;
        }
		var capitulo=$(this).attr('data-capitulo');
        var total_escenas=parseInt($(this).attr('data-totalescenas'));
         var id_estado=$(this).attr('data-idestado');
		if(id_estado==14 && total_escenas==0){
             if (confirm(el_capitulo+capitulo+" no tiene escenas asignadas ¿Desea confirmar el capitulo?")) {
						var id_capitulo=$(this).attr('data-idcapitulo');
						var id_user=$(this).attr('data-iduser');
						
						var id_produccion=$('.id_produccion').val();
						datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
						$.ajax({
							type: "POST",
							url: site_url+"/post_produccion/confirmacion_capitulo",
							data: datos,
							dataType: "json",
							success:function(data){
								if(data){
								   $('.estado_cap_'+id_capitulo).html(data.estatus);
								   $('.responsable_cap_'+id_capitulo).html(data.responsable);
								   //alert('Capitulo confirmado');
								   window.location.reload();
								}
							}
						}); 
				}else {
				 return false;
				}	
		}else{
		    if (confirm(el_capitulo+capitulo+" ha completado el estatus  "+estado_actual+y_para_al_estado+estado_new+"?")) {
						var id_capitulo=$(this).attr('data-idcapitulo');
						var id_user=$(this).attr('data-iduser');
						
						var id_produccion=$('.id_produccion').val();
						datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
						$.ajax({
							type: "POST",
							url: site_url+"/post_produccion/confirmacion_capitulo",
							data: datos,
							dataType: "json",
							success:function(data){
								if(data){
								   $('.estado_cap_'+id_capitulo).html(data.estatus);
								   $('.responsable_cap_'+id_capitulo).html(data.responsable);
								   //alert('Capitulo confirmado');
								   window.location.reload();
								}
							}
						}); 
				}else {
				 return false;
				}	
			}		
	})

	$('.tr_capitulos').on("click",".confirmar_capitulo_doble",function(){
		var capitulo=$(this).attr('data-capitulo');
		var total_escenas=parseInt($(this).attr('data-totalescenas'));
		var idestado=$(this).attr('data-idestado');
		var idestadodos=$(this).attr('data-idestadodos');
		var estado_actual='';
		var estado_new='';
		var id_capitulo=$(this).attr('data-idcapitulo');
		var id_user=$(this).attr('data-iduser');
        if(idestado=='6'){
           var estado_actual=codificando_video;
           var estado_new=qc_productor;
        }else if(idestado=='7'){
        	 var estado_actual=qc_tecnico;
            var estado_new=montnado_lto;
        }else if(idestado=='8'){
        	 var estado_actual=qc_productor;
            var estado_new=montnado_lto;
        }else if(idestado=='13'){
        	 var estado_actual=sesion_protools;
            var estado_new=capitulo_entregado;
        }else if(idestado=='14'){
        	 var estado_actual=montando_ps;
            var estado_new=capitulo_entregado;
        }
        if(idestadodos=='6'){
           var estado_actual2=codificando_video;
           var estado_new2=qc_productor;
        }else if(idestadodos=='7'){
        	 var estado_actual2=qc_tecnico;
            var estado_new2=montnado_lto;
        }else if(idestadodos=='8'){
        	 var estado_actual2=qc_productor;
            var estado_new2=montnado_lto;
        }else if(idestadodos=='13'){
        	 var estado_actual2=sesion_protools;
            var estado_new2=capitulo_entregado;
        }else if(idestadodos=='14'){
        	 var estado_actual2=montando_ps;
            var estado_new2=capitulo_entregado;
        }
        $.prompt("Seleccione Option 1 se desea aprobar el estado "+estado_actual+" o Opcion 2 si desea aprobar el estado "+estado_actual2, {
		title: capitulos+" "+capitulo,
		buttons: {"Option 1": idestado, "Option 2": idestadodos},
		focus: 1,
		submit:function(e,v,m,f){
			var id_estado=v;
			var id_produccion=$('.id_produccion').val();
			if(id_estado=="14" && total_escenas==0){
                 if(confirm(el_capitulo_no_tiene_escenas)) {
                    datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
					$.ajax({
						type: "POST",
						url: site_url+"/post_produccion/confirmacion_capitulo",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data){
							   $('.estado_cap_'+id_capitulo).html(data.estatus);
							   $('.responsable_cap_'+id_capitulo).html(data.responsable);
							   //alert('Capitulo confirmado');
							   window.location.reload();
							}
						}
					});
                 }else{
                 	return false;
                 }
			}else{
				datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/confirmacion_capitulo",
					data: datos,
					dataType: "json",
					success:function(data){
						if(data){
						   $('.estado_cap_'+id_capitulo).html(data.estatus);
						   $('.responsable_cap_'+id_capitulo).html(data.responsable);
						   //alert('Capitulo confirmado');
						   window.location.reload();
						}
					}
				});
			}	
		}

	    });
	    /*if (confirm(el_capitulo+capitulo+" ha completado el estatus  "+estado_actual+y_para_al_estado+estado_new+"?")) {
					var id_capitulo=$(this).attr('data-idcapitulo');
					var id_user=$(this).attr('data-iduser');
					var id_estado=$(this).attr('data-idestado');
					var id_produccion=$('.id_produccion').val();
					datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:idestado,id_produccion:id_produccion}
					$.ajax({
						type: "POST",
						url: site_url+"/post_produccion/confirmacion_capitulo",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data){
							   $('.estado_cap_'+id_capitulo).html(data.estatus);
							   $('.responsable_cap_'+id_capitulo).html(data.responsable);
							   //alert('Capitulo confirmado');
							   window.location.reload();
							}
						}
					}); 
		}else {
			        var estado_actual='';
					var estado_new='';
			        if(idestadodos=='6'){
			           var estado_actual=codificando_video;
			           var estado_new=qc_productor;
			        }else if(idestadodos=='7'){
			        	 var estado_actual=qc_tecnico;
			            var estado_new='Montado Archivo LTO';
			        }else if(idestadodos=='8'){
			        	 var estado_actual=qc_productor;
			            var estado_new='Montado Archivo LTO';
			        }else if(idestadodos=='13'){
			        	 var estado_actual='Archivar Capitulo';
			            var estado_new=capitulo_entregado;
			        }else if(idestadodos=='14'){
			        	 var estado_actual=montando_ps;
			            var estado_new=capitulo_entregado;
			        }
		    if (confirm(el_capitulo+capitulo+" ha completado el estatus  "+estado_actual+y_para_al_estado+estado_new+"?")) {
					var id_capitulo=$(this).attr('data-idcapitulo');
					var id_user=$(this).attr('data-iduser');
					var id_estado=$(this).attr('data-idestado');
					var id_produccion=$('.id_produccion').val();
					datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:idestadodos,id_produccion:id_produccion}
					$.ajax({
						type: "POST",
						url: site_url+"/post_produccion/confirmacion_capitulo",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data){
							   $('.estado_cap_'+id_capitulo).html(data.estatus);
							   $('.responsable_cap_'+id_capitulo).html(data.responsable);
							   //alert('Capitulo confirmado');
							   window.location.reload();
							}
						}
					}); 
			}else {
			  return false;
			}
		}	*/
	})	

	 $(".confirmar_capitulo2").click(function(){
	        var id_capitulo=$(this).attr('data-idcapitulo');
			if (confirm(esta_seguro_que_desea_confirmar_el_capitulo+id_capitulo+" ?")) {
				
				var id_user=$(this).attr('data-iduser');
				var id_estado=$(this).attr('data-idestado');
				var id_produccion=$('.id_produccion').val();
				datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/confirmacion_capitulo",
					data: datos,
					dataType: "json",
					success:function(data){
						if(data.estatus){
						   $('.estado_cap_'+id_capitulo).html(data.estatus);
						   $('.responsable_cap_'+id_capitulo).html(data.responsable);
						   alert('Capitulo confirmado');
						   window.location.reload();
						}
					}
				}); 
			}else {
			 return false;
			}	
	})

	$('.tr_capitulos').on("click",".rechazar_capitulo",function(){
		var estado_actual='';
		var estado_new='';
		var idestado=$(this).attr('data-idestado');
		switch(idestado){
          case '2':
            estado_actual=ingestando;
            estado_new=pre_editando;
          break;
          case '3':
            estado_actual=pre_editando;
            estado_new=ingestando;
          break;
          case '4':
            estado_actual=editando;
            estado_new=pre_editando;
          break;
          case '5':
            estado_actual=editando;
            estado_new=editando;
          break;
          case '6':
            estado_actual=codificando_video;
            estado_new=editando;
          break;
          case '7':
            estado_actual=qc_tecnico;
            estado_new=editando;
          break;
          case '8':
            estado_actual=qc_productor;
            estado_new=editando;
          break;
          case '9':
            estado_actual=montnado_lto;
            estado_new=editando;
          break;
          case '10':
            estado_actual=codificando_cliente;
            estado_new=montnado_lto;
          break;
          case '11':
            estado_actual=enviando_cliente;
            estado_new=codificando_cliente;
          break;
          case '12':
            estado_actual=qc_cliente;
            estado_new=editando;
          break;
          case '13':
            estado_actual=sesion_protools;
            estado_new=qc_cliente;
          break;
          case '14':
            estado_actual=montando_ps;
            estado_new=qc_cliente;
          break;
          case '15':
            estado_actual=montando_ps;
            estado_new=sesion_de_protools_montado_del_ps;
          break;
        }
		var capitulo=$(this).attr('data-capitulo');
		if (confirm(el_capitulo+capitulo+esta_en_el_estatus+estado_actual+y_para_al_estado+estado_new+"?")) {
			var id_capitulo=$(this).attr('data-idcapitulo');
			var id_user=$(this).attr('data-iduser');
			var id_estado=$(this).attr('data-idestado');
			var id_produccion=$('.id_produccion').val();
			datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
			$.ajax({
				type: "POST",
				url: site_url+"/post_produccion/rechazar_capitulo",
				data: datos,
				dataType: "json",
				success:function(data){
					//alert('Capitulo rechazado')
					window.location.reload();
				}
			});
		}else{
			return false;
		}
	})

	$('.tr_capitulos').on("click",".rechazar_capitulo_doble",function(){
		var capitulo=$(this).attr('data-capitulo');
		var idestado=$(this).attr('data-idestado');
		var idestadodos=$(this).attr('data-idestadodos');
		var estado_actual='';
		var estado_new='';
		var id_capitulo=$(this).attr('data-idcapitulo');
        var id_produccion=$('.id_produccion').val();
        if(idestado=='6'){
           var estado_actual=codificando_video;
           var estado_new=finalizando;
        }else if(idestado=='7'){
        	 var estado_actual=qc_tecnico;
            var estado_new=finalizando;
        }else if(idestado=='8'){
        	 var estado_actual=qc_productor;
            var estado_new=codificando_video;
        }else if(idestado=='13'){
        	 var estado_actual=sesion_protools;
            var estado_new=qc_cliente;
        }else if(idestado=='14'){
        	 var estado_actual=montando_ps;
            var estado_new=qc_cliente;
        }
        if(idestadodos=='6'){
           var estado_actual2=codificando_video;
           var estado_new=finalizando;
        }else if(idestadodos=='7'){
        	 var estado_actual2=qc_tecnico;
            var estado_new=finalizando;
        }else if(idestadodos=='8'){
        	 var estado_actual2=qc_productor;
            var estado_new=codificando_video;
        }else if(idestadodos=='13'){
        	 var estado_actual2=sesion_protools;
            var estado_new=qc_cliente;
        }else if(idestadodos=='14'){
        	 var estado_actual2=montando_ps;
            var estado_new=qc_cliente;
        }
	        $.prompt("Seleccione Option 1 si rechazar el estado "+estado_actual+" o Opcion 2 si desea rechazar el estado "+estado_actual2, {
			title: "Capitulo "+capitulo,
			buttons: {"Option 1": idestado, "Option 2": idestadodos},
			focus: 1,
			submit:function(e,v,m,f){
				
				var id_estado=v;
				
				datos= {id_capitulo: id_capitulo,id_estado:id_estado,id_produccion:id_produccion}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/rechazar_capitulo",
					data: datos,
					dataType: "json",
					success:function(data){
						//alert('Capitulo rechazado')
						window.location.reload();
					}
				});
			}
	    });
	    /*if (confirm(el_capitulo+capitulo+esta_en_el_estatus+estado_actual+y_para_al_estado+estado_new+"?")) {
			var id_capitulo=$(this).attr('data-idcapitulo');
			var id_user=$(this).attr('data-iduser');
			var id_estado=$(this).attr('data-idestado');
			var id_produccion=$('.id_produccion').val();
			datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
			$.ajax({
				type: "POST",
				url: site_url+"/post_produccion/rechazar_capitulo",
				data: datos,
				dataType: "json",
				success:function(data){
					//alert('Capitulo rechazado')
					window.location.reload();
				}
			});
		}else{
				var idestadodos=$(this).attr('data-idestadodos');
				var estado_actual='';
				var estado_new='';
		        if(idestadodos=='6'){
		           var estado_actual=codificando_video;
		           var estado_new='Finalizado';
		        }else if(idestadodos=='7'){
		        	 var estado_actual=qc_tecnico;
		            var estado_new='Finalizado';
		        }else if(idestadodos=='8'){
		        	 var estado_actual=qc_productor;
		            var estado_new=codificando_video;
		        }else if(idestadodos=='13'){
		        	 var estado_actual='Archivar Capitulo';
		            var estado_new=qc_cliente;
		        }else if(idestadodos=='14'){
		        	 var estado_actual=montando_ps;
		            var estado_new=qc_cliente;
		        }
			    if (confirm(el_capitulo+capitulo+" ha completado el estatus  "+estado_actual+y_para_al_estado+estado_new+"?")) {
					var id_capitulo=$(this).attr('data-idcapitulo');
					var id_user=$(this).attr('data-iduser');
					var id_estado=$(this).attr('data-idestado');
					var id_produccion=$('.id_produccion').val();
					datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
					$.ajax({
						type: "POST",
						url: site_url+"/post_produccion/rechazar_capitulo",
						data: datos,
						dataType: "json",
						success:function(data){
							//alert('Capitulo rechazado')
							window.location.reload();
						}
					});
				}else{
					return false;
				}
				
		} */ 
	})

	$(".rechazar_capitulo2").click(function(){
		var id_capitulo=$(this).attr('data-idcapitulo');
		var id_user=$(this).attr('data-iduser');
		var id_estado=$(this).attr('data-idestado');
		var id_produccion=$('.id_produccion').val();
		datos= {id_capitulo: id_capitulo,id_user:id_user,id_estado:id_estado,id_produccion:id_produccion}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/rechazar_capitulo",
			data: datos,
			dataType: "json",
			success:function(data){
				alert('Capitulo rechazado')
				window.location.reload();
			}
		}); 
	})

	$('.tr_capitulos').on("click",".eliminar_capitulo",function(){
		var capitulo=$(this).attr('data-capitulo');
		if (confirm(esta_seguro_que_desea_eliminar_el_capitulo+capitulo+" ?")) {
				var id_capitulo=$(this).attr('data-idcapitulo');
				datos= {id_capitulo: id_capitulo}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/eliminar_capitulo",
					data: datos,
					dataType: "json",
					success:function(data){
						if(data.eliminar){
							$('.capitulo_'+id_capitulo).remove();
							window.location.reload();
						}
					}
				}); 
		}else{
			return false;
		}		
	})


   $('.tr_capitulos').on("click",".ver_vitacora",function(){
				var id_capitulo=$(this).attr('data-idcapitulo');
				if($('.vitacora2_'+id_capitulo).css('display') == 'none'){
					datos= {id_capitulo: id_capitulo}
					$.ajax({
						type: "POST",
						url: site_url+"/post_produccion/vitacora",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data.vitacora){
								var html='<table cellpadding="0" cellspacing="0">';
									html = html+'<thead><tr><td>'+estado+'</td><td>'+departamento_responsable+'</td><td>'+fecha_aprobacion+'</td><td>'+aprobado_por+'</td></tr></thead>';
									$.each(data.vitacora, function(i,v){
										if(v['activo']=="0"){
                                             html = html+'<tr><td><span>'+v['estado']+'</span></td><td><span>'+v['aprobado']+'</span></td><td><span>'+v['fecha']+'</span></td><td><span>'+v['usuario']+v['estatus']+'</td></tr>';
										}else if(v['activo']=="3" && v['rechazo']=="1"){
                                             html = html+'<tr><td><span>'+v['estado']+'</span></td><td><span>'+v['aprobado']+'</span></td><td><span>'+v['fecha']+'</span></td><td><span>'+v['usuario']+v['estatus']+'</td></tr>';
										}     
										
									});
								html=html+'</table>';	
								

							}else{
								var html='<table cellpadding="0" cellspacing="0">';
									html = html+'<thead><tr><td>'+estado+'</td><td>'+departamento_responsable+'</td><td>'+fecha_aprobacion+'</td><td>'+aprobado_por+'</td></tr></thead>';
									html = html+'<tr><td colspan=4>'+no_hay_bitacora+'</td></tr>';
								html=html+'</table>';	
							}
							$('.vitacora_'+id_capitulo).html(html);	
						    $('.vitacora_'+id_capitulo).fadeIn();
						    $('.vitacora2_'+id_capitulo).fadeIn();
						}
					});
				}else{
					$('.vitacora2_'+id_capitulo).fadeOut();
				} 
		
	})
  
  $('.agregar_escena').on("click",".selectall",function(){
  	var valor=$(this).attr('data-valor')
        $('.selectedId_'+valor).prop('checked', this.checked);
    });



    /*$('.selectedId').change(function () {
        var check = ($('.selectedId').filter(":checked").length ==$('.selectedId').length);
        $('.selectall').prop("checked", check);
    });*/

  $('.save_detalle').click(function(){
  	var id_capitulo=$('.id_capitulo').val();
  	var total=parseInt($('.add_tiempos').attr('data-cantidad'))*2;
  	var cont=1;
  	$(".time").each(function(){
  		var valor=$(this).val();
  		var detalle=$(this).attr('data-tipo');
  		
  		datos= {valor: valor,id_capitulo:id_capitulo,detalle:detalle};
			$.ajax({
				type: "POST",
				url: site_url+"/post_produccion/guardar_detalles",
				data: datos,
				dataType: "json",
				success:function(data){
					 if(cont>=total){
					   $('.tiempo_extra_total').html(data.tiempo_extra);
					   $('.tiempo_total').html(data.tiempo_total);
					   $('.agregar_tiempo_extra').fadeOut(); 	
					   alert(tiempo_extra_actualizado);
					 }
					cont++;
				}
			});
  	});

  });

	 $('.tr_escenas').on('click','.eliminar_escena_capitulo',function(){
	    if(confirm(eliminar_escena)){
			 	var id_escena_capitulo=$(this).attr('data-idescena');
			 	datos= {id_escena_capitulo: id_escena_capitulo}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/eliminar_escena_capitulo",
					data: datos,
					dataType: "json",
					success:function(data){
						if(data.eliminar){
							$('.escena_'+id_escena_capitulo).remove();
						}
					}
				}); 
		}		

	 });

	 var libreto=0;
  $('.eliminar_escena_multiple').click(function(){
  	var datos=$(this).attr('data-datos');
	if(confirm(desea_eliminar_las_escenas+datos+" ?")){
         var id=$(this).attr('data-iddiv');
  		 $('.escenas_milti_'+id).remove();   
  	}
  	
  })

  $('.eliminar_escena').click(function(){
  	var id=$(this).attr('data-iddiv');
  	var datos=$(this).attr('data-datos');
	if(confirm(desea_eliminar_las_escenas+datos+" ?")){
  		$('.escena_'+id).remove();
  	}
  })

  $('.eliminar_creditos').click(function(){
  	var datos=$(this).attr('data-datos');
	if(confirm(desea_eliminar+datos+" ?")){
	  	var div=$(this).attr('data-iddiv');
	  	$('.'+div).val(0);
	  	$('.'+div+'_div').fadeOut();
  	}
  })

  
   $('.asignar_escenas').on('click','.add_libretos',function(){
   	$(this).css('display','none');
   	var libretos_usados=Array();
   	$(".asignar_escenas .libretos_cap").each(function() {
   		libretos_usados=libretos_usados.concat($(this).val());
   	});
   	var valor=$(this).attr("data-valor");
   	valor=parseInt(valor)+1;
  	var id_produccion=$('.id_produccion').val();
    datos= {id_produccion:id_produccion}
	$.ajax({
		type: "POST",
		url: site_url+"/post_produccion/libretos",
		data: datos,
		dataType: "json",
		success:function(data){
			if(data.libretos){
					var html='<div class="agregar_esc cambiar"><div class="libretos_asignar"><label>Libreto</label><select id="libretos" class="libretos_cap" data-valor="'+valor+'">';
						$.each(data.libretos, function(i,l){
							var usado=0;
						for (var i = 0; i < libretos_usados.length; i++) {
							if(libretos_usados[i]==l['id_capitulo']){
								usado=1;
							}
						};
						if(usado==0){
						   html = html+'<option value="'+l['id_capitulo']+'">'+l['numero']+'</option>';	
						}
						
						});
					html=html+'</select> <div class="add_libretos"></div></div> <div class="escenas_asignar valores_'+valor+'"></div>';	
					$('.ultimo').after(html);
					$(".agregar_esc").removeClass("ultimo");
					$( ".cambiar" ).addClass( "ultimo");
					$( ".ultimo" ).removeClass( "cambiar");
					$('.add_libretos').attr("data-valor",valor)


			}
		}
	}); 

  })





  $('.agregar_tiempo_extra').on('change','.detalle',function(){
  	  var id_capitulo=$('.id_capitulo').val();
  	  var detalle=$(this).val()
  	  var valor=$(this).attr('data-valor');
  	  datos= {id_capitulo: id_capitulo,detalle:detalle}
		$.ajax({
			type: "POST",
			url: site_url+"/post_produccion/detalles_capitulo",
			data: datos,
			dataType: "json",
			success:function(data){
				if(data.minutos){
                  $('.minutos_'+valor).val(data.minutos);
                  $('.minutos_'+valor).attr('data-tipo',detalle+'_minutos');
				}else{
				  $('.minutos_'+valor).val('');	
				  $('.minutos_'+valor).attr('data-tipo',detalle+'_minutos');
				}
				if(data.segundos){
                   $('.segundos_'+valor).val(data.segundos);
                   $('.segundos_'+valor).attr('data-tipo',detalle+'_segundos');
				}else{
					$('.segundos_'+valor).val('');
					$('.segundos_'+valor).attr('data-tipo',detalle+'_segundos');
				}

				if(data.cuadros){
                   $('.cuadros_'+valor).val(data.cuadros);
                   $('.cuadros_'+valor).attr('data-tipo',detalle+'_cuadros');
				}else{
					$('.cuadros_'+valor).val('');
					$('.cuadros_'+valor).attr('data-tipo',detalle+'_cuadros');
				}
			}
		}); 
  });

  $('.cancel_tiempo_multiple').click(function(){
    $('#agregarMultiplesWrap').fadeOut();  
    	
  })


  //CAJA DE COLORES
  var cant = $('.scroll_inner div');
  
  $('.scroll_inner').css('width',260*cant.length);

  $('.menu_index').on('click', function(){
  	if($('.sub_menu_list').css('display') == 'none'){
  		$('.tbody_header').fadeIn();
  		$('.sub_menu_list').slideDown();
  		$('#sidebar_caja_colores').animate({
  			width:"15%",
  		});
  	}else{
  		$('.tbody_header').fadeOut();
  		$('.sub_menu_list').slideUp();
  		$('#sidebar_caja_colores').animate({
  			width:"4%",
  		});
	}
  	
  });

   $('#wrapper').on('change','.minutos',function(){
  	var valor=$(this).val();
  	var tipo=isNaN(parseFloat(valor))
     if(tipo==true){
     	alert(validacion)
     	$(this).val('');
     }else{
     	valor=parseInt(valor)
     	if(valor<0){
     		alert(se_debe_ingresar_un_valor_positivo)
     		$(this).val('');
     	}
     	var cadena='';
     	var valor=$(this).val();
     	var p=0;
     	if(parseInt(valor)==0){
           cadena='00';
     	}else{
		     	for (var i = 0; i < valor.length; i++) {
		           
		     		if(parseInt(valor[i])==0 && p==0){
		     		}else{
		     			cadena=cadena+valor[i];
		     			 p=1;
		     		}
		     	};
		     	if(cadena.length<2){
		     		cadena='0'+cadena;
		     	}

		}     	

     	$(this).val(cadena);
     }
     return false;
  })
  $('#wrapper').on('change','.segundos',function(){
  	//$('.segundos').change(function(){
  	var valor=$(this).val();
  	var tipo=isNaN(parseFloat(valor))
  	if(tipo==true){
     	alert(validacion)
     	$(this).val('');
     }else{
     	valor=parseInt(valor)
     	if(valor>59){
           alert(los_segundos_no_puede_ser_mayor)
     		$(this).val('');
     	}

     	///////////////
     	 valor=parseInt(valor)
     	if(valor<0){
     		alert(se_debe_ingresar_un_valor_positivo)
     		$(this).val('');
     	}
     	var cadena='';
     	var valor=$(this).val();
     	var p=0;
     	if(parseInt(valor)==0){
           cadena='00';
     	}else{
	     	for (var i = 0; i < valor.length; i++) {
	     		if(parseInt(valor[i])==0 && p==0){
	     			//cadena='00';
	     		}else{
	     			cadena=cadena+valor[i];
	     			 p=1;
	     		}
	     	};
	     	if(cadena.length<2){
		     		cadena='0'+cadena;
		     	}
	    } 	
     	$(this).val(cadena);
     	//////////

     }
     return false;
  })

  $('#wrapper').on('change','.cuadros',function(){
  	//$('.segundos').change(function(){
  	var valor=$(this).val();
  	var tipo=isNaN(parseFloat(valor))
  	if(tipo==true){
     	alert(validacion)
     	$(this).val('');
     }else{
     	valor=parseInt(valor)
     	if(valor>29){
           alert(los_cuadros_no_puede_ser_mayores)
     		$(this).val('');
     	}

     	///////////////
     	 valor=parseInt(valor)
     	if(valor<0){
     		alert(se_debe_ingresar_un_valor_positivo)
     		$(this).val('');
     	}
     	var cadena='';
     	var valor=$(this).val();
     	var p=0;
     	if(parseInt(valor)==0){
           cadena='00';
     	}else{
	     	for (var i = 0; i < valor.length; i++) {
	     		if(parseInt(valor[i])==0 && p==0){
	     			//cadena='00';
	     		}else{
	     			cadena=cadena+valor[i];
	     			 p=1;
	     		}
	     	};
	     	if(cadena<10){
	     		cadena='0'+cadena;
	     	}
	    } 	
     	$(this).val(cadena);
     	//////////

     }
     return false;
  })

  $('.tr_capitulos').on('click','.save_icon_fecha',function(){

  	var id_produccion=$(this).attr('idproduccion');
  	var id_capitulo=$(this).attr('idcapitulo');
  	var fecha=$('.fecha_post_'+id_capitulo).val();
       if(fecha){ 	
       $('#wrapper_loader').fadeIn();			
  				datos= {id_produccion: id_produccion,id_capitulo:id_capitulo,fecha:fecha}
				$.ajax({
					type: "POST",
					url: site_url+"/post_produccion/actualizar_fechas_capitulos",
					data: datos,
					dataType: "json",
					success:function(data){
						window.location.reload();
					}
				}); 
		//$('#wrapper_loader').fadeOut();			
		}else{
			alert(la_fecha_no_puede_ser_nula);
		}		
  })

  $('.add_tiempos').click(function(){
  	var tiempo_usados=Array();
   	$(".detalle").each(function() {
   		tiempo_usados=tiempo_usados.concat($(this).val());
   	});
   	
   	var tiempos=Array('credito','flashback','transiciones','cortinillas','cabezote','recap','stab','despedida','presentacion','fotoclip','imagenes de archivos');
   	var tiempo_extra=Array();
   	$.each(tiempos, function(i,t){
   		 var usado=0;
   		 $.each(tiempo_usados, function(i2,t2){
            if(usado=='0'){
	            if(t==t2){
	              usado=1;
	            }
	        }    
   		 })
   		 if(usado=='0'){
   		  tiempo_extra=tiempo_extra.concat(t);  	
   		 }
   		
     // console.log(t)
   	})
   	var can=$(this).attr('data-cantidad');
	can=parseInt(can)+1;
   	var html='<div class="row tiempo_extra cambiar_extra"> <div class="column six"><select class="detalle" data-valor="'+can+'">';
   	html_select='';
   	html_select+='<option value="">Seleccionar</option>';
   	$.each(tiempo_extra, function(i,e){
   		if(e=='imagenes de archivos'){
   			e='imagenes_archivos';
   			html_select+='<option value="'+e+'">imagenes de archivos</option>';
   		}else{
   		  html_select+='<option value="'+e+'">'+e+'</option>';	
   		}
       
	})
     html_select+='</select></div>';
     html+=html_select+'<div class="column six"><div class="column twelve"><label style="width:16px; float:left; margin:9px 6px 0 0;">MM</label><input data-tipo="" type="text" name="minutos" placeholder="MM" class="minutos_'+can+' time onlynumbers_cont" value="" style="width:40px;float:left;margin:0 6px 0 0;" autofocus><label style="width:16px; float:left; margin:9px 6px 0 0;">SS</label><input data-tipo="" type="text" name="segundos" placeholder="SS"class="segundos_'+can+' segundos time onlynumbers_cont" value="" style="width:40px;float:left;"><label style="width:16px; float:left; margin:9px 6px 0 0;">CC</label><input data-tipo="" type="text" name="cuadros" placeholder="CC"class="cuadros_'+can+' cuadros time onlynumbers_cont" value="" style="width:40px;float:left;"><div class="remover_tiempos"></div></div></div>';
	$('.ultimo_tiempo').after(html);
	$(".tiempo_extra").removeClass("ultimo_tiempo");
	$( ".cambiar_extra" ).addClass( "ultimo_tiempo");
	$( ".ultimo_tiempo" ).removeClass( "cambiar_extra");
    $(this).attr('data-cantidad',can);
   	
  });


    $('.agregar_tiempo_extra').on('click','.remover_tiempos',function(){
    	$(this).parent().parent().prev('.six').remove();
    	$(this).parent().parent().remove();
    	var tiempo = parseInt($('.add_tiempos').attr('data-cantidad'));
    	console.log(tiempo);
    	$('.add_tiempos').attr('data-cantidad',tiempo-1);
    });

  
    $('.agregar_tiempo_extra').on('keypress','.onlynumbers_cont',function(){
    //$('.onlynumbers_cont').keypress(function(){
      return numbersonly(event);
    });


})

	
     function numbersonly(myfield, e, dec){

        var key;
        var keychar;

        if (window.event)
           key = window.event.keyCode;
        else if (e)
           key = e.which;
        else
           return true;
        keychar = String.fromCharCode(key);

        // control keys
        if ((key==null) || (key==0) || (key==8) || 
            (key==9) || (key==13) || (key==27) )
           return true;

        // numbers
        else if ((("0123456789").indexOf(keychar) > -1))
           return true;

        // decimal point jump
        else if (dec && (keychar == "."))
           {
           myfield.form.elements[dec].focus();
           return false;
           }
        else
           return false;
      }

function validar_archivo(){
	var archivo=$('#myfile').val()
	if(!archivo){
     alert(cargar_archivo);
     return false;
	}
	
}
