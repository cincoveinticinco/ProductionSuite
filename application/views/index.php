<div id="breadcrumbs">
  <a href="<?php echo base_url('produccion/producciones'); ?>">Home</a> / <a href="<?php echo base_url('/plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / Elementos
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<div id="inner_content">
  <div class="top_page top_page_escenas row">
    <div id="search_locations">
      <?php echo form_open('elementos/buscar_categoria'); ?>
            <select name="id_categoria" id="id_categoria">
              <?php foreach ($categorias_elementos as $c) { ?>
                    <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['id']==$id_categoria){ ?> selected <?php } ?> ?><?php echo $c['tipo'] ?></option>
              <?php } ?>
              <option value="locacion" <?php if($carga==2 or $carga==3){ ?> selected<?php } ?>>Locaciones</option>
              <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
            </select>
          <input class="button secondary" type="submit" value="buscar">
      </form>
    </div>
    <div class="columns three">
    <input type="text" id="seach_tableElements" class="search_input">
    </div>

    <div class="blue_box box_save ">
      <form action="#" class="custom">
        <div class="row">
          <div class="columns seven">
            <label for="detalles" class="label_check"><input type="radio" name="save" id="detalles"> Guardar reporte con detalle de unidades</label>
            <label for="sin_detalles" class="label_check"><input type="radio" name="save" id="sin_detalles"> Guardar reporte sin detalle de unidades</label>
          </div>
          <div class="columns five">
            <div class="call_to_action align_right">
              <a href="#" class="button secondary icon icon_cancel cancel_"><span></span>cancelar</a>
              <a href="<?=base_url('pdf2/pdf_elementos/').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria?>" target="_blank" id ="pdf_elementos" class="button secondary icon icon_print"><span></span>imprimir</a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="blue_box box_print">
      <form action="#" class="custom">
        <div class="row">
          <div class="columns seven">
            <label for="detalles" class="label_check"><input type="radio" name="save" id="detalles"> Imprimir reporte con detalle de unidades</label>
            <label for="sin_detalles" class="label_check"><input type="radio" name="save" id="sin_detalles"> Imprimir reporte sin detalle de unidades</label>
          </div>
          <div class="columns five">
            <div class="call_to_action align_right">
              <a href="#" class="button secondary icon icon_cancel cancel_"><span></span>cancelar</a>
              <a href="<?=base_url('pdf2/pdf_elementos/').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria?>" target="_blank" class="button secondary icon icon_print"><span></span>imprimir</a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="right">
      <div class="call_to_action align_right">
        <?php if($usuario_permisos=="write") { ?>
        <a href="#" class="button icon icon_plus new_elemento"><span></span>nuevo elemento</a>
        <a href="#" class="button icon icon_plus new_locacion_set"><span></span>nueva locación / set</a>
        <?php } ?>
        <a href="#" class="button icon icon_categories"><span></span>categorias</a>
        <?php if($id_locacion){?>
        <a href="<?=base_url('excel/excel_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_locacion?>" target="_blank" class="button icon icon_save"><span></span>guardar</a>
        <?php }else{ ?>
        <a href="<?=base_url('excel/excel_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria?>" target="_blank" class="button icon icon_save"><span></span>guardar</a>
        <?php } ?>
        <?php if($id_locacion){?>
        <a href="<?=base_url('pdf2/pdf_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_locacion?>" target="_blank" class="button icon icon_print"><span></span>imprimir</a>
        <?php }else{ ?>
        <a href="<?=base_url('pdf2/pdf_elementos').'/'.$produccion['0']->id_produccion.'/'.$carga.'/'.$id_categoria?>" target="_blank" class="button icon icon_print"><span></span>imprimir</a>
        <?php } ?>
        <a href="#" class="button full_screen" alt="Fullscreen"><span class="open">Fullscreen</span><span class="close">Fullscreen</span></a>
      </div>
  </div>
  <!-- <div class="right">
    <div class="escenasNoProducidas">Total:
      <?php
      if($carga==1){
        echo count($elementos);
      } else {
        echo count($locaciones);
      } 
      ?>
    </div>
  </div> -->
  <div class="categorias_elemento" style="display:none; clear:both;">
       <div>
        <label>Categorías</label>
          <div class="row">
            <div class="column six">
              <ul>
                <?php $cont=0; ?>
                <?php foreach ($categorias_elementos as $c) {?>
                  <?php echo form_open('elementos/update_categoria'); ?>
                  <?php if($cont==0){ $style='';}else{$style='style=display:none';} ?>
                  <li class='categoria'><?php // echo $c['tipo'] ?>
                    <input name="categoria_nombre" type="text" class='categoria' id="<?php echo $c['id'] ?> " value="<?php echo $c['tipo'] ?>" readonly>
                    <script type="text/javascript"> categorias['<?php echo $cont ?>']='<?php echo $c["tipo"]?>';</script>
                    
                    <input name="id_categoria" name="id_categroia" type="hidden" class='categoria' id="categoria<?php echo $c['id'] ?> " value="<?php echo $c['id'] ?>">
                    <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
                    <?php $js='onClick="pregunta()"'  ?>
                    <?php if($c['tipo']!='Personaje') {?>
                      <?php if($usuario_permisos=="write"){ ?>
                        <div id="<?php echo $c['id']?>" class="eliminarCAtegoria">Eliminar</div>
                      <?php } ?>
                    <?php } ?>
                    <!-- <textarea id='categoria<?php echo $c['id']; ?>' class="categoriatext" name="descripcion" <?php echo $style; ?>><?php echo $c['descripcion'] ?></textarea> -->
                    <?php if($usuario_permisos=="write"){ ?>
                    <input type="submit" value="Guardar" id='btnGuardar<?php echo $c['id']; ?>' class="btnGuardar button">
                    <?php } ?>
                    <?php echo form_close(); ?>
                  <?php $cont++; ?>
                <?php } ?>
                
              </ul>
              <label class="error"><?php echo form_error('categoria_nombre'); ?></label>
            </div>
            <?php if($usuario_permisos=="write"){ ?>
            <div class="column six">
              <?php echo form_open('elementos/crear_categoria',' class="custom" onSubmit="return validar_categoria()"') ?>
                <label>Nombre Categoria</label>
                <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
                <input type="text" value="" id="name_categoria" name="categoria_nombre_new" class="required">
                <label class="error"><?php echo form_error('categoria_nombre_new'); ?></label>
                <!-- <textarea name="descripcion_new" class="categoriatext2"  class="required"></textarea> -->
                <label class="error"><?php echo form_error('descripcion_new'); ?></label>
                <div class="align_right">
                  <input class="button" type="submit" value="Crear Categoria">  
                </div>
              <?php echo form_close(); ?>
            </div>
            <?php } ?>
          </div>  
        </div>   
  </div>  

   <div class="crear_elemento" style="display:none; clear:both;">
    <?php echo form_open('elementos/crear_elemento','id="create_element"') ?>  
      <?php if($id_categoria){ ?>
        <input type="hidden" value="<?=$id_categoria?>" name="categoria_actual">
      <?php } ?>
        <div class="clr"></div>
      <div class="row">
        <div class="column four">
          <label>Tipo elemento</label>
          <select name="categoria" class="id_elemento">
          <?php foreach ($categorias_elementos as $c) { ?>
                <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['id']==$id_categoria){ ?> selected <?php } ?>><?php echo $c['tipo'] ?></option>
          <?php } ?>
          </select>
        </div>
        <div class="column four">
          <label>Nombre elemento</label>
          <input type="text" id="element_name" name="nombre">
          <label class="error"><?php echo form_error('nombre'); ?></label>
        </div>
        <div class="column four">
          <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
          <div class="rol_personaje"  >
            <label for="type">rol:</label>
            <select name="rol" id="rol_personaje">
              <?php foreach ($rol_actores_elementos as $r) { ?>
                <option value="<?php echo $r['id'] ?>"><?php echo $r['rol'] ?></option>
              <?php } ?>
            </select> 
          </div>
        </div>
        <div class="column twelve">
          <label>Descripción:</label>
          <textarea name="descripcion"></textarea>
          <input type="button" id="create_element_button" value="Crear Elemento" class="button">
        </div>
      </div>
    <?php echo form_close() ?>
   </div>

  <div class="crear_locacion_set" style="display:none; clear:both;height:200px;">
    <input type="hidden" id="idproduccion" value="<?=$produccion[0]->id_produccion?>">
    <div class="left_dashed" style="width:34%;">
      <div class="row">
        <div class="column nine">
          <label for="location">Locación</label>
          <select name="location" id="location" class="required" validate="required:true">
            <option value="">Seleccione una locacion</option>
           <?php if($locaciones){?> 
              <?php foreach ($locaciones as $l ) { ?> 
               <option value="<?php echo $l['id'] ?>"><?php echo $l['nombre'] ?></option>
              <?php } ?>
            <?php } else{ ?>
              <option value="">No hay locacion para esta produccion</option>
            <?php } ?>  
          </select>
          <?php echo form_error('location'); ?>
          <input type="text" id="new_location" name="new_location"placeholder="" style="display:none;">
        </div>
        <div class="column three">
          <br>
          <a href="#" class="button secondary" id="new_item_location">Nuevo</a>
        </div>
      </div>
    </div>
    <div class="left_dashed last setHiden" style="width:35%;">
      <div class="row">
        <div class="column nine">
          <label for="set">set</label>
          <select name="set" id="set" class="set required" validate="required:true">
             <option value="">no hay set para esta locacion</option>
          </select>
          <?php echo form_error('set'); ?>
          <input type="text" id="new_set" name="new_set" placeholder="" style="display:none;">
        </div>
        <div class="column three">
          <br>
          <a href="#" class="button secondary" id="new_item_set">Nuevo</a>
          <a href="#" class="button" id="add_item_set" style="display:none;">Agregar</a>
          <a href="#" class="button" id="cancel_item_set" style="display:none;">Cancelar</a>
          <a href="#" class="button" id="add_item_location" style="display:none;">Agregar</a>
          <a href="#" class="button" id="cancel_item_location" style="display:none;">Cancelar</a>
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

            </div><?php ///carlos ?>
            <div id="scroll">
              <div class="normal_table">
                <table id="usersTable" class="tablesorter">
                  <thead>
                    <tr>
                      <th width="20%">Nombre</th>
                      <th width="20%" <?=$hide?>>Rol</th>
                      <th width="20%">descripción</th>
                      <th width="10%">usos</th>
                      <th width="10%">% de uso</th>
                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                    </tr>
                  </thead>
                  <tbody class="ui-selectable">
                   <?php $count=0;   if($elementos){ ?> 
                    <?php foreach ($elementos as $e) { ?>
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                    <?php /* ?>
                    <tr class="<?php echo $classRow.' element_'.$e['id_elemento'];?>">
                    <?php */ ?>
                    <?php $esc_producidas=$this->model_elementos->escenas_producidas($e['id_elemento']); ?>
                    <?php $esc_porproducir=$this->model_elementos->escenas_proproducir($e['id_elemento']); ?>
                    <?php 

                    $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
                    if($esc_producidas[0]->total==0){
                    $por=0;
                    }else{
                    //$por=($esc_producidas[0]->total*100)/$total_escenas[0]->total;
                    $por=$esc_producidas[0]->total;
                    $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                    //$por2=($esc_porproducir[0]->total*100)/$total_escenas[0]->total;
                    $por2=$esc_porproducir[0]->total;
                    $por2=round($por2);  
                    }?>
                    <?php $pot_total=abs($por-$por2); ?>
                    <tr class="actionAsing ui-selectee <?php echo 'element_'.$e['id_elemento'];?> diagrama" data-tr="<?=$e['id_elemento']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$pot_total?>" data-noasignadas="<?= abs($por2-$total_escenas[0]->total);  ?>" data-idcahrt="<?= $e['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $e['id_elemento'] ?>" data-tipo="1">
                      <td class="element_name"><?php echo $e['nombre']?></td>
                      <?php if($e['rol']){
                       //$rol=$this->model_elementos->rol_actores_elementos_id($e['rol']);
                       //$rol=$rol[0]->rol; 
                      }else{ $rol='';} ?>
                      <td <?=$hide?> ><?php echo $e['rol']; ?></td>
                      <td><?php echo Elementos::corta_palabra($e['des_elem'],32);
                      if(strlen($e['des_elem'])>32){
                        echo '...';
                      }
                      ?></td>
                      <?php $uso=$this->model_elementos->escenas_has_elementos($e['id_elemento']); ?>
                      <td style="text-align:center"><?php echo $uso; ?></td>
                      <?php $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
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
                      <?php if($e['rol']){
                       //$rol=$this->model_elementos->rol_actores_elementos_id($e['rol']);
                       //$rol=$rol[0]->rol; 
                      }else{ $rol='';} ?>
                      <td <?=$hide?> style="text-align:center;display:none;"><?php echo $e['rol']; ?></td>
                      <td style="text-align:center;display:none;"><?php echo Elementos::corta_palabra($e['des_elem'],32);
                      if(strlen($e['des_elem'])>32){
                        echo '...';
                      }
                      ?></td>
                      <?php $uso=$this->model_elementos->escenas_has_elementos($e['id_elemento']); ?>
                      <td style="text-align:center;display:none;"><?php echo $uso; ?></td>
                      <?php $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
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
                      <td colspan="7">
                          <!--grafico torta-->
                        <div class="row">
                            <div class="columns six">
                              <div class="torta_porcentaje">
                           
                            <div class="row">
                                <div class="column six">
                                  <div id="chart_div<?php echo $e['nombre']?>"></div>
                                  <div class="convencionesPieChart">
                                    <ul>
                                      <li> <span class="yellow"></span> Escenas producidas</li>
                                      <li> <span class="pink"></span> Escenas no producidas</li>
                                      <li> <span class="gray"></span> Escenas no asignadas</li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column six">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4>Escenas <br> producidas </h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4>Escenas no<br> producidas </h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> escenas creadas</div>
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
                              <label>Total de Libretos: <?php echo count($total_capitulos); ?></label>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->

                            <!--editar elementos -->
                            <?php if($usuario_permisos=="write"){ ?>
                              <div class="columns twelve">
                                  <label class="editar_elemento button" id="<?php echo $e['id_elemento'] ?>">Editar elemento</label>
                                  <?php if($uso==0){ ?>
                                  <input type="button" value="Eliminar" id="<?php echo $e['id_elemento'] ?>" class="button secondary eliminar_elemento">   
                                  <?php  } ?>
                              </div>

                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $e['id_elemento']?>">
                              <br>
                              <?php echo form_open('elementos/editar_elemento') ?> 
                                <div class="row">
                                    <div class="column six">
                                      <label>Tipo elemento</label>
                                      <select name="categoria" id="id_elemento2">
                                      <option id="<?php echo $e['tipo']?>" class="elemento_<?php echo $e['id_tipo_elemento']?>" value="<?php echo $e['id_tipo_elemento']?>"><?php echo $e['tipo']; ?></option>
                                      <?php foreach ($categorias_elementos as $c) { ?>
                                          <option id="<?php echo $c['tipo']?>" class="elemento2_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>"><?php echo $c['tipo'] ?></option>
                                      <?php } ?>
                                      </select> 

                                      <?php if($e['tipo']=='Personaje'){ ?>  
                                      <label>Actor</label>
                                      <div class="column three">
                                        <label>nombre</label>
                                        <input type="text" name="actor_nombre" value="<?=$e['actor_nombre']?>">
                                      </div>
                                      <div class="column three">
                                        <label>apellido</label>
                                        <input type="text" name="actor_apellido" value="<?=$e['actor_apellido']?>">
                                      </div>
                                      <div class="column three">
                                        <label>Documento</label>
                                        <input type="text" name="documento" value="<?=$e['documento_actor']?>">
                                      </div>
                                      <div class="column three">
                                        <label>Tipo Documento</label>
                                        <select name="tipo_documento">
                                            <option>Seleccione una opción</option>
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
                                      <label>Nombre elemento</label>
                                      <input type="hidden" name="id_elemento" value="<?php echo $e['id_elemento']?>" >
                                      <input type="text" name="nombre" value="<?php echo $e['nombre']?>">
                                      <label class="error"><?php echo form_error('nombre'); ?></label>   
                                      <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
                                      <?php if($e['tipo']=='Personaje'){ $c=''; }else{$c='display:none';}?> 
                                      <div class="rol_personaje2" style="<?php echo $c; ?>">
                                      <label for="type">rol:</label>
                                      <select name="rol" class="rol_personaje">
                                        <?php foreach ($rol_actores_elementos as $r) { ?>
                                          <option value="<?php echo $r['id'] ?>" <?php if($e['rol']==$r['rol']){ echo "selected"; } ?> ><?php echo $r['rol'] ?></option>
                                        <?php } ?>
                                      </select> 
                                      </div>
                                    </div>
                                </div>

                                <?php if($e['tipo']=='Personaje'){ ?>  
                                <div class="row">
                                  <div class="column twelve">
                                    <div class="column two">
                                        <label>Monto</label>
                                        <input type="text" class="monto_personaje" name="monto" value="<?=$e['monto']?>">
                                    </div>
                                    <div class="column two">
                                        <label>Tipo moneda</label>
                                        <select name="tipo_moneda">
                                           <option>Seleccione una opción</option>
                                          <?php foreach ($tipos_moneda as $tipo_moneda) { ?>
                                            <option <?php if($tipo_moneda->id==$e['id_tipo_moneda']){
                                              echo "selected";
                                            }?>  value="<?=$tipo_moneda->id?>"><?=$tipo_moneda->descripcion?></option>
                                          <?php } ?>
                                        </select>                                   
                                    </div>
                                    <div class="column two">
                                      <label>Tipo contrato</label> 
                                      <select name="cotrato">
                                        <option value="">Seleccione una opción</option>
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
                                        <label>fecha inicio</label>
                                        <?php
                                        $fecha_inicio = ""; 
                                        $fecha_finalizacion = ""; 
                                        if($e['fecha_finalizacion'] AND $e['fecha_finalizacion']!= '0000-00-00'){
                                          $fecha_finalizacion = $e['fecha_finalizacion'];
                                        }
                                        if($e['fecha_inicio'] AND $e['fecha_inicio']!= '0000-00-00'){
                                          $fecha_inicio = $e['fecha_inicio'];
                                        }else if($e['fecha_inicio_2']){
                                          $fecha_inicio = $e['fecha_inicio_2'];
                                        }
                                        ?>
                                        <input type="text" class="datepicker" name="fecha_inicio" value="<?=$fecha_inicio?>">
                                    </div>
                                    <div class="column three">
                                        <label>fecha finalización</label>
                                        <input type="text" class="datepicker" name="fecha_finalizacion" value="<?=$fecha_finalizacion?>">
                                    </div>
                                  </div>
                                </div>
                                <?php } ?>

                                  <div class="row">
                                    <div class="column twelve">
                                      <label>Descripción:</label>
                                      <textarea name="descripcion"><?php echo $e['des_elem'] ?></textarea>    
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="column twelve">
                                      <input type="submit" value="Actualizar Elemento" class="button">
                                      <input type="button" value="Cancelar" id="<?php echo $e['id_elemento'] ?>" class="button secondary cancel_edit_element">
                                    </div>
                                  </div>
                              <?php echo form_close() ?>
                            </div>
                            <?php  } ?>

                          <!--fin editar -->
                        </div>    
                      </td>
                    </tr>
                    <!---------------->  

                    <?php } ?>
                  <?php }else{?>
                    <tr>
                      <td colspan="7">No hay elementos para esta categoria</td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
              </div>

            <!-- fin tabla -->  
   <?php }elseif($carga==2){ ?>
                <!-- tabla locaciones-->  
      <div id="search_locations" style="float: inherit;">
        <?php echo form_open('elementos/buscar_sets'); ?>
              <select name="locacion" >
                <?php foreach ($locacion as $l) { ?>
                      <option value="<?php echo $l['id'] ?>" > <?php echo $l['nombre'] ?></option>
                <?php } ?>
              </select>  
              <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
              <input class="button secondary" type="submit" value="buscar">
        </form>
      </div>
            </div> <?php ///carlos ?>
            <div id="scroll">
              <div class="normal_table">
                <table id="usersTable" class="tablesorter">
                  <thead>
                    <tr>
                      <th width="20%">Nombre</th>
                      <th width="10%">usos</th>
                      <th width="10%">% de uso</th>
                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                    </tr>
                  </thead>
                  <tbody class="ui-selectable">
                   <?php $count=0;   if($locacion){ ?> 
                    <?php foreach ($locacion as $l) { ?>
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                    <?php /* ?>
                    <tr class="<?php echo $classRow.' element_'.$e['id_elemento'];?>">
                    <?php */ ?>
                  <?php $esc_producidas=$this->model_elementos->escenas_producidas_idcapitulo($l['id']); ?>
                  <?php $esc_porproducir=$this->model_elementos->escenas_porproducidas_idcapitulo($l['id']); ?>
                    <?php 

                    $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
                    if($esc_producidas[0]->total==0){
                    $por=0;
                    }else{
                    //$por=($esc_producidas[0]->total*100)/$total_escenas[0]->total;
                    $por=$esc_producidas[0]->total;
                    $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                    //$por2=($esc_porproducir[0]->total*100)/$total_escenas[0]->total;
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
                                      <li> <span class="yellow"></span> Escenas producidas</li>
                                      <li> <span class="pink"></span> Escenas no producidas</li>
                                      <li> <span class="gray"></span> Escenas no asignadas</li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column five">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4>Escenas <br> producidas </h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4>Escenas no<br> producidas </h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> escenas creadas</div>
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
                              <label>Total de Libretos: <?php echo count($total_capitulos); ?></label>
                              
                              <script type="text/javascript">

                              </script>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->
                               <!--editar elementos -->
                            <?php if($usuario_permisos=="write") { ?>
                            <div class="columns twelve">
                                <label class="editar_elemento button" id="<?php echo $l['id'] ?>">Editar elemento</label>
                                <?php if($uso==0){ ?>
                                  <input type="button" value="Eliminar" id="<?php echo $l['id'] ?>" class="button secondary eliminar_locacion">   
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $l['id']?>">
                              <br>
                                  <div class="row">
                                    <div class="column six">
                                      <label>Nombre elemento</label>
                                      <label class="error"><?php echo form_error('nombre'); ?></label>
                                      <input type="hidden" name="id_locacion" class="id_locacion" value="<?php echo $l['id']?>" >
                                      <input type="text" name="nombre" class="nombre" value="<?php echo $l['nombre']?>">
                                      <input type="hidden" value="<?php echo $id_produccion ?>" class="id_produccion" name="id_produccion"> 
                                      <input type="submit" value="Actualizar Elemento" class="button actualizar_locacion">
                                      <input type="button" value="Cancelar" id="<?php echo $l['id'] ?>" class="button secondary cancel_edit_element">
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
                      <td colspan="7">No hay elementos para esta categoria</td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
              </div>

            <!-- fin tabla locaciones -->  
   <?php }else{ ?>
        <!-- tabla sets -->
        <div id="search_locations">
           <?php echo form_open('elementos/buscar_sets'); ?>
            <select name="locacion" >
              <?php foreach ($locacion as $l) { ?>
                    <option value="<?php echo $l['id'] ?>" <?php if($id_locacion==$l['id']){ ?> selected <?php } ?>> <?php echo $l['nombre'] ?></option>
              <?php } ?>
            </select>  
            <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
            <input class="button secondary" type="submit" value="buscar">
      </form>
            </div>
            </div>  <?php ///carlos ?>
            <div id="scroll">
              <div class="normal_table">
                <table id="usersTable" class="tablesorter">
                  <thead>
                    <tr>
                      <th width="20%">Nombre</th>
                      <?php if(isset($l['descripción'])){ ?>
                        <th width="20%" <?=$hide?>>Rol</th>
                        <th width="20%">descripción</th>
                      <?php } ?>
                      <th width="10%">usos</th>
                      <th width="10%">% de uso</th>
                      <th width="10%">Esc Prod</th>
                      <th width="10%">Esc por Prod</th>
                    </tr>
                  </thead>
                  <tbody class="ui-selectable">
                   <?php $count=0;   if($sets){ ?> 
                    <?php foreach ($sets as $l) { ?>
                    <?php if ($count%2 == 0) {
                      $classRow = "white";
                    }else{
                      $classRow = "gray_light";
                    }$count++; ?>
                    <?php /* ?>
                    <tr class="<?php echo $classRow.' element_'.$e['id_elemento'];?>">
                    <?php */ ?>
                  <?php $esc_producidas=$this->model_elementos->escenas_producidas_id_sets($l['id']); ?>
                  <?php $esc_porproducir=$this->model_elementos->escenas_porproducidas_idsets($l['id']); ?>
                    <?php 

                    $total_escenas=$this->model_elementos->total_escenas_idProduccion($id_produccion);
                    if($esc_producidas[0]->total==0){
                    $por=0;
                    }else{
                    //$por=($esc_producidas[0]->total*100)/$total_escenas[0]->total;
                    $por=$esc_producidas[0]->total;
                    $por=round($por); 
                    }   ?>
                    <?php 
                    if($esc_porproducir[0]->total==0){
                    $por2=0;
                    }else{
                    //$por2=($esc_porproducir[0]->total*100)/$total_escenas[0]->total;
                    $por2=$esc_porproducir[0]->total;
                    $por2=round($por2);  
                    }?>
                    <?php $pot_total=$por2; ?>
                    <tr class="actionAsing ui-selectee <?php echo 'element_'.$l['id'];?> diagrama" data-tr="<?=$l['id']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$pot_total?>" data-noasignadas="<?= abs($total_escenas[0]->total-$por-$por2);  ?>" data-idcahrt="<?= $l['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $l['id'] ?>" data-tipo="3">
                      <td><?php echo $l['nombre']?></td>
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
                                      <li> <span class="yellow"></span> Escenas producidas</li>
                                      <li> <span class="pink"></span> Escenas no producidas</li>
                                      <li> <span class="gray"></span> Escenas no asignadas</li>
                                    </ul>
                                  </div>
                                </div>
                                <div class="column five">
                                  <div class="row">
                                    <div class="column six">
                                      <div class="yellowBox">
                                        <h4>Escenas <br> producidas </h4>
                                        <div class="number"><?php echo $esc_producidas[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="column six">
                                      <div class="magentaBox">
                                        <h4>Escenas no<br> producidas </h4>
                                        <div class="number"><?php echo $esc_porproducir[0]->total?></div>
                                      </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="column twelve">
                                      <div class="grayBox"><?php echo $total_escenas[0]->total?> escenas creadas</div>
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
                              <label>Total de Libretos: <?php echo count($total_capitulos); ?></label>
                              
                              <script type="text/javascript">

                              </script>
                            </div>
                          </div>
                            <!-- fin graficas tablas -->
                            <?php if($usuario_permisos=="write") { ?>
                           <!--editar elementos -->
                            <div class="columns twelve">
                                <label class="editar_elemento button" id="<?php echo $l['id'] ?>">Editar elemento</label>
                                <?php if($uso==0){ ?>
                                  <input type="button" value="Eliminar" id="<?php echo $l['id'] ?>" class="button secondary eliminar_sets">   
                                <?php } ?>
                            </div>
                            <div class="clr"></div>
                            <div style="display:none" class="form_elemento_<?php echo $l['id']?>">
                              <br>
                                  <div class="row">
                                    <div class="column six">
                                      <label>Nombre elemento</label>
                                      <label class="error"><?php echo form_error('nombre'); ?></label>   
                                      <input type="hidden" name="id_elemento" class="id_set"  value="<?php echo $l['id']?>" >
                                      <input type="text" name="nombre" class="nombre" value="<?php echo $l['nombre']?>">
                                      <input type="hidden" value="<?php echo $id_locacion ?>" class="id_locacion" name="id_locacion">
                                      <input type="submit" value="Actualizar Elemento" class="button actualizar_set">
                                      <input type="button" value="Cancelar" id="<?php echo $l['id'] ?>" class="button secondary cancel_edit_element">
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
                      <td colspan="7">No hay elementos para esta categoria</td>
                    </tr>
                  <?php } ?> 
                  </tbody>
                </table>
              </div>

            <!-- fin tabla sets --> 
   <?php } ?>
  </div>
</div>
