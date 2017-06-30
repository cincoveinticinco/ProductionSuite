<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.crear_actor') ?>
</div>



<nav>
    <ul class="nav_post nav_casting">
        <li><a href="<?=base_url($idioma.'casting')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.buscar_actor') ?></a></li>
        <li><a href="#" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_actor') ?></a></li>
    </ul>
</nav>

<div id="inner_content">
  <?php echo form_open_multipart($idioma.'casting/insert_actor','id="crear_actor", class="", onSubmit=""');?>
        <div class="row">
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.datos_actor') ?></h5></div>
            <div class="info with_title">
              <div class="column two">
                <label for=""><?php echo lang('global.nombre') ?>:</label>
                <input name="nombre_actor" type="text" class="required" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.apellido') ?>:</label>
                <input name="apellido_actor" type="text" class="required" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.nacionalidad') ?>:</label>
                <?php if($nacionalidades){ ?>
                <select name="nacionalidad" id="nacionalidad" class="s4a required">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($nacionalidades as $nacionalidad) { ?>
                    <option value="<?=$nacionalidad->id?>"><?=$nacionalidad->descripcion?></option>
                   <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.tipo_de_documento') ?>:</label>
                <?php if($tipos_documento){ ?>
                <select name="tipo_documento" id="tipo_documento" class="">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($tipos_documento as $tipo_documento) { ?>
                    <option value="<?=$tipo_documento->id?>"><?=$tipo_documento->descripcion?></option>
                  <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.numero_documento') ?>:</label>
                <input name="documento" id="documento_actor" type="text" class="required" >
              </div>
              <div class="clr6"></div>

              <div class="column two">
                <label for=""><?php echo lang('global.fecha_nacimiento') ?>:</label>
                <input type="text" name="fecha_nacimiento" class="required" id="birthday_actor">
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.genero') ?>:</label>
                <?php if($generos){ ?>
                <select name="genero" class="required">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($generos as $genero) { ?>
                    <option value="<?=$genero->id?>"><?=$genero->descripcion?></option>
                  <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <div class="column six">
                <label for=""><?php echo lang('global.altura_metros') ?>:</label>
                <input type="text" name="altura" id="altura" style="width:97%;float:left" class="required">
                </div>
                <div class="column six">
                <label for=""><?php echo lang('global.peso_kg') ?>:</label>
                <input type="text" name="peso" id="peso" style="width:97%;float:right" class="required">
                </div>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.color_tez') ?>:</label>
                <?php if($colores_tez){  ?>
                <select name="color_tez" class="required">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($colores_tez as $color_tez) {  ?>
                    <option value="<?=$color_tez->id?>"><?=$color_tez->descripcion?></option>
                  <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.color_ojos') ?>:</label>
                <?php if($colores_ojos){  ?>
                <select name="color_ojos" class="required">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($colores_ojos as $color_ojos) { ?>
                    <option value="<?=$color_ojos->id?>"><?=$color_ojos->descripcion?></option>
                  <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="clr6"></div>
              

              <div class="representante_legal" style="display:none">
                    <div class="column two">
                      <label for=""><?php echo lang('global.nombre_representante_legar') ?>:</label>
                      <input type="text" name="nombre_representante_legal" class="inf_repre" id="">
                    </div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.tipo_de_documeto_representante_legal') ?>:</label>
                      <?php if($tipos_documento){ ?>
                      <select name="id_tipo_documento_representante_legal" id="id_tipo_documento_representante_legal" class="inf_repre">
                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($tipos_documento as $tipo_documento) { ?>
                           <?php if($tipo_documento->id!=2 and $tipo_documento->id!=4){ ?>
                            <option value="<?=$tipo_documento->id?>"><?=$tipo_documento->descripcion?></option>
                            <?php } ?>
                          <?php } ?>
                      </select>
                      <?php } ?>
                    </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.numero_documento_representante_legal') ?>:</label>
                    <input name="documento_representante_legal" id="documento_representante_legal" type="text" class="inf_repre" >
                  </div>
                   
                   <div class="column two">
                      <label for=""><?php echo lang('global.telefono_fijo_representante_legal') ?>:</label>
                      <input type="text" name="telefono_fijo_representante" class="inf_repre" id="">
                    </div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.celular_representante_legal') ?>:</label>
                      <input type="text" name="celular_representante" class="inf_repre" id="">
                    </div>
                    
                    <div class="clr6"></div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.direccion_representante_legal') ?>:</label>
                      <input type="text" name="direccion_representante" class="inf_repre" id="">
                    </div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.correo_representante_legal') ?>:</label>
                      <input type="text" name="correo_representante" class="inf_repre" id="">
                    </div>
                    
                    <div class="clr6"></div>
              </div>
              
            </div>
          </div>
        </div>

        

        <div class="row">
          <div class="column twelve">
            <div class="info">
              <label for=""><?php echo lang('global.idioma') ?>:</label>
              <?php if($idiomas){ ?>
                <?php foreach ($idiomas as $idioma) {?>
                  <div class="column one" style="margin-top:10px">
                    <label class="label_check"><input value="<?=$idioma->id?>" name="idiomas[]" <?php if(strtolower($idioma->descripcion)=="otro" || strtolower($idioma->descripcion)=="otra"){ ?> class="languaje" <?php } ?> type="checkbox"> <?=$idioma->descripcion?></label>
                  </div>
                <?php } ?>
                <div class="column two right">
                  <input type="text" name="otro_idioma" id="other_languaje" style="margin-top: 4px;" placeholder="">
                </div>
              <?php } ?>
              <div class="clr"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="column twelve">
            <div class="info">
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                <input name="telefono_fijo" class="telefono_fijo" type="text" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                <input name="telefono_movil" class="telefono_movil" type="text"  >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.direccion') ?>:</label>
                <input name="direccion" type="text" class="direccion" >
              </div>

              <div class="location_section">
                <div class="column two">
                  <label for=""><?php echo lang('global.pais') ?>:</label>
                  <?php if($paises){ ?>
                  <select name="pais"  id="pais" class="pais_selector pais_residencia s4a ">
                    <!--option value=""><?php echo lang('global.seleccion_opcion') ?></option-->
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <option value="48">Colombia</option>
                    <?php foreach ($paises as $pais) { ?>
                    <?php if ($pais->id!=48): ?>
                      <option value="<?=$pais->id?>"><?=$pais->nombre?></option>
                    <?php endif ?>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <input type="hidden" name="id_ciudad_sociedad_hidden" id="id_ciudad_sociedad_hidden">
                  <label for=""><?php echo lang('global.ciudad') ?>:</label>
                  <select name="ciudad" id="ciudad" class="ciudad_selector ciudad_residencia s4a ">
                  <option value="null"><?php echo lang('global.seleccion_opcion') ?></option>
                  </select>
                </div>
                <div class="clr6"></div>
              </div>
              
              <div class="column two">
                <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                <input name="email" type="email" > 
              </div>
                <div class="column two">
                <label for=""><?php echo lang('global.contactar') ?>:</label>
                <select name="contactar" id="contactar">
                  <option value="1" disabled="true" class="disabled_item"><?php echo lang('global.solo_manager') ?></option>
                  <option value="2" selected="selected"><?php echo lang('global.solo_actor') ?></option>
                  <option value="3"  disabled="true" class="disabled_item"><?php echo lang('global.ambos_manager_actor') ?></option>
                </select>
              </div>
              <div class="clr6"></div>
              <div class="column two">
                <label for=""><?php echo lang('global.tiene_manager') ?>:</label>
                <div class="columns">
                  <label for="" class="label_check"><input name="manager_validation" class="manager_validation" type="radio" value="1"> <?php echo lang('global.si') ?></label>
                </div>
                <div class="columns alignLeft">
                  <label for="" class="label_check"><input name="manager_validation" class="manager_validation" type="radio" value="" checked="checked"> <?php echo lang('global.no') ?></label>
                </div>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.existe_sociedad') ?>:</label>
                <div class="columns">
                  <label for="" class="label_check"><input name="society_validation" class="society_validation society_validation_si" type="radio" value="1"> <?php echo lang('global.si') ?></label>
                </div>
                <div class="columns alignLeft">
                  <label for="" class="label_check"><input name="society_validation" class="society_validation society_validation_no" type="radio" value="2" checked="checked"> <?php echo lang('global.no') ?></label>
                </div>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.disponible') ?>?:</label>
                <div class="columns">
                  <label class="label_check"><input type="radio" name="disponible" value="1" class="disponible" checked="checked"><?php echo lang('global.si') ?></label>
                </div>
                <div class="columns alignLeft">
                  <label class="label_check"><input type="radio" name="disponible" value="2" class="disponible"><?php echo lang('global.no') ?></label>
                </div>

              </div>

              <div class="column two" id="terminacion_proyecto_section" style="display:none;">
                <label for=""><?php echo lang('global.terminacion_proyecto') ?>:</label>
                <input type="text" name="terminacion_proyecto" id="terminacion_proyecto" class="datepicker required">
              </div>
              <div class="clr"></div>
            </div>
          </div>
        </div>
        
        <!-- SECCION MANAGER -->
        <div class="row" id="manager_section">
          <div class="clr6"></div>
          <div class="column twelve">
            <div class="column title_section"><h5>MANAGER</h5></div>
            <div class="info with_title">
              <?php $estilo =""; ?>  
              <div id="agregar_manager">
                <div class="column two">
                  <label for="">Manager:</label>
                  <?php if ($managers): ?>
                    <?php $estilo ="readonly"; ?>
                    <select name="id_manager" id="id_manager" class="required">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($managers as $manager): ?>
                        <option value="<?=$manager->id?>"><?=$manager->nombre?></option>
                      <?php endforeach ?>
                        <option value="0"><?php echo lang('global.otros') ?></option>
                    </select>
                  <?php endif ?>
                  <input type="text" value="" name="nombre_manager" id="nombre_manager" style="display:none">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                  <input type="text" value="" name="telefono_fijo_manager" id="telefono_fijo_manager" class="required">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                  <input type="text" value="" name="telefono_movil_manager" id="telefono_movil_manager" class="required">
                </div>
                <div class="column two e_mail">
                  <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                  <input type="text" value="" name="email_manager" id="email_manager" class="required">
                </div>
                <div class="column two cancelar_manager" style="display:none;">
                    <label for="">&nbsp;</label>
                    <a href="#" class="button cancel" id="cancelar_manager" tabindex="-1"><?php echo lang('global.cancelar') ?></a>
                </div>
              </div>
              <div class="clr"></div>
            </div>
          </div>
        </div>
        <!-- FIN SECCION MANAGER -->


        <!-- SECCION CONTACTO CONTRACTUAL -->
        <div class="row" id="contacto_section">
          <div class="clr6"></div>
          <div class="column twelve">
             <div class="column title_section"><h5><?php echo lang('global.contacto_contractual') ?></h5></div>
            <div class="info with_title">
              <div class="column two">
                <label for=""><?php echo lang('global.contacto_contractual') ?>:</label>
                <input type="text" value="" name="contacto_nombre">
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                <input type="text" name="contacto_telefono" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                <input type="text" name="contacto_telefono_movil"  >
              </div>
              <div class="column four e_mail">
                <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                <input type="text" name="contacto_email" >
              </div>
              <div class="clr"></div>
            </div>
          </div>
        </div>
        <!-- FIN SECCION CONTACTO CONTRACTUAL -->
       

        <!-- SECCION SOCIEDAD -->
        <div class="row" id="society_section">
          <div class="clr6"></div>
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.datos_sociedad') ?></h5></div>
            <div class="info with_title">
              <div class="column two">
                <label for=""><?php echo lang('global.razon_social') ?>:</label>
                <?php if ($sociedades): ?>
                  <select name="razon_social" id="razon_social">
                    <option><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($sociedades as $sociedad): ?>
                      <option value="<?=$sociedad->id?>"><?=$sociedad->nombre?></option>
                    <?php endforeach ?>
                    <option value="0"><?php echo lang('global.otros') ?></option>
                  </select>
                <?php endif ?>
                <input type="text" id="nombre_sociedad" name="nombre_sociedad">
              </div>

             

              <div class="column two">
                <label for="">NIT:</label>
                <input type="text" name="nit_sociedad" id="nit_sociedad">
              </div>
              
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                <input type="text" name="telefono_fijo_sociedad" id="telefono_fijo_sociedad" class="required">
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                <input type="text" name="telefono_movil_sociedad" id="telefono_movil_sociedad" class="required">
              </div>
             

             
               <div class="column two">
                <label for=""><?php echo lang('global.direccion') ?>:</label>
                <input type="text" name="direccion_sociedad" id="direccion_sociedad" class="required">
              </div>
               <div class="clr6"></div>
              <div id="location_society">
                <div class="column two">
                  <label for=""><?php echo lang('global.pais') ?>:</label>
                    <?php if($paises){ ?>
                    <select name="pais_sociedad"  id="pais_sociedad" class="pais_selector s4a required">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($paises as $pais) { ?>
                        <option value="<?=$pais->id?>" id="sociedad_pais_<?=$pais->id?>"><?=$pais->nombre?></option>
                      <?php } ?>
                    </select>
                    <?php } ?>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.ciudad') ?>:</label>
                  <select name="ciudad_sociedad" id="ciudad_sociedad" class="ciudad_selector s4a">
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                  <input type="text" name="email_sociedad" id="email_sociedad" class="required">
                </div>
               
                <div class="column two">
                  <label for=""><?php echo lang('global.representante_legal') ?>:</label>
                  <input type="text" name="representante_legal" id="representante_legal" class="required">
                </div>
    
                <div class="column two">
                  <label for=""><?php echo lang('global.documento_representante') ?>:</label>
                  <input type="text" name="documento_representante" id="documento_representante" class="required">
                </div>
               <div class="clr6"></div>
                <div class="column two cancelar_sociedad">
                    <label for="">&nbsp;</label>
                    <a href="#" class="button cancel" id="cancelar_sociedad" tabindex="-1"><?php echo lang('global.cancelar') ?></a>
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- SECCION SOCIEDAD -->


        <div class="row">
          <div class="column twelve">
            <div class="clr6"></div>
            <div id="base_production" style="display:none;">
              <div class="column two">
                <label for=""><?php echo lang('global.produccion') ?>:</label>
                <input name="produccion_actor[]" type="text" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.rol') ?>:</label>
                <?php if($roles){?>
                <select name="rol_actor[]" id="">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($roles as $rol) { ?>
                      <?php if ($rol['id']!=4): ?>
                        <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                      <?php endif ?>
                    <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.personajes') ?>:</label>
                <input name="personaje_actor[]" type="text" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.ano') ?>:</label>
                <select name="ano_actor[]">
                  <option value=""><?php echo lang('global.seleccione_un_ano') ?></option>
                  <?php for ($i=1900; $i <= date('Y'); $i++) { ?>
                    <option value="<?=$i?>"><?=$i?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="clr6"></div>
            </div>
            
            <div class="column title_section"><h5><?php echo lang('global.histotial_artisitico') ?></h5></div>
            <div class="info with_title" id="old_producitions">
              <div class="column two">
                <label for=""><?php echo lang('global.produccion') ?>:</label>
                <input name="produccion_actor[]" type="text" >
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.rol') ?>:</label>
                <?php if($roles){?>
                <select name="rol_actor[]" id="">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($roles as $rol) { ?>
                      <?php if ($rol['id']!=4): ?>
                        <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                      <?php endif ?>
                    <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.personajes') ?>:</label>
                <input name="personaje_actor[]" type="text" placeholder="">
              </div>
              <div class="column two">
                <label for=""><?php echo lang('global.ano') ?>:</label>
                <select name="ano_actor[]">
                  <option value=""><?php echo lang('global.seleccione_un_ano') ?></option>
                  <?php for ($i= date('Y'); $i >= 1900; $i--) { ?>
                    <option value="<?=$i?>"><?=$i?></option>
                  <?php } ?>
                </select>              </div>
              <div class="column two">
                <label for="">&nbsp;</label>
                <button type="button" class="button add_production inline_button columns twelve"><?php echo lang('global.agregar') ?></button>
              </div>
              <div class="clr6"></div>
            </div>
          </div>
        </div>
      
        <div class="row">
          <div class="clr6"></div>
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.fotos_videos') ?></h5></div>
            <div class="info with_title">
              <div class="columns twelve">
                <div id="list_fotos_casting">
                </div>
              </div> 
              <div class="clr6"></div>
                <div class="columns twelve text-center">

                  <div class="add-foto">
                    <label for=""><?php echo lang('global.foto_principal') ?>:</label>
                    <div class="foto"><img src=""></div>
                    <div class="boton-foto"><a href="#" class="button twelve cambiar_foto_actor"><?php echo lang('global.agregar') ?></a></div>
                    <input type="file" name="foto_actor[]" id="fotos_actor_p" class="foto_remplazo" style="display:none;">
                  </div>
                  <div class="add-foto">
                    <label for=""><?php echo lang('global.foto_uno') ?>:</label>
                    <div class="foto"><img src=""></div>
                    <div class="boton-foto"><a href="#" class="button twelve cambiar_foto_actor"><?php echo lang('global.agregar') ?></a></div>
                    <input type="file" name="foto_actor[]" id="fotos_actor_1" class="foto_remplazo" style="display:none;">
                  </div>
                  <div class="add-foto">
                    <label for=""><?php echo lang('global.foto_dos') ?>:</label>
                    <div class="foto"><img src=""></div>
                    <div class="boton-foto"><a href="#" class="button twelve cambiar_foto_actor"><?php echo lang('global.agregar') ?></a></div>
                    <input type="file" name="foto_actor[]" id="fotos_actor_2" class="foto_remplazo" style="display:none;">
                  </div>
                  <div class="add-foto">
                    <label for=""><?php echo lang('global.foto_tres') ?>:</label>
                    <div class="foto"><img src=""></div>
                    <div class="boton-foto"><a href="" class="button twelve cambiar_foto_actor"><?php echo lang('global.agregar') ?></a></div>
                    <input type="file" name="foto_actor[]" id="fotos_actor_3" class="foto_remplazo" style="display:none;">
                  </div>
                </div>
                <div class="clr6"></div>

              <div class="clr"></div>
                <div class="clr"></div>
                <div class="columns twelve content_link_video" id="links_videos"></div>
                <div class="columns twelve content_link_video">
                  <div class="content-link-video">
                    <label for=""><?php echo lang('global.videos') ?>:</label>
                    <input type="text" name="links_videos[]" class="link_video">
                    <a href="#" id="more_link" class="button two"><?php echo lang('global.agregar') ?></a>
                  </div>
                </div>

                <div class="clr"></div>
             
            </div>
          </div>
        </div>
          
        <div class="row">
          <div class="clr6"></div>
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.documentos') ?></h5></div>
            <div class="info documentos with_title" id="documentos_section">
              <div class="row clone_row">
                <div class="columns two">
                  <label for=""><?php echo lang('global.agregar_tipo_documento') ?>:</label>
                  <select name="documentos_select" id="documentos_select">
                    <?php if ($tipos_documentacion): ?>
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>

                            <?php foreach ($tipos_documentacion as $tipo_documentacion): ?>
                                     <?php if ($tipo_documentacion->id!=2 and $tipo_documentacion->id!=3 and $tipo_documentacion->id!=4): ?>
                                           <option value="<?=$tipo_documentacion->id?>"><?=$tipo_documentacion->descripcion?></option>
                                     <?php endif ?>      
                            <?php endforeach ?>
                    <?php endif ?>    
                  </select>
                </div>
                <div class="load_content"></div>
              </div>

              <!-- DOCUMENTO IDENTIDAD -->
              <div class="columns twelve document_row" id="document_1" style="display:none">
                <div class="column three" style="margin-bottom:10px"> 
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="documento_identidad"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <span class="label-file" data-input="documento_identidad"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="1" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>
              <!-- ARL -->
              <div class="columns twelve document_row" id="document_2" style="display:none">
                <div class="column two">
                  <label for="">ARL (Riesgos profesionales):</label>
                  <input type="text"  name="arl_nombre" id="arl_nombre">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.activo') ?>:</label>
                  <select name="arl_activo" id="arl_activo">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.clase') ?>:</label>
                  <select name="arl_clase" id="arl_clase">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="arl_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <span class="label-file" data-input="arl_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="2" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>
              <!-- EPS -->
              <div class="columns twelve document_row" id="document_3" style="display:none">
                <div class="column two">
                  <label for="">EPS:</label>
                  <input type="text"  name="eps_nombre" id="eps_nombre">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.activo') ?>:</label>
                  <select name="eps_activo" id="eps_valido">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.clase') ?>:</label>
                  <select name="eps_clase" id="eps_clase">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="eps_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <span class="label-file" data-input="eps_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="3" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>
              <!-- FONDO PENSIONES -->
              <div class="columns twelve document_row" id="document_4" style="display:none">
                <div class="column two">
                  <label for=""><?php echo lang('global.fondo_pensiones') ?>:</label>
                  <input type="text"  name="pensiones_nombre" id="pensiones_nombre">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.activo') ?>:</label>
                  <select name="pensiones_activo" id="pensiones_activo">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.clase') ?>:</label>
                  <select name="pensiones_clase" id="pensiones_clase">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="pensiones_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <span class="label-file" data-input="pensiones_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="4" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>

              <div class="columns twelve document_row" id="document_5" style="display:none">
                
                <div class="column two">
                  <label><?php echo lang('global.pais') ?>:</label>
                  <?php if($paises){ ?>
                    <select name="pasaporte_pais">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($paises as $pais) { ?>
                      <option value="<?=$pais->id?>"><?=$pais->nombre?></option>
                      <?php } ?>
                    </select>
                  <?php } ?>
                </div>

                <div class="column three">
                  <label><?php echo lang('global.pasaporte') ?>:</label>
                  <input type="text" name="pasaporte_numero">
                </div>

               
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="pasaporte"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <input type="file" name="pasaporte_documento"  class="with-label" style="display:none">
                      <span class="label-file" data-input="pasaporte"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
               <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="5" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>


              <div class="columns twelve document_row" id="document_6" style="display:none">
                <div class="column two">
                  <label><?php echo lang('global.pais') ?>:</label>
                  <?php if($paises){ ?>
                    <select name="visa_pais">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($paises as $pais) { ?>
                      <option value="<?=$pais->id?>"><?=$pais->nombre?></option>
                      <?php } ?>
                    </select>
                  <?php } ?>
                </div>
                <div class="column three">
                  <label>Visa:</label>
                  <input type="text" name="visa_numero">
                </div>                
                <div class="column two">
                  <label><?php echo lang('global.vigencia') ?>:</label>
                  <input type="text" class="visa_vigencia" name="visa_vigencia">
                </div>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="visa_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <input type="file" name="visa_documento"  class="with-label" style="display:none">
                      <span class="label-file" data-input="visa_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>

                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="6" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>

              <div class="columns twelve document_row" id="document_7" style="display:none">
                <div class="column three">
                  <label><?php echo lang('global.otros') ?>:</label>
                  <input type="text" name="otro_numero">
                </div>
                
                <div class="column three">
                   <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="otro_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <input type="file" name="otro_documento" class="contrato_firmado with-label" style="display:none">
                      <span class="label-file" data-input="otro_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="7" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>


              <!-- registro civil  -->
              <div class="columns twelve document_row" id="document_8" style="display:none">
                <div class="column three" style="margin-bottom:10px"> 
                  <label for=""><?php echo lang('global.rcivil') ?></label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="registro_civil"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <span class="label-file" data-input="registro_civil"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <!--div class="columns one">
                  <button type="button" class="agregar_documento button twelve alignTop">AGREGAR</button>
                </div-->
                <div class="columns one">
                      <button type="button" data-tipo="8" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>

              <input type="file" name="documento_identidad" id="documento_identidad" class="with-label" style="display:none">
              <input type="file" name="arl_documento" id="arl_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="eps_documento" id="eps_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="pensiones_documento" id="pensiones_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="registro_civil" id="registro_civil" class=" contrato_firmado with-label" style="display:none">
            <div class="clr"></div>
            </div>
                   
          </div>
          

        </div>


          

        <div class="row">
          <div class="clr6"></div>
          <div class="column twelve">
             <div class="column title_section"><h5><?php echo lang('global.notas') ?></h5></div>
            <div class="info with_title">
              <div class="column three">
                <label for=""><?php echo lang('global.rol_desempeña') ?></label>
                <?php if($roles){?>
                <select name="rol" id="">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($roles as $rol) { ?>
                      <?php if ($rol['id']!=4): ?>
                      <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                      <?php endif ?>
                    <?php } ?>
                </select>
                <?php } ?>
              </div>
              <div class="column nine">
                <label for=""><?php echo lang('global.anotacion_del_actor') ?></label>
                <textarea name="notas_actor" id="notas_actor"></textarea>
              </div>
              
              <div class="clr"></div>
            </div>
          </div>
        </div>

        

        <div class="clr6"></div>

        <div class="row" style="padding:0px 5px;">
          <div class="column six">
             <a  href="<?=base_url($this->lang->lang().'/'.'casting')?>" class="button btn_orange h_40 no-padding-right" style="margin:0;"><?php echo lang('global.cancelar') ?></a>
           </div> 
          <div class="column six">
            <button type="button" class="button twelve h_40" id="actorInsert"><?php echo lang('global.crear_actor') ?></button>
          </div>
        </div>
  <?php echo form_close(); ?> 
</div>