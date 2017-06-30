<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"> <?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> /  <?php echo lang('global.libretos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>

<nav>
    <ul class="nav_post">
        <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){?>
        <li><a href="#" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_libreto') ?></a></li>
        <?php } ?> 
        <li><?php echo anchor($idioma.'excel/excel_libretos/'.$produccion['0']->id_produccion,'<span></span>'.lang('global.guardar'),array('class'=>'buttons icon icon_save','target'=>'_blank')) ?></li>
        <li><?php echo anchor($idioma.'pdf/pdf_capitulo/'.$produccion['0']->id_produccion,'<span></span>'.lang('global.imprimir'),array('class'=>'buttons icon icon_print','target'=>'_blank')) ?></li>
        <!-- <li><a href="#" class="buttons help_button help" alt="Fullscreen"><span class="open"></span><?php echo lang('global.ayuda') ?></a></li> -->
        <li><a href="#" class="buttons full_screen" alt="Fullscreen"><span class="open"></span><span class="close"></span><?php echo lang('global.full_screen') ?></a></li>
    </ul>
    <!--SECCION PARA AGREGAR CAPITULOS-->
    <div class="blue_box box_new">
      <?php echo form_open($idioma.'libretos/agregar_capitulo',array('class'=>'validate'));?>
        <div class="row">
          <div class="columns five">
            <input type="hidden" name="id_produccion" value="<?=$produccion[0]->id_produccion?>" class="required">
            <input type="text" placeholder="#" name="numero" class="required number">
          </div>
          <div class="columns seven">
            <div class="call_to_action align_right">
              <input type="submit" value="<?php echo lang('global.guardar') ?>" class="button secondaty">
              <a href="#" class="button secondary icon icon_cancel cancel_"><span></span><?php echo lang('global.cancelar') ?></a>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
    <!-- FIN SECCION PARA AGREGAR CAPITULOS-->
</nav>

