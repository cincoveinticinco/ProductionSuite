<?php $idioma = $this->lang->lang().'/'; ?>
<nav>
<a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
<!--<a href="escenas.html">ESCENAS</a>
<a href="elementos.html">ELEMENTOS</a>-->
<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
<?php if($produccion['0']->estado!=2){   ?>
<?php $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite')); 
      if($user){
        if($user[0]['id_rol_otros']!=2 and $user[0]['id_rol_otros']!=6){ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion.'/'.$escena['0']->id_capitulo.'/'.$id_escena.'/'.$escena['0']->dia_cont) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
        <?php } ?>
      <?php }else{ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion.'/'.$escena['0']->id_capitulo.'/'.$id_escena.'/'.$escena['0']->dia_cont) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
      <?php } ?>
 <?php } ?>
<!--<a href="index.html">CERRAR SESIÓN</a>-->
</nav>
<div class="clr"></div>
<div class="divider"></div>
<div class="resumen" style="margin:0px;">
	<div class="col">
		<h3><?php echo $produccion['0']->nombre_produccion  ?> / Unidad <?php echo $unidad['0']['numero'] ?> <?php echo date("d-M-Y",strtotime($escena['0']->fecha_inicio)); ?> / LIB-<?php echo $escena['0']->numero_capitulo; ?>-ESC-<?php echo $escena['0']->numero_escena; ?>	 </h3>
	</div>	
	<div class="clr"></div>
	<div class="col" style="width:24.3%;">
		<a href="<?php echo base_url($idioma.'continuidad/plan_diario/'.$produccion[0]->id_produccion.'/'.$id_unidad.'/'.$fecha_unidad) ?>"  class="btn_volver_plan fl" style="width:100% !important;"><?php echo lang('global.volver_plan_diario') ?></a>
	</div>
	<div class="col" style="width:24.3%;">
		<div style="width:93%; background:#ccc; padding:9px; background: #dd7644; color: #fff;">
			<strong><?php echo lang('global.tiempo_estimado') ?>:</strong> <?php echo $escena[0]->duracion_estimada_minutos.':'.$escena[0]->duracion_estimada_segundos;  ?>
		</div>
	</div>	
	<div class="col" style="width:49.6%;">
		<div style="width:96%; background:#ccc; padding:9px;">
			<strong><?php echo lang('global.locacion') ?> / <?php echo lang('global.set') ?>:</strong> <?php echo $escena['0']->locacion; ?> / <?php echo $escena['0']->sets; ?>
		</div>
	</div>
	<div class="clr"></div>	
</div>

