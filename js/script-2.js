$(document).ready(function() {
	$('.save_plan_diario').click(function(){
		var valor2='0';
		var idproduccion=$('.id_produccion').val();
		var valor=$(this).parent().parent().parent().find('.value').val();
		var tipo=$(this).parent().parent().parent().find('.tipo').val();
		var id_plan_actual = $(this).parent().parent().parent().find('.id_plan_actual').val();
		var idunidad = $('#unidad_plan').val();
		estado = "";
		var validator = true;
		if(tipo==1){
			var valor2=$(this).parent().parent().parent().find('.value2').val();
			estado = $(this).parent().parent().parent().find('.estado').val();
			// if(estado!='2' && estado!='1'){
				  if((valor=="" || valor=="00" || valor=="0")  && (valor2=="" || valor2=="00" || valor2=="0")
    				|| (valor2=="" && valor=="00" && valor=="0") || (valor=="" && valor2=="00" && valor2=="00" || parseInt(valor2)>59)){
				  	validator = false;
				    $(this).parent().parent().parent().find('label.error_2').remove();
				    $(this).parent().parent().parent().find('.value').parent().append('<label class="error_2" style="color:red;font-weight:normal;">'+segundos_deben_ser_menores_a+'</label>');
				    $(this).parent().parent().parent().find('.value').addClass('error');
				    $(this).parent().parent().parent().find('.value2').addClass('error');
				  }else{
				  	validator = true;
				  }
			// }else{
			// 	validator = true;
			// }
		}else{
			validator = true;
		}

		if(tipo==2 || tipo==3 || tipo==4){
		    var horas=$(this).parent().parent().parent().find('.horas').val();
		    var minutos=$(this).parent().parent().parent().find('.minutos').val();
		    var am_pm=$(this).parent().parent().parent().find('.am_pm').val();
		    valor = horas+':'+minutos+' '+am_pm;
		}

		if(validator){
			var id_plan=$(this).parent().parent().parent().find('.id_plan').val();
			var id_plan_actual = $('#idplan').val();
			if(valor){
				var datos={valor:valor,valor2:valor2,tipo:tipo,id_plan:id_plan,idproduccion:idproduccion,id_plan_actual:id_plan_actual,idunidad:idunidad,estado:estado}
		        $.ajax({
		        type: "POST",
		        async:false,
		        url: site_url+"/plan_diario/guardar_elementos",
		        data: datos,
		        dataType: "json",
		          success:function(data){
		          	location.reload(true);
		          }
		       });
		     } 
		}
	});

	$('#editar_plan').click(function(){
      $('#plan_diario').fadeIn();
	});

	$('#cancel_editar_plan').click(function(){
      $('#plan_diario').fadeOut();
	});

	$('.eliminar_comentario').click(function(){
		$('#wrapper_loader').fadeIn();
		var id_comentario=$(this).attr('id');
		var datos = {id_comentario:id_comentario}
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
        $('#wrapper_loader').fadeOut();
	});

	$('.completar_plan').click(function(){
		var id_plan=$('.id_plan').val();
		var estado_plan = $('.estado_plan').html();
		var confirmation = false;
		if(estado_plan=="Abierto Privado"){
			if(confirm(esta_seguro_que_desea_publicar_el_plan)){
				confirmation=true;
			}
		}
		if(estado_plan=="Abierto" || estado_plan=="Re abierto" ){
			if($('#horas_wrap_time').val()!="" && $('#minutos_wrap_time').val()!="" && $('#am_pm_wrap_time').val()!=""){
				if(confirm(esta_seguro_que_desea_cerrar_el_plan)){
					confirmation=true;
				}
			}else{
				alert(aun_no_ha_ingresado_el);
				confirmation=false;
			}
				
		}

		if(confirmation){
			$('#wrapper_loader').fadeIn();
			var datos={id_plan:id_plan}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/plan_diario/completar_plan",
	        data: datos,
	        async:false,
	        dataType: "json",
	          success:function(data){
	          	if(data.completar_plan==1){
	          		switch(data.estado){
	          			case 3:
		          			$('.estado_plan').html('Abierto');
		          			$('.completar_plan').html('<span></span>'+cerrar_plan);
	          			break;
	          			case 5:
	          				var datos={idplan:id_plan}
	  			  	        $.ajax({
						        type: "POST",
						        async:false,
						        url: site_url+"/plan_diario/cerrar_plan",
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

	$('.reabir_plan').click(function(){
		var id_plan=$('.id_plan').val();
		var idproduccion = $('#idproduccion').val();
		if(confirm(esta_seguro_que_desea_reabrir_el_plan)){
			$('#wrapper_loader').fadeIn();
	  		var datos={idplan:id_plan, idproduccion:idproduccion}
	  	        $.ajax({
		        type: "POST",
		        async:false,
		        url: site_url+"/plan_diario/reabrir_plan",
		        data: datos,
		        dataType: "json",
		        success:function(data){
		        	location.reload(true);
		        }	
		    });
	    }
	});

	$('#find_plan').click(function(){
		if($('#fecha_unidad_plan').val()==""){
			$('#fecha_unidad_plan').addClass('error');
		}else{
			$('#fecha_unidad_plan').removeClass('error');
		}
	});

	$('#consult_list_button').click(function(){
		$('#consult_list').fadeIn();
		$('#consult_list_button').hide();
	});

	$('#cancel_consult_list').click(function(){
		$('#consult_list').fadeOut();
		$('#consult_list_button').fadeIn();
	});

	$('.delete_consult').click(function(){
		var idconsulta = $(this).attr('data-idconsulta');
		if(confirm(desea_eliminar_esta_consulta)){
			var datos={idconsulta:idconsulta}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/plan_general/eliminar_consulta",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	$('.consult_'+idconsulta).hide();
	          }	
	       	});
		}
	});

	$('.save_coment').click(function(){
		var comentario=$(this).parent().parent().parent().find('.comentario_plan_general').val();
		var id_semana=$(this).parent().parent().parent().find('.id_semana').val();
		var tipo=$(this).parent().parent().parent().find('.tipo').val();
		var id=id_semana;
		if(comentario){
			var datos={id_semana:id_semana,tipo:tipo,comentario:comentario,}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/plan_produccion/insert_coment",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	document.getElementById(id).innerHTML = comentario.substring(0,25)+'...';
	          	$('.hide_box').fadeOut();	
	          }	
	       	});
		}
	});
  	var user;
  	var userid;
  	var ind;
  	$('.director').focus(function(){
  		user=$(this).data('user');
  		userid=$(this).data('userid');
  		ind=indexof($(this));
  	});

     $('.director').on('change', function(){
		var cambio=$(this).parent().find('.plan_diario_director').val();
		if(cambio==1 && $(this).attr("data-user")!=0){
			if (confirm(esta_plan_ya_se_a_iniciado)) {
			}else{
				this.selectedIndex = ind;
			}
		}
	});

    $('#number_cap').change(function(){
    	var mini_cap=$(this).attr('data-minimocap');
    	    mini_cap=parseInt(mini_cap);
    	var valor=$(this).val();
    	valor=parseInt(valor);
    	if(mini_cap>valor){
            alert(el_minimo_de_libretos_no_ouede_ser_menor);
            $(this).val(mini_cap);  
    	}
    }) 

    $('#usersTable').on('click','.actualizar_locacion',function(){
         if (confirm(desea_editar_la_locacion + $(this).attr('data-nombre') + '?')) {
         	  var id_locacion=$(this).parent().find('.id_locacion').val();
         	  var nombre=$(this).parent().find('.nombre').val();
         	  var id_produccion=$(this).parent().find('.id_produccion').val();
         	  if(nombre.length ==0){
         	    alert(la_locacion_no_puede_estar_vacio);	
         	  }else{
	         	    var datos={id_produccion:id_produccion,nombre:nombre,id_locacion:id_locacion}
					$.ajax({
						type: "POST",
						url: site_url+"/elementos/editar_locacion2",
						data: datos,
						dataType: "json",
						success:function(data){
						 if(data.existe==1){
	                        alert(ya_existe_otra_locacion_con_este_nombre);
						 }else{
						 	location.reload(true);
						 }
						}	
					});
				}		
			}
    });

    $('#usersTable').on('click','.actualizar_set',function(){
 		if (confirm(desea_editar_el_set + $(this).attr('data-nombre')  + '?')) {
     	  var id_set=$(this).parent().find('.id_set').val();
     	  var nombre=$(this).parent().find('.nombre').val();
     	  var id_locacion=$(this).parent().find('.id_locacion').val();
     	  if(nombre.length ==0){
     	    alert(el_set_no_puede_estar_vacio);	
     	  }else{
         	  var datos={id_set:id_set,nombre:nombre,id_locacion:id_locacion}
				$.ajax({
					type: "POST",
					url: site_url+"/elementos/editar_set",
					data: datos,
					dataType: "json",
					success:function(data){
					 if(data.existe==1){
                        alert(ya_existe_otro_set_con_este_nombre);
					 }else{
					 	location.reload(true);
					 }
					}	
				});
          }
		}
    });

   $('#usersTable').on('click','.eliminar_locacion',function(){
    var id_locacion=$(this).attr('id');
    if(confirm(esta_seguro_que_desea_eliminar_esta_locacion)){
      datos= {id_locacion: id_locacion}
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/eliminar_locacion_ajax",
        data: datos,
          dataType: "json",
          success:function(data){
            $('.element_'+id_locacion).remove();
          }
      }); 
    }
  });

   $('#usersTable').on('click','.eliminar_sets',function(){
    var id_set=$(this).attr('id');
    if(confirm(esta_seguro_que_desea_eliminar_esta_set)){
      datos= {id_set: id_set}
      $.ajax({
        type: "POST",
        url: site_url+"/elementos/eliminar_sets_ajax",
        data: datos,
          dataType: "json",
          success:function(data){
            $('.element_'+id_set).remove();
          }
      }); 
    }
  });


   $('.filtrar_semana_herramientas').click(function(){
      	
      /*var semana1=$('.semana1').val();
      var semana2=$('.semana2').val();
      var s1=parseInt(semana1);
      var s2=parseInt(semana2);

      if(semana1 && semana2){
      	$('#wrapper_loader').fadeIn();
      	$('.semanas').hide();
        
        var i=s1;
        while(i<=s2){
        	var capitulo=$('.semana_'+i).attr('data-capitulospagar');
        	console.log('.semana_'+i); 
        	$('.semana_'+i).show();
          i++;
        }	
        	$('#wrapper_loader').fadeOut();
      }else{
      	alert('Debes seleccionar las semanas de filtro')
      }*/

      var semana1=$('.semana1').val();
      var semana2=$('.semana2').val();
      var id_produccion=$('#id_produccion').val();
      $('.filtro').val('1');
 

      if(semana1 && semana2){
      	$('#wrapper_loader').show();
      	 datos= {id_produccion:id_produccion,semana1: semana1,semana2:semana2}
	      $.ajax({
	        type: "POST",
	        url: site_url+"/casting/filtro_nomina",
	        data: datos,
	          dataType: "json",
	          success:function(data){
	          	 if(data.html_personajes){
	          	 	 $('.tabla_personajes').html(data.html_personajes);
	          	 }


	          	 if(data.html_semanas){
	          	 	 $('.tabla_semanas').html(data.html_semanas);
	          	 }
	          	 $('#wrapper_loader').hide();
	          }
	      }); 
      	
      }else{
      	alert(debes_seleccionar_las_semanas_de_filtro)
      }
      
   }); 

  $('.excel_nomina').click(function(){
  	  var filtro=$('.filtro').val();
  	  var id_produccion=$('#id_produccion').val();
  	  var semana1=$('.semana1').val();
      var semana2=$('.semana2').val();
  	  if(filtro==0){
         window.open(site_url+'/excel/excel_nomnia/'+id_produccion);
  	  }else{
         window.open(site_url+'/excel/excel_nomnia/'+id_produccion+'/'+semana1+'/'+semana2);
  	  }
  });



});

