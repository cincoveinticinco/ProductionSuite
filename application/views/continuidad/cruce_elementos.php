  <?php $idioma = $this->lang->lang().'/'; ?>
  <style type="text/css">
table.cruce td{
	white-space: nowrap;
}

</style>
<div class="clr"></div>
	<div class="resumen">
		<div class="col">
		<?php if(isset($unidad[0]['id'])){
			$unidad_select=$unidad[0]['id']; 
		} ?>
			<h3> <?php echo lang('global.cruce_elementos') ?> / <?php echo date("d-M-Y",strtotime($fecha_unidad));?></h3>
		</div>
		<div class="clr"></div>
		<div class="col">
			<a href="<?php echo base_url($idioma.'continuidad/plan_diario/'.$produccion[0]->id_produccion.'/'.$unidad.'/'.date("Y-m-d",strtotime($fecha_unidad))) ?>"  class="btn_volver_plan fl"><?php echo lang('global.volver') ?></a>
		</div>
	</div>
	<div class="clr"></div>
	
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
			<div class="contenido_sup">
			<div class="top">
				<div><strong><?php echo lang('global.tipo_elemento') ?>:</strong> <?php echo $c['tipo']; ?></div>
				<div><strong><?php echo lang('global.nombre_elemento') ?>:</strong> <?php echo $c['nombre'];?>
				<?php if($c['rol_elemento']!=null){?>
				<?php echo ' ( '.$c['rol_elemento'].' )';}?>
				</div>
			</div>
			<div class="col scrolling">
				<?php $escenas2 = $this->model_plan_diario->escenas_cruce($idpersonajes,$c['id_elemento'], $fecha_unidad);?>								
						<table class="plan_diario cruce" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<td width="20">UNI</td>
									<td width="20">#</td>
									<td width="20"><?php echo lang('global.lib') ?>.</td>
									<td width="50"><?php echo lang('global.esc') ?>.</td>
									<td width="">TIE. EST.</td>
									<td width=""><span><?php echo lang('global.locacion') ?></span></td>
									<td width=""><span>SET</span></td>
									<td width=""><span><?php echo lang('global.personajes_principales') ?></span></td>
									<td width=""><span><?php echo lang('global.personajes_secundarios') ?></span></td>
									<td width=""><span><?php echo lang('global.elementos') ?></span></td>
								</tr>
							</thead>
							<?php $unidad_a=0;
							foreach ($escenas2 as $escena2) { ?>
							<?php $contador=0;
							foreach ($escenas2 as $escena3) {
								if($escena3->unidad_numero==$escena2->unidad_numero){
									$contador++;
								}
						 	}?>
								<tr>
									<?php if($unidad_a!=$escena2->unidad_numero){ ?>
									<td width="20" rowspan="<?=$contador?>"><?=$escena2->unidad_numero?></td>
									<?php } $unidad_a = $escena2->unidad_numero; ?>
									<td width="20"><?=$escena2->orden?></td>
									<td width="50"><?=$escena2->numero_libreto?></td>
									<td width="20"><?=$escena2->numero_escena?></td>
									<td width=""><?=$escena2->duracion_estimada_minutos.':'.$escena2->duracion_estimada_segundos?></td>
									<td width=""><span><?=$escena2->locacion_nombre?></span></td>
									<td width=""><span><?=$escena2->set_nombre?></span></td>
									<td width="">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->personajes_principales;?>">		                       
											<?php
								                            echo Continuidad::corta_palabra($escena2->personajes_principales,40);
								                            if(strlen($escena2->personajes_principales)>=40){
								                                    echo '...';
								                            } ?>
							                        	</span>
									</td>
									<td width="">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->personajes_secundarios;?>">		                       
											<?php
				                            echo Continuidad::corta_palabra($escena2->personajes_secundarios,40);
				                            if(strlen($escena2->personajes_secundarios)>=40){
				                                    echo '...';
				                            } ?>
				                        </span>
			                    	</td>
									<td width="">
										<span style="font-weight: normal;width:100%;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena2->elementos;?>">		                       
											<?php
				                            echo Continuidad::corta_palabra($escena2->elementos,40);
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
			<?php } } ?>
		<?php } if($impresion==0){?>
		<div class="resumen">
			<div class="col" style="width:99%;">
				<center>
					<h2><?php echo lang('global.no_hay_cruce') ?></h2>
				</center>
			</div>
		</div>
		<?php } ?>		
	