<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_cont extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_index');
	}

	public function validacion(){
	    $user=$this->model_index->correo_user($email);
	    if($user){
	     	$value=array('validacion'=>true);
	     	$session = array(
                 'login_pruduction_suite_cont' => TRUE,
                 'id_pruduction_suite' =>$user[0]->id,
                 'nombre_pruduction_suite' =>$user[0]->nombre,
                 'correo_pruduction_suite' =>$user[0]->correo,
                 'tipo_pruduction_suite' =>$user[0]->id_tipoUsuario,
                );
                $this->session->set_userdata($session); 
                $this->user_log();
	    }else {
	     	$value=array('validacion'=>false);
	    }
		echo json_encode($value);
	}

	public function user_log(){
      $idusuario = $this->session->userdata('id_pruduction_suite');
      $user = $this->model_admin->user_id($idusuario);
      if($user){
	      $cadena_insert = "______________________________________________________________________________________________________________"."\n";
	      $cadena_insert .= " INICIO DE SESIÓN MODULO CONTINUIDAD".strtoupper(date("d-M-Y H:i:s"))."\n";
	      $cadena_insert .= "______________________________________________________________________________________________________________"."\n";
	      $cadena = './logs/log_'.$user[0]->id.'_'.$user[0]->nombre.'_'.$user[0]->apellido.'_'.strtoupper(date("d_M_Y")).'.txt';
	      $nuevoarchivo = fopen($cadena, "a+w+"); 
	      fwrite($nuevoarchivo,$cadena_insert); 
	      fclose($nuevoarchivo);
  	  }
    }
}