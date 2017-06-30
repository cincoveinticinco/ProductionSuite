  <?php $idioma = $this->lang->lang().'/'; ?>
  <nav>
  <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
  <!--<a href="escenas.html">ESCENAS</a>
  <!--<a href="escenas.html">ESCENAS</a>
  <a href="elementos.html">ELEMENTOS</a>-->
  <a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
  <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
  <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
  <!--<a href="index.html">CERRAR SESIÓN</a>-->
</nav>
<div class="clr"></div>
<div class="resumen">
	<h2><?php echo lang('global.crear_continuidad') ?></h2>
	<?php $id_user=$this->model_admin->tipoUserId($this->session->userdata('id_production_suite'));
              if($id_user){
                 if($id_user['0']->id_tipoUsuario==5){
                   $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite'));
                   $rol=$user['0']['descripcion'];   
                 }else{
                   $rol=$id_user['0']->descripcion;   
                 }
              }else{
                $rol='';
              }
      ?>
</div>
<div class="divider"></div>
<div id="contenido">
<?php echo form_open_multipart($idioma.'continuidad/guardar_foto_set','onsubmit="return validar_form_set()"') ?>
   <input type="hidden" name="id_produccion" value="<?php echo $id_produccion ?>">
	<table class="crear_continuidad">
		<tr>
			<td colspan="4"style="padding: 0 8px 0 0; width: 320px">
				<ul class="list_fotos_sets"></ul>
				<span id="loader_gif" style="display:none"><img src="<?php echo $idioma.base_url() ?>images/loader.gif"></span>
				<div class="upload_img">
					<input type="file" id="fotos_sets" name="filesToUpload[]" multiple="">
					<input type="text" name="imagenes_eliminadas" class="imagenes_eliminadas" value="">
				</div>
					
						<div class="cantidad">
							<select name="cantidad_fotos" class="cantidad_fotos" style="display:none">
								<option value="1"><?php echo lang('global.foto1') ?></option>
								<option value="2"><?php echo lang('global.foto2') ?></option>
								<option value="3"><?php echo lang('global.foto3') ?></option>
								<option value="4"><?php echo lang('global.foto4') ?></option>
								<option value="5"><?php echo lang('global.foto5') ?></option>
								<option value="6"><?php echo lang('global.foto6') ?></option>
								<option value="3"><?php echo lang('global.foto7') ?></option>
								<option value="4"><?php echo lang('global.foto8') ?></option>
								<option value="5"><?php echo lang('global.foto9') ?></option>
								<option value="6"><?php echo lang('global.foto10') ?></option>
							</select>
						</div>
						<div class="cargar_imagenes" style="display:none">
	                       <ul class="tomar_list_fotos_sets"></ul>
						</div>
						<div class="tomar_foto btn_volver_plan"><?php echo lang('global.tomar_foto') ?></div>
				</div>
			</td>
		</tr>
		<tr>
			<td>

				<p><?php echo lang('global.locacion') ?>:</p>
				<select name="locacion" class="locacion" style="width:100%;">
				  <option value="null"><?php echo lang('global.seleccion_locacion') ?></option>
				  <?php foreach ($locaciones as $l) { ?>
				  	  <option value="<?php echo $l['id'] ?>" ><?php echo $l['nombre'] ?></option>
				  <?php } ?>
				</select>
			</td>
			<?php if($set){ ?>
			<td>
				<div class="sets">
					
					  <p>SET:</p>
					  <select name="set" class="set" style="width:100%;">
					  <?php foreach ($set as $e) { ?>
					      <option value="<?php echo $e['id'] ?>" <?php if($e['id']==$escena){?>selected <?php } ?>><?php echo $e['numero_escena'] ?></option>';
					  <?php } ?>
					</select>
					
				</div>
			</td>
			<?php }else{ ?>
			<td>
				<div class="sets" id="sets_carga">
				</div>
			</td>	
			<?php } ?>
		</tr>
		<tr>
			<td colspan="4">
				<p><?php echo lang('global.comentarios') ?>:</p>
				<textarea name="comentario" class="nota"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="4"> 
			    <input type="hidden" class="imagen" value="">
			    <?php if($this->session->userdata('id_produccion')){ ?>
			    	<a action="action" href="<?=base_url().$idioma.'/continuidad/plan_diario/'.$this->session->userdata('id_produccion').'/'.$this->session->userdata('id_unidad').'/'.$this->session->userdata('fecha_unidad')?>" class="btn_naranja" style="float:left; padding:7px 0px; margin:0px 1% 0 0; width:48.5% !important;"><?php echo lang('global.cancelar') ?></a>
			    <?php } else {?> 
				    <button action="action" onclick="history.go(-1); return false;" class="btn_naranja" style="float:left; padding:7px 0px; margin:0px 1% 0 0; width:49.5% !important;"><?php echo lang('global.cancelar') ?></button>
			    <?php } ?> 
			    <button type="submit" class="btn_volver_plan" style="float:left; width:49.5% !important;"><?php echo lang('global.crear_continuidad') ?></button>
			</td>
		</tr>
	</table>
	<?php echo form_close(); ?>
</div>
