<!DOCTYPE html>
<html xml:lang="es-CO" lang="es-CO">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo lang('global.continuidad') ?> APP - RTI</title>
    <meta name="viewport" content="width=device-width, user-scalable=no">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/style_cont.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/mobile_css/jquery-ui-1.10.3.custom.css">

    <script type="text/javascript" src="<?php echo base_url() ?>js/continuidad/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/continuidad/jquery-ui-1.10.3.custom.js"></script>
    <script src="<?php echo base_url()?>js/continuidad/script_cont.js"></script>

    <script type="text/javascript" src="<?php echo base_url()?>js/continuidad/jquery.fancybox.js?v=2.1.5"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>js/continuidad/jquery.fancybox.css?v=2.1.5" />

    <script type="text/javascript" src="<?php echo base_url()?>js/continuidad/bjqs-1.3.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/slide_escenas.css">

    <!-- Foundation CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/fundation/css/foundation.min.css">
    <!-- Foundation JS -->
    <script src="<?php echo base_url(); ?>css/fundation/js/vendor/foundation.js"></script>
    
    <script src="<?php echo base_url(); ?>css/fundation/js/vendor/what-input.js"></script>
  
  <?php $idioma = $this->lang->lang().'/'; ?>
  <!-- Site Url VAR -->
  <script type="text/javascript">
    //var site_url = "http://apps.rtitv.com/production_suite";
    var site_url1 = "http://localhost/production_suite/";
	var site_url2 = "http://productionsuite.co/";
  
    var site_url = "<?php echo base_url().$idioma ?>";
    var idioma = "<?php echo $idioma ?>";
  </script>
    <?php if($view=='continuidad/login_cont'){ ?>

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


      var ClientId = '828046741331-n3e2f8e1ndi51as5dvifqblepevjlcoj.apps.googleusercontent.com';
      //var ClientId = '828046741331.apps.googleusercontent.com';
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

      //function write_user_data(UserData) {
        function write_user_data() {
          console.log("aca entra")
          console.log($('.cont_continuidad').val())
          var email=$('.cont_continuidad').val()
           var password=$('.cont_password').val()
        //var datos={email:UserData.email};
        var datos={email:email,password:password};
        //var userDate=new Array(UserData.email,UserData.given_name,UserData.family_name,UserData.gender,UserData.locale,UserData.link,UserData.birthday);
        $.ajax({
        type: "POST",
        url: site_url+"index/validacion_cont",
        data: datos,
        dataType: "json",
        success:function(data){
          if(data.validacion==true){
            //window.location.href = site_url+'continuidad/index';
            window.location.assign("<?php echo base_url()?>"+data.idioma+"/continuidad/index");
            //window.location.assign("continuidad/index");
          } else {
            alert('Usuario no existe')
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

<script type="text/javascript">
$(document).ready(function() {
  $('.fancybox-thumbs').fancybox({
    maxWidth  : 630,
    maxHeight : 500,
    fitToView : false,
    width   : '630px',
    height    : '500px',
    prevEffect  : 'none',
    nextEffect  : 'none',
    openEffect  : 'elastic',
    closeEffect : 'elastic',

    closeBtn  : true,
    arrows    : true,
    nextClick : true
  });
});
</script>

  </head>
  <body>
  <div id="wrapper_loader" style="display:none">
    
  </div>
    <!-- header -->
    <?php if($view!='continuidad/login_cont'){ ?>
      <div class="login_estatus" id="login_estatus">
        <?php if($this->session->userdata('id_production_suite')){
              $id_user=$this->model_admin->tipoUserId($this->session->userdata('id_production_suite'));
              if($id_user){
                 if($id_user['0']->id_tipoUsuario==5){
                   $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite'));
                   $rol=$user['0']['descripcion'];   
                 }else{
                   $rol=$id_user['0']->descripcion;   
                 }
              }else{
                $rol='';
              }
          }else{
            $rol='';
          }    
        ?>
        <?php echo lang('global.bienvenido') ?> : <?php echo $this->session->userdata('correo_pruduction_suite') ?> | <?php echo $rol ?> |<a href="<?php echo base_url($idioma.'login/disconnectUser_cont') ?>"><?php echo lang('global.cerrar_sesion') ?> </a>/
        <?php if($this->lang->lang()=='es'){ ?>
         <a href="<?php echo base_url($this->lang->lang().'/continuidad/cambiar_idioma/2') ?>">Ingles</a>
     <?php }else{ ?>
        <a href="<?php echo base_url($this->lang->lang().'/continuidad/cambiar_idioma/1') ?>">Español</a>
     <?php } ?>
      </div>
    <?php } ?>
    <header>
      <a href="<?php echo base_url($idioma.'continuidad/index') ?>" class="logo">
        <img src="<?php echo base_url() ?>images/cont/logo.png" alt="">
      </a>
      <span class="titulo hide-for-small-only"><?php echo lang('global.continuidad') ?> <strong>APP</strong>
      </span>
    </header>
  

    <?php if($view=='continuidad/login_cont'){ ?>
    <div class="small-12 columns vertical-centered">
      <div class="medium-6 small-10 large-4 small-centered columns">
        <div class="large-12 columns">
          <label for=""> </label>
          <input type="text" name="email" class="cont_continuidad" placeholder="<?php echo lang('global.usuario') ?>" value="">
        </div>
        <div class="small-12 columns">
            <input type="password" name="password" placeholder="<?php echo lang('global.contrasena') ?>" class="cont_password" value="">
        </div>
        <div class="small-12 columns">
          <input type="submit" value="Login" onclick="write_user_data()" class="button expanded">
        </div>
      </div>
        <div class="message_login" style="display:none;">
          <?php echo lang('global.para_entrar_al_priduction') ?> 
        </div>  
    </div>
    <?php } ?>

    <!-- header -->
