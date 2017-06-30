
<?php $idioma = $this->lang->lang().'/'; setlocale(LC_TIME, 'es_ES.UTF-8');?>


<script src="<?php echo base_url(); ?>js/sortable.js"></script>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.elementos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<?php $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion); ?>
<nav>
  <ul class="nav_post nav_elementos">
    <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2) { ?>
    <li><a href="#" class="buttons icon icon_plus new_elemento"><span></span><?php echo lang('global.nuevo_elemento') ?></a></li>
    <li><a href="#" class="buttons icon icon_plus new_locacion_set"><span></span><?php echo lang('global.nueva_loc_set') ?></a></li>
    <?php } ?>
    <li><a href="#" class="buttons icon icon_categories"><span></span><?php echo lang('global.categorias') ?></a></li>
    <!--<?php if($id_locacion AND $id_locacion!="null"){?>
    <li><a href="<?=base_url('excel/excel_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_locacion?>" target="_blank" class="buttons icon icon_save"><span></span>guardar</a></li>
    <?php }else{ ?>
    <li><a href="<?=base_url('excel/excel_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria?>" target="_blank" class="buttons icon icon_save"><span></span>guardar</a></li>
    <?php } ?>-->
    <?php if($id_locacion AND $id_locacion!="null"){
        $id_categoria = $id_locacion; 
    } ?>
    <li><a data-idproduccion="<?=$produccion['0']->id_produccion?>" data-categoria="<?=$id_categoria?>" data-carga="<?=$carga?>" href="#" class="buttons icon icon_save excel_elementos" ><span></span><?php echo lang('global.guardar') ?></a></li>
    <?php if($id_locacion AND $id_locacion!="null"){
        $id_categoria = $id_locacion; 
    } ?>
    <li><a data-idproduccion="<?=$produccion['0']->id_produccion?>" data-categoria="<?=$id_categoria?>" data-carga="<?=$carga?>" href="#" class="buttons icon icon_print pdf_elementos" ><span></span><?php echo lang('global.imprimir') ?></a></li>
    <li><a href="#" class="buttons full_screen" alt="Fullscreen"><span class="open"></span><span class="close"></span>Fullscreen</a></li>
  </ul>
