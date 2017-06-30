<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class pdf2 extends CI_Controller {


    function __construct(){
      parent::__construct();
      $this->load->library('Pdf');
      $this->load->library('mypdf');
      $this->load->model('model_plan_produccion');
      $this->load->model('model_plan_diario');
      $this->load->model('model_plan_general');
      $this->load->model('model_admin');
      $this->load->model('model_capitulos');
      $this->load->model('model_escenas_2');
      $this->load->model('model_escenas');
      $this->load->model('model_pdf');
      $this->load->model('model_elementos');
      $this->load->model('model_herramientas');
      $this->load->model('model_post_produccion');
      $this->load->model('model_dashboard');
    }

    public function index($id='',$id_unidad='',$fecha_unidad='',$options_pdf=''){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($ejecutivo){
          $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
      }else{
          $nombre_ejecutivo='';
      }
      if($productor){
          $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
      }else{
          $nombre_productor='';
      }
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }
       
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $capitulos = $this->model_capitulos->capitulos_produccion_2($id);
      $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
      $sql="";
      $sql2 = "";
      $escenas="";
      $unidad="";
      
      $ultima_edicion="";
      $fechas_bloqueadas = $this->fechas_bloqueadas($id);
      $plan_select ="";
      $id_user = $this->session->userdata('id_pruduction_suite');
      $data=array('id_usuario'=>$id_user,'tipo'=>1);
      $campos_usuario = $this->model_plan_diario->buscar_columnas($data);
      
        $sql2=" e.descripcion AS descripcion_escena, 
                e.guion AS guion_escena,
                d.descripcion AS des_dia, 
                i.descripcion AS des_int, 
                l.nombre AS nom_locacion, 
                s.nombre AS nom_set,
                t.tipo AS tipo,
                (SELECT COUNT(id) FROM retomas_escena where id_escena = e.id) AS retomas, ";

      if($campos_usuario==false){
         $campos_usuario = $this->campos();
         $campos_usuario[count($campos_usuario)]="";
      }
      
      $campos = $this->campos();
      
      $ultimo_plan = $this->model_plan_diario->ultimo_plan($id_user, $id);
      

     if($id_unidad!=''){
        $data=array('id_unidad'=>$id_unidad,'fecha'=>$fecha_unidad);
        if($fecha_unidad==''){
          $escenas=$this->model_plan_diario->escenas_unidad($id_unidad);
        }else{
          $escenas=$this->model_plan_diario->unidad_id_fecha($data,$sql2);
        }
        $unidad=$this->model_plan_diario->unidad_id($id_unidad);
        $fecha=date('Y-m-d');
        $plan_select = $this->model_plan_general->buscar_plan_diario_fecha($fecha_unidad,$id_unidad);
        if($plan_select){
          $ultima_edicion = $this->model_plan_diario->ultima_edicion($plan_select[0]->id);
        }
        if($escenas){
          if($ultimo_plan){
            $this->model_plan_diario->actualizar_ultimo_plan($ultimo_plan[0]->id_ultimo,$escenas['0']['id_plan_diario']);
          }else{
            $this->model_plan_diario->insertar_ultimo_plan($id_user,$escenas['0']['id_plan_diario']);
          }
          $user_dia=$this->model_plan_diario->unidad_dia($escenas['0']['id_plan_diario'],$fecha);
          $comentarios=$this->model_plan_diario->comentarios_user($escenas['0']['id_plan_diario']);
          if($user_dia){
            $director_dia=$this->model_admin->user_id($user_dia['0']->id_director);
            $script_dia=$this->model_admin->user_id($user_dia['0']->id_script);
          }
        }
       
      }
      if(isset($director_dia) and $director_dia ){
        $director_unidad=strtoupper($director_dia['0']->nombre.' '.$director_dia['0']->apellido); 
      } else { 
         $director_unidad=strtoupper($unidad['0']['dir']); 
      }
       if ($escenas['0']['llamado']){
          $hora_llamado= date("h:i A",strtotime($escenas['0']['llamado'])); 
       }else{ 
          $hora_llamado='-';
       } 
       $total_escenas_pautadas=0;
      if($escenas){
        $total_escenas_pautadas=sizeof($escenas);
      }

      $duracion_minutos_es=0; $duracion_segundos_es=0; 
      if($escenas){
        foreach ($escenas as $e) {
           $duracion_minutos_es=$e['duracion_estimada_minutos']+$duracion_minutos_es;
          $duracion_segundos_es=$e['duracion_estimada_segundos']+$duracion_segundos_es;

        } 
      }
      $tiempo=$this->calculo_tiempo2($duracion_minutos_es,$duracion_segundos_es);

      $fecha= date("d-M-Y H:i:s");
      if($escenas[0]['lugar']==""){
        $lugar_llamado="-";
      }else{
        $lugar_llamado=$escenas[0]['lugar'];
      }

      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


      $dia_uni=strtotime($fecha_unidad); 
      $d=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$dia_uni),date("d",$dia_uni), date("Y",$dia_uni)) , 0 );
      switch ($d) {
        case '1':
          $dia_semana='Lunes';
          break;
        case '2':
          $dia_semana='Martes';
          break;
        case '3':
          $dia_semana='Miercoles';
          break;
        case '4':
          $dia_semana='Jueves';
          break;
        case '5':
          $dia_semana='Viernes';
          break;
        case '6':
          $dia_semana='Sabado';
          break;          
        case '7':
          $dia_semana='Domingo';
          break;
      }
      $fecha_unidad_semana=$dia_semana.': '.date("d-M-Y",strtotime($fecha_unidad));
      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'lugar_llamado' => $lugar_llamado,
        'productor_general'=>strtoupper($productor_general),
        'director_unidad'=>$director_unidad,
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'fecha'=>date("d-M-Y",strtotime($fecha)),
        'fecha_unidad'=>$fecha_unidad_semana,
        'hora_llamado'=>$hora_llamado,
        'total_escenas_pautadas'=>$total_escenas_pautadas,
        'tiempo'=>$tiempo,
        'unidad_numero'=>$unidad['0']['numero'],
        'estado_plan'=>$escenas[0]['estado_plan']
      );

      $pdf->setElementsHeader($valores);
      if($options_pdf!=2){ 
      $pdf->setTipoHeader(1);
      $pdf->setTitulo("GENERAL");
      $pdf->SetTopMargin('33px');
      $pdf->AddPage('L');
      $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='28px');
      $i=0;
      $pdf->SetFont('', '', 7);

      // -----------------------------------------------------------------------------
      $tbl4 ="";
   $i=1;
   $total=sizeof($escenas);
   $cont=0;
   $cont2=1;
   $sum_estimado_m=0;
   $sum_estimado_s=0;
   $sum_real_m=0;
   $sum_real_s=0;
if($escenas){
      $j=0;
         foreach ($escenas as $e) { 
          $tiempo = false;
          $orden=$e['orden'];
          $numero=$e['numero'];
          $libreto=$e['numero'];
          $escena=$e['numero_escena'];
          $continuidad=$e['dias_continuidad'];
          $dia_noche=strtoupper($e['des_dia']);


          $int_ext=strtoupper($e['des_int']);
          $tipo=strtoupper($e['tipo']);

          if(strtoupper($e['tipo']) != "ESTUDIO"){
            $tipo = "LOCACI&Oacute;N";
          }else{
            $tipo = strtoupper($e['tipo']);
          }
          $locacion=strtoupper($e['nom_locacion']);
          $set=strtoupper($e['nom_set']);
          $d_realm='';
          $d_reals='';
          if($e['duracion_real_minutos'] AND ($e['duracion_real_minutos']!='00' OR $e['duracion_real_segundos']!='00') ){ 
            $tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$fecha_unidad,$id_unidad); 
              if($tiempo){
                  $temporal = explode(':', $tiempo[0]->tiempo);
                  $d_realm = (int)$temporal[0];
                  $d_reals = (int)$temporal[1];

                  if(strlen($d_realm)<2){
                    $d_realm = '0'.$d_realm;
                  } 
                  if(strlen($d_reals)<2){
                    $d_reals = '0'.$d_reals;
                  } 
                  $d_realm .= ':';
              }else{ 
                $d_realm='-';
                $d_reals='-';
              } 
          }else{ 
            $d_realm='-';
            $d_reals='-';
          } 

          $sum_real_m+=$d_realm;
          $sum_real_s+=$d_reals;
          if($e['comienzo_ens']){ 
            $c_ens=date("h:i A",strtotime($e['comienzo_ens']));
          }else{
            $c_ens='--';        
           } 
          $t_estimado_minutos='';
          $t_estimado_segundos='';
          if($e['duracion_estimada_minutos']){
               if(strlen($e['duracion_estimada_minutos'])<2){
            $t_estimado_minutos='0';
          }
             $t_estimado_minutos.=$e['duracion_estimada_minutos'].':'; 
         }else{
           $t_estimado_minutos='--';
         }
         if($e['duracion_estimada_segundos'] or $e['duracion_estimada_segundos']==0){
            if(strlen($e['duracion_estimada_segundos'])<2){
             $t_estimado_segundos.='0';
            } 
          $t_estimado_segundos.=$e['duracion_estimada_segundos'];
         }else{
            $t_estimado_segundos='--';
         }
         $sum_estimado_m+=$t_estimado_minutos;
         $sum_estimado_s+=$t_estimado_segundos;

         if($e['comienzo_prod']){ 
          $c_pro=date("h:i A",strtotime($e['comienzo_prod']));
        }else{
          $c_pro='--';
         }
         if($e['fin_produccion']){ 
          $f_pro=$e['fin_produccion'];
         }else{
          $f_pro='--';
         }
         $observaciones=$e['comentarios'];

          if ($i%2==0){
            if($tiempo){
              $b="#FEE949";
            }else{
              $b="#e6e4e5";
            }
          }else{
            if($tiempo){
              $b="#FEE976";
            }else{
              $b="#ffffff";
            }
          }

         $tbl4.=<<<EOD
      <table border="0.5" style="whith:100%;" cellpadding="2">
         <tr style="background-color:$b">
          <td width="13" align="center">$cont2</td>
          <td width="28" align="center">$libreto</td>
          <td width="28" align="center">$escena</td>
          <td width="28" align="center">$continuidad</td>
          <td width="32" align="center">$dia_noche</td>
          <td width="40" align="center">$int_ext</td>
          <td width="55" align="center">$tipo</td>
          <td width="137" align="left">$locacion</td>
          <td width="138" align="left">$set</td>
          <td width="38" align="center">$t_estimado_minutos$t_estimado_segundos</td>
          <td width="32" align="center">$d_realm$d_reals</td>
          <td width="40" align="center">$c_ens</td>
          <td width="40" align="center">$c_pro</td>
          <td width="40" align="center">$f_pro</td>
          <td width="120" align="left">$observaciones</td>
         </tr>
      </table>
EOD;
          $i++;

if($i==2 and $options_pdf!=3){
  $pdf->writeHTML($tbl4, true, false, false, false, '');
  $pdf->setTipoHeader(1);
  $y = $pdf->GetY();  
  $pdf->SetTopMargin('37px');
  $pdf->SetY($y-3);
  $tbl4="";
}

         if($i==50){
          $total_producidas=0; 
          if($escenas){
            foreach ($escenas as $e) {
                if($e['estado_escenas']==1){
                  $total_producidas=$total_producidas+1;
                }
              }
          } 
          $total_escenas_pautadas=0;
          if($escenas){
            $total_escenas_pautadas=sizeof($escenas);
          }
          $total_pro_producir=$total_escenas_pautadas-$total_producidas;
            $i=1;
            $pdf->writeHTML($tbl4, true, false, false, false, '');
              $tbl4 = <<<EOD
      <table border="0.5" style="whith:100%;" cellpadding="2">
         <tr style="background-color:#535357;color:#FFFFFF;">
          <td width="13" align="center" >#</td>
          <td width="28" align="center">LIB</td>
          <td width="28" align="center">ESC</td>
          <td width="28" align="center">CONT</td>
          <td width="32" align="center">D/N</td>
          <td width="40" align="center">INT/EXT</td>
          <td width="55" align="center">LOC/EST</td>
          <td width="137" align="center">LOCACIONES</td>
          <td width="138" align="center">SET</td>
          <td width="38" align="center">T.EST</td>
          <td width="32" align="center">T.REAL</td>
          <td width="40" align="center">C.ENS</td>
          <td width="40" align="center">C.PRO</td>
          <td width="40" align="center">F.PRO</td>
          <td width="120" align="center">OBSERVACIONES</td>
         </tr>
         </table>
EOD;

           }
           $cont++;
           $cont2++;

           if($total==$cont){
                $total_producidas=0; 
          if($escenas){
            foreach ($escenas as $e) {
                if($e['estado_escenas']==1){
                  $total_producidas=$total_producidas+1;
                }
              }
          } 

    $total_escenas_pautadas=0;
    if($escenas){
      $total_escenas_pautadas=sizeof($escenas);
    }
    $total_pro_producir=$total_escenas_pautadas-$total_producidas;
    $total_tiempo_estimado=$this->calculo_tiempo2($sum_estimado_m,$sum_estimado_s);
    $total_tiempo_real=$this->calculo_tiempo2($sum_real_m,$sum_real_s);

    if ($escenas['0']['wrap_time'] AND $escenas['0']['wrap_time'] !='00:00:00'){ 
        $hora_corte=date("h:i A",strtotime($escenas['0']['wrap_time']));
    }else{
        $hora_corte='--';
    }

    $tbl4.=<<<EOD
<table border="0.5" style="whith:100%;" cellpadding="2">
   <tr style="background-color:#00a2e0;">
    <td width="169" align="center" colspan="6"><b>ESCENAS PRODUCIDAS: $total_producidas</b></td>
    <td width="192" align="center"><b>ESCENAS POR PRODUCIR: $total_pro_producir</b></td>
    <td width="138" align="center"><b>TOTAL TIEMPO </b></td>
    <td width="38" align="center"><b>$total_tiempo_estimado</b></td>
    <td width="32" align="center"><b>$total_tiempo_real</b></td>
    <td width="240" align="center"><b>HORA CORTE GENERAL: $hora_corte</b></td>
   </tr>
   </table>
EOD;
      $i=1;
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $comentario=''; 
    if($comentarios){
       foreach ($comentarios as $c) {
          $comentario.=strtoupper($c['nombre'].' '.$c['apellido'].' '.$fecha=date("d-M-Y",strtotime($c['fecha']))).'  '.$c['comentario'].'<br>';
      }
    }
    $pdf->setTipoHeader(null);
    if($comentario){
$tbl4=      
<<<EOD
<table border="0.5" style="whith:100%;" cellpadding="2">
   <tr style="text-align:left">
    <td width="810" colspan="8" style="background-color:#535357; color:#ffffff;">OBSERVACIONES DE PRODUCCION</td>
   </tr>
   <tr >
    <td height="55" colspan="8">$comentario</td>
   </tr>
   </table>
EOD;

 $pdf->writeHTML($tbl4, true, false, false, false, '');
 }
 $tbl4="";
     }
     
   } 
}else{
     $pdf->SetDrawColor(220,220,220);
     $pdf->SetFillColor(220,220,220);
     $pdf->SetY(-28);
     $pdf->Cell(310,4,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
     $pdf->Cell(0,4,'Pag: '.$pdf->getPage().'/'.$pdf->getNumPages(),0,0,'R',true);
}  

} 
  if($options_pdf!=1){ 
    $pdf->setTipoHeader(2);
    $pdf->setTitulo("DETALLADO");
    if($options_pdf==2){
      $pdf->SetTopMargin('23.5px');
    }elseif($options_pdf==3){
       $pdf->SetTopMargin('25.5px');
    }else{
      $pdf->SetTopMargin('36px');
    }
    

    $tbl4 ="";
    
        $pdf->AddPage('L');
        $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='15px');
        $pdf->SetDrawColor(0);
        $pdf->SetFillColor(0);
        $pdf->SetTextColor(0);
        $pdf->SetFont('', '', 7);
   $i=1;
   $total=sizeof($escenas);
   $cont=0;
   $sum_estimado_m=0;
   $sum_estimado_s=0;
   $sum_real_m=0;
   $sum_real_s=0;
