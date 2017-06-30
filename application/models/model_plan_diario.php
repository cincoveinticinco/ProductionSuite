<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_plan_diario extends CI_Model {

  public function escenas_unidad($id_unidad){
      $query=$this->db->query("SELECT UPPER(cap.*,h.id AS id_plan, h.*, e.*, e.descripcion AS descripcion_escena,  l.*, s.*, d.*, i.*, t.*,p.*,es.descripcion AS estado_plan
      ,d.descripcion AS des_dia, i.descripcion AS des_int, l.nombre AS nom_locacion, s.nombre AS nom_set, e.estado AS estado_escenas, (SELECT unidad.numero FROM unidad where unidad.id = e.unidad_produccion) AS unidad_produccion_numero,e.id AS id_escena,p.id as id_plan,h.id as id_plan_escena_unidad 
      ) from plan_diario p
      INNER JOIN plan_diario_has_escenas_has_unidades h ON h.id_plan_diario=p.id
      INNER JOIN escena e ON e.id=h.id_escena
      INNER JOIN locacion l ON l.id=e.id_locacion
      INNER JOIN sets s ON s.id=e.id_set
      INNER JOIN escenas_dias_noche d ON d.id=e.id_dia_noche
      INNER JOIN escena_interior_esterior i ON i.id=e.id_interior_esterior
      INNER JOIN tipo_locacion t ON t.id=e.id_tipo_locacion
      INNER JOIN estados_plan_diario es ON es.id=p.estado
      INNER JOIN produccion_has_capitulos cap ON cap.id = e.id_capitulo
      WHERE p.id_unidad=".$id_unidad." GROUP BY e.id ORDER BY h.orden;");
        if ($query->num_rows>0) {
          return $query->result_array();
        }else{
          return false;
        }
    }

    public function unidad_id_fecha($datos,$sql2 = "e.descripcion AS descripcion_escena, 
                e.guion AS guion_escena,
                d.descripcion AS des_dia, 
                i.descripcion AS des_int, 
                l.nombre AS nom_locacion, 
                s.nombre AS nom_set,
                t.tipo AS tipo,
               "){
     $query=$this->db->query("SELECT cap.*,h.id AS id_plan, h.*, e.*, h.id As id_plan_escenas, 
      (SELECT unidad.numero FROM unidad where unidad.id = e.unidad_produccion) AS unidad_produccion_numero,
      e.estado AS estado_escenas, 
      (SELECT group_concat(elemento.nombre ORDER BY elemento.nombre SEPARATOR ',') FROM elemento
      INNER JOIN categoria_elemento ON categoria_elemento.id=elemento.id_tipo_elemento
      INNER JOIN escenas_has_elementos h ON h.id_elemento = elemento.id
      WHERE h.id_escena = e.id
      AND categoria_elemento.tipo='Personaje' AND (elemento.rol = 1 OR elemento.rol = 3)  and elemento.id=h.id_elemento ORDER BY elemento.nombre) AS personajes_principales,
      
      (SELECT group_concat(elemento.nombre, 
      (CASE WHEN extras_escena.cantidad IS NULL THEN '' ELSE concat('(',extras_escena.cantidad,')') END) ORDER BY elemento.nombre SEPARATOR ',' ) FROM elemento
      INNER JOIN categoria_elemento ON categoria_elemento.id=elemento.id_tipo_elemento
      INNER JOIN escenas_has_elementos h ON h.id_elemento = elemento.id
      LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = h.id
      WHERE h.id_escena = e.id
      AND categoria_elemento.tipo='Personaje' AND (elemento.rol = 2 OR elemento.rol = 4)  and elemento.id=h.id_elemento
      ORDER BY elemento.nombre) AS personajes_secundarios,
      
      es.descripcion AS estado_plan, l.*, s.*, d.*, i.*,p.*,".$sql2."  (SELECT COUNT(id) FROM retomas_escena where id_escena = e.id AND retomas_escena.fecha_produccion <= '".$datos['fecha']."' ) AS retomas, e.guion AS test, e.id AS id_escena,p.id as id_plan, cap.numero AS numero_capitulo,h.id as id_plan_escena_unidad 
      from plan_diario p
      INNER JOIN plan_diario_has_escenas_has_unidades h ON h.id_plan_diario=p.id
      INNER JOIN escena e ON e.id=h.id_escena
      INNER JOIN locacion l ON l.id=e.id_locacion
      INNER JOIN sets s ON s.id=e.id_set
      INNER JOIN escenas_dias_noche d ON d.id=e.id_dia_noche
      INNER JOIN escena_interior_esterior i ON i.id=e.id_interior_esterior
      INNER JOIN tipo_locacion t ON t.id = e.id_tipo_locacion
      INNER JOIN estados_plan_diario es ON es.id=p.estado
      INNER JOIN produccion_has_capitulos cap ON cap.id = e.id_capitulo
      WHERE p.id_unidad=".$datos['id_unidad']." and p.fecha_inicio='".$datos['fecha']."' and
      (case 
    WHEN (e.estado=3 and (SELECT id FROM retomas_escena where id_escena = e.id AND retomas_escena.fecha_produccion <= '".$datos['fecha']."' and
    unidad_produccion=p.id_unidad  ) is not null) then '1'
    WHEN (e.estado=3 and (SELECT id FROM retomas_escena where id_escena = e.id AND retomas_escena.fecha_produccion <= '".$datos['fecha']."' and
    unidad_produccion=p.id_unidad  ) is null) then '0'
    else '1' 
    END) = 1
      GROUP BY e.id ORDER BY h.orden");
        if ($query->num_rows>0) {
          return $query->result_array();
        }else{
          return false;
        }
    }

    public function unidad_id($id_unidad){
      $query=$this->db->query('SELECT *,(SELECT group_concat(r.nombre," ",r.apellido) FROM user r 
        WHERE id = u.id_director) AS dir, (SELECT group_concat(r.nombre," ",r.apellido) from user r 
        WHERE id = u.id_script) AS scr from unidad u WHERE u.id='.$id_unidad);
        if ($query->num_rows>0) {
          return $query->result_array();
        }else{
          return false;
        }
    }

    public function unidad_id_plan($id_unidad,$fecha_plan){
      $query=$this->db->query('SELECT u.*,(SELECT group_concat(r.nombre," ",r.apellido) FROM user r  
        WHERE id = plan_diario.id_director) AS dir, (SELECT group_concat(r.nombre," ",r.apellido) from user r 
        WHERE id = plan_diario.id_script) AS scr from unidad u INNER JOIN plan_diario ON id_unidad = u.id WHERE plan_diario.fecha_inicio = "'.$fecha_plan.'" AND  u.id='.$id_unidad);
        if ($query->num_rows>0) {
          return $query->result_array();
        }else{
          return false;
        }
    }

  public function elemento_personajes($id_escena){
    $query=$this->db->query('SELECT *, extras_escena.cantidad AS cantidad_extra FROM elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      AND categoria_elemento.tipo="Personaje"
      INNER JOIN escenas_has_elementos h ON h.id_elemento = e.id
      LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = h.id
      WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.nombre');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function elemento_personajes_pdf($fecha,$idunidad){
    $query=$this->db->query('(SELECT DISTINCT(e.id),e.nombre, rol_actores_elementos.id AS id_rol, rol_actores_elementos.rol AS rol_elemento FROM elemento e
                            INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
                            INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena
                            INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario
                            INNER JOIN categoria_elemento ON categoria_elemento.id = e.id_tipo_elemento  
                            INNER JOIN rol_actores_elementos ON e.rol = rol_actores_elementos.id 
                            WHERE plan_diario.fecha_inicio = "'.$fecha.'" AND rol_actores_elementos.id = 1  AND plan_diario.id_unidad = '.$idunidad.' 
                            AND  categoria_elemento.tipo="Personaje" 
                            ORDER BY rol_actores_elementos.id,e.nombre)
                            UNION ALL 
                            (SELECT DISTINCT(e.id),e.nombre, rol_actores_elementos.id AS id_rol, rol_actores_elementos.rol AS rol_elemento  FROM elemento e
                            INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
                            INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena
                            INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario
                            INNER JOIN categoria_elemento ON categoria_elemento.id = e.id_tipo_elemento  
                            INNER JOIN rol_actores_elementos ON e.rol = rol_actores_elementos.id 
                            WHERE plan_diario.fecha_inicio = "'.$fecha.'" AND rol_actores_elementos.id = 3  AND plan_diario.id_unidad = '.$idunidad.' 
                            AND  categoria_elemento.tipo="Personaje" 
                            ORDER BY rol_actores_elementos.id,e.nombre)
                            UNION ALL 
                            (SELECT DISTINCT(e.id),e.nombre, rol_actores_elementos.id AS id_rol, rol_actores_elementos.rol AS rol_elemento  FROM elemento e
                            INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
                            INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena
                            INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario
                            INNER JOIN categoria_elemento ON categoria_elemento.id = e.id_tipo_elemento  
                            INNER JOIN rol_actores_elementos ON e.rol = rol_actores_elementos.id 
                            WHERE plan_diario.fecha_inicio = "'.$fecha.'" AND rol_actores_elementos.id = 2  AND plan_diario.id_unidad = '.$idunidad.' 
                            AND  categoria_elemento.tipo="Personaje" 
                            ORDER BY rol_actores_elementos.id,e.nombre)
                            UNION ALL
                            (SELECT DISTINCT(e.id),e.nombre, rol_actores_elementos.id AS id_rol, rol_actores_elementos.rol AS rol_elemento  FROM elemento e
                            INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
                            INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena
                            INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario
                            INNER JOIN categoria_elemento ON categoria_elemento.id = e.id_tipo_elemento  
                            INNER JOIN rol_actores_elementos ON e.rol = rol_actores_elementos.id 
                            WHERE plan_diario.fecha_inicio = "'.$fecha.'" AND rol_actores_elementos.id = 4  AND plan_diario.id_unidad = '.$idunidad.' 
                            AND  categoria_elemento.tipo="Personaje" 
                            ORDER BY rol_actores_elementos.id,e.nombre);');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function cantidad_extras($id_elemento,$fecha_inicio,$fecha_fin){
    $query=$this->db->query('SELECT MAX(cantidad) AS cantidad FROM extras_escena 
                             INNER JOIN escenas_has_elementos ON  escenas_has_elementos.id = extras_escena.id_escenas_has_elementos
                             INNER JOIN elemento ON escenas_has_elementos.id_elemento = elemento.id
                             INNER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_escena = escenas_has_elementos.id_escena  
                             INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario  
                             WHERE elemento.id = '.$id_elemento.' AND plan_diario.fecha_inicio BETWEEN "'.$fecha_inicio.'" AND "'.$fecha_fin.'";');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }


  public function elemento_personajes_principales($id_escena){
    $query=$this->db->query('SELECT * FROM escenas_has_elementos h, elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      AND categoria_elemento.tipo="Personaje" AND (e.rol = 1 OR e.rol = 3) WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.nombre');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function elemento_personajes_secundarios($id_escena){
    $query=$this->db->query('SELECT *, extras_escena.cantidad AS cantidad_extras FROM elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      INNER JOIN escenas_has_elementos h on h.id_elemento = e.id
      AND categoria_elemento.tipo="Personaje" AND (e.rol = 2 OR e.rol = 4) 
      LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = h.id
      WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.nombre');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

    public function elementos_dif_personajes($id_escena){
    $query=$this->db->query('SELECT * FROM escenas_has_elementos h, elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      and categoria_elemento.tipo!="Personaje" WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.nombre');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
    }

    public function eliminar_retoma($idretoma){
        $this->db->where('id',$idretoma);
        $this->db->delete('retomas_escena');
    }

    public function eliminar_retoma_escenas_id($idescena){
      $this->db->query("DELETE FROM retomas_escena WHERE id_escena = ".$idescena."  AND estado = 1;");
    }

    public function actualizar_retoma($id_escena){
        $data=array(
          'fecha_retoma'=>date('Y-m-d'),
          'estado'=>2
        );
        $this->db->where('id_escena',$id_escena);
        $this->db->where('estado = ',1);
        $this->db->update('retomas_escena',$data);
    }

    public function actualizar_retoma_producida($id_retoma,$tiempo){
        $data=array(
          'fecha_retoma'=>date('Y-m-d'),
          'tiempo'=>$tiempo
        );
        $this->db->where('id',$id_retoma);
        $this->db->update('retomas_escena',$data);
    }

    public function retoma_id($id){
      $this->db->where('id',$id);
      $query = $this->db->get('retomas_escena');
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
    }

    public function retoma_escena_id($id_escena){
        $this->db->where('id_escena',$id_escena);
        $this->db->where('estado = ',2);
        $query =  $this->db->get('retomas_escena');
        if ($query->num_rows>0) {
          return $query->result();
        }else{
          return false;
        }
    }
    public function retoma_escenas($id_escena){
        $this->db->where('id_escena',$id_escena);
        $this->db->where('estado = ',2);
        $query =  $this->db->get('retomas_escena');
        if ($query->num_rows>0) {
          return $query->result();
        }else{
          return false;
        }
    }


    public function retoma_escena_producida($id_escena, $fecha, $idunidad){
        $this->db->where('id_escena',$id_escena);
        $this->db->where('unidad_produccion',$idunidad);
        $this->db->where('fecha_produccion',$fecha);
        $query =  $this->db->get('retomas_escena');
        if ($query->num_rows>0) {
          return $query->result();
        }else{
          return false;
        }
    }

    public function guardar_elementos($datos){
      if($datos['tipo']==1){
        $retoma = $this->retoma_escena_producida($datos['id_plan'],$datos['fecha_plan'],$datos['idunidad']);
        $fecha_p = $datos['fecha_plan']; 
        $unidad_p = $datos['idunidad'];
        $estado = 1;
        
        if(strlen($datos['valor'])<2){
            $datos['valor'] = '0'.$datos['valor']; 
          }

        if(strlen($datos['valor2'])<2){
          $datos['valor2'] = '0'.$datos['valor2']; 
        }else{
          if($datos['valor2']==''){
            $datos['valor2']='00';
          }
        }
        $minutos = $datos['valor'];
        $segundos = $datos['valor2'];
        if(!$retoma){

            $data=array(
              'id_escena'=>$datos['id_plan'],
              'fecha_produccion'=>$datos['fecha_plan'],
              'unidad_produccion'=>$datos['idunidad'],
              'fecha_retoma'=>date('Y-m-d'),
              'tiempo'=> $datos['valor'].':'.$datos['valor2'],
              'estado'=>2
            );
            $this->db->insert('retomas_escena',$data);
        }else{
          $data=array(
              'tiempo'=> $datos['valor'].':'.$datos['valor2'],
            );
          $this->db->where('id',$retoma[0]->id);
          $this->db->update('retomas_escena',$data); 
        }
        if($retomas_escenas = $this->retoma_escenas($datos['id_plan'])){
          if(count($retomas_escenas)>=2){
            $estado = 2;
          }
        }
            $this->db->where('id',$datos['id_plan']);
            $escena_n = $this->db->get('escena');
            $escena_n = $escena_n->result();
            if(strtotime($escena_n[0]->fecha_produccion) <= strtotime($datos['fecha_plan']) OR $escena_n[0]->fecha_produccion=="" OR $escena_n[0]->fecha_produccion == "0000-00-00"){
              $fecha_p = $datos['fecha_plan'];
              $unidad_p = $datos['idunidad'];
            }else{
              $fecha_p = $escena_n[0]->fecha_produccion;
              $unidad_p = $escena_n[0]->unidad_produccion;
              $minutos = $escena_n[0]->duracion_real_minutos;
              $segundos = $escena_n[0]->duracion_real_segundos;
            }
        
        $data=array(
          'duracion_real_minutos'=>$minutos,
          'duracion_real_segundos'=>$segundos,
          'estado' =>$estado,
          'fecha_produccion' => $fecha_p,
          'unidad_produccion' => $unidad_p 
        );
        $this->db->where('id',$datos['id_plan']);
        $this->db->update('escena',$data);   
      }else{ 
        if($datos['tipo']==2){
          $data=array(
              'comienzo_ens'=>date("H:i",strtotime($datos['valor'])),
            );
        }else if ($datos['tipo']==3) {
          $data=array(
              'comienzo_prod'=>$datos['valor'],
            );
        }else if($datos['tipo']==4) {
          $data=array(
              'fin_produccion'=>$datos['valor'],
            );
        }else{
          $data=array(
              'comentarios'=>$datos['valor'],
            );
        }
        $this->db->where('id',$datos['id_plan']);
        $this->db->update('plan_diario_has_escenas_has_unidades',$data);
      }

    }

    public  function insert_unidad_user_dia($datos){
      $data=array(
            'id_plan'=>$datos['id_plan'],
            'id_director'=>$datos['id_director'],
            'id_script'=>$datos['id_script'],
            'fecha'=>$datos['fecha'],
          );
     return $this->db->insert('plan_unidad_dia',$data);
    }

    public  function update_unidad_user_dia($datos){
      $data=array(
            'id_plan'=>$datos['id_plan'],
            'id_director'=>$datos['id_director'],
            'id_script'=>$datos['id_script'],
            'fecha'=>$datos['fecha'],
          );
      $this->db->WHERE('id',$datos['id']);
     return $this->db->update('plan_unidad_dia',$data);
    }

    public function plan_unidad_dia($id_plan){
      $this->db->WHERE('id_plan',$id_plan);
      $query=$this->db->get('plan_unidad_dia');
      if ($query->num_rows>0){
        return $query->result_array();
       } else {
        return false;
       }
    }
   
   public  function update_plan($datos){
      $data=array(
            'llamado'=>$datos['llamado'],
            'lugar'=>$datos['lugar'],
            'wrap_time'=>$datos['wrap_time'],
          );
      $this->db->WHERE('id',$datos['id_plan']);
     return $this->db->update('plan_diario',$data);
    }

    public function update_unidad_director($datos){
      $data=array(
            'id_director'=>$datos['director'],
          );
      $this->db->WHERE('id',$datos['id_unidad']);
     return $this->db->update('unidad',$data);
    }

    public function update_unidad_script($datos){
      $data=array(
            'id_script'=>$datos['script'],
          );
      $this->db->WHERE('id',$datos['id_unidad']);
     return $this->db->update('unidad',$data);
    }
  public function unidad_dia($id_plan,$fecha){
      $this->db->WHERE('id_plan',$id_plan);
      $this->db->WHERE('fecha',$fecha);
      $query=$this->db->get('plan_unidad_dia');
      if ($query->num_rows>0){
        return $query->result();
       } else {
        return false;
       }
    }

 public function insert_coment_user($id_user,$comentario,$id_plan,$fecha){
   $data=array(
    'id_user'=>$id_user,
    'id_plan'=>$id_plan,
    'comentario'=>$comentario,
    'fecha'=>$fecha,
    );
  return $this->db->insert('plan_diario_has_comentario_user',$data);
 }

 public function insert_coment_escena($id_user,$comentario,$id_escena,$fecha){
   $data=array(
      'id_escena'=>$id_escena,
      'id_user'=>$id_user,
      'fecha'=>$fecha,
      'comentario'=>$comentario,
    );
  return $this->db->insert('escena_comentario',$data);
 }

 

  public function comentarios_user($id_plan){
    $query=$this->db->query('SELECT h.id AS id_comentario,h.*,u.* FROM plan_diario_has_comentario_user h, user u
    WHERE id_plan='.$id_plan.' and u.id=h.id_user;');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }
 
 public function eliminar_comentario($id_comentario){
     $this->db->WHERE('id',$id_comentario);
     return $this->db->delete('plan_diario_has_comentario_user');
    }
 
 public function cruces_elementos($id,$fecha, $sql){
    $query=$this->db->query('SELECT plan_diario.id_unidad, plan_diario_has_escenas_has_unidades.orden as orden,ele1.nombre, categoria_elemento.tipo '.$sql.' , rol_actores_elementos.rol AS rol_elemento, escenas_has_elementos.* 
            FROM escenas_has_elementos
            LEFT OUTER JOIN escena ON escenas_has_elementos.id_escena = escena.id
            LEFT OUTER JOIN plan_diario_has_escenas_has_unidades 
            ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
            INNER JOIN elemento ele1 ON ele1.id = escenas_has_elementos.id_elemento
            INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id  
            INNER JOIN unidad ON unidad.id=plan_diario.id_unidad 
            INNER JOIN categoria_elemento ON categoria_elemento.id=ele1.id_tipo_elemento
            LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = ele1.rol
            WHERE unidad.id_produccion='.$id.'
            AND plan_diario.fecha_inicio="'.$fecha.'"
            AND escena.estado != 1
            GROUP BY ele1.id
            ORDER BY ele1.id asc;');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
 }

 public function completar_plan($id,$estado){
    if($estado==6){
      $this->db->query("UPDATE plan_diario SET plan_diario.cantidad_reapertura = plan_diario.cantidad_reapertura + 1 where id = ".$id.";");
    }
      $data=array(
          'estado'=>$estado
      );
      $this->db->where('id',$id);
      return $this->db->update('plan_diario',$data);
   }  
 public function actualizar_minutos_reales($idproduccion,$minutos){
  $data = array(
    'minutos_reales'=>$minutos
  );
  $this->db->WHERE('id',$idproduccion);
  return $this->db->update('produccion',$data);
 }

 public function log_plan_diario($idplan,$idusuario){
    $data=array(
      'id_usuario'=>$idusuario,
      'id_plan_diario'=>$idplan,
      'fecha'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('log_plan_diario',$data);
 }

 public function ultima_edicion($idplan){
    $query = $this->db->query("SELECT log_plan_diario.*, user.*, rol_otros.descripcion AS description1, tipo_usuario.descripcion AS description2
          FROM log_plan_diario
          INNER JOIN user ON user.id = log_plan_diario.id_usuario
          LEFT OUTER JOIN usuario_has_produccion ON usuario_has_produccion.id_usuario = log_plan_diario.id_usuario
          LEFT OUTER JOIN rol_otros ON rol_otros.id = usuario_has_produccion.id_rol
          LEFT OUTER JOIN tipo_usuario ON tipo_usuario.id = user.id_tipoUsuario 
          WHERE log_plan_diario.id = (SELECT MAX(log_plan_diario.id) FROM log_plan_diario WHERE id_plan_diario =  ".$idplan.");");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
 }

 public function eliminar_escena_plan($idplan,$idescena){
  $this->db->where('id_plan_diario',$idplan);
  $this->db->where('id_escena',$idescena);
  $this->db->delete('plan_diario_has_escenas_has_unidades');
 }

 public function buscar_escena_plan_capitulo($idcapitulo,$idplan){
  $query = $this->db->query("SELECT escena.id AS id_escena, escena.*,  
    (SELECT group_concat(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'), '_', unidad.numero) FROM plan_diario_has_escenas_has_unidades
      INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
      INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
      WHERE (plan_diario.estado != 1 AND plan_diario.estado != 5) AND id_escena = escena.id) AS planes_abiertos,
  (SELECT id_escena FROM plan_diario_has_escenas_has_unidades
  INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id  
  WHERE escena.id = id_escena AND id_plan_diario = ".$idplan." ) AS id_plan_diario FROM escena
  WHERE escena.id_capitulo = ".$idcapitulo." AND escena.estado != 3; ");
  if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
 }

 public function liberar_escenas($idplan){
  $query = $this->db->query("UPDATE escena SET escena.estado = (escena.estado+1) 
    WHERE escena.id IN (SELECT Id_escena from plan_diario_has_escenas_has_unidades WHERE escena.estado%2=0 
    AND escena.estado!=2 AND escena.estado!=12 AND escena.estado!=14
    AND plan_diario_has_escenas_has_unidades.id_plan_diario = ".$idplan." );");
  $query = $this->db->query("UPDATE escena SET escena.estado = 1
    WHERE escena.id IN (SELECT Id_escena from plan_diario_has_escenas_has_unidades WHERE escena.estado%2=0 
    AND escena.estado=12
    AND plan_diario_has_escenas_has_unidades.id_plan_diario = ".$idplan." );");
  $query = $this->db->query("UPDATE escena SET escena.estado = 2
    WHERE escena.id IN (SELECT Id_escena from plan_diario_has_escenas_has_unidades WHERE escena.estado%2=0 
    AND escena.estado=14
    AND plan_diario_has_escenas_has_unidades.id_plan_diario = ".$idplan." );");
 }

 public function buscar_escenas_retoma($idplan){
  $query = $this->db->query("SELECT escena.* FROM escena
                        INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_escena = escena.id
                        INNER JOIN retomas_escena ON retomas_escena.id_escena = escena.id
                        WHERE pl.id_plan_diario = ".$idplan." AND retomas_escena.estado != 2;");
  if ($query->num_rows>0) {
    return $query->result();
  }else{
    return false;
  }
 }

 public function escenas_id_plan($idplan){
    $query = $this->db->query("SELECT escena.* FROM escena
                              INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_escena = escena.id 
                              WHERE escena.id NOT IN
                              (SELECT pm.id_escena 
                              FROM plan_diario_has_escenas_has_unidades pm
                              INNER JOIN plan_diario ON plan_diario.id = pm.id_plan_diario 
                              WHERE pm.id_plan_diario != ".$idplan." 
                              AND (plan_diario.estado = 5 OR plan_diario.estado = 1)) 
                              AND escena.estado != 2 AND escena.estado != 3 AND pl.id_plan_diario = ".$idplan.";");
  if ($query->num_rows>0) {
    return $query->result();
  }else{
    return false;
  }
 }

 public function contar_escenas_plan($idplan){
  $query = $this->db->query("SELECT COUNT(id) AS cantidad FROM plan_diario_has_escenas_has_unidades pl
                        WHERE pl.id_plan_diario = ".$idplan." ;");
  if ($query->num_rows>0) {
    return $query->result();
  }else{
    return false;
  }
 }

 public function escenas_a_retoma($idplan){
    $query = $this->db->query("SELECT escena.* FROM escena 
                                INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                WHERE plan_diario.id = ".$idplan." AND escena.fecha_produccion != plan_diario.fecha_inicio AND escena.estado != 3 AND escena.unidad_produccion!=null AND escena.unidad_produccion!=0;");
  if ($query->num_rows>0) {
    return $query->result();
  }else{
    return false;
  }
 }

 public function desproducir_escena($idescena,$estado,$fecha,$tiempo_real_minutos,$tiempo_real_segudos,$unidad){
  $data= array(
    'duracion_real_minutos'=>$tiempo_real_minutos,
    'duracion_real_segundos'=>$tiempo_real_segudos,
    'estado' => $estado, 
    'unidad_produccion'=>'',
    'fecha_produccion'=>$fecha,
    'unidad_produccion'=>$unidad
  );
  $this->db->where('id',$idescena);
  $this->db->update('escena',$data);
 }

 public function eliminar_timepos_escena($idescena,$idplan){
  $data= array(
    'comienzo_ens'=>'',
    'comienzo_prod'=>'',
    'fin_produccion'=>''
  );
  $this->db->where('id_plan_diario',$idplan);
  $this->db->where('id_escena',$idescena);
  $this->db->update('plan_diario_has_escenas_has_unidades',$data);
 }


public function buscar_planes_escena($idescena,$idplan){
  $query = $this->db->query("SELECT pl.*,pd.*, pd.id AS id_plan_diario, unidad.numero AS numero_unidad, pd.estado AS estado_plan 
                            FROM plan_diario pd
                            INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = pd.id
                            INNER JOIN escena ON pl.id_escena = escena.id
                            INNER JOIN produccion_has_capitulos pc ON pc.id = escena.id_capitulo
                            INNER JOIN unidad ON unidad.id = pd.id_unidad
                            WHERE pl.id_escena = ".$idescena." AND pd.fecha_inicio <= '".date("Y-m-d")."' AND pd.estado != 5 AND pd.id !=".$idplan);
  if ($query->num_rows>0) {
    return $query->result();
  }else{
    return false;
  } 
}

public function escenas_cruce($idpersonajes,$idelemento, $fecha){
  $query= $this->db->query("SELECT DISTINCT(escena.id) AS id_escena, pe.orden, escena.duracion_estimada_minutos, escena.duracion_estimada_segundos, escena.numero_escena, pc.numero AS numero_libreto, locacion.nombre AS locacion_nombre, se.nombre AS set_nombre, unidad.numero AS unidad_numero,
          (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
          INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento
          WHERE id_escena = escena.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 1 OR rol = 3) ORDER BY ele.nombre) as personajes_principales,

          (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
          INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento
          WHERE id_escena = escena.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 2 OR rol = 4) ORDER BY ele.nombre) as personajes_secundarios,

          (SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
          INNER JOIN elemento ele ON ele.id = escenas_has_elementos.id_elemento
          WHERE id_escena = escena.id AND ele.id_tipo_elemento != ".$idpersonajes." ORDER BY ele.nombre) as elementos 
          FROM escena 
          INNER JOIN produccion_has_capitulos pc ON pc.id = escena.id_capitulo
          INNER JOIN plan_diario_has_escenas_has_unidades pe ON escena.id = pe.id_escena
          INNER JOIN plan_diario pd ON pd.id = pe.id_plan_diario
          INNER JOIN locacion  ON locacion.id = escena.id_locacion
          INNER JOIN sets se ON se.id = escena.id_set
          INNER JOIN unidad ON unidad.id = pd.id_unidad
          INNER JOIN escenas_has_elementos ee ON ee.id_escena = escena.id
          WHERE pd.fecha_inicio = '".$fecha."' AND ee.id_elemento = ".$idelemento." ORDER BY unidad.numero;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    } 
}
  public function buscar_columnas($data){
    $this->db->where('id_usuario',$data['id_usuario']);
    $this->db->where('tipo',$data['tipo']);
    $query = $this->db->get('columnas_usuario');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function actualizar_columnas($data){
    $this->db->where('id_usuario',$data['id_usuario']);
    $this->db->where('tipo',$data['tipo']);
    $this->db->update('columnas_usuario',$data);
  }

  public function agregar_columnas($data){
    $this->db->insert('columnas_usuario',$data);
  }

  public function ultimo_plan($iduser, $idproduccion){
    $query = $this->db->query("SELECT plan_diario.*, ultimo_plan.id AS id_ultimo FROM plan_diario
    INNER JOIN ultimo_plan ON ultimo_plan.id_plan_diario = plan_diario.id
    INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
    WHERE ultimo_plan.id_usuario = ".$iduser." and unidad.id_produccion = ".$idproduccion);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function insertar_ultimo_plan($iduser,$idplan){
    $data=array(
            'id_usuario'=>$iduser,
            'id_plan_diario'=>$idplan
    );
    return $this->db->insert('ultimo_plan',$data);
  }


  public function actualizar_ultimo_plan($idultimo,$idplan){
    $data=array(
            'id_plan_diario'=>$idplan
    );
    $this->db->where('id',$idultimo);
    $this->db->update('ultimo_plan',$data);
  }

  public function escenas_id_plan_diario($idplan){
    $this->db->where('id_plan_diario',$idplan);
    $query = $this->db->get('plan_diario_has_escenas_has_unidades');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function retomas_escena($idescena, $fecha_inicio, $id_unidad){
    $this->db->where('id_escena',$idescena);
    $this->db->where('fecha_produccion',$fecha_inicio);
    $this->db->where('unidad_produccion',$id_unidad);
    $query=$this->db->get('retomas_escena');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function ultima_retoma($id_escena){
    $query = $this->db->query("SELECT * from retomas_escena
        where id_escena=".$id_escena." order by fecha_produccion desc limit 1;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }


  /*FUNCIONES PARA PDF SEMANAL*/
  public function semana_plan($fecha,$idproduccion){
     $query = $this->db->query("SELECT * FROM semanas_produccion WHERE fecha_inicio_semana = '".$fecha."' AND id_produccion = ".$idproduccion."  LIMIT 1;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  } 

  public function unidades_plan_semanal($fecha1,$fecha2,$sql){
     $query = $this->db->query("SELECT
                                unidad.numero AS numero_unidad, plan_diario.fecha_inicio, plan_diario.id_unidad AS id_unidad,
                                SUM(escena.duracion_estimada_minutos) AS minutos, 
                                SUM(escena.duracion_estimada_segundos) AS segundos,
                                (SELECT SUM(SUBSTRING_INDEX( retomas_escena.tiempo , ':', 1 )) FROM retomas_escena WHERE  retomas_escena.fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = unidad.id) AS minutos_producidos,
                                (SELECT SUM(SUBSTRING_INDEX( retomas_escena.tiempo , ':', -1 )) FROM retomas_escena WHERE  retomas_escena.fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = unidad.id) AS segundos_producidos,
                                (SELECT COUNT(retomas_escena.id)  FROM retomas_escena WHERE  retomas_escena.fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = unidad.id) AS escenas_producidas, 
                                (SELECT COUNT(id_escena) 
                                FROM plan_diario_has_escenas_has_unidades p2 
                                INNER JOIN escena  es2 ON p2.id_escena = es2.id 
                                WHERE p2.id_plan_diario = plan_diario.id AND es2.estado !=3) AS cantidad
                                FROM plan_diario
                                LEFT OUTER JOIN unidad ON unidad.id = plan_diario.id_unidad 
                                LEFT OUTER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
                                LEFT OUTER JOIN escena ON escena.id = plan_diario_has_escenas_has_unidades.id_escena
                                WHERE plan_diario.fecha_inicio >= '".$fecha1."' AND plan_diario.fecha_inicio <= '".$fecha2."' AND ".$sql." GROUP BY plan_diario.id order by id_unidad,fecha_inicio;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  } 
  /*FIN FUNCIONES PARA PDF SEMANAL*/

  public function escena_has_plan_by_id($id){
    $query = $this->db->query("SELECT escena.numero_escena, produccion_has_capitulos.numero FROM plan_diario_has_escenas_has_unidades 
                              INNER JOIN escena ON escena.id = plan_diario_has_escenas_has_unidades.id_escena
                              INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                              WHERE plan_diario_has_escenas_has_unidades.id = ".$id.";");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function plan_by_id_comentario($idcomentario){
    $query = $this->db->query("SELECT unidad.numero, unidad.id_produccion, plan_diario.fecha_inicio, plan_diario_has_comentario_user.comentario, plan_diario_has_comentario_user.fecha FROM plan_diario_has_comentario_user
                              INNER JOIN plan_diario On plan_diario.id = plan_diario_has_comentario_user.id_plan
                              INNER JOIN unidad On unidad.id = plan_diario.id_unidad
                              WHERE plan_diario_has_comentario_user.id = ".$idcomentario.";");
    if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
    }


    public function fechas_trabajo_unidades($id_produccion){
        $query = $this->db->query("SELECT plan_diario.* FROM unidad 
        inner join plan_diario on plan_diario.id_unidad=unidad.id
        where unidad.id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
    }


    public function update_plan_diario_mover($datos){
       
       $data=array(
              'fecha_inicio'=>$datos['fecha_inicio'],
              'id_unidad'=>$datos['id_unidad'],
      );
      $this->db->where('id',$datos['id']);
      $this->db->update('plan_diario',$data);
    }


    public function elementosByidplan($id_plan){

        $query = $this->db->query("SELECT elemento.* from plan_diario 
          right join plan_diario_has_escenas_has_unidades on plan_diario_has_escenas_has_unidades.id_plan_diario=plan_diario.id
          right join escenas_has_elementos on escenas_has_elementos.id_escena=plan_diario_has_escenas_has_unidades.id_escena
          inner join elemento on elemento.id=escenas_has_elementos.id_elemento
          INNER JOIN categoria_elemento ON categoria_elemento.id=elemento.id_tipo_elemento
          where plan_diario.id=".$id_plan." and categoria_elemento.tipo='Personaje'  group by elemento.id;");
                if ($query->num_rows>0) {
                  return $query->result_array();
                }else{
                  return false;
                }

    }

    public function fecha_inicioByidelemento($id_elemento){

        $query = $this->db->query("SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio
                FROM escenas_has_elementos
                INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                WHERE escenas_has_elementos.id_elemento =".$id_elemento." ORDER BY plan_diario.fecha_inicio LIMIT 1;");
                if ($query->num_rows>0) {
                  return $query->result();
                }else{
                  return false;
                }

    }

    
    public function solicitudesByidelemento($id_elemento){

        $query = $this->db->query("SELECT * from solicitud_has_elementos where id_elemento=".$id_elemento);
                if ($query->num_rows>0) {
                  return $query->result_array();
                }else{
                  return false;
                }

    }


}
