<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produccion extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_produccion');
	    $this->load->model('model_plan_produccion');
	    $this->load->model('model_admin');
	    $this->load->model('model_capitulos');
	    $this->load->model('model_elementos');
	    $this->load->model('model_post_produccion');
	    $this->load->helper('cookie'); 
	    $this->_logeo_in();
	}

	function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
        redirect ($this->lang->lang().'/login/disconnecUser');
      }
    }
	
	public function index($msg=''){
		$tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
		if($tipo_usuario=='1' OR $tipo_usuario=='4' OR $tipo_usuario=='3'){
	        if($msg==1){
	          $msg='<div class="alert-box success">
	                     Registro exitoso<a href="" class="close">&times;</a>
	                </div>';
			}else{
				$msg='';
			}
		    $centro_produccion=$this->model_produccion->centro_produccion();
		    $tipo_produccion=$this->model_produccion->tipo_produccion();
		    $ejecutivo=$this->model_produccion->tipo_usuario();
		    $productor=$this->model_produccion->tipo_usuario();
		    $director_unidad=$this->model_produccion->tipo_usuario_otros_2(5,7);
		    $script=$this->model_produccion->tipo_usuario_otros_2(5,1);
			$data['centro_produccion']=$centro_produccion;
			$data['tipo_produccion']=$tipo_produccion;	
			$data['ejecutivo']=$ejecutivo;
			$data['productor']=$productor;
			$data['msg']=$msg;
			$data['director_unidad']=$director_unidad;
			$data['script']=$script;
		    $data['view']='produccion/crear_produccion';
		    $this->load->view('includes/template',$data);
		}else{
			redirect ($this->lang->lang().'/produccion/producciones');
		}
	}


	public function fechaFormat($fecha){
		$meses = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$meses_es = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
	    $cont=0;
		 $f=str_replace('Ene','Jan',$fecha); 
		 $f=str_replace('Feb','Feb',$f); 
		 $f=str_replace('Mar','Mar',$f); 
		 $f=str_replace('Abr','Apr',$f);
		 $f=str_replace('May','May',$f);
		 $f=str_replace('Jun','Jun',$f);
		 $f=str_replace('Jul','Jul',$f); 
		 $f=str_replace('Ago','Aug',$f); 
		 $f=str_replace('Sep','Sep',$f); 
		 $f=str_replace('Oct','Oct',$f); 
		 $f=str_replace('Nov','Nov',$f); 
		 $f=str_replace('Dic','Dec',$f); 
		 
		 return date("Y-m-d",strtotime($f));
		//echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	}

	public function crear_produccion(){
		$rutaServidor="images/produccion";
		$rutaTemporal= $_FILES["image_production"]["tmp_name"];
		$nombreimage= $_FILES["image_production"]["name"];
		$nombre=$nombreimage;
		$rutaDestino= $rutaServidor.'/'.$nombre;
		$rutaTemporal= $_FILES["image_production"]["tmp_name"];
		$nombreimage= $_FILES["image_production"]["name"];			
		$this->form_validation->set_rules('name_production','Nombre produccion','required');
		$this->form_validation->set_rules('centro_produccion','Centro Produccion','required');
		$this->form_validation->set_rules('dias_sem','Días por semana','required');
		$this->form_validation->set_rules('unidades','Número de Unidades','required');
	    $this->form_validation->set_rules('type_production','Tipo de Produccion','required');
	    $new_date = strtotime($this->input->post('fin_grabacion'));
		$this->form_validation->set_message('required','%s es requerido');
		if($this->form_validation->run()==FALSE) {
			$this->index();
		}else{
			$dias_grabacion=$this->input->post('dias_sem');
			//$inicio_PreProduccion= date("Y-m-d",strtotime($this->input->post('inicio_PreProduccion')));//
			$inicio_PreProduccion=$this->fechaFormat($this->input->post('inicio_PreProduccion'));//
			//$fin_grabacion= date("Y-m-d",strtotime($this->input->post('fin_grabacion')));
			$fin_grabacion= $this->fechaFormat($this->input->post('fin_grabacion'));
			if($this->input->post('dias_sem')!=0){
				$data_dias=array(
                      'dias_grabacion'=>$this->input->post('dias_sem'),
					  'lunes'=> $this->input->post('lunes'),
					  'martes'=> $this->input->post('martes'),
					  'miercoles'=> $this->input->post('miercoles'),
					  'jueves'=> $this->input->post('jueves'),
				      'viernes'=> $this->input->post('viernes'),
					  'sabado'=> $this->input->post('sabado'),
					  'domingo'=> $this->input->post('domingo')
				);
				$dias_semana=$this->model_produccion->insert_dias($data_dias);
				$id_dias_grabacion=mysql_insert_id();
			}else{
                $id_dias_grabacion=null;
			}

			if($this->input->post('productor_ejecutivo')=='null'){
				$productor_ejecutivo=null;
			}else {
				$productor_ejecutivo=$this->input->post('productor_ejecutivo');
			}
			if($this->input->post('productor_general')=='null'){
				$productor_general=null;
			}else{
				$productor_general=$this->input->post('productor_general');
			}
			if($this->input->post('productor')=='null'){
				$productor=null;
			}else {
				$productor=$this->input->post('productor');
			}
			if($_FILES["image_production"]["tmp_name"]){
				$rutaServidor="images/produccion";
				$rutaTemporal= $_FILES["image_production"]["tmp_name"];
				$nombreimage= $_FILES["image_production"]["name"];
				$nombre=preg_replace('/ /','_', $nombreimage);
				$rutaDestino= $rutaServidor.'/'.$nombre;
				move_uploaded_file($rutaTemporal, $rutaDestino) ;
			}else{
				$rutaDestino=null;
			}
			$facha_a ="";
			if($this->input->post('fecha_aire')!=""){
				//$facha_a = date("Y-m-d",strtotime($this->input->post('fecha_aire')));

				$facha_a = $this->fechaFormat($this->input->post('fecha_aire'));
			}

			if($this->input->post('inicio_grabacion')!=""){
				//$fecha_i = date("Y-m-d",strtotime($this->input->post('inicio_grabacion')));
				$fecha_i = $this->fechaFormat($this->input->post('inicio_grabacion'));
			}else{
				$fecha_i = "";
			}

        	$data_produccion=array(
                'nombre_produccion'=>$this->input->post('name_production'),
                'id_centroProduccion'=>$this->input->post('centro_produccion'),
                'id_tipoProduccion'=>$this->input->post('type_production'),
                //'inicio_PreProduccion'=>date("Y-m-d",strtotime($this->input->post('inicio_PreProduccion'))),
                'inicio_PreProduccion'=>$this->fechaFormat($this->input->post('inicio_PreProduccion')),
                'inicio_grabacion'=>$fecha_i,
                'fecha_aire'=> $facha_a,
                //'fin_grabacion'=>date("Y-m-d",strtotime($this->input->post('fin_grabacion'))),
                'fin_grabacion'=>$this->fechaFormat($this->input->post('fin_grabacion')),
                'numero_capitulo'=>$this->input->post('number_cap'),
                'minuto_capitulo'=>$this->input->post('mins_cap'),
                'segundos_capitulo'=>$this->input->post('segundos_capitulo'),
                'cap_ese_semana'=>$this->input->post('write_cap'),
                'min_proy_seman'=>$this->input->post('min_proy_sem'),
                'seg_proy_seman'=>$this->input->post('seg_proy_sem'),
                'id_dias_grabacion'=>$id_dias_grabacion,
                'id_productor_ejecutivo'=>$productor_ejecutivo,
                'id_productor_general'=>$productor_general,
                'id_productor'=>$productor,
                'numero_unidades'=>$this->input->post('unidades'),
                'produccion_interior'=>$this->input->post('rang_prod_amount1'),
                'produccion_exterior'=>$this->input->post('rang_prod_amount2'),
                'locacion'=>$this->input->post('locacion'),
                'estudio'=>$this->input->post('estudio'),
                'over_time'=>$this->input->post('over_time'),
                'dia'=>$this->input->post('dia'),
                'noche'=>$this->input->post('noche'),
                'numero_locaciones'=>$this->input->post('numero_locaciones'),
                'numero_set'=>$this->input->post('numero_set'),
                'imagen_produccion'=>$rutaDestino,
				'numero_protagonistas'=>$this->input->post('numero_protagonistas'),
				'numero_repartos'=>$this->input->post('numero_repartos'),
				'numero_figurantes'=>$this->input->post('numero_figurantes'),
				'numero_extras'=>$this->input->post('numero_extras'),
				'numero_vehiculos'=>$this->input->post('numero_vehiculos'),
				'presupuesto_principales'=>$this->input->post('presupuesto_principales'),
				'presupuesto_secundarios'=>$this->input->post('presupuesto_secundarios'),
				'presupuesto_figurante'=>$this->input->post('presupuesto_figurante'),
				'escenas_libretos'=>$this->input->post('escenas_libretos'),
				'evento_pequeno'=>$this->input->post('evento_pequeno'),
				'evento_mediano'=>$this->input->post('evento_mediano'),
				'evento_grande'=>$this->input->post('evento_grande'),
				'protagonistas_produccion'=>$this->input->post('protagonistas_produccion'),
				'monto_figurante_extra'=>str_replace(',','',$this->input->post('monto_figurante_extra')),
				'monto_figurante_extra_dolar'=>str_replace(',','',$this->input->post('monto_figurante_extra_dolar')),
				'paginasPorLibretos'=>$this->input->post('paginas_libretos'),
			);

            $produccion=$this->model_produccion->insert_produccion($data_produccion);

			
            if($produccion){
                $id_produccion=mysql_insert_id();
                $this->categorias_elementos_default($id_produccion);
                $cont=1;
                $unidades=$this->input->post('unidades');
                if($unidades!=0){
                   	while ($cont<=$unidades) {
                   		if($this->input->post('director_'.$cont)=='null'){
                           $director=null;
                   		}else{
                   			$director=$this->input->post('director_'.$cont);
						   	$data=array(
			                    'id_user'=>$director,
			                    'rol'=>7,
			                    'id_produccion'=>$id_produccion);
						   	if(!$this->model_plan_produccion->user_has_produccion($data)){
						   		$this->model_plan_produccion->user_has_produccion($data);
						   	}
                   		}
                   		if($this->input->post('script_'.$cont)=='null'){
                           $script=null;
                   		}else {
                   			 $script=$this->input->post('script_'.$cont);
                   			 $data=array(
			                    'id_user'=>$script,
			                    'rol'=>1,
			                    'id_produccion'=>$id_produccion);
                   			if(!$this->model_plan_produccion->user_has_produccion($data)){
                   				$this->model_plan_produccion->user_has_produccion($data);
                   			}
                   		}
						if($this->input->post('date_start'.$cont)){
							//$fecha_unidad=date("Y-m-d",strtotime($this->input->post('date_start'.$cont)));
							$fecha_unidad=$this->fechaFormat($this->input->post('date_start'.$cont));
						}else {
							$fecha_unidad=null;
						}
	                   	$data_unidades=array(
	                        'id_director'=>$director,
	                        'id_script'=>$script,
	                        'fecha_inicio'=>$fecha_unidad,
	                        'id_produccion'=>$id_produccion,
	                        'numero'=>$cont
	                    );
	                   	$unidad=$this->model_produccion->insert_unidad($data_unidades);
	                    $cont++;
                    }
                   
                    $dias=((strtotime($fin_grabacion)-strtotime($inicio_PreProduccion))/86400);
                    $fecha= $fin_grabacion; 
                    $i = strtotime($fecha); 
                    $dia_semana_final=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
                 
                    if($dia_semana_final<=0){
                   		$dia_semana_final=0;
                    }else{
                   		$dia_semana_final=7-$dia_semana_final;
                    }
                    $fecha= $inicio_PreProduccion; 
                    $i = strtotime($fecha); 
                    $dia_semana=jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
                    if($dia_semana<0){
                    	$dia_semana=0;
                    }
                    $dias=$dias+$dia_semana+$dia_semana_final;
		            $semanas= $dias/7;
		            $semanas= ceil($semanas);
                    $i=1;
                    $cont=0;

                    $libretos_proyectados = $this->input->post('write_cap')*$semanas;
              	    $semana_final = 0;
              	    $valor_semana_final = 0;

              	   $libretos_prgramados = $this->input->post('number_cap');

                    while($i<=$semanas){
                       	if($cont==0){
							$fecha = strtotime($inicio_PreProduccion);
							$ano= date("Y", $fecha); // Year (2003)
							$mes= date("m", $fecha); // Month (12)
							$dia= date("d", $fecha); // day (14)
							$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
							$dia=7-$dia;
	                       	  	
		                    $fecha_inicio_semana = $inicio_PreProduccion;
		                    $fecha = $fecha_inicio_semana;
		                    $nuevafecha = strtotime ( '+'.$dia.' day' , strtotime ( $fecha ) ) ;
		                    $fecha_semana_final = date ( 'Y-m-j' , $nuevafecha );

		                    $cont++;
                    	}else{
	                      $fecha = $fecha_semana_final;
	                      $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
	                      $fecha_inicio_semana = date ( 'Y-m-j' , $nuevafecha );

	                      $fecha = $fecha_inicio_semana;
	                      $nuevafecha = strtotime ( '+6 day' , strtotime ( $fecha ) ) ;
	                      $fecha_semana_final = date ( 'Y-m-j' , $nuevafecha );
                    	}
                    	if($this->input->post('dias_sem')==1){
                           $lunes='checked';$martes='';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==2){
                           $lunes='checked';$martes='checked';$miercoles='';$jueves='';$viernes='';$sabado='';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==3){
                           $lunes='checked';$martes='checked';$miercoles='checked';$jueves='';$viernes='';$sabado='';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==4){
                           $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='';$sabado='';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==5){
                           $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==6){
                           $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='';
                    	}
                    	if($this->input->post('dias_sem')==7){
                           $lunes='checked';$martes='checked';$miercoles='checked';$jueves='checked';$viernes='checked';$sabado='checked';$domingo='checked';
                    	}

                    	if($libretos_prgramados >= $this->input->post('write_cap')){
	          				$capitulos_programados = $this->input->post('write_cap');
	          			}else{
	          				if($libretos_prgramados < $capitulos_programados AND $libretos_prgramados>0){
	          					$capitulos_programados = $libretos_prgramados;
	          				}else{
	          					$capitulos_programados = 0;
	          				}
	          			}
	          			$libretos_prgramados-=$capitulos_programados;	
                    	$date=array(
                    		'dias_trabajo'=>$dias_grabacion,
                    		'fecha_inicio_semana'=>$fecha_inicio_semana,
                    		'fecha_fin_semana'=>$fecha_semana_final,
                    		'capitulos_programados'=>$capitulos_programados,
                          	'minutos_proyectados'=>$this->input->post('min_proy_sem'),
                          	'segundos_proyectados'=>$this->input->post('seg_proy_sem'),
                          	'lunes'=>$lunes,
                          	'martes'=>$martes,
                          	'miercoles'=>$miercoles,
                          	'jueves'=>$jueves,
                          	'viernes'=>$viernes,
                          	'sabado'=>$sabado,
                          	'domingo'=>$domingo,
                    		'id_produccion'=>$id_produccion,
                    	);

                    	$sem = $this->model_produccion->semanas_produccion($date);
                    	echo $i.'---';
                    	$i++;	
                    }
                }
              	$fecha_aire = $this->input->post('fecha_aire');
              	$this->agregar_capitulos($id_dias_grabacion,$id_produccion, $fecha_aire);
			    redirect ($this->lang->lang().'/produccion/agregar_usuarios/'.$id_produccion."");
            }else{
           	  echo 'error al inserta la produccion';
            }
		}
	}

	public function agregar_capitulos($id_dias_grabacion,$id_produccion, $fecha_aire){
		$validator = false; 
		if($fecha_aire!=""){
			//$fecha_aire = date("Y-m-d",strtotime($this->input->post('fecha_aire')));
			$fecha_aire = $this->fechaFormat($this->input->post('fecha_aire'));
			$validator = true;	
		}else{
			$validator = false; 
		}
        $numero_capitulos = $this->input->post('number_cap');
        $dias = $this->model_capitulos->contar_dias_aire($id_dias_grabacion);
        $id_capitulo = "";
        $fecha="";
        $nueva_fecha="";
        for($i=0; $i<$numero_capitulos; ++$i) {
        	if($i==0){
        		if(!$validator){
        			$fecha_aire = '0000-00-00';
        		}
            	$data=array(
    				'id_produccion'=>$id_produccion,
            		'numero'=>($i+1),
            		'estado'=>1,
            		'duracion_estimada'=> '00:00',
            		'fecha_aire' => $fecha_aire
                );
				//$this->model_capitulos->insertar_capitulo($data);
				$id_capitulo = mysql_insert_id();
				$data=array(
					'id_produccion'=>$id_produccion,
            		'numero'=>($i+1),
            		'id_estado'=>2,
            		'fecha_entrega' => $fecha_aire
           		);
            	//$this->model_capitulos->insertar_capitulo_post($data);
            	$id_capitulo_post = mysql_insert_id();
	            $data_capitulo = array(
	                'id_capitulo' => $id_capitulo_post, 
	                'id_estado' => 2,
	                'activo'=>1
	            );
	            //$this->model_post_produccion->insertar_estado_capitulo($data_capitulo);

	            /*$id_user_prod=$this->model_post_produccion->user_produccion_rol2($id_produccion);
	            if($id_user_prod){
	                foreach ($id_user_prod as $user) {
	                    $data=array(
	                    'id_user'=>$user['id_usuario'],
	                    'id_capitulo'=>$id_capitulo_post,
	                    'estado'=>1
	                    );
	                    $this->model_capitulos->insertar_capitulo_user($data);
	                    $u=$this->model_produccion->user_id($user['id_usuario']);
	                    $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
	                    $cuerpo='Usted fue asigando al capitulo: '.$numero.' de la produccion: '.$produccion['0']->nombre_produccion.' ';
	                    $this->email($u['0']->correo,$cuerpo);
	                }
	            }*/
        	}else{
      			if($dias[0]['lunes']==0 AND $dias[0]['martes']==0 AND $dias[0]['miercoles']==0 AND $dias[0]['jueves']==0 AND $dias[0]['viernes']==0 AND $dias[0]['sabado']==0 AND $dias[0]['domingo']==0){
	      			$fecha="0000-00-00";
      			}
        		if($validator){
	        		$j=86400;
	        		$ultima_fecha = $this->model_capitulos->bucar_capitulo_id($id_capitulo);
	        		$ultima_fecha = $ultima_fecha[0]['fecha_aire'];
	        		$active = 0;
	        		while($active==0){
	        			$nueva_fecha = strtotime($ultima_fecha)+$j;
	        		    switch(date("D",$nueva_fecha)){
		                	case "Mon":
		                		if($dias[0]['lunes'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
		                			$active = 1;
		                		}
		                	break;
		                	case "Tue":
		                		if($dias[0]['martes'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
		                	case "Wed":
		                		if($dias[0]['miercoles'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
		                	case "Thu":
		                		if($dias[0]['jueves'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
		                	case "Fri":
		                		if($dias[0]['viernes'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
		                	case 'Sat':
		                		if($dias[0]['sabado'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
		                	case 'Sun':
		                		if($dias[0]['domingo'] == '1'){
		                			$fecha = date("Y-m-d",$nueva_fecha);
					                $active = 1;
			                	}
		                	break;
	                	}
	                	$j+=86400;
	        		}
	        	}else{
	        		$fecha = "0000-00-00";
	        	}
        		$data=array(
    				'id_produccion'=>$id_produccion,
            		'numero'=>($i+1),
            		'estado'=>1,
            		'duracion_estimada'=> '00:00',
            		'fecha_aire' => $fecha
                );
                //$this->model_capitulos->insertar_capitulo($data);
                $id_capitulo = mysql_insert_id();
                $data=array(
					'id_produccion'=>$id_produccion,
            		'numero'=>($i+1),
            		'id_estado'=>2,
            		'fecha_entrega' => $fecha
           		);
            	//$this->model_capitulos->insertar_capitulo_post($data);
            	$id_capitulo_post = mysql_insert_id();
	            $data_capitulo = array(
	                'id_capitulo' => $id_capitulo_post, 
	                'id_estado' => 2,
	                'activo'=>1
	            );
	            //$this->model_post_produccion->insertar_estado_capitulo($data_capitulo);

	            $id_user_prod=$this->model_post_produccion->user_produccion_rol2($id_produccion);
	            if($id_user_prod){
	                foreach ($id_user_prod as $user) {
	                    $data=array(
	                    'id_user'=>$user['id_usuario'],
	                    'id_capitulo'=>$id_capitulo_post,
	                    'estado'=>1
	                    );
	                    //$this->model_capitulos->insertar_capitulo_user($data);
	                    $u=$this->model_produccion->user_id($user['id_usuario']);
	                    $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
	                    $cuerpo='Usted fue asigando al capitulo: '.$numero.' de la produccion: '.$produccion['0']->nombre_produccion.' ';
	                    $this->email($u['0']->correo,$cuerpo);
	                }
	            }
        	}
		}  
	}

	public function agregar_usuarios($id){
		$produccion=$this->model_plan_produccion->produccion_id($id);
		$centro_produccion=$this->model_produccion->centro_produccion();
		$tipo_produccion=$this->model_produccion->tipo_produccion();
		$user=$this->model_produccion->tipo_usuario();
		$ejecutivo=$this->model_produccion->user_id($produccion['0']->id_productor_ejecutivo);
		$productor_general=$this->model_produccion->user_id($produccion['0']->id_productor_general);
		$productor=$this->model_produccion->user_id($produccion['0']->id_productor);
		$director_unidad=$this->model_produccion->tipo_usuario_otros(5,7);
		$script=$this->model_produccion->tipo_usuario_otros(5,1);
		$unidades=$this->model_plan_produccion->unidades_id_produccion($id);
		$roles=$this->model_plan_produccion->rol_otros();
		$user_has_produccion=$this->model_plan_produccion->user_has_roles_id(2);
		$usuarios_producion=$this->model_plan_produccion->usuarios_produccion($produccion['0']->id_produccion);
		$semanas_trabajo=$this->model_plan_produccion->semanas_trabajo($produccion['0']->id_produccion);
		$data['produccion']=$produccion;
		$data['centro_produccion']=$centro_produccion;
		$data['tipo_produccion']=$tipo_produccion;	
		$data['user']=$user;
		$data['director_unidad']=$director_unidad;
		$data['script']=$script;
		$data['productor_general']=$productor_general;
		$data['productor']=$productor;
		$data['ejecutivo']=$ejecutivo;
		$data['unidad']=$unidades;	
		$data['roles']=$roles;	
		$data['semanas_trabajo']=$semanas_trabajo;
		$data['usuarios_producion']=$usuarios_producion;	
		$data['user_has_produccion']=$user_has_produccion;		
		$data['view']='produccion/agregar_usuarios';
		$this->load->view('includes/template',$data);
	}

	/*  FUNCION PARA VALIDAR EL TIPO DE IMAGEN*/ 
 	public function _validacion_img($FILES){
     	if ($_FILES["image_production"]["type"]=="image/jpeg" || $_FILES["image_production"]["type"]=="image/pjpeg" || $_FILES["image_production"]["type"]=="image/gif" || $_FILES["image_production"]["type"]=="image/bmp" || $_FILES["image_production"]["type"]=="image/png"){
	       return true;  
	    }else{
	        return false;
	    }  
    }

    public function producciones(){
        $id_user=$this->session->userdata('id_pruduction_suite');
        $tipo_user=$this->session->userdata('tipo_pruduction_suite');
        
		if($tipo_user=='3' or $tipo_user=='1' OR $tipo_user=='2' OR $tipo_user=='7' OR $tipo_user=='8'  OR $tipo_user=='10'){
				if($this->input->post('filter_prod')=="3"){
					$estado=3;
					$sql=" ";
		        }elseif($this->input->post('filter_prod')=="1"){
		        	$estado=1;
					$sql=" WHERE p.estado =1";
		        }elseif($this->input->post('filter_prod')=="2"){
		        	$estado=2;
					$sql=" WHERE p.estado =2";
		        }else{
		        	$estado=1;
					$sql=" WHERE p.estado =1";
		        }
		  	$produccion=$this->model_produccion->producciones_all($sql);
		}else{
			if($this->input->post('filter_prod')=="3"){
					$estado=3;
					$sql=" ";
		        }elseif($this->input->post('filter_prod')=="1"){
		        	$estado=1;
					$sql=" AND p.estado =1";
		        }elseif($this->input->post('filter_prod')=="2"){
		        	$estado=2;
					$sql=" AND p.estado =2";
		        }else{
		        	$estado=1;
					$sql=" AND p.estado =1";
		        }
		  	$produccion=$this->model_produccion->producciones_user($id_user,$sql);
		}
      	$data['produccion'] = $produccion;
      	$data['estado'] = $estado;
      	$data['view'] = "produccion/index";
        $this->load->view('includes/template',$data); 
    }


	public function categorias_elementos_default($id_produccion){
		$data[0]=array(
			 'tipo'=>'Personaje',
			 'id_produccion'=>$id_produccion,
	    );
	    $data[1]=array(
	    	'tipo'=>'Utileria',
			 'id_produccion'=>$id_produccion,
		);
		$data[2]=array(
	    	'tipo'=>'Maquillaje',
			 'id_produccion'=>$id_produccion,
		);
		$data[3]=array(
	    	'tipo'=>'Vestuario',
			 'id_produccion'=>$id_produccion,
		);
		$data[4]=array(
	    	'tipo'=>'Vehiculo',
			 'id_produccion'=>$id_produccion,
		);
		$data[5]=array(
	    	'tipo'=>'Efectos Especiales',
			 'id_produccion'=>$id_produccion,
		);
		$data[6]=array(
	    	'tipo'=>'Seguridad ambiental',
			 'id_produccion'=>$id_produccion,
		);
		$data[7]=array(
	    	'tipo'=>'Producción',
			 'id_produccion'=>$id_produccion,
		);
		$data[8]=array(
	    	'tipo'=>'Vehículos Background',
			 'id_produccion'=>$id_produccion,
		);
		
		$cont=0;
		while($cont<=8){
			$this->model_elementos->categorias_elementos_default($data[$cont]);
			$cont++;
		}
      	return true;
	}

	public function cambiar_estado(){
		$id_produccion=$_POST['id_produccion'];
		$estado=$this->model_produccion->cambiar_estado($id_produccion);
		if($estado){
            $data['0']='1';
		}else{
        	$data['0']=$id_produccion;
		}
		echo json_encode($data);
	}

	
    public function validate_production_name(){
    	$nombre_produccion = $_POST['name_production'];
		$nombre_produccion = $this->model_produccion->buscar_nombre($nombre_produccion);
    	if($nombre_produccion != false AND $nombre_produccion[0]->cantidad !='0'){
			$data['response']=1;
		}else{
			$data['response']=2;
		}
		echo json_encode($data);
    }

    public function validate_production_name_produccion(){
    	$nombre_produccion = $_POST['name_production'];
    	if(isset($_POST['idproduccion'])){
    		$idproduccion = $_POST['idproduccion'];
    	}else{
    		$idproduccion=0;
    	}
		$nombre_produccion = $this->model_produccion->buscar_nombre_produccion($nombre_produccion,$idproduccion);
    	if($nombre_produccion != false AND $nombre_produccion[0]->cantidad !='0'){
			$data['response']=1;
		}else{
			$data['response']=2;
		}
		echo json_encode($data);
    }

    public function buscar_planes_unidad(){
    	$idunidad = $_POST['idunidad'];
    	$asignados = $this->model_produccion->buscar_planes_unidad($idunidad);
    	if($asignados != false){
			$data['asignados']=1;
		}else{
			$data['asignados']=2;
		}
		echo json_encode($data);
    }

    public function eliminar_unidad(){
    	$idunidad = $_POST['idunidad'];
    	$idproduccion = $_POST['idproduccion'];
    	$asignados = $this->model_produccion->eliminar_unidad($idunidad);
    	$this->model_produccion->actualizar_unidades($idproduccion);
    	$data['respuesta']=1;
    	echo json_encode($data);
    }

    /*FUNCION PARA BUSCAR USUARIOS*/
    public function buscar_usuarios(){
    	$id = $this->session->userdata('id_pruduction_suite');
    	$apellido = $_POST['apellido'];
    	$usuarios = $this->model_produccion->buscar_usuarios_apellido($apellido,$id);
		$cadena_tabla='
		<thead>
			<tr>
				<th width="12%">'.lang('global.nombre').'</th>
				<th width="12%">'.lang('global.apellido').'</th>
				<th width="24%">'.lang('global.correo').'</th>
				<th width="10%">'.lang('global.idioma').'</th>
				<th width="20%">'.lang('global.tipo_usuario').'</th>
				<th width="9%">'.lang('global.estado').'</th>
				<th width="12%">'.lang('global.acciones').'</th>
			</tr>
		</thead>
		<tbody>';
    	$idusuario = $this->session->userdata('id_pruduction_suite');
    	if($usuarios){
			$i=0;
	    	foreach ($usuarios as $usuario) {
	    		$cadena_tabla.='<tr';
	    		$style="";
	    		if($i%2!=0){
	    			$style="background:#e6e4e4 !important;";
	    		}
	    		++$i;
	    		$cadena_tabla.=' style="'.$style.'">';
				$cadena_tabla.='<td>'.$usuario['nombre'].'</td>';
				$cadena_tabla.='<td>'.$usuario['apellido'].'</td>';
				$cadena_tabla.='<td>'.$usuario['correo'].'</td>';
				$cadena_tabla.='<td>';
				if($usuario['idioma']=='es'){ 
					$cadena_tabla.='Español'; 
				}else{ 
					$cadena_tabla.='Ingles'; 
				} 
				$cadena_tabla.='</td>';

				$cadena_tabla.='<td>';
				$tipo=$this->model_admin->tipoUserId($usuario['id']); 
				$cadena_tabla.=$tipo['0']->descripcion;

				if($tipo['0']->descripcion=='Otros'){
					$cadena_tabla.=':';	
					$rol=$this->model_admin->rolUserId($usuario['id']);
					if($rol){
						foreach ($rol as $r) { 
							$cadena_tabla.= '<strong>';
							$cadena_tabla.= $r['descripcion'].', ';
							$cadena_tabla.= '</strong>';
						}
					}
				}

				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				if($usuario['estado']==1){ 
					$cadena_tabla.='Activo';
				}else{ 
					$cadena_tabla.='Desactivo';
				}
							
				$cadena_tabla.='</td>';
				$cadena_tabla.='<td>';
				$cadena_tabla.='<a href="'.base_url().$this->lang->lang().'/admin_user/editar_user/'.$usuario['id'].'">'.lang('global.editar').'</a>';
				$cadena_tabla.=' | ';
				if($usuario['estado']==1){ 
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="0"> '.lang('global.desactivar').'</a>';
				}else{
					$cadena_tabla.='<a class="cambiar_user" data-iduser="'.$usuario['id'].'" data-estado="1"> '.lang('global.activar').'</a>';
				}
				$cadena_tabla.=' | ';
				$onclick="onclick=\"return confirm('Esta seguro de elimiar este Usuario?')\"";
				$cadena_tabla.="<a href='".base_url().$this->lang->lang()."/admin_user/eliminarUser/".$usuario['id']."' ".$onclick.">".lang('global.eliminar')."</a> ";
				$cadena_tabla.='</td>';
				$cadena_tabla.='</tr>';
				$cadena_tabla.='</tbody>';
			}
		}
		$data['cadena_tabla'] = $cadena_tabla;
		echo json_encode($data);
    }


    public function cambiar_idioma($tipo){

      if($tipo==1){
        $this->config->set_item('language','spanish');
      }else if($tipo==2){
        $this->config->set_item('language','english');
      }
      $metodo=$this->router->fetch_method();
      redirect($this->lang->lang().'/produccion/producciones');
    
    }
}

