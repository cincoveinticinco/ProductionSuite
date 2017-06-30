<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {


  public function __construct (){         
      parent :: __construct (); 
      $this->load->model('model_capitulos');
      $this->load->model('model_plan_produccion');
      $this->load->model('model_pdf');
      $this->load->model('model_escenas_2');
      $this->load->model('model_plan_general');
      $this->load->model('model_plan_diario');
      $this->load->library('fpdf');
  }
              
  function _logeo_in(){
    $login_in = $this->session->userdata('login_pruduction_suite');
    if ($login_in !=true){
      redirect ($this->lang->lang().'/login/disconnecUser');
    }
  }

  public function pdf_capitulo($id){
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
      $header = array('NRO. LIB', 'NRO. ESC', 'DESGLOSADAS','CANCELADAS','POR PRODUCIR','PRODUCIDAS','TIEMPO EST.','TIEMPO. REAL.','MIN. CANCEL.','MIN. POR PRODUCIR','MIN. POST.','% PRODUCIDO','ESTADO');
      $pdf = new FPDF();
      $pdf->SetFont('Arial','',10);
      $pdf->AddPage('L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
      $pdf->Ln();
      $pdf->SetDrawColor(220,220,220);
      $pdf->SetFillColor(220,220,220);
      $pdf->Cell(100,0,'','');
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(85,5,"SITUACI".utf8_decode("Ó")."N DE LIBRETOS",1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".strtoupper($productor_general.'dasdsa'),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
      $pdf->Ln();
      $pdf->SetTextColor(0);
      $pdf->SetFont('Arial','B',10);
      $pdf->Ln();
      $pdf->SetTextColor(255);
      $pdf->SetDrawColor(83,83,87);
      $pdf->SetFont('','B');
      $fill = false;
      $pdf->SetFont('Arial','',7);
      $pdf->SetFillColor(83,83,87);
      $w = array(12, 13, 23,19,22,22,17,20,27,25,25,25,30);
      for($i=0;$i<count($header);$i++)

      $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
      $pdf->Ln();
      // Color and font restoration
      $pdf->SetFillColor(220,220,220);
      $pdf->SetTextColor(0);
      $pdf->SetFont('');
      // Data
     
      $cont=1;
      $cont2=1;
      $pag=1;
      $t2= sizeof($data);
      $t=$t2/20;
      $t=ceil($t);
      $cont_num_esc=0;
      $cont_esc_desg=0;
      $cont_prodc=0;
      $cont_pots_prodc=0;
      $cont_esc_can=0;
      $cont_sin_producir=0;
      $cont_prodc=0;
      $cont_est='00:00';
      $cont_min_real='00:00';
      $cont_min_cancelados='00:00';
      $cont_min_por_producir='00:00';
      $cont_min_post=0;
      $cont_seg_post=0;
      foreach($data as $row){
      $pdf->Cell($w[0],6,$row['numero'],'LR',0,'C',$fill);
      if($row['numero_escenas']== ""){
          $numero_escenas="-"  ;
      }else{
          $numero_escenas=$row['numero_escenas'];
          $cont_num_esc=$numero_escenas+$cont_num_esc;
      }
      $pdf->Cell($w[1],6,$numero_escenas,'LR',0,'C',$fill);
      if($row['escenas_escritas']== ""){
          $escenas_escritas="-";
      }else{
          $escenas_escritas= $row['escenas_escritas'];
          $cont_esc_desg=$cont_esc_desg+$escenas_escritas;
      }
      $pdf->Cell($w[2],6,$escenas_escritas,'LR',0,'C',$fill);
      $num_can=$this->model_pdf->numero_escenas_canceladas($row['id_capitulo']);
      if($num_can){
      $num_can=$num_can;
      $cont_esc_can=$num_can+$cont_esc_can;
      }else{
      $num_can='-';
      }
      $pdf->Cell($w[3],6,$num_can,'LR',0,'C',$fill);
      $sin_producir=$row['escenas_escritas']-$row['escenas_producidas'];
      if($sin_producir){
          $sin_producir=$sin_producir;
          $cont_sin_producir=$cont_sin_producir+$sin_producir;
      }else{
          $sin_producir='-';
      }
      $pdf->Cell($w[4],6,$sin_producir,'LR',0,'C',$fill);
      //$numero_escenas_producidas=$this->model_pdf->numero_escenas_producidas($row['id_capitulo']);
      $numero_escenas_producidas=$row['escenas_producidas'];
      if($numero_escenas_producidas){
       $numero_escenas_producidas=$numero_escenas_producidas;
       $cont_prodc=$cont_prodc+$numero_escenas_producidas;
      }else{
       $numero_escenas_producidas='-';
      } 
      $pdf->Cell($w[5],6,$numero_escenas_producidas,'LR',0,'C',$fill);
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
      $pdf->Cell($w[6],6,$duracion_estimada,'LR',0,'C',$fill);
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
      $pdf->Cell($w[7],6,$duracion_real,'LR',0,'C',$fill);
      $duracion_cancelada='00:00';
      $pdf->Cell($w[8],6,$duracion_cancelada,'LR',0,'C',$fill);
      
      $min_por_producir=$this->model_pdf->min_por_producir($row['id_capitulo']);
      if($min_por_producir){
          $minutos=$min_por_producir['0']->duracion_estimada_minutos;
          $segundos=$minutos*60;
          $segundos=$segundos+$min_por_producir['0']->duracion_estimada_segundos;
          $total=$segundos;

          //$m_total=segundos_tiempo($total);
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

      $pdf->Cell($w[9],6,$m_total2,'LR',0,'C',$fill);
      $tiempo=$this->model_pdf->tiempo_escenas_pots_producidas($row['id_capitulo']);
      if($tiempo){
       
      
       $tiempo=$this->calculo_tiempo_post($tiempo['0']->minutos,$tiempo['0']->segundos,$tiempo['0']->cuadros);
       $tiempo_cont_post=explode(':', $tiempo);
        $cont_min_post=$cont_min_post+$tiempo_cont_post['0'];
        $cont_seg_post=$cont_seg_post+$tiempo_cont_post['1'];
       //$cont_pots_prodc=$cont_pots_prodc+$numero_escenas_post_producidas;
      }else{
       $tiempo='00:00';
      } 
      $pdf->Cell($w[10],6,$tiempo.'','LR',0,'C',$fill);
      if($numero_escenas_producidas!='-'){
          $por_cen=($numero_escenas_producidas)*100/$numero_escenas;
          $por_cen=round($por_cen);
          $por_cen='% '.$por_cen;
      }else{
          $por_cen='% 0';
      }
      $pdf->Cell($w[11],6,$por_cen,'LR',0,'C',$fill);
      switch($row['descripcion']){
        case 'En Progreso':
        $pdf->SetFillColor(255,0,104);
          $class_cap="cap_progress";
        break;
        case 'Escrito':
          $class_cap="cap_writed";
        break;
        case 'Producido':
          $class_cap="cap_completed";
          $pdf->SetFillColor(254,233,62);
        break;
        case 'Entregado':
          $class_cap="cap_deliver";
          $pdf->SetFillColor(136,21,166);
        break;
        case 'Desglosado':
          $class_cap="cap_desglosed";
          $pdf->SetFillColor(133,198,70);
        break;
        case 'Cancelado':
          $class_cap="cap_canceled";
          $pdf->SetFillColor(0,0,0);
        break;
        case 'No iniciado':
          $class_cap="cap_progress_proyected";
          $pdf->SetFillColor(204,204,204);
        break;
      }
      if($class_cap!="cap_completed"){
        $pdf->SetTextColor(255,255,255);
      }else{
        $pdf->SetTextColor(0,0,0);
      }
      
      $pdf->Cell($w[12],6,strtoupper($row['descripcion']),'LR',0,'C',true);
      //$pdf->Cell($w[12],6,strtoupper($row['descripcion']),'LR',0,'C',true);
      $pdf->SetFillColor(220,220,220);
      $pdf->Ln();
      $fill = !$fill;
      if($cont2==24){
          $fill = true;
          $pdf->SetDrawColor(255,255,255);
          $pdf->SetTextColor(0);
          $pdf->Ln(3.9);
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(279,5,'Todos los derechos reservados Produciones RTI S.A.S',0,0,'C',true);
          $pdf->Cell(0,5,'Pag: '.$pag.'/'.$t,0,0,'R',true);
          $pdf->SetFillColor(220,220,220);
          $pag=$pag+1;
          $cont2=0;
          $pdf->AddPage('L');
          $pdf->SetTextColor(0);
          $pdf->SetTextColor(0);
          $pdf->SetFont('Arial','B',10);
          $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
          $pdf->SetDrawColor(220,220,220);
          $pdf->SetFillColor(220,220,220);
          $pdf->Cell(100,0,'','');
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(85,5,"SITUACI".utf8_decode("Ó")."N DE LIBRETOS",1,0,'L',true);
          $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
          $pdf->Ln();
          $pdf->Cell(100,0,'','');
          $pdf->Cell(85,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
          $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".strtoupper($productor_general),1,0,'L',true);
          $pdf->Ln();
          $pdf->Cell(100,0,'','');
          $pdf->Cell(85,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
          $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
          $pdf->Ln();
          $pdf->Ln();
          $pdf->Cell(100,0,'','');
          $pdf->Cell(0,5,'',1);
          $pdf->Cell(-277,0,'','');
          $pdf->SetTextColor(255);
          $pdf->SetDrawColor(83,83,87);
          $pdf->SetLineWidth(.3);
          //$pdf->SetFont('','B');
          $pdf->SetFont('Arial','',7);
          $pdf->SetFillColor(83,83,87);
          //$w = array(8, 15, 16,22,25,22,17,27,27,25,25,25,30);
          for($i=0;$i<count($header);$i++)
          $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
          $pdf->Ln();
          // Color and font restoration
          $pdf->SetFillColor(220,220,220);
          $pdf->SetTextColor(0);
          $pdf->SetFont('');
      // Data
      }
      if($t2==$cont){
        //$borders="LRB";
          $fill = true;
          $pdf->SetFillColor(0,184,255);
          $pdf->Cell($w[0],6,'TOTALES','LRB',0,'C',true);
          $pdf->Cell($w[1],6,$cont_num_esc,'LRB',0,'C',true);
          $pdf->Cell($w[2],6,$cont_esc_desg,'LRB',0,'C',true);
          $pdf->Cell($w[3],6,$cont_esc_can,'LRB',0,'C',true);
          $pdf->Cell($w[4],6,$cont_sin_producir,'LRB',0,'C',true);
          $pdf->Cell($w[5],6,$cont_prodc,'LRB',0,'C',true);
          $pdf->Cell($w[6],6,$cont_est,'LRB',0,'C',true);
          $pdf->Cell($w[7],6,$cont_min_real,'LRB',0,'C',true);
          $pdf->Cell($w[8],6,$cont_min_cancelados,'LRB',0,'C',true);
          $pdf->Cell($w[9],6,$cont_min_por_producir,'LRB',0,'C',true);
          $tiempo=$this->calculo_tiempo($cont_min_post,$cont_seg_post);
          $pdf->Cell($w[10],6,$tiempo,'LRB',0,'C',true);
          $pdf->Cell($w[11],6,'','LRB',0,'C',true);
          $pdf->Cell($w[12],6,'','LRB',0,'C',true);
          $pdf->SetTextColor(0);
          $pdf->SetDrawColor(255,255,255);
          $pdf->SetFillColor(220,220,220);
          /*$pdf->Ln();
          $pdf->Ln();*/
          $pdf->SetY(-28);
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(279,5,'Todos los derechos reservados Produciones RTI S.A.S',0,0,'C',true);
          $pdf->Cell(0,5,'Pag: '.$pag.'/'.$t,0,0,'R',true);
          $pdf->SetFillColor(220,220,220);
      }
      //$pdf->SetFillColor(255,255,255);
      $pdf->SetTextColor(0);
      $cont++;
      $cont2++;
      }
      // Closing line
      $pdf->Cell(array_sum($w),0,'','T');
      $pdf->Output();

  }


 public function pfd_escenas($id,$idcapitulo){
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
      $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);

      $header = array('NRO ESC','LOCACION', 'SET','CONT','TIEMPO-EST','TIEMPO-REAL','TIEMPO-POST','UNIDAD','FECHA','ESTADO');
      $pdf = new FPDF();
      $pdf->AddPage('L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
      $pdf->Ln();
      $pdf->SetDrawColor(220,220,220);
      $pdf->SetFillColor(220,220,220);
      $pdf->Cell(100,0,'','');
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(85,5,"SITUACI".utf8_decode("Ó")."N DE ESCENAS"." - LIBRETO ".$capitulo[0]['numero'],1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,"PRODUCCI".utf8_decode("Ó")."N: ".strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".strtoupper($productor_general),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
      $pdf->Ln();
      $pdf->SetTextColor(0);
      $pdf->SetFont('Arial','B',10);
      $pdf->Ln();
      $pdf->SetTextColor(255);
      $pdf->SetDrawColor(83,83,87);
      $pdf->SetFont('','B');
      $fill = false;
      $pdf->SetFont('Arial','',7);

      $pdf->SetFillColor(83,83,87);
      $w = array(12,55,55,10,20,20,20,18,20,47);
      for($i=0;$i<count($header);$i++)

      $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
      $pdf->Ln();
      // Color and font restoration
      $pdf->SetFillColor(220,220,220);
      $pdf->SetTextColor(0);
      $pdf->SetFont('');
      // Data
     
      $cont=1;
      $cont2=1;
      $pag=1;
      $t2= sizeof($data);
      $t=$t2/23;
      $t=ceil($t);
      $cont_tiempo_estimado='00:00';
      $cont_tiempo_real_minutos='';
      $cont_tiempo_real_segundos='';
      $cont_tiempo_post_minutos=0;
      $cont_tiempo_post_segundos=0;
      if($data){
          foreach($data as $row){
          $pdf->SetTextColor(0);
          $pdf->Cell($w[0],6,$row->numero_escena,'LR',0,'C',$fill);
          $pdf->Cell($w[1],6,strtoupper(utf8_decode($row->locacionnombre)),'LR',0,'L',$fill);
          $pdf->Cell($w[2],6,strtoupper(utf8_decode($row->setnombre)),'LR',0,'L',$fill);
          $pdf->Cell($w[3],6,$row->dias_continuidad,'LR',0,'C',$fill);
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
          $pdf->Cell($w[4],6,$duracion_estimada_minutos.':'.$duracion_estimada_segundos,'LR',0,'C',$fill);
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
          $cont_tiempo_real_minutos+=$duracion_real_minutos;
          $cont_tiempo_real_segundos+=$duracion_real_segundos;
          $pdf->Cell($w[5],6,$duracion_real,'LR',0,'C',$fill);
          if($row->tiempo_post_minutos==""){
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
          }

          $tiempo_real=$tiempo_post_minutos.':'.$tiempo_post_segundos;
          $cont_tiempo_post_minutos+=$tiempo_post_minutos;
          $cont_tiempo_post_segundos+=$tiempo_post_segundos;
          $pdf->Cell($w[6],6,$tiempo_real,'LR',0,'C',$fill);
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
          $pdf->Cell($w[7],6,$escena_unidad,'LR',0,'C',$fill);
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
          $pdf->Cell($w[8],6,$unidad_fecha,'LR',0,'C',$fill);

          switch($row->estado){
                  case 1:
                  $pdf->SetFillColor(254,233,62);
                  $estado='PRODUCIDA';
                  break;
                  case 2:
                  $pdf->SetFillColor(254,198,62);
                  $estado='RETOMA';
                  break;
                  case 3:
                  $pdf->SetFillColor(0,0,0);
                  $pdf->SetTextColor(255,255,255);
                  $estado='CANCELADA';
                  break;
                  case 4:
                  $pdf->SetFillColor(9,238,233);
                  $estado='PROGRAMADA';
                  break;
                  case 5:
                  $pdf->SetFillColor(166,255,249);
                  $estado='NO GRABADA';
                  break;
                  case 6:
                  $pdf->SetFillColor(227,34,141);
                  $estado='PROGRAMADA';
                  break;
                  case 7:
                  $pdf->SetFillColor(239,123,187);
                  $estado='NO GRABADA';
                  break;
                  case 8:
                  $pdf->SetFillColor(140,221,22);
                  $estado='PROGRAMADA';
                  break;
                  case 9:
                  $pdf->SetFillColor(193,243,120);
                  $estado='NO GRABADA';
                  break;
                  case 10:
                  $pdf->SetFillColor(247,146,30);
                  $estado='PROGRAMADA';
                  break;
                  case 11:
                  $pdf->SetFillColor(250,202,154);
                  $estado='NO GRABADA';
                  break;
                  case 12:
                  $pdf->SetFillColor(254,233,62);
                  $estado='PROGRAMADA';
                  break;
                  case 14:
                  $pdf->SetFillColor(254,198,62);
                  $estado='PROGRAMADA';
                  break;
                  default:
                  $pdf->SetFillColor(167,153,128);
                  $estado='NO ASIGNADA';
                  break;
              }
          
          $pdf->Cell($w[9],6,$estado,'LR',0,'C',true);
          $pdf->SetFillColor(220,220,220);
          $pdf->Ln();
          $fill = !$fill;
          if($cont2==23){
            $cont2=0;

                  $pdf->SetTextColor(0);
                  $pdf->SetDrawColor(255,255,255);
                  $pdf->Ln();
                  $pdf->SetFont('Arial','B',7);
                  $pdf->Cell(277,5,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
                  $pdf->Cell(0,5,'Pag: '.$pdf->PageNo().'/'.$t,0,0,'R',true);
                  $pdf->SetFillColor(220,220,220);
                  $pdf->AddPage('L');
                  $pdf->SetFont('Arial','B',10);
                  $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
                  $pdf->SetDrawColor(220,220,220);
                  $pdf->SetFillColor(220,220,220);
                  $pdf->Cell(100,0,'','');
                  $pdf->SetFont('Arial','B',8);
                  $pdf->Cell(85,5,"SITUACI".utf8_decode("Ó")."N DE ESCENAS"." - LIBRETO ".$capitulo[0]['numero'],1,0,'L',true);
                  $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
                  $pdf->Ln();
                  $pdf->SetFont('Arial','B',8);
                  $pdf->Cell(100,0,'','');
                  $pdf->Cell(85,5,"PRODUCCI".utf8_decode("Ó")."N: ".strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
                  $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".$productor_general,1,0,'L',true);
                  $pdf->Ln();
                  $pdf->Cell(100,0,'','');
                  $pdf->Cell(85,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
                  $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
                  $pdf->Ln();
                  $pdf->SetTextColor(0);
                  $pdf->SetFont('Arial','B',10);
                  $pdf->Ln();
                  $pdf->SetTextColor(255);
                  $pdf->SetDrawColor(83,83,87);
                  $pdf->SetFont('','B');
                  $fill = false;
                  $pdf->SetFont('Arial','',7);

                  $pdf->SetFillColor(83,83,87);

                  for($i=0;$i<count($header);$i++)

                  $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
                  $pdf->Ln();
                  // Color and font restoration
                  $pdf->SetFillColor(220,220,220);
                  $pdf->SetTextColor(0);
                  $pdf->SetFont('');
          // Data
          }
          $cont2++;
          if($t2==$cont){
              $pdf->SetTextColor(255);
              $pdf->SetFillColor(0,184,255);
              $pdf->Cell($w[0],6,'T. Esc '.sizeof($data),'LRB',0,'C',true);
              $pdf->Cell($w[1],6,'','LRB',0,'C',true);
              $pdf->Cell($w[2],6,'','LRB',0,'C',true);
              $pdf->Cell($w[3],6,'','LRB',0,'C',true);
              $pdf->Cell($w[4],6,$cont_tiempo_estimado,'LRB',0,'C',true);
              $total_real=$this->calculo_tiempo($cont_tiempo_real_minutos,$cont_tiempo_real_segundos);
              $pdf->Cell($w[5],6,$total_real,'LRB',0,'C',true);
              $total_post=$this->calculo_tiempo($cont_tiempo_post_minutos,$cont_tiempo_post_segundos);
              $pdf->Cell($w[6],6,$total_post,'LRB',0,'C',true);
              $pdf->Cell($w[7],6,'','LRB',0,'C',true);
              $pdf->Cell($w[8],6,'','LRB',0,'C',true);
              $pdf->Cell($w[9],6,'','LRB',0,'C',true);
              $pdf->SetTextColor(0);
              $pdf->SetDrawColor(255,255,255);
              $pdf->SetFillColor(220,220,220);
              /*$pdf->Ln();
              $pdf->Ln();*/
              $pdf->SetY(-28);
              $pdf->SetFont('Arial','B',7);
              $pdf->Cell(277,5,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
              $pdf->Cell(0,5,'Pag: '.$pdf->PageNo().'/'.$t,0,0,'R',true);
          }
        $cont++;  
       }   
      //$pdf->SetFillColor(255,255,255);
       
      $pdf->SetTextColor(0);
      
      
      }
      // Closing line
      $pdf->Cell(array_sum($w),0,'','T');

      $pdf->Output();
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


public function pfd_plan_produccion($id){
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

              /**************/
             /* $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
              for ($z=0; $z < count($unidad); $z++) { ;
                $acu_min_uni=0;$acu_seg_uni=0;
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
              } */
              $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
              for ($z=0; $z < count($unidad); $z++) { ;
               $acu_min_uni=0;$acu_seg_uni=0;
                    $tiempo=$this->model_escenas_2->escenas_producidas_produccion2($produccion['0']->id_produccion,$semanas_trabajo[$cont]['fecha_inicio_semana'],$semanas_trabajo[$cont]['fecha_fin_semana'],$unidad[$z]['id']);
                  if($tiempo){
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
              $dif_min_real=$this->calculo_tiempo($acu_min_uni_sem,$acu_seg_uni_sem);
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
              //echo $acumtotal.'<br>';
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
      $data=null;
      $header = array('SEM','#D', 'INICIO','FIN','PRO','AC.P','ENT','AC.E','DIF','DES','AC.D','SEM PROY','ACM PROY','SEM REAL','ACM REAL','SEM DIF','SEM DIF');
      $pdf = new FPDF();
      $pdf->AddPage('L');
      $pdf->SetFont('Arial','B',10);
      $pdf->SetY($pdf->GetY()+10);
      $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
      if($produccion['0']->imagen_produccion!=""){
        $pdf->Image(base_url($produccion['0']->imagen_produccion),262,10,25);
      }
      $y = $pdf->GetY();
      $pdf->SetY($y+10);
      $pdf->Ln();
      $pdf->SetDrawColor(220,220,220);
      $pdf->SetFillColor(220,220,220);
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,utf8_decode("PLAN PRODUCCIÓN"),1,0,'L',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".$productor_general,1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(85,05,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
      $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
      $pdf->Ln();
      $pdf->SetTextColor(0);
      $pdf->SetFont('Arial','B',10);
      $pdf->Ln();
      $pdf->SetTextColor(255);
      $pdf->SetFont('','B');
      $fill = false;
      $pdf->SetFont('Arial','B',8);
      if($color_libretos==0 or $color_semana=="green"){
        if($color1=='green' or $color_semana=='green'){
            $pdf->SetDrawColor(128,182,24);
            $pdf->SetFillColor(128,182,24);
        }else{
          $pdf->SetDrawColor(223,74,50);
          $pdf->SetFillColor(223,74,50);
        }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }
        
      $pdf->SetY($y+10);

      $pdf->Cell(40,5,"LIBRETOS ENTREGADOS",1,0,'L',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
        if($color2=='green'){
            $pdf->SetDrawColor(128,182,24);
            $pdf->SetFillColor(128,182,24);
        }else{
          $pdf->SetDrawColor(223,74,50);
          $pdf->SetFillColor(223,74,50);
        }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }  
      $pdf->Cell(40,5,"MINUTOS PRODUCCIDOS",1,0,'L',true);
      $pdf->Ln();
      $pdf->SetFont('Arial','B',10);
      if($color_libretos==0 or $color_semana=="green"){

        if($color1=='green' or $color_semana=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->Cell(40,5,sizeof($capitulos).'/'.$produccion['0']->numero_capitulo,1,0,'C',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
          if($color2=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,$minutos_producidos.'/'.$m_total_prod,1,0,'C',true);
      $pdf->Ln();
      $pdf->SetFont('Arial','B',8);
      if($color_libretos==0 or $color_semana=="green"){

        if($color1=='green' or $color_semana=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,'DIFERENCIA '.$dif_esc_real,1,0,'C',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
          if($color2=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,'DIFERENCIA '.$acumtotal2,1,0,'C',true);
      $pdf->ln();
      $pdf->ln();
      $pdf->SetFont('Arial','',7);
      $pdf->SetDrawColor(0,0,0);
      $pdf->SetFillColor(83,83,87);
       $pdf->SetTextColor(255,255,255);
      $w = array(14,14,22,22,13,13,13,13,13,13,13,14,17,22,22,21,19,19);
      for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
      $pdf->Ln();
      // Color and font restoration
      $pdf->SetDrawColor(0,0,0);
      $pdf->SetFillColor(220,220,220);
      $pdf->SetTextColor(0);
      $pdf->SetFont('');
      // Data
     
      $cont=1;
      $cont2=1;
      $pag=1;
      $t2= sizeof($data);
      $t=$t2/20;
      $t=ceil($t);
      $cont_tiempo_estimado='00:00';
      /******contadores aplicacon **********/
      $i=1;
      $m=1;
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
      /*************************************/
        $pag=1;
      $t2=$semanas[0]->total;
      $t=$t2/20;
      $t=ceil($t);
      while ($i<=$semanas[0]->total) { 
      $fill = true;
      $cont=$i-1; 
      $semana_aire =strtotime($produccion['0']->fecha_aire);
      $semana_inicio = strtotime($semanas_trabajo[$cont]['fecha_inicio_semana']);
      $semana_fin =   strtotime($semanas_trabajo[$cont]['fecha_fin_semana']);
      if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
        $pdf->SetFillColor(255,186,111);
      } else if($semana_aire>=$semana_inicio and $semana_aire<=$semana_fin){
         $pdf->SetFillColor(214,231,176);
      } else{
          if ($i %2 == 0) {
          $pdf->SetFillColor(220,220,220);
          } else {
           $pdf->SetFillColor(255,255,255);
          }
      }
      if($m%20==0 || $cont == $t2-1){
        $borders="LRB";
      }else{
        $borders="LR";
      }
      $pdf->SetFont('Arial','',7);
      $pdf->SetTextColor(0);
      $pdf->Cell($w[0],6,$i,$borders,0,'C',$fill);
      $pdf->Cell($w[1],6,$semanas_trabajo[$cont]['dias_trabajo'],$borders,0,'C',$fill);
      $pdf->Cell($w[2],6,date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_inicio_semana'])),$borders,0,'C',$fill);
      $pdf->Cell($w[3],6,date("d-M-Y",strtotime($semanas_trabajo[$cont]['fecha_fin_semana'])),$borders,0,'C',$fill);
      $pdf->Cell($w[4],6,$semanas_trabajo[$cont]['capitulos_programados'],$borders,0,'C',$fill);
      $cap_pro=$cap_pro+$semanas_trabajo[$cont]['capitulos_programados'];
      $pdf->Cell($w[5],6,$cap_pro,$borders,0,'C',$fill);
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
      $pdf->SetFillColor(248,242,190);
      $pdf->Cell($w[6],6,$contador,$borders,0,'C',$fill);
      if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
        $pdf->SetFillColor(255,186,111);
      } else if($semana_aire>=$semana_inicio and $semana_aire<=$semana_fin){
         $pdf->SetFillColor(214,231,176);
      } else{
          if ($i %2 == 0) {
          $pdf->SetFillColor(220,220,220);
          } else {
           $pdf->SetFillColor(255,255,255);
          }
      }
      $pdf->Cell($w[7],6,$acu_entre,$borders,0,'C',$fill);
      $dif_esc=$acu_entre-$cap_pro;
      if($dif_esc<0){
        $pdf->SetTextColor(255,0,0);
      }else{
        $pdf->SetTextColor(6, 134, 6);
      }
      $pdf->Cell($w[8],6,$dif_esc,$borders,0,'C',$fill);
      $pdf->SetTextColor(0);
      $pdf->Cell($w[9],6,$contador_desg,$borders,0,'C',$fill);
      $pdf->Cell($w[10],6,$acu_desg,$borders,0,'C',$fill);
      /***************/
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
      $pdf->Cell($w[11],6,$minutos_proyectados,$borders,0,'C',$fill);
      if($k!=1){
          $min_acu=0; 
      }else{
          $min_acu=$min_acu+$semanas_trabajo[$cont]['minutos_proyectados'];
      } 
      $pdf->Cell($w[12],6,$min_acu,$borders,0,'C',$fill);
      /********************************/
      $acu_min_uni_sem=0;$acu_seg_uni_sem=0;
      for ($z=0; $z < count($unidad); $z++) { ;
       $acu_min_uni=0;$acu_seg_uni=0;
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
      $pdf->SetFillColor(248,242,190);
      $pdf->Cell($w[13],6,$dif_min_real,$borders,0,'C',$fill);
      if($semana_actual>=$semana_inicio and $semana_actual<=$semana_fin) {
        $pdf->SetFillColor(255,186,111);
      } else if($semana_aire>=$semana_inicio and $semana_aire<=$semana_fin){
         $pdf->SetFillColor(214,231,176);
      } else{
        if ($i %2 == 0) {
        $pdf->SetFillColor(220,220,220);
        } else {
         $pdf->SetFillColor(255,255,255);
        }
      }
      $dif_min_acum=$this->calculo_tiempo($acu_min_real,$acu_seg_real);
      $pdf->Cell($w[14],6,$dif_min_acum,$borders,0,'C',$fill);
      /********************************/
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
        if($acumtotal<0){
          $pdf->SetTextColor(255,0,0);
        }else{
          $pdf->SetTextColor(6, 134, 6);
        }
        $acumtotal=$this->tiempo_segundos($acumtotal);
      $temp= explode(':',$m_total_dif);
      if($temp[0]<0){
        $pdf->SetTextColor(255,0,0);
      }else{
        $pdf->SetTextColor(6, 134, 6);
      }
      $pdf->Cell($w[15],6,$m_total_dif,$borders,0,'C',$fill);
      $temp= explode(':',$acumtotal);
      if($temp[0]<0){
        $pdf->SetTextColor(255,0,0);
      }else{
        $pdf->SetTextColor(6, 134, 6);
      }
      $pdf->Cell($w[16],6,$acumtotal,$borders,0,'C',$fill);
      /********************************/
      $pdf->SetFillColor(220,220,220);
      $pdf->Ln();
       $fill = !$fill;
      if($m==20){
              $m=0;
              $pdf->SetTextColor(0);
              $pdf->SetDrawColor(255,255,255);
              $pdf->Ln();
              $pdf->SetFont('Arial','B',7);
              $pdf->SetY(178);
              $pdf->Cell(277,5,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
              $pdf->Cell(0,5,'Pag: '.$pdf->PageNo().'/'.$t,0,0,'R',true);
              $pdf->SetFillColor(220,220,220);
              $pdf->AddPage('L');
              $pdf->SetFont('Arial','B',10);
              $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
              if($produccion['0']->imagen_produccion!=""){
                $pdf->Image(base_url($produccion['0']->imagen_produccion),240,10,25);
              }
              $y = $pdf->GetY();
              $pdf->SetY($y+15);
              $pdf->Ln();
              $pdf->SetDrawColor(220,220,220);
              $pdf->SetFillColor(220,220,220);
              $pdf->Cell(100,0,'','');
              $pdf->Cell(85,5,utf8_decode("PLAN  PRODUCCIÓN"),1,0,'L',true);
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(92,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
              $pdf->Ln();
              $pdf->Cell(100,0,'','');
              $pdf->Cell(85,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
              $pdf->Cell(92,5,"PRODUCTOR GENERAL: ".$productor_general,1,0,'L',true);
              $pdf->Ln();
              $pdf->Cell(100,0,'','');
              $pdf->Cell(85,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
              $pdf->Cell(92,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
              $pdf->Ln();
              $pdf->SetTextColor(0);
              $pdf->SetFont('Arial','B',10);
              $pdf->Ln();
              $pdf->SetTextColor(255);
              $pdf->SetFont('','B');
              $fill = false;
              $pdf->SetFont('Arial','B',8);
            if($color_libretos==0 or $color_semana=="green"){
            if($color1=='green' or $color_semana=='green'){
            $pdf->SetDrawColor(128,182,24);
            $pdf->SetFillColor(128,182,24);
            }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
            }
            }else{
            $pdf->SetDrawColor(248,242,190);
            $pdf->SetFillColor(248,242,190);
            $pdf->SetTextColor(0,0,0);
            }
            $pdf->SetY($y+20);
            $pdf->Cell(40,5,"LIBRETOS ENTREGADOS",1,0,'L',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
        if($color2=='green'){
            $pdf->SetDrawColor(128,182,24);
            $pdf->SetFillColor(128,182,24);
        }else{
          $pdf->SetDrawColor(223,74,50);
          $pdf->SetFillColor(223,74,50);
        }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }  
      $pdf->Cell(40,5,"MINUTOS PRODUCCIDOS",1,0,'L',true);
      $pdf->Ln();
      $pdf->SetFont('Arial','B',10);
      if($color_libretos==0 or $color_semana=="green"){

        if($color1=='green' or $color_semana=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->Cell(40,5,sizeof($capitulos).'/'.$produccion['0']->numero_capitulo,1,0,'C',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
          if($color2=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,$minutos_producidos.'/'.$m_total_prod,1,0,'C',true);
      $pdf->Ln();
      $pdf->SetFont('Arial','B',8);
      if($color_libretos==0 or $color_semana=="green"){

        if($color1=='green' or $color_semana=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,'DIFERENCIA '.$dif_esc_real,1,0,'C',true);
      $pdf->SetDrawColor(255,255,255);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(2,5,"",1,0,'L',true);
      if($color_minutos==0){
          if($color2=='green'){
              $pdf->SetDrawColor(128,182,24);
              $pdf->SetFillColor(128,182,24);
          }else{
            $pdf->SetDrawColor(223,74,50);
            $pdf->SetFillColor(223,74,50);
          }
      }else{
          $pdf->SetDrawColor(248,242,190);
          $pdf->SetFillColor(248,242,190);
          $pdf->SetTextColor(0,0,0);
      }    
      $pdf->cell(40,5,'DIFERENCIA '.$acumtotal2,1,0,'C',true);
              $pdf->ln();
              $pdf->ln();
              $pdf->SetFont('Arial','',6);
              $pdf->SetDrawColor(0,0,0);
              $pdf->SetFillColor(83,83,87);
              //$w = array(12,12,20,20,12,12,12,12,12,12,12,12,15,20,20,20,20,20);
              for($j=0;$j<count($header);$j++)
              $pdf->Cell($w[$j],7,$header[$j],1,-2,'C',true);
              $pdf->Ln();
              // Color and font restoration
              $pdf->SetDrawColor(0,0,0);
              $pdf->SetFillColor(220,220,220);
              $pdf->SetTextColor(0);
              $pdf->SetFont('');

      // Data
      }
      if($t2==$i){
            $pdf->SetY(178);
            $pdf->SetFillColor(0,125,173);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(255,255,255);
            $pdf->SetFillColor(220,220,220);
            //$pdf->Ln();
            $pdf->SetY(-28);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(277,5,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
            $pdf->Cell(0,5,'Pag: '.$pdf->PageNo().'/'.$t,0,0,'R',true);
      }
      $i++;
      $m++;
      }   
      // Closing line
      $pdf->Cell(array_sum($w),0,'','T');

      $pdf->Output();
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
    /*if($segundos_2>=0 and $segundos_2<10){
      $segundos_2='0'.$segundos_2;
    }*/
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


public function  SetCol($col)
{
    // Set position at a given column
  $pdf = new FPDF();
    $this->col = $col;
    $x = 10+$col*65;
    $pdf->SetLeftMargin($x);
    $pdf->SetX($x);
}


  public function MultiRow($left, $right) {
    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
    $pdf = new FPDF();
    $page_start = $this->getPage();
    $y_start = $this->GetY();

    // write the left cell
    $pdf->MultiCell(40, 0, $left, 1, 'R', 1, 2, '', '', true, 0);

    $page_end_1 = $this->getPage();
    $y_end_1 = $this->GetY();

    $pdf->setPage($page_start);

    // write the right cell
    $pdf->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX() ,$y_start, true, 0);

    $page_end_2 = $this->getPage();
    $y_end_2 = $this->GetY();

    // set the new row position by case
    if (max($page_end_1,$page_end_2) == $page_start) {
      $ynew = max($y_end_1, $y_end_2);
    } elseif ($page_end_1 == $page_end_2) {
      $ynew = max($y_end_1, $y_end_2);
    } elseif ($page_end_1 > $page_end_2) {
      $ynew = $y_end_1;
    } else {
      $ynew = $y_end_2;
    }

    $pdf->setPage(max($page_end_1,$page_end_2));
    $pdf->SetXY($this->GetX(),$ynew);
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

  public function pdf_excel($id='',$id_unidad='',$fecha_unidad=''){
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

      $fecha= date("d-M-Y H:i:s");
      $data=$capitulos;
      $header = array('#','LIB','ESC','CON','PAG','DESCRIPCION','GUION','DIA/NOCHE','INT/EXT','LOC/EST','LOCACION','SET','PERSONAJES','ELEMENTOS','TIEMPO EST','TIEMPO REAL','COM. ENSAYO','COM. PROD.','FIN PROD.','COMENTARIOS');
      $pdf = new FPDF();
      $pdf->SetFont('Arial','',10);
      $pdf->AddPage('L');
      $pdf->SetFont('Arial','B',10);
      $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
      $pdf->Ln();
      $pdf->SetDrawColor(220,220,220);
      $pdf->SetFillColor(220,220,220);
      $pdf->Cell(100,0,'','');
      $pdf->Cell(178,5,"SITUACI".utf8_decode("Ó")."N DE PLAN DIARIO",1,0,'L',true);
      $pdf->Ln();
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(100,0,'','');
      $pdf->Cell(70,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
      $pdf->Cell(108,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
      $pdf->Ln();
      $pdf->Cell(100,0,'','');
      $pdf->Cell(70,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
      $pdf->Cell(108,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
      $pdf->Ln();
      $pdf->SetTextColor(0);
      $pdf->SetFont('Arial','B',10);
      $pdf->Ln();
      $pdf->SetTextColor(255);
      $pdf->SetDrawColor(83,83,87);
      $pdf->SetFont('','B');
      $fill = false;
      $pdf->SetFont('Arial','',6);
      $pdf->SetFillColor(83,83,87);
      $w = array(5,10,10,10,10,35,8,12,12,10,15,15,15,15,15,15,15,15,15,20);
      for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
      $pdf->Ln();
      $pdf->SetFillColor(220,220,220);
      $pdf->SetTextColor(0);
      $pdf->SetFont('');
      $i=1;
      $cont=0;
      $fill = true;
      $t2= sizeof($escenas);
      $t=$t2/20;
      $t=ceil($t);
      foreach($escenas as $e){
        $pdf->cell($w[0],6,$i,'LR',0,'C',$fill);
        $pdf->cell($w[1],6,$e['numero'],'LR',0,'C',$fill);
        $pdf->cell($w[2],6,$e['numero_escena'],'LR',0,'C',$fill);
        $pdf->cell($w[3],6,$e['dias_continuidad'],'LR',0,'C',$fill);
        $pdf->cell($w[4],6,$e['libreto'],'LR',0,'C',$fill);
        $pdf->cell($w[5],6,'','LR',0,'C',$fill);
        $pdf->cell($w[6],6,'','LR',0,'C',$fill);
        $pdf->cell($w[7],6,'','LR',0,'C',$fill);
        $pdf->cell($w[8],6,utf8_decode($e['des_dia']),'LR',0,'C',$fill);
        $pdf->cell($w[9],6,utf8_decode($e['des_int']),'LR',0,'C',$fill);
        $pdf->cell($w[10],6,utf8_decode($e['tipo']),'LR',0,'C',$fill);
        $pdf->cell($w[11],6,'','LR',0,'C',$fill);
        $pdf->cell($w[12],6,'','LR',0,'C',$fill);
        $pdf->cell($w[13],6,'','LR',0,'C',$fill);
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
          $pdf->cell($w[14],6,$dureacion_rela_minutos.':'.$dureacion_rela_segundos,'LR',0,'C',$fill);
        }else{
          $pdf->cell($w[14],6,'-','LR',0,'C',$fill);
        } 
        if($e['comienzo_ens']){ 
          $pdf->cell($w[15],6,date("h:i",strtotime($e['comienzo_ens'])),'LR',0,'C',$fill);
        }else{
          $pdf->cell($w[15],6,'-','LR',0,'C',$fill);
        }
        if($e['comienzo_prod']){
          $pdf->cell($w[16],6,$e['comienzo_prod'],'LR',0,'C',$fill);
        }else{
          $pdf->cell($w[16],6,'-','LR',0,'C',$fill);
        }
        if($e['fin_produccion']){
          $pdf->cell($w[17],6,$e['fin_produccion'],'LR',0,'C',$fill);
        }else{
          $pdf->cell($w[17],6,'-','LR',0,'C',$fill);
        } 
        $pdf->cell($w[18],6,'','LR',0,'C',$fill);
        $pdf->cell($w[19],6,'','LR',0,'C',$fill);
        $pdf->SetFillColor(220,220,220);
        $pdf->Ln();
        $i++;
        $cont++;
        $fill = !$fill;
        $pdf->SetTextColor(0);
        if($i==24){
              $pdf->SetFont('Arial','',10);
              $pdf->AddPage('L');
              $pdf->SetFont('Arial','B',10);
              $pdf->Image(base_url('images/logoPdf.jpg'),10,10,60);
              $pdf->Ln();
              $pdf->SetDrawColor(220,220,220);
              $pdf->SetFillColor(220,220,220);
              $pdf->Cell(100,0,'','');
              $pdf->Cell(178,5,"SITUACI".utf8_decode("Ó")."N DE PLAN DIARIO",1,0,'L',true);
              $pdf->Ln();
              $pdf->SetFont('Arial','B',8);
              $pdf->Cell(100,0,'','');
              $pdf->Cell(70,5,utf8_decode("PRODUCCIÓN: ").strtoupper($produccion['0']->nombre_produccion),1,0,'L',true);
              $pdf->Cell(108,5,"PRODUCTOR EJECUTIVO: ".strtoupper($nombre_ejecutivo),1,0,'L',true);
              $pdf->Ln();
              $pdf->Cell(100,0,'','');
              $pdf->Cell(70,5,"FECHA DE REPORTE: ".$fecha,1,0,'L',true);
              $pdf->Cell(108,5,"PRODUCTOR: ".strtoupper($nombre_productor),1,0,'L',true);
              $pdf->Ln();
              $pdf->SetTextColor(0);
              $pdf->SetFont('Arial','B',10);
              $pdf->Ln();
              $pdf->SetTextColor(255);
              $pdf->SetDrawColor(83,83,87);
              $pdf->SetFont('','B');
              $fill = false;
              $pdf->SetFont('Arial','',6);
              $pdf->SetFillColor(83,83,87);
              $w = array(5,10,10,10,10,35,8,12,12,10,15,15,15,15,15,15,15,15,15,20);
              for($i=0;$i<count($header);$i++)

              $pdf->Cell($w[$i],7,$header[$i],1,-2,'C',true);
              $pdf->Ln();
      // Data
      }

        if($t2==$cont){
            $k=0;
            $pdf->SetFillColor(0,125,173);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(255,255,255);
            $pdf->SetFillColor(220,220,220);
            //$pdf->Ln();
            $pdf->SetY(-28);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(277,5,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
            $pdf->Cell(0,5,'Pag: '.$pdf->PageNo().'/'.$t,0,0,'R',true);
        }
        
      }
      $pdf->Cell(array_sum($w),0,'','T');
      $pdf->Output();
  }

   public function fechas_bloqueadas($idproduccion){
      $cadena_fechas = "";
      $contador_dias = 0;
      $j=86400;
      $semanas = $this->model_plan_produccion->semanas_trabajo($idproduccion);
      foreach ($semanas as $semana) {
        $contador_dias = strtotime($semana['fecha_inicio_semana']);
        while ($contador_dias<=strtotime($semana['fecha_fin_semana'])) {
          //echo date("D",$contador_dias);
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

    public static  function calculo_tiempo_post($minutos1,$segundos1,$cuadros1){
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
      
      if($cuadros>15){
        $segundos=$segundos+1;
      }

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