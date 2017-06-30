  <?php $idioma = $this->lang->lang().'/'; ?>
  <nav>
  <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
  <!--<a href="escenas.html">ESCENAS</a>
  <a href="elementos.html">ELEMENTOS</a>-->
  <?php if(!$palabra){ ?>
  	<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
  <?php }else{ ?>
  	<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion.'/null/'.$palabra) ?>"><?php echo lang('global.elementos') ?></a>
  <?php } ?>
  <?php $produccion=$this->model_plan_produccion->produccion_id($id_produccion);if($produccion['0']->estado!=2){   ?>
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
<div class="divider"></div>
<div class="resumen" style="padding:0;">
	<div class="col">
		<!--<h3><?php echo $produccion['0']->nombre_produccion  ?> / Unidad <?php echo $unidad['0']['numero'] ?> <?php echo date("d-M-Y",strtotime($escena['0']->fecha_inicio)); ?> / LIB-<?php echo $escena['0']->numero_capitulo; ?>-ESC-<?php echo $escena['0']->numero_escena; ?>	 </h3>-->
	</div>
</div>
<div class="contenido">
<h2 class="titulo"><?php echo $elemento['0']->nombre?></h2>
<input class="nombre_elemento" value="<?php echo $elemento['0']->nombre ?>" type="hidden"> 
			<div class="toma_tiempo">
				<div class="galeria">
					<div class="detalles">
						<div class="cambios">
							<ul>
							<?php if($personajes){ ?>
							        <?php foreach ($personajes as $p) { ?>
							             <li><a class="<?php if($p['dias_continuidad']==$dia_cont){ echo 'active ';} ?>cont"   data-idproduccion="<?php echo $id_produccion ?>" data-idelemento="<?php echo $p['id_elemento'] ?>" data-cont="<?php echo $p['dias_continuidad'] ?>">CONT <?php echo $p['dias_continuidad'] ?></a></li>        
							        <?php } ?>
							<?php } ?>

							</ul>
						</div>
						<div class="imagenes">
							
							<div id="banner-slide" style="overflow-x:hidden; overflow-y:auto;">
						        <!-- start Basic Jquery Slider -->
						        <ul class="lista_fotos">
						        <?php $total=0 ?>
						        <?php if($personajes_cont){ ?>
						           <?php $total=sizeof($personajes_cont) ?>
						           <?php $cont=1 ?>
							        <?php foreach ($personajes_cont as $p) { ?>
							            <li class="tipo_imagen-<?php echo $p['id_tipo'] ?> ocultar" id="continuidad_<?php echo $p['id'] ?>">
								            	<div class="div_thumb">
									            	<a class="fancybox-thumbs" data-fancybox-group="thumb" href="<?php echo base_url($p['imagen']) ?>">
									            		<img src="<?php echo base_url($p['imagen']) ?>" title="Imagen <?php echo $p['tipo'].' '.$cont.'/'.$total ?>">
									            	</a>
									            	<?php if($tipo_rol==1 and $produccion['0']->estado!=2){ ?>
									            	<div data-idcontinuidad="<?php echo $p['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>" data-cont="<?php echo $cont ?>" data-personaje="<?php echo $personajes_cont['0']['nombre'] ?>" class="eliminar_cont btn_rojo"><?php echo lang('global.eliminar') ?></div>
									            	<?php }elseif($tipo_rol==2 and $p['id_tipo']==2 and $produccion['0']->estado!=2){ ?>
		                                                 <div data-idcontinuidad="<?php echo $p['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>" data-cont="<?php echo $cont ?>" data-personaje="<?php echo $personajes_cont['0']['nombre'] ?>" class="eliminar_cont btn_rojo"><?php echo lang('global.eliminar') ?></div>
									            	<?php }elseif($tipo_rol==3 and $p['id_tipo']==3 and $produccion['0']->estado!=2){ ?>
									            	  <div data-idcontinuidad="<?php echo $p['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>" data-cont="<?php echo $cont ?>" data-personaje="<?php echo $personajes_cont['0']['nombre'] ?>" class="eliminar_cont btn_rojo"><?php echo lang('global.eliminar') ?></div>
									            	<?php } ?>
								            	</div>
							            </li>      
							        <?php $cont++; } ?>
							       
								<?php }else{ ?>
								<?php if($personajes_cont){ ?>
									<li class="tipo_imagen-<?php echo $p['id_tipo'] ?> ocultar">
										<img src="<?php echo base_url($idioma.'images/continuidad/no_image.png') ?>">
									</li>      
								<?php }else{ ?>
								<li class="tipo_imagen ocultar">
									<img src="<?php echo base_url($idioma.'images/continuidad/no_image.png') ?>">
								</li>      
								<?php } ?>
								
								<?php } ?>
						        </ul>
						        <!-- end Basic jQuery Slider -->
						     </div>

			
						</div>
					</div>
					<div class="clr"></div>
				</div>
			</div>
			<div class="comentarios">
				<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion.'/'.$dia_cont2.'/'.$palabra) ?>"  class="btn_volver_plan fl" style="width:100% !important; margin: 0 0 6px 0 !important;">
					<?php echo lang('global.volver_a_elementos') ?>
				</a>

				<?php echo lang('global.filtrar_imagenes') ?>:
				<select name="" id="filtrar_img">
				   <option  value="0"><?php echo lang('global.todas_imagenes') ?></option>
				 <?php if($tipo_imagen){ ?>
				   <?php foreach ($tipo_imagen as $t) { ?>
				   	 <option  value="<?php echo $t['id'] ?>" <?php if($t['id']==$tipo){ echo 'selected'; } ?>><?php echo $t['tipo'] ?></option>
				   <?php } ?>
				<?php } ?>
				</select>
				<input type="hidden" class="dia_con" value="<?php echo $cont_ele; ?>">
				<input type="hidden" class="id_elemento_cont" value="<?php echo $personajes['0']['id_elemento'] ?>">
				<p><?php echo lang('global.escenas_dias_de_continuidad') ?>:</p>
				
				<table id="dias_continuidad" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td><?php echo lang('global.unidad') ?></td>
							<td><?php echo lang('global.libreto') ?></td>
							<td><?php echo lang('global.escenas') ?></td>
							<td>CON</td>
							<td><?php echo lang('global.pag') ?></td>
						</tr>
					</thead>
					<tbody class="cont_table_escena">
					  <?php if($escenas){ ?>
						 <?php foreach ($escenas as $e) { ?>
						 <?php switch($e['estado_escena']){
                            case 1:
                             $class="td_yellow";
                            break;
                            case 2:
                            $class="td_retoma";
                            break;
                            case 3:
                            $class="td_black";
                            break;
                            case 4:
                            $class="td_cian";
                            break;
                            case 5:
                            $class="td_cian_light";
                            break;
                            case 6:
                            $class="td_green";
                            break;
                            case 7:
                            $class="td_green_light";
                            break;
                            case 8:
                            $class="td_pink";
                            break;
                            case 9:
                            $class="td_pink_light";
                            break;
                            case 10:
                            $class="td_orange";
                            break;
                            case 11:
                            $class="td_orange_light";
                            break;
                            case 12:
                            $class="td_yellow";
                            break;
                            case 14:
                            $class="td_retoma";
                            break;
                            default:
                            $class="td_brown_light";
                            break;
                            }?>
						 	<tr>
								<td class="<?php echo $class ?>"><?php if($e['numero']){ echo $e['numero'];}else{ echo '-'; } ?>/<?php if($e['fecha_inicio']){ echo date("d-M-Y",strtotime($e['fecha_inicio']));}else{ echo '-';} ?></td>
								<td><?php echo $e['numero_lib'] ?></td>
								<td><?php echo $e['numero_escena'] ?></td>
								<td><?php echo $e['dias_continuidad'] ?></td>
								<td><?php echo $e['libreto'] ?></td>
							</tr>
						 <?php } ?>	
					  <?php } ?>		
					</tbody>
				</table>
			</div>
			<div class="clr"></div>
	<div id="accordion">
		<h3><?php echo lang('global.comentarios') ?></h3>
		<div class="comentarios_cont">
			<!-- adfasdfj gasjdh fasjdgfashfgashdfg askhdgf kdhsgfkashdgf akf -->
			<div class="comentarios_cont_imagen">
					<?php if($comentarios){ ?>
					        <?php foreach ($comentarios as $c) { ?>
								<div class="comment">
								<span class="sub">20-06-2013 <strong><?php echo $c['nombre'] ?> <?php echo $c['apellido'] ?></strong></span>
								<p><?php echo $c['comentario'] ?></p>
								</div>
					        <?php } ?>
					<?php } ?>
			</div>
			<div class="clr"></div>
			<a href="#" class="btn_comentar" style="width:300px; padding:9px; margin:7px auto;"><?php echo lang('global.agregar') ?> <?php echo lang('global.comentarios') ?></a>
			<div id="agregar_comentario" style="display:none">
				<textarea class="comentario"></textarea>
				<input type="hidden" class="id_elemento" value="<?php echo $id_elemento ?>">
				<input type="hidden" class="dia_cont" value="<?php echo $dia_cont ?>">
				<button class="save_comment_continuidad btn_send_image" type="submit"><?php echo lang('global.guardar') ?><br /><?php echo lang('global.comentarios') ?></button>
			</div>
		</div>
	</div>
		</div>
		<input type="hidden" class="id_produccion" value="<?php echo $id_produccion ?>">


<div id="banner-slide">

        <!-- start Basic Jquery Slider -->
        <ul class="bjqs">
			<li><a href=""><img src="<?php echo base_url().$idioma ?>img/banner01.jpg" title="sdfg"></a></li>
			<li><img src="<?php echo base_url().$idioma ?>img/banner02.jpg" title="sdfgrrt"></li>
			<li><img src="<?php echo base_url().$idioma ?>img/banner03.jpg" title="sdfgrtsy"></li>
        </ul>
        <!-- end Basic jQuery Slider -->

 </div>
