    public function carga_contrato(){
        setlocale(LC_TIME, 'spanish'); 
        $idcontrato = $_POST['id_contrato'];
        $id_solicitud = $_POST['id_solicitud'];
        $alineado_justificado = " style=\"text-align:justify;\" ";
        $alineado_centrado = " style=\"text-align:center;\" ";
        $clase_tabla = " class=\"firm_cell\" ";
        $lista = " style=\"margin-left:2%;\" ";
        $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        $dias = array("","PRIMERO","DOS","TRES","CUATRO","CINCO","SÉIS","SIETE","OCHO","NUEVE","DIEZ","ONCE","DOCE","TRECE",
            "CATORCE","QUINCE","DIECISÉIS","DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUNO", "VEINTIDÓS", "VEINTITRES (23)", "VEINTICUATRO", "VEINTICINCO",
            "VEINTISÉIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE", "TREINTA", "TREINTA Y UNO");


        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $contrato = $this->model_casting->contrato_id($idcontrato);
        $pasaporte = $this->model_casting->documento_extranjero($solicitud[0]->id_actor,'5');
        $responsables_contrato = $this->model_casting->responsables_contrato();
        if (!$pasaporte) {
            @$pasaporte[0]->numero = '-';
            @$pasaporte[0]->pais = '-';
        }

        $visa = $this->model_casting->documento_extranjero($solicitud[0]->id_actor,'6');
        if (!$visa) {
            @$visa[0]->numero = '-';
            @$visa[0]->pais = '-';
            @$visa[0]->vigencia = '-';
        }else{
            if ($visa[0]->vigencia AND $visa[0]->vigencia!="0000-00-00") {
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") DE ".$meses[date("n")-1]." DE ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }
        $id_otro_si = "";
        //NUMERACION OTRO SI
        if ($solicitud[0]->id_solicitud_anexa) {
            $id_otro_si = $this->numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);
        }

        $clausula="4";
        //NUMERO CALUSULA
        if ($solicitud[0]->edad<18) {
            $clausula="5";
        }

        $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span></strong> <strong>', '<strong>', $contrato[0]->contrato);

        if ($solicitud[0]->id_tipo_moneda==2) {
            $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE (<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ',  $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
        }

        if ($solicitud[0]->descExt=='PASAPORTE') {
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span> de <span class=hightltght_yellow><strong>".$solicitud[0]->ciudExt."</strong></span>','<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span> de <span class=hightltght_yellow><strong>".$solicitud[0]->paisExt."</strong></span>',  $contrato[0]->contrato);
        }

        $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." DE ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        
        $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final))-1  ].', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.', '<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.<br>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('$meses[date("n",strtotime($solicitud[0]->fecha_inicio)-1)]', '$meses[date("n",strtotime($solicitud[0]->fecha_inicio))-1]', $contrato[0]->contrato);
        
        $contrato[0]->contrato = str_replace('".$dias[intval(date("d"))]." DE ".', '".$dias[intval(date("d"))]." (".date("d").") DE ".', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('valor y tenor, día (<span class=hightltght_yellow>".date("d")."</span>) DE', 'valor y tenor, día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>) DE', $contrato[0]->contrato);

        $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras)."</span>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span', 'igual valor y tenor, el día ".$dias[intval(date("d"))]." (<span', $contrato[0]->contrato);

        if ($solicitud[0]->tipo_documento!="C.C") {
            $contrato[0]->contrato = str_replace('cédula de ciudadanía número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', strtolower($solicitud[0]->tipo_documento).' número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con '.mb_strtolower($solicitud[0]->tipo_documento).'  número', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con cédula de ciudadania número', $contrato[0]->contrato);
        }

        $contrato[0]->contrato = str_replace(' el presente documento el día (<span class=hightltght_yellow>".date("d")."</span>)', ' el presente documento el día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>)', $contrato[0]->contrato);

        if ($solicitud[0]->id_tipo_moneda!=1) {
           $contrato[0]->contrato = str_replace('M/CTE', '', $contrato[0]->contrato);
        }

        if ($contrato){ 
            $str = $contrato[0]->contrato;
            eval("\$str = \"$str\";");
        }

        echo json_encode($str);
    }