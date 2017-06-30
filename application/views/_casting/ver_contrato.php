<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.generar_contrato') ?>
</div>
<nav class="nav_post nav_casting">
  <ul>
    <li><a  href="<?=base_url($idioma.'casting/crear_solicitud')?>"><?php echo lang('global.crear_solicitud') ?></a></li>
    <li><a  href="<?=base_url($idioma.'casting/solicitudes')?>"><?php echo lang('global.ver_solocitudes') ?></a></li>
  </ul>
</nav>
                  
<div class="column twelve">
  <div class="row">
    <div class="column twelve">
      <div class="info contrato">
      <input type="hidden" id="id_solcitud" value="<?=$solicitud[0]->id_solicitud?>">
        <?php echo lang('global.solicitud') ?>: <?php echo Casting::completar_id($solicitud[0]->id_solicitud); ?> <?php echo lang('global.generando_contrato') ?>
      </div>
    </div>
  </div>
 
</div>

<div class="columns twelve">
  <div class="row">
    <div class="column twelve">
      <div class="info">
        <div class="column twelve" style="padding:0 0 6px 0;">
          <div class="column nine">
            <input type="text" id="contrato" style="height:30px" readonly value="<?=$solicitud[0]->objeto_contrato.', '.$solicitud[0]->forma_pago?>">
          </div>
          <div class="column three" style="padding:0;">
              <button class="button btn_orange" style=" margin:0;float:left;padding:0">
                <?php echo lang('global.volver') ?>
              </button>
              <!-- <button class="button" style="width:33.33%; margin:0;float:left;">
                Imprimir
              </button> -->
          </div>
          <div class="clr"></div>
        </div>

        <div class="clr6"></div>


        <div class="clr"></div>
      </div>
    </div>
  </div>
</div>