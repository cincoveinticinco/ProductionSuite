<?php 

class MYPDF extends TCPDF {

    var $elementsHeader;
    var $tipoHeader;
    var $tipoPDF;
    var $titulo;
    var $numeroUnidad;
    var $cantidad;
    var $cantidad_producidas;
    var $tiempo;
    var $tiempo_producido;
    var $lunes;
    var $martes;
    var $miercoles;
    var $jueves;
    var $viernes;
    var $sabado;
    var $domingo;
    var $fecha;
    var $cadenaHeader;

    //Page header
    public function Header() {

    if($this->tipoPDF==""){
      $this->SetDrawColor(220,220,220);
      $this->SetFillColor(220,220,220);
      if($this->tipoHeader!=3){
        if($this->titulo=='GENERAL'){
          $this->Image($this->elementsHeader['imagen'],10,1,60);
        }else{
          $this->Image($this->elementsHeader['imagen'],10,1,40);
        }  
      
      $this->SetFont('', 'B', 7);
      $this->Ln();
      $y=$this->GetY();
      $this->SetY($this->GetY()+15);
      /*$this->Ln(10);
      $this->Cell(70,5,"LUGAR LLAMADO: ".strtoupper($this->elementsHeader['lugar_llamado']),1,0,'L',true);*/
      $this->SetY($y);
      if($this->titulo=='GENERAL'){
              $this->Cell(100,0,'','');
              $this->Cell(85,5,"PLAN DIARIO ".$this->titulo,1,0,'L',true);
              $this->Cell(1,0,'','');
              $this->Cell(91,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
              $this->Ln();
              $this->Cell(100,0,'','');
              $this->Cell(85,5,strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
              $this->Cell(1,0,'','');
              $this->Cell(91,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
              $this->Ln();
              $this->Cell(100,0,'','');
              $this->Cell(85,5,"DIRECTOR: ".strtoupper($this->elementsHeader['director_unidad']),1,0,'L',true);
              $this->Cell(1,0,'','');
              $this->Cell(91,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
              $this->Ln();
      }else{
              $this->Cell(100,0,'','');
              $this->Cell(85,5,"PLAN DIARIO ".$this->titulo,1,0,'L',true);
              $this->Cell(1,0,'','');
              $this->Cell(85,5,strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
              $this->Ln();
      }
      }
      $this->Ln(6);
      //$this->Ln(6);

      $this->SetFont('', 'B', 8);

      $this->SetDrawColor(0);
      $this->Cell(18,5,"  UNIDAD:".$this->elementsHeader['unidad_numero'],1,0,'L',true);
      $this->Cell(1,5,'','');
      $this->Cell(40,5,'  '.strtoupper($this->elementsHeader['fecha_unidad']),1,0,'L',true);
      $this->SetFont('', 'B', 7);
      $this->SetDrawColor(220,220,220);
      $this->Cell(1,5,'','');
      $this->Cell(38,5,'  HORA DE LLAMADO: '.$this->elementsHeader['hora_llamado'],1,0,'L',true);
      $this->SetDrawColor(220,220,220);
      $this->SetFillColor(0,162,224);
      $this->SetTextColor(255,255,255);
      $this->Cell(1,5,'','');
      $this->Cell(40,5,'  ESCENAS PAUTADAS: '.$this->elementsHeader['total_escenas_pautadas'] ,1,0,'L',true);
      $this->Cell(1,5,'','');
      $this->Cell(45,5,'  TOTAL TIEMPO ESTIMADO: '.$this->elementsHeader['tiempo'],1,0,'L',true);
      $this->Cell(1,5,'','');

      $this->SetTextColor(0);
      switch ($this->elementsHeader['estado_plan']) {
        case 'No Iniciado':
          $this->SetFillColor(208,208,208);
          break;
        case 'Abierto Privado':
          $this->SetFillColor(193,243,120);
          break;
        case 'Abierto':
          $this->SetFillColor(140,221,22);
          break;
        case 'Abierto offline':
          $this->SetFillColor(74,75,57);
          break;
        case 'Cerrado':
          $this->SetFillColor(254,233,62);
          break;
        case 'Re abierto':
          $this->SetFillColor(247,146,30);
          break;
        default:
          $color_state="#d0d0d0";
          $this->SetFillColor(208,208,208);
          break;
      }
      $this->SetDrawColor(220,220,220);
      if($this->elementsHeader['estado_plan']==""){ 
        $estado_plan="No iniciado"; 
      }else{ 
        $estado_plan=$this->elementsHeader['estado_plan'];
      }
      $this->Cell(50,5,'ESTADO: '.strtoupper($estado_plan),1,0,'L',true);
      $this->SetFillColor(220,220,220);
      $this->Cell(60,5,"LUGAR LLAMADO: ".strtoupper($this->elementsHeader['lugar_llamado']),1,0,'L',true);
      $this->Ln(8);
if($this->tipoHeader==1){
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
    <td width="137" align="center">LOCACIÓN</td>
    <td width="138" align="center">SET</td>
    <td width="38" align="center">T.EST</td>
    <td width="32" align="center">T.REAL</td>
    <td width="40" align="center">C.ENS</td>
    <td width="40" align="center">C.GRA</td>
    <td width="40" align="center">F.GRA</td>
    <td width="120" align="center">OBSERVACIONES</td>
   </tr>
   <tbody>
   </table>
EOD;
}else {
$tbl4 = <<<EOD
<table border="0.5" style="whith:100%;" cellpadding="2">
   <tr style="background-color:#535357;color:#FFFFFF;">
    <td width="13" align="center" >#</td>
    <td width="20" align="center">LIB</td>
    <td width="20" align="center">ESC</td>
    <td width="20" align="center">PAG</td>
    <td width="66" align="center">LUZ/DIA</td>
    <td width="105" align="center">LOCACIÓNES</td>
    <td width="105" align="center">SET</td>
    <td width="28" align="center">T.EST</td>
    <td width="85" align="center">PERSONAJES</td>
    <td width="85" align="center">FIGUR. / EXTRAS</td>
    <td width="85" align="center">ELEMENTOS</td>
    <td width="178" align="center">DESCRIPCIÓN</td>
   </tr>
</table>
EOD;
}

if($this->tipoHeader==null){
$tbl4 = <<<EOD
EOD;
}

}else if($this->tipoPDF==1){
      $this->SetFont('','B',10);
      $this->Image($this->elementsHeader['imagen'],10,10,60);
      $y = $this->GetY();
      $this->SetDrawColor(220,220,220);
      $this->SetFillColor(220,220,220);
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"REPORTE PLAN GENERAL",1,0,'L',true);
      $this->SetFont('','B',8);
      $this->Cell(91,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
      $this->Ln();
      $this->SetFont('','B',8);
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
      $this->Cell(91,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
      $this->Ln();
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"FECHA DE REPORTE: ".strtoupper(date("d-M-Y H:i:s")),1,0,'L',true);
      $this->Cell(91,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
      $this->Ln(5,false);
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Cell(47,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->Cell(2,5,"",1,0,'L',true);
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Cell(47,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->SetTextColor(0);
      $this->SetFont('','B',7);
      $this->Ln(9,false);
      $this->SetTextColor(255);
      $this->SetDrawColor(83,83,87);
      $this->SetFont('','B');
      $this->SetFont('','',7);
      $this->SetDrawColor(0,0,0);
      $this->SetFillColor(83,83,87);

      $tbl4=<<<EOD
<table border="0.5" width="720px" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="17" align="center">UNI</td> 
                <td  width="48" align="center">FECHA</td> 
                <td  width="17" align="center">LIB</td> 
                <td  width="23" align="center">ESC</td> 
                <td  width="37" align="center">PÁG</td> 
                <td  width="24" align="center">CONT</td> 
                <td  width="57" align="center">LOCACIÓN</td> 
                <td  width="56" align="center">SET</td> 
                <td  width="31" align="center">INT/EXT</td> 
                <td  width="30" align="center">D/N</td> 
                <td  width="34" align="center" >LOC/EST</td> 
                <td  width="32" align="center">TIE EST</td> 
                <td  width="36" align="center">TIE REAL</td>
                <td  width="36" align="center">TIE POST</td>  
                <td  width="75" align="center">PER. PRINCIPALES</td> 
                <td  width="75" align="center">PER. SECUNDARIOS</td> 
                <td  width="82" align="center">DESCRIPCIÓN</td> 
                <td  width="80" align="center">ELEMENTOS</td> 
            </tr>
</table>
EOD;
}else if($this->tipoPDF==2){
      $this->SetFont('','B',10);
      $this->Image($this->elementsHeader['imagen'],10,10,60);
      $this->Ln();
      $this->SetDrawColor(220,220,220);
      $this->SetFillColor(220,220,220);
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"REPORTE PLAN SEMANAL",1,0,'L',true);
      $this->SetFont('','B',8);
      $this->Cell(87,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
      $this->Ln();
      $this->SetFont('','B',8);
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
      $this->Cell(87,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
      $this->Ln();
      $this->Cell(100,0,'','');
      $this->Cell(85,5,"SEMANA: ".$this->fecha,1,0,'L',true);
      $this->Cell(87,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
      $this->Ln();
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Ln();
      $this->Cell(50,5,"TOTAL ESCENAS EST: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->Cell(2,5,"",1,0,'L',true);
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Cell(50,5,"TOTAL MINUTOS EST: ".$this->elementsHeader['total_minutos'],1,0,'L',true);
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->Cell(2,5,"",1,0,'L',true);
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Cell(50,5,"TOTAL ESCENAS PROD: ".$this->elementsHeader['escenas_producidas'],1,0,'L',true);
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->Cell(2,5,"",1,0,'L',true);
      $this->SetDrawColor(131,185,61);
      $this->SetFillColor(131,185,61);
      $this->Cell(50,5,"TOTAL MINUTOS PROD: ".$this->elementsHeader['total_producidos'],1,0,'L',true);

      $this->Ln();
      $this->SetDrawColor(255,255,255);
      $this->SetFillColor(255,255,255);
      $this->SetTextColor(0);
      $this->SetFont('','B',7);
      $this->Ln();
      $this->SetFont('','B');
      $this->SetFont('','',7);
      $tbl4=<<<EOD
<table border="0.5" width="770px" style="background-color:#535357!important;color:#FFFFFF;" cellpadding="2">
<tr style="background-color:#535357;color:#FFFFFF;">
  <td colspan="2">UNIDAD $this->numeroUnidad</td>
  <td colspan="3">TOTAL ESCENAS EST: $this->cantidad</td>
  <td colspan="3">TOTAL MINUTOS EST: $this->tiempo</td>
  <td colspan="3">TOTAL ESCENAS PROD: $this->cantidad_producidas</td>
  <td colspan="3">TOTAL MINUTOS PROD: $this->tiempo_producido</td>
</tr>
<tr>
  <td align="center" width="80">LUNES $this->lunes</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">MARTES $this->martes</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">MIERCOLES $this->miercoles</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">JUEVES $this->jueves</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">VIERNES $this->viernes</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">SABADO $this->sabado</td>
  <td align="center" width="30">ESC</td>
  <td align="center" width="80">DOMINGO $this->domingo</td>
  <td align="center" width="30">ESC</td>
</tr>
</table>
EOD;

}else if($this->tipoPDF==3){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(85,5,"REPORTE NOMINA DE PERSONAJES",1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(91,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(85,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(91,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);  
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(85,5,strtoupper($this->elementsHeader['fecha']),1,0,'L',true);
  $this->Cell(91,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',6);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
      $tbl4=<<<EOD
<table border="0.5" width="50%" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="60" align="center">PERSONAJE</td> 
                <td  width="50" align="center">ROL</td> 
                <td  width="47" align="center">NOMBRE</td>
                <td  width="52" align="center">APELLIDO</td>
                <td  width="40" align="center">TIPO DOC.</td> 
                <td  width="50" align="center">DOC.</td> 
                <td  width="50" align="center">CONTRATO</td>
                <td  width="50" align="center">MONTO</td>
                <td  width="42" align="center">TIPO MON.</td>   
                <td  width="30" align="center">TA CAM</td>
                <td  width="48" align="center">INICIO</td>
                <td  width="48" align="center">FINAL</td> 
                <td  align="center" width="53">LIQUID.</td> 
                <td  align="center" width="53">DESCUENTO</td> 
                <td  align="center" width="53">OBSERVACIONES</td>  
                <td  align="center" width="20">LIBS</td>
                <td  align="center" width="60">LIBRETOS</td> 
            </tr>
</table>
EOD;

}else if($this->tipoPDF==4){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"REPORTE  SEMANAL ".$this->titulo,1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(88,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"SEMANA: ".$this->elementsHeader['fecha'],1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
  $this->Ln();
if($this->tipoHeader==""){
  $tbl4=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="80" align="center">DÍA</td> 
                <td  width="80" align="center">FECHA</td> 
                <td  width="20" align="center" >UNI</td> 
                <td  width="65" align="center">ROL</td> 
                <td  width="140" align="center">PERSONAJE</td> 
                <td  width="200" align="center">LIB / ESC</td> 
                <td  width="190" align="center">OBSERVACIONES</td> 
            </tr>
</table>
EOD;
}else{
    $tbl4=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="80" align="center">DÍA</td> 
                <td  width="80" align="center">FECHA</td> 
                <td  width="20" align="center" >UNI</td> 
                <td  width="170" align="center">ELEMENTO</td> 
                <td  width="230" align="center">LIB / ESC</td> 
                <td  width="200" align="center">OBSERVACIONES</td> 
            </tr>
</table>
EOD;
}
}else if($this->tipoPDF==5){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(85,5,"REPORTE".$this->titulo,1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(91,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(85,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(91,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);  
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(85,5,strtoupper($this->elementsHeader['limite']),1,0,'L',true);
  $this->Cell(91,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $x = $this->GetX();
  $this->SetX($x+2);
  $this->Cell(70,5,strtoupper($this->elementsHeader['total_elementos']),1,0,'L',true);
  $this->Cell(70,5,"LIBRETOS REPORTADOS: ".strtoupper($this->elementsHeader['total_libretos']),1,0,'L',true);
  $this->Cell(70,5,"ESCENAS: ".strtoupper($this->elementsHeader['total_escenas']),1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
//   if($this->tipoHeader==1){
//       $tbl4=<<<EOD
// <table border="0.5" width="100%" cellpadding="2">
//   <tr style="background-color:#535357;color:#FFFFFF;">
//       <td  width="205" align="center">NOMBRE</td> 
//       <td  align="center">ROL</td> 
//       <td  width="220" align="center">DESCRIPCIÓN</td> 
//       <td  width="60" align="center">USOS</td> 
//       <td  width="60" align="center">% DE USO</td> 
//       <td  align="center" width="60">ESC PROD</td> 
//       <td  align="center" width="60">ESC POR PROD</td> 
//   </tr>
// </table>
// EOD;
//   }else if($this->tipoHeader==2){
//     $tbl4=<<<EOD
// <table border="0.5" width="100%" cellpadding="2">
//   <tr style="background-color:#535357;color:#FFFFFF;">
//       <td  width="218" align="center">NOMBRE</td> 
//       <td  width="240" align="center">DESCRIPCIÓN</td> 
//       <td  width="80" align="center">USOS</td> 
//       <td  width="80" align="center">% DE USO</td> 
//       <td  align="center" width="80">ESC PROD</td> 
//       <td  align="center" width="80">ESC POR PROD</td> 
//   </tr>
// </table>
// EOD;

//   }else if($this->tipoHeader==3){
//     $tbl4=<<<EOD
// <table border="0.5" width="100%" cellpadding="2">
//   <tr style="background-color:#535357;color:#FFFFFF;">
//       <td  width="295" align="center">NOMBRE</td> 
//       <td  width="120" align="center">USOS</td> 
//       <td  width="120" align="center">% DE USO</td> 
//       <td  align="center" width="120">ESC PROD</td> 
//       <td  align="center" width="120">ESC POR PROD</td> 
//   </tr>
// </table>
// EOD;
//   }else{
    
    $tbl4="";
  //}
}else if($this->tipoPDF==6){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"REPORTE  POST PRODUCCION ",1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(88,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"FECHA REPORTE: ".$this->elementsHeader['fecha'],1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
  $min_produccidos=$this->elementsHeader['min_produccidos'];
  $mins_post_produccidos=$this->elementsHeader['mins_post_produccidos'];
  $minutos=$this->elementsHeader['minutos'];
  $negativo=$this->elementsHeader['negativo'];
  $total_escenas=$this->elementsHeader['total_escenas'];
  $escenas_post_produccidas=$this->elementsHeader['escenas_post_produccidas'];
  $dif=$this->elementsHeader['dif'];
  $tbl4=<<<EOD
<table width="100%" cellpadding="2">
            <tr style="background-color:#DD7644;color:#fff;">
                <td  width="130" align="center"><strong>MIN PRODUCCIDOS: $min_produccidos</strong></td> 
                <td  width="130" align="center"><strong>MIN. POTS-PRODUCIDOS: $mins_post_produccidos</strong></td> 
                <td  width="130" align="center" ><strong>DIFERENCIA MINUTO: $negativo $minutos</strong> </td> 
                <td width="5" ></td>
                <td  width="130" align="center"><strong>ESC. PRODUCIDAS: $total_escenas</strong></td> 
                <td  width="130" align="center"><strong>ESC. POTS-PRODUCIDOS: $escenas_post_produccidas</strong></td> 
                <td  width="145" align="center"><strong>DIFERENCIA ESC: $dif</strong></td> 
            </tr>
</table>
EOD;
  $tbl4.=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="30" align="center">CAP</td> 
                <td  width="158" align="center">ESTATUS</td> 
                <td  width="25" align="center">ESC</td> 
                <td  width="25" align="center" >LIB</td> 
                <td  width="52" align="center">T.ESTIMADO</td> 
                <td  width="40" align="center">T.REAL</td> 
                <td  width="40" align="center">T.POST</td> 
                <td  width="40" align="center">T.EXTRA</td> 
                <td  width="40" align="center">TOTAL</td> 
                <td  width="50" align="center">DIFERENCIA</td> 
                <td  width="100" align="center">RESPONSABLE</td> 
                <td  width="100" align="center">ENTREGA</td> 
                <td  width="100" align="center">ENTREGADA</td> 
            </tr>
</table>
EOD;
}else if($this->tipoPDF==7){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"REPORTE  POST PRODUCCION ",1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(88,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"FECHA DE REPORTE: ".$this->elementsHeader['fecha'],1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
  $tiempo_real=$this->elementsHeader['tiempo_real'];
  $tiempo_post=$this->elementsHeader['tiempo_post'];
  $tiempo_extra=$this->elementsHeader['tiempo_extra'];
  $minutos=$this->elementsHeader['minutos'];
  $negativo=$this->elementsHeader['negativo'];
  $escenas_asignadas=$this->elementsHeader['escenas_asignadas'];
  $escenas_postproduccidas=$this->elementsHeader['escenas_postproduccidas'];
  $diferencia=$this->elementsHeader['diferencia'];
  $numero_capitulos=$this->elementsHeader['numero_capitulos'];
  $estatus=$this->elementsHeader['estatus'];
  $fecha_entrega=$this->elementsHeader['fecha_entrega'];
  $fecha_entregada=$this->elementsHeader['fecha_entregada'];
  $responsable=strtoupper($this->elementsHeader['responsable']);
  $lib=$this->elementsHeader['lib'];
  $class_cap=$this->elementsHeader['class_cap'];
  $color=$this->elementsHeader['color'];
  $tbl4=<<<EOD
<table width="100%" cellpadding="2">
            <tr style="background-color: $class_cap;color: $color;">
                <td  width="130" align="center">CAPITULO: $numero_capitulos</td> 
                <td  width="130" align="center">ESTATUS: $estatus</td> 
                <td  width="130" align="center" >LIBRETOS : $lib</td> 
                <td width="130" >RESPONSABLE: $responsable</td>
                <td  width="130" align="center">ENTREGA: $fecha_entrega</td> 
                <td  width="130" align="center">ENTREGADO: $fecha_entregada</td> 
            </tr>
</table>
EOD;
  $tbl4.=<<<EOD
<table width="90%" cellpadding="2">
            <tr style="background-color:#DD7644;color:#fff;">
                <td  width="100" align="center"><strong>TIEMPO REAL: $tiempo_real</strong></td> 
                <td  width="100" align="center"><strong>TIEMPO POST: $tiempo_post</strong></td> 
                <td  width="100" align="center" ><strong>TIEMPO EXTRA: $tiempo_extra</strong></td> 
                <td  width="115" align="center" ><strong>TOTAL: $negativo $minutos</strong></td> 
                <td  width="120" align="center"><strong>ESCENAS ASIGNAS: $escenas_asignadas</strong></td> 
                <td  width="120" align="center"><strong>ESCENAS POSTPRODUCIDAS: $escenas_postproduccidas</strong></td> 
                <td  width="125" align="center"><strong>DIFERENCIA: $diferencia</strong></td> 
            </tr>
</table>
EOD;
  $tbl4.=<<<EOD
<table border="0.5" width="100%" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td  width="50" align="center">LIB</td> 
                <td  width="100" align="center">ESC</td> 
                <td  width="210" align="center" >TIEMPO ESTIMADO</td> 
                <td  width="210" align="center">TIEMPO REAL</td> 
                <td  width="210" align="center">TIEMPO POS</td>
            </tr>
</table>
EOD;


}else if($this->tipoPDF==8){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"REPORTE  POST PRODUCCION ",1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(88,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"SEMANA: ".$this->elementsHeader['fecha'],1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
$tbl4="";
//   $tbl4=<<<EOD
//   <table border="1" width="100%" cellpadding="2">
//     <tr>
//       <td colspan="5">Tipo de elemento:</td>
//       <td colspan="5">Nombre de elemento:</td>
//     </tr>
//     <tr>
//       <td>UNI</td>
//       <td>#</td>
//       <td>LIB.</td>
//       <td>ESC.</td>
//       <td>TIE. EST.</td>
//       <td>LOCACIÓN</td>'
//       <td>SET</td>
//       <td>Personajes principales</td>
//       <td>Personajes secundarios</td>
//       <td>Elementos</td>
//     </tr>
//   </table>
// EOD;
}else if($this->tipoPDF==9){
  $this->SetFont('','B',10);
  $this->Image($this->elementsHeader['imagen'],10,10,60);
  $y = $this->GetY();
  $this->SetDrawColor(220,220,220);
  $this->SetFillColor(220,220,220);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"REPORTE CAJA DE COLORES ",1,0,'L',true);
  $this->SetFont('','B',8);
  $this->Cell(88,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
  $this->Ln();
  $this->SetFont('','B',8);
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
  $this->Ln();
  $this->Cell(100,0,'','');
  $this->Cell(88,5,"SEMANA: ".$this->elementsHeader['fecha'],1,0,'L',true);
  $this->Cell(88,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
  $this->Ln();
  $this->Ln();
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL ESCENAS: ".$this->elementsHeader['total_escenas'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->Cell(2,5,"",1,0,'L',true);
  $this->SetDrawColor(131,185,61);
  $this->SetFillColor(131,185,61);
  //$this->Cell(50,5,"TOTAL MINUTOS: ".$this->elementsHeader['tiempo_diponible'],1,0,'L',true);
  $this->SetDrawColor(255,255,255);
  $this->SetFillColor(255,255,255);
  $this->SetTextColor(0);
  $this->SetFont('','B',7);
  $this->SetTextColor(255);
  $this->SetDrawColor(83,83,87);
  $this->SetFont('','B');
  $this->SetFont('','',7);
  $this->SetDrawColor(0,0,0);
  $this->SetFillColor(83,83,87);
$tbl4="";
}
  $this->writeHTML($tbl4, true, false, false, false, '');
    }
    // Page footer
    public function Footer() {
         $this->SetFont('', '', 7);
         $this->SetDrawColor(220,220,220);
         $this->SetFillColor(220,220,220);
         $this->SetY(-10);
         if($this->lunes!="" OR $this->tipoPDF==3 OR $this->tipoPDF==4 OR $this->tipoPDF==5){
          $this->Cell(200,4,'  FECHA REPORTE: '.strtoupper(date("d-M-Y  H:i:s")).'                                                             '.'Todos los derechos reservados Produciones RTI S.A.S',1,0,'L',true);
        }else{
          $this->Cell(200,4,'FECHA REPORTE: '.strtoupper(date("d-M-Y  H:i:s")).'                                                                                                         Todos los derechos reservados Produciones RTI S.A.S',1,0,'L',true);
        }
         $this->Cell(0,4,'Pag: '.$this->getPage().'/'.$this->getAliasNbPages(),0,0,'R',true);
    }

    public function setTitulo($titulo){
        $this->titulo = $titulo;
    }

    public function setCadenaHeader($cadenaHeader){
        $this->cadenaHeader = $cadenaHeader;
    }

    public function setElementsHeader($elementsHeader){
        $this->elementsHeader = $elementsHeader;
    }

    public function setTipoHeader($tipoHeader){
        $this->tipoHeader = $tipoHeader;
    }

    public function setTipoPdf($tipoPdf){
        $this->tipoPDF = $tipoPdf;
    }

    public function setNumeroUnidad($numeroUnidad){
        $this->numeroUnidad = $numeroUnidad;
    }

    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function setTiempo($tiempo){
        $this->tiempo = $tiempo;
    }

    public function setCantidadProducidas($cantidad_producidas){
        $this->cantidad_producidas = $cantidad_producidas;
    }

    public function setTiempoProducido($tiempo_producido){
        $this->tiempo_producido = $tiempo_producido;
    }

    public function setLunes($lunes){
        $this->lunes = $lunes;
    }

    public function setMartes($martes){
        $this->martes = $martes;
    }

    public function setMiercoles($miercoles){
        $this->miercoles = $miercoles;
    }

    public function setJueves($jueves){
        $this->jueves = $jueves;
    }

    public function setViernes($viernes){
        $this->viernes = $viernes;
    }

    public function setSabado($sabado){
        $this->sabado = $sabado;
    }

    public function setDomingo($domingo){
        $this->domingo = $domingo;
    }

    public function setFecha($fecha){
        $this->fecha = $fecha;
    }


}
?>
