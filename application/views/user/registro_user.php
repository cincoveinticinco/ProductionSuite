<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <?php echo anchor($idioma.'produccion/producciones',lang('global.inicio')); ?> / <?php echo anchor($idioma.'admin_user/index',lang('global.usuarios')) ?> / <?php echo lang('global.crear_usuario') ?>
</div>
<div class="row" style="padding:10px 0;">
  <div class="columns centered">
    <?php echo form_open($idioma.'admin_user/insert_user','id="register_form", class=""');?>
    <h2><?php echo lang('global.registrar_nuevo_usuario') ?></h2>
      <?php echo $msg ?>
      <div class="column four left_dashed" style="width:32%;">
          <label for="name_user"><?php echo lang('global.nombre') ?>:</label>
          <input type="text" id="nombre" name="nombre" class="required" value="<?php echo set_value('nombre');?>">
          <label class="error"><?php echo form_error('nombre'); ?></label>
      </div>
      <div class="column four left_dashed" style="width:32%;">
        <label for="lastname_user"><?php echo lang('global.apellido') ?>:</label>
        <input type="text" id="apellido" name="apellido" class="required" value="<?php echo set_value('apellido');?>">
        <?php echo form_error('apellido'); ?>
      </div>
      <div class="column four left_dashed" style="width:32%;">
        <label for="email_user"><?php echo lang('global.correo') ?>:</label>
        <input type="text" id="correo" name="correo" class="required email" value="<?php echo set_value('correo');?>">
        <label class="error"><?php echo form_error('correo'); ?></label>
      </div>
      <div class="column four left_dashed" style="width:32%;">
        <label for="email_user"><?php echo lang('global.idioma') ?>:</label>
        <select name="idioma" class="required" id="idioma">
          <option value="" class="required"><?php echo lang('global.seleccionar_idioma') ?></option>
          <option value="es"><?php echo lang('global.espanol') ?></option>
          <option value="en"><?php echo lang('global.ingles') ?></option>
        </select>
        <label class="error"><?php echo form_error('idioma'); ?></label>
      </div>
      <div class="column four left_dashed" style="width:32%;">
        <label><?php echo lang('global.tipo_usuario') ?></label>
        <select name="tipo_user" class="required" id="tipo_user">
        <?php foreach ($tipo_user as $u) { ?>
          <option value="<?php echo $u['id']?>" id="<?php echo $u['descripcion'] ?>"><?php echo $u['descripcion'] ?></option>
        <?php } ?>
        </select>
        <label class="error"><?php echo form_error('tipo_user'); ?></label>
        <div id="otros" style="display:none;">
        <label><?php echo lang('global.seleccionar_rol_usuario') ?></label>
        <br>
         <?php foreach ($rol as $r) { ?>
         <?php if($r['id']!=8){?>
            <input type="checkbox" name="rol[<?php echo $r['id']?>]" value="<?php echo $r['id']?>" id="tipo_user<?php echo $r['id']?>"><?php echo $r['descripcion'] ?><br>
         <?php } ?>
         <?php } ?>
        <label class="error"><?php echo form_error('rol'); ?></label>
        </div>
      </div>

      <div class="column four left_dashed last" style="width:32%;">
        <label for="name_user"><?php echo lang('global.conasena_aplicaciones') ?>:</label>
        <input type="password" id="password" name="password" class="" value="<?php echo set_value('password');?>">
        <label class="error"><?php echo form_error('password'); ?></label>
      </div>

      <div class="" style="width:32%;">
        <?php echo anchor($idioma.'admin_user/index',lang('global.volver'), array('class'=>'button secondary')) ?>
        <input type="submit" name="registrar" value="<?php echo lang('global.registrar') ?>" class="button blue">
      </div>
    <?php echo form_close(); ?>   
  </div>
</div>

<script type="text/javascript">
$('#tipo_user').change(function(){
  var v = $('#tipo_user').val();
  if(v=='5'){
      $('#otros').fadeIn(500);
  }else{
      $('#otros').fadeOut(500);
  }
}); 
</script>