<div class="contenido_sup" style="padding:0;">
	<div class="col" style="width:99%; padding:0px;">
		<table cellpadding="0" cellspacing="0" class="plan_diario" width="100%">
			<thead>
				<tr>
				  <td>#</td>
				  <td><?php echo lang('global.lib') ?></td>
				  <td><?php echo lang('global.esc') ?></td>
				  <td>CONT</td>
				  <td><?php echo lang('global.pag') ?></td>
				  <td><?php echo lang('global.descripcion') ?></td>
				  <td><?php echo lang('global.guion') ?></td>
				  <td><?php echo lang('global.dia_noche') ?></td>
				  <td>INT/EXT</td>
				  <td>LOC/EST/TU</td>
				</tr>
			</thead>
			<tbody>
					<?php 
					$color_state='';
					switch ($escena[0]->estado_escena) {
					case 1:
						$color_state="td_yellow";
						break;
					case 2:
					    $color_state="td_yellow";	
					    break;
					}
					?>
				<tr class="<?php echo $color_state ?>";>
				  <td><?php echo $escena['0']->orden; ?></td>
				  <td><?php echo $escena['0']->numero_capitulo; ?></td>
				  <td><?php echo $escena['0']->numero_escena; ?></td>
				  <td><?php echo $escena['0']->dia_cont; ?></td>
				  <td><?php echo $escena['0']->libreto; ?></td>
				  <td>
				  	  <a class="fancybox" href="#inline1" title="<?php echo lang('global.descripcion') ?>">
				  	  	<?php 
				  	  		echo Continuidad::corta_palabra($escena['0']->descripcion_escena,35);
                              if(strlen($escena['0']->descripcion_escena)>=35){
                                echo '...';
                        	};
				  	  	 ?>
					  </a>
					  	<div id="inline1" style="width:800px;display: none; text-align:justify;">
							<p>
								<?php echo $escena['0']->descripcion_escena; ?>
							</p>
						</div>
				  </td>
				  <td>
				  	<a class="fancybox" href="#inline2" title="<?php echo lang('global.guion') ?>">
				  		<?php 
				  	  		echo Continuidad::corta_palabra($escena['0']->guion,35);
                              if(strlen($escena['0']->guion)>=35){
                                echo '...';
                        	};
				  	  	 ?>
				    </a>
				    	<div id="inline2" style="width:800px;display: none; text-align:justify;">
							<textarea name="" id="" style="width:780px; height:500px; background:transparent; padding: 0 20px 0 0;">
								<?php echo $escena['0']->guion; ?>
							</textarea>
						</div>
				  </td>
				  <td><?php echo strtoupper($escena['0']->dian_noche); ?></td>
				  <td><?php echo strtoupper($escena['0']->int_ext); ?></td>
				  <td><?php echo mb_strtoupper($escena['0']->tipo)	; ?></td>
				</tr>
			</tbody>
		</table>

		<?php if($permisos_usuario=="write"){?>
			<table cellspacing="0" cellpadding="0" width="100%" class="table_tiempo" id="t_tiempos">
					<input type="hidden" class="id_plan_escena" value="<?php echo $escena['0']->id_plan_escena ?>">
						<tr>
							<td>
								<?php
		                        	$time = false;
		                        	if($escena['0']->comienzo_ens!="" AND $escena['0']->comienzo_ens!="0"){
		                        		$delete = array(":", " ");
										$time = str_replace($delete, "", date("h:i A",strtotime($escena['0']->comienzo_ens)));
				                        $time = str_split($time, 2);
		                        	} 
		                        ?> 
		                        
								<div class="tomar hora_servidor-1" >
									<div>
									<input type="hidden" value="<?php echo $id_produccion ?>" class="id_produccion">
									<strong><?php echo lang('global.inicio_ensayo') ?></strong>
									<input type="text" class="hora" id="tomar_hora_ensayo-2" value="<?php if($escena['0']->comienzo_ens){ echo date("h:i:s A",strtotime($escena['0']->comienzo_ens)); } ?>">
									 <?php if($plan_actual[0]->estado_plan!="Cerrado" and $produccion['0']->estado!=2){ ?>
										 <?php if($escena['0']->comienzo_ens){ ?>
										   <a  class="tomar_hora_manual"  data-tipo='1'><?php echo lang('global.modificar') ?></a>
										   <input type="hidden" value="<?php echo $id_escena ?>" class="id_escena">
											
											<a href="#asignarEscenasWrap" id="asignarEscenasLink" class="fancybox button"><?php echo lang('global.multiescenas') ?></a>
										 <?php }else{ ?>
		                                   <a  class="btn_tomar_ahora" id="tomar_hora_ensayo" data-tipo='1'><?php echo lang('global.tomar_ahora') ?></a>
										 <?php } ?>
		 							<?php } ?>
									</div>
								</div>
								<div class="tomar hora_manual-1" style="display:none">
								<strong><?php echo lang('global.inicio_ensayo') ?></strong>
									<table cellspacing="0" cellpadding="0" class="horas_datos">
										<tr>
											<td><?php echo lang('global.time') ?></td>
											<td><?php echo lang('global.minutos') ?></td>
											<td>am/pm</td>
										</tr>
										<tr>
											<td>
												<select class="horas-1" id="incio_ensayo_hora">
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=1; $i <= 12; $i++) { 
													if(strlen($i)<2){
														$horas = '0'.$i;
													}else{
														$horas = $i;
													}
												?> 
												<option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="minutos-1" id="incio_ensayo_minutos">
													<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=0; $i <= 59; $i++) { 
													if(strlen($i)<2){
														$minutos = '0'.$i;
													}else{
														$minutos = $i;
													}?>
												<option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="am_pm-1" id="inicio_ensayo_am_pm">
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
												<option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
												</select>
											</td>
										</tr>
									</table>
									
									<a class="btn_tomar_ahora_manual" id="tomar_hora_ensayo" data-tipo='1'><?php echo lang('global.guardar') ?></a>
								</div>	
								<div class="clr"></div>
							</td>
							<td>
							<?php
		                        	$time = false;
		                        	if($escena['0']->comienzo_prod!="" AND $escena['0']->comienzo_prod!="0"){
		                        		$delete = array(":", " ");
										$time = str_replace($delete, "", date("h:i A",strtotime($escena['0']->comienzo_prod)));
				                        $time = str_split($time, 2);
		                        	} 
		                        ?> 
								<div class="tomar hora_servidor-2">
									<div>
									<strong><?php echo lang('global.inicio_grabacion_') ?></strong>
									<input type="text" class="hora" id="tomar_hora_produccion-2" value="<?php if($escena['0']->comienzo_prod){ echo date("h:i:s A",strtotime($escena['0']->comienzo_prod)); }?>">
									<?php if($plan_actual[0]->estado_plan!="Cerrado" and $produccion['0']->estado!=2){ ?>
										<?php if($escena['0']->comienzo_prod){ ?>
											<a  class="tomar_hora_manual"  data-tipo='2'><?php echo lang('global.modificar') ?></a>
										<?php }else{ ?>
											<a  class="btn_tomar_ahora" id="tomar_hora_produccion" data-tipo='2'><?php echo lang('global.tomar_ahora') ?></a>
										<?php } ?>
									<?php } ?>
									</div>
								</div>
								<div class="tomar hora_manual-2" style="display:none">
									<strong><?php echo lang('global.inicio_grabacion_') ?></strong>
									<table cellspacing="0" cellpadding="0" class="horas_datos">
										<tr>
											<td><?php echo lang('global.time') ?></td>
											<td><?php echo lang('global.minutos') ?></td>
											<td>am/pm</td>
										</tr>
										<tr>
											<td>
												<select class="horas-2" id="inicio_produccion_hora" >
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=1; $i <= 12; $i++) { 
													if(strlen($i)<2){
														$horas = '0'.$i;
													}else{
														$horas = $i;
													}
												?> 
												<option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="minutos-2" id="inicio_produccion_minutos">
													<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=0; $i <= 59; $i++) { 
													if(strlen($i)<2){
														$minutos = '0'.$i;
													}else{
														$minutos = $i;
													}?>
												<option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="am_pm-2" id="inicio_produccion_hora_am_pm">
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
												<option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
												</select>
											</td>
										</tr>
									</table>
									<a class="btn_tomar_ahora_manual" id="tomar_hora_produccion" data-tipo='2'><?php echo lang('global.guardar') ?></a>
								</div>	
							</td>
							<td>
								<?php
		                        	$time = false;
		                        	if($escena['0']->fin_produccion!="" AND $escena['0']->fin_produccion!="0"){
		                        		$delete = array(":", " ");
										$time = str_replace($delete, "", date("h:i A",strtotime($escena['0']->fin_produccion)));
				                        $time = str_split($time, 2);
		                        	} 
		                        ?> 
								<div class="tomar hora_servidor-3">
									<div>
									<strong><?php echo lang('global.fin_grabacion_') ?></strong>
									<input type="text" class="hora" id="tomar_fin_produccion-2" value="<?php if($escena['0']->fin_produccion){ echo date("h:i:s A",strtotime($escena['0']->fin_produccion)); }?>">
									<?php if($plan_actual[0]->estado_plan!="Cerrado" and $produccion['0']->estado!=2){ ?>
										<?php if($escena['0']->fin_produccion){ ?>
											<a  class=" tomar_hora_manual"  data-tipo='3'><?php echo lang('global.modificar') ?></a>
										<?php }else{ ?>
											<a  class="btn_tomar_ahora" id="tomar_fin_produccion" data-tipo='3'><?php echo lang('global.tomar_ahora') ?></a>
										<?php } ?>
									<?php } ?>									
									</div>
								</div>
								<div class="tomar hora_manual-3" style="display:none">
								<strong><?php echo lang('global.fin_grabacion_') ?></strong>
								<table cellspacing="0" cellpadding="0" class="horas_datos">
										<tr>
											<td><?php echo lang('global.hora') ?></td>
											<td><?php echo lang('global.minutos') ?></td>
											<td>am/pm</td>
										</tr>
										<tr>
											<td>
												<select class="horas-3" id="fin_produccion_hora">
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=1; $i <= 12; $i++) { 
													if(strlen($i)<2){
														$horas = '0'.$i;
													}else{
														$horas = $i;
													}
												?> 
												<option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="minutos-3" id="fin_produccion_minutos">
													<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<?php for ($i=0; $i <= 59; $i++) { 
													if(strlen($i)<2){
														$minutos = '0'.$i;
													}else{
														$minutos = $i;
													}?>
												<option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
												<?php } ?>
												</select>
											</td>
											<td>
												<select class="am_pm-3" id="fin_produccion_am_pm">
												<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
												<option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
												<option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
												</select>
											</td>
										</tr>
									</table>
										<a class="btn_tomar_ahora_manual" id="tomar_fin_produccion" data-tipo='3'><?php echo lang('global.guardar') ?></a>
								</div>		
							</td>
							<td>
								<div class="tomar treal">
									<div class="ancho_a">
									<strong><?php echo lang('global.tiempo_real') ?></strong>
										<div class="tr_real">
											<span class="cronometro-span hidden_span" id="cronometro-span">
												<span style="padding: 0px !important;"><span id="minutos_span">00</span>:<span id="segundos_span">00</span></span>
											</span>
											<div class="input_div" id="input_spinner">
												<input type="number" min="0" placeholder="MM" value="<?php if(($escena['0']->duracion_real_minutos<10 or $escena['0']->duracion_real_minutos=='0') and $escena['0']->duracion_real_minutos!='00' and strlen($escena['0']->duracion_real_minutos)<1){ 
													  echo '0'.$escena['0']->duracion_real_minutos; 
													}else{ 
													  	echo $escena['0']->duracion_real_minutos;} ?>" name="minutos_real" id="minutos_real" class="minutos_real clear_on_focus">
                                                <!--input type="text" value="00" id="minutos_real2" class="minutos_real" style="display:none"-->
											    <span style="width: 2px;padding: 0px;display: inline;">:</span>
												<input type="number" min="0" max="60" placeholder="SS" value="<?php if($escena['0']->duracion_real_segundos<10 and $escena['0']->duracion_real_segundos!='00' ){ echo $escena['0']->duracion_real_segundos; }else{ echo $escena['0']->duracion_real_segundos;
													  	} ?>" name="segunos_real" id="segundos_real" class="segundos_real clear_on_focus">
												
												<!--input type="text" value="00" id="segundos_real2" class="segundos_real" style="display:none"-->	  	

										    </div>
										    <div class="input_div" id="input_normal" style="display:none">
										    	<input type="text" value="00" id="minutos_real2" class="minutos_real" readonly>
											    <span style="width: 2px;padding: 0px;display: inline;">:</span>
												<input type="text" value="00" id="segundos_real2" class="segundos_real" readonly>	  	
										    	
										    </div>

										    <?php 
											    if($produccion['0']->estado!=2 and $plan_actual[0]->estado_plan!="Cerrado" AND $permisos_usuario!="read" AND ($escena[0]->estado_escena=='1' OR $escena[0]->estado_escena=='2' OR $escena[0]->estado_escena=='12' OR $escena[0]->estado_escena=='14')){ 
		                                            $this->db->where('id_escena',$escena[0]->id_escena);
		                                            $this->db->where('fecha_produccion',$escena[0]->fecha_inicio);
		                                            $this->db->where('unidad_produccion',$escena[0]->id_unidad);
		                                            $query = $this->db->get('retomas_escena');
		                                            $temp_time = $query->result();
		                                            if($temp_time){
		                                                echo anchor($idioma.'continuidad/desproducir_escena/'.$escena[0]->id_escena.'/'.date("Y-m-d",strtotime($plan_actual[0]->fecha_inicio)).'/'.$unidad[0]['id'].'/'.$produccion['0']->id_produccion.'/'.$temp_time[0]->id,'<div id="btn_desprod" class="btn_desprod">DESPRODUCIR</div>','onclick="return confirm('.lang('global.esta_seguro_que_desea_desproducir').')"');
		                                            }else{
		                                                echo anchor($idioma.'continuidad/desproducir_escena/'.$escena[0]->id_escena.'/'.date("Y-m-d",strtotime($plan_actual[0]->fecha_inicio)).'/'.$unidad[0]['id'].'/'.$produccion['0']->id_produccion,'<div id="btn_desprod" class="btn_desprod">DESPRODUCIR</div>','onclick="return confirm('.lang('global.esta_seguro_que_desea_desproducir').')"');
		                                            }
	                                        	}
                                            ?>

										    <div id="retornar_cronometro" class="hidden_span" style="padding: 0px !important;"></div>
											<input type="hidden" name="id_plan_diario" class="id_plan_diario" value="<?php echo $id_plan ?>">	  	
										    <input type="hidden" name="id_unidad" class="id_unidad" value="<?php echo $id_unidad ?>">	  	 		
										</div>
										<div id="btn_ins">	  	
										 <!-- a  class="btn_tomar_ahora_tiempo"  id="save_tiempo_real">INICIAR CRONOMETRO</a-->
										 <?php if($plan_actual[0]->estado_plan!="Cerrado" and $produccion['0']->estado!=2){ ?>
										 <a  class="btn_tomar_ahora_tiempo"  id="iniciar_cronometro"><?php echo lang('global.iniciar_') ?></a>
										 <a  class="btn_tomar_ahora_tiempo"  id="save_tiempo_real"><?php echo lang('global.guardar') ?></a>
										 <?php } ?>
									    </div>	  	
									<!--<input type="text" class="hora" id="t_real" value="<?php echo $escena['0']->duracion_real_minutos.':'.$escena['0']->duracion_real_segundos?>">-->
									<!--<div class="hora" id="t_real"><span id="minutos">
									<?php if(($escena['0']->duracion_real_minutos<10 or $escena['0']->duracion_real_minutos=='0') and $escena['0']->duracion_real_minutos!='00' and strlen($escena['0']->duracion_real_minutos)<1){ 
										  echo '0'.$escena['0']->duracion_real_minutos; 
										}else{ 
										  	echo $escena['0']->duracion_real_minutos;} ?></span>:<span id="segundos"><?php if($escena['0']->duracion_real_segundos<10 and $escena['0']->duracion_real_segundos!='00' ){ echo '0'.$escena['0']->duracion_real_segundos; }else{ echo $escena['0']->duracion_real_segundos;
										  	} ?></span></div>
									<div id="btn_ins">
										<a  class="btn_tomar_ahora_tiempo" onclick="carga()"  id="btn_change">INICIAR</a>
									</div>-->
									</div>
								</div>
							</td>
						</tr>
			</table>
		<?php } ?>

		</div>
	</div>	
