<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <?php echo anchor($idioma.'produccion/producciones',lang('global.inicio')); ?> / <?php echo anchor($idioma.'admin_user/index',lang('global.usuarios')) ?> / <?php echo lang('global.editar_usuario') ?>
  <div class="productionName">
      EDITAR USUARIO 
  </div>
</div>

<div id="inner_content">
  <div class="row">
    <div class="columns twelve">
      <?php echo form_open($idioma.'admin_user/update_user','id="register_form", class="custom"');?>
      <?php echo $msg ?>
      <div class="row row_field">
        <div class="left_dashed" style="width:285px; height:50px;">
          <label for="name_user"><?php echo lang('global.nombre') ?>:</label>
          <input type="hidden"  name="id"  value="<?php echo $user['0']->id?>">
          <input type="text" id="nombre" name="nombre" class="required" value="<?php echo $user['0']->nombre?>">
          <label class="error"><?php echo form_error('nombre'); ?></label>
        </div>
        <div class="left_dashed" style="width:285px; height:50px;">
          <label for="lastname_user"><?php echo lang('global.apellido') ?>:</label>
          <input type="text" id="apellido" name="apellido" class="required" value="<?php echo $user['0']->apellido?>">
          <?php echo form_error('apellido'); ?>
        </div>
        <div class="left_dashed" style="width:285px; height:50px;">
          <label for="email_user"><?php echo lang('global.correo') ?>:</label>
          <input type="text" id="correo" name="correo" class="required email" value="<?php echo $user['0']->correo?>">
          <label class="error"><?php echo form_error('correo'); ?></label>
        </div>
        <div class="left_dashed" style="width:100px; height:50px;">
          <label for="email_user"><?php echo lang('global.idioma') ?>:</label>
          <select name="idioma" class="required" id="idioma" style="display:block!important;">
              <option value="<?php echo $user['0']->idioma?>"><?php if($user['0']->idioma=='es'){ echo 'Español'; }else { echo 'Ingles'; }?></option>
              <option value="es"><?php echo lang('global.espanol') ?></option>
              <option value="en"><?php echo lang('global.ingles') ?></option>
          </select>
          <label class="error"><?php echo form_error('idioma'); ?></label>
        </div>
        <div class="left_dashed" style="width:200px; height:50px;">
          <label><?php echo lang('global.tipo_usuario') ?></label>
          <select name="tipo_user" class="required" id="tipo_user" style="display:block!important;">
            <?php $tipo=$this->model_admin->tipoUserId($user['0']->id);  ?>
            <option value="<?php echo $tipo['0']->id_tipo ?>"><?php echo $tipo['0']->descripcion; ?></option>
          <?php foreach ($tipo_user as $u) { ?>
           <?php if($u['id']!= $tipo['0']->id_tipo ){?>
                <option value="<?php echo $u['id']?>" id="<?php echo $u['descripcion'] ?>"><?php echo $u['descripcion'] ?></option>
           <?php } ?>
          <?php } ?>
          </select>
          <label class="error"><?php echo form_error('tipo_user'); ?></label>
          <?php $estilo = ""; if($tipo['0']->id_tipo != 5){
            $estilo = "display:none";
          } ?>
          <div id="otros" style="<?=$estilo?>">
            <label><?php echo lang('global.Seleccionar_rol_usuario') ?></label>
             <?php foreach ($rol as $r) { ?>
                <?php $user_rol=$this->model_admin->usuario_has_rol($user['0']->id,$r['id'])?>
                <?php if($r['id']!=8){?>
                <?php if($user_rol){?>
                    <input type="checkbox" name="rol[<?php echo $r['id']?>]" value="<?php echo $r['id']?>" id="tipo_user<?php echo $r['id']?>" checked><?php echo $r['descripcion'] ?><br />
                <?php }else { ?>  
                    <input type="checkbox" name="rol[<?php echo $r['id']?>]" value="<?php echo $r['id']?>" id="tipo_user<?php echo $r['id']?>"><?php echo $r['descripcion'] ?><br />        
                <?php } ?>
                <?php } ?>
             <?php } ?>
            <label class="error"><?php echo form_error('rol'); ?></label>
          </div>

         
        </div>
         <div class="left_dashed last" style="width:228px;">
          <label for="lastname_user"><?php echo lang('global.contrasena_aplicacion') ?>:</label>
          <input type="password" id="" name="password" class="" value="">
          <?php echo form_error('apellido'); ?>
        </div>
        <div class="" style="width:228px; padding:11px 0px;">
          <?php echo anchor($idioma.'admin_user',lang('global.volver'), array('class'=>'button secondary')) ?>
        <input type="submit" name="registrar" value="<?php echo lang('global.editar_usuario') ?>" class="button blue">
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
        $('#tipo_user').change(function(){
            var v = $('#tipo_user').val();
            if(v=='5'){
                $('#otros').fadeIn(500);
            }else{
                $('#otros').fadeOut(500);
            }
          });  
 
      }); 
</script>