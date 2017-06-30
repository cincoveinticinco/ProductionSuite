<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Production Suite - RTI</title>
  
  <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.png">
  <!-- ======================================================
  Included CSS Files 
  ======================================================= -->

  <!-- Foundation CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/foundation.min.css">
  <!-- jQuery UI CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/production-suite/jquery-ui-1.10.3.custom.css">
  <!-- Sexy Combo CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/sexy/sexy-combo.css" />
  <!-- ScrollPane CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.jscrollpane.css" media="all" />
  <!-- App Style CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/app.css">


  <!-- ======================================================
  Included JS Files 
  ======================================================= -->

  <!-- Modernizer Foundation JS -->
  <script src="<?php echo base_url(); ?>js/modernizr.foundation.js"></script>
  <!-- Foundation JS -->
  <script src="<?php echo base_url(); ?>js/foundation.min.js"></script>
  <!-- jQuery UI JS -->
  <script src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.custom.js"></script>
  <!-- the mousewheel plugin - optional to provide mousewheel support -->
  <script src="<?php echo base_url(); ?>js/jquery.mousewheel.js"></script>
  <!-- jQuery Validate JS -->
  <script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
  <!-- jQuery Sexy Combo JS -->
  <script src="<?php echo base_url(); ?>js/jquery.sexy-combo.js"></script>
  <!-- jQuery Table sorter JS -->
  <script src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script>
  <!-- jQuery ScrollPane JS -->
  <script src="<?php echo base_url(); ?>js/jquery.jscrollpane.min.js"></script>
  <!-- jQuery Pajinate JS -->
  <script src="<?php echo base_url(); ?>js/jquery.pajinate.js"></script>
  <!-- jQuery Highcharts JS -->
  <script src="<?php echo base_url()?>js/highcharts.js"></script>

  <script src="<?php echo base_url(); ?>js/timepicker.js"></script>

  <script src="https://www.google.com/jsapi"></script>

  <!-- Site Url VAR -->
  <script type="text/javascript">
    //var site_url = "http://apps.rtitv.com/production_suite";
    var site_url = "http://localhost/production_suite";
  </script>
  <!-- App JS -->
  <script src="<?php echo base_url(); ?>js/app.js"></script>
  <script src="<?php echo base_url(); ?>js/script.js"></script>
  <script src="<?php echo base_url(); ?>js/script-2.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){
    $('#wrapper_loader').fadeOut();
  });
  </script>

  <!-- ======================================================
  LOGIN 
  ======================================================= -->
<h1>Resultados</h1>
<label>Registros leidos</label><br>
 <?php foreach ($datos_final as $l) { 
       $tiempo=explode(':',$l['tiempo']);
       if($tiempo[3]<=15){
        $tiempo[2]=$tiempo[2]+1;
       }
       if($tiempo[2]<10){
        $tiempo[2]='0'.$tiempo[2];
       }
      echo 'Libretos: '.$l['Libreto'].' Escena: '.$l['escena'].' Tiempo: '.$l['tiempo'].' Tiempo redondedeado: '.$tiempo[0].':'.$tiempo[1].':'.$tiempo[2].' Numero de clips: '.$l['numero_escena'].'<br>';
    } ?>
    <br>
    <label>Registros leidos varios</label><br>
 <?php foreach ($datos_final_varias as $l) { 
      $tiempo=explode(':',$l['tiempo']);
       if($tiempo[3]<=15){
        $tiempo[2]=$tiempo[2]+1;
       }
       if($tiempo[2]<10){
        $tiempo[2]='0'.$tiempo[2];
       }
      echo 'Libretos: '.$l['Libreto'].' Tiempo: '.$l['tiempo'].' Tiempo redondedeado: '.$tiempo[0].':'.$tiempo[1].':'.$tiempo[2].' Numero de clips: '.$l['numero_escena'].'<br>';
    } ?>
    <br>
    <label>-----------valores Cred---------</label>
    <?php if($datos_cred){?>
           <?php foreach ($datos_cred as $d) {
              echo 'Creditos '.$d['creditos'].' Tiempo: '.$d['tiempo'].'<br>';
           } ?>
    <?php } ?>
    <br>
    <label>Total CRED</label>
    <?php echo $total_creditos ?>
    ---------------------<br>
    <label> No leidos por errores<br> </label>
    <label><?php echo 'Numero de registro nos leidos '.count($no_leidos).'<br>';?></label>
   <?php foreach ($no_leidos as $n) {
      echo 'Campo no leido '.$n['no_leidos']['libreto'].' '.$n['no_leidos']['tiempo1'].' '.$n['no_leidos']['tiempo2'].'<br>';
    } ?>
    <footer id="footer">
      <p class="copy">Todos los derechos reservdos. Producciones RTITV ® 2013</p>
    </footer>
  </div>
</body>
</html>
