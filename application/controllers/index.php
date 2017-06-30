<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
        $this->load->helper('cookie'); 
	    $this->load->model('model_index');
	    $this->load->model('model_admin');
	    $this->load->model('model_produccion');
	    $this->load->model('model_casting');
	    //$this->load->library('My_PHPMailer');
	    

	}
	
	public function index(){
	}

	public function validacion(){

	     //$email="andres.v@cincoveinticinco.com";

	   // $email=$_POST['email'];
           $email='pablo@cincoveinticinco.com';
	   // $email='alexander.ospina@cincoveinticinco.com';
	     $user=$this->model_index->correo_user($email);
	     if($user){
	     	$session = array(
                 'login_pruduction_suite' => TRUE,
                 'id_pruduction_suite' =>$user[0]->id,
                 'nombre_pruduction_suite' =>$user[0]->nombre,
                 'apellido_pruduction_suite' =>$user[0]->apellido,
                 'correo_pruduction_suite' =>$user[0]->correo,
                 'tipo_pruduction_suite' =>$user[0]->id_tipoUsuario,
                 'idioma' =>$user[0]->idioma,
                );
                $this->session->set_userdata($session); 
                $this->user_log();
                $value=array('validacion'=>true,'idioma'=>$user[0]->idioma);
	     } else {
	     	$value=array('validacion'=>false);
	     }
		echo json_encode($value);
	}



	public function validacion_app(){

	     //$email="andres.v@cincoveinticinco.com";

	    $email=$_POST['email'];
	    //$email='alexander.ospina@cincoveinticinco.com';
         $email='julian.m@cincoveinticinco.com';

	     $user=$this->model_index->correo_user($email);
	     if($user){
	     	$session = array(
                 'login_pruduction_suite' => TRUE,
                 'id_pruduction_suite' =>$user[0]->id,
                 'nombre_pruduction_suite' =>$user[0]->nombre,
                 'apellido_pruduction_suite' =>$user[0]->apellido,
                 'correo_pruduction_suite' =>$user[0]->correo,
                 'tipo_pruduction_suite' =>$user[0]->id_tipoUsuario,
                 'idioma' =>$user[0]->idioma,
                );
                $this->session->set_userdata($session); 
                $this->user_log();
                $value=array('validacion'=>true,'idioma'=>$user[0]->idioma);
	     } else {
	     	$value=array('validacion'=>false);
	     }
		echo json_encode($value);
	}

	

	public function validacion_cont(){
	     $email=$_POST['email'];
	     $password=$_POST['password'];
	     //$email='alexander.ospina@cincoveinticinco.com';
	     $user=$this->model_index->correo_user_password($email,md5($password));
	     if($user){
	     	
	     	$session = array(
                 'login_pruduction_suite_cont' => true,
                 'id_production_suite' =>$user[0]->id,
                 'nombre_pruduction_suite' =>$user[0]->nombre,
                 'correo_pruduction_suite' =>$user[0]->correo,
                 'tipo_production_suite' =>$user[0]->id_tipoUsuario,
                 'idioma' =>$user[0]->idioma,
                );
                $this->session->set_userdata($session);
                $value=array('validacion'=>true,'idioma'=>$user[0]->idioma);
	     } else {
	     	$value=array('validacion'=>false);
	     }
		echo json_encode($value);
	}

	public function user_log(){
      $idusuario = $this->session->userdata('id_pruduction_suite');
      $user = $this->model_admin->user_id($idusuario);
      if($user){
      	$cadena_insert = "______________________________________________________________________________________________________________"."\n";
	    $cadena_insert .= " INICIO DE SESIÓN ".strtoupper(date("d-M-Y H:i:s"))."\n";
	    $cadena_insert .= "______________________________________________________________________________________________________________"."\n";
	    $cadena = './logs/log_'.$user[0]->id.'_'.$user[0]->nombre.'_'.$user[0]->apellido.'_'.strtoupper(date("d_M_Y")).'.txt';
	    $nuevoarchivo = fopen($cadena, "a+w+"); 
	    fwrite($nuevoarchivo,$cadena_insert); 
	    fclose($nuevoarchivo);
      }
      
    }



     public function reporteMailllll(){

        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);

        if ($producciones) {
            foreach ($producciones as $produccion) {

                $cadena_personajes_expirados = "";
                $cadena_personajes_sin_contrato = "";
                $cadena_solicitudes_rechazadas = "";
                $cadena_solicitudes_anuladas = "";
                $cadena_solicitudes_aprobar = "";

                $personajes_expirados=$this->model_casting->get_personajes_expirados($produccion['id_produccion']);
                if ($personajes_expirados) {
                    foreach ($personajes_expirados as $key=>$personaje_expirados) {
                        if (strtotime($personaje_expirados->fecha_contrato)<strtotime($personaje_expirados->fecha_plan_diario)) {
                             if ($key%2!=0) {
                                $cadena_personajes_expirados .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_personajes_expirados .= '
                                <tr style="background: none">';
                            }
                            $cadena_personajes_expirados.="
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->actor)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->elemento)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->rol)."</td>
                                <td style='border: 1px solid black;'>".strtoupper(date('d-M-Y', strtotime($personaje_expirados->fecha_plan_diario)))."</td>
                            </tr>";
                        }
                    }
                    if ($cadena_personajes_expirados!=""){
                        $cadena_personajes_expirados = '<label>Personajes Expirados</label><table border="2">'. $cadena_personajes_expirados.'</table>';
                    }
                }

                $personajes_sin_contrato = $this->model_casting->get_personajes_sin_contrato($produccion['id_produccion']);
                if ($personajes_sin_contrato) {
                    foreach ($personajes_sin_contrato as $key=>$personaje_sin_contrato) {
                        if ($personaje_sin_contrato->fecha_plan_diario!="" AND $personaje_sin_contrato->fecha_plan_diario!="0000-00-00") {
                                
                            if ($key%2!=0) {
                                $cadena_personajes_sin_contrato .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_personajes_sin_contrato .= '
                                <tr style="background: none">';
                            }
                            $cadena_personajes_sin_contrato.="
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->elemento)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->rol)."</td>
                                <td style='border: 1px solid black;'>".strtoupper(date('d-M-Y', strtotime($personaje_sin_contrato->fecha_plan_diario)))."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->actor)."</td>
                            </tr>";
                        }
                    }
                    if ($cadena_personajes_sin_contrato!=""){
                        $cadena_personajes_sin_contrato = '<label><b>Personaje Sin Contratos a grabar en los próximos 7 días</b></label>
                        <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                        <tr style="background: #808080;font-weight: bold;color:white;">
                            <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                            <td style="border: 1px solid black;"><strong>ROL</strong></td>
                            <td style="border: 1px solid black;"><strong>FECHA A GRABAR</strong></td>
                            <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                        </tr>'. $cadena_personajes_sin_contrato.'</table>';
                    }
                }


                $solicitudes_aprobar="";
                $solicitudes_rechazadas=$this->model_casting->get_solicitudes_rechazadas($produccion['id_produccion']);
                if ($solicitudes_rechazadas) {
                    $cadena_solicitudes_rechazadas .= '
                    <label><b>Solicitudes Rechazadas</b></label>
                    <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                            <tr style="background: #808080;font-weight: bold;color:white;">
                                <td style="border: 1px solid black;"><strong>NRO SOLICITUD</strong></td>
                                <td style="border: 1px solid black;"><strong>ESTADO</strong></td>
                                <td style="border: 1px solid black;"><strong>FECHA RECHAZADA</strong></td>
                                <td style="border: 1px solid black;"><strong>RECHAZADO POR</strong></td>
                                <td style="border: 1px solid black;"><strong>RAZON</strong></td>
                                <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                                <td style="border: 1px solid black;"><strong>ROL</strong></td>
                                <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                            </tr>';
                    foreach ($solicitudes_rechazadas as $key=>$solicitud_rechazada) {
                        if ($solicitud_rechazada->tipo == 2) {
                           $id = $this->completar_id($solicitud_rechazada->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud_rechazada->id_solicitud_anexa,$solicitud_rechazada->id_solicitud);
                        }else{
                           $id = $this->completar_id($solicitud_rechazada->id_solicitud);
                        }

                        if ($key%2!=0) {
                            $cadena_solicitudes_rechazadas .= '
                            <tr style="background: #e6e4e5">';
                        }else{
                            $cadena_solicitudes_rechazadas .= '
                            <tr style="background: none">';
                        }

                        $cadena_solicitudes_rechazadas .= '
                            <td style="border: 1px solid black;">'.$id.'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->estado).'</td>
                            <td style="border: 1px solid black;">'.strtoupper(date('d-M-Y', strtotime($solicitud_rechazada->fecha_aprobacion))).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->usuario).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->notas).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->elemento).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->rol).'</td>
                            <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud_rechazada->actor).'</td>
                        </tr>';
                    }
                    $cadena_solicitudes_rechazadas .= '</table>';
                }

                $solicitudes_anuladas=$this->model_casting->get_solicitudes_anuladas($produccion['id_produccion']);

                if ($solicitudes_anuladas) {
                    $cadena_solicitudes_anuladas .= '
                    <label><b>Solicitudes Rechazadas</b></label>
                    <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                            <tr style="background: #808080;font-weight: bold;color:white;">
                                <td style="border: 1px solid black;"><strong>NRO SOLICITUD</strong></td>
                                <td style="border: 1px solid black;"><strong>FECHA ANULADA</strong></td>
                                <td style="border: 1px solid black;"><strong>ANULADA POR</strong></td>
                                <td style="border: 1px solid black;"><strong>RAZON</strong></td>
                                <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                                <td style="border: 1px solid black;"><strong>ROL</strong></td>
                                <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                            </tr>';
                    foreach ($solicitudes_anuladas as $key=>$solicitud_anulada) {
                        if ($solicitud_anulada->tipo == 2) {
                           $id = $this->completar_id($solicitud_anulada->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud_anulada->id_solicitud_anexa,$solicitud_anulada->id_solicitud);
                        }else{
                           $id = $this->completar_id($solicitud_anulada->id_solicitud);
                        }

                        if ($key%2!=0) {
                            $cadena_solicitudes_anuladas .= '
                            <tr style="background: #e6e4e5">';
                        }else{
                            $cadena_solicitudes_anuladas .= '
                            <tr style="background: none">';
                        }
                        $cadena_solicitudes_anuladas .= '
                            <td style="border: 1px solid black;">'.$id.'</td>
                            <td style="border: 1px solid black;">'.strtoupper(date('d-M-Y', strtotime($solicitud_anulada->fecha_aprobacion))).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->usuario).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->notas).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->elemento).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->rol).'</td>
                            <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud_anulada->actor).'</td>
                        </tr>';
                    }
                    $cadena_solicitudes_anuladas .= '</table>';
                }

                //$usuarios = $this->model_casting->get_usuarios_mail($produccion['id_produccion']);
                $usuarios = $this->model_casting->get_usuarios_mail_two($produccion['id_produccion']);
                foreach ($usuarios as $usuario) {
                    $cadena_solicitudes_aprobar = "";
                    $solicitudes = false;
                    if ($usuario->id_tipoUsuario==1 OR $usuario->id_tipoUsuario==3) {
                        $sql =" AND ( produccion.id = ".$produccion['id_produccion']." AND solicitudes.tipo!=2 ) ";
                        $solicitudes = $this->model_casting->get_all_solicitudes($sql);
                    }else{
                        if ($usuario->id_tipoUsuario!=5) {
                            $solicitudes = $this->model_casting->get_solicitudes_tipo_usuario($usuario->id_tipoUsuario);
                        }else{
                            $solicitudes = $this->model_casting->get_solicitudes_rol_otros($usuario->id_rol);
                        }
                    }

                    if ($solicitudes) {
                        $cadena_solicitudes_aprobar .= "
                        <label><b>Solcitudes por aprobar</b></label>
                        <table style='border-collapse: collapse;margin: 0;background: none;'>
                        <tr style='background-color: #808080; font-weight: bold; color:white;text-align: center;'>
                                <td style='border: 1px solid black;'><strong>NRO SOLICITUD</strong></td>
                                <td style='border: 1px solid black;'><strong>PERSONAJE</strong></td>
                                <td style='border: 1px solid black;'><strong>ROL</strong></td>
                                <td style='border: 1px solid black;'><strong>ACTOR</strong></td>
                                <td style='border: 1px solid black;'><strong>FECHA A GRABAR</strong></td>
                        </tr>";
                        foreach ($solicitudes as $key=>$solicitud) {
                            if ($solicitud->tipo == 2) {
                               $id = $this->completar_id($solicitud->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud->id_solicitud_anexa,$solicitud->id_solicitud);
                            }else{
                               $id = $this->completar_id($solicitud->id_solicitud);
                            }
                            if ($key%2!=0) {
                                $cadena_solicitudes_aprobar .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_solicitudes_aprobar .= '
                                <tr style="background: none">';
                            }
                            $fecha = $this->model_casting->fecha_maxima($solicitud->id_elemento);

                            if ($fecha AND $fecha[0]->fecha_inicio) {
                                $fecha = strtoupper(date('d-M-Y', strtotime($fecha[0]->fecha_inicio)));
                            }else{
                                $fecha = '-';
                            }

                            $cadena_solicitudes_aprobar .='
                                <td style="border: 1px solid black;">'.$id.'</td>
                                <td style="border: 1px solid black;">'.strtoupper($solicitud->elementos).'</td>
                                <td style="border: 1px solid black;">'.strtoupper($solicitud->roles_lista).'</td>
                                <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud->actor).'</td>
                                <td style="border: 1px solid black;"">'.$fecha.'</td>
                            </tr>';
                        }
                        $cadena_solicitudes_aprobar .= '</table>';                   
                    }
                      
                      //echo $usuario->correo.'produccion: '.$produccion['id_produccion'].'<br>';
                   
                    $mail = new PHPMailer (); 
                    $mail->From = "info@rtitv.com.co"; 
                    $mail->FromName = "info@rtitv.com.co"; 
                    $mail->AddAddress ($usuario->correo); 
                    $mail->CharSet = 'UTF-8'; 
                    $mail->Subject = "Staging - Production Suite – Notificación Diaria de Solicitudes"; 
                    $html = '<div style="font-family: "Calibri","sans-serif";"> 
                                    <img src="'.base_url('images/produccion/production_suite.jpg').'" width="150px" style="float: right;"><br>
                                    <div style="clear: both;"></div>
                                    <b>Reporte diario al '.date('d').' de '.date('M').' del '.date('Y').'</b><br> 
                                    <p>Los siguientes Personajes/Actores están pendiente por acciones:<br><br>'.
                                    '<strong>PRODUCCIÓN: </strong>'.strtoupper($produccion['nombre_produccion']).'<br><br>';
                    if($cadena_personajes_expirados or $cadena_personajes_sin_contrato or $cadena_solicitudes_aprobar or $cadena_solicitudes_rechazadas or $cadena_solicitudes_anuladas){
                             $html.=$cadena_personajes_expirados.'<br><br>'.
                                    $cadena_personajes_sin_contrato.'<br><br>'.
                                    $cadena_solicitudes_aprobar.'<br><br>'.
                                    $cadena_solicitudes_rechazadas.'<br><br>'.
                                    $cadena_solicitudes_anuladas.'<br><br>
                                    </div>';
                    }else{
                        $html.='No hay datos para reportar';
                    }
                                    
                    $mail->Body=$html;                
                    $mail->IsHTML(true); 
                    $mail->IsSMTP(); 
                    $mail->Host = 'ssl://smtp.gmail.com'; 
                    $mail->Port = 465; 
                    $mail->SMTPAuth = true; 
                    $mail->Username = 'alexander.ospina@cincoveinticinco.com'; 
                    $mail->Password = '1087997536'; 
                    if(!$mail->Send()) { 
                      echo 'Error: ' . $mail->ErrorInfo; 
                    } else { 
                      echo 'Mail enviado!'; 
                    }
                   // break;
                }
                //break;
            }
        }   
    }

        public function completar_id($id){
        $new_id ="";
        if($id){
            for ($i=strlen($id); $i < 5; $i++) { 
                $new_id.="0";
            }
            $new_id.=$id;
        }
        return $new_id;
    }

    public function numeracion_otro_si($id_solicitud, $id_otro_si){
        $query = $this->db->query("SELECT count(solicitudes.id) AS numero FROM solicitudes
        WHERE tipo = 2 AND solicitudes.id_solicitud_anexa = ".$id_solicitud." AND id < ".$id_otro_si.";");
        if ($query->num_rows>0){
            $query =  $query->result();
            if ($query[0]->numero==0) {
                $numero = 1;
            }else{
                $numero = $query[0]->numero;
            }
        }
        if (strlen($numero)<2) {
            $numero = '0'.$numero;
        }
        return $numero;
    }
}

