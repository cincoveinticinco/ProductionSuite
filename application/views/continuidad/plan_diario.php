  <?php $idioma = $this->lang->lang().'/'; ?>
  <nav>
  	<a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$produccion[0]->id_produccion) ?>"><?php echo lang('global.volver') ?></a>
  	<!-- 
  	<a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
  	 -->
  	<!--<a href="escenas.html">ESCENAS</a>
  	<a href="elementos.html">ELEMENTOS</a>-->
  	<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
  	<?php if($produccion['0']->estado!=2){   ?>
      <?php $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite'));
      if($user){
        if($user[0]['id_rol_otros']!=2 and $user[0]['id_rol_otros']!=6){ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
        <?php } ?>
      <?php }else{ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
      <?php } ?>
   <?php } ?>   
  <!--<a href="index.html">CERRAR SESIÓN</a>-->
</nav>
<div class="clr"></div>
<div class="resumen">
	<div class="col">
		<?php if(isset($unidad[0]['id'])){
			$unidad_select=$unidad[0]['id']; 
		} ?>
		<h3><?php echo $produccion['0']->nombre_produccion  ?> / Unidad <?php echo $unidad['0']['numero'] ?> <?php echo date("Y-M-d",strtotime($escenas['0']['fecha_inicio'])); ?> / </h3>
	</div>
	<div class="small-12 columns w-contentBox">
			<?php 
			$total_escenas_pautadas=0;
			if($escenas){
			$total_escenas_pautadas=sizeof($escenas);
			}
			?>
			<?php $total_producidas=0; ?>
			<?php if($escenas){
				foreach ($escenas as $e) {
					if($e['estado_escenas']==1 OR $e['estado_escenas']==2){
                        if($e['estado_escenas']==1 OR $e['estado_escenas']==2 OR $e['estado_escenas']==12 OR $e['estado_escenas']==14){
                        $this->db->where('id_escena',$e['id_escena']);
                        $this->db->where('fecha_produccion',$e['fecha_inicio']);
                        $this->db->where('unidad_produccion',$e['id_unidad']);
                        $query = $this->db->get('retomas_escena');
                        $temp_time = $query->result();
                        if($temp_time){
                            $total_producidas=$total_producidas+1;
                        }
                    }
                }
				}
			} ?>

			<?php if(isset($fecha_unidad) AND $fecha_unidad!=""){ ?>
				<?php if($fecha_unidad){
			     	$date=date("Y-M-d",strtotime($fecha_unidad));
				}else{
					echo $escenas['0']['fecha_inicio'];
			     $date=date("Y-M-d",strtotime($escenas['0']['fecha_inicio']));
					} ?>
			<?php } else{ 
                 if(isset($escenas['0']['fecha_inicio']) AND $fecha_unidad!=""){
                    $date=date("Y-M-d",strtotime($escenas['0']['fecha_inicio']));     	
                 }else{
                 	$date='';
                 }
			}  ?>					
			<div class="small-6 medium-3 columns box">
				<label> <?php echo lang('global.escenas_pauta') ?></label>
				<span class="itemOrange">
					<?php echo $total_escenas_pautadas;?>
				</span>
			</div>
			<div class="small-6 medium-3 columns box">
				<label> <?php echo lang('global.escenas_pro') ?></label>
				<span class="itemOrange produced">
					<?php echo $total_producidas;?>
				</span>
			</div>
			<?php $duracion_minutos_es=0; $duracion_segundos_es=0; ?>
				<?php  if($escenas){
					foreach ($escenas as $e) {
						$duracion_minutos_es=$e['duracion_estimada_minutos']+$duracion_minutos_es;
						$duracion_segundos_es=$e['duracion_estimada_segundos']+$duracion_segundos_es;
					} 
				}
				$tiempo=continuidad::calculo_tiempo2($duracion_minutos_es,$duracion_segundos_es);
				$tiempo_escenas=$tiempo;
				?>
				<?php $duracion_minutos_pro=0; $duracion_segundos_pro=0; ?>
				<?php if($escenas){
				foreach ($escenas as $e) {
					if($e['estado_escenas']==1 OR $e['estado_escenas']==2 OR $e['estado_escenas']==12 OR $e['estado_escenas']==14){
						$this->db->where('id_escena',$e['id_escena']);
						$this->db->where('fecha_produccion',$e['fecha_inicio']);
						$this->db->where('unidad_produccion',$e['id_unidad']);
						$query = $this->db->get('retomas_escena');
						$temp_time = $query->result();
						if($temp_time){
							$temp_time = explode(':', $temp_time[0]->tiempo); 
							$duracion_minutos_pro=$temp_time[0]+$duracion_minutos_pro;
							$duracion_segundos_pro=$temp_time[1]+$duracion_segundos_pro;
						}
					}
				} 
				}
				 $tiempo_prod=continuidad::calculo_tiempo2($duracion_minutos_pro,$duracion_segundos_pro);
				?>
			<div class="small-6 medium-3 columns box">
				<label> <?php echo lang('global.tiempo_estimado') ?></label>
				<span class="itemOrange">
					<?php echo $tiempo;?>
				</span>
			</div>
			<div class="small-6 medium-3 columns box">
				<label> <?php echo lang('global.total_tiempo_pro') ?></label>
				<span class="itemOrange produced">
					<?php echo $tiempo_prod;?>
				</span>
			</div>
		</div>
	<div class="clr"></div>
	<div id="accordion">
		<h3><?php echo lang('global.detalles') ?></h3>
		<div class="infoPlanBox">
			<div class="contr">

				<strong><?php echo lang('global.ultima_actualizacion') ?>: <?php if($ultima_edicion){ ?></strong> <?=strtoupper($ultima_edicion[0]->nombre)." ".strtoupper($ultima_edicion[0]->apellido)?>
	    			el <?=date("d/M/Y H:i:s",strtotime($ultima_edicion[0]->fecha))?>  
	    			<?php } ?>
							<?php 
								switch ($escenas[0]['estado_plan']) {
			    				case 'No Iniciado':
			    					$color_state="#d0d0d0";
			    					break;
			    				case 'Abierto Privado':
			    					$color_state="#c1f378";
			    					break;
			    				case 'Abierto':
			    					$color_state="#8cdd16";
			    					break;
			    				case 'Abierto offline':
			    					$color_state="#4a4b39";
			    					break;
			    				case 'Cerrado':
			    					$color_state="#fee93e";
			    					break;
			    				case 'Re abierto':
			    					$color_state="#f7921e";
			    					break;
			    				default:
			    					$color_state="#d0d0d0";
			    					break;
			    			}
			    		?>
			    </div>
			    <div class="estadoBox estadoBoxPlanDiario" style="background:<?=$color_state?>;color: #333; padding:4px; margin:4px 0px">
	    			<?php echo lang('global.estado') ?>: <span class="estado_plan"><?php if($escenas[0]['estado_plan']==""){ echo "No iniciado"; }else{ echo $escenas[0]['estado_plan']; } ?></span>
	    		</div>
			<div class="small-12 columns w-contentBox">
	    		<div class=" small-6 medium-4 columns box">
	    			<label><?php echo lang('global.director') ?>:</label> 
	    			<?php if(isset($director_dia) and $director_dia ){ ?>
					<span>
						<?php echo strtoupper($director_dia['0']->nombre.' '.$director_dia['0']->apellido); ?>
					</span>	
					<?php $director=$director_dia['0']->id?>
					<?php } else { ?>
					<span>
						<?php echo strtoupper($unidad['0']['dir']); ?>	
					</span>
					<?php $director=$unidad['0']['id_director']?>
					<?php } ?>
	    		</div>
	    		<div class=" small-6 medium-4 columns box">
	    			<label>Script:</label>  
	    			 <?php if(isset($script_dia) and $script_dia) { ?>
				    <span>
				    	<?php echo strtoupper($script_dia['0']->nombre.' '.$script_dia['0']->apellido); ?>	
				    </span>
				    <?php $script_unidad=$script_dia['0']->id?>
				    <?php } else{ ?>
				    <span>
				    	<?php if($unidad['0']['scr']!=""){
							echo strtoupper($unidad['0']['scr']);?>
				    	<?php }else{
				    		echo ' - ';
				    	} ?> 
				    </span>
				     <?php $script_unidad=$unidad['0']['id_script']?>
				    <?php } ?>	
	    		</div>
	    		<div class=" small-6 medium-4 columns box">
	    			<label><?php echo lang('global.llamado') ?>:</label>
					<span>
					<?php if ($escenas['0']['llamado']){ ?>
						<?php echo date("h:i A",strtotime($escenas['0']['llamado'])); ?>
					<?php }else{ ?>
						-
					<?php } ?>
					</span>
	    		</div>
	    		<div class=" small-6 medium-4 columns box">
	    			<label><?php echo lang('global.corte_general') ?>:</label>
					<span>
					<?php if ($escenas['0']['wrap_time'] AND $escenas['0']['wrap_time'] !='00:00:00'){ ?>
						<?php echo date("h:i A",strtotime($escenas['0']['wrap_time'])) ?>
					<?php }else{ ?>
						-
					<?php } ?>
					</span>
	    		</div>
	    		<div class=" small-12 medium-8 columns box">
	    			<label><?php echo lang('global.lugar') ?>:</label>
					<span>
					<?php if ($escenas['0']['lugar']){ ?>
						<?php echo $escenas['0']['lugar'] ?>
					<?php }else{ ?>
						-
					<?php } ?>
					</span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php 
						$total_escenas_pautadas=0;
						if($escenas){
							$total_escenas_pautadas=sizeof($escenas);
						}
						?>
						<label style="width:151px;"><?php echo lang('global.escenas_pauta') ?>: </label>
						<span class="itemOrange">
						<?php echo $total_escenas_pautadas;?></span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php $total_prodcell_align_leftucidas=0; ?>
						<?php $total_producidas=0; ?>
						<?php if($escenas){
							foreach ($escenas as $e) {
								if($e['estado_escenas']==1 OR $e['estado_escenas']==2){
									$total_producidas=$total_producidas+1;
								}
							}
						} ?>
						<label style="width:151px;"><?php echo lang('global.escenas_pro') ?>: </label>
						<span class="itemOrange produced">
						<?php echo $total_producidas ?></span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php $total_pro_producir=$total_escenas_pautadas-$total_producidas ?>
						<label style="width:151px;"><?php echo lang('global.escenas_por_pro') ?>:</label>
						<span class="itemOrange pending">
						<?php echo $total_pro_producir ?></span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php $duracion_minutos_es=0; $duracion_segundos_es=0; ?>
					<?php  if($escenas){
						foreach ($escenas as $e) {
							$duracion_minutos_es=$e['duracion_estimada_minutos']+$duracion_minutos_es;
							$duracion_segundos_es=$e['duracion_estimada_segundos']+$duracion_segundos_es;
						} 
					}
					$tiempo=Continuidad::calculo_tiempo2($duracion_minutos_es,$duracion_segundos_es);
					?>
					<label><?php echo lang('global.total_tiempo_estima') ?>: </label>
					<span class="itemOrange">
					<?php echo $tiempo ?></span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php $duracion_minutos_pro=0; $duracion_segundos_pro=0; ?>
						<?php if($escenas){
						foreach ($escenas as $e) {
							if($e['estado_escenas']==1 OR $e['estado_escenas']==2 OR $e['estado_escenas']==12 OR $e['estado_escenas']==14){
								$this->db->where('id_escena',$e['id_escena']);
								$this->db->where('fecha_produccion',$e['fecha_inicio']);
								$this->db->where('unidad_produccion',$e['id_unidad']);
								$query = $this->db->get('retomas_escena');
								$temp_time = $query->result();
								if($temp_time){
									$temp_time = explode(':', $temp_time[0]->tiempo); 
									$duracion_minutos_pro=$temp_time[0]+$duracion_minutos_pro;
									$duracion_segundos_pro=$temp_time[1]+$duracion_segundos_pro;
								}
							}
						} 
						}
						 $tiempo_prod=Continuidad::calculo_tiempo2($duracion_minutos_pro,$duracion_segundos_pro);
						?>
						<label><?php echo lang('global.total_tiempo_pro') ?>:</label>
						<span class="itemOrange produced">
						<?php echo $tiempo_prod ?></span>
	    		</div>
	    		<div class="small-6 medium-4 columns box">
	    			<?php $duracion_minutos_por_pro=0; $duracion_segundos_por_pro=0; $tiempo_por_prod="00:00";?>
						<?php $negativo=0; ?>
						<?php if($escenas){
							foreach ($escenas as $e) {
								if($e['estado_escenas']!=1 OR $e['estado_escenas']!=2){
									$duracion_minutos_por_pro=$e['duracion_estimada_minutos']+$duracion_minutos_por_pro;
									$duracion_segundos_por_pro=$e['duracion_estimada_segundos']+$duracion_segundos_por_pro;
								}
							} 

							$m=$duracion_minutos_pro-$duracion_minutos_es;
							$s=$duracion_segundos_pro-$duracion_segundos_es;
							
							if($m<0){
								$m=$m*-1;
								$negativo=1;
							}
							if($s<0){
								$s=$s*-1;
							}
							$tiempo_por_prod= Continuidad::calculo_tiempo2($m,$s);
						}
						?>
						<label>Minutos por producir:</label>
						<span class="itemOrange pending">
						<?php if($duracion_minutos_pro>$duracion_minutos_es or $duracion_segundos_pro>$duracion_segundos_es){?>
						  00:00
						<?php }else { ?>
						<?php echo $tiempo_por_prod?></span>
						<?php } ?>
	    		</div>	
			</div>							
		</div>
		<h3><?php echo lang('global.comentarios') ?></h3>
		<div class="infoPlanBox">
			<!-- LISTADO DE COMENTARIOS -->
				<?php if(isset($comentarios) and $comentarios){ ?>
					<div class="row">	
						<table class="tabla_info">
							<thead>
								<tr>
									<td width="58%"><?php echo lang('global.comentarios') ?></td>
									<td width="18%"><?php echo lang('global.usuarios') ?></td>
									<td width="12%"><?php echo lang('global.fecha') ?></td>
									<td width="12%"><?php echo lang('global.acciones') ?></td>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($comentarios as $c) { ?>
					     	<tr id="comentario_<?php echo $c['id_comentario'] ?>">
					     		<td style="font-size:14px; line-height:15px;"><?php echo $c['comentario'].'' ?></td>
					     		<td style="font-size:16px; line-height:13px;"><?php echo $c['nombre'].' '.$c['apellido'] ?></td>
					     		<td style="text-transform:uppercase;"><?php echo date("Y-M-d",strtotime($c['fecha'])); ?></td>
					     		<td align="center"><a class="eliminar_comentario" id="<?php echo $c['id_comentario'] ?>"><?php echo lang('global.eliminar') ?></a href="#"></td>
					     	</tr>
					     <?php } ?>	
					 		</tbody>
						</table>
					</div>
			    <?php } ?>
			<!-- FIN LISTADO DE COMENTARIOS -->
			<?php if($permisos_usuario=="write"){?>
				<div class="column twelve" id="comentarios_inferior">
					<textarea name="comentario_user" class="comentario_user"></textarea>
					<div class="clr"></div>
					<button type="submit" id="guardar_comentario_user" class="button"><?php echo lang('global.guardar') ?></button>
					<a href="#" class="button" id="cancel_editar_plan"><?php echo lang('global.cancelar') ?></a>
					<div class="clr"></div>
				</div>
			<?php } ?> 
		</div>
	</div>
			
	<nav>
			
		<?php if($escenas[0]['estado_plan']!="Cerrado" AND $permisos_usuario!="read" AND isset($escenas[0]['estado_plan'])){?>
            <?php if($escenas[0]['estado']>2 || $id_user==$produccion[0]->id_productor || $tipo_user==1 || $tipo_user==4 and $produccion['0']->estado!=2){ ?>
                    <a class="fancybox"  href="#horaLlamado">HORA DE LLAMADO</a>
            <?php } ?>
        <?php } ?>
		<?php if($permisos_usuario=="write" and $produccion['0']->estado!=2){?>
			<?php if(!$escenas['0']['wrap_time'] or $escenas['0']['wrap_time']=='00:00:00'){ ?>
			<a class="fancybox" href="#corteGeneral"><?php echo lang('global.corte_general') ?></a>
			 <?php }else{ ?>  
			<a  href="#" class="<?php if($escenas AND $escenas[0]['estado_plan']!='Cerrado'){ echo 'cerrar_plan'; }else{ 	echo 'reabrir_plan'; }?> "<?php if($escenas[0]['estado_plan']=='Cerrado' || $escenas[0]['estado_plan']=="" || $escenas[0]['estado_plan']=="No Iniciado"){ echo ' style="display:none;"';
			            	}

			            	?>
			                ><span></span><?php switch ($escenas[0]['estado_plan']) {
			                	case 'Abierto Privado':
			                			echo "Publicar plan";
			                		break;
			                	case 'Abierto':
			                	   	echo "Cerrar plan";
			                		break;
			                	case 'Cerrado':
			                			echo "Reabrir plan";
			                		break;
			                	case 'Re abierto':
			                	    	echo "Cerrar plan";
			                		break;

			                }?></a>
			<?php } ?>
		
			<?php if($escenas[0]['estado_plan']!="Cerrado"){ ?>
			<a class="fancybox " href="#asignarEscenasWrap" id="asignarEscenasLink"><span></span><?php echo lang('global.asignar_escenas') ?></a>
			<?php } ?>
		<?php } ?>

		<?php if($escenas){ ?>
		<a href="<?=base_url($idioma.'continuidad/cruce_elementos/'.$produccion[0]->id_produccion .'/'.$unidad[0]['id'].'/'.date("Y-m-d",strtotime($date)))?>">
			<?php echo lang('global.cruce_elementos') ?>
		</a>
		<?php } ?>            
		</nav>


	<div class="clr"></div>

	<? if(isset($unidad) AND $unidad!="") {?>
    
    <?php if($escenas){ ?>
    <input type="hidden" value="<?=$produccion[0]->id_produccion?>" id="idproduccion">
    <?php $ver_plan=1; ?>
    <div class="contenido_sup" style="padding:0 0 8px 0;">
	    <div class="col scrolling" style="padding:0px;">    
    		<table class="plan_diario" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
				    <td style="padding:0 8px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.orden') ?>">#</span></td>
				    <td style="padding:0 8px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_libretos') ?>"><?php echo lang('global.lib') ?></span></td>
				    <td style="padding:0 8px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escenas') ?>"><?php echo lang('global.esc') ?></span></td>
				    <td style="padding:0 8px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_continuidad') ?>">CONT</span></td>
				    <td style="padding:0 2px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.pag_ab') ?>"><?php echo lang('global.pag') ?></span></td>
		               	<?php if($campos_usuario){ 
		                	for ($i=0; $i < count($campos_usuario)-1; $i++) { 
		                		switch ($campos_usuario[$i]) {
		                			case 'día/noche':
		                				echo '<td width="100"><span class="has-tip tip-centered-top" title="'.lang('global.dia_noche').'" style="width:90px; display: block;">'.lang('global.dia_noche').'</span></td>';
		                			break;
		                			case 'int/ext':
		                				echo '<td width="100"><span class="has-tip tip-centered-top" title="'.lang('global.int_ext_ab').'" style="width:80px; display: block;">'.lang('global.int_ext_ab').'</span></td>';
		                			break;
		                			case 'loc/est':
		                				echo '<td width="100"><span class="has-tip tip-centered-top" title="'.lang('global.loc_est_ab').'" style="width:80px; display: block;">'.lang('global.loc_est_ab').'</span></td>';
		                			break;
		                			case 'locación':
		                				echo '<td width="10%"><span class="has-tip tip-centered-top"  style="width:300px; display: block;" title="'.lang('global.locacionUpper').'">'.lang('global.locacionUpper').'</span></td>';
		                			break;
		                			case 'set':
		                				echo '<td width="10%"><span class="has-tip tip-centered-top"  style="width:300px; display: block;" title="Set">SET</span></td>';
		                			break;
		                			case 'toma':
		                				echo '<td width="40%"><span class="has-tip tip-centered-top"  style="width:50px;  display: block;" title="'.lang('global.toma').'">'.lang('global.toma').'</span></td>';
		                			break;
		                		}
		                	}
		                }else{?>
		                  <td width="100">
                          <span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_noche') ?>" style="width:80px; display: block;"><?php echo lang('global.dia_noche') ?></span>
                          </td>
		                  <td width="100">
                          <span class="has-tip tip-centered-top" title="<?php echo lang('global.int_ext_ab') ?>" style="width:80px; display: block;"><?php echo lang('global.int_ext_ab') ?></span>
                          </td>
		                  <td width="100">
                          <span class="has-tip tip-centered-top" title="<?php echo lang('global.loc_est_ab') ?>" style="width:80px; display: block;"><?php echo lang('global.loc_est_ab') ?></span>
                          </td>
		                  <td width="10%">
                          <span class="has-tip tip-centered-top"  style="width:300px; display: block;" title="<?php echo lang('global.locacionUpper') ?>"><?php echo lang('global.locacionUpper') ?></span>
                          </td>
		                  <td width="10%">
                          <span class="has-tip tip-centered-top"  style="width:300px; display: block;" title="Set">SET</span>
                          </td>
		                  <td width="40%"><span class="has-tip tip-centered-top"  style="width:50px;  display: block;" title="<?php echo lang('global.toma') ?>"><?php echo lang('global.toma') ?></span>
                          </td>
		                <?php } ?>
		                  <td width="20%">
                          <span class="has-tip tip-centered-top" style="width:100px; display: block;" title="<?php echo lang('global.tiempo_estimado') ?>"><?php echo lang('global.tiempo_estimado') ?></span>
                          </td>
		                  <td width="20%">
                          <span class="has-tip tip-centered-top" style="width:100px; display: block;" title="<?php echo lang('global.tiempo_real') ?>"><?php echo lang('global.tiempo_real') ?></span>
                          </td>
		                  <td width="20%">
                          <span class="has-tip tip-centered-top" style="width:130px; display: block;" title="<?php echo lang('global.inicio_ensayo') ?>"><?php echo lang('global.inicio_ensayo') ?></span>
                          </td>
		                  <td width="20%">
                          <span class="has-tip tip-centered-top" style="width:100px; display: block;" title="<?php echo lang('global.inicio_grabacion_') ?>"><?php echo lang('global.inicio_grabacion_') ?></span>
                          </td>
		                  <td width="20%"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang('global.fin_grabacion_') ?>"><?php echo lang('global.fin_grabacion_') ?></span>
                          </td>

		          <!--INICIO FILAS-->
		        </tr> 
		    </thead>    
             <tbody>
		        <?php  foreach ($escenas as $e) { 
		        	$m=0;?>
		        <?php 
		       		if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad'] AND $e['estado_escenas']==1){
						$class_tr="td_yellow";
					}else{
						if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad']){
							$class_tr="td_yellow";
						}else{
							$tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
							if($tiempo){
							 $class_tr="td_yellow";
							}else{
								$class_tr="";
							}
						}
					} 
				?>
			  
		        <tr class="<?=$class_tr?>" onclick="document.location = '<?php echo base_url().$idioma?>continuidad/plan_diario_escena/<?php echo $e['id_plan'].'/'.$e['id_escena'].'/'.$id_unidad.'/'.$fecha_unidad ?>'">
					<td>
		              <?php echo $e['orden'];?>
					</td>
					<td><?php echo $e['numero'] ?></td>
					<td><?php echo $e['numero_escena'] ?></a></td>
					<td><?php echo $e['dias_continuidad'] ?></td>
					<td><?php echo $e['libreto'] ?></td>

		         <?php while ( $m < count($campos_usuario)-1) {?>


		                    <?php if(isset($e['des_dia']) AND $campos_usuario[$m]=="día/noche"){ ++$m;?>
							<td width="100"><span style="width:70px; overflow:hidden; display:block; margin:0 auto;text-transform: uppercase; "><?php echo $e['des_dia'] ?></span></td>
							<?php } ?>
							<?php 
							if(isset($e['retomas']) AND $campos_usuario[$m]=="toma"){ ++$m;?>
							<td>
                                <?php 
                                if($e['retomas']==0 AND ($e['estado_escenas']==1 OR $e['estado_escenas']==13)){
                                    echo 1;
                                }else{
                                    if($e['retomas']!=0){
                                        echo $e['retomas'];
                                    }else{
                                        echo 0;
                                    }
                                } ?>
		                    </td>
		                    <?php  } ?>
		                    
							<?php if(isset($e['des_int']) AND $campos_usuario[$m]=="int/ext"){ ++$m;?>
							<td width="100">
                            <span style="width:70px; overflow:hidden; display:block; margin:0 auto;text-transform: uppercase;"><?php echo $e['des_int'] ?></span>
                            </td>
							<?php } ?>
							<?php if(isset($e['tipo']) AND $campos_usuario[$m]=="loc/est"){ ++$m;?>
							<td width="100">
                            <span style="width:80px; overflow:hidden; display:block; margin:0 auto; text-transform: uppercase;"><?php echo $e['tipo'] ?></span>
                            </td>
							<?php } ?>
					    	<?php if(isset($e['nom_locacion']) AND $campos_usuario[$m]=="locación"){ ++$m;?>
							<td>
                            <span style="width:300px; overflow:hidden; display:block; margin:0 auto;" class="cell_align_left"><?php echo $e['nom_locacion'] ?></span>
                            </td>
							<?php } ?>
							<?php if(isset($e['nom_set']) AND $campos_usuario[$m]=="set"){ ++$m;?>
							<td>
                            <span style="width:300px; overflow:hidden; display:block; margin:0 auto;" class="cell_align_left"><?php echo $e['nom_set'] ?></span>
                            </td>
							<?php } ?>
		        <?php  } ?>
		         <?php  ?>
							<td>
								<span style="width:90px; overflow:hidden; display: block; margin:0 auto;">
								<?php 
									if(strlen($e['duracion_estimada_minutos'])<2){echo '0';} echo $e['duracion_estimada_minutos'].':'; if(strlen($e['duracion_estimada_segundos'])<2){echo '0';} echo $e['duracion_estimada_segundos']; 
								?>
								</span>
							</td>
							<td><a class="box" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
								<?php $minutos = '00';
									$segundos = '00';
									if($e['duracion_real_minutos'] AND ($e['duracion_real_minutos']!='00' OR $e['duracion_real_segundos']!='00') ){ 
									$tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
									if($tiempo){
										echo $tiempo[0]->tiempo;
										$temporal = explode(':', $tiempo[0]->tiempo);
										$minutos = $temporal[0];
										$segundos = $temporal[1]; 
									}else{
										echo "-";
									}
								}else{ ?>
									<span> - </span>
									<?php } ?>
								</a>
			                 </td>    
			                 <td> <a class="box"style="width:90px; overflow:hidden; display:block; margin:0 auto;">
			                   <?php if($e['comienzo_ens']){ echo date("h:i A",strtotime($e['comienzo_ens']));
			                   }else{?>
			                 		<span>-</span>
			                 	<?php } ?>
			                 	</a>
			                 </td> 
			                   <td> <a class="box" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
			                   <?php if($e['comienzo_prod']){ echo date("h:i A",strtotime($e['comienzo_prod']));
			                   }else{?>
			                   	<span> -</span>
			                   	<?php } ?>
			                   </a>
			                 </td>   
			                   <td> <a class="box" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
			                    <?php if($e['fin_produccion']){ echo date("h:i A",strtotime($e['fin_produccion']));
			                    }else{?>
			                   	<span>-</span><?php } ?>
								</a>
			                 </td>          
				</tr>
				<?php } ?>

				<?php } ?>
		        </tbody>
		      </table>
		</div>
	</div>	      

    <?php  } else{ ?>

	<?php } ?>	
