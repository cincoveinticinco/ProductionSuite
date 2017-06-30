<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peticiones_continuidad extends CI_Controller {


    public function __construct (){  
      parent :: __construct (); 
      $this->load->model('model_continuidad');
      $this->load->model('model_plan_produccion');
      $this->load->model('model_capitulos');
      $this->load->model('model_plan_diario');
      $this->load->model('model_plan_general');
      $this->load->model('model_produccion');
      $this->load->model('model_escenas_2');
      $this->load->model('model_elementos');
      $this->load->model('model_escenas');
      $this->load->model('model_admin');
      $this->load->model('model_index');
      header("Content-type: application/json; charset=utf-8");
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, POST');
            
  }

  public function userMail(){
     $email=$this->input->post('email');
    //$email='carlos.robinson@gmail.com';
       $user=$this->model_index->correo_user($email);
       $data['error']=false;
       if($user){
        
           

            if($user[0]->id_tipoUsuario=='3' or $user[0]->id_tipoUsuario=='1' OR $user[0]->id_tipoUsuario=='2' 
              OR $user[0]->id_tipoUsuario=='7' OR $user[0]->id_tipoUsuario=='8'  OR $user[0]->id_tipoUsuario=='10'){
             
                $sql=" WHERE p.estado =1";
              $produccion=$this->model_produccion->producciones_all($sql);
          }else{
          
                $sql=" AND p.estado =1";
              $produccion=$this->model_produccion->producciones_user($id_user,$sql);

          }

          $user = array(
                 'id_pruduction_suite' =>$user[0]->id,
                 'nombre_pruduction_suite' =>$user[0]->nombre,
                 'apellido_pruduction_suite' =>$user[0]->apellido,
                 'correo_pruduction_suite' =>$user[0]->correo,
                 'tipo_pruduction_suite' =>$user[0]->id_tipoUsuario,
                );

           $data['user']=$user;
          $data['producciones']=$produccion;

       } else {
        $data['error']=true;
       }
    echo json_encode($data);
  }


  public function menu_plan_diario($id_produccion){
    
    $fecha=Date('Y-m-d');
    $semana1=$this->model_continuidad->semana_actual($id_produccion,$fecha);
    $semana2=null;
    $semana3=null;
    $plan_seman1=null;
    $plan_seman2=null;
    $plan_seman3=null;
    if($semana1){
      $plan_seman1=array('fecha1'=>$semana1['0']->fecha_inicio_semana,'fecha2'=>$semana1['0']->fecha_fin_semana,'unidades'=>($this->model_continuidad->plan_diario_semana($id_produccion,$semana1['0']->fecha_inicio_semana,$semana1['0']->fecha_fin_semana)));
      //$plan_seman1=$this->model_continuidad->plan_diario_semana($id_produccion,$semana1['0']->fecha_inicio_semana,$semana1['0']->fecha_fin_semana);
      $id_semana=$semana1['0']->id+1;
      $semana2=$this->model_continuidad->semana_id($id_produccion,$id_semana);
      if($semana2){
        $plan_seman2=array('fecha1'=>$semana2['0']->fecha_inicio_semana,'fecha2'=>$semana2['0']->fecha_fin_semana,'unidades'=>($this->model_continuidad->plan_diario_semana($id_produccion,$semana2['0']->fecha_inicio_semana,$semana2['0']->fecha_fin_semana)));
        //$plan_seman2=$this->model_continuidad->plan_diario_semana($id_produccion,$semana2['0']->fecha_inicio_semana,$semana2['0']->fecha_fin_semana);
        $id_semana=$semana2['0']->id+1;
        $semana3=$this->model_continuidad->semana_id($id_produccion,$id_semana);   
        if($semana3){
          $plan_seman3=array('fecha1'=>$semana3['0']->fecha_inicio_semana,'fecha2'=>$semana3['0']->fecha_fin_semana,'unidades'=>($this->model_continuidad->plan_diario_semana($id_produccion,$semana3['0']->fecha_inicio_semana,$semana3['0']->fecha_fin_semana)));
          //$plan_seman3=$this->model_continuidad->plan_diario_semana($id_produccion,$semana3['0']->fecha_inicio_semana,$semana3['0']->fecha_fin_semana);
        }
      }
    }
    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);

    /*$data['plan_seman1']=$plan_seman1;
    $data['plan_seman2']=$plan_seman2;
    $data['plan_seman3']=$plan_seman3;*/
    $data['datos']=array($plan_seman1,$plan_seman2,$plan_seman3);

    /*if($semana1){
      $data['semana1_fecha1']=$semana1['0']->fecha_inicio_semana;
      $data['semana1_fecha2']=$semana1['0']->fecha_fin_semana;
    }else{
      $data['semana1_fecha1']='';
      $data['semana1_fecha2']='';
    }  
    
    if($semana2){     
      $data['semana2_fecha1']=$semana2['0']->fecha_inicio_semana;
      $data['semana2_fecha2']=$semana2['0']->fecha_fin_semana;
    }else{
      $data['semana2_fecha1']='';
      $data['semana2_fecha2']='';
    }  

    if($semana3){
      $data['semana3_fecha1']=$semana3['0']->fecha_inicio_semana;
      $data['semana3_fecha2']=$semana3['0']->fecha_fin_semana; 
    }else{
      $data['semana3_fecha1']='';
      $data['semana3_fecha2']='';
    }*/

     echo json_encode($data);
  }


   public function plan_diario($id='',$id_unidad='',$fecha_unidad=''){
    if($id!=''AND $id_unidad!='' AND $fecha_unidad!=''){
      $session = array(
                'id_produccion' =>$id,
                'id_unidad' =>$id_unidad,
                'fecha_unidad' =>$fecha_unidad,
      );
      $this->session->set_userdata($session);
    }

    $produccion=$this->model_plan_produccion->produccion_id($id);
    $capitulos = $this->model_capitulos->capitulos_produccion_2($id);
    $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
    $sql="";
    $sql2 = "";
    $escenas="";
    $unidad="";
            
    $ultima_edicion="";
    $plan_select ="";
    
    $sql2=" e.descripcion AS descripcion_escena, 
            e.guion AS guion_escena,
            d.descripcion AS des_dia, 
            i.descripcion AS des_int, 
            l.nombre AS nom_locacion, 
            s.nombre AS nom_set,
            t.tipo AS tipo,
            (SELECT COUNT(id) FROM retomas_escena where id_escena = e.id) AS retomas, ";
    
     $campos_usuario = $this->campos();
     $campos_usuario[count($campos_usuario)]="";
     $campos = $this->campos();

           if($id_unidad!=''){
              $data=array('id_unidad'=>$id_unidad,'fecha'=>$fecha_unidad);
              if($fecha_unidad==''){
                $escenas=$this->model_plan_diario->escenas_unidad($id_unidad);
              }else{
                $escenas=$this->model_plan_diario->unidad_id_fecha($data,$sql2);
              }
              $unidad=$this->model_plan_diario->unidad_id($id_unidad);
              $fecha=date('Y-m-d');
              $plan_select = $this->model_plan_general->buscar_plan_diario_fecha($fecha_unidad,$id_unidad);
              if($plan_select){
                $ultima_edicion = $this->model_plan_diario->ultima_edicion($plan_select[0]->id);
              }
              if($escenas){
                $user_dia=$this->model_plan_diario->unidad_dia($escenas['0']['id_plan_diario'],$fecha);
                $comentarios=$this->model_plan_diario->comentarios_user($escenas['0']['id_plan_diario']);
                $data['comentarios']=$comentarios;
                if($user_dia){
                  $director_dia=$this->model_admin->user_id($user_dia['0']->id_director);
                  $script_dia=$this->model_admin->user_id($user_dia['0']->id_script);
                  $data['director_dia']=$director_dia;
                  $data['script_dia']=$script_dia;
                }
              }
            }

            /*PERMISOS USUARIO*/
              $id_user = $this->session->userdata('id_production_suite');
              $tipo=$this->session->userdata('tipo_production_suite');
              $user=$this->model_admin->rolUserId($id_user);
              $tipo_rol=$user['0']['id_rol_otros'];
              $permisos_usuario="";
              $subir_imagenes="";
              if($tipo==1 OR $tipo_rol==1 OR $tipo_rol==16){ 
                $permisos_usuario="write";
              }else if($tipo==2 OR $tipo==3 OR $tipo=4 OR $tipo_rol==3 OR $tipo_rol==4 OR $tipo_rol==2 OR $tipo_rol==7 OR $tipo_rol==6 OR $tipo_rol==8){
                $permisos_usuario="read";
              }else{
                redirect($this->lang->lang().'/continuidad/index');
              }
            /*FIN PERMISOS USUARIO*/

            $data['permisos_usuario'] = $permisos_usuario;
            $data['id_produccion'] = $id;
            $data['campos'] = $campos;
            $data['campos_usuario'] = $campos_usuario;
            $data['plan_select'] = $plan_select;
            $data['ultima_edicion']= $ultima_edicion;
            $data['capitulos']= $capitulos; 
            $data['unidad']= $unidad; 
            $data['escenas']= $escenas;
            $data['fecha_unidad'] = $fecha_unidad;
            $director_unidad = $this->model_produccion->get_usuarios(7,$id);
            $script= $this->model_produccion->get_usuarios(1,$id);
            $data['director_unidad']= $director_unidad;
            $data['script']=$script;
            $data['unidades']=$unidades;
            $data['produccion']=$produccion;
            $data['id_unidad']=$id_unidad;
            $data['fecha_unidad']=$fecha_unidad;
             echo json_encode($data);
  }

  
    public function campos(){
      $campos[]="día/noche";
      $campos[]="int/ext";
      $campos[]="locación";
      $campos[]="set";
      $campos[]="loc/est";
      $campos[]="toma";
      return $campos;
    }
  
  
}
