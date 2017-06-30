<?php $idioma = $this->lang->lang().'/'; ?>
<!-- SECCION PARA ORDENAR COLUMNAS -->
<div id="ordenarWrap" style="display:none">
    <div class="ordenarBox">
        <span class="closeIcon" id="no_save_order"></span>
        <div class="table_general">
            <table class="main">
                <thead>
                    <tr>
                        <td><?php echo lang('global.ordenar_tabla') ?></td>
                    </tr>
                </thead>
            </table>
            <br>
            <?php echo form_open($idioma.'casting/orden_columnas','id="arderFormPLanDiario"') ?>
            <input type="hidden" name="tipo" value="5">
            <input type="hidden" name="campos_columnas" id="campos_select" value="">
                <div class="row" id="order_fields">
                    <div class="column six">
                        <h3><?php echo lang('global.campos_activos') ?></h3>
                        <ul id="itemsEnable" class="connectedSortable">
                        <?php if($campos_usuario){?>
                          <?php for ($i=0; $i < count($campos_usuario); $i++) { ?>
                          	<?php 
                          	$class_sort=""; 
                            if( $campos_usuario[$i]=="Personaje" OR 
                                $campos_usuario[$i]=="Rol" OR
                                $campos_usuario[$i]=="Estatus personaje") { 
                                $class_sort="horizontal_sort"; 
                            } 
                            ?>
                            <li class="<?=$class_sort?>" data-order="<?=$i+1?>"><?=$campos_usuario[$i]?></li>
                        <?php } } ?>
                        </ul>
                    </div>
                    <div class="column six">
                        <h3><?php echo lang('global.campos_disponibles') ?></h3>
                        <ul id="itemsDisable" class="connectedSortable">
                            <?php 
                          for ($i=0; $i < count($campos); $i++) { 
                            $validacion = true;
                            for ($j=0; $j < count($campos_usuario); $j++) { 
                                if($campos_usuario[$j]==$campos[$i]){
                                    $validacion=false;
                                }
                            }
                            if($validacion){ ?>
                            <li class="inactive_field"><?=$campos[$i]?></li>
                          <?php } } ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="column twelve">
                        <div class="align_center">
                            <input type="submit" id="save_fields" value="<?php echo lang('global.aplicar_cambios') ?>" class="button save_button">
                        </div>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
<!-- FIN SECCION PARA ORDENAR COLUMNAS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">

<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.personajes') ?>
</div>
<?php $this->load->view('includes/partials/top_nav_solicitudes'); ?>
<nav>
    <ul class="nav_post nav_casting" >
    	<li><a href="#" class="buttons icon icon_plus" id="filtro_personajes"><span></span><?php echo lang('global.filtrar') ?></a></li>
        <li><a href="<?php if ($idproduccion AND $idproduccion!='null'){
			echo base_url($idioma.'casting/pdf_personajes/'.$idproduccion);
		}else{
			echo '#';
		}?>" target="_blank" class="buttons icon icon_print"><span></span><?php echo lang('global.imprimir') ?></a></li>
	    <li><a href="<?php if ($idproduccion AND $idproduccion!='null'){
			echo base_url($idioma.'casting/excel_personajes/'.$idproduccion);
		}else{
			echo '#';
		}?>" target="_blank" class="buttons icon icon_save" id="crear_libreto"><span></span><?php echo lang('global.guardar') ?></a></li>
	    <li><a href="#" class="buttons icon icon_plus" id="ordenarCasting"><span></span><?php echo lang('global.ordenar') ?></a></li>
    </ul>
</nav>

