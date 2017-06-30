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
    <script type="text/javascript">
    var site_url = "<?php echo base_url()?>";
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
            window.location.href = site_url+'/dashboard/index';
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
<div data-role="page" class="content-page">
	<div data-role="header" id="header">
		<a id="logo" href="#" data-ajax="false"><img src="<?php echo base_url()?>images/app/logoApp.png" alt=""></a>
		<div class="timeContent">Hora actual <span class="hora"></span></div>
		<h1 class="titleApp">Dashboard<strong>App</strong></h1>
	</div><!-- /header -->
	<?php if($view!='dashboard/login_dashboard'){ ?>
		<?php if($view=='dashboard/index'){ ?>
			<div id="mainNavBar">
				<a href="<?php echo base_url('dashboard/index'); ?>" class="homeIcon" data-iconpos="notext" data-direction="reverse" data-ajax="false"><span>Home</span></a>
				<div class="breadCrubms">
					Home
				</div>
				<ul id="mainNav">
					<li></li>
					<!-- <li><a href="tablas.html" data-transition="flip">Tablas</a></li>
					<li><a href="index2.html" data-transition="flip">Caja de Colores</a></li> -->
					<li class="setup"><a href="index2.html" data-transition="flip"><span>Configuración</span></a></li>
				</ul>
			</div><!-- /navbar -->
		<?php }else { ?>
			<div id="mainNavBar">
				<a href="<?php echo base_url('dashboard/index'); ?>" class="homeIcon" data-iconpos="notext" data-direction="reverse" data-ajax="false"><span>Home</span></a>
				<div class="breadCrubms">
					<?php echo $datos_produccion['0']->nombre_produccion; ?> / <?php echo $datos_produccion['0']->centro; ?>
				</div>
				<ul id="mainNav">
					<li></li>
					<li><a style="display:none" id="save_order" href="#" data-transition="flip" data-ajax="false">Guardar orden</a></li>
					<li><a href="<?php echo base_url('plan_produccion/index/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false">Production Suite</a></li>
					<li><a href="<?php echo base_url('dashboard/produccion/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false">Tablas</a></li>
					<li><a href="<?php echo base_url('dashboard/caja_colores/'.$datos_produccion['0']->id_produccion); ?>" data-transition="flip" data-ajax="false">Caja de Colores</a></li>
					<li><a href="<?php echo base_url('dashboard/plan_produccion/'.$datos_produccion['0']->id_produccion) ?>" data-ajax="false" data-transition="flip">Plan Producción</a></li>
					<li class="setup"><a href="index2.html" data-transition="flip"><span>Configuración</span></a></li>
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
            <h2 style="font-size: 18px;">Usuario</h2><br>
            <input type="text" name="email" class="cont_continuidad" value="">
            <input type="submit" value="Login" onclick="write_user_data()" class="btn_volver_plan">
        </span>

        <div class="message_login" style="display:none;">
          Para entrar al Production Suite debes tener una cuenta de @rtitv.com<br>
          Haz <a href="mailto:info@rtitv.com.co">clic acá</a> para ponerte en contacto con nosotros
        </div>  
    </div>
    <?php } ?>

