<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.capitulos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<nav>
    <ul class="nav_post">
        <li><a href="<?php echo base_url($idioma.'post_produccion/caja_colores/'.$id_produccion) ?>"><?php echo lang('global.contorl_produccion') ?></a></li>
        <li><a href="#" class="filtar_capitulo"><?php echo lang('global.filtrar') ?></a></li>
        <?php if($permisos=="write" and $produccion['0']->estado!=2){ ?>
          <li><a href="#" class="nuevo_capitulo"><?php echo lang('global.nuevo_capitulo') ?></a></li>
        <?php } ?>
        <li><a href="<?php echo base_url($idioma.'excel/post_produccion_capitulos/'.$id_produccion) ?>" class=""><?php echo lang('global.guardar') ?></a></li>
        <li><a href="<?php echo base_url($idioma.'pdf2/pdf_postproduccion_capitulos/'.$id_produccion) ?>" target="_blank" class=""><?php echo lang('global.imprimir') ?></a></li>
        <li class="ver_convensiones"><a href="#"><?php echo lang('global.caja_convenciones') ?></a></li>
    </ul>
</nav>

<input type="hidden" value="<?=$produccion['0']->id_produccion?>" id="idproduccion">
<div data-role="content" id="inner_content"> 
    <div class="content_acction_menu" style="display:none">
      <div class="add_capitulo" style="display:none">
         <span class="closeIcon"></span>
          <div style="padding:19px 12px; display:inline-block; width:100%;">
            <h5><?php echo lang('global.numero_capitulo') ?></h5>  
            <input type="text" value="" class="numero_capitulo" style="width:222px; float:left; margin:0 6px 0 0;height:30px;" autofocus>
            <input type="hidden" value="<?php echo $id_produccion ?>" class="id_produccion">    
            <div class="cancel cancelar_crear_capitulo  button"><?php echo lang('global.cancelar') ?></div>
            <div class="crear_capitulo  button"><?php echo lang('global.crear_capitulo') ?></div>
           </div> 
        </div>   
         <!-- SECCCION FILTRO CAPITULOS ESTADO -->
         <div class="filtros_capitulo" style="display:none;padding:19px 12px; ">
          <div class="checksFilterPost" >
            <?php if(isset($_COOKIE['estados_libretos_post'])){
                $temp_state = explode(',', $_COOKIE['estados_libretos_post']);
                foreach ($estados as $estado) {  
                  $valida = true;
                  for ($s=0; $s < count($temp_state)-1; $s++) { 
                    if($temp_state[$s]==$estado->id){ 
                      $valida = false; 
                      break;
                    } 
                  } ?>
                  <div class="checker">
                    <?php if($valida){?>
                      <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox" checked class="capitule_state_post" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                    <?php }else{ ?>
                      <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox" class="capitule_state_post" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                    <?php } ?>
                  </div>
              <?php  }
               } else { ?>
              <?php foreach ($estados as $estado) { ?>
                <div class="checker">
                  <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox"  checked class="capitule_state_post" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          <div>
           <div class="cancel cancelar_filtro_capitulo button"><?php echo lang('global.cerrar') ?></div>
         </div>
      </div>
        <!-- FIN SECCCION FILTRO CAPITULOS ESTADO -->



  

      <div class="cuadro_convensiones" style="display:none">
          <div class="div_convensiones">
            <div class="list_convensiones">
              <ul>
                <!--<li><span class="convension no_prod"></span>No Producido</li>-->
                <li><span class="convension log_ing"></span><?php echo lang('global.ingestando') ?></li>
                <li><span class="convension pre_edi"></span><?php echo lang('global.pre_editando') ?></li>
                <li><span class="convension edi"></span><?php echo lang('global.editando') ?></li>
                <li><span class="convension fin"></span><?php echo lang('global.finalizando') ?></li>
                <li><span class="convension cod_app_vid"></span><?php echo lang('global.codificando_video') ?></li>
                <li><span class="convension qc_rti_tec"></span><?php echo lang('global.qc_tecnico') ?></li>
                <li><span class="convension qc_rti_por"></span><?php echo lang('global.qc_productor') ?></li>
              </ul>
              <ul>
                <li><span class="convension mon_arc_lto"></span><?php echo lang('global.montnado_lto') ?></li>
                <li><span class="convension cod_cli"></span><?php echo lang('global.codificando_cliente') ?></li>
                <li><span class="convension env_cli"></span><?php echo lang('global.enviando_cliente') ?></li>
                <li><span class="convension qc_cli"></span><?php echo lang('global.qc_cliente') ?></li>
                <li><span class="convension arc_cap"></span><?php echo lang('global.sesion_protools') ?></li>
                <li><span class="convension mon_edl_ps"></span><?php echo lang('global.montando_ps') ?></li>
                <li><span class="convension cap_ent"></span><?php echo lang('global.capitulo_entregado') ?></li>
                <!--<li><span class="convension cap_cancel"></span>Capitulo Entregado</li>-->
              </ul>
              
            </div>
            <input type="button" value="<?php echo lang('global.cerrar') ?>" class="button cancel cerrar_convensiones">
          </div>
        </div>

      </div>
    <div class="row row_table_post_prod">
      <div class="twelve columns">
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
            <tr>
              <td><p><?php echo lang('global.min_producidos') ?>: <span class="right"><strong><?php echo Post_produccion::calculo_tiempo2($capitulos_escenas_prod['0']->minutos,$capitulos_escenas_prod['0']->segundos); ?></span></p></td>
            </tr>
        </table>
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
            <tr>
              <td><p><?php echo lang('global.min_post_producidos') ?>: <span class="right"><strong><?php echo Post_produccion::calculo_tiempo_post($capitulos_escenas_post['0']->minutos,$capitulos_escenas_post['0']->segundos,$capitulos_escenas_post['0']->cuadros); ?></span></p></td>
            </tr>
        </table>
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
            <tr>
                <?php $minutos=$capitulos_escenas_prod['0']->minutos-$capitulos_escenas_post['0']->minutos;
                      $segundos=$capitulos_escenas_prod['0']->segundos-$capitulos_escenas_post['0']->segundos;
                      if($minutos<0 or $segundos<0){
                         $negativo='-';
                      }else{
                          $negativo='';
                      } 
                      if($minutos<0){
                        $minutos=$minutos*-1;
                      }
                      if($segundos<0){
                        $segundos=$segundos*-1;
                      }
                ?>
              <td><p><?php echo lang('global.diferencia_minutos') ?>: <span class="right"><strong><?php echo $negativo ?><?php echo Post_produccion::calculo_tiempo2($minutos,$segundos); ?></strong></span></p></td>
            </tr>
        </table>
      <!--/div>
      <div class="large-4 columns"-->
      <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><?php echo lang('global.escenas_pro') ?>: <span class="right"><strong><?php echo $capitulos_escenas_prod['0']->total_escenas; ?></strong></span></p></td>
        </tr>
      </table>
       <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><?php echo lang('global.escenas_post') ?>: <span class="right"><strong><?php echo $capitulos_escenas_post['0']->total_escenas; ?></strong></span></p></td>
        </tr>
      </table>
       <table class="table_post_prod table_post_prod_min"  cellpadding="0" cellspacing="0">
        <tr>
          <td><p><?php echo lang('global.diferecia_esc') ?>: <span class="right"><strong><?php echo $capitulos_escenas_prod['0']->total_escenas-$capitulos_escenas_post['0']->total_escenas; ?></strong></span></p></td>
        </tr>
      </table>
    <!--/div>
    <div class="large-3 columns"-->
      <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><?php echo lang('global.capitulos_editados') ?>: <span class="right"><strong><?=$capitulos_editados?></strong></span></p></td>
        </tr>
      </table>
    </div>
    </div>
    <?php if($capitulos_user){ ?>
    <div class="cap_confirmar">TIENES <?php echo $capitulos_user[0]->total ?> CAPÍTULOS POR CONFIRMAR</div>
    <?php } ?>



   
    <div class="clr"></div>
    <?php if($capitulos){ ?>
    <input type="hidden" id="limite_capitulos" value="<?=count($capitulos)-1?>">

    <div id="scroll">
    <div class="table_general">
      <table class="main" id="" cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <td class="td_info_cap"><?php echo lang('global.info_capitulo') ?></td>
            <td class="td_time_cap"><?php echo lang('global.tiempo_capitulo') ?></td>
            <td class="td_diff_esc"><?php echo lang('global.diferencia') ?></td>
            <td class="td_workflow"><?php echo lang('global.workflow') ?></td>
          </td>
        </thead>
        <tbody>
           <tr class="gray">
            <td>
              <table class="secondary">
                <tbody>
                  <tr>
                    <td width="10%" class="has-tip tip-centered-top" title="<?php echo lang('global.numero_capitulo') ?>">CAP</td>
                    <td width="50%" class="has-tip tip-centered-top" title="<?php echo lang('global.estatus') ?>">ESTATUS</td>
                    <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escenas') ?>">NRO. ESC</td>
                    <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.libreto_') ?>">LIB</td>
                  </tr>
                </tbody>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tbody>
                  <tr>
                    <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.estimado_') ?>">EST</td>
                    <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.real_') ?>">REAL</td>
                    <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.post_') ?>">POST</td>
                    <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.ext_') ?>">EXT</td>
                    <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.post_') ?> + <?php echo lang('global.ext_') ?>"><?php echo lang('global.total') ?></td>
                  </tr>
                </tbody>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tbody>
                  <tr>
                    <td class="has-tip tip-centered-top" title="TIEMPO REAL * TIEMPO POST-PRODUCCION"><?php echo lang('global.diferencia') ?></td>
                  </tr>
                </tbody>
               </table>    
            </td>
            <td>
              <table class="secondary">
                <tbody>
                  <tr>
                    <td width="25%" class="has-tip tip-centered-top" title="<?php echo lang('global.responsable') ?>"><?php echo lang('global.responsable') ?></td>
                    <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.entrega') ?>"><?php echo lang('global.entrega') ?></td>
                    <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.entregada_') ?>"><?php echo lang('global.entregada_') ?></td>
                    <td width="42%" class="has-tip tip-centered-top" title="<?php echo lang('global.acciones') ?>"><?php echo lang('global.acciones') ?></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
        <tbody class="tr_capitulos">
        <?php $cont_cap=1; ?>
         <?php foreach ($capitulos as $c) { ?>
             <?php
             if ($cont_cap%2==0){
                  $back= "#fff";
              }else{
                  $back= "#e6e4e4!important;";
              }
              ++$cont_cap;
              if($c['id_user']){
                $back='#fed8a0';
              } 
              ?>
           <?php
                $estado = explode(',', $c['estado']);
              // echo $c['estado'].'---'.print_r($estado).'***';
                $campos_estado="";
                for ($i=0; $i < count($estado); $i++) { 
                //  echo print_r($estado[$i]);
                  //echo $estado[$i].'-------';
                  $estado_base = explode('_', $estado[$i]);
                  //echo print_r($estado_base);
                  switch($estado_base[1]){
                    case 'No producido':
                                      $class_cap="no_prod";
                                      $campos_estado .="<div class='no_prod'>".$estado_base[1]."</div>";
                                    break;
                                    case 'LOGGING/INGESTANDO':
                                      $class_cap="log_ing";
                                      $campos_estado .="<div class='log_ing'>".$estado_base[1]."</div>";
                                    break;
                                    case 'PRE-EDITANDO':
                                      $class_cap="pre_edi";
                                      $campos_estado .="<div class='pre_edi'>".$estado_base[1]."</div>";
                                    break;
                                    case 'EDITANDO':
                                      $class_cap="edi";
                                      $campos_estado .="<div class='edi'>".$estado_base[1]."</div>";
                                    break;
                                    case 'FINALIZANDO':
                                      $class_cap="fin";
                                      $campos_estado .="<div class='fin'>".$estado_base[1]."</div>";
                                    break;
                                    case 'CODIFICANDO APP VIDEO':
                                      $class_cap="cod_app_vid";
                                      $campos_estado .="<div class='cod_app_vid'>".$estado_base[1]."</div>";
                                    break;
                                    case 'QC RTI TECNICO':
                                      $class_cap="qc_rti_tec";
                                      $campos_estado .="<div class='qc_rti_tec'>".$estado_base[1]."</div>";
                                    break;
                                    case 'QC RTI PRODUCTOR':
                                      $class_cap="qc_rti_por";
                                      $campos_estado .="<div class='qc_rti_por'>".$estado_base[1]."</div>";
                                    break;
                                    case 'MONTANDO ARCHIVO LTO':
                                      $class_cap="mon_arc_lto";
                                      $campos_estado .="<div class='mon_arc_lto'>".$estado_base[1]."</div>";
                                    break;
                                    case 'CODIFICANDO A CLIENTE':
                                      $class_cap="cod_cli";
                                      $campos_estado .="<div class='cod_cli'>".$estado_base[1]."</div>";
                                    break;
                                    case 'ENVIANDO A CLIENTE':
                                      $class_cap="env_cli";
                                      $campos_estado .="<div class='env_cli'>".$estado_base[1]."</div>";
                                    break;
                                    case 'QC CLIENTE':
                                      $class_cap="qc_cli";
                                      $campos_estado .="<div class='qc_cli'>".$estado_base[1]."</div>";
                                    break;
                                    case 'SESION DE PROTOOLS':
                                      $class_cap="arc_cap";
                                      $campos_estado .="<div class='arc_cap'>".$estado_base[1]."</div>";
                                    break;
                                    case 'MONTANDO EDL PS':
                                      $class_cap="mon_edl_ps";
                                      $campos_estado .="<div class='mon_edl_ps'>".$estado_base[1]."</div>";
                                    break;
                                    case 'CAPITULO ENTREGADO':
                                      $class_cap="cap_ent";
                                      $campos_estado .="<div class='cap_ent'>".$estado_base[1]."</div>";
                                    break;
                                    case 'CANCELADO':
                                      $class_cap="cap_cancel";
                                      $campos_estado .="<div class='cap_cancel'>".$estado_base[1]."</div>";
                                    break;
                  }
                }
                 
            ?>
       
          <tr class="capitulo_<?php echo $c['id']  ?>" style="background:<?php echo $back ?>">
            <td>
              <table class="secondary">
                <tr>
                  <td width="10%"><?php echo anchor($idioma.'post_produccion/capitulo/'.$c['id_produccion'].'/'.$c['id'],$c['numero']) ?></td>
                  <td width="50%" class="estado_cap_<?php echo $c['id'] ?> ver_vitacora estatus" data-idcapitulo="<?php echo $c['id'] ?>">
                    <?php echo $campos_estado ?>
                  </td>
                  <td width="15%" class="ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $c['total_escenas'] ?></td>
                  <?php $libretos_capitulo=$this->model_post_produccion->libretos_capitulo($c['id']); ?>
                  <td width="15%" class="ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>">

                    <?php if($libretos_capitulo){ ?>
                     <?php $tam=sizeof($libretos_capitulo);$cont=1;
                    $lib=''; ?>
                     <?php foreach ($libretos_capitulo as $l) { ?>
                              <?php if($cont==$tam){ ?>
                                 <?php $lib.=$l['numero']?>
                              <?php }else{ ?>
                                 <?php $lib.=$l['numero'].'-'; ?>
                              <?php } ?>
                              <?php $cont++; ?>
                      <?php } ?>
                      <span style="font-weight: normal;width:300px;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip cell_align_left tooltip_info libretos_tooltip" title="<?php echo $lib;?>">
                       <?php $tam=sizeof($libretos_capitulo);$cont=1;
                    ?>
                     <?php foreach ($libretos_capitulo as $l) { ?>
                              <?php if($cont==$tam){ ?>
                                 <?php echo $l['numero']?>
                              <?php }else{ ?>
                                 <?php  echo $l['numero'].'-'; ?>
                              <?php } ?>
                              <?php $cont++; ?>
                      <?php } ?>
                       
                      </span>
                    <?php }else{ ?>
                    <?php echo '-'; ?>
                    <?php } ?>


                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <?php $tiempo_estimados=Post_produccion::calculo_tiempo2($c['minutos_estimados'],$c['segundos_estimados']) ?>
                  <td width="20%" class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $tiempo_estimados ?></td>
                  <?php $tiempo_real=Post_produccion::calculo_tiempo2($c['minutos'],$c['segundos']) ?>
                  <td width="20%" class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $tiempo_real ?></td>
                  <?php $tiempo_post=Post_produccion::calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']) ?>
                  <td width="20%" class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $tiempo_post ?></td>
                  <?php $m_extra=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['foto_minutos']+$c['imagenes_archivos_minutos'];
                        $s_extra=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['foto_segundos']+$c['imagenes_archivos_segundos'];
                        $c_extra=$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros']; ?>

                  <?php $tiempo_extra=Post_produccion::calculo_tiempo_post($m_extra,$s_extra,$c_extra) ?>
                  <td width="20%" class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?=$tiempo_extra?></td>
                  <?php 
                      $m=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['foto_minutos']+$c['imagenes_archivos_minutos'];
                      $s=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['foto_segundos']+$c['imagenes_archivos_segundos'];
                  ?>
                  <?php $total=Post_produccion::calculo_tiempo_post_redondeo($c['minutos_post']+$m,$c['segundos_post']+$s,$c['cuadros']+$c_extra) ?>
                  <td width="20%" class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $total; ?></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                 <?php $c_m=explode(':', $tiempo_post);
                      $c_s=explode('-', $c_m['1']);

                      
                      if($c_s['1']>15){
                        $segundos_post_total=$c_s['0']+1;
                      }else{
                        $segundos_post_total=$c_s['0'];
                      }
                  ?>
                  <?php $diferencia=Post_produccion::calculo_tiempo2($c['minutos']-$c_m['0'],$c['segundos']-$segundos_post_total) ?>
                  <td class="ver_vitacora align_center" data-idcapitulo="<?php echo $c['id'] ?>"><?=$diferencia ?></td>
                </tr>
               </table>
            </td>    
            <td>
              <table class="secondary">
                <tr>
                   <td width="25%" class="responsable_cap_<?php echo $c['id'] ?> ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $c['responsable'] ?></td>
                        <?php //$fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id_produccion,$c['numero']); 
                             $fecha_entrega=$c['fecha_entrega']; 
                              if($fecha_entrega){
                                if($fecha_entrega!=null and $fecha_entrega!='' AND $fecha_entrega!='0000-00-00'){
                                   $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega));  
                                }else{
                                  $fecha_entrega='-';   
                                }
                              }else{
                                $fecha_entrega='-'; 
                              } 
                         ?>
                  <td width="15%" class="" data-idcapitulo="<?php echo $c['id'] ?>">
                      <?php $permitir=0;
                      foreach ($estado as $e) {
                        if($e<15){
                            $permitir=1;
                        }
                      } ?>
                      <?php if($permitir==1 and $produccion['0']->estado!=2){ ?>
                        <a href="#" class="chage_date" data-id="<?php echo $c['id'] ?>" onclick="return false;"><?php echo $fecha_entrega;  ?></a>
                      <?php }else{ ?>
                          <?php echo $fecha_entrega;  ?>
                      <?php } ?>
                    
                      <div class="fecha_entrega<?php echo $c['id'] ?> hide_box tiempos_pos_box">
                           <span class="close_box"></span>
                           <div style="width:100%; height:40px">
                             <input type="text" placeholder="DD-MM-YYYY" value="<?=$fecha_entrega?>" name="fecha_post" class="datepicker fecha_post_<?=$c['id'] ?>">
                           </div>
                            <div class="align_left">
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                              <a class="save_icon_fecha" idcapitulo="<?=$c['id'] ?>" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$c['id']?>" ><?php echo lang('global.guardar') ?></a>
                            </div>
                       </div>
                   </td>
                        <?php if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
                                $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
                              }else{
                                $fecha_entregada='-'; 
                              } 
                         ?>
                  <td width="15%" class="ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>"><?php echo $fecha_entregada; ?></td>
                    
                  <?php 
                      $estado_val = "";
                      $estado = explode(',', $c['id_estado']);
                      $campos_estado="";
                      if($this->session->userdata('tipo_pruduction_suite')==1){
                        $edita = true;
                      }else{
                        $edita = false;
                      }
                      
                      for ($i=0; $i < count($estado); $i++) {


                        if(!$edita){
                          $this->model_post_produccion->valida_edicion_capitulo($estado_base[0],$tipo_rol);
                          if($this->model_post_produccion->valida_edicion_capitulo($estado_base[0],$tipo_rol)){
                            $edita=true;

                          }
                        } 

                        $estado_base = explode('_', $estado[$i]);
                        if($estado_base[0]=='6' AND $tipo_rol=='9'){
                          $estado_val = $estado_base[0];
                          break;
                        }else if($estado_base[0]=='7' AND $tipo_rol=='14'){
                          $estado_val = $estado_base[0];
                          break;
                        }elseif(count($estado)>=2 AND $this->session->userdata('tipo_pruduction_suite')==1){
                           $estado_val=$estado[0].','.$estado[1];
                           break;
                        }else{
                          $estado_val = $estado_base[0];
                        }
                      }
                  ?>
                  <?php if($estado_val!=16){ ?>
                  <?php $usuario_roles = $this->model_produccion->roles_usuario_produccion($id_user,$produccion[0]->id_produccion);
                    if($usuario_roles){
                        foreach ($usuario_roles as $usuario_rol) {
                          if($usuario_rol['id_rol']==13){
                            $edita = true;

                            break;
                          }
                        }
                    }
                    
                   ?>
                    <?php if(($edita OR $this->session->userdata('tipo_pruduction_suite')==1) and $produccion['0']->estado!=2){ ?>
                      <td width="42%" class="" data-idcapitulo="<?php echo $c['id'] ?>">
                        <?php $est=explode(',', $estado_val);
                        if(count($est)>=2){ ?>
                              <a href="#" class="confirmar_capitulo_doble"  data-totalescenas="<?php echo $c['total_escenas'] ?>" data-idestado="<?php echo $est['0'] ?>" data-idestadodos="<?php echo $est['1'] ?>" data-capitulo="<?php echo $c['numero'] ?>" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.confirmar') ?></a>/
                        <?php }elseif($est['0']==15){ ?>
                        <?php }else{ ?>
                          <a href="#" class="confirmar_capitulo" data-totalescenas="<?php echo $c['total_escenas'] ?>" data-idestado="<?php echo $est['0'] ?>" data-capitulo="<?php echo $c['numero'] ?>" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.confirmar') ?></a>/
                        <?php } ?>
                        <?php $est=explode(',', $estado_val);
                        if(count($est)>=2){ ?>
                            <a href="#" class="rechazar_capitulo_doble" data-idestado="<?php echo $est['0'] ?>" data-idestadodos="<?php echo $est['1'] ?>" data-capitulo="<?php echo $c['numero'] ?>" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.rechazar') ?></a>/
                        <?php }elseif($est['0']==9){ ?>
                              <a href="#" class="rechazar_capitulo" data-capitulo="<?php echo $c['numero'] ?>" data-idestado="9" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.rechazar') ?></a>/
                        <?php }elseif($est['0']==2){ ?>
                        <?php }else{ ?>
                          <a href="#" class="rechazar_capitulo"  data-capitulo="<?php echo $c['numero'] ?>" data-idestado="<?php echo $est['0'] ?>" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.rechazar') ?></a>/
                        <?php } ?>
                        
                        <?php if($c['total_escenas']<=0 and $ultimo_capitulo==$c['id']){ ?>
                         <a href="#" class="eliminar_capitulo" data-capitulo="<?=$c['numero']?>" data-idestado="<?php echo $estado_val ?>" data-idcapitulo="<?php echo $c['id'] ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.eliminar') ?></a>

                        <?php } ?>
                      </td>
                      <?php }else{ ?>
                      <td width="42%" class="ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>">
                        <a href="#" onclick="return false;"><?php echo lang('global.ver') ?></a>
                      </td>
                    <?php } ?>
                  <?php }else{ ?>
                      <td width="42%" class="ver_vitacora" data-idcapitulo="<?php echo $c['id'] ?>">

                      </td>
                  <?php } ?>
                </tr>
              </table>
            </td>
          </tr>
          <tr class="vitacora2_<?php echo $c['id'] ?>" style="display:none; padding:0;">
              <td colspan="4">
                <div class="vitacora_<?php echo $c['id'] ?> table_post_prod_gen"  style="display:none; padding:11px 11px 0px 11px; border:none"></div>
              </td>
          </tr>
         <?php } ?>
        </tbody>        
      </table>
    </div>
      <div>
        <?php if(count($capitulos)-1<=$total_capitulos){ ?>
          <input type="button" style="width:100%;" id="load_capitulos" value="<?php echo lang('global.ver_mas_capitulos') ?>" class="button" data-idprod="<?php echo $produccion['0']->id_produccion ?>">
        <?php } ?>
      </div>
    </div>
    <?php }else{ ?>
      <div id="scroll">
        <div class="table_general">
          <table class="main" id="" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <td class="td_info_cap"><?php echo lang('global.info_capitulo') ?></td>
                <td class="td_time_cap"><?php echo lang('global.tiempo_capitulo') ?></td>
                <td class="td_diff_esc"><?php echo lang('global.diferencia') ?></td>
                <td class="td_workflow"><?php echo lang('global.workflow') ?></td>
              </td>
            </thead>
            <tbody>
               <tr class="gray">
                <td>
                  <table class="secondary">
                    <tbody>
                      <tr>
                        <td width="10%" class="has-tip tip-centered-top" title="<?php echo lang('global.numero_capitulo') ?>">CAP</td>
                        <td width="50%" class="has-tip tip-centered-top" title="<?php echo lang('global.estatus') ?>">ESTATUS</td>
                        <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escenas') ?>">NRO. ESC</td>
                        <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.libreto_') ?>">LIB</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td>
                  <table class="secondary">
                    <tbody>
                      <tr>
                        <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.estimado_') ?>">EST</td>
                        <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.real_') ?>">REAL</td>
                        <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.post_') ?>">POST</td>
                        <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.ext_') ?>">EXT</td>
                        <td width="20%" class="has-tip tip-centered-top" title="<?php echo lang('global.post_') ?> + <?php echo lang('global.ext_') ?>"><?php echo lang('global.total') ?></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td>
                  <table class="secondary">
                    <tbody>
                      <tr>
                        <td class="has-tip tip-centered-top" title="<?php echo lang('global.post_') ?> * <?php echo lang('global.ext_') ?>"><?php echo lang('global.diferencia') ?></td>
                      </tr>
                    </tbody>
                   </table>    
                </td>
                <td>
                  <table class="secondary">
                    <tbody>
                      <tr>
                        <td width="25%" class="has-tip tip-centered-top" title="<?php echo lang('global.responsable') ?>"><?php echo lang('global.responsable') ?></td>
                        <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.entrega') ?>"><?php echo lang('global.entrega') ?></td>
                        <td width="15%" class="has-tip tip-centered-top" title="<?php echo lang('global.entregada_') ?>"><?php echo lang('global.entregada_') ?></td>
                        <td width="42%" class="has-tip tip-centered-top" title="<?php echo lang('global.acciones') ?>"><?php echo lang('global.acciones') ?></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
            <tbody class="tr_capitulos">
            </tbody>
          </table>
          </div>
         </div> 
    <?php } ?>
</div>

</div>  

