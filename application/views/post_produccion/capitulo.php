<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.capitulos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<nav>

    <ul class="nav_post">
        <li><a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion) ?>" class="active"><?php echo lang('global.volver') ?></a></li>
        <?php //if($capitulo['0']->id_estado>=5){ ?>
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
                      $valida_agregar=1;
                      
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
        <?php $permitir=0;
        foreach ($estado as $e) {
             if($e==14 or $e==5){
             $permitir=1;
            }
        } ?>
        <?php if($permitir==1 and $produccion['0']->estado!=2){ ?>
        <li><a href="#" class="add_lib"><?php echo lang('global.asignar_escenas') ?></a></li>
        <?php } ?>
         <?php if(!$escenas){ ?>
             <?php $permitir=0;
              foreach ($estado as $e) {
                   if($e==14 or $e==5){
                   $permitir=1;
                  }
              } ?>
          <?php if($permitir==1 and $produccion['0']->estado!=2){ ?>
            <?php if($permisos=="write"){ ?>
               <li><a href="<?php echo base_url($idioma.'post_produccion/seleccionar_archivo/'.$produccion['0']->id_produccion.'/'.$id_capitulo) ?>" class=""> <?php echo lang('global.subir_archivo') ?></a></li>
            <?php } ?>
          <?php } ?>
        <?php } ?>
        <?php $permitir=0;
              foreach ($estado as $e) {
                   if($e>=5 and $e<15){
                   $permitir=1;
                  }
              } ?>
              
        <?php  if($permitir==1 and $produccion['0']->estado!=2){ ?>
        <li><a href="#" class="add_tiempo_extra"><?php echo lang('global.agregar_timpo_extra') ?></a></li>
        <?php } ?>
        <?php if($escenas){ ?>
        <?php $permitir=0;
              foreach ($estado as $e) {
                   if($e<15){
                   $permitir=1;
                  }
              } ?>
            <?php if($permisos=="write" and $permitir==1 and $produccion['0']->estado!=2){ ?>
              <li><a href="#" class="add_tiempo_mult"><?php echo lang('global.tiempo_multiple') ?></a></li>
            <?php } ?>  
        <?php } ?>
        <?php $user_capitulo=$this->model_post_produccion->user_capitulo($id_capitulo,$id_user) ?>
        <?php if($user_capitulo){ ?>
          <li><a href="#" class="confirmar_capitulo2" data-idestado="<?php echo $capitulo['0']->id_estado ?>" data-idcapitulo="<?php echo $id_capitulo ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.confirmar') ?></a></li>
          <li><a href="#" class="rechazar_capitulo2" data-idestado="<?php echo $capitulo['0']->id_estado ?>" data-idcapitulo="<?php echo $id_capitulo ?>" data-iduser="<?php echo $id_user ?>"><?php echo lang('global.rechazar') ?></a></li>
        <?php }?>
        <!-- <li style="width:20%;"><a href="">&nbsp;</a></li> -->
        <li><a href="<?php echo base_url($idioma.'excel/post_produccion_escenas/'.$produccion['0']->id_produccion.'/'.$id_capitulo) ?>"><?php echo lang('global.guardar') ?></a></li>
        <li><a href="<?php echo base_url($idioma.'pdf2/pdf_postproduccion_escenas/'.$produccion['0']->id_produccion.'/'.$id_capitulo) ?>" class="" target="_blank"><?php echo lang('global.imprimir') ?></a></li>
        <li class="ver_convensiones"><a href="#"><?php echo lang('global.caja_convenciones') ?></a></li>
        

    </ul>
</nav>

