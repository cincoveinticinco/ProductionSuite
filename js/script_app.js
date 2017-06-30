setInterval(function(){hora_actual();},1000)
$(document).ready(function() {
    var estado = "big";


    if(!detectDispMobil()){
        $(document).tooltip();   
     
        $('.linkTooltip').each(function(){
          $(this).on('click', function(){return false});  
        }); 
    }


    //export pies
    $('.export_pie').click(function(e){
        e.preventDefault();
        var div = $(this).attr('data-chart');
        var link = $(this);
        var cs = new CanvasSaver(site_url+'dashboard/download_pie_graphic');
        link.css('visibility','hidden');
        $('#' + div + ' .googleChart').hide();
        $('#img_'+div).css('display','block');
        html2canvas($('#'+div), {
            onrendered: function(canvas){
            theCanvas = canvas;
            cs.savePNG(canvas, 'chart');
            $('#' + div + ' .googleChart').show();
            $('#img_'+div).css('display','none');
            link.css('visibility','visible');
            }
        });
    });

    $('.export_table').click(function(e){
        e.preventDefault();
        var div = $(this).attr('data-table');
        var cs = new CanvasSaver(site_url+'/dashboard/download_pie_graphic');
         $('#table_s').css('width','700px');
         $('#table_s').css('background','white');
         html2canvas($('#'+div), {
            onrendered: function(canvas){
            theCanvas = canvas;
            //document.body.appendChild(canvas);
            $('#table_s').css('width','1600px');
             $('#table_s').css('background','transparent');
            cs.savePNG(canvas, 'chart');

            //document.body.removeChild(canvas);
            }
        });
    })


   
    
    

    $('#save_order').click(function(){
        var cadena ="";
        $('#widgets_dashboard li.widget_dashboard').each(function() {
            cadena+=$(this).attr('data-valor')+',';
        });

        datos= {cadena: cadena}
        $.ajax({
            type: "POST",
            url: site_url+"/dashboard/guardar_widgets",
            data: datos,
            dataType: "json",
            success:function(data){
                alert(oreden_actualizado);
            }
        });
    });

    /*SORTERABLE WIDGETS DASHBOARD*/
    $('#widgets_dashboard').sortable({
        'containment': 'parent',
        'opacity': 0.6,
        'axis': "y",
        update: function(event, ui) {
            //$('#save_order').fadeIn();
             var cadena ="";
                $('#widgets_dashboard li.widget_dashboard').each(function() {
                    cadena+=$(this).attr('data-valor')+',';
                });

                datos= {cadena: cadena}
                $.ajax({
                    type: "POST",
                    url: site_url+"/dashboard/guardar_widgets",
                    data: datos,
                    dataType: "json",
                    success:function(data){
                        //alert('Orden actualizado');
                    }
                });
        }
    });
    /*FIN SORTERABLE WIDGETS DASHBOARD*/


    $('.zoom_max').click(function(){
        $(".loading").delay(-100).show();
    });

    $(".menu_list ul li.menu_li").click(function() {
        if(estado == "big"){
            $('.menu_list ul li').not(this).hide();
            $('.table_capitulos table tr td').not('.table_capitulos thead td').hide();
            $('.table_capitulos,.table_capitulos table').css({
                marginBottom: 0,
                borderBottom: 0
            });
            return estado="small";
        } else if(estado == "small") {
            $('.menu_list ul li').not(this).show();
            $('.table_capitulos table tr td').not('.table_capitulos thead td').show();
            $('.table_capitulos,.table_capitulos table').css({
                marginBottom: 4
            });
            $('.table_capitulos table').css({
                borderBottom: '1px solid black'
            });
            return estado="big";
        }
    });
    $(".table_escenas tr").click(function() {
        var este = $(this).find('.hideBox').toggle();
        $('.hideBox').not(este).hide();
        $('.table_escenas tr').not(this).removeClass('active');
        $(this).toggleClass('active');
    });
    var cant = $('.scroll_inner .caja_colores').length;
    $('.scroll_inner').css('width',90*cant);


  $.mobile.loading('hide');
  //Carga de munots proyectados vs producidos cantidad 10
  $('#minutosProyectadosvsProducidos_id').click(function(){
     var cantidad=$('.minutosProyectadosvsProducidos').val();
     var url=site_url+"/dashboard/cargar_semanas_minutos";
    var id_produccion=$('#id_produccion').val();
    cantidad=parseInt(cantidad)+10;
    var datos={id_produccion:id_produccion,cantidad:cantidad}
    $.ajax({
    type: "POST",
    url: url,
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.cont!=0){
            var total=cantidad;
            $('.minutosProyectadosvsProducidos').val(total);

            Minutos(data.semana,data.minutos_proyectados,data.minutos_produccidos,data.cont);
        } else{
            alert(no_hay_mas_semanas);
        }
      } 
   });
     

  });

  $('#escenasPautadasvsProducidos_id').click(function(){
     var cantidad=$('.escenasPautadasvsProducidos').val();
     var url=site_url+"/dashboard/cargar_escenas";
    var id_produccion=$('#id_produccion').val();
    cantidad=parseInt(cantidad)+10;
    var datos={id_produccion:id_produccion,cantidad:cantidad}
    $.ajax({
    type: "POST",
    url: url,
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.cont!=0){
            var total=cantidad;
            $('.escenasPautadasvsProducidos').val(total);

            Escenas(data.semana,data.total_escenas_programadas,data.total_escenas_producidas,data.cont);
        } else{
            alert(no_hay_mas_semanas);
        }
      } 
   });
     

  });


  $('#anterior').click(function(){
    var cantidad=$('.minutosProyectadosvsProducidos').val();
    //if(cantidad<=10){
       cantidad=parseInt(cantidad)-10;    
    /*}else{
      cantidad=parseInt(cantidad)-20;        
    }*/
   if(cantidad>=0){
          var id_produccion=$('#id_produccion').val();
            var datos={id_produccion:id_produccion,cantidad:cantidad}
            $.ajax({
            type: "POST",
            url: site_url+"/dashboard/cargar_semanas_minutos",
            data: datos,
            dataType: "json",
              success:function(data){
                if(data.cont!=0){
                   /* if(cantidad>=0){
                        cantidad=0;
                    }*/
                    $('.minutosProyectadosvsProducidos').val(cantidad);
                    Minutos(data.semana,data.minutos_proyectados,data.minutos_produccidos,data.cont);
                } else{
                    alert(no_hay_mas_semanas);
                }
              } 
        });
    }else{
         alert(no_hay_mas_semanas);
    }
          
 
     

  });

 $('#anteriorEscenasProyectadas').click(function(){
    var cantidad=$('.escenasPautadasvsProducidos').val();
    //if(cantidad<=10){
       cantidad=parseInt(cantidad)-10;    
    /*}else{
      cantidad=parseInt(cantidad)-20;        
    }*/
   if(cantidad>=0){
          var id_produccion=$('#id_produccion').val();
            var datos={id_produccion:id_produccion,cantidad:cantidad}
            $.ajax({
            type: "POST",
            url: site_url+"/dashboard/cargar_escenas",
            data: datos,
            dataType: "json",
              success:function(data){
                if(data.cont!=0){
                   /* if(cantidad>=0){
                        cantidad=0;
                    }*/
                    $('.escenasPautadasvsProducidos').val(cantidad);
                    Escenas(data.semana,data.total_escenas_programadas,data.total_escenas_producidas,data.cont);
                } else{
                    alert(no_hay_mas_semanas);
                }
              } 
        });
    }else{
         alert(no_hay_mas_semanas);
    }
          
 
     

  });


  /****carga de libretos ajax*////////
  $('#libretos').click(function(){

     var cantidad=$('.libretos').val();
     cantidad=parseInt(cantidad)+10;
    var id_produccion=$('#id_produccion').val();
    var datos={id_produccion:id_produccion,cantidad:cantidad}
    $.ajax({
    type: "POST",
    url: site_url+"/dashboard/cargar_libretos",
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.cont!=0){
            var total=cantidad;
            $('.libretos').val(total);
            libretos(data.semana,data.capitulos_programados,data.capitulos_entregados,data.cont);
        } else{
            alert(no_hay_mas_semanas);
        }
      } 
   });
     

  });

  $('#libretros_anterior').click(function(){
    var cantidad=$('.libretos').val();
       cantidad=parseInt(cantidad)-10; 
    if(cantidad>=0){
            var id_produccion=$('#id_produccion').val();
            var datos={id_produccion:id_produccion,cantidad:cantidad}
            $.ajax({
            type: "POST",
            url: site_url+"/dashboard/cargar_libretos",
            data: datos,
            dataType: "json",
              success:function(data){
                if(data.cont!=0){
                    $('.libretos').val(cantidad);
                    $('#total_libretos_entregados').html('Total Libretos Entregrados:'+data.acumulado);
                     libretos(data.semana,data.capitulos_programados,data.capitulos_entregados,data.cont);
                } else{
                    alert(no_hay_mas_semanas);
                }
              } 
        });
    }else{
         alert(no_hay_mas_semanas);
    }
  });





  /*FUNCION CARGA DE CAPITULOS POST*/
  $('#capitulos_next').click(function(){
    var cantidad=$('.estatusCapitulo_limit').val();
    cantidad=parseInt(cantidad)+10;
    var id_produccion=$('#id_produccion').val();
    var datos={id_produccion:id_produccion,cantidad:cantidad}
    $.ajax({
    type: "POST",
    url: site_url+"dashboard/cargar_capitulos",
    data: datos,
    dataType: "json",
      success:function(data){
        if(data.cont!=0){
            var total=cantidad;
            $('.estatusCapitulo_limit').val(total);
            capitulos(data.numeros,data.cadena,data.cont);
        } else{
            alert(no_hay_capitulos);
        }
      } 
   });
  });

  $('#capitulos_previus').click(function(){
    var cantidad=$('.estatusCapitulo_limit').val();
    cantidad=parseInt(cantidad)-10; 
    if(cantidad>=0){
            var id_produccion=$('#id_produccion').val();
            var datos={id_produccion:id_produccion,cantidad:cantidad}
            $.ajax({
            type: "POST",
            url: site_url+"dashboard/cargar_capitulos",
            data: datos,
            dataType: "json",
              success:function(data){
                if(data.cont!=0){
                    $('.estatusCapitulo_limit').val(cantidad);
                    capitulos(data.numeros,data.cadena,data.cont);
                } else{
                    alert(no_hay_capitulos);
                }
              } 
        });
    }else{
         alert(no_hay_mas_semanas);
    }
  });


  function capitulos(numeros,cadena,cantidad){ 
    var numeros = numeros.split(',');
    var array_1 = new Array();
    var array_2 = new Array();
    var array_3 = new Array();
    var array_4 = new Array();
    var array_5 = new Array();
    var array_6 = new Array();
    var array_7 = new Array();
    var array_8 = new Array();
    var array_9 = new Array();
    var array_10 = new Array();
    var array_11= new Array();
    var array_12 = new Array();
    var array_13 = new Array();
    var array_14 = new Array();
    var cadena_principal = cadena.split('_');
    

    array_1=cadena_principal[0].split(',');
    array_1 = array_1.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_2=cadena_principal[1].split(',');
    array_2 = array_2.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_3=cadena_principal[2].split(',');
    array_3 = array_3.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_4=cadena_principal[3].split(',');
    array_4 = array_4.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_5=cadena_principal[4].split(',');
    array_5 = array_5.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_6=cadena_principal[5].split(',');
    array_6 = array_6.map(function (x) { 
        return parseInt(x, 10); 
    });
   

    array_7=cadena_principal[6].split(',');
    array_7 = array_7.map(function (x) { 
        return parseInt(x, 10); 
    });
   

    array_8=cadena_principal[7].split(',');
    array_8 = array_8.map(function (x) { 
        return parseInt(x, 10); 
    });
   

    array_9=cadena_principal[8].split(',');
    array_9 = array_9.map(function (x) { 
        return parseInt(x, 10); 
    });
    

    array_10=cadena_principal[9].split(',');
    array_10 = array_10.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_11=cadena_principal[10].split(',');
    array_11 = array_11.map(function (x) { 
        return parseInt(x, 10); 
    });


    array_12=cadena_principal[11].split(',');
    array_12 = array_12.map(function (x) { 
        return parseInt(x, 10); 
    });
   

    array_13=cadena_principal[12].split(',');
    array_13 = array_13.map(function (x) { 
        return parseInt(x, 10); 
    });
   

    array_14=cadena_principal[13].split(',');
    array_14 = array_14.map(function (x) { 
        return parseInt(x, 10); 
    });
    
    var array_status = new Array();
    var estados_capitulos = new Array();

    for(var j = 0; j < 10; j++){
        var row = [
                array_1[j],array_2[j],array_3[j],
                array_4[j],array_5[j],array_6[j],
                array_7[j],array_8[j],array_9[j],
                array_10[j],array_11[j],array_12[j],
                array_13[j],array_14[j],
            ]
        estados_capitulos.push(row);
    }
        $(estados_capitulos).each(function(key, value){
            $(value).each(function(k, v){
                if(v != 0){
                    return true;
                }
                array_status.push(k);
                return false;    
            });
        });
        

    

    $('#estatusCapitulo').highcharts({
            chart: {
                type: 'bar',
                marginBottom: 53
            },
            title: {
                text: estatus_capitulo
            },
            xAxis: {
                title: {
                    text: capitulos_text
                },
                categories: numeros
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels: {
                    enabled: false
                },
                min: 0,
                max: 14,
            },
            tooltip: {
                formatter: function() {
                    return '<b><span style="font-size:10px">Capítulo: '+ this.x +'</span></b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                y: 20,
                padding: 3,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                itemStyle: {
                    lineHeight: '14px'
                },
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            
            series: [
            {
                groupPadding: 0,
                color: '#0071BD',
                name: sesion_protools,
                data: array_14
            },{
                groupPadding: 0,
                color: '#662E93',
                name: montando_ps,
                data: array_13
            },{
                groupPadding: 0,
                color: '#9600FF',
                name: capitulo_entregado,
                data: array_12
            },{
                groupPadding: 0,
                color: '#C800FF',
                name: qc_cliente,
                data: array_11
            },{
                groupPadding: 0,
                color: '#FF00FE',
                name: enviando_cliente,
                data: array_10
            },{
                groupPadding: 0,
                color: '#FF00C8',
                name: codificando_cliente,
                data: array_9
            },{
                groupPadding: 0,
                color: '#FE0063',
                name: montnado_lto,
                data: array_8
            },{
                groupPadding: 0,
                color: '#FF3200',
                name: qc_productor,
                data: array_7
            },{
                groupPadding: 0,
                color: '#FF6400',
                name: qc_tecnico,
                data: array_6
            }, {
                groupPadding: 0,
                color: '#FF9600',
                name: codificando_video,
                data: array_5
            }, {
                groupPadding: 0,
                color: '#FFC801',
                name: finalizando,
                data: array_4
            }, {
                groupPadding: 0,
                color: '#FFFF01',
                name: editando,
                data: array_3
            }, {
                groupPadding: 0,
                color: '#FFFC65',
                name: pre_editando,
                data: array_2
            }, {
                groupPadding: 0,
                color: '#FFFCC7',
                name: ingestando,
                data: array_1
            }]

        });
        /*FIN ESTATUS CAPITULO CAMBIO*/

        /*RESALTAR ESTATUS CAPITLO*/
 
    
        var array_series = new Array();


        $('#estatusCapitulo .highcharts-series-group .highcharts-series > rect').each(function(){$(this).attr('x', Number($(this).attr('x'))+10)});
        $('#estatusCapitulo  .highcharts-series rect').attr('width', '15');

        $('#estatusCapitulo  .highcharts-series').each(function(i, value){
            array_series.push(value.childNodes);

        });
        
        array_series.reverse();
        $(array_status).each(function(i, value){
            $(value).each(function(j ,v){
                $(array_series[v-1][i]).attr('width', '24');
                $(array_series[v-1][i]).attr('x', $(array_series[(v-1)][i]).attr('x')-3);
                $(array_series[v-1][i]).css('-webkit-filter',' brightness(0.5)');
            }); 
        });
  }

       
});

