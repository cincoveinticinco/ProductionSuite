<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_general extends CI_Controller {

public  $consulta;


  public function __construct (){       
      parent :: __construct (); 
      $this->load->model('model_plan_produccion');
      $this->load->model('model_plan_general');
      $this->load->model('model_plan_diario');
      $this->load->model('model_casting');
      $this->load->model('model_escenas');
      $this->load->model('model_escenas_2');
      $this->load->model('model_elementos');
      $this->load->model('model_capitulos');
      $this->load->model('model_admin');
      $this->_logeo_in();
      $this->load->helper('cookie');
      $this->input->cookie();

      setlocale(LC_TIME, 'es_ES.UTF-8');

      
  }

  function _logeo_in(){
    $login_in = $this->session->userdata('login_pruduction_suite');
    if ($login_in !=true){
      redirect ($this->lang->lang().'/login/disconnecUser');
    }
  }

  // ARRAY DE CAMPOS CUANDO SE HA SELECCIONADO DESCRIPCION
  public function campos(){
      $campos[]="libreto";
      $campos[]="escena";
      $campos[]="página";
      $campos[]="día";
      $campos[]="locación";
      $campos[]="set";
      $campos[]="int/ext";
      $campos[]="d/n";
      $campos[]="loc/ext";
      $campos[]="tiempo estimado";
      $campos[]="tiempo real";
      $campos[]="tiempo post";
      $campos[]="personajes principales";
      $campos[]="personajes secundarios";
      $campos[]="descripción";
      $campos[]="elementos";
      $campos[]="magnitud";
      $campos[]="vehículo background";
      return $campos;
  }


  // ARRAY DE CAMPOS CUANDO NO SE HA SELECCIONADO DESCRIPCION
  public function campos_nodescription(){
      $campos[]="libreto";
      $campos[]="escena";
      $campos[]="página";
      $campos[]="día";
      $campos[]="locación";
      $campos[]="set";
      $campos[]="int/ext";
      $campos[]="d/n";
      $campos[]="loc/ext";
      $campos[]="tiempo estimado";
      $campos[]="tiempo real";
      $campos[]="tiempo post";
      $campos[]="personajes principales";
      $campos[]="personajes secundarios";
      $campos[]="elementos";
      $campos[]="magnitud";
      $campos[]="vehículo background";
      return $campos;
  }

  // FUNCION PAGINA INICIAL PLAN GENERAL
  public function index($id, $escenas="", $msg="" ){
    
    $tipo_usuario = $this->session->userdata('tipo_pruduction_suite');
    if($tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='5'){
      $id_user=$this->session->userdata('id_pruduction_suite');
      $user=$this->model_admin->rolUserId($id_user);
      $tipo_rol=$user['0']['id_rol_otros'];
      $continuar=0;
          if($user){
              foreach ($user as $u) {
                if($u['id_rol_otros']== 2 or $u['id_rol_otros']== 1 or $u['id_rol_otros']== 15 or $u['id_rol_otros']== 6
                    or $u['id_rol_otros']== 7){
                  $continuar=1;
                  break;
                }
              }
         }
      if($continuar==1 OR $tipo_usuario=='1' OR $tipo_usuario=='3' OR $tipo_usuario=='4' OR $tipo_usuario=='9' OR $tipo_usuario=='10'){
        $produccion = $this->model_plan_produccion->produccion_id($id);
        $fechas_reporte_semanal = $this->model_plan_general->semanas_reporte_semanal($produccion [0]->inicio_grabacion);
        $personajes_produccion = $this->model_plan_general->categoria_produccion($id, 'Personaje');
        $locaciones = $this->model_escenas->locacion($id);
        $elementos = $this->model_plan_general->elemento_id_produccion($id);
        $iduser = $this->session->userdata('id_pruduction_suite');
        $resumen = "";
        if($msg!=1 AND $msg!=""){
          $resumen = $msg;
        }
        $consultas = $this->model_plan_general->consultas_user($iduser, $id);
        $categoria_elemento=$this->model_escenas->categoria_elemento($id);
        $unidades = $this->model_plan_produccion->unidades_id_produccion_3($id);
        $escenas_noproducidas = $this->model_escenas_2->contar_escenas_noproducidas($id);
        $sql2="";
        $sql= "INNER JOIN produccion_has_capitulos ON escena.id_capitulo = produccion_has_capitulos.id 
              WHERE escena.estado !=1  AND escena.estado !=3 AND escena.estado !=2 AND escena.estado !=12 AND escena.estado !=14
              AND produccion_has_capitulos.id_produccion = ".$id.";";
        $temporal = $this->model_escenas_2->suma_tiempos_ajax($sql);
        $tiempo_diponible = $this->calculo_tiempo($temporal);
        $tipo_locacion=$this->model_escenas->tipo_locacion();
        $dia_noche=$this->model_escenas->dia_noche();
        $interior_exterior=$this->model_escenas->escena_interior_esterior();
        $fechas_bloqueadas = $this->fechas_bloqueadas($id);
        $usuario_permisos = $this->permisos_usuarios($id);
        $sets = $this->model_plan_general->sets_produccion($id);
        $magnitudes = $this->model_escenas->escena_magnitud();

        $data['usuario_permisos'] = $usuario_permisos;
        $data['fechas_reporte_semanal'] = $fechas_reporte_semanal;
        $data['produccion'] = $produccion;
        $data['fechas_bloqueadas'] = $fechas_bloqueadas;
        $data['unidades'] = $unidades;
        $data['locaciones'] = $locaciones;
        $data['sets'] = $sets;
        $data['elementos'] = $elementos;
        $data['consultas'] = $consultas;
        $data['categoria_elemento'] = $categoria_elemento;
        $data['tipo_locacion'] = $tipo_locacion;
        $data['dia_noche'] = $dia_noche;
        $data['interior_exterior'] = $interior_exterior;
        $data['escenas_noproducidas']= $escenas_noproducidas[0]->cantidad;
        $data['dias_trabajo'] = $this->model_plan_general->dias_trabajo_produccion($id);
        $data['msg'] = $msg;
        $data['resumen'] = $resumen;
        $data['magnitudes'] = $magnitudes;

        $data_campos=array(
          'id_usuario'=>$iduser,
          'tipo'=>2
        );
        $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
        if($campos_usuario==false){
          $campos_usuario = $this->campos_nodescription();
          $campos_usuario[count($campos_usuario)]="";
        }else{
          $campos_usuario = explode(',', $campos_usuario[0]->campos);
        }
        $campos = $this->campos();
          $limit_inf=0;
          $limit_sup=50;
        if($escenas==""){
         
          $escenas = $this->model_plan_general->listar_escenas($id,$personajes_produccion[0]->id,$limit_inf,$limit_sup);
        }else{
          foreach ($escenas as $escena) {
            if($sql2==""){
              $sql2.= " WHERE escena.id = ".$escena->id;
            }else{
              $sql2 .= " OR escena.id = ".$escena->id;
            }
          }
          $temporal = $this->model_escenas_2->suma_tiempos_ajax($sql2);
          $tiempo_diponible = $this->calculo_tiempo($temporal);
        }
        $total_escenas=$this->model_plan_general->total_escena($id);
        $data['campos'] = $campos;
        $data['limit_inf']=$limit_inf;
        $data['limit_sup']=$limit_sup;
        $data['campos_usuario'] = $campos_usuario;
        $data['tiempo_diponible'] =  $tiempo_diponible;
        $data['escenas'] = $escenas;
        $data['total_escenas'] =$total_escenas;
        $data['view']='plan_general/index';
       $this->load->view('includes/template',$data);

       
      }else{
        redirect ($this->lang->lang().'/produccion/producciones');
      }   
    }else{
      redirect ($this->lang->lang().'/produccion/producciones');
    }     
  }

  // FUNCION VERIFICACION DE PERMISOS USUARIOS
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
          if($usuario_rol['id_rol']==2 OR $usuario_rol['id_rol']==8){
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


    public function filtro(){
      /*INICIALIZACION DE CADENAS*/
      $cadena = array();
      $cadena2 = array();
      $cadena3 = array();
      $cadena_final = "";
      $cadena_final2 = "";
      $cadena_fechas="";
      $cadena_unidad="";
      $cadena_estado="";
      $cadena_resumen ="";

      /*VARIABLES CAMPOS FORUMLARIO*/
      $limite1_esc = $_POST['limite1_esc'];
      $limite2_esc = $_POST['limite2_esc'];
      $limite1_cap =$_POST['limite1_cap'];
      $limite2_cap =$_POST['limite2_cap'];

      $locacion =$_POST['locacion'];

      $limite1_fec = $_POST['limite1_fec'];
      $limite2_fec = $_POST['limite2_fec'];
      $set = $_POST['set'];
      $con = $_POST['con'];
      $sin = $_POST['sin'];
      $idescenas = $_POST['idescenas'];
      $tiempo_diponible ="00:00";
      $idproduccion = $_POST['idproduccion'];
      $unidad = $_POST['unidad'];
      $escena_estado = $_POST['esc_estado'];
      $toma_ubicacion = $_POST['toma_ubicacion'];
      $flashback = $_POST['flashback'];
      $foto_realizacion = $_POST['foto_realizacion'];
      $imagenes_archivo = $_POST['imagenes_archivo'];
      $tipo_locacion1 = $_POST['tipo_locacion1'];
      $tipo_locacion2 = $_POST['tipo_locacion2'];
      $dia_noche = $_POST['dia_noche'];
      $int_ext = $_POST['int_ext'];
      $limit=$_POST['limit'];
      $limit_plan_general_consulta=$_POST['limit_plan_general_consulta'];
      
      $magnitud = $_POST['magnitud'];
      $vehiculo=$_POST['vehiculo_background'];
      $order_by = $_POST['order_by'];
      if(!$order_by){
        $order_by="capitulo ASC, CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
      (CASE INSTR(numero_escena, '.') 
      WHEN 0 THEN 0
      ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC";
      }elseif($order_by=='dias_continuidad'){
        $order_by="CAST(SUBSTRING_INDEX(dias_continuidad,'.', 1 ) AS UNSIGNED) ASC,
      (CASE INSTR(dias_continuidad, '.') 
      WHEN 0 THEN 0
      ELSE CAST(SUBSTRING_INDEX(dias_continuidad,'.', -1 ) AS UNSIGNED) END) ASC";
      }
      $dia_cont = $_POST['dia_cont'];
      $validate_click = $_POST['validate_click'];


      $produccion = $this->model_plan_produccion->produccion_id($idproduccion);

      /*ID DE CATEGORIA PERSONAJES*/
      $personajes_produccion = $this->model_plan_general->categoria_produccion($idproduccion, 'Personaje');

      /*VALIDACIONES DE DE FECHAS*/
      if($limite1_fec!="" AND $limite2_fec!=""){
        $cadena_fechas = " AND 
        (
        plan_dia.fecha_inicio BETWEEN '".$this->fechaFormat($_POST['limite1_fec'])."' 
        AND '".$this->fechaFormat($_POST['limite2_fec'])."' 
        OR esc.fecha_produccion BETWEEN '".$this->fechaFormat($_POST['limite1_fec'])."' 
        AND '".$this->fechaFormat($_POST['limite2_fec'])."' ) ";
        $cadena_resumen.=" Fecha, ";
      }

      if($limite1_fec!="" AND $limite2_fec==""){
        $cadena_fechas = " 
        AND (plan_dia.fecha_inicio 
        BETWEEN '".$this->fechaFormat($_POST['limite1_fec'])."' AND '".$this->fechaFormat($_POST['limite1_fec'])."'
        OR esc.fecha_produccion 
        BETWEEN '".$this->fechaFormat($_POST['limite1_fec'])."' AND '".$this->fechaFormat($_POST['limite1_fec'])."')";
        $cadena_resumen.=" Fecha, ";
      }

      if($limite2_fec!="" AND $limite1_fec==""){
        $cadena_fechas = " 
        AND 
        (plan_dia.fecha_inicio <= '".$this->fechaFormat($_POST['limite2_fec'])."'
        OR esc.fecha_produccion <= '".$this->fechaFormat($_POST['limite2_fec'])."')";
        $cadena_resumen.=" Fecha, ";
      }



      /*VALIDACIONES DE RANGO DE ESCENAS*/
      if($limite1_esc!='' AND $limite2_esc!=''){
        $cadena [] = " AND CAST(esc.numero_escena AS decimal(10,5)) BETWEEN '".$limite1_esc."' AND '".$limite2_esc."'";
        $cadena_resumen.=" No. Escena ( ".$limite1_esc." - ".$limite2_esc." ), ";
      }
      if($limite1_cap!='' AND $limite2_cap!=''){
        $cadena []= " AND cap.numero BETWEEN ".$limite1_cap." AND ".$limite2_cap;
        $cadena_resumen.=" No. Libreto( ".$limite1_cap." - ".$limite2_cap." ), ";
      }

      if($limite1_cap!='' AND $limite2_cap==''){
        $cadena []= " AND cap.numero >= ".$limite1_cap." ";
        $cadena_resumen.=" No. Libreto Desde( ".$limite1_cap." ), ";
      }
      /*VALIDACION DE LOCACION*/
      if($locacion!=''){
        $locaciones=$_POST['locacion'];
        $locaciones=explode(',', $locaciones);
        $sql=' AND ( ';
        $sql2='';
        $cont=0;
        foreach ($locaciones as $l) {
            if($l){
              if($cont==0){
                 $sql.=' loc.id='.$l;   
                 $sql2.=' id='.$l;   
              }else{
                $sql.=' or loc.id='.$l;   
                $sql2.=' or id='.$l;
              }
              $cont++;
            }  
          
        }
        $sql.=' ) ';
        $cadena[]=$sql;
        

        //$cadena[] = " AND loc.id = ".$locacion;
        $locacion_actual = $this->model_escenas->id_locaciones($sql2);
        $cadena_resumen.=" Locaciónes (";
        foreach ($locacion_actual as $l) {
          $cadena_resumen.=" ".$l['nombre'].", ";
        }
        $cadena_resumen.="), ";
        //$cadena_resumen.=" Locación (".$locacion_actual[0]['nombre']."), ";
      }
      /*VALIDACION DE SET*/
      if($set!='' AND $set!=' '){
        $sets=$_POST['set'];
        $sets=explode(',', $sets);
        $sql=' AND ';
        $sql2='';
        $cont=0;
        foreach ($sets as $s) {
            if($s){
              if($cont==0){
                 $sql.=' se.id='.$s;
                 $sql2.=' id='.$s;   
              }else{
                $sql.=' or se.id='.$s;   
                $sql2.=' or id='.$s;
              }
              $cont++;
            }  
        }
        
        $cadena[]=$sql;

        $set_actual = $this->model_escenas->set_id_locaciones($sql2);
        $cadena_resumen.=" Set (";
        foreach ($set_actual as $s) {
          $cadena_resumen.=" ".$s['nombre'].", ";
        }
        $cadena_resumen.="), ";

        /*$cadena [] = " AND se.id = ".$set;
        $set_actual = $this->model_escenas->set_id_locaciones($set);
        $cadena_resumen.=" Set (". $set_actual[0]['nombre']."), ";*/
      }
     /*VALIDACION DE UNIDAD*/
      if($unidad!=''){
        $cadena_unidad = " AND  (plan_dia.id_unidad = ".$unidad." OR esc.unidad_produccion=".$unidad.")";
        $cadena_resumen.=" Unidad, ";
      }

      $cadena_estado="";
      /*VALIDACION DE ESCENAS PRODUCIDAS*/
      if($escena_estado=='1'){
        $cadena_estado = " AND  (esc.estado = ".$escena_estado." OR esc.estado = 12 OR esc.estado = 2)";
        $cadena_resumen.=" Estado(producidas), ";
      }
      /*VALIDACION DE ESCENAS CANCELADAS*/
      if($escena_estado=='3'){
        $cadena_estado = " AND  esc.estado = ".$escena_estado;
        $cadena_resumen.=" Estado(canceladas), ";
      }
      /*VALIDACION DE RETOMAS*/
      if($escena_estado=='2'){
        $cadena_estado = " AND  (esc.estado = ".$escena_estado." OR esc.estado = 14)";
        $cadena_resumen.=" Estado(retoma), ";
      }
      /*VALIDACION DE TODOS LOS ESTADOS*/
      if($escena_estado=='4'){
        $cadena_resumen.=" Estado(todos), ";
      }

      if($escena_estado=='' OR $escena_estado=='0'){
       $cadena_estado = " AND  esc.estado != 1 AND  esc.estado != 3 AND esc.estado!=2 AND esc.estado != 12 AND esc.estado != 14 ";
      }
      if($escena_estado=='5'){
       $cadena_estado = " AND ((capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0) ";
      }
      if($escena_estado=='6'){
        $cadena_resumen.=" Estado(Asignadas), ";
       $cadena_estado = " AND plan_dia.id_unidad is not null AND (esc.estado = 4 OR  esc.estado = 6 OR esc.estado=8 OR esc.estado = 10 OR esc.estado = 12) ";
      }
      if($escena_estado=='7'){
          $cadena_resumen.=" Estado(No Asignadas), ";
          $cadena_estado = " AND (esc.estado = 5 OR  esc.estado = 7 OR esc.estado=9 OR esc.estado = 11) ";
        }
      /*VALIDACION DE MAGNITUD*/
      if($magnitud!=""){
       $cadena_estado = " AND  esc.id_magnitud = ".$magnitud." ";
      }
      if($vehiculo!=""){
        $cadena_estado .= " AND  esc.vehiculo_background = ".$vehiculo." ";
      }

      $tiempo_producido =0;


      $cadena_final.= "SELECT esc.id,esc.numero_escena,esc.id_capitulo,esc.dias_continuidad,esc.duracion_estimada_minutos,esc.duracion_estimada_segundos, esc.duracion_real_minutos, esc.duracion_real_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_minutos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_cuadros,
      esc.descripcion,esc.guion, esc.fecha_produccion, esc.unidad_produccion, esc.vehiculo_background, esc.estado, esc.libreto, magnitud_escena.descripcion AS magnitud_nombre, esc.fecha_produccion AS fecha_produccion_2, cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion  AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, plan_dia.fecha_inicio AS fecha_inicio, 
      plan_dia.id_unidad AS idunidad, (SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, (SELECT unidad.numero FROM unidad where unidad.id = esc.unidad_produccion) AS unidad_produccion_numero,
      (SELECT group_concat(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'), '_', unidad.numero , '_',
      (SELECT id FROM retomas_escena where fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = plan_diario.id_unidad AND retomas_escena.id_escena =  esc.id)) FROM plan_diario_has_escenas_has_unidades
        INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
        INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
        WHERE (plan_diario.estado != 1 AND plan_diario.estado != 5) AND id_escena = esc.id) as planes_abiertos,
      (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$personajes_produccion[0]->id;

      $cadena_final2.= "SELECT esc.id,esc.numero_escena,esc.id_capitulo,esc.dias_continuidad,esc.duracion_estimada_minutos,esc.duracion_estimada_segundos, esc.duracion_real_minutos, esc.duracion_real_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_minutos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_cuadros,
      esc.descripcion,esc.guion, esc.fecha_produccion, esc.unidad_produccion, esc.vehiculo_background, esc.estado, esc.libreto, magnitud_escena.descripcion AS magnitud_nombre, esc.fecha_produccion AS fecha_produccion_2,  cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion   AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion,  plan_dia.fecha_inicio AS fecha_inicio, 
      plan_dia.id_unidad AS idunidad, (SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, (SELECT unidad.numero FROM unidad where unidad.id = esc.unidad_produccion) AS unidad_produccion_numero,
      (SELECT group_concat(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'), '_', unidad.numero, '_',
      (SELECT id FROM retomas_escena where fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = plan_diario.id_unidad AND retomas_escena.id_escena =  esc.id)) FROM plan_diario_has_escenas_has_unidades
        INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
        INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
        WHERE (plan_diario.estado != 1 AND plan_diario.estado != 5) AND id_escena = esc.id) as planes_abiertos,
      (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$personajes_produccion[0]->id;

      $cadena_final.= " AND (rol = 1 OR rol = 3) ORDER BY ele.nombre) as personajes_principales,
      (SELECT group_concat(ele.nombre, COALESCE(CONCAT('(',extras_escena.cantidad,')'),'')   
      ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = escenas_has_elementos.id
      WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$personajes_produccion[0]->id;

      $cadena_final2.= " AND (rol = 1 OR rol = 3) ORDER BY ele.nombre) as personajes_principales,
      (SELECT group_concat(ele.nombre, COALESCE(CONCAT('(',extras_escena.cantidad,')'),'')   
      ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = escenas_has_elementos.id
      WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$personajes_produccion[0]->id;

      $cadena_final.="), (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$personajes_produccion[0]->id;

      $cadena_final.= " AND (rol = 4 OR rol = 2) ORDER BY ele.nombre) as personajes_secundarios,
      (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$personajes_produccion[0]->id;

      $cadena_final2.="), (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$personajes_produccion[0]->id;

      $cadena_final2.= " AND (rol = 4 OR rol = 2) ORDER BY ele.nombre) as personajes_secundarios,
      (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
      INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento 
      WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$personajes_produccion[0]->id;


      $cadena_final.= ") as elementos 
      FROM escena esc 
      INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo
      INNER JOIN locacion loc ON loc.id = esc.id_locacion
      INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior 
      INNER JOIN sets se ON se.id = esc.id_set
      INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche
      INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
      INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
      LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id
      AND plan_esc.id_plan_diario = 
      (SELECT MAX(plan_diario.id) 
      FROM plan_diario 
      INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
      WHERE pl.id_escena = esc.id AND plan_diario.estado !=5) 
      LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
      LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
      LEFT OUTER JOIN capitulos_has_escenas capitulos_has_escenas ON capitulos_has_escenas.id_escena=esc.id ";

      $cadena_final2.= ") as elementos 
      FROM escena esc 
      INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo
      INNER JOIN locacion loc ON loc.id = esc.id_locacion
      INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior 
      INNER JOIN sets se ON se.id = esc.id_set
      INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche
      INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
      INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
      LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id
      AND plan_esc.id_plan_diario = 
      (SELECT MAX(plan_diario.id) 
      FROM plan_diario 
      INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
      WHERE pl.id_escena = esc.id AND plan_diario.estado !=5) 
      LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
      LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
      LEFT OUTER JOIN capitulos_has_escenas capitulos_has_escenas ON capitulos_has_escenas.id_escena=esc.id ";

      $cadena_final.= "
      WHERE cap.id_produccion = ".$idproduccion."";

      $cadena_final2.= "
      WHERE cap.id_produccion = ".$idproduccion."";

      /*UNION DE CADENAS*/
      for ($i=0; $i<sizeof($cadena); ++$i) {
        $cadena_final.= $cadena[$i];
        $cadena_final2.=$cadena[$i];
      }

      $cadena_final.= $cadena_unidad;
      $cadena_final2.= $cadena_unidad;
      $cadena_final.= $cadena_fechas;
      $cadena_final2.= $cadena_fechas;
      $cadena_final.= $cadena_estado;
      $cadena_final2.= $cadena_estado;
      $cadena_final.= " GROUP BY esc.id";
      $cadena_final.= " ORDER BY ".$order_by;
      /*ORDER BY capitulo ASC, CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
      (CASE INSTR(numero_escena, '.') 
      WHEN 0 THEN 0
      ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC";*/

      $query=$this->db->query($cadena_final);
      if ($query->num_rows>0) {
        $escenas = $query->result();
      }else{
        $escenas = "";
      }

      $lista_id = "";
      $lista_id2 = "";
      $validacion =true;

      /*SELECCION DE ESCENAS SEGUN ELEMENTOS CON Y SIN*/
     /* if($escenas!="" AND $con !=''){
        $con1 = explode(",",$con);
        $cadena_resumen.=" Con(";
        $lista_id .= " AND esc.id IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos 
                      INNER JOIN  elemento ON escenas_has_elementos.id_elemento = elemento.id ";
        for($i=0;$i<sizeof($con1);++$i) {
          if($con1[$i]!="" and $con1[$i]!=" "){
            $cadena_resumen.=$con1[$i].',';                
            if($i==0){
              $lista_id .= " WHERE elemento.nombre = '".$con1[$i]."' ";
            }else{
              $lista_id .= " OR elemento.nombre = '".$con1[$i]."' ";
            }
          }
        } 
        $lista_id .= " )";
        $cadena_resumen.=" ), ";        
      }*/
      if($escenas!="" AND $con !=''){
        $con1 = explode(",",$con);
        $cadena_resumen.=" Con(";
        //$lista_id .= " AND esc.id IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos ";
        for($i=0;$i<sizeof($con1);++$i) {

          if($con1[$i]!="" and $con1[$i]!=" "){
            $elemento=$this->model_elementos->buscar_elemento_id($con1[$i]);
            //$cadena_resumen.=$con1[$i].',';                
            $cadena_resumen.=$elemento['0']->nombre.',';                
            //if($i==0){
              $lista_id .= " AND esc.id IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos WHERE escenas_has_elementos.id_escena=esc.id and 
                (escenas_has_elementos.id_elemento= ".$con1[$i]." ))";
            //}else{
             // $lista_id .= " OR escenas_has_elementos.id_elemento = '".$con1[$i]."' ";
            //}

          }
        } 
      //  $lista_id .= " ))";
        $cadena_resumen.=" ), ";        
      }

      /*if($escenas!="" AND $sin !=''){
          $sin1 = explode(",",$sin);
          $cadena_resumen.=" Sin(";
          $lista_id .= " AND esc.id NOT IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos 
                        INNER JOIN  elemento ON escenas_has_elementos.id_elemento = elemento.id ";
          for($i=0;$i<sizeof($sin1);++$i) {
            if($sin1[$i]!="" and $sin1[$i]!=" "){
              $cadena_resumen.=$sin1[$i].',';
              if($i==0){
                $lista_id .= " WHERE elemento.nombre = '".$sin1[$i]."' ";
              }else{
                $lista_id .= " OR elemento.nombre = '".$sin1[$i]."' ";
              }
            }
          }
          $lista_id .= " )";
          $cadena_resumen.=" ), ";
      }*/

      if($escenas!="" AND $sin !=''){
          $sin1 = explode(",",$sin);
          $cadena_resumen.=" Sin(";
          //$lista_id .= " AND esc.id NOT IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos 
             //           INNER JOIN  elemento ON escenas_has_elementos.id_elemento = elemento.id ";
          for($i=0;$i<sizeof($sin1);++$i) {
            if($sin1[$i]!="" and $sin1[$i]!=" "){
              $elemento=$this->model_elementos->buscar_elemento_id($sin1[$i]);
              $cadena_resumen.=$elemento['0']->nombre.','; 
              $lista_id .= " AND esc.id NOT IN ( SELECT escenas_has_elementos.id_escena FROM escenas_has_elementos WHERE escenas_has_elementos.id_escena=esc.id and 
                (escenas_has_elementos.id_elemento= ".$sin1[$i]." ))";
            }
          }
          ///$lista_id .= " )";
          $cadena_resumen.=" ), ";
      }

      /*ESCENAS NO SELECCIONABLES*/
      if($idescenas!=""){
        $idescenas = explode(',', $idescenas);
        for ($i=0; $i < count($idescenas); $i++) { 
          if($idescenas[$i]!=""){
            $lista_id.= " AND  esc.id != ".$idescenas[$i]." ";
          }
        }
      }

      $cadena_filtro = $cadena_final2.$lista_id2.$lista_id;

      /*VALIDACION TIPO LOCACION*/
      if($tipo_locacion1!="" AND $tipo_locacion2==""){
        $cadena_filtro .= " AND id_tipo_locacion = ".$tipo_locacion1;
        $cadena_resumen.=" Tipo(locación), ";
      }
      /*VALIDACION TIPO ESTUDIO*/
      if($tipo_locacion2!="" AND $tipo_locacion1==""){
        $cadena_filtro .= " AND id_tipo_locacion = ".$tipo_locacion2;
        $cadena_resumen.=" Tipo(estudio), ";
      }
      /*VALIDACION TIPO TOMA UBICACION*/
      if($toma_ubicacion!=""){
        $cadena_filtro .= " AND id_toma_ubicacion = ".$toma_ubicacion;
        $cadena_resumen.=" Toma ubicación, ";
      }
      /*VALIDACION TIPO TOMA FASHBACK*/
      if($flashback!=""){
        $cadena_filtro .= " AND esc.id_flasback = ".$flashback;
        $cadena_resumen.=" Flashback, ";
      }
      /*VALIDACION foto realizacion*/
      if($foto_realizacion!=""){
        $cadena_filtro .= " AND esc.id_foto_realizacion = ".$foto_realizacion;
        $cadena_resumen.=" Foto realizacion, ";
      }
      /*VALIDACION IMAGENES DE ARCHIVO*/
      if($imagenes_archivo!=""){
        $cadena_filtro .= " AND esc.id_imagenes_archivo = ".$imagenes_archivo;
        $cadena_resumen.=" Foto imagenes de archivo, ";
      }
      /*VALIDACION TIPO TOMA DIA-NOCHE*/
      if($dia_noche!=""){
      $cadena_filtro .= " AND id_dia_noche = ".$dia_noche;
        if($dia_noche==1){
          $cadena_resumen.=" Día, ";
        }
        if($dia_noche==2){
          $cadena_resumen.=" Noche, ";
        }
      }
      /*VALIDACION TIPO TOMA INTERIOR-EXTERIOR*/
      if($int_ext!=""){
      $cadena_filtro .= " AND id_interior_esterior = ".$int_ext;
        if($int_ext==1){
          $cadena_resumen.=" Interior, ";
        }
        if($int_ext==2){
          $cadena_resumen.=" Exterior, ";
        }
      }

      /*VALIDACION CONTINUIDAD*/
      if($dia_cont){
        $cadena_filtro .= " AND (";
        $cont_cont=0;
        $tam_cont=count($dia_cont);
        if($tam_cont>1){
          $or=" OR ";
        }
        
        foreach ($dia_cont as $d) {
          if(($cont_cont+1)==$tam_cont){
            $cadena_filtro .= " esc.dias_continuidad='".$d."') "; 
          }else{
           $cadena_filtro .= " esc.dias_continuidad='".$d."' ".$or." ";  
          }
            $cont_cont++;
        }
     
      }
    
      $cantidad=0;
      $cadena_filtro_total=$cadena_filtro." GROUP BY esc.id";
      $cadena_final.= " ORDER BY ".$order_by;

      $query = $this->db->query($cadena_filtro." GROUP BY esc.id ORDER BY ".$order_by);
        $cadena_final.= " ORDER BY ".$order_by;
   
     $consultaImpresion = $cadena_filtro." GROUP BY esc.id
      ORDER BY ".$order_by." LIMIT 300";
     $consultaImpresion_excel='';
      if($query->num_rows>0) {
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
        $temporal_prod = $this->model_escenas_2->suma_tiempos_prod($sql);
        $tiempo_producido = $this->calculo_tiempo($temporal_prod);
        if($validate_click==1){
       
          $query = $this->db->query($cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by." LIMIT ".($limit-50).', '.$limit_plan_general_consulta);
          if($limit_plan_general_consulta==3000){
               $consultaImpresion_excel = $cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by." LIMIT 0, 30000";
          }else{
             $consultaImpresion_excel = $cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by." LIMIT 0, ".$limit;
          }
           
        }else{

          $query = $this->db->query($cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by." LIMIT ".($limit-50).', '.$limit_plan_general_consulta);

          if($limit_plan_general_consulta==3000){
               $consultaImpresion_excel = $cadena_filtro." GROUP BY esc.id
           ORDER BY ".$order_by." LIMIT 0, 30000";
          }else{
            $consultaImpresion_excel = $cadena_filtro." GROUP BY esc.id
           ORDER BY ".$order_by." LIMIT 0, ".$limit;
          }
           
         /* $query = $this->db->query($cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by );

          $consultaImpresion_excel = $cadena_filtro." GROUP BY esc.id
          ORDER BY ".$order_by ;*/
        }
        $escenas = $query->result();

      }else{
        $escenas = "";
        $msg=1;
      }



    
      $total=$this->db->query($cadena_filtro_total);
      $total=count($total->result());
    
      $cadena_filtro .= " GROUP BY esc.id
      ORDER BY ".$order_by." LIMIT ".$limit;
/*$cadena_filtro .= " GROUP BY esc.id
      ORDER BY cap.numero ASC, CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
(CASE INSTR(numero_escena, '.') 
    WHEN 0 THEN 0
    ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC LIMIT ".$limit;*/
      $query=$this->db->query($cadena_filtro);

      $tiempo ="00:00";
      $this->consulta = $this->db->last_query();
      /*GUARDAR LA CONSULTA REALIZADA*/
      if($_POST['idconsulta']!=0){
        $this->model_plan_general->actualizar_consulta($_POST['idconsulta'],$cadena_filtro,$cadena_resumen);
      }

      /*VERIFICAR LOS PERMISOS Y CAMPOS DE USUARIO*/
      $iduser = $this->session->userdata('id_pruduction_suite');
      $usuario_permisos = $this->permisos_usuarios($produccion[0]->id_produccion);
      $data_campos=array(
        'id_usuario'=>$iduser,
        'tipo'=>2
      );
      $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
      if($campos_usuario==false){
         $campos_usuario = $this->campos();
         $campos_usuario[count($campos_usuario)]="";
      }else{
        $campos_usuario = explode(',', $campos_usuario[0]->campos);
      }

      $data['campos_usuario']=$campos_usuario;
      $data['consultaImpresion'] = $consultaImpresion;
      $data['consultaImpresion_excel'] = $consultaImpresion_excel;
      
      $data['produccion'] = $produccion;
      $data['usuario_permisos']=$usuario_permisos;
      $data['header'] = $this->header_construct();
      $data['resumen']=$cadena_resumen;
      $data['escenas']=$escenas;
      $data['cantidad']=$cantidad;
      $data['tiempo']=$tiempo_diponible;
      $data['tiempo_producido']=$tiempo_producido;
      $data['msg']=$msg;
      $data['total']=$total;
      echo json_encode($data);
  }

  /*FUNCION BUSQUEDA DE ESCENAS*/
  public function find_scenes(){
    $palabra = $_POST['palabra'];
    $limit_plan_general = $_POST['limit_plan_general'];
    $idproduccion = $_POST['idproduccion'];
    $idescenas = $_POST['idescenas'];
    $produccion = $this->model_plan_produccion->produccion_id($idproduccion);
    $personajes_produccion = $this->model_plan_general->categoria_produccion($idproduccion, 'Personaje');
    $sql="";

    if($idescenas!=""){
      $idescenas = explode(',', $idescenas);
      for ($i=0; $i < count($idescenas); $i++) { 
        if($idescenas[$i]!=""){
          $sql.= " AND  esc.id != ".$idescenas[$i]." ";
        }
      }
    }
  
    if($limit_plan_general==3000){
      $escenas = $this->model_plan_general->find_escenas_total($idproduccion, $personajes_produccion[0]->id,$palabra,$sql);
       
    }else{
      $escenas = $this->model_plan_general->find_escenas($idproduccion, $personajes_produccion[0]->id,$palabra,$sql,$limit_plan_general);  
    }

    $total = $this->model_plan_general->find_escenas_total($idproduccion, $personajes_produccion[0]->id,$palabra,$sql);
   
    $limit_plan_general=$limit_plan_general+50;

    $consulta=$this->db->last_query();
    $cadena_tabla ="";
    $tiempo_diponible="00:00";
    $cantidad=0;
    if($escenas){
    //  $cadena_tabla = $this->table_construct($escenas,$produccion);
      $msg='2';
      $sql ="";
      $total = $this->model_plan_general->find_escenas_total($idproduccion, $personajes_produccion[0]->id,$palabra,$sql);
      $cantidad = count($total);
      foreach ($total as $escena) {
        if($sql==""){
          $sql.= " WHERE escena.id = ".$escena->id;
        }else{
          $sql .= " OR escena.id = ".$escena->id;
        }
      }
      $temporal = $this->model_escenas_2->suma_tiempos_ajax($sql);
      $tiempo_diponible = $this->calculo_tiempo($temporal);
    }else{
      $msg='1';
    }
     $total=count($total);

    $iduser = $this->session->userdata('id_pruduction_suite');
    $usuario_permisos = $this->permisos_usuarios($produccion[0]->id_produccion);
    $data_campos=array(
      'id_usuario'=>$iduser,
      'tipo'=>2);
    $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
    if($campos_usuario==false){
       $campos_usuario = $this->campos();
       $campos_usuario[count($campos_usuario)]="";
    }else{
      $campos_usuario = explode(',', $campos_usuario[0]->campos);
    }
    

    $data['consultaImpresion'] = $consulta;
    $data['consultaImpresion_excel'] = $consulta;

    $data['campos_usuario']=$campos_usuario;
    $data['limit_plan_general']=$limit_plan_general;

    $data['produccion'] = $produccion;
    $data['usuario_permisos']=$usuario_permisos;
    $data['header'] = $this->header_construct();
    $data['escenas'] = $escenas;
    $data['msg']=$msg;
    $data['cantidad']=$cantidad;
    $data['tiempo']=$tiempo_diponible;
    $data['total']=$total;
    echo json_encode($data);
  }

  /*FUNCION PARA CONSTRUIR LA TABLA DE PLAN GENERAL*/
  public function table_construct($escenas,$produccion){
      $iduser = $this->session->userdata('id_pruduction_suite');
      $usuario_permisos = $this->permisos_usuarios($produccion[0]->id_produccion);

      $data_campos=array(
        'id_usuario'=>$iduser,
        'tipo'=>2);
      $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
      if($campos_usuario==false){
         $campos_usuario = $this->campos();
         $campos_usuario[count($campos_usuario)]="";
      }else{
        $campos_usuario = explode(',', $campos_usuario[0]->campos);
      }

      $campos = $this->campos();
      $cadena_tabla = "";

      if($campos_usuario){ 
        $cadena_tabla.=
        '<thead>
            <tr>
            <th width="10%"><span class="has-tip tip-centered-top" title="Unidad" style="display:block; width:60px;">UNI</span></th>
            <th width="10%"><span class="has-tip tip-centered-top" title="'.lang('global.fecha').'" style="display:block; width:90px;">'.lang('global.fecha').'</span></th>';
       for ($i=0; $i < count($campos_usuario)-1; $i++) { 
            switch ($campos_usuario[$i]) {
               case "libreto":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_libretos').'" style="display:block; width:50px;">LIB</span></th>';
               break;
               case "escena":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_escena').'" style="display:block; width:60px;">ESC</span></th>';
               break;
               case "página":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.pagina').'"  style="display:block; width:100%;">'.lang('global.pagina').'</span></th>';
               break;
               case "día":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.dia').'"  style="display:block; width:60px;">'.lang('global.dia').'</span></th>';
               break;
               case "locación":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.locacion').'"  style="display:block; width:100%;">'.lang('global.locacion').'</span></th>';
               break;
               case "set":
                $cadena_tabla.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.set').'"  style="display:block; width:120px;">'.lang('global.set').'</span></th>';
               break;
               case "int/ext":
                $cadena_tabla.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.int_ext').'" style="display:block; width:90px;">INT/EXT</span></th>';
               break;
               case "d/n":
                $cadena_tabla.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_noche').'"  style="display:block; width:106px;">'.lang('global.dia_noche').'</span></th>';
               break;
               case "loc/ext":
                $cadena_tabla.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.loc_est').'"  style="display:block; width:100px;">LOC/EST</span></th>';
               break;
               case "tiempo estimado":
                $cadena_tabla.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_estimado').'"  style="display:block; width:90px;">TIE EST</span></th>';
               break;
               case "tiempo real":
                $cadena_tabla.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_post').'"   style="display:block; width:100px;">TIE REAL</span></th>';
               break;
               case "personajes principales":
                $cadena_tabla.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_principales').'" style="display:block; width:250px;">'.lang('global.personajes_principales').'</span></th>';
               break;
               case "personajes secundarios":
                $cadena_tabla.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_secundarios').'" style="display:block; width:250px;">'.lang('global.personajes_secundarios').'</span></th>';
               break;
               case "descripción":
                $cadena_tabla.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.descripcion').'" style="display:block; width:300px;">'.lang('global.descripcion').'</span></th>';
               break;
               case "elementos":
                $cadena_tabla.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.magnitud').'" style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>';
               break;
               case "magnitud":
                $cadena_tabla.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.magnitud').'" style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>';
               break;
            }
        }
        $cadena_tabla.='</tr>
        </thead>
        <tbody>';
    }else{    
        $cadena_tabla.=
        '<thead>
            <tr>
              <th width="10%"><span style="display:block; width:60px;">UNI</span></th>
              <th width="10%"><span style="display:block; width:90px;">'.lang('global.fecha').'</span></th>
              <th width="2%"><span style="display:block; width:50px;">LIB</span></th>
              <th width="2%"><span style="display:block; width:60px;">ESC</span></th>
              <th width="2%"><span style="display:block; width:60px;">'.lang('global.pagina').'</span></th>
              <th width="2%"><span style="display:block; width:60px;">'.lang('global.dia').'</span></th>
              <th width="2%"><span style="display:block; width:100%;">'.lang('global.locacion').'</span></th>
              <th width="2%"><span style="display:block; width:120px;">'.lang('global.set').'</span></th>
              <th width="3%"><span style="display:block; width:90px;">INT/EXT</span></th>
              <th width="3%"><span style="display:block; width:106px;">'.lang('global.dia_noche').'</span></th>
              <th width="3%"><span style="display:block; width:100px;">LOC/EST</span></th>
              <th width="3%"><span style="display:block; width:90px;">TIE EST</span></th>
              <th width="3%"><span style="display:block; width:100px;">TIE REAL</span></th>
              <th width="5%"><span style="display:block; width:250px;">'.lang('global.personajes_principales').'</span></th>
              <th width="5%"><span style="display:block; width:250px;">'.lang('global.personajes_secundarios').'</span></th>
              <th width="5%"><span style="display:block; width:300px;">'.lang('global.descripcion').'</span></th>
              <th width="5%"><span style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>
            </tr>
        </thead>
        <tbody>';
      }
      $k=0;
      foreach ($escenas as $escena) { $m=0;
        if($escena->estado!=3 AND $usuario_permisos=="write"){
                $class_tr = "actionAsing";
            }else{
                $class_tr="";
            }

            if($escena->estado == 1 AND $escena->unidad_numero==0 AND $escena->fecha_inicio==""){
                $class_tr = "actionAsing";
            }
        if($escena->fecha_inicio!=""){
            if(strtotime($escena->fecha_inicio) <= strtotime(date("d-M-Y"))){
                $class_tr = "oldPlan";
            }
        }
        $cadena_tabla.= '<tr class="' . $class_tr . '" data-plan="'.date('d-M-Y',strtotime($escena->fecha_inicio)).'" id="row_' . $escena->id . '" data-idescena="' . $escena->id . '" data-libreto="'.$escena->capitulo.'"';
        if($k%2!=0){
          $cadena_tabla.=' style="background: #e6e4e4 !important;"';
        }
        ++$k;
        $cadena_tabla.= '>';
        switch ($escena->estado) {
            case 1:
                $class = "td_yellow";
                break;
            case 2:
                $class = "td_retoma";
                break;
            case 3:
                $class = "td_black";
                break;
            case 4:
                $class = "td_cian";
                break;
            case 5:
                $class = "td_cian_light";
                break;
            case 6:
                $class = "td_green";
                break;
            case 7:
                $class = "td_green_light";
                break;
            case 8:
                $class = "td_pink";
                break;
            case 9:
                $class = "td_pink_light";
                break;
            case 10:
                $class = "td_orange";
                break;
            case 11:
                $class = "td_orange_light";
                break;
            default:
                $class = "td_brown_light";
                break;
        }

        $cadena_tabla.='<td class="align_center ' . $class . '">';

        
        if($escena->estado == 1 AND  $escena->unidad_produccion!=""){
          $cadena_tabla.= '<a href="' . base_url() . 'plan_diario/index/' .$produccion[0]->id_produccion.'/'.$escena->unidad_produccion.'/'.$escena->fecha_produccion. '">' .$escena->unidad_produccion_numero. '</a>';  
        }else{
            if ($escena->unidad_numero != 0) {
                $cadena_tabla.= '<a href="' . base_url() . 'plan_diario/index/' . $produccion[0]->id_produccion . '/' . $escena->idunidad . '/' . $escena->fecha_inicio . '">' . $escena->unidad_numero . '</a>';
            } else {
                $cadena_tabla.= '-';
            }
        } 

        $cadena_tabla.= '</td>';
        $cadena_tabla.='<td class="align_center ' . $class . '">';


        if ($escena->estado == 1 AND  $escena->fecha_produccion!="" AND  $escena->fecha_produccion!="0000-00-00") {
            $cadena_tabla.= '<a href="' . base_url() . 'plan_diario/index/' . $produccion[0]->id_produccion . '/' . $escena->unidad_produccion . '/' . $escena->fecha_produccion . '">' . date("d-M-Y", strtotime($escena->fecha_produccion)) . '</a>';
        } else {
          if ($escena->fecha_inicio != "" AND $escena->fecha_inicio != "0000-00-00") {
            $cadena_tabla.= '<a href="' . base_url() . 'plan_diario/index/' . $produccion[0]->id_produccion . '/' . $escena->idunidad . '/' . $escena->fecha_inicio . '">' . date("d-M-Y", strtotime($escena->fecha_inicio)) . '</a>';
          } else {
            $cadena_tabla.= '-';
          }
        }

        $cadena_tabla.='</td>';
        
        while ( $m < count($campos_usuario)-1) {
          if($campos_usuario[$m]=="libreto"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->capitulo. '</td>';
          }
          if($campos_usuario[$m]=="escena"){ ++$m; 
          $cadena_tabla.='<td class="align_center">' . $escena->numero_escena . '</td>';
          }
          if($campos_usuario[$m]=="página"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->libreto . '</td>';
          }
          if($campos_usuario[$m]=="día"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->dias_continuidad . '</td>';
          }
          if($campos_usuario[$m]=="locación"){ ++$m;
          $cadena_tabla.='<td class="cell_align_left">' . $escena->locacion . '</td>';
          }
          if($campos_usuario[$m]=="set"){ ++$m;
          $cadena_tabla.='<td class="cell_align_left">' . $escena->setnombre . '</td>';
          }
          if($campos_usuario[$m]=="int/ext"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->ubicacion . '</td>';
          }
          if($campos_usuario[$m]=="d/n"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->tiempo . '</td>';
          }
          if($campos_usuario[$m]=="loc/ext"){ ++$m;
          $cadena_tabla.='<td class="align_center">' . $escena->tipo . '</td>';
          }
          if($campos_usuario[$m]=="tiempo estimado"){ ++$m;
            $cadena_tabla.='<td class="align_center">';
            if (strlen($escena->duracion_estimada_minutos) < 2) {
                $cadena_tabla.= '0' . $escena->duracion_estimada_minutos . ':';
            } else {
                $cadena_tabla.= $escena->duracion_estimada_minutos . ':';
            }

            if (strlen($escena->duracion_estimada_segundos) < 2) {
                $cadena_tabla.= '0' . $escena->duracion_estimada_segundos;
            } else {
                $cadena_tabla.= $escena->duracion_estimada_segundos;
            }
            $cadena_tabla.='</td>';
          }
          if($campos_usuario[$m]=="tiempo real"){ ++$m;
          $cadena_tabla.='<td class="align_center">';

          if (strlen($escena->duracion_real_minutos) < 2) {
              $cadena_tabla.= '0' . $escena->duracion_real_minutos . ':';
          } else {
              $cadena_tabla.= $escena->duracion_real_minutos . ':';
          }

          if (strlen($escena->duracion_real_segundos) < 2) {
              $cadena_tabla.= '0' . $escena->duracion_real_segundos;
          } else {
              $cadena_tabla.= $escena->duracion_real_segundos;
          }
          $cadena_tabla.='</td>';
          }
          if($campos_usuario[$m]=="personajes principales"){ ++$m;
          $cadena_tabla.='<td class="cell_align_left">';
          $cadena_tabla.='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="' . $escena->personajes_principales . '">';

          $cadena_tabla.= $this->corta_palabra($escena->personajes_principales, 30);
          if (strlen($escena->personajes_principales) >= 30) {
              $cadena_tabla.= '...';
          }
          $cadena_tabla.='</span>';
          $cadena_tabla.='</td>';
          }
          if($campos_usuario[$m]=="personajes secundarios"){ ++$m;
          $cadena_tabla.='<td class="cell_align_left">';
          $cadena_tabla.='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="' . $escena->personajes_secundarios . '">';

          $cadena_tabla.= $this->corta_palabra($escena->personajes_secundarios, 30);
          if (strlen($escena->personajes_secundarios) >= 30) {
              $cadena_tabla.= '...';
          }

          $cadena_tabla.='</span>';
          $cadena_tabla.='</td>';
          }
          if($campos_usuario[$m]=="descripción"){ ++$m;
          $cadena_tabla.='<td width="5%">';
          $cadena_tabla.='<div class="descriptionText">';
          $cadena_tabla.='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="' . $escena->descripcion . '">';

          /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES descripcion */
          $cadena_tabla.= $this->corta_palabra($escena->descripcion, 40);
          if (strlen($escena->descripcion) >= 40) {
              $cadena_tabla.= '...';
              $cadena_tabla.='      </span>';
              $cadena_tabla.='    </div>';
              $cadena_tabla.='</td>';
              
          }
          }
          if($campos_usuario[$m]=="elementos"){ ++$m;
          $cadena_tabla.='<td>';
          $cadena_tabla.='<span style="color:#333;font-weight: initial;" class="has-tip tip-centered-top tooltip_info" title="' . $escena->elementos . '">';
          /* VALIDACIÓN PARA EL MAXIMO DE CARACTERES ELEMENTOS */
          $cadena_tabla.= $this->corta_palabra($escena->elementos, 30);
          if (strlen($escena->elementos) >= 30) {
              $cadena_tabla.= '...';
          }
          $cadena_tabla.= '    </span>';
          $cadena_tabla.= '</td>';
          }

          if($campos_usuario[$m]=="magnitud"){ ++$m;

          }

          if($campos_usuario[$m]=="vehículo background"){ ++$m;

          }
          
          //$cadena_tabla.= '</tbody>';
        }
        $cadena_tabla.= '</tr>';
      }
      $cadena_tabla.= '</tbody>';
      return $cadena_tabla; 
  }

  public function header_construct(){
    $iduser = $this->session->userdata('id_pruduction_suite');
      $data_campos=array(
        'id_usuario'=>$iduser,
        'tipo'=>2);
      $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
      if($campos_usuario==false){
         $campos_usuario = $this->campos();
         $campos_usuario[count($campos_usuario)]="";
      }else{
        $campos_usuario = explode(',', $campos_usuario[0]->campos);
      }

      $campos = $this->campos();
      $cadena_header = "";

    if($campos_usuario){ 
        $cadena_header.=
        '<thead>
            <tr>
            <th width="10%" class="header"><span class="has-tip tip-centered-top" title="Unidad" style="display:block; width:60px;">UNI</span></th>
            <th width="10%"><span class="has-tip tip-centered-top" title="'.lang('global.fecha').'" style="display:block; width:90px;">'.lang('global.fecha').'</span></th>';
        for ($i=0; $i < count($campos_usuario)-1; $i++) { 
            switch ($campos_usuario[$i]) {
              case "libreto":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_libretos').'" style="display:block; width:50px;">LIB</span></th>';
               break;
               case "escena":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.numero_escena').'" style="display:block; width:60px;">ESC</span></th>';
               break;
               case "página":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.pagina').'"  style="display:block; width:100%;">'.lang('global.pagina').'</span></th>';
               break;
               case "día":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.dia').'"  style="display:block; width:60px;">'.lang('global.dia').'</span></th>';
               break;
               case "locación":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.locacion').'"  style="display:block; width:100%;">'.lang('global.locacion').'</span></th>';
               break;
               case "set":
                $cadena_header.= '<th width="2%"><span class="has-tip tip-centered-top" title="'.lang('global.set').'"  style="display:block; width:120px;">'.lang('global.set').'</span></th>';
               break;
               case "int/ext":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.int_ext').'" style="display:block; width:90px;">INT/EXT</span></th>';
               break;
               case "d/n":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.dia_noche').'"  style="display:block; width:106px;">'.lang('global.dia_noche').'</span></th>';
               break;
               case "loc/ext":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.loc_est').'"  style="display:block; width:100px;">LOC/EST</span></th>';
               break;
               case "tiempo estimado":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_estimado').'"  style="display:block; width:90px;">TIE EST</span></th>';
               break;
               case "tiempo real":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_post').'"   style="display:block; width:100px;">TIE REAL</span></th>';
               break;
               case "tiempo post":
                $cadena_header.= '<th width="3%"><span class="has-tip tip-centered-top" title="'.lang('global.tiempo_post').'"  style="display:block; width:100px;">TEI POST</span></th>';
               break;
               case "personajes principales":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_principales').'" style="display:block; width:250px;">'.lang('global.personajes_principales').'</span></th>';
               break;
               case "personajes secundarios":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.personajes_secundarios').'" style="display:block; width:250px;">'.lang('global.personajes_secundarios').'</span></th>';
               break;
               case "descripción":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.descripcion').'" style="display:block; width:300px;">'.lang('global.descripcion').'</span></th>';
               break;
               case "elementos":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.elementos').'" style="display:block; width:250px;">'.lang('global.elementos').'</span></th>';
               break;
               case "magnitud":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.magnitud').'" style="display:block; width:250px;">'.lang('global.magnitud').'</span></th>';
               break;
               case "vehículo background":
                $cadena_header.= '<th width="5%"><span class="has-tip tip-centered-top" title="'.lang('global.vehivulo_back').'" style="display:block; width:250px;">'.lang('global.vehivulo_back').'</span></th>';
               break;
            }
        }
        $cadena_header.='</tr>
        </thead>
        <tbody class="ui-selectable">';
    }
      return $cadena_header;
  }

  /*FUNCION GUARDAR LA CONSULTA DE FILTRO REALIZADA*/
  public function guardar_consulta(){
    $idproduccion = $_POST['idproduccion'];
    $iduser = $this->session->userdata('id_pruduction_suite');
    $nombre = $_POST['nombre'];
    $data=array(
      'id_user'     => $iduser,
      'nombre' => $nombre,
      'id_produccion' => $idproduccion
    );
    $this->model_plan_general->guardar_consulta($data);
    $data['idconsulta']=mysql_insert_id();
    echo json_encode($data);
  }

  /*FUNCION EJECUTAR LA CONCULTA GUARDADA*/
  public function ejecutar_consulta($idconsulta, $idproduccion){
    $consulta = $this->model_plan_general->consulta_id($idconsulta);
    $query=$this->db->query($consulta[0]->consulta);
    if ($query->num_rows>0) {
      $escenas = $query->result();
    }else{
      $escenas = "";
    }
    $this->index($idproduccion, $escenas, $consulta[0]->resumen);
  }
  /*CORTAR PALABRAS*/
  public static function corta_palabra($palabra,$num) {
    $largo=strlen($palabra);
    $cadena=substr($palabra,0,$num);
    return $cadena;
  }
  /*BUSCAR ESCENAS ASIGNADAS A PLANES*/
  public function buscar_asignadas(){
    $idescena = $_POST['idescena'];
    $resultado = false;
    $idunidad = $_POST['idunidad'];
    $fecha = $_POST['fecha'];

    $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date('d-M-Y',strtotime($fecha)),$idunidad);

    $id_plan =0;
    if($plan_diario){
      $id_plan = $plan_diario[0]->id;
    }
    $escena = $this->model_plan_general->plan_diario_by_escena_id($idescena,$id_plan);
    if($escena!=false){
      $resultado = true;
      $plan = $this->model_plan_general->plan_diario_id($escena[0]->id_plan_diario);
      $data['idplan']=$plan[0]->id_plan_diario;
      $data['plan']=$plan;
    }
    $data['resultado']=$resultado;
    echo json_encode($data);
  } 
 

  /*BORRAR ESCENA DE UN PLAN DIARIO*/
  public function borrar_plan_diario($idplan, $idplan_diario,$idescena){
    $plan = explode(",",$idplan);
        for ($i=0; $i < count($plan); $i++){ 
      if($plan[$i]!=""){
        $plan_selected = $this->model_plan_general->plan_diario_id_2($plan[$i]);
        if($plan_selected[0]->llamado!=""){
          $fecha= strtotime($plan_selected[0]->fecha_inicio.' '.$plan_selected[0]->llamado);
        }else{
          $fecha = strtotime($plan_selected[0]->fecha_inicio);
        }


        if($plan_selected[0]->llamado == '' OR  $fecha > strtotime(date("d-M-Y H:i:s")) ){

          $numero = $this->db->query("SELECT COUNT(id)+1 AS numero from plan_diario_has_escenas_has_unidades WHERE id_plan_diario = ".$idplan_diario.";");
          $numero = $numero->result();
          $numero = $numero[0]->numero;
          $this->model_plan_general->cambiar_plan_diario($plan[$i],$idplan_diario,$idescena,$numero);
          $escenas_plan=$this->model_plan_diario->escenas_id_plan_diario($idplan_diario);
          if($escenas_plan){
            for ($m=0; $m < count($escenas_plan); $m++) { 
              $j = $m+1;
              $data = array(
                'orden' => $j 
              );
              $this->db->where('id_escena',$escenas_plan[$m]->id_escena);
              $this->db->where('id_plan_diario',$plan[$i]);
              $this->db->update('plan_diario_has_escenas_has_unidades',$data);
            }
          }
        }
        $cantidad = $this->model_plan_diario->contar_escenas_plan($plan[$i]);
        if($cantidad[0]->cantidad <= 0){
          $completar_plan=$this->model_plan_diario->completar_plan($plan[$i],1);
        }else{
          $escenas_plan=$this->model_plan_diario->escenas_id_plan_diario($plan[$i]);
          if($escenas_plan){
            for ($k=0; $k < count($escenas_plan); $k++) {
              $j = $k+1;
              $data = array(
                'orden' => $j
              );
              $this->db->where('id_escena',$escenas_plan[$k]->id_escena);
              $this->db->where('id_plan_diario',$plan[$i]);
              $this->db->update('plan_diario_has_escenas_has_unidades',$data);
            }
          }
        }
      }
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

     $f=str_replace('ene','Jan',$f); 
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


  /*ASIGNAR ESCENA A PLAN DIARIO*/
  public function asignar_plan_diario(){
      $idproduccion = $this->input->post('idproduccion');
      $fecha_inicio = $this->input->post('fecha_inicio');
      $fecha_inicio_p = $this->input->post('fecha_inicio');
      $idunidad = $this->input->post('unidad');
      $idescenas = $this->input->post('idescenas');
      $temporal = $this->input->post('idplanes');
      $cadena="";
      //$plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("d-M-Y",strtotime($fecha_inicio)), $idunidad);
      $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha($this->fechaFormat($fecha_inicio), $idunidad);
      
      $query = $this->db->query("SELECT id_director, id_script FROM unidad where id = ".$idunidad.";");
      $unidad_data = $query->result();
      $unidad = $this->model_plan_diario->unidad_id($idunidad);
      if($plan_diario==false){
        $data=array(
          'fecha_inicio' => $this->fechaFormat($fecha_inicio),
          'id_unidad' => $idunidad, 
          'id_director' => $unidad_data[0]->id_director,
          'id_script' =>  $unidad_data[0]->id_script,  
          'estado' => 2
        );
        $this->model_plan_general->crear_plan_diario($data);
        $idplan_diario = mysql_insert_id();
        $this->log_plan_diario($idplan_diario);
        $ultima_edicion = $this->model_plan_diario->ultima_edicion($idplan_diario);
        $fecha_inicio=$this->fechaFormat($fecha_inicio);
        $cadena .= "\n" . " PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($fecha_inicio)).' CREADO.';
        $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha($fecha_inicio, $idunidad);
      }else{
        $idplan_diario = $plan_diario[0]->id;
        $this->log_plan_diario($idplan_diario);
        $ultima_edicion = $this->model_plan_diario->ultima_edicion($idplan_diario);
      }
      $escenas = explode(",",$idescenas);

      for ($i=0; $i < sizeof($escenas); $i++) { 
        if($temporal!=""){
          $this->borrar_plan_diario($temporal, $idplan_diario,$escenas[$i]);
        }
        if($escenas[$i]!=""){
          $escena_actual=$this->model_escenas_2->escena_id($escenas[$i]);
          $capitulo_actual = $this->model_capitulos->bucar_capitulo_id($escena_actual[0]->id_capitulo);
          $cadena .= "\n" . " ESCENA ".$capitulo_actual[0]['numero'].' / '.$escena_actual[0]->numero_escena.' AGREAGADA AL PLAN UNIDAD '.$unidad[0]['numero'].' '.date("d-M-Y",strtotime($fecha_inicio));
          
          if($plan_diario[0]->estado==1){
            $this->model_plan_diario->completar_plan($plan_diario[0]->id,2);
             $cadena .= "\n" . "PLAN DIARIO UNIDAD ".$unidad[0]['numero'].' '.date("d-M-Y",strtotime($fecha_inicio)).' PASA A ESTADO ABIERO PRIVADO.';
          }
          $numero = $this->db->query("SELECT COUNT(id)+1 AS numero from plan_diario_has_escenas_has_unidades WHERE id_plan_diario = ".$idplan_diario.";");
          $numero = $numero->result();
          if(!$this->model_plan_general->escena_plan($idplan_diario,$escenas[$i])){ 
            $data=array(
              'id_plan_diario'  => $idplan_diario,
              'id_escena' => $escenas[$i],
              'orden' => $numero[0]->numero
            );
            $this->model_plan_general->asignar_plan_diario($data);
          }
          $estado = $this->model_escenas_2->escena_by_id($escenas[$i]);
          $estado_cambio = $estado[0]->estado;
          switch ($estado_cambio) {
            case 1:
              $escena_retoma = $this->model_escenas_2->escena_by_id($escenas[$i]);
              $estado_cambio = 12;
              break;
            case 2:
              $estado_cambio = 14;
              break;
            case 5:
              $estado_cambio = 4;
              break;
            case 7:
              $estado_cambio = 6;
              break;
            case 9:
              $estado_cambio = 8;
              break;
            case 11:
              $estado_cambio = 10;
              break;
            default:
             $estado_cambio = $estado[0]->estado;
            break;
          }
          $this->user_log($unidad[0]['id_produccion'],$cadena);
          $this->model_escenas_2->cambia_estado($escenas[$i],$estado_cambio);
        }
      }


      //*****ASIGNAR FECHA DE INICIO SOLICTUDES*******///////////////
      $f=date("Y-m-d",strtotime($this->fechaFormat($fecha_inicio)));
       $elementos=$this->model_plan_diario->elementosByidplan($idplan_diario);

       if($elementos){
          foreach ($elementos as $e) {
            $fecha_inicio=$this->model_plan_diario->fecha_inicioByidelemento($e['id']);
             if(strtotime($fecha_inicio['0']->fecha_inicio)>strtotime($f)){
                 $solicitudes=$this->model_plan_diario->solicitudesByidelemento($e['id']);
                 if($solicitudes){
                    foreach ($solicitudes as $s) {
                        $data=array('fecha_inicio' =>$f,'id'=>$s['id']);
                        $this->model_casting->update_solicitud($data); 
                    }
                    
                 }
             }
          }
       }
       //////////***************
   
        if($this->input->post('validator')){
          redirect ($this->lang->lang().'/plan_diario/index/'.$idproduccion.'/'.$idunidad.'/'.$this->fechaFormat($fecha_inicio_p));
        }else{
          redirect ($this->lang->lang().'/plan_general/index/'.$idproduccion);
        }
  } 

  /*FUNCION BUSCAR PLAN DIARIO POR ESTADO*/
  public function buscar_estado_plan(){
    $idunidad = $_POST['idunidad'];
    $fecha = $_POST['fecha'];
    $plan_diario = $this->model_plan_general->buscar_plan_diario_fecha(date("d-M-Y",strtotime($fecha)), $idunidad);
    if($plan_diario){
      if($plan_diario[0]->estado==5){
        $estado = false;
      }else{
        $estado = true;
      }
    }else{
      $estado = true;
    }
    $data['estado']=$estado;
    echo json_encode($data);
  }


  /*INSERCION DE ULTIMA PERSONA EDITAR PLAN*/
  public function log_plan_diario($idplan){
    $idusuario = $this->session->userdata('id_pruduction_suite');
    $this->model_plan_diario->log_plan_diario($idplan,$idusuario); 
  }
  /*FIN INSERCION DE ULTIMA PERSONA EDITAR PLAN*/

  /*FUNCION CALCULO DE TIEMPO DURACION ESCENAS*/
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
  /*FUNCION ELEMINAR CONSULTA FILTRO*/
  public function eliminar_consulta(){
    $idconsulta= $_POST['idconsulta'];
    $this->model_plan_general->eliminar_consulta($idconsulta);
    $data=1;
    echo json_encode($data);
  }
  /*FUNCION VERIFICAR FECHA PARA PLAN*/
  public function verificar_fecha(){
    $fecha = $_POST['fecha'];
    $this->model_plan_general->verificar_fecha($fecha);
    $data['resultado'] = false;
    echo json_encode($data);
  }
  /*FUNCION PARA VERIFICAR FECHAS DE PLAN SEGUN DIAS A TRABAJAR EN PLAN PRODUCCION*/
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
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case "Tue":
            if($semana['martes'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case "Wed":
            if($semana['miercoles'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case "Thu":
            if($semana['jueves'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case "Fri":
            if($semana['viernes'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case 'Sat':
            if($semana['sabado'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
          case 'Sun':
            if($semana['domingo'] != 'checked'){
              $cadena_fechas.= date("dmY",$contador_dias).',';
            }
          break;
        }
        $contador_dias+=$j;
      }
    }
    return $cadena_fechas;
  }
  /*AGREGAR RETOMA A ESCENA*/
  public function remotas_escena($data,$idproduccion){
    $this->model_escenas_2->insert_remotas_escena($data);
  }
  /*ACTULIZAR TIEMPOS DE CAPITULOS, ESCENA, PRODUCCION*/
  public function actualizar_tiempos($idescena){
    $escena = $this->model_escenas_2->escena_id($idescena);
    $idcapitulo = $escena[0]->id_capitulo;
    $escenas = $this->model_escenas_2->sumar_tiempos_reales($idcapitulo);
    $tiempo = $this->calculo_tiempo($escenas);
    $data=array(
        'duracion_real'=>$tiempo,
    );
    $this->db->where('id',$idcapitulo);
    $this->db->update('produccion_has_capitulos',$data);

    $capitulo = $this->model_capitulos->bucar_capitulo_id($idcapitulo);

      $escenas_canceladas=$this->model_escenas->escenas_canceladas($idlibreto);
      $t=$escenas_canceladas['0']->total;
      
      if($t>0){
        $total=$capitulo[0]['escenas_escritas']-$t;
      }else{
        $total=$capitulo[0]['escenas_escritas'];  
      }

      $escenas_producidas = count($this->model_escenas_2->escenas_producidas_idcapitulo($idcapitulo));
      if($total <= $escenas_producidas){
      $data=array(
        'estado'=>5,
      );
      $this->db->where('id',$idcapitulo);
      $this->db->update('produccion_has_capitulos',$data);
    }
  }
  /*BUSCAR SETS EN PRODUCCION*/
  public function sets_produccion(){
    $idproduccion = $_POST['idproduccion'];
    $sets = $this->model_plan_general->sets_produccion($idproduccion);
    $data['set'] = $sets;
    echo json_encode($data);
  }
  /*CONSULTAR COLUMNAS USUARIO*/
  public function orden_columnas(){
    $id_user = $this->session->userdata('id_pruduction_suite');
    $campos =  $this->input->post('campos_columnas');
    $idproduccion = $this->input->post('idproduccion');
    $tipo = 2;
    $data=array('id_usuario'=>$id_user,'campos'=>$campos,'tipo'=>2);
    $existe = $this->model_plan_diario->buscar_columnas($data);
    if($existe){
      $this->model_plan_diario->actualizar_columnas($data);
    }else{
      $this->model_plan_diario->agregar_columnas($data);
    }
    redirect ($this->lang->lang().'/plan_general/index/'.$idproduccion);
  }
  /*REORDENAR FILAS DE ESCENAS PLAN*/
  public function reorder_fields(){
    $cadena_campos ="";
    $iduser = $this->session->userdata('id_pruduction_suite');
    $data_campos=array(
      'id_usuario'=>$iduser,
      'tipo'=>2);
    $campos_usuario = $this->model_plan_diario->buscar_columnas($data_campos);
    if($campos_usuario==false){
       $campos_usuario = $this->campos();
       $campos_usuario[count($campos_usuario)]="";
    }else{
      $campos_usuario = explode(',', $campos_usuario[0]->campos);
    }

    $campos = $this->campos();
    $cadena_campos .= 
    '<div class="column six">
        <h3>Campos Activos</h3>
        <ul id="itemsEnable" class="connectedSortable">';
    if($campos_usuario){
      for ($i=0; $i < count($campos_usuario)-1; $i++) {
        $cadena_campos .= '<li class="active_field" data-order="'.($i).'">'.$campos_usuario[$i].'</li>';
      } 
    }
    $cadena_campos .= 
    '</ul></div><div class="column six"><h3>Campos Disponibles</h3><ul id="itemsDisable" class="connectedSortable">';
          for ($i=0; $i < count($campos); $i++) { 
            $validacion = true;
            for ($j=0; $j < count($campos_usuario)-1; $j++) { 
                if($campos_usuario[$j]==$campos[$i]){
                    $validacion=false;
                }
            }
            if($validacion){
            $cadena_campos .= '<li class="inactive_field">'.$campos[$i].'</li>';
          } 
        }
    $cadena_campos .= '</ul></div>';
    $data['cadena_campos'] = $cadena_campos;
    echo json_encode($data);
  }

    /*FUNCION LOG TXT*/
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