</div>	


<div id="asignarEscenasWrap" style="display: none;">
	<div class="asignarEscenasbox">
		<div class="table_general">
			<h3><?php echo lang('global.asignar_escenas') ?></h3>
			<!--AGREGAR ESCENAS AL PLAN-->
	       	<?php if($escenas[0]['estado_plan']!='Cerrado'){ ?>
		        <div class="columns twelve">       	
		        	<?php echo form_open($idioma.'continuidad/asignar_plan_diario',' class="", id="Asing_plan", name="Asing"');?>
		        	<input type="hidden" id="idescenas" name="idescenas" value="">
		        	<input type="hidden" id="idplanes" name="idplanes" value="">
		        	<input type="hidden" name="unidad" value="<?=$unidad_select?>">
		        	<input type="hidden" name="fecha_inicio" id="start_plan_2" value="<?=date("Y-m-d",strtotime($date)) ?>">
		        	<input type="hidden" id="start_plan" value='<?= date("Y-m-d",strtotime($date)) ?>'>
		        	<?php if(isset($escenas[0]['id_plan_diario'])){ 
		        		$idplan=$escenas[0]['id_plan_diario'];
		        	}else{
		        		$idplan=0;
		        	} ?>
		        	<input type="hidden" id="idplan" value="<?=$idplan?>">
		        	<input type="hidden" id="idproduccion" name="idproduccion" value="<?=$produccion['0']->id_produccion?>">
		        	<input type="hidden" id="unidad_selector" value="<?=$unidad_select?>">
		        	<div class="small-12 columns">
		        		<div class="small-6 medium-6 columns">
		        			<label><?php echo lang('global.libreto') ?></label>
					        	<select style="width:96%;" id="capitulos_continuidad">
					        		<?php foreach ($capitulos as $capitulo) {?>
					        			<option value="<?=$capitulo['id_capitulo']?>"><?=$capitulo['numero']?></option>
					        		<?php } ?>
					        	</select>
		        		</div>
		        		<div class="small-6 medium-6 columns">
		        			<label><?php echo lang('global.escenas') ?></label>
				        	<select style="width:96%;" name="idescena" id="escenas">
				        	</select>
		        		</div>
		        	</div>
		        	<div class="small-12 columns">
		        		<button type="button" id="insert_asing_button" class="button expanded" ><?php echo lang('global.agregar') ?></button>
		        	</div>
	        			
	        	<?php echo form_close() ?>
		        </div>
	        <?php } ?>
	        <!--FIN AGREGAR ESCENAS AL PLAN-->
		</div>
	</div>		
