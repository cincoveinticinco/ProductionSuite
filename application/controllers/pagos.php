<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagos extends CI_Controller {

    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_produccion');
      $this->load->model('model_escenas');
      $this->load->model('model_pagos');
      $this->load->model('model_escenas_2');
      $this->load->model('model_admin');
	    $this->_logeo_in();
	}
   function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
    }
     public function index(){
        ////////////////////////////////
        $iduser = $this->session->userdata('id_pruduction_suite');
       
        $data['view']='pagos/index';
        $this->load->view('includes/template_pago',$data);   
    }
    public function buscar_cuenta($filtro){
        ////////////////////////////////

        $produccion=$this->model_produccion->producciones_all();
        $data['filtro']=$filtro;
        $data['produccion']=$produccion;
        $data['view']='pagos/filtro_pago';
        $this->load->view('includes/template_pago',$data);   
    }
     public function tipo_personaje(){
        $rol_actores_elementos=$this->model_escenas->rol_actores_elementos();
        $data['rol_actor']=$rol_actores_elementos;
        echo json_encode($data);
    }
    public function personaje_produccion(){
        $id_prod=$_POST['idprod'];
        $id_tipo=$_POST['idtipo'];
        $palabra="Personaje";
        $sql="AND elemento.rol=".$id_tipo;
        $personaje_produccion=$this->model_escenas->elementos_palabra($palabra,$id_prod,$sql);
        $data['personaje_produccion']=$personaje_produccion;
        echo json_encode($data);
    }
    public function serch_element_doc(){
       $documento=$_POST['documento'];
       $sql="AND F.documento LIKE '%".$documento."%'";
        $elementos=$this->model_pagos->elementos_persona($sql);
        $data['elementos']=$elementos;
        echo json_encode($data);
    }
     public function cuenta_cobro($id_elemento,$page){
        //$id_elemento=$this->input->post('perso');
        $sql="AND B.id_elemento=".$id_elemento;
        $det_actor=$this->model_pagos->elementos_persona($sql);
        if($det_actor){
        $det_cap=$this->model_pagos->libretos_elementos($id_elemento);
        $sql="WHERE eh.id_elemento =".$id_elemento;
        $cuenta_cobro=$this->model_pagos->group_capitulos($sql);

        $data['solicitud']=$this->model_pagos->solicitud_elemento($id_elemento);
        $data['cuenta_cobro']=$cuenta_cobro;
        $data['id_elemento']=$id_elemento;
        $data['det_cap']=$det_cap;
        $data['det_actor']=$det_actor;
        $data['view']='pagos/cuenta_cobro';
        $data['page']=$page;
        $this->load->view('includes/template_pago',$data);
      }
        else{
          redirect ($this->lang->lang().'/pagos/buscar_cuenta/2');
        }
    }
    public function cuenta_cobro_select(){
      $id_elemento=$_POST['id_elemento'];
      $sql="AND B.id_elemento=".$id_elemento;
      $det_cap=$this->model_pagos->libretos_elementos($id_elemento);
      $data['det_cap']=$det_cap;
      echo json_encode($data);
    }
   public function insert_cuenta(){
    if(!isset($_POST['id_actor'])){
      $id_actor="";
    }
    else{
      $id_actor=$_POST['id_actor'];
    }
         $data=array(
          'id_elemento'=>$_POST['id_elemento'],
          'id_actor'=>$id_actor,
          'valor'=>$_POST['valor'],
          'fecha_generado'=>$_POST['fecha'],
          'estado'=>3 );
        $this->model_pagos->insertar_cuenta_cobro($data);
        $last_id = $this->db->insert_id();
        $data['id_cuenta']=$last_id ;
        echo json_encode($data);
    }
    public function insert_capitulo_cuenta(){
      $data=array(
          'id_cuenta_cobro'=>$_POST['id_cuenta'],
          'id_capitulo'=>$_POST['id_cap']
          );
      $this->model_pagos->insertar_cuenta_has_cap($data);
    }

  public function update_cuenta(){
        $data=array(
            'id_cuentas_cobro'=>$_POST['id_cuenta'],
            'estado'=>$_POST['opcion']
            );
        $this->model_pagos->update_cuenta_cobro($data); 
        $data['success']=1;
        echo json_encode($data);
      }
      public function lista_cuentas(){
        $cuentas_cobro=$this->model_pagos->group_capitulos();
        $estados_cuenta=$this->model_pagos->select_estados_cuenta();
        $produccion=$this->model_produccion->producciones_all();
        $data['estados_cuenta']=$estados_cuenta;
        $data['cuentas_cobro']=$cuentas_cobro;
        $data['produccion']=$produccion;
        $data['view']='pagos/lista_cuentas';
        $this->load->view('includes/template_pago',$data);
      }
      public function filtro_cuentas(){
        $fecha_ini=$_POST['date_inicio'];
        $fecha_fin=$_POST['date_fin'];
        $estado=$_POST['estado'];
        $id_produccion=$_POST['id_produccion'];
        $id_tipo_p=$_POST['id_tipo_p'];
        $id_personaje=$_POST['id_personaje'];
        $documento=$_POST['documento'];
        $nombre_actor=$_POST['nombre'];
        $apellido_actor=$_POST['apellido_actor'];
        $valor_desde=$_POST['valor_desde'];
        $valor_hasta=$_POST['valor_hasta'];
        $sql="WHERE 1=1 ";
        if($fecha_ini!=''){
          if($fecha_fin!=''){
            $sql.="AND fecha_generado >= '".$fecha_ini."' AND fecha_generado <= '".$fecha_fin."' ";
          }else{
             $sql.="AND fecha_generado >= '".$fecha_ini."' ";
          }
        }elseif($fecha_fin!=''){
            $sql.="AND fecha_generado <= '".$fecha_fin."' ";
        }   
        
        if($estado!=""){
           $sql.="AND ec.id_estado=".$estado." ";
        }
        if($id_produccion!=""){
          
          if($id_tipo_p!=""){
            $sql.="AND pr.id=".$id_produccion." AND rol.id=".$id_tipo_p." ";
          }
          else{
            $sql.="AND pr.id=".$id_produccion." ";
          }
        }
        if($id_tipo_p!=""){
             $sql.="AND rol.id=".$id_tipo_p." ";
        }
        if($id_personaje!="" && $id_tipo_p!="" && $id_produccion!=""){
             $sql.="AND el.id=".$id_personaje." ";
        }
        if($documento!=""){
             $sql.="AND ac.documento LIKE '%".$documento."%' ";
        }
        if($nombre_actor!=""){
          $sql.="AND ac.nombre LIKE '%".$nombre_actor."%' ";
        }
        if($apellido_actor!=""){
          $sql.="AND ac.apellido LIKE '%".$apellido_actor."%' ";
        }

        if($valor_desde!=""){
          if($valor_hasta!=""){
            $sql.="AND cc.valor >= '".$valor_desde."' AND cc.valor <= '".$valor_hasta."' ";
          }else{
             $sql.="AND cc.valor >= '".$valor_desde."' ";
          }
        }elseif($valor_hasta!=""){
            $sql.="AND cc.valor <= '".$valor_hasta."' ";
        }



        $cuentas_cobro=$this->model_pagos->group_capitulos($sql);
        $consulta=$this->db->last_query();
        $data['cuentas_cobro']=$cuentas_cobro;
        $data['consulta']=$consulta;
        echo json_encode($data);
      }
      public function escenas_capitulo(){
        $id_capitulo=$_POST['id_capitulo'];
        $des_escenas_cap=$this->model_escenas_2->buscar_escenas_id_capitulo($id_capitulo);
        $data['des_escenas_cap']=$des_escenas_cap;
        echo json_encode($data);
      }
      public function fechas_capitulo(){
        $id_capitulo=$_POST['id_capitulo'];
        if($id_capitulo){
        $fecha_capitulo=$this->model_pagos->fechas_capitulos($id_capitulo);}
        else{
          $fecha_capitulo="";
        }
        $data['fecha_capitulo']=$fecha_capitulo;
        echo json_encode($data);
      }

      public function fechas_produccion_capitulo(){
        $id_capitulo=$_POST['id_capitulo'];
        $meses_capitulo=$this->model_pagos->meses_capitulo_produccion($id_capitulo);
         $data['meses_capitulo']=$meses_capitulo;
        echo json_encode($data);
      }
  }