<div class="content_acction_menu" style="display:none">
  <div class="agregar_tiempo_extra" style="display:none">
      <div style="padding:26px 12px; display:inline-block; width:50%">
      <div class="row tiempo_extra ultimo_tiempo">
        <div class="column six" >
          <label><?php echo lang('global.agregar_timpo_extra') ?>:</label>
           <select class="detalle" data-valor="1">
             <option value="<?php echo lang('global.credito') ?>"><?php echo lang('global.credito') ?></option>
             <option value="flashback">FLASHBACK</option>
             <option value="transiciones"><?php echo lang('global.transaciones') ?></option>
             <option value="cortinillas"><?php echo lang('global.cortinillas') ?></option>
             <option value="cabezote"><?php echo lang('global.cabezote') ?></option>
             <option value="recap">RECAP</option>
             <option value="stab">STAB</option>
             <option value="<?php echo lang('global.despedida') ?>"><?php echo lang('global.despedida') ?></option>
             <option value="<?php echo lang('global.presentacion') ?>"><?php echo lang('global.presentacion') ?></option>
             <option value="foto">FOTOCLIP</option>
             <option value="imagenes_archivos"><?php echo lang('global.imagenes_archivo') ?></option>
           </select> 
        </div>
        <div class="column six">
           <label><?php echo lang('global.tiempo') ?></label>
           <div class="column twelve">
              <label style="width:16px; float:left; margin:9px 6px 0 0;">MM</label><input data-tipo="credito_minutos" type="text" name="minutos" placeholder="MM" class="minutos_1 time onlynumbers_cont" value="<?php if($capitulo['0']->credito_minutos==null){ echo '00';}elseif($capitulo['0']->credito_minutos<10){ echo '0'.$capitulo['0']->credito_minutos; }else{ echo $capitulo['0']->credito_minutos;} ?>" style="width:40px;float:left;margin:0 6px 0 0;" autofocus>
              <label style="width:16px; float:left; margin:9px 6px 0 0;">SS</label><input data-tipo="credito_segundos" type="text" name="segundos" placeholder="SS"class="segundos_1 segundos time onlynumbers_cont" value="<?php if($capitulo['0']->credito_segundos==null){ echo '00';}elseif($capitulo['0']->credito_segundos<10){ echo '0'.$capitulo['0']->credito_segundos; }else{ echo $capitulo['0']->credito_segundos;} ?>" style="width:40px;float:left;">
              <label style="width:16px; float:left; margin:9px 6px 0 0;">CC</label><input data-tipo="credito_cuadros" type="text" name="cuadros" placeholder="CC"class="cuadros_1 cuadros time onlynumbers_cont" value="<?php if($capitulo['0']->credito_cuadros==null){ echo '00';}elseif($capitulo['0']->credito_cuadros<10){ echo '0'.$capitulo['0']->credito_cuadros; }else{ echo $capitulo['0']->credito_cuadros;} ?>" style="width:40px;float:left;">
              <div class="add_tiempos" data-cantidad="1"></div>
           </div> 
        </div>
    </div>
    <!-- Construccion Ajax -->
      <div style="display:none">Nuevo Input</div>
      <div style="display:none">Nuevo Input</div>
      <div style="display:none">Nuevo Input</div>
    <!-- -->
      <input type="hidden" class="id_capitulo" value="<?php echo $id_capitulo ?>">   
        <div class="cancel cancel_tiempo_extra button"><?php echo lang('global.cancelar') ?></div>
        <div class="save_detalle button"><?php echo lang('global.guardar_detalles') ?></div>
      </div>
  </div>  


  <?php  if($valida_agregar==1){ ?>
  <div class="agregar_escena" style="display:none">
    <h2><?php echo lang('global.agregar') ?> <?php echo lang('global.escenas') ?></h2>
    <div class="asignar_escenas">

    <?php echo form_open($idioma.'/post_produccion/escenas_capitulo') ?>
    <input type="hidden" class="cantidad_escenas" value="0">
    <table style="margin:0; width: 100%;">
      <tr>
        <td>
          <div class="agregar_esc ultimo">
            <div class="libretos_asignar">
              <label><?php echo lang('global.libretos') ?></label>
              <select id="libretos" class="libretos_cap" data-valor="0" >
                  <option value=""><?php echo lang('global.selecionar_libreto') ?></option>
                  <?php foreach ($libretos as $libreto) {?>
                      <option value="<?=$libreto['id_capitulo']?>"><?=$libreto['numero']?></option>
                  <?php } ?>
              </select>
              <div class="add_libretos" data-valor="0"></div>
            </div>
            <div class="escenas_asignar  valores_0">
              <label><?php echo lang('global.escenas') ?></label>
            
            </div>
          </div>
        </td>
        <td width="240" style="padding:10px 0; vertical-align:bottom;">
          <div><span class="convension cap_asginado">&nbsp;</span><?php echo lang('global.asignado_este_capitulo') ?></div>
          <div><span class="convension no_producido">&nbsp;</span><?php echo lang('global.escena_no_producida') ?></div>
          <input type="reset" value="<?php echo lang('global.cancelar') ?>" class="button cancel column btn_escena_cancelar">
          <input type="submit" value="<?php echo lang('global.guardar') ?>" class="button column btn_escena_guardar">
        </td>
      </tr>
    </table>
    <input type="hidden" class="id_produccion" name="id_produccion" value="<?php echo $id_produccion ?>">   
    <input type="hidden" name="id_capitulo" class="id_capitulo" value="<?php echo $id_capitulo ?>"> 
    <?php echo form_close() ?> 
    </div>
  </div>
  <?php } ?>
   <div class="cuadro_convensiones" style="display:none">
          <div class="div_convensiones">
            <div class="list_convensiones">
              <ul>
                <li><span class="convension no_prod"></span><?php echo lang('global.escena_no_producida') ?></li>
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
                <li><span class="convension cap_cancel"></span><?php echo lang('global.capitulo_entregado') ?></li>
              </ul>
              
            </div>
            <input type="button" value="<?php echo lang('global.cerrar') ?>" class="button cancel cerrar_convensiones">
          </div>
        </div>

