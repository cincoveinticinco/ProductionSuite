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
    <li class="users <?php if ($view=='user/list_user' OR $view=='user/regisro_user' OR $view=='user/editar_user'){echo "active";} ?>"><a href="<?php echo base_url(); ?>admin_user"><span class="icon"></span>Usuarios</a></li>
    <?php } ?>
    <li class="productions <?php if ($view=='produccion/agregar_usuarios' OR $view=='produccion/crear_produccion' OR $view=='produccion/index' or $view=='produccion/plan_produccion'){echo "active";} ?>"><?php echo anchor('produccion/producciones','<span class="icon"></span>Producciones'); ?></li> 
    <?php $tipo_usuario = $this->session->userdata('tipo_pruduction_suite'); ?>
    <?php if($tipo_usuario==1 OR $tipo_usuario==2 OR $tipo_usuario==4 OR  $tipo_usuario==3 OR $tipo_rol==6){ ?>
        <?php if($prod==0){?>
          <li class="dashboard"><a href="<?php echo base_url('dashboard') ?>"><span class="icon"></span>Dashboard</a></li>
        <?php }else{ ?>
            <li class="dashboard"><a href="<?php echo base_url('dashboard/produccion/'.$prod) ?>"><span class="icon"></span>Dashboard</a></li>
        <?php } ?>
    <?php } ?>
    <!--li class="continuity"><a href="#"><span class="icon"></span>Continuidad</a></li-->

    <?php if($prod!=0){?>
      <li class="post_produccion"><a href="<?php echo base_url('post_produccion/index/'.$prod) ?>"><span class="icon"></span>Post Producci&oacute;n</a></li>
    <?php } ?>
    <li class="casting"><a href="<?php echo base_url('casting/') ?>"><span class="icon"></span>Casting</a></li>

  </ul>
</nav>
<?php } ?>