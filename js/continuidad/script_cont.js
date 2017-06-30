var num_per=0;
var i2=2;
var cont_tomar=0;
var personajes=new Array();
var imagenes_elminadas = new Array();
$(document).ready(function() {

	
	
	//var length_menu = $("#menu_plan_diario a").length;
	//$("#menu_plan_diario a").css('width', (100/length_menu)-0.1+'%');

	var length_menu = $("nav a").length;
	$("nav a").css('width', (100/length_menu)-0.1+'%');
	
	
	$(this).on('focus', '.clear_on_focus', function(e){
        if($(this).val() == '' || $(this).val() == '00' || $(this).val() == 0){
        	$(this).val('');
        }
        
	}).on('blur', '.clear_on_focus', function(e){
 	   if($(this).val() == '' || $(this).val() == 0){
        	$(this).val('00');
        }
	});	
 
 	$(this).on('focus', 'input, textarea, select', function(e){
        $('footer').css('position','relative');
	}).on('blur', 'input, textarea, select', function(e){
 	    $('footer').css('position','fixed');
	});
	

   $('#continuidad_produccion').click(function(){
    	var cont=$('.dia_continuidad').val();
    	var id_produccion=$('.id_produccion').val();
    	$('.contend_per').remove();
    	$('.escenas').remove();
    	if(cont){
			var datos={id_produccion:id_produccion,cont:cont}
			$.ajax({
			type: "POST",
			url: site_url+"/continuidad/cargar_personajes",
			data: datos,
			dataType: "json",
				success:function(data){
					total_personajes=0;
					var html='<select name="personajes" class="personaje_continuidad" id="personajes_cont"><option>'+seleccionar_presonaje+'</option>';
					p=data.personajes;
					$.each(data.personajes, function(i,p){
						html+='<option value="'+p.id_elemento+'" data-nombre="'+p.nombre+'">'+p.nombre+'</option>';
					});
					html+='</select>';
					$('.personajes').html(html);
	            }
			});
    	}else{
    		alert(debes_ingresar_un_dia_de_continuidad);
    	}
    	
   });

   $('.personajes').on('change','.personaje_continuidad',function(){
      var id_personaje=$(this).val();
      var seleccionado = $(this).find('option:selected');
      var nombre = seleccionado.data('nombre');
	  $("select#personajes_cont option[value='"+id_personaje+"']").remove(); 
      var html='<div class="date_personaje_'+num_per+' contend_per"><input type="hidden" value="'+id_personaje+'" name="id_personaje_'+num_per+'" class="id_personaje_'+num_per+'"><input type="text" value="'+nombre+'" name="nombre_'+num_per+'" class="nombre_'+num_per+'"><div class="delete" data-posicion="'+num_per+'">'+eliminar+'</div></div>';
      num_per=num_per+1; 
      
      $('#personajes_select').after(html);
   });

   $('#contenido').on('click','.delete',function(){
   		var num=$(this).attr('data-posicion');
   		var nombre=$('.nombre_'+num).val();
   		var select=$('#personajes_cont');
		select.append('<option value="'+num+'" data-nombre="'+nombre+'">'+nombre+'</option>');
        $('.date_personaje_'+num).remove();
        num_per=num_per-1;
   });

   $('.libreto').change(function(){
		var id_libreto=$(this).val();
		datos= {id_libreto: id_libreto}
		$.ajax({
			type: "POST",
			url: site_url+"/continuidad/numero_escena",
			data: datos,
			dataType: "json",
			success:function(data){
				var html='<p>'+escenas+':</p><select name="escenas" class="escenas" ><option>'+seleccionar_escena+'</option>';
				$.each(data.escenas, function(i,e){
					html+='<option value="'+e.id+'">'+e.numero_escena+'</option>';
				});
				html+='</select>';
				$('.escenas_libreto').html(html);

			}
		}); 
   });

  $('.escenas_libreto').on('change','.escenas',function(){
      var id_escena=$(this).val();
      datos= {id_escena: id_escena}
		$.ajax({
			type: "POST",
			url: site_url+"/continuidad/persona_escena",
			data: datos,
			dataType: "json",
			success:function(data){
				var html='<select name="personajes" class="personaje_continuidad" id="personajes_cont"><option>'+seleccionar_presonaje+'</option>';
				$.each(data.personajes, function(i,p){
					html+='<option value="'+p.id+'" data-nombre="'+p.nombre+'">'+p.nombre+'</option>';
					$('.dia_continuidad').val(p.dias_continuidad);
				});
				html+='</select>';
				$('.contend_per').remove();
				num_per=0;
				$('.personajes').html(html);
			}
		}); 
   });

  $('.cont').click(function(){
     var id_produccion=$(this).attr('data-idproduccion');
     var id_elemento=$(this).attr('data-idelemento');
     var cont=$(this).attr('data-cont');
     var nombre_elemento=$('.nombre_elemento').val();
     $('.dia_con').val(cont);
     $('.id_elemento_cont').val(id_elemento);
     $('.cont').removeClass('active');
	 $(this).addClass('active');
	 var tipo=$('#filtrar_img').val();
     datos= {id_produccion:id_produccion,cont:cont,id_elemento:id_elemento,tipo:tipo}
		$.ajax({
			type: "POST",
			url: site_url+"/continuidad/elementos_diacont",
			data: datos,
			dataType: "json",
			success:function(data){
				var html='<ul class="lista_fotos">';
				var html2='';
				var total_elementos=1;
				var actual=0;
				var tipo='';
				var total=data.elementos.length;
				if(total!=0){
					var total=data.elementos.length;
					$.each(data.elementos, function(i,e){
						tipo=e.tipo;
						if(data.tipo_rol=="1"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else if(data.tipo_rol=="2" && e.id_tipo=="2"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else if(data.tipo_rol=="3" && e.id_tipo=="3"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else{
                           html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a></li>';
						}
						html2+='<div class="comment"><span class="sub">20-JUN-2013 <strong>Luis Sarmiento</strong></span><p>'+e.nota+'</p></div>';
						total_elementos++;
					});
				}else{
					html+='<li class="ocultar"><img src="'+site_url2+'/images/continuidad/no_image.png" title="'+no_hay_imagenes_para_esta_continuidad+'"></li>';
					tipo='';
				}	
				if(total_elementos>0){
                    actual=1;
				}
				var htmlescena='';

				$.each(data.escenas, function(i,e){
					if(e.estado_plan){
							if(e.numero){
								var numero=e.numero;
							}else{ 
								var numero='-';
						    }
						    console.log(e.fecha_inicio)
						    if(e.fecha_inicio){
						     var fecha=e.fecha_inicio
						    }else{ 
						    	var fecha='-';
						    } 
					}else{
						var numero='-'
						var fecha='-/-';
					}
				    switch(e.estado_escena){
                            case '1':
                             var clas="td_yellow";
                            break;
                            case '2':
                            var clas="td_retoma";
                            break;
                            case '3':
                            var clas="td_black";
                            break;
                            case '4':
                            var clas="td_cian";
                            break;
                            case '5':
                            var clas="td_cian_light";
                            break;
                            case '6':
                            var clas="td_green";
                            break;
                            case '7':
                            var clas="td_green_light";
                            break;
                            case '8':
                            var clas="td_pink";
                            break;
                            case '9':
                            var clas="td_pink_light";
                            break;
                            case '10':
                            var clas="td_orange";
                            break;
                            case '11':
                            var clas="td_orange_light";
                            break;
                            case '12':
                            var clas="td_yellow";
                            break;
                            case '14':
                            var clas="td_retoma";
                            break;
                            default:
                            var clas="td_brown_light";
                            break;
                            }
					htmlescena+='<tr><td class="'+clas+'">'+numero+'/'+fecha+'</td><td >'+e.numero_lib+'</td><td>'+e.numero_escena+'</td><td>'+e.dias_continuidad+'</td><td>'+e.libreto+'</td></tr>';
				});
				html+='</ul>';
				$('#banner-slide').html(html);
				//$('.comentarios_cont').html(html2);
				$('.cont_table_escena').html(htmlescena);
				$('.dia_cont').val(cont)
				var html3='';
				$.each(data.comentarios, function(i,c){
							html3+='<div class="comment"><span class="sub">'+c.fecha+'<strong> '+c.nombre+' '+c.apellido+'</strong></span><p>'+c.comentario+'</p></div>';
								
				});
				$('.comentarios_cont_imagen').html(html3);
				/*$.getScript(site_url+"/js/continuidad/script_cont.js", function(){
				$.ajaxSetup({ cache: false });
				});*/
			}
		}); 
  });

  $('.comentarios').on('change','#filtrar_img',function(){
     var tipo=$(this).val()
     var id_produccion=$('.id_produccion').val();
     var id_elemento=$('.id_elemento_cont').val();
     var cont=$('.dia_con').val();
     var nombre_elemento=$('.nombre_elemento').val();
     datos= {id_produccion:id_produccion,cont:cont,id_elemento:id_elemento,tipo:tipo}
		$.ajax({
			type: "POST",
			url: site_url+"/continuidad/elementos_diacont_tipo",
			data: datos,
			dataType: "json",
			success:function(data){
				var html='<ul class="lista_fotos">';
				var html2='';
				var total_elementos=1;
				var actual=0;
				var tipo='';
				var total=data.elementos.length;
				if(total!=0){
					var total=data.elementos.length;
					$.each(data.elementos, function(i,e){
						tipo=e.tipo;
						if(data.tipo_rol=="1"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else if(data.tipo_rol=="2" && e.id_tipo=="2"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else if(data.tipo_rol=="3" && e.id_tipo=="3"){ 
							html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><div class="div_thumb"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a><div data-idcontinuidad="'+e.id_continuidad+'" data-idproduccion="'+id_produccion+'" data-cont="'+cont+'" data-personaje="'+nombre_elemento+'" class="eliminar_cont btn_rojo">'+eliminar+'</div></div></li>';
						}else{
						   html+='<li class="tipo_imagen-'+e.id_tipo+' ocultar" id="continuidad_'+e.id_continuidad+'"><a class="fancybox-thumbs" data-fancybox-group="thumb" href="'+site_url+'/'+e.imagen+'"><img src="'+site_url2+'/'+e.imagen+'" title="Imagen de '+e.tipo+' '+total_elementos+'/'+total+'"><a></li>';
						}
						html2+='<div class="comment"><span class="sub">20-JUN-2013 <strong>Luis Sarmiento</strong></span><p>'+e.nota+'</p></div>';
						total_elementos++;
					});
				}else{
					html+='<li class="ocultar"><img src="'+site_url2+'/images/continuidad/no_image.png" title="'+no_hay_imagenes_para_esta_continuidad+'"></li>';
					tipo='';
				}	
				if(total_elementos>0){
                    actual=1;
				}
				var htmlescena='';

				$.each(data.escenas, function(i,e){
					if(e.numero){
						var numero=e.numero;
					}else{ 
						var numero='-';
				    }
				    if(e.fecha_inicio){
				     var fecha=e.fecha_inicio
				    }else{ 
				    	var fecha='-';
				    } 
				    switch(e.estado_escena){
                            case '1':
                             var clas="td_yellow";
                            break;
                            case '2':
                            var clas="td_retoma";
                            break;
                            case '3':
                            var clas="td_black";
                            break;
                            case '4':
                            var clas="td_cian";
                            break;
                            case '5':
                            var clas="td_cian_light";
                            break;
                            case '6':
                            var clas="td_green";
                            break;
                            case '7':
                            var clas="td_green_light";
                            break;
                            case '8':
                            var clas="td_pink";
                            break;
                            case '9':
                            var clas="td_pink_light";
                            break;
                            case '10':
                            var clas="td_orange";
                            break;
                            case '11':
                            var clas="td_orange_light";
                            break;
                            case '12':
                            var clas="td_yellow";
                            break;
                            case '14':
                            var clas="td_retoma";
                            break;
                            default:
                            var clas="td_brown_light";
                            break;
                            }
					htmlescena+='<tr><td class="'+clas+'">'+numero+'/'+fecha+'</td><td>'+e.numero_lib+'</td><td>'+e.numero_escena+'</td><td>'+e.dias_continuidad+'</td><td>'+e.libreto+'</td></tr>';
				});
				html+='</ul>';
				$('#banner-slide').html(html);
				$('.comentarios_cont').html(html2);
				$('.cont_table_escena').html(htmlescena);
				$.getScript(site_url2+"/js/continuidad/script_cont.js", function(){
				$.ajaxSetup({ cache: false });
				
				});
			}
		}); 
 


}); 

  /*FUNCION PARA PRECARGAR LA IMAGEN DE LA ALCANCIA*/
    function archivo(evt) {

    	console.log(evt)
        var files = evt.target.files;
        for (var i = 0, f; f = files[i]; i++) {
            if (!f.type.match('image.*')) {
                continue;
            }
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    document.getElementById("uploading").innerHTML = ['<img class="thumb" id="imagen64" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                    $('.imagen').val(e.target.result);
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }
    if (document.getElementById('btn_subir_foto') != null) {
        document.getElementById('btn_subir_foto').addEventListener('change', archivo, false);
    }
    /*FIN FUNCION PARA PRECARGAR LA IMAGEN DE LA ALCANCIA*/



});

function guardar_continuidad(){

	var dia_continuidad=$('.dia_continuidad').val()
	var idproduccion=$('.id_produccion').val();
	if(dia_continuidad){
		var i=0
		var datos_personajes=new Array();
		while(i<=num_per){
			var id_personaje=$('.id_personaje_'+i).val();
			var nombre=$('.nombre_'+i).val();
				if(id_personaje){
				//datos_personajes[i]=[id_personaje]	
				datos_personajes=datos_personajes.concat(id_personaje);
				}
			i++;	
		}

		var tam=datos_personajes.length;
		var uploadFile = document.getElementById ("fotos_sets");
			if(uploadFile.files.length=='0'){
				var v=$('.cantidad_fotos').css('display')
				if(v=='none'){
					alert(cargar_imagen);
					return false;	
				}else{
					var fotos_camara=parseInt($('.cantidad_fotos').val());
					var validar=0;
					for (var i = 1; i <= fotos_camara; i++) {
						var uploadFile = document.getElementById (i);
						if(uploadFile.files.length=='0'){
						}else{
							var validar=1;
						}
					};
					if(validar==1){
			          //$('#wrapper_loader').fadeIn();	
			         /* alert('Debes cargar una imagen2');
					  return false;	*/
					}else{
					  alert(cargar_imagen);
					  return false;	
					}
				}	
					console.log(uploadFile.files.length)
			}
     
		//return false;		
		if(tam!=0){
			$('#wrapper_loader').fadeIn();
			var tipo_imagen=$('.tipo_imagen').val();
			var escenas=$('.escenas').val();
			var numero_imagenes=$('#cajas').val();
			numero_imagenes=parseInt(numero_imagenes);
			for (var i=2;i<=numero_imagenes;i++){ 
				var id='#btn_subir_foto_'+i;
				console.log(id);
				var imagen=$('#btn_subir_foto_'+i).val();
				/*if(!imagen){
					alert('Se deben cargar todas las imagen');
					return false;
				}*/

			}
			var imagen=$('#btn_subir_foto').val();
			/*if(!imagen){
			   alert('Se debe cargar una imagen');
			   return false;
			}else{*/
				///////aca!!!!!!!!!!!/////
					$('.personajes_array').val(datos_personajes);
					return true;
			//}
		}else{
			alert(se_debe_seleccionar_un_personaje);
			return false;
		}	

	}else{
		alert(dia_de_continuidad_no_puede_estar_vacio)
		return false;
	}
    
}



$(document).ready(function() {
		$('.btn_tomar_ahora').click(function(){
			var id=$(this).attr('id');
			var tipo=$(this).attr('data-tipo');
			if(tipo==1){
               var value=1;
			}else if(tipo==2){
				var value=$('#tomar_hora_ensayo-2').val();	
              	
			}else{
			  var value=$('#tomar_hora_ensayo-2').val();	 	
              //var value=$('#tomar_hora_produccion-2').val();
			}
			if(confirm(esta_seguro_que_desea_tomar_esta_hora)){
				if(value){
					var id_plan_escena=$('.id_plan_escena').val();
					var Digital=new Date()
					var hora=Digital.getHours()
					var minutos=Digital.getMinutes()
					var segundos=Digital.getSeconds()
					var tiempo='';
					var d=hora+':'+minutos+':'+segundos;
					if(hora>12){
			           tiempo= 'PM';
			           hora=hora-12;
					}else{
					   tiempo= 'AM';
					}
					if(hora<10){
						hora='0'+hora
					}
					if(minutos<10){
						minutos='0'+minutos;
					}
					if(segundos<10){
						segundos='0'+segundos;
					}
					var hora_actual=hora+':'+minutos+':'+segundos+':'+tiempo;
					datos= {id_plan_escena: id_plan_escena,d:d,tipo:tipo}
					$.ajax({
						type: "POST",
						url: site_url+"/continuidad/guardar_datos_plan",
						data: datos,
						dataType: "json",
						success:function(data){
							location.reload(true);
						}
					});
					$('#'+id+'-2').val(hora_actual);
					$(this).addClass('tomar_hora_manual');
					$(this).removeClass('btn_tomar_ahora');
					$(this).html('MODIFICAR');
				}else{
					 if(tipo==2){
						alert(se_debe_ingresar_el_inicio_ensayo);
					}else if(tipo==3){
						alert(se_debe_ingresar_el_inicio_ensayo);
		                //alert('Se debe ingresar el incio produccion');
					}	
				}
			}
		});

		$('.btn_tomar_ahora_manual').click(function(){
			var id=$(this).attr('id');
			var tipo=$(this).attr('data-tipo');
			if(tipo==1){
               var value=1;
			}else if(tipo==2){
				var value=$('#incio_ensayo_minutos').val();	
              	
			}else{
				var value=$('#incio_ensayo_minutos').val();	
               //var value=$('#inicio_produccion_minutos').val();
			}
			if(confirm(esta_seguro_que_desea_tomar_esta_hora)){
				if(value){
					var id_plan_escena=$('.id_plan_escena').val();
					var hora=$('.horas-'+tipo).val();
					var minutos=$('.minutos-'+tipo).val();
					var tiempo=$('.am_pm-'+tipo).val();
					if(tiempo=='PM'){
						var hora2=parseInt(hora)+12;
			          var d=hora2+':'+minutos;
					}else{
					 var d=hora+':'+minutos;	
					}
					var hora_actual=hora+':'+minutos+':00 '+tiempo;
					datos= {id_plan_escena: id_plan_escena,d:d,tipo:tipo}
					$.ajax({
						type: "POST",
						url: site_url+"/continuidad/guardar_datos_plan",
						data: datos,
						dataType: "json",
						success:function(data){
							$('.hora_manual-'+tipo).fadeOut()
 							$('#'+id+'-2').val(hora_actual);
							$('.hora_servidor-'+tipo).fadeIn();
						}
					});
					
				}else{
					 if(tipo==2){
						alert(se_debe_ingresar_el_inicio_ensayo);
					}else if(tipo==3){
						alert(se_debe_ingresar_el_inicio_ensayo);
		                //alert('Se debe ingresar el incio produccion');
					}	
				}
			}
		});

	    $('.btn_comentar').click(function(){
           $('#agregar_comentario').fadeIn();
           $("html, body").animate({ scrollTop: $(document).height() }, "slow");
  			return false;
	    });

	    $('.save_comment').click(function(){
	    	var comentario=$('.comentario').val();
            var id_plan=$('.id_plan').val();
            if(comentario){
            	datos= {id_plan: id_plan,comentario:comentario}
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/guardar_comentario_plan",
					data: datos,
					dataType: "json",
					success:function(data){
		                var html='';
		                var now = new Date();
						c=data.comentarios;
						$.each(data.comentarios, function(i,c){
							html+='<div class="comment"><span class="sub">'+c.fecha+'<strong> '+c.nombre+' '+c.apellido+'</strong></span><p>'+c.comentario+'</p></div>';
								
						});
						$('.comentarios_cont').html(html);
						$('.comentario').val('');

					}
				});
            }else{
            	alert(se_debe_ingresar_un_comentario)
            }
            
	    })

	    $('.save_comment_continuidad').click(function(){
	    	var comentario=$('.comentario').val();
            var continuidad=$('.dia_cont').val();
            var id_elemento=$('.id_elemento').val();

            if(comentario){
            	datos= {continuidad: continuidad,id_elemento:id_elemento,comentario:comentario}
            	console.log(datos)
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/guardar_comentario_continuidad",
					data: datos,
					dataType: "json",
					success:function(data){
		                var html='';
		                var now = new Date();
						c=data.comentarios;
						$.each(data.comentarios, function(i,c){
							html+='<div class="comment"><span class="sub">'+c.fecha+'<strong> '+c.nombre+' '+c.apellido+'</strong></span><p>'+c.comentario+'</p></div>';
								
						});
						$('.comentarios_cont_imagen').html(html);
						$('.comentario').val('');
						$('#agregar_comentario').fadeOut();

					}
				});
            }else{
            	alert(se_debe_ingresar_un_comentario)
            }
            
	    });

$('.save_comment_continuidad_set').click(function(){
	    	var comentario=$('.comentario').val();
            var id_set=$('.id_set').val();
            var set=$('.set').val();
            var id_produccion=$('.id_produccion').val();
            if(comentario){
            	datos= {id_set:id_set,comentario:comentario,set:set,id_produccion:id_produccion}
            	console.log(datos)
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/insert_comentario_imagenset",
					data: datos,
					dataType: "json",
					success:function(data){
		                var html='';
		                var now = new Date();
						c=data.comentarios;
						$.each(data.comentarios, function(i,c){
							html+='<div class="comment"><span class="sub">'+c.fecha+'<strong> '+c.usuario+'</strong></span><p>'+c.comentario+'</p></div>';
								
						});
						$('.comentarios_cont_imagen').html(html);
						$('.comentario').val('');
						$('#agregar_comentario').fadeOut();

					}
				});
            }else{
            	alert(se_debe_ingresar_un_comentario)
            }
            
	    });

	     $('.save_comment_escena').click(function(){
	    	var comentario=$('.comentario').val();
            var id_escena=$('.id_escena').val();
           if(comentario){
            	datos= {id_escena: id_escena,comentario:comentario}
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/guardar_comentario_escena",
					data: datos,
					dataType: "json",
					success:function(data){
		                var html='';
		                var now = new Date();
						c=data.comentarios;
						$.each(data.comentarios, function(i,c){
							html+='<div class="comment"><span class="sub">'+c.fecha+'<strong> '+c.nombre+' '+c.apellido+'</strong></span><p>'+c.comentario+'</p></div>';
								
						});
						$('.insert_coment').html(html);
						$('.comentario').val('');

					}
				});
            }else{
            	alert(se_debe_ingresar_un_comentario)
            }
            
	    })

	    $('#btn_ins').on('click','#iniciar_cronometro' ,function(){
	    	iniciar_cronometro();
        });

        $('#retornar_cronometro').on('click',function(){
	    	retornar_cronometro();
        });

        




	    $('#save_tiempo_real').on()
	    /*$('#btn_ins').on('click','#save_tiempo_real',function(){
	    	alert();
	    	var tiempo=$('#t_real').val();
	    	var fin_produccion=$('#tomar_fin_produccion-2').val();
	    	var segundos_reales=contador_s-1;
	    	var minutos_reales=contador_m;
	    	var id_escena=$('.id_escena').val();
            var id_produccion=$('.id_produccion').val();
	    	datos= {minutos_reales: minutos_reales,segundos_reales:segundos_reales,id_escena:id_escena,id_produccion:id_produccion}
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/guardar_tiempo_real",
					data: datos,
					dataType: "json",
					success:function(data){
					}
				});
			$('#btn_ins').html('<a class="btn_tomar_ahora_tiempo" onclick="carga()" id="btn_change">INICIAR</a>');
			$('#t_real').removeClass('tiempo_real');
			$('.ancho_a').removeClass('ancho');
			$('.treal').css('width','134px');
	    });*/


	     $('#btn_ins').on('click','#save_tiempo_real',function(){
	     	var value=$('#incio_ensayo_minutos').val();	
	     	if(value){
					var tiempo=$('#t_real').val();
					/*var segundos_reales=contador_s-1;
					var minutos_reales=contador_m;*/
					var segundos_reales=$('.segundos_real').val();
					if(segundos_reales<60){
						var minutos_reales=$('.minutos_real').val();
						var id_escena=$('.id_escena').val();
						var id_produccion=$('.id_produccion').val();
						var id_plan=$('.id_plan_diario').val();
						var id_unidad=$('.id_unidad').val();
						var producidos=$('#inicio_produccion_minutos').val();
						var fin_produccion=$('#tomar_fin_produccion-2').val();
						if(fin_produccion){
							fin_produccion=0
						}else{
							fin_produccion=1
						}
						if(producidos){
							var f=0;
						}else{
							var f=1;
						}
						var id_plan_escena=$('.id_plan_escena').val();
						datos= {minutos_reales: minutos_reales,segundos_reales:segundos_reales,id_escena:id_escena,id_produccion:id_produccion,id_plan:id_plan,id_unidad:id_unidad,id_plan_escena:id_plan_escena,f:f,fin_produccion:fin_produccion}
						$.ajax({
							type: "POST",
							url: site_url+"/continuidad/guardar_tiempo_real",
							data: datos,
							dataType: "json",
							success:function(data){
								alert(tiempo_real_guardado)
								location.reload(true);
							}
						});
					}else{
						alert(los_segundos_no_puede_ser_mayor);
					}	
					/*$('#btn_ins').html('<a class="btn_tomar_ahora_tiempo" onclick="carga()" id="btn_change">INICIAR</a>');
					$('#t_real').removeClass('tiempo_real');
					$('.ancho_a').removeClass('ancho');
					$('.treal').css('width','134px');*/
	     	}else{
	     		alert(se_debe_ingresar_el_inicio_ensayo);
	     	}
	    	
	    });

	     $('#btn_ins').on('click','#save_tiempo_real2',function(){
	     	var value=$('#incio_ensayo_minutos').val();	
	     	if(value){
					var tiempo=$('#t_real').val();
					/*var segundos_reales=contador_s-1;
					var minutos_reales=contador_m;*/
					var segundos_reales=$('#segundos_real2').val();
					console.log(segundos_reales)
						var minutos_reales=$('#minutos_real2').val();
						var id_escena=$('.id_escena').val();
						var id_produccion=$('.id_produccion').val();
						var id_plan=$('.id_plan_diario').val();
						var id_unidad=$('.id_unidad').val();
						var producidos=$('#inicio_produccion_minutos').val();
						var fin_produccion=$('#tomar_fin_produccion-2').val();
						if(fin_produccion){
							fin_produccion=0
						}else{
							fin_produccion=1
						}
						if(producidos){
							var f=0;
						}else{
							var f=1;
						}
						var id_plan_escena=$('.id_plan_escena').val();
						datos= {minutos_reales: minutos_reales,segundos_reales:segundos_reales,id_escena:id_escena,id_produccion:id_produccion,id_plan:id_plan,id_unidad:id_unidad,id_plan_escena:id_plan_escena,f:f,fin_produccion:fin_produccion}
						$.ajax({
							type: "POST",
							url: site_url+"/continuidad/guardar_tiempo_real",
							data: datos,
							dataType: "json",
							success:function(data){
								alert(tiempo_real_guardado)
								 location.reload(true);
							}
						});
					
					/*$('#btn_ins').html('<a class="btn_tomar_ahora_tiempo" onclick="carga()" id="btn_change">INICIAR</a>');
					$('#t_real').removeClass('tiempo_real');
					$('.ancho_a').removeClass('ancho');
					$('.treal').css('width','134px');*/
	     	}else{
	     		alert(se_debe_ingresar_el_inicio_ensayo);
	     	}
	    	
	    });

	  $('.corteGeneral').click(function(){
	  	 var id_plan=$('.id_plan').val();
	  	 var hora=$('#horas_wrap_time').val();
	  	 var minutos=$('#minutos_wrap_time').val();
	  	 var time=$('#am_pm_wrap_time').val();
	  	 if(hora && minutos && time){
	  	    if(time=='PM'){
	  	     hora=parseInt(hora)+12;
	  	 	}
            datos= {id_plan: id_plan,hora:hora,minutos:minutos}
				$.ajax({
					type: "POST",
					url: site_url+"/continuidad/guardar_corteGenal",
					data: datos,
					dataType: "json",
					success:function(data){
						location.reload(true);
					}
		   });
	  	 }
	  	 

	  });




	  $('.cerrar_plan').click(function(){
	  	if(confirm(esta_seguro_que_desea_cerrar_el_plan)){
			confirmation=true;
		}
          if(confirmation){
          	var id_plan=$('.id_plan').val();
          	 datos= {id_plan: id_plan}
          	$.ajax({
	        type: "POST",
	        url: site_url+"/continuidad/completar_plan",
	        data: datos,
	        async:false,
	        dataType: "json",
	          success:function(data){
	          	if(data.completar_plan==1){
	          		switch(data.estado){
	          			case 5:
	          				var datos={idplan:id_plan}
	  			  	        $.ajax({
						        type: "POST",
						        async:false,
						        url: site_url+"/continuidad/cerrar_plan",
						        data: datos,
						        dataType: "json",
						        success:function(data){
						        	location.reload(true);
						        }	
						    });
	          			break;
	          		}
	          	}
	          	location.reload(true);
	          }	
	        });
		 }
	  }); 


	 $('.contenido_sup').on('click','.tomar_hora_manual',function(){
	 	var tipo=$(this).attr('data-tipo')
	 	$('.hora_servidor-'+tipo).hide();
	 	$('.hora_manual-'+tipo).show();
	 }) 
	 
	 $('#guardar_comentario_user').click(function(){
        var id_plan=$('.id_plan').val()
        var comentario=$('.comentario_user').val()
	        datos= {id_plan: id_plan,comentario:comentario}
					$.ajax({
						type: "POST",
						url: site_url+"/continuidad/guardar_comentario",
						data: datos,
						dataType: "json",
						success:function(data){
							 location.reload(true);
						}
			   });
	 });

	 $('.eliminar_comentario').click(function(){
	 	if(confirm(esta_seguro_de_eliminar_este_comentario)){
			var id_comentario=$(this).attr('id');
			var datos={id_comentario:id_comentario}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/plan_diario/eliminar_comentario",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	if(data.eliminar==1){
	          	  $('#comentario_'+id_comentario).remove();	
	          	} else{
	          		alert(no_se_puede_elimanar_comentario);
	          	}
	          }	
	       });
	     }   
	});

		 $('.escenas_plan').click(function(){
             var tiempo=$(this).attr('data-tiempo');
             if(tiempo==1){
             	if(confirm(esta_escena_ya_tiene_un_inicio_de_ensayo)){

             	}else{
             	  return false;	
             	}
             }
             
		 });
		 	$('.reabrir_plan').click(function(){
				var id_plan=$('.id_plan').val();
				var idproduccion = $('#idproduccion').val();
				if(confirm(esta_seguro_que_desea_reabrir_el_plan)){
			  		var datos={idplan:id_plan, idproduccion:idproduccion}
			  	        $.ajax({
				        type: "POST",
				        async:false,
				        url: site_url+"/continuidad/reabrir_plan",
				        data: datos,
				        dataType: "json",
				        success:function(data){
				        	location.reload(true);
				        }	
				    });
			    }
			});


  $('.multiescenas').click(function(){
     var escenas=new Array(); 
        for (var i=0; i < document.commentForm.multiescena.length; i++){
          if (document.commentForm.multiescena[i].checked){
          escenas[i] =document.commentForm.multiescena[i].value;
          }
        }
        if(escenas.length>0){
        	var hora=$('#tomar_hora_ensayo-2').val();
        	
        	hora = hora.split(" ");
        	if(hora[1]=="AM"){
        		var hora_en=hora[0]
        	}else{
        		var h=hora[0].split(":");
        		var hora_en=(parseInt(h[0])+12)+':'+h[1]+':'+h[2];
        	}

             datos= {escenas: escenas,hora:hora_en}
					$.ajax({
						type: "POST",
						url: site_url+"/continuidad/guardar_incioEnsayo_multiescenas",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data.respuesta==1){
								alert(escenas_actualizadas)
							}
						}
			   });
        }
      
  });

  $('.imagenes').on('click','.eliminar_cont',function(){
	  	if(confirm(esta_seguro_que_desea_eliminar_esta_continuidad)){
		 	var id_continuidad=$(this).attr('data-idcontinuidad');
		 	var cont=$(this).attr('data-cont');
		 	var id_produccion=$(this).attr('data-idproduccion');
		 	var elemento=$(this).attr('data-personaje');
		 	
		 	datos= {id_continuidad: id_continuidad,continuidad:cont,id_produccion:id_produccion,elemento:elemento}
						$.ajax({
							type: "POST",
							url: site_url+"/continuidad/eliminar_continuidad",
							data: datos,
							dataType: "json",
							success:function(data){
								if(data.resultado=="1"){
									$('#continuidad_'+id_continuidad).remove();
									alert(continuidad_eliminada);
								}else{
									
								}
							}
		    });
		}		
  }); 

  $('.imagenes').on('click','.eliminar_cont_set',function(){
	  	if(confirm(esta_seguro_que_desea_elimnar_esta_foto)){
		 	var id_continuidad=$(this).attr('data-idcontinuidad');
		 	var id_produccion=$(this).attr('data-idproduccion');
		 	var set=$(this).attr('data-set');
		 	
		 	datos= {id_continuidad: id_continuidad,id_produccion:id_produccion,set:set}
						$.ajax({
							type: "POST",
							url: site_url+"/continuidad/eliminar_continuidad_set",
							data: datos,
							dataType: "json",
							success:function(data){
								if(data.resultado=="1"){
									$('#continuidad_'+id_continuidad).remove();
									alert(foto_eliminada);
								}else{
									
								}
							}
		    });
		}		
  }); 
 

});

	//=============== CRONOMETRO ===================
		
		var cronometro;
	    var contador_s =0;
	    var contador_m =0;

	    var minutos_temp = 0;
	    var segundos_temp = 0;

	    function cargar_cronometro(){

	    	s = $("#segundos_real2");
			m = $("#minutos_real2");

	        if(contador_s==60){
		        contador_s=0;
			    contador_m++;
		    
		        if(contador_m<10){
			        contador_m='0'+contador_m;
			    }
		        
		        m.val(contador_m)

				if(contador_m==60)
				{
				    contador_m=0;
				}
			}else{
				if(contador_m<1){
				  	contador_m='00';	
				}
		    }
				
		    if(contador_s<10){
				contador_s='0'+contador_s;
			}
			
			m.val(contador_m);
			s.val(contador_s);

			contador_s++;
	    }


    
	 
	    function iniciar_cronometro(){
            
	    	if(cronometro){
	    		clearInterval(cronometro);
	    	}

	    	cronometro = setInterval(cargar_cronometro,1000);
	    	
	    	$('#input_spinner').css('display','none');
	    	$('#input_normal').css('display','block');

	    	minutos_temp =  ($('#minutos_real2').val() != '') ? $('#minutos_real2').val() : '00';
	    	segundos_temp = ($('#segundos_real2').val() != '') ? $('#segundos_real2').val() : '00';
	    	
	    	
			
			$('.input_div input').addClass('cronometro_activo');
			$('.tr_real').addClass('cronometro_activo');
			$('#cronometro-span').removeClass('hidden_span');

			$('#iniciar_cronometro').removeClass('btn_tomar_ahora');
			$('#iniciar_cronometro').addClass('btn_parar');
			$('#iniciar_cronometro').html('PARAR');
			$('#save_tiempo_real').css('display', 'none');
			$('#btn_desprod').css('display','none');
			$('#iniciar_cronometro').css('width', '99%');
		    $('#iniciar_cronometro').attr('onclick','detener_cronometro()');
	    }

	    function detener_cronometro()  {
	        clearInterval(cronometro);
	        $('#hidden_span').removeClass('hidden_span');

	        $('#retornar_cronometro').removeClass('hidden_span');
	        $('#cronometro-span, #minutos_span, #segundos_span').css('color', '#FFFFFF');
	        $('#minutos_span').html(minutos_temp);
	        $('#segundos_span').html(segundos_temp);
	        $("#btn_ins").html("<a href='#' class='btn_tomar_ahora btn_t_real' id='save_tiempo_real2'>"+guardar+"</a><a href='#' class='btn_tomar_ahora btn_t_real' onclick='retomar_cronometro()' id='btn_retoma'>RETOMAR</a><a href='#' class='btn_tomar_ahora btn_t_real' onclick='borrar_cronometro()'>"+eliminar+"</a>");
        }

	    function retomar_cronometro()  {


	        $("#btn_ins").html('<a class="btn_tomar_ahora_tiempo btn_parar" id="iniciar_cronometro" onclick="detener_cronometro()">'+para_cronometro+'</a>');
	        iniciar_cronometro();
	        $('#hidden_span').addClass('hidden_span');
	        $('#retornar_cronometro').addClass('hidden_span');
	        $('#cronometro-span, #minutos_span, #segundos_span').css('color', '#000000');
	        
        }

	    
		function borrar_cronometro(){
		    contador_s=0;
		  	contador_m=0;
		  	$('#minutos_real2').val('00');
		  	$('#segundos_real2').val('00');
		  	$('#minutos_span2').html('00');
	        $('#segundos_span2').html('00');
	        $('#input_spinner').css('display','block');
	    	$('#input_normal').css('display','none');
	    	$('#btn_desprod').css('display','block');
	        $('#cronometro-span').addClass('hidden_span');
	        $('#retornar_cronometro').addClass('hidden_span');
	        $('.input_div input').removeClass('cronometro_activo');
			$('.tr_real').removeClass('cronometro_activo');
	        $("#btn_ins").html('<a class="btn_tomar_ahora_tiempo" id="iniciar_cronometro">'+iniciar+'</a> <a  class="btn_tomar_ahora_tiempo"  id="save_tiempo_real">'+guardar+'</a>');
		  	return false;
		  }

		  function retornar_cronometro(){
		  	  
		  	 contador_m = Number($('#minutos_span').html());
	         contador_s = Number($('#segundos_span').html());
	         
	         $('#minutos_real2').val($('#minutos_span').html());
	         $('#segundos_real2').val($('#segundos_span').html());

	         //iniciar_cronometro();
		  }

