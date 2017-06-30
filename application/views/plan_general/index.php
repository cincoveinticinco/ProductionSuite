<?php $idioma = $this->lang->lang().'/'; setlocale(LC_TIME, 'es_ES.UTF-8'); ?>
<div id="ordenarWrap">
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
            <?php echo form_open($idioma.'plan_general/orden_columnas','id="arderFormPLanDiario"') ?>
            <input type="hidden" name="campos_columnas" id="campos_select" value="">
            <input name="idproduccion" type="hidden" value="<?=$produccion[0]->id_produccion?>">
                <div class="row" id="order_fields">
                    <div class="column six">
                        <h3><?php echo lang('global.campos_activos') ?></h3>
                        <ul id="itemsEnable" class="connectedSortable">
                        <?php if($campos_usuario){?>
                          <?php for ($i=0; $i < count($campos_usuario)-1; $i++) { ?>
                            <li class="active_field" data-order="<?=$i+1?>"><?=$campos_usuario[$i]?></li>
                        <?php } } ?>
                        </ul>
                    </div>
                    <div class="column six">
                        <h3><?php echo lang('global.campos_inactivos') ?></h3>
                        <ul id="itemsDisable" class="connectedSortable">
                          <?php 
                          for ($i=0; $i < count($campos); $i++) { 
                            $validacion = true;
                            for ($j=0; $j < count($campos_usuario)-1; $j++) { 
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
                            <input type="submit" id="save_fields" value="<?php echo lang('global.aplicar_cambios') ?>" class="button">
                        </div>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.plan_general') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>

<nav>
  <input type="hidden" name="id_produccion_estado" id="id_produccion_estado" value="<?php echo $produccion[0]->id_produccion ?>">
    <ul class="nav_post nav_plan_general"> 
        <li>
            <a href="#" class="buttons icon icon_filter" id="filter_button2">
                <span></span>
                <?php echo lang('global.filtrar') ?>
            </a>
        </li>
        <li>
            <a href="#" id="ordenarPlanLink" class="buttons icon icon_sort">
                <span></span>
                <?php echo lang('global.ordenar') ?>
            </a>
        </li>

        <li>
            <a href="#" id="pdf_plan_general" class="buttons icon icon_print">
                <span></span>
                <?php echo lang('global.imprimir') ?>
            </a>
        </li>
        <li>
            <a href="#" id="excel_plan_general" class="buttons icon icon_save">
                <span></span>
                <?php echo lang('global.guardar') ?>
            </a>
        </li>
        <!-- <li>
            <a href="#" class="buttons help_button help_planProd">
                <span class="open"></span>
                <?php echo lang('global.ayuda') ?>
            </a>
        </li> -->
        <li>
            <a href="#" class="buttons icon full_screen">
                <span class="open"></span><span class="close"></span>
                <?php echo lang('global.full_screen') ?>
            </a>
        </li>  
    </ul>
</nav>

<div id="inner_content" class="pagePlanDiario">
    <div class="top_page_plangeneral row field_form">
        <div class="columns three">
                <input type="text" id="buscar_escena" class="search_input">
        </div>
        <div class="columns three" <?php  if($escenas==false OR $escenas==""){ echo 'style="display:none;"';} ?>>
            <div class="escenasNoProducidas" id="selected_scenes">

                <?php echo lang('global.escenas') ?>: <?=$escenas_noproducidas?>
            </div>
            <input type="hidden" value="<?=$escenas_noproducidas?>" class="total_escenas">
        </div>
        <div class="columns three" <?php  if($escenas==false OR $escenas==""){ echo 'style="display:none;"';} ?>>
            <div class="escenasNoProducidas" id="selected_time">
                <?php echo lang('global.tiempo_estimado') ?>: <?=$tiempo_diponible?>
            </div>
        </div>
        <div class="columns three" <?php  if($escenas==false OR $escenas==""){ echo 'style="display:none;"';} ?>>
            <div class="escenasNoProducidas" id="time_prod">
                <?php echo lang('global.tiempo_producido') ?>: 00:00
            </div>
        </div>

        <div class="columns three">
            <a href="#" class="button go_back" style="display:none; width:100%;margin: 0 0 6px 0;"><?php echo lang('global.limpiar_filtro') ?></a>
            <?php if($produccion['0']->estado!=2){ ?>
            <br>
            <a href="#" id="asignarEscenasSeleccinadas" style="display:none; width:100%;" class="button asignarEscenasSeleccinadas"><?php echo lang('global.asignar_escenas_seleccionadas') ?></a>
            <?php } ?>
        </div>
        <div class="clr8"></div>
        <div class="columns twelve">
            <div id="resume" class="left"><?php if($resumen!=""){ echo "<label>Filtrado por: ".$resumen.'</label>'; } ?></div>
        </div>
    </div>
    <div class="content_filter row" id="content_filter" style="display:none;">
        <!--LISTADO DE CONSULTAS DEL USUARIO-->
        <?php if($consultas){ 
            $i=1;?>
            <div class="colums four" id="consult_list">
                <label><?php echo lang('global.listado_filtros') ?></label>
                <div class="consult_list">
                    <?php foreach ($consultas as $consulta) { ?>
                    <?php $size = sizeof($consultas); ?>
                    <a class="nameConsulta consult_<?=$consulta->id?>" href="<?=base_url().$idioma?>plan_general/ejecutar_consulta/<?=$consulta->id?>/<?=$produccion[0]->id_produccion?>"><?=$consulta->nombre?></a>
                    <a class="delete_consult consult_<?=$consulta->id?>" data-idconsulta="<?=$consulta->id?>" data-idproduccion="<?=$produccion[0]->id_produccion?>"><?php echo lang('global.eliminar') ?></a><?php if ($size>=2): ?>, <?php endif ?>
                    <?php $i++; } ?>
                </div>
            </div>
        <?php } ?>
        <!--FIN LISTADO DE CONSULTAS DEL USUARIO-->
        <?php echo form_open($idioma.'plan_general/filtro',' class="", id="filtro"');?>
            <input type="hidden" name="idproduccion" id="idproduccion" value="<?=$produccion[0]->id_produccion ?>"> 
            <input type="hidden" name="orderBy" id="orderBy" value=""> 
            <div class="row">
                <table class="tabla_info">
                    <tr>
                        <td width="15%">
                            <!--RANGO DE LIBRETOS-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for=""><?php echo lang('global.rango_libretos') ?></label>
                                </div>
                                <div class="column six">
                                    <label for=""><?php echo lang('global.desde') ?></label>
                                    <input type="text" name="limite1_cap" id="limite1_cap" class="required" placeholder="00"> 
                                </div>
                                <div class="column six">
                                    <label for=""><?php echo lang('global.hasta') ?></label>
                                    <input type="text" type="text" name="limite2_cap" id="limite2_cap" class="required" placeholder="00">  
                                </div>
                            </div>
                            <!--FIN RANGO DE LIBRETOS-->
                        </td>
                        <td width="15%">
                            <!--RANGO DE ESCENAS-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for=""><?php echo lang('global.rando_escenas') ?></label>
                                </div>
                                <div class="column six">
                                    <label for=""><?php echo lang('global.desde') ?></label>
                                    <input type="text" name="limite1_esc" id="limite1_esc" class="required" placeholder="00">    
                                </div>
                                <div class="column six">
                                    <label for=""><?php echo lang('global.hasta') ?></label>
                                    <input type="text" name="limite2_esc" id="limite2_esc" class="required" placeholder="00">    
                                </div>
                            </div>
                            <!--FIN RANGO DE ESCENAS-->
                        </td>
                        <td width="25%">
                            <!--RANGO DE FECHAS-->
                            <div class="row dateRange">
                                <div class="column twelve">
                                    <label for=""><?php echo lang('global.rango_fechas') ?></label>
                                </div>
                                <div class="column four">
                                    <label for="start_recording"><?php echo lang('global.inicio') ?>:</label>
                                    <input type="text" placeholder="dd/mm/aaaa" id="start_recording" name="limite1_fec" class="required">
                                    <label class="error"><?php echo form_error('inicio_grabacion'); ?></label>
                                </div>
                                <div class="column four">
                                    <label for="end_recording"><?php echo lang('global.fin') ?>:</label>
                                    <input type="text" placeholder="dd/mm/aaaa" id="end_recording" name="limite2_fec" class="required">
                                    <label class="error"><?php echo form_error('fin_grabacion'); ?></label>
                                </div>
                                <div class="column three">
                                    <!--DOS CAMPOS PARA CON Y SIN-->
                                    <input type="hidden" name="con" id="con_hidden" class="required">
                                    <input type="hidden" name="sin" id="sin_hidden" class="required">
                                    <!--FIN DOS CAMPOS PARA CON Y SIN-->
                                    <!--UN CAMPO PARA UNIDAD-->
                                    <div class="row">
                                        <div class="columns twelve">
                                            <label><?php echo lang('global.unidad') ?></label>
                                            <select name="unidad" id="unidad" class="required">
                                                <option value="">#</option>
                                            <?php $j=1;foreach ($unidades as $unidad) { ?>
                                               <option value="<?=$unidad['id']?>"><?=$j?></option>
                                            <?php ++$j;} ?> 
                                            </select>
                                        </div>
                                    </div>
                                    <!--FIN UN CAMPO PARA UNIDAD-->
                                </div>  
                            </div>
                            <!--FIN RANGO DE FECHAS-->
                        </td>
                        <td width="20%">
                            <!--SELECT LOCACION-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="locacion"><?php echo lang('global.locacion') ?></label>
                                    <div id="locacion_section">
                                    <?php foreach($locaciones as $locacion) { ?>
                                         <label> 
                                            <input name="locacion[]" class="locacion_select" type="checkbox" value="<?php echo $locacion['id'] ?>"><?php echo $locacion['nombre'] ?>
                                         </label>  
                                    <?php } ?> 
                                    </div>
                                    <br><br>
                                </div>
                            </div>
                            <!--FIN SELECT LOCACION-->
                        </td>
                        <td width="25%">
                            <!--SELECT SET-->
                            <div class="row">
                                <div class="column twelve">
                                    <label for="locacion"><?php echo lang('global.set') ?></label>
                                    <!--select name="set" id="set" class="set" style="display:block!important;">
                                        <option value="">Todos</option-->
                                        <div id="locacion_section" class="sets_locaciones">
                                        <?php if($sets){ ?>
                                            <?php foreach ($sets as $set) {?>
                                                <label> 
                                                    <input name="sets[]" type="checkbox" value="<?php echo $set->id ?>"><?php echo $set->nombre ?>
                                                 </label>  
                                            <?php } ?>
                                        <?php } ?>
                                        </div>
                                    <!--/select-->
                                    <br>
                                </div>
                            </div>
                            <!--FIN SELECT SET-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--CAMPOS INTERIOR EXTERIOR-->
                            <?php foreach ($interior_exterior as $the_interior_exterior) { ?>
                            <div class="columns" style="float:left;">
                                <label for="int_ext"><?=$the_interior_exterior['descripcion']?>:</label>
                                <input type="radio" name="int_ext" class="required int_ext unchecked" value="<?=$the_interior_exterior['id']?>">
                            </div>
                            <?php } ?>
                            <!--FIN CAMPOS INTERIOR EXTERIOR-->
                        </td>
                        <td>
                            <!--CAMPOS LOC/TU/EST-->
                            <?php foreach ($tipo_locacion as $the_tipo_locacion) { 
                                if($the_tipo_locacion['id']!=3){ ?>
                            <div class="columns" style="float:left;">
                                <label for="tipo_locacion"><?=$the_tipo_locacion['tipo']?>:</label>
                                <input type="radio" name="tipo_locacion" id="tipo_locacion<?=$the_tipo_locacion['id']?>" class="required unchecked" value="<?=$the_tipo_locacion['id']?>">
                            </div>
                            <?php } } ?>
                            <!--FIN CAMPOS LOC/TU/EST-->
                        </td>
                        <td>
                            <!--CAMPOS DIA NOCHE-->
                            <?php foreach ($dia_noche as $the_dia_noche) { ?>
                            <div class="columns" style="float:left;">
                                <label for="dia_noche"><?=$the_dia_noche['descripcion']?>:</label>
                                <input type="radio" name="dia_noche" class="required dia_noche unchecked" value="<?=$the_dia_noche['id']?>">
                            </div>
                            <?php } ?>
                            <!--FIN CAMPOS DIA NOCHE--> 
                        </td>
                        <td>
                            <!--CAMPOS T.U. - FLASHBACK-->
                            <div class="columns" style="float:left;">
                                <label for="toma_ubicacion">T.U:</label>
                                <input type="checkbox" name="toma_ubicacion" id="toma_ubicacion" class="required" value="1">
                            </div>
                            <div class="columns" style="float:left;">
                                <label for="flashback"><?php echo lang('global.flashback') ?>:</label>
                                <input type="checkbox" name="flashback" id="flashback" class="required" value="1">
                            </div>
                            <!--FIN CAMPOS T.U. - FLASHBACK-->
                        </td>
                        <td>
                            <!--CAMPOS images archvio foto realziacion.-->
                            <div class="columns" style="float:left;">
                                <label for="foto_realizacion_"><?php echo lang('global.foto_realizacion') ?></label>
                                <input type="checkbox" name="foto_realizacion" id="foto_realizacion" class="required" value="1">
                            </div>
                            <div class="columns" style="float:left;">
                                <label for="imagenes_archivo"><?php echo lang('global.imagenes_archivo') ?>:</label>
                                <input type="checkbox" name="imagenes_archivo" id="imagenes_archivo" class="required" value="1">
                            </div>
                            <!--FIN CAMPOS images archvio foto realziacion-->
                        </td>
                    </tr>
                    <tr>  
                        <td colspan="2">
                            <!--CAMPOS PRODUCIDAS - CANCELADAS - TODAS -->
                            <div class="columns">
                                <label><?php echo lang('global.estado') ?>:</label>
                                <select name="esc_estado" id="esc_estado">
                                    <option value="0"><?php echo lang('global.seleccion_opcion') ?></option>
                                    <option value="4"><?php echo lang('global.todas') ?></option>
                                    <option value="1"><?php echo lang('global.producidas') ?></option>
                                    <option value="5"><?php echo lang('global.post_producidas') ?></option>
                                    <option value="2"><?php echo lang('global.retoma') ?></option>
                                    <option value="6"><?php echo lang('global.asignada') ?></option>
                                    <option value="7"><?php echo lang('global.no_asignado') ?></option>
                                    <option value="3"><?php echo lang('global.canceladas') ?></option>
                                </select>
                            </div>
                            <!--FIN CAMPOS PRODUCIDAS - CANCELADAS -TODAS -->
                        </td>
                        <td>
                            <div class="columns" style="float:left;">
                                <label for="magnitud"><?php echo lang('global.magnitud') ?>:</label>
                                <select id="magnitud" name="magnitud">
                                    <option value="">Todos</option>
                                    <?php foreach ($magnitudes as $magnitud) { ?>
                                            <option value="<?=$magnitud->id?>"><?=$magnitud->descripcion?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="columns" style="float:left;">
                                <label for="magnitud"><?php echo lang('global.vehivulo_back') ?>:</label>
                                 <input onkeypress="return soloLetras(event)" type="text" max="999" id="vehiculo_background" name="vehiculo_background">
                            </div>
                        </td>
                        <td>
                            <div class="columns" style="float:left;">
                                <label for="magnitud"><?php echo lang('global.ordenado_por') ?>:</label>
                                <select id="order_by" name="order_by">
                                    <option value=""><?php echo lang('global.libreto_escena') ?></option>
                                    <option value="fecha_inicio"><?php echo lang('global.fecha') ?></option>
                                    <option value="capitulo"><?php echo lang('global.numero_libretos') ?></option>
                                    <option value="numero_escena"><?php echo lang('global.escenas') ?></option>
                                    <option value="libreto"><?php echo lang('global.pagina') ?></option>
                                    <option value="dias_continuidad"><?php echo lang('global.dia') ?></option>
                                    <option value="locacion"><?php echo lang('global.locacion') ?></option>
                                    <option value="setnombre"><?php echo lang('global.set') ?></option>
                                    <option value="ubicacion"><?php echo lang('global.int_ext') ?></option>
                                    <option value="tiempo"><?php echo lang('global.dia_noche') ?></option>
                                    <option value="tipo"><?php echo lang('global.locacion_estudio') ?></option>
                                    <option value="duracion_estimada_minutos,duracion_estimada_segundos"><?php echo lang('global.tiempo_estimado') ?></option>
                                    <option value="duracion_real_minutos,duracion_real_segundos"><?php echo lang('global.tiempo_real') ?></option>
                                    <option value="tiempo_post_minutos,tiempo_post_segundos"><?php echo lang('global.tiempo_post') ?></option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="columns" style="float:left;">
                                <label for="dia_continuidad"><?php echo lang('global.dia_continuidad') ?>:</label>
                                <input type="text" name="dia_continuidad" placeholder='Separar con "-"' id="dia_continuidad"  value="">
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="column twelve">
                    <div class="row">
                        <div class="columns twelve">
                          <label for=""><?php echo lang('global.categoria') ?></label>
                          <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns six">
                             <!--SELECT DE CATEGORIAS-->   
                            <select name="" idescena="" id="elemento_id"  idproduccion="<?=$produccion['0']->id_produccion?>">
                              <option value="0"><?php echo lang('global.ver_todos') ?></option>
                              <?php foreach ($categoria_elemento as $c) { ?>
                               <option value="<?php echo $c['id'] ?>" idproduccion="<?=$produccion['0']->id_produccion?>"><?php echo $c['tipo'] ?></option>
                              <?php } ?>
                            </select>
                        </div>
                        <div class="columns six"><input style="cursor:pointer" type="text" id="buscar_elemento" idproduccion="<?=$produccion['0']->id_produccion?>" class="search_input"></div>
                    </div>
                    <div class="row">
                        <!--TABLA NO 1 DE ELEMENTOS-->
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
                        <!--FIN TABLA NO 2 DE ELEMENTOS-->
                    </div>
                    <br><br>
                </div>
            </div>
            <!--SECCION BOTONES-->
            <div class="row">
                <div class="twelve columns">
                    <div class="call_to_action align_left">
                        <a href="#" class="button secondary icon icon_cancel" id="cancel_filter"><span></span><?php echo lang('global.cancelar') ?></a>
                        <a href="#" id="filter_button" class="button icon icon_filter filter_button"><span></span><?php echo lang('global.filtrar') ?></a>
                        <a href="#" id="filterSave_button" class="button icon "><span></span><?php echo lang('global.filtrar_guardad') ?></a>
                        <a href="#" id="limpiar_seleccion" class="button"><?php echo lang('global.limpiar_seleccion') ?></a> 
                    </div>
                </div>
            </div>
            <!--FIN SECCION BOTONES-->
            <?php echo form_close(); ?>
    </div>

    <div id="asing_container" style="display:none;">
        <!--SECCION DE RESUMEN-->
        <div class="top_page top_page_plangeneral row">
            <div class="left" style="width:100%;">
              <div class="columns three">
                <br>
                <div class="escenasNoProducidas gray">
                    <?php echo lang('global.escenas_seleccionadas') ?>:</label> <strong id="selected_escenes"></strong>
                </div>
              </div>
              <div class="columns three">
                <br>
                <div class="escenasNoProducidas gray">
                    <?php echo lang('global.tiempo_acumulado') ?>:  </label><strong id="time_accumulated"></strong>
                </div>
                <div class="escenasNoProducidas gray" >
                    <?php echo lang('global.tiempo_producido') ?>:  </label><strong id=""></strong>
                </div>
              </div>
              <div class="columns six">
                <div class="row">
                    <!--SECCION PARA ASIGNAR A UN PLAN DIARIO-->
                    <?php echo form_open($idioma.'plan_general/asignar_plan_diario',' class="", id="Asing_plan", name="Asing"');?>
                        <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>">
                        <input type="hidden" id="idescenas" name="idescenas">
                        <input type="hidden" id="idplanes" name="idplanes">
                        <input type="hidden" id="fechas" name="fechas">
                    <div class="column three">
                        <label><?php echo lang('global.unidad') ?></label>
                        <select name="unidad" id="unidad_selector">
                                <option value="">Seleccione una unidad</option>
                            <?php $j=1;foreach ($unidades as $unidad) { ?>
                                <option value="<?=$unidad['id']?>"><?=$unidad['numero']?></option>
                            <?php ++$j;} ?> 
                        </select>
                        <!--SPANS OCULTOS CON FECHA DE INCIO UNIDADES-->
                        <?php $j=1;foreach ($unidades as $unidad) { ?>
                        <?php if($unidad['fecha_inicio']!="" AND $unidad['fecha_inicio']!="0000-00-00"){?>
                            <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""><?=strftime('%d-%b-%Y',strtotime($unidad['fecha_inicio']))?></span>
                        <?php }else{ ?>
                            <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""></span>
                        <?php } } ?>
                    </div>
                    <div class="column three">
                        <!--CAMPO PARA FECHA INICIO-->
                        <div id="date_plan">
                            <label for="start_recording"><?php echo lang('global.fecha_grabacion') ?></label>
                            <input type="text" placeholder="dd/mm/aaaa" id="start_plan" name="fecha_inicio" class="required">
                           <label class="error"><?php echo form_error('inicio_grabacion'); ?></label>
                        </div>
                    </div>
                    <div class="columns six">
                        <br>
                        <a href="#" id="insert_asing_button" class="button icon icon_save"><?php echo lang('global.asignar') ?></a>
                        <a href="#" id="cancel_asing" class="button secondary"><?php echo lang('global.cancelar') ?></a>
                        <a href="#" id="imprimir_seleccion" class="button secondary icon icon_save" ><span></span><?php echo lang('global.guardar') ?></a>
                    </div>
                    <?php echo form_close(); ?>
                    <!--SECCION PARA ASIGNAR A UN PLAN DIARIO-->
                </div>
              </div>
            </div>
        </div>
        <!--SECCION DE RESUMEN-->
        <div id="scroll">
            <!--TABLA DE ASIGNACION-->
            <div class="normal_table">
                <table id="table_asign" style="display:none;">
                    <thead>
                        <tr>
                            <th width="10%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.unidad') ?>" style="display:block; width:60px;">UNI</span></th>
                        <th width="10%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.fecha') ?>" style="display:block; width:90px;"><?php echo lang('global.fecha') ?></span></th>
                        <?php if($campos_usuario){ 
                            for ($i=0; $i < count($campos_usuario)-1; $i++) { 
                                switch ($campos_usuario[$i]) {
                                   case "libreto":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_libretos').'" style="display:block; width:50px;">LIB</span></th>';
                                   break;
                                   case "escena":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_escena').'" style="display:block; width:60px;">ESC</span></th>';
                                   break;
                                   case "página":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.pagina').'"  style="display:block; width:100px;">'.lang('global.pagina').'</span></th>';
                                   break;
                                   case "día":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_continuidad').'"  style="display:block; width:60px;">'.lang('global.dia').'</span></th>';
                                   break;
                                   case "locación":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.locacion').'"  style="display:block; width:100%;">'.lang('global.locacion').'</span></th>';
                                   break;
                                   case "set":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.set').'"  style="display:block; width:100%;">'.lang('global.set').'</span></th>';
                                   break;
                                   case "int/ext":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.int_ext').'"  style="display:block; width:90px;">INT/EXT</span></th>';
                                   break;
                                   case "d/n":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_noche').'"  style="display:block; width:106px;">'.lang('global.dia_noche').'</span></th>';
                                   break;
                                   case "loc/ext":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.loc_est').'"  style="display:block; width:100px;">LOC/EST</span></th>';
                                   break;
                                   case "tiempo estimado":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_estimado').'"  style="display:block; width:90px;">TIE EST</span></th>';
                                   break;
                                   case "tiempo real":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_real').'"  style="display:block; width:100px;">TIE REAL</span></th>';
                                   break;
                                   case "tiempo post":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_post').'"  style="display:block; width:100px;">TIE POST</span></th>';
                                   break;
                                   case "personajes principales":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_principales').'" style="display:block; width:250px;">'.lang('global.personajes_principales').'</span></th>';
                                   break;
                                   case "personajes secundarios":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_secundarios').'" style="display:block; width:250px;">'.lang('global.personajes_secundarios').'</span></th>';
                                   break;
                                   case "descripción":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.descripcion').'" style="display:block; width:300px;">'.lang('global.descripcion').'</span></th>';
                                   break;
                                   case "elementos":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.elementos').'" style="display:block; width:250px;">'.lang('global.elementos').'</span></th>';
                                   break;
                                   case "magnitud":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.magnitud').'" style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>';
                                   break;
                                   case "vehículo background":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.vehivulo_back').'" style="display:block; width:250px;">'.lang('global.vehivulo_back').'</span></th>';
                                   break;
                                }
                            }
                        }else{?>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_libretos')?>" style="display:block; width:50px;">LIB</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escena')?>" style="display:block; width:60px;">ESC</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.pagina')?>"  style="display:block; width:100px;"><?php echo lang('global.pagina')?></span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_continuidad')?>"  style="display:block; width:60px;">DÍA</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.locacion')?>"  style="display:block; width:100%;"><?php echo lang('global.locacion')?></span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.set')?>"  style="display:block; width:100%;"><?php echo lang('global.set')?></span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.int_ext')?>"  style="display:block; width:90px;">INT/EXT</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_noche')?>"  style="display:block; width:106px;">DÍA/NOCHE</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.loc_est')?>"  style="display:block; width:100px;">LOC/EST</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_estimado')?>"  style="display:block; width:90px;">TIE EST</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_real')?>"  style="display:block; width:100px;">TIE REAL</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_post')?>"  style="display:block; width:100px;">TIE POST</span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.personajes_principales')?>" style="display:block; width:250px;"><?php echo lang('global.personajes_principales')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.personajes_secundarios')?>" style="display:block; width:250px;"><?php echo lang('global.personajes_secundarios')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.descripcion')?>" style="display:block; width:300px;"><?php echo lang('global.descripcion')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.elementos')?>" style="display:block; width:250px;"><?php echo lang('global.elementos')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.magnitud')?>" style="display:block; width:250px;"><?php echo lang('global.magnitud')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.vehivulo_back')?>" style="display:block; width:250px;"><?php echo lang('global.vehivulo_back')?></span></th>
                        <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="sorterPlanGeneral"></tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <a href="#" id="desasignarEscenasSeleccinadas" style="display:none;" class="button"><?php echo lang('global.eliminar_escenas') ?></a>
        </div>
        <!--FIN TABLA DE ASIGNACION-->
    </div>

    <div class="alert-box alert" id="alert_filter" style="display:none">
        No se encontraron coincidencias <a href="" class="close">×</a>
    </div>

    <div id="scroll">
        <div class="normal_table">
            <table <?php  if($usuario_permisos!="read"){ echo 'id="table_general"'; } ?>  class="tablesorter tabla_filtro_general" <?php  if($escenas==false OR $escenas==""){ echo 'style="display:none;"';} ?>>
                <thead>
                    <tr>
                        <th width="10%" class="unidad_order"><span class="has-tip tip-centered-top" title="<?php echo lang('global.unidad')?>" style="display:block; width:60px;">UNI</span></th>
                        <th width="10%" class="fecha_order"><span class="has-tip tip-centered-top" title="<?php echo lang('global.fecha')?>" style="display:block; width:90px;"><?php echo lang('global.fecha')?></span></th>
                        <?php if($campos_usuario){ 
                            for ($i=0; $i < count($campos_usuario)-1; $i++) { 
                                switch ($campos_usuario[$i]) {
                                   case "libreto":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_libretos').'" style="display:block; width:50px;">LIB</span></th>';
                                   break;
                                   case "escena":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_escena').'" style="display:block; width:60px;">ESC</span></th>';
                                   break;
                                   case "página":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.pagina').'"  style="display:block; width:100px;">'.lang('global.pagina').'</span></th>';
                                   break;
                                   case "día":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_continuidad').'"  style="display:block; width:60px;">'.lang('global.dia').'</span></th>';
                                   break;
                                   case "locación":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.locacion').'"  style="display:block; width:100%;">'.lang('global.locacion').'</span></th>';
                                   break;
                                   case "set":
                                    echo '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.set').'"  style="display:block; width:100%;">'.lang('global.set').'</span></th>';
                                   break;
                                   case "int/ext":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.int_ext').'"  style="display:block; width:90px;">INT/EXT</span></th>';
                                   break;
                                   case "d/n":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_noche').'"  style="display:block; width:106px;">'.lang('global.dia_noche').'</span></th>';
                                   break;
                                   case "loc/ext":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.loc_est').'"  style="display:block; width:100px;">LOC/EST</span></th>';
                                   break;
                                   case "tiempo estimado":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_estimado').'"  style="display:block; width:90px;">TIE EST</span></th>';
                                   break;
                                   case "tiempo real":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_real').'"  style="display:block; width:100px;">TIE REAL</span></th>';
                                   break;
                                   case "tiempo post":
                                    echo '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_post').'"  style="display:block; width:100px;">TIE POST</span></th>';
                                   break;
                                   case "personajes principales":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_principales').'" style="display:block; width:250px;">'.lang('global.personajes_principales').'</span></th>';
                                   break;
                                   case "personajes secundarios":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_secundarios').'" style="display:block; width:250px;">'.lang('global.personajes_secundarios').'</span></th>';
                                   break;
                                   case "descripción":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.descripcion').'" style="display:block; width:300px;">'.lang('global.descripcion').'</span></th>';
                                   break;
                                   case "elementos":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.elementos').'" style="display:block; width:250px;">'.lang('global.elementos').'</span></th>';
                                   break;
                                   case "magnitud":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.magnitud').'" style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>';
                                   break;
                                   case "vehículo background":
                                    echo '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.vehivulo_back').'" style="display:block; width:250px;">'.lang('global.vehivulo_back').'</span></th>';
                                   break;
                                }
                            }
                         }else{?>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_libretos')?>" style="display:block; width:50px;">LIB</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escena')?>" style="display:block; width:60px;">ESC</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.pagina')?>"  style="display:block; width:100px;"><?php echo lang('global.pagina')?></span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_continuidad')?>"  style="display:block; width:60px;">DÍA</span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.locacion')?>"  style="display:block; width:100%;"><?php echo lang('global.locacion')?></span></th>
                            <th width="2%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.set')?>"  style="display:block; width:100%;"><?php echo lang('global.set')?></span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.int_ext')?>"  style="display:block; width:90px;">INT/EXT</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_noche')?>"  style="display:block; width:106px;">DÍA/NOCHE</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.loc_est')?>"  style="display:block; width:100px;">LOC/EST</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_estimado')?>"  style="display:block; width:90px;">TIE EST</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_real')?>"  style="display:block; width:100px;">TIE REAL</span></th>
                            <th width="3%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_post')?>"  style="display:block; width:100px;">TIE POST</span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.personajes_principales')?>" style="display:block; width:250px;"><?php echo lang('global.personajes_principales')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.personajes_secundarios')?>" style="display:block; width:250px;"><?php echo lang('global.personajes_secundarios')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.descripcion')?>" style="display:block; width:300px;"><?php echo lang('global.descripcion')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.elementos')?>" style="display:block; width:250px;"><?php echo lang('global.elementos')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.magnitud')?>" style="display:block; width:250px;"><?php echo ".as.da-".lang('global.magnitud')?></span></th>
                            <th width="5%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.vehivulo_back')?>" style="display:block; width:250px;"><?php echo lang('global.vehivulo_back')?></span></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="resultado">
                    <?php if($escenas!=false AND $escenas!=""){ ?>
                    <?php foreach ($escenas as $escena) { $m=0;?>
                    <?php $class_tr = "actionAsing"; 
                    $fecha_inicio_escena = $escena->fecha_inicio;
                    if($escena->estado!=3 /*AND $escena->estado!=1*/ AND $usuario_permisos=="write"){
                            $class_tr = "actionAsing";
                        }else{
                            $class_tr="";
                        }

                        if($escena->estado == 1 AND $escena->unidad_numero==0 AND $escena->fecha_inicio==""){
                            $class_tr = "actionAsing";
                        }
                    if($escena->fecha_inicio!=""){
                        if(strtotime($escena->fecha_inicio) <= strtotime(strftime('%d-%b-%Y'))){
                            $class_tr = "oldPlan";
                        }
                    }

                    if($escena->planes_abiertos){
                        $class_tr = "oldPlan";
                        $temp_ex = explode('_', $escena->planes_abiertos);
                        $fecha_inicio_escena = $temp_ex[0];
                    }
                    ?>
                    <tr class="<?=$class_tr?>" data-plan="<?=date('d-M-Y',strtotime($fecha_inicio_escena))?>" data-unidad="<?=$escena->idunidad?>" data-numero="<?=$escena->numero_escena?>" id="<?='row_'.$escena->id?>" data-idescena="<?=$escena->id?>" data-libreto="<?=$escena->capitulo?>">
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
                            case 12:
                            $class="td_yellow";
                            break;
                            case 14:
                            $class="td_retoma";
                            break;
                            default:
                            $class="td_brown_light";
                            break;
                        }?>
        
                        <td class="align_center <?=$class?>">
                            <?php
                            if($escena->estado == 1 AND  $escena->unidad_produccion!=""){
                                if($escena->unidad_numero!=0){
                                    echo anchor($idioma.'plan_diario/index/'.$produccion[0]->id_produccion.'/'.$escena->unidad_produccion.'/'.$escena->fecha_produccion,$escena->unidad_produccion_numero); 
                                }else{
                                    echo '-';
                                }  
                            }else{
                                if($escena->unidad_numero!=0){
                                    echo anchor($idioma.'plan_diario/index/'.$produccion[0]->id_produccion.'/'.$escena->idunidad.'/'.$escena->fecha_inicio,$escena->unidad_numero); 
                                }else{
                                    echo '-';
                                }  
                            } 
                            ?>
                        </td>
                        <td class="align_center <?=$class?> fecha_plan">
                        <?php if($escena->fecha_inicio!="" AND $escena->fecha_inicio!="0000-00-00" ){?>
                        <a href="<?php base_url('plan_diario/index/'.$produccion[0]->id_produccion.'/'.$escena->idunidad.'/'.$escena->fecha_inicio) ?>"><span style='display:none'><?php echo date('Ymd',strtotime($escena->fecha_inicio)); ?></span><?php echo strftime('%d-%b-%Y',strtotime($escena->fecha_inicio)); ?></a>
                        <?php }else{
                            echo '-';
                     }?></td>
                        <?php while ( $m < count($campos_usuario)-1) {?>
                        <?php if($campos_usuario[$m]=="libreto"){ ++$m;?>
                        <td class="align_center"><?=$escena->capitulo?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="escena"){ ++$m;?>
                        <td class="align_center"><?=$escena->numero_escena?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="página"){ ++$m;?>
                        <td class="align_center"><?=$escena->libreto?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="día"){ ++$m;?>
                        <td class="align_center"><?=$escena->dias_continuidad?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="locación"){ ++$m;?>
                        <td class="cell_align_left"><?=$escena->locacion?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="set"){ ++$m;?>
                        <td class="cell_align_left"><?=$escena->setnombre?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="int/ext"){ ++$m;?>
                        <td class="align_center"><?=$escena->ubicacion?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="d/n"){ ++$m;?>
                        <td class="align_center"><?=$escena->tiempo?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="loc/ext"){ ++$m;?>
                        <td class="align_center"><?=$escena->tipo?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="tiempo estimado"){ ++$m;?>
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
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="tiempo real"){ ++$m;?>
                        <td class="align_center"><?php 
                        if($escena->duracion_real_minutos!=""){
                            if(strlen($escena->duracion_real_minutos)<2){
                                echo '0'.$escena->duracion_real_minutos.':';
                            }else{
                                echo $escena->duracion_real_minutos.':';
                            }
                        }else{
                            echo "00:";
                        }

                        if($escena->duracion_real_segundos!=""){
                            if(strlen($escena->duracion_real_segundos)<2){
                                echo '0'.$escena->duracion_real_segundos;
                            }else{
                                echo $escena->duracion_real_segundos;
                            }
                        }else{
                            echo "00";
                        }?></td>
                        <?php } ?>

                        <?php if($campos_usuario[$m]=="tiempo post"){ ++$m;?>
                        <td class="align_center"><?php 
                        /*if($escena->tiempo_post_minutos!=""){
                            if(strlen($escena->tiempo_post_minutos)<2){
                                echo '0'.$escena->tiempo_post_minutos.':';
                            }else{
                                echo $escena->tiempo_post_minutos.':';
                            }
                        }else{
                            echo "00:";
                        }

                        if($escena->tiempo_post_segundos!=""){
                            if(strlen($escena->tiempo_post_segundos)<2){
                                echo '0'.$escena->tiempo_post_segundos;
                            }else{
                                echo $escena->tiempo_post_segundos;
                            }
                        }else{
                            echo "00";
                        }*/
                        echo Plan_general::calculo_tiempo_post($escena->tiempo_post_minutos,$escena->tiempo_post_segundos,$escena->tiempo_post_cuadros);?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="personajes principales"){ ++$m;?>
                        <td class="cell_align_left">
                        <span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->personajes_principales;?>">
                        <?php
                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                            echo Plan_general::corta_palabra($escena->personajes_principales,35);
                            if(strlen($escena->personajes_principales)>=35){
                                    echo '...';
                            }
                        ?></span>
                        </td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="personajes secundarios"){ ++$m;?>
                        <td class="cell_align_left">
                        <span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->personajes_secundarios;?>">
                        <?php
                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                            echo Plan_general::corta_palabra($escena->personajes_secundarios,35);
                            if(strlen($escena->personajes_secundarios)>=35){
                                    echo '...';
                            }
                        ?></span>
                        </td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="descripción"){ ++$m;?>
                        <td width="5%"> 
                            <div class="descriptionText">
                                <span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->descripcion;?>">
                                <?php 
                                /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES descripcion*/
                                echo Plan_general::corta_palabra($escena->descripcion,40);
                                      if(strlen($escena->descripcion)>=40){
                                        echo '...';
                                }
                                ?></span>
                            </div>
                        </td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="elementos"){ ++$m;?>
                        <td>
                            <span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $escena->elementos; ?>">
                            <?php 
                            /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES ELEMENTOS*/
                            echo Plan_general::corta_palabra($escena->elementos,30);
                                  if(strlen($escena->elementos)>=30){
                                    echo '...';
                            }
                            ?></span>
                        </td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="magnitud"){ ++$m;?>
                        <td class="align_center"><?=$escena->magnitud_nombre?></td>
                        <?php } ?>
                        <?php if($campos_usuario[$m]=="vehículo background"){ ++$m;
                            $vehiculo ="-";
                            if($escena->vehiculo_background!=null AND $escena->vehiculo_background!='0'){
                                $vehiculo = $escena->vehiculo_background;
                            }
                        ?>
                        
                        <td class="align_center"><?=$vehiculo?></td>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!--FIN TABLA PRINCIPAL-->
        </div>
    </div>

    <div class="row top_page_plangeneral">
        <a href="#" id="asignarEscenasSeleccinadas" style="display:none;" class="button asignarEscenasSeleccinadas"><?php echo lang('global.asignar_escenas_seleccionadas') ?></a>
    </div>
    <!--CAMṔO OCULTO FECHA FIN GRABACION-->
    <input type="hidden" id="recording_end" value="<?=strftime('%d-%b-%Y',strtotime($produccion[0]->fin_grabacion))?>">
    <input type="hidden" id="work_days" value="<?php if(isset($dias_trabajo[0]->dias_trabajo)){ echo $dias_trabajo[0]->dias_trabajo;}?>">
    <input type="hidden" id="min_date" value="<?=strftime('%d-%b-%Y',strtotime($produccion[0]->inicio_grabacion))?>">
    
    <!--FIN CAMPO OCULTO FECHA FIN GRABACION-->
    <?php }else{?>
        <h3 id="message"><?php echo lang('global.no_hay_escenas')?></h3>
        </tbody>
    </table>
    <?php } ?>


    <!--CAMPO OCULTO PARA LOS ELEMENTOS SELECCIONADOS-->
    <input type="hidden" id="elements_ids" value="">
    <input type="hidden" id="validator" value="">
    <input type="hidden" id="fechas_bloqueadas" value="<?=$fechas_bloqueadas?>">


    <input type="hidden" value="<?php echo $total_escenas[0]->total_escenas ?>" id="total_escenas">
     <input type="hidden" value="<?php echo $limit_sup ?>" class="limit_plan_general">
     <input type="hidden" value="0" class="limit_plan_general_general">
     <input type="hidden" value="0" class="busqueda_general">
    <?php if($limit_sup<$total_escenas['0']->total_escenas){ ?>
    <div class="limit_pdf_excel">
            <input type="submit" value="<?php echo lang('global.ver_mas')?>" style="width: 50% !important;float:left" class="button" id="cargar_mas">
            <input type="submit" value="<?php echo lang('global.ver_todos')?>" style="width: 50% !important;float:left" class="button" id="cargar_todos">
            <!-- FORMULARIO IMPRESIO PDF FILTRO -->
             <form action="<?=base_url().$idioma?>pdf2/pdf_plan_general" id="pdfForm" target="_blank" method="post" accept-charset="utf-8">
                <input type="hidden" id="consultaImpresion" name="consultaImpresion"> 
                <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>" >
            </form>
            <form action="<?=base_url().$idioma?>excel/excel_plan_general" id="excelForm" target="_blank" method="post" accept-charset="utf-8">
                <input type="hidden" id="consultaImpresion_excel" name="consultaImpresion"> 
                <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>" >
            </form>
    </div>        
    <?php }else{ ?>
      <div class="limit_pdf_excel" style="display:none">
            <input type="submit" value="<?php echo lang('global.ver_mas')?>" style="width: 50% !important;float:left" class="button" id="cargar_mas">
            <input type="submit" value="<?php echo lang('global.ver_todos')?>" style="width: 50% !important;float:left" class="button" id="cargar_todos">
            <!-- FORMULARIO IMPRESIO PDF FILTRO -->
             <form action="<?=base_url().$idioma?>pdf2/pdf_plan_general" id="pdfForm" target="_blank" method="post" accept-charset="utf-8">
                <input type="hidden" id="consultaImpresion" name="consultaImpresion"> 
                <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>" >
            </form>
            <form action="<?=base_url().$idioma?>excel/excel_plan_general" id="excelForm" target="_blank" method="post" accept-charset="utf-8">
                <input type="hidden" id="consultaImpresion_excel" name="consultaImpresion"> 
                <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>" >
            </form>
    </div>  
    <?php } ?>
</div>

<div id="wrappOverlay">
    <!--SECCION APRA GUARDAR LAS CONSULTAS-->
    <?php echo form_open($idioma.'plan_general/guardar_consulta',' class="", id="Save"');?>
    <div class="colums three" id="save_filter">
        <span class="closeIcon"></span>
        <h3><?php echo lang('global.guardar_filtro')?></h3>
        <input type="hidden" name="idproduccion" id="idproduccion" value="<?=$produccion[0]->id_produccion?>"> 
        <input type="hidden" name="consulta" id="consulta" value=""> 
        <label><?php echo lang('global.nombre')?>:</label><input type="text" name="nombre" id="nombre_consulta" class="required">
        <a href="#" id="save_consult_button" class="button icon icon_filter"><span></span><?php echo lang('global.guardar_filtro') ?></a>
        <a href="#" id="cancel_consult_button" class="button secondary"><span></span><?php echo lang('global.cancelar') ?></a>
    </div>
    <?php echo form_close(); ?>
    <!--FIN SECCION APRA GUARDAR LAS CONSULTAS-->
</div>
<input type="hidden" id="validate_click" value="0">