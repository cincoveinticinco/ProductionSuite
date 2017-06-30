<?php $idioma = $this->lang->lang().'/'; ?>
<?php 
 $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,13,3);
 $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,14,3); 
 $aprobacion_firma =  $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,10,3);
 $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,17,3);
?>

<?php  $valida_contrato = "" ?>
<?php  $valida_contrato_firmado = "" ?>

<?php if ($solicitud[0]->contrato=="" AND $solicitud[0]->contrato_personal==""): ?>  
  <?php $valida_contrato = "PENDIENTE"; ?> 
<?php endif ?>

<?php if ($solicitud[0]->id_estado != 1 AND $solicitud[0]->id_estado != 11): ?>
  <?php $valida = true; 

  if ($solicitud[0]->id_estado ==5 AND $valida_contrato!="" ): ?>
      <?php $valida = false; ?>

  <?php endif ?> 

  <?php if ($solicitud[0]->id_estado ==3 AND $tipo_usuario==7 AND $aprobacion_juridica): ?>
      <?php $valida = false; ?>

  <?php endif ?> 

  <?php if ($solicitud[0]->id_estado ==3 AND $tipo_usuario==8 AND $aprobacion_finanzas): ?>
      <?php $valida = false; ?>

  <?php endif ?> 

  <?php if ($solicitud[0]->id_estado ==10 AND $solicitud[0]->fecha_contrato_firmado==""): ?>
      <?php $valida = false; ?>

  <?php endif ?> 

  <?php if ($solicitud[0]->id_estado==16): ?>
      <?php $valida = false; ?>

  <?php endif ?> 
  
  <?php if ($permisos!="write"): ?>
      <?php $valida = false; ?>
  <?php endif ?>
<?php else: ?>

   <?php $valida = false; ?>
<?php endif ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.detalle_solicitud') ?>
</div>

<nav>
    <ul class="nav_post nav_casting">
       <?php if($type==""){ ?>
        <li><a href="<?=base_url($idioma.'casting/solicitudes')?>" class="active"><?php echo lang('global.volver') ?></a></li>
        <?php }else{ ?>
          <li><a href="<?=base_url($idioma.'casting/personajes')?>" class="active"><?php echo lang('global.volver') ?></a></li>
        <?php } ?>
        <li><a href="<?=base_url($idioma.'casting/crear_solicitud')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_solicitud') ?></a></li>
        <li><a href="<?=base_url($idioma.'casting/solicitudes')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.ver_solocitudes') ?></a></li>
    </ul>
</nav>