function fecha_inicio_grabacion(){
	var fecha_inicio_grabacion=$('#start_recording').val();
	var u1=$('#date_start1').val();
	var u2=$('#date_start2').val();
	var u3=$('#date_start3').val();
	var u4=$('#date_start4').val();
	var u5=$('#date_start5').val();
	if(fecha_inicio_grabacion==''){
		if(u1!='' || u2!='' || u3!='' || u4!='' || u5!=''){
          	if(confirm(no_se_ha_definido_un_inicio_de_grabacion)) {
		       return true;
		    }else{
		    	return false;
		    }
		} 
	}else{
		var validacion = true;
		var label = '<label style="color: #c60f13!important;" class="error label-error-hiden">'+fecha_invalida+'<label>';
		$('#date_start1').parent().find('.label-error-hiden').remove();
		$('#date_start2').parent().find('.label-error-hiden').remove();
		$('#date_start3').parent().find('.label-error-hiden').remove();
		$('#date_start4').parent().find('.label-error-hiden').remove();
		$('#date_start5').parent().find('.label-error-hiden').remove();
		$('#date_start1').removeClass('error');
		$('#date_start2').removeClass('error');
		$('#date_start3').removeClass('error');
		$('#date_start4').removeClass('error');
		$('#date_start5').removeClass('error');
		if((Date.parse($('#record_begin').html())/ 1000) > (Date.parse(u1)/1000) && u1!=''){
			$('#date_start1').addClass('error');
			$('#date_start1').parent().append(label);
			validacion = false;
		}
		if((Date.parse($('#record_begin').html())/ 1000) > (Date.parse(u2)/1000) && u2!='' ){
			$('#date_start2').addClass('error');
			$('#date_start2').parent().append(label);
			validacion = false;
		}
		if((Date.parse($('#record_begin').html())/ 1000) > (Date.parse(u3)/1000) && u3!='' ){
			$('#date_start3').addClass('error');
			$('#date_start3').parent().append(label);
			validacion = false;
		}
		if((Date.parse($('#record_begin').html())/ 1000) > (Date.parse(u4)/1000) && u4!=''){
			$('#date_start4').addClass('error');
			$('#date_start4').parent().append(label);
			validacion = false;
		}
		if((Date.parse($('#record_begin').html())/ 1000) > (Date.parse(u5)/1000) && u5!=''){
			$('#date_start5').addClass('error');
			$('#date_start5').parent().append(label);
			validacion = false;
		}
	    return validacion;
	}
}

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
$(document).ready(function() {
	$('#tabla_elementos').on("click", '.diagrama',function(){
		
		var producidas=$(this).data('producidas');
		var noProducidas=$(this).data('noproducidas');
		var noAsignadas=$(this).data('noasignadas');
		var idcahrt=$(this).data('idcahrt');
		var idproduccion=$(this).data('idproduccion');
		var idelemento=$(this).data('idelemento');
		var tipo=$(this).data('tipo');

		$('#loadElements'+idelemento).fadeIn(); 
		if(tipo==1){
		   var datos={idproduccion:idproduccion,idelemento:idelemento}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/elementos/grafica_tabla",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	chart(idelemento,data.totalcapitulos,data.escenasAsignadas,data.escenasCapitulo)
	          }	
	       	});	
		}else if(tipo==2){
         var datos={idproduccion:idproduccion,idlocacion:idelemento}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/elementos/grafica_tabla_locacion",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	chart(idelemento,data.totalcapitulos,data.escenasAsignadas,data.escenasCapitulo)
	          }	
	       	});	

		}else{
			var datos={idproduccion:idproduccion,idset:idelemento}
	        $.ajax({
	        type: "POST",
	        url: site_url+"/elementos/grafica_tabla_sets",
	        data: datos,
	        dataType: "json",
	          success:function(data){
	          	chart(idelemento,data.totalcapitulos,data.escenasAsignadas,data.escenasCapitulo)
	          }	
	       	});
		}
		console.log(noProducidas);
      drawChart(producidas,noProducidas,noAsignadas,idcahrt);
      
      
	});

});

