;(function ($, window, undefined) {
  'use strict';

  var $doc = $(document),
      Modernizr = window.Modernizr;

  $(document).ready(function() {

     
  $('#registerForm select').show()
  $('#list_cap select').show()

  $('#myfile').change(function(){
    $('#path').val($(this).val());
  });

    $.fn.foundationAlerts           ? $doc.foundationAlerts() : null;
    $.fn.foundationButtons          ? $doc.foundationButtons() : null;
    $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
    $.fn.foundationNavigation       ? $doc.foundationNavigation() : null;
    $.fn.foundationTopBar           ? $doc.foundationTopBar() : null;
    $.fn.foundationCustomForms? $doc.foundationCustomForms() : null;
    $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
    $.fn.foundationTabs             ? $doc.foundationTabs({callback : $.foundation.customForms.appendCustomMarkup}) : null;
    $.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
    $.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
    $.fn.foundationClearing         ? $doc.foundationClearing() : null;

    $.fn.placeholder                ? $('input, textarea').placeholder() : null;


    var window_width = $(window).width();
    var window_height = $(window).height();

    /* === Validate Forms === */
    $('form.custom').validate();
    $('form.validate').validate();
    $('#crear_escena').validate();
    $('#editar_escena').validate();
    $('#register_form').validate();
    $('#carga_archivo').validate({ ignore: []});
    /* === Hide button - Open Sidebar === */
    $('.open_sidebar').fadeOut();

    /* === Open and Close - Sidebar === */
    $('#open_sidebar').click(function() {
      $('#sidebar').animate({
        width: '220px',
      }, 600);
      $(this).fadeOut(50);
      $('#close_icon').fadeIn(50);
      $('#side_menu').fadeIn(600);
      $('#content').animate({
        'margin-left': '218px',
      }, 600);
      
    });
    $('#close_icon').click(function() {
      $('#sidebar').animate({
        width: '35px',
      }, 600);
      $(this).delay(600).fadeOut(10);
      $('#open_sidebar').delay(600).fadeIn(50);
      $('#side_menu').fadeOut(600);
      $('#content').animate({
        'margin-left': '33px',
      }, 600);
      
    });
 
    /* === Actions - Sidebar === */
    $('#side_menu ul li').click(function(){
        $(this).find("ul.submenu").stop().slideToggle('slow');
        $(this).parent().find("ul.submenu").not($(this).find("ul.submenu")).stop().slideUp('slow');
        return true;
    });

    /* === Add color transparent - Menu items top === */
    $('#top_menu ul li a').css('color','transparent');

    /* === Open and close - Menu items top === */


    if (window_width >= 1024) {
      $('#open_menu a').click(function() {
        $(this).find('span.icon').fadeOut();
        $(this).find('span.icon_close').fadeIn();

        $('#top_menu ul li a').not(this).animate({
          padding: '8px 10px 8px 45px',
          textIndent: '1px',
          width: '175px',
          color: '#fff'
        }, 300,'linear',function(){
          $('#top_menu ul li a').css('color','white');
        });
        return false;
      });
      $('#open_menu a .icon_close').click(function() {
        $(this).fadeOut();
        $(this).parent().find('span.icon').fadeIn();

        $('#top_menu ul li a').not(this).animate({
          padding: '8px 22px',
          textIndent: '-9999px',
          width: '32px',
          color: '#fff'
        }, 300,'linear',function(){
          $('#top_menu ul li a').css('color','white');
        });
        return false;
      });
    } else{
      $('#open_menu a').css('display','none');
    };
    /* === Actions icons: save - print - fullscreen === */

   /* $('.button.icon.icon_save').click(function() {
      $('.box_save').fadeIn('slow');
      return false;
    });*/
    $('.icon.icon_plus').click(function() {
      $('.box_new').fadeIn('slow');
      
    });
    $('.icon.icon_print').click(function() {
      //$('.box_print').fadeIn('slow');
     // return false;
    });
    $('.icon.icon_cancel').click(function() {
      $('.blue_box').fadeOut('slow');
      return false;
    });
    $( ".buttons.full_screen span.open" ).click(function(){
      $(this).fadeOut(600);
      $(".buttons.full_screen span.close").fadeIn(100).css('display', 'block');
      $('#breadcrumbs').fadeOut();
      $('#header').fadeOut().animate({
        height: 0
      });
      $('#sidebar').animate({
        width: 0
      }).fadeOut();
      $('#content').animate({
        'margin-left': '0',
      });
      $('#breadcrumbs').animate({
        height: '0',
      });
      $('ul.accordion').fadeOut();
    });

    $( ".buttons.full_screen span.close" ).click(function(){
      $(this).fadeOut(600);
      $(".buttons.full_screen span.open").fadeIn(100).css('display', 'block');
      $('#breadcrumbs').fadeIn();
      $('#header').fadeIn().animate({
        height: 82
      });
      $('#sidebar').animate({
        width: 220
      }).fadeIn();
      $('#content').animate({
        'margin-left': '218',
      });
      $('#breadcrumbs').animate({
        height: '32',
      });
      $('ul.accordion').fadeIn();
      $('ul.accordion').fadeIn();

      $('#sidebar').animate({
        width: '220px',
      }, 600);
      $('#open_sidebar').fadeOut(1);
      $('#side_menu').fadeIn(600);
      $('#content').animate({
        'margin-left': '218px',
      }, 600,function(){
        $('#close_icon').fadeIn(600);
      });

    });

    /* === Popup Cruce elementos === */
    $('#asignarEscenasWrap .asignarEscenasbox .closeIcon').click(function(){
      $('#asignarEscenasWrap').fadeOut();
    });
    $('#asignarEscenasLink').click(function(){
      $('#capitulos').change();
      var idunidad = $('#unidad_plan').val();
      var fecha  = fechaFormat($("#date_unity"+idunidad).html()); 

      var  fecha_actual= fechaFormat($('#fecha_unidad_plan').val());
      console.log(fechaFormat(fecha));
      console.log(fechaFormat(fecha_actual))

      console.log(Date.parse(fecha)/ 1000)
      console.log(Date.parse(fecha_actual)/ 1000)
      
      if((Date.parse(fecha)/ 1000)<=(Date.parse(fecha_actual)/ 1000)){ 
        $('#asignarEscenasWrap').fadeIn();
      }else{
        $('#fecha_unidad_plan').addClass('error');
      }
      return false;
    });
    /* === Popup Agreagr escenas === */
    $('#cruceWrap .cruce_box .closeIcon').click(function(){
      $('#cruceWrap').fadeOut();
    });
    $('#cruceLink').click(function(){
      $('#cruceWrap').fadeIn();
      return false;
    });

    /* === Popup guardar filtro === */
    $('#save_filter .closeIcon').click(function(){
      $('#wrappOverlay').fadeOut();
      $('#filter_button').click();
      return false;
    });
    $('#filterSave_button').click(function(){
      $('#wrappOverlay').fadeIn();
      return false;
    });


  function fechaFormat(fecha){
    var f=fecha.replace('Ene','Jan'); 
    f=f.replace('Feb','Feb'); 
    f=f.replace('Mar','Mar')
    f=f.replace('Abr','Apr')
    f=f.replace('May','May')
    f=f.replace('Jun','Jun')
    f=f.replace('Jul','Jul')
    f=f.replace('Ago','Aug')
    f=f.replace('Sep','Sep')
    f=f.replace('Oct','Oct')
    f=f.replace('Nov','Nov')
    f=f.replace('Dic','Dec')

    f=f.replace('ene','Jan');
    f=f.replace('feb','Feb'); 
    f=f.replace('mar','Mar')
    f=f.replace('abr','Apr')
    f=f.replace('may','May')
    f=f.replace('jun','Jun')
    f=f.replace('jul','Jul')
    f=f.replace('ago','Aug')
    f=f.replace('sep','Sep')
    f=f.replace('oct','Oct')
    f=f.replace('nov','Nov')
    f=f.replace('dic','Dec')
     
     return f;
    //echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
  }

    /* === Datepickers === */

    $( "#date_1, #date_2" ).datepicker({
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      dateFormat: 'dd-M-yy',
      showOn: "button",
      buttonImage: site_url2+"/images/calendar_white.png",
      buttonImageOnly: true,
      onSelect: function(){
        $(this).focus();
      }
    });


    var dateList_bloqueado = new Array();
    $('#fechas_bloqueadas_trabajo').ready(function(){
      var pathname = window.location.pathname;  
      if(pathname.search("plan_diario/")>0){
        if($('#fechas_bloqueadas_trabajo').val()!=""){
        var cadena = $('#fechas_bloqueadas_trabajo').val().split(",");
        //var cadena='';
        for (var i = 0; i <=cadena.length; i++) {
          dateList_bloqueado.push(cadena[i]);
        };
      }
      }
    });



    $("#fecha_unidad_mover" ).datepicker({
      dateFormat: 'dd-M-yy',
      showOn: "button",
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      buttonImage: site_url2+"/images/calendar_white.png",
      buttonImageOnly: true,
      beforeShowDay: function (dateToShow) { 
        return [!($.inArray($.datepicker.formatDate('yymmdd', dateToShow),dateList_bloqueado) >= 0), ""]; 
      },
      onSelect: function(){
        $('#fecha_unidad_plan').removeClass('error');
        $(this).focus();
      }
    });
    $( "#date_online, #date_start1,#date_start2,#date_start3,#date_start4,#date_start5" ).datepicker({
       dateFormat: 'dd-M-yy',
      showOn: "button",
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
       monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onSelect: function(){
        $(this).focus();
      }
    });


    

    $("#fecha_unidad_plan" ).datepicker({
      dateFormat: 'dd-M-yy',
      showOn: "button",
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      buttonImage: site_url2+"/images/calendar_white.png",
      buttonImageOnly: true,
      beforeShowDay: function (dateToShow) {
        return [!($.inArray($.datepicker.formatDate('yymmdd', dateToShow),dateList) >= 0), ""]; 
      },
      onSelect: function(){
        $('#fecha_unidad_plan').removeClass('error');
        $(this).focus();
      }
    });
    $( "#date_online, #date_start1,#date_start2,#date_start3,#date_start4,#date_start5" ).datepicker({
      dateFormat: 'dd-M-yy',
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onSelect: function(){
        $(this).focus();
      }
    });
    
   $( "#start_pre" ).datepicker({
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      dateFormat: 'dd-M-yy',
      /*defaultDate: "+1w",*/ 
      changeMonth: true,
      numberOfMonths: 2,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onClose: function( selectedDate ) {
          $( "#start_recording" ).datepicker( "option", "minDate", selectedDate );  
      },
      onSelect: function(){
        $(this).focus();
      }
    });

    $( "#start_pre" ).change(function(){
       var i=$("#start_pre" ).val();
        if(i){
        }else{
          $( "#start_recording" ).datepicker( "option", "minDate", new Date(2013, 8 - 1, 15) );
        }
    });

/*CAPOS DE FEHCA HERRAMIENTAS*/
$("#date_nomina_from_pdf").datepicker({
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    dateFormat: 'dd-M-yy',
      /*defaultDate: "+1w",*/
      changeMonth: true,
      numberOfMonths: 1,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onClose: function( selectedDate ) {
        $( "#date_nomina_to_pdf" ).datepicker( "option", "minDate", selectedDate );
      },
      onSelect: function(){
        $(this).focus();
      }
});

$("#date_nomina_to_pdf").datepicker({
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    dateFormat: 'dd-M-yy',
      /*defaultDate: "+1w",*/
      changeMonth: true,
      numberOfMonths: 1,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onClose: function( selectedDate ) {
      },
      onSelect: function(){
        $(this).focus();
      }
});

$( "#date_nomina_from_pdf" ).datepicker( "option", "minDate", $('#min_date').val());
$( "#date_nomina_from_pdf" ).datepicker( "option", "maxDate", $('#max_date').val());
$( "#date_nomina_to_pdf" ).datepicker( "option", "maxDate", $('#min_date').val());
$( "#date_nomina_to_pdf" ).datepicker( "option", "maxDate", $('#max_date').val());
/*FIN CAPOS DE FEHCA HERRAMIENTAS*/



    $( "#start_recording" ).datepicker({
      dateFormat: 'dd-M-yy',
      regional:['es'],
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],

      /*defaultDate: "+1w",*/
      changeMonth: true,
      numberOfMonths: 2,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onClose: function( selectedDate ) {
        $( "#end_recording" ).datepicker( "option", "minDate", selectedDate );
        $( ".start_date" ).datepicker( "option", "minDate", selectedDate );
        var fragmentoTexto = selectedDate.split('-'); 
        var dia = parseInt(fragmentoTexto[0])+1;
        if(dia>31){
          dia=1;
        }
        var fecha = dia + "-" + fragmentoTexto[1] + "-" + fragmentoTexto[2];
        $( "#date_online" ).datepicker( "option", "minDate", fecha );
      },
      onSelect: function(){
        $(this).focus();
      }
    });

    $( "#end_recording" ).datepicker({
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      dateFormat: 'dd-M-yy',
      /*defaultDate: "+1w",*/
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


/*CAMPOS DE FECHA ASIGNACION A PLAN DIARIO*/
var dateList = new Array();
$('#fechas_bloqueadas').ready(function(){
  var pathname = window.location.pathname;  
  if(pathname.search("plan_general/")>0 || pathname.search("plan_diario/")>0){
    if($('#fechas_bloqueadas').val()!=""){
    var cadena = $('#fechas_bloqueadas').val().split(",");
    //var cadena='';
    for (var i = 0; i <=cadena.length; i++) {
      dateList.push(cadena[i]);

    };
  }
  }
});


    $( "#start_plan" ).datepicker({
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
     //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      dateFormat: 'dd-M-yy',
      changeMonth: true,
      numberOfMonths: 1,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      onClose: function( selectedDate ) {
        $( "#end_plan" ).datepicker( "option", "minDate", selectedDate );
      },
      beforeShowDay: function (dateToShow) { 
        return [!($.inArray($.datepicker.formatDate('yymmdd', dateToShow),dateList) >= 0), ""]; 
      },
      onSelect: function(){
        $(this).focus();
      }
    });

    $( "#end_plan" ).datepicker({
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      //monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      dateFormat: 'dd-M-yy',
      /*defaultDate: "+1w",*/
      changeMonth: true,
      numberOfMonths: 1,
      showOn: "button",
      buttonImage: site_url2+"/images/calendar.gif",
      buttonImageOnly: true,
      /*onClose: function( selectedDate ) {
        $( "#end_plan" ).datepicker( "option", "maxDate", selectedDate );
      },*/
      onSelect: function(){
        //$( "#end_plan" ).datepicker( "option", "maxnDate", $('#recording_end').val() );
        $(this).focus();
      }
    });
/*FIN CAMPOS DE FECHA ASIGNACION A PLAN DIARIO*/


    /* === Actions Hitos === */
    $('.edit_hito').click(function(){
      $('#centro_produccion').show();
      $('#type_production').show();
      $('#over_time').show();
      $('#dias_sem').show();
      $('#hitos_form').show();
      $('#hitosInfo').hide();
      centro_produccion
      return false;
    });

    $('.cancel_edit_hito').click(function(){
      $('#hitos_form').hide();
      $('#hitosInfo').show();
      return false;
    });


    $('.edit_indicador').click(function(){
      $('#indicadores_form').show();
      $('#indicadoresInfo').hide();
      return false;
    });
    
    $(".cancelIndicadores").click(function(){
      $("#indicadores_form").hide();
      $("#indicadoresInfo").show();
      return false;
    });
    $(".edit_user_unidad").click(function(){
      $(".content_edit").show();
      $("#infoUnidades").hide();
      return false;
    });
    $(".btn_cancel_unidades").click(function(){
      $(".content_edit").hide();
      $("#infoUnidades").show();
      return false;
    });

    /* === Actions Hitos === */
    // $('.normal_table table tr').click(function(){
    //   $(this).next('.info_element').slideToggle();
    // });

    $('.normal_table').on('click','.diagrama',function(){
      $(this).next('.info_element').slideToggle();
    });

    /* === New set - location === */
    $('#new_item_location').click(function(){
      $(this).parent().parent().find('.column.nine').css({width: '100%'});
      $(this).parent().parent().find('.column.three').css({display: 'none'});
      //$(this).parent().parent().parent().css({width: '25%'});
      $(this).parent().parent().find('.save_tools').hide();
      $('.left_dashed.last.setHiden').css({width: '35%'});
      $('.left_dashed.last.setHiden .nine').css({width: '52%'});
      $('.left_dashed.last.setHiden .three').css({width: '48%'});
      $('.left_dashed.last.setHiden')
      $('.setHiden').fadeIn();
      $('#location').parent('div.combo.sexy').css('display','none');
      $('#new_location').css('display','block');
      $('#add_item_location').css('display','inline-block');
      $('#cancel_item_location').css('display','inline-block');
      $(this).css('display','none');
      $('#location').css('display','none');
      $('#set').css('display','none');
      $('#new_set').css('display','block');
      $('#new_set').val('');
      $('#new_item_set').css('display','none');
      $('select#location').next('label').hide();
      $('#cancel_item_set').hide();
      $('#add_item_set').hide();
      return false;
    });
    $('#new_item_set').click(function(){
      $(this).parent().parent().find('.column.nine').css({width: '55%'});
      $(this).parent().parent().find('.column.three').css({width: '45%'});
      $(this).parent().parent().find('.save_tools').hide();
      $('.left_dashed.last.setHiden').css({width: '44%'});
      $('.left_dashed.last.setHiden').prev().css({width: '25%'});
      $('.left_dashed.last.setHiden').prev().find('.column.nine').css({width: '65%'});
      $('.left_dashed.last.setHiden').prev().find('.column.three').css({width: '35%'});
      $('#set').parent('div.combo.sexy').css('display','none');
      $('#new_set').css('display','block');
      $('#add_item_set').css('display','inline-block');
      $('#cancel_item_set').css('display','inline-block');
      $(this).css('display','none');
      $('#set').css('display','none');
      return false;
    });
    $('#cancel_item_set').click(function(){
      $(this).parent().parent().find('.save_tools').show();
      $('.left_dashed.last.setHiden').css({width: '35%'});
      $('.left_dashed.last.setHiden').prev().css({width: '34%'});
      $('.left_dashed.last.setHiden').prev().find('.nine').css({width: '75%'});
      $('.left_dashed.last.setHiden').prev().find('.three').css({width: '25%',display: 'block'});
      $('.left_dashed.last.setHiden').find('.nine').css({width: '75%'});
      $('.left_dashed.last.setHiden').find('.three').css({width: '25%'});
      $('#new_set').css('display','none');
      $('#add_item_set').css('display','none');
      $('#cancel_item_set').css('display','none');
      $(this).css('display','none');
      $('#new_item_set').show();
      $('select#set').show();
      return false;
    });

    $('#cancel_item_location').click(function(){
      $(this).parent().parent().find('.save_tools').show();
      $('.left_dashed.last.setHiden').css({width: '35%'});
      $('.left_dashed.last.setHiden').prev().css({width: '34%'});
      $('.left_dashed.last.setHiden').prev().find('.nine').css({width: '75%'});
      $('.left_dashed.last.setHiden').prev().find('.three').css({width: '25%',display: 'block'});
      $('.left_dashed.last.setHiden').find('.nine').css({width: '75%'});
      $('.left_dashed.last.setHiden').find('.three').css({width: '25%'});
      $('select#set').show();
      //$('.setHiden').fadeOut();
      $('#new_set').hide();
      $('#new_item_set').show();
      $('.label-error-hiden').hide();
      $('#new_location').css('display','none');
      $('#add_item_location').css('display','none');
      $('#cancel_item_location').css('display','none');
      $(this).css('display','none');
      $('#new_item_location').show();
      $('#location').css('display','block');

      return false;
    });

    /* === Actions Hitos === */
    $('#escenas_table tbody tr.white, #escenas_table tbody tr.gray_light').click(function(){
      $(this).next('.info_escena').slideToggle();
    });

    $('tr.withLink').bind("dblclick", function(){
      if(!$(this).find('.td_black').html()){
        var link = $(this).find('.linkEditar').attr('href');
        window.location = link;
      }
    });

    // call the tablesorter plugin 
    $("#usersTable").tablesorter(); 
    $("#elementosTable").tablesorter(); 
    //$(".elementosTables").tablesorter(); 
    $("#table_general").tablesorter();
    $("#tabla_personajes").tablesorter();  
    $("#planDiarioTableSorter").tablesorter();  
    
    var inc=0;
    $('.colorsBoxLink').click(function(){

      $('.colorsBox').slideToggle();
      if(inc%2==0){
        $(this).css('background','#1D637B');
        $('.arrow').css("background-position-x", "-17px");
      }else{
        $('.arrow').css("background-position-x", "-1px");
        $(this).css('background','#08A0DA');
      }
      ++inc;
      return false;
    });



    //Style selects
    //$("select").sexyCombo();


    //Validacion Inputs porcentajes
    
    $(function() {
        //$( "#rang_prod_amount1" ).val( 50 );
        //$( "#rang_prod_amount2" ).val( 50 );

        
        $( "#rang_prod_amount1" ).change(function(){
          var  val1 = $( "#rang_prod_amount1" ).val();
          if(val1>=100){
            val2=0;
            $( "#rang_prod_amount1" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#rang_prod_amount2" ).val( val2 );
        });

        $( "#rang_prod_amount2" ).change(function(){
          var  val1 = $( "#rang_prod_amount2" ).val();
          var val2 =  100 - val1;
          if(val1>=100){
            val2=0;
            $( "#rang_prod_amount2" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#rang_prod_amount1" ).val( val2 );
        });


        $( "#locacion" ).change(function(){
          var  val1 = $( "#locacion" ).val();
          var val2 =  100 - val1;
          if(val1>=100){
            val2=0;
            $( "#locacion" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#estudio" ).val( val2 );
        });

        $( "#estudio" ).change(function(){
          var  val1 = $( "#estudio" ).val();
          var val2 =  100 - val1;
          if(val1>=100){
            val2=0;
            $( "#estudio" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#locacion" ).val( val2 );
        });


        $( "#dia" ).change(function(){
          var  val1 = $( "#dia" ).val();
          if(val1>=100){
            val2=0;
            $( "#dia" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#noche" ).val( val2 );
        });

        $( "#noche" ).change(function(){
          var  val1 = $( "#noche" ).val();
          var val2 =  100 - val1;
          if(val1>=100){
            val2=0;
            $( "#noche" ).val( 100 );
          }else{
            var val2 =  100 - val1;
          }
          $( "#dia" ).val( val2 );
        });



        $( ".indicadores" ).change(function(){
          var val1 = parseInt($( "#rang_cap_1" ).val());
          var val2 = parseInt($( "#rang_cap_2" ).val());
          var val3 = parseInt($( "#rang_cap_3" ).val());
          var total=val1+val2+val3;
          if(total>100){
            $(this).val('0');
            alert('El total de los valores esta sumando es mayor a 100%');

          }
         
        });


       // $( "#rang_cap_1" ).val( 33 );
        //$( "#rang_cap_2" ).val( 33 );
        //$( "#rang_cap_3" ).val( 33 );

      //$('.open_box').on('click',function(){
        $('.table_general').on('click','.open_box, .box',function(){
          $(this).next('.hide_box').stop().fadeIn();
          $(this).parent().find('.hide_box').css("opacity", "");
          $(this).parent('td').css({'background':'#2aabe2','font-weight':'bolder'});
          
          $('.open_box, .box').not($(this)).css({'color':'#0098d2'});
          $(this).css({'color':'white'});
          $('.hide_box').not($(this).next('.hide_box')).fadeOut();
          
          $('.table_general table.secondary tr td').not($(this).parent('td')).css({'background':'transparent'});
          $('.value').focus();
          $('.hide_box textarea').focus();
          $('.hide_box input').focus();
          return false;
      });

      $('.close_box').on('click',function(){
        hide_box_blue();
        return false;
      });
      function hide_box_blue(){
        $('.hide_box').stop().fadeOut();
        $('.table_general table table.secondary tr td').css({'background':'transparent','font-weight':'normal'});
        $('.open_box, .box').css({'color':'#0098d2'});
      }

    
      $('.open_boxWrap').on('click',function(){
        $(this).next().fadeIn();
        $(this).next().find('.hide_box').fadeIn();

      });

      $('.real_time').on('click',function(){
        $('.hide_box :input:first').focus();
      });


        
      $('.help_button').click(function(){
        $('#help_wrapper').stop().fadeIn();;
      });
      $('#help_wrapper span.close_box').click(function(){
        $('#help_wrapper').stop().fadeOut();;
      });
        
      });

        
      $('form.custom').submit(function(){
        if ($('select.required').val() == ""){
          $('select.required').next('input').addClass('error'); 
          $('select.required').parent('.combo.sexy').append('<label class="error">Este campo es requerido.  </label>');
          $('select.required').parent('.combo.sexy').find('label.error').css('padding-top','32px');
          return false;
        }else{
          $('select.required').next('input').removeClass('error'); 
          $('select.required').parent('.combo.sexy').find('label.error').css('display','none');
          return true;
        }

      });

      $('.categorias_elemento li.categoria input').click(function(){
        $(this).addClass('active');
        $('.categorias_elemento li.categoria input').removeClass('active');
      });
      
      $( "#itemsEnable, #itemsDisable" ).sortable({
        connectWith: ".connectedSortable",
      }).disableSelection();

      $("#itemsDisable").sortable({
        receive: function( event, ui ) {
        var newItem= $(ui.item.context).attr("class");
           if(newItem == "horizontal_sort"){
            $(ui.sender).sortable('cancel');
            return false;
           }
        }
      });

      //$( ".horizontal_sort" ).sortable({ axis: "y", containment: "#itemsEnable", scroll: false });
      $( "#itemsDisable" ).sortable( "option", "appendTo" );

      $('#ordenarPlanLink').click(function(){
        $('#ordenarWrap').fadeIn();
        return false;
      });
      $('#ordenarWrap .ordenarBox .closeIcon').click(function(){
        $('#ordenarWrap').fadeOut();
        return false;
      });
      
      $('.ordenarEsceneas').click(function(){
        $(this).hide();
        $('#planDiarioTableSorter').addClass('planDiarioTable');
        $('#planDiarioTableSorter tbody tr').removeClass('disabledItem');
        $.getScript(site_url2+"/js/script.js");
        $('.cancelOrdenEsceneas').show();
        $('.hide_box').css('visibility','hidden');
        $('#temporal_script').html("");
        $( "#planDiarioTableSorter tbody" ).selectable( "destroy");
        $( "#planDiarioTableSorter tr" ).each(function() {
          $(this).removeClass('ui-selected');
        });
        return false;
      });
      $('.cancelOrdenEsceneas').click(function(){
        $(this).hide();
        $('#order_button').hide();
        $('#planDiarioTableSorter').removeClass('planDiarioTable');
        $('#planDiarioTableSorter tbody tr').addClass('disabledItem');
        $.getScript(site_url2+"/js/script.js");
        $('.ordenarEsceneas').show();
        $('.hide_box').css('visibility','visible');
        $('#temporal_script').html('<script type="text/javascript"> $(document).ready(function() { $( "#planDiarioTableSorter tbody" ).selectable({ distance: 1, selected: mostrarBotonPlanDiario });  $("#planDiarioTableSorter").on("click","tr.actionAsing", function(){ $(this).toggleClass("ui-selected"); var itemsSelected = $("#planDiarioTableSorter tr.ui-selected").size(); if (itemsSelected == 0) { ocultarBotonPlanDiario(); }else{ mostrarBotonPlanDiario(); }; }); }); </script>');
        return false;
      });

      $('.open_boxWrap').click(function(){
        $('#scroll.scrollPlanDiario').css('padding-bottom','120px');
      });

      $('.open_box').click(function(){
        $('#scroll.scrollLibretos').css('padding-bottom','120px');
      });
      $('span.close_box').click(function(){
        $('#scroll.scrollPlanDiario').css('padding-bottom','0px');
        $('#scroll.scrollLibretos').css('padding-bottom','0px');
      });
  $('#registerForm select').show()
   $('#list_cap').show()

   $('#wrapper select').show()



  });

  // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
  // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
  // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
  // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
  // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

  // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
  if (Modernizr.touch && !window.location.hash) {
    $(window).load(function () {
      setTimeout(function () {
        window.scrollTo(0, 1);
      }, 0);
    });
  }

})(jQuery, this);