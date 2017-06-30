<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Casting extends CI_Controller {

    public function __construct (){         
        parent :: __construct (); 
        $this->load->helper('cookie'); 
        $this->load->model('model_elementos');
        $this->load->model('model_plan_produccion');
        $this->load->model('model_casting');
        $this->load->model('model_escenas');
        $this->load->model('model_admin');
        $this->load->model('model_pdf');
        $this->load->model('model_herramientas');
        require_once(APPPATH.'libraries/tcpdf/tcpdf.php');
        require_once(APPPATH.'libraries/excelWriter.php');
        $this->load->library('My_PHPMailer');
        $this->load->library('pdfcasting');
        
        $this->_logeo_in(); 
    }

    function _logeo_in(){
      $login_in = $this->session->userdata('login_pruduction_suite');
      if ($login_in !=true){
         redirect ('login/disconnecUser');
      }
    }

    public function index(){
        ////////////////////////////////
         // FUNCION VERIFICACION DE PERMISOS USUARIOS
        $iduser = $this->session->userdata('id_pruduction_suite');
        $permisos = "read";
        $usuario = $this->model_produccion->user_id($iduser);
        $usuario_roles=$this->model_admin->rolUserId($iduser);
        $resumen_busqueda = "";

          if($usuario_roles){
            foreach ($usuario_roles as $usuario_rol) {
              if($usuario_rol['id_rol_otros']==15){ 
                $permisos = "write";
                break;
              }else{
                $permisos = "read";
              }
            }
          }else{
            if($usuario[0]->id_tipoUsuario=='1' or $usuario[0]->id_tipoUsuario=='3' or $usuario[0]->id_tipoUsuario=='9'){
              $permisos = "write";
            }else{
              $permisos = "read";
            }
          }
        //////////////////////////////////////
          /*
            0 -> buscar ;1 -> nacionalidad;2 -> tipo documento;3 -> numero documento;4 -> Nombre;5 -> Apellido;6 -> genero;
            7 -> Altura desde;8 -> Altura hasta;9 -> peso desde;10 -> peso hasta;11 -> edad desde;12 -> edad hasta;13 -> color tez;
            14 -> color ojos;15 -> idioma;16 -> manager;17 -> Rol;18 -> Roles desempeñados;19 -> Proyectos desempeñados;
            20 -> personajes desempeñados;21 -> año proyecto;22 -> tiene foto;23 -> disponible;24 -> extranjero
            */
          $palabra="";$actores="";
        if(@$_COOKIE['filtro_casting']){
            $filtro=$_COOKIE['filtro_casting'];
            $listFiltro = explode(';', $_COOKIE['filtro_casting']);
            if($listFiltro[0]!='NULL'){
                $check=true;
                    $c=0;
                foreach ($listFiltro as $key => $value) {
                    if($value!='NULL' && $c!=0){
                        $check=false;
                    }
                    $c++;
                }
                if($check){
                    $palabra=$listFiltro[0];
                    $actores= $this->model_casting->filtro_actores_palabra($palabra);
                }
            }
                else {
                        $nacionalidades = $listFiltro[1];
                        $tipo_documento = $listFiltro[2];
                        $documento = $listFiltro[3];
                        $nombre = $listFiltro[4];
                        $apellido = $listFiltro[5];
                        $genero = $listFiltro[6];
                        $altura_desde = $listFiltro[7];
                        $altura_hasta = $listFiltro[8];
                        $peso_desde = $listFiltro[9];
                        $peso_hasta = $listFiltro[10];
                        $edad_desde = $listFiltro[11];
                        $edad_hasta = $listFiltro[12];
                        $color_tez = $listFiltro[13];
                        $color_ojos = $listFiltro[14]; 
                        $idioma = $listFiltro[15];
                        $manager = $listFiltro[16];
                        $rol=$listFiltro[17];
                        $proyectos_desempenados = $listFiltro[19];
                        $personajes_desempenados = $listFiltro[20];
                        $fotos = $listFiltro[22];
                        $disponible = $listFiltro[23];
                        $extranjero = $listFiltro[24];
                        ///////////////////////////////////////////////////////

        $sql = "";
        $sql_inner = "";

        if($nacionalidades!="" && $nacionalidades!="NULL"){
            $nacionalidades = explode(',', $nacionalidades);

            $resumen_busqueda .='<div class="column three"><label for="">Nacionalidad</label>';
            $sql .= " AND ( ";
            for ($i=0; $i < count($nacionalidades); $i++) { 
                if($nacionalidades[$i]!=""){
                    if($i==0){
                        $sql .= " actores.id_nacionalidad = ".$nacionalidades[$i];
                    }else{
                        $sql .= " OR actores.id_nacionalidad = ".$nacionalidades[$i];
                    }
                    $nacionalidad = $this->model_casting->nacionalidad_id($nacionalidades[$i]);
                    $resumen_busqueda .='<span>'.$nacionalidad[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>';
                }
            }
            $sql .= " ) ";
            $resumen_busqueda .='</div>';
        }

        if($tipo_documento  && $tipo_documento!="NULL"){
            $sql .= " AND actores.id_tipo_documento = ".$tipo_documento;
            $tipo_documento = $this->model_casting->tipo_documento_id($tipo_documento);
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Tipo documento</label>
                                    <span>'.$tipo_documento[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($documento  && $documento!="NULL") {
            $sql .= " AND actores.documento = ".$documento;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Documento</label>
                                    <span>'.$documento.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if($nombre  && $nombre!="NULL"){
            $sql .= " AND actores.nombre = '".$nombre."'";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Nombre</label>
                                    <span>'.$nombre.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if($apellido  && $apellido!="NULL"){
            $sql .= " AND UPPER(actores.apellido) = UPPER('".$apellido."')";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Apellido</label>
                                    <span>'.$apellido.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if($manager && $manager!="NULL"){
            $sql .= " AND actores.id_manager = ".$manager." ";
        }


        if ($genero && $genero!="NULL") {
            $sql .= " AND actores.id_genero = ".$genero;
            $genero = $this->model_casting->genero_id($genero);
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Género</label>
                                    <span>'.$genero[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }


        /*VALIDACINDES DE ALTURA*/
        if ($altura_desde!="" AND $altura_hasta!="" && $altura_desde!="NULL" && $altura_hasta!="NULL") {
            $sql .= " AND (actores.altura BETWEEN ".$altura_desde." AND ".$altura_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_desde.' - '.$altura_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($altura_desde!="" AND $altura_hasta=="" && $altura_desde!="NULL" && $altura_hasta!="NULL") {
            $sql .= " AND actores.altura >= ".$altura_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($altura_hasta!="" AND $altura_desde=="" && $altura_desde!="NULL" && $altura_hasta!="NULL") {
            $sql .= " AND actores.altura <= ".$altura_hasta;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        /*VALIDACIONES DE PESO*/
        if ($peso_desde!="" AND $peso_hasta!="" AND $peso_desde!="NULL" AND $peso_hasta!="NULL") {
            $sql .= " AND (actores.altura BETWEEN ".$altura_desde." AND ".$altura_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Peso</label>
                                    <span>'.$peso_desde.' - '.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($peso_desde!="" AND $peso_hasta=="" AND $peso_desde!="NULL" AND $peso_hasta!="NULL") {
            $sql .= " AND actores.peso >= ".$peso_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Peso</label>
                                    <span>'.$peso_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';

        }

        if ($peso_hasta!="" AND $peso_desde=="" AND $peso_desde!="NULL" AND $peso_hasta!="NULL") {
            $sql .= " AND actores.peso <= ".$peso_hasta;
            $resumen_busqueda .='<div class="column three">
                        <label for="">Peso</label>
                        <span>'.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        // VALIDACIONES edad_hasta
        if ($edad_desde!="" AND $edad_hasta!="" AND $edad_desde!="NULL" AND $edad_hasta!="NULL") {
            $sql .= " AND (TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) BETWEEN ".$edad_desde." AND ".$edad_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">edad</label>
                                    <span>'.$edad_desde.' - '.$edad_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($edad_desde!="" AND $edad_hasta=="" AND $edad_desde!="NULL" AND $edad_hasta!="NULL") {
            $sql .= " AND TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) >= ".$edad_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">edad</label>
                                    <span>'.$edad_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';

        }

        if ($edad_hasta!="" AND $edad_desde=="" AND $edad_desde!="NULL" AND $edad_hasta!="NULL") {
            $sql .= " AND TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) <= ".$edad_hasta;
            $resumen_busqueda .='<div class="column three">
                        <label for="">Peso</label>
                        <span>'.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }


        if ($color_tez AND $color_tez!="NULL") {
            $sql .= " AND actores.id_color_tez = ".$color_tez;
            $color_tez  = $this->model_casting->color_tez_id($color_tez);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Color tez</label>
                        <span>'.$color_tez[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        if ($color_ojos AND $color_ojos!="NULL") {
            $sql .= " AND actores.id_color_ojos = ".$color_ojos;
            $color_ojos  = $this->model_casting->color_ojos_id($color_ojos);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Color ojos</label>
                        <span>'.$color_ojos[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        if ($idioma AND $idioma!="NULL") {
            $sql .= " AND idiomas_actor.id_idioma = ".$idioma;
            $sql_inner.=" INNER JOIN idiomas_actor ON idiomas_actor.id_actor = actores.id ";
            $idioma  = $this->model_casting->idioma_id($idioma);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Idioma</label>
                        <span>'.$idioma[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        if ($proyectos_desempenados AND $proyectos_desempenados!="NULL") {

            $sql .= " AND UPPER(papeles_actor.nombre_proyecto) = UPPER('".$proyectos_desempenados."') ";
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $resumen_busqueda .='<div class="column three">
                        <label for="">Proyectos desempeñados</label>
                        <span>'.$proyectos_desempenados.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        if ($personajes_desempenados AND $personajes_desempenados!="NULL") {
            $sql .= " AND UPPER(papeles_actor.nombre_personaje) = UPPER('".$personajes_desempenados."') ";
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $resumen_busqueda .='<div class="column three">
                        <label for="">Personajes desempeñados</label>
                        <span>'.$proyectos_desempenados.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        if ($rol AND $rol!="NULL") {
            $sql .= " AND papeles_actor.id_rol = ".$rol;
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $rol = $this->model_casting->rol_id($rol);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Roles desempeñados</label>
                        <span>'.$rol[0]->rol.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }
        if($disponible AND $disponible!="NULL"){
            $sql .= " AND actores.disponible = ".$disponible;
            if($disponible==1){
                $resumen_busqueda .='<div class="column three">
                        <label for="">Disponible</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
            }else{
                $resumen_busqueda .='<div class="column three">
                        <label for="">Disponible</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
            }
        }

        if($fotos AND $fotos!="NULL"){
            if($fotos==1){
                $sql_inner.=" INNER JOIN fotos_actor ON fotos_actor.id_actor = actores.id ";
                $resumen_busqueda .='<div class="column three">
                        <label for="">Fotos</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }else{
                $sql .= " AND actores.id NOT IN (SELECT id_actor FROM fotos_actor WHERE fotos_actor.id_actor = actores.id) ";

                $resumen_busqueda .='<div class="column three">
                        <label for="">Fotos</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }
        }

        if($extranjero AND $extranjero!="NULL"){

            if($extranjero==1){
                $sql .= " AND actores.id_nacionalidad!=13";
                $resumen_busqueda .='<div class="column three">
                        <label for="">extranjero</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }else{
                $sql .= " AND actores.id_nacionalidad=13 ";
                $resumen_busqueda .='<div class="column three">
                        <label for="">extranjero</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }
        }

        

         $actores = $this->model_casting->filtro_actores($sql,$sql_inner);
            if(!$actores){
                $actores="";
            }
                }
        }else{
         $actores= $this->model_casting->filtro_actores_palabra($palabra);
        }
        ///////////////////////////////////////////

        $nacionalidades = $this->model_casting->nacionalidades();
        $tipos_documento = $this->model_elementos->tipos_documento();
        $generos = $this->model_casting->generos();
        $paises = $this->model_casting->paises();
        $colores_ojos = $this->model_casting->colores_ojos();
        $colores_tez = $this->model_casting->colores_tez();
        $idiomas = $this->model_casting->idiomas();
        $roles = $this->model_escenas->rol_actores_elementos_casting();
        $managers = $this->model_casting->get_managers();
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);
        $data['resumen_busqueda'] = $resumen_busqueda.'<div class="clr"></div>';
        $data['actores']=$actores;
        $data['produccion'] = false;
        $data['producciones'] = $producciones;
        $data['nacionalidades'] = $nacionalidades;
        $data['tipos_documento'] = $tipos_documento;
        $data['generos'] = $generos;
        $data['paises'] = $paises;
        $data['colores_tez'] = $colores_tez;
        $data['colores_ojos'] = $colores_ojos;
        $data['idiomas'] = $idiomas;
        $data['roles'] = $roles;
        $data['managers'] = $managers;
        $data['permisos'] = $permisos;
        $data['view']='casting/index';
        $this->load->view('includes/template',$data);   
    }


    public function anadir_actor(){
        $nacionalidades = $this->model_casting->nacionalidades();
        $tipos_documento = $this->model_elementos->tipos_documento();
        $generos = $this->model_casting->generos();
        $paises = $this->model_casting->paises();
        $colores_ojos = $this->model_casting->colores_ojos();
        $colores_tez = $this->model_casting->colores_tez();
        $idiomas = $this->model_casting->idiomas();
        $roles = $this->model_escenas->rol_actores_elementos_casting();
        $managers = $this->model_casting->get_managers();
        $tipos_documentacion= $this->model_casting->get_tipos_documentacion();
        $sociedades = $this->model_casting->get_sociedades();
        $data['sociedades'] = $sociedades;
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();
        $data['tipos_documentacion'] = $tipos_documentacion;
        $data['nacionalidades'] = $nacionalidades;
        $data['managers'] = $managers;
        $data['tipos_documento'] = $tipos_documento;
        $data['generos'] = $generos;
        $data['paises'] = $paises;
        $data['colores_tez'] = $colores_tez;
        $data['colores_ojos'] = $colores_ojos;
        $data['idiomas'] = $idiomas;
        $data['roles'] = $roles;
        $data['view']='casting/anadir_actor';
        $this->load->view('includes/template',$data);   
    }
     /*FUNCION CARGAR CIUDADES SEGUN PAIS*/
    public function insert_actor(){
        $this->form_validation->set_rules('nombre_actor','Nombre','required');
        $this->form_validation->set_rules('apellido_actor','Apellido','required');
        $this->form_validation->set_rules('nacionalidad','Nacionalidad','required');
        $this->form_validation->set_rules('documento','Documento','required');
        $this->form_validation->set_rules('fecha_nacimiento','Fecha nacimiento','required');
        $this->form_validation->set_rules('telefono_movil','Telefono movil');
        $this->form_validation->set_rules('direccion','Direccion');
        $this->form_validation->set_rules('pais','Pais');
        $this->form_validation->set_rules('ciudad','Ciudad');
        $this->form_validation->set_message('required','%s es requerido');
        if ($this->form_validation->run()==FALSE) {
            $this->anadir_actor($this->input->post('idproduccion'));
        }else{

            if((!$this->input->post('id_manager') OR $this->input->post('id_manager')=='0') 
                AND $this->input->post('manager_validation')==1){
                $dataManager = array( 
                    'nombre' => $this->input->post('nombre_manager'), 
                    'telefono_fijo' => $this->input->post('telefono_fijo_manager'), 
                    'telefono_movil' => $this->input->post('telefono_movil_manager'), 
                    'email' => $this->input->post('email_manager')
                );
                $this->model_casting->anadir_manager($dataManager);
                $id_manager = mysql_insert_id();
            }else{
                if($this->input->post('id_manager')!="" AND  $this->input->post('manager_validation')==1){
                    $id_manager = $this->input->post('id_manager');
                    $dataManager = array( 
                        'id'=>$this->input->post('id_manager'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_manager'), 
                        'telefono_movil' => $this->input->post('telefono_movil_manager'), 
                        'email' => $this->input->post('email_manager')
                    );
                    $this->model_casting->update_manager($dataManager);
                }else{
                    $id_manager = null;
                }
            }


            $disponible=1;
            $terminacion_proyecto = null;

            if ($this->input->post('disponible')==2 AND $this->input->post('disponible')) {
                $disponible = 2;
                $terminacion_proyecto = date("Y-m-d",strtotime($this->input->post('terminacion_proyecto')));
            }

            $visa_numero  = "";
            $visa_url  = "";
            $visa_pais  = "";
            if($this->input->post('ciudad')){
                $datos=array(
                'nombre'=>$this->input->post('nombre_actor'),
                'apellido'=>$this->input->post('apellido_actor'),
                'id_nacionalidad'=>$this->input->post('nacionalidad'),
                'documento'=>$this->input->post('documento'),
                'id_tipo_documento'=>$this->input->post('tipo_documento'),
                'fecha_nacimiento'=>$this->fechaFormat($this->input->post('fecha_nacimiento')),
                'telefono_fijo'=>$this->input->post('telefono_fijo'),
                'telefono_movil'=>$this->input->post('telefono_movil'),
                'direccion'=>$this->input->post('direccion'),
                'ciudad'=>$this->input->post('ciudad'),
                'pais'=>$this->input->post('pais'),
                'email'=>$this->input->post('email'),

                'id_genero'=>$this->input->post('genero'),
                'altura'=>$this->input->post('altura'),
                'peso'=>$this->input->post('peso'),
                'id_color_tez'=>$this->input->post('color_tez'),
                'id_color_ojos'=>$this->input->post('color_ojos'),
                'otros_idiomas'=>$this->input->post('otro_idioma'),
                'pasaporte'=>$this->input->post('pasaporte'),
                'contacto_nombre'=>$this->input->post('contacto_nombre'),
                'contacto_telefono'=>$this->input->post('contacto_telefono'),
                'contacto_telefono_movil'=>$this->input->post('contacto_telefono_movil'),
                'contacto_email'=>$this->input->post('contacto_email'),
                'disponible'=>$disponible,
                'terminacion_proyecto'=>$terminacion_proyecto,
                'id_rol'=>$this->input->post('rol'),
                'contactar'=>$this->input->post('contactar'),
                'notas_actor'=>$this->input->post('notas_actor'),
                'id_tipo_documento_representante_legal'=>$this->input->post('id_tipo_documento_representante_legal'),
                'documento_representante_legal'=>$this->input->post('documento_representante_legal'),
                'nombre_representante_legal'=>$this->input->post('nombre_representante_legal'),
                'telefono_fijo_representante'=>$this->input->post('telefono_fijo_representante'),
                'celular_representante'=>$this->input->post('celular_representante'),
                'direccion_representante'=>$this->input->post('direccion_representante'),
                'correo_representante'=>$this->input->post('correo_representante'),
              );
            }else{
                 $datos=array(
                'nombre'=>$this->input->post('nombre_actor'),
                'apellido'=>$this->input->post('apellido_actor'),
                'id_nacionalidad'=>$this->input->post('nacionalidad'),
                'documento'=>$this->input->post('documento'),
                'id_tipo_documento'=>$this->input->post('tipo_documento'),
                'fecha_nacimiento'=>$this->fechaFormat($this->input->post('fecha_nacimiento')),
                'telefono_fijo'=>$this->input->post('telefono_fijo'),
                'telefono_movil'=>$this->input->post('telefono_movil'),
                'direccion'=>$this->input->post('direccion'),
                'email'=>$this->input->post('email'),

                'id_genero'=>$this->input->post('genero'),
                'altura'=>$this->input->post('altura'),
                'peso'=>$this->input->post('peso'),
                'id_color_tez'=>$this->input->post('color_tez'),
                'id_color_ojos'=>$this->input->post('color_ojos'),
                'otros_idiomas'=>$this->input->post('otro_idioma'),
                'pasaporte'=>$this->input->post('pasaporte'),
                'contacto_nombre'=>$this->input->post('contacto_nombre'),
                'contacto_telefono'=>$this->input->post('contacto_telefono'),
                'contacto_telefono_movil'=>$this->input->post('contacto_telefono_movil'),
                'contacto_email'=>$this->input->post('contacto_email'),
                'disponible'=>$disponible,
                'terminacion_proyecto'=>$terminacion_proyecto,
                'id_rol'=>$this->input->post('rol'),
                'contactar'=>$this->input->post('contactar'),
                'notas_actor'=>$this->input->post('notas_actor'),
                'nombre_representante_legal'=>$this->input->post('nombre_representante_legal'),
                'id_tipo_documento_representante_legal'=>$this->input->post('id_tipo_documento_representante_legal'),
                'documento_representante_legal'=>$this->input->post('documento_representante_legal'),
                'telefono_fijo_representante'=>$this->input->post('telefono_fijo_representante'),
                'celular_representante'=>$this->input->post('celular_representante'),
                'direccion_representante'=>$this->input->post('direccion_representante'),
                'correo_representante'=>$this->input->post('correo_representante'),
                 );

            }
           

            if ($id_manager) {
                $datos['id_manager'] = $id_manager;
            }

            

            if($this->input->post('society_validation')==1){

                $razon_social = $this->input->post('razon_social');
                if ($razon_social==0) {
                    $dataSociedad = array(
                        'nombre' =>  $this->input->post('nombre_sociedad'),
                        'representante_legal' =>  $this->input->post('representante_legal'),
                        'nit' => $this->input->post('nit_sociedad'),
                        'representante_legal' => $this->input->post('representante_legal'),
                        'documento_representante' => $this->input->post('documento_representante'),
                        'direccion' => $this->input->post('direccion_sociedad'), 
                        'email' => $this->input->post('email_sociedad'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_sociedad'),
                        'telefono_movil' => $this->input->post('telefono_movil_sociedad'),
                        'ciudad' => $this->input->post('ciudad_sociedad'),
                        'pais' =>  $this->input->post('pais_sociedad') 
                    );
                    $this->model_casting->insert_sociedad($dataSociedad);
                    $id_sociedad = mysql_insert_id();
                }else{
                    $id_sociedad = $razon_social;
                    $dataSociedad = array(
                        'id' => $razon_social,
                        'representante_legal' =>  $this->input->post('representante_legal'),
                        'nit' => $this->input->post('nit_sociedad'),
                        'direccion' => $this->input->post('direccion_sociedad'), 
                        'email' => $this->input->post('email_sociedad'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_sociedad'),
                        'telefono_movil' => $this->input->post('telefono_movil_sociedad'),
                        'ciudad' => $this->input->post('ciudad_sociedad'),
                        'pais' =>  $this->input->post('pais_sociedad'),
                        'documento_representante' => $this->input->post('representante_documento'),
                    );
                     $this->model_casting->update_sociedad($dataSociedad);
                }
                $datos['id_sociedad'] = $id_sociedad;
            }
            $this->model_casting->insert_actor($datos);
            $idactor = mysql_insert_id();

            // INSERCION DE DOCUMENTOS
            
            // DOCUMENTO IDENTIDAD
            $documento_identidad = $this->input->post('documento_identidad');
            if($_FILES['documento_identidad']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['documento_identidad']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_DOCUMENTO_IDENTIDAD'.$this->extension_archivo($_FILES['documento_identidad']["name"]);
                $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>1,
                    'url'=>$rutaDestino,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
                
            }else{
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>1,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
            }
            $this->model_casting->insert_documentos_actor($data);



            /////////registro civil///////////////
            // DOCUMENTO IDENTIDAD
            $documento_identidad = $this->input->post('registro_civil');
            if($_FILES['registro_civil']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['documento_identidad']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_registro_civil'.$this->extension_archivo($_FILES['registro_civil']["name"]);
                $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>8,
                    'url'=>$rutaDestino,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
                
            }else{
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>8,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
            }
            $this->model_casting->insert_documentos_actor($data);
            // FIN DOCUMENTO IDENTIDAD

            // ARL
            $arl_nombre =$this->input->post('arl_nombre');
            $arl_documento='';
            $arl_activo=$this->input->post('arl_activo');
            $arl_clase=$this->input->post('arl_clase');
            if($_FILES['arl_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_ARL'.$this->extension_archivo($_FILES['arl_documento']["name"]);
                $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }
            if(!$this->input->post('arl_activo')){
              $arl_activo=null;
            }
            if(!$this->input->post('arl_clase')){
              $arl_clase=null;
            }
            $data=array(
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>2,
                'url'=>$arl_documento,
                'descripcion'=>$arl_nombre,
                'id_clase_entidad'=>$arl_activo,
                'id_estado_entidad'=>$arl_clase
            );
            $this->model_casting->insert_documentos_actor($data);
            // FIN ARL

            // EPS
            $eps_nombre = $this->input->post('eps_nombre');
            $eps_activo = $this->input->post('eps_activo');
            $eps_clase = $this->input->post('eps_clase');
            $eps_documento='';
            if($_FILES['eps_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_EPS'.$this->extension_archivo($_FILES['eps_documento']["name"]);
                $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }
            if(!$eps_activo){
              $eps_activo=null;
            }
            if(!$eps_clase){
              $eps_clase=null;
            }

            $data=array(
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>3,
                'url'=>$eps_documento,
                'descripcion'=>$eps_nombre,
                'id_clase_entidad'=>$eps_activo,
                'id_estado_entidad'=>$eps_clase
            );
            $this->model_casting->insert_documentos_actor($data);
            // FIN EPS

            // FONDO PENSIONES
            $pensiones_nombre = $this->input->post('pensiones_nombre');
            $pensiones_activo = $this->input->post('pensiones_activo');
            $pensiones_clase = $this->input->post('pensiones_clase');
            $pensiones_documento ='';

            if($_FILES['pensiones_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']["name"]);
                $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }
            if(!$pensiones_activo){
              $pensiones_activo=null;
            }
            if(!$pensiones_clase){
              $pensiones_clase=null;
            }
            $data=array(
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>4,
                'url'=>$pensiones_documento,
                'descripcion'=>$pensiones_nombre,
                'id_clase_entidad'=>$pensiones_activo,
                'id_estado_entidad'=>$pensiones_clase
            );
            $this->model_casting->insert_documentos_actor($data);
            // FIN FONDO PENSIONES

            // PASAPORTE
            $pasaporte_numero = $this->input->post('pasaporte_numero');
            $pasaporte_pais = $this->input->post('pasaporte_pais');
            $pasaporte_documento = '';

            if ($pasaporte_numero) {
               // for ($pa=0; $pa < count($pasaporte_numero); $vs++) { 
                    $pasaporte_documento = "";
                    if($_FILES['pasaporte_documento']["tmp_name"]){
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['pasaporte']["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_PASAPORTE_'.$this->extension_archivo($_FILES['pasaporte']["name"]);
                        $pasaporte_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino); 
                    }
                    
                    $data=array(
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>5,
                        'url'=>$pasaporte_documento,
                        'descripcion'=>$pasaporte_numero,
                        'pais' => $pasaporte_pais, 
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null
                    );
                    $this->model_casting->insert_documentos_actor($data);
                //}
            }
            //FIN PASAPORTE

            // VISA 
            $visa_numero  = $this->input->post('visa_numero');
            $visa_pais = $this->input->post('visa_pais');
            $visa_vigencia = $this->input->post('visa_vigencia');
    
            //$_FILES['visa_documento'] = var_dump($_FILES['visa_documento'] );
            if ($visa_numero) {
                //for ($vs=0; $vs < count($visa_numero); $vs++) { 
                    $val = false;
                    $visa_documento='';
                    //if($_FILES['visa_documento']['error'][0]==0){
                        if($_FILES['visa_documento']["tmp_name"]){
                            $rutaServidor="images/documentos_actor";
                            $rutaTemporal= $_FILES['visa_documento']["tmp_name"];
                            $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_VISA'.'_.'.$this->extension_archivo($_FILES['visa_documento']["name"]);
                            $visa_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                            move_uploaded_file($rutaTemporal, $rutaDestino); 
                            $val = true;
                        }
                    //}

                    $visa_numero_temp="";
                    if($visa_numero){
                        $visa_numero_temp =  $visa_numero;
                        $val = true;
                    }

                    $visa_pais_temp="";
                    if($visa_pais){
                        $visa_pais_temp =  $visa_pais;
                        $val = true;
                    }

                    $visa_vigencia_temp="";
                    if($visa_vigencia){
                        $visa_vigencia_temp = $this->fechaFormat($visa_vigencia);
                        $val = true;
                    }

                    if($val){
                        $data=array(
                            'id_actor'=>$idactor,
                            'id_tipo_documentacion'=>6,
                            'url'=>$visa_documento,
                            'descripcion'=>$visa_numero_temp,
                            'pais'=>$visa_pais_temp,
                            'id_clase_entidad'=>null,
                            'id_estado_entidad'=>null,
                            'vigencia'=> $visa_vigencia_temp
                        );
                        $this->model_casting->insert_documentos_actor($data);
                    }
                    
                //}
            }

            
            // FIN VISA
       
            //OTROS
            $otro_numero  = $this->input->post('otro_numero');
            if ($otro_numero) {
                //for ($v=0; $v < count($otro_numero); $v++) { 
                    $val = false;
                    $otro_documento='';
                    //if($_FILES['otro_documento']['error'][0]==0){
                        if($_FILES['otro_documento']["tmp_name"]){
                            $val = true;
                            $rutaServidor="images/documentos_actor";
                            $rutaTemporal= $_FILES['otro_documento']["tmp_name"];
                            $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_OTROS'.'_'.$this->extension_archivo($_FILES['otro_documento']["name"]);
                            $otro_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                            move_uploaded_file($rutaTemporal, $rutaDestino); 
                        }
                   /* }else{
                        "error";
                    }*/
                    $otro_numero_tmp = "";
                    if($otro_numero){
                        $val = true;
                        $otro_numero_tmp = $otro_numero;
                    }
                    if($val){
                        $data=array(
                            'id_actor'=>$idactor,
                            'id_tipo_documentacion'=>7,
                            'url'=>$otro_documento,
                            'descripcion'=>$otro_numero_tmp,
                            'id_clase_entidad'=>null,
                            'id_estado_entidad'=>null
                        );
                        $this->model_casting->insert_documentos_actor($data);
                    }
                    
               // }
            }
            //FIN OTROS

            // FIN INSERCION DE DOCUMENTOS

            //LINKS VIDEOS

            $links_videos = $this->input->post('links_videos');
            if($links_videos AND $links_videos!=""){
                for ($i=0; $i < count($links_videos); $i++) { 
                    $dataVideo = array(
                        'id_actor' => $idactor, 
                        'url' => $links_videos[$i]
                    );
                    $this->model_casting->insertVideoActor($dataVideo);
                }

            }

            /*INSERCION FOTOS ACTOR*/
            if($_FILES['foto_actor']){
                $fotos = $this->input->post('foto_actor'); 
                for ($i=0; $i < 4; $i++) { 
                    $rutaDestino = "";
                    if($_FILES['foto_actor']["tmp_name"]){
                        if($_FILES['foto_actor']["tmp_name"][$i]){
                            $rutaServidor="images/casting";
                            $rutaTemporal= $_FILES['foto_actor']["tmp_name"][$i];
                            $nombreimage= date('d_m_y_h_m_s').'_'.$i.'_'.$idactor.'.jpg';
                            $rutaDestino= $rutaServidor.'/'.$nombreimage;
                            move_uploaded_file($rutaTemporal, $rutaDestino); 
                        }
                    }
                    $data = array(
                        'id_actor' => $idactor,
                        'ruta_foto' => $rutaDestino 
                    );
                    $this->model_casting->insert_foto($data);
                }
            }
            /*INSERCION FOTOS ACTOR*/

            if($this->input->post('idiomas')){
                $idiomas = $this->input->post('idiomas');
                foreach ($idiomas as $idioma) {
                    $data = array(
                        'id_actor' => $idactor,
                        'id_idioma' => $idioma
                    );
                    $this->model_casting->insert_idioma($data);
                }
            }

            if($this->input->post('produccion_actor')){
                $producciones = $this->input->post('produccion_actor');
                $roles = $this->input->post('rol_actor');
                $personajes = $this->input->post('personaje_actor');
                $anos = $this->input->post('ano_actor');
                for ($i=1; $i <count($producciones); $i++) { 
                    if($roles[$i]){
                        $data = array(
                            'id_actor' => $idactor,
                            'nombre_proyecto' => $producciones[$i],
                            'nombre_personaje' => $personajes[$i],
                            'id_rol' => $roles[$i],
                            'ano' => $anos[$i],
                        );
                        $this->model_casting->insert_papel_actor($data);
                    }
                }
            }
            redirect($this->lang->lang().'/casting/detalle_actor/'.$idactor.'/1');
        }
    }

    /*FUNCION EDITAR ACTOR*/
    public function editar_actor($idactor){
        $actor = $this->model_casting->actor_id($idactor);
        $nacionalidades = $this->model_casting->nacionalidades();
        $tipos_documento = $this->model_elementos->tipos_documento();
        $generos = $this->model_casting->generos();
        $paises = $this->model_casting->paises();
        $paises_visa = $this->model_casting->paises_visa($idactor);
        $paises_pasaporte = $this->model_casting->paises_visa($idactor,5);
        $colores_ojos = $this->model_casting->colores_ojos();
        $colores_tez = $this->model_casting->colores_tez();
        $idiomas = $this->model_casting->idiomas();
        $roles = $this->model_escenas->rol_actores_elementos_casting();
        $managers = $this->model_casting->get_managers();
        $videos_actor = $this->model_casting->videos_actor($idactor);
        $proyectos_actor = $this->model_casting->proyectos_actor($idactor);
        $fotos_actor = $this->model_casting->fotos_actor($idactor); 
        $tipos_documentacion= $this->model_casting->get_tipos_documentacion();
        $documentos_actor=$this->model_casting->documentos_actor($idactor);
        $visas_actor=$this->model_casting->otros_documentos_actor($idactor,6);
        $pasaportes_actor=$this->model_casting->otros_documentos_actor($idactor,5);
        $otros_documentos_actor=$this->model_casting->otros_documentos_actor($idactor,7);
        $contactos=$this->model_casting->get_contactos();


        $ciudades=$this->model_casting->ciudades_pais($actor['0']->pais);
        $sociedades = $this->model_casting->get_sociedades();
        $data['sociedades'] = $sociedades;
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();
        $data['documentos_actor'] = $documentos_actor;
        $data['pasaportes_actor'] = $pasaportes_actor;
        $data['visas_actor'] = $visas_actor;
        $data['otros_documentos_actor'] = $otros_documentos_actor;
        $data['tipos_documentacion'] = $tipos_documentacion;
        $data['contactos'] = $contactos;
        $data['fotos_actor'] = $fotos_actor;
        $data['proyectos_actor'] = $proyectos_actor;
        $data['videos_actor'] = $videos_actor;
        $data['nacionalidades'] = $nacionalidades;
        $data['managers'] = $managers;
        $data['tipos_documento'] = $tipos_documento;
        $data['generos'] = $generos;
        $data['paises'] = $paises;
        $data['paises_visa'] = $paises_visa;
        $data['paises_pasaporte'] = $paises_pasaporte;
        $data['ciudades'] = $ciudades;
        $data['colores_tez'] = $colores_tez;
        $data['colores_ojos'] = $colores_ojos;
        $data['idiomas'] = $idiomas;
        $data['roles'] = $roles;
        $data['actor'] = $actor;
        $data['view']='casting/editar_actor';
        $this->load->view('includes/template',$data);
    }
    /*FUNCION EDITAR ACTOR*/

    public function update_actor(){
        $this->form_validation->set_rules('nombre_actor','Nombre','required');
        $this->form_validation->set_rules('apellido_actor','Apellido','required');
        $this->form_validation->set_rules('nacionalidad','Nacionalidad','required');
        $this->form_validation->set_rules('documento','Documento','required');
        $this->form_validation->set_rules('fecha_nacimiento','Fecha nacimiento','required');
        $this->form_validation->set_rules('telefono_movil','Telefono movil');
        $this->form_validation->set_rules('direccion','Direccion');
        $this->form_validation->set_rules('pais','Pais');
        $this->form_validation->set_rules('ciudad','Ciudad');
        $this->form_validation->set_message('required','%s es requerido');
        $idactor=$this->input->post('id_actor');
        if ($this->form_validation->run()==FALSE) {
            $this->editar_actor($this->input->post('id_actor'));
        }else{
            $id_manager="";

            if((!$this->input->post('id_manager') OR $this->input->post('id_manager')=='0') 
                AND $this->input->post('manager_validation')==1){
                $dataManager = array( 
                    'nombre' => $this->input->post('nombre_manager'), 
                    'telefono_fijo' => $this->input->post('telefono_fijo_manager'), 
                    'telefono_movil' => $this->input->post('telefono_movil_manager'), 
                    'email' => $this->input->post('email_manager')
                );
                $this->model_casting->anadir_manager($dataManager);
                $id_manager = mysql_insert_id();
            }else{
                if($this->input->post('id_manager')!="" AND  $this->input->post('manager_validation')==1){
                    $id_manager = $this->input->post('id_manager');
                    $dataManager = array( 
                        'id'=>$this->input->post('id_manager'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_manager'), 
                        'telefono_movil' => $this->input->post('telefono_movil_manager'), 
                        'email' => $this->input->post('email_manager')
                    );
                    $this->model_casting->update_manager($dataManager);
                }else{
                    $id_manager = null;
                }
            }

            $disponible=1;
            $terminacion_proyecto = null;

            if ($this->input->post('disponible')==2 AND $this->input->post('disponible')) {
                $disponible = 2;
                $terminacion_proyecto = $this->fechaFormat($this->input->post('terminacion_proyecto'));
            }
          
        
            if($this->input->post('tipo_documento')==2){
                $nombre_representante_legal=$this->input->post('nombre_representante_legal');
                $telefono_fijo_representante=$this->input->post('telefono_fijo_representante');
                $celular_representante=$this->input->post('celular_representante');
                $direccion_representante=$this->input->post('direccion_representante');
                $correo_representante=$this->input->post('correo_representante');
                $id_tipo_documento_representante_legal=$this->input->post('id_tipo_documento_representante_legal');
                $documento_representante_legal=$this->input->post('documento_representante_legal');
            }else{
                $nombre_representante_legal=null;
                $telefono_fijo_representante=null;
                $celular_representante=null;
                $direccion_representante=null;
                $correo_representante=null;
                $id_tipo_documento_representante_legal=null;
                $documento_representante_legal=null;
            }
                

           
           if($this->input->post('ciudad')){
              $datos=array(
                'id'=>$idactor,
                'nombre'=>$this->input->post('nombre_actor'),
                'apellido'=>$this->input->post('apellido_actor'),
                'id_nacionalidad'=>$this->input->post('nacionalidad'),
                'documento'=>$this->input->post('documento'),
                'id_tipo_documento'=>$this->input->post('tipo_documento'),
                'fecha_nacimiento'=>$this->fechaFormat($this->input->post('fecha_nacimiento')),
                'telefono_fijo'=>$this->input->post('telefono_fijo'),
                'telefono_movil'=>$this->input->post('telefono_movil'),
                'direccion'=>$this->input->post('direccion'),
                'ciudad'=>$this->input->post('ciudad'),
                'pais'=>$this->input->post('pais'),
                'email'=>$this->input->post('email'),
                'id_manager'=>$id_manager,
                'id_genero'=>$this->input->post('genero'),
                'altura'=>$this->input->post('altura'),
                'peso'=>$this->input->post('peso'),
                'id_color_tez'=>$this->input->post('color_tez'),
                'id_color_ojos'=>$this->input->post('color_ojos'),
                'otros_idiomas'=>$this->input->post('otro_idioma'),
                'pasaporte'=>$this->input->post('pasaporte'),
                'contacto_nombre'=>$this->input->post('contacto_nombre'),
                'contacto_telefono'=>$this->input->post('contacto_telefono'),
                'contacto_telefono_movil'=>$this->input->post('contacto_telefono_movil'),
                'contacto_email'=>$this->input->post('contacto_email'),
                'disponible'=>$disponible,
                'terminacion_proyecto'=>$terminacion_proyecto,
                'id_rol'=>$this->input->post('rol'),
                'contactar'=>$this->input->post('contactar'),
                'notas_actor'=>$this->input->post('notas_actor'),
                'nombre_representante_legal'=>$nombre_representante_legal,
                'id_tipo_documento_representante_legal'=>$id_tipo_documento_representante_legal,
                'documento_representante_legal'=>$documento_representante_legal,
                'telefono_fijo_representante'=>$telefono_fijo_representante,
                'celular_representante'=>$celular_representante,
                'direccion_representante'=>$direccion_representante,
                'correo_representante'=>$correo_representante,
            );

           }else{
               $datos=array(
                'id'=>$idactor,
                'nombre'=>$this->input->post('nombre_actor'),
                'apellido'=>$this->input->post('apellido_actor'),
                'id_nacionalidad'=>$this->input->post('nacionalidad'),
                'documento'=>$this->input->post('documento'),
                'id_tipo_documento'=>$this->input->post('tipo_documento'),
                'fecha_nacimiento'=>$this->fechaFormat($this->input->post('fecha_nacimiento')),
                'telefono_fijo'=>$this->input->post('telefono_fijo'),
                'telefono_movil'=>$this->input->post('telefono_movil'),
                'direccion'=>$this->input->post('direccion'),
                'email'=>$this->input->post('email'),
                'id_manager'=>$id_manager,
                'id_genero'=>$this->input->post('genero'),
                'altura'=>$this->input->post('altura'),
                'peso'=>$this->input->post('peso'),
                'id_color_tez'=>$this->input->post('color_tez'),
                'id_color_ojos'=>$this->input->post('color_ojos'),
                'otros_idiomas'=>$this->input->post('otro_idioma'),
                'pasaporte'=>$this->input->post('pasaporte'),
                'contacto_nombre'=>$this->input->post('contacto_nombre'),
                'contacto_telefono'=>$this->input->post('contacto_telefono'),
                'contacto_telefono_movil'=>$this->input->post('contacto_telefono_movil'),
                'contacto_email'=>$this->input->post('contacto_email'),
                'disponible'=>$disponible,
                'terminacion_proyecto'=>$terminacion_proyecto,
                'id_rol'=>$this->input->post('rol'),
                'contactar'=>$this->input->post('contactar'),
                'notas_actor'=>$this->input->post('notas_actor'),
                'nombre_representante_legal'=>$nombre_representante_legal,
                'id_tipo_documento_representante_legal'=>$id_tipo_documento_representante_legal,
                'documento_representante_legal'=>$documento_representante_legal,
                'telefono_fijo_representante'=>$telefono_fijo_representante,
                'celular_representante'=>$celular_representante,
                'direccion_representante'=>$direccion_representante,
                'correo_representante'=>$correo_representante,
            );
           }
            


            if($this->input->post('society_validation')==1){

                $razon_social = $this->input->post('razon_social');
                if ($razon_social==0) {
                    $dataSociedad = array(
                        'nombre' =>  $this->input->post('nombre_sociedad'),
                        'nit' => $this->input->post('nit_sociedad'),
                        'direccion' => $this->input->post('direccion_sociedad'), 
                       
                        'email' => $this->input->post('email_sociedad'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_sociedad'),
                        'telefono_movil' => $this->input->post('telefono_movil_sociedad'),
                        'ciudad' => $this->input->post('ciudad_sociedad'),
                        'pais' =>  $this->input->post('pais_sociedad'),
                         'representante_legal' => $this->input->post('representante_legal'), 
                        'documento_representante' => $this->input->post('representante_documento'),
                    );
                    $this->model_casting->insert_sociedad($dataSociedad);
                    $id_sociedad = mysql_insert_id();
                }else{
                    $id_sociedad = $razon_social;
                    $dataSociedad = array(
                        'id' => $razon_social,
                        'representante_legal' =>  $this->input->post('representante_legal'),
                        'nit' => $this->input->post('nit_sociedad'),
                        'direccion' => $this->input->post('direccion_sociedad'), 
                        'email' => $this->input->post('email_sociedad'),
                        'telefono_fijo' => $this->input->post('telefono_fijo_sociedad'),
                        'telefono_movil' => $this->input->post('telefono_movil_sociedad'),
                        'ciudad' => $this->input->post('ciudad_sociedad'),
                        'pais' =>  $this->input->post('pais_sociedad'),
                        'representante_legal' => $this->input->post('representante_legal'), 
                        'documento_representante' => $this->input->post('representante_documento'),
                    );
                     $this->model_casting->update_sociedad($dataSociedad);
                }
                $datos['id_sociedad'] = $id_sociedad;
            }else{
                $datos['id_sociedad'] = null;
            }

            $this->model_casting->update_actor($datos);

            ////////ELIMINAR DOCUMENTOS CREADOR//////
                  $documentos_eliminar=$this->input->post('documentos_eliminar');
                    if($documentos_eliminar){
                        foreach ($documentos_eliminar as $d) {
                            if($d){
                              $this->model_casting->eliminar_documentos($d);    
                            }
                        }
                    }
            /////////////////////////////////////////

            // DOCUMENTO IDENTIDAD
            $documento_identidad = $this->input->post('documento_identidad');
            $id_documento = $this->input->post('id_documento');
        
            if ($id_documento) {
                $rutaDestino="";
                if($_FILES['documento_identidad']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['documento_identidad']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_DOCUMENTO_IDENTIDAD'.$this->extension_archivo($_FILES['documento_identidad']['name']);
                    $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                    $data=array(
                        'id'=>$id_documento,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>1,
                        'url'=>$rutaDestino,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null
                    );
                    $this->model_casting->update_documentos_actor($data);
                }
            }else{
                $data=array(
                    'id'=>$id_documento,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>1,
                    'url'=>null,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null,
                    'pais'=>null
                );
                $this->model_casting->update_documentos_actor($data);
                
            }
            
            $documentos_actor = $this->model_casting->documentos_actor($idactor);

            // ARL
            $arl_activo = $this->input->post('arl_activo');
            $arl_clase = $this->input->post('arl_clase');
            $arl_nombre = $this->input->post('arl_nombre');
            $arl_documento='';
            $id_arl = $this->input->post('id_arl');
            if($id_arl){
                if($_FILES['arl_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_ARL'.$this->extension_archivo($_FILES['arl_documento']['name']);
                    $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $arl_documento = $documentos_actor[1]->url;
                }
                if(!$arl_activo){
                  $arl_activo=null;
                }
                if(!$arl_clase){
                  $arl_clase=null;
                }
                $data=array(
                    'id'=>$id_arl,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>2,
                    'url'=>$arl_documento,
                    'descripcion'=>$arl_nombre,
                    'id_clase_entidad'=>$arl_activo,
                    'id_estado_entidad'=>$arl_clase);
                $this->model_casting->update_documentos_actor($data);
            }else{
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>2,
                    'url'=>null,
                    'descripcion'=>null,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
                $this->model_casting->update_documentos_null($data);
            }


            // EPS
            $eps_nombre = $this->input->post('eps_nombre');
            $eps_activo = $this->input->post('eps_activo');
            $eps_clase = $this->input->post('eps_clase');
            $id_eps = $this->input->post('id_eps');
            $eps_documento='';
            
            if($id_eps){
                if($_FILES['eps_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_EPS'.$this->extension_archivo($_FILES['eps_documento']['name']);
                    $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $eps_documento = $documentos_actor[2]->url;
                }
                if(!$eps_activo){
                  $eps_activo=null;
                }
                if(!$eps_clase){
                  $eps_clase=null;
                }

                $data=array(
                    'id'=>$id_eps,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>3,
                    'url'=>$eps_documento,
                    'descripcion'=>$eps_nombre,
                    'id_clase_entidad'=>$eps_activo,
                    'id_estado_entidad'=>$eps_clase);
                $this->model_casting->update_documentos_actor($data);
            }else{
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>3,
                    'url'=>null,
                    'descripcion'=>null,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
                $this->model_casting->update_documentos_null($data);
            }
            

            // FONDO PENSIONES
            $pensiones_nombre = $this->input->post('pensiones_nombre');
            $pensiones_activo = $this->input->post('pensiones_activo');
            $pensiones_clase = $this->input->post('pensiones_clase');
            $pensiones_documento ='';
            $id_pensiones = $this->input->post('id_pensiones');

            if($id_pensiones){
                if($_FILES['pensiones_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']['name']);
                    $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $pensiones_documento = $documentos_actor[3]->url;
                }
                if(!$pensiones_activo){
                  $pensiones_activo=null;
                }
                if(!$pensiones_clase){
                  $pensiones_clase=null;
                }
                $data=array(
                    'id'=>$id_pensiones,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>4,
                    'url'=>$pensiones_documento,
                    'descripcion'=>$pensiones_nombre,
                    'id_clase_entidad'=>$pensiones_activo,
                    'id_estado_entidad'=>$pensiones_clase
                );
                $this->model_casting->update_documentos_actor($data);
            }else{
                $data=array(
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>4,
                    'url'=>null,
                    'descripcion'=>null,
                    'id_clase_entidad'=>null,
                    'id_estado_entidad'=>null
                );
                $this->model_casting->update_documentos_null($data);
            }
            

            // PASAPORTE
            $pasaporte_numero  = $this->input->post('pasaporte_numero_original');
            $pasaporte_pais = $this->input->post('pasaporte_pais_original');
            $pasaporte_documento='';
            $pasaporte_vigencia = $this->input->post('pasaporte_vigencia_original');
            $id_pasaporte = $this->input->post('id_pasaporte_original');
            $sql = " ( ";
            //PASAPORTES ANTERIORES
            if($pasaporte_numero){
                //for ($i=0; $i < count($pasaporte_numero); $i++) { 
                    $pasaporte_documento =  $this->input->post('pasaporte_documento_'.$id_pasaporte);
                    if ($_FILES['pasaporte_documento_'.$id_pasaporte]["tmp_name"]) {
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['pasaporte_documento_'.$id_pasaporte]["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_PASAPORTE'.$this->extension_archivo($_FILES['pasaporte_documento_'.$id_pasaporte]["name"]);
                        $pasaporte_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino);
                    }
                    
                    if($pasaporte_documento){
                         $data=array(
                        'id'=>$id_pasaporte,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>5,
                        'url'=>$pasaporte_documento,
                        'descripcion'=>$pasaporte_numero,
                        'pais'=>$pasaporte_pais,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia' =>$this->fechaFormat($pasaporte_vigencia)
                         );
                    }else{
                         $data=array(
                        'id'=>$id_pasaporte,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>5,
                        'descripcion'=>$pasaporte_numero,
                        'pais'=>$pasaporte_pais,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia' =>$this->fechaFormat($pasaporte_vigencia)
                         );
                    }
                    
                   // if ($i==0) {
                        $sql .= " documentos_actor.id !=".$id_pasaporte;
                    /*}else{
                        $sql .= " AND documentos_actor.id !=".$id_pasaporte;
                    }*/
                    $this->model_casting->update_documentos_actor($data); 
               // }
            }

            $sql .=" ) ";
            //ELIMINAR PASAPORTES
            if ($sql!=" (  ) ") {
                $this->model_casting->eliminar_documentos_actor($idactor,$sql,5);
            }
            

            //PASAPORTES NUEVAS
            $pasaporte_numero  = $this->input->post('pasaporte_numero');
            $pasaporte_pais = $this->input->post('pasaporte_pais');
            $pasaporte_vigencia = $this->input->post('pasaporte_vigencia');
        
            //for ($v=0; $v < count($pasaporte_numero) ; $v++) { 
                $val = false;
                $pasaporte_documento='';
                //if($_FILES['pasaporte_documento']['error'][0]==0){
                    if($_FILES['pasaporte_documento']["tmp_name"]){
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['pasaporte_documento']["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_pasaporte'.'_'.$this->extension_archivo($_FILES['pasaporte_documento']["name"]);
                        $pasaporte_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino); 
                        $val = true;
                    }
               // }

                $pasaporte_numero_temp="";
                if($pasaporte_numero){
                    $pasaporte_numero_temp =  $pasaporte_numero;
                    $val = true;
                }

                $pasaporte_pais_temp="";
                if($pasaporte_pais){
                    $pasaporte_pais_temp =  $pasaporte_pais;
                    $val = true;
                }

                $pasaporte_vigencia_temp="";
                if($pasaporte_vigencia){
                    $pasaporte_vigencia_temp =$this->fechaFormat($pasaporte_vigencia);
                    $val = true;
                }

                if($val){
                    $data=array(
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>5,
                        'url'=>$pasaporte_documento,
                        'descripcion'=>$pasaporte_numero_temp,
                        'pais'=>$pasaporte_pais_temp,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia'=>$pasaporte_vigencia_temp
                    );
                    $this->model_casting->insert_documentos_actor($data);
                }
            //}
            //FIN PASAPORTES

            // VISA 
            $visa_numero  = $this->input->post('visa_numero_original');
            $visa_pais = $this->input->post('visa_pais_original');
            $visa_documento='';
            $visa_vigencia = $this->input->post('visa_vigencia_original');
            $id_visa = $this->input->post('id_visa_original');
            $sql = " ( ";
            //VISAS ANTERIORES
            if($visa_numero){
                //for ($i=0; $i < count($visa_numero); $i++) { 
                    $visa_documento =  $this->input->post('visa_documento_original_'.$id_visa);
                    if ($_FILES['visa_documento_'.$id_visa]["tmp_name"]) {
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['visa_documento_'.$id_visa]["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_VISA'.$this->extension_archivo($_FILES['visa_documento_'.$id_visa]["name"]);
                        $visa_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino);
                    }

                    if($visa_documento){
                       $data=array(
                        'id'=>$id_visa,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>6,
                        'url'=>$visa_documento,
                        'descripcion'=>$visa_numero,
                        'pais'=>$visa_pais,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia' => $this->fechaFormat($visa_vigencia)

                      );
                    }else{
                         $data=array(
                        'id'=>$id_visa,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>6,
                        'descripcion'=>$visa_numero,
                        'pais'=>$visa_pais,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia' => $this->fechaFormat($visa_vigencia)
                        );                        
                    }

                   
                   // if ($i==0) {
                        $sql .= " documentos_actor.id !=".$id_visa;
                    //}else{
                        //$sql .= " AND documentos_actor.id !=".$id_visa;
                    //}
                    $this->model_casting->update_documentos_actor($data); 
                //}
            }

            $sql .=" ) ";
            //ELIMINAR VISAS
            if ($sql!=" (  ) ") {
                $this->model_casting->eliminar_documentos_actor($idactor,$sql,6);
            }
            

            //VISAS NUEVAS
            echo $visa_numero  = $this->input->post('visa_numer');
            $visa_pais = $this->input->post('visa_pais');
            $visa_vigencia = $this->input->post('visa_vigencia');
        
            //for ($v=0; $v < count($visa_numero) ; $v++) { 
                $val = false;
                $visa_documento='';
                //if($_FILES['visa_documento']['error'][0]==0){
                    if($_FILES['visa_documento']["tmp_name"]){
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['visa_documento']["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_VISA'.'_'.$this->extension_archivo($_FILES['visa_documento']["name"]);
                        $visa_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino); 
                        $val = true;
                    }
               // }

                $visa_numero_temp="";
                if($visa_numero){
                    $visa_numero_temp =  $visa_numero;
                    $val = true;
                }

                $visa_pais_temp="";
                if($visa_pais){
                    $visa_pais_temp =  $visa_pais;
                    $val = true;
                }

                $visa_vigencia_temp="";
                if($visa_vigencia){
                    $visa_vigencia_temp = $this->fechaFormat($visa_vigencia);
                    $val = true;
                }

                if($val){
                    $data=array(
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>6,
                        'url'=>$visa_documento,
                        'descripcion'=>$visa_numero_temp,
                        'pais'=>$visa_pais_temp,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null,
                        'vigencia'=>$visa_vigencia_temp
                    );
                    $this->model_casting->insert_documentos_actor($data);
                }
            //}
            //FIN VISAS

            //OTROS
            //OTROS ANTIGUOS
            $otro_numero  = $this->input->post('otro_numero_original');
            $otro_documento='';
            $id_otro = $this->input->post('id_otro_original');
            $sql = " ( ";
            if ($otro_numero) {
               // for ($i=0; $i < count($otro_numero); $i++) { 
                    $otro_documento =  $this->input->post('visa_documento_original_'.$id_otro);
                    if ($_FILES['otro_documento_'.$id_otro]["tmp_name"]) {
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['otro_documento_'.$id_otro]["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_VISA'.$this->extension_archivo($_FILES['otro_documento_'.$id_otro]["name"]);
                        $otro_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino);
                    }

                    if($otro_documento){
                        $data=array(
                        'id'=>$id_otro,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>7,
                        'url'=>$otro_documento,
                        'descripcion'=>$otro_numero,
                        'pais'=>null,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null
                      );
                    }else{
                       $data=array(
                        'id'=>$id_otro,
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>7,
                        'descripcion'=>$otro_numero,
                        'pais'=>null,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null
                    );    
                    }
                    
                   // if ($i==0) {
                        $sql .= " documentos_actor.id !=".$id_otro;
                    /*}else{
                        $sql .= " AND documentos_actor.id !=".$id_otro;
                    }*/
                    $this->model_casting->update_documentos_actor($data); 
               // }
            }
            // FIN OTROS ANTIGUOS

            $sql .=" ) ";
            //ELIMINAR OTROS
            if ($sql!=" (  ) ") {
                $this->model_casting->eliminar_documentos_actor($idactor,$sql,7);
            }

            $otro_numero  = $this->input->post('otro_numero');
            
           // for ($v=0; $v < count($otro_numero) ; $v++) { 
                $val = false;
                $otro_documento='';
                if($_FILES['otro_documento']["tmp_name"]){
                    $val = true;
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['otro_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$this->input->post('nombre_actor').'_'.$this->input->post('apellido_actor').'_OTROS'.'_'.$this->extension_archivo($_FILES['otro_documento']["name"]);
                    $otro_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }
                $otro_numero_tmp = "";
                if($otro_numero){
                    $val = true;
                    $otro_numero_tmp = $otro_numero;
                }
                if($val){
                    $data=array(
                        'id_actor'=>$idactor,
                        'id_tipo_documentacion'=>7,
                        'url'=>$otro_documento,
                        'descripcion'=>$otro_numero_tmp,
                        'id_clase_entidad'=>null,
                        'id_estado_entidad'=>null
                    );
                    $this->model_casting->insert_documentos_actor($data);
                }
           // }
            
            //FIN OTROS

            // FIN INSERCION DE DOCUMENTOS


            // ACTUALIZAR FOTOS ACTOR
            for ($i=0; $i < 4; $i++) { 
                if($_FILES['foto_remplazo_'.$i]["tmp_name"]){
                    $rutaServidor="images/casting";
                    $rutaTemporal= $_FILES['foto_remplazo_'.$i]["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$i.'_'.$idactor.$this->extension_archivo($_FILES['foto_remplazo_'.$i]["name"]);
                    $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                    $data=array(
                        'id'=>$this->input->post('foto_original_'.$i),
                        'ruta_foto'=>$rutaDestino
                    );
                    $this->model_casting->update_foto_actor($data);
                }
            } 

            //ACTULIZAR VIDEOS ACTOR
            //LINKS VIDEOS
            $this->model_casting->deleteVideoActor($idactor);
            $links_videos = $this->input->post('links_videos');
            if($links_videos AND $links_videos!=""){
                for ($i=0; $i < count($links_videos); $i++) { 
                    $dataVideo = array(
                        'id_actor' => $idactor, 
                        'url' => $links_videos[$i]
                    );
                    $this->model_casting->insertVideoActor($dataVideo);
                }

            }


            if($this->input->post('idiomas')){
                $idiomas = $this->input->post('idiomas');
                $this->model_casting->del_idiomas_actor($idactor);
                foreach ($idiomas as $idioma) {
                        $data = array(
                            'id_actor' => $idactor,
                            'id_idioma' => $idioma
                        );
                        $this->model_casting->insert_idioma($data);
                }
            }

            $this->model_casting->delete_papel_actor($idactor);

            if($this->input->post('produccion_actor')){
                $producciones = $this->input->post('produccion_actor');
                $roles = $this->input->post('rol_actor');
                $personajes = $this->input->post('personaje_actor');
                $anos = $this->input->post('ano_actor');
                for ($i=1; $i <count($producciones); $i++) { 
                    if($roles[$i]){
                        $data = array(
                            'id_actor' => $idactor,
                            'nombre_proyecto' => $producciones[$i],
                            'nombre_personaje' => $personajes[$i],
                            'id_rol' => $roles[$i],
                            'ano' => $anos[$i],
                        );
                        $this->model_casting->insert_papel_actor($data);
                    }
                }
            }
        }
        redirect($this->lang->lang().'/casting/detalle_actor/'.$idactor.'/2');
    }

    /*FUNCION CARGAR CIUDADES SEGUN PAIS*/
    public function ciudades_pais(){
        $idpais = $_POST['idpais'];
        $ciudades = $this->model_casting->ciudades_pais($idpais);
        $data['ciudades']=$ciudades;
        echo json_encode($data);
    }
    /*FUNCION CARGAR CIUDADES SEGUN PAIS*/


    /*FUNCION VALIDAR DOCUMENTOS ACTOR*/
    public function validar_documento(){
        $documento = $_POST['documento'];
        $id_actor = $_POST['id_actor'];
        $tipo_documento = $_POST['tipo_documento'];
        if ($id_actor) {
            $documento = $this->model_casting->validar_documento($documento,$tipo_documento,$id_actor);
        }else{
            $documento = $this->model_casting->validar_documento($documento,$tipo_documento);
        }
        echo json_encode($documento);
    }

    // FUNCION FILTRO DE ACTORES
    public function filtro_actores(){

        /*INICIALIZACION DE VARIABLES*/
        $nacionalidades = $_POST['nacionalidades'];
        $tipo_documento = $_POST['tipo_documento'];
        $documento = $_POST['documento'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $manager = $_POST['manager'];
        $genero = $_POST['genero'];
        $altura_desde = $_POST['altura_desde'];
        $altura_hasta = $_POST['altura_hasta'];
        $peso_desde = $_POST['peso_desde'];
        $peso_hasta = $_POST['peso_hasta'];
        $edad_desde = $_POST['edad_desde'];
        $edad_hasta = $_POST['edad_hasta'];
        $color_tez = $_POST['color_tez'];
        $color_ojos = $_POST['color_ojos'];
        $idioma = $_POST['idioma'];
        $proyectos_desempenados = $_POST['proyectos_desempenados'];
        $personajes_desempenados = $_POST['personajes_desempenados'];
        $rol = $_POST['rol'];
        $disponible = $_POST['disponible'];
        $fotos = $_POST['fotos'];
        $extranjero = $_POST['extranjero'];

        $sql = "";
        $sql_inner = "";
        $resumen_busqueda = "";
        $listCookie=array();
        $listCookie[0]="NULL";
        if($nacionalidades!=""){
            $listCookie[1]=$nacionalidades;
            $nacionalidades = explode(',', $nacionalidades);
            $resumen_busqueda .='<div class="column three"><label for="">Nacionalidad</label>';
            $sql .= " AND ( ";
            for ($i=0; $i < count($nacionalidades); $i++) { 
                if($nacionalidades[$i]!=""){
                    if($i==0){
                        $sql .= " actores.id_nacionalidad = ".$nacionalidades[$i];
                    }else{
                        $sql .= " OR actores.id_nacionalidad = ".$nacionalidades[$i];
                    }
                    $nacionalidad = $this->model_casting->nacionalidad_id($nacionalidades[$i]);
                    $resumen_busqueda .='<span>'.$nacionalidad[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>';
                }
            }
            $sql .= " ) ";
            $resumen_busqueda .='</div>';
        }else{
            $listCookie[1]="NULL";
        }

        if($tipo_documento){
            $listCookie[2]=$tipo_documento;
            $sql .= " AND actores.id_tipo_documento = ".$tipo_documento;
            $tipo_documento = $this->model_casting->tipo_documento_id($tipo_documento);
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Tipo documento</label>
                                    <span>'.$tipo_documento[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[2]="NULL";
        }

        if ($documento) {
            $listCookie[3]=$documento;
            $sql .= " AND actores.documento = ".$documento;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Documento</label>
                                    <span>'.$documento.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
             $listCookie[3]="NULL";
        }

        if($nombre){
            $listCookie[4]=$nombre;
            $sql .= " AND actores.nombre = '".$nombre."'";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Nombre</label>
                                    <span>'.$nombre.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[4]="NULL";
        }

        if($apellido){
            $listCookie[5]=$apellido;
            $sql .= " AND UPPER(actores.apellido) = UPPER('".$apellido."')";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Apellido</label>
                                    <span>'.$apellido.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[5]="NULL";
        }

        if($manager){
            $listCookie[16]=$manager;
            $sql .= " AND actores.id_manager = ".$manager." ";
        }else{
             $listCookie[16]="NULL";
        }


        if ($genero) {
             $listCookie[6]=$genero;
            $sql .= " AND actores.id_genero = ".$genero;
            $genero = $this->model_casting->genero_id($genero);
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Género</label>
                                    <span>'.$genero[0]->descripcion.' <img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[6]="NULL";
        }


        /*VALIDACINDES DE ALTURA*/
        if ($altura_desde!="" AND $altura_hasta!="") {
            $listCookie[7]=$altura_desde;
            $listCookie[8]=$altura_hasta;
            $sql .= " AND (actores.altura BETWEEN ".$altura_desde." AND ".$altura_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_desde.' - '.$altura_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[7]="NULL";
            $listCookie[8]="NULL";
        }

        if ($altura_desde!="" AND $altura_hasta=="") {
            $sql .= " AND actores.altura >= ".$altura_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        if ($altura_hasta!="" AND $altura_desde=="") {
            $sql .= " AND actores.altura <= ".$altura_hasta;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Altura</label>
                                    <span>'.$altura_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }

        /*VALIDACIONES DE PESO*/
        if ($peso_desde!="" AND $peso_hasta!="") {
            $listCookie[9]=$peso_desde;
            $listCookie[10]=$peso_hasta;
            $sql .= " AND (actores.altura BETWEEN ".$altura_desde." AND ".$altura_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Peso</label>
                                    <span>'.$peso_desde.' - '.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[9]="NULL";
            $listCookie[10]="NULL";
        }

        if ($peso_desde!="" AND $peso_hasta=="") {
            $sql .= " AND actores.peso >= ".$peso_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">Peso</label>
                                    <span>'.$peso_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';

        }

        if ($peso_hasta!="" AND $peso_desde=="") {
            $sql .= " AND actores.peso <= ".$peso_hasta;
            $resumen_busqueda .='<div class="column three">
                        <label for="">Peso</label>
                        <span>'.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }

        // VALIDACIONES edad_hasta
        if ($edad_desde!="" AND $edad_hasta!="") {
            $listCookie[11]=$edad_desde;
            $listCookie[12]=$edad_hasta;
            $sql .= " AND (TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) BETWEEN ".$edad_desde." AND ".$edad_hasta.") ";
            $resumen_busqueda .='<div class="column three">
                                    <label for="">edad</label>
                                    <span>'.$edad_desde.' - '.$edad_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';
        }else{
            $listCookie[11]="NULL";
            $listCookie[12]="NULL";
        }

        if ($edad_desde!="" AND $edad_hasta=="") {
            $sql .= " AND TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) >= ".$edad_desde;
            $resumen_busqueda .='<div class="column three">
                                    <label for="">edad</label>
                                    <span>'.$edad_desde.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                                </div>';

        }

        if ($edad_hasta!="" AND $edad_desde=="") {
            $sql .= " AND TIMESTAMPDIFF(YEAR, actores.fecha_nacimiento, CURDATE()) <= ".$edad_hasta;
            $resumen_busqueda .='<div class="column three">
                        <label for="">Peso</label>
                        <span>'.$peso_hasta.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }


        if ($color_tez) {
            $listCookie[13]=$color_tez;
            $sql .= " AND actores.id_color_tez = ".$color_tez;
            $color_tez  = $this->model_casting->color_tez_id($color_tez);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Color tez</label>
                        <span>'.$color_tez[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
            $listCookie[13]="NULL";
        }

        if ($color_ojos) {
            $listCookie[14]=$color_tez;
            $sql .= " AND actores.id_color_ojos = ".$color_ojos;
            $color_ojos  = $this->model_casting->color_ojos_id($color_ojos);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Color ojos</label>
                        <span>'.$color_ojos[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
            $listCookie[14]="NULL";
        }

        if ($idioma) {
            $listCookie[15]=$idioma;
            $sql .= " AND idiomas_actor.id_idioma = ".$idioma;
            $sql_inner.=" INNER JOIN idiomas_actor ON idiomas_actor.id_actor = actores.id ";
            $idioma  = $this->model_casting->idioma_id($idioma);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Idioma</label>
                        <span>'.$idioma[0]->descripcion.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
            $listCookie[15]="NULL";
        }

        if ($proyectos_desempenados) {
            $listCookie[19]=$proyectos_desempenados;
            $sql .= " AND UPPER(papeles_actor.nombre_proyecto) = UPPER('".$proyectos_desempenados."') ";
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $resumen_busqueda .='<div class="column three">
                        <label for="">Proyectos desempeñados</label>
                        <span>'.$proyectos_desempenados.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
             $listCookie[19]="NULL";
        }

        if ($personajes_desempenados) {
            $listCookie[20]=$personajes_desempenados;
            $sql .= " AND UPPER(papeles_actor.nombre_personaje) = UPPER('".$personajes_desempenados."') ";
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $resumen_busqueda .='<div class="column three">
                        <label for="">Personajes desempeñados</label>
                        <span>'.$proyectos_desempenados.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
             $listCookie[20]="NULL";
        }

        if ($rol) {
             $listCookie[17]=$rol;
            $sql .= " AND papeles_actor.id_rol = ".$rol;
            $sql_inner.=" INNER JOIN papeles_actor ON papeles_actor.id_actor = actores.id ";
            $rol = $this->model_casting->rol_id($rol);
            $resumen_busqueda .='<div class="column three">
                        <label for="">Roles desempeñados</label>
                        <span>'.$rol[0]->rol.'<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
        }else{
             $listCookie[17]="NULL";
        }
        if($disponible){
            $listCookie[23]=$disponible;
            $sql .= " AND actores.disponible = ".$disponible;
            if($disponible==1){
                $resumen_busqueda .='<div class="column three">
                        <label for="">Disponible</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
            }else{
                $resumen_busqueda .='<div class="column three">
                        <label for="">Disponible</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:bottom;" /></span>
                    </div>';
            }
        }else{
             $listCookie[23]="NULL";
        }

        if($fotos){
            $listCookie[22]=$fotos;
            if($fotos==1){
                $sql_inner.=" INNER JOIN fotos_actor ON fotos_actor.id_actor = actores.id ";
                $resumen_busqueda .='<div class="column three">
                        <label for="">Fotos</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }else{
                $sql .= " AND actores.id NOT IN (SELECT id_actor FROM fotos_actor WHERE fotos_actor.id_actor = actores.id) ";

                $resumen_busqueda .='<div class="column three">
                        <label for="">Fotos</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }
        }else{
            $listCookie[22]="NULL";
        }

        if($extranjero){
            $listCookie[24]=$extranjero;
            if($extranjero==1){
                $sql .= " AND actores.id_nacionalidad!=13";
                $resumen_busqueda .='<div class="column three">
                        <label for="">extranjero</label>
                        <span>Si<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }else{
                $sql .= " AND actores.id_nacionalidad=13 ";
                $resumen_busqueda .='<div class="column three">
                        <label for="">extranjero</label>
                        <span>No<img src="'.base_url().'/images/close.png" width="14" style="vertical-align:middle;margin-left:10px" /></span>
                    </div>';
            }

        }else{
            $listCookie[24]="NULL";
        }

        $listCookie[18]="NULL";
        $listCookie[21]="NULL";

        $actores = $this->model_casting->filtro_actores($sql,$sql_inner);
        if(!$actores){
            $actores="";
        }
        $separeteArray=implode(';', $listCookie);
        setcookie("filtro_casting", $separeteArray, time()+1200); 
        $data['actores'] = $actores;
        $data['resumen_busqueda'] = $resumen_busqueda.'<div class="clr"></div>';
        echo json_encode($data);
    }
    //limpiar Filtro
    public function limpiar_filtro(){
        $separeteArray="NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL";
        //setcookie("filtro_casting", $separeteArray, time()+1200); 
        setcookie('filtro_casting', '', time()-300);   
        redirect($this->lang->lang().'/casting');
    }
    // FUNCION FILTRAR ACTORES POR NOMBRE
    public function filtro_actores_nombre(){
        $palabra = $_POST['palabra'];
        $actores = $this->model_casting->filtro_actores_palabra($palabra);
        $separeteArray=$palabra.";NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL;NULL";
        setcookie("filtro_casting", $separeteArray, time()+1200); 
        $data['actores'] = $actores;
        echo json_encode($data);
    }

    // FUNCION DETALE DE ACTOR
    public function detalle_actor($idactor,$msg=""){
         // FUNCION VERIFICACION DE PERMISOS USUARIOS
        $iduser = $this->session->userdata('id_pruduction_suite');
        $permisos = "read";
        $usuario = $this->model_produccion->user_id($iduser);
        $usuario_roles=$this->model_admin->rolUserId($iduser);
          if($usuario_roles){
            foreach ($usuario_roles as $usuario_rol) {
              if($usuario_rol['id_rol_otros']==15){
                $permisos = "write";
                break;
              }else{
                $permisos = "read";
              }
            }
          }else{
            if($usuario[0]->id_tipoUsuario=='1' or $usuario[0]->id_tipoUsuario=='3' or $usuario[0]->id_tipoUsuario=='9'){
              $permisos = "write";
            }else{
              $permisos = "read";
            }
          }
        //////////////////////////////////////

        $actor = $this->model_casting->actor_id($idactor);
        $proyectos_actor = $this->model_casting->proyectos_actor($idactor);
        $fotos_actor = $this->model_casting->fotos_actor_cargadas($idactor); 
        $solicitudes_actor = $this->model_casting->solicitudes_actor($idactor);
        $videos_actor = $this->model_casting->videos_actor($idactor);
        $documentos_actor = $this->model_casting->documentos_actor($idactor);
        $visas_actor = $this->model_casting->otros_documentos_actor($idactor,6);
        $otros_documentos_actor = $this->model_casting->otros_documentos_actor($idactor,7);
        $data['msg'] = $msg;
        $data['actor'] = $actor;
        $data['proyectos_actor'] = $proyectos_actor;
        $data['documentos_actor'] = $documentos_actor;
        $data['visas_actor'] = $visas_actor;
        $data['otros_documentos_actor'] = $otros_documentos_actor;
        $data['fotos_actor'] = $fotos_actor;
        $data['solicitudes_actor'] = $solicitudes_actor;
        $data['videos_actor'] = $videos_actor;
        $data['permisos']=$permisos;
        $data['view']='casting/detalle_actor';
        $this->load->view('includes/template',$data); 
    }

    public function crear_solicitud($idactor="",$idproduccion="",$idelemento=""){
        $actores= $this->model_casting->get_actores();
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);
        $objetos_contrato= $this->model_casting->objetos_contrato();
        $lugares_servicio= $this->model_casting->lugares_servicio();
        $areas_solicitantes= $this->model_casting->areas_solicitantes();
        $roles = $this->model_escenas->rol_actores_elementos_casting();
        $formas_pago = $this->model_elementos->contratos();
        $tipos_moneda = $this->model_elementos->tipos_moneda();
        $elemento = false;

        if($idelemento!="" AND $idelemento!=null){
            $elemento = $this->model_elementos->buscar_elemento_id($idelemento);
        }

        $data['tipos_moneda'] = $tipos_moneda;
        $data['idactor'] = $idactor;
        $data['idproduccion'] = $idproduccion;
        $data['elemento'] = $elemento;
        $data['idelemento'] = $idelemento;
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();

        $data['actores'] = $actores;
        $data['roles'] = $roles;
        $data['formas_pago'] = $formas_pago;
        $data['producciones'] = $producciones;
        $data['objetos_contrato'] = $objetos_contrato;
        $data['lugares_servicio'] = $lugares_servicio;
        $data['areas_solicitantes'] = $areas_solicitantes;

        $data['view']='casting/crear_solicitud';
        $this->load->view('includes/template',$data); 
    }

    public function detalle_solicitud($idsolicitud,$type=""){
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
       
        $elementos_solicitud = $this->model_casting->elementos_solicitud($idsolicitud);
        $historial_solicitud = $this->model_casting->historial_solicitud($idsolicitud);
        $documentos_actor=$this->model_casting->documentos_actor($solicitud[0]->id_actorp);
        $comentarios_solicitud = $this->model_casting->comentarios_solicitud($idsolicitud);
        $documentos_solicitud = $this->model_casting->documentos_solicitud($idsolicitud);
        $documentos_actor = false;
        $visas_actor = false;

        $iduser = $this->session->userdata('id_pruduction_suite');
        $usuario_roles=$this->model_admin->rolUserId($iduser);
        $coordinado=0;
        if($usuario_roles){
            foreach ($usuario_roles as $usuario_rol) {
                  if($usuario_rol['id_rol_otros']==18){
                     $coordinado=1;
                  }
            }
        }


        if($this->diferencia_dias($solicitud[0]->fecha_inicio,$solicitud[0]->fecha_final)>15){
            $documentos_actor = $this->model_casting->documentos_actor($solicitud[0]->id_actorp);
        }
        if ($solicitud[0]->id_nacionalidad!=13 AND $solicitud[0]->id_nacionalidad!="") {
            $visas_actor = $this->model_casting->otros_documentos_actor($solicitud[0]->id_actorp,6);
        }
        $data['permisos'] = $this->permisos_solicitud($solicitud[0]);

        $data['tipo_usuario'] = $this->session->userdata('tipo_pruduction_suite');
        $data['caso_especial'] = $this->validacion_caso_especial($solicitud[0]);
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();
        $data['documentos_actor'] = $documentos_actor;
        $data['visas_actor'] = $visas_actor;
        $data['documentos_solicitud'] = $documentos_solicitud;
        $data['comentarios_solicitud'] = $comentarios_solicitud;
        $data['documentos_actor'] = $documentos_actor;
        $data['elementos_solicitud'] = $elementos_solicitud;
        $data['solicitud'] = $solicitud;
        $data['historial_solicitud'] = $historial_solicitud;
        $data['type']=$type;
        $data['coordinado']=$coordinado;
        $data['view']='casting/detalle_solicitud';
        $this->load->view('includes/template',$data);
    }

    public function editar_solicitud($idsolicitud){
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
        $elementos_solicitud = $this->model_casting->elementos_solicitud($idsolicitud);
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);
        $objetos_contrato= $this->model_casting->objetos_contrato();
        $lugares_servicio= $this->model_casting->lugares_servicio();
        $areas_solicitantes= $this->model_casting->areas_solicitantes();
        $roles = $this->model_escenas->rol_actores_elementos();
        $formas_pago = $this->model_elementos->contratos();
        $actores= $this->model_casting->get_actores();
        $tipos_moneda = $this->model_elementos->tipos_moneda();
        $documentos_solicitud = $this->model_casting->documentos_solicitud($idsolicitud);
        $comentarios_solicitud = $this->model_casting->comentarios_solicitud($idsolicitud);

        $data['comentarios_solicitud'] = $comentarios_solicitud;
        $data['documentos_solicitud'] = $documentos_solicitud;
        $data['tipos_moneda'] = $tipos_moneda;
        $data['actores'] = $actores;
        $data['solicitud'] = $solicitud;
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();
        $data['elementos_solicitud'] = $elementos_solicitud;
        $data['actores'] = $actores;
        $data['roles'] = $roles;
        $data['formas_pago'] = $formas_pago;
        $data['producciones'] = $producciones;
        $data['objetos_contrato'] = $objetos_contrato;
        $data['lugares_servicio'] = $lugares_servicio;
        $data['areas_solicitantes'] = $areas_solicitantes;
        $data['solicitud'] = $solicitud;
        $data['view']='casting/editar_solicitud';
        $this->load->view('includes/template',$data); 
    }

    public function editar_solicitud_otro_si($idsolicitud){
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
        $elementos_solicitud = $this->model_casting->elementos_solicitud($idsolicitud);
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);
        $objetos_contrato= $this->model_casting->objetos_contrato();
        $lugares_servicio= $this->model_casting->lugares_servicio();
        $areas_solicitantes= $this->model_casting->areas_solicitantes();
        $roles = $this->model_escenas->rol_actores_elementos();
        $formas_pago = $this->model_elementos->contratos();
        $actores= $this->model_casting->get_actores();
        $tipos_moneda = $this->model_elementos->tipos_moneda();
        $documentos_solicitud = $this->model_casting->documentos_solicitud($idsolicitud);
        $comentarios_solicitud = $this->model_casting->comentarios_solicitud($idsolicitud);

        $data['comentarios_solicitud'] = $comentarios_solicitud;
        $data['documentos_solicitud'] = $documentos_solicitud;
        $data['tipos_moneda'] = $tipos_moneda;
        $data['actores'] = $actores;
        $data['solicitud'] = $solicitud;
        $data['clases_entidad'] = $this->model_casting->clases_entidades();
        $data['estados_entidad'] = $this->model_casting->estados_entidades();
        $data['elementos_solicitud'] = $elementos_solicitud;
        $data['actores'] = $actores;
        $data['roles'] = $roles;
        $data['formas_pago'] = $formas_pago;
        $data['producciones'] = $producciones;
        $data['objetos_contrato'] = $objetos_contrato;
        $data['lugares_servicio'] = $lugares_servicio;
        $data['areas_solicitantes'] = $areas_solicitantes;
        $data['solicitud'] = $solicitud;
        $data['view']='casting/editar_otro_si';
        $this->load->view('includes/template',$data); 
    }

    public function update_solicitud(){
        $this->form_validation->set_rules('area_solicitante','Area Solicitante','required');
        $this->form_validation->set_rules('lugar_sevicio','Lugar prestación servicio','required');
        $this->form_validation->set_rules('fecha_inicio','Fecha inicio','required');
        $this->form_validation->set_rules('fecha_final','Fecha terminación','required');
        if ($this->form_validation->run()==FALSE) {
            $this->editar_solicitud($this->input->post('id_solicitud'));
        }else{
            $condiciones ="";
            $sugerenias = "";
            $rutaEPS=$this->input->post('eps_documento_hidden');
            $rutaPensiones=$this->input->post('pensiones_documento_hidden');
            $rutaARL=$this->input->post('arl_documento_hidden');
            $id_solicitud = $this->input->post('id_solicitud');
            $id_elementos = $this->input->post('id_elemento');

            $solicitud = $this->model_casting->solicitud_id($id_solicitud);
            $actor = $this->model_casting->actor_id($solicitud[0]->id_actor);

            if($this->input->post('valida_condiciones')){
                $condiciones = $this->input->post('condiciones_especiales');
            }

            if($this->input->post('valida_sugerencias')){
                $sugerenias = $this->input->post('sugerencias_contratacion');
            }

            if($this->input->post('valida_completo')==1){
                $aprobacion_solicitud = $this->model_casting->buscar_aprobacion($id_solicitud,1);
                if(!$aprobacion_solicitud ){
                    $dataEstado = array(
                        'id_solicitud' => $id_solicitud, 
                        'id_estado' => 1,
                        'fecha_aprobacion' => date("Y-m-d"),
                        'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                        'activo'=>0,
                        'notas'=>''
                    );
                    $this->model_casting->insert_aprobacion_solicitud($dataEstado);
                }

                $valida_protagonista = true;
                foreach ($id_elementos as $id_elemento) {
                    $elemento_t = $this->model_elementos->buscar_elemento_id($id_elemento);
                    if($elemento_t[0]->rol == 1){
                        $valida_protagonista = false;
                    }
                }

                // VALIDA EXTRANJERO
                if($actor[0]->id_nacionalidad!=13 AND $valida_protagonista){
                    $estado = 15;
                }else{
                    $estado = 2;
                }
                // VALIDA EXTRANjERO
            }else{
                $estado = 1;
            }

            $tipo_moneda = $this->input->post('tipo_moneda');
            if (!$tipo_moneda) {
                $tipo_moneda = 1;
            }


            $data = array(
                'id'=>$id_solicitud,
                'id_actor' => $this->input->post('actor'), 
                'area_solicitante'=> $this->input->post('area_solicitante'),
                'id_usuario' =>$this->session->userdata('id_pruduction_suite'), 
                'id_lugar_servicio'=>$this->input->post('lugar_sevicio'),
                'fecha_inicio'=>$this->fechaFormat($this->input->post('fecha_inicio')),
                'fecha_final'=>$this->fechaFormat($this->input->post('fecha_final')),
                'id_objeto_contrato'=>$this->input->post('objeto_contrato'),
                'honorarios'=>str_replace(',', '', $this->input->post('honorarios')),
                'honorarios_letras'=>$this->input-> post('honorarios_letras'),
                'id_forma_pago'=>$this->input->post('forma_pago'),
                'id_tipo_moneda'=>$tipo_moneda,
                'condiciones_especiales'=>$condiciones,
                'sugerencias_contratacion'=>$sugerenias,
                'sugerencias_contratacion'=>$sugerenias,
                'mes_otro_si'=> date("Y-m-d",strtotime('01'.$this->input-> post('mes_inicio'))),
                'id_estado'=>$estado
            );

            $this->model_casting->update_solicitud($data);

            if($estado==2){
                $dataEstado = array(
                    'id_solicitud' => $id_solicitud, 
                    'id_estado' => $estado,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>1,
                    'notas'=>''
                );
                $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }

            

            $this->model_casting->delete_solcitud_elemento($id_solicitud);
            foreach ($id_elementos as $id_elemento) {
                $dataElemento = array(
                    'id_solicitud' => $id_solicitud, 
                    'id_elemento' => $id_elemento,
                );
                $this->model_casting->insert_solicitud_elemento($dataElemento);
            }

            if((strtotime($this->input->post('fecha_final')) - strtotime($this->input->post('fecha_inicio')))>15){
                //ACTUALIZACION DE DOCUMENTOS
                $documentos_actor = $this->model_casting->documentos_actor($actor[0]->id);
                $idactor = $actor[0]->id;
                // ARL
                $arl_activo = $this->input->post('arl_activo');
                $arl_clase = $this->input->post('arl_clase');
                $arl_nombre = $this->input->post('arl_nombre');
                $arl_documento='';
                $id_arl = $this->input->post('id_arl');
                if($_FILES['arl_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_ARL'.$this->extension_archivo($_FILES['arl_documento']['name']);
                    $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $arl_documento = $documentos_actor[1]->url;
                }
                if(!$arl_activo){
                  $arl_activo=null;
                }
                if(!$arl_clase){
                  $arl_clase=null;
                }
                $data=array(
                    'id'=>$id_arl,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>2,
                    'url'=>$arl_documento,
                    'descripcion'=>$arl_nombre,
                    'id_clase_entidad'=>$arl_activo,
                    'id_estado_entidad'=>$arl_clase);
                $this->model_casting->update_documentos_actor($data);

                // EPS
                $eps_nombre = $this->input->post('eps_nombre');
                $eps_activo = $this->input->post('eps_activo');
                $eps_clase = $this->input->post('eps_clase');
                $id_eps = $this->input->post('id_eps');
                $eps_documento='';
                
                if($_FILES['eps_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_EPS'.$this->extension_archivo($_FILES['eps_documento']['name']);
                    $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $eps_documento = $documentos_actor[2]->url;
                }
                if(!$eps_activo){
                  $eps_activo=null;
                }
                if(!$eps_clase){
                  $eps_clase=null;
                }

                $data=array(
                    'id'=>$id_eps,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>3,
                    'url'=>$eps_documento,
                    'descripcion'=>$eps_nombre,
                    'id_clase_entidad'=>$eps_activo,
                    'id_estado_entidad'=>$eps_clase);
                $this->model_casting->update_documentos_actor($data);

                // FONDO PENSIONES
                $pensiones_nombre = $this->input->post('pensiones_nombre');
                $pensiones_activo = $this->input->post('pensiones_activo');
                $pensiones_clase = $this->input->post('pensiones_clase');
                $pensiones_documento ='';
                $id_pensiones = $this->input->post('id_pensiones');

                if($_FILES['pensiones_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']['name']);
                    $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $pensiones_documento = $documentos_actor[3]->url;
                }
                if(!$pensiones_activo){
                  $pensiones_activo=null;
                }
                if(!$pensiones_clase){
                  $pensiones_clase=null;
                }
                $data=array(
                    'id'=>$id_pensiones,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>4,
                    'url'=>$pensiones_documento,
                    'descripcion'=>$pensiones_nombre,
                    'id_clase_entidad'=>$pensiones_activo,
                    'id_estado_entidad'=>$pensiones_clase
                );
                $this->model_casting->update_documentos_actor($data);
                //FIN ACTUALIZACION DE DOCUMENTOS
            }


            $documento_numero = $this->input->post('documento_numero_original');
            $documento='';
            $id_documento= $this->input->post('id_documento_original');
            $sql = " ( ";
            //DOCUMENTOS ANTERIORES
            if($documento_numero){
                for ($i=0; $i < count($documento_numero); $i++) { 
                    $documento =  $this->input->post('documento_original_'.$id_documento[$i]);
                    if ($_FILES['documento_solicitud_'.$id_documento[$i]]["tmp_name"]) {
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['documento_solicitud_'.$id_documento[$i]]["tmp_name"];
                        $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$id_solicitud.$this->extension_archivo($_FILES['documento_solicitud_'.$id_documento[$i]]["name"]);
                        $documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino);
                    }

                    $data=array(
                        'id'=>$id_documento[$i],
                        'id_solicitud'=>$id_solicitud,
                        'documento'=>$documento,
                        'descripcion'=>$documento_numero[$i],
                    );
                    if ($i==0) {
                        $sql .= " documentos_solicitud.id !=".$id_documento[$i];
                    }else{
                        $sql .= " AND documentos_solicitud.id !=".$id_documento[$i];
                    }
                    $this->model_casting->update_documentos_solicitud($data); 
                }
            }

            $sql .=" ) ";
            //ELIMINAR DOCUMENTOS
            if ($sql!=" (  ) ") {
                $this->model_casting->eliminar_documentos_solicitud($id_solicitud,$sql);
            }

            //INSERCION NUEVOS DOCUMENTOS
            $numeros_documentos = $this->input->post('documento_numero');
            for ($i=0; $i < count($numeros_documentos); $i++) {
                $valida = 0;
                $documento_solicitud = "";
                if($_FILES['documento_solicitud']['error'][0]==0){ 
                    if($_FILES['documento_solicitud']["tmp_name"][$i]){
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['documento_solicitud']["tmp_name"][$i];
                        $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$id_solicitud.'_'.$i.$this->extension_archivo($_FILES['documento_solicitud']['name'][$i]);
                        $documento_solicitud =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino); 
                        ++$valida;
                    }
                }

                if($valida>0){
                    $data=array(
                        'id_solicitud'=>$id_solicitud,  
                        'documento'=>$documento_solicitud,
                        'descripcion'=>$numeros_documentos[$i],
                        'fecha' => date('Y-m-d H:i:s')
                    );
                    $this->model_casting->insert_documento_solcitud($data);
                }

            }
            //FIN INSERCION NUEVOS DOCUMENTOS

            //INSERSION COMENTARIOS
            $idusuario = $this->session->userdata('id_pruduction_suite');
            $comentario = $this->input->post('notas_solicitud');
            if($comentario!=""){
                $data = array(
                    'id_solicitud' => $id_solicitud,
                    'id_usuario' => $idusuario, 
                    'comentario' => $comentario,
                    'fecha' => date('Y-m-d H:i:s')
                );
                $this->model_casting->guardar_comentario_solicitud($data);
            }
            //FIN INSERSION DOCUMENTOS
            
            redirect($this->lang->lang().'/casting/detalle_solicitud/'.$id_solicitud);
        }
    }

    public function insert_solicitud(){
        $this->form_validation->set_rules('area_solicitante','Area Solicitante','required');
        $this->form_validation->set_rules('lugar_sevicio','Lugar prestación servicio','required');
        $this->form_validation->set_rules('fecha_inicio','Fecha inicio','required');
        $this->form_validation->set_rules('fecha_final','Fecha terminación','required');
        if ($this->form_validation->run()==FALSE) {
            $this->crear_solicitud($this->input->post('produccion'));
        }else{
            $condiciones ="";
            $sugerenias = "";
            $rutaEPS="";
            $rutaPensiones="";
            $rutaARL="";

            if($this->input->post('valida_condiciones')){
                $condiciones = $this->input->post('condiciones_especiales');
            }

            if($this->input->post('valida_sugerencias')){
                $sugerenias = $this->input->post('sugerencias_contratacion');
            }

            $id_elementos = $this->input->post('id_elemento');
            $actor = $this->model_casting->actor_id($this->input->post('actor'));

            $valida_protagonista = true;
            foreach ($id_elementos as $id_elemento) {
                $elemento_t = $this->model_elementos->buscar_elemento_id($id_elemento);
                if($elemento_t[0]->rol == 1){
                    $valida_protagonista = false;
                }
            }

            if($this->input->post('valida_completo')==1){
                /*VALIDACION EXTRANJERTO*/
                if($actor[0]->id_nacionalidad!=13 AND $valida_protagonista){
                    $estado = 19;
                }else{
                   $estado = 2; 
                }
            }else{
                $estado = 1;
            }

            $tipo_moneda = $this->input->post('tipo_moneda');
            if (!$tipo_moneda) {
                $tipo_moneda = 1;
            }


            $data = array(
                'id_actor' => $this->input->post('actor'), 
                'area_solicitante'=> $this->input->post('area_solicitante'),
                'id_usuario' =>$this->session->userdata('id_pruduction_suite'), 
                'id_lugar_servicio'=>$this->input->post('lugar_sevicio'),
                'fecha_inicio'=>$this->fechaFormat($this->input->post('fecha_inicio')),
                'fecha_final'=>$this->fechaFormat($this->input->post('fecha_final')),
                'id_objeto_contrato'=>$this->input->post('objeto_contrato'),
                'honorarios'=>str_replace(',', '', $this->input->post('honorarios')),
                'honorarios_letras'=>$this->input->post('honorarios_letras'),
                'id_forma_pago'=>$this->input->post('forma_pago'),
                'id_tipo_moneda'=>$this->input->post('tipo_moneda'),
                'condiciones_especiales'=>$condiciones,
                'id_estado'=>$estado,
                'fecha_creacion'=>date("Y-m-d")
            );

            $this->model_casting->insert_solicitud($data);
            $id_solicitud = mysql_insert_id();

            if($this->input->post('valida_completo')==1){
                $dataEstado = array(
                    'id_solicitud' => $id_solicitud, 
                    'id_estado' => 1,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }


            $dataEstado = array(
                'id_solicitud' => $id_solicitud, 
                'id_estado' => $estado,
                'fecha_aprobacion' => date("Y-m-d H:i:s"),
                'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                'activo'=>1,
                'notas'=>''
            );

            $this->model_casting->insert_aprobacion_solicitud($dataEstado);

            foreach ($id_elementos as $id_elemento) {
                $dataElemento = array(
                    'id_solicitud' => $id_solicitud, 
                    'id_elemento' => $id_elemento,
                );
                $this->model_casting->insert_solicitud_elemento($dataElemento);
            }
            

            //INSERCION DOCUMENTOS SOLICITUD
            $numeros_documentos = $this->input->post('documento_numero');
            for ($i=0; $i < count($numeros_documentos); $i++) {
                $valida = 0;
                $documento_solicitud = "";
                if($_FILES['documento_solicitud']['error'][0]==0){ 
                    if($_FILES['documento_solicitud']["tmp_name"][$i]){
                        $rutaServidor="images/documentos_actor";
                        $rutaTemporal= $_FILES['documento_solicitud']["tmp_name"][$i];
                        $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$id_solicitud.'_'.$i.$this->extension_archivo($_FILES['documento_solicitud']['name'][$i]);
                        $documento_solicitud =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                        move_uploaded_file($rutaTemporal, $rutaDestino); 
                        ++$valida;
                    }
                }

                if($valida>0){
                    $data=array(
                        'id_solicitud'=>$id_solicitud,  
                        'documento'=>$documento_solicitud,
                        'descripcion'=>$numeros_documentos[$i],
                        'fecha' => date('Y-m-d H:i:s')
                    );
                    $this->model_casting->insert_documento_solcitud($data);
                }

            }
            //FIN INSERCION DOCUMENTOS SOLICITUD

            //INSERSION COMENTARIOS
            $idusuario = $this->session->userdata('id_pruduction_suite');
            $comentario = $this->input->post('notas_solicitud');
            if($comentario!=""){
                $data = array(
                    'id_solicitud' => $id_solicitud,
                    'id_usuario' => $idusuario, 
                    'comentario' => $comentario,
                    'fecha' => date('Y-m-d H:i:s')
                );
                $this->model_casting->guardar_comentario_solicitud($data);
            }
            //FIN INSERSION DOCUMENTOS

            
            if((strtotime($this->input->post('fecha_final')) - strtotime($this->input->post('fecha_inicio')))>15){
                //ACTUALIZACION DE DOCUMENTOS
                $documentos_actor = $this->model_casting->documentos_actor($actor[0]->id);
                $idactor = $actor[0]->id;
                // ARL
                $arl_activo = $this->input->post('arl_activo');
                $arl_clase = $this->input->post('arl_clase');
                $arl_nombre = $this->input->post('arl_nombre');
                $arl_documento='';
                $id_arl = $this->input->post('id_arl');
                if($_FILES['arl_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_ARL'.$this->extension_archivo($_FILES['arl_documento']['name']);
                    $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $arl_documento = $documentos_actor[1]->url;
                }
                if(!$arl_activo){
                  $arl_activo=null;
                }
                if(!$arl_clase){
                  $arl_clase=null;
                }
                $data=array(
                    'id'=>$id_arl,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>2,
                    'url'=>$arl_documento,
                    'descripcion'=>$arl_nombre,
                    'id_clase_entidad'=>$arl_activo,
                    'id_estado_entidad'=>$arl_clase);
                $this->model_casting->update_documentos_actor($data);

                // EPS
                $eps_nombre = $this->input->post('eps_nombre');
                $eps_activo = $this->input->post('eps_activo');
                $eps_clase = $this->input->post('eps_clase');
                $id_eps = $this->input->post('id_eps');
                $eps_documento='';
                
                if($_FILES['eps_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_EPS'.$this->extension_archivo($_FILES['eps_documento']['name']);
                    $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $eps_documento = $documentos_actor[2]->url;
                }
                if(!$eps_activo){
                  $eps_activo=null;
                }
                if(!$eps_clase){
                  $eps_clase=null;
                }

                $data=array(
                    'id'=>$id_eps,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>3,
                    'url'=>$eps_documento,
                    'descripcion'=>$eps_nombre,
                    'id_clase_entidad'=>$eps_activo,
                    'id_estado_entidad'=>$eps_clase);
                $this->model_casting->update_documentos_actor($data);
                // FONDO PENSIONES
                $pensiones_nombre = $this->input->post('pensiones_nombre');
                $pensiones_activo = $this->input->post('pensiones_activo');
                $pensiones_clase = $this->input->post('pensiones_clase');
                $pensiones_documento ='';
                $id_pensiones = $this->input->post('id_pensiones');

                if($_FILES['pensiones_documento']["tmp_name"]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                    $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']['name']);
                    $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                }else{
                    $pensiones_documento = $documentos_actor[3]->url;
                }
                if(!$pensiones_activo){
                  $pensiones_activo=null;
                }
                if(!$pensiones_clase){
                  $pensiones_clase=null;
                }
                $data=array(
                    'id'=>$id_pensiones,
                    'id_actor'=>$idactor,
                    'id_tipo_documentacion'=>4,
                    'url'=>$pensiones_documento,
                    'descripcion'=>$pensiones_nombre,
                    'id_clase_entidad'=>$pensiones_activo,
                    'id_estado_entidad'=>$pensiones_clase
                );
                $this->model_casting->update_documentos_actor($data);
                //FIN ACTUALIZACION DE DOCUMENTOS
            }
            redirect($this->lang->lang().'/casting/detalle_solicitud/'.$id_solicitud);
        }

    }

    public function insert_solicitud_otro_si(){
        $id_solicitud_anexa = $this->input->post('id_solicitud_anexa');
        $mes_inicio = $this->input->post('mes_inicio');
        if($mes_inicio){
            $mes_inicio=date("Y-m-d",strtotime('01'.$mes_inicio));
        }else{
            $mes_inicio=null;
        }

        $solicitud = $this->model_casting->solicitud_id($id_solicitud_anexa);
        $actor = $this->model_casting->actor_id($solicitud[0]->id_actor);
        $condiciones = "";
        if($this->input->post('valida_condiciones')){
            $condiciones = $this->input->post('condiciones_especiales');
        }

        if($this->input->post('valida_completo')==1){
            /*VALIDACION EXTRANJERTO*/
            if($actor[0]->id_nacionalidad!=13){
                $estado = 19;
            }else{
               $estado = 2; 
            }
        }else{
            $estado = 1;
        }
        if(($solicitud[0]->honorarios!=str_replace(',', '', $this->input->post('honorarios_otro_si')))  and 
            (date("Y-m-d",strtotime($solicitud[0]->fecha_final))!=date("Y-m-d",strtotime($this->input->post('fecha_final_otro_si'))))){
            $condiciones = "Cambio honorario y fecha terminacion contrato";
        }
        $data = array(
            'id_actor' => $solicitud[0]->id_actor, 
            'area_solicitante'=> $solicitud[0]->id_area,
            'id_lugar_servicio'=> $solicitud[0]->id_lugar,
            'id_usuario' =>$this-> session->userdata('id_pruduction_suite'), 
            'id_objeto_contrato'=> $solicitud[0]->id_objeto,
            'fecha_inicio'=>$this->fechaFormat($solicitud[0]->fecha_inicio),
            'fecha_final'=>$this->fechaFormat($solicitud[0]->fecha_final),
            'fecha_final_otro_si'=>$this->fechaFormat($this->input->post('fecha_final_otro_si')),
            'honorarios'=>$solicitud[0]->honorarios,
            'honorarios_letras'=>$solicitud[0]->honorarios_letras,
            'honorarios_otro_si'=>str_replace(',', '', $this->input->post('honorarios_otro_si')),
            'honorarios_letras_otro_si'=>$this->input->post('honorarios_letras_otro_si'),
            'id_forma_pago'=>$solicitud[0]->id_forma,
            'condiciones_especiales'=>$condiciones,
            'id_solicitud_anexa' => $id_solicitud_anexa,
            'mes_otro_si'=> $mes_inicio,
            'id_estado'=>$estado,
            'fecha_creacion'=>date("Y-m-d"),
            'tipo'=>2,
            'razon_otro_si' => $this->input->post('razon_otro_si'),
            'id_tipo_moneda'=>$solicitud[0]->id_tipo_moneda,
        );

        $this->model_casting->insert_solicitud($data);
        $id_solicitud = mysql_insert_id();

        //INSERCION APROBACIONES
        $dataEstado = array(
            'id_solicitud' => $id_solicitud, 
            'id_estado' => $estado,
            'fecha_aprobacion' => date("Y-m-d H:i:s"),
            'id_usuario' => $this->session->userdata('id_pruduction_suite'),
            'activo'=>1,
            'notas'=>''
        );
        $this->model_casting->insert_aprobacion_solicitud($dataEstado);

        if($this->input->post('valida_completo')==1){
            $dataEstado = array(
                'id_solicitud' => $id_solicitud, 
                'id_estado' => 1,
                'fecha_aprobacion' => date("Y-m-d H:i:s"),
                'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                'activo'=>0,
                'notas'=>''
            );
            $this->model_casting->insert_aprobacion_solicitud($dataEstado);
        }
        // FIN INSERCION APROBACIONES

        //INSERCION DE PERSONAJES
        $elementos_solicitud_anexa = $this->model_casting->elementos_solicitud_anexa($solicitud[0]->id_solicitud);
        foreach ($elementos_solicitud_anexa  as $id_elemento) {
            $dataElemento = array(
                'id_solicitud' => $id_solicitud, 
                'id_elemento' => $id_elemento->id_elemento,
            );
            $this->model_casting->insert_solicitud_elemento($dataElemento);
        }
        //INSERCION DE PERSONAJES
            

        //INSERCION DOCUMENTOS SOLICITUD
        $numeros_documentos = $this->input->post('documento_numero');
        for ($i=0; $i < count($numeros_documentos); $i++) {
            $valida = 0;
            $documento_solicitud = "";
            if($_FILES['documento_solicitud']['error'][0]==0){ 
                if($_FILES['documento_solicitud']["tmp_name"][$i]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['documento_solicitud']["tmp_name"][$i];
                    $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$id_solicitud.'_'.$i.$this->extension_archivo($_FILES['documento_solicitud']['name'][$i]);
                    $documento_solicitud =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                    ++$valida;
                }
            }

            if($valida>0){
                $data=array(
                    'id_solicitud'=>$id_solicitud,  
                    'documento'=>$documento_solicitud,
                    'descripcion'=>$numeros_documentos[$i],
                    'fecha' => date('Y-m-d H:i:s')
                );
                $this->model_casting->insert_documento_solcitud($data);
            }

        }
        //FIN INSERCION DOCUMENTOS SOLICITUD

        //INSERSION COMENTARIOS
        $idusuario = $this->session->userdata('id_pruduction_suite');
        $comentario = $this->input->post('notas_solicitud');
        if($comentario!=""){
            $data = array(
                'id_solicitud' => $id_solicitud,
                'id_usuario' => $idusuario, 
                'comentario' => $comentario,
                'fecha' => date('Y-m-d H:i:s')
            );
            $this->model_casting->guardar_comentario_solicitud($data);
        }
        //FIN INSERSION DOCUMENTOS

        
        if((strtotime($this->input->post('fecha_fecha')) - strtotime($this->input->post('fecha_inicio')))>15){
            //ACTUALIZACION DE DOCUMENTOS
            $documentos_actor = $this->model_casting->documentos_actor($actor[0]->id);
            $idactor = $actor[0]->id;
            // ARL
            $arl_activo = $this->input->post('arl_activo');
            $arl_clase = $this->input->post('arl_clase');
            $arl_nombre = $this->input->post('arl_nombre');
            $arl_documento='';
            $id_arl = $this->input->post('id_arl');
            if($_FILES['arl_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_ARL'.$this->extension_archivo($_FILES['arl_documento']['name']);
                $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $arl_documento = $documentos_actor[1]->url;
            }
            if(!$arl_activo){
              $arl_activo=null;
            }
            if(!$arl_clase){
              $arl_clase=null;
            }
            $data=array(
                'id'=>$id_arl,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>2,
                'url'=>$arl_documento,
                'descripcion'=>$arl_nombre,
                'id_clase_entidad'=>$arl_activo,
                'id_estado_entidad'=>$arl_clase);
            $this->model_casting->update_documentos_actor($data);

            // EPS
            $eps_nombre = $this->input->post('eps_nombre');
            $eps_activo = $this->input->post('eps_activo');
            $eps_clase = $this->input->post('eps_clase');
            $id_eps = $this->input->post('id_eps');
            $eps_documento='';
            
            if($_FILES['eps_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_EPS'.$this->extension_archivo($_FILES['eps_documento']['name']);
                $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $eps_documento = $documentos_actor[2]->url;
            }
            if(!$eps_activo){
              $eps_activo=null;
            }
            if(!$eps_clase){
              $eps_clase=null;
            }

            $data=array(
                'id'=>$id_eps,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>3,
                'url'=>$eps_documento,
                'descripcion'=>$eps_nombre,
                'id_clase_entidad'=>$eps_activo,
                'id_estado_entidad'=>$eps_clase
            );
            $this->model_casting->update_documentos_actor($data);
            // FONDO PENSIONES
            $pensiones_nombre = $this->input->post('pensiones_nombre');
            $pensiones_activo = $this->input->post('pensiones_activo');
            $pensiones_clase = $this->input->post('pensiones_clase');
            $pensiones_documento ='';
            $id_pensiones = $this->input->post('id_pensiones');

            if($_FILES['pensiones_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']['name']);
                $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $pensiones_documento = $documentos_actor[3]->url;
            }
            if(!$pensiones_activo){
              $pensiones_activo=null;
            }
            if(!$pensiones_clase){
              $pensiones_clase=null;
            }
            $data=array(
                'id'=>$id_pensiones,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>4,
                'url'=>$pensiones_documento,
                'descripcion'=>$pensiones_nombre,
                'id_clase_entidad'=>$pensiones_activo,
                'id_estado_entidad'=>$pensiones_clase
            );
            $this->model_casting->update_documentos_actor($data);
            //FIN ACTUALIZACION DE DOCUMENTOS
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$id_solicitud);
    }


    public function update_solicitud_otro_si(){
        $id_solicitud = $this->input->post('id_solicitud');
        $mes_inicio = $this->input->post('mes_inicio');
        if($mes_inicio){
            $mes_inicio=date("Y-m-d",strtotime('01'.$mes_inicio));
        }else{
            $mes_inicio=null;
        }

        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $actor = $this->model_casting->actor_id($solicitud[0]->id_actor);
        $condiciones = "";
        if($this->input->post('valida_condiciones')){
            $condiciones = $this->input->post('condiciones_especiales');
        }

        if($this->input->post('valida_completo')==1){
            /*VALIDACION EXTRANJERTO*/
            if($actor[0]->id_nacionalidad!=13){
                $estado = 19;
            }else{
               $estado = 2; 
            }
        }else{
            $estado = 1;
        }
        if(($solicitud[0]->honorarios!=str_replace(',', '', $this->input->post('honorarios_otro_si')))  and 
            (date("Y-m-d",strtotime($solicitud[0]->fecha_final))!=date("Y-m-d",strtotime($this->input->post('fecha_final_otro_si'))))){
            $condiciones = "Cambio honorario y fecha terminacion contrato";
        }
        $data = array(
            'id' => $id_solicitud,
            'id_actor' => $solicitud[0]->id_actor, 
            'area_solicitante'=> $solicitud[0]->id_area,
            'id_lugar_servicio'=> $solicitud[0]->id_lugar,
            'id_usuario' =>$this-> session->userdata('id_pruduction_suite'), 
            'id_objeto_contrato'=> $solicitud[0]->id_objeto,
            'fecha_inicio'=>$this->fechaFormat($solicitud[0]->fecha_inicio),
            'fecha_final'=>$this->fechaFormat($solicitud[0]->fecha_final),
            'fecha_final_otro_si'=>$this->fechaFormat($this->input->post('fecha_final_otro_si')),
            'honorarios'=>$solicitud[0]->honorarios,
            'honorarios_letras'=>$solicitud[0]->honorarios_letras,
            'honorarios_otro_si'=>str_replace(',', '', $this->input->post('honorarios_otro_si')),
            'honorarios_letras_otro_si'=>$this->input->post('honorarios_letras_otro_si'),
            'id_forma_pago'=>$solicitud[0]->id_forma,
            'condiciones_especiales'=>$condiciones,
            'mes_otro_si'=> $mes_inicio,
            'fecha_creacion'=>date("Y-m-d"),
            'tipo'=>2,
            'razon_otro_si' => $this->input->post('razon_otro_si'),
            'id_tipo_moneda'=>$solicitud[0]->id_tipo_moneda,
        );

        $this->model_casting->update_solicitud($data);



        if($this->input->post('valida_completo')==1){
            $dataEstado = array(
                'id_solicitud' => $id_solicitud, 
                'id_estado' => 1,
                'fecha_aprobacion' => date("Y-m-d H:i:s"),
                'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                'activo'=>0,
                'notas'=>''
            );
            $this->model_casting->insert_aprobacion_solicitud($dataEstado);
        }
        // FIN INSERCION APROBACIONES


            

        //INSERCION DOCUMENTOS SOLICITUD
        $numeros_documentos = $this->input->post('documento_numero');
        for ($i=0; $i < count($numeros_documentos); $i++) {
            $valida = 0;
            $documento_solicitud = "";
            if($_FILES['documento_solicitud']['error'][0]==0){ 
                if($_FILES['documento_solicitud']["tmp_name"][$i]){
                    $rutaServidor="images/documentos_actor";
                    $rutaTemporal= $_FILES['documento_solicitud']["tmp_name"][$i];
                    $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$id_solicitud.'_'.$i.$this->extension_archivo($_FILES['documento_solicitud']['name'][$i]);
                    $documento_solicitud =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                    move_uploaded_file($rutaTemporal, $rutaDestino); 
                    ++$valida;
                }
            }

            if($valida>0){
                $data=array(
                    'id_solicitud'=>$id_solicitud,  
                    'documento'=>$documento_solicitud,
                    'descripcion'=>$numeros_documentos[$i],
                    'fecha' => date('Y-m-d H:i:s')
                );
                $this->model_casting->insert_documento_solcitud($data);
            }

        }
        //FIN INSERCION DOCUMENTOS SOLICITUD

        //INSERSION COMENTARIOS
        $idusuario = $this->session->userdata('id_pruduction_suite');
        $comentario = $this->input->post('notas_solicitud');
        if($comentario!=""){
            $data = array(
                'id_solicitud' => $id_solicitud,
                'id_usuario' => $idusuario, 
                'comentario' => $comentario,
                'fecha' => date('Y-m-d H:i:s')
            );
            $this->model_casting->guardar_comentario_solicitud($data);
        }
        //FIN INSERSION DOCUMENTOS

        
        if((strtotime($this->input->post('fecha_fecha')) - strtotime($this->input->post('fecha_inicio')))>15){
            //ACTUALIZACION DE DOCUMENTOS
            $documentos_actor = $this->model_casting->documentos_actor($actor[0]->id);
            $idactor = $actor[0]->id;
            // ARL
            $arl_activo = $this->input->post('arl_activo');
            $arl_clase = $this->input->post('arl_clase');
            $arl_nombre = $this->input->post('arl_nombre');
            $arl_documento='';
            $id_arl = $this->input->post('id_arl');
            if($_FILES['arl_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['arl_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_ARL'.$this->extension_archivo($_FILES['arl_documento']['name']);
                $arl_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $arl_documento = $documentos_actor[1]->url;
            }
            if(!$arl_activo){
              $arl_activo=null;
            }
            if(!$arl_clase){
              $arl_clase=null;
            }
            $data=array(
                'id'=>$id_arl,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>2,
                'url'=>$arl_documento,
                'descripcion'=>$arl_nombre,
                'id_clase_entidad'=>$arl_activo,
                'id_estado_entidad'=>$arl_clase);
            $this->model_casting->update_documentos_actor($data);

            // EPS
            $eps_nombre = $this->input->post('eps_nombre');
            $eps_activo = $this->input->post('eps_activo');
            $eps_clase = $this->input->post('eps_clase');
            $id_eps = $this->input->post('id_eps');
            $eps_documento='';
            
            if($_FILES['eps_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['eps_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_EPS'.$this->extension_archivo($_FILES['eps_documento']['name']);
                $eps_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $eps_documento = $documentos_actor[2]->url;
            }
            if(!$eps_activo){
              $eps_activo=null;
            }
            if(!$eps_clase){
              $eps_clase=null;
            }

            $data=array(
                'id'=>$id_eps,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>3,
                'url'=>$eps_documento,
                'descripcion'=>$eps_nombre,
                'id_clase_entidad'=>$eps_activo,
                'id_estado_entidad'=>$eps_clase
            );
            $this->model_casting->update_documentos_actor($data);
            // FONDO PENSIONES
            $pensiones_nombre = $this->input->post('pensiones_nombre');
            $pensiones_activo = $this->input->post('pensiones_activo');
            $pensiones_clase = $this->input->post('pensiones_clase');
            $pensiones_documento ='';
            $id_pensiones = $this->input->post('id_pensiones');

            if($_FILES['pensiones_documento']["tmp_name"]){
                $rutaServidor="images/documentos_actor";
                $rutaTemporal= $_FILES['pensiones_documento']["tmp_name"];
                $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_PENSIONES'.$this->extension_archivo($_FILES['pensiones_documento']['name']);
                $pensiones_documento =  $rutaDestino= $rutaServidor.'/'.$nombreimage;
                move_uploaded_file($rutaTemporal, $rutaDestino); 
            }else{
                $pensiones_documento = $documentos_actor[3]->url;
            }
            if(!$pensiones_activo){
              $pensiones_activo=null;
            }
            if(!$pensiones_clase){
              $pensiones_clase=null;
            }
            $data=array(
                'id'=>$id_pensiones,
                'id_actor'=>$idactor,
                'id_tipo_documentacion'=>4,
                'url'=>$pensiones_documento,
                'descripcion'=>$pensiones_nombre,
                'id_clase_entidad'=>$pensiones_activo,
                'id_estado_entidad'=>$pensiones_clase
            );
            $this->model_casting->update_documentos_actor($data);
            //FIN ACTUALIZACION DE DOCUMENTOS
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$id_solicitud);
    }

    public function buscar_personajes(){
        $idrol = $_POST['idrol'];
        $idproduccion = $_POST['idproduccion'];
        $personajes= $this->model_casting->get_personajes_no_uso($idproduccion,$idrol);
        $data['personajes'] = $personajes;
        echo json_encode($data);
    }

    public function buscar_manager(){
        $idmanager = $_POST['idmanager'];
        $manager = $this->model_casting->manager_id($idmanager );
        $data['manager'] = $manager;
        echo json_encode($data);
    }

    public function solicitudes(){
       $sql = "";
       $idproduccion="";

       

        if($this->input->post('estados_solicitud')){
            $estados_solicitud=array('');
            $estados = $this->input->post('estados_solicitud');
            $sql.=" AND ( ";
            for ($i=0; $i < count($this->input->post('estados_solicitud')); $i++){ 
                if($i==0){
                    if($estados[$i]==4){
                        $sql.="  solicitudes.id_estado =4 OR  solicitudes.id_estado =7 OR solicitudes.id_estado =8 OR solicitudes.id_estado =9 ";
                    }else{
                        $sql.="  solicitudes.id_estado = ".$estados[$i];    
                    }
                }else{
                    if($estados[$i]==4){
                        $sql.=" OR solicitudes.id_estado =4 OR  solicitudes.id_estado =7 OR solicitudes.id_estado =8 OR solicitudes.id_estado =9 ";
                    }else{
                        $sql.=" OR solicitudes.id_estado = ".$estados[$i];    
                    }
                    
                }
                array_push($estados_solicitud,$estados[$i]);
            }
            $sql.=" ) ";
        }else{
          $estados_solicitud=array(1,2,18,3,4,5,7,8,9,10,17,12,16,20);
        }
        

        if($this->input->post('produccion')){
            $sql.=" AND produccion.id = ".$this->input->post('produccion');
            $idproduccion = $this->input->post('produccion');
        }
        
        $estado_pruduccion='';

        if($this->input->post('produccion_estado')){
            if($this->input->post('produccion_estado')==1){
                  $sql.=" AND produccion.estado = 1";
                  $estado_pruduccion = 1;
            }elseif($this->input->post('produccion_estado')==2){
                   $sql.=" AND produccion.estado = 2";
                  $estado_pruduccion = 2;
            }
            
        }

        $otro_si = "";
        if($this->input->post('otro_si')==1){
            $sql.=" AND solicitudes.tipo = 1 ";
            $otro_si = "1";
        }elseif($this->input->post('otro_si')==2){
            $sql.=" AND solicitudes.tipo = 2 ";
            $otro_si = "2";
        }


        $id_user = $this->session->userdata('id_pruduction_suite');
        $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];
        $solicitudes="";

        
        
     
        if ($tipo_usuario==1) {
             
             if(!$this->input->post('estados_solicitud')){
               $sql.=' and solicitudes.id_estado!=11';    
             }
            
            $solicitudes = $this->model_casting->get_all_solicitudes($sql);

        }elseif ($tipo_usuario==4 OR ($tipo_usuario==5 AND $tipo_rol==8) OR $tipo_usuario==6 OR $tipo_usuario==7 OR $tipo_usuario==8) {
            if($tipo_usuario==5 AND $tipo_rol==8){
                $tipo_usuario = 4;
            }
            //$solicitudes = $this->model_casting->get_solicitudes_tipo_usuario($tipo_usuario,$sql);
            $solicitudes = $this->model_casting->get_solicitudes_tipo_usuario($tipo_usuario,'');
        }elseif($tipo_usuario==5 AND ($tipo_rol==18 OR $tipo_rol==17) ){

            //$solicitudes = $this->model_casting->get_solicitudes_rol_otros($tipo_rol,$sql);
            $solicitudes = $this->model_casting->get_solicitudes_rol_otros($tipo_rol,'');
            
        }


        if(!$this->input->post('estados_solicitud')){
               $sql.=' and solicitudes.id_estado!=11';    
        }
        $todas_solicitudes = $this->model_casting->get_all_solicitudes($sql);
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);
        $iduser = $this->session->userdata('id_pruduction_suite');
        $data_campos=array(
          'id_usuario'=>$iduser,
          'tipo'=>6
        );
        $campos = $this->campos_solicitud();
        $campos_usuario = $this->model_casting->buscar_columnas($data_campos);
        if($campos_usuario==false){
           $campos_usuario = $this->campos_solicitud();
        }else{
          $campos_usuario = explode(',', substr($campos_usuario[0]->campos, 0, -1));
        }


         // FUNCION VERIFICACION DE PERMISOS USUARIOS
        $iduser = $this->session->userdata('id_pruduction_suite');
        $permisos = "read";
        $usuario = $this->model_produccion->user_id($iduser);
        $usuario_roles=$this->model_admin->rolUserId($iduser);
        $coordinado=0;
          if($usuario_roles){
            foreach ($usuario_roles as $usuario_rol) {
                  if($usuario_rol['id_rol_otros']==15 or $usuario_rol['id_rol_otros']==17){
                    $permisos = "write";
                    break;
                  }else{
                    $permisos = "read";
                  }
               if($usuario_rol['id_rol_otros']==18){
                $coordinado=1;
               }    
            }
          }else{
            if($usuario[0]->id_tipoUsuario=='1' or $usuario[0]->id_tipoUsuario=='3' or $usuario[0]->id_tipoUsuario=='4' or $usuario[0]->id_tipoUsuario=='9'){
              $permisos = "write";
            }else{
              $permisos = "read";
            }
          }
        //////////////////////////////////////


        $data['campos'] =  $campos;
        $data['coordinado'] =  $coordinado;
        $data['otro_si'] =  $otro_si;
        $data['campos_usuario'] =  $campos_usuario;
        $data['idproduccion'] =  $idproduccion;
        $data['producciones'] = $producciones;
        $data['solicitudes'] = $solicitudes;
        $data['todas_solicitudes'] = $todas_solicitudes;
        $data['estado_pruduccion'] = $estado_pruduccion;
        $data['estados_solicitud'] = $estados_solicitud;
        $data['permisos'] = $permisos; 
        $data['campos'] =  $campos;
        $data['campos'] =  $campos;
        $data['view']='casting/solicitudes';
        $this->load->view('includes/template',$data);
       
        
    }


    public function campos_solicitud(){
        $campos[0]= "# Solicitud";
        $campos[1]= "Estatus";
        $campos[2]= "Responsable";
        $campos[3]= "Producción";
        $campos[4]= "Personaje";
        $campos[5]= "Actor";
        $campos[6]= "Monto";
        $campos[7]= "Forma pago";
        $campos[8]= "Fecha inicio";
        $campos[9]= "Fecha terminación";
        return $campos;
    }

    public function aprobar_solicitud(){
        $idsolicitud = $_POST['idsolicitud'];
        $validacion=$_POST['validacion'];
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
        switch ($solicitud[0]->id_estado) {
            case 1:
                if ($solicitud[0]->id_nacionalidad!=13 and $solicitud[0]->roles!="1") {
                    $estado = 19;
                }else{
                      $estado = 2;
                }
              
                break;
            case 2:
                $estado = 3;
                break;
            case 4:
                $estado = 3;
                break;
            case 5:
                $estado = 20;
                break;
            case 6:
                $estado = 20;
                break;
            case 7:
                if ($solicitud[0]->id_nacionalidad!=13) {
                    $estado = 19;
                }else{
                    $estado = 2;
                }
                break;
            case 8:
                if ($solicitud[0]->id_nacionalidad!=13) {
                    $estado = 19;
                }else{
                    $estado = 2;
                }
                break;
            case 9:
                if ($solicitud[0]->id_nacionalidad!=13) {
                    $estado = 19;
                }else{
                    $estado = 2;
                }
                break;
            case 3:

                if($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1" OR $solicitud[0]->id_forma_pago==1 OR $solicitud[0]->id_tipo_moneda==2 and $solicitud['0']->id_nacionalidad==13){
                    echo "roles: ".$solicitud[0]->roles." id_nacionalidad". $solicitud[0]->id_forma_pago;
                    $estado = 18;
                }else{

                    $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,13,3);
                    $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,14,3);
                    if($aprobacion_juridica AND $aprobacion_finanzas){
                      $estado = 5;    
                    }elseif($solicitud[0]->edad<18){
                        
                        $estado = 5;    
                    }elseif($this->validacion_caso_especial($solicitud[0])==0){
                        $estado = 5;    
                    }
                  
                    
                }
                break;
            case 10:
                $estado = 17;
                break;
            case 15:
                $estado = 2;
                break;
            case 17:
                $estado = 11;
                break;
            case 18:
                $estado = 6;
                break;
            case 19:
                if ($solicitud[0]->id_nacionalidad!=13 or $solicitud[0]->condiciones_especiales!="") {
                    $estado = 3;
                }else{
                    $estado = 2;
                }
                break;
            
            default:
                $estado = $solicitud[0]->id_estado;
                break;  
        }


        $data ="";
           // if ($solicitud[0]->id_estado==18) {
            if ($estado==18) {
            $id_user = $this->session->userdata('id_pruduction_suite');
            $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
            $user=$this->model_admin->rolUserId($id_user);
            $tipo_rol=$user['0']['id_rol_otros'];

            $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,13,3);
            $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,14,3);
            //echo "tipo_usuario: ".$tipo_usuario." validacion:".$validacion." aprobacion_juridica.".$aprobacion_juridica;
            //if(($tipo_usuario == 7 OR $tipo_usuario == 1) AND !$aprobacion_juridica AND $_POST['validacion']==1){
            if(($tipo_usuario == 7 OR $validacion == 1 ) AND !$aprobacion_juridica){
                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 13,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }

            //if(($tipo_usuario == 8 OR $tipo_usuario == 1) AND !$aprobacion_finanzas AND $_POST['validacion']==2){
            if(($tipo_usuario == 8 OR $validacion == 2 ) AND !$aprobacion_finanzas ){

                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 14,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }

            $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,13,3);
            $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,14,3);
           // echo "aprobacion_juridica ".$aprobacion_juridica." aprobacion_finanzas".$aprobacion_finanzas;
            if($aprobacion_juridica AND $aprobacion_finanzas){

                $data = array(
                    'id' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 5
                );
                $this->model_casting->update_solicitud($data);
                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 5,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }

        }else if ($estado==20) {
            $id_user = $this->session->userdata('id_pruduction_suite');
            $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
            $user=$this->model_admin->rolUserId($id_user);
            $tipo_rol=$user['0']['id_rol_otros'];

            $aprobacion_firma = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,10,3);
            $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,17,3);


            if(($tipo_usuario == 7 OR $tipo_usuario == 1 OR $tipo_rol==18) AND !$aprobacion_firma AND $_POST['validacion']=="10"){
                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 10,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);

            }

            if(($tipo_usuario == 7 OR $tipo_usuario == 1 OR $tipo_rol==18) AND !$aprobacion_documentos AND $_POST['validacion']=="17"){
                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 17,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);

            }

            $aprobacion_firma = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,10,3);
            $aprobacion_documentos = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,17,3);

            if($aprobacion_firma AND $aprobacion_documentos){
                $data = array(
                    'id' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 11
                );
                $this->model_casting->update_solicitud($data);
                $dataEstado = array(
                    'id_solicitud' => $solicitud[0]->id_solicitud, 
                    'id_estado' => 11,
                    'fecha_aprobacion' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                    'activo'=>0,
                    'notas'=>''
                );
                $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
            }else{
                if (!$aprobacion_firma AND !$aprobacion_documentos AND !$this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,20,3)) {
                    $data = array(
                        'id' => $solicitud[0]->id_solicitud, 
                        'id_estado' => $estado
                    );
                    $this->model_casting->update_solicitud($data);

                    $dataEstado = array(
                        'id_solicitud' => $solicitud[0]->id_solicitud, 
                        'id_estado' => $estado,
                        'fecha_aprobacion' => date("Y-m-d H:i:s"),
                        'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                        'activo'=>0,
                        'notas'=>''
                    );
                    $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
                }
            }
        }else{
            $data = array(
                'id' => $solicitud[0]->id_solicitud, 
                'id_estado' => $estado
            );
            $this->model_casting->update_solicitud($data);

            $dataEstado = array(
                'id_solicitud' => $solicitud[0]->id_solicitud, 
                'id_estado' => $estado,
                'fecha_aprobacion' => date("Y-m-d H:i:s"),
                'id_usuario' => $this->session->userdata('id_pruduction_suite'),
                'activo'=>0,
                'notas'=>''
            );
            $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
        }

        echo json_encode($data);
    }

    public function rechazar_solicitud(){
        $estado = $_POST['idestado'];
        $idsolicitud = $_POST['idsolicitud'];
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
        
        if($estado==9){
            $data = array(
            'id' => $solicitud[0]->id_solicitud, 
            'id_estado' => $estado,
            'contrato'=>0
            );
        }else{
            $data = array(
            'id' => $solicitud[0]->id_solicitud, 
            'id_estado' => $estado
            );
        }
        
        $this->model_casting->update_solicitud($data);

        $data = array(
            'id_solicitud' => $solicitud[0]->id_solicitud, 
            'activo' => 3
        );
        $this->model_casting->update_aprocaciones($data,$estado);

        $dataEstado = array(
            'id_solicitud' => $solicitud[0]->id_solicitud, 
            'id_estado' => $estado,
            'fecha_aprobacion' => date("Y-m-d H:i:s"),
            'id_usuario' => $this->session->userdata('id_pruduction_suite'),
            'activo'=>0,
            'notas'=>$_POST['razon']
        );
        $data['resultado'] =  $this->model_casting->insert_aprobacion_solicitud($dataEstado);
        echo json_encode($data);
    }

    public function cancelar_solicitud($idsolicitud){
        $data = array(
            'id' => $solicitud[0]->id_solicitud, 
            'id_estado' => 12
        );
        $this->model_casting->update_solicitud($data);
        $dataEstado = array(
            'id_solicitud' => $solicitud[0]->id_solicitud, 
            'id_estado' => 12,
            'fecha_aprobacion' => date("Y-m-d H:i:s"),
            'id_usuario' => $this->session->userdata('id_pruduction_suite'),
            'activo'=>0
        );
        $this->model_casting->insert_aprobacion_solicitud($dataEstado);
        redirect($this->lang->lang().'/casting/solicitudes');
    }


    /*MODULO PERSONAJES*/
    public function personajes($idproduccion="null",$rol="null",$date_from="null",$date_to="null"){
        $id_user=$this->session->userdata('id_pruduction_suite');
        $tipo_user=$this->session->userdata('tipo_pruduction_suite');
        $sql="";
        $produccion=false;
        $personajes_produccion=false;

        if($tipo_user=='3' or $tipo_user=='1' OR $tipo_user=='2'){
            $producciones=$this->model_produccion->producciones_all($sql);
        }else{
            $producciones=$this->model_produccion->producciones_user($id_user,$sql);
        }

        $sql_p = "";

        if($rol!="null"){
            $sql_p .= " AND elemento.rol = ".$rol." ";
        }

        if($date_to!="null" AND $date_from!="null"){
            $sql_p .= " AND (SELECT MIN(plan_diario.fecha_inicio) FROM plan_diario
                                        INNER JOIN plan_diario_has_escenas_has_unidades pd ON pd.id_plan_diario = plan_diario.id
                                        INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = pd.id_escena
                                        WHERE escenas_has_elementos.id_elemento = elemento.id  ) BETWEEN '".date('Y-m-d', strtotime($date_from))."' AND '".date('Y-m-d', strtotime($date_to))."' ";
        }

        if($idproduccion!="null"){
            $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
            $personajes_produccion = $this->model_casting->personajes_produccion($idproduccion, $sql_p);
        }
        echo "SWL:".$sql_p;
        /*COLUMNAS USUARIO*/
        $data_campos=array(
          'id_usuario'=>$id_user,
          'tipo'=>5
        );
        $campos = $this->campos_personajes();
        $campos_usuario = $this->model_casting->buscar_columnas($data_campos);
        if($campos_usuario==false){
          $campos_usuario = $this->campos_personajes();
        }else{
          $campos_usuario = explode(',', substr($campos_usuario[0]->campos, 0, -1));
        }
        /*COLUMNAS USUARIO*/  

        $roles = $this->model_escenas->rol_actores_elementos();

         // FUNCION VERIFICACION DE PERMISOS USUARIOS
        $iduser = $this->session->userdata('id_pruduction_suite');
        $permisos = "read";
        $usuario = $this->model_produccion->user_id($iduser);
        $usuario_roles=$this->model_admin->rolUserId($iduser);
          if($usuario_roles){
            foreach ($usuario_roles as $usuario_rol) {
              if($usuario_rol['id_rol_otros']==15 or $usuario_rol['id_rol_otros']==17){
                $permisos = "write";
                break;
              }else{
                $permisos = "read";
              }
            }
          }else{
            if($usuario[0]->id_tipoUsuario=='1' or $usuario[0]->id_tipoUsuario=='3' or $usuario[0]->id_tipoUsuario=='4' or $usuario[0]->id_tipoUsuario=='9'){
              $permisos = "write";
            }else{
              $permisos = "read";
            }
          }
        //////////////////////////////////////

        $data['roles'] = $roles;
        $data['idrol'] = $rol;
        $data['date_to'] = $date_to;
        $data['date_from'] = $date_from;
        $data['campos'] =  $campos;
        $data['campos_usuario'] =  $campos_usuario;
        $data['idproduccion'] = $idproduccion;
        $data['producciones'] = $producciones;
        $data['produccion'] = $produccion;
        $data['personajes_produccion'] = $personajes_produccion;
        $data['permisos'] = $permisos;
        $data['view']='casting/personajes';
        $this->load->view('includes/template',$data); 
    }

    public function anadir_contrato($id_solicitud){
        $alineado_justificado = " style=\"text-align:justify;\" ";
        $alineado_centrado = " style=\"text-align:center;\" ";
        $lista = " style=\"margin-left:2%;\" ";
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","");

        $dias = array("","Primero","Dos","Tres","Cuatro","Cinco","Séis","Siete","Ocho","Nueve","Diez","Once","Doce","Trece",
            "Catorce","Quince","Dieciséis","Diecisiete", "Dieciocho", "Diecinueve", "Veinte", "Veintiuno", "Veintidós", "Veintitres", "Veinticuatro", "Veinticinco",
            "Veintiséis", "Veintisiete", "Veintiocho", "Veintinueve", "Treinta", "Treinta y uno");

        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $fecha_final_otro_si=$solicitud[0]->fecha_final_otro_si;
        //echo $this->db->last_query();
        $contrato_select = false;
        $responsables_contrato = $this->model_casting->responsables_contrato();

        $pasaporte = $this->model_casting->documento_extranjero($solicitud[0]->id_actor,'5');
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
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") de ".$meses[date("n")]." de ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }

        if($solicitud[0]->contrato!="" and $solicitud[0]->contrato!=0){
            $contrato_select = $solicitud[0]->contrato;
        }else{
           
            if($solicitud[0]->tipo==2){

                $solicitud_anexa = $this->model_casting->solicitud_id($solicitud[0]->id_solicitud_anexa);
                
                //PERSONA NATURAL
                if(!$solicitud[0]->nit_sociedad){
                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 24;
                        }
                        if ($solicitud[0]->id_forma == 2 and ($fecha_final_otro_si=='' or $fecha_final_otro_si==' ' or $fecha_final_otro_si==null or $fecha_final_otro_si=='0000-00-00')) {
                           $contrato_select = 21;
                        }
                   // }else{
                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 23;
                        }
                   // }
                }

                //PERSONA JURIDICA

                if($solicitud[0]->nit_sociedad){

                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 19;
                        }
                        if ($solicitud[0]->id_forma == 2 and ($fecha_final_otro_si=='' or $fecha_final_otro_si==' ' or $fecha_final_otro_si==null or $fecha_final_otro_si=='0000-00-00')) {
                           $contrato_select = 22;
                        }

                   // }else{

                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 20;
                        }

                   // }
                }

                //MENOR DE EDAD

                if($solicitud[0]->edad<18){
                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 33;

                        }
                        if ($solicitud[0]->id_forma == 2 and ($fecha_final_otro_si=='' or $fecha_final_otro_si=='' or $fecha_final_otro_si==null or $fecha_final_otro_si=='0000-00-00' or $fecha_final_otro_si==$solicitud[0]->fecha_final)) {
                           $contrato_select = 32;
                        }

                    //}else{
                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 31;
                        }
                    
                   // }
                }

   


                //EXTRANJERO

                if ($solicitud[0]->id_nacionalidad!=13) {
                    if(!$solicitud[0]->nit_sociedad){
                       //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                            if ($solicitud[0]->id_forma == 5) {
                               $contrato_select = 28;
                            }
                            if ($solicitud[0]->id_forma == 2 and ($fecha_final_otro_si=='' or $fecha_final_otro_si=='' or $fecha_final_otro_si==null or $fecha_final_otro_si=='0000-00-00')) {
                               $contrato_select = 26;
                            }
                        //}else{
                            //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                            else{
                                $contrato_select = 27;
                            }
                        //}
                    } else{
                       // if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                            if ($solicitud[0]->id_forma == 5) {
                               $contrato_select = 25;
                            }
                            if ($solicitud[0]->id_forma == 2 and ($fecha_final_otro_si=='' or $fecha_final_otro_si=='' or $fecha_final_otro_si==null or $fecha_final_otro_si=='0000-00-00')) {
                               $contrato_select = 29;
                            }
                        //}else{
                            //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                            else{
                                $contrato_select = 30;
                            }
                       // }
                    }
                }

            }else{

                //PERSONA NATURAL
                if(!$solicitud[0]->nit_sociedad){
                    if ($solicitud[0]->id_forma==5) {
                        $contrato_select = 4;
                    }
                    if ($solicitud[0]->id_forma==3) {
                        $contrato_select = 3;
                    }
                    if ($solicitud[0]->id_forma==2) {
                        $contrato_select = 18;

                    }
                }

                //MENOR DE EDAD
                if($solicitud[0]->edad<18){
                    if ($solicitud[0]->id_forma==2) {
                       $contrato_select = 15;
                    }
                    if ($solicitud[0]->id_forma==5) {
                       $contrato_select = 16;
                    }
                }

                //EXTRANJERO
                if ($solicitud[0]->id_nacionalidad!=13) {
                    if($solicitud[0]->nit_sociedad){

                        if ($solicitud[0]->id_forma==5) {
                            if ($solicitud[0]->id_sociedad==17) {
                                $contrato_select = 12;
                            }else{
                                $contrato_select = 9;
                            }
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 10;
                        }
                        if ($solicitud[0]->id_forma==2) {
                            if ($solicitud[0]->id_sociedad==17) {
                                $contrato_select = 14;
                            }else{
                                $contrato_select = 11;
                            }
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 7;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 13;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 8;
                        }
                    }
                    
                }

                //PERSONA JURIDICA
                if($solicitud[0]->nit_sociedad and $solicitud[0]->id_nacionalidad==13){
                     
                    if ($solicitud[0]->id_sociedad==17) {
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 6;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 5;
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 2;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 1;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 17;
                        }
                    }
                }
            }
        }


        $data['responsables_contrato'] = $responsables_contrato; 
        $data['contrato_select'] = $contrato_select;
        $data['pasaporte'] = $pasaporte;
        $data['visa'] = $visa;

        if($solicitud[0]->tipo==2){
             $data['contratos'] = $this->model_casting->get_contratos2();
        }else{
            $data['contratos'] = $this->model_casting->get_contratos1();
        }

        $data['solicitud'] = $solicitud;
        $data['alineado_justificado'] = $alineado_justificado;
        $data['alineado_centrado'] = $alineado_centrado;
        $data['meses'] = $meses;
        $data['dias'] = $dias;
        $data['view']='casting/anadir_contrato';
        $this->load->view('includes/template',$data); 
    }

    public function busqueda_actor(){
        $idactor = $_POST['idactor'];
        $actor = $this->model_casting->actor_id($idactor);
        $actor[0]->cadena_documentos = $this->carga_documentos_solicitud($idactor);
        echo json_encode($actor);
    }

    public function agregar_contrato(){
        $data = array(
            'id' => $this->input->post('idsolicitud'), 
            'contrato' => $this->input->post('contrato'),
            'fecha_contrato_selec' => date('Y-m-d H:i:s'),
            'contrato_personal' => null,
        );
        $this->model_casting->asignar_contrato($data);
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$this->input->post('idsolicitud'));
    }

    public function ver_contrato($id_solicitud){
        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $data['solicitud'] = $solicitud;
        $data['view']='casting/ver_contrato';
        $this->load->view('includes/template',$data); 
    }

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
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") DE ".$meses[date("n")]." DE ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }

        $id_otro_si = "";
        //NUMERACION OTRO SI
        if ($solicitud[0]->id_solicitud_anexa) {
            $id_otro_si = $this->completar_id($solicitud[0]->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);
        }

        $clausula="4";
        //NUMERO CALUSULA
        if ($solicitud[0]->edad<18) {
            $clausula="5";
        }

        $contrato[0]->contrato=str_replace('<span class=hightltght_yellow>".strtoupper($solicitud[0]->nacionalidad)."</span>','<span class=hightltght_yellow>".mb_strtoupper($solicitud[0]->nacionalidad,"UTF-8")."</span>',$contrato[0]->contrato);
        //echo mb_strtoupper($solicitud[0]->nacionalidad,"UTF-8");

        $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span></strong> <strong>', '<strong>', $contrato[0]->contrato);
         
        if($solicitud[0]->tipo==2){

                if ($solicitud[0]->id_tipo_moneda==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('M/CTE','DOLARES', $contrato[0]->contrato);  
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras_otro_si.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras_otro_si."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> PESOS M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }else{

                if ($solicitud[0]->tipo==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras_otro_si.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> M/CTE (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span> ',  $contrato[0]->contrato);
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }


        if ($solicitud[0]->descExt=='PASAPORTE') {
            //$contrato[0]->contrato = str_replace('<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>',
            //'<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span> de <span class=hightltght_yellow><strong>".$solicitud[0]->paisExt."</strong></span>',  $contrato[0]->contrato);
            $contrato[0]->contrato=str_replace('y cédula de extranjería número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>','',  $contrato[0]->contrato);
        }

        if ($solicitud[0]->descExt=='CÉDULA EXTRANJERA') {
            $contrato[0]->contrato=str_replace('pasaporte número <span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->documento)."</strong></span> de nacionalidad <span class=hightltght_yellow>".strtoupper($solicitud[0]->nacionalidad)."</span>  y','',  $contrato[0]->contrato);
        }
        
       /* if($solicitud[0]->tipo_moneda=="DÓLARES" or $solicitud[0]->tipo_moneda=="DOLARES"){
            $contrato[0]->contrato=str_replace('PESOS','DÓLARES',  $contrato[0]->contrato);   
        }*/

        

        //echo $solicitud[0]->honorarios_letras;

        $porciones = explode(" ", $solicitud[0]->honorarios_letras);
        $cambiar=0;
        if($porciones){
            foreach ($porciones as $p) {
                if($p=='mil'){
                     $cambiar=1;
                }
            }
        }
        if ($cambiar ==0) {
            $h=str_replace('millones','millones de',$solicitud[0]->honorarios_letras);
        } else {
            $h=$solicitud[0]->honorarios_letras;
        }

        $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span', 'igual valor y tenor, el día ".$dias[intval(date("d"))]." (<span', $contrato[0]->contrato);
         
        $MTE='';

        if ($solicitud[0]->id_tipo_moneda==1) {
            $MTE=' M/CTE';
        }
        if ($solicitud[0]->tipo==2) {
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras_otro_si.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);
        }else{
           $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);    
        }
        if ($solicitud[0]->tipo==2) {

            $contrato[0]->contrato = str_replace('$"', 'U$"', $contrato[0]->contrato);
        }

        if($solicitud[0]->id_tipo_moneda==2){
            $contrato[0]->contrato = str_replace('$"', 'U$"', $contrato[0]->contrato);   
        }
        //$contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);

        
        if ($solicitud[0]->tipo==2) {
           $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si))-1  ].', $contrato[0]->contrato);
        }else{
           $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final))-1  ].', $contrato[0]->contrato); 
        }
        $contrato[0]->contrato = str_replace('<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.', '<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.<br>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('$meses[date("n",strtotime($solicitud[0]->fecha_inicio)-1)]', '$meses[date("n",strtotime($solicitud[0]->fecha_inicio))-1]', $contrato[0]->contrato);
        
        $contrato[0]->contrato = str_replace('".$dias[intval(date("d"))]." DE ".', '".$dias[intval(date("d"))]." (".date("d").") DE ".', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('valor y tenor, día (<span class=hightltght_yellow>".date("d")."</span>)  DE', 'valor y tenor, día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>) DE', $contrato[0]->contrato);
        
        if ($solicitud[0]->tipo==2) {
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras_otro_si)."</span>', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras)."</span>', $contrato[0]->contrato);
        }
        $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span class=hightltght_yellow>".date("d")."</span>)', 'igual valor y tenor, el día ".$dias[intval(date("d"))]."', $contrato[0]->contrato);
        





        if ($solicitud[0]->tipo_documento!="CÉDULA DE CIUDADANÍA") {
            $contrato[0]->contrato = str_replace('cédula de ciudadanía número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').' número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con '.mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').'  número', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con cédula de ciudadania número', $contrato[0]->contrato);
        }

        $contrato[0]->contrato = str_replace(' el presente documento el día (<span class=hightltght_yellow>".date("d")."</span>)', ' el presente documento el día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>)', $contrato[0]->contrato);

 

        if ($solicitud[0]->id_tipo_moneda!=1) {
           //$contrato[0]->contrato = str_replace('M/CTE', '', $contrato[0]->contrato);
          // $contrato[0]->contrato = str_replace('M/CTE PESOS', 'PESOS M/CTE', $contrato[0]->contrato);
        }

        if ($contrato){ 
            $str = $contrato[0]->contrato;
            @eval("\$str = \"$str\";");
        }

       // echo date("d");

        echo json_encode($str);
    }

    public function buscar_libretos_personaje(){
        $idpersonaje = $_POST['idpersonaje'];
        $libretos = $this->model_casting->buscar_libretos_personaje($idpersonaje);
        $fecha_inicio = $this->model_casting->fecha_inicial($idpersonaje);
        $cantidad_libretos=0;
        if($libretos){
            $cantidad_libretos = count(explode(',', $libretos[0]->libretos));
            $libretos = $libretos[0]->libretos;
        }else{
            $libretos = "";
        }
        $data['fecha_inicio'] = $fecha_inicio;
        $data['libretos'] = $libretos;
        $data['cantidad_libretos'] = $cantidad_libretos;
        echo json_encode($data);
    }


    public function pdf_contrato($id_solicitud){

        $pdf = new PDFCASTING(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setTipoPDF("Contrato");
        $pdf->SetTopMargin('16px');
        $pdf->SetLeftMargin('25px');
        $pdf->SetRightMargin('25px');
        $fontname = $pdf->addTTFfont(base_url().'css/font_calibri/Calibri.ttf', 'TrueTypeUnicode', '', 11);
        $pdf->SetFont(''.$fontname.'', '', 10.5);
        $pdf->AddPage();

        setlocale(LC_TIME, 'spanish'); 
        
        $alineado_justificado = " style=\"text-align:justify;\" ";
        $alineado_centrado = " style=\"text-align:center;\" ";
        $clase_tabla = " class=\"firm_cell\" ";
        //$lista = " style=\"margin-left:2%;\" ";
        $lista = " style=\"margin-left:2%;\" ";
        $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE","");
        $dias = array("", "PRIMERO","DOS","TRES","CUATRO","CINCO","SÉIS","SIETE","OCHO","NUEVE","DIEZ","ONCE","DOCE","TRECE",
            "CATORCE","QUINCE","DIECISÉIS","DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUNO", "VEINTIDÓS", "VEINTITRES", "VEINTICUATRO", "VEINTICINCO",
            "VEINTISÉIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE", "TREINTA", "TREINTA Y UNO");

        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $solicitu_contrato=$solicitud[0]->contrato;
        if($solicitu_contrato==0 or $solicitu_contrato==""){
            $solicitu_contrato=$this->selectContrato($id_solicitud);
            //echo $solicitud[0]->contrato;
        } 
        $contrato = $this->model_casting->contrato_id($solicitu_contrato);
       // $contrato = $this->model_casting->contrato_id(33);
        $tipo = explode(' - ',  $contrato[0]->nombre);

        $clausula="4";
        //NUMERO CALUSULA
        if ($solicitud[0]->edad<18) {
            $clausula="5";
        }

        $nombre_contrato = 'CTO - '.strtoupper($solicitud[0]->produccion).' - TALENTO - '.strtoupper($tipo[1]).' - '.strtoupper($solicitud[0]->forma_pago).' - ';
        if ( strrpos('Jca', $contrato[0]->nombre)) {
            $nombre_contrato .= strtoupper($solicitud[0]->razon_social_sociedad).' - ';
        }
        $nombre_contrato .= strtoupper($solicitud[0]->actor).'.pdf';

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
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") de ".$meses[date("n")]." de ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }

        $id_otro_si = "";
        //NUMERACION OTRO SI
        if ($solicitud[0]->id_solicitud_anexa) {
                $id_otro_si = $this->completar_id($solicitud[0]->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);
            }

        $contrato[0]->contrato=str_replace('<span class=hightltght_yellow>".strtoupper($solicitud[0]->nacionalidad)."</span>','<span class=hightltght_yellow>".mb_strtoupper($solicitud[0]->nacionalidad,"UTF-8")."</span>',$contrato[0]->contrato);
        if($solicitud[0]->tipo==2){
             $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras_otro_si."</span></strong> <strong>', '<strong>', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span></strong> <strong>', '<strong>', $contrato[0]->contrato);
        }
        


        if($solicitud[0]->tipo==2){

                if ($solicitud[0]->id_tipo_moneda==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('M/CTE','DOLARES', $contrato[0]->contrato);  
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras_otro_si.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras_otro_si."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> PESOS M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }else{

                if ($solicitud[0]->id_tipo_moneda==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ',  $contrato[0]->contrato);
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }

        if ($solicitud[0]->descExt=='PASAPORTE') {
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>','<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span> de <span class=hightltght_yellow><strong>".$solicitud[0]->paisExt."</strong></span>',  $contrato[0]->contrato);
        }
           
        
        $porciones = explode(" ", $solicitud[0]->honorarios_letras);
        $cambiar=0;
        if($porciones){
            foreach ($porciones as $p) {
                if($p=='mil'){
                     $cambiar=1;
                }
            }
        }
        if ($cambiar ==0) {
             if($solicitud[0]->tipo==2){
                $h=str_replace('millones','millones de',$solicitud[0]->honorarios_letras_otro_si);
             }else{
               $h=str_replace('millones','millones de',$solicitud[0]->honorarios_letras);   
             }   
            
        } else {
            if($solicitud[0]->tipo==2){
                 $h=$solicitud[0]->honorarios_letras_otro_si;
            }else{
                  $h=$solicitud[0]->honorarios_letras;
            }    
            
        }
        
        if ($solicitud[0]->id_tipo_moneda==1) {
            if($solicitud[0]->tipo==2){
               $h=$solicitud[0]->honorarios_letras_otro_si.' M/CTE';
            }else{
                $h=$solicitud[0]->honorarios_letras.' M/CTE';    
            }
        }
        
         $MTE='';
        if ($solicitud[0]->id_tipo_moneda==1) {
            $MTE=' M/CTE';
        }
        if($solicitud[0]->tipo==2){
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras_otro_si.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);
        }    
        
           //$contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        if ($solicitud[0]->id_tipo_moneda==2) {
            $contrato[0]->contrato = str_replace('$"', 'U$"', $contrato[0]->contrato);
        }

        

        if($solicitud[0]->tipo==2){
            $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras_otro_si.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras_otro_si."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>)',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras_otro_si.', '.strtoupper($solicitud[0]->honorarios_letras_otro_si)." DE ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>)',  $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." DE ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        }
        
        
        //$contrato[0]->contrato = str_replace('<p ".$lista." >', '<table class=\"first\" cellpadding=\"20px\"><tr><td>', $contrato[0]->contrato);
        //$contrato[0]->contrato = str_replace('</p ".$lista." >', '</td></tr></table>', $contrato[0]->contrato);
        
        if($solicitud[0]->tipo==2){
              $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si))-1  ].', $contrato[0]->contrato);
        }else{
              $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final))-1  ].', $contrato[0]->contrato);
        }    
        $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final))-1  ].', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.', '<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.<br>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('$meses[date("n",strtotime($solicitud[0]->fecha_inicio)-1)]', '$meses[date("n",strtotime($solicitud[0]->fecha_inicio))-1]', $contrato[0]->contrato);
        

        $contrato[0]->contrato = str_replace('".$dias[intval(date("d"))]." DE ".', '".$dias[intval(date("d"))]." (".date("d").") DE ".', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('valor y tenor, día (<span class=hightltght_yellow>".date("d")."</span>) DE', 'valor y tenor, día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>) DE', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace(' el presente documento el día (<span class=hightltght_yellow>".date("d")."</span>)', ' el presente documento el día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>)', $contrato[0]->contrato);

        if($solicitud[0]->tipo==2){
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras_otro_si)."</span>', $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span', 'igual valor y tenor, el día ".$dias[intval(date("d"))]." (<span', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras)."</span>', $contrato[0]->contrato);
             $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span', 'igual valor y tenor, el día ".$dias[intval(date("d"))]." (<span', $contrato[0]->contrato);
        }

        if ($solicitud[0]->tipo_documento!="CÉDULA DE CIUDADANÍA") {
            $contrato[0]->contrato = str_replace('cédula de ciudadanía número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').' número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con '.mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').'  número', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con cédula de ciudadania número', $contrato[0]->contrato);
        }

        if ($solicitud[0]->id_tipo_moneda!=1) {
           $contrato[0]->contrato = str_replace('M/CTE', '', $contrato[0]->contrato);
        }


        // ESPECIALES
        //$contrato[0]->contrato .= "<style> .firm_cell{ height: 70px; vertical-align: bottom; }</style>";
        //$contrato[0]->contrato = str_replace('<p ".$lista." >', '<table class=\"first\" cellpadding=\"20px\"><tr><td>', $contrato[0]->contrato);
        //$contrato[0]->contrato = str_replace('</p ".$lista." >', '</td></tr></table>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<br pdf_delete >', '', $contrato[0]->contrato);

        
        if ($contrato){ 

            $str = $contrato[0]->contrato;

            @eval("\$str = \"$str\";");
        
        }
        
       // $str = str_replace('</br>', '<br>', $str);
        //ECHO '<textarea>'.$str.'</textarea>';

        $pdf->writeHTML($str, true, false, true, false, '');
        $pdf->Output($nombre_contrato, 'I');
    }

    public function subir_contrato_firmado(){
        $idsolicitud = $this->input->post('idsolicitud');
        if($_FILES['contrato_firmado']){
            $rutaServidor="images/documentos_actor";
            $rutaTemporal= $_FILES['contrato_firmado']["tmp_name"];
            //$nombreimage = $this->nombreContrato($idsolicitud).'_CONTRATO_FIRMADO'.$this->extension_archivo($_FILES['contrato_firmado']["name"]);
            $nombreimage = $_FILES['contrato_firmado']["name"];
            $nombreimage = str_replace(' ', '_', $nombreimage);
            $nombreimage = $this->quitar_tildes($nombreimage);
            $rutaDestino= $rutaServidor.'/'.$nombreimage;
            move_uploaded_file($rutaTemporal, $rutaDestino); 
            $data=array(
                'id'=>$idsolicitud,  
                'contrato_firmado'=>$rutaDestino,
                'fecha_contrato_firmado'=>date('Y-m-d H:i:s')
            );
            $this->model_casting->insert_contrato_firmado($data);
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function subir_contrato_personalizado(){
        $idsolicitud = $this->input->post('idsolicitud');
        if($_FILES['contrato_personalizado']){
            $rutaServidor="images/documentos_actor";
            $rutaTemporal= $_FILES['contrato_personalizado']["tmp_name"];
            $nombreimage = $this->nombreContrato($idsolicitud).$this->extension_archivo($_FILES['contrato_personalizado']["name"]);
            $nombreimage = str_replace(' ', '_', $nombreimage);
            $nombreimage = $this->quitar_tildes($nombreimage);
            $rutaDestino = $rutaServidor.'/'.$nombreimage;
            move_uploaded_file($rutaTemporal, $rutaDestino); 

            $data=array(
                'id'=>$idsolicitud,  
                'contrato_personal'=>$rutaDestino,
                'contrato'=>null,
                'fecha_contrato' => date('Y-m-d H:i:s')
            );
            $this->model_casting->insert_contrato_firmado($data);
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function nombreContrato($id_solicitud){
        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        if($solicitud[0]->contrato!="" and $solicitud[0]->contrato!=0){
            $contrato_select = $solicitud[0]->contrato;
        }else{
             if($solicitud[0]->edad<18){
                    if ($solicitud[0]->id_forma==2) {
                       $contrato_select = 15;
                    }
                    if ($solicitud[0]->id_forma==5) {
                       $contrato_select = 16;
                    }
                }

                //EXTRANJERO
                if ($solicitud[0]->id_nacionalidad!=13) {
                    if($solicitud[0]->nit_sociedad){

                        if ($solicitud[0]->id_forma==5) {
                            if ($solicitud[0]->id_sociedad==17) {
                                $contrato_select = 12;
                            }else{
                                $contrato_select = 9;
                            }
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 10;
                        }
                        if ($solicitud[0]->id_forma==2) {
                            if ($solicitud[0]->id_sociedad==17) {
                                $contrato_select = 14;
                            }else{
                                $contrato_select = 11;
                            }
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 7;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 13;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 8;
                        }
                    }
                    
                }

            //PERSONA JURIDICA
            if($solicitud[0]->nit_sociedad and $solicitud[0]->id_nacionalidad==13){
                     
                    if ($solicitud[0]->id_sociedad==17) {
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 6;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 5;
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 2;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 1;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 17;
                        }
                    }
                }

            //PERSONA NATURAL
            if(!$solicitud[0]->nit_sociedad){
                    if ($solicitud[0]->id_forma==5) {
                        $contrato_select = 4;
                    }
                    if ($solicitud[0]->id_forma==3) {
                        $contrato_select = 3;
                    }
                    if ($solicitud[0]->id_forma==2) {
                        $contrato_select = 18;

                    }
                }
        }
        $contrato_select;
        $contrato = $this->model_casting->contrato_id($contrato_select);
        $tipo = explode(' - ',  $contrato[0]->nombre);

        $nombre_contrato = 'CTO-'.strtoupper($solicitud[0]->produccion).'-TALENTO-'.strtoupper($tipo[2]).'-'.strtoupper($solicitud[0]->forma_pago).'-';
        if ( strrpos('Jca', $contrato[0]->nombre)) {
            $nombre_contrato .= strtoupper($solicitud[0]->razon_social_sociedad).'-';
        }
        $nombre_contrato .= strtoupper($solicitud[0]->actor);
        return $nombre_contrato;
    }

    public function quitar_tildes($cadena) {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    public function guardar_comentario_solicitud(){
        $idsolicitud = $this->input->post('idsolicitud');
        $idusuario = $this->session->userdata('id_pruduction_suite');
        $comentario = $this->input->post('comentario_solicitud');
        if($comentario!=""){
            $data = array(
                'id_solicitud' => $idsolicitud,
                'id_usuario' => $idusuario, 
                'comentario' => $comentario,
                'fecha' => date('Y-m-d H:i:s')
            );
            $this->model_casting->guardar_comentario_solicitud($data);
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function exportar_contrato($id_solicitud,$idcontrato){
        $alineado_justificado = " style=\"text-align:justify;\" ";
        $alineado_centrado = " style=\"text-align:center;\" ";
        $clase_tabla = " class=\"firm_cell\" ";
        $lista = " style=\"margin-left:2%;\" ";

        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        $contrato = $this->model_casting->contrato_id($idcontrato);

        $tipo = explode(' - ',  $contrato[0]->nombre);
        $nombre_contrato = 'CTO-'.strtoupper($solicitud[0]->produccion).'-TALENTO-'.strtoupper($tipo[2]).'-'.strtoupper($solicitud[0]->forma_pago).'-';
        if ( strrpos('Jca', $contrato[0]->nombre)) {
            $nombre_contrato .= strtoupper($solicitud[0]->razon_social_sociedad).'-';
        }
        $nombre_contrato .= strtoupper($solicitud[0]->actor).'.doc';

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
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") de ".$meses[date("n")]." de ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }

        $id_otro_si = "";
        //NUMERACION OTRO SI
        if ($solicitud[0]->id_solicitud_anexa) {
            $id_otro_si = $this->completar_id($solicitud[0]->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud[0]->id_solicitud_anexa,$solicitud[0]->id_solicitud);
        }

        $clausula="4";
        //NUMERO CALUSULA+
        if ($solicitud[0]->edad<18) {
            $clausula="5";
        }

        $contrato[0]->contrato=str_replace('<span class=hightltght_yellow>".strtoupper($solicitud[0]->nacionalidad)."</span>','<span class=hightltght_yellow>".mb_strtoupper($solicitud[0]->nacionalidad,"UTF-8")."</span>',$contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span></strong> <strong>', '<strong>', $contrato[0]->contrato);

        if($solicitud[0]->tipo==2){

                if ($solicitud[0]->id_tipo_moneda==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('M/CTE','DOLARES', $contrato[0]->contrato);  
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras_otro_si.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras_otro_si."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span> PESOS M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios_otro_si, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }else{

                if ($solicitud[0]->id_tipo_moneda==2) {
                    //echo $solicitud[0]->honorarios_letras;
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '(<span class=hightltght_yellow>USD$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ',  $contrato[0]->contrato);
                }else{
                    $contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>) ',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>', '($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>',  $contrato[0]->contrato);
                    $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span>','<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span> M/CTE ($<span class=hightltght_yellow>".number_format((double)$solicitud[0]->honorarios, 2, ".", ",").")</span> ', $contrato[0]->contrato);  
                }
        }

        if ($solicitud[0]->descExt=='PASAPORTE') {
            $contrato[0]->contrato = str_replace('<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>','<span class=hightltght_yellow><strong>".strtoupper($solicitud[0]->actor)."</strong></span>, identificado(a) con <span class=hightltght_yellow><strong>".$solicitud[0]->descExt."</strong></span> número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span> de <span class=hightltght_yellow><strong>".$solicitud[0]->paisExt."</strong></span>',  $contrato[0]->contrato);
        }

        if ($solicitud[0]->tipo_documento!="CÉDULA DE CIUDADANÍA") {
            $contrato[0]->contrato = str_replace('cédula de ciudadanía número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').' número <span class=hightltght_yellow>".strtoupper($solicitud[0]->documento)."</span>', $contrato[0]->contrato);
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con '.mb_strtolower($solicitud[0]->tipo_documento, 'UTF-8').'  número', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace(', identificado(a) con cédula de extranjería  número', ', identificado(a) con cédula de ciudadania número', $contrato[0]->contrato);
        }
         

        $porciones = explode(" ", $solicitud[0]->honorarios_letras);
        $cambiar=0;
        if($porciones){
            foreach ($porciones as $p) {
                if($p=='mil'){
                     $cambiar=1;
                }
            }
        }
        if ($cambiar ==0) {
            if($solicitud[0]->tipo==2){
              $h=str_replace('millones','millones de',$solicitud[0]->honorarios_letras_otro_si);      
            }else{
              $h=str_replace('millones','millones de',$solicitud[0]->honorarios_letras);    
            }    
        } else {
            $h=$solicitud[0]->honorarios_letras;
        }
        
         $MTE='';
        if ($solicitud[0]->id_tipo_moneda==1) {
            $MTE=' M/CTE';
        }

        if($solicitud[0]->tipo==2){
           $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras_otro_si.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);
        }else{
           $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($h)." ".$solicitud[0]->tipo_moneda." ".$MTE.', $contrato[0]->contrato);
        }
        //$contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        if ($solicitud[0]->id_tipo_moneda==2) {
            $contrato[0]->contrato = str_replace('$"', 'U$"', $contrato[0]->contrato);
        }

        //$contrato[0]->contrato = str_replace('<strong><span class=hightltght_yellow>(".$solicitud[0]->honorarios_letras.")</span> <span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>', '<strong><span class=hightltght_yellow>".$solicitud[0]->honorarios_letras."</span> (<span class=hightltght_yellow>$".number_format((double)$solicitud[0]->honorarios, 2, ".", ",")."</strong></span>)',  $contrato[0]->contrato);
        if($solicitud[0]->tipo==2){
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras_otro_si.', '.strtoupper($solicitud[0]->honorarios_letras_otro_si)." DE ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        }else{
            $contrato[0]->contrato = str_replace('.$solicitud[0]->honorarios_letras.', '.strtoupper($solicitud[0]->honorarios_letras)." DE ".$solicitud[0]->tipo_moneda.', $contrato[0]->contrato);
        }
        
        $contrato[0]->contrato = str_replace('<p ".$lista." >', '<table><tr><td><font face=\"Calibri\" size=\"2\" ><span style=\"font-size: 11pt\">', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('</p ".$lista." >', '</span></font></td></tr></table>', $contrato[0]->contrato);


        $contrato[0]->contrato = str_replace('<br><br><br><br>', '', $contrato[0]->contrato);
        if($solicitud[0]->tipo==2){
            $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final_otro_si))-1  ].', $contrato[0]->contrato); 
        }else{
            $contrato[0]->contrato = str_replace('.$meses[date("n",strtotime($solicitud[0]->fecha_final)-1)].', '.$meses[date("n",strtotime($solicitud[0]->fecha_final))-1  ].', $contrato[0]->contrato); 
        }
        $contrato[0]->contrato = str_replace('<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.', '<strong>PRTI:</strong> Calle 63F # 28B – 15, teléfono 6409000 de Bogotá D.C., Colombia.<br>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('$meses[date("n",strtotime($solicitud[0]->fecha_inicio)-1)]', '$meses[date("n",strtotime($solicitud[0]->fecha_inicio))-1]', $contrato[0]->contrato);
        

        $contrato[0]->contrato = str_replace('".$dias[intval(date("d"))]." DE ".', '".$dias[intval(date("d"))]." (".date("d").") DE ".', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('valor y tenor, día (<span class=hightltght_yellow>".date("d")."</span>) DE', 'valor y tenor, día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>) DE', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace(' el presente documento el día (<span class=hightltght_yellow>".date("d")."</span>)', ' el presente documento el día ".$dias[intval(date("d"))]." (<span class=hightltght_yellow>".date("d")."</span>)', $contrato[0]->contrato);
        if($solicitud[0]->tipo==2){
           $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras_otro_si).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras_otro_si)."</span>', $contrato[0]->contrato);  
        }else{
           $contrato[0]->contrato = str_replace('<span class=hightltght_yellow>(".strtoupper($solicitud[0]->honorarios_letras).")</span>', '<span class=hightltght_yellow>".strtoupper($solicitud[0]->honorarios_letras)."</span>', $contrato[0]->contrato);    
        }
        
        $contrato[0]->contrato = str_replace('igual valor y tenor, el día (<span', 'igual valor y tenor, el día ".$dias[intval(date("d"))]." (<span', $contrato[0]->contrato);


        if ($solicitud[0]->id_tipo_moneda!=1) {
           $contrato[0]->contrato = str_replace('M/CTE', '', $contrato[0]->contrato);
        }

        $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        $dias = array("", "PRIMERO","DOS","TRES","CUATRO","CINCO","SÉIS","SIETE","OCHO","NUEVE","DIEZ","ONCE","DOCE","TRECE",
            "CATORCE","QUINCE","DIECISÉIS","DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUNO", "VEINTIDÓS", "VEINTITRES", "VEINTICUATRO", "VEINTICINCO",
            "VEINTISÉIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE", "TREINTA", "TREINTA Y UNO");

        // ESPECIALES
        $contrato[0]->contrato = str_replace('<p ".$lista." >', '<table><tr><td><font face=\"Calibri\" size=\"2\" ><span style=\"font-size: 11pt\">', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('</p ".$lista." >', '</span></font></td></tr></table>', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<br><br><br><br>', '', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<br delete >', '', $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<span page_break></span>', addslashes('<br style="page-break-before: always">'), $contrato[0]->contrato);
        $contrato[0]->contrato = str_replace('<span page_breakos></span>', addslashes('</span</font></td></tr></table><br style="page-break-before: always"><table><tr><td><font face=Calibri size=3 ><span style=font-size: 11pt>'), $contrato[0]->contrato);

        if ($contrato){ 
            $str = $contrato[0]->contrato;
            @eval("\$str = \"$str\";");
        }
        if ($contrato[0]->id == 20) {
             @$str = utf8_decode($str);
             @$str = str_replace('?', '"', $str);
        }

        
        $html = '
        <html xmlns:v="urn:schemas-microsoft-com:vml"
        xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:w="urn:schemas-microsoft-com:office:word"
        xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
        xmlns="http://www.w3.org/TR/REC-html40">

        <head>
        <meta http-equiv=Content-Type content="text/html; charset=unicode">
        <meta name=ProgId content=Word.Document>
        <meta name=Generator content="Microsoft Word 14">
        <meta name=Originator content="Microsoft Word 14">
        <link rel=File-List>

        <!--[if gte mso 9]><xml>
         <o:DocumentProperties>
          <o:Author>Andres</o:Author>
          <o:Template>Normal</o:Template>
          <o:LastAuthor>Andres</o:LastAuthor>
          <o:Revision>2</o:Revision>
          <o:TotalTime>2</o:TotalTime>
          <o:Created>2014-09-01T19:27:00Z</o:Created>
          <o:LastSaved>2014-09-01T19:27:00Z</o:LastSaved>
          <o:Pages>13</o:Pages>
          <o:Words>5314</o:Words>
          <o:Characters>29233</o:Characters>
          <o:Lines>243</o:Lines>
          <o:Paragraphs>68</o:Paragraphs>
          <o:CharactersWithSpaces>34479</o:CharactersWithSpaces>
          <o:Version>14.00</o:Version>
         </o:DocumentProperties>
         <o:OfficeDocumentSettings>
          <o:AllowPNG/>
         </o:OfficeDocumentSettings>
        </xml><![endif]-->
        <!--[if gte mso 9]><xml>
         <w:WordDocument>
          <w:View>Print</w:View>
          <w:GrammarState>Clean</w:GrammarState>
          <w:TrackMoves>false</w:TrackMoves>
          <w:TrackFormatting/>
          <w:HyphenationZone>21</w:HyphenationZone>
          <w:ValidateAgainstSchemas/>
          <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
          <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
          <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
          <w:DoNotPromoteQF/>
          <w:LidThemeOther>ES-CO</w:LidThemeOther>
          <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
          <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
          <w:Compatibility>
           <w:BreakWrappedTables/>
           <w:SplitPgBreakAndParaMark/>
          </w:Compatibility>
          <m:mathPr>
           <m:mathFont m:val="Cambria Math"/>
           <m:brkBin m:val="before"/>
           <m:brkBinSub m:val="&#45;-"/>
           <m:smallFrac m:val="off"/>
           <m:dispDef/>
           <m:lMargin m:val="0"/>
           <m:rMargin m:val="0"/>
           <m:defJc m:val="centerGroup"/>
           <m:wrapIndent m:val="1440"/>
           <m:intLim m:val="subSup"/>
           <m:naryLim m:val="undOvr"/>
          </m:mathPr></w:WordDocument>
        </xml><![endif]--><!--[if gte mso 9]><xml>
         <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
          DefSemiHidden="true" DefQFormat="false" DefPriority="99"
          LatentStyleCount="267">
          <w:LsdException Locked="false" Priority="0" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
          <w:LsdException Locked="false" Priority="9" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
          <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
          <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
          <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
          <w:LsdException Locked="false" Priority="10" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Title"/>
          <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
          <w:LsdException Locked="false" Priority="11" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
          <w:LsdException Locked="false" Priority="22" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
          <w:LsdException Locked="false" Priority="20" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
          <w:LsdException Locked="false" Priority="59" SemiHidden="false"
           UnhideWhenUsed="false" Name="Table Grid"/>
          <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
          <w:LsdException Locked="false" Priority="1" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 1"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
          <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
          <w:LsdException Locked="false" Priority="34" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
          <w:LsdException Locked="false" Priority="29" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
          <w:LsdException Locked="false" Priority="30" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 1"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 2"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 2"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 3"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 3"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 4"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 4"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 5"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 5"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
          <w:LsdException Locked="false" Priority="60" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
          <w:LsdException Locked="false" Priority="61" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light List Accent 6"/>
          <w:LsdException Locked="false" Priority="62" SemiHidden="false"
           UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
          <w:LsdException Locked="false" Priority="63" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
          <w:LsdException Locked="false" Priority="64" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
          <w:LsdException Locked="false" Priority="65" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
          <w:LsdException Locked="false" Priority="66" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
          <w:LsdException Locked="false" Priority="67" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
          <w:LsdException Locked="false" Priority="68" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
          <w:LsdException Locked="false" Priority="69" SemiHidden="false"
           UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
          <w:LsdException Locked="false" Priority="70" SemiHidden="false"
           UnhideWhenUsed="false" Name="Dark List Accent 6"/>
          <w:LsdException Locked="false" Priority="71" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
          <w:LsdException Locked="false" Priority="72" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
          <w:LsdException Locked="false" Priority="73" SemiHidden="false"
           UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
          <w:LsdException Locked="false" Priority="19" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
          <w:LsdException Locked="false" Priority="21" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
          <w:LsdException Locked="false" Priority="31" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
          <w:LsdException Locked="false" Priority="32" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
          <w:LsdException Locked="false" Priority="33" SemiHidden="false"
           UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
          <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
          <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
         </w:LatentStyles>
        </xml><![endif]-->
        <style>
        <!--
         /* Style Definitions */
         
         p.MsoNormal, li.MsoNormal, div.MsoNormal
            {mso-style-unhide:no;
            mso-style-qformat:yes;
            mso-style-parent:"";
            margin:0cm;
            margin-bottom:.0001pt;
            text-align:left;
            mso-pagination:widow-orphan;
            font-size:11.0pt;
            font-family:"Calibri","serif"; 
            mso-fareast-font-family:"Calibri";
            mso-fareast-theme-font:minor-fareast;}
        p
            {mso-style-noshow:yes;
            mso-style-priority:99;
            mso-margin-top-alt:auto;
            margin-right:0cm;
            mso-margin-bottom-alt:auto;
            margin-left:0cm;
            text-align:justify;
            mso-pagination:widow-orphan;
            font-size:11.0pt;
            font-family:"Calibri","sans-serif";
            mso-fareast-font-family:"Calibri";
            mso-fareast-theme-font:minor-fareast;}
        span.GramE
            {mso-style-name:"";
            mso-gram-e:yes;}
        .MsoChpDefault
            {mso-style-type:export-only;
            mso-default-props:yes;
            font-size:10pt
            mso-ansi-font-size:10pt
            mso-bidi-font-size:10pt}
        @page WordSection1
            {size:612.0pt 792.0pt;
            margin:14.0pt 14.0pt 14.0pt 14.0pt
            mso-header-margin:35.4pt;
            mso-footer-margin:35.4pt;
            mso-paper-source:0;}
        div.WordSection1
            {page:WordSection1;}
        table { text-align: justify; width:100%; }
        td { text-align: justify; margin-left:20px; }
        tr { text-align: justify; }
        p{text-align:justify!important;}
        span{text-align:justify!important;text-justify:inter-word}

        div{text-align:justify!important;}
        body{text-align:justify;font-family:"Calibri","sans-serif";margin:50px 50px 50px 50px;}
        -->
        </style>
        <!--[if gte mso 10]>
        <style>
         /* Style Definitions */
         table.MsoNormalTable
            {mso-style-name:"Tabla normal";
            mso-tstyle-rowband-size:0;
            mso-tstyle-colband-size:0;
            mso-style-noshow:yes;
            mso-style-priority:99;
            mso-style-parent:"";
            mso-padding-alt:0cm 5.4pt 0.5pt 5.4pt;
            mso-para-margin:0cm;
            mso-para-margin-bottom:.0001pt;
            mso-pagination:widow-orphan;
            font-size:10pt;
            font-family:"Calibri","serif";}
        </style>
        <![endif]-->
        <meta name=author content="Andrés Vera">
        <meta name=created content="20140108;105900000000000">
        <meta name=changedby content="Andrés Vera">
        <meta name=changed content="20140529;184800000000000">
        <!--[if gte mso 9]><xml>
         <o:shapedefaults v:ext="edit" spidmax="1026"/>
        </xml><![endif]--><!--[if gte mso 9]><xml>
         <o:shapelayout v:ext="edit">
          <o:idmap v:ext="edit" data="1"/>
         </o:shapelayout></xml><![endif]-->
        </head>
        <body lang=ES-CO style="tab-interval:10.4pt;margin-left:0.5pt;margin-top:0.5pt;
margin-right:0.5pt;margin-bottom:0.5pts" align=justify>
            <font face="Calibri" size="2" >
            <span style="font-size: 11pt;text-justify:inter-word">'.
            str_replace('</br>', '<br>', $str).'
            <DIV TYPE=FOOTER>
                <P ALIGN=CENTER STYLE="margin-top: 1cm; margin-bottom: 0cm"><FONT FACE="Calibri, sans-serif"><FONT SIZE=1 STYLE="font-size: 8pt"><B>Todos
                los derechos reservados Produciones RTI S.A.S <SDFIELD TYPE=PAGE SUBTYPE=RANDOM FORMAT=PAGE>11</SDFIELD>
                / <SDFIELD TYPE=DOCSTAT SUBTYPE=PAGE FORMAT=ARABIC>12</SDFIELD></B></FONT></FONT></P>
                <P STYLE="margin-right: 0.64cm; margin-bottom: 0cm"><BR>
                </P>
            </DIV>
            </span>
            </font>
        </body>
        </html>';

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment; Filename=".$nombre_contrato );
        echo  '<p style=\'font-family:"Calibri","sans-serif";text-align:justify;text-justify:inter-word\'>'.$html.'</p>';
    }

    public function agregar_sugerencia_contratacion(){
        $idsolicitud = $this->input->post('idsolicitud');
        $sugerencia = $this->input->post('sugerencia_contratacion');
        $data = array(
            'id' => $idsolicitud, 
            'sugerencias_contratacion' => $sugerencia
        );
        $this->model_casting->agregar_sugerencia_contratacion($data);
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function guardar_documento_solicitud(){
        $idsolicitud = $this->input->post('idsolicitud');
        $descripcion_socumento = $this->input->post('descripcion_socumento');

        if($_FILES['documento_solicitud']["tmp_name"]){
            $rutaServidor="images/documentos_solicitud";
            $rutaTemporal= $_FILES['documento_solicitud']["tmp_name"];
            $nombreimage= date('d_m_y_h_m_s').'_'.$idsolicitud.'_'.$descripcion_socumento.$this->extension_archivo($_FILES['documento_solicitud']["name"]);
            $rutaDestino= $rutaServidor.'/'.$nombreimage;
            move_uploaded_file($rutaTemporal, $rutaDestino); 

            $data=array(
                'id_solicitud'=>$idsolicitud,  
                'documento'=>$rutaDestino,
                'descripcion'=>$descripcion_socumento,
                'fecha' => date('Y-m-d H:i:s')
            );

            $this->model_casting->insert_documento_solcitud($data);
        }
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function pdf_comparacion($actores, $nombre_produccion, $nombre_personaje ){
        $nombre_produccion = str_replace('_', ' ',  $nombre_produccion);
        $nombre_produccion = str_replace('...', '?',  $nombre_produccion);
        $pdf = new PDFCASTING(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetHeaderMargin('10px');
        $pdf->setPageOrientation('L', $autopagebreak=true, $bottommargin='40px');
        $pdf->SetTopMargin('28px');
        /*VARIABLES HEADER*/
        $pdf->setTitulo("COMPARACIÓN PERSONAJES");
        $pdf->setProyecto(urldecode($nombre_produccion));
        $pdf->setPersonaje(urldecode($nombre_personaje));
        if ($nombre_produccion!="-" AND $nombre_produccion!="" ) {
            $produccion = $this->model_casting->getProduccionByName($nombre_produccion);
            if ($produccion AND $produccion[0]->imagen_produccion) {
                $pdf->setImagenProduccion(base_url($produccion[0]->imagen_produccion));
            }
        }
        /*FIN VARIABLES HEADER*/
        $pdf->AddPage('L');
        $pdf->Ln(10);
        $pdf->SetFont('','',7);
        $actores_array = explode('.', $actores);
        $cadena = '<table><tr style="background-color:#f8f7f2;">';




        $sql = "( ";

        for ($i=0; $i < count($actores_array)-1; $i++) { 
            if($i==0){
                $sql.= "  id_actor = ".$actores_array[$i];
            }else{
                $sql.= " OR  id_actor = ".$actores_array[$i];
            }
        }
        $sql .= " )";


        $max_videos = $this->model_casting->maximo_videos($sql);
        $j=0;
        for ($i=0; $i < count($actores_array) -1; $i++) { 
            if($i == 3){
                $cadena .= "</tr></table>";
                $pdf->writeHTML($cadena, true, false, true, false, '');
                $cadena = "";
                $pdf->AddPage('L');
                $cadena .= '<table><tr style="background-color:#f8f7f2;">';
            }

            if($actores_array[$i]!=""){
                $cadena .= '<td>
                                <table style="padding: 10px 0px 0px 0px" cellpadding="2">';
                $actor = $this->model_casting->actor_id($actores_array[$i]);
                $fotos_actor = $this->model_casting->fotos_actor($actores_array[$i]); 
                if($fotos_actor){
                    $imagen = $fotos_actor[0]->ruta_foto; 
                }else{
                    $imagen = 'images/casting/default.jpg'; 
                }
                $cadena .= '<tr><td align="center"><img src="'.base_url($imagen).'" width="100px" height="250px"></td></tr>';
                $cadena .= '<tr style="background-color:#535357;color:#FFFFFF;font-size:10px;"><td align="center" style="vertical-align:middle;" valign="middle" ><b>'.strtoupper($actor[0]->nombre.' '.$actor[0]->apellido).'</b></td></tr>';
                $videos_actor = $this->model_casting->videos_actor($actores_array[$i]);
                $v = 0;
                if ($videos_actor) {
                    $t=1;
                    
                    foreach ($videos_actor AS $video_actor) {
                        ++$v;
                        if ($t%2==0){
                          $b="#e6e4e5";
                        }else{
                          $b="#ffffff";
                        }
                        $cadena .= '<tr><td align="center"><a href="'.$video_actor->url.'" target="_blank" >'.$video_actor->url.'</a></td></tr>';
                        ++$t;
                    }
                }
                for ($f=$v; $f < $max_videos[0]->contador ; $f++) { 
                    $cadena .= '<tr><td align="center"> - </td></tr>';
                }

                $cadena .= '</table>
                </td>';
            }
            $j=$i;
        }

        if ($j%3!=0) {
            do {
                $cadena .= '<td><table><tr><td></td></tr></table>
                </td>';
                ++$j;
            } while ($j%3==0);
        }
        $cadena .= "</tr></table>";
        $pdf->writeHTML($cadena, true, false, true, false, '');
        $pdf->Output('comparacion_actores.pdf', 'I');
    }



    /*VALIDA EL TIPO ICONO ARCHIVO*/
    public function tipo_icono($url){
        $icono = "generic";
        $extensiones = array(".pdf", ".jpg", ".png", ".doc");
        foreach($extensiones as $extension) {
            if (strpos($url, $extension) !== false) {
                $hasString = true;
                $icono = str_replace('.', '', $extension);
                break;
            }
        }
        return $icono;
    }


    /*RETORNA EXTENSION ARCHIVO*/
    public function extension_archivo($archivo){
        return substr($archivo, strrpos($archivo,'.'));
    }

    //RETORNA VALIDACION DOCUMENTO
    public function valida_documento($documento_actor){
        if( $documento_actor->descripcion OR 
            isset($documento_actor->id_clase_entidad) OR 
            isset($documento_actor->id_estado_entidad) OR 
            $documento_actor->url){ 
            return ""; 
        }else{ 
            return '_base'; 
        }
    }

    public function carga_documentos_solicitud($idactor){
        $documentos_actor = $this->model_casting->documentos_actor($idactor);
        $cadena_documentos = "";
        $clases_entidad = $this->model_casting->clases_entidades();
        $estados_entidad = $this->model_casting->estados_entidades();
        if($documentos_actor){
            // ARL
            $cadena_documentos .='<div class="columns twelve" id="document_2"';
            $cadena_documentos .=' > ';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">ARL (Riesgos profesionales)</label>';
            $cadena_documentos .='<input type="hidden" value="';
            if($documentos_actor['1']){ 
                $cadena_documentos .=$documentos_actor['1']->id; 
            } 
            $cadena_documentos .='" name="id_arl">';
            $cadena_documentos .='<input type="text" name="arl_nombre" id="arl_nombre" value="';
            if($documentos_actor['1']){ 
                $cadena_documentos .=$documentos_actor['1']->descripcion; 
            } 
            $cadena_documentos .='">';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Activo:</label>';
            $cadena_documentos .='<select name="arl_activo" id="arl_activo">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($estados_entidad as $estado_entidad){ 
                $cadena_documentos .='<option value="'.$estado_entidad->id.'"';
                if ($documentos_actor['1'] AND $documentos_actor['1']->id_estado_entidad == $estado_entidad->id){
                  $cadena_documentos .='selected'; 
                }
                $cadena_documentos .='>'.$estado_entidad->descripcion.'</option>';
            }
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Clase:</label>';
            $cadena_documentos .='<select name="arl_clase" id="arl_clase">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($clases_entidad as $clase_entidad){ 
                $cadena_documentos .='<option value="'.$clase_entidad->id.'"';
                if ($documentos_actor['1'] AND $documentos_actor['1']->id_clase_entidad == $clase_entidad->id){
                  $cadena_documentos .='selected'; 
                }
                $cadena_documentos .=' >'.$clase_entidad->descripcion.'</option>';
            } 
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';
            //
            $label="ADJUNTAR";
            if ($documentos_actor['1'] AND $documentos_actor['1']->url){
                $label="CAMBIAR";
            }

            $cadena_documentos .='<div class="column three">';
            $cadena_documentos .='<label for="">&nbsp;</label>';
            $cadena_documentos .='<div class="content-input-file inline">';
            $cadena_documentos .='<input type="hidden" name="arl_documento_hidden" id="arl_documento_hidden" value="'.$documentos_actor['1']->url.'">';
            $cadena_documentos .='<span class="button-file" data-input="arl_documento">'.$label.' ARCHIVO</span>';
            $cadena_documentos .='<input type="file" name="arl_documento" id="arl_documento" class=" contrato_firmado with-label" style="display:none">';
            $cadena_documentos .='<span class="label-file" data-input="arl_documento">NO HAY ARCHIVO SELECCIONADO</span>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="columns one alignLeft">';
            if ($label=="CAMBIAR"){
                $cadena_documentos .='<a href="'.base_url($documentos_actor['1']->url).'" target="_blank" class="button alignTop twelve">VER</a>';
            }

            $cadena_documentos .='</div>';
            $cadena_documentos .='</div>';

            //EPS
            $cadena_documentos .='<div class="columns twelve" id="document_3"';
            $cadena_documentos .=' >';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">EPS:</label>'; 

            if ($documentos_actor['2']){ 
                $cadena_documentos .='<input type="hidden" value="';
                if($documentos_actor['2']){
                 $cadena_documentos .=  $documentos_actor['2']->id;  
                }
                $cadena_documentos .='" name="id_eps">';
            }
            $cadena_documentos .='<input type="text"  name="eps_nombre" id="eps_nombre" value="';
            if($documentos_actor['2']){ 
                $cadena_documentos .= $documentos_actor['2']->descripcion; 
            } 
            $cadena_documentos .='" >';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Activo:</label>';
            $cadena_documentos .='<select name="eps_activo" id="eps_activo">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($estados_entidad as $estado_entidad){
                $cadena_documentos .='<option value="'.$estado_entidad->id.'"';
                if ($documentos_actor['2'] AND $documentos_actor['2']->id_estado_entidad == $estado_entidad->id){
                    $cadena_documentos .='selected'; 
                }
                $cadena_documentos .=' >'.$estado_entidad->descripcion.'</option>';
            }
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Clase:</label>';
            $cadena_documentos .='<select name="eps_clase" id="eps_clase">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($clases_entidad as $clase_entidad){
                $cadena_documentos .='<option value="'.$clase_entidad->id.'"';
                if ($documentos_actor['2'] AND $documentos_actor['2']->id_clase_entidad == $clase_entidad->id){
                  $cadena_documentos .='selected'; 
                }
                $cadena_documentos .=' >'.$clase_entidad->descripcion.'</option>';
            }
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';

            $label="ADJUNTAR";
            if ($documentos_actor['2'] AND $documentos_actor['2']->url){
                $label="CAMBIAR";
            }

            $cadena_documentos .='<div class="column three">';
            $cadena_documentos .='<label for="">&nbsp;</label>';
            $cadena_documentos .='<div class="content-input-file inline">';
            $cadena_documentos .='<input type="hidden" name="eps_documento_hidden" id="eps_documento_hidden"  value="'.$documentos_actor['2']->url.'">';
            $cadena_documentos .='<span class="button-file" data-input="eps_documento">'.$label.' ARCHIVO</span>';
            $cadena_documentos .='<input type="file" name="eps_documento" id="eps_documento" class=" contrato_firmado with-label" style="display:none">';
            $cadena_documentos .='<span class="label-file" data-input="eps_documento">NO HAY ARCHIVO SELECCIONADO</span>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="columns one alignLeft">';
            if ($label=="CAMBIAR"){
                $cadena_documentos .='<a href="'.base_url($documentos_actor['2']->url).'" target="_blank" class="button alignTop twelve">VER</a>';
            }
            $cadena_documentos .='</div>';

            $cadena_documentos .='</div>'; 

            // FONDO PENSIONES
            $cadena_documentos .='<div class="columns twelve" id="document_4" ';
            $cadena_documentos .=' >';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Fondo de Pensiones:</label>';
            if ($documentos_actor['3']){
                $cadena_documentos .='<input type="hidden" value="';
                if($documentos_actor['3']){ 
                    $cadena_documentos .= $documentos_actor['3']->id; 
                }
                $cadena_documentos .='" name="id_pensiones">';
            }
            $cadena_documentos .='<input type="text"  name="pensiones_nombre" id="pensiones_nombre" value="';
            if($documentos_actor['3']){ 
                $cadena_documentos .= $documentos_actor['3']->descripcion; 
            } 
            $cadena_documentos .='">';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Activo:</label>';
            $cadena_documentos .='<select name="pensiones_activo" id="pensiones_activo">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($estados_entidad as $estado_entidad){
                $cadena_documentos .='<option value="'.$estado_entidad->id.'"';
                if ($documentos_actor['3'] AND $documentos_actor['3']->id_estado_entidad == $estado_entidad->id){
                    $cadena_documentos .='selected'; 
                }
                $cadena_documentos .=' >'.$estado_entidad->descripcion.'</option>';
            }
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="column two">';
            $cadena_documentos .='<label for="">Clase:</label>';
            $cadena_documentos .='<select name="pensiones_clase" id="pensiones_clase">';
            $cadena_documentos .='<option value="">Seleccione una opción</option>';
            foreach ($clases_entidad as $clase_entidad){
                $cadena_documentos .='<option value="'.$clase_entidad->id.'"';
                if ($documentos_actor['3'] AND $documentos_actor['3']->id_clase_entidad == $clase_entidad->id){
                    $cadena_documentos .='selected'; 
                } 
                $cadena_documentos .=' >'.$clase_entidad->descripcion.'</option>';
            }
            $cadena_documentos .='</select>';
            $cadena_documentos .='</div>';
 
            $label="ADJUNTAR";
            if ($documentos_actor['3'] AND $documentos_actor['3']->url){
                $label="CAMBIAR";
            }

            $cadena_documentos .='<div class="column three">';
            $cadena_documentos .='<label for="">&nbsp;</label>';
            $cadena_documentos .='<div class="content-input-file inline">';
            $cadena_documentos .='<input type="hidden" name="pensiones_documento_hidden" id="pensiones_documento_hidden"  value="'.$documentos_actor['3']->url.'">';
            $cadena_documentos .='<span class="button-file" data-input="pensiones_documento">'.$label.' ARCHIVO</span>';
            $cadena_documentos .='<input type="file" name="pensiones_documento" id="pensiones_documento" class=" contrato_firmado with-label" style="display:none">';
            $cadena_documentos .='<span class="label-file" data-input="pensiones_documento">NO HAY ARCHIVO SELECCIONADO</span>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='</div>';
            $cadena_documentos .='<div class="columns one alignLeft">';
            if ($label=="CAMBIAR"){
                $cadena_documentos .='<a href="'.base_url($documentos_actor['3']->url).'" target="_blank" class="button alignTop twelve">VER</a>';
            } 
            $cadena_documentos .='</div>';
            $cadena_documentos .='</div>';

        }
        return $cadena_documentos;
    }

    public function crear_otro_si($idsolicitud){
        $solicitud = $this->model_casting->solicitud_id($idsolicitud);
        $elementos_solicitud = $this->model_casting->elementos_solicitud($idsolicitud);
        $objetos_contrato= $this->model_casting->objetos_contrato();
        $lugares_servicio= $this->model_casting->lugares_servicio();
        $areas_solicitantes= $this->model_casting->areas_solicitantes();
        $roles = $this->model_escenas->rol_actores_elementos();
        $formas_pago = $this->model_elementos->contratos();
        $tipos_moneda = $this->model_elementos->tipos_moneda();

        $data['tipos_moneda'] = $tipos_moneda;
        $data['roles'] = $roles;
        $data['formas_pago'] = $formas_pago;
        $data['objetos_contrato'] = $objetos_contrato;
        $data['lugares_servicio'] = $lugares_servicio;
        $data['areas_solicitantes'] = $areas_solicitantes;
        $data['solicitud'] = $solicitud;
        $data['elementos_solicitud'] = $elementos_solicitud;
        $data['view']='casting/crear_otro_si';
        $this->load->view('includes/template',$data); 
    }

    public function actualizar_documento_solicitud(){
        $iddocumento = $this->input->post('id_documento');
        $descripcion = $this->input->post('numero_documento_'.$iddocumento[0]);
        $documento = $this->input->post('documento_solicitud_'.$iddocumento[0]);
        $idsolicitud = $this->input->post('idsolicitud');
        if($_FILES['documento_solicitud_'.$iddocumento[0]]["tmp_name"]){
            $rutaServidor="images/documentos_actor";
            $rutaTemporal= $_FILES['documento_solicitud_'.$iddocumento[0]]["tmp_name"];
            $nombreimage= date('d_m_y_h_m_s').'_SOLICITUD_'.$idsolicitud.$this->extension_archivo($_FILES['documento_solicitud_'.$iddocumento[0]]["name"]);
            $documento= $rutaServidor.'/'.$nombreimage;
            move_uploaded_file($rutaTemporal, $documento); 
        }
        $data = array(
            'id' => $iddocumento[0], 
            'descripcion'=> $descripcion,
            'documento'=> $documento
        );

        $this->model_casting->actualizar_documento_solicitud($data);
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function eliminar_documentos_solicitud($id_solicitud,$id_documento){
        $this->model_casting->eliminar_documentos_solicitudID($id_documento,'null');
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$id_solicitud);

    }

    public function guardar_documento_actor(){
        $idactor = $this->input->post('idactor');
        $idsolicitud = $this->input->post('idsolicitud');
        $tipo =$this->input->post('tipo');
        $nombre =$this->input->post('nombre');
        $clase = $this->input->post('clase');
        $activo = $this->input->post('activo');
        $iddocumento = $this->input->post('iddocumento');
        $documento = $this->input->post('documento_original');
        $actor = $this->model_casting->actor_id($idactor);

        if($_FILES['documento']["tmp_name"]){
            $rutaServidor="images/documentos_actor";
            $rutaTemporal= $_FILES['documento']["tmp_name"];
            $nombreimage= date('d_m_y_h_m_s').'_'.$actor[0]->nombre.'_'.$actor[0]->apellido.'_TIPO_'.$tipo.$this->extension_archivo($_FILES['documento']["name"]);
            $documento= $rutaServidor.'/'.$nombreimage;
            move_uploaded_file($rutaTemporal, $documento); 
        }

        if(!$activo){
          $activo=null;
        }
        if(!$clase){
          $clase=null;
        }
        $data=array(
            'id'=>$iddocumento,
            'id_actor'=>$idactor,
            'id_tipo_documentacion'=>$tipo,
            'url'=>$documento,
            'descripcion'=>$nombre,
            'id_clase_entidad'=>$clase,
            'id_estado_entidad'=>$activo,
        );
        $this->model_casting->update_documentos_actor($data);   
        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);

    }

    public function diferencia_dias($fecha_inicio,$fecha_final){
        $dias = (strtotime($fecha_inicio)-strtotime($fecha_final))/86400;
        $dias = abs($dias); $dias = floor($dias);     
        return $dias;
    }

    function calculo_edad(){
        $fecha = date('Y-m-d', strtotime($_POST['fecha']));
        list($Y,$m,$d) = explode("-",$fecha);
        echo json_encode( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

    public function anular_solicitud($idsolicitud){
        $data = array(
            'id' => $idsolicitud, 
            'id_estado'=>16
        );
        $this->model_casting->update_solicitud($data);
     

        $elementos_solicitud = $this->model_casting->elementos_solicitud($idsolicitud);
        if($elementos_solicitud){
            $data=array('id_solicitud'=>$idsolicitud,'id_elemento'=>$elementos_solicitud['0']->id); 
            $this->model_casting->update_solicitud_elemento($data);
        }
        

        $dataEstado = array(
            'id_solicitud' => $idsolicitud, 
            'id_estado' => 16,
            'fecha_aprobacion' => date("Y-m-d"),
            'id_usuario' => $this->session->userdata('id_pruduction_suite'),
            'activo'=>0,
            'notas'=>''
        );
        $this->model_casting->insert_aprobacion_solicitud($dataEstado);

        redirect($this->lang->lang().'/casting/detalle_solicitud/'.$idsolicitud);
    }

    public function validacion_caso_especial($solicitud){
        $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13,3);
        $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14,3); 

        $caso_especial=0;
        $id_user = $this->session->userdata('id_pruduction_suite');
        $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
        $user=$this->model_admin->rolUserId($id_user);
        $tipo_rol=$user['0']['id_rol_otros'];

        if($solicitud->roles ==1 OR $solicitud->condiciones_especiales!=""){
            $caso_especial=1;
        }
        if($tipo_usuario == 7 AND !$aprobacion_juridica){
            $caso_especial = 2;
        }

        if($tipo_usuario == 8 AND !$aprobacion_finanzas){
            $caso_especial = 3;
        }

        if($tipo_usuario == 1 AND $solicitud->id_estado ==3  
            AND ($solicitud->condiciones_especiales!="" OR  $solicitud->roles=="1" 
                OR  $solicitud->id_tipo_moneda=="2" OR $solicitud->id_forma_pago==1)){
            if($solicitud->roles==1){
                 $caso_especial = 4;    
            }else{
                 $caso_especial=0;
            }
            
        }
        return $caso_especial;
    }

    public function pdf_solicitudes(){
        $pdf = new PDFCASTING(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setTitulo ("REPORTE: SOLICITUDES");

        $cadena_header = '
        <table border="0.5" style="whith:100%;" cellpadding="2">
            <tr style="background-color:#535357;color:#FFFFFF;">
                <td width="30" align="center" >#</td>
                <td width="110" align="center" >STATUS</td>
                <td width="110" align="center" >RESPONSABLE</td>
                <td width="100" align="center" >PRODUCCIÓN</td>
                <td width="110" align="center" >PERSONAJE</td>
                <td width="100" align="center" >ACTOR</td>
                <td width="70" align="center" >MONTO</td>
                <td width="60" align="center" >TIPO PAGO</td>
                <td width="50" align="center" >FEC. INI</td>
                <td width="50" align="center" >FEC. FIN.</td>
            </tr>    
        </table>';
        $content = "";
        $tbl="";

        $solicitudes = $this->model_casting->get_all_solicitudes();
        if ($solicitudes) {
            $pdf->setContenidoHeader($cadena_header);
            $pdf->SetHeaderMargin('11px');
            $pdf->SetTopMargin('30.9');
            $pdf->SetFont('', '', 7);
            $pdf->AddPage('L');
            $content.='<table border="1" width="100%" cellpadding="2">';
            $i=1;

            foreach ($solicitudes as $solicitud) {
                $content.='<tr>';
                $content.='<td align="center" width="30" style="background-color:#'.$this->color_solicitud($solicitud->id_estado).'">';
                if ($solicitud->tipo == 2){
                  $content.=$this->completar_id($solicitud->id_solicitud_anexa).'-';  
                }

                $content.=$this->completar_id($solicitud->id);
                $content.='</td>';
                $content.='<td align="center" width="110">'.mb_strtoupper($solicitud->descripcion,'UTF-8');
                if ($solicitud->tipo==2) {
                    $content.= " / OTRO SI";
                }
                $content.= '</td>';
                $content.='<td align="center" width="110">';
                
                $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13);
                $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14); 
                if($solicitud->id_estado==3 AND $solicitud->condiciones_especiales=="" AND $solicitud->roles!="1"){
                    $responsable =  strtoupper($solicitud->responsable);
                    if($aprobacion_juridica){
                      $responsable = strtoupper(str_replace(mb_strtoupper("DIRECTOR JURÍDICA",'UTF-8'), '',$responsable));
                    }

                    if($aprobacion_finanzas){
                      $responsable = str_replace(mb_strtoupper("- FINANZAS",'UTF-8'), '', $responsable );
                    }
                    $content.= mb_strtoupper(str_replace('COORDINADOR DE CONTRATO -', '', $responsable),'UTF-8');
                } else{
                    $content.= mb_strtoupper(str_replace(strtoupper('PRODUCCIÓN'), '', strtoupper($solicitud->responsable)),'UTF-8');
                }

                $content.='</td>';
                $content.='<td align="center" width="100">'.mb_strtoupper($solicitud->nombre_produccion,'UTF-8').'</td>';
                $content.='<td align="center" width="110">'.mb_strtoupper($solicitud->elementos,'UTF-8').'</td>';
                $content.='<td align="center" width="100">'.mb_strtoupper($solicitud->actor,'UTF-8').'</td>';
                 if($solicitud->tipo_moneda=='PESOS'){
                  $moneda='$';
                   }else{
                  $moneda='U$D';
                  }
                $content.='<td align="right" width="70">'.$moneda.number_format((double)$solicitud->monto, 2, '.', ",").'</td>';
                $content.='<td align="center" width="60">';
                if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                    $content.="OTRO";
                }else{
                    $content.=strtoupper($solicitud->forma_pago);
                }  

                $content.='</td>';
                $content.='<td align="center" width="50">'.strtoupper(date("d-M-Y",strtotime($solicitud->fecha_inicio))).'</td>';
                $content.='<td align="center" width="50">'.strtoupper(date("d-M-Y",strtotime($solicitud->fecha_final))).'</td>';
                $content.='</tr>';
                ++$i;
                //CAMBIO DE MARGIN-TOP
                if($i==2){
                $content.="</table>";
    $tbl=<<<EOD
    $content
EOD;

                  $pdf->writeHTML($tbl, true, false, false, false, '');
                  $y = $pdf->GetY();  
                  $pdf->SetTopMargin('33.5px');
                  $pdf->SetY($y-3);
                  $content='<table border="0.5" style="whith:100%;" cellpadding="2">';
                }
            }
            $content.='</table>';
        }

      $tbl=<<<EOD
       $content