</div>

<div id="corteGeneral" style="display: none;">
	<div class="asignarEscenasbox">
		<div class="table_general">
			<h3><?php echo lang('global.corte_general') ?>:</h3>
			<?php
		    	$time = false;
		    	if($escenas['0']['wrap_time']!="" AND $escenas['0']['wrap_time']!="00:00:00"){
		    		$delete = array(":", " ");
					$time = str_replace($delete, "", date("h:i A",strtotime($escenas['0']['wrap_time'])));
		            $time = str_split($time, 2);
		    	} 
		    ?>
		    <div class="small-4 columns">
		    	<label><?php echo lang('global.hora') ?>:</label>
		        <select class="horas" id="horas_wrap_time" name="horas_wrap_time">
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
		    </div>

		    <div class="small-4 columns">
		    	 <span><?php echo lang('global.minutos') ?>:</span>
		    	 <select class="minutos" id="minutos_wrap_time" name="minutos_wrap_time">
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
		    </div>
		    <div class="small-4 columns">
		    	<span>AM/PM</span>
		        <select class="am_pm" id="am_pm_wrap_time" name="am_pm_wrap_time">
		        	<option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
		        	<option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
		        	<option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
		        </select>
		    </div>
		    <div class="small-12 columns">
		    	<input type="hidden" class="id_plan" value="<?php echo $escenas['0']['id'] ?>">
				<button type="submit" class="corteGeneral button expanded"><?php echo lang('global.guardar') ?></button>
		    </div>

		</div>
	</div>
