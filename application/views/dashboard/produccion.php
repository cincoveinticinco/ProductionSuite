  <?php $idioma = $this->lang->lang().'/'; ?>
  <script type="text/javascript">
    $(function () {
        // CHART DETALLE CAPÃTULOS
        $('#chartDetalleCapitulos').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: "<?php echo lang('global.detalle_libretos') ?>"
            },
            xAxis: {
                categories: ['Libretos']
            },
            yAxis: {
                min: 0,
                max: <?php echo $produccion['0']->total_libretos ?>,
                title: {
                    text: '' //Total de libretos
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
                series: [{
                name: "<?php echo  lang('global.proyectados') ?>",
                data: [<?php echo $produccion['0']->total_proyectados?>],
                color: '#cccccc'
            }, {
                name: "<?php echo  lang('global.entregados') ?>",
                data: [<?php echo $produccion['0']->total_entregados ?>],
                color: '#8815a6'
            }, {
                name: "<?php echo  lang('global.desglosado') ?>",
                data: [<?php echo $produccion['0']->total_desglosados ?>],
                color: '#91c24c'
            }, {
                name: "<?php echo  lang('global.producidos') ?>",
                data: [<?php echo $produccion['0']->total_producidos ?>],
                color: '#fee93e'
            }]
        });
        
        // CHART DETALLE ESCENA
        var escenas=<?php echo $produccion['0']->total_libretos*$produccion['0']->escenas_libretos ?>;
        if(escenas<=0){
            escenas=<?php echo $produccion['0']->total_libretos*50 ?>;;
        }
        var esc_producidas=<?php echo $produccion['0']->total_escenas_producidas ?>;
        if(escenas<esc_producidas){
            escenas=<?php echo $produccion['0']->total_escenas_producidas ?>+<?php echo $produccion['0']->total_escenas_escritas-$produccion['0']->total_escenas_producidas ?>;
        }

        var maximo=escenas;
        $('#chartDetalleEscena').highcharts({
            chart: {
                type: 'bar',
                animation: false, 
                renderTo: 'body'
            },
            loading: {
                labelStyle: {
                    top: '45%'
                }
            },
            title: {
                text: "<?php echo lang('global.detalle_escenas') ?>"
            },
            xAxis: {
                categories: ['Escenas']
            },
            yAxis: {
                min: 0,
                tickInterval: 200,
                max: maximo,
                title: {
                    text: '' ///Total de libretos
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
                series: [{
                name: "<?php echo  lang('global.proyectados') ?>",
                data: [maximo],
                color: '#cccccc'
            }, {
                name: "<?php echo  lang('global.desglosado') ?>",
                data: [<?php echo $produccion['0']->total_escenas_escritas-$produccion['0']->total_escenas_producidas ?>],
                color: '#91c24c'
            },{
               name: "<?php echo  lang('global.producidos') ?>",
                data: [<?php echo $produccion['0']->total_escenas_producidas-$produccion['0']->total_escenas_post_producidas ?>],
                color: '#fee93e' 
            },{
                name: "<?php echo  lang('global.post_producidas') ?>",
                data: [<?php echo $produccion['0']->total_escenas_post_producidas ?>],
                color: '#0070bc'
            }]
        });



        // CHART DETALLE MINUTOS
         var minutos_max=<?php echo ($datos_produccion['0']->minuto_capitulo*$datos_produccion['0']->numero_capitulo); ?>;
         var segundos_max=<?php echo round(($datos_produccion['0']->segundos_capitulo*$datos_produccion['0']->numero_capitulo)/60) ?>;
         minutos_max=minutos_max+segundos_max;
         var total_minutos_producidos=<?php echo $produccion['0']->total_minutos_producidos_escenas+(round($produccion['0']->total_segundos_producidos_escenas/60)) ?>;
         var total_minutos_postProducidos=<?php echo $produccion['0']->total_minutos_post+(round($produccion['0']->total_segundos_post/60)); ?>;
        $('#chartDetalleMinutos').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: "<?php echo lang('global.detalle_minutos') ?>"
            },  
            xAxis: {
                categories: ['Minutos']
            },
            yAxis: {
                min: 0,
                tickInterval: 500,
                max: minutos_max,
                title: {
                    text: '' ////Total de libretos
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
                series: [{
                name: "<?php echo  lang('global.proyectadas') ?>",
                data: [minutos_max],
                color: '#cccccc'
            }, {
                name: "<?php echo  lang('global.desglosadas') ?>",
                data: [<?php echo $produccion['0']->total_duracion_estimada_minutos+(round($produccion['0']->total_duracion_estimada_segudos/60)); ?>],
                color: '#91c24c'
            },{
                name: "<?php echo  lang('global.producidas') ?>",
                data: [total_minutos_producidos-total_minutos_postProducidos],
                color: '#fee93e'
            },{
                name: "<?php echo  lang('global.producidas') ?>",
                data: [total_minutos_postProducidos],
                color: '#0070bc'
            }]
        });

        // CHART MIN. PROY. VS MIN. PROD. SEMANALES
        
        var semanas=[<?php echo $semanas ?>];
        var minutos_proyectados=[<?php echo $minutos_proyectados ?>];
        var minutos_produccidos=[<?php echo $minutos_produccidos ?>];
        $('#chartMinProyVSMinProd').highcharts({
            title: {
                text: "<?php echo lang('global.minutos_proyectados_vs_minutos_producidos') ?>"
            },
            xAxis: {
                gridLineColor: '#a4a4a4',
                gridLineWidth: 1,
                categories:semanas
            },
            yAxis: {
                gridLineColor: '#a4a4a4',
                title: {
                    text: "<?php echo lang('global.minutos') ?>"
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            series: [{
                name: "<?php echo  lang('global.minutos_proyectados') ?>",
                data: minutos_proyectados,
                color: '#0070bb'
            }, {
                name: "<?php echo  lang('global.minutos_producidos') ?>",
                data: minutos_produccidos,
                color: '#f05a23'
            }]
        });

        /*** Escenas pautas vs Escenas producidas***/

        var semanas=[<?php echo $semanas ?>];
        var escenas_proyectados=[<?php echo $total_escenas_programadas ?>];
        var escenas_produccidos=[<?php echo $total_escenas_producidas ?>];
        
        $('#chartEscenasPautadasVSEscenasProducidas').highcharts({
            title: {
                text: "<?php echo lang('global.escenas_pautas_vs_escenas_producidas') ?>"
            },
            xAxis: {
                gridLineColor: '#a4a4a4',
                gridLineWidth: 1,
                categories:semanas
            },
            yAxis: {
                gridLineColor: '#a4a4a4',
                title: {
                    text: "<?php echo lang('global.escenas') ?>"
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            series: [{
                name: "<?php echo  lang('global.escenas_pauta') ?>",
                data: escenas_proyectados,
                color: '#0070bb'
            }, {
                name: "<?php echo  lang('global.escenas_pro') ?>",
                data: escenas_produccidos,
                color: '#f05a23'
            }]
        });

        /**** Fin Escenas pautas vs Escenas producidas***/

        // CHART CAP. PROY. VS CAP. PROD. SEMANALES
        
        var semanas=[<?php echo $semanas ?>];
        var capitulos_programados=[<?php echo $capitulos_programados ?>];
        var capitulos_entregados=[<?php echo $capitulos_entregados ?>];
        $('#chartCapProyVSCapProd').highcharts({
            title: {
                text: "<?php echo lang('global.libretos_proyectados_vs_libretos_entregados') ?>"
            },
            xAxis: {
                gridLineColor: '#a4a4a4',
                gridLineWidth: 1,
                categories:semanas
            },
            yAxis: {
                gridLineColor: '#a4a4a4',
                title: {
                    text: "<?php echo lang('global.libretos') ?>"
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            series: [{
                name: "<?php echo  lang('global.libretos_proyectados') ?>",
                data: capitulos_programados,
                color: '#0070bb'
            }, {
                name: "<?php echo  lang('global.libretos_entregados') ?>",
                data: capitulos_entregados,
                color: '#f05a23'
            }]
        });

        // CHART LOCACION VS ESTUDIO
        var capitulos=[<?php echo $capitulos_resumen ?>];
        var locacion=[<?php echo $locacion_resumen ?>];
        var estudio=[<?php echo $estudio_resumen ?>];
        $('#chartLocVSEst').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo lang('global.locacion_vs_estudio') ?>"
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
                name: "<?php echo  lang('global.locacion') ?>",
                data: locacion,
                color:'#8bdd16'
            },{
                name: "<?php echo  lang('global.estudio') ?>",
                data: estudio,
                color:'#e4228e'
            }]
        });
        
        // CHART Interior VS Exterior
        var interior_locacion=[<?php echo $interior_locacion ?>];
        var interior_estudio=[<?php echo $interior_estudio ?>];
        var exterior_locacion=[<?php echo $exterior_locacion ?>];
        var exterior_estudio=[<?php echo $exterior_estudio ?>];
        $('#chartIntVSExt').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo lang('global.interior_vs_exterior') ?>"
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
                name: "<?php echo  lang('global.interior_locacion') ?>",
                data: interior_locacion,
                color:'#cf7c29'
            },{
                name: "<?php echo  lang('global.interior_estudio') ?>",
                data: interior_estudio,
                color:'#ff9933'
            },{
                name: "<?php echo  lang('global.exterior_locacion') ?>",
                data: exterior_locacion,
                color:'#719e3a'
            },{
                name: "<?php echo  lang('global.exterior_estudio') ?>",
                data: exterior_estudio,
                color:'#8bc347'
            }]
        });

        // CHART Dia VS noche
        var dia_locacion=[<?php echo $dia_locacion ?>];
        var dia_estudio=[<?php echo $dia_estudio ?>];
        var noche_locacion=[<?php echo $noche_locacion ?>];
        var noche_estudio=[<?php echo $noche_estudio ?>];
        $('#chartDiaVSNoc').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo lang('global.dias_vs_noche') ?>"
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
                name: "<?php echo  lang('global.dia_locacion') ?>",
                data: dia_locacion,
                color:'#ccc01a'
            },{
                name: "<?php echo  lang('global.dia_estudio') ?>",
                data: dia_estudio,
                color:'#fbed20'
            },{
                name: "<?php echo  lang('global.noche_locacion') ?>",
                data: noche_locacion,
                color:'#005b98'
            },{
                name: "<?php echo  lang('global.noche_estudio') ?>",
                data: noche_estudio,
                color:'#0070bb'
            }]
        });
        // detalle capitulo
        var capitulo_post=[<?php echo $capitulo_post ?>];

        var capitulo_tiempo_post=[<?php echo $capitulo_tiempo_post ?>];
        var capitulo_flas=[<?php echo $capitulo_flas ?>];
        var stab=[<?php echo $stab ?>];
        var otros=[<?php echo $otros ?>];
        
        var valores=new Array();
        var variable='<?php echo $capitulo_post ?>';
        $.each(variable.split(/\,/), function(i, attr) {

              valores[i]=attr;
        });

        var valores2=new Array();
        var variables2='<?php echo $otros_graf ?>';
        $.each(variables2.split(/\*/), function(i, attr) {
            valores2[valores[i]]=attr;
        });

        $('#detallesCapitulo').highcharts({
           chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo lang('global.detalles_capitulos') ?>"
            },
            xAxis: {
                categories: capitulo_post
            },
            yAxis: {
                min: 0,
                max:Math.max.apply(null,capitulo_tiempo_post),
                title: {
                    text: "<?php echo lang('global.detalles_capitulos') ?>"
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            /*legend: {
                align: 'center',
                verticalAlign: 'bottom',
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },*/
             tooltip: {
                headerFormat: '<span style="font-size:10px">'+capitulos_text+' {point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            /*tooltip: {
                formatter: function() {
       

                    if(this.series.name=='Otros'){
                        var valor=1;

                        var valores_otros=valores2[this.x];
                        var v=new Array();
                        $.each(valores_otros.split(/\-/), function(i, attr) {
                            v[i]=attr;
                        });

                        return '<b><span style="font-size:10px"><?php echo lang("global.capitulos") ?>: '+ this.x +'</span></b><br/>Otros<br/>'+
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
                      return '<b><span style="font-size:10px"><?php echo lang("global.capitulos") ?>: '+ this.x +'</span></b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;    
                    }
                    
                }
            },*/
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
                name: "<?php echo  lang('global.otros') ?>",
                data: otros
            }, {
                name: "<?php echo  lang('global.escenas') ?>",
                data: capitulo_tiempo_post
            }]
        });


        /* minutos estimados vs reales vs post por capitulos*/
        var capitulo_post=[<?php echo $capitulo_post ?>];
        var min_estimados_cap=[<?php echo $min_estimados_cap ?>];
        var min_real_cap=[<?php echo $min_real_cap ?>];
        var min_post_cap=[<?php echo $min_post_cap ?>];
         $('#minEstVsRealVsPots').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo lang('global.minutos_est_vs_reales') ?>"
            },
            xAxis: {
                categories: capitulo_post
            },
            yAxis: {
                min: 0,
                max:Math.max.apply(null,min_real_cap),
                title: {
                    text: "<?php echo lang('global.minutos') ?>"
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px"><?php echo lang("global.capitulos") ?> {point.key}</span><table>',
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
                name: "<?php echo  lang('global.estimado') ?>",
                data: min_estimados_cap
    
            }, {
                name: "<?php echo  lang('global.reales') ?>",
                data: min_real_cap
    
            }, {
                name: "<?php echo  lang('global.post_produccion') ?>",
                data: min_post_cap
    
            }]
        });
        


        /*ESTATUS CAPITULO CAMBIO*/
        // estatus capitulo
        var capitulo_post=[<?php echo $capitulo_post ?>];
        var estados_cap=[<?php echo $estados_cap ?>];
        var numeros = new Array();
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
        
        var array_status = new Array();
        <?php 
            $series = "";
            $status = "";
            
            for($i = 0; $i<count($capitulos_estatus); $i++){
                echo 'numeros['. $i .'] = '. $capitulos_estatus[$i]['numero'] .';';
                echo 'array_1['. $i .'] = '. $capitulos_estatus[$i]['estado_1'] .';';
                echo 'array_2['. $i .'] = '. $capitulos_estatus[$i]['estado_2'] .';';
                echo 'array_3['. $i .'] = '. $capitulos_estatus[$i]['estado_3'] .';';
                echo 'array_4['. $i .'] = '. $capitulos_estatus[$i]['estado_4'] .';';
                echo 'array_5['. $i .'] = '. $capitulos_estatus[$i]['estado_5'] .';';
                echo 'array_6['. $i .'] = '. $capitulos_estatus[$i]['estado_6'] .';';
                echo 'array_7['. $i .'] = '. $capitulos_estatus[$i]['estado_7'] .';';
                echo 'array_8['. $i .'] = '. $capitulos_estatus[$i]['estado_8'] .';';
                echo 'array_9['. $i .'] = '. $capitulos_estatus[$i]['estado_9'] .';';
                echo 'array_10['. $i .'] = '. $capitulos_estatus[$i]['estado_10'] .';';
                echo 'array_11['. $i .'] = '. $capitulos_estatus[$i]['estado_11'] .';';
                echo 'array_12['. $i .'] = '. $capitulos_estatus[$i]['estado_12'] .';';
                echo 'array_13['. $i .'] = '. $capitulos_estatus[$i]['estado_13'] .';';
                echo 'array_14['. $i .'] = '. $capitulos_estatus[$i]['estado_14'] .';';

                //print_r($capitulos_estatus);
                foreach ($capitulos_estatus[$i] as $key => $value) {
                    if($value != 0){
                        continue;
                    }
                    $status .= (substr($key, 7) - 1).',';
                    break;
                }
            }
        ?>
        
        $('#estatusCapitulo').highcharts({
            chart: {
                type: 'bar',
                marginBottom: 53,

            },
            title: {
                text: "<?php echo lang('global.capitulos') ?>"
            },
            xAxis: {
                title: {
                    text: "<?php echo lang('global.capitulos') ?>"
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
                    return '<b><span style="font-size:10px"><?php echo lang("global.capitulos") ?>: '+ this.x +'</span></b><br/>'+
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
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal',

                }
            },
            
            series: [
            {
                groupPadding: 0,
                color: '#0071BD',
                name: "<?php echo lang('global.capitulo_entregado') ?>",
                data: array_14
            },{
                groupPadding: 0,
                color: '#662E93',
                name: "<?php echo lang('global.montando_ps') ?>",
                data: array_13
            },{
                groupPadding: 0,
                color: '#9600FF',
                name: "<?php echo lang('global.sesion_protools') ?>",
                data: array_12
            },{
                groupPadding: 0,
                color: '#C800FF',
                name: "<?php echo lang('global.qc_cliente') ?>",
                data: array_11
            },{
                groupPadding: 0,
                color: '#FF00FE',
                name: "<?php echo lang('global.enviando_cliente') ?>",
                data: array_10
            },{
                groupPadding: 0,
                color: '#FF00C8',
                name: "<?php echo lang('global.codificando_cliente') ?>",
                data: array_9
            },{
                groupPadding: 0,
                color: '#FE0063',
                name: "<?php echo lang('global.montnado_lto') ?>",
                data: array_8
            },{
                groupPadding: 0,
                color: '#FF3200',
                name: "<?php echo lang('global.qc_productor') ?>",
                data: array_7
            },{
                groupPadding: 0,
                color: '#FF6400',
                name: "<?php echo lang('global.qc_tecnico') ?>",
                data: array_6
            }, {
                groupPadding: 0,
                color: '#FF9600',
                name: "<?php echo lang('global.codificando_video') ?>",
                data: array_5
            }, {
                groupPadding: 0,
                color: '#FFC801',
                name: "<?php echo lang('global.finalizando') ?>",
                data: array_4
            }, {
                groupPadding: 0,
                color: '#FFFF01',
                name: "<?php echo lang('global.editando') ?>",
                data: array_3
            }, {
                groupPadding: 0,
                color: '#FFFC65',
               name: "<?php echo lang('global.pre_editando') ?>",
                data: array_2
            }, {
                groupPadding: 0,
                color: '#FFFCC7',
                name: "<?php echo lang('global.ingestando') ?>",
                data: array_1
            }]

        });
        /*FIN ESTATUS CAPITULO CAMBIO*/

        /*presupuesot*/
         


    });