if($escenas){
  $cont_2=1;
   foreach ($escenas as $e) { 
    $orden=$e['orden'];
    $libreto=$e['numero'];
    $escena=$e['numero_escena'];
    $continuidad=$e['dias_continuidad'];
    $dia_noche=strtoupper($e['des_dia']);
    $int_ext=strtoupper($e['des_int']);
    $tipo = strtoupper(utf8_decode($e['tipo']));
    $locacion=$e['nom_locacion'];
    $set=$e['nom_set'];
    $d_realm='';
    $d_reals='';
    if($e['duracion_real_minutos'] AND ($e['duracion_real_minutos']!='00' OR $e['duracion_real_segundos']!='00') ){ 
        if(strlen($e['duracion_real_minutos'])<2){
          $d_realm='0';
        } $d_realm.= $e['duracion_real_minutos'].':'; 
        if(strlen($e['duracion_real_segundos'])<2){
          $d_reals='0';
        } $d_reals.=$e['duracion_real_segundos'];
    }else{ 
      $d_realm='-';
      $d_reals='-';
    } 
    $sum_real_m+=$d_realm;
    $sum_real_s+=$d_reals;
    if($e['comienzo_ens']){ 
      $c_ens=date("h:i A",strtotime($e['comienzo_ens']));
    }else{
      $c_ens='--';        
     } 
    $t_estimado_minutos='';
    $t_estimado_segundos='';
   if($e['duracion_estimada_minutos']){
         if(strlen($e['duracion_estimada_minutos'])<2){
           $t_estimado_minutos='0';
         }
       $t_estimado_minutos.=$e['duracion_estimada_minutos'].':'; 
   }else{
     $t_estimado_minutos='--';
   }
   if($e['duracion_estimada_segundos'] or $e['duracion_estimada_segundos']==0){
      if(strlen($e['duracion_estimada_segundos'])<2){
      $t_estimado_segundos.='0';
    } 
    $t_estimado_segundos.=$e['duracion_estimada_segundos'];
   }else{
      $t_estimado_segundos='--';
   }
   $sum_estimado_m+=$t_estimado_minutos;
   $sum_estimado_s+=$t_estimado_segundos;

   if($e['comienzo_prod']){ 
    $c_pro=date("h:i A",strtotime($e['comienzo_prod']));
  }else{
    $c_pro='--';
   }
   if($e['fin_produccion']){ 
    $f_pro=$e['fin_produccion'];
   }else{
    $f_pro='--';
   }
   $observaciones=$e['descripcion_escena'];
   $personajes=$this->model_plan_diario->elemento_personajes($e['id_escena']);
   $cadena_personajes_principal="";
   $cadena_personajes_otros="";
    if($personajes){
        foreach ($personajes as $p) {
          if($p['rol']==1 OR $p['rol']==3){
            $cadena_personajes_principal.= '<span style="display:block;background-color:#ffffff;border-bottom;1px solid #000000;">'.$p['nombre'].'</span><hr>';
          }else{
            $cadena_personajes_otros.= '<span style="display:block;background-color:#ffffff;border-bottom;1px solid #000000;">'.$p['nombre'];
            if($p['cantidad_extra']!=""){
              $cadena_personajes_otros.='('.$p['cantidad_extra'].')';
            }
            $cadena_personajes_otros.='</span><hr>';
          }
          
        }
    }
    $personajes=$this->model_pdf->elementos_dif_personajes_group_tipo($e['id_escena']); 
    $cadena_elementos="";
    $tipo='';
    if($personajes){
      $tipo=$personajes['0']['id_tipo_elemento'];
      $cadena_elementos.='<div style="display:table;background-color:#20CFFF;padding:8px;border-bottom;1px solid #000000; whith:80px">'.$personajes['0']['tipo'].'</div>';
      foreach ($personajes as $p) { 
        if($tipo==$p['id_tipo_elemento']){
          $cadena_elementos.=$p['nombre'].'<hr>';
        }else{
          $cadena_elementos.='<div style="display:table;background-color:#20CFFF;padding:8px;border-bottom;1px solid #000000; whith:80px">'.$p["tipo"].'</div>';
          $cadena_elementos.= $p['nombre'].'<hr>';
          $tipo=$p['id_tipo_elemento'];
        }
      }
    }  

    if ($i%2==0){
      $b="#e6e4e5";
    }else{
      $b="#ffffff";
    }
    
    if(strtoupper($e['tipo']) != "ESTUDIO"){
      $tipo = "LOCACI&Oacute;N";
    }else{
      $tipo = strtoupper($e['tipo']);
    }
   $tbl4.=<<<EOD
<table border="0.5" style="whith:100%;" cellpadding="2">
   <tr style="background-color:$b;color:black;">
    <td width="13" align="center">$cont_2</td>
    <td width="20" align="center">$libreto</td>
    <td width="20" align="center">$escena</td>
    <td width="20" align="center">$libreto</td>
    <td width="66" align="center">$dia_noche<br>$int_ext<br>$tipo<br>$continuidad</td>
    <td width="105" align="left">$locacion</td>
    <td width="105" align="left">$set</td>
    <td width="28" align="center">$t_estimado_minutos$t_estimado_segundos</td>
    <td width="85" align="left">$cadena_personajes_principal</td>
    <td width="85" align="left">$cadena_personajes_otros</td>
    <td width="85" align="left">$cadena_elementos</td>
    <td width="178" align="left">$observaciones</td>
   </tr>
   </table>
EOD;
$cont_2++;
$i++;
if($i==2 AND $options_pdf==2){
  $pdf->writeHTML($tbl4, true, false, false, false, '');
  $pdf->setTipoHeader(3);
  $y = $pdf->GetY();  
  $pdf->SetTopMargin('19px');
  $pdf->SetY($y-3);
  $tbl4="";
}

if($i==2 AND $options_pdf==3){
  $pdf->writeHTML($tbl4, true, false, false, false, '');
  $y = $pdf->GetY(); 
  $pdf->SetTopMargin('19px');
  $pdf->SetY($y-3);
  $pdf->setTipoHeader(3);
  $tbl4="";
}



   }
}
}

    $pdf->writeHTML($tbl4, true, false, false, false, '');
    $pdf->Output('plan_diario.pdf', 'I');
}


  public function fechas_bloqueadas($idproduccion){
      $cadena_fechas = "";
      $contador_dias = 0;
      $j=86400;
      $semanas = $this->model_plan_produccion->semanas_trabajo($idproduccion);
      foreach ($semanas as $semana) {
        $contador_dias = strtotime($semana['fecha_inicio_semana']);
        while ($contador_dias<=strtotime($semana['fecha_fin_semana'])) {
                      switch(date("D",$contador_dias)){
                        case "Mon":
                            if($semana['lunes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Tue":
                            if($semana['martes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Wed":
                            if($semana['miercoles'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Thu":
                            if($semana['jueves'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case "Fri":
                            if($semana['viernes'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sat':
                            if($semana['sabado'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                        case 'Sun':
                            if($semana['domingo'] != 'checked'){
                                $cadena_fechas.= date("Ymd",$contador_dias).',';
                            }
                        break;
                    }
            $contador_dias+=$j;
        }
      }
      return $cadena_fechas;
    }

    public function campos(){
      $campos[]="Descripción";
      $campos[]="guión";
      $campos[]="día/noche";
      $campos[]="int/ext";
      $campos[]="locación";
      $campos[]="set";
      $campos[]="personajes";
      $campos[]="elementos";
      $campos[]="loc/est";
      return $campos;
    }


    public static  function calculo_tiempo2($minutos1,$segundos1){
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $minutos1 + $minutos;


      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }
        if($minutos2==0){
          $minutos2='00';
        }
        if($segundos==0){
          $segundos='00';
        }
        $tiempo = $minutos2.":".$segundos;

      return $tiempo;
    }


    /*PDF DE PLAN GENERAL*/
    public function pdf_plan_general(){
      ini_set('memory_limit', '-1');
      set_time_limit(10000000);
      $id=$this->input->post('idproduccion');

      $produccion = $this->model_plan_produccion->produccion_id($id);
      $personajes_produccion = $this->model_plan_general->categoria_produccion($id, 'Personaje');
      $categoria_elemento=$this->model_escenas->categoria_elemento($id);
      $unidades = $this->model_plan_produccion->unidades_id_produccion($id);
      $escenas_noproducidas = $this->model_escenas_2->contar_escenas_noproducidas($id);
      $sql2="";
      
      $limit_inf=0;
      if($this->input->post('consultaImpresion')==""){
        $escenas = $this->model_plan_general->listar_escenas($id,$personajes_produccion[0]->id,$limit_inf,300);
        $temporal = $this->model_pdf->suma_tiempos_pdf($id);
        $tiempo_diponible = $this->calculo_tiempo($temporal);
      }else if($this->input->post('consultaImpresion')!=1){
        $query = $this->db->query($this->input->post('consultaImpresion'));
        if ($query->num_rows>0) {
          $escenas = $query->result();
          $msg=$this->db->last_query();
          
          $sql ="";
          $cantidad = count($escenas);
          foreach ($escenas as $escena) {
            if($sql==""){
              $sql.= " WHERE escena.id = ".$escena->id;
            }else{
              $sql .= " OR escena.id = ".$escena->id;
            }
          }
          $temporal = $this->model_escenas_2->suma_tiempos_ajax($sql);
          $tiempo_diponible = $this->calculo_tiempo($temporal);
        }
      }else{
        $escenas = false;
      }

      $total_escenas=$this->model_plan_general->total_escena($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($ejecutivo){
          $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
      }else{
          $nombre_ejecutivo='';
      }
      if($productor){
          $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
      }else{
          $nombre_productor='';
      }
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }
       
      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $pdf->SetHeaderMargin('10px');
      $pdf->setTipoPdf(1);
      $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');

      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=> strtoupper($produccion['0']->nombre_produccion),
        'nombre_ejecutivo'=> strtoupper($nombre_ejecutivo),
        'nombre_productor'=> strtoupper($nombre_productor),
        'tiempo_diponible'=> $tiempo_diponible,
        'total_escenas'=> count($escenas)
      );

      $pdf->setElementsHeader($valores);
      $pdf->SetTopMargin('25px');
      $pdf->AddPage('L');
      $i=0;
      $pdf->SetFont('', '', 7);
      
      $tbl4 ="";
      if($escenas){
        $i=1;
        foreach ($escenas as $escena) {

            switch($escena->estado){
              case 1:
              $color_celda = "rgb(254,233,62)";
              break;
              case 2:
              $color_celda = "rgb(254,198,62)";
              break;
              case 3:
              $color_celda = "rgb(0,0,0)";
              break;
              case 4:
              $color_celda = "rgb(9,238,233)";
              break;
              case 5:
              $color_celda = "rgb(166,255,249)";
              break;
              case 6:
              $color_celda = "rgb(227,34,141)";
              break;
              case 7:
              $color_celda = "rgb(239,123,187)";
              break;
              case 8:
              $color_celda = "rgb(140,221,22)";
              break;
              case 9:
              $color_celda = "rgb(193,243,120)";
              break;
              case 10:
              $color_celda = "rgb(247,146,30)";
              break;
              case 11:
              $color_celda = "rgb(250,202,154)";
              break;
              case 12:
              $color_celda = "rgb(254,233,62)";
              break;
              case 14:
              $color_celda = "rgb(254,198,62)";
              break;
              default:
              $color_celda = "rgb(167,153,128)";
              break;  
            }
            $unidad_produccion = "";
             if(($escena->estado == 1 and  $escena->unidad_produccion!=null) or ($escena->estado == 2 and  $escena->unidad_produccion!="") ){
              $unidad_produccion= $escena->unidad_produccion_numero;  
            }else{
                if ($escena->unidad_numero != 0) {
                    $unidad_produccion= $escena->unidad_numero;
                } else {
                    $unidad_produccion= '-';
                }
            }

            $fecha_inicio_unidad ="";
            if (($escena->estado == 1 and  $escena->fecha_produccion!=null and  $escena->fecha_produccion!="0000-00-00") or ($escena->estado == 2 and  $escena->fecha_produccion!=null and  $escena->fecha_produccion!="0000-00-00") ) {
                  $fecha_inicio_unidad= $escena->fecha_produccion_2;
              } else {
                if ($escena->fecha_inicio != null and $escena->fecha_inicio != "0000-00-00") {
                  $fecha_inicio_unidad=$escena->fecha_inicio;
                } else {
                  $fecha_inicio_unidad='-';
                }
             }

            $duracion_estimada = "";
            $duracion_real = "";
            $duracion_post = "";
 
            if(strlen($escena->duracion_estimada_minutos)<2){
                $duracion_estimada.= '0'.$escena->duracion_estimada_minutos.':';
            }else{
                $duracion_estimada.= $escena->duracion_estimada_minutos.':';
            }

            if(strlen($escena->duracion_estimada_segundos)<2){
                $duracion_estimada.= '0'.$escena->duracion_estimada_segundos;
            }else{
                $duracion_estimada.= $escena->duracion_estimada_segundos;
            }

 
            if($escena->duracion_real_minutos!=""){
                if(strlen($escena->duracion_real_minutos)<2){
                    $duracion_real.= '0'.$escena->duracion_real_minutos.':';
                }else{
                    $duracion_real.= $escena->duracion_real_minutos.':';
                }
            }else{
                $duracion_real.= "00:";
            }

            if($escena->duracion_real_segundos!=""){
              if(strlen($escena->duracion_real_segundos)<2){
                  $duracion_real.= '0'.$escena->duracion_real_segundos;
              }else{
                  $duracion_real.= $escena->duracion_real_segundos;
              }
            }else{
                $duracion_real.= "00";
            }

            /*if($escena->tiempo_post_minutos!=""){
                if(strlen($escena->tiempo_post_minutos)<2){
                    $duracion_post.= '0'.$escena->tiempo_post_minutos.':';
                }else{
                    $duracion_post.= $escena->tiempo_post_minutos.':';
                }
            }else{
                $duracion_post.= "00:";
            }

            if($escena->tiempo_post_segundos!=""){
              if(strlen($escena->tiempo_post_segundos)<2){
                  $duracion_post.= '0'.$escena->tiempo_post_segundos;
              }else{
                  $duracion_post.= $escena->tiempo_post_segundos;
              }
            }else{
                $duracion_post.= "00";
            }*/

            $duracion_post=$this->calculo_tiempo_post_redondeo($escena->tiempo_post_minutos,$escena->tiempo_post_segundos,$escena->tiempo_post_cuadros);

            if ($i%2==0){
              $b="#e6e4e5";
            }else{
              $b="#ffffff";
            }

            $locacion = strtoupper($escena->locacion);
            $set = strtoupper($escena->setnombre);
            $ubicacion = strtoupper(substr($escena->ubicacion,0,3));
            $personajes_p = strtoupper($escena->personajes_principales);
            $personajes_s = strtoupper($escena->personajes_secundarios);
            $descripcion = strtoupper($escena->descripcion);
            $elementos = strtoupper($escena->elementos);
            $tiempo = strtoupper($escena->tiempo);

            if($escena->tipo!= "Estudio"){
              $tipo = "LOC";
            }else{
              $tipo = "EST";
            }

$estilo ="";
$pdf->SetTopMargin('39px');
            $tbl4.=<<<EOD
<table border="0.5" width="720px" cellpadding="2" nobr="true">
  <tr style="background-color:$b">
    <td align="center" width="17" class="align_center" style="background-color:$color_celda">$unidad_produccion</td>
    <td align="center" width="48" class="align_center fecha_plan" style="background-color:$color_celda">$fecha_inicio_unidad</td>
    <td align="center" width="17" class="align_center">$escena->capitulo</td>
    <td align="center" width="23" class="align_center">$escena->numero_escena</td>
    <td align="center" width="37" class="align_center">$escena->libreto</td>
    <td align="center" width="24" class="align_center">$escena->dias_continuidad</td>
    <td width="57" class="cell_align_left">$locacion</td>
    <td width="56" class="cell_align_left">$set</td>
    <td width="31" align="center" class="align_center">$ubicacion</td>
    <td width="30" align="center" class="align_center">$tiempo</td>
    <td width="34" align="center" class="align_center" >$tipo</td>
    <td width="32" align="center" class="align_center">$duracion_estimada</td>
    <td width="36" align="center" class="align_center">$duracion_real</td>
    <td width="36" align="center" class="align_center">$duracion_post</td>
    <td width="75" class="cell_align_left">$personajes_p</td>
    <td width="75" class="cell_align_left">$personajes_s</td>
    <td width="82">$descripcion</td>
    <td width="80">$elementos</td>
  </tr>
</table>
EOD;
++$i;
        }
      }

    $pdf->writeHTML($tbl4, true, false, false, false, '');
    $pdf->Output('plan_general.pdf', 'I');
}



public function calculo_tiempo($escenas){
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos += $escenas[0]->segundos;

      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $escenas[0]->minutos + $minutos;

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }

      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      $tiempo = $minutos2.":".$segundos;
      return $tiempo;
    }


    public function pdf_reporte_semanal($id='', $fecha_inicio ='',$unidades=""){

  $produccion=$this->model_plan_produccion->produccion_id($id);
  $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
  $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
  $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
  
  if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
  }else{
      $nombre_ejecutivo='';
  }
  if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
  }else{
      $nombre_productor='';
  }
  if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
  }else{
      $productor_general='';
  }

  $total_escenas=0;
  $total_minutos="";
  
  $fecha = $this->model_plan_diario->semana_plan($fecha_inicio,$id);
  //echo $this->db->last_query();

  $lunes="-";
  $martes="-";
  $miercoles="-";
  $jueves="-";
  $viernes="-";
  $sabado="-";
  $domingo="-";

  $unidades = explode('.', $unidades);
  $d=86400;

  $parcial_date = strtotime($fecha[0]->fecha_inicio_semana);
  $c=0;

  while ($parcial_date < (strtotime($fecha[0]->fecha_fin_semana)+$d)) {
    $dia_2 = date("D", $parcial_date);
    switch ($dia_2) {
      case 'Mon':
        $lunes = date('d/m',$parcial_date);
        break;
      case 'Tue':
        $martes = date('d/m',$parcial_date);
        break;
      case 'Wed':
        $miercoles = date('d/m',$parcial_date);
        break;
      case 'Thu':
        $jueves = date('d/m',$parcial_date);
        break;
      case 'Fri':
        $viernes = date('d/m',$parcial_date);
        break;
      case 'Sat':
        $sabado = date('d/m',$parcial_date);
        break;
      case 'Sun':
        $domingo = date('d/m',$parcial_date);
        break;
    }
    $parcial_date+=86400;  
  }
  $segundos=0;
  $minutos=0;
  $escenas_producidas=0;
  $minutos_producidos=0;
  $segundos_producidos=0; 
  $sql2="";
  for ($g=0; $g < count($unidades)-1; $g++) {
    if($g==0){
      $sql2.=" unidad.id = ".$unidades[$g];
    }else{
      $sql2.=" OR unidad.id = ".$unidades[$g];
    } 
  }
  $unidades_semana = $this->model_plan_diario->unidades_plan_semanal($fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana, '('.$sql2.')');
  //echo $this->db->last_query();
  if($unidades_semana){

  foreach ($unidades_semana  as $unidad_semana) {
    $segundos += $unidad_semana->segundos;
    $minutos += $unidad_semana->minutos;
    $total_escenas += $unidad_semana->cantidad;
    $escenas_producidas += $unidad_semana->escenas_producidas;
    $minutos_producidos += $unidad_semana->minutos_producidos;
    $segundos_producidos += $unidad_semana->segundos_producidos;
  }
  
  while($segundos>60){
    $minutos+=1;
    $segundos-=60;
  }

  if(strlen($minutos)<2){
    $minutos = '0'.$minutos;
  }
  if(strlen($segundos)<2){
    $segundos = '0'.$segundos;
  }
  $total_minutos = $minutos.':'.$segundos;

  /*MINUTOS PRODUCIDOS*/
  while($segundos_producidos>60){
    $minutos_producidos+=1;
    $segundos_producidos-=60;
  }

  if(strlen($minutos_producidos)<2){
    $minutos_producidos = '0'.$minutos_producidos;
  }
  if(strlen($segundos_producidos)<2){
    $segundos_producidos = '0'.$segundos_producidos;
  }
  $total_producidos = $minutos_producidos.':'.$segundos_producidos;
  /*FIN MINUTOS PRODUCIDOS*/

  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='32px');
  $pdf->SetHeaderMargin('10px');
  $pdf->SetFont('', '', 7);
  $pdf->SetTopMargin('49px');
  $pdf->SetTipoPdf(2);
  $pdf->setFecha(strtoupper(date("d-M-Y",strtotime($fecha_inicio))));

  $valores = array(
    'imagen' => base_url('images/logoPdf.jpg'),
    'productor_general'=> strtoupper($productor_general),
    'nombre_produccion'=> strtoupper($produccion['0']->nombre_produccion),
    'nombre_ejecutivo'=> strtoupper($nombre_ejecutivo),
    'nombre_productor'=> strtoupper($nombre_productor),
    'total_escenas'=> $total_escenas,
    'total_minutos'=> $total_minutos,
    'escenas_producidas'=> $escenas_producidas,
    'total_producidos'=> $total_producidos
  );

  $pdf->setLunes($lunes);
  $pdf->setMartes($martes);
  $pdf->setMiercoles($miercoles);
  $pdf->setJueves($jueves);
  $pdf->setViernes($viernes);
  $pdf->setSabado($sabado);
  $pdf->setDomingo($domingo);
  $uni = $unidades_semana[0]->numero_unidad;
  $cantidad=0;
  $minutos=0;
  $segundos=0;
  $cantidad_producidas=0;
  $minutos_producidos=0;
  $segundos_producidos=0;
  foreach ($unidades_semana as $unidad_semana) {
    if($unidad_semana->numero_unidad==$uni){
      $cantidad+=$unidad_semana->cantidad;
      $minutos+=$unidad_semana->minutos;
      $segundos+=$unidad_semana->segundos;
      $cantidad_producidas += $unidad_semana->escenas_producidas;
      $minutos_producidos += $unidad_semana->minutos_producidos;
      $segundos_producidos += $unidad_semana->segundos_producidos;
    }
  }

  while($segundos>60){
    $minutos+=1;
    $segundos-=60;
  }

  if(strlen($minutos)<2){
    $minutos = '0'.$minutos;
  }
  if(strlen($segundos)<2){
    $segundos = '0'.$segundos;
  }
  $time = $minutos.':'.$segundos;


  /*TIEMPO PRODUCIDO UNIDAD*/
  while($segundos_producidos>60){
    $minutos_producidos+=1;
    $segundos_producidos-=60;
  }

  if($minutos_producidos<10){
    $minutos_producidos = '0'.$minutos_producidos;
  }
  if($segundos_producidos<10){
    $segundos_producidos = '0'.$segundos_producidos;
  }
  /*FIN TIEMPO PRODUCIDO UNIDAD*/

  $time_producido = $minutos_producidos.':'.$minutos_producidos;
  $pdf->setCantidadProducidas($cantidad_producidas);
  $pdf->setTiempoProducido($time_producido);

  $pdf->setCantidad($cantidad);
  $pdf->setNumeroUnidad($unidades_semana[0]->numero_unidad);
  $pdf->setTiempo($time);

  $pdf->setElementsHeader($valores);

  $pdf->setTipoPdf(2);
  $tbl4="";
  $locaciones_lunes = "";
  $locaciones_martes = "";
  $locaciones_miercoles = "";
  $locaciones_jueves = "";
  $locaciones_viernes = "";
  $locaciones_sabado = "";
  $locaciones_domingo = "";
  $sets_lunes = "";
  $sets_martes = "";
  $sets_miercoles = "";
  $sets_jueves = "";
  $sets_viernes = "";
  $sets_sabado = "";
  $sets_domingo = "";
  $personajes_lunes = ""; 
  $personajes_martes = "";
  $personajes_miercoles = "";
  $personajes_jueves = "";
  $personajes_viernes = "";
  $personajes_sabado = "";
  $personajes_domingo = "";
  $escenas_lunes="";
  $escenas_martes="";
  $escenas_miercoles="";
  $escenas_jueves="";
  $escenas_viernes="";
  $escenas_sabado="";
  $escenas_domingo="";
  $dias[]= "Mon";
  $dias[]= "Tue";
  $dias[]= "Wed";
  $dias[]= "Thu";
  $dias[]= "Fri";
  $dias[]= 'Sat';
  $dias[]= 'Sun';
  $estado_lunes='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_martes='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_miercoles='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_jueves='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_viernes='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_sabado='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $estado_domingo='<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
  $j=0;
  $k=0;
  $pdf->AddPage('L');   
  $unidad_temp = $unidades_semana[0]->id_unidad;

  $segundos = 0;
  $minutos = 0;
  $cantidad = 0;
  $validat =0;
  // CICLO FECHAS DE PLANES 
  for ($c=0; $c < count($unidades)-1; $c++) { 

      $locaciones_lunes = "";
      $locaciones_martes = "";
      $locaciones_miercoles = "";
      $locaciones_jueves = "";
      $locaciones_viernes = "";
      $locaciones_sabado = "";
      $locaciones_domingo = "";
      $sets_lunes = "";
      $sets_martes = "";
      $sets_miercoles = "";
      $sets_jueves = "";
      $sets_viernes = "";
      $sets_sabado = "";
      $sets_domingo = "";
      $personajes_lunes = ""; 
      $personajes_martes = "";
      $personajes_miercoles = "";
      $personajes_jueves = "";
      $personajes_viernes = "";
      $personajes_sabado = "";
      $personajes_domingo = "";
      $escenas_lunes="";
      $escenas_martes="";
      $escenas_miercoles="";
      $escenas_jueves="";
      $escenas_viernes="";
      $escenas_sabado="";
      $escenas_domingo="";

    $unidades_semana = $this->model_plan_diario->unidades_plan_semanal($fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana, " unidad.id = ".$unidades[$c]);

    if($unidades_semana){
      foreach ($unidades_semana as $unidad_semana) {

        if ($j%2==0){
          $b="#e6e4e5";
        }else{
          $b="#ffffff";
        }

        ++$j;

        // INICIO CICLO DIAS SEMANA
        for ($i=0; $i < 7; $i++) {
          $dia = date("D", strtotime($unidad_semana->fecha_inicio));
          
          // LUNES
          if($dias[$i] == "Mon"){
            if($dia == "Mon"){

              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );
              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_lunes = $datos['escenas_lista'];
              $personajes_lunes= $datos['personajes'];
              $sets_lunes= $datos['sets'];
              $locaciones_lunes= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_lunes = $datos['estado_plan'];
              }else{
                $estado_lunes = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // MARTES
          if($dias[$i] == "Tue"){
            if($dia == "Tue"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );
              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_martes = $datos['escenas_lista'];
              $personajes_martes= $datos['personajes'];
              $sets_martes= $datos['sets'];
              $locaciones_martes= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_martes = $datos['estado_plan'];
              }else{
                $estado_martes = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // MIERCOLES
          if($dias[$i] == "Wed"){
            if($dia == "Wed"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );

              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_miercoles = $datos['escenas_lista'];
              $personajes_miercoles= $datos['personajes'];
              $sets_miercoles= $datos['sets'];
              $locaciones_miercoles= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_miercoles = $datos['estado_plan'];
              }else{
                $estado_miercoles = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // JUEVES
          if($dias[$i] == "Thu"){
            if($dia == "Thu"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );

              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_jueves = $datos['escenas_lista'];
              $personajes_jueves= $datos['personajes'];
              $sets_jueves= $datos['sets'];
              $locaciones_jueves= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_jueves = $datos['estado_plan'];
              }else{
                $estado_jueves = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // VIERNES
          if($dias[$i] == "Fri"){
            if($dia == "Fri"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );

              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_viernes = $datos['escenas_lista'];
              $personajes_viernes= $datos['personajes'];
              $sets_viernes= $datos['sets'];
              $locaciones_viernes= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_viernes = $datos['estado_plan'];
              }else{
                $estado_viernes = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // SABADO
          if($dias[$i] == "Sat"){
            if($dia == "Sat"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );
              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_sabado = $datos['escenas_lista'];
              $personajes_sabado= $datos['personajes'];
              $sets_sabado= $datos['sets'];
              $locaciones_sabado= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_sabado = $datos['estado_plan'];
              }else{
                $estado_sabado = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }

          // DOMINGO
          if($dias[$i] == "Sun"){
            if($dia == "Sun"){
              $datos = array(
                'fecha' => $unidad_semana->fecha_inicio,
                'id_unidad'=> $unidad_semana->id_unidad
              );

              $escenas = $this->model_plan_diario->unidad_id_fecha($datos);
              $cantidad += count($escenas);
              $cadena_producidas = $unidad_semana->escenas_producidas.'/'.$unidad_semana->cantidad;
              $datos = $this->elementos_semana($escenas,$unidad_semana,$fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana,$cadena_producidas);
              $escenas_domingo = $datos['escenas_lista'];
              $personajes_domingo= $datos['personajes'];
              $sets_domingo= $datos['sets'];
              $locaciones_domingo= $datos['locaciones'];
              if(isset($datos['estado_plan'])){
                $estado_domingo = $datos['estado_plan'];
              }else{
                $estado_domingo = '<td style="background-color:#d0d0d0">NO INICIADO 0/0</td>';
              }
            }
          }
        }
        // FIN CICLO DIAS SEMANA

        $tbl4 = <<<EOD
<table border="0.5" width="770px" cellpadding="2">
  <tr style="background-color:$b">
    <td align="center" width="80">
      <table width="100%">
        <tr>
          $estado_lunes
        </tr>
        <tr style="background-color:gray;">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_lunes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_lunes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_lunes</td>
        </tr>
      </table>
     
    </td>
    <td width="30" align="center">$escenas_lunes</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_martes
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_martes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_martes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_martes</td>
        </tr>
      </table>
    </td>
    <td width="30" align="center">$escenas_martes</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_miercoles
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_miercoles</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_miercoles</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_miercoles</td>
        </tr>
      </table>
    </td>
    <td width="30" align="center">$escenas_miercoles</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_jueves
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_jueves</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_jueves</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_jueves</td>
        </tr>
      </table>
    </td>
    <td width="30" align="center">$escenas_jueves</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_viernes
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_viernes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_viernes</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_viernes</td>
        </tr>
      </table>
    </td>
    <td width="30" align="center">$escenas_viernes</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_sabado
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_sabado</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_sabado</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_sabado</td>
        </tr>
      </table>
    </td>
    <td width="30" align="center">$escenas_sabado</td>

    <td align="center" width="80">

      <table width="100%" >
        <tr>
          $estado_domingo
        </tr>
        <tr style="background-color:gray">
          <td>LOCACIONES</td>
        </tr>
        <tr>
          <td align="left">$locaciones_domingo</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">SETS</td>
        </tr>
        <tr>
          <td align="left">$sets_domingo</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td style="background-color:gray">PERSONAJES</td>
        </tr>
        <tr>
          <td>$personajes_domingo</td>
        </tr>
      </table></td>
    <td width="30" align="center">$escenas_domingo</td>
  </tr>
</table>
EOD;
    
      }
    }else{
      $pdf->setCantidad(0);
      $uni = $this->model_pdf->unidades_pdf($unidades[$c]);
      $pdf->setNumeroUnidad($uni[0]->numero);
      $pdf->setTiempo('00:00');
    }

    //echo $tbl4;
    $pdf->writeHTML(utf8_encode($tbl4), true, false, false, false, '');

   
    $pdf->SetTopMargin(52);
    while($segundos>60){
      $minutos+=1;
      $segundos-=60;
    }
    $pdf->setTiempo($minutos.':'.$segundos); 
    if($c < count($unidades)-2){
      $cantidad=0;
      $minutos=0;
      $segundos=0;

      $cantidad_producidas=0;
      $minutos_producidos=0;
      $segundos_producidos=0;

      $unidades_semana3 = $this->model_plan_diario->unidades_plan_semanal($fecha[0]->fecha_inicio_semana,$fecha[0]->fecha_fin_semana, " unidad.id = ".$unidades[$c+1]);
      if($unidades_semana3){
        foreach ($unidades_semana3 as $unidad_semana3) {
          if($unidad_semana3->id_unidad==$unidades[$c+1]){
            $cantidad+=$unidad_semana3->cantidad;
            $minutos+=$unidad_semana3->minutos;
            $segundos+=$unidad_semana3->segundos;
            $numero_temp = $unidad_semana3->numero_unidad;

            $cantidad_producidas+=$unidad_semana3->escenas_producidas;
            $minutos_producidos+=$unidad_semana3->minutos_producidos;
            $segundos_producidos+=$unidad_semana3->segundos_producidos;
          }
        }

        while($segundos>60){
          $minutos+=1;
          $segundos-=60;
        }

        if(strlen($minutos)<2){
          $minutos = '0'.$minutos;
        }
        if(strlen($segundos)<2){
          $segundos = '0'.$segundos;
        }

        /*TIEMPO PRODUCIDO UNIDAD*/
        while($segundos_producidos>60){
          $minutos_producidos+=1;
          $segundos_producidos-=60;
        }

        if(strlen($minutos_producidos)<2){
          $minutos_producidos = '0'.$minutos_producidos;
        }
        if(strlen($segundos_producidos)<2){
          $segundos_producidos = '0'.$segundos_producidos;
        }
        /*FIN TIEMPO PRODUCIDO UNIDAD*/
      }else{
        $numero_temp = $this->model_plan_diario->unidad_id($unidades[$c+1]);
        $numero_temp = $numero_temp[0]['numero'];
      }
      if(strlen($minutos)<2){
          $minutos='0'.$minutos;
      }
      if(strlen($segundos)<2){
          $segundos='0'.$segundos;
      }

      if(strlen($minutos_producidos)<2){
          $minutos_producidos='0'.$minutos_producidos;
      }
      if(strlen($segundos_producidos)<2){
          $segundos_producidos='0'.$segundos_producidos;
      }
      $time = $minutos.':'.$segundos;
      $time_producido = $minutos_producidos.':'.$segundos_producidos;
      $pdf->setCantidad($cantidad);
      $pdf->setNumeroUnidad($numero_temp);
      $pdf->setTiempo($time);
      $pdf->setCantidadProducidas($cantidad_producidas);
      $pdf->setTiempoProducido($time_producido);
      $pdf->writeHTML("", true, false, false, false, '');
      $pdf->AddPage('L');
      $tbl4="";
    }
    
  }                     

  // FIN DE CICLO SEMANAS UNIDAD
  //$pdf->writeHTML($tbl4, true, false, false, false, '');
  $pdf->Output('plan_diario_semanal.pdf', 'I');
  }else{
    echo "No hay planes asociados a esa semana";  
  }
}



  public function elementos_semana($escenas,$unidad_semana,$fecha_inicio,$fecha_fin,$cadena_producidas="0/0"){

    $datos['escenas_lista']="";
    $datos['personajes'] ="";
    $datos['sets'] ="";
    $datos['locaciones'] ="";
    
    if($escenas){

        foreach ($escenas as $escena) {

          $temp_loc = str_split($escena['nom_locacion'],14);
          $tem_set = str_split($escena['nom_set'],14);
          $temp_id = $escena['nom_locacion'];
          $temp_set = $escena['nom_set'];
          $datos['escenas_lista'].= $escena['numero_capitulo']."/".$escena['numero_escena'].', ';
          $count=0;

          $datos['estado_plan']='<td style="background-color:#d0d0d0">NO INICIADO</td>';
          switch ($escena['estado_plan']) {
            case 'Abierto Privado':
              $datos['estado_plan']='<td style="background-color:#c1f378">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            case 'Abierto':
              $datos['estado_plan']='<td style="background-color:#8cdd16">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            case 'Abierto':
              $datos['estado_plan']='<td style="background-color:#8cdd16">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            case 'Abierto Offline':
              $datos['estado_plan']='<td style="background-color:#8cdd16">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            case 'Cerrado':
              $datos['estado_plan']='<td style="background-color:#fee93e">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            case 'Re abierto':
              $datos['estado_plan']='<td style="background-color:#f7921e">'.strtoupper($escena['estado_plan']).' '.$cadena_producidas.'</td>';
              break;
            default:
              $datos['estado_plan']='<td style="background-color:#d0d0d0">NO INICIADO'.' '.$cadena_producidas.'</td>';
              break;
          }

          if(!strpos($datos['locaciones'],$temp_loc[0].', ')){
            foreach ($escenas as $escena2) {
              if($temp_id == $escena2['nom_locacion']){
                $count++;
              }
            }
            $datos['locaciones'] = str_replace($count.'-'.$temp_loc[0].', ',"", $datos['locaciones']);
            $datos['locaciones'].= $count.'-'.$temp_loc[0].', <br>';
          }
          $count2=0;
          if(!strpos($datos['sets'], $tem_set[0].', ')){
            foreach ($escenas as $escena3) {
              if($temp_set == $escena3['nom_set']){
                $count2++;
              }
            }
            $datos['sets'] = str_replace(strtoupper($count2.'-'.$tem_set[0]).', <br>',"", $datos['sets']);
            $datos['sets'].= strtoupper($count2.'-'.$tem_set[0]).', <br>';
          }
        }
        
        $personajes=$this->model_plan_diario->elemento_personajes_pdf($unidad_semana->fecha_inicio,$unidad_semana->id_unidad);
        if($personajes){
            $temporal = $personajes[0]['id_rol'];
            $datos['personajes'] .= '<table><tr width="100%"><td>--'.strtoupper($personajes[0]['rol_elemento']).'--</td></tr></table>';
            for ($m=0; $m < count($personajes); $m++) { 
              if($temporal != $personajes[$m]['id_rol'] AND $personajes[$m]['nombre']!=""){
                $datos['personajes'] .= '<br><table><tr width="100%"><td>--'.strtoupper($personajes[$m]['rol_elemento']).'--</td></tr></table>';
                $temporal = $personajes[$m]['id_rol'];
              }
              if(!strpos($datos['personajes'], $personajes[$m]['nombre'].', ')){
                $cantidad = false;
                if($personajes[$m]['id_rol']==4){
                  $cantidad = $this->model_plan_diario->cantidad_extras($personajes[$m]['id'],$fecha_inicio,$fecha_fin);
                }
                if($cantidad AND $cantidad[0]->cantidad!=0){
                  $datos['personajes'] .= $personajes[$m]['nombre'].' ('.$cantidad[0]->cantidad.') '.', ';
                }else{
                  $datos['personajes'] .= $personajes[$m]['nombre'].', ';
                }
                
              }
            } 
          }else{
            $datos['$personajes']="-";
          }
      }else{
        $datos['escenas_lista']="-";
        $datos['personajes'] ="-";
        $datos['sets'] ="-";
        $datos['locaciones'] ="-";
      }
      return $datos;
  }


  // REPORTE DE NOMINA DE PERSONAJES
  public function pdf_nomina($idproduccion,$fecha1,$fecha2){
    $nomina = $this->model_herramientas->nomina_personajes($idproduccion,$fecha1,$fecha2);
    $tbl4="";
    $liquidacion = 0;
    if($nomina){
      $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);

      if($ejecutivo){
          $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
      }else{
          $nombre_ejecutivo='';
      }
      if($productor){
          $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
      }else{
          $nombre_productor='';
      }
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'fecha'=> "DESDE: ".date("d-M-Y",strtotime($fecha1))." HASTA: ".date("d-M-Y",strtotime($fecha2)) 
      );
      $pdf->setTipoPdf(3);
      $pdf->setElementsHeader($valores);
      $pdf->SetTopMargin('34.5px');
      
      $pdf->SetHeaderMargin('10px');
      $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
      $pdf->SetFont('', '', 7);
      $pdf->AddPage('L');
      $pdf->SetLeftMargin('12px');
      $f=1;
      $val = false;
      foreach ($nomina as $nomina) {
        if(strtotime($nomina->fecha_inicio) >= strtotime($fecha1) AND strtotime($nomina->fecha_inicio) <= strtotime($fecha2) AND $nomina->id_tipo_contrato!=5){
        $val = true;
        $libretos = explode(',', $nomina->libretos);
        $libretos= count($libretos);

        if($nomina->fecha_inicio==""){
          $fecha="-";
        }else{
          $fecha = strtoupper($nomina->fecha_inicio);
        }

        if($nomina->fecha_inicio_2!="" AND $nomina->fecha_inicio_2!="0000-00-00"){
          $fecha = strtoupper($nomina->fecha_inicio_2);
        }

        if($nomina->fecha_finalizacion!="" AND $nomina->fecha_finalizacion!="0000-00-00"){
          $fecha_final = strtoupper($nomina->fecha_finalizacion);
        }else{
          $fecha_final = "-";
        }

        if ($f%2==0){
          $b="#e6e4e5";
        }else{
          $b="#ffffff";
        }
        $liquidacion = 0;
        if($nomina->id_tipo_contrato){
          switch ($nomina->id_tipo_contrato) {
            case 1:
              if($nomina->monto){
                $liquidacion = number_format($nomina->monto, 2, '.', ",");
              }
              break;
            case 2:
              $liquidaciones = $this->model_herramientas->liquidaciones_produccion($idproduccion);
              if($liquidaciones){
                foreach ($liquidaciones as $liquidacion) {
                  $capitulos_pagados = $this->model_herramientas->libretos_pagados($nomina->idelemento,'01'.$liquidacion->mes,'31'.$liquidacion->mes);
                  if($capitulos_pagados){
                    $capitulos_pagados = explode(',', $capitulos_pagados[0]->libretos);
                    for ($c=0; $c < count($capitulos_pagados); $c++) { 
                      str_replace($capitulos_pagados[$c], "", $nomina->libretos.',');
                    }
                  }
                }
              }
              $libretos = explode(',', $nomina->libretos);
              $libretos= count($libretos);
              $liquidacion =$nomina->monto*$libretos;
              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
              break;
            case 3:
                $liquidacion=0;
                if($nomina->monto){
                  if($libretos<11){
                    $liquidacion = $nomina->monto*$libretos;
                    $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                  }else{
                    $liquidacion = number_format($nomina->monto*13, 2, '.', ",");
                  }
                }
              break;
            case 4:
              if($nomina->monto){
                $liquidacion = $nomina->dias_trabajados*$nomina->monto;
                $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
              }
              break;
            default:
              $liquidacion = "-";
              break;
          }        
        }else{
          $liquidacion = "-";
        }

        ++$f;

        switch ($nomina->id_tipo_documento) {
          case 1:
            $doc_type = "CÉD.COL";
            break;
          case 2:
            $doc_type = "CÉD.EXT";
            break;
          case 3:
            $doc_type = "T.I";
            break;
          
          default:
            $doc_type = '-';
            break;
        }

        if($nomina->monto!="-"){
          $monto=number_format((double)$nomina->monto, 2, '.', ",");
        }else{
          $monto="-";
        }
        
        $nomina->nombre = strtoupper($nomina->nombre);
      $tbl4.=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
  <tr style="background-color:$b">
    <td align="left" width="85" class="align_center">$nomina->nombre</td>
    <td align="center" width="60" class="align_center fecha_plan">$nomina->rol</td>
    <td align="left" width="57" >$nomina->actor_nombre</td>
    <td align="left" width="58" >$nomina->actor_apellido</td>
    <td align="left" width="40" >$doc_type</td>
    <td align="left" width="58" >$nomina->documento_actor</td>
    <td align="left" width="58" class="align_center">$nomina->contrato</td>
    <td align="right" width="50" class="align_center">$monto</td>
    <td align="left" width="45" class="align_center">$nomina->tipo_moneda</td>
    <td align="left" width="48" class="align_center">$fecha</td>
    <td align="left" width="48" class="align_center">$fecha_final</td>
    <td align="right" width="53" class="align_center">$liquidacion</td>
    <td align="left" width="27" class="align_center">$libretos</td>
    <td align="left" class="cell_align_left" width="95">$nomina->libretos</td>
  </tr>
</table>
EOD;
    }
    }
    if($val){
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $pdf->Output('nomina_personajes.pdf', 'I');
    }else{
      echo "No hay resultado";
    }
    }
  }

    // REPORTE DE NOMINA DE PERSONAJES
  public function pdf_nomina_mensual($idproduccion,$fecha1,$fecha2){

    $nomina = $this->model_herramientas->nomina_personajes_mensuales($idproduccion,$fecha1,$fecha2);
    $tbl4="";
    $liquidacion = 0;
    if($nomina){
      $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);

      if($ejecutivo){
          $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
      }else{
          $nombre_ejecutivo='';
      }
      if($productor){
          $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
      }else{
          $nombre_productor='';
      }
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'fecha'=> "DESDE: ".date("d-M-Y",strtotime($fecha1))." HASTA: ".date("d-M-Y",strtotime($fecha2)) 
      );
      $pdf->setTipoPdf(3);
      $pdf->setElementsHeader($valores);
      $pdf->SetTopMargin('34.5px');
      
      $pdf->SetHeaderMargin('10px');
      $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
      $pdf->SetFont('', '',6);
      $pdf->AddPage('L');
      $pdf->SetLeftMargin('12px');
      $f=1;
      $val = false;
      $t=0;
      $total_liquidacion=0;
      foreach ($nomina as $nomina) {
        //if(strtotime($nomina->fecha_inicio) >= strtotime($fecha1) AND strtotime($nomina->fecha_inicio) <= strtotime($fecha2) ){
        $val = true;
        $libretos =0;
        if($nomina->libretos!=""){
          $libretos = explode(',', $nomina->libretos);
          $libretos= count($libretos);
        }
        

        if($nomina->fecha_inicio==""){
          $fecha="-";
        }else{
          $fecha = strtoupper($nomina->fecha_inicio);
        }

        if($nomina->fecha_inicio_2!="" AND $nomina->fecha_inicio_2!="0000-00-00"){
          $fecha = strtoupper($nomina->fecha_inicio_2);
        }

        if($nomina->fecha_liquidacion!="" AND $nomina->fecha_liquidacion!="0000-00-00"){
          $fecha_final = strtoupper($nomina->fecha_liquidacion);
        }else{
          $fecha_final = "-";
        }

        if ($f%2==0){
          $b="#e6e4e5";
        }else{
          $b="#ffffff";
        }
        $liquidacion = 0;
        $tasa_cambio='';
        if($nomina->id_tipo_contrato){

              if($nomina->monto){
                 $descuentos = $this->model_herramientas->descuento_personaje($nomina->idelemento,date("Y-m",strtotime($fecha1)));
                 if($descuentos){
                    if($descuentos['0']->tasa_cambio){
                       $monto=$nomina->monto*$descuentos['0']->tasa_cambio;
                     }else{
                      $monto=$nomina->monto;
                     }
                 }else{
                   $monto=$nomina->monto;
                 }
                     
                //$valor_dia = $nomina->monto / 30;
                 $valor_dia = $monto/ 30;
                $segundos= abs(strtotime($fecha));

                $dias_mes = cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($fecha1)) , date("Y",strtotime($fecha1)));


                if( date("m",strtotime($fecha1)) <= date("m",strtotime($fecha))  AND date("Y",strtotime($fecha)) == date("Y",strtotime($fecha1)) ){


                  if(date("d",strtotime($fecha))<30){
                    $dias_pagar = 30 - date("d",strtotime($fecha))+1;
                  }else{
                    $dias_pagar = 1;
                  }
                  // echo "Primera validacion";
                }else{
                  // echo "Segunda validacion";
                  if($fecha_final!="-" AND date("m",strtotime($fecha_final)) == date("m",strtotime($fecha1)) AND date("Y",strtotime($fecha_final)) == date("Y",strtotime($fecha1)) ){
                    if(date("d",strtotime($fecha_final))>30){
                      // echo "Tercera validacion";
                      $dias_pagar=30;
                    }else{
                      // echo "Cuarta validacion";
                      $dias_pagar=date("d",strtotime($fecha_final));
                    }
                  }else{
                     if($fecha_final!="-" AND date(strtotime($fecha_final))<date(strtotime($fecha1.'-01'))){
                        $dias_pagar=0;
                       }else{
                          $dias_pagar=30;   
                       }
                  }
                }

               
                 
                if($descuentos){
                  $tasa_cambio=$descuentos['0']->tasa_cambio;
                  if($descuentos[0]->observaciones){
                    $observaciones=$descuentos[0]->observaciones;  
                  }else{
                    $observaciones='';
                  }
                  $descuentos = $descuentos[0]->descuento;
                }else{
                  $descuentos =0;
                  $observaciones='';
                }


                //echo  $dias_pagar.'</br>';
                //$liquidacion = number_format( (($nomina->monto*$dias_pagar)/30) - $descuentos  , 2, '.', ",");
                //$liquidacion = (($nomina->monto*$dias_pagar)/30) - $descuentos;
                $liquidacion = (($monto*$dias_pagar)/30) - $descuentos;
                $total_liquidacion+=$liquidacion;
                $liquidacion=number_format((double)$liquidacion, 0, '.', ",");
                $descuentos = number_format($descuentos  , 0, '.', ",");

              }
        }else{
          $liquidacion = "-";
        }

        $t=$t+$liquidacion;

        ++$f;

        switch ($nomina->id_tipo_documento) {
          case 1:
            $doc_type = "CÉD.COL";
            break;
          case 2:
            $doc_type = "CÉD.EXT";
            break;
          case 3:
            $doc_type = "T.I";
            break;
          
          default:
            $doc_type = '-';
            break;
        }

        if($nomina->monto!="-"){
          $monto=number_format((double)$nomina->monto, 0, '.', ",");
        }else{
          $monto="-";
        }


        

      $tbl4.=<<<EOD
<table border="0.5" width="50%" cellpadding="2">
  <tr style="background-color:$b">
    <td align="left" width="60" class="align_center">$nomina->nombre</td>
    <td align="center" width="52" class="align_center fecha_plan">$nomina->rol</td>
    <td align="left" width="47" >$nomina->actor_nombre</td>
    <td align="left" width="52" >$nomina->actor_apellido</td>
    <td align="left" width="40" >$doc_type</td>
    <td align="left" width="50" >$nomina->documento_actor</td>
    <td align="left" width="50" class="align_center">$nomina->contrato</td>
    <td align="right" width="50" class="align_center">$monto</td>
    <td align="left" width="42" class="align_center">$nomina->tipo_moneda</td>
    <td align="right" width="30" class="align_center">$tasa_cambio</td>
    <td align="left" width="47" class="align_center">$fecha</td>
    <td align="left" width="47" class="align_center">$fecha_final</td>
    <td align="right" width="53" class="align_center">$liquidacion</td>
    <td align="right" width="53" class="align_center">$descuentos</td>
    <td align="right" width="53" class="align_center">$observaciones</td>
    <td align="left" width="20" class="align_center">$libretos</td>
    <td align="left" class="cell_align_left" width="60">$nomina->libretos</td>
  </tr>
</table>
EOD;
    //}
    }
                $total_liquidacion=number_format((double)$total_liquidacion, 0, '.', ",");
    $tbl4.=<<<EOD
<table border="0.5" width="50%" cellpadding="2">
  <tr style="background-color:#3BB4E4">
    <td align="left" width="60" class="align_center">TOTAL</td>
    <td align="center" width="50" class="align_center fecha_plan"></td>
    <td align="left" width="47" ></td>
    <td align="left" width="52" ></td>
    <td align="left" width="40" ></td>
    <td align="left" width="50" ></td>
    <td align="left" width="50" class="align_center"></td>
    <td align="right" width="50" class="align_center"></td>
    <td align="left" width="42" class="align_center"></td>
    <td align="right" width="30" class="align_center"></td>
    <td align="left" width="48" class="align_center"></td>
    <td align="left" width="48" class="align_center"></td>
    <td align="right" width="53" class="align_center">$total_liquidacion</td>
    <td align="right" width="53" class="align_center"></td>
    <td align="right" width="53" class="align_center"></td>
    <td align="left" width="20" class="align_center"></td>
    <td align="left" class="cell_align_left" width="60"></td>
  </tr>
</table>
EOD;
    if($val){
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $pdf->Output('nomina_personajes.pdf', 'I');
    }else{
      echo "No hay resultado";
    }
    }
  }

  public function pdf_personajes_elementos_semanal ($idproduccion='',$fecha_inicio="", $opcion, $unidades){
    $fecha = $this->model_plan_diario->semana_plan($fecha_inicio,$idproduccion);
    $fecha_temp = strtotime($fecha[0]->fecha_inicio_semana);
    $cadena="";
    $cadena2="";
    $tbl4="";
    $int = strtotime($fecha[0]->fecha_inicio_semana);
    $int += 86400; 

    $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);

    if($ejecutivo){
        $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
        $nombre_ejecutivo='';
    }
    if($productor){
        $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
        $nombre_productor='';
    }
    if($productor_general){
        $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
        $productor_general='';
    }
    $j=1;
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
    $sql2="";
    $unidades = explode('.', $unidades);
    for ($g=0; $g < count($unidades)-1; $g++) {
      if($g==0){
        $sql2.="  unidad.id = ".$unidades[$g];
      }else{
        $sql2.=" OR unidad.id = ".$unidades[$g];
      } 
    }
    $sql2 = " AND (".$sql2.") ";

    while($fecha_temp <= strtotime($fecha[0]->fecha_fin_semana)) {
      
      $personajes_semanal = $this->model_pdf->personajes_semanal($idproduccion,date("Y-m-d",$fecha_temp), $sql2);
      $elementos_semanal = $this->model_pdf->elementos_semanal($idproduccion,date("Y-m-d",$fecha_temp), $sql2);
    
      $id_temp = 0; 
      $id_temp2=0;
      $contador=0;
      $v=true;
      $v2=true;

      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      if($personajes_semanal AND $opcion ==1){
      
      $id_temp2 = $personajes_semanal[0]->id;

        foreach ($personajes_semanal as $personaje_semanal) {
          
          $cadena .= '<tr style="vertical-align:middle">';

          if($v){
            $w=date("w",$fecha_temp);
            $cadena .='<td width="80" align="center" rowspan="'.count($personajes_semanal).'">'.strtoupper($dias[$w])."</td>";
            $cadena .= '<td width="80" align="center" rowspan="'.count($personajes_semanal).'">'.strtoupper(date("d-M-Y",$fecha_temp))."</td>";
            $v=false;
          }
          $contador = $this->model_pdf->filas_pdf($idproduccion,date("Y-m-d",$fecha_temp), $personaje_semanal->id);
          
          if($id_temp!=$personaje_semanal->id) {
            if ($j%2==0){
            $b="#e6e4e5";
            } else {
              $b="#ffffff";
            }
            $cadena.='<td align="center" style="background-color:'.$b.'" width="20" rowspan="'.$contador[0]->filas.'">'.$personaje_semanal->numero."</td>";
            ++$j;
          } 


          $cadena.='<td style="background-color:'.$b.'" width="65">'.strtoupper($personaje_semanal->rol)."</td>";
          $id_temp = $personaje_semanal->id;

          $cadena.='<td style="background-color:'.$b.'" width="140">';
          if($personaje_semanal->cantidad_extras!=""){
            $cadena.= $personaje_semanal->cantidad_extras.' ';
          }
          $cadena.= strtoupper($personaje_semanal->nombre)."</td>";
          
          $cadena.='<td style="background-color:'.$b.'" width="200">'.$personaje_semanal->escenas."</td>";
          $cadena.='<td style="background-color:'.$b.'" width="190"></td>';
          $cadena.="</tr>";
        }
       
      } 

      $id_temp = 0; 
      $id_temp2=0;
      $contador=0;
      $v2=true;

      if($elementos_semanal AND $opcion ==2){
      
      $id_temp2 = $elementos_semanal[0]->id;

        foreach ($elementos_semanal as $elemento_semanal) {


        if ($j%2==0){
          $t="#000000";
        }else{
          $t="#ffffff";
        }

        $cadena2 .= '<tr style="vertical-align:middle">';

          if($v2){
             $w=date("w",$fecha_temp);
            $cadena2.='<td width="80" align="center" rowspan="'.count($elementos_semanal).'">'.strtoupper($dias[$w])."</td>";
            $cadena2.= '<td width="80" align="center" rowspan="'.count($elementos_semanal).'">'.strtoupper(date("d-M-Y",$fecha_temp))."</td>";
            $v2=false;
          }

          $contador = $this->model_pdf->filas_pdf_elementos($idproduccion,date("Y-m-d",$fecha_temp), $elemento_semanal->id);   
          if($id_temp!=$elemento_semanal->id) {
            $cadena2.='<td align="center" width="20" rowspan="'.$contador[0]->filas.'">'.$elemento_semanal->numero."</td>";
          } 

          $id_temp = $elemento_semanal->id;
          $cadena2.='<td width="170">'.strtoupper($elemento_semanal->nombre)."</td>";
          $cadena2.='<td width="230">'.$elemento_semanal->escenas."</td>";
          $cadena2.='<td width="200"></td>';
          $cadena2.="</tr>";
        }
      } 
      $fecha_temp+=86400;
    }


    $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'fecha'=>strtoupper(date("d-M-Y",strtotime($fecha_inicio))),
    );
if($cadena2!="" OR $cadena!=""){    
    $pdf->setTipoPdf(4);
    

    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('10px');
    $pdf->SetTopMargin('40px');

    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
    $pdf->SetFont('', '', 7);
if($cadena!=""){     
    $pdf->setTitulo("PERSONAJES");   
    $pdf->AddPage('L');
    $tbl4.=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
  $cadena
</table>
EOD;
  $pdf->writeHTML($tbl4, true, false, false, false, '');
}
if($cadena2!=""){
  $pdf->setTitulo("ELEMENTOS");   
  $tbl4="";
  $pdf->setTipoHeader(1);
  $pdf->AddPage('L');
  $tbl4.=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
  $cadena2
</table>
EOD;

    $pdf->writeHTML($tbl4, true, false, false, false, '');
}
    $pdf->Output('personajes_elementos_semanal.pdf', 'I');  
  }else{
    echo "No hay elementos o personajes asignados en esta semana";
  }

  }


 public function pdf_elementos($idproduccion,$carga,$id_categoria,$limite_inferior,$limite_superior,$roles=''){

    ini_set('memory_limit', '-1');
    set_time_limit(10000000);
    $cadena = "";
    $tbl4="";
    $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
    $valida=0;
    if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
      $nombre_ejecutivo='';
    }
    if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
      $nombre_productor='';
    }
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }
    $categorias_elementos=$this->model_elementos->categorias_elementos($idproduccion);
    $cadena_tipo ="";

    if($carga==1){
      $cadena_tipo.= 'TOTAL ';
      foreach ($categorias_elementos as $categoria_elementos) {
        if($categoria_elementos['id']==$id_categoria){
          $cadena_tipo.= $categoria_elementos['tipo'].'S: ';
          break;
        }
      }
    } else if($carga==2){
      $cadena_tipo.= 'TOTAL LOCACIONES: ';
    } else{
      $cadena_tipo.= 'TOTAL SETS: ';
    }

    $capitulos = $this->model_capitulos->capitulos_produccion_2($idproduccion);

    if($limite_inferior=='null'){
         $limite_inferior=$capitulos['0']['numero'];
     }

     if($limite_superior=='null'){
      $ultimo=end($capitulos);
      $limite_superior=$ultimo['numero'];
     }
    $total_libretos = $this->model_elementos->total_libretos_limite($idproduccion,$limite_inferior,$limite_superior);
    if($carga!=3 and $carga!=4){
        $sql='';
        $roles_selec=explode('-',$roles);
        if($roles){
          $sql.=' AND (';
            $cont=0;
          foreach ($roles_selec as $r) {
            if($cont==0){
              $sql.=' r.rol="'.$r.'"';  
              $cont++;
            }else{
              if($r){
               $sql.=' OR r.rol="'.$r.'"';  
              }  
            }
            
          }
          $sql.=' )';
        }
      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'limite'=>'LIBRETO DESDE: '.$limite_inferior.' HASTA: '.$limite_superior,
        'total_libretos'=>$total_libretos[0]->total_libretos,
        'total_escenas'=>$total_libretos[0]->total_escenas,
        'total_elementos'=>$cadena_tipo.' '.count($elementos=$this->model_elementos->elementos_limite_libretos($idproduccion,$id_categoria,$limite_inferior,$limite_superior,$sql))
      );
    }elseif($carga==3){
      $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'limite'=>'LIBRETO DESDE: '.$limite_inferior.' HASTA: '.$limite_superior,
        'total_libretos'=>$total_libretos[0]->total_libretos,
        'total_escenas'=>$total_libretos[0]->total_escenas,
        'total_elementos'=>$cadena_tipo.' '.count($elementos=$this->model_elementos->set_escena($id_categoria))
      );
    }else{
       $valores = array(
        'imagen' => base_url('images/logoPdf.jpg'),
        'productor_general'=>strtoupper($productor_general),
        'nombre_produccion'=>$produccion['0']->nombre_produccion,
        'nombre_ejecutivo'=>$nombre_ejecutivo,
        'centro'=>strtoupper($produccion['0']->centro),
        'nombre_productor'=>$nombre_productor,
        'limite'=>'LIBRETO DESDE: '.$limite_inferior.' HASTA: '.$limite_superior,
        'total_libretos'=>$total_libretos[0]->total_libretos,
        'total_escenas'=>$total_libretos[0]->total_escenas,
        'total_elementos'=> $cadena_tipo.' '.count($elementos=$this->model_elementos->sets_produccion_limite($idproduccion,$limite_inferior,$limite_superior))
      );
    }
    $i=1;
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTopMargin('31px');
    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('10px');
    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
    $pdf->SetFont('', '', 7);
    $tbl4='';
    $limite_elementos=0;
    $total_cap=$this->model_capitulos->agregar_capitulo($idproduccion);
    $total_cap=count($total_cap);

    if($carga==1){
      $pdf->setTipoPdf(5);
      $add_limit = 37;
      $validacion = false;
      foreach ($categorias_elementos as $c) {
        if($c['id']==$id_categoria) {
          $pdf->setTitulo(" ELEMENTOS (".strtoupper($c['tipo']).")");
          $titulo = strtoupper($c['tipo']);
          $add_limit = 20;
          $valida=1;
        }else{
          $add_limit = 32;
        }
      }

      if($categorias_elementos[0]['id']==$id_categoria){
        $validacion = true;
      }else{
        $add_limit = 34;
      }
        $pdf->setTipoPdf(5);
        $pdf->AddPage('L');
        $pdf->SetLeftMargin('12px');
        $tbl_persona='<table  border="1" width="100%" cellpadding="2">';
        
        $limite_elementos=0;
        $sql='';
        $roles_selec=explode('-',$roles);
        if($roles){
          $sql.=' AND (';
            $cont=0;
          foreach ($roles_selec as $r) {
            if($cont==0){
              $sql.=' r.rol="'.$r.'"';  
              $cont++;
            }else{
              if($r){
               $sql.=' OR r.rol="'.$r.'"';  
              }  
            }
            
          }
          $sql.=' )';
        }
        $elementos=$this->model_elementos->elementos_limite($idproduccion,$id_categoria,$limite_elementos,$limite_inferior,$limite_superior,$sql);
        //echo $this->db->last_query();
        $in = 0;
        if($elementos){
        // CILCO INCIAL DE ELEMENTOS
        while($elementos){
          $limit=0;
          $total_cap2=0;
          $limit_entro=0;
          $cont=0;
          // CICLO DE CAPITULOS

          while($total_cap2<=$total_cap){
            $total_cap2+=$add_limit;
            $capitulos=$this->model_capitulos->capitulos_limite($idproduccion,$limit,$add_limit,$limite_inferior,$limite_superior);
            $limit+=$add_limit;

            if($limit>$total_cap AND $limit_entro==0){
              $limit+=$add_limit;
              $total_cap2=$total_cap;
              $limit_entro=1;
            }
            // CICLO PARCIAL DE ELEMENTOS
            foreach ($elementos as $e) {
              if($capitulos){ 
                if($cont==0){

                  if($in==0){
                    $cell_size = 108;
                  }else{
                    $cell_size = 110;
                  }

                  $tbl_persona.='<tr style="background-color:#535357;color:#FFFFFF;"><td  width="'.$cell_size .'">'.$titulo.'</td>';
                  if($validacion){
                    $tbl_persona.='<td width="60">ROL</td>';
                  }
                  if($in==0){
                    $tbl_persona.='<td width="37">TOTALES</td>' ; 
                  }
                  foreach ($capitulos as $c) {
                    $numero=$c['numero'];
                    $tbl_persona.='<td width="18" align="center">'.$numero.'</td>';
                  }
                  $tbl_persona.='</tr>';
                }
                $nombre=$e['nombre']; 
                $tbl_persona.='<tr><td width="'.$cell_size.'">'.strtoupper($nombre).'</td>';

                if($validacion){
                  $tbl_persona.='<td width="60">'.strtoupper($e['rol']).'</td>';
                }

                
                if($in==0){

                  /*PORCENTAJE Y CANTIDAD USOS ELEMENTOS*/
                $uso_total=$this->model_elementos->escenas_has_elementos_limit($e['id'],$limite_inferior,$limite_superior);
                $total_escenas=$this->model_elementos->total_escenas_limite($idproduccion,$limite_inferior,$limite_superior);
                if($uso_total==0){
                  $por=0;
                }else{
                  $por=($uso_total*100)/$total_escenas[0]->total;
                  $por=round($por);  
                }

                  $tbl_persona.='<td width="18">'.$uso_total.'</td>';
                  $tbl_persona.='<td width="19">'.$por.'%</td>';
                }

                // CICLO PACIAL DE LIBRETOS
                foreach ($capitulos as $c) {
                  $uso=$this->model_elementos->uso($idproduccion,$c['id'],$e['id']);
                  if($uso[0]->uso){
                    $uso=$uso[0]->uso;
                    $tbl_persona.='<td width="18">'.$uso.'</td>';
                  }else{
                    $tbl_persona.='<td width="18">0</td>';    
                  }
                }

                $tbl_persona.='</tr>';
                $cont++;
              }  
            }
            $cont=0;
            $in = 1;
            $add_limit = 34;
          }
          $in=0;
          $add_limit = 32;
          $limite_elementos+=30;
          $sql='';
          $roles_selec=explode('-',$roles);
          if($roles){
            $sql.=' AND (';
              $cont=0;
            foreach ($roles_selec as $r) {
              if($cont==0){
                $sql.=' r.rol="'.$r.'"';  
                $cont++;
              }else{
                if($r){
                 $sql.=' OR r.rol="'.$r.'"';  
                }  
              }
              
            }
            $sql.=' )';
          }
          $elementos=$this->model_elementos->elementos_limite($idproduccion,$id_categoria,$limite_elementos,$limite_inferior,$limite_superior,$sql);
        } 
        $tbl_persona.='</table>';
        $tbl4.=<<<EOD
        $tbl_persona
EOD;
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        $pdf->Output('elementos.pdf', 'I');
    }else{
      echo "No hay resultados";
    }

    }else if($carga==2){
      $in = 0;
      $pdf->setTipoPdf(5);
      $limite_elementos=0;
      $sql ="limit ".$limite_elementos.",30";
      $locacion=$this->model_elementos->locacion_usos($idproduccion,$limite_elementos);
      $pdf->setTitulo(' LOCACIONES');
      $pdf->AddPage('L');
      $pdf->SetLeftMargin('12px');
      if($locacion){
        $tbl_locacion='<table  border="1" width="100%" cellpadding="2">';
        while($locacion){
          $limit=0;
          $total_cap2=0;
          $limit_entro=0;
          $cont=0;
          // CICLO DE CAPITULOS
          while($total_cap2<=$total_cap){
            if($in==0){
              $add_limit=35;
            }else{
              $add_limit=37;
            }
            $total_cap2+=$add_limit;

            $capitulos=$this->model_capitulos->capitulos_limite($idproduccion,$limit,$add_limit,$limite_inferior,$limite_superior);
            $limit+=37;
            foreach ($locacion as $l) {
              if($capitulos){ 
                if($cont==0){
                  $tbl_locacion.='<tr style="background-color:#535357;color:#FFFFFF;"><td  width="110">LOCACIÓN</td>';
                  if($in==0){
                    $tbl_locacion.='<td width="36">TOTALES</td>' ; 
                  }

                  foreach ($capitulos as $c) {
                    $numero=$c['numero'];
                    $tbl_locacion.='<td width="18" align="center">'.$numero.'</td>';
                  }

                  $tbl_locacion.='</tr>';
                }
                $nombre = strtoupper($l['nombre']);
                $tbl_locacion.='<tr><td width="110">'.$nombre.'</td>';
                if($in==0){
                    $total_escenas=$this->model_elementos->total_escenas_limite($idproduccion,$limite_inferior,$limite_superior);
                    /*PORCENTAJE Y CANTIDAD USOS ELEMENTOS*/
                    $uso_total = $this->model_elementos->escenas_locacion_limite($l['id'],$limite_inferior,$limite_superior);
                    $uso_total = $uso_total[0]->total;
                    if($uso_total==0){
                      $por=0;
                    }else{
                      $por=($uso_total*100)/$total_escenas[0]->total;
                      $por=round($por);  
                    }
                    $tbl_locacion.='<td width="18">'.$uso_total.'</td>';
                    $tbl_locacion.='<td width="19">'.$por.'%</td>';
                }

                // CICLO PACIAL DE LIBRETOS
                foreach ($capitulos as $c) {
                  $uso =$this->model_elementos->usos_locacion($c['id'],$l['id']);
                  if($uso[0]->uso){
                    $uso=$uso[0]->uso;
                    $tbl_locacion.='<td width="18">'.$uso.'</td>';
                  }else{
                    $tbl_locacion.='<td width="18">0</td>';    
                  }
                }
                $tbl_locacion.='</tr>';
                $cont++;
            }
          }
          $in = 1;
          $cont=0;
        }
        $in=0;
        $limite_elementos+=30;
        $sql ="limit ".$limite_elementos.",30";
        $locacion=$this->model_elementos->locacion_usos($idproduccion,$limite_elementos);
      }
      $tbl_locacion.='</table>';
      $tbl4.=<<<EOD
      $tbl_locacion
EOD;
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $pdf->Output('elementos.pdf', 'I');
      }else{
        echo "No hay resultados";
      }
    }elseif($carga==3) {
      $in=0;
      $pdf->setTipoPdf(5);
      $sql ="limit ".$limite_elementos.",30";
      $locacion_t=$this->model_escenas->locacion_id($id_categoria);
      $sql ="limit ".$limite_elementos.",30";
      $sets=$this->model_elementos->set_escena_limite($id_categoria,$sql);  
      $pdf->setTitulo(' SETS - '.$locacion_t[0]['nombre']);
      //echo $pdf->setTitulo(' SETS - '.$locacion_t[0]['nombre']);
      $pdf->AddPage('L');
      $pdf->SetLeftMargin('12px');
      if($sets){
        $tbl_set='<table  border="1" width="100%" cellpadding="2">';
        while($sets){
          $limit=0;
          $total_cap2=0;
          $limit_entro=0;
          $cont=0;
          // CICLO DE CAPITULOS
          while($total_cap2<=$total_cap){
            if($in==0){
              $add_limit=35;
            }else{
              $add_limit=37;
            }
            $total_cap2+=$add_limit;
            $capitulos=$this->model_capitulos->capitulos_limite($idproduccion,$limit,$add_limit,$limite_inferior,$limite_superior);
            $limit+=37;
            foreach ($sets as $l) {
              if($capitulos){ 
                if($cont==0){
                  $tbl_set.='<tr style="background-color:#535357;color:#FFFFFF;"><td  width="110">SET</td>';
                  if($in==0){
                    $tbl_set.='<td width="36">TOTALES</td>' ; 
                  }
                  foreach ($capitulos as $c) {
                    $numero=$c['numero'];
                    $tbl_set.='<td width="18" align="center">'.$numero.'</td>';
                  }
                  $tbl_set.='</tr>';
                }

                $nombre = strtoupper($l['nombre']);
                $tbl_set.='<tr><td width="110">'.$nombre.'</td>';
                if($in==0){
                    $total_escenas=$this->model_elementos->total_escenas_limite($idproduccion,$limite_inferior,$limite_superior);
                    /*PORCENTAJE Y CANTIDAD USOS ELEMENTOS*/
                    $uso_total = $this->model_elementos->escenas_set_limite($l['id'],$limite_inferior,$limite_superior);
                    $uso_total = $uso_total[0]->total;
                    if($uso_total==0){
                      $por=0;
                    }else{
                      $por=($uso_total*100)/$total_escenas[0]->total;
                      $por=round($por);  
                    }
                    $tbl_set.='<td width="18">'.$uso_total.'</td>';
                    $tbl_set.='<td width="19">'.$por.'%</td>';
                }

                // CICLO PACIAL DE LIBRETOS
                foreach ($capitulos as $c) {
                  $uso =$this->model_elementos->usos_set($c['id'],$l['id']);
                  if($uso[0]->uso){
                    $uso=$uso[0]->uso;
                    $tbl_set.='<td width="18">'.$uso.'</td>';
                  }else{
                    $tbl_set.='<td width="18">0</td>';    
                  }
                }
                $tbl_set.='</tr>';
                $cont++;
              }
            }
            $in = 1;
            $cont=0;
          }
          $in=0;
          $limite_elementos+=30;
          $sql ="limit ".$limite_elementos.",30";
          $sets=$this->model_elementos->set_escena_limite($id_categoria,$sql);
        }
        $tbl_set.='</table>';
        $tbl4.=<<<EOD
        $tbl_set
EOD;
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        $pdf->Output('elementos.pdf', 'I');
      }else{
        echo "No hay resultados";
      }
    }elseif($carga==4) {  
      $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='38px');
      $in=0;
      $pdf->setTipoPdf(5);
      $sql ="limit ".$limite_elementos.",30";
      $locacion_t=$this->model_escenas->locacion_id($id_categoria);
      $sql ="limit ".$limite_elementos.",30";
      $sets=$this->model_elementos->sets_produccion_limite2($idproduccion,$limite_inferior,$limite_superior,$sql);      
      $pdf->setTitulo(' SETS ');
      $pdf->AddPage('L');
      $pdf->SetLeftMargin('12px');
      if($sets){
        $tbl_set='<table  border="1" width="100%" cellpadding="2">';
        while($sets){
          $limit=0;
          $total_cap2=0;
          $limit_entro=0;
          $cont=0;
          // CICLO DE CAPITULOS
          while($total_cap2<=$total_cap){
            if($in==0){
              $add_limit=28;
            }else{
              $add_limit=30;
            }
            $total_cap2+=$add_limit;
            $capitulos=$this->model_capitulos->capitulos_limite($idproduccion,$limit,$add_limit,$limite_inferior,$limite_superior);
            $limit+=37;
            foreach ($sets as $l) {
              if($capitulos){ 
                if($cont==0){
                  $tbl_set.='<tr style="background-color:#535357;color:#FFFFFF;"><td  width="110">SET</td><td width="110">LOCACIÓN</td>';
                  if($in==0){
                    $tbl_set.='<td width="36">TOTALES</td>' ; 
                  }
                  foreach ($capitulos as $c) {
                    $numero=$c['numero'];
                    $tbl_set.='<td width="18" align="center">'.$numero.'</td>';
                  }
                  $tbl_set.='</tr>';
                }

                $nombre = strtoupper($l['nombre']);
                $tbl_set.='<tr><td width="110">'.$nombre.'</td><td width="110">'.strtoupper($l['nombre_locacion']).'</td>';
                if($in==0){
                    $total_escenas=$this->model_elementos->total_escenas_limite($idproduccion,$limite_inferior,$limite_superior);
                    /*PORCENTAJE Y CANTIDAD USOS ELEMENTOS*/
                    $uso_total = $this->model_elementos->escenas_set_limite($l['id'],$limite_inferior,$limite_superior);
                    $uso_total = $uso_total[0]->total;
                    if($uso_total==0){
                      $por=0;
                    }else{
                      $por=($uso_total*100)/$total_escenas[0]->total;
                      $por=round($por);  
                    }
                    $tbl_set.='<td width="18">'.$uso_total.'</td>';
                    $tbl_set.='<td width="19">'.$por.'%</td>';
                }

                // CICLO PACIAL DE LIBRETOS
                foreach ($capitulos as $c) {
                  $uso =$this->model_elementos->usos_set($c['id'],$l['id']);
                  if($uso[0]->uso){
                    $uso=$uso[0]->uso;
                    $tbl_set.='<td width="18">'.$uso.'</td>';
                  }else{
                    $tbl_set.='<td width="18">0</td>';    
                  }
                }
                $tbl_set.='</tr>';
                $cont++;
              }
            }
            $in = 1;
            $cont=0;
          }
          $in=0;
          $limite_elementos+=30;
          $sql ="limit ".$limite_elementos.",30";
          $sets=$this->model_elementos->sets_produccion_limite2($idproduccion,$limite_inferior,$limite_superior,$sql);      
          //$sets=$this->model_elementos->set_escena_limite($id_categoria,$sql);
          //$sets=$this->model_elementos->sets_produccion_limite($idproduccion,$limite_inferior,$limite_superior);      
        }
        $tbl_set.='</table>';
        $tbl4.=<<<EOD
        $tbl_set
EOD;
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        $pdf->Output('elementos.pdf', 'I');
      }else{
        echo "No hay resultados";
      }
    }

  }

  
    public function pdf_postproduccion_capitulos($idproduccion){
    ini_set('memory_limit', '-1');
    set_time_limit(10000000);
    $cadena = "";
    $tbl4="";
    $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);

    if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
      $nombre_ejecutivo='';
    }
    if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
      $nombre_productor='';
    }
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }
    $categorias_elementos=$this->model_elementos->categorias_elementos($idproduccion);
     
   $capitulos=$this->model_post_produccion->capitulos_escenas2($idproduccion);
   $capitulos_escenas_prod=$this->model_post_produccion->capitulos_escenas_prod($idproduccion);
   $capitulos_escenas_post=$this->model_post_produccion->capitulos_escenas_post($idproduccion);
   $id_user=$this->session->userdata('id_pruduction_suite');

    $min_produccidos=$this->calculo_tiempo2($capitulos_escenas_prod['0']->minutos,$capitulos_escenas_prod['0']->segundos);
    $mins_post_produccidos=$this->calculo_tiempo2($capitulos_escenas_post['0']->minutos,$capitulos_escenas_post['0']->segundos);
    $minutos=$capitulos_escenas_prod['0']->minutos-$capitulos_escenas_post['0']->minutos;
    $segundos=$capitulos_escenas_prod['0']->segundos-$capitulos_escenas_post['0']->segundos;
    if($minutos<0 or $segundos<0){
       $negativo='-';
    }else{
        $negativo='';
    } 
    if($minutos<0){
      $minutos=$minutos*-1;
    }
    if($segundos<0){
      $segundos=$segundos*-1;
    }
    
    $minutos=$this->calculo_tiempo2($minutos,$segundos);
    $total_escenas=$capitulos_escenas_prod['0']->total_escenas;
    $escenas_post_produccidas=$capitulos_escenas_post['0']->total_escenas;
    $dif=$capitulos_escenas_prod['0']->total_escenas-$capitulos_escenas_post['0']->total_escenas;
    $valores = array(
      'imagen' => base_url('images/logoPdf.jpg'),
      'productor_general'=>strtoupper($productor_general),
      'nombre_produccion'=>$produccion['0']->nombre_produccion,
      'nombre_ejecutivo'=>$nombre_ejecutivo,
      'centro'=>strtoupper($produccion['0']->centro),
      'nombre_productor'=>$nombre_productor,
      'fecha'=>strtoupper(date("d-M-Y")),
      'min_produccidos'=>$min_produccidos,
      'mins_post_produccidos'=>$mins_post_produccidos,
      'minutos'=>$minutos,
      'negativo'=>$negativo,
      'total_escenas'=>$total_escenas,
      'escenas_post_produccidas'=>$escenas_post_produccidas,
      'dif'=>$dif,
    );
    $i=1;
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTopMargin('31px');
    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('11px');
    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='35px');
    $pdf->SetFont('', '', 7);
    $pdf->setTipoPdf(6);
    $pdf->AddPage('L');
    $pdf->SetLeftMargin('12px');
    $pdf->Ln(9);
    
    $tbl_set='<table border="0.5" width="100.5%" cellpadding="2">';
    if($capitulos){
        foreach ($capitulos as $c) {
              $libretos_capitulo=$this->model_post_produccion->libretos_capitulo($c['id']);
              $lib='';
              if($libretos_capitulo){
                 $tam=sizeof($libretos_capitulo);$cont=1; 
                  foreach ($libretos_capitulo as $l) {
                        if($cont==$tam){ 
                           $lib.=$l['numero'];
                        }else{
                            $lib.=$l['numero'].'-';
                        }
                       $cont++;
                   }
                }  
                $estado = explode(',', $c['estado']);
                $campos_estado="";
                $valida = true;

                for ($i=0; $i < count($estado); $i++) { 
                  $estado_base = explode('_', $estado[$i]);
                  switch($estado_base[1]){
                    case 'No producido':
                      $class_cap="no_prod";
                      $campos_estado .='<span style="background-color:#ffffff">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'LOGGING/INGESTANDO':
                      $class_cap="log_ing";
                      $campos_estado .='<span style="background-color:#fffcc7">'.strtoupper($estado_base[1])."</span>";
                    break;
                    case 'PRE-EDITANDO':
                      $class_cap="pre_edi";
                      $campos_estado .='<span style="background-color:#FFFC65">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'EDITANDO':
                      $class_cap="edi";
                      $campos_estado .='<span style="background-color:#FFFF01">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'FINALIZANDO':
                      $class_cap="fin";
                      $campos_estado .='<span style="background-color:#FFFF01">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'CODIFICANDO APP VIDEO':
                      $class_cap="cod_app_vid";
                      $campos_estado .='<span style="background-color:#FF9600">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'QC RTI TECNICO':
                      $class_cap="qc_rti_tec";
                      $campos_estado .='<span style="background-color:#FF6400">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'QC RTI PRODUCTOR':
                      $class_cap="qc_rti_por";
                      $campos_estado .='<span style="background-color:#FF3200">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'MONTANDO ARCHIVO LTO':
                      $class_cap="mon_arc_lto";
                      $campos_estado .='<span style="background-color:#FE0063">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'CODIFICANDO A CLIENTE':
                      $class_cap="cod_cli";
                      $campos_estado .='<span style="background-color:#FF00C8">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'ENVIANDO A CLIENTE':
                      $class_cap="env_cli";
                      $campos_estado .='<span style="background-color:#FF00FE">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'QC CLIENTE':
                      $class_cap="qc_cli";
                      $campos_estado .='<span style="background-color:#C800FF">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'SESION DE PROTOOLS':
                      $class_cap="arc_cap";
                      $campos_estado .='<span style="background-color:#9600ff;color: #fff;">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'MONTANDO EDL PS':
                      $class_cap="mon_edl_ps";
                      $campos_estado .='<span style="background-color:#662E93;color: #fff;">'.strtoupper($estado_base[1]).'</span>';
                    break;
                    case 'CAPITULO ENTREGADO':
                      $class_cap="cap_ent";
                      $campos_estado .='<span style="background-color:#0071BD">'.strtoupper($estado_base[1]).'</span>';
                    break;
                  }

                  if(count($estado)>=2 AND $valida){
                    $campos_estado .=' ';
                    $valida=false;
                  }
                }
                $tbl_set.='<tr>
                <td  width="30" align="center">'.$c['numero'].'</td> 
                <td  width="158" align="center">'.$campos_estado.'</td> 
                <td  width="25" align="center">'.$c['total_escenas'].'</td> 
                <td  width="25" align="center">'.$lib.'</td>'; 
                $tiempo_estimados=$this->calculo_tiempo2($c['minutos_estimados'],$c['segundos_estimados']);
                $tiempo_real=$this->calculo_tiempo2($c['minutos'],$c['segundos']);
                $tiempo_post=$this->calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']);
                $fecha_entrega=$c['fecha_entrega']; 
                if($fecha_entrega){
                  if($fecha_entrega!='0000-00-00'){
                     $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega));  
                  }else{
                    $fecha_entrega='-';     
                  }
                }else{
                  $fecha_entrega='-'; 
                } 
                if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
                  $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
                }else{
                  $fecha_entregada='-'; 
                } 
                $m_extra=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['foto_minutos']+$c['imagenes_archivos_minutos'];
          $s_extra=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['foto_segundos']+$c['imagenes_archivos_segundos'];
          $c_extra=$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros'];
                $t_extra=$this->calculo_tiempo_post($m_extra,$s_extra,$c_extra);
                $s_d=$c['segundos']-$c['segundos_post'];
                if($s_d<0){
                  $s_d=$s_d*-1;
                }
                $m_d=$c['minutos']-$c['minutos_post'];
                $diferencia=$this->calculo_tiempo2($m_d,$s_d);
                $total=$this->calculo_tiempo_post_redondeo($c['minutos_post']+$m_extra,$c['segundos_post']+$s_extra,$c['cuadros']+$c_extra);
                $tbl_set.='<td  width="52" align="center">'.$tiempo_estimados.'</td> 
                <td  width="40" align="center">'.$tiempo_real.'</td> 
                <td  width="40" align="center">'.$tiempo_post.'</td> 
                <td  width="40" align="center">'.$t_extra.'</td> 
                <td  width="40" align="center">'.$total.'</td> 
                <td  width="50" align="center">'.$diferencia.'</td> 
                <td  width="100" align="center">'.$c['responsable'] .'</td> 
                <td  width="100" align="center">'.$fecha_entrega.'</td> 
                <td  width="100" align="center">'.$fecha_entregada.'</td> 
            </tr>';
            }
       } 
       $tbl_set.='</table>';
        $tbl4=<<<EOD
        $tbl_set
