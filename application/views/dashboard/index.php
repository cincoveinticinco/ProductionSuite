  <?php $idioma = $this->lang->lang().'/'; ?>
  <div data-role="content"> 
  <div class="productions_content">
   <?php $cont=0; ?>
   <?php if($produccion){ ?>
    <?php foreach ($produccion as $p) { ?>
    <?php 
     if($cont%2==0){
      $class='item white row';
     }else{
      $class='item gray_light row';
     }
     $cont++;
     ?>
      <a data-ajax="false" href="<?php echo base_url($idioma.'dashboard/produccion/'.$p['id_produccion']);?>" class="<?php echo $class ?> estelink">
        <div class="columns two">
          <div class="thumbnail_img">
            <?php if($p['imagen_produccion']!=null){  ?> 
            <img src="<?php echo base_url($p['imagen_produccion']) ?>" alt="">
            <?php } else { ?>
               <img src="<?php echo base_url() ?>images/produccion/production_suite.jpg" alt="">
            <?php } ?>
          </div>
        </div>
        <div class="columns ten">
          <div class="row">
            <h2><?php echo $p['nombre_produccion'] ?></h2>
          </div>
        </div>
      </a>
    <?php } ?>
    <?php } else {?>
    <h4><?php echo lang('global.no_tiene_producciones') ?></h4>
    <?php } ?>      
  </div>
  
</div>




<div id="inner_content">
  
</div>