function Minutos(s,m,min,tam){ 
    var semanas_total = s.split(',');
    var proyectados_total = m.split(',');
    var producidos_total = min.split(',');
    var semanas = new Array();
    var minutos_proyectados = new Array();
    var minutos_produccidos = new Array();
    var i=0;
    tam=tam-1;
    while(i<=tam){
        semanas[i] = semanas_total[i];
        i++;
    };
    var i=0;
    while(i<=tam){
        minutos_proyectados[i] =  parseInt(proyectados_total[i]);
        i++;
    };
    var i=0;
    while(i<=tam){
        if(producidos_total[i] && producidos_total[i]!=' '){
          minutos_produccidos[i] = parseInt(producidos_total[i]);
          i++;
        }else{
          //minutos_produccidos[i] =' ';
          i++;
        }
        
    };
    //var semanas=[s];
       $('#chartMinProyVSMinProd').highcharts({
                title: {
                    text: minutos_proyectados_vs_minutos_producidos
                },
                xAxis: {
            gridLineColor: '#a4a4a4',
            gridLineWidth: 1,
                    categories:semanas
                },
                yAxis: {
                  gridLineColor: '#a4a4a4',
                    title: {
                        text:minutos
                    }
                },
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 1
                },
                series: [{
                    name: minutos_proyectados_text,
                    data: minutos_proyectados,
                    color: '#0070bb'
                }, {
                    name: minutos_producidos_text,
                    data: minutos_produccidos,
                    color: '#f05a23'
                }]
            });
 } 

 function Escenas(s,pautadas,producidas,tam){ 
    var semanas_total = s.split(',');
    var pautadas_total = pautadas.split(',');
    var producidas_total = producidas.split(',');
    var semanas = new Array();
    var escenas_pautadas = new Array();
    var escenas_produccidas = new Array();
    var i=0;
    tam=tam-1;
    while(i<=tam){
        semanas[i] = semanas_total[i];
        i++;
    };
    var i=0;
    while(i<=tam){
        escenas_pautadas[i] =  parseInt(pautadas_total[i]);
        i++;
    };
    var i=0;
    while(i<=tam){
        if(producidas_total[i] && producidas_total[i]!=' '){
          escenas_produccidas[i] = parseInt(producidas_total[i]);
          i++;
        }else{
          //minutos_produccidos[i] =' ';
          i++;
        }
        
    };
    //var semanas=[s];
       $('#chartEscenasPautadasVSEscenasProducidas').highcharts({
                title: {
                    text: escenas_pautas_vs_escenas_producidas
                },
                xAxis: {
            gridLineColor: '#a4a4a4',
            gridLineWidth: 1,
                    categories:semanas
                },
                yAxis: {
                  gridLineColor: '#a4a4a4',
                    title: {
                        text: escenas_text
                    }
                },
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 1
                },
                series: [{
                    name: escenas_pauta_text,
                    data: escenas_pautadas,
                    color: '#0070bb'
                }, {
                    name: escenas_pro_text,
                    data: escenas_produccidas,
                    color: '#f05a23'
                }]
            });
 } 


    



