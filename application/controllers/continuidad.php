<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Continuidad extends CI_Controller {


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
      $this->load->helper('cookie');
      $this->_logeo_in();
      
  }

  function _logeo_in(){
    $login_in = $this->session->userdata('login_pruduction_suite_cont');
      if ($login_in !=true){
        //redirect ($this->lang->lang().'/login/login_cont');
      }
  }
  
  public function index(){
    $tipo=$this->session->userdata('tipo_production_suite');
    if($tipo==1){
      $produccion=$this->model_produccion->producciones_all();  
    }else{
      $id_user = $this->session->userdata('id_production_suite');
      $user=$this->model_admin->rolUserId($id_user);
      $continuar=0;
      if($user){
        foreach ($user as $u) {
          if(($u['id_rol_otros']>= 1 AND $u['id_rol_otros']<= 8) OR $u['id_rol_otros']== 16){
            $continuar=1;
            break;
          }
        }
      }
      $tipo_rol=$user['0']['id_rol_otros'];
      
      if(($tipo>= 1 AND $tipo<= 4) OR $continuar=1){ 
        $produccion=$this->model_continuidad->produccion_user($id_user);
      }else{
        redirect($this->lang->lang().'/login/disconnectUser_cont');
      }
    }
    $data['produccion'] = $produccion;
    $data['view']='continuidad/index';
    $this->load->view('includes/template_cont',$data);
  }

  public function menu_plan_diario($id_produccion,$msg=''){
    if($msg==1){
      $msg='<div class="alert-box success">
                       Continuidad creada con exito<a href="" class="close">&times;</a>
                  </div>';
    }
    $fecha=Date('Y-m-d');
    $semana1=$this->model_continuidad->semana_actual($id_produccion,$fecha);
    
    $semana2=null;
    $semana3=null;
    $plan_seman1=null;
    $plan_seman2=null;
    $plan_seman3=null;
    if($semana1){
      $plan_seman1=$this->model_continuidad->plan_diario_semana($id_produccion,$semana1['0']->fecha_inicio_semana,$semana1['0']->fecha_fin_semana);

      $id_semana=$semana1['0']->id+1;
      $semana2=$this->model_continuidad->semana_id($id_produccion,$id_semana);
      if($semana2){
        $plan_seman2=$this->model_continuidad->plan_diario_semana($id_produccion,$semana2['0']->fecha_inicio_semana,$semana2['0']->fecha_fin_semana);
        $id_semana=$semana2['0']->id+1;
        $semana3=$this->model_continuidad->semana_id($id_produccion,$id_semana);   
        if($semana3){
          $plan_seman3=$this->model_continuidad->plan_diario_semana($id_produccion,$semana3['0']->fecha_inicio_semana,$semana3['0']->fecha_fin_semana);
        }
      }
    }
    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);

    $data['produccion']=$produccion;
    $data['plan_seman1']=$plan_seman1;
    $data['plan_seman2']=$plan_seman2;
    $data['plan_seman3']=$plan_seman3;

    if($semana1){
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
    }
    
    $data['id_produccion']=$id_produccion;
    $data['msg']=$msg;
    $data['view']='continuidad/menu_plan_diario';
    $this->load->view('includes/template_cont',$data);
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
            $data['view']='continuidad/plan_diario';
            $this->load->view('includes/template_cont',$data);
  }

  public function plan_diario_escena($id_plan,$id_escena,$id_unidad,$fecha_unidad){
        if($id_plan!=''AND $id_escena!='' AND $id_unidad!='' AND $fecha_unidad!=''){
          $session = array(
            'id_plan' =>$id_plan,
            'id_escena' =>$id_escena,
          );
          $this->session->set_userdata($session);
        }
      $escena=$this->model_continuidad->plan_diario_escena($id_plan,$id_escena);
      $personajes=$this->model_continuidad->personajes_escena($id_escena);
      $per=array();
      $cont=0;
      if($personajes){
        foreach ($personajes as $p) {
            $imagen=$this->model_continuidad->imagen_continuidad($p['id'],$id_escena);
            if($imagen){
              $img=$imagen['0']->imagen;
            }else{
              $imagen2=$this->model_continuidad->imagen_continuidad2($p['id'],$escena['0']->dias_continuidad);
              if($imagen2){
                $img=$imagen2['0']->imagen;
              }else{
                $img=null;
              }
            }
            $per[$cont]=array('id_elemento'=>$p['id'],'nombre'=>$p['nombre'],'rol'=>$p['rol'],'imagen'=>$img);;
            $cont++;  
        }
      }
      $elementos=$this->model_continuidad->elementos_escena($id_escena);
      $produccion=$this->model_continuidad->produccion_idescena($id_escena);
      $comentarios=$this->model_continuidad->escena_comentarios($id_escena);
      $produccion=$this->model_plan_produccion->produccion_id($produccion['0']->id_produccion);
      $unidad=$this->model_plan_diario->unidad_id($id_unidad);
      $escenas_plan=$this->model_continuidad->escenas_plan($id_plan,$id_escena);
      $plan_actual = $this->model_continuidad->plan_id($id_plan);

      /*PERMISOS USUARIO*/
        $id_user = $this->session->userdata('id_production_suite');
        $tipo=$this->session->userdata('tipo_production_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
        $permisos_usuario="";
        $subir_imagenes="";
        if($tipo==1 OR $tipo_rol==1){ 
          $permisos_usuario="write";
        }else if($tipo==2 OR $tipo==3 OR $tipo_rol==7 OR $tipo=4 OR $tipo_rol==2 OR $tipo_rol==3 OR $tipo_rol==6 OR $tipo_rol==8 OR $tipo_rol==16 OR $tipo_rol==4){
          $permisos_usuario="read";
        }else{
          redirect($this->lang->lang().'/continuidad/index');
        }
      /*FIN PERMISOS USUARIO*/

      $data['subir_imagenes'] = $subir_imagenes;
      $data['permisos_usuario'] = $permisos_usuario;
      $data['produccion']=$produccion;
      $data['plan_actual']=$plan_actual;
      $data['elementos']=$elementos;
      $data['id_plan']=$id_plan;
      $data['id_escena']=$id_escena;
      $data['per']=$per;
      $data['escena']=$escena;
      $data['id_produccion']=$produccion['0']->id_produccion;
      $data['id_unidad']=$id_unidad;
      $data['unidad']=$unidad;
      $data['fecha_unidad']=$fecha_unidad;
      $data['comentarios']=$comentarios;
      $data['escenas_plan']=$escenas_plan;
      $data['view']='continuidad/plan_diario_escena';
      $this->load->view('includes/template_cont',$data);
  }

  public static  function calculo_tiempo2($minutos1,$segundos1){
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $minutos1 + $minutos;


      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }
        if($minutos2==0){
          $minutos2='00';
        }
        if($segundos==0){
          $segundos='00';
        }
        $tiempo = $minutos2.":".$segundos;

      return $tiempo;
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


    public static function corta_palabra($palabra,$num) {
      $largo = strlen($palabra);
      $cadena = substr($palabra,0,$num);
      return $cadena;
    }

    public function cargar_imagen($id_produccion){
      $data['id_produccion']=$id_produccion;
      $data['view']='continuidad/cargar_imagen';
      $this->load->view('includes/template_cont',$data);   
    }


    public function crear_continuidad($id_produccion,$libreto="",$escena="",$cont="",$id_elemento=""){
      $id_produccion=$id_produccion;
      $escenas='';
      $personajes='';
      $personaje='';
      if($libreto){
        $escenas=$this->model_continuidad->numero_escenas($libreto);
      }
      if($cont or $cont==0){
        $personajes=$this->model_continuidad->personajes_cont($id_produccion,$cont); 
      }
      
      if($id_elemento){
        $personaje=$this->model_continuidad->personajes_id_elemento($id_produccion,$id_elemento);
      }
      $libretos = $this->model_capitulos->capitulos_produccion($id_produccion);
      $tipo_imagen=$this->model_continuidad->tipo_imagen();
      $data['tipo_imagen']=$tipo_imagen;
      $data['libretos']=$libretos;
      $data['id_produccion']=$id_produccion;
      $data['libreto']=$libreto;
      $data['escena']=$escena;
      $data['escenas']=$escenas;
      $data['cont']=$cont;
      $data['personajes']=$personajes;
      $data['personaje']=$personaje;
      $data['id_elemento']=$id_elemento;
      $data['view']='continuidad/crear_continuidad';
      $this->load->view('includes/template_cont',$data);
    
    }

    public function cargar_personajes(){
      $id_produccion=$_POST['id_produccion'];
      $cont=$_POST['cont'];
      $personajes=$this->model_continuidad->personajes_cont($id_produccion,$cont);
      $data['personajes']=$personajes;
      echo json_encode($data);
    }

    public function numero_escena(){
      $id_libreto=$_POST['id_libreto'];
      $escenas=$this->model_continuidad->numero_escenas($id_libreto);
      $data['escenas']=$escenas;
      echo json_encode($data);
    }

    public function persona_escena(){
      $id_escena=$_POST['id_escena'];
      $personajes=$this->model_continuidad->personajes_escena_id($id_escena);
      $data['personajes']=$personajes;
      echo json_encode($data);
    }

   public function guardar_continuidad(){
      $datos_personajes=$this->input->post('personajes_array');
      $datos_personajes=explode(',', $datos_personajes);
      $escenas=$this->input->post('escenas');
      $dia_continuidad=$this->input->post('dia_continuidad');
      $nota=$this->input->post('nota');
      $tipo_imagen=$this->input->post('tipo_imagen');
      $id_produccion=$this->input->post('id_produccion');
      $imagenes_eliminadas=$this->input->post('imagenes_eliminadas');
      $cadena="";
      $array=explode(',', $imagenes_eliminadas); 
        if(count($_FILES['filesToUpload'])) {
          $cont=0;
              foreach ($_FILES['filesToUpload']['name'] as $temporal) {
                  if (in_array($temporal, $array)){
                     echo 'eliminado :'.$temporal.'<br>';
                  }else{
                        $rutaServidor="images/continuidad";
                         $rutaTemporal= $_FILES['filesToUpload']['tmp_name'][$cont];
                        if($escenas!=0){
                           $id_escena=$escenas;
                        }else{
                            $id_escena='NN';
                        }
                        $nombre=$id_escena.'_'.$dia_continuidad.'_'.date("Y-m-d H:i:s").'_'.$cont.'_'.$temporal;
                        $nombre=str_replace(' ', '_', $nombre);
                        $rutaFicha= $rutaServidor.'/'.$nombre;
                        move_uploaded_file($rutaTemporal, $rutaFicha);
                        $imagen=$rutaFicha;
                        if($escenas!=0){
                          foreach ($datos_personajes as $d) {
                              $id=$d;
                              $escena_has_elemento=$this->model_continuidad->escena_has_elemento($id,$escenas);
                               $continuidad=array(    
                                        'id_escena_has_elemento'=>$escena_has_elemento['0']->id,
                                        'id_elemento'=>null,
                                        'dia_continuidad'=>null,
                                        'id_tipo'=>$tipo_imagen,
                                        'imagen'=>$imagen,
                                        'nota'=>$nota,
                                       );
                            // BLOQUE DE LOGS USUARIO
                            $elemento_selected = $this->model_elementos->buscar_elemento_id($escena_has_elemento[0]->id_elemento);
                            $tipo_imagen_selected = $this->model_continuidad->tipo_imagen_id($tipo_imagen);
                            $escena_selected = $this->model_escenas_2->escena_id($escenas); 
                            $cadena= "\n" . " INSERTADA CONTINUIDAD DÍA  ".$escena_selected[0]->dias_continuidad.' CON ESCENA '.$escena_selected[0]->numero_libreto.'/'.$escena_selected[0]->numero_escena.' Y ELMENTO '. $elemento_selected[0]->nombre.' - IMAGEN '.  base_url($imagen). ' TIPO '.$tipo_imagen_selected[0]->tipo ."\n";
                            $this->user_log($id_produccion,$cadena);
                            // FIN BLOQUE DE LOGS USUARIO
                            $insert_continuidad=$this->model_continuidad->crear_continuidad($continuidad);  
                          }
                        }else{
                            foreach ($datos_personajes as $d) {
                              $id=$d;
                              $continuidad=array(
                                'id_elemento'=>$id,
                                'dia_continuidad'=>$dia_continuidad,
                                'id_tipo'=>$tipo_imagen,
                                'imagen'=>$imagen,
                                'nota'=>$nota,
                              ); 
                              $insert_continuidad=$this->model_continuidad->crear_continuidad2($continuidad);
                              // BLOQUE DE LOGS USUARIO
                              $cadena= "\n" . " INSERTADA CONTINUIDAD DÍA  ".$dia_continuidad.' - IMAGEN '.  base_url($imagen). ' TIPO '.$tipo_imagen_selected[0]->tipo ."\n";
                              $this->user_log($id_produccion,$cadena);
                              // FIN BLOQUE DE LOGS USUARIO
                            }
                        }
                  }
                 $cont++;
              }
        }          
       
       $dia_continuidad=$this->input->post('cantidad_fotos');
       for ($i=1; $i <=$dia_continuidad ; $i++) { 
                        $rutaServidor="images/continuidad";
                        $name="fotos_tomados_".$i;
                       $rutaTemporal= $_FILES[$name]['tmp_name'];
                       if($rutaTemporal){
                            if($escenas!=0){
                                 $id_escena=$escenas;
                              }else{
                                  $id_escena='NN';
                              }
                              $nombre=$id_escena.'_'.$dia_continuidad.'_'.date("Y-m-d H:i:s").'_'.$i.'_'.$_FILES[$name]['name'];
                              $nombre=str_replace(' ', '_', $nombre);
                              $rutaFicha= $rutaServidor.'/'.$nombre;
                              move_uploaded_file($rutaTemporal, $rutaFicha);
                              
                              $imagen=$rutaFicha;
                              if($escenas!=0){
                                foreach ($datos_personajes as $d) {
                                    $id=$d;
                                    $escena_has_elemento=$this->model_continuidad->escena_has_elemento($id,$escenas);
                                     $continuidad=array(    
                                              'id_escena_has_elemento'=>$escena_has_elemento['0']->id,
                                              'id_elemento'=>null,
                                              'dia_continuidad'=>null,
                                              'id_tipo'=>$tipo_imagen,
                                              'imagen'=>$imagen,
                                              'nota'=>$nota,
                                             );
                                  // BLOQUE DE LOGS USUARIO
                                  $elemento_selected = $this->model_elementos->buscar_elemento_id($escena_has_elemento[0]->id_elemento);
                                  $tipo_imagen_selected = $this->model_continuidad->tipo_imagen_id($tipo_imagen);
                                  $escena_selected = $this->model_escenas_2->escena_id($escenas); 
                                  $cadena= "\n" . " INSERTADA CONTINUIDAD DÍA  ".$escena_selected[0]->dias_continuidad.' CON ESCENA '.$escena_selected[0]->numero_libreto.'/'.$escena_selected[0]->numero_escena.' Y ELMENTO '. $elemento_selected[0]->nombre.' - IMAGEN '.  base_url($imagen). ' TIPO '.$tipo_imagen_selected[0]->tipo ."\n";
                                  $this->user_log($id_produccion,$cadena);
                                  // FIN BLOQUE DE LOGS USUARIO
                                  $insert_continuidad=$this->model_continuidad->crear_continuidad($continuidad);  
                                }
                              }else{
                                  foreach ($datos_personajes as $d) {
                                    $id=$d;
                                    $continuidad=array(
                                      'id_elemento'=>$id,
                                      'dia_continuidad'=>$dia_continuidad,
                                      'id_tipo'=>$tipo_imagen,
                                      'imagen'=>$imagen,
                                      'nota'=>$nota,
                                    ); 
                                    $insert_continuidad=$this->model_continuidad->crear_continuidad2($continuidad);
                                    // BLOQUE DE LOGS USUARIO
                                    $cadena= "\n" . " INSERTADA CONTINUIDAD DÍA  ".$dia_continuidad.' - IMAGEN '.  base_url($imagen). ' TIPO '.$tipo_imagen_selected[0]->tipo ."\n";
                                    $this->user_log($id_produccion,$cadena);
                                    // FIN BLOQUE DE LOGS USUARIO
                                  }
                              }
                       }
                              
       }

            
     if($this->session->userdata('id_produccion')){
        if($this->session->userdata('id_plan')){
             redirect($this->lang->lang()."/continuidad/plan_diario_escena/".$this->session->userdata('id_plan')."/".$this->session->userdata('id_escena').'/'.$this->session->userdata('id_unidad').'/'.$this->session->userdata('fecha_unidad'));
        }else{
            redirect($this->lang->lang()."/continuidad/plan_diario/".$this->session->userdata('id_produccion')."/".$this->session->userdata('id_unidad').'/'.$this->session->userdata('fecha_unidad'));    
        }  
        
      }else{
        redirect($this->lang->lang()."/continuidad/menu_plan_diario/".$id_produccion."/1");
      }
    }

    public function elemento($id_elemento,$id_plan,$id_escena,$dia_cont,$id_unidad,$fecha_unidad){
      $escena=$this->model_continuidad->plan_diario_escena($id_plan,$id_escena);
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
        $tipo='';
        $tipo_rol='';
        if($rol){
            if($rol=='Maquillaje'){
              $tipo=2;
              $tipo_rol=2;
              $personajes_cont1=$this->model_continuidad->personajes_cont_all_tipo($id_elemento,$dia_cont,2);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$dia_cont,2);
            }elseif($rol=='Vestuario'){
              $tipo=3;
              $tipo_rol=3;
              $personajes_cont1=$this->model_continuidad->personajes_cont_all_tipo($id_elemento,$dia_cont,3);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$dia_cont,3);
            }else{
              if($rol=='Script' or $rol=='Master'){
                $tipo_rol=1;
              }
              $personajes_cont1=$this->model_continuidad->personajes_cont_all($id_elemento,$dia_cont);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$dia_cont);
            }

        }else{
              $personajes_cont1=$this->model_continuidad->personajes_cont_all($id_elemento,$dia_cont);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$dia_cont);
        }
         $personajes_cont=array();
         $cont=0;
         if($personajes_cont1){
          foreach ($personajes_cont1 as $p) {
            $personajes_cont[$cont]=array('id'=>$p['id_continuidad'],'nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }
         if($personajes_cont2){
          foreach ($personajes_cont2 as $p) {
            $personajes_cont[$cont]=array('id'=>$p['id_continuidad'],'nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }

         $personajes=$this->model_continuidad->personajes_cont_all2($id_elemento,$id_escena);
      /*}*/
      $produccion=$this->model_continuidad->produccion_idescena($id_escena);
      $escenas=$this->model_continuidad->escenas_dia_cont_prod($produccion['0']->id_produccion,$dia_cont,$id_elemento);
      $tipo_imagen=$this->model_continuidad->tipo_imagen();
      $unidad=$this->model_plan_diario->unidad_id($id_unidad);
      $comentarios=$this->model_continuidad->comentario_continuidad($id_elemento,$dia_cont);
      $data['tipo_imagen']=$tipo_imagen;
      $data['personajes_cont']=$personajes_cont;
      $data['personajes']=$personajes;
      $data['escena']=$escena;
      $data['escenas']=$escenas;
      $data['unidad']=$unidad;
      $data['id_escena']=$id_escena;
      $data['id_produccion']=$produccion['0']->id_produccion;
      $data['produccion']=$produccion;
      $data['id_elemento']=$id_elemento;
      $data['id_plan']=$id_plan;
      $data['id_unidad']=$id_unidad;
      $data['fecha_unidad']=$fecha_unidad;
      $data['tipo']=$tipo;
      $data['tipo_rol']=$tipo_rol;      
      $data['comentarios']=$comentarios;
      $data['dia_cont']=$dia_cont;
      $data['view']='continuidad/elementos';
      $this->load->view('includes/template_cont',$data);  
    }

     public function view_elemento_con($id_produccion,$id_elemento,$dia_cont='',$dia_cont2='',$palabra=""){
      $personajes=$this->model_continuidad->personajes_cont_idElemento($id_elemento);
      $elemento=$this->model_continuidad->elemento_id($id_elemento);
        if($dia_cont){
           $cont_ele=$dia_cont;
        }else{
           $cont_ele=$personajes['0']['dias_continuidad'];
        } 

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
        $tipo='';
        $tipo_rol='';
        if($rol){
            if($rol=='Maquillaje'){
              $tipo=2;
              $tipo_rol=2;
              $personajes_cont1=$this->model_continuidad->personajes_cont_all_tipo($id_elemento,$cont_ele,2);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$cont_ele,2);
            }elseif($rol=='Vestuario'){
              $tipo=3;
              $tipo_rol=3;
              $personajes_cont1=$this->model_continuidad->personajes_cont_all_tipo($id_elemento,$cont_ele,3);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$cont_ele,3);
            }else{
              if($rol=='Script' or $rol=='Master'){
                $tipo_rol=1;
              }
              $personajes_cont1=$this->model_continuidad->personajes_cont_all($id_elemento,$cont_ele);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$cont_ele);
            }
        }else{
              $personajes_cont1=$this->model_continuidad->personajes_cont_all($id_elemento,$cont_ele);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$cont_ele);
        }

         
         $personajes_cont=array();
         $cont=0;
         if($personajes_cont1){
          foreach ($personajes_cont1 as $p) {
            $personajes_cont[$cont]=array('id'=>$p['id_continuidad'],'nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }
         if($personajes_cont2){
          foreach ($personajes_cont2 as $p) {
            $personajes_cont[$cont]=array('id'=>$p['id_continuidad'],'nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }

      

      $comentarios=$this->model_continuidad->comentario_continuidad($id_elemento,$cont_ele);
      $escenas=$this->model_continuidad->escenas_dia_cont_prod($id_produccion,$cont_ele,$id_elemento);
      $tipo_imagen=$this->model_continuidad->tipo_imagen();
      $data['cont_ele']=$cont_ele;
      $data['elemento']=$elemento;
      $data['tipo_imagen']=$tipo_imagen;
      $data['personajes_cont']=$personajes_cont;
      $data['personajes']=$personajes;
      $data['id_elemento']=$id_elemento;
      $data['dia_cont']=$cont_ele;
      $data['id_produccion']=$id_produccion;
      $data['personajes']=$personajes;
      $data['escenas']=$escenas;
      $data['tipo']=$tipo;
      $data['tipo_rol']=$tipo_rol;
      $data['comentarios']=$comentarios;
      $data['palabra']=$palabra;
      $data['dia_cont2']=$dia_cont2;
      $data['view']='continuidad/view_elemento_con';
      $this->load->view('includes/template_cont',$data);  
    }


    public function elemento_id($id_elemento){
         $personajes_cont1=$this->model_continuidad->personajes_cont_all($id_elemento,$dia_cont);
         $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$dia_cont);
         $personajes_cont=array();
         $cont=0;
         if($personajes_cont1){
          foreach ($personajes_cont1 as $p) {
            $personajes_cont[$cont]=array('nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }
         if($personajes_cont2){
          foreach ($personajes_cont2 as $p) {
            $personajes_cont[$cont]=array('nombre'=>$p['nombre'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
            $cont++;
          }
         }

         $personajes=$this->model_continuidad->personajes_cont_all2($id_elemento,$id_escena);

      $produccion=$this->model_continuidad->produccion_idescena($id_escena);
      $escenas=$this->model_continuidad->escenas_dia_cont_prod($produccion['0']->id_produccion,$dia_cont,$id_elemento);
      $tipo_imagen=$this->model_continuidad->tipo_imagen();
      $unidad=$this->model_plan_diario->unidad_id($id_unidad);
      $data['tipo_imagen']=$tipo_imagen;
      $data['personajes_cont']=$personajes_cont;
      $data['personajes']=$personajes;
      $data['escena']=$escena;
      $data['escenas']=$escenas;
      $data['unidad']=$unidad;
      $data['id_escena']=$id_escena;
      $data['id_produccion']=$produccion['0']->id_produccion;
      $data['produccion']=$produccion;
      $data['id_elemento']=$id_elemento;
      $data['id_plan']=$id_plan;
      $data['id_unidad']=$id_unidad;
      $data['fecha_unidad']=$fecha_unidad;
      $data['view']='continuidad/elementos';
      $this->load->view('includes/template_cont',$data);  
    }

    public function guardar_datos_plan(){
      $id_plan_escena=$_POST['id_plan_escena'];
      $hora=$_POST['d'];
      $tipo=$_POST['tipo'];
      $datos=array(
        'id'=>$id_plan_escena,
        'hora'=>$hora,
        'tipo'=>$tipo,
      );
      $plan=$this->model_continuidad->plan_diario_escena_update($datos);
      $plan_actual = $this->model_continuidad->id_plan_by_escena($id_plan_escena);
      if($tipo==1){
        $evento='COMIENZO ENSAYO';
      }elseif ($tipo==2) {
        $evento='COMIENZO PRODUCCIÓN';   
      }else{
        $evento='FIN PRODUCCIÓN';
      }
      $plan_selected = $this->model_plan_general->plan_diario_id($plan_actual[0]->id_plan_diario);
      $escena_selected = $this->model_continuidad->escena_by_plan($id_plan_escena);
      $cadena= "\n" . " ACTUALIZADA ESCENA ".$escena_selected[0]->numero_libreto.'/'.$escena_selected[0]->numero_escena." EN PLAN DIARIO U" .$plan_selected[0]->numero_unidad.' - '.date("d-M-Y",strtotime($plan_selected[0]->fecha_inicio_f)) . ' ' . $evento.' '.$hora;
      $this->user_log($escena_selected[0]->id_produccion,$cadena);
      $this->log_plan_diario($plan_actual[0]->id_plan_diario);
      $data['datos']=1;
      echo json_encode($data);
    }

    public function guardar_comentario_plan(){
      $comentario=$_POST['comentario'];
      $id_plan=$_POST['id_plan'];
      $fecha=Date('Y-m-d');
      $id_user = $this->session->userdata('id_pruduction_suite');
      $plan=$this->model_plan_diario->insert_coment_user($id_user,$comentario,$id_plan,$fecha);
      $comentarios=$this->model_continuidad->plan_diario_comentarios($id_plan);
      $plan_selected = $this->model_plan_general->plan_diario_id($id_plan);
      $cadena .= "\n" . " AGREGADO COMENTARIO ".$comentario." A PLAN DIARIO U" .$plan_selected[0]->numero_unidad.' - '.date("d-M-Y",strtotime($plan_selected[0]->fecha_inicio_f))."\n";
      $this->user_log($plan_selected[0]->id_produccion,$cadena);
      $data['comentarios']=$comentarios;
      echo json_encode($data);      
    }

  public function guardar_comentario_continuidad(){
      $comentario=$_POST['comentario'];
      $continuidad=$_POST['continuidad'];
      $id_elemento=$_POST['id_elemento'];
      $fecha=Date('Y-m-d');
      $id_user = $this->session->userdata('id_production_suite');
      $datos=array(
              'comentario'=>$comentario,
              'fecha'=>$fecha,
              'id_elemento'=>$id_elemento,
              'continuidad'=>$continuidad,
              'id_user'=>$id_user,
        );
      $this->model_continuidad->insert_comentario_continuidad($datos);
      $elemento_selected = $this->model_elementos->buscar_elemento_id($id_elemento);
      $cadena = "\n" . " AGREGADO COMENTARIO ".$comentario." A ELEMENTO" .$elemento_selected[0]->nombre.' DÍA CONTINUIDAD  '.$continuidad;
      $this->user_log($elemento_selected[0]->id_produccion,$cadena);
      $comentarios=$this->model_continuidad->comentario_continuidad($id_elemento,$continuidad);

      $data['comentarios']=$comentarios;
      echo json_encode($data);      
    }

     public function insert_comentario_imagenset(){
      $comentario=$_POST['comentario'];
      $id_set=$_POST['id_set'];
      $set=$_POST['set'];
      $id_produccion=$_POST['id_produccion'];
      $fecha=Date('Y-m-d');
      $id_produccion=$_POST['id_produccion'];
      $id_user = $this->session->userdata('id_production_suite');
      $datos=array(
              'comentario'=>$comentario,
              'fecha'=>$fecha,
              'id_set'=>$id_set,
              'id_user'=>$id_user,
        );
      $this->model_continuidad->insert_comentario_imagenset($datos);
      $cadena = "\n" . " AGREGADO COMENTARIO ".$comentario." A set " .$set;
      $this->user_log($id_produccion,$cadena);
      $comentarios = $this->model_continuidad->comentarios_set($id_set);

      $data['comentarios']=$comentarios;
      echo json_encode($data);      
    }

    

    public function guardar_comentario_escena(){
      $comentario=$_POST['comentario'];
      $id_escena=$_POST['id_escena'];
      $fecha=Date('Y-m-d');
      $id_user = $this->session->userdata('id_production_suite');

      $plan=$this->model_plan_diario->insert_coment_escena($id_user,$comentario,$id_escena,$fecha);
      // BLOQUE LOG USUARIO
      $escena_selected = $this->model_escenas_2->escena_id($id_escena);
      $cadena = "\n" . " AGREGADO COMENTARIO ".$comentario." A ESCENA" .$escena_selected[0]->numero_libreto.'/'.$escena_selected[0]->numero_escena;
      $this->user_log($escena_selected[0]->id_produccion,$cadena);
      // FIN BLOQUE LOG USUARIO
      $comentarios=$this->model_continuidad->escena_comentarios($id_escena);
      $data['comentarios']=$comentarios;
      echo json_encode($data);   
    }

  public function guardar_tiempo_real(){
      $minutos_reales=$_POST['minutos_reales'];
      $segundos_reales=$_POST['segundos_reales'];
      $id_escena=$_POST['id_escena'];
      $id_produccion=$_POST['id_produccion'];
      $id_plan=$_POST['id_plan'];
      $id_unidad=$_POST['id_unidad'];

      $plan_actual = $this->model_plan_general->plan_diario_id($id_plan);
      $datos=array(
        'id_plan'=>$id_escena,
        'tipo'=>1,
        'valor'=>$minutos_reales,
        'valor2'=>$segundos_reales,
        'idunidad'=>$id_unidad,
        'fecha_plan' => date("Y-m-d",strtotime($plan_actual[0]->fecha_inicio_f)),
      );
      // BLOQUE LOG USUARIO
      $escena_selected = $this->model_escenas_2->escena_id($id_escena);
      $cadena = "\n" . " ESCENA " . $escena_selected[0]->numero_libreto .' / '. $escena_selected[0]->numero_escena . ' ASIGNADO TIEMPO REAL ' . $minutos_reales.':'.$segundos_reales  ." EN PLAN DIARIO UNIDAD ".$plan_actual[0]->numero_unidad.' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
      
      // FIN BLOQUE LOG USUARIO

      $datos=$this->model_plan_diario->guardar_elementos($datos);
      $this->actualizar_tiempos($id_escena);
      $escena=$this->model_escenas_2->escena_id($id_escena);
      $capitulo = $this->model_capitulos->bucar_capitulo_id($escena[0]->id_capitulo);
      $this->model_escenas_2->actualizar_producidas($escena[0]->id_capitulo);
      $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($escena[0]->id_capitulo);
      if($capitulo[0]['escenas_escritas']<=sizeof($producidas)){
        $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA A ESTADO PRODUCIDO, EN PLAN DIARIO UNIDAD ".$plan_actual[0]->numero_unidad.' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
        $this->model_capitulos->capitulo_estado($capitulo[0]['id_produccion'],$capitulo[0]['id'], 5);
      }

      $this->user_log($id_produccion,$cadena);

      /*GUARDAR LOS MINUTOS REALES EN LA PRODUCCION*/
      $escenas = $this->model_escenas_2->sumar_tiempos_produccion($id_produccion);
      $minutos = $this->calculo_tiempo($escenas);
      $this->model_plan_diario->actualizar_minutos_reales($id_produccion,$minutos);
      /*

      /*GUARDAR LOS MINUTOS REALES EN LA PRODUCCION*/
      $escenas = $this->model_escenas_2->sumar_tiempos_produccion($id_produccion);
      $minutos = $this->calculo_tiempo($escenas);
      $this->model_plan_diario->actualizar_minutos_reales($id_produccion,$minutos);
      $f=$_POST['f'];
      if($f==1){
        $id_plan_escena=$_POST['id_plan_escena'];
        $hora=Date('H:i:s');
        $datos=array(
                        'id'=>$id_plan_escena,
                        'hora'=>$hora,
                        'tipo'=>2,
                      );
        $plan=$this->model_continuidad->plan_diario_escena_update($datos);
      }
      $this->log_plan_diario($id_plan);
      $fin_produccion=$_POST['fin_produccion'];
      if($fin_produccion==1){
        $hora=Date('H:i:s');
        $id_plan_escena=$_POST['id_plan_escena'];
        $datos=array(
          'id'=>$id_plan_escena,
          'hora'=>$hora,
          'tipo'=>3,
        );  
        $plan=$this->model_continuidad->plan_diario_escena_update($datos);
      }
      ///////////////////
      $data['estado']=1;
      echo json_encode($data);  
    }

  public function actualizar_tiempos($idescena){
    $escena = $this->model_escenas_2->escena_id($idescena);
    $idcapitulo = $escena[0]->id_capitulo;
    $escenas = $this->model_escenas_2->sumar_tiempos_reales($idcapitulo);
    $tiempo = $this->calculo_tiempo($escenas);
    $data=array(
        'duracion_real'=>$tiempo,
    );
    $this->db->where('id',$idcapitulo);
    $this->db->update('produccion_has_capitulos',$data);

    $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);

    $escenas_canceladas=$this->model_escenas->escenas_canceladas($idcapitulo);
      $t=$escenas_canceladas['0']->total;
      
      if($t>0){
        $total=$capitulo[0]['escenas_escritas']-$t;
      }else{
        $total=$capitulo[0]['escenas_escritas'];  
      }

      $escenas_producidas = count($this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo));
      if($total <= $escenas_producidas){

   // $escenas_producidas = count($this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo));
    //if($capitulo[0]['escenas_escritas'] == $escenas_producidas){
      $data=array(
        'estado'=>5,
      );
      $this->db->where('id',$idcapitulo);
      $this->db->update('produccion_has_capitulos',$data);
    }
  }

  public function calculo_tiempo($escenas){
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos += $escenas[0]->segundos;

      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $escenas[0]->minutos + $minutos;

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }

      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      $tiempo = $minutos2.":".$segundos;
      return $tiempo;
    }

    public function elementos_diacont(){
      $id_produccion=$_POST['id_produccion'];
      
      $continuidad=$_POST['cont'];
      $id_elemento=$_POST['id_elemento'];
      $tipo=$_POST['tipo'];
      if($tipo!='0'){
              $personajes_cont1=$this->model_continuidad->elementos_diacont_tipo($id_produccion,$continuidad,$id_elemento,$tipo);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$continuidad,$tipo);
        }else{
              $personajes_cont1=$this->model_continuidad->elementos_diacont($id_produccion,$continuidad,$id_elemento);
              $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$continuidad);
        }

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
      $tipo_rol='';
      if($rol=='Maquillaje'){
        $tipo_rol=2;
      }elseif($rol=='Vestuario'){
        $tipo_rol=3;
      }elseif($rol=='Script' or $rol=='Master'){
        $tipo_rol=1;
      }
      $personajes_cont=array();
      $cont=0;
      if($personajes_cont1){
        foreach ($personajes_cont1 as $p) {
          $personajes_cont[$cont]=array('id_continuidad'=>$p['id_continuidad'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
          $cont++;
        }
      }
      if($personajes_cont2){
        foreach ($personajes_cont2 as $p) {
          $personajes_cont[$cont]=array('id_continuidad'=>$p['id_continuidad'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
          $cont++;
        }
      }
      $escenas=$this->model_continuidad->escenas_dia_cont_prod($id_produccion,$continuidad,$id_elemento);
      $comentarios=$this->model_continuidad->comentario_continuidad($id_elemento,$continuidad);
      $data['elementos']=$personajes_cont;
      $data['escenas']=$escenas;
      $data['comentarios']=$comentarios;
      $data['tipo_rol']=$tipo_rol;
      echo json_encode($data);  
    }
    public function elementos_diacont_tipo(){
      $id_produccion=$_POST['id_produccion'];
      $continuidad=$_POST['cont'];
      $id_elemento=$_POST['id_elemento'];
      $tipo=$_POST['tipo'];
      if($tipo==0){
        $personajes_cont1=$this->model_continuidad->elementos_diacont($id_produccion,$continuidad,$id_elemento);
        $personajes_cont2=$this->model_continuidad->personajes_cont_all_general($id_elemento,$continuidad);
      }else{
        $personajes_cont1=$this->model_continuidad->elementos_diacont_tipo($id_produccion,$continuidad,$id_elemento,$tipo);  
        $personajes_cont2=$this->model_continuidad->personajes_cont_all_general_tipo($id_elemento,$continuidad,$tipo);
      }
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
      $tipo_rol='';
      if($rol=='Maquillaje'){
        $tipo_rol=2;
      }elseif($rol=='Vestuario'){
        $tipo_rol=3;
      }elseif($rol=='Script' or $rol=='Master'){
        $tipo_rol=1;
      }
      $personajes_cont=array();
      $cont=0;
      if($personajes_cont1){
        foreach ($personajes_cont1 as $p) {
          $personajes_cont[$cont]=array('id_continuidad'=>$p['id_continuidad'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
          $cont++;
        }
      }
      if($personajes_cont2){
        foreach ($personajes_cont2 as $p) {
          $personajes_cont[$cont]=array('id_continuidad'=>$p['id_continuidad'],'id_tipo'=>$p['id_tipo'],'nota'=>$p['nota'],'imagen'=>$p['imagen'],'tipo'=>$p['tipo']);
          $cont++;
        }
      }
      $escenas=$this->model_continuidad->escenas_dia_cont_prod($id_produccion,$continuidad,$id_elemento);
      $data['elementos']=$personajes_cont;
      $data['escenas']=$escenas;
      $data['tipo_rol']=$tipo_rol;
      echo json_encode($data);  
    }    


   public function guardar_corteGenal(){
      $id_plan=$_POST['id_plan'];
      $hora=$_POST['hora'];
      $minutos=$_POST['minutos'];
      $plan=$this->model_continuidad->guardar_corteGenal($id_plan,$hora,$minutos);
      // BLOQUE LOG USUARIO
      $plan_actual = $this->model_plan_general->plan_diario_id($id_plan);
      $cadena = "\n" ."ASIGNADO CORTE GENERAL ".$hora.':'.$minutos." EN PLAN DIARIO UNIDAD ".$plan_actual[0]->numero_unidad.' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
      $this->user_log($plan_actual['0']->id_produccion,$cadena);
      // FIN BLOQUE LOG USUARIO
      $data['plan']=$plan;
      echo json_encode($data); 
   }

    public function buscar_estado_plan(){
    $idunidad = $_POST['idunidad'];
    $fecha = $_POST['fecha'];
    $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("Y-m-d",strtotime($fecha)), $idunidad);
    if($plan_diario){
      if($plan_diario[0]->estado==5){
        $estado = false;
      }else{
        $estado = true;
      }
    }else{
      $estado = true;
    }
    $data['estado']=$estado;
    echo json_encode($data);
  }

  public function buscar_asignadas(){
    $idescena = $_POST['idescena'];
    $resultado = false;
    $idunidad = $_POST['idunidad'];
    $fecha = $_POST['fecha'];
    $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date('Y-m-d',strtotime($fecha)),$idunidad);
    $id_plan =0;
    if($plan_diario){
      $id_plan = $plan_diario[0]->id;
    }
    $escena = $this->model_plan_general->plan_diario_by_escena_id($idescena,$id_plan);

    if($escena!=false){
      $resultado = true;
      $plan = $this->model_plan_general->plan_diario_id($escena[0]->id_plan_diario);
      $data['idplan']=$plan[0]->id_plan_diario;
      $data['plan']=$plan;
    }
    $data['resultado']=$resultado;
    echo json_encode($data);
  } 

     public function asignar_plan_diario(){
      $idproduccion = $this->input->post('idproduccion');
      $fecha_inicio = $this->input->post('fecha_inicio');
      $idunidad = $this->input->post('unidad');
      $idescenas = $this->input->post('idescena');
      $temporal = $this->input->post('idplanes');
      $cadena_insert="";

      $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("Y-m-d",strtotime($fecha_inicio)), $idunidad);
      if($plan_diario==false){
        $data=array(
            'fecha_inicio' => date("Y-m-d",strtotime($fecha_inicio)),
            'id_unidad' => $idunidad, 
            'estado' => 2
        );
        $this->model_plan_general->crear_plan_diario($data);
        $idplan_diario = mysql_insert_id();
        $this->log_plan_diario($idplan_diario);
        $ultima_edicion = $this->model_plan_diario->ultima_edicion($idplan_diario);
        $cadena_insert .= "CREADO POR: ".strtoupper($ultima_edicion[0]->nombre)." ".strtoupper($ultima_edicion[0]->apellido);
        $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("Y-m-d",strtotime($fecha_inicio)), $idunidad);
      }else{
        $idplan_diario = $plan_diario[0]->id;
        $this->log_plan_diario($idplan_diario);
        $ultima_edicion = $this->model_plan_diario->ultima_edicion($idplan_diario);
        $cadena_insert .= "ESCENAS AGREGADAS POR: ".strtoupper($ultima_edicion[0]->nombre)." ".strtoupper($ultima_edicion[0]->apellido);
      }

      /*GUARDA EN ARCHIVO LOG*/
      $unidad_plan = $this->model_plan_diario->unidad_id($plan_diario[0]->id_unidad);
      $cadena = "\n" . ' PLAN UNIDAD '.$unidad_plan[0]['numero'].' '. date('d-M-Y', strtotime($plan_diario[0]->fecha_inicio)) .$cadena_insert. ".";
      $this->user_log($unidad_plan[0]['id_produccion'],$cadena);
      

      $escenas = explode(",",$idescenas);
      $cadena="";
      for ($i=0; $i < sizeof($escenas); $i++) { 
        if($temporal!=""){
          $this->borrar_plan_diario($temporal, $idplan_diario,$escenas[$i]);
        }
        if($escenas[$i]!=""){
          $escena_actual=$this->model_escenas_2->escena_id($escenas[$i]);
          $capitulo_actual = $this->model_capitulos->bucar_capitulo_id($escena_actual[0]->id_capitulo);
          $cadena .= "\n" . " ESCENA ".$capitulo_actual[0]['numero'].' / '.$escena_actual[0]->numero_escena.' AGREAGADA AL PLAN UNIDAD '.$unidad_plan[0]['numero'].' '.date("d-M-Y",strtotime($fecha_inicio));
          
          if($plan_diario[0]->estado==1){
            $this->model_plan_diario->completar_plan($plan_diario[0]->id,2);
            $cadena .= "\n" . "PLAN DIARIO UNIDAD ".$unidad_plan[0]['numero'].' '.date("d-M-Y",strtotime($fecha_inicio)).' PASA A ESTADO ABIERO PRIVADO.';
          }
          $numero = $this->db->query("SELECT COUNT(id)+1 AS numero from plan_diario_has_escenas_has_unidades WHERE id_plan_diario = ".$idplan_diario.";");
          $numero = $numero->result();
            if(!$this->model_plan_general->escena_plan($idplan_diario,$escenas[$i])){
            $data=array(
              'id_plan_diario'  => $idplan_diario,
              'id_escena' => $escenas[$i],
              'orden' => $numero[0]->numero
            );
            $this->model_plan_general->asignar_plan_diario($data);
          }
          $estado = $this->model_escenas_2->escena_by_id($escenas[$i]);
          $estado_cambio = $estado[0]->estado;
          switch ($estado_cambio) {
            case 1:
              $escena_retoma = $this->model_escenas_2->escena_by_id($escenas[$i]);
              $estado_cambio = 12;
              break;
            case 2:
              $estado_cambio = 14;
              break;
            case 5:
              $estado_cambio = 4;
              break;
            case 7:
              $estado_cambio = 6;
              break;
            case 9:
              $estado_cambio = 8;
              break;
            case 11:
              $estado_cambio = 10;
              break;
            default:
             $estado_cambio = $estado[0]->estado;
            break;
          }
          $this->user_log($unidad_plan[0]['id_produccion'],$cadena);
          $this->model_escenas_2->cambia_estado($escenas[$i],$estado_cambio);
        }
      }

    redirect($this->lang->lang().'/continuidad/plan_diario/'.$idproduccion.'/'.$idunidad.'/'.$fecha_inicio);

  } 

  /*INSERCION DE ULTIMA PERSONA EDITAR PLAN*/
  public function log_plan_diario($idplan){
    $idusuario = $this->session->userdata('id_production_suite');
    $this->model_plan_diario->log_plan_diario($idplan,$idusuario); 
  }
  /*FIN INSERCION DE ULTIMA PERSONA EDITAR PLAN*/

  /* FUNCION VERIFICACION DE CRUCE DE ELEMENTOS */
  public function cruce_elementos($id, $idunidad, $fecha_unidad){
      $cruce="";
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
      $sql=", ele1.id AS id_elemento";
      if($unidades){        
        for ($i=1; $i <= count($unidades); $i++) { 
          $sql .= ",(SELECT group_concat(produccion_has_capitulos.numero,'/',escena.numero_escena separator ' - ')
                  FROM escenas_has_elementos
                  INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                  INNER JOIN plan_diario_has_escenas_has_unidades 
                  ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
                  INNER JOIN elemento ele2 ON ele2.id = escenas_has_elementos.id_elemento
                  INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id 
                  INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                  WHERE plan_diario.id_unidad = ".$unidades[$i-1]['id']." AND plan_diario.fecha_inicio = '".$fecha_unidad."' AND ele2.id= ele1.id) AS unidad".$i;
        }
      }
      $cruce = $this->model_plan_diario->cruces_elementos($id,$fecha_unidad,$sql);

      $idpersonajes = $this->model_plan_general->categoria_produccion($id, 'Personaje');
      $data['idpersonajes'] = $idpersonajes[0]->id;
      $data['cruce']=$cruce;
      $data['fecha_unidad'] = $fecha_unidad;
      $data['produccion'] = $produccion;
      $data['id_produccion']=$produccion['0']->id_produccion;
      $data['unidad'] = $idunidad;
      $data['unidades']=$unidades;
      $data['view']='continuidad/cruce_elementos';
      $this->load->view('includes/template_cont',$data);
    }


    public function guardar_comentario(){
      $id_plan=$_POST['id_plan'];
      $comentario=$_POST['comentario'];
      $id_user=$this->session->userdata('id_production_suite');
      $fecha=date('Y-m-d');
      $comen=$this->model_plan_diario->insert_coment_user($id_user,$comentario,$id_plan,$fecha);
      $comen=$this->model_plan_diario->comentarios_user($id_plan);
      $plan_diario = $this->model_plan_general->plan_diario_id($id_plan);
      $unidad = $this->model_plan_diario->unidad_id($plan_diario[0]->id_unidad);
      $cadena .= "\n" . " AGREGADO COMENTARIO ". "\n" .$comentario_user. "\n" ." A PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_diario[0]->fecha_inicio_f)).'.';
      $this->user_log($unidad[0]['id_produccion'],$cadena);
      $data['comentarios']=$comen;
      echo json_encode($data); 
    }

    public function guardar_incioEnsayo_multiescenas(){
      $escenas=$_POST['escenas'];
      $hora=$_POST['hora'];
       if($escenas){
        foreach ($escenas as $e) {
          $escena=$this->model_continuidad->multiescena($e,$hora);
          $escena_selected = $this->model_continuidad->escena_by_plan($id_plan_escena);
          $plan_selected = $this->model_plan_general->plan_diario_id($escena_selected[0]->id_plan_diario);
          $cadena= "\n" . " ACTUALIZADA ESCENA ".$escena_selected[0]->numero_libreto.'/'.$escena_selected[0]->numero_escena." EN PLAN DIARIO U" .$plan_selected[0]->numero_unidad.' - '.date("d-M-Y",strtotime($plan_selected[0]->fecha_inicio_f)) . ' INICIO ENSAYO '.$hora;
        }
        $data['respuesta']='1';
      }else{
        $data['respuesta']='0';
      }
      echo json_encode($data);   
    }

    public function elementos($id_produccion,$cont="",$palabra=""){
      $rol_actores_elementos=$this->model_continuidad->rol_actores_elementos();
      $data['rol_actores_elementos']=$rol_actores_elementos;
      $data['id_produccion']=$id_produccion;
      /*PERMISOS USUARIO*/
        $id_user = $this->session->userdata('id_production_suite');
        $tipo=$this->session->userdata('tipo_production_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
        $permisos_usuario="";
        if($tipo==1 OR $tipo_rol==1 OR $tipo_rol==3 OR $tipo_rol==4 OR $tipo_rol==16){ 
          $permisos_usuario="write";
        }else if($tipo==2 OR $tipo==3 OR $tipo=4 OR $tipo_rol==2 OR $tipo_rol==7 OR $tipo_rol==6 OR $tipo_rol==8){
          $permisos_usuario="read";
        }else{
          redirect($this->lang->lang().'/continuidad/index');
        }
      /*FIN PERMISOS USUARIO*/
      $locaciones=$this->model_escenas->locacion($id_produccion);
      $data['permisos_usuario'] = $permisos_usuario;
      $data['locaciones'] = $locaciones;
      $data['palabra'] = $palabra;
      $data['cont'] = $cont;
      $data['view'] = 'continuidad/elementos_continuidad';
      $this->load->view('includes/template_cont',$data);
    }

    public function elementos_produccion_rol(){
      $rol=$_POST['rol'];
      $id_produccion=$_POST['id_produccion'];
      $limit=$_POST['limit'];
      $continuidad=$_POST['continuidad'];
      $like=$_POST['like'];
      $total='';
      if($continuidad or $continuidad=='0'){
         $personajes=$this->model_continuidad->personajes_cont_rol($id_produccion,$continuidad,$rol,$limit,$like); 
         $total=$this->model_continuidad->personajes_cont_rol_total($id_produccion,$continuidad,$rol,$like); 
      }else{
        if($like){
          $personajes=$this->model_continuidad->elementos_rol_like($id_produccion,$rol,$limit,$like);  
        }else{
          $personajes=$this->model_continuidad->elementos_rol($id_produccion,$rol,$limit);
          $total=$this->model_continuidad->elementos_rol_total($id_produccion,$rol,$limit);
        }        
      }
      $per=array();
      $cont=0;
      if($personajes){
        foreach ($personajes as $p) {
            $imagen=$this->model_continuidad->imagen_continuidad_elemento($p['id_elemento']);
            if($imagen){
              $img=$imagen['0']->imagen;
              $continuidad=$imagen['0']->dia_continuidad;
            }else{
              $imagen2=$this->model_continuidad->imagen_continuidad_escena_has_elemento($p['id_elemento']);
              if($imagen2){
                $img=$imagen2['0']->imagen;
                $continuidad=$imagen2['0']->dias_continuidad;
              }else{
                $img=$imagen2['0'];
                $continuidad=null;
              }

            }
            $per[$cont]=array('continuidad'=>$continuidad,'id_elemento'=>$p['id_elemento'],'nombre'=>$p['nombre'],'rol'=>$p['rol'],'imagen'=>$img);;
            $cont++;  
        }
      }
      $data['personajes']=$per;
      $data['total']=$total;
      echo json_encode($data);
    }

     public function buscar_escenas_capitulo(){
      $idcapitulo = $_POST['idcapitulo'];
      $idplan = $_POST['idplan'];
      $cadena_escenas ="";
      $escenas = $this->model_plan_diario->buscar_escena_plan_capitulo($idcapitulo,$idplan);
      if($escenas){
        foreach ($escenas as $escena) {   
            if($escena->id_plan_diario!="" AND $escena->id_plan_diario!=null){
              $cadena_escenas .= '<option disabled value="'.$escena->id_escena.'">'.$escena->numero_escena.' - Pertenece al plan </option>';
            }else{
              $cadena_escenas .= '<option value="'.$escena->id_escena.'"';
              $escena_plan = $this->model_plan_diario->buscar_planes_escena($escena->id,$idplan);
              if($escena_plan!=false AND $escena_plan[0]->estado_plan>2){
                $cadena_escenas .= ' disabled >'.$escena->numero_escena.' - plan Unidad '.$escena_plan[0]->numero_unidad.' -- '.date("d-M-Y",strtotime($escena_plan[0]->fecha_inicio)).' - Abierto</option>';
              }else{
                $tem_ex = explode('_',$escena->planes_abiertos);
                if($escena->planes_abiertos AND isset($tem_ex[1]) AND (strtotime($tem_ex[0])<=strtotime(date("Y-m-d")))){
                  $cadena_escenas .= ' disabled >'.$escena->numero_escena.' - plan Unidad '.$tem_ex[1].' -- '.date("d-M-Y",strtotime($tem_ex[0])).' - Abierto</option>';
                }else{
                  $cadena_escenas .= ' >'.$escena->numero_escena.'</option>';
                }
              }
            }
        }
      }
      $data['cadena_escenas']=$cadena_escenas;
      echo json_encode($data);
    }


  public function cerrar_plan(){
      $idplan=$this->input->post('idplan');
      $this->model_plan_diario->liberar_escenas($idplan);
      $escenas_retoma = $this->model_plan_diario->buscar_escenas_retoma($idplan); 
      if($escenas_retoma){
        foreach ($escenas_retoma as $escena_retoma) {
          $datos = array(
              'estado'=>1,
          );
          $this->db->where('id',$escena_retoma->id);
          $this->db->update('escena',$datos);
        }
      }
      $data['resultado'] = 1;
      echo json_encode($data);
  }


public function completar_plan(){
    $id_plan=$_POST['id_plan'];
    $plan_diario = $this->model_plan_general->plan_diario_id($id_plan);

    if($plan_diario[0]->estado_plan==2){
      $estado = 3;
    }else{
      $estado = 5;
    }
    $this->log_plan_diario($id_plan);
    $completar_plan=$this->model_plan_diario->completar_plan($id_plan,$estado);
    if($completar_plan){
      $data['completar_plan']=1;
    }else{
      $data['completar_plan']=0;
    }

    $cadena_insert = ""; 
    switch ($estado) {
      case 3:
          $cadena_insert.= " PUBLICADO";
        break;
      case 5:
          $cadena_insert.= " CERRADO";
      break;
    }

    /*GUARDA EN ARCHIVO LOG*/
    $unidad_plan = $this->model_plan_diario->unidad_id($plan_diario[0]->id_unidad);
    $cadena = "\n" . ' PLAN UNIDAD '.$unidad_plan[0]['numero'].' '. date('d-M-Y', strtotime($plan_diario[0]->fecha_inicio)) .$cadena_insert. ".";
    $this->user_log($unidad_plan[0]['id_produccion'],$cadena);
    $data['estado']=$estado;
    echo json_encode($data);
   }


    public function desproducir_escena($idescena,$fecha_plan,$unidad,$idproduccion,$id_retoma=0){
      $escena = $this->model_escenas_2->escena_id($idescena);
      $estado = 12;
      $fecha='';
      $fecha='';
      $tiempo_real_minutos=0;
      $tiempo_real_segudos=0;
      $unidad_retoma='';
        switch ($escena[0]->id_tipo_locacion) {
        case 1:
          $estado = 8;
          break;
        case 2:
          $estado = 6; 
          break;
        }

        if($escena[0]->id_toma_ubicacion==1){
          $estado = 6; 
        }

        if($escena[0]->id_flasback==1){
          $estado = 10;
        }

        if($escena[0]->id_producida==1){
          $estado = 1;
        }
        if($escena[0]->estado==2 or $id_retoma){
          $this->model_plan_diario->eliminar_retoma($id_retoma);
          $retoma=$this->model_plan_diario->ultima_retoma($idescena);
          if($retoma){
            $estado=1;
            $fecha=$retoma[0]->fecha_produccion;
            $tiempo_real=$retoma[0]->tiempo;
            $t=explode(':', $tiempo_real);
            $tiempo_real_minutos=$t[0];
            $tiempo_real_segudos=$t[1];
            $unidad_retoma=$retoma[0]->unidad_produccion;
          }
        }
      $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("Y-m-d",strtotime($fecha_plan)), $unidad);
      $this->model_plan_diario->desproducir_escena($idescena,$estado,$fecha,$tiempo_real_minutos,$tiempo_real_segudos,$unidad_retoma);
      $this->model_plan_diario->eliminar_timepos_escena($idescena,$plan_diario[0]->id);
      redirect($this->lang->lang().'/continuidad/plan_diario_escena/'.$plan_diario[0]->id.'/'.$idescena.'/'.$unidad.'/'.$fecha_plan);
    }


    public function reabrir_plan(){
      $idplan = $_POST['idplan'];
      $idproduccion = $_POST['idproduccion'];
      $completar_plan = $this->model_plan_diario->completar_plan($idplan, 6);
      $escenas_plan = $this->model_plan_diario->escenas_id_plan($idplan);
      $plan_actual = $this->model_plan_general->plan_diario_id($idplan);
      $escenas_retoma = $this->model_plan_diario->escenas_a_retoma($idplan); 
      $unidad_plan = $this->model_plan_diario->unidad_id($plan_actual[0]->id_unidad);
      $cadena = "\n" . ' PLAN UNIDAD '.$unidad_plan[0]['numero'].' '. date('d-M-Y', strtotime($plan_actual[0]->fecha_inicio)) . " REABIERTO.";
      $this->user_log($unidad_plan[0]['id_produccion'],$cadena);

      if($escenas_retoma){
      foreach ($escenas_retoma as $escena_retoma) {
        $data=array(
          'id_escena' => $escena_retoma->id,
          'tiempo' => $escena_retoma->duracion_real_minutos.':'.$escena_retoma->duracion_real_segundos,
          'fecha_produccion' => $escena_retoma->fecha_produccion,
          'unidad_produccion' => $escena_retoma->unidad_produccion
        );

        $this->remotas_escena($data,$idproduccion);
          $datos = array(
              'estado'=>2
          );
          $this->db->where('id',$escena_retoma->id);
          $this->db->update('escena',$datos);
        }

      }

      if($escenas_plan){
      foreach ($escenas_plan as $escena_plan) {
        if($escena_plan->estado!=1 AND $escena_plan->fecha_produccion!=""){
          switch ($escena_plan->id_tipo_locacion) {
              case 1:
                $estado = 8;
                break;
              case 2:
                $estado = 6; 
                break;
            }
            if($escena_plan->id_toma_ubicacion==1){
              $estado = 6; 
            }
    
            if($escena_plan->id_flasback==1){
              $estado = 10;
            }

            if($escena_plan->id_producida==1){
              $estado = 1;
            }
            $datos = array(
              'estado'=>$estado ,
            );
            $this->db->where('id',$escena_plan->id);
            $this->db->update('escena',$datos);
          }
        }
      }
      $data['resultado'] = 1;
      echo json_encode($data);

    }

    public function eliminar_continuidad(){
      $id_continuidad=$_POST['id_continuidad'];
      $continuidad=$_POST['continuidad'];
      $elemento=$_POST['elemento'];
      $id_produccion=$_POST['id_produccion'];
      $eliminar=$this->model_continuidad->eliminar_continuidad($id_continuidad);
      if($eliminar){
        $cadena= "\n" . " ELIMINACION CONTINUIDAD ELEMENTO: " . $elemento  . ' DIA DE CONTINUIDAD: ' . $continuidad.'';
        $this->user_log($id_produccion,$cadena);
        $data['resultado'] = 1;
      }else{
        $data['resultado'] = 0;
      }
      echo json_encode($data);

    }

    public function eliminar_continuidad_set(){
      $id_continuidad=$_POST['id_continuidad'];
      $set=$_POST['set'];
      $id_produccion=$_POST['id_produccion'];
      $eliminar=$this->model_continuidad->eliminar_continuidad_set($id_continuidad);
      if($eliminar){
        $cadena= "\n" . " ELIMINACION CONTINUIDAD SET: " . $set;
        $this->user_log($id_produccion,$cadena);
        $data['resultado'] = 1;
      }else{
        $data['resultado'] = 0;
      }
      echo json_encode($data);

    }

    // FUNCION DE REGISTRO DE LOGS
    public function user_log($idproduccion,$cadena){
      $idusuario = $this->session->userdata('id_production_suite');
      $user = $this->model_admin->user_id($idusuario);
      if($user){
        $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
        $cadena_insert = "______________________________________________________________________________________________________________"."\n";
        $cadena_insert .= " ".strtoupper(date("d-M-Y H:i:s")).'  '.$_SERVER['REMOTE_ADDR'].'  '.strtoupper($produccion[0]->nombre_produccion)."\n";
        $cadena_insert .= $cadena."\n";
        $cadena_insert .= "______________________________________________________________________________________________________________"."\n";
        $cadena = './logs/log_'.$user[0]->id.'_'.$user[0]->nombre.'_'.$user[0]->apellido.'_'.strtoupper(date("d_M_Y")).'.txt';
        $nuevoarchivo = fopen($cadena, "a+w+"); 
        fwrite($nuevoarchivo,$cadena_insert); 
        fclose($nuevoarchivo);
      }
    }
    // FIN FUNCION DE REGISTRO DE LOGS

    public function cargar_sets(){
        $id_locacion=$_POST['id_locacion'];
        $sets=$this->model_continuidad->sets_continuidad($id_locacion);
        $sets_cont='';
        $cont=0;
        if($sets){
           foreach ($sets as $s) {
              $sets_cont[$cont]=array('id_set'=>$s['id'],'imagen'=>$s['imagen'],'nombre'=>$s['nombre']);
              $cont++;
           }
          $data['sets'] =$sets_cont;
          echo json_encode($data); 
        }else{
          $data['sets'] ='';
          echo json_encode($data);
        }
        
    }

    public function set($id_produccion,$id_set){
      $set=$this->model_escenas->set_id($id_set);
      $imagenes=$this->model_continuidad->continuidad_set($id_set);
      $escenas=$this->model_continuidad->escenas_set($id_set);
      $comentarios_set = $this->model_continuidad->comentarios_set($id_set);
      $data['comentarios_set']=$comentarios_set;
      $data['id_produccion']=$id_produccion;
      $data['set'] = $set;
      $data['imagenes'] = $imagenes;
      $data['escenas'] = $escenas;
      $data['view'] = 'continuidad/set';
      $this->load->view('includes/template_cont',$data);
    }


    public function crear_imagen_set($id_produccion){
      $locaciones=$this->model_escenas->locacion($id_produccion);
      $data['id_produccion']=$id_produccion;
      $data['locaciones']=$locaciones;
      $data['set'] = '';
      $data['view'] = 'continuidad/crear_imagenes_set';
      $this->load->view('includes/template_cont',$data);
      
    }

    public function locaciones(){
       $id_locacion=$_POST['id_locacion'];
       $sets=$this->model_continuidad->sets_continuidad($id_locacion);
       $data['sets'] =$sets;
       echo json_encode($data); 
    }

    public function guardar_foto_set(){
      $imagenes_eliminadas=$this->input->post('imagenes_eliminadas');
      $id_set=$this->input->post('set');
      $comentario=$this->input->post('comentario');
      $id_produccion=$this->input->post('id_produccion');
      $array=explode(',', $imagenes_eliminadas); 
      $set_selected = $this->model_escenas->set_id($id_set);
        if(count($_FILES['filesToUpload'])) {
          $cont=0;
            foreach ($_FILES['filesToUpload']['name'] as $temporal) {
              $cadena="";
              if (in_array($temporal, $array)){
                 //echo 'eliminado :'.$temporal.'<br>';
              }else{
                $rutaServidor="images/sets";
                $rutaTemporal= $_FILES['filesToUpload']['tmp_name'][$cont];
                $nombreimage= $temporal;
                $nombre=date("Y-m-d H:i:s").'_'.$id_set.'_'.$cont.'_'.$nombreimage;
                $nombre=str_replace('', '_', $nombre);
                $rutaFicha= $rutaServidor.'/'.$nombre;
                move_uploaded_file($rutaTemporal, $rutaFicha);
                $data=array(
                  'id_set'=>$id_set,
                  'imagen'=>$rutaFicha, 
                  'id_user'=>$this->session->userdata('id_production_suite'),
                  'fecha'=>date('Y-m-d')
                );
                $this->model_continuidad->insert_imagen_set($data);
                /*BLOQUE LOG USUARIO*/
                $cadena.= "\n" . " AGREGADA IMAGEN " . base_url($rutaFicha)  . ' A SET '.$set_selected[0]['nombre'];
                
              }
              $cont++;
              $data_com=array('id_set'=>$id_set,'comentario'=>$comentario);
              $this->model_continuidad->insert_comentario_imagenset($data_com);
              $cadena.= "\n" . " AGREGADO COMENTARIO " . $comentario  . ' A SET '.$set_selected[0]['nombre'];

              $this->user_log($id_produccion,$cadena);
            }

            $dia_continuidad=$this->input->post('cantidad_fotos');
             for ($i=1; $i <=$dia_continuidad ; $i++) { 
                  $rutaServidor="images/continuidad";
                  $name="fotos_tomados_".$i;
                  $rutaTemporal= $_FILES[$name]['tmp_name'];
                 if($rutaTemporal){
                      $rutaServidor="images/sets";
                      
                      $nombreimage= $temporal;
                      $nombre=date("Y-m-d H:i:s").'_'.$id_set.'_'.$i.'_'.$_FILES[$name]['name'];
                      $nombre=str_replace('', '_', $nombre);
                      $rutaFicha= $rutaServidor.'/'.$nombre;
                      move_uploaded_file($rutaTemporal, $rutaFicha);
                      $data=array(
                        'id_set'=>$id_set,
                        'imagen'=>$rutaFicha, 
                        'id_user'=>$this->session->userdata('id_production_suite'),
                        'fecha'=>date('Y-m-d')
                      );
                      $this->model_continuidad->insert_imagen_set($data);
                      /*BLOQUE LOG USUARIO*/
                      $cadena.= "\n" . " AGREGADA IMAGEN " . base_url($rutaFicha)  . ' A SET '.$set_selected[0]['nombre'];
                    $data_com=array('id_set'=>$id_set,'comentario'=>$comentario);
                    $this->model_continuidad->insert_comentario_imagenset($data_com);
                    $cadena.= "\n" . " AGREGADO COMENTARIO " . $comentario  . ' A SET '.$set_selected[0]['nombre'];

                    $this->user_log($id_produccion,$cadena);
                 }
             }    
            redirect($this->lang->lang().'/continuidad/set/'.$id_produccion.'/'.$id_set);
        }

    }


    public function guardar_hora_llamado(){
      $id_produccion=$this->input->post('id_produccion');
      $idplan=$this->input->post('idplan');
      $fecha=$this->input->post('fecha');
      $id_unidad=$this->input->post('id_unidad');
      $time_llamado = $this->input->post('horas_llamado').':'.$this->input->post('minutos_llamado').' '.$this->input->post('am_pm_llamado');
      $llamado = date("H:i:s",strtotime($time_llamado));
      $data=array('llamado'=>$llamado);
      $this->model_continuidad->update_plan($idplan,$data);
      /*BLOQUE DE LOG USUARIO*/
      $plan_actual = $this->model_plan_general->plan_diario_id($idplan);
      $unidad_plan = $this->model_plan_diario->unidad_id($plan_actual[0]->id_unidad);
      $cadena = "\n" . ' PLAN UNIDAD '.$unidad_plan[0]['numero'].' '. date('d-M-Y', strtotime($plan_actual[0]->fecha_inicio)) . " ACTUALIZADA HORA DE LLAMADO ".$time_llamado." .";
      $this->user_log($unidad_plan[0]['id_produccion'],$cadena);

      redirect($this->lang->lang().'/continuidad/plan_diario/'.$id_produccion.'/'.$id_unidad.'/'.$fecha);      
    }

  public function borrar_plan_diario($idplan, $idplan_diario,$idescena){
    $plan = explode(",",$idplan);
        for ($i=0; $i < count($plan); $i++){ 
      if($plan[$i]!=""){
        $plan_selected = $this->model_plan_general->plan_diario_id_2($plan[$i]);
        if($plan_selected[0]->llamado!=""){
          $fecha= strtotime($plan_selected[0]->fecha_inicio.' '.$plan_selected[0]->llamado);
        }else{
          $fecha = strtotime($plan_selected[0]->fecha_inicio);
        }
        if($plan_selected[0]->llamado == '' OR  $fecha > strtotime(date("Y-m-d H:i:s")) ){
          $numero = $this->db->query("SELECT COUNT(id)+1 AS numero from plan_diario_has_escenas_has_unidades WHERE id_plan_diario = ".$idplan_diario.";");
          $numero = $numero->result();
          $numero = $numero[0]->numero;
          $this->model_plan_general->cambiar_plan_diario($plan[$i],$idplan_diario,$idescena,$numero);
          //echo $this->db->last_query();
          $escenas_plan=$this->model_plan_diario->escenas_id_plan_diario($idplan_diario);
          if($escenas_plan){
            for ($m=0; $m < count($escenas_plan); $m++) { 
              $j = $m+1;
              $data = array(
                'orden' => $j 
              );
              $this->db->where('id_escena',$escenas_plan[$m]->id_escena);
              $this->db->where('id_plan_diario',$plan[$i]);
              $this->db->update('plan_diario_has_escenas_has_unidades',$data);
              echo 'das';
            }
          }
        }
        $cantidad = $this->model_plan_diario->contar_escenas_plan($plan[$i]);
        if($cantidad[0]->cantidad <= 0){
          $completar_plan=$this->model_plan_diario->completar_plan($plan[$i],1);
        }else{
          $escenas_plan=$this->model_plan_diario->escenas_id_plan_diario($plan[$i]);
          if($escenas_plan){
            for ($k=0; $k < count($escenas_plan); $k++) {
              $j = $k+1;
              $data = array(
                'orden' => $j
              );
              $this->db->where('id_escena',$escenas_plan[$k]->id_escena);
              $this->db->where('id_plan_diario',$plan[$i]);
              $this->db->update('plan_diario_has_escenas_has_unidades',$data);
            }
          }
        }
      }
    }
  }

  public function cambiar_idioma($tipo){

      if($tipo==1){
        $this->config->set_item('language','spanish');
      }else if($tipo==2){
        $this->config->set_item('language','english');
      }
      $metodo=$this->router->fetch_method();
      redirect($this->lang->lang().'/continuidad/index');
    
    }

}
