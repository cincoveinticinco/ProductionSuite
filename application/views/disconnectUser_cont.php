<script src="<?php echo base_url(); ?>js/foundation.min.js"></script>
<script src="https://apis.google.com/js/client.js"></script>
<script type="text/javascript">
//var site_url = "http://testing.productionsuite.co/";
var site_url = "http://staging.productionsuite.co";
 (function() {
      document.getElementById('top_menu').style.display='none';
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
          window.location.hfer=site_url+'continuidad/index';
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


    //var ClientId = '828046741331.apps.googleusercontent.com';
    var ClientId = '275563443386-uprkh8i7jfdokn1mk40s2frvietkvhor.apps.googleusercontent.com';
    var scopes = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
    var token = '';

    function authorize() {
      gapi.auth.authorize({client_id: ClientId, immediate: true, scope: scopes}, function() {
      token = gapi.auth.getToken();
      if (!token.error) {
      token = token.access_token;

      var request = gapi.client.request({
        'path':   'oauth2/v2/userinfo'
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
      
      $.ajax({
      type: "POST",
      url: site_url+"/index/validacion",
      data: datos,
      dataType: "json",
      success:function(data){
        if(data.validacion==true){
          disconnectUser(token);
        } else {
          disconnectUser(token);
          $('.message_login').fadeIn();
        } 
       }
      }); 
      

    }
       
</script>