</nav>
<div id="inner_content">
  <div class="top_page top_page_escenas row">
    <div id="search_locations">
      <?php echo form_open($idioma.'elementos/buscar_categoria',' style="margin:0;" id="filtro_elementos"'); ?>
            
            <div class="">
              <div class="escenasNoProducidas">
              <?php
                if($carga==1){
                  echo lang('global.total').' ';
                  foreach ($categorias_elementos as $categoria_elementos) {
                    if($categoria_elementos['id']==$id_categoria){
                      echo $categoria_elementos['tipo'].': ';
                      break;
                    }
                  }
                  echo $total_elementos;
                } else if($carga==2){
                  echo lang('global.total_locacion').': '.count($total_locacion);
                } else{
                  echo lang('global.total_sets').': '.count($sets);
                }
              ?>
              </div>
            </div>
            <div id="div_filtros">
            
              <div class="div_input" style="margin-right:8px">
                <label for="capitulos_herramientas_from"><?php echo lang('global.categoria') ?>
                <select name="id_categoria" id="id_categoria">
                  <?php foreach ($categorias_elementos as $c) { ?>
                      <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['id']==$id_categoria){ ?> selected <?php } ?> ?><?php echo $c['tipo'] ?></option>
                  <?php } ?>
                  <option value="locacion" <?php if($carga==2 or $carga==3){ ?> selected<?php } ?>>Locaciones</option>
                  <option value="set" <?php if($carga==4){ ?> selected<?php } ?>>Set</option>
                  <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
                </select>
              </div>


              <div class="div_input" style="margin-right:4px">
                <label for="capitulos_herramientas_from"><?php echo lang('global.del_libro') ?>
                <select id="capitulos_herramientas_from" name="capitulos_herramientas_from">
                     <option value="null">seleccine una opcion</option>
                  <?php foreach ($capitulos as $capitulo) { ?>

                    <option data-idcapitulo="<?=$capitulo['id_capitulo']?>" value="<?=$capitulo['numero']?>"
                      <?php
                        if($desde==$capitulo['numero']){
                          echo "selected";
                        }
                      ?>
                      ><?=$capitulo['numero']?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="div_input" style="margin-right:8px">
                <label for="capitulos_herramientas_to"><?php echo lang('global.al_libreto') ?>
                <select id="capitulos_herramientas_to" name="capitulos_herramientas_to">
                   <option value="null">seleccine una opcion</option>
                  <?php $inc=count($capitulos)-1;foreach ($capitulos as $capitulo) { ?>
                    <option data-idcapitulo="<?=$capitulos[$inc]['id_capitulo']?>" value="<?=$capitulos[$inc]['numero']?>"
                    <?php
                      if($hasta==$capitulos[$inc]['numero']){
                        echo "selected";
                      }
                    ?>
                      ><?=$capitulos[$inc]['numero']?></option>
                  <?php --$inc; } ?>
                </select>
              </div>

            
              

              <!-- SECCION FILTRO POR ROL  -->
              <?php if($categorias_elementos[0]['id']==$id_categoria AND $carga==1){ ?>
                <?php if($roles){ 
                         $pro='';
                         $rep='';
                         $fig='';
                         $ext='';
                         foreach ($roles as $r) {
                             if($r=='Protagonista'){
                                $pro='checked=""';
                             }
                             if($r=='Reparto'){
                                $rep='checked=""';
                             }
                             if($r=='Figurante'){
                                $fig='checked=""';
                             }
                             if($r=='Extra'){
                                $ext='checked=""';
                             }
                         }
                  ?>
                   <div class="checksFilter" id="filter_roles">
                      <div class="left">
                        <label><input type="checkbox" <?php echo $pro ?> name="roles_personajes[]" class="roles_personajes" value="Protagonista"><?php echo lang('global.protagonista') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" <?php echo $rep ?> name="roles_personajes[]" class="roles_personajes" value="Reparto"><?php echo lang('global.reparto') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" <?php echo $fig ?> name="roles_personajes[]" class="roles_personajes" value="Figurante"><?php echo lang('global.figurante') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" <?php echo $ext ?> name="roles_personajes[]" class="roles_personajes" value="Extra"><?php echo lang('global.extra') ?></label>           
                      </div>
                  </div>
                <?php }else{ ?>
                  <div class="checksFilter" id="filter_roles">
                      <div class="left">
                        <label><input type="checkbox" checked="" name="roles_personajes[]" class="roles_personajes" value="Protagonista"><?php echo lang('global.protagonista') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" checked="" name="roles_personajes[]" class="roles_personajes" value="Reparto"><?php echo lang('global.reparto') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" checked="" name="roles_personajes[]" class="roles_personajes" value="Figurante"><?php echo lang('global.figurante') ?></label>           
                      </div>
                      <div class="left">
                        <label><input type="checkbox" checked="" name="roles_personajes[]" class="roles_personajes" value="Extra"><?php echo lang('global.extra') ?></label>           
                      </div>
                  </div>
                 <?php } ?> 
              <?php } ?>
                <input type="hidden" id="hasta_hidden" value="<?=$hasta?>"> 
                <input type="hidden" id="desde_hidden" value="<?=$desde?>">
                <input type="hidden" id="id_locacion_hidden" value="<?php echo $id_locacion?>">
                 <input class="button secondary load_page" type="submit" value="<?php echo lang('global.buscar') ?>">
              <!-- SECCION FILTRO POR ROL  -->

      <?php echo form_close()?>

    <div class="div_input" id="div_seach_input">
      <input type="text" 
      <?php if($carga==1){ ?>
        id="seach_tableElements"
      <?php } ?>
      <?php if($carga==2){ ?>
        id="seach_tableLocations"
      <?php } ?>
      <?php if($carga==3){ ?>
        id="seach_tableSets"
      <?php } ?>
      class="search_input">
    </div>
    <div class="alert-box alert" style="display:none">
        <?php echo lang('global.nodatos') ?><a href="" class="close">&times;</a>
    </div>
    </div>
    </div>

    <div class="blue_box box_save ">
      <form action="#" class="custom">
        <div class="row">
          <div class="columns seven">
            <label for="detalles" class="label_check"><input type="radio" name="save" id="detalles"> <?php echo lang('global.guardar_reporte_unidades') ?></label>
            <label for="sin_detalles" class="label_check"><input type="radio" name="save" id="sin_detalles"> <?php echo lang('global.guardar_reporte_sin_unidades') ?></label>
          </div>
          <div class="columns five">
            <div class="call_to_action align_right">
              <a href="#" class="button secondary icon icon_cancel cancel_"><span></span><?php echo lang('global.cancelar') ?></a>
              <a href="#" class="button secondary icon icon_print print_elements_button"><span></span><?php echo lang('global.imprimir') ?></a>
            </div>
          </div>
        </div>
      </form>
    </div>
  
  <div class="clr8"></div>
  <div class="categorias_elemento" style="display:none; clear:both;">
       <div>
        <label><?php echo lang('global.categorias') ?></label>
          <div class="row">
            <div class="column six">
              <ul>
                <?php $cont=0; ?>
                <?php foreach ($categorias_elementos as $c) { ?>
                  <?php echo form_open($idioma.'elementos/update_categoria'); ?>
                  <?php if($cont==0){ $style='';}else{$style='style=display:none';} ?>
                  <li class='categoria'>
                    <input name="categoria_nombre" type="text" class='categoria' id="<?php echo $c['id'] ?> " value="<?php echo $c['tipo'] ?>" readonly>
                    <script type="text/javascript"> categorias['<?php echo $cont ?>']='<?php echo $c["tipo"]?>';</script>
                    
                    <input name="id_categoria" name="id_categroia" type="hidden" class='categoria' id="categoria<?php echo $c['id'] ?> " value="<?php echo $c['id'] ?>">
                    <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
                    <?php $js='onClick="pregunta()"'  ?>
                    <?php if($c['tipo']!='Personaje') {?>
                      <?php if($usuario_permisos=="write"){ ?>
                        <div id="<?php echo $c['id']?>" class="eliminarCAtegoria"><?php echo lang('global.eliminar') ?></div>
                      <?php } ?>
                    <?php } ?>
                    <?php if($usuario_permisos=="write"){ ?>
                    <input type="submit" value="<?php echo lang('global.guardar') ?>" id='btnGuardar<?php echo $c['id']; ?>' class="btnGuardar button">
                    <?php } ?>
                    <?php echo form_close(); ?>
                  <?php $cont++; ?>
                <?php } ?>
                
              </ul>
              <label class="error"><?php echo form_error('categoria_nombre'); ?></label>
            </div>
            <?php if($usuario_permisos=="write"){ ?>
            <div class="column six">
              <?php echo form_open($idioma.'elementos/crear_categoria',' class="custom" onSubmit="return validar_categoria()"') ?>
                <label><?php echo lang('global.nombre_categoria') ?></label>
                <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
                <input type="text" value="" id="name_categoria" name="categoria_nombre_new" class="required">
                <label class="error"><?php echo form_error('categoria_nombre_new'); ?></label>
                <label class="error"><?php echo form_error('descripcion_new'); ?></label>
                <div class="align_right">
                  <input class="button" type="submit" value="<?php echo lang('global.crear_categoria') ?>">  
                </div>
              <?php echo form_close(); ?>
            </div>
            <?php } ?>
          </div>  
        </div>   
  </div>

  <div class="crear_elemento" style="display:none; clear:both;">
    <?php echo form_open($idioma.'elementos/crear_elemento','id="create_element"') ?>  
      <?php if($id_categoria){ ?>
        <input type="hidden" value="<?=$id_categoria?>" name="categoria_actual">
      <?php } ?>
        <div class="clr"></div>
      <div class="row">
        <div class="column four">
          <label><?php echo lang('global.tipo_elemento') ?></label>
          <select name="categoria" class="id_elemento">
          <?php foreach ($categorias_elementos as $c) { ?>
                <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['id']==$id_categoria){ ?> selected <?php } ?>><?php echo $c['tipo'] ?></option>
          <?php } ?>
          </select>
        </div>
        <div class="column four">
          <label><?php echo lang('global.nombre_elemento') ?></label>
          <input type="text" id="element_name" name="nombre">
          <label class="error"><?php echo form_error('nombre'); ?></label>
        </div>
        <div class="column four">
          <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
          <div class="rol_personaje"  >
            <label for="type"><?php echo lang('global.rol') ?>:</label>
            <select name="rol" data id="rol_personaje">
              <?php foreach ($rol_actores_elementos as $r) { ?>
                <option value="<?php echo $r['id'] ?>"><?php echo $r['rol'] ?></option>
              <?php } ?>
            </select> 
          </div>
        </div>
        <div class="column twelve">
          <label><?php echo lang('global.descripcion') ?>:</label>
          <textarea name="descripcion"></textarea>
          <input type="button" id="create_element_button" value="<?php echo lang('global.crear_elemento') ?>" class="button">
        </div>
      </div>
    <?php echo form_close() ?>
   </div>

  <div class="crear_locacion_set" style="display:none; clear:both;height:200px;">
    <input type="hidden" id="idproduccion" value="<?=$produccion[0]->id_produccion?>">
    <div class="left_dashed" style="width:34%;">
      <div class="row">
        <div class="column nine">
          <label for="location"><?php echo lang('global.locacion') ?></label>
          <select name="location" id="location" class="required" validate="required:true">
            <option value=""><?php echo lang('global.seleccion_locacion') ?></option>
           <?php if($locaciones){?> 
              <?php foreach ($locaciones as $l ) { ?> 
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
          <label for="set">set</label>
          <select name="set" id="set" class="set required" validate="required:true">
             <option value=""><?php echo lang('global.nodatos') ?></option>
          </select>
          <?php echo form_error('set'); ?>
          <input type="text" id="new_set" name="new_set" placeholder="" style="display:none;">
        </div>
        <div class="column three">
          <br>
          <a href="#" class="button secondary" id="new_item_set"><?php echo lang('global.Nuevo') ?></a>
          <a href="#" class="button" id="add_item_set" style="display:none;"><?php echo lang('global.agrgar') ?></a>
          <a href="#" class="button" id="cancel_item_set" style="display:none;"><?php echo lang('global.cancelar') ?></a>
          <a href="#" class="button" id="add_item_location" style="display:none;"><?php echo lang('global.agregar') ?></a>
          <a href="#" class="button" id="cancel_item_location" style="display:none;"><?php echo lang('global.cancelar') ?></a>
        </div>
      </div>
    </div>
  </div>


    <?php if($categorias_elementos[0]['id']!=$id_categoria) {
      $hide = 'style="display:none"';
    }else{
      $hide="";
    } 
    ?>

  <?php if($carga==1){ ?>
            <!-- tabla elementos-->  
            <?php if($elementos){ ?> 
                   <input type="hidden" id="limite_elementos" value="<?=($total_elementos)?>">
                   <input type="hidden" id="limite_actual" value="30">
                   <input type="hidden" id="limite_escena" value="30">
            <?php } ?>
            </div>    
            <div id="scroll">
              <div class="normal_table">
                <table id="usersTable" class="tablesorter tabla_filtro_general">
                  <thead>

                      <th width="15%"><?php echo lang('global.nombre') ?></th>
                      <th width="15%" <?=$hide?>><?php echo lang('global.rol') ?></th>
                      <?php if($hide!=""){ ?>
                      <th width="20%"><?php echo lang('global.descripcion') ?></th>
                      <?php }else{ ?>
                      <th width="10%"><?php echo lang('global.actor') ?></th>
                      <?php if($hide==""){ ?>
                      <th width="10%"><?php echo lang('global.total_libretos') ?></th>
                      <?php } ?>
                      <th width="10%"><?php echo lang('global.contrato') ?></th>
                      <?php } ?>
                      <th width="8%"><?php echo lang('global.usos') ?></th>
                      <th width="8%">% <?php echo lang('global.de_usos') ?></th>
                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                  </thead>
                  <tbody class="ui-selectable" id="tabla_elementos">
                   <?php $count=0; if($elementos){ ?> 
                    <?php $limite=0;foreach ($elementos as $e) { ++$limite; ?>
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                    <?php $esc_producidas=$this->model_elementos->escenas_producidas($e['id_elemento']); ?>
                    <?php $esc_porproducir=$this->model_elementos->escenas_proproducir($e['id_elemento']); ?>
                    <?php 

                    if($esc_producidas[0]->total==0){
                    $por=0;
                    }else{
                    $por=$esc_producidas[0]->total;
                    $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                    $por2=$esc_porproducir[0]->total;
                    $por2=round($por2);  
                    }?>
                    <?php $pot_total=abs($por-$por2); ?>
                    <tr class="actionAsing ui-selectee <?php echo 'element_'.$e['id_elemento'];?> diagrama" data-tr="<?=$e['id_elemento']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$por2?>" data-noasignadas="<?= abs($por2-$total_escenas[0]->total);  ?>" data-idcahrt="<?= $e['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $e['id_elemento'] ?>" data-tipo="1">
                      <td class="element_name"><?php echo $e['nombre']?></td>
                      <?php if(!$e['rol']){ $rol='';} ?>
                      <td <?=$hide?> class="rol_name" ><?php echo $e['rol']; ?></td>
                      <?php if($hide!=""){ ?>
                      <td><?php echo Elementos::corta_palabra($e['des_elem'],32);
                      if(strlen($e['des_elem'])>32){
                        echo '...';
                      }
                      ?></td>
                      <?php }else{ ?>
                      <td><?=$e['actor_nombre']." ".$e['actor_apellido']?></td>
                      <?php if($hide==""){ ?>
                      <?php $libretos_elementos=$this->model_elementos->libretos_elementos($e['id_elemento']);
                       $libretos_elementos=$libretos_elementos['0']->libretos?>
                      <td style="text-align:center"><?php echo count(explode(',', $libretos_elementos)); ?></td>
                      <?php } ?>
                      <td><?=$e['tipo_contrato']?></td>
                      <?php } ?>
                      <?php $uso=$this->model_elementos->escenas_has_elementos($e['id_elemento']); ?>
                      <td style="text-align:center"><?php echo $uso; ?></td>
                      <?php
                      if($uso==0){
                        $por=0;
                      }else{
                        $por=($uso*100)/$total_escenas[0]->total;
                        $por=round($por);  
                      }
                       ?>
                      <td style="text-align:center"><?php echo $por; ?>%</td>
                      
                      <td style="text-align:center"><?php echo $esc_producidas[0]->total; ?></td>
                      
                      <td style="text-align:center"><?php echo $esc_porproducir[0]->total; ?></td>
                    
                      
</tr>
                    <!---carga ajax -->
                    <tr class="info_element ui-selectee element_<?=$e['id_elemento']?>" data-tr="<?=$e['id_elemento']?>" >
                      <td style="text-align:center;display:none;"><?php echo $e['nombre']?></td>
                      <?php if(!$e['rol']){$rol='';} ?>
                      <td <?=$hide?> style="text-align:center;display:none;"><?php echo $e['rol']; ?></td>
                      <?php if($hide!=""){ ?>
                      <td style="text-align:center;display:none;"><?php echo Elementos::corta_palabra($e['des_elem'],32);
                      if(strlen($e['des_elem'])>32){
                        echo '...';
                      }
                      ?></td>
                      <?php }else{ ?>
                        <td style="text-align:center;display:none;"><?=$e['actor_nombre']." ".$e['actor_apellido']?></td>
                        <?php $libretos_elementos=$this->model_elementos->libretos_elementos($e['id_elemento']);
                       $libretos_elementos=$libretos_elementos['0']->libretos?>
                        <td style="text-align:center;display:none;"><?php echo count(explode(',',$libretos_elementos)); ?>dsasada</td>
                        <td style="text-align:center;display:none;"><?=$e['tipo_contrato']?></td>
                      <?php } ?>
                      <?php $uso=$this->model_elementos->escenas_has_elementos($e['id_elemento']); ?>
                      <td style="text-align:center;display:none;"><?php echo $uso; ?></td>
                      <?php 
                      if($uso==0){
                        $por=0;
                      }else{
                        $por=($uso*100)/$total_escenas[0]->total;
                      $por=round($por);  
                      }
                       ?>
                      <td style="text-align:center;display:none;"><?php echo $por; ?>%</td>
                      
                      <td style="text-align:center;display:none;"><?php echo $esc_producidas[0]->total; ?></td>
                      
                      <td style="text-align:center;display:none;"><?php echo $esc_porproducir[0]->total; ?></td>
                      
                      <td colspan="9">
                          <!--grafico torta-->
                        <div class="row">
                          <!-- LISTADO DE LIBRETOS -->
                          <div class='columns twelve'>
                          <?php $libretos_elementos=$this->model_elementos->libretos_elementos($e['id_elemento']);
                          $libretos_elementos=$libretos_elementos['0']->libretos?>
                            <span class="title_graphic"><h5>Libretos: <span><?php echo str_replace(',', ', ', $libretos_elementos); ?></span></h5></span>
                          </div>
                          <!-- LISTADO DE LIBRETOS -->
                            <div class="columns six">
                              <div class="torta_porcentaje">
                           
                            <div class="row">
                                <div class="column six">
                                  <div id="chart_div<?php echo $e['nombre']?>" ></div>
                                  <div class="convencionesPieChart">
                                    <ul>
                                      <li> <span class="yellow"></span> <?php echo lang('global.escenas_producidas') ?></li>
                                      <li> <span class="pink"></span> <?php echo lang('global.escenas_no_producidas') ?></li>
                                      <li> <span class="gray"></span> <?php echo lang('global.escenas_asignadas') ?></li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column six">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4><?php echo lang('global.escenas_producidas') ?> </h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4><?php echo lang('global.escenas_no_producidas') ?> </h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> <?php echo lang('global.escenas_creadas') ?></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            </div>
                            
                           <!--fin grafica torta-->
                            <!-- Inicio graficas tablas -->
                            
                            <div class="columns six">
                            <div id="loadElements<?=$e['id_elemento']?>" class="loadElements" style="height: 301px;width: 99%;">
                            </div>
                            <div class="barras_estadisticas">
                              <?php  $total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion); ?>
                              <script type="text/javascript">
                              var escenasAsignadas<?php echo $e['id_elemento']?>=new Array();
                              var escenasCapitulo<?php echo $e['id_elemento']?>=new Array();
                              </script>
                            <script type="text/javascript"></script>
                            <div id="capitulos_chart<?php echo $e['id_elemento']?>" style="min-width: 560px; height: 250px;"></div>
                              <label><?php echo lang('global.total_libretos') ?>: <?php echo count($total_capitulos); ?></label>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->


                          <!-- AREA INFORMACION ADICIONAL ELEMENTO -->
                            <?php if($categorias_elementos[0]['id']==$id_categoria) { ?>
                              
                             

                              <div class="columns twelve">
                              <ul class="div_cell">
                                <li>
                                  <label><?php echo lang('global.documento') ?>:</label>
                                  <?=$e['documento_actor']?>
                                  </li>
                                <li>
                                  <label><?php echo lang('global.tipo_documento') ?>:</label>
                                  <?=$e['tipo_documento']?>
                                </li>
                                <li>
                                  <label><?php echo lang('global.monto') ?>:</label>
                                  <?=number_format($e['monto'], 2, '.', ",")?>
                                </li>
                                <li>
                                  <label><?php echo lang('global.tipo_moneda') ?>:</label>
                                  <?=$e['tipo_moneda']?>
                                </li>
                                  <?php 
                                  $fecha_inicio = ""; 
                                  $fecha_finalizacion = ""; 
                                  if($e['fecha_finalizacion'] AND $e['fecha_finalizacion']!= '0000-00-00'){
                                    $fecha_finalizacion = $e['fecha_finalizacion'];
                                  }
                                  $e_fecha_inicio_2=$this->model_elementos->elementos_fecha2($e['id_elemento']);
                                  if($e['fecha_inicio'] AND $e['fecha_inicio']!= '0000-00-00'){
                                    $fecha_inicio = $e['fecha_inicio'];
                                  }else if($e_fecha_inicio_2){
                                    $fecha_inicio = $e_fecha_inicio_2['0']->fecha_inicio_2;
                                  } 
                                  ?>
                                <li>
                                  <label><?php echo lang('global.fecha_inicia') ?>:</label>
                                  <?=$fecha_inicio?>
                                </li>
                                <li>
                                  <label><?php echo lang('global.fecha_finalizacion') ?>:</label>
                                  <?=$fecha_finalizacion?>
                                </li>
                              </ul>

                              </div>
                            <?php } ?>
                            <!-- FIN AREA INFORMACION ADICIONAL ELEMENTO -->

                            <!--editar elementos -->
                            <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){ ?>
                              <div class="columns twelve" style="margin-bottom:10px">
                                  <label class="editar_elemento button" id="<?php echo $e['id_elemento'] ?>"><?php echo lang('global.editar_elemento') ?></label>
                                  <?php if($uso==0){ ?>
                                  <input type="button" value="<?php echo lang('global.eliminar') ?>" id="<?php echo $e['id_elemento'] ?>" class="button secondary eliminar_elemento">   
                                  <?php  } ?>
                              </div>
                            <!--fin editar elementos -->


                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $e['id_elemento']?>">
                              <br>
                              <?php echo form_open($idioma.'elementos/editar_elemento') ?> 
                                <div class="row">
                                    <div class="column six">
                                      <label><?php echo lang('global.tipo_elemento') ?></label>
                                      <select name="categoria" id="id_elemento2">
                                      
                                      <?php foreach ($categorias_elementos as $c) { ?>
                                          <option id="<?php echo $c['tipo']?>" class="elemento2_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['tipo']==$e['tipo']){ echo "selected";} ?> ><?php echo $c['tipo'] ?></option>
                                      <?php } ?>
                                      </select> 

                                      <?php if($e['tipo']=='Personaje' and $produccion['0']->tipo_produccion==2){ ?>  
                                      <label><?php echo lang('global.actor') ?></label>
                                      <div class="column three">
                                        <label><?php echo lang('global.nombre') ?></label>
                                        <input type="text" name="actor_nombre" value="<?=$e['actor_nombre']?>" readonly>
                                      </div>
                                      <div class="column three">
                                        <label><?php echo lang('global.apellido') ?></label>
                                        <input type="text" name="actor_apellido" value="<?=$e['actor_apellido']?>" readonly>
                                      </div>
                                      <div class="column three">
                                        <label><?php echo lang('global.documento') ?></label>
                                        <input type="text" name="documento" value="<?=$e['documento_actor']?>" readonly>
                                      </div>
                                      <div class="column three">
                                        <label><?php echo lang('global.tipo_documento') ?></label>
                                        <select name="tipo_documento" disabled>
                                            <option><?php echo lang('global.seleccion_opcion') ?></option>
                                          <?php foreach ($tipos_documento as $tipo_documento) { ?>
                                            <?php if($tipo_documento->id==$e['id_tipo_documento']){
                                              echo "selected";
                                            }?>
                                            <option <?php if($tipo_documento->id==$e['id_tipo_documento']){
                                              echo "selected";
                                            }?> value="<?=$tipo_documento->id?>"><?=$tipo_documento->descripcion?></option>
                                          <?php } ?>
                                        </select>
                                      </div>
                                      <?php } ?>
                                    </div>
                                    <div class="column six">
                                      <label><?php echo lang('global.nombre_elemento') ?></label>
                                      <input type="hidden" name="id_elemento" value="<?php echo $e['id_elemento']?>" >
                                      <input type="hidden" name="id_locacion" value="<?php echo $id_locacion?>" >
                                      <input type="hidden" name="desde" value="<?php echo $desde?>" >
                                      <input type="hidden" name="hasta" value="<?php echo $hasta?>" >
                                      <?php 
                                        $roles_s=null;
                                         if($roles){
                                            $cont=0;
                                            foreach ($roles as $r) {
                                              if($r){
                                                 if($cont==0){
                                                    $roles_s=$r.'-';  
                                                  }else{
                                                    $roles_s=$roles_s.''.$r;
                                                  }
                                                  $cont++;
                                                  }
                                            }
                                          }
                                       ?>
                                      <input type="hidden" name="roles" value="<?php echo $roles_s?>" >

                                      <input type="text" name="nombre" value="<?php echo $e['nombre']?>">
                                      <label class="error"><?php echo form_error('nombre'); ?></label>   
                                      <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
                                      <?php if($e['tipo']=='Personaje'){ $c=''; }else{$c='display:none';}?> 
                                      <div class="rol_personaje2" style="<?php echo $c; ?>">
                                      <label for="type"><?php echo lang('global.rol') ?>:</label>
                                      <select name="rol" data-rol="<?php echo $e['rol'] ?>" class="rol_personaje">
                                        <?php /*foreach ($rol_actores_elementos as $r) { ?>
                                          <option value="<?php echo $r['id'] ?>" <?php if($e['rol']==$r['rol']){ echo "selected"; } ?> ><?php echo $r['rol'] ?></option>
                                        <?php }*/ ?>
                                        <option  <?php if($e['rol']=="Protagonista"){ echo "selected"; } ?> value="1"><?php echo lang('global.protagonista') ?></option>
                                        <option  <?php if($e['rol']=="Reparto"){ echo "selected"; } ?> value="3"><?php echo lang('global.reparto') ?></option>
                                        <option  <?php if($e['rol']=="Figurante"){ echo "selected"; } ?> value="2"><?php echo lang('global.figurante') ?></option>
                                        <option  <?php if($e['rol']=="Extra"){ echo "selected"; } ?> value="4"><?php echo lang('global.extra') ?></option>
                                      </select> 
                                      </div>
                                    </div>
                                </div>

                                <?php if($e['tipo']=='Personaje' and $produccion['0']->tipo_produccion==2){ ?>  
                                <div class="row">
                                  <div class="column twelve">
                                    <div class="column two">
                                        <label><?php echo lang('global.monto') ?></label>
                                        <input type="text" class="monto_personaje" name="monto" value="<?=$e['monto']?>" readonly>
                                    </div>
                                    <div class="column two">
                                        <label><?php echo lang('global.tipo_moneda') ?></label>
                                        <select name="tipo_moneda" disabled>
                                           <option><?php echo lang('global.seleccion_opcion') ?></option>
                                          <?php foreach ($tipos_moneda as $tipo_moneda) { ?>
                                            <option <?php if($tipo_moneda->id==$e['id_tipo_moneda']){
                                              echo "selected";
                                            }?>  value="<?=$tipo_moneda->id?>"><?=$tipo_moneda->descripcion?></option>
                                          <?php } ?>
                                        </select>                                   
                                    </div>
                                    <div class="column two">
                                      <label><?php echo lang('global.tipo_contrato') ?></label> 
                                      <select name="cotrato" disabled>
                                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                                        <?php foreach ($contratos as $contrato) { ?>
                                          <?php 
                                          $select=""; 
                                          if($contrato->id == $e['id_tipo_contrato']){ 
                                            $select="selected"; 
                                          }?>
                                          <option <?=$select?> value="<?=$contrato->id?>"><?=$contrato->descripcion?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                    <div class="column three">
                                        <label><?php echo lang('global.fecha_inicio') ?></label>
                                        <?php
                                        $fecha_inicio = ""; 
                                        $fecha_finalizacion = ""; 
                                        if($e['fecha_finalizacion'] AND $e['fecha_finalizacion']!= '0000-00-00'){
                                          $fecha_finalizacion = $e['fecha_finalizacion'];
                                        }
                                        $e_fecha_inicio_2=$this->model_elementos->elementos_fecha2($e['id_elemento']);
                                        if($e['fecha_inicio'] AND $e['fecha_inicio']!= '0000-00-00'){
                                          $fecha_inicio = $e['fecha_inicio'];
                                        }else if($e_fecha_inicio_2){
                                          $fecha_inicio = $e_fecha_inicio_2['0']->fecha_inicio_2;
                                        }
                                        ?>
                                        <input type="text" class="datepicker ui-datepicker-trigger" name="fecha_inicio" value="<?=$fecha_inicio?>" readonly>
                                    </div>

                                    <div class="column three">
                                        <label><?php echo lang('global.fecha_finalizacion') ?></label>
                                        <input type="text" class="datepicker ui-datepicker-trigger" name="fecha_finalizacion" value="<?=$fecha_finalizacion?>" readonly>
                                    </div>
                                    
                                  </div>
                                </div>

                                <?php 
                                    if($e['fecha_liquidacion'] AND $e['fecha_liquidacion']!= '0000-00-00'){
                                          $fecha_liquidacion = $e['fecha_liquidacion'];
                                        }else{
                                          $fecha_liquidacion ='';
                                        }
                                 ?>
                                <div class="row">
                                   <div class="column two ">
                                        <label><?php echo lang('global.fecha_liquidacion') ?></label>
                                        <input type="text" class="datepicker ui-datepicker-trigger" name="fecha_liquidacion" value="<?=$fecha_liquidacion?>">
                                    </div>
                                </div>
                                <?php } ?>

                                  <div class="row">
                                    <?php $estilo_t=""; 
                                    if($categorias_elementos[0]['id']==$id_categoria) {
                                      $estilo_t = "display:none;";
                                    } ?>
                                    <div style="<?=$estilo_t?>" class="column twelve">
                                      <label>Descripción:</label>
                                      <textarea name="descripcion"><?php echo $e['des_elem'] ?></textarea>    
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="column twelve">
                                      <input type="submit" value="Actualizar Elemento" class="button">
                                      <input type="button" value="<?php echo lang('global.cancelar') ?>" id="<?php echo $e['id_elemento'] ?>" class="button secondary cancel_edit_element">
                                    </div>
                                  </div>
                              <?php echo form_close() ?>
                            </div>

                            <div class="clr6"></div>
                            <!--tabla planes elemento -->
                            <?php $planes_elemento = $this->model_elementos->planes_elemento($e['id_elemento']);
                            if($planes_elemento){?>
                            <div class="columns twelve">

                            <table class="anyid" id="anyid" >
                              <tr>

                                <th class="header">Lib</th>
                                <th class="header"><?php echo lang('global.escenas') ?></th>
                                <th class="header"><?php echo lang('global.locacion') ?></th>
                                <th class="header"><?php echo lang('global.set') ?></th>
                                <th class="header"><?php echo lang('global.fecha_grabacion') ?></th>
                                <th class="header"><?php echo lang('global.unidad') ?></th>
                                <th class="header"><?php echo lang('global.producidas') ?></th>

                              </tr>
                              <?php foreach ($planes_elemento as $plan_elemento) {?>
                                <tr>
                                  <td><?=$plan_elemento->numero_libreto?></td>
                                  <td><?=$plan_elemento->numero_escena?></td>
                                  <td><?=$plan_elemento->nombre_locacion?></td>
                                  <td><?=$plan_elemento->nombre_set?></td>
                                  <td> <span style="display: inline-block;width: 1px; overflow:hidden; text-indent:-999"><?php echo date('Y-m-d',strtotime($plan_elemento->fecha_grabacion)) ?></span> <?=strftime('%Y-%b-%d',strtotime($plan_elemento->fecha_grabacion));?></td>

                                  <td><?=$plan_elemento->numero_unidad?></td>
                                  <td>
                                    <?php if($plan_elemento->producida=="0"){
                                      echo 'NO';
                                    }else{
                                      echo "SI";
                                    }
                                    ?>
                                  </td>
                                </tr>
                              <?php } ?>
                            </table>
                            </div>
                            <?php } ?>
                            <!--tabla planes elemento -->
                            <?php  } ?>

                          <!--fin editar -->
                        </div>    
                      </td>
                    </tr>
                    <!---------------->  

                    <?php } ?>
                  <?php }else{?>
                    <tr>
                      <td colspan="7"><?php echo lang('global.no_hay_elemento') ?></td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
                </div>
              </div>
              <?php if($total_elementos>30){ ?>
                <input type="button" style="width:100%!important;" id="cargar_elementos" value="<?php echo lang('global.ver_mas') ?>" class="button">
              <?php } ?>

            <!-- fin tabla -->  
   <?php }elseif($carga==2){ ?>
                <!-- tabla locaciones-->  
      <div id="search_locations" style="float: inherit;">
        <?php echo form_open($idioma.'elementos/buscar_sets'); ?>
            <?php if($locacion){ ?> 
               <input type="hidden" id="limite_elementos" value="<?=(count($total_locacion))?>">
               <input type="hidden" id="limite_actual" value="30">
               <input type="hidden" id="limite_escena" value="30">
            <?php } ?> 
            <input type="hidden" name="capitulos_herramientas_from" id="capitulos_for">
            <input type="hidden" name="capitulos_herramientas_to" id="capitulos_to">
              <select name="locacion" >
                <?php foreach ($locacion as $l) { ?>
                      <option value="<?php echo $l['id'] ?>" > <?php echo $l['nombre'] ?></option>
                <?php } ?>
              </select>  
              <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
              <input class="button secondary" id="search_sets" type="submit" value="<?php echo lang('global.buscar') ?>">
        </form>
      </div>
            </div>
            <div id="scroll">
              <div class="normal_table">
                <table id="usersTable" class="tablesorter tabla_filtro_general">
                  <thead>

                      <th width="20%"><?php echo lang('global.nombre') ?></th>
                      <th width="10%"><?php echo lang('global.usos') ?></th>
                      <th width="10%">% <?php echo lang('global.de_usos') ?></th>

                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                  </thead>
                  <tbody class="ui-selectable" id="tabla_elementos">
                   <?php $count=0;   if($locacion){ ?> 
                   <input type="hidden" id="limite_elementos" value="<?=(count($total_locacion))?>">
                   <input type="hidden" id="limite_actual" value="30">
                   <input type="hidden" id="limite_locaciones" value="30">
                    <?php $limite=0;foreach ($locacion as $l) { ++$limite;?>
                    
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                  <?php $esc_producidas=$this->model_elementos->escenas_producidas_idcapitulo($l['id']); ?>
                  <?php $esc_porproducir=$this->model_elementos->escenas_porproducidas_idcapitulo($l['id']); ?>
                    <?php 

                    
                    if($esc_producidas[0]->total==0){
                      $por=0;
                    }else{
                      $por=$esc_producidas[0]->total;
                      $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                      $por2=$esc_porproducir[0]->total;
                      $por2=round($por2);  
                    }?>
                    <?php $pot_total=abs($por-$por2); ?>
                    <tr class="actionAsing ui-selectee <?php echo 'element_'.$l['id'];?> diagrama" data-tr="<?=$l['id']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$pot_total?>" data-noasignadas="<?= abs($por2-$total_escenas[0]->total);  ?>" data-idcahrt="<?= $l['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $l['id'] ?>" data-tipo="2">
                      <td class="element_name"><?php echo $l['nombre']?></td>
                      <?php $uso=$l['uso'];?>
                      <td style="text-align:center"><?php echo $uso; ?></td>
                      <?php if($uso==0){
                        $por=0;
                      } else{
                        $por=($uso*100)/$total_escenas[0]->total;
                        $por=round($por);  
                      }
                       ?>
                      <td style="text-align:center"><?php echo $por; ?>%</td>
                      <td style="text-align:center"><?php echo $esc_producidas[0]->total; ?></td>
                      <td style="text-align:center"><?php echo $esc_porproducir[0]->total; ?></td>
                    </tr>
                    <!---carga ajax -->
                    <tr class="info_element element_<?=$l['id']?>" data-tr="<?=$l['id']?>" >
                      <td colspan="7">
                          <!--grafico torta-->
                        <div class="row">
                            <div class="columns six">
                              <div class="torta_porcentaje">
                           
                           
                            <div class="row">
                                <div class="column seven">
                                  <div id="chart_div<?php echo $l['nombre']?>"></div>
                                  <div class="convencionesPieChart">
                                    <ul>
                                      <li> <span class="yellow"></span> <?php echo lang('global.escenas_producidas') ?></li>
                                      <li> <span class="pink"></span> <?php echo lang('global.escenas_no_producidas') ?></li>
                                      <li> <span class="gray"></span> <?php echo lang('global.escenas_asignadas') ?></li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column five">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4><?php echo lang('global.escenas_producidas') ?></h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4><?php echo lang('global.escenas_no_producidas') ?></h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> <?php echo lang('global.escenas_creadas') ?></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            </div>
                            
                            <!--fin grafica torta-->
                            <!-- Inicio graficas tablas -->

                            <div class="columns six">
                            <div id="loadElements<?=$l['id']?>" class="loadElements" style="height: 301px;width: 99%;">
                            </div>
                            <div class="barras_estadisticas">
                              <?php  $total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion); ?>
                            <div id="capitulos_chart<?php echo $l['id']?>" style="min-width: 560px; height: 250px;"></div>
                              <label><?php echo lang('global.total_libretos') ?>: <?php echo count($total_capitulos); ?></label>
                              
                              <script type="text/javascript">

                              </script>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->

                              <!--editar elementos -->
                            <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2) { ?>
                            <div class="columns twelve">
                                <label class="editar_elemento button" id="<?php echo $l['id'] ?>"><?php echo lang('global.editar_elemento') ?></label>
                                <?php if($uso==0){ ?>
                                  <input type="button" value="<?php echo lang('global.eliminar') ?>" id="<?php echo $l['id'] ?>" class="button secondary eliminar_locacion">   
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $l['id']?>">
                              <br>
                                  <div class="row">
                                    <div class="column six">
                                      <label><?php echo lang('global.nombre_elemento') ?></label>
                                      <label class="error"><?php echo form_error('nombre'); ?></label>
                                      <input type="hidden" name="id_locacion" class="id_locacion" value="<?php echo $l['id']?>" >
                                      <input type="text" name="nombre" class="nombre" value="<?php echo $l['nombre']?>">
                                      <input type="hidden" value="<?php echo $id_produccion ?>" class="id_produccion" name="id_produccion"> 
                                      <input type="submit" value="Actualizar Elemento" data-nombre="<?= $l['nombre']?>" class="button actualizar_locacion">
                                      <input type="button" value="<?php echo lang('global.cancelar') ?>" id="<?php echo $l['id'] ?>" class="button secondary cancel_edit_element">
                                    </div>
                                  </div>
                              <?php echo form_close() ?>
                              </div>
                            </div>

                          <!--fin editar -->
                          <?php } ?>

                        </div>    
                      </td>
                    </tr>
                    <!----------------> 

                   <?php }?>
                  <?php }else{?>
                    <tr>
                      <td colspan="7"><?php echo lang('global.no_hay_elemento') ?></td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
                <?php if(count($total_locacion)>30){ ?>
                  <input type="button" style="width:100%!important;" id="cargar_locaciones" value="<?php echo lang('global.ver_mas') ?>" class="button">
                <?php } ?>
              </div>

            <!-- fin tabla locaciones -->  
   <?php }else{ ?>
        <!-- tabla sets -->
        <div id="search_locations">
           <?php echo form_open($idioma.'elementos/buscar_sets'); ?>
            <input type="hidden" name="capitulos_herramientas_from" id="capitulos_for">
            <input type="hidden" name="capitulos_herramientas_to" id="capitulos_to">
            <?php if($carga!=4){?>
            <select name="locacion" >

              <?php foreach ($locacion as $l) { ?>
                    <option value="<?php echo $l['id'] ?>" <?php if($id_locacion==$l['id']){ ?> selected <?php } ?>> <?php echo $l['nombre'] ?></option>
              <?php } ?>
            </select>  
            
            <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
            <input class="button secondary" type="submit" value="buscar">
            <?php } ?>
      </form>
            </div>
            </div> 
            <div id="scroll">
              <?php if($sets){ ?> 
                <input type="hidden" id="limite_elementos" value="<?=(count($sets)-1)?>">
                <input type="hidden" id="limite_actual" value="30">
              <?php } ?>
              <div class="normal_table">
                <table id="usersTable" class="tablesorter tabla_filtro_general">
                  <thead>

                      <th width="15%"><?php echo lang('global.nombre') ?></th>
                      <?php if($carga==4){ ?>
                      <th width="20%"><?php echo lang('global.locacion') ?></th>
                      <?php } ?>
                      <?php if(isset($l['descripción'])){ ?>
                        <th width="20%" <?=$hide?>>Rol</th>
                        <th width="20%"><?php echo lang('global.descripcion') ?></th>
                      <?php } ?>
                      <th width="10%"><?php echo lang('global.usos') ?></th>
                      <th width="10%">% <?php echo lang('global.de_usos') ?></th>
                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                  </thead>
                  <tbody class="ui-selectable" id="tabla_elementos">
                   <?php $count=0;   if($sets){ ?> 
                    <?php $limite=0;  foreach ($sets as $l) { ++$limite;?>
                    <?php if($limite>30){
                      break;
                    } ?>
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                  <?php $esc_producidas=$this->model_elementos->escenas_producidas_id_sets($l['id']); ?>
                  <?php $esc_porproducir=$this->model_elementos->escenas_porproducidas_idsets($l['id']); ?>
                    <?php 

                    if($esc_producidas[0]->total==0){
                    $por=0;
                    }else{
                    $por=$esc_producidas[0]->total;
                    $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                    $por2=$esc_porproducir[0]->total;
                    $por2=round($por2);  
                    }?>
                    <?php $pot_total=$por2; ?>
                    <tr class="actionAsing ui-selectee <?php echo 'element_'.$l['id'];?> diagrama" data-tr="<?=$l['id']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$pot_total?>" data-noasignadas="<?= abs($total_escenas[0]->total-$por-$por2);  ?>" data-idcahrt="<?= $l['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $l['id'] ?>" data-tipo="3">
                      <td class="element_name"><?php echo $l['nombre']?></td>


                      <!-- VALIDACION PARA MOSTRAR EL NOMBRE DE LA LOCACION -->
                      <?php if ($carga==4) { ?>

                        <td><?=$l['nombre_locacion']?></td>
                      <?php } ?>

                      <?php if(isset($l['descripción'])){ ?>
                      <td <?=$hide?>></td>
                      <td></td>
                      <?php } ?>
                      <?php $uso=$l['uso'];?>
                      <td style="text-align:center"><?php echo $uso; ?></td>
                      <?php if($uso==0){
                        $por=0;
                      }else{
                        $por=($uso*100)/$total_escenas[0]->total;
                        $por=round($por);  
                      }
                       ?>
                      <td style="text-align:center"><?php echo $por; ?>%</td>
                      
                      <td style="text-align:center"><?php echo $esc_producidas[0]->total; ?></td>
                      
                      <td style="text-align:center"><?php echo $esc_porproducir[0]->total; ?></td>
                    </tr>
                    <!---carga ajax -->
                    <tr class="info_element element_<?=$l['id']?>" data-tr="<?=$l['id']?>" >
                      <td colspan="7">
                          <!--grafico torta-->
                        <div class="row">
                            <div class="columns six">
                              <div class="torta_porcentaje">
                           
                           
                            <div class="row">
                                <div class="column seven">
                                  <div id="chart_div<?php echo $l['nombre']?>"></div>
                                  <div class="convencionesPieChart">
                                    <ul>
                                      <li> <span class="yellow"></span> <?php echo lang('global.escenas_producidas') ?> </li>
                                      <li> <span class="pink"></span> <?php echo lang('global.escenas_no_producidas') ?> </li>
                                      <li> <span class="gray"></span> <?php echo lang('global.escenas_asignadas') ?> </li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column five">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4> <?php echo lang('global.escenas_producidas') ?> </h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4> <?php echo lang('global.escenas_no_producidas') ?> </h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> <?php echo lang('global.escenas_creadas') ?></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            </div>
                            
                            <!--fin grafica torta-->
                            <!-- Inicio graficas tablas -->
                            <div class="columns six">
                            <div id="loadElements<?=$l['id']?>" class="loadElements" style="height: 301px;width: 99%;">
                            </div>
                            <div class="barras_estadisticas">
                              <?php  $total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion); ?>
                            <div id="capitulos_chart<?php echo $l['id']?>" style="min-width: 560px; height: 250px;"></div>
                              <label><?php echo lang('global.total_libretos') ?>: <?php echo count($total_capitulos); ?></label>
                              
                              <script type="text/javascript">

                              </script>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->
                            <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2) { ?>
                           <!--editar elementos -->
                            <div class="columns twelve">
                                <label class="editar_elemento button" id="<?php echo $l['id'] ?>"><?php echo lang('global.editar_elemento') ?></label>
                                <?php if($uso==0){ ?>
                                  <input type="button" value="<?php echo lang('global.eliminar') ?>" id="<?php echo $l['id'] ?>" class="button secondary eliminar_sets">   
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $l['id']?>">
                              <br>
                                  <div class="row">
                                    <div class="column six">
                                      <label><?php echo lang('global.nombre_elemento') ?></label>
                                      <label class="error"><?php echo form_error('nombre'); ?></label>   
                                      <input type="hidden" name="id_elemento" class="id_set"  value="<?php echo $l['id']?>" >
                                      <input type="text" name="nombre" class="nombre" value="<?php echo $l['nombre']?>">
                                      <input type="hidden" value="<?php echo $id_locacion ?>" class="id_locacion" name="id_locacion">
                                      <input type="submit" value="Actualizar Elemento" data-nombre="<?=$l['nombre']?>" class="button actualizar_set">
                                      <input type="button" value="<?php echo lang('global.cancelar') ?>" id="<?php echo $l['id'] ?>" class="button secondary cancel_edit_element">
                                    </div>
                                  </div>
                              </div>
                              <?php } ?>
                            </div>

                          <!--fin editar -->
                        </div>    
                      </td>
                    </tr>
                    <!---------------->  
                    
                    <?php }?>

                  <?php }else{?>
                    <tr>
                      <td colspan="7"><?php echo lang('global.no_hay_elemento') ?></td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
                <?php if($carga==4 AND (count($sets)-1)>30){ ?>
                      <input type="button" style="width:100%!important;" id="cargar_sets" value="<?php echo lang('global.ver_mas') ?>" class="button">
                <?php } ?>
              </div>
            <!-- fin tabla sets --> 
   <?php } ?>
  </div>
</div>