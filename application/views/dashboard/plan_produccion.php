  <?php $idioma = $this->lang->lang().'/'; ?>
  <script type="text/javascript" charset="utf-8">
$(document).ready( function () {
  var oTable = $('#fixed_header').dataTable({
    "sScrollY": 293,
    "bScrollCollapse": true,
    "bPaginate": false,
        "sScrollX": "100%",
    "sScrollXInner": "110%",
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
        "bSort": false
  });
  new AutoFill( oTable );
} );
</script>
<div id="inner_content">
  <div class="top_page top_page_planproduccion row">
    <?php if(form_error('valor')){ ?>
    <div class="columns twelve">
      <div class="alert-box alert">
        <?php echo form_error('valor'); ?>
        <a href="" class="close">&times;</a>
      </div>
    </div>
    <?php } ?>
    <div class="column nine">
      <div class="columns three">
        <div  id="capitules_green_box" class="red_box color_box">
          <span class="title"><?php echo lang('global.libretos_entregados') ?></span>
          <span class="number"><?=sizeof($capitulos)?>/<?= $produccion['0']->numero_capitulo ?></span>
          <span class="dif"><?php echo lang('global.diferencia') ?> <span id="difference_capitules"></span></span>
        </div>
      </div>
      <div class="columns three">
        <div id="minutes_green_box" class="green_box color_box">
          <span class="title"><?php echo lang('global.minutos_producidos') ?></span>
         
          <?php $minutos=$produccion['0']->minuto_capitulo;
            $segundos=$minutos*60;
            $segundos=$segundos*$produccion['0']->numero_capitulo;
            $segundos2=$produccion['0']->segundos_capitulo;
            $segundos2=$segundos2*$produccion['0']->numero_capitulo;
            $total=$segundos+$segundos2;
            
            function segundos_tiempo($segundos){
            $minutos=floor($segundos/60);
            $segundos_2=$segundos%60%60%60;
            $resultado=$minutos.'.'.$segundos_2;
            return $resultado;
            }
            $m_total=floor($total/60);
            $segundos_2=$total%60;
            if(strlen($segundos_2)<2){
              $segundos_2 = '0'.$segundos_2;
            }
            $m_total=$m_total.':'.$segundos_2;
            ?>
          <span class="number"><?=$minutos_producidos?>/<?=($m_total);?></span>
          <span class="dif"><?php echo lang('global.editando') ?> <span id="difference_minutes"></span></span>

        </div>
      </div>

       <div class="columns three" style="float:left">
        <div id="" class="green_box color_box" style="background-color:#10AD9B">
          <span class="title"><?php echo lang('global.escena_por_minuto') ?></span>
         
          
          <span class="number">
          <?php
           if($produccion['0']->minuto_capitulo and $produccion['0']->minuto_capitulo!=0 and $produccion['0']->escenas_libretos and $produccion['0']->escenas_libretos!=0){ 
              echo number_format((float)$produccion['0']->minuto_capitulo/$produccion['0']->escenas_libretos, 2, '.', ''); ;
           }else{
            echo '0';
           }
           ?>
          </span>

        </div>
      </div>

    </div>
  </div>
  <div class="column twelve" id="scroll">
    <div class="table_general">
      <table class="main" id="fixed_header" cellpadding="0" cellspacing="0" border="0" class="display">
        <thead>
          <tr class="blue">
            <td nowrap="nowrap" class="td_weeknumber">&nbsp;</td>
            <td nowrap="nowrap" class="td_unit"><?php echo lang('global.unidad') ?></td>
            <td nowrap="nowrap" class="td_week"><?php echo lang('global.semana') ?></td>
            <td nowrap="nowrap" class="td_write_cap"><?php echo lang('global.escritura_libretos') ?></td>
            <td nowrap="nowrap" class="td_mproy"><?php echo lang('global.min_proy') ?></td>
            <td nowrap="nowrap" class="td_min_really"><?php echo lang('global.minutos_reales') ?></td>
            <td nowrap="nowrap" class="td_dif"><?php echo lang('global.diferencia') ?></td>
            <td nowrap="nowrap" class="td_pronostico"><?php echo lang('global.pronostico_escenas') ?></td>
            <td nowrap="nowrap" class="td_comments"><?php echo lang('global.comentarios') ?></td>
          </tr>
          <tr class="gray">
            <td><?php echo lang('global.semana') ?></td>
            <?php 
            /* $dias=((strtotime($produccion[0]->fin_grabacion) - strtotime($produccion[0]->inicio_PreProduccion)))/86400 ;
             $semanas = $dias/7;
             $semanas = floor($semanas);*/
            ?>
            <td>
              <table class="secondary">
                <tr>
                  <?php $j=1; ?>
                  <?php if ($produccion['0']->numero_unidades == 1){
                    $width = '100%';
                  }elseif ($produccion['0']->numero_unidades == 2) {
                    $width = '50%';
                  }elseif ($produccion['0']->numero_unidades == 3) {
                    $width = '33.33%';
                  }elseif ($produccion['0']->numero_unidades == 4) {
                    $width = '25%';
                  }elseif ($produccion['0']->numero_unidades == 5) {
                    $width = '20%';
                  } ?>
                    
                  <?php $m=0; while ($j<=$produccion['0']->numero_unidades) { ?>

                    <td width="<?php echo $width; ?>">
                      <a href="#unidad<?=$unidad[$m]['numero']?>" data-rel="popup" class="linkTooltip" >
                        <span class="has-tip tip-centered-top" title="UNIDAD # <?=$unidad[$m]['numero']?>">
                          <?php echo $unidad[$m]['numero'];?>
                        </span>
                      </a>
                      <div data-role="popup" id="unidad<?=$unidad[$m]['numero']?>"><p><?php echo lang('global.unidad') ?> # <?php echo $unidad[$m]['numero']; ++$m;?></p></div>
                    </td>
                 <?php $j++; } ?>
                 </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="10%">
                    <a href="#D" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dias_trabajar') ?>">#D</span></a>
                    <div data-role="popup" id="D"><p><?php echo lang('global.dias_trabajar') ?></p></div>
                  </td>
                  <td width="30%">
                    <a href="#inicio" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.inicio_semana') ?>">inicio</span></a>
                    <div data-role="popup" id="inicio"><p><?php echo lang('global.inicio_semana') ?></p></div>
                  </td>
                  <td width="30%">
                    <a href="#fin" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.fin_semana') ?>">fin</span></a>
                    <div data-role="popup" id="fin"><p><?php echo lang('global.fin_semana') ?></p></div>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="12.2%">
                    <a href="#pro" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_programados_semana') ?>">PRO</span>
                     <div data-role="popup" id="pro"><p><?php echo lang('global.libretos_programados_semana') ?></p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#acp" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos_semanales') ?>">AC.P</span>
                     <div data-role="popup" id="acp"><p><?php echo lang('global.acumulado_libretos_semanales') ?></p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#ent" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_entregados') ?>">ENT</span>
                     <div data-role="popup" id="ent"><p><?php echo lang('global.libretos_entregados') ?></p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#ace" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos') ?>">AC.E</span>
                     <div data-role="popup" id="ace"><p><?php echo lang('global.acumulado_libretos') ?></p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#dif" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="AC.E – AC.P">dIF</span>
                     <div data-role="popup" id="dif"><p>AC.E – AC.P</p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#des" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_desglosados_semanal') ?>">DES</span>
                     <div data-role="popup" id="des"><p><?php echo lang('global.libretos_desglosados_semanal') ?></p></div>  
                  </td>
                  <td width="12.2%">
                    <a href="#acd" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos_desglosados') ?>">AC.D</span>
                     <div data-role="popup" id="acd"><p><?php echo lang('global.acumulado_libretos_desglosados') ?></p></div>  
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%">
                    <a href="#sem" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.minutos_proyectados_semanal') ?>">SEM</span></a>
                    <div data-role="popup" id="sem"><p><?php echo lang('global.minutos_proyectados_semanal') ?></p></div>  
                  </td>
                  <td width="50%">
                    <a href="#acm" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_minutos_proyectados') ?>">acm</span></a>
                    <div data-role="popup" id="acm"><p><?php echo lang('global.acumulado_minutos_proyectados') ?></p></div>  
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                <?php
                /*VALIDACION TAMAÑO CELDAS UNIDADES*/
                $size = count($unidad);
                ?>
                <?php 
                  if ($size == 1){
                    $width = '60%';
                  }elseif ($size == 2) {
                    $width = '30%';
                  }elseif ($size == 3) {
                    $width = '20%';
                  }elseif ($size == 4) {
                    $width = '15%';
                  }elseif ($size == 5) {
                    $width = '12%';
                  }
                /*FIN VALIDACION TAMAÑO CELDAS UNIDADES*/
                ?>
                  <?php for ($z=1; $z <=count($unidad); $z++) { ?>
                  <td width="<?=$width?>">
                    <a href="#U<?=$z?>" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.unidad') ?> <?=$z?>">U<?=$z?></span></a>
                    <div data-role="popup" id="U<?=$z?>"><p><?php echo lang('global.unidad') ?> <?=$z?></p></div>  
                  </td>
                  <?php } ?>
                  <td width="20%">
                    <a href="#tsem" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.total_minutos_reales_semanales') ?>">SEM</span></a>
                    <div data-role="popup" id="tsem"><p><?php echo lang('global.total_minutos_reales_semanales') ?></p></div>  
                  </td>
                  <td width="20%">
                    <a href="#racm" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_minutos_reales') ?>">acm</span></a>
                    <div data-role="popup" id="racm"><p><?php echo lang('global.acumulado_minutos_reales') ?></p></div>  
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%">
                    <a href="#rtsem" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.minutos_proyectados_semanal') ?> - <?php echo lang('global.total_minutos_reales_semanales') ?>">SEM</span></a>
                    <div data-role="popup" id="rtsem"><p><?php echo lang('global.minutos_proyectados_semanal') ?> - <?php echo lang('global.total_minutos_reales_semanales') ?></p></div>  
                  </td>
                  <td width="50%">
                    <a href="#rtacm" data-rel="popup" class="linkTooltip"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_proyecto_semanal') ?> – <?php echo lang('global.acumulado_minutos_reales') ?>">acum</span></a>
                    <div data-role="popup" id="rtacm"><p><?php echo lang('global.acumulado_proyecto_semanal') ?> – <?php echo lang('global.acumulado_minutos_reales') ?></p></div>  
                  </td>
                </tr>
              </table>
            </td>
            <td>
                <table class="secondary">
                  <tr>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_proyectadas') ?>">ESC PROY</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.escenas_producidas_semanales') ?>">ACUM ESC</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_reales_prod') ?>">ESC PROD</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_reales_prod') ?>">ACM ESC PROD</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.dif_proyec_produ') ?>">ESC DIF</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_diferencia_escenas') ?>">ACUM ESC MIN</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.variacion_de_escenas_equivalentes') ?>">MIN</td>
                   <td><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_de_variacion') ?>">ACM</td>
                  </tr>
                </table>  
               </td> 
            <td><?php echo lang('global.comentarios') ?></td>
          </tr>
        </thead>
        <tbody>
          <!--
          <tr class="gray">
            <td>SEMANA</td>
            <?php 
            /* $dias=((strtotime($produccion[0]->fin_grabacion) - strtotime($produccion[0]->inicio_PreProduccion)))/86400 ;
             $semanas = $dias/7;
             $semanas = floor($semanas);*/
            ?>
            <td>
              <table class="secondary">
                <tr>
                  <?php $j=1; ?>
                  <?php if ($produccion['0']->numero_unidades == 1){
                    $width = '100%';
                  }elseif ($produccion['0']->numero_unidades == 2) {
                    $width = '50%';
                  }elseif ($produccion['0']->numero_unidades == 3) {
                    $width = '33.33%';
                  }elseif ($produccion['0']->numero_unidades == 4) {
                    $width = '25%';
                  }elseif ($produccion['0']->numero_unidades == 5) {
                    $width = '20%';
                  } ?>
                    
                  <?php $m=0; while ($j<=$produccion['0']->numero_unidades) { ?>

                    <td width="<?php echo $width; ?>">
                      <span class="has-tip tip-centered-top" title="Unidad # 1">
                        <?php echo $unidad[$m]['numero']; ++$m;?>
                      </span>
                    </td>
                 <?php $j++; } ?>
                 </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="20%"><span class="has-tip tip-centered-top" title="Tooltip content">#D</span></td>
                  <td width="40%"><span class="has-tip tip-centered-top" title="Tooltip content">inicio</span></td>
                  <td width="40%"><span class="has-tip tip-centered-top" title="Tooltip content">fin</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">PRO</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">AC.P</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">ENT</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">AC.E</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">dIF</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">DES</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="Tooltip content">AC.D</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%"><span class="has-tip tip-centered-top" title="Tooltip content">SEM</span></td>
                  <td width="50%"><span class="has-tip tip-centered-top" title="Tooltip content">acm</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                <?php
                /*VALIDACION TAMAÑO CELDAS UNIDADES*/
                $size = count($unidad);
                ?>
                <?php 
                  if ($size == 1){
                    $width = '60%';
                  }elseif ($size == 2) {
                    $width = '30%';
                  }elseif ($size == 3) {
                    $width = '20%';
                  }elseif ($size == 4) {
                    $width = '15%';
                  }elseif ($size == 5) {
                    $width = '12%';
                  }
                /*FIN VALIDACION TAMAÑO CELDAS UNIDADES*/
                ?>
                  <?php for ($z=1; $z <=count($unidad); $z++) { ?>
                  <td width="<?=$width?>"><span class="has-tip tip-centered-top" title="Tooltip content">U<?=$z?></span></td>
                  <?php } ?>
                  <td width="20%"><span class="has-tip tip-centered-top" title="Tooltip content">SEM</span></td>
                  <td width="20%"><span class="has-tip tip-centered-top" title="Tooltip content">acm</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%"><span class="has-tip tip-centered-top" title="Tooltip content">SEM</span></td>
                  <td width="50%"><span class="has-tip tip-centered-top" title="Tooltip content">acum</span></td>
                </tr>
              </table>
            </td>
            <td><?php echo lang('global.comentarios') ?></td>
          </tr>
          -->
           <?php 
             $i=1;
             $cont=1;
             $cont_unida1=1;
             $cont_unida2=1;
             $cont_unida3=1;
             $cont_unida4=1;
             $cont_unida5=1;
             $cap_pro=0;
             $acu_entre=0;
             $acu_desg=0;
             $min_acu=0;
             $entro_u=0;
             $dif_min = 0;
             $k=0;

             $acu_min_real=0;
             $acu_seg_real=0;
             $dif_min_real=0;
             $acu_dif_min_real=0;
             $acu_dif_seg_real=0;
             $dif_esc=0;
             $acumtotal='00:00';
             $acum_escena_proyec=0;
             $total_acum_escena_producidas=0;
             $acum_esc_dif=0;
             $acum_minutos=0;



            ?>
           <?php while ($i<=$semanas) { ?>
           <?php  $cont=$i-1; ?>
           <?php 
               $semana_actual =strtotime(date('Y-m-d')); 
               $semana_aire =strtotime($produccion['0']->fecha_aire);
               $semana_inicio = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
               $semana_fin =   strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
               if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
                  $class='highltght_orange';
               } else if($semana_aire>=$semana_inicio and $semana_aire<=$semana_fin){
                  $class='highltght_green';
               } else{
                  $class='';
                  $mins=0;
               }
            ?>
              <tr class="<?php echo $class; ?>">
              <td class="td_weeknumber" nowrap="nowrap"><?php echo $i; ?></td>
              <td class="td_unit" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <?php $j=1 ?>
                    
                    <?php while ($j<=$produccion['0']->numero_unidades) { ?>
                    <?php 
                    $cont_p=$i-2;
                    $cont_u=$j-1; 
                    $fecha_inicio = strtotime($unidad[$cont_u]['fecha_inicio']);
                    $fecha_inicio_semana = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
                    $fecha_fin_semana=strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
                     ?>
                    <?php  if(($fecha_inicio<=$fecha_inicio_semana or $fecha_inicio<=$fecha_fin_semana) and $fecha_inicio!='') { ?>
                             <?php $k=1;if($j==1){ ?>
                             <td width="<?php echo $width; ?>"><?php echo $cont_unida1; ?></td>
                             <?php $cont_unida1++; ?>
                             <?php } ?>
                             <?php if($j==2){ ?>
                             <td width="<?php echo $width; ?>"><?php echo $cont_unida2; ?></td>
                             <?php $cont_unida2++; ?>
                             <?php } ?>
                             <?php if($j==3){ ?>
                             <td width="<?php echo $width; ?>"><?php echo $cont_unida3; ?></td>
                             <?php $cont_unida3++; ?>
                             <?php } ?>
                             <?php if($j==4){ ?>
                             <td width="<?php echo $width; ?>"><?php echo $cont_unida4; ?></td>
                             <?php $cont_unida4++; ?>
                             <?php } ?>
                             <?php if($j==5){ ?>
                             <td width="<?php echo $width; ?>"><?php echo $cont_unida5; ?></td>
                             <?php $cont_unida5++; ?>
                             <?php } ?>
                       <?php }else { ?>
                              <td width="<?php echo $width; ?>">-</td>
                       <?php } ?> 
                    <?php $j++; } ?>
                  </tr>
                </table>
              </td>
              <td class="td_week" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td  width="9.5%">
                      <?php echo $semanas_trabajo[$cont]['dias_trabajo'] ?>
                    </td>
                    <td  width="29.2%">
                      <!--<?php if($i==1){ ?>
                        <?= date("d-M-Y",strtotime($produccion[0]->inicio_PreProduccion)) ?>
                      <?php } else{ ?>-->
                      <!--<?php } ?>-->
                      <?= date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])) ?>  
                    </td>
                    <td  width="30%">
                      <?php  $fecha = date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_fin_semana']));
                      ?>
                      <?= $fecha ?>
                    </td>
                  </tr>
                </table>
              </td>
              <td class="td_write_cap" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td width="13.3%">
                      <?=$semanas_trabajo[$cont]['capitulos_programados']?>
                    </td>
                    <?php $cap_pro=$cap_pro+$semanas_trabajo[$cont]['capitulos_programados'] ?>
                    <td width="13.4%"><?php echo $cap_pro ?></td>
                    <td width="14.1%" class="hightltght_yellow">
                    <?php
                      $contador=0;
                      $contador_desg=0;
                      foreach ($capitulos as $capitulo) {
                        if(strtotime($capitulo->fecha_entregado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                          $contador++;
                        }
                        if(strtotime($capitulo->fecha_desglosado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_desglosado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                          $contador_desg++;
                        }
                      }
                      $acu_entre += $contador;
                      $acu_desg += $contador_desg;
                      $last_week_cap = 0;
                      echo $contador;
                    ?>

                    </td>
                    <td width="14.2%"><?php echo $acu_entre; ?></td>
                    <?php $last_week_cap = $dif_esc;
                      $dif_esc=$acu_entre-$cap_pro; ?>
                    <?php if($dif_esc<0){ 
                            $red='red';
                          }else{
                            $red='green';
                          }
                    ?>

                    <!--VALIDACIÓN PARA CUADRO DE MINUTOS-->
                    <?php if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {?>
                          <input type="hidden" id="difference_capitules_hidden" data-last-week="<?=$last_week_cap?>" value="<?=$dif_esc?>">
                    <?php } ?>
                    <!--FIN VALIDACIÓN PARA CUADRO DE MINUTOS-->

                    <td width="14.2%"><span class="<?php echo $red ?>"><?php echo $dif_esc ?></span></td>
                    <td width="14%"><?=$contador_desg;?></td>
                    <td width="14.4%"><?=$acu_desg;?></td>
                  </tr>
                </table>
              </td>
              <td class="td_mproy" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td width="50%">
                      <?php if($entro_u==0){ ?>
                          <?php 
                          $semana_inicio_unidad = strtotime($unidad[0]['fecha_inicio']);
                          if($k==1) {
                         ?></a>
                          <?php $entro_u=1; ; ?>
                          <?php echo $semanas_trabajo[$cont]['minutos_proyectados'] ?>
                          <?php $dif_min = -$semanas_trabajo[$cont]['minutos_proyectados'];?>
                          <?php }else{;
                            echo 0;
                            $min_acu = 0;
                            $dif_min = 0;
                          }   ?>
                       <?php }else{ ?>
                            <?php echo $semanas_trabajo[$cont]['minutos_proyectados'] ?>
                       <?php } ?>   
                      
                    </td>
                    <?php 
                    if($k!=1){
                      $min_acu=0; 
                    }else{
                    $min_acu=$min_acu+$semanas_trabajo[$cont]['minutos_proyectados'];
                    } 

                    ?>
                    <td width="50%"><?php echo $min_acu ?></td>
                  </tr>
                </table>
              </td>
              <td class="td_min_really" nowrap="nowrap">
                <!--ACUMULADO DE MINUTOS REALES-->
                <table class="secondary">
                  <tr>
                    <?php $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
                      $acum_escena_producidas=0;
                      for ($z=0; $z < count($unidad); $z++) { ;?>
                      <?php $acu_min_uni=0;$acu_seg_uni=0;
                            $tiempo=$this->model_escenas_2->escenas_producidas_produccion2($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana'],$unidad[$z]['id']);
                            if($tiempo){
                                $acum_escena_producidas=$acum_escena_producidas+$tiempo['0']->total_escenas;
                                $acu_min_uni+=$tiempo['0']->duracion_real_minutos_total;
                                $acu_seg_uni+=$tiempo['0']->duracion_real_segudos_total;
                                $acu_min_uni_sem+=$tiempo['0']->duracion_real_minutos_total;
                                $acu_seg_uni_sem+=$tiempo['0']->duracion_real_segudos_total;
                                $acu_min_real+=$tiempo['0']->duracion_real_minutos_total;
                                $acu_seg_real+=$tiempo['0']->duracion_real_segudos_total;
                            }else{
                                $acu_min_uni+=0;
                                $acu_seg_uni+=0;
                                $acu_min_uni_sem+=0;
                                $acu_seg_uni_sem+=0;
                                $acu_min_real+=0;   
                                $acu_seg_real+=0;

                            }
                            
                             ?>
                      
                      <td width="<?=$width?>"><?=calculo_tiempo($acu_min_uni,$acu_seg_uni)?></td>
                      <?php } ?> 
                      <?php  $total_acum_escena_producidas=$total_acum_escena_producidas+$acum_escena_producidas; ?> 
                      <td width="20%" class="hightltght_yellow"><?=$dif_min_real=calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem)?></td>
                      <td width="20%"><?=calculo_tiempo($acu_min_real,$acu_seg_real)?></td>                 
                  </tr>
                </table>
                <!--FIN ACUMULADO DE MINUTOS REALES-->
              </td>
              <td class="td_dif" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td width="50%">
                      
                    <?php 
                    $last_week_time=$acumtotal;
                    $classe="";
                    $min_real = explode(':',$dif_min_real);
                    if($k==1){
                      $segundos_real = $min_real[0]*60;
                      $segundos_real = $segundos_real+$min_real[1];
                      $segundos_sem_tra=$semanas_trabajo[$cont]['minutos_proyectados']*60;
                      $total=$segundos_real-$segundos_sem_tra;
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
                        if($segundos_2>=0 and $segundos_2<10){
                          $segundos_2='0'.$segundos_2;
                        }
                        if($m_total>=0 and $m_total<10){
                          $m_total='0'.$m_total;
                        }
                        if($m_total>-10 and $m_total<0){
                          $m_total='0'.abs($m_total);
                          $m_total='-'.$m_total;
                        }
                        $m_total_dif=$m_total.':'.$segundos_2;
                     }else{
                         $m_total_dif = $min_real[0].':'.$min_real[1];
                      }
                    $acu_dif_min_real+=$dif_min_real;
                    $acu_dif_seg_real+=$min_real[1];
                    ?>
                    <?php

                      $sem=explode(':',$acumtotal);
                      $acum=explode(':',$m_total_dif);
                      $min1=$sem[0]*60;
                      $min2=$acum[0]*60;
                      if($min1>=0){
                         $seg1=$min1+$sem[1];
                      }else{
                         $seg1=$min1-$sem[1];
                      }
                      if($min2>=0){
                        $seg2=$min2+$acum[1];  
                      }else{
                        $seg2=$min2-$acum[1];
                      }
                      
                      $acumtotal=$seg2+$seg1;
                      $color_cell = explode(':',$acumtotal);
                      if($color_cell[0]<0){
                        $classe="red";
                      }else{
                        $classe="green";
                      }

                      $color_cell_2 = explode(':',$m_total_dif);
                      if($color_cell[0]<0){
                        $color_cell_2="red";
                      }else{
                        $color_cell_2="green";
                      }

                      $acumtotal=tiempo_segundos($acumtotal);

                    ?><span class="<?=$color_cell_2?>"><?=$m_total_dif?></span>
                    </td>
                    <td width="50%"><span class="<?=$classe?>"><?=$acumtotal?></span>
                      <?php if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) { ?>
                          <input type="hidden" id="difference_minutes_hidden" data-last-week="<?=$last_week_time?>" value="<?=$acumtotal?>">
                    <?php }  ?>
                    </td>
                  </tr>
                </table>
              </td>
               <td class="td_pronostico" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td width="76px">
                      <?php if($produccion['0']->minuto_capitulo and $produccion['0']->minuto_capitulo!=0 and $produccion['0']->minuto_capitulo!=''
                        and $produccion['0']->escenas_libretos and $produccion['0']->escenas_libretos!=0 and $produccion['0']->escenas_libretos!=''){
                          $escena_porminuto=$produccion['0']->minuto_capitulo/$produccion['0']->escenas_libretos;
                       }else{
                         $escena_porminuto=0;  
                       }  ?>
                      <?php 
                       if($entro_u==1){ 
                          if($semanas_trabajo[$cont]['minutos_proyectados']!=0 and $escena_porminuto!=0){
                          echo round($semanas_trabajo[$cont]['minutos_proyectados']/$escena_porminuto);
                          $count_escenas_proyec=round($semanas_trabajo[$cont]['minutos_proyectados']/$escena_porminuto);
                          $acum_escena_proyec=$acum_escena_proyec+$count_escenas_proyec;
                         }else{
                          $count_escenas_proyec=0;
                          echo '0';
                         }
                       }  
                        ?>
                    </td>
                    <td width="80px">
                     <?php if($entro_u==1){ 
                           echo $acum_escena_proyec;
                         }
                        ?>
                    </td>
                    <td width="79px">
                     <?php if($entro_u==1){ 
                           echo $acum_escena_producidas;
                         }
                        ?>
                    </td>
                    <td width="118px">
                     <?php if($entro_u==1){ 
                           echo $total_acum_escena_producidas;
                         }
                        ?>
                    </td>
                    <td width="60px">
                     <?php if($entro_u==1){ 
                           echo $esc_dif=$acum_escena_producidas-$count_escenas_proyec;
                         }
                        ?>
                    </td>
                    <td width="113px">
                     <?php if($entro_u==1){ 
                           echo $acum_esc_dif=$acum_esc_dif+$esc_dif;
                         }
                        ?>
                    </td>
                    <td width="28px">
                     <?php if($entro_u==1){ 
                           echo round($esc_dif*$escena_porminuto);
                           $acum_minutos=$acum_minutos+round($esc_dif*$escena_porminuto);
                         }
                        ?>
                    </td>
                    <td width="37px">
                     <?php if($entro_u==1){ 
                           echo $acum_minutos;
                         }
                        ?>
                    </td>
                  </tr>
                </table>    
              </td>
              <td class="td_comments" nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td>
                      <?php if($semanas_trabajo[$cont]['comentario'] == ""){?>
                      <?php echo lang('global.comentar') ?> 
                      <?php } else{?>
                      <?php echo Plan_produccion::corta_palabra($semanas_trabajo[$cont]['comentario'],15);
                      if(strlen($semanas_trabajo[$cont]['comentario'])>=15){
                        echo '...';
                      }; ?>
                      <?php } ?>
                    </td>
                  </tr>
                </table>
                
              </td>
            </tr>
            <?php $i++; } ?>
        </tbody>
      </table>
    </div>
  </div> 
</div>


<?php 

  function calculo_tiempo($minutos,$segundos){
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


<?php 

  function calculo_tiempo_negativo($minutos,$segundos){
    $minutos2=0;
    $segundos2= 60-$segundos;
   
      while($segundos<=60 AND $segundos>0){
          $minutos+=1;

          $segundos= $segundos-60;
      }

      if($segundos2<60){
        $segundos = abs($segundos2);
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
      /*if($segundos_2>=0 and $segundos_2<10){
        $segundos_2='0'.$segundos_2;
      }*/
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