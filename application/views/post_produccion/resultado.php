<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.capitulos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<nav>
        <ul class="nav_post">
            <li><a href="<?php echo base_url($idioma.'post_produccion/seleccionar_archivo/'.$produccion['0']->id_produccion.'/'.$capitulo['0']->id) ?>" class="active"><?php echo lang('global.volver') ?></a></li>
        </ul>
    </nav>
<div data-role="content" id="inner_content"> 
<?php echo form_open($idioma.'post_produccion/guardar_datos_archivo','id="carga_archivo", class=""') ?>
    
    <table cellpadding="0" cellspacing="0" class="cap_est">
            <?php 
                $estado = explode(',', $capitulo['0']->estado);
                $campos_estado="";
                $valida_agregar=0;
               // $estado=array();
                for ($i=0; $i < count($estado); $i++) { 
                  $estado_base = explode('_', $estado[$i]);
                  switch($estado_base[1]){
                    case 'No producido':
                      $class_cap="no_prod";
                      $campos_estado .="<div class='no_prod'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='1';
                    break;
                    case 'LOGGING/INGESTANDO':
                      $class_cap="log_ing";
                      $campos_estado .="<div class='log_ing'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='2';
                    break;
                    case 'PRE-EDITANDO':
                      $class_cap="pre_edi";
                      $campos_estado .="<div class='pre_edi'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='3';
                    break;
                    case 'EDITANDO':
                      $class_cap="edi";
                      $campos_estado .="<div class='edi'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='4';
                    break;
                    case 'FINALIZANDO':
                      $class_cap="fin";
                      $campos_estado .="<div class='fin'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='5';
                    break;
                    case 'CODIFICANDO APP VIDEO':
                      $class_cap="cod_app_vid";
                      $campos_estado .="<div class='cod_app_vid'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='6';
                    break;
                    case 'QC RTI TECNICO':
                      $class_cap="qc_rti_tec";
                      $campos_estado .="<div class='qc_rti_tec'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='7';
                    break;
                    case 'QC RTI PRODUCTOR':
                      $class_cap="qc_rti_por";
                      $campos_estado .="<div class='qc_rti_por'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='8';
                    break;
                    case 'MONTANDO ARCHIVO LTO':
                      $class_cap="mon_arc_lto";
                      $campos_estado .="<div class='mon_arc_lto'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='9';
                    break;
                    case 'CODIFICANDO A CLIENTE':
                      $class_cap="cod_cli";
                      $campos_estado .="<div class='cod_cli'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='10';
                    break;
                    case 'ENVIANDO A CLIENTE':
                      $class_cap="env_cli";
                      $campos_estado .="<div class='env_cli'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='11';
                    break;
                    case 'QC CLIENTE':
                      $class_cap="qc_cli";
                      $campos_estado .="<div class='qc_cli'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='12';
                    break;
                    case 'SESION DE PROTOOLS':
                      $class_cap="arc_cap";
                      $campos_estado .="<div class='arc_cap'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='13';
                    break;
                    case 'MONTANDO EDL PS':
                      $class_cap="mon_edl_ps";
                      $campos_estado .="<div class='mon_edl_ps'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='14';
                      $valida_agregar=1;
                    break;
                    case 'CAPITULO ENTREGADO':
                      $class_cap="cap_ent";
                      $campos_estado .="<div class='cap_ent'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='15';
                    break;
                    case 'CANCELADO':
                      $class_cap="cap_cancel";
                      $campos_estado .="<div class='cap_cancel'>ESTATUS: ".$estado_base[1]."</div>";
                      $estado[$i]='16';
                    break;
                  }
                }
        ?>
      <tr class="<?php echo $class_cap; ?>">
        <td><?php echo lang('global.capitulos') ?>: <?php echo $capitulo['0']->numero; ?></td>
        <td><?php echo $campos_estado ?></td>
        <td><?php echo lang('global.libretos') ?>:</td>
        <td><?php echo lang('global.responsable') ?>: <?php echo $capitulo['0']->responsable ?></td>
        <?php $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id_produccion,$capitulo['0']->numero); 
              if($fecha_entrega){
                $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));
              }else{
                $fecha_entrega='-'; 
              } 
         ?>
        <td><?php echo lang('global.entrega_') ?>: <?php echo $fecha_entrega; ?></td>
        <?php if($capitulo['0']->fecha_entregada and $capitulo['0']->fecha_entregada!=null and $capitulo['0']->fecha_entregada!='' AND $capitulo['0']->fecha_entregada!='0000-00-00'){
                $fecha_entregada=date("d-M-Y",strtotime($capitulo['0']->fecha_entregada));
              }else{
                $fecha_entregada='-'; 
              } 
         ?>
        <td><?php echo lang('global.entregada_') ?>:<?php echo $fecha_entregada ?> </td>
      </tr>
    </table>
    <div class="clr"></div>
    <ul class="accordion up_files">
      <h5>&nbsp;&nbsp;<?php echo lang('global.resultado_archivo') ?>: <?php echo  $clips; ?> : <?php echo $tiempo_total ?></h5>
      
      <li>
        <div class="title"><?php echo lang('global.registro_leidos_total') ?>: <?php echo $tiempo_final ?></div>
        <div class="content">
          <?php $cont=0; $cont2=0; ?>
          
          <?php foreach ($datos_final as $l) { ?>
              <div class="regs_file escena_<?php echo $cont2 ?>">
                <div class="esc_lib">
                  <span>
                    <?php echo lang('global.libretos').': '.$l['Libreto']; ?>
                  </span>
                  <span>
                    <?php echo lang('global.escenas').': '.$l['escena']; ?>
                  </span>
                </div>
                <div class="tiempos_escenas">
                    <?php $tiempo=explode(':',$l['tiempo']);
                    $cuadros=$tiempo[3];
                    if($tiempo[3]>=30){
                      while($tiempo[3]>=30){
                          $tiempo[2]=$tiempo[2]+1;
                          $tiempo[3]=$tiempo[3]-30;
                      }
                      $cuadros=$tiempo[3];
                    }
                    if(strlen($cuadros)<2){
                      $cuadros = '0'.$cuadros;
                    }
                    if($tiempo[2]<10 and (strlen($tiempo[2])<=1)){
                      $tiempo[2]='0'.$tiempo[2];
                    }
                    if($l['id_escena']==0){ ?>
                      <span><h6>Esta escena no existe en esta producción</h6></span>
                    <?php }else{ ?>
                       <?php if($l['existe_capitulo']!=1){ ?>
                           <span>
                             <?php echo lang('global.tiempo') ?>: 
                           </span>
                           <input type='hidden' name="escena[<?php echo $cont ?>]" value="<?php echo $l['id_escena'] ?>">
                           <input type="text" name="tiempo_escena_minutos[<?php echo $cont ?>]" value="<?php echo $tiempo["1"] ?>" class="required">
                           <span> : </span>
                           <input type="text" name="tiempo_escena_segundos[<?php echo $cont ?>]" value="<?php echo $tiempo["2"] ?>" class="required segundos">
                           <span> - </span>
                           <input type="text" name="tiempo_escena_cuadros[<?php echo $cont ?>]" value="<?php echo $cuadros ?>" class="required cuadros">
                          <?php $cont++; ?>
                      <?php }elseif($l['existe_capitulo']==1){ ?>
                      <span><h6><?php echo lang('global.esta_escena_ya_esta_asignada_a_este_capitulo') ?></h6></span>
                      <?php } ?>
                    <?php } ?>
                    
                </div>
                <div class="esc_clips">
                  <span><?php echo lang('global.numero_clips') ?>: <?php echo $l['numero_escena'] ?></span>
                </div>

                  <?php //echo //' Libretos: '.$l['Libreto'].' Escena: '.$l['escena'].' Tiempo: '.$l['tiempo'].' Tiempo redondedeado: '.$tiempo[0].':'.$tiempo[1].':'.$tiempo[2].' Numero de clips: '.$l['numero_escena'].'<br>';  ?>
                  <div class="eliminar_escena" data-datos="<?php echo $l['Libreto'].'/'.$l['escena'] ?>" data-iddiv="<?php echo $cont2 ?>"><?php echo lang('global.eliminar') ?></div>
                  <?php $cont2++ ?>
               </div> 
          <?php  } ?>
        </div>
      </li>

      <li>
        <div class="title"><?php echo lang('global.registro_leidos_varios_total') ?>: <?php echo $tiempo_varios ?></div>
        <div class="content">
          <?php $cont_mult=0;$cont_mult_eliminar=0; ?>
          <?php foreach ($escena_final_varias as $l) { 
            $tiempo=explode(':',$l['tiempo']);
            if($tiempo[3]>=30){
                while($tiempo[3]>=30){
                    $tiempo[2]=$tiempo[2]+1;
                    $tiempo[3]=$tiempo[3]-30;
                }
                $cuadros=$tiempo[3];
              }

              if(strlen($cuadros)<2){
                $cuadros = '0'.$cuadros;
              }

             if(strlen($tiempo[2])<2){
              $tiempo[2]='0'.$tiempo[2];
             }
            ?>
            <div class="regs_file escenas_milti_<?=$cont_mult_eliminar?> ">
                <div class="esc_tam">
                  <input class="escenas_tam_<?php echo $l['Libreto'] ?>" type="hidden" value="<?php echo $cont2 ?>">
                  <span class="lib"><?php echo lang('global.libretos').': '.$l['Libreto'] ?></span>
                  <span class="tie"><?php echo lang('global.tiempo').': '.Post_produccion::calculo_tiempo_post($tiempo[1],$tiempo[2],$cuadros); ?></span>
                </div>
                <div class="esc_datos">
                  <?php
                  if($l['escenas']){
                    $cont=1;
                    $cont2=0;
                         foreach ($l['escenas'] as $e) {

                              if($e['id_escena']!=0){ ?>
                               <?php if($e['existe']==0){ ?>
                                     <div style="padding: 0 26px 0 0;display: inline-block;float: left;position:relative;">
                                         <input type='hidden' name="escena_multi[<?php echo $cont_mult ?>]" value="<?php echo $e['id_escena'] ?>">
                                         <span class="span_lib">LIB.<?php echo $e['libreto'] ?> - ESC. <?php echo $e['escena_numero'] ?>:</span>
                                         <input type="text" data-class="<?php echo $l['Libreto'] ?>" data-minutos="<?php echo $tiempo["1"] ?>" data-segundos="<?php echo $tiempo["2"] ?>" class="escena_minutos_<?php echo $l['Libreto'] ?>_<?php echo $cont2 ?> tiempo" name="escena_multi_minutos[<?php echo $cont_mult?>]"  >
                                         <span>:</span>
                                         <input type="text" data-class="<?php echo $l['Libreto'] ?>" data-minutos="<?php echo $tiempo["1"] ?>" data-segundos="<?php echo $tiempo["2"] ?>" class="segundos escena_segundos_<?php echo $l['Libreto'] ?>_<?php echo $cont2 ?> tiempo" name="escena_multi_segundos[<?php echo $cont_mult ?>]" >
                                         <span>-</span>
                                         <input type="text" data-class="<?php echo $l['Libreto'] ?>" data-minutos="<?php echo $tiempo["1"] ?>" data-segundos="<?php echo $tiempo["2"] ?>" class="cuadros escena_cuadros_<?php echo $l['Libreto'] ?>_<?php echo $cont2 ?> tiempo" name="escena_multi_cuadros[<?php echo $cont_mult ?>]" >

                                     </div>
                                     <?php $cont_mult++; $cont2++?>
                                <?php }else{ ?>
                                      <span><h6><?php echo lang('global.escenas') ?> <?php echo $e['libreto'].'/'.$e['escena_numero'] ?> <?php echo lang('global.esta_asignada_otro_capitulo') ?></h6></span>
                                <?php } ?>     
                               <?php }else{
                                 echo '<span class="span_esc_nx"><h6>'.lang('global.escenas').' '.$e['libreto'].'/'.$e['escena_numero'].' '.lang('global.no_existe').'</h6></span>';
                               }
                           $cont++; ?>

                         <?php } ?>
                  <?php }else{ ?>   
                  <?php echo '<span><h6>'.lang('global.esta_escena_no_existe').'</h6></span>'; ?>
                  <?php } ?>
                </div>
              <div class="esc_clips">
                    <span><?php echo lang('global.numero_clips') ?>: <?php echo $l['numero_escena'] ?></span>
              </div>
              <div class="eliminar_escena_multiple" data-datos="<?php echo $l['Libreto'] ?>" data-iddiv="<?=$cont_mult_eliminar ?>"><?php echo lang('global.eliminar') ?></div>
              <?php $cont_mult_eliminar++ ?>
            </div>
            <?php //echo 'Libretos: '.$l['Libreto'].' Tiempo: '.$l['tiempo'].' Tiempo redondedeado: '.$tiempo[0].':'.$tiempo[1].':'.$tiempo[2].' Numero de clips: '.$l['numero_escena'].'<br>';
              } 
            ?>
          <input type="hidden" name="id_capitulo" value="<?php echo $id_capitulo ?>">
          <input type="hidden" name="id_produccion" value="<?php echo $id_produccion ?>">
          
          
        </div>
      </li>

      <li>
        <div class="title"><?php echo lang('global.creditos') ?> Total: <?php echo $total_creditos_final; ?></div>
        <div class="content">
          
          <?php if($datos_cred){?>
                 <?php $minutos=0;$segundos=0; $cuadros=0;?>
                 <?php foreach ($total_cred_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                    $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file cred_div">
                    <div class="tiempos_escenas ">
                        <div class="columns two"><label><?php echo lang('global.creditos') ?></label></div>
                        <div class="columns ten">
                          <span>Total:</span>
                          <input type="text" name="credito_minutos" value="<?=$tiempo['0'] ?>" class="required cred">
                          <input type="text" name="credito_segundos" value="<?=$tiempo['1'] ?>" class="segundos required cred">
                          <span>-</span>
                          <input type="text" name="credito_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required cred">
                        </div>
                     </div>
                     <div class="eliminar_creditos" data-datos="Creditos" data-iddiv="cred"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
             <div class="regs_file cred_div">
               <div class="tiempos_escenas ">
                 <div class="columns two">
                   <label><?php echo lang('global.creditos') ?></label>
                 </div>
                 <div class="columns ten">
                   <?php echo lang('global.nodatos') ?>
                   <input type="hidden" name="credito_minutos" value="0">
                   <input type="hidden" name="credito_segundos" value="0">
                   <input type="hidden" name="credito_cuadros" value="0">
                 </div>
               </div>
             </div>
          <?php } ?>
          
          <?php if($total_flah_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_flah_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                  <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                   $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file flash_div">
                    <div class="tiempos_escenas ">
                      <div class="columns one"><label>Flashback</label></div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="flashback_minutos" value="<?=$tiempo['0'] ?>" class="required flash">
                        <input type="text" name="flashback_segundos" value="<?=$tiempo['1'] ?>" class="segundos required flash">
                         <span>-</span>
                        <input type="text" name="flashback_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required flash">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Flashback" data-iddiv="flash"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
              <div class="regs_file flash_div">
                <div class="tiempos_escenas ">
                  <div class="columns two">
                    <label>Flashback</label>
                  </div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="flashback_minutos" value="0">
                    <input type="hidden" name="flashback_segundos" value="0">
                    <input type="hidden" name="flashback_cuadros" value="0">
                  </div>
                </div>
              </div>
          <?php } ?>
         
          <?php if($total_timelaps_fin){?>
              <?php $minutos=0;$segundos=0;$cuadros=0; ?>
             <?php foreach ($total_timelaps_fin as $d) {
                $tiempo=explode(':',$d['tiempo']);
                if($tiempo[3]>=15){
                  $tiempo[2]=$tiempo[2]+1;
                }
                $minutos+=$tiempo[1];
                $segundos+=$tiempo[2];
                $cuadros+=$tiempo[3];
             } ?>
             <?php 
             $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
               /* echo '<div class="regs_file"><div class="esc_lib">';
                echo 'Total : '.$total.'<br>';
                echo '</div></div>';*/
               $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file tran_div">
                    <div class="tiempos_escenas ">
                       <div class="columns two">
                         <label><?php echo lang('global.transaciones') ?></label>
                       </div>
                       <div class="columns ten">
                         <span>Total:</span>
                         <input type="text" name="transiciones_minutos" value="<?=$tiempo['0'] ?>" class="required tran">
                         <input type="text" name="transiciones_segundos" value="<?=$tiempo['1'] ?>" class="segundos required tran">
                          <span>-</span>
                         <input type="text" name="transiciones_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required tran">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Transiciones" data-iddiv="tran"><?php echo lang('global.eliminar') ?></div>
                  </div>

          <?php }else{ ?>
            <div class="regs_file tran_div">
              <div class="tiempos_escenas ">
                <div class="columns two">
                  <label><?php echo lang('global.transaciones') ?></label>
                </div>
                <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="transiciones_minutos" value="0">
                    <input type="hidden" name="transiciones_segundos" value="0"> 
                    <input type="hidden" name="transiciones_cuadros" value="0">
                </div>
              </div>
            </div>
          <?php } ?>
          
          <?php if($total_cortinas_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_cortinas_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php 
                 $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file cort_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two">
                        <label><?php echo lang('global.cortinillas') ?></label>
                      </div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="cortinillas_minutos" value="<?=$tiempo['0'] ?>" class="required cort">
                        <input type="text" name="cortinillas_segundos" value="<?=$tiempo['1'] ?>" class="segundos required cort">
                         <span>-</span>
                        <input type="text" name="cortinillas_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required cort">
                      </div>
                     </div>
                     <div class="eliminar_creditos" data-datos="Cortinillas" data-iddiv="cort"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
            <div class="regs_file cort_div">
              <div class="tiempos_escenas ">
                <div class="columns two">
                  <label><?php echo lang('global.cortinillas') ?></label>
                </div>
                <div class="columns ten">
                  <?php echo lang('global.nodatos') ?>
                  <input type="hidden" name="cortinillas_minutos" value="0">
                  <input type="hidden" name="cortinillas_segundos" value="0"> 
                  <input type="hidden" name="cortinillas_cuadros" value="0">
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if($total_cabe_fin){?>
                 <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_cabe_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file cabe_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two">
                        <label><?php echo lang('global.cabezote') ?></label>
                      </div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="cabezote_minutos" value="<?=$tiempo['0'] ?>" class="required cabe">
                        <input type="text" name="cabezote_segundos" value="<?=$tiempo['1'] ?>" class="segundos required cabe">
                         <span>-</span>
                        <input type="text" name="cabezote_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required cabe">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Cabezotes" data-iddiv="cabe"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
            <div class="regs_file cabe_div">
               <div class="tiempos_escenas ">
                 <div class="columns two">
                    <label><?php echo lang('global.cabezote') ?></label>
                  </div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="cabezote_minutos" value="0">
                    <input type="hidden" name="cabezote_segundos" value="0">  
                    <input type="hidden" name="cabezote_cuadros" value="0">
                  </div>
               </div>
            </div>
          <?php } ?>
           
          <?php if($total_recap_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_recap_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file recap_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two"><label class="label_cred_edl">Recap</label></div>
                      <div class="columns ten">
                      <span>Total:</span>
                      <input type="text" name="recap_minutos" value="<?=$tiempo['0'] ?>" class="required recap">
                      <input type="text" name="recap_segundos" value="<?=$tiempo['1'] ?>" class="segundos required recap">
                       <span>-</span>
                      <input type="text" name="recap_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required recap">
                      </div>
                     </div>
                     <div class="eliminar_creditos" data-datos="Recap" data-iddiv="recap"><?php echo lang('global.eliminar') ?></div>
                  </div>      
          <?php }else{ ?>
             <div class="regs_file recap_div">
                <div class="tiempos_escenas ">
                  <div class="columns two"><label>Recap</label></div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="recap_minutos" value="0">
                    <input type="hidden" name="recap_segundos" value="0">
                    <input type="hidden" name="recap_cuadros" value="0">
                </div>
              </div>
            </div>
          <?php } ?>
          
          <?php if($total_fach_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_fach_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file facha_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two">
                        <label>Stab</label>
                      </div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="stab_minutos" value="<?=$tiempo['0'] ?>" class="required facha">
                        <input type="text" name="stab_segundos" value="<?=$tiempo['1'] ?>" class="segundos required facha">
                         <span>-</span>
                        <input type="text" name="stab_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required facha">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Fachadas" data-iddiv="facha"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
             <div class="regs_file facha_div">
                <div class="tiempos_escenas ">
                  <div class="columns two">
                    <label>Stab</label>
                  </div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="stab_minutos" value="0">  
                    <input type="hidden" name="stab_segundos" value="0"> 
                    <input type="hidden" name="stab_cuadros" value="0">
                  </div>
                </div>
              </div>
          <?php } ?>
           
          <?php if($total_despedias_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_despedias_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file despe_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two">
                        <label><?php echo lang('global.despedida') ?></label>
                      </div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="despedida_minutos" value="<?=$tiempo['0'] ?>" class="required despe">
                        <input type="text" name="despedida_segundos" value="<?=$tiempo['1'] ?>" class="segundos required despe">
                         <span>-</span>
                        <input type="text" name="despedida_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required despe">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Despedida" data-iddiv="despe"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
              <div class="regs_file despe_div">
                <div class="tiempos_escenas ">
                  <div class="columns two">
                    <label><?php echo lang('global.despedida') ?></label>
                  </div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="despedida_minutos" value="0">
                    <input type="hidden" name="despedida_segundos" value="0">
                    <input type="hidden" name="despedida_cuadros" value="0">
                  </div>
                </div>
              </div>
          <?php } ?>
         
          <?php if($total_presentacion_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_presentacion_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file prese_div">
                    <div class="tiempos_escenas ">
                      <div class="columns two">
                        <label><?php echo lang('global.presentacion') ?></label>
                      </div>
                      <div class="columns ten">
                        <span>Total:</span>
                        <input type="text" name="presentacion_minutos" value="<?=$tiempo['0'] ?>" class="required prese">
                        <input type="text" name="presentacion_segundos" value="<?=$tiempo['1'] ?>" class="segundos required prese">
                         <span>-</span>
                        <input type="text" name="presentacion_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required prese">
                      </div>
                    </div>
                    <div class="eliminar_creditos" data-datos="Presentacion" data-iddiv="prese"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
              <div class="regs_file prese_div">
                <div class="tiempos_escenas ">
                  <div class="columns two">
                    <label><?php echo lang('global.presentacion') ?></label>
                  </div>
                  <div class="columns ten">
                    <?php echo lang('global.nodatos') ?>
                    <input type="hidden" name="presentacion_minutos" value="0">
                    <input type="hidden" name="presentacion_segundos" value="0"> 
                    <input type="hidden" name="presentacion_cuadros" value="0">
                  </div>
                </div>
              </div>
          <?php } ?>
         
          <?php if($total_foto_fin){?>
          <?php $minutos=0;$segundos=0;$cuadros=0; ?>
                 <?php foreach ($total_foto_fin as $d) {
                    $tiempo=explode(':',$d['tiempo']);
                    /*if($tiempo[3]>=15){
                      $tiempo[2]=$tiempo[2]+1;
                    }*/
                    $minutos+=$tiempo[1];
                    $segundos+=$tiempo[2];
                    $cuadros+=$tiempo[3];
                 } ?>
                 <?php  $total=Post_produccion::calculo_tiempo_post2($minutos,$segundos,$cuadros);
                    /* echo '<div class="regs_file"><div class="esc_lib">';
                    echo 'Total : '.$total.'<br>';
                    echo '</div></div>';*/
                  $tiempo=explode(':',$total);
                  ?>
                  <div class="regs_file foto_div">
                    <div class="tiempos_escenas ">
                    <div class="columns one">
                      <label>FotoClip</label>
                    </div>
                    <div class="columns ten">
                      <span>Total:</span>
                      <input type="text" name="foto_minutos" value="<?=$tiempo['0'] ?>" class="required foto">
                      <input type="text" name="foto_segundos" value="<?=$tiempo['1'] ?>" class="segundos required foto">
                       <span>-</span>
                      <input type="text" name="foto_cuadros" value="<?=$tiempo['2'] ?>" class="cuadros required foto">
                    </div>
                   </div>
                   <div class="eliminar_creditos" data-datos="FotoClip" data-iddiv="foto"><?php echo lang('global.eliminar') ?></div>
                  </div>    
          <?php }else{ ?>
             <div class="regs_file foto_div">
                <div class="tiempos_escenas ">
                <div class="columns one">
                  <label>FotoClip</label>
                </div>
                <div class="columns ten">
                  <?php echo lang('global.nodatos') ?>
                  <input type="hidden" name="foto_minutos" value="0">
                  <input type="hidden" name="foto_segundos" value="0"> 
                  <input type="hidden" name="foto_cuadros" value="0">
                </div>
              </div>
            </div>
          <?php } ?>
          
        </div>
      </li>

      <li>
        <div class="title"><?php echo lang('global.archivos_no_leidos') ?> <?php echo count($no_leidos_final);?></div>
        <div class="content">
          <?php if($no_leidos_final){ ?>
              <?php foreach ($no_leidos_final as $n) {
                echo '<div class="regs_file"><div class="esc_lib no_reads">';
                echo '<span style="width:250px;padding:0 20px 0 0;">'.lang('global.archivo').': '.$n['libreto'].'</span><span style="width:100px;">'.$n['tiempo'].'</span><div class="clr"></div>';  
                echo '</div></div>';
                } 
              ?>
           <?php }else{
               echo '<div class="regs_file"><div class="esc_lib no_reads">';
                echo '<span style="width:250px;padding:0 20px 0 0;">No hay registro</span><div class="clr"></div>';  
                echo '</div></div>';
           } ?>   
        </div>
      </li>

    </ul>
    <div class="guardar">
            <input type="submit" value="<?php echo lang('global.guardar') ?>" class="button" />
          </div>
      <?php echo form_close(); ?>
</div>