$(document).ready(function(){
    

    var array_status = [<?php echo substr($status, 0,-1) ?>];
    var array_series = new Array();
    

    $('#estatusCapitulo .highcharts-series-group .highcharts-series > rect').each(function(){$(this).attr('x', Number($(this).attr('x'))+10)});
    $('#estatusCapitulo  .highcharts-series rect').attr('width', '15');
    
    $('#estatusCapitulo  .highcharts-series').each(function(i, value){
        array_series.push(value.childNodes);

    });
 
    $(array_status).each(function(i, value){
        $(value).each(function(j ,v){
            
           $(array_series[(array_series.length - v)][i]).attr('width', '24');
           $(array_series[(array_series.length - v)][i]).attr('x', $(array_series[(array_series.length - v)][i]).attr('x')-3);
           $(array_series[(array_series.length - v)][i]).css('-webkit-filter',' brightness(0.5)');

        }); 
    
    });
    
}); 


</script>

<!-- Cicular Information - Carlos Carvajal -->

<script>
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
</script>

<!-- Cicular Information - Carlos Carvajal -->

<!-- style=".chart-inner {position: absolute; width: 100%; height: 100%;}" -->
<input type="hidden" id="id_produccion" value="<?php echo $id_produccion?>">
<div data-role="content" id="contenido_dash_prod"> 
    <div data-role="collapsible-set" >
        <ul id="widgets_dashboard">
            <?php $xl=0; while ( $xl < count($widgets_usuario)-1) {?>
                
                <?php if($widgets_usuario[$xl]=="indicadores proyectados"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="indicadores proyectados">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.analisis_desensibilidad') ?> <span class="iconDrag"></span></h3>
                            <div>
                             
                             <div><a target=""  href="#" class="export_table" data-table="table_s"><?php echo lang('global.guardar_jpg') ?></a></div>
                             <div class="row row_export" style="overflow-x:scroll" >
                             <div class="tablescroll" style="width:1600px" id="table_s">
                             <div class="tablecontent"> 
                             <span class="span-inline"><label><?php echo lang('global.total_libretos') ?>:</label> <?php echo $produccion['0']->total_libretos; ?></span> 
                                <table class="det_proyectados">
                                    <thead>
                                        <tr>
                                            <td><?php echo lang('global.descripcion') ?></td>
                                            <td><?php echo lang('global.presupuestado') ?></td>
                                            <td><?php echo lang('global.desglosado') ?> (<?php echo $produccion['0']->total_desglosados+$produccion['0']->total_producidos ?> Libreto)</td>
                                            <td><?php echo lang('global.saldo') ?></td>
                                            <td><?php echo lang('global.porcentaje_x_utilizar') ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo lang('global.numero_escenas') ?></td>
                                            <td><?php echo $total_escenas=$produccion['0']->numero_capitulo*$produccion['0']->escenas_libretos ?></td>
                                            <?php if($produccion['0']->total_escenas_escritas){ $desglosadas=($produccion['0']->total_escenas_escritas);}else { $desglosadas=0;}?>
                                            <td><?php echo $desglosadas ?></td>
                                            <?php $total=$total_escenas-$desglosadas; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total; ?></span></td>
                                            <?php if($total!=0 and $total_escenas!=0){ ?>
                                            <td><?php echo round(($total*100)/$total_escenas) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.protagonistas') ?></td>
                                            <td><?php echo $protagonistas_total=$produccion['0']->protagonistas_produccion ?></td>
                                            <td><?php echo $protagonistas ?></td>
                                            <?php $total=$protagonistas_total-$protagonistas; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if ($protagonistas_total): ?>
                                                <td><?php echo round(($total*100)/$protagonistas_total) ?> %</td>
                                            <?php else: ?>
                                                <td></td>
                                            <?php endif ?>
                                            
                                        </tr>
                                        <tr>

                                            <td><?php echo lang('global.reparto') ?></td>
                                            <td><?php echo $reparto_total=$produccion['0']->numero_repartos*$produccion['0']->total_libretos ?></td>
                                            <td><?php echo $reparto ?></td>
                                            <?php $total=$reparto_total-$reparto; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($reparto_total){ ?> 
                                            <td><?php echo round(($total*100)/$reparto_total) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                            
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.figurante') ?></td>
                                            <td><?php echo $figurante_total=$produccion['0']->numero_figurantes*$produccion['0']->total_libretos ?></td>
                                            <td><?php echo $figurante ?></td>
                                            <?php $total=$figurante_total-$figurante; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($figurante_total){ ?>
                                            <td><?php echo round(($total*100)/$figurante_total) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                            
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.extra') ?></td>
                                            <td><?php echo $extras_total=$produccion['0']->numero_extras*$produccion['0']->total_libretos ?></td>
                                            <td><?php echo $extra ?></td>
                                            <?php $total=$extras_total-$extra; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($extras_total){ ?>
                                            <td><?php echo round(($total*100)/$extras_total) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                            
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.locaciones') ?></td>
                                            <td><?php echo $locacion_prod=$produccion['0']->locacion_proyectadas*$produccion['0']->total_libretos ?></td>
                                            <td><?php echo $total_locaciones ?></td>
                                            <?php $total=$locacion_prod-$total_locaciones; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($locacion_prod){ ?>
                                            <td><?php echo round(($total*100)/$locacion_prod) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                            
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.vehiculos') ?></td>
                                            <td><?php echo $vehiculos_prod=$produccion['0']->numero_vehiculos*$produccion['0']->total_libretos ?></td>
                                            <td><?php echo $vehiculos_desglosados ?></td>
                                            <?php $total=$vehiculos_prod-$vehiculos_desglosados; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($vehiculos_prod){ ?>
                                            <td><?php echo round(($total*100)/$vehiculos_prod) ?> %</td>
                                            <?php }else{ ?>
                                            <td></td>
                                            <?php } ?>
                                            
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.eventos_pequenos') ?></td>
                                            <?php if($produccion['0']->evento_pequeno AND $produccion['0']->evento_pequeno!=0){ ?>
                                            <?php $peque_prod=$produccion['0']->evento_pequeno;
                                           
                                                 $even_pe=$produccion['0']->evento_pequeno;
                                             ?>
                                             <?php }else{
                                                   $even_pe=0;
                                                } ?>
                                            <td><?php echo $even_pe; ?></td>
                                            <td><?php echo $pequenos ?></td>
                                            <?php $total=$even_pe-$pequenos; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($even_pe OR $even_pe!=0 OR $even_pe!=null){
                                                $p=round(($total*100)/$even_pe);
                                            }else{
                                                 $p='';
                                                } ?>
                                            <td><?php echo $p ?> %</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.eventos_medianos') ?></td>
                                            <?php if($produccion['0']->evento_mediano AND $produccion['0']->evento_mediano!=0){ ?>
                                                    <?php $mediado_prod=$produccion['0']->evento_mediano;
                                                    
                                                     $even=$produccion['0']->evento_mediano;
                                                        ?>
                                             <?php }else{
                                                $even=0;
                                              
                                             } ?>
                                            <td><?php echo $even ?></td>
                                            <td><?php echo $medianos ?></td>
                                            <?php $total=$even-$medianos; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                            <?php if($even or $even!=0 or $even!=null){
                                                $p=round(($total*100)/$even);
                                            }else{
                                                 $p='';
                                                } ?>
                                            <td><?php echo $p ?> %</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo lang('global.eventos_grandes') ?></td>
                                            <?php if($produccion['0']->evento_grande AND $produccion['0']->evento_grande!=0){ ?>
                                                    <?php $grandes_even=$produccion['0']->evento_grande;
                                                   
                                                     $even=$produccion['0']->evento_grande;
                                                         //$even=$grandes_even[0]*$even;
                                                     ?>
                                            <?php }else{
                                                $even=0;
                                                //$even=round(($produccion['0']->total_libretos/2));
                                            } ?>         
                                            <td><?php echo $even ?></td>
                                            <td><?php echo $grandes ?></td>
                                            <?php $total=$even-$grandes; 
                                            $class='';
                                             if($total<0){
                                                $class='td_negativo';
                                             }
                                            ?>
                                            <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                             <?php if($even or $even!=0 or $even!=null){
                                                $p=round(($total*100)/$even);
                                            }else{
                                                 $p='';
                                                } ?>
                                            <td><?php echo $p ?> %</td>    
                                        </tr>
                                    </tbody>
                                </table>
                                </div>  
                                <div class="tablecontent"> 
                                <span class="span-inline"> <label><?php echo lang('global.desglosados_libreto') ?>:</label> <?php echo $produccion['0']->total_desglosados+$produccion['0']->total_producidos ?></span> 
                                    <table class="det_proyectados">
                                        <thead>
                                            <tr>
                                                <td><?php echo lang('global.presupuestado') ?></td>
                                                <td><?php echo lang('global.desglosado') ?></td>
                                                <td><?php echo lang('global.saldo') ?></td>
                                                <td><?php echo lang('global.pronostico') ?> (<?php echo $produccion['0']->total_desglosados+$produccion['0']->total_producidos; ?> Libreto)</td>
                                                <td><?php echo lang('global.proyeccion_presu_pronos') ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $presupuestados=$produccion['0']->escenas_libretos*($produccion['0']->total_desglosados+$produccion['0']->total_producidos) ?></td>
                                                <td><?php echo $desglosadas=($produccion['0']->total_escenas_escritas)?></td>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($presupuestados!=0){
                                                $porcentaje_temp = (100*abs($total))/$presupuestados;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO 
                                                ?>
                                                <td class=""><?php echo $saldo=$presupuestados-$desglosadas?></td>

                                                <?php 

                                                 $desglosados_total=$produccion['0']->total_desglosados+$produccion['0']->total_producidos;
                                                if($produccion['0']->total_libretos and $desglosados_total and $desglosadas){
                                                  $total=round(($produccion['0']->total_libretos*$desglosadas)/$desglosados_total); 
                                                }else{
                                                    $total=0; 
                                                }
                                                $class='<?=$class?>';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $t=($produccion['0']->numero_capitulo*$produccion['0']->escenas_libretos)-$total ?>
                                                <?php $class='';
                                                 if($t<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $t ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $protagonistas_total=$produccion['0']->protagonistas_produccion?></td>
                                                <td><?php echo $protagonistas ?></td>
                                                <?php $total=$protagonistas_total-$protagonistas; 
                                                $class='';
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($protagonistas_total!=0){
                                                $porcentaje_temp = (100*abs($total))/$protagonistas_total;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                  $t=round(($produccion['0']->total_libretos*$protagonistas)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                }else{
                                                  $t=0;
                                                }
                                                
                                                $class='';
                                                 if($t<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $t=$produccion['0']->protagonistas_produccion-$total ?>
                                                <?php $class='';
                                                 if($t<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $t ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $reparto_total=($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->numero_repartos?></td>
                                                <td><?php echo $reparto ?></td>
                                                <?php $total=$reparto_total-$reparto; 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($reparto_total!=0){
                                                $porcentaje_temp = (100*abs($total))/$reparto_total;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                   $total=round(($produccion['0']->total_libretos*$reparto)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                }else{
                                                    $total=0;
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=($produccion['0']->numero_repartos*$produccion['0']->total_libretos)-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $figurante_total=($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->numero_figurantes?></td>
                                                <td><?php echo $figurante ?></td>
                                                <?php $total=$figurante_total-$figurante; 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($figurante_total!=0){
                                                $porcentaje_temp = (100*abs($total))/$figurante_total;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                     $total=round(($produccion['0']->total_libretos*$figurante)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                }else{
                                                  $total=0;        
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=($produccion['0']->numero_figurantes*$produccion['0']->total_libretos)-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $extra_total=($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->numero_extras?></td>
                                                <td><?php echo $extra ?></td>
                                                <?php $total=$extra_total-$extra; 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($extra_total!=0){
                                                $porcentaje_temp = (100*abs($total))/$extra_total;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                  $total=round(($produccion['0']->total_libretos*$extra)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                }else{
                                                   $total=0;
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=($produccion['0']->numero_extras*$produccion['0']->total_libretos)-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $total=$produccion['0']->locacion_proyectadas*($produccion['0']->total_desglosados+$produccion['0']->total_producidos) ?></td>
                                                <td><?php echo $total_locaciones ?></td>
                                                <?php  $total=$total-$total_locaciones ?>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($total_locaciones!=0){
                                                $porcentaje_temp = (100*abs($total))/$total_locaciones;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                 if($produccion['0']->total_desglosados or $produccion['0']->total_producidos ){
                                                   $total=round(($produccion['0']->total_libretos*$total_locaciones)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                 }else{
                                                     $total=0;
                                                 }
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=($produccion['0']->locacion_proyectadas*$produccion['0']->total_libretos)-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $total=$produccion['0']->numero_vehiculos*($produccion['0']->total_desglosados+$produccion['0']->total_producidos) ?></td>
                                                <td><?php echo $vehiculos_desglosados ?></td>
                                                <?php $tota=$total-$vehiculos_desglosados ?>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($vehiculos_desglosados!=0){
                                                $porcentaje_temp = (100*abs($total))/$vehiculos_desglosados;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados){
                                                    $total=round(($produccion['0']->total_libretos*$vehiculos_desglosados)/$produccion['0']->total_desglosados); 
                                                }else{
                                                    $total=0;
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=($produccion['0']->numero_vehiculos*$produccion['0']->total_libretos)-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <?php if($produccion['0']->evento_pequeno AND $produccion['0']->evento_pequeno!=0){ ?>
                                                            <?php $peque_prod=$produccion['0']->evento_pequeno;
                                                                if($peque_prod){
                                                                  $even_pe=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->evento_pequeno)/$produccion['0']->total_libretos);    
                                                                }else{
                                                                    $even_pe=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)/2));
                                                                }
                                                             ?>
                                                 <?php }else{
                                                       $even_pe=0;
                                                    } ?>
                                                <td><?php echo $p_total=$even_pe; ?></td>
                                                <td><?php echo $pequenos ?></td>
                                                <?php  $total=$even_pe-$pequenos ?>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($even_pe!=0){
                                                $porcentaje_temp = (100*abs($total))/$even_pe;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if ($produccion['0']->total_desglosados or $produccion['0']->total_producidos) {
                                                  $total=round(($produccion['0']->total_libretos*$pequenos)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos));     
                                                }else{
                                                    $total=0;
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                               <?php $total=$produccion['0']->evento_pequeno-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                <?php if($produccion['0']->evento_mediano AND $produccion['0']->evento_mediano!=0){ ?>
                                                        <?php $mediado_prod=$produccion['0']->evento_mediano;
                                                         if($mediado_prod){
                                                             $even=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->evento_mediano)/$produccion['0']->total_libretos);    
                                                         }else{
                                                             $even=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)/2));
                                                         } 
                                                         //$even=$mediado_prod[0]*$even;     ?>
                                                 <?php }else{
                                                    $even=0;
                                                 } ?>
                                                <td><?php echo $m_total=$even ?></td>
                                                <td><?php echo $medianos ?></td>
                                                <?php  $total=$even-$medianos ?>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($even!=0){
                                                $porcentaje_temp = (100*abs($total))/$even;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                    $total=round(($produccion['0']->total_libretos*$medianos)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                }else{
                                                    $total=0;
                                                }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=$produccion['0']->evento_mediano-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                            <tr>
                                                
                                                <?php if($produccion['0']->evento_grande AND $produccion['0']->evento_grande!=0){ ?>
                                                        <?php $grandes_even=$produccion['0']->evento_grande;
                                                        if($grandes_even){
                                                             
                                                             $even=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)*$produccion['0']->evento_grande)/$produccion['0']->total_libretos);    
                                                        }else{
                                                            //$even=round(($produccion['0']->total_desglosados));
                                                            //$even=round(($produccion['0']->total_desglosados/5));
                                                            $even=round((($produccion['0']->total_desglosados+$produccion['0']->total_producidos)/2));
                                                        }     
                                                             //$even=$grandes_even[0]*$even;
                                                         ?>
                                                <?php }else{
                                                    $even=0;
                                                   // $even=round(($produccion['0']->total_libretos/2));
                                                } ?>         
                                                <td><?php echo $g_total=$even ?></td>
                                                <td><?php echo $grandes ?></td>
                                                <?php  $total=$even-$grandes ?>
                                                <?php 
                                                $class='';
                                                // VALIDACION COLOR SALDO
                                                $porcentaje_temp =0;
                                                if($total<0){
                                                    $class='td_negativo';
                                                }
                                                if($even!=0){
                                                $porcentaje_temp = (100*abs($total))/$even;
                                                    if($porcentaje_temp>=20 ){
                                                        $class='td_negativo';
                                                    }else if( $porcentaje_temp>=10 AND $porcentaje_temp<20 ){
                                                        $class='td_orange';
                                                    }else{
                                                        $class='';
                                                    }
                                                }
                                                // FIN VALIDACION COLOR SALDO
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total;?></td>
                                                <?php 
                                                 if($produccion['0']->total_desglosados or $produccion['0']->total_producidos){
                                                    $total=round(($produccion['0']->total_libretos*$grandes)/($produccion['0']->total_desglosados+$produccion['0']->total_producidos)); 
                                                 }else{
                                                    $total=0;
                                                 }
                                                
                                                $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class=""><?php echo $total; ?></span></td>
                                                <?php $total=$produccion['0']->evento_grande-$total ?>
                                                <?php $class='';
                                                 if($total<0){
                                                    $class='td_negativo';
                                                 }
                                                ?>
                                                <td class="<?php echo $class ?>"><?php echo $total ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </li>
                <?php } ?> 


                <?php if($widgets_usuario[$xl]=="detalles produccion"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="detalles produccion" >
                        <div data-role="collapsible" data-corners="false" >
                            <h3><?php echo lang('global.detalle_produccion') ?> <span class="iconDrag"></span></h3>
                            <div class="row">
                                <div class="column six">
                                    <div class="blueBox" style="margin:0px;">
                                     <?php if($ultimo_capitulos_entregado){
                                        $n=$ultimo_capitulos_entregado['0']->numero;
                                        echo "Últimos ".lang('global.capitulos_entregados').": ".$n;
                                        }else{
                                         $n='';
                                         echo lang('global.no_hay_capitulos_entregados');
                                        } ?>
                                    </div>
                                </div>
                                <div class="column six">
                                    <div class="blueBox" style="margin:0px;">
                                        <?php if($capitulos_editados){
                                        $n=$capitulos_editados['0']->total;
                                        echo lang('global.capitulos_editados').": ".$n;
                                        }else{
                                         $n='';
                                         echo lang('global.no_hay_capitulos_entregados');
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column twelve">
                                    <div class="dataBox" style="padding:0 10px;">
                                        <div id="chartDetalleCapitulos" class="chartBox"></div> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">   
                                <div class="column twelve">
                                    <div class="dataBox" id="chartDetalleEscenaContainer">
                                        <div id="chartDetalleEscena" class="chartBox"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column twelve">
                                    <div class="dataBox">
                                        <div id="chartDetalleMinutos" class="chartBox"></div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>

                <?php if($widgets_usuario[$xl]=="detalle diario"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="detalle diario">
                      <div data-role="collapsible" data-corners="false">
                        <h3><?php echo lang('global.detalle_diario') ?> <span class="iconDrag"></span></h3>
                        <div class="detalleminutos">
                                <?php if($unidades){  ?>
                                    <?php $i=0; ?> 
                                <?php $totales =0; $totales_mins=0; $unidad_llamado=''; $estado_plan_unidad=""; ?>
                                    <?php foreach ($unidades as $u) { ?>
                                                   <?php $fecha=date('Y-m-d');
                                                    $unidad=$this->model_dashboard->detalle_unidad($u['id'],$fecha);
                                                     ?>
                                                    <?php $acum_min_producidos=0;$acum_seg_producidos=0;$acum_min_porproducir=0;$acum_seg_porproducir=0; ?>
                                                    <?php $cont=0;$cont_min_producidos=0;$cont_min_porproducir=0;$cont2=0;$totales_mins_producidos=0;
                                                    $unidad_llamado='';$estado_plan_unidad='' ?>
                                                            <?php if($unidad){ ?>
                                                                    <?php $totales_mins=0;   ?>
                                                                    <?php foreach ($unidad as $uni) { ?>
                                                                       <?php $unidad_llamado=$uni['llamado']; 
                                                                       $estado_plan_unidad=$uni['estado_plan'];?>
                                                                                <?php if($uni['estado']==1){ ?>
                                                                                    <?php 
                                                                                      $cont++; 
                                                                                      $cont_min_producidos=$cont_min_producidos+$uni['duracion_real_minutos']+(round($uni['duracion_real_segundos']/60));
                                                                                      $acum_min_producidos+=$uni['duracion_real_minutos'];
                                                                                      $acum_seg_producidos+=$uni['duracion_real_segundos'];
                                                                                    ?>
                                                                                <?php }else{
                                                                                      $cont_min_porproducir=$cont_min_porproducir+$uni['duracion_estimada_minutos']+(round($uni['duracion_estimada_segundos']/60));
                                                                                      $cont2++; 
                                                                                      /*$acum_min_porproducir+=$uni['duracion_estimada_minutos'];
                                                                                      $acum_seg_porproducir+=$uni['duracion_estimada_segundos'];*/
                                                                                     }
                                                                                     $acum_min_porproducir+=$uni['duracion_estimada_minutos'];
                                                                                     $acum_seg_porproducir+=$uni['duracion_estimada_segundos']; ?>
                                                                                <?php 
                                                                                //$totales_mins=$totales_mins+$uni['duracion_estimada_minutos']+(round($uni['duracion_estimada_segundos']/60)); 
                                                                                $totales_mins=$totales_mins+$uni['duracion_estimada_minutos']+(($uni['duracion_estimada_segundos']/60)); 
                                                                                $totales_mins_producidos=$totales_mins+$acum_min_producidos+(($acum_min_producidos/60)); 

                                                                                ?>
                                                                                
                                                                    <?php } ?>
                                                                    <?php $totales = $cont + $cont2; ?>
                                                                    <?php $tiempo_producido=Dashboard::calculo_tiempo2($acum_min_producidos,$acum_seg_producidos) ?>
                                                                    <?php $totales_mins_producidos=$acum_min_producidos+(($acum_seg_producidos/60)); ?>
                                                                    <?php $tiempo_porproducido=Dashboard::calculo_tiempo2($acum_min_porproducir,$acum_seg_porproducir);?>
                                                            <?php }else{ ?>  
                                                            <?php $totales=''; ?>
                                                            <?php } ?>

                                                            <script type="text/javascript">
                                                            function drawCircle(selector, center, radius, angle, x, y){
                                                                var total = jQuery(selector).length;
                                                                var alpha = Math.PI * 2 / total;
                                                                jQuery(selector).each(function(index){
                                                                    var theta = alpha * index;
                                                                    var pointx  =  Math.floor(Math.cos( theta - 69.9 ) * radius);
                                                                    var pointy  = Math.floor(Math.sin( theta - 69.9 ) * radius );
                                                                    
                                                                    jQuery(this).css('margin-left', pointx + x + 'px');
                                                                    jQuery(this).css('margin-top', pointy  + y  + 'px');
                                                                });
                                                            }
                                                            $(document).ready(function(){
                                                                var clas='.n_box_data'+<?=$u['numero']?>;
                                                                var clas2='.n_box_mins_data'+<?=$u['numero']?>;
                                                                drawCircle(clas, 60, 110, 0, 78, 88); 
                                                                drawCircle(clas2, 60, 110, 0, 78, 88); 
                                                            });
                                                            </script>   
                                                            <div class="column six" id="detail_daily" style="height:340px;">
                                                                 <?php 
                                                                    if($estado_plan_unidad!=5){ 
                                                                        if($cont>0){
                                                                           echo "<h2 class='header-detalle' style='background:#8cdd16'>";
                                                                        }else{
                                                                           echo "<h2 class='header-detalle' style='background:#4D4D4D'>";
                                                                        }
                                                                    }else{
                                                                         echo "<h2 class='header-detalle' style='background:#fee93e'>";
                                                                    } ?>
        
                                                                    <?php echo 'Unidad ' .$u['numero'] ?>: 
                                                                    <span style="color:#FFF"><?php echo lang('global.llamado').$unidad_llamado; ?>
                                                                    <!-- VALIDACIÓN DE ESTADO DEL PLAN -->
                                                                    <?php 
                                                                    if($estado_plan_unidad!=5){ 
                                                                        /*if($unidad_llamado=="" OR strtotime($unidad_llamado)>strtotime(date('H:i:s'))){
                                                                            echo "- <span style='color:#FFF'>NO INICIADO</span>";
                                                                        }else{
                                                                            echo "- <span style='color:#FFF'>EN PROGRESO</span>";
                                                                        }*/
                                                                         
                                                                        if($cont>0){
                                                                            echo "- <span style='color:#FFF'>EN PROGRESO</span>";

                                                                        }else{
                                                                            echo "- <span style='color:#FFF'>NO INICIADO</span>";
                                                                        }
                                                                    }else{
                                                                        echo "- <span style='color:#FFF'>CERRADO</span>";
                                                                    } ?>
                                                                    </span>
                                                                    <!-- FIN VALIDACIÓN DE ESTADO DEL PLAN -->
                                                                </h2>
                                                                <?php if($totales>0 and $totales!=''){ ?>
                                                                    <div class="demo">
                                                                    <?php $valor=$totales/8; ?>
                                                                    <?php  ?>
                                                                    <div class="numeros_fl">
                                                                        <?php  for ($valores_libretos=$totales/8; $valores_libretos<=$totales;) { ?>
                                                                                <input type="text" disabled="disabled" class="n_box n_box_data<?=$u['numero']?>" value="<?php echo round($valores_libretos,1); ?>">
                                                                                <?php //echo $valor.'alex'; ?>
                                                                                <?php $valores_libretos=$valores_libretos+$valor;
                                                                                $i++;
                                                                            } ?>

                                                                    </div>

                                                                        <div style="position:relative;width:200px; height:200px; z-index:20;margin:auto">
                                                                            <div style="position:absolute;left:0px;top:0px">
                                                                                <div class="scroll_demo">
                                                                                    <div class="up carrucel" data-tipo="1" data-posicion="1" data-unidad="<?php echo $u['numero']  ?>"></div>
                                                                                        <div class="mover">
                                                                                            <div class="numeros producidas_libretos_<?php echo $u['numero']  ?>"><span><?php echo $cont; ?></span><br /><?php echo lang('global.escenas_pro') ?></div>
                                                                                            <div class="numeros proyectados_libretos_<?php echo $u['numero']  ?>"><span><?php echo $totales; ?></span><br /><?php echo lang('global.escenas_pauta') ?></div>
                                                                                        </div>
                                                                                    <div class="down down_libretos carrucel" data-tipo="1" data-posicion="1" data-unidad="<?php echo $u['numero']  ?>"></div>
                                                                                </div>
                                                                                <input class="knob" data-readOnly=true data-max="<?php echo $totales ?>" data-bgColor="#CCC" data-fgColor="#fced20" data-displayInput=false data-width="200" data-height="200" data-thickness=".14" value="<?php echo $cont ?>">
                                                                            </div>
                                                                            <div style="position:absolute; top:226px; left:0px; width:100%;">
                                                                                <ul class="big">
                                                                                    <li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#cccccc; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span><?php echo lang('global.escenas_pauta') ?></li>
                                                                                    <li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#fbed20; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span><?php echo lang('global.escenas_pro') ?></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="demo">
                                                                    <?php $valor=explode(':', $tiempo_porproducido); ?>
                                                                    <?php $valor_minutos=$valor[0]/8; 
                                                                    if($valor_minutos<=0){
                                                                       $valor2=$valor[1];
                                                                        $valor_minutos=$valor[1]/8;     
                                                                        $valores_minutos=$valor[1]/8; 
                                                                    }else{
                                                                        $valor2=$valor[0];
                                                                        $valor_minutos=$valor[0]/8;     
                                                                        $valores_minutos=$valor[0]/8;
                                                                    }
                                                                    
                                                                    $totales_mins=$totales_mins;
                                                                    $cont_min=0;
                                                                   ?>     
                                                                    <div class="numeros_fl">
                                                                    <?php while($valores_minutos<=$valor2) { ?>
                                                                            <?php if($cont_min==7){?>
                                                                                <input type="text" disabled="disabled" class="n_box_mins n_box_mins_data<?=$u['numero']?>" value="<?php echo $tiempo_porproducido; ?>">
                                                                            <?php }else{ ?>
                                                                                <input type="text" disabled="disabled" class="n_box_mins n_box_mins_data<?=$u['numero']?>" value="<?php echo round($valores_minutos,1); ?>">
                                                                            <?php } ?>
                                                                            <?php $cont_min++; 
                                                                            $valores_minutos = $valores_minutos+$valor_minutos;
                                                                            $i++;
                                                                        }  ?>

                                                                    </div>
                                                                        <div style="position:relative;width:200px; height:200px; z-index:20;margin:auto">
                                                                            <div style="position:absolute;left:0px;top:0px">
                                                                                <div class="scroll_demo" style="z-index:10">
                                                                                    <div class="up carrucel2" data-tipo="2" data-posicion="1" data-unidad="<?php echo $u['numero']  ?>"></div>
                                                                                        <div class="mover">
                                                                                            <div class="numeros producidas_minutos_<?php echo $u['numero']  ?>"><span><?php echo $tiempo_producido ?></span><br /><?php echo lang('global.minutos_producidos') ?></div>
                                                                                            <div class="numeros proyectados_minutos_<?php echo $u['numero']  ?>"><span><?php echo $tiempo_porproducido ?></span><br /><?php echo lang('global.minutos_estimados') ?></div>
                                                                                        </div>
                                                                                    <div class="down carrucel2" data-tipo="2" data-posicion="1" data-unidad="<?php echo $u['numero']  ?>"></div>
                                                                                </div>
                                                                                <input class="knob" data-readOnly=true data-max="<?php echo $valores_minutos ?>" data-bgColor="#CCC" data-fgColor="#fced20" data-displayInput=false data-width="200" data-height="200" data-thickness=".14" value="<?php echo $totales_mins_producidos ?>">
                                                                            </div>
                                                                            <div style="position:absolute; top:226px; left:0px; width:100%;">
                                                                                <ul class="big">
                                                                                    <li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#cccccc; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span><?php echo lang('global.minutos_asignados') ?></li>
                                                                                    <li style="float:left; width:50%; border:none; color:#000;font-size: 8px;"><span style="background:#fbed20; width:30px; height:30px; padding:0 5px; border-radius:15px; -webkit-border-radius:15px;"></span><?php echo lang('global.minutos_producidos') ?></li>
                                                                                </ul>
                                                                            </div>

                                                                            <?php if($totales_mins_producidos>$valores_minutos){ ?>
                                                                            <div style="position:absolute;left:14px;top:14px">
                                                                                <input class="knob" data-readOnly=true data-max="<?php echo $totales_mins_producidos ?>" data-bgColor="#ffffff" data-fgColor="#E8D540" data-displayInput=false data-width="172" data-height="172" data-thickness=".14" data-angleOffset=-0  value="<?php echo $totales_mins_producidos-$valores_minutos ?>">
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                <?php }else{ ?> 
                                                                    <div class="demo">
                                                                     <h3><?php echo lang('global.este_trabajo_no_tiene_trabajo_para_este_dia') ?></h3>
                                                                    </div>  
                                                                <?php } ?>

                                                            <!-- ESCENAS EN GRABACION -->
                                                            <?php  $escenas_grabacion = $this->model_dashboard->escenas_grabacion($u['id']); ?>
                                                            <?php if($escenas_grabacion){ ?>
                                                                <div class="column six" style="margin-top: 10px;">
                                                                    <strong><?php echo lang('global.grabando_escenas') ?>: </strong>
                                                                    <?php foreach ($escenas_grabacion as $escena_grabacion) { 
                                                                            echo $escena_grabacion->numero_libreto.'/'.$escena_grabacion->numero_escena.',';
                                                                     } ?>
                                                                </div>
                                                            <?php } ?>
                                                            <!-- FIN ESCENAS EN GRABACION -->
                                                            </div>


                                                        <?php $i++; ?>
                                            <?php } ?>

                                <?php } ?> 

                        </div>
                        <div class="clr"></div>         
                        <div class="actualizar_plan"><?php echo lang('global.actualizar') ?></div>
                     </div> 
                    </li>
                <?php } ?>

                <?php if($widgets_usuario[$xl]=="detalle semanal y acumulado"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="detalle semanal y acumulado">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.detalle_semanal_y_acumulado') ?><span class="iconDrag"></span></h3>
                           <div> 
                            <?php if($detalle_semana_actual){ ?>
                            
                                    <h4 class="titulo">
                                            <?php echo lang('global.detalle_semanal_actual') ?>, <?php echo date("d-M-Y",strtotime($detalle_semana_actual['0']->fecha_inicio_semana)).' a '. date("d-M-Y",strtotime($detalle_semana_actual['0']->fecha_fin_semana));  ?> [<?php echo $detalle_semana_actual['0']->semanas_actual ?>/<?php echo $detalle_semana_actual['0']->total_semanas ?>]
                                            </h4>
                                            <div>
                                                <div class="row">
                                                <div class="column six">
                                                    <div class="dataBox">
                                                        <div class="simpleInfo">
                                                        <?php
                                                        $capitulos_entregado=0;
                                                        $total_minutos=0;
                                                        $total_segundos=0;
                                                            foreach ($capitulos as $capitulo) {

                                                                if(strtotime($capitulo->fecha_entregado) >= strtotime($detalle_semana_actual['0']->fecha_inicio_semana)  AND strtotime($capitulo->fecha_entregado) <= strtotime($detalle_semana_actual['0']->fecha_fin_semana)){
                                                                    $capitulos_entregado++;
                                                                    if($capitulo->duracion_real){
                                                                        $tiempo=explode(":",$capitulo->duracion_real);
                                                                        $total_segundos=$total_segundos+$tiempo['1'];
                                                                        $total_minutos=$total_minutos+$tiempo['0'];
                                                                    }

                                                                }
                                                            }
                                                            $tiempo=$total_minutos+(round($total_segundos/60));
                                                            $total_entregados=$capitulos_entregado-$detalle_semana_actual['0']->capitulos_programados;
                                                            if($total_entregados<0){
                                                                $class='red';
                                                            }else{
                                                                 $class='green';
                                                            }
                                                        ?>
                                                            Libretos Entregados:<br> <?php echo $capitulos_entregado ?> de <?php echo $detalle_semana_actual['0']->capitulos_programados ?> [<span class="<?php echo $class ?>"><?php echo $total_entregados ?></span>]
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                  $acum_total=$detalle_semana_actual['0']->minutos_proyectados.':00';
                                                  $sem=explode(':',$acum_semana);
                                                  $acum=explode(':',$acum_total);
                                                  $min1=$sem[0]*60;
                                                  $min2=$acum[0]*60;
                                                  if($min1>=0){
                                                     $seg1=$min1+$sem[1];
                                                  }else{
                                                     $seg1=$min1-$sem[1];
                                                  }
                                                  if($min2>=0){
                                                    $seg2=$min2+$acum[1];  
                                                  }else{
                                                    $seg2=$min2-$acum[1];
                                                  }
                                                  
                                                  $acumtotal=$seg2+$seg1;
                                                  $color_cell = explode(':',$acumtotal);
                                                  if($color_cell[0]<0){
                                                    $classe="red";
                                                  }else{
                                                    $classe="green";
                                                  }

                                                  $color_cell_2 = explode(':',$acum_total);
                                                  if($color_cell[0]<0){
                                                    $color_cell_2="red";
                                                  }else{
                                                    $color_cell_2="green";
                                                  }

                                                  $acumtotal=Dashboard::tiempo_segundos($acumtotal);
                                                ?>
                                                <?php  $total_acumulados=($acum_semana+1)-$detalle_semana_actual['0']->minutos_proyectados;
                                                       $total_acumulados_segundos=explode(':',$acum_semana);
                                                       $total_acumulados_segundos=60-$total_acumulados_segundos[1];
                                                       if($total_acumulados<0){
                                                            if($total_acumulados_segundos==60){
                                                                $total_acumulados-=1;
                                                                $total_acumulados_segundos=00;
                                                            }
                                                            $class='red';
                                                        }else{
                                                            if($total_acumulados_segundos==60){
                                                                $total_acumulados+=1;
                                                                $total_acumulados_segundos=00;
                                                            }
                                                            $class='green';
                                                        }
                                                 ?>
                                                <div class="column six">
                                                    <div class="dataBox">
                                                        <div class="simpleInfo">
                                                            <?php echo lang('global.minutos_acumulados_totales') ?>:<br><?php echo $acum_semana ?> de <?php echo $detalle_semana_actual['0']->minutos_proyectados ?> [<span class="<?php echo $class ?>"><?php echo $total_acumulados.':'.$total_acumulados_segundos  ?></span>]
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <!--inicio-->
                                        <div>
                                            <div>
                                                <div class="row">
                                                <div class="column six">
                                                    <div class="dataBox">
                                                        <div class="simpleInfo">
                                                        <?php
                                                            $total_entregados=$capitulos_entregados2['0']->total-$produccion['0']->capitulos_proyectados;
                                                            if($total_entregados<0){
                                                                $class='red';
                                                            }else{
                                                                 $class='green';
                                                            }
                                                        ?>
                                                            <?php echo lang('global.capitulos_entregados') ?>:<br> <?php echo $capitulos_entregados2['0']->total ?> de <?php echo $produccion['0']->capitulos_proyectados?> [<span class="<?php echo $class ?>"><?php echo $total_entregados ?></span>]
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                            </div>
                            <?php } ?>
                            <div>
                                <?php if($detalle_semana_actual){?>
                                <?php
                                  $m=sizeof($acumulado)-1;
                                ?>
                                <?php
                                    $capitulos_entregado=0;
                                    $total_minutos=0;
                                    $total_segundos=0;
                                        foreach ($capitulos as $capitulo) {
                                            if(strtotime($capitulo->fecha_entregado) <= strtotime($acumulado[$m]['fecha_fin_semana'])){
                                                $capitulos_entregado++;
                                                if($capitulo->duracion_real){
                                                    $tiempo=explode(":",$capitulo->duracion_real);
                                                    $total_segundos=$total_segundos+$tiempo['1'];
                                                    $total_minutos=$total_minutos+$tiempo['0'];
                                                }

                                            }
                                        }
                                        $tiempo=$total_minutos+(round($total_segundos/60));
                                        $total_entregados=$capitulos_entregado-$produccion['0']->total_libretos;
                                        if($total_entregados<0){
                                            $class='red';
                                        }else{
                                             $class='green';
                                        }
                                    ?>
                                <div>
                                    <h4 class="titulo">
                                    <?php echo lang('global.acumulado_hasta') ?> <?php echo date("d-M-Y",strtotime($acumulado[$m]['fecha_fin_semana'])); ?>
                                    </h4>
                                    <div>
                                        <div class="row">
                                            <div class="column six">
                                                <div class="dataBox">
                                                    <div class="simpleInfo">
                                                        <?php echo lang('global.total_libretos_entregados') ?>:<br> <?php echo $capitulos_entregado ?> de <?php echo $produccion['0']->total_libretos; ?> [<span class="<?php echo $class ?>"><?php echo $total_entregados ?></span>]
                                                    </div>
                                                </div>
                                            </div>
                                            <?php  
                                                   if($acumulado_diferencia<0){
                                                        $class='red';
                                                    }else{
                                                        $class='green';
                                                    }
                                             ?>
                                            <div class="column six">
                                                <div class="dataBox">
                                                    <div class="simpleInfo">
                                                        <?php echo lang('global.minutos_acumulados_totales') ?>: <br> <?php echo $acumulado_minutos_totales ?> de <?php echo $acumulado_minutos ?> [<span class="<?php echo $class ?>"><?php echo $acumulado_diferencia ?></span>]
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div>
                                    <?php 
                                    $total_entregados=$acumulados_entregados_capitulos['0']->total-($total_semanas_fecha['0']->total*$produccion['0']->capitulos_proyectados);
                                        if($total_entregados<0){
                                            $class='red';
                                        }else{
                                             $class='green';
                                        } 
                                    ?>  
                                    <div class="row">
                                            <div class="column six">
                                                <div class="dataBox">
                                                    <div class="simpleInfo">
                                                        <?php echo lang('global.capitulos_entregados_totales') ?>:<br> <?php echo $acumulados_entregados_capitulos['0']->total ?> de <?php echo $total_semanas_fecha['0']->total*$produccion['0']->capitulos_proyectados; ?> [<span class="<?php echo $class ?>"><?php echo $total_entregados ?></span>]
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } else{ ?>
                                        <h4><?php echo lang('global.detalle_semanal_actual') ?><span class="iconDrag"></span></h4>
                                        <div><?php echo lang('global.nodatos') ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?> 


                <?php if($widgets_usuario[$xl]=="graficas comparativas del proyecto"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="graficas comparativas del proyecto">  
                        <div data-role="collapsible" data-corners="false">
                        <h3><?php echo lang('global.graficas_comparativas_del_proyecto') ?><span class="iconDrag"></span></h3>
                         <?php if($comparativos['0']->total){ ?>
                           <?php $porcentaje_locacion=round(100*$comparativos['0']->locacion/$comparativos['0']->total); 
                                 $porcentaje_estudio=round(100*$comparativos['0']->estudio/$comparativos['0']->total); 
                           ?>
                            <div class="graficasContent">
                                <div class="row">
                                    <div class="column four">
                                        <div id="pie1" style="">
                                            <div class="dataBox">
                                                <script type="text/javascript">
                                                var locacion=<?php echo $porcentaje_locacion ?>;
                                                var estudio=<?php echo $porcentaje_estudio ?>;
                                                  google.load('visualization', '1.0', {'packages':['corechart']});
                                                  google.setOnLoadCallback(drawChart);
                                                  function drawChart() {
                                                    var data = new google.visualization.DataTable();
                                                    data.addColumn('string', 'Topping');
                                                    data.addColumn('number', 'Slices');
                                                    data.addRows([
                                                      ['Estudio', estudio],
                                                      ['Locación', locacion]
                                                    ]);
                                                    var options = {
                                                       'width':200,
                                                       'height':200,
                                                       colors:['#e4228e','#8bdd16'],
                                                       fontSize: 12,
                                                       is3D: true,
                                                       chartArea:{left:10,top:0,width:"100%",height:"100%"},
                                                       legend:{position: 'bottom', alignment: 'center', textStyle: {color: 'gray', fontSize: 9}}
                                                     };
                                                    var chart = new google.visualization.PieChart(document.getElementById('loc_vs_est_chart'));
                                                    ///carlos
                                                    chart.draw(data, options);
                                                    $('#img_pie1').attr('src', chart.getImageURI());
                                              }
                                                    
                                                </script>
                                                
                                                <h4><?php echo lang('global.locacion_vs_estudio') ?></h4>
                                                <div class="indicadoresBox">
                                                    <div><a target=""  href="#" class="export_pie" data-chart="pie1"><?php echo lang('global.guardar') ?></a></div>
                                                    <ul class="big">
                                                        <li><strong><?php echo lang('global.indicadore') ?> :</strong></li>
                                                        <li><span style="background:#8bdd16;"></span> <?php //echo $porcentaje_locacion ?> <?php echo $produccion['0']->locacion ?>%</li>
                                                        <li><span style="background:#e4228e;"></span> <?php //echo $porcentaje_estudio ?><?php echo $produccion['0']->estudio ?>%</li>
                                                    </ul>
                                                </div>  
                                                <div id="loc_vs_est_chart" class="googleChart"></div>
                                                 <img src="" alt="" id="img_pie1" style="display: none;margin: 0 auto;">
                                                <div class="indicadoresBox">
                                                    <ul class="small">
                                                        <li><span style="background:#8bdd16;"></span> <?php echo lang('global.locacion') ?></li>
                                                        <li><span style="background:#e4228e;"></span> <?php echo lang('global.estudio') ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <?php $porcentaje_interior=round(100*$comparativos['0']->interior/$comparativos['0']->total); 
                                          $porcentaje_exterior=round(100*$comparativos['0']->exterior/$comparativos['0']->total);
                                          $porcentaje_interior_locacion=round(100*$comparativos['0']->interior_locacion/$comparativos['0']->total);
                                          $porcentaje_interior_estudio=round(100*$comparativos['0']->interior_estudio/$comparativos['0']->total);
                                          $porcentaje_exterior_locacion=round(100*$comparativos['0']->exterior_locacion/$comparativos['0']->total);
                                          $porcentaje_exterior_estudio=round(100*$comparativos['0']->exterios_estudio/$comparativos['0']->total);
                                    ?>
                                    <div class="column four">
                                        <div id="pie2">
                                            <div class="dataBox">
                                                <script type="text/javascript">
                                                var interior_locacion=<?php echo $porcentaje_interior_locacion ?>;
                                                var interior_estudio=<?php echo $porcentaje_interior_estudio ?>;
                                                var exterior_locacion=<?php echo $porcentaje_exterior_locacion ?>;
                                                var exterior_estudio=<?php echo $porcentaje_exterior_estudio ?>;
                                                  google.load('visualization', '1.0', {'packages':['corechart']});
                                                  google.setOnLoadCallback(drawChart);
                                                  function drawChart() {
                                                    var data = new google.visualization.DataTable();
                                                    data.addColumn('string', 'Topping');
                                                    data.addColumn('number', 'Slices');
                                                    data.addRows([
                                                      ['Int. Locación', interior_locacion],
                                                      ['Int. Estudio', interior_estudio],
                                                      ['Ext. Locación', exterior_locacion],
                                                      ['Ext. Estudio', exterior_estudio],
                                                    ]);
                                                    var options = {
                                                       'width':200,
                                                       'height':200,
                                                       colors:['#c16c27','#ff9933','#8bc347','#6c8b3a'],
                                                       fontSize: 12,
                                                       is3D: true,
                                                       chartArea:{left:10,top:0,width:"100%",height:"100%"},
                                                       legend:{position: 'bottom', alignment: 'center', textStyle: {color: 'gray', fontSize: 9}}
                                                     };
                                                    var chart = new google.visualization.PieChart(document.getElementById('int_vs_ext_chart'));
                                                    chart.draw(data, options);
                                                     $('#img_pie2').attr('src', chart.getImageURI());
                                                  }
                                                </script>
                                                <h4><?php echo lang('global.interior_vs_exterior') ?></h4>
                                                <div class="indicadoresBox">
                                                    <div><a target="_blank"  href="#" class="export_pie" data-chart="pie2"><?php echo lang('global.guardar') ?></a></div>
                                                    <ul class="big">
                                                        <li><strong><?php echo lang('global.graficas_comparativas_del_proyecto') ?>:</strong></li>
                                                        <li><span style="background:#8bc347;"></span> <?php //echo $porcentaje_exterior ?><?php echo $produccion['0']->produccion_exterior ?> %</li>
                                                        <li><span style="background:#ff9933;"></span> <?php //echo $porcentaje_interior ?><?php echo $produccion['0']->produccion_interior ?> %</li>
                                                    </ul>
                                                </div>  
                                                <div id="int_vs_ext_chart" class="googleChart"></div>
                                                <img src="" alt="" id="img_pie2" style="display: none;margin: 0 auto;">
                                                <div class="indicadoresBox">
                                                    <ul class="small">
                                                        <li><span style="background:#7ca840;"></span> <?php echo lang('global.ext_locacion') ?></li>
                                                        <li><span style="background:#8bc347;"></span> <?php echo lang('global.ext_estudio') ?></li>
                                                        <li><span style="background:#c16c27;"></span> <?php echo lang('global.int_locacion') ?></li>
                                                        <li><span style="background:#ff9933;"></span> <?php echo lang('global.int_estudio') ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $porcentaje_dia=round(100*$comparativos['0']->dia/$comparativos['0']->total); 
                                        $porcentaje_noche=round(100*$comparativos['0']->noche/$comparativos['0']->total);
                                        $porcentaje_locacion_dia=round(100*$comparativos['0']->locacion_dia/$comparativos['0']->total);
                                        $porcentaje_locacion_noche=round(100*$comparativos['0']->locacion_noche/$comparativos['0']->total);
                                        $porcentaje_estudio_dia=round(100*$comparativos['0']->estudio_dia/$comparativos['0']->total);
                                        $porcentaje_estudio_noche=round(100*$comparativos['0']->estudio_noche/$comparativos['0']->total);
                                    ?>
                                    <div class="column four">
                                        <div id="pie3">
                                            <div class="dataBox">
                                                <script type="text/javascript">
                                                var locacion_dia=<?php echo $porcentaje_locacion_dia ?>;
                                                var estudio_dia=<?php echo $porcentaje_estudio_dia ?>;
                                                var locacion_noche=<?php echo $porcentaje_locacion_noche ?>;
                                                var estudio_noche=<?php echo $porcentaje_estudio_noche ?>;
                                                  google.load('visualization', '1.0', {'packages':['corechart']});
                                                  google.setOnLoadCallback(drawChart);
                                                  function drawChart() {
                                                    var data = new google.visualization.DataTable();
                                                    data.addColumn('string', 'Topping');
                                                    data.addColumn('number', 'Slices');
                                                    data.addRows([
                                                      ['Día Locación', locacion_dia],
                                                      ['Día Estudio', estudio_dia],
                                                      ['Noche Locación', locacion_noche],
                                                      ['Noche Estudio', estudio_noche],
                                                    ]);
                                                    var options = {
                                                       'width':200,
                                                       'height':200,
                                                       colors:['#c4ae1a','#fbed20','#0070bb','#04567b'],
                                                       fontSize: 12,
                                                       is3D: true,
                                                       chartArea:{left:10,top:0,width:"100%",height:"100%"},
                                                       legend:{position: 'bottom', alignment: 'center', textStyle: {color: 'gray', fontSize: 9}}
                                                     };
                                                    var chart = new google.visualization.PieChart(document.getElementById('dia_vs_noc_chart'));
                                                    chart.draw(data, options);
                                                    $('#img_pie3').attr('src', chart.getImageURI());
                                                  }
                                                </script>
                                                <h4><?php echo lang('global.dias_vs_noche') ?></h4>
                                                <div class="indicadoresBox">
                                                    <div><a target="_blank"  href="#" class="export_pie" data-chart="pie3"><?php echo lang('global.guardar') ?></a></div>
                                                    <ul class="big">
                                                        <li><strong><?php echo lang('global.indicadore') ?>:</strong></li>
                                                        <li><span style="background:#0070bb;"></span> <?php //echo $porcentaje_noche ?><?php echo $produccion['0']->noche ?> %</li>
                                                        <li><span style="background:#fbed20;"></span> <?php //echo $porcentaje_dia ?><?php echo $produccion['0']->dia ?> %</li>
                                                    </ul>
                                                </div>  
                                                <div id="dia_vs_noc_chart" class="googleChart"></div>
                                                <img src="" alt="" id="img_pie3" style="display: none;margin: 0 auto;">
                                                <div class="indicadoresBox">
                                                    <ul class="small">
                                                        <li><span style="background:#04567b;"></span> <?php echo lang('global.noche_locacion') ?></li>
                                                        <li><span style="background:#0070bb;"></span> <?php echo lang('global.noche_estudio') ?></li>
                                                        <li><span style="background:#c4ae1a;"></span> <?php echo lang('global.dia_locacion') ?></li>
                                                        <li><span style="background:#fbed20;"></span> <?php echo lang('global.dia_estudio') ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <?php }else{ ?>
                           <div><?php echo lang('global.nodatos') ?></div>
                           <?php } ?>   
                        </div>
                    </li>
                <?php } ?>

                <?php if($widgets_usuario[$xl]=="minutos proyectados vs minutos producidos semanales"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="minutos proyectados vs minutos producidos semanales">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.proyectados_vs_producidos_semanales') ?><span class="iconDrag"></span></h3>
                            <div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <h5 style="padding-left:50px"><?php echo lang('global.total_minutos_producidos') ?>: <?php echo $total_capitulos_entregados ?></h5> 
                                            <div id="chartMinProyVSMinProd"></div>
                                            <div id="minutosProyectadosvsProducidos_id" class="arrow_right" data-cantidad="10"><?php echo lang('global.cargar') ?></div>
                                            <div id="anterior" class="arrow_left"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="<?php echo $limit_semanas ?>" class="minutosProyectadosvsProducidos">
                                        </div>
                                    </div>
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="chartEscenasPautadasVSEscenasProducidas"></div>
                                            <div id="escenasPautadasvsProducidos_id" class="arrow_right" data-cantidad="10"><?php echo lang('global.cargar') ?></div>
                                            <div id="anteriorEscenasProyectadas" class="arrow_left"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="<?php echo $limit_semanas ?>" class="escenasPautadasvsProducidos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?> 

                <?php if($widgets_usuario[$xl]=="libretos proyectados vs entregados"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="libretos proyectados vs entregados">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.libretos_proyectados_vs_entregados') ?> <span class="iconDrag"></span></h3>
                            <div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <h5 style="padding-left:50px"><?php echo lang('global.total_libretos_entregados') ?>: <?php echo $acu_entre ?></h5> 
                                            <div id="chartCapProyVSCapProd"></div>
                                            <div id="libretos" class="arrow_right"><?php echo lang('global.cargar') ?></div>
                                            <div id="libretros_anterior" class="arrow_left"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="<?php echo $limit_semanas ?>" class="libretos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?> 

                <?php if($widgets_usuario[$xl]=="resumen indicadores libreto"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="resumen indicadores libreto">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.resumen_indicadores_libretos') ?> <span class="iconDrag"></span></h3>
                            <div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="chartLocVSEst"></div>
                                            <div id="" data-anterior="1" class="arrow_right LocvsEst"><?php echo lang('global.cargar') ?></div>
                                            <div id="" data-anterior="0" class="arrow_left LocvsEst"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="LocvsEst_limit">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="chartIntVSExt"></div>
                                            <div id="" data-anterior="1" class="arrow_right intVSext"><?php echo lang('global.cargar') ?></div>
                                            <div id="" data-anterior="0" class="arrow_left intVSext"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="intVSext_limit">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="chartDiaVSNoc"></div>
                                            <div id="" data-anterior="1" class="arrow_right diaVSnoc"><?php echo lang('global.cargar') ?></div>
                                            <div id="" data-anterior="0" class="arrow_left diaVSnoc"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="diaVSnoc_limit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?> 

                

                <?php if($widgets_usuario[$xl]=="detalles post-produccion"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="detalles post-produccion">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.detalle_post_produccion') ?> <span class="iconDrag"></span></h3>
                            <div>
                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="minEstVsRealVsPots"></div>
                                            <div id="" data-anterior="1" class="arrow_right minEstVsRealVsPots"><?php echo lang('global.cargar') ?></div>
                                            <div id="" data-anterior="0" class="arrow_left minEstVsRealVsPots"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="minEstVsRealVsPots_limit">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="detallesCapitulo"></div>
                                            <div id="" data-anterior="1" class="arrow_right detallesCapitulo"><?php echo lang('global.cargar') ?></div>
                                            <div id="" data-anterior="0" class="arrow_left detallesCapitulo"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="detallesCapitulo_limit">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="column twelve">
                                        <div class="dataBox">
                                            <div id="estatusCapitulo"></div>
                                            <div id="capitulos_next" data-anterior="1" class="arrow_down_horizontal"><?php echo lang('global.cargar') ?></div>
                                            <div id="capitulos_previus" data-anterior="0" class="arrow_up_horizontal"><?php echo lang('global.anterior') ?></div>
                                            <input type="hidden" value="0" class="estatusCapitulo_limit">
                                        </div>
                                    </div>
                                </div>

                            </div>  
                        </div>
                    </li>
                <?php } ?> 

                <?php if($widgets_usuario[$xl]=="detalles indicadores"){ ++$xl; ?>
                    <li class="widget_dashboard" data-valor="detalles indicadores">
                        <div data-role="collapsible" data-corners="false">
                            <h3><?php echo lang('global.detalles_indicadores') ?> <span class="iconDrag"></span></h3>
                            <div>
                                <div class="row">
                                    <div>
                                        <?php if($capitulos_detallados){ 
                                            $total_escenas=0;
                                            $cadena_escenas="";
                                            $cadena_escenas_diferencia="";
                                            $total_exterior=0;
                                            $cadena_exterior="";
                                            $cadena_exterior_diferencia="";
                                            $total_locacion=0;
                                            $cadena_locacion="";
                                            $cadena_locacion_diferencia="";
                                            $total_noche=0;
                                            $cadena_noche="";
                                            $cadena_noche_diferencia="";
                                            $total_eventos_p=0;
                                            $cadena_eventos_p="";
                                            $cadena_eventos_p_diferencia="";
                                            $total_eventos_m=0;
                                            $cadena_eventos_m="";
                                            $cadena_eventos_m_diferencia="";
                                            $total_eventos_g=0;
                                            $cadena_eventos_g="";
                                            $cadena_eventos_g_diferencia="";
                                            $total_locaciones=0;
                                            $cadena_locaciones="";
                                            $cadena_locaciones_diferencia="";
                                            $total_sets=0;
                                            $cadena_sets=""; 
                                            $cadena_sets_diferencia="";
                                            $total__capitulox="";
                                            $cadena_capitulox="";
                                            $cadena_capitulox_diferencia="";
                                            $total__escenas_estudio="";
                                            $cadena_escenas_estudio="";
                                            $cadena_escenas_estudio_diferencia="";
                                            $total__pagina_libreto="";
                                            $cadena_pagina_libreto="";
                                            $cadena_pagina_libreto_diferencia="";
                                            $cadena_escenas_numero="";
                                            $total_escenas_numero="";
                                            $cadena_escenas2="";
                                            $cadena_escenas_numero_diferencia="";
                                            $cadena_escenas2_numero_diferencia="";
                                            $cadena_vehiculos_max="";
                                            $cadena_locaciones_nuevas_max="";
                                            $cadena_vehiculos_max_diferencia="";
                                            $cadena_locaciones_nuevas_max_diferencia="";
                                            $total_locaciones_nuevas=0;
                                            $total_vehiculos=0;
                                            $min=""; $seg=""; ?>

                                            

                                            <!-- INICIO TIEMPO DE LECTURA POR LIBRETO && paginasPorLibretos-->
                                            
                                            <!-- alteraciones
                                                ALTER TABLE  `produccion` ADD  `paginasPorLibretos` INT NULL
                                                ALTER TABLE  `produccion_has_capitulos` ADD  `paginasPorLibretos` INT NULL 
                                            -->
                
                                            <?php   

                                            foreach ($capitulox as $capx) {
                                                @list($minutox, $segundox) = split(':', $capx['duracion_estimada']);
                                                $min+=$minutox;
                                                $seg+=$segundox;


                                                if ($producciones['0']->minuto_capitulo>$minutox) {                                                 
                                                    if ($producciones['0']->segundos_capitulo==0) {
                                                        if ($segundox==0) {
                                                            $s="00";
                                                            $mint=(((($minutox*60)+$segundox)/60));
                                                        } else {
                                                            $s=abs($segundox-60);
                                                            if ($s<10) {
                                                              $s="0".abs($segundox-60); 
                                                            } else {
                                                              $s=abs($segundox-60);
                                                            }  
                                                            $mint=(((($minutox*60)+$segundox)/60)+1);                                                            
                                                        }
                                                        $m=(substr($mint,0,2)-$producciones['0']->minuto_capitulo);
                                                    }else{
                                                        $mint=(((($minutox*60)+$segundox)/60)+1);
                                                        $m=(substr($mint,0,2)-$producciones['0']->minuto_capitulo);
                                                        $s=abs($segundox-$producciones['0']->segundos_capitulo);
                                                        if ($s<10) {
                                                           $s="0".abs($segundox-$producciones['0']->segundos_capitulo);
                                                        } else {
                                                           $s=abs($segundox-$producciones['0']->segundos_capitulo);
                                                        }
                                                        
                                                        
                                                    }
                                                } else {
                                                    $m=$minutox-$producciones['0']->minuto_capitulo;
                                                    $s=$segundox-$producciones['0']->segundos_capitulo;
                                                        if ($s<10) {
                                                           $s="0".$s=$segundox-$producciones['0']->segundos_capitulo;
                                                        } else {
                                                           $s=$segundox-$producciones['0']->segundos_capitulo;
                                                        }
                                                }                                                

                                                if ($capx['duracion_estimada']) {
                                                    $cadena_capitulox.="<td >".$capx['duracion_estimada']."</td>";

                                                    $cadena_capitulox_diferencia.="<td ";
                                                    if($m<0){
                                                        $cadena_capitulox_diferencia.="style='color:red'";
                                                    }
                                                    $cadena_capitulox_diferencia.=">".($m).":".($s)."</td>";
                                                } else {
                                                    $cadena_capitulox.="<td>00:00</td>";
                                                    $cadena_capitulox_diferencia.="<td>00:00</td>";
                                                }

                                                if ($capx['paginasPorLibretos']) {                                                    
                                                    $cadena_pagina_libreto.="<td>".$capx['paginasPorLibretos']."</td>";
                                                    $dif=$capx['paginasPorLibretos']-$producciones['0']->paginasPorLibretos;
                                                    
                                                    $cadena_pagina_libreto_diferencia.="<td ";
                                                    if($dif<0){ 
                                                        $cadena_pagina_libreto_diferencia.= "style='color:red'";
                                                    }
                                                    $cadena_pagina_libreto_diferencia.=">".($dif)."</td>";
                                                } else {
                                                    $cadena_pagina_libreto.="<td>0</td>";
                                                    $cadena_pagina_libreto_diferencia.="<td ";
                                                    $dif=$capx['paginasPorLibretos']-$producciones['0']->paginasPorLibretos;
                                                    if($dif<0){ 
                                                        $cadena_pagina_libreto_diferencia.= "style='color:red'";
                                                    }
                                                    $cadena_pagina_libreto_diferencia.=">".($dif)."</td>";
                                                } 
                                                $total__pagina_libreto+=$capx['paginasPorLibretos'];

                                            } 
                                                //segundoa a minutos 
                                                $totalx=$min+($seg/60);
                                                @$total__capitulox0 = explode(".", $totalx);
                                                //minutos a horas
                                                $totalx1=$total__capitulox0[0]/60;
                                                @$total__capitulox1 = explode(".", $totalx1);
                                                //totla a impreso
                                                $total__capitulox= $total__capitulox1[0].":".substr($total__capitulox1[1],0,2).":".substr($total__capitulox0[1],0,2);
                                            ?>

                                            <!-- FIN TIEMPO DE LECTURA POR LIBRETO && paginasPorLibretos-->


                                            <? foreach ($capitulos_detallados as $capitulo_detallado) {

                                            //consultas
                                            $vehiculos = $this->model_capitulos->vehiculos_capitulo($capitulo_detallado['id_capitulo']);
                                            if($vehiculos){
                                                $total_vehiculos+= ($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background']);
                                                $cadena_vehiculos_max.= "<td>".($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background'])."</td>";
                                                $dif=($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background'])-$produccion['0']->numero_vehiculos;
                                                $cadena_vehiculos_max_diferencia.="<td ";
                                                if($dif<0){
                                                    $cadena_vehiculos_max_diferencia.= "style='color:red'";
                                                }
                                                $cadena_vehiculos_max_diferencia.=">".($dif)."</td>";
                                            }else{
                                                $cadena_vehiculos_max.= "<td>0</td>";
                                            }                                           

                                            $nuevas_locaciones = $this->model_capitulos->nuevas_locaciones($capitulo_detallado['id_capitulo'],$capitulo_detallado['id_produccion']);
                                            if($nuevas_locaciones){
                                                $total_locaciones_nuevas+= $nuevas_locaciones[0]['locaciones_nuevas'];
                                                $cadena_locaciones_nuevas_max.= "<td>".$nuevas_locaciones[0]['locaciones_nuevas']."</td>";
                                                $dif=$nuevas_locaciones[0]['locaciones_nuevas']-$producciones['0']->locaciones_nuevas;
                                                $cadena_locaciones_nuevas_max_diferencia.="<td ";
                                                if($dif<0){
                                                    $cadena_locaciones_nuevas_max_diferencia.="style='color:red'";
                                                }
                                                $cadena_locaciones_nuevas_max_diferencia.=">".($dif)."</td>";
                                            }else{
                                                $cadena_locaciones_nuevas_max.= "<td>0</td>";
                                            }
                                            //consultas



                                                $cadena_escenas.="<td ";
                                                if($produccion['0']->escenas_libretos<$capitulo_detallado['escenas_escritas']){ 
                                                    $cadena_escenas.= "'"; 
                                                }
                                                $cadena_escenas.=">".$capitulo_detallado['escenas_escritas']."</td>";                                                
                                                $total_escenas+=$capitulo_detallado['escenas_escritas'];                                                
                                                $cadena_exterior.="<td>".$capitulo_detallado['escenas_exterior']."</td>";                                                
                                                $total_exterior+=$capitulo_detallado['escenas_exterior'];

                                                $cadena_locacion.="<td ";
                                                if($capitulo_detallado['escenas_locacion']<0){
                                                    $cadena_locacion.="style='color:red'";
                                                }
                                                $cadena_locacion.=">".$capitulo_detallado['escenas_locacion']."</td>";

                                                $total_locacion+=$capitulo_detallado['escenas_locacion'];

                                                $cadena_escenas_estudio.="<td>".$capitulo_detallado['escenas_estudio']."</td>"; 
                                                $total__escenas_estudio+=$capitulo_detallado['escenas_estudio'];                                               
                                                
                                                $cadena_noche.="<td ";
                                                if($cadena_noche<0){
                                                   $cadena_noche.="style='color:red'";
                                                }
                                                $cadena_noche.=">".$capitulo_detallado['escenas_noche']."</td>";

                                                $total_noche+=$capitulo_detallado['escenas_noche'];
                                                $eventos = $this->model_capitulos->eventos_capitulo($capitulo_detallado['id_capitulo']);
                                                $eventos = explode(',', $eventos[0]['cnt']);
                                                $pequenos=0;
                                                $medianos=0;
                                                $grandes=0;
                                                for ($k=0; $k < count($eventos); ++$k) { 
                                                  $temp= explode('-', $eventos[$k]);
                                                  if(isset($temp[1])) {
                                                    if($temp[1]==2){  
                                                      $total_eventos_p+=$temp[0];
                                                      $pequenos=$temp[0];
                                                    }

                                                    if($temp[1]==3){
                                                      $total_eventos_m+=$temp[0];
                                                      $medianos=$temp[0];
                                                    }
                                                    
                                                    if($temp[1]==4){
                                                      $total_eventos_g+=$temp[0];
                                                      $grandes=$temp[0];
                                                    }
                                                  }
                                                }
                                                $cadena_eventos_p.="<td>".$pequenos."</td>";
                                                $cadena_eventos_m.="<td>".$medianos."</td>";
                                                $cadena_eventos_g.="<td>".$grandes."</td>";

                                                $cadena_escenas2.="<td >".$capitulo_detallado['escenas_escritas']."</td>";
                                                $cadena_escenas_numero.="<td>".$capitulo_detallado['numero_escenas']."</td>"; 
                                                $total_escenas_numero+=$capitulo_detallado['numero_escenas']; 

                                                $total_locaciones+=$capitulo_detallado['cantidad_locaciones'];
                                                $cadena_locaciones.="<td ";
                                                if($produccion['0']->locacion_proyectadas<$capitulo_detallado['cantidad_locaciones']){ 
                                                    $cadena_locaciones.=""; 
                                                }
                                                $cadena_locaciones.=">".$capitulo_detallado['cantidad_locaciones']."</td>";
                                                $total_sets+=$capitulo_detallado['cantidad_sets'];
                                                $cadena_sets.="<td ";
                                                if($produccion['0']->numero_set<$capitulo_detallado['cantidad_sets']){ 
                                                    $cadena_sets.= ""; 
                                                }
                                                $cadena_sets.= ">".$capitulo_detallado['cantidad_sets']."</td>";


                                                $cadena_escenas_diferencia.="<td >".($capitulo_detallado['escenas_escritas']-$producciones['0']->escenas_libretos)."</td>";
                                                $cadena_exterior_diferencia.="<td>".($capitulo_detallado['escenas_exterior']-$producciones['0']->produccion_exterior)."</td>";
                                                $cadena_locacion_diferencia.="<td >".($capitulo_detallado['escenas_locacion']-$producciones['0']->locacion)."</td>";
                                                $cadena_noche_diferencia.="<td>".($capitulo_detallado['escenas_noche']-$producciones['0']->noche)."</td>";
                                                $dif=$pequenos-$producciones['0']->evento_pequeno;
                                                $cadena_eventos_p_diferencia.="<td";
                                                if($dif<0){
                                                    $cadena_eventos_p_diferencia.=" style='color:red'";
                                                }
                                                $cadena_eventos_p_diferencia.=">".($dif)."</td>";

                                                $dif=$medianos-$producciones['0']->evento_mediano;
                                                $cadena_eventos_m_diferencia.="<td";
                                                if($dif<0){
                                                     $cadena_eventos_m_diferencia.=" style='color:red'";   
                                                }
                                                $cadena_eventos_m_diferencia.=">".($dif)."</td>";

                                                $dif=$grandes-$producciones['0']->evento_grande;
                                                $cadena_eventos_g_diferencia.="<td";
                                                if($dif<0){
                                                    $cadena_eventos_g_diferencia.=" style='color:red'"; 
                                                }
                                                $cadena_eventos_g_diferencia.=">".($dif)."</td>";


                                                $dif=$capitulo_detallado['cantidad_locaciones']-$producciones['0']->numero_locaciones;
                                                $cadena_locaciones_diferencia.="<td ";
                                                if($dif<0){
                                                     $cadena_locaciones_diferencia.="style='color:red'"; 
                                                }
                                                 $cadena_locaciones_diferencia.=">".($dif)."</td>";

                                                $dif=$capitulo_detallado['cantidad_sets']-$producciones['0']->numero_set;
                                                $cadena_sets_diferencia.="<td ";
                                                if($dif<0){
                                                     $cadena_sets_diferencia.="style='color:red'"; 
                                                }
                                                $cadena_sets_diferencia.=">".($dif)."</td>";

                                                $dif=$capitulo_detallado['escenas_estudio']-$producciones['0']->estudio;
                                                $cadena_escenas_estudio_diferencia.="<td ";
                                                if($dif<0){
                                                    $cadena_escenas_estudio_diferencia.="style='color:red'";    
                                                }
                                                $cadena_escenas_estudio_diferencia.=">".($dif)."</td>";


                                                $dif=$capitulo_detallado['numero_escenas']-$producciones['0']->escenas_libretos;
                                                $cadena_escenas_numero_diferencia.="<td ";
                                                if($dif<0){
                                                     $cadena_escenas_numero_diferencia.="style='color:red'";
                                                }
                                                $cadena_escenas_numero_diferencia.=">".($dif)."</td>"; 

                                                $dif=$capitulo_detallado['escenas_escritas']-$producciones['0']->escenas_libretos;
                                                $cadena_escenas2_numero_diferencia.="<td ";
                                                if($dif<0){
                                                    $cadena_escenas2_numero_diferencia.="style='color:red'";
                                                }
                                                $cadena_escenas2_numero_diferencia.=">".($dif)."</td>"; 
                                            } 
                                            $total_exterior=($total_exterior*100)/$total_escenas;
                                            $total_locacion=($total_locacion*100)/$total_escenas;
                                            $total_noche=($total_noche*100)/$total_escenas;
                                            $total_escenas_produccion=$produccion['0']->numero_capitulo*$produccion['0']->escenas_libretos;
                                            ?>

                                            <div class="left_table_fix info_estatica">
                                                <table class="det_proyectados">
                                                     <thead>
                                                        <tr>

                                                            <td><?php echo lang('global.descripcion') ?></td>
                                                            <td><?php echo $produccion['0']->total_libretos ?> <?php echo lang('global.capitulos') ?></td>
                                                            <td><?php echo lang('global.por_libretos') ?></td> 

                                                        </tr>
                                                    </thead>    
                                                    <tbody> 
                                                        <tr>

                                                            <td class="info_der"><?php echo lang('global.paginas') ?></td>


                                                            <td><?php echo $total_PagLib=$produccion['0']->total_libretos*$producciones['0']->paginasPorLibretos ?></td>
                                                            <td><?php if ($producciones['0']->paginasPorLibretos) {echo $producciones['0']->paginasPorLibretos;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <!--  <tr>
                                                            <td class="info_der">No.PAGINAS</td>
                                                            <td><?php echo $total_PagLib=$produccion['0']->total_libretos*((round($total__pagina_libreto/$produccion['0']->total_libretos,2))) ?></td>
                                                            <td><?php if ($total__pagina_libreto) {echo (round($total__pagina_libreto/$produccion['0']->total_libretos,2));}else{echo "0";} ?></td>
                                                        </tr> -->
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>  

                                                        <tr>
                                                            <td class="info_der">No. <?php echo lang('global.de_escenas_por_libreto') ?></td>
                                                            <td><?php echo $total_escenas=$produccion['0']->total_libretos*$produccion['0']->escenas_libretos ?></td>
                                                            <td><?php if ($producciones['0']->escenas_libretos) {echo $producciones['0']->escenas_libretos;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der">No. <?php echo lang('global.de_escenas_por_desglose') ?></td>
                                                            <td><?php echo $total_escenas=$produccion['0']->total_libretos*$produccion['0']->escenas_libretos ?></td>
                                                            <td><?php if ($producciones['0']->escenas_libretos) {echo $producciones['0']->escenas_libretos;}else{echo "0";} ?></td>
                                                                    
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr> 
                                                        <tr>
                                                            <?php $tiempo=(($producciones['0']->minuto_capitulo*60)+$producciones['0']->segundos_capitulo); $tiemp=($tiempo/60); ?>
                                                            <td class="info_der">No. <?php echo lang('global.tiempo_lectura') ?></td>
                                                            <td><?php echo $total_TiemLecLibr=$produccion['0']->total_libretos* round($tiemp) ?></td> 
                                                            <td><?= $producciones['0']->minuto_capitulo ?>:<?= $producciones['0']->segundos_capitulo ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>                                          
                                                        <tr>
                                                            <td class="info_der">%<?php echo lang('global.exterior') ?></td>
                                                            <td><?php echo $total_exterior=$produccion['0']->total_libretos*$producciones['0']->produccion_exterior ?></td>
                                                            <td><?php if ($producciones['0']->produccion_exterior) {echo $producciones['0']->produccion_exterior;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der">%<?php echo lang('global.locacionUpper') ?></td>
                                                            <td><?php echo $total_locacion=$produccion['0']->total_libretos*$producciones['0']->locacion ?></td>
                                                            <td><?php if ($producciones['0']->locacion) {echo $producciones['0']->locacion;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.estudio') ?></td>
                                                            <td><?php echo $total_Estu=$produccion['0']->total_libretos*$producciones['0']->estudio ?></td>
                                                            <td><?php if ($producciones['0']->estudio) {echo $producciones['0']->estudio;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der">%<?php echo lang('global.noche') ?></td>
                                                            <td><?php echo $total_noche=$produccion['0']->total_libretos*$producciones['0']->noche ?></td>
                                                            <td><?php if ($producciones['0']->noche) {echo $producciones['0']->noche;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.max_locaciones') ?></td>
                                                            <td><?php echo $total_MaxLoca=$produccion['0']->total_libretos*$producciones['0']->numero_locaciones ?></td>
                                                            <td><?php if ($producciones['0']->numero_locaciones) {echo $producciones['0']->numero_locaciones;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.max_sets') ?></td>
                                                            <td><?php echo $total_MaxSets=$produccion['0']->total_libretos*$producciones['0']->numero_set ?></td>
                                                            <td><?php if ($producciones['0']->numero_set) {echo $producciones['0']->numero_set;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr> 
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.locaciones_nuevas') ?></td>
                                                            <td><?php echo $total_evePeque=$produccion['0']->total_libretos*$producciones['0']->locaciones_nuevas ?></td>
                                                            <td><?php if ($producciones['0']->locaciones_nuevas) {echo $producciones['0']->locaciones_nuevas;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.vehiculosUpper') ?></td>
                                                            <td><?php echo $total_evePeque=$produccion['0']->total_libretos*$produccion['0']->numero_vehiculos ?></td>
                                                            <td><?php if ($produccion['0']->numero_vehiculos) {echo $produccion['0']->numero_vehiculos;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.eventos_pequenosUpper') ?></td>
                                                            <td><?php echo $total_evePeque=$produccion['0']->total_libretos*$produccion['0']->evento_pequeno ?></td>
                                                            <td><?php if ($producciones['0']->evento_pequeno) {echo $producciones['0']->evento_pequeno;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.eventos_medianosUpper') ?></td>
                                                            <td><?php echo $total_eveMed=$produccion['0']->total_libretos*$produccion['0']->evento_mediano ?></td>
                                                            <td><?php if ($producciones['0']->evento_mediano) {echo $producciones['0']->evento_mediano;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="info_der"><?php echo lang('global.eventos_grandesUpper') ?></td>
                                                            <td><?php echo $total_eveGran=$produccion['0']->total_libretos*$produccion['0']->evento_grande ?></td>
                                                            <td><?php if ($producciones['0']->evento_grande) {echo $producciones['0']->evento_grande;}else{echo "0";} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><?php echo lang('global.diferencia') ?></td>
                                                        </tr>                                                                                                                                                
                                                         
                                                                                                      
                                                    </tbody>  
                                                </table>
                                            </div>


                                        <div class="right_table_fix movible">
                                            <table class="tab_mov">
                                                <tr>
                                                    <td class="nfd">
                                                        <table class="det_proyectados">
                                                            <thead>
                                                                <tr>
                                                                    <? foreach ($capitulos_detallados as $capitulo_detallado2) { ?>
                                                                        <td><?=$capitulo_detallado2['numero']?></td>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody><?php $class="style='color:red'"; ?>
                                                                <tr><?=$cadena_pagina_libreto ; ?></tr>
                                                                <tr><?=$cadena_pagina_libreto_diferencia ; ?></tr> 
                                                                <tr><?=$cadena_escenas_numero?></tr> 
                                                                <tr><?=$cadena_escenas_numero_diferencia?></tr>
                                                                <tr><?=$cadena_escenas2?></tr>  

                                                                <tr><?=$cadena_escenas2_numero_diferencia?></tr>  

                                                                <tr><?=$cadena_capitulox ; ?></tr>
                                                                <tr><?=$cadena_capitulox_diferencia ; ?></tr>  
                                                                <tr ><?=$cadena_escenas_diferencia; ?></tr>
                                                                <tr <?php if($produccion['0']->produccion_exterior<$total_exterior){ echo ""; } ?> >
                                                                    <?=$cadena_exterior?>
                                                                </tr>
                                                                <tr ><?=$cadena_exterior_diferencia ; ?></tr>
                                                                <tr <?php if($produccion['0']->locacion<$total_locacion){ echo ""; }?> >
                                                                    <?=$cadena_locacion?>
                                                                </tr>
                                                                <tr><?=$cadena_escenas_estudio ; ?></tr>
                                                                <tr><?=$cadena_escenas_estudio_diferencia ; ?></tr>
                                                                <tr><?=$cadena_locacion_diferencia ; ?></tr>
                                                                <tr <?php if($produccion['0']->noche<$total_noche){ echo ""; } ?> >
                                                                    <?=$cadena_noche?>
                                                                </tr>
                                                                <tr ><?=$cadena_locaciones ?></tr>
                                                                <tr><?=$cadena_locaciones_diferencia; ?></tr>
                                                                <tr><?=$cadena_sets?></tr>
                                                                <tr><?=$cadena_sets_diferencia ; ?></tr> 
                                                                <tr><?=$cadena_locaciones_nuevas_max; ?></tr> 
                                                                <tr><?=$cadena_locaciones_nuevas_max_diferencia; ?></tr>      
                                                                <tr><?=$cadena_vehiculos_max?></tr>
                                                                <tr><?=$cadena_vehiculos_max_diferencia?></tr>
                                                                <tr><?=$cadena_eventos_p?></tr>
                                                                <tr><?=$cadena_eventos_p_diferencia; ?></tr>
                                                                <tr><?=$cadena_eventos_m?></tr>
                                                                <tr><?=$cadena_eventos_m_diferencia ; ?></tr>
                                                                <tr><?=$cadena_eventos_g?></tr>
                                                                <tr><?=$cadena_eventos_g_diferencia; ?></tr>   

                                                                                                                                                                                
                                                                                                                                                                                        
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="nfd">
                                                        <table class="det_proyectados det_pro_final">
                                                            <thead>
                                                                <tr>

                                                                    <td><?php echo lang('global.promedio') ?></td>  

                                                                    <td>Total</td> 
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><b><?=round($total__pagina_libreto/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total__pagina_libreto?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr>
                                                                    <td <?php if($total_escenas_produccion<$total_escenas){ echo ""; } ?> ><b><?=$total_escenas/$produccion['0']->total_libretos?></b></td>
                                                                    <td <?php if($total_escenas_produccion<$total_escenas){ echo ""; } ?> ><b><?=$total_escenas?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr>
                                                                    <td><b><?=round($total_escenas_numero/$produccion['0']->total_libretos)?></b></td>
                                                                    <td><b><?=$total_escenas_numero ?></b></td>
                                                                </tr> 
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr>
                                                                    <td><b><?=round($min/70).":".round($seg/70)?></b></td>
                                                                    <td><b><?=$total__capitulox?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr <?php if($produccion['0']->produccion_exterior<$total_exterior){ echo ""; } ?> >
                                                                    <td><b><?=round($total_exterior/$produccion['0']->total_libretos,2)?>%</b></td>
                                                                    <td><b><?=round($total_exterior,2)?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr <?php if($produccion['0']->locacion<$total_locacion){ echo ""; } ?> >
                                                                    <td><b><?=round($total_locacion/$produccion['0']->total_libretos,2)?>%</b></td>
                                                                    <td><b><?=round($total_locacion,2)?></b></td>
                                                                </tr>  
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>                                                              
                                                                <tr>
                                                                    <td><b><?=round($total__escenas_estudio/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total__escenas_estudio?></b></td>
                                                                </tr>                                                                
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr <?php if($produccion['0']->noche<$total_noche){ echo ""; } ?> >
                                                                    <td><b><?=round($total_noche/$produccion['0']->total_libretos,2)?>%</b></td>
                                                                    <td><b><?=round($total_noche,2)?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr>
                                                                    <td><b><?=round($total_locaciones/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_locaciones?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr>
                                                                    <td><b><?=round($total_sets/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_sets?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr>
                                                                    <td><b><?=round($total_locaciones_nuevas/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_locaciones_nuevas?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr>
                                                                    <td><b><?=round($total_vehiculos/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_vehiculos?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr> 
                                                                <tr>
                                                                    <td><b><?=round($total_eventos_p/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_eventos_p?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr>
                                                                    <td><b><?=round($total_eventos_m/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_eventos_m?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>
                                                                <tr>
                                                                    <td><b><?=round($total_eventos_g/$produccion['0']->total_libretos,2)?></b></td>
                                                                    <td><b><?=$total_eventos_g?></b></td>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="3"><?php echo lang('global.diferencia') ?></td>

                                                                </tr>                                                                                          
                                                                                                                           
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>




                                        <!-- <div class="left_table_fix">
                                            <table class="det_proyectados det_pro_final">
                                                <thead>
                                                    <tr>
                                                        <td>Promedio</td>  
                                                        <td>Total</td> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td <?php if($total_escenas_produccion<$total_escenas){ echo ""; } ?> ><b><?=$total_escenas/$produccion['0']->total_libretos?></b></td>
                                                        <td <?php if($total_escenas_produccion<$total_escenas){ echo "class='td_negativo'"; } ?> ><b><?=$total_escenas?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr <?php if($produccion['0']->produccion_exterior<$total_exterior){ echo "class='td_negativo'"; } ?> >
                                                        <td><b>%<?=round($total_exterior/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b>%<?=round($total_exterior,2)?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr <?php if($produccion['0']->locacion<$total_locacion){ echo "class='td_negativo'"; } ?> >
                                                        <td><b>%<?=round($total_locacion/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b>%<?=round($total_locacion,2)?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr <?php if($produccion['0']->noche<$total_noche){ echo "class='td_negativo'"; } ?> >
                                                        <td><b>%<?=round($total_noche/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b>%<?=round($total_noche,2)?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total_eventos_p/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total_eventos_p?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total_eventos_m/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total_eventos_m?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total_eventos_g/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total_eventos_g?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total_locaciones/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total_locaciones?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total_sets/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total_sets?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>                                
                                                    <tr>
                                                        <td><b><?=round($min/70).":".round($seg/70)?></b></td>
                                                        <td><b><?=$total__capitulox?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total__escenas_estudio/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total__escenas_estudio?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($total__pagina_libreto/$produccion['0']->total_libretos,2)?></b></td>
                                                        <td><b><?=$total__pagina_libreto?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>-</b></td>
                                                        <td><b>-</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?=round($min/70).":".round($seg/70)?></b></td>
                                                        <td><b><?=$total__capitulox?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">diferencia</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> -->


                                        <?php } ?>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </li>
                <?php } ?> 

       
                    <?php if($widgets_usuario[$xl]=="presupuesto"){ ++$xl; ?>
                          <!--?php if($produccion['0']->tipo_produccion==2){?-->
                            <li class="widget_dashboard " data-valor="presupuesto">
                                <div data-role="collapsible" data-corners="false">
                                    <h3 class="liquidacion"><?php echo lang('global.proyeccion_nomina_de_actores') ?> <span class="iconDrag"></span></h3>
                                    <div>
                                        <div class="row">
                                            <div class="columns twelve">
                                                <div class="columns four" id="presupuestoPrincipales"></div>
                                                <div class="columns four" id="presupuestoReparto"></div>
                                                <div class="columns four" id="presupuestoFigurantes"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="columns twelve">
                                                <div class="columns twelve" id="liquidacion_mensual"></div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </li>
                         <!--?php } ?--> 
                    <?php } ?> 

            <?php } ?> 
        </ul>   
    </div>
</div>