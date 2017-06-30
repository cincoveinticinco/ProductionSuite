<?php $idioma = $this->lang->lang().'/'; ?>
<?php $editar='';

if($escena[0]->estado==1 or $escena[0]->estado==2 or $escena[0]->estado==12 or $escena[0]->estado==14){
  $editar='readonly';
}
 ?>
<div id="breadcrumbs">
  <a class="confirm_link" href="<?php echo base_url($idioma.'produccion/producciones'); ?>">Home</a> / <a class="confirm_link" href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a>  
  <?php if($capitulo!="") {?>
    <?php if($capitulo[0]['nombre']!=""){
        echo '/ <a class="confirm_link" href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'.'  Libretos</a> ';
    }else echo '/ <a class="confirm_link"  href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'." Libreto No ".$capitulo[0]['numero'];}?></a> 
    / <a class="confirm_link" href="<?= base_url($idioma.'escenas/buscar_escenas/'.$produccion['0']->id_produccion.'/'.$capitulo[0]['id']) ?>">Libreto No. <?=$capitulo[0]['numero']?></a> / Escena No. <?=$escena[0]->numero_escena?> / <?php echo lang('global.editar_ecena') ?>
   <div class="productionName">
      <?php echo $produccion['0']->nombre_produccion ?>
    </div>