$(function() {
	
    $( "#accordion" ).accordion({ header: "h3", collapsible: true, active: false });
    $("#acc_locaciones").accordion({ header: "h4", collapsible: true, active: false });
});

$(document).ready(function() {
    $('.fancybox').fancybox({
      padding: 20,
      openEffect : 'fade',
      closeEffect : 'fade',
    });
});


$(document).ready(function($) {
          
          /*$('#banner-slide').bjqs({
            animtype      : 'slide',
            height        : 452,
            width         : 552,
            responsive    : false,
            centercontrols : true, 
            showmarkers : false,
            usecaptions : true,
            automatic:false,
          });*/
          
        });

$(document).ready(function($) {
	$('.rol').click(function(){
		var self = $(this);
		$('.cargar_mas').fadeIn();
		//$('.cont_personaje').val('');
			 var rol=$(this).attr('data-rol');
			 var id_produccion=$(this).attr('data-idproduccion');
			 var limit=$('.limit_'+rol).val()
			 var continuidad=$('.continuidad').val()
			 var like=$('.personaje').val();
			 if(!like){
			 	like='';
			 }
			 datos= {rol: rol,id_produccion:id_produccion,limit:limit,continuidad:continuidad,like:like}
			$.ajax({
				type: "POST",
				url: site_url+"/continuidad/elementos_produccion_rol",
				data: datos,
				dataType: "json",
				success:function(data){
					var html='';
					var total_datos=data.personajes.length;
					if(total_datos>0){
						var cont=0;
					    $.each(data.personajes, function(i,p){
					    	var c='';
						  		if(continuidad){
                                   c=continuidad;
						  		}else{
                                   c='null';
						  		}
						  	if(p.imagen){
						  		
							  	html+='<div><div class="elemento_cont_link" data-continuidad="'+p.continuidad+'" data-continuidad2="'+c+'" data-idelemento="'+p.id_elemento+'"><img src="'+site_url2+'/'+p.imagen+'" height="10" width="10"></div><p>'+p.nombre.toUpperCase()+'<p>';
							  	if($('#permisos_usuario').val()=="write"){
							  	  	'<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
							  	}
							  	html+='</div>';
						  	}else{
						  		html+='<div><div class="elemento_cont_link" data-continuidad="'+p.continuidad+'" data-continuidad2="'+c+'" data-idelemento="'+p.id_elemento+'"><img src="'+site_url2+'/images/continuidad/no_image.png" /></div><p>'+p.nombre.toUpperCase()+'<p>';
						  		if($('#permisos_usuario').val()=="write"){
						  	  		'<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
						  	  	}
						  	  	html+='</div>';
						  	}
						 	cont++;
					    });	
					    self.prev('.count_characters').html(data.personajes.length);
					    	
					    
					    self.find('.count_characters').html(' ('+data.personajes.length+')');
					  if(total_datos>=data.total){
					  	$('.cargar_mas').fadeOut();
					  }
					  var total=parseInt(limit)+parseInt(cont);
                       $('.limit_'+rol).val(total)
                       document.getElementById("listado_"+rol).innerHTML=html;
					}else{
						self.find('.count_characters').html('');
						$('.cargar_mas').fadeOut();
                      html='<div class="" style="width:265px">'+no_hay_concidencias_para_esta_busqueda+'</div>';
					}
					document.getElementById("listado_"+rol).innerHTML=html;
                    //$('.listado_'+rol).before(html)

				}
			});
	  });


	$('.cargar_mas').click(function(){
			 var rol=$(this).attr('data-rol');
			 var id_produccion=$(this).attr('data-idproduccion');
			 var limit=$('.limit_'+rol).val()
			 var continuidad=$('.continuidad').val()
			 var like=$('.personaje').val();
			 datos= {rol: rol,id_produccion:id_produccion,limit:limit,continuidad:continuidad,like:like}
			$.ajax({
				type: "POST",
				url: site_url+"/continuidad/elementos_produccion_rol",
				data: datos,
				dataType: "json",
				success:function(data){
					var html='';
					var total_datos=data.personajes.length;
					if(total_datos>0){
						var cont=0;
					  $.each(data.personajes, function(i,p){
					  	
						  	if(p.imagen){
						  	    html+='<div><div class="elemento_cont_link" data-idelemento="'+p.id_elemento+'"><img src="'+site_url2+'/'+p.imagen+'" height="10" width="10"></div><p>'+p.nombre+'<p>';
						  	    if($('#permisos_usuario').val()=="write"){
						  	    	html+='<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
						  		}
						  		html+='</div>';
						  	}else{
						  		html+='<div><div class="elemento_cont_link" data-idelemento="'+p.id_elemento+'"><img src="'+site_url2+'/images/continuidad/no_image.png" /></div><p>'+p.nombre+'<p>';
						  		if($('#permisos_usuario').val()=="write"){
						  	    	html+='<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
						  		}
						  		html+='</div>';
						  	}
 
						 cont++;
					   });	
					  var total=parseInt(limit)+parseInt(cont);
                       $('.limit_'+rol).val(total)

                      if(total_datos>=data.total){
					  	$('.cargar_mas').fadeOut();
					  }
                       document.getElementById("listado_"+rol).innerHTML=html;
                       //$('.listado_'+rol).before(html)
					}else{
						alert(no_hay_mas_elementos_para_cargar);
					}
					

				}
			});
	  });


	$('.personaje').keyup(function(){
		      $('.cargar_mas').fadeOut();
			 var rol=$(this).attr('data-rol');
			 console.log(rol);
			 var id_produccion=$(this).attr('data-idproduccion');
			 var limit=$('.limit_'+rol).val()
			 //var continuidad=$('.continuidad').val()
			 var continuidad='';
			 //var like=$('.personaje').val();
			 var like=$(this).val();
			 datos= {rol: rol,id_produccion:id_produccion,limit:limit,continuidad:continuidad,like:like}
			$.ajax({
				type: "POST",
				url: site_url+"/continuidad/elementos_produccion_rol",
				data: datos,
				dataType: "json",
				success:function(data){
					var html='';
					var total_datos=data.personajes.length;
					if(total_datos>0){
						var cont=0;
					  $.each(data.personajes, function(i,p){
					  	
						  	if(p.imagen){
							    html+='<div><div class="elemento_cont_link" data-idelemento="'+p.id_elemento+'"><img src="'+site_url+'/'+p.imagen+'" /></div><p>'+p.nombre+'<p>';
								if($('#permisos_usuario').val()=="write"){
								    html+='<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
								}
							    html+='</div>';

							}else{
						  		html=html+'<div><div class="elemento_cont_link" data-idelemento="'+p.id_elemento+'"><img src="'+site_url+'/images/continuidad/no_image.png" /></div><p>'+p.nombre+'<p>';
						  		if($('#permisos_usuario').val()=="write"){
								    html+='<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
								}
							    html+='</div>';
						  	}
						 cont++;
					   });	
					  var total=parseInt(limit)+parseInt(cont);
                       $('.limit_'+rol).val(10)
                       document.getElementById("listado_"+rol).innerHTML=html;
                       //$('.listado_'+rol).before(html)
                       //$('.listado_'+rol).html(html)
					}else{
						alert(no_hay_elemento_que_concidan_con_esta_busqueda);
					}
					
				}
			});
	  });

	$('.segundos_real').click(function(){
		var value=$('#incio_ensayo_minutos').val();	
	     	if(value){
	     		var s=$(this).val();
				if(s=='00'){
					$('.segundos_real').val('')
				}
	     	}else{
               alert(se_debe_ingresar_el_inicio_ensayo)
	     	}
		
	});





	$('.minutos_real').click(function(){
		var value=$('#incio_ensayo_minutos').val();	
	     	if(value){
	     		var s=$(this).val();
				if(s=='00'){
					$('.minutos_real').val('')
				}
	     	}else{
               alert(se_debe_ingresar_el_inicio_ensayo)
	     	}
	})


	$('.segundos_real').change(function(){
		var value=$(this).val();	
		console.log(value);
		console.log(parseInt(value));
	     	if(isNaN(parseInt(value))){
					$('.segundos_real').val('00')
					alert(se_debe_ingresar_un_valor_valido);
	     	}else{
               
	     	}
		
	});

	$('.minutos_real').change(function(){
		var value=$(this).val();	
		console.log(value);
		console.log(parseInt(value));
	     	if(isNaN(parseInt(value))){
					$('.minutos_real').val('00')
					alert(se_debe_ingresar_un_valor_valido);
	     	}else{
               
	     	}
		
	});

	

	$('.colapsar').click(function(){
		$( "#accordion" ).accordion({ header: "h3", collapsible: true, active: false });
		$('.cargar_mas').fadeIn();

		if($('.personaje').val()!=""){

			$("#accordion h3").each(function() {
				$(this).click();
			});
			
		}else{
			$('.count_characters').html('');
		}
		$( "#accordion" ).accordion({ header: "h3", collapsible: true, active: false });
	});  
    
    $('#accordion').on('click','.elemento_cont_link',function(){
      var id_produccion=$('.id_produccion').val();
      var cont=$(this).attr('data-continuidad');
      var cont2=$(this).attr('data-continuidad2');
      var id_elemento=$(this).attr('data-idelemento');
      var palabra = $('#personaje_general').val();
      if(cont){
		window.location.href = site_url+'/continuidad/view_elemento_con/'+id_produccion+'/'+id_elemento+'/'+cont+'/'+cont2+'/'+palabra;
      }else{
        window.location.href = site_url+'/continuidad/view_elemento_con/'+id_produccion+'/'+id_elemento+'/null/'+palabra;
      }
	});

	

	$('#capitulos_continuidad').change(function(){
    var cadena_escenas="";
    datos= {idcapitulo: $('#capitulos_continuidad').val(), idplan: $('#idplan').val()}
    $.ajax({
      type: "POST",
      url: site_url+"/continuidad/buscar_escenas_capitulo",
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

  var pathname = window.location.pathname;  
  if(pathname.search("continuidad/plan_diario")>0){
    $('#unidad_plan').change();
    $('#capitulos_continuidad').change();
  }

  $('.numero_imagenes').change(function(){
  	var numero_imagenes=$(this).val();
  	if(numero_imagenes>=i2){
  		if(numero_imagenes!='1'){
		  		numero_imagenes=parseInt(numero_imagenes);
		  		for (var i=i2;i<=numero_imagenes;i++){ 
		             var html='<div class="uploading" id="uploading_'+i+'"></div><div class="upload_img conten_'+i+'"><input type="file"  id="btn_subir_foto_'+i+'" name="imagen_'+i+'"> </div>'
		  			$('#cajas').after(html)
				}
				i2=i;
		  	}
  	}else{
  		i2=i2-1;
  		for (var i=i2;i>=numero_imagenes;i--){ 
		           $('#uploading_'+i2).remove();
		           $('.conten_'+i2).remove();
		}
  	}
		  	

  })

  $('.locaciones').click(function(){
  	 
  	 var id_locacion=$(this).attr('data-idlocacion');
     var id_produccion=$(this).attr('data-idproduccion');
		 datos= {id_locacion: id_locacion}
					$.ajax({
						type: "POST",
						url: site_url+"/continuidad/cargar_sets",
						data: datos,
						dataType: "json",
						success:function(data){
							if(data.sets){
								var cont=0;
								html='';
							    $.each(data.sets, function(i,s){
								  	if(s.imagen){
									  	html+='<div><div class="" data-idset="'+s.id_set+'"><a href="'+site_url+'/continuidad/set/'+id_produccion+'/'+s.id_set+'"><img src="'+site_url2+'/'+s.imagen+'" height="10" width="10"></a></div><p>'+s.nombre.toUpperCase()+'<p>';
									  	if($('#permisos_usuario').val()=="write"){
									  	  	'<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
									  	}
									  	html+='</div>';
								  	}else{
								  		html+='<div><div class="" data-idset="'+s.id_set+'"><a href="'+site_url+'/continuidad/set/'+id_produccion+'/'+s.id_set+'"><img src="'+site_url2+'/images/continuidad/no_image.png" /></a></div><p>'+s.nombre.toUpperCase()+'<p>';
								  		if($('#permisos_usuario').val()=="write"){
								  	  		'<a href="'+site_url+'/continuidad/crear_continuidad/'+id_produccion+'">'+tomar_foto+'</a>';
								  	  	}
								  	  	html+='</div>';
								  	}
								 	cont++;
							    });	
							    //$('#sets_'+id_locacion).fadeIn();
							    $('#sets_'+id_locacion).html(html);

							}else{
								$('#sets_carga').html('<div>'+no_hay_set_para_esta_locacion+'</div>');
							}
						}
	    });
		return false;
  });

 
	 $('.locacion').change(function(){
	 	 var id_locacion=$(this).val();
	 	 datos= {id_locacion: id_locacion}
			$.ajax({
				type: "POST",
				url: site_url+"/continuidad/locaciones",
				data: datos,
				dataType: "json",
				success:function(data){
					if(data.sets){
						var html="<p>SET:</p><select name='set' class='set'>"
						$.each(data.sets, function(i,s){
                           html+='<option value="'+s.id+'">'+s.nombre+'</option>';
						});
                        html+='</select>' 
                        $('.sets').html(html);
					}else{
						$('.sets').html(''+no_hay_set_para_esta_locacion+'');
					}
				}
		    });
	 });
    
	 $('.list_fotos_sets').on('click','.eliminar_foto',function(){
        var foto=$(this).attr('data-foto');
        var name=$(this).attr('data-nameimagen');
        
        
        $('.imagen_set_'+foto).remove();
		imagenes_elminadas = imagenes_elminadas.concat(name);
		$('.imagenes_eliminadas').val(imagenes_elminadas);
		var va=$('.imagenes_eliminadas').val();
        console.log(va)
		
		
        /*var uploadFile = document.getElementById ("fotos_sets");
        console.log(uploadFile.files);*/
 
	 });

	 $('.tomar_list_fotos_sets').on('click','.eliminar_foto',function(){
        var foto=$(this).attr('data-foto');
        var name=$(this).attr('data-nameimagen');
        
        
        $('.tomar_imagen_set_'+foto).remove();
		imagenes_elminadas = imagenes_elminadas.concat(name);
		$('.imagenes_eliminadas').val(imagenes_elminadas);
		var va=$('.imagenes_eliminadas').val();
        console.log(va)
		
		
        /*var uploadFile = document.getElementById ("fotos_sets");
        console.log(uploadFile.files);*/
 
	 });

	 $('#toogle_comment').on('click', function(){
	 	$('#nota').toggle();
	 });
  

  $('.colapsar').click();

  $('.tomar_foto').click(function(){
    $('.cantidad_fotos').fadeIn();
  });
  $('.cantidad_fotos').change(function(){
  	$('.tomar_list_fotos_sets').html('');
  	cont_tomar=0;
  	var cant=parseInt($(this).val());
  	var html='';
  	for (var i = 1; i <= cant; i++) {
  	    
  		html=html+'<li class="cantidad_'+i+'"><input  type="file" class="imagenes_camara" id="'+i+'" name="fotos_tomados_'+i+'"></li>';
  	};
  	console.log(html)
  	$('.tomar_list_fotos_sets').html(html);
  	$('.cargar_imagenes').fadeIn();
  })

  $('.tomar_list_fotos_sets').on('click','.eliminar_foto_camara',function(){
  	var foto=$(this).attr('data-foto');
  	$('.numero_foto_'+foto).remove();
  });

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
          var idescenas = $('#escenas').val();
          var ids = idescenas.split(',');
          var comparacion="";
          var confirmacion="";
          var estado=false;
          var datos2={idunidad:$('#unidad_selector').val(),fecha:$('#start_plan').val()};
          var valida=0;
          $.ajax({
            type: "POST",
            async:false,
            url: site_url+"/continuidad/buscar_estado_plan",
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
                  url: site_url+"/continuidad/buscar_asignadas",
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
                  alert(la_escena+ $('#row2_'+ids).attr('data-libreto')+ '/' +$('#row2_'+ids).attr('data-numero')+ ya_pertenede_a_un_plan);
                }
                }  
              });
            
            if(pathname.search("plan_diario/")>=0){
              if(!confirm(desea_asignar+' '+confirmacion+' '+al_plan_diario_del_dia+' '+$('#fecha_unidad_plan').val()+' '+de_la_unidad+' '+unidad_selected.replace("unidad","")+' ?')){
                var temp_e = comparacion.split(',');
                for (var i = 0; i < temp_e.length; i++) {
                   $('#escenas').val($('#escenas').val().replace(temp_e[i]+',', ""));
                };
                $('#escenas').val("");
                console.log($('#escenas').val()+'------'+comparacion);
              }
            }else{
              if(!confirm(confirmacion+desea_asignar_estas_escenas_al_plan_diario_del_dia+' '+$('#start_plan').val()+' '+de_la_unidad+' '+unidad_selected.replace("unidad","")+' ?')){
                var temp_e = comparacion.split(',');
                for (var i = 0; i < temp_e.length; i++) {
                   $('#escenas').val($('#escenas').val().replace(temp_e[i]+',', ""));
                };
                $('#idplanes').val("");
                console.log($('#escenas').val()+'------'+comparacion);
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


});


    /*if (document.getElementsByClassName("btn_subir_foto2") != null) {
       document.getElementsByClassName("btn_subir_foto2").addEventListener('change', archivo2, false);
    }*/
    document.addEventListener('change', function(e){
    	//alert(e.target.id)

    	 archivo2(e.target.files,e.target.id)
    	

    });

    function archivo2(evt,id) {
    	if(id=="fotos_sets"){
    		$('#loader_gif').fadeIn();
    	    $('.list_fotos_sets').append('<li>'+cargando+'...</li>');
            var files = evt;
            console.log(evt)
	        var id2=id;
	        if(files){
	        	id = id.split("_");
	        	imagenes_elminadas = new Array();
	        	if (files['0']['type']=="image/jpeg" || files['0']['type']=="image/jpg" || files['0']['type']=="image/png"){
		        	$('.list_fotos_sets').html('');
		        	var cont =0;
			        for (var i = 0, f; f = files[i]; i++) {
			            if (!f.type.match('image.*')) {
			                continue;
			            }
			            var reader = new FileReader();
			            var status = document.getElementById('status');
			           
			           
			            reader.onload = (function(theFile) {
			                return function(e) {
			                    //document.getElementById('uploading_'+id['3']).innerHTML = ['<img class="thumb" id="imagen64" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                                var html='<li class="imagen_set_'+cont+'"><div class="div_thumb"><img class="thumb" id="imagen64"  width="50" src="'+e.target.result+'" title="'+escape(theFile.name)+'"/><div class="eliminar_foto btn_naranja" data-foto="'+cont+'" data-nameimagen="'+escape(theFile.name)+'">'+eliminar+'</div></div></li>';
                                cont++;
                                $('.list_fotos_sets').append(html);
                                $('#loader_gif').fadeOut();
                                //$('#fotos_sets').val(e.target.result);
			                };
			            })(f);
						reader.readAsDataURL(f);

		        	}
		        }else{
	                $('#btn_subir_foto_'+id['3']).val('');
		        	alert(el_formatio_del_archivo_no_es_permitido);
		        }	
		     } 
    	}else{
    	    var files = evt;
	        var id2=id;
	        if(files){
	        	$('#loader_gif').fadeIn();
    	   		 //$('.list_fotos_sets').append('<li>'+cargando+'...</li>');
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
			                     var html='<div class="div_thumb numero_foto_'+cont_tomar+'"><img class="thumb" id="imagen64"  width="50" src="'+e.target.result+'" title="'+escape(theFile.name)+'"/><div class="'+eliminar+'_foto_camara btn_naranja" data-foto="'+cont_tomar+'" data-nameimagen="'+escape(theFile.name)+'">'+eliminar+'</div></div>';
                                cont_tomar++;
                                $('.cantidad_'+id).append(html);
                                $('#loader_gif').fadeOut();
			                };
			            })(f);
			            reader.readAsDataURL(f);

		        	}
		        }else{
	                $('#'+id).val('');
		        	alert(el_formatio_del_archivo_no_es_permitido);
		        }	
		     } 
		 }      
    }

