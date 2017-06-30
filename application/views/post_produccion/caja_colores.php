<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"> <?php echo lang('global.capitulos') ?></a> / Control de produccion
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<nav>
        <ul class="nav_post">
            <li><a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion) ?>" class="active"><?php echo lang('global.volver') ?></a></li>
            <li><a href="<?php echo base_url($idioma.'excel/post_produccion_control/'.$id_produccion) ?>" class=""><?php echo lang('global.guardar') ?></a></li>
        	<!--<li><a href="<?php echo base_url('pdf2/pdf_postproduccion_capitulos/'.$id_produccion) ?>" class="">Imprimir</a></li>-->
        </ul>
    </nav>
<div data-role="content" id="inner_content">
<div style="width:99.5%;">


	<aside id="sidebar_caja_colores">
		<nav>
			<ul class="menu_list_sidebar">
				<li class="menu_index">&nbsp;</li>
				<li class="sub_menu_list">
					<ul>
						<li><?php echo lang('global.fecha_entrega') ?></li>
						<li><?php echo lang('global.fecha_entregada') ?></li>
						<li>% <?php echo lang('global.locacion') ?></li>
						<li>% <?php echo lang('global.estudio') ?></li>
						<li><?php echo lang('global.total_real') ?></li>
						<li><?php echo lang('global.total_post') ?></li>
						<li><?php echo lang('global.total_extra') ?></li>
						<li><?php echo lang('global.diferencia') ?></li>
					</ul>
				</li>
			</ul>
		</nav>
		
	</aside>
	<div class="content_caja_colores"> 
	<div style="width:7200px" class="scroll_inner">

	    <?php $numero=0; $cont=1; $cont_minutos_real=0;$cont_segundos_real=0;
	    $cont_minutos_estimado=0;$cont_segundos_estimado=0;$cont_minutos_post=0;$cont_segundos_post=0; $cont_cuadros_post=0;?>
	    <?php if($capitulos){ ?>
						<?php foreach ($capitulos as $c) { ?>
						<?php $cont_minutos_real=$cont_minutos_real+$c['duracion_real_minutos'];
						$cont_segundos_real=$cont_segundos_real+$c['duracion_real_segundos'];
						$cont_minutos_estimado=$cont_minutos_estimado+$c['duracion_estimada_minutos'];
						$cont_segundos_estimado=$cont_segundos_estimado+$c['duracion_estimada_segundos'];
						$cont_minutos_post=$cont_minutos_post+$c['tiempo_post_minutos'];
						$cont_segundos_post=$cont_segundos_post+$c['tiempo_post_segundos'];
						$cont_cuadros_post=$cont_cuadros_post+$c['total_post_cuadros']; ?>
						    <?php if($numero!=$c['numero_capitulo']){ ?>
						      <div class="cj_colores">
                              <?php 
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
							      	     ?>
						      <table cellspacing="0" cellpadding="0">
						      	<thead>
						      		<tr>
						      			<td colspan="4" class="<?=$class_cap ?>"><?php echo lang('global.capitulos') ?> <?php echo $c['numero_capitulo'] ?> </td>
						      		</tr>
						      	</thead>
						      	<tbody class="tbody_header">
							      	<tr>
										<?php $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id_produccion,$c['libreto']); 
											if($fecha_entrega){
												if($fecha_entrega['0']->fecha_aire!='0000-00-00'){
													$fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));  
												}else{
													$fecha_entrega='-';   
												}
											}else{
												$fecha_entrega='-'; 
											} 
										?>
							      		<td colspan="4"><?php echo $fecha_entrega; ?></td>
							      	</tr>
							      	<tr>
								      	<?php if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
					                          $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
					                        }else{
					                          $fecha_entregada='-'; 
					                        }
					                     ?>
							      		<td colspan="4"><?php echo $fecha_entregada; ?></td>
							      	</tr>
							      	<tr>
							      	</tr>
							      	    <tr class="bg_gray">
							      	        <?php if($c['total_escenas']){ ?>
							            	<td colspan="4"><?php echo round((100*$c['total_locacion'])/$c['total_escenas']); ?>%</td>
							            	<?php }else{ ?>
							            	<td colspan="4"><?php echo '0'; ?>%</td>
							            	<?php } ?>
							            </tr>
							            <tr class="bg_gray">
							             <?php if($c['total_escenas']){ ?>
							            	<td colspan="4"><?php echo round((100*$c['total_estudio'])/$c['total_escenas']); ?>%</td>
							            	<?php }else{ ?>
							            	<td colspan="4"><?php echo '0'; ?>%</td>
							            	<?php } ?>
							            </tr>
							            <tr class="bg_gray">
							            	<td colspan="4"><?php echo Post_produccion::calculo_tiempo2($c['total_real_minutos'],$c['total_real_seg']); ?></td>
							            </tr>
							            <?php 
							                $c_m=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['credito_minutos']+$c['cabezote_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos'];
									     	$c_s=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['credito_segundos']+$c['cabezote_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos'];
									     	$c_c=$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['credito_cuadros']+$c['cabezote_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['imagenes_archivos_cuadros'];
							             ?>
							            <tr class="bg_gray">
							            	<td colspan="4"><?php echo $tiempo_post=Post_produccion::calculo_tiempo_post($c['total_post_minutos'],$c['total_post_seg'],$c['total_post_cuadros']); ?></td>
							            </tr>
							             <tr class="bg_gray">
							            	<td colspan="4"><?php echo Post_produccion::calculo_tiempo_post($c_m,$c_s,$c_c); ?></td>
							            </tr>
							            <tr class="bg_gray">
							              <?php 
                                              $c_m2=explode(':', $tiempo_post);
						                      $c_s2=explode('-', $c_m2['1']);

						                      
						                      if($c_s2['1']>15){
						                        $segundos_post_total=$c_s2['0']+1;
						                      }else{
						                        $segundos_post_total=$c_s2['0'];
						                      }
							                ?>
							            	<td colspan="4"><?php echo Post_produccion::calculo_tiempo2($c['total_real_minutos']-$c_m2['0'],$c['total_real_seg']-$segundos_post_total); ?></td>
							            </tr>
							            <tr class="bg_total">
							            	<td>TOTAL</td>
							            	<td><?php echo Post_produccion::calculo_tiempo2($c['total_est_minutos'],$c['total_est_seg']); ?></td>
							            	<td><?php echo Post_produccion::calculo_tiempo2($c['total_real_minutos'],$c['total_real_seg']); ?></td>
							            	<td><?php echo Post_produccion::calculo_tiempo_post($c_m+$c['total_post_minutos'],$c_s+$c['total_post_seg'],$c_c+$c['total_post_cuadros']); ?></td>
							            </tr>
						        </tbody>
						      </table>
							  <table cellspacing="0" cellpadding="0">
						        <tbody>
							        <tr class="bg_blue">
							     		<td><?php echo lang('global.lib_esc') ?></td>
							     		<td>TE</td>
							     		<td>TR</td>
							     		<td>TP</td>
							     	</tr>
						     <?php } ?>
								     <tr>
								     		<td><?php echo $c['libreto'] ?>/<?php echo $c['numero_escena'] ?></td>
								     		<?php if($c['duracion_estimada_minutos']<10 and (strlen($c['duracion_estimada_minutos'])<2)){ 
								     				$duracion_estimada_minutos='0'.$c['duracion_estimada_minutos'];
								     			  }else{
                                                    $duracion_estimada_minutos=$c['duracion_estimada_minutos'];
								     		      }
								     		      if($c['duracion_estimada_segundos']<10 and (strlen($c['duracion_estimada_segundos'])<2)){ 
								     				$duracion_estimada_segundos='0'.$c['duracion_estimada_segundos'];
								     			  }else{
                                                    $duracion_estimada_segundos=$c['duracion_estimada_segundos'];
								     		      } 
								     	    ?>

								     		<td><?php echo $duracion_estimada_minutos ?>:<?php echo $duracion_estimada_segundos ?></td>
                                             <?php if($c['duracion_real_minutos']<10 and (strlen($c['duracion_real_minutos'])<2)){ 
								     				$duracion_real_minutos='0'.$c['duracion_real_minutos'];
								     			  }else{
                                                    $duracion_real_minutos=$c['duracion_real_minutos'];
								     		      }
								     		      if($c['duracion_real_segundos']<10 and (strlen($c['duracion_real_segundos'])<2)){ 
								     				$duracion_real_segundos='0'.$c['duracion_real_segundos'];
								     			  }else{
                                                    $duracion_real_segundos=$c['duracion_real_segundos'];
								     		      } 
								     	    ?>
								     		<td><?php echo $duracion_real_minutos ?>:<?php echo $duracion_real_segundos ?></td>
								     		<?php if($c['tiempo_post_minutos']<10 and (strlen($c['tiempo_post_minutos'])<2)){ 
								     				$tiempo_post_minutos='0'.$c['tiempo_post_minutos'];
								     			  }else{
                                                    $tiempo_post_minutos=$c['tiempo_post_minutos'];
								     		      }
								     		      if($c['tiempo_post_segundos']<10 and (strlen($c['tiempo_post_segundos'])<2)){ 
								     				$tiempo_post_segundos='0'.$c['tiempo_post_segundos'];
								     			  }else{
                                                    $tiempo_post_segundos=$c['tiempo_post_segundos'];
								     		      } 
								     		      if($c['tiempo_post_cuadros']==null){
                                                    $tiempo_post_cuadros='00';
								     		      }elseif($c['tiempo_post_cuadros']<10 and (strlen($c['tiempo_post_cuadros'])<2)){ 
								     				$tiempo_post_cuadros='0'.$c['tiempo_post_cuadros'];
								     			  }else{
                                                    $tiempo_post_cuadros=$c['tiempo_post_cuadros'];
								     		      }
								     	    ?>
								     		<td><?php echo $tiempo_post_minutos ?>:<?php echo $tiempo_post_segundos ?>-<?php echo $tiempo_post_cuadros ?></td>
								     </tr>
				            <?php $numero=$c['numero_capitulo'] ?>
					            <?php if($cont<sizeof($capitulos)){ ?>
					             <?php $capitulo=$capitulos[$cont]['numero_capitulo'] ?>
						            <?php if($capitulo!=$c['numero_capitulo']){ ?>
							            <?php 
                                           if($c['flashback_minutos']==null){
                                              $m='00';
                                           }elseif($c['flashback_minutos']<10 and (strlen($c['flashback_minutos'])<2)){
					                    	   $m='0'.$c['flashback_minutos'];
					                    	}else{
					                    		$m=$c['flashback_minutos'];
					                    	 }

					                    	 
                                           if($c['flashback_segundos']==null){
                                              $s='00';
                                           }elseif($c['flashback_segundos']<10 and (strlen($c['flashback_segundos'])<2)){
					                    	   $s='0'.$c['flashback_segundos'];
					                    	}else{
					                    		$s=$c['flashback_segundos'];
					                    	 }
					                    	
                                            
                                            if($c['flashback_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['flashback_cuadros']<10 and (strlen($c['flashback_cuadros'])<2)){
					                    	   $cuadros='0'.$c['flashback_cuadros'];
					                    	}else{
					                    		$cuadros=$c['flashback_cuadros'];
					                    	 }
					                    	  ?>
							            <tr class="bg_gray">
							            	<td>FLASHB</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php 
                                            if($c['transiciones_minutos']==null){
                                              $m='00';
                                           }elseif($c['transiciones_minutos']<10 and (strlen($c['transiciones_minutos'])<2)){
					                    	   $m='0'.$c['transiciones_minutos'];
					                    	}else{
					                    		$m=$c['transiciones_minutos'];
					                    	 }

					                    	if($c['transiciones_segundos']==null){
                                              $s='00';
                                           }elseif($c['transiciones_segundos']<10 and (strlen($c['transiciones_segundos'])<2)){
					                    	   $s='0'.$c['transiciones_segundos'];
					                    	}else{
					                    		$s=$c['transiciones_segundos'];
					                    	 }
                                        
					                    	if($c['transiciones_cuadros']==null or empty($c['transiciones_cuadros'])){
                                              $cuadros='00';
                                            }elseif($c['transiciones_cuadros']<10 and (strlen($c['transiciones_cuadros'])<2)){
					                    	   $cuadros='0'.$c['transiciones_cuadros'];
					                    	}else{
					                    		$cuadros=$c['transiciones_cuadros'];
					                    	 }
					                     ?>
							            	<td>TRAN</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['stab_minutos']==null){
                                              $m='00';
                                           }elseif($c['stab_minutos']<10 and (strlen($c['stab_minutos'])<2)){
					                    	   $m='0'.$c['stab_minutos'];
					                    	}else{
					                    		$m=$c['stab_minutos'];
					                    	 }


					                    	if($c['stab_segundos']==null){
                                              $s='00';
                                           }elseif($c['stab_segundos']<10 and (strlen($c['stab_segundos'])<2)){
					                    	   $s='0'.$c['stab_segundos'];
					                    	}else{
					                    		$s=$c['stab_segundos'];
					                    	 }
                                         

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>STAB.</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php 
                                           if($c['recap_minutos']==null){
                                              $m='00';
                                           }elseif($c['recap_minutos']<10 and (strlen($c['recap_minutos'])<2)){
					                    	   $m='0'.$c['recap_minutos'];
					                    	}else{
					                    		$m=$c['recap_minutos'];
					                    	 }
                                            
                                            if($c['recap_segundos']==null){
                                              $s='00';
                                           }elseif($c['recap_segundos']<10 and (strlen($c['recap_segundos'])<2)){
					                    	   $s='0'.$c['recap_segundos'];
					                    	}else{
					                    		$s=$c['recap_segundos'];
					                    	 }
					                 

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>RECAP</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['credito_minutos']==null){
                                              $m='00';
                                           }elseif($c['credito_minutos']<10 and (strlen($c['credito_minutos'])<2)){
					                    	   $m='0'.$c['credito_minutos'];
					                    	}else{
					                    		$m=$c['credito_minutos'];
					                    	 }

					                    	if($c['credito_segundos']==null){
                                              $s='00';
                                           }elseif($c['credito_segundos']<10 and (strlen($c['credito_segundos'])<2)){
					                    	   $s='0'.$c['credito_segundos'];
					                    	}else{
					                    		$s=$c['credito_segundos'];
					                    	 }

					                    

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>CRED</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['cabezote_minutos']==null){
                                              $m='00';
                                           }elseif($c['cabezote_minutos']<10 and (strlen($c['cabezote_minutos'])<2)){
					                    	   $m='0'.$c['cabezote_minutos'];
					                    	}else{
					                    		$m=$c['cabezote_minutos'];
					                    	 }

					                    	if($c['cabezote_segundos']==null){
                                              $s='00';
                                           }elseif($c['cabezote_segundos']<10 and (strlen($c['cabezote_segundos'])<2)){
					                    	   $s='0'.$c['cabezote_segundos'];
					                    	}else{
					                    		$s=$c['cabezote_segundos'];
					                    	 }

					                    	

					                    	if($c['cabezote_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['cabezote_cuadros']<10 and (strlen($c['cabezote_cuadros'])<2)){
					                    	   $cuadros='0'.$c['cabezote_cuadros'];
					                    	}else{
					                    		$cuadros=$c['cabezote_cuadros'];
					                    	 }
					                     ?>
							            	<td>T.LABS</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['cortinillas_minutos']==null){
                                              $m='00';
                                           }elseif($c['cortinillas_minutos']<10 and (strlen($c['cortinillas_minutos'])<2)){
					                    	   $m='0'.$c['cortinillas_minutos'];
					                    	}else{
					                    		$m=$c['cortinillas_minutos'];
					                    	 }

					                    	if($c['cortinillas_segundos']==null){
                                              $s='00';
                                           }elseif($c['cortinillas_segundos']<10 and (strlen($c['cortinillas_segundos'])<2)){
					                    	   $s='0'.$c['cortinillas_segundos'];
					                    	}else{
					                    		$s=$c['cortinillas_segundos'];
					                    	 }


					                    	if($c['cortinillas_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['cortinillas_cuadros']<10 and (strlen($c['cortinillas_cuadros'])<2)){
					                    	   $cuadros='0'.$c['cortinillas_cuadros'];
					                    	}else{
					                    		$cuadros=$c['cortinillas_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.cortinillas') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['despedida_minutos']==null){
                                              $m='00';
                                           }elseif($c['despedida_minutos']<10 and (strlen($c['despedida_minutos'])<2)){
					                    	   $m='0'.$c['despedida_minutos'];
					                    	}else{
					                    		$m=$c['despedida_minutos'];
					                    	 }

					                    	if($c['despedida_segundos']==null){
                                              $s='00';
                                           }elseif($c['despedida_segundos']<10 and (strlen($c['despedida_segundos'])<2)){
					                    	   $s='0'.$c['despedida_segundos'];
					                    	}else{
					                    		$s=$c['despedida_segundos'];
					                    	 }

					                    	if($c['despedida_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['despedida_cuadros']<10 and (strlen($c['despedida_cuadros'])<2)){
					                    	   $cuadros='0'.$c['despedida_cuadros'];
					                    	}else{
					                    		$cuadros=$c['despedida_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.despedida') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							             <tr class="bg_gray">
							             <?php if($c['presentacion_minutos']==null){
                                              $m='00';
                                           }elseif($c['presentacion_minutos']<10 and (strlen($c['presentacion_minutos'])<2)){
					                    	   $m='0'.$c['presentacion_minutos'];
					                    	}else{
					                    		$m=$c['presentacion_minutos'];
					                    	 }

					                    	if($c['presentacion_segundos']==null){
                                              $s='00';
                                           }elseif($c['presentacion_segundos']<10 and (strlen($c['presentacion_segundos'])<2)){
					                    	   $s='0'.$c['presentacion_segundos'];
					                    	}else{
					                    		$s=$c['presentacion_segundos'];
					                    	 }


					                    	 if($c['presentacion_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['presentacion_cuadros']<10 and (strlen($c['presentacion_cuadros'])<2)){
					                    	   $cuadros='0'.$c['presentacion_cuadros'];
					                    	}else{
					                    		$cuadros=$c['presentacion_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.presentacion') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							             <?php if($c['imagenes_archivos_minutos']==null){
                                              $m='00';
                                           }elseif($c['imagenes_archivos_minutos']<10 and (strlen($c['imagenes_archivos_minutos'])<2)){
					                    	   $m='0'.$c['imagenes_archivos_minutos'];
					                    	}else{
					                    		$m=$c['imagenes_archivos_minutos'];
					                    	 }

					                    	if($c['imagenes_archivos_segundos']==null){
                                              $s='00';
                                           }elseif($c['imagenes_archivos_segundos']<10 and (strlen($c['imagenes_archivos_segundos'])<2)){
					                    	   $s='0'.$c['imagenes_archivos_segundos'];
					                    	}else{
					                    		$s=$c['imagenes_archivos_segundos'];
					                    	 }


					                    	 if($c['imagenes_archivos_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['imagenes_archivos_cuadros']<10 and (strlen($c['imagenes_archivos_cuadros'])<2)){
					                    	   $cuadros='0'.$c['imagenes_archivos_cuadros'];
					                    	}else{
					                    		$cuadros=$c['imagenes_archivos_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.imagenes_archivo') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            
							            <?php 
							            $cont_minutos_post=$cont_minutos_post+$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['credito_minutos']+$c['cabezote_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos'];
										$cont_segundos_post=$cont_segundos_post+$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['credito_segundos']+$c['cabezote_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos'];
										$cont_cuadros_post=$cont_cuadros_post+$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['credito_cuadros']+$c['cabezote_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['imagenes_archivos_cuadros'];
							             ?>
							            <tr class="bg_total">
							            	<td>TOTAL</td>
							            	<td><?php echo Post_produccion::calculo_tiempo2($cont_minutos_estimado,$cont_segundos_estimado); ?></td>
							            	<?php $cont_minutos_estimado=0;$cont_segundos_estimado=0; ?>
							            	<td><?php echo Post_produccion::calculo_tiempo2($cont_minutos_real,$cont_segundos_real); ?></td>
							            	<?php $cont_minutos_real=0;$cont_segundos_real=0; ?>
							            	<td><?php echo Post_produccion::calculo_tiempo_post_redondeo($cont_minutos_post,$cont_segundos_post,$cont_cuadros_post) ?></td>
							            	<?php $cont_minutos_post=0;$cont_segundos_post=0;$cont_cuadros_post=0 ?>
							            </tr>
							            </tbody>
									</table>
								</div>
						            <?php } ?>

					           <?php } ?>
					           <?php if($cont==sizeof($capitulos)){ ?>
					                    <?php 
                                           if($c['flashback_minutos']==null){
                                              $m='00';
                                           }elseif($c['flashback_minutos']<10 and (strlen($c['flashback_minutos'])<2)){
					                    	   $m='0'.$c['flashback_minutos'];
					                    	}else{
					                    		$m=$c['flashback_minutos'];
					                    	 }

					                    	 
                                           if($c['flashback_segundos']==null){
                                              $s='00';
                                           }elseif($c['flashback_segundos']<10 and (strlen($c['flashback_segundos'])<2)){
					                    	   $s='0'.$c['flashback_segundos'];
					                    	}else{
					                    		$s=$c['flashback_segundos'];
					                    	 }
					                    	
                                            
                                            if($c['flashback_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['flashback_cuadros']<10 and (strlen($c['flashback_cuadros'])<2)){
					                    	   $cuadros='0'.$c['flashback_cuadros'];
					                    	}else{
					                    		$cuadros=$c['flashback_cuadros'];
					                    	 }
					                    	  ?>
							            <tr class="bg_gray">
							            	<td>FLASHB</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php 
                                            if($c['transiciones_minutos']==null){
                                              $m='00';
                                           }elseif($c['transiciones_minutos']<10 and (strlen($c['transiciones_minutos'])<2)){
					                    	   $m='0'.$c['transiciones_minutos'];
					                    	}else{
					                    		$m=$c['transiciones_minutos'];
					                    	 }

					                    	if($c['transiciones_segundos']==null){
                                              $s='00';
                                           }elseif($c['transiciones_segundos']<10 and (strlen($c['transiciones_segundos'])<2)){
					                    	   $s='0'.$c['transiciones_segundos'];
					                    	}else{
					                    		$s=$c['transiciones_segundos'];
					                    	 }
                                        
					                    	if($c['transiciones_cuadros']==null or empty($c['transiciones_cuadros'])){
                                              $cuadros='00';
                                            }elseif($c['transiciones_cuadros']<10 and (strlen($c['transiciones_cuadros'])<2)){
					                    	   $cuadros='0'.$c['transiciones_cuadros'];
					                    	}else{
					                    		$cuadros=$c['transiciones_cuadros'];
					                    	 }
					                     ?>
							            	<td>TRAN</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['stab_minutos']==null){
                                              $m='00';
                                           }elseif($c['stab_minutos']<10 and (strlen($c['stab_minutos'])<2)){
					                    	   $m='0'.$c['stab_minutos'];
					                    	}else{
					                    		$m=$c['stab_minutos'];
					                    	 }


					                    	if($c['stab_segundos']==null){
                                              $s='00';
                                           }elseif($c['stab_segundos']<10 and (strlen($c['stab_segundos'])<2)){
					                    	   $s='0'.$c['stab_segundos'];
					                    	}else{
					                    		$s=$c['stab_segundos'];
					                    	 }
                                         

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>STAB.</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php 
                                           if($c['recap_minutos']==null){
                                              $m='00';
                                           }elseif($c['recap_minutos']<10 and (strlen($c['recap_minutos'])<2)){
					                    	   $m='0'.$c['recap_minutos'];
					                    	}else{
					                    		$m=$c['recap_minutos'];
					                    	 }
                                            
                                            if($c['recap_segundos']==null){
                                              $s='00';
                                           }elseif($c['recap_segundos']<10 and (strlen($c['recap_segundos'])<2)){
					                    	   $s='0'.$c['recap_segundos'];
					                    	}else{
					                    		$s=$c['recap_segundos'];
					                    	 }
					                 

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>RECAP</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['credito_minutos']==null){
                                              $m='00';
                                           }elseif($c['credito_minutos']<10 and (strlen($c['credito_minutos'])<2)){
					                    	   $m='0'.$c['credito_minutos'];
					                    	}else{
					                    		$m=$c['credito_minutos'];
					                    	 }

					                    	if($c['credito_segundos']==null){
                                              $s='00';
                                           }elseif($c['credito_segundos']<10 and (strlen($c['credito_segundos'])<2)){
					                    	   $s='0'.$c['credito_segundos'];
					                    	}else{
					                    		$s=$c['credito_segundos'];
					                    	 }

					                    

					                    	if($c['stab_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
					                    	   $cuadros='0'.$c['stab_cuadros'];
					                    	}else{
					                    		$cuadros=$c['stab_cuadros'];
					                    	 }
					                     ?>
							            	<td>CRED</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['cabezote_minutos']==null){
                                              $m='00';
                                           }elseif($c['cabezote_minutos']<10 and (strlen($c['cabezote_minutos'])<2)){
					                    	   $m='0'.$c['cabezote_minutos'];
					                    	}else{
					                    		$m=$c['cabezote_minutos'];
					                    	 }

					                    	if($c['cabezote_segundos']==null){
                                              $s='00';
                                           }elseif($c['cabezote_segundos']<10 and (strlen($c['cabezote_segundos'])<2)){
					                    	   $s='0'.$c['cabezote_segundos'];
					                    	}else{
					                    		$s=$c['cabezote_segundos'];
					                    	 }

					                    	

					                    	if($c['cabezote_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['cabezote_cuadros']<10 and (strlen($c['cabezote_cuadros'])<2)){
					                    	   $cuadros='0'.$c['cabezote_cuadros'];
					                    	}else{
					                    		$cuadros=$c['cabezote_cuadros'];
					                    	 }
					                     ?>
							            	<td>T.LABS</td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['cortinillas_minutos']==null){
                                              $m='00';
                                           }elseif($c['cortinillas_minutos']<10 and (strlen($c['cortinillas_minutos'])<2)){
					                    	   $m='0'.$c['cortinillas_minutos'];
					                    	}else{
					                    		$m=$c['cortinillas_minutos'];
					                    	 }

					                    	if($c['cortinillas_segundos']==null){
                                              $s='00';
                                           }elseif($c['cortinillas_segundos']<10 and (strlen($c['cortinillas_segundos'])<2)){
					                    	   $s='0'.$c['cortinillas_segundos'];
					                    	}else{
					                    		$s=$c['cortinillas_segundos'];
					                    	 }


					                    	if($c['cortinillas_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['cortinillas_cuadros']<10 and (strlen($c['cortinillas_cuadros'])<2)){
					                    	   $cuadros='0'.$c['cortinillas_cuadros'];
					                    	}else{
					                    		$cuadros=$c['cortinillas_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.cortinillas') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							            <?php if($c['despedida_minutos']==null){
                                              $m='00';
                                           }elseif($c['despedida_minutos']<10 and (strlen($c['despedida_minutos'])<2)){
					                    	   $m='0'.$c['despedida_minutos'];
					                    	}else{
					                    		$m=$c['despedida_minutos'];
					                    	 }

					                    	if($c['despedida_segundos']==null){
                                              $s='00';
                                           }elseif($c['despedida_segundos']<10 and (strlen($c['despedida_segundos'])<2)){
					                    	   $s='0'.$c['despedida_segundos'];
					                    	}else{
					                    		$s=$c['despedida_segundos'];
					                    	 }

					                    	if($c['despedida_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['despedida_cuadros']<10 and (strlen($c['despedida_cuadros'])<2)){
					                    	   $cuadros='0'.$c['despedida_cuadros'];
					                    	}else{
					                    		$cuadros=$c['despedida_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.despedida') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							             <tr class="bg_gray">
							             <?php if($c['presentacion_minutos']==null){
                                              $m='00';
                                           }elseif($c['presentacion_minutos']<10 and (strlen($c['presentacion_minutos'])<2)){
					                    	   $m='0'.$c['presentacion_minutos'];
					                    	}else{
					                    		$m=$c['presentacion_minutos'];
					                    	 }

					                    	if($c['presentacion_segundos']==null){
                                              $s='00';
                                           }elseif($c['presentacion_segundos']<10 and (strlen($c['presentacion_segundos'])<2)){
					                    	   $s='0'.$c['presentacion_segundos'];
					                    	}else{
					                    		$s=$c['presentacion_segundos'];
					                    	 }


					                    	 if($c['presentacion_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['presentacion_cuadros']<10 and (strlen($c['presentacion_cuadros'])<2)){
					                    	   $cuadros='0'.$c['presentacion_cuadros'];
					                    	}else{
					                    		$cuadros=$c['presentacion_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.presentacion') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            <tr class="bg_gray">
							             <?php if($c['imagenes_archivos_minutos']==null){
                                              $m='00';
                                           }elseif($c['imagenes_archivos_minutos']<10 and (strlen($c['imagenes_archivos_minutos'])<2)){
					                    	   $m='0'.$c['imagenes_archivos_minutos'];
					                    	}else{
					                    		$m=$c['imagenes_archivos_minutos'];
					                    	 }

					                    	if($c['imagenes_archivos_segundos']==null){
                                              $s='00';
                                           }elseif($c['imagenes_archivos_segundos']<10 and (strlen($c['imagenes_archivos_segundos'])<2)){
					                    	   $s='0'.$c['imagenes_archivos_segundos'];
					                    	}else{
					                    		$s=$c['imagenes_archivos_segundos'];
					                    	 }


					                    	 if($c['imagenes_archivos_cuadros']==null){
                                              $cuadros='00';
                                            }elseif($c['imagenes_archivos_cuadros']<10 and (strlen($c['imagenes_archivos_cuadros'])<2)){
					                    	   $cuadros='0'.$c['imagenes_archivos_cuadros'];
					                    	}else{
					                    		$cuadros=$c['imagenes_archivos_cuadros'];
					                    	 }
					                     ?>
							            	<td><?php echo lang('global.imagenes_archivo') ?></td>
							            	<td></td>
							            	<td></td>
							            	<td><?php echo $m.':'.$s.'-'.$cuadros; ?></td>
							            </tr>
							            
							            <?php 
							            $cont_minutos_post=$cont_minutos_post+$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['credito_minutos']+$c['cabezote_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos'];
										$cont_segundos_post=$cont_segundos_post+$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['credito_segundos']+$c['cabezote_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos'];
										$cont_cuadros_post=$cont_cuadros_post+$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['credito_cuadros']+$c['cabezote_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['imagenes_archivos_cuadros'];
							             ?>
							            <tr class="bg_total">
							            	<td>TOTAL</td>
							            	<td><?php echo Post_produccion::calculo_tiempo2($cont_minutos_estimado,$cont_segundos_estimado); ?></td>
							            	<?php $cont_minutos_estimado=0;$cont_segundos_estimado=0; ?>
							            	<td><?php echo Post_produccion::calculo_tiempo2($cont_minutos_real,$cont_segundos_real); ?></td>
							            	<?php $cont_minutos_real=0;$cont_segundos_real=0; ?>
							            	<td><?php echo Post_produccion::calculo_tiempo_post_redondeo($cont_minutos_post,$cont_segundos_post,$cont_cuadros_post)?></td>
							            	<?php $cont_minutos_post=0;$cont_segundos_post=0;$cont_cuadros_post=0 ?>
							            </tr>
							           </tbody>
									</table>
								</div>
					           <?php } ?>
						            <?php $cont++; ?> 
									
						<?php } ?>
							
				<?php }else{ ?>		
				No hay datos
				<?php } ?>

		

</div>
</div>

</div>
</div>