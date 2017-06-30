
<?php $idioma = $this->lang->lang().'/'; setlocale(LC_TIME, 'es_ES.UTF-8'); ?>

<?php setlocale(LC_TIME, 'es_ES.UTF-8'); ?>

<div id="breadcrumbs">
    <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> 
    / <a  href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> 
    <?php if($capitulo!="") {?>
    <?php if($capitulo[0]['nombre']!=""){
        echo '/ <a  href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'.lang('global.libretos').'</a> ';
        echo '/'.lang('global.numero_libretos').' '.$capitulo[0]['numero']; 
    }else{
    echo '/ <a  href="'.base_url($idioma.'libretos/index/'.$produccion['0']->id_produccion).'">'.lang('global.libretos').'</a> '; 
    echo '/'.lang('global.numero_libretos').' '.$capitulo[0]['numero']; } }?>

    / <?php echo lang('global.escenas') ?>
    <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>

<nav>
    <ul class="nav_post nav_escenas">
        <?php if($capitulo!="" AND $capitulo[0]['numero_escenas']!="" AND $capitulo[0]['estado']!=6 AND $usuario_permisos=="write" and $produccion['0']->estado!=2) {?>
        <li><a href="<?=base_url($idioma.'escenas/crear_escenas/'.$produccion[0]->id_produccion.'/'.$idcapitulo)?>" class="buttons icon icon_plus"><span></span><?php echo lang('global.crear_escenas') ?></a></li>
        <?php } ?>
        <li><a href="#" class="buttons icon icon_filter"><span></span><?php echo lang('global.filtrar') ?></a></li>
        <li><?php echo anchor($idioma.'excel/excel_escenas/'.$produccion[0]->id_produccion.'/'.$idcapitulo,'<span></span>'.lang('global.guardar'),array('class'=>'buttons icon icon_save','target'=>'_blank')); ?></li>
        <li><?php echo anchor($idioma.'pdf/pfd_escenas/'.$produccion[0]->id_produccion.'/'.$idcapitulo,'<span></span>'.lang('global.imprimir'),array('class'=>'buttons icon icon_print','target'=>'_blank')); ?></li>
        <!-- <li><a href="#" class="buttons help_button" alt="Fullscreen"><span class="open"></span><?php echo lang('global.ayuda') ?></a></li> -->
        <li><a href="#" class="buttons full_screen" alt="Fullscreen"><span class="open"></span><span class="close"></span><?php echo lang('global.full_screen') ?></a></li>
    </ul>
</nav>