<div class="contenido">
	<div class="toma_tiempo" style="width:100%;">
	<div id="accordion">
	<h3><?php echo lang('global.listado_personajes') ?></h3>
	<div>
		<table style="width:100%;">
			<tr>
				<td style="vertical-align: top;">
					<?php if($per){ ?>
						<?php $rol=$per['0']['rol']; ?>
						<h4><?php echo strtoupper($rol); ?></h4>
						   <div style="padding:6px;">
						     	<ul>
						    		<?php foreach ($per as $p) { ?>
								        <?php if($rol==$p['rol']){ ?>
								    	<li><?php echo $p['nombre']; ?></li>
							   	  	<?php }else{ ?>
			                    	</ul>
			                </div>
			            </td>
			            <td style="vertical-align: top;">
			            <?php $rol=$p['rol']; ?>
			            <h4><?php echo strtoupper($rol) ?></h4>
			            <div style="padding:6px;">
			               	<ul>
			                  	<li><?php echo $p['nombre']; ?></li>
				   	  			<?php } ?>
					            <?php } ?>
								</ul>
							</div>
				    <?php }else{ ?>
				    <label><?php echo lang('global.no_personajes') ?></label>
				    <?php } ?>
				</td>
			</tr>
		</table>
	</div>

	<h3><?php echo lang('global.listado_elementos') ?></h3>
	<div>
		<?php if($elementos){ ?>
		<?php $elementos_listado=''; ?>
		    <?php foreach ($elementos as $e) { ?>
		    	<?php $elementos_listado.=strtoupper($e['tipo']).' '.$e['nombre'].', '; ?>
		    <?php } ?>
		    <p><?php echo $elementos_listado;  ?></p>
		<?php }else {?>
		<label>No hay elementos</label>
		<?php } ?>
	</div>

