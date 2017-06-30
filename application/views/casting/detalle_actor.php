<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.actor') ?>
</div>

<nav>
    <ul class="nav_post nav_casting">
    	<li><a href="<?=base_url($idioma.'casting')?>" class="active"><?php echo lang('global.volver') ?></a></li>
        <li><a href="<?=base_url($idioma.'casting')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.buscar_actor') ?></a></li>
        <?php if($permisos=='write'){ ?>
	    	<li><a href="<?=base_url($idioma.'casting/anadir_actor')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_actor') ?></a></li>
	    	<li><a href="<?=base_url($idioma.'casting/editar_actor/'.$actor[0]->id)?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.editar_actor') ?></a></li>
	    	<?php if (!$solicitudes_actor): ?>
	    		    	<li><a href="#" data-id="<?php echo $actor[0]->id ?>" class="buttons icon icon_plus" id="eliminar_actor"><span></span><?php echo lang('global.eliminar_actor') ?></a></li>
	    	<?php endif ?>
    	<?php } ?>
    	<!-- <li><a href="<?=base_url($idioma.'pdf/pdf_actor/'.$actor[0]->id)?>" class="buttons icon icon_plus" id="crear_libreto" target="_blank"><span></span>Imprimir</a></li> -->
    </ul>
</nav>