function drawChart(producidas,noProducidas,noAsignadas,idcahrt) {
// Create the data table.
var data = new google.visualization.DataTable();
data.addColumn('string', 'Topping');
data.addColumn('number', 'Slices');
data.addRows([
  ['Escenas producidas', producidas],
  ['Escenas no producidas',noProducidas],
  ['Escenas no asignadas', noAsignadas]
]);
// Set chart options
var options = {
               'width':250,
               'height':200,
               colors:['#fee93e','#d3135c','#b3b3b3'],
               fontSize: 14,
               pieSliceText: 'none',
               is3D: true,
               chartArea:{left:40,top:0,width:"100%",height:"100%"},
               legend:{position: 'bottom', left: '-16px',alignment: 'center', textStyle: {color: 'gray', fontSize: 9}}
             };

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('chart_div'+idcahrt));
chart.draw(data, options);
}

function chart(idelemento,totalcapitulos,escenasAsignadas,escenasCapitulo){
	$('#capitulos_chart'+idelemento).highcharts({
                        chart: {
                            type: 'line',
                            zoomType: 'x',
                            events: {
				                load: function(event) {
				                    $('#loadElements'+idelemento).fadeOut();
				                }
				            } 
                        },
                        title: {
                            text: escenas_por_libretos
                        },
                        xAxis: {
                            min: 1,
                            allowDecimals:false,
                            max: totalcapitulos,
                            title: {
                                text: 'Libretos'
                            }
                        },
                        yAxis: {
                        	min: 0,
                        	tickInterval: 10,
                            title: {
                                text: escenas,
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: function() {
                            	if(this.series.name =="Escenas del Libreto"){
	                                return '<b>'+ this.series.name +'</b><br/>'+
	                                    libreto+': '+ this.x +' - '+total_escenas+': '+ this.y;
	                                }else{
	                                return '<b>'+ this.series.name +'</b><br/>'+
                                    libreto+': '+ this.x +' - '+escenas_asignada+': '+ this.y;
	                                }
                            }
                        },
                        plotOptions: {
                            line: {
                                enableMouseTracking: true
                            }
                        },
                        legend: {
                              layout: 'vertical',
                              backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                              borderColor: '#CCC',
                              borderWidth: 1,
                              shadow: false,
                              width: 180
                          },
                        series: [{
                            name: escenas_del_libreto,
                            data: escenasAsignadas,
                            color: ['#e30a58']
                            
                        }, {
                            name: escenas_asignada,
                            data: escenasCapitulo,
                            color: ['#27b1e0']
                        }]

                      });
}




