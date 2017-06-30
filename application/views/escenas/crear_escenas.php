<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a class="confirm_link" href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a class="confirm_link"  href="<?php echo base_url('/plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> <a class="confirm_link" href="<?php echo base_url('escenas/index/'.$produccion['0']->id_produccion); ?>"></a>
    <?php if($capitulo!="") {?>
    <?php if($capitulo[0]['numero']!=""){
      echo '/ <a class="confirm_link"  href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'.lang('global.libretos').'</a> /'.lang('global.numero_libretos').' '.$capitulo[0]['numero'].'</a>';
    }else echo "/ NT";}else{
        echo '/ <a class="confirm_link"  href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'.lang('global.libretos').'</a> '; 
    }?> / <?php echo lang('global.crear_escenas') ?>
    <div class="productionName">
      <?php echo $produccion['0']->nombre_produccion ?> 
    </div>
</div>
<?php if($msg!=""){?>
  <div class="alert-box success">
          <p><?php echo lang('global.escena_exitosamente') ?></p><a href="" class="close">×</a>
  </div>
<?php } ?>
<div id="inner_content">
   <?php echo form_open_multipart($idioma.'escenas/insert_escenas','id="crear_escena", class="", onSubmit="return validation_duration()"');?>
   <input type="hidden" value="<?=$idcapitulo?>" name="idcapitulo" ?>
   <input type="hidden" value="<?=$produccion['0']->id_produccion?>" id="idproduccion" name="idproduccion" ?>
    <div class="row">
      <div class="columns twelve">
        <div class="row row_field">
          <div class="left_dashed" style="width:5%;">
            <label for="cap"><?php echo lang('global.libreto') ?></label>
            <select name="cap" id="cap" class="required" validate="required:true">
              <?php if( $capitulo != "" ){?>
              <option value="<?= $idcapitulo ?>" selected>
                <?=$capitulo[0]['numero']?>
              </option>
              <?php }?>
              <?php foreach ($capitulos as $the_capitulo) { ?>
                  <?php
                  if($capitulo){
                      if($capitulo[0]['numero'] != $the_capitulo['numero']){?>
                      <option value="<?= $the_capitulo['id_capitulo'] ?>" >
                        <?=$the_capitulo['numero']?>
                  <?php }
                  }else { ?>  
                    <option value="<?= $the_capitulo['id_capitulo'] ?>" >
                    <?=$the_capitulo['numero'];?>
                  <?php } ?>
              </option>
              <?php } ?>
            </select>
          </div>

          <div class="left_dashed" style="width:4.5%;">
            <label for="esc"><?php echo lang('global.escenas') ?></label>
            <input type="hidden" name="id_produccion" class="required" value="<?php echo $produccion['0']->id_produccion ?>">
            <input onkeypress="return soloLetras(event)" type="text" name="numero_escena" id="numero_escena" class="required number numero_escena_crear" value="<?php echo $numero_escena ?>">
             <label class="error2"><?php echo form_error('numero_escena'); ?></label>
          </div>

          <div class="left_dashed" style="width:9.5%;">
            <label for="dur"><?php echo lang('global.tiempo_estimado') ?>.</label>
            <div class="left" style="width:30%"><input type="text" placeholder="00" class="" id="duration_minutes" class="required number" name="minutos" value="<?= set_value('minutos')?>"></div>
             <?php echo form_error('minutos'); ?>
            <div class="left" style="width:20%"><label style="margin-top:7px; text-align:center;">MM</label></div>
            <div class="left" style="width:30%"><input type="text" placeholder="00" class="" id="duration_seconds" class="required number" name="segundos" value="<?= set_value('segundos')?>"></div> 
             <?php echo form_error('segundos'); ?>
            <div class="left" style="width:20%"><label style="margin-top:7px; text-align:center;">SS</label></div>
          </div>

          <div class="left_dashed" style="width:8%;">
            <label for="libreto">Pág. <?php echo lang('global.guion') ?></label>
            <input type="text" placeholder="0" class="required" id="libreto" name="libreto" value="<?= set_value('libreto')?>">
             <?php echo form_error('libreto'); ?>
          </div>

          <div class="left_dashed" style="width:34%;">
            <div class="row">
              <div class="column nine">
                <label for="location"><?php echo lang('global.locacion') ?></label>
                <select name="location" id="location_crear_escena" class="required" validate="required:true">
                    <option value=""><?php echo lang('global.seleccione_locacion') ?></option>
                 <?php if($locacion){?> 
                    <?php foreach ($locacion as $l ) { ?> 
                     <option value="<?php echo $l['id'] ?>"><?php echo $l['nombre'] ?></option>
                    <?php } ?>
                  <?php } else{ ?>
                    <option value=""><?php echo lang('global.nodatos') ?></option>
                  <?php } ?>  
                </select>
                <?php echo form_error('location'); ?>
                <input type="text" id="new_location" name="new_location"placeholder="" style="display:none;">
              </div>
              <div class="column three">
                <br>
                <a href="#" class="button secondary" id="new_item_location"><?php echo lang('global.Nuevo') ?></a>
              </div>
            </div>
          </div>
          <div class="left_dashed last setHiden" style="width:35%;">
            <div class="row">
              <div class="column nine">
                <label for="set"><?php echo lang('global.set') ?></label>
                <select name="set" id="set" class="set required" validate="required:true">
                   <option value=""><?php echo lang('global.nodatos') ?></option>
                </select>
                <?php echo form_error('set'); ?>
                <input type="text" id="new_set" name="new_set" placeholder="" style="display:none;">
              </div>
              <div class="column three">
                <br>
                <a href="#" class="button secondary" id="new_item_set"><?php echo lang('global.Nuevo') ?></a>
                <a href="#" class="button" id="add_item_set" style="display:none;"><?php echo lang('global.agregar') ?></a>
                <a href="#" class="button" id="cancel_item_set" style="display:none;"><?php echo lang('global.cancelar') ?></a>
                <a href="#" class="button" id="add_item_location" style="display:none;"><?php echo lang('global.agregar') ?></a>
                <a href="#" class="button" id="cancel_item_location" style="display:none;"><?php echo lang('global.cancelar') ?></a>
              </div>
            </div>
          </div>
        </div>
        
      
        <div class="row row_field">

          <div class="left_dashed" style="width:8%;">
            <label for="cap"><?php echo lang('global.dia_continuidad') ?></label>
            <input type="text" placeholder="0" class="required number" id="cap" name="continuidad" value="<?= set_value('continuidad')?>">
            <?php echo form_error('continuidad'); ?>
          </div>


          <div class="left_dashed" style="width:11%;">
            <label for="esc"><?php echo lang('global.locacion') ?> / <?php echo lang('global.estudio') ?></label>
            <?php foreach ($tipo_locacion as $t) { ?>
            <?php if($t['id']!=3){?>
            <div class="left">
              <label for="tipo_<?php  echo $t['id']  ?>" class="label_check"><input id="tipo_<?php  echo $t['id']  ?>" type="radio" required validate="required:true" name="location_tipo" value="<?php  echo $t['id']  ?>" class="location_tipo"> <?php echo $t['tipo'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
            <?php } }?>
            <?php echo form_error('continuidad'); ?>
          </div>

          <div class="left_dashed" style="width:8%;">
            <label for=""><?php echo lang('global.dia_noche') ?></label>
            <?php foreach ($dia_noche as $d) { ?>
            <?php if($d['id']!=3){?>
              <div class="left">
              <label for="dia_noche<?php echo $d['id'] ?>" class="label_check"><input type="radio" id="dia_noche<?php echo $d['id'] ?>"  class="required" name="dia_noche" value="<?php echo $d['id'] ?>"> <?php echo $d['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
            <?php }} ?>
            <?php echo form_error('dia_noche'); ?>
          </div>

          <div class="left_dashed" style="width:11%;">
            <label for=""><?php echo lang('global.int_ext') ?></label>
            <?php foreach ($escena_interior_esterior as $e) { ?>
            <?php if($e['id']!=3){?>
              <div class="left">
              <label for="int_ext<?php echo $e['id'] ?>" class="label_check"><input type="radio" class="required" name="int_ext" value="<?php echo $e['id'] ?>" id="int_ext<?php echo $e['id'] ?>"><?php echo $e['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
            <?php } } ?>
            <?php echo form_error('int_ext'); ?>
          </div>

          <div class="left_dashed" style="width:7%;">
            <label for=""><?php echo lang('global.flashback') ?></label>
            <?php foreach ($escenas_flasback as $f) { ?>
            <?php if($f['id']!=3){?>
              <div class="left">
              <label for="flashback_<?php echo $f['id'] ?>" class="label_check"><input humcar id="flashback_<?php echo $f['id'] ?>" type="radio" name="flashback" value="<?php echo $f['id'] ?>" class="flashback"  <?php if($f['descripcion'] == 'no'){
                                                echo 'checked';}?>> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
             <?php } } ?>
             <?php echo form_error('flashback'); ?>
          </div>

            <div class="left_dashed" id="toma" style="width:7%;">
            <label for="esc"><?php echo lang('global.toma_ubicacion') ?></label>
              <div class="left">
                <label for="toma_ubi_3" class="label_check"><input type="radio" id="toma_ubi_3" name="toma_ubi_si" value="1" class="flashback"> Si&nbsp;&nbsp;&nbsp;&nbsp;</label>
              </div>
              <div class="left">
                <label for="toma_ubi_no" class="label_check"><input checked type="radio" id="toma_ubi_no" name="toma_ubi_si" value="2" class="flashback"> No&nbsp;&nbsp;&nbsp;&nbsp;</label>
              </div>
            </div>
            
          <div class="left_dashed escena_producida" style="display:none">
              <label for="escena_producida" class="escena_producida " style="display:none;"><?php echo lang('global.producidas') ?></label>
                <?php foreach ($producida as $p) { ?>
                <div class="left">
                <label for="producida_<?php echo $p['id'] ?>" class="label_check">
                <input type="radio" id="producida_<?php echo $p['id'] ?>" <?php if($p['id']==2){ echo "checked "; } ?>name="producida" value="<?php echo $p['id'] ?>" class="flashback"> <?php echo $p['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                </div>
                <?php } ?>
          </div>


          <div class="left_dashed" style="width:7%;">
            <label for=""><?php echo lang('global.foto_realizacion') ?></label>
            <?php foreach ($escenas_foto_realizacion as $f) { ?>
            <?php if($f['id']!=3){?>
              <div class="left">
              <label for="foto_realizacion_<?php echo $f['id'] ?>" class="label_check"><input humcar id="foto_realizacion_<?php echo $f['id'] ?>" type="radio" name="foto_realizacion" value="<?php echo $f['id'] ?>" class="foto_realizacion_"  <?php if($f['descripcion'] == 'no'){
                                                echo 'checked';}?>> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
             <?php } } ?>
             <?php echo form_error('foto_realizacion'); ?>
          </div>

          <div class="left_dashed" style="width:9%;">
            <label for=""><?php echo lang('global.imagen_archivo') ?></label>
            <?php foreach ($escenas_imagenes_archivo as $i) { ?>
            <?php if($f['id']!=3){?>
              <div class="left">
              <label for="escenas_imagenes_archivo_<?php echo $i['id'] ?>" class="label_check"><input humcar id="escenas_imagenes_archivo<?php echo $i['id'] ?>" type="radio" name="escenas_imagenes_archivo" value="<?php echo $i['id'] ?>" class="escenas_imagenes_archivo_"  <?php if($i['descripcion'] == 'no'){
                                                echo 'checked';}?>> <?php echo $i['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
             <?php } } ?>
             <?php echo form_error('escenas_imagenes_archivo'); ?>
          </div>

          <!-- Magnitud -->
          <div class="left_dashed escena_producida" style="width:6%;">
              <label for="escena_producida" class="escena_producida "><?php echo lang('global.magnitud') ?></label>
                <select name="magnitud">
                <?php foreach ($magnitud as $m) { ?>
                <option value="<?=$m->id?>"><?=$m->descripcion?></option>
                <?php } ?>
                </select>
          </div>

          <!-- Vehiculo Background -->
          <div class="left escena_producida" style="width:8%;">
              <label for="escena_producida" class="escena_producida "><?php echo lang('global.vehivulo_back') ?>.</label>
              <input onkeypress="return soloLetras(event)" type="text" max="999" id="vehiculo_background" name="vehiculo_background" value="<?= set_value('vehiculo_background')?>">
          </div>
        </div>

        <div class="row row_field">
          <div class="column six">
            <label for="caption"><?php echo lang('global.descripcion_escena') ?></label>
            <textarea name="descripcion" value="<?= set_value('descripcion')?>" class="validcharprint"></textarea>
          </div>
          <div class="column six">
            <label for="caption"><?php echo lang('global.guion') ?></label>
            <textarea name="guion" value="<?= set_value('guion')?>" class="validcharprint"></textarea>
          </div>
        </div>
      </div>

      <div class="columns twelve">
        <div class="left_col_inner">
          <div class="row">
            <div class="columns twelve">
              <label for=""><?php echo lang('global.categoria') ?></label>
            </div>
          </div>
          <div class="row">
            <div class="columns six">
              <div class="row">
                <div class="columns six">
                  <select name="" id="elemento_id" idescena="" idproduccion="<?=$produccion['0']->id_produccion?>">
                  <option value="0"><?php echo lang('global.ver_todos') ?></option>
                  <?php foreach ($categoria_elemento as $c) { ?>
                   <option value="<?php echo $c['id'] ?>" idproduccion="<?=$produccion['0']->id_produccion?>"><?php echo $c['tipo'] ?></option>
                  <?php } ?>
                </select>
                </div>
                <div class="columns six">
                  <input type="text" id="buscar_elemento" idproduccion="<?=$produccion['0']->id_produccion?>" class="search_input">
                </div>
              </div>
            </div>
          </div>
          <br><br>
          <div class="row ">
            <div class="columns six">
              <div id="loadElements">
              </div>
              <div id="sortable_content">
                <div class="top normal_table">
                  <table style="margin:0;">
                    <thead>
                      <tr>
                        <td width="30%"><?php echo lang('global.categorias') ?></td>
                        <td width="50%"><?php echo lang('global.nombre_elemento') ?></td>
                        <td width="20%"><?php echo lang('global.acciones') ?></td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="scroller">
                 <table>
                      <tbody id="tabla1" class="connectedSortable">
                         <?php if($elementos) { ?>
                            <?php foreach ($elementos as $e) { ?>
                            <tr id="elemento<?php echo $e['id_elemento'] ?>">
                              <td class="sort_left">
                                <input type="hidden" value="<?php echo $e['id_elemento']?>" id="id_elemento_escenas">
                                <input type="hidden" value="<?php echo $e['tipo']?>" id="tipo">
                                <input type="hidden" value="<?php echo $e['nombre']?>" id="nombre">
                                <span><?php echo $e['tipo'] ?></span><?php echo $e['nombre'] ?></td>
                                <td><a class="eliminar_elemento" ><?php echo lang('global.eliminar') ?></a> <a class="agregar_elemento" ><?php echo lang('global.agregar') ?></a></td>
                            </tr>
                           <?php } ?>
                        <?php } else { ?>
                             <tr class="ui-drag-disabled">
                               <td class="sort_left"><span><?php echo lang('global.no_hay_elemento') ?></td>
                              </tr>
                        <?php } ?>
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="columns six">
              <div id="sortable_content">
                <div class="top normal_table">
                  <table style="margin:0;">
                    <thead>
                      <tr>
                        <td width="30%"><?php echo lang('global.categorias') ?></td>
                        <td width="50%"><?php echo lang('global.nombre_elemento') ?></td>
                        <td width="20%"><?php echo lang('global.acciones') ?></td>
                      </tr>
                    </thead>
                  </table>
                </div>
                  <div class="scroller">
                    <table>
                      <tbody id="tabla2" class="connectedSortable">
                         <tr class="ui-drag-disabled">
                            <td class="sort_right"><span><?php echo lang('global.no_hay_elemento') ?></td>
                        </tr> 
                      </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
            <hr class="dashed">
            <div class="row">
              <div class="columns twelve">
                <label for=""><?php echo lang('global.Nuevo') ?> <?php echo lang('global.elementos') ?></label>
              </div>
              <div class="columns four">
                <label for="type"><?php echo lang('global.categoria') ?>:</label>
                <select name="type" id="id_elemento2">
                  <?php foreach ($categoria_elemento as $c) { ?>
                   <option value="<?php echo $c['id'] ?>"><?php echo $c['tipo'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="columns four">
                <label for="name"><?php echo lang('global.nombre') ?>:</label>
                <input type="text" placeholder="" id="name_elemento" name="name" class="filed_required">
              </div>
              <div class="columns four">
                <div class="row">
                  <div class="columns seven" id="rolPersonajes" >
                    <label for="type"><?php echo lang('global.rol') ?>:</label>
                    <select name="type" id="rol_personaje">
                      <?php /*foreach ($rol_actores_elementos as $r) { ?>
                        <option value="<?php echo $r['id'] ?>"><?php echo $r['rol'] ?></option>
                      <?php }*/ ?>
                      <option value="1"><?php echo lang('global.protagonista') ?></option>
                      <option value="3"><?php echo lang('global.reparto') ?></option>
                      <option value="2"><?php echo lang('global.figurante') ?></option>
                      <option value="4"><?php echo lang('global.extra') ?></option>
                    </select>
                    <div id="numero_extras_section" style="display:none;"> 
                      <label for="type"># <?php echo lang('global.extra') ?>:</label> 
                      <input type="text" id="numero_extras" name="numero_extras">
                    </div> 
                  </div>
                  <div class="column five align_right" style="float:left;">
                    <br>
                    <a href="#" id="crear_elemento" class="button secondary" style="min-width:143px;"><?php echo lang('global.crear_continuar') ?></a>
                    <!--<a href="#" class="button secondary icon_categories" >crear Categoria</a> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id= "crear_categoria" class="categorias_elemento" style="display:none">
          <label><?php echo lang('global.nombre') ?> <?php echo lang('global.categoria') ?></label>
          <input type="hidden" value="<?php echo $produccion['0']->id_produccion  ?>" name="id_produccion">
          <div></div>
          <input type="text" id="categoria_nombre" value="" name="categoria_nombre_new">
          <label class="error"><?php echo form_error('categoria_nombre_new'); ?></label>
          <textarea name="descripcion_new" class="categoriatext2"></textarea>
          <label class="error"><?php echo form_error('descripcion_new'); ?></label>
          <input id="crear_categoria" type="submit" value="Crear Categoria">
      </div>
    <div class="align_left">
      <br>
      <a href="#" class="button help_button help" alt="Fullscreen"><span class="open"><?php echo lang('global.ayuda') ?></span></a>
      <a href="<?= base_url().$idioma.'escenas/index/'.$produccion['0']->id_produccion ?>" class="button secondary"><?php echo lang('global.cancelar') ?></a>
      <input type="submit" class="button" value="<?php echo lang('global.crear_salir') ?>">
      <input type="submit" id="continue_button" class="button" value="<?php echo lang('global.crear_continuar') ?>">
      <input type="hidden" id="validator_field" value="0" name="validator_field">
    </div>
  <?php echo form_close(); ?> 
</div>
<!--CAMPO OCULTO PARA LOS ELEMENTOS SELECCIONADOS-->
<input type="hidden" id="elements_ids" value="">