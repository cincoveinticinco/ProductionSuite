<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_produccion extends CI_Controller {


    public function __construct (){  
      parent :: __construct (); 
      $this->load->model('model_produccion');
      $this->load->model('model_post_produccion');
      $this->load->model('model_plan_produccion');
      $this->load->model('model_admin');
      $this->load->model('model_capitulos');
      $this->load->model('model_escenas_2');
      $this->_logeo_in();
  }


  function _logeo_in(){
    $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/login');
      }
  }

  public function index($id){
   
    $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
    if($tipo_usuario=='1' OR $tipo_usuario=='2' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5'){
      $id_user=$this->session->userdata('id_pruduction_suite');
      $user=$this->model_admin->rolUserId($id_user);
      $tipo_rol=$user['0']['id_rol_otros'];
      $sql="";

      $continuar=0;
            if($user){
                foreach ($user as $u) {
                  if($u['id_rol_otros']== 2 or $u['id_rol_otros']=='6' OR $u['id_rol_otros']=='8' OR $u['id_rol_otros']=='9' OR $u['id_rol_otros']=='10' OR $u['id_rol_otros']=='11'  OR $u['id_rol_otros']=='12' OR $u['id_rol_otros']=='13' OR $u['id_rol_otros']=='14'){

                    $continuar=1;
                    break;
                  }
                }
           }
      if($continuar==1 or $tipo_usuario=='2' or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){
        $produccion=$this->model_plan_produccion->produccion_id($id);
        
        if(isset($_COOKIE['estados_libretos_post'])){
            
            $estados = explode(',', $_COOKIE['estados_libretos_post']);
            $sql.= " AND c.id NOT IN (SELECT capitulo_has_estados.id_capitulo from capitulo_has_estados WHERE capitulo_has_estados.activo=1  AND capitulo_has_estados.id_capitulo = c.id AND (";

            for ($i=0; $i < count($estados); $i++) { 
                if($i==0){
                  if($estados[$i]!=""){
                      $sql.= " capitulo_has_estados.id_estado = ".$estados[$i];
                  }
                }else{
                  if($estados[$i]!=""){
                      $sql.= " OR capitulo_has_estados.id_estado = ".$estados[$i];
                  }
                }
            }
             $sql.= " ) )";
            $capitulos=$this->model_post_produccion->capitulos_escenas($id,$id_user,40,$sql);
           //echo $this->db->last_query();
        }else{
          $capitulos=$this->model_post_produccion->capitulos_escenas($id,$id_user,40,$sql="");
          //echo $this->db->last_query();
        }

        $total_capitulos=$this->model_post_produccion->total_capitulos_prod($id,$sql);
        $capitulos_escenas_prod=$this->model_post_produccion->capitulos_escenas_prod($id);
        $capitulos_escenas_post=$this->model_post_produccion->capitulos_escenas_post($id);
        if($tipo_rol){
          $capitulos_user=$this->model_post_produccion->capitulos_user($tipo_rol,$id);
        }else{
          $capitulos_user='';
        }
        
        /*PERMISOS USUARIO*/
         $permisos=$this->permisos_usuarios($id_user,$id);
        /*FIN PERMISOSO USUARIO*/
        $ultimo_capitulo=$this->model_post_produccion->ultimo_capitulo($id);
        if($ultimo_capitulo){
          $ultimo_capitulo=$ultimo_capitulo['0']->id;
        }else{
          $ultimo_capitulo='';
        }
        $capitulos_editados = $this->model_post_produccion->cantidad_capitulos_editados($id);
        $data['capitulos_editados']=$capitulos_editados[0]->total;
        $estados = $this->model_post_produccion->lista_estados(); 
        $data['ultimo_capitulo']=$ultimo_capitulo;
        $data['total_capitulos']=$total_capitulos;
        $data['tipo_rol']=$tipo_rol;
        $data['estados']=$estados;
        $data['produccion']=$produccion;
        $data['id_produccion']=$id;
        $data['capitulos']=$capitulos;
        $data['capitulos_escenas_prod']=$capitulos_escenas_prod;
        $data['capitulos_escenas_post']=$capitulos_escenas_post;
        $data['id_user']=$id_user;
        $data['capitulos_user']=$capitulos_user;
        $data['id_user']=$id_user;
        $data['permisos']=$permisos;
        $data['view']='post_produccion/capitulos';
        $this->load->view('includes/template',$data);
      }else{
        redirect ($this->lang->lang().'/produccion/producciones');
      } 
    }else{
      redirect ($this->lang->lang().'/produccion/producciones');
    } 
  }

  public function permisos_usuarios($id_user,$id_produccion){
     $usuario = $this->model_produccion->user_id($id_user);
      $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
        if($produccion[0]->id_productor == $id_user){
          $permisos = "write";
        }else{
          $usuario_roles = $this->model_produccion->roles_usuario_produccion($id_user,$produccion[0]->id_produccion);
          if($usuario_roles){
              foreach ($usuario_roles as $usuario_rol) {
                if($usuario_rol['id_rol']==8 OR 
                  $usuario_rol['id_rol']==9 OR
                  $usuario_rol['id_rol']==10 OR 
                  $usuario_rol['id_rol']==11 OR 
                  $usuario_rol['id_rol']==12 OR
                  $usuario_rol['id_rol']==13 OR
                  $usuario_rol['id_rol']==14){
                  $permisos = "write";
                  break;
                }else{
                  $permisos = "read";
                }
              }
          }else{
            if($usuario[0]->id_tipoUsuario=='1'){
              $permisos = "write";
            }else{
              $permisos = "read";
            }
          }
        }
    return $permisos;
  }


 public function crear_capitulo(){
    $numero_capitulo=$_POST['numero_capitulo'];
    $id_produccion=$_POST['id_produccion'];
    $capitulo=$this->model_post_produccion->capitulo_numero($numero_capitulo,$id_produccion);
    if($capitulo){
      $data['existe']=1;
    }else{
      $data=array(
             'id_produccion'=>$id_produccion,
             'numero'=>$numero_capitulo,
             'estado'=>2,
          );
      $insert_capitulos=$this->model_post_produccion->insert_capitulos($data);
      $cadena = " CAPITULO " . $numero_capitulo . " CREADO." ."\n";
      $this->user_log($id_produccion,$cadena);
      $id_capitulo = mysql_insert_id();

      /*ASIGNAR CAPITULO USUARIO*/
      $user_estado=$this->model_post_produccion->user_estado_cap($id_produccion,2);
      if($user_estado){
        foreach ($user_estado as $user) {
          $data=array(
            'id_user'=>$user['id_user'],
            'id_capitulo'=>$id_capitulo,
            'estado'=>1
          );
          $this->model_capitulos->insertar_capitulo_user($data);
          $data['estatus']=$user['estatus'];
          $data['responsable']=$user['responsable'];
          $u=$this->model_produccion->user_id($user['id_user']);
          $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
          $numero = $this->model_post_produccion->capitulo_id($id_capitulo);
          $cuerpo = 'Usted fue asigando al capitulo: '.$numero[0]->numero.' de la produccion: '.$produccion['0']->nombre_produccion.' ';
        }
      }
     /*FIN ASIGNAR CAPITULO USUARIO*/

      /*INSERTAR ESTADO CAPITULO*/
      $data_capitulo = array(
        'id_capitulo' => $id_capitulo, 
        'id_estado' => 2,
        'activo'=>1
      );
      $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      /*FIN INSERTAR ESTADO CAPITULO*/

      $data['existe']=2;
    }
    echo json_encode($data);
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


    public static  function calculo_tiempo_post($minutos1,$segundos1,$cuadros1){
      $cuadros = 0;
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      $cuadros = $cuadros1;
     
     while($cuadros>=30){
          $segundos+=1;
          $cuadros= $cuadros-30;
      }
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
      if(strlen($cuadros)<2){
        $cuadros = '0'.$cuadros;
      }
        if($minutos2==0){
          $minutos2='00';
        }
        if($segundos==0){
          $segundos='00';
        }
        if($cuadros==0){
          $cuadros='00';
        }
        $tiempo = $minutos2.":".$segundos.'-'.$cuadros;

      return $tiempo;
    }

    public static  function calculo_tiempo_post2($minutos1,$segundos1,$cuadros1){
      $cuadros = 0;
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      $cuadros = $cuadros1;
     
     while($cuadros>=30){
          $segundos+=1;
          $cuadros= $cuadros-30;
      }
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
      if(strlen($cuadros)<2){
        $cuadros = '0'.$cuadros;
      }
        if($minutos2==0){
          $minutos2='00';
        }
        if($segundos==0){
          $segundos='00';
        }
        if($cuadros==0){
          $cuadros='00';
        }
        $tiempo = $minutos2.":".$segundos.':'.$cuadros;

      return $tiempo;
    }

   public function capitulo($id_produccion,$id_capitulo,$msg=''){
      if($msg==1){
          $msg='<div class="alert-box success">
                 Tiempos post-produccion actualizados<a href="" class="close">&times;</a>
            </div>';
      }else{
        $msg='';
      }
      $escenas=$this->model_post_produccion->escenas_id_capitulo($id_capitulo);
      $total_escenas_capitulos=$this->model_post_produccion->escenas_id_capitulo_total($id_capitulo);
      $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
      $capitulo=$this->model_post_produccion->capitulo_id($id_capitulo);
      $tiempo_real=$this->model_post_produccion->escenas_id_capitulo_tiempo_real($id_capitulo);
      $tiempo_post=$this->model_post_produccion->escenas_id_capitulo_tiempo_post($id_capitulo);
      $tiempo_est=$this->model_post_produccion->escenas_id_capitulo_tiempo_estimado($id_capitulo);
      $libretos = $this->model_capitulos->capitulos_produccion_2($id_produccion);
      /*$escenas_libreto=$this->model_post_produccion->escena_id_libreto($libretos['0']['id_capitulo']);
      $escenas_libreto=$this->capitulos_has_escenas($escenas_libreto);*/
      $vitacora=$this->model_post_produccion->vitacora($id_capitulo);
      $id_user=$this->session->userdata('id_pruduction_suite');

      $permisos=$this->permisos_usuarios($id_user,$id_produccion);
      $total_escenas=$this->model_post_produccion->total_escenas_capitulo($id_capitulo);
      $libretos_capitulo=$this->model_post_produccion->libretos_capitulo($id_capitulo);
      $data['produccion']=$produccion;
      $data['escenas']=$escenas;
      $data['total_escenas_capitulos']=$total_escenas_capitulos;
      
      $data['capitulo']=$capitulo;
      $data['tiempo_real']=$tiempo_real;
      $data['tiempo_post']=$tiempo_post;
      $data['tiempo_est']=$tiempo_est;
      $data['libretos']=$libretos;
      $data['id_capitulo']=$id_capitulo;
      $data['id_produccion']=$id_produccion;
      //$data['escenas_libreto']=$escenas_libreto;
      $data['vitacora']=$vitacora;
      $data['id_user']=$id_user;
      $data['total_escenas']=$total_escenas;
      $data['libretos_capitulo']=$libretos_capitulo;
      $data['msg']=$msg;
      $data['permisos']=$permisos;
      $data['view']='post_produccion/capitulo';
      $this->load->view('includes/template',$data);
   }

  public function cargar_capitulos(){
    $id_user=$this->session->userdata('id_pruduction_suite');
    $user=$this->model_admin->rolUserId($id_user);
    $tipo_rol=$user['0']['id_rol_otros'];
    $id_produccion=$_POST['id_produccion'];
    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    $estados = $_POST['estados'];
    $sql="";
    setcookie("estados_libretos_post", $estados);
    if($estados!=""){
        $estados = explode(',', $estados);
        for ($i=0; $i < count($estados); $i++) { 

            if($estados[$i]!=""){
                $sql.= " AND capitulo_has_estados.id_estado != ".$estados[$i];
            }
        }
        $sql.=' AND capitulo_has_estados.activo = 1 ';
    }

    $limit=$_POST['limit'];
    $capitulos=$this->model_post_produccion->capitulos_escenas($id_produccion,$id_user,$limit,$sql);
    $total_capitulos=$this->model_post_produccion->total_capitulos_prod2($id_produccion);
    
    if(!$capitulos){
      $data['existe']=1;
    }else{ $html='';
        foreach ($capitulos as $c) {
          //if($id_user==$c['id_user']){
            //$class='cap_confirmar';
          //}else{
             $class='';
          //}
          $tiempo_estimados=Post_produccion::calculo_tiempo2($c['minutos_estimados'],$c['segundos_estimados']);
          $tiempo_real=Post_produccion::calculo_tiempo2($c['minutos'],$c['segundos']);
          $tiempo_post=Post_produccion::calculo_tiempo2($c['minutos_post'],$c['segundos_post']);
          $m_extra=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['foto_minutos']+$c['imagenes_archivos_minutos'];
          $s_extra=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['foto_segundos']+$c['imagenes_archivos_segundos'];
          $c_extra=$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros'];
          $tiempo_extra=Post_produccion::calculo_tiempo_post($m_extra,$s_extra,$c_extra);
          $total=Post_produccion::calculo_tiempo_post_redondeo($c['minutos_post']+$m_extra,$c['segundos_post']+$s_extra,$c['cuadros']+$c_extra);
          //$fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id_produccion,$c['numero']); 
          $fecha_entrega=$c['fecha_entrega'];
          //
          if($fecha_entrega){
            if($fecha_entrega!=null and $fecha_entrega!='0000-00-00'){
              $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega));  
            }else{
              $fecha_entrega='-';  
            }
          }else{
            $fecha_entrega='-'; 
          } 
          if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
            $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
          }else{
            $fecha_entregada='-'; 
          } 
          
         if($c['estado']){
            $estado = explode(',', $c['estado']);

          $campos_estado="";
          for ($i=0; $i < count($estado); $i++) { 
            $estado_base = explode('_', $estado[$i]);
            switch($estado_base[1]){
              case 'No producido':
                $class_cap="no_prod";
                $campos_estado .="<div class='no_prod'>".$estado_base[1]."</div>";
              break;
              case 'LOGGING/INGESTANDO':
                $class_cap="log_ing";
                $campos_estado .="<div class='log_ing'>".$estado_base[1]."</div>";
              break;
              case 'PRE-EDITANDO':
                $class_cap="pre_edi";
                $campos_estado .="<div class='pre_edi'>".$estado_base[1]."</div>";
              break;
              case 'EDITANDO':
                $class_cap="edi";
                $campos_estado .="<div class='edi'>".$estado_base[1]."</div>";
              break;
              case 'FINALIZANDO':
                $class_cap="fin";
                $campos_estado .="<div class='fin'>".$estado_base[1]."</div>";
              break;
              case 'CODIFICANDO APP VIDEO':
                $class_cap="cod_app_vid";
                $campos_estado .="<div class='cod_app_vid'>".$estado_base[1]."</div>";
              break;
              case 'QC RTI TECNICO':
                $class_cap="qc_rti_tec";
                $campos_estado .="<div class='qc_rti_tec'>".$estado_base[1]."</div>";
              break;
              case 'QC RTI PRODUCTOR':
                $class_cap="qc_rti_por";
                $campos_estado .="<div class='qc_rti_por'>".$estado_base[1]."</div>";
              break;
              case 'MONTANDO ARCHIVO LTO':
                $class_cap="mon_arc_lto";
                $campos_estado .="<div class='mon_arc_lto'>".$estado_base[1]."</div>";
              break;
              case 'CODIFICANDO A CLIENTE':
                $class_cap="cod_cli";
                $campos_estado .="<div class='cod_cli'>".$estado_base[1]."</div>";
              break;
              case 'ENVIANDO A CLIENTE':
                $class_cap="env_cli";
                $campos_estado .="<div class='env_cli'>".$estado_base[1]."</div>";
              break;
              case 'QC CLIENTE':
                $class_cap="qc_cli";
                $campos_estado .="<div class='qc_cli'>".$estado_base[1]."</div>";
              break;
              case 'SESION DE PROTOOLS':
                $class_cap="arc_cap";
                $campos_estado .="<div class='arc_cap'>".$estado_base[1]."</div>";
              break;
              case 'MONTANDO EDL PS':
                $class_cap="mon_edl_ps";
                $campos_estado .="<div class='mon_edl_ps'>".$estado_base[1]."</div>";
              break;
              case 'CAPITULO ENTREGADO':
                $class_cap="cap_ent";
                $campos_estado .="<div class='cap_ent'>".$estado_base[1]."</div>";
              break;
              case 'CANCELADO':
                $class_cap="cap_cancel";
                $campos_estado .="<div class='cap_cancel'>".$estado_base[1]."</div>";
              break;
            }
          }
         }else{
            $data_capitulo = array(
            'id_capitulo' => $c['id'], 
            'id_estado' => 2,
            'activo'=>1
           );
             $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);

            $class_cap="log_ing";
            $campos_estado .="<div class='log_ing'> LOGGING/INGESTANDO</div>";
         }
            
      
          $html=$html.'
          <tr class="capitulo_'.$c['id'].''.$class.'">
          <td>
            <table class="secondary">
              <tr>
                <td width="10%" ><a href="'.base_url($this->lang->lang().'/post_produccion/capitulo/'.$c['id_produccion'].'/'.$c['id']).'">'.$c['numero'].'</a></td>
                <td width="50%" class="estado_cap_'.$c['id'].' ver_vitacora '.$class_cap.' estatus" data-idcapitulo="'.$c['id'].'">'.$campos_estado.'</td>
                <td width="15%" class="ver_vitacora" data-idcapitulo="'.$c['id'].'" >'.$c['total_escenas'].'</td>
                <td width="15%" class="ver_vitacora" data-idcapitulo="'.$c['id'].'" >';
                $libretos_capitulo=$this->model_post_produccion->libretos_capitulo($c['id']);
                $lib='';
                if($libretos_capitulo){ 
                $tam=sizeof($libretos_capitulo);$cont=1;
                 foreach ($libretos_capitulo as $l) {
                     if($cont==$tam){ 
                        $lib=$lib.$l['numero'];
                     }else{
                        $lib=$lib.$l['numero'].'-'; 
                     } 
                     $cont++;
                 }
              }else{ 
                $lib=$lib.'-'; 
              }
              $html.=$lib."</td>
                </tr>
              </table></td>";
              $html=$html.$lib.'
              <td>
                <table class="secondary">
                  <tr>
                    <td width="20%" class="ver_vitacora align_center" data-idcapitulo="'.$c['id'].'" >'.$tiempo_estimados.'</td>
                    <td width="20%" class="ver_vitacora align_center" data-idcapitulo="'.$c['id'].'" >'.$tiempo_real.'</td>
                    <td width="20%" class="ver_vitacora align_center" data-idcapitulo="'.$c['id'].'" >'.$tiempo_post.'</td>
                    <td width="20%" class="ver_vitacora align_center" data-idcapitulo="'.$c['id'].'" >'.$tiempo_extra.'</td>
                    <td width="20%" class="ver_vitacora align_center" data-idcapitulo="'.$c['id'].'" >'.$total.'</td>
                  </tr>
                </table>
              </td>';
              $diferencia=Post_produccion::calculo_tiempo2($c['minutos']-$c['minutos_post'],$c['segundos']-$c['segundos_post']);
              $html=$html.'
              <td>
                <table class="secondary">
                  <tr>
                    <td class="responsable_cap_'.$c['id'].' ver_vitacora" data-idcapitulo="'.$c['id'].'" >'.$diferencia.'</td>
                  </tr>
                </table>
              </td>';
              $html=$html.'
              <td>
                <table class="secondary">
                  <tr>
                    <td width="25%" class="responsable_cap_'.$c['id'].' ver_vitacora" data-idcapitulo="'.$c['id'].'" >'.$c['responsable'].'</td>';
              $html=$html.'<td width="15%" class="" data-idcapitulo="'.$c['id'].'">';
              if($produccion['0']->estado!=2){
                $html=$html.'<a href="#" class="chage_date" data-id="'.$c['id'].'" onclick="return false;">';
              }
              $html=$html.$fecha_entrega.'</a>
                      <div class="fecha_entrega'.$c['id'].' hide_box tiempos_pos_box">
                           <span class="close_box"></span>
                           <div style="width:100%; height:40px">
                             <input type="text" placeholder="DD-MM-YYYY" value="'.$fecha_entrega.'" name="fecha_post" class="datepicker fecha_post_'.$c['id'].'">
                           </div>
                            <div class="align_left">
                              <a href="#" class="cancel_icon close_box">Cancelar</a>
                              <a class="save_icon_fecha" idcapitulo="'.$c['id'].'" idproduccion="'.$id_produccion.'" idcapitulo="'.$c['id'].'" >Guardar</a>
                            </div>
                       </div>
                   </td>';
              $html=$html.'<td width="15%" class="ver_vitacora" data-idcapitulo="'.$c['id'].'" >'.$fecha_entregada.'</td>';
              
              $ultimo_capitulo=$this->model_post_produccion->ultimo_capitulo($id_produccion);
              if($ultimo_capitulo){
                $ultimo_capitulo=$ultimo_capitulo['0']->id;
              }else{
                $ultimo_capitulo='';
              }

              if($this->session->userdata('tipo_pruduction_suite')==1){
                $edita = true;
              }else{
                $edita = false;
              }

              $estado_val = "2";
              $estado = explode(',', $c['id_estado']);
              $campos_estado="";
              for ($i=0; $i < count($estado); $i++) { 
                if(!$edita){
                  $this->model_post_produccion->valida_edicion_capitulo($estado_base[0],$tipo_rol);
                  if($this->model_post_produccion->valida_edicion_capitulo($estado_base[0],$tipo_rol)){
                    $edita=true;
                  }
                } 
                $estado_base = explode('_', $estado[$i]);
                if($estado_base[0]=='6' AND $tipo_rol=='9'){
                  $estado_val = $estado_base[0];
                  break;
                }else if($estado_base[0]=='7' AND $tipo_rol=='14'){
                  $estado_val = $estado_base[0];
                  break;
                }elseif(count($estado)>=2 and $this->session->userdata('tipo_pruduction_suite')==1){
                   $estado_val=$estado[0].','.$estado[1];
                   break;
                }else{
                  $estado_val = $estado_base[0];
                }
              }
              
              if($estado_val!=16){
                if(($edita OR $this->session->userdata('tipo_pruduction_suite')==1) and $produccion['0']->estado!=2){
                  $html.='<td width="42%" class="" data-idcapitulo="'.$c['id'].'">';
                  $est=explode(',', $estado_val);
                  if(count($est)>=2){ 
                    $html.='<a href="#" class="confirmar_capitulo_doble" data-idestado="'.$est['0'].'" data-idestadodos="'.$est['1'].'" data-capitulo="'.$c['numero'].'" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">CONFIRMAR</a>/';
                  }elseif($est['0']==15){
                  
                  }else{
                    $html.='<a href="#" class="confirmar_capitulo" data-idestado="'.$est['0'].'" data-capitulo="'.$c['numero'].'" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">CONFIRMAR</a>/';
                  } 
                  $est=explode(',', $estado_val);
                  if(count($est)>=2){
                    $html.='<a href="#" class="rechazar_capitulo_doble" data-idestado="'.$est['0'].'" data-idestadodos="'.$est['1'].'" data-capitulo="'.$c['numero'].'" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">RECHAZAR</a>/';
                  }elseif($est['0']==9){ 
                    $html.='<a href="#" class="rechazar_capitulo" data-capitulo="'.$c['numero'].'" data-idestado="9" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">RECHAZAR</a>/';
                  }elseif($est['0']==2){ 
                  }else{
                    $html.='<a href="#" class="rechazar_capitulo"  data-capitulo="'.$c['numero'].'" data-idestado="'.$est['0'].'" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">RECHAZAR</a>/';
                  } 
                    
                  if($c['total_escenas']<=0 and $ultimo_capitulo==$c['id']){ 
                    $html.='<a href="#" class="eliminar_capitulo" data-capitulo="'.$c['numero'].'" data-idestado="'.$estado_val.'" data-idcapitulo="'.$c['id'].'" data-iduser="'.$id_user.'">ELIMINAR</a>';
                  } 
                  $html.='</td>';
                }else{ 
                  $html.='<td width="42%" class="ver_vitacora" data-idcapitulo="'.$c['id'].'">';
                  $html.='<a href="#" onclick="return false;">VER</a>';
                  $html.='</td>';
                } 
              }else{ 
                  $html.='<td width="42%" class="ver_vitacora" data-idcapitulo="'.$c['id'].'">';
                  $html.='</td>';
              }
              $html=$html.'</tr></table></td>';
      }    
      $data['html']=$html;
      $data['total_capitulos']=$total_capitulos;
      $data['cantidad'] = count($capitulos);
      $data['existe']=2;
    }
    echo json_encode($data);

   }

   public function capitulos_has_escenas($escenas,$id_capitulo){
    $escenas_capitulos=array();
    $cont=0;
    if($escenas){
      foreach ($escenas as $e) {
        $es=$this->model_post_produccion->capitulos_has_escenas_id_escena($e['id'],$id_capitulo);
        if(!$es){
          $escenas_capitulos[$cont]= array('id'=>$e['id'],'numero_escena'=>$e['numero_escena'],
                                   'capitulo'=>0,'estado'=>$e['estado']);
          //$escenas_capitulos[$cont]=$e;
        }else{
          $escenas_capitulos[$cont]= array('id'=>$e['id'],'numero_escena'=>$e['numero_escena'],
                                   'capitulo'=>1,'estado'=>$e['estado']);
        }
        $cont++;
      }
    }

    return $escenas_capitulos;
   }

  public function cargar_escena(){
    $id_libreto=$_POST['libreto'];
    $id_capitulo=$_POST['id_capitulo'];
    $escenas_libreto=$this->model_post_produccion->escena_id_libreto($id_libreto);
    $escenas_libreto=$this->capitulos_has_escenas($escenas_libreto,$id_capitulo);
    $data['escenas']=$escenas_libreto;
    echo json_encode($data);
  }

  public function escenas_capitulo(){
    $escenas=$this->input->post('escenas');
    $id_capitulo=$this->input->post('id_capitulo');
    $id_produccion=$this->input->post('id_produccion');

    if($escenas){
      foreach ($escenas as $e) {
        $datos=array('id_capitulo' =>$id_capitulo,'id_escena'=>$e);
        $es=$this->model_post_produccion->insert_capitulos_has_esena($datos);
        $escena_selected = $this->model_escenas_2->escena_id($e);
        $capitulo = $this->model_post_produccion->capitulo_id($id_capitulo);
        $cadena = " ESCENA " . $escena_selected[0]->numero_libreto .'/'.$escena_selected[0]->numero_escena ." ASIGNADA AL CAPITULO ".$capitulo[0]->numero ." .\n";
        $this->user_log($id_produccion,$cadena);
      }
    }
    redirect ($this->lang->lang().'/post_produccion/capitulo/'.$id_produccion.'/'.$id_capitulo);
  }

  public function save_post_produccion(){
    $id_escena=$this->input->post('id_escena');
    $id_capitulo=$this->input->post('id_capitulo');
    $minutos_post=$this->input->post('minutos_post');
    $segundos_post=$this->input->post('segundos_post');
    $cuadros_post=$this->input->post('cuadros_post');
    if($minutos_post or $segundos_post){
      $data=array('id_capitulo'=>$id_capitulo,'id_escena'=>$id_escena,'tiempo_post_minutos'=>$minutos_post,'tiempo_post_segundos'=>$segundos_post,'tiempo_post_cuadros'=>$cuadros_post);
      $this->model_post_produccion->update_tiempo_post($data);
    }
    $id_capitulo=$this->input->post('id_capitulo');
    $id_produccion=$this->input->post('id_produccion');
    redirect ($this->lang->lang().'/post_produccion/capitulo/'.$id_produccion.'/'.$id_capitulo);
  }

  public function confirmacion_capitulo(){
    $id_capitulo=$_POST['id_capitulo'];
    $id_user=$_POST['id_user'];
    $estado_actual=$_POST['id_estado'];
    $id_produccion=$_POST['id_produccion'];
    $estado=$estado_actual+1;
    $rol_estado_cap=$this->model_post_produccion->rol_estado_cap($estado_actual);
    $fecha=date('Y-m-d');
    if($estado==15){
       $fecha_entregada=$fecha;
    }else{
       $fecha_entregada=null;
    }
    $data=array(
      'id_capitulo'=>$id_capitulo,
      'id_estado'=>$estado,
      'fecha_entregada'=>$fecha_entregada
    );
    /*DESACTIVAR ESTADO ANTERIOR*/
     $this->model_post_produccion->descartivar_estado($id_capitulo,$estado_actual,$fecha,$id_user);
     $data = array('id_capitulo'=>$id_capitulo,
        'fecha_aprobacion'=>$fecha,
        'id_estado_capitulo'=>$estado_actual,
        'id_rol'=>$rol_estado_cap['0']->id_rol,
        );
    //$update_capitulo_user=$this->model_post_produccion->update_capitulo_user($data);
    if($estado_actual==5){
          // CODIFICANDO APP VIDEO
          $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 6,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          // FIN CODIFICANDO APP VIDEO

          // QC RTI TECNICO
          $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 7,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo); 
          // FIN QC RTI TECNICO

    }elseif($estado_actual==12) {
        // CODIFICANDO APP VIDEO
          $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 13,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          // FIN CODIFICANDO APP VIDEO

          // QC RTI TECNICO
          $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 14,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo); 
    }else{
      if($estado_actual==6){
        $data_capitulo = array(
          'id_capitulo' => $id_capitulo, 
          'id_estado' => 8,
          'activo'=>1
        );
        $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        }elseif($estado_actual==8){
          $actual=$this->model_post_produccion->validar_estado($id_capitulo,7);
            if(!$actual){
              $data_capitulo = array(
              'id_capitulo' => $id_capitulo, 
              'id_estado' => 9,
              'activo'=>1
              );
              $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
            }
      }elseif($estado_actual==13){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,14);
          if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 15,
            'activo'=>1
            );
            $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          }
      }elseif($estado_actual==14){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,13);
          if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 15,
            'activo'=>1
            );
            $data_fecha = array('fecha_entregada' => date('Y-m-d') );
            $this->model_post_produccion->actualiza_fecha_entregada($id_capitulo,$data_fecha);
            $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          }
      }elseif($estado_actual==7){
        $actual=$this->model_post_produccion->validar_estado2($id_capitulo,6,8);
          if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 9,
            'activo'=>1
            );
            $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          }
      }else{
         $data_capitulo = array(
          'id_capitulo' => $id_capitulo, 
          'id_estado' => $estado,
          'activo'=>1
        );
         $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      }
        
        
        
    }

    //$update_capitulo=$this->model_post_produccion->update_capitulo($data);
    echo json_encode($data);
  }


  public function vitacora(){
    $id_capitulo=$_POST['id_capitulo'];
    $vitacora=$this->model_post_produccion->vitacora($id_capitulo);
    //echo $this->db->last_query();
    $data['vitacora']=$vitacora;
    echo json_encode($data);
  }

  public function rechazar_capitulo(){
    $id_capitulo=$_POST['id_capitulo'];
    $estado_actual=$_POST['id_estado'];
    $fecha=date('Y-m-d');
    $id_user=$this->session->userdata('id_pruduction_suite');
    $this->model_post_produccion->reverzar_estado($id_capitulo,$estado_actual,$fecha,$id_user,1);
      if($estado_actual==9){
        $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 5,
            'activo'=>1
          );
        $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);

      }elseif($estado_actual==15){
        $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 14,
            'activo'=>1
          );
        $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 13,
            'activo'=>1
          );
        $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      }elseif($estado_actual==6){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,7);
        if($actual){
        
          $this->model_post_produccion->reverzar_estado($id_capitulo,7,$fecha,$id_user);
        }
          $data_capitulo = array(
          'id_capitulo' => $id_capitulo, 
          'id_estado' => 5,
          'activo'=>1
        );
        $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      }elseif($estado_actual==7){
        $actual=$this->model_post_produccion->validar_estado2($id_capitulo,6,8);
        if($actual){
          
          $this->model_post_produccion->reverzar_estado($id_capitulo,6,$fecha,$id_user);
          $this->model_post_produccion->reverzar_estado($id_capitulo,8,$fecha,$id_user);
        }
        $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 5,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      }elseif($estado_actual==8){
        $this->model_post_produccion->reverzar_estado($id_capitulo,7,$fecha,$id_user);
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 5,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
          $this->model_post_produccion->reverzar_estado($id_capitulo,7,$fecha,$id_user);
      }elseif($estado_actual==14){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,13);
        if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 12,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        }
      }elseif($estado_actual==13){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,14);
        if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 12,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        }
      }elseif($estado_actual==12){
        $actual=$this->model_post_produccion->validar_estado($id_capitulo,14);
        if(!$actual){
            $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => 5,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        }
      }else{
        $estado=$estado_actual-1;
         $data_capitulo = array(
            'id_capitulo' => $id_capitulo, 
            'id_estado' => $estado,
            'activo'=>1
          );
          $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
      }
      $data['datos']='';
      echo json_encode($data);
  }

  public function eliminar_capitulo(){
    $id_capitulo=$_POST['id_capitulo'];
    $eliminar=$this->model_post_produccion->eliminar_capitulo($id_capitulo);
    $data['eliminar']=$eliminar;
    echo json_encode($data);
  }

  public function guardar_detalles(){
    $id_capitulo=$_POST['id_capitulo'];
    $valor=$_POST['valor'];
    $detalle=$_POST['detalle'];
    $datos=array(
      'valor'=>$valor,
      'id_capitulo'=>$id_capitulo,
      'detalle'=>$detalle,
    );
    $update_catitulo=$this->model_post_produccion->update_detalle_capitulo($datos);
    // LOGS USUARIO
    $capitulo = $this->model_post_produccion->capitulo_id($id_capitulo);
    $cadena = " CAPITULO " . $capitulo[0]->numero . " ASIGNADO TIEMPO EXTRA '". strtoupper($detalle). "' DE ". $valor."\n";
    $this->user_log($capitulo[0]->id_produccion,$cadena);
    $tiempo_extra=$this->model_post_produccion->sum_tiempos_extras_capitulo($id_capitulo);
    $m=$tiempo_extra['0']->flashback_minutos+$tiempo_extra['0']->transiciones_minutos+$tiempo_extra['0']->stab_minutos+$tiempo_extra['0']->recap_minutos+$tiempo_extra['0']->cabezote_minutos+$tiempo_extra['0']->credito_minutos+$tiempo_extra['0']->cortinillas_minutos+$tiempo_extra['0']->despedida_minutos+$tiempo_extra['0']->presentacion_minutos+$tiempo_extra['0']->foto_minutos+$tiempo_extra['0']->imagenes_archivos_minutos;
    $s=$tiempo_extra['0']->flashback_segundos+$tiempo_extra['0']->transiciones_segundos+$tiempo_extra['0']->stab_segundos+$tiempo_extra['0']->recap_segundos+$tiempo_extra['0']->cabezote_segundos+$tiempo_extra['0']->credito_segundos+$tiempo_extra['0']->cortinillas_segundos+$tiempo_extra['0']->despedida_segundos+$tiempo_extra['0']->presentacion_segundos+$tiempo_extra['0']->foto_segundos+$tiempo_extra['0']->imagenes_archivos_segundos; 
    $c=$tiempo_extra['0']->flashback_cuadros+$tiempo_extra['0']->transiciones_cuadros+$tiempo_extra['0']->stab_cuadros+$tiempo_extra['0']->recap_cuadros+$tiempo_extra['0']->cabezote_cuadros+$tiempo_extra['0']->credito_cuadros+$tiempo_extra['0']->cortinillas_cuadros+$tiempo_extra['0']->despedida_cuadros+$tiempo_extra['0']->presentacion_cuadros+$tiempo_extra['0']->foto_cuadros+$tiempo_extra['0']->imagenes_archivos_cuadros; 
    $tiempo_extra=$this->calculo_tiempo_post($m,$s,$c);

    $tiempo_post=$this->model_post_produccion->escenas_id_capitulo_tiempo_post($id_capitulo);

    $tiempo_total=$this->calculo_tiempo_post_redondeo($tiempo_post['0']->minutos+$m,$tiempo_post['0']->segundos+$s,$tiempo_post['0']->cuadros+$c);



    $data['tiempo_extra']=$tiempo_extra;
    $data['tiempo_total']=$tiempo_total;
    echo json_encode($data);
  }

 public function eliminar_escena_capitulo(){
    $id_escena_capitulo=$_POST['id_escena_capitulo'];
    $eliminar=$this->model_post_produccion->eliminar_escena_capitulo($id_escena_capitulo);
    $data['eliminar']=$eliminar;
    echo json_encode($data);
  }

  public function seleccionar_archivo($id,$id_capitulo){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $data['produccion']=$produccion;
      $data['id_capitulo']=$id_capitulo;
      $data['view']='post_produccion/carga_archivo';
      $this->load->view('includes/template',$data);
  }
  
  public function cargar_archivo(){
    $id_produccion=$this->input->post('id_produccion');
    $id_capitulo=$this->input->post('id_capitulo');
    $capitulo=$this->model_post_produccion->capitulo_id($id_capitulo);
    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    $rutaServidor="text";
    $rutaTemporal= $_FILES["archivo"]["tmp_name"];
    $date=date("Y-m-d H:i:s"); 
    $nombreimage= $_FILES["archivo"]["name"].'_'.$date;
    $nombre=preg_replace('/ /','_', $nombreimage);
    $rutaDestino= $rutaServidor.'/'.$nombre;
    move_uploaded_file($rutaTemporal, $rutaDestino);
    $archivo=base_url($rutaDestino);
    $file = fopen($archivo, "r") or exit("Unable to open file!");
    $cont=0;
    $cont2=1;
    $i=0;
    $despedida=0;
    $cred=0;
    $flah=0;
    $timelaps=0;
    $cortinas=0;
    $cabe=0;
    $recap=0;
    $fach=0;
    $despedias=0;
    $presentacion=0;
    $cont_fx=0;
    $foto=0;
    $i=0;
    $total_presentacion='';
    $total_fach='';
    $total_recap='';
    $total_cabe='';
    $total_cortinas='';
    $total_timelaps='';
    $total_flah='';
    $total_cred='';
    $total_despedias='';
    $total_foto='';
    $fx='';
    while(!feof($file)){
       $datos=fgets($file);
       $totalcadena=strlen($datos);
       $cadena=strpos($datos,'');
       if($cadena === false){
               if($cont>1){
                 $valor=explode(" ",$datos);
                 if($cont2==1){
                    $tam=count($valor);
                    $camp1=$tam-1;
                    $camp2=$tam-2;
                    $tiempo1=$valor[$camp2];
                    $tiempo2=$valor[$camp1];
                    $tam_var=strlen($tiempo2);
                    if($tam_var>5) {
                    }else{
                      $camp1=$tam-2;
                      $camp2=$tam-3;
                      $tiempo1=$valor[$camp2];
                      $tiempo2=$valor[$camp1];
                    }
                 }else{
                  $strig=array('DESPEDIDA');
                  $pos=strpos($datos,'DESPEDIDA');
                  if($pos === false){
                      $strig=array('DESP');
                      $pos=strpos($datos,'DESP');
                      if($pos === false){
                        $strig=array('PRESENTACION');
                        $pos=strpos($datos,'PRESENTACION');
                        if($pos === false){
                          $strig=array('FLAH');
                          $pos=strpos($datos,'FLAH');
                          if($pos === false){
                            $strig=array('TRAN');
                            $pos=strpos($datos,'TRAN');
                            if($pos === false){
                              $strig=array('TX');
                              $pos=strpos($datos,'TX');
                              if($pos === false){
                                    $strig=array('TIMELAPS');
                                    $pos=strpos($datos,'TIMELAPS');
                                    if($pos === false){
                                        $strig=array('CORTINAS');
                                        $pos=strpos($datos,'CORTINAS');
                                        if($pos === false){
                                            $strig=array('CORT');
                                            $pos=strpos($datos,'CORT');
                                            if($pos === false){
                                              $strig=array('CABE');
                                              $pos=strpos($datos,'CABE');
                                              if($pos === false){
                                                $strig=array('RECA');
                                                $pos=strpos($datos,'RECA');
                                                if($pos === false){
                                                  $strig=array('FACH');
                                                  $pos=strpos($datos,'FACH');
                                                  if($pos === false){
                                                    $strig=array('DESPEDIDAS');
                                                    $pos=strpos($datos,'DESPEDIDAS');
                                                    if($pos === false){
                                                        $strig=array('CRED');
                                                        $pos=strpos($datos,'CRED');
                                                        if($pos === false){
                                                           $strig=array('FOTO');
                                                           $pos=strpos($datos,'FOTO');
                                                            if($pos === false){
                                                                $strig=array('PRES');
                                                                $pos=strpos($datos,'PRES');
                                                                if($pos === false){
                                                                   $strig=array('FX');
                                                                   $pos=strpos($datos,'FX');
                                                                  if($pos === false){
                                                                   //echo print_r($datos).'<br>';
                                                                    $total_valores[$i]=array('libreto'=>$valor[5],'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                                    $i++;
                                                                  }else{
                                                                    $letters = array('FX');
                                                                    $number   = array('');
                                                                    $valor_fx  = str_replace($letters, $number, $valor[5]);
                                                                    $total_valores[$i]=array('libreto'=>$valor_fx,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                                    $i++;
                                                                    $fx[$cont_fx]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                                    $cont_fx++;
                                                                  }  
                                                                }else{
                                                                  //echo $datos.'<br>';
                                                                  $total_presentacion[$presentacion]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                                  $presentacion++;
                                                                }
                                                              }else{
                                                                $total_foto[$foto]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                                $foto++;
                                                              }          
                                                        }else{
                                                          $total_cred[$cred]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                          $cred++;
                                                          
                                                        }
                                                    }else{
                                                      //echo $datos.'<br>';
                                                      $total_despedias[$despedia]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                      $despedia++;
                                                    }
                                                  }else{
                                                    //echo $datos.'<br>';
                                                    $total_fach[$fach]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                    $fach++;
                                                  }
                                                }else{
                                                  //echo $datos.'<br>';
                                                  $total_recap[$recap]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                  $recap++;
                                                }
                                              }else{
                                                //echo $datos.'<br>';
                                                $total_cabe[$cabe]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                                $cabe++;
                                              }
                                            }else{
                                              //echo $datos.'<br>';
                                               $total_cortinas[$cortinas]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                               $cortinas++;
                                            }
                                        }else{
                                          //echo $datos.'<br>';
                                          $total_cortinas[$cortinas]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                          $cortinas++;
                                        }
                                    }else{
                                     // echo $datos.'dasdsa<br>';
                                      $total_timelaps[$timelaps]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                      $timelaps++;
                                    }
                              }else{
                                //echo $datos.'<br>';
                                $total_timelaps[$timelaps]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                                $timelaps++;
                              }
                            }else{
                              //echo $datos.'<br>';
                              $total_timelaps[$timelaps]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                              $timelaps++;
                            }
                          }else{
                           // echo $datos.'<br>';
                             $total_flah[$flah]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                             $flah++;
                          }
                        }else{
                          //echo $datos.'<br>';
                          $total_presentacion[$presentacion]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                          $presentacion++;
                        }
                      }else{
                        //echo $datos.'<br>';
                        $total_despedias[$despedida]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                        $despedida++;
                      }
                  }else{
                    //echo $datos.'<br>';
                    $total_despedias[$despedida]=array('libreto'=>$datos,'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
                    $despedida++;
                  }

                 }
                 
               }
               if($cont2==2){
                     $cont2=1;
               }else{
                     $cont2=2;
               }
               $cont++;
         }      
       
    }
   //echo print_r($fx).'<br>';


    $i=0;
    $lib_esant='';
    $lib_eant_varios='';
    $cont_es=0;
    $cont=0;
    $cont_cred=0;
    $totalanterior=0;
    $totalanterior_cuadros=0;
    $totalanterior_varios=0;
    $totalanterior_cuadros_varios=0;
    asort($total_valores);
    $numero_escenas=1;
    $total_cred=0;
    $numero_escenas_varias=1;
    $cont_varias=0;
    $datos_final_varias='';
    $datos_cred='';
    $escena_final_varias='';
    foreach ($total_valores as $t) {
      $valor=explode("_",$t['libreto']);
      $tam=count($valor);
            if($valor[0]!="IMG"and $tam>=3 and $t['tiempo1'] and $t['tiempo2']){
                    $libreto=explode("_",$t['libreto']);
                    $letra='P';
                    $pos =strpos($libreto['2'],$letra);
                    if($pos==false){
                        $lib=$t['libreto'];
                        $lib=explode("P",$lib);
                        $lib_es_varios=$lib;
                       // echo $t['tiempo1'].'--'.$t['tiempo2'].'<br>';
                        $h1=explode(":",$t['tiempo1']);
                        $h2=explode(":",$t['tiempo2']);
                        $hora=$h1['0']-$h2['0'];
                        $minutos=$h1['1']-$h2['1'];
                        $segundos=$h1['2']-$h2['2'];
                        $milisegundos=$h1['3']-$h2['3'];

                        $cuadro1=$h1['3'];
                      $cuadro2=$h2['3'];
                      if($cuadro1<$cuadro2){
                        $cuadro1=$cuadro1+30;
                        $segundos1=$h1['2']-1;
                      }else{
                        $segundos1=$h1['2'];
                      }
                      $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
                      $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
                     // echo $segundos1.'--'.$segundos2.'<br>';
                      $total=$segundos1-$segundos2;
                      $total_cuadros=$cuadro1-$cuadro2;
                      $sumar_milisegundos=$this->sumar_milisegundos($total);
                      $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;


                            if($cont_varias==0){
                              $datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
                              $numero_escenas_varias=$numero_escenas_varias+1;
                              $cont_varias++;
                            }else{
                                  if($lib_eant_varios==$lib['0']){
                                      $total=$total+$totalanterior_varios;
                                      $sumar_milisegundos=$this->sumar_milisegundos($total);

                                      $total_cuadros=$total_cuadros+$totalanterior_cuadros_varios;
                                       $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;

                                      $cont_varias=$cont_varias-1;
                                      //////inicion if////
                                        if($cont_varias>0){
                                          $numero_escenas_varias=$numero_escenas_varias+1;  
                                        }
                                      //////fin if////
                                        $datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
                                        if($cont_varias==0){
                                           $numero_escenas_varias=$numero_escenas_varias+1;  
                                        }
                                       $cont_varias++;
                                  }else{
                                        $numero_escenas_varias=1;
                                        $datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
                                        $cont_varias++;
                                  }
                            }
                        $totalanterior_varios=$total;
                        $totalanterior_cuadros_varios=$total_cuadros;
                        $lib_eant_varios=$lib['0'];
                    }else{
                      $libreto_escena=$libreto['1'];
                      $escena=explode("P",$libreto['2']);
                      $lib_es=$libreto_escena.$escena['0'];
                      //echo $t['tiempo1'].'--'.$t['tiempo2'].'<br>';
                      $h1=explode(":",$t['tiempo1']);
                      $h2=explode(":",$t['tiempo2']);
                      $hora=$h1['0']-$h2['0'];
                      $minutos=$h1['1']-$h2['1'];
                      $segundos=$h1['2']-$h2['2'];
                      $milisegundos=$h1['3']-$h2['3'];

                      $cuadro1=$h1['3'];
                      $cuadro2=$h2['3'];
                      if($cuadro1<$cuadro2){
                        $cuadro1=$cuadro1+30;
                        $segundos1=$h1['2']-1;
                      }else{
                        $segundos1=$h1['2'];
                      }
                      $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
                      $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
                     // echo $segundos1.'--'.$segundos2.'<br>';
                      $total=$segundos1-$segundos2;
                      $total_cuadros=$cuadro1-$cuadro2;
                      $sumar_milisegundos=$this->sumar_milisegundos($total);

                      //echo $sumar_milisegundos.':dasdad'.($cuadro1-$cuadro2).'adsda<br>';
                      $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
                      if($cont==0){
                        $letters = array('A', 'B','C','D','E','F','G');
                        $number   = array('.1', '.2','.3','.4','.5','.6','.7');
                        $escena_arc  = str_replace($letters, $number, $escena['0']);

                        $escena_arc=ltrim($escena_arc,'0');

                        $pos=strpos($escena_arc,'.');
                        if($pos === false){

                        }else{
                           $num_escena=explode('.', $escena_arc);
                           if($num_escena['1']<10){
                              $escena_arc=$num_escena['0'].'.0'.$num_escena['1'];
                           }
                        }
                        $datos=array('id_produccion'=>$id_produccion,'libreto'=>$libreto_escena,'escena'=>$escena_arc);
                        $escena_id=$this->model_post_produccion->validar_escena_archivo($datos);
                            if($escena_id){
                              $id_escena=$escena_id['0']->id_escena;
                            }else{
                              $id_escena=0;
                            }
                        $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                          if($escena_capitulo){
                           $existe_capitulo=1;
                          }else{
                            $existe_capitulo=0;
                          }
                        $datos_final[$cont]=array('existe_capitulo'=>$existe_capitulo,'id_escena'=>$id_escena,'Libreto' => $libreto_escena,'escena'=>$escena_arc,'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
                        $numero_escenas=$numero_escenas+1;
                        $cont++;
                      }else{
                        if($lib_esant==$lib_es){
                         
                          $total=$total+$totalanterior;
                          $sumar_milisegundos=$this->sumar_milisegundos($total);
                          $total_cuadros=$total_cuadros+$totalanterior_cuadros;
                          //echo $sumar_milisegundos.':'.($total_cuadros+$totalanterior_cuadros).'<br>';
                          $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;

                          $cont=$cont-1;
                                      $letters = array('A', 'B','C','D','E','F','G');
                                      $number   = array('.1', '.2','.3','.4','.5','.6','.7');
                                      $escena_arc  = str_replace($letters, $number, $escena['0']);

                                      $escena_arc=ltrim($escena_arc,'0');

                                      $pos=strpos($escena_arc,'.');
                                      if($pos === false){

                                      }else{
                                         $num_escena=explode('.', $escena_arc);
                                         if($num_escena['1']<10){
                                            $escena_arc=$num_escena['0'].'.0'.$num_escena['1'];
                                         }
                                      }
                                      $datos=array('id_produccion'=>$id_produccion,'libreto'=>$libreto_escena,'escena'=>$escena_arc);
                                      //echo print_r($datos).'<br>';
                                       $escena_id=$this->model_post_produccion->validar_escena_archivo($datos);
                                        if($escena_id){
                                         $id_escena=$escena_id['0']->id_escena;
                                        }else{
                                          $id_escena=0;
                                        }
                                      $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                                        if($escena_capitulo){
                                          $existe_capitulo=1;
                                        }else{
                                          $existe_capitulo=0;
                                        }
                                      $datos_final[$cont]=array('existe_capitulo'=>$existe_capitulo,'id_escena'=>$id_escena,'Libreto' => $libreto_escena,'escena'=>$escena_arc,'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
                                      $numero_escenas=$numero_escenas+1;
                                      $cont++;
                        }else{
                            $numero_escenas=1;
                            $letters = array('A', 'B','C','D','E','F','G');
                            $number   = array('.1', '.2','.3','.4','.5','.6','.7');
                            $escena_arc  = str_replace($letters, $number, $escena['0']);
                            $escena_arc=ltrim($escena_arc,'0');

                            $pos=strpos($escena_arc,'.');
                            if($pos === false){

                            }else{
                               $num_escena=explode('.', $escena_arc);
                               if($num_escena['1']<10){
                                  $escena_arc=$num_escena['0'].'.0'.$num_escena['1'];
                               }
                            }
                            $datos=array('id_produccion'=>$id_produccion,'libreto'=>$libreto_escena,'escena'=>$escena_arc);
                            $escena_id=$this->model_post_produccion->validar_escena_archivo($datos);
                              if($escena_id){
                                $id_escena=$escena_id['0']->id_escena;
                              }else{
                                $id_escena=0;
                              }
                            $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                              if($escena_capitulo){
                                  $existe_capitulo=1;
                              }else{
                                $existe_capitulo=0;
                              }

                            $datos_final[$cont]=array('existe_capitulo'=>$existe_capitulo,'id_escena'=>$id_escena,'Libreto' => $libreto_escena,'escena'=>$escena_arc,'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
                            $cont++;
                            $numero_escenas=$numero_escenas+1;
                        }
                      }
                      
                      $totalanterior=$total;
                      $totalanterior_cuadros=$total_cuadros;
                      $lib_esant=$libreto_escena.$escena['0'];
                    }
                  }else{
                      $valor=explode("_",$t['libreto']);
                      $tam=count($valor);
                      if(isset($valor[1])){
                        $valor2=explode(".",$valor[1]);
                      }
                      
                         if($tam>=2 and $valor2[0]=="CRED" and $t['tiempo1'] and $t['tiempo2']){
                            $h1=explode(":",$t['tiempo1']);
                            $h2=explode(":",$t['tiempo2']);
                            $hora=$h1['0']-$h2['0'];

                            $minutos=$h1['1']-$h2['1'];
                            $segundos=$h1['2']-$h2['2'];
                            $milisegundos=$h1['3']-$h2['3'];

                            $cuadro1=$h1['3'];
                            $cuadro2=$h2['3'];
                            if($cuadro1<$cuadro2){
                              $cuadro1=$cuadro1+30;
                              $segundos1=$h1['2']-1;
                            }else{
                              $segundos1=$h1['2'];
                            }
                            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
                            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
                            $total=$segundos1-$segundos2;
                            $total_cuadros=$cuadro1-$cuadro2;
                            $total_cred=$total_cred+$total;


                            $sumar_milisegundos=$this->sumar_milisegundos($total);
                            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;

                            $datos_cred[$cont_cred]=array('creditos' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
                            $cont_cred++;
                          }else{
                            $letra='FX';
                            $no_leidos[$i]=array('no_leidos'=>$t);
                          }
                  }
        
         $i++; 
    }
        
    $total_creditos=$this->sumar_milisegundos($total_cred);
    $no_leidos='';
    fclose($file);
    if($datos_final_varias){
      $cont_escena=0;
      foreach ($datos_final_varias as $d) {
               $datos=explode("_",$d['Libreto']);
              // echo print_r($datos).'<br>';
               $libreto=$datos['1'];
               if(is_numeric($libreto)){
                     $tam=count($datos)-1;
                     $cont_id_escena=0;
                     $escenas='';
                         for ($i=2; $i <=$tam; $i++) { 
                            if(is_numeric($datos[$i])) {
                                 if(strlen($datos[$i])<3){
                                       
                                       $escena_arc=ltrim($datos[$i],'0');

                                      $pos=strpos($escena_arc,'.');
                                      if($pos === false){

                                      }else{
                                         $num_escena=explode('.', $escena_arc);
                                         if($num_escena['1']<10){
                                            $escena_arc=$num_escena['0'].'.0'.$num_escena['1'];
                                         }
                                      }
                                      $da=array('id_produccion'=>$id_produccion,'libreto'=>$libreto,'escena'=>$escena_arc);
                                      $escena_id=$this->model_post_produccion->validar_escena_archivo($da);
                                      if($escena_id){
                                         $id_escena=$escena_id['0']->id_escena;
                                      }else{
                                          $id_escena=0;
                                      }
                                      $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                                      if($escena_capitulo){
                                          $existe_capitulo=1;
                                      }else{
                                        $existe_capitulo=0;
                                      }
                                      $escenas[$cont_id_escena]=array('id_escena'=>$id_escena,'libreto'=>$libreto,'escena_numero'=>$datos[$i],'existe'=>$existe_capitulo);
                                      $cont_id_escena++;
                                  }else{
                                    $libreto=$datos[$i];
                                  }    
                                    
                            }else{
                              $letra='FX';
                              $pos=strpos($datos[$i],$letra);
                                if($pos){
                                    $es=explode("FX",$datos[$i]); 

                                    $escena_arc=ltrim($es['0'],'0');

                                    $pos=strpos($escena_arc,'.');
                                    if($pos === false){

                                    }else{
                                       $num_escena=explode('.', $escena_arc);
                                       if($num_escena['1']<10){
                                          $escena_arc=$num_escena['0'].'.0'.$num_escena['1'];
                                       }
                                    }
                                    $da=array('id_produccion'=>$id_produccion,'libreto'=>$libreto,'escena'=>$escena_arc); 
                                    $escena_id=$this->model_post_produccion->validar_escena_archivo($da);
                                    if($escena_id){
                                       $id_escena=$escena_id['0']->id_escena;
                                    }else{
                                       $id_escena=0;
                                    }
                                    $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                                    if($escena_capitulo){
                                        $existe_capitulo=1;
                                    }else{
                                      $existe_capitulo=0;
                                    }
                                    $escenas[$cont_id_escena]=array('id_escena'=>$id_escena,'libreto'=>$libreto,'escena_numero'=>$es['0'],'existe'=>$existe_capitulo);
                                    $cont_id_escena++;
                                }else{
                                  $numero=array('1','2','3','4','5','6','7','8','9');
                                  $c=str_split($datos[$i]);
                                  if(is_numeric($c['0'])){
                                   // echo $datos[$i].'<br>';
                                    $letters = array('A', 'B','C','D','E','F','G');
                                    $number   = array('.1', '.2','.3','.4','.5','.6','.7');
                                    $escena  = str_replace($letters, $number, $datos[$i]);
                                    $escena=ltrim($escena,'0');

                                    $pos=strpos($escena,'.');
                                    if($pos === false){

                                    }else{
                                       $num_escena=explode('.', $escena);
                                       if($num_escena['1']<10){
                                          $escena=$num_escena['0'].'.0'.$num_escena['1'];
                                       }
                                    }
                                    $da=array('id_produccion'=>$id_produccion,'libreto'=>$libreto,'escena'=>$escena);

                                    $escena_id=$this->model_post_produccion->validar_escena_archivo($da);
                                    if($escena_id){
                                       $id_escena=$escena_id['0']->id_escena;
                                    }else{
                                        $id_escena=0;
                                    }
                                    $escena_capitulo=$this->model_post_produccion->escena_capitulo($id_escena,$id_capitulo);
                                    if($escena_capitulo){
                                        $existe_capitulo=1;
                                    }else{
                                      $existe_capitulo=0;
                                    }
                                    $escenas[$cont_id_escena]=array('id_escena'=>$id_escena,'libreto'=>$libreto,'escena_numero'=>$escena,'existe'=>$existe_capitulo);
                                    $cont_id_escena++;
                                  }
                                }
                            }
                         }
                     $escena_final_varias[$cont_escena]=array('escenas'=>$escenas,'Libreto' => $d['Libreto'],'existe_capitulo'=>$existe_capitulo,'tiempo'=>$d['tiempo'],'numero_escena'=>$d['numero_escena']);
                     $cont_escena++;
               }else{
                 $a=array('libreto'=>$d['Libreto'],'tiempo1'=>$d['tiempo'],'tiempo2'=>'');
                  $no_leidos2=array('no_leidos'=>$a);
                  $c=count($no_leidos)-1;
                  if($no_leidos and $no_leidos2){
                    $no_leidos[$c]=array_merge($no_leidos,$no_leidos2);  
                  }elseif($no_leidos){
                    $no_leidos[$c]=$no_leidos;  
                  }else{
                    $no_leidos[$c]=$no_leidos2;  
                  }
                  
                }   
      }
    }
    $clips=0;
    $minutos=0;
    $segundos=0;
    $cuadros=0;
    $minutos_final=0;
    $segundos_final=0;
    $cuadros_final=0;
    $minutos_varios=0;
    $segundos_varios=0;
    $cuadros_varios=0;
    foreach ($datos_final as $l) { 
         $clips=$clips+$l['numero_escena'];
         $tiempo=explode(':',$l['tiempo']);
       
          if($tiempo[2]<10){
          $tiempo[2]='0'.$tiempo[2];
          }
          if($l['id_escena']==0){
          }else{ 
          
          }
            $minutos=$minutos+$tiempo["1"];
            $segundos=$segundos+$tiempo["2"];
            $cuadros=$cuadros+$tiempo["3"];
            $minutos_final=$minutos_final+$tiempo["1"];
            $segundos_final=$segundos_final+$tiempo["2"];
            $cuadros_final=$cuadros_final+$tiempo["3"];
    }

    if($datos_final_varias){
        foreach ($datos_final_varias as $l) { 
             $clips=$clips+$l['numero_escena'];
             $tiempo=explode(':',$l['tiempo']);
            
             if($tiempo[2]<10){
              $tiempo[2]='0'.$tiempo[2];
             }
             $minutos=$minutos+$tiempo["1"];
             $segundos=$segundos+$tiempo["2"];
             $cuadros=$cuadros+$tiempo["3"];
             $minutos_varios=$minutos_varios+$tiempo["1"];
             $segundos_varios=$segundos_varios+$tiempo["2"];
             $cuadros_varios=$cuadros_varios+$tiempo["3"];
             
        }
     }   
     
    $h=0;
    $total_creditos_final_segundos1=0;
    $total_creditos_final_segundos2=0;
    $total_creditos_final_cuadros=0;
    $total_presentacion_fin='';
    if($total_presentacion){
      foreach ($total_presentacion as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;
            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;

            $total_presentacion_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_fach_fin='';
    if($total_fach){
      foreach ($total_fach as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;
            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_fach_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_recap_fin='';
    if($total_recap){
      foreach ($total_recap as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_recap_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_cabe_fin='';
    if($total_cabe){
      foreach ($total_cabe as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_cabe_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_cortinas_fin='';
    if($total_cortinas){
      foreach ($total_cortinas as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_cortinas_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_timelaps_fin='';
    if($total_timelaps){
      foreach ($total_timelaps as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_timelaps_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_flah_fin='';
    if($total_flah){
      foreach ($total_flah as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_flah_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_cred_fin='';
    if($total_cred){
      foreach ($total_cred as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_cred_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_despedias_fin='';
    if($total_despedias){
      foreach ($total_despedias as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_despedias_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $h=0;
    $total_foto_fin='';
    if($total_foto){
      foreach ($total_foto as $t) {
            $h1=explode(":",$t['tiempo1']);
            $h2=explode(":",$t['tiempo2']);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];

            $cuadro1=$h1['3'];
            $cuadro2=$h2['3'];
            if($cuadro1<$cuadro2){
              $cuadro1=$cuadro1+30;
              $segundos1=$h1['2']-1;
            }else{
              $segundos1=$h1['2'];
            }
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$segundos1,$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $total_cuadros=$cuadro1-$cuadro2;

            $total_creditos_final_segundos1+=$segundos1;
            $total_creditos_final_segundos2+=$segundos2;
            $total_creditos_final_cuadros+=$total_cuadros;

            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $sumar_milisegundos=$sumar_milisegundos.':'.$total_cuadros;
            $total_foto_fin[$h]=array('libreto' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
            $h++;
      }
    }
    $no_leidos_final=array();
    $cont_no_leidos=0;
    if($no_leidos){
      foreach ($no_leidos as $n) {
            $tiempo1=$n['no_leidos']['tiempo1'];
            $tiempo2=$n['no_leidos']['tiempo2'];
            
            if(!$tiempo1){
              $tiempo1='00:00:00:00';
            }
            if(!$tiempo2){
              $tiempo2='00:00:00:00';
            }

            $h1=explode(":",$tiempo1);
            $h2=explode(":",$tiempo2);
            $hora=$h1['0']-$h2['0'];
            $minutos=$h1['1']-$h2['1'];
            $segundos=$h1['2']-$h2['2'];
            $milisegundos=$h1['3']-$h2['3'];
            $segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$h1['2'],$h1['3']);
            $segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
            $total=$segundos1-$segundos2;
            $sumar_milisegundos=$this->sumar_milisegundos($total);
            $no_leidos_final[$cont_no_leidos]=array('libreto'=>$n['no_leidos']['libreto'],'tiempo'=>$sumar_milisegundos);
            $cont_no_leidos++;
       } 
    }
    $total_creditos_final_segundos1+=$segundos1;
    $total_creditos_final_segundos2+=$segundos2;
    $total_creditos_final=$this->sumar_milisegundos($total_creditos_final_segundos1-$total_creditos_final_segundos2);
   // $total_creditos_final=$total_creditos_final.':'.$total_creditos_final_cuadros;
    $h1=explode(":",$total_creditos_final);

    $total_creditos_final=$this->calculo_tiempo_post($h1['1'],$h1['2'],$total_creditos_final_cuadros);

    if($h1['1']){
      //$total_creditos_final=$h1['1'].':'.$h1['2'];
      $tiempo_total=$this->calculo_tiempo2($minutos_final+$minutos_varios+$h1['1'],$segundos_final+$segundos_varios+$h1['2']);
    }else{
      $tiempo_total=$this->calculo_tiempo2($minutos_final+$minutos_varios,$segundos_final+$segundos_varios);
    }
    
    $tiempo_final=$this->calculo_tiempo_post($minutos_final,$segundos_final,$cuadros_final);
    $tiempo_varios=$this->calculo_tiempo_post($minutos_varios,$segundos_varios,$cuadros_varios);
    $data['produccion']=$produccion;
    $data['datos_final']=$datos_final;
    $data['datos_cred']=$datos_cred;
    $data['total_creditos']=$total_creditos;
    $data['no_leidos_final']=$no_leidos_final;
    $data['datos_final_varias']=$datos_final_varias;
    $data['escena_final_varias']=$escena_final_varias;
    $data['id_capitulo']=$id_capitulo;
    $data['id_produccion']=$id_produccion;
    $data['capitulo']=$capitulo;
    $data['clips']=$clips;
    $data['tiempo_total']=$tiempo_total;
    $data['tiempo_final']=$tiempo_final;
    $data['tiempo_varios']=$tiempo_varios;

    $data['total_presentacion_fin']=$total_presentacion_fin;
    $data['total_fach_fin']=$total_fach_fin;
    $data['total_recap_fin']=$total_recap_fin;
    $data['total_cabe_fin']=$total_cabe_fin;
    $data['total_cortinas_fin']=$total_cortinas_fin;
    $data['total_timelaps_fin']=$total_timelaps_fin;
    $data['total_flah_fin']=$total_flah_fin;
    $data['total_cred_fin']=$total_cred_fin;
    $data['total_despedias_fin']=$total_despedias_fin;
    $data['total_foto_fin']=$total_foto_fin;
    $data['total_creditos_final']=$total_creditos_final;
    
    $data['view']='post_produccion/resultado';
    $this->load->view('includes/template',$data);

  }

  public function horas_milisegundos($hora,$minutos,$segundos,$milisegundos){
    $m=$hora*60;
    $s=($m+$minutos)*60;
    /*$milisegundos=$milisegundos/60;
    $milisegundos=round($milisegundos);
    $mil=(($s+$segundos+$milisegundos)*1000);*/
    $mil=(($s+$segundos)*1000);
    return $mil;
  }


  public function sumar_milisegundos($milisegundos){
       $minutos=0;
       $hora=0;
       $segundos=0;

      while($milisegundos>=3600000){
          $hora+=1;

          $milisegundos= $milisegundos-3600000;
      }

      while($milisegundos>=60000){
          $minutos+=1;
          $milisegundos= $milisegundos-60000;
      }

      while($milisegundos>=1000){
          $segundos+=1;
          $milisegundos= $milisegundos-1000;
      }

      while($milisegundos>=30){
          $segundos+=1;
          $milisegundos= $milisegundos-30;
      }
      /*while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }*/
     

      if($hora<10){
        $hora='0'.$hora;
      }
      if($minutos<10){
        $minutos='0'.$minutos;
      }
      if($segundos<10){
        $segundos='0'.$segundos;
      }
      if($milisegundos<10){
        $milisegundos='0'.$milisegundos;
      }
     /* while($milisegundos>=1000){
        $milisegundos=$milisegundos*10;
          echo '3';
          /*$minutos+=1;
          $segundos= $milisegundos-1000;
      }*/
    //return $hora.':'.$minutos.':'.$segundos.':'.$milisegundos;
      return $hora.':'.$minutos.':'.$segundos;
  }

  public function libretos(){
    $id_produccion=$_POST['id_produccion'];
    $libretos = $this->model_capitulos->capitulos_produccion_2($id_produccion);
    $data['libretos']=$libretos;
    echo json_encode($data);
  }

  public function cargar_escenas(){
    $id_capitulo=$_POST['id_capitulo'];
    $id_produccion=$_POST['id_produccion'];
    $permitir=$_POST['permitir'];
    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    $limit=$_POST['limit'];
    $escenas=$this->model_post_produccion->escenas_id_capitulo($id_capitulo,$limit);
    $total_escena=$this->model_post_produccion->total_escenas_id_capitulo($id_capitulo);
    
   
    if(!$escenas){
      $data['existe']=1;
    }else{ $html='';
        foreach ($escenas as $e) {
          $html=$html.'<tr class="escenas escena_'.$e['id_capitulo_escena'].'">
          <td>'.$e['numero'] .'</td>
          <td>'.$e['numero_escena'] .'</td>
          <td class="align_center">'.$e['duracion_estimada_minutos'].':'.$e['duracion_estimada_segundos'] .'</td>
          <td class="align_center">'.$e['duracion_real_minutos'].':'.$e['duracion_real_segundos'].'</td>';
          if(($e['tiempo_post_minutos']+$e['tiempo_post_segundos'])==0){
            if($produccion['0']->estado!=2 and $permitir==1){
            $html=$html.'<td class="align_center">
            <a href="#" onclick="return false;" class="add_tiempo_post" data-id="'.$e['id_escena'].'">Agregar tiempo</a>';
            }else{
              $html=$html.'<td class="align_center">00:00';
            }    

            if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)){ 
                      $minutos='0'.$e['tiempo_post_minutos']; 
                     if(strlen($minutos)<2){
                       $minutos='0'.$minutos; 
                     } 
                  }else{ 
                    $minutos=$e['tiempo_post_minutos'];
                  } 
                    
                  if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ 
                    $segundos='0'.$e['tiempo_post_segundos']; 
                    if(strlen($segundos)<2){
                       $segundos='0'.$segundos; 
                     } 
                  }else{
                   $segundos=$e['tiempo_post_segundos'];
                  } 

                  if($e['tiempo_post_cuadros']==null){ 
                    $cuadros='00';
                  }elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ 
                    $cuadros='0'.$e['tiempo_post_cuadros']; 
                    if(strlen($cuadros)<2){
                       $cuadros='0'.$cuadros; 
                     } 
                  }else{
                   $cuadros=$e['tiempo_post_cuadros'];
                  }
            $html=$html.'
            <div class="tiempo_post_'.$e['id_escena'].' hide_box tiempos_pos_box" ">
                 <span class="close_box"></span>'.
                 form_open("post_produccion/save_post_produccion").'
                 <input type="hidden" name="id_escena" value="'.$e['id_escena'].'">
                  <input type="hidden" name="id_capitulo" value="'.$id_capitulo.'">
                 <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Minutos
                     <input type="text" name="minutos_post" value="'.$minutos.'" class="minutos m_'.$e['id_escena'].' columns">
                   </div>
                   <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Segundos
                     <input type="text" name="segundos_post" value="'.$segundos.'" class="segundos s_'.$e['id_escena'].' columns">
                   </div>
                   <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Cuadros
                     <input type="text" name="cuadros_post" value="'.$cuadros.'" class="segundos c_'.$e['id_escena'].' columns">
                   </div>
                 <input type="hidden" name="id_produccion" value="'.$id_produccion.'">   
                 <input type="hidden" name="id_capitulo" value="'.$id_capitulo.'">   
                 <a class="cancel_icon close_box" style="margin-top:17px"></a>
                 <a class="save_icon2 save_tiempo_post" data-idescena="'.$e['id_escena'].'" href="#" style="margin-top:17px"></a>
                 '.form_close().'
             </div> 
           </td>
           <td class="eliminar_escena_capitulo" data-idescena="'.$e['id_capitulo_escena'].'">';
            if($produccion['0']->estado!=2 and $permitir==1){
            $html=$html.'<a href="#" onclick="return false;">eliminar</a>';
            }
            $html=$html.'</td></tr>';
          }else{
              $html=$html.'<td class="align_center">';
              if($produccion['0']->estado!=2){
                 if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)){ 
                      $minutos='0'.$e['tiempo_post_minutos']; 
                     if(strlen($minutos)<2){
                       $minutos='0'.$minutos; 
                     } 
                  }else{ 
                    $minutos=$e['tiempo_post_minutos'];
                  } 
                    
                  if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ 
                    $segundos='0'.$e['tiempo_post_segundos']; 
                    if(strlen($segundos)<2){
                       $segundos='0'.$segundos; 
                     } 
                  }else{
                   $segundos=$e['tiempo_post_segundos'];
                  } 

                  if($e['tiempo_post_cuadros']==null){ 
                    $cuadros='00';
                  }elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ 
                    $cuadros='0'.$e['tiempo_post_cuadros']; 
                    if(strlen($cuadros)<2){
                       $cuadros='0'.$cuadros; 
                     } 
                  }else{
                   $cuadros=$e['tiempo_post_cuadros'];
                  } 

               if($permitir==1){
                   $html=$html.'<a href="#" onclick="return false;" class="add_tiempo_post" data-id="'.$e['id_escena'].'">';
                  
                  $html=$html.$minutos.':'.$segundos.'-'.$cuadros.'</a>';   
                }else{
                  $html=$html.$minutos.':'.$segundos.'-'.$cuadros.'</a>';   
                }
              
          }
          $html=$html.'
            <div class="tiempo_post_'.$e['id_escena'].' hide_box tiempos_pos_box" ">
                 <span class="close_box"></span>'.
                 form_open("post_produccion/save_post_produccion").'
                 <input type="hidden" name="id_escena" value="'.$e['id_escena'].'">
                 <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Minutos
                     <input type="text" name="minutos_post" value="'.$minutos.'" class="minutos m_'.$e['id_escena'].' columns">
                   </div>
                   <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Segundos
                     <input type="text" name="segundos_post" value="'.$segundos.'" class="segundos s_'.$e['id_escena'].' columns">
                   </div>
                   <div style="width:68px;float:left;font-size:10px;color:#fff;">
                     Cuadros
                     <input type="text" name="cuadros_post" value="'.$cuadros.'" class="segundos c_'.$e['id_escena'].' columns">
                   </div>
                 <input type="hidden" name="id_produccion" value="'.$id_produccion.'">   
                 <input type="hidden" name="id_capitulo" value="'.$id_capitulo.'">   
                 <a class="cancel_icon close_box" style="margin-top:17px"></a>
                 <a class="save_icon2 save_tiempo_post" data-idescena="'.$e['id_escena'].'" href="#" style="margin-top:17px"></a>
                 '.form_close().'
             </div> 
           </td>
           <td class="eliminar_escena_capitulo" data-idescena="'.$e['id_capitulo_escena'].'">';
            if($produccion['0']->estado!=2 and $permitir==1){
            $html=$html.'<a href="#" onclick="return false;">eliminar</a>';
            }
            $html=$html.'</td></tr>';
        }
        }    
      $data['html']=$html;
      $data['total_escena']=$total_escena;
      $data['existe']=2;
    }
    echo json_encode($data);

  }

  public function guardar_datos_archivo(){
      $id_capitulo=$this->input->post('id_capitulo');
      $id_produccion=$this->input->post('id_produccion');
      $escena=$this->input->post('escena');
      $minutos=$this->input->post('tiempo_escena_minutos');
      $segundos=$this->input->post('tiempo_escena_segundos');
      $cuadros=$this->input->post('tiempo_escena_cuadros');
      $cont=0;
      foreach ($escena as $e) {
       // $data=array('id_escena'=>$e,'tiempo_post_minutos'=>$minutos[$cont],'tiempo_post_segundos'=>$segundos[$cont],
         //  'tiempo_post_cuadros'=>$cuadros[$cont]);
        //$this->model_post_produccion->update_tiempo_post($data);
        //$this->model_post_produccion->eliminar_escena_capitulo_id_escena($e);
        $datos=array('id_capitulo' =>$id_capitulo,'id_escena'=>$e,'tiempo_post_minutos'=>$minutos[$cont],'tiempo_post_segundos'=>$segundos[$cont],
           'tiempo_post_cuadros'=>$cuadros[$cont]);
        $es=$this->model_post_produccion->insert_capitulos_has_esena($datos);
        $cont++;
      }

      $escena_multi=$this->input->post('escena_multi');
      $minutos=$this->input->post('escena_multi_minutos');
      $segundos=$this->input->post('escena_multi_segundos');
      $cuadros=$this->input->post('escena_multi_cuadros');
      $cont=0;
      foreach ($escena_multi as $e) {
         if($minutos[$cont] or $segundos[$cont]){
           /* $data=array('id_escena'=>$e,'tiempo_post_minutos'=>$minutos[$cont],'tiempo_post_segundos'=>$segundos[$cont],
              'tiempo_post_cuadros'=>$cuadros[$cont]);
            $this->model_post_produccion->update_tiempo_post($data);
            $this->model_post_produccion->eliminar_escena_capitulo_id_escena($e);*/
            $datos=array('id_capitulo' =>$id_capitulo,'id_escena'=>$e,'tiempo_post_minutos'=>$minutos[$cont],'tiempo_post_segundos'=>$segundos[$cont],
           'tiempo_post_cuadros'=>$cuadros[$cont]);
            $es=$this->model_post_produccion->insert_capitulos_has_esena($datos);
            $cont++;
          }  
      }

      $credito_minutos=$this->input->post('credito_minutos');
      $credito_segundos=$this->input->post('credito_segundos');
      $credito_cuadros=$this->input->post('credito_cuadros');

      $flashback_minutos=$this->input->post('flashback_minutos');
      $flashback_segundos=$this->input->post('flashback_segundos');
      $flashback_cuadros=$this->input->post('flashback_cuadros');

      $transiciones_minutos=$this->input->post('transiciones_minutos');
      $transiciones_segundos=$this->input->post('transiciones_segundos');
      $transiciones_cuadros=$this->input->post('transiciones_cuadros');


      $cortinillas_minutos=$this->input->post('cortinillas_minutos');
      $cortinillas_segundos=$this->input->post('cortinillas_segundos');
      $cortinillas_cuadros=$this->input->post('cortinillas_cuadros');

      $cabezote_minutos=$this->input->post('cabezote_minutos');
      $cabezote_segundos=$this->input->post('cabezote_segundos');
      $cabezote_cuadros=$this->input->post('cabezote_cuadros');

      $recap_minutos=$this->input->post('recap_minutos');
      $recap_segundos=$this->input->post('recap_segundos');
      $recap_cuadros=$this->input->post('recap_cuadros');

      $stab_minutos=$this->input->post('stab_minutos');
      $stab_segundos=$this->input->post('stab_segundos');
      $stab_cuadros=$this->input->post('stab_cuadros');

      $despedida_minutos=$this->input->post('despedida_minutos');
      $despedida_segundos=$this->input->post('despedida_segundos');
      $despedida_cuadros=$this->input->post('despedida_cuadros');

      $presentacion_minutos=$this->input->post('presentacion_minutos');
      $presentacion_segundos=$this->input->post('presentacion_segundos');
      $presentacion_cuadros=$this->input->post('presentacion_cuadros');

      $foto_minutos=$this->input->post('foto_minutos');
      $foto_segundos=$this->input->post('foto_segundos');
      $foto_cuadros=$this->input->post('foto_cuadros');

      $data=array('credito_minutos'=>$credito_minutos,
                  'credito_segundos'=>$credito_segundos,
                  'credito_cuadros'=>$credito_cuadros,
                  'flashback_minutos'=>$flashback_minutos,
                  'flashback_segundos'=>$flashback_segundos,
                  'flashback_cuadros'=>$flashback_cuadros,
                  'transiciones_minutos'=>$transiciones_minutos,
                  'transiciones_segundos'=>$transiciones_segundos,
                  'transiciones_cuadros'=>$transiciones_cuadros,
                  'cortinillas_minutos'=>$cortinillas_minutos,
                  'cortinillas_segundos'=>$cortinillas_segundos,
                  'cortinillas_cuadros'=>$cortinillas_cuadros,
                  'cabezote_minutos'=>$cabezote_minutos,
                  'cabezote_segundos'=>$cabezote_segundos,
                  'cabezote_cuadros'=>$cabezote_cuadros,
                  'recap_minutos'=>$recap_minutos,
                  'recap_segundos'=>$recap_segundos,
                  'recap_cuadros'=>$recap_cuadros,
                  'stab_minutos'=>$stab_minutos,
                  'stab_segundos'=>$stab_segundos,
                  'stab_cuadros'=>$stab_cuadros,
                  'despedida_minutos'=>$despedida_minutos,
                  'despedida_segundos'=>$despedida_segundos,
                  'despedida_cuadros'=>$despedida_cuadros,
                  'presentacion_minutos'=>$presentacion_minutos,
                  'presentacion_segundos'=>$presentacion_segundos,
                  'presentacion_cuadros'=>$presentacion_cuadros,
                  'foto_minutos'=>$foto_minutos,
                  'foto_segundos'=>$foto_segundos,
                  'foto_cuadros'=>$foto_cuadros,
        );
        $update_capitulo=$this->model_post_produccion->update_capitulo_extras($id_capitulo,$data);

    redirect ($this->lang->lang().'/post_produccion/capitulo/'.$id_produccion.'/'.$id_capitulo);
  }


  public function caja_colores($id_produccion){
    $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
    if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5'){
      $id_user=$this->session->userdata('id_pruduction_suite');
      $user=$this->model_admin->rolUserId($id_user);
      $tipo_rol=$user['0']['id_rol_otros'];
      if($tipo_rol==2 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_rol=='6' OR $tipo_usuario=='4' OR $tipo_rol=='9' OR $tipo_rol=='10' OR $tipo_rol=='11'  OR $tipo_rol=='12' OR $tipo_rol=='13' OR $tipo_rol=='14'){
        $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
        $capitulos=$this->model_post_produccion->capitulos_has_escenas($id_produccion);
        $data['produccion']=$produccion;
        $data['id_produccion']=$id_produccion;
        $data['capitulos']=$capitulos;
        $data['view']='post_produccion/caja_colores';
        $this->load->view('includes/template',$data);
      }else{
        redirect ($this->lang->lang().'/produccion/producciones');
      } 
    }else{
      redirect ($this->lang->lang().'/produccion/producciones');
    } 
  }


  public function detalles_capitulo(){
    $id_capitulo=$_POST['id_capitulo'];
    $detalle=$_POST['detalle'];
    $valor=$this->model_post_produccion->detalles_capitulo($id_capitulo);
    $m=$detalle.'_minutos';
    $s=$detalle.'_segundos';
    $c=$detalle.'_cuadros';
    if($valor['0']->$m<10){
      $m='0'.$valor['0']->$m;
    }else{
      $m=$valor['0']->$m;
    }
    if($valor['0']->$s<10){
      $s='0'.$valor['0']->$s;
    }else{
      $s=$valor['0']->$s;
    }

    if($valor['0']->$c<10){
      $c='0'.$valor['0']->$c;
    }else{
      $c=$valor['0']->$c;
    }
    $data['minutos']=$m;
    $data['segundos']=$s;
    $data['cuadros']=$c;
    echo json_encode($data);
  }

  public function duplicar($id){
    $lib=$this->model_post_produccion->libretos_id_produccion($id);
    foreach ($lib as $l) {
      $numero=$l['numero'];
      $fecha_entrega=$l['fecha_aire'];
      $data=array(
              'id_produccion'=>$id,
              'numero'=>$numero,
              'id_estado'=>2,
              'fecha_entrega'=>$fecha_entrega,
       );
      $this->model_capitulos->insertar_capitulo_post($data);
      $id_capitulo = mysql_insert_id();
      $data_capitulo = array(
        'id_capitulo' => $id_capitulo, 
        'id_estado' => 2,
        'activo'=>1
      );
      $this->model_post_produccion->insertar_estado_capitulo($data_capitulo);
        /*$id_user_prod=$this->model_post_produccion->user_produccion_rol2($id);
            if($id_user_prod){
                    foreach ($id_user_prod as $user) {
                        $data=array(
                        'id_user'=>$user['id_usuario'],
                        'id_capitulo'=>$id_capitulo,
                        'estado'=>1,
                        'id_estado_capitulo'=>2,
                      );
                      $this->model_capitulos->insertar_capitulo_user($data);
                    }
            }*/
        }
  }


   public function email($correo='alexander.ospina@cincoveinticinco.com',$cuerpo='hola'){
        $asunto='asignacion capitulo';
        $remitente='info@rtitv.com';
        $sheader="To: Productionsuite \n";
        $sheader.="From: Productionsuite ".$remitente."\n";
        $sheader=$sheader."Mime-Version: 1.0\n";
        $sheader=$sheader."Content-Type: text/html";          
        mail($correo,$asunto,$cuerpo,$sheader);
    }

  public function guardar_tiempo_post(){
    $id_capitulo=$this->input->post('id_capitulo');
    $tam=$this->input->post('tam');
    $id_produccion=$this->input->post('id_produccion');
    $cont=0;
    while ($cont<$tam) {
      $id_escena=$this->input->post('escena_'.$cont);
      $minutos=$this->input->post('minutos_'.$cont);
      $segundos=$this->input->post('segundos_'.$cont);
      $cuadros=$this->input->post('cuadros_'.$cont);
      if($segundos or $minutos){
          if(!$minutos){
            $minutos=0;
          }
          if(!$segundos){
            $segundos=0;
          }
           $data=array('id_capitulo'=>$id_capitulo,'id_escena'=>$id_escena,'tiempo_post_minutos'=>$minutos,'tiempo_post_segundos'=>$segundos,'tiempo_post_cuadros'=>$cuadros);
          $this->model_post_produccion->update_tiempo_post($data);
      }
      $cont++;
    }
    redirect ($this->lang->lang().'/post_produccion/capitulo/'.$id_produccion.'/'.$id_capitulo.'/1');
  }  

   public function actualizar_fechas_capitulos(){
        $id_produccion=$_POST['id_produccion'];
        $id_capitulo=$_POST['id_capitulo'];
        $fecha_entrega=$_POST['fecha'];
        $fecha_entrega=date('Y-m-d',strtotime($fecha_entrega));
        $data=array('id_capitulo'=>$id_capitulo,'fecha_entrega'=>$fecha_entrega);
        $resultado = $this->model_post_produccion->update_capitulo_fecha_entrega($data);
        $capitulo_select=$this->model_post_produccion->capitulo_id($id_capitulo);
        $numero_capitulos = $this->model_post_produccion->contar_capitulos($capitulo_select[0]->numero,$id_produccion);
        $numero_capitulos=$numero_capitulos['0']->total;
        $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
        $id_dias_grabacion = $produccion[0]->id_dias_grabacion;
        $dias = $this->model_capitulos->contar_dias_aire($id_dias_grabacion);
        $j=86400;
            $capitulo_select=$this->model_post_produccion->capitulo_id($id_capitulo);
            $ultima_fecha = $capitulo_select[0]->fecha_entrega;
            for($i=$capitulo_select[0]->numero+1; $i<=$numero_capitulos+1; ++$i) {
                  $active = 0;
              $capitulo = $this->model_post_produccion->capitulo_by_numero($id_produccion,$i);
              if($capitulo){
                        if($dias[0]['lunes']==0 AND $dias[0]['martes']==0 AND $dias[0]['miercoles']==0 AND $dias[0]['jueves']==0 AND $dias[0]['viernes']==0 AND $dias[0]['sabado']==0 AND $dias[0]['domingo']==0){
                                $active=1;
                                $fecha="0000-00-00";
                            }
                            while($active==0){
                            $nueva_fecha = strtotime($ultima_fecha) + ($j);
                                    switch(date("D",$nueva_fecha)){
                                        case "Mon":
                                            if($dias[0]['lunes'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case "Tue":
                                            if($dias[0]['martes'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case "Wed":
                                            if($dias[0]['miercoles'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case "Thu":
                                            if($dias[0]['jueves'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case "Fri":
                                            if($dias[0]['viernes'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case 'Sat':
                                            if($dias[0]['sabado'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                        case 'Sun':
                                            if($dias[0]['domingo'] == '1'){
                                                $fecha = date("Y-m-d",$nueva_fecha);
                                                $active = 1;
                                            }
                                        break;
                                    }
                                $j+=86400;
                            }
                  $j=86400;
                      $estado_val = "";
                      $estado = explode(',', $capitulo['0']['estado']);
                      $campos_estado="";
                      for ($x=0; $i < count($estado); $x++) { 
                        $estado_base = explode('_', $estado[$x]);
                          if($estado_base[0]=='16'){
                            $fecha = "0000-00-00";
                            break;
                          }
                      }
                  $data=array('id_capitulo'=>$capitulo['0']['id'],'fecha_entrega'=>$fecha);
                  $this->model_post_produccion->update_capitulo_fecha_entrega($data);
                  $cadena = "\n" . " CAPITULO " . $capitulo[0]['numero'] . " CAMBIA FECHA DE ENTREGA A ".$fecha.".";
                  $this->user_log($id_produccion,$cadena);

                  $ultima_fecha = $fecha;
               }   
             }   
        echo json_encode($data);     
    }

    public function tiempos_extras(){
      $id_capitulo=$_POST['id_capitulo'];
      $tiempos=$this->model_post_produccion->tiempos_extras_capitulo($id_capitulo);
      $data['tiempos']=$tiempos;
      echo json_encode($data);     
    }

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


public function cambio_tiempos($id){

  $escenas=$this->model_post_produccion->escenas($id);
    foreach ($escenas as $e) {
      $data=array('id_escena'=>$e['id'],'tiempo_post_minutos'=>$e['tiempo_post_minutos'],
                  'tiempo_post_segundos'=>$e['tiempo_post_segundos']);
      $this->model_post_produccion->update_tiempo_post2($data);
    }
}


public  function calculo_tiempo_post_redondeo($minutos1,$segundos1,$cuadros1){
       $cuadros = 0;
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      $cuadros = $cuadros1;
     
     while($cuadros>=30){
          $segundos+=1;
          $cuadros= $cuadros-30;
      }
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $minutos1 + $minutos;
      
      /*if($cuadros>15){
        $segundos=$segundos+1;
      }*/

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }
      if(strlen($cuadros)<2){
        $cuadros = '0'.$cuadros;
      }
      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      if($cuadros==0){
        $cuadros='00';
      }

      $tiempo = $minutos2.":".$segundos;

      return $tiempo;
    }
      

}

