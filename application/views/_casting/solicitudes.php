<?php $idioma = $this->lang->lang().'/'; ?>
<!-- SECCION PARA ORDENAR COLUMNAS -->
<div id="ordenarWrap" style="display:none">
    <div class="ordenarBox">
        <span class="closeIcon" id="no_save_order"></span>
        <div class="table_general">
            <table class="main">
                <thead>
                    <tr>
                        <td><?php echo lang('global.ordenar_tabla') ?></td>
                    </tr>
                </thead>
            </table>
            <br>
            <?php echo form_open($idioma.'casting/orden_columnas','id="orderCasting"') ?>
            <input type="hidden" name="campos_columnas" id="campos_select" value="">
            <input type="hidden" name="tipo" value="6">
                <div class="row" id="order_fields">
                    <div class="column six">
                        <h3><?php echo lang('global.campos_activos') ?></h3>
                        <ul id="itemsEnable" class="connectedSortable">
                        <?php if($campos_usuario){?>
                          <?php for ($i=0; $i < count($campos_usuario); $i++) { ?>
                            <?php 
                            $class_sort=""; 
                            if( $campos_usuario[$i]=="# Solicitud" OR 
                                $campos_usuario[$i]=="Estatus" OR
                                $campos_usuario[$i]=="Responsable") { 
                                $class_sort="horizontal_sort"; 
                            } 
                            ?>
                            <li class="<?=$class_sort?>" data-order="<?=$i+1?>"><?=$campos_usuario[$i]?></li>
                        <?php } } ?>
                        </ul>
                    </div>
                    <div class="column six">
                        <h3><?php echo lang('global.campos_disponibles') ?></h3>
                        <ul id="itemsDisable" class="connectedSortable">
                            <?php 
                          for ($i=0; $i < count($campos); $i++) { 
                            $validacion = true;
                            for ($j=0; $j < count($campos_usuario); $j++) { 
                                if($campos_usuario[$j]==$campos[$i]){
                                    $validacion=false;
                                }
                            }
                            if($validacion){ ?>
                            <li class="inactive_field"><?=$campos[$i]?></li>
                          <?php } } ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="column twelve">
                        <div class="align_center">
                            <input type="submit" id="save_fields_casting" value="<?php echo lang('global.aplicar_cambios') ?>" class="button save_button">
                        </div>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
<!-- FIN SECCION PARA ORDENAR COLUMNAS -->

<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.solicitudes') ?>
</div>
<?php $this->load->view('includes/partials/top_nav_solicitudes'); ?>

<nav class="nav_post">
  <ul class="nav_casting">
    <li><a href="javascript:history.go(-1)" class="active"><?php echo lang('global.volver') ?></a></li>
    <li><a href="#" class="buttons icon icon_plus" id="ordenarCasting"><span></span><?php echo lang('global.ordenar') ?></a></li>
    <li><a href="#" class="buttons icon icon_plus" id="filtro_solicitudes"><span></span><?php echo lang('global.filtrar') ?></a></li>
    <li><a href="<?=base_url($idioma.'casting/pdf_solicitudes')?>" target="_blank" class="buttons icon icon_plus"><span></span><?php echo lang('global.imprimir') ?></a></li>
    <li><a href="<?=base_url($idioma.'casting/excel_solicitudes')?>" target="_blank" class="buttons icon icon_plus"><span></span><?php echo lang('global.guardar') ?></a></li>
    <?php if($permisos=='write'){ ?>
    <li><a href="<?=base_url($idioma.'casting/crear_solicitud')?>" class="buttons icon icon_plus"><span></span><?php echo lang('global.crear_solicitud') ?></a></li>
    <?php } ?>
  </ul>
</nav>

