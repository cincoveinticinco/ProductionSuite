<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.generar') ?> <?php if ($solicitud[0]->tipo==2): ?>
                      OTROSI
                    <?php else: ?>
                      <?php echo strtoupper(lang('global.contrato')) ?>
                    <?php endif ?>
</div>
<nav class="nav_post nav_casting">
  <ul>
    <li><a  href="<?=base_url($idioma.'casting/crear_solicitud')?>" class="buttons icon icon_plus"><span></span><?php echo lang('global.crear_actor') ?></a></li>
    <li><a  href="<?=base_url($idioma.'casting/solicitudes')?>" class="buttons icon icon_plus"><span></span><?php echo lang('global.ver_solocitudes') ?></a></li>
  </ul>
    
</nav>
       

<div class="column twelve">
  <div class="row">
    <div class="column twelve">
      <div class="info contrato estado_solicitud<?=$solicitud[0]->id_estado?>">
      <input type="hidden" id="id_solcitud" value="<?=$solicitud[0]->id_solicitud?>">
        <?php echo lang('global.solicitud') ?>: <?php if ($solicitud[0]->tipo == 2 ): ?>
                      <?php $solicitud_anexa = $this->model_casting->solicitud_id($solicitud[0]->id_solicitud_anexa);  ?>
                      <?php echo Casting::completar_id($solicitud[0]->id_solicitud_anexa); ?> - 
                      <?php echo Casting::numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);?>
                    <?php else: ?>
                      <?php echo Casting::completar_id($solicitud[0]->id_solicitud); ?>
                    <?php endif ?> <?php echo lang('global.generando') ?> 
                    <?php if ($solicitud[0]->tipo==2): ?>
                      OTROSI
                    <?php else: ?>
                      <?php echo strtoupper(lang('global.contrato')) ?>
                    <?php endif ?>
                    
      </div>
    </div>
  </div>
 
</div>
<div class="columns twelve">
  <div class="row">
    <div class="column twelve">
      <div class="info">
        <?php echo form_open($idioma.'casting/agregar_contrato','id="agregar_contrato"', 'class=""'); ?>
        <div class="column twelve" style="padding:0 0 6px 0;">
          <div class="column nine">
           <!-- revisar codigo que pinta el contrato <textarea><?=$contratos[32]->contrato?></textarea> -->
            <select name="contrato" id="contrato_select" class="required">
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php if ($contratos): ?>  
                  <?php foreach ($contratos as $contrato): ?>
                    <option value="<?=$contrato->id?>"
                      <?php if ($contrato->id == $contrato_select): ?>
                        selected   
                      <?php endif ?>
                    >
                    <?=$contrato->nombre?>
                    </option>
                  <?php endforeach ?>
              <?php endif ?>
            </select>
          </div>
          <div class="column three" style="padding:0;">
            <a href="#" id="exportar_contrato" class="button" style="width:50%; margin:0;float:left;">
              <?php echo lang('global.exportar') ?>
            </a>
            <button class="button btn_orange" style="width:50%;margin:0;float:left;padding:0" onclick="javascript:history.go(-1)">
              <?php echo lang('global.volver') ?>
            </button>
          </div>
          <div class="clr"></div>
        </div>
        <div class="column twelve" style="padding:0px;">
        <input type="hidden" name="idsolicitud" id="idsolicitud" value="<?=$solicitud[0]->id_solicitud?>">
          <!-- SECCION DE CARGA DE PLANTILLA CONTRATO -->
          <div style="height:400px;" name="contrato_section" class="required" id="contrato_section">
              
          </div>
          <!-- FIN SECCION DE CARGA DE PLANTILLA CONTRATO -->
        </div>
        <div class="column twelve" style="margin:7px 0 0 0; padding:0px;">
          <div class="column six" style="padding:0;">
            <button class="button btn_orange" style="padding:0;" type="submit">
                  <?php echo lang('global.generar') ?> <?php echo lang('global.contrato') ?>
            </button>
          </div>
          <div class="column six">
            <a href="<?=base_url($idioma.'casting/detalle_solicitud/'.$solicitud[0]->id_solicitud)?>" class="button" style="width:100%;">
                  <?php echo lang('global.cancelar') ?>
            </a>
          </div>
        </div>
        <div class="clr6"></div>
        <?php echo form_close(); ?> 
        <div class="clr"></div>

        <!-- REPRSENTANTES INVOLUCRADOS EN CONTRATO -->
        <div class="row">
          <div class="column twelve">
            <div class="column four">
              <label for=""><?php echo lang('global.representante') ?> RTI</label>
              <input type="text" id="representante_rti" value="<?=(isset($responsables_contrato[0]->nombre))?$responsables_contrato[0]->nombre:''?>">
            </div>
            <div class="column four">
              <label for=""><?php echo lang('global.documento') ?></label>
              <input type="text" id="documento_rti"  value="<?=(isset($responsables_contrato[0]->documento))?$responsables_contrato[0]->documento:''?>">
            </div>
            <div class="column four">
              <label for="">&nbsp;</label>
              <button type="button" data-representante="rti"  class="button guardar_representantes"><?php echo lang('global.guardar') ?></button>
            </div>
          </div>
        </div>
        <div class="clr6"></div>
        <div class="row">
          <div class="column twelve" id="rcn_represent">
            <input type="hidden" id="id_sociedad" value="<?=$solicitud[0]->id_sociedad?>">  
            <div class="column four">
              <label for=""><?php echo lang('global.representante_sociedad') ?></label>
              <input type="text" id="representante_legal" value="<?=(isset($solicitud[0]->representante_legal))?$solicitud[0]->representante_legal:''?>">
            </div>
            <div class="column four">
              <label for=""><?php echo lang('global.documento') ?></label>
              <input type="text" id="representante_documento"value="<?=(isset($solicitud[0]->representante_documento))?$solicitud[0]->representante_documento:''?>">
            </div>
            <div class="column four">
              <label for="">&nbsp;</label>
              <button type="button" data-representante="sociedad" class="button guardar_sociedad"><?php echo lang('global.guardar') ?></button>
            </div>
          </div>
        </div>

        <div class="columns twelve" id="alerta_representantes" style="display:none">
          <div class="alert-box sucess">
            <?php echo lang('global.los_datos_se_han_guardado_correctamente') ?>
            <a href="" class="close">&times;</a>
          </div>
        </div>
        <div class="clr6"></div>
        <!-- REPRSENTANTES INVOLUCRADOS EN CONTRATO -->
      </div>
    </div>
  </div>
</div>