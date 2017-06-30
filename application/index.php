<style> .tooltip.tip-centered-top{ background-color: #adadad!important;}.tooltip.tip-centered-top>.nub{border-top-color:#adadad!important;}</style>
<div id="breadcrumbs">
  <a href="<?php echo base_url('produccion/producciones'); ?>">Home</a> / <a href="<?php echo base_url('/plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / Plan General
</div>
<div id="inner_content">
    <div class="top_page top_page_plangeneral row">
        <div class="left">
          <div class="columns six">
            <div class="escenasNoProducidas">
                Escenas no producidas: <?=$escenas_noproducidas?>
            </div>
            </br>
            <div class="escenasNoProducidas">
                Tiempo estimado disponible: <?=$tiempo_diponible?>
            </div>
          </div>
          <div class="columns six">
            <form action="#">
                <input type="text" class="search_input">
            </form>
          </div>
        </div>
        <div class="right">
          <div class="call_to_action align_right">
            <a href="#" class="button icon icon_edit"><span></span>editar</a>
            <a href="#" class="button icon icon_filter"><span></span>filtrar</a>
            <a href="#" class="button icon icon_save"><span></span>guardar</a>
            <a href="#" class="button icon icon_print"><span></span>imprimir</a>
            <a href="#" class="button help_button help_planProd" alt="Fullscreen"><span class="open">Ayuda</span></a>
            <a href="#" class="button full_screen" alt="Fullscreen"><span class="open">Fullscreen</span><span class="close">Fullscreen</span></a>
          </div>
        </div>
    </div>
    <!--INICIO SECCION DE FILTRO-->
    <div class="content_filter row" id="content_filter" style="display:none;">
        <?php echo form_open('plan_general/filtro',' class="", id="filtro"');?>
            <input type="hidden" name="idproduccion" id="idproduccion" value="<?=$produccion[0]->id_produccion ?>"> 
            <div class="row">
                <table class="tabla_info">
                    <tr>
                        <td>
                            <!--RANGO DE ESCENAS-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="">Rango de escenas</label>
                                </div>
                                <div class="column six">
                                    <label for="">Desde</label>
                                    <input type="text" name="limite1_esc" class="required" placeholder="00">    
                                </div>
                                <div class="column six">
                                    <label for="">hasta</label>
                                    <input type="text" name="limite2_esc" class="required" placeholder="00">    
                                </div>
                            </div>
                            <!--FIN RANGO DE ESCENAS-->
                        </td>
                        <td>
                            <!--RANGO DE LIBRETOS-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="">Rango de libretos</label>
                                </div>
                                <div class="column six">
                                    <label for="">Desde</label>
                                    <input type="text" name="limite1_cap" class="required" placeholder="00"> 
                                </div>
                                <div class="column six">
                                    <label for="">Hasta</label>
                                    <input type="text" name="limite2_cap" class="required" placeholder="00">  
                                </div>
                            </div>
                            <!--FIN RANGO DE LIBRETOS-->
                        </td>
                        <td>
                            <!--RANGO DE FECHAS-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="">Rango de fechas</label>
                                </div>
                                <div class="column six">
                                    <label for="start_recording">inicio:</label>
                                    <input type="text" placeholder="dd/mm/aaaa" id="start_recording" name="limite1_fec" class="required">
                                    <label class="error"><?php echo form_error('inicio_grabacion'); ?></label>
                                </div>
                                <div class="column six">
                                    <label for="end_recording">fin:</label>
                                    <input type="text" placeholder="dd/mm/aaaa" id="end_recording" name="limite2_fec" class="required">
                                    <label class="error"><?php echo form_error('fin_grabacion'); ?></label>
                                </div>
                            </div>
                            <!--FIN RANGO DE FECHAS-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--SELECT LOCACION-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="locacion">locación</label>
                                    <select name="locacion" id="location" class="required">
                                        <option value="" selected>Seleccione una locación</option>
                                        <?php foreach ($locaciones as $locacion) { ?>
                                           <option value="<?=$locacion['id']?>"><?=$locacion['nombre']?></option>
                                        <?php } ?> 
                                    </select>
                                    <br><br>
                                </div>
                            </div>
                            <!--FIN SELECT LOCACION-->
                        </td>
                        <td>
                            <!--SELECT SET-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="locacion">set</label>
                                    <select name="set" id="set" class="set" style="display:block!important;">
                                      <?php foreach ($set as $s) { ?>
                                        <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'] ?></option>
                                      <?php } ?>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <!--FIN SELECT SET-->
                        </td>
                        <td>
                            <!--DOS CAMPOS PARA CON Y SIN-->
                            <input type="hidden" name="con" id="con_hidden" class="required">
                            <input type="hidden" name="sin" id="sin_hidden" class="required">
                            <!--FIN DOS CAMPOS PARA CON Y SIN-->

                            <!--UN CAMPO PARA UNIDAD-->
                            <div class="row">
                                <div class="columns twelve">
                                    <label>Unidad</label>
                                    <select name="unidad" id="unidad" class="required">
                                        <option value="">Seleccione una unidad</option>
                                    <?php $j=1;foreach ($unidades as $unidad) { ?>
                                       <option value="<?=$unidad['id']?>"><?=$j?></option>
                                    <?php ++$j;} ?> 
                                    </select>
                                </div>
                            </div>
                            <!--FIN UN CAMPO PARA UNIDAD-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--CAMPOS INTERIOR EXTERIOR-->
                            <?php foreach ($interior_exterior as $the_interior_exterior) { ?>
                            <div class="columns" style="float:left;">
                                <label for="int_ext"><?=$the_interior_exterior['descripcion']?>:</label>
                                <input type="radio" name="int_ext" class="required unselected" value="<?=$the_interior_exterior['id']?>">
                            </div>
                            <?php } ?>
                            <!--FIN CAMPOS INTERIOR EXTERIOR-->
                        </td>
                        <td>
                            <!--CAMPOS LOC/TU/EST-->
                            <?php foreach ($tipo_locacion as $the_tipo_locacion) { if($the_tipo_locacion['id']!=3){?>
                            <div class="columns" style="float:left;">
                                <label for="tipo_locacion"><?=$the_tipo_locacion['tipo']?>:</label>
                                <input type="radio" name="tipo_locacion" class="required unselected" value="<?=$the_tipo_locacion['id']?>">
                            </div>
                            <?php } } ?>
                            <!--FIN CAMPOS LOC/TU/EST-->
                        </td>
                        <td>
                           <!--CAMPOS DIA NOCHE-->
                            <?php foreach ($dia_noche as $the_dia_noche) { ?>
                            <div class="columns" style="float:left;">
                                <label for="dia_noche"><?=$the_dia_noche['descripcion']?>:</label>
                                <input type="radio" name="dia_noche" class="required unselected" value="<?=$the_dia_noche['id']?>">
                            </div>
                            <?php } ?>
                            <!--FIN CAMPOS DIA NOCHE--> 
                        </td>
                    </tr>
                </table>
                <div class="column twelve">
                    <div class="row">
                        <div class="columns twelve">
                          <label for="">Categoría</label>
                          <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns six">
                             <!--SELECT DE CATEGORIAS-->   
                            <select name="" idescena="" id="elemento_id"  idproduccion="<?=$produccion['0']->id_produccion?>">
                              <option value="0">Ver Todos</option>
                              <?php foreach ($categoria_elemento as $c) { ?>
                               <option value="<?php echo $c['id'] ?>" idproduccion="<?=$produccion['0']->id_produccion?>"><?php echo $c['tipo'] ?></option>
                              <?php } ?>
                            </select>
                        </div>
                        <div class="columns six"><input type="text" id="buscar_elemento" idproduccion="<?=$produccion['0']->id_produccion?>" class="search_input"></div>
                    </div>
                    <div class="row">
                        <!--TABLA NO 1 DE ELEMENTOS-->
                        <div class="columns six">
                          <div id="sortable_content">
                            <div class="top normal_table">
                              <table style="margin:0;">
                                <thead>
                                  <tr>
                                    <td width="30%">Categorías</td>
                                    <td width="50%">nombre de elemento</td>
                                    <td width="20%">Acciones</td>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                            <div class="scroller">
                             <table>
                                  <tbody id="tabla1" class="connectedSortable">
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <!--FIN TABLA NO 1 DE ELEMENTOS-->
                        <!--TABLA NO 2 DE ELEMENTOS-->
                        <div class="columns six">
                          <div id="sortable_content">
                            <div class="top normal_table">
                              <table style="margin:0;">
                                <thead>
                                  <tr>
                                    <td width="30%">Categorías</td>
                                    <td width="50%">nombre de elemento</td>
                                    <td width="20%">Acciones</td>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                              <div class="scroller">
                                <table>
                                  <tbody id="tabla2" class="connectedSortable">
                                     <tr class="ui-drag-disabled">
                                        <td class="sort_right"><span>No hay elementos</td>
                                    </tr> 
                                  </tbody>
                              </table>
                              </div>
                            </div>
                        </div>
                        <!--FIN TABLA NO 2 DE ELEMENTOS-->
                    </div>
                    <br><br>
                    

                </div>
            </div>
            <br><br>
            <!--SECCION BOTONES-->
            <div class="row">
                <div class="twelve columns">
                    <div class="call_to_action align_left">
                        <a href="#" class="button secondary icon icon_cancel" id="cancel_filter"><span></span>cancelar</a>
                        <a href="#" onClick="document.getElementById('filtro').submit()" class="button icon icon_filter"><span></span>filtrar</a>
                    </div>
                </div>
            </div>
            <!--FIN SECCION BOTONES-->
            <?php echo form_close(); ?>
    <!--FIN SECCION DE FILTRO-->
    </div>


            <!--LISTADO DE CONSULTAS DEL USUARIO-->
            <?php if($consultas){ $i=1;?>
            <div class="colums two">
                <label>Listado de consultas</label>
                <ul>
            <?php foreach ($consultas as $consulta) { ?>
                <li><a href="<?=base_url()?>plan_general/ejecutar_consulta/<?=$consulta->id?>/<?=$produccion[0]->id_produccion?>"><?=$consulta->nombre?></a></li>
            <?php $i++;} ?>
                </ul>
            </div>
            <?php } ?>
            <!--FIN LISTADO DE CONSULTAS DEL USUARIO-->


    <?php if($escenas!=false AND $escenas!=""){ ?>

    <div id="asing_container" style="display:none;">
        <div id="scroll">
            <!--TABLA DE ASIGNACION-->
            <div class="normal_table">
                <table id="table_asign" style="display:none;">
                    <thead>
                        <tr>
                            <th width="10%"><span style="display:block; width:60px;">UNI.</span></th>
                            <th width="10%"><span style="display:block; width:90px;">Fec.</span></th>
                            <th width="2%"><span style="display:block; width:50px;">Lib.</span></th>
                            <th width="2%"><span style="display:block; width:60px;">Esc.</span></th>
                            <th width="2%"><span style="display:block; width:60px;">Pág.</span></th>
                            <th width="2%"><span style="display:block; width:60px;">Día</span></th>
                            <th width="2%"><span style="display:block; width:90px;">Loc.</span></th>
                            <th width="2%"><span style="display:block; width:120px;">Set.</span></th>
                            <th width="3%"><span style="display:block; width:90px;">Int/Ext</span></th>
                            <th width="3%"><span style="display:block; width:60px;">D/N</span></th>
                            <th width="3%"><span style="display:block; width:100px;">Loc/est</span></th>
                            <th width="3%"><span style="display:block; width:90px;">tie. est</span></th>
                            <th width="3%"><span style="display:block; width:100px;">tie. real</span></th>
                            <th width="5%"><span style="display:block; width:250px;">Personajes</span></th>
                            <th width="5%"><span style="display:block; width:300px;">Descripción</span></th>
                            <th width="5%"><span style="display:block; width:250px;">Elementos</span></th>
                        </tr>
                    </thead>
                    <tbody id="sorterPlanGeneral"></tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <a href="#" id="desasignarEscenasSeleccinadas" style="display:none;" class="button">Desasignar escenas seleccionadas</a>
        </div>
        <br><br>
        <!--FIN TABLA DE ASIGNACION-->
        <!--SECCION DE RESUMEN-->
        <div class="row">
            <div class="column five">
                <div class="row">
                    <div class="column six"><label class="left">Nro. Escenas seleccionadas:</label> &nbsp;&nbsp; <strong id="selected_escenes"></strong></div>
                    <div class="column six"><label class="left">Tiempo Acumulado: &nbsp;&nbsp;</label><strong id="time_accumulated"></strong></div>
                </div>
                <br><br>
            </div>
        </div>
        <!--SECCION DE RESUMEN-->
        <!--SECCION PARA ASIGNAR A UN PLAN DIARIO-->
        <?php echo form_open('plan_general/asignar_plan_diario',' class="", id="Asing", name="Asing"');?>
            <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>">
            <input type="hidden" id="idescenas" name="idescenas">
            <div class="row">

                <!--UN CAMPO PARA UNIDAD-->
                <div class="columns three">
                    <label>Únidad</label>
                        <select name="unidad" id="unidad_selector">
                            <option value="">Seleccione una unidad</option>
                        <?php $j=1;foreach ($unidades as $unidad) { ?>
                            <option value="<?=$unidad['id']?>"><?=$j?></option>
                        <?php ++$j;} ?> 
                    </select>
                    <!--SPANS OCULTOS CON FECHA DE INCIO UNIDADES-->
                    <?php $j=1;foreach ($unidades as $unidad) { ?>
                    <?php if($unidad['fecha_inicio']!="" AND $unidad['fecha_inicio']!="0000-00-00"){?>
                        <?php if(strtotime($unidad['fecha_inicio'])>=strtotime(date("d-M-Y"))){?>
                            <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""><?=date("d-M-Y",strtotime($unidad['fecha_inicio']))?></span>
                        <?php }else{ ?>
                            <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""><?=date("d-M-Y")?></span>
                        <?php } ?>
                    <?php }else{ ?>
                        <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""></span>
                    <?php } } ?>
                </div>
                <!--FIN UN CAMPO PARA UNIDAD-->

                <!--CAMPO PARA FECHA INICIO-->
                <div class="columns three" id="date_plan">
                    <label for="start_recording">Fecha Grabación</label>
                    <input type="text" placeholder="dd/mm/aaaa" id="start_plan" name="fecha_inicio" class="required">
                   <label class="error"><?php echo form_error('inicio_grabacion'); ?></label>
                </div>
                <!--
                <div class="columns three">
                   <label for="end_recording">Fin Grabación</label>
                   <input type="text" placeholder="dd/mm/aaaa" id="end_plan" name="fecha_fin" class="required">
                   <label class="error"><?php echo form_error('fin_grabacion'); ?></label>
                </div>-->

                <div class="column three">
                    <br>
                    <a href="#" id="insert_asing_button" class="button icon icon_save"><span></span>Asignar</a>
                    <a href="#" id="cancel_asing" class="button secondary">Cancelar</a>
                </div>
            </div>
        <?php echo form_close(); ?>
        <!--SECCION PARA ASIGNAR A UN PLAN DIARIO-->
    </div>

    <?php if($msg==1){ ?>
    <div class="alert-box alert">
        No se encontraron coincidencias<a href="" class="close">×</a>
    </div>
    <?php } ?>



    <!--TABLA PRINCIPAL-->
    <div id="scroll">
        <div class="normal_table">
            <table id="table_general" class="tablesorter">
                <thead>
                    <tr>
                        <th width="10%"><span style="display:block; width:60px;">UNI.</span></th>
                        <th width="10%"><span style="display:block; width:90px;">Fec.</span></th>
                        <th width="2%"><span style="display:block; width:50px;">Lib.</span></th>
                        <th width="2%"><span style="display:block; width:60px;">Esc.</span></th>
                        <th width="2%"><span style="display:block; width:60px;">Pág.</span></th>
                        <th width="2%"><span style="display:block; width:60px;">Día</span></th>
                        <th width="2%"><span style="display:block; width:90px;">Loc.</span></th>
                        <th width="2%"><span style="display:block; width:120px;">Set.</span></th>
                        <th width="3%"><span style="display:block; width:90px;">Int/Ext</span></th>
                        <th width="3%"><span style="display:block; width:60px;">D/N</span></th>
                        <th width="3%"><span style="display:block; width:100px;">Loc/est</span></th>
                        <th width="3%"><span style="display:block; width:90px;">tie. est</span></th>
                        <th width="3%"><span style="display:block; width:100px;">tie. real</span></th>
                        <th width="5%"><span style="display:block; width:250px;">Personajes</span></th>
                        <th width="5%"><span style="display:block; width:300px;">Descripción</span></th>
                        <th width="5%"><span style="display:block; width:250px;">Elementos</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($escenas as $escena) {?>
                    <tr class="actionAsing" id="<?='row_'.$escena->id?>" data-idescena="<?=$escena->id?>">
                        <!-- <td class="align_center"><input type="checkbox" class="asing_tabla2" idescena="<?=$escena->id?>"></td> -->
                        <?php switch($escena->estado){
                            case 1:
                            $class="td_yellow";
                            break;
                            case 2:
                            $class="td_retoma";
                            break;
                            case 3:
                            $class="td_black";
                            break;
                            case 4:
                            $class="td_cian";
                            break;
                            case 5:
                            $class="td_cian_light";
                            break;
                            case 6:
                            $class="td_green";
                            break;
                            case 7:
                            $class="td_green_light";
                            break;
                            case 8:
                            $class="td_pink";
                            break;
                            case 9:
                            $class="td_pink_light";
                            break;
                            case 10:
                            $class="td_orange";
                            break;
                            case 11:
                            $class="td_orange_light";
                            break;
                            default:
                            $class="td_brown_light";
                            break;
                        }?>
        
                        <td class="align_center <?=$class?>"><?php if($escena->unidad_numero!=0){
                            echo $escena->unidad_numero;
                        }else{
                            echo '-';
                        }?></td>
                        <td class="align_center <?=$class?>"><?php if($escena->fecha_inicio!="" AND $escena->fecha_inicio!="0000-00-00" ){
                            echo date("d-M-Y",strtotime($escena->fecha_inicio));
                        }else{
                            echo '-';
                        }?></td>
                        <td class="align_center"><?=$escena->capitulo?></td>
                        <td class="align_center"><?=$escena->numero_escena?></td>
                        <td class="align_center"><?=$escena->libreto?></td>
                        <td class="align_center"><?=$escena->dias_continuidad?></td>
                        <td class="cell_align_left"><?=$escena->locacion?></td>
                        <td class="cell_align_left"><?=$escena->setnombre?></td>
                        <td class="align_center"><?=$escena->ubicacion?></td>
                        <td class="align_center"><?=$escena->tiempo?></td>
                        <td class="align_center"><?=$escena->tipo?></td>
                        <td class="align_center"><?php 
                        if(strlen($escena->duracion_estimada_minutos)<2){
                            echo '0'.$escena->duracion_estimada_minutos.':';
                        }else{
                            echo $escena->duracion_estimada_minutos.':';
                        }
        
                        if(strlen($escena->duracion_estimada_segundos)<2){
                            echo '0'.$escena->duracion_estimada_segundos;
                        }else{
                            echo $escena->duracion_estimada_segundos;
                        }
                        ?></td>
                        <td class="align_center"><?php 
                        if(strlen($escena->duracion_real_minutos)<2){
                            echo '0'.$escena->duracion_real_minutos.':';
                        }else{
                            echo $escena->duracion_real_minutos.':';
                        }
        
                        if(strlen($escena->duracion_real_segundos)<2){
                            echo '0'.$escena->duracion_real_segundos;
                        }else{
                            echo $escena->duracion_real_segundos;
                        }?></td>
                        <td class="cell_align_left">
                        <span style="color:#333;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->personajes;?>">
                        <?php
                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                              echo Plan_general::corta_palabra($escena->personajes,30);
                              if(strlen($escena->personajes)>=30  ){
                                echo '...';};
                        
                        ?></span></td>
                        <td width="5%"> <div class="descriptionText">
                        <span style="color:#333;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->descripcion;?>">
                        <?php 
                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES descripcion*/
                        echo Plan_general::corta_palabra($escena->descripcion,40);
                              if(strlen($escena->descripcion)>=40){
                                echo '...';
                        };
                        ?></span></td>
                        <td>
                        <span style="color:#333;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->elementos; ?>">
                        <?php 
                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES ELEMENTOS*/
                        echo Plan_general::corta_palabra($escena->elementos,30);
                              if(strlen($escena->elementos)>=30){
                                echo '...';
                        };
                        ?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <!--FIN TABLA PRINCIPAL-->
        </div>
    </div>
    <div class="row">
        <a href="#" id="asignarEscenasSeleccinadas" style="display:none;" class="button">Asignar escenas seleccionadas</a>
    </div>

    <!--SECCION APRA GUARDAR LAS CONSULTAS-->
    <?php echo form_open('plan_general/guardar_consulta',' class="", id="Save"');?>
    <?php if($msg!="" AND $msg!=1){?>

    <div class="colums three">
        <label>GUARDAR CONSULTA</label>
        <input type="hidden" name="idproduccion" id="idproduccion" value="<?=$produccion[0]->id_produccion?>"> 
        <input type="hidden" name="consulta" value="<?=$msg?>"> 
        <label>Nombre:</label><input type="text" name="nombre" class="required">
        <a href="#" id="asing_button" onClick="document.getElementById('Save').submit()" class="button icon icon_filter"><span></span>Guardar Consulta</a>
        <a href="javascript:history.go(-1)" class="button icon">Regresar</a>
    </div>
    <?php } ?>
    <?php echo form_close(); ?>
    <!--FIN SECCION APRA GUARDAR LAS CONSULTAS-->

    <!--CAMṔO OCULTO FECHA FIN GRABACION-->
    <input type="hidden" id="recording_end" value="<?=date("d-M-Y",strtotime($produccion[0]->fin_grabacion))?>">
    <!--FIN CAMPO OCULTO FECHA FIN GRABACION-->
    <?php } ?>
</div>



