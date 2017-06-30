<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_pagos extends CI_Model {

	//filtro por documento
	public function elementos_persona($sql){

     $query=$this->db->query("SELECT DISTINCT C.id as elemento_id, E.id,E.nombre_produccion,C.nombre as nombre_elem,F.nombre,F.apellido,F.documento,G.rol,F.id as id_actor
								FROM solicitudes A,solicitud_has_elementos B, elemento C, categoria_elemento D,produccion E,actores F, rol_actores_elementos G
								WHERE A.id=B.id_solicitud AND A.tipo=1
								AND B.id_elemento=C.id AND C.id_tipo_elemento=D.id AND E.id=D.id_produccion
								AND A.id_actor=F.id AND G.id=C.rol ".$sql);
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  public function libretos_elementos($id_elemento){
    $query=$this->db->query("SELECT p2.numero as libretos ,  p2.id as id_capitulo,p2.estado as estado_libreto,
                              (SELECT group_concat(cuentas_cobro.estado separator ',' )  FROM cuenta_cobro_has_capitulo
                            inner join cuentas_cobro on cuentas_cobro.id_cuentas_cobro=cuenta_cobro_has_capitulo.id_cuenta_cobro
                            where cuenta_cobro_has_capitulo.id_capitulo=p2.id and cuentas_cobro.id_elemento=".$id_elemento.") as estado_pago
                            FROM produccion_has_capitulos p2 
                                  inner JOIN escena e2 ON e2.id_capitulo = p2.id 
                                  inner JOIN escenas_has_elementos eh ON eh.id_escena = e2.id 
                                  WHERE eh.id_elemento=".$id_elemento." group by p2.id  ORDER BY p2.numero");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }
  public function insertar_cuenta_cobro($data){
      return $this->db->insert('cuentas_cobro',$data);
    }
   public function insertar_cuenta_has_cap($data){
      return $this->db->insert('cuenta_cobro_has_capitulo',$data);
    }
   public function group_capitulos($sql=""){
    $query=$this->db->query("SELECT cc.id_cuentas_cobro,cc.fecha_generado,ec.descripcion as estado,cc.valor, group_concat(DISTINCT(p2.numero) order by p2.numero SEPARATOR ',') as libretos,
        group_concat(DISTINCT(p2.id) order by p2.id SEPARATOR ',') as id_capitulo,
        pr.nombre_produccion,el.nombre as nombre_elemento,rol.rol, ac.documento, ac.nombre ,ac.apellido, el.id as id_elemento
    FROM produccion_has_capitulos p2
      INNER JOIN escena e2 ON e2.id_capitulo = p2.id INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id 
      INNER JOIN cuenta_cobro_has_capitulo chc ON chc.id_capitulo = e2.id_capitulo
      INNER JOIN cuentas_cobro cc ON cc.id_cuentas_cobro = chc.id_cuenta_cobro AND cc.id_elemento=eh.id_elemento
      INNER JOIN estados_cuenta ec ON cc.estado = ec.id_estado
      INNER JOIN produccion pr ON pr.id = p2.id_produccion
      INNER JOIN elemento el ON el.id = cc.id_elemento
      INNER JOIN rol_actores_elementos rol ON rol.id = el.rol
      LEFT OUTER JOIN actores ac ON ac.id = cc.id_actor ".$sql." GROUP BY cc.id_cuentas_cobro ORDER BY ec.id_estado");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
   }

    public function update_cuenta_cobro($data){
      $id_cuenta=$data['id_cuentas_cobro'];
      $data=array(
            'estado'=>$data['estado']
          );
      $this->db->where('id_cuentas_cobro',$id_cuenta);
     return $this->db->update('cuentas_cobro',$data);
    }

    public function select_estados_cuenta(){
    $query = $this->db->get('estados_cuenta');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }
  public function fechas_capitulos($id_capitulo){
    $query=$this->db->query("SELECT es.fecha_produccion,(SELECT p2.fecha_entregado FROM produccion_has_capitulos p2 WHERE p2.id=es.id_capitulo) as fecha_inicio FROM production_suite.escena es
                             WHERE es.id_capitulo=".$id_capitulo." AND es.fecha_produccion IS NOT NULL ORDER BY es.fecha_produccion LIMIT 1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
   }
   public function meses_capitulo_produccion($id_capitulo){
       $query=$this->db->query("SELECT DISTINCT EXTRACT(MONTH FROM fecha_produccion) as month,EXTRACT(YEAR FROM fecha_produccion) as year FROM production_suite.escena
                                WHERE id_capitulo=".$id_capitulo." AND fecha_produccion IS NOT NULL ORDER BY year DESC, month");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
   }


   public function solicitud_elemento($id_elemento){
       $query=$this->db->query("SELECT elemento.id,elemento.nombre, rol_actores_elementos.rol AS rol, rol_actores_elementos.id AS id_rol,solicitud_has_elementos.id_solicitud
                      FROM elemento 
                      INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
                      INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_elemento = elemento.id 
                      INNER JOIN solicitudes ON solicitudes.id = solicitud_has_elementos.id_solicitud
                      WHERE solicitudes.id_estado=11 and elemento.id=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
   }

}
?>