<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct ();
	    $this->load->model('model_admin'); 
	}
	
	public function index(){
		$data['view']='login';   
		$this->load->view('includes/template',$data);
		setcookie('contador2','0');
	}

   public function login_cont(){
		$data['view']='continuidad/login_cont';   
		$this->load->view('includes/template_cont',$data);
	}

	   public function login_dashboard(){
		$data['view']='dashboard/login_dashboard';   
		$this->load->view('includes/template_app',$data);
	}

	public function login_out(){
	    $this->session->sess_destroy();
	   redirect ($this->lang->lang().'/login');
    }

    public function disconnecUser(){ 
    	$this->user_log();
	    $this->session->sess_destroy(); 
	    $data['view']='disconnectUser';   
		$this->load->view('includes/template',$data);
    }

    public function disconnectUser_cont(){  
    	$this->user_log("MODULO CONTINUIDAD ");
	    $this->session->sess_destroy(); 
	    $data['view']='disconnectUser_cont';   
		$this->load->view('includes/template_cont',$data);
    }

    public function user_log($modulo=""){
      $idusuario = $this->session->userdata('id_pruduction_suite');
      $user = $this->model_admin->user_id($idusuario);
      if($user){
	      $cadena_insert = "______________________________________________________________________________________________________________"."\n";
	      $cadena_insert .= " FIN DE SESIÓN ".$modulo.strtoupper(date("d-M-Y H:i:s"))."\n";
	      $cadena_insert .= "______________________________________________________________________________________________________________"."\n";
	      $cadena = './logs/log_'.$user[0]->id.'_'.$user[0]->nombre.'_'.$user[0]->apellido.'_'.strtoupper(date("d_M_Y")).'.txt';
	      $nuevoarchivo = fopen($cadena, "a+w+"); 
	      fwrite($nuevoarchivo,$cadena_insert); 
	      fclose($nuevoarchivo);
  	  }
    }
	
}

