  <?php $idioma = $this->lang->lang().'/'; ?>
  <div class="clr"></div>
<div class="resumen">
	<h2><?php echo lang('global.cargar_imagen') ?></h2>
</div>
<div class="divider"></div>
<div class="contenido">
	<div class="subir_foto">
		<div id="innerImg">
			
		</div>
<?php /* ?>
		<div class="loading_img">
			Cargando Archivo...<br><img src="<?php echo base_url(); ?>images/cargando.gif" alt="">
		</div>
<?php */ ?>		
		<?php echo form_open_multipart($idioma.'continuidad/crear_continuidad') ?>
		<div class="upload">
			<p><?php echo lang('global.cargar_imagen') ?></p>
			<input type="hidden" name="id_produccion" value="<?php echo $id_produccion ?>">
			<input type="file"  id="btn_subir_foto" name="imagen"> 
			<?php echo form_error('imagen'); ?>
		</div>
		<input type="submit" class="btn_send_image"  value="Guardar" style="height:48px; font-size:17px;">
		<?php echo form_close() ?>
	</div>
</div>