<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.home') ?></a>  / <a href="#"><?php echo $produccion['0']->nombre_produccion ?></a> / <?php echo lang('global.asignar_usuarios') ?>
  <div class="productionName">
    <?php echo $produccion['0']->nombre_produccion ?>
  </div>
</div>
<div id="inner_content">
  <div id="confirm" >
    <div class="row">
      <div class="columns twelve">
        <label><?php echo lang('global.desea_agregar_usuarios') ?></label>
        <a href="#" class="button full" id="botton_yes"><?php echo lang('global.si') ?></a>
        <a href="<?=base_url().$idioma?>produccion/producciones" class="button full"><?php echo lang('global.no') ?></a>
      </div>
    </div>
  </div>
  <div id="user_box" style="display:none;">
    <div class="row">
      <div class="columns four">
          <label><?php echo lang('global.seleccione_tipo_usuario') ?></label>
          <select name="roles" id="tipo_user">
            <?php foreach ($roles as $r) { ?>
              <option value="<?php echo $r['id']?>"><?php echo $r['descripcion'] ?></option>
            <?php } ?>
          </select>
      </div>
    </div>
    <br>
    <div class="row ">
      <div class="columns six">
        <div id="sortable_content">
          <div class="scroller">
              <div class="normal_table">
                <table class="users_table">
                  <thead>
                    <tr>
                      <td class="user"><?php echo lang('global.usuario') ?></td>
                      <td class="role"><?php echo lang('global.rol') ?></td>
                      <td class="isactive"><?php echo lang('global.activo') ?></td>
                      <td class="actions"><?php echo lang('global.acciones') ?></td>
                    </tr>
                  </thead>
                  <tbody  id="table_1" class="connectedSortable">
    
                    <input type="hidden" name="id_produccion" id="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                    <?php if($user_has_produccion) { ?>
                        <?php foreach ($user_has_produccion as $u) { ?>
    
                          <tr class="sort_left">
                            <input type="hidden" name="id_user" id="id_user"value="<?php echo $u['id_user'] ?>">
                            <input type="hidden" name="rol" id="rol" value="<?php echo   $u['id_rol'] ?>">
                            <td><?php echo $u['nombre'].' '.$u['apellido'] ?></td>
                            <td><?php echo $u['descripcion']?></td>
                            <td class="role_hiden" style="text-align:center;"><input type="checkbox" id="domingo"> </td>
                            <td class="actions_hiden" style="text-align: center;"> <a class="delete" ><?php echo lang('global.eliminar') ?></a> <a class="add" ><?php echo lang('global.agregar') ?></a> </td>
                          </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr class="hidden_row"><td colspan="4"><?php echo lang('global.usuario_rol') ?></td></tr>    
                    <?php } ?>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
      <div class="columns six">
        <div id="sortable_content">
          <div class="scroller">
            <div class="normal_table">
              <table class="users_table">
                <thead>
                  <tr>
                    <td class="user"><?php echo lang('global.usuario') ?></td>
                      <td class="role"><?php echo lang('global.rol') ?></td>
                      <td class="isactive"><?php echo lang('global.activo') ?></td>
                      <td class="actions"><?php echo lang('global.acciones') ?></td>
                  </tr>
                </thead>
                <tbody  id="table_2" class="connectedSortable">
                  <input type="hidden" name="id_produccion" id="id_produccion" value="<?php echo $produccion['0']->id_produccion ?>">
                  <?php if($usuarios_producion) { ?>
                      <?php foreach ($usuarios_producion as $u) { ?>
                        <tr class="sort_right">
                          <input type="hidden" name="id_user" id="id_user"value="<?php echo $u['id_user'] ?>">
                          <input type="hidden" name="rol" id="rol" value="<?php echo   $u['id_rol'] ?>">
                          <td><?php echo $u['nombre'].' '.$u['apellido'] ?></td>
                          <td><?php echo $u['descripcion']?></td>
                          <td class="role_hiden" style="text-align:center;"><input type="checkbox" id="domingo"> </td>
                          <td class="actions_hiden" style="text-align: center;"> <a class="delete" ><?php echo lang('global.eliminar') ?></a> <a class="add" ><?php echo lang('global.agregar') ?></a></tsd>
                        </tr>
                      <?php } ?>
                  <?php } else { ?>
                      <tr class="hidden_row"><td colspan="4"><?php echo lang('global.usuario_rol') ?></td></tr>    
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns twelve">
        <br>
        <a href="<?=base_url().$idioma?>produccion/producciones" class="button full"><?php echo lang('global.aceptar') ?></a>
      </div>
    </div>
  </div>
</div>
