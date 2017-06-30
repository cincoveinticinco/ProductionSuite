<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DashboardAPP - Producion Suite RTI</title>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.png">
	<link rel="stylesheet"  href="<?php echo base_url()?>css/themes/dashboard_app/jquery.mobile-1.2.1.css" />
	<link rel="stylesheet"  href="<?php echo base_url()?>css/jquery-ui-1.10.3.custom.min.css" />
	<link rel="stylesheet"  href="<?php echo base_url()?>css/foundation.css" />
	<link rel="stylesheet" href="<?php echo base_url()?>css/style_app.css" />
	<script src="<?php echo base_url()?>js/app/jquery.js"></script>
  	<script src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.custom.js"></script>
	<script src="<?php echo base_url()?>js/app/jquery.mobile-1.2.1.js"></script>
	<script src="<?php echo base_url()?>js/highcharts.js"></script>
	<script src="<?php echo base_url()?>js/kinetic-v5.1.0.min.js"></script>
	<script src="<?php echo base_url()?>js/dount_custom.js"></script>
	
	<!--script src="<?php echo base_url()?>js/foundation.min.js"> </script-->

<?php /* ?>
	<script src="http://code.highcharts.com/highcharts-more.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
<?php */ ?>	
	<script src="https://www.google.com/jsapi"></script>
	<script src="<?php echo base_url()?>js/canvas2image.js"></script>
	<script src="<?php echo base_url()?>js/html2canvas.js"></script>
	<script src="<?php echo base_url()?>js/script_app.js"></script>
    <script src="<?php echo base_url()?>js/modules/exporting.js"></script>
    <script src="<?php echo base_url()?>js/jquery.knob.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>/js/jquery.dataTables.js"></script>
	 <?php $idioma = $this->lang->lang().'/'; ?>
 
    <script type="text/javascript">
    var site_url = "<?php echo base_url().$idioma ?>";
    //var site_url = "http://productionsuite.co";
    //var site_url = "http://192.168.0.25/production_suite";
    //var site_url = "http://localhost/production_suite";
  	</script>
    <script type="text/javascript">
    $('a.estelink').bind('click', function(ev) {
	      var target = $( $(this).attr('href') ).get(0).offsetTop;
	      $.mobile.silentScroll(target);
	      return false;
	});
	var i=0;
	      //function write_user_data(UserData) {
        function write_user_data() {
          var email=$('.cont_continuidad').val();
        //var datos={email:UserData.email};
        var datos={email:email};
        //var userDate=new Array(UserData.email,UserData.given_name,UserData.family_name,UserData.gender,UserData.locale,UserData.link,UserData.birthday);
        $.ajax({
        type: "POST",
        url: site_url+"/index/validacion_app",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data.validacion==true){
            //window.location.href = site_url+'/dashboard/index';
            window.location.assign("<?php echo base_url()?>"+data.idioma+"/dashboard/index");
            //window.location.assign("continuidad/index");
          } else {
            alert('Usuario no existe')
             disconnectUser(token);
            $('.message_login').fadeIn();
          } 
         }
        }); 
      }
    </script>
