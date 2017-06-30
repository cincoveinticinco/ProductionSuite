<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_user extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_admin');
	    $this->_logeo_in();

	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
      $tipo_usuario = $this->session->userdata('tipo_pruduction_suite'); 
      if($tipo_usuario!=1 and $tipo_usuario!=5){ 
         redirect($this->lang->lang().'/produccion/producciones');
      }
      if($tipo_usuario==5){
      	   $id_user=$this->session->userdata('id_pruduction_suite');
      	   $user=$this->model_admin->rolUserId($id_user);
           $tecnico=$user['0']['id_rol_otros'];	
           if($tecnico!=5){
              redirect($this->lang->lang().'/produccion/producciones');
           }
      	}
    }

    public function _verificarcorreo($correo){
	    return $this->model_admin->verificacion_correo($correo);
    }
	
	public function index($msg='',$produccion='',$estado=''){
	  $id = $this->session->userdata('id_pruduction_suite');
	  if(!$produccion and !$estado){
	   $where='and estado=1';
	   $estado=1;
       $user=$this->model_admin->list_user($id,$where);
	  }else{
	    $idproduccion = $produccion;
    	$id_estado = $estado;
    	if($id_estado==3){
             if($idproduccion!="0"){
				$user = $this->model_produccion->buscar_usuarios_produccion($idproduccion,$id);
			}else{
				$user=$this->model_admin->list_user($id);
			}
    	}else{
			if($idproduccion!="0"){
				$user = $this->model_produccion->buscar_usuarios_produccion_estado($idproduccion,$id,$id_estado);
			}else{
				$user=$this->model_admin->list_user_estado($id,$id_estado);
			}	
    	}
	  }
	  
      
      $producciones =$this->model_produccion->producciones_all("");
      $data['producciones']=$producciones;
      $data['id_produccion']=$produccion;
      $data['estado']=$estado;
      $data['user']=$user;
      $data['msg']=$msg;
      $data['view']='user/list_user';
      $this->load->view('includes/template',$data);
    }

    public function insert_user(){
    		$this->form_validation->set_rules('nombre','nombre','required');
			$this->form_validation->set_rules('apellido','apellido','required');
			$this->form_validation->set_rules('tipo_user','Tipo Usuario','required');
			$this->form_validation->set_rules('idioma','Idioma','required');
			$this->form_validation->set_rules('password','password');
			$this->form_validation->set_rules('correo','correo','required|valid_email|callback__verificarcorreo');
			$this->form_validation->set_message('required','%s es requerido');
			$this->form_validation->set_message('valid_email','Correo no valido');
			$this->form_validation->set_message('_verificarcorreo','Este Correo ya se encuentra registrado');
			if ($this->form_validation->run()==FALSE) {
				$this->registro();
			}else{
				$datos=array(
	             'nombre' => $this->input->post('nombre'),
	             'apellido' => $this->input->post('apellido'),
	             'correo' => $this->input->post('correo'),
	             'idioma' => $this->input->post('idioma'),
	             'tipo_user' => $this->input->post('tipo_user'),
	             'password' => md5($this->input->post('password')),
			);
			 $user=$this->model_admin->insert_user($datos);
			 if($user){
			 	if($this->input->post('tipo_user')==5){
			 		$id_user=mysql_insert_id();
			 		$rol= $this->input->post('rol');
			   		foreach ($rol as $r) {
				 		$user_rol=$this->model_admin->insert_user_rol($id_user,$r);
			    	}
			 	}
			    $msg='<div class="alert-box success">Registro de usuario con éxito.<a href="" class="close">&times;</a></div>';
			    $this->index($msg);
			 }

             
			}
    }

    public function registro($msg=''){
    	 $tipo_user=$this->model_admin->tipo_usuario();
			 $rol = $this->model_admin->rol();
		     $data['tipo_user']=$tipo_user;
		     $data['rol']=$rol; 
		     $data['msg']=$msg; 
		     $data['view']='user/registro_user';   
			 $this->load->view('includes/template',$data);
    }

    public function editar_user($id,$msg=''){
        $user=$this->model_admin->user_id($id);
        $tipo_user=$this->model_admin->tipo_usuario();
        $rol = $this->model_admin->rol();
        $data['msg']='';
        $data['rol']=$rol;
        $data['tipo_user']=$tipo_user;
        $data['user']=$user;
      	$data['view']='user/editar_user';
      	$this->load->view('includes/template',$data);
        
    }
    public function eliminarUser($id_user){
    	$this->model_admin->delete_user_produccion($id_user);
    	$this->model_admin->delete_user($id_user);
    	redirect($this->lang->lang().'/admin_user');
    }

    public function update_user(){
            $this->form_validation->set_rules('nombre','nombre','required');
			$this->form_validation->set_rules('apellido','apellido','required');
			$this->form_validation->set_rules('tipo_user','Tipo Usuario','required');
			$this->form_validation->set_rules('idioma','Idioma','required');
			$this->form_validation->set_rules('password','password');
			//$this->form_validation->set_rules('correo','correo','required|valid_email|callback__verificarcorreo');
			$this->form_validation->set_message('required','%s es requerido');
			$this->form_validation->set_message('valid_email','Correo no valido');
			$this->form_validation->set_message('_verificarcorreo','Este Correo ya se encuentra registrado');
			if ($this->form_validation->run()==FALSE) {
				$this->registro();
			}else{
				if($this->input->post('password')){
					 $datos=array(
		             'id' => $this->input->post('id'),
		             'nombre' => $this->input->post('nombre'),
		             'apellido' => $this->input->post('apellido'),
		             'correo' => $this->input->post('correo'),
		             'idioma' => $this->input->post('idioma'),
		             'id_tipoUsuario' => $this->input->post('tipo_user'),
		             'password' => md5($this->input->post('password')),
					);
				}else{
					$datos=array(
		             'id' => $this->input->post('id'),
		             'nombre' => $this->input->post('nombre'),
		             'apellido' => $this->input->post('apellido'),
		             'correo' => $this->input->post('correo'),
		             'idioma' => $this->input->post('idioma'),
		             'id_tipoUsuario' => $this->input->post('tipo_user'),
					);
				}
				
			 $user=$this->model_admin->update_user($datos);
			 if($user){
			 	$id_user=$this->input->post('id');
			 	$this->model_admin->delete_rol($id_user);
			 	if($this->input->post('tipo_user')==5){
			 		$rol= $this->input->post('rol');
			   		foreach ($rol as $r) {
				 		$user_rol=$this->model_admin->insert_user_rol($id_user,$r);
			    	}
			 	}
			    $msg='<div class="alert-box success">Se han actualizado los datos del usuario con éxito.<a href="" class="close">&times;</a></div>';
			    $this->index($msg);
			 }

             
			}

    }

    public function cambiar_estado_user($id,$estado,$produccion,$estado_selec){
         $actualzar=$this->model_admin->cambiarEstadoUser($id,$estado);
         if($actualzar){
           $msg='<div class="alert-box success">Se ha actualizado el estado del usuario con éxito.<a href="" class="close">&times;</a></div>';
		   $this->index($msg,$produccion,$estado_selec);	
         }

    }

    public function buscar_usuarios_produccion(){
    	$id = $this->session->userdata('id_pruduction_suite');
    	$idproduccion = $_POST['idproduccion'];
    	$id_estado = $_POST['estado_user'];
    	if($id_estado==3){
             if($idproduccion!="Todos"){
				$usuarios = $this->model_produccion->buscar_usuarios_produccion($idproduccion,$id);
			}else{
				$usuarios=$this->model_admin->list_user($id);
			}
    	}else{
			if($idproduccion!="Todos"){
				$usuarios = $this->model_produccion->buscar_usuarios_produccion_estado($idproduccion,$id,$id_estado);
			}else{
				$usuarios=$this->model_admin->list_user_estado($id,$id_estado);
			}	
    	}
    	
		$cadena_tabla='<thead>
			<tr>
				<th width="12%">'.lang('global.nombre').'</th>
				<th width="12%">'.lang('global.apellido').'</th>
				<th width="24%">'.lang('global.correo').'</th>
				<th width="10%">'.lang('global.idioma').'</th>
				<th width="20%">'.lang('global.tipo_usuario').'</th>
				<th width="9%">'.lang('global.estado').'</th>
				<th width="12%">'.lang('global.acciones').'</th>
			</tr>
		</thead>
		<tbody id="user_list">
		';
    	$idusuario = $this->session->userdata('id_pruduction_suite');
    	if($usuarios){
			$i=0;
	    	foreach ($usuarios as $usuario) {
	    		$cadena_tabla.='<tr class="user_produccion" data-id="'.$usuario['id'].'"';
	    		$style="";
	    		if($i%2!=0){
	    			$style="background:#e6e4e4 !important;";
	    		}
	    		++$i;
	    		$cadena_tabla.=' style="'.$style.'">';
				$cadena_tabla.='<td>'.$usuario['nombre'].'</td>';
				$cadena_tabla.='<td>'.$usuario['apellido'].'</td>';
				$cadena_tabla.='<td>'.$usuario['correo'].'</td>';
				$cadena_tabla.='<td>';
				if($usuario['idioma']=='es'){ 
					$cadena_tabla.='Español'; 
				} else { 
					$cadena_tabla.='Ingles'; 
				} 
				$cadena_tabla.='</td>';

				$cadena_tabla.='<td>';
				$tipo=$this->model_admin->tipoUserId($usuario['id']); 
				$cadena_tabla.=$tipo['0']->descripcion;

				if($tipo['0']->descripcion=='Otros'){
					$cadena_tabla.=':';	
					$rol=$this->model_admin->rolUserId($usuario['id']);
					if($rol){
						foreach ($rol as $r) { 
							$cadena_tabla.= '<strong>';
							$cadena_tabla.= $r['descripcion'].', ';
							$cadena_tabla.= '</strong>';
						}
					}
				}

				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				if($usuario['estado']==1){ 
					$cadena_tabla.='Activo';
				} else { 
					$cadena_tabla.='Inactivo';
				}
							
				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				$cadena_tabla.='<a href="'.base_url().$this->lang->lang().'/admin_user/editar_user/'.$usuario['id'].'">'.lang('global.editar').'</a>';
				$cadena_tabla.=' | ';
				if($usuario['estado']==1){ 
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="0"> '.lang('global.desactivar').'</a>';
					//$cadena_tabla.='<a href="'.base_url().'admin_user/cambiar_estado_user/'.$usuario['id'].'/0">Desactivar</a>';
				} else {
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="1"> '.lang('global.activar').'</a>';
					//$cadena_tabla.='<a href="'.base_url().'admin_user/cambiar_estado_user/'.$usuario['id'].'/1">Activar</a>';
				}
				$cadena_tabla.=' | ';
				$onclick="onclick=\"return confirm('Esta seguro de elimiar este Usuario?')\"";
				$cadena_tabla.="<a href='".base_url().$this->lang->lang()."/admin_user/eliminarUser/".$usuario['id']."' ".$onclick.">".lang('global.eliminar')."</a> ";
				$cadena_tabla.='</td>';
				$cadena_tabla.='</tr>';
				$cadena_tabla.='<tr style="display:none" 
				        id="user_'.$usuario['id'].'" class="producciones_usuario">
					</tr>';
				
				}
				
				$cadena_tabla.='</tbody><script type="text/javascript">$("#usersTable").tablesorter();</script>';
		}
		$data['cadena_tabla'] = $cadena_tabla;
		echo json_encode($data);
    }


    public function buscar_usuarios_estado(){
    	$id = $this->session->userdata('id_pruduction_suite');
    	$estado = $_POST['estado'];
    	if($estado==1){
    		$where='and estado=1';
    	}elseif($estado==0){
    		$where='and estado=0';
    	}else{
    		$where='';
    	}
        $usuarios=$this->model_admin->list_user($id,$where);
		$cadena_tabla='<thead>
			<tr>
				<th width="12%">'.lang('global.nombre').'</th>
				<th width="12%">'.lang('global.apellido').'</th>
				<th width="24%">'.lang('global.correo').'</th>
				<th width="10%">'.lang('global.idioma').'</th>
				<th width="20%">'.lang('global.tipo_usuario').'</th>
				<th width="9%">'.lang('global.estado').'</th>
				<th width="12%">'.lang('global.acciones').'</th>
			</tr>
		</thead>
		<tbody id="user_list">
		';
    	$idusuario = $this->session->userdata('id_pruduction_suite');
    	if($usuarios){
			$i=0;
	    	foreach ($usuarios as $usuario) {
	    		$cadena_tabla.='<tr';
	    		$style="";
	    		if($i%2!=0){
	    			$style="background:#e6e4e4 !important;";
	    		}
	    		++$i;
	    		$cadena_tabla.=' style="'.$style.'">';
				$cadena_tabla.='<td>'.$usuario['nombre'].'</td>';
				$cadena_tabla.='<td>'.$usuario['apellido'].'</td>';
				$cadena_tabla.='<td>'.$usuario['correo'].'</td>';
				$cadena_tabla.='<td>';
				if($usuario['idioma']=='es'){ 
					$cadena_tabla.='Español'; 
				} else { 
					$cadena_tabla.='Ingles'; 
				} 
				$cadena_tabla.='</td>';

				$cadena_tabla.='<td>';
				$tipo=$this->model_admin->tipoUserId($usuario['id']); 
				$cadena_tabla.=$tipo['0']->descripcion;

				if($tipo['0']->descripcion=='Otros'){
					$cadena_tabla.=':';	
					$rol=$this->model_admin->rolUserId($usuario['id']);
					if($rol){
						foreach ($rol as $r) { 
							$cadena_tabla.= '<strong>';
							$cadena_tabla.= $r['descripcion'].', ';
							$cadena_tabla.= '</strong>';
						}
					}
				}

				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				if($usuario['estado']==1){ 
					$cadena_tabla.='Activo';
				} else { 
					$cadena_tabla.='Desactivo';
				}
							
				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				$cadena_tabla.='<a href="'.base_url().$this->lang->lang().'/admin_user/editar_user/'.$usuario['id'].'">'.lang('global.editar').'</a>';
				$cadena_tabla.=' | ';
				if($usuario['estado']==1){ 
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="0"> '.lang('global.desactivar').'</a>';
					//$cadena_tabla.='<a href="'.base_url().'admin_user/cambiar_estado_user/'.$usuario['id'].'/0">Desactivar</a>';
				} else {
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="1"> '.lang('global.activar').'</a>';
					//$cadena_tabla.='<a href="'.base_url().'admin_user/cambiar_estado_user/'.$usuario['id'].'/1">Activar</a>';
				}
				$cadena_tabla.='</td>';
				$cadena_tabla.='</tr>';
				
				}
				$cadena_tabla.='</tbody><script type="text/javascript">$("#usersTable").tablesorter();</script>';
		}
		$data['cadena_tabla'] = $cadena_tabla;
		echo json_encode($data);
    }

    public function user_produccion(){
    	$id_user = $_POST['id_user'];
    	
		$cadena_tabla='<td colspan="7">
							<table>
							 <thead>
								<tr>
									<td width="12%">'.lang('global.producciones').'</td>
									<td width="12%">'.lang('global.rol').'</td>
								</tr>
							<thead>
							<tbody>';
         $user_producciones=$this->model_admin->roles_user_produccion($id_user); 
		 if($user_producciones){
			foreach ($user_producciones as $p) {
				$cadena_tabla.='<tr>
					<td width="12%">'.$p['nombre_produccion'].'</td>
					<td width="12%">'.$p['roles'].'</td>
				</tr>';
			 }
		 }else{
		 	$cadena_tabla.='
		 		<tr>
				<td colspan="2">'.lang('global.No_producciones_asociadas').'</td>
			</tr>';
		 }
		 $cadena_tabla.='</tbody></table></td>';
		$data['cadena_tabla'] = $cadena_tabla;
		echo json_encode($data);
    }




}
