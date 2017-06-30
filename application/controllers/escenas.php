<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Escenas extends CI_Controller {

    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_plan_produccion');
      $this->load->model('model_post_produccion');
      $this->load->model('model_escenas');
      $this->load->model('model_escenas_2');
      $this->load->model('model_capitulos');
      $this->load->model('model_elementos');
      $this->load->model('model_admin');
      $this->load->model('model_casting');
	    $this->_logeo_in();
	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
    }

  public function index($id, $idcapitulo="", $msg="", $escenas="", $limite1="", $limite2="", $idlocacion="", $idset="", $continuidad=""){


    if(isset($_COOKIE['escena_cookie']) AND $_COOKIE['escena_cookie'][1]==$id AND isset($_COOKIE['escena_cookie'][0]) AND $idcapitulo==""){
      $idcapitulo = $_COOKIE['escena_cookie'][0];
    }else{
      setcookie("escena_cookie[0]", $idcapitulo);
      setcookie("escena_cookie[1]", $id);
    }
    
    $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
      if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
          $id_user=$this->session->userdata('id_pruduction_suite');
          $user=$this->model_admin->rolUserId($id_user);
          $tipo_rol=$user['0']['id_rol_otros'];

          $continuar=0;
          if($user){
              foreach ($user as $u) {
                if($u['id_rol_otros']== 1 or $u['id_rol_otros']== 2 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 8 or $u['id_rol_otros']== 9 or $u['id_rol_otros']== 10 
                  or $u['id_rol_otros']== 11 or $u['id_rol_otros']== 12 or $u['id_rol_otros']== 15){
                  $continuar=1;
                  break;
                }
              }
         }
          if($continuar==1 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
            $produccion = $this->model_plan_produccion->produccion_id($id);

            if(!$idcapitulo){
              $id_capitulo= $this->model_escenas->capitulo_inicial($id);
              $idcapitulo=$id_capitulo['0']->id_capitulo;
            }
            if(!$idcapitulo){
              redirect($this->lang->lang().'/libretos/index/'.$id);
            }

            $capitulos = $this->model_capitulos->capitulos_produccion($id);
            $capitulo = "";
            $locaciones = $this->model_escenas_2->buscar_locaciones($id);

            if($idcapitulo!="" AND $escenas==""){
              $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
              $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo);
            }

            if($idcapitulo!="" AND $escenas!=""){
              $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);

            }

            $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo);
            if($capitulo[0]['escenas_escritas']<=sizeof($producidas) AND $producidas>0 AND $capitulo[0]['escenas_escritas']!=0){
                $data=array(
                    'estado'=>5
                );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                //$cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " PRODUCIDO."; 
            }

            if($idcapitulo!="" AND $escenas==""){
              $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
              $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo);
            }

            if($idcapitulo!="" AND $escenas!=""){
              $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);

            }

            $usuario_permisos = $this->permisos_usuarios($id);
            $estados_color = $this->model_escenas_2->estados_color();
            $post_producidas=$this->model_escenas_2->total_post_produccidas($idcapitulo);
            $data['msg'] = $msg;
            $data['estados_color'] = $estados_color;
            $data['usuario_permisos'] = $usuario_permisos;
            $data['produccion'] = $produccion;
            $data['capitulos'] = $capitulos;
            $data['idcapitulo'] = $idcapitulo;
            $data['escenas'] = $escenas;
            $data['capitulo'] = $capitulo;
            $data['locaciones'] = $locaciones;
            $data['post_producidas']=$post_producidas;
            $data['limite1'] = $limite1;
            $data['limite2'] = $limite2;
            $data['continuidad'] = $continuidad;
            $data['idlocacion'] = $idlocacion;
            $data['idset'] = $idset;
            $data['view']='escenas/index';

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
      $usuario = $this->model_produccion->user_id($iduser);
      if($produccion[0]->id_productor == $iduser){
        $permisos = "write";
      }else{
        $usuario_roles = $this->model_produccion->roles_usuario_produccion($iduser,$produccion[0]->id_produccion);
        if($usuario_roles){
          foreach ($usuario_roles as $usuario_rol) {
            if($usuario_rol['id_rol']==1 or $usuario_rol['id_rol']==2 OR $usuario_rol['id_rol']==8){
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

    public function crear_escenas($id,$idcapitulo='',$msg=''){
      if($this->permisos_usuarios($id)=="write"){
        $produccion = $this->model_plan_produccion->produccion_id($id);
        $capitulos = $this->model_capitulos->capitulos_produccion_2($id);
        if($idcapitulo){
          $numero_escena = $this->model_escenas->numero_escena($idcapitulo);
          $numero_escena=round($numero_escena['0']->numero_escena+1); 
        }else{
          $numero_escena=null;   
        }
        $numero_escena=100;
        $locacion=$this->model_escenas->locacion($id);
        $set=$this->model_escenas->set_id_locacion($locacion[0]['id']);
        $categoria_elemento=$this->model_escenas->categoria_elemento($id);
        $rol_actores_elementos=$this->model_escenas->rol_actores_elementos();
        $elementos=$this->model_escenas->elementos(1,"");
        $tipo_locacion=$this->model_escenas->tipo_locacion();
        $dia_noche=$this->model_escenas->dia_noche();
        $escena_interior_esterior=$this->model_escenas->escena_interior_esterior();
        $escenas_flasback=$this->model_escenas->escenas_flasback();
        $escenas_foto_realizacion=$this->model_escenas->escenas_foto_realizacion();
        $escenas_imagenes_archivo=$this->model_escenas->escenas_imagenes_archivo();
        $producida=$this->model_escenas->escena_producida();
        $magnitud=$this->model_escenas->escena_magnitud();
        $capitulo="";
        if($idcapitulo!=""){
          $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
          $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo);
        }else{
          $ultimo_capitulo = $this->model_capitulos->ultimo_capitulo($id);
          if($ultimo_capitulo){
            $idcapitulo = $ultimo_capitulo[0]->id_capitulo;
            $capitulo = $this->model_capitulos->bucar_capitulo_id($ultimo_capitulo[0]->id_capitulo);
            $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($ultimo_capitulo[0]->id_capitulo);
          }
        }
        $data['msg']=$msg;
        $data['produccion']=$produccion;
        $data['capitulos']=$capitulos;
        $data['capitulo']=$capitulo;
        $data['idcapitulo']=$idcapitulo;
        $data['numero_escena']=$numero_escena;
        $data['locacion']=$locacion;
        $data['set']=$set;
        $data['categoria_elemento']=$categoria_elemento;
        $data['rol_actores_elementos']=$rol_actores_elementos;
        $data['elementos']=$elementos;
        $data['tipo_locacion']=$tipo_locacion;
        $data['dia_noche']=$dia_noche;
        $data['escena_interior_esterior']=$escena_interior_esterior;
        $data['escenas_flasback']=$escenas_flasback;
        $data['escenas_foto_realizacion']=$escenas_foto_realizacion;
        $data['escenas_imagenes_archivo']=$escenas_imagenes_archivo;
        $data['producida']=$producida;
        $data['magnitud']=$magnitud;
        $data['view']='escenas/crear_escenas';
        $this->load->view('includes/template',$data);
      }else{
        redirect ($this->lang->lang().'/escenas/index/'.$id);
      }
    }

    public function set(){
      $id_locacion=$_POST['id_locacion'];
      $set=$this->model_escenas->set_id_locacion($id_locacion);
      $data['set']=$set;
      echo json_encode($data);
    }

    public function set_multiple(){
      $locaciones=$_POST['locaciones'];
      $locaciones=explode(',', $locaciones);
      $sql='';
      $cont=0;
      foreach ($locaciones as $l) {
          if($l){
            if($cont==0){
               $sql.=' id_locacion='.$l;   
            }else{
              $sql.=' or id_locacion='.$l;   
            }
            $cont++;
          }  
      }
      $set=$this->model_escenas->set_id_locaciones($sql);
      $data['set']=$set;
      echo json_encode($data);
    }

    public function crear_locacion(){
      $nombre_locacion=$_POST['nombre_locacion'];
      $nombre_set = $_POST['nombre_set'];
      $idproduccion=$_POST['idproduccion'];
      $this->model_escenas->crear_locacion($nombre_locacion, $idproduccion);
     

      $cadena = "";
      $cadena .= " LOCACIÓN " . strtoupper($nombre_locacion) . " CREADA." ."\n";
      $cadena .= " SET " . strtoupper($nombre_set) . " CREADO." . "\n";
      $this->user_log($idproduccion,$cadena);

     
      $id_locacion=mysql_insert_id();
      $data['id_locacion']=$id_locacion;
      $this->model_escenas->crear_set($nombre_set,$id_locacion);
      $locacion=$this->model_escenas->locacion($idproduccion);
      $data['locacion']=$locacion;
      echo json_encode($data);
    }

    public function nombre_locacion(){
      $nombre_locacion=$_POST['nombre_locacion'];
      $idproduccion=$_POST['idproduccion'];
      $locacion = $this->model_escenas->nombre_locacion($nombre_locacion, $idproduccion);
      if($locacion){
        $data['respuesta']=true;
      }else{
        $data['respuesta']=false;
      }
      echo json_encode($data);
    }


    public function crear_set(){
      $nombre_set = $_POST['nombre_set'];
      $id_locacion = $_POST['id_locacion'];
      $this->model_escenas->crear_set($nombre_set,$id_locacion);
      $set=$this->model_escenas->set_id_locacion($id_locacion);
      $loca_actual=$this->model_escenas->locacion_id($id_locacion);
      $cadena = "";
      $cadena .= " SET " . strtoupper($nombre_set) . " CREAD0." ."\n";
      $this->user_log($loca_actual[0]['id_produccion'],$cadena);

      $id_set=mysql_insert_id();
      $data['id_set']=$id_set;
      $data['set']=$set;
      echo json_encode($data);
    }

    public function nombre_set(){
      $nombre_set=$_POST['nombre_set'];
      $id_locacion=$_POST['id_locacion'];
      $set =$this->model_escenas->nombre_set($nombre_set,$id_locacion);
      if($set){
        $data['respuesta']=true;
      }else{
        $data['respuesta']=false;
      }
      echo json_encode($data);
    }

    public function crear_elemento(){
      $nombre_elemento=$_POST['nombre_elemento'];
      $id_elemento=$_POST['id_elemento'];
      $tipo =$this->model_escenas_2->buscar_elemento($id_elemento);
      $rol=$_POST['rol'];
      $rol_selected="";
      if($tipo[0]->tipo!='Personaje'){
        $rol=null;
        $tipo2="";
      }else{
        $tipo2="( ".$tipo[0]->tipo." )";
        $rol_selected = $this->model_casting->rol_id($rol);
        $rol_selected = $rol_selected[0]->rol;
      }
      $validar=$this->model_elementos->validar_elemento($nombre_elemento,$id_elemento);
      if($validar){
        $data['respuesta']=1;
        echo json_encode($data);
      }else{
        $this->model_escenas->crear_elemento($nombre_elemento,$id_elemento,$rol);
        /*BLOQUE LOG USUARIO*/
        $cadena = "";
        $cadena .= " ELEMENTO ".$nombre_elemento." ".$rol_selected." (" . strtoupper($tipo[0]->tipo) . ") CREAD0." ."\n";
        $this->user_log($tipo[0]->id_produccion,$cadena);

        $elemento= $this->model_elementos->buscar_elemento_id(mysql_insert_id());
        $data['respuesta']=0;
        $data['elemento'] =$elemento[0]; 
        
        $data['tipo']=$tipo2;
        echo json_encode($data);
      }      
    }

    public function buscar_elemento(){
      $elemento_id=$_POST['elemento_id'];
      $idescena = $_POST['idescena'];
      $idproduccion = $_POST['idproduccion'];
      $elements_ids = $_POST['elements_ids'];
      $tipo= $this->model_escenas->categoria_id($elemento_id);
      $sql="";

      if($elements_ids!=""){
        $cadena = explode(',', $elements_ids);
        for ($i=0; $i < count($cadena); $i++) { 
          if($cadena[$i]!=""){
            $sql.= " AND e.id != ".$cadena[$i];
          }
        }
      }

      if($elemento_id==0){
          if($idescena==""){
            $elementos=$this->model_escenas->elementos_all($idproduccion,$sql);
          }else{
            $elementos=$this->model_escenas->elementos_all_escena($idproduccion,$idescena,$sql);
          }
      }else{
        if($tipo[0]->tipo == "Personaje"){
          if($idescena==""){
            $elementos=$this->model_escenas->elementos_rol($elemento_id,$sql);
          }else{
            $elementos=$this->model_escenas->elementos_rol_escena($elemento_id,$idescena,$sql);
          }
        }else{
          if($idescena==""){
            $elementos=$this->model_escenas->elementos($elemento_id,$sql);
          }else{
            $elementos=$this->model_escenas->elementos_escena($elemento_id,$idescena,$sql);
          }
        } 
      }
      $data['elementos']=$elementos;
      echo json_encode($data);
    }

    public function buscar_rol(){
      $idrol=$_POST['idrol'];
      $rol=$this->model_elementos->rol_actores_elementos_id($idrol);
      $data['rol']=$rol[0]->rol;
      echo json_encode($data);
    }

    public function buscar_elemento_palabra(){
      $palabra=$_POST['palabra'];
      $idproduccion=$_POST['idproduccion'];
      $elements_ids = $_POST['elements_ids'];
      $categoria_id = $_POST['categoria_id'];
      $sql="";
      if($elements_ids!=""){
        $cadena = explode(',', $elements_ids);
        for ($i=0; $i < count($cadena); $i++) { 
          if($cadena[$i]!=""){
            $sql.= " AND elemento.id != ".$cadena[$i];
          }
        }
      }
      if($categoria_id!=0){
        $sql.= " AND categoria_elemento.id = ".$categoria_id;
      }
      $elementos=$this->model_escenas->elementos_palabra($palabra, $idproduccion,$sql);

      $data['elementos']=$elementos;
      echo json_encode($data);
    }

    public function _validacion_numero(){
      if($this->input->post('numero_escena')!=""){
        $numero_escena=$this->input->post('numero_escena');
        $n=explode('.', $numero_escena);
        if(isset($n['1']) and strlen($n['1'])<2){
          if($n['1']<10){
            $numero_escena=$n['0'].'.'.$n['1'].'0';
          }
        }
        return $this->model_escenas->buscar_escenas_numero($numero_escena,$this->input->post('cap') );
      }
    }

    public function validacion_numero(){
      $numero_escena = $_POST['numero_escena'];
      $idcapitulo = $_POST['idcapitulo'];
      $existe = $this->model_escenas->buscar_escenas_numero($numero_escena,$idcapitulo);
      $data['existe']=$existe;
      echo json_encode($data);
    }

    public function insert_escenas(){
      
      $this->form_validation->set_rules('numero_escena','Escena','required|callback__validacion_numero');
      $this->form_validation->set_rules('minutos','Minutos','required');
      $this->form_validation->set_rules('segundos','Segundos','required');
      $this->form_validation->set_rules('libreto','Libreto','required');
      $this->form_validation->set_rules('location','locacion','required');
      $this->form_validation->set_rules('set','Set','required');
      $this->form_validation->set_rules('continuidad','Continuidad','required');
      $this->form_validation->set_rules('dia_noche',' dia noche','required');
      $this->form_validation->set_rules('int_ext','Interior exterior','required');
      $this->form_validation->set_rules('flashback','flashback','required');
      $this->form_validation->set_rules('foto_realizacion','foto realizacion','required');
      $this->form_validation->set_rules('escenas_imagenes_archivo','Imagenes de Archivo','required');
      $this->form_validation->set_message('required','%s es requerido');
      $this->form_validation->set_message('_validacion_numero','Ya existe una escena con este número');
      $idcapitulo=$this->input->post('cap');
      setcookie("escena_cookie[0]", $idcapitulo);
      setcookie("escena_cookie[1]", $this->input->post('id_produccion'));
      if ($this->form_validation->run()==FALSE) {
        $this->crear_escenas($this->input->post('id_produccion'), $idcapitulo);
      }else{
        $estado = 12;
        switch ($this->input->post('location_tipo')) {
          case 1:
            $estado = 9;
            break;
          case 2:
            $estado = 7; 
            break;
        }
        $toma_ubicacion = 2;
        if($this->input->post('toma_ubi_si')==1){
          $estado = 5; 
          $toma_ubicacion = 1;
        }

        if($this->input->post('flashback')==1){
          $estado = 11;
        }

        if($this->input->post('producida')==1){
          $estado = 1;
        }
        /*VALIDACION DE MINUTOS Y SEGUNDOS*/
        $minutos = $this->input->post('minutos');
        $segundos = $this->input->post('segundos');


        while($segundos>=60){
          $minutos+=1;
          $segundos = $segundos-60;
        }

        if(strlen($minutos)<2){
          $minutos = '0'.$minutos;
        }
        if(strlen($segundos)<2){
          $segundos = '0'.$segundos;
        }
        
         
        $numero_escena=$this->input->post('numero_escena');
        $n=explode('.', $numero_escena);
        if(isset($n['1']) and strlen($n['1'])<2){
          if($n['1']<10){
            $numero_escena=$n['0'].'.'.$n['1'].'0';
          }
        }

        if($this->input->post('foto_realizacion')==1){
          $foto_realizacion=1; 
        }else{
          $foto_realizacion=2;
        }

        if($this->input->post('escenas_imagenes_archivo')==1){
          $escenas_imagenes_archivo=1; 
        }else{
          $escenas_imagenes_archivo=2;
        }

        
        $datos=array(
            'numero_escena'=>$numero_escena,
            'id_capitulo'=>$this->input->post('cap'),
            'id_locacion'=>$this->input->post('location'),
            'id_set'=>$this->input->post('set'),
            'dias_continuidad'=>$this->input->post('continuidad'),
            'id_tipo_locacion'=>$this->input->post('location_tipo'),
            'id_dia_noche'=>$this->input->post('dia_noche'),
            'id_interior_esterior'=>$this->input->post('int_ext'),
            'id_flasback'=>$this->input->post('flashback'),
            'id_foto_realizacion'=>$foto_realizacion,
            'id_imagenes_archivo'=>$escenas_imagenes_archivo,
            'id_toma_ubicacion'=>$toma_ubicacion,
            'duracion_estimada_minutos'=>$this->input->post('minutos'),
            'duracion_estimada_segundos'=>$this->input->post('segundos'),
            'libreto'=>$this->input->post('libreto'),
            'producida'=>$this->input->post('producida'),
            'descripcion'=>$this->input->post('descripcion'),
            'guion'=>$this->input->post('guion'),
            'estado' => $estado,
            'id_magnitud' => $this->input->post('magnitud'),
            'vehiculo_background' => $this->input->post('vehiculo_background')
         );
         

         $escena=$this->model_escenas->insert_escenas($datos);


          if($escena){
           $id_escena=mysql_insert_id();
            $elemento=$this->input->post('elemento');
            $elemento_extra=$this->input->post('elemento');
            if($elemento){
              foreach ($elemento as $e){
                $this->model_escenas->escenas_has_elementos($id_escena,$e);
                $id_escena_has_elementos = mysql_insert_id();
                $temp = $this->input->post('hidden'.$e);
                  if($temp){
                    $this->model_escenas->escenas_extras($id_escena_has_elementos,$temp);
                  }

                $temp2 = $this->input->post('hidden'.$e.'vehiculo');
                  if($temp2){
                    $this->model_escenas->Vehiculos_background($id_escena_has_elementos,$temp2,1);
                  }
                $temp3 = $this->input->post('hiddenBack'.$e.'vehiculo');
                  if($temp3){
                    $this->model_escenas->Vehiculos_background($id_escena_has_elementos,$temp2,2);
                  }
              }
            }
            $catidad_esc = $this->model_escenas->contar_escenas($idcapitulo);
           
            $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
            $cadena = "";
            $cadena .= " ESCENA " . $capitulo[0]['numero'] . "/" . $this->input->post('numero_escena') . " CREADA.";
            

            if($capitulo[0]['estado'] != 4){
              if($capitulo[0]['estado'] != 3){
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " EN PROGRESO.";
              }
              $this->model_capitulos->capitulo_estado($this->input->post('id_produccion'),$idcapitulo, 3);
            }

            $this->user_log($this->input->post('id_produccion'),$cadena);

            $this->model_capitulos->actualizar_escenas_escritas($idcapitulo);
            $this->actualizar_tiempos($idcapitulo);
          }

          if($this->input->post('producida')==1){
            $this->model_escenas_2->actualizar_producidas($idcapitulo);
            $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo);
            if($capitulo[0]['numero_escenas']<=sizeof($producidas)){
              $this->model_capitulos->capitulo_estado($this->input->post('id_produccion'),$idcapitulo, 5);
            }
          }

          if($this->input->post('validator_field')=="1"){
            redirect ($this->lang->lang().'/escenas/crear_escenas/'.$this->input->post('id_produccion').'/'.$idcapitulo.'/1');
          }else{
            redirect ($this->lang->lang().'/escenas/index/'.$this->input->post('id_produccion').'/'.$idcapitulo);
          }
      }
    }

    public function editar_escena($idescena,$id,$idcapitulo,$limite1="",$limite2="",$idlocacion="",$idset="",$continuidad=""){
      if($this->permisos_usuarios($id)=="write"){
        $escena=$this->model_escenas_2->escena_id($idescena);
        $produccion=$this->model_plan_produccion->produccion_id($id);
        $capitulos=$this->model_escenas->capitulos_idProduccion($id);
        $locacion=$this->model_escenas->locacion($id);
        $set=$this->model_escenas->set_id_locacion($escena[0]->id_locacion);
        $categoria_elemento=$this->model_escenas->categoria_elemento($id);
        $rol_actores_elementos=$this->model_escenas->rol_actores_elementos();
        $elementos=$this->model_escenas->elementos(1,"");
        $elementos_escena = $this->model_escenas_2->buscar_elementos_escena($idescena);
        $tipo_locacion=$this->model_escenas->tipo_locacion();
        $dia_noche=$this->model_escenas->dia_noche();
        $escena_interior_esterior=$this->model_escenas->escena_interior_esterior();
        $escenas_flasback=$this->model_escenas->escenas_flasback();
        $escenas_foto_realizacion=$this->model_escenas->escenas_foto_realizacion();
        $escenas_imagenes_archivo=$this->model_escenas->escenas_imagenes_archivo();
        $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        $producida=$this->model_escenas->escena_producida();
        $magnitud=$this->model_escenas->escena_magnitud();
        $data['magnitud']=$magnitud;
        $data['escena']=$escena;
        $data['idcapitulo']=$idcapitulo;
        $data['elementos_escena']=$elementos_escena;
        $data['produccion']=$produccion;
        $data['capitulos']=$capitulos;
        $data['capitulo']=$capitulo;
        $data['producida']=$producida;
        $data['locacion']=$locacion;
        $data['set']=$set;
        $data['categoria_elemento']=$categoria_elemento;
        $data['rol_actores_elementos']=$rol_actores_elementos;
        $data['elementos']=$elementos;
        $data['tipo_locacion']=$tipo_locacion;
        $data['dia_noche']=$dia_noche;
        $data['escena_interior_esterior']=$escena_interior_esterior;
        $data['escenas_flasback']=$escenas_flasback;
        $data['escenas_foto_realizacion']=$escenas_foto_realizacion;
        $data['escenas_imagenes_archivo']=$escenas_imagenes_archivo;

        // campos ocultos filtro_escenas
        $data['limite1']=$limite1;
        $data['limite2']=$limite2;
        $data['idlocacion']=$idlocacion;
        $data['idset']=$idset;
        $data['dia_continuidad']=$continuidad;

        $data['view']='escenas/editar_escena';
        $this->load->view('includes/template',$data);
      }else{
        redirect ($this->lang->lang().'/escenas/index/'.$id);
      }
    }

    public function actualizar_escena(){
      $idcapitulo = $this->input->post('idcapitulo');
      if($this->input->post('numero_escena_hidden')!=$this->input->post('numero_escena')){
        $this->form_validation->set_rules('numero_escena','Escena','required|callback__validacion_numero');
      }
      $this->form_validation->set_rules('libreto','Libreto','required');
      $this->form_validation->set_rules('location','locacion','required');
      $this->form_validation->set_rules('set','Set','required');
      $this->form_validation->set_rules('continuidad','Continuidad','required');
      $this->form_validation->set_rules('dia_noche',' dia noche','required');
      $this->form_validation->set_rules('int_ext','Interior exterior','required');
      $this->form_validation->set_rules('escena_producida','escena_producida');
      
      $this->form_validation->set_message('required','%s es requerido');
      $this->form_validation->set_message('_validacion_numero','Ya exite una escena con este número');
      if ($this->form_validation->run()==FALSE) {
        $this->editar_escena($this->input->post('id_escena'),$this->input->post('id_produccion'),$this->input->post('id_capitulo'));
      }else{
        $toma_ubicacion = $this->input->post('toma_ubi_si');
        if($this->input->post('estado_escena')%2!=0 AND $this->input->post('estado_escena')!=2){
          $estado = 12;
          switch ($this->input->post('location_tipo')) {
            case 1:
              $estado = 9;
              break;
            case 2:
              $estado = 7; 
              break;
            case 3:
              $estado = 5; 
              break;
          }

        $toma_ubicacion = 2;
        if($this->input->post('toma_ubi_si')==1){
          $estado = 5; 
          $toma_ubicacion = 1;
        }
        if($this->input->post('flashback')==1){
          $estado = 11;
        }

        if($this->input->post('producida')==1){
          $estado = 1;
        }
        $flashback = $this->input->post('flashback');
        if($flashback==2){
          $flashback= 2;
        }
        }else{
          $escena = $this->model_escenas_2->escena_id($this->input->post('id_escena'));
          $estado = $this->input->post('estado_escena');
                if($escena[0]->id_tipo_locacion!=$this->input->post('location_tipo')){
                  switch ($this->input->post('location_tipo')) {
                  case 1:
                    $estado = 8;
                    break;
                  case 2:
                    $estado = 6; 
                    break;
                  }
                }

                  if($escena[0]->id_toma_ubicacion!=$this->input->post('toma_ubi_si')){
                    if($this->input->post('toma_ubi_si')==1){
                      $estado = 6; 
                    }
                  }
                  
                  if($escena[0]->id_flasback!=$this->input->post('flashback')){
                    if($this->input->post('flashback')==1){
                      $estado = 10;
                    }
                  }

                  if($this->input->post('producida')!=$escena[0]->id_producida){
                    if($this->input->post('producida')==1){
                      $estado = 1;
                    }
                  }

                  $flashback = $this->input->post('flashback');
                  if($flashback==2){
                    $flashback= 2;
                  }

        }


        /*VALIDACION DE MINUTOS Y SEGUNDOS*/
        $minutos = $this->input->post('minutos');
        $segundos = $this->input->post('segundos');


        while($segundos>=60){
          $minutos+=1;
          $segundos = $segundos-60;
        }

        if(strlen($minutos)<2){
          $minutos = '0'.$minutos;
        }
        if(strlen($segundos)<2){
          $segundos = '0'.$segundos;
        }  

        $numero_escena=$this->input->post('numero_escena');
        $n=explode('.', $numero_escena);
        if(isset($n['1']) and strlen($n['1'])<2){
          if($n['1']<10){
            $numero_escena=$n['0'].'.'.$n['1'].'0';
          }
        }

        if($this->input->post('foto_realizacion')==1){
          $foto_realizacion=1; 
        }else{
          $foto_realizacion=2;
        }

        if($this->input->post('escenas_imagenes_archivo')==1){
          $escenas_imagenes_archivo=1; 
        }else{
          $escenas_imagenes_archivo=2;
        }
        
        $escena_producida=$this->input->post('escena_producida');
        if($escena_producida==1){
          $estado_escena_producida=$this->input->post('estado_escena_producida');
          $datos=array(
            'numero_escena'=>$numero_escena,
            'id_capitulo'=>$this->input->post('cap'),
            'id_locacion'=>$this->input->post('location'),
            'id_set'=>$this->input->post('set'),
            'dias_continuidad'=>$this->input->post('continuidad'),
            'id_tipo_locacion'=>$this->input->post('location_tipo'),
            'id_dia_noche'=>$this->input->post('dia_noche'),
            'id_interior_esterior'=>$this->input->post('int_ext'),
            'id_flasback'=>$this->input->post('flashback'),
            'id_foto_realizacion'=>$foto_realizacion,
            'id_imagenes_archivo'=>$escenas_imagenes_archivo,
            'id_toma_ubicacion'=>$toma_ubicacion,
            'duracion_estimada_minutos'=>$minutos,
            'duracion_estimada_segundos'=>$segundos,
            'libreto'=>$this->input->post('libreto'),
            'producida'=>$this->input->post('producida'),
            'descripcion'=>$this->input->post('descripcion'),
            'guion'=>$this->input->post('guion'),
            'id_magnitud'=>$this->input->post('magnitud'),
            'vehiculo_background'=>$this->input->post('vehiculo_background'),
            'estado' => $estado_escena_producida
         );

        }else{
           $datos=array(
            'numero_escena'=>$numero_escena,
            'id_capitulo'=>$this->input->post('cap'),
            'id_locacion'=>$this->input->post('location'),
            'id_set'=>$this->input->post('set'),
            'dias_continuidad'=>$this->input->post('continuidad'),
            'id_tipo_locacion'=>$this->input->post('location_tipo'),
            'id_dia_noche'=>$this->input->post('dia_noche'),
            'id_interior_esterior'=>$this->input->post('int_ext'),
            'id_flasback'=>$this->input->post('flashback'),
            'id_foto_realizacion'=>$foto_realizacion,
            'id_imagenes_archivo'=>$escenas_imagenes_archivo,
            'id_toma_ubicacion'=>$toma_ubicacion,
            'duracion_estimada_minutos'=>$minutos,
            'duracion_estimada_segundos'=>$segundos,
            'libreto'=>$this->input->post('libreto'),
            'producida'=>$this->input->post('producida'),
            'descripcion'=>$this->input->post('descripcion'),
            'guion'=>$this->input->post('guion'),
            'id_magnitud'=>$this->input->post('magnitud'),
            'vehiculo_background'=>$this->input->post('vehiculo_background'),
            'estado' => $estado
         );
        }
         
         $id_escena = $this->input->post('id_escena');
         $escena = $this->model_escenas->actualizar_escenas($datos,$id_escena);
            $elemento = $this->input->post('elemento');
            $this->model_escenas->eliminar_escenas_has_elementos($id_escena);
              if($elemento){
                foreach ($elemento as $e) {
                  $this->model_escenas->escenas_has_elementos($id_escena,$e);
                  $id_escena_has_elementos = mysql_insert_id();
                  $temp = $this->input->post('hidden'.$e);
                  if($temp){
                    $this->model_escenas->escenas_extras($id_escena_has_elementos,$temp);
                  }

                  $temp2 = $this->input->post('hidden'.$e.'vehiculo');
                  if($temp2){
                    $this->model_escenas->Vehiculos_background($id_escena_has_elementos,$temp2,1);
                  }
                  $temp3 = $this->input->post('hiddenBack'.$e.'vehiculo');
                  if($temp3){
                    $this->model_escenas->Vehiculos_background($id_escena_has_elementos,$temp3,2);
                  }
                }
              }
          $idcapitulo = $this->input->post('idcapitulo');
          $this->actualizar_tiempos($idcapitulo);
          $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
          if($estado == 1){
            $this->model_escenas_2->actualizar_producidas($idcapitulo);
            $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo);
            if($capitulo[0]['numero_escenas']<=sizeof($producidas)){
              $this->model_capitulos->capitulo_estado($this->input->post('id_produccion'),$idcapitulo, 5);
            }
          }
          $escena = $this->model_escenas_2->escena_id($id_escena);
          $cadena = "\n" . " ESCENA " . $capitulo[0]['numero'] . '/'. $escena[0]->numero_escena . " EDITADA.";
          $this->user_log($this->input->post('id_produccion'),$cadena);

          $limite1=$this->input->post('limite1'); 
          $limite2=$this->input->post('limite2');; 
          $idlocacion=$this->input->post('idlocacion');
          $idset=$this->input->post('idset');
          $dia_continuidad=$this->input->post('dia_continuidad');
          if($limite1!="" OR $limite2!="" OR $idlocacion!="" OR $idset!="" OR $dia_continuidad!=""){
            redirect ($this->lang->lang().'/escenas/filtro_escenas/'.$idcapitulo.'/'.$this->input->post('id_produccion').'/'.$limite1.'/'.$limite2.'/'.$idlocacion.'/'.$idset.'/'.$dia_continuidad);
          }else{
            redirect ($this->lang->lang().'/escenas/index/'.$this->input->post('id_produccion').'/'.$idcapitulo);
          }
      }
    }

    public function actualizar_tiempos($idcapitulo){
      $timepo="";
      $escenas = $this->model_escenas_2->suma_tiempos($idcapitulo);
      $tiempo = $this->calculo_tiempo($escenas);

      $data=array(
          'duracion_estimada'=>$tiempo,
      );
      $this->db->where('id',$idcapitulo);
      $this->db->update('produccion_has_capitulos',$data);

      
      $escenas = $this->model_escenas_2->suma_tiempos_producidas($idcapitulo);
      $tiempo = $this->calculo_tiempo($escenas);

      $data=array(
          'duracion_real'=>$tiempo,
      );
      $this->db->where('id',$idcapitulo);
      $this->db->update('produccion_has_capitulos',$data);
    }

    public function calculo_tiempo($escenas){
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $escenas[0]->segundos;

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

    public function buscar_escenas($idproduccion="", $idcapitulo="", $msg=""){
      if($this->input->post('cap')!=""){
        $idcapitulo = $this->input->post('cap');
        $idproduccion = $this->input->post('idproduccion');
      }
      $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
      if($idcapitulo!=""){
        $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo);
        $this->index($idproduccion, $idcapitulo, $msg);
      }else{
        $this->index($idproduccion, '', $msg);
      }
    }

    public function filtro_escenas($idcapitulo="",$idproduccion="",$limite1="",$limite2="",$locacion="",$set="",$continuidad=""){
      if($idcapitulo==""){
        $idcapitulo = $this->input->post('idcapitulo');
      }

      if($idproduccion==""){
        $idproduccion = $this->input->post('idproduccion');
      }

      if($limite1=="" or $limite1=='null'){
        $limite1 =$this->input->post('limite1');
      }

      if($limite2=="" or $limite2=='null'){
        $limite2 =$this->input->post('limite2');
      }

      if($locacion=="" or $locacion=='null'){
        $locacion =$this->input->post('locacion');
      }

      if($set=="" or $set=='null'){
        $set =$this->input->post('set');
      }

      if($continuidad=="" or $continuidad=='null'){
        $continuidad =$this->input->post('continuidad');
      }

      $msg='';
      $sql = "";
          if($locacion!=""){
            $sql.=" AND escena.id_locacion = ".$locacion;
          }
          if($set!=""){
            $sql.=" AND sets.id = ".$set;
          }
          if($limite1!="" AND $limite2!=""){
            $sql.=" AND numero_escena BETWEEN ".$limite1." AND ".$limite2;
          }
          if($limite1=="" AND $limite2!=""){
            $sql.=" AND numero_escena <= ".$limite2;
          }
          if($limite1!="" AND $limite2==""){
            $sql.=" AND numero_escena >= ".$limite1;
          }
          if($continuidad!=""){
            $sql.= " AND dias_continuidad = ".$continuidad;
          }

      $escenas = $this->model_escenas_2->filtar_escenas($idcapitulo,$sql);   
      if($escenas=="" OR !$escenas){
        $msg="1";
      }else{
         if(empty($limite1) and empty($limite2) and empty($locacion) and empty($set) and empty($continuidad)){
           $msg="0";  
         }else{
           $msg="2"; 

         }
        
      }

     $this->index($idproduccion, $idcapitulo, $msg, $escenas,$limite1, $limite2, $locacion, $set, $continuidad);
    }

    public function acciones_escenas(){
      $idescena = $_POST['idescena'];
      $idcapitulo = $_POST['idcapitulo'];
      $idproduccion = $_POST['idproduccion'];
      $opcion = $_POST['opcion'];
      $numero_escena = $_POST['numero_escena'];
      switch($opcion){
        case "cancelar":
          $this->cancelar_escena($idescena, $idproduccion, $idcapitulo);
        break;
        case "eliminar":
          $this->eliminar_escena($idescena, $idproduccion, $idcapitulo);
        break;
        case "duplicar":
          $this->duplicar_escena($idescena, $idproduccion, $idcapitulo, $numero_escena);
        break;
      }
      $data=1;
      echo json_encode($data);
    }

    public function cancelar_escena($idescena, $idproduccion, $idcapitulo){
      $resultado = $this->model_escenas_2->cancelar_escena($idescena);
      $escena = $this->model_escenas_2->escena_id($idescena);
      $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
      $cadena = "\n" . " ESCENA " . $capitulo[0]['numero'] . '/'. $escena[0]->numero_escena . " CANCELADA.";
      $this->user_log($idproduccion,$cadena);
    }

    public function eliminar_escena($idescena, $idproduccion, $idcapitulo){
      $escena = $this->model_escenas_2->escena_by_id($idescena);
      $cadena = ""; 
      if($escena[0]->estado != 1){
        $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        $cadena .= "\n" . " ESCENA " . $capitulo[0]['numero'] . '/'. $escena[0]->numero_escena . " ELIMINADA.";
        $resultado = $this->model_escenas_2->eliminar_escena($idescena);

        $this->actualizar_tiempos($idcapitulo);
        $this->model_capitulos->actualizar_escenas_escritas($idcapitulo);
        $msg = "1";
        $catidad_esc = $this->model_escenas->contar_escenas($idcapitulo);
        
        if($catidad_esc[0]->cantidad==0 AND $capitulo[0]['numero_escenas']!=""){
          if($capitulo[0]['estado']!=2){
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA ESTADO ENTREGADO.";
          }
          $this->model_capitulos->capitulo_estado($idproduccion,$idcapitulo, 2);
        }else if($capitulo[0]['numero_escenas'] > $catidad_esc[0]->cantidad AND $capitulo[0]['numero_escenas']>0){
          if($capitulo[0]['estado']!=3){
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA ESTADO EN PROGRESO.";
          }
          $this->model_capitulos->capitulo_estado($idproduccion,$idcapitulo, 3);
        }
      }
      $this->user_log($idproduccion,$cadena);
      $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($escena[0]->id_capitulo);
    } 

    public function duplicar_escena($idescena, $idproduccion, $idcapitulo, $numero_escena){ 
      $escena = $this->model_escenas_2->escena_by_id($idescena);
      $estado = 12;
      if($escena[0]->estado == 1 OR $escena[0]->estado==2){
        
        switch ($escena[0]->id_tipo_locacion) {
          case 1:
            $estado = 9;
            break;
          case 2:
            $estado = 7; 
            break;
          case 3:
            $estado = 5; 
            break;
        }

        if($escena[0]->id_tipo_locacion==1){
          $estado = 5; 
        }

        if($escena[0]->id_tipo_locacion==2){
          $estado = 7; 
        }

        if($escena[0]->id_flasback==1){
          $estado = 11;
        }

        if($escena[0]->id_producida==1){
          $estado = 1;
        }

      }else if($escena[0]->estado%2==0){
        $estado = ((int)$escena[0]->estado)+1;
      }else{
        $estado = $escena[0]->estado;
      }


      $this->model_escenas_2->duplicar_escena($idescena,$idcapitulo,$numero_escena,$estado);

      $capitulo_temp = $this->model_capitulos->bucar_capitulo_id($escena[0]->id_capitulo);
      $cadena="";
      
      $idescena_duplicada = mysql_insert_id();
      $escena_d = $this->model_escenas_2->escena_by_id($idescena_duplicada);
      
      $this->actualizar_tiempos($idcapitulo);
    
      $elementos = $this->model_escenas_2->buscar_elementos_escena($idescena);
      if($elementos){
        foreach ($elementos as $elemento) {
          $this->model_escenas->escenas_has_elementos($idescena_duplicada, $elemento->id_elemento);
          $id_escena_has_elementos = mysql_insert_id();
          $esc=$this->model_elementos->escenas_has_elementos_id_escena_id_elemento($elemento->id_elemento,$idescena);
          if($esc){
             $f=$this->model_elementos->cantidad_extra_escena($esc['0']->id);
             if($f){
               $this->model_escenas->escenas_extras($id_escena_has_elementos,$f['0']->cantidad); 
              }
          }
        }
      }
        $this->model_capitulos->actualizar_escenas_escritas($idcapitulo);
        $msg = "1";
        $catidad_esc = $this->model_escenas->contar_escenas($idcapitulo);
        //$this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,'numero_escenas',$catidad_esc[0]->cantidad);

        $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        $cadena .= "\n" . " ESCENA " . $capitulo_temp[0]['numero'] . '/'. $escena[0]->numero_escena . " DUPLICADA A LIBRETO " . $capitulo[0]['numero']."/".$escena_d[0]->numero_escena.".";
        if($catidad_esc[0]->cantidad==0 OR $catidad_esc[0]->cantidad==""){
          if($capitulo[0]['estado']!=1){
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " PRODUCIDO."; 
          }
          $this->model_capitulos->capitulo_estado($idproduccion,$idcapitulo, 1);
        }
        
        if($capitulo[0]['estado']==2){
          /*if($capitulo[0]['estado']!=4){
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " DESGLOSADO."; 
          }*/
          $this->model_capitulos->capitulo_estado($idproduccion,$idcapitulo, 3);
          $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        }

        /*if($capitulo[0]['numero_escenas'] == count($this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo))){
          if($capitulo[0]['estado']!=4){
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero']." DESGLOSADO."; 
          }
          $data=array(
              'estado'=>4,
              'fecha_desglosado' => date("Y-m-d")
          );
          $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
        } */
        $this->user_log($idproduccion,$cadena);

    }

    public function numero_escena(){
      $id_capitulo=$_POST['id_capitulo'];
      $numero_escena = $this->model_escenas->numero_escena($id_capitulo);
      $numero_escena=round($numero_escena['0']->numero_escena+1); 
      $data['numero_escena']=$numero_escena;
      echo json_encode($data);
    }

    public function suma_tiempos_escenas(){
      $sql = "";
      $idescenas=$_POST['idescenas'];
      $ids = explode(',', $idescenas);
      for ($i=0; $i < sizeof($ids); $i++) {
        if($ids[$i]!=""){ 
          if($i==0){
            $sql .= " WHERE id = ".$ids[$i]; 
          }else{
            $sql .= " OR id = ".$ids[$i];
          }
        }
      }
      $escenas = $this->model_escenas_2->suma_tiempos_ajax($sql);
      $data['tiempo']=$this->calculo_tiempo($escenas);
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

    public function asignar_extras_aux(){
      $extras = $this->model_escenas->asignar_extras_aux();
      if($extras){
        foreach ($extras as $extra) {
          $this->model_escenas->escenas_extras($extra->id,1);
        }
      }
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
      
      if($cuadros>15){
        $segundos=$segundos+1;
      }

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