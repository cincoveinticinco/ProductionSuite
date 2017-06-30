<?php $idioma = $this->lang->lang().'/'; ?>
<nav>
  <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
  <!--<a href="escenas.html">ESCENAS</a>
  <a href="elementos.html">ELEMENTOS</a>-->
  <a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
  <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion.'/'.$set['0']['id']) ?>"><?php echo lang('global.subir_foto_video') ?></a>
  <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
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
<h2 class="titulo"><?php echo $set['0']['nombre']?></h2>
			<div class="toma_tiempo">
				<div class="galeria">
					<div class="detalles">
						<div class="imagenes">
							
							<div id="banner-slide" style="overflow-x:hidden; overflow-y:auto;">
						        <!-- start Basic Jquery Slider -->
						        <ul class="lista_fotos">
						        <?php $total=0 ?>
						        <?php if($imagenes){ ?>
						           <?php $total=sizeof($imagenes) ?>
						           <?php $cont=1 ?>
							        <?php foreach ($imagenes as $p) { ?>
							            <li id="continuidad_<?php echo $p['id'] ?>">
							              <div class="div_thumb">
							            	<a class="fancybox-thumbs" data-fancybox-group="thumb" href="<?php echo base_url($p['imagen']) ?>">
							            		<img src="<?php echo base_url($p['imagen']) ?>">
							            	</a>
							            	<?php if($this->session->userdata('tipo_production_suite')==1){ ?>
								            	<div data-idcontinuidad="<?php echo $p['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>" data-set="<?php echo $set['0']['nombre'] ?>" class="eliminar_cont_set btn_rojo"><?php echo lang('global.eliminar') ?></div>
								            	<?php }?>
								           </div> 	
							            </li>      
							        <?php $cont++; } ?>
							       
								<?php }else{ ?>
								<?php if($imagenes){ ?>
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
				<a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"  class="btn_volver_plan fl" style="width:100% !important; margin: 0 0 6px 0 !important;">
					<?php echo lang('global.volver_a_elementos') ?>
				</a>

	
				<p><?php echo lang('global.esc_set') ?>:</p>
				
				<table id="dias_continuidad" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td>UNI</td>
							<td><?php echo lang('global.lib') ?></td>
							<td><?php echo lang('global.esc') ?></td>
							<td>CON</td>
							<td><?php echo lang('global.pag') ?></td>
						</tr>
					</thead>
					<tbody class="cont_table_escena">
					  <?php if($escenas){ ?>
						 <?php foreach ($escenas as $e) { ?>
						 <?php switch($e['estado']){
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
								<td class="<?php echo $class ?>"><?php if($e['numero_escena']){ echo $e['numero_escena'];}else{ echo '-'; } ?>/<?php if($e['fecha_inicio']){ echo date("d-M-Y",strtotime($e['fecha_inicio']));}else{ echo '-';} ?></td>
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
				<?php if($comentarios_set){ ?>
			        <?php foreach ($comentarios_set as $comentario_set) { ?>
						<div class="comment">
						<span class="sub"><?=$comentario_set->fecha?> <strong><?=$comentario_set->usuario?></strong></span>
						<p><?=$comentario_set->comentario?></p>
						</div>
			        <?php } ?>
				<?php } ?>
			</div>
			<div class="clr"></div>
			<a href="#" class="btn_comentar" style="width:300px; padding:9px; margin:7px auto;"><?php echo lang('global.agregar') ?> <?php echo lang('global.comentarios') ?></a>
			<div id="agregar_comentario" style="display:none">
				<textarea class="comentario"></textarea>
				<input type="hidden" class="id_set" value="<?php echo $set['0']['id'] ?>">
				<input type="hidden" class="set" value="<?php echo $set['0']['nombre']?>">
				<input type="hidden" class="id_produccion" value="<?php echo $id_produccion ?>">
				<button class="save_comment_continuidad_set btn_send_image" type="submit"><?php echo lang('global.guardar') ?></br><?php echo lang('global.comentarios') ?></button>
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

