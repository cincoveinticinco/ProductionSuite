  <?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <?php echo anchor($idioma.'produccion/producciones',lang('global.inicio')); ?>  / <?php echo lang('global.producciones') ?>
</div>
<div id="inner_content">
  <div class="productions_content" style="width: inherit;">
    <div class="columns twelve">
      <div class="columns four right" style="float: right; padding:0 0 8px 0;">
        <?php echo form_open($idioma.'produccion/producciones');?>
            <div class="columns nine">
              <select name="filter_prod" id="filter_prod">
                <option value="3" <?php if($estado=="3"){echo "selected";}?>><?php echo lang('global.todas') ?></option>
                <option value="1" <?php if($estado=="1"){echo "selected";}?>><?php echo lang('global.activas') ?></option>
                <option value="2" <?php if($estado=="2"){echo "selected";}?>><?php echo lang('global.inactivas') ?></option>
              </select>
            </div>
            <div class="columns three">
              <input type="submit" value="<?php echo lang('global.filtrar') ?>" class="button">
            </div>
        <?php echo form_close(); ?>
      </div>
    </div>    
   <?php $cont=0; ?>
   <?php if($produccion){ ?>
    <div class="row">
          <?php foreach ($produccion as $p) { ?>
          <?php 
           if($cont%2==0){
            $class='item gray_light row';
           }else{
            $class='item white row';
           }
           $cont++;
           $centro_produccion=$this->model_produccion->centro_produccion_id($p['id_centroProduccion']);
           $tipo_produccion=$this->model_produccion->tipo_produccion_id($p['id_tipoProduccion']);
           $productor_general=$this->model_admin->user_id($p['id_productor_general']);
           $productor_ejecutivo=$this->model_admin->user_id($p['id_productor_ejecutivo']);
           $productor=$this->model_admin->user_id($p['id_productor']);
           ?>

            <div data-href="<?php echo site_url($idioma.'plan_produccion/index/'.$p['id_produccion']); ?>" class="production_div column four" style="">
              <div class="row header_blue">
                <?php echo $p['nombre_produccion'] ?>
              </div>
              <div class="<?php echo $class ?>">
                  <div class="columns five">
                    <div class="thumbnail_img">
                      <?php if($p['imagen_produccion']!=null){  ?> 
                      <img src="<?php echo base_url($p['imagen_produccion']) ?>" alt="">
                      <?php } else { ?>
                         <img src="<?php echo base_url() ?>images/produccion/production_suite.jpg" alt="">
                      <?php } ?>
                    </div>
                     <!-- BOTONES PLAN PRODUCCION Y POSTPRODUCCION -->
                  <div class="" style="margin-top:45px">
                      <a href="<?php echo site_url($idioma.'plan_produccion/index/'.$p['id_produccion']); ?>" class="button" style="width:100%;margin-top:2px"><?php echo lang('global.produccion') ?></a>
                      <?php  $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
                      $id_user=$this->session->userdata('id_pruduction_suite');
                      $user=$this->model_admin->rolUserId($id_user);
                      $tipo_rol=$user['0']['id_rol_otros'];
                      $sql="";
                        if($tipo_rol==2 or  $tipo_usuario=='2' or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_rol=='6' OR $tipo_usuario=='4' OR $tipo_rol=='8' OR $tipo_rol=='9' OR $tipo_rol=='10' OR $tipo_rol=='11'  OR $tipo_rol=='12' OR $tipo_rol=='13' OR $tipo_rol=='14'){ ?>
                              <a href="<?php echo site_url($idioma.'post_produccion/index/'.$p['id_produccion']); ?>" class="button" style="width:100%;margin-top:2px"><?php echo lang('global.post_produccion') ?></a>
                       <?php } ?>
                      
                  </div>
                  <!-- BOTONES PLAN PRODUCCION Y POSTPRODUCCION -->
                  </div>

                 
                  <div class="column seven textual">
                      <strong><?php echo lang('global.centro_produccion') ?>:</strong><span> <?php echo $centro_produccion['0']->centro ?> </span>
                      <strong><?php echo lang('global.tipo_produccion') ?>:</strong><span> <?php echo $tipo_produccion['0']->tipo ?> </span>
                      <?php $estado=$this->model_produccion->estado_produccion($p['estado']); ?>
                      <strong><?php echo lang('global.estado') ?>:</strong><span> <?php echo $estado['0']->descripcion_estado ?> </span>
                      <strong><?php echo lang('global.productor_ejecutivo') ?>:</strong><span> <?php if($productor_ejecutivo) { echo $productor_ejecutivo['0']->nombre.' '.$productor_ejecutivo['0']->apellido; } ?>   </span>
                      <strong><?php echo lang('global.productor_general') ?>:</strong><span> <?php  if($productor_general) { echo $productor_general['0']->nombre.' '.$productor_general['0']->apellido; } ?> </span>
                      <strong><?php echo lang('global.productor') ?>:</strong><span> <?php if($productor) { echo $productor['0']->nombre.' '.$productor['0']->apellido; }  ?> </span>
                  </div>

                <div class="clr"></div>

              </div>
            </div>

          <?php } ?>
    </div>
    <?php } else {?>
    <h4>No tiene Producciones asignadas</h4>
    <?php } ?>  
  </div>
</div>