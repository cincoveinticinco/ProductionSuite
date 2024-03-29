<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css">
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <a href="<?=base_url($idioma.'casting')?>"><?php echo lang('global.casting') ?></a>  / <?php echo lang('global.agregar') ?> otro si
</div>

<nav>
    <ul class="nav_post nav_casting">
        <li><a href="#" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_solicitud') ?></a></li>
        <li><a href="<?=base_url($idioma.'casting/solicitudes')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.ver_solocitudes') ?></a></li>
    </ul>
</nav>

<div id="inner_content">
    <div class="columns twelve">
      <?php echo form_open_multipart($idioma.'casting/insert_solicitud_otro_si','id="crear_solicitud", class="", onSubmit=""');?>
        <input type="hidden" name="id_solicitud_anexa" value="<?=$solicitud[0]->id_solicitud?>">
        <div class="column title_section"><h5><?php echo lang('global.informacion_personaje') ?></h5></div>
        <div class="info with_title">
          <div class="row">
            <div class="column four">
              <label for=""><?php echo lang('global.persona_que_solicita_el_contrato') ?>:</label>
              <input type="text"   disabled value="<?=$this->session->userdata('nombre_pruduction_suite').' '.$this->session->userdata('apellido_pruduction_suite')?>">
            </div>
             <div class="column four">
                <label for=""><?php echo lang('global.area_solicitante') ?>:</label>
                <input type="text" disabled value="<?php echo lang('global.produccion') ?>">  
              </div>
              <div class="column four ">
                <label for=""><?php echo lang('global.produccion') ?>:</label>
                <input type="text" value="<?=$solicitud[0]->produccion?>" disabled="">
              </div>
              <div class="clr6"></div>
               <!-- PERSONAJE-->
            
               <div class="column three">
                <label for=""><?php echo lang('global.rol') ?>:</label>
                <input type="text" value="<?php if ($elementos_solicitud): ?><?php foreach ($elementos_solicitud as $elemento_solicitud): ?><?=$elemento_solicitud->rol?>
                    <?php endforeach ?>
                  <?php endif ?>" disabled="">
              </div>

              <div class="column three alignLeft">
                <label for=""><?php echo lang('global.personajes') ?>:</label>
                <input type="text" value="<?=$solicitud[0]->elementos?>" disabled="">
              </div>

              <div class="column three alignLeft">
                 <label for=""><?php echo lang('global.apariciones') ?>:</label>
                 <?php 
                 $valor = "";
                 if($solicitud[0]->libretos){
                  $libretos = count(explode(',', $solicitud[0]->libretos));
                  $valor = $libretos.' (DESGLOSADOS: '.$solicitud[0]->libretos.')';
                 }

                 ?>
                 <input disabled type="text" class="num_libretos" class="" value="<?=$valor ?>">
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
              <input type="text" value="<?=$solicitud[0]->actor?>" disabled="">
              </div>
              <div class="column three">
                <label for=""><?php echo lang('global.lugar_de_presentacion_del_servicio') ?>:</label>
                <input type="text" disabled value="<?=$solicitud[0]->lugar_servicio?>">  
              </div>
               <div class="column three">
                <label for=""><?php echo lang('global.objeto_del_contrato') ?>:</label>
                <input type="text" disabled value="<?=$solicitud[0]->objeto_contrato?>">  
              </div>
               <div class="column three">
                    <label for=""> <?php echo lang('global.tipo_de_pago') ?>:</label>

                    <select name="forma_pago" id="forma_pago" class="required old_field" disabled>
                        <?php foreach ($formas_pago as $forma_pago): ?>
                          <?php if ($forma_pago->id!=4): ?>
                            <option value="<?=$forma_pago->id?>"
                              <?php if ($solicitud[0]->id_forma == $forma_pago->id): ?>
                                selected 
                              <?php endif ?>
                            ><?=$forma_pago->descripcion?></option>
                          <?php endif ?>
                        <?php endforeach ?>
                        <option value="4">Otro
                            <?php if ($solicitud[0]->id_forma==4): ?>
                              (DÍA)
                            <?php endif ?>
                        </option>
                    </select>


                    <!--select data-id="forma_pago" class="required old_field copyValue" style="display:none; background: #FAFACA;">
                      <option value="">Seleccione una opción</option>
                        <?php foreach ($formas_pago as $forma_pago): ?>
                          <?php if ($forma_pago->id!=4): ?>
                            <option value="<?=$forma_pago->id?>"
                              <?php if ($solicitud[0]->id_forma == $forma_pago->id): ?>
                                selected 
                              <?php endif ?>
                            ><?=$forma_pago->descripcion?></option>
                          <?php endif ?>
                        <?php endforeach ?>
                        <option value="4">Otro
                            <?php if ($solicitud[0]->id_forma==4): ?>
                              (DÍA)
                            <?php endif ?>
                        </option>
                    </select-->

                  </div>
              <div class="clr6"></div>
              <!-- SECCION DATOS ACTOR -->
              <div class="datos_actor">
                <h6><?php echo lang('global.datos_actor') ?></h6>
                <div class="column two"><label for=""><?php echo lang('global.nacionalidad') ?>:</label><input type="text" disabled class="normal" id="nacionalidad_hidden" value="<?=$solicitud[0]->nacionalidad?>"></div>
                <div class="column two"><label for=""><?php echo lang('global.numero_documento') ?>:</label><input disabled type="text" id="identificacion_hidden" value="<?=$solicitud[0]->documento?>"></div>
                <div class="column two"><label for=""><?php echo lang('global.direcion') ?>:</label><input disabled  type="text"  id="direccion_hidden" value="<?=$solicitud[0]->direccion?>"></div>
                <div class="column two"><label for=""><?php echo lang('global.ciudad') ?>:</label><input disabled  type="text" id="ciudad_hidden" value="<?=$solicitud[0]->ciudad?>"></div>
                <div class="column two"><label for=""><?php echo lang('global.pais') ?>:</label><input disabled  type="text" id="pais_hidden" value="<?=$solicitud[0]->pais?>"></div>
                <div class="clr6"></div>
                <div class="column two"><label for=""><?php echo lang('global.telefono_fijo') ?>:</label><input  type="text" disabled id="telefono_fijo_hidden"  value="<?=$solicitud[0]->telefono_fijo?>"></div>
                <div class="column two"><label for=""><?php echo lang('global.telefono_movil') ?>:</label><input  type="text" disabled id="telefono_movil_hidden" value="<?=$solicitud[0]->telefono_movil?>"></div>
                <div class="clr6"></div>
                <!-- SECCION DATOS SOCIEDAD -->
                <div class="datos_sociedad" 
                <?php if (!$solicitud[0]->nit_sociedad): ?>        
                  style="display:none">  
                <?php endif ?>
                > 
                  <div class="clr6"></div>
                  <h6><?php echo lang('global.datos_sociedad') ?></h6>
                  <div class="column two"><label for="">nit:</label><input type="text" disabled id="nit_hidden" value="<?=$solicitud[0]->nit_sociedad?>"></div>
                  <div class="column two"><label for=""><?php echo lang('global.razon_social') ?>:</label><input type="text" disabled id="razon_hidden" value="<?=$solicitud[0]->razon_social_sociedad?>"></div>
                  <div class="column two"><label for=""><?php echo lang('global.telefono_fijo') ?>:</label><input type="text" disabled id="telefono_fijo_sociedad_hidden" value="<?=$solicitud[0]->telefono_fijo_sociedad?>"></div>
                  <div class="column two"><label for=""><?php echo lang('global.telefono_movil') ?>:</label><input type="text" disabled id="telefono_movil_sociedad_hidden" value="<?=$solicitud[0]->telefono_movil_sociedad?>"></div>
                  <div class="column two"><label for=""><?php echo lang('global.direccion') ?>:</label><input type="text" disabled id="direccion_sociedad_hidden" value="<?=$solicitud[0]->direccion_sociedad?>"></div>
                  <div class="clr"></div>
                </div>
                <!-- FIN SECCION DATOS SOCIEDAD -->
              </div>
              <div class="clr6"></div>
              <!-- FIN SECCION DATOS ACTOR -->
             </div>
              <div class="row datos_actor">
                 
                  <div class="column two">
                    <label for=""><?php echo lang('global.inicio_de_contrato') ?>:</label>
                    <input type="text" name="fecha_inicio" id="fecha_inicio" class="required fecha_inicio_casting old_field " disabled value="<?=date("d-M-Y", strtotime($solicitud[0]->fecha_inicio))?>">
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.terminacion_de_contrato') ?>:</label>
                    <input type="text" name="fecha_final"  disabled class="required old_field" value="<?=date("d-M-Y", strtotime($solicitud[0]->fecha_final))?>" >
                    <input type="hidden" name="fecha_final" data-id="fecha_final" class="required old_field copyValue" value="<?=date("d-M-Y", strtotime($solicitud[0]->fecha_final))?>" style="background: #FAFACA;">
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.terminacion_de_contrato_otro') ?>:</label>
                    <input type="text" name="fecha_final_otro_si" id="fecha_final_casting" class="required old_field" value="<?=date("d-M-Y", strtotime($solicitud[0]->fecha_final))?>" style="background: #FAFACA;">
                  </div>
                   <div class="column two">
                    <label for=""><?php echo lang('global.tipo_moneda') ?>:</label>
                    <?php if ($tipos_moneda): ?>
                      <select name="tipo_moneda" id="tipo_moneda" class="required old_field" disabled>
                      <?php foreach ($tipos_moneda as $tipo_moneda): ?>
                          <?php if ($solicitud[0]->id_tipo_moneda == $tipo_moneda->id): ?>
                        <option value="<?=$tipo_moneda->id?>"
                            selected 
                        ><?=$tipo_moneda->descripcion?></option>
                          <?php endif ?>
                      <?php endforeach ?>
                      </select>
                    <?php endif ?>

                    <!--select data-id="tipo_moneda" class="required old_field copyValue" style="background: #FAFACA; display:none;">
                        <option>Seleccione una opción</option>
                      <?php foreach ($tipos_moneda as $tipo_moneda): ?>
                        <option value="<?=$tipo_moneda->id?>"
                          <?php if ($solicitud[0]->id_tipo_moneda == $tipo_moneda->id): ?>
                            selected 
                          <?php endif ?>
                        ><?=$tipo_moneda->descripcion?></option>
                      <?php endforeach ?>
                      </select-->

                  </div>
                   <div class="column two">
                    <label for=""><?php echo lang('global.honorarios') ?>:</label>
                    <input type="text"   class="required old_field ho honorarios" disabled name="honorarios" id="" value="<?=$solicitud[0]->honorarios?>" >
                    <input type="text" style="display:none" id="honorarios_hidden"   class="required old_field h copyValue" data-id="honorarios" id="honorarios" value="<?=$solicitud[0]->honorarios?>" style="background: #FAFACA;">
                  </div>
                   
              </div>
               <div class="clr6"></div>
               <div class="row datos_actor">
                    <div class="column two">
                    <label for=""><?php echo lang('global.honorarios_en_letras') ?>:</label>
                    <input type="text"   class="required old_field hlo" disabled id="honorarios_letras" name="honorarios_letras" value="<?=$solicitud[0]->honorarios_letras?>" >
                    <input type="hidden"   class="required old_field hl copyValue" data-id="honorarios_letras" value="<?=$solicitud[0]->honorarios_letras?>" style="background: #FAFACA;">
                  </div>
                    <div class="column two">
                    <label for=""><?php echo lang('global.honorarios') ?>:</label>
                    <input type="text"   class="required old_field ho honorarios_otro_si honorarios" name="honorarios_otro_si" id="" value="<?=$solicitud[0]->honorarios?>" style="background: #FAFACA;">
                    <input type="text" style="display:none" id="honorarios_hidden_otro_si"   class="required old_field h copyValue" data-id="honorarios" id="honorarios" value="<?=$solicitud[0]->honorarios?>" style="background: #FAFACA;">
                  </div>
                   <div class="column two">
                    <label for=""><?php echo lang('global.honorarios_en_letras') ?>:</label>
                    <input type="text"   class="required old_field hlo"  id="honorarios_letras_otro_si" name="honorarios_letras_otro_si" value="<?=$solicitud[0]->honorarios_letras?>" style="background: #FAFACA;">
                    <input type="hidden"   class="required old_field hl copyValue" data-id="honorarios_letras" value="<?=$solicitud[0]->honorarios_letras?>" style="background: #FAFACA;">
                  </div>
                  <div class="column two" id="start_month_section"  style="display:none">
                <label><?php echo lang('global.inicio_otro_si_desde') ?>:</label>
                  <input type="text" id="start_month" name="mes_inicio" class="" >
                </div>
               </div>
               <div class="clr6"></div>
                <div class="row">
                
                 <div class="column three" style="width:35%;">
                    <label><?php echo lang('global.casos_especiales') ?></label>
                    <div class="clr3"></div>
                    <label class="label_check">
                    <input type="checkbox" id="special_contidions" name="valida_condiciones"><?php echo lang('global.tiene_condiciones_especiales') ?></label>
                    <span>&nbsp;</span>
                  </div>
                <div class="clr6"></div>
                  <div class="column twelve" id="conditions_field" style="display:none;padding:0;">
                    <div class="column twelve" id="conditions_field">
                      <label for=""><?php echo lang('global.condiciones_especiales') ?>:</label>
                      <textarea style="min-height:80px;" class="required" name="condiciones_especiales"></textarea>
                    </div>
                  </div>
                </div>

           </div>
            <div id="documentos_section" >
              <div class="column title_section"><h5><?php echo lang('global.documentos') ?></h5></div>
              <div class="info data-solicitud with_title" id="row_documents">
                <div class="row" id="load_documents_solicitud">

                </div>

                <div class="row clone_row" style="display:none;">
                  <div class="load_content_base">
                    <div class="column six">
                      <label><?php echo lang('global.documentos') ?></label>
                      <input type="text" name="documento_numero_base[]" value="">
                    </div>
                    
                    <div class="column four">
                       <label for="">&nbsp;</label>
                        <div class="content-input-file inline">
                          <span class="button-file" data-input="documento_solicitud_base"><?php echo lang('global.adjuntar_archuivo') ?></span>
                          <input type="file" name="documento_solicitud_base[]" id="documento_solicitud_base" class="with-label" style="display:none">
                          <span class="label-file" data-input="documento_solicitud_base"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                        </div>
                    </div>
                    <div class="columns two">
                      <button type="button" class="agregar_documento_solicitud button alignTop twelve"><?php echo lang('global.agregar') ?></button>
                    </div>
                    <div class="clr6"></div>
                  </div>
                </div>

                <div class="row clone_row">
                  <div class="load_content">
                    <div class="column six">
                      <label><?php echo lang('global.documentos') ?></label>
                      <input type="text" name="documento_numero[]" value="">
                    </div>
                    
                    <div class="column four">
                       <label for="">&nbsp;</label>
                        <div class="content-input-file inline">
                          <span class="button-file" data-input="documento_solicitud"><?php echo lang('global.adjuntar_archuivo') ?></span>
                          <input type="file" name="documento_solicitud[]" id="documento_solicitud" class="with-label" style="display:none">
                          <span class="label-file" data-input="documento_solicitud"><?php echo lang('global.no_hay_archivo_seleccionado') ?></span>
                        </div>
                    </div>
                    <div class="columns two">
                      <button type="button" class="agregar_documento_solicitud button alignTop twelve"><?php echo lang('global.agregar') ?></button>
                    </div>
                    <div class="clr6"></div>
                  </div>
                </div>
            </div>

           
         </div>
         <div class="clr6"></div>
         <div class="column title_section"><h5><?php echo lang('global._notas_del_otro_si') ?></h5></div>
            <div class="info data-solicitud with_title">
              <div class="row">
                <div class="column twelve">
                  <label for=""><?php echo lang('global.anotaciones') ?>:</label>
                  <textarea cols="30" rows="10" name="notas_solicitud" id="notas_solicitud"></textarea>
                </div>
              </div>
            </div>
         <div class="clr6"></div>

         <div class="column title_section"><h5><?php echo lang('global.razon_social_otro_si') ?></h5></div>
            <div class="info data-solicitud with_title">
              <div class="row">
                <div class="column twelve">
                  <label for=""><?php echo lang('global.razon') ?>:</label>
                  <textarea cols="30" rows="10" name="razon_otro_si" id="razon_otro_si" class="required"></textarea>
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
              <button class="button column twelve h_40" id="guardar_solicitud"><?php echo lang('global.guardar_otro_si') ?></button>
            </div>
            <div class="columns four no-padding">
              <button class="button column twelve h_40" id="generar_solicitud"><?php echo lang('global.generar_otro_si') ?></button>
            </div>
          </div>
         </div>
          <div class="clr6"></div>
              <!-- CAMPO OCULTO VALIDACION DE COMPETAR FORMULARIO -->
              <input type="hidden" value="" name="valida_completo" id="valida_completo">
      <?php echo form_close(); ?>
    </div>
</div>	