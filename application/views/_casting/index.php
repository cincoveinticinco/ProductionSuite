<?php $idioma = $this->lang->lang().'/'; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style_casting.css"> 
<div id="breadcrumbs">
  <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>"><?php echo lang('global.inicio') ?></a> / <?php echo lang('global.casting') ?>
</div>

<?php $this->load->view('includes/partials/top_nav_solicitudes'); ?>
<nav>
    <ul class="nav_post nav_casting">
        <li><a href="#" class="buttons icon icon_plus" id="filtro_actores"><span></span><?php echo lang('global.buscar_actor') ?></a></li>
        <?php if($permisos=="write"){ ?>
        <li><a href="<?=base_url($idioma.'casting/anadir_actor')?>" class="buttons icon icon_plus" id="crear_libreto"><span></span><?php echo lang('global.crear_actor') ?></a></li>
        <?php } ?>
    </ul>
</nav>




<div id="inner_content">
    <div class="columns twelve">
      <div class="row">
        <div class="column twelve">
          <div class="info" id="filtro_general">
            <div class="column ten">
              <label for=""><?php echo lang('global.buscar_actor') ?></label>
              <input type="text"    id="palabra">
            </div>
            <div class="column two">
              <label for="" style="margin:0">&nbsp;</label>
              <button class="button twelve" id="filtro_general">
                <?php echo lang('global.buscar') ?>
              </button>
            </div>
            <div class="clr"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="column twelve">
          <div class="info" id ="filtro_actores_section" style="display:none;">
            <div class="column two">
                <label for=""><?php echo lang('global.nacionalidad') ?>:</label>
                  <div id="nacionalidades_section">
                    <?php foreach ($nacionalidades as $nacionalidad): ?>
                      <label><input type="checkbox" name="nacionalidades[]" class="nacionalidad" value="<?=$nacionalidad->id?>"><?=$nacionalidad->descripcion?></label>
                    <?php endforeach ?>
                  </div>
                <div id="nacionalidades">
                </div>
            </div>
            <div class="column ten fill_actor">
              <div class="row">
                <div class="column two">
                  <label for=""><?php echo lang('global.tipo_de_documento') ?>:</label>
                  <select name="tipo_documento" id="tipo_documento">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($tipos_documento as $tipo_documento): ?>
                      <option value="<?=$tipo_documento->id?>"><?=$tipo_documento->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.numero_documento') ?>:</label>
                  <input name="documento" id="documento" type="text"  >
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.nombre') ?>:</label>
                  <input name="nombre" id="nombre" type="text"  >
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.apellido') ?>:</label>
                  <input name="apellido" id="apellido" type="text"  >
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.genero') ?>:</label>
                  <select name="genero" id="genero">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($generos as $genero): ?>
                      <option value="<?=$genero->id?>"><?=$genero->descripcion?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="row">
                  <div class="column two">
                    <label for=""><?php echo lang('global.altura_metros') ?>:</label>
                    <div class="column six firts-range">
                      <input type="text" id="altura_desde" placeholder="<?php echo lang('global.desde') ?>" class="text-rango">
                    </div>
                    <div class="column six last-range">
                      <input type="text" id="altura_hasta" placeholder="<?php echo lang('global.hasta') ?>" class="text-rango">
                    </div>
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.peso_kg') ?>:</label>
                    <div class="column six firts-range">
                      <input type="text" id="peso_desde" placeholder="<?php echo lang('global.desde') ?>" class="text-rango">
                    </div>
                    <div class="column six last-range">
                      <input type="text" id="peso_hasta" placeholder="<?php echo lang('global.hasta') ?>" class="text-rango">
                    </div>
                  </div>
                  <div class="column two">
                    <label for=""><?php echo lang('global.edad_anos') ?>:</label>
                    <div class="column six firts-range">
                       <input name="edad_desde" id="edad_desde" type="text" placeholder="<?php echo lang('global.desde') ?>" class="text-rango" />
                      <!-- <label>Desde:</label> -->
                    </div>
                    <div class="column six last-range">
                      <input name="edad_hasta" id="edad_hasta" type="text" placeholder="<?php echo lang('global.hasta') ?>" class="text-rango" />
                      <!-- <label>Hasta: </label> -->
                    </div>
                  </div>
                   <div class="column two">
                      <label for=""><?php echo lang('global.color_tez') ?>:</label>
                      <select name="color_tez" id="color_tez">
                          <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                          <?php foreach ($colores_tez as $color_tez): ?>
                            <option value="<?=$color_tez->id?>"><?=$color_tez->descripcion?></option>
                          <?php endforeach ?>
                      </select>
                    </div>
                    <div class="column two">
                      <label for=""><?php echo lang('global.color_ojos') ?>:</label>
                      <select name="color_ojos" id="color_ojos">
                          <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                          <?php foreach ($colores_ojos as $color_ojos): ?>
                            <option value="<?=$color_ojos->id?>"><?=$color_ojos->descripcion?></option>
                          <?php endforeach ?>
                      </select>
                    </div>
              </div>
              <div class="row">
                <div class="column two">
                  <label for=""><?php echo lang('global.idioma') ?>:</label>
                  <select name="idioma" id="idioma">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($idiomas as $idioma): ?>
                        <option value="<?=$idioma->id?>"><?=$idioma->descripcion?></option>
                      <?php endforeach ?>
                  </select>
                </div>
                 <div class="column two">
                  <label for=""><?php echo lang('global.manager') ?>:</label>
                  <select name="manager" id="manager">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($managers as $manager): ?>
                      <option value="<?=$manager->id?>"><?=$manager->nombre?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.rol') ?>:</label>
                  <select name="rol_rti" id="rol_rti">
                    <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                    <?php foreach ($roles as $rol): ?>
                      <?php if ($rol['id']!=4): ?>
                        <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                      <?php endif ?>
                    <?php endforeach ?>
                  </select>
                </div>
                 <div class="column two">
                  <label for=""><?php echo lang('global.roles_desempenados') ?>:</label>
                  <select name="rol" id="rol">
                      <option value=""><?php echo lang('global.seleccion_opcion') ?></option>
                      <?php foreach ($roles as $rol): ?>
                        <?php if ($rol['id']!=4): ?>
                          <option value="<?=$rol['id']?>"><?=$rol['rol']?></option>
                        <?php endif ?>
                      <?php endforeach ?>
                  </select>
                </div>
                <div class="column two">
                  <label for=""><?php echo lang('global.proyectos_desempenados') ?>:</label>
                  <input name="proyectos_desempenados" id="proyectos_desempenados" type="text"  >
                </div>
              </div>
              <div class="row">
                 <div class="column two">
                  <label for=""><?php echo lang('global.personajes_desempenados') ?>:</label>
                  <input name="personajes_desempenados" id="personajes_desempenados" type="text"  >
                </div>
                <!-- CAMPO DE AÑO DEL PROYECTO DESEMPEÑADO -->
                <div class="column two">
                  <label for=""><?php echo lang('global.ano_proyectado') ?>:</label>
                  <select name="ano_actor" id="ano_actor">
                    <option value=""><?php echo lang('global.seleccione_un_ano') ?></option>
                    <?php for ($i=1900; $i <= date('Y'); $i++) { ?>
                      <option value="<?=$i?>"><?=$i?></option>
                    <?php } ?>
                  </select>
                </div>
                <!-- CAMPO DE AÑO DEL PROYECTO DESEMPEÑADO -->
                 <div class="column two" id="fotos_section">
                  <label for=""><?php echo lang('global.tiene_fotos') ?>:</label>
                  <label class="label-radio"><input name="fotos" class="fotos unchecked" type="radio" value="1"><?php echo lang('global.si') ?></label>
                  <label class="label-radio"><input name="fotos" class="fotos unchecked" type="radio" value="2"><?php echo lang('global.no') ?></label>
                </div>

                <div class="column two" id="disponible_section">
                  <label for=""><?php echo lang('global.disponible') ?>:</label>
                  <label class="label-radio"><input name="disponible" class="disponible unchecked" type="radio" value="1"><?php echo lang('global.si') ?></label>
                  <label class="label-radio"><input name="disponible" class="disponible unchecked" type="radio" value="2"><?php echo lang('global.no') ?></label>
                </div>
                <div class="column two" id="extranjero_section">
                  <label for=""><?php echo lang('global.extranjero') ?>:</label>
                  <label class="label-radio"><input name="extranjero" class="extranjero unchecked" type="radio" value="1"><?php echo lang('global.si') ?></label>
                  <label class="label-radio"><input name="extranjero" class="extranjero unchecked" type="radio" value="2"><?php echo lang('global.no') ?></label>
                </div>
               
              </div>

            </div>
            <div class="row">
                <div class="columns six">
                  <button class="button btn_orange colums twelve" id="cancelar_filtro">
                    <?php echo lang('global.cancelar') ?>
                  </button>
                </div>
                <div class="columns six">
                  <button class="button colums twelve" id="filtro_principal">
                    <?php echo lang('global.filtrar') ?>
                  </button>
                </div>
               </div>
            <div class="clr"></div>
          </div>
        </div>
      </div>



      <div class="row">
        <div class="column twelve">
          <div class="info"  >
            <div id="resumen_busqueda" style="display:none">
              
            </div>
            <div class="clr6"></div>
            <button type="button" id="limpiar_filtro_casting" class="twelve button" style="display:none;"><?php echo lang('global.limpiar_filtro') ?></button>
          </div>
          
        </div>
      </div>

      <div class="row">
        <div class="column twelve">
          <div class="alert-box alert" id="alert_filter" style="display:none">
            <?php echo lang('global.no_coincidencias') ?> <a href="" class="close">×</a>
          </div>
        </div>
      </div>

      <div class="row" style="display:none;" id="div_comparar_archivos">
        <div class="column twelve">
          <div class="info">
            <div class="columns four">
              <label for="nombre_produccion" style="text-align:left"><?php echo lang('global.proyecto') ?>:</label>
              <?php if ($producciones): ?>
                <select name="nombre_produccion" id="nombre_produccion">
                  <?php foreach ($producciones as $produccion): ?>
                    <option value="<?=$produccion['id_produccion']?>"><?=$produccion['nombre_produccion']?></option>
                  <?php endforeach ?>
                  <option value="otro"><?php echo lang('global.otros') ?></option>
                </select>
              <?php endif ?>
              <div id="other_production"  style="display:none;">
                <div class="columns ten">
                  <input type="text" id="nombre_produccion_otro" name="nombre_produccion_otro">
                </div>
                <div  class="columns two">
                  <button type="button" id="cancel_other_production" class="button"><?php echo lang('global.cancelar') ?></button>
                </div>
              </div>
            </div>
            <div class="columns four">
              <label for="nombre_produccion" style="text-align:left"><?php echo lang('global.personajes') ?>:</label>
              <input type="text" id="nombre_personaje" name="nombre_personaje">
            </div>
            <div class="columns four">
              <label for="">&nbsp;</label>
              <button id="comparar_actores" class="button twelve" ><?php echo lang('global.comprar') ?></button>
            </div>
            <div class="clr "></div>
          </div>
        </div>
      </div>


      <div class="row resultado_actores" id="resultado_actores">
     
      </div>
    </div>
<div>
    <!-- </div> -->
    

    <!-- RESULTADO FILTRO -->
    <div class="columns twelve">

      <!-- RESUMEN BUSQUEDA -->
     <!--  <div class="row">
       
        
      </div> -->
      <!-- FIN RESUMEN BUSQUEDA -->

      <!-- ALERTA DE RESULTADO -->
     <!--  <div class="alert-box alert" id="alert_filter" style="display:none">
        <?php echo lang('global.no_coincidencias') ?> <a href="" class="close">×</a>
      </div> -->
      <!-- FIN ALERTA DE RESULTADO -->


      <!-- <div class="row resultado_actores" id="resultado_actores">
     
      </div> -->

    </div> 
    <!-- FIN RESULTADO FILTRO -->
</div>