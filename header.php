	<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <!-- <meta name="viewport" content="width=device-width" /> -->

  <title>RTI Producciones</title>
  
  
  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/foundation.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/app.css">

	<!-- Included JS Files (Compressed) -->
	<script src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script src="<?php echo base_url(); ?>js/foundation.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery.validate.js" type="text/javascript"></script>
	<!-- Initialize JS Plugins -->
	<script src="<?php echo base_url(); ?>js/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.pajinate.js"></script>

  <script src="<?php echo base_url(); ?>js/modernizr.foundation.js"></script>
  <script type="text/javascript">
  var site_url="<?php echo base_url() ?>";
  </script>
</head>
<header id="header">
	<div class="row">
		<div class="two columns">
      <?php if($this->session->userdata('id_user_rti') and $this->session->userdata('id_user_rti')!=1){?>
			     <a href="<?php echo base_url('perfil/index'); ?>" id="logo">RTI Producciones</a>
      <?php } else { ?>
           <a href="<?php echo base_url('produccion/index'); ?>" id="logo">RTI Producciones</a>
      <?php }?>
		</div> 
		<div class="ten columns">
			<?php if($this->session->userdata('id_user_rti')){ ?>
			<div class="rfloat userProfileNav">
				<h2>Bienvenido: <span class="user_name"><?php echo  $this->session->userdata('nombre_user_rti')." ".$this->session->userdata('apellido_user_rti'); ?></span> </h2>
        <ul class="role_list">
					<li><?php echo anchor('entrada/login_out','CERRAR SESIÓN ','class="button gray"'); ?></li>
					<?php $tipo_user_rti=$this->session->userdata('tipo_user_rti');?>
      				<?php if($tipo_user_rti!=1){ ?>
            		<?php $id=$this->session->userdata('id_user_rti'); ?>
      				<li><?php echo anchor('perfil/ver_datos/'.$id,'MI PERFIL ','class="button blue""'); ?></li>
					<?php } ?>
					<?php if($tipo_user_rti!=1){ ?>
	            		<?php $id=$this->session->userdata('id_user_rti'); ?>
	            		<li>
	            			<a href="<?php echo base_url(); ?>perfil/index" class="button light" id="bt_menu"><img src="<?php echo base_url(); ?>/images/icon_home.png" alt=""></a></li>
					<?php } ?>
					<?php if($tipo_user_rti!=2){ ?>
	            		<?php $id=$this->session->userdata('id_user_rti'); ?>
	            		<li><a href="#" class="button light" id="bt_menu"><img src="<?php echo base_url(); ?>/images/icon_menu.png" alt=""></a>
	      					<div class="menu_nav radius_8">
			      				<ul>
									<li><?php echo anchor('produccion/index','Producciones'); ?></li>
									<li><?php echo anchor('registro/index','Usuarios'); ?></li>
	                				<li><?php echo anchor('produccion/password','Contraseña Predeterminada'); ?></li>
								</ul>
							</div>
						</li>
					<?php } ?>
					
				</ul>
			</div>	
			<?php } ?>
		</div>
  </header>
<body>