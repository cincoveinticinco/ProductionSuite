<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
    <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>">Home</a> 
    / <a  href="<?php echo base_url($idioma.'plan_produccion/index/'.$produccion['0']->id_produccion); ?>"><?php echo $produccion['0']->nombre_produccion ?></a> 
     
    / <?php echo lang('global.escenas') ?> <?php echo $escena[0]->numero_libreto.'/'.$escena[0]->numero_escena ?>
    <div class="productionName"><?php echo $produccion['0']->nombre_produccion ?></div>
</div>
<table>
    <tr class="info_escena">
        <td colspan="5">
            <div class="row">
                <table class="tabla_info">
                  <tr>
                    <td>
                      <label>PÁG.  <?php echo lang('global.guion') ?>: </label> <span><?= $escena[0]->libreto?></span>
                    </td>
                    <td>
                      <label><?php echo lang('global.locacion') ?> / <?php echo lang('global.estudio') ?>: </label> <?php if($escena[0]->id_tipo_locacion==1){echo "Locación";}?> <?php if($escena[0]->id_tipo_locacion==2){echo "Estudio";}?><?php if($escena[0]->id_tipo_locacion==3){echo "Toma Ubicación";}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.dia_noche') ?>:  </label> <?php if($escena[0]->id_dia_noche==1){echo 'Día';}?> <?php if($escena[0]->id_dia_noche==2){echo 'Noche';}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.int_ext') ?>: </label> <?php if($escena[0]->id_interior_esterior==1){echo 'Interior';}?> <?php if($escena[0]->id_interior_esterior==2){echo 'Exterior';}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.flashback') ?>: </label> <?php if($escena[0]->id_flasback==1){echo 'Si';}?> <?php if($escena[0]->id_flasback!=1){echo 'No';}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.toma_ubicacion') ?>: </label> <?php if($escena[0]->id_toma_ubicacion==1){echo 'Si';}?> <?php if($escena[0]->id_toma_ubicacion!=1){echo 'No';}?>
                    </td>
                     <td>
                      <label><?php echo lang('global.magnitud') ?>: </label> <?php if($escena[0]->magnitud){echo $escena[0]->magnitud;}else{ echo '-';}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.capitulos') ?>: </label> <?php if($escena[0]->vehiculo_background){echo $escena[0]->vehiculo_background;}else{ echo '-';}?>
                    </td>
                    <td>
                      <?php $capitulo=$this->model_escenas_2->capitulo_idescena($escena[0]->id) ?>
                      <label><?php echo lang('global.capitulos') ?>:  </label> <?php if($capitulo){echo $capitulo['0']->numero;}else{ echo '-';}?>
                    </td>
                    <td>
                      <label><?php echo lang('global.fecha_post_produccion') ?>:  </label> <?php if($capitulo and $capitulo['0']->fecha_entregada and $capitulo['0']->fecha_entregada!='0000-00-00'){echo date("Y-M-d",strtotime($capitulo['0']->fecha_entregada));}else{ echo '-';}?>
                    </td>
                  </tr>
                </table>
                <table class="tabla_info">
                  <tr>
                    <td style="vertical-align:top;" width="50%">
                            <label><?php echo lang('global.descripcion_escena') ?>: </label>
                        <?php if ($escena[0]->descripcion){ ?>
                            <?= $escena[0]->descripcion?>
                        <?php } else { ?>
                            <?php echo lang('global.escena_no_descripion') ?>
                        <?php } ?>
                    </td>
                    <td style="vertical-align:top;" width="50%">
                      <label><?php echo lang('global.guion') ?>: </label>
                        <?php if ($escena[0]->guion){ ?>
                            <textarea style="height:250px"><?= $escena[0]->guion?></textarea>
                        <?php } else { ?>
                                                <?php echo lang('global.escena_no_guion') ?>
                        <?php } ?>
                    </td>
                  </tr>
                </table>
            </div> 
            <br>

                                <h5><?php echo lang('global.elementos_asignados') ?></h5>
            <?php $elementos_escena = $this->model_escenas_2->buscar_elementos($escena[0]->id);
                  $personajes_escena = $this->model_escenas_2->buscar_personajes_escena($escena[0]->id); ?>
            <?php if($personajes_escena OR $elementos_escena){ 
                 $rol_temp =""; ?>
                <table class="tabla_info">
                    <tr>
                    <?php if($personajes_escena){?>
                        <?php foreach ($personajes_escena as $personaje_escena) { ?>
                            <?php if($personaje_escena->rol!=$rol_temp){?>
                                <td><label><?=$personaje_escena->rol?></label></td>
                            <?php $rol_temp= $personaje_escena->rol; } ?>
                        <?php } ?>
                    <?php } ?>
                    <?php if($elementos_escena!=false){?>
                                            <td><label><?php echo lang('global.elementos') ?></label></td>
                    <?php } ?>
                    </tr>
                    <tr>
                        <?php if($personajes_escena){?>
                            <td>
                                <?php $rol_temp = $personajes_escena[0]->rol; 
                                foreach ($personajes_escena as $personaje_escena) { ?>
                                <?php if($personaje_escena->rol!=$rol_temp){?>
                                    </td><td>
                                <?php $rol_temp= $personaje_escena->rol; } ?>
                                <strong><?= $personaje_escena->cantidad.' '.$personaje_escena->nombre ?></strong> <!-- (<?= $personaje_escena->rol ?>)  --></br> 
                            <?php } ?>
                            </td>
                        <?php } ?>
                        <?php
                        if($elementos_escena!=false){?>
                        <td>
                        <?php foreach ($elementos_escena as $elemento_escena) { ?>
                              <strong><?= $elemento_escena->nombre_elemento ?></strong> (<?= $elemento_escena->categoria ?>) </br> 
                        <?php } ?>
                        </td>
                        <?php } ?>
                    </tr>
                </table>
            <?php } ?>
            <br>
        </td>
    </tr>
</table>