<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_plan_produccion extends CI_Model {
 


  public function produccion_id($id){
     $query=$this->db->query("SELECT p.id as id_produccion,p.tipo as tipo_produccion,p.*,c.*,t.*,d.* from produccion p, centro_produccion c, tipo_produccion t, dias_grabacion d
            where p.id_centroProduccion=c.id and p.id_tipoProduccion=t.id
            and p.id_dias_grabacion=d.id and p.id=".$id);
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }

  public function update_dias($datos){
      $this->db->where('id',$datos['id_dias_grabacion']);
      $data=array(
        'dias_grabacion'=> $datos['dias_grabacion'],
        'lunes'         => $datos['lunes'],
        'martes'        => $datos['martes'],
        'miercoles'     => $datos['miercoles'],
        'jueves'     => $datos['jueves'],
        'viernes'     => $datos['viernes'],
        'sabado'     => $datos['sabado'],
        'domingo'     => $datos['domingo']);
    return $this->db->update('dias_grabacion',$data);
  }

  public function update_produccion($datos){
    $this->db->where('id',$datos['id']);
    if($datos['imagen_produccion']==null){
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
        'over_time'=>$datos['over_time'],
        'capitulos_proyectados'=>$datos['capitulos_proyectados'],
        'estado'=>1);
    return $this->db->update('produccion',$data);
    } else {
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
        'cap_esce_semana'=>$datos['cap_ese_semana'],
        'min_proy_semana'=>$datos['min_proy_seman'],
        'seg_proy_semana'=>$datos['seg_proy_seman'],
        'imagen_produccion'=>$datos['imagen_produccion'],
        'capitulos_proyectados'=>$datos['capitulos_proyectados'],
        'estado'=>1);
    return $this->db->update('produccion',$data);
    }
    
  }

  public function unidades_id_produccion($id){
      $this->db->where('id_produccion',$id);
      $this->db->order_by('numero',"asc");
      $query=$this->db->get('unidad');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function unidades_id_produccion_2($id){
      $this->db->where('id_produccion',$id);
      $this->db->where('fecha_inicio is not null');
      $this->db->order_by('fecha_inicio',"asc");
      $this->db->order_by('id',"asc");
      $query=$this->db->get('unidad');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function unidades_id_produccion_3($id){
      $this->db->where('id_produccion',$id);
      $this->db->order_by('numero',"asc");
      $query=$this->db->get('unidad');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function unidades_id_produccion_4($id){
      $this->db->where('id_produccion',$id);
      $this->db->where('fecha_inicio is not null');
      $this->db->order_by('id',"asc");
      $query=$this->db->get('unidad');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function udpate_numero_unidades($datos){
     $this->db->where('id',$datos['id']);
     $data=array(
        'id_productor_ejecutivo'=>$datos['id_productor_ejecutivo'],
        'id_productor_general'=>$datos['id_productor_general'],
        'id_productor'=>$datos['id_productor'],
        'numero_unidades'=>$datos['numero_unidades']);
    return $this->db->update('produccion',$data);
  }

  public function delete_unidades($id_produccion,$sql){
     $query=$this->db->query("DELETE from unidad where id_produccion=".$id_produccion." 
      ".$sql);
    return true;
  }

  public function delete_unidad($idunidad){
    $this->db->where('id',$idunidad);
    return $this->db->delete('unidad');
  }
  

  public function rol_otros(){
     $query=$this->db->query("select * from rol_otros");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }

  }

  public function user_has_roles_id($id){
      $query=$this->db->query("SELECT u.id as id_user,u.*, h.*, o.*, o.id as id_rol  
      FROM user u , user_has_rol_otros h, rol_otros o
      WHERE NOT EXISTS (SELECT usuario_has_produccion.id_usuario from usuario_has_produccion 
      where usuario_has_produccion.id_usuario = u.id)
      and u.id=h.id_user and h.id_rol_otros=".$id." and h.id_rol_otros=o.id;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }  

  public function user_has_roles_id_produccion($id,$idproduccion){
      $query=$this->db->query("SELECT u.id as id_user,u.*, h.*, o.*, o.id as id_rol  
      FROM user u , user_has_rol_otros h, rol_otros o
      WHERE NOT EXISTS 
      (SELECT usuario_has_produccion.id_usuario from usuario_has_produccion 
      where usuario_has_produccion.id_usuario = u.id 
      AND id_produccion = ".$idproduccion." and usuario_has_produccion.id_rol=".$id.")
      and u.id=h.id_user and h.id_rol_otros=".$id." and h.id_rol_otros=o.id;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  } 

   public function user_has_produccion($datos){
     $data=array(
        'id_usuario'=>$datos['id_user'],
        'id_produccion'=>$datos['id_produccion'],
        'id_rol'=>$datos['rol'],
        'activo'=>1);
    return $this->db->insert('usuario_has_produccion',$data);
    
   }

   public function usuarios_produccion($id_produccion){
    $query=$this->db->query("SELECT u.id as id_user, u.*,p.*, o.*,o.id as id_rol, p.id as id_user_pro from user u, usuario_has_produccion p, rol_otros o
    where u.id=p.id_usuario and p.id_produccion=".$id_produccion." and p.id_rol=o.id ORDER BY o.descripcion,u.nombre,u.apellido");
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
   }
   
   public function delete_user_has_produccion($datos){
      $this->db->where('id_usuario',$datos['id_usuario']);
      $this->db->where('id_produccion',$datos['id_produccion']);
      return $this->db->delete('usuario_has_produccion');
   }  
  
  public function update_indicadores_produccion($datos){
    $this->db->where('id',$datos['id_produccion']);
     $data=array(
        'produccion_interior'=>$datos['produccion_interior'],
        'produccion_exterior'=>$datos['produccion_exterior'],
        'locacion'=>$datos['locacion'],
        'estudio'=>$datos['estudio'],
        'dia'=>$datos['dia'],
        'noche'=>$datos['noche'],
        'numero_locaciones'=>$datos['numero_locaciones'],
        'numero_set'=>$datos['numero_set'],
        'numero_protagonistas'=>$datos['numero_protagonistas'],
        'numero_repartos'=>$datos['numero_repartos'],
        'numero_figurantes'=>$datos['numero_figurantes'],
        'numero_extras'=>$datos['numero_extras'],
        'numero_vehiculos'=>$datos['numero_vehiculos'],
        'presupuesto_principales'=>$datos['presupuesto_principales'],
        'presupuesto_secundarios'=>$datos['presupuesto_secundarios'],
        'presupuesto_figurante'=>$datos['presupuesto_figurante'],
        'escenas_libretos'=>$datos['escenas_libretos'],
        'evento_pequeno'=>$datos['evento_pequeno'],
        'evento_mediano'=>$datos['evento_mediano'],
        'evento_grande'=>$datos['evento_grande'],
        'locaciones_nuevas'=>$datos['locaciones_nuevas'],
        'protagonistas_produccion'=>$datos['protagonistas_produccion'],
        'monto_figurante_extra'=>$datos['monto_figurante_extra'],
        'monto_figurante_extra_dolar'=>$datos['monto_figurante_extra_dolar'],
        'paginasPorLibretos'=>$datos['paginasPorLibretos'],
      );
    return $this->db->update('produccion',$data);
  }

  public function semanas_trabajo($id){
     $this->db->where('id_produccion',$id);
     $query=$this->db->get('semanas_produccion');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function delete_semanas($id_produccion, $id_escena){
    $this->db->where('id_produccion',$id_produccion);
    $this->db->where('id',$id_escena);
    return $this->db->delete('semanas_produccion');
  }

  public function update_semana($datos){
    $this->db->where('id',$datos['id']);
    if($datos['tipo']==1){
      $data=array(
        'dias_trabajo'=>$datos['valor']);
    }else if ($datos['tipo']==2){
       $data=array(
        'fecha_inicio_semana'=>$datos['valor']);
    }else if($datos['tipo']==3){
      $data=array(
        'fecha_fin_semana'=>$datos['valor']);
    }else if($datos['tipo']==4){
       $data=array(
        'capitulos_programados'=>$datos['valor']);
    }else if($datos['tipo']==5){
       $data=array(
          'minutos_proyectados'=>$datos['valor'],
          'segundos_proyectados'=>$datos['valor2']);
    }else if($datos['tipo']==6){
      $data=array(
          'comentario'=>$datos['valor']);
    }
    return $this->db->update('semanas_produccion',$data);
  }

  public function update_semana2($datos){
    $this->db->where('id_produccion',$datos['id_produccion']);
    if($datos['tipo']==4){
      $data=array(
        'capitulos_programados'=>$datos['valor']);
    }else if ($datos['tipo']==5){
       $data=array(
        'minutos_proyectados'=>$datos['valor'],
        'segundos_proyectados'=>$datos['valor2']);
    }
    return $this->db->update('semanas_produccion',$data);
  }

  public function ver_comentario($id){
     $this->db->where('id_semana',$id);
      $query=$this->db->get('semana_comentarios');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
     
  }

  public function update_fecha_preProduccion($datos){
     $this->db->where('id',$datos['id']);
      $data=array(
        'inicio_preProduccion'=>$datos['inicio_preProduccion']);
    return $this->db->update('produccion',$data);
  }

  public function dias_grabacion_semana($datos){
    $this->db->where('id',$datos['id']);
     $data=array(
        'dias_trabajo'=>$datos['dias_trabajo'],
        'lunes'=>$datos['lunes'],
        'martes'=>$datos['martes'],
        'miercoles'=>$datos['miercoles'],
        'jueves'=>$datos['jueves'],
        'viernes'=>$datos['viernes'],
        'sabado'=>$datos['sabado'],
        'domingo'=>$datos['domingo'],
      );
    return $this->db->update('semanas_produccion',$data);
  }

  public function total_semanas($id){
     $query=$this->db->query("SELECT count(*) as total FROM semanas_produccion 
        where id_produccion=".$id);
    if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }

  public function update_values_semana($campo_produccion, $campo_semana, $idproduccion, $valor){
    $query=$this->db->query("UPDATE semanas_produccion SET ".$campo_semana."= ". $valor." WHERE  id_produccion = ". $idproduccion."
    AND ".$campo_semana." = (SELECT ".$campo_produccion." FROM produccion WHERE id =". $idproduccion." )  AND semanas_produccion.fecha_fin_semana >= '".date("Y-m-d")."';");
  }

  public function semanas_actualizar($id){
     $fecha =  "'".date("Y-m-d")."'";
     $this->db->where('id_produccion',$id);
     $this->db->where('fecha_inicio_semana >= ',$fecha);
     $query=$this->db->get('semanas_produccion');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }


  public function unidades_plan($id_produccion){
    $query=$this->db->query("SELECT u.* FROM unidad u
        inner join plan_diario p on p.id_unidad=u.id
        where u.id_produccion=".$id_produccion);
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function plan_diario_id_unida($id_unidad){
      $query=$this->db->query("SELECT * from plan_diario
        where id_unidad=".$id_unidad." order by fecha_inicio asc limit 1");
    if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
     
  }

  public function id_libreto_maximo($id_produccion){
      $query=$this->db->query(" SELECT max(id) as id_mayor  FROM produccion_has_capitulos
      where numero_escenas<>'' and id_produccion=".$id_produccion);
     $row = $query->row(); 
     if ($row->id_mayor!=null) {
           $query2=$this->db->query("SELECT count(*) as total  FROM produccion_has_capitulos
          where id_produccion=".$id_produccion." and id<=".$row->id_mayor);
          if ($query->num_rows>0) {
             return $query2->result();
          }else {
             return false;
          }
      }else {
        return false;
      }
     
  }

   public function actualizar_fecha_aire($idproduccion){
  $query=$this->db->query("UPDATE produccion SET fecha_aire = (SELECT MIN(fecha_aire) FROM produccion_has_capitulos 
WHERE id_produccion = ".$idproduccion.") WHERE id = ".$idproduccion.";");

 }

 public function buscar_usuarios_palabra($palabra,$sql){
    $query=$this->db->query("SELECT CONCAT(user.nombre,' ',user.apellido) AS nombre, user.id AS id_user, rol_otros.descripcion, rol_otros.id AS id_rol  FROM user 
                            INNER JOIN user_has_rol_otros on user_has_rol_otros.id_user = user.id
                            INNER JOIN rol_otros on rol_otros.id = user_has_rol_otros.id_rol_otros
                            WHERE user.apellido 
                            LIKE '%".$palabra."%' ".$sql);
    $row = $query->row(); 
    if ($query->num_rows>0) {
       return $query->result();
    }else {
       return false;
    }
 }
 public function usurio_rol_produccion($datos){
  $query=$this->db->query("SELECT * FROM usuario_has_produccion 
                           WHERE id_produccion = ".$datos['id_produccion']." 
                           AND id_usuario = ".$datos['id_user']." 
                           AND id_rol = ".$datos['rol']." ;");
    if ($query->num_rows>0) {
       return true;
    }else {
       return false;
    }
 }

 public function actualizar_usuarios_planes($data_plan){
    $this->db->where('id_unidad',$data_plan['id_unidad']);
    $this->db->where('fecha_inicio >',date("Y-m-d"));
    $this->db->update('plan_diario',$data_plan);
 }

 public function productores(){

 }

 public function productores_id_produccion($idproduccion){
  $query=$this->db->query("SELECT u.id as id_user,u.*, 8 as id_rol, 'productor' AS descripcion   
      FROM user u
      WHERE NOT EXISTS (SELECT usuario_has_produccion.id_usuario from usuario_has_produccion 
      where usuario_has_produccion.id_usuario = u.id AND id_produccion = ".$idproduccion.")
      AND NOT EXISTS (SELECT produccion.id_productor_general from produccion 
      where produccion.id = ".$idproduccion.")
      AND NOT EXISTS (SELECT produccion.id_productor_ejecutivo from produccion 
      where produccion.id = ".$idproduccion.")
      AND NOT EXISTS (SELECT produccion.id_productor from produccion 
      where produccion.id = ".$idproduccion.")
      AND NOT EXISTS (SELECT * FROM usuario_has_produccion
      where id_usuario=u.id and id_rol=".$idproduccion.")
      AND u.id_tipoUsuario = 4;");
  if ($query->num_rows>0) {
    return $query->result_array();
  }else {
    return false;
  }
 }

  public function unidad_inicio($id_produccion){
  $query=$this->db->query("SELECT * FROM unidad
    where id_produccion=".$id_produccion." order by fecha_inicio ASC LIMIT 1;");
  if ($query->num_rows>0) {
    return $query->result();
  }else {
    return false;
  }
 }

 public function capitulos_entregados($id_produccion,$fecha_inicio,$fecha_fin){
  $query=$this->db->query("SELECT count(*) total from capitulos WHERE id_estado=15 and id_produccion=".$id_produccion."
    and fecha_entregada>='".$fecha_inicio."' and fecha_entregada<='".$fecha_fin."';");
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
 }

 public function capitulos_entregados_totales($id_produccion,$fecha_fin){
  $query=$this->db->query("SELECT count(*) total from capitulos WHERE id_estado=15 and id_produccion=".$id_produccion."
     and fecha_entregada<='".$fecha_fin."';");
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
 } 

 public function total_semanas_produccion_fecha($id_produccion,$fecha_fin){
  $query=$this->db->query("SELECT count(*) total FROM semanas_produccion
      where id_produccion=".$id_produccion." and fecha_fin_semana<='".$fecha_fin."';");
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
 }  

 


}
 