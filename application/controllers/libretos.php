<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Libretos extends CI_Controller {


    public function __construct (){         
        parent :: __construct (); 
        $this->load->model('model_plan_produccion');
        $this->load->model('model_produccion');
        $this->load->model('model_capitulos');
        $this->load->model('model_admin');
        $this->load->model('model_escenas_2');
        $this->load->model('model_escenas');
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

    public function index($id, $capitulos="", $from="", $to=""){


        $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
        if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
            $id_user=$this->session->userdata('id_pruduction_suite');
            $user=$this->model_admin->rolUserId($id_user);
            $tipo_rol=$user['0']['id_rol_otros'];

            $continuar=0;
              if($user){
                  foreach ($user as $u) {
                    if($u['id_rol_otros']== 1 or $u['id_rol_otros']== 6 or $u['id_rol_otros']== 9 or $u['id_rol_otros']== 2 or $u['id_rol_otros']== 10
                        or $u['id_rol_otros']== 11 or $u['id_rol_otros']== 12 or $u['id_rol_otros']== 15){
                      $continuar=1;
                      break;
                    }
                  }
             }
            if($continuar==1 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
                    $produccion = $this->model_plan_produccion->produccion_id($id);
                     
                     $limit=30;
                     if(isset($_COOKIE['limit'])){
                        $limit=$_COOKIE['limit'];
                     }

                    
                    if($capitulos=="" OR $capitulos== false){
                          $estados_user=$this->model_capitulos->estados_libretos_user($this->session->userdata('id_pruduction_suite'));
                        if($estados_user){

                            $sql="";
                            $estados = explode(',', $estados_user['0']->campos);
                            for ($i=0; $i < count($estados); $i++) { 
                                if($estados[$i]!=""){
                                    $sql.= " AND estado != ".$estados[$i];
                                }
                            }
                            $capitulos = $this->model_capitulos->capitulos_produccion_limit($id,0,$limit,$sql);
                            $data['estados_user'] = $estados_user['0']->campos;
                            $total=$this->model_capitulos->capitulos_produccion_total($id,$sql);
                            $total=count($total);
                        }else{
                           $capitulos = $this->model_capitulos->capitulos_produccion_limit($id,0,$limit," AND estado != 5"); 
                           $data['estados_user'] = '';
                           $total=$this->model_capitulos->capitulos_produccion_total($id," AND estado != 5");
                            $total=count($total);
                        }

                    }
                    $usuario_permisos = $this->permisos_usuarios($id);
                    $capitulos_complete = $this->model_capitulos->capitulos_produccion($id);
                    $capitulos_complete2 = $total;
                    $dias_aire = $this->model_capitulos->contar_dias_aire($id);
                    $estados = $this->model_capitulos->list_estados(); 
                     
                    $data['produccion'] = $produccion;
                    $data['produccion'] = $produccion;
                    $data['capitulos'] = $capitulos;
                    $data['capitulos_complete'] = $capitulos_complete;
                    $data['capitulos_complete2'] = $capitulos_complete2;
                    $data['dias_aire'] = $dias_aire;
                    $data['view']='capitulos/index';
                    $data['from']=$from;
                    $data['estados']=$estados;
                    $data['usuario_permisos']=$usuario_permisos;
                    $data['to']=$to;
                    $data['limit']=$limit;
                    
                    $this->load->view('includes/template',$data);   
            }else{
                redirect ($this->lang->lang().'/produccion/producciones');
            }       
        }else{
            redirect ($this->lang->lang().'/produccion/producciones');
        }      
    }

    public function buscarcapitulo(){
        $numero=$this->input->post('numero');
        $id_produccion=$this->input->post('id_produccion');
        if($this->model_capitulos->capitulo_id($numero, $id_produccion)!= false){
            return false;
        }else{
            return true;
        }
    }
    
    public function agregar_capitulo(){
        $this->form_validation->set_rules('numero','numero','required');
        if($this->input->post('numero')!="" AND $this->input->post('numero')!=" "){
            $this->form_validation->set_rules('existe','existe','callback_buscarcapitulo');
        }
        $this->form_validation->set_message('required','%s es requerido');
        $this->form_validation->set_message('buscarcapitulo','Este capítulo ya exite');
        if ($this->form_validation->run()==FALSE) {
            $id = $this->input->post('id_produccion');
            $this->index($id);
        }else{
            $numero = $this->input->post('numero');
            $cadena = "";
            
            $id_produccion = $this->input->post('id_produccion');
            if($numero==1){
                $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
                $fecha_aire = $produccion[0]->fecha_aire;
                $data=array(
                    'id_produccion'=>$id_produccion,
                    'numero'=> $numero,
                    'duracion_estimada'=> '00:00',
                    'estado'=> 1,
                    'fecha_aire'=>$fecha_aire
                );
                $this->model_capitulos->insertar_capitulo($data);
                $idcapitulo = mysql_insert_id();
            }else{
                $data=array(
                    'id_produccion'=>$id_produccion,
                    'numero'=> $numero,
                    'duracion_estimada'=> '00:00',
                    'estado'=> 1
                );
                $this->model_capitulos->insertar_capitulo($data);
                $capitulo = $this->model_capitulos->primer_capitulo($id_produccion);
                $fecha_aire = $capitulo[0]['fecha_aire'];
                $idcapitulo = $capitulo[0]['id'];
            }
            $cadena .= " LIBRETO " . $numero . " CREADO." ."\n";
            $this->user_log($id_produccion,$cadena);
            $cantidad = $this->actulizar_cantidad_capitulos($id_produccion);
            $this->actualizar_fechas_capitulos($id_produccion, $idcapitulo, $fecha_aire);      
            redirect ($this->lang->lang().'/libretos/index/'.$id_produccion);
        }
    }

    public function email($correo='alexander.ospina@cincoveinticinco.com',$cuerpo='hola'){
        $asunto='asignacion capitulo';
        $remitente='info@rtitv.com';
        $sheader="To: Productionsuite \n";
        $sheader.="From: Productionsuite ".$remitente."\n";
        $sheader=$sheader."Mime-Version: 1.0\n";
        $sheader=$sheader."Content-Type: text/html";          
        mail($correo,$asunto,$cuerpo,$sheader);
    }

    public function actulizar_cantidad_capitulos($id_produccion){
        $numero_capitulos = $this->model_capitulos->cantidad_capitulos($id_produccion);
         return $this->model_capitulos->actualizar_cantidad_capitulos($id_produccion, $numero_capitulos[0]->cantidad);
         //return $numero_capitulos[0]->cantidad;
    }

    public function editar_capitulo(){
        $idproduccion = $_POST['idproduccion'];
        $idcapitulo = $_POST['idcapitulo'];
        $opcion = $_POST['opcion'];
        $valor = $_POST['valor'];
        $resultado = "";
        $validacion = true;
        $val = true;
        $cadena = "";

        if($opcion == 'numero_escenas'){
            $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo); 
            $catidad_esc = $this->model_escenas->contar_escenas($idcapitulo);
            /*CAMBIA ESTADO A DESGLOSADO*/

            if(!$valor){
                $data=array(
                    'estado'=>1,
                    'fecha_desglosado' => null
                );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " No iniciado."; 
                $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo); 
                $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,null);
                $val =false;
            }if($capitulo[0]['numero_escenas'] == $valor OR $catidad_esc[0]->cantidad == $valor){
                $data=array(
                    'estado'=>4,
                    'fecha_desglosado' => date("Y-m-d")
                );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " DESGLOSADO."; 
                $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo); 
                $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
                $val =false;
            } else
              /*FIN CAMBIA ESTADO A DESGLOSADO*/

            /*CAMBIA ESTADO A EN ENTREGADO O EN PROGRESO*/
            if($capitulo[0]['numero_escenas'] < $valor AND $val){
                if($capitulo[0]['numero_escenas']=="" OR $capitulo[0]['numero_escenas'] == 0 OR $catidad_esc[0]->cantidad<$valor){
                    $data=array(
                        'estado'=>2,
                        'fecha_entregado' => date("Y-m-d"),
                        'fecha_desglosado' => '0000-00-00'
                    );
                    $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " ENTREGADO."; 
                    $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
                    $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                    $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
                }

                if($this->model_escenas_2->buscar_escenas_id_capitulo($idcapitulo)!=false){
                    $data=array(
                        'estado'=>3,
                        'fecha_entregado' => date("Y-m-d"),
                        'fecha_desglosado' => '0000-00-00'
                    );
                    $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                    $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " EN PROGRESO."; 
                    $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
                    $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
                }
            }else
            /*FIN CAMBIA ESTADO A EN ENTREGADO O EN PROGRESO*/

            /*MODIFICA LA CANTIDAD DE ESCENAS SI NO HAY ESCERNAS ASIGNADAS*/
            if($capitulo[0]['numero_escenas'] > $valor AND $val){
                if($catidad_esc[0]->cantidad < $valor AND $catidad_esc[0]->cantidad > 0){
                    $data=array(
                        'estado'=>3,
                        'fecha_entregado' => date("Y-m-d"),
                        'fecha_desglosado' => '0000-00-00'
                    );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " EN PROGRESO."; 
                $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
                $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
                }
               // if($valor > $catidad_esc[0]->cantidad){
                    $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
               // }else{
                    //$validacion=false;
                //}
            }

            if($catidad_esc[0]->cantidad < $valor AND $catidad_esc[0]->cantidad > 0 ){
                $data=array(
                        'estado'=>3,
                        'fecha_entregado' => date("Y-m-d"),
                        'fecha_desglosado' => '0000-00-00'
                );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " EN PROGRESO."; 
                $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
                $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
            }

            $producidas = $this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo);
            if($capitulo[0]['escenas_escritas']<=sizeof($producidas) AND $producidas>0 AND $capitulo[0]['escenas_escritas']!=0){
                $data=array(
                    'estado'=>5
                );
                $this->model_capitulos->actualizar_capitulo_estado($idcapitulo,$data);
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " PRODUCIDO."; 
            }

            if($validacion){
                $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA NÚMERO DE ESCENAS A ".$valor."."; 
            }
            /*FIN MODIFICA LA CANTIDAD DE ESCENAS SI NO HAY ESCERNAS ASIGNADAS*/
        }
        
        if ($opcion == 'paginasPorLibretos') {
            $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
        }

        if($opcion == 'fecha_aire' AND $valor!="-"){
            $valor = str_replace("/", "-", $valor);
            $valor = date("Y-m-d", strtotime($valor));
            $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
            $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA FECHA AL AIRE A ".$valor.".";
            $this->actualizar_fechas_capitulos($idproduccion, $idcapitulo, $valor);
        }else if($opcion != 'numero_escenas' AND $opcion != 'fecha_aire') {
            $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CAMBIA ".strtoupper($opcion)." A ".$valor.".";
            $resultado = $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor);
        }
        $this->user_log($idproduccion,$cadena);
        $data=$resultado;
        $data['resultado']=$resultado;
        $data['validacion']=$validacion;
        echo json_encode($data);
    }

    public function actualizar_fechas_capitulos($id_produccion, $idcapitulo, $valor){
        $capitulo_select = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        $numero_capitulos = $this->model_capitulos->contar_capitulos($capitulo_select[0]['numero'], $id_produccion);
        $numero_capitulos = $numero_capitulos[0]['cantidad'];
        $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
        $id_dias_grabacion = $produccion[0]->id_dias_grabacion;
        $dias = $this->model_capitulos->contar_dias_aire($id_dias_grabacion);
        $id_capitulo = $idcapitulo;
        $fecha="";
        $nueva_fecha="";
        $validator= true;
        $j=86400;
        $ultima_fecha = $valor;
        $this->model_plan_produccion->actualizar_fecha_aire($id_produccion);
        if($capitulo_select[0]['numero']==1){
            if(strtotime($produccion[0]->inicio_grabacion)<strtotime($valor)){
                $validator=false;
                $data=array(
                    'fecha_aire'=>date("Y-m-d",strtotime($valor))
                );
                $this->db->where('id',$id_produccion);
                $this->db->update('produccion',$data);
            }else{
                $validator= true;
            }
        }

        if($validator){
            $this->model_capitulos->actualizar_capitulo($id_produccion,$capitulo_select[0]['numero'],"fecha_aire",$fecha);
            $capitulo_select = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
            $ultima_fecha = $capitulo_select[0]['fecha_aire'];
            for($i=$capitulo_select[0]['numero']+1; $i<=$numero_capitulos+1; ++$i) {
                $active = 0;
                $capitulo = $this->model_capitulos->capitulo_id($i, $id_produccion);
            if($dias[0]['lunes']==0 AND $dias[0]['martes']==0 AND $dias[0]['miercoles']==0 AND $dias[0]['jueves']==0 AND $dias[0]['viernes']==0 AND $dias[0]['sabado']==0 AND $dias[0]['domingo']==0){
                    $active=1;
                    $fecha="0000-00-00";
                }
                while($active==0){
                $nueva_fecha = strtotime($ultima_fecha) + ($j);
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
                $j=86400;
                if($capitulo[0]['estado']=='6'){
                    $fecha = "0000-00-00";
                }
                $this->model_capitulos->actualizar_capitulo_2($id_produccion,$i,"fecha_aire",$fecha);
                $ultima_fecha = $fecha;
            }
        }   
    }

    public function cancelar_capitulo(){
        $idproduccion = $_POST['idproduccion'];
        $idcapitulo = $_POST['idcapitulo'];
        $capitulo = $this->model_capitulos->buscar_capitulo_id($idcapitulo);
        $resultado = $this->model_capitulos->cancelar_capitulo($idproduccion,$idcapitulo);
        $this->model_escenas_2->eliminar_escenas_no_producidas($idcapitulo);
        $data=$resultado;
        $catidad_esc = $this->model_escenas->contar_escenas($idcapitulo);   
        $this->model_capitulos->actualizar_capitulo($idproduccion,$idcapitulo,'numero_escenas',$catidad_esc[0]->cantidad);
        $cadena = "\n" . " LIBRETO " . $capitulo[0]['numero'] . " CANCELADO.";
        $this->user_log($idproduccion,$cadena);
        echo json_encode($data);
    }

    public function eliminar_capitulo(){
        $idproduccion = $_POST['idproduccion'];
        $idcapitulo = $_POST['idcapitulo'];
        $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        if($this->model_escenas_2->buscar_escenas_producidas_id_capitulo($capitulo[0]['id'])==false){
            $resultado = $this->model_capitulos->eliminar_capitulo($idproduccion,$idcapitulo);
            $this->actulizar_cantidad_capitulos($idproduccion);
            $cadena = "\n" . " LIBRETO " . $capitulo[0]['numero'] . " ELIMINADO.";
            $this->user_log($idproduccion,$cadena);
        }else{
            $resultado = "no puede eliminarce el capitulo ya que ya contiene escenas";
        }
        $data=$resultado;
        echo json_encode($data);
    }

    public function filtro_libretos($idproduccion="",$from="", $to=""){
        if($from=="" AND $to==""){
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $idproduccion = $this->input->post('idproduccion');
        }
        $sql="";

        if($this->input->post('producidos')!='on'){
            $sql=" AND produccion_has_capitulos.estado != 5";
        }

        //$capitulos = $this->model_capitulos->filtro_capitulos($from, $to, $idproduccion,$sql);
        $capitulos='';
        $this->index($idproduccion,$capitulos, $from, $to);
    }

    public function ingresar_libreto(){
        $idcapitulo = $this->input->post('id_capitulo');
        $id_produccion = $this->input->post('id_produccion');
        $produccion = $this->model_plan_produccion->produccion_id($id_produccion);
        $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);
        if($_FILES["libreto"]["tmp_name"]){
            $extension=explode('.',$_FILES["libreto"]["name"]);
            $rutaServidor="libretos";
            $rutaTemporal= $_FILES["libreto"]["tmp_name"];
            $nombreimage= "Produccion_".$produccion[0]->id_produccion."_Capitulo_".$capitulo[0]['numero'].".".$extension[1];
            $nombre=$nombreimage;
            $rutaDestino= $rutaServidor.'/'.$nombre;
            move_uploaded_file($rutaTemporal, $rutaDestino) ;
            $cadena = "\n" . " LIBRETO " . $capitulo[0]['numero'] . " SE AGREGA DOCUMENTO LIBRETO ".$nombre.".";
            $this->user_log($id_produccion,$cadena);
        }else{
            $rutaDestino=null;
        }
        $this->model_capitulos->actualizar_capitulo($id_produccion,$idcapitulo,"libreto",$rutaDestino);
        redirect ($this->lang->lang().'/libretos/index/'.$id_produccion);
    }

    public function load_capitules(){
        $idioma = $this->lang->lang().'/';
        $limite_inf = $_POST['limite_inf'];
       



        if($limite_inf==1){
            $limite_inf=0;
        }
        $limite_sup = $_POST['limite_sup'];

        


        $idproduccion = $_POST['idproduccion'];
        $estados = $_POST['estados'];
        $val = $_POST['val'];
       
       if($val==0){
           delete_cookie("limit");
       }else{
          ///////Crear cooki cargar mas libretos/
        $cookie = array(
            'name'   => 'limit',
            'value'  => $limite_sup,
            'expire' => '100',
        );
        $this->input->set_cookie($cookie);
        /////////////////////////////////
       }
        
        $sql="";
        $estado = "";
     
        if($estados!=""){
            $user=$this->model_capitulos->estados_libretos_user($this->session->userdata('id_pruduction_suite'));

            if($user){
                $this->model_capitulos->estados_libretos_update($user['0']->id,$estados);
            }else{
                $this->model_capitulos->estados_libretos_insert($this->session->userdata('id_pruduction_suite'),$estados); 
            }
            $estados = explode(',', $estados);
            for ($i=0; $i < count($estados); $i++) { 

                if($estados[$i]!=""){
                    $sql.= " AND estado != ".$estados[$i];
                }
            }
        }
        $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
        if($val==0){
            $limite_sup=30;
        }
        if($val==3){
             $sql.=" and produccion_has_capitulos.numero between ".$limite_inf." and ".$limite_sup." ";
        }
        $total=$this->model_capitulos->capitulos_produccion_total($idproduccion,$sql);
        $total=count($total);
        if($val==3){
            $capitulos = $this->model_capitulos->capitulos_produccion_lib($idproduccion,$sql);
         }else{
            $capitulos = $this->model_capitulos->capitulos_produccion_limit($idproduccion,$limite_inf,30,$sql);
        }
        $usuario_permisos = $this->permisos_usuarios($idproduccion);
        if($val==0 or $val==3){
            $cadena = '<thead>
          <tr>
            <td class="">'.lang('global.informacion_libreto').'</td>
            <td class="">'.lang('global.escenas').'</td>
            <td class="">'.lang('global.tiempo').'</td>
            <td class="">'.lang('global.detalle') .'</td>
          </tr>
        </thead>
        <tbody>
          <tr class="gray">
            <td>
              <table class="secondary">
                <tr>

                  <td width="10%"><span class="has-tip tip-centered-top" title="'.lang('global.numero').'">#</span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="'.lang('global.nombre').'">'.lang('global.nombre').'</span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="'.lang('global.sinopsis').'">'.lang('global.sinopsis').'</span></td>
                  <td width="30%"><span class="has-tip tip-centered-top" title="Sinopsis">'.lang('global.paginasLibretos').'</span></td>
                  </tr>

             </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="25%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_escenas').'">Nro Esc</span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="'.lang('global.escenas_desglosadas').'">'.lang('global.desglosadas').'</span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="'.lang('global.producidas').'">'.lang('global.producidas').'</span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="'.lang('global.post_producidas').'">'.lang('global.post_producidas').'</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <td width="33%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_estimado').'">est</span></td>
                  <td width="33%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_real').'">'.lang('global.real').'</span></td>
                  <td width="33%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_postproducidas').'">post</span></td>
                </tr>
              </table>
            </td>
            <td>
              <table class="secondary">
                <tr>
                  <!--<td width="15%"><span class="has-tip tip-centered-top" title="Fecha al aire">al aire</span></td>-->
                  <td width="15%"><span class="has-tip tip-centered-top" title="'.lang('global.estado').'">'.lang('global.estado').'</span></td>
                  <td width="15%"><span class="has-tip tip-centered-top" title="'.lang('global.libreto').'">'.lang('global.libreto').'</span></td>
                  <td width="31%"><span class="has-tip tip-centered-top" title="'.lang('global.alertas').'">'.lang('global.alertas').'</span></td>
                  <td width="25%"><span class="has-tip tip-centered-top" title="'.lang('global.acciones').'">'.lang('global.acciones').'</span></td>
                </tr>
              </table>
            </td>';  
        }else{
          $cadena='';  
        }
        

            for ($i=0; $i < count($capitulos); $i++) {

              if($capitulos[$i]['descripcion']!="Cancelado"){
                if($usuario_permisos=="write"){
                    $class_box="open_box";
                }else{
                    $class_box="";
                }
              }else{
                $class_box="null_box";
              }

              if($i%2==0){
                $class="white";
              }else{
                $class="gray_light";
              }

            $cadena .='<tr class="'.$class.'">';
            $cadena .='<td>';
            $cadena .='<table class="secondary">';
            $cadena .='<tr>';

            $cadena .='<td width="10%">';

            $cadena .='<a';
            if($capitulos[$i]['numero_escenas']!=""){
                $cadena .=' href="'.base_url().$this->lang->lang().'/escenas/buscar_escenas/'.$produccion[0]->id_produccion.'/'.$capitulos[$i]['id_capitulo'].'"';
            }
            if($capitulos[$i]['numero_escenas']==""){
                $cadena.= ' class="no_scene" data-numero="'.$capitulos[$i]['numero'].'" ';
            }

            $cadena.= ' >';

            $cadena .= $capitulos[$i]["numero"];
            if($capitulos[$i]['descripcion']=="Cancelado"){
                $cadena .='C';
            }
            $cadena .='</a></td>';
            $cadena .='<td width="30%">';
            $cadena .='<a href="#" class="'.$class_box.'" id="nombre_text'.$produccion[0]->id_produccion.'_'.$capitulos[$i]['id_capitulo'].'" >';

            if($capitulos[$i]['nombre'] == null OR $capitulos[$i]['nombre'] == ""){
              $cadena .="Nombre Libreto </a>";
            }else{
              $cadena .= $this->corta_palabra($capitulos[$i]["nombre"],15)."</a>";
            }

            
            $cadena .='<div class="hide_box capitulo_box">';
            $cadena .='<span class="close_box"></span>';
            $cadena .='<input type="text" placeholder="Nombre Libreto" id="nombre_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';
            $cadena .='<div class="align_left">';
            $cadena .='<a href="#" class="cancel_icon close_box">'.lang('global.cancelar').'</a>';
            $cadena .='<a href="#" class="save_icon" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'" opcion="nombre">'.lang('global.guardar').'</a>';
            $cadena .='</div>';
            $cadena .='</div>';
            $cadena .='</td>';
            $cadena .='<td width="30%">';
            $cadena .='<a href="#" class="'.$class_box.'" id="sinopsis_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';

            if(!isset($capitulos[$i]) OR $capitulos[$i]['sinopsis'] == ""){
              $cadena .="Sinopsis";
            }else{
              $cadena .=$this->corta_palabra($capitulos[$i]['sinopsis'],15);
            }

            $cadena .='</a>'; 
            $cadena .='<div class="hide_box capitulo_box">';
            $cadena .='<span class="close_box"></span>';
            $cadena .='<textarea cols="30" rows="10" placeholder="Sinopsis" id="sinopsis_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'" name="sinopsis_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">'.$capitulos[$i]['sinopsis'].'</textarea>';
            //$cadena .='<textarea><input type="textarea" placeholder="Sinopsis" id="sinopsis_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';
            $cadena .='<div class="align_left">';
            $cadena .='<a href="#" class="cancel_icon close_box">'.lang('global.cancelar').'</a>';
            $cadena .='<a href="#" class="save_icon" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'" opcion="sinopsis">'.lang('global.guardar').'</a>';
            $cadena .='</div>';
            $cadena .='</div>';
            $cadena .='</td>';
            $cadena .='<td width="30%">';
            $cadena .='<a href="#" class="'.$class_box.'" id="sinopsis_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';

            if(!isset($capitulos[$i]) OR $capitulos[$i]['paginasPorLibretos'] == ""){
              $cadena .="paginas";
            }else{
              $cadena .=$this->corta_palabra($capitulos[$i]['paginasPorLibretos'],15);
            }

            $cadena .='</a>'; 
            $cadena .='<div class="hide_box capitulo_box">';
            $cadena .='<span class="close_box"></span>';
            $cadena .='<input type="text" placeholder="paginasPorLibretos" id="paginasPorLibretos_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'" name="paginasPorLibretos_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'" value="'.$capitulos[$i]['paginasPorLibretos'].'" >';
            $cadena .='<div class="align_left">';
            $cadena .='<a href="#" class="cancel_icon close_box">'.lang('global.cancelar').'</a>';
            $cadena .='<a href="#" class="save_icon" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'" opcion="paginasPorLibretos">'.lang('global.guardar').'</a>';
            $cadena .='</div>';
            $cadena .='</div>';
            $cadena .='</td>';

                  
            $cadena .='</tr>';
            $cadena .='</table>';
            $cadena .='</td>';
            $cadena .='<td>';
            $cadena .='<table class="secondary">';
            $cadena .='<tr>';
            $cadena .='<td width="25%">';
            $cadena .='<a href="#" class="'.$class_box.'" id="numero_escenas_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';

            if(!isset($capitulos[$i]) OR $capitulos[$i]['numero_escenas'] == ""){
              $cadena .="Nro. escenas";
            }else{
              $cadena .=$capitulos[$i]['numero_escenas'];
            }

            $cadena .='</a>'; 
            $cadena .='<div class="hide_box capitulo_box">';
            $cadena .='<span class="close_box"></span>';
            $cadena .='<input onkeypress="return onlyNumbers(event)" type="text" placeholder="Nro. escenas" id="numero_escenas_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';
            $cadena .='<div class="align_left">';
             $cadena .='<a href="#" class="cancel_icon close_box">'.lang('global.cancelar').'</a>';
            $cadena .='<a href="#" class="save_icon" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'" opcion="numero_escenas">'.lang('global.guardar').'</a>';
            $cadena .='</div>';
            $cadena .='</div>';
            $cadena .='</td>';
            $cadena .='<td width="25%">';
            if($capitulos[$i]['escenas_escritas']==""){
              $cadena .= '0';
            }else{
              $cadena .= $capitulos[$i]['escenas_escritas'];
            }
            $cadena .='</td>';
            $cadena .='<td width="25%">';
            if($capitulos[$i]['escenas_producidas']==""){
              $cadena .= '0';
            }else{
              $cadena .= $capitulos[$i]['escenas_producidas'];
            }
            $cadena .='</td>';
            $cadena .='<td width="25%">';
            if($capitulos[$i]['post_produccidas']==""){
              $cadena .= '0';
            }else{
              $cadena .= $capitulos[$i]['post_produccidas'];
            }
            $cadena .='</td>';


            $cadena .='</tr>';
            $cadena .='</table>';
            $cadena .='</td>';
            $cadena .='<td>';
            $cadena .='<table class="secondary">';
            $cadena .='<tr>';

            $cadena .='<td width="33%">'.$capitulos[$i]['duracion_estimada'].'</td>';

            $cadena .='<td width="33%"><span class="red">';
            if($capitulos[$i]['duracion_real']!=""){
              $cadena .= $capitulos[$i]['duracion_real'];
            }else{
              $cadena .= "00:00";
            }
            $cadena.='<td width="33%">'.Libretos::calculo_tiempo_post($capitulos[$i]['tiempo_post_minutos'],$capitulos[$i]['tiempo_post_segundos'],$capitulos[$i]['tiempo_post_cuadros']).'</td>';
            $cadena .='</span> </td>';
            $cadena .='</tr>';
            $cadena .='</table>';
            $cadena .='</td>';
            $cadena .='<td>';
            $cadena .='<table class="secondary">';
            $cadena .='<tr>';
                  
            /*$cadena .='<td width="15%">';
            $cadena .='<a href="#" class="'.$class_box.'" id="fecha_aire_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">';
            if($capitulos[$i]['fecha_aire']!="0000-00-00"){
              $valor = date('d-M-Y',strtotime($capitulos[$i]['fecha_aire']));
              $cadena .= $valor;
            }else{
              $valor = "-";
              $cadena .= $valor;
            }

            $cadena .='</a>'; 
                    $cadena .='<div class="hide_box name_capitulo">';
                      $cadena .='<span class="close_box"></span>';
                      $cadena .='<form action="#" class="custom">';
                        $cadena .='<input type="text" placeholder="DD-MM-YYYY" value="'.$valor.'" id="fecha_aire_'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'" class="datepicker">';
                        $cadena .='<div class="align_left">';
                          $cadena .='<a href="#" class="cancel_icon close_box">Cancelar</a>';
                         $cadena .=' <a class="save_icon" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'" opcion="fecha_aire">Guardar</a>';
                        $cadena .='</div>';
                      $cadena .='</form>';
                    $cadena .='</div>';
                  $cadena .='</td>';*/

                  $descripcion = "";
                  $class_cap="";
                  if(isset($capitulos[$i])){
                    $descripcion = $capitulos[$i]['descripcion'];
                    switch($capitulos[$i]['descripcion']){
                      case 'En Progreso':
                        $class_cap="cap_progress";
                      break;
                      case 'Escrito':
                        $class_cap="cap_writed";
                      break;
                      case 'Producido':
                        $class_cap="cap_completed";
                      break;
                      case 'Entregado':
                        $class_cap="cap_deliver";
                      break;
                      case 'Desglosado':
                        $class_cap="cap_desglosed";
                      break;
                      case 'Cancelado':
                        $class_cap="cap_canceled";
                      break;
                      case 'No iniciado':
                        $class_cap="cap_progress_proyected";
                      break;
                    }
                  }else{
                      $descripcion = "En Progreso";
                      $class_cap="cap_progress";
                    }

                  $cadena .='<td width="15%" class="'.$class_cap.'">'.$descripcion.'</td>';

                  $cadena .='<td width="15%">';
                  if($capitulos[$i]['libreto']==""){
                    if($usuario_permisos=="write"){
                        $cadena .='<a href="#" class="'.$class_box.'" id="libreto_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">subir</a>';
                    }
                  } else{
                        $cadena .='<a href="'.base_url().$capitulos[$i]['libreto'].'">'.lang('global.ver').'</a> '; 
                        if($usuario_permisos=="write"){
                            $cadena .='/<a href="#" class="'.$class_box.'" id="libreto_text'.$produccion[0]->id_produccion.'_'.($capitulos[$i]['id_capitulo']).'">Cambiar</a>';
                        }
                  }

                  $cadena .='<div class="hide_box capitulo_box">';
                  $cadena .='<span class="close_box"></span>';
                  $cadena .=form_open_multipart( $idioma.'libretos/ingresar_libreto','class="custom", id="subirLibreto'.$capitulos[$i]['id_capitulo'].'"');
              
                  $cadena .='<div class="columns tow">';
                  $cadena .='<input type="hidden" name="id_produccion" value="'.$produccion[0]->id_produccion.'">';
                  $cadena .='<input type="hidden" name="id_capitulo" value="'.$capitulos[$i]['id_capitulo'].'">';
                  $cadena .='<input type="file" name="libreto" id="libreto'.$capitulos[$i]['id_capitulo'].'">';
                  $cadena .='</div>';
                  $cadena .='<div class="align_left">';
                  $cadena .='<a href="#" class="cancel_icon close_box">'.lang('global.cancelar').'</a>';
                  $cadena .='<a href="#" class="save_script save_icon" idcapitulo="'.$capitulos[$i]['id_capitulo'].'">'.lang('global.guardar').'</a>';
                  $cadena .='</div>';
                  $cadena .= form_close(); 
                  $cadena .='</div>';
                  $cadena .='</td>';

                  $cadena .='<td width="31%"><span class="red">';
                  $cadena_alertas = "";
                    $contador = 0;
                    $personajes = $this->model_capitulos->personajes_capitulos($capitulos[$i]['id_capitulo']);
                    $personajes = explode(',', $personajes[0]['cnt']);
                    if($produccion[0]->numero_locaciones < $capitulos[$i]['cantidad_locaciones'] AND $produccion[0]->numero_locaciones>0){
                      $cadena_alertas.= "MAX. LOCACIONES ".$produccion[0]->numero_locaciones."(".$capitulos[$i]['cantidad_locaciones'].")";
                      $contador++;
                    }
                    if($produccion[0]->numero_set<$capitulos[$i]['cantidad_sets'] AND $produccion[0]->numero_locaciones < $capitulos[$i]['cantidad_locaciones'] AND $produccion[0]->numero_set>0 AND $produccion[0]->numero_locaciones>0){
                      $cadena_alertas.= ' - ';
                    }
                    if($produccion[0]->numero_set<$capitulos[$i]['cantidad_sets'] AND $produccion[0]->numero_set>0){
                      $cadena_alertas.= "MAX. SETS ".$produccion[0]->numero_set."(".$capitulos[$i]['cantidad_sets'].") - ";
                    }
                    for ($xx=0; $xx < count($personajes); ++$xx) { 

                        $temp = explode('-', $personajes[$xx]);
                        //rint_r($temp);
                        if(isset($temp[1])){

                        if($temp[1]==1){
                          if($produccion[0]->numero_protagonistas < $temp[0] AND $produccion[0]->numero_protagonistas>0){
                            $cadena_alertas.= "MAX. PROTAGONISTA ".$produccion[0]->numero_protagonistas."(".$temp[0].") - ";
                          }
                        }

                        if($temp[1]==2){
                          if($produccion[0]->numero_figurantes < $temp[0] AND $produccion[0]->numero_figurantes>0){
                            $cadena_alertas.= "MAX. FIGURANTE ".$produccion[0]->numero_figurantes."(".$temp[0].") - ";
                          }
                        }

                        if($temp[1]==3){
                          if($produccion[0]->numero_repartos < $temp[0] AND $produccion[0]->numero_repartos>0){
                            $cadena_alertas.= "MAX. REPARTO ".$produccion[0]->numero_repartos."(".$temp[0].") - ";
                          }
                        }
                        
                        if($temp[1]==4){
                          if($produccion[0]->numero_extras < $temp[0] AND $produccion[0]->numero_extras>0){
                            $cadena_alertas.= "MAX. EXTRA ".$produccion[0]->numero_extras."(".$temp[0].") - ";
                          }
                        }
                        }
                    }

                    if($capitulos[$i]['estado']!=1 AND $capitulos[$i]['libreto']==""){
                      $cadena_alertas.= "NO LIBRETO";
                    }

                    $vehiculos = $this->model_capitulos->vehiculos_capitulo($capitulos[$i]['id_capitulo']);
                    if($vehiculos){
                      if($produccion[0]->numero_vehiculos < ($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background']) AND $produccion[0]->numero_vehiculos>0){
                          $cadena_alertas.= "MAX. vehiculos ".$produccion[0]->numero_vehiculos."(".($vehiculos[0]['vehiculos_desglosados']+$vehiculos[0]['vehiculo_background']).") - ";
                      }
                    }

                  $cadena.='<span class="red has-tip tip-centered-top tooltip_info" style="font-weight: normal;" title="'.$cadena_alertas.'">';
                  $cadena.= $this->corta_palabra($cadena_alertas,30);
                  if(strlen($cadena_alertas)>=30){
                    $cadena .= '...';
                  }
                  $cadena .='</span>';
                  $cadena .='</td>';

                  $cadena .='<td width="25%">';
                   if($usuario_permisos=="write"){
                       if ($capitulos[$i]['descripcion']=="En Progreso" and $capitulos[$i]['descripcion']!="Cancelado"){
                               $cadena .='<a href="'.base_url().$this->lang->lang().'/libretos/desglosar_libreto/'.$produccion[0]->id_produccion.'/'.$capitulos[$i]['id_capitulo'].'" class="desglosa_cap">Desglosar</a>/';
                       }
                   }     
                   $data['planes_producidos'] = $capitulos[$i]['planes_producidos'];
                  if($capitulos[$i]['descripcion']!="Cancelado" AND $capitulos[$i]['planes_producidos']>0){ 
                    if($usuario_permisos=="write"){
                        $cadena .='<a href="#" class="cancel_cap" planes-producidos="'.$capitulos[$i]['planes_producidos'].'" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'">'.lang('global.cancelar').'</a> '; 
                         
                    }    

                  } else{
                    if($usuario_permisos=="write"){
                        $cadena .='<a href="#" class="delete_cap" planes-asignados="'.$capitulos[$i]['planes_asignados'].'" idproduccion="'.$produccion[0]->id_produccion.'" idcapitulo="'.$capitulos[$i]['id_capitulo'].'">eliminar</a></td>';
                    }
                  }
                $cadena .='</tr>';
              $cadena .='</table>';
            $cadena .='</td>';
          $cadena .='</tr> ';
          }

        $data['cadena'] = $cadena;
        $data['total'] = $total;
        $data['limite_inf'] = $limite_inf;
        $data['limite_sup'] = $limite_sup;

        echo json_encode($data);
    }

    public function desglosar_libreto($idproduccion,$idlibreto){
        $capitulo = $this->model_capitulos->buscar_capitulo_id($idlibreto); 
        $escenas_canceladas=$this->model_escenas->escenas_canceladas($idlibreto);
        $t=$escenas_canceladas['0']->total;
        
        if($t>0){
          $total=$capitulo[0]['escenas_escritas']-$t;
        }else{
          $total=$capitulo[0]['escenas_escritas'];  
        }
    
       
        if($total<=$capitulo[0]['escenas_producidas']){
           $cadena="";
            $data=array(
                'estado'=>5,
                'fecha_desglosado' => date("Y-m-d")
            );
            $this->model_capitulos->actualizar_capitulo_estado($idlibreto,$data);
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " PRODUCIDO."; 
            $this->user_log($idproduccion,$cadena);
        }else{
            $cadena="";
            $data=array(
                'estado'=>4,
                'fecha_desglosado' => date("Y-m-d")
            );
            $this->model_capitulos->actualizar_capitulo_estado($idlibreto,$data);
            $cadena .= "\n" . " LIBRETO " . $capitulo[0]['numero'] . " DESGLOSADO."; 
            $this->user_log($idproduccion,$cadena);
        }
       
       redirect ($this->lang->lang().'/libretos/index/'.$idproduccion);
        echo json_encode(true);
    }

    public function scrtip($id_produccion){
        $libretos=$this->model_capitulos->libretos_produccion_a($id_produccion);
         
        foreach ($libretos as $l) {
        
         $capitulo = $this->model_capitulos->buscar_capitulo_id($l['id']); 
         $escenas_canceladas=$this->model_escenas->escenas_canceladas($l['id']);
            $t=$escenas_canceladas['0']->total;
            
            if($t>0){
              $total=$capitulo[0]['escenas_escritas']-$t;
            }else{
              $total=$capitulo[0]['escenas_escritas'];  
            }

            if($total<=$capitulo[0]['escenas_producidas'] and $total!=0 and $capitulo[0]['escenas_producidas']!=0){
           $cadena="";
            $data=array(
                'estado'=>5,
               
            );
            $this->model_capitulos->actualizar_capitulo_estado($l['id'],$data);
        }
        }
    }

    
    public static function corta_palabra($palabra,$num) {
        $largo=strlen($palabra);//indicarme el largo de una cadena
        $cadena=substr($palabra,0,$num);
        return $cadena;
    }  

    public function permisos_usuarios($id){
      $produccion = $this->model_plan_produccion->produccion_id($id);
      $iduser = $this->session->userdata('id_pruduction_suite');
      $permisos = "read";
      $usuario = $this->model_produccion->user_id($iduser);
      if($produccion[0]->id_productor == $iduser){
        $permisos = "write";
      }else{
        $usuario_roles = $this->model_produccion->roles_usuario_produccion($iduser,$produccion[0]->id_produccion);
        if($usuario_roles){
          foreach ($usuario_roles as $usuario_rol) {
            if($usuario_rol['id_rol']==1 or $usuario_rol['id_rol']==2 OR $usuario_rol['id_rol']==8){
              $permisos = "write";
              break;
            }else{
              $permisos = "read";
            }
          }
        }else{
          if($usuario[0]->id_tipoUsuario=='1'){
            $permisos = "write";
          }else{
            $permisos = "read";
          }
        }

      }

      return $permisos;
    }

    public function user_log($idproduccion,$cadena){
      $idusuario = $this->session->userdata('id_pruduction_suite');
      $user = $this->model_admin->user_id($idusuario);
      if($user){
        $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
        $cadena_insert = "______________________________________________________________________________________________________________"."\n";
        $cadena_insert .= " ".strtoupper(date("d-M-Y H:i:s")).'  '.$_SERVER['REMOTE_ADDR'].'  '.strtoupper($produccion[0]->nombre_produccion)."\n";
        $cadena_insert .= $cadena."\n";
        $cadena_insert .= "______________________________________________________________________________________________________________"."\n";
        $cadena = './logs/log_'.$user[0]->id.'_'.$user[0]->nombre.'_'.$user[0]->apellido.'_'.strtoupper(date("d_M_Y")).'.txt';
        $nuevoarchivo = fopen($cadena, "a+w+"); 
        fwrite($nuevoarchivo,$cadena_insert); 
        fclose($nuevoarchivo);
      }
    }



    public function prueba(){
        $rh = fopen('http://apps.rtitv.com/alex.txt', 'rb');
      $wh = fopen('http://localhost/production_suite/logs', 'a+w+');
      if (!$rh || !$wh) {
          return false;
      }

      while (!feof($rh)) {
          if (fwrite($wh, fread($rh, 4096)) === FALSE) {
              return false;
          }
          echo ' ';
          flush();
      }
      fclose($rh);
      fclose($wh);

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