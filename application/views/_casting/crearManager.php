<?php $idioma = $this->lang->lang().'/'; ?>
<!-- CASTING / Manager-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
    <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.crear_manager') ?>
</div>
<?php $this->load->view('includes/partials/top_nav_solicitudes'); ?>

<nav>
    <ul class="nav_post nav_casting">

    </ul>
</nav>

<div id="inner_content">
    <?php echo form_open_multipart($idioma.'casting/insertManager',"id='myform'");?>
    <div class="row">
        <div class="column twelve">
            <div class="column title_section">
                <h5><?php echo lang('global.formulario_crear_manager') ?></h5>
            </div>
            <div class="info with_title">
                <div class="column three">
                    <label for=""><?php echo lang('global.nombre') ?>:</label>
                    <input name="nombre" type="text" class="required" >
                    <?php echo form_error('nombre') ?>
                </div>

                <div class="column three">
                    <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                    <input name="email" type="email" class="required" >
                    <?php echo form_error('email') ?>
                </div>

                <div class="column two">
                    <label for=""><?php echo lang('global.contrasena') ?>:</label>
                    <input name="contrasena" type="password" class="required"  id="contrasena">
                    <?php echo form_error('contrasena') ?>
                </div>

                <div class="column two">
                    <label for=""><?php echo lang('global.ultima_conexion') ?> <?php echo lang('global.contrasena') ?>:</label>
                    <input name="contrasena_repetir" type="password" class="required"  id="contrasena_repetir">
                </div>

                <div class="column two">
                    <label>&nbsp;</label>
                    <input type="submit" name="Guardar" class="button twelve" value="<?php echo lang('global.crear_manager') ?>">
                </div>
                <div class="clr"></div>          
            </div>
        </div>              
    </div>

    <?php echo form_close(); ?> 

    <div class="row"> 
        <div class="column twelve normal_table">
            <table id="table_general" class="tablesorter tabla_filtro_general">
                <thead>
                    <tr>
                        <th><?php echo lang('global.nombre') ?></th>
                        <th><?php echo lang('global.correo_electronico') ?></th>
                        <th><?php echo lang('global.actores') ?></th>
                        <th><?php echo lang('global.estado') ?></th>
                        <th><?php echo lang('global.ultima_conexion') ?></th>
                        <th><?php echo lang('global.acciones') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $l) { ?>
                    <tr>
                        <td><?php echo $l['nombre']; ?></td>
                        <td><?php echo $l['email']; ?>	</td>
                        <td>Vacio</td>
                        <td><?php if ($l['tipo_usuario']=='1'){ ?>
                        Activo
                        <?php }else{ ?>
                        Inactivo
                        <?php } ?></td>
                        <td><?php echo $l['fecha_conexion']; ?></td>
                        <td>
                            <?php if ($l['tipo_usuario']=='1') { ?> <a class="button" href="<?php echo base_url($idioma.'casting/updateEstado/'.$l['id'].'/0') ?>" onclick='return confirmar("¿Está seguro que desea Desactivar el Usuario")'><?php echo lang('global.desactivar') ?></a>
                            <?php }else{ ?> <a class="button" href="<?php echo base_url($idioma.'casting/updateEstado/'.$l['id'].'/1') ?>" onclick='return confirmar("¿Está seguro que desea Activar el Usuario")'><?php echo lang('global.activar') ?></a> 
                            <?php } ?>
                            <a class="button" href="<?php echo base_url($idioma.'casting/editarManager/'.$l['id']) ?>"><?php echo lang('global.editar') ?></a> 
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>


    <script>
        $(document).ready(function() {	

            $( "#myform" ).validate({
                rules: {
                    contrasena: "required",
                    contrasena_repetir: {
                        required: true,
                        equalTo: "#contrasena"
                    }
                }
            });

        });
    </script>}
    <script>function confirmar ( mensaje ) {return confirm( mensaje );} </script>


