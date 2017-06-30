<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_produccion extends CI_Controller {

    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_plan_produccion');
	    $this->load->model('model_produccion');
	    $this->load->model('model_capitulos');
	    $this->load->model('model_escenas_2');
	    $this->load->model('model_admin');
	    $this->load->model('model_post_produccion');
	    $this->load->model('model_dashboard');
	    $this->_logeo_in();
	}

	function _logeo_in(){
		$login_in = $this->session->userdata('login_pruduction_suite');
		if ($login_in !=true){
		  
		    redirect ($this->lang->lang().'/login/disconnecUser');
		}
   	}
	
	public function index($id,$msg=''){
		$tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
		if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='8'){
			$id_user=$this->session->userdata('id_pruduction_suite');
			$user=$this->model_admin->rolUserId($id_user);
            $tipo_rol=$user['0']['id_rol_otros'];
            $continuar=0;
            if($user){
	        	foreach ($user as $u) {
	        		if($u['id_rol_otros']==2 or $u['id_rol_otros']==6){
	        			$continuar=1;
	        			break;
	        		}
	        	}
	        }	
            if($continuar==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){
				if($msg==1){
					$msg='<div class="alert-box success">
				         Produccion Actualizada<a href="" class="close">&times;</a>
				    </div>';
				}elseif($msg==2){
					$msg='<div class="alert-box alert">
				        No se puede realizar el cambios ya que la(s) unida(des) que se van a a eliminar ya estan asignadas a una plan diario<a href="" class="close">&times;</a>
				    </div>';;
				}else{
					$msg='';
				}
				$produccion=$this->model_plan_produccion->produccion_id($id);
				$centro_produccion=$this->model_produccion->centro_produccion();
				$tipo_produccion=$this->model_produccion->tipo_produccion();
				$user=$this->model_produccion->tipo_usuario();
				$ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
				$productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
				$productor=$this->model_produccion->user_id($produccion['0']->id_productor);
				$director_unidad=$this->model_produccion->get_usuarios(7,$id);
				$script=$this->model_produccion->get_usuarios(1,$id);
				$unidades=$this->model_plan_produccion->unidades_id_produccion($id);
				$roles=$this->model_plan_produccion->rol_otros();
				$user_has_produccion=$this->model_plan_produccion->user_has_roles_id(2);
				$usuarios_producion=$this->model_plan_produccion->usuarios_produccion($produccion['0']->id_produccion);
				$semanas_trabajo=$this->model_plan_produccion->semanas_trabajo($produccion['0']->id_produccion);
				$semanas=$this->model_plan_produccion->total_semanas($id);
				$cantidad_capitulos = $this->model_capitulos->contar_capitulos_escritos($produccion['0']->id_produccion);
				$minutos_producidos = $this->sumar_minutos($produccion['0']->id_produccion);
				//$escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($produccion['0']->id_produccion);
				$usuario_permisos = $this->permisos_usuarios($id);
				$unidad_inicio=$this->model_plan_produccion->unidad_inicio($id);
				$data['usuario_permisos'] = $usuario_permisos;
				$data['produccion']=$produccion;
				$data['centro_produccion']=$centro_produccion;
				$data['tipo_produccion']=$tipo_produccion;	
				$data['user']=$user;
				$data['msg']=$msg;
				$data['director_unidad']=$director_unidad;
				$data['script']=$script;
				$data['productor_general']=$productor_general;
				$data['productor']=$productor;
				$data['ejecutivo']=$ejecutivo;
				$data['unidad']=$unidades;	
				$data['roles']=$roles;	
				$data['semanas_trabajo']=$semanas_trabajo;
				$data['usuarios_producion']=$usuarios_producion;	
				$data['user_has_produccion']=$user_has_produccion;		
				$data['semanas']=$semanas[0]->total;		
				$data['view']='produccion/plan_produccion';
				$data['capitulos'] = $cantidad_capitulos;
				$data['minutos_producidos'] = $minutos_producidos;
				//$data['escenas_producidas'] = $escenas_producidas;
				$data['unidad_inicio'] = $unidad_inicio;
				$this->load->view('includes/template',$data);
			}else{				
				if($tipo_rol==1 OR $tipo_rol==7){
					redirect ($this->lang->lang().'/plan_diario/index/'.$id);
				}else{
					if($tipo_rol==15){
						redirect ($this->lang->lang().'/elementos/index/'.$id);
					}else if ($tipo_rol==9 OR $tipo_rol==10 OR $tipo_rol==11 OR $tipo_rol==12){
						redirect ($this->lang->lang().'/libretos/index/'.$id);
					}else{
						redirect ($this->lang->lang().'/produccion/producciones');
					}
				}
			}
				
					
		}else{
			if ($tipo_usuario=='7' OR $tipo_usuario =='8') {
				redirect ($this->lang->lang().'/casting/solicitudes');
			}else{
				redirect ($this->lang->lang().'/produccion/producciones');
			}
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
		            if($usuario_rol['id_rol']==2 OR $usuario_rol['id_rol']==8){
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

	public function sumar_minutos($idproduccion){
	  	$acumulado = $this->model_escenas_2->sumar_tiempos_produccion($idproduccion);
      	$segundos = 0;
      	$minutos = 0;
      	$horas = 0;
      	$segundos = $acumulado[0]->segundos;

      	while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      	}

      	$minutos2 = $acumulado[0]->minutos + $minutos;
      	if($minutos2==0 and $segundos==0){
        	$tiempo= 0;
  	  	}else{
	  	  	if($minutos2>=0 and $minutos2<10){
	  	  		$minutos2='0'.$minutos2;
	  	  	}
	  	  	if($segundos>=0 and $segundos<10){
	  	  		$segundos='0'.$segundos;
	  	  	}
	  	  	$tiempo= $minutos2.":".$segundos;
  	  	}
	  	return $tiempo;
	}


	public function fechaFormat($fecha){
		$meses = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$meses_es = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
            $fecha=strtolower($fecha);
	    $cont=0;
		 $f=str_replace('ene','Jan',$fecha); 
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

   
    public function editar_produccion(){
    	
    	
    	
   		$dias_grabacion =  $this->input->post('id_dias_grabacion');
   	    $this->form_validation->set_rules('name_production','Nombre produccion','required');
		$this->form_validation->set_rules('centro_produccion','Centro Produccion','required');
	    $this->form_validation->set_rules('type_production','Tipo de Produccion','required');
	    if($_FILES["image_production"]["tmp_name"]){
	      $this->form_validation->set_rules('image_production','Imagen','callback__validacion_img');
	      $this->form_validation->set_message('_validacion_img','El formato de Archivo no esta permitido');
	    } 
		$this->form_validation->set_message('required','%s es requerido');
		if ($this->form_validation->run()==FALSE) {
			$this->index($this->input->post('id_produccion'));
		}else{
			$idproduccion = $this->input->post('id_produccion');
			$produccion = $this->model_plan_produccion->produccion_id($idproduccion);
	        $actual_days = $produccion[0]->dias_grabacion;
			$data_dias=array(
                'id_dias_grabacion'=>$this->input->post('dias_grabacion'),
                'dias_grabacion'=>$this->input->post('id_dias_grabacion'),
				'lunes'=> $this->input->post('lunes'),
				'martes'=> $this->input->post('martes'),
				'miercoles'=> $this->input->post('miercoles'),
				'jueves'=> $this->input->post('jueves'),
			    'viernes'=> $this->input->post('viernes'),
				'sabado'=> $this->input->post('sabado'),
				'domingo'=> $this->input->post('domingo')
	        );
			$dias_semana=$this->model_plan_produccion->update_dias($data_dias);
			
			
			if($_FILES["image_production"]["tmp_name"]){
				$rutaServidor="images/produccion";
				$rutaTemporal= $_FILES["image_production"]["tmp_name"];
				$nombreimage= $_FILES["image_production"]["name"];
				$nombre=preg_replace('/ /','_', $nombreimage);
				$rutaDestino= $rutaServidor.'/'.$nombre;
				move_uploaded_file($rutaTemporal, $rutaDestino) ;
			}else{
				$rutaDestino=null;
			}
					
			$produccion = $this->model_plan_produccion->produccion_id($idproduccion);
            if($this->input->post('inicio_PreProduccion')){
            	//$inicio_PreProduccion= date("Y-m-d",strtotime($this->input->post('inicio_PreProduccion')));
            	$inicio_PreProduccion= $this->fechaFormat($this->input->post('inicio_PreProduccion'));
            }else{
            	$inicio_PreProduccion=null;
            }
            if($this->input->post('fin_grabacion')){
            	//$fin_grabacion= date("Y-m-d",strtotime($this->input->post('fin_grabacion')));
            	$fin_grabacion= $this->fechaFormat($this->input->post('fin_grabacion'));
            }else{
            	$fin_grabacion=null;
            }
            
            if($produccion[0]->inicio_PreProduccion != $inicio_PreProduccion or $produccion[0]->fin_grabacion != $fin_grabacion ){
            	$validation = true;
            }else{
            	$validation = false;
            } 

            $this->model_plan_produccion->update_values_semana('cap_esce_semana', 'capitulos_programados', $this->input->post('id_produccion'), $this->input->post('write_cap'));
            $this->model_plan_produccion->update_values_semana('min_proy_semana', 'minutos_proyectados', $this->input->post('id_produccion'), $this->input->post('min_proy_sem'));
             $this->model_plan_produccion->update_values_semana('seg_proy_semana', 'segundos_proyectados', $this->input->post('id_produccion'), $this->input->post('seg_proy_sem'));
	           

            if($this->input->post('fecha_aire')=="" OR $this->input->post('fecha_aire')=="-"){
            	$fecha_a = '0000-00-00';
            }else{
            	//$fecha_a = date("Y-m-d",strtotime($this->input->post('fecha_aire')));
            	$fecha_a= $this->fechaFormat($this->input->post('fecha_aire'));
            }

            if($this->input->post('inicio_grabacion')=="" OR $this->input->post('inicio_grabacion')=="-"){
            	$fecha_i = '0000-00-00';
            }else{
            	//$fecha_i = date("Y-m-d",strtotime($this->input->post('inicio_grabacion')));
            	$fecha_i= $this->fechaFormat($this->input->post('inicio_grabacion'));
            }
        	$data_produccion=array(
                'id'=>$this->input->post('id_produccion'),
                'nombre_produccion'=>$this->input->post('name_production'),
                'id_centroProduccion'=>$this->input->post('centro_produccion'),
                'id_tipoProduccion'=>$this->input->post('type_production'),
                //'inicio_PreProduccion'=> date("Y-m-d",strtotime($this->input->post('inicio_PreProduccion'))),
                'inicio_PreProduccion'=> $this->fechaFormat($this->input->post('inicio_PreProduccion')),
                'inicio_grabacion'=>$fecha_i,
                'fecha_aire'=>$fecha_a,
                //'fin_grabacion'=>date("Y-m-d",strtotime($this->input->post('fin_grabacion'))),
                'fin_grabacion'=> $this->fechaFormat($this->input->post('fin_grabacion')),
                'numero_capitulo'=>$this->input->post('number_cap'),
                'minuto_capitulo'=>$this->input->post('mins_cap'),
                'segundos_capitulo'=>$this->input->post('segundos_capitulo'),
                'cap_ese_semana'=>$this->input->post('write_cap'),
                'min_proy_seman'=>$this->input->post('min_proy_sem'),
                'seg_proy_seman'=>$this->input->post('seg_proy_sem'),
                'over_time'=>$this->input->post('over_time'),
                'capitulos_proyectados'=>$this->input->post('capitulos_proy'),
                'imagen_produccion'=>$rutaDestino
            );
            

            $produccion=$this->model_plan_produccion->update_produccion($data_produccion);
	        if($produccion){
	        	if($validation){
	                $dias=((strtotime($fin_grabacion)-strtotime($inicio_PreProduccion))/86400);
	                $fecha= $fin_grabacion; 
	                $i = strtotime($fecha); 
	                $dia_semana_final=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
	                if($dia_semana_final<=0){
	               		$dia_semana_final=0;
	                }else{
	               		$dia_semana_final=7-$dia_semana_final;
	                }
	                $fecha= $inicio_PreProduccion; 
	                $i = strtotime($fecha); 
	                $dia_semana=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
	                if($dia_semana<0){
	               		$dia_semana=0;
	                }
	                $dias=$dias+$dia_semana+$dia_semana_final;
		            $semanas= $dias/7;
		            $semanas= ceil($semanas);
	                $i=1;
	                $cont=0;

	                $libretos_prgramados = $this->input->post('number_cap');

	                $semanas_actuales = $this->model_plan_produccion->semanas_trabajo($this->input->post('id_produccion'));
	                $k=0;
	                while($i<=$semanas){
	                   	if($cont==0){
							$fecha = strtotime($this->input->post('inicio_PreProduccion'));
							$ano= date("Y", $fecha); // Year (2003)
							$mes= date("m", $fecha); // Month (12)
							$dia= date("d", $fecha); // day (14)
							$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
							$dia=7-$dia;
		                   	  	
		                    $fecha_inicio_semana=$this->input->post('inicio_PreProduccion');
		                    $fecha = $fecha_inicio_semana;
		                    $nuevafecha = strtotime ( '+'.$dia.' day' , strtotime ( $fecha ) ) ;
		                    $fecha_semana_final = date ( 'Y-m-j' , $nuevafecha );
		                    $fecha_inicio_semana=$inicio_PreProduccion;
		                    $cont++;
	                	}else{
		                    $fecha = $fecha_semana_final;
		                    $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
		                    $fecha_inicio_semana = date ( 'Y-m-j' , $nuevafecha );
		                    $fecha = $fecha_inicio_semana;
		                    $nuevafecha = strtotime ( '+6 day' , strtotime ( $fecha ) ) ;
		                    $fecha_semana_final = date ( 'Y-m-j' , $nuevafecha );
	                	}
	                	if($this->input->post('id_dias_grabacion')==1){
	                       $lunes='checked';$martes='';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==2){
	                       $lunes='checked';$martes='checked';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==3){
	                       $lunes='checked';$martes='checked';$miercoles='checked';$jueves='';$viernes='';$sabado='';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==4){
	                       $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='';$sabado='';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==5){
	                       $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==6){
	                       $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='';
	                	}
	                	if($this->input->post('id_dias_grabacion')==7){
	                       $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='checked';
	                	}
	                	if($i==$semanas){
	                	  $fecha_semana_final=$fin_grabacion;
	                	}
                    	/*SECCION PARA ACTULIZACION DE DATOS SEMANA*/
                    	$fecha_1=$fecha_inicio_semana;
                    	$fecha_2=$fecha_semana_final;
                		$capitulos_2 = $this->model_produccion->suma_capitulos_semana($this->input->post('id_produccion'), $fecha_1, $fecha_2, $semanas_actuales[count($semanas_actuales)-1]['id']);
                		$minutos_2 = $this->model_produccion->suma_minutos_semana($this->input->post('id_produccion'), $fecha_1, $fecha_2, $semanas_actuales[count($semanas_actuales)-1]['id']);

                		$dias_2 = $this->model_produccion->dias_trabajo_semana($this->input->post('id_produccion'), $fecha_1, $fecha_2, $semanas_actuales[count($semanas_actuales)-1]['id']);
						$capitulos_programados = $this->input->post('write_cap');
                		$minutos_proyectados = $this->input->post('min_proy_sem');
                		$segundos_proyectados = $this->input->post('seg_proy_sem');

                    	$capitulos_programados = $this->input->post('write_cap');

                    	$dias_trabajo = $this->input->post('id_dias_grabacion');

                  		if(isset($capitulos_2[0]->capitulos_programados)){
                  			$capitulos_programados= $capitulos_2[0]->capitulos_programados;
                  		}else{
                  			if($libretos_prgramados >= $capitulos_programados){
                  				$capitulos_programados = $this->input->post('write_cap');
                  			}else{
                  				if($libretos_prgramados < $capitulos_programados AND $libretos_prgramados>0){
                  					$capitulos_programados = $libretos_prgramados;
                  				}else{
                  					$capitulos_programados = 0;
                  				}
                  			}
                  			$libretos_prgramados-=$capitulos_programados;
                  		}

                  		if(isset($minutos_2[0]->minutos_proyectados)){
                  			$minutos_proyectados = $minutos_2[0]->minutos_proyectados;
                  			$segundos_proyectados = $minutos_2[0]->segundos_proyectados;
                  		}

                  		if(isset($dias_2[0]->dias_trabajo)){
                  			$dias_trabajo = $dias_2[0]->dias_trabajo;
                  		}

                    	$date=array(
                    		'dias_trabajo'=>$dias_trabajo,
                    		'fecha_inicio_semana'=>$fecha_inicio_semana,
                    		'fecha_fin_semana'=>$fecha_semana_final,
                    		'capitulos_programados'=>$capitulos_programados,
                          	'minutos_proyectados'=>$minutos_proyectados,
                          	'segundos_proyectados'=>$segundos_proyectados,
                          	'lunes'=>$lunes,
                          	'martes'=>$martes,
                          	'miercoles'=>$miercoles,
                          	'jueves'=>$jueves,
                          	'viernes'=>$viernes,
                          	'sabado'=>$sabado,
                          	'domingo'=>$domingo,
                    		'id_produccion'=>$this->input->post('id_produccion'),
                    	);
                    	$sem=$this->model_produccion->semanas_produccion($date);
                    	$i++;
	                }

                    for ($m=0; $m <count($semanas_actuales); $m++) { 
                        $this->model_plan_produccion->delete_semanas($this->input->post('id_produccion'),$semanas_actuales[$m]['id']);
                    }
                    /*FIN SECCION PARA ACTULIZACION DE DATOS SEMANA*/
	            }else{
	           		if($this->input->post('id_dias_grabacion')==1){
	                   $lunes='checked';$martes='';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==2){
	                   $lunes='checked';$martes='checked';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==3){
	                   $lunes='checked';$martes='checked';$miercoles='checked';$jueves='';$viernes='';$sabado='';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==4){
	                   $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='';$sabado='';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==5){
	                   $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==6){
	                   $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='';
	            	}
	            	if($this->input->post('id_dias_grabacion')==7){
	                   $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='checked';
	            	}
	            	$date=array(
	            		'dias_trabajo'=>$this->input->post('id_dias_grabacion'),
	                  	'lunes'=>$lunes,
	                  	'martes'=>$martes,
	                  	'miercoles'=>$miercoles,
	                  	'jueves'=>$jueves,
	                  	'viernes'=>$viernes,
	                  	'sabado'=>$sabado,
	                  	'domingo'=>$domingo,
	            		'id_produccion'=>$this->input->post('id_produccion'),
	            	);
	            	$sem=$this->model_produccion->semanas_produccion_update($date,$actual_days);
	           }
	           $this->actualizar_capitulos($idproduccion,$this->input->post('id_dias_grabacion'));  
	          redirect ($this->lang->lang().'/plan_produccion/index/'.$this->input->post('id_produccion').'/1');
	        }else {
	       	  echo 'error al edi la produccion';
	        }
		}
   }

    public function actualizar_capitulos($idproduccion,$id_dias_grabacion){
   		$produccion = $this->model_plan_produccion->produccion_id($idproduccion);
        $cantidad_capitulos = $this->model_capitulos->contar_capitulos(0,$idproduccion);
        $diferencia = $cantidad_capitulos[0]['cantidad'] - $produccion[0]->numero_capitulo;
        if($diferencia>0){
        	$capitulos_eliminar = $this->model_capitulos->buscar_capitulos_escritos	($produccion[0]->numero_capitulo+1, $cantidad_capitulos[0]['cantidad'],$idproduccion);
        	if(!$capitulos_eliminar){
        		$this->model_capitulos->eliminar_rango_capitulos($produccion[0]->numero_capitulo+1, $cantidad_capitulos[0]['cantidad'],$idproduccion);
        	}else{
        		$data = array(
        			'numero_capitulo'=>$cantidad_capitulos[0]['cantidad'] 
        		);
        		$this->db->where('id',$idproduccion);
        		$this->db->update('produccion',$data);
        	}
        }

        if($diferencia<0){
        	for ($i=$cantidad_capitulos[0]['cantidad']+1; $i <= $produccion[0]->numero_capitulo; $i++) { 
        		$data=array(
    				'id_produccion'=>$idproduccion,
            		'numero'=>($i),
            		'estado'=>1,
            		'duracion_estimada'=> '00:00',
            		'fecha_aire' => '0000-00-00'
                );
                $this->model_capitulos->insertar_capitulo($data);
                $data=array(
    				'id_produccion'=>$idproduccion,
            		'numero'=>($i),
            		'id_estado'=>1
                );
                $this->model_capitulos->insertar_capitulo_post($data);

                $id_capitulo = mysql_insert_id();
                $id_user_prod=$this->model_post_produccion->user_produccion_rol2($idproduccion);
                if($id_user_prod){
                	foreach ($id_user_prod as $user) {
                		$data=array(
	    				'id_user'=>$user['id_user'],
	            		'id_capitulo'=>$id_capitulo,
	            		'estado'=>1
		                );
		                $this->model_capitulos->insertar_capitulo_user($data);
                	}
                }
        	}
        }

   		if($produccion[0]->fecha_aire!="" AND $produccion[0]->fecha_aire!="-" AND $produccion[0]->fecha_aire!="0000-00-00"){
	        $j=86400;
	        $ultima_fecha = $produccion[0]->fecha_aire;
	        for($i=0; $i<$produccion[0]->numero_capitulo; ++$i) {
	            $active = 0;
	            $capitulo = $this->model_capitulos->capitulo_id($i,$idproduccion);
	            $dias = $this->model_capitulos->contar_dias_aire($produccion[0]->id_dias_grabacion);
	            if($i==0){
	            	$fecha = $produccion[0]->fecha_aire;
	            }else{
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
	        	}

	            $j=86400;
	            $capitulo = $this->model_capitulos->capitulo_id($i+1,$idproduccion);
	            $capitulo_post = $this->model_post_produccion->capitulo_by_numero($idproduccion,$i+1);
        		$data=array('id_capitulo'=>$capitulo_post['0']['id'],'fecha_entrega'=>$fecha);
        		//echo print_r($data);
                $this->model_post_produccion->update_capitulo_fecha_entrega($data);
	            if(!$capitulo){
	            	$data=array(
	    				'id_produccion'=>$idproduccion,
	            		'numero'=>($i+1),
	            		'estado'=>1,
	            		'duracion_estimada'=> '00:00',
	            		'fecha_aire' => $fecha
	            	);
	            	$this->model_capitulos->insertar_capitulo($data);
	        	}else{
	        		$this->model_capitulos->actualizar_capitulo($idproduccion,$capitulo[0]['id'],"fecha_aire",$fecha);
	        		
	        	}
	        	$ultima_fecha = $fecha; 
	        }   
   		}else{
   			for($i=0; $i<$produccion[0]->numero_capitulo; ++$i) {
   				$this->model_capitulos->actualizar_capitulo_2($idproduccion,($i+1),"fecha_aire",'0000-00-00');
   			}
   		}
   }

    public function actualizar_user(){
        $unidades_plan=$this->model_plan_produccion->unidades_plan($this->input->post('id_produccion'));
        $unidades=$this->input->post('unidades');
        $unidades_produccion=$this->model_plan_produccion->unidades_id_produccion($this->input->post('id_produccion'));
        $cont=1;
        $cont2=0;
        $existe=0;
        $array=array();
    	while($cont<=$unidades){
			if($this->input->post('id_unida_'.$cont)!='null'){
				$array[$cont2]=$this->input->post('id_unida_'.$cont);
				$cont2++;
			}
    		$cont++;
    	}
        if($unidades_plan){
	        foreach($unidades_plan as $u){
				if (in_array($u['id'],$array)) {
				}else{
					$existe=1;
				}
	        }
        } 
	    if($existe==1){
	     	redirect ($this->lang->lang().'/plan_produccion/index/'.$this->input->post('id_produccion').'/2');
	    }else{
	      	if($this->input->post('productor_ejecutivo')=='null'){
				$productor_ejecutivo=null;
			}else {
				$productor_ejecutivo=$this->input->post('productor_ejecutivo');
			}
			if($this->input->post('productor_general')=='null'){
				$productor_general=null;
			}else{
				$productor_general=$this->input->post('productor_general');
			}
			if($this->input->post('productor')=='null'){
				$productor=null;
			}else {
				$productor=$this->input->post('productor');
			}
			$data_produccion=array(
	            'id'=>$this->input->post('id_produccion'),
	            'id_productor_ejecutivo'=>$productor_ejecutivo,
	            'id_productor_general'=>$productor_general,
	            'id_productor'=>$productor,
	            'numero_unidades'=>$this->input->post('unidades')
        	);
	        $this->model_plan_produccion->udpate_numero_unidades($data_produccion);
	        $cont=1;
	        $cont_unidad=$unidades_produccion[sizeof($unidades_produccion)-1]['numero']; 
            $unidades_actuales = $this->model_plan_produccion->unidades_id_produccion($this->input->post('id_produccion'));
            $sql='';
            if($unidades!=0){
               	while ($cont<=$unidades) {
               		if($this->input->post('id_unida_'.$cont)=='null'){
                        $id_unida=null;
               		}else{
               			$id_unida=$this->input->post('id_unida_'.$cont);
               		}
               		if($this->input->post('director_'.$cont)=='null'){
                       $director=null;
               		}else{
               			$director=$this->input->post('director_'.$cont);
               		}
               		if($this->input->post('script_'.$cont)=='null'){
                        $script=null;
               		}else{
               			$script=$this->input->post('script_'.$cont);
               		}
	               	if($this->input->post('date_start'.$cont)){
						//$fecha_unidad=date("Y-m-d",strtotime($this->input->post('date_start'.$cont)));
						$fecha_unidad=$this->fechaFormat($this->input->post('date_start'.$cont));
					}else{
						$fecha_unidad=null;
					}	
               	
	               	if($id_unida==null){
	               		$data_unidades=array(
	                      'id_director'=>$director,
	                      'id_script'=>$script,
	                      'fecha_inicio'=>$fecha_unidad,
	                      'id_produccion'=>$this->input->post('id_produccion'),
	                      'numero'=>++$cont_unidad);
	              	    $unidad=$this->model_produccion->insert_unidad($data_unidades);	
	               	    $id_unidad = mysql_insert_id();
	               	    $sql=$sql.' AND id!='.$id_unidad;
	               	}else{
	               		$data_unidades=array(
	                      'id_unida'=>$id_unida,
	                      'id_director'=>$director,
	                      'id_script'=>$script,
	                      'fecha_inicio'=>$fecha_unidad,
	                      'id_produccion'=>$this->input->post('id_produccion')
	                    );
	               	    $unidad=$this->model_produccion->update_unidad($data_unidades);
	               	    $data_plan=array(
	                      'id_unidad'=>$id_unida,
	                      'id_director'=>$director,
	                      'id_script'=>$script
	                  	);
	               	    $this->model_plan_produccion->actualizar_usuarios_planes($data_plan);
	               	    $sql=$sql.' AND id!='.$id_unida;
               		}
                	$cont++;
               	}
                $this->model_plan_produccion->delete_unidades($this->input->post('id_produccion'),$sql);
            }
            $produccion=$this->model_plan_produccion->produccion_id($this->input->post('id_produccion'));
            if($produccion['0']->inicio_grabacion=="0000-00-00" or $produccion['0']->inicio_grabacion==''){
           	  $fecha_unidad=$this->model_produccion->fecha_unidad_plan($this->input->post('id_produccion'));
           	  if($fecha_unidad['0']->fecha_inicio and $fecha_unidad['0']->fecha_inicio!="0000-00-00"){
           	  	$update_inicio_grabacion=$this->model_produccion->update_inicio_grabacion($fecha_unidad['0']->fecha_inicio,$this->input->post('id_produccion'));
           	  }
            }
            redirect ($this->lang->lang().'/plan_produccion/index/'.$this->input->post('id_produccion').'/1'.'/');
        }   
    }

    public function carga_usuarios(){
   	    $id_tipo=$_POST['id_tipo'];
   	    $idproduccion=$_POST['idproduccion'];
	   	if($idproduccion){
	   		if($id_tipo!=8){
	   			$user=$this->model_plan_produccion->user_has_roles_id_produccion($id_tipo,$idproduccion);
	   			//echo $this->db->last_query();
	   		}else{
				$user=$this->model_plan_produccion->productores_id_produccion($id_tipo,$idproduccion);		   			
			//	
	   		}
	   	}else{
	   		if($id_tipo!=8){
	   	  		$user=$this->model_plan_produccion->user_has_roles_id_($id_tipo);
	   	  	}else{
	   	  		$user=$this->model_plan_produccion->productores($id_tipo);
	   	  	}
	   	}
	   	$data['user']=$user;
	    echo json_encode($data);
   	}

    public function agregar_usuario(){
	   	$id_user=$_POST['id_user'];
	   	$rol=$_POST['rol'];
	   	$id_produccion=$_POST['id_produccion'];
	   	$data=array(
            'id_user'=>$id_user,
            'rol'=>$rol,
            'id_produccion'=>$id_produccion
        );
	   	$user=$this->model_plan_produccion->user_has_produccion($data);
	   	if($user){
	   		$data['user']=$user;
	        echo json_encode($data);
	   	}
    }

    public function borrar_usuario(){
		$id_user=$_POST['id_user'];
		$id_produccion=$_POST['id_produccion'];
		$data=array(
		'id_usuario'=>$id_user,
		'id_produccion'=>$id_produccion);
		$user=$this->model_plan_produccion->delete_user_has_produccion($data);
		echo json_encode($data);
    }

    public function guardar_indicadores(){
	  	$this->form_validation->set_rules('rang_prod_amount2','Interior','required');
	   	$this->form_validation->set_rules('rang_prod_amount2','Exterior','required');
	   	if ($this->form_validation->run()==FALSE) {
			$this->index($this->input->post('id_produccion'));
		}else{
			$data=array(
				'id_produccion'=>$this->input->post('id_produccion'),
				'produccion_interior'=>$this->input->post('rang_prod_amount1'),
				'produccion_exterior'=>$this->input->post('rang_prod_amount2'),
				'locacion'=>$this->input->post('locacion'),
				'estudio'=>$this->input->post('estudio'),
				'dia'=>$this->input->post('dia'),
				'noche'=>$this->input->post('noche'),
				'numero_locaciones'=>$this->input->post('rang_cap_1'),
				'numero_set'=>$this->input->post('rang_cap_2'),
				'numero_protagonistas'=>$this->input->post('numero_protagonistas'),
                'numero_repartos'=>$this->input->post('numero_repartos'),
                'numero_figurantes'=>$this->input->post('numero_figurantes'),
                'numero_extras'=>$this->input->post('numero_extras'),
                'numero_vehiculos'=>$this->input->post('numero_vehiculos'),
                'presupuesto_principales'=>str_replace(',','',$this->input->post('presupuesto_principales')),
                'presupuesto_secundarios'=>str_replace(',','',$this->input->post('presupuesto_secundarios')),
                'presupuesto_figurante'=>str_replace(',','',$this->input->post('presupuesto_figurante')),
                'escenas_libretos'=>$this->input->post('escenas_libretos'),
                'evento_pequeno'=>$this->input->post('evento_pequeno'),
                'evento_mediano'=>$this->input->post('evento_mediano'),
                'evento_grande'=>$this->input->post('evento_grande'),
                'locaciones_nuevas'=>$this->input->post('locaciones_nuevas'),
                'protagonistas_produccion'=>$this->input->post('protagonistas_produccion'),
                'monto_figurante_extra'=>str_replace(',','',$this->input->post('monto_figurante_extra')),
                'monto_figurante_extra_dolar'=>str_replace(',','',$this->input->post('monto_figurante_extra_dolar')),
                'paginasPorLibretos'=>$this->input->post('paginasPorLibretos'),
            );
			$produccion=$this->model_plan_produccion->update_indicadores_produccion($data);
			redirect ($this->lang->lang().'/plan_produccion/index/'.$this->input->post('id_produccion').'/1');
	    }                 
    }

    public function update_semana() {
	    $this->form_validation->set_rules('valor', 'Campo', 'required');
	    $this->form_validation->set_message('required', '%s es requerido');
	    if ($this->form_validation->run() == FALSE) {
	        $this->index($this->input->post('id_produccion'));
	    }else{
	        if($this->input->post('tipo') == 1) {
	            $l='';$m='';$mi='';$j='';$v='';$s='';$d='';
	            $cont_d=0;
	            $lunes=$this->input->post('lunes');
	            $martes=$this->input->post('martes');
	            $miercoles=$this->input->post('miercoles');
	            $jueves=$this->input->post('jueves');
	            $viernes=$this->input->post('viernes');
	            $sabado=$this->input->post('sabado');
	            $domingo=$this->input->post('domingo');
	            if($lunes=='on'){
	              $l='checked';
	              $cont_d=1;
	            }
	            if($martes=='on'){
	              $m='checked';
	              $cont_d=$cont_d+1;
	            } 
	            if($miercoles=='on'){
	              $mi='checked';
	              $cont_d=$cont_d+1;
	            } 
	            if($jueves=='on'){
	              $j='checked';
	              $cont_d=$cont_d+1;
	            } 
	            if($viernes=='on'){
	              $v='checked';
	              $cont_d=$cont_d+1;
	            }  
	            if($sabado=='on'){
	              $s='checked';
	              $cont_d=$cont_d+1;
	            }  
	            if($domingo=='on'){
	              $d='checked';
	              $cont_d=$cont_d+1;
	            }

                $datos=array(
	              'id'=>$this->input->post('id_semana'),
	              'dias_trabajo'=>$cont_d,
	              'lunes'=>$l,
	              'martes'=>$m,
	              'miercoles'=>$mi,
	              'jueves'=>$j,
	              'viernes'=>$v,
	              'sabado'=>$s,
	              'domingo'=>$d
                );
                $this->model_plan_produccion->dias_grabacion_semana($datos);
                redirect ($this->lang->lang().'/plan_produccion/index/'.$this->input->post('id_produccion').'/1');
	        }


	        if($this->input->post('tipo') == 2) {
	        	$semanas_actuales = $this->model_plan_produccion->semanas_trabajo($this->input->post('id_produccion'));
	            $fin_grabacion=$this->input->post('final_grabacion');
	            $inicio_PreProduccion=$this->input->post('valor');
	            $dias=((strtotime($fin_grabacion)-strtotime($inicio_PreProduccion))/86400);
                $fecha= $fin_grabacion; 
                $i = strtotime($fecha); 
                $dia_semana_final=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
                if($dia_semana_final<=0){
               		$dia_semana_final=0;
                }else{
               		$dia_semana_final=7-$dia_semana_final;
                }
                $fecha= $inicio_PreProduccion; 
                $i = strtotime($fecha); 
                $dia_semana=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
                $dia_semana=$dia_semana-7;
                if($dia_semana<0){
               		$dia_semana=0;
                }
                $dias=$dias+$dia_semana+$dia_semana_final;
	            $semanas= $dias/7;
	            $semanas= ceil($semanas);
                $i=1;
                $cont=0;
                
                $produccion=$this->model_plan_produccion->produccion_id($this->input->post('id_produccion'));
	            while ($i <= $semanas) {
	                if ($cont == 0) {
	                    $fecha = strtotime($this->input->post('valor'));
	                    $ano = date("Y", $fecha); // Year (2003)
	                    $mes = date("m", $fecha); // Month (12)
	                    $dia = date("d", $fecha); // day (14)
	                    $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
	                    $dia = 7 - $dia;

	                    $fecha_inicio_semana = date("Y-m-d", strtotime($this->input->post('valor')));
	                    $fecha = $fecha_inicio_semana;
	                    $nuevafecha = strtotime('+' . $dia . ' day', strtotime($fecha));
	                    $fecha_semana_final = date('Y-m-j', $nuevafecha);
	                    $cont++;
	                }else{
	                    $fecha = $fecha_semana_final;
	                    $nuevafecha = strtotime('+1 day', strtotime($fecha));
	                    $fecha_inicio_semana = date('Y-m-j', $nuevafecha);
	                    $fecha = $fecha_inicio_semana;
	                    $nuevafecha = strtotime('+6 day', strtotime($fecha));
	                    $fecha_semana_final = date('Y-m-j', $nuevafecha);
	                }

	                if ($this->input->post('id_dias_grabacion') == 1) {
	                    $lunes = 'checked';
	                    $martes = '';
	                    $miercoles = '';
	                    $jueves = '';
	                    $viernes = '';
	                    $sabado = '';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 2) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = '';
	                    $jueves = '';
	                    $viernes = '';
	                    $sabado = '';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 3) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = 'checked';
	                    $jueves = '';
	                    $viernes = '';
	                    $sabado = '';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 4) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = 'checked';
	                    $jueves = 'checked';
	                    $viernes = '';
	                    $sabado = '';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 5) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = 'checked';
	                    $jueves = 'checked';
	                    $viernes = 'checked';
	                    $sabado = '';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 6) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = 'checked';
	                    $jueves = 'checked';
	                    $viernes = 'checked';
	                    $sabado = 'checked';
	                    $domingo = '';
	                }
	                if ($this->input->post('id_dias_grabacion') == 7) {
	                    $lunes = 'checked';
	                    $martes = 'checked';
	                    $miercoles = 'checked';
	                    $jueves = 'checked';
	                    $viernes = 'checked';
	                    $sabado = 'checked';
	                    $domingo = 'checked';
	                }
	                if($i==$semanas){
	                   $fecha_semana_final=$fin_grabacion;
	                }

            		/*SECCION PARA ACTULIZACION DE DATOS SEMANA*/
                	$fecha_1=$fecha_inicio_semana;
                	$fecha_2=$fecha_semana_final;
            		$capitulos_2 = $this->model_produccion->suma_capitulos_semana($this->input->post('id_produccion'), $fecha_1, $fecha_2, $semanas_actuales[count($semanas_actuales)-1]['id']);
            		$minutos_2 = $this->model_produccion->suma_minutos_semana($this->input->post('id_produccion'), $fecha_1, $fecha_2, $semanas_actuales[count($semanas_actuales)-1]['id']);

                	$capitulos_programados = $this->input->post('write_cap');
                	$minutos_proyectados = $this->input->post('min_proy_sem');

              		if(isset($capitulos_2[0]->capitulos_programados)){
              			$capitulos_programados= $capitulos_2[0]->capitulos_programados;
              		}

              		if(isset($minutos_2[0]->minutos_proyectados)){
              			$minutos_proyectados = $minutos_2[0]->minutos_proyectados;
              		}

                	$date=array(
                		'dias_trabajo'=>$this->input->post('id_dias_grabacion'),
                		'fecha_inicio_semana'=>$fecha_inicio_semana,
                		'fecha_fin_semana'=>$fecha_semana_final,
                		'capitulos_programados'=>$capitulos_programados,
                      	'minutos_proyectados'=>$minutos_proyectados,
                      	'lunes'=>$lunes,
                      	'martes'=>$martes,
                      	'miercoles'=>$miercoles,
                      	'jueves'=>$jueves,
                      	'viernes'=>$viernes,
                      	'sabado'=>$sabado,
                      	'domingo'=>$domingo,
                		'id_produccion'=>$this->input->post('id_produccion'),
                	);

                	$sem=$this->model_produccion->semanas_produccion($date);
                 	$i++;
            		/*FIN SECCION PARA ACTULIZACION DE DATOS SEMANA*/
	            }
	            for($m=0; $m <count($semanas_actuales); $m++) { 
                    $this->model_plan_produccion->delete_semanas($this->input->post('id_produccion'),$semanas_actuales[$m]['id']);
                }
	            $datos = array(
	                'inicio_preProduccion' => date("Y-m-d", strtotime($this->input->post('valor'))),
	                'id' => $this->input->post('id_produccion')
	            );
	            $this->model_plan_produccion->update_fecha_preProduccion($datos);
	            redirect ($this->lang->lang().'/plan_produccion/index/' . $this->input->post('id_produccion') . '/1');
	        }else{
	            if($this->input->post('tipo') == 4 or $this->input->post('tipo') == 5) {
	                if ($this->input->post('cambiar_todos') == 'on') {
	                    $datos = array(
	                        'id_produccion' => $this->input->post('id_produccion'),
	                        'valor' => $this->input->post('valor'),
	                        'valor2' => $this->input->post('valor2'),
	                        'tipo' => $this->input->post('tipo')
	                    );
	                    $this->model_plan_produccion->update_semana2($datos);
	                    redirect ($this->lang->lang().'/plan_produccion/index/' . $this->input->post('id_produccion') . '/1');
	                }else{
	                    $datos = array(
	                        'id' => $this->input->post('id_semana'),
	                        'valor' => $this->input->post('valor'),
	                        'valor2' => $this->input->post('valor2'),
	                        'tipo' => $this->input->post('tipo')
	                    );
	                    $this->model_plan_produccion->update_semana($datos);
	                    redirect ($this->lang->lang().'/plan_produccion/index/' . $this->input->post('id_produccion') . '/1');
	                }
	            }else{
	                $datos = array(
	                    'id' => $this->input->post('id_semana'),
	                    'valor' => $this->input->post('valor'),
	                    'tipo' => $this->input->post('tipo')
	                );
	                $this->model_plan_produccion->update_semana($datos);
	                redirect ($this->lang->lang().'/plan_produccion/index/' . $this->input->post('id_produccion') . '/1');
	            }
        	}
	    }
	}

	public static function corta_palabra($palabra,$num)	{
		$largo=strlen($palabra);
		$cadena=substr($palabra,0,$num);
		return $cadena;
	}  

	public function update_escritura_cap(){
		$id_semana=$_POST['id_semana'];	
		$valor=$_POST['valor'];	
		$tipo=$_POST['tipo'];	
		$datos = array(
			'id' => $id_semana,
			'valor' => $valor,
			'tipo' => $tipo);
		$this->model_plan_produccion->update_semana($datos);
		$data['dato']=1;
		echo json_encode($data);
	}

	public function insert_coment(){
		$datos = array(
		    'id' => $_POST['id_semana'],
		    'valor' => $_POST['comentario'],
		    'tipo' => $_POST['tipo']);
		$coment=$this->model_plan_produccion->update_semana($datos);
		if($coment){
			$data['dato']=1;
			echo json_encode($data);
		}
	}

	public function buscar_usuarios_palabra(){
		$id_users=$_POST['id_users'];	
		$idproduccion=$_POST['idproduccion'];	
		$palabra=$_POST['palabra'];
		$sql="";

		if($id_users!=""){
			$temp = explode(',', $id_users);
			foreach ($temp as $id) {
				if($id!=""){
					$sql .= " AND user.id !=".$id;
				}
			}
		}
		$usuarios = $this->model_plan_produccion->buscar_usuarios_palabra($palabra,$sql);
		$data['usuarios'] = $usuarios;
		echo json_encode($data);
	}	
}
