<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_elementos extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function categorias_elementos_default($data){
     return $this->db->insert('categoria_elemento',$data);
  }

  public function categorias_elementos($id){
  	$this->db->where('id_produccion',$id);
  	$query=$this->db->get('categoria_elemento');
  	if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function update_elemento($datos){
    $this->db->where('id',$datos['id_categoria']);
    $data=array(
      'tipo'=>$datos['tipo'],
      'descripcion'=>$datos['descripcion'],
    );
    return $this->db->update('categoria_elemento',$data);
  }

  public function eliminar_categoria($id_categoria){
     $this->db->where('id',$id_categoria);
     $this->db->delete('categoria_elemento');
  }

  public function insert_elemento($datos){
    $data=array(
      'tipo'=>$datos['tipo'],
      'id_produccion'=>$datos['id_produccion'],
      //'descripcion'=>$datos['descripcion'],
    );
    return $this->db->insert('categoria_elemento',$data);
  }

  public function categoria_elemento($id){
   $this->db->where('id',$id);
    $query=$this->db->get('categoria_elemento');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     } 
  }

  public function elemento($datos){
    $data=array(
      'nombre'=>$datos['nombre'],
      'descripcion'=>$datos['descripcion'],
      'rol'=>$datos['rol'],
      'id_tipo_elemento'=>$datos['id_categoria'],
    );
    return $this->db->insert('elemento',$data);
  }

   public function elemento_Upate($datos){
    $this->db->where('id',$datos['id_elemento']);
    $data=array(
      'nombre'=>$datos['nombre'],
      'descripcion'=>$datos['descripcion'],
      'rol'=>$datos['rol'],
      'id_tipo_elemento'=>$datos['id_categoria'],
      'actor_nombre'=>$datos['actor_nombre'],
      'actor_apellido'=>$datos['actor_apellido'],
      'documento_actor'=>$datos['documento_actor'],
      'id_tipo_documento'=>$datos['id_tipo_documento'],
      'monto'=>$datos['monto'],
      'fecha_inicio'=>$datos['fecha_inicio'],
      'fecha_finalizacion'=>$datos['fecha_finalizacion'],
      'fecha_liquidacion'=>$datos['fecha_liquidacion'],
      'id_tipo_contrato'=>$datos['id_tipo_contrato'],
      'id_tipo_moneda'=>$datos['id_tipo_moneda']
    );
    return $this->db->update('elemento',$data);
  }


 /* public function elemento_id_produccion($id,$id_categoria,$limit_escena){
    $query=$this->db->query("SELECT rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, 
                                  DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion As tipo_moneda,
                                  (SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2
                                  FROM escenas_has_elementos
                                  INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                                  INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                  INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                  WHERE escenas_has_elementos.id_elemento = e.id ORDER BY plan_diario.fecha_inicio LIMIT 1
                                  ) AS fecha_inicio_2 ,
                                  (SELECT group_concat(DISTINCT(p2.numero) SEPARATOR ',') 
                                  FROM produccion_has_capitulos p2
                                  INNER JOIN escena e2 ON e2.id_capitulo = p2.id
                                  INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id
                                  WHERE eh.id_elemento = e.id
                                  ) as libretos
                                  FROM elemento e 
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id."  AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento 
                                  ORDER BY e.nombre limit ".$limit_escena.",30 ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }*/

  public function elemento_id_produccion($id,$id_categoria,$limit_escena){
    $query=$this->db->query("SELECT 
                          rol_actores_elementos.rol AS rol, act.nombre as actor_nombre, act.apellido actor_apellido, e.nombre, e.documento_actor, 
                          e.id_tipo_elemento,e.id AS id_elemento,
                          e.descripcion AS des_elem,c.*, act.documento as documento_actor, solicitudes.honorarios as monto, solicitudes.id_tipo_moneda, 

                          solicitudes.id_forma_pago as id_tipo_contrato,  act.id_tipo_documento, 
                          DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion, 
                          DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion, 
                          tipo_documento.descripcion AS tipo_documento, 
                          tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion As tipo_moneda, 
                          (SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2
                          FROM escenas_has_elementos 

                          INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                          INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
                          INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
                          WHERE escenas_has_elementos.id_elemento = e.id ORDER BY plan_diario.fecha_inicio LIMIT 1 ) AS fecha_inicio_2 , 
                          (SELECT group_concat(DISTINCT(p2.numero) SEPARATOR ',') FROM produccion_has_capitulos p2 
                          INNER JOIN escena e2 ON e2.id_capitulo = p2.id 
                          INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id WHERE eh.id_elemento = e.id ) as libretos 
                          FROM elemento e 
                          LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=e.id
                          LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
                          LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor
                          INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento 
                          LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol 
                          LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
                          LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
                          LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda 
                                  WHERE c.id_produccion=".$id."  AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento 
                                 group by e.id ORDER BY e.nombre limit ".$limit_escena.",30 ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function elemento_id_produccion2($id,$id_categoria){
    $query=$this->db->query("SELECT rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, 
                                  DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion As tipo_moneda,
                                  (SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2
                                  FROM escenas_has_elementos
                                  INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                                  INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                  INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                  WHERE escenas_has_elementos.id_elemento = e.id ORDER BY plan_diario.fecha_inicio LIMIT 1
                                  ) AS fecha_inicio_2 ,
                                  (SELECT group_concat(DISTINCT(p2.numero) SEPARATOR ',') 
                                  FROM produccion_has_capitulos p2
                                  INNER JOIN escena e2 ON e2.id_capitulo = p2.id
                                  INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id
                                  WHERE eh.id_elemento = e.id
                                  ) as libretos
                                  FROM elemento e 
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id."  AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento 
                                  group by e.id ORDER BY e.nombre");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function elemento_id_produccion_total($id,$id_categoria){
    $query=$this->db->query("SELECT rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion As tipo_moneda
                                  FROM elemento e 
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id."  AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento 
                                  group by e.id ORDER BY e.nombre");
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
        return false;
      }
  }

  

  public function elemento_id_produccion_limit_total($id,$id_categoria,$desde,$hasta,$sql){
    $query=$this->db->query("SELECT DISTINCT(e.id),rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                                  FROM elemento e 

                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id 
                                  INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                                  INNER JOIN produccion_has_capitulos ON  produccion_has_capitulos.id = escena.id_capitulo
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id." AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                                  group by e.id ORDER BY e.nombre;");
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
        return false;
      }
  }

  public function locacion_limit($idproduccion,$limit=0){
    $query =$this->db->query("SELECT * FROM locacion WHERE id_produccion = ".$idproduccion." 
    ORDER BY nombre asc limit ".$limit.",30;");
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }


/*public function elemento_id_produccion_limit($id,$id_categoria,$desde,$hasta,$limit_escena,$sql=''){
    $query=$this->db->query("SELECT DISTINCT(e.id),rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                               
                                FROM elemento e 
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id 
                                  INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                                  INNER JOIN produccion_has_capitulos ON  produccion_has_capitulos.id = escena.id_capitulo
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id." AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                                  ORDER BY e.nombre limit ".$limit_escena.",30 ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }*/

  public function elemento_id_produccion_limit($id,$id_categoria,$desde,$hasta,$limit_escena,$sql=''){
    $query=$this->db->query("SELECT DISTINCT(e.id),rol_actores_elementos.rol AS rol, act.nombre as actor_nombre, act.apellido actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  act.documento as documento_actor, solicitudes.honorarios as monto, solicitudes.id_tipo_moneda, solicitudes.id_forma_pago as id_tipo_contrato,
                                  act.id_tipo_documento, DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                               
                                FROM elemento e 
                                LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=e.id
                                LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
                                LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor

                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id 
                                  INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                                  INNER JOIN produccion_has_capitulos ON  produccion_has_capitulos.id = escena.id_capitulo
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda 
                                  WHERE c.id_produccion=".$id." AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                                  group by e.id ORDER BY e.nombre limit ".$limit_escena.",30 ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

    /*public function elemento_id_produccion_limit2($id,$id_categoria,$desde,$hasta,$sql=''){
    $query=$this->db->query("SELECT DISTINCT(e.id),rol_actores_elementos.rol AS rol, e.actor_nombre, e.actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  e.documento_actor, e.monto, e.id_tipo_moneda, e.id_tipo_contrato, e.id_tipo_documento, DATE_FORMAT(e.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(e.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                                 
                                  FROM elemento e 
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id 
                                  INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                                  INNER JOIN produccion_has_capitulos ON  produccion_has_capitulos.id = escena.id_capitulo
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                  LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = e.id_tipo_contrato
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = e.id_tipo_documento
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = e.id_tipo_moneda
                                  WHERE c.id_produccion=".$id." AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                                  ORDER BY e.nombre ");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }*/

  public function elemento_id_produccion_limit2($id,$id_categoria,$desde,$hasta,$sql=''){
    $query=$this->db->query("SELECT DISTINCT(e.id),rol_actores_elementos.rol AS rol, act.nombre as actor_nombre, act.apellido actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  act.documento as documento_actor, solicitudes.honorarios as monto, solicitudes.id_tipo_moneda, solicitudes.id_forma_pago as id_tipo_contrato,
                                  act.id_tipo_documento, DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                                 
                                  FROM elemento e 

                                  LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=e.id
                                LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
                                LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor


                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id 
                                  INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                                  INNER JOIN produccion_has_capitulos ON  produccion_has_capitulos.id = escena.id_capitulo
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                   LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda 
                                  WHERE c.id_produccion=".$id." AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                                  group by e.id ORDER BY e.nombre ");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function elemento_id_produccion_palabra($id,$id_categoria,$palabra){
    $query=$this->db->query("SELECT rol_actores_elementos.rol AS rol,  act.nombre as actor_nombre, act.apellido actor_apellido, e.nombre, e.documento_actor, 
                                  e.id_tipo_elemento,e.id AS id_elemento,e.descripcion AS des_elem,c.*, 
                                  act.documento as documento_actor, solicitudes.honorarios as monto, solicitudes.id_tipo_moneda, solicitudes.id_forma_pago as id_tipo_contrato,
                                  act.id_tipo_documento, DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') AS fecha_inicio, 
                                  DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
                                  DATE_FORMAT(e.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
                                  tipo_documento.descripcion AS tipo_documento, tipo_contrato.descripcion AS tipo_contrato, tipo_moneda.descripcion AS tipo_moneda
                                  /*(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2
                                  FROM escenas_has_elementos
                                  INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                                  INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                  INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                  WHERE escenas_has_elementos.id_elemento = e.id ORDER BY plan_diario.fecha_inicio LIMIT 1
                                  ) AS fecha_inicio_2,
                                  (SELECT group_concat(DISTINCT(p2.numero) SEPARATOR ',') 
                                  FROM produccion_has_capitulos p2
                                  INNER JOIN escena e2 ON e2.id_capitulo = p2.id
                                  INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id
                                  WHERE eh.id_elemento = e.id
                                  ) as libretos*/
                                  FROM elemento e 
                                  LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=e.id
                                LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
                                LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor
                                  INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
                                  LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = e.rol
                                   LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
                                  LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
                                  LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda
                                  WHERE c.id_produccion=".$id."  AND c.id=".$id_categoria." AND c.id=e.id_tipo_elemento 
                                  AND e.nombre LIKE '%".$palabra."%' group by e.id ORDER BY e.nombre;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }



  public function rol_actores_elementos_id($id){
    $this->db->where('id',$id);
    $query=$this->db->get('rol_actores_elementos');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }
  public function escenas_has_elementos($id_elemento){
    $this->db->where('id_elemento',$id_elemento);
    $query=$this->db->get('escenas_has_elementos');
    if ($query->num_rows>0){
      return $query->num_rows;
     } else {
      return 0;
     }
  }

 public function escenas_has_elementos_limit($id_elemento,$desde,$hasta){
  $query=$this->db->query("SELECT * FROM escenas_has_elementos 
                            INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
                            INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                            WHERE escenas_has_elementos.id_elemento = ".$id_elemento."  AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ;");
      if ($query->num_rows>0){
        return $query->num_rows;
      } else {
        return 0;
      }
  }

  public function total_libretos_limite($idproduccion,$desde,$hasta){
    $query=$this->db->query("SELECT COUNT(DISTINCT(produccion_has_capitulos.id)) AS total_libretos, COUNT(DISTINCT(escena.id)) AS total_escenas 
                              FROM produccion_has_capitulos
                              INNER JOIN escena On escena.id_capitulo = produccion_has_capitulos.id
                              WHERE produccion_has_capitulos.id_produccion = ".$idproduccion." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta.";");
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function categoria_elemento_id($id){
    $query=$this->db->query("SELECT c.*, e.*,e.id as id_elemento FROM categoria_elemento c, elemento e
              where c.id=".$id." and c.id=e.id_tipo_elemento;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }
  public function escena_has_elemento($id){
    $this->db->where('id_elemento',$id);
    $query=$this->db->get('escenas_has_elementos');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function escenas_producidas($id_elemento){
   $query=$this->db->query("SELECT count(*) AS total from escenas_has_elementos e, escena s
            where e.id_elemento=".$id_elemento." and e.id_escena=s.id and s.estado=1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function escenas_proproducir($id_elemento){
   $query=$this->db->query("SELECT count(*) AS total from escenas_has_elementos e, escena s
            where e.id_elemento=".$id_elemento." and e.id_escena=s.id and (s.estado IS NULL or s.estado<>1)");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  /*public function total_escenas_idProduccion($id_produccion){
    $query=$this->db->query("SELECT count(*) as total from produccion_has_capitulos p, escena e
        where p.id_produccion=".$id_produccion." and p.id=e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }*/

  public function total_escenas_idProduccion($id_produccion){
    $query=$this->db->query("SELECT sum(escenas_escritas) as total FROM produccion_has_capitulos
    WHERE id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function total_escenas_limite($id_produccion,$desde,$hasta){
    $query=$this->db->query("SELECT count(*) AS total FROM  escena e
        INNER JOIN produccion_has_capitulos p ON e.id_capitulo = p.id
        WHERE p.id_produccion=".$id_produccion." AND p.id = e.id_capitulo AND p.numero BETWEEN ".$desde." AND ".$hasta.";");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function total_capitulos_idProduccion($id_produccion){
    $query=$this->db->query("SELECT p.* from produccion_has_capitulos p
        where p.id_produccion=".$id_produccion." ORDER BY p.numero");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    
  }

  public function total_escenas_capitulo($id_capitulo){
     $this->db->where('id_capitulo',$id_capitulo);
    $query=$this->db->get('escena');
    if ($query->num_rows>0){
      return $query->num_rows();
     } else {
      return false;
     }
    
  }

  public function escenas_elementos($id_capitulo,$id_elemento){
    $query=$this->db->query("SELECT * from escena e, escenas_has_elementos h
        where e.id_capitulo=".$id_capitulo." and h.id_escena=e.id and h.id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
         return false;
      }
    
  }

  public function validar_elemento($nombre,$id_tipo_elemento){
     $this->db->where('nombre', $nombre); // users table
     $this->db->where('id_tipo_elemento',$id_tipo_elemento);
    $query=$this->db->get('elemento');
    if ($query->num_rows>0){
      return true;
     } else {
      return false;
     }
    
  }

  public function buscar_elemento_id($id_elemento){
      $query=$this->db->query("SELECT *,ele.id AS idelemento, roles.rol AS rol_final, cat.tipo AS tipo, cat.id_produccion  FROM elemento ele
      LEFT OUTER JOIN rol_actores_elementos roles ON roles.id = ele.rol 
      INNER JOIN categoria_elemento cat ON cat.id = ele.id_tipo_elemento
      WHERE ele.id = ".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function locaciones_sets($idproduccion){
    $query = $this->db->query("SELECT 
                                locacion.id AS id_locacion,
                                locacion.nombre AS locacion_nombre, 
                                sets.id AS id_set, 
                                sets.nombre AS set_nombre, 
                                (SELECT COUNT(escena.id) FROM escena WHERE escena.id_set = sets.id) AS usos_set,
                                (SELECT COUNT(escena.id) FROM escena WHERE escena.id_locacion = locacion.id) AS usos_locacion,
                                (SELECT COUNT(l.id) FROM locacion l WHERE l.id_produccion = ".$idproduccion.") AS cantidad_locaciones
                                FROM locacion
                                LEFT OUTER JOIN sets ON  sets.id_locacion = locacion.id 
                                WHERE locacion.id = ".$idproduccion.";");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
       return false;
    }
  }

  public function eliminar_locacion($idlocacion){
    $this->db->where('id', $idlocacion);
    $this->db->delete('locacion');
  }

  public function eliminar_set($idset){
    $this->db->where('id', $idset);
    $this->db->delete('sets');
  }

  public function eliminar_elemento($idelemento){
    $this->db->where('id', $idelemento);
    $this->db->delete('elemento');
  }

    public function locacion_usos($idproduccion,$limit_escenas,$sql=""){
      $query = $this->db->query("SELECT *, (select count(id_locacion) from escena
              WHERE escena.id_locacion=locacion.id ) as uso FROM locacion
              WHERE id_produccion = ".$idproduccion."
              order by locacion.nombre asc ".$sql." limit ".$limit_escenas.",30;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function locacion_usos2($idproduccion,$sql=""){
      $query = $this->db->query("SELECT *, (select count(id_locacion) from escena
              WHERE escena.id_locacion=locacion.id ) as uso FROM locacion
              WHERE id_produccion = ".$idproduccion."
              order by locacion.nombre asc ".$sql);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function locacion_usos_palabra($idproduccion,$palabra){
      $query = $this->db->query("SELECT *, (select count(id_locacion) from escena
              WHERE escena.id_locacion=locacion.id ) as uso FROM locacion
              WHERE id_produccion = ".$idproduccion." AND locacion.nombre like '%".$palabra."%' 
              order by locacion.nombre asc ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }


    public function locacion_usos_limite($idproduccion,$sql="",$desde,$hasta,$limit_escena,$tamano_fial){
      $query = $this->db->query("SELECT DISTINCT(locacion.id),locacion.*, (SELECT count(DISTINCT(id_locacion)) FROM escena
              INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
              WHERE escena.id_locacion=locacion.id ".$sql." ) AS uso FROM locacion
              LEFT OUTER JOIN escena ON escena.id_locacion = locacion.id
              LEFT OUTER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
              WHERE locacion.id_produccion = ".$idproduccion." 
              AND produccion_has_capitulos.numero BETWEEN ".$hasta." AND ".$desde."
              ORDER BY locacion.nombre ASC limit ".$limit_escena.",".$tamano_fial.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function escenas_producidas_idcapitulo($id_locacion){
     $query=$this->db->query("SELECT count(id) as total from escena 
            where id_locacion=".$id_locacion."
            and (estado=1 OR estado=2 OR estado=12 OR estado=14);");
        if ($query->num_rows>0) {
          return $query->result();
        }else{
           return false;
        }
    }

    public function escenas_producidas_limite($id_locacion,$desde,$hasta){
     $query=$this->db->query("SELECT count(escena.id) as total from escena
            INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
            WHERE id_locacion=".$id_locacion."
            AND (escena.estado=1 OR escena.estado=2 OR escena.estado=12 OR escena.estado=14) AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

    public function escenas_locacion_limite($id_locacion,$desde,$hasta){
      $query=$this->db->query("SELECT count(escena.id) as total from escena 
            INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
            WHERE id_locacion=".$id_locacion."
            AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

    public function escenas_set_limite($id_set,$desde,$hasta){
      $query=$this->db->query("SELECT count(DISTINCT(escena.id)) as total from escena 
            INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
            WHERE id_set=".$id_set."
            AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

    public function escenas_producidas_id_sets($id_set){
     $query=$this->db->query("SELECT count(id) as total from escena 
            where id_set=".$id_set."
            and estado=1;");
        if ($query->num_rows>0) {
          return $query->result();
        }else{
           return false;
        }
    }

  public function escenas_porproducidas_idcapitulo($id_locacion){
    $query=$this->db->query("SELECT count(*) AS total 
        from escena where id_locacion=".$id_locacion." and (estado IS NULL or estado<>1);");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

   public function escenas_porproducidas_idsets($id_set){
    $query=$this->db->query("SELECT count(*) AS total 
        from escena where id_set=".$id_set." and (estado IS NULL or estado<>1);");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

 public function locacion_escena($id_capitulo,$id_set){
    $query=$this->db->query("SELECT * FROM 
      escena where id_capitulo=".$id_capitulo." and id_set=".$id_set);
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
         return false;
      }
    
  }

  public function locacion_escena_id($id_capitulo,$id_set){
    $query=$this->db->query("SELECT * FROM 
      escena where id_capitulo=".$id_capitulo." and id_locacion=".$id_set);
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
         return false;
      }
    
  }

  public function set_escena($id_locacion){
    $query=$this->db->query("SELECT *,
      (SELECT count(e.id_set) FROM escena e where e.id_set=s.id ) as uso from sets s 
      where s.id_locacion=".$id_locacion."
            order by s.nombre asc;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }


  public function set_escena_limite($id_locacion,$sql=""){
    $query=$this->db->query("SELECT *,
      (SELECT count(e.id_set) FROM escena e where e.id_set=s.id ) as uso from sets s 
      where s.id_locacion=".$id_locacion."
            order by s.nombre asc ".$sql.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function set_locacion_limit($id_locacion,$desde,$hasta){
    $query=$this->db->query("SELECT DISTINCT(s.id),s.*,
      (SELECT count(e.id_set) FROM escena e WHERE e.id_set=s.id ) AS uso FROM sets s
      INNER JOIN escena ON escena.id_set = s.id
      INNER JOIN produccion_has_capitulos On produccion_has_capitulos.id = escena.id_capitulo 
      WHERE s.id_locacion=".$id_locacion." 
      AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
      ORDER BY s.nombre ASC;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function sets_produccion($idproduccion){
    $query = $this->db->query('SELECT DISTINCT(sets.id) AS id, sets.nombre, sets.id_locacion, locacion.nombre AS nombre_locacion,
                      (SELECT count(e.id_set) FROM escena e where e.id_set=sets.id ) as uso 
                      FROM sets
                      INNER JOIN locacion ON locacion.id = sets.id_locacion
                      WHERE locacion.id_produccion = '.$idproduccion.' 
                      ORDER BY sets.nombre ASC;');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function sets_produccion_limite($idproduccion,$desde,$hasta){
    $query = $this->db->query("SELECT DISTINCT(sets.id) AS id, sets.nombre, sets.id_locacion, locacion.nombre AS nombre_locacion,
                      (SELECT count(e.id_set) FROM escena e where e.id_set=sets.id ) as uso 
                      FROM sets
                      LEFT outer JOIN  escena ON escena.id_set = sets.id
                      LEFT outer JOIN  produccion_has_capitulos On produccion_has_capitulos.id = escena.id_capitulo
                      INNER JOIN locacion ON locacion.id = sets.id_locacion
                      WHERE locacion.id_produccion = ".$idproduccion."
                      AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                      ORDER BY sets.nombre ASC;");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

    public function sets_produccion_limite2($idproduccion,$desde,$hasta,$sql){
    $query = $this->db->query("SELECT DISTINCT(sets.id) AS id, sets.nombre, sets.id_locacion,
                      (SELECT count(e.id_set) FROM escena e where e.id_set=sets.id ) as uso, locacion.nombre AS nombre_locacion 
                      FROM sets
                      LEFT outer JOIN  escena ON escena.id_set = sets.id
                      LEFT outer JOIN  produccion_has_capitulos On produccion_has_capitulos.id = escena.id_capitulo
                      INNER JOIN locacion ON locacion.id = sets.id_locacion
                      WHERE locacion.id_produccion = ".$idproduccion."
                      AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." 
                      ORDER BY sets.nombre ASC ".$sql);
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }


  public function elementos($id_produccion,$categoria,$limite="0"){
    $query=$this->db->query("SELECT e.id,e.nombre,r.rol from elemento e 
          INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
          LEFT OUTER JOIN rol_actores_elementos r ON r.id = e.rol
          where c.id_produccion=".$id_produccion." AND c.id=".$categoria." ORDER BY e.nombre limit ".$limite.",30");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function elementos_limite($id_produccion,$categoria,$limite="0",$desde,$hasta,$sql){
    $query=$this->db->query("SELECT DISTINCT(e.id),e.nombre,r.rol from elemento e 
          INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
          LEFT OUTER JOIN rol_actores_elementos r ON r.id = e.rol
          INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
          INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
          INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id =  escena.id_capitulo 
          where c.id_produccion=".$id_produccion." AND c.id=".$categoria." ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ORDER BY e.nombre limit ".$limite.",30");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }



  public function elementos_limite_libretos($id_produccion,$categoria,$desde,$hasta,$sql=''){
    $query=$this->db->query("SELECT DISTINCT(e.id),e.nombre,r.rol from elemento e 
          INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
          LEFT OUTER JOIN rol_actores_elementos r ON r.id = e.rol
          INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
          INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
          INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id =  escena.id_capitulo 
          WHERE c.id_produccion=".$id_produccion." AND c.id=".$categoria." ".$sql." AND  produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ORDER BY e.nombre;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }


  public function contar_elementos_roles($id_produccion,$sql,$desde,$hasta){
    $query=$this->db->query("SELECT COUNT(DISTINCT(e.id)) AS cantidad from elemento e 
          INNER JOIN categoria_elemento c ON c.id = e.id_tipo_elemento
          LEFT OUTER JOIN rol_actores_elementos r ON r.id = e.rol
          INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = e.id
          INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
          INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id =  escena.id_capitulo 
          WHERE c.id_produccion=".$id_produccion." AND ".$sql." AND produccion_has_capitulos.numero BETWEEN ".$desde." AND ".$hasta." ORDER BY e.nombre;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function uso($id_produccion,$id_capitulo,$id_elemento){
    $query=$this->db->query("SELECT SUM((SELECT count(h.id) from escenas_has_elementos h where h.id_elemento=".$id_elemento." and  e.id=h.id_escena)) AS uso FROM
          produccion_has_capitulos c
          INNER JOIN escena e ON e.id_capitulo=c.id
          WHERE c.id_produccion=".$id_produccion." AND c.id=".$id_capitulo);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function usos_locacion($id_capitulo,$id_locacion){
    $query=$this->db->query("SELECT count(escena.id) AS uso FROM escena
          WHERE escena.id_capitulo = ".$id_capitulo." AND escena.id_locacion=".$id_locacion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function usos_set($id_capitulo,$id_set){
    $query=$this->db->query("SELECT count(escena.id) AS uso FROM escena
          WHERE escena.id_capitulo = ".$id_capitulo." AND escena.id_set=".$id_set);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function validar_locacion($nombre,$id_produccion){
     $this->db->where('nombre', $nombre); // users table
     $this->db->where('id_produccion',$id_produccion);
    $query=$this->db->get('locacion');
    if ($query->num_rows>0){
      return true;
     } else {
      return false;
     }
  }


  public function planes_elemento($id_elemento){
    $query = $this->db->query("SELECT escena.numero_escena AS numero_escena, pc.numero AS numero_libreto, locacion.nombre AS nombre_locacion, sets.nombre AS nombre_set,
                              DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') as fecha_grabacion,plan_diario.fecha_inicio as fecha_grabacion_format,
                              unidad.numero AS numero_unidad, 
                              estados_plan_diario.descripcion,
                              COALESCE(retomas_escena.tiempo, 0) AS producida
                              FROM plan_diario_has_escenas_has_unidades
                              INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
                              INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
                              INNER JOIN estados_plan_diario ON estados_plan_diario.id = plan_diario.estado
                              INNER JOIN escena ON escena.id = plan_diario_has_escenas_has_unidades.id_escena
                              INNER JOIN produccion_has_capitulos pc ON pc.id = escena.id_capitulo
                              INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = escena.id
                              INNER JOIN locacion ON locacion.id = escena.id_locacion
                              INNER JOIN sets ON sets.id = escena.id_set
                              LEFT OUTER JOIN retomas_escena ON retomas_escena.id_escena = plan_diario_has_escenas_has_unidades.id_escena
                              AND retomas_escena.fecha_produccion = plan_diario.fecha_inicio
                              AND retomas_escena.unidad_produccion = unidad.id
                              WHERE
                              escenas_has_elementos.id_elemento = ".$id_elemento." AND plan_diario.estado>2 ORDER BY numero_libreto,numero_escena;");
    if ($query->num_rows>0){
      return  $query->result();
    } else {
      return false;
    }
  }

    public function validar_set($nombre,$id_locacion){
     $this->db->where('nombre', $nombre); // users table
     $this->db->where('id_locacion',$id_locacion);
     $query=$this->db->get('sets');
    if ($query->num_rows>0){
      return true;
     } else {
      return false;
     }
    
  }

  public function eliminar_elemento_locacion($id){
    $this->db->where('id', $id);
     return $this->db->delete('locacion');
  }

  public function eliminar_elemento_set($id){
    $this->db->where('id', $id);
     return $this->db->delete('sets');
  }

  public function contratos(){
    $query=$this->db->get('tipo_contrato');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function tipos_documento(){
    $query=$this->db->get('tipo_documento');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function tipos_moneda(){
    $query=$this->db->get('tipo_moneda');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

public function personajes_no_extra_anteriores($id_produccion){
    $query = $this->db->query("SELECT elemento.id, elemento.id_tipo_contrato, elemento.nombre,elemento.documento_actor,rol_actores_elementos.rol AS rol, elemento.monto, elemento.actor_nombre, elemento.actor_apellido,
                                      tipo_contrato.descripcion AS tipo_contrato,
                                      (SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio 
                                        FROM escenas_has_elementos 
                                        INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
                                        INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
                                        INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
                                        WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1 
                                        ) AS fecha_inicio,
                                        DATE_FORMAT(elemento.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2,
                                        DATE_FORMAT(elemento.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
                                        (SELECT COUNT(DISTINCT(plan_diario.fecha_inicio))
                                        FROM escenas_has_elementos
                                        INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                                        INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                        INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                        WHERE escenas_has_elementos.id_elemento = elemento.id
                                        ) AS dias_trabajados,
                                (SELECT  group_concat(DISTINCT(prod.numero ))
                                from escenas_has_elementos e2
                                INNER JOIN escena es2 on es2.id=e2.id_escena
                                INNER JOIN produccion_has_capitulos prod ON prod.id=es2.id_capitulo
                                where e2.id_elemento=elemento.id AND (es2.estado=1 or es2.estado=2 or es2.estado=12 or es2.estado=14)) as libretos_personaje
                                FROM  elemento
                                INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
                                INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
                                left JOIN tipo_contrato ON tipo_contrato.id = elemento.id_tipo_contrato
                                WHERE elemento.rol != 4 
                                AND (elemento.id_tipo_contrato !=5 or elemento.id_tipo_contrato is null) AND categoria_elemento.id_produccion = ".$id_produccion." 
                                ORDER BY elemento.nombre; ");
    if ($query->num_rows>0){
      return $query->result();
    }else {
      return false;
    }
  }

  public function personajes_no_extra($id_produccion){
    $query = $this->db->query("SELECT elemento.id, solicitudes.id_forma_pago as id_tipo_contrato, elemento.nombre,
                                      act.documento as documento_actor,rol_actores_elementos.rol AS rol, solicitudes.honorarios as monto, act.nombre as actor_nombre, act.apellido actor_apellido,
                                      tipo_contrato.descripcion AS tipo_contrato,
                                      (SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio 
                                        FROM escenas_has_elementos 
                                        INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
                                        INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
                                        INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
                                        WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1 
                                        ) AS fecha_inicio,
                                        DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2,
                                        DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
                                        (SELECT COUNT(DISTINCT(plan_diario.fecha_inicio))
                                        FROM escenas_has_elementos
                                        INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                                        INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                                        INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                                        WHERE escenas_has_elementos.id_elemento = elemento.id
                                        ) AS dias_trabajados,
                                (SELECT  group_concat(DISTINCT(prod.numero ))
                                from escenas_has_elementos e2
                                INNER JOIN escena es2 on es2.id=e2.id_escena
                                INNER JOIN produccion_has_capitulos prod ON prod.id=es2.id_capitulo
                                where e2.id_elemento=elemento.id AND (es2.estado=1 or es2.estado=2 or es2.estado=12 or es2.estado=14)) as libretos_personaje
                                FROM  elemento
                                LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=elemento.id
                                LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
                                LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor
                                INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
                                INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
                                LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
                                WHERE elemento.rol != 4 
                                AND (elemento.id_tipo_contrato !=5 or elemento.id_tipo_contrato is null) AND categoria_elemento.id_produccion = ".$id_produccion." 
                                group by elemento.id ORDER BY elemento.nombre; ");
    if ($query->num_rows>0){
      return $query->result();
    }else {
      return false;
    }
  }


  public function libretos_elementos($id_elemento){
    $query=$this->db->query("SELECT group_concat(DISTINCT(p2.numero) order by p2.numero SEPARATOR ',') as libretos FROM produccion_has_capitulos p2 
      INNER JOIN escena e2 ON e2.id_capitulo = p2.id INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id 
      WHERE eh.id_elemento =".$id_elemento." ORDER BY p2.numero");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function elementos_fecha2($id_elemento){
    $query=$this->db->query("SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio_2 
FROM escenas_has_elementos INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
WHERE escenas_has_elementos.id_elemento = ".$id_elemento." ORDER BY plan_diario.fecha_inicio LIMIT 1");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function escenas_has_elementos_id_elemento($id_elemento){
    $this->db->where('id_elemento',$id_elemento);
    $query=$this->db->get('escenas_has_elementos');
    if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return 0;
     }
  }

  public function escenas_has_elementos_id_escena_id_elemento($id_elemento,$id_escena){
    $this->db->where('id_elemento',$id_elemento);
    $this->db->where('id_escena',$id_escena);
    $query=$this->db->get('escenas_has_elementos');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return 0;
     }
  }

  public function eliminar_extras_escena($id_categoria){
     $this->db->where('id_escenas_has_elementos',$id_categoria);
     $this->db->delete('extras_escena');
  }
 

 public function cantidad_extra_escena($id){
    $this->db->where('id_escenas_has_elementos',$id);
    $query=$this->db->get('extras_escena');
    if ($query->num_rows>0){
      return $query->result();
     } else {
      return 0;
     }
  }


}  