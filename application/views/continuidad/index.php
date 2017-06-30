  <?php $idioma = $this->lang->lang().'/'; ?>
  <div class="small-12 columns resumen">
    <div class="small-12 columns">
       <h1><?php echo lang('global.producciones') ?></h1>
    </div>
    <?php if($produccion){ ?>
    <?php foreach ($produccion as $p) { ?>
      <a class="small-6 medium-4 columns ws-production" href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$p['id']);?>">
        <div class="">
          <div class="produccion_img">
            <?php if($p['imagen_produccion']!=null){  ?> 
            <img src="<?php echo base_url($p['imagen_produccion']) ?>" alt="" onerror = "this.src='<?php echo base_url() ?>images/produccion/production_suite.jpg'">
            <?php } else { ?>
               <img src="<?php echo base_url() ?>images/produccion/production_suite.jpg" alt="">
            <?php } ?>
          </div>
        <div>
            <h2><?php echo $p['nombre_produccion'] ?></h2>
        </div>
        </div>
      </a>
    <?php } ?>
    <?php } else {?>
    <h2><?php echo lang('global.no_tiene_producciones') ?></h2>
    <?php } ?>      
  </div>