function validar_form_set(){
	var set=$('.set').val();
	
	if(!set){
		alert(debe_seleccionar_un_set);
		return false;
	}
	var uploadFile = document.getElementById ("fotos_sets");
	if(uploadFile.files.length=='0'){

		var v=$('.cantidad_fotos').css('display')
		if(v=='none'){
			alert(cargar_imagen);
			return false;	
		}else{
				var fotos_camara=parseInt($('.cantidad_fotos').val());
				var validar=0;
				for (var i = 1; i <= fotos_camara; i++) {
					var uploadFile = document.getElementById (i);
					if(uploadFile.files.length=='0'){
					}else{
						var validar=1;
					}
				};
				if(validar==1){
		          $('#wrapper_loader').fadeIn();	
				}else{
				  alert(cargar_imagen);
				  return false;	
				}
		}		

		
	}else{
	  $('#wrapper_loader').fadeIn();	
	}
}

 function validar_horaLlamado(){
 	var hora=$('#horas_llamado').val();
 	var minutos=$('#minutos_llamado').val();
 	var horario=$('#am_pm_llamado').val();
 	if(hora && minutos && horario){
      return true;	
 	}else{
 	  alert(debe_seleccionar_la_hora_de_llamada_de_la_unidad);
 	  return false;	
 	}
 	
 }

