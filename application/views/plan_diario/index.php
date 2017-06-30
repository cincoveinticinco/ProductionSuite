<?php $idioma = $this->lang->lang().'/'; setlocale(LC_TIME, 'es_ES.UTF-8');?>

<?php setlocale(LC_TIME, 'es_ES.UTF-8'); ?>

<div id="temporal_script">
    <script type="text/javascript">
        $(document).ready(function() {
            $('#planDiarioTableSorter').on("click",'tr.actionAsing', function(){
              $(this).toggleClass('ui-selected');
              var itemsSelected = $('#planDiarioTableSorter tr.ui-selected').size();
              if (itemsSelected == 0) {
                ocultarBotonPlanDiario();
              }else{
                mostrarBotonPlanDiario();
              };
            });

            $( "#planDiarioTableSorter tbody" ).selectable({
              distance: 1,
              selected: mostrarBotonPlanDiario
            });

            function mostrarBotonPlanDiario(){
              $('.borrarSeleccionadas').fadeIn();
              var itemsSelected = $('#planDiarioTableSorter tr.ui-selected').size();
              if(itemsSelected==0){
                 ocultarBotonPlanDiario();
              }
            }

            function ocultarBotonPlanDiario(){
              $('.borrarSeleccionadas').stop().fadeOut();
            }
        });


    </script>
</div>
<?php $id_user=$this->session->userdata('id_pruduction_suite'); ?>
<?php $tipo_user=$this->session->userdata('tipo_pruduction_suite');?>
<div id="ordenarWrap">
    <div class="ordenarBox">
        <span class="closeIcon"></span>
        <div class="table_general">
            <table class="main">
                <thead>
                    <tr>
                        <td><?php echo lang('global.ordenar_plan') ?></td>
                    </tr>
                </thead>
            </table>
            <br>
            <?php 
            if(isset($fecha_unidad) AND $fecha_unidad!=""){ ?>
            <?php if($fecha_unidad){
                $date=strftime('%d-%b-%Y',strtotime($fecha_unidad));
            }else{
                echo $escenas['0']['fecha_inicio'];
                $date=strftime('%d-%b-%Y',strtotime($escenas['0']['fecha_inicio']));
            } ?>
            <?php } else{ 
            if(isset($escenas['0']['fecha_inicio']) AND $fecha_unidad!=""){
                $date=strftime('%d-%b-%Y',strtotime($escenas['0']['fecha_inicio']));       
            }else{
                $date='';
            }
            };

            $orden=""; 
            if(isset($unidad[0]['id'])){
                    $unidad_select=$unidad[0]['id']; 
            }
            if($escenas){ ?>

            <?php echo form_open($idioma.'plan_diario/orden_columnas','id="arderFormPLanDiario"') ?>
            <input type="hidden" name="campos_columnas" id="campos_select" value="">
            <input name="fecha_plan" type="hidden" value="<?=$fecha_unidad?>">
            <input name="unidad_plan" type="hidden" value="<?=$unidad[0]['id']?>">
            <input name="idproduccion" type="hidden" value="<?=$produccion[0]->id_produccion?>">
                    <div class="row">
                        <div class="column six">
                            <h3><?php echo lang('global.campos_activos') ?></h3>
                            <ul id="itemsEnable" class="connectedSortable">
                            <?php if($campos_usuario){?>
                              <?php for ($i=0; $i < count($campos_usuario)-1; $i++) { ?>
                                <?php $class_sort=""; 
                                    if( $campos_usuario[$i]=="tiempo real" OR 
                                        $campos_usuario[$i]=="tiempo estimado" OR
                                        $campos_usuario[$i]=="comienzo ensayo" OR 
                                        $campos_usuario[$i]=="comienzo grabación" OR
                                        $campos_usuario[$i]== "fin grabación") { 
                                        $class_sort="horizontal_sort"; 
                                    } 
                                ?>
                                    <li class="<?=$class_sort?>"><?=$campos_usuario[$i]?></li>
                                <?php } ?>
                              <?php } ?>
                            </ul>
                        </div>
                        <div class="column six">
                            <h3><?php echo lang('global.campos_disponibles') ?></h3>
                            <ul id="itemsDisable" class="connectedSortable">
                              <?php for ($i=0; $i < count($campos); $i++) { 
                                    $validacion = true;
                                    for ($j=0; $j < count($campos_usuario)-1; $j++) { 
                                            if($campos_usuario[$j]==$campos[$i]){
                                                    $validacion=false;
                                            }
                                    }
                                    if($validacion){ ?>
                                        <li class=""><?=$campos[$i]?></li>
                                    <?php } ?>
                              <?php } ?>
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
            <?php } ?>
        </div>
    </div>
</div>

<div id="cruceWrap"></div>
<div id="breadcrumbs">

    <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> 
    / <a  href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> 
    / <a  href="<?php echo base_url($idioma.'plan_general/index/'.$produccion['0']->id_produccion); ?>"><?php echo lang('global.plan_general') ?></a>
    / <?php echo lang('global.plan_diario') ?> <?php if($fecha_unidad AND $unidad['0']['numero']){ echo ' / Unidad '.$unidad['0']['numero'].' - '.lang("global.fecha").' '.strftime('%d-%b-%Y',strtotime($fecha_unidad)); } ?>

   <div class="productionName">
        <?php echo $produccion['0']->nombre_produccion ?>
    </div>
</div>

<nav>
  <input type="hidden" name="id_produccion_estado" id="id_produccion_estado" value="<?php echo $produccion[0]->id_produccion ?>">
    <ul class="nav_post nav_plan_diario">
        <li><a class="buttons icon icon_plus reabir_plan"

            <?php if($escenas AND $escenas[0]['estado_plan']!='Cerrado'){
                    echo ' style="display:none;"';

            }
            if($escenas AND ($tipo_rol==1 or $tipo_rol==7) AND ($escenas[0]['estado_plan']=='Abierto Privado' OR $escenas[0]['estado_plan']=='Cerrado')){
                echo ' style="display:none;"';
            }
            ?>><span></span><?php echo lang('global.reabrir_plan') ?>
        </a></li>
        <?php if($escenas AND $escenas[0]['estado']>=2 AND ($id_user==$produccion[0]->id_productor OR $tipo_user==4 OR $tipo_user==1  OR $tipo_rol==2 OR $tipo_rol==1 OR $tipo_rol==7)){ ?>
        <li><a href="#" class="buttons icon icon_plus completar_plan"
            <?php 
            if($escenas[0]['estado_plan']=='Cerrado' || $escenas[0]['estado_plan']=="" || $escenas[0]['estado_plan']=="No Iniciado" || $usuario_permisos=="read"){ 
                echo ' style="display:none;"';
            }
            if(($tipo_rol==1 or $tipo_rol==7) AND ($escenas[0]['estado_plan']=='Abierto Privado' OR $escenas[0]['estado_plan']=='Cerrado')){
                echo ' style="display:none;"';    
            }
            ?>
            ><span></span>
            <?php switch ($escenas[0]['estado_plan']) {
                    case 'Abierto Privado':
                    echo  lang('global.publicar_plan');
                    break;
                    case 'Abierto':
                    echo  lang('global.cerrar_plan');
                    break;
                    case 'Cerrado':
                    echo  lang('global.reabrir_plan');
                    break;
                    case 'Re abierto':
                    echo  lang('global.cerrar_plan');
                    break;
            }?>
        </a></li>
        <?php if($escenas and $escenas[0]['estado_plan']!="Cerrado") { ?>
        <li><a href="#" id="ordenarPlanLink" class="buttons icon icon_sort"><span></span><?php echo lang('global.ordenar') ?></a></li>
        <?php } ?>
        <?php if($escenas[0]['estado_plan']!="Cerrado"){ ?>
        <?php if($escenas[0]['estado_plan']!="Abierto Privado" OR ($tipo_rol !=7 AND $tipo_rol !=1)){ ?>
              <?php if($produccion['0']->estado!=2){ ?>
            <li><a href="#" id="asignarEscenasLink" class="buttons icon icon_plus"><span></span><?php echo lang('global.asignar_escenas') ?></a></li>
            <?php } ?>
        <?php } ?>
        <?php } ?>

        <?php if($escenas) {?>

        <li><a href="<?=base_url($idioma.'plan_diario/cruce_elementos/'.$produccion[0]->id_produccion.'/'.strftime('%d-%b-%Y',strtotime($date)))?>" id="" target="_blank" class="buttons icon icon_filter"><span></span><?php echo lang('global.cruce_elementos') ?></a></li>

        <li><a href="<?=base_url($idioma.'excel/excel_plan_diario/'.$produccion[0]->id_produccion.'/'.$unidad[0]['id'].'/'.$fecha_unidad)?>" target="_blank" class="buttons icon icon_save"><span></span><?php echo lang('global.guardar') ?></a></li>
        <li><a href="#" class="buttons icon icon_print" id="print_pdf"><span></span><?php echo lang('global.imprimir') ?></a></li>
        <div id="blue_box" class="blue_box box_save blue_box_pdf">
            <form action="#" class="custom">
                <div class="row">
                    <div class="columns seven" id="options_pdf">
                        <label for="detalles" class="label_check"><input type="radio" class="option_pdf" name="save" value="1" id="detalles"><?php echo lang('global.plan_diario_general') ?></label>
                        <label for="sin_detalles" class="label_check"><input type="radio" class="option_pdf" name="save" value="2" id="sin_detalles">  <?php echo lang('global.plan_diario_detallado') ?></label>
                        <label for="sin_detalles" class="label_check"><input checked type="radio" class="option_pdf" value="3" name="save" id="sin_detalles"><?php echo lang('global.ambos') ?></label>
                    </div>
                    <div class="columns three">
                        <div class="call_to_action align_right">
                          <a href="#" data-idproduccion="<?=$produccion[0]->id_produccion?>" data-idunidad="<?=$unidad[0]['id']?>" data-fechaunidad="<?=$fecha_unidad?>"  id="create_pdf" class="button secondary icon icon_print"><span></span><?php echo lang('global.imprimir') ?></a>
                          </br></br>
                          <a href="#" class="button secondary icon icon_cancel cancel_"><span></span><?php echo lang('global.cancelar') ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php } ?>
        <?php } ?>
        <li><a href="#" class="buttons icon full_screen" alt="Fullscreen"><span class="open"></span><span class="close"></span><?php echo lang('global.full_screen') ?></a></li>
    </ul>
