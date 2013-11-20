<?php
class modelo
{
	/*NOMBRE: login
	  PARÁMETROS: $user -> String, $contrasena -> String
	  DETALLES: Dado el String $query, que es una sentencia sql
	  Ejecuta dicha sentencia, si hay algun error, el servidor
	  devuelve al cliente ERROR=1 y DESCRIPTIONERROR=query: 
	  (El query ejecutado) - dberror: (El tipo de error que 
	  	mysql devuelve).*/
	public function login($user, $contrasena){
		if ($loggued_in_user_info = $this->DBC('SELECT * FROM persona WHERE usuario=\''.mysql_real_escape_string($user).'\' AND contrasena=\''.mysql_real_escape_string($contrasena).'\'',0)) {
			$current_cargos_persona = array();
			$cargos_persona = $this->DBC('SELECT CONCAT(CAST(pcai.cargo_id AS CHAR), CAST(pcai.area_id AS CHAR), CAST(pcai.tipo_instancia_id AS CHAR)) AS id, pcai.*,p.* FROM persona_cargo_area_instancia pcai, permisos p WHERE pcai.persona_id='.$loggued_in_user_info[0]['id'].' AND p.tipo_instancia_id=pcai.tipo_instancia_id AND p.area_id=pcai.area_id and p.cargo_id=pcai.cargo_id ORDER BY p.nivel ASC',0);
			for ($i=0; $i < count($cargos_persona); $i++) { 
				if(!$cargos_persona[$i]['fecha_fin']||$cargos_persona[$i]['fecha_fin']>date())
					$current_cargos_persona[]=$this->getInfoData($cargos_persona[$i]);
			}
			session_start();
			$loggued_in_user_info[0]['name']=$loggued_in_user_info[0]['nombre'].' '.$loggued_in_user_info[0]['apellido'];
			$_SESSION["user"] = $loggued_in_user_info[0];
			$_SESSION["cargos"]=$current_cargos_persona;
			$_SESSION["current_cargo"]=$current_cargos_persona[0];
			header('Location: ../main.php');
			//return array('error'=>0, 'data'=>array('user'=>$loggued_in_user_info[0],'cargos'=>$current_cargos_persona));
		}else{
			return array('error'=>2,'descriptionerror'=>'Combinación Email/Password incorrecta.');
		}
	}
	public function change($pos){
		$_SESSION["current_cargo"]=$_SESSION["cargos"][$pos];
		header('Location: ../main.php');
	}
	public function logout(){
		session_destroy();
		header('Location: ../index.php');
	}
	private function getInfoData($cargo_persona){
		switch ($cargo_persona['nivel']) {
			case 1://SUPER ADMIN
				$info=array_merge($cargo_persona,$this->DBC('SELECT c.nombre AS nombre_cargo, c.descripcion AS descripcion_cargo FROM cargo c WHERE c.id='.$cargo_persona['cargo_id'],0)[0]);
				return array('info'=>$info,
					'data'=>array(
						'cargo'=>$this->DBC('SELECT * FROM cargo ORDER BY nombre ASC ',0),
						'area'=>$this->DBC('SELECT * FROM area ORDER BY nombre ASC ',0),
						'tipos_instancia'=>$this->DBC('SELECT * FROM tipo_instancia ORDER BY nombre ASC ',0),
						'permisos'=>array(
							'info'=>array('cargos'=>$this->DBC('SELECT * FROM cargo ORDER BY nombre ASC ',0),
										  'areas'=>$this->DBC('SELECT * FROM area ORDER BY nombre ASC ',0),
										  'tipos'=>$this->DBC('SELECT * FROM tipo_instancia ORDER BY id ASC ',0)),
							'data'=>$this->getPermisos()),
						'nucleo'=>array(
							'info'=>array('personas'=>$this->DBC('SELECT * FROM persona ORDER BY nombre ASC ',0)),
							'data'=>$this->DBC('SELECT pcai.*,p.* FROM persona pe, persona_cargo_area_instancia pcai, permisos p WHERE p.nivel=2 AND p.tipo_instancia_id=pcai.tipo_instancia_id AND p.area_id=pcai.area_id AND p.cargo_id=pcai.cargo_id AND pcai.fecha_fin<NOW() ORDER BY pe.nombre ASC',0))
						));
				break;
			case 8://ANIMADOR
				$result=mysql_query('select c.* from cargo c where c.id='.$cargo_persona['cargo_id'])
						or die(
							print json_encode( array('error'=>4,'descriptionerror'=>'dberror: Invalid '.
							'query GetInfoData1-> '. mysql_error()))
						);
				$info=array();
				$info=array_merge($cargo_persona,mysql_fetch_assoc($result));
				mysql_free_result($result);
				//GET RECURSOS DE APOSTOLADO
				$result1=mysql_query('select ip.*, ti.tipo as tipo_tipo, ti.nombre as tipo_nombre,
					ti.descripcion as tipo_descripcion,ti.logo as tipo_logo,ti.dinamismo as tipo_dinamismo,
					ti.nivel_arbol as tipo_nivel,ti.campos as tipo_campos
					from instancia_permanencia ip, tipo_instancia ti 
					where ip.id='.$cargo_persona['idinstancia_permanencia'].' 
					and ip.tipo_instancia_id=ti.id')
					or die(
						print json_encode( array('error'=>6,'descriptionerror'=>'dberror: Invalid '.
							'query getInfoData3-> '. mysql_error()))
					);
				$asociaciones = array();
				$asoInfo = mysql_fetch_assoc($result1);
				return array('info'=>$info,
					'data'=>array(
						'agrupacion'=>array('info'=>$asoInfo,'cargos'=>$this->getCargos(array('instancia'=>'asociacion')),'miembros'=>$this->getMiembrosAsociacion($asoInfo['id'])),
						'registros'=>$this->getRegistrosAsociacion($asoInfo['id']),
						'recursos'=>'',
						'cronograma'=>''));
				break;
			default:
				if($cargo_persona['idinstancia_permanencia']==0){//GENERAL
				}else{//DE CENTRO O ESPECÍFICO
					$result=mysql_query('SELECT c.id AS cargo_id,c.nombre AS nombre,c.titulo AS titulo,
						c.instancia AS cargo_instancia,a.id AS area_id,a.nombre AS nombre_area,
						ip.nombre AS nombre_permanencia,ip.iniciales AS iniciales_permanencia,ti.id AS tipo_id_permanencia,
						ti.tipo AS tipo_permanencia,ti.nombre AS tipo_nombre_permanencia,
						ti.dinamismo AS tipo_dinamismo_permanencia,ti.nivel_arbol AS tipo_nivel_permanencia,
						ti.campos AS tipo_campos_permanencia,centro.id AS centro_id,centro.nombre AS centro_nombre,
						centro.iniciales AS centro_iniciales,id.id AS idinstancia_despliegue,id.nombre AS nombre_despliegue,
						ti1.id AS tipo_id_despliegue,ti1.tipo AS tipo_despliegue,ti1.nombre AS tipo_nombre_despliegue,
						ti1.dinamismo AS tipo_dinamismo_despliegue,ti1.nivel_arbol AS tipo_nivel_despliegue,
						ti1.campos AS tipo_campos_despliegue
						FROM (persona p,cargo c,area a)
						LEFT JOIN instancia_permanencia ip ON ip.id='.$cargo_persona['idinstancia_permanencia'].'
						LEFT JOIN tipo_instancia ti ON ti.id=ip.tipo_instancia_id
						LEFT JOIN instancia_permanencia centro ON centro.id=ip.idinstancia_permanencia
						LEFT JOIN instancia_despliegue id ON id.id='.$cargo_persona['idinstancia_despliegue'].'
						LEFT JOIN tipo_instancia ti1 ON ti1.id=id.tipo_instancia_id
						where p.id='.$cargo_persona['idpersona'].' and c.id='.$cargo_persona['cargo_id'].' 
						and a.id='.$cargo_persona['area_id'])
						or die(
							print json_encode( array('error'=>5,'descriptionerror'=>'dberror: Invalid '.
								'query getInfoData2-> '. mysql_error()))
						);
					$info=array();
					$info=array_merge($cargo_persona,mysql_fetch_assoc($result));
					switch ($cargo_persona['tipo_menu']) {
						case 2://ENCARGADO GENERAL
							$result1=mysql_query('select ip.*, ti.tipo as tipo_tipo, ti.nombre as tipo_nombre,
								ti.descripcion as tipo_descripcion,ti.logo as tipo_logo,ti.dinamismo as tipo_dinamismo,
								ti.nivel_arbol as tipo_nivel,ti.campos as tipo_campos
								from instancia_permanencia ip, tipo_instancia ti 
								where ip.idinstancia_permanencia='.$cargo_persona['idinstancia_permanencia'].' 
								and ip.tipo_instancia_id=ti.id')
								or die(
									print json_encode( array('error'=>6,'descriptionerror'=>'dberror: Invalid '.
										'query getInfoData3-> '. mysql_error()))
								);
							$asociaciones = array();
							while ($asoInfo = mysql_fetch_assoc($result1)) {
								$asociaciones[]=array('info'=>$asoInfo,
									'miembros'=>$this->getMiembrosAsociacion($asoInfo['id']),
									'registros'=>$this->getRegistrosAsociacion($asoInfo['id']));
							}
							mysql_free_result($result);
							mysql_free_result($result1);
							return array('info'=>$info,
								'data'=>array(
									'asociaciones'=>array(
										'info'=>array(
											'asociaciones_tipo'=>$this->getTipos(array('tipo'=>'asociación')),
											'asociaciones_cargos'=>$this->getCargos(array('instancia'=>'asociacion'))),
										'data'=>$asociaciones),
									'recursos'=>$this->getRecursos(),
									'actividades'=>array('info'=>array('actividades_tipo'=>$this->getTipos()),
										'data'=>$this->getDespliegues())
									)
								);
							break;
						case 3://ENCARGADO INSTRUCCIÓN
							break;
						case 4://ENCARGADO ESPIRITUALIDAD
							break;
						case 5://ENCARGADO APOSTOLADO
							break;
						case 6://ENCARGADO COMUNICACIONES
							break;
						case 7://ENCARGADO TEMPORALIDADES
							break;
					}
				}
				break;
		}
	}
	private function getPermisos(){
		$permisos_orden= array();
		$permisos = $this->DBC('SELECT * FROM permisos ORDER BY id_tipo_instancia ASC',0);
		for ($i=0; $i < count($permisos); $i++) { 		
			if(count($permisos_orden)==0){
				$tmp=array('id'=>$permisos[$i]['cargo_id']."-".$permisos[$i]['area_id']."-".$permisos[$i]['tipo_instancia_id'], 'cargo_id'=>$permisos[$i]['cargo_id'],'area_id'=>$permisos[$i]['area_id'], 'tipo_instancia_id'=>$permisos[$i]['tipo_instancia_id']);
				if($permisos[$i]['id_tipo_instancia']==0)
					//$tmp['tipo_menu']=$permiso['tipo_menu'];
					$tmp['nivel']=$permisos[$i]['nivel'];
				else
					$tmp['permisos'][]=array('permiso'=>$permisos[$i]['permiso'],'tipo'=>$permisos[$i]['id_tipo_instancia']);
				array_push($permisos_orden,$tmp);
			}else{
				$done=0;
				for($j=0;$j<count($permisos_orden);$j++){
					if($permisos_orden[$j]['id']==$permisos[$i]['cargo_id']."-".$permisos[$i]['area_id']."-".$permisos[$i]['tipo_instancia_id']){
						if($permisos[$i]['id_tipo_instancia']==0)
							//$permisos[$i]['tipo_menu']=$permiso['tipo_menu'];
							$permisos_orden[$j]['nivel']=$permisos[$i]['nivel'];
						else{
							$permisos_orden[$j]['permisos'][]=array('permiso'=>$permisos[$i]['permiso'],'tipo'=>$permisos[$i]['id_tipo_instancia']);
						}
						$done=1;
					}
				}
				if($done==0){
					$tmp=array('id'=>$permisos[$i]['cargo_id']."-".$permisos[$i]['area_id']."-".$permisos[$i]['tipo_instancia_id'],'cargo_id'=>$permisos[$i]['cargo_id'],'area_id'=>$permisos[$i]['area_id'],'tipo_instancia_id'=>$permisos[$i]['tipo_instancia_id']);
					if($permisos[$i]['id_tipo_instancia']==0)
						$tmp['nivel']=$permisos[$i]['nivel'];
					else
						$tmp['permisos'][]=array('permiso'=>$permisos[$i]['permiso'],'tipo'=>$permisos[$i]['id_tipo_instancia']);
					array_push($permisos_orden,$tmp);
				}
			}
		}
		return $permisos_orden;
	}
	public function save($tipo, $parametros){
		session_start();
		switch ($tipo) {
			case 'permisos':
				if($parametros["id"]){
				 	list($cargoid,$areaid,$tipo_instanciaid) = explode('-',$parametros["id"]);
				 	$ids= explode(',',$parametros["ids"]);
				 	$permisos = explode(',',$parametros["permisos"]);	
				}
				$band=0;
			 	for ($i=0; $i<count($permisos); $i++){
			 		if($rowinfo = $this->DBC('SELECT * FROM permisos WHERE cargo_id='.$cargoid.' AND area_id='.$areaid.' AND tipo_instancia_id='.$tipo_instanciaid.' AND id_tipo_instancia='.$ids[$i],0)){
			 			if($parametros["id_tipo_instancia"]==0){
			 				$this->DBC('UPDATE permisos SET cargo_id='.$cargoid.', area_id='.$areaid.', tipo_instancia_id='.$tipo_instanciaid.',  nivel='.$parametros["nivel"].' WHERE cargo_id='.$cargoid.' AND area_id='.$areaid.' AND tipo_instancia_id='.$tipo_instanciaid,1);	
			 			}else{
			 				$this->DBC('UPDATE permisos SET cargo_id='.$cargoid.', area_id='.$areaid.', tipo_instancia_id='.$tipo_instanciaid.' , permiso=\''.$permisos[$i].'\', id_tipo_instancia='.$ids[$i].',  nivel='.$parametros["nivel"].' WHERE cargo_id='.$cargoid.' AND area_id='.$areaid.' AND tipo_instancia_id='.$tipo_instanciaid.' AND id_tipo_instancia='.$ids[$i],1);	
			 			}
						
			 		}else{
			 			if($band==0&&$parametros["nivel"]){
			 				$this->DBC('INSERT INTO permisos SET cargo_id='.$cargoid.' , area_id='.$areaid.' , tipo_instancia_id='.$tipo_instanciaid.' , permiso="" ,nivel='.$parametros["nivel"],1);
			 				$band=1;
			 			}
			 			$this->DBC('INSERT INTO permisos SET cargo_id='.$cargoid.' , area_id='.$areaid.' , tipo_instancia_id='.$tipo_instanciaid.' , permiso=\''.$permisos[$i].'\' , id_tipo_instancia='.$ids[$i].' ,  nivel='.$parametros["nivel"],1);
			 		}
				}
				return array('error'=>0, 'descriptionerror'=>'dberror: '.mysql_error());

				break;
			case 'persona':
		        if($parametros["id"])
		           return $this->DBC('UPDATE persona SET foto=\''.$parametros["foto"].'\' , nombre=\''.$parametros["nombre"].'\', apellido=\''.$parametros["apellido"].'\' , ciudad=\''.$parametros["ciudad"].'\' , sexo=\''.$parametros["sexo"].'\', edad=\''.$parametros["edad"].'\' , nacimiento=\''.$parametros["nacimiento"].'\' , domicilio=\''.$parametros["domicilio"].'\', estudio=\''.$parametros["estudio"].'\' , institucion=\''.$parametros["institucion"].'\' , telefono=\''.$parametros["telefono"].'\', claro=\''.$parametros["claro"].'\' , movi=\''.$parametros["movi"].'\' , pin=\''.$parametros["pin"].'\', email=\''.$parametros["email"].'\' , fb=\''.$parametros["fb"].'\' , tw=\''.$parametros["tw"].'\', user=\''.$parametros["user"].'\' , pass=\''.$parametros["pass"].'\' WHERE id='.$parametros["id"],1);
		         else        
		           return $this->DBC('INSERT INTO persona SET foto=\''.$parametros["foto"].'\' , nombre=\''.$parametros["nombre"].'\', apellido=\''.$parametros["apellido"].'\' , ciudad=\''.$parametros["ciudad"].'\' , sexo=\''.$parametros["sexo"].'\', edad=\''.$parametros["edad"].'\' , nacimiento=\''.$parametros["nacimiento"].'\' , domicilio=\''.$parametros["domicilio"].'\', estudio=\''.$parametros["estudio"].'\' , institucion=\''.$parametros["institucion"].'\' , telefono=\''.$parametros["telefono"].'\', claro=\''.$parametros["claro"].'\' , movi=\''.$parametros["movi"].'\' , pin=\''.$parametros["pin"].'\', email=\''.$parametros["email"].'\' , fb=\''.$parametros["fb"].'\' , tw=\''.$parametros["tw"].'\', user=\''.$parametros["user"].'\' , pass=\''.$parametros["pass"].'\'',1);
		     	break;
			case 'tipos_instancia':
				if ($parametros["id"])
					return $this->DBC('UPDATE tipo_instancia SET logo=\''.$parametros["logo"].'\' , clasificacion=\''.$parametros["clasificacion"].'\' , nombre=\''.$parametros["nombre"].'\' , descripcion=\''.$parametros["descripcion"].'\' WHERE id='.$parametros["id"],1);
				else				
					return $this->DBC('INSERT INTO tipo_instancia SET logo=\''.$parametros["logo"].'\' , clasificacion=\''.$parametros["clasificacion"].'\', 
					nombre=\''.$parametros["nombre"].'\' , descripcion=\''.$parametros["descripcion"].'\'',1);
				break;
			default:
				if ($parametros["id"])
					return $this->DBC('UPDATE '.$tipo.' SET nombre=\''.$parametros["nombre"].'\' , descripcion=\''.$parametros["descripcion"].'\' WHERE id='.$parametros["id"],1);
				else
					return $this->DBC('INSERT INTO tipo_instancia SET logo=\''.$parametros["logo"].'\' , clasificacion=\''.$parametros["clasificacion"].'\' , nombre=\''.$parametros["nombre"].'\' , descripcion=\''.$parametros["descripcion"].'\'',1);
				break;
		}
	}

	public function delete($tipo, $id){
		session_start();
		$flag=0;
        for ($i = 0; $i < sizeof($_SESSION["current_cargo"]["data"][$tipo])&&$flag==0; $i++) {
          if($_SESSION["current_cargo"]["data"][$tipo][$i]["id"]==$id){
            unset($_SESSION["current_cargo"]["data"][$tipo][$i]);
            $flag=1;
          }
        }
		switch ($tipo) {
			case 'tipos_instancia':
				return $this->DBC('DELETE FROM tipo_instancia WHERE id='.$id,1);
				break;
			default:

				return $this->DBC('DELETE FROM '.$tipo.' WHERE id='.$id,1);
				break;
		}
	}
	/*NOMBRE: DBC
	  PARÁMETROS: $query -> String
	  DETALLES: Dado el String $query, que es una sentencia sql
	  Ejecuta dicha sentencia, si hay algun error, el servidor
	  devuelve al cliente ERROR=1 y DESCRIPTIONERROR=query: 
	  (El query ejecutado) - dberror: (El tipo de error que 
	  	mysql devuelve).
	  	Caso contrario la función devuelve un arreglo asociativo 
	  	como respuesta del query ejecutado. */
	private function DBC($query,$insert){
		$result = mysql_query($query)
		or die(
			print json_encode(array('error'=>1,'descriptionerror'=>'query: '.$query.' - dberror: '.mysql_error()))
		);
		if($insert==0){
			$rows = array();
			while ($row = mysql_fetch_assoc($result)) {
				$rows[]=$row;
			}
			mysql_free_result($result);
			return $rows;
		}
		return  array('id' => mysql_insert_id());
	}
}
?>