<div id="inner_content">
  <div class="top_page top_page_capitulos row" style="margin:0px;">
    <div class="blue_box box_save ">
      <form action="#" class="custom">
        <div class="row">
          <div class="columns seven">
            <label for="detalles" class="label_check"><input type="radio" name="save" id="detalles"> Guardar reporte con detalle de unidades</label>
            <label for="sin_detalles" class="label_check"><input type="radio" name="save" id="sin_detalles"> Guardar reporte sin detalle de unidades</label>
            </div>
            <div class="columns five">
            <div class="call_to_action align_right">
              <a href="#" class="button secondary icon icon_cancel cancel_"><span></span><?php echo lang('global.cancelar') ?></a>
              <a href="#" class="button secondary icon icon_print"><span></span><?php echo lang('global.imprimi') ?></a>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!--ZONA PARA EL FILTRO DE CAPITULOS-->
      <div class="columns twelve">
        <?php echo form_open($idioma.'libretos/filtro_libretos','id="filtro"');?>
        <div class="row">
          <div class="columns one">
              <input type="hidden" name="idproduccion" value="<?=$produccion[0]->id_produccion?>">
              <label><?php echo lang('global.desde') ?></label>
              <select name="from" id="from_select">
                  <?php for ($i=0; $i < count($capitulos_complete); $i++) { ?>
                  <?php if($from!="" AND $from==$capitulos_complete[$i]['numero']){ ?>
                    <option selected value="<?=$from?>"><?=$from?></option>
                    <?php }else { ?>
                    <option value="<?=$capitulos_complete[$i]['numero']?>"><?=$capitulos_complete[$i]['numero']?></option>
                  <?php } } ?>
              </select>
          </div>
          <div class="columns one">
              <label><?php echo lang('global.hasta') ?></label>
              <select name="to" id="to_select">
                  <?php for ($i=0; $i < count($capitulos_complete); $i++) { ?>
                  <?php if($to!="" AND $to==$capitulos_complete[$i]['numero']){ ?>
                    <option selected><?=$to?></option>
                    <?php }else { ?>
                    <option <?php if($i == count($capitulos_complete)-1 AND $to==""){ echo "selected"; }?> value="<?=$capitulos_complete[$i]['numero']?>"><?=$capitulos_complete[$i]['numero']?></option>
                  <?php } } ?>
              </select>
          </div>
          <div class="columns two">
            <br>
            <a href="#" class="button icon icon_filter" id="principal_filter" style="width:100%;"><span></span><?php echo lang('global.filtrar') ?></a>
          </div>
          <div class="columns eight">
            <div class="checksFilter">
                <?php if($estados_user){
                    $temp_state = explode(',', $estados_user);
                    foreach ($estados as $estado) {  
                      $valida = true;
                      for ($s=0; $s < count($temp_state)-1; $s++) { 
                        if($temp_state[$s]==$estado->id){ 
                          $valida = false; 
                          break;
                        } 
                      } ?>
                      <div class="left">
                      <?php if($valida){?>
                        <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox" checked class="capitule_state" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                      <?php }else{ ?>
                        <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox" class="capitule_state" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                      <?php } ?>
                      </div>
                  <?php  }
                   } else { ?>
                  <?php foreach ($estados as $estado) { ?>
                  <div class="left">
                  <label for="<?=$estado->descripcion?>Check"><input id="<?=$estado->descripcion?>Check" type="checkbox" <?php if($estado->id!=5){echo "checked";}?> class="capitule_state" data-idestado="<?=$estado->id?>" name=""> <?=$estado->descripcion?></label>
                  </div>
                  <?php } ?>
                <?php } ?>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    <!--FIN ZONA PARA EL FILTRO DE CAPITULOS-->
  </div>
  <div class="row">
    <div class="columns twelve">
      <?php if (form_error('numero')): ?>
        <div class="alert-box alert">
          <?php echo form_error('numero'); ?>
          <a href="" class="close">&times;</a>
        </div>
      <?php endif ?>
      <?php if (form_error('existe')): ?>
        <div class="alert-box alert">
          <?php echo form_error('existe'); ?>
          <a href="" class="close">&times;</a>
        </div>
      <?php endif ?>
    </div>
  </div>
  <div id="scroll" class="scrollLibretos">
    <div class="table_general">
      <table class="main" id="main">
        <thead>
          <tr>
            <td class=""><?php echo lang('global.informacion_libreto') ?></td>
            <td class=""><?php echo lang('global.escenas') ?></td>
            <td class=""><?php echo lang('global.tiempo') ?></td>
            <td class=""><?php echo lang('global.detalle')  ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="gray">
            <td>
              <table class="secondary">
                <tr>

                  <td width="10%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero') ?>">#</span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.nombre') ?>"><?php echo lang('global.nombre') ?></span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.sinopsis') ?>"><?php echo lang('global.sinopsis') ?></span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="Sinopsis"><?php echo lang('global.paginasLibretos') ?></span></td>
                  </tr>

              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="25%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.numero_escenas') ?>">Nro Esc</span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.escenas_desglosadas') ?>"><?php echo lang('global.desglosadas') ?></span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.producidas') ?>"><?php echo lang('global.producidas') ?></span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.post_producidas') ?>"><?php echo lang('global.post_producidas') ?></span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_estimado') ?>">est</span></td>
                  <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_real') ?>"><?php echo lang('global.real') ?></span></td>
                  <td width="33%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.tiempo_postproducidas') ?>">post</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <!--<td width="15%"><span class="has-tip tip-centered-top" title="Fecha al aire">al aire</span></td>-->
                  <td width="15%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.estado') ?>"><?php echo lang('global.estado') ?></span></td>
                  <td width="15%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.libreto') ?>"><?php echo lang('global.libreto') ?></span></td>
                  <td width="31%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.alertas') ?>"><?php echo lang('global.alertas') ?></span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="<?php echo lang('global.acciones') ?>"><?php echo lang('global.acciones') ?></span></td>
                </tr>
              </table>
            </td>
          </tr>

          <!--INICIO FILAS-->
          <?php 
            for ($i=0; $i < count($capitulos); $i++) {
              /*VALIDACION DE OPEN BOX SI EL CAPITULO ESTA CANCELADO*/

              if($capitulos[$i]['descripcion']!="Cancelado" and $produccion['0']->estado!=2){
                $class_box="open_box";
              }else{
                $class_box="null_box";
              }
              /*FIN VALIDACION DE OPEN BOX SI EL CAPITULO ESTA CANCELADO*/

              /*VALIDACION DE CLASS DE FILA*/
              if($i%2==0){
                $class="white";
              }else{
                $class="gray_light";
              }
              /*FIN VALIDACION DE CLASS DE FILA*/
          ?>

            <tr class="<?=$class?>">
            <td>
              <table class="secondary">
                <tr>
                  <!--INICIO CELDA NUMERO-->
                  <td width="10%">
                    <!--VALIDACION NO ENLACE SI EL CAPITULO ESTA CANCELADO-->
                    <a   
                    <?php if($capitulos[$i]['numero_escenas']!=""){?>
                    href="<?=base_url().$idioma.'escenas/buscar_escenas/'.$produccion[0]->id_produccion.'/'.$capitulos[$i]['id_capitulo']?>" <?php } ?> 
                    <?php if($capitulos[$i]['numero_escenas']==""){
                      echo ' class="no_scene" data-numero="'.$capitulos[$i]['numero'].'" ';
                    }?>> <?=$capitulos[$i]['numero']?><?php if($capitulos[$i]['estado']==6){echo "C";}?></a> </td>
                    <!--VALIDACION NO ENLACE SI EL CAPITULO ESTA CANCELADO-->
                  <!--FIN CELDA NUMERO-->

                  <!--INICIO CELDA NOMBRE-->
                  <td width="30%">
                    <a href="#" <?php if($usuario_permisos=="write"){?>class="<?=$class_box?>" <?php } ?> id="nombre_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php
                        if(!isset($capitulos[$i]) OR $capitulos[$i]['nombre'] == ""){
                          echo lang('global.nombre_libreto');
                        }else{
                          echo Libretos::corta_palabra($capitulos[$i]['nombre'],15);
                        }
                      ?>
                    </a> 
                    <div class="hide_box capitulo_box">
                      <span class="close_box"></span>
                        <input type="text" placeholder="Nombre Libreto" id="nombre_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" value="<?php echo $capitulos[$i]['nombre'] ?>">
                        <div class="align_left">
                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar') ?></a>
                          <a href="#" class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>" opcion="nombre">Guardar</a>
                        </div>
                    </div>
                  </td>
                  <!--FIN CELDA NOMBRE-->

                  <!--INICIO CELDA SINOPSIS-->
                  <td width="30%">
                    <a href="#" <?php if($usuario_permisos=="write"){?>class="<?=$class_box?>" <?php } ?> id="sinopsis_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php
                        if(!isset($capitulos[$i]) OR $capitulos[$i]['sinopsis'] == ""){
                          echo lang('global.sinopsis');
                        }else{
                          echo '<span class="has-tip" title="'.$capitulos[$i]['sinopsis'].'" style="display:block; width:100%;color:#0098d2;text-align:center; font-weight:normal;">'.Libretos::corta_palabra($capitulos[$i]['sinopsis'],15).'<span>';
                        }
                      ?>
                    </a> 
                    <div class="hide_box capitulo_box">
                      <span class="close_box"></span>
                      <textarea cols="30" rows="10" placeholder="Sinopsis" id="sinopsis_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" name="sinopsis_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>"><?=$capitulos[$i]['sinopsis']?></textarea>
                      <?php /* ?>
                        <input type="text" placeholder="Sinopsis" value="<?=$capitulos[$i]['sinopsis']?>" id="sinopsis_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php */ ?>
                        <div class="align_left">
                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar'); ?></a>
                          <a href="#" class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>" opcion="sinopsis">opcion="sinopsis"><?php echo lang('global.guardar'); ?></a>
                        </div>
                    </div>
                  </td>
                  <!--FIN CELDA SINOPSIS-->


                  <!--INICIO CELDA pagina por libreto-->
                  <td width="30%">

                    <a href="#" <?php if($usuario_permisos=="write"){?>class="<?=$class_box?>" <?php } ?> id="sinopsis_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php
                        if(!isset($capitulos[$i]) OR $capitulos[$i]['paginasPorLibretos'] == ""){
                          echo "paginas";
                        }else{
                          echo '<span class="has-tip" title="'.$capitulos[$i]['paginasPorLibretos'].'" style="display:block; width:100%;color:#0098d2;text-align:center; font-weight:normal;">'.Libretos::corta_palabra($capitulos[$i]['paginasPorLibretos'],15).'<span>';
                        }
                      ?>
                    </a> 

                    <div class="hide_box capitulo_box">
                      <span class="close_box"></span>
                      <input type="text" placeholder="paginasPorLibretos" id="paginasPorLibretos_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" name="paginasPorLibretos_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" value="<?php echo $capitulos[$i]['paginasPorLibretos'] ?>">
                      <?php /* ?>
                        <input type="text" placeholder="Sinopsis" value="<?=$capitulos[$i]['sinopsis']?>" id="sinopsis_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php */ ?>
                        <div class="align_left">
                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar'); ?></a>
                          <a href="#" class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>" opcion="paginasPorLibretos"><?php echo lang('global.guardar'); ?></a>
                        </div>
                    </div>

                  </td>
                  <!--FIN CELDA pagina por libreto-->
                  
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>

                  <!--INICIO CELDA NUMERO ESCENAS-->
                  <td width="25%">
                    <a href="#" <?php if($usuario_permisos=="write"){?>class="<?=$class_box?>" <?php } ?>id="numero_escenas_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php
                        if(!isset($capitulos[$i]) OR $capitulos[$i]['numero_escenas'] == ""){
                          echo "Nro. escenas";
                        }else{
                          echo $capitulos[$i]['numero_escenas'];
                        }
                      ?>
                    </a> 
                    <div class="hide_box capitulo_box">
                      <span class="close_box"></span>
                        <input onkeypress="return onlyNumbers(event)" type="text" placeholder="Nro. escenas" id="numero_escenas_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                        <div class="align_left">
                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar'); ?></a>
                          <a href="#" class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>" escenasDesglosadas="<?=$capitulos[$i]['escenas_escritas']?>" opcion="numero_escenas">Guardar</a>
                        </div>
                    </div>
                  </td>
                  <!--FIN CELDA NUMERO ESCENAS-->

                  <!--INICIO CELDA ESCENAS ESCRITAS-->
                  <td width="25%"><?php if($capitulos[$i]['escenas_escritas']==""){
                    echo '0';
                  }else{
                    echo $capitulos[$i]['escenas_escritas'];
                  }?></td>
                  <!--FIN CELDA ESCENAS ESCRITAS-->

                  <!--INICIO CELDA ESCENAS PRODUCIDAS-->
                  <td width="25%"><?php if($capitulos[$i]['escenas_producidas']==""){
                    echo '0';
                  }else{
                    echo $capitulos[$i]['escenas_producidas'];
                  }?></td>
                  <!--FIN CELDA ESCENAS PRODUCIDAS-->
                  <!--INICIO CELDA ESCENAS POTS-PRODUCIDAS-->
                  <td width="25%"><?php if($capitulos[$i]['post_produccidas']==""){
                    echo '0';
                  }else{
                    echo $capitulos[$i]['post_produccidas'];
                  }?></td>
                  <!--FIN CELDA ESCENAS PRODUCIDAS-->

                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <!--INICIO CELDA EST-->
                  <td width="33%"><?= $capitulos[$i]['duracion_estimada']?></td>
                  <!--FIN CELDA EST-->

                  <!--INICIO CELDA REAL-->
                  <td width="33%"><span class="red"><?php if($capitulos[$i]['duracion_real']!=""){
                    echo $capitulos[$i]['duracion_real'];
                  }else{
                    echo "00:00";
                  }
                    ?></span> </td>
                  <td width="33%"><?= Libretos::calculo_tiempo_post($capitulos[$i]['tiempo_post_minutos'],$capitulos[$i]['tiempo_post_segundos'],$capitulos[$i]['tiempo_post_cuadros'])?></td>
                  <!--INICIO CELDA REAL-->
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  
                  <!--INICIO CELDA FECHA AL AIRE-->
                  <!--<td width="15%">
                    <a href="#" class="<?=$class_box?>" id="fecha_aire_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>">
                      <?php //if($capitulos[$i]['fecha_aire']!="0000-00-00"){
                        //$valor = date('d-M-Y',strtotime($capitulos[$i]['fecha_aire']));
                        /*echo $valor;
                      }else{
                        $valor = "-";
                        echo $valor;
                      }?></a> 
                    <div class="hide_box name_capitulo">
                      <span class="close_box"></span>
                      <div style="width:100%; height:40px">
                      <form action="#" class="custom">
                        <input type="text" placeholder="DD-MM-YYYY" value="<?=$valor?>" id="fecha_aire_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" class="datepicker online_cap">
                        
                      </form>
                    </div>
                    <div class="align_left">
                          <a href="#" class="cancel_icon close_box">Cancelar</a>
                          <a class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']*/?>" opcion="fecha_aire">Guardar</a>
                        </div>
                    </div>
                  </td>-->
                  <!--FIN CELDA FECHA AL AIRE-->

                  <!--VALIDACION DELA CLASE SEGUN ESTADO-->
                  <?php 
                  $descripcion = "";
                  $class_cap="";
                  if(isset($capitulos[$i])){
                    $descripcion = $capitulos[$i]['descripcion'];
                    switch($capitulos[$i]['descripcion']){
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
                  <!--FIN VALIDACION DELA CLASE SEGUN ESTADO-->

                  <!--INICIO CELDA ESTADO-->
                  <td width="15%" class="<?=$class_cap?>"><?=$descripcion?></td>
                  <!--FIN CELDA ESTADO-->

                  <!--INICIO CELDA LIBRETO-->
                  <td width="15%">
                    <?php 
                        if($capitulos[$i]['estado']!=6){
                        if($capitulos[$i]['libreto']==""){ ?>
                          <?php if($usuario_permisos=="write"){?>
                            <a href="#" class="<?=$class_box?>" id="libreto_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>"><?php echo lang('global.subir'); ?></a>
                          <?php } ?>
                        <?php } else{?>
                          <a href="<?=base_url().$capitulos[$i]['libreto']?>"><?php echo lang('global.ver'); ?></a> 
                          <?php if($usuario_permisos=="write"){?>/ 
                            <a href="#" class="<?=$class_box?>" id="libreto_text<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>"><?php echo lang('global.cambiar'); ?></a>
                          <?php } ?>
                    <?php } } ?> 

                    <div class="hide_box capitulo_box">
                        <span class="close_box"></span>
                        <?php echo form_open_multipart($idioma.'libretos/ingresar_libreto','id="subirLibreto'.$capitulos[$i]['id_capitulo'].'", class="custom"');?>
                        <!--<?php if($capitulos[$i]['libreto']!=""){ ?>
                        <a href="<?=base_url().$capitulos[$i]['libreto']?>" target="_blank">Descargar</a>
                        <?php } ?>-->
                        <div class="columns tow">
                          <input type="hidden" name="id_produccion" value="<?=$produccion[0]->id_produccion?>">
                          <input type="hidden" name="id_capitulo" value="<?=$capitulos[$i]['id_capitulo']?>">
                          <input type="file" name="libreto" id="libreto<?=$capitulos[$i]['id_capitulo']?>">
                        </div>
                          <!--<textarea id="libreto_<?=$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo'])?>" name="valor" rows="2" class="required" style="min-height: 50px;"><?=$capitulos[$i]['libreto']?></textarea>-->
                        <div class="align_left">
                          <a href="#" class="cancel_icon close_box"><?php echo lang('global.cancelar'); ?></a>
                          <a href="#" class="save_script save_icon" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>"><?php echo lang('global.guardar'); ?></a>
                          <!--<a href="#" class="save_icon" idproduccion="<?=$produccion[0]->id_produccion?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>" opcion="libreto">Guardar</a>-->
                        </div>
                        <?php echo form_close();?> 
                    </div>
                  </td>
                  <!--FIN CELDA LIBRETO-->

                  <!--CELDA DE ALERTAS-->
                  <td width="31%">
                    <?php
                    $cadena_alertas = "";
                    $contador = 0;
                    $personajes = $this->model_capitulos->personajes_capitulos($capitulos[$i]['id_capitulo']);
                    $personajes = explode(',', $personajes[0]['cnt']);
                    if($produccion[0]->numero_locaciones < $capitulos[$i]['cantidad_locaciones'] AND $produccion[0]->numero_locaciones>0){
                      $cadena_alertas.= "MAX. LOCACIONES ".$produccion[0]->numero_locaciones."(".$capitulos[$i]['cantidad_locaciones'].")";
                      $contador++;
                    }
                    if($produccion[0]->numero_set<$capitulos[$i]['cantidad_sets'] AND $produccion[0]->numero_locaciones < $capitulos[$i]['cantidad_locaciones'] AND $produccion[0]->numero_set>0 AND $produccion[0]->numero_locaciones>0){
                      $cadena_alertas.= ' - ';
                    }
                    if($produccion[0]->numero_set<$capitulos[$i]['cantidad_sets'] AND $produccion[0]->numero_set>0){
                      $cadena_alertas.= "MAX. SETS ".$produccion[0]->numero_set."(".$capitulos[$i]['cantidad_sets'].") - ";
                    }
                    for ($k=0; $k < count($personajes); ++$k) { 
                        $temp= explode('-', $personajes[$k]);
                        if(isset($temp[1])){
                          if($temp[1]==1){
                            if($produccion[0]->numero_protagonistas < $temp[0] AND $produccion[0]->numero_protagonistas>0){
                              $cadena_alertas.= "MAX. PROTAGONISTA ".$produccion[0]->numero_protagonistas."(".$temp[0].") - ";
                            }
                          }

                          if($temp[1]==2){
                            if($produccion[0]->numero_figurantes < $temp[0] AND $produccion[0]->numero_figurantes>0){
                              $cadena_alertas.= "MAX. FIGURANTE ".$produccion[0]->numero_figurantes."(".$temp[0].") - ";
                            }
                          }

                          if($temp[1]==3){
                            if($produccion[0]->numero_repartos < $temp[0] AND $produccion[0]->numero_repartos>0){
                              $cadena_alertas.= "MAX. REPARTO ".$produccion[0]->numero_repartos."(".$temp[0].") - ";
                            }
                          }
                          
                          if($temp[1]==4){
                            if($produccion[0]->numero_extras < $temp[0] AND $produccion[0]->numero_extras>0){
                              $cadena_alertas.= "MAX. EXTRA ".$produccion[0]->numero_extras."(".$temp[0].") - ";
                            }
                          }
                        }
                    }

                    if($capitulos[$i]['estado']!=1 AND $capitulos[$i]['libreto']==""){
                      $cadena_alertas.= "NO LIBRETO";
                    }

                    $vehiculos = $this->model_capitulos->vehiculos_capitulo($capitulos[$i]['id_capitulo']);
                    if($vehiculos){
                      if($produccion[0]->numero_vehiculos < ($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background']) AND $produccion[0]->numero_vehiculos>0){
                          $cadena_alertas.= "MAX. vehiculos ".$produccion[0]->numero_vehiculos."(".($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background']).") - ";
                      }
                    }

                    $nuevas_locaciones = $this->model_capitulos->nuevas_locaciones($capitulos[$i]['id_capitulo'],$capitulos[$i]['id_produccion']);
                    if($vehiculos){
                      if($produccion[0]->locaciones_nuevas < $nuevas_locaciones[0]['locaciones_nuevas'] AND $produccion[0]->locaciones_nuevas>0){
                          $cadena_alertas.= "MAX. LOCACIONES NUEVAS ".$produccion[0]->locaciones_nuevas."(".$nuevas_locaciones[0]['locaciones_nuevas'] .") - ";
                      }
                    }

                    ?><span class="red has-tip tip-centered-top tooltip_info" style="font-weight: normal;" title="<?=$cadena_alertas?>">
                      <?php
                        echo Libretos::corta_palabra($cadena_alertas,30);
                        if(strlen($cadena_alertas)>=30){
                                echo '...';
                        }
                      ?>
                    </span>
                  </td>
                  <!--FIN CELDA DE ALERTAS-->


                  <!--BOTONES DE CANCELAR Y ELIMINAR-->
                  <td width="25%">
                    <!--NO MOSTRAR SI EL CAPITULO YA ESTA CANCELADO-->
                  <?php if($usuario_permisos=="write" and $produccion['0']->estado!=2){ ?>
                      <?php if ($capitulos[$i]['descripcion']=="En Progreso" and $capitulos[$i]['descripcion']!="Cancelado"): ?>
                        <a href="<?=base_url($idioma.'libretos/desglosar_libreto/'.$produccion[0]->id_produccion.'/'.$capitulos[$i]['id_capitulo'])?>" class="desglosa_cap"><?php echo lang('global.desglosar'); ?></a>/  
                      <?php endif ?>
                      <?php if($capitulos[$i]['descripcion']!="Cancelado" AND $capitulos[$i]['planes_producidos']>0){ ?>
                      <a href="#" class="cancel_cap" idproduccion="<?=$produccion[0]->id_produccion?>" planes-producidos="<?=$capitulos[$i]['planes_producidos']?>" planes-asignados="<?=$capitulos[$i]['planes_asignados']?>" 
                       data-noproducudos="<?php echo $capitulos[$i]['numero_escenas']-$capitulos[$i]['escenas_producidas'] ?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>"><?php echo lang('global.cancelar'); ?></a>  
                      <!-- BOTON DESGLOSE MANUAL -->
                      <?php } else{?>
                      <!-- FIN NO MOSTRAR SI EL CAPITULO YA ESTA CANCELADO-->
                      <a href="#" class="delete_cap" idproduccion="<?=$produccion[0]->id_produccion?>" planes-asignados="<?=$capitulos[$i]['planes_asignados']?>" idcapitulo="<?=$capitulos[$i]['id_capitulo']?>"><?php echo lang('global.eliminar'); ?></a>
                      <!--FIN BOTONES DE CANCELAR Y ELIMINAR-->
                    </td>

                      <?php } ?>
                   <?php } ?>   
                </tr>
              </table>
            </td>
          </tr>
          <?php } ?>
          <!--FINN DE FILAS-->
          <tr class="load_more_conainer">
            <td colspan="4">
              <img src="<?php echo base_url(); ?>images/loader.gif" alt="">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  <!--BOTON PARA CARGAR MAS ESCENAS-->
  <?php 
    $cadena=""; 
    if($capitulos){
    if(count($capitulos)>=$capitulos_complete2){
      $cadena="display:none;";
    } 
   }else{
    $cadena="";
   } 
  ?>
  <input type="button" style="width:100%!important;<?=$cadena?>" id="load" value="<?php echo lang('global.ver_mas_libretos'); ?>" class="button" data-idprod="<?=$produccion[0]->id_produccion?>">
  <!--FIN BOTON PARA CARGAR MAS ESCENAS--> 
  </div>

</div>
</div>

<!--CAMPOS OCULTOS PARA CONOCER EL RANGO DE CAPITULOS ACTUAL-->
<input id="limit_inf" type="hidden"  value="0">
<input id="limit_sup" type="hidden" value="<?=count($capitulos)?>">
<input id="limit_tot" type="hidden" value="<?=sizeof($capitulos_complete)?>">
<input id="idproduccion" type="hidden" value="<?=$produccion[0]->id_produccion?>"> 
<input id="min_date_cap" type="hidden" value="<?=date('d-M-Y',strtotime($produccion[0]->inicio_grabacion))?>"> 
<!--FIN CAMPOS OCULTOS PARA CONOCER EL RANGO DE CAPITULOS ACTUAL-->

<div id="now"></div>