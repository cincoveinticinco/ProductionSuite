<?php 

class PDFCASTING extends TCPDF {

	/*VARIABLES GENERALES*/
	var $titulo;
	var $personaje;
	var $proyecto;
	var $tipo_pdf="";
	var $contenido_header ="";
	var $elementsHeader = "";

	public function Header() {
		if($this->tipo_pdf!="Contrato"){
			$this->SetDrawColor(220,220,220);
		    $this->SetFillColor(220,220,220);
			$this->SetFont('', 'B', 7);
			$this->Image(base_url('images/logoPdf.jpg'),10,10,60);
			$this->Ln();
			$this->SetDrawColor(220,220,220);
			$this->SetFillColor(220,220,220);
			$this->Cell(100,0,'','');
			$this->SetFont('','B',8);
			
			
			
			if ($this->proyecto!="" OR $this->personaje!="") {
				$this->Cell(77,5,$this->titulo,1,0,'L',true);
				$this->Cell(100,0,'',1,0,'L',true);
				$this->Ln();
				$this->Cell(100,0,'','');
				$this->Cell(85,5,"PROYECTO: ".$this->proyecto,1,0,'L',true);
				$this->Cell(92,5,"PERSONAJE: ".$this->personaje,1,0,'L',true);
			}else{
				$this->Cell(85,5,$this->titulo,1,0,'L',true);
			}

			//DATOS DE LA PRODUCCION
			if($this->elementsHeader!=""){;
			    $this->Cell(91,5,"PRODUCTOR EJECUTIVO: ".strtoupper($this->elementsHeader['nombre_ejecutivo']),1,0,'L',true);
			    $this->Ln();
			    $this->Cell(100,0,'','');
			    $this->Cell(85,5,"PRODUCCIÓN: ".strtoupper($this->elementsHeader['nombre_produccion']),1,0,'L',true);
			    $this->Cell(91,5,"PRODUCTOR GENERAL: ".strtoupper($this->elementsHeader['productor_general']),1,0,'L',true);
			    $this->Ln();
			    $this->Cell(100,0,'','');
			    $this->Cell(85,5,"CENTRO DE PRODUCCIÓN: ".strtoupper($this->elementsHeader['centro']),1,0,'L',true);
			    $this->Cell(91,5,"PRODUCTOR: ".strtoupper($this->elementsHeader['nombre_productor']),1,0,'L',true);
			}
			// }else{
			// 	$this->Cell(92,5,"",1,0,'L',true);
			// }
			if ($this->titulo!="REPORTE: SOLICITUDES DE PERSONAJES") {
				$this->Ln();
				$this->Cell(100,0,'','');
				$this->Cell(86,5,"FECHA DE REPORTE: ".strtoupper(date("d-M-Y H:i:s")),1,0,'L',true);
				$this->Cell(91,5,"",1,0,'L',true);
			}
			$this->Ln();
			$this->SetTextColor(0);
			$this->SetFont('','B',10);
			$this->Ln();
			$this->SetTextColor(255);
			$this->SetDrawColor(83,83,87);
		}
$tbl4 = <<<EOD
	$this->contenido_header;
EOD;
		$this->SetFont('','B');
		$fill = false;
		$this->SetFont('','',7);
		$this->SetFillColor(83,83,87);
		$this->SetFont('','',7);
		$this->writeHTML($tbl4, true, false, false, false, '');
	}

	public function Footer() {
        $this->SetFont('', '', 7);
        $this->SetDrawColor(220,220,220);
        $this->SetFillColor(220,220,220);
        $this->SetY(-18);
        if ($this->tipo_pdf=="") {
        	if ($this->titulo=="REPORTE: SOLICITUDES DE PERSONAJES") {
	        	$this->Cell(200,4,'  FECHA REPORTE: '.strtoupper(date("d-M-Y  H:i:s")).'                                                                                             Todos los derechos reservados Produciones RTI S.A.S',1,0,'L',true);
        	}else{
	        	$this->Cell(200,4,'                                                                          Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);
        	}
        	$this->Cell(0,4,'Pag: '.$this->getPage().'/'.$this->getAliasNbPages(),0,0,'R',true);
        }else{

	        $this->Cell(180,4,'Todos los derechos reservados Produciones RTI S.A.S',1,0,'C',true);

        	$this->Cell(0,4,'Pag: '.$this->getPage().'/'.$this->getAliasNbPages(),0,0,'R',true);
        }

        
    }

    public function setTitulo($titulo){
        $this->titulo = $titulo;
    }

    public function setProyecto($proyecto){
        $this->proyecto = $proyecto;
    }

    public function setPersonaje($personaje){
        $this->personaje = $personaje;
    }

    public function setTipoPDF($tipo_pdf){
        $this->tipo_pdf = $tipo_pdf;
    }

    public function setContenidoHeader($contenido_header){
        $this->contenido_header = $contenido_header;
    }

    public function setElementsHeader($elementsHeader){
        $this->elementsHeader = $elementsHeader;
    }

}

?>