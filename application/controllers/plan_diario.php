<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_diario extends CI_Controller {


    public function __construct (){       
      parent :: __construct ();
      $this->load->model('model_plan_produccion');
      $this->load->model('model_plan_diario');
      $this->load->model('model_plan_general');
      $this->load->model('model_admin');
      $this->load->model('model_capitulos');
      $this->load->model('model_escenas_2');
      $this->load->model('model_escenas');
      $this->load->helper('cookie');
      $this->load->model('model_casting');
      $this->_logeo_in();
    }

  function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
  }

    public function campos(){
      $campos[]="toma";
      $campos[]="Descripción";
      $campos[]="guión";
      $campos[]="día/noche";
      $campos[]="int/ext";
      $campos[]="locación";
      $campos[]="set";
      $campos[]="personajes principales";
      $campos[]="personajes secundarios";
      $campos[]="elementos";
      $campos[]="loc/est";
      $campos[]="tiempo real";
      $campos[]="tiempo estimado";
      $campos[]="comienzo ensayo";
      $campos[]="comienzo grabación";
      $campos[]="fin grabación";
      return $campos;
    }

    public function index($id='',$id_unidad='',$fecha_unidad=''){
      $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
      
      if($tipo_usuario=='1' OR $tipo_usuario=='2' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
        $id_user=$this->session->userdata('id_pruduction_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
        if(!$tipo_rol){
         $tipo_rol=0;
        }

        $continuar=0;
          if($user){
              foreach ($user as $u) {
                if($u['id_rol_otros']== 2 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 15 or $u['id_rol_otros']== 1
                    or $u['id_rol_otros']== 7 or $u['id_rol_otros']== 17){
                  $continuar=1;
                  break;
                }
              }
         }
        
        if($continuar==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){      
            $produccion=$this->model_plan_produccion->produccion_id($id);
            $fechas_reporte_semanal = $this->model_plan_general->semanas_reporte_semanal($produccion [0]->inicio_grabacion);
            $capitulos = $this->model_capitulos->capitulos_produccion_2($id);
            $unidades=$this->model_plan_produccion->unidades_id_produccion_3($id);
            $sql="";
            $sql2 = "";
            $escenas="";
            $unidad="";
            
            $ultima_edicion="";
            $fechas_bloqueadas = $this->fechas_bloqueadas($id);

            $fechas_bloqueadas_trabajo=$this->fechas_bloqueadas_trabajo($id);

            //fechas_trabajo_unidades
            $plan_select ="";
            $id_user = $this->session->userdata('id_pruduction_suite');
            $data=array('id_usuario'=>$id_user,'tipo'=>1);
            $campos_usuario = $this->model_plan_diario->buscar_columnas($data);
            
            if($campos_usuario){
              $campos_usuario = explode(',', $campos_usuario[0]->campos);
              for ($i=0; $i < count($campos_usuario)-1; $i++) { 
                switch ($campos_usuario[$i]) {
                  case 'Descripción':
                    $sql2.=" e.descripcion AS descripcion_escena, ";
                  break;
                  case 'guión':
                    $sql2.=" e.guion AS guion_escena, ";
                  break;
                  case 'día/noche':
                    $sql2.=" d.descripcion AS des_dia, ";
                  break;
                  case 'int/ext':
                    $sql2.=" i.descripcion AS des_int, ";
                  break;
                  case 'locación':
                    $sql2.=" l.nombre AS nom_locacion, ";
                  break;
                  case 'set':
                    $sql2.=" s.nombre AS nom_set, ";
                  break;
                  case 'loc/est':
                    $sql2.=" t.tipo AS tipo, ";
                  break;
                  case 'toma':
                    $sql2.=" (SELECT COUNT(id) FROM retomas_escena where id_escena = e.id) AS retomas, ";
                  break;
                  default: $sql2.="";
                  break;          
                }  
              }
            }else{
              $sql2=" e.descripcion AS descripcion_escena, 
                      e.guion AS guion_escena,
                      d.descripcion AS des_dia, 
                      i.descripcion AS des_int, 
                      l.nombre AS nom_locacion, 
                      s.nombre AS nom_set,
                      t.tipo AS tipo,
                      (SELECT COUNT(id) FROM retomas_escena where id_escena = e.id) AS retomas, ";
            }

            if($campos_usuario==false){
               $campos_usuario = $this->campos();
               $campos_usuario[count($campos_usuario)]="";
            }
            
            $campos = $this->campos();
            $usuario_permisos = $this->permisos_usuarios($id);
            
            $ultimo_plan = $this->model_plan_diario->ultimo_plan($id_user, $id);
            if($id_unidad=="" AND $fecha_unidad==""){
              if($ultimo_plan){
                $id_unidad = $ultimo_plan[0]->id_unidad;
                $fecha_unidad = $ultimo_plan[0]->fecha_inicio;
                redirect ($this->lang->lang().'/plan_diario/index/'.$id.'/'.$id_unidad."/".$fecha_unidad);
              }
            }


           if($id_unidad!=''){
            $fecha_unidad= date("Y-m-d",strtotime($fecha_unidad));

              $data=array('id_unidad'=>$id_unidad,'fecha'=>$fecha_unidad);
              if($fecha_unidad==''){
                $escenas=$this->model_plan_diario->escenas_unidad($id_unidad);
              }else{
                $escenas=$this->model_plan_diario->unidad_id_fecha($data,$sql2);
              }
              $unidad=$this->model_plan_diario->unidad_id_plan($id_unidad,$fecha_unidad);
              if(!$unidad){
                $unidad=$this->model_plan_diario->unidad_id($id_unidad);
              }
              $fecha=date('Y-m-d');
              $plan_select = $this->model_plan_general->buscar_plan_diario_fecha($fecha_unidad,$id_unidad);
              if($plan_select){
                $ultima_edicion = $this->model_plan_diario->ultima_edicion($plan_select[0]->id);
              }
              if($escenas){
                if($ultimo_plan){
                  $this->model_plan_diario->actualizar_ultimo_plan($ultimo_plan[0]->id_ultimo,$escenas['0']['id_plan_diario']);
                }else{
                  $this->model_plan_diario->insertar_ultimo_plan($id_user,$escenas['0']['id_plan_diario']);
                }
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
            $data['fechas_reporte_semanal'] = $fechas_reporte_semanal;
            $data['tipo_rol']=$tipo_rol;
            $data['campos'] = $campos;
            $data['campos_usuario'] = $campos_usuario;
            $data['usuario_permisos'] = $usuario_permisos;
            $data['plan_select'] = $plan_select;
            $data['ultima_edicion']=$ultima_edicion;
            $data['fechas_bloqueadas']=$fechas_bloqueadas; 
            $data['fechas_bloqueadas_trabajo']=$fechas_bloqueadas_trabajo; 
            
            $data['capitulos']=$capitulos; 
            $data['unidad']=$unidad; 
            $data['escenas']=$escenas;
            $data['fecha_unidad'] = $fecha_unidad;
            $director_unidad=$this->model_produccion->get_usuarios(7,$id);
            $script=$this->model_produccion->get_usuarios(1,$id);
            //$data['cruce']=$cruce;
            $data['director_unidad']=$director_unidad;
            $data['script']=$script;
            $data['unidades']=$unidades;
            $data['produccion']=$produccion;
            $data['view']='plan_diario/index';
            $this->load->view('includes/template',$data);
         }else{
          redirect ($this->lang->lang().'/produccion/producciones');
        }   
      }else{
        redirect ($this->lang->lang().'/produccion/producciones');
      }     
    }

    public function permisos_usuarios($id){
      $produccion = $this->model_plan_produccion->produccion_id($id);
      $iduser = $this->session->userdata('id_pruduction_suite');
      $permisos = "read";
      $ver_plan=0;
      $usuario = $this->model_produccion->user_id($iduser);
      if($produccion[0]->id_productor == $iduser){
        $permisos = "write";
        $ver_plan=1;
      }else{
        $usuario_roles = $this->model_produccion->roles_usuario_produccion($iduser,$produccion[0]->id_produccion);
        if($usuario_roles){
          foreach ($usuario_roles as $usuario_rol) {
            if($usuario_rol['id_rol']==2 or $usuario_rol['id_rol']==1 or $usuario_rol['id_rol']==7 or $usuario_rol['id_rol']==8){
              $permisos = "write";
              break;
            }else{
              $permisos = "read";
            }
          }
        } else {
          
          if($usuario[0]->id_tipoUsuario=='1'){
            $permisos = "write";
          }else{
            $permisos = "read";
          }
        }

      }

      return $permisos;
    }

    public function escena_detalle($idescena,$idproduccion){
      $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
      $data['produccion'] = $produccion;
      $data['escena'] = $escena=$this->model_escenas_2->escena_id($idescena);;
      $data['view']='escenas/escena';
      $this->load->view('includes/template',$data);
    }

    public function guardar_elementos(){
      $cadena = "";
      $id_plan=$_POST['id_plan'];
      $tipo=$_POST['tipo'];
      $valor=$_POST['valor'];
      $valor2=$_POST['valor2'];
      $idunidad = $_POST['idunidad'];
      $idproduccion=$_POST['idproduccion'];
      $estado = $_POST['estado'];
      $id_plan_actual = $_POST['id_plan_actual'];
      $plan_actual = $this->model_plan_general->plan_diario_id($id_plan_actual);
      $datos=array(
          'id_plan'=>$id_plan,
          'tipo'=>$tipo,
          'valor'=>$valor,
          'valor2'=>$valor2,
          'idunidad'=>$idunidad,
          'fecha_plan' => date("Y-m-d",strtotime($plan_actual[0]->fecha_inicio_f)),
      );
      $datos=$this->model_plan_diario->guardar_elementos($datos);
      $data['valor']=$valor;
      $data['valor2']=$valor2;
      $unidad = $this->model_plan_diario->unidad_id($idunidad);
      if($tipo==1){
        if($estado!="" AND $estado =='2' AND $valor=="" AND $valor2=="" OR ($valor<=0 && $valor2<=0)){
          $this->escenas_retoma_programar($id_plan);
          $this->model_plan_diario->eliminar_retoma_escenas_id($id_plan);
        }
        $this->actualizar_tiempos($id_plan);
        $escena=$this->model_escenas_2->escena_id($id_plan);
        $capitulo = $this->model_capitulos->bucar_capitulo_id($escena[0]->id_capitulo);
        $this->model_escenas_2->actualizar_producidas($escena[0]->id_capitulo);
        $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($escena[0]->id_capitulo);
        if($capitulo[0]['escenas_escritas']<=sizeof($producidas)){
          $this->model_capitulos->capitulo_estado($capitulo[0]['id_produccion'],$capitulo[0]['id'], 5);
          $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA A ESTADO PRODUCIDO, EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
        }
        $cadena .= "\n" . " ESCENA " . $capitulo[0]['numero'] .' / '. $escena[0]->numero_escena . ' ASIGNADO TIEMPO REAL ' . $valor.':'.$valor2  ." EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
        $this->user_log($unidad[0]['id_produccion'],$cadena);
        /*GUARDAR LOS MINUTOS REALES EN LA PRODUCCION*/
        $escenas = $this->model_escenas_2->sumar_tiempos_produccion($idproduccion);
        $minutos = $this->calculo_tiempo($escenas);
        $this->model_plan_diario->actualizar_minutos_reales($idproduccion,$minutos);
      }else{
        $escena_has_plan = $this->model_plan_diario->escena_has_plan_by_id($id_plan);
        switch ($tipo) {
          case '2':
            $cadena = "\n" . " ESCENA " . $escena_has_plan[0]->numero .' / '. $escena_has_plan[0]->numero_escena . ' ASIGNADO COMIENZO ENSAYO ' . $valor ." EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
            break;
          case '3':
            $cadena = "\n" . " ESCENA " . $escena_has_plan[0]->numero .' / '. $escena_has_plan[0]->numero_escena . ' ASIGNADO COMIENZO GRABACIÓN ' . $valor ." EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
          break;
          case '4':
            $cadena = "\n" . " ESCENA " . $escena_has_plan[0]->numero .' / '. $escena_has_plan[0]->numero_escena . ' ASIGNADO FIN GRABACIÓN ' . $valor ." EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
            break;
          case '5':
            $cadena = "\n" . " ESCENA " . $escena_has_plan[0]->numero .' / '. $escena_has_plan[0]->numero_escena . ' AGREGADO COMENTARIO '."\n". $valor . "\n"." EN PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_actual[0]->fecha_inicio_f)).'.';
            break;
        }
        $this->user_log($unidad[0]['id_produccion'],$cadena);
      }
      $this->log_plan_diario($id_plan_actual);
      echo json_encode($data);
    }



  public function fechaFormat($fecha){
    $meses = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $meses_es = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
      $cont=0;
     $f=str_replace('Ene','Jan',$fecha); 
     $f=str_replace('Feb','Feb',$f); 
     $f=str_replace('Mar','Mar',$f); 
     $f=str_replace('Abr','Apr',$f);
     $f=str_replace('May','May',$f);
     $f=str_replace('Jun','Jun',$f);
     $f=str_replace('Jul','Jul',$f); 
     $f=str_replace('Ago','Aug',$f); 
     $f=str_replace('Sep','Sep',$f); 
     $f=str_replace('Oct','Oct',$f); 
     $f=str_replace('Nov','Nov',$f); 
     $f=str_replace('Dic','Dec',$f); 

     $f=str_replace('ene','Jan',$f); 
     $f=str_replace('feb','Feb',$f); 
     $f=str_replace('mar','Mar',$f); 
     $f=str_replace('abr','Apr',$f);
     $f=str_replace('may','May',$f);
     $f=str_replace('jun','Jun',$f);
     $f=str_replace('jul','Jul',$f); 
     $f=str_replace('ago','Aug',$f); 
     $f=str_replace('sep','Sep',$f); 
     $f=str_replace('oct','Oct',$f); 
     $f=str_replace('nov','Nov',$f); 
     $f=str_replace('dic','Dec',$f);
     
     return date("Y-m-d",strtotime($f));
    //echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
  }

   public function unidad(){
    $fecha=$fecha=$this->fechaFormat($this->input->post('fecha'));
    $data=array('id_unidad'=>$this->input->post('id_unidad'),
      'fecha'=>$fecha);
    $id=$this->input->post('id_produccion');
    redirect ($this->lang->lang().'/plan_diario/index/'.$id.'/'.$this->input->post('id_unidad').'/'.$fecha);
   } 

  public function editar_plan(){
      $cambio_directo=$this->input->post('director_t');
      $cambio_script=$this->input->post('script_t');
      $director=$this->input->post('director');
      $script=$this->input->post('script');
      
      $llamado="";
      if($this->input->post('horas_llamado')!="" AND $this->input->post('horas_llamado')!=""){
        $time_llamado = $this->input->post('horas_llamado').':'.$this->input->post('minutos_llamado').' '.$this->input->post('am_pm_llamado');
        $llamado = date("H:i:s",strtotime($time_llamado));
      }

      $wrap_time="";
      if($this->input->post('horas_wrap_time')!="" AND $this->input->post('horas_wrap_time')!="-"){
        $time_wrap_time = $this->input->post('horas_wrap_time').':'.$this->input->post('minutos_wrap_time').' '.$this->input->post('am_pm_wrap_time');
        $wrap_time = date("H:i:s",strtotime($time_wrap_time));
      }
      
      $lugar=$this->input->post('lugar');
      $id_plan=$this->input->post('id_plan');
      $plan_diario = $this->model_plan_general->plan_diario_id($id_plan);
      $fecha=date("Y-m-d");
      $cambio=0;
      $id_director=null;
      $id_script=null;
      $director_anterior=$this->input->post('director_anterior');
      $script_anterior=$this->input->post('script_anterior');
      if($director_anterior!=$director){
        if($cambio_directo!=1){
          $id_director=$director;
          $cambio=1;
        }else{
          $data_director=array('id_unidad'=>$this->input->post('id_unidad'),
                      'director'=>$director);
          $update_director=$this->model_plan_diario->update_unidad_director($data_director);
        }

      }
      if($script_anterior!=$script){
        if($cambio_script!=1){
          $id_script=$script;
          $cambio=1;
        }
        else{
          $data_script=array('id_unidad'=>$this->input->post('id_unidad'),
                      'script'=>$script);
          $update_director=$this->model_plan_diario->update_unidad_script($data_script);
        }
      }
      if($cambio==1){
        $data=array(
          'id_plan'=>$id_plan,
          'id_director'=>$id_director,
          'id_script'=>$id_script,
          'fecha'=>$fecha);
        $plan=$this->model_plan_diario->plan_unidad_dia($id_plan);
        $t=0;
        if($plan){
          foreach ($plan as $p) {
            if($p['fecha']==$fecha){
              $id=$p['id'];
              $t=1;
            }
          } 
        }
        if($t==1){
          $data=array(
          'id'=>$id,
          'id_plan'=>$id_plan,
          'id_director'=>$id_director,
          'id_script'=>$id_script,
          'fecha'=>$fecha);
          $insert_director=$this->model_plan_diario->update_unidad_user_dia($data);
        }else{
          $insert_director=$this->model_plan_diario->insert_unidad_user_dia($data);   
        }
       
      }
      $datos=array(
       'id_plan'=>$id_plan,
       'llamado'=>$llamado,
       'lugar'=>$lugar,
       'wrap_time'=>$wrap_time);
      $update=$this->model_plan_diario->update_plan($datos);
      $comentario_user=$this->input->post('comentario_user');
      if($comentario_user){
        $plan_diario = $this->model_plan_general->plan_diario_id($id_plan);
        $unidad = $this->model_plan_diario->unidad_id($plan_diario[0]->id_unidad);
        $cadena .= "\n" . " AGREGADO COMENTARIO ". "\n" .$comentario_user. "\n" ." A PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_diario[0]->fecha_inicio_f)).'.';
        $this->user_log($unidad[0]['id_produccion'],$cadena);
        $id_user=$this->session->userdata('id_pruduction_suite');
        $insert_coment=$this->model_plan_diario->insert_coment_user($id_user,$comentario_user,$id_plan,$fecha);
      }
      redirect ($this->lang->lang().'/plan_diario/index/'.$this->input->post('id_produccion').'/'.$plan_diario[0]->id_unidad.'/'.date("Y-m-d",strtotime($plan_diario[0]->fecha_inicio_f)));
  }


  public function mover_plan(){
      $id_plan=$this->input->post('id_plan');
      $id_unidad=$this->input->post('id_unidad');
      $fecha=$this->input->post('fecha');
      $id_produccion=$this->input->post('id_produccion');

      $plan_diario_anterior= $this->model_plan_general->plan_diario_id($id_plan);
      $unidad_anterior = $this->model_plan_diario->unidad_id($plan_diario_anterior[0]->id_unidad);
       
       $data=array(
                  'id'=>$id_plan,
                  'id_unidad'=>$id_unidad,
                  'fecha_inicio'=>$this->fechaFormat($fecha),
        );
       $this->model_plan_diario->update_plan_diario_mover($data);
       $f=date("Y-m-d",strtotime($this->fechaFormat($fecha)));


       //*****ASIGNAR FECHA DE INICIO SOLICTUDES*******///////////////

       $elementos=$this->model_plan_diario->elementosByidplan($id_plan);

       if($elementos){
          foreach ($elementos as $e) {
            $fecha_inicio=$this->model_plan_diario->fecha_inicioByidelemento($e['id']);
             if(strtotime($fecha_inicio['0']->fecha_inicio)>strtotime($f)){
                 $solicitudes=$this->model_plan_diario->solicitudesByidelemento($e['id']);
                 if($solicitudes){
                    foreach ($solicitudes as $s) {
                        $data=array('fecha_inicio' =>$f,'id'=>$s['id']);
                        $this->model_casting->update_solicitud($data);  
                    }
                    
                 }
             }
          }
       }
       //////////***************
        
        $plan_diario = $this->model_plan_general->plan_diario_id($id_plan);
        $unidad = $this->model_plan_diario->unidad_id($id_unidad);
        $cadena = "\n" . " MOVIENDO PLAN DIARIO ".$unidad_anterior[0]['numero'].' '.date("d-M-Y",strtotime($plan_diario_anterior[0]->fecha_inicio_f))." A PLAN DIARIO  UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($plan_diario[0]->fecha_inicio_f)).'.';
        $this->user_log($id_produccion,$cadena);
        redirect ($this->lang->lang().'/plan_diario/index/'.$id_produccion.'/'.$plan_diario[0]->id_unidad.'/'.date("Y-m-d",strtotime($plan_diario[0]->fecha_inicio_f)));

  }

  public function eliminar_comentario(){
    $id_comentario=$_POST['id_comentario'];
    $camentario = $this->model_plan_diario->plan_by_id_comentario($id_comentario);
    $eliminar=$this->model_plan_diario->eliminar_comentario($id_comentario);
    $cadena = "\n" . " ELIMINADO COMENTARIO ". "\n" .$camentario[0]->comentario. "\n" . " PUBLICADO EL DÍA " . $camentario[0]->fecha . " EN  PLAN DIARIO UNIDAD ".$camentario[0]->numero.' '.date("d-M-Y",strtotime($camentario[0]->fecha_inicio)).'.';
    $this->user_log($camentario[0]->id_produccion,$cadena);
    $id_user=$this->session->userdata('id_pruduction_suite');

    if($eliminar){
      $data['eliminar']=1;
    }else{
      $data['eliminar']=0;
    }
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

    public static function corta_palabra($palabra,$num) {
      $largo = strlen($palabra);
      $cadena = substr($palabra,0,$num);
      return $cadena;
    }

    /*INSERCION DE ULTIMA PERSONA EDITAR PLAN*/
    public function log_plan_diario($idplan){
      $idusuario = $this->session->userdata('id_pruduction_suite');
      $this->model_plan_diario->log_plan_diario($idplan,$idusuario); 
    }
    /*FIN INSERCION DE ULTIMA PERSONA EDITAR PLAN*/

    public function eliminar_escena_plan(){
      $idplan = $_POST['idplan'];
      $idescena=$_POST['idescena'];
      $idproduccion = $_POST['idproduccion'];
      $idunidad = $_POST['idunidad'];
      $escena = $this->model_escenas_2->escena_id($idescena);
      $cadena = "";
      $unidad_plan = $this->model_plan_diario->unidad_id($idunidad);
      if($escena[0]->estado!=12 AND $escena[0]->estado!=14 AND $escena[0]->estado%2==0){
        $estado = (int)$escena[0]->estado+1;
      }else{
        if($escena[0]->estado==14 OR $escena[0]->estado==12){
          $estado=1;
        }else if($escena[0]->estado==15){
          $estado = 2;
        }else{
          $estado=$escena[0]->estado;
        }
        /*GUARDAR LOS MINUTOS REALES EN LA PRODUCCION*/
        $escenas_actuales = $this->model_escenas_2->sumar_tiempos_produccion($idproduccion);
        $minutos = $this->calculo_tiempo($escenas_actuales);
        $this->model_plan_diario->actualizar_minutos_reales($idproduccion,$minutos);
      }
      $this->log_plan_diario($idplan);
      $this->model_escenas_2->cambia_estado($idescena,$estado);
      $this->model_plan_diario->eliminar_escena_plan($idplan,$idescena);
      $cantidad = $this->model_plan_diario->contar_escenas_plan($idplan);
      $plan_actual = $this->model_plan_general->plan_diario_id_2($idplan);
      if($cantidad[0]->cantidad<=0){
        $completar_plan=$this->model_plan_diario->completar_plan($idplan,1);
        $cadena .= "\n" . " PLAN DIARIO UNIDAD " . $unidad_plan[0]['numero'].' '. $plan_actual[0]->fecha_inicio . " CAMBIA A ESTADO NO INICIADO.";
      }else{
        
        $data=array('id_unidad'=>$plan_actual[0]->id_unidad,'fecha'=>date("Y-m-d",strtotime($plan_actual[0]->fecha_inicio_f) ));
        $escenas_plan=$this->model_plan_diario->unidad_id_fecha($data);
        $i=0;

        foreach ($escenas_plan as $escena_plan) {
          $data = array('orden' => (++$i));
          $this->db->where('id_escena',$escena_plan['id_escena']);
          $this->db->where('id_plan_diario',$idplan);
          $this->db->update('plan_diario_has_escenas_has_unidades',$data);
        }
      }
      $capitulo = $this->model_capitulos->bucar_capitulo_id($escena[0]->id_capitulo);
      $unidad_plan = $this->model_plan_diario->unidad_id($idunidad);
      $cadena .= "\n" . " ESCENA " . $capitulo[0]['numero'] . ' / '. $escena[0]->numero_escena . ' ELIMINADA DEL PLAN UNIDAD '.$unidad_plan[0]['numero'].' '. $plan_actual[0]->fecha_inicio_f . ".";
      $this->user_log($idproduccion,$cadena);
      $data['cantidad'] = $cantidad[0]->cantidad;
      $data['resultado']=1;
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


    public function escenas_retoma_programar($idescena){
          $escena = $this->model_escenas_2->escena_id($idescena);
          $estado = 12;
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
          $datos = array(
            'estado'=>$estado ,
          );
          $this->db->where('id',$idescena);
          $this->db->update('escena',$datos);
    }

    public function fechas_bloqueadas($idproduccion){
      $cadena_fechas = "";
      $contador_dias = 0;
      $j=86400;
      $semanas = $this->model_plan_produccion->semanas_trabajo($idproduccion);
      foreach ($semanas as $semana) {
        $contador_dias = strtotime($semana['fecha_inicio_semana']);
        while ($contador_dias<=strtotime($semana['fecha_fin_semana'])) {
                      switch(date("D",$contador_dias)){
                        case "Mon":
                            if($semana['lunes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Tue":
                            if($semana['martes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Wed":
                            if($semana['miercoles'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Thu":
                            if($semana['jueves'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Fri":
                            if($semana['viernes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sat':
                            if($semana['sabado'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sun':
                            if($semana['domingo'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                    }
            $contador_dias+=$j;
        }
      }
      return $cadena_fechas;
    }


    public function fechas_bloqueadas_trabajo($idproduccion){
      $cadena_fechas = "";
      $contador_dias = 0;
      $j=86400;
      $semanas = $this->model_plan_diario->fechas_trabajo_unidades($idproduccion);
      $cont=0;
      if($semanas):
        foreach ($semanas as $semana) {

              $contador_dias = strtotime($semana['fecha_inicio']);
              $cadena_fechas.= date("Ymd",$contador_dias).',';
              $cont++;
        }
      endif;

      $contador_dias = 0;
      $j=86400;
      $semanas = $this->model_plan_produccion->semanas_trabajo($idproduccion);
      foreach ($semanas as $semana) {
       // echo $semana['fecha_inicio_semana'].'<br>';
        $contador_dias = strtotime($semana['fecha_inicio_semana']);
        while ($contador_dias<=strtotime($semana['fecha_fin_semana'])) {
          //echo $semana['lunes'].'<br>';
                      switch(date("D",$contador_dias)){
                        case "Mon":
                            if($semana['lunes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Tue":
                            if($semana['martes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Wed":
                            if($semana['miercoles'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Thu":
                            if($semana['jueves'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Fri":
                            if($semana['viernes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sat':
                            if($semana['sabado'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sun':
                            if($semana['domingo'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                    }
                   // echo date("Ymd",$contador_dias).'<br>';
            $contador_dias+=$j;
        }
      }
      //echo print_r($cadena_fechas);
      return $cadena_fechas;
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

    public function remotas_escena($data,$idproduccion){
      $this->model_escenas_2->insert_remotas_escena($data);
        $datos = array(
          'duracion_real_minutos' => '00',
          'duracion_real_segundos' => '00',
        );
        $this->db->where('id',$data['id_escena']);
        $this->db->update('escena',$datos);
      $this->actualizar_tiempos($data['id_escena']);
      $escenas_actuales = $this->model_escenas_2->sumar_tiempos_produccion($idproduccion);
      $minutos = $this->calculo_tiempo($escenas_actuales);
      $this->model_plan_diario->actualizar_minutos_reales($idproduccion,$minutos);
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
      redirect ($this->lang->lang().'/plan_diario/index/'.$idproduccion.'/'.$unidad.'/'.$fecha_plan);
    }

    public function ordenar_escenas(){
      $orden = $this->input->post('orden');
      $fecha_plan = $this->input->post('fecha_plan');
      $unidad_plan = $this->input->post('unidad_plan');
      $idproduccion = $this->input->post('idproduccion');
      $data=array('id_unidad'=>$unidad_plan,'fecha'=>$fecha_plan);
      $escenas_plan=$this->model_plan_diario->unidad_id_fecha($data);
      $unidad=$this->model_plan_diario->unidad_id($unidad_plan);
      $cadena = "\n" . " MODIFICADO ORDEN ESCENAS PLAN UNIDAD ". $unidad[0]['numero'] . ' ' . date('d-M-Y', strtotime($fecha_plan)) . ".";
      $this->user_log($idproduccion,$cadena);
      $orden = explode(',', $orden);
      for ($i=0; $i < count($orden)-1; $i++) { 
        $data = array('orden' => ($i+1));
        $this->db->where('id_escena',$orden[$i]);
        $this->db->where('id_plan_diario',$escenas_plan[0]['id_plan_diario']);
        $this->db->update('plan_diario_has_escenas_has_unidades',$data);
      }
      redirect ($this->lang->lang().'/plan_diario/index/'.$idproduccion.'/'.$unidad_plan.'/'.$fecha_plan);
    }

    public function orden_columnas(){
      $id_user = $this->session->userdata('id_pruduction_suite');
      $campos =  $this->input->post('campos_columnas');
      $unidad_plan = $this->input->post('unidad_plan');
      $fecha_plan = $this->input->post('fecha_plan');
      $idproduccion = $this->input->post('idproduccion');
      $tipo = 1;
      $data=array('id_usuario'=>$id_user,'campos'=>$campos,'tipo'=>1);
      $existe = $this->model_plan_diario->buscar_columnas($data);
      if($existe){
        $this->model_plan_diario->actualizar_columnas($data);
      }else{
        $this->model_plan_diario->agregar_columnas($data);
      }
      redirect ($this->lang->lang().'/plan_diario/index/'.$idproduccion.'/'.$unidad_plan.'/'.$fecha_plan);
    }

    public function cruce_elementos($id,$fecha_unidad){
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
      $data['unidades']=$unidades;
      $data['view']='plan_diario/cruce_elementos';
      $this->load->view('includes/template',$data);
    }

    /*FUNCION LOG TXT*/
    public function user_log($idproduccion,$cadena){
      $idusuario = $this->session->userdata('id_pruduction_suite');
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
}