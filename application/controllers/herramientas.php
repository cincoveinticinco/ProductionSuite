<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Herramientas extends CI_Controller {
	public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_elementos');
	    $this->load->model('model_plan_general');
	    $this->load->model('model_escenas');
	    $this->load->model('model_capitulos');
	    $this->load->model('model_admin');
	    $this->load->model('model_herramientas');
	    $this->load->model('model_plan_produccion');
	    $this->load->model('model_pdf');
	    $this->load->model('model_escenas_2');
	    $this->_logeo_in();
	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
    }

    public function index($id=''){
    	$tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
    	$id_user=$this->session->userdata('id_pruduction_suite');
    	$user=$this->model_admin->rolUserId($id_user);
    	$tipo_rol = $user['0']['id_rol_otros'];
	    if($tipo_usuario=='1' OR $tipo_usuario=='2' OR $tipo_usuario=='3'  OR $tipo_usuario=='5' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
	        $id_user=$this->session->userdata('id_pruduction_suite');
	        $user=$this->model_admin->rolUserId($id_user);
	        $tipo_rol=$user['0']['id_rol_otros'];
	        if(!$tipo_rol){
	         $tipo_rol=0;
	        }

	        $continuar=0;
	          if($user){
	              foreach ($user as $u) {
	                if($u['id_rol_otros']== 6 or $u['id_rol_otros']== 8 or $u['id_rol_otros']== 2 or $u['id_rol_otros']== 13){
	                  $continuar=1;
	                  break;
	                }
	              }
	         }
        
	        if( $continuar==1 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='2' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){ 
	        	$usuario_permisos = $this->permisos_usuarios($id);
		    	$produccion = $this->model_plan_produccion->produccion_id($id);
		    	$capitulos = $this->model_capitulos->capitulos_produccion_2($id);
		    	$locacion=$this->model_escenas->locacion($id);
		    	$unidades=$this->model_plan_produccion->unidades_id_produccion($id);
		    	$fecha=$this->model_plan_produccion->unidades_id_produccion_2($id);
		    	if($fecha){
                   $inicio_unidad=$fecha['0']['fecha_inicio'];
                   $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad($id,$inicio_unidad);
		    	}else{
		    	  $fechas_reporte_semanal='';	
		    	}
		    	//$personajes = $this->model_elementos->personajes_no_extra($id);
		    	//$data['personajes'] = $personajes;
				$data['fechas_reporte_semanal'] = $fechas_reporte_semanal;
				$data['usuario_permisos'] = $usuario_permisos;
		    	$data['unidades']=$unidades;
				$data['locacion']=$locacion;
		    	$data['produccion'] = $produccion;
		    	$data['capitulos'] = $capitulos;
		    	$categorias_elementos=$this->model_elementos->categorias_elementos($id);
				$elementos=$this->model_elementos->elemento_id_produccion2($id,$categorias_elementos[0]['id']);
		        $data['elementos'] = $elementos;
		    	$data['view']='herramientas/index';
		    	$this->load->view('includes/template',$data);
		    }else{
            redirect ($this->lang->lang().'/produccion/producciones');
        }   
      }else{
      	redirect ($this->lang->lang().'/produccion/producciones');
      }  
	}

	public function buscar_escenas_capitulo(){
		$idcapitulo = $_POST['idcapitulo'];
		$escenas = $this->model_herramientas->buscar_escena_capitulo($idcapitulo);
		$data['escenas']=$escenas;
      	echo json_encode($data);
	}

	public function actualizar_escenas(){
		$numero1 = $this->input->post('capitulos_herramientas_from');
		$numero2 = $this->input->post('capitulos_herramientas_to');
		$escena1 = $this->input->post('escenas_herramientas_from');
		$escena2 = $this->input->post('escenas_herramientas_to'); 
		$set_busqueda = $this->input->post('set_busqueda'); 
		$idproduccion = $this->input->post('id_produccion'); 
		
		if($numero2<$numero1){
			$aux = $numero1;
			$numero1 = $numero2;
			$numero2 = $aux;
			$aux = $escena1;
			$escena1 = $escena2;
			$escena2 = $escena1;
		}
		$cadena = "";
		$cadena_parcial ="";
		$data = array();
		$tipo = $this->input->post('tipo'); 
		$sql = "";
		if($tipo==1){
			$dia_continuidad = $this->input->post('dia_continuidad');
			$dia_continuidad_actual = $this->input->post('dia_continuidad_actual');
			$sql = " AND dias_continuidad = ".$dia_continuidad_actual; 
			if($set_busqueda){
				if($set_busqueda!='' AND $set_busqueda!=' '){
			        $sets=$_POST['set_busqueda'];
			        $sets=explode(',', $sets);
			        $sql.=' AND (';
			        $cont=0;
			        foreach ($sets as $s) {
			            if($s){
				              if($cont==0){
				                 $sql.=' escena.id_set='.$s;
				              }else{
				                 $sql.=' OR escena.id_set='.$s;   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
                }
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}
			if($dia_continuidad!=""){
				$data ['dias_continuidad'] = $dia_continuidad;
				$cadena_parcial = " DÍA CONTINUIDAD A ".$dia_continuidad;
			}
		}elseif($tipo==2){
			$locacion = $this->input->post('location_cambio');
			$set = $this->input->post('set_cambio');
			$locacion_actual = $this->input->post('location_actual');
			$set_actual = $this->input->post('set_actual');
			$sql = " AND id_locacion = ".$locacion_actual; 
			if($set_actual){
				if($set_actual!='' AND $set_actual!=' '){
			        $sets=$_POST['set_actual'];
			        $sets=explode(',', $sets);
			        $sql.=' AND (';
			        $cont=0;
			        foreach ($sets as $s) {
			            if($s){
				              if($cont==0){
				                 $sql.=' id_set='.$s;
				              }else{
				                 $sql.=' OR id_set='.$s;   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
                }
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}

			//$sql = " AND id_locacion = ".$locacion_actual." AND id_set =".$set_actual." "; 
			if($locacion!="" AND $locacion!="Seleccione una locación" AND $locacion!=""){
				$data ['id_locacion'] = $locacion;
				$data ['id_set'] = $set;
				$locacion_selected = $this->model_escenas->locacion_id($set);
				$set_selected= $this->model_escenas->set_id($set);
				$cadena_parcial = " LOCACIÓN A ".$locacion_selected[0]['nombre']." Y SET A ".$set_selected[0]['nombre'];
			}
		}elseif($tipo==3){
			$locacion_tipo = $this->input->post('locacion_tipo');
			$locacion_tipo_cambiar = $this->input->post('locacion_tipo_cambiar');
			$sql = " AND id_tipo_locacion = ".$locacion_tipo; 
			if($set_busqueda){
				if($set_busqueda!='' AND $set_busqueda!=' '){
			        $sets=$_POST['set_busqueda'];
			        $sets=explode(',', $sets);
			        $sql.=' AND (';
			        $cont=0;
			        foreach ($sets as $s) {
			            if($s){
				              if($cont==0){
				                 $sql.=' escena.id_set='.$s;
				              }else{
				                 $sql.=' OR escena.id_set='.$s;   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
                }
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}
			if($locacion_tipo!="" AND $locacion_tipo){
				$data ['id_tipo_locacion'] = $locacion_tipo_cambiar;
			}
			$cadena_parcial = " TIPO LOCACIÓN  A ";
			if($locacion_tipo_cambiar==1){
				$cadena_parcial .= " LOCACIÓN";
			}else{
				$cadena_parcial .= " ESTUDIO";
			}

		}elseif($tipo==4){
			$dia_noche = $this->input->post('dia_noche');
			$dia_noche_cambiar = $this->input->post('dia_noche_cambiar');
			$sql = " AND id_dia_noche = ".$dia_noche; 
			if($set_busqueda){
				if($set_busqueda!='' AND $set_busqueda!=' '){
			        $sets=$_POST['set_busqueda'];
			        $sets=explode(',', $sets);
			        $sql.=' AND (';
			        $cont=0;
			        foreach ($sets as $s) {
			            if($s){
				              if($cont==0){
				                 $sql.=' escena.id_set='.$s;
				              }else{
				                 $sql.=' OR escena.id_set='.$s;   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
                }
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}
			if($dia_noche!="" AND $dia_noche){
				$data ['id_dia_noche'] = $dia_noche_cambiar;
			}
			$cadena_parcial = " TIEMPO  A ";
			if($dia_noche_cambiar==1){
				$cadena_parcial .= " DÍA";
			}else{
				$cadena_parcial .= " NOCHE";
			}
		}elseif($tipo==5){
			$int_ext = $this->input->post('int_ext');
			$int_ext_cambiar = $this->input->post('int_ext_cambiar');
			$sql = " AND id_interior_esterior = ".$int_ext; 
			if($set_busqueda){
				if($set_busqueda!='' AND $set_busqueda!=' '){
			        $sets=$_POST['set_busqueda'];
			        $sets=explode(',', $sets);
			        $sql.=' AND (';
			        $cont=0;
			        foreach ($sets as $s) {
			            if($s){
				              if($cont==0){
				                 $sql.=' escena.id_set='.$s;
				              }else{
				                 $sql.=' OR escena.id_set='.$s;   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
                }
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}
			if($int_ext!="" AND $int_ext){
				$data ['id_interior_esterior'] = $int_ext_cambiar;
			}
			$cadena_parcial = " UBICACIÓN  A ";
			if($int_ext_cambiar==1){
				$cadena_parcial .= " INTERIOR";
			}else{
				$cadena_parcial .= " EXTERIOR";
			}
		}


		$escenas_capitulos = $this->model_herramientas->rango_escenas($idproduccion,$numero1,$numero2,$sql);

		$validacion = false;
         $cont=0;
		if($escenas_capitulos){
			if($escena1<10){
				$escena1='0'.$escena1;
			}
			if($escena2<10){
				$escena2='0'.$escena2;
			}

			foreach ($escenas_capitulos as $escena_capitulo) {
				    if($escena_capitulo->numero_escena<10){
				      $escena_consulta='0'.$escena_capitulo->numero_escena;
				    }else{
				    	$escena_consulta=$escena_capitulo->numero_escena;
				    }
                    
					if(floatval($numero1.'.'.str_replace('.', '', $escena1))<= floatval($escena_capitulo->numero.'.'.str_replace('.', '', $escena_consulta))){			           
			          //$cont++;	
			          $validacion=true;	

					}
					if($validacion){
						if($tipo==3){ 
									  if($escena_capitulo->estado%2!=0 AND $escena_capitulo->estado!=2){
								          $estado = 12;
									          switch ($locacion_tipo_cambiar) {
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
									        if($escena_capitulo->id_toma_ubicacion==1){
									          $estado = 5; 
									          $toma_ubicacion = 1;
									        }
									        if($escena_capitulo->id_flasback==1){
									          $estado = 11;
									        }

									        if($escena_capitulo->id_producida==1){
									          $estado = 1;
									        }
								        }else{
								          //$estado = $escena_capitulo->estado;
								        	 switch ($locacion_tipo_cambiar) {
							                  case 1:
							                    $estado = 8;
							                    break;
							                  case 2:
							                    $estado = 6; 
							                    break;
							                  }
												if($escena_capitulo->id_toma_ubicacion==1){
													$estado = 6; 
												}
												if($escena_capitulo->id_flasback==1){
													$estado = 10;
												}
												if($escena_capitulo->id_producida==1){
													$estado = 1;
												}
								         
								        }
		                     $data ['estado'] = $estado;
						}
						$escena_selected = $this->model_escenas_2->escena_by_id($escena_capitulo->id);
						if(floatval($numero2.'.'.str_replace('.', '',$escena2)) >= floatval($escena_capitulo->numero.'.'.str_replace('.', '', $escena_consulta))){
							$cont++;
							$this->model_herramientas->actualizar_escena_datos($escena_capitulo->id,$data);
							$cadena .= "\n" . " ESCENA " . $escena_selected[0]->numero_libreto . '/'. $escena_selected[0]->numero_escena . " CAMBIA " . $cadena_parcial;
						}else{
							//$this->model_herramientas->actualizar_escena_datos($escena_capitulo->id,$data);
                          break;
						}
						
					}

			}
		}

		$this->user_log($idproduccion,$cadena);
		$data['resultado']=true;
		$data['cantidad'] = $cont;
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

    public function actores_mensuales(){
    	$mes = $_POST['mes'];
    	$idproduccion = $_POST['id_produccion'];
    	$liquidacion_produccion = $this->model_herramientas->liquidacion_produccion_mensual($idproduccion,$mes);
    	$cadena_liquidacion="";
    	$resultado=false;
    	if(!$liquidacion_produccion){
    		$actores = $this->model_herramientas->nomina_personajes_mensuales($idproduccion,$mes.'-01',$mes.'-31');
    		$resultado=true;
    		if($actores){
    			$cadena_liquidacion.="<div class='table_general'><table style='width:2126px'>
    									<thead>
    										<th>Personaje</th>
    										<th>Actor</th>
											<th>Rol</th>
    										<th>Tipo Doc.</th>
    										<th>Documento</th>
    										<th>Contrato</th>
    										<th>Monto</th>
    										<th >Tipo Mon.</th>
    										<th style='width: 146px;'>Tasa Cambio</th>
    										<th>Inicio</th>
    										<th>Final</th>
    										<th>Liquid.</th>
    										<th>Libs</th>
    										<th>Libretos</th>
    										<th>Descuento</th>
    										<th>Observaciones</th>
    									</thead>";
    			$total_liquidado=0;
    			foreach ($actores as $actor) {
    				$cadena_liquidacion.="<tr ><td>".$actor->nombre."</td>";
    				$cadena_liquidacion.="<td>".$actor->actor_nombre." ".$actor->actor_apellido."</td>";
    				
    				$cadena_liquidacion.="<td>".$actor->rol."</td>";	

    				switch ($actor->id_tipo_documento) {
			          case 1:
			            $doc_type = "CÉD.COL";
			            break;
			          case 2:
			            $doc_type = "T.I";
			            break;
			          case 3:
			            $doc_type = "CÉD.EXT";
			            break;
			          
			          default:
			            $doc_type = '-';
			            break;
			        }

    				$cadena_liquidacion.="<td>".$doc_type."</td>";
    				$cadena_liquidacion.="<td>".$actor->documento_actor."</td>";
    				$cadena_liquidacion.="<td>".$actor->contrato."</td>";
    				$cadena_liquidacion.="<td>$".number_format((double)$actor->monto, 2, '.', ",")."</td>";
    				$cadena_liquidacion.="<td>".$actor->tipo_moneda."</td>";


    				if($actor->id_tipo_moneda==2){
                       $cadena_liquidacion.="<td><input  style='text-align: center;' type='text' name='tamsa_cambio_".$actor->idelemento."' value='' class='tamsa_cambio' data-monto='".$actor->monto."' data-elemento='".$actor->idelemento."'></td>";
                    }else{
                    	$cadena_liquidacion.="<td></td>";
                    }
    				


    				if($actor->fecha_inicio==""){
			          $fecha="-";
			        }else{
			          $fecha = strtoupper($actor->fecha_inicio);
			        }

			        if($actor->fecha_inicio_2!="" AND $actor->fecha_inicio_2!="0000-00-00"){
			          $fecha = strtoupper($actor->fecha_inicio_2);
			        }

    				if($actor->fecha_liquidacion!="" AND $actor->fecha_liquidacion!="0000-00-00"){
			          $fecha_final = strtoupper($actor->fecha_liquidacion);
			        }else{
			          $fecha_final = "-";
			        }

			        $cadena_liquidacion.="<td>".$fecha."</td>";
    				$cadena_liquidacion.="<td>".$fecha_final."</td>";

    				$liquidacion = 0;
			        if($actor->id_tipo_contrato){
			              if($actor->monto){
			                $valor_dia = $actor->monto / 30;
			                $segundos= abs(strtotime($fecha));

			                $dias_mes = cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($mes.'-01')) , date("Y",strtotime($mes.'-01')));

				                if( date("m",strtotime($mes.'-01')) <= date("m",strtotime($fecha))  AND date("Y",strtotime($fecha)) == date("Y",strtotime($mes.'-01')) ){


					                  if(date("d",strtotime($fecha))<30){
					                    $dias_pagar = 30 - date("d",strtotime($fecha))+1;
					                  }else{
					                    $dias_pagar = 1;
					                  }
				                  // echo "Primera validacion";
				                }else{
				                  // echo "Segunda validacion";
					                  if($fecha_final!="-" AND date("m",strtotime($fecha_final)) == date("m",strtotime($mes.'-01')) AND date("Y",strtotime($fecha_final)) == date("Y",strtotime($mes.'-01')) ){
					                    if(date("d",strtotime($fecha_final))>30){
					                      // echo "Tercera validacion";
					                      $dias_pagar=30;
					                    }else{
					                      // echo "Cuarta validacion";
					                      $dias_pagar=date("d",strtotime($fecha_final));
					                    }
					                  }else{
					                  	 if($fecha_final!="-" AND date(strtotime($fecha_final))<date(strtotime($mes.'-01'))){
                                           $dias_pagar=0;
					                  	 }else{
					                  	    $dias_pagar=30; 	
					                  	 }
					                     // echo "Quinta validacion";
					                    
					                  }
				                }

			                $descuentos = $this->model_herramientas->descuento_personaje($actor->idelemento,date("Y-m",strtotime($mes.'-01')));
			                if($descuentos){
			                  $descuentos = $descuentos[0]->descuento;
			                }else{
			                  $descuentos =0;
			                }

			                //echo  $dias_pagar.'</br>';
			                $liquidacion = number_format( (($actor->monto*$dias_pagar)/30) - $descuentos  , 2, '.', ",");
			                $liquidacion_s= (($actor->monto*$dias_pagar)/30) - $descuentos;
			                 if($actor->id_tipo_moneda==2){
			                    }else{
			                    	$total_liquidado+=(($actor->monto*$dias_pagar)/30) - $descuentos;
			                    	 
			                    }
			                
			                $descuentos = number_format($descuentos  , 2, '.', ",");

			              }
			        }else{
			          $liquidacion = "-";
			        }
                    
                    if($actor->id_tipo_moneda==2){
                       $cadena_liquidacion.="<td class='total_liquidado liquidacion_".$actor->idelemento."'> </td>";	
                    }else{
                    	$cadena_liquidacion.="<td data-valorliquidado=".$liquidacion_s." class='total_liquidado'>".$liquidacion."</td>";
                    	 
                    }

    				

    				$libretos =0;
			        if($actor->libretos!=""){
			          $libretos = explode(',', $actor->libretos);
			          $libretos= count($libretos);
			        }
			        $cadena_liquidacion.="<td>".$libretos."</td>";
			        $cadena_liquidacion.="<td>".$actor->libretos."</td>";

    				$cadena_liquidacion.="<td><input type='hidden' name='id_elementos[]' value='".$actor->idelemento."'><input type='text' class='altoInput' name='descuento_".$actor->idelemento."'></td>";		
    				$cadena_liquidacion.="<td><input type='text' class='altoInput anchoInput' name='observacion_".$actor->idelemento."'></td></tr>";		
    			}
    			$cadena_liquidacion.="
    			<tr>
    				<td>TOTAL</td>
    				<td colspan='10'></td>
    				<td class='acum_liquidacion'>".number_format($total_liquidado, 2, '.', ",")."</td>
    				<td colspan='4'></td>
    			</tr>
    			</table></div>";
    		}
    	}
    	$data['resultado']=$resultado;
		$data['cadena_liquidacion'] = $cadena_liquidacion;
      	echo json_encode($data); 
    }


    public function liquidacion_monto(){
       
       $id_elemento=$_POST['id_elemento'];
       $valor=$_POST['valor'];
       $mes = $_POST['mes'];
       $actor=$this->model_herramientas->elementos_id($id_elemento);

       if($actor['0']->fecha_inicio==""){
          $fecha="-";
        }else{
          $fecha = strtoupper($actor['0']->fecha_inicio);
        }

        if($actor['0']->fecha_inicio_2!="" AND $actor['0']->fecha_inicio_2!="0000-00-00"){
          $fecha = strtoupper($actor['0']->fecha_inicio_2);
        }

		if($actor['0']->fecha_liquidacion!="" AND $actor['0']->fecha_liquidacion!="0000-00-00"){
          $fecha_final = strtoupper($actor['0']->fecha_liquidacion);
        }else{
          $fecha_final = "-";
        }

    	$liquidacion = 0;
			        if($actor['0']->id_tipo_contrato){
			              if($actor['0']->monto){
			                $valor_dia = $valor / 30;
			                $segundos= abs(strtotime($fecha));

			                $dias_mes = cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($mes.'-01')) , date("Y",strtotime($mes.'-01')));

				                if( date("m",strtotime($mes.'-01')) <= date("m",strtotime($fecha))  AND date("Y",strtotime($fecha)) == date("Y",strtotime($mes.'-01')) ){


					                  if(date("d",strtotime($fecha))<30){
					                    $dias_pagar = 30 - date("d",strtotime($fecha))+1;
					                  }else{
					                    $dias_pagar = 1;
					                  }
				                  // echo "Primera validacion";
				                }else{
				                  // echo "Segunda validacion";
					                  if($fecha_final!="-" AND date("m",strtotime($fecha_final)) == date("m",strtotime($mes.'-01')) AND date("Y",strtotime($fecha_final)) == date("Y",strtotime($mes.'-01')) ){
					                    if(date("d",strtotime($fecha_final))>30){
					                      // echo "Tercera validacion";
					                      $dias_pagar=30;
					                    }else{
					                      // echo "Cuarta validacion";
					                      $dias_pagar=date("d",strtotime($fecha_final));
					                    }
					                  }else{
					                  	 if($fecha_final!="-" AND date(strtotime($fecha_final))<date(strtotime($mes.'-01'))){
                                           $dias_pagar=0;
					                  	 }else{
					                  	    $dias_pagar=30; 	
					                  	 }
					                     // echo "Quinta validacion";
					                    
					                  }
				                }

			                $descuentos = $this->model_herramientas->descuento_personaje($actor['0']->idelemento,date("Y-m",strtotime($mes.'-01')));
			                if($descuentos){
			                  $descuentos = $descuentos[0]->descuento;
			                }else{
			                  $descuentos =0;
			                }

			                //echo  $dias_pagar.'</br>';
			                $liquidacion = number_format( (($valor*$dias_pagar)/30) - $descuentos  , 2, '.', ",");
			                $liquidacion_s=(($valor*$dias_pagar)/30) - $descuentos;
			                $descuentos = number_format($descuentos  , 2, '.', ",");

			              }
			        }else{
			          $liquidacion = "-";
			        }

			     $data['liquidacion'] = $liquidacion;
			     $data['liquidacion_s'] = $liquidacion_s;
      		echo json_encode($data);    
    }


    public function insertar_liquidacion(){
    	$mes_liquidacion = $this->input->post('mes_liquidacion');
    	$idproduccion = $this->input->post('idproduccion');
    	$this->model_herramientas->insertar_liquidacion($idproduccion,$mes_liquidacion);
    	$idliquidacion = mysql_insert_id();
    	$personajes = $this->input->post('id_elementos');


    	if($personajes){
	    	foreach ($personajes as $personaje) {
	    		if($this->input->post('descuento_'.$personaje)!="" or $this->input->post('tamsa_cambio_'.$personaje)!=""){
	    			$data = array(
	    				'id_elemento' => $personaje, 
	    				'id_liquidacion' => $idliquidacion,
	    				'descuento' => str_replace(',','',$this->input->post('descuento_'.$personaje)),
	    				'observaciones' => $this->input->post('observacion_'.$personaje),
	    				'tasa_cambio' => $this->input->post('tamsa_cambio_'.$personaje)
	    				
	    			);
	    			$this->model_herramientas->insertar_descuento($data);
	    		}
	    	}
    	}

    	$cadena = "\n" . " INSERTADA LIQUIDADCIÓN " .$mes_liquidacion;
		$this->user_log($idproduccion,$cadena);

    	redirect ($this->lang->lang().'/casting/nomina_personajes/'.$idproduccion);
    }

    public function unificar_personaje(){
    	$id_produccion=$_POST['id_produccion'];
    	$personaje1=$_POST['personaje1'];
    	$personaje2=$_POST['personaje2'];


    	if($personaje1){
			        $per=$_POST['personaje1'];
			        $per=explode(',', $per);
			        $sql=' AND (';
			        $cont=0;
			        foreach ($per as $p) {
			            if($p){
				              if($cont==0){
				                 $sql.=' elemento.nombre="'.$p.'"';
				              }else{
				                 $sql.=' OR elemento.nombre="'.$p.'"';   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}else{
				 $validar1=1;
			}


    	$p1=$this->model_herramientas->personaje_produccion_sql($id_produccion,$sql);
    	$validar1=0;
    	if(!$p1){
          $validar1=1;
    	}
    	$p2=$this->model_herramientas->personaje_produccion($id_produccion,$personaje2);
    	$validar2=0;
    	if(!$p2){
    	  $validar2=1;	
    	}
		
		$validar3=0;
       
       $actor1='';
       if($p1){
	       	foreach ($p1 as $p) {
	       		$actor1.=$p['actor_nombre'].''.$p['actor_apellido'].',';
	       	}
	    }
		$actor2=$p2['0']->actor_nombre.''.$p2['0']->actor_apellido;
		if($actor2 and $actor1){
		  $pos = strpos($actor1, $actor2);	
		  if($actor1!=null and $actor2!=null and $pos === false){
	    	  $validar3=1;	
	      }
		}
		

		
   
		//$actor1=$p1['0']->actor_nombre.''.$p1['0']->actor_apellido;
		//$actor2=$p2['0']->actor_nombre.''.$p2['0']->actor_apellido;
    	
    	
    	if($validar1==0 and $validar2==0 and $validar3==0){
    		/*BLOQUE LOG USUARIO*/
			//$personaje_org = $this->model_elementos->buscar_elemento_id($p1['0']->id);
			if($personaje1){
			        $per=$_POST['personaje1'];
			        $per=explode(',', $per);
			        $sql=' (';
			        $cont=0;
			        foreach ($per as $p) {
			            if($p){
				              if($cont==0){
				                 $sql.=' ele.nombre="'.$p.'"';
				              }else{
				                 $sql.=' OR ele.nombre="'.$p.'"';   
				              }
			              $cont++;
			            }  
	      			  }
	      			  $sql.=' )';
				//$sql .= " AND escena.id_set = ".$set_busqueda; 
			}

            $personaje_org = $this->model_herramientas->buscar_elemento_sql($sql);
            $actores_cambios='';
			if($personaje_org){
			       	foreach ($personaje_org as $p) {
			       		$personaje_org.=$p['nombre'].' ('.$p['rol_final'].'),';
			       	}
			    }
			$personaje_mod = $this->model_elementos->buscar_elemento_id($p2['0']->id);
			$cadena = "\n" . " PERSONAJES " . $personaje_org . ' REMPLAZADO Y UNCIFICADO POR  '. $personaje_mod[0]->nombre. '('. $personaje_mod[0]->rol_final .')' ;
			$this->user_log($id_produccion,$cadena);
			$personaje_org = $this->model_herramientas->buscar_elemento_sql($sql);
            
            if($personaje_org){
            	$tam=count($personaje_org);
            	$cont=1;
            	$sql=' (';
            	foreach ($personaje_org as $p) {
            		if($cont!=$tam){
                       $sql.='id_elemento='.$p['idelemento'].'  OR ';
            		}else{
                       $sql.='id_elemento='.$p['idelemento'].')';
            		}
            		$cont++;
            		
            	}
            }
			$cantidad_escenas=$this->model_herramientas->escenas_has_elementos_sql($sql);
			//echo $this->db->last_query();
			$this->model_herramientas->update_escenas_has_elemtento($p2['0']->id,$sql);
			$cantidad_continuidad=$this->model_herramientas->elementos_continuidad($sql);
			$this->model_herramientas->update_elemtento_cont($p2['0']->id,$sql);

			if($personaje_org){
            	$tam=count($personaje_org);
            	$cont=1;
            	$sql=' (';
            	foreach ($personaje_org as $p) {
            		if($cont!=$tam){
                       $sql.='id='.$p['idelemento'].'  OR ';
            		}else{
                       $sql.='id='.$p['idelemento'].')';
            		}
            		$cont++;
            		
            	}
            }
            $this->model_herramientas->eliminar_elemento($sql);
			$data['validar1']=$validar1;
			$data['validar2']=$validar2;
			$data['validar3']=$validar3;
			$data['cantidad_escenas']=$cantidad_escenas;
			$data['cantidad_continuidad']=$cantidad_continuidad;

			

			echo json_encode($data);
    	}else{
    	  $data['validar1']=$validar1;
    	  $data['validar2']=$validar2;
    	  $data['validar3']=$validar3;
    	  echo json_encode($data); 	
    	}
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

    public function eliminar_fotos_produccion($id_produccion){
    	$produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    	if($produccion){
    		if($produccion['0']->imagen_produccion){
    	          $img=$produccion['0']->imagen_produccion;
    	         /// echo base_url($img);
    	          if(file_exists($img)){
    	          	echo $img;
                     unlink($img);
    	          }
    		}
    	}

    	$continuidad=$this->model_herramientas->fotos_continuidad_escenas_has_elementos($id_produccion);
    	if($continuidad){
    		foreach ($continuidad as $c) {
    			if($c['imagen']){
    				 $img=$c['imagen'];
	    	          if(file_exists($img)){
	                     unlink($img);
	    	          }
    			}
    		}
    	}

    	$continuidad=$this->model_herramientas->fotos_continuidad_elementos($id_produccion);
    	if($continuidad){
    		foreach ($continuidad as $c) {
    			if($c['imagen']){
    				$img=$c['imagen'];
	    	          if(file_exists($img)){
	                     unlink($img);
	    	          }	
    			}
    		}
    	}

    	$continuidad=$this->model_herramientas->fotos_continuidad_sets($id_produccion);
    	if($continuidad){
    		foreach ($continuidad as $c) {
    			if($c['imagen']){
    				$img=$c['imagen'];
	    	          if(file_exists($img)){
	                     unlink($img);
	    	          }
    			}
    		}
    	}

    	redirect ($this->lang->lang().'/herramientas/index/'.$id_produccion);
    	
    }

}