
<?php $idioma = $this->lang->lang().'/'; setlocale(LC_TIME, 'es_ES.UTF-8');?>

<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a>  / <a href="#"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.plan_produccion') ?>
  <div class="productionName">
    <?php $id_users="";?>
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<div>
  <ul class="accordion">
    <li>
      <div class="title">
        <h5><?php echo lang('global.ver_hitos') ?></h5>
      </div>
      <div class="content">
        <div class="row" id="hitosInfo">
          <div class="columns twelve">
            <table class="tabla<_info">
              <tr>

                <td><label><?php echo lang('global.nombre_produccion') ?>:</label><?php echo $produccion['0']->nombre_produccion ?></td>
                <td><label><?php echo lang('global.inicio_pre_produccion') ?>:</label><span id="record_finish"><?= strftime('%d-%b-%Y',strtotime($produccion['0']->inicio_PreProduccion)) ?></span></td>
                 <td><label><?php echo lang('global.inicio_grabacion') ?>:</label> <span id="record_begin">

                  <?php if($produccion['0']->inicio_grabacion =='0000-00-00'){ 
                        echo '-';
                      }else{
                        echo strftime('%d-%b-%Y',strtotime($produccion['0']->inicio_grabacion)); 
                      }?>
                     </span>
                </td> 

                <td><label><?php echo lang('global.fin_grabacion') ?>:</label> <?= strftime('%d-%b-%Y',strtotime($produccion['0']->fin_grabacion)) ?></td>

                <td>
                  <label><?php echo lang('global.fecha_entrega') ?>:</label>
                  <?php if($produccion['0']->fecha_aire !="" AND $produccion['0']->fecha_aire != '0000-00-00'){ 
                      echo strftime('%d-%b-%Y',strtotime($produccion['0']->fecha_aire)); 
                    }else{
                      echo '-';
                    }?>
                </td>
                
                <td rowspan="3">
                  <label><?php echo lang('global.dias_entrega_capitulos') ?>: </label>
                  <ul>
                    <?php if($produccion['0']->lunes==1) {?><li><?php echo lang('global.lunes') ?></li><?php } ?>
                    <?php if($produccion['0']->martes==1) {?><li><?php echo lang('global.martes') ?></li><?php } ?>
                    <?php if($produccion['0']->miercoles==1) {?><li><?php echo lang('global.miercoles') ?></li><?php } ?>
                    <?php if($produccion['0']->jueves==1) {?><li><?php echo lang('global.jueves') ?></li><?php } ?>
                    <?php if($produccion['0']->viernes==1) {?><li><?php echo lang('global.viernes') ?></li><?php } ?>
                    <?php if($produccion['0']->sabado==1) {?><li><?php echo lang('global.sabado') ?></li><?php } ?>
                    <?php if($produccion['0']->domingo==1) {?><li><?php echo lang('global.domingo') ?></li><?php } ?>
                  </ul>
                </td>
              </tr>
              <tr>
                <td><label><?php echo lang('global.centro_produccion') ?>:</label> <?php echo $produccion['0']->centro ?></td>
                <td><label><?php echo lang('global.over_time') ?>:</label><?= $produccion['0']->over_time ?></td>
                <td><label><?php echo lang('global.numero_libretos') ?>:</label> <?= $produccion['0']->numero_capitulo ?></td>
                <td><label><?php echo lang('global.tiempo_por_libreto') ?>:</label> <?= $produccion['0']->minuto_capitulo ?><strong style="color:#007baa"> MM </strong><?= $produccion['0']->segundos_capitulo ?><strong style="color:#007baa"> SS </strong></td>
                <td><label><?php echo lang('global.libretos_esc_semanal') ?>:</label><?= $produccion['0']->cap_esce_semana ?></td>
              </tr>
              <tr>
                <td><label><?php echo lang('global.tipo_produccion') ?>:</label> <?php echo $produccion['0']->tipo ?></td>
                <td><label><?php echo lang('global.dias_trabajar') ?>: </label> <?php echo $produccion['0']->dias_grabacion ?></td>
                <td><label><?php echo lang('global.minutos_proy_semanal') ?>:</label><?= $produccion['0']->min_proy_semana ?></td>
                <?php if($produccion['0']->capitulos_proyectados){ ?>
                   <td><label><?php echo lang('global.capitulos_proyectados') ?>:</label><?= $produccion['0']->capitulos_proyectados ?></td> 
                <?php }else{ ?>
                   <td><label>Capitulos proyectados:</label>-</td>
                 <?php } ?>  
                <td></td>
              </tr>
            </table>
            <?php if($usuario_permisos!="read" and $produccion['0']->estado!=2) { ?>
            <div class="columns twelve">
              <a href="#" class="button edit_hito"><?php echo lang('global.editar') ?></a>
              <br><br>
            </div>
             <?php } ?>
          </div>
        </div>

          <?php echo form_open_multipart($idioma.'plan_produccion/editar_produccion','class="custom", id="hitos_form", onSubmit="return comprueba_extension()"');?>
          <div class="row">
            <div class="columns two">
              <label for="name_production"><?php echo lang('global.nombre_produccion') ?>:</label>
              <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
              <input type="hidden" name="dias_grabacion" value="<?php echo $produccion['0']->id_dias_grabacion ?>">
              <input type="text" placeholder="Insert text" id="name_production" name="name_production" value="<?php echo $produccion['0']->nombre_produccion ?>">
              <input type="hidden" value="" id="validation_name">
            </div>
            <div class="columns two">
              <label for="center_production"><?php echo lang('global.centro_produccion') ?>:</label>
              <select name="centro_produccion" id="centro_produccion" style="display:block!important;">
                <option value="<?php echo $produccion['0']->id_centroProduccion?>" selected><?php echo $produccion['0']->centro ?></option>
                <?php foreach ($centro_produccion as $c) { ?>
                <?php if($produccion['0']->id_centroProduccion!=$c['id']) { ?>
                  <option value="<?php echo $c['id'] ?>"><?php echo $c['centro'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
            <div class="columns two">
              <label for="type_production"><?php echo lang('global.tipo_produccion') ?>:</label>
              <select name="type_production" id="type_production" style="display:block!important;">
                <option value="<?php echo $produccion['0']->id_tipoProduccion ?>" ><?php echo $produccion['0']->tipo ?></option>
                <?php foreach ($tipo_produccion as $t) { ?>
                 <?php if($produccion['0']->id_tipoProduccion!=$t['id']) { ?>
                    <option value="<?php echo $t['id'] ?>"><?php echo $t['tipo'] ?></option>
                 <?php } ?>   
              <?php } ?>
              </select>
            </div>
            <div class="columns two">
              <label for="start_pre"><?php echo lang('global.inicio_pre_produccion') ?>:</label>
              <?php $cambiar_preproduccion=$this->model_produccion->estados_libretos($produccion['0']->id_produccion,1); 
               if($cambiar_preproduccion){ ?>
                    <script type="text/javascript">
                    $(document).ready(function() {
                      var fecha =new Date("<?php echo $cambiar_preproduccion['0']->fecha_entregado; ?>");
                       $( "#start_pre" ).datepicker( "option", "maxDate",fecha);
                       });
                    </script>
             <?php  }else{
                
               }
              ?>

              <input type="text" placeholder="dd/mm/aaaa" readonly id="start_pre" name="inicio_PreProduccion" class="required" value="<?=strftime('%d-%b-%Y',strtotime($produccion['0']->inicio_PreProduccion)) ?>">
              
            </div>
            <div class="columns two">
              <label for="start_recording"><?php echo lang('global.inicio_grabacion') ?>:</label>
              <?php $fecha_unidad_plan=$this->model_produccion->fecha_unidad_plan($produccion['0']->id_produccion);
                if($fecha_unidad_plan['0']->fecha_inicio){ ?>
                 <?php $fecha_grabacion=strftime('%d-%b-%Y',strtotime($fecha_unidad_plan['0']->fecha_inicio)) ?>
                <script type="text/javascript">
                $(document).ready(function() {
                  var fecha =new Date("<?php echo $fecha_grabacion ?>");
                   $( "#start_recording" ).datepicker( "option", "maxDate",fecha);
                   });
                   //$( "#start_recording" ).datepicker( "option", "minDate", new Date(2013, 9 - 1, 1) );
                </script>
                <?php } ?>

              <input type="text" placeholder="dd/mm/aaaa" readonly id="start_recording" class="required" name="inicio_grabacion" <?php if($produccion['0']->inicio_grabacion and $produccion['0']->inicio_grabacion!='0000-00-00'){ ?> value="<?= strftime('%d-%b-%Y',strtotime($produccion['0']->inicio_grabacion))?>" <?php } else{  ?> value="" <?php } ?>>
            </div>
            <div class="columns two">
              <label for="image_production"><?php echo lang('global.imagen_produccion') ?>:</label>
              <input type="file" class="" id="image_production" name="image_production" value="">
            </div>
          </div>
          <div class="row">
            <div class="columns two">

              <label for="end_recording"><?php echo lang('global.fin_grabacion') ?>:</label>

              <input type="text" placeholder="dd/mm/aaaa" readonly id="end_recording" class="required" name="fin_grabacion" value="<?= strftime('%d-%b-%Y',strtotime($produccion['0']->fin_grabacion)) ?>">
            </div>
            <div class="columns two">
              <label for="date_online"><?php echo lang('global.fecha_entrega') ?>:</label>
              <input type="text" placeholder="dd/mm/aaaa" readonly id="date_online" name="fecha_aire" value="<?php if($produccion['0']->fecha_aire!="0000-00-00"){
                echo strftime('%d-%b-%Y',strtotime($produccion['0']->fecha_aire)); }else{
                echo '-';
              }
              ?>">
            </div>
            <div class="columns two">
              <label for="number_cap"><?php echo lang('global.numero_libretos') ?>:</label>
              <?php $menor=$this->model_plan_produccion->id_libreto_maximo($produccion['0']->id_produccion);
               if($menor){
                $menor=$menor['0']->total;
               }else{
                $menor=0;
               }
              ?>
              <input type="text" placeholder="insert text" data-minimocap="<?php echo $menor ?>" id="number_cap" name="number_cap" value="<?php echo $produccion['0']->numero_capitulo?>" class="required number onlynumbers maxnumber">
            </div>
            <div class="columns two">
            <label for="mins_cap"><?php echo lang('global.minutos_por_libreto') ?>:</label>
            <div class="row">
            <div class="column four">
              <input type="text" placeholder="00" id="mins_cap" name="mins_cap" value="<?php echo $produccion['0']->minuto_capitulo;?>" class="required number onlynumbers maxnumber">
              <label class="error"><?php echo form_error('mins_cap'); ?></label>
            </div>
            <div class="column two"><label style="margin-top:8px;">MM</label></div>
            <div class="column four">
              <input type="text" placeholder="00" id="segundos_capitulo" name="segundos_capitulo" maxlength="2" value="<?php echo $produccion['0']->segundos_capitulo;?>" class="required number onlynumbers">
              <label class="error"><?php echo form_error('segundos_capitulo'); ?></label>
            </div>
            <div class="column two"><label style="margin-top:8px;">SS</label></div>
            </div>
            </div>
            <div class="columns two">
              <label for="write_cap"><?php echo lang('global.libretos_esc_semanal') ?>:</label>
              <input type="text" placeholder="insert text" id="write_cap" name="write_cap" class="number onlynumbers maxnumber" value="<?php echo $produccion['0']->cap_esce_semana?>">
            </div>
            <div class="columns two">
              <div class="columns six">
                <label for="min_proy_sem"><?php echo lang('global.minutos_proy_semanal') ?>:</label>
                <input type="text" placeholder="insert text" id="min_proy_sem" name="min_proy_sem" value="<?php echo $produccion['0']->min_proy_semana?>" class="number onlynumbers maxnumber">
              </div> 
              <div class="columns six">
                <label for="min_proy_sem"><?php echo lang('global.segundos_proy_semanal') ?>:</label>
                <input type="text" placeholder="insert text" id="seg_proy_sem" maxlength="2" name="seg_proy_sem" value="<?php echo $produccion['0']->seg_proy_semana?>" class="number onlynumbers">
              </div>
            </div>
           </div>
           <div class="row">
            <div class="columns two">
              <label for="over_time"><?php echo lang('global.over_time') ?>:</label>
              <select name="over_time" style="display:block!important;">
              <?php for ($i=0; $i <= 24; $i++) { 
                if($produccion['0']->over_time ==$i){ ?>
                  <option selected value="<?=$i?>"><?=$i?></option> 
                <? }else{ ?>
                <option value="<?=$i?>"><?=$i?></option> 
              <?php } } ?>
              </select>
              <!--<input type="text" placeholder="insert text" id="" name="over_time" class="number" value="<?php echo $produccion['0']->over_time?>">-->
            </div>
            <?php if($unidad_inicio){ ?>
                <?php $fecha_actual=strtotime(date('Y-m-d'));
                      $fecha_unidad=strtotime($unidad_inicio['0']->fecha_inicio);
                 ?>
                <?php if($fecha_actual>=$fecha_unidad){ ?>
                    <div class="columns two">
                      <label><?php echo lang('global.capitulos_proyectados') ?></label>
                      <input type="text" name="capitulos_proy" value="<?php echo $produccion['0']->capitulos_proyectados ?>">  
                    </div>
                <?php }else{ ?>  
                  <input type="hidden" name="capitulos_proy" value="<?php echo $produccion['0']->capitulos_proyectados ?>">  
            <?php } ?>   
            <?php }else{ ?>  
                  <input type="hidden" name="capitulos_proy" value="<?php echo $produccion['0']->capitulos_proyectados ?>">  
            <?php } ?>
            <div class="column eight">
              <div class="row">
                <div class="column two" >
                  <label for="dias_sem"><?php echo lang('global.dias_trabajar') ?>:</label>
                  <select name="id_dias_grabacion" class="required" id="dias_sem" style="display:block!important;">
                    <option value="<?php echo $produccion['0']->dias_grabacion ?>"><?php echo $produccion['0']->dias_grabacion ?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                  </select>
                  <label class="error"><?php echo form_error('dias_sem'); ?></label>
                </div>
                <div class="columns ten" id="online_days">
                  <label for="days_online"><?php echo lang('global.dias_aire') ?>: </label>
                  <div class="columns"><label for="lunes" class="label_check"><input type="checkbox" name="lunes" id="lunes" value="1" <?php if($produccion['0']->lunes==1) {echo 'checked';}?>> <?php echo lang('global.lunes') ?></label></div>
                  <div class="columns"><label for="martes" class="label_check"><input type="checkbox" name="martes" id="martes" value="1" <?php if($produccion['0']->martes==1) {echo 'checked';} ?>> <?php echo lang('global.martes') ?></label></div>
                  <div class="columns"><label for="miercoles" class="label_check"><input type="checkbox" name="miercoles" id="miercoles" value="1"<?php if($produccion['0']->miercoles==1) {echo 'checked';} ?>> <?php echo lang('global.miercoles') ?></label></div>
                  <div class="columns"><label for="jueves" class="label_check"><input type="checkbox" name="jueves" id="jueves" value="1"<?php if($produccion['0']->jueves==1) {echo 'checked';}?>> <?php echo lang('global.jueves') ?></label></div>
                  <div class="columns"><label for="viernes" class="label_check"><input type="checkbox" name="viernes" id="viernes" value="1"<?php if($produccion['0']->viernes==1) {echo 'checked';} ?>> <?php echo lang('global.viernes') ?></label></div>
                  <div class="columns"><label for="sábado" class="label_check"><input type="checkbox" name="sabado" id="sabado" value="1"<?php if($produccion['0']->sabado==1) {echo 'checked';} ?>> <?php echo lang('global.sabado') ?></label></div>
                  <div class="columns" style="float:left;"><label for="domingo" class="label_check"><input type="checkbox" name="domingo" id="domingo" value="1"<?php if($produccion['0']->domingo==1) {echo 'checked';} ?>> <?php echo lang('global.domingo') ?></label></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="align_left columns twelve">
              <a href="#" class="button secondary cancel_edit_hito"><?php echo lang('global.cancelar') ?></a>
              <input type="submit" class="button" value="<?php echo lang('global.guardar') ?>">
            </div>
          </div>
        <?php echo form_close(); ?> 
      </div>
    </li>
    <li>
      <div class="title">
        <h5><?php echo lang('global.ver_producto_user') ?></h5>
      </div>
      <div class="content">
        <?php echo form_open($idioma.'plan_produccion/actualizar_user');?>
        <div class="row" id="infoUnidades">
          <table class="tabla_info">
            <tr>
              <td><label><?php echo lang('global.productor_ejecutivo') ?>:</label><?php if($ejecutivo){?><?php echo $ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido; }else{ echo '-';}?></td>
              <td><label><?php echo lang('global.productor_general') ?>:</label><?php if($productor_general){?><?php echo $productor_general['0']->nombre.' '.$productor_general['0']->apellido;}else{ echo '-';} ?></td>
              <td><label><?php echo lang('global.productor') ?>:</label><?php if($productor){ ?><?php echo $productor['0']->nombre.' '.$productor['0']->apellido;}else{ echo '-';} ?></td>
              <td><label><?php echo lang('global.numero_unidades') ?>:</label><?php echo $produccion['0']->numero_unidades ?></td>
            </tr>
          </table>
          <div class="clr"></div>
          <div class="row">
          <?php $cont=1; ?>
          <?php $counter = sizeof($unidad);  foreach ($unidad as $u) { ?>
          <?php if ($counter == 1) {
            $width = '100%';
          } elseif ($counter == 2) {
            $width = '50%';
          } elseif ($counter == 3) {
            $width = '33.33333%';
          } elseif ($counter == 4) {
            $width = '25%';
          } elseif ($counter == 5) {
            $width = '20%';
          } ?>

            <div class="column" style="width:<?php echo $width; ?>;">
              <table class="tabla_info">
                <tr>
                  <td>
                    <label><?php echo lang('global.director_unidad') ?> <?php echo $cont; ?> </label>
                    <?php $director_u = $this->model_produccion->user_id($u['id_director']); ?>
                       <?php if($director_u){ ?>
                       <?php echo $director_u['0']->nombre.' '.$director_u['0']->apellido; ?>
                    <?php }else{ ?>
                         -
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php  $script_u=$this->model_produccion->user_id($u['id_script']); ?>
                    <label><?php echo lang('global.script_unidad') ?><?php echo $cont; ?> </label>
                    <?php if($script_u){ ?>
                    <?php echo $script_u['0']->nombre.' '.$script_u['0']->apellido; ?>
                    <?php }else{ ?>
                         -
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label><?php echo lang('global.fecha_inicio_unidad') ?> <?php echo $cont; ?> </label><?php if($u['fecha_inicio']){?><?php echo strftime('%d-%b-%Y',strtotime($u['fecha_inicio'])); }else{ echo '-';} ?>

                  </td>
                </tr>
              </table>
            </div>
          <?php $cont++; } ?>
          </div>
          <div class="row">
            <div class="columns twelve tabel">
              <table class="tabla_info">
                <tr>
                  <td>
                    <label><?php echo lang('global.usuarios_asigandos') ?></label>
                    <?php if($usuarios_producion) { ?>
                    <div class="normal_table">
                    <table class="" >
                      <thead>
                        <tr>
                          <th  style="width:250px;"><?php echo lang('global.usuario') ?></th>
                          <th style="text-align: left;text-indent: 30px;"><?php echo lang('global.rol') ?></th>
                        </tr>
                      </thead>
                      </tbody>
                          <!--  <?php $rol_temp = "";
                                 foreach ($usuarios_producion as $u) { ?>
                                 <tr>
                                    <td >
                                   <?php if($u['descripcion']!=$rol_temp){ ?>
                                     <label><?=$u['descripcion']?></label>
                                     <?php $rol_temp= $u['descripcion'];  ?>
                                   <?php } else {?> 
                                      <?php echo '<strong>'.$u['nombre'].' '.$u['apellido'].'</strong>'?>
                                   <?php } ?>
                                   </td>
                                 </tr>
                            <?php } ?>
                          -->
                       
                          <?php foreach ($usuarios_producion as $u) { ?>
                           <tr>
                           <td><?= $u['nombre'].' '.$u['apellido'] ?></td>
                           <td><?=$u['descripcion']?></td>
                             </tr> 
                          <?php } ?> 
                    
                      </tbody>
                  </table>
                </div>
                    <?php } else { ?>
                        <?php echo lang('global.no_hay_usuarios_asignados_ala_produccion') ?>
                    <?php } ?>
                  </td>
                </tr>
                
              </table>
            </div>
          </div>
          <?php if($usuario_permisos!="read" and $produccion['0']->estado!=2){ ?>
          <div class="columns twelve">
             <a href="#" class="button edit_user_unidad"><?php echo lang('global.editar') ?></a>
          </div>
          <?php } ?>
        </div>
        <?php echo form_close() ?>

        <!--//////////////////////////////////////////////////////////////////-->

        <div class="content_edit" style="display:none">
        <?php echo form_open($idioma.'plan_produccion/actualizar_user','onSubmit="return fecha_inicio_grabacion()"');?>
          <div class="row field_form">
            <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
            <div class="columns three">
              <label for="productor_ejecutivo"><?php echo lang('global.productor_ejecutivo') ?>:</label>
              <select name="productor_ejecutivo" id="productor_ejecutivo" style="display:block!important;">
                <?php if ($ejecutivo['0']->id) { ?>
                  <option value="<?php echo $ejecutivo['0']->id ?>" selected><?php echo $ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido ?></option>
               <?php } else { ?>
                   <option value="null" selected><?php echo lang('global.no_hay_productor_ejecutivo') ?></option>
               <?php } ?>
                
                <?php foreach ($user as $e) { ?> 
                      <?php if($ejecutivo['0']->id!=$e['id']) {?>
                          <option value="<?php echo $e['id'] ?>"><?php echo $e['nombre'].' '.$e['apellido']?></option>
                      <?php } ?>
                <?php } ?>
              </select>
              <?php echo form_error('productor_ejecutivo'); ?>
              <br>
            </div>
            <div class="columns three">
              <label for="productor_general"><?php echo lang('global.productor_general') ?>:</label>
              <select name="productor_general" id="productor_general" style="display:block!important;">
                <?php if ($productor_general and $productor_general['0']->id) { ?>
                  <option value="<?php echo $productor_general['0']->id ?>" selected><?php echo $productor_general['0']->nombre.' '.$productor_general['0']->apellido; ?></option>
                <?php } else { ?>
                   <option value="null" selected><?php echo lang('global.no_hay_productor_general') ?></option>
                <?php } ?>
                <?php foreach ($user as $p) { ?>
                      <?php if($productor_general and $productor_general['0']->id!=$p['id']) { ?>
                          <option value="<?php echo $p['id'] ?>"><?php echo $p['nombre'].' '.$p['apellido']?></option>
                      <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="columns three">
              <label for="productor"><?php echo lang('global.productor') ?>:</label>
              <select name="productor" id="productor" style="display:block!important;">
                <?php if ($productor['0']->id) { ?>
                  <option value="<?php echo $productor['0']->id ?>" selected><?php echo $productor['0']->nombre.' '.$productor['0']->apellido ?></option>
                <?php } else { ?>
                  <option value="null" selected><?php echo lang('global.no_hay_productor') ?></option>
                <?php } ?>
                <?php foreach ($user as $p) { ?>
                     <?php if($productor['0']->id!=$p['id']){ ?>
                      <option value="<?php echo $p['id'] ?>"><?php echo $p['nombre'].' '.$p['apellido']?></option>
                      <?php } ?>
                <?php } ?>
              </select>
              <?php echo form_error('productor'); ?>
            </div>
            <div class="columns three">
              <label for="unidades"><?php echo lang('global.numero_unidades') ?>:</label>
                <select name="unidades" id="unidades" style="display:block!important;">
                <option value="<?php echo $produccion['0']->numero_unidades ?>" selected><?php echo $produccion['0']->numero_unidades ?></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
              <?php echo form_error('unidades'); ?>
            </div>
          </div>



          <?php $i=1;$cont=0;

          $cont2=$unidad[sizeof($unidad)-1]['numero']; 
          while($i<=5) { ?>
          <div class="field_form field_form<?php echo $i; ?>" style="<?php if($i>$produccion['0']->numero_unidades){ echo 'display:none';}?>">
            <div class="row director_item" id="row_<?php if(isset($unidad[$cont])){ echo $unidad[$cont]['id'];}?>">
              <div class="columns one"><div class="icon left">U
                    <?php if(isset($unidad[$cont])){ ?>
                    <?php echo $unidad[$cont]['numero']; ?>

                    <a <?php if($i==1){ echo 'id="first_unity"';}  ?> href="#" class="delete_unity" data-idunidad="<?=$unidad[$cont]['id']?>"><?php echo lang('global.eliminar') ?></a>
                    <?php } else { ?>
                    <?php echo ++$cont2; ?>
                    <?php } ?>
              </div></div>
              <div class="columns eleven">
                <div class="row">
                  <div class="columns four">
                    <label for="director_<?php echo $i; ?>">director:</label>
                    <?php if(isset($unidad[$cont]['id'])){ ?>
                      <?php $id_unidad=$unidad[$cont]['id'] ?>
                      <?php $plan_diario_id_unida=$this->model_plan_produccion->plan_diario_id_unida($id_unidad); ?>
                          <?php if($plan_diario_id_unida){ ?>
                          <input type="hidden" class="plan_diario_director" value="1">
                          <?php }else{ ?>
                          <input type="hidden" class="plan_diario_director" value="0">
                          <?php } ?>
                      <input type="hidden" name="id_unida_<?php echo $i; ?>" value="<?php echo $id_unidad ?>">
                    <?php }else{ ?>
                       <input type="hidden" name="id_unida_<?php echo $i; ?>" value="null">  
                    <?php  } ?>
                    <?php if(isset($unidad[$cont])){ ?>
                    <?php $director_u = $this->model_produccion->user_id($unidad[$cont]['id_director']); ?>
                    <?php } else { ?>
                    <?php $director_u =null?>
                    <?php } ?>
                    <select name="director_<?php echo $i; ?>" id="director_<?php echo $i; ?>" class="director" <?php if($director_u){ ?> data-user="<?php echo $director_u['0']->nombre.' '.$director_u['0']->apellido ?>" data-userid="<?php echo $director_u['0']->id ?>" <?php }else{ ?> data-user="0" <?php } ?> style="display:block!important;">
                      <?php if($i<=$produccion['0']->numero_unidades) { ?>
                            <?php $director_u = $this->model_produccion->user_id($unidad[$cont]['id_director']); ?>
                            <?php if($director_u){ ?>
                              <option value="<?php echo $director_u['0']->id ?>" selected><?php echo $director_u['0']->nombre.' '.$director_u['0']->apellido ?></option>
                            <?php } else{ ?>
                               <option value="null" selected>NO HAY DIRECTOR ASIGNADO A ESTA UNIDAD</option>
                            <?php } ?>
                            <?php if($director_unidad['0']!=null){ ?>
                              <?php foreach ($director_unidad as $d) { ?>
                                  <?php if($director_u['0']->id!=$d['id']) { ?>
                                    <option value="<?php echo $d['id'] ?>"><?php echo $d['nombre'].' '.$d['apellido']?></option>
                                 <?php } ?> 
                              <?php } ?>
                            <?php } ?>
                      <?php } else { ?>
                            <option value="null" selected>Select option</option>
                            <?php foreach ($director_unidad as $d) { ?>
                              <option value="<?php echo $d['id'] ?>"><?php echo $d['nombre'].' '.$d['apellido']?></option>
                            <?php } ?>
                      <?php } ?>
                    </select> 
                    <?php echo form_error('director_<?php echo $i; ?>'); ?>

                  </div>
                  <div class="columns four">
                    <label for="script_<?php echo $i; ?>"><?php echo lang('global.asignar_script') ?>:</label>
                    <?php $plan_diario_id_unida=$this->model_plan_produccion->plan_diario_id_unida($id_unidad); ?>
                          <?php if($plan_diario_id_unida){ ?>
                          <input type="hidden" class="plan_diario_script" value="1">
                          <?php }else{ ?>
                          <input type="hidden" class="plan_diario_script" value="0">
                          <?php } ?>
                          <?php if(isset($unidad[$cont])){ ?>
                              <?php  $script_u=$this->model_produccion->user_id($unidad[$cont]['id_script']); ?> 
                          <?php }else{ ?>
                             <?php $script_u=null; ?>  
                          <?php } ?>
                      <select name="script_<?php echo $i; ?>" id="script_<?php echo $i; ?>" class="script" style="display:block!important;">
                      <?php if($i<=$produccion['0']->numero_unidades) { ?>
                        <?php  $script_u=$this->model_produccion->user_id($unidad[$cont]['id_script']); ?>
                            <?php if($script_u){ ?>
                                <option value="<?php echo $script_u['0']->id ?>" selected><?php echo $script_u['0']->nombre.' '.$script_u['0']->apellido ?></option>
                             <?php } else { ?>
                                 <option value="null" selected><?php echo lang('global.no_hay_script') ?> </option> 
                             <?php } ?>
                            <?php foreach ($script as $s) { ?>
                                <?php if($script_u['0']->id!=$s['id']){ ?>
                                    <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'].' '.$s['apellido']?></option>
                                 <?php } ?>
                            <?php } ?>
                      <?php } else { ?>
                          <option value="null" selected>Select option</option>
                          <?php foreach ($script as $s) { ?>
                            <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'].' '.$s['apellido']?></option>
                          <?php } ?>
                       <?php } ?>    
                    </select>
                    <?php echo form_error('script_<?php echo $i; ?>'); ?>
                  </div>
                  <div class="columns four">
                    <label for="date_start<?php echo $i; ?>"><?php echo lang('global.fecha_inicio') ?>:</label>
                    <?php if($i<=$produccion['0']->numero_unidades){ ?>
                        <?php if($unidad[$cont]['fecha_inicio']){
                        $fecha_unidad=strftime('%d-%b-%Y',strtotime($unidad[$cont]['fecha_inicio']));
                        }else {
                          $fecha_unidad=null;
                        }
                      } else{
                          $fecha_unidad=null;
                       }  
                    ?>
                    <?php $cambiar_unidad=$this->model_plan_produccion->plan_diario_id_unida($id_unidad); 
                    if($cambiar_unidad){ ?>

                     <?php $fecha=strftime('%d-%b-%Y',strtotime($cambiar_unidad['0']->fecha_inicio)) ?>

                     <?php if ($cambiar_unidad['0']->fecha_inicio) {$fecha=strftime('%d-%b-%Y',strtotime($cambiar_unidad['0']->fecha_inicio));}?>

                        <script type="text/javascript">
                          $(document).ready(function() {
                            var fecha =new Date("<?php if ($fecha_unidad) {echo $fecha_unidad;} else {}?>");
                             var id_unida="#date_start<?php echo $i; ?> ";
                             if (fecha) {$(id_unida).datepicker( "option", "maxDate",fecha);};                             
                             });
                        </script>
                   <?php } ?>
                    <input type="text" class="start_unity" placeholder="" id="date_start<?php echo $i; ?>" name="date_start<?php echo $i; ?>" value="<?php echo $fecha_unidad;?>">
                    <?php echo form_error('date_start<?php echo $i; ?>'); ?>
                  </div>
                </div>
              </div>
            </div>
            </div> 
          <?php   $i++; $cont++;} ?>
          <div class="row field_form">
            <div class="align_left">
              <a class="button secondary btn_cancel_unidades"><?php echo lang('global.cancelar') ?></a>
              <input type="submit" class="button" value="<?php echo lang('global.guardar') ?>">
              <br><br>
            </div>
          </div>
        <?php echo form_close(); ?>

        <!--SECCION PARA ASIGNAR USUARIOS A LA PRODUCCION-->
        <?php if($usuario_permisos!="write_2" AND $usuario_permisos!="read"){ ?>
        <div class="row">
          <div class="columns six">
            <form class="custom">
              <label><?php echo lang('global.seleccione_tipo_usuario') ?></label>
              <select name="roles" id="tipo_user" style="display:block!important;">
                <?php foreach ($roles as $r) { ?>
                  <option value="<?php echo $r['id']?>"><?php echo $r['descripcion'] ?></option>
                <?php } ?>
              </select>
            </form>
          </div>
          </br>
          <div class="columns six">
            <input type="text" id="buscar_usuarios_tabla" idproduccion="" class="search_input">
          </div>
        </div>
        <br>
        <div class="row ">
          <div class="columns six">
            <div id="loadElements" style="height: 204px;">
            </div>
            <div id="sortable_content">
              <div class="scroller">
                  <div class="normal_table">
                    <table class="users_table">
                      <thead>
                        <tr>
                          <th class="user"><?php echo lang('global.usuario') ?></th>
                          <th class="role"><?php echo lang('global.rol') ?></th>
                          <th class="isactive"><?php echo lang('global.activo') ?></th>
                          <th class="actions"><?php echo lang('global.acciones') ?></th>
                        </tr>
                      </thead>
                      <tbody  id="table_1" class="connectedSortable">

                        <input type="hidden" name="id_produccion" id="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                        <?php if($user_has_produccion) { ?>
                            <?php  foreach ($user_has_produccion as $u) { ?>
                              <tr class="sort_left">
                                <input type="hidden" name="id_user" id="id_user"value="<?php echo $u['id_user'] ?>">
                                <input type="hidden" name="rol" id="rol" value="<?php echo   $u['id_rol'] ?>">
                                <td><?php echo $u['nombre'].' '.$u['apellido'] ?></td>
                                <td><?php echo $u['descripcion']?></td>
                                <td class="role_hiden" style="text-align:center;"><input type="checkbox" id="domingo"> </td>
                                <td class="actions_hiden" style="text-align: center;"> <a class="delete" ><?php echo lang('global.eliminar') ?></a> <a class="add" ><?php echo lang('global.agregar') ?></a> </td>
                              </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="hidden_row"><td colspan="4"><?php echo lang('global.no_hay_usuarios_rol') ?></td></tr>    
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
          </div>
          

        
          <div class="columns six">
            <div id="sortable_content">
              <div class="scroller">
                  <div class="normal_table">
                    <table id="usersTable" class="tablesorter">
                      <thead>
                        <tr>
                          <th class="user"><?php echo lang('global.usuario') ?></th>
                          <th class="role"><?php echo lang('global.rol') ?></th>
                          <th class="isactive"><?php echo lang('global.activo') ?></th>
                          <th class="actions"><?php echo lang('global.acciones') ?></th>
                        </tr>
                      </thead>
                      <tbody  id="table_2" class="ui-selectable connectedSortable">
                        <input type="hidden" name="id_produccion" id="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                        <?php if($usuarios_producion) { ?>
                            <?php foreach ($usuarios_producion as $u) { $id_users.=$u['id_user'];?>
                              <tr class="sort_right">
                                <input type="hidden" name="id_user" id="id_user"value="<?php echo $u['id_user'] ?>">
                                <input type="hidden" name="rol" id="rol" value="<?php echo   $u['id_rol'] ?>">
                                <td><?php echo $u['nombre'].' '.$u['apellido'] ?></td>
                                <td><?php echo $u['descripcion']?></td>
                                <td class="role_hiden" style="text-align:center;"><input type="checkbox" id="domingo"> </td>
                                <td class="actions_hiden" style="text-align: center;"> <a class="delete" ><?php echo lang('global.eliminar') ?></a> <a class="add" ><?php echo lang('global.agregar') ?></a></td>
                              </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="hidden_row"><td colspan="4"><?php echo lang('global.no_hay_usuarios_rol') ?></td></tr>    
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                    <input type="hidden" id="id_users_table" value="<?=$id_users?>">
              </div>
             </div>
          </div>
        </div>
        <?php }?>
        <!--FIN SECCION PARA ASIGNAR USUARIOS A LA PRODUCCION-->
      </div>
    </li>
    <li>
      <div class="title">
        <h5><?php echo lang('global.ver_indicadores') ?></h5>
      </div>
      <div class="content">
        <div class="row indicadores_box" id="indicadoresInfo">
          <?php echo form_open('') ?>
            <div class="column twelve">
              <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.indicadores_produccion') ?></h5>
              <table class="tabla_info">
                <tr>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="amount">% <?php echo lang('global.interior') ?>:</label>
                        % <?php echo $produccion['0']->produccion_interior ?>
                      </div>
                      <div class="column six align_center">
                        <label for="amount">% <?php echo lang('global.exterior') ?>:</label>
                        % <?php echo $produccion['0']->produccion_exterior ?>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="locacion">% <?php echo lang('global.locacion') ?>:</label>
                        % <?php echo $produccion['0']->locacion ?>
                      </div>
                      <div class="column six align_center">
                        <label for="estudio">% <?php echo lang('global.estudio') ?>:</label>
                        % <?php echo $produccion['0']->estudio ?>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="dia">% <?php echo lang('global.dia') ?>:</label>
                        % <?php echo $produccion['0']->dia?>
                      </div>
                      <div class="column six align_center">
                        <label for="noche">% <?php echo lang('global.noche') ?>:</label>
                        % <?php echo $produccion['0']->noche ?>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="dia"><?php echo lang('global.protagonistas_produccion') ?>:</label>
                        <?php echo $produccion['0']->protagonistas_produccion?>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>

                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="dia"><?php echo lang('global.monto_min_p') ?>:</label>
                        (pesos)$<?php echo number_format($produccion['0']->monto_figurante_extra)?>
                      </div>
                    
                      <div class="column six align_center">
                        <label for="dia"><?php echo lang('global.monto_min_d') ?>:</label>
                        (dolar) $<?php echo number_format($produccion['0']->monto_figurante_extra_dolar)?>
                      </div>
                    </div>
                  </td>
                  <td >
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="dia"><?php echo lang('global.escenas_libretos') ?>:</label>
                        <?php echo $produccion['0']->escenas_libretos?>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column four align_center">
                        <label for="noche"><?php echo lang('global.evento_pequeno') ?>:</label>
                        <?php echo $produccion['0']->evento_pequeno ?>
                      </div>
                      <div class="column four align_center">
                        <label for="dia"><?php echo lang('global.evento_mediano') ?>:</label>
                        <?php echo $produccion['0']->evento_mediano?>
                      </div>
                      <div class="column four align_center">
                        <label for="dia"><?php echo lang('global.evento_grande') ?>:</label>
                        <?php echo $produccion['0']->evento_grande?>
                      </div>
                    </div>
                  </td>
                  <td colspan="3">
                    <div class="row">
                      <div class="column four align_center">
                        <label for="noche"><?php echo lang('global.presupuesto_protagonista') ?>:</label>
                        $<?php echo number_format($produccion['0']->presupuesto_principales); ?>
                      </div>
                      <div class="column four align_center">
                        <label for="dia"><?php echo lang('global.presupuesto_reparto') ?>:</label>
                        $<?php echo number_format($produccion['0']->presupuesto_secundarios)?>
                      </div>
                      <div class="column four align_center">
                        <label for="dia"><?php echo lang('global.presupuesto_figurante') ?>:</label>
                        $<?php echo number_format($produccion['0']->presupuesto_figurante)?>
                      </div>
                    </div>
                  </td>

                </tr>
              </table>
            </div>
            <div class="clr"></div>
            <div class="column twelve centered">
              <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.indicadores_lirebtos') ?></h5>
              <table class="tabla_info">
                <tr>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.max_locaciones') ?>:</label>
                        <span><?php echo $produccion['0']->numero_locaciones ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.max_sets') ?>:</label>
                        <span><?php echo $produccion['0']->numero_set ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.protagonista') ?>:</label>
                        <span><?php echo $produccion['0']->numero_protagonistas ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.reparto') ?>:</label>
                        <span><?php echo $produccion['0']->numero_repartos ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.figurante') ?>:</label>
                        <span><?php echo $produccion['0']->numero_figurantes ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.extra') ?>:</label>
                        <span><?php echo $produccion['0']->numero_extras ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.vehiculos') ?>:</label>
                        <span><?php echo $produccion['0']->numero_vehiculos ?></span>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="noche"><?php echo lang('global.locaciones_nuevas') ?>:</label>
                        <?php echo number_format($produccion['0']->locaciones_nuevas); ?>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="noche"><?php echo lang('global.paginasLibretos') ?>:</label>
                        <?php echo number_format($produccion['0']->paginasPorLibretos); ?>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="row">
              <?php if($usuario_permisos!="read" and $produccion['0']->estado!=2){ ?>
              <div class="columns twelve align_left">
                <input type="submit" value="<?php echo lang('global.editar') ?>" class="button edit_indicador">
              </div>
              <?php } ?>
              <br>
            </div>
          <?php echo form_close(); ?>
        </div>      
        <div id="indicadores_form" style="display:none">
          <script type="text/javascript">
          function numbersonly(myfield, e, dec){
          var key;
          var keychar;

          if (window.event)
             key = window.event.keyCode;
          else if (e)
             key = e.which;
          else
             return true;
          keychar = String.fromCharCode(key);

          // control keys
          if ((key==null) || (key==0) || (key==8) || 
              (key==9) || (key==13) || (key==27) )
             return true;

          // numbers
          else if ((("0123456789").indexOf(keychar) > -1))
             return true;

          // decimal point jump
          else if (dec && (keychar == "."))
             {
             myfield.form.elements[dec].focus();
             return false;
             }
          else
             return false;
          }

          </script>

          <div class="row indicadores_box">
            <?php echo form_open($idioma.'plan_produccion/guardar_indicadores', 'id="indicadores_form"') ?>

            <div class="column twelve">
              <div class="column twelve centered">
              <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.indicadores_produccion') ?></h5>
              <table class="tabla_info">
                <tr>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="amount">% <?php echo lang('global.interior') ?>:</label>
                        <div class="box_blue">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="rang_prod_amount1" id="rang_prod_amount1" value="<?php echo $produccion['0']->produccion_interior ?>" />
                        </div>
                      </div>
                      <div class="column six align_center">
                        <label for="amount">% <?php echo lang('global.exterior') ?>:</label>
                        <div class="box_blue">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="rang_prod_amount2" id="rang_prod_amount2" value="<?php echo $produccion['0']->produccion_exterior ?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="locacion">% <?php echo lang('global.locacion') ?>:</label>
                        <div class="box_blue">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="locacion" id="locacion" value="<?php echo $produccion['0']->locacion ?>" />
                        </div>
                      </div>
                      <div class="column six align_center">
                        <label for="estudio">% <?php echo lang('global.estudio') ?>:</label>
                        <div class="box_blue">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="estudio" id="estudio" value="<?php echo $produccion['0']->estudio ?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="dia">% <?php echo lang('global.dia') ?>:</label>
                        <div class="box_blue">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="dia" id="dia" value="<?php echo $produccion['0']->dia?>"/>
                        </div>
                      </div>
                      <div class="column six align_center">
                        <label for="noche">% <?php echo lang('global.noche') ?>:</label>
                        <div class="box_blue">
                          % <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="noche" id="noche" value="<?php echo $produccion['0']->noche ?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="escenas_libretos"><?php echo lang('global.protagonistas_produccion') ?>:</label>
                        <div class="box_blue">
                          <input type="text"  onKeyPress="return numbersonly(this, event)" name="protagonistas_produccion" id="protagonistas_produccion" value="<?php echo $produccion['0']->protagonistas_produccion ?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>

                  <td colspan="2">
                    <div class="row">
                      <div class="column six align_center">
                        <label for="escenas_libretos"><?php echo lang('global.monto_min_p') ?> (pesos):</label>
                        <div class="box_blue">
                           $<input type="text" onkeypress="return numbersonly(this, event)" name="monto_figurante_extra" id="monto_figurante_extra" value="<?php echo $produccion['0']->monto_figurante_extra ?>">
                        </div>
                      </div>
                   
                      <div class="column six align_center">
                        <label for="escenas_libretos"><?php echo lang('global.monto_min_d') ?> (dolar):</label>
                        <div class="box_blue">
                          $<input type="text" onkeypress="return numbersonly(this, event)" name="monto_figurante_extra_dolar" id="monto_figurante_extra_dolar" value="<?php echo $produccion['0']->monto_figurante_extra_dolar ?>">
                        </div>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for="escenas_libretos"><?php echo lang('global.escenas_libretos') ?>:</label>
                        <div class="box_blue">
                          <input type="text" maxlength="3" size="3" onKeyPress="return numbersonly(this, event)" name="escenas_libretos" id="escenas_libretos" value="<?php echo $produccion['0']->escenas_libretos ?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="2">
                    <div class="row">
                      <div class="column four align_center">
                        <label for="evento_pequeno"><?php echo lang('global.evento_pequeno') ?>:</label>
                            <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_pequeno" id="evento_pequeno" value="<?=$produccion['0']->evento_pequeno?>"/>
                      </div>
                      <div class="column four align_center">
                        <label for="evento_mediano"><?php echo lang('global.evento_mediano') ?>:</label>
                          <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_mediano" id="evento_mediano" value="<?=$produccion['0']->evento_mediano?>"/>
                      </div>
                      <div class="column four align_center">
                        <label for="evento_grande"><?php echo lang('global.evento_grande') ?>:</label>
                          <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_grande" id="evento_grande" value="<?=$produccion['0']->evento_grande?>"/>
                    </div>
                  </td>
                  <td colspan="3">
                    <div class="row">
                      <div class="column four align_center">
                        <label for="presupuesto_principales"><?php echo lang('global.presupuesto_protagonista') ?>:</label>
                        <div class="box_blue">
                          $<input type="text" onKeyPress="return numbersonly(this, event)" name="presupuesto_principales" id="presupuesto_principales" value="<?php echo $produccion['0']->presupuesto_principales ?>"/>
                        </div>
                      </div>
                      <div class="column four align_center">
                        <label for="presupuesto_secundarios"><?php echo lang('global.presupuesto_reparto') ?>:</label>
                        <div class="box_blue">
                          $<input type="text" onKeyPress="return numbersonly(this, event)" name="presupuesto_secundarios" id="presupuesto_secundarios" value="<?php echo $produccion['0']->presupuesto_secundarios?>"/>
                        </div>
                      </div>
                      <div class="column four align_center">
                        <label for="presupuesto_secundarios"><?php echo lang('global.presupuesto_figurante') ?>:</label>
                        <div class="box_blue">
                          $<input type="text" onKeyPress="return numbersonly(this, event)" name="presupuesto_figurante" id="presupuesto_figurante" value="<?php echo $produccion['0']->presupuesto_figurante?>"/>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="clr"></div>
            <div class="column twelve centered">
              <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.indicadores_libretos') ?></h5>
              <table class="tabla_info">
                <tr>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.max_locaciones') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3" id="rang_cap_1" onKeyPress="return numbersonly(this, event)" name="rang_cap_1" value="<?php echo $produccion['0']->numero_locaciones ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.max_sets') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3" id="rang_cap_2" onKeyPress="return numbersonly(this, event)" name="rang_cap_2" value="<?php echo $produccion['0']->numero_set ?>">
                        </div>    
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.protagonista') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="numero_protagonistas" onKeyPress="return numbersonly(this, event)" name="numero_protagonistas" value="<?php echo $produccion['0']->numero_protagonistas ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.reparto') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="numero_repartos" onKeyPress="return numbersonly(this, event)" name="numero_repartos" value="<?php echo $produccion['0']->numero_repartos ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.figurante') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="numero_figurantes" onKeyPress="return numbersonly(this, event)" name="numero_figurantes" value="<?php echo $produccion['0']->numero_figurantes ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.extra') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="numero_extras" onKeyPress="return numbersonly(this, event)" name="numero_extras" value="<?php echo $produccion['0']->numero_extras ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""># <?php echo lang('global.vehiculos') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="numero_vehiculos" onKeyPress="return numbersonly(this, event)" name="numero_vehiculos" value="<?php echo $produccion['0']->numero_vehiculos ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.locaciones_nuevas') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="locaciones_nuevas" onKeyPress="return numbersonly(this, event)" name="locaciones_nuevas" value="<?php echo $produccion['0']->locaciones_nuevas ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="12.5%">
                    <div class="row">
                      <div class="column twelve align_center">
                        <label for=""><?php echo lang('global.paginasLibretos') ?>:</label>
                        <div class="box_gray">
                          <input type="text" maxlength="3" size="3"  id="paginasPorLibretos" onKeyPress="return numbersonly(this, event)" name="paginasPorLibretos" value="<?php echo $produccion['0']->paginasPorLibretos ?>">
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="row">
              <div class="columns twelve align_left">
                <br>
                <a class="button secondary cancelIndicadores"><?php echo lang('global.cancelar') ?></a>
                <input type="submit" value="<?php echo lang('global.guardar') ?>" class="button">
              </div>
            </div>
          </div>  
          <?php echo form_close(); ?>
        </div>
      </div>
    </li>
  </ul>
</div>

<?php if($usuario_permisos!="read" and $produccion['0']->estado!=2){
  $class_box = "open_box";
}else{
  $class_box = "null_box";
}?>
<!-- <div class="call_to_action align_right"> -->
<nav>
  <input type="hidden" name="id_produccion_estado" id="id_produccion_estado" value="<?php echo $produccion[0]->id_produccion ?>">
    <ul class="nav_post"> 
      <?php if($produccion[0]->estado!=2 AND $usuario_permisos!="read"){?>
      <li>
        <a href="#" id="produccion_estado" class="buttons icon ">
          <span></span>
          <?php echo lang('global.cerrar_produccion') ?>
        </a>
      </li>
      <?php } ?>
      <li>
        <a href="<?php echo base_url($idioma.'excel/plan_produccion/'.$produccion['0']->id_produccion) ?>" target="_blank" class="buttons icon icon_save">
          <span></span>
          <?php echo lang('global.guardar') ?>
        </a>
      </li>
      <li>
        <a href="<?php echo base_url($idioma.'pdf/pfd_plan_produccion/'.$produccion['0']->id_produccion) ?>" target="_blank" class="buttons icon icon_print">
          <span></span>
          <?php echo lang('global.imprimir') ?>
        </a>
      </li>
     <!-- <li>
        <a href="#" class="buttons help_button help_planProd">
          <span class="open"></span>
          <?php echo lang('global.ayuda') ?>
        </a>
      </li> -->
      <li>
        <a href="#" class="buttons full_screen">
          <span class="open"></span>
          <span class="close"></span>
           <?php echo lang('global.full_screen') ?>
        </a>
      </li>  
    </ul>
</nav>

<div id="inner_content">
  <div class="row">
    <div class="columns twelve"><?php echo $msg; ?></div>
  </div>
  <div class="top_page top_page_planproduccion row">
    <?php if(form_error('valor')){ ?>
      <div class="columns twelve">
        <div class="alert-box alert">
          <?php echo form_error('valor'); ?>
          <a href="" class="close">&times;</a>
        </div>
      </div>
    <?php } ?>
<div class="producciones">
<table class="four table_plan_prod red_box" id="capitules_green_box" cellpadding="0" cellspacing="0">
  <tr>
    <td><p><?php echo lang('global.libretos_entregados') ?>:</p></td>
    <td><h3><?=sizeof($capitulos)?>/<?= $produccion['0']->numero_capitulo ?></h3></td>
    <td><p><?php echo lang('global.diferencia') ?>: <span id="difference_capitules"></span></p></td>
  </tr>
</table>

<table class="four table_plan_prod red_box" id="minutes_green_box" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <p>
        <?php echo lang('global.minutos_producidos') ?>:
      </p>
    </td>
    <td>
      <h3>
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
      <?=$minutos_producidos?>/<?=($m_total);?>
      </h3>
    </td>
    <td><p><?php echo lang('global.diferencia') ?>: <span id="difference_minutes"></span></p></td>

  </tr>
  <table class="three table_plan_prod green_box" id="minutes_green_box" cellpadding="0" cellspacing="0">
    <tr>
      <td style="background-color:#10AD9B">
        <p><?php echo lang('global.escena_por_minuto') ?>:</p>
      </td>
      <td style="background-color:#10AD9B">
          <h3>
             <?php
             if($produccion['0']->minuto_capitulo and $produccion['0']->minuto_capitulo!=0 and $produccion['0']->escenas_libretos and $produccion['0']->escenas_libretos!=0){ 
                echo $escena_porminuto=$escena_por_minuto=number_format((float)($produccion['0']->escenas_libretos)/$produccion['0']->minuto_capitulo, 2, '.', ''); 
             }else{
                echo $escena_porminuto=$escena_por_minuto='0';
             }
             ?>
           </h3>
         </td>
    </tr>
  </table>

</table>
</div>

  </div>
  <div id="scroll">
    <div class="table_general" style="width: 120% !important;">
      <table class="main" style="width:2255px">
        <thead>
          <tr>
            <td class="td_weeknumber">&nbsp;</td>
            <td class="td_unit"><?php echo lang('global.unidad') ?></td>
            <td class="td_week"><?php echo lang('global.semana') ?></td>
            <td class="td_write_cap"><?php echo lang('global.escritura_libretos') ?></td>
            <td class="td_mproy"><?php echo lang('global.min_proy') ?></td>
            <td class="td_mproy"><?php echo lang('global.capitulos') ?></td>
            <td class="td_min_really"><?php echo lang('global.minutos_reales') ?></td>
            <td class="td_dif"><?php echo lang('global.diferencia') ?></td>
            <td class="td_pronostico"><?php echo lang('global.pronostico_escenas') ?></td>
            <td class="td_comments"><?php echo lang('global.comentarios') ?></td>
          </tr>
        </thead>
        <tbody>
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
                      <span class="has-tip tip-centered-top" title="Unidad <?=$unidad[$m]['numero']?>">
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
                  <td width="20%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dias_trabajar') ?>">#D</span></td>
                  <td width="40%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.inicio_semana') ?>">inicio</span></td>
                  <td width="40%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.fin_semana') ?>">fin</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_programados_semana') ?>">PRO</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos') ?>">AC.P</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_entregados') ?>">ENT</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos') ?>">AC.E</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="AC.E – AC.P">dIF</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libretos_desglosados_semanal') ?>">DES</span></td>
                  <td width="14.2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_libretos_desglosados') ?>">AC.D</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.minutos_proyectados_semanal') ?>">SEM</span></td>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_minutos_proyectados') ?>">acm</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.capitulos_proyectados_semanal') ?>">PROY</span></td>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.capitulos_entregados_semanal') ?>">Entr</span></td>
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
                  <?php for ($z=0; $z <count($unidad); $z++) { ?>
                  <td width="<?=$width?>"><span class="has-tip tip-centered-top" title="<?php echo lang('global.minutos_reales_semanales_unidad') ?> <?=$unidad[$z]['numero']?>">U<?=$unidad[$z]['numero']?></span></td>
                  <?php } ?>
                  <td width="20%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.total_minutos_reales_semanales') ?>">SEM</span></td>
                  <td width="20%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_minutos_reales') ?>">acm</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.minutos_proyectados_semanal') ?> - <?php echo lang('global.total_minutos_reales_semanales') ?>">SEM</span></td>
                  <td width="50%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acumulado_proyectado_semala') ?> – <?php echo lang('global.acumulado_minutos_reales') ?>">acum</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                  <tr>
                   <td width="66px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.escenas_proyectadas') ?>">ESC PROY</td>
                   <td width="66px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_proyectadas') ?>">ACUM ESC</td>
                   <td width="66px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.escenas_producidas_semanales') ?>">ESC PROD</td>
                   <td width="80px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_producidas') ?>">ACM ESC PROD</td>
                   <td width="80px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.escenas_reales_producidas') ?>">ESC REAL PROD</td>
                   <td width="80px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_escenas_reales_prod') ?>">ACM ESC REAL</td>
                   <td width="60px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.dif_proyec_produ') ?>">ESC DIF</td>
                   <td width="80px"><span class="has-tip tip-centered-top"  title="<?php echo lang('global.acumulado_diferencia_escenas') ?>">ACUM DIF ESC</td>
                  </tr>
                </table>  
               </td> 
            <td><?php echo lang('global.comentarios') ?></td>
          </tr>
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
             $seg_acu=0;
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
             $acum_escenas_pautadas=0;
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
              <td><?php echo $i; ?></td>
              <td>
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

              <td>
                <table class="secondary">
                  <tr>
                    
                    <td  width="20%">
                      <a href="#" class="<?=$class_box?>"> <?php echo $semanas_trabajo[$cont]['dias_trabajo'] ?></a>
                      <div class="hide_box number_days_box">
                        <span class="close_box"></span>
                         <?php echo form_open($idioma.'plan_produccion/update_semana','class=custom'); ?>
                          <input type="hidden" name="id_semana" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" name="tipo" value="1">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>">
                          <input type="hidden" name="valor" value="44">
                          <h5>Número de días a trabajar por semana</h5>

                          <label enabled="false" for="lunes" class="label_check"><input type="checkbox" name="lunes" id="lunes"  <?php if($semanas_trabajo[$cont]['lunes']=='checked') {echo 'checked';}?>> <?php echo lang('global.lunes') ?></label>
                          <label for="martes" class="label_check"><input type="checkbox" name="martes" id="martes"  <?php if($semanas_trabajo[$cont]['martes']=='checked') {echo 'checked';} ?>> <?php echo lang('global.martes') ?></label>
                          <label for="miercoles" class="label_check"><input type="checkbox" name="miercoles" id="miercoles" <?php if($semanas_trabajo[$cont]['miercoles']=='checked') {echo 'checked';} ?>> <?php echo lang('global.miercoles') ?></label>
                          <label for="jueves" class="label_check"><input type="checkbox" name="jueves" id="jueves" <?php if($semanas_trabajo[$cont]['jueves']=='checked') {echo 'checked';} ?>> <?php echo lang('global.jueves') ?></label>
                          <label for="viernes" class="label_check"><input type="checkbox" name="viernes" id="viernes" <?php if($semanas_trabajo[$cont]['viernes']=='checked') {echo 'checked';} ?>> <?php echo lang('global.viernes') ?></label>
                          <label for="sábado" class="label_check"><input type="checkbox" name="sabado" id="sabado" <?php if($semanas_trabajo[$cont]['sabado']=='checked') {echo 'checked';} ?>> <?php echo lang('global.sabado') ?></label>
                          <label for="domingo" class="label_check"><input type="checkbox" name="domingo" id="domingo" <?php if($semanas_trabajo[$cont]['domingo']=='checked') {echo 'checked';} ?>> <?php echo lang('global.domingo') ?></label>
                          <!--
                          <h6>Comentario</h6>
                          <textarea name="comment_post" id="comment_post" ></textarea>-->
                          <div class="align_left columns three">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_icon"><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                       <?php echo form_close(); ?>
                      </div>
                    </td>
                    <td  width="40%">
                      <!--<?php if($i==1){ ?>
                        <a href="#" class="<?=$class_box?>"><?= strftime('%d-%b-%Y',strtotime($produccion[0]->inicio_PreProduccion)) ?></a>
                      <?php } else{ ?>-->
                         
                      <!--<?php } ?>-->
                      <?= strftime('%d-%b-%Y',strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])) ?>  
                      <div class="hide_box date_start_box">
                        <span class="close_box"></span>
                         <?php echo form_open($idioma.'plan_produccion/update_semana','class=custom'); ?>
                          <h5>Inicio de grabación</h5>
                          <input type="text" name="valor"  class="required" placeholder="<?= strftime('%d-%b-%Y',strtotime( $semanas_trabajo[$cont]['fecha_inicio_semana'])) ?>" value="<?= strftime('%d-%b-%Y',strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])) ?>" id="date_1">
                          <input type="hidden" name="final_grabacion" value="<?php echo $produccion['0']->fin_grabacion ?>">
                          <input type="hidden" name="id_dias_grabacion" value="<?php echo $semanas_trabajo[$cont]['dias_trabajo'] ?>">
                          <input type="hidden" name="id_semana" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" name="write_cap" value="<?php echo $produccion['0']->cap_esce_semana?>">
                          <input type="hidden"  name="min_proy_sem" value="<?php echo $produccion['0']->min_proy_semana?>">
                          <input type="hidden" name="tipo" value="2">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>">
                          <div class="clr"></div>
                          <div class="align_left">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_icon"><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                        <?php echo form_close(); ?>
                      </div>
                    </td>
                    <td  width="40%">
                      <?php  $fecha = strftime('%d-%b-%Y',strtotime($semanas_trabajo[$cont]['fecha_fin_semana']));
                      ?>
                      <?= $fecha ?>
                      <div class="hide_box date_end_box">
                        <span class="close_box"></span>

                        <?php echo form_open($idioma.'plan_produccion/update_semana','class=custom'); ?>

                          <input type="text" name="valor"  class="required" placeholder="<?= strftime('%d-%b-%Y',strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])) ?>" value="<?= strftime('%d-%b-%Y',strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])) ?>" id="date_2">
                          <input type="hidden" name="id_semana" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" name="tipo" value="3">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>">
                          <div class="clr"></div>
                          <div class="align_left">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_icon"><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                        <?php echo form_close(); ?>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="secondary">
                  <tr>
                    <td width="14.2%">
                      
                        <a href="#" class="<?=$class_box?>" id="valor_<?php echo $semanas_trabajo[$cont]['id'] ?>"><?=$semanas_trabajo[$cont]['capitulos_programados']?></a>


                      <div class="hide_box cap_prog_box">
                        <span class="close_box"></span>
                          <h5><?php echo lang('global.libretos_programados_semana') ?></h5>
                          <?php echo form_open($idioma.'plan_produccion/update_semana','class=custom'); ?>
                          <input type="text" name="valor" class="required val_cap_sema_id_<?php echo $semanas_trabajo[$cont]['id'] ?>" value="<?php echo $semanas_trabajo[$cont]['capitulos_programados'] ?>">
                          <!-- <label for="cambiar_todos"><input type="checkbox" name="cambiar_todos"> Cambiar en todas las semanas</label> -->
                          <input type="hidden" class="cap_semana_id_<?php echo $semanas_trabajo[$cont]['id'] ?>" name="id_semana" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" class="tipo_cap_sema_id_<?php echo $semanas_trabajo[$cont]['id'] ?>" name="tipo" value="4">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>">
                          <div class="align_left">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_icon" ><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                      <?php echo form_close(); ?>
                      </div>
                    </td>
                    <?php $cap_pro=$cap_pro+$semanas_trabajo[$cont]['capitulos_programados'] ?>
                    <td width="14.2%"><?php echo $cap_pro ?></td>
                    <td width="14.2%" class="hightltght_yellow">
                    <?php
                      $contador=0;
                      $contador_desg=0;
                      foreach ($capitulos as $capitulo) {
                        if(strtotime($capitulo->fecha_entregado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                          $contador++;
                        }
                        if(strtotime($capitulo->fecha_desglosado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_desglosado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana']) and $capitulo->estado==4){
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
                    <td width="14.2%"><?=$contador_desg;?></td>
                    <td width="14.2%"><?=$acu_desg;?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="secondary">
                  <tr>
                    <td width="50%">
                      <?php if($entro_u==0){ ?>
                          <?php 
                          $semana_inicio_unidad = strtotime($unidad[0]['fecha_inicio']);
                          if($k==1) {
                         ?></a>
                          <?php $entro_u=1; ; ?>
                          <a href="#" class="<?=$class_box?>"><?php echo $semanas_trabajo[$cont]['minutos_proyectados'].':'; 
                            if(strlen($semanas_trabajo[$cont]['segundos_proyectados'])<2){
                              echo '0';
                            }
                            echo $semanas_trabajo[$cont]['segundos_proyectados'];?></a>
                          <?php $dif_min = -$semanas_trabajo[$cont]['minutos_proyectados'];?>
                          <?php }else{;
                            echo 0;
                            $min_acu = 0;
                            $seg_acu = 0;
                            $dif_min = 0;
                          }   ?>
                       <?php }else{ ?>

                            <a href="#" class="<?=$class_box?>"><?php echo $semanas_trabajo[$cont]['minutos_proyectados'].':';
                              if(strlen($semanas_trabajo[$cont]['segundos_proyectados'])<2){
                                echo '0';
                              }
                              echo $semanas_trabajo[$cont]['segundos_proyectados'];
                             ?></a>
                       <?php } ?>   
                      <div class="hide_box min_proy_box">
                        <span class="close_box"></span>
                        <?php echo form_open($idioma.'plan_produccion/update_semana','class=custom'); ?>
                          <h5>Minutos proyectados por semana</h5>
                          <input type="text" name="valor"  class="required" value="<?php echo $semanas_trabajo[$cont]['minutos_proyectados'] ?>">
                          <input type="text" name="valor2"  class="required" value="<?php echo $semanas_trabajo[$cont]['segundos_proyectados'] ?>">
                         <!-- <label for="cambiar_todos"><input type="checkbox" name="cambiar_todos"> Cambiar en todas las semanas</label>-->
                          <input type="hidden" name="id_semana" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" name="tipo" value="5">
                          <input type="hidden" name="id_produccion" value="<?php echo $produccion[0]->id_produccion ?>">
                          <div class="align_left">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_icon"><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                        <?php echo form_close(); ?>
                      </div>
                    </td>
                    <?php 
                    if($k!=1){
                      $min_acu = 0;
                      $seg_acu = 0; 
                    }else{
                    $min_acu=$min_acu+$semanas_trabajo[$cont]['minutos_proyectados'];
                    $seg_acu+=$semanas_trabajo[$cont]['segundos_proyectados'];
                    } 

                    ?>
                    <td width="50%"><?php echo calculo_tiempo($min_acu,$seg_acu ) ?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="secondary">
                  <tr>
                    <td width="50%"><?php echo $produccion['0']->capitulos_proyectados?></td>
                    <?php $capitulos_entregados=$this->model_plan_produccion->capitulos_entregados($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana']); ?>
                    <td width="50%"><?php echo $capitulos_entregados['0']->total ?></td>
                  </tr>  
                 </table> 
              </td>   
              <td>
                <!--ACUMULADO DE MINUTOS REALES-->
                <table class="secondary">
                  <tr>
                      <?php /*$acu_min_uni_sem=0;$acu_seg_uni_sem=0;
                      for ($z=0; $z < count($unidad); $z++) { ;?>
                      <?php $acu_min_uni=0;$acu_seg_uni=0;
                      for ($y=0; $y<count($escenas_producidas); ++$y) {
                        if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  
                        AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])
                        AND $unidad[$z]['id'] == $escenas_producidas[$y]->id_unidad){
                          $acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
                          $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
                          $acu_min_uni_sem+=$escenas_producidas[$y]->duracion_real_minutos;
                          $acu_seg_uni_sem+=$escenas_producidas[$y]->duracion_real_segundos;
                          $acu_min_real+=$escenas_producidas[$y]->duracion_real_minutos;   
                          $acu_seg_real+=$escenas_producidas[$y]->duracion_real_segundos;  
                        }
                      }*/ ?>

                      <?php $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
                      $acum_escena_producidas=0;
                      for ($z=0; $z < count($unidad); $z++) { ;?>
                      <?php $acu_min_uni=0;$acu_seg_uni=0;
                            $tiempo=$this->model_escenas_2->escenas_producidas_produccion2($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana'],$unidad[$z]['id']);
                            if($tiempo){
                               // $acum_escena_producidas=$acum_escena_producidas+$tiempo['0']->total_escenas;
                              //echo $tiempo['0']->total_escenas.'dasdadas';
                                //$acum_escena_producidas=$acum_escena_producidas+$tiempo['0']->total_escenas;
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
                     
                      <td width="20%" class="hightltght_yellow"><?=$dif_min_real=calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem)?></td>
                       <?php $v=explode(':', $dif_min_real); 
                        $acum_escena_producidas=round($escena_por_minuto*$v['0']);
                        $escenas_pautadas=$this->model_dashboard->escenas_programadas($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana']);
                       ?>
                      <?php  $total_acum_escena_producidas=$total_acum_escena_producidas+$acum_escena_producidas; 
                      $acum_escenas_pautadas=$acum_escenas_pautadas+$escenas_pautadas;?>
                      <td width="20%"><?=calculo_tiempo($acu_min_real,$acu_seg_real)?></td>                 
                  </tr>
                </table>
                <!--FIN ACUMULADO DE MINUTOS REALES-->
              </td>
              <td>
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
                      $segundos_sem_tra=($semanas_trabajo[$cont]['minutos_proyectados']*60)+$semanas_trabajo[$cont]['segundos_proyectados'];
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
              <td nowrap="nowrap">
                <table class="secondary">
                  <tr>
                    <td width="66px">
                    
                      <?php 
                       if($entro_u==1){ 
                          if($semanas_trabajo[$cont]['minutos_proyectados']!=0 and $escena_porminuto!=0){
                          echo round($semanas_trabajo[$cont]['minutos_proyectados']*$escena_porminuto);
                          $count_escenas_proyec=round($semanas_trabajo[$cont]['minutos_proyectados']*$escena_porminuto);
                          $acum_escena_proyec=$acum_escena_proyec+$count_escenas_proyec;
                         }else{
                          $count_escenas_proyec=0;
                          echo '0';
                         }
                       }  
                        ?>
                    </td>
                    <td width="66px">
                     <?php if($entro_u==1){ 
                           echo $acum_escena_proyec;
                         }
                        ?>
                    </td>
                    <td width="66px">
                     <?php if($entro_u==1){ 
                           echo $acum_escena_producidas;
                         }
                        ?>
                    </td>
                   <td width="80px">
                     <?php if($entro_u==1){ 
                           echo $total_acum_escena_producidas;
                         }
                        ?>
                    </td>
                    <td width="80px">
                     <?php if($entro_u==1){ 
                           echo $escenas_pautadas;
                         }
                        ?>
                    </td>
                    <td width="80px">
                     <?php if($entro_u==1){ 
                           echo $acum_escenas_pautadas;
                         }
                        ?>
                    </td>
                    <td width="60px">
                     <?php if($entro_u==1){ 
                           $esc_dif=$acum_escena_producidas-$escenas_pautadas;
                             if($esc_dif<0){ ?>
                                  <span class="red"><?php echo $esc_dif ?></span>      
                             <?php }else{ ?>
                                <?php echo $esc_dif ?>
                              <?php } ?>
                           
                       <?php } ?> 
                    </td>
                    <td width="80px">
                     <?php if($entro_u==1){ 
                           $acum_esc_dif=$acum_esc_dif+$esc_dif;
                           if($acum_esc_dif<0){ ?>
                                <span class="red"><?php echo $acum_esc_dif ?></span>      
                           <?php }else{ ?>
                              <?php echo $acum_esc_dif ?>
                       <?php } ?>
                      <?php } ?> 
                    </td>
                    
                  </tr>
                </table>    
              </td>      
              <td>
                <table class="secondary">
                  <tr>
                    <td>
                      <?php if($semanas_trabajo[$cont]['comentario'] == ""){?>
                      <a href="#" id="<?php echo $semanas_trabajo[$cont]['id'] ?>"  class="<?=$class_box?>"><?php echo lang('global.comentar') ?></a>
                      <?php } else{?>
                      <a href="#" class="<?=$class_box?>" id="<?php echo $semanas_trabajo[$cont]['id'] ?>" value="<?php echo $semanas_trabajo[$cont]['id'] ?>" style="color:#4a4b39"><?php echo Plan_produccion::corta_palabra($semanas_trabajo[$cont]['comentario'],25);
                      if(strlen($semanas_trabajo[$cont]['comentario'])>=25){
                        echo '...';
                      }; ?></a>
                      <?php } ?>
                      <div class="hide_box comments_box" style="width: 600px; left: -330px !important;">
                        <span class="close_box"></span>
                          <textarea name="valor" class="comentario_plan_general" rows="2"  class="required" style="min-height: 50px;"><?php echo $semanas_trabajo[$cont]['comentario'] ?></textarea>
                          <input type="hidden" name="id_semana" class="id_semana" id="<?php echo $semanas_trabajo[$cont]['id'] ?>" value="<?php echo $semanas_trabajo[$cont]['id'] ?>">
                          <input type="hidden" name="tipo" class="tipo" value="6">
                          <input type="hidden" name="id_produccion" class="id_produccio" value="<?php echo $produccion[0]->id_produccion ?>">
                          <div class="align_left">
                            <button>
                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                            </button>
                            <button type="submit">
                              <a class="save_coment"><?php echo lang('global.guardar') ?></a>
                            </button>
                          </div>
                      </div>
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

<div id="help_wrapper" class="helpBox">
  <div class="box">
    <span class="close_box"></span>
    <div class="innerBox">
      <iframe src="http://player.vimeo.com/video/68238929" width="680" height="400" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
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