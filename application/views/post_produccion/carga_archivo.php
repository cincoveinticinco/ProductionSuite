<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?php echo base_url($idioma.'post_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.capitulos') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<nav>
        <ul class="nav_post">
            <li><a href="<?php echo base_url($idioma.'post_produccion/capitulo/'.$produccion['0']->id_produccion.'/'.$id_capitulo) ?>" class="active"><?php echo lang('global.volver') ?></a></li>
        </ul>
    </nav>
<div data-role="content" id="inner_content"> 
	<div class="upload_files">
		<div class="column six" style="margin:0 auto; float:none;">
			<h4><?php echo lang('global.subir_archivo') ?></h4>
			<?php echo form_open_multipart($idioma.'post_produccion/cargar_archivo','onsubmit="return validar_archivo();"') ?>
			<input type="hidden" name="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
			<input type="hidden" name="id_capitulo" value="<?php echo $id_capitulo ?>">	
			<div class="form">
			    <input type="text" id="path" disabled="disabled" style="width: 65%; margin: 0; height:30px;" />
				<label class="add-photo-btn button" style="width:32%; float:right; margin:0 6px 0 0;"><?php echo lang('global.adjuntar_archuivo') ?><span>
				<input type="file" id="myfile" name="archivo" /></span>
			</label>
			</div>
			<input type="submit" value="<?php echo lang('global.leer_archivo') ?>" class="button" style="width:99%; margin:6px 0 0 0;" >
		</div>
		<div class="clr"></div>
	</div>
</div>