</div>

    
<div id="agregarMultiplesWrap" class="tiempos_multiples">
  <div class="agregarMultiplesbox">
      <span class="closeIcon"></span>
      <div style="padding:15px 12px; overflow:auto; height:500px">
      <?php if($total_escenas_capitulos){ ?>
      <?php echo form_open($idioma.'post_produccion/guardar_tiempo_post'); ?>
        <table class="table_general">
          <thead>
            <tr>
              <td><?php echo lang('global.libretos') ?></td>
              <td><?php echo lang('global.escenas') ?></td>
              <td colspan="3"><?php echo lang('global.tiempo') ?></td>
            </tr>
          </thead>
          <tbody>

          <?php $cont=0; ?>
          <?php $cont2=0; ?>
              <?php foreach ($total_escenas_capitulos as $e) { ?>
                <tr>
                  <td><?= $e['numero'] ?></td>
                  <td><?= $e['numero_escena'] ?></td>
                  <input type="hidden" name="escena_<?=$cont2 ?>" value="<?=$e['id_escena'] ?>">    
                  <td>
                    <?php if(!$e['tiempo_post_minutos']){ ?>
                      <input type="text" name="minutos_<?=$cont2 ?>" class="minutos" placeholder="MM" value="" <?php if($cont==0){ ?> autofocus <?php $cont++; } ?> style="width:40px;float:left;margin-right:6px;">
                    <?php }else{ ?>
                        <?php if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)) { $minutos='0'.$e['tiempo_post_minutos']; }else{ $minutos=$e['tiempo_post_minutos'];} ?>
                        <input type="text" name="minutos_<?=$cont2 ?>" class="minutos" placeholder="MM" value="<?=$minutos ?>" <?php if($cont==0){ ?> autofocus <?php $cont++; } ?> style="width:40px;float:left;margin-right:6px;">
                    <?php } ?>
                    <label style="width:16px; float:left; margin:9px 6px 0 0;">MM</label>
                  </td>

                  <td>
                    <?php if(!$e['tiempo_post_segundos']){ ?>
                      <input type="text" name="segundos_<?=$cont2 ?>" class="segundos" placeholder="SS" value="" style="width:40px;float:left;margin-right:6px">
                    <?php }else{ ?>
                      <?php if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ $segundos='0'.$e['tiempo_post_segundos']; }else{ $segundos=$e['tiempo_post_segundos'];} ?>
                      <input type="text" name="segundos_<?=$cont2 ?>" class="segundos" placeholder="SS" value="<?=$segundos ?>" style="width:40px;float:left;margin-right:6px">
                    <?php } ?>
                    <label style="width:16px; float:left; margin:9px 6px 0 0;">SS</label>
                  </td>
                  <td>
                    <?php if(!$e['tiempo_post_cuadros']){ ?>
                      <input type="text" name="cuadros_<?=$cont2 ?>" class="cuadros" placeholder="CC" value="" style="width:40px;float:left;margin-right:6px">
                    <?php }else{ ?>
                      <?php if($e['tiempo_post_cuadros']==null){ $cuadros=='00'; }if($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ $cuadros='0'.$e['tiempo_post_cuadros']; }else{ $cuadros=$e['tiempo_post_cuadros'];} ?>
                      <input type="text" name="cuadros_<?=$cont2 ?>" class="cuadros" placeholder="CC" value="<?=$cuadros ?>" style="width:40px;float:left;margin-right:6px">
                    <?php } ?>
                    <label style="width:16px; float:left; margin:9px 6px 0 0;">CC</label>
                  </td>
                </tr>
                <?php $cont2++; ?>
                <?php } ?>
      
          </tbody>

        </table>
        
        <input type="hidden" class="id_capitulo" name ="id_capitulo" value="<?php echo $id_capitulo ?>">
        <input type="hidden" class="id_produccion" name ="id_produccion" value="<?php echo $id_produccion ?>"> 
        <input type="hidden" class="tam" name="tam" value="<?=$cont2 ?>">   
        
      
      <!--div tabindex="3" class="save_tiempo_multiple button" >GUARDAR</div-->
      <input type="submit" class="save_tiempo_multiple button" value="<?php echo lang('global.guardar') ?>">
      <?php echo form_close(); ?>
     <?php }else{ ?>   
         <span><?php echo lang('global.No_escenas') ?></span>
     <?php } ?>
      <input type="button" class="cancel_tiempo_multiple button cancel" value="<?php echo lang('global.cancelar') ?>">
      </div>
  </div>          