<div id="inner_content ">
	<div class="column twelve">
	<!-- MENSAJES DE ALERTA -->
	<?php if ($msg==1): ?>
		<div class="alert-box success">
          <?php echo lang('global.actor_creado_exitosamente') ?>	
          <a href="" class="close">&times;</a>
        </div>
	<?php endif ?>
	<?php if ($msg==2): ?>
		<div class="alert-box success">
          <?php echo lang('global.actor_editado_exitosamente') ?>	
          <a href="" class="close">&times;</a>
        </div>
	<?php endif ?>
	<!-- FIN  MENSAJES DE ALERTA -->
	</div>

  	<div class="contentDetalleActor">
      	<?php if ($actor): ?>
      		<!-- NOMBRE -->
      		<div class="row">
		        <div class="column twelve">
		          <h3 class="tit_actor">
		            <?=$actor[0]->apellido?>, <?=$actor[0]->nombre?>
					 <?php if($actor[0]->disponible==1): ?>
					 	<span class="disponible"><span class="semaforo"></span> <?php echo lang('global.disponible') ?></span>
					 <?php else: ?>
					 	<span class="no_disponible"><span class="semaforo"></span> no <?php echo lang('global.disponible') ?>
					 	<?php if($actor[0]->terminacion_proyecto){ ?>
					 	 <?=date('d-M-Y', strtotime($actor[0]->terminacion_proyecto))?></span>
					 	 <?php } ?>
		          	<?php endif; ?>
		          </h3>
	        		       
		        </div>
		       
		    </div>
		    <!-- IMAGENES Y SOLICITUDES -->
		    <div class="row">
		        <div class="column four" style="padding-right:5px">
		        <?php if ($fotos_actor): ?>
			        <div class="columns twelve fotoPrincipal panel" style="padding: 0;overflow: hidden;">
			        	<?php if ($fotos_actor[0]->ruta_foto!=""): ?>
				       		<img src="<?=base_url($fotos_actor[0]->ruta_foto)?>" style="" id="foto_principal">
				       	<?php else: ?>
							<img src="<?=base_url($idioma.'/images/casting/default.jpg')?>" style="" id="foto_principal">
			        	<?php endif ?>
			        </div>
			        
			        <div class="column twelve fotosPequenas">
			        	<?php foreach ($fotos_actor as $foto_actor): ?>
			        		<?php if ($foto_actor->ruta_foto!=""): ?>
				            	<span><img src="<?=base_url($foto_actor->ruta_foto)?>" width="" class="g_scale"></span>
				            <?php endif ?>
			            <?php endforeach ?>
			        </div>
		        	<?php else: ?>
		        	<div class="columns twelve fotoPrincipal">
			       		<img src="<?=base_url($idioma.'/images/casting/default.jpg')?>" style="height:400px; width:100%;" id="foto_principal">
			       	</div>
			    <?php endif ?>
		    	
		        
		        
	        <?php if ($videos_actor): ?>
	        	<div class="row">
	        	<div class="columns twelve">
	        		<div class="">
	        		<?php foreach ($videos_actor as $video_actor): ?>
	        		<div class="content-video">
	        			<?php if (strpos(strtolower($video_actor->url),'youtube')): ?>
	        				<?php $idvideo = explode('?v=', $video_actor->url); ?>
	        				<iframe width="270" height="180" src="//www.youtube.com/embed/<?=$idvideo[1]?>" frameborder="0" allowfullscreen>
	        				</iframe>
	        			<?php else: ?>
	        				<?php if (strpos(strtolower($video_actor->url),'vimeo')): ?>
	        					<?php $idvideo =  explode('.com/', $video_actor->url); ?>
	        					<iframe src="//player.vimeo.com/video/<?=$idvideo[1]?>" width="210" height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	        				<?php else: ?>	
	        					<a href="<?=$video_actor->url?>"><?=$video_actor->url?></a>
	        				<?php endif ?>
	        			<?php endif ?>
	        			</div>
	        		<?php endforeach ?>
	        		</div>
	        	</div>
	        </div>
	        <?php endif ?>


		        <!-- FIN TABLA DE SOLICITUDES -->
	      	</div>
	      	<!-- DATOS ACTOR -->

	      	<div class="column eight no-padding-left">
	      		<!-- TABLA DE SOLICITUDES -->
		        <?php if ($solicitudes_actor): ?>
			        <div class="column twelve no-padding">
			          <table class="tabla_detalle_actor " cellpadding="0" cellspacing="0" border="1">
			                <thead>
			                  <tr>
			                    <td><?php echo lang('global.numero') ?>.</td>
			                    <td><?php echo lang('global.estado_solicitud') ?></td>
			                    <td><?php echo lang('global.monto') ?></td>
			                    <td><?php echo lang('global.produccion') ?></td>
			                    <td><?php echo lang('global.fecha_creacion') ?></td>
			                  </tr>
			                </thead>
			                <tbody>
			                	<?php foreach ($solicitudes_actor as $solicitud_actor): ?>
				                  <tr class="estado_solicitud<?=$solicitud_actor->id_estado?>">
				                    <td><a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud_actor->id_solicitud)?>"><?php echo Casting::completar_id($solicitud_actor->id_solicitud);?></a></td>
				                    <td><?=$solicitud_actor->estados_solicitud?></td>
				                    <td><?=number_format((double)$solicitud_actor->honorarios, 2, '.', ",")?></td>
				                    <td><?=$solicitud_actor->nombre_produccion?></td>
				                    <td><?=$solicitud_actor->fecha_creacion?></td>
				                  </tr>
			                  	<?php endforeach ?>
			                </tbody>
			          </table>
			          <div class="clr"></div>
			          <!-- <button class="button twelve h_40">
			            ver todas las solicitudes
			          </button> -->
			        </div> 
			        <div class="clr"></div>
			    <?php else: ?>
			    <div class="column twelve no-padding">
			    	<span class="no-solicitud"><?php echo lang('global.no_hay_solicitudes') ?></span>
				</div>
		        <?php endif ?>
		        <div class="clr"></div>
	            <div class="columns twelve no-padding">
		            <a href="<?=base_url($idioma.'casting/crear_solicitud/'.$actor[0]->id)?>" class="button twelve h_40 btnAsignar">
              			<?php echo lang('global.asignar_actor_a_personaje') ?>
		            </a>
	            </div>
	            <div class="clr"></div>
		      	<ul class="accordion" id="accordion_actor">
		      		<li class="active">
		      			<div class="title">
		      				<h5><?php echo lang('global.datos_personales') ?></h5>
		      			</div>
		      			<div class="content" style="display:block">
		      				<div class="row">
		      					<!-- <div class="column two no-padding"><div class="panel h_41">&nbsp;</div></div> -->
		      					<div class="column twelve no-padding">
							        	<table class="table_actor">
							        		<tr>
							        			<td>
							        				<label>Nacionalidad</label>
							          				<span><?=$actor[0]->nacionalidad?></span>
							        			</td>
							        			<td>
							        				<label>documento</label>
							          				<span><?=$actor[0]->tipo_documento.' '.$actor[0]->documento?></span>
							        			</td>
							        			<td colspan="2">
							        				<label><?php echo lang('global.fecha_nacimiento') ?></label> 
							          				<span><?=date("d-M-Y", strtotime($actor[0]->fecha_nacimiento))?></span>
							        			</td>
							        		</tr>
							        		<tr>
							        			<td>
							        				<label><?php echo lang('global.genero') ?></label>
							          				<span><?=$actor[0]->genero?></span>
							        			</td>
							        			<td>
							        				<label><?php echo lang('global.altura_metros') ?></label>
							          				<span><?=$actor[0]->altura?>M</span>
							        			</td>
							        			<td colspan="2">
							        				<label><?php echo lang('global.peso_kg') ?></label>
							          				<span><?=$actor[0]->peso?>Kg</span>
							        			</td>
							        		</tr>
							        		<tr>
							        			<td>
							        				<label><?php echo lang('global.color_tez') ?></label>
							          				<span><?=$actor[0]->color_tez?></span>
							        			</td>
							        			<td>
							        				<label><?php echo lang('global.color_ojos') ?></label>
							          				<span><?=$actor[0]->color_ojos?></span>
							        			</td>
							        			<td colspan="2">
							        				 <label><?php echo lang('global.idioma') ?></label>
							          				 <span><?= str_replace(',', ', ', $actor[0]->idiomas)?></span>
							        			</td>
							        		</tr>
							        		<tr>
							        			<td>
							        				<label><?php echo lang('global.telefono_fijo') ?> <?php echo lang('global.actor') ?></label>
							          				<span><?=$actor[0]->telefono_fijo?></span>
							        			</td>
							        			<td>
							        				 <label><?php echo lang('global.telefono_movil') ?> <?php echo lang('global.actor') ?></label>
							          				 <span><?=$actor[0]->telefono_movil?></span>
							        			</td>
							        			<td colspan="2">
							        				<label><?php echo lang('global.direcionn_residencial') ?></label>
							          				<span><?=$actor[0]->direccion?></span>
							        			</td>
							        		</tr>
							        		<tr>
							        			<td>
							        				<label><?php echo lang('global.ciudad_residencia') ?></label>
							          				<span><?=$actor[0]->nombre_ciudad?></span>
							        			</td>
							        			<td>
							        				<label><?php echo lang('global.pais_residencia') ?></label>
							          				<span><?=$actor[0]->nombre_pais?></span>
							        			</td>
							        			<td colspan="2">
							        				<label><?php echo lang('global.correo_electronico') ?></label>
							          				<span><a href="mailto:<?=$actor[0]->email?>"><?=$actor[0]->email?></a></span>
							        			</td>
							        		</tr>
                                            
                                            <?php if($actor[0]->id_tipo_documento==2){ ?>
							        		<tr>
							        			<td>
							        				<label><?php echo lang('global.nombre_representante_legar') ?></label>
							          				<span><?=$actor[0]->nombre_representante_legal?></span>
							        			</td>
							        			<td>
							        				<label><?php echo lang('global.numero_documento_representante_legal') ?></label>
							          				<span><?=$actor['0']->tipo_documento_representante_legal.' '.$actor[0]->documento_representante_legal?></span>
							        			</td>
							        			<td colspan="2">
							        				 <label><?php echo lang('global.telefono_fijo_representante_legal') ?></label>
							          				 <span><?=$actor[0]->telefono_fijo_representante?></span>
							        			</td>
							        			
							        		</tr>
							        		<tr>
							        		    <td>
							        				<label><?php echo lang('global.celular_representante_legal') ?></label>
							          				<span><?=$actor[0]->celular_representante?></span>
							        			</td>
							        			<td>
							        				<label><?php echo lang('global.direccion_representante_legal') ?></label>
							          				<span><?=$actor[0]->direccion_representante?></span>
							        			</td>
							        			<td colspan="2">
							        				<label><?php echo lang('global.correo_representante_legal') ?></label>
							          				<span><?=$actor[0]->correo_representante?></span>
							        			</td>
							        		</tr>
							        		<tr>
							        			<td >
							        				<label><?php echo lang('global.contactar') ?></label>
							          				<span><?=$actor[0]->contacto?></span>
							        			</td>
							        		</tr>
							        		<?php }else{ ?>
							        		<tr>
							        		<td colspan="2">
							        				<label><?php echo lang('global.contactar') ?></label>
							          				<span><?=$actor[0]->contacto?></span>
							        			</td>
							        		</tr>
							        		<?php } ?>


							        		<tr>
							        			
							        		</tr>
							        	</table>
						        </div>
						        
						        
						        <!-- <div class="column two no-padding"><div class="panel h_41">&nbsp;</div></div> -->
		      				</div>
		      				
		      			</div>
		      		</li>
		      		
		      		<?php if ($actor[0]->id_manager): ?>
			      		<li>
			      			<div class="title">
			      				<h5>MANAGER</h5>
			      			</div>
			      			<div class="content">
			      				 <!-- MANAGER -->
			      				<div class="row">
			      					<div class="column twelve no-padding">
				      					<table class="table_actor"> 
				      						<tr>
				      							<td colspan="2">
				      								<label><?php echo lang('global.nombre') ?></label>
								          			<span><?=$actor[0]->nombre_manager?></span>
				      							</td>
				      							<td colspan="2">
				      								<label><?php echo lang('global.telefono_fijo') ?></label>
								          			<span><?=$actor[0]->telefono_fijo_manager?></span>
				      							</td>
				      						</tr>
				      						<tr>
				      							<td colspan="2">
				      								<label><?php echo lang('global.telefono_movil') ?></label>
								          			<span><?=$actor[0]->telefono_movil_manager?></span>
				      							</td>
				      							<td colspan="2">
				      								<label><?php echo lang('global.correo_electronico') ?></label>
								          			<span><?=$actor[0]->email_manager?></span>
				      							</td>
				      						</tr>
				      					</table>
			      					</div>
						        </div>
						        <!-- FIN MANAGER -->
			      			</div>
			      		</li>
		      		<?php endif ?>

					<?php if ($actor[0]->contacto_nombre || $actor[0]->contacto_telefono || $actor[0]->contacto_telefono_movil || $actor[0]->contacto_email): ?>
			      		<li>
			      			<div class="title">
			      				<h5><?php echo lang('global.contacto_contractual') ?></h5>
			      			</div>
			      			<div class="content">
			      				<div class="row">
			      					<!-- CONTACTO CONTRACTUAL -->
			      					<div class="column twelve no-padding">
				      					<table class="table_actor"> 
				      						<tr>
				      							<td colspan="2">
				      								<label><?php echo lang('global.nombre') ?></label>
									         		<span><?=$actor[0]->contacto_nombre?></span>
				      							</td>
				      							<td colspan="2">
				      								<label><?php echo lang('global.telefono_fijo') ?></label>
									         		<span><?=$actor[0]->contacto_telefono?></span>
				      							</td>
				      						</tr>
				      						<tr>
				      							<td colspan="2">
				      								<label><?php echo lang('global.telefono_movil') ?></label>
									         		<span><?=$actor[0]->contacto_telefono_movil?></span>
				      							</td>
				      							<td colspan="2">
				      								<label><?php echo lang('global.correo_electronico') ?></label>
									         		<span><?=$actor[0]->contacto_email?></span>
				      							</td>
				      						</tr>
				      					</table>
			      					</div>
						        </div>
						        <!-- FIN CONTACTO CONTRACTUAL -->
			      				</div>
			      		</li>
		      		<?php endif ?>

		      		<?php if ($actor[0]->id_sociedad): ?>
			      		<li>
			      			<div class="title">
			      				<h5><?php echo lang('global.datos_sociedad') ?></h5>
			      			</div>
			      			<div class="content">
			      				<div class="row">
			      					<div class="column twelve no-padding">
					      				 <!-- SOCIEDAD -->
					      				 <table class="table_actor">
					      				 	<tr>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.razon_social') ?></label>
									            	<span><?=$actor[0]->razon_social_sociedad?></span>
					      				 		</td>
					      				 		<td colspan="2">
					      				 			<label>nit</label>
									            	<span><?=$actor[0]->nit_sociedad?></span>
					      				 		</td>
					      				 	</tr>
					      				 	<tr>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.direccion') ?></label>
									            	<span><?=$actor[0]->direccion_sociedad?></span>
					      				 		</td>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.correo_electronico') ?></label>
									            	<span><?=$actor[0]->email_sociedad?></span>
					      				 		</td>
					      				 	</tr>
					      				 	<tr>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.telefono_fijo') ?></label>
									            	<span><?=$actor[0]->telefono_fijo_sociedad?></span>
					      				 		</td>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.telefono_movil') ?></label>
									            	<span><?=$actor[0]->telefono_movil_sociedad?></span>
					      				 		</td>
					      				 	</tr>
					      				 	<tr>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.pais') ?></label>
									            	<span><?=$actor[0]->pais_sociedad?></span>
					      				 		</td>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.ciudad') ?></label>
									            	<span><?=$actor[0]->ciudad_sociedad?></span>
					      				 		</td>
					      				 	</tr>
					      				 	<tr>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.representante_legal') ?></label>
									            	<span><?=$actor[0]->representante_legal?></span>
					      				 		</td>
					      				 		<td colspan="2">
					      				 			<label><?php echo lang('global.documento_representante') ?>:</label>
									            	<span><?=$actor[0]->documento_representante?></span>
					      				 		</td>
					      				 	</tr>
					      				</table>
							        </div>
				        		</div>
			        		</div>
			      		</li>
		      		<?php endif ?>
		      	
		      		<?php if ($proyectos_actor): ?>
			      		<li>
			      			<div class="title">
			      				<h5><?php echo lang('global.histotial_artisitico') ?></h5>
			      			</div>
			      			<div class="content">
			      				<div class="row">
									<!-- PROYECTOS ACTOR -->
						      	
							        <div class="column twelve  no-padding">
								        <div class="">
								          <table cellpadding="0" cellspacing="0" border="1" class="tabla_detalle_actor">
								            <thead>
								              <tr >
								                <td><?php echo lang('global.produccion') ?></td>
								                <td><?php echo lang('global.rol') ?></td>
								                <td><?php echo lang('global.personajes') ?></td>
								                <td><?php echo lang('global.ano') ?></td>
								              </tr>
								            </thead>
								            <tbody>
								            	<?php foreach ($proyectos_actor as $proyecto_actor): ?>
								            		<tr>
										                <td><?=$proyecto_actor->nombre_proyecto?></td>
										                <td><?=$proyecto_actor->rol?></td>
										                <td><?=$proyecto_actor->nombre_personaje?></td>
										                <td><?=$proyecto_actor->ano?></td>
										             </tr>
								            	<?php endforeach ?>
								            </tbody>
								          </table>	
								        </div>
							        </div>
		   						</div>
		      				</div>
			      		</li>
		      		<?php endif ?>

		      		<?php if ($documentos_actor): ?>
			      		<li id="documentsL">
			      			<div class="title"><h5><?php echo lang('global.documentos') ?></h5></div>
			      			<div class="content">
			      				<div class="row">

				      				<?php if ($documentos_actor): ?>
				      				 <div class="column twelve  no-padding">
				      				 	<table class="table_actor">
				      				 		<?php //print_r($documentos_actor); ?>
					      					<?php foreach ($documentos_actor as $documento_actor): ?>
					      						
					      						<?php if ($documento_actor->descripcion!="" OR 
					      								  isset($documento_actor->clase_entidade) OR 
					      								  $documento_actor->pais!="" OR 
					      								  $documento_actor->url!="" OR 
					      								  $documento_actor->estado_entidad!=""): ?>
					      						<span class="validaDocuments"></span>

												<tr>
						      						<td>
						      							<label><?=$documento_actor->tipo_documentacion?></label>
						      							<span>
							      							<?php if ($documento_actor->id_tipo_documentacion!=1): ?>
									      						<?=$documento_actor->descripcion?>
									      					<?php else: ?>	
									      						---
															<?php endif ?>
														</span>
													</td>
		
					      							
													<?php if ($documento_actor->id_tipo_documentacion==2 
															OR $documento_actor->id_tipo_documentacion==3
															OR $documento_actor->id_tipo_documentacion==4): ?>
														<td>
															<label for=""><?php echo lang('global.activo') ?></label>
								      						<span><?=$documento_actor->estado_entidad?></span>
							      						</td>
							      						<td>
								      						<label for=""><?php echo lang('global.clase') ?></label>	
								      						<span><?=$documento_actor->clase_entidade?></span>
								      					</td>
								      				<?php elseif ($documento_actor->id_tipo_documentacion!=6 AND  $documento_actor->id_tipo_documentacion!=5): ?>
									      				<td>
															<label for="">&nbsp;</label>
								      						<span>&nbsp;</span>
							      						</td>
							      						<td>
								      						<label for="">&nbsp;</label>
								      						<span>&nbsp;</span>
								      					</td>
													<?php endif ?>

													<?php if ($documento_actor->id_tipo_documentacion==6 OR $documento_actor->id_tipo_documentacion==5): ?>
														<td>
															<label for=""><?php echo lang('global.pais') ?></label>	
								      						<span ><?=$documento_actor->nombre_pais?></span>
								      					</td>
								      					<?php if ($documento_actor->id_tipo_documentacion==6){?>
								      					<td>
								      						<label for="">vigencia</label>
								      						<span><?php if ($documento_actor->vigencia AND $documento_actor->vigencia!="0000-00-00") {
								      							echo date('d-M-Y', strtotime($documento_actor->vigencia));
								      						}?></span>
									      				</td>
									      				<?php } ?>
													<?php endif ?>
													<td>
														<!-- VALIDACION ICONO DOCUMENTO -->
														<?php if ($documento_actor->url){ ?>
						      								<span><a href="<?=base_url($documento_actor->url)?>" target="_blank"><img src="<?php echo base_url($idioma.'images/icon_'.Casting::tipo_icono($documento_actor->url).'.png')?>" alt="" style="width:15%"></a></span>
						      							<?php }else{ ?>
						      							  <span><?php echo lang('global.no_hay_documentos') ?></span>
						      							<?php } ?>

						      						</td>
				      							</tr>
				      							<?php endif ?>
					      					<?php endforeach ?>
				      					</table>
					      			</div>
				      				<?php endif ?>
			      				</div>
			      			</div>
			      		</li>
		      		<?php endif ?>

		      		<script>
		      			if ($('.validaDocuments').length<=0) {
		      				$('#documentsL').remove();
		      			};
		      		</script>

	      			<li>
		      			<div class="title" style="backgroud:#C7541B" id="datos_casting">
		      				<h5><?php echo lang('global.datos_de_casting') ?></h5>
		      			</div>
		      			<div class="content">
		      				<div class="row">
		      					<div class="column twelve no-padding">
						      		<div class="panel">
						      			<label><?php echo lang('global.rol') ?></label>
								        <span><?=$actor[0]->rol?></span>
								        <hr>
							      		<label><?php echo lang('global.notas_actor') ?></label>
							      		<span><?=$actor[0]->notas_actor?></span>
						      		</div>
						      	</div>
		      				</div>
		      			</div>
		      		</li>
