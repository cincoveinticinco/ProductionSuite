
			<h4>Subir Archivo EDL</h4>
			<?php echo form_open_multipart('carga_archivo/cargar_archivo') ?>

			<div class="form">
			    <input type="text" id="path" disabled="disabled" style="width: 65%; margin: 0; height:30px;" />
				<label class="add-photo-btn button" style="width:32%; float:right; margin:0 6px 0 0;">Adjuntar Archivo<span>
				<input type="file" id="myfile" name="archivo" /></span>
			</label>
			</div>
			<input type="submit" value="Leer Archivo" class="button" style="width:99%; margin:6px 0 0 0;">
		</div>
		<div class="clr"></div>
	</div>
</div>