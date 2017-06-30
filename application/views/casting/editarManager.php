<?php $idioma = $this->lang->lang().'/'; ?>
<!-- CASTING / Manager-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.editar_manager') ?>
</div>

<nav>
    <ul class="nav_post nav_casting">

    </ul>
</nav>

<div id="inner_content">
  <?php echo form_open_multipart($idioma.'casting/updateManager',"id='myform'");?>
        <div class="row">
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.formulario_editar_manager') ?></h5></div>
            <div class="info with_title">


	              <input name="id" type="hidden" class="required" value="<?php echo $datos['0']->id; ?>" readonly>

            	  <div class="column two">
	                <label for=""><?php echo lang('global.nombre') ?>:</label>
	                <input name="nombre" type="text" class="required" value="<?php echo $datos['0']->nombre; ?>" readonly>
	                <?php echo form_error('nombre') ?>
	              </div>

	              <div class="column three">
	                <label for=""><?php echo lang('global.correo') ?>:</label>
	                <input name="email" type="text" class="required" value="<?php echo $datos['0']->email; ?>" readonly>
	                <?php echo form_error('email') ?>
	              </div>

	              <div class="column two">
	                <label for=""><?php echo lang('global.contrasena') ?>:</label>
	                <input name="contrasena" type="password" class="required"  id="contrasena">
	                <?php echo form_error('contrasena') ?>
	              </div>

	              <div class="column two">
	                <label for=""><?php echo lang('global.repetir') ?> <?php echo lang('global.contrasena') ?>:</label>
	                <input name="contrasena_repetir" type="password" class="required"  id="contrasena_repetir">
	              </div>
	               <div class="column two">
	               <label>&nbsp;</label>
	                <input type="submit" name="Guardar" class="button twelve" value="<?php echo lang('global.guardar') ?>">
	              </div>    
	              <div class="column one">
	               <label>&nbsp;</label>
	                <a class="button" href="<?php echo base_url($idioma.'casting/crearManager') ?>"><?php echo lang('global.volver') ?></a>
	              </div>  
                  <div class="clr"></div>      

            </div>
          </div>              
        </div>
       
  <?php echo form_close(); ?> 
</div>


<script>
	$(document).ready(function() {	

		$( "#myform" ).validate({
		  rules: {
		    contrasena: "required",
		    contrasena_repetir: {
		    required: true,
		      equalTo: "#contrasena"
		    }
		  }
		});

	});
</script>}



