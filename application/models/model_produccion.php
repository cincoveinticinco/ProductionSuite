<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_produccion extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function centro_produccion(){
      $query=$this->db->get('centro_produccion');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function tipo_produccion(){
      $query=$this->db->get('tipo_produccion');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }
   public function tipo_usuario(){
      $query=$this->db->query("SELECT * from user where id_tipoUsuario = 4 ORDER BY nombre");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

  public function tipo_usuario_otros($tipo,$id_otros){
      $query=$this->db->query("SELECT u.* from user u, tipo_usuario t, rol_otros r, user_has_rol_otros h
                                WHERE u.id_tipoUsuario=".$tipo." and  h.id_rol_otros=".$id_otros." and t.id=u.id_tipoUsuario and h.id_rol_otros=r.id GROUP BY u.id ORDER BY u.nombre");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

    public function tipo_usuario_otros_2($tipo,$id_otros){
      $query=$this->db->query("SELECT u.* from user u, tipo_usuario t, rol_otros r, user_has_rol_otros h, usuario_has_produccion uh, produccion p
                                WHERE u.id_tipoUsuario=".$tipo." and  h.id_rol_otros=".$id_otros." and t.id=u.id_tipoUsuario and h.id_rol_otros=r.id and uh.id_usuario = u.id AND uh.id_rol = ".$id_otros."  GROUP BY u.id ORDER BY u.nombre");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

  public function get_usuarios($idrol,$idproduccion){
    $query= $this->db->query("SELECT DISTINCT(user.id),user.* 
                              FROM user 
                              INNER JOIN tipo_usuario t ON t.id = user.id_tipoUsuario
                              INNER JOIN user_has_rol_otros h ON h.id_user = user.id
                              INNER JOIN usuario_has_produccion uh ON uh.id_usuario = user.id 
                              INNER JOIN produccion ON produccion.id = uh.id_produccion   
                              WHERE uh.id_rol = ".$idrol." AND id_produccion = ".$idproduccion.";");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else
    {
      return false;
    }
  } 

  public function insert_dias($datos){
      $data=array(
        'dias_grabacion'=> $datos['dias_grabacion'],
        'lunes'         => $datos['lunes'],
        'martes'        => $datos['martes'],
        'miercoles'     => $datos['miercoles'],
        'jueves'     => $datos['jueves'],
        'viernes'     => $datos['viernes'],
        'sabado'     => $datos['sabado'],
        'domingo'     => $datos['domingo']);
    return $this->db->insert('dias_grabacion',$data);
  }

  public function insert_produccion($datos){
    $data=array(
        'nombre_produccion'=>$datos['nombre_produccion'],
        'id_centroProduccion'=>$datos['id_centroProduccion'],
        'id_tipoProduccion'=>$datos['id_tipoProduccion'],
        'inicio_PreProduccion'=>$datos['inicio_PreProduccion'],
        'inicio_grabacion'=>$datos['inicio_grabacion'],
        'fecha_aire'=>$datos['fecha_aire'],
        'fin_grabacion'=>$datos['fin_grabacion'],
        'numero_capitulo'=>$datos['numero_capitulo'],
        'minuto_capitulo'=>$datos['minuto_capitulo'],
        'segundos_capitulo'=>$datos['segundos_capitulo'],
        'cap_esce_semana'=>$datos['cap_ese_semana'],
        'min_proy_semana'=>$datos['min_proy_seman'],
        'seg_proy_semana'=>$datos['seg_proy_seman'],
        'id_dias_grabacion'=>$datos['id_dias_grabacion'],
        'id_productor_ejecutivo'=>$datos['id_productor_ejecutivo'],
        'id_productor_general'=>$datos['id_productor_general'],
        'id_productor'=>$datos['id_productor'],
        'numero_unidades'=>$datos['numero_unidades'],
        'produccion_interior'=>$datos['produccion_interior'],
        'produccion_exterior'=>$datos['produccion_exterior'],
        'numero_locaciones'=>$datos['numero_locaciones'],
        'locacion'=>$datos['locacion'],
        'estudio'=>$datos['estudio'],
        'over_time'=>$datos['over_time'],
        'dia'=>$datos['dia'],
        'noche'=>$datos['noche'],
        'numero_set'=>$datos['numero_set'],
        'presupuesto_principales'=>$datos['presupuesto_principales'],
        'presupuesto_secundarios'=>$datos['presupuesto_secundarios'],
        'presupuesto_figurante'=>$datos['presupuesto_figurante'],
        'escenas_libretos'=>$datos['escenas_libretos'],
        'evento_pequeno'=>$datos['evento_pequeno'],
        'evento_mediano'=>$datos['evento_mediano'],
        'evento_grande'=>$datos['evento_grande'],
        'protagonistas_produccion'=>$datos['protagonistas_produccion'],
        'monto_figurante_extra'=>$datos['monto_figurante_extra'],
        'monto_figurante_extra_dolar'=>$datos['monto_figurante_extra_dolar'],
        'paginasPorLibretos'=>$datos['paginasPorLibretos'],
        'estado'=>1);
    return $this->db->insert('produccion',$data);
  }

  public function insert_unidad($datos){
    $data=array(
        'id_director'=>$datos['id_director'],
        'id_script'=>$datos['id_script'],
        'fecha_inicio'=>$datos['fecha_inicio'],
        'id_produccion'=>$datos['id_produccion'],
        'numero'=>$datos['numero']);
    return $this->db->insert('unidad',$data);
  }

  public function update_unidad($datos){
    $data=array(
        'id_director'=>$datos['id_director'],
        'id_script'=>$datos['id_script'],
        'fecha_inicio'=>$datos['fecha_inicio'],
        'id_produccion'=>$datos['id_produccion']);
    $this->db->where('id',$datos['id_unida']);
    return $this->db->update('unidad',$data);

  }

  public function user_id($id){
     $this->db->where('id',$id);
      $query=$this->db->get('user');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function producciones_user($id,$sql=""){
    $query=$this->db->query("SELECT *, p.id AS id_produccion FROM produccion p
      left outer JOIN usuario_has_produccion up ON up.id_produccion = p.id 
      left outer JOIN unidad d ON d.id_produccion = p.id 
      WHERE ((d.id_director=".$id." or d.id_script=".$id.") and p.id=d.id_produccion) 
      OR p.id_productor_ejecutivo=".$id."
      OR p.id_productor_general=".$id."
      OR p.id_productor=".$id." 
      OR (up.id_usuario=".$id.") ".$sql." group by p.id ORDER BY p.nombre_produccion;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function producciones_all($sql=""){
    $query=$this->db->query("SELECT *, p.id AS id_produccion  from unidad d, produccion p ".$sql." GROUP BY p.id ORDER BY p.nombre_produccion");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

 public function centro_produccion_id($id){
      $this->db->where('id',$id);
      $query=$this->db->get('centro_produccion');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
 }
 public function tipo_produccion_id($id){
   $this->db->where('id',$id);
      $query=$this->db->get('tipo_produccion');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
 }

 public function semanas_produccion($datos){
    $data=array(
        'dias_trabajo'=>$datos['dias_trabajo'],
        'fecha_inicio_semana'=>$datos['fecha_inicio_semana'],
        'fecha_fin_semana'=>$datos['fecha_fin_semana'],
        'capitulos_programados'=>$datos['capitulos_programados'],
        'minutos_proyectados'=>$datos['minutos_proyectados'],
        'lunes'=>$datos['lunes'],
        'martes'=>$datos['martes'],
        'miercoles'=>$datos['miercoles'],
        'jueves'=>$datos['jueves'],
        'viernes'=>$datos['viernes'],
        'sabado'=>$datos['sabado'],
        'domingo'=>$datos['domingo'],
        'id_produccion'=>$datos['id_produccion'],);
    return $this->db->insert('semanas_produccion',$data);
 }

 public function semanas_produccion_update($datos,$dias){
    $this->db->where('id_produccion',$datos['id_produccion']);
    $this->db->where('fecha_fin_semana >=', date("Y-m-d") );
    $this->db->where('dias_trabajo', $dias);
    $data=array(
        'dias_trabajo'=>$datos['dias_trabajo'],
        'lunes'=>$datos['lunes'],
        'martes'=>$datos['martes'],
        'miercoles'=>$datos['miercoles'],
        'jueves'=>$datos['jueves'],
        'viernes'=>$datos['viernes'],
        'sabado'=>$datos['sabado'],
        'domingo'=>$datos['domingo']);
    return $this->db->update('semanas_produccion',$data);

 }

 public function buscar_nombre($nombre){
  $query=$this->db->query("SELECT COUNT(id) AS cantidad from produccion p WHERE lower(nombre_produccion) = lower('".$nombre."');");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

 public function buscar_nombre_produccion($nombre, $idproduccion){
  $query=$this->db->query("SELECT COUNT(id) AS cantidad from produccion p WHERE lower(nombre_produccion) = lower('".$nombre."') AND id != ".$idproduccion." ;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }
public function estado_produccion($id){
   $this->db->where('id',$id);
      $query=$this->db->get('estado_produccion');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
 }

 public function cambiar_estado($id){
      $data=array(
          'estado'=>2,
      );
      $this->db->where('id',$id);
    return  $this->db->update('produccion',$data);
 }

 public function suma_capitulos_semana($idproduccion, $fecha_inicio, $fecha_fin, $max){
  $query = $this->db->query("SELECT capitulos_programados AS capitulos_programados FROM  semanas_produccion 
    WHERE fecha_fin_semana BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND id_produccion = ".$idproduccion." AND capitulos_programados != (SELECT cap_esce_semana from produccion where id = ".$idproduccion.") AND id <= ".$max.";");
  return $query->result();
 }

public function suma_minutos_semana($idproduccion, $fecha_inicio, $fecha_fin, $max){
  $query = $this->db->query("SELECT minutos_proyectados  AS minutos_proyectados, segundos_proyectados  AS segundos_proyectados FROM  semanas_produccion 
    WHERE fecha_fin_semana BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND id_produccion = ".$idproduccion." AND minutos_proyectados != (SELECT min_proy_semana from produccion where id = ".$idproduccion.") AND id <= ".$max.";");
  return $query->result();
 }

public function dias_trabajo_semana($idproduccion, $fecha_inicio, $fecha_fin, $max){
  $query = $this->db->query("SELECT dias_trabajo AS dias_trabajo FROM  semanas_produccion 
  WHERE fecha_fin_semana BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND id_produccion = ".$idproduccion." AND dias_trabajo != (SELECT dias_grabacion from produccion INNER JOIN dias_grabacion on produccion.id_dias_grabacion = dias_grabacion.id where produccion.id = ".$idproduccion.") AND id <= ".$max.";");
  return $query->result();
}

 public function estados_libretos($idproduccion,$estado){
 $query=$this->db->query("SELECT * FROM produccion_has_capitulos
        where id_produccion=".$idproduccion." AND estado!=".$estado." ORDER BY fecha_entregado ASC limit 1");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

  public function fecha_unidad_plan($idproduccion){
    $query=$this->db->query("SELECT min(fecha_inicio) as fecha_inicio  FROM unidad
      where id_produccion=".$idproduccion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

 
  public function update_inicio_grabacion($fecha,$id_produccion){
      $data=array('inicio_grabacion'=>$fecha);
      $this->db->where('id',$id_produccion);
    return  $this->db->update('produccion',$data);
 }

 public function buscar_planes_unidad($id){
   $this->db->where('id_unidad',$id);
   $query=$this->db->get('plan_diario');
   if ($query->num_rows>0){
    return $query->result();
   }else {
    return false;
   }
 }

 public function eliminar_unidad($id){
  $this->db->where('id',$id);
  $this->db->delete('unidad');
 }

 public function actualizar_unidades($idproduccion){
  $this->db->query("UPDATE produccion SET  numero_unidades= (SELECT COUNT(id) from unidad where id_produccion=".$idproduccion.") WHERE id = ".$idproduccion." ;");
 }

 public function buscar_usuarios_apellido($apellido,$idusuario){
  $query=$this->db->query("SELECT * FROM user WHERE apellido LIKE '%".$apellido."%' AND user.id !=".$idusuario.";");
  if ($query->num_rows>0){
    return $query->result_array();
  }else {
    return false;
  }
 }

public function roles_usuario_produccion($iduser,$idproduccion){
  $this->db->where('id_usuario',$iduser);
  $this->db->where('id_produccion',$idproduccion);
  $this->db->where('activo',1);
  $query=$this->db->get('usuario_has_produccion');
  if ($query->num_rows>0){
    return $query->result_array();
  }else {
    return false;
  }
}

public function buscar_usuarios_produccion($idproduccion,$idusuario){
  $query= $this->db->query("SELECT user.* FROM user 
                            LEFT OUTER JOIN usuario_has_produccion up ON up.id_usuario = user.id
                            LEFT OUTER JOIN produccion ON produccion.id = up.id_produccion
                            WHERE produccion.id_productor = user.id OR 
                            produccion.id_productor_ejecutivo = user.id OR 
                            produccion.id_productor_general = user.id OR produccion.id = ".$idproduccion." AND user.id !=".$idusuario." 
                            ORDER BY user.nombre,user.apellido;");
  if ($query->num_rows>0){
    return $query->result_array();
  }else {
    return false;
  }
}

public function buscar_usuarios_produccion_estado($idproduccion,$idusuario,$id_estado){
  $query= $this->db->query("SELECT user.* FROM user 
                            LEFT OUTER JOIN usuario_has_produccion up ON up.id_usuario = user.id
                            LEFT OUTER JOIN produccion ON produccion.id = up.id_produccion
                            OR (produccion.id_productor = user.id OR 
                            produccion.id_productor_ejecutivo = user.id OR 
                            produccion.id_productor_general = user.id)
                            WHERE  produccion.id = ".$idproduccion." AND user.id !=".$idusuario." 
                            AND user.estado=".$id_estado." ORDER BY user.nombre,user.apellido;");
  if ($query->num_rows>0){
    return $query->result_array();
  }else {
    return false;
  }
}


 
}