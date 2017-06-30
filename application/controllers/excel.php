<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel extends CI_Controller {


  public function __construct (){         
    parent :: __construct (); 
    $this->load->model('model_capitulos');
    $this->load->model('model_plan_produccion');
    $this->load->model('model_pdf');
    $this->load->model('model_escenas_2');
    $this->load->model('model_escenas');
    
    $this->load->model('model_plan_general');
    $this->load->model('model_plan_diario');
    $this->load->model('model_elementos');
    $this->load->model('model_herramientas');

    $this->load->model('model_post_produccion');
    $this->load->model('model_dashboard');
    $this->load->model('model_pagos');
    require_once(APPPATH.'libraries/excelWriter.php');
    require_once(APPPATH.'libraries/html2pdf.class.php');
  }

  public function sumar_minutos($idproduccion){

    $acumulado = $this->model_escenas_2->sumar_tiempos_produccion($idproduccion);
    $segundos = 0;
    $minutos = 0;
    $horas = 0;

    $segundos = $acumulado[0]->segundos;

    while($segundos>=60){
        $minutos+=1;
        $segundos= $segundos-60;
    }

    $minutos2 = $acumulado[0]->minutos + $minutos;
    if($minutos2==0 and $segundos==0){
      $tiempo= 0;
    }else{
      if($minutos2>=0 and $minutos2<10){
          $minutos2='0'.$minutos2;
      }
      if($segundos>=0 and $segundos<10){
          $segundos='0'.$segundos;
      }
      $tiempo= $minutos2.":".$segundos;
    }
    return $tiempo;
  }

  function tiempo_segundos($total){
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
        $m_total_dif=$m_total.':'.$segundos_2;
        return $m_total_dif;
    }

    public  function calculo_tiempo($minutos1,$segundos1){
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
  
  public function plan_produccion($id){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $capitulos = $this->model_capitulos->contar_capitulos_escritos($produccion['0']->id_produccion);
      $minutos=$produccion['0']->minuto_capitulo;
      $segundos=$minutos*60;
      $segundos=$segundos*$produccion['0']->numero_capitulo;
      $segundos2=$produccion['0']->segundos_capitulo;
      $segundos2=$segundos2*$produccion['0']->numero_capitulo;
      $total=$segundos+$segundos2;
      $m_total=floor($total/60);
      $segundos_2=$total%60;
      
      if(strlen($segundos_2)<2){
        $segundos_2 = '0'.$segundos_2;
      }
      $m_total_prod=$m_total.':'.$segundos_2;
      $minutos_producidos =$this->sumar_minutos($produccion['0']->id_produccion);
      $semanas_trabajo=$this->model_plan_produccion->semanas_trabajo($produccion['0']->id_produccion);
      $semanas=$this->model_plan_produccion->total_semanas($id);
      $unidad=$this->model_plan_produccion->unidades_id_produccion($id);
      $escenas_producidas = $this->model_escenas_2->escenas_producidas_produccion($produccion['0']->id_produccion);

      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      $i=1;
      $cont=1;
      $cont_unida1=1;
      $cont_unida2=1;
      $cont_unida3=1;
      $cont_unida4=1;
      $cont_unida5=1;
      $cap_pro=0;
      $acu_entre=0;
      $acu_desg=0;
      $min_acu=0;
      $entro_u=0;
      $dif_min = 0;
      $k=0;
      $acu_min_real=0;
      $acu_seg_real=0;
      $dif_min_real=0;
      $acu_dif_min_real=0;
      $acu_dif_seg_real=0;
      $acumtotal='00:00';
      $dif_esc_real=0;
      $acumtotal2=0;
      $color_libretos=0;
      $color_minutos=0;
      $dif_esc=null;
      $m_total_dif=0;
      $color_semana='';
      $acum_escena_proyec=0;
             $total_acum_escena_producidas=0;
             $acum_esc_dif=0;
             $acum_minutos=0;

       if($produccion['0']->minuto_capitulo and $produccion['0']->minuto_capitulo!=0 and $produccion['0']->escenas_libretos and $produccion['0']->escenas_libretos!=0){ 
          //$escena_porminuto= number_format((float)$produccion['0']->minuto_capitulo/$produccion['0']->escenas_libretos, 2, '.', ''); ;
          $escena_porminuto=number_format((float)($produccion['0']->escenas_libretos)/$produccion['0']->minuto_capitulo, 2, '.', '');
       }else{
        $escena_porminuto= 0;
       }
      while ($i<=$semanas[0]->total) { 
          $cont=$i-1; 
          $semana_actual =strtotime(date('Y-m-d')); 
          $semana_aire =strtotime($produccion['0']->fecha_aire);
          $semana_inicio = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
          $semana_fin =   strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
          if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
                if($dif_esc>=0){
                 $color_libretos=1; 
                }
                $m_acum=explode(':',$m_total_dif);
                if($m_acum['0']>=0){
                $color_minutos=1;
                }
          }  
           
              $cont=$i-1;
              $cap_pro=$cap_pro+$semanas_trabajo[$cont]['capitulos_programados'];
              $contador=0;
              $contador_desg=0;
              foreach ($capitulos as $capitulo) {
                  if(strtotime($capitulo->fecha_entregado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                      $contador++;
                  }
                  if(strtotime($capitulo->fecha_desglosado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_desglosado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                      $contador_desg++;
                  }
              }
              $acu_entre += $contador;
              $acu_desg += $contador_desg;
             $dif_esc=$acu_entre-$cap_pro;
             if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
                 if($dif_esc>=0){ 
                    $color_semana='green';
                  }
              } 
              if($semana_actual<=$semana_aire or $semana_aire!=''){
                if($dif_esc<0){ 
                      $color1='red';
                  }else{
                      $color1='green';
                }
              }else{
                 $color1='green';
              }
               
              if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin){
                  $dif_esc_real=$dif_esc;
              }

         
              $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
             // $acum_escena_producidas=0;
              for ($z=0; $z < count($unidad); $z++) { ;
               $acu_min_uni=0;$acu_seg_uni=0;
                    $tiempo=$this->model_escenas_2->escenas_producidas_produccion2($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana'],$unidad[$z]['id']);
                  if($tiempo){
                    //$acum_escena_producidas=$acum_escena_producidas+$tiempo['0']->total_escenas;
                      $acu_min_uni+=$tiempo['0']->duracion_real_minutos_total;
                      $acu_seg_uni+=$tiempo['0']->duracion_real_segudos_total;
                      $acu_min_uni_sem+=$tiempo['0']->duracion_real_minutos_total;
                      $acu_seg_uni_sem+=$tiempo['0']->duracion_real_segudos_total;
                      $acu_min_real+=$tiempo['0']->duracion_real_minutos_total;
                      $acu_seg_real+=$tiempo['0']->duracion_real_segudos_total;
                  }else{
                      $acu_min_uni+=0;
                      $acu_seg_uni+=0;
                      $acu_min_uni_sem+=0;
                      $acu_seg_uni_sem+=0;
                      $acu_min_real+=0;   
                      $acu_seg_real+=0;

                  } 
               } 
              // $total_acum_escena_producidas=$total_acum_escena_producidas+$acum_escena_producidas;
              $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
              $v=explode(':', $dif_min_real); 
              $acum_escena_producidas=round($escena_porminuto*$v['0']);
              $min_real = explode(':',$dif_min_real);
              $j=1; 
              while ($j<=$produccion['0']->numero_unidades) { 
                  $cont_p=$i-2;
                  $cont_u=$j-1; 
                  $fecha_inicio = strtotime($unidad[$cont_u]['fecha_inicio']);
                  $fecha_inicio_semana = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
                  $fecha_fin_semana=strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
                  if(($fecha_inicio<=$fecha_inicio_semana or $fecha_inicio<=$fecha_fin_semana) and $fecha_inicio!='') { 
                   $k=1;
                  }
                  $j++; 
              } 

              if($k==1){
                    $segundos_real = $min_real[0]*60;
                    $segundos_real = $segundos_real+$min_real[1];
                    $segundos_sem_tra=$semanas_trabajo[$cont]['minutos_proyectados']*60;
                    $total=$segundos_real-$segundos_sem_tra;
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
                      if($segundos_2>=0 and $segundos_2<10){
                        $segundos_2='0'.$segundos_2;
                      }
                      if($m_total>=0 and $m_total<10){
                        $m_total='0'.$m_total;
                      }
                      if($m_total>-10 and $m_total<0){
                        $m_total='0'.abs($m_total);
                        $m_total='-'.$m_total;
                      }
                      $m_total_dif=$m_total.':'.$segundos_2;
                   }else{
                       $m_total_dif = $min_real[0].':'.$min_real[1];
                    }
                  $acu_dif_min_real+=$dif_min_real;
                  $acu_dif_seg_real+=$min_real[1];

                    $sem=explode(':',$acumtotal);
                    $acum=explode(':',$m_total_dif);
                    $min1=$sem[0]*60;
                    $min2=$acum[0]*60;
                    if($min1>=0){
                       $seg1=$min1+$sem[1];
                    }else{
                       $seg1=$min1-$sem[1];
                    }
                    if($min2>=0){
                      $seg2=$min2+$acum[1];  
                    }else{
                      $seg2=$min2-$acum[1];
                    }
                    $acumtotal=$seg2+$seg1;
                    $acumtotal=$this->tiempo_segundos($acumtotal);
              if($acumtotal2<0){ 
                  $color2='red';
              }else{
                  $color2='green';
              }
              if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) { 
               $acumtotal2=$acumtotal;
          } 
          /**************/
        $i++;
      }
      
           $fecha= date("d-M-Y H:i:s");
            $xls = new ExcelWriter();
               
            $xls_int = array('type'=>'int','border'=>'111111');
            $xls_date = array('type'=>'date','border'=>'111111');
            $xls_normal = array('border'=>'111111');
            $xls->OpenRow();
            $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
            $xls->NewCell('',array('background'=>'ffffff'));
            $xls->NewCell('',array('background'=>'ffffff'));
            $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
            $xls->CloseRow();
            $xls->OpenRow();
            $xls->NewCell('FECHA: '.strtoupper($fecha),false);
            $xls->NewCell('');
            $xls->NewCell('');
            $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
            $xls->CloseRow();
            $xls->OpenRow();
            $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
            $xls->CloseRow();
            $xls->OpenRow();
            $xls->CloseRow();
            $xls->OpenRow();
            if($color_libretos==0 or $color_semana=="green"){

              if($color1=='green' or $color_semana=='green'){
               $background_head='80B618';
              }else{
                $background_head='DF4A32';
              }
            }else{
              $background_head='f8f2be';
            }  
            $xls->NewCell('LIBRETOS ENTREGADOS: '.sizeof($capitulos).'/'.$produccion['0']->numero_capitulo,false,array('background'=>$background_head));
            $xls->NewCell('',false,array('background'=>$background_head));
            $xls->NewCell('',false,array('background'=>$background_head));
            $xls->NewCell('DIFERENCIA: '.$dif_esc_real,false,array('background'=>$background_head));
            $xls->NewCell('',false,array('background'=>$background_head));
            $xls->NewCell('',false);
            
            $xls->NewCell('ESCENA POR MIN: '.$escena_porminuto,false,array('background'=>"10AD9B"));
            $xls->NewCell('',false,array('background'=>"10AD9B"));
            $xls->CloseRow();
            $xls->OpenRow();
            $xls->CloseRow();
            $xls->OpenRow();
            if($color_minutos==0){
                if($color2=='green'){
                $background_head2='80B618';
                }else{
                  $background_head2='DF4A32';
                }
            }else{
              $background_head2='f8f2be';
            }    
            $xls->NewCell('MINUTOS PRODUCCIDOS: '.$minutos_producidos.'/'.$m_total_prod,false,array('background'=>$background_head2));
            $xls->NewCell('',false,array('background'=>$background_head2));
            $xls->NewCell('DIFERENCIA: '.$acumtotal2,false,array('background'=>$background_head2));
            $xls->NewCell('',false,array('background'=>$background_head2));
            $xls->CloseRow();
            $xls->OpenRow();
            $xls->CloseRow();

            $textos = array('aaaa ','bbbb ','cccc ','dddd ','eeee ','erwssd','gggg','hhhh','iiii','kkkkkk');
            $colores = array('F17C0E','0012FF','06FF00','FF0000','AE00FF','BCA8E6','7E8D00','9D9D96','00789B','FF4E00');

            $arr = array('SEM','#D','INICIO','FIN','PRO','AC.P','ENT','AC.E','DIF','DES','AC.D','SEM PROY','ACM PROY','SEM REAL','ACM REAL','SEM DIF','ACM SEM','ESC PROY','ACUM ESC','ESC PROD','ACM ESC PROD','ESC REAL PROD','ACM ESC REAL PROD','ESC DIF','ACUM ESC MIN');
            $xls->OpenRow();
            foreach($arr as $cod=>$val) 
            $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'111111'));
            $xls->CloseRow();
            $i=1;
            $cont=0;
            $cont_unida1=1;
            $cont_unida2=1;
            $cont_unida3=1;
            $cont_unida4=1;
            $cont_unida5=1;
            $cap_pro=0;
            $acu_entre=0;
            $acu_desg=0;
            $min_acu=0;
            $entro_u=0;
            $dif_min = 0;
            $k=0;
            $acu_min_real=0;
            $acu_seg_real=0;
            $dif_min_real=0;
            $acu_dif_min_real=0;
            $acu_dif_seg_real=0;
            $acumtotal='00:00';
            $dif_esc_real=0;
            $acumtotal2=0;
            
            while ($i<=$semanas[0]->total) {
                $xls->OpenRow();
                $semana_aire =strtotime($produccion['0']->fecha_aire);
                $semana_inicio = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
                $semana_fin =   strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
                if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
                  $background='FFBA6F';
                } else if($semana_aire>=$semana_inicio and $semana_aire<=$semana_fin){
                   $background='D6E7B0';
                } else{
                      $background='';
                }
                $xls->NewCell($i,false,array('background'=>$background));
                $xls->NewCell($semanas_trabajo[$cont]['dias_trabajo'],false,array('type'=>'int','background'=>$background));
                $xls->NewCell(date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])),false,array('background'=>$background));
                $xls->NewCell(date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])),false,array('background'=>$background));
                $xls->NewCell($semanas_trabajo[$cont]['capitulos_programados'],false,array('type'=>'int','background'=>$background));
                $cap_pro=$cap_pro+$semanas_trabajo[$cont]['capitulos_programados'];
                $xls->NewCell($cap_pro,false,array('type'=>'int','background'=>$background));
                $contador=0;
                $contador_desg=0;
                foreach ($capitulos as $capitulo) {
                    if(strtotime($capitulo->fecha_entregado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_entregado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                        $contador++;
                    }
                    if(strtotime($capitulo->fecha_desglosado) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  AND strtotime($capitulo->fecha_desglosado) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])){
                        $contador_desg++;
                    }
                }
                $acu_entre += $contador;
                $acu_desg += $contador_desg;
                $xls->NewCell($contador,false,array('type'=>'int','background'=>'f8f2be'));
                $xls->NewCell($acu_entre,false,array('type'=>'int','background'=>$background));
                $dif_esc=$acu_entre-$cap_pro;
                $xls->NewCell($dif_esc,false,array('type'=>'int','background'=>$background));
                $xls->NewCell($contador_desg,false,array('type'=>'int','background'=>$background));
                $xls->NewCell($acu_desg,false,array('type'=>'int','background'=>$background));
                $acum_escenas_pautadas=0;
                $j=1; 
                while ($j<=$produccion['0']->numero_unidades) { 
                  $cont_p=$i-2;
                  $cont_u=$j-1; 
                  $fecha_inicio = strtotime($unidad[$cont_u]['fecha_inicio']);
                  $fecha_inicio_semana = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
                  $fecha_fin_semana=strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
                   if(($fecha_inicio<=$fecha_inicio_semana or $fecha_inicio<=$fecha_fin_semana) and $fecha_inicio!='') { 
                           $k=1;
                   }
                $j++; 
                } 
                if($entro_u==0){
                 $semana_inicio_unidad = strtotime($unidad[0]['fecha_inicio']);
                    if($k==1) {
                       $entro_u=1;
                       $minutos_proyectados=$semanas_trabajo[$cont]['minutos_proyectados'];
                       $dif_min = -$semanas_trabajo[$cont]['minutos_proyectados'];
                   }else{
                      $minutos_proyectados=0;
                      $min_acu = 0;
                      $dif_min = 0;
                   }

                }else{ 
                $minutos_proyectados=$semanas_trabajo[$cont]['minutos_proyectados'];
                }  
                $xls->NewCell($minutos_proyectados,false,array('type'=>'int','background'=>$background));
                if($k!=1){
                  $min_acu=0; 
                }else{
                  $min_acu=$min_acu+$semanas_trabajo[$cont]['minutos_proyectados'];
                } 
                $xls->NewCell($min_acu,false,array('type'=>'int','background'=>$background));
                $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
                $acum_escena_producidas=0;
                for ($z=0; $z < count($unidad); $z++) { ;
                $acu_min_uni=0;$acu_seg_uni=0;
                $tiempo=$this->model_escenas_2->escenas_producidas_produccion2($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana'],$unidad[$z]['id']);
                if($tiempo){
                    //$acum_escena_producidas=$acum_escena_producidas+$tiempo['0']->total_escenas;
                }else{

                }
                    for ($y=0; $y<count($escenas_producidas); ++$y) {
                        if(strtotime($escenas_producidas[$y]->fecha_produccion) >= strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])  
                        AND strtotime($escenas_producidas[$y]->fecha_produccion) <= strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])
                        AND $unidad[$z]['id'] == $escenas_producidas[$y]->id_unidad){
                        $acu_min_uni+=$escenas_producidas[$y]->duracion_real_minutos;
                          $acu_seg_uni+=$escenas_producidas[$y]->duracion_real_segundos;
                          $acu_min_uni_sem+=$escenas_producidas[$y]->duracion_real_minutos;
                          $acu_seg_uni_sem+=$escenas_producidas[$y]->duracion_real_segundos;
                          $acu_min_real+=$escenas_producidas[$y]->duracion_real_minutos;   
                          $acu_seg_real+=$escenas_producidas[$y]->duracion_real_segundos;  
                        }
                    }
                }  

                
               
               
                $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
                $v=explode(':', $dif_min_real); 
                $acum_escena_producidas=round($escena_porminuto*$v['0']);
                 $escenas_pautadas=$this->model_dashboard->escenas_programadas($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana']);
                 $acum_escenas_pautadas=$acum_escenas_pautadas+$escenas_pautadas;
                 $total_acum_escena_producidas=$total_acum_escena_producidas+$acum_escena_producidas;

                $xls->NewCell($dif_min_real,false,array('background'=>'f8f2be'));
                $dif_min_acum=$this->calculo_tiempo($acu_min_real,$acu_seg_real);
                $xls->NewCell($dif_min_acum,false,array('background'=>$background));
                $min_real = explode(':',$dif_min_real);
                if($k==1){
                $segundos_real = $min_real[0]*60;
                $segundos_real = $segundos_real+$min_real[1];
                $segundos_sem_tra=$semanas_trabajo[$cont]['minutos_proyectados']*60;
                $total=$segundos_real-$segundos_sem_tra;
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
                if($segundos_2>=0 and $segundos_2<10){
                $segundos_2='0'.$segundos_2;
                }
                if($m_total>=0 and $m_total<10){
                $m_total='0'.$m_total;
                }
                if($m_total>-10 and $m_total<0){
                $m_total='0'.abs($m_total);
                $m_total='-'.$m_total;
                }
                $m_total_dif=$m_total.':'.$segundos_2;
                }else{
                $m_total_dif = $min_real[0].':'.$min_real[1];
                }
                $acu_dif_min_real+=$dif_min_real;
                $acu_dif_seg_real+=$min_real[1];
                $sem=explode(':',$acumtotal);
                $acum=explode(':',$m_total_dif);
                $min1=$sem[0]*60;
                $min2=$acum[0]*60;
                if($min1>=0){
                $seg1=$min1+$sem[1];
                }else{
                $seg1=$min1-$sem[1];
                }
                if($min2>=0){
                $seg2=$min2+$acum[1];  
                }else{
                $seg2=$min2-$acum[1];
                }
                $acumtotal=$seg2+$seg1;
                $acumtotal=$this->tiempo_segundos($acumtotal);
                $xls->NewCell($m_total_dif,false,array('background'=>$background));
                $temp= explode(':',$acumtotal);
                $xls->NewCell($acumtotal,false,array('background'=>$background));
                $count_escenas_proyec='';
                 
               if($k==1){ 
                  if($semanas_trabajo[$cont]['minutos_proyectados']!=0 and $escena_porminuto!=0){
                      $count_escenas_proyec=round($semanas_trabajo[$cont]['minutos_proyectados']*$escena_porminuto);
                      $acum_escena_proyec=$acum_escena_proyec+$count_escenas_proyec;
                     }else{
                      $count_escenas_proyec=0;
                     }
                  
               } 
                $xls->NewCell($count_escenas_proyec,false,array('background'=>$background));
                $xls->NewCell($acum_escena_proyec,false,array('background'=>$background));
                $xls->NewCell($acum_escena_producidas,false,array('background'=>$background));
                $xls->NewCell($total_acum_escena_producidas,false,array('background'=>$background));
                
                $xls->NewCell($escenas_pautadas,false,array('background'=>$background));
                $xls->NewCell($acum_escenas_pautadas,false,array('background'=>$background));

                $esc_dif=$acum_escena_producidas-$escenas_pautadas;
                $xls->NewCell($esc_dif,false,array('background'=>$background));
                $acum_esc_dif=$acum_esc_dif+$esc_dif;
                $xls->NewCell($acum_esc_dif,false,array('background'=>$background));
                $xls->CloseRow();
                $i++;
                $cont++;
            }
            $xls->GetXLS(true,'Plan Produccion');
    }

    public function excel_libretos($id){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }
      $capitulos = $this->model_capitulos->capitulos_produccion($id);
      $fecha= date("d-M-Y H:i:s");
      $data=$capitulos;
      $xls = new ExcelWriter();
      $xls_int = array('type'=>'int','border'=>'111111');
      $xls_date = array('type'=>'date','border'=>'111111');
      $xls_normal = array('border'=>'111111');
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper($fecha),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $xls->OpenRow();
      $arr = array('NRO. LIB','NRO. ESC','DESGLOSADAS','CANCELADAS','POR PRODUCIR','PRODUCIDAS','TIEMPO EST.','MIN. PROD.','MIN. CANCEL.','MIN. POR PRODUCIR','MIN. POST.','% PRODUCIDO','ESTADO','TIEMPO FLASHBACK','TOMA UBICACIÓN','TOTAL LOC','LOC. NUEVAS','LOC. CONT','NRO. SET','NRO. PROT.','NRO. REPARTO','NRO. FIGURANTE','NRO. EXTRA', 'TOTAL PERSONAJES','NRO VEHÍCULOS','NRO VEHÍCULOS BACKGROUND','VEHÍCULOS BACKGROUND','TOTAL VEHÍCULOS','ESC EST','MIN EST ESTUDIO','EVENTOS PEQUEÑOS','EVENTOS MEDIANOS','EVENTOS GRANDES'); 
      foreach($arr as $cod=>$val) $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->CloseRow();

      $cont=1;
      $cont2=1;
      $pag=1;
      $t2= sizeof($data);
      $t=$t2/20;
      $t=ceil($t);
      $cont_num_esc=0;
      $cont_esc_desg=0;
      $cont_prodc=0;
      $cont_esc_can=0;
      $cont_sin_producir=0;
      $cont_prodc=0;
      $cont_est='00:00';
      $cont_min_real='00:00';
      $cont_min_cancelados='00:00';
      $cont_min_por_producir='00:00';
      $cont_min_pos='00:00';
      foreach($data as $row){
          $xls->OpenRow();
          $xls->NewCell($row['numero'],false,array('type'=>'int'));
          if($row['numero_escenas']== ""){
              $numero_escenas="-"  ;
          }else{
              $numero_escenas=$row['numero_escenas'];
              $cont_num_esc=$numero_escenas+$cont_num_esc;
          }
          $xls->NewCell($numero_escenas,false,array('type'=>'int'));
          if($row['escenas_escritas']== ""){
              $escenas_escritas="-";
          }else{
              $escenas_escritas= $row['escenas_escritas'];
              $cont_esc_desg=$cont_esc_desg+$escenas_escritas;
          }
          $xls->NewCell($escenas_escritas,false,array('type'=>'int'));
          $num_can=$this->model_pdf->numero_escenas_canceladas($row['id_capitulo']);
          if($num_can){
          $num_can=$num_can;
          $cont_esc_can=$num_can+$cont_esc_can;
          }else{
          $num_can='-';
          }
          $xls->NewCell($num_can,false,array('type'=>'int'));
          $sin_producir=$row['escenas_escritas']-$row['escenas_producidas'];
          if($sin_producir){
              $sin_producir=$sin_producir;
              $cont_sin_producir=$cont_sin_producir+$sin_producir;
          }else{
              $sin_producir='-';
          }
          $xls->NewCell($sin_producir,false,array('type'=>'int'));
          $numero_escenas_producidas=$row['escenas_producidas'];
          if($numero_escenas_producidas){
           $numero_escenas_producidas=$numero_escenas_producidas;
           $cont_prodc=$cont_prodc+$numero_escenas_producidas;
          }else{
           $numero_escenas_producidas='-';
          } 
          $xls->NewCell($numero_escenas_producidas,false,array('type'=>'int'));
          if($row['duracion_estimada']!="" and $row['duracion_estimada']!='00:00'){
              $duracion_estimada= $row['duracion_estimada'];
              $tiempo = explode(":", $duracion_estimada);
              $tiempo2 = explode(":", $cont_est);
              if(isset($tiempo[2])){
                 $h=60/$tiempo[0]; 
                 $minutos=$tiempo[1]+$h;
                 $segundos=$tiempo[2];  

              }else{
                $minutos=$tiempo[0];
                $segundos=$tiempo[1];
              }
              if(isset($tiempo2[2])){
                  $h=60/$tiempo2[0]; 
                 $minutos2=$tiempo2[1]+$h;
                 $segundos2=$tiempo2[2]; 
     
              }else{
                $minutos2=$tiempo2[0];
                $segundos2=$tiempo2[1];
              }   

              $m=$minutos+$minutos2;
              $s=$segundos+$segundos2;
              
              $cont_est=$this->calculo_tiempo($m,$s);
          }else{
           $duracion_estimada= "00:00";
          }
          $xls->NewCell($duracion_estimada);
          if($row['duracion_real']==""){
              $duracion_real='00:00';
          }else{
              $duracion_real =$row['duracion_real'];
              $tiempo = explode(":", $duracion_real);
              $tiempo2 = explode(":", $cont_min_real);
              if(isset($tiempo[2])){
                 $h=60/$tiempo[0]; 
                 $minutos=$tiempo[1]+$h;
                 $segundos=$tiempo[2];  
              }else{
                $minutos=$tiempo[0];
                $segundos=$tiempo[1];
              }
              if(isset($tiempo2[2])){
                  $h=60/$tiempo2[0]; 
                 $minutos2=$tiempo2[1]+$h;
                 $segundos2=$tiempo2[2]; 
     
              }else{
                $minutos2=$tiempo2[0];
                $segundos2=$tiempo2[1];
              }   

              $m=$minutos+$minutos2;
              $s=$segundos+$segundos2;
              $cont_min_real=$this->calculo_tiempo($m,$s);
          }
          $xls->NewCell($duracion_real);
          $duracion_cancelada='00:00';
          $xls->NewCell($duracion_cancelada);
          $min_por_producir=$this->model_pdf->min_por_producir($row['id_capitulo']);
          if($min_por_producir){
              $minutos=$min_por_producir['0']->duracion_estimada_minutos;
              $segundos=$minutos*60;
              $segundos=$segundos+$min_por_producir['0']->duracion_estimada_segundos;
              $total=$segundos;
              $m_total=floor($total/60);
              $segundos_2=$total%60;
              if($m_total<=10){
                  $m_total='0'.$m_total;
              }
              if($segundos_2<=10){
                  $segundos_2='0'.$segundos_2;
              }
              $m_total2=$m_total.':'.$segundos_2;

              $tiempo = explode(":", $m_total2);
              $tiempo2 = explode(":", $cont_min_por_producir);
              if(isset($tiempo[2])){
                 $h=60/$tiempo[0]; 
                 $minutos=$tiempo[1]+$h;
                 $segundos=$tiempo[2];  
              }else{
                $minutos=$tiempo[0];
                $segundos=$tiempo[1];
              }
              if(isset($tiempo2[2])){
                  $h=60/$tiempo2[0]; 
                 $minutos2=$tiempo2[1]+$h;
                 $segundos2=$tiempo2[2]; 
     
              }else{
                $minutos2=$tiempo2[0];
                $segundos2=$tiempo2[1];
              }   

              $m=$minutos+$minutos2;
              $s=$segundos+$segundos2;
              $cont_min_por_producir=$this->calculo_tiempo($m,$s);

          }else{
              $m_total2='00:00';
          }
          $xls->NewCell($m_total2);
          $tiempo=$this->model_pdf->tiempo_escenas_pots_producidas($row['id_capitulo']);
          if($tiempo){
           $tiempo=$this->calculo_tiempo_post_redondeo($tiempo['0']->minutos,$tiempo['0']->segundos,$tiempo['0']->cuadros);
           //$cont_pots_prodc=$cont_pots_prodc+$numero_escenas_post_producidas;
          }else{
           $tiempo='00:00';
          } 
          $xls->NewCell($tiempo);
          if($numero_escenas_producidas!='-'){
              $por_cen=($numero_escenas_producidas)*100/$numero_escenas;
              $por_cen=round($por_cen);
              $por_cen='% '.$por_cen;
          }else{
              $por_cen='% 0';
          }
          $xls->NewCell($por_cen);
          switch($row['descripcion']){
            case 'En Progreso':
              $background_libreto='FF0068';
            break;
            case 'Escrito':
              $background_libreto='';
              $class_cap="cap_writed";
            break;
            case 'Producido':
              $background_libreto='FEE93E';
            break;
            case 'Entregado':
              $background_libreto='8815A6';
            break;
            case 'Desglosado':
              $background_libreto='85C646';
            break;
            case 'Cancelado':
              $background_libreto='000000';
            break;
            case 'No iniciado':
              $background_libreto='CCCCCC';
            break;
          }
          $xls->NewCell($row['descripcion'],false,array('background'=>$background_libreto));


          //CELDA TIEMPO FLASHBACK
          $xls->NewCell($this->calculo_tiempo($row['minutos_flash'],$row['segundos_flash']));
          //CELDA TOMAS UBICACION
          $xls->NewCell($row['escenas_toma'],false,array('type'=>'int'));
          //CELDA TOTAL LOCACION
          $xls->NewCell($row['cantidad_locaciones'],false,array('type'=>'int'));

          //CELDA LOCACIONES NUEVAS
          $nuevas_locaciones = $this->model_capitulos->nuevas_locaciones($row['id_capitulo'],$id);
          $xls->NewCell($nuevas_locaciones[0]['locaciones_nuevas'],false,array('type'=>'int'));

          //CELDA LOCACIONES CONTINUIDAD
          $xls->NewCell($row['cantidad_locaciones']-$nuevas_locaciones[0]['locaciones_nuevas'],false,array('type'=>'int'));

          //CELDA SETS
          $xls->NewCell($row['cantidad_sets'],false,array('type'=>'int'));

          //$personajes = $this->model_capitulos->personajes_capitulos($row['id_capitulo']);

         // $personajes = explode(',', $personajes[0]['cnt']);
          $total_personajes=0;
          $monto_figurante_extra = 0;
          $monto_figurante_extra_dolar = 0;
          $figurante=0;
          $reparto=0;
          $extra=0;

          $protagonistas=$this->model_capitulos->protagonistas_capitulos_id_capitulo($produccion['0']->id_produccion,$row['id_capitulo']);
          $protagonistas=$protagonistas['0']->total;

          if($produccion['0']->monto_figurante_extra){
              $monto_figurante_extra = $produccion['0']->monto_figurante_extra;
          }
          if($produccion['0']->monto_figurante_extra_dolar){
              $monto_figurante_extra_dolar = $produccion['0']->monto_figurante_extra_dolar;
          }
         $f = $this->model_capitulos->figurante_capitulos($row['id_capitulo'],$monto_figurante_extra,$monto_figurante_extra_dolar);
         if($f){
            $figurante=$f['0']->total;    
          }

          $r = $this->model_capitulos->reparto_capitulos($row['id_capitulo'],$monto_figurante_extra,$monto_figurante_extra_dolar);
             //echo $this->db->last_query().'<br>';
         if($r){
            $reparto=$r['0']->total;
           }

         $extras_t = $this->model_capitulos->extras_capitulos($row['id_capitulo']);

          if($extras_t){
            foreach ($extras_t as $e) {
              $extra=$extra+$e['cantidad'];  
            }
          }

          $total_personajes=$protagonistas+$figurante+$reparto+$extra;
         

          //CELDA PROTAGONISTAS
         /* $protagonistas=0;
          $figurante=0;
          $reparto=0;
          $extra=0;
          $total_personajes=0;
          if($personajes){
            for ($k=0; $k < count($personajes); $k++) { 
                $temp= explode('-', $personajes[$k]);
                if(isset($temp[1])){

                  if($temp[1]==1){
                    $total_personajes+=$protagonistas=$temp[0];
                  }

                  if($temp[1]==2){
                    $total_personajes+=$figurante=$temp[0];
                  }

                  if($temp[1]==3){
                    $total_personajes+=$reparto=$temp[0];
                  }
                  
                  // if($temp[1]==4){
                  //   $total_personajes+=$extra=$temp[0];
                  // }
                }
            }
          }

          $extras_t = $this->model_capitulos->extras_capitulos($row['id_capitulo']);

          if($extras_t){
            foreach ($extras_t as $e) {
              $extra=$extra+$e['cantidad'];  
            }
            $total_personajes= $total_personajes+$extra;
          }*/

          
          $xls->NewCell($protagonistas,false,array('type'=>'int'));
          //CELDA FIGURANTE
          $xls->NewCell($reparto,false,array('type'=>'int'));
          //CELDA REPARTO
          $xls->NewCell($figurante,false,array('type'=>'int'));
          //CELDA EXTRA
          $xls->NewCell($extra,false,array('type'=>'int'));
          //CELDA TOTAL PERSONAJES
          $xls->NewCell($total_personajes,false,array('type'=>'int'));
          

          $vehiculos = $this->model_capitulos->vehiculos_capitulo($row['id_capitulo']);
              
          //CELDA VEHICULOS DESGLOSADOS
          $xls->NewCell($vehiculos[0]['vehiculos_desglosados'],false,array('type'=>'int'));
          //CELDA NRO VEHICULOS DESGLOSADOS
          $xls->NewCell($vehiculos[0]['background_v'],false,array('type'=>'int'));
          //CELDA VEHICULOS BACK
          if($vehiculos[0]['vehiculo_background']!=""){
          $xls->NewCell($vehiculos[0]['vehiculo_background'],false,array('type'=>'int'));
          }else{
          $xls->NewCell(0,false,array('type'=>'int'));  
          }
          //CELDA TOTAL VEHICULOS
          $total_vehiculos = $vehiculos[0]['vehiculo_background']+$vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['background_v'];
          $xls->NewCell($total_vehiculos,false,array('type'=>'int'));
          
          //CELDA ESCENAS ESTUDIO
          $xls->NewCell($row['escenas_estudio'],false,array('type'=>'int'));
          //CELDA TIEMPO ESTUDIO
          $xls->NewCell($this->calculo_tiempo($row['minutos_estudio'],$row['segundos_estudio']));

          $eventos = $this->model_capitulos->eventos_capitulo($row['id_capitulo']);

          $pequenos=0;
          $medianos=0;
          $grandes=0;

          $eventos = explode(',', $eventos[0]['cnt']);
          for ($k=0; $k < count($eventos); ++$k) { 

              $temp= explode('-', $eventos[$k]);
              if(isset($temp[1])) {
                if($temp[1]==2){  
                  $pequenos=$temp[0];
                }

                if($temp[1]==3){
                  $medianos=$temp[0];
                }
                
                if($temp[1]==4){
                  $grandes=$temp[0];
                }
              }
          }
          //CELDA EVENTOS PEQUEÑOS
          $xls->NewCell($pequenos,false,array('type'=>'int'));
          //CELDA EVENTOS MEDIANOS
          $xls->NewCell($medianos,false,array('type'=>'int'));
          //CELDA EVENTOS GRANDES
          $xls->NewCell($grandes,false,array('type'=>'int'));
          $xls->CloseRow();
      }
      $xls->GetXLS(true,'Libretos');
    }

    public function excel_escenas($id,$idcapitulo){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
      $productor_general='';
      }
      $escenas = $this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo);
      
      $fecha= date("d-M-Y H:i:s");
      $data=$escenas;

      $xls = new ExcelWriter();
      $xls_int = array('type'=>'int','border'=>'111111');
      $xls_date = array('type'=>'date','border'=>'111111');
      $xls_normal = array('border'=>'111111');
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper($fecha),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
    
    
    $arr = array('NRO ESC','LOCACION', 'SET','CONT','TIEMPO-EST','TIEMPO-REAL','TIEMPO POST','UNIDAD','FECHA PROD','ESTADO');
    $xls->OpenRow();
   
  foreach($arr as $cod=>$val) 
    $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
    $xls->CloseRow();

    $cont=1;
    $cont2=1;
    $pag=1;
    $t2= sizeof($data);
    $t=$t2/20;
    $t=ceil($t);
    $cont_tiempo_estimado='00:00';
     if($data){
          foreach($data as $row){
          $xls->OpenRow();
          $xls->NewCell($row->numero_escena,false);
          $xls->NewCell(strtoupper($row->locacionnombre),true);
          $xls->NewCell(strtoupper($row->setnombre),true);
          $xls->NewCell($row->dias_continuidad,false,array('type'=>'int'));
          $duracion_estimada_segundos=$row->duracion_estimada_segundos;
          $duracion_estimada_minutos=$row->duracion_estimada_minutos;
          if($row->duracion_estimada_minutos!='00' and $row->duracion_estimada_minutos<=9 or $row->duracion_estimada_minutos=='0'){ $duracion_estimada_minutos=$row->duracion_estimada_minutos;}
          if($row->duracion_estimada_segundos!='00' and $row->duracion_estimada_segundos<=9 or $row->duracion_estimada_segundos=='0'){ $duracion_estimada_segundos=$row->duracion_estimada_segundos;}
          $tiempo2 = explode(":", $cont_tiempo_estimado);
          $minutos2=$tiempo2[0];
          $segundos2=$tiempo2[1];
          $m=$row->duracion_estimada_minutos+$minutos2;
          $s=$row->duracion_estimada_segundos+$segundos2;
          $cont_tiempo_estimado=$this->calculo_tiempo($m,$s);
          $xls->NewCell($duracion_estimada_minutos.':'.$duracion_estimada_segundos);
          if($row->duracion_real_minutos==""){
            $duracion_real_minutos='00';
          }else{
              if(strlen($row->duracion_real_minutos)<2){
                  $duracion_real_minutos='0'.$row->duracion_real_minutos;
              }else{
                $duracion_real_minutos=$row->duracion_real_minutos;
              }
          }
          if($row->duracion_real_segundos==""){
             $duracion_real_segundos='00';
          }else{
              if(strlen($row->duracion_real_segundos)<2){
                  $duracion_real_segundos='0'.$row->duracion_real_segundos;
              }else{
                  $duracion_real_segundos=$row->duracion_real_segundos;
              }
          }

          $duracion_real=$duracion_real_minutos.':'.$duracion_real_segundos;
          $xls->NewCell($duracion_real);

          /*if($row->tiempo_post_minutos==""){
            $tiempo_post_minutos='00';
          }else{
              if(strlen($row->tiempo_post_minutos)<2){
                  $tiempo_post_minutos='0'.$row->tiempo_post_minutos;
              }else{
                $tiempo_post_minutos=$row->tiempo_post_minutos;
              }
          }
          if($row->tiempo_post_segundos==""){
             $tiempo_post_segundos='00';
          }else{
              if(strlen($row->tiempo_post_segundos)<2){
                  $tiempo_post_segundos='0'.$row->tiempo_post_segundos;
              }else{
                  $tiempo_post_segundos=$row->tiempo_post_segundos;
              }
          }*/
         

          $duracion_post=$this->calculo_tiempo_post_redondeo($row->tiempo_post_minutos,$row->tiempo_post_segundos,$row->tiempo_post_cuadros);
          $xls->NewCell($duracion_post);
          if($row->estado == 1 AND $row->unidad_produccion!=""){
              if($row->unidad_produccion!=""){
                  $escena_unidad=$row->unidad_produccion;
              }else{
                  $escena_unidad= "-";
              } 
          }else{
              if($row->unidad_numero!=0){
                  $escena_unidad=$row->unidad_numero;
              }else{
                  $escena_unidad="-";
              } 
          } 
          $xls->NewCell($escena_unidad,false,array('type'=>'int'));
          if($row->estado == 1 AND $row->fecha_produccion!="" AND $row->fecha_produccion!="0000-00-00"){
              if($row->fecha_produccion!=""){
                  $unidad_fecha=date("d-M-Y",strtotime($row->fecha_produccion));
              }else{
                  $unidad_fecha="-";
              }
          }else{
              if($row->fecha_inicio!=""){
                  $unidad_fecha= date("d-M-Y",strtotime($row->fecha_inicio));
              }else{
                  $unidad_fecha="-";
              }
          }
          $xls->NewCell($unidad_fecha);
          switch($row->estado){
                  case 1:
                  $estado='PRODUCIDA';
                  $background_escena='FEE93E';
                  break;
                  case 2:
                  $estado='RETOMA';
                  $background_escena='FEC63E';
                  break;
                  case 3:
                  $estado='CANCELADA';
                  $background_escena='000000';
                  break;
                  case 4:
                  $estado='PROGRAMADA';
                  $background_escena='09EEE9';
                  break;
                  case 5:
                  $estado='NO GRABADA';
                  $background_escena='A6FFF9';
                  break;
                  case 6:
                  $estado='PROGRAMADA';
                  $background_escena='E3228D';
                  break;
                  case 7:
                  $estado='NO GRABADA';
                  $background_escena='EF7BBB';
                  break;
                  case 8:
                  $estado='PROGRAMADA';
                  $background_escena='8CD316';
                  break;
                  case 9:
                  $estado='NO GRABADA';
                  $background_escena='C1F378';
                  break;
                  case 10:
                  $estado='PROGRAMADA';
                  $background_escena='F7921E';
                  break;
                  case 11:
                  $estado='NO GRABADA';
                  $background_escena='FACA9A';
                  break;
                  default:
                  $estado='NO ASIGNADA';
                  $background_escena='A79980';
                  break;
              }
          
          $xls->NewCell($estado,false,array('background'=>$background_escena));
          $xls->CloseRow();
       }   
    }
    $xls->GetXLS(true,'Escena');
  }

  public function excel_plan_general(){
      $id=$this->input->post('idproduccion');
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
        $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
        $productor_general='';
      }

      $personajes_produccion = $this->model_plan_general->categoria_produccion($id, 'Personaje');
      if($this->input->post('consultaImpresion')==""){
        $escenas = $this->model_plan_general->listar_escenas($id, $personajes_produccion[0]->id,0,1000000);
        $sql= "INNER JOIN produccion_has_capitulos ON escena.id_capitulo = produccion_has_capitulos.id 
          WHERE escena.estado !=1  AND escena.estado !=3 AND escena.estado !=2 AND escena.estado !=12 AND escena.estado !=14
          AND produccion_has_capitulos.id_produccion = ".$id.";";
        $temporal = $this->model_escenas_2->suma_tiempos_ajax($sql);
        $tiempo_diponible = $this->calculo_tiempo_escenas($temporal);
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
          $tiempo_diponible = $this->calculo_tiempo_escenas($temporal);
        }
      }else{
        $escenas = false;
      }
      $escenas_noproducidas = $this->model_escenas_2->contar_escenas_noproducidas($id);
      
      
      $fecha= date("d-M-Y H:i:s");
      $data=$escenas;

      $xls = new ExcelWriter();
      $xls_int = array('type'=>'int','border'=>'111111');
      $xls_date = array('type'=>'date','border'=>'111111');
      $xls_normal = array('border'=>'111111');
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.$this->pasarMayusculas($fecha),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.$this->pasarMayusculas($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('ESCENAS: '.count($escenas),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('TIEMPO ESTIMADO: '.$tiempo_diponible,false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.$this->pasarMayusculas($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();


      $cont=1;
      $cont2=1;
      $t2= sizeof($data);
      $t=$t2/20;
      $t=ceil($t);
      $k=0;
      $t=0;
      $arr = array('UNI','FEC', 'LIB','ESC','PÁG','DÍA','LOC','SET','INT/EXT','D/N','LOC/SET','TIE.EST','TIE.REAL','PERSONAJES PRINCIPAL','PERSONAJES SECUNDARIOS','DESCRIPCION','ELEMENTOS');
      $xls->OpenRow();
      foreach($arr as $cod=>$val){ 
        $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      }

      $xls->CloseRow();
    if($data){      
     foreach($data as $row){
            $xls->OpenRow();
            $row_size = explode(',', $row->personajes_principales);
            if(count($row_size) < count(explode(',', $row->personajes_secundarios))){
                $row_size = explode(',', $row->personajes_secundarios);
            }

            if(count($row_size) < count(explode(',', $row->elementos))){
                $row_size = explode(',', $row->personajes_secundarios);;
            }
            if(count($row_size)<=0){
                $row_size=6;
            }else{
              $row_size =  count($row_size)*6; 
            }
            // echo $row->estado.'<br>';
            $temporal="";
              switch($row->estado){
                  case 1:
                  $color='FEE93E';
                  break;
                  case 2:
                  $color='fec63e';
                  break;
                  case 3:
                  $color='000000';
                  break;
                  case 4:
                  $color='09EEE9';
                  break;
                  case 5:
                  $color='A6FFF9';
                  break;
                  case 6:
                  $color='E3228D';
                  break;
                  case 7:
                  $color='EF7BBB';
                  break;
                  case 8:
                  $color='8CDD16';
                  break;
                  case 9:
                  $color='C1F378';
                  break;
                  case 10:
                  $color='F7921E';
                  break;
                  case 11:
                  $color='FACA9A';
                  break;
                  case 12:
                  $color='fee93e';
                  break;
                  case 14:
                  $class="fec63e";
                  break;
                  default:
                  $color='A79980';
                  break;    
              }
          /*if($row->unidad_numero!=0){
              $numero=$row->unidad_numero;
          }else{
              $numero='-';
          }*/
          if(($row->estado == 1 and  $row->unidad_produccion!=null) or ($row->estado == 2 and  $row->unidad_produccion!="") ){
            $numero= $row->unidad_produccion_numero;  
          }else{
              if ($row->unidad_numero != 0) {
                  $numero= $row->unidad_numero;
              } else {
                  $numero= '-';
              }
          } 
          $xls->NewCell($numero,false,array('background'=>$color));
          if (($row->estado == 1 and  $row->fecha_produccion!=null and  $row->fecha_produccion!="0000-00-00") or ($row->estado == 2 and  $row->fecha_produccion!=null and  $row->fecha_produccion!="0000-00-00") ) {
              $fecha_inicio= $row->fecha_produccion_2;
          } else {
            if ($row->fecha_inicio != null and $row->fecha_inicio != "0000-00-00") {
              $fecha_inicio=$row->fecha_inicio;
            } else {
              $fecha_inicio='-';
            }
         }
         
          $xls->NewCell($fecha_inicio,false,array('background'=>$color));
          $xls->NewCell($row->capitulo,false,array('type'=>'int'));
          $xls->NewCell($row->numero_escena,false,array('type'=>'int'));
          $xls->NewCell(str_replace ( '-' , '─', $row->libreto ));
          $xls->NewCell($row->dias_continuidad);
          $xls->NewCell($this->pasarMayusculas($row->locacion),true);
          $xls->NewCell($this->pasarMayusculas($row->setnombre),true);
          $xls->NewCell($this->pasarMayusculas($row->ubicacion));
          $tiempo=$row->tiempo;
          $xls->NewCell($this->pasarMayusculas($row->tiempo));
          $tipo=$row->tipo;
          $xls->NewCell($this->pasarMayusculas($tipo));
          if(strlen($row->duracion_estimada_minutos)<2){
              $duracion_estimada='0'.$row->duracion_estimada_minutos.':';
          }else{
              $duracion_estimada=$row->duracion_estimada_minutos.':';
          }
          if(strlen($row->duracion_estimada_segundos)<2){
              $duracion_estimada=$duracion_estimada.'0'.$row->duracion_estimada_segundos;
          }else{
              $duracion_estimada=$duracion_estimada.$row->duracion_estimada_segundos;
          }
          $xls->NewCell($duracion_estimada);
          if(strlen($row->duracion_real_minutos)<2){
              $duracion_real='0'.$row->duracion_real_minutos.':';
          }else{
              $duracion_real=$row->duracion_real_minutos.':';
          }

          if(strlen($row->duracion_real_segundos)<2){
              $duracion_real=$duracion_real.'0'.$row->duracion_real_segundos;
          }else{
              $duracion_real=$duracion_real.$row->duracion_real_segundos;
          }
          $xls->NewCell($duracion_real);
         /* if(strlen($row->tiempo_post_minutos)<2){
              $duracion_post='0'.$row->tiempo_post_minutos.':';
          }else{
              $duracion_post=$row->tiempo_post_minutos.':';
          }

          if(strlen($row->tiempo_post_segundos)<2){
              $duracion_post=$duracion_post.'0'.$row->tiempo_post_segundos;
          }else{
              $duracion_post=$duracion_post.$row->tiempo_post_segundos;
          }
          $xls->NewCell($duracion_post);*/
          $xls->NewCell($this->pasarMayusculas($row->personajes_principales),true);
          $xls->NewCell($this->pasarMayusculas($row->personajes_secundarios),true);
          $arrayName = array("<", ">");
          $xls->NewCell(str_replace($arrayName, '', $this->pasarMayusculas($row->descripcion)),false, array('width'=>200,'height'=>100));
          $xls->NewCell($this->pasarMayusculas($row->elementos),true);
          $xls->CloseRow();
       }
      // $xls->setRowHeight(-200); 
       $xls->GetXLS(true,'Plan General');
     }else{
      echo "No hay resultado";
     }
  }

  public function calculo_tiempo_escenas($escenas){
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

  public function excel_plan_diario($id='',$id_unidad='',$fecha_unidad=''){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $capitulos = $this->model_capitulos->capitulos_produccion_2($id);
      $unidades=$this->model_plan_produccion->unidades_id_produccion($id);
      $sql="";
      $escenas="";
      $unidad="";
      $cruce="";
      $ultima_edicion="";
      $fechas_bloqueadas = $this->fechas_bloqueadas($id);
      $plan_select ="";


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
        $sql .= ",(SELECT count(DISTINCT(escena.id))
                FROM escenas_has_elementos
                INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                INNER JOIN plan_diario_has_escenas_has_unidades 
                ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
                INNER JOIN elemento ele2 ON ele2.id = escenas_has_elementos.id_elemento
                INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id 
                INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                WHERE plan_diario.id_unidad = ".$unidades[$i-1]['id']." AND plan_diario.fecha_inicio = '".$fecha_unidad."' AND ele2.id= ele1.id) AS cantidad".$i;
      }

     if($id_unidad!=''){
        $data=array('id_unidad'=>$id_unidad,'fecha'=>$fecha_unidad);
        if($fecha_unidad==''){
          $escenas=$this->model_plan_diario->escenas_unidad($id_unidad);
        }else{
          $escenas=$this->model_plan_diario->unidad_id_fecha($data);
        }
        $unidad=$this->model_plan_diario->unidad_id($id_unidad);
        $fecha=date('Y-m-d');
        $plan_select = $this->model_plan_general->buscar_plan_diario_fecha($fecha_unidad,$id_unidad);
        if($plan_select){
          $ultima_edicion = $this->model_plan_diario->ultima_edicion($plan_select[0]->id);
        }
        if($escenas){
          $user_dia=$this->model_plan_diario->unidad_dia($escenas['0']['id_plan_diario'],$fecha);
          $comentarios=$this->model_plan_diario->comentarios_user($escenas['0']['id_plan_diario']);
          $data['comentarios']=$comentarios;
          if($user_dia){
            $director_dia=$this->model_admin->user_id($user_dia['0']->id_director);
            $script_dia=$this->model_admin->user_id($user_dia['0']->id_script);
            $data['director_dia']=$director_dia;
            $data['script_dia']=$script_dia;
          }
        }
        $cruce=$this->model_plan_diario->cruces_elementos($id,$fecha_unidad,$sql);
      }
      $director_unidad=$this->model_produccion->tipo_usuario_otros(5,7);
      $script=$this->model_produccion->tipo_usuario_otros(5,1);
      
      $xls = new ExcelWriter();
      
      $xls->OpenRow();
      $xls->NewCell('UNIDAD: '.$unidad['0']['numero'],false);
      $xls->NewCell('FECHA: '.strtoupper(date("d-M-Y",strtotime($fecha_unidad))),false);
      $xls->CloseRow();
      $xls->OpenRow();
       if(isset($director_dia) and $director_dia ){ 
          $xls->NewCell('DIRECTOR: '.strtoupper($director_dia['0']->nombre.' '.$director_dia['0']->apellido),false);

       } else { 
          $xls->NewCell('DIRECTOR: '.strtoupper($unidad['0']['dir']),false);           
       }
       $xls->NewCell('');
       $xls->NewCell('');
       if(isset($script_dia) and $script_dia) { 
        $xls->NewCell('SCRIPT: '.strtoupper($script_dia['0']->nombre.' '.$script_dia['0']->apellido),false);  
      } else{
        $xls->NewCell('SCRIPT: '.strtoupper($unidad['0']['scr']),false);  
      } 
      $xls->NewCell('');
      if ($escenas['0']['llamado']){ 
        $xls->NewCell('LLAMADO: '.strtoupper($escenas['0']['llamado']),false); 
      }else{
        $xls->NewCell('LLAMADO: -',false); 
      }
      $xls->NewCell('LUGAR: -',false); 
     if ($escenas['0']['lugar']){ 
       $xls->NewCell('LUGAR: '.strtoupper($escenas['0']['lugar']),false); 
     }else{ 
        $xls->NewCell('LUGAR: -',false); 
     }
     
     if ($escenas['0']['wrap_time'] AND $escenas['0']['wrap_time'] !='00:00:00'){ 
        $xls->NewCell('WRAP TIME: '.date("H:i",strtotime($escenas['0']['wrap_time'])),false);
      }else{ 
          $xls->NewCell('WRAP TIME: -',false); 
      } 
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('ESCENAS PAUTADAS:'); 
      $xls->NewCell('ESCENAS PRODUCIDAS:'); 
      $xls->NewCell('ESCENAS POR PRODUCIR:'); 
      $xls->NewCell('TOTAL TIEMPO ESTIMADO:'); 
      $xls->NewCell('TOTAL TIEMPO PRODUCIDO:'); 
      $xls->NewCell('MINUTOS POR PRODUCIR:'); 
      $xls->CloseRow();
      $xls->OpenRow();
      $total_escenas_pautadas=sizeof($escenas);
      $xls->NewCell($total_escenas_pautadas); 
      $total_producidas=0; 
      if($escenas){
              foreach ($escenas as $e) {
                if($e['estado_escenas']==1){
                  $total_producidas=$total_producidas+1;
                }
              }
      } 
      $xls->NewCell($total_producidas); 
      $total_pro_producir=$total_escenas_pautadas-$total_producidas;
      $xls->NewCell($total_pro_producir); 
      $duracion_minutos_es=0; $duracion_segundos_es=0;
      if($escenas){
         foreach ($escenas as $e) {
              $duracion_minutos_es=$e['duracion_estimada_minutos']+$duracion_minutos_es;
              $duracion_segundos_es=$e['duracion_estimada_segundos']+$duracion_segundos_es;

              } 
      }
      $tiempo=$this->calculo_tiempo($duracion_minutos_es,$duracion_segundos_es);
      $xls->NewCell($tiempo);
      $duracion_minutos_pro=0; $duracion_segundos_pro=0; 
      if($escenas){
            foreach ($escenas as $e) {
              if($e['estado_escenas']==1){
                $duracion_minutos_pro=$e['duracion_real_minutos']+$duracion_minutos_pro;
                $duracion_segundos_pro=$e['duracion_real_segundos']+$duracion_segundos_pro;
              }
            } 
        }
      $tiempo_prod=$this->calculo_tiempo($duracion_minutos_pro,$duracion_segundos_pro); 
      $xls->NewCell($tiempo_prod); 
      $duracion_minutos_por_pro=0; 
      $duracion_segundos_por_pro=0; 
      $tiempo_por_prod="00:00";
      if($escenas){
          foreach ($escenas as $e) {
               if($e['estado_escenas']!=1){
                  $duracion_minutos_por_pro=$e['duracion_estimada_minutos']+$duracion_minutos_por_pro;
                  $duracion_segundos_por_pro=$e['duracion_estimada_segundos']+$duracion_segundos_por_pro;
                }
           } 
      $tiempo_por_prod=$this->calculo_tiempo($duracion_minutos_por_pro,$duracion_segundos_por_pro);
      }
      $xls->NewCell($tiempo_por_prod); 
      $xls->CloseRow();

      $arr = array('#','LIB','ESC','CONT','PÁGINA','DESCRIPCIÓN','GUIÓN','DÍA/NOCHE','INT/EXT','LOC/EST','LOCACION','SET','PERSONAJES PRINCIPALES', 'PERSONAJES SECUNDARIOS','ELEMENTOS','TIEMPO EST.','TIEMPO REAL','COM. ENSAYO','COM. PROD.','FIN PROD.','COMENTARIOS');
      $xls->OpenRow();
      foreach($arr as $cod=>$val) $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->CloseRow();
       $i=1;
       foreach ($escenas as $e) { 
         if($e['estado_escenas']==1){
            $background_escena="FEE93E";
          }else{
            if($e['fecha_produccion']==$e['fecha_inicio'] AND $e['unidad_produccion']==$e['id_unidad']){
                $background_escena="FEE93E";
            }else{
                $tiempo = $this->model_plan_diario->retomas_escena($e['id_escena'],$e['fecha_inicio'],$e['id_unidad']); 
                if($tiempo){
                  $background_escena="FEE93E";
                }else{
                  $background_escena="";
                }
            }
            
        }

        $xls->OpenRow();
        $xls->NewCell($i,false,array('type'=>'int','background'=>$background_escena));
        $xls->NewCell($e['numero'],false,array('type'=>'int','background'=>$background_escena));
        $xls->NewCell($e['numero_escena'],false,array('type'=>'int','background'=>$background_escena));
        $xls->NewCell($e['dias_continuidad'],false,array('type'=>'int','background'=>$background_escena));
        $xls->NewCell($e['libreto'],false,array('type'=>'int','background'=>$background_escena));
        $search  = array('<', '>');
        $xls->NewCell(str_replace($search,' ',$e['descripcion_escena']),true,array('background'=>$background_escena));
        $xls->NewCell($e['guion'],true,array('background'=>$background_escena));
        $xls->NewCell($e['des_dia'],false,array('background'=>$background_escena));
        $xls->NewCell($e['des_int'],false,array('background'=>$background_escena));
        $xls->NewCell($e['tipo'],false,array('background'=>$background_escena));
        $xls->NewCell($e['nom_locacion'],true,array('background'=>$background_escena));
        $xls->NewCell($e['nom_set'],true,array('background'=>$background_escena));
        $cadena_personajes="";
        $personajes=$this->model_plan_diario->elemento_personajes_principales($e['id_escena']);
        if($personajes){
            foreach ($personajes as $p) {
           $cadena_personajes.= $p['nombre'].',';
            } 
        }else{
          $cadena_personajes="";
        }
        $xls->NewCell($cadena_personajes,true,array('background'=>$background_escena));

        $personajes=$this->model_plan_diario->elemento_personajes_secundarios($e['id_escena']);
        if($personajes){
            foreach ($personajes as $p) {
           $cadena_personajes.= $p['nombre'].',';
            } 
        }else{
          $cadena_personajes="";
        }
        $xls->NewCell($cadena_personajes,true,array('background'=>$background_escena));

        $personajes=$this->model_plan_diario->elementos_dif_personajes($e['id_escena']);
        if($personajes){
          $cadena_elementos=""; 
            foreach ($personajes as $p) {
              $cadena_elementos.= $p['nombre'].',';
            }
        }else{
          $cadena_elementos=""; 
        }            
        $xls->NewCell($cadena_elementos,false,array('background'=>$background_escena));

        if(strlen($e['duracion_estimada_minutos'])<2){
            $dureacion_estimada_minutos= '0';
          }else{ 
            $dureacion_estimada_minutos=$e['duracion_estimada_minutos'];
          }
          if(strlen($e['duracion_estimada_segundos'])<2){
            $dureacion_estimada_segundos='0';}
          else{ 
            $dureacion_estimada_segundos=$e['duracion_estimada_segundos'];
          }
          $xls->NewCell($dureacion_estimada_minutos.':'.$dureacion_estimada_segundos,false,array('background'=>$background_escena));
          
       if($e['duracion_real_minutos'] AND ($e['duracion_real_minutos']!='00' OR $e['duracion_real_segundos']!='00') ){ 
           if(strlen($e['duracion_real_minutos'])<2){
            $dureacion_rela_minutos= '0';
          }else{ 
            $dureacion_rela_minutos=$e['duracion_real_minutos'];
          }
          if(strlen($e['duracion_real_segundos'])<2){
            $dureacion_rela_segundos='0';}
          else{ 
            $dureacion_rela_segundos=$e['duracion_real_segundos'];
          }
          $xls->NewCell($dureacion_rela_minutos.':'.$dureacion_rela_segundos,false,array('background'=>$background_escena));
        }else{
           $xls->NewCell('-',false,array('background'=>$background_escena));
        }
      
        if($e['comienzo_ens']){ 
          $xls->NewCell(date("h:i",strtotime($e['comienzo_ens'])),false,array('background'=>$background_escena));
        }else{
          $xls->NewCell('-',false,array('background'=>$background_escena));
        }
        if($e['comienzo_prod']){
         $xls->NewCell($e['comienzo_prod'],false,array('background'=>$background_escena));
        }else{
          $xls->NewCell('-',false,array('background'=>$background_escena));
        }
        if($e['fin_produccion']){
         $xls->NewCell($e['fin_produccion'],false,array('background'=>$background_escena));
        }else{
         $xls->NewCell('-',false,array('background'=>$background_escena));
        } 
        $xls->NewCell($e['comentarios'],false,array('background'=>$background_escena));
        $i++;
        $xls->CloseRow();
      }
      $xls->GetXLS(true,'Plan Diario');
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

    public function excel_elementos($idproduccion,$carga,$id_categoria,$limite_inferior,$limite_superior,$roles=''){
      $cadena = "";
      $tbl4="";
      $produccion=$this->model_plan_produccion->produccion_id($idproduccion);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
      $categorias_elementos=$this->model_elementos->categorias_elementos($idproduccion);

      $capitulos = $this->model_capitulos->capitulos_produccion_2($idproduccion);

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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
        $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
        $productor_general='';
      }
      $xls = new ExcelWriter();
      $xls_int = array('type'=>'int','border'=>'111111');
      $xls_date = array('type'=>'date','border'=>'111111');
      $xls_normal = array('border'=>'111111');
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper(date('Y-m-d')),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      if($carga==1){
        $sql='';
          $roles_selec=explode('-',$roles);
          $rol=array();
          if($roles){
            $sql.=' AND (';
              $cont=0;
            foreach ($roles_selec as $r) {
                  if($r=='Protagonista'){
                    $id_rol=1;
                  }elseif ($r=='Reparto') {
                    $id_rol=3;
                  }elseif ($r=='Figurante') {
                    $id_rol=2;
                  }elseif ($r=='Extra') {
                    $id_rol=4;
                  }
                  if($cont==0){
                    $sql.=' e.rol='.$id_rol.' ';  
                    $cont++;
                  }else{
                    if($r){
                     $sql.=' OR e.rol='.$id_rol.' ';  
                    }  
                  }
              array_push($rol,$r);
            }
            $sql.=' )';
          }

           if($limite_inferior=='null'){
               $limite_inferior=$capitulos['0']['numero'];
             }

             if($limite_superior=='null'){
              $ultimo=end($capitulos);
              $limite_superior=$ultimo['numero'];
             }
        //$elementos=$this->model_elementos->elementos_limite($idproduccion,$id_categoria,$limite_elementos,$limite_inferior,$limite_superior,$sql);
        $elementos=$this->model_elementos->elemento_id_produccion_limit2($idproduccion,$id_categoria,$limite_inferior,$limite_superior,$sql);
        if($elementos){
          if($categorias_elementos[0]['id']==$id_categoria) {
            $arr = array('NOMBRE','ROL','DESCRIPCIÓN','ACTOR','TOTAL LIBRETOS','CONTRATO','USOS','% DE USO','ESC PROD','ESC POR PROD');
          }else{
            $arr = array('NOMBRE','DESCRIPCIÓN','USOS','% DE USO','ESC PROD','ESC POR PROD');
          }
          $xls->OpenRow();
          foreach($arr as $cod=>$val){ 
            $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
          }
          $xls->CloseRow();
          foreach ($elementos as $e){
            $xls->OpenRow();
            $esc_producidas=$this->model_elementos->escenas_producidas($e['id_elemento']);
            $esc_porproducir=$this->model_elementos->escenas_proproducir($e['id_elemento']);
            $uso=$this->model_elementos->escenas_has_elementos($e['id_elemento']);
            $total_escenas=$this->model_elementos->total_escenas_idProduccion($idproduccion);
            if($uso==0){
              $por=0;
            }else{
              $por=($uso*100)/$total_escenas[0]->total;
              $por=round($por);  
            }
            $xls->NewCell(strtoupper($e['nombre']));
            
            if($categorias_elementos[0]['id']==$id_categoria) {
              $xls->NewCell(strtoupper($e['rol']));
            }
            $xls->NewCell(strtoupper($e['des_elem']));
            if($categorias_elementos[0]['id']==$id_categoria) {
              $xls->NewCell(strtoupper($e['actor_nombre']." ".$e['actor_apellido']));
              $libretos_elementos=$this->model_elementos->libretos_elementos($e['id_elemento']);
              $libretos_elementos=$libretos_elementos['0']->libretos;
              $xls->NewCell(count(explode(',', $libretos_elementos)));
              $xls->NewCell(strtoupper($e['tipo_contrato']));
             }
            $xls->NewCell($uso,false,array('type'=>'int'));
            $xls->NewCell(strtoupper($por.'%'));
            $xls->NewCell($esc_producidas[0]->total,false,array('type'=>'int'));
            $xls->NewCell($esc_porproducir[0]->total,false,array('type'=>'int'));
            $xls->CloseRow();
          }
          $xls->GetXLS(true,'Elementos');
          }else{
            echo "No hay resultado";
          }
        }else if($carga==2){
          $locacion=$this->model_elementos->locacion_usos2($idproduccion);
          if($locacion){
            $arr = array('NOMBRE','USOS','% DE USO','ESC PROD','ESC POR PROD');
            $xls->OpenRow();
            foreach($arr as $cod=>$val){ 
              $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
            }
            $xls->CloseRow();
            foreach ($locacion as $l) {
              $xls->OpenRow();
              $esc_producidas=$this->model_elementos->escenas_producidas_idcapitulo($l['id']);
              $esc_porproducir=$this->model_elementos->escenas_porproducidas_idcapitulo($l['id']);
              $total_escenas=$this->model_elementos->total_escenas_idProduccion($idproduccion);
              if($l['uso']==0){
                $por=0;
              }else{
                $por=($l['uso']*100)/$total_escenas[0]->total;
                $por=round($por);  
              }
              $xls->NewCell(strtoupper($l['nombre']));
              $xls->NewCell($l['uso']);
              $xls->NewCell(strtoupper($por.'%'));
              $xls->NewCell($esc_producidas[0]->total);
              $xls->NewCell($esc_porproducir[0]->total);
              $xls->CloseRow();
            }
            $xls->GetXLS(true,'Locaciones');
          }else{
            echo "No hay resultados";
          }
        } else{
          //echo $carga;
          $locacion_t=$this->model_escenas->locacion_id($id_categoria);
          if($carga == 3){
            $sets=$this->model_elementos->set_escena($id_categoria);
          }else{
            $sets=$this->model_elementos->sets_produccion($idproduccion);
            //echo $this->db->last_query();
          }
             if($carga==4){
              $arr = array('NOMBRE', 'LOCACIÓN', 'USOS','% DE USO','ESC PROD','ESC POR PROD');
             }else{
              $arr = array('NOMBRE','USOS','% DE USO','ESC PROD','ESC POR PROD');
             }
            
            $xls->OpenRow();
            foreach($arr as $cod=>$val){ 
              $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
            }
            $xls->CloseRow();
           if($sets){
           foreach ($sets as $l) {
            $xls->OpenRow();
            $esc_producidas=$this->model_elementos->escenas_producidas_id_sets($l['id']);
            $esc_porproducir=$this->model_elementos->escenas_porproducidas_idsets($l['id']);
            $total_escenas=$this->model_elementos->total_escenas_idProduccion($idproduccion);
            $uso=$l['uso'];
            if($uso==0){
              $por=0;
            }else{
              $por=($uso*100)/$total_escenas[0]->total;
              $por=round($por);  
            }
            $xls->NewCell(strtoupper($l['nombre']));
            if($carga==4){
              $xls->NewCell(strtoupper($l['nombre_locacion']));
            }
            $xls->NewCell($l['uso']);
            $xls->NewCell(strtoupper($por.'%'));
            $xls->NewCell($esc_producidas[0]->total);
            $xls->NewCell($esc_porproducir[0]->total);
            $xls->CloseRow();
          }
          $xls->GetXLS(true,'Sets');
        }
      }
  }


  public function post_produccion_capitulos($id){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      $capitulos=$this->model_post_produccion->capitulos_escenas2($id);
      $capitulos_escenas_prod=$this->model_post_produccion->capitulos_escenas_prod($id);
      $capitulos_escenas_post=$this->model_post_produccion->capitulos_escenas_post($id);

      $min_produccidos=$this->calculo_tiempo($capitulos_escenas_prod['0']->minutos,$capitulos_escenas_prod['0']->segundos);
      $mins_post_produccidos=$this->calculo_tiempo_post($capitulos_escenas_post['0']->minutos,$capitulos_escenas_post['0']->segundos,$capitulos_escenas_post['0']->cuadros);
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
      $minutos=$this->calculo_tiempo($minutos,$segundos);
      $total_escenas=$capitulos_escenas_prod['0']->total_escenas;
      $escenas_post_produccidas=$capitulos_escenas_post['0']->total_escenas;
      $dif=$capitulos_escenas_prod['0']->total_escenas-$capitulos_escenas_post['0']->total_escenas;

      $hora=date('H:i:s');
      $fecha=date('Y-m-d');
      $fecha=date("d-M-Y ",strtotime($fecha));
      $xls = new ExcelWriter();
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),true,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper($fecha).' '.$hora,false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('MIN. PRODUCIDOS:',false);
      $xls->NewCell('',false);
      $xls->NewCell($min_produccidos,false);
      $xls->NewCell('MIN. POTS-PRODUCIDOS:',false);
      $xls->NewCell('',false);
      $xls->NewCell($mins_post_produccidos,false);
      $xls->NewCell('DIFERENCIA. DISPONIBLES:',false);
      $xls->NewCell('',false);
      $xls->NewCell($negativo.' '.$minutos,false);
      $xls->NewCell('ESC. PRODUCIDAS:',false);
      $xls->NewCell('',false);
      $xls->NewCell($total_escenas,false);
      $xls->NewCell('ESC. POTS-PRODUCIDOS:',false);
      $xls->NewCell('',false);
      $xls->NewCell($escenas_post_produccidas,false);
      $xls->NewCell('DIFERENCIA ESC:',false);
      $xls->NewCell('',false);
      $xls->NewCell($dif,false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $arr = array('CAP','ESTATUS','NRO. ESC','LIB','T. ESTIMANDO','T. REAL','T. POST','T.EXTRA','TOTAL','DIFERENCIA','RESPONSABLE','ENTREGA','ENTREGADA');
      $xls->OpenRow();
      foreach($arr as $cod=>$val) $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->CloseRow();
      if($capitulos){
        foreach ($capitulos as $c) {
          $xls->OpenRow();
          $xls->NewCell($c['numero']);
          $estado = explode(',', $c['estado']);
          $campos_estado="";
          for ($i=0; $i < count($estado); $i++) { 
            $estado_base = explode('_', $estado[$i]);
            switch($estado_base[1]){
              case 'No producido':
                $class_cap="no_prod";
                $campos_estado .="<div class='no_prod'>".$estado_base[1]."</div>";
              break;
              case 'LOGGING/INGESTANDO':
                $class_cap="log_ing";
                $campos_estado .="<div class='log_ing'>".$estado_base[1]."</div>";
              break;
              case 'PRE-EDITANDO':
                $class_cap="pre_edi";
                $campos_estado .="<div class='pre_edi'>".$estado_base[1]."</div>";
              break;
              case 'EDITANDO':
                $class_cap="edi";
                $campos_estado .="<div class='edi'>".$estado_base[1]."</div>";
              break;
              case 'FINALIZANDO':
                $class_cap="fin";
                $campos_estado .="<div class='fin'>".$estado_base[1]."</div>";
              break;
              case 'CODIFICANDO APP VIDEO':
                $class_cap="cod_app_vid";
                $campos_estado .="<div class='cod_app_vid'>".$estado_base[1]."</div>";
              break;
              case 'QC RTI TECNICO':
                $class_cap="qc_rti_tec";
                $campos_estado .="<div class='qc_rti_tec'>".$estado_base[1]."</div>";
              break;
              case 'QC RTI PRODUCTOR':
                $class_cap="qc_rti_por";
                $campos_estado .="<div class='qc_rti_por'>".$estado_base[1]."</div>";
              break;
              case 'MONTANDO ARCHIVO LTO':
                $class_cap="mon_arc_lto";
                $campos_estado .="<div class='mon_arc_lto'>".$estado_base[1]."</div>";
              break;
              case 'CODIFICANDO A CLIENTE':
                $class_cap="cod_cli";
                $campos_estado .="<div class='cod_cli'>".$estado_base[1]."</div>";
              break;
              case 'ENVIANDO A CLIENTE':
                $class_cap="env_cli";
                $campos_estado .="<div class='env_cli'>".$estado_base[1]."</div>";
              break;
              case 'QC CLIENTE':
                $class_cap="qc_cli";
                $campos_estado .="<div class='qc_cli'>".$estado_base[1]."</div>";
              break;
              case 'SESION DE PROTOOLS':
                $class_cap="arc_cap";
                $campos_estado .="<div class='arc_cap'>".$estado_base[1]."</div>";
              break;
              case 'MONTANDO EDL PS':
                $class_cap="mon_edl_ps";
                $campos_estado .="<div class='mon_edl_ps'>".$estado_base[1]."</div>";
              break;
              case 'CAPITULO ENTREGADO':
                $class_cap="cap_ent";
                $campos_estado .="<div class='cap_ent'>".$estado_base[1]."</div>";
              break;
              case 'CANCELADO':
                $class_cap="cap_cancel";
                $campos_estado .="<div class='cap_cancel'>".$estado_base[1]."</div>";
              break;
            }
          }
          $xls->NewCell($campos_estado,false);
          $xls->NewCell($c['total_escenas']);
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
          $xls->NewCell($lib);
          $tiempo_estimados=$this->calculo_tiempo2($c['minutos_estimados'],$c['segundos_estimados']);
          $tiempo_real=$this->calculo_tiempo2($c['minutos'],$c['segundos']);
          $tiempo_post=$this->calculo_tiempo_post($c['minutos_post'],$c['segundos_post'],$c['cuadros']);
          $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id,$c['numero']); 
          if($fecha_entrega){
            $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));
          }else{
            $fecha_entrega='-'; 
          } 
          if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
            $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
          }else{
            $fecha_entregada='-'; 
          } 
          $xls->NewCell($tiempo_estimados);
          $xls->NewCell($tiempo_real);
          $xls->NewCell($tiempo_post);
          $m_extra=$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['cabezote_minutos']+$c['credito_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['foto_minutos']+$c['imagenes_archivos_minutos'];
          $s_extra=$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['cabezote_segundos']+$c['credito_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['foto_segundos']+$c['imagenes_archivos_segundos'];
          $c_extra=$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['cabezote_cuadros']+$c['credito_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['foto_cuadros']+$c['imagenes_archivos_cuadros'];
          $tiempo_extra=$this->calculo_tiempo_post($m_extra,$s_extra,$c_extra);
          $xls->NewCell($tiempo_extra);
          //$tiempo_extra=$this->calculo_tiempo($m_extra,$s_extra);
          $total=$this->calculo_tiempo_post_redondeo($c['minutos_post']+$m_extra,$c['segundos_post']+$s_extra,$c['cuadros']+$c_extra);
          $xls->NewCell($total);
          $c_m=explode(':', $tiempo_post);
          $c_s=explode('-', $c_m['1']);
          if($c_s['1']>15){
            $segundos_post_total=$c_s['0']+1;
          }else{
            $segundos_post_total=$c_s['0'];
          }
          $s_d=$c['segundos']-$segundos_post_total;
          if($s_d<0){
            $s_d=$s_d*-1;
          }
         
          

          $m_d=$c['minutos']-$c_m['0'];
          
          $diferencia=$this->calculo_tiempo($m_d,$s_d);
          $xls->NewCell($diferencia);
          $xls->NewCell($c['responsable']);
          $xls->NewCell($fecha_entrega);
          $xls->NewCell($fecha_entregada);
          $xls->CloseRow();
        }  
      }    
      $xls->GetXLS(true,'Post produccion');
  }

    public function post_produccion_escenas($id,$id_capitulo){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      $escenas=$this->model_post_produccion->escenas_id_capitulo2($id_capitulo);
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $capitulo=$this->model_post_produccion->capitulo_id($id_capitulo);
      $tiempo_real=$this->model_post_produccion->escenas_id_capitulo_tiempo_real($id_capitulo);
      $tiempo_post=$this->model_post_produccion->escenas_id_capitulo_tiempo_post($id_capitulo);
      $libretos = $this->model_capitulos->capitulos_produccion_2($id);
      $vitacora=$this->model_post_produccion->vitacora($id_capitulo);
      $id_user=$this->session->userdata('id_pruduction_suite');

      $tiempo_realm=$this->calculo_tiempo($tiempo_real['0']->minutos,$tiempo_real['0']->segundos);
      $tiempo_postm=$this->calculo_tiempo_post($tiempo_post['0']->minutos,$tiempo_post['0']->segundos,$tiempo_post['0']->cuadros);


      if($capitulo){
        $m=$capitulo['0']->flashback_minutos+$capitulo['0']->transiciones_minutos+$capitulo['0']->stab_minutos+$capitulo['0']->recap_minutos+$capitulo['0']->cabezote_minutos+$capitulo['0']->credito_minutos+$capitulo['0']->cortinillas_minutos+$capitulo['0']->despedida_minutos+$capitulo['0']->presentacion_minutos+$capitulo['0']->foto_minutos+$capitulo['0']->imagenes_archivos_minutos;;
        $s=$capitulo['0']->flashback_segundos+$capitulo['0']->transiciones_segundos+$capitulo['0']->stab_segundos+$capitulo['0']->recap_segundos+$capitulo['0']->cabezote_segundos+$capitulo['0']->credito_segundos+$capitulo['0']->cortinillas_segundos+$capitulo['0']->despedida_segundos+$capitulo['0']->presentacion_segundos+$capitulo['0']->foto_segundos+$capitulo['0']->imagenes_archivos_segundos; 
        $c=$capitulo['0']->flashback_cuadros+$capitulo['0']->transiciones_cuadros+$capitulo['0']->stab_cuadros+$capitulo['0']->recap_cuadros+$capitulo['0']->cabezote_cuadros+$capitulo['0']->credito_cuadros+$capitulo['0']->cortinillas_cuadros+$capitulo['0']->despedida_cuadros+$capitulo['0']->presentacion_cuadros+$capitulo['0']->foto_cuadros+$capitulo['0']->imagenes_archivos_cuadros; 
      }else{
        $m='00';
        $s='00';
        $c='00';
      }


      $tiempo_extra=$this->calculo_tiempo_post($m,$s,$c);

      $total_capitulo=$this->calculo_tiempo_post_redondeo($tiempo_post['0']->minutos+$m,$tiempo_post['0']->segundos+$s,$tiempo_post['0']->cuadros+$c);

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
      $minutos=$this->calculo_tiempo($minutos,$segundos);
      $escenas_asignadas=$tiempo_real['0']->total_escenas;
      $escenas_postproduccidas=$tiempo_post['0']->total_escenas;
      $diferencia=$tiempo_real['0']->total_escenas-$tiempo_post['0']->total_escenas;

      $numero_capitulos=$capitulo['0']->numero;
      //$estatus=$capitulo['0']->estado;
      $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id,$capitulo['0']->numero); 
      if($fecha_entrega){
        $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));
      }else{
        $fecha_entrega='-'; 
      }
      if($capitulo['0']->fecha_entregada and $capitulo['0']->fecha_entregada!=null and $capitulo['0']->fecha_entregada!='' AND $capitulo['0']->fecha_entregada!='0000-00-00'){
        $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
      }else{
        $fecha_entregada='-'; 
      }
      $responsable=$capitulo['0']->responsable;

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
      $estatus=$campos_estado;
       
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

      $hora=date('H:i:s');
      $fecha=date('Y-m-d');
      $fecha=date("d-M-Y",strtotime($fecha));
      $xls = new ExcelWriter();
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),true,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper($fecha).' '.$hora,false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('CAPITULO: '.$numero_capitulos,false);
      $xls->NewCell('',false);
      $xls->NewCell('ESTATUS: '.strtoupper($estatus),false);
      $xls->NewCell('',false);
      $xls->NewCell('LIBRETOS: '.$lib,false);
      $xls->NewCell('',false);
      $xls->NewCell('RESPONSABLE: '.strtoupper($responsable),false);
      $xls->NewCell('',false);
      $xls->NewCell('ENTREGA: '.strtoupper($fecha_entrega),false);
      $xls->NewCell('',false);
      $xls->NewCell('ENTREGADO: '.strtoupper($fecha_entregada),false);
      $xls->NewCell('',false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('TIEMPO REAL:',false);
      $xls->NewCell($tiempo_realm,false);
      $xls->NewCell('',false);
      $xls->NewCell('TIEMPO POST:',false);
      $xls->NewCell($tiempo_postm,false);
      $xls->NewCell('',false);
      $xls->NewCell('TIEMPO EXTRA:',false);
      $xls->NewCell($tiempo_extra,false);
      $xls->NewCell('',false);
      $xls->NewCell('TOTAL:',false);
      $xls->NewCell($negativo.' '.$total_capitulo,false);
      $xls->NewCell('',false);
      $xls->NewCell('ESCENAS ASIGNAS:',false);
      $xls->NewCell($escenas_asignadas,false);
      $xls->NewCell('',false);
      $xls->NewCell('ESCENAS POSTPRODUCIDAS:',false);
      $xls->NewCell($escenas_postproduccidas,false);
      $xls->NewCell('',false);
      $xls->NewCell('',false);
      $xls->NewCell('DIFERENCIA:',false);
      $xls->NewCell($diferencia,false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $arr = array('LIB','ESC','TIEMPO ESTIMADO','TIEMPO REAL','TIEMPO POST');
      $xls->OpenRow();
      foreach($arr as $cod=>$val) $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->CloseRow();
      if($escenas){
         foreach ($escenas as $e) {   
          $xls->OpenRow();
          $xls->NewCell($e['numero']);
          $xls->NewCell($e['numero_escena']);
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
          $xls->NewCell($m.':'.$s);
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
          $xls->NewCell($m.':'.$s);
          if(!$e['tiempo_post_minutos'] or !$e['tiempo_post_segundos']){
           $xls->NewCell('-');
          }else{
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
            $xls->NewCell($e['tiempo_post_minutos'].':'.$e['tiempo_post_segundos'].'-'.$cuadros);
          }
          $xls->CloseRow();
        }  
      }    
      $xls->GetXLS(true,'Post produccion');
  }


 public function post_produccion_control($id){
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
          $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
          $productor_general='';
      }

      

      $fecha=date('Y-m-d');
      $fecha=date("d-M-Y",strtotime($fecha));
      $xls = new ExcelWriter();
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),true,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.strtoupper($fecha),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();
      
      $capitulos=$this->model_post_produccion->capitulos_has_escenas($id);
       if($capitulos){ 
         $numero=0; $cont=1; $cont_minutos_real=0;$cont_segundos_real=0;
      $cont_minutos_estimado=0;$cont_segundos_estimado=0;$cont_minutos_post=0;$cont_segundos_post=0;$cont_cuadros_post=0;
            foreach ($capitulos as $c){ 
                $cont_minutos_real=$cont_minutos_real+$c['duracion_real_minutos'];
                $cont_segundos_real=$cont_segundos_real+$c['duracion_real_segundos'];
                $cont_minutos_estimado=$cont_minutos_estimado+$c['duracion_estimada_minutos'];
                $cont_segundos_estimado=$cont_segundos_estimado+$c['duracion_estimada_segundos'];
                $cont_minutos_post=$cont_minutos_post+$c['tiempo_post_minutos'];
                $cont_segundos_post=$cont_segundos_post+$c['tiempo_post_segundos'];
                $cont_cuadros_post=$cont_cuadros_post+$c['total_post_cuadros'];
                     if($numero!=$c['numero_capitulo']){
                          $xls->OpenRow();
                          $xls->NewCell('');
                          $xls->CloseRow();
                          $xls->OpenRow();
                          $xls->NewCell('',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                          $xls->NewCell('Capítulo ',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                          $xls->NewCell($c['numero_capitulo'],false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                          $xls->NewCell('',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                          $xls->CloseRow();
                          $fecha_entrega=$this->model_post_produccion->fecha_entrega_libreto($id,$c['libreto']); 
                          if($fecha_entrega){
                            if($fecha_entrega['0']->fecha_aire!='0000-00-00'){
                              $fecha_entrega=date("d-M-Y",strtotime($fecha_entrega['0']->fecha_aire));  
                            }else{
                              $fecha_entrega='-';   
                            }
                          }else{
                            $fecha_entrega='-'; 
                          } 
                          $xls->OpenRow();
                          $xls->NewCell($fecha_entrega);
                          $xls->CloseRow();
                          if($c['fecha_entregada'] and $c['fecha_entregada']!=null and $c['fecha_entregada']!='' AND $c['fecha_entregada']!='0000-00-00'){
                            $fecha_entregada=date("d-M-Y",strtotime($c['fecha_entregada']));
                          }else{
                            $fecha_entregada='-'; 
                          }
                          $xls->OpenRow();
                          $xls->NewCell($fecha_entregada);
                          $xls->CloseRow(); 
                          $xls->OpenRow();
                          $xls->NewCell($fecha_entregada);
                          $xls->CloseRow($c['estado']); 
                          $xls->OpenRow();
                          $xls->NewCell('LIB/ESC',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));          
                          $xls->NewCell('TE',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));          
                          $xls->NewCell('TR',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));          
                          $xls->NewCell('TP',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));          
                          $xls->CloseRow($c['estado']);   
                      }
                      $xls->OpenRow();
                      $xls->NewCell($c['libreto'].'/'.$c['numero_escena']);
                      $xls->NewCell($c['duracion_estimada_minutos'].':'.$c['duracion_estimada_segundos']);
                      $xls->NewCell($c['duracion_real_minutos'].':'.$c['duracion_real_segundos']);
                      if($c['tiempo_post_minutos']=='0' and strlen($c['tiempo_post_minutos'])<2){
                       $m='0'.$c['tiempo_post_minutos'];  
                      }else{
                        $m=$c['tiempo_post_minutos'];
                      }
                      if($c['tiempo_post_segundos']==0 and strlen($c['tiempo_post_segundos'])<2){
                       $s='0'.$c['tiempo_post_segundos'];  
                      }else{
                        $s=$c['tiempo_post_segundos'];
                      }

                      if($c['tiempo_post_cuadros']==null){
                        $tiempo_post_cuadros='00';
                      }elseif($c['tiempo_post_cuadros']<10 and (strlen($c['tiempo_post_cuadros'])<2)){ 
                        $tiempo_post_cuadros='0'.$c['tiempo_post_cuadros'];
                      }else{
                        $tiempo_post_cuadros=$c['tiempo_post_cuadros'];
                      }
                      
                      $xls->NewCell($m.':'.$s.'-'.$tiempo_post_cuadros);
                      $xls->CloseRow(); 
                      
                      $numero=$c['numero_capitulo'];
                      if($cont<sizeof($capitulos)){ 
                            $capitulo=$capitulos[$cont]['numero_capitulo'];
                            if($capitulo!=$c['numero_capitulo']){
                              $xls->OpenRow();
                              $xls->NewCell('FLASHB');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['flashback_minutos']==null){
                                 $m='00';
                              }elseif($c['flashback_minutos']<10 and (strlen($c['flashback_minutos'])<2)){
                                 $m='0'.$c['flashback_minutos'];
                              }else{
                                $m=$c['flashback_minutos'];
                               }

                              if($c['flashback_segundos']==null){
                                 $s='00';
                              }elseif($c['flashback_segundos']<10 and (strlen($c['flashback_segundos'])<2)){
                                 $s='0'.$c['flashback_segundos'];
                              }else{
                                $s=$c['flashback_segundos'];
                               }

                             if($c['flashback_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['flashback_cuadros']<10 and (strlen($c['flashback_cuadros'])<2)){
                                 $cuadros='0'.$c['flashback_cuadros'];
                              }else{
                                $cuadros=$c['flashback_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('TRAN');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['transiciones_minutos']==null){
                                 $m='00';
                              }elseif($c['transiciones_minutos']<10 and (strlen($c['transiciones_minutos'])<2)){
                                 $m='0'.$c['transiciones_minutos'];
                              }else{
                                $m=$c['transiciones_minutos'];
                               }

                              if($c['transiciones_segundos']==null){
                                 $s='00';
                              }elseif($c['transiciones_segundos']<10 and (strlen($c['transiciones_segundos'])<2)){
                                 $s='0'.$c['transiciones_segundos'];
                              }else{
                                $s=$c['transiciones_segundos'];
                               }
                              
                              if($c['transiciones_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['transiciones_cuadros']<10 and (strlen($c['transiciones_cuadros'])<2)){
                                 $cuadros='0'.$c['transiciones_cuadros'];
                              }else{
                                $cuadros=$c['transiciones_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('FACHA.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['stab_minutos']==null){
                                 $m='00';
                              }elseif($c['stab_minutos']<10 and (strlen($c['stab_minutos'])<2)){
                                 $m='0'.$c['stab_minutos'];
                              }else{
                                $m=$c['stab_minutos'];
                               }

                              if($c['stab_segundos']==null){
                                 $s='00';
                              }elseif($c['stab_segundos']<10 and (strlen($c['stab_segundos'])<2)){
                                 $s='0'.$c['stab_segundos'];
                              }else{
                                $s=$c['stab_segundos'];
                               }
                             
                             if($c['stab_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
                                 $cuadros='0'.$c['stab_cuadros'];
                              }else{
                                $cuadros=$c['stab_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('RECAP.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['recap_minutos']==null){
                                 $m='00';
                              }elseif($c['recap_minutos']<10 and (strlen($c['recap_minutos'])<2)){
                                 $m='0'.$c['recap_minutos'];
                              }else{
                                $m=$c['recap_minutos'];
                               }

                              if($c['recap_segundos']==null){
                                 $s='00';
                              }elseif($c['recap_segundos']<10 and (strlen($c['recap_segundos'])<2)){
                                 $s='0'.$c['recap_segundos'];
                              }else{
                                $s=$c['recap_segundos'];
                               }
                              
                              if($c['recap_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['recap_cuadros']<10 and (strlen($c['recap_cuadros'])<2)){
                                 $cuadros='0'.$c['recap_cuadros'];
                              }else{
                                $cuadros=$c['recap_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('CRED.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['credito_minutos']==null){
                                 $m='00';
                              }elseif($c['credito_minutos']<10 and (strlen($c['credito_minutos'])<2)){
                                 $m='0'.$c['credito_minutos'];
                              }else{
                                $m=$c['credito_minutos'];
                               }

                              if($c['credito_segundos']==null){
                                 $s='00';
                              }elseif($c['credito_segundos']<10 and (strlen($c['credito_segundos'])<2)){
                                 $s='0'.$c['credito_segundos'];
                              }else{
                                $s=$c['credito_segundos'];
                               }
                             
                              if($c['credito_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['credito_cuadros']<10 and (strlen($c['credito_cuadros'])<2)){
                                 $cuadros='0'.$c['credito_cuadros'];
                              }else{
                                $cuadros=$c['credito_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('T.LABS');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['cabezote_minutos']==null){
                                 $m='00';
                              }elseif($c['cabezote_minutos']<10 and (strlen($c['cabezote_minutos'])<2)){
                                 $m='0'.$c['cabezote_minutos'];
                              }else{
                                $m=$c['cabezote_minutos'];
                               }

                              if($c['cabezote_segundos']==null){
                                 $s='00';
                              }elseif($c['cabezote_segundos']<10 and (strlen($c['cabezote_segundos'])<2)){
                                 $s='0'.$c['cabezote_segundos'];
                              }else{
                                $s=$c['cabezote_segundos'];
                               }
                              
                              if($c['cabezote_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['cabezote_cuadros']<10 and (strlen($c['cabezote_cuadros'])<2)){
                                 $cuadros='0'.$c['cabezote_cuadros'];
                              }else{
                                $cuadros=$c['cabezote_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('CORTINILLAS');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['cortinillas_minutos']==null){
                                 $m='00';
                              }elseif($c['cortinillas_minutos']<10 and (strlen($c['cortinillas_minutos'])<2)){
                                 $m='0'.$c['cortinillas_minutos'];
                              }else{
                                $m=$c['cortinillas_minutos'];
                               }

                              if($c['cortinillas_segundos']==null){
                                 $s='00';
                              }elseif($c['cortinillas_segundos']<10 and (strlen($c['cortinillas_segundos'])<2)){
                                 $s='0'.$c['cortinillas_segundos'];
                              }else{
                                $s=$c['cortinillas_segundos'];
                               }
                              
                              if($c['cortinillas_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['cortinillas_cuadros']<10 and (strlen($c['cortinillas_cuadros'])<2)){
                                 $cuadros='0'.$c['cortinillas_cuadros'];
                              }else{
                                $cuadros=$c['cortinillas_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('DESPEDIDA');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['despedida_minutos']==null){
                                 $m='00';
                              }elseif($c['despedida_minutos']<10 and (strlen($c['despedida_minutos'])<2)){
                                 $m='0'.$c['despedida_minutos'];
                              }else{
                                $m=$c['despedida_minutos'];
                               }

                              if($c['despedida_segundos']==null){
                                 $s='00';
                              }elseif($c['despedida_segundos']<10 and (strlen($c['despedida_segundos'])<2)){
                                 $s='0'.$c['despedida_segundos'];
                              }else{
                                $s=$c['despedida_segundos'];
                               }
                              
                              if($c['despedida_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['despedida_cuadros']<10 and (strlen($c['despedida_cuadros'])<2)){
                                 $cuadros='0'.$c['despedida_cuadros'];
                              }else{
                                $cuadros=$c['despedida_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();

                              $xls->OpenRow();
                              $xls->NewCell('PRESENTACION');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['presentacion_minutos']==null){
                                 $m='00';
                              }elseif($c['presentacion_minutos']<10 and (strlen($c['presentacion_minutos'])<2)){
                                 $m='0'.$c['presentacion_minutos'];
                              }else{
                                $m=$c['presentacion_minutos'];
                               }

                              if($c['presentacion_segundos']==null){
                                 $s='00';
                              }elseif($c['presentacion_segundos']<10 and (strlen($c['presentacion_segundos'])<2)){
                                 $s='0'.$c['presentacion_segundos'];
                              }else{
                                $s=$c['presentacion_segundos'];
                               }
                               
                              if($c['presentacion_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['presentacion_cuadros']<10 and (strlen($c['presentacion_cuadros'])<2)){
                                 $cuadros='0'.$c['presentacion_cuadros'];
                              }else{
                                $cuadros=$c['presentacion_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();

                              $xls->OpenRow();
                              $xls->NewCell('IMAGENES DE ARCHIVO');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['imagenes_archivos_minutos']==null){
                                 $m='00';
                              }elseif($c['imagenes_archivos_minutos']<10 and (strlen($c['imagenes_archivos_minutos'])<2)){
                                 $m='0'.$c['imagenes_archivos_minutos'];
                              }else{
                                $m=$c['imagenes_archivos_minutos'];
                               }

                              if($c['imagenes_archivos_segundos']==null){
                                 $s='00';
                              }elseif($c['imagenes_archivos_segundos']<10 and (strlen($c['imagenes_archivos_segundos'])<2)){
                                 $s='0'.$c['imagenes_archivos_segundos'];
                              }else{
                                $s=$c['imagenes_archivos_segundos'];
                               }
                               
                              if($c['imagenes_archivos_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['imagenes_archivos_cuadros']<10 and (strlen($c['imagenes_archivos_cuadros'])<2)){
                                 $cuadros='0'.$c['imagenes_archivos_cuadros'];
                              }else{
                                $cuadros=$c['imagenes_archivos_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();


                              $cont_minutos_post=$cont_minutos_post+$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['credito_minutos']+$c['cabezote_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos'];
                              $cont_segundos_post=$cont_segundos_post+$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['credito_segundos']+$c['cabezote_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos'];
                              $cont_cuadros_post=$cont_cuadros_post+$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['credito_cuadros']+$c['cabezote_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['imagenes_archivos_cuadros'];
                              $xls->OpenRow();
                              $xls->NewCell('TOTAL');
                              $xls->NewCell($this->calculo_tiempo($cont_minutos_estimado,$cont_segundos_estimado));
                               $cont_minutos_estimado=0;$cont_segundos_estimado=0;
                              $xls->NewCell($this->calculo_tiempo($cont_minutos_real,$cont_segundos_real));
                              $cont_minutos_real=0;$cont_segundos_real=0;
                              $xls->NewCell($this->calculo_tiempo_post_redondeo($cont_minutos_post,$cont_segundos_post,$cont_cuadros_post));
                              $cont_minutos_post=0;$cont_segundos_post=0;$cont_cuadros_post=0;
                              $xls->CloseRow();
                             }
                      }     
                       if($cont==sizeof($capitulos)){ 
                              $xls->OpenRow();
                              $xls->NewCell('FLASHB');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['flashback_minutos']==null){
                                 $m='00';
                              }elseif($c['flashback_minutos']<10 and (strlen($c['flashback_minutos'])<2)){
                                 $m='0'.$c['flashback_minutos'];
                              }else{
                                $m=$c['flashback_minutos'];
                               }

                              if($c['flashback_segundos']==null){
                                 $s='00';
                              }elseif($c['flashback_segundos']<10 and (strlen($c['flashback_segundos'])<2)){
                                 $s='0'.$c['flashback_segundos'];
                              }else{
                                $s=$c['flashback_segundos'];
                               }

                             if($c['flashback_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['flashback_cuadros']<10 and (strlen($c['flashback_cuadros'])<2)){
                                 $cuadros='0'.$c['flashback_cuadros'];
                              }else{
                                $cuadros=$c['flashback_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('TRAN');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['transiciones_minutos']==null){
                                 $m='00';
                              }elseif($c['transiciones_minutos']<10 and (strlen($c['transiciones_minutos'])<2)){
                                 $m='0'.$c['transiciones_minutos'];
                              }else{
                                $m=$c['transiciones_minutos'];
                               }

                              if($c['transiciones_segundos']==null){
                                 $s='00';
                              }elseif($c['transiciones_segundos']<10 and (strlen($c['transiciones_segundos'])<2)){
                                 $s='0'.$c['transiciones_segundos'];
                              }else{
                                $s=$c['transiciones_segundos'];
                               }
                              
                              if($c['transiciones_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['transiciones_cuadros']<10 and (strlen($c['transiciones_cuadros'])<2)){
                                 $cuadros='0'.$c['transiciones_cuadros'];
                              }else{
                                $cuadros=$c['transiciones_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('FACHA.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['stab_minutos']==null){
                                 $m='00';
                              }elseif($c['stab_minutos']<10 and (strlen($c['stab_minutos'])<2)){
                                 $m='0'.$c['stab_minutos'];
                              }else{
                                $m=$c['stab_minutos'];
                               }

                              if($c['stab_segundos']==null){
                                 $s='00';
                              }elseif($c['stab_segundos']<10 and (strlen($c['stab_segundos'])<2)){
                                 $s='0'.$c['stab_segundos'];
                              }else{
                                $s=$c['stab_segundos'];
                               }
                             
                             if($c['stab_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['stab_cuadros']<10 and (strlen($c['stab_cuadros'])<2)){
                                 $cuadros='0'.$c['stab_cuadros'];
                              }else{
                                $cuadros=$c['stab_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('RECAP.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['recap_minutos']==null){
                                 $m='00';
                              }elseif($c['recap_minutos']<10 and (strlen($c['recap_minutos'])<2)){
                                 $m='0'.$c['recap_minutos'];
                              }else{
                                $m=$c['recap_minutos'];
                               }

                              if($c['recap_segundos']==null){
                                 $s='00';
                              }elseif($c['recap_segundos']<10 and (strlen($c['recap_segundos'])<2)){
                                 $s='0'.$c['recap_segundos'];
                              }else{
                                $s=$c['recap_segundos'];
                               }
                              
                              if($c['recap_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['recap_cuadros']<10 and (strlen($c['recap_cuadros'])<2)){
                                 $cuadros='0'.$c['recap_cuadros'];
                              }else{
                                $cuadros=$c['recap_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('CRED.');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['credito_minutos']==null){
                                 $m='00';
                              }elseif($c['credito_minutos']<10 and (strlen($c['credito_minutos'])<2)){
                                 $m='0'.$c['credito_minutos'];
                              }else{
                                $m=$c['credito_minutos'];
                               }

                              if($c['credito_segundos']==null){
                                 $s='00';
                              }elseif($c['credito_segundos']<10 and (strlen($c['credito_segundos'])<2)){
                                 $s='0'.$c['credito_segundos'];
                              }else{
                                $s=$c['credito_segundos'];
                               }
                             
                              if($c['credito_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['credito_cuadros']<10 and (strlen($c['credito_cuadros'])<2)){
                                 $cuadros='0'.$c['credito_cuadros'];
                              }else{
                                $cuadros=$c['credito_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('T.LABS');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['cabezote_minutos']==null){
                                 $m='00';
                              }elseif($c['cabezote_minutos']<10 and (strlen($c['cabezote_minutos'])<2)){
                                 $m='0'.$c['cabezote_minutos'];
                              }else{
                                $m=$c['cabezote_minutos'];
                               }

                              if($c['cabezote_segundos']==null){
                                 $s='00';
                              }elseif($c['cabezote_segundos']<10 and (strlen($c['cabezote_segundos'])<2)){
                                 $s='0'.$c['cabezote_segundos'];
                              }else{
                                $s=$c['cabezote_segundos'];
                               }
                              
                              if($c['cabezote_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['cabezote_cuadros']<10 and (strlen($c['cabezote_cuadros'])<2)){
                                 $cuadros='0'.$c['cabezote_cuadros'];
                              }else{
                                $cuadros=$c['cabezote_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('CORTINILLAS');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['cortinillas_minutos']==null){
                                 $m='00';
                              }elseif($c['cortinillas_minutos']<10 and (strlen($c['cortinillas_minutos'])<2)){
                                 $m='0'.$c['cortinillas_minutos'];
                              }else{
                                $m=$c['cortinillas_minutos'];
                               }

                              if($c['cortinillas_segundos']==null){
                                 $s='00';
                              }elseif($c['cortinillas_segundos']<10 and (strlen($c['cortinillas_segundos'])<2)){
                                 $s='0'.$c['cortinillas_segundos'];
                              }else{
                                $s=$c['cortinillas_segundos'];
                               }
                              
                              if($c['cortinillas_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['cortinillas_cuadros']<10 and (strlen($c['cortinillas_cuadros'])<2)){
                                 $cuadros='0'.$c['cortinillas_cuadros'];
                              }else{
                                $cuadros=$c['cortinillas_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();
                              $xls->OpenRow();
                              $xls->NewCell('DESPEDIDA');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['despedida_minutos']==null){
                                 $m='00';
                              }elseif($c['despedida_minutos']<10 and (strlen($c['despedida_minutos'])<2)){
                                 $m='0'.$c['despedida_minutos'];
                              }else{
                                $m=$c['despedida_minutos'];
                               }

                              if($c['despedida_segundos']==null){
                                 $s='00';
                              }elseif($c['despedida_segundos']<10 and (strlen($c['despedida_segundos'])<2)){
                                 $s='0'.$c['despedida_segundos'];
                              }else{
                                $s=$c['despedida_segundos'];
                               }
                              
                              if($c['despedida_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['despedida_cuadros']<10 and (strlen($c['despedida_cuadros'])<2)){
                                 $cuadros='0'.$c['despedida_cuadros'];
                              }else{
                                $cuadros=$c['despedida_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();

                              $xls->OpenRow();
                              $xls->NewCell('PRESENTACION');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['presentacion_minutos']==null){
                                 $m='00';
                              }elseif($c['presentacion_minutos']<10 and (strlen($c['presentacion_minutos'])<2)){
                                 $m='0'.$c['presentacion_minutos'];
                              }else{
                                $m=$c['presentacion_minutos'];
                               }

                              if($c['presentacion_segundos']==null){
                                 $s='00';
                              }elseif($c['presentacion_segundos']<10 and (strlen($c['presentacion_segundos'])<2)){
                                 $s='0'.$c['presentacion_segundos'];
                              }else{
                                $s=$c['presentacion_segundos'];
                               }
                               
                              if($c['presentacion_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['presentacion_cuadros']<10 and (strlen($c['presentacion_cuadros'])<2)){
                                 $cuadros='0'.$c['presentacion_cuadros'];
                              }else{
                                $cuadros=$c['presentacion_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();

                              $xls->OpenRow();
                              $xls->NewCell('IMAGENES DE ARCHIVO');
                              $xls->NewCell('');
                              $xls->NewCell('');
                              if($c['imagenes_archivos_minutos']==null){
                                 $m='00';
                              }elseif($c['imagenes_archivos_minutos']<10 and (strlen($c['imagenes_archivos_minutos'])<2)){
                                 $m='0'.$c['imagenes_archivos_minutos'];
                              }else{
                                $m=$c['imagenes_archivos_minutos'];
                               }

                              if($c['imagenes_archivos_segundos']==null){
                                 $s='00';
                              }elseif($c['imagenes_archivos_segundos']<10 and (strlen($c['imagenes_archivos_segundos'])<2)){
                                 $s='0'.$c['imagenes_archivos_segundos'];
                              }else{
                                $s=$c['imagenes_archivos_segundos'];
                               }
                               
                              if($c['imagenes_archivos_cuadros']==null){
                                 $cuadros='00';
                             }elseif($c['imagenes_archivos_cuadros']<10 and (strlen($c['imagenes_archivos_cuadros'])<2)){
                                 $cuadros='0'.$c['imagenes_archivos_cuadros'];
                              }else{
                                $cuadros=$c['imagenes_archivos_cuadros'];
                              }
                              
                              $xls->NewCell($m.':'.$s.'-'.$cuadros);
                              $xls->CloseRow();

                              $cont_minutos_post=$cont_minutos_post+$c['flashback_minutos']+$c['transiciones_minutos']+$c['stab_minutos']+$c['recap_minutos']+$c['credito_minutos']+$c['cabezote_minutos']+$c['cortinillas_minutos']+$c['despedida_minutos']+$c['presentacion_minutos']+$c['imagenes_archivos_minutos'];
                              $cont_segundos_post=$cont_segundos_post+$c['flashback_segundos']+$c['transiciones_segundos']+$c['stab_segundos']+$c['recap_segundos']+$c['credito_segundos']+$c['cabezote_segundos']+$c['cortinillas_segundos']+$c['despedida_segundos']+$c['presentacion_segundos']+$c['imagenes_archivos_segundos'];
                              $cont_cuadros_post=$cont_cuadros_post+$c['flashback_cuadros']+$c['transiciones_cuadros']+$c['stab_cuadros']+$c['recap_cuadros']+$c['credito_cuadros']+$c['cabezote_cuadros']+$c['cortinillas_cuadros']+$c['despedida_cuadros']+$c['presentacion_cuadros']+$c['imagenes_archivos_cuadros'];
                              $xls->OpenRow();
                              $xls->NewCell('TOTAL');
                              $xls->NewCell($this->calculo_tiempo($cont_minutos_estimado,$cont_segundos_estimado));
                               $cont_minutos_estimado=0;$cont_segundos_estimado=0;
                              $xls->NewCell($this->calculo_tiempo($cont_minutos_real,$cont_segundos_real));
                              $cont_minutos_real=0;$cont_segundos_real=0;
                              $xls->NewCell($this->calculo_tiempo_post_redondeo($cont_minutos_post,$cont_segundos_post,$cont_cuadros_post));
                              $cont_minutos_post=0;$cont_segundos_post=0;$cont_cuadros_post=0;
                              $xls->CloseRow();
                            }
                      
                         $cont++; 
                   }  
             }        
        $xls->GetXLS(true,'Control de produccion');
  }

  public function excel_nomnia($id,$semana1='',$semana2=''){
    $produccion=$this->model_plan_produccion->produccion_id($id);

    if($semana1){
       $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad_filtro($id,$semana1,$semana2);
    }else{
       /*$fecha=$this->model_plan_produccion->unidades_id_produccion_2($id);
       $inicio_unidad=$fecha['0']['fecha_inicio'];
       $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad($id,$inicio_unidad);*/
       //$fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal2($id, $produccion[0]->inicio_grabacion);
       $fecha=$this->model_plan_produccion->unidades_id_produccion_2($id);
       $inicio_unidad=$fecha['0']['fecha_inicio'];
       $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad($id,$inicio_unidad);
    }
    
   
     if($produccion['0']->tipo_produccion==2){
      $personajes = $this->model_elementos->personajes_no_extra_anteriores($id);
    }else{
      $personajes = $this->model_elementos->personajes_no_extra($id);    
    }

    
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }

    $fecha=date('Y-m-d');
    $fecha=date("d-M-Y",strtotime($fecha));
    $xls = new excelWriter();
    $xls->OpenRow();
    $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),true,array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->NewCell('FECHA: '.strtoupper($fecha),false);
    $xls->NewCell('');
    $xls->NewCell('');
    $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->CloseRow();

    /*HEADER AZUL*/
    $xls->OpenRow();
      $xls->NewCell('PERSONAJE',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));

      //$num=1;
      $num=$fechas_reporte_semanal['0']->inicio_semana;$c=1;
      foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
        $xls->NewCell('SEMANA '.$num,false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
        if($semana1){
        $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
        }

        if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
              strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) 
              or($c==count($fechas_reporte_semanal))  ){
            if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ){
              $xls->NewCell('LIQUIDACIÓN',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
              $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
              $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
            }else{
              if(!$semana1){
                  $xls->NewCell('LIQUIDACIÓN A',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('LIQUIDACIÓN B',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('',false,array('align'=>'center','background'=>'0094C2','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
              }
            }
        } 
        ++$num;$c++;
      }
    $xls->CloseRow();

    /*HEADER GRIS*/
    $xls->OpenRow();
      $xls->NewCell('PERSONAJE',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('ACTOR',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('DOCUMENTO',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('MONTO',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $xls->NewCell('TIPO CONTRATO',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      $c=1;
      foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
        $xls->NewCell($fecha_reporte_semanal->fecha_muestra.' / '.$fecha_reporte_semanal->fecha_muestra_2,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
        if($semana1){
          $xls->NewCell('LIQUIDADO PERIODO',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));  
        }
        if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
              strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) 
              or($c==count($fechas_reporte_semanal)) ){
          if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ){
              if(!$semana1){
                  $xls->NewCell('LIB',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('LIQUIDACIÓN',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('CAPITULOS LIQUIDADOS',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
              }
          }else{
            if(!$semana1){
                  $xls->NewCell('LIB',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('LIQUIDACIÓN',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('CAPITULOS LIQUIDADOS',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('LIB',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('LIQUIDACIÓN',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
                  $xls->NewCell('CAPITULOS LIQUIDADOS',false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
            }
          }
        }
        $c++;
      }
    $xls->CloseRow();

    // CONTENIDO TABLA
   $semana_limite=$this->model_pdf->semanas_reporte_semanal_fecha_unidad_filtro_max_min($id,$semana1,$semana2);
   $liquidacion_total=array();


   ///////////////////////////
       $c=1;
        $num2=$fechas_reporte_semanal['0']->inicio_semana;
       foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
            $liquidacion_total[$num2]=0;
            $c++;$num2++;
         }   

          $c=1;
                                        $num2=$fechas_reporte_semanal['0']->inicio_semana;
                                       foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
                                            $liquidacion_periodo[$num2]=0;
                                            $c++;$num2++;
                                         }  

    $lista_residuo = "";
     //////////////////    
    foreach ($personajes as $personaje) {
       $acumulado_capitulos = 0; 
       $acumulado_residuo=0;
       $lista_capitulos = ""; 
        $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,$semana_limite['0']->min,$semana_limite['0']->max);

          if($capitulos_personaje['0']->capitulos OR !$semana1){
                      $acumulado_capitulos = 0;
                      $acumulado_dias = 0;
                      $acumulado_residuo=0;
                      $acumulado_residuo_dias=0;
                      $lista_capitulos = "";
                      $xls->OpenRow();
                        $xls->NewCell($personaje->nombre,false);
                        $xls->NewCell(strtoupper($personaje->actor_nombre.' '.$personaje->actor_apellido),false);
                        $xls->NewCell($personaje->documento_actor,false);
                        $xls->NewCell(number_format((double)$personaje->monto, 2, '.', ","),false);
                        $xls->NewCell($personaje->tipo_contrato,false);
                        $c=1;
                        $num2=$fechas_reporte_semanal['0']->inicio_semana;
                        foreach ($fechas_reporte_semanal as $fecha_reporte_semanal2) {

                          if ($fecha AND  strtotime($fecha_reporte_semanal2->fecha_muestra) <= strtotime($fecha) AND strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime($fecha)):
                                  $dias_trabajados = (strtotime($fecha)-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
                                  $dias_trabajados = abs($dias_trabajados); 
                                  $dias_trabajados = floor($dias_trabajados)+1;
                                  $fecha = date("d-m-Y",strtotime($fecha_reporte_semanal2->fecha_muestra_2)+86400);
                          endif;

                          $capitulos_pagar = 0;
                          $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,date("Y-m-d", strtotime($fecha_reporte_semanal2->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
                          if ($capitulos_personaje[0]->capitulos) {
                              $acumulado_capitulos += $capitulos_pagar = count(explode(',', $capitulos_personaje[0]->capitulos));
                              $lista_capitulos.=$capitulos_personaje[0]->capitulos.',';
                          }

                          $dias_pagar = 0;
                          $dias_pagar = $this->model_herramientas->buscar_dias_elemento($personaje->id,date("Y-m-d", strtotime($fecha_reporte_semanal2->fecha_muestra)),date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
                          if ($dias_pagar[0]->total) {
                              $acumulado_dias += $dias_pagar[0]->total;
                          }

                          $xls->NewCell($capitulos_pagar,false);
                          if($semana1){
                            $xls->NewCell(number_format((double)$personaje->monto*$capitulos_pagar, 2, '.', ","),false,array('type'=>'int'));
                            $liquidacion_periodo[$num2]=$liquidacion_periodo[$num2]+$personaje->monto*$capitulos_pagar;
                          }
                        
                          if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra))) >= strtotime($fecha_reporte_semanal2->fecha_muestra) AND 
                                strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2))) 
                                or($c==count($fechas_reporte_semanal))  ){

                              if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2)))) == "Sun" ){
                          
                                $acumulado_capitulos += $acumulado_residuo;
                                $acumulado_residuo = 0;
                                $lista_capitulos.=$lista_residuo; 
                                $lista_residuo = "";

                                $xls->NewCell($acumulado_capitulos,false,array('type'=>'int'));
                                                
                                
                                $liquidacion=0;
                                  if($personaje->id_tipo_contrato){
                                        switch ($personaje->id_tipo_contrato) {
                                          case 1:
                                            if($personaje->monto and $acumulado_capitulos!=0){
                                              $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
                                              $liquidacion = number_format($personaje->monto, 2, '.', ",");
                                            }
                                            break;
                                          case 2:
                                            $liquidacion =$personaje->monto*$acumulado_capitulos;
                                            $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                            $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                            break;
                                          case 3:
                                              $liquidacion=0;
                                              if($personaje->monto){
                                                if($acumulado_capitulos<11){
                                                  $liquidacion = $personaje->monto*$acumulado_capitulos;
                                                  $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                                  $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                                }else{
                                                  $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                                  $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
                                                }
                                              }
                                            break;
                                          case 4:
                                            if($personaje->monto){
                                              //$liquidacion = $personaje->dias_trabajados*$personaje->monto;
                                               $liquidacion = $acumulado_dias*$personaje->monto;
                                               $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                              $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                            }
                                            break;
                                          default:
                                            $liquidacion = "0";
                                            break;
                                        }        
                                      }else{
                                        $liquidacion = "0";
                                      }

                                
                                $xls->NewCell($liquidacion,false,array('type'=>'int'));
                                
                                $xls->NewCell($lista_capitulos,false);
                                $acumulado_capitulos=0;
                                $acumulado_dias=0;
                              }else{
                                $acumulado_capitulos += $acumulado_residuo;
                                $acumulado_residuo = 0;
                                $lista_residuo="";


                                $capitulos_residuo = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,date("Y-m", strtotime($fecha_reporte_semanal2->fecha_muestra)).'-16',date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
                                $dias_trabajados = (strtotime('16'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra)))-strtotime($fecha_reporte_semanal2->fecha_muestra_2))/86400;
                                $dias_trabajados = abs($dias_trabajados); 
                                $dias_trabajados = floor($dias_trabajados)+1;

                                if ($capitulos_residuo[0]->capitulos) {
                                    $acumulado_residuo = count($temp = explode(',', $capitulos_residuo[0]->capitulos));
                                    $lista_residuo = $capitulos_residuo[0]->capitulos;
                                    for ($i=0; $i < $acumulado_residuo; $i++) { 
                                        $lista_capitulos=str_replace($temp[$i].',', ' ', $lista_capitulos); 
                                    }
                                }

                                $dias_pagar = 0;
                                $dias_pagar = $this->model_herramientas->buscar_dias_elemento($personaje->id,date("Y-m", strtotime($fecha_reporte_semanal2->fecha_muestra)).'-16',date("Y-m-d",strtotime($fecha_reporte_semanal2->fecha_muestra_2))); 
                                if ($dias_pagar[0]->total) {
                                    $acumulado_residuo_dias += $dias_pagar[0]->total;
                                }
                                
                                if(!$semana1){
                                $xls->NewCell( $acu_te = $acumulado_capitulos-$acumulado_residuo,false,array('type'=>'int'));
                

                                $liquidacion=0;
                                $liquidacion_residuo=0;
                                if($personaje->id_tipo_contrato){
                                      switch ($personaje->id_tipo_contrato) {
                                        case 1:
                                          if($personaje->monto and $acu_te !=0){
                                            $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
                                            $liquidacion = number_format($personaje->monto, 2, '.', ",");
                                          }
                                          break;
                                        case 2:
                                          $liquidacion =$personaje->monto*$acu_te;
                                          $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
                                          $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                          $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                          break;
                                        case 3:
                                            $liquidacion=0;
                                            if($personaje->monto){
                                              if($acumulado_capitulos<11){
                                                $liquidacion = $personaje->monto*$acu_te ;
                                                $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
                                                $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                                $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                              }else{
                                                $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
                                                $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
                                              }
                                            }
                                          break;
                                        case 4:
                                          if($personaje->monto){
                                            $liquidacion = (($acumulado_dias)*$personaje->monto)+$liquidacion_residuo;
                                            $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                            $liquidacion_residuo = $liquidacion_residuo*$personaje->monto;
                                            $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                          }
                                          break;
                                        default:
                                          $liquidacion = "0";
                                          $liquidacion_residuo = 0;
                                          break;
                                      }        
                                    }else{
                                      $liquidacion = "0";
                                      $liquidacion_residuo = 0;
                                    }
                                    $acumulado_capitulos=0;
                                     $acumulado_dias=0;  
                                
                                  
                                
                                  $xls->NewCell($liquidacion,false,array('type'=>'int'));
                                  $liquidacion=0;
                                  $xls->NewCell($lista_capitulos ,false);
                                  $xls->NewCell($acumulado_residuo,false,array('type'=>'int'));
                                  $xls->NewCell(number_format((double)$liquidacion_residuo, 2, '.', ","),false,array('type'=>'int'));
                                  $xls->NewCell($lista_residuo,false);
                                }
                               /* $acumulado_capitulos=0;
                                $acu_te = 0;*/
                              }
                              $lista_capitulos = ""; 
                          }
                          $c++;$num2++;
                        }

                      $xls->CloseRow();
             }        
            
    }        
        if($personajes){

            $xls->OpenRow(); 
              $xls->NewCell('TOTAL',false);
               $xls->NewCell('',false);
               $xls->NewCell('',false);
               $xls->NewCell('',false);
               $xls->NewCell('',false);
                $c=1;
                $num2=$fechas_reporte_semanal['0']->inicio_semana;
               foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) {
                  $xls->NewCell('',false);
                  if($semana1){
                    $xls->NewCell(number_format(round($liquidacion_periodo[$num2],2), 2, '.', ","),false);
                  }
                    if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
                          strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) 
                          or($c==count($fechas_reporte_semanal)) ){
                          if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ){
                                if(!$semana1){
                                    $xls->NewCell(' ',false);
                                    $xls->NewCell($liquidacion_total[$num2],false);
                                    $xls->NewCell(' ',false);
                                }
                          }else{
                                if(!$semana1){
                                      $xls->NewCell(' ',false);
                                      $xls->NewCell(number_format($liquidacion_total[$num2], 2, '.', ","),false);
                                      $xls->NewCell(' ',false);
                                      $xls->NewCell(' ',false);
                                      $xls->NewCell('',false);
                                      $xls->NewCell(' ',false);
                                }
                          }
                    }
                    $c++;$num2++;
                 }   
            $xls->CloseRow();
        }
             
    $xls->GetXLS(true,'Nomina Personajes');

  }

 public function pasarMayusculas($cadena) { 
    $cadena = strtoupper($cadena); 
    $cadena = str_replace("á", "Á", $cadena); 
    $cadena = str_replace("é", "É", $cadena); 
    $cadena = str_replace("í", "Í", $cadena); 
    $cadena = str_replace("ó", "Ó", $cadena); 
    $cadena = str_replace("ú", "Ú", $cadena); 
    return ($cadena); 
  } 

  public function excel_caja_colores($id){

    $produccion=$this->model_plan_produccion->produccion_id($id);

    $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal($id, $produccion[0]->inicio_grabacion);
    $personajes = $this->model_elementos->personajes_no_extra($id);

    
    $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
    $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
    $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
    if($productor_general){
      $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
    }else{
      $productor_general='';
    }

    $fecha=date('Y-m-d');
    $fecha=date("d-M-Y",strtotime($fecha));
    $xls = new excelWriter();

    $xls->OpenRow();
    $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),true,array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('',array('background'=>'ffffff'));
    $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->NewCell('FECHA: '.strtoupper($fecha),false);
    $xls->NewCell('');
    $xls->NewCell('');
    $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->CloseRow();
    $xls->OpenRow();
    $xls->CloseRow();


    $xls->OpenRow();
    $capitulos=$this->model_dashboard->capitulos($id);
      if($capitulos){
        foreach ($capitulos as $c) {
          switch($c['estado']){
            case 3:
              $back="ff0068";
            break;
            case 5:
              $back="fee93e";
            break;
            case 2:
              $back="8815a6";
            break;
            case 4:
              $back="85c646";
            break;
            case 6:
              $back="000";
            break;
            case 1:
              $back="cccccc";
            break;
          }
          $xls->NewCell($c['numero'],false,array('align'=>'center','background'=>$back,'border'=>'000000','width'=>40));
          $xls->NewCell('',false,array('background'=>$back,'border'=>'000000','width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
      }
    $xls->CloseRow();
    
    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell(round((100*$c['locacion'])/$c['total']).' %',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();    

    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell(round((100*$c['estudio'])/$c['total']).' %',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();    

    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell(round((100*$c['total_producidos'])/$c['total']).' %',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();    

    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell($c['total'],false,array('width'=>40));
          $xls->NewCell($this->calculo_tiempo($c['total_duracion_estimada_minutos'],$c['total_duracion_estimada_segundos']),false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();

    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell($c['total_producidos'],false,array('width'=>40));
          $xls->NewCell($this->calculo_tiempo($c['total_duracion_real_minutos'],$c['total_duracion_real_segundos']),false,array('width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();
     
   
    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $dif_minutos=$c['total_duracion_estimada_minutos']-$c['total_duracion_real_minutos']; 
          $dif_segundos=$c['total_duracion_estimada_segundos']-$c['total_duracion_real_segundos'];
          $dif_segundos=$dif_segundos+($dif_minutos*60);
          $dif_cap=$c['total']-$c['total_producidos'];
          if($dif_cap<0){ 
            $color='EB060F';
          }else{
            $color=''; 
          }
          $xls->NewCell($dif_cap,false,array('color' =>$color,'width'=>40));
          if($dif_minutos<0){ 
            $color='EB060F';
          }else{
            $color='';
          }
          $xls->NewCell($this->tiempo_segundos($dif_segundos),false,array('color' =>$color,'width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();

    $xls->OpenRow();
    $xls->CloseRow();

    $xls->OpenRow();
    if($capitulos){
        foreach ($capitulos as $c) {
          $xls->NewCell('Esc',false,array('background'=>'827e7d','width'=>40));
          $xls->NewCell('Tiem',false,array('background'=>'827e7d','width'=>40));
          $xls->NewCell('',false,array('width'=>10));
        }
    }
    $xls->CloseRow();

  
   
    
    if($capitulos){
      $total_escenas=$this->model_dashboard->max_escenas_libreto($id);
      $total_escenas=$total_escenas['0']->total;
      $cont_escenas=0;
        while ($cont_escenas < $total_escenas) {
                   $xls->OpenRow(); 
                   foreach ($capitulos as $c) {
                     $escenas=$this->model_dashboard->escenas_id_capitulos_limit($c['id_capitulo'],$cont_escenas);
                      if($escenas) {
                        switch($escenas['0']->estado){
                          case 1:
                            $color='FEE93E';
                            break;
                            case 2:
                            $color='FEC63E';
                            break;
                            case 3:
                            $color='000000';
                            break;
                            case 4:
                            $color='09EEE9';
                            break;
                            case 5:
                            $color='A6FFF9';
                            break;
                            case 6:
                            $color='E3228D';
                            break;
                            case 7:
                            $color='EF7BBB';
                            break;
                            case 8:
                            $color='8CD316';
                            break;
                            case 9:
                            $color='C1F378';
                            break;
                            case 10:
                            $color='F7921E';
                            break;
                            case 11:
                            $color='FACA9A';
                            break;
                            case 12:
                            $class="background-color: #fee93e;";
                            break;
                            case 14:
                            $class="background-color: #fec63e; color: #333;";
                            break;
                            default:
                            $color='A79980';
                            break;
                        }
                        $xls->NewCell($escenas['0']->numero_escena,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                        if($escenas['0']->estado==1){ 
                          $xls->NewCell($this->calculo_tiempo2($escenas['0']->duracion_real_minutos,$escenas['0']->duracion_real_segundos),false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                        }else{  
                          $xls->NewCell($this->calculo_tiempo2($escenas['0']->duracion_estimada_minutos,$escenas['0']->duracion_estimada_segundos),false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                        } 
                        $xls->NewCell('',false,array('width'=>10));
                      }else{
                        $xls->NewCell('',false,array('width'=>40));
                        $xls->NewCell('',false,array('width'=>40));
                        $xls->NewCell('',false,array('width'=>10));
                      } 
                  }
                 $xls->CloseRow();
          $cont_escenas++;      
       }  
    }
   

    $xls->GetXLS(true,'Excel Caja colores');


  }

  public function prueba(){

    $xls = new excelWriter();
    $xls->OpenRow();
    $xls->NewCell('prueba',array('background'=>'ffffff'));
    $xls->CloseRow();
    $xls->GetXLS(true,'Excel Caja colores');

  }

  public function excel_escenas_seleccionadas($id_produccion,$escenas){
    

      $id=$id_produccion;
      $produccion=$this->model_plan_produccion->produccion_id($id);
      $ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
      $productor=$this->model_produccion->user_id($produccion['0']->id_productor);
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
      $productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
      if($productor_general){
        $productor_general=$productor_general['0']->nombre.' '.$productor_general['0']->apellido;
      }else{
        $productor_general='';
      }

      $personajes_produccion = $this->model_plan_general->categoria_produccion($id, 'Personaje');
      $escenas_noproducidas = $this->model_escenas_2->contar_escenas_noproducidas($id);
      
        $fecha= date("d-M-Y H:i:s");
      $xls = new ExcelWriter();
      $xls_int = array('type'=>'int','border'=>'111111');
      $xls_date = array('type'=>'date','border'=>'111111');
      $xls_normal = array('border'=>'111111');
      $xls->OpenRow();
      $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('',array('background'=>'ffffff'));
      $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('FECHA: '.$this->pasarMayusculas($fecha),false);
      $xls->NewCell('');
      $xls->NewCell('');
      $xls->NewCell('PRODUCTOR: '.$this->pasarMayusculas($nombre_productor),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->NewCell('PRODUCTOR GENERAL: '.$this->pasarMayusculas($productor_general),false);
      $xls->CloseRow();
      $xls->OpenRow();
      $xls->CloseRow();


      $cont=1;
      $cont2=1;
     /* $t2= sizeof($data);
      $t=$t2/20;
      $t=ceil($t);
      $k=0;
      $t=0;*/
      $arr = array('UNI','FEC', 'LIB','ESC','PÁG','DÍA','LOC','SET','INT/EXT','D/N','LOC/SET','TIE.EST','TIE.REAL','PERSONAJES PRINCIPAL','PERSONAJES SECUNDARIOS','DESCRIPCION','ELEMENTOS');
      $xls->OpenRow();
      foreach($arr as $cod=>$val){ 
        $xls->NewCell($val,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
      }

      $xls->CloseRow();
      $e=explode('-',$escenas);
      $personajes_produccion = $this->model_plan_general->categoria_produccion($id, 'Personaje');
    if($e){      
     foreach($e as $row){

      if($row){
           $escena_id=$this->model_plan_general->escenas_selecionadas($id_produccion,$personajes_produccion[0]->id,$row);
           
            $xls->OpenRow();
            $row_size = explode(',', $escena_id['0']->personajes_principales);
            if(count($row_size) < count(explode(',', $escena_id['0']->personajes_secundarios))){
                $row_size = explode(',', $escena_id['0']->personajes_secundarios);
            }

            if(count($row_size) < count(explode(',', $escena_id['0']->elementos))){
                $row_size = explode(',', $escena_id['0']->personajes_secundarios);;
            }
            if(count($row_size)<=0){
                $row_size=6;
            }else{
              $row_size =  count($row_size)*6; 
            }

           
            $temporal="";
              switch($escena_id['0']->estado){
                  case 1:
                  $color='FEE93E';
                  break;
                  case 2:
                  $color='FEC63E';
                  break;
                  case 3:
                  $color='000000';
                  break;
                  case 4:
                  $color='09EEE9';
                  break;
                  case 5:
                  $color='A6FFF9';
                  break;
                  case 6:
                  $color='E3228D';
                  break;
                  case 7:
                  $color='EF7BBB';
                  break;
                  case 8:
                  $color='8CDD16';
                  break;
                  case 9:
                  $color='C1F378';
                  break;
                  case 10:
                  $color='F7921E';
                  break;
                  case 11:
                  $color='FACA9A';
                  break;
                  default:
                  $color='A79980';
                  break;    
              }
          if($escena_id['0']->unidad_numero!=0){
              $numero=$escena_id['0']->unidad_numero;
          }else{
              $numero='-';
          }
          $xls->NewCell($numero,false,array('background'=>$color));
          if($escena_id['0']->fecha_inicio!='' AND $escena_id['0']->fecha_inicio!="0000-00-00" ){
             $fecha_inicio=date("d-M-Y",strtotime($escena_id['0']->fecha_inicio));
          }else{
             $fecha_inicio='-';
          }
          $xls->NewCell($fecha_inicio,false,array('background'=>$color));
          $xls->NewCell($escena_id['0']->capitulo,false,array('type'=>'int'));
          $xls->NewCell($escena_id['0']->numero_escena,false,array('type'=>'int'));
          $xls->NewCell(str_replace ( '-' , '─', $escena_id['0']->libreto ));
          $xls->NewCell($escena_id['0']->dias_continuidad);
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->locacion),true);
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->setnombre),true);
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->ubicacion));
          $tiempo=$escena_id['0']->tiempo;
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->tiempo));
          $tipo=$escena_id['0']->tipo;
          $xls->NewCell($this->pasarMayusculas($tipo));
          if(strlen($escena_id['0']->duracion_estimada_minutos)<2){
              $duracion_estimada='0'.$escena_id['0']->duracion_estimada_minutos.':';
          }else{
              $duracion_estimada=$escena_id['0']->duracion_estimada_minutos.':';
          }
          if(strlen($escena_id['0']->duracion_estimada_segundos)<2){
              $duracion_estimada=$duracion_estimada.'0'.$escena_id['0']->duracion_estimada_segundos;
          }else{
              $duracion_estimada=$duracion_estimada.$escena_id['0']->duracion_estimada_segundos;
          }
          $xls->NewCell($duracion_estimada);
          if(strlen($escena_id['0']->duracion_real_minutos)<2){
              $duracion_real='0'.$escena_id['0']->duracion_real_minutos.':';
          }else{
              $duracion_real=$escena_id['0']->duracion_real_minutos.':';
          }

          if(strlen($escena_id['0']->duracion_real_segundos)<2){
              $duracion_real=$duracion_real.'0'.$escena_id['0']->duracion_real_segundos;
          }else{
              $duracion_real=$duracion_real.$escena_id['0']->duracion_real_segundos;
          }
          $xls->NewCell($duracion_real);
         
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->personajes_principales),true);
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->personajes_secundarios),true);
          $arrayName = array("<", ">");
          $xls->NewCell(str_replace($arrayName, '', $this->pasarMayusculas($escena_id['0']->descripcion)),false, array('width'=>200,'height'=>100));
          $xls->NewCell($this->pasarMayusculas($escena_id['0']->elementos),true);
          $xls->CloseRow();
         } 
       }
      
       $xls->GetXLS(true,'Plan General escenas Seleccionadas');
     }else{
      echo "No hay resultado";
     }

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
      
      /*if($cuadros>15){
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

    public function excel_cuentas_cobro(){
      if($this->input->post('consultaExcel')==""){
        $data=$this->model_pagos->group_capitulos();
      }else{
        $query = $this->db->query($this->input->post('consultaExcel'));
        if ($query->num_rows>0) {
          $data = $query->result();
        }
      }
           $fecha= date("d-M-Y H:i:s");
            $xls = new ExcelWriter();
            $xls_int = array('type'=>'int','border'=>'111111');
            $xls_date = array('type'=>'date','border'=>'111111');
            $xls_normal = array('border'=>'111111');
            $arr = array('FECHA','ESTADO', 'PRODUCCION','TIPO PERSONAJE','PERSONAJE','DOCUMENTO','NOMBRE ACTOR','VALOR','CAPITULOS');
            
            $xls->OpenRow();
           foreach($arr as $cod=>$val){ 
              $xls->NewCell($val,false,array('align'=>'center','background'=>'222222','color'=>'FFFFFF','bold'=>true,'border'=>'000000'));
            }
            $xls->CloseRow();
            
            foreach($data as $row){
                  if($row){
                  switch($row->estado){
                  case "PAGADA":
                  $color='9afb5c';
                  break;
                  case "RECHAZADA":
                  $color='f40606';
                  break;
                  default:
                  $color='FFFFFF';
                  break;    
                }
                    $xls->OpenRow(); 
                    $xls->NewCell($row->fecha_generado,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->estado,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->nombre_produccion,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->rol,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->nombre_elemento,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->documento,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->nombre." ".$row->apellido,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->valor,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->NewCell($row->libretos,false,array('background' =>$color,'width'=>40,'border'=>'000000'));
                    $xls->CloseRow();
                  }
            }
           
            $xls->GetXLS(true,'Cuentas de cobro');
      }
   

}       