<div id="inner_content" >
  <div class="contentSolicitud">
    <div class="row">
        <div class="columns ten">
          <span class="estado_solicitud<?=$solicitud[0]->id_estado?>"> <?php echo lang('global.estado') ?>: <span id="estado_solicitud_span" style="display: inline-block;width: 50%;">
           <?php if ($solicitud[0]->id_estado==20): ?>
              <?php if (!$aprobacion_firma): ?>
                <?=lang('global.proceso_de_firma')?>
              <?php endif ?>
              <?php if (!$aprobacion_documentos AND !$aprobacion_firma): ?>
                  <?='-'?>
              <?php endif ?>
              <?php if (!$aprobacion_documentos): ?>
                <?=lang('global.recoleccion_de_documentos') ?>
              <?php endif ?><?php if ($solicitud[0]->tipo == 2): ?> / OTRO SI <?php endif ?>
            <?php else: ?>
              <?=strtoupper($solicitud[0]->estado)?><?php if ($solicitud[0]->tipo == 2): ?> / OTRO SI <?php endif ?>
            <?php endif ?>
          </span>

           <span style="">
              <?php echo lang('global.responsable') ?>:&nbsp;
              <?php 
              $continuar=1;
           
              if(($solicitud[0]->id_estado==3) AND ($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1" OR $solicitud[0]->id_forma_pago==1 OR $solicitud[0]->id_tipo_moneda==2)){
             
                $responsable =  strtoupper($solicitud[0]->responsable); 
                if($aprobacion_juridica){
                  $responsable = str_replace("JURíDICA -", '',strtoupper($responsable));
                  $responsable = str_replace("JURíDICA", '',strtoupper($responsable));
                  
                }

                if($aprobacion_finanzas){
                  $responsable = str_replace(strtoupper("- FINANZAS"), '', $responsable );
                  $responsable = str_replace("FINANZAS", '',strtoupper($responsable));
                }
                
                if($solicitud[0]->roles==1 or $solicitud['0']->id_nacionalidad==13){


                 $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                 $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                 $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));

                }else{
                   $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('Coordinador de contrato'), '', strtoupper($responsable));
                }

                echo str_replace('COORDINADOR DE CONTRATO -', '', $responsable);
                if($coordinado==1){
                  $continuar=0;
                }

              }else if ($solicitud[0]->id_estado==20) {

                  if (($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1") OR $solicitud[0]->id_forma_pago==1  OR $solicitud[0]->id_tipo_moneda==2) {
                    echo lang('global.juridica');
                    if($coordinado==1){
                        $continuar=0;
                      }
                  }else{
                    echo lang('global.coordinadora_de_contrado');
                  }

              }else{


                if($solicitud[0]->id_estado==5 AND ($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1" OR $solicitud[0]->id_forma_pago==1  OR $solicitud[0]->id_tipo_moneda==2)){
                  if($coordinado==1){
                    $continuar=0;
                  }
                    echo lang('global.coordinadora_de_contrado');
                }else{

                  if ($solicitud[0]->id_estado!=19) {

                    $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($solicitud[0]->responsable));
                    $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('- Abogada RTI'), '', strtoupper($responsable));
                    $responsable = str_replace(strtoupper('Abogada RTI'), '', strtoupper($responsable));
                     
        
                    if($solicitud['0']->id_estado==3 and $solicitud['0']->id_nacionalidad==13){
                         $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                    }
                    
                    echo $responsable;
                  }elseif($solicitud['0']->id_nacionalidad!=13 or $solicitud['0']->condiciones_especiales!=""){
                     if($solicitud[0]->roles!=1){
                         $responsable = str_replace(strtoupper('- Productor'), '', strtoupper($solicitud['0']->responsable));
                         $responsable = str_replace(strtoupper('Productor'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('Productor -'), '', strtoupper($responsable));
                     }else{
                         $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($solicitud['0']->responsable));
                         $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('JURÍDICA -'), '', strtoupper($responsable));
                         $responsable = str_replace(strtoupper('-'), '', strtoupper($responsable));
                     }
                         
                    echo $responsable.'';

                  }else{
                      if($solicitud[0]->roles==3 and $solicitud[0]->id_nacionalidad!=13){
                        $responsable = str_replace(strtoupper('Jurídica -'), '', strtoupper($solicitud[0]->responsable));
                        echo $responsable;
                      }else{
                         echo strtoupper($solicitud[0]->responsable);
                      }
                  }
                }
              }
              ?>
          </span>
          </span>

        </div>
        <div class="column two no-padding-left">
          <?php if (($solicitud[0]->id_estado == 5 OR $solicitud[0]->id_estado == 6 
            OR $solicitud[0]->id_estado == 10 OR $solicitud[0]->id_estado == 3 OR $solicitud[0]->id_estado == 2 
            OR $solicitud[0]->id_estado == 17 OR $solicitud[0]->id_estado == 19 OR $solicitud[0]->id_estado == 20) AND $permisos=="write"): ?>
            <div href="#" class="column six no-padding">
             <?php if ($valida and $continuar==1): ?>
              <a href="#" class="button btn_orange rechazar_solicitud_button_up twelve up_button"><?php echo lang('global.rechazar') ?></a></div>
             <?php endif ?>  
          <?php endif ?>
          <?php if ($solicitud[0]->id_estado == 1):?>
            <div href="#" class="column six no-padding">
              <a href="#" id="aprobar_solicitud_up" class="button twelve  up_button"><?php echo lang('global.aprobar') ?></a>
            </div>
          <?php endif ?>
         
          <?php if ($valida and $continuar==1): ?>
            <div href="#" class="column six no-padding">
              <a href="#" id="aprobar_solicitud_up" class="button twelve up_button" data-idEstado="<?php echo $solicitud[0]->id_estado; ?>" ><?php echo lang('global.aprobar') ?></a>
            </div>
          <?php endif ?>
        </div>
        
    </div>
    <div class="clr"></div>
    <!-- CAMPO MENOR DE EDAD O EXTRANJERO -->
    <?php if ($solicitud[0]->id_nacionalidad!=13 OR $solicitud[0]->edad<18): ?>
      <div class="row"> 
        <div class="column twelve">
          <span class="alertGreen" id="situacion_especial">
            <?php if ($solicitud[0]->id_nacionalidad!=13): ?>
            <?php echo lang('global.extranjero') ?>
            <?php else: ?>
            <?php echo lang('global.menor_edad') ?>
            <?php endif ?>
          </span>
        </div>
      </div>
    <?php endif ?>
    <!-- CAMPO MENOR DE EDAD O EXTRANJERO -->
    <div class="row">
      <div class="column twelve">
        <input type="hidden" value="<?=$solicitud[0]->tipo?>" id="solicitud_tipo">    
        <table class="grupo-datos">
          <caption class="detalleSolicitud"><?php echo lang('global.detalle_solicitud') ?></caption>
          <tr>
            <td>  
                <label><?php echo lang('global.solicitud') ?> #:</label>
                <span id="id_solicitud_span">
                    <?php $solicitud_anexa = false; ?>
                    <?php if ($solicitud[0]->tipo == 2 ): ?>
                      <?php $solicitud_anexa = $this->model_casting->solicitud_id($solicitud[0]->id_solicitud_anexa);  ?>
                      <?php echo Casting::completar_id($solicitud[0]->id_solicitud_anexa); ?> - 
                      <?php echo Casting::numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);?>
                    <?php else: ?>
                      <?php echo Casting::completar_id($solicitud[0]->id_solicitud); ?>
                    <?php endif ?>
                </span>
            </td>
            <td colspan="2">
                <label><?php echo lang('global.persona_solicitante_contrato') ?>:</label>
                <?=$solicitud[0]->nombre_usuario?>
            </td>
            <td colspan="2">
                <label><?php echo lang('global.area_solicitante') ?>:</label>
                <?=$solicitud[0]->area_solicitante?>
            </td>
            <td colspan="3">
                 <label><?php echo lang('global.produccion') ?></label>
                 <?=$solicitud[0]->produccion?>
            </td>
          </tr>
          <tr>
            <td> 
              <label><?php echo lang('global.fecha_inicio') ?>:</label>
              <?=date("d-M-y",strtotime($solicitud[0]->fecha_inicio))?>
            </td>
            <td <?=($solicitud_anexa AND $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final_otro_si) ? 'style="background: lightyellow;"' : '' ?> >
               <label>
               <?php if ($solicitud[0]->tipo==2 and $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) { ?> 
                    <?php echo lang('global.fin_contrato_anterior') ?>:</label>
               <?php }else{ ?>
                <?php echo lang('global.fin_contrato') ?>:</label>
               <?php } ?>
               
          
               <?=date("d-M-y",strtotime($solicitud[0]->fecha_final))?>
            </td>
            <?php if ($solicitud[0]->tipo==2 and $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final_otro_si) { ?>
                 <td <?php if($solicitud[0]->fecha_final!=$solicitud[0]->fecha_final_otro_si){ ?> style="background: lightyellow;" <?php } ?> >
                  <label><?php echo lang('global.fin_contrato_otro_si') ?>:</label>
                    <?php if($solicitud[0]->fecha_final_otro_si){ ?>
                      <?=date("d-M-y",strtotime($solicitud[0]->fecha_final_otro_si))?>
                    <?php }else{ ?>
                      --
                    <?php } ?>
                  </td>
            <?php } ?>

            <?php if ($solicitud[0]->tipo==2 and $solicitud[0]->fecha_final!=$solicitud[0]->fecha_final_otro_si) { ?>
               <td colspan="3">
            <?php }else{ ?>
             <td colspan="4">
            <?php } ?>
              <label><?php echo lang('global.objeto_del_contrato') ?>:</label>
              <?=$solicitud[0]->objeto_contrato?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.lugar_de_presentacion_del_servicio') ?>:</label>
              <?=$solicitud[0]->lugar_servicio?>
            </td>
          </tr>
          <tr>
          <?php 
            $cols =3;
            if ($solicitud[0]->tipo==2) {
              $cols = 2;
            }

          ?>
            <td colspan="<?php if($solicitud[0]->tipo==2 and $solicitud[0]->honorarios!=$solicitud[0]->honorarios_otro_si
            ) { echo 1; }else{ echo 2;} ?>" <?=($solicitud_anexa AND ($solicitud_anexa[0]->tipo_moneda != $solicitud[0]->tipo_moneda || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios || $solicitud_anexa[0]->honorarios_letras != $solicitud[0]->honorarios_letras)) ? 'style="background: lightyellow;"' : '' ?>>
              <label>
              <?php if($solicitud[0]->tipo==2 and $solicitud[0]->honorarios!=$solicitud[0]->honorarios_otro_si){ ?>
                 <?php echo lang('global.honorarios_anterior') ?>:</label>
              <?php }else{ ?>
                   <?php echo lang('global.honorarios') ?>:</label>
              <?php } ?>
              
              <?php if($solicitud[0]->id_tipo_moneda==2){
                echo  'U$';   
              }else{
                echo  '$';   
              }
               ?>
              <?=number_format((double)$solicitud[0]->honorarios, 2, '.', ",")?>
            </td>
            <td colspan="<?php if($solicitud[0]->tipo==2 and ($solicitud[0]->honorarios_letras!=$solicitud[0]->honorarios_letras_otro_si)
            ) { echo 1; }else{ echo 2;} ?>" <?=($solicitud_anexa AND ($solicitud_anexa[0]->tipo_moneda != $solicitud[0]->tipo_moneda || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios || $solicitud_anexa[0]->honorarios_letras != $solicitud[0]->honorarios_letras)) ? 'style="background: lightyellow;"' : '' ?>>
              <label>
               <?php if($solicitud[0]->tipo==2 and ($solicitud[0]->honorarios_letras!=$solicitud[0]->honorarios_letras_otro_si or $solicitud[0]->honorarios!=$solicitud[0]->honorarios_otro_si)){ ?>
                 <?php echo lang('global.honorarios_en_letras_anterior') ?>
               <?php }else{ ?>
               <?php echo lang('global.honorarios_en_letras') ?>
               <?php } ?>
              
              :</label>
              <?php //if($solicitud[0]->honorarios_letras==1){  
              if($solicitud[0]->id_tipo_moneda==1){ 
                $var_de=" ";
                  $string = $solicitud[0]->honorarios_letras;
                  $pieces = explode(' ', $string);
                  $last_word = array_pop($pieces);
                  if($last_word=='millon' || $last_word=='millones'){
                    $var_de=" de ";
                  }
                ?>
                  <?=$solicitud[0]->honorarios_letras.$var_de.$solicitud[0]->tipo_moneda?>
              <?php }else{ ?>
                  <?=$solicitud[0]->honorarios_letras.' '.$solicitud[0]->tipo_moneda?>
              <?php } ?>
            </td>
            
            <?php if ($solicitud[0]->tipo==2 and $solicitud[0]->honorarios!=$solicitud[0]->honorarios_otro_si) { ?>
            <td  <?=($solicitud[0]->honorarios != $solicitud[0]->honorarios_otro_si) ? 'style="background: lightyellow;"' : '' ?>>
              <label><?php echo lang('global.honorarios_otro_si') ?>:</label>
              <?php if($solicitud[0]->id_tipo_moneda==2){
                echo  'U$';   
              }else{
                echo  '$';   
              }
               ?>
              <?=number_format((double)$solicitud[0]->honorarios_otro_si, 2, '.', ",")?>
            </td>
            <td <?=($solicitud[0]->honorarios_letras != $solicitud[0]->honorarios_letras_otro_si) ? 'style="background: lightyellow;"' : '' ?>>
              <label><?php echo lang('global.honorarios_en_letras') ?>:</label>
               <?php if($solicitud[0]->honorarios_letras==1){ ?>
                  <?=$solicitud[0]->honorarios_letras_otro_si.' de '.$solicitud[0]->tipo_moneda?>
              <?php }else{ ?>
                  <?=$solicitud[0]->honorarios_letras_otro_si.' '.$solicitud[0]->tipo_moneda?>
              <?php } ?>
              
            </td>
            <?php } ?>


            <td colspan="<?php echo $cols ?>" <?=($solicitud_anexa AND $solicitud_anexa[0]->forma_pago != $solicitud[0]->forma_pago) ? 'style="background: lightyellow;"' : '' ?> >
               <label><?php echo lang('global.tipo_de_pago') ?>:</label>
                <?=$solicitud[0]->forma_pago?>
            </td>
            <td>
                <label class="condicionesEsp" data-condicionesEsp="<?php if($solicitud[0]->condiciones_especiales){ echo "1"; }else{ echo "0"; }?><"><?php echo lang('global.condiciones_esp') ?>: </label><span><?php if($solicitud[0]->condiciones_especiales){ echo "SI"; }else{ echo "NO"; }?></span>
            </td>
            <?php if ($solicitud[0]->tipo==2) { ?>
            <td>
              <label><?php echo lang('global.desde') ?>: </label>
              <span>

                <?php if($solicitud[0]->mes_otro_si){ ?>
                  <?=date("M-Y",strtotime($solicitud[0]->mes_otro_si))?>
                <?php }else{ ?>
                    --
                <?php } ?>
               </span>
            </td>
            
            <?php } ?>
          </tr>
          <?php if ($solicitud[0]->condiciones_especiales): ?>
          <tr>
            <td colspan="8">
              <label><?php echo lang('global.condiciones_especiales') ?>:</label>
              <?=$solicitud[0]->condiciones_especiales?>
            </td>
          </tr>
          <?php endif ?>

          <?php if ($solicitud[0]->tipo==2): ?>
            <tr>
              <td colspan="8">
              <label><?php echo lang('global.razon') ?> otro si:</label>
              <?=$solicitud[0]->razon_otro_si?>
              </td>
            </tr>
          <?php endif ?>
        </table>
        <table class="grupo-datos">
          <caption class="detalleSolicitud"><?php echo lang('global.dato_del_personaje') ?></caption>
          
          <tr>
           
             <td colspan="1">
                <label><?php echo lang('global.rol') ?></label>
                <?php if ($elementos_solicitud): ?>
                    <?php foreach ($elementos_solicitud as $elemento_solicitud): ?>
                      <?=$elemento_solicitud->rol?>
                    <?php endforeach ?>
                  <?php endif ?>
            </td>
             <td colspan="2">  
                <label><?php echo lang('global.personajes') ?></label>
                  <?php if ($elementos_solicitud): ?>
                    <?php foreach ($elementos_solicitud as $elemento_solicitud): ?>
                      <?=$elemento_solicitud->nombre?>
                    <?php endforeach ?>
                  <?php endif ?>
            </td>
            <td colspan="5">
              <label><?php echo lang('global.apariciones') ?>:</label>
              <strong><?=  count(explode(',',$solicitud[0]->libretos)); ?> <?php echo lang('global.libretos') ?></strong>
              (<?php echo lang('global.desglosado') ?> <?= str_replace(',', ', ', $solicitud[0]->libretos)?>)
            </td>
            
          </tr>
        </table>
        <table class="grupo-datos">
          <caption class="detalleSolicitud"><?php echo lang('global.dato_del_contratista_talento') ?></caption>
          
          <tr>
            <td colspan="4">
                <label><?php echo lang('global.nombre_actor') ?></label>
                <?=$solicitud[0]->actor?>
            </td>
            <td colspan="2">
              <label data-nacionalida="<?=$solicitud[0]->id_nacionalidad?>" class="id_nacionalidad">Nacionalidad</label>
              <?=$solicitud[0]->nacionalidad?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.documento_identidad') ?></label>
              <?=$solicitud[0]->tipo_documento?> - <?=$solicitud[0]->documento?>
            </td>
          </tr>
          <tr>
            <td colspan="4">
               <label><?php echo lang('global.direccion_ciudad_pais') ?></label>
               <?=$solicitud[0]->direccion?>, <?=$solicitud[0]->ciudad?>, <?=$solicitud[0]->pais?>
            </td>
            <td>
              <label><?php echo lang('global.telefono_fijo') ?></label>
              <?=$solicitud[0]->telefono_fijo?>
            </td>
            <td>
              <label><?php echo lang('global.telefono_movil') ?></label>
              <?=$solicitud[0]->telefono_movil?>
            </td>
            <td colspan="2">
               <label><?php echo lang('global.correo_electronico') ?>:</label>
               <?=$solicitud[0]->email?>
            </td>
          </tr>
         
        </table>
         <table class="grupo-datos">
           <tr>
            <td colspan="2">
              <label><?php echo lang('global.contacto_contractual') ?>:</label>
              <?=$solicitud[0]->contacto_nombre?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.telefono_fijo') ?>:</label>
              <?=$solicitud[0]->contacto_telefono?>
            </td>
            <td colspan="2">
                <label><?php echo lang('global.telefono_movil') ?>:</label>
                <?=$solicitud[0]->contacto_telefono_movil?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.correo_electronico') ?>:</label>
              <?=$solicitud[0]->contacto_email?>
            </td>
          </tr>
           <tr>
            <td colspan="2">
              <label>manager:</label>
              <?=$solicitud[0]->nombre_manager?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.telefono_fijo') ?>:</label>
              <?=$solicitud[0]->telefono_fijo_manager?>
            </td>
            <td colspan="2">
                <label><?php echo lang('global.telefono_movil') ?>:</label>
                <?=$solicitud[0]->telefono_movil_manager?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.correo_electronico') ?>:</label>
              <?=$solicitud[0]->email_manager?>
            </td>
          </tr>
          
          <?php if ($solicitud[0]->id_sociedad): ?>
          <tr>
            <td colspan="2">
              <label><?php echo lang('global.razon_social') ?>:</label>
              <?=$solicitud[0]->razon_social_sociedad?>
            </td>
            <td colspan="2">
              <label>NIT:</label>
              <?=$solicitud[0]->nit_sociedad?>
            </td>
            <td colspan="2">
                <label><?php echo lang('global.representante_legal') ?>:</label>
                <?=$solicitud[0]->representante_legal?>
            </td>
            <td colspan="2">
              <label><?php echo lang('global.documento_representante') ?>:</label>
              <?=$solicitud[0]->documento_representante?>
            </td>
          </tr>
          <?php endif ?>

          
         </table>
         <table class="grupo-datos">
           <caption class="detalleSolicitud"><?php echo lang('global.documentos') ?></caption>
           <tr>
             <td colspan="8">
                <?php if ($documentos_actor): ?>
                  <?php if ($solicitud[0]->id_estado==20 || $solicitud[0]->id_estado==11 || $solicitud[0]->id_estado==10 || $solicitud[0]->id_estado==17): ?>
                    <div class="content-doc">
                      <?php echo form_open_multipart($idioma.'casting/guardar_documento_actor','class="guardar_documento_actor_form"'); ?>
                        <!-- INPUTS OCULTOS -->
                        <input type="hidden" name="idactor" value="<?=$solicitud[0]->id_actor?>">
                        <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                        <?php if(isset($documentos_actor[1]->url)){ ?>
                        <input type="hidden" name="documento_original" value="<?=$documentos_actor[1]->url?>">
                        <?php }else{ ?>
                                 <input type="hidden" name="documento_original" value="null">
                        <?php } ?>
                          <?php if(isset($documentos_actor[1]->id)){ ?>
                        <input type="hidden" name="iddocumento" value="<?=$documentos_actor[1]->id?>">
                        <?php }else{ ?>
                 
                        <input type="hidden" name="iddocumento" value="null">
                        <?php } ?>
                        <input type="hidden" name="tipo" value="2">
                        <!-- FIN INPUTS OCULTOS -->
                        <label for="">ARL:</label>
                        <!-- INPUTS -->
                        <div class="content_form">
                        <?php if(isset($documentos_actor[1]->url)){ ?>
                          <input type="text" name="nombre" value="<?=$documentos_actor['1']->descripcion?>"  class="field_document_actor" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                          <?php }else{ ?>
                              <input type="text" name="nombre" value=""  class="field_document_actor" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                          <?php } ?>
                          <select name="activo" id="activo" class="field_document_actor" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?>>
                            <option><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($estados_entidad as $estado_entidad): ?>
                              <option value="<?=$estado_entidad->id?>"
                                 <?php if(isset($documentos_actor[1]->url)){ ?>
                                      <?php if ($documentos_actor['1'] AND $documentos_actor['1']->id_estado_entidad == $estado_entidad->id): ?>
                                        selected 
                                      <?php endif ?>
                                 <?php }?>
                              ><?=$estado_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="clase" id="clase"  class="field_document_actor" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                            <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($clases_entidad as $clase_entidad): ?>
                              <option value="<?=$clase_entidad->id?>"
                                   <?php if(isset($documentos_actor[1]->url)){ ?>
                                    <?php if ($documentos_actor['1'] AND $documentos_actor['1']->id_clase_entidad == $clase_entidad->id): ?>
                                      selected 
                                    <?php endif ?>
                                  <?php } ?>  
                              ><?=$clase_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                        <!-- FIN INPUTS -->
                        <!-- BOTONES -->
                        <?php 
                        $label = lang('global.agregar');
                        if(isset($documentos_actor[1]->url)){
                            if ($documentos_actor['1']->url!=""){ 
                              $label = lang('global.modificar');
                            } 
                        }    
                        ?>
                        <div class="btns" >
                          <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                            <a class="button-file modificar" href="#" data-documento="arl_documento"><?=$label?></a>
                            <input type="file" name="documento" id="arl_documento" style="display:none"  class="field_document_actor">
                          <?php endif ?>  
                          <?php  if(isset($documentos_actor[1]->url)){ ?>
                                <?php if ($documentos_actor['1']->url!=""): ?>
                                  <a href="<?=base_url($documentos_actor['1']->url)?>" target="_blank" class="button-file"><?php echo lang('global.ver') ?></a>
                                <?php endif ?>
                          <?php } ?>      
                        
                        <?php if ($permisos=="write"): ?>
                            <button class="guardar_documento_actor second-button-file" style="display:none"><?php echo lang('global.guardar') ?></button>
                        <?php endif ?>
                        </div>
                        <!-- FIN BOTONES -->
                      <?php echo form_close(); ?>
                    </div>
                    <div class="content-doc">
                      <?php echo form_open_multipart($idioma.'casting/guardar_documento_actor','class="guardar_documento_actor_form"'); ?>
                        <!-- INPUTS OCULTOS -->
                        <input type="hidden" name="idactor" value="<?=$solicitud[0]->id_actor?>">
                        <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                        <?php if(isset($documentos_actor[2]->url)){ ?>
                        <input type="hidden" name="documento_original" value="<?=$documentos_actor[2]->url?>">
                        <?php }else{ ?>
                        <input type="hidden" name="documento_original" value="null">
                       
                        <?php } ?>

                        <?php if(isset($documentos_actor[2]->id)){ ?>
                        <input type="hidden" name="iddocumento" value="<?=$documentos_actor[2]->id?>">
                        <?php }else{ ?>
                         <input type="hidden" name="iddocumento" value="null">
                       
                        <?php } ?>
                        
                        <input type="hidden" name="tipo" value="3">
                        <!-- FIN INPUTS OCULTOS -->
                        <label for="">EPS:</label>
                        <!-- INPUTS -->
                        <div class="content_form">
                          <?php if(isset($documentos_actor[2]->url)){ ?>
                          <input type="text" name="nombre" class="field_document_actor" value="<?=$documentos_actor['2']->descripcion?>" 
                          <?php }else{ ?>
                          <input type="text" name="nombre" class="field_document_actor" value="" 
                          <?php } ?>

                          <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                          <select name="activo" class="field_document_actor" id="eps_activo" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                            <option><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($estados_entidad as $estado_entidad): ?>
                              <option value="<?=$estado_entidad->id?>" 
                              <?php if ($documentos_actor['2'] AND $documentos_actor['2']->id_estado_entidad == $estado_entidad->id): ?>
                                selected 
                              <?php endif ?>
                              ><?=$estado_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="clase" class="field_document_actor" id="eps_clase" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                            <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($clases_entidad as $clase_entidad): ?>
                              <option value="<?=$clase_entidad->id?>" 
                                <?php if ($documentos_actor['2'] AND $documentos_actor['2']->id_clase_entidad == $clase_entidad->id): ?>
                                  selected 
                                <?php endif ?>
                              ><?=$clase_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                        <!-- FIN INPUTS -->
                        <!-- BOTONES -->
                        <?php 
                        $label = lang('global.agregar');
                        if(isset($documentos_actor[2]->url)){
                            if ($documentos_actor['2']->url!=""){ 
                              $label = lang('global.modificar');
                            } 
                        }
                        ?>
                        <div class="btns">
                          <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                            <a class="button-file modificar" href="#" data-documento="eps_documento"><?=$label?></a>
                            <input type="file" style="display:none" name="documento" id="eps_documento" class="field_document_actor">
                          <?php endif ?>  
                          <?php if(isset($documentos_actor[2]->url)){ ?>
                              <?php if ($documentos_actor['2']->url!=""): ?>
                                <a href="<?=base_url($documentos_actor['2']->url)?>" target="_blank" class="button-file"><?php echo lang('global.ver') ?></a>
                              <?php endif ?>
                          <?php } ?>
                        
                        <?php if ($permisos=="write"): ?>
                            <button class="guardar_documento_actor second-button-file" style="display:none"><?php echo lang('global.guardar') ?></button>
                        <?php endif ?>
                        </div>
                        <!-- FIN BOTONES -->
                      <?php echo form_close(); ?>
                    </div>
                    <div class="content-doc">
                      <?php echo form_open_multipart($idioma.'casting/guardar_documento_actor','class="guardar_documento_actor_form"'); ?>
                        <!-- INPUTS OCULTOS -->
                        <input type="hidden" name="idactor" value="<?=$solicitud[0]->id_actor?>">
                        <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                        <?php if(isset($documentos_actor[3]->url)){ ?>
                        <input type="hidden" name="documento_original" value="<?=$documentos_actor[3]->url?>">
                        <?php }else{ ?>
                        <input type="hidden" name="documento_original" value="null">
                        <?php } ?>
                        <?php if(isset($documentos_actor[3]->id)){ ?>
                        <input type="hidden" name="iddocumento" value="<?=$documentos_actor[3]->id?>">
                        <?php }else{ ?>
                        <input type="hidden" name="iddocumento" value="null">
                        <?php } ?>
                        <input type="hidden" name="tipo" value="4">
                        <!-- FIN INPUTS OCULTOS -->
                        <label for=""><?php echo lang('global.fondo_pensiones') ?>:</label>
                        <!-- INPUTS -->
                        <div class="content_form">
                         <?php if(isset($documentos_actor[3]->url)){ ?>
                          <input type="text" name="nombre" class="field_document_actor"  value="<?=$documentos_actor['3']->descripcion?>"
                         <?php }else{ ?> 
                         <input type="text" name="nombre" class="field_document_actor"  value=""
                         <?php } ?>
                           <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                          <select name="activo" class="field_document_actor" id="pensiones_activo" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                            <option><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($estados_entidad as $estado_entidad): ?>
                              <option value="<?=$estado_entidad->id?>"
                              <?php if ($documentos_actor['3'] AND $documentos_actor['3']->id_estado_entidad == $estado_entidad->id): ?>
                                selected 
                              <?php endif ?>
                              ><?=$estado_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                          <select name="clase" class="field_document_actor" id="pensiones_clase" <?php if ($solicitud[0]->id_estado==11 OR $solicitud[0]->id_estado==12 OR $solicitud[0]->id_estado==16 OR $permisos!="write"): ?> disabled <?php endif ?> >
                            <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                            <?php foreach ($clases_entidad as $clase_entidad): ?>
                              <option value="<?=$clase_entidad->id?>"
                                <?php if ($documentos_actor['3'] AND $documentos_actor['3']->id_clase_entidad == $clase_entidad->id): ?>
                                  selected 
                                <?php endif ?>
                              ><?=$clase_entidad->descripcion?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                        <!-- FIN INPUTS -->
                        <!-- BOTONES -->
                        <?php 
                        $label = lang('global.agregar');
                        if(isset($documentos_actor[3]->url)){
                            if ($documentos_actor['3']->url!=""){ 
                              $label = lang('global.modificar');
                            }
                        } 
                        ?>

                        <div class="btns" >
                          <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                            <a class="button-file modificar" href="#" data-documento="pensiones_documento"><?=$label?></a>
                            <input type="file" style="display:none" name="documento" id="pensiones_documento" class="field_document_actor">
                          <?php endif ?>  
                          <?php  if(isset($documentos_actor[3]->url)){ ?>
                              <?php if ($documentos_actor['3']->url!=""): ?>
                                <a href="<?=base_url($documentos_actor['3']->url)?>" target="_blank" class="button-file"><?php echo lang('global.ver') ?></a>
                              <?php endif ?>
                          <?php } ?>
                        
                        <?php if ($permisos=="write"): ?>
                         
                            <button class="guardar_documento_actor second-button-file" style="display:none"><?php echo lang('global.guardar') ?></button>
                          
                        <?php endif ?>
                        </div>
                        <!-- FIN BOTONES -->
                      <?php echo form_close(); ?>
                    </div>
                  <?php endif ?>
                <?php endif ?>

                <?php if ($visas_actor): ?>
                  <?php foreach ($visas_actor as $visa_actor): ?>
                    <div class="content-doc">
                      <label for="">VISA:</label>
                      <div class="content_form">
                        <input type="text" disabled="disabled" name="pais" value="<?=$visa_actor->descripcion?>">
                        <input type="text" disabled="disabled" name="pais" value="<?=$visa_actor->nombre_pais?>">
                      </div>
                      <?php if ($visa_actor->url!=""): ?>
                        <a href="<?=base_url($visa_actor->url)?>" target="_blank" class="button-file"><?php echo lang('global.ver') ?></a>
                      <?php endif ?>
                    </div>
                  <?php endforeach ?>
                <?php endif ?>

                <?php  $valida_contrato = "" ?>
                <?php  $valida_contrato_firmado = "" ?>

                <?php if ($solicitud[0]->id_estado ==5 
                          OR $solicitud[0]->id_estado ==6 
                          OR $solicitud[0]->id_estado ==10
                          OR $solicitud[0]->id_estado ==17
                          OR $solicitud[0]->id_estado ==11
                          OR $solicitud[0]->id_estado ==20): ?>
                  <div class="content-doc" <?php if (($solicitud[0]->contrato=="" or $solicitud[0]->contrato==0) AND $solicitud[0]->contrato_personal=="") { ?> style="background: #FCFFCC;" <?php } ?> >
                    <label for=""><?=($solicitud[0]->tipo==2) ? 'OTRO SI' : 'CONTRATO' ?>:</label>
                    <div class="content_form">
                     <?php if ($solicitud[0]->contrato=="" AND $solicitud[0]->contrato_personal==""): ?>  
                        <!-- VALIDACION SUBIR APROBAR SOLCITUD -->
                        <?php $valida_contrato = "PENDIENTE"; ?> 
                        <input disabled type="text" value="<?= $valida_contrato  ?>">
                        <!-- PLANTILLAS -->
                        <?php if ($permisos=="write" or $this->session->userdata('tipo_pruduction_suite')==7): ?>
                          <a href="<?=base_url($idioma.'casting/anadir_contrato/'.$solicitud[0]->id_solicitud)?>" class="view_file" style="margin-top: 7px;"><?php echo lang('global.ver_plantillas') ?></a>
                        <?php endif ?>
                        <!-- PLANTILLAS -->
                    </div>

                      <!-- CONTRATO PERSONALIZADO -->
                      <?php if ($caso_especial!=0 AND $solicitud[0]->id_estado == 5 AND $permisos=="write"): ?>
                      <span class="">
                        <?php echo form_open_multipart($idioma.'casting/subir_contrato_personalizado', 'id="form_subir_contrato_personalizado"'); ?>
                          <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>" >
                          <span class="button-file modificar" style="background: #4D4D4D;"><?php echo lang('global.subir') ?> <?=($solicitud[0]->tipo==2)?'OTRO SI':'CONTRATO'?></span>
                          <input type="file" name="contrato_personalizado" id="contrato_personalizado" class="autosave" style="display:none"> 
                        <?php echo form_close(); ?>  
                      </span>
                      <?php endif ?>
                      <!-- FIN CONTRATO PERSONALIZADO -->

                     <?php else: ?>
                        <input disabled type="text" value="ADJUNTO">
                        <?php if ($solicitud[0]->contrato_personal!=""): ?>
                         <textarea style="min-height:60px;" disabled ><?=str_replace(array('images/documentos_actor/','.docx','.pdf','.doc'), '', $solicitud[0]->contrato_personal)?></textarea>
                         <input type="text" value="<?php echo $solicitud[0]->fecha_contrato_selec ?>" disabled="">
                        <?php else: ?>
                          <textarea style="min-height:60px;" disabled ><?php echo Casting::nombreContrato($solicitud[0]->id_solicitud); ?></textarea>
                          <input type="text" value="<?php echo $solicitud[0]->fecha_contrato_selec ?>" disabled="">
                        <?php endif ?>
                        </div>
                        <?php if ($solicitud[0]->contrato!="" AND $permisos=="write"): ?> 
                        <div class="btns">
                          <?php if ($solicitud[0]->id_estado==5): ?> 
                            <a href="<?=base_url($idioma.'casting/anadir_contrato/'.$solicitud[0]->id_solicitud)?>" class="button-file" style="background: #C7541B;"><?php echo lang('global.modificar') ?></a>
                          <?php endif ?>
                          
                          <?php if ($solicitud[0]->id_estado>=5): ?> 
                          
                            <a target="_blank" href="<?=base_url($idioma.'casting/pdf_contrato/'.$solicitud[0]->id_solicitud)?>" class="button-file"><?php echo lang('global.ver') ?></a>
                          <?php endif ?>
                          
                        <?php endif ?>
                        <?php if ($solicitud[0]->contrato_personal!="" AND $permisos=="write"): ?> 
                        <?php echo form_open_multipart($idioma.'casting/subir_contrato_personalizado', 'id="form_subir_contrato_personalizado" style="margin-bottom: 0px;" '); ?>
                          <?php if ($solicitud[0]->id_estado ==5 AND $permisos=="write"): ?>
                         
                          <a target="_blank" class="button-file modificar" href="#"><?php echo lang('global.modificar') ?></a>
                          <?php endif ?>
                          <input type="file" name="contrato_personalizado" style="display:none">
                          
                          <a target="_blank" href="<?= base_url($solicitud[0]->contrato_personal)?> " class="button-file"><?php echo lang('global.ver') ?></a>
                         
                        <?php echo form_close(); ?> 
                        <?php endif ?>

                        <?php if ($caso_especial!=0 AND $solicitud[0]->id_estado == 5 AND $permisos=="write") { ?>
                          <?php if ($solicitud[0]->contrato_personal==""): ?>
                            <span class="">
                              <?php echo form_open_multipart($idioma.'casting/subir_contrato_personalizado', 'id="form_subir_contrato_personalizado"'); ?>
                                <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>" >
                                <span class="second-button-file modificar" style="background: #4D4D4D;" style="width: 99%;"><?php echo lang('global.subir') ?> <?=($solicitud[0]->tipo==2)?'OTRO SI':'CONTRATO'?></span>
                                <input type="file" name="contrato_personalizado" id="contrato_personalizado" class="autosave" style="display:none"> 
                              <?php echo form_close(); ?>  
                            </span>
                          <?php else: ?>
                          
                            <?php if ($solicitud[0]->contrato=="" or $solicitud[0]->contrato==0): ?>
                              <a href="<?=base_url($idioma.'casting/anadir_contrato/'.$solicitud[0]->id_solicitud)?>" class="view_file" style="margin-top: 7px;"><?php echo lang('global.ver_plantillas') ?></a>
                            <?php endif ?>

                          <?php endif ?>
                           </div>
                        <?php } ?>
                     <?php endif ?>
                    </div>
                  </div>
                <?php endif ?>


                <!-- CONTRATO FIRMADO -->
                <?php if ($solicitud[0]->id_estado == 10 OR $solicitud[0]->id_estado == 11 OR $solicitud[0]->id_estado == 20): ?>
                  <div class="content-doc" <?php if ($solicitud[0]->contrato_firmado=="") { ?> style="background: #FCFFCC;" <?php } ?> >
                      <label><?=($solicitud[0]->tipo==2)?'OTRO SI':'CONTRATO'?> FIRMADO</label>
                      <div class="content_form">
                      <?php 
                        if ($solicitud[0]->contrato_firmado=="") { ?>
                          <input type="text" value="PENDIENTE" disabled>
                        <?php }else{ ?>
                          <input type="text" value="ADJUNTO" disabled>
                           <input type="text" value="<?= $responsable = str_replace(("images/documentos_actor/"), '', $solicitud[0]->contrato_firmado);?>" disabled>
                          <input type="text" value="<?=$solicitud[0]->fecha_contrato_firmado?>" disabled>
                        <?php } ?>
                      </div>
                        
                        <?php echo  form_open_multipart($idioma.'casting/subir_contrato_firmado', 'id="form_contrato_firmado"'); ?>
                            <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                            <div class="btns" >
                            <?php if ($solicitud[0]->contrato_firmado=="" AND $permisos=="write") { ?>
                              <a href="#" class="button-file modificar" style="background: #4D4D4D;"><?php echo lang('global.subir') ?> <?=($solicitud[0]->tipo==2)?'OTRO SI':'CONTRATO'?></a> 
                            <?php }else{ ?>
                              <?php if ($permisos=="write" AND $solicitud[0]->id_estado == 20): ?>
                                <a href="#" class="button-file modificar"><?php echo lang('global.modificar') ?></a> 
                              <?php endif ?>
                            <?php }?>
                                  <input type="file" name="contrato_firmado" id="contrato_firmado_file" class="autosave with-label" style="display:none">
                                   <?php if ($solicitud[0]->contrato_firmado): ?>
                              <a target="_blank" href="<?= base_url($solicitud[0]->contrato_firmado)?> " class="button-file"><?php echo lang('global.ver') ?></a>
                            <?php endif ?>
                            </div>
                      
                           
                        <?php echo form_close(); ?>
                  </div>
                <?php endif ?>
                <!-- FIN CONTRATO FIRMADO -->

                <?php if ($documentos_solicitud): ?>
                  <?php foreach ($documentos_solicitud as $documento_solicitud): ?>
                    <div class="content-doc">
                    <?php echo form_open_multipart($idioma.'casting/actualizar_documento_solicitud', 'id="actualizar_documento_'.$documento_solicitud->id.'"'); ?>
                       <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                       <label><?php echo lang('global.documentos') ?>:</label>
                      <div class="content_form_doc">
                        <input 
                        <?php if ($permisos!="write"): ?>
                          disabled="disabled" 
                        <?php endif ?> type="text" name="numero_documento_<?=$documento_solicitud->id?>" class="change_numero_documento" value="<?=$documento_solicitud->descripcion?>" data-id="<?=$documento_solicitud->id?>">
                        <input disabled type="text" value="<?=$documento_solicitud->fecha?>">
                      </div>
                      <div>
                        <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                            <a class="button-file modificar" href="#" data-id="<?=$documento_solicitud->id?>"><?php echo lang('global.modificar') ?></a>
                            <input type="file" class="change_documento_solicitud" name="documento_solicitud_<?=$documento_solicitud->id?>" data-id="<?=$documento_solicitud->id?>" id="documento_solicitud_<?=$documento_solicitud->id?>" style="display:none">
                            <input type="hidden" name="documento_original_<?=$documento_solicitud->id?>" value="<?=$documento_solicitud->documento?>">
                            <input type="hidden" name="id_documento[]" value="<?=$documento_solicitud->id?>">
                        <?php endif ?>
                        <a target="_blank" class="button-file" href="<?=base_url($documento_solicitud->documento)?>"><?php echo lang('global.ver') ?></a>
                      <!-- BOTON ELIMINAR DOCUMENTO  -->
                      <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                        <a href="#" class="eliminar_documento_solicitud button-file" data-id="<?php echo $documento_solicitud->id ?>" data-idsolitud="<?php echo $solicitud['0']->id_solicitud ?>">ELIMINAR</a>
                        <a href="#" id="button_<?=$documento_solicitud->id?>" class="guardar_documento_solicitud button-file" data-id="<?=$documento_solicitud->id?>" style="display:none;"><?php echo lang('global.guardar') ?></a>
                      <?php endif ?>
                      <!-- FIN BOTON ELIMINAR DOCUMENTO  -->
                      </div>
                      
                    <?php echo form_close(); ?>
                   </div>

                  <?php endforeach ?>
                <?php endif ?>

                <?php if ($solicitud[0]->id_estado!=11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
                  <div class="content-doc">
                    <?php echo form_open_multipart($idioma.'casting/guardar_documento_solicitud', 'id="guardar_documento_solicitud"'); ?>
                    <label for=""><?php echo lang('global.nuevo_documento') ?>:</label>
                    <div>
                      <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                      <input type="text" placeholder="DESCRIPCIÓN" name="descripcion_socumento">
                    </div>
                    <div class="btns" ><span class="button-file"><?php echo lang('global.adjuntar_archuivo') ?></span>   <input type="file" name="documento_solicitud" class="autosave" style="display:none"></div>
                 
                    <?php echo form_close(); ?>
                  </div>
                <?php endif ?>

            </td>
          </tr>
           
           
         </table>

                  
          <!-- SECCION DE NOTAS DE LA SOLICITUD -->
          <table class="grupo-datos tablaNotaSolicitudes">
          <caption class="detalleSolicitud"><?php echo lang('global.notas_de_la_solicitud') ?></caption>
            <?php if ($comentarios_solicitud): ?>
            <tr>
              <td colspan="3">
                <span class="scroll_td">
                  <table cellspacing="0" cellpadding="0" border="0" style="width:100%;" class="tablesorter tabla_detalle_actor">
                    <thead >
                      <tr>
                        <th class="header"><?php echo lang('global.usuario') ?></th>
                        <th class="header"><?php echo lang('global.comentarios') ?></th>
                        <th class="header"><?php echo lang('global.fecha') ?></th>
                      </tr>
                    </thead>
                    <tbody> 
                      <?php foreach ($comentarios_solicitud as $comentario_solicitud): ?>
                        <tr>
                          <td ><?=$comentario_solicitud->usuario?></td>
                          <td><?=$comentario_solicitud->comentario?></td>
                          <td><?=$comentario_solicitud->fecha?></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody> 
                  </table>
                </span>
              </td>
            </tr>
            <?php endif ?>
            <?php if($solicitud[0]->id_estado != 11 AND $solicitud[0]->id_estado!=12 AND $solicitud[0]->id_estado!=16 AND $permisos=="write"): ?>
            <tr>
              <td colspan="3">
                <?php echo form_open($idioma.'/casting/guardar_comentario_solicitud', 'id="guardar_comentario_solicitud"'); ?>
                  <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                  <textarea name="comentario_solicitud" id="comentario_solicitud" class="required"></textarea>
                  <div class="clr6"></div>
                  <button class="button two"><?php echo lang('global.guardar') ?></button>
                <?php echo  form_close(); ?>
              </td>
            </tr>
            <?php endif ?>
            <!-- SUGERENCIAS DE LA CONTRATACION  -->
            <?php if ($solicitud[0]->id_estado == 5 AND !$solicitud[0]->sugerencias_contratacion AND ($solicitud[0]->roles=="1" OR $solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->id_forma_pago==1  OR $solicitud[0]->id_tipo_moneda==2) AND $permisos=="write" ): ?>
              <tr>
                <td colspan="3">
                  <?php echo  form_open($idioma.'casting/agregar_sugerencia_contratacion', ''); ?>
                  <input type="hidden" name="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
                  <label for=""><?php echo lang('global.sugerencia_contratacion') ?></label>
                  <label for="" class="labelSugestionContratacion" >Si<input type="radio" name="sugestions_contratation" value="1" class="sugestions_contratation"></label>
                  <label for="">No<input type="radio" name="sugestions_contratation" value="2" class="sugestions_contratation"></label>
                  <div id="sugestions_field" style="display:none;">
                    <br>
                    <textarea name="sugerencia_contratacion"></textarea>
                    <div class="clr6"></div>
                    <button class="button two"><?php echo lang('global.guardar') ?></button>
                  </div>
                  <?php echo form_close(); ?>
                </td>
              </tr>
            <?php endif ?>
            <?php if ($solicitud[0]->sugerencias_contratacion): ?>
              <tr>
                <td colspan="3">
                  <label for=""><?php echo lang('global.sugerencia_contratacion') ?></label>
                  <div><?=$solicitud[0]->sugerencias_contratacion?></div>
                  </div>
                </td>
              </tr>
            <?php endif ?>
            <!-- FIN SUGERENCIAS DE LA CONTRATACION  -->
          </table>
          <!-- FIN SECCION DE NOTAS DE LA SOLICITUD -->

         <table class="grupo-datos">
            <tr>
            <td colspan="8">
             <label><?php echo lang('global.historia_de_solicitud') ?>:</label>
              <?php if ($historial_solicitud): ?>
              <span class="scroll_td">
                <table class="historial">
                    
                  <?php foreach ($historial_solicitud as $aprobacion): ?>
                    <?php if ($aprobacion->id_estado!=20): ?>
                      <tr class="estado_solicitud<?=$aprobacion->id_estado?>" >
                        <td class="cell-fecha"><?=date("d-M-y h:i:s a",strtotime($aprobacion->fecha_aprobacion))?></td>
                        <td><?=$aprobacion->estado?>
                          <?php if ($solicitud[0]->tipo==2): ?>
                             / OTRO SI
                          <?php endif ?>
                        </td>
                        <td><?=$aprobacion->nombre_usuario?></td>
                        <td><?=$aprobacion->notas?></td>
                      </tr>
                    <?php endif ?>
                  <?php endforeach ?>
                </table>
              </span>
              <?php endif ?>
            </td>
          </tr>
         </table>

      </div>
       </div>
      <div class="clr6"></div>
      <div class="row">
        <div class="column twelve btn_solicitud">
          <?php if ($solicitud[0]->id_estado != 1): ?>
            <?php if (($solicitud[0]->id_estado == 5 OR $solicitud[0]->id_estado == 6) AND $solicitud[0]->contrato=="" AND $permisos=="write" and $continuar==1): ?>
              <?php if ($caso_especial==0): ?>
                <a href="<?=base_url($idioma.'casting/anadir_contrato/'.$solicitud[0]->id_solicitud)?>" class="button columns six" style="height:32px; margin:0;">
                  <?php echo lang('global.ver_plantillas') ?>
                </a>
              <?php else: ?>
                <?php if (!$solicitud[0]->contrato_personal AND $permisos=="write" and $continuar==1): ?>
                  <a href="#" id="adjuntar_archivo_contrato" class="button columns six" style="height:32px; margin:0;"><?php echo lang('global.subir_contrato') ?></a>
                <?php endif ?>
              <?php endif ?>
            <?php endif ?>
          <?php endif ?>
          <?php if ($solicitud[0]->id_estado == 1 OR $solicitud[0]->id_estado == 4 OR $solicitud[0]->id_estado == 7 OR $solicitud[0]->id_estado == 8 OR $solicitud[0]->id_estado == 9 AND $permisos=="write" and $continuar==1): ?>
            <?php if ($solicitud[0]->id_estado == 1):?>
            <button class="button columns six" style="height:32px; margin:0;" id="aprobar_solicitud" data-estado="<?=$solicitud[0]->id_estado?>" data-solicitud="<?=$solicitud[0]->id_solicitud?>">
                <?php echo lang('global.aprobar_solicitud') ?>
            </button>
            <?php endif ?>
            <?php if ($permisos=="write"): ?>
               <?php if ($solicitud['0']->tipo == 2): ?> 
                    <a href="<?=base_url($idioma.'casting/editar_solicitud_otro_si/'.$solicitud[0]->id_solicitud)?>" class="button columns six" style="height:32px; margin:0;">
                      <?php echo lang('global.editar_solicitud') ?>
                  </a>
               <?php else: ?>
                  <a href="<?=base_url($idioma.'casting/editar_solicitud/'.$solicitud[0]->id_solicitud)?>" class="button columns six" style="height:32px; margin:0;">
                      <?php echo lang('global.editar_solicitud') ?>
                  </a>
               <?php endif ?>                           
              
            <?php endif ?>
          <?php endif ?>
          <?php if ($solicitud[0]->id_estado != 1): ?>

            <!-- BOTON DE APROBAR -->
            <?php if ($valida AND $continuar==1): ?>
              <?php if ($solicitud[0]->id_estado == 20): ?>
                <button class="button columns six" style="height:32px; margin:0;" id="aprobar_solicitud_firma" data-estado="<?=$solicitud[0]->id_estado?>" data-solicitud="<?=$solicitud[0]->id_solicitud?>"><?php echo lang('global.aprobar_solicitud') ?></button>
              <?php else: ?>
                <button class="button columns six" style="height:32px; margin:0;" id="aprobar_solicitud" data-estado="<?=$solicitud[0]->id_estado?>" data-solicitud="<?=$solicitud[0]->id_solicitud?>">
                  <?php if ($solicitud[0]->id_estado != 17): ?>
                    <?php echo lang('global.aprobar_solicitud') ?> 
                  <?php else: ?>
                    <?php echo lang('global.terminar_solicitud') ?>
                  <?php endif ?>
                </button>
              <?php endif ?>
              
            <?php endif ?>
            <!-- FIN BOTON DE APROBAR -->

            <?php if ($solicitud[0]->id_estado ==20 AND $solicitud[0]->fecha_contrato_firmado=="" AND $permisos=="write" and $continuar==1): ?>
              <button type="button" class="button columns six" style="height:32px; margin:0;" id="subir_contrato_firmado"><?php echo lang('global.subir_contrato_firmado') ?></button>
            <?php endif ?>


            <?php if (($solicitud[0]->id_estado == 5 OR $solicitud[0]->id_estado == 6 
            OR $solicitud[0]->id_estado == 10 OR $solicitud[0]->id_estado == 3 OR $solicitud[0]->id_estado == 2 
            OR $solicitud[0]->id_estado == 17 OR $solicitud[0]->id_estado == 19 OR $solicitud[0]->id_estado == 20) AND $permisos=="write" AND $valida AND $continuar==1): ?>
              <button class="button btn_orange  columns six rechazar_solicitud_button" style="height:32px; margin:0;" data-idestado="<?=$solicitud[0]->id_estado?>" data-solicitud="<?=$solicitud[0]->id_solicitud?>">
                <?php echo lang('global.rechazar') ?>
              </button>
            <?php endif ?>
            <?php if ($solicitud[0]->id_estado == 1 
                        OR $solicitud[0]->id_estado == 7
                        OR $solicitud[0]->id_estado == 8 
                        OR $solicitud[0]->id_estado == 9 AND $permisos=="write"):?>
              <a href="<?=base_url($idioma.'casting/cancelar_solicitud/'.$solicitud[0]->id_solicitud)?>" class="button btn_orange columns six" style="height:32px; margin:0;">
                <?php echo lang('global.cancelar_solicitud') ?>
              </a>
            <?php endif ?>
            
            <!-- BOTON ANULACION SOLICITUD -->

            <?php 
            $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
            $id_user=$this->session->userdata('id_pruduction_suite');
            $user=$this->model_admin->rolUserId($id_user);
            $tipo_rol=$user['0']['id_rol_otros'];
            if(($tipo_usuario==1 OR $tipo_usuario==4 OR $tipo_rol==8) AND $solicitud[0]->id_estado!=16){ ?>
                <a href="#" id="anular_solicitud" class="button btn_orange columns six" style="height:32px; margin:0;" data-idsolicitud="<?=$solicitud[0]->id_solicitud?>"><?php echo lang('global.anular') ?></a>
            <?php } ?>

          <?php endif ?>
        </div>

        <!-- CAMPO OCULTO PARA APROBACION ESPECIAL -->
        <input type="hidden" id="caso_especial" name="caso_especial" value="<?=$caso_especial?>">
        <!-- FIN CAMPO OCULTO PARA APROBACION ESPECIAL -->

        <!-- SECCION PARA EL RECHAZO DE LA SOLICITUD -->
        <div id="rechazar_section" class="column twelve" style="display:none;" >
          <table class="grupo-datos">
            <caption><?php echo lang('global.rechazar') ?></caption>
            <tr>
              <td colspan="8">
                <div style="padding:10px">
                    <label for=""><?php echo lang('global.razon') ?></label>
                    <textarea name="rezon" id="rechazo_razon"></textarea>
                    <div class="clr6"></div>

                    <?php if ($solicitud[0]->id_estado == 20): ?>
                      <label for="" class="label_check label-inline" ><?php echo lang('global.generando_contrato') ?>: 
                        <input value="5"  type="radio" name="estado_cambio" class="estado_cambio_solicitud"></label>
                    <?php endif ?>

                    <?php if ($caso_especial==0): ?>
                      <label for="" class="label_check label-inline" ><?php echo lang('global.escalar_juridico') ?>: <input value="8" type="radio" name="estado_cambio" class="estado_cambio_solicitud"></label>
                    <?php endif ?>
                    <label for="" class="label_check label-inline" ><?php echo lang('global.solicitud_incompleta') ?>: <input value="7"  type="radio" name="estado_cambio" class="estado_cambio_solicitud"></label>
                    <label for="" class="label_check label-inline" ><?php echo lang('global.otros') ?>: <input value="9"  type="radio" name="estado_cambio" class="estado_cambio_solicitud"></label>
                    <div class="clr6"></div>
                    <button type="button" class="button two rechazar_solicitud" data-solicitud="<?=$solicitud[0]->id_solicitud?>"><?php echo lang('global.confirmar') ?></button>  
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="clr6"></div>
        <!-- FIN SECCION PARA EL RECHAZO DE LA SOLICITUD -->
      </div>
   
  </div>
</div>

<!-- SECCION PARA COMFIRMAR SOLICITUD -->
<div id="ordenarWrap" style="display:none">
    <div class="ordenarBox typeAprobacionDetalleProduct">
      <span class="closeIcon" id="no_save_order"></span>
      <h3 ><?php echo lang('global.seleccion_el_tipo_de_aprobacion') ?></h3>
      <?php if (!$aprobacion_juridica): ?>
        <span for=""><?php echo lang('global.aprobar_como_juridico') ?><input type="radio" class="estado_admin"  name="estado_cambio" 
        <?php if($aprobacion_finanzas){ ?> checked <?php } ?>
        value="1"></span>
      <?php endif ?>
      <?php if (!$aprobacion_finanzas): ?>
        <span for=""><?php echo lang('global.aprobar_como_finanzas') ?><input type="radio" class="estado_admin"
         <?php if($aprobacion_juridica){ ?> checked <?php } ?>
         name="estado_cambio" value="2"></span>
      <?php endif ?>
      <button id="aprobar_admin" class="button" data-solicitud="<?=$solicitud[0]->id_solicitud?>"><?php echo lang('global.aprobar') ?></button>
    </div>
</div>
<!-- SECCION PARA COMFIRMAR SOLICITUD -->

<!-- SECCION PARA COMFIRMAR SOLICITUD -->
<div id="ordenarWrap2" style="display:none">
    <div class="ordenarBox typeAprobacionDetalleProduct">
      <span class="closeIcon" id="no_save_order"></span>
      <?php if (!$aprobacion_documentos): ?>
        <span for=""><?php echo lang('global.aprobar_recoleccion_documento') ?> <input type="radio" class="estado_admin" 
           <?php if($aprobacion_firma){ ?> checked <?php } ?>
         name="estado_cambio" value="17"></span>
      <?php endif ?>
      <?php if (!$aprobacion_firma): ?>
        <span for=""><?php echo lang('global.aprobar') ?> <?php echo lang('global.proceso_de_firma') ?>

          <input type="radio" class="estado_admin documento_fir" name="estado_cambio" value="10" 
          <?php if($aprobacion_documentos){ ?> checked <?php } ?>
          data-documento="<?php if ($solicitud[0]->contrato_firmado==""){ echo 0;}else{ echo 1;} ?>"
           
          ></span>
      <?php endif ?>
      <button id="aprobar_admin_firma" class="button" data-solicitud="<?=$solicitud[0]->id_solicitud?>"><?php echo lang('global.aprobar') ?></button>
    </div>
</div>
<!-- SECCION PARA COMFIRMAR SOLICITUD -->

<?php if ($solicitud[0]->tipo==2): ?>
  <style>
    caption.detalleSolicitud{
      background: #C7541B!important;
    }
  </style>
<?php endif ?>