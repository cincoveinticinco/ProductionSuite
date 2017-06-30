<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carga_archivo extends CI_Controller {


    public function __construct (){   		
	    parent :: __construct (); 
	    $this->load->model('model_index');
	    $this->load->model('model_admin');
	}

	public function index(){
      $data['view']="carga_archivo";
      $this->load->view('carga_archivo');
	}
	
	public function cargar_archivo(){
		$rutaServidor="text";
		$rutaTemporal= $_FILES["archivo"]["tmp_name"];
		$date=date("Y-m-d H:i:s"); 
		$nombreimage= $_FILES["archivo"]["name"].'_'.$date;
		$nombre=preg_replace('/ /','_', $nombreimage);
		$rutaDestino= $rutaServidor.'/'.$nombre;
		move_uploaded_file($rutaTemporal, $rutaDestino);
		$archivo=base_url($rutaDestino);
		$file = fopen($archivo, "r") or exit("Unable to open file!");
        $cont=0;
        $cont2=1;
        $i=0;
		while(!feof($file)){
		   $datos=fgets($file);
		   if($cont>1){
		     $valor=explode(" ",$datos);
		     if($cont2==1){
		     	$tam=count($valor);
		     	$camp1=$tam-1;
		     	$camp2=$tam-2;
		     	$tiempo1=$valor[$camp2];
		     	$tiempo2=$valor[$camp1];
		     }else{
		     	/*$letra='P';
				$pos = strpos($libreto['2'],$letra);*/
		     	$tam=count($valor);
		     	$camp1=$tam-1;
		     	$total_valores[$i]=array('libreto'=>$valor[$camp1],'tiempo1'=>$tiempo2,'tiempo2'=>$tiempo1);
		     	$i++;
		     }
		     
		   }
		   if($cont2==2){
             $cont2=1;
		   }else{
             $cont2=2;
		   }
		   $cont++;
		   
		}
		$i=0;
		$lib_esant='';
		$lib_eant_varios='';
		$cont_es=0;
        $cont=0;
        $cont_cred=0;
        $totalanterior=0;
        $totalanterior_varios=0;
        asort($total_valores);
        $numero_escenas=1;
        $total_cred=0;
        $numero_escenas_varias=1;
        $cont_varias=0;
		foreach ($total_valores as $t) {
			$valor=explode("_",$t['libreto']);
			$tam=count($valor);
           if($valor[0]!="IMG" and $tam>=3 and $t['tiempo1'] and $t['tiempo2']){
				$libreto=explode("_",$t['libreto']);
				$letra='P';
				$pos = strpos($libreto['2'],$letra);
				if ($pos==false) {
					$lib=$t['libreto'];
					$lib=explode("P",$lib);
					$lib_es_varios=$lib;
					//echo $lib_es.'---'.$lib_esant;
					$h1=explode(":",$t['tiempo1']);
					$h2=explode(":",$t['tiempo2']);
					$hora=$h1['0']-$h2['0'];
					$minutos=$h1['1']-$h2['1'];
					$segundos=$h1['2']-$h2['2'];
					$milisegundos=$h1['3']-$h2['3'];
					$segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$h1['2'],$h1['3']);
					$segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
					$total=$segundos1-$segundos2;
				    $sumar_milisegundos=$this->sumar_milisegundos($total);
					//echo 'Libreto: '.$libreto_escena.' Escena:'.$escena['0'].' tiempo: '.$sumar_milisegundos.'<br>'; 
					
					if($cont_varias==0){
						$datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
						$numero_escenas_varias=$numero_escenas_varias+1;
						$cont_varias++;
					}else{
						if($lib_eant_varios==$lib['0']){
							$total=$total+$totalanterior_varios;
							$sumar_milisegundos=$this->sumar_milisegundos($total);
							$cont_varias=$cont_varias-1;
							if($cont_varias>0){
							  $numero_escenas_varias=$numero_escenas_varias+1;	
							}
	                        $datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
	                        if($cont_varias==0){
							  $numero_escenas_varias=$numero_escenas_varias+1;	
							}
	                        $cont_varias++;
						}else{
							$numero_escenas_varias=1;
							$datos_final_varias[$cont_varias]=array('Libreto' => $lib['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas_varias);
							$cont_varias++;
						}
					}
					$totalanterior_varios=$total;
					$lib_eant_varios=$lib['0'];
				} else {
					$libreto_escena=$libreto['1'];
					$escena=explode("P",$libreto['2']);
					$lib_es=$libreto_escena.$escena['0'];
					//echo $lib_es.'---'.$lib_esant;
					$h1=explode(":",$t['tiempo1']);
					$h2=explode(":",$t['tiempo2']);
					$hora=$h1['0']-$h2['0'];
					$minutos=$h1['1']-$h2['1'];
					$segundos=$h1['2']-$h2['2'];
					$milisegundos=$h1['3']-$h2['3'];
					$segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$h1['2'],$h1['3']);
					$segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
					$total=$segundos1-$segundos2;
				    $sumar_milisegundos=$this->sumar_milisegundos($total);
					//echo 'Libreto: '.$libreto_escena.' Escena:'.$escena['0'].' tiempo: '.$sumar_milisegundos.'<br>'; 
					
					if($cont==0){
						$datos_final[$cont]=array('Libreto' => $libreto_escena,'escena'=>$escena['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
						$numero_escenas=$numero_escenas+1;
						$cont++;
					}else{
						if($lib_esant==$lib_es){
							$total=$total+$totalanterior;
							$sumar_milisegundos=$this->sumar_milisegundos($total);
							$cont=$cont-1;
	                        $datos_final[$cont]=array('Libreto' => $libreto_escena,'escena'=>$escena['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
	                        $numero_escenas=$numero_escenas+1;
	                        $cont++;
						}else{
							$numero_escenas=1;
							$datos_final[$cont]=array('Libreto' => $libreto_escena,'escena'=>$escena['0'],'tiempo'=>$sumar_milisegundos,'numero_escena'=>$numero_escenas);
							$cont++;
						}
					}
					
					$totalanterior=$total;
					$lib_esant=$libreto_escena.$escena['0'];
				}
           }else{
             //$no_leidos[$i]=array('libreto'=>$t['libreto'],'tiempo1'=>$t['tiempo1'],'tiempo2'=>$t['tiempo2']);
			$valor=explode("_",$t['libreto']);
			$tam=count($valor);
			if(isset($valor[1])){
				$valor2=explode(".",$valor[1]);
			}
			$datos_cred='';
			if($tam>=2 and $valor2[0]=="CRED" and $t['tiempo1'] and $t['tiempo2']){
				$h1=explode(":",$t['tiempo1']);
				$h2=explode(":",$t['tiempo2']);
				$hora=$h1['0']-$h2['0'];
				$minutos=$h1['1']-$h2['1'];
				$segundos=$h1['2']-$h2['2'];
				$milisegundos=$h1['3']-$h2['3'];
				$segundos1=$this->horas_milisegundos($h1['0'],$h1['1'],$h1['2'],$h1['3']);
				$segundos2=$this->horas_milisegundos($h2['0'],$h2['1'],$h2['2'],$h2['3']);
				$total=$segundos1-$segundos2;
				$total_cred=$total_cred+$total;
				$sumar_milisegundos=$this->sumar_milisegundos($total);
				$datos_cred[$cont_cred]=array('creditos' => $t['libreto'],'tiempo'=>$sumar_milisegundos);
				$cont_cred++;
          
			}else{
				$letra='FX';
				//echo print_r($t).'<br>';
				//$pos = strpos($t,$letra);
				//echo $pos.'dasda<br>'; 
				$no_leidos[$i]=array('no_leidos'=>$t);
			}
             $i++; 
           }
		}
        
		$total_creditos=$this->sumar_milisegundos($total_cred);

		fclose($file);
		$data['datos_final']=$datos_final;
		$data['datos_cred']=$datos_cred;
		$data['total_creditos']=$total_creditos;
		$data['no_leidos']=$no_leidos;
		$data['datos_final_varias']=$datos_final_varias;
	  $this->load->view('resultado',$data);

	}

	public function horas_milisegundos($hora,$minutos,$segundos,$milisegundos){
		$m=$hora*60;
		$s=($m+$minutos)*60;
		$mil=(($s+$segundos)*1000)+$milisegundos;
		return $mil;
	}


	public function sumar_milisegundos($milisegundos){
       $minutos=0;
       $hora=0;
       $segundos=0;
      /* if($segundos<=10){
       	$segundos='0'.$segundos;
       }
       /*$milisegundos=$milisegundos%1000%1000;
       if($milisegundos>=30){
       	 $resultado=$milisegundos/30;

         $segundos$segundos
       }*/
      while($milisegundos>=3600000){
          $hora+=1;

          $milisegundos= $milisegundos-3600000;
      }
      while($milisegundos>=60000){
          $minutos+=1;
          $milisegundos= $milisegundos-60000;
      }

      while($milisegundos>=1000){
      	  $segundos+=1;
          $milisegundos= $milisegundos-1000;
      }
      while($milisegundos>=30){
      	  $segundos+=1;
          $milisegundos= $milisegundos-30;
      }
      while($segundos>=60){
          $minutos+=1;
          $segundos= $segundos-60;
      }
      if($hora<10){
      	$hora='0'.$hora;
      }
      if($minutos<10){
      	$minutos='0'.$minutos;
      }
      if($segundos<10){
      	$segundos='0'.$segundos;
      }
      if($milisegundos<10){
      	$milisegundos='0'.$milisegundos;
      }
     /* while($milisegundos>=1000){
      	$milisegundos=$milisegundos*10;
          echo '3';
          /*$minutos+=1;
          $segundos= $milisegundos-1000;
      }*/
	  return $hora.':'.$minutos.':'.$segundos.':'.$milisegundos;
	}



	
}