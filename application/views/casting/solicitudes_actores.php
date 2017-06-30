<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css"> 
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <?php echo lang('global.solicitues_actores') ?>
</div>


<div id="inner_content">
     
     <?php if($solicitudes_pendientes){ ?>
     <div class="columns twelve">
      <div class="column title_section"><h5><?php echo lang('global.solicitues_pendientes') ?></h5></div>
      <div class="info with_title">
        <table class="tablesorter tabla_detalle_actor">
           <thead>
              <tr>
                <td><?php echo lang('global.actor') ?></td>
                <td><?php echo lang('global.manager_solicitante') ?></td>
                <td><?php echo lang('global.correo_solicitante') ?></td>
                <td><?php echo lang('global.manager_actual') ?></td>
                <td><?php echo lang('global.correo_manager_actual') ?></td>
                <td><?php echo lang('global.accion') ?></td>
              </tr>
           </thead>
           <tbody>
            <?php foreach ($solicitudes_pendientes as $s) { ?>
              <tr class="solicitud_<?php echo $s['id'] ?>">
               <td><?php echo $s['nombre'] ?> <?php echo $s['apellido'] ?></td>
               <td><?php echo $s['manager_solicitante'] ?></td>
               <td><?php echo $s['email_solicitante'] ?></td>
               <td><?php echo $s['manager_actual'] ?></td>
               <td><?php echo $s['corre_manager_actual'] ?></td>
               <td data-id="<?php echo $s['id'] ?>" data-idactor="<?php echo $s['id_actor'] ?>" data-idmanager="<?php echo $s['id_manager'] ?>"
               data-actor="<?php echo $s['nombre'] ?> <?php echo $s['apellido'] ?>" data-manager="<?php echo $s['manager_solicitante'] ?>"
               data-manageractual="<?php echo $s['manager_actual'] ?>"
               data-idmanageractual="<?php echo $s['id_manager_actual'] ?>" data-view="1"
                class="asignar_actor"><?php echo lang('global.asignar') ?></td>
              </tr>
          <?php } ?>
          </tbody>
        </table> 
        </div>
      </div>
     
      <?php } ?>

      <?php if($solicitudes_actuales){ ?>
       <div class="columns twelve">
        <div class="column title_section"><h5><?php echo lang('global.solicitudes_resueltas') ?></h5></div>
        <div class="info with_title">
          <table class="tablesorter tabla_detalle_actor">
             <thead>
                <tr>
                  <td><?php echo lang('global.actor') ?></td>
                  <td><?php echo lang('global.manager_solicitante') ?></td>
                  <td><?php echo lang('global.correo_solicitante') ?></td>
                  <td><?php echo lang('global.manager_anterior') ?></td>
                  <td><?php echo lang('global.correo_manager_anterior') ?></td>
                  <td><?php echo lang('global.usuario_aprobacion') ?></td>
                  <td><?php echo lang('global.fecha_aprobacion') ?></td>
                </tr>
             </thead>
             <tbody>
            <?php foreach ($solicitudes_actuales as $s) { ?>
              <tr class="solicitud_<?php echo $s['id'] ?>">
               <td><?php echo $s['nombre'] ?> <?php echo $s['apellido'] ?></td>
               <td><?php echo $s['manager_solicitante'] ?></td>
               <td><?php echo $s['email_solicitante'] ?></td>
               <td><?php echo $s['manager_actual'] ?></td>
               <td><?php echo $s['corre_manager_actual'] ?></td>
               <td><?php echo $s['user_aprobacion'] ?></td>
               <td><?php echo $s['fecha_aprobacion'] ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table> 
        </div>
        </div>
      <?php } ?>
      <?php if(!$solicitudes_pendientes and !$solicitudes_actuales){ ?>
         <?php echo lang('global.no_hay_solicitudes') ?>
      <?php } ?>
</div>