function libretos(s,c,cap,tam){ 
    var semanas_total = s.split(',');
    var programados_total = c.split(',');
    var entregados_total = cap.split(',');
    var semanas = new Array();
    var capitulos_programados = new Array();
    var capitulos_entregados = new Array();
    var i=0;
    tam=tam-1;
    while(i<=tam){
        semanas[i] = semanas_total[i];
        i++;
    };
    var i=0;
    while(i<=tam){
        capitulos_programados[i] =  parseInt(programados_total[i]);
        i++;
    };
    var i=0;
    while(i<=tam){
        capitulos_entregados[i] = parseInt(entregados_total[i]);
        i++;
    };


        $('#chartCapProyVSCapProd').highcharts({
            title: {
                text:libretos_proyectados_vs_libretos_entregados
            },
            xAxis: {
                gridLineColor: '#a4a4a4',
                gridLineWidth: 1,
                categories:semanas
            },
            yAxis: {
                gridLineColor: '#a4a4a4',
                title: {
                    text: libretos_text
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 1
            },
            series: [{
                name: libretos_proyectados_text,
                data: capitulos_programados,
                color: '#0070bb'
            }, {
                name: libretos_entregados_text,
                data: capitulos_entregados,
                color: '#f05a23'
            }]
        });
 }
   function hora_actual(){

    var d = new Date();
    var hora=d.getHours()
    var minutos=d.getMinutes();
    if(minutos<10){
        minutos="0"+minutos;
    }
    $('.hora').html(hora+':'+minutos);
     
   }  
$(document).ready(function() {
    $( "#alejar" ).click(function() {
        $(".loading").fadeIn(1200,function(){
          $(".caja_colores").css({"width": "+=8px"});
          $(".caja_colores table td").css({"font-size": "+=1px"});
          $("ul.submenu li").css({"display": "none"});
          $("ul.submenu li:first-child").css({"display": "block"});

          $(".scroll_inner > .caja_colores > .table_capitulos table").css({"display": "none"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child").css({"display": "block"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead").css({"display": "initial"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead tr").css({"display": "block"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead tr td").css({"display": "block", "padding": "8px"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child tbody").css({"display": "none"});
        $(".loading").fadeOut();
        });
    });

     
    $( "#acercar" ).click(function(){
        $(".loading").fadeIn(1200,function(){
          $(".caja_colores").css({"width": "-=8px","font-size": "-=8px"});
          $(".caja_colores table td").css({"font-size": "-=1px"});
          $("ul.submenu li").css({"display": "none"});
          $("ul.submenu li:first-child").css({"display": "block"});

          $(".scroll_inner > .caja_colores > .table_capitulos table").css({"display": "none"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child").css({"display": "block"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead").css({"display": "initial"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead tr").css({"display": "block"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child thead tr td").css({"display": "block","padding": "8px"});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child tbody").css({"display": "none"});
        $(".loading").fadeOut();
        });
    }); 

    $( "#resetear" ).click(function() {
        $(".loading").fadeIn(1200,function(){
          $(".caja_colores").css({"width": "90px"});
          $(".caja_colores table td").css({"font-size": "12px"});
          $("ul.submenu li").css({"display": "block"});

          $(".scroll_inner > .caja_colores > .table_capitulos table").css({"display": ""});
          $(".scroll_inner > .caja_colores > .table_capitulos table:first-child tbody").css({"display": ""});
        $(".loading").fadeOut();
        });
    });

   /* $('.up').click(function(){
       var unidad=$(this).attr('data-unidad');
       var tipo=$(this).attr('data-tipo');
       var posicion=$(this).attr('data-posicion');
       if(tipo=='1'){
         if(posicion=='1'){
            $('.producidas_libretos_'+unidad).fadeIn();
            $('.proyectados_libretos_'+unidad).fadeOut();
            $(this).attr('data-posicion','0');
         }else{
            $('.producidas_libretos_'+unidad).fadeOut();
            $('.proyectados_libretos_'+unidad).fadeIn();
            $(this).attr('data-posicion','1');
         }
          
       }else{
        if(posicion=='1'){
            $('.producidas_libretos_'+unidad).fadeOut();
            $('.proyectados_libretos_'+unidad).fadeIn();
            $(this).attr('data-posicion','0');
         }else{
            $('.producidas_libretos_'+unidad).fadeIn();
            $('.proyectados_libretos_'+unidad).fadeOut();
            $(this).attr('data-posicion','1');
         }
       }
       
    });*/


    /*$('.down').click(function(){
       var unidad=$(this).attr('data-unidad');
       var tipo=$(this).attr('data-tipo');
       if(tipo=='1'){
          $('.proyectados_libretos_'+unidad).fadeOut();
          $('.producidas_libretos_'+unidad).fadeIn();
       }else{
          $('.proyectados_minutos_'+unidad).fadeOut();
          $('.producidas_minutos_'+unidad).fadeIn();
       }
    });*/


  $('.detalleminutos').on('click', '.carrucel', function(){
   // $('.carrucel').click(function(){
      var unidad=$(this).attr('data-unidad');
       var tipo=$(this).attr('data-tipo');
       var posicion=$(this).attr('data-posicion');
         if(posicion=='1'){
            $('.producidas_libretos_'+unidad).fadeIn();
            $('.proyectados_libretos_'+unidad).fadeOut();
            $(this).attr('data-posicion','0');
         }else{
            $('.producidas_libretos_'+unidad).fadeOut();
            $('.proyectados_libretos_'+unidad).fadeIn();
            $(this).attr('data-posicion','1');
         }
    })

   //$('.carrucel2').click(function(){
      $('.detalleminutos').on('click', '.carrucel2', function(){
      var unidad=$(this).attr('data-unidad');
       var tipo=$(this).attr('data-tipo');
       var posicion=$(this).attr('data-posicion');
         if(posicion=='1'){
            $('.producidas_minutos_'+unidad).fadeIn();
            $('.proyectados_minutos_'+unidad).fadeOut();
            $(this).attr('data-posicion','0');
         }else{
            $('.proyectados_minutos_'+unidad).fadeIn();
            $('.producidas_minutos_'+unidad).fadeOut();
            $(this).attr('data-posicion','1');
         }
    })



  $('.LocvsEst').click(function(){
      var ante=$(this).attr('data-anterior');
      var limit=$('.LocvsEst_limit').val()
      
      if(ante=='1'){
        limit=parseInt(limit)+20;
      }else{
        limit=parseInt(limit)-20;
      }
      if(limit<0){
        alert(no_hay_mas_datos_mostrar);
      }else{
          var id_produccion=$('#id_produccion').val();
          var datos={id_produccion:id_produccion,limit:limit}
            $.ajax({
              type: "POST",
              url: site_url+"/dashboard/locacionVSestudio",
              data: datos,
              dataType: "json",
                success:function(data){
                   if(data.tam!=0){
                    $('.LocvsEst_limit').val(limit)
                    locacionVSestudio(data.capitulos_resumen,data.locacion_resumen,data.estudio_resumen,data.tam);
                  } else{
                      alert(no_hay_mas_datos_mostrar);
                  }
               } 
           });
      }
    })

 $('.intVSext').click(function(){
      var ante=$(this).attr('data-anterior');
      var limit=$('.intVSext_limit').val()

      if(ante=='1'){
        limit=parseInt(limit)+20;
      }else{
        limit=parseInt(limit)-20;
      }
      if(limit<0){
        alert(no_hay_mas_datos_mostrar);
      }else{
          var id_produccion=$('#id_produccion').val();
          var datos={id_produccion:id_produccion,limit:limit}
            $.ajax({
              type: "POST",
              url: site_url+"/dashboard/intVSext",
              data: datos,
              dataType: "json",
                success:function(data){
                   if(data.tam!=0){
                    $('.intVSext_limit').val(limit)
                      intVSestudio(data.capitulos,data.interior_locacion,data.interior_estudio,data.exterior_locacion,data.exterior_estudio,data.tam);
                  } else{
                      alert(no_hay_mas_datos_mostrar);
                  }
               } 
           });
      }
    })

 $('.diaVSnoc').click(function(){
      var ante=$(this).attr('data-anterior');
      var limit=$('.diaVSnoc_limit').val()

      if(ante=='1'){
        limit=parseInt(limit)+20;
      }else{
        limit=parseInt(limit)-20;
      }
      if(limit<0){
        alert(no_hay_mas_datos_mostrar);
      }else{
          var id_produccion=$('#id_produccion').val();
          var datos={id_produccion:id_produccion,limit:limit}
            $.ajax({
              type: "POST",
              url: site_url+"/dashboard/diaVSnoc",
              data: datos,
              dataType: "json",
                success:function(data){
                   if(data.tam!=0){
                    $('.diaVSnoc_limit').val(limit)
                       diaVSnoche(data.capitulos,data.dia_locacion,data.dia_estudio,data.noche_locacion,data.noche_estudio,data.tam);
                  } else{
                      alert(no_hay_mas_datos_mostrar);
                  }
               } 
           });
      }
    })

    $('.detallesCapitulo').click(function(){
          var ante=$(this).attr('data-anterior');
          var limit=$('.detallesCapitulo_limit').val()
          if(ante=='1'){
            limit=parseInt(limit)+10;
          }else{
            limit=parseInt(limit)-10;
          }
          if(limit<0){
            alert(no_hay_mas_datos_mostrar);
          }else{
              var id_produccion=$('#id_produccion').val();
              var datos={id_produccion:id_produccion,limit:limit}
                $.ajax({
                  type: "POST",
                  url: site_url+"/dashboard/detallesCapitulo",
                  data: datos,
                  dataType: "json",
                    success:function(data){
                       if(data.tam!=0){
                        $('.detallesCapitulo_limit').val(limit)
                           detalleCapitulo(data.capitulo_post,data.capitulo_tiempo_post,data.capitulo_flas,data.stab,data.otros,data.tam,data.otros_graf);
                      } else{
                          alert(no_hay_mas_datos_mostrar);
                      }
                   } 
               });
          }
        })


    $('.estatusEscena').click(function(){
          var ante=$(this).attr('data-anterior');
          var limit=$('.estatusEscena_limit').val()
          if(ante=='1'){
            limit=parseInt(limit)+10;
          }else{
            limit=parseInt(limit)-10;
          }
          if(limit<0){
            alert(no_hay_mas_datos_mostrar);
          }else{
              var id_produccion=$('#id_produccion').val();
              var datos={id_produccion:id_produccion,limit:limit}
                $.ajax({
                  type: "POST",
                  url: site_url+"/dashboard/estatusEscena",
                  data: datos,
                  dataType: "json",
                    success:function(data){
                       if(data.tam!=0){
                        $('.estatusEscena_limit').val(limit)
                           estatusEscena(data.capitulo_post,data.estados_cap,data.tam);
                      } else{
                          alert(no_hay_mas_datos_mostrar);
                      }
                   } 
               });
          }
        })

    $('.minEstVsRealVsPots').click(function(){
          var ante=$(this).attr('data-anterior');
          var limit=$('.minEstVsRealVsPots_limit').val()
          if(ante=='1'){
            limit=parseInt(limit)+10;
          }else{
            limit=parseInt(limit)-10;
          }
          if(limit<0){
            alert(no_hay_mas_datos_mostrar);
          }else{
              var id_produccion=$('#id_produccion').val();
              var datos={id_produccion:id_produccion,limit:limit}
                $.ajax({
                  type: "POST",
                  url: site_url+"/dashboard/minEstVsRealVsPots",
                  data: datos,
                  dataType: "json",
                    success:function(data){
                       if(data.tam!=0){
                        $('.minEstVsRealVsPots_limit').val(limit)
                           minEstVsRealVsPots(data.capitulo_post,data.min_estimados_cap,data.min_real_cap,data.min_post_cap,data.tam);
                      } else{
                          alert(no_hay_mas_datos_mostrar);
                      }
                   } 
               });
          }
        })

   $('.actualizar_plan').click(function(){
      var id_produccion=$('#id_produccion').val();
      var datos={id_produccion:id_produccion}
          $.ajax({
            type: "POST",
            url: site_url+"dashboard/actualizar_detalle_diario",
            data: datos,
            dataType: "json",
              success:function(data){
                var html='';
                  if(data.unidades){ 
                        var i=0; 
                        var totales=0; var totales_mins=0; var unidad_llamado='';
                              $.each(data.unidades, function(i,u){
                                    if(u.unidad_llamado){
                                      var llamado=u.unidad_llamado;
                                    }else{
                                      var llamado=' ';
                                    }
                                    html+='<div class="column six" id="detail_daily" style="height:340px;"><h2 class="header-detalle" style="background:#8cdd16">Unidad '+u.numero+': <span style="color:#FFF">Llamado '+llamado+'</span></h2>';
                                        if(parseInt(u.totales)>0){ 
                                               html+='<div class="demo">';
                                               var valor=parseInt(u.totales)/8;
                                               html+='<div class="numeros_fl">';
                                              for (var valores_libretos=u.totales/8;valores_libretos<=u.totales;) { 
                                                 html+='<input type="text" disabled="disabled" class="n_box n_box_data'+u.numero+'" value="'+Math.round(valores_libretos* 10) / 10+'">';
                                                  valores_libretos=valores_libretos+valor;
                                                  i++;

                                              }
                                              html+='</div><div style="position:relative;width:200px; height:200px; z-index:20;margin:auto"><div style="position:absolute;left:0px;top:0px"><div class="scroll_demo"><div class="up carrucel" data-tipo="1" data-posicion="1" data-unidad="'+u.numero+'"></div><div class="mover"><div class="numeros producidas_libretos_'+u.numero+'"><span>'+u.cont+'</span><br />'+escenas_pro+'</div><div class="numeros proyectados_libretos_'+u.numero+'"><span>'+u.totales+'</span><br />'+escenas_pauta+'</div></div><div class="down down_libretos carrucel" data-tipo="1" data-posicion="1" data-unidad="'+u.numero+'"></div></div><input class="knob" data-readOnly=true data-max="'+u.totales+'" data-bgColor="#CCC" data-fgColor="#fced20" data-displayInput=false data-width="200" data-height="200" data-thickness=".14" value="'+u.cont+'"></div><div style="position:absolute; top:226px; left:0px; width:100%;"><ul class="big"><li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#cccccc; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span>'+escenas_pauta+'</li><li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#fbed20; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span>'+escenas_pro+'</li></ul></div></div></div><div class="demo">';
                                                var valor=u.tiempo_porproducido.split(':'); 

                                                var valor_minutos=valor[0]/8; 
                                                        if(valor_minutos<=0){
                                                           var valor2=valor[1];
                                                            valor_minutos=valor[1]/8;     
                                                            var valores_minutos=valor[1]/8; 
                                                        }else{
                                                            var valor2=valor[0];
                                                            valor_minutos=valor[0]/8;     
                                                            var valores_minutos=valor[0]/8;
                                                        }
                                                        var cont_min=0;
                                                html+='<div class="numeros_fl">';
                                                while(valores_minutos<=valor2) {
                                                          if(cont_min==7){
                                                            html+='<input type="text" disabled="disabled" class="n_box_mins n_box_mins_data'+u.numero+'" value="'+u.tiempo_porproducido+'">';
                                                          }else{
                                                            html+='<input type="text" disabled="disabled" class="n_box_mins n_box_mins_data'+u.numero+'" value="'+Math.round(valores_minutos * 10) / 10+'">';
                                                          }
                                                          valores_minutos = valores_minutos+valor_minutos;
                                                          i++;
                                                          cont_min++;
                                                } 
                                                console.log(valores_minutos);
                                                html+='</div><div style="position:relative;width:200px; height:200px; z-index:20;margin:auto"><div style="position:absolute;left:0px;top:0px"><div class="scroll_demo" style="z-index:10"><div class="up carrucel2" data-tipo="2" data-posicion="1" data-unidad="'+u.numero+'"></div><div class="mover"><div class="numeros producidas_minutos_'+u.numero+'"><span>'+u.tiempo_producido+'</span><br />'+minutos_producidos+'</div><div class="numeros proyectados_minutos_'+u.numero+'"><span>'+u.tiempo_porproducido+'</span><br />'+minutos_estimados_text+'</div></div><div class="down carrucel2" data-tipo="2" data-posicion="1" data-unidad="'+u.numero+'"></div></div>';
                                                 html+='<input class="knob" data-readOnly=true data-max="'+valores_minutos+'" data-bgColor="#CCC" data-fgColor="#fced20" data-displayInput=false data-width="200" data-height="200" data-thickness=".14" value="'+u.totales_mins+'">';
                                                 html+='</div><div style="position:absolute; top:226px; left:0px; width:100%;"><ul class="big"><li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#cccccc; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span>'+minutos_asignados_text+'</li><li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#fbed20; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span>'+minutos_producidos_text+'</li></ul></div>';
                                                    if(parseInt(u.totales_mins) > parseInt(valores_minutos)){
                                                      html+='<div style="position:absolute;left:14px;top:14px"><input class="knob" data-readOnly=true data-max="'+u.totales_mins+'" data-bgColor="#ffffff" data-fgColor="#E8D540" data-displayInput=false data-width="172" data-height="172" data-thickness=".14" data-angleOffset=-0  value="'+(u.totales_mins-u.totales_mins)+'"></div>';

                                                    } 
                                                    html+='</div>';
                                                if (u.escenas_grabacion!="") {
                                                    html+='<div class="column six" style="margin-top: 10px;"><strong>'+grabando_escenas+': </strong>' + u.escenas_grabacion +'</div></div>';
                                                }else{
                                                    html+='</div>';
                                                };
                                      }else{ 
                                          html+='<div class="demo"><h3>'+este_trabajo_no_tiene_trabajo_para_este_dia+'</h3></div>';  
                                      }

                                              html+='</div>';
                                              i++;

                                  
                                });
                                $('.detalleminutos').html(html)
                                 $.each(data.unidades, function(i,u){
                                    var clas=".n_box_data"+u.numero;
                                    var clas2=".n_box_mins_data"+u.numero;
                                    drawCircle(clas, 60, 110, 0, 78, 88); 
                                    drawCircle(clas2, 60, 110, 0, 78, 88);
                                 })
                                    /*var clas=".n_box_data1";
                                    var clas2=".n_box_mins_data1";
                                    drawCircle(clas, 60, 110, 0, 78, 88); 
                                    drawCircle(clas2, 60, 110, 0, 78, 88);
                                    var clas=".n_box_data2";
                                    var clas2=".n_box_mins_data2";
                                    drawCircle(clas, 60, 110, 0, 78, 88); 
                                    drawCircle(clas2, 60, 110, 0, 78, 88);
                                    var clas=".n_box_data3";
                                    var clas2=".n_box_mins_data3";
                                    drawCircle(clas, 60, 110, 0, 78, 88); 
                                    drawCircle(clas2, 60, 110, 0, 78, 88);
                                    $.ajaxSetup({ cache: true });
                                      $.getScript(site_url+"/js/jquery.knob.js", function(){
                                      $.ajaxSetup({ cache: false });
                                    });*/

                                          $(function($) {
                                          $(".knob").knob({
                                          draw : function () {

                                          // "tron" case
                                          if(this.$.data('skin') == 'tron') {

                                          var a = this.angle(this.cv)  // Angle
                                          , sa = this.startAngle          // Previous start angle
                                          , sat = this.startAngle         // Start angle
                                          , ea                            // Previous end angle
                                          , eat = sat + a                 // End angle
                                          , r = 1;

                                          this.g.lineWidth = this.lineWidth;

                                          this.o.cursor
                                          && (sat = eat - 0.3)
                                          && (eat = eat + 0.3);

                                          if (this.o.displayPrevious) {
                                          ea = this.startAngle + this.angle(this.v);
                                          this.o.cursor
                                          && (sa = ea - 0.3)
                                          && (ea = ea + 0.3);
                                          this.g.beginPath();
                                          this.g.strokeStyle = this.pColor;
                                          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                                          this.g.stroke();
                                          }

                                          this.g.beginPath();
                                          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                                          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                                          this.g.stroke();

                                          this.g.lineWidth = 2;
                                          this.g.beginPath();
                                          this.g.strokeStyle = this.o.fgColor;
                                          this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                                          this.g.stroke();

                                          return false;
                                          }
                                          }
                                          });
                                          });
                 }
                 
              }
          });
    });



    $('.liquidacion').click(function(){
              
             var id_produccion=$('#id_produccion').val();
             var datos={id_produccion:id_produccion}
                $.ajax({
                  type: "POST",
                  url: site_url+"dashboard/liquidacion_personajes",
                  data: datos,
                  dataType: "json",
                    success:function(data){
                      presupuesto(1,data.liquidacion_principales,data.liquidacion_principales_f,data.presupuestado_principales,data.presupuestado_principales_f);
                      presupuesto(2,data.liquidacion_figurantes,data.liquidacion_figurantes_f,data.presupuestado_figurantes,data.presupuestado_figurantes_f);
                      presupuesto(3,data.liquidacion_reparto,data.liquidacion_reparto_f,data.presupuestado_reparto,data.presupuestado_reparto_f);
                      liquidacion_mensual(data.mensual_principales,data.mensual_figurantes,data.mensual_reparto,data.tam,data.meses,data.meses2,data.meses_liqu);
                   } 
               });

          });




}); 

/* FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/ 
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




function drawCircle(selector, center, radius, angle, x, y){
    var total = jQuery(selector).length;
    var alpha = Math.PI * 2 / total;
    jQuery(selector).each(function(index){
        var theta = alpha * index;
        var pointx  =  Math.floor(Math.cos( theta - 69.9 ) * radius);
        var pointy  = Math.floor(Math.sin( theta - 69.9 ) * radius );
    
        jQuery(this).css("margin-left", pointx + x + "px");
        jQuery(this).css("margin-top", pointy  + y  + "px");
    });
}



function locacionVSestudio(cap,loc,est,tam){
        var capitulos = cap.split(',');
        var locacion = loc.split(',');
        var estudio = est.split(',');
        var total = new Array();
        var locacion_total = new Array();
        var estudio_total = new Array();
        var i=0;
        tam=tam-1;
        while(i<=tam){
          total[i] = capitulos[i];
          i++;
        };
        var i=0;
        while(i<=tam){
          locacion_total[i] =  parseInt(locacion[i]);
          i++;
        };
        var i=0;
        while(i<=tam){
          estudio_total[i] = parseInt(estudio[i]);
          i++;
        };


        $('#chartLocVSEst').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: locacion_vs_estudio
            },
            xAxis: {
                categories: total
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Porcentaje'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
               pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
               shared: true
           },
            plotOptions: {
                column: {
                    stacking: 'percent',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
        series: [{
        name: locacion_text,
        data: locacion_total,
        color:'#8bdd16'
      },{
        name: estudio_text,
        data: estudio_total,
        color:'#e4228e'
            }]
        });
        
}

function intVSestudio(cap,int_loc,int_est,ext_loc,ext_est,tam){
      var capitulos = cap.split(',');
      var interior_locacion = int_loc.split(',');
      var interior_estudio = int_est.split(',');
      var exterior_locacion = ext_loc.split(',');
      var exterior_estudio = ext_est.split(',');
      var total_capitulos = new Array();
      var interior_locacion_total = new Array();
      var interior_estudio_total = new Array();
      var exterior_locacion_total = new Array();
      var exterior_estudio_total = new Array();
      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_capitulos[i] = capitulos[i];
      i++;
      };
      var i=0;
      while(i<=tam){
      interior_locacion_total[i] =  parseInt(interior_locacion[i]);
      i++;
      };
      var i=0;
      while(i<=tam){
        interior_estudio_total[i] = parseInt(interior_estudio[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        exterior_locacion_total[i] = parseInt(exterior_locacion[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        exterior_estudio_total[i] = parseInt(exterior_estudio[i]);
        i++;
      };


        $('#chartIntVSExt').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Interior VS. Exterior'
            },
            xAxis: {
                categories: total_capitulos
            },
            yAxis: {
                min: 0,
                title: {
                    text: interior_vs_exterior
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
               pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
               shared: true
           },
            plotOptions: {
                column: {
                    stacking: 'percent',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: interior_locacion_text,
                data: interior_locacion_total,
                color:'#cf7c29'
            },{
                name: interior_estudio_text,
                data: interior_estudio_total,
                color:'#ff9933'
            },{
                name: exterior_locacion_text,
                data: exterior_locacion_total,
                color:'#719e3a'
            },{
                name: exterior_estudio_text,
                data: exterior_estudio_total,
                color:'#8bc347'
            }]
        });

}

function diaVSnoche(cap,dia_loc,dia_est,noc_loc,noc_est,tam){
      var capitulos = cap.split(',');
      var dia_locacion = dia_loc.split(',');
      var dia_estudio = dia_est.split(',');
      var noche_locacion = noc_loc.split(',');
      var noche_estudio = noc_est.split(',');
      var total_capitulos = new Array();
      var dia_locacion_total = new Array();
      var dia_estudio_total = new Array();
      var noche_locacion_total = new Array();
      var noche_estudio_total = new Array();
      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_capitulos[i] = capitulos[i];
      i++;
      };
      var i=0;
      while(i<=tam){
      dia_locacion_total[i] =  parseInt(dia_locacion[i]);
      i++;
      };
      var i=0;
      while(i<=tam){
        dia_estudio_total[i] = parseInt(dia_estudio[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        noche_locacion_total[i] = parseInt(noche_locacion[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        noche_estudio_total[i] = parseInt(noche_estudio[i]);
        i++;
      };


        $('#chartDiaVSNoc').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: dias_vs_noche
            },
            xAxis: {
                categories: capitulos
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Porcentaje'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
               pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
               shared: true
           },
            plotOptions: {
                column: {
                    stacking: 'percent',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: dia_locacion_text,
                data: dia_locacion_total,
                color:'#ccc01a'
            },{
                name: dia_estudio_text,
                data: dia_estudio_total,
                color:'#fbed20'
            },{
                name: noche_locacion_text,
                data: noche_locacion_total,
                color:'#005b98'
            },{
                name: noche_estudio_text,
                data: noche_estudio_total,
                color:'#0070bb'
            }]
        });

}

function detalleCapitulo(cap_post,tiempo_post,flas,st,ot,tam,otros_graf){

      var capitulo_post = cap_post.split(',');
      var capitulo_tiempo_post = tiempo_post.split(',');
      var capitulo_flas = flas.split(',');
      var stab = st.split(',');
      var otros = ot.split(',');

      var total_capitulo_post = new Array();
      var total_capitulo_tiempo_post = new Array();
      var total_capitulo_flas = new Array();
      var total_stab = new Array();
      var total_otros = new Array();

      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_capitulo_post[i] = capitulo_post[i];
      i++;
      };
      var i=0;
      while(i<=tam){
        total_capitulo_tiempo_post[i] =  parseInt(capitulo_tiempo_post[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_capitulo_flas[i] = parseInt(capitulo_flas[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_stab[i] = parseInt(stab[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_otros[i] = parseInt(otros[i]);
        i++;
      };

        var valores=new Array();
        $.each(cap_post.split(/\,/), function(i, attr) {

              valores[i]=attr;
        });
        console.log(valores)

        var valores2=new Array();
        $.each(otros_graf.split(/\*/), function(i, attr) {
            valores2[valores[i]]=attr;
        });

    $('#detallesCapitulo').highcharts({
           chart: {
                type: 'column'
            },
            title: {
                text: detalles_capitulos
            },
            xAxis: {
                categories: total_capitulo_post
            },
            yAxis: {
                min: 0,
                max:Math.max.apply(null,capitulo_tiempo_post),
                title: {
                    text: detalles_capitulos
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -70,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    if(this.series.name=='Otros'){
                        var valor=1;

                        var valores_otros=valores2[this.x];
                        var v=new Array();
                        $.each(valores_otros.split(/\-/), function(i, attr) {
                            v[i]=attr;
                        });

                        return '<b><span style="font-size:10px">'+capitulos_text+': '+ this.x +'</span></b><br/><?php echo lang("global.otros") ?><br/>'+
                        '<?php echo lang("global.creditos") ?>: '+v[0]+'<br>'+
                        '<?php echo lang("global.flashback") ?>: '+v[1]+'<br>'+
                        '<?php echo lang("global.transaciones") ?>: '+v[2]+'<br>'+
                        '<?php echo lang("global.cortinillas") ?>: '+v[3]+'<br>'+
                        '<?php echo lang("global.cabezote") ?>: '+v[4]+'<br>'+
                        'Recap: '+v[5]+'<br>'+
                        'Stab: '+v[6]+'<br>'+
                        '<?php echo lang("global.despedida") ?>: '+v[7]+'<br>'+
                        '<?php echo lang("global.presentacion") ?>: '+v[8]+'<br>'+
                        'Fotoclip: '+v[9]+'<br>'+
                        '<?php echo lang("global.imagenes_archivo") ?>: '+v[10]+'<br>'+
                        'Total: '+ this.point.stackTotal;
                    }else{
                      return '<b><span style="font-size:10px">'+capitulos_text+': '+ this.x +'</span></b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;    
                    }
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: otros_text,
                data: total_otros
            }, {
                name: escenas_text,
                data: total_capitulo_tiempo_post
            }]
        });
}

function estatusEscena(cap_post,est_cap,tam){

      var capitulo_post = cap_post.split(',');
      var estados_cap = est_cap.split(',');

      var total_capitulo_post = new Array();
      var total_estados_cap = new Array();

      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_capitulo_post[i] = capitulo_post[i];
      i++;
      };
      var i=0;
      while(i<=tam){
        total_estados_cap[i] =  parseInt(estados_cap[i]);
        i++;
      };

    $('#estatusEscena').highcharts({
           chart: {
                type: 'column'
            },
            title: {
                text: estatus_capitulo
            },
            xAxis: {/*FUNCION PARA CAMBIAR EL COLOR A CUADRO CAPITULOS ESCRITOS P. PRODUCCION*/
                categories: total_capitulo_post
            },
            yAxis: {
                min: 0,
                title: {
                    text: estatus_capitulo
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -70,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: 'Porcentaje',
                data: total_estados_cap
            }]
        });
}

function minEstVsRealVsPots(cap_post,min_est_cap,min_re_cap,min_po_cap,tam){

      var capitulo_post = cap_post.split(',');
      var min_estimados_cap = min_est_cap.split(',');
      var min_real_cap = min_re_cap.split(',');
      var min_post_cap = min_po_cap.split(',');

      var total_capitulo_post = new Array();
      var total_min_estimados_cap = new Array();
      var total_min_real_cap = new Array();
      var total_min_post_cap = new Array();

      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_capitulo_post[i] = capitulo_post[i];
      i++;
      };
      var i=0;
      while(i<=tam){
        total_min_estimados_cap[i] =  parseInt(min_estimados_cap[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_min_real_cap[i] =  parseInt(min_real_cap[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_min_post_cap[i] =  parseInt(min_post_cap[i]);
        i++;
      };
      console.log(total_min_estimados_cap)

    $('#minEstVsRealVsPots').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: minutos_est_vs_reales
            },
            xAxis: {
                categories: total_capitulo_post
            },
            yAxis: {
                min: 0,
                max:Math.max.apply(null,min_real_cap),
                title: {
                    text: minutos
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">'+capitulos_text+' {point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name:estimados_text,
                data: total_min_estimados_cap
    
            }, {
                name: reales_text,
                data: total_min_real_cap
    
            }, {
                name: post_produccion_text,
                data: total_min_post_cap
    
            }]
        });
}

function detectDispMobil(){
    return navigator.userAgent.toLowerCase().search(/iphone|ipod|ipad|android/) > -1;
}


//Guardar Canvas Forzar Descarga
function CanvasSaver(url) {
  this.url = url;
  this.savePNG = function(cnvs, fname) {
  if(!cnvs || !url) return;
    fname = fname || 'picture';

    var data = cnvs.toDataURL("image/jpeg");
    data = data.substr(data.indexOf(',') + 1).toString();
    var dataInput = document.createElement("input") ;
    dataInput.setAttribute("name", 'imgdata') ;
    dataInput.setAttribute("value", data);

    var nameInput = document.createElement("input") ;
    nameInput.setAttribute("name", 'name') ;
    nameInput.setAttribute("value", fname + '.jpeg');

    var myForm = document.createElement("form");
    myForm.method = 'post';
    myForm.action = url;
    myForm.appendChild(dataInput);
    myForm.appendChild(nameInput);

    document.body.appendChild(myForm) ;
    myForm.submit() ;
    document.body.removeChild(myForm) ;
  };
}

function presupuesto(tipo,liquidado,liquidado_f,presupuestado,presupuestado_f){
    if(tipo=="1"){
       var title="Presupuesto vs Liquidado Protagonistas";
       var id="presupuestoPrincipales";
    }
    if(tipo=="2"){
        var title="Presupuesto vs Liquidado Figurantes";
        var id="presupuestoFigurantes";
    }
    if(tipo=="3"){
        var title="Presupuesto vs Liquidado Reparto";
        var id="presupuestoReparto";
    }
    presupuestado=parseInt(presupuestado);
    liquidado=parseInt(liquidado);
 
    var li=((liquidado*100)/presupuestado);
    var pr=100-li;
    
    $('#'+id).dountGraphic({
        full: presupuestado,
        total:liquidado,
        color:'#0D233A',
        title: title,
        labels:  ['Liquidado: $'+liquidado_f, 'Presupuestado: $'+presupuestado_f,]
    })
    // $('#'+id).highcharts({
    //             chart: {
    //                 plotBackgroundColor: null,
    //                 plotBorderWidth: null,//1,
    //                 plotShadow: false
    //             },
    //             title: {
    //                 text: title
    //             },
    //             tooltip: {
    //                 pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    //             },
    //             plotOptions: {
    //                 pie: {
    //                     allowPointSelect: true,
    //                     cursor: 'pointer',
    //                     dataLabels: {
    //                         enabled: false,
    //                         //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
    //                         //style: {
    //                         //    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
    //                         //}
    //                     },
    //                     showInLegend:true
    //                 }
    //             },
    //             series: [{
    //                 type: 'pie',
    //                 name: 'Porcentaje',
    //                 data: [
    //                     ['Presupuestado: $'+presupuestado_f,   pr],
    //                     ['Liquidado: $'+liquidado_f,li]
    //                 ]
    //             }]
    //         });
        /*fin presupuesto*/
}


function liquidacion_mensual(mensual_principales,mensual_figurantes,mensual_reparto,tam,menses,meses2,meses_liq){
      
      var meses = menses.split(',');
      meses2 = meses2.split(',');

      meses_liq = meses_liq.split(',');
      
      var mensual_principales = mensual_principales.split(',');
      var mensual_figurantes = mensual_figurantes.split(',');
      var mensual_reparto = mensual_reparto.split(',');

      var total_meses = new Array();
      var total_mensual_principales = new Array();
      var total_mensual_figurantes = new Array();
      var total_mensual_reparto = new Array();

      var i=0;
      tam=tam-1;
      while(i<=tam){
      total_meses[i] = meses[i];
      i++;
      };
      var i=0;
      var j=0;
      while(i<=tam){
        if(meses2.length>0){
            if(meses_liq[i]==meses2[j]){
                total_mensual_principales[i] =  parseInt(mensual_principales[j]);
                 i++;  
                 j++;
            }else{
                total_mensual_principales[i] =  parseInt(0);
                i++;  
            }
        }else{
            total_mensual_principales[i] =  parseInt(0);
            i++;  
        }
            
        
      };
      var i=0;
      while(i<=tam){
        total_mensual_figurantes[i] =  parseInt(mensual_figurantes[i]);
        i++;
      };
      var i=0;
      while(i<=tam){
        total_mensual_reparto[i] =  parseInt(mensual_reparto[i]);
        i++;
      };

        
        
        var maxp=Math.max.apply(null,mensual_principales);
        var maxf=Math.max.apply(null,total_mensual_figurantes);
        var maxr=Math.max.apply(null,total_mensual_reparto);

   

        if ((maxp>maxf) && (maxp>maxr)) 
        { 
         var max=maxp;
        } 
        if ((maxf>maxp) && (maxf>maxr)) 
        { 
         var max=maxf; 
        } 
        if ((maxr>maxp) && (maxr>maxf)) 
        { 
           var max=maxr; 
        }  

    $('#liquidacion_mensual').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: liquidacion_mensual
            },
            xAxis: {
                  categories: total_meses
            },
            yAxis: {
                min: 0,
                //max:Math.max.apply(null,mensual_principales),
                max:max,
                title: {
                    text: millones
                }
            },
            tooltip: {
                formatter: function(){
                    return '<b>Fecha: '+ this.point.category +'</b><br/>'+
                        '<b>'+this.series.name+' </b>: $' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: protagonista_text,
                data: total_mensual_principales
    
            }, {
                name: reparto_text,
                data: total_mensual_reparto,
    
            }, {
                name: figurante_text,
                data: total_mensual_figurantes,

    
            }]
        });
}