</div>

<?php echo $msg; ?>

<div data-role="content" id="inner_content"> 
    <table cellpadding="0" cellspacing="0" class="cap_est">
      <tr class="<?php echo $class_cap; ?>" stalye>
        <td class="<?php echo $class_cap; ?>"><?php echo lang('global.capitulos') ?>: <?php echo $capitulo['0']->numero; ?></td>
        <td class="estado_cap_<?php echo $id_capitulo ?> "><?php echo $campos_estado ?></td>
        <td class="<?php echo $class_cap; ?>"><?php echo lang('global.libretos') ?>: 
        <?php if($libretos_capitulo){ ?>
           <?php $tam=sizeof($libretos_capitulo);$cont=1; ?>
          <?php foreach ($libretos_capitulo as $l) { ?>
                <?php if($cont==$tam){ ?>
                   <?php echo $l['numero']?>
                <?php }else{ ?>
                   <?php echo $l['numero'].'-'; ?>
                <?php } ?>
                <?php $cont++; ?>
          <?php } ?>
        <?php } ?>
        </td>

        <td class="responsable_cap_<?php echo $id_capitulo ?> <?php echo $class_cap; ?>"><?php echo lang('global.responsable') ?>: <?php echo $capitulo['0']->responsable ?></td>
        <?php /*$fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id_produccion,$capitulo['0']->numero);*/ 
        $fecha_entrega=$capitulo['0']->fecha_entrega;
                      if($fecha_entrega and $fecha_entrega!=null and $fecha_entrega!='' AND $fecha_entrega!='0000-00-00'){
                        $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega));
                      }else{
                        $fecha_entrega='-'; 
                      } 
         ?>
        <td class="<?php echo $class_cap; ?>"><?php echo lang('global.entrega_') ?>: <?php echo $fecha_entrega; ?></td>
        <?php if($capitulo['0']->fecha_entregada and $capitulo['0']->fecha_entregada!=null and $capitulo['0']->fecha_entregada!='' AND $capitulo['0']->fecha_entregada!='0000-00-00'){
                $fecha_entregada=date("d-M-Y",strtotime($capitulo['0']->fecha_entregada));
              }else{
                $fecha_entregada='-'; 
              } 
         ?>
        <td class="<?php echo $class_cap; ?>"><?php echo lang('global.entregada_') ?>: <?php echo $fecha_entregada ?></td>
      </tr>
    </table>
    <div class="row">
      <div class="twelve columns" style="padding: 0 3px;">
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0" > 
          <tr>
            <td><p><span class="left"><?php echo lang('global.tiempo_estimado') ?>:</span><span class="right"><strong><?php echo Post_produccion::calculo_tiempo2($tiempo_est['0']->minutos,$tiempo_est['0']->segundos); ?></span></strong></p></td>
          </tr>
        </table>
         <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0" > 
          <tr>
            <td><p><span class="left"><?php echo lang('global.tiempo_real') ?>:</span> <span class="right"><strong><?php echo Post_produccion::calculo_tiempo2($tiempo_real['0']->minutos,$tiempo_real['0']->segundos); ?></span></strong></p></td>
          </tr>
        </table>
         <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0" > 
          <tr>
            <td><p><span class="left"><?php echo lang('global.tiempo_post') ?>:</span> <span class="right"><strong><?php echo Post_produccion::calculo_tiempo_post($tiempo_post['0']->minutos,$tiempo_post['0']->segundos,$tiempo_post['0']->cuadros); ?></span></strong></p></td>
          </tr>
        </table>
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0" > 
          <tr>
            <?php 
            if($capitulo){
              $m=$capitulo['0']->flashback_minutos+$capitulo['0']->transiciones_minutos+$capitulo['0']->stab_minutos+$capitulo['0']->recap_minutos+$capitulo['0']->cabezote_minutos+$capitulo['0']->credito_minutos+$capitulo['0']->cortinillas_minutos+$capitulo['0']->despedida_minutos+$capitulo['0']->presentacion_minutos+$capitulo['0']->foto_minutos+$capitulo['0']->imagenes_archivos_minutos;
              $s=$capitulo['0']->flashback_segundos+$capitulo['0']->transiciones_segundos+$capitulo['0']->stab_segundos+$capitulo['0']->recap_segundos+$capitulo['0']->cabezote_segundos+$capitulo['0']->credito_segundos+$capitulo['0']->cortinillas_segundos+$capitulo['0']->despedida_segundos+$capitulo['0']->presentacion_segundos+$capitulo['0']->foto_segundos+$capitulo['0']->imagenes_archivos_segundos;
              $c=$capitulo['0']->flashback_cuadros+$capitulo['0']->transiciones_cuadros+$capitulo['0']->stab_cuadros+$capitulo['0']->recap_cuadros+$capitulo['0']->cabezote_cuadros+$capitulo['0']->credito_cuadros+$capitulo['0']->cortinillas_cuadros+$capitulo['0']->despedida_cuadros+$capitulo['0']->presentacion_cuadros+$capitulo['0']->foto_cuadros+$capitulo['0']->imagenes_archivos_cuadros;
            }else{
              $m='00';
              $s='00';
              $c='00';
            }
              
             ?>
            <td><p><span class="left"><?php echo lang('global.ext_') ?>: </span><span class="tiempo_extra_total right"><strong><?php echo Post_produccion::calculo_tiempo_post($m,$s,$c); ?></span></strong></p></td>
          </tr>
        </table>
        <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0" > 
          <tr>
           
            <?php $minutos=$tiempo_post['0']->minutos+$m;
                  $segundos=$tiempo_post['0']->segundos+$s;
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
            <td><p><span class="left">total <?php echo lang('global.capitulo') ?>:</span> <span class="tiempo_total right"><strong><?php echo $negativo ?><?php echo Post_produccion::calculo_tiempo_post_redondeo($tiempo_post['0']->minutos+$m,$tiempo_post['0']->segundos+$s,$tiempo_post['0']->cuadros+$c); ?></span></strong></p></td>
          </tr>
        </table>
      <!-- </div>
    <div class="five columns "  style="padding: 0 3px;"> -->
      <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><span class="left"><?php echo lang('global.escenas_asignada') ?>: </span> <span class="right"><strong><?php echo $total_escenas['0']->total; ?></span></strong></p></td>
        </tr>
      </table>
      <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><span class="left"><?php echo lang('global.escenas_post_producida') ?>: </span> <span class="right"><strong><?php echo $tiempo_post['0']->total_escenas; ?></span></strong></p></td>
        </tr>
      </table>
      <table class="table_post_prod table_post_prod_min" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><span class="left "><?php echo lang('global.diferencia') ?>:  </span><span class="right"><strong><?php echo $total_escenas['0']->total-$tiempo_post['0']->total_escenas; ?></span></strong></p></td>
        </tr>
      </table>
  </div>
  </div>