</div>

<div id="horaLlamado" style="display: none;">
	<div class="asignarEscenasbox">
		<div class="table_general">
			<h3><?php echo lang('global.llamado') ?>:</h3>
			 <?php echo form_open($idioma.'continuidad/guardar_hora_llamado',' id="hora_llamado" onsubmit="return validar_horaLlamado();"');?>
		    <input type="hidden" id="idplan" name="idplan" value="<?=$idplan?>">
			<input type="hidden" id="idproduccion" name="id_produccion" value="<?=$produccion['0']->id_produccion?>">
			<input type="hidden" name="fecha" value="<?=$fecha_unidad?>">
			<input type="hidden" name="id_unidad" value="<?=$id_unidad?>">
		    <?php
		        $time = false;
		        if($escenas['0']['llamado']!="" AND $escenas['0']['llamado']!="0"){
		            $delete = array(":", " ");
		            $time = str_replace($delete, "", date("h:i A",strtotime($escenas['0']['llamado'])));
		            $time = str_split($time, 2);
		        } 
		    ?> 
		    <div class="small-4 columns">
		    	<label>H</label>
	            <select class="horas required" id="horas_llamado" name="horas_llamado">
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
		    </div>

		    <div class="small-4 columns">
		    	 <label>M</label>
		           <select class="minutos required" id="minutos_llamado" name="minutos_llamado">
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
		    </div>
		     <div class="small-4 columns">
	            <span>AM/PM</span>
	            <select class="am_pm required" id="am_pm_llamado" name="am_pm_llamado">
	                <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
	                <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
	                <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
	            </select>
	        </div>
	        <div class="small-12 columns">
	        	 <button type="submit" class="guardar_horaLlamado button expanded"><?php echo lang('global.guardar') ?></button>
	        </div>
	        <?php echo form_close(); ?> 
		</div>
	</div>
</div>
<?php
	function tiempo_segundos($total){
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
?>
