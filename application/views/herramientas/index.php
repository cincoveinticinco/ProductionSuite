<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.herramientas') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<div id="inner_content">
	<div class="row">
		<ul class="accordion">
			<li>
				<div class="title">
			      <h5><?php echo lang('global.reportes') ?></h5>
			    </div>
			    <div class="content">
			    	<div class="title_table_content"><?php echo lang('global.reportes_semanal_personaje_elementos') ?></div>
			    	<table class="table_content collapsed">
			    		
			    		<tr>
	    					<td>
	    						<label for=""><?php echo lang('global.seleccion_opcion') ?>:</label>
	    						<div id="semanal_report">
		    					    <div class="columns four" style="float:left;">
					    				<label class="label_check">
							    			<input type="radio" class="semanal_option" name="semanal_elementos" value="0">
							    			<?php echo lang('global.planes') ?>
							    		</label>
				    				</div>
				    				<div class="columns four" style="float:left;">
					    				<label class="label_check">
					    					<input type="radio" class="semanal_option" name="semanal_elementos" value="1">
					    					<?php echo lang('global.personajes') ?>
							    		</label>
				    				</div>
				    				<div class="columns four" style="float:left;">
				    					<label class="label_check">
		    								<input type="radio" class="semanal_option" name="semanal_elementos" value="2">
		    								<?php echo lang('global.elementos') ?>
		    							</label>
				    				</div>
			    				</div>
		    				</td>
		    				<td>
		    					<!-- CHECKBOX DE UNIDADES -->
		    					<label for=""><?php echo lang('global.seleccion_unidad') ?>:</label>
	                            <div id="semanal_reporte_elements">
	                            <?php foreach ($unidades as $unidad) { ?>
			    					<?php if($unidad['fecha_inicio']!=""){ ?>
	                            	<div class="columns three">
							    		<label class="label_check">
							    			<input class="box_unity_elements" type="checkbox" value="<?=$unidad['id']?>" name="unidades[]">
							    			<?php echo lang('global.unidad') ?> <?=$unidad['numero']?>
										</label>
									</div>
									<?php } ?>
			    				<?php } ?>
			    				</div>
			    				<!-- FIN CHECKBOX DE UNIDADES -->
		    				</td>
		    				<td>
		    					<label class="column"><?php echo lang('global.fecha') ?>:</label>
		    					<div class="columns twelve">
									<select id="date_semanal_element_pdf" name="date_semanal_element_pdf" style="width: 50%;height: 32px;vertical-align: middle;">
			                            <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
			                               <option 
			                               <?php if(strtotime($fecha_reporte_semanal->fecha_valor_2)>=strtotime(date("d-m-Y")) AND strtotime($fecha_reporte_semanal->fecha_valor)<=strtotime(date("d-m-Y"))){
		                                   	echo "selected";
		                                   } ?>
			                               value="<?=$fecha_reporte_semanal->fecha_valor?>"><?=$fecha_reporte_semanal->fecha_muestra?></option>
			                            <?php } ?>
			                        </select>
							    	<a href="#" id="semanal_element_pdf"  class="button secondary icon icon_print" data-idproduccion="<?=$produccion['0']->id_produccion?>"><span></span><?php echo lang('global.imprimir') ?></a>
						    	</div>
	    					</td>
	    				</tr>
		    				<!-- NOMINA PERSONAJES MENSUAL -->
		    				<!--<td>
		    					<div class="column twelve">
			    					<label>NOMINA DE PERSONAJES MENSUAL</label>
			    					<div class="columns four">
		    							<label for="date_nomina_from_pdf">Mes:</label>
		    							<select id="month_selector">
		    								<option vlaue="">Seleccione un mes</option>
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
								    	<a href="#" id="nomina_mensual_pdf" style="display:none"  class="button secondary icon icon_print" data-idproduccion="<?=$produccion['0']->id_produccion?>"><span></span>imprimir</a>
					    			</div>
		    					</div>
		    				</td>-->
		    				<!-- FIN NOMINA PERSONAJES MENSUAL -->
			    		</tr>
			    		<tr>
			    			 <!--<td width="50%">
			    				<div class="row">
			    					<div class="column twelve">
	                                    <label for="">REPORTE SEMANAL</label>
	                                </div>
	                                <!-- CHECKBOX DE UNIDADES -->
	                                <!-- <div class="row">
		                               <div id="semanal_report" class="columns seven">
		                                <?php foreach ($unidades as $unidad) { ?>
					    					<?php if($unidad['fecha_inicio']!=""){ ?>
		                                	<div class="columns" style="float:left;">
	    							    		<label>UNIDAD <?=$unidad['numero']?>
	    							    			<input class="box_unity" type="checkbox" value="<?=$unidad['id']?>" name="undiades[]">
												</label>
											</div>
											<?php } ?>
					    				<?php } ?>
					    				</div>-->
				    				
				    				<!-- FIN CHECKBOX DE UNIDADES -->
				    				<!--<?php if($fechas_reporte_semanal){ ?>
				    				<div class="column five">
				    					<label class="column">Fecha:
				    					<!-- LISTA DE FECHAS -->
				    					<!--<select id="date_semanal_pdf" name="date_semanal_pdf" style="width: 164px;">
			                                <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
			                                   <option 
			                                   <?php if(strtotime($fecha_reporte_semanal->fecha_valor_2)>=strtotime(date("d-m-Y")) AND strtotime($fecha_reporte_semanal->fecha_valor)<=strtotime(date("d-m-Y"))){
			                                   	echo "selected";
			                                   } ?>
			                                   value="<?=$fecha_reporte_semanal->fecha_valor?>"><?=$fecha_reporte_semanal->fecha_muestra?></option>
			                                <?php } ?>
			                            </select> </label>-->
			                            <!-- FIN LISTA DE FECHAS -->
			    						<!--<div class="column" style="">
                                      		<a href="#" id="semanal_pdf" class="button secondary icon icon_print" data-idproduccion="<?=$produccion['0']->id_produccion?>"><span></span>imprimir</a>
			    						</div>
			    					</div>	
			    					
			    					
			    					</div>
			    					<?php } ?>
			    				</div>
			    			</td>-->
			    			<!--<td colspan="3">
			    				<div class="row dateRange">
			    					<div class="column twelve">
			    						<label>NOMINA DE PERSONAJES</label>
		    						</div>
		    						<div class="column twelve">
			    						<div class="columns four">
			    							<label for="date_nomina_from_pdf">Desde:</label>
				                        	<input type="text" placeholder="dd/mm/aaaa" id="date_nomina_from_pdf" name="date_nomina_from_pdf" class="required">
			    						</div>
			    						<div class="columns four">
			    							<label for="date_nomina_to_pdf">Hasta:</label>
				                       		<input type="text" placeholder="dd/mm/aaaa" id="date_nomina_to_pdf" name="date_nomina_to_pdf" class="required">
			    						</div>
			    						<div class="columns four" style="margin-top:17px">
			    	    					<a href="#" id="nomina_pdf" class="button secondary icon icon_print" data-idproduccion="<?=$produccion['0']->id_produccion?>"><span></span>imprimir</a>
		    							</div>
	    							</div>
	    						</div>
			    			</td>-->
			    		</tr>

			    		<!-- FILA DESCUENTOS PERSONAJE -->
			    		<!--<tr id="discounts_actors" style="display:none">
			    			<td colspan="2">
			    				<h3>LIQUIDACIÓN NOMINA PERSONAJES MENSUALES</h3>
		    					<?php echo form_open('herramientas/insertar_liquidacion ','id="discounts_actors_form"'); ?>
		    					<input type="hidden" id="mes_liquidacion" name="mes_liquidacion">
		    					<input type="hidden" name="idproduccion" value="<?=$produccion['0']->id_produccion?>" class="idproduccion">
		    					<div id="discounts_actors_section" class="normal_table">

		    					</div>
		    					<input type="submit" class="button icon icon_save" value="Guardar liquidación">
		    					<?php echo form_close() ?>
			    			</td>
			    		</tr>-->
			    		<!-- FIN FILA DESCUENTOS PERSONAJE -->
			    	</table>
			    </div>
			</li>
			<input type="hidden" name="idproduccion" value="<?=$produccion['0']->id_produccion?>" class="idproduccion">
			<?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){ ?>
			<li>
				<div class="title">
			      <h5><?php echo lang('global.edicion_escena') ?></h5>
			    </div>
				<div class="content">
					<?php if($capitulos){ ?>
					<?php echo form_open($idioma.'herramientas/actualizar_escenas','id="herramientas_form"'); ?>
					<table class="table_content">
						<caption><?php echo lang('global.cambiar_parametros') ?></caption>
						<!--	cambiar continuidad-->
						<tr>
							<td>
								<div class="row">
									<div class="column six">
				    					<label class="label_check"><?php echo lang('global.desde') ?></label>
				    				</div>
				    				<div class="column six">
				    					<label class="label_check"><?php echo lang('global.hasta') ?></label>
				    				</div>
			    				</div>
			    				<div class="row">
				    				<div class="column three">
										<label><?php echo lang('global.libreto') ?>: 
										<select id="capitulos_herramientas_from" name="capitulos_herramientas_from">
										<?php foreach ($capitulos as $capitulo) { ?>
											<option data-idcapitulo="<?=$capitulo['id_capitulo']?>" value="<?=$capitulo['numero']?>"><?=$capitulo['numero']?></option>
										<?php } ?>
										</select></label>
									</div>
									<div class="column three">
										<label><?php echo lang('global.escenas') ?>:
										<select id="escenas_herramientas_from" name="escenas_herramientas_from">
										</select></label>
									</div>
									
				    				<div class="column three">
					    				<label><?php echo lang('global.libreto') ?>: 
										<select id="capitulos_herramientas_to" name="capitulos_herramientas_to">
											<?php foreach ($capitulos as $capitulo) { ?>
												<option data-idcapitulo="<?=$capitulo['id_capitulo']?>" value="<?=$capitulo['numero']?>"><?= $capitulo['numero'] ?></option>
											<?php } ?>
										</select></label>
									</div>
									<div class="column three">
										<label><?php echo lang('global.escenas') ?>:
											<select id="escenas_herramientas_to" name="escenas_herramientas_to"></select>
										</label>
									</div>
								</div>
								<div class="row">
									<div class="column three" >
					    				<label>Locación :</label>	
										<!--select name="location_actual" id="locacion_select_herramientas" >
											<option><?php echo lang('global.seleccion_locacion') ?></option>
											<?php foreach ($locacion as $locacion_1) { ?>
												<option value="<?=$locacion_1['id']?>"><?=$locacion_1['nombre']?></option>
											<?php } ?>
										</select-->
										 <div class="" id="locacion_section" > 
				                                        <?php foreach ($locacion as $locacion_1) { ?>
												      <label>
										                   <input name="locacion_select_herramientas" class="locacion_select_herramientas" type="checkbox" value="<?=$locacion_1['id']?>"><?php echo $locacion_1['nombre'] ?>
										              </label>
											<?php } ?>
				                          </div>
									</div>


										<div class="row">
				                                <div class="column three">
				                                    <label for="locacion"><?php echo lang('global.set_actual') ?>:</label>
				                                    <!--select name="set" id="set" class="set" style="display:block!important;">
				                                        <option value="">Todos</option-->
				                                        <div class="sets_locaciones" id="locacion_section" > 
				                                        
				                                        </div>
				                                    <!--/select-->
				                                    <br>
				                                </div>
											</div>
									<div class="column three" >
									</div>
								</div>
							</td>
						</tr>
					</table>
					<ul class="accordion">
						
						<li>
							<div class="title">
								<h5><?php echo lang('global.cambiar_continuidad') ?></h5>
							</div>
							<div class="content">
								<table class="table_content">
									<!--	cambiar continuidad-->
									<tr>
										<td>
											<div class="row">
												<div class="column six">
							    					<label class="label_check"><?php echo lang('global.dia_continuidad') ?></label>
							    				</div>
							    			</div>
							    			<div class="row">
							    				<div class="column four" >
								    				<label for="dia_continuidad_actual"><?php echo lang('global.actual') ?>:
													<input type="text" id="dia_continuidad_actual" name="dia_continuidad_actual" class="number"></label>
												</div>
												<div class="column four" >
													<label for="dia_continuidad"><?php echo lang('global.Nuevo') ?>:
													<input type="text" id="dia_continuidad" name="dia_continuidad" class="number"></label>
												</div>
												<div class="column four" >
													<label for="dia_continuidad">&nbsp;
													<input type="button" data-tipo="1" value="<?php echo lang('global.cambiar') ?>" class="button twelve save_tools "></label>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</li>
						<li>
							<div class="title">
								<h5><?php echo lang('global.cambiar_locacion_set') ?></h5>
							</div>
							<div class="content">
								<table class="table_content">
									<tr>
										<td>
											<div class="row">
												<div class="column five" >
								    				<label><?php echo lang('global.locacion_actual') ?>:</label>	
													<!--select name="location_actual" id="locacion_select_cambio" >
														<option><?php echo lang('global.seleccion_locacion') ?></option>
														<?php foreach ($locacion as $locacion_1) { ?>
															<option value="<?=$locacion_1['id']?>"><?=$locacion_1['nombre']?></option>
														<?php } ?>
													</select-->
													<div class="" id="locacion_section" > 
				                                        <?php foreach ($locacion as $locacion_1) { ?>
															      <label>
													                   <input name="locacion_select_cambio" class="locacion_select_cambio" type="checkbox" value="<?=$locacion_1['id']?>"><?php echo $locacion_1['nombre'] ?>
													              </label>
														<?php } ?>
							                          </div>
												</div>
												<div class="column five" >
								    				<label><?php echo lang('global.locacion_cambiar') ?></label>	
													<!--select name="location" id="location">
														<option><?php echo lang('global.seleccion_locacion') ?></option>
														<?php foreach ($locacion as $locacion_2) { ?>
															<option value="<?=$locacion_2['id']?>"><?=$locacion_2['nombre']?></option>
														<?php } ?>
													</select-->
													<div class="" id="locacion_section" > 
				                                        <?php foreach ($locacion as $locacion_1) { ?>
															      <label>
													                   <input name="location" class="location" type="checkbox" data-name="<?php echo $locacion_1['nombre'] ?>" value="<?=$locacion_1['id']?>"><?php echo $locacion_1['nombre'] ?>
													              </label>
														<?php } ?>
							                          </div>
													<input type="text" id="new_location" name="new_location"placeholder="" style="display:none;">
												</div>
												<div class="columns two">
													<label for="">&nbsp;</label>
													<a href="#" class="button secondary" id="new_item_location"><?php echo lang('global.Nuevo') ?></a>
												</div>
												
											</div>
											<div class="row">
												<div class="columns five">
													<a href="#" class="button twelve" id="add_item_location" style="display:none;"><?php echo lang('global.agregar') ?></a>
							                	</div>
							                	<div class="columns five" style="float:left">
							                		<a href="#" class="button twelve" id="cancel_item_location" style="display:none;"><?php echo lang('global.cancelar') ?></a>
							                	</div>
											</div>
											<input type="hidden" id="idproduccion" value="<?=$produccion[0]->id_produccion?>">
						    				<div class="row">
						    					<div class="column five" >
													<label><?php echo lang('global.set_actual') ?>:</label>
													<!--select name="set_actual" id="set_actual">
													</select-->
													<div class="sets_locaciones_cambio" id="locacion_section" >
				                                        
				                                     </div>
												</div>

						    					<div class="column five">
													<label><?php echo lang('global.set_cambiar') ?></label>	

													<div class="sets_locaciones_a_cambio" id="locacion_a_section" ></div>
												</div>
						    					<!-- div class="column five">
													<label>Sets a Cambiar</label>	
													<select name="set" id="set"></select>
													<input type="text" id="new_set" name="new_set" placeholder="" style="display:none;">
												</div> -->
												<div class="columns two">
													<label for="">&nbsp;</label>
													<a href="#" class="button secondary" id="new_item_set"><?php echo lang('global.Nuevo') ?></a>
												</div>
											</div>
											<div class="row">
												<div class="columns five">
													<a href="#" class="twelve button" id="add_item_set" style="display:none;"><?php echo lang('global.agregar') ?></a>
												</div>
												<div class="columns five" style="float:left">
								                	<a href="#" class="twelve button" id="cancel_item_set" style="display:none;"><?php echo lang('global.cancelar') ?></a>
												</div>
											</div>
											<div class="row">
					                    		<div class="column twelve" style="margin-top:10px">
					                    			<input type="button" data-tipo="2" value="<?php echo lang('global.cambiar') ?>" class="button twelve save_tools">
					                    		</div>
				                    		</div>
										</td>
									</tr>
								</table>
							</div>
						</li>
						<li>
							<div class="title">
								<h5><?php echo lang('global.cambiar_locacion_estudio') ?></h5>
							</div>
							<div class="content">
								<table  class="table_content">
						    		<tr>
										<td>
											<label><?php echo lang('global.locacion_estudio_actual') ?></label>
											<div class="row loc_est_actual">
							    				<div class="columns four">
							    					<label for="tipo_1" class="label_check">
							    					<input id="tipo_1" type="radio" name="locacion_tipo" value="1" class="locacion_tipo"><?php echo lang('global.locacion_a_estudio') ?></label>
							    				</div>
							    				<div class="columns four">
							    					<label for="tipo_1" class="label_check">
							    					<input id="tipo_1" type="radio" name="locacion_tipo" value="2" class="locacion_tipo"><?php echo lang('global.estudio_a_locacion') ?></label>
							    				</div>
							    				<div class="columns four">
							    					<input type="button" data-tipo="3" value="<?php echo lang('global.cambiar') ?>" class="button twelve save_tools">
							    				</div>
							    			</div>	
							    				
										</td>
						    		</tr>
						    	</table>
							</div>
						</li>
						<li>
							<div class="title">
								<h5><?php echo lang('global.cambiar_dia_noche') ?></h5>
							</div>
							<div class="content">
								<table  class="table_content">
						    		<tr>
										<td>
											<label><?php echo lang('global.dia_noche_actual') ?></label>
											<div class="row d_n_actual">
						    				 <div class="columns four">
							    				 <label for="tipo_1" class="label_check">
							    				 <input id="tipo_1" type="radio" name="dia_noche" value="1" class="dia_noche"><?php echo lang('global.dia_a_noche') ?></label>
						    				 </div>
						    				 <div class="columns four">
							    				 <label for="tipo_1" class="label_check">
							    				 <input id="tipo_1" type="radio" name="dia_noche" value="2" class="dia_noche"><?php echo lang('global.noche_a_dia') ?></label>	
						    				 </div>
						    				 <div class="columns four">
													<input type="button" data-tipo="4" value="<?php echo lang('global.cambiar') ?>" class="button twelve save_tools">
												</div>
						    				</div>	
										</td>
						    		</tr>
						    	</table>
							</div>
						</li>
						<li>
							<div class="title">
								<h5><?php echo lang('global.cambiar_interior_exterior') ?></h5>
							</div>
							<div class="content">
								<table  class="table_content">
						    		<tr>
										<td>
											<label><?php echo lang('global.interior_exterior_actual') ?></label>
											<div class="row i_e_actual">
						    				 <div class="columns four">
							    				 <label for="tipo_1" class="label_check">
							    				 <input id="tipo_1" type="radio"  name="int_ext" value="1" class="int_ext"><?php echo lang('global.interior_a_exterior') ?></label>	
						    				 </div>
						    				 <div class="columns four">
							    				 <label for="tipo_1" class="label_check">
							    				 <input id="tipo_1" type="radio"   name="int_ext" value="2" class="int_ext"><?php echo lang('global.exterior_a_interior') ?></label>	
						    				 </div>
						    				 <div class="columns four">
													<input type="button" data-tipo="5" value="<?php echo lang('global.cambiar') ?>" class="button twelve save_tools">
												</div>
						    				</div>	
										</td>
						    		</tr>
						    	</table>
							</div>
						</li>
					</ul>
						
					
					<!-- -->
					
					
			    	
			    	
			    	<?php echo form_close(); ?>
					<?php } ?>
					<div id="alertSection" style="display:none;"></div>
				</div>
			</li>
			<li>
				<div class="title">
			      <h5><?php echo lang('global.unificacion_personajes') ?></h5>
			    </div>
				<div class="content">
					<table class="table_content collapsed">
						<tr> 
							<td>
								<div class="row">
									<div class="columns">
										<label class="label_margin"><?php echo lang('global.cambiar_personajes_de') ?>: </label>
									
										<?php if($elementos){ ?>
										<div id="locacion_section" class="">
										  <?php foreach ($elementos as $e) {?>
										       <label> 
                                                    <input name="personaje1[]" type="checkbox" value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?>  
                                       		   </label>
										    <?php } ?>
										</div>
										<!--select class="personaje1">
										    <?php foreach ($elementos as $e) {?>
										      <option value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?></option>
										    <?php } ?>
										</select-->
										<?php } ?>

									    <label style="display:none" class='error label1'><?php echo lang('global.campo_requerido') ?></label>
								    </div>
								    <div class="columns">
										<label class="label_margin"><?php echo lang('global.a') ?>: </label>
									
										<?php if($elementos){ ?>
										<!--select class="personaje2">
										    <?php foreach ($elementos as $e) {?>
										      <option value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?></option>
										    <?php } ?>
										</select-->
										<div id="locacion_section" class="">
										  <?php foreach ($elementos as $e) {?>
										       <label> 
                                                    <input name="personaje2" class="personaje2" data-name="<?php echo $e['nombre'] ?>" type="checkbox" value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?>  
                                       		   </label>
										    <?php } ?>
										</div>
										<?php } ?>
										<label style="display:none" class='error label2'><?php echo lang('global.campo_requerido') ?></label>
									</div>
									<div class="column" style="float:left">
										<label for="">&nbsp;</label>
										<input type="button" value="<?php echo lang('global.cambiar') ?>" class="button unificacion_personaje">
								  		
									</div>
									<div class="clr"></div>
									<div>
										<div id="alertUnificacion" style="display:none;"></div>
									</div>
								</div>
							</td>
						</tr>
					</table>
			    </div>	
			</li>
				<?php if($this->session->userdata('tipo_pruduction_suite')==1){ ?>
					<li>
						<div class="title">
					      <h5><?php echo lang('global.elminar_fotos_produccion') ?></h5>
					    </div>
						<div class="content">
							<table class="table_content collapsed">
							<a href="#" data-idproduccion="<?php echo $produccion['0']->id_produccion ?>" class="button eliminar_fotos"><?php echo lang('global.elminar_fotos_produccion') ?></a>
							</table>
						</div>	
					</li>	
				<?php } ?>	
			<?php } ?>
		</ul>
	</div>
