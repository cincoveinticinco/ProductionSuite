<?php $idioma = $this->lang->lang().'/'; ?>
<div id="breadcrumbs">
  <?php echo anchor($idioma.'produccion/producciones','HOME','class="confirm_link"'); ?>  / <?php echo lang('global.crear_produccion') ?>
</div>
<div id="inner_content">
  <?php echo $msg ?>
  <?php echo form_open_multipart($idioma.'produccion/crear_produccion','id="registerForm", class="custom", onSubmit="return comprueba_extension()"' );?>
    <br>  
    <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.hitos_produccion') ?></h5>
    <div class="row field_form">
      <div class="columns three">
        <label for="name_production"><?php echo lang('global.nombre_produccion') ?>:</label>
        <input type="text" placeholder="" class="required" id="name_production" name="name_production" value="<?php echo set_value('name_production');?>">
        <input type="hidden" value="" id="validation_name">
       <label class="error"><?php echo form_error('name_production'); ?></label>
      </div>
      <div class="columns three">
        <label for="center_production"><?php echo lang('global.centro_produccion') ?>:</label>
        <select id="basic-combo" name="centro_produccion" style="display:block!important;"  size="1" class="required">
            <option value="<?php echo set_value('name_production');?>" selected>Select option</option>
            <?php foreach ($centro_produccion as $c) { ?>
              <option value="<?php echo $c['id'] ?>"><?php echo $c['centro'] ?></option>
            <?php } ?>
          </select>
           <label class="error"><?php echo form_error('centro_produccion'); ?></label>
      </div>
      <div class="columns three">
        <label for="type_production"><?php echo lang('global.tipo_produccion') ?>:</label>
        <select name="type_production" id="type_production" style="display:block!important;" class="required">
          <option value="<?php echo set_value('type_production');?>" selected>Select option</option>
            <?php foreach ($tipo_produccion as $t) { ?>
            <!--Validacion Temporal para omitir "Unitario"-->
            <?php if($t['id'] !=1){?>
              <option value="<?php echo $t['id'] ?>"><?php echo $t['tipo'] ?></option>
            <?php } ?>
            <!--Fin Validacion Temporal para omitir "Unitario"-->
            <?php } ?>
        </select>
        <label class="error"><?php echo form_error('type_production'); ?></label>
      </div>
      <div class="columns three">
        <label for="image_production"><?php echo lang('global.imagen_produccion') ?>:</label>
        <input type="file" class="" id="image_production" name="image_production" value="<?php echo set_value('image_production');?>" class="required">
       <label class="error"><?php echo form_error('image_production'); ?></label>
      </div>
    </div>
    <div class="row field_form">
      <div class="columns three">
        <label for="start_pre"><?php echo lang('global.inicio_pre_produccion') ?>:</label>
        <input type="text"  placeholder="dd/mm/aaaa" id="start_pre" name="inicio_PreProduccion" value="<?php echo set_value('inicio_PreProduccion');?>" class="required">
        <label class="error"><?php echo form_error('inicio_PreProduccion'); ?></label>
      </div>
      <div class="columns three">
        <label for="start_recording"><?php echo lang('global.inicio_grabacion') ?>:</label>
        <input type="text" placeholder="dd/mm/aaaa" id="start_recording" name="inicio_grabacion" value="<?php echo set_value('inicio_grabacion');?>" class="">
        <label class="error"><?php echo form_error('inicio_grabacion'); ?></label>
      </div>
      <div class="columns three">
        <label for="end_recording"><?php echo lang('global.fin_grabacion') ?>:</label>
        <input type="text" placeholder="dd/mm/aaaa" id="end_recording" name="fin_grabacion" value="<?php echo set_value('fin_grabacion');?>" class="required">
        <label class="error"><?php echo form_error('fin_grabacion'); ?></label>
      </div>
      <div class="columns three">
        <label for="date_online"><?php echo lang('global.fecha_aire') ?>:</label>
        <input type="text" placeholder="dd/mm/aaaa" id="date_online" name="fecha_aire" value="<?php echo set_value('fecha_aire');?>">
        <label class="error"><?php echo form_error('fecha_aire'); ?></label>
      </div>
    </div>

    <div class="row field_form">
      <div class="columns three">
        <label for="number_cap"><?php echo lang('global.numero_libretos') ?>:</label>
        <input type="text" placeholder="" id="number_cap" name="number_cap" value="<?php echo set_value('number_cap');?>" class="required number onlynumbers maxnumber">
        <label class="error"><?php echo form_error('number_cap'); ?></label>
      </div>
      
      <div class="columns three">
        <label for="mins_cap"><?php echo lang('global.minutos_por_libreto') ?>:</label>
        <div class="row">
          <div class="column four">
            <input type="text" placeholder="00" id="mins_cap" name="mins_cap" value="<?php echo set_value('mins_cap');?>" class="required number onlynumbers maxnumber">
            <label class="error"><?php echo form_error('mins_cap'); ?></label>
          </div>
          <div class="column two"><label style="margin-top:8px; margin-left: -12px;">MM</label></div>
          <div class="column four">
            <input type="text" placeholder="00" id="segundos_capitulo" name="segundos_capitulo" value="<?php echo set_value('segundos_capitulo');?>" class="required number onlynumbers" maxlength="2">
            <label class="error"><?php echo form_error('segundos_capitulo'); ?></label>
          </div>
          <div class="column two"><label style="margin-top:8px;margin-left: -12px;">SS</label></div>
        </div>
      </div>

      <div class="columns three">
        <label for="write_cap"><?php echo lang('global.libretos_esc_semanal') ?>:</label>
        <input type="text" placeholder="" id="write_cap" name="write_cap" value="<?php echo set_value('mins_cap');?>" class="required number onlynumbers maxnumber">
        <label class="error"><?php echo form_error('write_cap'); ?></label>
      </div>
      <div class="columns three">
        <div class="columns six">
          <label for="min_proy_sem"><?php echo lang('global.minutos_proy_semanal') ?>:</label>
          <input type="text" placeholder="" id="min_proy_sem" name="min_proy_sem" value="<?php echo set_value('min_proy_sem');?>" class="required number onlynumbers maxnumber">
          <label class="error"><?php echo form_error('min_proy_sem'); ?></label>
        </div>
        <div class="columns six">
          <label for="seg_proy_sem"><?php echo lang('global.segundos_proy_semanal') ?>:</label>
          <input type="text" placeholder="" id="seg_proy_sem" maxlength="2" name="seg_proy_sem" value="<?php echo set_value('seg_proy_sem');?>" class="required number onlynumbers maxnumber">
          <label class="error"><?php echo form_error('seg_proy_sem'); ?></label>
        </div>
      </div>
    </div>
    <div class="row field_form">
      <div class="column two">
        <label for="dias_sem"><?php echo lang('global.dias_trabajar') ?>:</label>
        <select name="dias_sem" class="required" id="dias_sem" style="display:block!important;">
          <option  value="">#</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
        <label class="error"><?php echo form_error('dias_sem'); ?></label>
      </div>
      <div class="ten columns days">
        <div class="row" id="online_days">
          <div class="">
            <label for="days_online"><?php echo lang('global.dias_entrega_capitulos') ?>:</label>
          </div>
          <div class="clr"></div>
          <div class="columns"><label for="lunes" class="label_check"><input type="checkbox" class="day_check" value="1" id="lunes" name="lunes"> <?php echo lang('global.lunes') ?></label></div>
          <div class="columns"><label for="martes" class="label_check"><input type="checkbox" class="day_check"  id="martes" value="1" name="martes"> <?php echo lang('global.martes') ?></label></div>
          <div class="columns"><label for="miercoles" class="label_check"><input type="checkbox" class="day_check" id="miercoles" value="1" name="miercoles"> <?php echo lang('global.miercoles') ?></label></div>
          <div class="columns"><label for="jueves" class="label_check"><input type="checkbox" class="day_check" id="jueves" value="1" name="jueves"> <?php echo lang('global.jueves') ?></label></div>
          <div class="columns"><label for="viernes" class="label_check"><input type="checkbox" class="day_check" id="viernes" value="1" name="viernes"> <?php echo lang('global.viernes') ?></label></div>
          <div class="columns"><label for="sábado" class="label_check"><input type="checkbox" class="day_check" id="sabado" value="1" name="sabado"> <?php echo lang('global.sabado') ?></label></div>
          <div class="columns" style="float:left;"><label for="domingo" class="label_check"><input type="checkbox" id="domingo"  value="1" name="domingo"> <?php echo lang('global.domingo') ?></label></div>
        <label class="error"><?php echo form_error('dias'); ?></label>
        </div>
      </div>
    </div>
    <div class="row field_form">
      <div class="columns three">
        <label for="productor_ejecutivo"><?php echo lang('global.productor_ejecutivo') ?>:</label>
        <select name="productor_ejecutivo" id="productor_ejecutivo" style="display:block!important;">
          <option value="null" selected>Select option</option>
          <?php foreach ($ejecutivo as $e) { ?> 
                <option value="<?php echo $e['id'] ?>"><?php echo $e['nombre'].' '.$e['apellido']?></option>
          <?php } ?>
        </select>
        <label class="error"><?php echo form_error('productor_ejecutivo'); ?></label>
      </div>
      <div class="columns three">
        <label for="productor_general"><?php echo lang('global.productor_general') ?>:</label>
        <select name="productor_general" id="productor_general" style="display:block!important;">
          <option value="null" selected>Select option</option>
          <?php foreach ($productor as $p) { ?>
                <option value="<?php echo $p['id'] ?>"><?php echo $p['nombre'].' '.$p['apellido']?></option>
          <?php } ?>
        </select>
      </div>
      <div class="columns three">
        <label for="productor"><?php echo lang('global.productor') ?>:</label>
        <select name="productor" id="productor" style="display:block!important;">
          <option value="null" selected>Select option</option>
          <?php foreach ($productor as $p) { ?>
                <option value="<?php echo $p['id'] ?>"><?php echo $p['nombre'].' '.$p['apellido']?></option>
          <?php } ?>
        </select>
        <label class="error"><?php echo form_error('productor'); ?></label>
      </div>
      <div class="columns three">
        <div class="row">
          <div class="columns six">
            <label for="unidades"># <?php echo lang('global.unidades') ?>:</label>
            <select class="required" name="unidades" id="unidades" style="display:block!important;">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            <label class="error"><?php echo form_error('unidades'); ?></label>
          </div>
          <div class="column six"> 
          <label for="min_proy_sem"><?php echo lang('global.over_time') ?> :</label>
          <select name="over_time" style="display:block!important;">
          <?php for ($i=0; $i <= 24; $i++) { ?>
            <option value="<?=$i?>"><?=$i?></option> 
          <?php } ?>
          </select>
          <label class="error"><?php echo form_error('over_time'); ?></label>
        </div> 
        </div>
        
      </div>
    </div>

  <?php $i=1; while($i<=5) { ?>
    <div class="field_form field_form<?php echo $i; ?>" style="<?php if($i>1){ echo 'display:none';}?>">
      <div class="row director_item">
        <div class="columns one"><div class="icon left">U<?php echo $i; ?></div></div>
        <div class="columns eleven">
          <div class="row">
            <div class="columns four">
              <label for="director_<?php echo $i; ?>"><?php echo lang('global.director') ?></label>
              <select name="director_<?php echo $i; ?>" id="director_<?php echo $i; ?>" style="display:block!important;">
                <option value="null" selected>Select option</option>
                <?php foreach ($director_unidad as $d) { ?>
                <option value="<?php echo $d['id'] ?>"><?php echo $d['nombre'].' '.$d['apellido']?></option>
                <?php } ?>
              </select> 
              <label class="error"><?php echo form_error('director_<?php echo $i; ?>'); ?></label>
            </div>
            <div class="columns four">
              <label for="script_<?php echo $i; ?>"><?php echo lang('global.asignar_script') ?>:</label>
              <select name="script_<?php echo $i; ?>" id="script_<?php echo $i; ?>" style="display:block!important;">
                <option value="null" selected>Select option</option>
                <?php foreach ($script as $s) { ?>
                <option value="<?php echo $s['id'] ?>"><?php echo $s['nombre'].' '.$s['apellido']?></option>
                <?php } ?>
              </select>
              <label class="error"><?php echo form_error('script_<?php echo $i; ?>'); ?></label>
            </div>
            <div class="columns four">
              <label for="date_start<?php echo $i; ?>"><?php echo lang('global.fecha_inicio') ?>:</label>
              <input class="start_date" type="text" placeholder="" id="date_start<?php echo $i; ?>" name="date_start<?php echo $i;?>">
              <label class="error"><?php echo form_error('date_start<?php echo $i; ?>'); ?></label>
            </div>
          </div>
        </div>
      </div>
     </div> 
  <?php   $i++; } ?> 
    <script type="text/javascript">
      function numbersonly(myfield, e, dec){
              console.log('dasdas')
        var key;
        var keychar;

        if (window.event)
           key = window.event.keyCode;
        else if (e)
           key = e.which;
        else
           return true;
        keychar = String.fromCharCode(key);

        // control keys
        if ((key==null) || (key==0) || (key==8) || 
            (key==9) || (key==13) || (key==27) )
           return true;

        // numbers
        else if ((("0123456789").indexOf(keychar) > -1))
           return true;

        // decimal point jump
        else if (dec && (keychar == "."))
           {
           myfield.form.elements[dec].focus();
           return false;
           }
        else
           return false;
      }

     </script>

    <div class="row field_form indicadores_box">
      <div class="column twelve">
        <h5 style="text-align:center; text-transform:uppercase;"> <?php echo lang('global.indicadores_produccion') ?></h5>
        <br>
        <div class="row">
          <div class="columns four" style="border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">
              <div class="column six align_center">
                <label for="rang_prod_amount1">% <?php echo lang('global.interior') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" size="3" placeholder="" onKeyPress="return numbersonly(this, event)" name="rang_prod_amount1" id="rang_prod_amount1" />
                </div>
              </div>
              <div class="column six align_center">
                <label for="rang_prod_amount2">% <?php echo lang('global.exterior') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" size="3" placeholder="" onKeyPress="return numbersonly(this, event)" name="rang_prod_amount2" id="rang_prod_amount2"/>
                </div>
              </div>
            </div>
          </div>
          <div class="columns four" style="border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">
              <div class="column six align_center">
                <label for="locacion">% <?php echo lang('global.locacion') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" size="3" placeholder="" onKeyPress="return numbersonly(this, event)" name="locacion" id="locacion" />
                </div>
              </div>
              <div class="column six align_center">
                <label for="estudio">% <?php echo lang('global.estudio') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" size="3" placeholder="" onKeyPress="return numbersonly(this, event)" name="estudio" id="estudio"/>
                </div>
              </div>
            </div>
          </div>
          <div class="columns four">
            <div class="row">
              <div class="column six align_center">
                <label for="dia">% <?php echo lang('global.dia') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="dia" id="dia" />
                </div>
              </div>
              <div class="column six align_center">
                <label for="noche">% <?php echo lang('global.noche') ?>:</label>
                <div class="box_blue">
                  % <input type="text" maxlength="3" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="noche" id="noche"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- LINEA 2 INDICADORES -->
        <div class="row">
          <div class="columns four" style="border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">
              
               <div class="column six align_center">
                <label for="monto_figurante_extra"><?php echo lang('global.monto_min_pesos') ?>:</label>
                <div class="box_blue">
                  $<input type="text" placeholder="" onKeyPress="return numbersonly(this, event)" name="monto_figurante_extra" id="monto_figurante_extra"/>
                </div>
              </div>

              <div class="column six align_center">
                <label for="monto_figurante_extra_dolar"><?php echo lang('global.monto_min_dolar') ?>:</label>
                <div class="box_blue">
                  $<input type="text" placeholder="" onKeyPress="return numbersonly(this, event)" name="monto_figurante_extra_dolar" id="monto_figurante_extra_dolar"/>
                </div>
              </div>
           
            </div>
          </div>
          <div class="columns four" style="border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">

              <div class="column six align_center">
                <label for="escenas_libretos"><?php echo lang('global.escenas_libretos') ?>:</label>
                <div class="box_blue">
                  <input type="text" maxlength="3" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="escenas_libretos" id="escenas_libretos"/>
                </div>
              </div>

              <div class="column six align_center">
                <label for="evento_pequeno"><?php echo lang('global.evento_pequeno') ?>:</label>
                  <div class="box_blue">
                    <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_pequeno" id="evento_pequeno"/>
                  </div>
              </div>
            </div>

          </div>
          <div class="columns four">
            <div class="row">
               <div class="column six align_center">
                <label for="evento_mediano" style="width: 96%;"><?php echo lang('global.evento_mediano') ?>:</label>
                  <div class="box_blue">
                    <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_mediano" id="evento_mediano"/>
                  </div>
              </div> 

              <div class="column six align_center">
                <label for="evento_grande" style="width: 93%;"><?php echo lang('global.evento_grande') ?>:</label>
                  <div class="box_blue">
                    <input type="text" maxlength="3" placeholder="eventos" size="3" onKeyPress="return numbersonly(this, event)" name="evento_grande" id="evento_grande"/>
                  </div>
              </div>

              
            </div>
          </div>

        </div>
        <div class="row">
          <div class="columns four" style="border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">
              <div class="column six align_center">
                <label for="protagonistas_produccion"><?php echo lang('global.protagonistas_produccion') ?>:</label>
                <div class="box_blue">
                  <input type="text" maxlength="3" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="protagonistas_produccion" id="protagonistas_produccion"/>
                </div>
              </div>
              <div class="column six align_center">
                <label for="presupuesto_principales"><?php echo lang('global.presupuesto_protagonista') ?>:</label>
                <div class="box_blue">
                  $<input type="text" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="presupuesto_principales" id="presupuesto_principales"/>
                </div>
              </div>
            </div>
          </div>
          <div class="columns four" style="float:left;border-right: 1px solid #c1c1c1;box-shadow: 1px 0px 0 #fff;">
            <div class="row">
              <div class="column six align_center">
                <label for="presupuesto_secundarios"><?php echo lang('global.presupuesto_reparto') ?>:</label>
                <div class="box_blue">
                  $<input type="text" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="presupuesto_secundarios" id="presupuesto_secundarios" />
                </div>
              </div>

              <div class="column six align_center">
                <label for="presupuesto_figurante"><?php echo lang('global.presupuesto_figurante') ?>:</label>
                <div class="box_blue">
                  $<input type="text" placeholder="" size="3" onKeyPress="return numbersonly(this, event)" name="presupuesto_figurante" id="presupuesto_figurante" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
      </div>
      <div class="clr"></div>
      <div class="centered column eight">
        <h5 style="text-align:center; text-transform:uppercase;"><?php echo lang('global.indicadores_libreto') ?></h5>
        <br>
        <div class="row">
          <div class="column four align_center">
            <label for=""><?php echo lang('global.max_locaciones') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3"  placeholder="#" id="rang_cap_1" onKeyPress="return numbersonly(this, event)" name="numero_locaciones">
            </div>
          </div>
          <div class="column four align_center">
            <label for=""><?php echo lang('global.max_sets') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3" placeholder="#" id="rang_cap_2" onKeyPress="return numbersonly(this, event)" name="numero_set">
            </div>    
          </div>
          <div class="column four align_center">
            <label for=""># <?php echo lang('global.protagonista') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="numero_protagonistas">
            </div>
          </div>
          <div class="column four align_center">
            <label for=""># <?php echo lang('global.reparto') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="numero_respartos">
            </div>
          </div>
          <div class="column four align_center">
            <label for=""># <?php echo lang('global.figurante') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="numero_figurantes">
            </div>
          </div>
          <div class="column four align_center">
            <label for=""># <?php echo lang('global.extra') ?>:</label>
            <div class="box_gray">
              <input type="text" maxlength="3" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="numero_extras">
            </div>
          </div>
          <div class="column four align_center" style="float:left">
            <label for=""># <?php echo lang('global.vehiculos') ?>:</label>
            <div class="box_gray">
              <input type="text" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="numero_vehiculos">
            </div>
          </div>
          <div class="column four align_center" style="float:left">
            <label for=""># <?php echo lang('global.paginasLibretos') ?>:</label>
            <div class="box_gray">
              <input type="text" size="3"  placeholder="#"  id="rang_cap_3" onKeyPress="return numbersonly(this, event)" name="paginas_libretos">
            </div>
          </div>
        </div>
        <br>
      </div>
    </div>
    <div class="call_to_action align_right">

      <a href="<?php echo base_url($idioma.'produccion/producciones'); ?>" class="button full secondary"><?php echo lang('global.cancelar') ?></a>
      <button type="submit" >
        <span  class="button full"><?php echo lang('global.crear_produccion') ?></span>
      </button>
    </div>
    <input type="hidden" id="oculto" name="oculto">
  <?php echo form_close(); ?>   
</div>
