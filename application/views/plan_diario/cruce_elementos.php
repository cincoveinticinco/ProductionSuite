<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
    <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> 
    / <a  href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> 
     / <?php echo lang('global.cruce_elementos') ?>
</div>
<nav>
  <ul class="nav_post nav_elementos">
  	<li><a href="<?=base_url().$idioma.'pdf2/pdf_cruce_elementos/'.$produccion['0']->id_produccion.'/'.$fecha_unidad?>" target="_blank" class="buttons icon icon_print"><span></span><?php echo lang('global.imprimir') ?></a></li>
  </ul>
 </nav>
<div id="inner_content">
	<div class="cruce_box">
		<div class="row">
		<?php $orden=""; $impresion=0; if($cruce!="") { ?>
		<?php foreach ($cruce as $c) { ?>
			<?php 
			$validator=0;
			$acumulator=0;
			for ($i=1; $i <= count($unidades); $i++) { 
				$acumulator +=$c['unidad'.$i];
				if($acumulator>$c['unidad'.$i] AND $c['unidad'.$i]>0){
					$validator=1;
					$impresion=1;
				}
			}if($validator){
			?>
			<div class="column twelve itemCruce">
				<div class="top">
					<div><strong><?php echo lang('global.tipo_elemento') ?>:</strong> <?php echo $c['tipo']; ?></div>
					<div><strong><?php echo lang('global.nombre_elemento') ?>:</strong> <?php echo $c['nombre'];?>
					<!--VALIDACION, SI TIENE ROL-->
					<?php if($c['rol_elemento']!=null){?>
					<?php echo ' ( '.$c['rol_elemento'].' )';}?>
					<!--FIN VALIDACION, SI TIENE ROL-->
					</div>
				</div>
				<?php $escenas2 = $this->model_plan_diario->escenas_cruce($idpersonajes,$c['id_elemento'], $fecha_unidad);?>								
				<div id="scroll">
					<div class="table_general">
						<table class="secondary" id="cruce_elementos">
							<tr class="gray">
								<td>UNI</td>
								<td>#</td>
								<td>LIB.</td>
								<td>ESC.</td>
								<td>TIE. EST.</td>
								<td><span><?php echo lang('global.locacion') ?></span></td>
								<td><span><?php echo lang('global.set') ?></span></td>
								<td width="15%"><span><?php echo lang('global.personajes_principales') ?></span></td>
								<td width="15%"><span><?php echo lang('global.personajes_secundarios') ?></span></td>
								<td width="15%"><span><?php echo lang('global.elementos') ?></span></td>
							</tr>
							<?php $unidad_a=0;
							foreach ($escenas2 as $escena2) { ?>
							<?php $contador=0;
							foreach ($escenas2 as $escena3) {
								if($escena3->unidad_numero==$escena2->unidad_numero){
									$contador++;
								}
						 	}?>
								<tr>
									<?php if($unidad_a!=$escena2->unidad_numero) { ?>
									<td rowspan="<?=$contador?>"><?=$escena2->unidad_numero?></td>
									<?php } $unidad_a = $escena2->unidad_numero; ?>
									<td><?=$escena2->orden?></td>
									<td><?=$escena2->numero_libreto?></td>
									<td><?=$escena2->numero_escena?></td>
									<td><?=$escena2->duracion_estimada_minutos.':'.$escena2->duracion_estimada_segundos?></td>
									<td><span><?=$escena2->locacion_nombre?></span></td>
									<td><span><?=$escena2->set_nombre?></span></td>
									<td width="15%">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->personajes_principales;?>">		                       
											<?php
				                            echo Plan_diario::corta_palabra($escena2->personajes_principales,40);
				                            if(strlen($escena2->personajes_principales)>=40){
				                                    echo '...';
				                            } ?>
			                        	</span>
									</td>
									<td width="15%">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->personajes_secundarios;?>">		                       
											<?php
				                            echo Plan_diario::corta_palabra($escena2->personajes_secundarios,40);
				                            if(strlen($escena2->personajes_secundarios)>=40){
			                                    echo '...';
				                            } ?>
				                        </span>
			                    	</td>
									<td width="15%">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->elementos;?>">		                       
											<?php
				                            echo Plan_diario::corta_palabra($escena2->elementos,40);
				                            if(strlen($escena2->elementos)>=40){
			                                    echo '...';
				                            } ?>
				                        </span>
				                    </td>
								</tr>
							<?php } ?>
						</table>
					</div>
								</div>
				</div>
			<?php } } ?>
		<?php } if($impresion==0){?>
		<h2><?php echo lang('global.no_hay_cruce') ?></h2>
		<?php } ?>		
		</div>
	</div>
</div>