<?php if($per){ ?>       
   <?php $rol=$per['0']['rol']; ?>
    
    <h3><?php echo lang('global.continuidad') ?></h3>

    <div>
    	<ul class="elementos">
			<?php foreach ($per as $p) { ?>
					<li>
						<a href="<?php echo base_url($idioma.'continuidad/elemento/'.$p['id_elemento'].'/'.$id_plan.'/'.$id_escena.'/'.$escena['0']->dia_cont.'/'.$unidad['0']['id'].'/'.$fecha_unidad) ?>">
							<?php if($p['imagen']){ ?>
							      <?php $imagen=$p['imagen'];  ?>
							<?php }else{ ?>
                                  <?php $imagen='images/continuidad/no_image.png';  ?>
							<?php  } ?>
							<div class="imagen" style="background:#D2EAFD url('<?php echo base_url($imagen); ?>') center center no-repeat; background-size: 100% auto;"></div>
							<p><?php echo $p['nombre']; ?></p>
							<?php if($plan_actual[0]->estado_plan !="Cerrado" and $produccion['0']->estado!=2){ ?>
								<?php $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite')); 
								  if($user){
								    if($user[0]['id_rol_otros']!=2 and $user[0]['id_rol_otros']!=6){ ?>
								    <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion.'/'.$escena['0']->id_capitulo.'/'.$id_escena.'/'.$escena['0']->dia_cont.'/'.$p['id_elemento']) ?>" class="btn_tomarse" style="color:#fff;"><?php echo lang('global.tomar_foto') ?></a>
								    <?php } ?>
								  <?php }else{ ?>
								    <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion.'/'.$escena['0']->id_capitulo.'/'.$id_escena.'/'.$escena['0']->dia_cont.'/'.$p['id_elemento']) ?>" class="btn_tomarse" style="color:#fff;"><?php echo lang('global.tomar_foto') ?></a>
								  <?php } ?>
							<?php } ?>
						</a>
					</li>
		<?php } ?>
		</ul>
    </div>