<div id="inner_content" class="row">
	<div class="columns twelve">
		
	
	<?php if ($producciones): ?>
	<!-- SECCION FILTRO -->
	<div class="column twelve no-padding" id="filtro_presonajes_section" style="display:block">
		<div class="info" style="margin:0px 0px 10px 0px">
		<div class="row">
			<div class="columns twelve">
				<div class="columns twelve ">
					<label for=""><?php echo lang('global.buscar') ?>:</label>	
					<input type="text" id="buscar_personaje" class="search_input">
				</div>
				<div class="clr6"></div>
				<div class="columns two alignLeft">
					 <label><?php echo lang('global.estado_produccion') ?>: </label>
		              <select name="produccion" id="no_activas">
		                <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
		                <option value="1"><?php echo lang('global.activas') ?></option>
		                <option value="2">No <?php echo lang('global.activas') ?></option>
		              </select>
				</div>
				<div class="columns two alignLeft">
					<label><?php echo lang('global.produccion') ?>: </label>
						<select name="produccion" id="the_produccion2" >
							<option value=""><?php echo lang('global.seleccion_opcion') ?></option>
							<?php foreach ($producciones as $the_produccion): ?>
								<option value="<?=$the_produccion['id_produccion']?>"
									<?php if ($the_produccion['estado']==2 AND $idproduccion != $the_produccion['id_produccion']): ?>
										style="display:none;" class="inactive"
									<?php endif ?>
									<?php if ($idproduccion == $the_produccion['id_produccion']): ?>
										selected
									<?php endif ?>
								><?=$the_produccion['nombre_produccion']?></option>
							<?php endforeach ?>
						</select>
						<!--<label class="no_activas">inactivas: <input type="checkbox" id="no_activas"></label>-->
				</div>
				<div class="column two alignLeft">
					<label for=""><?php echo lang('global.rol') ?></label>
					<select name="rol" id="rol">
						<option value=""><?php echo lang('global.seleccion_opcion') ?></option>
						<?php foreach ($roles as $rol): ?>
							<option value="<?=$rol['id']?>"
								<?php if ($rol['id']==$idrol): ?>
									selected 
								<?php endif ?>
							><?=$rol['rol']?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="column two alignLeft">
					<label for=""><?php echo lang('global.fecha_inicio') ?></label>
					<input type="text"  id="inicio_personaje" value="<?php if($date_from!='null'){ echo $date_from;}?>">
				</div>
				<div class="column two alignLeft">
					<label for=""><?php echo lang('global.fecha_final') ?></label>
					<input type="text" id="fin_personaje" value="<?php if($date_to!='null'){ echo $date_to;}?>">
				</div>
				
			</div>
		</div>
		<div class="clr6"></div>
		
		<!-- <div class="clr"></div> -->
		<div class="row">
			<div class="columns twelve">
				<div class="checksFilter">
					<table class="table-layout-estados">
						<caption class="encabezado-estados"><?php echo strtoupper(lang('global.estado')) ?>S</caption>
						<tr>
							<td><label class="label-fill"><span class="estado_color red" ></span><?php echo lang('global.no_asignado') ?><input checked data-clase="no_asignado" type="checkbox" class="estado_personaje"></label></td>
							<td><label class="label-fill"><span class="estado_color orange" ></span><?php echo lang('global.generando_solicitud') ?><input checked data-clase="generando_solicitud"  type="checkbox" class="estado_personaje"></label></td>
							<td><label class="label-fill"><span class="estado_color orange" ></span><?php echo lang('global.aprobada_produccion') ?><input checked data-clase="aprobacion_produccion" type="checkbox" class="estado_personaje"></label></td>
							<td><label class="label-fill"><span class="estado_color orange" ></span><?php echo lang('global.generando_contrato') ?><input checked data-clase="generando_contrato" type="checkbox" class="estado_personaje"></label></td>
							<td><label class="label-fill"><span class="estado_color orange" ></span><?php echo lang('global.proceso_de_firma') ?><input checked data-clase="proceso_firma" type="checkbox" class="estado_personaje"></label></td>
						</tr>
						<tr>
							<td><label class="label-fill"><span class="estado_color green" ></span><?php echo lang('global.asignado') ?><input checked data-clase="asignado" type="checkbox" class="estado_personaje"></label></td>
							<td><label class="label-fill"><span class="estado_color purple" ></span><?php echo lang('global.expirado') ?><input checked data-clase="expirado" type="checkbox" class="estado_personaje"></label></td>
							<td colspan="3"></td>
						</tr>
					</table>
				</div>	
			</div>
			<div class="columns twelve"><input type="button" value="<?php echo lang('global.filtrar') ?>" id="filtro_personajes_button" class="twelve button estado_personaje_fill save_button"></div>
		</div>
		<!-- <div class="clr"></div> -->
		</div>
	</div>
	<div class="clr"></div>
	<div class="alert-box success">
		<?php echo lang('global.total_personajes') ?> 
		<?php if ($personajes_produccion) {
			echo ' = '.count($personajes_produccion);
		}else{
			echo '-';
		} ?>
	</div>
	<!-- FIN SECCION FILTRO -->
	<?php endif ?>	
	<div class="normal_table">
	<?php if ($personajes_produccion): ?>
		<table class="tablesorter" id="tabla_personajes">
			<thead>
				<tr>
					<?php $m =0; while ( $m < count($campos_usuario)-1) { ?>
						<?php if($campos_usuario[$m]=="Personaje"){ ++$m;?>
							<th><?php echo lang('global.personajes') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Rol"){ ++$m;?>
							<th><?php echo lang('global.rol') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Estatus personaje"){ ++$m;?>
							<th><?php echo lang('global.estatus_personajes') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Solicitudes"){ ++$m;?>
							<th><?php echo lang('global.solicitudes') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Actor"){ ++$m;?>
							<th><?php echo lang('global.actor') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Primer día plan"){ ++$m;?>
							<th><?php echo lang('global.primer_dia_del_plan') ?></th>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Responsable"){ ++$m;?>
							<th><?php echo lang('global.responsable') ?></th>
						<?php } ?>
					<?php } ?>
					<th><?php echo lang('global.acciones') ?></th>
				</tr>
			</thead>
			<tbody id="tbody_personajes">
			<?php foreach ($personajes_produccion as $personaje_produccion): ?>
			
				<?php $data = Casting::responsablePersonaje($personaje_produccion->id_solicitud); ?>

				<tr class="row_personaje" data-idpersonaje="<?=$personaje_produccion->id_elemento?>">
					<?php 

					$fechas_personaje = $this->model_casting->fechas_personaje($personaje_produccion->id_elemento);
					if($fechas_personaje){
						if($fechas_personaje[0]->fecha_plan_diario AND $fechas_personaje[0]->fecha_contrato){
							if(strtotime($fechas_personaje[0]->fecha_plan_diario) > strtotime($fechas_personaje[0]->fecha_contrato)){
								$data['clase_estado'] = "td_purple expirado";
								$data['reponsable'] = "-" ;
								$data['estado'] = "Expirado";
							}
						}
					}

					?>
				 	<?php $m =0; while ( $m < count($campos_usuario)-1) { ?>
						<?php if($campos_usuario[$m]=="Personaje"){ ++$m;?>
							<td class="nombre_personaje"><?=$personaje_produccion->elemento_nombre?></td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Rol"){ ++$m;?>
							<td><?=$personaje_produccion->rol?></td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Estatus personaje"){ ++$m;?>
							<td class="<?=$data['clase_estado']?>"><?=$data['estado']?></td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Solicitudes"){ ++$m;?>
							<td class="solicitud_personaje"><?php $cadena_responsable="";
								$id ="";
								if(strlen($personaje_produccion->id_solicitud)<4 AND $personaje_produccion->id_solicitud) {	 
									for($j=strlen($personaje_produccion->id_solicitud);$j<5;$j++) 
										$id.="0";
								}

								echo '<a href="'.base_url($idioma.'casting/detalle_solicitud/'.$personaje_produccion->id_solicitud).'/1">'.$id.$personaje_produccion->id_solicitud.'</a> - '.$personaje_produccion->estado_solicitud;
								if ($personaje_produccion->id_solicitud) {
									$otro_si = $this->model_casting->buscar_otro_si($personaje_produccion->id_solicitud);
									if ($otro_si) {
										$k = 1;
										foreach ($otro_si as $otro) {
											echo '</br><a  href="'.base_url($idioma.'casting/detalle_solicitud/'.$otro->id).'/1">';
											echo Casting::completar_id($personaje_produccion->id_solicitud).' - ';
											if ($k<10) {
												echo '0'.$k.'</a>';
											}else{
												echo $k.'</a>';
											}
											echo ' '.$otro->descripcion.' / OTRO SI';
											$cadena_responsable.='</br>'.$otro->responsable;
											++$k;
										}
										
									}
								}
								?>
							</td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Actor"){ ++$m;?>
						<td class="nombre_actor"><?=$personaje_produccion->actor_nombre?></td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Primer día plan"){ ++$m;?>
							<td><?=$personaje_produccion->fecha_inicio?></td>
						<?php } ?>

						<?php if($campos_usuario[$m]=="Responsable"){ ++$m;?>
							<td><?=$data['responsable'].$cadena_responsable?></td>
						<?php } ?>
					<?php } ?> 

					<td>
						<?php 
							if ($personaje_produccion->id_estado) {
								if($personaje_produccion->id_estado==2 and $permisos=='write'){ ?>
									<a href="<?=base_url($idioma.'casting/editar_solicitud/'.$personaje_produccion->id_solicitud)?>"><?php echo lang('global.generar') ?> <?php echo lang('global.solicitud') ?></a>				 			
								<?php } ?>
							<?php }else{ ?>
								<?php if ($personaje_produccion->estado_produccion==1 and $permisos=='write'): ?>
									<a href="<?=base_url($idioma.'casting/crear_solicitud/null/'.$produccion[0]->id_produccion.'/'.$personaje_produccion->id_elemento)?>"><?php echo lang('global.asignar') ?> <?php echo lang('global.actor') ?></a>
								<?php endif ?>
							<?php } ?>
							
							<!-- ENLACE DE OTRO SI -->
							<?php if ($personaje_produccion->id_estado==11): ?>
								<?php if ($personaje_produccion->estado_produccion==1 and $permisos=='write'): ?>
									<a href="<?=base_url($idioma.'casting/crear_otro_si/'.$personaje_produccion->id_solicitud)?>"><?php echo lang('global.agregar') ?> otro si</a>
								<?php endif ?>
							<?php endif ?>
					</td>

				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	<?php endif ?>
		</div>

	</div>
</div>	
