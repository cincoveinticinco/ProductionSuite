  <?php $idioma = $this->lang->lang().'/'; ?>
  <nav>
  <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
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
<?php echo form_open_multipart($idioma.'continuidad/guardar_continuidad','onsubmit="return guardar_continuidad()"') ?>
	<div class="small-12 columns crear_continuidad">
		<div class="small-12 columns">
			<ul class="list_fotos_sets"></ul>
			<span id="loader_gif" style="display:none"><img src="<?php echo $idioma.base_url() ?>images/loader.gif"></span>
			<div class="upload_img">
				<input class="button expanded" type="file" id="fotos_sets" name="filesToUpload[]" multiple="">
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
				</select>
			</div>
			<div class="cargar_imagenes" style="display:none">
               <ul class="tomar_list_fotos_sets"></ul>
			</div>
			<!-- <div class="tomar_foto btn_volver_plan" style="width:281px !important"><?php echo lang('global.tomar_foto') ?></div> -->
		</div>
		<div class="small-12 columns">
				
			<div class="small-6 medium-3 columns" colspan="">
				<p><?php echo lang('global.tipo_imagen') ?></p>
				<?php if($rol=='Maquillaje'){?>
					<select name="tipo_imagen" class="tipo_imagen" style="width:100%;">
					   	 	<option value="2"><?php echo $user['0']['descripcion'] ?></option>
					</select>
				<?php }elseif($rol=='Vestuario'){ ?>	
				     <select name="tipo_imagen" class="tipo_imagen" style="width:100%;">
					   	 	<option value="3"><?php echo $user['0']['descripcion'] ?></option>
					</select>
				<?php }else{ ?>
					<?php if($tipo_imagen){ ?>
						<select name="tipo_imagen" class="tipo_imagen" style="width:100%;">
					   		<?php foreach ($tipo_imagen as $t) { ?>
					   	 		<option value="<?php echo $t['id'] ?>"><?php echo $t['tipo'] ?></option>
					   		<?php } ?>
							
					</select>
					<?php } ?>
				<?php } ?>	
			</div>
			<div class="small-6 medium-3 columns">
				<p><?php echo lang('global.libreto') ?>:</p>
				<select name="libreto" class="libreto" style="width: 100%;">
				  <option value="null"><?php echo lang('global.selecionar_libreto') ?></option>
				  <?php foreach ($libretos as $l) { ?>
				  	  <option value="<?php echo $l['id_capitulo'] ?>" <?php if($l['id_capitulo']==$libreto){?>selected <?php } ?>><?php echo $l['numero'] ?></option>
				  <?php } ?>
				</select>
			</div>
			<?php if($escenas){ ?>
			<div class="small-6 medium-3 columns">
				<div class="escenas_libreto">
				
					  <p><?php echo lang('global.escenas') ?>:</p>
					  <select name="escenas" class="escenas">
					  <?php foreach ($escenas as $e) { ?>
					      <option value="<?php echo $e['id'] ?>" <?php if($e['id']==$escena){?>selected <?php } ?>><?php echo $e['numero_escena'] ?></option>';
					  <?php } ?>
					</select>
				
				</div>
			</div>
			<?php }else{ ?>
			<div class="small-6 medium-3 columns">
				<div class="escenas_libreto">
				</div>	
			</div>		
			<?php } ?>	
			<div class="small-6 medium-3 columns">
				<div>
					<p><?php echo lang('global.continuidad') ?>:</p>
					<input type="hidden" class="id_produccion" name="id_produccion" value="<?php echo $id_produccion ?>">
					<?php if($cont=='null' or !$cont){ ?>
					<input type="text" class="dia_continuidad" name="dia_continuidad" value="">
				    <?php }else{ ?>
				     <input type="text" class="dia_continuidad" name="dia_continuidad" value="<?php echo $cont ?>">
				    <?php } ?>
					
					<!-- btn_send_image -->
					<div class="clr"></div>
				</div>
			</div>
			<div class="small-12 columns">
				<div class="personajes">
				     <?php if($personajes){ ?>
				     	<select name="personajes" id="personajes_cont" class="personaje_continuidad">
				     	<option><?php echo lang('global.seleccionar_presonaje') ?></option>
					     	<?php foreach ($personajes as $p) { ?>
					     	        <?php if($p['id_elemento']!=$id_elemento){ ?>
	                         	 	 	<option value="<?php echo $p['id_elemento'] ?>" data-nombre="<?php echo $p['nombre'] ?>"><?php echo $p['nombre'] ?></option>
	                                <?php } ?>  
	                         <?php } ?>
				     <?php } ?>
				</div>
			</div>
			<div class="small-12 columns">
				<div id="personajes_select">
                 <?php if($personaje){ ?>
	                 <div class="date_personaje_0 contend_per">
	                 	<input type="hidden" value="<?php echo $personaje['0']->id_elemento ?>" name="id_personaje_0" class="id_personaje_0">
	                 	<input type="text" value="<?php echo $personaje['0']->nombre ?>" name="nombre_0" class="nombre_0">
	                 	<div class="button alert expanded" data-posicion="0"><?php echo lang('global.eliminar') ?>
	                 	</div>
	                 </div>
	                 <script type="text/javascript">num_per=1</script>
	                 <?php } ?>
	                 <input type="hidden" name="personajes_array" class="personajes_array">
				</div>
			</div>
			<div class="small-12 columns">
				<label for="">
					<?php echo lang('global.comentarios') ?>
				</label>
				<textarea name="nota" class="nota" id="nota"></textarea>
			</div>
			<div class="small-12 columns">
				<input type="hidden" class="imagen" value="">
			    <?php if($this->session->userdata('id_produccion')){ ?>
			    	<a action="action" href="<?=$idioma.base_url().'continuidad/plan_diario/'.$this->session->userdata('id_produccion').'/'.$this->session->userdata('id_unidad').'/'.$this->session->userdata('fecha_unidad')?>" class="button alert expanded" style="float:left; padding:7px 0px; margin:0px 1% 0 0; width:48.5% !important;"><?php echo lang('global.cancelar') ?></a>
			    <?php } else {?> 
				    <button action="action" onclick="history.go(-1); return false;" class="button alert expanded"><?php echo lang('global.cancelar') ?></button>
			    <?php } ?> 
			    <button type="submit" class="button expanded" style="float:left; width: 49.4% !important;"><?php echo lang('global.crear_continuidad') ?></button>
			</div>
			<!-- <div id="continuidad_produccion" class="btn_naranja" style="width:100%; padding:7px 0px; margin:3px 0px;"><?php echo lang('global.cargar') ?></div> -->
		</div>
		
	
	<table class="crear_continuidad">
		<tr>
			<td colspan="4" style="/*padding: 0 8px 0 0;*/">
				
			</td>
		</tr>
		<tr>
			
		</tr>
		<tr><td colspan="4"></td></tr>
		<tr>
			<td colspan="">
				
			</td>
		</tr>
		<tr>
			<td colspan="4">
				
			</td>
		</tr>
		<tr>
			
		</tr>
		<tr>
			<td colspan="4"> 
			    
			</td>
		</tr>
	</table>
	</div>
	<?php echo form_close(); ?>
</div>