</div>
<!--<div class="columns">
	<div class="columnsLeft">
		    <label>Fecha uno</label>
			<select class="semana1">
			    <?php if($fechas_reporte_semanal){ ?>
			            <?php $num=$fechas_reporte_semanal['0']->inicio_semana; ?>
					    <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
						<option value="<?php echo $num ?>">SEMANA <?php echo $num ?> <?php echo '('.$fecha_reporte_semanal->fecha_muestra.'-'.$fecha_reporte_semanal->fecha_muestra_2.')'; ?></option>
						<?php $num++; ?>
						<?php } ?>
			    <?php }else{ ?>
		              <option value="">No hay semanas</option>
			    <?php } ?>
			</select>
	</div>
    <div class="columnsLeft">
    		<label>Fecha dos</label>
    		<select  class="semana2">
    			<?php if($fechas_reporte_semanal){ ?>
    				     <?php $num=$fechas_reporte_semanal['0']->inicio_semana; ?>
    				    <?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
    					<option value="<?php echo $num ?>">SEMANA <?php echo $num ?> <?php echo '('.$fecha_reporte_semanal->fecha_muestra.'-'.$fecha_reporte_semanal->fecha_muestra_2.')'; ?></option>
    					<?php $num++; ?>
    					<?php } ?>
    		    <?php }else{ ?>
    	              <option value="">No hay semanas</option>
    		    <?php } ?>
    		</select>
    </div>
	<input type="submit" value="Filtar" class="filtrar_semana_herramientas button buttonFiltro">
