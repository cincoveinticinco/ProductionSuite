<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.crear_solicitud') ?>
</div>

<nav>
    <ul class="nav_post nav_casting">
        <li><a href="#" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_solicitud') ?></a></li>
        <li><a href="<?=base_url($idioma.'casting/solicitudes')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.ver_solocitudes') ?></a></li>
    </ul>
</nav>

<div id="inner_content">
  <div class="columns twelve">
    <?php echo form_open_multipart($idioma.'casting/insert_solicitud','id="crear_solicitud", class="", onSubmit=""');?>
      <div class="column title_section"><h5><?php echo lang('global.informacion_personaje') ?></h5></div>
      <div class="info with_title">
        <div class="row">
          <div class="column four">
            <label for=""><?php echo lang('global.persona_que_solicita_el_contrato') ?>:</label>
            <input type="text"   disabled value="<?=$this->session->userdata('nombre_pruduction_suite').' '.$this->session->userdata('apellido_pruduction_suite')?>">
          </div>
           <div class="column four">
              <label for=""><?php echo lang('global.area_solicitante') ?>:</label>
              <select name="area_solicitante"  class="required">
                <?php foreach ($areas_solicitantes as $area_solicitante): ?>
                      <option value="<?=$area_solicitante->id?>"><?=$area_solicitante->descripcion?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="column four ">
              <label for=""><?php echo lang('global.produccion') ?>:</label>
              <select name="produccion" id="production_selector" class="required">
                <option value="0"><?php echo lang('global.seleccion_opcion') ?></option>
                <?php foreach ($producciones as $produccion): ?>
                  <option value="<?=$produccion['id_produccion']?>"
                    <?php if ($produccion['id_produccion']==$idproduccion): ?>
                      selected
                    <?php endif ?>
                    ><?=$produccion['nombre_produccion']?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="clr6"></div>
             <!-- PERSONAJE-->

            <div id="initial_personaje" style="display:none;">
              <div class="column three">
                <label for=""><?php echo lang('global.rol') ?>:</label>
                <select name-temp="rol[]" class-temp="required new_rol">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                  <?php foreach ($roles as $rol): ?>
                    <?php if ($rol['id']!=4): ?>
                      <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                    <?php endif ?>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="column three">
                <label for=""><?php echo lang('global.personajes') ?>:</label>
                <select name-temp="id_elemento[]" class-temp="required new_personaje">
                  <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                </select>
              </div>
              <div class="column four alignLeft">
               <label for=""><?php echo lang('global.apariciones') ?>:</label>
               <input disabled type="text" class="num_libretos" class="">
              </div>
              <a href="#" class="eliminar_personaje" ><img src="<?php echo base_url('images/delete_esc.png')?>" alt="" style="margin-top: 15px;"></a>
            </div>
          
             <div class="column three">
              <label for=""><?php echo lang('global.rol') ?>:</label>
              <select name="rol[]" id="rol_selector" class="required">
                <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                <?php foreach ($roles as $rol): ?>
                  <?php if ($rol['id']!=4): ?>
                    <option value="<?=$rol['id']?>"
                      <?php if ($elemento AND $elemento[0]->rol == $rol['rol']): ?>
                        selected
                      <?php endif ?>
                      ><?=$rol['rol']?></option>
                  <?php endif ?>
                <?php endforeach ?>
              </select>
            </div>

            <div class="column three alignLeft">
              <!-- CAMPO OCULTO ID ELEMENTO -->
              <input type="hidden" value="<?=$idelemento?>" name="id_elemento_hidden" id="id_elemento_hidden">
              <label for=""><?php echo lang('global.personajes') ?>:</label>
              <select id="personaje_selector" name="id_elemento[]" class="required">
                <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              </select>
              <input type="hidden" id="id_elemento_hidden" value="<?=$idelemento?>">
            </div>
            <div class="column four alignLeft">
               <label for=""><?php echo lang('global.apariciones') ?>:</label>
               <input disabled type="text" class="num_libretos" class="">
            </div>
            <div class="column two">
              <a href="#" id="add_personaje" style="margin-top: 16px;"><img src="<?php echo base_url('images/plus.png')?>"></a>
            </div>
            <div class="clr6"></div>
            <div id="personajes_section"></div>
            <div class="clr6"></div>

        </div>
      </div>
      <div class="column title_section"><h5><?php echo lang('global.informacion_del_actor') ?></h5></div>
      <div class="info with_title">
        <div class="row">
          <div class="column three">
            <label for=""><?php echo lang('global.actor') ?>:</label>
            <select name="actor" class="select_actor required">
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php foreach ($actores as $actor): ?>

                <option value="<?=$actor->id?>"  
                  <?php if ($actor->id == $idactor): ?>
                      selected
                  <?php endif ?>
                  ><?=$actor->nombre.' '.$actor->apellido?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="column three">
            <label for=""><?php echo lang('global.lugar_de_presentacion_del_servicio') ?>:</label>
            <select name="lugar_sevicio"  class="required">
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php foreach ($lugares_servicio as $lugar_servicio): ?>
                <option value="<?=$lugar_servicio->id?>"><?=$lugar_servicio->descripcion?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="column three">
            <label for=""><?php echo lang('global.objeto_del_contrato') ?>:</label>
            <select name="objeto_contrato">
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php foreach ($objetos_contrato as $objeto_contrato): ?>
                <option value="<?=$objeto_contrato->id?>"><?=$objeto_contrato->descripcion?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="column three">
            <label for=""> <?php echo lang('global.tipo_de_pago') ?>:</label>
            <select name="forma_pago" class="required">
              <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                <?php foreach ($formas_pago as $forma_pago): ?>
                  <?php if ($forma_pago->id!=4): ?>
                    <option value="<?=$forma_pago->id?>"
                      <?php if ($forma_pago->id==3): ?>
                         class="clausula"
                      <?php endif ?>
                    ><?=$forma_pago->descripcion?></option>
                  <?php endif ?>
                <?php endforeach ?>
                <option value="4"><?php echo lang('global.otros') ?></option>
            </select>
          </div>
          <div class="clr6"></div>
          <!-- SECCION DATOS ACTOR -->
          <div class="datos_actor " style="display:none">
            <h6><?php echo lang('global.datos_actor') ?></h6>
            <div class="column two"><label for=""><?php echo lang('global.nacionalidad') ?></label><input type="text" disabled class="normal" id="nacionalidad_hidden"></div>
            <div class="column two"><label for="">Identificación</label><input disabled type="text" id="identificacion_hidden"></div>
            <div class="column two"><label for=""><?php echo lang('global.direccion') ?></label><input disabled  type="text"  id="direccion_hidden"></div>
            <div class="column two"><label for=""><?php echo lang('global.ciudad') ?></label><input disabled  type="text" id="ciudad_hidden"></div>
            <div class="column two"><label for=""><?php echo lang('global.pais') ?></label><input disabled  type="text" id="pais_hidden"></div>
            <div class="clr6"></div>
            <div class="column two"><label for=""><?php echo lang('global.telefono_fijo') ?></label><input  type="text" disabled id="telefono_fijo_hidden" ></div>
            <div class="column two"><label for=""><?php echo lang('global.telefono_movil') ?></label><input  type="text" disabled id="telefono_movil_hidden"></div>
            <div class="clr6"></div>
            <!-- SECCION DATOS SOCIEDAD -->
            <div class="datos_sociedad" style="display:none">
              <div class="clr6"></div>
              <h6><?php echo lang('global.datos_sociedad') ?></h6>
              <div class="column two"><label for="">nit</label><input type="text" disabled id="nit_hidden"></div>
              <div class="column two"><label for=""><?php echo lang('global.razon_social') ?></label><input type="text" disabled id="razon_hidden"></div>
              <div class="column two"><label for=""><?php echo lang('global.telefono_fijo') ?></label><input type="text" disabled id="telefono_fijo_sociedad_hidden"></div>
              <div class="column two"><label for=""><?php echo lang('global.telefono_fijo') ?></label><input type="text" disabled id="telefono_movil_sociedad_hidden"></div>
              <div class="column two"><label for=""><?php echo lang('global.direccion') ?></label><input type="text" disabled id="direccion_sociedad_hidden"></div>
              <div class="clr"></div>
            </div>
            <!-- FIN SECCION DATOS SOCIEDAD -->
          </div>
          <div class="clr6"></div>
          <!-- FIN SECCION DATOS ACTOR -->
        </div>
        <div class="row">
          <div class="column two">
            <label for=""><?php echo lang('global.inicio_de_contrato') ?>:</label>
            <input type="text" name="fecha_inicio" id="fecha_inicio" class="required fecha_inicio_casting">
          </div>
          <div class="column two">
            <label for=""><?php echo lang('global.terminacion_de_contrato') ?>:</label>
            <input type="text" name="fecha_final" id="fecha_final" class="required fecha_final_casting">
          </div>
          <div class="column two">
            <label for=""><?php echo lang('global.tipo_moneda') ?>:</label>
            <?php if ($tipos_moneda): ?>
              <select name="tipo_moneda" id="tipo_moneda" class="required">
                <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
              <?php foreach ($tipos_moneda as $tipo_moneda): ?>
                <option value="<?=$tipo_moneda->id?>"><?=$tipo_moneda->descripcion?></option>
              <?php endforeach ?>
              </select>
            <?php endif ?>
          </div>
           <div class="column two">
            <label for=""><?php echo lang('global.honorarios') ?>:</label>
            <input type="text"   class="required" name="honorarios" id="honorarios">
          </div>
           <div class="column four alignLeft">
            <label for=""><?php echo lang('global.honorarios_en_letras') ?>:</label>
            <input type="text"   class="required" name="honorarios_letras" >
          </div>
        </div>
        <div class="clr6"></div>
        <div class="row">
          <div class="column three" style="width:35%;">
            <label><?php echo lang('global.casos_especiales') ?></label>
            <div class="clr3"></div>
            <label class="label_check" style="display:inline-block"><input type="checkbox" id="special_contidions" name="valida_condiciones"><?php echo lang('global.tiene_condiciones_especiales') ?></label>
            <span>&nbsp;</span>
            <label class="label_check" style="display:none;"><input type="checkbox" id="sugestions_contratation" name="valida_sugerencias"><?php echo lang('global.sugerencia_de_contratacion') ?></label>
          </div>
          <div class="clr6"></div>
          <div class="column twelve" style="padding:0;">
            <div class="column twelve" id="conditions_field" style="display:none">
              <label for=""><?php echo lang('global.condiciones_especiales') ?>:</label>
              <textarea style="min-height:80px;" name="condiciones_especiales"></textarea>
            </div>
            <div class="column six" id="sugestions_field" style="display:none">
              <label for=""><?php echo lang('global.sugerencia_de_contratacion') ?>:</label>
              <textarea style="min-height:80px;" name="sugerencias_contratacion"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div id="documentos_section" >
        <div class="column title_section"><h5><?php echo lang('global.documentos') ?></h5></div>
        <div class="info data-solicitud with_title" id="row_documents">
          
          <div class="row" id="load_documents_solicitud" style="display:none;">

          </div>
          
          <div class="columns twelve clone_row">
            <!-- SECCION BASE PARA AGREGAR DOCUMENTOS -->
            <div class="load_content_base" style="display:none;">
              <div class="column three">
                <label><?php echo lang('global.documento') ?></label>
                <input type="text" name="documento_numero_base[]" value="">
              </div>
              
              <div class="column three">
                 <label for="">&nbsp;</label>
                  <div class="content-input-file inline">
                    <span class="button-file" data-input="documento_solicitud_indice"><?php echo lang('global.adjuntar_archuivo') ?></span>
                    <input type="file" name="documento_solicitud_base[]" id="documento_solicitud_indice" class="with-label" style="display:none">
                    <span class="label-file" data-input="documento_solicitud_indice"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                  </div>
              </div>
              <div class="columns two">
                <button type="button" class="agregar_documento_solicitud button alignTop twelve"><?php echo lang('global.agregar') ?></button>
              </div>
              <div class="clr6"></div>
            </div>
            <!-- FIN SECCION BASE PARA AGREGAR DOCUMENTOS -->

            <div class="load_content">
              <div class="column six">
                <label><?php echo lang('global.documento') ?></label>
                <input type="text" name="documento_numero[]" value="">
              </div>
              
              <div class="column three">
                 <label for="">&nbsp;</label>
                  <div class="content-input-file inline">
                    <span class="button-file" data-input="documento_solicitud"><?php echo lang('global.adjuntar_archuivo') ?></span>
                    <input type="file" name="documento_solicitud[]" id="documento_solicitud" class="with-label" style="display:none">
                    <span class="label-file" data-input="documento_solicitud"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                  </div>
              </div>
              <div class="columns one alignLeft">
                <a href="#" class="button alignTop twelve agregar_documento_solicitud " style="width:100%"><?php echo lang('global.agregar') ?></a>
              </div>
              <div class="clr6"></div>
            </div>
            
            

          </div>
          <div class="clr"></div>
        </div>

      </div>
      <div class="clr6"></div>
      <div class="column title_section"><h5><?php echo lang('global.notas_de_la_solicitud') ?></h5></div>
      <div class="info data-solicitud with_title">
        <div class="row">
          <div class="column twelve">
            <label for=""><?php echo lang('global.anotaciones') ?>:</label>
            <textarea  maxlength="100" cols="30" rows="10" name="notas_solicitud" id="notas_solicitud"></textarea>
          </div>
        </div>
      </div>
      <div class="clr6"></div>
      <div class="row">
        <div class="column twelve" style="padding: 0 6px;">
         <div class="columns four no-padding">
            <button type="button" onclick ="javascript:history.go(-1)" class="button btn_orange column twelve h_40"><?php echo lang('global.cancelar') ?></button>
          </div>
          <div class="columns four">
            <button class="button column twelve h_40" id="guardar_solicitud"><?php echo lang('global.guardar') ?> <?php echo lang('global.solicitud') ?></button>
          </div>
          <div class="columns four no-padding">
            <button class="button column twelve h_40" id="generar_solicitud"><?php echo lang('global.generar') ?> <?php echo lang('global.solicitud') ?></button>
          </div>
        </div>
      </div>
      <div class="clr6"></div>
      <!-- CAMPO OCULTO VALIDACION DE COMPETAR FORMULARIO -->
      <input type="hidden" value="" name="valida_completo" id="valida_completo">
    <?php echo form_close(); ?>
  </div>
</div>