<div id="inner_content">
    <div class="top_page top_page_capitulos row">

        <!--INICIO FORMULARIO BUSQUEDA DE ESCENAS-->
        <div class="column two">
            <?php echo form_open($idioma.'escenas/buscar_escenas',' class="custom" style="margin:0;"');?>
            <input type="hidden" name="idproduccion" id="idproduccion" value="<?= $produccion[0]->id_produccion  ?>"> 
            <input type="hidden" name="idcapitulo" id="idcapitulo" value="<?= $idcapitulo  ?>">
            <label><?php echo lang('global.libretos') ?></label>
            <?php $capitulo_numero='';$capitulo_id=''; ?>
                <select  onchange = "this.form.submit()" name="cap" style="display:block!important;" id="list_cap">
                    <?php if($capitulo!=""){?>
                    <?php $capitulo_numero=$capitulo[0]['numero'];$capitulo_id=$idcapitulo ?>
                    <option value="<?= $idcapitulo ?>" selected>
                        <?=$capitulo[0]['numero']?>
                    </option>
                    <?php } ?>
                    <?php foreach ($capitulos as $the_capitulo) { ?>
                        <?php 
                        if($capitulo!=""){
                            if($capitulo[0]['numero'] != $the_capitulo['numero']){?>
                            <option value="<?= $the_capitulo['id_capitulo'] ?>" >
                                <?=$the_capitulo['numero']?>
                            <?php } ?>
                            <?php }else { ?>
                            <option value="<?= $the_capitulo['id_capitulo'] ?>" >
                            <?=$the_capitulo['numero']?>
                        <?php } ?>
                    </option>
                    <?php } ?>
                </select>
                <?php echo form_close(); ?>
        </div>
        <!--FIN FORMULARIO BUSQUEDA DE ESCENAS-->

        <div class="column seven infoPlanBox" id="detail_gray">
                <?php if(isset($capitulo)){
                    $escenas_canceladas=0;
                    if($escenas){
                    foreach ($escenas as $escena) {
                   if($escena->estado==3){
                    $escenas_canceladas++;
                   }
                   }
                } ?>
               <div><strong>total: </strong><span><?=$capitulo[0]['numero_escenas']?></span></div>
               <div><strong>Prod: </strong><span><?=$capitulo[0]['escenas_producidas']?></span></div>
               <div><strong>No Prod: </strong><span><?=$capitulo[0]['escenas_escritas']-$capitulo[0]['escenas_producidas']?></span></div>
               <div><strong><?php echo lang('global.canceladas') ?>: </strong><span><?=$escenas_canceladas?></span></div>
               <div><strong>Post Prod: </strong><span><?=$post_producidas[0]->total?></span></div>
               
               <?php if(isset($capitulo[0])){
                    $descripcion = $capitulo[0]['estado_capitulo'];
                    switch($capitulo[0]['estado_capitulo']){
                      case 'En Progreso':
                        $class_cap="cap_progress";
                      break;
                      case 'Escrito':
                        $class_cap="cap_writed";
                      break;
                      case 'Producido':
                        $class_cap="cap_completed";
                      break;
                      case 'Entregado':
                        $class_cap="cap_deliver";
                      break;
                      case 'Desglosado':
                        $class_cap="cap_desglosed";
                      break;
                      case 'Cancelado':
                        $class_cap="cap_canceled";
                      break;
                      case 'No iniciado':
                        $class_cap="cap_progress_proyected";
                      break;
                    }
                  }else{
                      $descripcion = "En Progreso";
                      $class_cap="cap_progress";
                  }
                ?>
               <div class="<?=$class_cap?>"><strong><?php echo lang('global.estatus') ?>: </strong><span><?=$capitulo[0]['estado_capitulo']?></span></div>
                
                <?php } ?>
        </div>
        
        <div class="blue_box box_print">
            <form action="#" class="custom">
                <div class="row">
                    <div class="columns seven">
                        <label for="detalles" class="label_check"><input type="radio" name="save" id="detalles"> <?php echo lang('global.imprimir_reporte_unidad') ?> </label>
                        <label for="sin_detalles" class="label_check"><input type="radio" name="save" id="sin_detalles"> <?php echo lang('global.imprimir_reporte_unidad2') ?> </label>
                    </div>
                    <div class="columns five">
                        <div class="call_to_action align_right">
                            <a href="#" class="button secondary icon icon_cancel cancel_"><span></span><?php echo lang('global.cancelar') ?></a>
                            <a href="#" class="button secondary icon icon_print"><span></span><?php echo lang('global.imprimir') ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="column three">
            <br>
            <a href="#" class="button colorsBoxLink" style="width:100%;"><?php echo lang('global.convencion_colores') ?> <span class="arrow"></span></a>   
            <?php if($msg==2){ ?>
                <a href="<?=base_url($idioma.'escenas/index/'.$produccion[0]->id_produccion.'/'.$idcapitulo)?>" class="button" style="width:100%;"> <?php echo lang('global.limpiar_filtro') ?></a>
            <?php } ?>
        </div>
            
    </div>

    <!--INICIO SECCION DE FILTRO-->
    <div class="content_filter row" id="content_filter" style="display:none">
        <?php echo form_open($idioma.'escenas/filtro_escenas',' class="custom", id="filtro"');?>
        <input type="hidden" name="idproduccion" id="idproduccion" value="<?= $produccion[0]->id_produccion  ?>"> 
        <input type="hidden" name="idcapitulo" id="idcapitulo" value="<?= $idcapitulo  ?>"> 
            <div class="columns two">
                <div class="row">
                    <div class="columns twelve">
                        <label for=""><?php echo lang('global.rango_escenas') ?></label>
                    </div>
                    <div class="columns six">
                        <input type="text" name="limite1" class="required" placeholder="00" value="<?=$limite1?>">
                    </div>
                    <div class="columns six">
                        <input type="text" name="limite2" class="required" placeholder="00" value="<?=$limite2?>">
                    </div>
                </div>
            </div>
            <div class="columns three">
                <label for="locacion"><?php echo lang('global.locacion') ?></label>
                <select name="locacion" id="location_crear_escena" class="required" style="display:block!important;">
                    <option value="" selected>Seleccione una locación</option>
                    <?php foreach ($locaciones as $locacion) { ?>
                       <option value="<?=$locacion->id?>"
                        <?php if ($locacion->id ==$idlocacion): ?>
                            selected
                        <?php endif ?>
                        ><?=$locacion->nombre?></option>
                    <?php } ?> 
                </select>
            </div>
            <div class="columns three">
                <input type="hidden" value="<?=$idset?>" id="idset_hidden">
                <label for="locacion"><?php echo lang('global.set') ?></label>
                <select name="set" id="set" class="set" style="display:block!important;">

                </select>
            </div>
            <div class="columns one">
                    <div class="columns twelve">
                        <label for=""><?php echo lang('global.continuidad') ?></label>
                        <input type="text" name="continuidad" placeholder="00" value="<?=$continuidad?>">
                </div>
            </div>
            <div class="columns three">
                <div class="twelve columns">
                    <div class="call_to_action align_left" style="margin: 11px 0 0 0;">
                        <a href="#" class="button secondary icon icon_cancel" id="cancel_filter"><span></span><?php echo lang('global.cancelar') ?></a>
                        <a href="#" onClick="document.getElementById('filtro').submit()" class="button icon icon_filter"><span></span><?php echo lang('global.filtrar') ?></a>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
    <!--FIN SECCION DE FILTRO-->

    <!---->
    <?php if($msg==1){ ?>
    <div class="alert-box alert">
        No hay resultados para la busqueda<a href="" class="close">&times;</a>
    </div>
    <?php } ?>

    <div class="row">
        <div class="column twelve">
            <div class="colorsBox">
                <div class="row">
                  <?php $total=sizeof($estados_color) ?>
                    <?php $i=0;foreach ($estados_color as $estado_color) {?>
                       <?php if($estado_color->id!=3){ ?>
                            <?php if( $i==0){
                                echo '<div class="column three"><ul class="colorsList">';
                            }?>
                            <?php if($i==(ceil(($total/2)+1))){
                                echo '</ul></div><div class="column three"><ul class="colorsList">';
                            }
                            if($estado_color->id==5){
                                $estado_color->descripcion = 'NO GRABADA TOMAS DE UBICACION';
                            }
                            ?>

                            <li><span class="estado_color" style="background:<?=$estado_color->color?>;"></span><span><?=$estado_color->descripcion.'</br>'?></span></li>
                      <?php } $n=$i+1; if($total==$n){ ?>
                             <?php  //echo '</ul></div><div class="column two"><ul class="colorsList">'; ?> 
                             <li><span class="estado_color" style="background:#000000"></span><span>CANCELADA</br></span></li>
                      <?php } ?>      
                    <?php ++$i; }
                        echo '</ul></div>'; ?> 
                </div>
            </div>
        </div>
    </div>
    
    

    <!--INICIO TABLA PRINCIPAL DE ESCENAS-->
    <?php if($escenas!="" and $escenas){?>
    <div id="scroll">
        <div class="table_general">
            <table class="main" id="escenas_table">
                <thead>
                    <tr>
                        <td width="37%"><?php echo lang('global.detalle') ?></td>
                        <td width="14%"><?php echo lang('global.tiempo') ?></td>
                        <td width="11%"><?php echo lang('global.asignada') ?></td>
                        <td width="10%"><?php echo lang('global.estatus') ?></td>
                        <td width="18%"><?php echo lang('global.acciones') ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="gray">
                        <td>
                            <table class="secondary">
                                <tr>                                    
                                    <td width="6%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.escena') ?>">esc</span></td>
                                    <td width="39%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.locacion') ?>"><?php echo lang('global.locacion') ?></span></td>
                                    <td width="39%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.set') ?>">set</span></td>
                                    <td width="6%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.dia_continuidad') ?>">cont</span></td>
                                    <td width="10%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.toma') ?>"><?php echo lang('global.toma') ?></span></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="secondary">
                                <tr>
                                    
                                    <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_estimado') ?>">est</span></td>
                                    <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_real') ?>">real</span></td>
                                    <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_post') ?>">post</span></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="secondary">
                                <tr>
                                    <td width="40%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.unidad') ?>">unidad</span></td>
                                    <td width="60%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.fecha') ?>"><?php echo lang('global.fecha') ?> prod</span></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <span class="has-tip tip-centered-top" title="<?php echo lang('global.estado') ?>"><?php echo lang('global.estado') ?></span>
                        </td>
                        <td>
                            <span class="has-tip tip-centered-top" title="<?php echo lang('global.acciones') ?>"><?php echo lang('global.acciones') ?></span>
                        </td>
                    </tr>
                    <!--INICIO FILAS DE ESCENAS-->
                    <?php $i=0; foreach ($escenas as $escena) {?>
                    <?php if($escena->estado != 1 AND $escena->estado != 2 AND $escena->estado != 3 AND $escena->estado != 12 AND $escena->estado != 14 AND $usuario_permisos!="read") {?>
                        <?php $class='withLink'; ?>
                        <?php }else{
                            $class='withOutLink';
                        } 
                        if($escena->estado == '1'){
                            $class='withOutLink';
                        }
                    ?>
                    <?php if($i%2==0){?>
                        <tr <?=$escena->estado?> class="white edit <?php echo $class; ?>" 
                        <?php if($escena->estado == '') {?>
                            ondblclick="edit(<?=$escena->id?>,<?=$produccion[0]->id_produccion?>, <?=$idcapitulo?>);"
                        <?php }?>>
                        <?php }else{ ?>
                        <tr <?=$escena->estado?> class="gray_light <?php echo $class; ?>"
                        <?php if($escena->estado == '') {?>
                            ondblclick="edit(<?=$escena->id?>,<?=$produccion[0]->id_produccion?>, <?=$idcapitulo?>);"
                        <?php }?>>
                            <?php } ++$i; ?>
                            <td>
                                <table class="secondary">
                                    <tr>
                                        <td width="6%" ><?= $escena->numero_escena ?></td>
                                        <td width="39%" class="cell_align_left"><?= $escena->locacionnombre ?></td>
                                        <td width="39%" class="cell_align_left"><?= $escena->setnombre ?></td>
                                        <td width="6%"><?= $escena->dias_continuidad ?></td>
                                        <td width="10%">
                                        <?php 
                                        if($escena->retomas==0 AND ($escena->estado==1 OR $escena->estado==13)){
                                            echo 1;
                                        }else{
                                            if($escena->retomas!=0){
                                                echo $escena->retomas;
                                            }else{
                                                echo 0;
                                            }
                                        } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="secondary">
                                    <tr>
                                        <td width="33%"><?php 
                                            if(strlen($escena->duracion_estimada_minutos)<2){
                                                echo  $minutos2 = '0'.$escena->duracion_estimada_minutos;
                                            }else{
                                                echo  $escena->duracion_estimada_minutos;
                                            }
                                            echo ":";
                                            if(strlen($escena->duracion_estimada_segundos)<2){
                                               echo $segundos = '0'.$escena->duracion_estimada_segundos;
                                            }else{
                                                echo $escena->duracion_estimada_segundos;
                                            }
                                         ?></td>
                                        <td width="33%">
                                            <?php

                                            if($escena->duracion_real_minutos==""){
                                                echo '00';
                                            }else{
                                                if(strlen($escena->duracion_real_minutos)<2){
                                                    echo  $minutos2 = '0'.$escena->duracion_real_minutos;
                                                }else{
                                                    echo  $escena->duracion_real_minutos;
                                                }
                                            }

                                            echo ":";

                                            if($escena->duracion_real_segundos==""){
                                                echo '00';
                                            }else{
                                                if(strlen($escena->duracion_real_segundos)<2){
                                                    echo $segundos = '0'.$escena->duracion_real_segundos;
                                                }else{
                                                    echo $escena->duracion_real_segundos;
                                                }
                                            }

                                            ?>
                                            <td width="33%">
                                             <?php
                                                /*if($escena->tiempo_post_minutos==""){
                                                    echo '00';
                                                }else{
                                                    if(strlen($escena->tiempo_post_minutos)<2){
                                                        echo  $minutos2 = '0'.$escena->tiempo_post_minutos;
                                                    }else{
                                                        echo  $escena->tiempo_post_minutos;
                                                    }
                                                }

                                                echo ":";

                                                if($escena->tiempo_post_segundos==""){
                                                    echo '00';
                                                }else{
                                                    if(strlen($escena->tiempo_post_segundos)<2){
                                                        echo $segundos = '0'.$escena->tiempo_post_segundos;
                                                    }else{
                                                        echo $escena->tiempo_post_segundos;
                                                    }
                                                }*/
                                                echo Escenas::calculo_tiempo_post($escena->tiempo_post_minutos,$escena->tiempo_post_segundos,$escena->tiempo_post_cuadros);
                                            ?>
                                            </td>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="secondary">
                                    <tr>
                                        <td width="40%">
                                        <?php 
                                        if(($escena->estado == 1 AND $escena->unidad_produccion!="") OR ($escena->estado == 2 AND $escena->unidad_produccion!="") ){
                                            if($escena->unidad_produccion!=""){
                                                echo $escena->unidad_produccion;
                                            }else{
                                                echo "-";
                                            } 
                                        }else{
                                            if($escena->unidad_numero!=0){
                                                echo $escena->unidad_numero;
                                            }else{
                                                echo "-";
                                            } 
                                        } ?>
                                        </td>
                                        <td width="60%">
                                        <?php 
                                        if(($escena->estado == 1 AND $escena->fecha_produccion!="" AND $escena->fecha_produccion!="0000-00-00") 
                                            OR ($escena->estado == 3 AND $escena->fecha_produccion!="" AND $escena->fecha_produccion!="0000-00-00") 
                                            OR ($escena->estado == 2 AND $escena->fecha_produccion!="" AND $escena->fecha_produccion!="0000-00-00") ){
                                            if($escena->fecha_produccion!=""){
                                                echo strftime('%d-%b-%Y',strtotime($escena->fecha_produccion));
                                            }else{
                                                echo "-";
                                            }
                                        }else{
                                            if($escena->fecha_inicio!=""){
                                                echo strftime('%d-%b-%Y',strtotime($escena->fecha_inicio));
                                            }else{
                                                echo "-";
                                            }
                                        }?></td>
                                    </tr>
                                </table>
                            </td>
                            <!--INICIO CELDA ESTADO DE ESCENAS-->
                            <?php switch($escena->estado){
                            case 1:
                            echo ' <td class="td_yellow">
                            '.lang('global.producidas').'
                            </td>
                            ';
                            break;
                            case 2:
                            echo ' <td class="td_retoma">
                            '.lang('global.retoma').'
                            </td>
                            ';
                            break;
                            case 3:
                            echo ' <td class="td_black">
                            '.lang('global.canceladas').'
                            </td>
                            ';
                            break;
                            case 4:
                            echo ' <td class="td_cian">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            case 5:
                            echo ' <td class="td_cian_light">
                            '.lang('global.no_grabada').'
                            </td>
                            ';
                            break;
                            case 6:
                            echo ' <td class="td_green">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            case 7:
                            echo ' <td class="td_green_light">
                            '.lang('global.no_grabada').'
                            </td>
                            ';
                            break;
                            case 8:
                            echo ' <td class="td_pink">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            case 9:
                            echo ' <td class="td_pink_light">
                            '.lang('global.no_grabada').'
                            </td>
                            ';
                            break;
                            case 10:
                            echo ' <td class="td_orange">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            case 11:
                            echo ' <td class="td_orange_light">
                            '.lang('global.no_grabada').'
                            </td>
                            ';
                            break;
                            case 12:
                            echo ' <td class="td_yellow">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            case 14:
                            echo ' <td class="td_retoma">
                            '.lang('global.programada').'
                            </td>
                            ';
                            break;
                            default:
                            echo ' <td class="td_brown_light">
                            '.lang('global.no_asignado').'
                            </td>
                            ';
                            break;
                            }?>
                            <td>
                            <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){ ?>   
                                <?php if($escena->estado != 3 ) {?>
                                    <a opcion="cancelar" idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" idcapitulo="<?=$idcapitulo?>" href="#" class="action_escena"><?php echo lang('global.cancelar') ?></a> /
                                <?php } ?>
                                <?php if($escena->estado != 1 OR $escena->estado != 2 OR $escena->estado != 12 OR $escena->estado != 14) {?>

                                    <a opcion="eliminar" idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" asignacion="<?php if($escena->fecha_inicio!=""){ echo strftime('%d-%b-%Y',strtotime($escena->fecha_inicio)).'/ U'.$escena->unidad_numero; }else{ echo "-"; }?>" idcapitulo="<?=$idcapitulo?>" href="#" class="action_escena"><?php echo lang('global.eliminar') ?></a> /

                                <?php } ?>
                                <?php if($escena->estado != 3) {?>
                                    <a idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" idcapitulo="<?=$idcapitulo?>" href="#" class="duplicate_escena"><?php echo lang('global.duplicar') ?></a>
                                <?php } ?>
                                <?php if($escena->estado != 3 AND $escena->estado != 13) {?>
                                <?php if(!$limite1 or $limite1==''){
                                        $limite1='null';
                                        }
                                        if(!$limite2){
                                        $limite2='null';
                                        }
                                        if(!$idlocacion){
                                        $idlocacion='null';
                                        }
                                        if(!$idset){
                                        $idset='null';
                                        }
                                        if(!$continuidad){
                                        $continuidad='null';
                                        } ?>
                                    / <a href="<?=base_url().$idioma.'escenas/editar_escena/'.$escena->id.'/'.$produccion[0]->id_produccion.'/'.$idcapitulo.'/'.$limite1.'/'.$limite2.'/'.$idlocacion.'/'.$idset.'/'.$continuidad?>" class="linkEditar"><?php echo lang('global.editar') ?></a> 
                                <?php } ?>
                            <?php } ?>
                            </td>
                            </tr>
                            <!-- FIN CELDA ESTADO DE ESCENA-->

                        </tr>

                        <!--INICIO INFORMACION ADICIONAL ESCENA-->
                        <?php if($escena->estado != ''){ ?>
                        <tr class="info_escena">
                            <td colspan="5">
                                <div class="row">
                                    <table class="tabla_info">
                                      <tr>
                                        <td>
                                          <label>PÁG.  <?php echo lang('global.guion') ?>: </label> <span><?= $escena->libreto?></span>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.locacion') ?> / <?php echo lang('global.estudio') ?>: </label> <?php if($escena->id_tipo_locacion==1){echo "Locación";}?> <?php if($escena->id_tipo_locacion==2){echo "Estudio";}?><?php if($escena->id_tipo_locacion==3){echo "Toma Ubicación";}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.dia_noche') ?>: </label> <?php if($escena->id_dia_noche==1){echo 'Día';}?> <?php if($escena->id_dia_noche==2){echo 'Noche';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.int_ext') ?>: </label> <?php if($escena->id_interior_esterior==1){echo 'Interior';}?> <?php if($escena->id_interior_esterior==2){echo 'Exterior';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.flashback') ?>: </label> <?php if($escena->id_flasback==1){echo 'Si';}?> <?php if($escena->id_flasback!=1){echo 'No';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.toma_ubicacion') ?>: </label> <?php if($escena->id_toma_ubicacion==1){echo 'Si';}?> <?php if($escena->id_toma_ubicacion!=1){echo 'No';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.foto_realizacion') ?>: </label> <?php if($escena->id_foto_realizacion==1){echo 'Si';}?> <?php if($escena->id_foto_realizacion!=1){echo 'No';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.imagen_archivo') ?>: </label> <?php if($escena->id_imagenes_archivo==1){echo 'Si';}?> <?php if($escena->id_imagenes_archivo!=1){echo 'No';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.magnitud') ?>: </label> <?php if($escena->magnitud){echo $escena->magnitud;}else{ echo '-';}?>
                                        </td>
                                        <td>
                                          <label><?php echo lang('global.vehivulo_back') ?>: </label> <?php if($escena->vehiculo_background){echo $escena->vehiculo_background;}else{ echo '-';}?>
                                        </td>
                                        <td>
                                          <?php $capitulo_post=$this->model_escenas_2->capitulo_idescena($escena->id) ?>
                                          <label><?php echo lang('global.capitulos') ?>: </label> <?php if($capitulo_post){echo $capitulo_post['0']->numero;}else{ echo '-';}?>
                                        </td>
                                        <td>

                                          <label><?php echo lang('global.fecha_post_produccion') ?>: </label> <?php if($capitulo_post and $capitulo_post['0']->fecha_entregada and $capitulo_post['0']->fecha_entregada!='0000-00-00'){echo strftime('%d-%b-%Y',strtotime($capitulo_post['0']->fecha_entregada));}else{ echo '-';}?>

                                        </td>
                                      </tr>
                                    </table>
                                    <table class="tabla_info">
                                      <tr>
                                        <td style="vertical-align:top;" width="50%">
                                            <label><?php echo lang('global.descripcion_escena') ?>: </label>
                                            <?php if ($escena->descripcion){ ?>
                                                <?= $escena->descripcion?>
                                            <?php } else { ?>
                                                <?php echo lang('global.escena_no_descripion') ?>
                                            <?php } ?>
                                        </td>
                                        <td style="vertical-align:top;" width="50%">
                                          <label><?php echo lang('global.guion') ?>: </label>
                                            <?php if ($escena->guion){ ?>
                                                <textarea disabled style="resize: none;"><?= $escena->guion?></textarea>
                                            <?php } else { ?>
                                                <?php echo lang('global.escena_no_guion') ?>
                                            <?php } ?>
                                        </td>
                                      </tr>
                                    </table>
                                </div> 
                                <br>
                                <h5><?php echo lang('global.elementos_asignados') ?></h5>
                                <?php $elementos_escena = $this->model_escenas_2->buscar_elementos($escena->id);
                                      $personajes_escena = $this->model_escenas_2->buscar_personajes_escena($escena->id); ?>
                                <?php if($personajes_escena OR $elementos_escena){ 
                                     $rol_temp =""; ?>
                                    <table class="tabla_info">
                                        <tr>
                                        <?php if($personajes_escena){?>
                                            <?php foreach ($personajes_escena as $personaje_escena) { ?>
                                                <?php if($personaje_escena->rol!=$rol_temp){?>
                                                    <td><label><?=$personaje_escena->rol?></label></td>
                                                <?php $rol_temp= $personaje_escena->rol; } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if($elementos_escena!=false){?>
                                            <td><label><?php echo lang('global.elementos') ?></label></td>
                                        <?php } ?>
                                        </tr>
                                        <tr>
                                            <?php if($personajes_escena){?>
                                                <td>
                                                    <?php $rol_temp = $personajes_escena[0]->rol; 
                                                    foreach ($personajes_escena as $personaje_escena) { ?>
                                                    <?php if($personaje_escena->rol!=$rol_temp){?>
                                                        </td><td>
                                                    <?php $rol_temp= $personaje_escena->rol; } ?>
                                                    <strong><?= $personaje_escena->cantidad.' '.$personaje_escena->nombre ?></strong> <!-- (<?= $personaje_escena->rol ?>)  --></br> 
                                                <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <?php
                                            if($elementos_escena!=false){?>
                                            <td>
                                            <?php foreach ($elementos_escena as $elemento_escena) { ?>
                                                  <strong><?= $elemento_escena->cantidad.' '.$elemento_escena->nombre_elemento ?></strong> (<?= $elemento_escena->categoria ?>) </br> 
                                            <?php } ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                <?php } ?>
                                    
                                  <!-- <tr>
                                    <td style="vertical-align:top;" width="50%">
                                    <?php
                                    if($personajes_escena!=false){
                                        foreach ($personajes_escena as $personaje_escena) { ?>
                                          <strong><?= $personaje_escena->cantidad.' '.$personaje_escena->nombre ?></strong> (<?= $personaje_escena->rol ?>) - 
                                        <?php }
                                    }?>
                                    <?php
                                    if($elementos_escena!=false){
                                        foreach ($elementos_escena as $elemento_escena) { ?>
                                          <strong><?= $elemento_escena->nombre_elemento ?></strong> (<?= $elemento_escena->categoria ?>) - 
                                        <?php }
                                    }?>
                                    </td>
                                  </tr>
                                </table> -->
                                <?php if($escena->planes_escena){ ?>
                                <h5><?php echo lang('global.hisoria_planes') ?></h5>
                                <table class="tabla_info">
                                    <tr>
                                        <td><label><?php echo lang('global.unidad') ?></label></td>
                                        <td><label><?php echo lang('global.fecha') ?></label></td>
                                        <td><label><?php echo lang('global.estatus') ?></label></td>
                                        <td><label><?php echo lang('global.tiempo_real') ?></label></td>
                                        <td><label><?php echo lang('global.producidas') ?></label></td>
                                    </tr>
                                        <?php $planes = explode(',', $escena->planes_escena);
                                        for ($m=0; $m < count($planes); $m++) { 
                                            $temp = explode('_', $planes[$m]);
                                            $producida="";   ?>
                                        <tr>
                                            <td style="vertical-align:top;" width="20%"><?=$temp[0]?></td>
                                            <td style="vertical-align:top;" width="20%"><?=$temp[1]?></td>
                                            <td style="vertical-align:top;" width="20%"><?=$temp[2]?></td>
                                            <?php if($temp[3]=="0"){
                                                $tiempo_plan = "00:00";
                                                $producida = "No";
                                            }else{
                                                $tiempo_plan = $temp[3];
                                                $producida = "Si";
                                            } ?>
                                            <td style="vertical-align:top;" width="20%"><?=$tiempo_plan?></td>
                                            <td style="vertical-align:top;" width="20%"><?=$producida?></td>
                                        </tr>
                                        <?php }?>
                                </table>
                                <?php } ?>
                                <br>
                                <!--FIN PANEL DE ELEMENTOS-->
                                <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){ ?>
                                <div class="align_left">
                                    <?php if($escena->estado !=3) {?>
                                        <a opcion="cancelar" idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" idcapitulo="<?=$idcapitulo?>" href="#" class="button action_escena"><?php echo lang('global.cancelar') ?></a>
                                    <?php } ?>
                                    <?php if($escena->estado != 1) {?>
                                        <a opcion="eliminar" idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" idcapitulo="<?=$idcapitulo?>" href="#" class="button action_escena"><?php echo lang('global.eliminar') ?></a>
                                    <?php } ?>
                                    <?php if($escena->estado != 3) {?>
                                        <a idproduccion="<?=$produccion[0]->id_produccion?>" idescena="<?=$escena->id?>" idcapitulo="<?=$idcapitulo?>" href="#" class="button duplicate_escena"><?php echo lang('global.duplicar') ?></a>
                                    <?php } ?>
                                    <?php if($escena->estado != 3 AND $escena->estado != 13) {?>
                                    <?php if(!$limite1){
                                        $limite1='null';
                                        }
                                        if(!$limite2){
                                        $limite2='null';
                                        }
                                        if(!$idlocacion){
                                        $idlocacion='null';
                                        }
                                        if(!$idset){
                                        $idset='null';
                                        }
                                        if(!$continuidad){
                                        $continuidad='null';
                                        } ?>
                                        <a href="<?=base_url().$idioma.'escenas/editar_escena/'.$escena->id.'/'.$produccion[0]->id_produccion.'/'.$idcapitulo.'/'.$limite1.'/'.$limite2.'/'.$idlocacion.'/'.$idset.'/'.$continuidad?>" class="button linkEditar"><?php echo lang('global.editar') ?></a> 
                                    <?php } ?>
                                </div>
                                <?php } ?>

                            </td>
                            
                        </tr>
                        <?php }?>
                        <!--FIN INFORMACION ADICIONAL ESCENA-->
                        <?php } ?>
                        <!--FIN FILAS DE ESCENAS-->
                </tbody>
            </table>
            <!--FIN TABLA PRINCIPAL DE ESCENAS-->
            </div>
            <?php }else{
                echo "<h4>".lang('global.No_escenas')."</h4>";
            } ?>
    </div>

                <!--SECCION PAR DUPLICAR-->
                </br>
                <div id="wrappOverlay">
                <div id="duplicate_escene" class="colums three" style="display:none;">
                    <span class="closeIcon"></span>
                    <h3><?php echo lang('global.hisoria_planes') ?></h3>
                    </br>
                    <label><?php echo lang('global.libreto') ?></label>
                    <select id="cap">
                    <?php if($capitulo_numero!=""){?>
                        <option value="<?= $idcapitulo ?>" selected>
                            <?=$capitulo_numero?>
                        </option>
                        <?php } ?>
                        <?php foreach ($capitulos as $the_capitulo) { ?>
                            <?php 
                            if($capitulo!=""){
                                $disable='';
                                $estado_capitulo_asig='';
                                if($the_capitulo['estado_capitulo']==1){
                                   $disable='disabled';
                                   $estado_capitulo_asig=' - No a iniciado';
                                }if($the_capitulo['estado_capitulo']==6){
                                   $disable='disabled';
                                   $estado_capitulo_asig=' - Cancelado';
                                }
                                if($capitulo[0]['numero'] != $the_capitulo['numero']){?>
                                <option value="<?= $the_capitulo['id_capitulo'] ?>" <?php echo $disable ?> >
                                    <?=$the_capitulo['numero'].''.$estado_capitulo_asig?>
                                <?php } ?>
                                <?php }else { ?>
                                <option value="<?= $the_capitulo['id_capitulo'] ?>" <?php echo $disable ?> >
                                <?=$the_capitulo['numero'].''.$estado_capitulo_asig?>
                            <?php } ?>
                        </option>
                    <?php } ?>
                    </select>
                    <label><?php echo lang('global.numero_escenas') ?></label>
                    <input type="text" id="numero_escena">
                    <a opcion="duplicar" id="duplicate_button" class="button action_escena"><?php echo lang('global.duplicar') ?></a>
                    <a class="button secondary" id="cancel_duplicate"><?php echo lang('global.cancelar') ?></a> 
                </div>
            </div>
            <!--FIN SECCION PARA DUPLICAR-->
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#location').change();
});
</script>