</div>
<a href="<?=base_url()?>excel/excel_nomnia/<?=$produccion[0]->id_produccion?>" target="_blank" class="button icon icon_save btnGuardar">GUARDAR</a>


<div class="table_general table_herramientas tablaSinScroll">	
	<div class="columnIzq">
		<table class="secondary">
			<thead>
				<tr>
					<td colspan="4">PERSONAJE</td>
				</tr>
			</thead>
			<tr class="gray">
				<td>PERSONAJE</td>
				<td>ACTOR</td>
				<td>MONTO</td>
				<td>TIPO CONTRATO</td>
			</tr>
			<?php if($personajes){ ?>
					<?php foreach ($personajes as $personaje) { ?>
						<tr>
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
			<?php } ?>		
		
		</table>
	</div>
	<div class="columnDer">
		<table class="secondary">
			<thead>
				<tr>
				<?php if($fechas_reporte_semanal){ ?>
					<?php $cadena_header=""; $num=$fechas_reporte_semanal['0']->inicio_semana; foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { ?>
						<td class="semana_<?=$num?> semanas">SEMANA <?=$num?></td>	
						<?php $cadena_header.='<td class="semana_'.$num.' semanas">'.$fecha_reporte_semanal->fecha_muestra.'</br>'.$fecha_reporte_semanal->fecha_muestra_2.'</td>';
						 if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
							  strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) ): ?>
							
							<?php if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ):
								$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>'; 
								$cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>'; ?>
								<td class="semana_<?php echo $num;?> semanas" colspan="2">LIQUIDACIÓN</td>
							<?php else: 	
								$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
								$cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>';
								$cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
								$cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>';  ?>

								<td colspan="2" class="semana_<?php echo $num;?> semanas">LIQUIDACIÓN A</td>
								<td colspan="2" class="semana_<?php echo $num;?> semanas">LIQUIDACIÓN B</td>
							<?php endif ?>
							
						<?php endif ?>
					<?php ++$num; } ?>
					<?php }else{ ?>
					  <td>No hay semanas</td>
					<?php } ?>
				</tr>
			</thead>
			<?php if($fechas_reporte_semanal){ ?>
			<tr class="gray">
				<?=$cadena_header?>
			</tr>
			<?php }else{ ?>
			<td>no hay semanas</td>
			<?php } ?>
			<?php if($personajes){ ?>
				<?php foreach ($personajes as $personaje) { ?>
				<?php $acumulado_capitulos = 0; ?>
				<?php $acumulado_residuo=0; ?>
				<?php $lista_capitulos = ""; ?>
				<tr>
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
                    <?php $num2=$fechas_reporte_semanal['0']->inicio_semana; ?>
						<?php foreach ($fechas_reporte_semanal as $fecha_reporte_semanal2) { ?>
							<?php if ($fecha AND  strtotime($fecha_reporte_semanal2->fecha_muestra) <= strtotime($fecha) AND strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime($fecha)):
									$dias_trabajados = (strtotime($fecha)-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
									$dias_trabajados = abs($dias_trabajados); 
									$dias_trabajados = floor($dias_trabajados)+1;
									$fecha = date("Y-M-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2)+86400);
								endif ?>
							<?php 
								$capitulos_pagar = 0;
								$capitulos_personaje = $this->model_herramientas-> buscar_capitulos_elemento($personaje->id,date("Y-m-d", strtotime($fecha_reporte_semanal2->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
								if ($capitulos_personaje[0]->capitulos) {
									$acumulado_capitulos += $capitulos_pagar = count(explode(',', $capitulos_personaje[0]->capitulos));
									$lista_capitulos.=$capitulos_personaje[0]->capitulos.',';
								}
							?>
							<td class="semana_<?php echo $num2; ?> semanas"><?=$capitulos_pagar?></td>

							<?php if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra))) >= strtotime($fecha_reporte_semanal2->fecha_muestra) AND 
									strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2))) ): ?>

								<?php if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2)))) == "Sun" ): ?>
										<td class="semana_<?=$num2?> semanas"><?=$acumulado_capitulos?></td>
										<td class='liquidacion semana_<?=$num2?> semanas'>
											<?php 
											$liquidacion=0;
											if($personaje->id_tipo_contrato){
									          switch ($personaje->id_tipo_contrato) {
									            case 1:
									              if($personaje->monto and $acumulado_capitulos!=0){
									                $liquidacion = number_format($personaje->monto, 2, '.', ",");
									              }
									              break;
									            case 2:
									              $liquidacion =$personaje->monto*$acumulado_capitulos;
									              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
									              break;
									            case 3:
									                $liquidacion=0;
									                if($personaje->monto){
									                  if($acumulado_capitulos<11){
									                    $liquidacion = $personaje->monto*$acumulado_capitulos;
									                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
									                  }else{
									                    $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
									                  }
									                }
									              break;
									            case 4:
									              if($personaje->monto){
									                $liquidacion = $personaje->dias_trabajados*$personaje->monto;
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
											<?='$'.$liquidacion?>
											<div class="hide box">
												<?=$lista_capitulos?>
											</div>
										</td>
								<?php else: ?>	

									<?php 
									$acumulado_capitulos += $acumulado_residuo;

									$acumulado_residuo = 0;
									$lista_residuo="";
									$capitulos_residuo = $this->model_herramientas-> buscar_capitulos_elemento($personaje->id,date("Y-m", strtotime($fecha_reporte_semanal2->fecha_muestra)).'-15',date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
									//echo $this->db->last_query().'</br></br>';
									$dias_trabajados = (strtotime('16'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra)))-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
									$dias_trabajados = abs($dias_trabajados); 
									$dias_trabajados = floor($dias_trabajados)+1;

									if ($capitulos_residuo[0]->capitulos) {
										$acumulado_residuo = count($temp = explode(',', $capitulos_residuo[0]->capitulos));
										$lista_residuo = $capitulos_residuo[0]->capitulos;
										for ($i=0; $i < $acumulado_residuo; $i++) { 
											str_replace($temp[$i].',', '', $lista_capitulos); 
										}
									}
									?>

									<td class="semana_<?=$num2?> semanas"><?=$acumulado_capitulos-$acumulado_residuo?></td>
									<td class='liquidacion semana_<?=$num2?> semanas'>
										<?php 
										$liquidacion=0;
										$liquidacion_residuo=0;
										if($personaje->id_tipo_contrato){
								          switch ($personaje->id_tipo_contrato) {
								            case 1:
								              if($personaje->monto and $acumulado_capitulos!=0){
								                $liquidacion = number_format($personaje->monto, 2, '.', ",");
								              }
								              break;
								            case 2:
								              $liquidacion =$personaje->monto*$acumulado_capitulos;
								              $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
								              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
								              break;
								            case 3:
								                $liquidacion=0;
								                if($personaje->monto){
								                  if($acumulado_capitulos<11){
								                    $liquidacion = $personaje->monto*$acumulado_capitulos;
								                    $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
								                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
								                  }else{
								                    $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
								                  }
								                }
								              break;
								            case 4:
								              if($personaje->monto){
								                $liquidacion = (($personaje->dias_trabajados-$dias_trabajados)*$personaje->monto)+$liquidacion_residuo;
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
										?>
										<?='$'.$liquidacion?>
										<div class="textLiquidacion hideLiquidacion box">
											<?php $explode = explode(",", $lista_capitulos);?>
											<br>
											<?php foreach($explode as $e) { ?>
											 <?php echo $e ?>
											 <br>
											<?php } ?>
										</div>

									</td>


									<td class="semana_<?=$num2?> semanas"><?=$acumulado_residuo?></td>
									<td class="semana_<?=$num2?> semanas"><?='$'.number_format((double)$liquidacion_residuo, 2, '.', ",")?>
										<div class="textLiquidacion hideLiquidacion box">
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
                             <?php $num2++; ?>
						<?php } ?>
					<?php }else{ ?>	
					<td>No hay semanas</td>
					<?php } ?>
				</tr>
				<?php } ?>
			<?php }?>	
		</table>
	</div>
</div>-->
<!-- TABLA DE SEMANAS ELEMENTOS NO MENSUAL -->