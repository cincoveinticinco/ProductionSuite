<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_dashboard');
	    $this->load->model('model_plan_produccion');
	    $this->load->model('model_capitulos');
	    $this->load->model('model_escenas_2');
	    $this->load->model('model_post_produccion');
	    $this->load->model('model_admin');
	    $this->load->model('model_pdf');
	    $this->load->model('model_herramientas');
	     $this->_logeo_in();
	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/login_dashboard');
      }
    }
	


	public function index(){
	    $tipo=$this->session->userdata('tipo_pruduction_suite');
	    if($tipo==1 or $tipo==3 or $tipo==2 OR $tipo==8){
	      $sql=" WHERE p.estado = 1";
		  $produccion=$this->model_produccion->producciones_all($sql);
	    }else{
	      $id_user = $this->session->userdata('id_pruduction_suite');
	      $user=$this->model_admin->rolUserId($id_user);
	      $tipo_rol=$user['0']['id_rol_otros'];
	      
	      $continuar=0;
	      if($user){
	        foreach ($user as $u) {
	          if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 2){
	            $continuar=1;
	            break;
	          }
	        }
	      }


	   
	      if($tipo==4 or $continuar==1){ 
	        $produccion=$this->model_produccion->producciones_user($id_user);
	      }else{
	        redirect($this->lang->lang().'/login/disconnecUser');
	      }
	    }
	   
	     $data['produccion'] = $produccion;
	     $data['view']='dashboard/index';
	     $this->load->view('includes/template_app',$data);
  }

	public function produccion($id){

		$tipo=$this->session->userdata('tipo_pruduction_suite');
	    if($tipo==1 or $tipo==3 or $tipo==2 OR $tipo==8 OR $tipo==4){
	     $continuar=1;
	    }else{
	      $id_user = $this->session->userdata('id_pruduction_suite');
	      $user=$this->model_admin->rolUserId($id_user);
	      $tipo_rol=$user['0']['id_rol_otros'];
	      
	      $continuar=0;
	      if($user){
		        foreach ($user as $u) {
			          if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6){
			            $continuar=1;
			            break;
			          }
		        }
	      }
	    }  


   if($continuar==1){
   			 $capitulox = $this->model_capitulos->capitulos_produccion_limit2($id,"");
			 $datos_produccion=$this->model_plan_produccion->produccion_id($id);
			 $produccion=$this->model_dashboard->produccion($id);
			 $producciones=$this->model_plan_produccion->produccion_id($id);
			 $unidades=$this->model_plan_produccion->unidades_id_produccion_4($id);
			 $fecha_actual=date('Y-m-d');
			 
			 $detalle_semana_actual=$this->model_dashboard->detalle_semana_actual($id,$fecha_actual);
			 $capitulos = $this->model_capitulos->contar_capitulos_escritos($id);
			 $acumulado=$this->model_dashboard->acumulado($id,$fecha_actual);
			 $comparativos=$this->model_dashboard->comparativos($id);
			 $capitulos_editados=$this->model_dashboard->capitulos_editados($id);
			 $ultimo_capitulos_entregado=$this->model_dashboard->ultimo_capitulos_entregado($id);
			 $limit_semanas=$this->model_dashboard->limit_semanas($id);
			 $limit_semanas=intval($limit_semanas['0']->total/10);
			 $limit_semanas=$limit_semanas*10;
			 $semanas=$this->model_dashboard->semanas_cargar($id,$limit_semanas,$limit_semanas);

		     $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($id);
		     $capitulos_total= $this->model_capitulos->contar_capitulos_escritos($id);
		     $cont=1;
		     $acu_min_real=0;
		     $acu_seg_real=0;
		     $semana='';
		     $minutos_proyectados='';
		     $minutos_produccidos='';
		     $capitulos_programados='';
		     $capitulos_entregados='';
		     $total_escenas_programadas='';
		     $total_escenas_producidas='';
		     $acu_entre=0;

		     
			 foreach ($semanas as $s) {
				 $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
			             for ($z=0; $z < count($unidades); $z++) { 
				               $acu_min_uni=0;$acu_seg_uni=0;
					              for ($y=0; $y<count($escenas_producidas); ++$y) {
						                if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($s['fecha_inicio_semana'])  
						                AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($s['fecha_fin_semana'])
						                AND $unidades[$z]['id'] == $escenas_producidas[$y]->id_unidad){
						                	$acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
					                        $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
					                        $acu_min_uni_sem+=$escenas_producidas[$y]->duracion_real_minutos;
					                        $acu_seg_uni_sem+=$escenas_producidas[$y]->duracion_real_segundos;
					                        $acu_min_real+=$escenas_producidas[$y]->duracion_real_minutos;   
					                        $acu_seg_real+=$escenas_producidas[$y]->duracion_real_segundos; 
						                }
					               
				             	  }
				              $this->calculo_tiempo($acu_min_uni,$acu_seg_uni);

			              } 

			               $inicio_semana= date("d-M-Y",strtotime($s['fecha_inicio_semana']));
			               $fin_semana= date("d-M-Y",strtotime($s['fecha_fin_semana']));
			               $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
			               $inicio_s=explode('-', $inicio_semana);
			               $fin_s=explode('-', $fin_semana);
			               $semana= $semana.'"'.$inicio_s['0'].'-'.$inicio_s['1'].'",';
			               $fecha_inicio_unidad=$unidades['0']['fecha_inicio'];
			               if($fecha_inicio_unidad<=$s['fecha_inicio_semana']){
			               	if($s['segundos_proyectados']>=30){
			               		$s['minutos_proyectados'] = $s['minutos_proyectados']+1;
			               	}
			                  $minutos_proyectados= $minutos_proyectados.''.$s['minutos_proyectados'].',';	
			               }else{
			               	$minutos_proyectados= $minutos_proyectados.'0,';	
			               }
			               $d=explode(':',$dif_min_real);
			               if($d['1']>=30){
			               		$d['0']+=1;
			               }
			               if($detalle_semana_actual){
				               if($s['fecha_fin_semana']<=$detalle_semana_actual['0']->fecha_fin_semana){
				                  $minutos_produccidos= $minutos_produccidos.''.$d['0'].',';
				               }else{
			              		 
				               }
				            }else{
				            	$minutos_produccidos= $minutos_produccidos.' ,';
				            }   
			               $total_capitulos_entregados=$this->calculo_tiempo($acu_min_real,$acu_seg_real);
			               $capitulos_programados=$capitulos_programados.''.$s['capitulos_programados'].',';
		                   
		                   $escenas_programadas=$this->model_dashboard->escenas_programadas($id,$s['fecha_inicio_semana'],$s['fecha_fin_semana']);
		                   //echo $this->db->last_query().'<br>';
		                   //echo $escenas_programadas.' '.$s['fecha_inicio_semana'].'--'.$s['fecha_fin_semana'].'<br>';
		                   $total_escenas_programadas.=$escenas_programadas.' ,';
		                   
		                   if($detalle_semana_actual){
			                   if($s['fecha_fin_semana']<=$detalle_semana_actual['0']->fecha_fin_semana){
			                  	$escenas_producidas_semana=$this->model_dashboard->escenas_producidas($id,$s['fecha_inicio_semana'],$s['fecha_fin_semana']);
			                   	$total_escenas_producidas.=$escenas_producidas_semana.' ,';

				               	}else{
				          		 $total_escenas_producidas= $total_escenas_producidas.' ,';
				               	}
		                   }else{
		                   	$total_escenas_producidas= $total_escenas_producidas.' ,';
		                   }

			               $cont++;

						$contador=0;
						$contador_desg=0;
						foreach ($capitulos_total as $capitulo) {
							if(strtotime($capitulo->fecha_entregado) >= strtotime($s['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($s['fecha_fin_semana'])){
								$contador++;
							}
						}
						$capitulos_entregados=$capitulos_entregados.''.$contador.',';

			 }
			 foreach ($capitulos_total as $capitulo) {
				if($capitulo->fecha_entregado){
					$acu_entre++;
				}
			 }
			 $resumen_indicadores=$this->model_dashboard->resumen_indicadores($id,0);
			 $capitulos_resumen='';
			 $locacion_resumen='';
			 $estudio_resumen='';
			 $interior_locacion='';
			 $interior_estudio='';
			 $exterior_locacion='';
			 $exterior_estudio='';
			 $dia_locacion='';
			 $dia_estudio='';
			 $noche_locacion='';
			 $noche_estudio='';
			 $cont=0;
		    	 if($resumen_indicadores){
				 foreach ($resumen_indicadores as $r) {
				 	$capitulos_resumen= $capitulos_resumen.'"'.$r['numero'].'",';
					$locacion=$this->model_dashboard->resumen_indicadores_locacion($id,$r['id_capitulo']);
					if($locacion){
		                $locacion_resumen=$locacion_resumen.''.$locacion['0']->locacion.',';
					}else{
		                $locacion_resumen=$locacion_resumen.'0,';
					}
					
					$estudio=$this->model_dashboard->resumen_indicadores_estudio($id,$r['id_capitulo']);
					if($estudio){
		               $estudio_resumen= $estudio_resumen.''.$estudio['0']->estudio.',';
					}else{
						$estudio_resumen= $estudio_resumen.'0,';
					}	

					$int_loca=$this->model_dashboard->resumen_indicadores_interior_locacion($id,$r['id_capitulo']);
					if($int_loca){
		               $interior_locacion=$interior_locacion.''.$int_loca['0']->interior_locacion.',';
					}else{
					   $interior_locacion=$interior_locacion.'0,';
				    }

					$int_est=$this->model_dashboard->resumen_indicadores_interior_estudio($id,$r['id_capitulo']);
					if($int_est){
		               $interior_estudio=$interior_estudio.''.$int_est['0']->interior_estudio.',';
					}else{
		               $interior_estudio=$interior_estudio.'0,';
					}

					$ext_loca=$this->model_dashboard->resumen_indicadores_exterior_locacion($id,$r['id_capitulo']);
					if($ext_loca){
		                 $exterior_locacion=$exterior_locacion.''.$ext_loca['0']->exterior_locacion.',';
		            }else{
		            	$exterior_locacion=$exterior_locacion.'0,';
		            }

					$ext_est=$this->model_dashboard->resumen_indicadores_exterios_estudio($id,$r['id_capitulo']);
					if($ext_est){
		               $exterior_estudio=$exterior_estudio.''.$ext_est['0']->exterios_estudio.',';
		            }else{
		               $exterior_estudio=$exterior_estudio.'0,';
		            }

					$dia_loc=$this->model_dashboard->resumen_indicadores_locacion_dia($id,$r['id_capitulo']);
					if($dia_loc){
		                $dia_locacion=$dia_locacion.''.$dia_loc['0']->locacion_dia.',';
					}else{
		                 $dia_locacion=$dia_locacion.'0,';
					}

					$dia_estu=$this->model_dashboard->resumen_indicadores_estudio_dia($id,$r['id_capitulo']);
					if($dia_estu){
		              $dia_estudio=$dia_estudio.''.$dia_estu['0']->estudio_dia.',';
					}else{
					 $dia_estudio=$dia_estudio.'0,';
				    }

					$noc_loca=$this->model_dashboard->resumen_indicadores_locacion_noche($id,$r['id_capitulo']);
					if($noc_loca){
		               $noche_locacion=$noche_locacion.''.$noc_loca['0']->locacion_noche.',';
					}else{
		               $noche_locacion=$noche_locacion.'0,';
					}

					$noc_est=$this->model_dashboard->resumen_indicadores_estudio_noche($id,$r['id_capitulo']);
					if($noc_est){
		               $noche_estudio=$noche_estudio.''.$noc_est['0']->estudio_noche.',';
					}else{
		               $noche_estudio=$noche_estudio.'0,';
					}

					$cont++; 
				 }
				
			 } 
			 $minutos_e=explode(':',$total_capitulos_entregados);
			 if($minutos_e[1]>=30){
			 	$minutos_e[0]+=1;
			 }
			 $datos_produccion=$this->model_plan_produccion->produccion_id($id);
			 $capitulos_detallados=$this->model_capitulos->capitulos_produccion($id);
			$protagonistas=0;
			$figurante=0;
			$reparto=0;
			$extra=0;
			$total_personajes=0;
			$total_locaciones=0;
			$vehiculos_desglosados=0;
			$pequenos=0;
			$medianos=0;
			$grandes=0;
			$protagonistas=$this->model_capitulos->protagonistas_capitulos($id);
			$protagonistas=$protagonistas['0']->total;
			 if($capitulos_detallados){
			    foreach ($capitulos_detallados as $c) {
			    	   $vehiculos = $this->model_capitulos->vehiculos_capitulo($c['id_capitulo']);
		          	   $vehiculos_desglosados=$vehiculos_desglosados+$vehiculos[0]['vehiculos_desglosados'];
		          	   $vehiculos_desglosados=$vehiculos_desglosados+$vehiculos[0]['vehiculo_background'];
			    	   	  $total_locaciones=$total_locaciones+$c['cantidad_locaciones'];
			    	   	
					     $monto_figurante_extra = 0;
					     $monto_figurante_extra_dolar = 0;

				          if($produccion['0']->monto_figurante_extra){
				           		$monto_figurante_extra = $produccion['0']->monto_figurante_extra;
				          }
				          if($produccion['0']->monto_figurante_extra_dolar){
				           		$monto_figurante_extra_dolar = $produccion['0']->monto_figurante_extra_dolar;
				          }
				         $f = $this->model_capitulos->figurante_capitulos($c['id_capitulo'],$monto_figurante_extra,$monto_figurante_extra_dolar);
				        // echo $this->db->last_query().'<br>';
				         
						 if($f){
						 	//echo $f['0']->total.'--'.$c['id_capitulo'].'<br>';
					        $figurante=$figurante+$f['0']->total;    
				         }
				         $r = $this->model_capitulos->reparto_capitulos($c['id_capitulo'],$monto_figurante_extra,$monto_figurante_extra_dolar);
				         //echo $this->db->last_query().'<br>';
						 if($r){
					        $reparto=$reparto+$r['0']->total;
				         }

				         $extras_t = $this->model_capitulos->extras_capitulos($c['id_capitulo']);

				          if($extras_t){
				            foreach ($extras_t as $e) {
				              $extra=$extra+$e['cantidad'];  
				            }
				          }
				        
						

			    	   }

						
			 //	}	
			 }


		     $pequenos=$this->model_dashboard->total_evento_produccion($id,2);
			 $medianos=$this->model_dashboard->total_evento_produccion($id,3);
			 $grandes=$this->model_dashboard->total_evento_produccion($id,4);

		     $capitulos_produccion=$this->model_post_produccion->capitulos_escenas($id,null,10);
		     $capitulo_post='';
		     $capitulo_tiempo_post='';
		     $capitulo_flas='';
		     $stab='';
		     $otros='';
		     $estados_cap='';
		     $min_estimados_cap='';
		     $min_real_cap='';
		     $min_post_cap='';

		     /////////////////////////
		     $otros_graf='';
		     
		     /////////////////////////
		     if($capitulos_produccion){
		     	foreach ($capitulos_produccion as $c) {
		     		  $capitulo_post=$capitulo_post.$c['numero'].',';
		     		  if($c['minutos_post']){

		     		  	//$t=$this->calculo_tiempo($c['minutos_post'],$c['segundos_post']);
		     		  	$t=$this->calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']);
		     		  	$tem=explode(':',$t);
		     		    $capitulo_tiempo_post=$capitulo_tiempo_post.$tem['0'].',';	
		     		  }else{
		     		  	$capitulo_tiempo_post=$capitulo_tiempo_post.'0,';	
		     		  }

		     		  ///////////Nueovos Intem///////////
		     		  if($c['credito_minutos'] or $c['credito_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['credito_minutos'],$c['credito_segundos'],$c['credito_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';	
		     		  }

		     		  if($c['flashback_minutos'] or $c['flashback_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['flashback_minutos'],$c['flashback_segundos'],$c['flashback_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	$otros_graf.='00:00-';		
		     		  }

		     		  if($c['transiciones_minutos'] or $c['transiciones_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['transiciones_minutos'],$c['transiciones_segundos'],$c['transiciones_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['cortinillas_minutos'] or $c['cortinillas_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['cortinillas_minutos'],$c['cortinillas_segundos'],$c['cortinillas_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['cabezote_minutos'] or $c['cabezote_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['cabezote_minutos'],$c['cabezote_segundos'],$c['cabezote_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['recap_minutos'] or $c['recap_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['recap_minutos'],$c['recap_segundos'],$c['recap_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['presentacion_minutos'] or $c['presentacion_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['presentacion_minutos'],$c['presentacion_segundos'],$c['presentacion_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['foto_minutos'] or $c['foto_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['foto_minutos'],$c['foto_segundos'],$c['foto_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }
                      
                      if($c['imagenes_archivos_minutos'] or $c['imagenes_archivos_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['imagenes_archivos_minutos'],$c['imagenes_archivos_segundos'],$c['imagenes_archivos_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  $otros_graf.='*';	
		     		  
		     		  //////////////////////////////////
   
		     		  if($c['despedida_minutos'] or $c['despedida_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['despedida_minutos'],$c['despedida_segundos'],$c['despedida_cuadros']);
		     		  	$tem=explode(':',$t);
		     		    $capitulo_flas=$capitulo_flas.$tem['0'].',';	
		     		  }else{
		     		  	$capitulo_flas=$capitulo_flas.'0,';	
		     		  }
		     		 
		     		  $m=$c['transiciones_minutos']+$c['stab_minutos']+$c['flashback_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos']+$c['foto_minutos'];
		              $s=$c['transiciones_segundos']+$c['stab_segundos']+$c['flashback_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos']+$c['foto_segundos'];
		              $cuadros=$c['transiciones_cuadros']+$c['stab_cuadros']+$c['flashback_cuadros']+$c['recap_cuadros']+$c['stab_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros']+$c['foto_cuadros']; 
		              
		              if($m or $s){
		              	$tiempo=$this->calculo_tiempo_post($m,$s,$cuadros);
		     		  	$tem=explode(':',$tiempo);
		     		    $otros=$otros.$tem['0'].',';	
		     		  }else{
		     		  	$otros=$otros.'0,';	
		     		  }
		     		  if($c['id_estado']==1){
		     		  	$estado=0;
		     		  }else{
		     		  	$estado=$c['id_estado'];
		     		  }
		     		  $por=(100*$estado)/15;
		     		  $por=round($por);
		     		  $estados_cap=$estados_cap.$por.',';
		              
		        
		     		  if($c['minutos_estimados'] or $c['segundos_estimados']){

		     		  	$t=$this->calculo_tiempo($c['minutos_estimados'],$c['segundos_estimados']);
		     		  	$tem=explode(':',$t);
		     		    $min_estimados_cap=$min_estimados_cap.$tem['0'].',';	
		     		  }else{
		     		  	$min_estimados_cap=$min_estimados_cap.'0,';	
		     		  }	

		     		  if($c['minutos'] or $c['segundos']){
		     		  	$t=$this->calculo_tiempo($c['minutos'],$c['segundos']);
		     		  	$tem=explode(':',$t);
		     		    $min_real_cap=$min_real_cap.$tem['0'].',';	
		     		  }else{
		     		  	$min_real_cap=$min_real_cap.'0,';	
		     		  }	

		     		  if($c['minutos_post'] or $c['segundos_post']){
		     		  	$t=$this->calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']);
		     		  	$tem=explode(':',$t);
		     		    $min_post_cap=$min_post_cap.$tem['0'].',';	
		     		  }else{
		     		  	$min_post_cap=$min_post_cap.'0,';	
		     		  }	

		     	}
		     }


			 if($detalle_semana_actual){
				$capitulos_entregados2=$this->model_plan_produccion->capitulos_entregados($id,$detalle_semana_actual['0']->fecha_inicio_semana,$detalle_semana_actual['0']->fecha_fin_semana);
				$acumulados_entregados_capitulos=$this->model_plan_produccion->capitulos_entregados_totales($id,$detalle_semana_actual['0']->fecha_fin_semana);
				$total_semanas_fecha=$this->model_plan_produccion->total_semanas_produccion_fecha($id,$detalle_semana_actual['0']->fecha_fin_semana);

			 }else{
			 	$capitulos_entregados2='';
			 	$ultima_semana=$this->model_dashboard->ultima_semana_produccion($id);
			 	$acumulados_entregados_capitulos=$this->model_plan_produccion->capitulos_entregados_totales($id,$ultima_semana['0']->fecha_fin_semana);
				$total_semanas_fecha=$this->model_plan_produccion->total_semanas_produccion_fecha($id,$ultima_semana['0']->fecha_fin_semana);
		
			 }		


			 $data['capitulos_detallados']=$capitulos_detallados;
			 $data['limit_semanas']=$limit_semanas;
		     $data['unidades']=$unidades;
		     $data['datos_produccion']=$datos_produccion;
		     $data['produccion']=$produccion;
		     $data['producciones']=$producciones;
		     $data['id_produccion']=$id;
		     $data['detalle_semana_actual']=$detalle_semana_actual;
		     $data['capitulos']=$capitulos;
		     $data['capitulox']=$capitulox;
		     $data['acumulado']=$acumulado;
		     $data['comparativos']=$comparativos;
		     $data['capitulos_editados']=$capitulos_editados;
		     $data['ultimo_capitulos_entregado']=$ultimo_capitulos_entregado;

		     $data['semanas']=$semana;
		     $data['minutos_proyectados']=$minutos_proyectados;
		     $data['minutos_produccidos']=$minutos_produccidos;
		     $data['datos_produccion']=$datos_produccion;
		     $data['total_capitulos_entregados']=$minutos_e[0];
		     $data['capitulos_programados']=$capitulos_programados;
		     $data['capitulos_entregados']=$capitulos_entregados;
		     $data['capitulos_entregados2']=$capitulos_entregados2;
		     $data['acu_entre']=$acu_entre;
		     $data['capitulos_resumen']=$capitulos_resumen;
		     $data['locacion_resumen']=$locacion_resumen;
		     $data['estudio_resumen']=$estudio_resumen;
		     $data['interior_locacion']=$interior_locacion;
		     $data['interior_estudio']=$interior_estudio;
		     $data['exterior_locacion']=$exterior_locacion;
		     $data['exterior_estudio']=$exterior_estudio;
		     $data['dia_locacion']=$dia_locacion;
		     $data['dia_estudio']=$dia_estudio;
		     $data['noche_locacion']=$noche_locacion;
		     $data['noche_estudio']=$noche_estudio;
		     $data['protagonistas']=$protagonistas;
		     $data['figurante']=$figurante;
		     $data['reparto']=$reparto;
		     $data['extra']=$extra;
		     $data['total_locaciones']=$total_locaciones;
		     $data['vehiculos_desglosados']=$vehiculos_desglosados;
		     $data['pequenos']=$pequenos;
		     $data['medianos']=$medianos;
		     $data['grandes']=$grandes;
		     $data['capitulos_entregados']=$capitulos_entregados;
		     $data['acumulados_entregados_capitulos']=$acumulados_entregados_capitulos;
		     $data['total_semanas_fecha']=$total_semanas_fecha;
		     $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($id);
		     $unidad=$this->model_plan_produccion->unidades_id_produccion($id);

		      $acu_min_uni_sem=0;$acu_seg_uni_sem=0;$acum_unida_minuto=0;$acum_unida_segundos=0;
		                      for ($z=0; $z < count($unidad); $z++) { 
		                       $acu_min_uni=0;$acu_seg_uni=0;
			                      for ($y=0; $y<count($escenas_producidas); ++$y) {
			                      	 if($detalle_semana_actual){
				                        if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($detalle_semana_actual['0']->fecha_inicio_semana)  
				                        AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($detalle_semana_actual['0']->fecha_fin_semana)
				                        AND $unidad[$z]['id'] == $escenas_producidas[$y]->id_unidad){
				                          $acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
				                          $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
				                          $acu_min_uni_sem+=$escenas_producidas[$y]->duracion_real_minutos;
				                          $acu_seg_uni_sem+=$escenas_producidas[$y]->duracion_real_segundos;
				                          $acu_min_real+=$escenas_producidas[$y]->duracion_real_minutos;   
				                          $acu_seg_real+=$escenas_producidas[$y]->duracion_real_segundos;  
				                        }
				                     }   
				                         
			                      }

		                        $acum_unida_minuto+=$acu_min_uni;
		                        $acum_unida_segundos+=$acu_seg_uni;
		                       } 
		     $acum_semana=$this->calculo_tiempo2($acum_unida_minuto,$acum_unida_segundos);                       
		     $data['acum_semana']=$acum_semana;
		     $acumunaldos_semana=$this->model_dashboard->acumunaldos_semana($id);  
		     $acumulado_minutos_totales=$this->calculo_tiempo($acumunaldos_semana['0']->minutos_reales,$acumunaldos_semana['0']->segundos_reales);  
		     $acumulado_minutos=explode(":", $acumulado_minutos_totales);
		     $minutos=$acumulado_minutos['0'];
		     $diferencia_acumulado_minutos=($minutos+1)-$acumunaldos_semana['0']->total_minutos;
		     $diferencia_acumulado_segudos=60-$acumulado_minutos['1'];
		     $diferencia_acumulado_minutos.':'.$diferencia_acumulado_segudos;
		     $data['acumulado_minutos_totales']=$acumulado_minutos_totales;
		     $data['acumulado_minutos']=$acumunaldos_semana['0']->total_minutos;
		     $data['acumulado_diferencia']=$diferencia_acumulado_minutos.':'.$diferencia_acumulado_segudos;
		     
		     $data['capitulos_estatus']=$this->model_dashboard->estatus_capitulos($id);

		     $data['capitulo_post']=$capitulo_post;
		     $data['capitulo_tiempo_post']=$capitulo_tiempo_post;
		     $data['capitulo_flas']=$capitulo_flas;
		     $data['stab']=$stab;
		     $data['otros']=$otros;
		     $data['estados_cap']=$estados_cap;
		     $data['min_estimados_cap']=$min_estimados_cap;
		     $data['min_real_cap']=$min_real_cap;
		     $data['min_post_cap']=$min_post_cap;
		     $data['total_escenas_programadas']=$total_escenas_programadas;
		     $data['total_escenas_producidas']=$total_escenas_producidas;


		     $data['otros_graf']=$otros_graf;
		      

		     

		     /*ORDEN WIDGETS USUARIO*/
		     $data['lista_widgets'] = $this->lista_widgets();
		     $widgets_usuario = $this->model_dashboard->widgets_usuario($this->session->userdata('id_pruduction_suite'));
		     if($widgets_usuario==false){
		     	$widgets_usuario = $this->lista_widgets();
		     }else{
			    $widgets_usuario = explode(',', $widgets_usuario[0]->campos);
		     }
		     $data['widgets_usuario'] = $widgets_usuario;
		     /*FIN ORDEN WIDGETS USUARIO*/
		     $data['view']='dashboard/produccion';  
		     $this->load->view('includes/template_app',$data);
	}else{

		 $id_user = $this->session->userdata('id_pruduction_suite');
	      $user=$this->model_admin->rolUserId($id_user);
	      $tipo_rol=$user['0']['id_rol_otros'];
	      if($user){
		        foreach ($user as $u) {
			          if($u['id_rol_otros']== 2){
			            redirect($this->lang->lang().'/dashboard/caja_colores/'.$id);
			            break;
			          }
		        }
	      }

	}
}

function guardar_widgets(){
	$campos = $_POST['cadena'];
	$id_user = $this->session->userdata('id_pruduction_suite');
	$data=array('id_usuario'=>$id_user,'campos'=>$campos,'tipo'=>3);
    $existe = $this->model_dashboard->widgets_usuario($id_user);
    if($existe!=false){
      $this->model_dashboard->actualizar_orden_widgets($data);
    }else{
      $this->model_dashboard->agregar_orden_widgets($data);
    }
    echo json_encode(1);
}

function lista_widgets(){
	$lista_widgets[0]='indicadores proyectados';
	$lista_widgets[1]='detalles produccion';
	$lista_widgets[2]='detalle diario';
	$lista_widgets[3]='detalle semanal y acumulado';
	$lista_widgets[4]='graficas comparativas del proyecto';
	$lista_widgets[5]='minutos proyectados vs minutos producidos semanales';
	$lista_widgets[6]='libretos proyectados vs entregados';
	$lista_widgets[7]='resumen indicadores libreto';
	$lista_widgets[8]='detalles post-produccion';
	$lista_widgets[9]='detalles indicadores';
	$lista_widgets[10]='presupuesto';	
	
	//$lista_widgets[10]="indicadores";
	return $lista_widgets;
}

 function calculo_tiempo($minutos,$segundos){
    $minutos2=0;
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 += $minutos;

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

  public static function calculo_tiempo2($minutos,$segundos){
    $minutos2=0;
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 += $minutos;

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


  public function cargar_semanas_minutos(){
    $id_produccion=$_POST['id_produccion'];
  	$cantidad=$_POST['cantidad'];
  	$limit1=$cantidad;
  	$limit2=$cantidad+10;
  	$unidades=$this->model_plan_produccion->unidades_id_produccion($id_produccion);
    $semanas=$this->model_dashboard->semanas_cargar($id_produccion,$limit1,$limit1);
    $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($id_produccion);
    $fecha_actual=date('Y-m-d');
	$detalle_semana_actual=$this->model_dashboard->detalle_semana_actual($id_produccion,$fecha_actual);
    $acu_min_real=0;
    $acu_seg_real=0;
	$capitulos_resumen='';
	$locacion_resumen='';
	$estudio_resumen='';
	$interior_locacion='';
	$interior_estudio='';
	$exterior_locacion='';
	$exterior_estudio='';
	$dia_locacion='';
	$dia_estudio='';
	$noche_locacion='';
	$noche_estudio='';
	$semana='';
	$minutos_proyectados='';
	$minutos_produccidos='';
	$cont=0;
	if($semanas){
		foreach ($semanas as $s) {
			$acu_min_uni_sem=0;$acu_seg_uni_sem=0;
	            for ($z=0; $z < count($unidades); $z++) { 
	                $acu_min_uni=0;$acu_seg_uni=0;
	                for ($y=0; $y<count($escenas_producidas); ++$y) {
		                if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($s['fecha_inicio_semana'])  
		                AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($s['fecha_fin_semana'])
		                AND $unidades[$z]['id'] == $escenas_producidas[$y]->id_unidad){
		                  	$acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
		                    $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
		                    $acu_min_uni_sem+=$escenas_producidas[$y]->duracion_real_minutos;
		                    $acu_seg_uni_sem+=$escenas_producidas[$y]->duracion_real_segundos;
		                    $acu_min_real+=$escenas_producidas[$y]->duracion_real_minutos;   
		                    $acu_seg_real+=$escenas_producidas[$y]->duracion_real_segundos; 
		                }
	                }
	                $this->calculo_tiempo($acu_min_uni,$acu_seg_uni);
	            } 
	            $inicio_semana= date("d-M-Y",strtotime($s['fecha_inicio_semana']));
	            $fin_semana= date("d-M-Y",strtotime($s['fecha_fin_semana']));
	            $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
	            $inicio_s=explode('-', $inicio_semana);
	            $fin_s=explode('-', $fin_semana);
	            $semana= $semana.''.$inicio_s['0'].'-'.$inicio_s['1'].',';
	            $fecha_inicio_unidad=$unidades['0']['fecha_inicio'];
	               if($fecha_inicio_unidad<=$s['fecha_inicio_semana']){
	                  $minutos_proyectados= $minutos_proyectados.''.$s['minutos_proyectados'].',';	
	               }else{
	               	$minutos_proyectados= $minutos_proyectados.'0,';	
	               }
	            $d=explode(':',$dif_min_real);
	            if($d['1']>=30){
	            	$d['0']+=1;
	            }

	            if($detalle_semana_actual){
			            if($s['fecha_fin_semana']<=$detalle_semana_actual['0']->fecha_fin_semana){
		                  $minutos_produccidos= $minutos_produccidos.''.$d['0'].',';
		               }else{
		          		 $minutos_produccidos= $minutos_produccidos.' ,';
		               }
		        }else{
		        	$minutos_produccidos= $minutos_produccidos.' ,';
		        }       
	            $cont++;
		 }

		 $data['semana']=$semana;
		 $data['minutos_proyectados']=$minutos_proyectados;
		 $data['minutos_produccidos']=$minutos_produccidos;
		 $data['cont']=$cont;
		 $data['cantidad']=$limit2;
		 echo json_encode($data);
	}else{
		$data['cont']=0;
		echo json_encode($data);
	}
  }

  public function cargar_escenas(){
    $id_produccion=$_POST['id_produccion'];
  	$cantidad=$_POST['cantidad'];
  	$limit1=$cantidad;
  	$limit2=$cantidad+10;
  	$unidades=$this->model_plan_produccion->unidades_id_produccion($id_produccion);
    $semanas=$this->model_dashboard->semanas_cargar($id_produccion,$limit1,$limit1);
    $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($id_produccion);
    $fecha_actual=date('Y-m-d');
	$detalle_semana_actual=$this->model_dashboard->detalle_semana_actual($id_produccion,$fecha_actual);
    $acu_min_real=0;
    $acu_seg_real=0;
	$capitulos_resumen='';
	$locacion_resumen='';
	$estudio_resumen='';
	$interior_locacion='';
	$interior_estudio='';
	$exterior_locacion='';
	$exterior_estudio='';
	$dia_locacion='';
	$dia_estudio='';
	$noche_locacion='';
	$noche_estudio='';
	$semana='';
	$total_escenas_programadas='';
	$total_escenas_producidas='';
	$cont=0;
	if($semanas){
		foreach ($semanas as $s) {
			$acu_min_uni_sem=0;$acu_seg_uni_sem=0;
	            $inicio_semana= date("d-M-Y",strtotime($s['fecha_inicio_semana']));
	            $fin_semana= date("d-M-Y",strtotime($s['fecha_fin_semana']));
	            $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
	            $inicio_s=explode('-', $inicio_semana);
	            $fin_s=explode('-', $fin_semana);
	            $semana= $semana.''.$inicio_s['0'].'-'.$inicio_s['1'].',';
	            $fecha_inicio_unidad=$unidades['0']['fecha_inicio'];
	               
	           
	           
                $escenas_programadas=$this->model_dashboard->escenas_programadas($id_produccion,$s['fecha_inicio_semana'],$s['fecha_fin_semana']);
               //echo $escenas_programadas.' '.$s['fecha_inicio_semana'].'--'.$s['fecha_fin_semana'].'<br>';
                $total_escenas_programadas.=$escenas_programadas.' ,';

                if($detalle_semana_actual){
               
		               if($s['fecha_fin_semana']<=$detalle_semana_actual['0']->fecha_fin_semana){
		              	$escenas_producidas_semana=$this->model_dashboard->escenas_producidas($id_produccion,$s['fecha_inicio_semana'],$s['fecha_fin_semana']);
		               	$total_escenas_producidas.=$escenas_producidas_semana.' ,';
		               	}else{
		          		 $total_escenas_producidas= $total_escenas_producidas.' ,';
		               	}
                }else{
                	$total_escenas_producidas= $total_escenas_producidas.' ,';
                }

	            $cont++;
		 }

		 $data['semana']=$semana;
		 $data['total_escenas_programadas']=$total_escenas_programadas;
		 $data['total_escenas_producidas']=$total_escenas_producidas;
		 $data['cont']=$cont;
		 $data['cantidad']=$limit2;
		 echo json_encode($data);
	}else{
		$data['cont']=0;
		echo json_encode($data);
	}
  }


  public function cargar_libretos(){
  	$id_produccion=$_POST['id_produccion'];
  	$cantidad=$_POST['cantidad'];
  	$limit1=$cantidad;
  	$limit2=$cantidad+10;
	 $unidades=$this->model_plan_produccion->unidades_id_produccion($id_produccion);
	 $fecha_actual=date('Y-m-d');
	 //$capitulos = $this->model_capitulos->contar_capitulos_escritos($id_produccion);
	 //$acumulado=$this->model_dashboard->acumulado($id_produccion,$fecha_actual);
	 //$capitulos_sin_editar=$this->model_dashboard->capitulos_sin_editar($id_produccion);
	 //$capitulos_al_aire=$this->model_dashboard->capitulos_al_aire($id_produccion,$fecha_actual);
	 $semanas=$this->model_dashboard->semanas_cargar($id_produccion,$limit1,$limit1);

     $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($id_produccion);
     $capitulos_total= $this->model_capitulos->contar_capitulos_escritos($id_produccion);
     $cont=0;
     $acu_min_real=0;
     $acu_seg_real=0;
     $semana='';
     $minutos_proyectados='';
     $minutos_produccidos='';
     $capitulos_programados='';
     $capitulos_entregados='';
     $acu_entre=0;
     if($semanas){
			foreach ($semanas as $s) {
			$acu_min_uni_sem=0;$acu_seg_uni_sem=0;
			      for ($z=0; $z < count($unidades); $z++) { 
			       $acu_min_uni=0;$acu_seg_uni=0;
			      for ($y=0; $y<count($escenas_producidas); ++$y) {
			        if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($s['fecha_inicio_semana'])  
			        AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($s['fecha_fin_semana'])
			        AND $unidades[$z]['id'] == $escenas_producidas[$y]->id_unidad){
			          $acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
			          $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
			          $acu_min_uni_sem+=$acu_min_uni;
			          $acu_seg_uni_sem+=$acu_seg_uni;
			          $acu_min_real+=$acu_min_uni;   
			          $acu_seg_real+=$acu_seg_uni; 
			        }
			      }
			       $this->calculo_tiempo($acu_min_uni,$acu_seg_uni);

			      } 
			      $inicio_semana= date("d-M-Y",strtotime($s['fecha_inicio_semana']));
			      $fin_semana= date("d-M-Y",strtotime($s['fecha_fin_semana']));
			       $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
			       $inicio_s=explode('-', $inicio_semana);
			       $fin_s=explode('-', $fin_semana);
			       $semana= $semana.''.$inicio_s['0'].'-'.$inicio_s['1'].',';
			       $minutos_proyectados= $minutos_proyectados.''.$s['minutos_proyectados'].',';
			       $d=explode(':',$dif_min_real);
			       $minutos_produccidos= $minutos_produccidos.''.$d['0'].',';
			       $total_capitulos_entregados=$this->calculo_tiempo($acu_min_real,$acu_seg_real);
			       $capitulos_programados=$capitulos_programados.''.$s['capitulos_programados'].',';
			       $cont++;

				$contador=0;
				$contador_desg=0;
				foreach ($capitulos_total as $capitulo) {
					if(strtotime($capitulo->fecha_entregado) >= strtotime($s['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($s['fecha_fin_semana'])){
						$contador++;
					}
				}
				$capitulos_entregados=$capitulos_entregados.''.$contador.',';
				$acu_entre += $contador;

			}
	 	 $data['semana']=$semana;
		 $data['capitulos_programados']=$capitulos_programados;
		 $data['capitulos_entregados']=$capitulos_entregados;
		 $data['cont']=$cont;
		 $data['cantidad']=$limit2;
		 echo json_encode($data);
	}else{
		$data['cont']=0;
		echo json_encode($data);
	}

  }


  public function cargar_capitulos(){
  	$cantidad=$_POST['cantidad'];
  	$id_produccion=$_POST['id_produccion'];
  	$limite=$cantidad;
  	$capitulos_estatus = $this->model_dashboard->estatus_capitulos($id_produccion,$limite);
  	//echo $this->db->last_query();

	$numeros="";
	$array_1="";
	$array_2="";
	$array_3="";
	$array_4=""; 
	$array_5="";
	$array_6="";
	$array_7="";
	$array_8="";
	$array_9="";
	$array_10="";
	$array_11="";
	$array_12="";
	$array_13="";
	$array_14="";

	if($capitulos_estatus){
		foreach ($capitulos_estatus as $capitulo_estatus) {
		  	$numeros  .= $capitulo_estatus['numero'] .',';
		    $array_1  .= $capitulo_estatus['estado_1'] .',';
		    $array_2  .= $capitulo_estatus['estado_2'] .',';
		    $array_3  .= $capitulo_estatus['estado_3'] .',';
		    $array_4  .= $capitulo_estatus['estado_4'] .',';
		    $array_5  .= $capitulo_estatus['estado_5'] .',';
		    $array_6  .= $capitulo_estatus['estado_6'] .',';
		    $array_7  .= $capitulo_estatus['estado_7'] .',';
		    $array_8  .= $capitulo_estatus['estado_8'] .',';
		    $array_9  .= $capitulo_estatus['estado_9'] .',';
		    $array_10  .= $capitulo_estatus['estado_10'] .',';
		    $array_11  .= $capitulo_estatus['estado_11'] .',';
		    $array_12  .= $capitulo_estatus['estado_12'] .',';
		    $array_13  .= $capitulo_estatus['estado_13'] .',';
		    $array_14  .= $capitulo_estatus['estado_14'] .',';
		}

		$data['cont']=count($capitulos_estatus);
		$data['cantidad']=$limite;
		$data['numeros']=trim($numeros, ',');
		$data['cadena']=trim($array_1, ',').'_'.trim($array_2, ',').'_'.trim($array_3, ',').'_'.trim($array_4, ',').'_'.trim($array_5, ',').'_'.trim($array_6, ',').'_'.trim($array_7, ',').'_'.trim($array_8, ',').'_'.trim($array_9, ',').'_'.trim($array_10, ',').'_'.trim($array_11, ',').'_'.trim($array_12, ',').'_'.trim($array_13, ',').'_'.trim($array_14, ',');
		echo json_encode($data);
	}else{
		$data['cont']=0;
		echo json_encode($data);
	}
  }


	public function plan_produccion($id){
				$msg='';
			 $produccion=$this->model_plan_produccion->produccion_id($id);
			 $datos_produccion=$this->model_plan_produccion->produccion_id($id);
			 $centro_produccion=$this->model_produccion->centro_produccion();
		     $tipo_produccion=$this->model_produccion->tipo_produccion();
		     $user=$this->model_produccion->tipo_usuario();
		     $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
		     $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
		     $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
		     $director_unidad=$this->model_produccion->tipo_usuario_otros(5,7);
		     $script=$this->model_produccion->tipo_usuario_otros(5,1);
		     $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
		     $roles=$this->model_plan_produccion->rol_otros();
		     $user_has_produccion=$this->model_plan_produccion->user_has_roles_id(2);
		     $usuarios_producion=$this->model_plan_produccion->usuarios_produccion($produccion['0']->id_produccion);
		     $semanas_trabajo=$this->model_plan_produccion->semanas_trabajo($produccion['0']->id_produccion);
		     $semanas=$this->model_plan_produccion->total_semanas($id);
		     $cantidad_capitulos = $this->model_capitulos->contar_capitulos_escritos($produccion['0']->id_produccion);
	         $minutos_producidos = $this->sumar_minutos($produccion['0']->id_produccion);
	         $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($produccion['0']->id_produccion);
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
		     $data['capitulos'] = $cantidad_capitulos;
		     $data['minutos_producidos'] = $minutos_producidos;
		     $data['escenas_producidas'] = $escenas_producidas;
		     $data['datos_produccion'] = $datos_produccion;
		     $data['view']='dashboard/plan_produccion';
		     $this->load->view('includes/template_app',$data);

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

	public function caja_colores($id_produccion){
		$capitulos=$this->model_dashboard->capitulos($id_produccion);
		$datos_produccion=$this->model_plan_produccion->produccion_id($id_produccion);
		$estados_color = $this->model_escenas_2->estados_color();
		$data['estados_color'] = $estados_color;
		$data['capitulos']=$capitulos;
		$data['datos_produccion']=$datos_produccion;
		$data['view']='dashboard/caja_colores';
		$this->load->view('includes/template_app',$data);

	}

  public static	function tiempo_segundos($total){
    $total=$total;
    if($total>=0){
      $m_total=floor($total/60);
    }else{
      $m_total=ceil($total/60);
    }
    $segundos_2=$total%60;
      if(strlen($segundos_2)<2){
        $segundos_2 = '0'.$segundos_2;
      }
      if($segundos_2<=0){
        $segundos_2 =abs($segundos_2);
      }

      if($m_total>=0 and $m_total<10){
        $m_total='0'.$m_total;
      }
      if($m_total>-10 and $m_total<0){
        $m_total='0'.abs($m_total);
        $m_total='-'.$m_total;
      }
      if(strlen($segundos_2)<2){
        $segundos_2 = '0'.$segundos_2;
      }
      $m_total_dif=$m_total.':'.$segundos_2;
      return $m_total_dif;
  }

  public function locacionVSestudio(){
  	$id_produccion=$_POST['id_produccion'];
  	$limit=$_POST['limit'];
  	$resumen_indicadores=$this->model_dashboard->resumen_indicadores($id_produccion,$limit);
	 $capitulos_resumen='';
	 $locacion_resumen='';
	 $estudio_resumen='';
	 $cont=0;
     if($resumen_indicadores){
			 foreach ($resumen_indicadores as $r) {
			 	$capitulos_resumen= $capitulos_resumen.''.$r['numero'].',';
				$locacion=$this->model_dashboard->resumen_indicadores_locacion($id_produccion,$r['id_capitulo']);
					if($locacion){
		                $locacion_resumen=$locacion_resumen.''.$locacion['0']->locacion.',';
					}else{
		                $locacion_resumen=$locacion_resumen.'0,';
					}
					$estudio=$this->model_dashboard->resumen_indicadores_estudio($id_produccion,$r['id_capitulo']);
					if($estudio){
		               $estudio_resumen= $estudio_resumen.''.$estudio['0']->estudio.',';
					}else{
						$estudio_resumen= $estudio_resumen.'0,';
					}	
				$cont++; 
			 }
		$data['capitulos_resumen']=$capitulos_resumen;
	 	$data['locacion_resumen']=$locacion_resumen;
	 	$data['estudio_resumen']=$estudio_resumen;
	 	$data['tam']=$cont;
		echo json_encode($data);
	   }else{
	   	$data['tam']=$cont;
		echo json_encode($data);
	   }
  }

  public function intVSext(){
  	$id_produccion=$_POST['id_produccion'];
  	$limit=$_POST['limit'];
  	$resumen_indicadores=$this->model_dashboard->resumen_indicadores($id_produccion,$limit);
	 $capitulos_resumen='';
	 $interior_locacion='';
	 $interior_estudio='';
	 $exterior_locacion='';
	 $exterior_estudio='';
	 $cont=0;
     if($resumen_indicadores){
			 foreach ($resumen_indicadores as $r) {
			 	$capitulos_resumen= $capitulos_resumen.''.$r['numero'].',';
				$int_loca=$this->model_dashboard->resumen_indicadores_interior_locacion($id_produccion,$r['id_capitulo']);
					if($int_loca){
		               $interior_locacion=$interior_locacion.''.$int_loca['0']->interior_locacion.',';
					}else{
					   $interior_locacion=$interior_locacion.'0,';
				    }

					$int_est=$this->model_dashboard->resumen_indicadores_interior_estudio($id_produccion,$r['id_capitulo']);
					if($int_est){
		               $interior_estudio=$interior_estudio.''.$int_est['0']->interior_estudio.',';
					}else{
		               $interior_estudio=$interior_estudio.'0,';
					}

					$ext_loca=$this->model_dashboard->resumen_indicadores_exterior_locacion($id_produccion,$r['id_capitulo']);
					if($ext_loca){
		                 $exterior_locacion=$exterior_locacion.''.$ext_loca['0']->exterior_locacion.',';
		            }else{
		            	$exterior_locacion=$exterior_locacion.'0,';
		            }

					$ext_est=$this->model_dashboard->resumen_indicadores_exterios_estudio($id_produccion,$r['id_capitulo']);
					if($ext_est){
		               $exterior_estudio=$exterior_estudio.''.$ext_est['0']->exterios_estudio.',';
		            }else{
		               $exterior_estudio=$exterior_estudio.'0,';
		            }	
				$cont++; 
			 }
	    $data['capitulos']=$capitulos_resumen;
		$data['interior_locacion']=$interior_locacion;
	 	$data['interior_estudio']=$interior_estudio;
	 	$data['exterior_locacion']=$exterior_locacion;
	 	$data['exterior_estudio']=$exterior_estudio;
	 	$data['tam']=$cont;
		echo json_encode($data);
	   }else{
	   	$data['tam']=$cont;
		echo json_encode($data);
	   }
  }

  public function DiaVSNoc(){
         $id_produccion=$_POST['id_produccion'];
         $limit=$_POST['limit'];
         $resumen_indicadores=$this->model_dashboard->resumen_indicadores($id_produccion,$limit);
        $capitulos_resumen='';
        $dia_locacion='';
        $dia_estudio='';
        $noche_locacion='';
        $noche_estudio='';
        $cont=0;
        if($resumen_indicadores){
                        foreach ($resumen_indicadores as $r) {
                                $capitulos_resumen= $capitulos_resumen.''.$r['numero'].',';
                               $dia_loc=$this->model_dashboard->resumen_indicadores_locacion_dia($id_produccion,$r['id_capitulo']);
                               if($dia_loc){
                       $dia_locacion=$dia_locacion.''.$dia_loc['0']->locacion_dia.',';
                               }else{
                        $dia_locacion=$dia_locacion.'0,';
                               }

                               $dia_estu=$this->model_dashboard->resumen_indicadores_estudio_dia($id_produccion,$r['id_capitulo']);
                               if($dia_estu){
                     $dia_estudio=$dia_estudio.''.$dia_estu['0']->estudio_dia.',';
                               }else{
                                $dia_estudio=$dia_estudio.'0,';
                           }

                               $noc_loca=$this->model_dashboard->resumen_indicadores_locacion_noche($id_produccion,$r['id_capitulo']);
                               if($noc_loca){
                      $noche_locacion=$noche_locacion.''.$noc_loca['0']->locacion_noche.',';
                               }else{
                      $noche_locacion=$noche_locacion.'0,';
                               }

                               $noc_est=$this->model_dashboard->resumen_indicadores_estudio_noche($id_produccion,$r['id_capitulo']);
                               if($noc_est){
                      $noche_estudio=$noche_estudio.''.$noc_est['0']->estudio_noche.',';
                               }else{
                      $noche_estudio=$noche_estudio.'0,';
                               }
                               $cont++; 
                        }
                $data['capitulos']=$capitulos_resumen;
                $data['dia_locacion']=$dia_locacion;
                $data['dia_estudio']=$dia_estudio;
                $data['noche_locacion']=$noche_locacion;
                $data['noche_estudio']=$noche_estudio;
                $data['tam']=$cont;
               echo json_encode($data);
          }else{
                  $data['tam']=$cont;
               echo json_encode($data);
          }
 	}

  public function detallesCapitulo(){
	 $id_produccion=$_POST['id_produccion'];
	 $limit=$_POST['limit'];
	 $cont=0;
     $capitulos_produccion=$this->model_dashboard->capitulos_escenas($id_produccion,$limit);
     $capitulo_post='';
     $capitulo_tiempo_post='';
     $capitulo_flas='';
     $stab='';
     $otros='';
          		   $otros_graf='';
     if($capitulos_produccion){
     	foreach ($capitulos_produccion as $c) {
     		  $capitulo_post=$capitulo_post.$c['numero'].',';
     		  if($c['minutos_post']){
     		  	$t=$this->calculo_tiempo($c['minutos_post'],$c['segundos_post']);
     		  	$tem=explode(':',$t);
     		    $capitulo_tiempo_post=$capitulo_tiempo_post.$tem['0'].',';	
     		  }else{
     		  	$capitulo_tiempo_post=$capitulo_tiempo_post.'0,';	
     		  }


///////////Nueovos Intem///////////
		     		  if($c['credito_minutos'] or $c['credito_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['credito_minutos'],$c['credito_segundos'],$c['credito_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';	
		     		  }

		     		  if($c['flashback_minutos'] or $c['flashback_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['flashback_minutos'],$c['flashback_segundos'],$c['flashback_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	$otros_graf.='00:00-';		
		     		  }

		     		  if($c['transiciones_minutos'] or $c['transiciones_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['transiciones_minutos'],$c['transiciones_segundos'],$c['transiciones_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['cortinillas_minutos'] or $c['cortinillas_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['cortinillas_minutos'],$c['cortinillas_segundos'],$c['cortinillas_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['cabezote_minutos'] or $c['cabezote_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['cabezote_minutos'],$c['cabezote_segundos'],$c['cabezote_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['recap_minutos'] or $c['recap_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['recap_minutos'],$c['recap_segundos'],$c['recap_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['stab_minutos'] or $c['stab_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['presentacion_minutos'] or $c['presentacion_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['presentacion_minutos'],$c['presentacion_segundos'],$c['presentacion_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  if($c['foto_minutos'] or $c['foto_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['foto_minutos'],$c['foto_segundos'],$c['foto_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }
                      
                      if($c['imagenes_archivos_minutos'] or $c['imagenes_archivos_segundos']){
		     		  	$t=$this->calculo_tiempo_post($c['imagenes_archivos_minutos'],$c['imagenes_archivos_segundos'],$c['imagenes_archivos_cuadros']);
		     		    $otros_graf.=$t.'-';	
		     		  }else{
		     		  	 $otros_graf.='00:00-';		
		     		  }

		     		  $otros_graf.='*';	
		     	///////////////////////	  

     		  if($c['stab_minutos'] or $c['stab_segundos']){
			  	$t=$this->calculo_tiempo_post($c['stab_minutos'],$c['stab_segundos'],$c['stab_cuadros']);
			  	$tem=explode(':',$t);
			    $stab=$stab.$tem['0'].',';	
			  }else{
			  	 $stab=$stab.'0,';	
			  }

     		  if($c['flashback_minutos'] or $c['flashback_segundos']){
     		  	$t=$this->calculo_tiempo_post($c['flashback_minutos'],$c['flashback_segundos'],$c['flashback_cuadros']);
     		  	$tem=explode(':',$t);
     		    $capitulo_flas=$capitulo_flas.$tem['0'].',';	
     		  }else{
     		  	$capitulo_flas=$capitulo_flas.'0,';	
     		  }

     		  
			$m=$c['transiciones_minutos']+$c['stab_minutos']+$c['flashback_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos']+$c['foto_minutos'];
			$s=$c['transiciones_segundos']+$c['stab_segundos']+$c['flashback_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos']+$c['foto_segundos'];
			$cuadros=$c['transiciones_cuadros']+$c['stab_cuadros']+$c['flashback_cuadros']+$c['recap_cuadros']+$c['stab_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros']+$c['foto_cuadros']; 

              if($m or $s){
              	$tiempo=$this->calculo_tiempo_post($m,$s,$cuadros);
     		  	$tem=explode(':',$tiempo);
     		    $otros=$otros.$tem['0'].',';	
     		  }else{
     		  	$otros=$otros.'0,';	
     		  }
     		  $cont++; 
     	}
	    $data['capitulo_post']=$capitulo_post;
		$data['capitulo_tiempo_post']=$capitulo_tiempo_post;
	 	$data['capitulo_flas']=$capitulo_flas;
	 	$data['stab']=$stab;
	 	$data['otros']=$otros;
	 	$data['tam']=$cont;
	 	$data['otros_graf']=$otros_graf;
	 	
		echo json_encode($data);
	   }else{
	   	$data['tam']=$cont;
		echo json_encode($data);
	   }
  }

  public function estatusEscena(){
	$id_produccion=$_POST['id_produccion'];
	$limit=$_POST['limit'];
	 $cont=0;
     $capitulos_produccion=$this->model_post_produccion->capitulos_escenas($id_produccion,null,$limit);
     $capitulo_post='';
     $estados_cap='';
     	if($capitulos_produccion){
	     	foreach ($capitulos_produccion as $c) {
					$capitulo_post=$capitulo_post.$c['numero'].',';
					if($c['id_estado']==1){
					$estado=0;
					}else{
					$estado=$c['id_estado'];
					}
					$por=(100*$estado)/15;
					$por=round($por);
					$estados_cap=$estados_cap.$por.',';
					$cont++; 
	     	}
		    $data['capitulo_post']=$capitulo_post;
			$data['estados_cap']=$estados_cap;
		 	$data['tam']=$cont;
			echo json_encode($data);
	  }else{
	   	$data['tam']=$cont;
		echo json_encode($data);
	  }
  }

  public function minEstVsRealVsPots(){
	$id_produccion=$_POST['id_produccion'];
	 $limit=$_POST['limit'];
	 $cont=0;
     $capitulos_produccion=$this->model_dashboard->capitulos_escenas($id_produccion,$limit);
     $capitulo_post='';
     $min_estimados_cap='';
     $min_real_cap='';
     $min_post_cap='';
     	if($capitulos_produccion){
	     	foreach ($capitulos_produccion as $c) {
				$capitulo_post=$capitulo_post.$c['numero'].',';
				if($c['minutos_estimados'] or $c['segundos_estimados']){
				  	$t=$this->calculo_tiempo($c['minutos_estimados'],$c['segundos_estimados']);
				  	$tem=explode(':',$t);
				    $min_estimados_cap=$min_estimados_cap.$tem['0'].',';	
				  }else{
				  	$min_estimados_cap=$min_estimados_cap.'0,';	
				  }	

				  if($c['minutos'] or $c['segundos']){
				  	$t=$this->calculo_tiempo($c['minutos'],$c['segundos']);
				  	$tem=explode(':',$t);
				    $min_real_cap=$min_real_cap.$tem['0'].',';	
				  }else{
				  	$min_real_cap=$min_real_cap.'0,';	
				  }	

				  if($c['minutos_post'] or $c['segundos_post']){
	     		  	$t=$this->calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']);
	     		  	$tem=explode(':',$t);
	     		    $min_post_cap=$min_post_cap.$tem['0'].',';	
	     		  }else{
	     		  	$min_post_cap=$min_post_cap.'0,';	
	     		  }
				$cont++; 
	     	}
		    $data['capitulo_post']=$capitulo_post;
			$data['min_estimados_cap']=$min_estimados_cap;
			$data['min_real_cap']=$min_real_cap;
			$data['min_post_cap']=$min_post_cap;
		 	$data['tam']=$cont;
			echo json_encode($data);
	  }else{
	   	$data['tam']=$cont;
		echo json_encode($data);
	  }
  }

  public function actualizar_detalle_diario(){
  	 $id_produccion=$_POST['id_produccion'];
  	 $unidades=$this->model_plan_produccion->unidades_id_produccion($id_produccion);
  	 $totales=0; $totales_mins=0; $unidad_llamado='';
  	 $datos_unidades=array();
  	 $cont_unidades=0;
  	 $estado_plan_unidad="";
	 foreach ($unidades as $u) { 
  	 	$fecha=date('Y-m-d');
        $unidad=$this->model_dashboard->detalle_unidad($u['id'],$fecha);
        $acum_min_producidos=0;$acum_seg_producidos=0;$acum_min_porproducir=0;$acum_seg_porproducir=0;
        $cont=0;$cont_min_producidos=0;$cont_min_porproducir=0;
        $cont2=0;$totales_mins=0;
        $cadena_extra='';
        if($unidad){
           foreach ($unidad as $uni) {
           	 $estado_plan_unidad=$uni['estado_plan'];
             $unidad_llamado=$uni['llamado'];
             if($uni['estado']==1){ 
               $cont++; 
               $cont_min_producidos=$cont_min_producidos+$uni['duracion_real_minutos']+(round($uni['duracion_real_segundos']/60));
               $acum_min_producidos+=$uni['duracion_real_minutos'];
               $acum_seg_producidos+=$uni['duracion_real_segundos'];
              }else{
                $cont_min_porproducir=$cont_min_porproducir+$uni['duracion_estimada_minutos']+(round($uni['duracion_estimada_segundos']/60));
                $cont2++; 
                
              }
              $acum_min_porproducir+=$uni['duracion_estimada_minutos'];
              $acum_seg_porproducir+=$uni['duracion_estimada_segundos']; 
              $totales_mins=$totales_mins+$uni['duracion_estimada_minutos']+(($uni['duracion_estimada_segundos']/60)); 
               
                if($estado_plan_unidad!=5){ 
	                /*if($unidad_llamado=="" OR strtotime($unidad_llamado)>strtotime(date('H:i:s'))){
	                    
	                }else{
	                    
	                }*/
					if($cont>0){
					     $cadena_extra= " - <span style='color:#FFF'>EN PROGRESO</span>";

					}else{
					    $cadena_extra= " - <span style='color:#FFF'>NO INICIADO</span>";
					}
	            }else{
	                $cadena_extra= " - <span style='color:#FFF'>CERRADO</span>";
	            }
            }

            $totales = $cont + $cont2;
            $tiempo_producido=$this->calculo_tiempo2($acum_min_producidos,$acum_seg_producidos);
            $totales_mins=$acum_min_producidos+(($acum_seg_producidos/60)); 
            $tiempo_porproducido=$this->calculo_tiempo2($acum_min_porproducir,$acum_seg_porproducir);

          }else{
            $totales='';
            $tiempo_producido=0;
            $tiempo_porproducido=0;
          }  

        // ESCENAS EN GRABACION
        $escenas_grabacion = $this->model_dashboard->escenas_grabacion($u['id']);
        $cadena_temp="";
        if($escenas_grabacion){ 
            foreach ($escenas_grabacion as $escena_grabacion) { 
                $cadena_temp.=$escena_grabacion->numero_libreto.'/'.$escena_grabacion->numero_escena.',';
            }
        } 
        // FIN ESCENAS EN GRABACION 
        $escenas_grabacion = $this->model_dashboard->escenas_grabacion($u['id']);
          $date=array('unidad_llamado'=>$unidad_llamado.' '.$cadena_extra,'numero'=>$u['numero'],'totales'=>$totales,
          	          'tiempo_producido'=>$tiempo_producido,'tiempo_porproducido'=>$tiempo_porproducido,
          	          'cont'=>$cont,'cont2'=>$cont2,'cont_min_producidos'=>$cont_min_producidos,
          	          'cont_min_porproducir'=>$cont_min_porproducir,'totales_mins'=>$totales_mins,'escenas_grabacion'=>$cadena_temp);
         $datos_unidades[$cont_unidades]=$date; 
         $cont_unidades++;
      }
  	 $data['unidades']=$datos_unidades;
	 echo json_encode($data);

  }


  function download_pie_graphic(){
  	 # we are a PNG image
	 header('Content-type: image/jpeg');

	  # we are an attachment (eg download), and we have a name
	 header('Content-Disposition: attachment; filename="' . $_POST['name'] .'"');

	  #capture, replace any spaces w/ plusses, and decode
	 $encoded = $_POST['imgdata'];
	  $encoded = str_replace(' ', '+', $encoded);
	  $decoded = base64_decode($encoded);

	  #write decoded data
	 echo $decoded;
  }

  public function liquidacion_personajes(){
  	$idproduccion=$_POST['id_produccion'];


  	$liquidacion_principales=0;
  	$liquidacion_figurantes=0;
  	$liquidacion_reparto=0;
  	$mensual_principales='';
  	$mensual_figurantes='';
  	$mensual_reparto='';
  	$meses2='';


  	$fecha=$this->model_plan_produccion->unidades_id_produccion_2($idproduccion);
	if($fecha){
	   $inicio_unidad=$fecha['0']['fecha_inicio'];
	   $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad($idproduccion,$inicio_unidad);
	}else{
	  $fechas_reporte_semanal='';   
	}
    
    $meses_num=array();
    $fechas_meses=array();
    $cont=1;
    $fechas_grafica='';
    $tam_semanas=0;
    $meses_liqu='';
   $tam=count($fechas_reporte_semanal);
    if($fechas_reporte_semanal){
    	foreach ($fechas_reporte_semanal as $f) {
    		$total_semanas=explode('-',$f->fecha_valor);
    		if($cont==1){
    			$fecha_mes=array('fecha_inicio'=>$f->fecha_valor,'fecha_fin'=>$total_semanas['0'].'-'.$total_semanas['1'].'-31');
               array_push($meses_num, $total_semanas['1']);
               array_push($fechas_meses, $fecha_mes);
               $fechas_grafica=$fechas_grafica.date("d-M-Y",strtotime($f->fecha_valor)).' al '.date("d-M-Y",strtotime($total_semanas['0'].'-'.$total_semanas['1'].'-31')).',';
               $meses_liqu=$meses_liqu.$total_semanas['1'].',';
               $tam_semanas++;
    		}else{
    			if(in_array($total_semanas['1'], $meses_num)){

    			}else{
    				if($cont==18){
    					$fecha_mes=array('fecha_inicio'=>$total_semanas['0'].'-'.$total_semanas['1'].'-01','fecha_fin'=>$f->fecha_valor_2);
	    				array_push($meses_num, $total_semanas['1']);
	    				array_push($fechas_meses, $fecha_mes);	
	    				$fechas_grafica=$fechas_grafica.date("d-M-Y",strtotime($total_semanas['0'].'-'.$total_semanas['1'].'-01')).' al '.date("d-M-Y",strtotime($f->fecha_valor_2)).',';
	    				$meses_liqu=$meses_liqu.$total_semanas['1'].',';
	    				$tam_semanas++;
    				}else{
    				   $fecha_mes=array('fecha_inicio'=>$total_semanas['0'].'-'.$total_semanas['1'].'-01','fecha_fin'=>$total_semanas['0'].'-'.$total_semanas['1'].'-31');
	    				array_push($meses_num, $total_semanas['1']);
	    				array_push($fechas_meses, $fecha_mes);	
	    				$fechas_grafica=$fechas_grafica.date("d-M-Y",strtotime($total_semanas['0'].'-'.$total_semanas['1'].'-01')).' al '.date("d-M-Y",strtotime($total_semanas['0'].'-'.$total_semanas['1'].'-31')).',';
	    				$meses_liqu=$meses_liqu.$total_semanas['1'].',';
	    				$tam_semanas++;
    				}
    				
    			}
    		}
    		$cont++;
    	}
    }


	

  	$produccion=$this->model_dashboard->produccion($idproduccion);
  	$presupuestado_principales=$produccion['0']->presupuesto_principales;
  	$presupuestado_figurantes=$produccion['0']->presupuesto_figurante;
  	$presupuestado_reparto=$produccion['0']->presupuesto_secundarios;
  	$monto_figurante_extra = 0;
    $monto_figurante_extra_dolar = 0;

      if($produccion['0']->monto_figurante_extra){
       		$monto_figurante_extra = $produccion['0']->monto_figurante_extra;
      }
      if($produccion['0']->monto_figurante_extra_dolar){
       		$monto_figurante_extra_dolar = $produccion['0']->monto_figurante_extra_dolar;
      }
  	$liquidaciones = $this->model_herramientas->liquidaciones_produccion($idproduccion);
  	$tam=0;
  	               
  	               if($produccion['0']->tipo_produccion==2){
  	               	
  	               	 $nominas = $this->model_herramientas->nomina_personajes_anterior($idproduccion,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);
  	               }else{
  	                  $nominas = $this->model_herramientas->nomina_personajes($idproduccion,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);	
  	               }
  				   

		
				   $mensual_figurantes_sum=0;
				   $mensual_reparto_sum=0;
				   $mensual_principales_sum=0;
				   $cont1=0;
				   $cont2=0;
				   if($nominas){
						  	foreach ($nominas as $nomina) {

												  /** figurantes**/   
										$liquidacion = 0;  
									    if(((($nomina->id_tipo_moneda==1 or $nomina->id_tipo_moneda==0) and $nomina->monto<$monto_figurante_extra) 	or  ($nomina->id_tipo_moneda==2 and $nomina->monto<$monto_figurante_extra_dolar) )
						      	    				and $nomina->rol!=null and $nomina->rol!='' and $nomina->rol != 4 and $nomina->id_tipo_contrato!=5 and $nomina->id_tipo_contrato!='' and $nomina->id_tipo_contrato!=0){
									    	 // ECHO $nomina->id_tipo_contrato;
											        if($nomina->id_tipo_contrato){
                                                                $cont1++;

													          switch ($nomina->id_tipo_contrato) {
													            case 1:
													              if($nomina->monto){
													              	$pago=0;
													              	  if($fechas_reporte_semanal){

													              	  	$acumulado_capitulos = 0;
																			 foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
																			 	$capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($nomina->idelemento,date("Y-m-d", strtotime($fecha_reporte_semanal->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal->fecha_muestra_2))); 
																				if ($capitulos_personaje[0]->capitulos) {
																					$acumulado_capitulos += count($capitulos_personaje[0]->capitulos);
																					
																				}


																			 	 /* if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
																					 strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) ){
																			 	  			echo $acumulado_capitulos.'acumu2<br>';*/
																			 	  	 if($acumulado_capitulos>0){

																			 	  	   $pago++; 	
																			 	  	   $acumulado_capitulos=0;
																			 	  	 }
																			 	  	
																			 	  //}	

																			 }	
																		}
													                $liquidacion =$nomina->monto*$pago;

													              }
													              break;
													            case 2:
													              $liquidaciones = $this->model_herramientas->liquidaciones_produccion($idproduccion);
													              //if($liquidaciones){
													               // foreach ($liquidaciones as $liquidacion) {
													                  //$capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,'01'.$liquidacion->mes,'31'.$liquidacion->mes);
													              $capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);
													                  if($capitulos_pagados and $nomina->libretos){
													                    $capitulos_pagados = explode(',', $capitulos_pagados[0]->libretos);
													                    for ($c=0; $c < count($capitulos_pagados); $c++) { 
													                      $libretos=str_replace($capitulos_pagados[$c], "", $nomina->libretos.',');
													                    }
													                  }else{
													                  	$libretos='';
													                  }
													                //}
													             // }

													               if($libretos){  
														              $libretos = explode(',',$libretos);
														              $libretos= count($libretos)-1;
														              $liquidacion =$nomina->monto*$libretos;
														           }else{
														           	$liquidacion =$nomina->monto*0;
														           }   
													             // $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
													              break;
													            case 3:
													                $liquidacion=0;
													                if($nomina->monto){
													                  if($libretos<11){
													                    $liquidacion = $nomina->monto*$libretos;
													                   // $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
													                  }else{
													                    $liquidacion = $nomina->monto;
													                  }
													                }
													              break;
													            case 4:
													              if($nomina->monto){
													                //$liquidacion = $nomina->dias_trabajados*$nomina->monto;
													                if($fechas_reporte_semanal){

													              	  		$acumulado_dias = 0;
																			 foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
																			 	$dias_pagar = 0;
			                                                                    $dias_pagar = $this->model_herramientas->buscar_dias_elemento($nomina->idelemento,date("Y-m-d", strtotime($fecha_reporte_semanal->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal->fecha_muestra_2))); 
			                                                                    if ($dias_pagar[0]->total) {
			                                                                       $acumulado_dias += $dias_pagar[0]->total;
			                                                                    }
                                                                            }

																		}

													                //$liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
																		 $liquidacion = $acumulado_dias*$nomina->monto;
													              }else{
													              	 $liquidacion =0;
													              }

													              break;
													            default:
													               $liquidacion = 0;
													              break;
													          }        
											        }else{
											          $liquidacion = 0;
											        }

 
											        /**********************/
											      
											        /**********************/




	                                             //$mensual_figurantes_sum=$mensual_figurantes_sum+$liquidacion;
											        //echo $nomina->idelemento.'---'.$liquidacion.'</br>';

										        $liquidacion_figurantes=$liquidacion_figurantes+$liquidacion;
											        
										} 


											  /** reparto**/
	                     			      $liquidacion = 0;
				                          if(((($nomina->id_tipo_moneda==1 or $nomina->id_tipo_moneda==0) and $nomina->monto>=$monto_figurante_extra) or  ($nomina->id_tipo_moneda==2 and $nomina->monto>=$monto_figurante_extra_dolar) )
					      	    				and $nomina->rol!=null and $nomina->rol!='' and $nomina->rol != 4 and $nomina->id_tipo_contrato!=5 and $nomina->id_tipo_contrato!='' and $nomina->id_tipo_contrato!=0){
											       if($nomina->id_tipo_contrato){
											       	$cont2++;
													          switch ($nomina->id_tipo_contrato) {
													            case 1:
													              if($nomina->monto){
													                  $pago=0;
													              	  if($fechas_reporte_semanal){
													              	  	$acumulado_capitulos = 0;
																			 foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
																			 	$capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($nomina->idelemento,date("Y-m-d", strtotime($fecha_reporte_semanal->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal->fecha_muestra_2))); 
																				if ($capitulos_personaje[0]->capitulos) {
																					$acumulado_capitulos += count($capitulos_personaje[0]->capitulos);
																					
																				}
																			 	  if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
																					 strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) ){
																			 	  	 if($acumulado_capitulos>0){
																			 	  	   $pago++; 	
																			 	  	   $acumulado_capitulos=0;
																			 	  	 }
																			 	  	
																			 	  }	

																			 }	
																		}
											                			$liquidacion =$nomina->monto*$pago;
													                }
													              break;
													            case 2:
													               //if($liquidaciones){
													               // foreach ($liquidaciones as $liquidacion) {
													                  //$capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,'01'.$liquidacion->mes,'31'.$liquidacion->mes);
													             	 $capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);
													                  if($capitulos_pagados and $nomina->libretos){
													                    $capitulos_pagados = explode(',', $capitulos_pagados[0]->libretos);
													                    for ($c=0; $c < count($capitulos_pagados); $c++) { 
													                      $libretos=str_replace($capitulos_pagados[$c], "", $nomina->libretos.',');
													                    }
													                  }else{
													                  	$libretos='';
													                  }
													                //}
													             // }

													               if($libretos){  
														              $libretos = explode(',',$libretos);
														              $libretos= count($libretos)-1;
														              $liquidacion =$nomina->monto*$libretos;
														           }else{
														           	$liquidacion =$nomina->monto*0;
														           }   
													               
													              break;
													            case 3:
													                $liquidacion=0;
													                if($nomina->monto){
													                	$capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);
													                  if($capitulos_pagados and $nomina->libretos){
													                    $capitulos_pagados = explode(',', $capitulos_pagados[0]->libretos);
													                    for ($c=0; $c < count($capitulos_pagados); $c++) { 
													                      $libretos=str_replace($capitulos_pagados[$c], "", $nomina->libretos.',');
													                    }
													                  }else{
													                  	$libretos='';
													                  }
													                  if($libretos<11){
													                    $liquidacion = $nomina->monto*$libretos;
													                    
													                  }else{
													                    $liquidacion = $nomina->monto*13;
													                  }
													                }
													              break;
													            case 4:
													              if($nomina->monto){
													                //$liquidacion = $nomina->dias_trabajados*$nomina->monto;
														                if($fechas_reporte_semanal){

															              	  		$acumulado_dias = 0;
																					foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
																					 	$dias_pagar = 0;
					                                                                    $dias_pagar = $this->model_herramientas->buscar_dias_elemento($nomina->idelemento,date("Y-m-d", strtotime($fecha_reporte_semanal->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal->fecha_muestra_2))); 
					                                                                    if ($dias_pagar[0]->total) {
					                                                                       $acumulado_dias += $dias_pagar[0]->total;
					                                                                    }
					                                                                }    

															                //$liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
																				 $liquidacion = $acumulado_dias*$nomina->monto;
															              }else{
															              	 $liquidacion =0;
															              }
													                
													              }
													              break;
													            default:
													              $liquidacion = 0;
													              break;
													           }        
											        }else{
											          $liquidacion = 0;
											        }

											        /******************************/
											       
											        /*******************************/
                                                       //echo $nomina->idelemento.'---'.$liquidacion.'</br>';
												    $mensual_reparto_sum=$mensual_reparto_sum+$liquidacion;
												    $liquidacion_reparto=$liquidacion_reparto+$liquidacion;
										        
										 	}  
									       // $mensual_figurantes=$mensual_figurantes.$mensual_figurantes_sum.',';
											//$mensual_reparto=$mensual_reparto.$mensual_reparto_sum.',';
                                             


                                             
					        }
                             

								    
	                 }
                   
				

				//}	

	          
            //liquidacion mensual figurantes
             if($fechas_meses){
				foreach ($fechas_meses as $f) {

					    $liquidacion_mensual_figurantes=0;
					    $liquidacion_mensual_reparto=0;
					    foreach ($nominas as $nomina) {
                            
                            ///////figutantes
					    	if(((($nomina->id_tipo_moneda==1 or $nomina->id_tipo_moneda==0) and $nomina->monto<$monto_figurante_extra) 	or  ($nomina->id_tipo_moneda==2 and $nomina->monto<$monto_figurante_extra_dolar) )
						      	    				and $nomina->rol!=null and $nomina->rol!='' and $nomina->rol != 4 and $nomina->id_tipo_contrato!=5 and $nomina->id_tipo_contrato!='' and $nomina->id_tipo_contrato!=0){
                              
										    	   $acumulado_capitulos = 0; 
													$fecha="";


													if($nomina->fecha_inicio_2!="" AND $nomina->fecha_inicio_2!="0000-00-00"){
													  $fecha = $nomina->fecha_inicio_2;
													}

													if($nomina->fecha_finalizacion!="" AND $nomina->fecha_finalizacion!="0000-00-00"){
													  $fecha_final = $nomina->fecha_finalizacion;
													}else{
													  $fecha_final = "";
													} 
													$dias_trabajados = 0;

														if ($fecha AND  strtotime($f['fecha_inicio']) <= strtotime($fecha) AND strtotime($f['fecha_fin']) >= strtotime($fecha)):
								                                $dias_trabajados = (strtotime($fecha)-strtotime($f['fecha_fin']))/86400;
								                                $dias_trabajados = abs($dias_trabajados); 
								                                $dias_trabajados = floor($dias_trabajados)+1;
								                                $fecha = date("d-m-Y",strtotime($f['fecha_fin'])+86400);
								                        endif;

								                        $capitulos_pagar = 0;
								                        $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($nomina->idelemento,$f['fecha_inicio'],$f['fecha_fin']); 
								                        if ($capitulos_personaje[0]->capitulos) {
								                            $acumulado_capitulos += $capitulos_pagar = count(explode(',', $capitulos_personaje[0]->capitulos));
								                          
								                        }

								                       		 $liquidacion=0;
								                            if($nomina->id_tipo_contrato){
								                                $cont1++;
								                              switch ($nomina->id_tipo_contrato) {
								                                case 1:
								                                  if($nomina->monto and $acumulado_capitulos!=0){
								                                    $liquidacion = $nomina->monto;
								                                  }
								                                  break;
								                                case 2:
								                                  $liquidacion =$nomina->monto*$acumulado_capitulos;
								                                  break;
								                                case 3:
								                                    $liquidacion=0;
								                                    if($nomina->monto){
								                                      if($acumulado_capitulos<11){
								                                        $liquidacion = $nomina->monto*$acumulado_capitulos;
								                                      }else{
								                                        $liquidacion = $nomina->monto;
								                                      }
								                                    }
								                                  break;
								                                case 4:
								                                  if($nomina->monto){
								                                    //$liquidacion = $nomina->dias_trabajados*$personaje->monto;
								                                    if($fechas_reporte_semanal){

															              	  		$acumulado_dias = 0;
																					 	$dias_pagar = 0;
					                                                                    $dias_pagar = $this->model_herramientas->buscar_dias_elemento($nomina->idelemento,$f['fecha_inicio'],$f['fecha_fin']); 
					                                                                    if ($dias_pagar[0]->total) {
					                                                                       $acumulado_dias += $dias_pagar[0]->total;
					                                                                    }


																					 	

															                //$liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
																				 $liquidacion = $acumulado_dias*$nomina->monto;
															              }else{
															              	 $liquidacion =0;
															              }
								                                  }
								                                  break;
								                                default:
								                                  $liquidacion = 0;
								                                  break;
								                              }        
								                            }else{
								                              $liquidacion = 0;
								                            }

					                                           
					                                          $liquidacion_mensual_figurantes= $liquidacion_mensual_figurantes+$liquidacion;
								                          	  $acumulado_capitulos = 0; 
								                          	  $acumulado_dias=0;


							}  


							

							/////reparto
							if(((($nomina->id_tipo_moneda==1 or $nomina->id_tipo_moneda==0) and $nomina->monto>=$monto_figurante_extra) or  ($nomina->id_tipo_moneda==2 and $nomina->monto>=$monto_figurante_extra_dolar) )
					      	    				and $nomina->rol!=null and $nomina->rol!='' and $nomina->rol != 4 and $nomina->id_tipo_contrato!=5 and $nomina->id_tipo_contrato!='' and $nomina->id_tipo_contrato!=0){
										    	   $acumulado_capitulos = 0; 
													$fecha="";


													if($nomina->fecha_inicio_2!="" AND $nomina->fecha_inicio_2!="0000-00-00"){
													  $fecha = $nomina->fecha_inicio_2;
													}

													if($nomina->fecha_finalizacion!="" AND $nomina->fecha_finalizacion!="0000-00-00"){
													  $fecha_final = $nomina->fecha_finalizacion;
													}else{
													  $fecha_final = "";
													} 
													$dias_trabajados = 0;

														if ($fecha AND  strtotime($f['fecha_inicio']) <= strtotime($fecha) AND strtotime($f['fecha_fin']) >= strtotime($fecha)):
								                                $dias_trabajados = (strtotime($fecha)-strtotime($f['fecha_fin']))/86400;
								                                $dias_trabajados = abs($dias_trabajados); 
								                                $dias_trabajados = floor($dias_trabajados)+1;
								                                $fecha = date("d-m-Y",strtotime($f['fecha_fin'])+86400);
								                        endif;

								                        $capitulos_pagar = 0;
								                        $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($nomina->idelemento,$f['fecha_inicio'],$f['fecha_fin']); 
								                        if ($capitulos_personaje[0]->capitulos) {
								                            $acumulado_capitulos += $capitulos_pagar = count(explode(',', $capitulos_personaje[0]->capitulos));
								                          
								                        }

								                       		 $liquidacion=0;
								                            if($nomina->id_tipo_contrato){
								                                $cont1++;
								                              switch ($nomina->id_tipo_contrato) {
								                                case 1:
								                                  if($nomina->monto and $acumulado_capitulos!=0){
								                                    $liquidacion = $nomina->monto;
								                                  }
								                                  break;
								                                case 2:
								                                  $liquidacion =$nomina->monto*$acumulado_capitulos;
								                                  break;
								                                case 3:
								                                    $liquidacion=0;
								                                    if($nomina->monto){
								                                      if($acumulado_capitulos<11){
								                                        $liquidacion = $nomina->monto*$acumulado_capitulos;
								                                      }else{
								                                        $liquidacion = $nomina->monto;
								                                      }
								                                    }
								                                  break;
								                                case 4:
								                                  if($nomina->monto){
								                                    //$liquidacion = $nomina->dias_trabajados*$personaje->monto;
								                                     if($fechas_reporte_semanal){

															              	  		$acumulado_dias = 0;
																					 

																					 	$dias_pagar = 0;
					                                                                    $dias_pagar = $this->model_herramientas->buscar_dias_elemento($nomina->idelemento,$f['fecha_inicio'],$f['fecha_fin']); 
					                                                                    if ($dias_pagar[0]->total) {
					                                                                       $acumulado_dias += $dias_pagar[0]->total;
					                                                                    }


														

															                //$liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
																				 $liquidacion = $acumulado_dias*$nomina->monto;
															              }else{
															              	 $liquidacion =0;
															              }
								                                  }
								                                  break;
								                                default:
								                                  $liquidacion = 0;
								                                  break;
								                              }        
								                            }else{
								                              $liquidacion = 0;
								                            }

					                                           // echo $nomina->idelemento.'---'.$liquidacion.'</br>';
					                                          $liquidacion_mensual_reparto= $liquidacion_mensual_reparto+$liquidacion;
								                          	  $acumulado_capitulos = 0; 
								                          	  $acumulado_dias=0;

							}  


							
						}

						$mensual_figurantes=$mensual_figurantes.$liquidacion_mensual_figurantes.',';	
						$mensual_reparto=$mensual_reparto.$liquidacion_mensual_reparto.',';	

				}
			}	            
             

             //fin liquidacion mensual   figutantes

  $liquidacion_principales=0;
      if($liquidaciones){
  		foreach ($liquidaciones as $l) {
  			$x=explode('-',$l->mes);

  			$meses2=$meses2.$x['1'].',';
  			//echo date("d-M-Y",strtotime($l->mes.'-01')).' al '.date("d-M-Y",strtotime($l->mes.'-31')).'--*----';

				//$nomina_principales= $this->model_herramientas->nomina_personajes_mensuales($idproduccion,$l->mes.'-01',$l->mes.'-31');

				if($produccion['0']->tipo_produccion==2){
  	               	
  	               	 $nomina_principales = $this->model_herramientas->nomina_personajes_mensuales_anteriores($idproduccion,$l->mes.'-01',$l->mes.'-31');
  	               }else{
  	                  $nomina_principales = $this->model_herramientas->nomina_personajes_mensuales($idproduccion,$produccion['0']->inicio_grabacion,$produccion['0']->fin_grabacion);	
  	               }


			    $liquidacion = 0;
			    if($nomina_principales){
			    	foreach ($nomina_principales as $nomina) {
				    		$val = true;
					        $libretos =0;
					        if($nomina->libretos!=""){
					          $libretos = explode(',', $nomina->libretos);
					          $libretos= count($libretos);
					        }
					        

					        if($nomina->fecha_inicio==""){
					          $fecha="-";
					        }else{
					          $fecha = strtoupper($nomina->fecha_inicio);
					        }

					        if($nomina->fecha_inicio_2!="" AND $nomina->fecha_inicio_2!="0000-00-00"){
					          $fecha = strtoupper($nomina->fecha_inicio_2);
					        }

					        if($nomina->fecha_liquidacion!="" AND $nomina->fecha_liquidacion!="0000-00-00"){
					          $fecha_final = strtoupper($nomina->fecha_liquidacion);
					        }else{
					          $fecha_final = "-";
					        }

					        
			    		  if($nomina->id_tipo_contrato){
					              if($nomina->monto){
					                $valor_dia = $nomina->monto / 30;
					                $segundos= abs(strtotime($fecha));

					                $dias_mes = cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($l->mes.'-01')) , date("Y",strtotime($l->mes.'-01')));


					                if( date("m",strtotime($l->mes.'-01')) <= date("m",strtotime($fecha))  AND date("Y",strtotime($fecha)) == date("Y",strtotime($l->mes.'-01')) ){


					                  if(date("d",strtotime($fecha))<30){
					                    $dias_pagar = 30 - date("d",strtotime($fecha))+1;
					                  }else{
					                    $dias_pagar = 1;
					                  }
					                  // echo "Primera validacion";
					                }else{
					                  // echo "Segunda validacion";
						                  if($fecha_final!="-" AND date("m",strtotime($fecha_final)) == date("m",strtotime($l->mes.'-01')) AND date("Y",strtotime($fecha_final)) == date("Y",strtotime($l->mes.'-01')) ){
						                    if(date("d",strtotime($fecha_final))>30){
						                      // echo "Tercera validacion";
						                      $dias_pagar=30;
						                    }else{
						                      // echo "Cuarta validacion";
						                      $dias_pagar=date("d",strtotime($fecha_final));
						                    }
						                  }else{
						                     if($fecha_final!="-" AND date(strtotime($fecha_final))<date(strtotime($l->mes.'-01'))){
						                        $dias_pagar=0;
						                       }else{
						                          $dias_pagar=30;   
						                       }
						                  }
					                }

					                $descuentos = $this->model_herramientas->descuento_personaje($nomina->idelemento,date("Y-m",strtotime($l->mes.'-01')));
					                if($descuentos){
					                  if($descuentos[0]->observaciones){
					                    $observaciones=$descuentos[0]->observaciones;  
					                  }else{
					                    $observaciones='';
					                  }
					                  $descuentos = $descuentos[0]->descuento;
					                }else{
					                  $descuentos =0;
					                  $observaciones='';
					                }


					                //echo  $dias_pagar.'</br>';
					                $liquidacion =  (($nomina->monto*$dias_pagar)/30) - $descuentos;

					              }
					        }else{
					          $liquidacion = 0;
					        }
					       $mensual_principales_sum=$mensual_principales_sum+$liquidacion;
					        
			    	}
			    	$mensual_principales=$mensual_principales.$mensual_principales_sum.',';
                    
			    	$liquidacion_principales=$liquidacion_principales+$mensual_principales_sum;
			    	$mensual_principales_sum=0;
			    }

			  
				
       }

    }		

   

       $data['liquidacion_principales']=$liquidacion_principales;
       $data['liquidacion_principales_f']=number_format(round($liquidacion_principales,2), 2, '.', ",");
       $data['presupuestado_principales']=$presupuestado_principales;
       $data['presupuestado_principales_f']=number_format(round($presupuestado_principales,2), 2, '.', ",");
       

       $data['liquidacion_figurantes']=$liquidacion_figurantes;
       $data['liquidacion_figurantes_f']=number_format(round($liquidacion_figurantes,2), 2, '.', ",");	
       $data['presupuestado_figurantes']=$presupuestado_figurantes;	
       $data['presupuestado_figurantes_f']=number_format(round($presupuestado_figurantes,2), 2, '.', ",");


       $data['liquidacion_reparto']=$liquidacion_reparto;
       $data['liquidacion_reparto_f']=number_format(round($liquidacion_reparto,2), 2, '.', ",");
       $data['presupuestado_reparto']=$presupuestado_reparto;	
       $data['presupuestado_reparto_f']=number_format(round($presupuestado_reparto,2), 2, '.', ",");


		$data['mensual_principales']=$mensual_principales;
		$data['mensual_figurantes']=$mensual_figurantes;
		$data['mensual_reparto']=$mensual_reparto;
		$data['tam']=$tam_semanas;
		$data['meses']=$fechas_grafica;
		$data['meses2']=$meses2;
		$data['meses_liqu']=$meses_liqu;

       echo json_encode($data);   

  	
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


    public function cambiar_idioma($tipo){

      if($tipo==1){
        $this->config->set_item('language','spanish');
      }else if($tipo==2){
        $this->config->set_item('language','english');
      }
      $metodo=$this->router->fetch_method();
      redirect($this->lang->lang().'/dashboard/index');
    
    }


}