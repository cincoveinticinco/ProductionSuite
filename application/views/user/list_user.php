<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <?php echo anchor($idioma.'produccion/producciones',lang('global.inicio')); ?> / <?php echo lang('global.usuarios') ?>
</div>

<!-- <div id="page_inner" class="row"> -->

  	<div class="columns twelve">
  		<div class="clr8"></div>
  		<h2><?php echo lang('global.usuarios') ?></h2>
  		<?php echo $msg ?>
  		<?php if(isset($idproduccion)){?>
  			<input type="hidden" name="idproduccion" value="<?=$idproduccion?>">
  		<?php } ?>
  		<div class="columns Five">
  			<input type="text" id="buscar_usuarios" idproduccion="" class="search_input" style="padding-left: 400px;">
  		</div>
  		<div class="columns Five">
  			<select id="productions_list" style="padding-right: 246px;">
  				<option>Todos</option>
  				<?php if($producciones){
  				      foreach ($producciones as $produccion) {?>
  						<option value="<?=$produccion['id']?>" <?php if($id_produccion==$produccion['id']){ echo 'selected';} ?>><?=$produccion['nombre_produccion']?></option>
  				<?php } } ?>
  			</select>
  		</div>
  		<div class="columns Five">
  			<select id="estado_user" style="padding-right: 246px;">
  					<option value="1" <?php if($estado==1){ echo 'selected';} ?>><?php echo lang('global.activos') ?></option>
  					<option value="0" <?php if($estado==0){ echo 'selected';} ?>><?php echo lang('global.inactivos') ?></option>
  					<option value="3" <?php if($estado==3){ echo 'selected';} ?>><?php echo lang('global.todas') ?></option>
  			</select>
  		</div>
  		<div class="columns Five">
  		<?php echo anchor($idioma.'admin_user/registro',lang('global.crear_usuario'), array('class'=>'button','style'=>'padding: 0 53px','height'=>'29px')) ?>
  		</div>
		<div class="normal_table">
			<table id="usersTable" class="tablesorter">
				<thead>
					<tr>
						<th width="12%"><?php echo lang('global.nombre') ?></th>
						<th width="12%"><?php echo lang('global.apellido') ?></th>
						<th width="24%"><?php echo lang('global.correo') ?></th>
						<th width="10%"><?php echo lang('global.idioma') ?></th>
						<th width="20%"><?php echo lang('global.tipo_usuario') ?></th>
						<th width="9%"><?php echo lang('global.estado') ?></th>
						<th width="12%"><?php echo lang('global.acciones') ?></th>
					</tr>
				</thead>
				<tbody id="user_list">
				<?php $cont=0; 
				if($user){
				foreach ($user as $u ) {?>
					<?php
					/*++$cont; 
					if($cont%2==0){
						echo '<tr class="gray_light">';
					}else{
						echo '<tr class="white">';
					}*/
					?>	
					<tr class="user_produccion" data-id="<?php echo $u['id'] ?>">
					
						<td><?php echo $u['nombre'] ?></td>
						<td><?php echo $u['apellido'] ?></td>
						<td><?php echo $u['correo'] ?></td>
						<td><?php if($u['idioma']=='es'){ echo 'Español'; } else { echo 'Ingles'; } ?></td>

						<td>
						<?php $tipo=$this->model_admin->tipoUserId($u['id']); 
						echo $tipo['0']->descripcion; ?>

						<?php if($tipo['0']->descripcion=='Otros'){ ?>:
							
							<?php $rol=$this->model_admin->rolUserId($u['id']); ?>

							<?php if($rol) {foreach ($rol as $r) { ?>
								<strong>
								<?php echo $r['descripcion'].', '; ?>
								</strong>
							<?php } } ?>
						
						<?php } ?>

						</td>
						
						<td style="text-align:center;">
							<?php if($u['estado']==1){ ?>
								<?php echo 'Activo'; ?>
							<?php } else { ?>
								<?php echo 'Inactivo';?>
							<?php } ?>
							
						</td>
						<td style="text-align:center;">
							<?php echo anchor($idioma.'admin_user/editar_user/'.$u['id'],lang('global.guardar')); ?> |
							<?php if($u['estado']==1){ ?>
							<a class="cambiar_user" data-iduser="<?php echo $u['id'] ?>" data-estado="0"> <?php echo lang('global.desactivar') ?></a>
							<?php } else { ?>
							<a class="cambiar_user" data-iduser="<?php echo $u['id'] ?>" data-estado="1"> <?php echo lang('global.activar') ?></a>
							<?php } ?> |
							<a href="<?php echo base_url($idioma.'admin_user/eliminarUser/'.$u['id']) ?>" onclick="return confirm('Esta seguro de elimiar este Usuario?')" ><?php echo lang('global.eliminar') ?></a> 
						</td>
						 
						
					</tr>
					<tr style="display:none" id="user_<?php echo $u['id']; ?>" class="producciones_usuario">
						
					</tr>
				
				<?php  } ?>
				<?php }else{ ?>
				 <tr>
				  <td colspan="7"><?php echo lang('global.no_hay_usuarios') ?></td>
				 </tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		
	</div>
