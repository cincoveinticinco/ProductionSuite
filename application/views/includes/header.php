<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Production Suite - RTI</title>
  
  <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.png">
  <!-- =====
  Included CSS Files 
  ====== -->

  <!-- Foundation CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/foundation.min.css">
  <!-- jQuery UI CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/production-suite/jquery-ui-1.10.3.custom.css">
  <!-- Sexy Combo CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/sexy/sexy-combo.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/component.css" media="all" />
  <!-- ScrollPane CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.jscrollpane.css" media="all" />
  <!-- App Style CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/app.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-impromptu.css">
 
  <!-- App Style Cating -->
  <!--link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css"-->


  <!-- =====
  Included JS Files 
  ====== -->


 
<!-- Modernizer Foundation JS -->


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

  <script src="<?php echo base_url()?>js/autoNumeric.js"></script>

  <!-- GALERIA CASTINING -->
  
  <script src="<?php echo base_url()?>js/modernizr.custom.js"></script>
  <script src="<?php echo base_url()?>js/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo base_url()?>js/classie.js"></script>
  <script src="<?php echo base_url()?>js/colorfinder-1.1.js"></script>
  <script src="<?php echo base_url()?>js/masonry.pkgd.min.js"></script>
  
  <script src="<?php echo base_url()?>js/cbpGridGallery.js"></script>
  
  <script src="https://www.google.com/jsapi"></script>

  <?php $idioma = $this->lang->lang().'/'; ?>
  <!-- Site Url VAR -->
  <script type="text/javascript">
    //var site_url = "http://apps.rtitv.com/production_suite";

    var site_url1 = "http://localhost/production_suite/";
    var site_url = "<?php echo base_url().$idioma ?>";
    var site_url2 = "<?php echo base_url() ?>";
    var idioma = "<?php echo $idioma ?>";

    //var site_url = "http://localhost/production_suite/";

  </script>
  <!-- App JS -->
  <script src="<?php echo base_url(); ?>js/app.js"></script>
  <script src="<?php echo base_url(); ?>js/script.js"></script>
  <script src="<?php echo base_url(); ?>js/script-2.js"></script>
  <script src="<?php echo base_url()?>js/script_post.js"></script>
  <script src="<?php echo base_url()?>js/jquery-impromptu.js"></script>
  

  <script type="text/javascript">
  $(document).ready(function(){
    $('#wrapper_loader').fadeOut();
  });
  </script>

  <!-- =====
  LOGIN 
  ====== -->
  <?php if($view=='login'){ ?>

  <script src="https://apis.google.com/js/client.js"></script>
  <!-- Place this asynchronous JavaScript just before your </body> tag -->
 
    <script type="text/javascript">
      
      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();

      function signinCallback(authResult) {
        if (authResult['access_token']) {
          document.getElementById('signinButton').setAttribute('style', 'display: block');
          authorize();
        } else if (authResult['error']) {
        // There was an error.
        // Possible error codes:
        //   "access_denied" - User denied access to your app
        //   "immediate_failed" - Could not automatically log in the user
        // console.log('There was an error: ' + authResult['error']);
        }
      }
      function disconnectUser(access_token) {
        var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' +
        access_token;
        // Perform an asynchronous GET request.
        $.ajax({
          type: 'GET',
          url: revokeUrl,
          async: false,
          contentType: "application/json",
          dataType: 'jsonp',
          success: function(nullResponse) {
            //location.reload();
          },
          error: function(e) {
          // Handle the error
          // console.log(e);
          // You could point users to manually disconnect if unsuccessful
          // https://plus.google.com/apps
          }
        });
      }

      // produccion 
      //var ClientId = '275563443386-9maiprfknbiedmib2k4etfk7p861ahti.apps.googleusercontent.com';
      // pruebas 
      var ClientId = '828046741331-n3e2f8e1ndi51as5dvifqblepevjlcoj.apps.googleusercontent.com';
      var scopes = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
      var token = '';

      function authorize() {
        gapi.auth.authorize({client_id: ClientId, immediate: true, scope: scopes}, function() {
        token = gapi.auth.getToken();
        if (!token.error) {
        token = token.access_token;

        var request = gapi.client.request({
          'path': 'oauth2/v2/userinfo'
        });
        request.execute(function(response) {
          write_user_data(response);
           
          });

          } else {
          token = '';
            alert('Ocurrió algún error');
          }
        });
      }

      function write_user_data(UserData) {
        var datos={email:UserData.email};
    var userDate=new Array(UserData.email,UserData.given_name,UserData.family_name,UserData.gender,UserData.locale,UserData.link,UserData.birthday);
        $.ajax({
        type: "POST",
        /* url: site_url+"index.php?/index/validacion",*/
        url: site_url+"index/validacion",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data.validacion==true){
            window.location.assign("<?php echo base_url()?>"+data.idioma+"/produccion/producciones");
          } else {
            disconnectUser(token);
            $('.message_login').fadeIn();
          } 
         }
        }); 
      }
      // Could trigger the disconnect on a button click
      $(document).ready(function(){
        $('#revokeButton').click(function(){
          disconnectUser(token);
      });
    });
    </script>
   <?php } ?>

</head>

<body>
  <div id="wrapper_loader">
    
  </div>
  <div id="wrapper">
    <?php if($view!='login'){ ?>
    <div id="top_header">
     <?php echo lang('global.bienvenido') ?>: <?php echo $this->session->userdata('correo_pruduction_suite') ?>/ <a href="#"><?php echo lang('global.editar_mis_datos') ?></a> / <?php echo anchor('login/disconnecUser', lang('global.cerrar_sesion')) ?>/
      <?php if($this->lang->lang()=='es'){ ?>
         <a href="<?php echo base_url($this->lang->lang().'/produccion/cambiar_idioma/2') ?>">Ingles</a>
     <?php }else{ ?>
        <a href="<?php echo base_url($this->lang->lang().'/produccion/cambiar_idioma/1') ?>">Español</a>
     <?php } ?>
    </div>
    <?php } ?>   
    <header id="header" class="row">
      <div id="logo" class="logo_production">
        <?php echo anchor($this->lang->lang().'/produccion/producciones','Production Suite',array('title'=>'Production Suite')); ?>
      </div>
      <?php $this->load->view('includes/partials/top_nav'); ?>
      <div class="right login_content">
        <span id="signinButton">
          <span
            class="g-signin"
            data-callback="signinCallback"
             data-clientid="828046741331-n3e2f8e1ndi51as5dvifqblepevjlcoj.apps.googleusercontent.com"
            cookiepolicy= "http://localhost/"
            data-requestvisibleactions="http://schemas.google.com/AddActivity"
            data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile">
           </span>
        </span>
        <div class="message_login" style="display:none;">
          Para entrar al Production Suite debes tener una cuenta de @rtitv.com<br>
          Haz <a href="mailto:info@rtitv.com.co">clic acá</a> para ponerte en contacto con nosotros
        </div>  
      </div>
    </header>