<div class="column twelve" style="display:none;" id="filtro_solicitudes_section">
  <?php echo form_open($idioma.'casting/solicitudes','id="solicitudes", class="", onSubmit=""');?>
    <div class="row">
        <div class="info filtros_solicitud">
           <div class="columns three">
            <label><?php echo lang('global.estado_produccion') ?>: </label>
              <select name="produccion_estado" id="produccion_iestado">
                <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                <option  value="1" <?php if($estado_pruduccion==1){ echo 'selected';} ?>><?php echo lang('global.activas') ?></option>
                <option  value="2" <?php if($estado_pruduccion==2){ echo 'selected';} ?>>No <?php echo lang('global.guardar') ?></option>
              </select>
          </div>
          <div class="columns three">
            <label><?php echo lang('global.produccion') ?>: </label>
            <select name="produccion" id="produccion_solicitudes" >
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php foreach ($producciones as $the_produccion): ?>
                <option value="<?=$the_produccion['id_produccion']?>"
                  <?php if ($the_produccion['estado']==2 AND $idproduccion != $the_produccion['id_produccion']): ?>
                    style="display:none;" class="inactive"
                  <?php endif ?>
                  <?php if ($idproduccion == $the_produccion['id_produccion']): ?>
                    selected
                  <?php endif ?>
                ><?=$the_produccion['nombre_produccion']?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="columns three">
            <label>Otro si: </label>
            <select name="otro_si" id="otro_si">
            <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <option value="2" <?php echo ($otro_si==2) ? "selected" : "" ?>><?php echo lang('global.si') ?></option>
              <option value="1" <?php echo ($otro_si==1) ? "selected" : "" ?> ><?php echo lang('global.no') ?></option>
            </select>
          </div>

          <div class="clr6"></div>
          <div class="columns twelve">
            <table class="table-layout-estados">
              <caption class="encabezado-estados"><?php echo lang('global.estado') ?></caption>
              <tr>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#ccc;"></span>
                    <?php echo lang('global.generando_solicitud') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(1, $estados_solicitud)){ echo 'checked'; } ?>  class="estado_solicitud" value="1">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#d9f3c2;"></span>
                    <?php echo lang('global.aprobacion_extranjero') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(2, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="2">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#BAF188;"></span>
                    <?php echo lang('global.solicitud_completa') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(18, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="18">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#85c646;"></span>
                    <?php echo lang('global.aprobada_produccion') ?>  <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(3, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="3">
                  </label>
                </td>
              </tr>
              <tr>
                <!--td>
                  <label class="label-fill"> 
                    <span class="estado_color" style="background:#ff9400;"></span>
                    <?php echo lang('global.rechazada__produccion') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(4, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="4">
                  </label>
                </td-->
                <td>
                  <label class="label-fill">
                     <span class="estado_color" style="background:#B2E6FD;"></span>
                    <?php echo lang('global.generando_contrato') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(5, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="5">
                  </label>
                </td>
                <!--td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#E79F32;"></span>
                    <?php echo lang('global.rechazada_incompleta') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(7, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="7">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#E79F32;"></span>
                    <?php echo lang('global.rechazada_escalar_a_juridica') ?>.<input type="checkbox" name="estados_solicitud[]" <?php if(in_array(8, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="8">
                  </label>
                </td>
              </tr>
              <tr>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#E79F32;"></span>
                    <?php echo lang('global.rechazada_otros') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(9, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="9">
                  </label>
                </td-->
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#B6BFF1;"></span>
                    <?php echo lang('global.proceso_de_firma') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(10, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="10">
                  </label>
                </td>
                 <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#B6BFF1;"></span>
                    <?php echo lang('global.proceso_de_firma_recoleccion') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(20, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="20">
                  </label>
                </td>
                
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#fdff91;"></span>
                    <?php echo lang('global.recolectar_documentos') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(17, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="17">
                  </label>
                </td>
              </tr>
              <tr>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#fee93e;"></span>
                    <?php echo lang('global.terminada') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(11, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="11">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:black;color:white;"></span>
                    <?php echo lang('global.canceladas') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(12, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="12">
                  </label>
                </td>
                <td>
                  <label class="label-fill">
                    <span class="estado_color" style="background:#6072D6;color:white;"></span>
                    <?php echo lang('global.anulado') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(16, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="16">
                  </label>
                </td>
                <td>
                  <label class="label-fill"> 
                    <span class="estado_color" style="background:#ff9400;"></span>
                    <?php echo lang('global.rechazado') ?> <input type="checkbox" name="estados_solicitud[]" <?php if(in_array(4, $estados_solicitud)){ echo 'checked'; } ?> class="estado_solicitud" value="4">
                  </label>
                </td>
              </tr>
            </table>
          </div>
          <div class="columns twelve">
            <button class="button twelve save_button" style="margin-top:10px"><?php echo lang('global.filtrar') ?></button>
          </div>
          <div class="clr"></div>
      
        </div>
    </div>
   
    
    
    <div class="clr6"></div>


  <?php echo form_close(); ?>
</div>

<div class="column twelve">
  <div class="info">
  <div class="column twelve" style="padding:0 0 6px 0;">
    <div class="column twelve" style="padding:0">
      <label for=""><?php echo lang('global.buscar') ?>:</label>
      <input type="text" name="nombre_solicitud" id="nombre_solicitud" class="search_input">
    </div>
   
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
  <?php 
  if ($this->session->userdata('tipo_pruduction_suite')==1){
    $label = lang('global.todas_las_solicitudes');
  }else{
    $label = lang('global.mis_solicitudes');
  }
  ?>

  <div class="column title_section" style="margin:0"><h5><?=$label?></h5></div>
  <div class="row" style="overflow:auto">
  <?php if ($solicitudes): ?>
     
      <table cellspacing="0" cellpadding="0" border="1" style="width:2000px" class="tablesorter tabla_detalle_actor">
      <thead>
        <tr>
          <?php $m =0; while ( $m < count($campos_usuario)-1) { ?>
            <?php if($campos_usuario[$m]=="# Solicitud"){ ++$m;?>
              <th><?php echo lang('global.solicitud') ?></th>
            <?php } ?>

            <?php if($campos_usuario[$m]=="Estatus"){ ++$m;?>
              <th><?php echo lang('global.estatus') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Responsable"){ ++$m;?>
              <th><?php echo lang('global.responsable') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Producción"){ ++$m;?>
              <th><?php echo lang('global.produccion') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Personaje"){ ++$m;?>
              <th><?php echo lang('global.personajes') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Actor"){ ++$m;?>
              <th><?php echo lang('global.actor') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Monto"){ ++$m;?>
              <th><?php echo lang('global.monto') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Forma pago"){ ++$m;?>
              <th><?php echo lang('global.tipo_de_pago') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Fecha inicio"){ ++$m;?>
              <th><?php echo lang('global.fecha_inicio') ?></th>
            <?php } ?>
            
            <?php if($campos_usuario[$m]=="Fecha terminación"){ ++$m;?>
              <th><?php echo lang('global.fecha_terminacion') ?></th>
            <?php } ?>
            
          <?php } ?>
          <th><?php echo lang('global.acciones') ?></th>
        </tr>
      </thead>
      <tbody class="color" id="tbody_solicitudes">
        <?php foreach ($solicitudes as $solicitud): ?>
          <?php $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); 
               $conti=1;
               if( ($solicitud->id_estado==3) AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1 OR $solicitud->id_tipo_moneda==2)){
                  if($coordinado==1){
                     $conti=0;
                  }
               } 

               if (($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1") OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2) {
                   if($coordinado==1){
                     $conti=0;
                  }
               }
          ?>
             <?php if(!$aprobacion_finanzas and $conti==1){ ?>
                    <?php if (Casting::permisos_solicitud($solicitud)=="write"): ?>
                                <tr class="estado_solicitud<?=$solicitud->id_estado?> row_solicitud">

                                  <?php $m =0; while ( $m < count($campos_usuario)-1) {?>

                                    <!-- ID SOLICITUD -->

                                    <?php if($campos_usuario[$m]=="# Solicitud"){ ++$m;?>
                                      <td>
                                        <a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud->id)?>" class="span_solicitud">

                                          <?php if ($solicitud->tipo == 2 ): ?>
                                            <?php echo Casting::completar_id($solicitud->id_solicitud_anexa); ?> - 
                                            <?php echo Casting::numeracion_otro_si($solicitud->id_solicitud_anexa,$solicitud->id_solicitud);?>
                                          <?php else: ?>
                                            <?php echo Casting::completar_id($solicitud->id_solicitud); ?>
                                          <?php endif ?>
                                        </a>
                                      </td>
                                    <?php } ?>

                                    <!-- ESTADO SOLICITUD -->
                                    <?php if($campos_usuario[$m]=="Estatus"){ ++$m;?>
                                      <td class="estado_solicitud_cell">
                                        <?php 
                                          $aprobacion_firma =  $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,10,3);
                                          $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,17,3); 
                                        ?>
                                        <?php if ($solicitud->id_estado==20): ?>
                                          <?php if (!$aprobacion_firma): ?>
                                            <?=lang('global.proceso_de_firma')?>
                                          <?php endif ?>
                                          <?php if (!$aprobacion_documentos AND !$aprobacion_firma): ?>
                                              <?='-'?>
                                          <?php endif ?>
                                          <?php if (!$aprobacion_documentos): ?>
                                            <?=lang('global.recoleccion_de_documentos')?>
                                          <?php endif ?>
                                        <?php else: ?>
                                          <?=strtoupper($solicitud->descripcion)?> 
                                          <?php if ($solicitud->tipo == 2): ?> 
                                            / OTRO SI 
                                          <?php endif ?>
                                        <?php endif ?>
                                      </td>
                                    <?php } ?>

                                    <!-- RESPONSABLE -->
                                    <?php if($campos_usuario[$m]=="Responsable"){ ++$m;?>
                                      <td>
                                        <?php
                                          $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13,3);
                                          $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); 
                                          $aprobacion_firma =  $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,10,3);
                                          $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,17,3);
                                          
                                          if( ($solicitud->id_estado==3) AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1 OR $solicitud->id_tipo_moneda==2)){
                                            
                                            $responsable =  strtoupper($solicitud->responsable); 
                                            if($aprobacion_juridica){
                                              $responsable = str_replace("JURíDICA -", '',strtoupper($responsable));
                                              $responsable = str_replace("JURíDICA", '',strtoupper($responsable));
                                            }

                                            if($aprobacion_finanzas){

                                              $responsable = str_replace(strtoupper("- FINANZAS"), '', $responsable );
                                              $responsable = str_replace("FINANZAS", '',strtoupper($responsable));
                                            }


                                                if($solicitud->roles==1 or $solicitud->id_nacionalidad==13){
                                                 $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));

                                                }else{
                                                   $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('Coordinador de contrato'), '', strtoupper($responsable));
                                                }


                                            echo str_replace('COORDINADOR DE CONTRATO -', '', $responsable);

                                          }else if ($solicitud->id_estado==20) {

                                              if (($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1") OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2) {
                                                echo lang('global.juridica');
                                              }else{
                                                echo lang('global.coordinadora_de_contrado');
                                              }

                                          }else{
                                            if($solicitud->id_estado==5 AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2)){
                                              echo lang('global.coordinadora_de_contrado');
                                            }else{
                                              if ($solicitud->id_estado!=19) {
                                                $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($solicitud->responsable));
                                                $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                                                $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                                                $responsable = str_replace(strtoupper('- Abogada RTI'), '', strtoupper($responsable));
                                                $responsable = str_replace(strtoupper('Abogada RTI'), '', strtoupper($responsable));

                                                if($solicitud->id_estado==3 and $solicitud->id_nacionalidad==13){
                                                     $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                                                     $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                                                     $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                                     $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                                }
                                                echo $responsable;
                                              }elseif($solicitud->id_nacionalidad!=13 or $solicitud->condiciones_especiales!=""){
                                                if($solicitud->roles==1 and $solicitud->id_nacionalidad!=13){
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($solicitud->responsable));
                                                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('-'), '', strtoupper($responsable));
                                                 }elseif($solicitud->roles!=1 and $solicitud->id_nacionalidad!=13){
                                                    $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($solicitud->responsable));
                                                    $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('-'), '', strtoupper($responsable));
                                                 }
                                                 
                                                echo $responsable;

                                              }else{
                                                if($solicitud->roles==3 and $solicitud->id_nacionalidad!=13){
                                                    $responsable = str_replace(strtoupper('Jurídica -'), '', strtoupper($solicitud->responsable));
                                                    echo $responsable;
                                                  }else{
                                                     echo strtoupper($solicitud->responsable);
                                                  }
                                              }
                                            }
                                          }
                                      ?>
                                      </td>
                                    <?php } ?>

                                    <!-- PRODUCCION -->
                                    <?php if($campos_usuario[$m]=="Producción"){ ++$m;?>
                                      <td><?=$solicitud->nombre_produccion?></td>
                                    <?php } ?>

                                    <!-- PERSONAJE -->
                                    <?php if($campos_usuario[$m]=="Personaje"){ ++$m;?>
                                      <td class="nombre_elemento"><?=$solicitud->elementos?></td>
                                    <?php } ?>

                                    <!-- ACTOR -->
                                    <?php if($campos_usuario[$m]=="Actor"){ ++$m;?>
                                      <td class="nombre_actor"><?=$solicitud->actor?></td>
                                    <?php } ?>

                                    <!-- MONTO -->
                                    <?php if($campos_usuario[$m]=="Monto"){ ++$m;?>
                                    <?php if($solicitud->tipo_moneda=='PESOS'){
                                        $moneda='$';
                                      }else{
                                        $moneda='U$D';
                                        } ?>
                                      <td class="text-right"><?=$moneda.''.number_format((double)$solicitud->monto, 2, '.', ",")?></td>
                                    <?php } ?>

                                    <!-- FORMA DE PAGO -->
                                    <?php if($campos_usuario[$m]=="Forma pago"){ ++$m;?>
                                      <td>
                                        <?php
                                        if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                                          echo "Otro";
                                        }else{
                                          echo $solicitud->forma_pago;
                                        }  
                                        ?>
                                      </td>
                                    <?php } ?>

                                    <!-- FECHA INICIO -->
                                    <?php if($campos_usuario[$m]=="Fecha inicio"){ ++$m;?>
                                      <td><?=date("Y-M-d",strtotime($solicitud->fecha_inicio))?></td>
                                    <?php } ?>

                                    <!-- FECHA FINAL -->
                                    <?php if($campos_usuario[$m]=="Fecha terminación"){ ++$m;?>
                                      <td><?=date("Y-M-d",strtotime($solicitud->fecha_final))?></td>
                                    <?php } ?>

                                  <?php } ?>
                                  
                                  <!-- ACCIONES -->
                                  <td>
                                    <?php if ($solicitud->id_estado != 1 AND $solicitud->id_estado != 11 AND $solicitud->id_estado!=12 AND $solicitud->id_estado!=16): ?>
                                        <?php if (Casting::validacion_caso_especial($solicitud)==0 OR Casting::validacion_caso_especial($solicitud)==1 OR Casting::validacion_caso_especial($solicitud)==3): ?>
                                          <a href="#" class="aprobar_solicitud" data-estado="<?=$solicitud->id_estado?>" data-solicitud="<?=$solicitud->id_solicitud?>">
                                            <?php echo lang('global.aprobar') ?>
                                          </a>
                                        <?php endif ?>
                                      <?php if ($solicitud->id_estado == 5 OR $solicitud->id_estado == 6 OR $solicitud->id_estado == 10): ?>
                                        / <a href="#" class="rechazar_solicitud" data-estado="7" data-solicitud="<?=$solicitud->id_solicitud?>">
                                          <?php echo lang('global.rechazar') ?> 
                                      <?php else: ?>
                                        / <a href="#" class="rechazar_solicitud" data-estado="4" data-solicitud="<?=$solicitud->id_solicitud?>">
                                          <?php echo lang('global.rechazar') ?>
                                        </a>
                                      <?php endif ?>
                                    <?php endif ?>
                                  </td>
                                </tr>
                                <tr class="info_solicitud">
                                  <!-- CELDAS OCULTAS PARA ORDENAMIENTO -->
                                  <td style="display:none;"><a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud->id)?>"><?php echo Casting::completar_id($solicitud->id); ?></a></td>
                                  <td style="display:none;"><?=$solicitud->descripcion?></td>
                                  <td style="display:none;"><?=$solicitud->responsable?></td>
                                  <td style="display:none;"><?=$solicitud->nombre_produccion?></td>
                                  <td style="display:none;" class="nombre_elemento"><?=$solicitud->elementos?></td>
                                  <td style="display:none;" class="nombre_actor"><?=$solicitud->actor?></td>
                                  <?php if($solicitud->tipo_moneda=='PESOS'){
                                        $moneda='$';
                                      }else{
                                        $moneda='U$D';
                                        } ?>
                                  <td class="text-right" style="display:none;"><?=$moneda.''.number_format((double)$solicitud->monto, 2, '.', ",")?></td>
                                  <td style="display:none;">
                                  <?php
                                    if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                                      echo "Otro";
                                    }else{
                                      echo $solicitud->forma_pago;
                                    }  
                                    ?>
                                  </td>
                                  <td style="display:none;"><?=date("Y-M-d",strtotime($solicitud->fecha_inicio))?></td>
                                  <td style="display:none;"><?=date("Y-M-d",strtotime($solicitud->fecha_final))?></td>
                                  <td style="display:none;">
                                    <?php if ($solicitud->id_estado != 1 AND $solicitud->id_estado != 11): ?>
                                        <a href="#" class="aprobar_solicitud" data-estado="<?=$solicitud->id_estado?>" data-solicitud="<?=$solicitud->id_solicitud?>">
                                          <?php echo lang('global.aprobar') ?>
                                        </a>
                                      <?php if ($solicitud->id_estado == 5 OR $solicitud->id_estado == 6 OR $solicitud->id_estado == 10): ?>
                                        / <a href="#" class="rechazar_solicitud" data-estado="7" data-solicitud="<?=$solicitud->id_solicitud?>">
                                          <?php echo lang('global.rechazar') ?> 
                                      <?php else: ?>
                                        / <a href="#" class="rechazar_solicitud" data-estado="4" data-solicitud="<?=$solicitud->id_solicitud?>">
                                          <?php echo lang('global.rechazar') ?>
                                        </a>
                                      <?php endif ?>
                                  <?php endif ?>
                                  </td>
                                  <!-- FIN CELDAS OCULTAS PARA ORDENAMIENTO -->
                                  <td colspan="11">
                                    <?php $historial_solicitud = $this->model_casting->historial_solicitud($solicitud->id_solicitud); ?>
                                    <?php if ($historial_solicitud): ?>
                                      <table class="tableSolicitudes" style="width: 100%;">
                                        <thead>
                                          <tr>
                                            <th><?php echo lang('global.fecha') ?></th>  
                                            <th><?php echo lang('global.estatus') ?></th>
                                            <th><?php echo lang('global.usuarios') ?></th>
                                            <th><?php echo lang('global.razon') ?></th>
                                          </tr>
                                        <thead>
                                        <tbody>
                                          <?php foreach ($historial_solicitud as $aprobacion): ?>
                                            <tr > 
                                              <td><?=date("d-M-Y g:i a",strtotime($aprobacion->fecha_aprobacion))?></td>
                                              <td><?=$aprobacion->estado?>
                                              <?php if ($solicitud->tipo==2): ?>
                                                 / OTRO SI
                                              <?php endif ?>
                                              </td>
                                              <td><?=$aprobacion->nombre_usuario?></td>
                                              <td><?=$aprobacion->notas?></td>
                                            </tr>
                                          <?php endforeach ?>
                                        </tbody>
                                      </table>           
                                    <?php endif ?>
                                  </td>
                                </tr>
                    <?php endif ?>
              <?php }else{
                  
                 // array_push($todas_solicitudes,$solicitud);
              } ?>      
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>
    </div>
    <div class="clr6"></div>

  <?php if ($this->session->userdata('tipo_pruduction_suite')!=1){ ?>
    <div class="column title_section" style="margin:0"><h5><?php echo lang('global.todas_las_solicitudes') ?></h5></div>
    <div class="row" style="overflow:auto">
      <table cellspacing="0" cellpadding="0" border="1" style="width:2000px" class="tablesorter tabla_detalle_actor">
        <thead>
          <tr>
            <th><?php echo lang('global.solicitud') ?></th>
            <th><?php echo lang('global.estatus') ?></th>
            <th><?php echo lang('global.responsable') ?></th>
            <th><?php echo lang('global.produccion') ?></th>
            <th><?php echo lang('global.personajes') ?></th>
            <th><?php echo lang('global.actor') ?></th>
            <th><?php echo lang('global.monto') ?></th>
            <th><?php echo lang('global.tipo_de_pago') ?></th>
            <th><?php echo lang('global.fecha_inicio') ?></th>
            <th><?php echo lang('global.fecha_terminacion') ?></th>
          </tr>
        </thead>
        <tbody class="color" id="tbody_solicitudes">
          <?php foreach ($todas_solicitudes as $solicitud): ?>
            <?php $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); 
               $conti=1;
               if( ($solicitud->id_estado==3) AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1 OR $solicitud->id_tipo_moneda==2)){
                  if($coordinado==1){
                     $conti=0;
                  }
               } 
               if (($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1") OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2) {
                   if($coordinado==1){
                     $conti=0;
                  }
               }
          ?>
             <?php $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); ?>
            <?php if (Casting::permisos_solicitud($solicitud)!="write" or $aprobacion_finanzas or $conti==0): ?>
              <tr class="estado_solicitud<?=$solicitud->id_estado?> row_solicitud">
                <td><a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud->id)?>" class="span_solicitud">
                <?php if ($solicitud->tipo == 2 ): ?>
                  <?php echo Casting::completar_id($solicitud->id_solicitud_anexa); ?> - 
                  <?php echo Casting::numeracion_otro_si($solicitud->id_solicitud_anexa,$solicitud->id_solicitud);?>
                <?php else: ?>
                  <?php echo Casting::completar_id($solicitud->id_solicitud); ?>
                <?php endif ?></a>

                </td>
                <td class="estado_solicitud_cell">
                  <?php 
                      $aprobacion_firma =  $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,10,3);
                      $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,17,3); 
                  ?>
                  <?php if ($solicitud->id_estado==20): ?>
                    <?php if (!$aprobacion_firma): ?>
                      <?=lang('global.proceso_de_firma')?>
                    <?php endif ?>
                    <?php if (!$aprobacion_documentos AND !$aprobacion_firma): ?>
                        <?='-'?>
                    <?php endif ?>
                    <?php if (!$aprobacion_documentos): ?>
                      <?=lang('global.recoleccion_de_documentos')?>
                    <?php endif ?>
                  <?php else: ?>
                    <?=strtoupper($solicitud->descripcion)?> 
                    <?php if ($solicitud->tipo == 2): ?> 
                      / OTRO SI 
                    <?php endif ?>
                  <?php endif ?>
                </td>
                <td>
                  <?php
                      $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13,3);
                      $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); 
                      $aprobacion_firma =  $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,10,3);
                      $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,17,3);
                      
                      if( ($solicitud->id_estado==3) AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1 OR $solicitud->id_tipo_moneda==2)){
                        
                        $responsable =  strtoupper($solicitud->responsable); 
                        if($aprobacion_juridica){
                          $responsable = str_replace("JURíDICA -", '',strtoupper($responsable));
                          $responsable = str_replace("JURíDICA", '',strtoupper($responsable));
                        }

                        if($aprobacion_finanzas){

                          $responsable = str_replace(strtoupper("- FINANZAS"), '', $responsable );
                          $responsable = str_replace("FINANZAS", '',strtoupper($responsable));
                        }


                        if($solicitud->roles==1 or $solicitud->id_nacionalidad==13){
                                                 $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));

                                                }else{
                                                   $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('Coordinador de contrato'), '', strtoupper($responsable));
                                                }



                        echo str_replace('COORDINADOR DE CONTRATO -', '', $responsable.'');

                      }else if ($solicitud->id_estado==20) {

                          if (($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1") OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2) {
                            echo lang('global.juridica').'';
                          }else{
                            echo lang('global.coordinadora_de_contrado');
                          }

                      }else{
                        if($solicitud->id_estado==5 AND ($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1" OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2)){
                          echo lang('global.coordinadora_de_contrado');
                        }else{
                          if ($solicitud->id_estado!=19) {
                            /*$responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($solicitud->responsable));
                            $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));*/

                            $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($solicitud->responsable));
                            $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('- Abogada RTI'), '', strtoupper($responsable));
                            $responsable = str_replace(strtoupper('Abogada RTI'), '', strtoupper($responsable));

                            if($solicitud->id_estado==3 and $solicitud->id_nacionalidad==13){
                                 $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                                 $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                            }




                            echo $responsable;
                           }elseif($solicitud->id_nacionalidad!=13 or $solicitud->condiciones_especiales!=""){
                                                if($solicitud->roles==1 and $solicitud->id_nacionalidad!=13){
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($solicitud->responsable));
                                                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('-'), '', strtoupper($responsable));
                                                 }elseif($solicitud->roles!=1 and $solicitud->id_nacionalidad!=13){
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($solicitud->responsable));
                                                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($responsable));
                                                    $responsable = str_replace(strtoupper('-'), '', strtoupper($responsable));
                                                 }
                                                echo $responsable;

                          }else{
                            if($solicitud->roles==3 and $solicitud->id_nacionalidad!=13){
                                $responsable = str_replace(strtoupper('Jurídica -'), '', strtoupper($solicitud->responsable));
                                echo $responsable;
                              }else{
                                 echo strtoupper($solicitud->responsable);
                              }
                          }
                        }
                      }
                  ?>
                </td>
                <td><?=$solicitud->nombre_produccion?></td>
                <td class="nombre_elemento"><?=$solicitud->elementos?></td>
                <td class="nombre_actor"><?=$solicitud->actor?></td>
                <?php if($solicitud->tipo_moneda=='PESOS'){
                    $moneda='$';
                  }else{
                    $moneda='U$D';
                    } ?>
                <td class="text-right"><?=$moneda.''.number_format((double)$solicitud->monto, 2, '.', ",")?></td>
                <td>
                <?php
                  if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                    echo "Otro";
                  }else{
                    echo $solicitud->forma_pago;
                  }  
                  ?>
                </td>
                <td><?=date("Y-M-d",strtotime($solicitud->fecha_inicio))?></td>
                <td><?=date("Y-M-d",strtotime($solicitud->fecha_final))?></td>
              </tr>
              <tr class="info_solicitud">
                <!-- CELDAS OCULTAS PARA ORDENAMIENTO -->
                <td style="display:none;"><a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud->id)?>"><?php echo Casting::completar_id($solicitud->id); ?></a></td>
                <td style="display:none;"><?=$solicitud->descripcion?></td>
                <td style="display:none;"><?=$solicitud->responsable?></td>
                <td style="display:none;"><?=$solicitud->nombre_produccion?></td>
                <td style="display:none;" class="nombre_elemento"><?=$solicitud->elementos?></td>
                <td style="display:none;" class="nombre_actor"><?=$solicitud->actor?></td>
                <?php if($solicitud->tipo_moneda=='PESOS'){
                    $moneda='$';
                  }else{
                    $moneda='U$D';
                    } ?>
                <td class="text-right" style="display:none;"><?=$moneda.''.number_format((double)$solicitud->monto, 2, '.', ",")?></td>
                <td style="display:none;">
                <?php
                  if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                    echo "Otro";
                  }else{
                    echo $solicitud->forma_pago;
                  }  
                  ?>
                </td>
                <td style="display:none;"><?=date("Y-M-d",strtotime($solicitud->fecha_inicio))?></td>
                <td style="display:none;"><?=date("Y-M-d",strtotime($solicitud->fecha_final))?></td>
                <!-- FIN CELDAS OCULTAS PARA ORDENAMIENTO -->
                <td colspan="11">
                  <?php $historial_solicitud = $this->model_casting->historial_solicitud($solicitud->id_solicitud); ?>
                  <?php if ($historial_solicitud): ?>
                    <table class="tableSolicitudes" style="width: 100%;">
                      <thead>
                        <tr>
                          <th><?php echo lang('global.fecha') ?></th>  
                          <th><?php echo lang('global.estatus') ?></th>
                          <th><?php echo lang('global.usuarios') ?></th>
                          <th><?php echo lang('global.razon') ?></th>
                        </tr>
                      <thead>
                      <tbody> 
                        <?php foreach ($historial_solicitud as $aprobacion): ?>
                          <tr >
                            <td><?=date("d-M-Y g:i a",strtotime($aprobacion->fecha_aprobacion))?></td>
                            <td><?=$aprobacion->estado?></td>
                            <td><?=$aprobacion->nombre_usuario?></td>
                            <td><?=$aprobacion->notas?></td>
                          </tr>
                        <?php endforeach ?>
                      </tbody>
                    </table>           
                  <?php endif ?>
                </td>
              </tr>
            <?php endif ?>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  <?php } ?>


<!--     <div class="column twelve" style="margin:6px 0 0 0;">
<?php if (count($solicitudes)>30): ?>
  <button class="button column twelve" style="padding:6px;">
    Ver más solicitudes
  </button>
<?php endif ?>
</div> -->

  </div>
</div>  