</div>
<div id="inner_content">
   <?php echo form_open_multipart($idioma.'escenas/actualizar_escena','id="editar_escena", class="", onSubmit="return validation_duration()"');?>
    <!-- CAMPOS OCULTOS FILTRO ESCENAS -->
    <input type="hidden" name="limite1" value="<?=$limite1?>">
    <input type="hidden" name="limite2" value="<?=$limite2?>">
    <input type="hidden" name="idlocacion" value="<?=$idlocacion?>">
    <input type="hidden" name="idset" value="<?=$idset?>">
    <input type="hidden" name="dia_continuidad" value="<?=$dia_continuidad?>">

    <!-- FIN CAMPOS OCULTOS FILTRO ESCENAS -->

    <input type="hidden" name="idcapitulo" id="idcapitulo" value="<?= $idcapitulo  ?>">
    <input type="hidden" name="estado_escena" id="estado_escena" value="<?= $escena[0]->estado  ?>">
    <input type="hidden" value="<?=$produccion['0']->id_produccion?>" id="idproduccion" name="idproduccion" ?>
    <div class="row">
      <div class="columns twelve">
        <div class="row row_field">
          <div class="left_dashed" style="width:5%;">
            <label for="cap"><?php echo lang('global.libreto') ?></label>
            <select name="cap" id="cap" <?php echo $editar ?>>
              <option value="<?= $idcapitulo ?>" selected>
                      <?=$capitulo[0]['numero']?>
              </option>
              <?php if($editar!='readonly'){ ?>
                    <?php foreach ($capitulos as $c) { ?>
                        <?php if($c['id']!= $escena[0]->id_capitulo){?>
                        <option value="<?php echo $c['numero'] ?>">
                              <?=$c['numero']?>
                        </option>
                    <?php  } } ?>
              <?php } ?>      
            </select>
          </div>
          <div class="left_dashed" style="width:4.5%;">
            <label for="esc"><?php echo lang('global.escenas') ?></label>
            <input type="hidden" name="id_produccion" class="required" value="<?php echo $produccion['0']->id_produccion ?>">
            <input type="hidden" name="id_capitulo" class="required" value="<?php echo $idcapitulo ?>">
            <input type="hidden" name="id_escena" class="required" value="<?php echo $escena[0]->id ?>">
            <input type="hidden" name="numero_escena_hidden" class="required" value="<?php echo $escena[0]->numero_escena ?>">
            <input onkeypress="return soloLetras(event)" type="text" id="numero_escena" name="numero_escena" <?php echo $editar ?> class="required" value="<?php echo $escena[0]->numero_escena ?>">
             <label class="error"><?php echo form_error('numero_escena'); ?></label>
          </div>
          <div class="left_dashed" style="width:9.5%;">
            <label for="dur"><?php echo lang('global.tiempo_estimado') ?>.</label>
            <div class="left" style="width:30%"><input type="text" placeholder="00" class="required" <?php echo $editar ?> id="duration_minutes" name="minutos" 
              value="<?=$escena[0]->duracion_estimada_minutos?>"></div>
             <?php echo form_error('minutos'); ?>
            <div class="left" style="width:20%"><label style="margin-top:7px;">&nbsp;&nbsp;MM</label></div>
            <div class="left" style="width:30%"><input type="text" placeholder="00" class="required" id="duration_seconds" <?php echo $editar ?> name="segundos" value="<?=$escena[0]->duracion_estimada_segundos?>"></div> 
             <?php echo form_error('segundos'); ?>
            <div class="left" style="width:20%"><label style="margin-top:7px;">&nbsp;&nbsp;SS</label></div>
          </div>
          <div class="left_dashed" style="width:8%;">
            <label for="libreto">Pág. <?php echo lang('global.guion') ?></label>
            <input type="text" placeholder="0" class="required" id="libreto" name="libreto" value="<?= $escena[0]->libreto ?>" <?php echo $editar ?>>
             <?php echo form_error('libreto'); ?>
          </div>

          <div class="left_dashed" style="width:34%;">
            <div class="row">
              <div class="column nine">
                <label for="location"><?php echo lang('global.locacion') ?></label>
                <select name="location" id="location_crear_escena">
                  <option value="<?php echo $escena[0]->locacionid ?>"><?php echo $escena[0]->locacionnombre ?></option>
                   <?php if($editar!='readonly'){ ?>
                          <?php foreach ($locacion as $l ) { ?>
                            <?php if($escena[0]->locacionid != $l['id'] ){ ?>
                           <option value="<?php echo $l['id'] ?>"><?php echo $l['nombre'] ?></option>
                          <?php } } ?>
                   <?php } ?>       
                </select>
                <input type="text" id="new_location" name="new_location"placeholder="insert text" style="display:none;">
              </div>
               <?php if($editar!='readonly'){ ?>
              <div class="column three">
                <br>
                <a href="#" class="button secondary" id="new_item_location"><?php echo lang('global.Nuevo') ?></a>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="left_dashed last setHiden" style="width:35%;">
            <div class="row">
              <div class="column nine">
                <label for="set"><?php echo lang('global.set') ?></label>
                <select name="set" id="set" class="set required" validate="required:true">
                  <option value="<?php echo $escena[0]->setid ?>"><?php echo $escena[0]->setnombre ?></option>
                   <?php if($editar!='readonly'){ ?>
                      <?php foreach ($set as $s) { ?>
                        <?php if($escena[0]->setid != $s['id']){?>
                        <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'] ?></option>
                      <?php } }?>
                  <?php } ?>    
                </select>
                <div id="prueba"></div>
               <input type="text" id="new_set" placeholder="insert text" style="display:none;">
              </div>
               <?php if($editar!='readonly'){ ?>
                <div class="column three">
                  <br>
                  <a href="#" class="button secondary" id="new_item_set"><?php echo lang('global.Nuevo') ?></a>
                  <a href="#" class="button" id="add_item_set" style="display:none;"><?php echo lang('global.agregar') ?></a>
                  <a href="#" class="button" id="cancel_item_set" style="display:none;"><?php echo lang('global.cancelar') ?></a>
                  <a href="#" class="button" id="add_item_location" style="display:none;"><?php echo lang('global.agregar') ?></a>
                  <a href="#" class="button" id="cancel_item_location" style="display:none;"><?php echo lang('global.cancelar') ?></a>
                </div>
              <?php } ?>  
            </div>
          </div>
        </div>
          
          <?php if($editar=='readonly'){ ?>
            <input type="hidden" name="escena_producida" value="1"> 
            <input type="hidden" name="estado_escena_producida" value="<?php echo $escena[0]->estado ?>"> 
          <?php }else{ ?>  
              <input type="hidden" name="escena_producida" value="0"> 
         <?php } ?>
  
         

        <div class="row row_field">

          <div class="left_dashed" style="width:8%;">
            <label for="cap"><?php echo lang('global.dia_continuidad') ?></label>
            <input type="text" placeholder="0" class="required number" id="cap" name="continuidad" <?php echo $editar ?> value="<?= $escena[0]->dias_continuidad ?>">
          </div>

          <div class="left_dashed" style="width:11%;">
            <label for="esc"><?php echo lang('global.locacion') ?> / <?php echo lang('global.estudio') ?></label>
<!--             <div class="left">
              <label for="loc_loc" class="label_check">
                <?php if($escena[0]->tipolocacionid!=3){?>
                <input type="radio"  checked name="location_tipo" value="<?php  echo $escena[0]->tipolocacionid  ?>" class="location_tipo"> <?php echo $escena[0]->tipolocacion ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <?php } ?>
                <label class="error"><?php echo form_error('location_tipo'); ?></label>
            </div>  --> 
            <?php foreach ($tipo_locacion as $t) { ?>
            <?php if($t['id'] !=3){ ?>
             <div class="left">
              <label for="loc_loc" class="label_check">
               <?php if($editar!='readonly'){ ?>
              <input type="radio" <?php if($t['id'] == $escena[0]->tipolocacionid AND $t['id'] !=3){ ?> checked <?php } ?> name="location_tipo" value="<?php  echo $t['id']  ?>" class="location_tipo"> <?php echo $t['tipo'] ?>
              <?php }else{ ?>

              <?php if($t['id'] == $escena[0]->tipolocacionid AND $t['id'] !=3){ ?> <input type="radio" checked name="location_tipo" value="<?php  echo $t['id']  ?>" class="location_tipo"> <?php echo $t['tipo'] ?><?php } ?> 
              <?php } ?>
              &nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
            <?php } } ?>
          </div>

          <div class="left_dashed" style="width:8%;">
            <label for=""><?php echo lang('global.dia_noche') ?></label>
<!--             <div class="left">
             <label for="loc" class="label_check">
               <?php if($escena[0]->dianocheid!=3){?>
              <input checked type="radio" name="dia_noche" value="<?php echo $escena[0]->dianocheid ?>" id="<?php echo $escena[0]->dianocheid ?>"> <?php echo $escena[0]->dianochenombre ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
              <?php }?>
              <label class="error"><?php echo form_error('dia_noche'); ?></label>
            </div> -->
            <?php foreach ($dia_noche as $d) { ?>
              <div class="left">
              <label for="loc" class="label_check">
              <?php if($editar!='readonly'){ ?>
                    <input <?php if($d['id'] == $escena[0]->dianocheid AND $d['id']!=3){?> checked
                    <?php } ?>
                    type="radio" name="dia_noche" value="<?php echo $d['id'] ?>" id="<?php echo $d['id'] ?>"> <?php echo $d['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
               <?php }else{ ?>   
                <?php if($d['id'] == $escena[0]->dianocheid AND $d['id']!=3){?><input checked
                   
                    type="radio" name="dia_noche" value="<?php echo $d['id'] ?>" id="<?php echo $d['id'] ?>"> <?php echo $d['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                      <?php } ?>
               <?php } ?>
               
            </div>
            <?php  } ?>
          </div>

          <div class="left_dashed" style="width:11%;">
            <label for=""><?php echo lang('global.int_ext') ?></label>
<!--             <div class="left">
              <label for="loc" class="label_check">
                <?php if($escena[0]->interiorid!=3){?>
                <input checked type="radio" name="int_ext" value="<?php echo $escena[0]->interiorid ?>" id="<?php echo $escena[0]->interiorid ?>"><?php echo $escena[0]->interiornombre ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <?php }?>
                <label class="error"><?php echo form_error('int_ext'); ?></label>
            </div> -->
            <?php foreach ($escena_interior_esterior as $e) { ?>
            
              <div class="left">
              <label for="loc" class="label_check">
              <?php if($editar!='readonly'){ ?>
                      <input type="radio" 
                        <?php if($escena[0]->interiorid == $e['id'] AND $e['id']!=3){?> checked <?php } ?>
                         name="int_ext" value="<?php echo $e['id'] ?>" id="<?php echo $e['id'] ?>"><?php echo $e['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
               <?php }else{ ?>     
                    <?php if($escena[0]->interiorid == $e['id'] AND $e['id']!=3){?>
                         <input type="radio" 
                        checked 
                         name="int_ext" value="<?php echo $e['id'] ?>" id="<?php echo $e['id'] ?>"><?php echo $e['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>      
                         <?php } ?>
               <?php } ?>
            </div>
            <?php } ?>
          </div>

          <div class="left_dashed" style="width:7%;">
            <label for=""><?php echo lang('global.flashback') ?></label>

            <?php foreach ($escenas_flasback as $f) { ?>
            
              <div class="left">
              <label for="flashback_yes" class="label_check">
                  <?php if($editar!='readonly'){ ?>
                        <input type="radio" 
                          <?php if($escena[0]->flashbackid == $f['id'] and $f['id']!=3){?> checked
                          <?php } ?>
                          name="flashback" value="<?php echo $f['id'] ?>" class="flashback"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php }else{ ?> 
                       <?php if($escena[0]->flashbackid == $f['id'] and $f['id']!=3){?>
                       <input type="radio" 
                           checked
                          name="flashback" value="<?php echo $f['id'] ?>" class="flashback"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;       
                          <?php } ?>
                  <?php } ?>

                </label>
            </div>
            <?php } ?>
            <?php echo form_error('flashback'); ?>
          </div>

            <div class="left_dashed" id="toma" style="width:7%;">
            <label for="esc"><?php echo lang('global.toma_ubicacion') ?></label>
            <?php if($editar!='readonly'){ ?>
                          <div class="left">
                            <label for="toma_ubi_3" class="label_check">
                                 <input <?php if(1 == $escena[0]->id_toma_ubicacion){ echo "checked"; }?> type="radio" id="toma_ubi_3" name="toma_ubi_si" value="1" class="flashback"> Si&nbsp;&nbsp;&nbsp;&nbsp;
                            </label>
                          </div>
                          <div class="left">
                            <label for="toma_ubi_no" class="label_check"><input type="radio" <?php if(2 == $escena[0]->id_toma_ubicacion){ echo "checked"; }?> id="toma_ubi_no" name="toma_ubi_si" value="2" class="flashback"> No&nbsp;&nbsp;&nbsp;&nbsp;</label>
                          </div>
              <?php }else{ ?> 
              <?php if(1 == $escena[0]->id_toma_ubicacion){ ?>
                         <div class="left">
                            <label for="toma_ubi_3" class="label_check">
                                 <input  "checked" type="radio" id="toma_ubi_3" name="toma_ubi_si" value="1" class="flashback"> Si&nbsp;&nbsp;&nbsp;&nbsp;
                            </label>
                          </div>
              <?php }?>   
              <?php if(2 == $escena[0]->id_toma_ubicacion){  ?>    
                          <div class="left">
                            <label for="toma_ubi_no" class="label_check"><input type="radio"   checked  id="toma_ubi_no" name="toma_ubi_si" value="2" class="flashback"> No&nbsp;&nbsp;&nbsp;&nbsp;</label>
                          </div> 
               <?php }?>              
              <?php } ?>
            </div>



            <div class="left_dashed" style="width:7%;">
            <label for=""><?php echo lang('global.foto_realizacion') ?></label>

            <?php foreach ($escenas_foto_realizacion as $f) { ?>
            
              <div class="left">
              <label for="flashback_yes" class="label_check">
                  <?php if($editar!='readonly'){ ?>
                        <input type="radio" 
                          <?php if($escena[0]->id_foto_realizacion == $f['id'] and $f['id']!=3){?> checked
                          <?php } ?>
                          name="foto_realizacion" value="<?php echo $f['id'] ?>" class="foto_realizacion_"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php }else{ ?> 
                       <?php if($escena[0]->id_foto_realizacion == $f['id'] and $f['id']!=3){?>
                       <input type="radio" 
                           checked
                          name="foto_realizacion" value="<?php echo $f['id'] ?>" class="foto_realizacion"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;       
                          <?php } ?>
                  <?php } ?>

                </label>
            </div>
            <?php } ?>
            <?php echo form_error('foto_realizacion'); ?>
          </div>

          <div class="left_dashed" style="width:9%;">
            <label for=""><?php echo lang('global.imagen_archivo') ?></label>

            <?php foreach ($escenas_imagenes_archivo as $f) { ?>
            
              <div class="left">
              <label for="flashback_yes" class="label_check">
                  <?php if($editar!='readonly'){ ?>
                        <input type="radio" 
                          <?php if($escena[0]->id_imagenes_archivo == $f['id'] and $f['id']!=3){?> checked
                          <?php } ?>
                          name="escenas_imagenes_archivo" value="<?php echo $f['id'] ?>" class="escenas_imagenes_archivo"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php }else{ ?> 
                       <?php if($escena[0]->id_imagenes_archivo == $f['id'] and $f['id']!=3){?>
                       <input type="radio" 
                           checked
                          name="escenas_imagenes_archivo" value="<?php echo $f['id'] ?>" class="escenas_imagenes_archivo"> <?php echo $f['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;       
                          <?php } ?>
                  <?php } ?>

                </label>
            </div>
            <?php } ?>
            <?php echo form_error('escenas_imagenes_archivo'); ?>
          </div>


            
          <div class="left_dashed escena_producida" style="display:none">
              <label for="escena_producida" class="escena_producida " style="display:none;"><?php echo lang('global.producidas') ?></label>
              <?php foreach ($producida as $p) { ?>
              <div class="left">
              <label for="flashback_yes" class="label_check"><input type="radio" name="producida" value="<?php echo $p['id'] ?>" class="flashback"> <?php echo $p['descripcion'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            </div>
            <?php } ?>
          </div>

          <!-- Magnitud -->
          <div class="left_dashed escena_producida" style="width:6%;">
              <label for="escena_producida" class="escena_producida "><?php echo lang('global.magnitud') ?></label>
                <select name="magnitud">
                <?php if($editar!='readonly'){ ?>
                        <?php foreach ($magnitud as $m) { ?>
                        <option <?php if($m->id == $escena[0]->id_magnitud){ echo "selected"; }?> value="<?=$m->id?>"><?=$m->descripcion?></option>
                        <?php } ?>
                <?php }else{ ?> 
                       <?php foreach ($magnitud as $m) { ?>
                         <?php if($m->id == $escena[0]->id_magnitud){ ?>
                          <option  selected value="<?=$m->id?>"><?=$m->descripcion?></option>
                          <?php } ?>
                        <?php } ?>       
                <?php } ?>
                </select>
          </div>

          <!-- Vehiculo Background -->
          <div class="left escena_producida" style="width:8%;">
              <label for="escena_producida" class="escena_producida "><?php echo lang('global.vehivulo_back') ?>.</label>
              <input onkeypress="return soloLetras(event)" type="text" id="vehiculo_background" <?php echo $editar ?> name="vehiculo_background" max="999"  value="<?php echo $escena[0]->vehiculo_background ?>">
          </div>
        </div>

        <div class="row row_field">
          <div class="column six">
            <label for="caption"><?php echo lang('global.descripcion_escena') ?></label>
            <textarea name="descripcion" <?php echo $editar ?>><?= $escena[0]->descripcion?></textarea>
          </div>
          <div class="column six">
            <label for="caption"><?php echo lang('global.guion') ?></label>
            <textarea name="guion" <?php echo $editar ?>><?= $escena[0]->guion?></textarea>
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
                  <select name="" id="elemento_id" idescena="<?=$escena[0]->id?>" idproduccion="<?=$produccion['0']->id_produccion?>">
                  <?php if($editar!='readonly'){ ?>
                          <option value="0"><?php echo lang('global.ver_todos') ?></option>
                          <?php foreach ($categoria_elemento as $c) { ?>
                           <option value="<?php echo $c['id'] ?>"><?php echo $c['tipo'] ?></option>
                          <?php } ?>
                  <?php }else{ ?>   
                          <?php foreach ($categoria_elemento as $c) { ?>
                          <?php if($c['tipo']=='Personaje'){ ?>
                           <option value="<?php echo $c['id'] ?>"><?php echo $c['tipo'] ?></option>
                           <?php } ?>
                          <?php } ?>
                  <?php } ?>     
                </select>
                </div>
                <div class="columns six"><input type="text" id="buscar_elemento" idproduccion="<?=$produccion['0']->id_produccion?>" class="search_input"></div>
              </div>
            </div>
          </div>
          <br><br>
          <div class="row ">
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
                      <tbody id="tabla1" class="connectedSortable">
                         <?php if($elementos) { ?>
                            <?php foreach ($elementos as $e) {  ?>
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
              <div id="loadElementsAsing">
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
                      <tbody id="tabla2" class="connectedSortable">
                         <?php if($elementos_escena) { ?>
                            <?php foreach ($elementos_escena as $e) {?>
                            <tr id="elemento<?= $e->idelemento ?>" name="elemento" class="sort_left">
                                
                                <td>
                                  <!--CAMPO OCULTO VERIFICACION ELEMENTO-->
                                  <input type="checkbox" class="elemento_escena" name="elemento[<?=$e->idelemento?>]" value="<?=$e->idelemento?>" style="display:none;" checked="">
                                  <input type="hidden" value="<?= $e->idelemento ?>" id="id_elemento_escenas">
                                  <input type="hidden" value="<?= $e->categoria ?>" id="tipo">
                                  <input type="hidden" value="<?= $e->nombre_elemento ?>" id="nombre">
                                  <span><?php echo $e->categoria ?></span>
                                </td>
                                <td>
                                  <?php echo $e->nombre_elemento ?>
                                  <?php if($e->rol_elemento!=""){
                                    echo '( '.$e->rol_elemento.' )';
                                  } 
                                  if($e->cantidad){?>
                                  <input type="text" value="<?=$e->cantidad?>" name="hidden<?=$e->idelemento?>">
                                  <?php 
                                  }
                                  if($e->cantidad2){?>
                                  <input type="text" value="<?=$e->cantidad2?>" name="hidden<?=$e->idelemento?>vehiculo">
                                  <?php 
                                  }
                                  ?>
                                </td>
                               
                                <?php if($editar!='readonly'){ ?>
                                <td><a class="eliminar_elemento" style="text-align:center"><?php echo lang('global.eliminar') ?></a></td>
                                <?php }else{ ?>
                                         <?php if($e->categoria=="Personaje"){ ?>
                                           <td><a class="eliminar_elemento" style="text-align:center"><?php echo lang('global.eliminar') ?></a></td>
                                         <?php } ?>

                                <?php } ?>
                              </tr>
                           <?php } ?>
                        <?php } else { ?>
                             <tr>
                               <td class="sort_left"><span><?php echo lang('global.no_hay_elemento') ?></td>
                              </tr> 
                        <?php } ?>
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
                <input type="text" placeholder="Insert text" id="name_elemento" name="name" class="filed_required">
              </div>
              <div class="columns four">
                <div class="row">
                  <div class="columns seven" id="rolPersonajes" >
                    <label for="name"><?php echo lang('global.rol') ?>:</label>
                    <select name="type" id="rol_personaje">
                      <?php /*foreach ($rol_actores_elementos as $r) { ?>
                        <option value="<?php echo $r['id'] ?>"><?php echo $r['rol'] ?></option>
                      <?php }*/ ?>
                      <option value="1"><?php echo lang('global.protagonista') ?></option>
                      <option value="3"><?php echo lang('global.reparto') ?></option>
                      <option value="2"><?php echo lang('global.figurante') ?></option>
                      <option value="4"><?php echo lang('global.extra') ?></option>
                    </select>  
                  </div>
                  <div class="column five align_right">
                    <br>
                    <a href="#" id="crear_elemento" class="button secondary"  style="min-width:143px;"><?php echo lang('global.crear_continuar') ?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="align_left">
      <br>
      <a href="<?=base_url().$idioma.'escenas/buscar_escenas/'.$produccion['0']->id_produccion.'/'.$idcapitulo?>" class="button secondary"><?php echo lang('global.cancelar') ?></a>
      <input type="submit" class="button" value="<?php echo lang('global.guardar') ?>">
    </div>
  <?php echo form_close(); ?> 
</div>
<!--CAMPO OCULTO PARA LOS ELEMENTOS SELECCIONADOS-->
<input type="hidden" id="elements_ids" value="">