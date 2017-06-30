  <?php $idioma = $this->lang->lang().'/'; ?>
  <?php $id_user=$this->session->userdata('id_pruduction_suite');
      $tipo_user=$this->session->userdata('tipo_pruduction_suite');


//if($tipo_user=='3' or $tipo_user=='1' OR $tipo_user=='2'){

if($tipo_user=='3' or $tipo_user=='1' OR $tipo_user=='2' OR $tipo_user=='7' OR $tipo_user=='8'  OR $tipo_user=='10'){

  $produccion=$this->model_produccion->producciones_all();
}else{
  $produccion=$this->model_produccion->producciones_user($id_user);
}
?>
<aside id="sidebar">
<span id="localstore" contenteditable="true"></span>
  <span id="close_icon" class="cooki_menu"><span></span></span>
  <nav id="side_menu">
  <?php $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
        $id_user=$this->session->userdata('id_pruduction_suite'); 
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
  ?>
    <ul>
      <?php if($tipo_user=='4' OR $tipo_user=='1') {?>
      <li class="create_prod"><?php echo anchor($idioma.'produccion/index',lang('global.crear_produccion')); ?></li>
      <?php } ?>
      <?php if($produccion) { ?>

        <?php foreach ($produccion as $p) { ?>
        <?php if ($p['id_produccion']==$prod and $p['estado']==1){ ?>
          <li class="prod<?php echo $p['id_produccion']; ?> <?php if ($p['id_produccion']==$prod): ?><?php echo 'active' ?><?php endif ?>"><a class="has-tip tip-right tooltip_info" title="<?php echo $p['nombre_produccion']?>" href="#"><?php echo $p['nombre_produccion'] ?></a>
          <ul class="submenu" <?php if ($p['id_produccion']==$prod): ?><?php echo 'style="display:block;"' ?><?php endif ?>>
            <li class="imgProd">
              <?php if($p['imagen_produccion']!=null){  ?> 
                  <img src="<?php echo base_url($p['imagen_produccion']) ?>" alt="">
              <?php } else { ?>
                 <img src="<?php echo base_url() ?>images/produccion/production_suite.jpg" alt="">
              <?php } ?>
            </li>
            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='8'){
                  if($tipo_rol==2 OR $tipo_usuario=='1' OR $tipo_rol==6 OR $tipo_rol==8 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                    <li><?php echo anchor($idioma.'plan_produccion/index/'.$p['id_produccion'],lang('global.plan_produccion').' '.$tipo_rol) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
              if($tipo_rol==2 OR $tipo_rol==1 OR $tipo_rol==6 OR  $tipo_rol==15 OR $tipo_rol==7 OR $tipo_rol==8 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                <li><?php echo anchor($idioma.'plan_general/index/'.$p['id_produccion'],lang('global.plan_general')) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='2' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==2 OR $tipo_rol==1 OR $tipo_rol==6 OR  $tipo_rol==15 OR  $tipo_rol==17 OR $tipo_rol==7 OR $tipo_rol==8 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){   ?>
              <li><?php echo anchor($idioma.'plan_diario/index/'.$p['id_produccion'],lang('global.plan_diario')) ?></li>
             <?php }} ?> 
            <?php if($tipo_rol==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
              if($tipo_rol==1 or $tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==15 OR $tipo_rol==6 OR  $tipo_rol==15  OR  $tipo_rol==8  OR $tipo_rol==11  OR $tipo_usuario=='3' OR $tipo_usuario=='4' ){ ?>
               <li><?php echo anchor($idioma.'libretos/index/'.$p['id_produccion'],lang('global.libretos')) ?></li>
            <?php }} ?>

            <?php if($tipo_rol==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==1 or $tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==6  OR $tipo_rol==6 OR $tipo_rol==15 OR $tipo_rol==8  OR $tipo_rol==11 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                <li><?php echo anchor($idioma.'escenas/index/'.$p['id_produccion'],lang('global.escenas')) ?></li>
            <?php }} ?>

             <?php if($tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='5' ){
                if($tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==8  OR $tipo_usuario=='4'){ ?>
                <?php if($p['estado']!=2){ ?>
                <li><?php echo anchor($idioma.'escenas/crear_escenas/'.$p['id_produccion'],lang('global.crear_escenas')) ?></li>
                <?php } ?>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                  if($tipo_rol==2 OR $tipo_usuario=='1' OR  $tipo_rol==15 OR  $tipo_rol==17 OR  $tipo_rol==6 OR $tipo_rol==8 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?> 
            <li><?php echo anchor($idioma.'elementos/index/'.$p['id_produccion'],lang('global.elementos')) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='3' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==2 OR $tipo_rol==8 OR $tipo_rol==6 OR $tipo_rol==15 OR $tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='3'){ ?>
            <li><?php echo anchor($idioma.'herramientas/index/'.$p['id_produccion'],lang('global.herramientas')) ?></li>

            <?php } ?>
            <?php } ?> 
          </ul>
        </li>
       <?php }} ?>
        <?php foreach ($produccion as $p) { ?>
        <?php if ($p['id_produccion']!=$prod and $p['estado']==1) {?>
          <li class="prod<?php echo $p['id_produccion']; ?>"><a class="has-tip tip-right tooltip_info" title="<?php echo $p['nombre_produccion']?>" href="#"><?php echo $p['nombre_produccion'] ?></a>
          <ul class="submenu" <?php if ($p['id_produccion']==$prod): ?><?php echo 'style="display:block;"' ?><?php endif ?>>
            <li class="imgProd">
              <?php if($p['imagen_produccion']!=null){  ?> 
                  <img src="<?php echo base_url($p['imagen_produccion']) ?>" alt="">
              <?php } else { ?>
                 <img src="<?php echo base_url() ?>images/produccion/production_suite.jpg" alt="">
              <?php } ?>
            </li>
            
            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='8'){
                  if($tipo_rol==2 OR $tipo_usuario=='1' OR $tipo_rol==6 OR $tipo_rol==8 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                    <li><?php echo anchor($idioma.'plan_produccion/index/'.$p['id_produccion'],lang('global.plan_produccion').' '.$tipo_rol) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
              if($tipo_rol==2 OR $tipo_rol==1 OR $tipo_rol==6 OR  $tipo_rol==15 OR $tipo_rol==7 OR $tipo_rol==8 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                <li><?php echo anchor($idioma.'plan_general/index/'.$p['id_produccion'],lang('global.plan_general')) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='2' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==2 OR $tipo_rol==1 OR $tipo_rol==6 OR  $tipo_rol==15 OR  $tipo_rol==17 OR $tipo_rol==7 OR $tipo_rol==8 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4'){   ?>
              <li><?php echo anchor($idioma.'plan_diario/index/'.$p['id_produccion'],lang('global.plan_diario')) ?></li>
             <?php }} ?> 
            <?php if($tipo_rol==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
              if($tipo_rol==1 or $tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==15 OR $tipo_rol==6 OR  $tipo_rol==15  OR  $tipo_rol==8  OR $tipo_rol==11  OR $tipo_usuario=='3' OR $tipo_usuario=='4' ){ ?>
               <li><?php echo anchor($idioma.'libretos/index/'.$p['id_produccion'],lang('global.libretos')) ?></li>
            <?php }} ?>

            <?php if($tipo_rol==1 or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==1 or $tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==6  OR $tipo_rol==6 OR $tipo_rol==15 OR $tipo_rol==8  OR $tipo_rol==11 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?>
                <li><?php echo anchor($idioma.'escenas/index/'.$p['id_produccion'],lang('global.escenas')) ?></li>
            <?php }} ?>

             <?php if($tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='5' ){
                if($tipo_rol==2 or $tipo_usuario=='1' OR $tipo_rol==8  OR $tipo_usuario=='4'){ ?>
                <?php if($p['estado']!=2){ ?>
                <li><?php echo anchor($idioma.'escenas/crear_escenas/'.$p['id_produccion'],lang('global.crear_escenas')) ?></li>
                <?php } ?>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                  if($tipo_rol==2 OR $tipo_usuario=='1' OR  $tipo_rol==15 OR  $tipo_rol==17 OR  $tipo_rol==6 OR $tipo_rol==8 OR $tipo_usuario=='3' OR $tipo_usuario=='4'){ ?> 
            <li><?php echo anchor($idioma.'elementos/index/'.$p['id_produccion'],lang('global.elementos')) ?></li>
            <?php }} ?>

            <?php if($tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='3' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                if($tipo_rol==2 OR $tipo_rol==8 OR $tipo_rol==6 OR $tipo_rol==15 OR $tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='3'){ ?>
            <li><?php echo anchor($idioma.'herramientas/index/'.$p['id_produccion'],lang('global.herramientas')) ?></li>

            <?php } ?>
            <?php } ?>  
          </ul>
        </li>
       <?php } } ?> 
      <?php } ?>
      <!-- BOTONERA CASTING -->
    <?php if($tipo_usuario=='2' or $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_rol=='6' OR $tipo_usuario=='8' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='7' OR $tipo_usuario=='10' OR $tipo_rol=='8' OR $tipo_rol=='9' OR $tipo_rol=='10' OR $tipo_rol=='11'  OR $tipo_rol=='12' OR $tipo_rol=='15' OR $tipo_rol=='17' OR $tipo_rol=='18'){ ?>
      <li class="casting_sidebutton">
        <a href="#">Casting</a>
        <ul class="submenu">
          <?php if($tipo_usuario=='1' or $tipo_usuario=='2' or $tipo_usuario=='3' OR $tipo_usuario=='8' OR $tipo_usuario=='9' OR $tipo_usuario=='10' OR $tipo_usuario=='7' or $tipo_usuario=='4' OR $tipo_rol=='7' OR $tipo_rol=='15' ){ ?>
          <li><?php echo anchor($idioma.'casting/index',lang('global.actores')); ?></li>
          <?php } ?>
          <?php if($tipo_usuario=='1' or $tipo_usuario=='2' or $tipo_usuario=='3' OR $tipo_usuario=='8' OR $tipo_usuario=='9' OR $tipo_usuario=='10' OR $tipo_usuario=='7' or $tipo_usuario=='4' OR $tipo_rol=='15' OR $tipo_rol=='17' OR $tipo_rol=='18'){ ?>
          <li><?php echo anchor($idioma.'casting/solicitudes',lang('global.solicitudes')); ?></li>
          <?php } ?>
          <?php if($tipo_usuario=='1' or $tipo_usuario=='2' or $tipo_usuario=='3' OR $tipo_usuario=='8' OR $tipo_usuario=='9' OR $tipo_usuario=='10' OR $tipo_usuario=='7' or $tipo_usuario=='4' OR $tipo_rol=='15' OR $tipo_rol=='17' OR $tipo_rol=='18'){ ?>
          <li><?php echo anchor($idioma.'casting/personajes',lang('global.personajes')); ?></li>
          <?php } ?>
          <?php if($tipo_usuario=='1' or $tipo_usuario=='2' or $tipo_usuario=='3' or $tipo_usuario=='4' or $tipo_usuario=='8' or $tipo_usuario=='10'){ ?>
          <li><?php echo anchor($idioma.'casting/nomina_personajes',lang('global.nomina')); ?></li>
          <?php } ?>
          <?php if($tipo_usuario=='1' or $tipo_usuario=='9' OR $tipo_rol=='15'){ ?>
          <li><?php echo anchor($idioma.'casting/crearManager',lang('global.crear_manager')); ?></li>
          <?php } ?>
          <?php if($tipo_usuario=='1' or $tipo_usuario=='9'){ ?>
          <li><?php echo anchor($idioma.'casting/solicitudes_actores',lang('global.ver_solicitudes_actores')); ?></li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?> 
      <!-- FIN BOTONERA CASTING -->
     </ul>
  </nav>
  <div id="open_sidebar" class="cooki_menu"><span class="icon">Close Sidebar</span></div>
</aside>