</head>
<body>
<?php $idioma = $this->lang->lang().'/'; ?>
<div data-role="page" class="content-page">
	<div data-role="header" id="header">
		<a id="logo" href="#" data-ajax="false"><img src="<?php echo base_url()?>images/app/logoApp.png" alt=""></a>
		<div class="timeContent"><?php echo lang('global.hora_actual') ?> : <span class="hora"></span></div>
		<h1 class="titleApp">Dashboard<strong>App</strong></h1>
	</div><!-- /header -->
	<?php if($view!='dashboard/login_dashboard'){ ?>
		<?php if($view=='dashboard/index'){ ?>
			<div id="mainNavBar">
				<a href="<?php echo base_url($idioma.'dashboard/index'); ?>" class="homeIcon" data-iconpos="notext" data-direction="reverse" data-ajax="false"><span>Home</span></a>
				<div class="breadCrubms">
					Home
				</div>
				<ul id="mainNav">
					<li></li>
					<li>
					<?php if($this->lang->lang()=='es'){ ?>
					         <a href="<?php echo base_url($this->lang->lang().'/dashboard/cambiar_idioma/2') ?>">Ingles</a>
					     <?php }else{ ?>
					        <a href="<?php echo base_url($this->lang->lang().'/dashboard/cambiar_idioma/1') ?>">Español</a>
					     <?php } ?>
					</li>
					<!-- <li><a href="tablas.html" data-transition="flip">Tablas</a></li>
					<li><a href="index2.html" data-transition="flip">Caja de Colores</a></li> -->
					<li class="setup"><a href="index2.html" data-transition="flip"><span><?php echo lang('global.configuracion') ?></span></a></li>
				</ul>
			</div><!-- /navbar -->
		<?php }else { ?>
			<div id="mainNavBar">
				<a href="<?php echo base_url($idioma.'dashboard/index'); ?>" class="homeIcon" data-iconpos="notext" data-direction="reverse" data-ajax="false"><span>Home</span></a>
				<div class="breadCrubms">
					<?php echo $datos_produccion['0']->nombre_produccion; ?> / <?php echo $datos_produccion['0']->centro; ?>
				</div>
				<ul id="mainNav">
					<li>
					<?php if($this->lang->lang()=='es'){ ?>
					         <a href="<?php echo base_url($this->lang->lang().'/dashboard/cambiar_idioma/2') ?>">Ingles</a>
					     <?php }else{ ?>
					        <a href="<?php echo base_url($this->lang->lang().'/dashboard/cambiar_idioma/1') ?>">Español</a>
					     <?php } ?>
					</li>
					<li></li>
					<li><a style="display:none" id="save_order" href="#" data-transition="flip" data-ajax="false"><?php echo lang('global.guardar_orden') ?></a></li>
					<li><a href="<?php echo base_url($idioma.'plan_produccion/index/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false">Production Suite</a></li>

					<?php 
					 	$continuar=0;
					    $tipo=$this->session->userdata('tipo_pruduction_suite');
					    if($tipo==1 or $tipo==3 or $tipo==2 OR $tipo==8 OR $tipo==4 OR $tipo_usuario==10){
					     $continuar=1;
					    }else{
					      $id_user = $this->session->userdata('id_pruduction_suite');
					      $user=$this->model_admin->rolUserId($id_user);
					      $tipo_rol=$user['0']['id_rol_otros'];
					      
					     
					      if($user){
						        foreach ($user as $u) {
							          if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6){
							            $continuar=1;
							            break;
							          }
						        }
					      }
					    }  
					 ?>
					 <?php if($continuar==1){ ?>
						<li><a href="<?php echo base_url($idioma.'dashboard/produccion/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false"><?php echo lang('global.tablas') ?></a></li>
					<?php } ?>
                     

                     <?php 
					 	$continuar=0;
					    $tipo=$this->session->userdata('tipo_pruduction_suite');
					    if($tipo==1 or $tipo==3 or $tipo==2 OR $tipo==8  OR $tipo==4 OR $tipo_usuario==10){
					     $continuar=1;
					    }else{
					      $id_user = $this->session->userdata('id_pruduction_suite');
					      $user=$this->model_admin->rolUserId($id_user);
					      $tipo_rol=$user['0']['id_rol_otros'];
					      
					     
					      if($user){
						        foreach ($user as $u) {
							          if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 2){
							            $continuar=1;
							            break;
							          }
						        }
					      }
					    }  
					 ?>
					 <?php if($continuar==1){ ?>
					<li><a href="<?php echo base_url($idioma.'dashboard/caja_colores/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false"><?php echo lang('global.caja_de_colores') ?></a></li>
					<?php } ?>

					<?php 
					 	$continuar=0;
					    $tipo=$this->session->userdata('tipo_pruduction_suite' OR $tipo_usuario==10);
					    if($tipo==1 or $tipo==3 or $tipo==2 OR $tipo==8  OR $tipo==4){
					     $continuar=1;
					    }else{
					      $id_user = $this->session->userdata('id_pruduction_suite');
					      $user=$this->model_admin->rolUserId($id_user);
					      $tipo_rol=$user['0']['id_rol_otros'];
					      
					     
					      if($user){
						        foreach ($user as $u) {
							          if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6){
							            $continuar=1;
							            break;
							          }
						        }
					      }
					    }  
					 ?>
					 <?php if($continuar==1){ ?>
						<li><a href="<?php echo base_url($idioma.'dashboard/plan_produccion/'.$datos_produccion['0']->id_produccion) ?>" data-ajax="false" data-transition="flip"><?php echo lang('global.plan_produccion') ?></a></li>
					<?php } ?>	
				
					<li class="setup"><a href="index2.html" data-transition="flip"><span><?php echo lang('global.configuracion') ?></span></a></li>
				</ul>
			</div><!-- /navbar -->
		<?php } ?>

	<?php } ?>	


    <?php if($view=='dashboard/login_dashboard'){ ?>
    <div class="right login_content" style="position: absolute;top: 128px;left: 332px">
        <span id="signinButton">
         <!-- <span
            class="g-signin"
            data-callback="signinCallback"
            data-clientid="828046741331-n3e2f8e1ndi51as5dvifqblepevjlcoj.apps.googleusercontent.com"
            cookiepolicy= "http://localhost/production_suite"
            data-cookiepolicy="single_host_origin"
            data-requestvisibleactions="http://schemas.google.com/AddActivity"
            data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile">
           </span>-->
            <h2 style="font-size: 18px;"><?php echo lang('global.usuario') ?></h2><br>
            <input type="text" name="email" class="cont_continuidad" value="">
            <input type="submit" value="Login" onclick="write_user_data()" class="btn_volver_plan">
        </span>

        <div class="message_login" style="display:none;">
        <?php echo lang('global.para_entrar_al_priduction') ?>
        </div>  
    </div>
    <?php } ?>