<?php } ?>

	<h3><?php echo lang('global.comentarios') ?></h3>
	<div class="comentarios_cont">
	  <div class="insert_coment">
			<?php if($comentarios){ ?>
			  <?php foreach ($comentarios as $c) { ?>
				<div class="comment">
					<span class="sub"><?php echo date("d-M-Y",strtotime($c['fecha'])) ?> <strong><?php echo $c['nombre'].' '.$c['apellido'] ?></strong></span>
					<p><?php echo $c['comentario'] ?></p>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php if($plan_actual[0]->estado_plan !="Cerrado"){ ?>
		<?php if($permisos_usuario=="write"){ ?>
			<a href="#" class="btn_comentar" style="width:300px; padding:9px; margin:7px auto;"><?php echo lang('global.agregar') ?> <?php echo lang('global.comentarios') ?></a>
			<div id="agregar_comentario" style="display:none">
				<textarea class="comentario"></textarea>
				<input type="hidden" value="<?php echo $id_escena ?>" class="id_escena">
				<button class="save_comment_escena btn_send_image" type="submit"><?php echo lang('global.guardar') ?><br /><?php echo lang('global.comentarios') ?></button>
			</div>
		<?php } ?>
	<?php } ?>
	</div>
</div>
</div>


</div>
<div id="asignarEscenasWrap" style="width: 200px;display: none;">
<?php $attributes = array( 'id' =>'commentForm', 'name'=>'commentForm','class'=>'custom');
    echo form_open('',$attributes);?>
    <h3><?php echo lang('global.escenas') ?></h3>
     <?php if($escenas_plan){ ?>
       <?php foreach ($escenas_plan as $e) {?>
       <input type="checkbox" name="multiescena" value="<?php echo $e['id'] ?>" class="escenas_plan" <?php if($e['comienzo_ens']){ ?>data-tiempo="1"<?php } ?>><?php echo $e['numero_escena'] ?><br>
       </label>
       <?php } ?>
       <div class="multiescenas"><?php echo lang('global.guardar') ?></div>
     <?php } ?>
 <?php echo form_close() ?>    
</div>
			
		