EOD;
$pdf->SetTopMargin('40px');
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        $pdf->Output('post_produccion.pdf', 'I');
  }

  public function pdf_postproduccion_escenas($idproduccion,$id_capitulo){
    ini_set('memory_limit', '-1');
    set_time_limit(10000000);
    $cadena = "";
    $tbl4="";
    $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);

    if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
      $nombre_ejecutivo='';
    }
    if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
      $nombre_productor='';
    }
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }

    $escenas=$this->model_post_produccion->escenas_id_capitulo2($id_capitulo);
    $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
    $capitulo=$this->model_post_produccion->capitulo_id($id_capitulo);
    $tiempo_real=$this->model_post_produccion->escenas_id_capitulo_tiempo_real($id_capitulo);
    $tiempo_post=$this->model_post_produccion->escenas_id_capitulo_tiempo_post($id_capitulo);
    $libretos = $this->model_capitulos->capitulos_produccion_2($idproduccion);
    $vitacora=$this->model_post_produccion->vitacora($id_capitulo);
    $id_user=$this->session->userdata('id_pruduction_suite');

    $tiempo_realm=$this->calculo_tiempo2($tiempo_real['0']->minutos,$tiempo_real['0']->segundos);
    $tiempo_postm=$this->calculo_tiempo_post($tiempo_post['0']->minutos,$tiempo_post['0']->segundos,$tiempo_post['0']->cuadros);
    $minutos=$tiempo_real['0']->minutos-$tiempo_post['0']->minutos;
    $segundos=$tiempo_real['0']->segundos-$tiempo_post['0']->segundos;
    if($minutos<0 or $segundos<0){
       $negativo='-';
    }else{
        $negativo='';
    } 
    if($minutos<0){
      $minutos=$minutos*-1;
    }
    if($segundos<0){
      $segundos=$segundos*-1;
    }

    if($capitulo){
        $m=$capitulo['0']->flashback_minutos+$capitulo['0']->transiciones_minutos+$capitulo['0']->stab_minutos+$capitulo['0']->recap_minutos+$capitulo['0']->cabezote_minutos+$capitulo['0']->credito_minutos+$capitulo['0']->cortinillas_minutos+$capitulo['0']->despedida_minutos+$capitulo['0']->presentacion_minutos+$capitulo['0']->foto_minutos+$capitulo['0']->imagenes_archivos_minutos;
        $s=$capitulo['0']->flashback_segundos+$capitulo['0']->transiciones_segundos+$capitulo['0']->stab_segundos+$capitulo['0']->recap_segundos+$capitulo['0']->cabezote_segundos+$capitulo['0']->credito_segundos+$capitulo['0']->cortinillas_segundos+$capitulo['0']->despedida_segundos+$capitulo['0']->presentacion_segundos+$capitulo['0']->foto_segundos+$capitulo['0']->imagenes_archivos_segundos; 
        $c=$capitulo['0']->flashback_cuadros+$capitulo['0']->transiciones_cuadros+$capitulo['0']->stab_cuadros+$capitulo['0']->recap_cuadros+$capitulo['0']->cabezote_cuadros+$capitulo['0']->credito_cuadros+$capitulo['0']->cortinillas_cuadros+$capitulo['0']->despedida_cuadros+$capitulo['0']->presentacion_cuadros+$capitulo['0']->foto_cuadros+$capitulo['0']->imagenes_archivos_cuadros;  
      }else{
        $m='00';
        $s='00';
        $c='00';
      }


    $tiempo_extra=$this->calculo_tiempo_post($m,$s,$c);
    $minutos=$this->calculo_tiempo2($minutos,$segundos);
    $total_capitulo=$this->calculo_tiempo_post_redondeo($tiempo_post['0']->minutos+$m,$tiempo_post['0']->segundos+$s,$tiempo_post['0']->cuadros+$c);

    $escenas_asignadas=$tiempo_real['0']->total_escenas;
    $escenas_postproduccidas=$tiempo_post['0']->total_escenas;
    $diferencia=$tiempo_real['0']->total_escenas-$tiempo_post['0']->total_escenas;
    
     $libretos_capitulo=$this->model_post_produccion->libretos_capitulo($id_capitulo);
    $lib='';
    if($libretos_capitulo){
       $tam=sizeof($libretos_capitulo);$cont=1;
          foreach ($libretos_capitulo as $l) { 
                if($cont==$tam){ 
                   $lib.=$l['numero'];
                }else{
                   $lib.=$l['numero'].'-';
                }
                $cont++;
           }
    }
    $numero_capitulos=$capitulo['0']->numero;
    ////////////
    $estado = explode(',', $capitulo['0']->estado);
                $campos_estado="";
                $valida_agregar=0;
               // $estado=array();
                for ($i=0; $i < count($estado); $i++) { 
                  $estado_base = explode('_', $estado[$i]);
                  switch($estado_base[1]){
                    case 'No producido':
                      $class_cap="#ffffff";
                      $color="#000000";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='1';
                    break;
                    case 'LOGGING/INGESTANDO':
                      
                      $class_cap="#fffcc7";
                      $color="#000000";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='2';
                    break;
                    case 'PRE-EDITANDO':
                      $class_cap="#FFFC65";
                      $color="#000000";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='3';
                    break;
                    case 'EDITANDO':
                      $class_cap="#FFFF01";
                      $color="#000000";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='4';
                    break;
                    case 'FINALIZANDO':
                      $class_cap="#FFFF01";
                      $color="#000000";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='5';
                      $valida_agregar=1;
                      
                    break;
                    case 'CODIFICANDO APP VIDEO':
                      $class_cap="#FF9600";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='6';
                    break;
                    case 'QC RTI TECNICO':
                      $class_cap="#FF6400";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='7';
                    break;
                    case 'QC RTI PRODUCTOR':
                      $class_cap="#FF3200";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='8';
                    break;
                    case 'MONTANDO ARCHIVO LTO':
                      $class_cap="#FE0063";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='9';
                    break;
                    case 'CODIFICANDO A CLIENTE':
                      $class_cap="#FF00C8";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='10';
                    break;
                    case 'ENVIANDO A CLIENTE':
                      $class_cap="#FF00FE";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='11';
                    break;
                    case 'QC CLIENTE':
                      $class_cap="#C800FF";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='12';
                    break;
                    case 'SESION DE PROTOOLS':
                      $class_cap="#9600ff";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='13';
                    break;
                    case 'MONTANDO EDL PS':
                      $class_cap="#662E93";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='14';
                      $valida_agregar=1;
                    break;
                    case 'CAPITULO ENTREGADO':
                      $class_cap="#0071BD";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='15';
                    break;
                    case 'CANCELADO':
                      $class_cap="#000000";
                      $color="#ffffff";
                      $campos_estado .=" ".$estado_base[1];
                      $estado[$i]='16';
                    break;
                  }
                }
    //////////////
    $estatus=$campos_estado;
    $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($idproduccion,$capitulo['0']->numero); 
    if($fecha_entrega){
      if($fecha_entrega['0']->fecha_aire!=null and $fecha_entrega['0']->fecha_aire!='' and $fecha_entrega['0']->fecha_aire!='0000-00-00'){
          $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));
      }else{
          $fecha_entrega='-'; 
      }
      
    }else{
      $fecha_entrega='-'; 
    }
    if($capitulo['0']->fecha_entregada and $capitulo['0']->fecha_entregada!=null and $capitulo['0']->fecha_entregada!='' AND $capitulo['0']->fecha_entregada!='0000-00-00'){
      $fecha_entregada=date("d-M-Y",strtotime($capitulo['0']->fecha_entregada));
    }else{
      $fecha_entregada='-'; 
    }
    $responsable=$capitulo['0']->responsable;

    $valores = array(
      'imagen' => base_url('images/logoPdf.jpg'),
      'productor_general'=>strtoupper($productor_general),
      'nombre_produccion'=>$produccion['0']->nombre_produccion,
      'nombre_ejecutivo'=>$nombre_ejecutivo,
      'centro'=>strtoupper($produccion['0']->centro),
      'nombre_productor'=>$nombre_productor,
      'fecha'=>strtoupper(date("d-M-Y")),
      'tiempo_real'=>$tiempo_realm,
      'tiempo_post'=>$tiempo_postm,
      'tiempo_extra'=>$tiempo_extra,
      'minutos'=>$total_capitulo,
      'negativo'=>$negativo,
      'escenas_asignadas'=>$escenas_asignadas,
      'escenas_postproduccidas'=>$escenas_postproduccidas,
      'diferencia'=>$diferencia,
      'numero_capitulos'=>$numero_capitulos,
      'estatus'=>$estatus,
      'fecha_entrega'=>$fecha_entrega,
      'fecha_entregada'=>$fecha_entregada,
      'responsable'=>$responsable,
      'lib'=>$lib,
      'class_cap'=>$class_cap,
      'color'=>$color
    );
    $i=1;
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTopMargin('34px');
    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('11px');
    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='50px');
    $pdf->SetFont('', '', 7);
    $pdf->setTipoPdf(7);
    $pdf->AddPage('L');
    $pdf->SetLeftMargin('12px');
    $pdf->Ln(11);
    if($escenas){
      $tbl_set='<table border="0.5" width="100%" cellpadding="2">';
         foreach ($escenas as $e) {               
              $tbl_set.='
              <tr>
                <td  width="50" align="center">'.$e['numero'].'</td> 
                <td  width="100" align="center">'.$e['numero_escena'].'</td>';
                  if($e['duracion_estimada_minutos']<10 and (strlen($e['duracion_estimada_minutos'])<2)){ 
                    $m='0'.$e['duracion_estimada_minutos']; 
                    if(strlen($cuadros)<2){
                       $m='0'.$cuadros; 
                     } 
                  }else{
                   $m=$e['duracion_estimada_minutos'];
                  }

                  if($e['duracion_estimada_segundos']<10 and (strlen($e['duracion_estimada_segundos'])<2)){ 
                    $s='0'.$e['duracion_estimada_segundos']; 
                    if(strlen($cuadros)<2){
                       $s='0'.$cuadros; 
                     } 
                  }else{
                   $s=$e['duracion_estimada_segundos'];
                  }
                $tbl_set.='<td  width="210" align="center" >'.$m.':'.$s.'</td>';
                if($e['duracion_real_minutos']<10 and (strlen($e['duracion_real_minutos'])<2)){ 
                    $m='0'.$e['duracion_real_minutos']; 
                    if(strlen($cuadros)<2){
                       $m='0'.$cuadros; 
                     } 
                  }else{
                   $m=$e['duracion_real_minutos'];
                  }

                if($e['duracion_real_segundos']<10 and (strlen($e['duracion_real_segundos'])<2)){ 
                  $s='0'.$e['duracion_real_segundos']; 
                  if(strlen($cuadros)<2){
                     $s='0'.$cuadros; 
                   } 
                }else{
                 $s=$e['duracion_real_segundos'];
                }
                $tbl_set.='<td  width="210" align="center">'.$m.':'.$s.'</td>';
                if($e['tiempo_post_cuadros']==null){ 
                   $cuadros='00';
                  }elseif($e['tiempo_post_cuadros']<10 and (strlen($e['tiempo_post_cuadros'])<2)){ 
                    $cuadros='0'.$e['tiempo_post_cuadros']; 
                    if(strlen($cuadros)<2){
                       $cuadros='0'.$cuadros; 
                     } 
                  }else{
                   $cuadros=$e['tiempo_post_cuadros'];
                  }
                if(!$e['tiempo_post_minutos'] or !$e['tiempo_post_segundos']){
                 $tbl_set.='<td  width="210" align="center">-</td>';
                }else{
                  $tbl_set.='<td  width="210" align="center">'.$e['tiempo_post_minutos'].':'.$e['tiempo_post_segundos'].'-'.$cuadros.'</td>';
                }

                $tbl_set.='
              </tr>';
          }  
      

       $tbl_set.='</table>';
        $tbl4=<<<EOD
        $tbl_set
EOD;
$pdf->SetTopMargin('45px');
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        $pdf->Output('post_produccion.pdf', 'I');
    } else{
      echo "Este capitulo no tiene escenas asignadas";
    } 

  }


  public function pdf_cruce_elementos($id,$fecha_unidad){
    $orden=""; 
    $impresion=0; 
    $cadena_impresion="";
    $cruce="";
    $produccion=$this->model_plan_produccion->produccion_id($id);
    $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
    $sql=", ele1.id AS id_elemento";
    $produccion=$this->model_plan_produccion->produccion_id($id);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
    $t=0;
    if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
      $nombre_ejecutivo='';
    }
    if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
      $nombre_productor='';
    }
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }



    $valores = array(
      'imagen' => base_url('images/logoPdf.jpg'),
      'productor_general'=>strtoupper($productor_general),
      'nombre_produccion'=>$produccion['0']->nombre_produccion,
      'nombre_ejecutivo'=>$nombre_ejecutivo,
      'centro'=>strtoupper($produccion['0']->centro),
      'nombre_productor'=>$nombre_productor,
      'fecha'=>strtoupper(date("d-M-Y")),
    );

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTopMargin('31px');
    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('11px');
    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='50px');
    $pdf->SetFont('', '', 7);
    $pdf->setTipoPdf(8);
    $pdf->AddPage('L');
    $pdf->SetLeftMargin('12px');
    //$pdf->Ln(13.5);


    if($unidades){        
      for ($i=1; $i <= count($unidades); $i++) { 
        $sql .= ",(SELECT group_concat(produccion_has_capitulos.numero,'/',escena.numero_escena separator ' - ')
                FROM escenas_has_elementos
                INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                INNER JOIN plan_diario_has_escenas_has_unidades 
                ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
                INNER JOIN elemento ele2 ON ele2.id = escenas_has_elementos.id_elemento
                INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id 
                INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                WHERE plan_diario.id_unidad = ".$unidades[$i-1]['id']." AND plan_diario.fecha_inicio = '".$fecha_unidad."' AND ele2.id= ele1.id) AS unidad".$i;
      }
    }
    $cruce = $this->model_plan_diario->cruces_elementos($id,$fecha_unidad,$sql);
    $idpersonajes = $this->model_plan_general->categoria_produccion($id, 'Personaje');
    $idpersonajes = $idpersonajes[0]->id;


    if($cruce!="") { 
      foreach ($cruce as $c) {
        $validator=0;
        $acumulator=0;
        for ($i=1; $i <= count($unidades); $i++) { 
          $acumulator +=$c['unidad'.$i];
          if($acumulator>$c['unidad'.$i] AND $c['unidad'.$i]>0){
            $validator=1;
            $impresion=1;
          }
        }

        if($validator){
          
          $cadena_impresion.='<table border="1" width="100%" cellpadding="2">';
          $cadena_impresion.='<tr style="background-color:#535357;color:#FFFFFF;"><td colspan="5">TIPO DE ELEMENTO: '.strtoupper($c['tipo']).'</td>';
          $cadena_impresion.='<td colspan="5">NOMBRE DE ELEMENTO: '.strtoupper($c['nombre']);
          //VALIDACION, SI TIENE ROL
          if($c['rol_elemento']!=null){
            $cadena_impresion.= strtoupper(' ( '.$c['rol_elemento'].' )');
          }
          $cadena_impresion.='</td>';
          //FIN VALIDACION, SI TIENE ROL
          $cadena_impresion.='</tr>';

          
          $escenas2 = $this->model_plan_diario->escenas_cruce($idpersonajes,$c['id_elemento'], $fecha_unidad);

          $cadena_impresion.='<tr style="background-color:#535357;color:#FFFFFF;">';
            $cadena_impresion.='<td width="25" align="center">UNI</td>';
            $cadena_impresion.='<td width="25" align="center">#</td>';
            $cadena_impresion.='<td width="25" align="center">LIB.</td>';
            $cadena_impresion.='<td width="25" align="center">ESC.</td>';
            $cadena_impresion.='<td width="40" align="center">TIE. EST.</td>';
            $cadena_impresion.='<td align="center">LOCACIÓN</td>';
            $cadena_impresion.='<td width="81" align="center">SET</td>';
            $cadena_impresion.='<td width="160" align="center">PERSONAJES PRINCIPALES</td>';
            $cadena_impresion.='<td width="160" align="center">PERSONAJES SECUNDARIOS</td>';
            $cadena_impresion.='<td width="160" align="center">ELEMENTOS</td>';
          $cadena_impresion.='</tr>';
          $unidad_a=0;
          foreach ($escenas2 as $escena2) {
            if ($t%2==0){
              $b="#e6e4e5";
            } else {
              $b="#ffffff";
            }
            ++$t;
            $contador=0;
            foreach ($escenas2 as $escena3) {
              if($escena3->unidad_numero==$escena2->unidad_numero){
                $contador++;
              }
            }
            $cadena_impresion.='<tr style="background-color:'.$b.';">';
              if($unidad_a!=$escena2->unidad_numero) { 
                $cadena_impresion.='<td rowspan="'.$contador.'">'.$escena2->unidad_numero.'</td>';
              } 
              $unidad_a = $escena2->unidad_numero; 
              $cadena_impresion.='<td align="center">'.$escena2->orden.'</td>';
              $cadena_impresion.='<td align="center">'.$escena2->numero_libreto.'</td>';
              $cadena_impresion.='<td align="center">'.$escena2->numero_escena.'</td>';
              $cadena_impresion.='<td align="center">'.$escena2->duracion_estimada_minutos.':'.$escena2->duracion_estimada_segundos.'</td>';
              $cadena_impresion.='<td>'.$escena2->locacion_nombre.'</td>';
              $cadena_impresion.='<td width="81">'.$escena2->set_nombre.'</td>';
              $cadena_impresion.='<td width="160">';
              $cadena_impresion.=strtoupper($escena2->personajes_principales);
              $cadena_impresion.='</td>';
              $cadena_impresion.='<td width="160">';
              $cadena_impresion.=strtoupper($escena2->personajes_secundarios);
              $cadena_impresion.='</td>';
              $cadena_impresion.='<td width="160">';
              $cadena_impresion.=strtoupper($escena2->elementos);
              $cadena_impresion.='</td>';
            $cadena_impresion.='</tr>';
          }
          $cadena_impresion.='</table>';
        }
      }

    } 
    if($impresion==0){
      echo 'No hay cruce de elementos';
    }else{
      //echo $cadena_impresion;
      $tbl4=<<<EOD
       $cadena_impresion
EOD;
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $pdf->Output('cruce_elementos.pdf', 'I');
    }
  }


  /*CAJA DE COLORES */
  public function pdf_caja_colores($id_produccion=232){
    ini_set('memory_limit', '-1');
    set_time_limit(10000000);
    $capitulos=$this->model_dashboard->capitulos_limite($id_produccion);
    $datos_produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    $estados_color = $this->model_escenas_2->estados_color();
    $cadena_tabla = "";
    $tbl4="";


    $produccion=$this->model_plan_produccion->produccion_id($id_produccion);
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
    $t=0;
    if($ejecutivo){
      $nombre_ejecutivo=$ejecutivo['0']->nombre.' '.$ejecutivo['0']->apellido;
    }else{
      $nombre_ejecutivo='';
    }
    if($productor){
      $nombre_productor=$productor['0']->nombre.' '.$productor['0']->apellido;
    }else{
      $nombre_productor='';
    }
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }



    $valores = array(
      'imagen' => base_url('images/logoPdf.jpg'),
      'productor_general'=>strtoupper($productor_general),
      'nombre_produccion'=>$produccion['0']->nombre_produccion,
      'nombre_ejecutivo'=>$nombre_ejecutivo,
      'centro'=>strtoupper($produccion['0']->centro),
      'nombre_productor'=>$nombre_productor,
      'fecha'=>strtoupper(date("d-M-Y")),
    );

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTopMargin('31px');
    $pdf->setElementsHeader($valores);
    $pdf->SetHeaderMargin('11px');
    $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
    $pdf->SetFont('', '', 7);
    $pdf->setTipoPdf(9);
    $pdf->AddPage('L');
    $pdf->SetLeftMargin('12px');
    $limite = 0;

    if($capitulos){ 
      while($capitulos){
        $cadena_tabla.='<table><tr>';
        foreach ($capitulos as $c) {
          $cadena_tabla.='<td>';
          switch($c['estado']){
            case 3:
              $class_cap="background-color: #ff0068;color: #fff;font-weight: bolder;";
            break;
            case 5:
              $class_cap="background-color: #fee93e;font-weight: bolder;";
            break;
            case 2:
              $class_cap="background-color: #8815a6; color: #fff; font-weight: bolder;";
            break;
            case 4:
              $class_cap="background-color: #85c646; color: #fff; font-weight: bolder;";
            break;
            case 6:
              $class_cap="background-color: #000;color: #fff;font-weight: bolder;";
            break;
            case 1:
              $class_cap="background-color: #cccccc; color: #000; font-weight: bolder;";
            break;
          } 

          $cadena_tabla.='<table>';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5" colspan="1.5" style="'.$class_cap.'" align="center">'.$c['numero'].'</td>';
            $cadena_tabla.='</tr>';
          $cadena_tabla.='</table>';

          $cadena_tabla.='<table style="margin-top: -5px;">';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5" colspan="2" align="center">'.round((100*$c['locacion'])/$c['total']).'%</td>';
            $cadena_tabla.='</tr> ';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5" colspan="2" align="center">'.round((100*$c['estudio'])/$c['total']).'%</td>';
            $cadena_tabla.='</tr>';
          $cadena_tabla.='</table>';


          $dif_minutos=$c['total_duracion_estimada_minutos']-$c['total_duracion_real_minutos']; 
          $dif_segundos=$c['total_duracion_estimada_segundos']-$c['total_duracion_real_segundos'];
          $dif_segundos=$dif_segundos+($dif_minutos*60);
          $dif_cap=$c['total']-$c['total_producidos']; 

          $cadena_tabla.='<table>';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5"  colspan="2" align="center">'.round((100*$c['total_producidos'])/$c['total']).'%</td>';
            $cadena_tabla.='</tr>';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5" style="width: 50%; border-right:none;" align="center">'.$c['total'].'</td>';
              $cadena_tabla.='<td border="1.5" style="width: 50%;" align="center">'.$this->calculo_tiempo_caja($c['total_duracion_estimada_minutos'],$c['total_duracion_estimada_segundos']).'</td>';
            $cadena_tabla.='</tr>';
            $cadena_tabla.='<tr>';
              $cadena_tabla.='<td  border="1.5" style="width: 50%; border-right:none; background-color: #C2C0C1;" align="center">'.$c['total_producidos'].'</td>';
              $cadena_tabla.='<td  border="1.5" style="width: 50%; background-color: #C2C0C1;" align="center" >'.$this->calculo_tiempo2($c['total_duracion_real_minutos'],$c['total_duracion_real_segundos']).'</td>';
            $cadena_tabla.='</tr>';
            $cadena_tabla.='<tr>';

              $cadena_tabla.='<td border="1.5" style="width: 50%; border-right:none; background-color: #C2C0C1;';
              if($dif_cap<0){ 
                $cadena_tabla.='color:red';
              } 
              $cadena_tabla.='" align="center">'.$dif_cap.'</td>';
              $cadena_tabla.='<td border="1.5"  style="width: 50%; background-color: #C2C0C1;';

              if($dif_minutos<0){ 
                $cadena_tabla.= 'color:red';
              } 

              $cadena_tabla.='" align="center">'.$this->tiempo_segundos($dif_segundos).'</td>';
            $cadena_tabla.='</tr>';
          $cadena_tabla.='</table>';


          $escenas=$this->model_dashboard->escenas_id_capitulos($c['id_capitulo']);

          $cadena_tabla.='<table class="escena_head">';
            $cadena_tabla.='<tr style="background-color:#535357;color:#FFFFFF;">';
              $cadena_tabla.='<td border="1.5" style="height:auto !important; width: 50%; border-right: none;" align="center" >Esc</td>';
              $cadena_tabla.='<td border="1.5" style="height:auto !important; width: 50%;" align="center">Tiem</td>';
            $cadena_tabla.='</tr>';
          $cadena_tabla.='</table>';

          if ($escenas){
            $cadena_tabla.='<table class="table_escenas">';
            foreach ($escenas as $e){
              switch($e['estado']){
                case 1:
                $class="background-color: #fee93e;";
                break;
                case 2:
                $class="background-color: #fec63e; color: #333;";
                break;
                case 3:
                $class="background-color: #000000; color: #fff; ";
                break;
                case 4:
                $class="background-color: #09eee9;";
                break;
                case 5:
                $class="background-color: #a6fff9;";
                break;
                case 6:
                $class="background-color: #e3228d;";
                break;
                case 7:
                $class="background-color: #ef7bbb;";
                break;
                case 8:
                $class="background-color: #8cdd16;";
                break;
                case 9:
                $class="background-color: #c1f378;";
                break;
                case 10:
                $class="background-color: #f7921e;";
                break;
                case 11:
                $class="background-color: #faca9a;";
                break;
                case 12:
                $class="background-color: #fee93e;";
                break;
                case 14:
                $class="background-color: #fec63e; color: #333;";
                break;
                default:
                $class="background-color: #a79980;";
                break;
              } 

              $cadena_tabla.='<tr>';
              $cadena_tabla.='<td border="1.5" style="'.$class.'" align="center" >';
              $cadena_tabla.= $e['numero_escena'];
              $cadena_tabla.='</td>';
              if($e['estado']==1){ 
                $cadena_tabla.='<td border="1.5" style="width: 50%; height:auto !important;'.$class.'" align="center">'.$this->calculo_tiempo2($e['duracion_real_minutos'],$e['duracion_real_segundos']).'</td>';
              } else {
                $cadena_tabla.='<td border="1.5" style="width: 50%; height:auto !important;'.$class.'" align="center" >'.$this->calculo_tiempo2($e['duracion_estimada_minutos'],$e['duracion_estimada_segundos']).'</td>';
              } 
              $cadena_tabla.='</tr>';
            }
            $cadena_tabla.='</table>';
          }
          $cadena_tabla.='</td>';
        } 
          $cadena_tabla.='</tr>';
          $cadena_tabla.='</table>';
          $tbl4=<<<EOD
       $cadena_tabla
EOD;
          $pdf->writeHTML($tbl4, true, false, false, false, '');
          $cadena_tabla="";
          
          $capitulos=$this->model_dashboard->capitulos_limite($id_produccion,$limite+=12);
          if($capitulos){
            $pdf->AddPage('L');
          }
      }
        $tbl4=<<<EOD
       $cadena_tabla
EOD;
      $pdf->writeHTML($tbl4, true, false, false, false, '');
      $pdf->Output('caja_colores.pdf', 'I');

    } else { 
      echo '<div>No hay libretos para esta Produccion</div>';
    } 
  }

  function calculo_tiempo_caja($minutos,$segundos){
    $minutos2=0;
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 += $minutos;

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }

      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      $tiempo = $minutos2.":".$segundos;
      return $tiempo;
  }


  public static function tiempo_segundos($total){
    $total=$total;
    if($total>=0){
      $m_total=floor($total/60);
    }else{
      $m_total=ceil($total/60);
    }
    $segundos_2=$total%60;
      if(strlen($segundos_2)<2){
        $segundos_2 = '0'.$segundos_2;
      }
      if($segundos_2<=0){
        $segundos_2 =abs($segundos_2);
      }

      if($m_total>=0 and $m_total<10){
        $m_total='0'.$m_total;
      }
      if($m_total>-10 and $m_total<0){
        $m_total='0'.abs($m_total);
        $m_total='-'.$m_total;
      }
      if(strlen($segundos_2)<2){
        $segundos_2 = '0'.$segundos_2;
      }
      $m_total_dif=$m_total.':'.$segundos_2;
      return $m_total_dif;
  }

  public  function calculo_tiempo_post($minutos1,$segundos1,$cuadros1){
      $cuadros = 0;
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      $cuadros = $cuadros1;
     
     while($cuadros>=30){
          $segundos+=1;
          $cuadros= $cuadros-30;
      }
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $minutos1 + $minutos;


      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }
      if(strlen($cuadros)<2){
        $cuadros = '0'.$cuadros;
      }
        if($minutos2==0){
          $minutos2='00';
        }
        if($segundos==0){
          $segundos='00';
        }
        if($cuadros==0){
          $cuadros='00';
        }
        $tiempo = $minutos2.":".$segundos.'-'.$cuadros;

      return $tiempo;
    }

    public  function calculo_tiempo_post_redondeo($minutos1,$segundos1,$cuadros1){
      $cuadros = 0;
      $segundos = 0;
      $minutos = 0;
      $horas = 0;

      $segundos = $segundos1;

      $cuadros = $cuadros1;
     
     while($cuadros>=30){
          $segundos+=1;
          $cuadros= $cuadros-30;
      }
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }

      $minutos2 = $minutos1 + $minutos;
      
    /* if($cuadros>15){
        $segundos=$segundos+1;
      }*/

      if(strlen($minutos2)<2){
        $minutos2 = '0'.$minutos2;
      }
      if(strlen($segundos)<2){
        $segundos = '0'.$segundos;
      }
      if(strlen($cuadros)<2){
        $cuadros = '0'.$cuadros;
      }
      if($minutos2==0){
        $minutos2='00';
      }
      if($segundos==0){
        $segundos='00';
      }
      if($cuadros==0){
        $cuadros='00';
      }

      $tiempo = $minutos2.":".$segundos;

      return $tiempo;
    }

}