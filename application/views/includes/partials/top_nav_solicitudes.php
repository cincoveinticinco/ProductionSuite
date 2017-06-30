<?php 
   $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
   if($tipo_usuario=='1' OR $tipo_usuario=='9'){ ?>
            <?php $solicitudes_actor=$this->model_casting->solicitudes_actor_asignacion(0); ?>
            <?php if($solicitudes_actor){ ?>
            <nav>
              <div class="alert_solicitud_actores"> 
                <?php echo lang('global.msj1_top_nav').' '.count($solicitudes_actor).' '.lang('global.msj2_top_nav'); ?>
              </div>
              <div class="info info_solicitud_actores">
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
                    <?php foreach ($solicitudes_actor as $s) { ?>
                      <tr class="solicitud_<?php echo $s['id'] ?>">
                       <td><?php echo $s['nombre'] ?> <?php echo $s['apellido'] ?></td>
                       <td><?php echo $s['manager_solicitante'] ?></td>
                       <td><?php echo $s['email_solicitante'] ?></td>
                       <td><?php echo $s['manager_actual'] ?></td>
                       <td><?php echo $s['corre_manager_actual'] ?></td>
                       <td data-id="<?php echo $s['id'] ?>" data-idactor="<?php echo $s['id_actor'] ?>" data-idmanager="<?php echo $s['id_manager'] ?>"
                       data-actor="<?php echo $s['nombre'] ?> <?php echo $s['apellido'] ?>" data-manager="<?php echo $s['manager_solicitante'] ?>"
                       data-manageractual="<?php echo $s['manager_actual'] ?>"
                       data-idmanageractual="<?php echo $s['id_manager_actual'] ?>" data-view="0"
                        class="asignar_actor"><?php echo lang('global.asignar') ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table> 
                </div>
            </nav>
            <?php } ?>
     <?php } ?>