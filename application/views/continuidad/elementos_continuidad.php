  <?php $idioma = $this->lang->lang().'/'; ?>
      <nav>
      <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
      <a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
      <?php $produccion=$this->model_plan_produccion->produccion_id($id_produccion);if($produccion['0']->estado!=2){   ?>
      <?php $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite')); 
      if($user){
        if($user[0]['id_rol_otros']!=2 and $user[0]['id_rol_otros']!=6){ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
        <?php } ?>
      <?php }else{ ?>
        <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
        <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
      <?php } ?>
      <?php } ?>
      
    </nav>
          <div class="clr"></div>
    <div class="gray_zone">
            <div class="small-4 medium-3 columns">
              <label><?php echo lang('global.dia_de_continuidad') ?></label>
              <?php if($cont and $cont!='null'){ ?>
            
              <input type="text" value="<?=$cont?>" class="cont_personaje continuidad">
              <?php }else{ ?>
                <input type="text" value="" class="cont_personaje continuidad">
              <?php } ?>
            </div>
            <div class="small-4 medium-3 columns">
              <label><?php echo lang('global.personajes') ?></label>
              <input type="text" value="<?=$palabra?>" class="cont_personaje personaje" id="personaje_general" >
            </div>
            <div class="small-4 medium-2 columns end">
              <a  class="colapsar button expanded" style="margin-top: 25px;"><?php echo lang('global.filtrar') ?></a>
            </div>
          </div>

          <div id="accordion" style="margin: 0 0 25px 0;" class="elementos_continuidad">
            <input type="hidden" name="permisos_usuario" id="permisos_usuario" value="<?=$permisos_usuario?>">
		      <?php foreach ($rol_actores_elementos as $r) { ?>
                <!--span class="count_characters"></span-->
								<h3 class="rol" data-rol="<?php echo $r['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>"><?php echo $r['rol'] ?><span class="count_characters"></span></h3>

								<div>
                  <div class="gray_zone">
                    <label style="font-size:16px; padding:0 10px;"><?php echo lang('global.filtrar') ?></label>
                    <input type="text" value="" class="cont_personaje personaje" data-rol="<?php echo $r['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>">
                  </div>
                  <div id="listado_<?php echo $r['id'] ?>" style="display: block; width: 99%; margin: 0px auto 0px 12px; height: 10px "></div>
								  <ul ></ul> 
								  <input type="hidden" value="10" class="limit_<?php echo $r['id'] ?>">
                  <input type="hidden" class="id_produccion" value="<?php echo $id_produccion ?>">
								  <span class="cargar_mas" data-rol="<?php echo $r['id'] ?>" data-idproduccion="<?php echo $id_produccion ?>"><?php echo lang('global.cargar_mas') ?></span>
								</div>
		      <?php } ?>
          <h3><?php echo lang('global.locaciones') ?></h3>
          <div>
            <div id="acc_locaciones">
            <?php if($locaciones){ ?>
                <?php foreach ($locaciones as $l) { ?>
                    <h4 data-idlocacion="<?php echo $l['id'];  ?>" data-idproduccion="<?php echo $id_produccion ?>" class="locaciones"><?php echo $l['nombre'] ?></h4>
                    <div style="display: block; width: 98.6%; margin: 0px auto; height: 10px "><div id="sets_<?php echo $l['id'];  ?>"  class="sets" ></div></div>
                  
                <?php } ?>
             <?php }else{ ?>
              <div><?php echo lang('global.no_hay_locaciones_para_esta_produccion') ?></div>    
            <?php } ?>
          
            </div>
          

          </div>
          <input type="hidden" value="10" class="limit">
			