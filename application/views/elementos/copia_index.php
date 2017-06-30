<div id="breadcrumbs">
  <a href="<?php echo base_url('produccion/producciones'); ?>">Home</a> / <a href="<?php echo base_url('/plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / Elementos
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<div id="inner_content">
  <div class="top_page top_page_escenas row">
    <div class="left">
      <?php echo form_open('elementos/buscar_categoria'); ?>
        <div class="columns nine">
            <select name="id_categoria" id="id_categoria">
              <?php foreach ($categorias_elementos as $c) { ?>
                    <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>" <?php if($c['id']==$id_categoria){ ?> selected <?php } ?> ?><?php echo $c['tipo'] ?></option>
              <?php } ?>
                    <option value="locacion">Locaciones</option>
                    <option value="set">Sets</option>
              <input name="id_produccion" class="id_produccion" type="hidden" value="<?php echo $id_produccion; ?>">
            </select>
        </div>
        <div class="column three">
          <input class="button secondary" type="submit" value="buscar">
        </div>
      </form>
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
              <a href="#" class="button secondary icon icon_print"><span></span>imprimir</a>
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
              <a href="#" class="button secondary icon icon_print"><span></span>imprimir</a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="right">
      <div class="call_to_action align_right">
        <a href="#" class="button icon icon_plus new_elemento"><span></span>nuevo elemento</a>
        <a href="#" class="button icon icon_categories"><span></span>categorias</a>
        <a href="#" class="button icon icon_save"><span></span>guardar</a>
        <a href="#" class="button icon icon_print"><span></span>imprimir</a>
        <a href="#" class="button full_screen" alt="Fullscreen"><span class="open">Fullscreen</span><span class="close">Fullscreen</span></a>
      </div>
    </div>
  </div>
  <div class="categorias_elemento" style="display:none">
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
                      <div id="<?php echo $c['id']?>" class="eliminarCAtegoria">Eliminar</div>
                    <?php } ?>
                    <!-- <textarea id='categoria<?php echo $c['id']; ?>' class="categoriatext" name="descripcion" <?php echo $style; ?>><?php echo $c['descripcion'] ?></textarea> -->
                    <input type="submit" value="Guardar" id='btnGuardar<?php echo $c['id']; ?>' class="btnGuardar button">
                    <?php echo form_close(); ?>
                  <?php $cont++; ?>
                <?php } ?>
                
              </ul>
              <label class="error"><?php echo form_error('categoria_nombre'); ?></label>
            </div>
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
          </div>
          
        </div>   
        
   </div>   
   <div class="crear_elemento" style="display:none">
    <?php echo form_open('elementos/crear_elemento') ?>  
      <div class="row">
        <div class="column four">
          <label>Tipo elemento</label>
          <select name="categoria" class="id_elemento">
          <?php foreach ($categorias_elementos as $c) { ?>
                <option id="<?php echo $c['tipo']?>" class="elemento_<?php echo $c['id']?>" value="<?php echo $c['id'] ?>"><?php echo $c['tipo'] ?></option>
          <?php } ?>
          </select>
        </div>
        <div class="column four">
          <label>Nombre elemento</label>
          <input type="text" name="nombre">
          <label class="error"><?php echo form_error('nombre'); ?></label>
        </div>
        <div class="column four">
          <input type="hidden" value="<?php echo $id_produccion ?>" name="id_produccion">
          <div class="rol_personaje" >
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
          <input type="submit" value="Crear Elemento" class="button">
        </div>
      </div>
    <?php echo form_close() ?>
   </div>  
    <?php if($categorias_elementos[0]['id']!=$id_categoria) {
      $hide = 'style="display:none"';
    }else{
      $hide="";
    } 
    ?>
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
          <tr class="actionAsing ui-selectee <?php echo 'element_'.$e['id_elemento'];?> diagrama" data-tr="<?=$e['id_elemento']?>" data-producidas="<?=$por ?>" data-noproducidas="<?=$pot_total?>" data-noasignadas="<?= abs($por2-$total_escenas[0]->total);  ?>" data-idcahrt="<?= $e['nombre'] ?>" data-idproduccion="<?= $id_produccion?>" data-idelemento="<?= $e['id_elemento'] ?>">
            <td><?php echo $e['nombre']?></td>
            <?php if($e['rol']){
             $rol=$this->model_elementos->rol_actores_elementos_id($e['rol']);
             $rol=$rol[0]->rol; 
            }else{ $rol='';} ?>
            <td <?=$hide?> ><?php echo $rol; ?></td>
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
          <tr class="info_element element_<?=$e['id_elemento']?>" data-tr="<?=$e['id_elemento']?>" >
            <td colspan="7">
                <!--grafico torta-->
              <div class="row">
                  <div class="columns six">
                    <div class="torta_porcentaje">
                 
                 
                  <div class="row">
                      <div class="column seven">
                        <div id="chart_div<?php echo $e['nombre']?>"></div>
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
                  <div class="barras_estadisticas">
                    <?php  $total_capitulos=$this->model_elementos->total_capitulos_idProduccion($id_produccion); ?>
                    <script type="text/javascript">
                    var escenasAsignadas<?php echo $e['id_elemento']?>=new Array();
                    var escenasCapitulo<?php echo $e['id_elemento']?>=new Array();
                    </script>
                  <script type="text/javascript"></script>
                  <div id="capitulos_chart<?php echo $e['id_elemento']?>" style="min-width: 560px; height: 250px;"></div>
                    <label>Total de Libretos: <?php echo count($total_capitulos); ?></label>
                    
                    <script type="text/javascript">

                    </script>
                  </div>
                </div>
                  <!-- fin graficas tablas -->

                  <!--editar elementos -->
                  <div class="columns twelve">
                      <label class="editar_elemento button" id="<?php echo $e['id_elemento'] ?>">Editar elemento</label>
                      <input type="button" value="Eliminar" id="<?php echo $e['id_elemento'] ?>" class="button secondary eliminar_elemento">   
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
                                <option value="<?php echo $r['id'] ?>"><?php echo $r['rol'] ?></option>
                              <?php } ?>
                            </select> 
                            </div>
                          </div>
                        </div>
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
      <!-- SECCION PARA LA EDICION DE SET Y LOCACIONES -->
      <div class="ver_locaciones">
        <h3 class="locaciones">Locaciones - Sets</h3>
          <?php if($locacion) { ?>
            <?php foreach ($locacion as $locacion) { ?>
            <div class="column twelve itemCruce">
              <div class="top">
                <div><strong>Locación: </strong> <?php echo $locacion['nombre']; ?></div>
              </div>
              <div id="scroll">
                <?php $sets = $this->model_elementos->locaciones_sets($locacion['id']); ?>
                <?php if($sets AND $sets[0]['usos_locacion']){?>
                <div class="table_general">
                  <table class="secondary">
                    <tr class="gray">
                      <td>Set</td>
                      <td>Usos</td>
                      <td>Eliminar</td>
                    </tr>
                    <?php foreach ($sets as $set) {?>
                      <tr>
                        <td><?=$set['set_nombre'];?></td>
                        <td><?=$set['usos_set'];?></td>
                        <td><?php if($set['usos_set']==0){ ?>
                          <a href="<?=base_url('elementos/eliminar_set/'.$id_produccion.'/'.$set['id_set'])?>">Eliminar</a>
                        <?php }else{
                          echo '-';
                        } ?>
                        </td>
                      </tr>
                    <?php } ?>
                    <?php }else{?>
                     <a href="<?=base_url('elementos/eliminar_locacion/'.$id_produccion.'/'.$sets[0]['id_locacion'])?>">Eliminar</a>
                    <?php } ?>
                    </table>
                </div>
              </div>
            </div>
            <?php } ?>    
          <?php } ?>
      </div>
  <!-- SECCION PARA LA EDICION DE SET Y LOCACIONES -->
  </div>
</div>



