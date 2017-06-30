  <?php $idioma = $this->lang->lang().'/'; ?>
  <div class="loading" style="display:none;">
	<?php echo lang('global.cargando') ?>...
</div>
<div data-role="collapsible" data-corners="false" style="margin:0px !important;">
	<h3><?php echo lang('global.convencion_colores') ?></h3>
	<div>
		<!--div class="colorsBox">
        	<?php $total=sizeof($estados_color) ?>
	        <table width="100%">
	        <?php $i=0;foreach ($estados_color as $estado_color) {?>
	               <tr><td><span class="estado_color" style="background:<?=$estado_color->color?>;"></span><span>
	               <?php if($estado_color->descripcion=="PROGRAMADA TOMAS DE UBICACION"){
	               	echo "PROGRAMADA T.U.";
	               }
	               elseif ($estado_color->id==5) {
	               	echo "NO GRABADA T.U.";
	               }else{
	               	echo $estado_color->descripcion;
	               }?></span></td></tr>
	        <?php } ?> 
	        </table> 
		</div-->
		 <div class="colorsBox">
                <div class="row">
                  <?php $total=sizeof($estados_color) ?>
                    <?php $i=0;foreach ($estados_color as $estado_color) {?>
                       <?php if($estado_color->id!=3){ ?>
                            <?php if( $i==0){
                                echo '<div class="column three"><ul class="colorsList">';
                            }?>
                            <?php if($i==(ceil(($total/2)+1))){
                                echo '</ul></div><div class="column three"><ul class="colorsList">';
                            }?>
                            <li><span class="estado_color" style="background:<?=$estado_color->color?>;"></span><span>
                            	 <?php if($estado_color->descripcion=="PROGRAMADA TOMAS DE UBICACION"){
					               	echo "PROGRAMADA T.U.";
					               }
					               elseif ($estado_color->id==5) {
					               	echo "NO GRABADA T.U.";
					               }else{
					               	echo $estado_color->descripcion;
					               }?>
                            </span></li>
                      <?php } $n=$i+1; if($total==$n){ ?>
                             <?php  //echo '</ul></div><div class="column two"><ul class="colorsList">'; ?> 
                             <li><span class="estado_color" style="background:#000000"></span><span><?php echo lang('global.canceladas') ?></br></span></li>
                      <?php } ?>      
                    <?php ++$i; }
                        echo '</ul></div>'; ?> 
                </div>
            </div>
	</div>
	</div>

