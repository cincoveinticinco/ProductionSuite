<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/********
template el cual me cargara el header la vista que le envio desde el controlador
y el footer
*********/
?>

<?php $prod = 0; ?>
  <?php if($view!='login' AND $view!='disconnectUser' AND $view!='produccion/crear_produccion' AND $view!='user/list_user' AND 
            $view!='user/editar_user' AND $view!='user/registro_user' AND 
            $view!='carga_archivo' AND $view!='casting/anadir_actor'  AND 
            $view!='casting/index' AND $view!='casting/detalle_actor'  AND 
            $view!='casting/crear_solicitud' AND $view!='casting/detalle_solicitud' AND
            $view!='casting/editar_solicitud' AND $view!='casting/personajes' AND 
            $view!='casting/solicitudes' AND $view!='casting/anadir_contrato' AND 
            $view!='casting/editar_actor' AND $view!='casting/editar_otro_si'AND $view!='casting/ver_contrato' AND  $view!='casting/crearManager' AND $view!='casting/editarManager' AND
            $view!='casting/crear_otro_si' AND $view!='casting/nomina_personajes' and $view!='casting/solicitudes_actores'){ ?>
    
    <?php if ($view=='produccion/index') {
      $prod = 0;
    } else { 
      $prod = $produccion[0]->id_produccion;
    } ?>
  <?php } ?>
  <?php $data['prod']=$prod; ?>
  <?php 
  $data['view']=$view;
  $this->load->view('includes/header',$data); ?>
  <section id="main_container">
  <?php if($view!='login' AND $view!='disconnectUser'){?>
  <?php $this->load->view('includes/diccionario',$data); ?>
  <?php $this->load->view('includes/sidebar',$data);  }?>
  <div id="content" <?php if($view=='login'){ ?>class="login_page"<?php } ?>>
    <?php $this->load->view($view); ?>
  </div>
</section>

<?php $this->load->view('includes/footer'); ?>