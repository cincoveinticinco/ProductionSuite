<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.editar_actor') ?>
</div>

<nav>
    <ul class="nav_post nav_casting">
        <li><a href="<?=base_url($idioma.'casting')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.buscar_actor') ?></a></li>
        <li><a href="<?=base_url($idioma.'casting/anadir_actor')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_actor') ?></a></li>
    </ul>
</nav>

<div id="inner_content">
  <?php if ($actor): ?>
    <?php echo form_open_multipart($idioma.'casting/update_actor','id="crear_actor", class="", onSubmit=""');?>
          <div class="row">
            <div class="column twelve">
               <div class="column title_section"><h5><?php echo lang('global.datos_actor') ?></h5></div>
              <div class="info with_title">
                <div class="column two">
                  <label for=""><?php echo lang('global.nombre') ?>:</label>
                  <input name="id_actor" id="id_actor" type="hidden" value="<?=$actor[0]->id?>">
                  <input name="nombre_actor" type="text" value="<?=$actor[0]->nombre?>" class="required" placeholder="">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.apellido') ?>:</label>
                  <input name="apellido_actor" type="text" value="<?=$actor[0]->apellido?>" class="required" placeholder="">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.nacionalidad') ?>:</label>
                  <?php if($nacionalidades){ ?>
                  <select name="nacionalidad" id="nacionalidad" class="s4a required">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($nacionalidades as $nacionalidad) { ?>
                      <option value="<?=$nacionalidad->id?>" 
                        <?php if ($nacionalidad->id == $actor[0]->id_nacionalidad): ?>
                          selected 
                        <?php endif ?>
                      ><?=$nacionalidad->descripcion?></option>
                     <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.tipo_de_documento') ?>:</label>
                  <?php if($tipos_documento){ ?>
                  <select name="tipo_documento" id="tipo_documento">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($tipos_documento as $tipo_documento) { ?>
                      <option value="<?=$tipo_documento->id?>" 
                        <?php if ($tipo_documento->id == $actor[0]->id_tipo_documento): ?>
                          selected 
                        <?php endif ?>
                      ><?=$tipo_documento->descripcion?></option>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.numero_documento') ?>:</label>
                  <input name="documento" id="documento_actor" value="<?=$actor[0]->documento?>" type="text" class="required" placeholder="">
                </div>
                <div class="clr6"></div>

                <div id="" style="display:none;">
                  <!-- VISA -->
                  <div class="columns two">
                    <div class="columns two">
                      <label><?php echo lang('global.pais') ?>:</label>
                      <input type="text" name="visa_numero">
                    </div>
                    <div class="columns three">
                      <label>Visa:</label>
                      <input type="file" name="visa_url">
                    </div>
                    <div class="columns three">
                      <label><?php echo lang('global.numero') ?>:</label>
                      <input type="text" name="visa_numero">
                    </div>                    
                    <div class="columns two">
                      <label><?php echo lang('global.vigencia') ?>:</label>
                      <input type="text" name="visa_vigencia" class="visa_vigencia">
                    </div>
                  </div>

                  <!-- PASAPORTE -->
                  <div class="columns four">
                    <label><?php echo lang('global.pasaporte') ?>:</label>
                    <input type="file" name="pasaporte">
                  </div>

                  <!-- CÉDULA DE EXTRANJERÍA-->
                  <div class="columns four">
                    <label><?php echo lang('global.cedula_extranjeria') ?></label>
                    <input type="file" name="cedula_extranjera">
                  </div>

                  <div class="clr6"></div>
                </div>

                <div class="column two">
                  <label for=""><?php echo lang('global.fecha_nacimiento') ?>:</label>
                  <input type="text" name="fecha_nacimiento" class="required" id="birthday_actor" value="<?=date("d-M-Y", strtotime($actor[0]->fecha_nacimiento))?>">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.genero') ?>:</label>
                  <?php if($generos){ ?>
                  <select name="genero">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($generos as $genero) { ?>
                      <option value="<?=$genero->id?>" 
                        <?php if ($genero->id == $actor[0]->id_generos): ?>
                          selected 
                        <?php endif ?>
                      ><?=$genero->descripcion?></option>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <div class="column six">
                    <label for=""><?php echo lang('global.altura_metros') ?>:</label>
                    <input type="text" name="altura" id="altura" sstyle="width:97%;float:left" value="<?=$actor[0]->altura?>">
                  </div>
                  <div class="column six">
                    <label for=""><?php echo lang('global.peso_kg') ?>:</label>
                    <input type="text" name="peso" id="peso" style="width:97%;float:right" value="<?=$actor[0]->peso?>">
                  </div>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.color_tez') ?>:</label>
                  <?php if($colores_tez){  ?>
                  <select name="color_tez">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($colores_tez as $color_tez) {  ?>
                      <option value="<?=$color_tez->id?>" 
                        <?php if ($color_tez->id == $actor[0]->id_color_tez): ?>
                          selected 
                        <?php endif ?>
                      ><?=$color_tez->descripcion?></option>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.color_ojos') ?>:</label>
                  <?php if($colores_ojos){  ?>
                  <select name="color_ojos">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($colores_ojos as $color_ojos) { ?>
                      <option value="<?=$color_ojos->id?>" 
                        <?php if ($color_ojos->id==$actor[0]->id_color_ojos): ?>
                          selected 
                        <?php endif ?>
                      ><?=$color_ojos->descripcion?></option>
                    <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="clr6"></div>
                
                 
                <div class="representante_legal" <?php if($actor[0]->id_tipo_documento!=2){ ?> style="display:none" <?php } ?>>
                    <div class="column two">
                      <label for=""><?php echo lang('global.nombre_representante_legar') ?>:</label>
                      <input type="text" name="nombre_representante_legal" value="<?php echo $actor[0]->nombre_representante_legal ?>" class="inf_repre" id="">
                    </div>
                    <div class="column two">
                      <label for=""><?php echo lang('global.tipo_de_documeto_representante_legal') ?>:</label>
                      <?php if($tipos_documento){ ?>
                      <select name="id_tipo_documento_representante_legal" id="id_tipo_documento_representante_legal" class="inf_repre">
                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($tipos_documento as $tipo_documento) { ?>
                           <?php if($tipo_documento->id!=2 and $tipo_documento->id!=4){ ?>
                            <option value="<?=$tipo_documento->id?>"
                              <?php if ($tipo_documento->id==$actor[0]->id_tipo_documento_representante_legal): ?>
                              selected 
                            <?php endif ?>
                            ><?=$tipo_documento->descripcion?></option>
                            <?php } ?>
                          <?php } ?>
                      </select>
                      <?php } ?>
                    </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.numero_documento_representante_legal') ?>:</label>
                    <input name="documento_representante_legal" id="documento_representante_legal" value="<?php echo $actor[0]->documento_representante_legal ?>" type="text" class="inf_repre" >
                  </div>
                   
                   <div class="column two">
                      <label for=""><?php echo lang('global.telefono_fijo_representante_legal') ?>:</label>
                      <input type="text" name="telefono_fijo_representante" value="<?php echo $actor[0]->telefono_fijo_representante ?>" class="inf_repre" id="">
                    </div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.celular_representante_legal') ?>:</label>
                      <input type="text" name="celular_representante" value="<?php echo $actor[0]->celular_representante ?>" class="inf_repre" id="">
                    </div>
                    <div class="clr6"></div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.direccion_representante_legal') ?>:</label>
                      <input type="text" name="direccion_representante" value="<?php echo $actor[0]->direccion_representante ?>" class="inf_repre" id="">
                    </div>

                    <div class="column two">
                      <label for=""><?php echo lang('global.correo_representante_legal') ?>:</label>
                      <input type="text" name="correo_representante" value="<?php echo $actor[0]->correo_representante ?>" class="inf_repre" id="">
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
                      <label class="label_check">
                        <input value="<?=$idioma->id?>" name="idiomas[]" 
                        <?php if(strtolower($idioma->descripcion)=="otro" || strtolower($idioma->descripcion)=="otra"){ ?> 
                          class="languaje" 
                        <?php } ?> 
                        type="checkbox"
                        <?php if (strpos(','.$actor[0]->idiomas_actor,$idioma->id)>0): ?>
                        <?php if ($idioma->id==9): ?>
                          <?php if ($actor[0]->otros_idiomas): ?>
                            checked
                          <?php endif ?>
                        <?php else: ?>
                          checked
                        <?php endif ?>
                          
                        <?php endif ?>

                        > 
                        <?=$idioma->descripcion?>
                      </label>
                    </div>
                  <?php } ?>
                  <div class="column two right">
                    <input type="text" name="otro_idioma" id="other_languaje" placeholder="OTRO"
                      <?php if (strpos($actor[0]->idiomas_actor,"9")): ?>
                        style="display:block;margin-top: 4px; display: inline-block;"
                      <?php endif ?>
                    value="<?=$actor[0]->otros_idiomas?>">
                  </div>
                <?php } ?>
                <div class="clr"></div>
              </div>
            </div>
          </div>

          <?php $required=''; if($actor[0]->id_nacionalidad==13){
             $required='required';
            } ?>

          <div class="row">
            <div class="column twelve">
              <div class="info">
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                  <input name="telefono_fijo" type="text" class="telefono_fijo" placeholder="" value="<?=$actor[0]->telefono_fijo?>">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                  <input name="telefono_movil"  type="text" class="<?php echo $required ?> telefono_movil" placeholder="" value="<?=$actor[0]->telefono_movil?>">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.direccion') ?>:</label>
                  <input name="direccion" type="text" class="<?php echo $required ?> direccion" placeholder="" value="<?=$actor[0]->direccion?>">
                </div>

                <div class="location_section">
                  <div class="column two">
                    <label for=""><?php echo lang('global.pais') ?>:</label>
                    <?php if($paises){ ?>
                    <select name="pais"  id="pais" class="pais_selector s4a <?php echo $required ?> pais_residencia">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($paises as $pais) { ?>
                      <option value="<?=$pais->id?>"
                        <?php if ($pais->id == $actor[0]->id_paises): ?>
                          selected
                        <?php endif ?>
                      ><?=$pais->nombre?></option>
                      <?php } ?>
                    </select>
                    <?php } ?>
                  </div>
                  <div class="column two">
                  <label for=""><?php echo lang('global.ciudad') ?>:</label>
                  <?php if($ciudades){ ?>
                    <select name="ciudad" id="ciudad" class="ciudad_selector s4a <?php echo $required ?> ciudad_residencia">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($ciudades as $ciudades) { ?>
                      <option value="<?=$pais->id?>"
                        <?php if ($ciudades->id == $actor[0]->ciudad): ?>
                          selected
                        <?php endif ?>
                      ><?=$ciudades->nombre?></option>
                      <?php } ?>
                    </select>
                    <?php }else{ ?>
                       <select name="ciudad" id="ciudad" class="ciudad_selector s4a <?php echo $required ?> ciudad_residencia">
                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($ciudades as $ciudades) { ?>
                        <option value="<?=$pais->id?>"
                         
                        ><?=$ciudades->nombre?></option>
                        <?php } ?>
                      </select>
                    <?php } ?>
                    
                    <input type="hidden" id="ciudad_hidden" value="<?=$actor[0]->ciudad?>">

                  </div>
                  <div class="clr6"></div>
                </div>
                
                <div class="column two">
                  <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                  <input name="email" type="email" placeholder="" value="<?=$actor[0]->email?>"> 
                </div>
                <div class="column two">
                <label for=""><?php echo lang('global.contactar') ?>:</label>
                <select name="contactar" id="contactar">
                  <?php if ($contactos): ?>
                    <?php foreach ($contactos as $contacto): ?>
                      <option value="<?=$contacto->id?>"
                        <?php if ($contacto->id == $actor[0]->contactar): ?>
                           selected style="display:block;" 
                        <?php endif ?>
                        <?php if (!$actor[0]->id_manager and $contacto->id!=2){ ?>
                         disabled="true" 
                        <?php } ?>
                        <?php if ($contacto->id!=2){ ?>class="disabled_item"
                        <?php } ?>
                      ><?=$contacto->descripcion?></option>
                    <?php endforeach ?>
                  <?php endif ?>
                </select>
              </div>
              <div class="clr6"></div>
                <div class="column two">
                  <label for=""><?php echo lang('global.tiene_manager') ?>:</label>
                  <div class="columns">
                    <label for="" class="label_check">
                      <input name="manager_validation" class="manager_validation" type="radio" value="1"
                        <?php if ($actor[0]->id_manager): ?>
                          checked
                        <?php endif ?>
                      > Si
                    </label>
                  </div>
                  <div class="columns alignLeft">
                    <label for="" class="label_check">
                      <input name="manager_validation" class="manager_validation" type="radio" value=""
                        <?php if (!$actor[0]->id_manager): ?>
                          checked
                        <?php endif ?>
                      > 
                      No
                    </label>
                  </div>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.existe_sociedad') ?>:</label>
                  <div class="columns">
                    <label for="" class="label_check">
                      <input name="society_validation" class="society_validation society_validation_si" type="radio" value="1"
                        <?php if ($actor[0]->nit_sociedad): ?>
                          checked
                        <?php endif ?>
                      > 
                      Si
                    </label>
                  </div>
                  <div class="columns alignLeft">
                    <label for="" class="label_check">
                      <input name="society_validation" class="society_validation society_validation_no" type="radio" value="2"
                        <?php if (!$actor[0]->nit_sociedad): ?>
                          checked
                        <?php endif ?>
                      > 
                      No
                    </label>
                  </div>
                </div>
                <div class="column two">
                  <label for="">¿<?php echo lang('global.disponible') ?>?:</label>
                  <div class="columns">
                    <label class="label_check">
                      <input type="radio" name="disponible" value="1" class="disponible"
                        <?php if ($actor[0]->disponible!=2): ?>
                          checked
                        <?php endif ?>
                      >
                      Si
                    </label>
                  </div>
                  <div class="columns alignLeft">
                    <label class="label_check">
                      <input type="radio" name="disponible" value="2" class="disponible"
                        <?php if ($actor[0]->disponible==2): ?>
                          checked
                        <?php endif ?>
                      >
                      No
                    </label>
                  </div>

                </div>

                <div class="column two" id="terminacion_proyecto_section" 
                  <?php if ($actor[0]->disponible!=2): ?>
                    style="display:none;"
                  <?php endif ?>
                >
                  <label for=""><?php echo lang('global.terminacion_proyecto') ?>:</label>
                  <input type="text" name="terminacion_proyecto" id="terminacion_proyecto" class="datepicker" value="<?=date("d-M-Y",strtotime($actor[0]->terminacion_proyecto));?>">
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>

           <div class="row" id="manager_section" <?php if ($actor[0]->id_manager): ?> style="display:block;" <?php endif; ?>>
            <div class="column twelve">
              <div class="column title_section"><h5>MANAGER</h5></div>
              <div class="info with_title">
                <?php $estilo =""; ?>  
                <div id="agregar_manager">
                  <div class="column two">
                    <label for="">Manager:</label>
                    <?php if ($managers): ?>
                      <?php $estilo ="readonly"; ?>
                      <select name="id_manager" id="id_manager">
                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($managers as $manager): ?>
                          <option value="<?=$manager->id?>"
                            <?php if ($manager->id == $actor[0]->id_manager): ?>
                              selected
                            <?php endif ?>
                          ><?=$manager->nombre?></option>
                        <?php endforeach ?>
                          <option value="0">Otro</option>
                      </select>
                    <?php endif ?>
                    <input type="text" value="" name="nombre_manager" id="nombre_manager" style="display:none;">
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                    <input type="text" placeholder="" value="" name="telefono_fijo_manager" id="telefono_fijo_manager">
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                    <input type="text" placeholder="" value="" name="telefono_movil_manager" id="telefono_movil_manager">
                  </div>
                  <div class="column two e_mail">
                    <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                    <input type="text" placeholder="" value="" name="email_manager" id="email_manager">
                  </div>
                  <div class="column two <?php echo lang('global.cancelar') ?>_manager" style="display:none;">
                    <label for="">&nbsp;</label>
                    <a href="#" class="button cancel" id="cancelar_manager" ><?php echo lang('global.cacelar') ?></a>
                  </div>
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>



          <div class="row" id="contacto_section">
            <div class="column twelve">
              <div class="column title_section"><h5><?php echo lang('global.contacto_contractual') ?></h5></div>
              <div class="info with_title">
                <div class="column two">
                  <label for=""><?php echo lang('global.contacto_contractual') ?>:</label>
                  <input type="text" value="<?=$actor[0]->contacto_nombre?>" name="contacto_nombre">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                  <input type="text" value="<?=$actor[0]->contacto_telefono?>"  name="contacto_telefono" placeholder="">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                  <input type="text" value="<?=$actor[0]->contacto_telefono_movil?>"  name="contacto_telefono_movil"  placeholder="">
                </div>
                <div class="column four e_mail">
                  <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                  <input type="text" value="<?=$actor[0]->contacto_email?>"  name="contacto_email" placeholder="">
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>

         

          <div class="row" id="society_section" 
            <?php if ($actor[0]->nit_sociedad): ?>
              style="display:block"
            <?php endif ?>
          >
            <div class="column twelve">
              <div class="column title_section"><h5><?php echo lang('global.datos_sociedad') ?></h5></div>
              <div class="info with_title">
                <div class="column two">
                  <label for=""><?php echo lang('global.razon_social') ?>:</label>
                  <?php if ($sociedades): ?>
                    <select name="razon_social" id="razon_social">
                      <option><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($sociedades as $sociedad): ?>
                          <option value="<?=$sociedad->id?>" 
                            <?php if ($sociedad->id == $actor[0]->id_sociedad): ?>
                               selected 
                            <?php endif ?>
                          ><?=$sociedad->nombre?></option>
                        <?php endforeach ?>
                      <option value="0">Otro</option>
                    </select>
                  <?php endif ?>
                  <input type="text" id="nombre_sociedad" name="nombre_sociedad">
                </div>

                

                <div class="column two">
                  <label for="">NIT:</label>
                  <input type="text" name="nit_sociedad" id="nit_sociedad" placeholder="" 
                    <?php if ($actor[0]->nit_sociedad): ?>
                      value="<?=$actor[0]->nit_sociedad?>"
                    <?php endif ?>
                  >
                </div>

                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_fijo') ?>:</label>
                  <input type="text" name="telefono_fijo_sociedad" id="telefono_fijo_sociedad" placeholder=""
                    <?php if ($actor[0]->telefono_fijo_sociedad): ?>
                      value="<?=$actor[0]->telefono_fijo_sociedad?>"
                    <?php endif ?>
                  >
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.telefono_movil') ?>:</label>
                  <input type="text" name="telefono_movil_sociedad" id="telefono_movil_sociedad" placeholder=""
                    <?php if ($actor[0]->telefono_movil_sociedad): ?>
                      value="<?=$actor[0]->telefono_movil_sociedad?>"
                    <?php endif ?>
                  >
                </div>

               

                <div class="column two">
                  <label for=""><?php echo lang('global.direccion') ?>:</label>
                  <input type="text" name="direccion_sociedad" id="direccion_sociedad" placeholder=""
                    <?php if ($actor[0]->direccion_sociedad): ?>
                      value="<?=$actor[0]->direccion_sociedad?>"
                    <?php endif ?>
                  >
                </div>

                   <div class="clr6"></div>
              
                <div id="location_society">
                  <div class="column two">
                    <label for=""><?php echo lang('global.pais') ?>:</label>
                      <?php if($paises){ ?>
                      <select name="pais_sociedad"  id="pais_sociedad" class="pais_selector s4a required">
                        <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                        <?php foreach ($paises as $pais) { ?>
                        <option value="<?=$pais->id?>"
                          <?php if ($actor[0]->pais_sociedad AND $pais->id == $actor[0]->id_pais_sociedad ): ?>
                             selected 
                          <?php endif ?>
                        ><?=$pais->nombre?></option>
                        <?php } ?>
                      </select>
                      <?php } ?>
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.ciudad') ?>:</label>
                    <select name="ciudad_sociedad" id="ciudad_sociedad" class="ciudad_selector s4a">
                    </select>
                    <input type="hidden" name="id_ciudad_sociedad_hidden" id="id_ciudad_sociedad_hidden"
                      <?php if ($actor[0]->id_ciudad_sociedad): ?>
                         value="<?=$actor[0]->id_ciudad_sociedad?>" 
                      <?php endif ?>
                    >
                  </div>

                  

                  <div class="column two">
                    <label for=""><?php echo lang('global.correo_electronico') ?>:</label>
                    <input type="text" name="email_sociedad" id="email_sociedad"
                      <<?php if ($actor[0]->email_sociedad): ?>
                        value="<?=$actor[0]->email_sociedad?>"
                      <?php endif ?>

                    >
                  </div>
                  
                 <div class="column two">
                      <label for=""><?php echo lang('global.representante_legal') ?>:</label>
                     <input type="text" id="representante_legal" name="representante_legal" value="<?=$actor[0]->representante_legal?>" placeholder="">
                   </div>  

         
                  <div class="column two">
                    <label for=""><?php echo lang('global.documento_representante') ?>:</label>
                    <input type="text" name="representante_documento" id="representante_documento"
                      <<?php if ($actor[0]->representante_legal): ?>
                        value="<?=$actor[0]->representante_documento?>"
                      <?php endif ?>
                    >
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

          <div class="row">
            <div class="column twelve">
              <div id="base_production" style="display:none;">
                <div class="column two">
                  <label for=""><?php echo lang('global.produccion') ?>:</label>
                  <input name="produccion_actor[]" type="text" placeholder="">
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
                    <option value="">Seleccione un año</option>
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
                  <input name="produccion_actor[]" type="text" placeholder="" value="<?=($proyectos_actor) ? $proyectos_actor[0]->nombre_proyecto : '';?>">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.rol') ?>:</label>
                  <?php if($roles){?>
                  <select name="rol_actor[]" id="">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($roles as $rol) { ?>
                        <?php if ($rol['id']!=4): ?>
                          <option value="<?=$rol['id']?>"
                          <?php if ($proyectos_actor AND  $rol['id'] == $proyectos_actor[0]->id_rol): ?>
                            selected  
                          <?php endif ?>
                          ><?=$rol['rol']?></option>
                        <?php endif ?>
                      <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.personajes') ?>:</label>
                  <input name="personaje_actor[]" type="text" placeholder="" value="<?=($proyectos_actor) ? $proyectos_actor[0]->nombre_personaje : '';?>">
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.ano') ?>:</label>
                  <select name="ano_actor[]">
                    <option value="">Seleccione un año</option>
                    <?php for ($i=date('Y'); $i >= 1900; $i--) { ?>
                      <option value="<?=$i?>"
                        <?php if ($proyectos_actor AND  $i == $proyectos_actor[0]->ano): ?>
                            selected  
                        <?php endif ?>
                      ><?=$i?></option>
                    <?php } ?>
                  </select>              
                </div>
                <div class="column two">
                  <label for="">&nbsp;</label>
                  <button type="button" class="button add_production inline_button columns twelve"><?php echo lang('global.agregar') ?></button>
                </div>
                <div class="clr6"></div>
                <?php if (count($proyectos_actor)>1): ?>
                  <?php for ($p=1; $p < count($proyectos_actor); $p++) { ?>
                    <div class="column two">
                      <label for=""><?php echo lang('global.produccion') ?>:</label>
                      <input name="produccion_actor[]" type="text" placeholder="" value="<?=$proyectos_actor[$p]->nombre_proyecto?>">
                    </div>
                    <div class="column two">
                      <label for=""><?php echo lang('global.rol') ?>:</label>
                      <?php if($roles){?>
                      <select name="rol_actor[]" id="">
                          <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                          <?php foreach ($roles as $rol) { ?>
                            <?php if ($rol['id']!=4): ?>
                              <option value="<?=$rol['id']?>"
                              <?php if ($rol['id'] == $proyectos_actor[$p]->id_rol): ?>
                                selected  
                              <?php endif ?>
                              ><?=$rol['rol']?></option>
                            <?php endif ?>
                          <?php } ?>
                      </select>
                      <?php } ?>
                    </div>
                    <div class="column two">
                      <label for=""><?php echo lang('global.personajes') ?>:</label>
                      <input name="personaje_actor[]" type="text" placeholder="" value="<?=$proyectos_actor[$p]->nombre_personaje?>">
                    </div>
                    <div class="column two">
                      <label for=""><?php echo lang('global.ano') ?>:</label>
                      <select name="ano_actor[]">
                        <option value="">Seleccione un año</option>
                        <?php for ($i=1900; $i <= date('Y'); $i++) { ?>
                          <option value="<?=$i?>"
                            <?php if ($i == $proyectos_actor[$p]->ano): ?>
                                selected  
                            <?php endif ?>
                          ><?=$i?>
                          </option>
                        <?php } ?>
                      </select>              
                    </div>
                    <div class="clr6"></div>
                  <?php } ?>
                <?php endif ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="column twelve">
              <div class="column title_section"><h5><?php echo lang('global.fotos_videos') ?></h5></div>
              <div class="info with_title">
                <div class="clr6"></div>
                
                <div class="columns twelve text-cener">
                  <div id="list_fotos_casting">
                    <div class="add-foto">
                      <label for=""><?php echo lang('global.foto_principal') ?>:</label>
                      <div class="foto">
                      <img 
                      <?php if ($fotos_actor[0]->ruta_foto): ?>
                        src="<?=base_url($fotos_actor[0]->ruta_foto)?>"}                      
                      <?php endif ?>
                      ></div>
                      <div class="boton-foto">
                      <?php 
                        $label_picture = lang('global.agregar');
                        if ($fotos_actor[0]->ruta_foto) {
                          $label_picture = lang('global.cambiar');
                        }
                      ?>
                      <a href="#" class="button twelve cambiar_foto_actor" data-foto="" data-nameimagen=""><?=$label_picture?></a></div>
                      <input type="hidden" name="foto_original_0" value="<?=$fotos_actor[0]->id?>">
                      <input type="file" name="foto_remplazo_0" style="display:none;" class="foto_remplazo">
                    </div>
                    <div class="add-foto">
                      <label for=""><?php echo lang('global.foto_uno') ?>:</label>
                      <div class="foto">
                        <img 
                        <?php if ($fotos_actor[1]->ruta_foto): ?>
                          src="<?=base_url($fotos_actor[1]->ruta_foto)?>"
                        <?php endif ?>
                        >
                      </div>
                      <div class="boton-foto">
                      <?php 
                        $label_picture = lang('global.agregar');
                        if ($fotos_actor[1]->ruta_foto) {
                          $label_picture = lang('global.cambiar');
                        }
                      ?>
                      <a href="#" class="button twelve cambiar_foto_actor" data-foto="" data-nameimagen=""><?=$label_picture?></a></div>
                      <input type="hidden" name="foto_original_1" value="<?=$fotos_actor[1]->id?>">
                      <input type="file" name="foto_remplazo_1" style="display:none;" class="foto_remplazo">
                    </div>
                    <div class="add-foto">
                      <label for=""><?php echo lang('global.foto_dos') ?>:</label>
                      <div class="foto">
                        <img 
                        <?php if ($fotos_actor[2]->ruta_foto): ?>
                          src="<?=base_url($fotos_actor[2]->ruta_foto)?>"
                        <?php endif ?>
                        >
                      </div>
                      <div class="boton-foto">
                      <?php 
                        $label_picture = lang('global.agregar');
                        if ($fotos_actor[2]->ruta_foto) {
                          $label_picture = lang('global.cambiar');
                        }
                      ?>
                      <a href="#" class="button twelve cambiar_foto_actor" data-foto="" data-nameimagen=""><?=$label_picture?></a></div>
                      <input type="hidden" name="foto_original_2" value="<?=$fotos_actor[2]->id?>">
                      <input type="file" name="foto_remplazo_2" style="display:none;" class="foto_remplazo">
                    </div>
                    <div class="add-foto">
                      <label for=""><?php echo lang('global.foto_tres') ?>:</label>
                      <div class="foto">
                      <img 
                      <?php if ($fotos_actor[3]->ruta_foto): ?>
                        src="<?=base_url($fotos_actor[3]->ruta_foto)?>"
                      <?php endif ?>
                      ></div>
                      <div class="boton-foto">
                      <?php 
                        $label_picture = lang('global.agregar');
                        if ($fotos_actor[3]->ruta_foto) {
                          $label_picture = lang('global.cambiar');
                        }
                      ?>
                      <a href="#" class="button twelve cambiar_foto_actor" data-foto="" data-nameimagen=""><?=$label_picture?></a></div>
                      <input type="hidden" name="foto_original_3" value="<?=$fotos_actor[3]->id?>">
                      <input type="file" name="foto_remplazo_3" style="display:none;" class="foto_remplazo">
                    </div>
                </div>
                </div>
              <div class="clr"></div>

                <div class="columns three text-center">
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="column twelve">
              <div class="info">
                  <div class="columns twelve content_link_video" id="">
                    
                  
                  <?php if (count($videos_actor)>1): ?>
                    <?php for ($i=count($videos_actor)-1; $i >= 1; $i--) { ?>
                    <div class="content-link-video">
                      <label for="">videos:</label>
                      <input type="text" name="links_videos[]" class="link_video" value="<?=$videos_actor[$i]->url?>">
                      <a href="#" class="button btn_orange two delete_link"><?php echo lang('global.eliminar') ?></a>
                    </div>
                    <?php } ?> 
                  <?php endif ?>
                   <div class=" twelve content_link_video" id="links_videos"></div>
                  <div class="content-link-video">
                      <label for=""><?php echo lang('global.videos') ?>:</label>
                      <input type="text" name="links_videos[]" class="link_video"
                      <?php if ($videos_actor): ?>
                        value="<?=$videos_actor[0]->url?>"
                      <?php endif ?>
                      >
                      <a href="#" id="more_link" class="button two"><?php echo lang('global.agregar') ?></a>
                    </div>
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="column twelve">
              <div class="column title_section"><h5><?php echo lang('global.notas') ?></h5></div>
              <div class="info  with_title">
                <div class="column nine">
                  <label for=""><?php echo lang('global.anotacion_del_actor') ?></label>
                  <textarea name="notas_actor" id="notas_actor"><?=$actor[0]->notas_actor?></textarea>
                </div>
                <div class="column three">
                  <label for=""><?php echo lang('global.rol') ?></label>
                  <?php if($roles){?>
                  <select name="rol" id="">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($roles as $rol) { ?>
                        <?php if ($rol['id']!=4): ?>
                        <option value="<?=$rol['id']?>" 
                          <?php if ($rol['id']==$actor[0]->id_rol): ?>
                            selected
                          <?php endif ?>
                        ><?=$rol['rol']?></option>
                        <?php endif ?>
                      <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </div>

          <!-- 
            DOCUMENTOS
          -->

          
        <div class="row">
          <div class="column twelve">
            <div class="column title_section"><h5><?php echo lang('global.documentos') ?></h5></div>
            <div class="info documentos with_title" id="documentos_section">


                 <div class="columns twelve clone_row">
                    <div class="column two">

                          <label for=""><?php echo lang('global.agregar_tipo_documento') ?>:</label>
                          <select name="documentos_select" id="documentos_select">
                            <?php if ($tipos_documentacion): ?>
                              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                              <?php foreach ($tipos_documentacion as $tipo_documentacion): ?>
                                <?php 
                                  $documento_temporal = false;
                                  if ($tipo_documentacion->id!=7 AND $tipo_documentacion->id!=6 AND $tipo_documentacion->id!=5) {
                                    $documento_temporal = $this->model_casting->documento_actor($actor[0]->id,$tipo_documentacion->id); 
                                  }
                                ?>

                                <?php if ($tipo_documentacion->id!=2 and $tipo_documentacion->id!=3 and $tipo_documentacion->id!=4): ?>
                                  <option value="<?=$tipo_documentacion->id?>"
                                    <?php if ($documento_temporal): ?>
                                      disabled
                                    <?php endif ?>
                                  >
                                  <?=$tipo_documentacion->descripcion?></option>
                                <?php endif ?>      
                                
                              <?php endforeach ?>
                            <?php endif ?>    
                          </select>
                      
                    </div>
                <div class="load_content"></div>
        
              </div>
              <div class="clr6"></div> 
           


              <!-- DOCUMENTO IDENTIDAD -->
              <?php $label = lang('global.adjuntar'); ?>
              <div class="columns twelve" id="document_1" <?php if(isset($documentos_actor['0']->url)){ }else{?> style="display:none" <?php } ?>>
                <input type="hidden" value="<?php if($documentos_actor['0']){ echo $documentos_actor['0']->id;  } ?>" name="id_documento">
                <div class="column <?php if(isset($documentos_actor['0']->url)){ echo 'six'; }else{echo 'two';} ?>">
                  <label for="">&nbsp;</label>
                  <input type="text" disabled value="<?php echo lang('global.documentos') ?>" <?php if(isset($documentos_actor['0']->url)){ $label = lang('global.cambiar'); }else{?> style="display:none" <?php } ?>>
                </div>
                <div class="column three" style="margin-bottom:10px"> 
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="documento_identidad"><?=$label?> <?php echo lang('global.archivo') ?></span>
                      <span class="label-file" data-input="documento_identidad"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <div class="columns one">
                  <?php if ($label==lang('global.cambiar')): ?>
                    <a href="<?=base_url($documentos_actor['0']->url)?>" target="_blank" class="button alignTop twelve"><?php echo lang('global.ver') ?></a>
                    
                  <?php endif ?>
                </div>
                <?php if ($documentos_actor['0']->descripcion OR 
                          isset($documentos_actor['0']->id_clase_entidad) OR 
                          isset($documentos_actor['0']->id_estado_entidad) OR 
                          $documentos_actor['0']->url): ?>    
                  <div class="columns one">
                  <?php if(isset($documentos_actor['0']->id)){ ?>
                      <button type="button" data-tipo="1" data-id="<?php echo $documentos_actor['0']->id; ?>" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                  <?php }else{ ?>    
                      <button type="button" data-tipo="1" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                  <?php } ?>
                  <input type="hidden" name="documentos_eliminar[]" value="" class="documentos_eliminar_<?php echo $documentos_actor['0']->id; ?>">
                  </div>
                <?php endif ?>
            </div>
              

              <!-- ARL 
              <div class="columns twelve" id="document_2" <?php if($documentos_actor['1']->descripcion!="" or isset($documentos_actor['1']->id_clase_entidad) or isset($documentos_actor['1']->id_estado_entidad) or $documentos_actor['1']->url){ }else{?> style="display:none" <?php } ?>>
                <div class="column two">
                  <label for="">ARL (Riesgos profesionales):</label>
                  <input type="hidden" value="<?php if($documentos_actor['1']){ echo $documentos_actor['1']->id; } ?>" name="id_arl">
                  <input type="text" name="arl_nombre<?php echo Casting::valida_documento($documentos_actor['1']);?>" id="arl_nombre" value="<?php if($documentos_actor['1']){ echo $documentos_actor['1']->descripcion; } ?>">
                </div>
                <div class="column two">
                  <label for="">Activo:</label>
                  <select name="arl_activo<?php echo Casting::valida_documento($documentos_actor['1']);?>" id="arl_activo">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"
                        <?php if ($documentos_actor['1'] AND $documentos_actor['1']->id_estado_entidad == $estado_entidad->id): ?>
                          selected 
                        <?php endif ?>
                      ><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for="">Clase:</label>
                  <select name="arl_clase<?php echo Casting::valida_documento($documentos_actor['1']);?>" id="arl_clase">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"
                        <?php if ($documentos_actor['1'] AND $documentos_actor['1']->id_clase_entidad == $clase_entidad->id): ?>
                          selected 
                        <?php endif ?>
                      ><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <?php  
                  $label=lang('global.adjuntar');
                  if ($documentos_actor['1'] AND $documentos_actor['1']->url){
                    $label=lang('global.cambiar');
                  }
                ?>
                <div class="column three">
                   <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="arl_documento"><?=$label?> ARCHIVO</span>
                      <span class="label-file" data-input="arl_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <div class="columns one">
                  <?php if ($label==lang('global.cambiar')): ?>
                    <a href="<?=base_url($documentos_actor['1']->url)?>" target="_blank" class="button alignTop  twelve">VER</a>
                    
                  <?php else: ?>
                    <button type="button" class="agregar_documento button alignTop twelve">AGREGAR</button>
                  <?php endif ?>
                </div>

                <?php if ($documentos_actor['1']->descripcion OR 
                          isset($documentos_actor['1']->id_clase_entidad) OR 
                          isset($documentos_actor['1']->id_estado_entidad) OR 
                          $documentos_actor['1']->url): ?>    
                  <div class="columns one">
                      <button type="button" class="eliminar_documento_cargado btn_orange button alignTop twelve">ELIMINAR</button>
                  </div>
                <?php endif ?>
              </div> -->

              <!-- EPS
              <div class="columns twelve" id="document_3" <?php if($documentos_actor['2']->descripcion or isset($documentos_actor['2']->id_clase_entidad) or isset($documentos_actor['2']->id_estado_entidad) or $documentos_actor['2']->url){ }else{?> style="display:none" <?php } ?>>
                <div class="column two">
                  <label for="">EPS:</label>
                  <?php if ($documentos_actor['2']): ?>  
                    <input type="hidden" value="<?php if($documentos_actor['2']){ echo  $documentos_actor['2']->id;  }?>" name="id_eps">
                  <?php endif ?>
                  <input type="text"  name="eps_nombre<?php echo Casting::valida_documento($documentos_actor['2']);?>" id="eps_nombre" value="<?php if($documentos_actor['2']){  echo $documentos_actor['2']->descripcion; } ?>">
                </div>
                <div class="column two">
                  <label for="">Activo:</label>
                  <select name="eps_activo<?php echo Casting::valida_documento($documentos_actor['2']);?>" id="eps_valido">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"
                        <?php if ($documentos_actor['2'] AND $documentos_actor['2']->id_estado_entidad == $estado_entidad->id): ?>
                          selected 
                        <?php endif ?>
                        ><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for="">Clase:</label>
                  <select name="eps_clase<?php echo Casting::valida_documento($documentos_actor['2']);?>" id="eps_clase">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"
                        <?php if ($documentos_actor['2'] AND $documentos_actor['2']->id_clase_entidad == $clase_entidad->id): ?>
                          selected 
                        <?php endif ?>
                      ><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <?php  
                  $label=lang('global.adjuntar');
                  if ($documentos_actor['2'] AND $documentos_actor['2']->url){
                    $label=lang('global.cambiar');
                  }
                ?>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="eps_documento"><?=$label?> ARCHIVO</span>
                      <span class="label-file" data-input="eps_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <div class="columns one">
                  <?php if ($label==lang('global.cambiar')): ?>
                    <a href="<?=base_url($documentos_actor['2']->url)?>" target="_blank" class="button alignTop twelve">VER</a>
                  <?php else: ?>
                    <button type="button" class="agregar_documento button alignTop twelve">AGREGAR</button>
                  <?php endif ?>
                </div>

                <?php if ($documentos_actor['2']->descripcion OR 
                          isset($documentos_actor['2']->id_clase_entidad) OR 
                          isset($documentos_actor['2']->id_estado_entidad) OR 
                          $documentos_actor['2']->url): ?>    
                  <div class="columns one">
                      <button type="button" class="eliminar_documento_cargado btn_orange button alignTop twelve">ELIMINAR</button>
                  </div>
                <?php endif ?>
              </div>  -->

              <!-- FONDO PENSIONES 
              <div class="columns twelve" id="document_4" <?php if($documentos_actor['3']->descripcion or isset($documentos_actor['3']->id_clase_entidad) or isset($documentos_actor['3']->id_estado_entidad) or $documentos_actor['3']->url){ }else{?> style="display:none" <?php } ?>>
                <div class="column two">
                  <label for="">Fondo de Pensiones:</label>
                  <?php if ($documentos_actor['3']): ?>
                    <input type="hidden" value="<?php if($documentos_actor['3']){ echo $documentos_actor['3']->id; } ?>" name="id_pensiones">
                  <?php endif ?>
                  <input type="text"  name="pensiones_nombre<?php echo Casting::valida_documento($documentos_actor['3']);?>" id="pensiones_nombre" value="<?php if($documentos_actor['3']){ echo $documentos_actor['3']->descripcion; } ?>">
                </div>
                <div class="column two">
                  <label for="">Activo:</label>
                  <select name="pensiones_activo<?php echo Casting::valida_documento($documentos_actor['3']);?>" id="pensiones_activo">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($estados_entidad as $estado_entidad): ?>
                      <option value="<?=$estado_entidad->id?>"
                        <?php if ($documentos_actor['3'] AND $documentos_actor['3']->id_estado_entidad == $estado_entidad->id): ?>
                          selected 
                        <?php endif ?>
                      ><?=$estado_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for="">Clase:</label>
                  <select name="pensiones_clase<?php echo Casting::valida_documento($documentos_actor['3']);?>" id="pensiones_clase">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($clases_entidad as $clase_entidad): ?>
                      <option value="<?=$clase_entidad->id?>"
                      <?php if ($documentos_actor['3'] AND $documentos_actor['3']->id_clase_entidad == $clase_entidad->id): ?>
                          selected 
                      <?php endif ?>
                      ><?=$clase_entidad->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <?php  
                  $label=lang('global.adjuntar');
                  if ($documentos_actor['3'] AND $documentos_actor['3']->url){
                    $label=lang('global.cambiar');
                  }
                ?>
                <div class="column three">
                  <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="pensiones_documento"><?=$label?> ARCHIVO</span>
                      <span class="label-file" data-input="pensiones_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                  </div>
                <div class="columns one">
                  <?php if ($label==lang('global.cambiar')): ?>
                    <a href="<?=base_url($documentos_actor['3']->url)?>" target="_blank" class="button alignTop alignLeft twelve">VER</a>
                  <?php else: ?>
                    <button type="button" class="agregar_documento button alignTop twelve">AGREGAR</button>
                  <?php endif ?>
                </div>
                <?php if ($documentos_actor['3']->descripcion OR 
                          isset($documentos_actor['3']->id_clase_entidad) OR 
                          isset($documentos_actor['3']->id_estado_entidad) OR 
                          $documentos_actor['3']->url): ?>    
                  <div class="columns one">
                      <button type="button" class="eliminar_documento_cargado btn_orange button alignTop alignLeft twelve">ELIMINAR</button>
                  </div>
                <?php endif ?>
              </div>-->


              <!-- MIS PASAPORTES-->
              <?php if ($pasaportes_actor): ?>
                <?php foreach ($pasaportes_actor as $pasaporte_actor): ?>
                  <div class="columns twelve" id="document_pasaporte" <?php if($pasaporte_actor->descripcion OR $pasaporte_actor->url OR $pasaporte_actor->pais){ }else{?> style="display:none" <?php } ?>>
                    <div class="column three">
                      <label><?php echo lang('global.pasaporte') ?></label>
                      <input type="hidden" value="<?php if($pasaporte_actor){ echo $pasaporte_actor->id; }?>" name="id_pasaporte_original">
                      <input type="text" name="pasaporte_numero_original" value="<?php if($pasaporte_actor){ echo $pasaporte_actor->descripcion; } ?>">
                    </div>
                    <div class="column two">
                      <label><?php echo lang('global.pais') ?></label>
                      <?php if($paises){ ?>
                        <select name="pasaporte_pais_original">
                          <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                          <?php foreach ($paises as $pais) { ?>
                          <option value="<?=$pais->id?>"
                          <?php if($pasaporte_actor AND $pasaporte_actor->pais == $pais->id){ 
                            echo " selected ";
                          }?>
                          ><?=$pais->nombre?></option>
                          <?php } ?>
                        </select>
                      <?php } ?>
                    </div>
                    
                    <?php  
                      $label=lang('global.adjuntar');
                      if ($pasaporte_actor AND $pasaporte_actor->url){
                        $label=lang('global.cambiar');
                      }
                    ?>
                    <div class="column three">
                      <label for="">&nbsp;</label>
                        <div class="content-input-file inline">
                          <span class="button-file" data-input="pasaporte_documento_<?=$pasaporte_actor->id?>"><?=$label?> <?php echo lang('global.archivo') ?></span>
                          <input type="file" name="pasaporte_documento_<?=$pasaporte_actor->id?>" id="pasaporte_documento_<?=$pasaporte_actor->id?>"  class="with-label" style="display:none">
                          <span class="label-file" data-input="asaporte_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                          <input type="hidden" name="pasaporte_actor_original_<?=$pasaporte_actor->id?>" value="<?=$pasaporte_actor->url?>">
                          
                        </div>
                    </div>
                    <div class="columns one">
                      <?php if ($label==lang('global.cambiar')): ?>
                        <a  class="button alignTop alignLeft twelve" href="<?=base_url($pasaporte_actor->url)?>" target="_blank"><?php echo lang('global.ver') ?></a>
                      <?php endif ?>
                    </div>
                    <!-- ELIMINAR DOCUMENTO -->
                    <?php if ($pasaporte_actor->url OR 
                          isset($pasaporte_actor->descripcion) OR isset($pasaporte_actor->pais)): ?>    
                           <button type="button"  data-id="<?php echo $pasaporte_actor->id; ?>" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                            <input type="hidden" name="documentos_eliminar[]" value="" classs="documentos_eliminar_<?php echo $pasaporte_actor->id; ?>">
                    <?php endif ?>
                    <!-- FIN ELIMINAR DOCUMENTO -->
                  </div>    
                <?php endforeach ?>       
              <?php endif ?>
              <div class="columns twelve" id="document_5" style="display:none">
                <div class="column three">
                  <label><?php echo lang('global.pasaporte') ?></label>
                  <input type="hidden" value="" name="id_visa">
                  <input type="text" name="pasaporte_numero" value="">
                </div>
                <div class="column two">
                  <label><?php echo lang('global.pais') ?></label>
                  <select name="pasaporte_pais">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($paises_pasaporte as $pais) { ?>
                      <option value="<?=$pais->id?>"><?=$pais->nombre?></option>
                    <?php } ?>
                  </select>
                </div>
                
                <div class="column three">
                    <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="pasaporte_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <input type="file" name="pasaporte_documento"  class="with-label" style="display:none">
                      <span class="label-file" data-input="pasaporte_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
               
                <div class="columns one">
                          <button type="button" data-tipo="5" class="eliminar_documento_cargado btn_orange button alignTop alignLeft twelve"><?php echo lang('global.eliminar') ?></button>
                </div>
              </div>  


              <!-- VISAS -->
              <?php if ($visas_actor): ?>
                <?php foreach ($visas_actor as $visa_actor): ?>
                  <div class="columns twelve" id="document_visa" <?php if($visa_actor->descripcion OR $visa_actor->url OR $visa_actor->pais){ }else{?> style="display:none" <?php } ?>>
                    <div class="column two">
                      <label><?php echo lang('global.pais') ?></label>
                      <?php if($paises){ ?>
                        <select name="visa_pais_original">
                          <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                          <?php foreach ($paises as $pais) { ?>
                          <option value="<?=$pais->id?>"
                          <?php if($visa_actor AND $visa_actor->pais == $pais->id){ 
                            echo " selected ";
                          }?>
                          ><?=$pais->nombre?></option>
                          <?php } ?>
                        </select>
                      <?php } ?>
                    </div>
                    
                    <div class="column three">
                      <label>Visa</label>
                      <input type="hidden" value="<?php if($visa_actor){ echo $visa_actor->id; }?>" name="id_visa_original">
                      <input type="text" name="visa_numero_original" value="<?php if($visa_actor){ echo $visa_actor->descripcion; } ?>">
                    </div>
                    

                    <div class="column two">
                      <label><?php echo lang('global.vigencia') ?></label>
                      <input type="text" name="visa_vigencia_original" class="visa_vigencia" value="<?php if($visa_actor->vigencia AND $visa_actor->vigencia!='0000-00-00'){ echo date('d-M-Y',strtotime($visa_actor->vigencia)); } ?>">
                    </div>
                    
                    <?php  
                      $label=lang('global.adjuntar');
                      if ($visa_actor AND $visa_actor->url){
                        $label=lang('global.cambiar');
                      }
                    ?>
                    <div class="column three">
                      <label for="">&nbsp;</label>
                        <div class="content-input-file inline">
                          <span class="button-file" data-input="visa_documento"><?=$label?> <?php echo lang('global.archivo') ?></span>
                          <input type="file" name="visa_documento_<?=$visa_actor->id?>"  class="with-label" style="display:none">
                          <span class="label-file" data-input="visa_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                          <input type="hidden" name="visa_documento_original_<?=$visa_actor->id?>" value="<?=$visa_actor->url?>">
                          
                        </div>
                    </div>
                    <div class="columns one">
                      <?php if ($label==lang('global.cambiar')): ?>
                        <a  class="button alignTop alignLeft twelve" href="<?=base_url($visa_actor->url)?>" target="_blank"><?php echo lang('global.ver') ?></a>
                      <?php endif ?>
                    </div>
                    <!-- ELIMINAR DOCUMENTO -->
                    <?php if ($visa_actor->url OR 
                          isset($visa_actor->descripcion) OR isset($visa_actor->pais)): ?>    
                      <div class="columns one">
                          <button type="button" data-id="<?php echo $visa_actor->id ?>" class="eliminar_documento_cargado btn_orange button alignTop alignLeft twelve"><?php echo lang('global.eliminar') ?></button>
                          <input type="hidden" name="documentos_eliminar[]" value="" class="documentos_eliminar_<?php echo $visa_actor->id; ?>">
                      </div>
                    <?php endif ?>
                    <!-- FIN ELIMINAR DOCUMENTO -->
                  </div>    
                <?php endforeach ?>       
              <?php endif ?>
              <div class="columns twelve" id="document_6" style="display:none">
                
                <div class="column two">
                  <label><?php echo lang('global.pais') ?></label>
                  <select name="visa_pais">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($paises_visa as $pais) { ?>
                      <option value="<?=$pais->id?>"><?=$pais->nombre?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="column three">
                  <label>Visa</label>
                  <input type="hidden" value="" name="id_visa">
                  <input type="text" name="visa_numer" value="">
                </div>

                <div class="column two">
                  <label><?php echo lang('global.vigencia') ?></label>
                  <input type="text" name="visa_vigencia" class="visa_vigencia">
                </div>
                
                <div class="column three">
                    <label for="">&nbsp;</label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="visa_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                      <input type="file" name="visa_documento"  class="with-label" style="display:none">
                      <span class="label-file" data-input="visa_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                
                <div class="columns one">
                          <button type="button" data-tipo="6" class="eliminar_documento_cargado btn_orange button alignTop alignLeft twelve"><?php echo lang('global.eliminar') ?></button>
                      </div>
              </div>  

              <!-- OTROS DOCUMENTOS-->
              <?php if ($otros_documentos_actor): ?>
                <?php foreach ($otros_documentos_actor as $otro_documento_actor): ?>
                  <div class="columns twelve" id="document_visas"> 
                    <div class="column <?php if(isset($otro_documento_actor->url)){ echo 'six'; }else{echo 'four';} ?>">
                      <label>Otro</label>
                      <input type="hidden" value="<?php if(isset($otro_documento_actor->id)){ echo $otro_documento_actor->id; }?>" name="id_otro_original">
                      <input type="text" name="otro_numero_original" value="<?php if(isset($otro_documento_actor)){  echo $otro_documento_actor->descripcion; } ?>">
                    </div>
                    
                    <?php  
                      $label=lang('global.adjuntar');
                      if ($otro_documento_actor AND $otro_documento_actor->url){
                        $label=lang('global.cambiar'); 
                      }
                    ?>
                    <div class="column three">
                       <label for="">&nbsp;</label>
                        <div class="content-input-file inline">
                          <span class="button-file" data-input="otro_documento"><?=$label?> <?php echo lang('global.archivo') ?></span>
                          <input type="file" name="otro_documento_<?=$otro_documento_actor->id?>"  class="with-label" style="display:none">
                          <span class="label-file" data-input="otro_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                          <input type="hidden" name="otro_documento_original_<?=$otro_documento_actor->id?>" value="<?=$otro_documento_actor->url?>">
                        </div>
                    </div>
                    <div class="columns one">
                      <?php if ($label==lang('global.cambiar')): ?>
                        <a  class="button  alignTop twelve" href="<?=base_url($otro_documento_actor->url)?>" target="_blank"><?php echo lang('global.ver') ?></a>
                      <?php endif ?>
                    </div>
                    <!-- ELIMINAR DOCUMENTO -->
                    <div class="columns one">
                      <?php if ($label==lang('global.cambiar')): ?>
                        <button type="button alignTop" data-id="<?php echo $otro_documento_actor->id ?>" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                        <input type="hidden" name="documentos_eliminar[]" value="" class="documentos_eliminar_<?php echo $otro_documento_actor->id; ?>">
                      <?php endif ?>
                    </div>
                    <!-- FIN ELIMINAR DOCUMENTO -->
                  </div>
                <?php endforeach ?>
              <?php endif ?>
                <div class="columns twelve" id="document_7" style="display:none">
                  <div class="column four">
                    <label>Otro</label>
                    <input type="text" name="otro_numero" value="">
                  </div>
                  
                 
                  <div class="column three">
                     <label for="">&nbsp;</label>
                      <div class="content-input-file inline">
                        <span class="button-file" data-input="otro_documento"><?php echo lang('global.adjuntar_archuivo') ?></span>
                        <input type="file" name="otro_documento" id="otro_documento" class="with-label" style="display:none">
                        <span class="label-file" data-input="otro_documento"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                      </div>
                  </div>
                  
                  <div class="columns one">
                          <button type="button" data-tipo="6" class="eliminar_documento_cargado btn_orange button alignTop alignLeft twelve"><?php echo lang('global.eliminar') ?></button>
                      </div>
                </div>

       


               <!-- regisotr IDENTIDAD -->
              <?php $label = lang('global.adjuntar'); ?>
              <div class="columns twelve" id="document_8" <?php if(isset($documentos_actor['5']->url)){ }else{?> style="display:none" <?php } ?> >
                <input name="id_documento" type="hidden" value="<?php if(isset($documentos_actor['5'])){ echo $documentos_actor['5']->id;} ?> " >
                <div class="column <?php if(isset($documentos_actor['5']->url)){ echo 'six'; }else{echo 'two';} ?>">
                  <label for="">&nbsp;</label>
                  <input type="text" disabled value="<?php echo lang('global.documentos') ?>" <?php if(isset($documentos_actor['5']->url)){ $label = lang('global.cambiar'); }else{?> style="display:none" <?php } ?>>
                </div>
                <div class="column three" style="margin-bottom:10px"> 
                  <label for=""><?php echo lang('global.rcivil') ?></label>
                    <div class="content-input-file inline">
                      <span class="button-file" data-input="registro_civil"><?=$label?> <?php echo lang('global.archivo') ?></span>
                      <span class="label-file" data-input="registro_civil"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                    </div>
                </div>
                <div class="columns one">
                  <?php if ($label==lang('global.cambiar')): ?>
                    <a href="<?=base_url($documentos_actor['5']->url)?>" target="_blank" class="button alignTop twelve"><?php echo lang('global.ver') ?></a>
                    
                  <?php endif ?>
                </div>
                <?php if (isset($documentos_actor['5']->url)): ?>    
                  <div class="columns one">
                  <?php if(isset($documentos_actor['5']->id)){ ?>
                      <button type="button" data-tipo="8" data-id="<?php echo $documentos_actor['5']->id; ?>" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                  <?php }else{ ?>    
                      <button type="button" data-tipo="8" class="eliminar_documento_cargado btn_orange button alignTop twelve"><?php echo lang('global.eliminar') ?></button>
                  <?php } ?>
                  <input type="hidden" name="documentos_eliminar[]" value="" class="documentos_eliminar_<?php echo $documentos_actor['5']->id; ?>">
                  </div>
                <?php endif ?>
            </div>

              <!-- INPUTS FILES-->
              <input type="file" name="pasaporte"  id="pasaporte" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="pensiones_documento" id="pensiones_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="eps_documento" id="eps_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="documento_identidad" id="documento_identidad"  class="with-label" style="display:none">
              <input type="file" name="arl_documento" id="arl_documento" class=" contrato_firmado with-label" style="display:none">
              <input type="file" name="registro_civil" id="registro_civil" class=" contrato_firmado with-label" style="display:none">
             <div class="clr"></div> 
              

            </div>

           
          </div>
        
        </div>



          <div class="clr6"></div>

          <div class="row" style="padding:0px 5px;">
            <div class="column six">
               <a href="<?=base_url($this->lang->lang().'/'.'casting/detalle_actor/'.$actor[0]->id)?>" class="button btn_orange h_40 no-padding-right" style="margin:0;"><?php echo lang('global.cancelar') ?></a>
             </div> 
            <div class="column six">
              <button type="button" class="button twelve h_40 " id="actorInsert"><?php echo lang('global.guardar') ?></button>
            </div>
          </div>
    <?php echo form_close(); ?>  
  <?php endif ?>
</div>