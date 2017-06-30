<h1><?php echo lang('global.locaciones') ?></h1>
<ul>
<?php foreach ($locaciones as $l) { ?>
	<li><?php echo $l['nombre'].'</br>'; ?>
	<label>Sets:</label>
	<?php $sets=$this->model_escenas->set_id_locacion($l['id']) ?>
	<ul>
	<?php foreach ($sets as $s) { ?>
		<li><?php echo $s['nombre']; ?></li>
	<?php	} ?>
	</ul>
	</li>
<?php } ?>	
</ul>