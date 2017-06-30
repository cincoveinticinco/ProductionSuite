  <?php $idioma = $this->lang->lang().'/'; ?>
      <nav id="menu_plan_diario">
      <a href="<?php echo base_url($idioma.'continuidad/menu_plan_diario/'.$id_produccion) ?>"><?php echo lang('global.plan_diario') ?></a>
      <!--<a href="escenas.html">ESCENAS</a>
      <a href="elementos.html">ELEMENTOS</a>-->
      <a href="<?php echo base_url($idioma.'continuidad/elementos/'.$id_produccion) ?>"><?php echo lang('global.elementos') ?></a>
      <?php $user=$this->model_admin->rolUserId($this->session->userdata('id_production_suite')); 
        if($produccion['0']->estado!=2){  
          if($user){
            if($user[0]['id_rol_otros']!=2 and $user[0]['id_rol_otros']!=6){ ?>
            <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
            <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
            <?php } ?>
          <?php }else{ ?>
            <a href="<?php echo base_url($idioma.'continuidad/crear_continuidad/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_video') ?></a>
            <a href="<?php echo base_url($idioma.'continuidad/crear_imagen_set/'.$id_produccion) ?>"><?php echo lang('global.subir_foto_set') ?></a>
          <?php } ?>
        <?php } ?>  
      <!--<a href="index.html">CERRAR SESIÓN</a>-->
    </nav>
      <!-- header -->
      <div class="clr"></div>
      <div class="resumen">
         <h2><?php echo lang('global.plan_diario') ?> / <?php echo $produccion['0']->nombre_produccion ?></h2>
      
        <?php echo $msg ?>
        <div class="contenido_sup">
           <div class="col" style="width:99%;">
              
              <div id="accordion">
                <?php if($plan_seman1){ ?>
                <h3><?php echo lang('global.semana_de') ?> <?php echo date("Y-M-d",strtotime($semana1_fecha1)).' Al '.date("Y-M-d",strtotime($semana1_fecha2)) ?></h3>
                <div>
                  <table width="100%" cellpadding="0" cellspacing="0" class="unidades">
                     <tr>
                     <?php $unidad=$plan_seman1['0']['id_unidad']; ?>
                     <?php $existe_plan1=0; ?>
                       <td>
                         <div class="unidad">
                          <ul>
                               <?php foreach ($plan_seman1 as $p) { ?>
                                  <?php if($p['id_plan']){ ?>
                                            <?php $existe_plan1=1; ?>
                                           <?php if($unidad==$p['id_unidad']){ $unidad=$p['id_unidad'];?>
                                            
                                            <?php if($p['estado']==5){
                                              $color='#fee93e';
                                             }elseif($p['estado']==6){
                                               $color='#fec63e'; 
                                             }else{
                                              $color='';
                                             }
                                            ?>

                                             <li style="background:<?php echo $color ?>"><?php echo anchor($idioma.'continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],' '.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                          <?php }else{ ?>
                                          <?php $unidad=$p['id_unidad']; ?>  
                                            </ul>
                                           </div>
                                          </td>
                                          <td>
                                             <div class="unidad">
                                                <ul>
                                                <?php if($p['estado']==5){
                                                  $color='#fee93e';
                                                 }elseif($p['estado']==6){
                                                   $color='#fec63e'; 
                                                 }else{
                                                  $color='';
                                                 }
                                                ?>
                                                  <li style="background:<?php echo $color ?>"><?php echo anchor($idioma.'continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],' '.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                         <?php } ?> 
                                   <?php } ?>
                               <?php } ?>  
                               <?php if($existe_plan1==0){ ?>
                               <li class="sin_plan"><?php echo lang('global.no_hay_plan_para_esta_semana') ?></li>
                               <?php } ?>
                           </ul>
                         </div>
                        </td>
                     </tr>
                  </table>
                </div>
                <?php } ?> 

                <?php if($plan_seman2){ ?>
                <h3><?php echo lang('global.semana_de') ?> <?php echo date("Y-M-d",strtotime($semana2_fecha1)).' Al '.date("Y-M-d",strtotime($semana2_fecha2)) ?></h3>
                <div>
                  <table width="100%" cellpadding="0" cellspacing="0" class="unidades">
                     <tr>
                     <?php $unidad=$plan_seman2['0']['id_unidad']; ?>
                     <?php $existe_plan2=0; ?>
                       <td>
                         <div class="unidad">
                          <ul>
                               <?php foreach ($plan_seman2 as $p) { ?>
                                  <?php if($p['id_plan']){ ?>
                                          <?php $existe_plan2=1; ?>
                                          <?php if($unidad==$p['id_unidad']){ $unidad=$p['id_unidad'];?>
                                             <li><?php echo anchor($idioma.'/continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],''.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                          <?php }else{ ?>
                                          <?php $unidad=$p['id_unidad']; ?>  
                                            </ul>
                                           </div>
                                          </td>
                                          <td>
                                             <div class="unidad">
                                                <ul>
                                                  <li><?php echo anchor($idioma.'/continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],''.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                         <?php } ?> 
                                   <?php } ?>  
                               <?php } ?>  
                               <?php if($existe_plan2==0){ ?>
                               <li class="sin_plan"><?php echo lang('global.no_hay_plan_para_esta_semana') ?></li>
                               <?php } ?>
                           </ul>
                         </div>
                        </td>
                     </tr>
                  </table>
                </div>
                <?php } ?>  

                <?php if($plan_seman3){ ?>
                <h3><?php echo lang('global.semana_de') ?> <?php echo date("Y-M-d",strtotime($semana3_fecha1)).' Al '.date("Y-M-d",strtotime($semana3_fecha2)) ?></h3>
                <div>
                  <table width="100%" cellpadding="0" cellspacing="0" class="unidades">
                     <tr>
                     <?php $unidad=$plan_seman2['0']['id_unidad']; ?>
                     <?php $existe_plan3=0; ?>
                       <td>
                         <div class="unidad">
                          <ul>
                               <?php foreach ($plan_seman3 as $p) { ?>
                                  <?php if($p['id_plan']){ ?>
                                          <?php $existe_plan3=1; ?>
                                          <?php if($unidad==$p['id_unidad']){ $unidad=$p['id_unidad'];?>
                                             <li><?php echo anchor($idioma.'/continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],''.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                          <?php }else{ ?>
                                          <?php $unidad=$p['id_unidad']; ?>  
                                            </ul>
                                           </div>
                                          </td>
                                          <td>
                                             <div class="unidad">
                                                <ul>
                                                  <li><?php echo anchor($idioma.'/continuidad/plan_diario/'.$id_produccion.'/'.$p['id_unidad'].'/'.$p['fecha_unicio_plan'],''.lang('global.unidad').$p['numero'].' / '.date("Y-M-d",strtotime($p['fecha_unicio_plan']))); ?></li>
                                         <?php } ?> 
                                   <?php } ?>  
                               <?php } ?>  
                               <?php if($existe_plan3==0){ ?>
                               <li class="sin_plan"><?php echo lang('global.no_hay_plan_para_esta_semana') ?></li>
                               <?php } ?>
                           </ul>
                         </div>
                        </td>
                     </tr>
                  </table>
                </div>
                <?php } ?> 

              </div>
        </div>
      </div>
    </div>