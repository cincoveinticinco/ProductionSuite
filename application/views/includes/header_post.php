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
  <!-- Fancy Box CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>js/continuidad/jquery.fancybox.css?v=2.1.5" />
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

  <script src="<?php echo base_url()?>js/script_post.js"></script>
  <!-- the mousewheel plugin - optional to provide mousewheel support -->

  <script type="text/javascript" src="<?php echo base_url()?>js/continuidad/jquery.fancybox.js?v=2.1.5"></script>
    

  

  <!-- Site Url VAR -->
  <script type="text/javascript">
    //var site_url = "http://apps.rtitv.com/production_suite";
    var site_url = "<?=base_url()?>";
  </script>
  <!-- App JS -->

</head>

<body>
  <div id="wrapper">
    <div id="top_header">
     Bienvenido: <?php echo $this->session->userdata('correo_pruduction_suite') ?>/ <a href="#">Editar mis datos</a> / <?php echo anchor('login/disconnecUser','Cerrar sesión') ?>
    </div>
    <header id="header" class="row">
      <div id="logo" class="logo_production">
        <?php echo anchor('produccion/producciones','Production Suite',array('title'=>'Production Suite')); ?>
      </div>
      <?php $this->load->view('includes/partials/top_nav_post'); ?>
      <div class="right login_content">
        
      </div>
    </header>