EOD;
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Output('solicitudes.pdf', 'I');
    }


    //PDF PERSONAJES
    public function pdf_personajes($idproduccion){
        if($idproduccion){
            $pdf = new PDFCASTING(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $personajes_produccion = $this->model_casting->personajes_produccion($idproduccion);
            if ($personajes_produccion) {
                $pdf->setTitulo ("REPORTE: SOLICITUDES DE PERSONAJES");
                //ELEMENTOS HEADER PRODUCCION
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
           
                $valores = array(
                    'productor_general'=>strtoupper($productor_general),
                    'nombre_produccion'=>$produccion['0']->nombre_produccion,
                    'nombre_ejecutivo'=>$nombre_ejecutivo,
                    'centro'=>strtoupper($produccion['0']->centro),
                    'nombre_productor'=>$nombre_productor,
                );
                $pdf->setElementsHeader($valores);
                // FIN ELEMENTOS HEADER PRODUCCION

                $tamaños =  array(143,70,100,180,120,70,100);
                //CONTENIDO HEADER TABLA
                $cadena_header = '
                <table border="0.5" style="whith:100%;" cellpadding="2">
                    <tr style="background-color:#535357;color:#FFFFFF;">
                        <td width="'.$tamaños[0].'" align="center" >PERSONAJE</td>
                        <td width="'.$tamaños[1].'" align="center" >ROL</td>
                        <td width="'.$tamaños[2].'" align="center" >ESTATUS PERSONAJE</td>
                        <td width="'.$tamaños[3].'" align="center" >SOLICITUDES</td>
                        <td width="'.$tamaños[4].'" align="center" >ACTOR</td>
                        <td width="'.$tamaños[5].'" align="center" >DÍA PLAN</td>
                        <td width="'.$tamaños[6].'" align="center" >RESPONSABLE</td>
                    </tr>    
                </table>';
                $pdf->setContenidoHeader($cadena_header);
                //CONTENIDO HEADER TABLA

                $pdf->SetHeaderMargin('11px');
                $pdf->SetTopMargin('35.5');
                $pdf->SetFont('', '', 7);
                $pdf->AddPage('L');
                $content ="";
                $content.='<table border="0.5" style="whith:100%;" cellpadding="2">';
                $i=1;
                foreach ($personajes_produccion as $personaje_produccion) {
                    $content.='<tr>';
                    $clase_estado = "";
                    $reponsable = "";
                    $estado = "";
                    switch ($personaje_produccion->id_estado) {
                        case 1:
                            $clase_estado = "#f7921e";
                            $reponsable = "CASTING / SECRETARIA DE PRODUCCIÓN" ;
                            $estado = "EN PROCESO - GENERANDO SOLICITUD";
                            break;
                        case 2:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                        case 3:
                            $clase_estado = "#f7921e";
                            $reponsable = "Legal" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 4:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 5:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 6:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 7:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN"; 
                            break;
                        case 8:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 9:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 10:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - PROCESO DE FIRMA";
                            break;
                        case 11:
                            $clase_estado = "#5DA423";
                            $reponsable = "-" ;
                            $estado = "ASIGNADO";
                            break;
                        case 12:
                            $clase_estado = "#a22ed1";
                            $reponsable = "-" ;
                            $estado = "EXPIRADO";
                            break;

                        default:
                            $clase_estado = "#D51313";
                            $reponsable = "Casting" ;
                            $estado = "NO ASIGNADO";
                            break;
                    } 

                    $fechas_personaje = $this->model_casting->fechas_personaje($personaje_produccion->id_elemento);
                    if($fechas_personaje){
                        if($fechas_personaje[0]->fecha_plan_diario AND $fechas_personaje[0]->fecha_contrato){
                            if(strtotime($fechas_personaje[0]->fecha_plan_diario) > $fechas_personaje[0]->fecha_contrato){
                                $clase_estado = "#a22ed1";
                                $reponsable = "-" ;
                                $estado = "EXPIRADO";
                            }
                        }
                    }

                    $content.='<td class="nombre_personaje" width="'.$tamaños[0].'"  >'.strtoupper($personaje_produccion->elemento_nombre).'</td>';
                    $content.='<td width="'.$tamaños[1].'" align="center" >'.strtoupper($personaje_produccion->rol).'</td>';
                    $content.='<td style="background-color:'.$clase_estado.'" width="'.$tamaños[2].'" align="center" >'.strtoupper($estado).'</td>';
                    $content.='<td width="'.$tamaños[3].'" align="center" >';
                    $content.= strtoupper($this->completar_id($personaje_produccion->id_solicitud).' - '.$personaje_produccion->estado_solicitud);
                    $content.='</td>';
                    $content.='<td class="nombre_actor" width="'.$tamaños[4].'" align="center" >'.strtoupper($personaje_produccion->actor_nombre).'</td>';
                    $content.='<td width="'.$tamaños[5].'" align="center" >'.strtoupper($personaje_produccion->fecha_inicio).'</td>';
                    $content.='<td width="'.$tamaños[6].'" align="center" >'.strtoupper($reponsable).'</td>';
                    $content.='</tr>';
                    ++$i;
                    //CAMBIO DE MARGIN-TOP
                    if($i==2){
                    $content.="</table>";
                    $tbl=<<<EOD
                    $content
EOD;
                      $pdf->writeHTML($tbl, true, false, false, false, '');
                      $y = $pdf->GetY();  
                      $pdf->SetTopMargin('38.5px');
                      $pdf->SetY($y-3);
                      $content='<table border="0.5" style="whith:100%;" cellpadding="2">';
                    }
                }
                $content.="</table>";
                $tbl=<<<EOD
                $content
EOD;
                $pdf->writeHTML($tbl, true, false, false, false, '');

                $pdf->Output('personajes.pdf', 'I');
            }
        }
    }

    //EXCEL SOLICITUDES
    public function excel_solicitudes(){
        $solicitudes = $this->model_casting->get_all_solicitudes();
        if($solicitudes){
            $xls = new ExcelWriter();
            //TIPOS DE CELDA
            $xls_int = array('type'=>'int','border'=>'111111');
            $xls_date = array('type'=>'date','border'=>'111111');
            $xls_normal = array('border'=>'111111');
            $columnas = array('SOLICITUD', 'ESTATUS', 'RESPONSABLE', 'PRODUCCIÓN', 'PERSONAJE', 'ACTOR', 'MONTO', 'TIPO PAGO', 'FECHA INICIO', 'FECHA TERMINACIÓN');
            $tamanos = array();
            //CABECERA
            $xls->OpenRow();
            foreach ($columnas as $columna) {
                $xls->NewCell($columna,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'111111'));
            }
            $xls->CloseRow();
            //FIN CABECERA
            foreach ($solicitudes as $solicitud) {
                $xls->OpenRow();
                $idsolicitud="";


                if ($solicitud->tipo == 2 ){
                    $idsolicitud.= $this->completar_id($solicitud->id_solicitud_anexa).' - '; 
                }

                $idsolicitud.= $this->completar_id($solicitud->id);
                $xls->NewCell($idsolicitud,false,array('border'=>'111111','background'=>$this->color_solicitud($solicitud->id_estado)));

                $estado_solicitud = $solicitud->descripcion;
                if ($solicitud->tipo==2) {
                    $estado_solicitud .=" / OTRO SI";
                }
                $xls->NewCell($estado_solicitud,false,array('border'=>'111111'));

                $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13);
                $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,14); 

                if($solicitud->id_estado==3 AND $solicitud->condiciones_especiales=="" AND $solicitud->roles!="1"){
                  $responsable =  strtoupper($solicitud->responsable);
                  if($aprobacion_juridica){
                    $responsable = str_replace(strtoupper("DIRECTOR JURÍDICA"), '',$responsable);
                  }

                  if($aprobacion_finanzas){
                    $responsable = str_replace(strtoupper("- FINANZAS"), '', $responsable );
                  }

                  $responsable = str_replace('COORDINADOR DE CONTRATO -', '', $responsable);

                }else{
                  $responsable = str_replace(strtoupper('PRODUCCIÓN'), '', strtoupper($solicitud->responsable));
                }
                $xls->NewCell($responsable,false,array('border'=>'111111'));

                $xls->NewCell($solicitud->nombre_produccion,false,array('border'=>'111111'));
                $xls->NewCell($solicitud->elementos,false,array('border'=>'111111'));
                $xls->NewCell($solicitud->actor,false,array('border'=>'111111'));
                if($solicitud->tipo_moneda=='PESOS'){
                    $moneda='$';
                }else{
                    $moneda='U$D';
                }
                $xls->NewCell($moneda.number_format((double)$solicitud->monto, 2, '.', ","),false,array('border'=>'111111'));
                
                $forma_pago = "";
                if($solicitud->id_forma_pago==1 OR $solicitud->id_forma_pago==4){
                  $forma_pago = "Otro";
                }else{
                  $forma_pago = $solicitud->forma_pago;
                } 

                $xls->NewCell($forma_pago,false,array('border'=>'111111')); 
                $xls->NewCell(date("d-M-Y",strtotime($solicitud->fecha_inicio)),false,array('border'=>'111111'));
                $xls->NewCell(date("d-M-Y",strtotime($solicitud->fecha_final)),false,array('border'=>'111111'));
                $xls->CloseRow();
            }

            $xls->GetXLS(true,'Solicitudes');
        }
        
    }


    public function excel_personajes($idproduccion){
        if($idproduccion){
            $personajes_produccion = $this->model_casting->personajes_produccion($idproduccion);
            if ($personajes_produccion) {
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

                $xls = new ExcelWriter();

                /*VALORES HEADER*/
                $xls->OpenRow();
                $xls->NewCell('PRODUCCION: '.strtoupper($produccion['0']->nombre_produccion),false,array('background'=>'ffffff'));
                $xls->NewCell('',array('background'=>'ffffff'));
                $xls->NewCell('',array('background'=>'ffffff'));
                $xls->NewCell('PRODUCTOR EJECUTIVO: '.strtoupper($nombre_ejecutivo),false,array('background'=>'ffffff'));
                $xls->CloseRow();
                $xls->OpenRow();
                $xls->NewCell('FECHA: '.strtoupper(date("Y-M-d")),false);
                $xls->NewCell('');
                $xls->NewCell('');
                $xls->NewCell('PRODUCTOR: '.strtoupper($nombre_productor),false);
                $xls->CloseRow();
                $xls->OpenRow();
                $xls->NewCell('PRODUCTOR GENERAL: '.strtoupper($productor_general),false);
                $xls->CloseRow();
                $xls->OpenRow();
                $xls->CloseRow();
                /*VALORES HEADER*/
                $xls->OpenRow();
                $xls->CloseRow();
                $xls->OpenRow();
                $xls->CloseRow();
                $columnas = array('PERSONAJE', 'ROL', 'ESTATUS PERSONAJE', 'SOLICITUD', 'ACTOR', 'DÍA PLAN', 'RESPONSABLE');
                
                /*HEADER TABLA*/
                $xls->OpenRow();
                foreach ($columnas as $columna) {
                    $xls->NewCell($columna,false,array('align'=>'center','background'=>'666666','color'=>'FFFFFF','bold'=>true,'border'=>'111111'));
                }
                $xls->CloseRow();
                /*FIN HEADER TABLA*/
                foreach ($personajes_produccion as $personaje_produccion) {
                    $xls->OpenRow();
                    $clase_estado = "";
                    $reponsable = "";
                    $estado = "";
                    switch ($personaje_produccion->id_estado) {
                        case 1:
                            $clase_estado = "#f7921e";
                            $reponsable = "CASTING / SECRETARIA DE PRODUCCIÓN" ;
                            $estado = "EN PROCESO - GENERANDO SOLICITUD";
                            break;
                        case 2:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                        case 3:
                            $clase_estado = "#f7921e";
                            $reponsable = "Legal" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 4:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 5:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 6:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - GENERANDO CONTRATO";
                            break;
                        case 7:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN"; 
                            break;
                        case 8:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 9:
                            $clase_estado = "#f7921e";
                            $reponsable = "PRODUCCIÓN" ;
                            $estado = "EN PROCESO - APROBACIÓN PRODUCCIÓN";
                            break;
                        case 10:
                            $clase_estado = "#f7921e";
                            $reponsable = "LEGAL" ;
                            $estado = "EN PROCESO - PROCESO DE FIRMA";
                            break;
                        case 11:
                            $clase_estado = "#5DA423";
                            $reponsable = "-" ;
                            $estado = "ASIGNADO";
                            break;
                        case 12:
                            $clase_estado = "#a22ed1";
                            $reponsable = "-" ;
                            $estado = "EXPIRADO";
                            break;

                        default:
                            $clase_estado = "#D51313";
                            $reponsable = "Casting" ;
                            $estado = "NO ASIGNADO";
                            break;
                    } 

                    $fechas_personaje = $this->model_casting->fechas_personaje($personaje_produccion->id_elemento);
                    if($fechas_personaje){
                        if($fechas_personaje[0]->fecha_plan_diario AND $fechas_personaje[0]->fecha_contrato){
                            if(strtotime($fechas_personaje[0]->fecha_plan_diario) > $fechas_personaje[0]->fecha_contrato){
                                $clase_estado = "#a22ed1";
                                $reponsable = "-" ;
                                $estado = "EXPIRADO";
                            }
                        }
                    }
                    $xls->NewCell($personaje_produccion->elemento_nombre,false,array('border'=>'111111'));
                    $xls->NewCell(strtoupper($personaje_produccion->rol),false,array('border'=>'111111'));
                    $xls->NewCell(strtoupper($estado),false,array('border'=>'111111','background'=>str_replace('#', '', $clase_estado)));
                    $xls->NewCell(strtoupper($this->completar_id($personaje_produccion->id_solicitud).' - '.$personaje_produccion->estado_solicitud),false,array('border'=>'111111'));
                    $xls->NewCell(strtoupper($personaje_produccion->actor_nombre),false,array('border'=>'111111'));
                    $xls->NewCell(strtoupper($personaje_produccion->fecha_inicio),false,array('border'=>'111111'));
                    $xls->NewCell(strtoupper($reponsable),false,array('border'=>'111111'));
                    $xls->CloseRow();
                }
                $xls->GetXLS(true,'Personajes');

            }
        }

    }

    //GUARDA REPRESENTANTES CONTRATO
    public function guardar_representantes(){
        $nombre = $_POST['nombre'];
        $documento = $_POST['documento'];
        $tipo = $_POST['tipo'];
        if ($tipo =="rti") {
            $tipo = 1;
        }else{
            $tipo = 2;
        }

        $data = array(
            'id' => $tipo,
            'nombre' => $nombre , 
            'documento' => $documento,
        );

        $this->model_casting->update_representantes($data);
        echo json_encode(true);

    }

    public function guardar_sociedad(){
        $representante_legal = $_POST['representante_legal'];
        $representante_documento = $_POST['representante_documento'];
        $id_sociedad = $_POST['id_sociedad'];
        $data = array(
            'id' => $id_sociedad,
            'representante_legal' => $representante_legal, 
            'documento_representante' => $representante_documento,
        );

        $this->model_casting->update_sociedad($data);
        echo json_encode(true);
    }

    

    //FUNCION NOMINA DE PERSONAJES
    public function nomina_personajes($idproduccion=""){

        //PRODUCCIONES
        $id_user=$this->session->userdata('id_pruduction_suite');
        $tipo_user=$this->session->userdata('tipo_pruduction_suite');
        
        if($tipo_user=='3' or $tipo_user=='1' OR $tipo_user=='2'){
            $sql=" ";
            $producciones=$this->model_produccion->producciones_all($sql);
        }else{
            $sql=" ";
            $producciones=$this->model_produccion->producciones_user($id_user,$sql);
        }
        //FIN PRODUCCIONES

        $fecha=$this->model_plan_produccion->unidades_id_produccion_2($idproduccion);
        if($fecha){
           $inicio_unidad=$fecha['0']['fecha_inicio'];

           $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad($idproduccion,$inicio_unidad);
        }else{
          $fechas_reporte_semanal='';   
        }
        $personajes=false;
        $produccion=false;
        if ($idproduccion!="") {
            $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
            if($produccion['0']->tipo_produccion==2){
              $personajes = $this->model_elementos->personajes_no_extra_anteriores($idproduccion);
            }else{
              $personajes = $this->model_elementos->personajes_no_extra($idproduccion);    
            }
            
            
        }

        $data['produccion'] = $produccion;
        $data['producciones'] = $producciones;
        $data['personajes'] = $personajes;
        $data['fechas_reporte_semanal'] = $fechas_reporte_semanal;
        $data['view']='casting/nomina_personajes';
        $this->load->view('includes/template',$data); 
    }

  


      public function filtro_nomina(){
                    $id_produccion=$_POST['id_produccion'];
                    $semana1=$_POST['semana1'];
                    $semana2=$_POST['semana2'];

                    $fechas_reporte_semanal = $this->model_pdf->semanas_reporte_semanal_fecha_unidad_filtro($id_produccion,$semana1,$semana2);
                    $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
                     if($produccion['0']->tipo_produccion==2){
                          $personajes = $this->model_elementos->personajes_no_extra_anteriores($id_produccion);
                        }else{
                          $personajes = $this->model_elementos->personajes_no_extra($id_produccion);    
                        }
                    $semana_limite=$this->model_pdf->semanas_reporte_semanal_fecha_unidad_filtro_max_min($id_produccion,$semana1,$semana2);


                    $html_personajes="";
                    if($personajes){ 
                        foreach ($personajes as $personaje) { 
                            $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,$semana_limite['0']->min,$semana_limite['0']->max);
                            
                            if($capitulos_personaje['0']->capitulos){
                                        $html_personajes.='<tr>';
                                        $html_personajes.='<td class="liquidacion">';
                                        $html_personajes.=$personaje->nombre;
                                        $html_personajes.='<div class="textLiquidacion anchoPersonajeLibretos hideLiquidacion box">';
                                                $explode = explode(",", $personaje->libretos_personaje);
                                                $count = count($explode);
                                                if($count > 1){
                                                    foreach($explode as $e) {
                                                     $html_personajes.=$e.'<br>';
                                                     } 
                                                }
                                        $html_personajes.='</div>';
                                        $html_personajes.='</td>';
                                        $html_personajes.='<td>'.$personaje->actor_nombre.' '.$personaje->actor_apellido.'</td>';
                                        $html_personajes.='<td>$'.number_format((double)$personaje->monto, 2, '.', ",").'</td>';
                                        $html_personajes.='<td>'.$personaje->tipo_contrato.'</td>';
                                        $html_personajes.='</tr>';
                            }      

                        } 
                        $html_personajes.='<tr><td colspan="4">TOTAL</td></tr>';    
                    }

                    $html_semanas="";
                 
                    $html_semanas.='<thead>';
                    $html_semanas.='<tr>';
                              if($fechas_reporte_semanal){
                                   $cadena_header=""; $num=$fechas_reporte_semanal['0']->inicio_semana; 
                                   foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { 
                                        $html_semanas.='<td colspan="2" class="semana_'.$num.' semanas">SEMANA '.$num.'</td>';
                                         $cadena_header.='<td class="semana_'.$num.' semanas">'.$fecha_reporte_semanal->fecha_muestra.'</br>'.$fecha_reporte_semanal->fecha_muestra_2.'</td>';
                                         $cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDADO PERIODO</td>';
                                         if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
                                              strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) ):
                                            
                                            if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ):
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>'; 
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>'; 
                                                $html_semanas.='<td class="semana_'.$num.'semanas" colspan="2">LIQUIDACIÓN</td>';
                                             else:     
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>';
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIB</td>';
                                                $cadena_header.='<td class="semana_'.$num.' semanas">LIQUIDACIÓN</td>'; 

                                                $html_semanas.='<td colspan="2" class="semana_'.$num.' semanas">LIQUIDACIÓN A</td>';
                                                $html_semanas.='<td colspan="2" class="semana_'.$num.' semanas">LIQUIDACIÓN B</td>';
                                            endif;
                                            
                                         endif;
                                    ++$num; }
                                    }else{ 
                                      $html_semanas.='<td>No hay semanas</td>';
                                    }
                                $html_semanas.='</tr>';
                            $html_semanas.='</thead>';
                            if($fechas_reporte_semanal){
                                $html_semanas.='<tbody>';
                                $html_semanas.='<tr class="gray">';
                                $html_semanas.=$cadena_header;
                                $html_semanas.='</tr>';
                                $html_semanas.='</tbody>';
                            }else{
                                 $html_semanas.='no hay semanas</td>';
                            } 
                            $total_liquidado=0;
                            $cont1=0;
                            $cont2=0;
                             $liquidacion_total=array();
                             $liquidacion_periodo=array();
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
                                     //////////////////   
                            if($personajes){
                                foreach ($personajes as $personaje) { 
                                  $acumulado_capitulos = 0; 
                                  $acumulado_dias = 0;
                                  $acumulado_residuo=0; 
                                  $acumulado_residuo_dias=0;
                                  $lista_capitulos = ""; 
                                 $capitulos_personaje = $this->model_herramientas->buscar_capitulos_elemento($personaje->id,$semana_limite['0']->min,$semana_limite['0']->max);
                                  
                                    if($capitulos_personaje['0']->capitulos){
                                                 $acumulado_capitulos = 0; 
                                                 $acumulado_residuo=0;
                                                 $lista_capitulos = "";
                                                 $html_semanas.='<tr id="id_'.$personaje->id.'" class="personaje_'.$personaje->id.'">';
                                                 $fecha="";

                                                  if($personaje->fecha_inicio_2!="" AND $personaje->fecha_inicio_2!="0000-00-00"){
                                                      $fecha = $personaje->fecha_inicio_2;
                                                    }

                                                    if($personaje->fecha_finalizacion!="" AND $personaje->fecha_finalizacion!="0000-00-00"){
                                                      $fecha_final = $personaje->fecha_finalizacion;
                                                    }else{
                                                      $fecha_final = "";
                                                    } 
                                                    $dias_trabajados = 0;
                                                   
                                                    
                                                     if($fechas_reporte_semanal){ 
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

                                                            $html_semanas.='<td class="semana_'.$num2.' semanas" data-capitulospagar="'.$capitulos_pagar.'">'.$capitulos_pagar.'</td>';
                                                            $html_semanas.='<td class="semana_'.$num2.' semanas" data-capitulospagar="'.$capitulos_pagar.'">'.number_format((double)$personaje->monto*$capitulos_pagar, 2, '.', ",").'</td>';
                                                            $liquidacion_periodo[$num2]=$liquidacion_periodo[$num2]+$personaje->monto*$capitulos_pagar;
                                                            
                                                            if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra))) >= strtotime($fecha_reporte_semanal2->fecha_muestra) AND 
                                                                    strtotime($fecha_reporte_semanal2->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2))) ):

                                                                if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal2->fecha_muestra_2)))) == "Sun" ):
                                                                        $acumulado_capitulos += $acumulado_residuo;
                                                                        $acumulado_residuo = 0;
                                                                        $acumulado_residuo_dias=0;
                                                                        $lista_capitulos.=$lista_residuo; 
                                                                        $lista_residuo = "";
                                                                        $html_semanas.='<!--SECCION OCULTA LISTA DE CAPITULOS-->';
                                                                        $html_semanas.='<td class="semana_'.$num2.' semanas">'.$acumulado_capitulos.'</td>';
                                                                        $html_semanas.='<td class="liquidacion semana_'.$num2.' semanas">';
                                                                        $html_semanas.='<!--CALCULO DE NOMINA A PAGAR-->';

                                                                                $liquidacion=0;
                                                                                if($personaje->id_tipo_contrato){
                                                                                    $cont1++;
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
                                                                                            $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
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

                                                                            $html_semanas.='<!--CALCULO DE NOMINA A PAGAR-->';
                                                                            $html_semanas.='$'.$liquidacion;
                                                                            $total_liquidado=$total_liquidado+$liquidacion;
                                                                            $html_semanas.='<div class="textLiquidacion hideLiquidacion box">';
                                                                                if ($acumulado_capitulos!=0):
                                                                                    $explode = explode(",", $lista_capitulos);
                                                                                    $html_semanas.='<br>';
                                                                                    foreach($explode as $e) {
                                                                                         $html_semanas.=$e;
                                                                                         $html_semanas.='<br>';
                                                                                    } 
                                                                                endif;
                                                                            $html_semanas.='</div>';
                                                                        $html_semanas.='</td>';
                                                                        $acumulado_capitulos=0; 
                                                                        $acumulado_dias=0;

                                                                 else:

                                                                    $html_semanas.='<!-- CAPITULOS SIGUIENTE LIQUIDACION -->';
                                                                    
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
                                                                        $liquidacion_residuo += $dias_pagar[0]->total;
                                                                    }

                                                                    $html_semanas.='<!--LIQUIDACION A -->';
                                                                    $html_semanas.='<td class="semana_'.$num2.' semanas">'.$acu_te = $acumulado_capitulos-$acumulado_residuo.'</td>';
                                                                    $html_semanas.='<td class="liquidacion semana_'.$num2.' semanas">';
                                                                    $html_semanas.='<!--CALCULO DE NOMINA A PAGAR-->';
                                                                        
                                                                            $liquidacion=0;
                                                                            $liquidacion_residuo=0;
                                                                            if($personaje->id_tipo_contrato){
                                                                                $cont2++;
                                                                              switch ($personaje->id_tipo_contrato) {
                                                                                case 1:
                                                                                  if($personaje->monto and $acu_te !=0){
                                                                                    $liquidacion = number_format($personaje->monto, 2, '.', ",");
                                                                                    $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
                                                                                  }
                                                                                  break;
                                                                                case 2:
                                                                                  $liquidacion =$personaje->monto*$acu_te;
                                                                                  $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                                                                  $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
                                                                                  $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                                                                  break;
                                                                                case 3:
                                                                                    $liquidacion=0;
                                                                                    if($personaje->monto){
                                                                                      if($acumulado_capitulos<11){
                                                                                        $liquidacion = $personaje->monto*$acu_te ;
                                                                                        $liquidacion_total[$num2]=$liquidacion_total[$num2]+$liquidacion;
                                                                                        $liquidacion_residuo = $personaje->monto*$acumulado_residuo;
                                                                                        $liquidacion = number_format(round($liquidacion,2), 2, '.', ",");
                                                                                      }else{
                                                                                        $liquidacion_total[$num2]=$liquidacion_total[$num2]+$personaje->monto;
                                                                                        $liquidacion = number_format($personaje->monto*13, 2, '.', ",");
                                                                                      }
                                                                                    }
                                                                                  break;
                                                                                case 4:
                                                                                  if($personaje->monto){
                                                                                    //$liquidacion = (($personaje->dias_trabajados-$dias_trabajados)*$personaje->monto)+$liquidacion_residuo;
                                                                                    //$liquidacion_residuo = $liquidacion_residuo*$personaje->monto;
                                                                                    $liquidacion = (($acumulado_dias)*$personaje->monto)+$liquidacion_residuo;
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
                                                                        
                                                                        $html_semanas.='<!--CALCULO DE NOMINA A PAGAR-->';
                                                                        $html_semanas.='$'.$liquidacion;
                                                                         $total_liquidado=$total_liquidado+$liquidacion;
                                                                        $html_semanas.='<div class="textLiquidacion hideLiquidacion box">';
                                                                            if ($acu_te!=0 AND $acu_te!=""):
                                                                                $explode = explode(",", $lista_capitulos);
                                                                                $html_semanas.='<br>';
                                                                                foreach($explode as $e) {
                                                                                 $html_semanas.=$e;
                                                                                 $html_semanas.='<br>';
                                                                                }
                                                                            endif;
                                                                        $html_semanas.='</div>';

                                                                    $html_semanas.='</td>';

                                                                    $html_semanas.='<!--LIQUIDACION B -->';

                                                                    $html_semanas.='<td class="semana_'.$num2.' semanas">'.$liquidacion_residuo.'</td>';
                                                                    $html_semanas.='<td class="liquidacion semana_'.$num2.' semanas">$'.number_format((double)$liquidacion_residuo, 2, '.', ",");
                                                                        $html_semanas.='<div class="textLiquidacion hideLiquidacion box">';
                                                                            $explode = explode(",", $lista_residuo);
                                                                            $html_semanas.='<br>';
                                                                            foreach($explode as $e) { 
                                                                             $html_semanas.=$e.'<br>';
                                                                            } 
                                                                        $html_semanas.='</div>';
                                                                    $html_semanas.='</td>';


                                                                 endif;
                                                                 $lista_capitulos = "";
                                                            endif;
                                                              $num2++; 
                                                        }
                                            }            
                                     }else{ 
                                      //$html_semanas.='<td>No hay semanas</td>';
                                     } 
                                 $html_semanas.='</tr>';
                                 } 
                             }
                                            if($personajes){ 
                                               $html_semanas.='<tr>';
                                               
                                                    $c=1;
                                                    $num2=$fechas_reporte_semanal['0']->inicio_semana;
                                                   foreach ($fechas_reporte_semanal as $fecha_reporte_semanal) { 
                                                    $html_semanas.=' <td></td>';
                                                    $html_semanas.=' <td>$'.number_format(round($liquidacion_periodo[$num2],2), 2, '.', ",").'</td>';


                                                       if ( strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra))) >= strtotime($fecha_reporte_semanal->fecha_muestra) AND 
                                                             strtotime($fecha_reporte_semanal->fecha_muestra_2) >= strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2))) ){
                                                              
                                                              if (date("D",strtotime('15'.'-'.date("m-Y", strtotime($fecha_reporte_semanal->fecha_muestra_2)))) == "Sun" ){
                                                                
                                                                        $html_semanas.='<td></td>';
                                                                        $html_semanas.='<td>$'.number_format(round($liquidacion_total[$num2],2), 2, '.', ",").'</td>';
                                                                    
                                                              }else{ 
                                                                          $html_semanas.='<td></td>';
                                                                          $html_semanas.='<td>$'.number_format(round($liquidacion_total[$num2],2), 2, '.', ",").'</td>';
                                                                          $html_semanas.='<td></td>';
                                                                          $html_semanas.='<td></td>';
                                                              }
                                                        }
                                                        $c++;$num2++;
                                                     }   

                                               $html_semanas.='</tr>';
                                             }

                  $data['html_personajes']=$html_personajes;
                  $data['html_semanas']=$html_semanas;
                  echo json_encode($data);

    }

    


    //AÑADE CEROS A AID DE SOLICITUD
    public function completar_id($id){
        $new_id ="";
        if($id){
            for ($i=strlen($id); $i < 5; $i++) { 
                $new_id.="0";
            }
            $new_id.=$id;
        }
        return $new_id;
    }

    //VALIDA PERMISOS USUARIO SOLICITUD
    public function permisos_solicitud($solicitud){
        $id_user = $this->session->userdata('id_pruduction_suite');
        $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
        $tipo_roles=$this->model_casting->multiples_roles($id_user);
        $permisos = "read";
        //MASTER
        if($tipo_usuario==1){
            $permisos = "write";
        }else{
            //COMPLIANCE
            if($tipo_usuario==10){
                $permisos = "read";
            }else{
                if($tipo_usuario!=5){
                    //POR TIPO USUARIO 
                    if(strpos($solicitud->tipo_usuario_estado, $tipo_usuario)){
                        $permisos = "write";
                    }else{
                        $permisos = "read";
                    }
                }else{
                    //POR ROL OTROS
                    if ($tipo_roles) {
                        foreach ($tipo_roles as $tipo_rol) {
                            if (strrpos(','.$solicitud->rol_estado, $tipo_rol->rol)>0){
                                $permisos = "write";
                            }
                        }
                    }  
                }

                //VALIDACIONES ESPECIALES DE SOLICITUD

                if ( ($solicitud->id_estado==3 
                    OR $solicitud->id_estado==5 
                    OR $solicitud->id_estado==10 
                    OR $solicitud->id_estado==17
                    OR $solicitud->id_estado==20) AND $permisos == "write" ) {
                    if($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1"){
                        if ($tipo_usuario != 7 and $tipo_usuario != 8) {
                             $permisos = "read";


                        }
                    }else{
                        if ($tipo_roles) {
                            foreach ($tipo_roles as $tipo_rol) {
                                if($tipo_rol->rol == 17 OR $tipo_rol->rol == 18){
                                    $permisos = "write";
                                    break;
                                }else{
                                    $permisos = "read";


                                }
                            }
                        }
                    }
                }

                if($solicitud->id_estado==5){
                            if(($tipo_usuario==7 and $solicitud->roles_lista=='Protagonista') or $solicitud->id_nacionalidad!=13){
                                $permisos = "write";
                            }elseif($tipo_roles and $solicitud->id_nacionalidad==13){
                                    foreach ($tipo_roles as $tipo_rol) {
                                        if($tipo_rol->rol == 18){
                                            $permisos = "write";
                                            break;
                                        }else{
                                            $permisos = "read";

                                        }
                                    }
                            }else{
                            $permisos = "read";
                            }
                }
                if($solicitud->id_estado==19){
                       if($tipo_usuario==4 and $solicitud->roles==1 and $solicitud->id_nacionalidad!=13){
                           $permisos = "write";
                       }elseif($tipo_usuario==7 and $solicitud->roles!=1 and $solicitud->id_nacionalidad!=13){
                          $permisos = "write";
                       }else{
                        $permisos = "read";
                       }
                }
                if($tipo_usuario==7 and $solicitud->id_estado==3){
                    $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud->id_solicitud,13,3);
                    if($aprobacion_juridica){
                        $permisos = "read";
                    }else{
                        $permisos = "write";
                    }
                }


                 if ($solicitud->id_estado==20) {


                      if (($solicitud->condiciones_especiales!="" OR $solicitud->roles=="1") OR $solicitud->id_forma_pago==1  OR $solicitud->id_tipo_moneda==2 and $tipo_usuario==7) {
                            $permisos = "write";
                      }else{
                            $permisos = "read";

                      }

                      if (($solicitud->condiciones_especiales=="" OR $solicitud->roles!="1") OR $solicitud->id_forma_pago!=1  OR $solicitud->id_tipo_moneda!=2 and $tipo_usuario!=7) {
                              if($tipo_roles){
                                foreach ($tipo_roles as $tipo_rol) {
                                    if($tipo_rol->rol == 18){
                                        $permisos = "write";
                                        break;
                                    }else{
                                        $permisos = "read";

                                    }
                                }
                               } 
                      }

                 } 

               
                //FIN VALIDACIONES ESPECIALES DE SOLICITUD
            }

            if($solicitud->id_estado==3 and $solicitud->id_nacionalidad==13 and $tipo_usuario==4){
                $permisos = "read";
            }

        }

        return $permisos;
    }

    //COLOR DE SOLICITUD
    public function color_solicitud($idestado){
        $color = "000";
        switch ($idestado) {
            case 1:
                $color = "ccc";
                break;
            case 2:
                $color = "BAF188";
                break;
            case 3:
                $color = "85c646";
                break;
            case 4:
                $color = "ff9400";
                break;
            case 5:
                $color = "B2E6FD";
                break;
            case 6:
                $color = "fbdd85";
                break;
            case 7:
                $color = "E79F32";
                break;
            case 8:
                $color = "E79F32";
                break;
            case 9:
                $color = "E79F32";
                break;
            case 10:
                $color = "B6BFF1";
                break;
            case 11:
                $color = "fee93e";
                break;
            case 12:
                $color = "fee93e";
                break;
            case 13:
                $color = "B2E6FD";
                break;
            case 14:
                $color = "B2E6FD";
                break;
            case 16:
                $color = "6072D6";
                break;
            case 17:
                $color = "fdff91";
                break;
            case 19:
                $color = "d9f3c2";
                break;
            case 20:
                $color = "B6BFF1";
             break;
        }
        return $color;
    }

    public function buscar_sociedad(){
        $id_sociedad = $_POST['id_sociedad'];
        $sociedad = $this->model_casting->get_sociedad_by_id($id_sociedad);
        $data['sociedad'] = $sociedad;
        echo json_encode($data);
    }

    public function orden_columnas(){
        $tipo = $this->input->post('tipo');
        $id_user = $this->session->userdata('id_pruduction_suite');
        $campos =  $this->input->post('campos_columnas');
        $data=array('id_usuario'=>$id_user,'campos'=>$campos,'tipo'=>$tipo);
        $existe = $this->model_casting->buscar_columnas($data);
        if($existe){
          $this->model_casting->actualizar_columnas($data);
        }else{
          $this->model_casting->agregar_columnas($data);
        }
        if($tipo==6){
            redirect($this->lang->lang().'/casting/solicitudes');
        }else{
            redirect($this->lang->lang().'/casting/personajes');
        }
    }

    //COLUMNAS EN PERSONAJES
    public function campos_personajes(){
        $campos[0]= "Personaje";
        $campos[1]= "Rol";
        $campos[2]= "Estatus personaje";
        $campos[3]= "Solicitudes";
        $campos[4]= "Actor";
        $campos[5]= "Primer día plan";
        $campos[6]= "Responsable";
        return $campos;
    }

    public function responsablePersonaje($id_solicitud){
        $clase_estado = "";
        $reponsable = "";
        $estado = "";
        if ($id_solicitud) {
            $solicitud = $this->model_casting->solicitud_id($id_solicitud);
            switch ($solicitud[0]->id_estado) {
                case 1:
                    $clase_estado = "td_orange generando_solicitud";
                    $reponsable = "Casting / Secretaria de producción" ;
                    $estado = "En Proceso - Generando Solicitud";

                    break;
                case 2:
                    $clase_estado = "td_orange aprobacion_produccion";
                    $reponsable = "Producción" ;
                    $estado = "En Proceso - Aprobación Producción";
                    break;
                case 3:
                    $clase_estado = "td_orange generando_contrato";
                    $reponsable = "Legal" ;
                    $estado = "En Proceso - Generando Contrato";
                    break;
                case 4:
                    $clase_estado = "td_orange aprobacion_produccion";
                    $reponsable = "Producción" ;
                    $estado = "En Proceso - Aprobación Producción";
                    break;
                case 5:
                    $clase_estado = "td_orange generando_contrato";
                    $reponsable = "Legal" ;
                    $estado = "En Proceso - Generando Contrato";
                    break;
                case 6:
                    $clase_estado = "td_orange generando_contrato";
                    $reponsable = "Legal" ;
                    $estado = "En Proceso - Generando Contrato";
                    break;
                case 7:
                    $clase_estado = "td_orange aprobacion_produccion";
                    $reponsable = "Producción" ;
                    $estado = "En Proceso - Aprobación Producción"; 
                    break;
                case 8:
                    $clase_estado = "td_orange aprobacion_produccion";
                    $reponsable = "Producción" ;
                    $estado = "En Proceso - Aprobación Producción";
                    break;
                case 9:
                    $clase_estado = "td_orange aprobacion_produccion";
                    $reponsable = "Producción" ;
                    $estado = "En Proceso - Aprobación Producción";
                    break;
                case 10:
                    $clase_estado = "td_orange proceso_firma";
                    $reponsable = "Legal" ;
                    $estado = "En Proceso - Proceso de Firma";
                    break;
                case 11:
                    $clase_estado = "td_green asignado";
                    $reponsable = "-" ;
                    $estado = "Asignado";
                    break;
                case 12:
                    $clase_estado = "td_purple expirado";
                    $reponsable = "-" ;
                    $estado = "Expirado";
                    break;
                case 19:
                    $clase_estado = "td_orange aprobacion_extranjero";
                    $reponsable = "Jurídica" ;
                    $estado = "En Proceso - Aprobación extranjero";
                    break;
                case 20:
                    $clase_estado = "td_orange proceso_firma";
                    $reponsable = "Legal" ;
                    $estado = "En Proceso - Proceso de Firma";
                    break;

                default:
                    $clase_estado = "td_red no_asignado";
                    $reponsable = "Casting" ;
                    $estado = "No asignado";
                    break;
            } 


            if( ($solicitud[0]->id_estado==3) AND ($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1" OR $solicitud[0]->id_forma_pago==1  OR $solicitud[0]->id_tipo_moneda==2)){
                $aprobacion_juridica = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,13);
                $aprobacion_finanzas = $this->model_casting->buscar_aprobacion($solicitud[0]->id_solicitud,14); 
                $responsable =  strtoupper($solicitud[0]->responsable); 
                if($aprobacion_juridica){
                  $responsable = str_replace("JURíDICA -", '',strtoupper($responsable));
                  $responsable = str_replace("JURíDICA", '',strtoupper($responsable));
                }
                if($aprobacion_finanzas){
                  $responsable = str_replace(strtoupper("- FINANZAS"), '', $responsable );
                  $responsable = str_replace("FINANZAS", '',strtoupper($responsable));
                }
                $responsable = str_replace('COORDINADOR DE CONTRATO -', '', $responsable);
            }else{
                if($solicitud[0]->id_estado==5 AND ($solicitud[0]->condiciones_especiales!="" OR $solicitud[0]->roles=="1" OR $solicitud[0]->id_forma_pago==1  OR $solicitud[0]->id_tipo_moneda==2)){
                  $responsable =  "JURíDICA";
                }else{
                    if ($solicitud[0]->id_estado!=19) {
                        $responsable = str_replace(strtoupper('- JURíDICA'), '', strtoupper($solicitud[0]->responsable));
                        $responsable = str_replace(strtoupper('JURíDICA'), '', strtoupper($responsable));
                        $responsable = str_replace(strtoupper('- FINANZAS'), '', strtoupper($responsable));
                        $responsable = str_replace(strtoupper('FINANZAS'), '', strtoupper($responsable));
                    }else{
                        $responsable = strtoupper($solicitud[0]->responsable);
                    }
                }
            }

            $data['clase_estado'] = $clase_estado;
            $data['responsable'] = $responsable ;
            $data['estado'] = $estado;

        }else{
            $data['clase_estado'] = "td_red no_asignado";
            $data['responsable'] = "Casting" ;
            $data['estado'] = "No asignado";
        }
        return $data;
    }

    public function numeracion_otro_si($id_solicitud, $id_otro_si){
        $query = $this->db->query("SELECT count(solicitudes.id) AS numero FROM solicitudes
        WHERE tipo = 2 AND solicitudes.id_solicitud_anexa = ".$id_solicitud." AND id < ".$id_otro_si.";");
        if ($query->num_rows>0){
            $query =  $query->result();
            //if ($query[0]->numero==0) {
            //    $numero = 1;
            //}else{
                $numero = $query[0]->numero+1;
            //}
        }
        if (strlen($numero)<2) {
            $numero = '0'.$numero;
        }
        return $numero;
    }

    public function __reporteMail(){
        $sql=" WHERE p.estado = 1";
        $producciones=$this->model_produccion->producciones_all($sql);

        if ($producciones) {
            foreach ($producciones as $produccion) {

                $cadena_personajes_expirados = "";
                $cadena_personajes_sin_contrato = "";
                $cadena_solicitudes_rechazadas = "";
                $cadena_solicitudes_anuladas = "";
                $cadena_solicitudes_aprobar = "";

                $personajes_expirados=$this->model_casting->get_personajes_expirados($produccion['id_produccion']);
                if ($personajes_expirados) {
                    foreach ($personajes_expirados as $key=>$personaje_expirados) {
                        if (strtotime($personaje_expirados->fecha_contrato)<strtotime($personaje_expirados->fecha_plan_diario)) {
                             if ($key%2!=0) {
                                $cadena_personajes_expirados .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_personajes_expirados .= '
                                <tr style="background: none">';
                            }
                            $cadena_personajes_expirados.="
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->actor)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->elemento)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_expirados->rol)."</td>
                                <td style='border: 1px solid black;'>".strtoupper(date('d-M-Y', strtotime($personaje_expirados->fecha_plan_diario)))."</td>
                            </tr>";
                        }
                    }
                    if ($cadena_personajes_expirados!=""){
                        $cadena_personajes_expirados = '<label>Personajes Expirados</label><table border="2">'. $cadena_personajes_expirados.'</table>';
                    }
                }

                $personajes_sin_contrato = $this->model_casting->get_personajes_sin_contrato($produccion['id_produccion']);
                if ($personajes_sin_contrato) {
                    foreach ($personajes_sin_contrato as $key=>$personaje_sin_contrato) {
                        if ($personaje_sin_contrato->fecha_plan_diario!="" AND $personaje_sin_contrato->fecha_plan_diario!="0000-00-00") {
                                
                            if ($key%2!=0) {
                                $cadena_personajes_sin_contrato .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_personajes_sin_contrato .= '
                                <tr style="background: none">';
                            }
                            $cadena_personajes_sin_contrato.="
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->elemento)."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->rol)."</td>
                                <td style='border: 1px solid black;'>".strtoupper(date('d-M-Y', strtotime($personaje_sin_contrato->fecha_plan_diario)))."</td>
                                <td style='border: 1px solid black;'>".strtoupper($personaje_sin_contrato->actor)."</td>
                            </tr>";
                        }
                    }
                    if ($cadena_personajes_sin_contrato!=""){
                        $cadena_personajes_sin_contrato = '<label><b>Personaje Sin Contratos a grabar en los próximos 7 días</b></label>
                        <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                        <tr style="background: #808080;font-weight: bold;color:white;">
                            <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                            <td style="border: 1px solid black;"><strong>ROL</strong></td>
                            <td style="border: 1px solid black;"><strong>FECHA A GRABAR</strong></td>
                            <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                        </tr>'. $cadena_personajes_sin_contrato.'</table>';
                    }
                }


                $solicitudes_aprobar="";
                $solicitudes_rechazadas=$this->model_casting->get_solicitudes_rechazadas($produccion['id_produccion']);
                if ($solicitudes_rechazadas) {
                    $cadena_solicitudes_rechazadas .= '
                    <label><b>Solicitudes Rechazadas</b></label>
                    <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                            <tr style="background: #808080;font-weight: bold;color:white;">
                                <td style="border: 1px solid black;"><strong>NRO SOLICITUD</strong></td>
                                <td style="border: 1px solid black;"><strong>ESTADO</strong></td>
                                <td style="border: 1px solid black;"><strong>FECHA RECHAZADA</strong></td>
                                <td style="border: 1px solid black;"><strong>RECHAZADO POR</strong></td>
                                <td style="border: 1px solid black;"><strong>RAZON</strong></td>
                                <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                                <td style="border: 1px solid black;"><strong>ROL</strong></td>
                                <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                            </tr>';
                    foreach ($solicitudes_rechazadas as $key=>$solicitud_rechazada) {
                        if ($solicitud_rechazada->tipo == 2) {
                           $id = $this->completar_id($solicitud_rechazada->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud_rechazada->id_solicitud_anexa,$solicitud_rechazada->id_solicitud);
                        }else{
                           $id = $this->completar_id($solicitud_rechazada->id_solicitud);
                        }

                        if ($key%2!=0) {
                            $cadena_solicitudes_rechazadas .= '
                            <tr style="background: #e6e4e5">';
                        }else{
                            $cadena_solicitudes_rechazadas .= '
                            <tr style="background: none">';
                        }

                        $cadena_solicitudes_rechazadas .= '
                            <td style="border: 1px solid black;">'.$id.'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->estado).'</td>
                            <td style="border: 1px solid black;">'.strtoupper(date('d-M-Y', strtotime($solicitud_rechazada->fecha_aprobacion))).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->usuario).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->notas).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->elemento).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_rechazada->rol).'</td>
                            <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud_rechazada->actor).'</td>
                        </tr>';
                    }
                    $cadena_solicitudes_rechazadas .= '</table>';
                }

                $solicitudes_anuladas=$this->model_casting->get_solicitudes_anuladas($produccion['id_produccion']);

                if ($solicitudes_anuladas) {
                    $cadena_solicitudes_anuladas .= '
                    <label><b>Solicitudes Rechazadas</b></label>
                    <table style="border-collapse: collapse;margin: 0;background: none;text-align: center;">
                            <tr style="background: #808080;font-weight: bold;color:white;">
                                <td style="border: 1px solid black;"><strong>NRO SOLICITUD</strong></td>
                                <td style="border: 1px solid black;"><strong>FECHA ANULADA</strong></td>
                                <td style="border: 1px solid black;"><strong>ANULADA POR</strong></td>
                                <td style="border: 1px solid black;"><strong>RAZON</strong></td>
                                <td style="border: 1px solid black;"><strong>PERSONAJE</strong></td>
                                <td style="border: 1px solid black;"><strong>ROL</strong></td>
                                <td style="border: 1px solid black;"><strong>ACTOR</strong></td>
                            </tr>';
                    foreach ($solicitudes_anuladas as $key=>$solicitud_anulada) {
                        if ($solicitud_anulada->tipo == 2) {
                           $id = $this->completar_id($solicitud_anulada->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud_anulada->id_solicitud_anexa,$solicitud_anulada->id_solicitud);
                        }else{
                           $id = $this->completar_id($solicitud_anulada->id_solicitud);
                        }

                        if ($key%2!=0) {
                            $cadena_solicitudes_anuladas .= '
                            <tr style="background: #e6e4e5">';
                        }else{
                            $cadena_solicitudes_anuladas .= '
                            <tr style="background: none">';
                        }
                        $cadena_solicitudes_anuladas .= '
                            <td style="border: 1px solid black;">'.$id.'</td>
                            <td style="border: 1px solid black;">'.strtoupper(date('d-M-Y', strtotime($solicitud_anulada->fecha_aprobacion))).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->usuario).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->notas).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->elemento).'</td>
                            <td style="border: 1px solid black;">'.strtoupper($solicitud_anulada->rol).'</td>
                            <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud_anulada->actor).'</td>
                        </tr>';
                    }
                    $cadena_solicitudes_anuladas .= '</table>';
                }

                //$usuarios = $this->model_casting->get_usuarios_mail($produccion['id_produccion']);
                $usuarios = $this->model_casting->get_usuarios_mail_two($produccion['id_produccion']);
                foreach ($usuarios as $usuario) {
                    $cadena_solicitudes_aprobar = "";
                    $solicitudes = false;
                    if ($usuario->id_tipoUsuario==1 OR $usuario->id_tipoUsuario==3) {
                        $sql =" AND ( produccion.id = ".$produccion['id_produccion']." AND solicitudes.tipo!=2 ) ";
                        $solicitudes = $this->model_casting->get_all_solicitudes($sql);
                    }else{
                        if ($usuario->id_tipoUsuario!=5) {
                            $solicitudes = $this->model_casting->get_solicitudes_tipo_usuario($usuario->id_tipoUsuario);
                        }else{
                            $solicitudes = $this->model_casting->get_solicitudes_rol_otros($usuario->id_rol);
                        }
                    }

                    if ($solicitudes) {
                        $cadena_solicitudes_aprobar .= "
                        <label><b>Solcitudes por aprobar</b></label>
                        <table style='border-collapse: collapse;margin: 0;background: none;'>
                        <tr style='background-color: #808080; font-weight: bold; color:white;text-align: center;'>
                                <td style='border: 1px solid black;'><strong>NRO SOLICITUD</strong></td>
                                <td style='border: 1px solid black;'><strong>PERSONAJE</strong></td>
                                <td style='border: 1px solid black;'><strong>ROL</strong></td>
                                <td style='border: 1px solid black;'><strong>ACTOR</strong></td>
                                <td style='border: 1px solid black;'><strong>FECHA A GRABAR</strong></td>
                        </tr>";
                        foreach ($solicitudes as $key=>$solicitud) {
                            if ($solicitud->tipo == 2) {
                               $id = $this->completar_id($solicitud->id_solicitud_anexa).'-'.$this->numeracion_otro_si($solicitud->id_solicitud_anexa,$solicitud->id_solicitud);
                            }else{
                               $id = $this->completar_id($solicitud->id_solicitud);
                            }
                            if ($key%2!=0) {
                                $cadena_solicitudes_aprobar .= '
                                <tr style="background: #e6e4e5">';
                            }else{
                                $cadena_solicitudes_aprobar .= '
                                <tr style="background: none">';
                            }
                            $fecha = $this->model_casting->fecha_maxima($solicitud->id_elemento);

                            if ($fecha AND $fecha[0]->fecha_inicio) {
                                $fecha = strtoupper(date('d-M-Y', strtotime($fecha[0]->fecha_inicio)));
                            }else{
                                $fecha = '-';
                            }

                            $cadena_solicitudes_aprobar .='
                                <td style="border: 1px solid black;">'.$id.'</td>
                                <td style="border: 1px solid black;">'.strtoupper($solicitud->elementos).'</td>
                                <td style="border: 1px solid black;">'.strtoupper($solicitud->roles_lista).'</td>
                                <td style="border: 1px solid black;white-space: nowrap;">'.strtoupper($solicitud->actor).'</td>
                                <td style="border: 1px solid black;"">'.$fecha.'</td>
                            </tr>';
                        }
                        $cadena_solicitudes_aprobar .= '</table>';                   
                    }


                    $mail = new PHPMailer (); 
                    $mail->From = "info@rtitv.com.co"; 
                    $mail->FromName = "info@rtitv.com.co"; 
                    $mail->AddAddress ($usuario->correo); 
                    $mail->CharSet = 'UTF-8'; 
                    $mail->Subject = "Staging - Production Suite – Notificación Diaria de Solicitudes"; 
                    $mail->Body = '<div style="font-family: "Calibri","sans-serif";"> 
                                    <img src="'.base_url('images/produccion/production_suite.jpg').'" width="150px" style="float: right;"><br>
                                    <div style="clear: both;"></div>
                                    <b>Reporte diario al '.date('d').' de '.date('M').' del '.date('Y').'</b><br> 
                                    <p>Los siguientes Personajes/Actores están pendiente por acciones:<br><br>'.
                                    '<strong>PRODUCCIÓN: </strong>'.strtoupper($produccion['nombre_produccion']).'<br><br>'.
                                    $cadena_personajes_expirados.'<br><br>'.
                                    $cadena_personajes_sin_contrato.'<br><br>'.
                                    $cadena_solicitudes_aprobar.'<br><br>'.
                                    $cadena_solicitudes_rechazadas.'<br><br>'.
                                    $cadena_solicitudes_anuladas.'<br><br>
                                    </div>';
                    $mail->IsHTML(true); 
                    $mail->IsSMTP(); 
                    $mail->Host = 'ssl://smtp.gmail.com'; 
                    $mail->Port = 465; 
                    $mail->SMTPAuth = true; 
                    $mail->Username = 'alexander.ospina@cincoveinticinco.com'; 
                    $mail->Password = '1087997536'; 
                    if(!$mail->Send()) { 
                      echo 'Error: ' . $mail->ErrorInfo; 
                    } else { 
                      echo 'Mail enviado!'; 
                    } 
                    break;
                }
                break;
            }
        }   
    }

    public function eliminar_actor($id_actor){
        $this->model_casting->delete_actor($id_actor);
        redirect($this->lang->lang().'/casting/index');
    }

    public function crearManager(){
        $lista=$this->model_casting->selecttManager();
        $data['lista']=$lista;
        $data['view']='casting/crearManager';
        $this->load->view('includes/template',$data);        
    }
     public function eliminarManager($id_manager){
        //set Null relacion
        $actores=$this->model_casting->selectActorManager($id_manager);
        if($actores):
            foreach ($actores as $ac ) {
            $datos = array(
                'id' => $ac->id,
                'id_manager' => NULL
                );
            $this->model_casting->update_actor($datos);
            }
        endif;
        //delete
        $this->model_casting->delete_manager($id_manager);
        redirect($this->lang->lang().'/casting/crearManager');
    }
    public function crearManagers(){
        print ("<script type=\"text/javascript\">alert('Ya existe el Registro');</script>");
        $lista=$this->model_casting->selecttManager();
        $data['lista']=$lista;
        $data['view']='casting/crearManager';
        $this->load->view('includes/template',$data);        
    }

    public function insertManager(){
        $this->form_validation->set_rules('email','email','required');   
        $this->form_validation->set_rules('contrasena','contrasena','required'); 
        if ($this->form_validation->run()==FALSE) {
            $this->index();
        }else{
            if ($this->input->post('contrasena')==$this->input->post('contrasena_repetir')) {
                $data = array(
                'nombre' => $this->input->post('nombre'),
                'email' => $this->input->post('email'),
                'contrasena'=>sha1($this->input->post('contrasena')),
                'tipo_usuario' => 1,                
                );
                $datos=$this->model_casting->selecttManagerEmail($this->input->post('email'));
                if (!$datos) {
                    $this->model_casting->insertManager($data);
                    redirect($this->lang->lang().'/casting/crearManager');
                }else{
                    print ("<script type=\"text/javascript\">alert('Ya existe el Registro');</script>");  
                    redirect($this->lang->lang().'/casting/crearManagers');              
                }      
                
            } 
            else{
                $data['view']='casting/crearManager';
                $this->load->view('includes/template',$data); 
                print ("<script type=\"text/javascript\">alert('la Contraseña no conciden');</script>");                
            }        
            //redirect($this->lang->lang().'/casting/crearManager');                   
        }
    }

    public function updateEstado($id,$estado){ 
        $datos=array(
            'id' => $id,
            'tipo_usuario' =>$estado
        );
        $estado=$this->model_casting->updateEstado($datos);        
        redirect($this->lang->lang().'/casting/crearManager');
    }

    public function editarManager($id){
        $datos=$this->model_casting->selecttManagerAll($id);
        $data['datos']=$datos;
        $data['view']='casting/editarManager';
        $this->load->view('includes/template',$data);        
    }

    public function updateManager($id,$estado){ 
        $datos=array(
            'id' => $this->input->post('id'),
            'contrasena'=>sha1($this->input->post('contrasena')),
        );
        $estado=$this->model_casting->updateEstado($datos);        
        redirect($this->lang->lang().'/casting/crearManager');
    }

    
    public function asignar_actor_manager(){
        $id_solicitud=$_POST['id_solicitud'];
        $id_actor=$_POST['id_actor'];
        $id_manager=$_POST['id_manager'];
        $id_manageractual=$_POST['id_manageractual'];
         
         $data=array('id'=>$id_actor,'id_manager'=>$id_manager);
         $this->model_casting->update_actor($data);
          
         $data=array('id'=>$id_solicitud,'id_user_aprobacion'=>$this->session->userdata('id_pruduction_suite'),
                     'fecha_aprobacion'=>date('Y-m-d m:s'),'id_manager_anterior'=>$id_manageractual,'estado'=>1);
         $this->model_casting->update_solicitudes_actores($data);
         echo json_encode(true);
    }

    public function solicitudes_actores(){
        $solicitudes_pendientes=$this->model_casting->solicitudes_actor_asignacion(0);
        $solicitudes_actuales=$this->model_casting->solicitudes_actor_asignacion(1);
       
        $data['produccion'] = false;
        $data['solicitudes_pendientes'] = $solicitudes_pendientes;
        $data['solicitudes_actuales'] = $solicitudes_actuales;
        $data['view']='casting/solicitudes_actores';
        $this->load->view('includes/template',$data);   
    }


    public function selectContrato($id_solicitud){
        $solicitud = $this->model_casting->solicitud_id($id_solicitud);
        //echo $this->db->last_query();
        $contrato_select = false;
        $responsables_contrato = $this->model_casting->responsables_contrato();

        $pasaporte = $this->model_casting->documento_extranjero($solicitud[0]->id_actor,'5');
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
                @$visa[0]->vigencia = " <span class=hightltght_yellow>".$dias[intval(date("d",strtotime($visa[0]->vigencia)))]." (".date("d",strtotime($visa[0]->vigencia)).") de ".$meses[date("n")]." de ".date("Y",strtotime($visa[0]->vigencia))."</span>"; 
            }else{
                @$visa[0]->vigencia = '-';
            }
        }


        if($solicitud[0]->contrato!="" and $solicitud[0]->contrato!=0){
            $contrato_select = $solicitud[0]->contrato;
        }else{
           
            if($solicitud[0]->tipo==2){
                $solicitud_anexa = $this->model_casting->solicitud_id($solicitud[0]->id_solicitud_anexa);
                
                //PERSONA NATURAL
                if(!$solicitud[0]->nit_sociedad){
                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 24;
                        }
                        if ($solicitud[0]->id_forma == 2) {
                           $contrato_select = 21;
                        }
                   // }else{
                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 23;
                        }
                   // }
                }

                //PERSONA JURIDICA

                if($solicitud[0]->nit_sociedad){

                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 19;
                        }
                        if ($solicitud[0]->id_forma == 2) {
                           $contrato_select = 22;
                        }
                   // }else{

                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 20;
                        }
                   // }
                }

                //MENOR DE EDAD

                if($solicitud[0]->edad<18){
                    //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                        if ($solicitud[0]->id_forma == 5) {
                           $contrato_select = 33;
                        }
                        if ($solicitud[0]->id_forma == 2) {
                           $contrato_select = 32;
                        }
                    //}else{
                        //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                        else{
                            $contrato_select = 31;
                        }
                   // }
                }

                //EXTRANJERO

                if ($solicitud[0]->id_nacionalidad!=13) {
                    if(!$solicitud[0]->nit_sociedad){
                       //if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                            if ($solicitud[0]->id_forma == 5) {
                               $contrato_select = 28;
                            }
                            if ($solicitud[0]->id_forma == 2) {
                               $contrato_select = 26;
                            }
                        //}else{
                            //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                            else{
                                $contrato_select = 27;
                            }
                        //}
                    } else{
                       // if ($solicitud_anexa[0]->id_forma != $solicitud[0]->id_forma || $solicitud_anexa[0]->honorarios != $solicitud[0]->honorarios) {
                            if ($solicitud[0]->id_forma == 5) {
                               $contrato_select = 25;
                            }
                            if ($solicitud[0]->id_forma == 2) {
                               $contrato_select = 29;
                            }
                        //}else{
                            //if ($solicitud_anexa[0]->fecha_inicio != $solicitud[0]->fecha_inicio || $solicitud_anexa[0]->fecha_final != $solicitud[0]->fecha_final) {
                            else{
                                $contrato_select = 30;
                            }
                       // }
                    }
                }
   
            }else{

                //PERSONA NATURAL
                if(!$solicitud[0]->nit_sociedad){
                    if ($solicitud[0]->id_forma==5) {
                        $contrato_select = 4;
                    }
                    if ($solicitud[0]->id_forma==3) {
                        $contrato_select = 3;
                    }
                    if ($solicitud[0]->id_forma==2) {
                        $contrato_select = 18;

                    }
                }

                //MENOR DE EDAD
                if($solicitud[0]->edad<18){
                    if ($solicitud[0]->id_forma==2) {
                       $contrato_select = 15;
                    }
                    if ($solicitud[0]->id_forma==5) {
                       $contrato_select = 16;
                    }
                }

                //EXTRANJERO
                if ($solicitud[0]->id_nacionalidad!=13) {
                    if($solicitud[0]->nit_sociedad){

                        if ($solicitud[0]->id_forma==5) {
                            if ($solicitud[0]->id_sociedad==1) {
                                $contrato_select = 12;
                            }else{
                                $contrato_select = 9;
                            }
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 10;
                        }
                        if ($solicitud[0]->id_forma==2) {
                            if ($solicitud[0]->id_sociedad==1) {
                                $contrato_select = 14;
                            }else{
                                $contrato_select = 11;
                            }
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 7;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 13;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 8;
                        }
                    }
                    
                }

                //PERSONA JURIDICA
                if($solicitud[0]->nit_sociedad and $solicitud[0]->id_nacionalidad==13){
                     
                    if ($solicitud[0]->id_sociedad==17) {
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 6;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 5;
                        }
                    }else{
                        if ($solicitud[0]->id_forma==2) {
                            $contrato_select = 2;
                        }
                        if ($solicitud[0]->id_forma==3) {
                            $contrato_select = 1;
                        }
                        if ($solicitud[0]->id_forma==5) {
                            $contrato_select = 17;
                        }
                    }
                }
            }
        }

        return $contrato_select;
    }
    public function fechaFormat($fecha){
        $meses = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $meses_es = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        $cont=0;
         $f=str_replace('ene','Jan',$fecha); 
         $f=str_replace('feb','Feb',$f); 
         $f=str_replace('mar','Mar',$f); 
         $f=str_replace('abr','Apr',$f);
         $f=str_replace('may','May',$f);
         $f=str_replace('jun','Jun',$f);
         $f=str_replace('jul','Jul',$f); 
         $f=str_replace('ago','Aug',$f); 
         $f=str_replace('sep','Sep',$f); 
         $f=str_replace('oct','Oct',$f); 
         $f=str_replace('nov','Nov',$f); 
         $f=str_replace('dic','Dec',$f); 
         
         return date("Y-m-d",strtotime($f));
        //echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
    }


} ?>