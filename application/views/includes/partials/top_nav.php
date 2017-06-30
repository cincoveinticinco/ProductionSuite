<?php $idioma = $this->lang->lang().'/'; ?>
<?php if($view!='login' and $view!="disconnectUser"){ ?>
<nav id="top_menu_label">
  <ul>
    <?php $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
          if($this->session->userdata('id_pruduction_suite')){
          $user=$this->model_admin->rolUserId($this->session->userdata('id_pruduction_suite'));
          $tipo_rol=$user['0']['id_rol_otros'];
          }
    ?>
    <?php if($tipo_usuario==1 OR $tipo_rol==5){ ?>
    <li class="users <?php if ($view=='user/list_user' OR $view=='user/regisro_user' OR $view=='user/editar_user'){echo "active";} ?>"><a href="<?php echo base_url().$idioma; ?>admin_user"><span class="icon"></span><?php echo lang('global.usuarios') ?></a></li>
    <?php } ?>
    <li class="productions <?php if ($view=='produccion/agregar_usuarios' OR $view=='produccion/crear_produccion' OR $view=='produccion/index' or $view=='produccion/plan_produccion'){echo "active";} ?>"><?php echo anchor($idioma.'produccion/producciones','<span class="icon"></span>'.lang('global.producciones')); ?></li> 
    <?php $tipo_usuario = $this->session->userdata('tipo_pruduction_suite'); ?>
    <?php $id_user = $this->session->userdata('id_pruduction_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
        
        $continuar=0;
        if($user){
          foreach ($user as $u) {
            if($u['id_rol_otros']== 8 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 2){
              $continuar=1;
              break;
            }
          }
        } ?>
    <?php if($tipo_usuario==1 OR $tipo_usuario==2 OR $tipo_usuario==4 OR  $tipo_usuario==3 OR $tipo_usuario==10 OR $tipo_rol==6 or $continuar==1){ ?>
        <?php if($prod==0){?>
          <li class="dashboard"><a href="<?php echo base_url($idioma.'dashboard') ?>"><span class="icon"></span>Dashboard</a></li>
        <?php }else{ ?>
            <li class="dashboard"><a href="<?php echo base_url($idioma.'dashboard/produccion/'.$prod) ?>"><span class="icon"></span>Dashboard</a></li>
        <?php } ?>
    <?php } ?>
    <!--li class="continuity"><a href="#"><span class="icon"></span>Continuidad</a></li-->
   <?php if($tipo_rol==2 or  $tipo_usuario=='2' or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_rol=='6' OR $tipo_usuario=='4' OR $tipo_rol=='8' OR $tipo_rol=='9' OR $tipo_rol=='10' OR $tipo_rol=='11'  OR $tipo_rol=='12' OR $tipo_rol=='13' OR $tipo_rol=='14'){ ?>
        <?php if($prod!=0){?>
          <li class="post_produccion"><a href="<?php echo base_url($idioma.'post_produccion/index/'.$prod) ?>"><span class="icon"></span><?php echo lang('global.post_produccion') ?></a></li>
        <?php } ?>
    <?php } ?>

    <?php if($tipo_usuario=='2' or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='8' OR $tipo_rol=='6' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='7' OR $tipo_usuario=='10' OR $tipo_rol=='8' OR $tipo_rol=='9' OR $tipo_rol=='10' OR $tipo_rol=='11'  OR $tipo_rol=='12' OR $tipo_rol=='15' OR $tipo_rol=='17' OR $tipo_rol=='18'){ ?>
      <li class="casting"><a href="<?php echo base_url($idioma.'casting/') ?>"><span class="icon"></span>Casting</a></li>
    <?php } ?>  

  </ul>
</nav>
<?php } ?>