<div class="clr"></div>

<ul class="accordion up_files">
  <li>
    <div class="title"><?php echo lang('global.bitacora_aprobaciones') ?></div>
    <div class="content">
      <table cellpadding="0" cellspacing="0" class="table_post_prod_gen">
        <thead>
          <tr><td><?php echo lang('global.estado') ?></td><td><?php echo lang('global.departamento_responsable') ?></td><td><?php echo lang('global.fecha_aprobacion') ?></td><td><?php echo lang('global.aprobado_por') ?></td></tr>
        </thead>
        <tbody>
        <?php if($vitacora){ ?>
           <?php foreach ($vitacora as $v) { ?>
             <tr>
              <td><span></span> <?php echo $v['estado'] ?></td>
              <td><span></span> <?php echo $v['aprobado'] ?></td>
              <td><span></span> <?php echo $v['fecha']; ?></td>
              <td><span><?php echo $v['usuario'].$v['estatus'] ?></span></td></tr>
           <?php } ?>
        <?php }else{ ?>
          <tr><td><?php echo lang('global.nodatos') ?></td><tr>
        <?php } ?>
          
        </tbody>
      </table>
      <div class="clr"></div>
    </div>
  </li>
</ul>




<?php if($escenas){ ?>
<table cellspacing="0" cellpadding="0" class="table_post_prod_gen table_general">
   <thead>
     <tr>
        <td>LIB</td>
        <td>ESC</td>
        <!--td>ESTADO</td-->
        <td><?php echo lang('global.tiempo_estimado') ?></td>
        <td><?php echo lang('global.tiempo_real') ?></td>
        <td><?php echo lang('global.tiempo_post') ?></td>
        <td><?php echo lang('global.acciones') ?></td>
     </tr>
   </thead>
   <tbody class="tr_escenas">
    <?php foreach ($escenas as $e) { ?>

        <tr class="escenas escena_<?php echo $e['id_capitulo_escena']; ?> ">
          <td><?php echo $e['numero'] ?></td>
          <td><?php echo $e['numero_escena'] ?></td>
          <!--td><?php echo $e['descripcion'] ?></td-->
          <td class="align_center"><?php echo $e['duracion_estimada_minutos'].':'.$e['duracion_estimada_segundos'] ?></td>
          <td class="align_center"> <?php echo $e['duracion_real_minutos'].':'.$e['duracion_real_segundos'] ?></td>
          <?php $permitir=0;
              foreach ($estado as $es) {
                   if($es<15){
                   $permitir=1;
                  }
              } ?>
          <?php if($permisos=="write" and $permitir==1 and $produccion['0']->estado!=2){ ?>
                          <?php if($e['tiempo_post_minutos'] or $e['tiempo_post_segundos']){ ?>
                            
                            <td class="align_center">
                            <a href="#" class="add_tiempo_post" data-id="<?php echo $e['id_escena'] ?>" onclick="return false;">
                            <?php if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)){ $minutos='0'.$e['tiempo_post_minutos']; }else{ $minutos=$e['tiempo_post_minutos'];} ?>
                            <?php if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ $segundos='0'.$e['tiempo_post_segundos']; }else{ $segundos=$e['tiempo_post_segundos'];} ?>
                            <?php if($e['tiempo_post_cuadros']==null){ $cuadros='00';}elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ $cuadros='0'.$e['tiempo_post_cuadros']; }else{ $cuadros=$e['tiempo_post_cuadros'];} ?>
                              <?php echo  $minutos.':'.$segundos.'-'.$cuadros?>
                            </a>
                            <div class="tiempo_post_<?php echo $e['id_escena'] ?> hide_box tiempos_pos_box">
                               <span class="close_box"></span>
                               <?php echo form_open($idioma.'post_produccion/save_post_produccion') ?>
                               <input type="hidden" name="id_escena" value="<?php echo $e['id_escena'] ?>">
                               <input type="hidden" name="id_capitulo" value="<?php echo $id_capitulo ?>">
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                 <?php echo lang('global.minutos') ?>
                                 <?php if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)){ $minutos='0'.$e['tiempo_post_minutos']; }else{ $minutos=$e['tiempo_post_minutos'];} ?>
                                 <input type="text" name="minutos_post" value="<?php echo  $minutos ?>" class="columns minutos m_<?php echo $e['id_escena'] ?>" placeholder="MM">
                               </div>
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                 <?php echo lang('global.segundos') ?>
                                 <?php if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ $segundos='0'.$e['tiempo_post_segundos']; }else{ $segundos=$e['tiempo_post_segundos'];} ?>
                                 <input type="text" name="segundos_post" value="<?php echo $segundos ?>" class="columns segundos s_<?php echo $e['id_escena'] ?>" placeholder="SS">
                               </div>
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                 <?php echo lang('global.cuadros') ?>
                                 <?php if($e['tiempo_post_cuadros']==null){ $cuadros='00';}elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ $cuadros='0'.$e['tiempo_post_cuadros']; }else{ $cuadros=$e['tiempo_post_cuadros'];} ?>
                                 <input type="text" name="cuadros_post" value="<?php echo $cuadros ?>" class="columns segundos c_<?php echo $e['id_escena'] ?>" placeholder="CC">
                               </div>
                               <input type="hidden" name="id_produccion" value="<?php echo $id_produccion ?>">   
                               <input type="hidden" name="id_capitulo" value="<?php echo $id_capitulo ?>">   
                               <a class="cancel_icon close_box" style="margin-top:17px"></a>
                               <a class="save_icon2 save_tiempo_post" data-idescena="<?php echo $e['id_escena'] ?>" href="#" style="margin-top:17px"></a>
                               <?php echo form_close() ?>
                            </div>
                     
                           </td>
                          <?php }else{ ?>
                          
                           <td class="align_center"> 
                            <a href="#" class="add_tiempo_post" data-id="<?php echo $e['id_escena'] ?>" onclick="return false;">
                               <?php echo lang('global.agregar_timpo_extra') ?>
                            </a>
                            <div class="tiempo_post_<?php echo $e['id_escena'] ?> hide_box tiempos_pos_box">
                               <span class="close_box"></span>
                               <?php echo form_open($idioma.'post_produccion/save_post_produccion') ?>
                               <input type="hidden" name="id_escena" value="<?php echo $e['id_escena'] ?>">
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                  <?php echo lang('global.minutos') ?>
                                 <input type="text" name="minutos_post" value="" class="columns minutos m_<?php echo $e['id_escena'] ?>" placeholder="MM">
                               </div>
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                 <?php echo lang('global.segundos') ?>
                                 <input type="text" name="segundos_post" value="" class="columns segundos s_<?php echo $e['id_escena'] ?>" placeholder="SS">
                               </div>
                               <div style="width:68px;float:left;font-size:10px;color:#fff;">
                                 <?php echo lang('global.cuadros') ?>
                                 <input type="text" name="cuadros_post" value="" class="columns segundos c_<?php echo $e['id_escena'] ?>" placeholder="CC">
                               </div>
                               <input type="hidden" name="id_produccion" value="<?php echo $id_produccion ?>">   
                               <input type="hidden" name="id_capitulo" value="<?php echo $id_capitulo ?>">   
                               <a class="cancel_icon close_box" style="margin-top:17px"></a>
                               <a class="save_icon2 save_tiempo_post" data-idescena="<?php echo $e['id_escena'] ?>" href="#" style="margin-top:17px"></a>
                               <?php echo form_close() ?>
                           </div>
                            
                          </td>

                        <?php } ?>
                <?php }else{ ?>
                <td class="align_center">
                      <?php if($e['tiempo_post_minutos']<10 and (strlen($e['tiempo_post_minutos'])<2)){ 
                          $minutos='0'.$e['tiempo_post_minutos']; 
                         if(strlen($minutos)<2){
                           $minutos='0'.$minutos; 
                         } 
                        }else{ 
                          $minutos=$e['tiempo_post_minutos'];} 
                          ?>
                      <?php 
                        if($e['tiempo_post_segundos']<10 and (strlen($e['tiempo_post_segundos'])<2)){ 
                          $segundos='0'.$e['tiempo_post_segundos']; 
                          if(strlen($segundos)<2){
                             $segundos='0'.$segundos; 
                           } 
                        }else{
                         $segundos=$e['tiempo_post_segundos'];
                         } ?>
                         <?php 
                         if($e['tiempo_post_cuadros']==null){ 
                          $cuadros='00';
                        }elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ 
                          $cuadros='0'.$e['tiempo_post_cuadros']; 
                          if(strlen($cuadros)<2){
                             $cuadros='0'.$cuadros; 
                           } 
                        }else{
                         $cuadros=$e['tiempo_post_cuadros'];
                         } ?>
                      <?php echo $minutos.':'.$cuadros.'-'.$cuadros; ?>
                </td>  
                <?php } ?>
           <td class="eliminar_escena_capitulo" data-idescena="<?php echo $e['id_capitulo_escena']; ?>"> <?php if($permisos=="write" and $permitir==1 and $produccion['0']->estado!=2){ ?><a href="#"><?php echo lang('global.eliminar') ?></a><?php } ?> </td>
        </tr>
    <?php } ?>
    </tbody>
  </table>
<div>
  <input type="button" style="width:100%!important;" id="load_escenas" data-permitir="<?php echo $permitir ?>" value="<?php echo lang('global.ver_mas_escenas') ?>" class="button" data-idcaptulo="<?php echo $id_capitulo ?>" data-produccion="<?php echo $id_produccion ?>">
</div>
<?php } ?>

</div>