</nav>

<div id="inner_content">
        <div class="top_page">
            <div class="column six">
                <?php $arg=array('id'=>'validation_fecha','onSubmit'=>'return validation_fecha()','style'=>'width:100%;'); ?>
                <?php echo form_open($idioma.'plan_diario/unidad',$arg) ?>
                    <div class="columns twelve">
                        <div class="column four">
                            <label><?php echo lang('global.unidad') ?></label>
                            <?php $i=1; ?>
                            <select name="id_unidad" id="unidad_plan">
                                <option value=""><?php echo lang('global.seleccion_unidad') ?></option>
                                <?php foreach ($unidades as $u) { ?>
                                   <?php if(isset($unidad) AND $unidad!="") {?>
                                            <?php if($unidad['0']['id']==$u['id']){ ?>
                                                <option value="<?php echo $u['id'] ?>" selected><?php echo lang('global.unidad') ?> <?php echo $u['numero'] ?></option>
                                            <?php }else{ ?>
                                                        <option value="<?php echo $u['id'] ?>"><?php echo lang('global.unidad') ?> <?php echo $u['numero'] ?></option>
                                                <?php } ?>
                                        <?php }else{ ?>
                                                <option value="<?php echo $u['id'] ?>"><?php echo lang('global.unidad') ?> <?php echo $u['numero'] ?></option>
                                        <?php } ?>
                                        <?php $i++; ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="column four">
                           <label><?php echo lang('global.fecha') ?></label>          
                            <div class="fecha">
                                <input type="text" id="fecha_unidad_plan" placeholder="dd/mm/AAAA"  name="fecha" value="<?php echo $date ?>" class="required">
                            </div>          
                            <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                        </div>
                        <div class="column four">
                            <label>&nbsp;</label>
                            <input type="submit" id="find_plan" value="<?php echo lang('global.buscar') ?>" class="button secondary" style="width:100%;">
                        </div>  
                    </div>
                <?php echo form_close() ?>
            </div>
            <? if(isset($unidad) AND $unidad!="") { ?>
            <div class="column six unidadesPlanDiario">
                    <div class="column twelve infoPlanBox">
                        <!-- Director -->
                        <div class="item">
                            <?php if(isset($director_dia) and $director_dia ){ ?>
                               <label><?php echo lang('global.director') ?>:</label> 
                                <span class="under_text">
                                        <?php echo strtoupper($director_dia['0']->nombre.' '.$director_dia['0']->apellido); ?>
                                </span> 
                                <?php $director=$director_dia['0']->id?>
                            <?php } else { ?>
                                <label><?php echo lang('global.director') ?>:</label> 
                                <span class="under_text">
                                    <?php echo strtoupper($unidad['0']['dir']); ?>  
                                </span>
                                <?php $director=$unidad['0']['id_director']?>
                            <?php } ?>
                        </div>
                        <!-- Director -->
                        <!-- Script -->
                        <div class="item">
                            <?php if(isset($script_dia) and $script_dia) { ?>
                                <label><?php echo lang('global.script') ?>:</label> 
                                <span class="under_text">
                                    <?php echo strtoupper($script_dia['0']->nombre.' '.$script_dia['0']->apellido); ?>      
                                </span>
                                <?php $script_unidad=$script_dia['0']->id?>
                            <?php } else{ ?>
                                <label><?php echo lang('global.script') ?>:</label> 
                                <span class="under_text">
                                    <?php if($unidad['0']['scr']!=""){
                                                    echo strtoupper($unidad['0']['scr']);?>
                                    <?php }else{
                                            echo ' - ';
                                    } ?> 
                                </span>
                                <?php $script_unidad=$unidad['0']['id_script']?>
                            <?php } ?> 
                        </div>
                        <!-- Script -->
                        <!-- Llamado -->
                        <div class="item">
                            <label><?php echo lang('global.llamado') ?>:</label>
                            <span class="under_text">
                                <?php if ($escenas['0']['llamado']){ ?>
                                        <?php echo date("h:i A",strtotime($escenas['0']['llamado'])); ?>
                                <?php }else{ ?>
                                        -
                                <?php } ?>
                            </span>
                        </div>
                        <!-- Llamado -->
                        <!-- Lugar -->
                        <div class="item">
                            <label><?php echo lang('global.lugar') ?>:</label>
                            <span class="under_text">
                                <?php if ($escenas['0']['lugar']){ ?>
                                        <?php echo $escenas['0']['lugar'] ?>
                                <?php }else{ ?>
                                        -
                                <?php } ?>
                            </span>
                        </div>
                        <!-- Lugar -->
                        <!-- Wrap Time -->
                        <div class="item">
                            <label><?php echo lang('global.corte_general') ?>:</label>
                            <span class="under_text">
                                <?php if ($escenas['0']['wrap_time'] AND $escenas['0']['wrap_time'] !='00:00:00'){ ?>
                                        <?php echo date("h:i A",strtotime($escenas['0']['wrap_time'])) ?>
                                <?php }else{ ?>
                                        -
                                <?php } ?>
                            </span>
                        </div>
                        <!-- Wrap Time -->
                    </div>
            </div>  
             <?php $duracion_minutos_pro=0; $duracion_segundos_pro=0; ?>
                                        <?php $duracion_minutos_es=0; $duracion_segundos_es=0; ?>
                                        <?php $duracion_minutos_por_pro=0; $duracion_segundos_por_pro=0; $tiempo_por_prod="00:00";?>

                                        <?php $total_producidas=0; ?>
                                        <?php if($escenas){
                                            foreach ($escenas as $e) {
                                                $duracion_minutos_es=$e['duracion_estimada_minutos']+$duracion_minutos_es;
                                                $duracion_segundos_es=$e['duracion_estimada_segundos']+$duracion_segundos_es;
                                                if($e['estado_escenas']==1 OR $e['estado_escenas']==2 OR $e['estado_escenas']==3){
                                                    if($e['estado_escenas']==1 OR $e['estado_escenas']==2 
                                                        OR $e['estado_escenas']==12 OR $e['estado_escenas']==14
                                                        OR $e['estado_escenas']==3){
                                                    $this->db->where('id_escena',$e['id_escena']);
                                                    $this->db->where('fecha_produccion',$e['fecha_inicio']);
                                                    $this->db->where('unidad_produccion',$e['id_unidad']);
                                                    $query = $this->db->get('retomas_escena');
                                                    $temp_time = $query->result();
                                                    if($temp_time){
                                                    $temp_time = explode(':', $temp_time[0]->tiempo); 
                                                    $duracion_minutos_pro=$temp_time[0]+$duracion_minutos_pro;
                                                    $duracion_segundos_pro=$temp_time[1]+$duracion_segundos_pro;
                                                        $total_producidas=$total_producidas+1;
                                                    }
                                                    }
                                                    
                                                }

                                                $active=0;
                                                if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad'] AND $e['estado_escenas']==1){
                                                    $active=1;
                                                }else{
                                                    if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad']){
                                                        $active=1;
                                                    }else{
                                                        $tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
                                                        if($tiempo){
                                                         $active=1;
                                                        }else{
                                                           $active=0;
                                                        }
                                                    }
                                                }
                                                if($active==0){
                                                    $duracion_minutos_por_pro = $e['duracion_estimada_minutos']+$duracion_minutos_por_pro;
                                                    $duracion_segundos_por_pro = $e['duracion_estimada_segundos']+$duracion_segundos_por_pro;
                                                }
                                            }
                                        } ?>
            <div class="columns twelve">
                <div class="columns two">
                    <?php if($escenas[0]['estado_plan']!="Cerrado" AND $produccion['0']->estado!=2 AND $usuario_permisos!="read" AND isset($escenas[0]['estado_plan'])){?>
                        <?php if($escenas[0]['estado']>2 || $id_user==$produccion[0]->id_productor || $tipo_user==1 || $tipo_user==4){ ?>
                                <div class="button" id="editar_plan" style="width:100%;"><?php echo lang('global.editar_plan_diario') ?></div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="columns two">
                   <?php if($escenas[0]['estado_plan']!="Cerrado" AND $produccion['0']->estado!=2 AND $usuario_permisos!="read" AND isset($escenas[0]['estado_plan'])){?>
                        <?php if($escenas[0]['estado']>2 || $id_user==$produccion[0]->id_productor || $tipo_user==1 || $tipo_user==4){ ?>
                                <?php if($total_producidas==0){ ?>
                                                <div class="button" id="mover_plan" style="width:100%;"><?php echo lang('global.mover_plan') ?></div>
                                 <?php } ?>
                         <?php } ?>
                    <?php } ?>           
               </div>
                <div class="columns two">
                    <?php switch ($escenas[0]['estado_plan']) {
                        case 'No Iniciado':
                        $color_state="#d0d0d0";
                        break;
                        case 'Abierto Privado':
                        $color_state="#c1f378";
                        break;
                        case 'Abierto':
                        $color_state="#8cdd16";
                        break;
                        case 'Abierto offline':
                        $color_state="#4a4b39";
                        break;
                        case 'Cerrado':
                        $color_state="#fee93e";
                        break;
                        case 'Re abierto':
                        $color_state="#f7921e";
                        break;
                        default:
                        $color_state="#d0d0d0";
                        break;
                    } ?>
                   
                    <div class="estadoBox estadoBoxPlanDiario" style="background:<?=$color_state?>;color: #333; border: 2px outset <?=$color_state?>; width:100%;">
                         <?php echo lang('global.estado') ?>: <span class="estado_plan"><?php if($escenas[0]['estado_plan']==""){ echo "No iniciado"; }else{ echo $escenas[0]['estado_plan']; } ?></span>
                    </div>
                </div>
                <div class="columns six">
                    <?php if($ultima_edicion!=""){ ?>
                        <div class="columns twelve">
                            <div class="infoPlanBox">
                                <strong><?php echo lang('global.ultima_actualizacion') ?>:</strong> <?=strtoupper($ultima_edicion[0]->nombre)." ".strtoupper($ultima_edicion[0]->apellido)?>
                                <?php echo lang('global.el') ?>  <?=date("d/M/Y H:i:s",strtotime($ultima_edicion[0]->fecha))?>                
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="column six">
                    <table class="table_post_prod table_plan_dia" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div class="itemInfo">
                                        <?php $total_escenas_pautadas=0;
                                        if($escenas){
                                            $total_escenas_pautadas=sizeof($escenas);
                                        } ?>
                                         <p><?php echo lang('global.escenas_pautadas') ?>: 
                                        <span class="itemOrange">
                                        <?php echo $total_escenas_pautadas;?></span></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="itemInfo">
                                       
                                        <p><?php echo lang('global.escenas_producidas') ?>: 
                                        <span class="itemOrange">
                                        <?php echo $total_producidas ?></span></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="itemInfo">
                                            <?php $total_pro_producir=$total_escenas_pautadas-$total_producidas ?>
                                            <p><?php echo lang('global.escenas_por_producir') ?>:
                                            <span class="itemOrange">
                                            <?php echo $total_pro_producir ?></span></p>
                                    </div>
                                </td>
                            </tr>
                    </table>
                </div>
                <div class="column six">
                    <table class="table_post_prod table_plan_dia"  cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                            <div class="itemInfo">
                               
                                <?php
                                $tiempo=plan_diario::calculo_tiempo2($duracion_minutos_es,$duracion_segundos_es);
                                ?>
                                <p><?php echo lang('global.total_tiempo_estimado') ?>:
                                <span class="itemOrange">
                                <?php echo $tiempo ?></span></p>
                            </div>
                        </td>
                        <td>
                            <div class="itemInfo">
                                
                                <?php 
                                 $tiempo_prod=plan_diario::calculo_tiempo2($duracion_minutos_pro,$duracion_segundos_pro);
                                ?>
                                <p><?php echo lang('global.total_tiempo_producido') ?>:
                                <span class="itemOrange">
                                <?php echo $tiempo_prod ?></span></p>
                            </div>
                        </td>
                        <td>
                            <div class="itemInfo">
                                <?php $negativo=0; ?>
                                <?php if($escenas){
                                    $m = $duracion_minutos_por_pro;
                                    $s = $duracion_segundos_por_pro;
                                    
                                    if($m<0){
                                            $m=$m*-1;
                                            $negativo=1;
                                    }
                                    if($s<0){
                                            $s=$s*-1;
                                    }
                                    $tiempo_por_prod= plan_diario::calculo_tiempo2($m,$s);
                                }
                                ?>
                                <p><?php echo lang('global.minutos_por_producir') ?>:
                                <span class="itemOrange">
                                    <?php $var_temp = ($duracion_minutos_pro*60)+$duracion_segundos_pro;
                                          $var_temp2 = ($duracion_minutos_es*60)+$duracion_segundos_es;
                                    ?>
                                    <?php if($var_temp>$var_temp2){?>
                                      00:00
                                    <?php }else { ?>
                                        <?php echo $tiempo_por_prod?>
                                </span></p>
                                <?php } ?>
                            </div>
                        </td>
                      </tr>
                    </table>
                </div>
            </div>
            

            <div class="row">
                <div class="column twelve">
                    <div id="plan_diario" style="display:none" class="field_form">
                        <?php echo form_open($idioma.'plan_diario/editar_plan','id="form_edit_plan_diario"') ?>
                            <input type="hidden" class="id_plan" name="id_plan" value="<?php echo $escenas['0']['id_plan_diario'] ?>">
                            <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                            <input type="hidden" name="id_unidad" value="<?php echo $unidad['0']['id'] ?>">
                            <input type="hidden" name="director_anterior" value="<?php echo $unidad['0']['id_director'] ?>">
                            <input type="hidden" name="script_anterior" value="<?php echo $unidad['0']['id_script'] ?>">
                            
                            <div class="row rowField">
                                <div class="column three">
                                    <label><?php echo lang('global.director') ?>:</label>
                                    <br>
                                    <select name="director">
                                       <?php if($director_unidad){ ?>
                                            <?php foreach ($director_unidad as $d) { ?>
                                                    <label><?php echo lang('global.script') ?>:</label>
                                                <?php if($director==$d['id']){ ?>
                                                    <option value="<?php echo $d['id'] ?>" selected><?php echo $d['nombre'].' '.$d['apellido']?></option>
                                                <?php }else{ ?>
                                                    <option value="<?php echo $d['id'] ?>"><?php echo $d['nombre'].' '.$d['apellido']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>    
                                    </select>       
                                    <br><br>
                                </div>
                                <div class="column three">
                                    <label><?php echo lang('global.script') ?>:</label>
                                    <br>
                                    <select name="script">
                                        <option value="">Seleccione un Script</option>
                                        <?php foreach ($script as $s) { ?>
                                            <?php if($script_unidad==$s['id']){ ?>
                                                <option value="<?php echo $s['id'] ?>" selected><?php echo $s['nombre'].' '.$s['apellido']?></option>
                                            <?php }else { ?>
                                                <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'].' '.$s['apellido']?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <br><br>
                                </div>
                                <div class="column three">
                                   <label><?php echo lang('global.lugar') ?>:</label>
                                    <br>
                                    <input type="text" name="lugar" value="<?php echo $escenas['0']['lugar'] ?>" >
                                </div>
                                <div class="column three timeItem">
                                    <label><?php echo lang('global.llamado') ?>:</label>
                                    <?php
                                        $time = false;
                                        if($escenas['0']['llamado']!="" AND $escenas['0']['llamado']!="0"){
                                            $delete = array(":", " ");
                                            $time = str_replace($delete, "", date("h:i A",strtotime($escenas['0']['llamado'])));
                                            $time = str_split($time, 2);
                                        } 
                                    ?> 
                                    <div class="column three">
                                        <span>H</span>
                                        <select class="horas" id="horas_llamado" name="horas_llamado">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <?php for ($i=1; $i <= 12; $i++) { 
                                                if(strlen($i)<2){
                                                    $horas = '0'.$i;
                                                }else{
                                                    $horas = $i;
                                                }
                                            ?> 
                                                <option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                   <div class="column three">
                                       <span>M</span>
                                       <select class="minutos" id="minutos_llamado" name="minutos_llamado">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <?php for ($i=0; $i <= 59; $i++) { 
                                                if(strlen($i)<2){
                                                    $minutos = '0'.$i;
                                                }else{
                                                    $minutos = $i;
                                                }?>
                                            <option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="column six">
                                        <span>AM/PM</span>
                                        <select class="am_pm" id="am_pm_llamado" name="am_pm_llamado">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
                                            <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column three timeItem">
                                    <label><?php echo lang('global.corte_general') ?>:</label>
                                    <?php
                                        $time = false;
                                        if($escenas['0']['wrap_time']!="" AND $escenas['0']['wrap_time']!="00:00:00"){
                                                $delete = array(":", " ");
                                                                        $time = str_replace($delete, "", date("h:i A",strtotime($escenas['0']['wrap_time'])));
                                                $time = str_split($time, 2);
                                        } 
                                    ?> 
                                    <div class="column three">
                                        <span>H</span>
                                        <select class="horas" id="horas_wrap_time" name="horas_wrap_time">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <?php for ($i=1; $i <= 12; $i++) { 
                                                if(strlen($i)<2){
                                                    $horas = '0'.$i;
                                                }else{
                                                    $horas = $i;
                                                }
                                            ?> 
                                            <option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="column three">
                                       <span>M</span>
                                       <select class="minutos" id="minutos_wrap_time" name="minutos_wrap_time">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <?php for ($i=0; $i <= 59; $i++) { 
                                                if(strlen($i)<2){
                                                    $minutos = '0'.$i;
                                                }else{
                                                    $minutos = $i;
                                                }?>
                                            <option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="column six">
                                        <span>AM/PM</span>
                                        <select class="am_pm" id="am_pm_wrap_time" name="am_pm_wrap_time">
                                            <option <?php if($time!=false){ echo "selected"; }?> value="">-</option>
                                            <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
                                            <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="column twelve">
                                    <input type="button" id="edit_plan_diario" value="<?php echo lang('global.guardar') ?>" class="button">
                                    <a href="#" class="button secondary" id="cancel_editar_plan"><?php echo lang('global.cancelar') ?></a>
                                </div>
                            </div>  
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>

               <div class="column twelve">
                    <div id="plan_mover" style="display:none" class="field_form">
                       <?php $arg=array('id'=>'form_mover_plan','style'=>'width:100%;','onSubmit'=>"return validation_confirmacion()"); ?>
                        <?php echo form_open($idioma.'plan_diario/mover_plan',$arg) ?>
                            <div class="column four">
                                <label><?php echo lang('global.unidad') ?></label>
                                 <input type="hidden" class="id_plan" name="id_plan" value="<?php echo $escenas['0']['id_plan_diario'] ?>">
                                <select name="id_unidad" id="unidad_plan_mover">
                                    <option value=""><?php echo lang('global.seleccion_unidad') ?></option>
                                    <?php foreach ($unidades as $u) { ?>
                                                    <option value="<?php echo $u['id'] ?>">unidad <?php echo $u['numero'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="row rowField">
                                     <div class="column four">
                                <label><?php echo lang('global.fecha') ?></label>         
                                <div class="fecha">
                                    <input type="text" id="fecha_unidad_mover" placeholder="dd/mm/AAAA"  name="fecha" value="" class="required ">
                                </div>          
                                <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                            </div>
                             <div class="clr"></div>
                            <div class="column twelve">
                                <input type="button" id="enviar_form_mover_plan" value="<?php echo lang('global.guardar') ?>" class="button">
                                <a href="#" class="button secondary" id="cancel_mover_plan"><?php echo lang('global.cancelar') ?></a>
                            </div>        
                        <?php echo form_close() ?>
                    </div>
                </div>    
                
    
            <?php if($escenas){ 
                    $ver_plan=0;
                    if($escenas[0]['estado']==2 AND ($id_user==$produccion[0]->id_productor or $tipo_user==1 or $tipo_user==3 or $tipo_user==4  OR $tipo_rol==2)){
                        $ver_plan=1;
                    }
                    $usuario_roles = $this->model_produccion->roles_usuario_produccion($id_user,$produccion[0]->id_produccion);
                    if($usuario_roles){
                        foreach ($usuario_roles as $usuario_rol) {
                            if($usuario_rol['id_rol']==8){
                              $ver_plan=1; 
                              break;
                            }
                        }
                    }
                    if($escenas[0]['estado']>2){
                        $ver_plan=1;        
                    } 
            ?>
     
    <?php if($ver_plan==1){ ?>
    <div id="scroll" class="scrollPlanDiario">
        <div class="table_general tableLeft">
        <table class="tablesorter tabla_filtro_general" id="planDiarioTableSorter">
            <thead>
                <tr class="gray ordenar">
                      
                                <th style="padding:0 8px;" class="header"><span class="has-tip tip-centered-top" title="<?php echo lang('global.orden') ?>">#</span></th>
                      
                  
                                <th style="padding:0 8px;" class="header"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_libretos') ?>">LIB</span></th>
                
                    
                                <th style="padding:0 8px;" class="header"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escena') ?>">ESC</span></th>
                    
                   
                                <th style="padding:0 8px;" class="header"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_continuidad') ?>">CONT</span></th>
                       
                    
                                <th style="padding:0 2px;" class="header"><span class="has-tip tip-centered-top" title="<?php echo lang('global.pagina') ?>"><?php echo lang('global.pagina') ?></span></th>
                     
                    <?php if($campos_usuario){ 
                        for ($i=0; $i < count($campos_usuario)-1; $i++) { 
                            switch ($campos_usuario[$i]) {
                                case 'Descripción':
                                    echo '<th width="300" class="header"><span class="has-tip tip-centered-top" title="'.lang("global.descripcion_escena").'"><span style="width:300px; overflow:hidden; display:block">'.lang("global.descripcion").'</span></span></th>';
                                break;
                                case 'guión':
                                    echo '<th width="300" class="header"><span class="has-tip tip-centered-top" title="'.lang("global.guion_escena").'" style="width:300px; display: block;">'.lang("global.guion").'</span></th>';
                                break;
                                case 'día/noche':
                                    echo '<th width="100" class="header"><span class="has-tip tip-centered-top" title="'.lang("global.dia_noche").'" style="width:90px; display: block;">'.lang("global.dia_noche").'</span></th>';
                                break;
                                case 'int/ext':
                                    echo '<th width="100" class="header"><span class="has-tip tip-centered-top" title="'.lang("global.int_ext").'" style="width:80px; display: block;">INT/EXT</span></th>';
                                break;
                                case 'loc/est':
                                    echo '<th width="100" class="header"><span class="has-tip tip-centered-top" title="'.lang("global.loc_est").'" style="width:80px; display: block;">LOC/EST</span></th>';
                                break;
                                case 'locación':
                                    echo '<th width="10%" class="header"><span class="has-tip tip-centered-top"  style="width:220px; display: block;" title="'.lang("global.locacion").'">'.lang("global.locacion").'</span></th>';
                                break;
                                case 'set':
                                    echo '<th width="10%" class="header"><span class="has-tip tip-centered-top"  style="width:220px; display: block;" title="'.lang("global.set").'">'.lang("global.set").'</span></th>';
                                break;
                                case 'personajes principales':
                                    echo '<th width="50%" class="header"><span class="has-tip tip-centered-top"  style="width:180px;  display: block;" title="'.lang("global.personajes_principales").'">'.lang("global.personajes_principales").'</span></th>';
                                break;
                                case 'personajes secundarios':
                                    echo '<th width="50%" class="header"><span class="has-tip tip-centered-top"  style="width:180px;  display: block;" title="'.lang("global.personajes_secundarios").'">'.lang("global.personajes_secundarios").'</span></th>';
                                break;
                                case 'elementos':
                                    echo '<th width="50%" class="header"><span class="has-tip tip-centered-top"  style="width:230px;  display: block;" title="'.lang("global.elementos").'">'.lang("global.elementos").'</span></th>';
                                break;
                                case 'toma':
                                    echo '<th width="40%" class="header"><span class="has-tip tip-centered-top"  style="width:50px;  display: block;" title="'.lang("global.toma").'">'.lang("global.toma").'</span></th>';
                                break;
                                case 'tiempo estimado':
                                    echo '<th width="20%" class="header"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="'.lang("global.tiempo_estimado").'">'.lang("global.tiempo").' EST.</span></th>';
                                break;
                                case 'tiempo real':
                                    echo '<th width="20%" class="header"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="'.lang("global.tiempo_real").'">'.lang("global.tiempo_real").'</span></th>';
                                break;
                                case 'comienzo ensayo':
                                    echo  '<th width="20%" class="header"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="'.lang("global.comienzo_ensayo").'">COM. '.lang("global.ensayo").'</span></th>';
                                break;
                                case 'comienzo grabación':
                                    echo  '<th width="20%" class="header"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="'.lang("global.comienzo_grabacion").'">COM. GRAB.</span></th>';
                                break;
                                case 'fin grabación':
                                    echo  '<th width="20%" class="header"><span class="has-tip tip-centered-top" style="width:90px; display: block;" title="'.lang("global.fin_grabacion").'">FIN GRAB.</span></th>';
                                break;
                            }
                        }
                    }else{?>
                      <th width="300">
                        <span class="has-tip tip-centered-top" title="<?php echo lang("global.descripcion_escena") ?>"><span style="width:300px; overflow:hidden; display:block"><?php echo lang("global.descripcion") ?></span></span></th>
                      <th width="300">
                        <span class="has-tip tip-centered-top" title="<?php echo lang("global.guion_escena") ?>" style="width:300px; display: block;"><?php echo lang("global.guion") ?></span>
                      </th>
                      <th width="100">
                        <span class="has-tip tip-centered-top" title="<?php echo lang("global.dia_noche") ?>" style="width:80px; display: block;"><?php echo lang("global.dia_noche") ?></span>
                      </th>
                      <th width="100">
                        <span class="has-tip tip-centered-top" title="<?php echo lang("global.int_ext") ?>" style="width:80px; display: block;">INT/EXT</span>
                      </th>
                      <th width="100">
                        <span class="has-tip tip-centered-top" title="<?php echo lang("global.loc_est") ?>" style="width:80px; display: block;">LOC/ESt</span>
                      </th>
                      <th width="10%">
                        <span class="has-tip tip-centered-top"  style="width:220px; display: block;" title="<?php echo lang("global.locacion") ?>"><?php echo lang("global.locacion") ?></span>
                      </th>
                      <th width="10%">
                        <span class="has-tip tip-centered-top"  style="width:220px; display: block;" title="<?php echo lang("global.set") ?>"><?php echo lang("global.locacion") ?></span>
                      </th>
                      <th width="50%">
                        <span class="has-tip tip-centered-top"  style="width:180px;  display: block;" title="<?php echo lang("global.personajes_principales") ?>"><?php echo lang("global.personajes_principales") ?></span>
                      </th>
                      <th width="50%">
                        <span class="has-tip tip-centered-top"  style="width:180px;  display: block;" title="<?php echo lang("global.personajes_secundarios") ?>"><?php echo lang("global.personajes_secundarios") ?></span>
                      </th>
                      <th width="50%">
                        <span class="has-tip tip-centered-top"  style="width:230px;  display: block;" title="<?php echo lang("global.elementos") ?>"><?php echo lang("global.elementos") ?></span>
                      </th>
                      <th width="40%">
                        <span class="has-tip tip-centered-top"  style="width:50px;  display: block;" title="<?php echo lang("global.toma") ?>"><?php echo lang("global.elementos") ?></span>
                      </th>
                      <th width="20%">
                        <span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang("global.tiempo_estimado") ?>"><?php echo lang("global.tiempo") ?> EST.</span>
                      </th>
                      <th width="20%">
                        <span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang("global.tiempo_real") ?>"><?php echo lang("global.tiempo_real") ?></span>
                      </th>
                      <th width="20%" class="header">
                        <span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang("global.comienzo_ensayo") ?>">COM. <?php echo lang("global.ensayo") ?></span>
                      </th>
                      <th width="20%" class="header">
                        <span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang("global.comienzo_grabacion") ?>">COM. PROD.</span>
                      </th>
                      <th width="20%" class="header">
                        <span class="has-tip tip-centered-top" style="width:90px; display: block;" title="<?php echo lang("global.fin_grabacion") ?>"><?php echo lang("global.fin") ?> PROD.</span>
                      </th>
                    <?php } ?>
                  
                              <th style="padding:0 6px;"><span class="has-tip tip-centered-top" style="width:100px;" title="<?php echo lang('global.comentarios') ?>"><?php echo lang('global.comentarios') ?></span></th>
                       
                   
                              <th style="padding:0 8px;"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acciones') ?>"><?php echo lang('global.acciones') ?></span></th>
                    
                </tr>
             </thead>
                <script type="text/javascript">
                $(document).ready(function() {
                     $('.fancybox').fancybox({
                        padding: 20,

                        openEffect : 'fade',

                        closeEffect : 'fade',
                      });
                });

                </script>
                    <!--INICIO FILAS-->
                     <tbody>   
                    <?php  foreach ($escenas as $e) { 

                        $m=0; ?>
                    <?php 
                        if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad'] AND $e['estado_escenas']==1){
                            $class_tr="td_yellow";
                        }else{
                            if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad']){
                                $class_tr="td_yellow";
                            }else{
                                $tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
                                if($tiempo){
                                 $class_tr="td_yellow";
                                }else{
                                    $class_tr="actionAsing";
                                }
                            }
                        } 
                    ?>

                 
                <tr class="order_row <?=$class_tr?>" data-idescena="<?=$e['id_escena']?>">
                    
                                <td><?php echo $e['orden'];?></td>
                     
                    <td><?php echo $e['numero'] ?></td>
                    <td>
                        <a href="<?php 
                            if($e['estado_escenas']!=1){ 
                                    echo base_url().$idioma.'plan_diario/escena_detalle/'.$e['id_escena'].'/'.$produccion['0']->id_produccion; 
                            }else{ 
                                    echo '#';
                            }?>" 
                            <?php if($e['estado_escenas']!=1){ ?>target="_blank" <?php } ?>>
                            <?php echo $e['numero_escena'] ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $e['dias_continuidad'] ?>
                    </td>
                    <td>
                        <?php echo $e['libreto'] ?>
                    </td>
                    <?php $class="";
                    if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado' AND $usuario_permisos!="read"){
                        $class= "open_box open_boxWrap";
                    }else{
                        $class="box";
                    } 
                    ?>

                    <!-- INICIO DE WHILE DE CAMPOS USUARIO -->
                    <?php while ( $m < count($campos_usuario)-1) { ?>
                        <!-- CAMPO DE DESCRIPCION -->
                        <?php if(isset($e['descripcion_escena']) AND $campos_usuario[$m]=="Descripción"){ ++$m;?>
                            <td width="300">
                                <span style="font-weight: normal;width:300px;color:#333;overflow:hidden; display:block; margin:0 auto;"  class="has-tip cell_align_left tooltip_info" title="<?php echo $e['descripcion_escena'];?>">                                  
                                    <?php
                                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES DESCRIPCION*/
                                    echo utf8_decode(Plan_diario::corta_palabra(utf8_encode($e['descripcion_escena']),45));
                                    if(strlen($e['descripcion_escena'])>=45){
                                        echo '...';
                                    }
                                    ?>
                                </span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO DE GUION -->
                        <?php if(isset($e['guion_escena']) AND $campos_usuario[$m]=="guión"){ ++$m;?>
                            <td width="300">
                                <span style="font-weight: normal;width:300px;color:#333;overflow:hidden; display:block; margin:0 auto;" class="cell_align_left" >                                 
                                    <a class="fancybox" href="#inline<?= $e['orden']?>">
                                    <?php
                                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES GUION*/
                                    echo Plan_diario::corta_palabra($e['guion_escena'],45);
                                    if(strlen($e['guion_escena'])>=45){
                                        echo '...';
                                    }
                                    ?>
                                    </a>
                                    <div id="inline<?= $e['orden']?>" style="width:800px;display: none; text-align:justify;">
                                        <textarea style="width:780px; height:500px; background:transparent; padding: 0 20px 0 0; border:none; box-shadow:none; outline:none"> <?=$e['guion_escena']?></textarea>
                                    </div>

                                </span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO DIA - NOCHE -->
                        <?php if(isset($e['des_dia']) AND $campos_usuario[$m]=="día/noche"){ ++$m;?>
                            <td width="100">
                                <span style="width:70px; overflow:hidden; display:block; margin:0 auto;">
                                    <?php echo $e['des_dia'] ?>
                                </span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO DE TOMA -->
                        <?php if(isset($e['retomas']) AND $campos_usuario[$m]=="toma"){ ++$m;?>
                            <td>
                                <?php 
                                if($e['retomas']==0 AND ($e['estado_escenas']==1 OR $e['estado_escenas']==13)){
                                    echo 1;
                                }else{
                                    if($e['retomas']!=0){
                                        echo $e['retomas'];
                                    }else{
                                        echo 0;
                                    }
                                } ?>
                            </td>
                        <?php  } ?>
                        <!-- CAMPO INTERIOR - EXTERIOR -->
                        <?php if(isset($e['des_int']) AND $campos_usuario[$m]=="int/ext"){ ++$m;?>
                            <td width="100">
                                <span style="width:70px; overflow:hidden; display:block; margin:0 auto;"><?php echo $e['des_int'] ?></span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO LOCACION - ESTUDIO -->
                        <?php if(isset($e['tipo']) AND $campos_usuario[$m]=="loc/est"){ ++$m;?>
                            <td width="100">
                                <span style="width:70px; overflow:hidden; display:block; margin:0 auto;"><?php echo $e['tipo'] ?></span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO LOCACION -->
                        <?php if(isset($e['nom_locacion']) AND $campos_usuario[$m]=="locación"){ ++$m;?>
                            <td>
                                <span style="width:220px; overflow:hidden; display:block; margin:0 auto;" class="cell_align_left"><?php echo $e['nom_locacion'] ?></span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO SET -->
                        <?php if(isset($e['nom_set']) AND $campos_usuario[$m]=="set"){ ++$m;?>
                            <td>
                                <span style="width:220px; overflow:hidden; display:block; margin:0 auto;" class="cell_align_left"><?php echo $e['nom_set'] ?></span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO PERSONAJES PRINCIPALES -->
                        <?php if($campos_usuario[$m]=="personajes principales"){ ++$m; ?>
                            <td>
                               <?php $cadena_personajes="";
                                if($e['personajes_principales']){ ?> 
                                        <span style="font-weight: normal;color:#333; /*width:400px;*/  overflow:hidden; display:block; text-align:left; padding:0 3px; margin:0 auto;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $e['personajes_principales'];?>">
                                    <?php
                                        /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                                    echo Plan_diario::corta_palabra($e['personajes_principales'],30);
                                    if(strlen($e['personajes_principales'])>=30  ){
                                        echo '...';
                                    };
                                }else{ ?>
                                    <span style="color:#333; /*width:400px;*/  overflow:hidden; display:block; margin:0 auto; text-align:left;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $cadena_personajes;?>">
                                <?php } ?>
                                    </span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO PERSONAJES SECUNDARIO -->
                        <?php if($campos_usuario[$m]=="personajes secundarios"){ ++$m; ?>
                        <td>
                            <?php
                            if($e['personajes_secundarios']){ ?>
                                <span style="font-weight: normal;color:#333; /*width:400px;*/  overflow:hidden; display:block; text-align:left; padding:0 3px; margin:0 auto;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $e['personajes_secundarios'];?>">
                            <?php
                                /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                            echo Plan_diario::corta_palabra($e['personajes_secundarios'],30);
                            if(strlen($e['personajes_secundarios'])>=30  ){
                                echo '...';
                            };
                            }else{ ?>
                                <span style="color:#333; /*width:400px;*/  overflow:hidden; display:block; margin:0 auto; text-align:left;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $e['personajes_secundarios'];?>">
                            <?php } ?>
                                </span>
                        </td>
                        <?php } ?>
                        <!-- CAMPO ELEMENTOS -->
                        <?php if($campos_usuario[$m]=="elementos"){ ++$m;
                            $personajes=$this->model_plan_diario->elementos_dif_personajes($e['id_escena']); ?>
                            <td width="90">
                                <?php if($personajes){
                                    $cadena_elementos=""; 
                                    foreach ($personajes as $p) { ?>
                                        <?php $cadena_elementos.= $p['nombre'].',' ?>
                                    <?php } ?>
                                    <span style="font-weight: normal;color:#333; text-align:left; float:left; padding:0px 3px;" class="has-tip tip-centered-top tooltip_info" title="<?php echo $cadena_elementos;?>">
                                <?php
                                    /*VALIDACIÓN PARA EL MAXIMO DE CARACTERES PERSONAJES*/
                                    echo Plan_diario::corta_palabra($cadena_elementos,30);
                                    if(strlen($cadena_elementos)>=30  ){
                                        echo '...';
                                    };
                                }   
                            ?>
                                </span>
                            </td>
                        <?php } ?>
                        <!-- CAMPO TIEMPO ESTIMADO -->
                        <?php if($campos_usuario[$m]=="tiempo estimado"){ ++$m; ?>
                            <td>
                                <span style="width:90px; overflow:hidden; display: block; margin:0 auto;">
                                    <?php if(strlen($e['duracion_estimada_minutos'])<2){
                                        echo '0';
                                        } 
                                        echo $e['duracion_estimada_minutos'].':'; 
                                        if(strlen($e['duracion_estimada_segundos'])<2){
                                            echo '0';
                                        } 
                                        echo $e['duracion_estimada_segundos']; 
                                    ?>
                                </span>
                            </td>
                        <?php  } ?>
                        <!-- CAMPO TIEMPO REAL -->
                        <?php if($campos_usuario[$m]=="tiempo real"){ ++$m; ?>
                            <td><?php if($produccion['0']->estado!=2){ ?>
                                <a href="#"  id="new_date_1<?php echo $e['id_escena'] ?>" class="real_time <?=$class?>" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
                               <?php } ?> 
                                    <?php $minutos = '00';
                                    $segundos = '00';
                                    if($e['duracion_real_minutos'] AND ($e['duracion_real_minutos']!='00' OR $e['duracion_real_segundos']!='00') ){ 
                                        $tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
                                        if($tiempo){
                                            
                                            $temporal = explode(':', $tiempo[0]->tiempo);
                                            $minutos = intval($temporal[0]);
                                            if(strlen($minutos)<2){
                                                $minutos = "0".$minutos;
                                            }
                                            $segundos = $temporal[1];
                                            echo $minutos.':'.$segundos;
                                        }else{
                                            if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado') { 
                                               if($produccion['0']->estado!=2){ 
                                                echo lang('global.asignar');
                                               }else{
                                                  echo ' - ';    
                                               } 
                                            }else{
                                             echo ' - ';
                                            }
                                        }
                                    }else{ ?>
                                        <span>
                                        <?php 
                                        if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado') { 
                                                if($produccion['0']->estado!=2){ 
                                                echo lang('global.asignar');
                                               }else{
                                                  echo ' - ';    
                                               } 
                                        }else{
                                                echo ' - ';
                                        }?>
                                        </span>
                                    <?php } ?>
                                </a>
                                <div class="hide_box" style="width: 200px;">
                                    <span class="close_box"></span>
                                        <div class="row">
                                            <div class="column five">
                                                <input type="text" class="value" value="<?php echo $minutos; ?>">
                                            </div>
                                            <div class="column" style="padding: 0;">
                                                :
                                            </div>
                                            <div class="column five" style="float:left;">
                                                <input type="text" class="value2" maxlength="2" value="<?php echo $segundos; ?>">
                                            </div>
                                        </div>
                                    <input type="hidden" class="tipo" value="1">
                                    <input type="hidden" class="id_plan" value="<?php echo $e['id_escena'] ?>">
                                    <input type="hidden" class="id_plan_actual" value="<?php echo $e['id_plan_diario'] ?>">
                                    <input type="hidden" class="id_plan_escena_unidad" value="<?php echo $e['id_plan_escena_unidad'] ?>">
                                    <input type="hidden" id="idproduccion" class="id_produccion" value="<?php echo $produccion[0]->id_produccion;?>">
                                    <input type="hidden" class="estado" value="<?php echo $e['estado_escenas']?>">
                                    <div class="align_left">
                                        <button>
                                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                                        </button>
                                        </button>
                                        <button type="submit">
                                          <a class="save_plan_diario save_icon2"><?php echo lang('global.guardar') ?></a>
                                        </button>
                                    </div>        
                              </div>
                            </td> 
                        <?php } ?>   
                    
                                <?php if($campos_usuario[$m]=="comienzo ensayo"){ ++$m; ?>
                                <td> 
                                <?php if($produccion['0']->estado!=2){  ?>
                                    <a href="#" id="new_date_2<?php echo $e['id_plan'] ?>" class="<?=$class?>"style="width:90px; overflow:hidden; display:block; margin:0 auto;">
                                    <?php } ?>
                                    <?php if($e['comienzo_ens']){ echo date("h:i A",strtotime($e['comienzo_ens']));}else{?>
                                    
                                        <span>
                                        <?php 
                                            if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado') { 
                                               if($produccion['0']->estado!=2){ 
                                                    echo lang('global.asignar');
                                                }else{
                                                    echo ' - ';
                                                }
                                            }else{
                                                    echo ' - ';
                                            }?>
                                        </span>
                                        <?php } ?>
                                    </a>
                                    <div class="hide_box" style="width: 350px;">
                                        <span class="close_box"></span>
                                            <?php
                                                $time = false;
                                                if($e['comienzo_ens']!=""){
                                                    $delete = array(":", " ");
                                                    $time = str_replace($delete, "", date("h:i A",strtotime($e['comienzo_ens'])));
                                                    $time = str_split($time, 2);
                                                } 
                                            ?> 
                                        <div class="row selectHour">
                                            <div class="column three">
                                                <span>H</span>
                                                    <select class="horas" name="horas">
                                                            <?php for ($i=1; $i <= 12; $i++) { 
                                                                    if(strlen($i)<2){
                                                                            $horas = '0'.$i;
                                                                    }else{
                                                                            $horas = $i;
                                                                    }
                                                            ?> 
                                                            <option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
                                                            <?php } ?>
                                                    </select>
                                            </div>
                                           <div class="column three">
                                               <span>M</span>
                                               <select class="minutos" name="minutos">
                                                        <?php for ($i=0; $i <= 59; $i++) { 
                                                            if(strlen($i)<2){
                                                                    $minutos = '0'.$i;
                                                            }else{
                                                                    $minutos = $i;
                                                            }?>
                                                            <option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                            <div class="column six">
                                                <span>AM/PM</span>
                                                <select class="am_pm" name="am_pm">
                                                    <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
                                                    <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" class="tipo" value="2">
                                        <input type="hidden" class="id_plan" value="<?php echo $e['id_plan_escenas'] ?>">
                                        <div class="align_left">
                                            <button>
                                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                                            </button>
                                            <button type="submit">
                                              <a class="save_icon2 save_plan_diario"><?php echo lang('global.guardar') ?></a>
                                            </button>
                                        </div>                
                                    </div>
                                </td> 
                                <?php } ?>

                                <?php if($campos_usuario[$m]=="comienzo grabación"){ ++$m; ?>
                                <td> 
                                <?php if($produccion['0']->estado!=2){  ?>
                                <a href="#" id="new_date_3<?php echo $e['id_plan'] ?>" class="<?=$class?>" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
                                <?php } ?>
                                    
                                        <?php if($e['comienzo_prod']){ echo date("h:i A",strtotime($e['comienzo_prod']));}else{?>
                                            <span>                                                                  
                                                <?php 
                                                    if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado') { 
                                                        if($produccion['0']->estado!=2){
                                                           echo lang('global.asignar');
                                                        }else{
                                                            echo lang('global.asignar');
                                                        }
                                                        
                                                    }else{
                                                        echo ' - ';
                                                    }
                                                ?>
                                            </span>
                                        <?php } ?>
                                    </a>
                                    <div class="hide_box" style="width: 350px;">
                                        <span class="close_box"></span>
                                        <?php
                                            $time = false;
                                            if($e['comienzo_prod']!=""){
                                                $delete = array(":", " ");
                                                $time = str_replace($delete, "", date("h:i A",strtotime($e['comienzo_ens'])));
                                                $time = str_split($time, 2);
                                            } 
                                        ?> 
                                        <div class="row selectHour">
                                            <div class="column three">
                                                <span>H</span>
                                                <select class="horas" name="horas">
                                                        <?php for ($i=1; $i <= 12; $i++) { 
                                                                if(strlen($i)<2){
                                                                        $horas = '0'.$i;
                                                                }else{
                                                                        $horas = $i;
                                                                }
                                                        ?> 
                                                        <option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                            <div class="column three">
                                                <span>M</span>
                                                <select class="minutos" name="minutos">
                                                    <?php for ($i=0; $i <= 59; $i++) { 
                                                            if(strlen($i)<2){
                                                                $minutos = '0'.$i;
                                                            }else{
                                                                $minutos = $i;
                                                            }?>
                                                            <option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="column six">
                                                <span>AM/PM</span>
                                                <select class="am_pm" name="am_pm">
                                                    <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
                                                    <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" class="tipo" value="3">
                                        <input type="hidden" class="id_plan" value="<?php echo $e['id_plan_escenas'] ?>">
                                        <div class="align_left">
                                            <button>
                                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                                            </button>
                                            <button type="submit">
                                              <a class="save_icon2 save_plan_diario"><?php echo lang('global.guardar') ?></a>
                                            </button>
                                        </div>                
                                    </div>
                                </td>   
                                <?php } ?>

                                <?php if($campos_usuario[$m]=="fin grabación"){ ++$m; ?>
                                <td> 
                                <?php if($produccion['0']->estado!=2){ ?>
                                    <a href="#" id="new_date_4<?php echo $e['id_plan'] ?>" class="<?=$class?>" style="width:90px; overflow:hidden; display:block; margin:0 auto;">
                                    <?php } ?>
                                        <?php if($e['fin_produccion']){ 
                                            //echo $e['fin_produccion'];
                                            echo date("h:i A",strtotime($e['fin_produccion']));
                                        }else{ ?>
                                            <span>
                                                <?php if($e['estado_plan']!='Cerrado' AND $e['estado_plan']!='Abierto Privado') { 
                                                        if($produccion['0']->estado!=2){
                                                        echo lang('global.asignar');
                                                        }else{
                                                        echo ' - ';
                                                        }
                                                    }else{
                                                        echo ' - ';
                                                    }
                                                ?>
                                            </span>
                                        <?php } ?>
                                    </a>
                                    <div class="hide_box" style="width: 350px;">
                                        <span class="close_box"></span>
                                            <?php $time = false;
                                                if($e['fin_produccion']!=""){
                                                        $delete = array(":", " ");
                                                                                $time = str_replace($delete, "", date("h:i A",strtotime($e['comienzo_ens'])));
                                                        $time = str_split($time, 2);
                                                } 
                                            ?> 
                                        <div class="row selectHour">
                                            <div class="column three">
                                                <span>H</span>
                                                <select class="horas" name="horas">
                                                    <?php for ($i=1; $i <= 12; $i++) { 
                                                        if(strlen($i)<2){
                                                                $horas = '0'.$i;
                                                        }else{
                                                                $horas = $i;
                                                        }
                                                    ?> 
                                                    <option <?php if($time AND $horas==$time[0]){ echo "selected"; }?> value="<?=$horas?>"><?=$i?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="column three">
                                                   <span>M</span>
                                                   <select class="minutos" name="minutos">
                                                            <?php for ($i=0; $i <= 59; $i++) { 
                                                                    if(strlen($i)<2){
                                                                            $minutos = '0'.$i;
                                                                    }else{
                                                                            $minutos = $i;
                                                                    }?>
                                                                    <option <?php if($time AND $minutos==$time[1]){ echo "selected"; }?> value="<?=$minutos?>"><?=$minutos?></option>
                                                            <?php } ?>
                                                    </select>
                                            </div>
                                            <div class="column six">
                                                    <span>AM/PM</span>
                                                    <select class="am_pm" name="am_pm">
                                                        <option <?php if($time AND 'AM'==$time[2]){ echo "selected"; }?> value="AM">AM</option>
                                                        <option <?php if($time AND 'PM'==$time[2]){ echo "selected"; }?> value="PM">PM</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <input type="hidden" class="tipo" value="4">
                                        <input type="hidden" class="id_plan" value="<?php echo $e['id_plan_escenas'] ?>">
                                        <div class="align_left">
                                            <button>
                                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                                            </button>
                                            <button type="submit">
                                              <a class="save_icon2 save_plan_diario"><?php echo lang('global.guardar') ?></a>
                                            </button>
                                        </div>                
                                    </div>
                                </td>  
                                <?php } ?>        

                    <?php } ?>
                    <!-- FIN DE WHILE DE CAMPOS USUARIO -->
                       
                                <?php $stilo="";if($e['comentarios']){
                                        $stilo="text-align:left; padding:0 3px;";
                                } ?>
                                <td width="120" style="<?=$stilo?>"> 
                                    <a  href="#" id="new_date_5<?php echo $e['id_plan'] ?>" class="<?=$class?>">
                                        <?php if($e['comentarios']){ ?> 
                                        <span class="has-tip tip-centered-top" style="color: #0098d2;font-weight: normal;" title="<?=$e['comentarios']?>">
                                        <?php echo Plan_diario::corta_palabra($e['comentarios'],20);
                                        if(strlen($e['comentarios'])>=20){
                                                echo '...';
                                        } ?>
                                        </span>
                                        <?php }else{?>
                                        <?php if($produccion['0']->estado!=2){ ?>
                                            <span>Crear</span>
                                        <?php } ?>    
                                        <?php } ?>
                                    </a>
                                    <div class="hide_box" style="left: -232px;">
                                        <span class="close_box"></span>
                                        <textarea name="valor" rows="2"  class="value"  style="min-height: 50px;"><?php echo $e['comentarios'] ?></textarea>
                                        <input type="hidden" class="tipo" value="5">
                                        <input type="hidden" class="id_plan" value="<?php echo $e['id_plan_escenas'] ?>">
                                        <div class="align_left">
                                           <?php if($produccion['0']->estado!=2){ ?>
                                            <button>
                                              <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                                            </button>
                                            <button type="submit">
                                              <a class="save_plan_diario save_icon2"><?php echo lang('global.guardar') ?></a>
                                            </button>
                                            <?php } ?>
                                        </div>            
                                    </div>
                          
                    </td>
                    <!-- COLUMNA DE ACCIONES -->
                    <td>
                     
                                    <span>
                                        <?php   $this->db->where('id_escena',$e['id_escena']);
                                                $this->db->where('fecha_produccion',$e['fecha_inicio']);
                                                $this->db->where('unidad_produccion',$e['id_unidad']);
                                                $query = $this->db->get('retomas_escena');
                                                $temp_time_2 = $query->result();
                                        ?>
                                        <?php if($e['fecha_produccion'] == $e['fecha_inicio'] AND $e['unidad_produccion'] == $e['id_unidad'] OR $temp_time_2){
                                                if($escenas[0]['estado_plan']!="Cerrado" AND $usuario_permisos!="read" AND ($e['estado_escenas']=='1' OR $e['estado_escenas']=='2' OR $e['estado_escenas']=='12' OR $e['estado_escenas']=='14')){ 
                                                    $this->db->where('id_escena',$e['id_escena']);
                                                    $this->db->where('fecha_produccion',$e['fecha_inicio']);
                                                    $this->db->where('unidad_produccion',$e['id_unidad']);
                                                    $query = $this->db->get('retomas_escena');
                                                    $temp_time = $query->result();
                                                    if($produccion['0']->estado!=2){
                                                        if($temp_time){

                                                            echo anchor($idioma.'plan_diario/desproducir_escena/'.$e['id_escena'].'/'.strftime('%d-%b-%Y',strtotime($date)).'/'.$unidad[0]['id'].'/'.$produccion['0']->id_produccion.'/'.$temp_time[0]->id,lang('global.desproducir'));
                                                        }else{
                                                            echo anchor($idioma.'plan_diario/desproducir_escena/'.$e['id_escena'].'/'.strftime('%d-%b-%Y',strtotime($date)).'/'.$unidad[0]['id'].'/'.$produccion['0']->id_produccion,lang('global.desproducir'));

                                                       }
                                                    }    
                                            }else{
                                                echo ' - ';
                                            } 
                                        } else{ ?>
                                         <?php if($produccion['0']->estado!=2){ ?>
                                            <a href="#" data-idescena="<?=$e['id_escena']?>" data-numeroescena="<?=$e['numero_escena']?>" data-numerolibreto="<?=$e['numero']?>" data-idplan="<?=$e['id_plan_diario']?>"
                                            <?php 
                                                if($escenas[0]['estado_plan']!="Cerrado" AND $usuario_permisos!="read"){  
                                                        echo 'class="delete_scene_plan">'.lang('global.eliminar');
                                                }else{
                                                        echo '>-';
                                                } 
                                            ?>
                                            </a>
                                          <?php } ?>  
                                        <?php } ?>
                                    </span>
                       
                    </td>
                    <!-- FIN COLUMNA DE ACCIONES -->
                    <?php $orden.=$e['id_escena'].','; ?>
                </tr>
                <?php } ?>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

    <?php if($escenas){ ?>
        <!-- FORMULARIO DE ORDENAMIENTO FILAS -->
        <?php echo form_open($idioma.'plan_diario/ordenar_escenas',' class="", id="order_plan", name="order_plan"');?>
            <input name="orden" type="hidden" id="orden_filas" value="<?=$orden?>">
            <input name="fecha_plan" type="hidden" value="<?=$fecha_unidad?>">
            <input name="unidad_plan" type="hidden" value="<?=$unidad[0]['id']?>">
            <input name="idproduccion" type="hidden" value="<?=$produccion[0]->id_produccion?>">
            <?php if($escenas[0]['estado']>1){ ?>
                <?php if($id_user==$produccion[0]->id_productor || $tipo_user==1 || $tipo_user==4 || $tipo_rol==2){ ?>

                    <?php if($escenas[0]['estado_plan']!="Cerrado"){ ?>
                        <?php $estilo_b =""; 
                        if($escenas[0]['estado_plan']=="Abierto Privado" AND ($tipo_rol ==7 OR $tipo_rol ==1)){ 
                            $estilo_b = "display:none";
                        } ?>
                            <a href="#" style="<?=$estilo_b?>" class="ordenarEsceneas button"><?php echo lang('global.ordenar_escenas') ?></a>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <a href="#" class="cancelOrdenEsceneas button secondary" style="display:none"><?php echo lang('global.cancelar') ?></a>
            <input type="submit" value="Guardar Orden Filas" class="button" id="order_button" style="display:none">
            <?php if($produccion['0']->estado!=2){ ?>
            <a href="#" style="display:none" class="borrarSeleccionadas button"><?php echo lang('global.eliminar_escenas') ?></a>
            <?php } ?>
        <?php echo form_close() ?>
        <!-- FIN FORMULARIO DE ORDENAMIENTO FILAS -->
    <?php } ?>
        
    <?php if($escenas){ ?>

        <?php if($id_user==$produccion[0]->id_productor || $tipo_user==1 || $tipo_user==4 || $tipo_rol==2 || $tipo_rol==1){ ?>
                <?php echo form_open($idioma.'plan_diario/editar_plan') ?>
                    <input type="hidden" class="id_plan" name="id_plan" value="<?php echo $escenas['0']['id_plan_diario'] ?>">
                    <input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                    <input type="hidden" id="id_unidad_actual" name="id_unidad" value="<?php echo $unidad['0']['id'] ?>">
                    <input type="hidden" name="director_anterior" value="<?php echo $unidad['0']['id_director'] ?>">
                    <input type="hidden" name="script_anterior" value="<?php echo $unidad['0']['id_script'] ?>">
                    <div class="row">
                        <div class="column three" style="display:none;">
                            <label><?php echo lang('global.director') ?>:</label>
                            <select name="director">
                               <?php foreach ($director_unidad as $d) { ?>
                                <?php if($director==$d['id']){ ?>
                                 <option value="<?php echo $d['id'] ?>" selected><?php echo $d['nombre'].' '.$d['apellido']?></option>
                                <?php }else{ ?>
                                     <option value="<?php echo $d['id'] ?>"><?php echo $d['nombre'].' '.$d['apellido']?></option>
                                <?php } ?>
                               <?php } ?>
                            </select>       
                        </div>
                        <div class="column three" style="display:none;">
                            <select name="script">
                               <?php foreach ($script as $s) { ?>
                                <?php if($script_unidad==$s['id']){ ?>
                                     <option value="<?php echo $s['id'] ?>" selected><?php echo $s['nombre'].' '.$s['apellido']?></option>
                                <?php }else { ?>
                                  <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'].' '.$s['apellido']?></option>
                                <?php } ?>
                               <?php } ?>
                            </select>
                        </div>
                        <input style="display:none;" type="time" name="llamado" value="<?php echo $escenas['0']['llamado'] ?>" >
                        <input style="display:none;" type="text" name="lugar" value="<?php echo $escenas['0']['lugar'] ?>" >
                        <input style="display:none;" type="time" name="wrap_time" value="<?php if($escenas['0']['wrap_time'] AND $escenas['0']['wrap_time']!='00:00:00'){ echo $escenas['0']['wrap_time']; }?>" >
                        <div class="clr"></div>
                        <div class="column twelve">
                            <br><br>
                            <label><?php echo lang('global.comentario') ?></label>
                            <textarea name="comentario_user"></textarea>
                        </div>
                        <div class="clr"></div>
                        <div class="column twelve">
                            <input type="submit" value="<?php echo lang('global.guardar') ?>" class="button">
                            <a href="#" class="button secondary" id="cancel_editar_plan"><?php echo lang('global.cancelar') ?></a>
                        </div>
                    </div>  
                <?php echo form_close() ?>
                <?php } ?>

        <?php } ?>      

    <!--CAMPO OCULTO CANTIDAD DE REAPERTURAS PLAN-->
    <input type="hidden" id="cantidad_reapertura" value="<?=$escenas[0]['cantidad_reapertura']?>" >
</div>

<!-- LISTADO DE COMENTARIOS -->
<div class="clr"></div>
</br>
<div class="row" style="height: 158PX;overflow-y: auto;">
    <?php if(isset($comentarios) and $comentarios){ ?>
    <table class="tabla_info">
        <thead>
            <tr>
                <td width="50%"><?php echo lang('global.comentarios') ?></td>
                <td width="30%"><?php echo lang('global.usuario') ?></td>
                <td width="10%"><?php echo lang('global.fecha') ?></td>
                <td width="10%"><?php echo lang('global.acciones') ?></td>
            </tr>
        </thead>
        <?php foreach ($comentarios as $c) { ?>
            <tr id="comentario_<?php echo $c['id_comentario'] ?>">
                <td><?php echo $c['comentario'].'' ?></td>
                <td><?php echo $c['nombre'].' '.$c['apellido'] ?></td>
                <td><?php echo $c['fecha'] ?></td>
                <td><a href="#" class="eliminar_comentario" id="<?php echo $c['id_comentario'] ?>"><?php echo lang('global.eliminar') ?></a href="#"></td>
            </tr>
        <?php } ?> 
    </table>
    <?php } ?>
</div>
<!-- FIN LISTADO DE COMENTARIOS -->
<?php  }  ?>


<!-- CAMPOS OCULTOS FECHAS DE PRODUCCION -->
<input type="hidden" id="fechas_bloqueadas" value="<?=$fechas_bloqueadas?>">
<input type="hidden" id="fechas_bloqueadas_trabajo" value="<?=$fechas_bloqueadas_trabajo?>">
<input type="hidden" id="end_recording_production" value="<?=strftime('%d-%b-%Y',strtotime($produccion['0']->fin_grabacion))?>">
<input type="hidden" id="start_recording_production" value="<?=strftime('%d-%b-%Y',strtotime($produccion['0']->inicio_grabacion))?>">
<input type="hidden" id="fecha_actual" value="<?=strftime('%d-%b-%Y')?>">
<!--SPANS OCULTOS CON FECHA DE INCIO UNIDADES-->
<?php $j=1;foreach ($unidades as $unidad) { ?>
    <?php if($unidad['fecha_inicio']!="" AND $unidad['fecha_inicio']!="0000-00-00"){?>
        <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""><?=strftime('%d-%b-%Y',strtotime($unidad['fecha_inicio']))?></span>
    <?php }else{ ?>
        <span class="date_unity" id="date_unity<?=$unidad['id']?>" value=""></span>
    <?php } ?>
<?php } ?>
<!-- FIN CAMPOS OCULTOS FECHAS DE PRODUCCION -->

<!-- SECCION PARA ASIGNAR ESCENAS AL PLAN -->
<div id="asignarEscenasWrap">
    <div class="asignarEscenasbox">
        <span class="closeIcon"></span>
        <div class="table_general">
            <table class="main" style="width: 100% !important;min-width: 100% !important;">
                <thead>
                    <tr>
                        <td><?php echo lang('global.asignar_escenas') ?></td>
                    </tr>
                </thead>
            </table>
            <!--AGREGAR ESCENAS AL PLAN-->
            <?php if($escenas[0]['estado_plan']!='Cerrado' AND $usuario_permisos!="read"){ ?>
                <div class="columns twelve">            
                    <?php echo form_open($idioma.'plan_general/asignar_plan_diario',' class="", id="Asing_plan", name="Asing"');?>
                        <input type="hidden" id="idescenas" name="idescenas" value="">
                        <input type="hidden" id="idplanes" name="idplanes" value="">
                        <input type="hidden" name="unidad" value="<?=$unidad_select?>">
                        <input type="hidden" name="fecha_inicio" id="start_plan_2" value="<?=$date?>">
                        <input type="hidden" id="start_plan" value="<?=$date?>">
                        <?php if(isset($escenas[0]['id_plan_diario'])){ 
                            $idplan=$escenas[0]['id_plan_diario'];
                        }else{
                            $idplan=0;
                        } ?>
                        <input type="hidden" id="idplan" value="<?=$idplan?>">
                        <input type="hidden" name="validator" value="1">
                        <input type="hidden" id="idproduccion" name="idproduccion" value="<?=$produccion['0']->id_produccion?>">
                        <input type="hidden" id="unidad_selector" value="<?=$unidad_select?>">
                        
                        <h3><?php echo lang('global.asignar_escenas') ?></h3>
                        <label><?php echo lang('global.libreto') ?></label>
                        <select id="capitulos">
                            <?php foreach ($capitulos as $capitulo) {?>
                                <option value="<?=$capitulo['id_capitulo']?>"><?=$capitulo['numero']?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                        <label><?php echo lang('global.escena') ?></label>
                        <select name="idescena" id="escenas">
                        </select>
                        <br><br>
                        <input type="button" value="<?php echo lang('global.agregar') ?>" id="insert_asing_button" class="button">
                    <?php echo form_close() ?>
                </div>
            <?php } ?>
            <!--FIN AGREGAR ESCENAS AL PLAN-->
        </div>
    </div>          
</div>
<!-- FIN SECCION PARA ASIGNAR ESCENAS AL PLAN -->

<?php
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