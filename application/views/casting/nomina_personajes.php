<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.nomina_personajes') ?>
</div>
<?php $this->load->view('includes/partials/top_nav_solicitudes'); ?>

<nav>
    <ul class="nav_post nav_casting">
        <li><a href="<?=base_url($idioma.'casting')?>" class="buttons active icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.volver') ?></a></li>
        <li><span></span>&nbsp;</li>
        <li><span></span>&nbsp;</li>
        <li><span></span>&nbsp;</li>

    </ul>
</nav>

<div id="inner_content">
	<div class="row">
		<div class="column twelve">
			<div class="info">
			<!-- SELECT DE PRODUCCIONES -->
				<?php if ($producciones): ?>
					<div>
						<label for="produccion"><?php echo lang('global.produccion') ?></label>
						<select name="the_produccion" id="the_produccion" class="three">
							<option value=""><?php echo lang('global.seleccion_opcion') ?></option>	
							<?php foreach ($producciones as $the_produccion): ?>
								<!--?php if($the_produccion['tipo']==2){ ?-->
									<option value="<?=$the_produccion['id_produccion']?>"
										<?php if ($produccion AND $produccion[0]->id_produccion==$the_produccion['id_produccion']): ?>
											selected
										<?php endif ?>
									><?=$the_produccion['nombre_produccion']?></option>
								<!--?php } ?-->
							<?php endforeach ?>
						</select>
					</div>
				<?php endif ?>
			<!-- SELECT DE PRODUCCIONES -->
			</div>
		</div>
		<div class="clr6"></div>
		
		<?php if ($produccion): ?>
				<div class="column twelve">
					<ul class="accordion" style="margin: 6px 6px 0px;">
						<li>
							<div class="title">
							      <h5><b><?php echo lang('global.nomina_personajes') ?></b></h5>
						    </div>
								<!-- NOMINA PERSONAJES NO MENSUALES -->
								<!-- NOMINA PERSONAJES MENSUAL -->
									<div class="content">
										<label><?php echo lang('global.nomina_personaje_mensual') ?></label>
										<div class="clr6"></div>
										<div class="columns four">
											<label for="date_nomina_from_pdf"><?php echo lang('global.mes') ?>:</label>
											<select id="month_selector">
												<option vlaue=""><?php echo lang('global.selecion_un_mes') ?></option>
												<?php $temp_month=""; foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
													$now = explode('-', $fecha_reporte_semanal->fecha_valor);
													if($temp_month!=$now[0].'-'.$now[1]){ 
														$temp_month=$now[0].'-'.$now[1]; ?>
														<option value="<?=$temp_month?>"><?=date("M-Y",strtotime($fecha_reporte_semanal->fecha_valor))?></option>
													<?php } ?>	
												<?php } ?>
											</select>
										</div>
										<div class="columns" style="float:left;">
											<label for="">&nbsp;</label>
									    	<a href="#" id="nomina_mensual_pdf" style="display:none"  class="button secondary icon icon_print" data-idproduccion="<?=$produccion['0']->id_produccion?>"><span></span><?php echo lang('global.imprimir') ?></a>
										</div>
									
								<!-- FIN NOMINA PERSONAJES MENSUAL -->
						
								<!-- FILA DESCUENTOS PERSONAJE -->
								<div class="clr6"></div>
								<div class="columns twelve" id="discounts_actors" style="display:none">
									<h5><?php echo lang('global.liquidacion_nomina_persoanjes') ?></h5>
									<?php echo form_open($idioma.'herramientas/insertar_liquidacion ','id="discounts_actors_form"'); ?>
									<input type="hidden" id="mes_liquidacion" name="mes_liquidacion">
									<input type="hidden" name="idproduccion" value="<?=$produccion['0']->id_produccion?>" class="idproduccion">
									<div id="discounts_actors_section" class="normal_table">
						
									</div>
									<?php if($tipo_user=$this->session->userdata('tipo_pruduction_suite')==1 or $tipo_user=$this->session->userdata('tipo_pruduction_suite')==8){ ?>
									<input type="submit" class="button icon icon_save" value="<?php echo lang('global.guardar_liquidacion') ?>">
									<?php } ?>
									<?php echo form_close() ?>
								</div>
								<!-- FIN FILA DESCUENTOS PERSONAJE -->
								<!-- FIN NOMINA PERSONAJES NO MENSUALES -->
								<div class="clr6"></div>
								</div>
						</li>
					</ul>
				</div>

				<div class="column twelve">
					<div class="column title_section">
					      <h5><?php echo lang('global.nomina_no_mensual') ?></h5>
				    </div>
				    <div class="info with_title">

						<!-- FILTRO DE SEMANAS TABLA -->
						<div class="columns">
							<div class="columnsLeft">
								    <label><?php echo lang('global.fecha_uno') ?></label>
									<select class="semana1">
									    <?php if($fechas_reporte_semanal){ ?>
									            <?php $num=$fechas_reporte_semanal['0']->inicio_semana; ?>
											    <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
												<option value="<?php echo /*$num*/ $fecha_reporte_semanal->fecha_valor ?>"><?php echo lang('global.semana') ?> <?php echo $num ?> <?php echo '('.$fecha_reporte_semanal->fecha_muestra.'-'.$fecha_reporte_semanal->fecha_muestra_2.')'; ?></option>
												<?php $num++; ?>
												<?php } ?>
									    <?php }else{ ?>
								              <option value=""><?php echo lang('global.no_hay_semanas') ?></option>
									    <?php } ?>
									</select>
							</div>
						    <div class="columnsLeft">
						    		<label><?php echo lang('global.fecha_dos') ?></label>
						    		<select  class="semana2">
						    			<?php if($fechas_reporte_semanal){ ?>
						    				     <?php $num=$fechas_reporte_semanal['0']->inicio_semana; ?>
						    				    <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
						    					<option value="<?php echo /*$num*/ $fecha_reporte_semanal->fecha_valor ?>"><?php echo lang('global.semana') ?> <?php echo $num ?> <?php echo '('.$fecha_reporte_semanal->fecha_muestra.'-'.$fecha_reporte_semanal->fecha_muestra_2.')'; ?></option>
						    					<?php $num++; ?>
						    					<?php } ?>
						    		    <?php }else{ ?>
						    	              <option value=""><?php echo lang('global.no_hay_semanas') ?></option>
						    		    <?php } ?>
						    		</select>
						    </div>
						    <div class="columnsLeft">
						    	<label for="">&nbsp;</label>
								<input type="submit" value="<?php echo lang('global.filtrar') ?>" class="filtrar_semana_herramientas button buttonFiltro" style="margin:0">
							</div>
							<div class="columnsLeft">
						    	<label for="">&nbsp;</label>
								<a href="<?php echo base_url($idioma.'casting/nomina_personajes/'.$produccion[0]->id_produccion);?>"><input type="submit" value="<?php echo lang('global.borar_filtro') ?>" class="button buttonFiltro" style="margin:0"></a>
							</div>
						</div>
						<!-- FIN FILTRO DE SEMANAS TABLA -->
						<!-- BOTON IMPRESION -->
						<div class="column alignRight">
							<label for="">&nbsp;</label>
							<!--<a href="<?=base_url()?>excel/excel_nomnia/<?=$produccion[0]->id_produccion?>" target="_blank" class="button icon icon_save" style="margin-top:6px">GUARDAR</a>-->
							<a class="button icon icon_save excel_nomina" style="margin-top:6px"><?php echo lang('global.guardar') ?></a> 
						</div>
						<input type="hidden" id="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>"> 
						<input type="hidden" class="filtro" value="0"> 

						<!-- TABLA DE SEMANAS ELEMENTOS NO MENSUAL -->
						<div class="table_general table_herramientas tablaSinScroll flex_conatiner">	
							<div class="left_table_fix">
								<table class="secondary">
									<thead>
										<tr>
											<td colspan="4"><?php echo lang('global.personajes') ?></td>
										</tr>
									</thead>
									<tr class="gray">
										<td><?php echo lang('global.personajes') ?></td>
										<td><?php echo lang('global.actor') ?></td>
										<td><?php echo lang('global.monto') ?></td>
										<td><?php echo lang('global.tipo_contrato') ?></td>
									</tr>
									<tbody class="tabla_personajes">
									<?php if($personajes){ ?>
											<?php foreach ($personajes as $personaje) { ?>
												<tr id="id_<?php echo $personaje->id ?>" class="personaje_<?php echo $personaje->id ?>">
													<td class="liquidacion">
													<?=$personaje->nombre?>
													<div class="textLiquidacion anchoPersonajeLibretos hideLiquidacion box">
														<?php $explode = explode(",", $personaje->libretos_personaje);?>
														<?php $count = count($explode) ?>
														<?php if($count > 1){ ?>
															<?php foreach($explode as $e) { ?>
															 <?php echo $e ?>
															 <br>
															<?php } ?>
														<?php } ?>
												    </div>
													</td>
													<td><?=$personaje->actor_nombre.' '.$personaje->actor_apellido?></td>
													<td><?='$'.number_format((double)$personaje->monto, 2, '.', ",")?></td>
													<td><?=$personaje->tipo_contrato?></td>
												</tr>
											<?php } ?>
											 <tr>
											 	<td colspan="4"><?php echo lang('global.total') ?></td>
											 </tr>
									<?php } ?>		
								  </tbody>
								</table>
							</div>
							<div class="right_table_fix">
								<table class="secondary tabla_semanas">
									<thead>
										<tr>
										<?php if($fechas_reporte_semanal){ ?>
											<?php $cadena_header=""; $c=1; $num=$fechas_reporte_semanal['0']->inicio_semana; 
											foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
												<td class="semana_<?=$num?> semanas"><?php echo lang('global.semana') ?> <?=$num?></td>	
												<?php $cadena_header.='<td class="semana_'.$num.' semanas">'.$fecha_reporte_semanal->fecha_muestra.'</br>'.$fecha_reporte_semanal->fecha_muestra_2.'</td>';
												 if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
													  strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) 
													or($c==count($fechas_reporte_semanal))  ): ?>
													 
													<?php if ((date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun") 
														 ):
														$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>'; 
														$cadena_header.='<td class="semana_'.$num.' semanas">'.lang("global.liquidacion").'</td>'; ?>
														<td class="semana_<?php echo $num;?> semanas" colspan="2"><?php echo lang('global.liquidacion') ?></td>
													<?php else: 	
														$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
														$cadena_header.='<td class="semana_'.$num.' semanas">'.lang("global.liquidacion").'</td>';
														$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
														$cadena_header.='<td class="semana_'.$num.' semanas">'.lang("global.liquidacion").'</td>';  ?>

														<td colspan="2" class="semana_<?php echo $num;?> semanas"><?php echo lang('global.liquidacion') ?> A</td>
														<td colspan="2" class="semana_<?php echo $num;?> semanas"><?php echo lang('global.liquidacion') ?> B</td>
													<?php endif ?>
													
												<?php endif ?>
											<?php ++$num;  $c++;} ?>
											<?php }else{ ?>
											  <td><?php echo lang('global.no_hay_semanas') ?></td>
											<?php } ?>
										</tr>
									</thead>
									<?php if($fechas_reporte_semanal){ ?>
									<tbody>
									<tr class="gray">
										<?=$cadena_header?>
									</tr>
									</tbody>
									<?php }else{ ?>
									<td><?php echo lang('global.no_hay_semanas') ?></td>
									<?php } ?>
									<?php if($personajes){ ?>

									<?php 
								    $liquidacion_total=array();
								    $liquidacion_total=array();
								   ///////////////////////////
								       $c=1;
								        $num2=$fechas_reporte_semanal['0']->inicio_semana;
								       foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
								            $liquidacion_total[$num2]=0;
								            $c++;$num2++;
								         }   
								     //////////////////    
								 ?>
									<?php $r_totla=0; ?>
									<?php $lista_residuo=""; ?>
									<?php $liquidacion_personaje_total=0; ?>
										<?php foreach ($personajes as $personaje) { ?>
										<?php $acumulado_capitulos = 0; ?>
										<?php $acumulado_dias = 0; ?>
										<?php $acumulado_residuo=0; ?>
										<?php $acumulado_residuo_dias=0; ?>
										<?php $lista_capitulos = ""; ?>
										<tr id="id_<?php echo $personaje->id ?>" class="personaje_<?php echo $personaje->id ?>">
											<?php $fecha="";  ?>

											<?php if($personaje->fecha_inicio_2!="" AND $personaje->fecha_inicio_2!="0000-00-00"){
									          $fecha = $personaje->fecha_inicio_2;
									        }

									        if($personaje->fecha_finalizacion!="" AND $personaje->fecha_finalizacion!="0000-00-00"){
									          $fecha_final = $personaje->fecha_finalizacion;
									        }else{
									          $fecha_final = "";
									        } 
									        $dias_trabajados = 0;
									        ?>
						                    
						                    <?php if($fechas_reporte_semanal){ ?>
						                    <?php $num2=$fechas_reporte_semanal['0']->inicio_semana; $c=1;?>
												<?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal2) { ?>
													<?php if ($fecha AND  strtotime($fecha_reporte_semanal2->fecha_muestra) <= strtotime($fecha) AND strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime($fecha)):
															$dias_trabajados = (strtotime($fecha)-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
															$dias_trabajados = abs($dias_trabajados); 
															$dias_trabajados = floor($dias_trabajados)+1;
															$fecha = date("d-m-Y",strtotime($fecha_reporte_semanal2->fecha_muestra_2)+86400);
														endif ?>
													<?php 
														$capitulos_pagar = 0;
														$capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,date("Y-m-d", strtotime($fecha_reporte_semanal2->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
														if ($capitulos_personaje[0]->capitulos) {
															$acumulado_capitulos += $capitulos_pagar = count(explode(',', $capitulos_personaje[0]->capitulos));
															$lista_capitulos.=$capitulos_personaje[0]->capitulos.',';
														}
                                                        
                                                        $dias_pagar = 0;
														$dias_pagar = $this->model_herramientas->buscar_dias_elemento($personaje->id,date("Y-m-d", strtotime($fecha_reporte_semanal2->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
														if ($dias_pagar[0]->total) {
															$acumulado_dias += $dias_pagar[0]->total;
														}


													?>
													<td class="semana_<?php echo $num2; ?>" data-capitulospagar="<?=$capitulos_pagar?>"><?=$capitulos_pagar?></td>
													
													<?php if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra))) >= strtotime($fecha_reporte_semanal2->fecha_muestra) AND 
															strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2)))
															or($c==count($fechas_reporte_semanal)) ): ?>

														<?php if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2)))) == "Sun" ): ?>

															<?php  
															    $acumulado_capitulos += $acumulado_residuo;
																$acumulado_residuo = 0;
																$acumulado_residuo_dias=0;
																$lista_capitulos.=$lista_residuo; 
																$lista_residuo = "";
															?>
																<!--SECCION OCULTA LISTA DE CAPITULOS-->
																<td class="semana_<?=$num2?> semanas"><?=$acumulado_capitulos?></td>
																<td class='liquidacion semana_<?=$num2?> semanas'>
																	<!--CALCULO DE NOMINA A PAGAR-->
																	<?php 

																	$liquidacion=0;
																	if($personaje->id_tipo_contrato){
															          switch ($personaje->id_tipo_contrato) {
															            case 1:
															              if($personaje->monto and $acumulado_capitulos!=0){
															              	 $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
															                $liquidacion = number_format($personaje->monto, 2, '.', ",");
															              }
															              break;
															            case 2:
															              $liquidacion =$personaje->monto*$acumulado_capitulos;
															              $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
															              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
															              break;
															            case 3:
															                $liquidacion=0;
															                if($personaje->monto){
															                  if($acumulado_capitulos<11){
															                    $liquidacion = $personaje->monto*$acumulado_capitulos;
															                    $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
															                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
															                  }else{
															                  	$liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
															                    $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
															                  }
															                }
															              break;
															            case 4:
															              if($personaje->monto){
															                //$liquidacion = $personaje->dias_trabajados*$personaje->monto;
															                $liquidacion = $acumulado_dias*$personaje->monto;
															                $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
															                $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
															              }
															              break;
															            default:
															              $liquidacion = "0";
															              break;
															          }        
															        }else{
															          $liquidacion = "0";
															        }
																	?>
																	<!--CALCULO DE NOMINA A PAGAR-->
																	<?php $liquidacion_personaje_total=$liquidacion_personaje_total+str_replace(',','',$liquidacion); ?>
																	<?='$'.$liquidacion?>
																	<div class="textLiquidacion hideLiquidacion box <?php echo $r_totla+=$liquidacion ?>">
																		<?php if ($acumulado_capitulos!=0): ?>
																			<?php $explode = explode(",", $lista_capitulos);?>
																			<br>
																			<?php foreach($explode as $e) { ?>
																				<?php echo $e ?>
																				<br>
																			<?php } ?>
																		<?php endif ?>
																	</div>
																</td>
																<?php $acumulado_capitulos=0;$acumulado_dias=0; ?>

														<?php else: ?>	

															<!-- CAPITULOS SIGUIENTE LIQUIDACION -->
															<?php 
															$acumulado_capitulos += $acumulado_residuo;

															$acumulado_residuo = 0;
															$lista_residuo="";
                                                           

															$capitulos_residuo = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,date("Y-m", strtotime($fecha_reporte_semanal2->fecha_muestra)).'-16',date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 

															

															$dias_trabajados = (strtotime('16'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra)))-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
															$dias_trabajados = abs($dias_trabajados); 
															$dias_trabajados = floor($dias_trabajados)+1;

															if ($capitulos_residuo[0]->capitulos) {
																$acumulado_residuo = count($temp = explode(',', $capitulos_residuo[0]->capitulos));
																$lista_residuo = $capitulos_residuo[0]->capitulos;
																for ($i=0; $i < $acumulado_residuo; $i++) { 
																	$lista_capitulos=str_replace($temp[$i].',', ' ', $lista_capitulos); 
																}
															}

															$dias_pagar = 0;
															$dias_pagar = $this->model_herramientas->buscar_dias_elemento($personaje->id,date("Y-m", strtotime($fecha_reporte_semanal2->fecha_muestra)).'-16',date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
															if ($dias_pagar[0]->total) {
																//$liquidacion_residuo += $dias_pagar[0]->total;
															}

															?>

															<!--LIQUIDACION A -->
															<td class="semana_<?=$num2?> semanas" data-c="<?php echo $acumulado_residuo ?>"><?=$acu_te = $acumulado_capitulos-$acumulado_residuo?></td>
															<td class='liquidacion semana_<?=$num2-1?> semanas' data-semana="<?php echo $personaje->id ?>_<?=$num2-1?>">
																<!--CALCULO DE NOMINA A PAGAR-->
																<?php 
																$liquidacion=0;
																$liquidacion_residuo=0;
																if($personaje->id_tipo_contrato){
														          switch ($personaje->id_tipo_contrato) {
														            case 1:
														              if($personaje->monto and $acu_te !=0){
														              	$liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
														                $liquidacion = number_format($personaje->monto, 2, '.', ",");
														              }
														              break;
														            case 2:
														              $liquidacion =$personaje->monto*$acu_te;
														              $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
														              $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
														              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
														              break;
														            case 3:
														                $liquidacion=0;
														                if($personaje->monto){
														                  if($acumulado_capitulos<11){
														                    $liquidacion = $personaje->monto*$acu_te ;
														                    $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
														                    $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
														                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
														                  }else{
														                  	$liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
														                    $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
														                  }
														                }
														              break;
														            case 4:

														              if($personaje->monto){
														              	//$liquidacion = $dias_pagar*$personaje->monto;
														              	$liquidacion = (($acumulado_dias)*$personaje->monto)+$liquidacion_residuo;
														              	$liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
	                                                                    //$liquidacion = (($personaje->dias_trabajados)*$personaje->monto)+$liquidacion_residuo;
	                                                                    $liquidacion_residuo = $liquidacion_residuo*$personaje->monto;
	                                                                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
	                                                                  }
														              break;
														            default:
														              $liquidacion = "0";
														              $liquidacion_residuo = 0;
														              break;
														          }        
														        }else{
														          $liquidacion = "0";
														          $liquidacion_residuo = 0;
														        }
														        $acumulado_capitulos=0;
														        $acumulado_dias=0;
																?>
																<!--CALCULO DE NOMINA A PAGAR-->
																<?='$'.$liquidacion?>
																<?php $liquidacion_personaje_total=$liquidacion_personaje_total+str_replace(',','',$liquidacion); ?>
																<div class="textLiquidacion hideLiquidacion <?php echo $r_totla+=$liquidacion ?> box">
																	<?php if ($acu_te!=0 AND $acu_te!=""): ?>
																		<?php $explode = explode(",", $lista_capitulos);?>
																		<br>
																		<?php foreach($explode as $e) { ?>
																		 <?php echo $e ?>
																		 <br>
																		<?php } ?>
																	<?php endif ?>
																</div>

															</td>

															<!--LIQUIDACION B -->

															<td class="semana_<?=$num2?> semanas"><?=$liquidacion_residuo?></td>
															<td class="liquidacion semana_<?=$num2?> semanas" data-semana="<?php echo $personaje->id ?>_<?=$num2?>"><?='$'.number_format((double)$liquidacion_residuo, 2, '.', ",")?>
																<div class="textLiquidacion  hideLiquidacion box">
																	<?php $explode = explode(",", $lista_residuo);?>
																	<br>
																	<?php foreach($explode as $e) { ?>
																	 <?php echo $e ?>
																	 <br>
																	<?php } ?>
																</div>
															</td>
                                                        
														<?php endif ?>
														<?php $lista_capitulos = ""; ?>
													<?php endif ?>
						                             <?php $num2++; $c++;  ?>
												<?php } ?> 
						                             <?php /*echo $personaje->id.'---'.number_format(round($liquidacion_personaje_total,2), 2, '.', ",").'</br>';
						                             $liquidacion_personaje_total=0;*/?>
											<?php }else{ ?>	
											<td><?php echo lang('global.no_hay_semanas') ?></td>
											<?php } ?>
										</tr>
										<?php } ?>
									<?php }?>	
									<?php 
											if($personajes){ ?>
												<tr>
												
												  <?php 
									                $c=1;
									                $num2=$fechas_reporte_semanal['0']->inicio_semana;
									               foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
									                 <td></td>
									                  <?php 
									                    if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
									                          strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) 
									                          or($c==count($fechas_reporte_semanal)) ){
									                          if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ){
									                             ?>
									                                    <td></td>
									                                    <td>$<?php echo number_format(round($liquidacion_total[$num2],2), 2, '.', ","); ?></td>
									                                <?php 
									                          }else{ ?>
									                                      <td></td>
									                                      <td>$<?php echo number_format(round($liquidacion_total[$num2],2), 2, '.', ","); ?></td>
									                                      <td></td>
									                                      <td></td>
									                              <?php  
									                          }
									                    }
									                    $c++;$num2++;
									                 }   ?>

									           </tr>      
											<?php }?>	
								</table>
							</div>
						</div>
						<!-- TABLA DE SEMANAS ELEMENTOS NO MENSUAL -->
					</div>
				</div>
				<div class="clr"></div>
		<?php endif ?>
	</div>
</div>