<!-- 		      		<?php if ($actor[0]->tipo_documento == 3): ?>
			      		<li>
			      			<div class="title">
			      				<h5></h5>
			      			</div>
			      			<div class="content">
			      				<div class="row">
							        <div class="column eight">
									        <div class="info">
									          <div class="column six">
									            <label>Visa</label>
									            <span>
									            	<?php if ($actor[0]->visa_numero): ?>
									            		<?=$actor[0]->visa_numero?>
								            		<?php else: ?>
									            	    -
									            	<?php endif ?>
									            </span>
									            <label>documento</label>
									            <span>
									            	<?php if ($actor[0]->visa_url): ?>
									            		<a href="<?=base_url($actor[0]->visa_url)?>" target="_blank">Ver</a>
									            		<?php else: ?>
									            	    -
									            	<?php endif ?>
									            </span>
									            <label>País</label>
									            <span>
									            	<?php if ($actor[0]->visa_pais): ?>
									            		<?=$actor[0]->visa_pais?>
									            		<?php else: ?>
									            	    -
									            	<?php endif ?>
									            </span>
									          </div>
									          <div class="column six">
									            <label>Pasaporte</label>
									            <span>
									            	<?php if ($actor[0]->pasaporte): ?>
									            		<a href="<?=base_url($actor[0]->pasaporte)?>" target="_blank">Ver</a>
									            	<?php else: ?>
									            	    -
									            	<?php endif ?>
									            </span>
									            <label>Cédula extranjera</label>
									            <span>
									            	<?php if ($actor[0]->cedula_extranjera): ?>
									            		<a href="<?=base_url($actor[0]->cedula_extranjera)?>" target="_blank">Ver</a>
									            	<?php else: ?>
									            	    -
									            	<?php endif ?>
									            </span>
									          </div>
									          <div class="clr"></div>
									        </div>
								       	</div>
			      				</div>
			      			</div>
			      		</li>
		      		<?php endif ?> -->
		      	</ul>
	      	</div>
	        
	        <!-- FIN DATOS ACTOR -->
	      	
	        <!-- FIN PROYECTOS ACTOR -->

      	<?php endif ?>
  	</div>
</div>