<div class="caja_colores_content">
	<aside id="sidebar">
	<span id="close_icon" class="cooki_menu"><span>
	<nav id="side_menu">
	<ul class="menu_list">
	      <li><a class="has-tip tip-centered-top tooltip_info" title="" href="#"></a>
	      <ul class="submenu">
	        <li class="menu_li"></li>
	        <!-- <li>Fecha al Aire</li>
	        <li style="margin-bottom: 5px; border-bottom: 1px solid black;">Entrega de Lib</li> -->
	        <li>% <?php echo lang('global.locacion') ?></li>
	        <li style="margin-bottom: 5px; border-bottom: 1px solid black;">% <?php echo lang('global.estudio') ?></li>
	        <li>% <?php echo lang('global.producido') ?></li>
	        <li><?php echo lang('global.total_tiempo_estima') ?></li>
	        <li><?php echo lang('global.total_tiempo_pro') ?></li>
	        <li><?php echo lang('global.diferencia') ?></li>
	      </ul>
	    </li>
	    <li style="width: 92%; float: right; margin-top:7px;">
	    	<a id="acercar" class="button zoom_max" style="padding:6px; width:50%; float:left; color: #fff;">ZoomOut<br>(-)</a>
			<a id="alejar" class="button zoom_max" style="padding:6px; width:50%; float:left; color: #fff;">ZoomIn<br>(+)</a>
			<a id="resetear" class="button" style="padding:6px; width:100%; color: #fff;">Restart</a>
			<a href="<?=base_url().$idioma.'pdf2/pdf_caja_colores/'.$datos_produccion[0]->id_produccion?>" target="_blank" class="button" style="padding:6px; width:100%; color: #fff;"><?php echo lang('global.imprimir') ?></a>
			<a href="<?=base_url().$idioma.'excel/excel_caja_colores/'.$datos_produccion[0]->id_produccion?>" target="_blank" class="button" style="padding:6px; width:100%; color: #fff;"><?php echo lang('global.guardar') ?></a>
	    </li>		
	 </ul>
	</nav>
	</aside>

	<?php if($capitulos){ ?>
	<div class="content_caja_colores">
		<div class="scroll_inner">
	<?php foreach ($capitulos as $c) { ?>
	<?php
	switch($c['estado']){
	  case 3:
	    $class_cap="cap_progress";
	  break;
	  case 5:
	    $class_cap="cap_completed";
	  break;
	  case 2:
	    $class_cap="cap_deliver";
	  break;
	  case 4:
	    $class_cap="cap_desglosed";
	  break;
	  case 6:
	    $class_cap="cap_canceled";
	  break;
	  case 1:
	    $class_cap="cap_progress_proyected";
	  break;
	} 
	?>
	
		<div class="caja_colores">
			<div class="table_capitulos">
				<table>
					<thead>
					 	<tr>
					 		<td colspan="2" class="<?php echo $class_cap; ?>"><?php echo $c['numero'] ?></td>
					 	</tr>
				 	</thead>
				 	<!-- <tr>
				 		<td colspan="2"><?php if($c['fecha_aire'] and $c['fecha_aire']!='0000-00-00'){ echo date("d-M-Y",strtotime($c['fecha_aire'])); }else{ echo '--';} ?></td>
				 	</tr>
				 	<tr>
				 		<td colspan="2" style="background: #C2C0C1;"><?php if($c['fecha_entregado'] and $c['fecha_entregado']!='0000-00-00'){ echo date("d-M-Y",strtotime($c['fecha_entregado'])); }else{ echo '--';} ?></td>
				 	</tr> -->
				 </table>
				 <table style="margin-top: -5px;">
				 	<tr>
				 		<td colspan="2"><?php echo round((100*$c['locacion'])/$c['total']) ?>%</td>
				 	</tr>	
				 	<tr>
				 		<td colspan="2"><?php echo round((100*$c['estudio'])/$c['total']) ?>%</td>
				 	</tr>
				 </table>
				 <table>
				 	<tr>
				 		<td colspan="2"><?php echo round((100*$c['total_producidos'])/$c['total']) ?>%</td>
				 	</tr>
				 	<tr>
				 		<td style="width: 50%; border-right:none;"><?php echo $c['total'] ?></td>
				 		<td style="width: 50%;"><?php echo calculo_tiempo($c['total_duracion_estimada_minutos'],$c['total_duracion_estimada_segundos']) ?></td>
				 	</tr>
				 	<tr>
				 		<td style="width: 50%; border-right:none; background: #C2C0C1;"><?php echo $c['total_producidos'] ?></td>
				 		<td style="width: 50%; background: #C2C0C1;"><?php echo Dashboard::calculo_tiempo2($c['total_duracion_real_minutos'],$c['total_duracion_real_segundos']) ?></td>
				 	</tr>
				 	<tr>
				 	      <?php  $dif_minutos=$c['total_duracion_real_minutos']-$c['total_duracion_estimada_minutos']; 
				 	             $dif_segundos=$c['total_duracion_real_segundos']-$c['total_duracion_estimada_segundos'];
				 	             $dif_segundos=$dif_segundos+($dif_minutos*60);
				 	      ?>
				 	      <?php $dif_cap=$c['total']-$c['total_producidos'] ?>
				 		<td style="width: 50%; border-right:none; background: #C2C0C1;<?php if($dif_cap<0){ echo 'color:red';} ?>"><?php echo $dif_cap; ?></td>
					 	<td style="width: 50%; background: #C2C0C1;<?php if($dif_minutos<0){ echo 'color:red';} ?>"><?php echo Dashboard::tiempo_segundos($dif_segundos) ?></td>
				 	</tr>
				 </table>
			 </div>
			 <?php $escenas=$this->model_dashboard->escenas_id_capitulos($c['id_capitulo']); ?>
			 <table class="escena_head">
			 	<tr>
			 		<td style="height:auto !important; width: 50%; border-right: none;"><?php echo lang('global.escenas') ?></td>
			 		<td style="height:auto !important; width: 50%;"><?php echo lang('global.tiempo') ?></td>
			 	</tr>
			 </table>
			 <?php if ($escenas): ?>
			 <div class="scroll_escenas">
				<table class="table_escenas">
				  <?php foreach ($escenas as $e): ?>
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
					  <td style="width: 50%; border-right: none; height:auto !important;" class="<?php echo $class ?>">
					  	<?php echo $e['numero_escena'] ?>
					  	<div class="hideBox">
					  		<div><?php echo lang('global.libretos') ?> <?php echo $c['numero'] ?></div>
					  		<div><?php echo lang('global.escenas') ?> <?php echo $e['numero_escena'] ?></div>
					  		<div><?php echo lang('global.tiempo_estimado') ?>: <?php echo $e['duracion_estimada_minutos'].':'.$e['duracion_estimada_segundos']?></div>
					  		<?php if($e['estado']==1){ ?>
			                  <div><?php echo lang('global.tiempo_real') ?>: <?php echo $e['duracion_real_minutos'].':'.$e['duracion_real_segundos']?></div>
					  		<?php }else{ ?>
					  		   <div><?php echo lang('global.tiempo_real') ?>: --</div>
					  		<?php } ?>
					  	</div>
					  </td>
					  <?php if($e['estado']==1){ ?>
					    <td style="width: 50%; height:auto !important;" class="<?php echo $class ?>"><?php echo Dashboard::calculo_tiempo2($e['duracion_real_minutos'],$e['duracion_real_segundos']); ?></td>
					  <?php }else{ ?>
					  	<td style="width: 50%; height:auto !important;" class="<?php echo $class ?>" ><?php echo Dashboard::calculo_tiempo2($e['duracion_estimada_minutos'],$e['duracion_estimada_segundos']); ?></td>
					  <?php } ?>
					</tr>
				  <?php endforeach ?>
				</table>
			</div>
			<?php endif ?>
			</div>
			<?php } ?>
		</div>
		<?php }else { ?>
		    <div><?php echo lang('global.no_hay_libretos_para_esta_produccion') ?></div>
		<?php } ?>	
		</div>
		</div>
	</div>
</div>	
<?php  function calculo_tiempo($minutos,$segundos){
    $minutos2=0;
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 += $minutos;

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }

      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      $tiempo = $minutos2.":".$segundos;
      return $tiempo;
  }
 ?>