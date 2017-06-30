<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elementos extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_elementos');
	    $this->load->model('model_capitulos');
	    $this->load->model('model_escenas');
	    $this->load->model('model_plan_produccion');
	    $this->load->model('model_admin');
	    $this->_logeo_in();
	     setlocale(LC_TIME, 'es_ES.UTF-8');
	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
    }
	
	public function index($id_produccion,$id_categoria='',$id_locacion='',$desde="",$hasta="",$roles=''){
		$tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
		if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
		  $rol='';	
          $id_user=$this->session->userdata('id_pruduction_suite');
          $user=$this->model_admin->rolUserId($id_user);
          $tipo_rol=$user['0']['id_rol_otros'];
           $continuar=0;
	      if($user){
		        foreach ($user as $u) {
		          if($u['id_rol_otros']== 2 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 8 or $u['id_rol_otros']== 15 or $u['id_rol_otros']== 17){
		            $continuar=1;
		            break;
		          }
		        }
		   }
          if($continuar==1 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
			$data['id_produccion']=$id_produccion;
			$produccion = $this->model_plan_produccion->produccion_id($id_produccion);
			$categorias_elementos=$this->model_elementos->categorias_elementos($id_produccion);
			$produccion=$this->model_plan_produccion->produccion_id($id_produccion);
			$locacion='';
			$sets='';

			$capitulos = $this->model_capitulos->capitulos_produccion_2($id_produccion);

			if($id_categoria=='locacion'){
		        $carga=2;
		        $id_categoria=$categorias_elementos[0]['id'];
		        if($desde!="" AND $hasta!=""){
		        	 if($desde=='null'){
				     	 $desde=$capitulos['0']['numero'];
				     	 $valor_desde=null;
				     }

				     if($hasta=='null'){
				     	$ultimo=end($capitulos);
				     	$hasta=$ultimo['numero'];
				     	$valor_hasta=null;
				     }
		
		        	$locacion=$this->model_elementos->locacion_usos_limite($id_produccion,"",$desde,$hasta,0,30);
		        		if(isset($valor_desde)){
		        			if(!$valor_desde){
						    $desde=null;
							}
		        		}
		        		if(isset($valor_hasta)){
		        			if(!$valor_hasta){
								$hasta=null;
							}
		        		}
		    	}else{
		    		$locacion=$this->model_elementos->locacion_usos($id_produccion,0);
		    	}
			}else if($id_categoria=='sets'){
		        $carga=3;
		        $id_categoria=$categorias_elementos[0]['id'];
		        $locacion=$this->model_elementos->locacion_usos($id_produccion,0);
		        if($desde!="" AND $hasta!=""){
		        	if($desde=='null'){
				     	 $desde=$capitulos['0']['numero'];
				     	 $valor_desde=null;
				     }

				     if($hasta=='null'){
				     	$ultimo=end($capitulos);
				     	$hasta=$ultimo['numero'];
				     	$valor_hasta=null;
				     }
		        	$sets=$this->model_elementos->set_locacion_limit($id_locacion,$desde,$hasta,0);
		        	 if(isset($valor_desde)){
		        			if(!$valor_desde){
						    $desde=null;
							}
		        		}
						if(isset($valor_hasta)){
		        			if(!$valor_hasta){
								$hasta=null;
							}
		        		}
		        }else{
		        	$sets=$this->model_elementos->set_escena($id_locacion);
		        }	
			}else if($id_categoria=="set"){
				$carga=4;
				if($desde!="" AND $hasta!=""){
					if($desde=='null'){
				     	 $desde=$capitulos['0']['numero'];
				     	 $valor_desde=null;
				     }

				     if($hasta=='null'){
				     	$ultimo=end($capitulos);
				     	$hasta=$ultimo['numero'];
				     	$valor_hasta=null;
				     }
		        	$sets=$this->model_elementos->sets_produccion_limite($id_produccion,$desde,$hasta);
		        	 if(isset($valor_desde)){
		        			if(!$valor_desde){
						    $desde=null;
							}
		        		}
						if(isset($valor_hasta)){
		        			if(!$valor_hasta){
								$hasta=null;
							}
		        		}
		    	}else{
		    		$sets=$this->model_elementos->sets_produccion($id_produccion);
		    	}
			}else{	
				if($id_categoria==''){
				    $id_categoria=$categorias_elementos[0]['id'];
				}
				$carga=1;
				if($desde!="" AND $hasta!=""){
						$sql='';
				        $roles_selec=explode('-',$roles);
				        $rol=array();
				        if($roles){
				          $sql.=' AND (';
				            $cont=0;
				          foreach ($roles_selec as $r) {
				          	if($r=='Protagonista'){
				          		$id_rol=1;
				          	}elseif ($r=='Reparto') {
				          		$id_rol=3;
				          	}elseif ($r=='Figurante') {
				          		$id_rol=2;
				          	}elseif ($r=='Extra') {
				          		$id_rol=4;
				          	}
				            if($cont==0){
				              $sql.=' e.rol='.$id_rol.' ';  
				              $cont++;
				            }else{
				              if($r){
				               $sql.=' OR e.rol='.$id_rol.' ';  
				              }  
				            }
				            array_push($rol,$r);
				          }
				          $sql.=' )';
				        }
				        if($desde=='null'){
				     	 $desde=$capitulos['0']['numero'];
				     	 $valor_desde=null;
				     }

				     if($hasta=='null'){
				     	$ultimo=end($capitulos);
				     	$hasta=$ultimo['numero'];
				     	$valor_hasta=null;
				     }
				     $total_elementos=$this->model_elementos->elemento_id_produccion_limit_total($id_produccion,$id_categoria,$desde,$hasta,$sql);
				  
				     $data['total_elementos'] = $total_elementos;
				     $valor_desde=1;
				     $valor_hasta=1;
				     
					$elementos=$this->model_elementos->elemento_id_produccion_limit($id_produccion,$id_categoria,$desde,$hasta,0,$sql);
					
					if(isset($valor_desde)){
		        			if(!$valor_desde){
						    $desde=null;
							}
		        		}
						if(isset($valor_hasta)){
		        			if(!$valor_hasta){
								$hasta=null;
							}
		        		}
				}else{;
					$total_elementos=$this->model_elementos->elemento_id_produccion_total($id_produccion,$id_categoria);
				    $data['total_elementos'] = $total_elementos;
					$elementos=$this->model_elementos->elemento_id_produccion($id_produccion,$id_categoria,0);


				}
				$rol_actores_elementos=$this->model_escenas->rol_actores_elementos();
				$tipos_moneda = $this->model_elementos->tipos_moneda(); 
				$tipos_documento = $this->model_elementos->tipos_documento();
				$contratos = $this->model_elementos->contratos();
				$data['tipos_documento'] = $tipos_documento;
				$data['tipos_moneda'] = $tipos_moneda;
				$data['contratos'] = $contratos;
			    $data['elementos']=$elementos;
			    $data['rol_actores_elementos']=$rol_actores_elementos;
			}
			
			$usuario_permisos = $this->permisos_usuarios($id_produccion);
			$data['usuario_permisos'] = $usuario_permisos;
			$locaciones=$this->model_elementos->locacion_limit($id_produccion);
			$total_locacion=$this->model_escenas->locacion($id_produccion);
			$data['locaciones']=$locaciones;
			$data['total_locacion']=$total_locacion;
			
			
		
			$data['capitulos'] = $capitulos;
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
		    $data['locacion'] = $locacion;
		    $data['sets'] = $sets;
		    $data['carga'] = $carga;
		    $data['id_locacion'] = $id_locacion;
		    $data['produccion'] = $produccion;
		    $data['categorias_elementos']=$categorias_elementos;
		    $data['id_categoria']=$id_categoria;
		    $data['roles']=$rol;
		    $data['view']='elementos/index';
		    $con=1;

		    $this->load->view('includes/template',$data);
		}else{
            redirect($this->lang->lang().'/produccion/producciones');
        }       
      }else{
        redirect($this->lang->lang().'/produccion/producciones');
      } 
	}

	public function carga_tabla_ajax(){
		$id_produccion = $_POST['id_produccion'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$id_categoria = $_POST['id_categoria'];
		$id_locacion = $_POST['id_locacion'];
		$tamano_inicial = $_POST['tamano_inicial'];
		$tamano_final = $_POST['tamano_final'];
		$limite_escena = $_POST['limite_escena'];
		$categorias_elementos=$this->model_elementos->categorias_elementos($id_produccion);
		if($id_categoria==''){
		    $id_categoria=$categorias_elementos[0]['id'];
		}
		$roles_editar='';
		if($desde!="" AND $hasta!=""){
			$tipo=$_POST['tipo'];
			if($tipo==1){ 
				$sql='';
				$roles=$_POST['cadena'];
		        $roles_selec=explode(',',$roles);
		        $rol=array();
		        if($roles){
		          $sql.=' AND (';
		            $cont=0;
		          foreach ($roles_selec as $r) {
		          	  if($cont==0){
		          	  	$roles_editar=$r.'-';
		          	  }else{
		          	  	$roles_editar=$roles_editar.''.$r;
		          	  }
		          	    
			          	if($r=='Protagonista'){
			          		$id_rol=1;
			          	}elseif ($r=='Reparto') {
			          		$id_rol=3;
			          	}elseif ($r=='Figurante') {
			          		$id_rol=2;
			          	}elseif ($r=='Extra') {
			          		$id_rol=4;
			          	}
			            if($cont==0){
			              $sql.=' e.rol='.$id_rol.' ';  
			              $cont++;
			            }else{
			              if($r){
			               $sql.=' OR e.rol='.$id_rol.' ';  
			              }  
			            }
		            array_push($rol,$r);
		         	 }
		          $sql.=' )';
                 }
			    $elementos=$this->model_elementos->elemento_id_produccion_limit($id_produccion,$id_categoria,$desde,$hasta,$limite_escena,$sql);   	
			}else{
				$elementos=$this->model_elementos->elemento_id_produccion_limit($id_produccion,$id_categoria,$desde,$hasta,$limite_escena);
			}

		}else{
			$elementos=$this->model_elementos->elemento_id_produccion($id_produccion,$id_categoria,$limite_escena);
		}


		$cadena_tabla=$this->table_construct_elementos($elementos,$id_produccion,$id_categoria,$tamano_inicial,$tamano_final,$id_locacion,$desde,$hasta,$roles_editar);
		//echo $cadena_tabla;
		echo json_encode($cadena_tabla);
	}

	public function carga_tabla_nombre(){
		$palabra = $_POST['palabra'];
		$id_categoria = $_POST['id_categoria'];
		$id_produccion = $_POST['id_produccion'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$id_locacion = $_POST['id_locacion'];
		$tipo=$_POST['tipo'];
		$roles_editar='';
		if($tipo==1){ 
			$roles=$_POST['cadena'];
	        $roles_selec=explode(',',$roles);
	        $rol=array();
	        if($roles){
	            $cont=0;
	          foreach ($roles_selec as $r) {
	          	  if($cont==0){
	          	  	$roles_editar=$r.'';
	          	  	$cont++;
	          	  }else{
	          	  	$roles_editar=$roles_editar.'-'.$r;
	          	  }
	          	  //$cont++;
              }
            }
         }     
		$categorias_elementos=$this->model_elementos->categorias_elementos($id_produccion);
		if($id_categoria==''){
		    $id_categoria=$categorias_elementos[0]['id'];
		}
		$elementos = $this->model_elementos->elemento_id_produccion_palabra($id_produccion,$id_categoria,$palabra);
		$cadena_tabla=$this->table_construct_elementos($elementos,$id_produccion,$id_categoria,0,count($elementos)-1,$id_locacion,$desde,$hasta,$roles_editar);
		echo json_encode($cadena_tabla);
	}

	public function table_construct_elementos($elementos,$id_produccion,$id_categoria,$tamano_inicial,$tamano_final,$id_locacion,$desde,$hasta,$roles_editar){

		setlocale(LC_ALL,"es_ES");
		$produccion=$this->model_plan_produccion->produccion_id($id_produccion);
		$rol_actores_elementos=$this->model_escenas->rol_actores_elementos();
		$tipos_moneda = $this->model_elementos->tipos_moneda(); 
		$tipos_documento = $this->model_elementos->tipos_documento(); 
		$capitulos = $this->model_capitulos->capitulos_produccion_2($id_produccion);
		$contratos = $this->model_elementos->contratos();

		$usuario_permisos = $this->permisos_usuarios($id_produccion);
		$categorias_elementos=$this->model_elementos->categorias_elementos($id_produccion);
		if($id_categoria==''){
		    $id_categoria=$categorias_elementos[0]['id'];
		}

		if($categorias_elementos[0]['id']!=$id_categoria) {
	      $hide = 'style="display:none"';
	    }else{
	      $hide="";
	    } 

		$cadena_tabla="";
		$count=0;	

		if($elementos){
			//for ($k=$tamano_inicial; $k <= $tamano_final; $k++) { 
			foreach ($elementos as $elementos) {
				//echo $k;
				if ($count%2 == 0) {
		          $classRow = "white";
		        }else{
		          $classRow = "gray_light";
		        }
		        $count++;

		        $esc_producidas=$this->model_elementos->escenas_producidas($elementos['id_elemento']);
		        $esc_porproducir=$this->model_elementos->escenas_proproducir($elementos['id_elemento']);
		        $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);

			    if($esc_producidas[0]->total==0){
			    	$por=0;
			    }else{
			        $por=$esc_producidas[0]->total;
			        $por=round($por); 
			    }    

		        if($esc_porproducir[0]->total==0){
		        	$por2=0;
		        }else{
		            $por2=$esc_porproducir[0]->total;
		            $por2=round($por2);  
		        }
		        $pot_total=abs($por-$por2);

	            $cadena_tabla.='<tr class="actionAsing element_'.$elementos['id_elemento'].' diagrama" data-tr="'.$elementos['id_elemento'].'" data-producidas="'.$por.'" data-noproducidas="'.$pot_total.'" data-noasignadas="'.abs($por2-$total_escenas[0]->total).'" data-idcahrt="'.$elementos['nombre'].'" data-idproduccion="'.$id_produccion.'" data-idelemento="'.$elementos['id_elemento'].'" data-tipo="1">';
	            $cadena_tabla.='<td class="element_name">'.$elementos['nombre'].'</td>';
	            

                if(!$elementos['rol']){ $rol='';}
	            $cadena_tabla.='<td '.$hide.' class="rol_name" >'.$elementos['rol'].'</td>';
	            if($hide!=""){ 
	            	$cadena_tabla.='<td>'.$this->corta_palabra($elementos['des_elem'],32);
	                if(strlen($elementos['des_elem'])>32){
	                   $cadena_tabla.= '...';
	                }
	            	$cadena_tabla.='</td>';
	            }else{ 
	                $cadena_tabla.='<td>'.$elementos['actor_nombre']." ".$elementos['actor_apellido'].'</td>';
	                if($hide==""){
	                   $libretos_elementos=$this->model_elementos->libretos_elementos($elementos['id_elemento']);
                       $libretos_elementos=$libretos_elementos['0']->libretos;
                    $cadena_tabla.='<td style="text-align:center">'.count(explode(',', $libretos_elementos)).'</td>';
                	}
	                $cadena_tabla.='<td>'.$elementos['tipo_contrato'].'</td>';
	            } 
	            $uso=$this->model_elementos->escenas_has_elementos($elementos['id_elemento']);
	            $cadena_tabla.='<td style="text-align:center">'.$uso.'</td>';
	            $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
		        if($uso==0){
		            $por=0;
		        }else{
		            $por=($uso*100)/$total_escenas[0]->total;
		            $por=round($por);  
		        }

	            $cadena_tabla.='<td style="text-align:center">'.$por.'%</td>';
	            $cadena_tabla.='<td style="text-align:center">'.$esc_producidas[0]->total.'</td>';     
	            $cadena_tabla.='<td style="text-align:center">'.$esc_porproducir[0]->total.'</td>';
	            
	            $cadena_tabla.='</tr>';
	            $cadena_tabla.='<tr class="info_element element_'.$elementos['id_elemento'].'" data-tr="'.$elementos['id_elemento'].'" >';
	            $cadena_tabla.='<td style="text-align:center;display:none;">'.$elementos['nombre'].'</td>';
	            $cadena_tabla.='<td '.$hide.'style="text-align:center;display:none;">'.$elementos['rol'].'</td>';
	            if($hide!=""){
	                $cadena_tabla.='<td style="text-align:center;display:none;">'.$this->corta_palabra($elementos['des_elem'],32);
	                if(strlen($elementos['des_elem'])>32){
	                	$cadena_tabla.= '...';
	                }
	                $cadena_tabla.='</td>';
	            }else{ 
	                $cadena_tabla.='<td style="text-align:center;display:none;">'.$elementos['actor_nombre']." ".$elementos['actor_apellido'].'</td>';
	                if($hide==""){
                    $cadena_tabla.='<td style="text-align:center;display:none;">'.count(explode(',', $libretos_elementos)).'</td>';
                	}
	                $cadena_tabla.='<td style="text-align:center;display:none;">'.$elementos['tipo_contrato'].'</td>';
	            }
	            $uso=$this->model_elementos->escenas_has_elementos($elementos['id_elemento']);
	            $cadena_tabla.='<td style="text-align:center;display:none;">'.$uso.'</td>';
	            $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
	            if($uso==0){
	            	$por=0;
	            }else{
		            $por=($uso*100)/$total_escenas[0]->total;
		          	$por=round($por);  
	            }
	            $cadena_tabla.='<td style="text-align:center;display:none;">'.$por.'%</td>';
	                      
				$cadena_tabla.='<td style="text-align:center;display:none;">'.$esc_producidas[0]->total.'</td>';
	                      
				$cadena_tabla.='<td style="text-align:center;display:none;">'.$esc_porproducir[0]->total.'</td>';
				
				$cadena_tabla.='<td colspan="9">';
				$cadena_tabla.='<div class="row">';
				$libretos_elementos=$this->model_elementos->libretos_elementos($elementos['id_elemento']);
				$libretos_elementos=$libretos_elementos['0']->libretos;
				$cadena_tabla.='<div class="columns twelve">                  <span class="title_graphic"><h5>Libretos: <span>'.str_replace(',', ', ', $libretos_elementos).'</span></h5></span>
                          </div>';
				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div class="torta_porcentaje">';

				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div id="chart_div'.$elementos['nombre'].'"></div>';
				$cadena_tabla.='<div class="convencionesPieChart">';
				$cadena_tabla.='<ul>';
				$cadena_tabla.='<li> <span class="yellow"></span> Escenas producidas</li>';
				$cadena_tabla.='<li> <span class="pink"></span> Escenas no producidas</li>';
				$cadena_tabla.='<li> <span class="gray"></span> Escenas no asignadas</li>';
				$cadena_tabla.='</ul>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column six">';
	            $cadena_tabla.='<div class="yellowBox">';
	            $cadena_tabla.='<h4>Escenas <br> producidas </h4>';
	            $cadena_tabla.='<div class="number">'.$esc_producidas[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="magentaBox">';
				$cadena_tabla.='<h4>Escenas no<br> producidas </h4>';
				$cadena_tabla.='<div class="number">'.$esc_porproducir[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="clr"></div>';
				$cadena_tabla.='<div class="column twelve">';
				$cadena_tabla.='<div class="grayBox">'.$total_escenas[0]->total.' escenas creadas</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
	     
				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div id="loadElements'.$elementos['id_elemento'].'" class="loadElements" style="height: 301px;width: 99%;">';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="barras_estadisticas">';
				$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion);
				$cadena_tabla.='<script type="text/javascript">';
				$cadena_tabla.='var escenasAsignadas'.$elementos['id_elemento'].'=new Array();';
				$cadena_tabla.='var escenasCapitulo'.$elementos['id_elemento'].'=new Array();';
				$cadena_tabla.='</script>';
				$cadena_tabla.='<div id="capitulos_chart'.$elementos['id_elemento'].'" style="min-width: 560px; height: 250px;"></div>';
				$cadena_tabla.='<label>Total de Libretos: '.count($total_capitulos).'</label>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';

	            if($categorias_elementos[0]['id']==$id_categoria) {
					$cadena_tabla.='<div class="columns twelve">';
					$cadena_tabla.='<ul class="div_cell">';
					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Documento:</label>';
					$cadena_tabla.=$elementos['documento_actor'];
					$cadena_tabla.='</li>';
					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Tipo Documento:</label>';
					$cadena_tabla.=$elementos['tipo_documento'];
					$cadena_tabla.='</li>';
					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Monto:</label>';
					$cadena_tabla.= number_format($elementos['monto'], 2, '.', ",");
					$cadena_tabla.=' </li>';
					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Tipo moneda:</label>';
					$cadena_tabla.= $elementos['tipo_moneda'];
					$cadena_tabla.='</li>';

					$fecha_inicio = ""; 
					$fecha_finalizacion = ""; 
					if($elementos['fecha_finalizacion'] AND $elementos['fecha_finalizacion']!= '0000-00-00'){
						$fecha_finalizacion = $elementos['fecha_finalizacion'];
					}
					$e_fecha_inicio_2=$this->model_elementos->elementos_fecha2($elementos['id_elemento']);
					if($elementos['fecha_inicio'] AND $elementos['fecha_inicio']!= '0000-00-00'){
						$fecha_inicio = $elementos['fecha_inicio'];
					}else if($e_fecha_inicio_2){
						$fecha_inicio = $e_fecha_inicio_2['0']->fecha_inicio_2;
					} 

					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Fecha inicio:</label>';
					$cadena_tabla.=$fecha_inicio;
					$cadena_tabla.='</li>';
					$cadena_tabla.='<li>';
					$cadena_tabla.='<label>Fecha finalización:</label>';
					$cadena_tabla.=$fecha_finalizacion;
					$cadena_tabla.='</li>';
					$cadena_tabla.='</ul>';
					$cadena_tabla.='</div>';
	            } 

	            if($usuario_permisos=="write" and $produccion['0']->estado!=2){ 
	                $cadena_tabla.='<div class="columns twelve">';
	                $cadena_tabla.='<label class="editar_elemento button" id="'.$elementos['id_elemento'].'">Editar elemento</label>';
	                if($uso==0){
	              		$cadena_tabla.='<input type="button" value="Eliminar" id="'.$elementos['id_elemento'].'" class="button secondary eliminar_elemento">';   
	              	} 
	              	$cadena_tabla.='</div>';
					$cadena_tabla.='<div class="clr"></div>';
	                $cadena_tabla.='<div style="display:none" class="form_elemento_'.$elementos['id_elemento'].'">';
	                $cadena_tabla.='<br>';
	                $cadena_tabla.=form_open($this->lang->lang().'/elementos/editar_elemento'); 
					$cadena_tabla.='<div class="row">';
					$cadena_tabla.='<div class="column six">';
					$cadena_tabla.='<label>Tipo elemento</label>';
					$cadena_tabla.='<select name="categoria" id="id_elemento2">';
					$cadena_tabla.='<option id="'.$elementos['tipo'].'" class="elemento_'.$elementos['id_tipo_elemento'].'" value="'.$elementos['id_tipo_elemento'].'">'.$elementos['tipo'].'</option>';
					foreach ($categorias_elementos as $c) { 
						$cadena_tabla.='<option id="'.$c['tipo'].'" class="elemento2_'.$c['id'].'" value="'.$c['id'] .'">'.$c['tipo'] .'</option>';
					}
					$cadena_tabla.='</select>'; 

	                if($elementos['tipo']=='Personaje' and $produccion['0']->tipo_produccion==2){
						$cadena_tabla.='<label>Actor</label>';
						$cadena_tabla.='<div class="column three">';
						$cadena_tabla.='<label>nombre</label>';
						$cadena_tabla.='<input type="text" name="actor_nombre" value="'.$elementos['actor_nombre'].'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column three">';
						$cadena_tabla.='<label>apellido</label>';
						$cadena_tabla.='<input type="text" name="actor_apellido" value="'.$elementos['actor_apellido'].'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column three">';
						$cadena_tabla.='<label>Documento</label>';
						$cadena_tabla.='<input type="text" name="documento" value="'.$elementos['documento_actor'].'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column three">';
						$cadena_tabla.='<label>Tipo Documento</label>';
						$cadena_tabla.='<select name="tipo_documento" disabled>';
						$cadena_tabla.='<option>Seleccione una opción</option>';
						foreach ($tipos_documento as $tipo_documento) {
							if($tipo_documento->id==$elementos['id_tipo_documento']){
							  $cadena_tabla.= "selected";
							}
							$cadena_tabla.='<option ';
							if($tipo_documento->id==$elementos['id_tipo_documento']){
								$cadena_tabla.="selected";
	                        } 
	                        $cadena_tabla.=' value="'.$tipo_documento->id.'" >'.$tipo_documento->descripcion.'</option>';
	                    }
	                    $cadena_tabla.='</select>';
	                    $cadena_tabla.='</div>';
	                }
					$cadena_tabla.='</div>';
					$cadena_tabla.='<div class="column six">';
					$cadena_tabla.='<label>Nombre elemento</label>';
					$cadena_tabla.='<input type="hidden" name="id_elemento" value="'.$elementos['id_elemento'].'" >';
					$cadena_tabla.='<input type="hidden" name="id_locacion" value="'.$id_locacion.'" >';
                    $cadena_tabla.='<input type="hidden" name="desde" value="'.$desde.'" >';
                    $cadena_tabla.='<input type="hidden" name="hasta" value="'.$hasta.'" >';
                    $cadena_tabla.='<input type="hidden" name="roles" value="'.$roles_editar.'" >';
					$cadena_tabla.='<input type="text" name="nombre" value="'.$elementos['nombre'].'">';
					$cadena_tabla.='<label class="error">'.form_error('nombre').'</label>  '; 
					$cadena_tabla.='<input type="hidden" value="'.$id_produccion .'" name="id_produccion">';
					if($elementos['tipo']=='Personaje'){ $c=''; }else{$c='display:none';} 
					$cadena_tabla.='<div class="rol_personaje2" style="'.$c.'">';
					$cadena_tabla.='<label for="type">rol:</label>';
					$cadena_tabla.='<select name="rol" data-rol="'.$elementos['rol'].'" class="rol_personaje">';
					/*foreach ($rol_actores_elementos as $r) {
						$cadena_tabla.='<option value="'.$r['id'].'"';
						if($elementos['rol']==$r['rol']){ 
							$cadena_tabla.="selected"; 
						} 
						$cadena_tabla.='>';
						$cadena_tabla.= $r['rol'].'</option>';
					}*/
					
					if($elementos['rol']=="Protagonista"){ 
						$cadena_tabla.='<option selected  value="1">Protagonista</option>';
					 }else{
					 	$cadena_tabla.='<option value="1">Protagonista</option>';
					 }
					 if($elementos['rol']=="Reparto"){ 
						$cadena_tabla.='<option selected  value="3">Reparto</option>';
					 }else{
					 	$cadena_tabla.='<option value="3">Reparto</option>';
					 }
					 if($elementos['rol']=="Figurante"){ 
						$cadena_tabla.='<option selected value="2">Figurante</option>';
					 }else{
					 	$cadena_tabla.='<option value="2">Figurante</option>';
					 }
					 if($elementos['rol']=="Extra"){ 
						$cadena_tabla.='<option selected value="4">Extra</option>';
					 }else{
					 	$cadena_tabla.='<option value="4">Extra</option>';
					 }
					$cadena_tabla.='</select>'; 
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';

	                if($elementos['tipo']=='Personaje' and $produccion['0']->tipo_produccion==2){   
						$cadena_tabla.='<div class="row">';
						$cadena_tabla.='<div class="column twelve">';
						$cadena_tabla.='<div class="column two">';
						$cadena_tabla.='<label>Monto</label>';
						$cadena_tabla.='<input type="text" class="monto_personaje" name="monto" value="'.$elementos['monto'].'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column two">';
						$cadena_tabla.='<label>Tipo moneda</label>';
						$cadena_tabla.='<select name="tipo_moneda" disabled>';
						$cadena_tabla.='<option>Seleccione una opción</option>';
						foreach ($tipos_moneda as $tipo_moneda) { 
							$cadena_tabla.='<option '; 
							if($tipo_moneda->id==$elementos['id_tipo_moneda']){
							  $cadena_tabla.="selected";
							}
							$cadena_tabla.=' value="'.$tipo_moneda->id.'" >'.$tipo_moneda->descripcion.'</option>';
						} 
						$cadena_tabla.='</select> ';                                  
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column two">';
						$cadena_tabla.='<label>Tipo contrato</label> ';
						$cadena_tabla.='<select name="cotrato" disabled>';
						$cadena_tabla.='<option value="">Seleccione una opción</option>';
						foreach ($contratos as $contrato) {
							$select=""; 
							if($contrato->id == $elementos['id_tipo_contrato']){ 	
								$select="selected"; 
							}
							$cadena_tabla.='<option '.$select.' value="'.$contrato->id.'">'.$contrato->descripcion.'</option>';
						}
						$cadena_tabla.='</select>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column three">';
	                    $cadena_tabla.='<label>fecha inicio</label>';
	                    $fecha_inicio = ""; 
	                    $fecha_finalizacion = ""; 
	                    if($elementos['fecha_finalizacion'] AND $elementos['fecha_finalizacion']!= '0000-00-00'){
	                      $fecha_finalizacion = $elementos['fecha_finalizacion'];
	                    }
	                    $e_fecha_inicio_2=$this->model_elementos->elementos_fecha2($elementos['id_elemento']);
	                    if($elementos['fecha_inicio'] AND $elementos['fecha_inicio']!= '0000-00-00'){
	                      $fecha_inicio = $elementos['fecha_inicio'];
	                    }else if($e_fecha_inicio_2){
	                      $fecha_inicio = $e_fecha_inicio_2['0']->fecha_inicio_2;
	                    }
	                                        
						$cadena_tabla.='<input type="text" class=" ui-datepicker-trigger" name="fecha_inicio" value="'.$fecha_inicio.'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='<div class="column three">';
						$cadena_tabla.='<label>fecha finalización</label>';
						$cadena_tabla.='<input type="text" id="#date_1" class=" ui-datepicker-trigger" name="fecha_finalizacion" value="'.$fecha_finalizacion.'" readonly>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='</div>';
						$cadena_tabla.='</div>';

						if($elementos['fecha_liquidacion'] AND $elementos['fecha_liquidacion']!= '0000-00-00'){
	                      $fecha_liquidacion = $elementos['fecha_liquidacion'];
	                    }else{
	                      $fecha_liquidacion = '';
	                    }
	                                        
						$cadena_tabla.='<div class="row"><div class="column two "><label>fecha liquidacion</label>';
						$cadena_tabla.='<input type="text" class="datepicker ui-datepicker-trigger" name="fecha_liquidacion" value="'.$fecha_liquidacion.'">';
						$cadena_tabla.='</div>';
						$cadena_tabla.='</div>';
	                } 

					$cadena_tabla.='<div class="row">';
					$estilo_t=""; 
					if($categorias_elementos[0]['id']==$id_categoria) {
						$estilo_t = "display:none;";
					}
					$cadena_tabla.='<div style="<?=$estilo_t?>" class="column twelve">';
					$cadena_tabla.='<label>Descripción:</label>';
					$cadena_tabla.='<textarea name="descripcion">'.$elementos['des_elem'].'</textarea>';    
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
					$cadena_tabla.='<div class="row">';
					$cadena_tabla.='<div class="column twelve">';
					$cadena_tabla.='<input type="submit" value="Actualizar Elemento" class="button">';
					$cadena_tabla.='<input type="button" value="Cancelar" id="'.$elementos['id_elemento'].'" class="button secondary cancel_edit_element">';
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
					$cadena_tabla.=form_close();
					$cadena_tabla.='</div>';
				}

				$cadena_tabla.='<div class="clr6"></div>';

                $planes_elemento = $this->model_elementos->planes_elemento($elementos['id_elemento']);
                if($planes_elemento){
                $cadena_tabla.='<table class="anyid" id="anyid">';
                  $cadena_tabla.='<tr>';
                    $cadena_tabla.='<th class="hs">Lib</th>';
                    $cadena_tabla.='<th class="hs">Escena</th>';
                    $cadena_tabla.='<th class="hs">Locación</th>';
                    $cadena_tabla.='<th class="hs">Set</th>';
                    $cadena_tabla.='<th class="hs">Fecha Grabación</th>';
                    $cadena_tabla.='<th class="hs">Unidad</th>';
                    $cadena_tabla.='<th class="hs">Producida</th>';
                  $cadena_tabla.='</tr>';
                foreach ($planes_elemento as $plan_elemento) {
                    $cadena_tabla.='<tr>';
                      $cadena_tabla.='<td>'.$plan_elemento->numero_libreto.'</td>';
                      $cadena_tabla.='<td>'.$plan_elemento->numero_escena.'</td>';
                      $cadena_tabla.='<td>'.$plan_elemento->nombre_locacion.'</td>';
                      $cadena_tabla.='<td>'.$plan_elemento->nombre_set.'</td>';
                      //$cadena_tabla.='<td>'.$plan_elemento->fecha_grabacion.'</td>';
                      $cadena_tabla.='<td> <span style="display: inline-block;width: 1px; overflow:hidden; text-indent:-999">'.date('Y-m-d',strtotime($plan_elemento->fecha_grabacion)).'</span>'.strftime('%Y-%b-%d',strtotime($plan_elemento->fecha_grabacion)).'</td>';
                      $cadena_tabla.='<td>'.$plan_elemento->numero_unidad.'</td>';
                      $cadena_tabla.='<td>';
                      if($plan_elemento->producida=="0"){
                          $cadena_tabla.='NO';
                        }else{
                          $cadena_tabla.="SI";
                        }
                      $cadena_tabla.='</td>';
                    $cadena_tabla.='</tr>';
                  }
                $cadena_tabla.='</table>';
                } 

				$cadena_tabla.='</div>';    
				$cadena_tabla.='</td>';
				$cadena_tabla.='</tr>';
			}
		}
		return $cadena_tabla;
	}


	public function carga_tabla_nombre_locacion(){
		$id_produccion = $_POST['id_produccion'];
		$palabra = $_POST['palabra'];
		$locacion = $this->model_elementos->locacion_usos_palabra($id_produccion,$palabra);
		$cadena_tabla=$this->construct_table_locaciones($id_produccion,$locacion,0,count($locacion)-1);
		echo json_encode($cadena_tabla);
	}

	public function carga_tabla_ajax_locaciones(){
		$id_produccion = $_POST['id_produccion'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$id_categoria = $_POST['id_categoria'];
		$tamano_inicial = $_POST['tamano_inicial'];
		$tamano_final = $_POST['tamano_final'];
		$limite_escena = $_POST['limite_escena'];
		$capitulos = $this->model_capitulos->capitulos_produccion_2($id_produccion);

		if($desde!="" AND $hasta!=""){
        	$locacion=$this->model_elementos->locacion_usos_limite($id_produccion,"",$desde,$hasta,$tamano_final,$limite_escena);
    	}else{
    		$locacion=$this->model_elementos->locacion_usos($id_produccion,$limite_escena);
    	}
    	$cadena_tabla=$this->construct_table_locaciones($id_produccion,$locacion,$tamano_inicial,$tamano_final);
		echo json_encode($cadena_tabla);
	}

	public function construct_table_locaciones($id_produccion,$locacion,$tamano_inicial,$tamano_final){
		$usuario_permisos = $this->permisos_usuarios($id_produccion);
		$produccion=$this->model_plan_produccion->produccion_id($id_produccion);

		$cadena_tabla="";
		$count=0;

		if($locacion){
			//for ($k=$tamano_inicial; $k <= $tamano_final; $k++) { 
			foreach ($locacion as $locacion) {
				if ($count%2 == 0) {
		          $classRow = "white";
		        }else{
		          $classRow = "gray_light";
		        }$count++;

		        $esc_producidas=$this->model_elementos->escenas_producidas_idcapitulo($locacion['id']);
		        $esc_porproducir=$this->model_elementos->escenas_porproducidas_idcapitulo($locacion['id']); 
		        $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
		        if($esc_producidas[0]->total==0){
		        	$por=0;
		        }else{
		        	$por=$esc_producidas[0]->total;
		        	$por=round($por); 
		        } 
		        if($esc_porproducir[0]->total==0){
		        	$por2=0;
		        }else{
		        	$por2=$esc_porproducir[0]->total;
		        	$por2=round($por2);  
		        }
		        $pot_total=abs($por-$por2);
		        $cadena_tabla.='<tr class="actionAsing  element_'.$locacion['id'].' diagrama" data-tr="'.$locacion['id'].'" data-producidas="'.$por .'" data-noproducidas="'.$pot_total.'" data-noasignadas="'. abs($por2-$total_escenas[0]->total).'" data-idcahrt="'. $locacion['nombre'] .'" data-idproduccion="'. $id_produccion.'" data-idelemento="'. $locacion['id'] .'" data-tipo="2">';
		        $cadena_tabla.='<td class="element_name">'.$locacion['nombre'].'</td>';
		        $uso=$locacion['uso'];
		        $cadena_tabla.='<td style="text-align:center">'.$uso.'</td>';
		        if($uso==0){
			        $por=0;
			    }else{
			        $por=($uso*100)/$total_escenas[0]->total;
			        $por=round($por);  
			    }

				$cadena_tabla.='<td style="text-align:center">'.$por.'%</td>';
				$cadena_tabla.='<td style="text-align:center">'.$esc_producidas[0]->total.'</td>';
				$cadena_tabla.='<td style="text-align:center">'.$esc_porproducir[0]->total.'</td>';
				$cadena_tabla.='</tr>';

				$cadena_tabla.='<tr class="info_element element_'.$locacion['id'].'" data-tr="'.$locacion['id'].'" >';
				$cadena_tabla.='<td colspan="7">';

				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div class="torta_porcentaje">';
		                                            
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column seven">';
				$cadena_tabla.='<div id="chart_div'.$locacion['nombre'].'"></div>';
				$cadena_tabla.='<div class="convencionesPieChart">';
				$cadena_tabla.='<ul>';
				$cadena_tabla.='<li> <span class="yellow"></span> Escenas producidas</li>';
				$cadena_tabla.='<li> <span class="pink"></span> Escenas no producidas</li>';
				$cadena_tabla.='<li> <span class="gray"></span> Escenas no asignadas</li>';
				$cadena_tabla.='</ul>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column five">';
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="yellowBox">';
				$cadena_tabla.='<h4>Escenas <br> producidas </h4>';
				$cadena_tabla.='<div class="number">'.$esc_producidas[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="magentaBox">';
				$cadena_tabla.='<h4>Escenas no<br> producidas </h4>';
				$cadena_tabla.='<div class="number">'.$esc_porproducir[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="clr"></div>';
				$cadena_tabla.='<div class="column twelve">';
				$cadena_tabla.='<div class="grayBox">'.$total_escenas[0]->total.' escenas creadas</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';

				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div id="loadElements'.$locacion['id'].'" class="loadElements" style="height: 301px;width: 99%;">';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="barras_estadisticas">';
				$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion);
				$cadena_tabla.='<div id="capitulos_chart'.$locacion['id'].'" style="min-width: 560px; height: 250px;"></div>';
				$cadena_tabla.='<label>Total de Libretos: '.count($total_capitulos).'</label>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				if($usuario_permisos=="write" and $produccion['0']->estado!=2) {
					$cadena_tabla.='<div class="columns twelve">';
					$cadena_tabla.='<label class="editar_elemento button" id="'.$locacion['id'] .'">Editar elemento</label>';
					if($uso==0){
						$cadena_tabla.='<input type="button" value="Eliminar" id="'.$locacion['id'] .'" class="button secondary eliminar_locacion"> ';  
					}
					$cadena_tabla.='</div>';
					$cadena_tabla.='<div class="clr"></div>';
					$cadena_tabla.='<div style="display:none" class="form_elemento_'.$locacion['id'].'">';
					$cadena_tabla.='<br>';
					$cadena_tabla.='<div class="row">';
					$cadena_tabla.='<div class="column six">';
					$cadena_tabla.='<label>Nombre elemento</label>';
					$cadena_tabla.='<label class="error">'.form_error('nombre').'</label>';
					$cadena_tabla.='<input type="hidden" name="id_locacion" class="id_locacion" value="'.$locacion['id'].'" >';
					$cadena_tabla.='<input type="text" name="nombre" class="nombre" value="'.$locacion['nombre'].'">';
					$cadena_tabla.='<input type="hidden" value="'.$id_produccion .'" class="id_produccion" name="id_produccion"> ';
					$cadena_tabla.='<input type="submit" value="Actualizar Elemento" data-nombre="'.$locacion['nombre'].'" class="button actualizar_locacion">';
					$cadena_tabla.='<input type="button" value="Cancelar" id="'.$locacion['id'] .'" class="button secondary cancel_edit_element">';
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
		            $cadena_tabla.=form_close();
		            $cadena_tabla.='</div>';
		            $cadena_tabla.='</div>';
		        }
		        $cadena_tabla.='</div>';    
		        $cadena_tabla.='</td>';
		        $cadena_tabla.='</tr>';
		    }
		}
		return $cadena_tabla;
	}


	public function carga_tabla_ajax_sets(){
		$id_produccion = $_POST['id_produccion'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$id_categoria = $_POST['id_categoria'];
		$tamano_inicial = $_POST['tamano_inicial'];
		$tamano_final = $_POST['tamano_final'];
		$capitulos = $this->model_capitulos->capitulos_produccion_2($id_produccion);

		if($desde!="" AND $hasta!=""){
        	$sets=$this->model_elementos->sets_produccion_limite($id_produccion,$desde,$hasta);
    	}else{
    		$sets=$this->model_elementos->sets_produccion($id_produccion);
    	}
    	$cadena_tabla=$this->construct_table_sets($id_produccion,$sets,$tamano_inicial,$tamano_final);
		echo json_encode($cadena_tabla);
	}

	public function construct_table_sets($id_produccion,$sets,$tamano_inicial,$tamano_final){
		$usuario_permisos = $this->permisos_usuarios($id_produccion);
		$total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
         $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
		$cadena_tabla="";
		$count=0;

		if($sets){ 
			for ($k=$tamano_inicial+1; $k <= $tamano_final; $k++) { 
                if ($count%2 == 0) {
                  $classRow = "white";
                }else{
                  $classRow = "gray_light";
                }
                $count++;
                $esc_producidas=$this->model_elementos->escenas_producidas_id_sets($sets[$k]['id']);
                $esc_porproducir=$this->model_elementos->escenas_porproducidas_idsets($sets[$k]['id']);

                if($esc_producidas[0]->total==0){
                	$por=0;
                }else{
                	$por=$esc_producidas[0]->total;
                	$por=round($por); 
                } 

                if($esc_porproducir[0]->total==0){
                	$por2=0;
                }else{
                	$por2=$esc_porproducir[0]->total;
                	$por2=round($por2);  
                }
                $pot_total=$por2;
                $cadena_tabla.='<tr class="actionAsing ui-selectee element_'.$sets[$k]['id'].' diagrama" data-tr="'.$sets[$k]['id'].'" data-producidas="'.$por.'" data-noproducidas="'.$pot_total.'" data-noasignadas="'.abs($total_escenas[0]->total-$por-$por2).'" data-idcahrt="'.$sets[$k]['nombre'].'" data-idproduccion="'.$id_produccion.'" data-idelemento="'.$sets[$k]['id'].'" data-tipo="3">';
              	$cadena_tabla.='<td class="element_name">'.$sets[$k]['nombre'].'</td>';
              	$cadena_tabla.='<td class="element_name">'.$sets[$k]['nombre_locacion'].'</td>';
                if(isset($sets[$k]['descripción'])){
	              	$cadena_tabla.='<td'. $hide.'></td>';
	              	$cadena_tabla.='<td></td>';
                }
                $uso=$sets[$k]['uso'];
              	$cadena_tabla.='<td style="text-align:center">'.$uso.'</td>';
                if($uso==0){
					$por=0;
				}else{
					$por=($uso*100)/$total_escenas[0]->total;
					$por=round($por);  
				}

	            $cadena_tabla.='<td style="text-align:center">'.$por.'%</td>';
	            $cadena_tabla.='<td style="text-align:center">'.$esc_producidas[0]->total.'</td>'; 
                $cadena_tabla.='<td style="text-align:center">'.$esc_porproducir[0]->total.'</td>';
                $cadena_tabla.='</tr>';

                $cadena_tabla.='<tr class="info_element element_'.$sets[$k]['id'].'" data-tr="'.$sets[$k]['id'].'" >';
                $cadena_tabla.='<td colspan="7">';
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div class="torta_porcentaje">';          
                           
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column seven">';
				$cadena_tabla.='<div id="chart_div'.$sets[$k]['nombre'].'"></div>';
				$cadena_tabla.=' <div class="convencionesPieChart">';
				$cadena_tabla.='<ul>';
				$cadena_tabla.='<li> <span class="yellow"></span> Escenas producidas</li>';
				$cadena_tabla.='<li> <span class="pink"></span> Escenas no producidas</li>';
				$cadena_tabla.='<li> <span class="gray"></span> Escenas no asignadas</li>';
				$cadena_tabla.='</ul>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column five">';
				$cadena_tabla.='<div class="row">';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="yellowBox">';
				$cadena_tabla.='<h4>Escenas <br> producidas </h4>';
				$cadena_tabla.='<div class="number">'.$esc_producidas[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="column six">';
				$cadena_tabla.='<div class="magentaBox">';
				$cadena_tabla.='<h4>Escenas no<br> producidas </h4>';
				$cadena_tabla.='<div class="number">'.$esc_porproducir[0]->total.'</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="clr"></div>';
				$cadena_tabla.='<div class="column twelve">';
				$cadena_tabla.='<div class="grayBox">'.$total_escenas[0]->total.' escenas creadas</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';
                            

				$cadena_tabla.='<div class="columns six">';
				$cadena_tabla.='<div id="loadElements'.$sets[$k]['id'].'" class="loadElements" style="height: 301px;width: 99%;">';
				$cadena_tabla.='</div>';
				$cadena_tabla.='<div class="barras_estadisticas">';
				$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion);
				$cadena_tabla.='<div id="capitulos_chart'. $sets[$k]['id'].'" style="min-width: 560px; height: 250px;"></div>';
				$cadena_tabla.='<label>Total de Libretos: '.count($total_capitulos).'</label>';

				$cadena_tabla.='</div>';
				$cadena_tabla.='</div>';

                if($usuario_permisos=="write" and $produccion['0']->estado!=2) {
					$cadena_tabla.='<div class="columns twelve">';
					$cadena_tabla.='<label class="editar_elemento button" id="'. $sets[$k]['id'] .'">Editar elemento</label>';
					if($uso==0){
						$cadena_tabla.='<input type="button" value="Eliminar" id="'. $sets[$k]['id'] .'" class="button secondary eliminar_sets">'; 
					}
					$cadena_tabla.='</div>';
					$cadena_tabla.='<div class="clr"></div>';
					$cadena_tabla.='<div style="display:none" class="form_elemento_'. $sets[$k]['id'].'">';
					$cadena_tabla.='<br>';
					$cadena_tabla.='<div class="row">';
					$cadena_tabla.='<div class="column six">';
					$cadena_tabla.='<label>Nombre elemento</label>';
					$cadena_tabla.='<label class="error">'. form_error('nombre').'</label>  '; 
					$cadena_tabla.='<input type="hidden" name="id_elemento" class="id_set"  value="'. $sets[$k]['id'].'" >';
					$cadena_tabla.='<input type="text" name="nombre" class="nombre" value="'. $sets[$k]['nombre'].'">';
					//$cadena_tabla.='<input type="hidden" value="'. $id_locacion .'" class="id_locacion" name="id_locacion">';
					$cadena_tabla.='<input type="submit" value="Actualizar Elemento" data-nombre="'.$sets[$k]['nombre'].'" class="button actualizar_set">';
					$cadena_tabla.='<input type="button" value="Cancelar" id="'. $sets[$k]['id'] .'" class="button secondary cancel_edit_element">';
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
					$cadena_tabla.='</div>';
				}
				$cadena_tabla.='</div>';
				$cadena_tabla.='</div> ';   
				$cadena_tabla.='</td>';
				$cadena_tabla.='</tr>';
            }
        }
        return $cadena_tabla;
	}

	public function categorias_elementos_default($id_produccion){
		$data[0]=array(
			 'tipo'=>'Personaje',
			 'id_produccion'=>$id_produccion,
	    );
	    $data[1]=array(
	    	'tipo'=>'Utileria',
			 'id_produccion'=>$id_produccion,
		);
		$data[2]=array(
	    	'tipo'=>'Maquillaje',
			 'id_produccion'=>$id_produccion,
		);
		$data[3]=array(
	    	'tipo'=>'Vestuario',
			 'id_produccion'=>$id_produccion,
		);
		$data[4]=array(
	    	'tipo'=>'Vehiculo',
			 'id_produccion'=>$id_produccion,
		);
		$data[5]=array(
	    	 'tipo'=>'Efectos Especiales',
			 'id_produccion'=>$id_produccion,
		);
		$data[6]=array(
	    	'tipo'=>'Seguridad ambiental',
			 'id_produccion'=>$id_produccion,
		);
		$data[7]=array(
	    	'tipo'=>'Producción',
			 'id_produccion'=>$id_produccion,
		);
		$data[8]=array(
	    	'tipo'=>'Vehículos background',
			 'id_produccion'=>$id_produccion,
		);
		$cont=0;
		while($cont<=8){
			$this->model_elementos->categorias_elementos_default($data[$cont]);
			$cont++;
		}
      return true;
	}

	public function crear_categoria(){
		$this->form_validation->set_rules('categoria_nombre_new','Nombre categoria','required');
      	$this->form_validation->set_message('required','%s es requerido');
      	$id_produccion=$this->input->post('id_produccion');
		if ($this->form_validation->run()==FALSE) {
			$this->index($id_produccion);
		}else{
			$data=array(
				'tipo'=>$this->input->post('categoria_nombre_new'),
				'id_produccion'=>$id_produccion,
				'descripcion'=>$this->input->post('descripcion_new'),
			);
         $update_elemento=$this->model_elementos->insert_elemento($data);
         redirect($this->lang->lang().'/elementos/index/'.$id_produccion);
		}
	}

	public function update_categoria(){
		$this->form_validation->set_rules('categoria_nombre','Nombre categoria','required');
      	$this->form_validation->set_message('required','%s es requerido');
      	$id_produccion=$this->input->post('id_produccion');
      	$id_categoria=$this->input->post('id_categoria');
		if ($this->form_validation->run()==FALSE) {
			$this->index($id_produccion);
		}else{
			$data=array(
				'id_categoria'=>$id_categoria,
				'tipo'=>$this->input->post('categoria_nombre'),
				'id_produccion'=>$id_produccion,
				'descripcion'=>$this->input->post('descripcion'),
			);
         $update_elemento=$this->model_elementos->update_elemento($data);
         redirect($this->lang->lang().'/elementos/index/'.$id_produccion);
		}
	}

	public function eliminar_categoria(){
		$id_categoria=$_POST['id_categoria'];
		$categoria_elemento=$this->model_elementos->categoria_elemento_id($id_categoria);
		if($categoria_elemento){
			$existe=0;
           foreach ($categoria_elemento as $c) {
           	   $escena_has_elemento=$this->model_elementos->escena_has_elemento($c['id_elemento']);
           	   if($escena_has_elemento){
           	   	 $existe=1;
           	   }
           }
           if($existe==1){
				$data['eliminar']=0;
				echo json_encode($data);
			}else{
				$eliminar=$this->model_elementos->eliminar_categoria($id_categoria);
				$data['eliminar']=1;
				echo json_encode($data);
			}
		}else{
	        $eliminar=$this->model_elementos->eliminar_categoria($id_categoria);
			$data['eliminar']=1;
			echo json_encode($data);
		}
		
	}

	public function validacionElementos(){
      if($this->input->post('nombre')!=""){
        $validacion=$this->model_elementos->validar_elemento($this->input->post('nombre'),$this->input->post('categoria'));
        if($validacion){
          return false;
        }else{
          return true;
        }
      }
    }

	public function crear_elemento(){
		$this->form_validation->set_rules('nombre','Nombre Elemento','callback_validacionElementos');
      	$this->form_validation->set_message('required','%s es requerido');
      	$this->form_validation->set_message('validacionElementos','Este elemento ya existe en esta categoria');
      	$id_produccion=$this->input->post('id_produccion');
      	$id_categoria=$this->input->post('categoria');
		if ($this->form_validation->run()==FALSE) {
			$this->index($id_produccion);
		}else{
			$categoria=$this->model_elementos->categoria_elemento($id_categoria);
			if($categoria['0']->tipo=="Personaje"){
				$rol=$this->input->post('rol');
			}else{
				$rol=null;
			}
			$data=array(
				'id_categoria'=>$id_categoria,
				'nombre'=>$this->input->post('nombre'),
				'descripcion'=>$this->input->post('descripcion'),
				'rol'=>$rol,
			);
        $insert_elemento=$this->model_elementos->elemento($data);
        if($this->input->post('categoria_actual')){
        	redirect($this->lang->lang().'/elementos/index/'.$id_produccion.'/'.$this->input->post('categoria_actual'));
        }else{
        	redirect($this->lang->lang().'/elementos/index/'.$id_produccion);
        }
        
		}
	}

	public static function corta_palabra($palabra,$num)	{
	$largo=strlen($palabra);//indicarme el largo de una cadena
	$cadena=substr($palabra,0,$num);
	return $cadena;
	}  

	public function buscar_categoria(){
		$id_categoria=$this->input->post('id_categoria');
		$id_produccion=$this->input->post('id_produccion');
		$desde=$this->input->post('capitulos_herramientas_from');
		$hasta=$this->input->post('capitulos_herramientas_to');
		$roles=$this->input->post('roles_personajes');
		$rol='';
		if($roles){
           foreach ($roles as $r) {
           	 $rol.=$r.'-';
           }
		}else{
			$roles='';
		}

		redirect($this->lang->lang().'/elementos/index/'.$id_produccion.'/'.$id_categoria.'/'.'null/'.$desde.'/'.$hasta.'/'.$rol);	
	}

	public function buscar_sets(){
		$id_categoria='sets';
		$id_locacion=$this->input->post('locacion');
		$id_produccion=$this->input->post('id_produccion');
		$desde=$this->input->post('capitulos_herramientas_from');
		$hasta=$this->input->post('capitulos_herramientas_to');
		redirect($this->lang->lang().'/elementos/index/'.$id_produccion.'/'.$id_categoria.'/'.$id_locacion.'/'.$desde.'/'.$hasta);	
	}

	public function editar_elemento(){
		$this->form_validation->set_rules('nombre','Nombre Elemento','required');
      	$this->form_validation->set_message('required','%s es requerido');
      	$id_elemento=$this->input->post('id_elemento');
      	$id_produccion=$this->input->post('id_produccion');
      	$id_categoria=$this->input->post('categoria');
      	$id_locacion=$this->input->post('id_locacion');
      	$desde=$this->input->post('desde');
      	$hasta=$this->input->post('hasta');
      	$roles=$this->input->post('roles');

		if ($this->form_validation->run()==FALSE) {
			$this->index($id_produccion);
		}else{
			$categoria=$this->model_elementos->categoria_elemento($id_categoria);
			if($categoria['0']->tipo=="Personaje"){
				$rol=$this->input->post('rol');
				$actor_nombre=$this->input->post('actor_nombre');
				$actor_apellido=$this->input->post('actor_apellido');
				$monto=str_replace(',','',$this->input->post('monto'));
				$contrato=$this->input->post('cotrato');
				$documento_actor=$this->input->post('documento');
				$tipo_documento=$this->input->post('tipo_documento');
				$tipo_moneda=$this->input->post('tipo_moneda');

				if($this->input->post('fecha_inicio')!=""){
					$fecha_inicio = date("d-M-Y",strtotime($this->input->post('fecha_inicio')));
				}else{
					$fecha_inicio=NULL;
				}

				if($this->input->post('fecha_finalizacion')){
					$fecha_finalizacion= date("d-M-Y",strtotime($this->input->post('fecha_finalizacion')));
				}else{
					$fecha_finalizacion=NULL;
				}

				if($this->input->post('fecha_liquidacion')){
					$fecha_liquidacion= date("d-M-Y",strtotime($this->input->post('fecha_liquidacion')));
				}else{
					$fecha_liquidacion=NULL;
				}

			}else{
				$rol=NULL;
				$actor_nombre=NULL;
				$actor_apellido=NULL;
				$contrato=NULL;
				$fecha_finalizacion=NULL;
				$fecha_inicio=NULL;
				$tipo_documento=NULL;
				$documento_actor=NULL;
				$monto=NULL;
			}
			if($contrato==""){
				$contrato=NULL;
			}
			$data=array(
				'id_elemento'=>$id_elemento,
				'id_categoria'=>$id_categoria,
				'nombre'=>$this->input->post('nombre'),
				'descripcion'=>$this->input->post('descripcion'),
				'rol'=>$rol,
				'actor_nombre'=>$actor_nombre,
				'actor_apellido'=>$actor_apellido,
				'documento_actor'=>$documento_actor,
				'monto'=>$monto,
				'id_tipo_contrato'=>$contrato,
				'id_tipo_documento'=>$tipo_documento,
				'id_tipo_moneda'=>$tipo_moneda,
				'fecha_inicio'=>$fecha_inicio,
				'fecha_finalizacion'=>$fecha_finalizacion,
				'fecha_liquidacion'=>$fecha_liquidacion
			);
         $insert_elemento=$this->model_elementos->elemento_Upate($data);
         if($rol!=4){
         	$escena_elemento=$this->model_elementos->escenas_has_elementos_id_elemento($id_elemento);
         	if($escena_elemento){
         		foreach ($escena_elemento as $e) {
         			$this->model_elementos->eliminar_extras_escena($e['id']);
         		}
         		
         	}
         }

         redirect($this->lang->lang().'/elementos/index/'.$id_produccion.'/'.$id_categoria.'/'.$id_locacion.'/'.$desde.'/'.$hasta.'/'.$roles);
		}
	}


	public function eliminar_locacion($idproduccion,$idlocacion){
		$this->model_elementos->eliminar_locacion($idlocacion);
		redirect($this->lang->lang().'/elementos/index/'.$idproduccion);
	}

	public function eliminar_set($idproduccion,$idset){
		$this->model_elementos->eliminar_set($idset);
		redirect($this->lang->lang().'/elementos/index/'.$idproduccion);
	}

	public function eliminar_elemento(){
		$idelemento = $_POST['idelemento'];
		$this->model_elementos->eliminar_elemento($idelemento);
		$data['eliminar']=1;
	    echo json_encode($data);

	}

	public function grafica_tabla(){
		$idproduccion=$_POST['idproduccion'];
		$idelemento=$_POST['idelemento'];
		$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($idproduccion);
		$escenasCapitulo[0]=0;
        $escenasAsignadas[0]=0;
		$cont=1;
		$cont_lip=0;
		while($cont_lip<=count($total_capitulos)-1) { 
          $escenas_capitulo=$this->model_elementos->total_escenas_capitulo($total_capitulos[$cont_lip]['id']); 

          $escenas_elementos=$this->model_elementos->escenas_elementos($total_capitulos[$cont_lip]['id'],$idelemento);
          $i=$escenas_elementos;
          $i2=$escenas_capitulo;
          if (!$i) {
            $i=0;
          }
          if (!$i2) {
            $i2=0;
          };
          $escenasCapitulo[$cont]=$i;
          $escenasAsignadas[$cont]=$i2;
          $cont++;
          $cont_lip++; 
        }
        $data['escenasCapitulo']=$escenasCapitulo;
        $data['escenasAsignadas']=$escenasAsignadas;
        $data['totalcapitulos']=count($total_capitulos);
        echo json_encode($data);

	}

	public function grafica_tabla_locacion(){
		$idproduccion=$_POST['idproduccion'];
		$idlocacion=$_POST['idlocacion'];
		$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($idproduccion);
		$escenasCapitulo[0]=0;
        $escenasAsignadas[0]=0;
		$cont=1;
		$cont_lip=0;
		while($cont<=count($total_capitulos)-1) { 
          $escenas_capitulo=$this->model_elementos->total_escenas_capitulo($total_capitulos[$cont_lip]['id']); 
          $escenas_elementos=$this->model_elementos->locacion_escena_id($total_capitulos[$cont_lip]['id'],$idlocacion);
          $i=$escenas_elementos;
          $i2=$escenas_capitulo;
          if (!$i) {
            $i=0;
          }
          if (!$i2) {
            $i2=0;
          };
          $escenasCapitulo[$cont]=$i;
          $escenasAsignadas[$cont]=$i2;
          $cont++; 
          $cont_lip++;
        }
        $data['escenasCapitulo']=$escenasCapitulo;
        $data['escenasAsignadas']=$escenasAsignadas;
        $data['totalcapitulos']=count($total_capitulos);
        echo json_encode($data);

	}	

   	public function grafica_tabla_sets(){
		$idproduccion=$_POST['idproduccion'];
		$idset=$_POST['idset'];
		$total_capitulos=$this->model_elementos->total_capitulos_idProduccion($idproduccion);
		$escenasCapitulo[0]=0;
        $escenasAsignadas[0]=0;
		$cont=1;
		$cont_lip=0;
		while($cont<=count($total_capitulos)-1) { 
          $escenas_capitulo=$this->model_elementos->total_escenas_capitulo($total_capitulos[$cont_lip]['id']); 
          $escenas_elementos=$this->model_elementos->locacion_escena($total_capitulos[$cont_lip]['id'],$idset);
          $i=$escenas_elementos;
          $i2=$escenas_capitulo;
          if (!$i) {
            $i=0;
          }
          if (!$i2) {
            $i2=0;
          };
          $escenasCapitulo[$cont]=$i;
          $escenasAsignadas[$cont]=$i2;
          $cont++;
          $cont_lip++;
        }
        $data['escenasCapitulo']=$escenasCapitulo;
        $data['escenasAsignadas']=$escenasAsignadas;
        $data['totalcapitulos']=count($total_capitulos);
        echo json_encode($data);

	}	

	  public function editar_locacion2(){
		$id_produccion=$_POST['id_produccion'];
		$nombre=$_POST['nombre'];
		$id_locacion=$_POST['id_locacion'];
		$locacion=$this->model_elementos->validar_locacion($nombre,$id_produccion);
		if($locacion){
          $data['existe']=1;   
		}else{
			$data['existe']=0; 
			$crear_locacion=$this->model_escenas->update_locacion($id_locacion,$nombre,$id_produccion); 
		}
        echo json_encode($data);

	}	

  public function editar_set(){
		$id_locacion=$_POST['id_locacion'];
		$nombre=$_POST['nombre'];
		$id_set=$_POST['id_set'];
		$set=$this->model_elementos->validar_set($nombre,$id_locacion);
		if($set){
          	$data['existe']=1;   
		}else{
			$data['existe']=0; 
			$crear_locacion=$this->model_escenas->update_set($id_set,$nombre); 
		}
        echo json_encode($data);

	}		

	


	public function eliminar_locacion_ajax(){
		$id_locacion = $_POST['id_locacion'];
		$this->model_elementos->eliminar_elemento_locacion($id_locacion);
		$data['eliminar']=1;
	    echo json_encode($data);

	}

	public function eliminar_sets_ajax(){
		$id_set = $_POST['id_set'];
		$this->model_elementos->eliminar_elemento_set($id_set);
		$data['eliminar']=1;
	    echo json_encode($data);

	}

	public function validacionElementosAjax(){
		$nombre = $_POST['nombre'];
		$categoria = $_POST['categoria'];
      if($nombre!=""){
        $validacion=$this->model_elementos->validar_elemento($nombre,$categoria);
        if($validacion){
          $data['respuesta'] = false;
        }else{
          $data['respuesta'] = true;
        }
      }
      echo json_encode($data);
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
            if($usuario_rol['id_rol']==2 OR $usuario_rol['id_rol']==8 OR $usuario_rol['id_rol']==13 OR $usuario_rol['id_rol']==15){
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

    public function contar_elementos_roles(){
    	$cadena = $_POST['cadena'];
    	$id_produccion = $_POST['id_produccion'];
    	$desde = $_POST['desde'];
    	$hasta = $_POST['hasta'];
    	$sql ="( e.rol IS NOT NULL ";
    	if($cadena!=""){
    		$temporal = explode(',', $cadena);
    		for ($i=0; $i < count($temporal)-1; $i++) { 
    			$sql.=" AND r.rol != '".$temporal[$i]."'";
    		}
    	}
    	$sql .=" ) ";
    	$cantidad = $this->model_elementos->contar_elementos_roles($id_produccion,$sql,$desde,$hasta);	
    	$data['cantidad'] = $cantidad[0]->cantidad;
    	echo json_encode($data);
    }

	

	



}