<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_escenas extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function capitulos_idProduccion($id){
      $this->db->where('id_produccion',$id);
      $query=$this->db->get('produccion_has_capitulos');
      if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function buscar_escenas_numero($numero, $idcapitulo){
    $this->db->where('numero_escena like',$numero);
    $this->db->where('id_capitulo',$idcapitulo);
      $query=$this->db->get('escena');
      if ($query->num_rows>0) {
        return false;
      } else {
        return true;
      }
  }

 
  public function escenas_canceladas($idcapitulo){
      $query=$this->db->query("SELECT count(id) AS total 
        FROM escena WHERE estado=3 and id_capitulo =".$idcapitulo);
      if ($query->num_rows>0) {
         return $query->result();
      }else{
         return false;
      }
  }

  

/*  public function numero_escena($idcapitulo){

    $this->db->select_max('numero_escena');
    $this->db->where('id_capitulo',$idcapitulo);
    $query = $this->db->get('escena');
    if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }*/

    public function numero_escena($idcapitulo){
      $query=$this->db->query("SELECT max(cast(numero_escena AS unsigned)) AS numero_escena 
        FROM escena WHERE id_capitulo =".$idcapitulo);
      if ($query->num_rows>0) {
         return $query->result();
      }else{
         return false;
      }
  }
  public function locacion($idproduccion){
    $this->db->where("id_produccion",$idproduccion);
    $this->db->order_by("nombre", "asc"); 
    $query = $this->db->get('locacion');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
   public function locacion_id($id){
    $this->db->where("id",$id); 
    $query = $this->db->get('locacion');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function set(){
    $this->db->order_by("id", "desc"); 
    $query = $this->db->get('sets');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function set_id($id){
    $this->db->where("id",$id); 
    $query = $this->db->get('sets');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function set_id_locacion($id_locacion){
    $this->db->where("id_locacion", $id_locacion); 
    $this->db->order_by("nombre","asc");
    $query = $this->db->get('sets');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }



  public function set_id_locaciones($sql){
      $query=$this->db->query("SELECT * FROM sets
        where  ".$sql." order by nombre");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  public function id_locaciones($sql){
      $query=$this->db->query("SELECT * FROM locacion
        where  ".$sql." order by nombre");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  public function crear_locacion($nombre, $idproduccion){
    $data=array(
        'nombre'     => $nombre,
        'id_produccion'     => $idproduccion
    );
    return $this->db->insert('locacion',$data);
  }

    public function update_locacion($id,$nombre, $idproduccion){
       $this->db->where('id',$id);
    $data=array(
        'nombre'     => $nombre,
    );
    return $this->db->update('locacion',$data);
  }

  public function update_set($id,$nombre){
       $this->db->where('id',$id);
    $data=array(
        'nombre'     => $nombre,
    );
    return $this->db->update('sets',$data);
  }

  

  public function crear_set($nombre_set,$id_locacion){
    $data=array(
        'nombre'     => $nombre_set,
        'id_locacion'     => $id_locacion,);
    return $this->db->insert('sets',$data);
  }
  public function categoria_elemento($idproduccion){
    $this->db->where('id_produccion',$idproduccion);
    $query = $this->db->get('categoria_elemento');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function rol_actores_elementos(){
    $query = $this->db->get('rol_actores_elementos');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function rol_actores_elementos_casting(){
     $query=$this->db->query("SELECT * from rol_actores_elementos order by id=4,id=2,id=3,id=1;");
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function crear_elemento($nombre_elemento,$id_elemento,$rol){
    $data=array(
        'nombre'     => $nombre_elemento,
        'rol'     => $rol,
        'id_tipo_elemento'     => $id_elemento);
    return $this->db->insert('elemento',$data);
  }

  public function elementos($id_elemento,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento, e.*,c.* FROM elemento e, categoria_elemento c
        where e.id_tipo_elemento=c.id ".$sql." and c.id=".$id_elemento);
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  /*FUNCION PARA TRAER TODOS LOS ELEMENTOS de al produccion*/
  public function elementos_all($id_produccion,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento,e.nombre, c.*, r.rol FROM elemento e
      inner join categoria_elemento c on e.id_tipo_elemento = c.id 
      left outer join rol_actores_elementos r on r.id  = e.rol  
        where e.id_tipo_elemento=c.id and c.id_produccion = ".$id_produccion." ".$sql." ORDER BY e.nombre;");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  /**/
  public function elementos_all_escena($id_produccion, $id_escena,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento,e.nombre, c.*, r.rol FROM elemento e
      inner join categoria_elemento c on e.id_tipo_elemento = c.id 
      left outer join rol_actores_elementos r on r.id  = e.rol
        where e.id_tipo_elemento=c.id and c.id_produccion = ".$id_produccion." ".$sql."   AND NOT EXISTS(SELECT ele.id FROM escenas_has_elementos ele WHERE ele.id_escena = ".$id_escena." AND id_elemento = e.id) ORDER BY e.nombre;");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  /*FUNCION PARA TRAER LOS ELEMENTOS QUE NO ESTAN ASIGNADOS A LA ESCENA*/
  public function elementos_escena($id_elemento, $id_escena,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento,e.nombre, c.*, r.rol FROM elemento e
      inner join categoria_elemento c on e.id_tipo_elemento = c.id 
      left outer join rol_actores_elementos r on r.id  = e.rol
      WHERE e.id_tipo_elemento=c.id AND c.id=".$id_elemento." ".$sql."  
      AND NOT EXISTS(SELECT ele.id FROM escenas_has_elementos ele WHERE ele.id_escena = ".$id_escena." AND id_elemento = e.id) ORDER BY e.nombre;");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  public function elementos_rol($id_elemento,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento, e.*,c.*, r.* FROM elemento e, categoria_elemento c, rol_actores_elementos r
        where e.id_tipo_elemento=c.id and r.id=e.rol ".$sql." and c.id=".$id_elemento." ORDER BY e.nombre;");
      //echo $this->db->last_query();
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }


  /*FUNCION PARA TRAER LOS ELEMENTOS QUE NO ESTAN ASIGNADOS A LA ESCENA y PERSONAJES */
  public function elementos_rol_escena($id_elemento, $id_escena,$sql){
      $query=$this->db->query("SELECT e.id as id_elemento, e.*,c.*, r.* FROM elemento e, categoria_elemento c, rol_actores_elementos r
        where e.id_tipo_elemento=c.id and r.id=e.rol ".$sql." and c.id=".$id_elemento." AND NOT EXISTS(SELECT ele.id FROM escenas_has_elementos ele WHERE ele.id_escena = ".$id_escena." AND id_elemento = e.id) ORDER BY e.nombre;");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

    public function elementos_palabra($palabra, $idproduccion,$sql){
      $query=$this->db->query("SELECT *,elemento.id as id_elemento_es, escenas_has_elementos.*, categoria_elemento.tipo AS categoria, categoria_elemento.id AS categoriaid, elemento.nombre AS nombre_elemento, elemento.id AS idelemento, elemento.id_tipo_elemento AS idtipoelemento, rol_actores_elementos.rol AS rol_elemento  
      FROM elemento
      LEFT OUTER JOIN escenas_has_elementos ON  elemento.id = escenas_has_elementos.id_elemento
      INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
      LEFT OUTER JOIN rol_actores_elementos ON elemento.rol = rol_actores_elementos.id
      where (elemento.nombre like '%".$palabra."%' or categoria_elemento.tipo like '%".$palabra."%') ".$sql." AND categoria_elemento.id_produccion = ".$idproduccion."
      GROUP BY elemento.id 
      ORDER BY elemento.nombre;");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
  }

  public function tipo_locacion(){
    $query = $this->db->get('tipo_locacion');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  
  public function dia_noche(){
    $query = $this->db->get('escenas_dias_noche');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function escena_interior_esterior(){
    $query = $this->db->get('escena_interior_esterior');
    if ($query->num_rows>0) {
        return $query->result_array();
    }else {
      return false;
    }
  }

  public function escenas_flasback(){
    $query = $this->db->get('escenas_flasback');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function escenas_foto_realizacion(){
    $query = $this->db->get('escenas_foto_realizacion');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function escenas_imagenes_archivo(){
    $query = $this->db->get('escenas_imagenes_archivo');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }
  public function escena_producida(){
    $query = $this->db->get('escena_producida');
    if ($query->num_rows>0) {
        return $query->result_array();
      }else {
        return false;
      }
  }

  public function insert_escenas($datos){
      $data=array(
          'numero_escena'=>$datos['numero_escena'],
          'id_capitulo'=>$datos['id_capitulo'],
          'id_locacion'=>$datos['id_locacion'],
          'id_set'=>$datos['id_set'],
          'dias_continuidad'=>$datos['dias_continuidad'],
          'id_tipo_locacion'=>$datos['id_tipo_locacion'],
          'id_dia_noche'=>$datos['id_dia_noche'],
          'id_interior_esterior'=>$datos['id_interior_esterior'],
          'id_flasback'=>$datos['id_flasback'],
          'id_foto_realizacion'=>$datos['id_foto_realizacion'],
          'id_imagenes_archivo'=>$datos['id_imagenes_archivo'],
          'id_toma_ubicacion'=>$datos['id_toma_ubicacion'],
          'duracion_estimada_minutos'=>$datos['duracion_estimada_minutos'],
          'duracion_estimada_segundos'=>$datos['duracion_estimada_segundos'],
          'libreto'=>$datos['libreto'],
          'id_producida'=>$datos['producida'],
          'descripcion'=>$datos['descripcion'],
          'guion'=>$datos['guion'],
          'estado'=>$datos['estado'],
          'id_magnitud'=>$datos['id_magnitud'],
          'vehiculo_background'=>$datos['vehiculo_background']
      );
     return $this->db->insert('escena',$data);
  }

    public function insert_capitulos_has_esena($datos){
      $data=array(
        'id_capitulo'=> $datos['id_capitulo'],
        'id_escena'=> $datos['id_escena']);
    return $this->db->insert('capitulos_has_escenas',$data);
  }

    public function actualizar_escenas($datos, $id){
      $data=array(
          'numero_escena'=>$datos['numero_escena'],
          'id_capitulo'=>$datos['id_capitulo'],
          'id_locacion'=>$datos['id_locacion'],
          'id_set'=>$datos['id_set'],
          'dias_continuidad'=>$datos['dias_continuidad'],
          'id_tipo_locacion'=>$datos['id_tipo_locacion'],
          'id_dia_noche'=>$datos['id_dia_noche'],
          'id_interior_esterior'=>$datos['id_interior_esterior'],
          'id_flasback'=>$datos['id_flasback'],
          'id_foto_realizacion'=>$datos['id_foto_realizacion'],
          'id_imagenes_archivo'=>$datos['id_imagenes_archivo'],
          'id_toma_ubicacion'=>$datos['id_toma_ubicacion'],
          'duracion_estimada_minutos'=>$datos['duracion_estimada_minutos'],
          'duracion_estimada_segundos'=>$datos['duracion_estimada_segundos'],
          'libreto'=>$datos['libreto'],
          'id_producida'=>$datos['producida'],
          'descripcion'=>$datos['descripcion'],
          'guion'=>$datos['guion'],
          'id_magnitud'=>$datos['id_magnitud'],
          'vehiculo_background'=>$datos['vehiculo_background'],
          'estado'=>$datos['estado']
      );
      $this->db->where('id',$id);
      return $this->db->update('escena',$data);
  }

  public function escenas_has_elementos($id_escena,$id_elemento){
    $data=array(
      'id_escena'=>$id_escena,
      'id_elemento'=>$id_elemento,
    );
    return $this->db->insert('escenas_has_elementos',$data);
  }

  public function eliminar_escenas_has_elementos($id_escena){
    $this->db->where('id_escena',$id_escena);
    return $this->db->delete('escenas_has_elementos');
  }

  public function contar_escenas($idcapitulo){
    $query=$this->db->query("SELECT COUNT(id) AS cantidad FROM escena
    where id_capitulo = ".$idcapitulo.";");
      if ($query->num_rows>0) {
         return $query->result();
      }else{
         return false;
      }
  }

  public function capitulo_inicial($id_produccion){
   $query=$this->db->query("SELECT MIN(id) as id_capitulo FROM produccion_has_capitulos 
    where id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
         return $query->result();
      }else{
         return false;
      } 
  }

  public function categoria_id($id){
    $this->db->where('id',$id);
    $query = $this->db->get('categoria_elemento');
    return $query->result();
  }

  public function nombre_locacion($nombre,$idproduccion){
    $query = $this->db->query("SELECT * FROM locacion WHERE UPPER(TRIM(nombre)) = UPPER(TRIM('".$nombre."')) AND id_produccion = ".$idproduccion.";");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function nombre_set($nombre,$id_locacion){
    $query = $this->db->query("SELECT * FROM sets WHERE UPPER(TRIM(nombre)) = UPPER(TRIM('".$nombre."')) AND id_locacion = ".$id_locacion.";");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function escena_magnitud(){
    $query = $this->db->get('magnitud_escena');
    if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }

  public function escenas_extras($id_escena_has_elementos,$cantidad){
    $data=array(
      'id_escenas_has_elementos'=>$id_escena_has_elementos,
      'cantidad'=>$cantidad,
    );
    return $this->db->insert('extras_escena',$data);
  }

  public function Vehiculos_background($id_escena_has_elementos,$cantidad,$tipo){
    $data=array(
      'id_has_elementos'=>$id_escena_has_elementos,
      'cantidad'=>$cantidad,
      'tipo_vehiculo'=>$tipo
    );
    return $this->db->insert('Vehiculos_background',$data);
  }

  public function asignar_extras_aux(){
    $query=$this->db->query("SELECT escenas_has_elementos.id AS id FROM escenas_has_elementos
                            INNER JOIN elemento ON escenas_has_elementos.id_elemento = elemento.id
                            WHERE elemento.ROL =4 AND escenas_has_elementos.id NOT IN (SELECT id_escenas_has_elementos FROM extras_escena) ORDER BY escenas_has_elementos.id;");
    if ($query->num_rows>0) {
      return $query->result();
    }else {
      return false;
    }
  }
    
}  